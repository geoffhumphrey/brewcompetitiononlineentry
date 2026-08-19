<?php

declare(strict_types=1);

/**
 * Test bootstrap.
 *
 * Provides a minimal, isolated boot context for unit tests:
 * - defines ROOT (and derived path constants) without executing the app's
 *   own paths.php, so tests never touch site/config.php or the database
 * - stubs sterilize() (defined in lib/common.lib.php) so tests can run
 *   without loading the full lib stack
 * - starts a session in a disposable save path
 */

if (!defined('ROOT')) {
    define('ROOT', dirname(__DIR__) . DIRECTORY_SEPARATOR);
}

define('ADMIN', ROOT . 'admin' . DIRECTORY_SEPARATOR);
define('SSO', ROOT . 'sso' . DIRECTORY_SEPARATOR);
define('EVALS', ROOT . 'eval' . DIRECTORY_SEPARATOR);
define('CLASSES', ROOT . 'classes' . DIRECTORY_SEPARATOR);
define('CONFIG', ROOT . 'site' . DIRECTORY_SEPARATOR);
define('DB', ROOT . 'includes' . DIRECTORY_SEPARATOR . 'db' . DIRECTORY_SEPARATOR);
define('IMAGES', ROOT . 'images' . DIRECTORY_SEPARATOR);
define('INCLUDES', ROOT . 'includes' . DIRECTORY_SEPARATOR);
define('LIB', ROOT . 'lib' . DIRECTORY_SEPARATOR);
define('MODS', ROOT . 'mods' . DIRECTORY_SEPARATOR);
define('PROCESS', ROOT . 'includes' . DIRECTORY_SEPARATOR . 'process' . DIRECTORY_SEPARATOR);
define('SECTIONS', ROOT . 'sections' . DIRECTORY_SEPARATOR);
define('SETUP', ROOT . 'setup' . DIRECTORY_SEPARATOR);
define('UPDATE', ROOT . 'update' . DIRECTORY_SEPARATOR);
define('OUTPUT', ROOT . 'output' . DIRECTORY_SEPARATOR);
define('USER_IMAGES', ROOT . 'user_images' . DIRECTORY_SEPARATOR);
define('USER_DOCS', ROOT . 'user_docs' . DIRECTORY_SEPARATOR);
define('USER_TEMP', ROOT . 'user_temp' . DIRECTORY_SEPARATOR);
define('LANG', ROOT . 'lang' . DIRECTORY_SEPARATOR);
define('DEBUGGING', ROOT . 'includes' . DIRECTORY_SEPARATOR . 'debug' . DIRECTORY_SEPARATOR);
define('AJAX', ROOT . 'ajax' . DIRECTORY_SEPARATOR);
define('PUB', ROOT . 'pub' . DIRECTORY_SEPARATOR);

define('HOSTED', false);
define('NHC', false);
define('SINGLE', false);
define('EVALUATION', true);

// -- stubs ---------------------------------------------------------------

// sterilize() + is_https() live in lib/sanitize.lib.php (extracted from
// paths.php); load the real production implementations so unit tests
// exercise production code rather than a re-implementation.
require_once LIB . 'sanitize.lib.php';

// MysqliDb is not PSR-4 autoloadable (vendor file); load it so the typed
// data layer (src/Connection.php, repositories) works under PHPUnit.
// -- session --------------------------------------------------------------
// Start the session BEFORE loading MysqliDb: the vendor class emits a PHP 8.4
// deprecation notice at definition time (insertMulti() implicit nullable),
// which would count as output and break session_start() ("headers already
// sent").

if (session_status() !== PHP_SESSION_ACTIVE) {
    $tmp = sys_get_temp_dir() . '/bcoem-test-sessions-' . getmypid();
    if (!is_dir($tmp)) {
        mkdir($tmp, 0700, true);
    }
    session_save_path($tmp);
    session_start();
}

// -- vendor libs ----------------------------------------------------------

// MysqliDb is not PSR-4 autoloadable (vendor file); load it so the typed
// data layer (src/Connection.php, repositories) works under PHPUnit. The
// known insertMulti() deprecation is silenced only for this require.
set_error_handler(static fn(int $severity, string $message, string $file): bool => str_contains($message, 'Implicitly marking parameter $dataKeys as nullable'));
try {
    require_once CONFIG . 'MysqliDb.php';
} finally {
    restore_error_handler();
}

require_once dirname(__DIR__) . '/vendor/autoload.php';
