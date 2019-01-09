<?php
/**
 * Module:      paths.php
 * Description: This module sets global file folder paths. Also houses
 *              specific, site-wide variables.
 *
 */

/**
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
 * Default is FALSE
 */

define('MAINT', FALSE);

/**
 * Disable the following to utilize the Load Libraries
 * Locally option if your installation is having trouble
 * loading libraries via CDN.
 * See http://www.brewcompetition.com/local-load
 * Default is TRUE
 */

define('CDN', TRUE);

/**
 * Enable the following to put the site into "test mode"
 * Useful for testing the PayPal IPN functions in their
 * sandbox enfvironment, etc.
 * Default is FALSE
 */

define('TESTING', FALSE);

/**
 * Enable the following to display php errors on screen.
 * Default is FALSE
 */

define('DEBUG', FALSE);

/**
 * Enable the following to show a collapsable table of all
 * session variables on screen
 * Default is FALSE
 */

define('DEBUG_SESSION_VARS', FALSE);

/**
 * Enable the following when receiving mySQL "column does
 * not exist" errors and the like.
 * This will trigger DB structure updates contained in the
 * off_schedule_update.php file.
 * ONLY enbable for a single refresh of the index.php
 * page for performance issues.
 * Default is FALSE
 */

define('FORCE_UPDATE', FALSE);

/**
 * --------------------------------------------------------
 * Error Reporting
 * --------------------------------------------------------
 */

ini_set('error_reporting', E_ALL ^ E_DEPRECATED);
ini_set('log_errors','On');
if (DEBUG) ini_set('display_errors','On');
else ini_set('display_errors','Off');

/**
 * --------------------------------------------------------
 * Load Configuration
 * --------------------------------------------------------
 */

require_once (CONFIG.'config.php');
require_once (INCLUDES.'current_version.inc.php');

if (HOSTED) {
    $installation_id = $prefix;
    $session_expire_after = 30;
}

/** Using an MD5 of __FILE__ will ensure a different session
 * name for multiple installs on the same domain name.
 *
 * @fixes https://github.com/geoffhumphrey/brewcompetitiononlineentry/issues/781
 */

if (empty($installation_id)) $prefix_session = md5(__FILE__);
else $prefix_session = md5($installation_id);

function is_session_started() {
    if (php_sapi_name() !== 'cli' ) {
        if (version_compare(phpversion(), '5.4.0', '>=')) {
            return session_status() === PHP_SESSION_ACTIVE ? TRUE : FALSE;
        } else {
            return session_id() === '' ? FALSE : TRUE;
        }
    }
    return FALSE;
}

if (is_session_started() === FALSE) {
	session_name($prefix_session);
    session_start();
}

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
 * --------------------------------------------------------
 * RECAPTCHA Keys
 * One set is for hosted installations on brewcomp.com or
 * brewcompetition.com - the other is for outside use.
 * Per Google guidelines, all keys validate the domain from
 * which it was generated:
 * https://developers.google.com/recaptcha/docs/domain_validation
 * --------------------------------------------------------
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