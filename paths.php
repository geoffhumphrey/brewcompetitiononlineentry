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

// --------------------------------------------------------
// Global Definitions
// --------------------------------------------------------
define('HOSTED', FALSE); 		// Top-level constant for use in a hosted environment and shared db tables.
define('MAINT', FALSE); 			// Top-level constant for maintenance mode. Set to TRUE to perform code updates.
define('NHC', FALSE); 			// Top-level constant for specialized NHC functionality. Set to FALSE for production.
define('TESTING', FALSE); 		// Top-level constant for testing functionality. Set to FALSE for production.
define('SINGLE', FALSE);

// --------------------------------------------------------
// Error Reporting
// --------------------------------------------------------
error_reporting(0);	// comment out to debug
// error_reporting(E_ALL); // uncomment to debug 

// --------------------------------------------------------
// Load Configuration
// --------------------------------------------------------
require (CONFIG.'config.php');
require (INCLUDES.'current_version.inc.php'); 

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