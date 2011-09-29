<?php 
/**
 * Module:      paths.php 
 * Description: This module sets global file folder paths. Also houses
 *              specific, site-wide variables.
 * 
 */

define('ROOT',dirname( __FILE__ ).DIRECTORY_SEPARATOR);
define('INCLUDES',ROOT.'includes'.DIRECTORY_SEPARATOR);
define('CONFIG',ROOT.'site'.DIRECTORY_SEPARATOR);
define('SECTIONS',ROOT.'sections'.DIRECTORY_SEPARATOR);
define('ADMIN',ROOT.'admin'.DIRECTORY_SEPARATOR);
define('TEMPLATES',ROOT.'templates'.DIRECTORY_SEPARATOR);
define('DB',ROOT.'includes'.DIRECTORY_SEPARATOR.'db'.DIRECTORY_SEPARATOR);
define('CLASSES',ROOT.'classes'.DIRECTORY_SEPARATOR);
define('SETUP',ROOT.'setup'.DIRECTORY_SEPARATOR);
define('IMAGES',ROOT.'images'.DIRECTORY_SEPARATOR);
define('USER_IMAGES',ROOT.'user_images'.DIRECTORY_SEPARATOR);
require(CONFIG.'config.php');
?>