<?php
declare(strict_types=1);

/**
 * Generates typed readonly Row classes from the BCOE&M SQL baseline schema.
 *
 * Usage: php tools/generate_row_types.php
 * Reads:  sql/bcoem_baseline_3.0.X.sql
 * Writes: src/Domain/<Table>Row.php (one readonly class per table)
 *
 * SQL → PHP type map:
 *   int/tinyint/smallint/mediumint/bigint       → int
 *   varchar/text/char/tinytext/mediumtext/longtext → string
 *   float/decimal/double                        → float
 *   date/datetime/timestamp                     → string (epoch per #1716 convention)
 *   enum('Y','N')                               → 'Y'|'N' (string literal union)
 *   nullable (no NOT NULL)                      → ?T
 *
 * fromArray() casts raw mysqli values (strings) to the declared PHP type,
 * because mysqli returns everything as string unless the native flag is set.
 * Only `id` columns are NOT NULL; everything else is nullable in this schema.
 */

const SCHEMA_FILE = __DIR__ . '/../sql/bcoem_baseline_3.0.X.sql';
const OUTPUT_DIR  = __DIR__ . '/../src/Domain';

$sql = file_get_contents(SCHEMA_FILE);
if ($sql === false) {
    fwrite(STDERR, "Cannot read schema: " . SCHEMA_FILE . "\n");
    exit(1);
}

preg_match_all(
    '/CREATE TABLE `baseline_([a-z_]+)` \((.*?)\) ENGINE=/s',
    $sql,
    $matches,
    PREG_SET_ORDER
);

if (count($matches) === 0) {
    fwrite(STDERR, "No CREATE TABLE blocks found in schema.\n");
    exit(1);
}

$totalCols = 0;
foreach ($matches as $m) {
    $tableName = $m[1];
    $columns   = parseColumns($m[2]);

    if ($columns === []) {
        fwrite(STDERR, "WARN: table {$tableName} has no parseable columns\n");
        continue;
    }

    $className = tableToClassName($tableName);
    $php = renderClass($className, $tableName, $columns);
    $outFile = OUTPUT_DIR . '/' . $className . 'Row.php';

    file_put_contents($outFile, $php);
    $totalCols += count($columns);
    echo "generated {$outFile} (" . count($columns) . " cols)\n";
}

echo "\n" . count($matches) . " row classes, {$totalCols} columns total\n";

/**
 * @return list<array{name: string, sqlType: string, phpType: string, nullable: bool}>
 */
function parseColumns(string $block): array
{
    $columns = [];

    foreach (explode("\n", $block) as $line) {
        $line = trim($line);
        if ($line === '') {
            continue;
        }
        // Skip keys / constraints
        if (str_starts_with($line, 'PRIMARY KEY')
            || str_starts_with($line, 'KEY')
            || str_starts_with($line, 'UNIQUE')
            || str_starts_with($line, 'CONSTRAINT')
        ) {
            continue;
        }
        if (!preg_match('/^`([a-zA-Z0-9_]+)`\s+([a-z0-9]+)(?:\([^)]*\))?/', $line, $m)) {
            continue;
        }

        $name     = $m[1];
        $sqlType  = strtolower($m[2]);
        $nullable = !preg_match('/NOT NULL/', $line);

        $enumValues = null;
        if ($sqlType === 'enum' && preg_match('/enum\((.+?)\)/', $line, $em)) {
            preg_match_all("/'((?:[^']|'')*)'/", $em[1], $vals);
            $enumValues = array_map(
                static fn(string $v): string => str_replace("''", "'", $v),
                $vals[1]
            );
        }

        $columns[] = [
            'name'     => $name,
            'sqlType'  => $sqlType,
            'phpType'  => mapSqlType($sqlType, $enumValues),
            'nullable' => $nullable,
        ];
    }

    return $columns;
}

/**
 * @param list<string>|null $enumValues
 */
function mapSqlType(string $sqlType, ?array $enumValues): string
{
    if ($enumValues !== null) {
        return "'" . implode("'|'", $enumValues) . "'";
    }

    return match ($sqlType) {
        'int', 'tinyint', 'smallint', 'mediumint', 'bigint' => 'int',
        'varchar', 'text', 'char', 'tinytext', 'mediumtext', 'longtext' => 'string',
        'float', 'decimal', 'double' => 'float',
        'date', 'datetime', 'timestamp' => 'string',
        default => 'string',
    };
}

function tableToClassName(string $table): string
{
    return str_replace(' ', '', ucwords(str_replace('_', ' ', $table)));
}

/**
 * @param list<array{name: string, sqlType: string, phpType: string, nullable: bool}> $columns
 */
function renderClass(string $className, string $tableName, array $columns): string
{
    $params = [];
    $fromArray = [];
    $hasEnum = false;

    foreach ($columns as $col) {
        $propName = camelCase($col['name']);
        $phpType  = $col['nullable'] ? '?' . $col['phpType'] : $col['phpType'];
        $params[] = "        public readonly {$phpType} \${$propName},";

        if ($col['name'] === 'id' && !$col['nullable']) {
            // Non-nullable PK: cast directly, always present in fetched rows.
            $fromArray[] = "            {$propName}: (int) \$row['{$col['name']}'],";
        } elseif (str_starts_with($col['phpType'], "'")) {
            $hasEnum = true;
            $fromArray[] = "            {$propName}: self::yn(\$row, '{$col['name']}'),";
        } else {
            $cast = $col['phpType'] === 'int' ? '(int)' : ($col['phpType'] === 'float' ? '(float)' : '(string)');
            $fromArray[] = "            {$propName}: isset(\$row['{$col['name']}']) ? {$cast} \$row['{$col['name']}'] : null,";
        }
    }

    $paramBlock   = implode("\n", $params);
    $fromArrayBlock = implode("\n", $fromArray);
    $ynHelper = $hasEnum ? <<<'PHP_WRAP'
    
        /**
         * Cast a raw DB value to a 'Y'|'N' flag. Assert guards the invariant;
         * PHPStan narrows the type via the in_array assertion.
         *
         * @param array<string, mixed> $row
         */
        private static function yn(array $row, string $key): 'Y'|'N'|null
        {
            if (!isset($row[$key])) {
                return null;
            }
            $v = (string) $row[$key];
            assert(in_array($v, ['Y', 'N'], true));
            return $v;
        }
    PHP_WRAP : '';

    return <<<PHP
<?php
declare(strict_types=1);

namespace BCOEM\\Domain;

/**
 * Typed row for table `{$tableName}`.
 * Generated by tools/generate_row_types.php — do not edit by hand.
 *
 * @immutable
 */
final readonly class {$className}Row
{
    public function __construct(
{$paramBlock}
    ) {}

    /**
     * Build a row from a raw mysqli/MysqliDb assoc result.
     * Values are cast from DB strings to the declared PHP types.
     *
     * @param array<string, mixed> \$row
     */
    public static function fromArray(array \$row): self
    {
        return new self(
{$fromArrayBlock}
        );
    }
{$ynHelper}
}

PHP;
}

function camelCase(string $snake): string
{
    return lcfirst(str_replace(' ', '', ucwords(str_replace('_', ' ', $snake))));
}
