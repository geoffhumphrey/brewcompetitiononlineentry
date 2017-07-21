<?php 
/**

 * Module:      paths.php 
 * Description: This module sets global file folder paths. Also houses
 *              specific, site-wide variables.
 * 
 */
 
define('ROOT',dirname( __FILE__ ).DIRECTORY_SEPARATOR);
define('ADMIN',ROOT.'admin'.DIRECTORY_SEPARATOR);
define('SSO',ROOT.'sso'.DIRECTORY_SEPARATOR);
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

// --------------------------------------------------------
// Global Definitions
// --------------------------------------------------------
define('HOSTED', FALSE); // default FALSE
define('MAINT', FALSE); // default FALSE
define('NHC', FALSE); // default FALSE
define('TESTING', FALSE); // default FALSE
define('SINGLE', FALSE); // default FALSE
define('DEBUG', FALSE); // default FALSE
define('DEBUG_SESSION_VARS', FALSE); // default FALSE
define('CDN', TRUE); // default TRUE

// --------------------------------------------------------
// Error Reporting
// --------------------------------------------------------

ini_set('error_reporting', E_ALL ^ E_DEPRECATED);
ini_set('log_errors','On');
if (DEBUG)  ini_set('display_errors','On');
else ini_set('display_errors','Off');

// --------------------------------------------------------
// Load Configuration
// --------------------------------------------------------
require_once (CONFIG.'config.php');
require_once (INCLUDES.'current_version.inc.php');

if (HOSTED) {
	$installation_id = $prefix;
	$session_expire_after = 30;
}

/** Using an MD5 of __FILE__ will ensure a different session name for multiple
 * installs on the same domain name.
 * 
 * @fixes https://github.com/geoffhumphrey/brewcompetitiononlineentry/issues/781
 */
if (empty($installation_id)) {
	$prefix_session = md5(__FILE__);
} else {
	$prefix_session = md5($installation_id);
}

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

// Uncomment to display paths
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
echo USER_DOCS;
*/

?>