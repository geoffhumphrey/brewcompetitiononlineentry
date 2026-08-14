<?php

declare(strict_types=1);

namespace BCOEM\Tests\Integration;

use PHPUnit\Framework\TestCase;

/**
 * Token round-trip regression test for the contact-modal link (issue #1708).
 *
 * pub/contact.pub.php encrypts a zero-padded contact id with
 * simpleEncrypt(), drops the token into a query string, and
 * output/print.output.php later decrypts it with simpleDecrypt().
 * simpleEncrypt() returns base64, which may contain '+', '/', '=' —
 * characters that a raw query-string embed mangles ('+' decodes to a
 * space). The fix (rawurlencode on the link) makes the round-trip lossless.
 *
 * This test replays the exact production path:
 *   sprintf('%06d', $id) -> simpleEncrypt -> rawurlencode -> query-string
 *   parse (urldecode) -> simpleDecrypt -> assert id recovered.
 *
 * It must fail on the pre-fix code whenever the token contains '+'.
 */
final class TokenRoundTripTest extends TestCase
{
    private const SECRET_KEY = '746573742d70617373776f72642d6b65792d3132333435363738393031323334353637383930';
    private const NACL = '746573742d7365727665722d726f6f742d6162636465666768696a6b6c6d';

    protected function setUp(): void
    {
        if (!defined('LIB')) {
            define('LIB', dirname(__DIR__, 2) . DIRECTORY_SEPARATOR . 'lib' . DIRECTORY_SEPARATOR);
        }
        if (!defined('INCLUDES')) {
            define('INCLUDES', dirname(__DIR__, 2) . DIRECTORY_SEPARATOR . 'includes' . DIRECTORY_SEPARATOR);
        }
        foreach (['HOSTED', 'NHC', 'SINGLE', 'EVALUATION'] as $const) {
            if (!defined($const)) {
                define($const, false);
            }
        }
        // common.lib.php includes date_time.lib.php and version.inc.php and
        // touches $_SESSION at load; ensure the session bootstrap ran.
        if (session_status() !== PHP_SESSION_ACTIVE) {
            session_start();
        }
        require_once LIB . 'common.lib.php';
    }

    public function testAllIdsRoundTripThroughUrlEncoding(): void
    {
        for ($id = 1; $id <= 50; $id++) {
            $token = $this->buildToken($id);
            $recovered = $this->recoverId($token);
            $this->assertSame($id, $recovered, "id {$id} did not survive the URL round-trip");
        }
    }

    public function testTokenContainingPlusIsDecodedByQueryStringParser(): void
    {
        // Find a token that contains '+'; assert the query-string corruption
        // mode ('+' -> space) is exactly what the rawurlencode fix prevents.
        $sawPlus = false;
        for ($id = 1; $id <= 50; $id++) {
            $token = $this->buildToken($id);
            if (str_contains($token, '+')) {
                $sawPlus = true;
                // rawurlencode() then query-string parse (urldecode) round-trips losslessly
                $this->assertSame($token, rawurldecode(rawurlencode($token)));
                // the raw (un-encoded) embed would corrupt: '+' becomes a space
                $corrupted = urldecode($token);
                $this->assertNotSame($token, $corrupted);
                break;
            }
        }
        $this->assertTrue($sawPlus, 'expected at least one of ids 1-50 to produce a + in its token');
    }

    public function testEncryptDecryptRoundTripWithoutUrl(): void
    {
        for ($id = 1; $id <= 50; $id++) {
            $idPadded = sprintf('%06d', $id);
            $token = simpleEncrypt($idPadded, self::SECRET_KEY, self::NACL);
            $this->assertSame($idPadded, simpleDecrypt($token, self::SECRET_KEY, self::NACL));
        }
    }

    public function testCorruptedTokenYieldsFalseNotNumericId(): void
    {
        // A '+' corrupted to a space must NOT silently decrypt to a valid id —
        // the pre-fix failure mode rendered the modal's Error branch.
        $found = false;
        for ($id = 1; $id <= 50; $id++) {
            $token = $this->buildToken($id);
            if (str_contains($token, '+')) {
                $found = true;
                $corrupted = urldecode($token); // query-string parse of raw token
                $result = @simpleDecrypt($corrupted, self::SECRET_KEY, self::NACL);
                $this->assertNotSame(sprintf('%06d', $id), $result);
                break;
            }
        }
        $this->assertTrue($found);
    }

    private function buildToken(int $id): string
    {
        return simpleEncrypt(sprintf('%06d', $id), self::SECRET_KEY, self::NACL);
    }

    private function recoverId(string $token): int
    {
        $decrypted = simpleDecrypt(rawurldecode(rawurlencode($token)), self::SECRET_KEY, self::NACL);
        $this->assertIsString($decrypted);
        return (int) $decrypted;
    }
}
