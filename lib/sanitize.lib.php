<?php

declare(strict_types=1);

/**
 * Module:      sanitize.lib.php
 * Description: Site-wide sanitization functions, extracted from paths.php
 *              so tests can exercise the real production code without
 *              executing paths.php's database bootstrap.
 *
 * is_https() and sterilize() must remain top-level: sterilize() is used by
 * url_variables.inc.php and is_https() by site/config.php construction.
 */

/**
 * Function to check for HTTPS protocol (SSL) will be
 * called when constructing the $base_url variable in the
 * /sites/config.php file.
 * @see https://github.com/geoffhumphrey/brewcompetitiononlineentry/issues/1123
 * @see https://stackoverflow.com/questions/1175096/how-to-find-out-if-youre-using-https-without-serverhttps
 */

function is_https(): bool {
    if (((!empty($_SERVER['HTTPS'])) && (strtolower($_SERVER['HTTPS']) !== "off")) || ((isset($_SERVER['SERVER_PORT'])) && ($_SERVER['SERVER_PORT'] === "443"))) return TRUE;
    elseif (((!empty($_SERVER['HTTP_X_FORWARDED_PROTO'])) && (strtolower($_SERVER['HTTP_X_FORWARDED_PROTO']) == "https")) || ((!empty($_SERVER['HTTP_X_FORWARDED_SSL'])) && (strtolower($_SERVER['HTTP_X_FORWARDED_SSL']) == "on"))) return TRUE;
    else return FALSE;
}

/**
 * General sanitization function. Needs to be top-level due to its 
 * use in the url_variables.inc.php file.
 */

function sterilize(mixed $sterilize = null): mixed {
    if (is_array($sterilize)) return array_map('sterilize', $sterilize);
    elseif ($sterilize == NULL) return NULL;
    elseif (empty($sterilize)) return $sterilize;
    else {
        $sterilize = trim($sterilize);
        // After trim() the value is always a string; is_float()/is_int()
        // on a string always evaluate false, so the numeric-string path
        // falls through to FULL_SPECIAL_CHARS (which leaves digits, '.',
        // and ',' intact). This matches the pre-existing behavior.
        $sterilize = filter_var($sterilize, FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        $sterilize = strip_tags($sterilize);
        $sterilize = stripcslashes($sterilize);
        $sterilize = stripslashes($sterilize);
        $sterilize = addslashes($sterilize);
        return $sterilize;
    }
}
