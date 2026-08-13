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

if (!function_exists('sterilize')) {
    /**
     * Test stub of lib/common.lib.php sterilize().
     * Mirrors the production contract: escape a value for DB insertion.
     */
    function sterilize(string $str): string
    {
        return addslashes($str);
    }
}

if (!function_exists('prep_redirect_link')) {
    function prep_redirect_link(string $link): string
    {
        return $link;
    }
}

// -- session --------------------------------------------------------------

if (session_status() !== PHP_SESSION_ACTIVE) {
    $tmp = sys_get_temp_dir() . '/bcoem-test-sessions-' . getmypid();
    if (!is_dir($tmp)) {
        mkdir($tmp, 0700, true);
    }
    session_save_path($tmp);
    session_start();
}

require_once dirname(__DIR__) . '/vendor/autoload.php';
