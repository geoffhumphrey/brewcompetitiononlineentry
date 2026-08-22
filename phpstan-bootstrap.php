<?php

/**
 * PHPStan-only bootstrap.
 *
 * Mirrors the constants and top-level functions paths.php defines, without
 * its side effects (opening a live DB connection, starting a session) —
 * those aren't meaningful during static analysis and would just fail outside
 * a real request. Never loaded by the actual application; safe to change
 * freely without any runtime impact.
 */

define('ROOT', __DIR__.DIRECTORY_SEPARATOR);
define('ADMIN', ROOT.'admin'.DIRECTORY_SEPARATOR);
define('SSO', ROOT.'sso'.DIRECTORY_SEPARATOR);
define('EVALS', ROOT.'eval'.DIRECTORY_SEPARATOR);
define('CLASSES', ROOT.'classes'.DIRECTORY_SEPARATOR);
define('CONFIG', ROOT.'site'.DIRECTORY_SEPARATOR);
define('DB', ROOT.'includes'.DIRECTORY_SEPARATOR.'db'.DIRECTORY_SEPARATOR);
define('IMAGES', ROOT.'images'.DIRECTORY_SEPARATOR);
define('INCLUDES', ROOT.'includes'.DIRECTORY_SEPARATOR);
define('LIB', ROOT.'lib'.DIRECTORY_SEPARATOR);
define('MODS', ROOT.'mods'.DIRECTORY_SEPARATOR);
define('PROCESS', ROOT.'includes'.DIRECTORY_SEPARATOR.'process'.DIRECTORY_SEPARATOR);
define('SECTIONS', ROOT.'sections'.DIRECTORY_SEPARATOR);
define('SETUP', ROOT.'setup'.DIRECTORY_SEPARATOR);
define('UPDATE', ROOT.'update'.DIRECTORY_SEPARATOR);
define('OUTPUT', ROOT.'output'.DIRECTORY_SEPARATOR);
define('USER_IMAGES', ROOT.'user_images'.DIRECTORY_SEPARATOR);
define('USER_DOCS', ROOT.'user_docs'.DIRECTORY_SEPARATOR);
define('USER_TEMP', ROOT.'user_temp'.DIRECTORY_SEPARATOR);
define('LANG', ROOT.'lang'.DIRECTORY_SEPARATOR);
define('DEBUGGING', ROOT.'includes'.DIRECTORY_SEPARATOR.'debug'.DIRECTORY_SEPARATOR);
define('AJAX', ROOT.'ajax'.DIRECTORY_SEPARATOR);
define('PUB', ROOT.'pub'.DIRECTORY_SEPARATOR);

define('HOSTED', FALSE);
define('NHC', FALSE);
define('SINGLE', FALSE);
define('EVALUATION', TRUE);
define('MAINT', FALSE);
define('CDN', TRUE);
define('TESTING', TRUE);
define('DEBUG', TRUE);
define('DEBUG_SESSION_VARS', FALSE);
define('FORCE_UPDATE', FALSE);
define('ENABLE_MARKDOWN', FALSE);
define('ENABLE_MAILER', FALSE);

if (!function_exists('is_https')) {
    function is_https()
    {
        if (((!empty($_SERVER['HTTPS'])) && (strtolower($_SERVER['HTTPS']) !== "off")) || ((isset($_SERVER['SERVER_PORT'])) && ($_SERVER['SERVER_PORT'] === "443"))) {
            return TRUE;
        }
        return ((!empty($_SERVER['HTTP_X_FORWARDED_PROTO'])) && (strtolower($_SERVER['HTTP_X_FORWARDED_PROTO']) === "https")) || ((!empty($_SERVER['HTTP_X_FORWARDED_SSL'])) && (strtolower($_SERVER['HTTP_X_FORWARDED_SSL']) === "on"));
    }
}

if (!function_exists('sterilize')) {
    function sterilize($sterilize = NULL) {
        if (is_array($sterilize)) {
            return array_map(sterilize(...), $sterilize);
        }
        if ($sterilize == NULL) {
            return NULL;
        }
        if (empty($sterilize)) {
            return $sterilize;
        }
        $sterilize = trim($sterilize);
        if (is_numeric($sterilize)) {
            if (is_float($sterilize)) $sterilize = filter_var($sterilize,FILTER_SANITIZE_NUMBER_FLOAT,FILTER_FLAG_ALLOW_FRACTION);
            if (is_int($sterilize)) {
                if ($sterilize === 0) $sterilize = 0;
                else $sterilize = filter_var($sterilize,FILTER_SANITIZE_NUMBER_INT);
            }
        }
        else $sterilize = filter_var($sterilize,FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        $sterilize = strip_tags($sterilize);
        $sterilize = stripcslashes($sterilize);
        $sterilize = stripslashes($sterilize);
        $sterilize = addslashes($sterilize);
        return $sterilize;
    }
}
