<?php

/**
 * Module:      paths.php
 * Description: This module sets global file folder paths. Also houses
 *              specific, site-wide variables.
 * 
 * The following are file path definitions for various
 * script and document storage folders used/accessed by the
 * application.
 */

define('ROOT',dirname( __FILE__ ).DIRECTORY_SEPARATOR);
define('ADMIN',ROOT.'admin'.DIRECTORY_SEPARATOR);
define('SSO',ROOT.'sso'.DIRECTORY_SEPARATOR);
define('EVALS',ROOT.'eval'.DIRECTORY_SEPARATOR);
define('CLASSES',ROOT.'classes'.DIRECTORY_SEPARATOR);
define('CONFIG',ROOT.'site'.DIRECTORY_SEPARATOR);
define('DB',ROOT.'includes'.DIRECTORY_SEPARATOR.'db'.DIRECTORY_SEPARATOR);
define('IMAGES',ROOT.'images'.DIRECTORY_SEPARATOR);
define('INCLUDES',ROOT.'includes'.DIRECTORY_SEPARATOR);
define('LIB',ROOT.'lib'.DIRECTORY_SEPARATOR);
define('MODS',ROOT.'mods'.DIRECTORY_SEPARATOR);
define('PROCESS',ROOT.'includes'.DIRECTORY_SEPARATOR.'process'.DIRECTORY_SEPARATOR);
define('SECTIONS',ROOT.'sections'.DIRECTORY_SEPARATOR);
define('COMPONENTS',ROOT.'components'.DIRECTORY_SEPARATOR);
define('TEMPLATES',ROOT.'templates'.DIRECTORY_SEPARATOR);
define('SETUP',ROOT.'setup'.DIRECTORY_SEPARATOR);
define('UPDATE',ROOT.'update'.DIRECTORY_SEPARATOR);
define('OUTPUT',ROOT.'output'.DIRECTORY_SEPARATOR);
define('USER_IMAGES',ROOT.'user_images'.DIRECTORY_SEPARATOR);
define('USER_DOCS',ROOT.'user_docs'.DIRECTORY_SEPARATOR);
define('USER_TEMP',ROOT.'user_temp'.DIRECTORY_SEPARATOR);
define('LANG',ROOT.'lang'.DIRECTORY_SEPARATOR);
define('DEBUGGING',ROOT.'includes'.DIRECTORY_SEPARATOR.'debug'.DIRECTORY_SEPARATOR);
define('AJAX',ROOT.'ajax'.DIRECTORY_SEPARATOR);

/**
 * --------------------------------------------------------
 * Global Definitions
 * --------------------------------------------------------
 */

/**
 * The following are for use by the developer
 * Default for all is FALSE
 */

define('HOSTED', FALSE);
define('NHC', FALSE);
define('SINGLE', FALSE);
define('EVALUATION', FALSE);

/**
 * Enable to following to put your installation into
 * "mainenance mode" - bypasses the default index.php script
 * and displays the maintenance.php file to alert visitors.
 * Default is FALSE.
 */

define('MAINT', FALSE);

/**
 * Disable the following to utilize the Load Libraries
 * Locally option if your installation is having trouble
 * loading libraries via CDN or if you wish to host libraries
 * in the root folder of your installation.
 * If set to FALSE, the local libraries must be in place
 * PRIOR to proceeding through the setup process.
 * @see http://www.brewingcompetitions.com/local-load
 * Default is TRUE.
 */

define('CDN', TRUE);

/**
 * Enable the following to put the site into "test mode"
 * Useful for testing the PayPal IPN functions in their
 * sandbox environment, etc.
 * Default is FALSE.
 */

define('TESTING', FALSE);

/**
 * Enable the following to display php errors on screen.
 * Default is FALSE.
 */

define('DEBUG', FALSE);

/**
 * Enable the following to show a collapsable table of all
 * session variables on screen.
 * Default is FALSE.
 */

define('DEBUG_SESSION_VARS', FALSE);

/**
 * Enable the following when receiving mySQL "column does
 * not exist" errors and the like.
 * This will trigger DB structure updates contained in the
 * off_schedule_update.php file.
 * ONLY enbable for a single refresh of the index.php
 * page for performance issues.
 * Default is FALSE.
 */

define('FORCE_UPDATE', FALSE);

/**
 * Set the following to TRUE if receiving mod_security 
 * "Not Acceptable!" errors. This may not alleviate the 
 * problem, but it's a good first step in diagnosing
 * why some mod_security errors are occurring.
 * Default is FALSE.
 */

define('ENABLE_MARKDOWN', FALSE);

/**
 * Set the following to TRUE if you would like to use
 * PHPMailer to send emails from your BCOE&M installation.
 * PHPMailer uses your server's SMTP configuration to send
 * emails instead of using PHP's native mail() function,
 * which may be disabled on certain web hosts.
 * Requires configuration in the /site/config.mail.php file
 * Default is FALSE.
 */

define('ENABLE_MAILER', FALSE);

/**
 * Error Reporting
 */

ini_set('error_reporting', E_ALL ^ E_DEPRECATED);
ini_set('log_errors','On');
if (DEBUG) ini_set('display_errors','On');
else ini_set('display_errors','Off');

/** 
 * Function to check for HTTPS protocol (SSL) will be
 * called when constructing the $base_url variable in the
 * /sites/config.php file.
 * @see https://github.com/geoffhumphrey/brewcompetitiononlineentry/issues/1123
 * @see https://stackoverflow.com/questions/1175096/how-to-find-out-if-youre-using-https-without-serverhttps
 */

function is_https() {
    if (((!empty($_SERVER['HTTPS'])) && (strtolower($_SERVER['HTTPS']) !== "off")) || ((isset($_SERVER['SERVER_PORT'])) && ($_SERVER['SERVER_PORT'] === "443"))) return TRUE;
    elseif (((!empty($_SERVER['HTTP_X_FORWARDED_PROTO'])) && (strtolower($_SERVER['HTTP_X_FORWARDED_PROTO']) == "https")) || ((!empty($_SERVER['HTTP_X_FORWARDED_SSL'])) && (strtolower($_SERVER['HTTP_X_FORWARDED_SSL']) == "on"))) return TRUE;
    else return FALSE;
}

/**
 * General sanitization function.
 * Needs to be top-level due to use in 
 * url_variables.inc.php file.
 */

function sterilize($sterilize = NULL) {
    if ($sterilize == NULL) return NULL;
    elseif (empty($sterilize)) return $sterilize;
    else {
        $sterilize = trim($sterilize);
        if (is_numeric($sterilize)) {
            if (is_float($sterilize)) $sterilize = filter_var($sterilize,FILTER_SANITIZE_NUMBER_FLOAT,FILTER_FLAG_ALLOW_FRACTION);
            if (is_int($sterilize)) $sterilize = filter_var($sterilize,FILTER_SANITIZE_NUMBER_INT);
        }
        else $sterilize = filter_var($sterilize,FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        $sterilize = strip_tags($sterilize);
        $sterilize = stripcslashes($sterilize);
        $sterilize = stripslashes($sterilize);
        $sterilize = addslashes($sterilize);
        return $sterilize;
    }
}

if (HOSTED) {
    $installation_id = md5(__FILE__);
    $session_expire_after = 60;
}

/** 
 * Using an MD5 of __FILE__ will ensure a different session
 * name for multiple installs on the same domain name.
 *
 * @see https://github.com/geoffhumphrey/brewcompetitiononlineentry/issues/781
 */

if (empty($installation_id)) $prefix_session = md5(__FILE__);
else $prefix_session = md5($installation_id);

if (session_status() == PHP_SESSION_ACTIVE) {
    // **PREVENTING SESSION HIJACKING**
    // Prevents javascript XSS attacks aimed to steal the session ID
    ini_set('session.cookie_httponly', 1);

    // **PREVENTING SESSION FIXATION**
    // Session ID cannot be passed through URLs
    ini_set('session.use_only_cookies', 1);

    // Uses a secure connection (HTTPS) if possible
    ini_set('session.cookie_secure', 1);
}

if (session_status() == PHP_SESSION_NONE) {
    session_name($prefix_session);
    session_start();
}

/**
 * Load DB connection and configuration files
 */

require_once (CONFIG.'config.php');
require_once (CONFIG.'MysqliDb.php');
$db_conn = new MysqliDb($connection);

if (ENABLE_MAILER) require_once (CONFIG.'config.mail.php');
require_once (INCLUDES.'current_version.inc.php');

if (isset($_SESSION['last_action'])) {
    $seconds_inactive = time() - $_SESSION['last_action'];
    $session_expire_after_seconds = $session_expire_after * 60;
    if ($seconds_inactive >= $session_expire_after_seconds) {
        session_unset();
        session_destroy();
    }
}

$_SESSION['last_action'] = time();

/**
 * RECAPTCHA Keys
 * One set is for hosted installations, the other is for outside use.
 * Per Google guidelines, all keys validate the domain from
 * which it was generated:
 * @see https://developers.google.com/recaptcha/docs/domain_validation
 * You may need to change the second set with your own API keys if
 * reCAPTCHA is not functioning on your self-hosted installation.
 * @see https://developers.google.com/recaptcha/
 * These are the fallback default. Custom keys must be defined in site 
 * preferences.
 */

if (HOSTED) {
	$public_captcha_key = "6LdUsBATAAAAAEJYbnqmygjGK-S6CHCoGcLALg5W";
	$private_captcha_key = "6LdUsBATAAAAAMPhk5yRSmY5BMXlBgcTjiLjiyPb";
}

else {
    $public_captcha_key = "6LfHUCoUAAAAACHsPn8hpzbtzcpXatm-GXTTWuR3";
    $private_captcha_key = "6LfHUCoUAAAAACNL-wzpAG3eIWQC-PpX6X3a0iaM";
}

/** Uncomment to display paths */
/*
echo ROOT."<br>";
echo ADMIN."<br>";
echo SSO."<br>";
echo CLASSES."<br>";
echo CONFIG."<br>";
echo DB."<br>";
echo IMAGES."<br>";
echo INCLUDES."<br>";
echo LIB."<br>";
echo MODS."<br>";
echo PROCESS."<br>";
echo SECTIONS."<br>";
echo COMPONENTS."<br>";
echo TEMPLATES."<br>";
echo SETUP."<br>";
echo UPDATE."<br>";
echo OUTPUT."<br>";
echo USER_IMAGES."<br>";
echo USER_DOCS."<br>";
echo USER_TEMP."<br>";
echo LANG."<br>";
echo DEBUGGING."<br>";
*/
?>