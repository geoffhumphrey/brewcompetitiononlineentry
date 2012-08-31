<?php 
/**
 * Module:      paths.php 
 * Description: This module sets global file folder paths. Also houses
 *              specific, site-wide variables.
 * 
 */

define('ROOT',dirname( __FILE__ ).DIRECTORY_SEPARATOR);
define('INCLUDES',ROOT.'includes'.DIRECTORY_SEPARATOR);
define('PROCESS',ROOT.'includes'.DIRECTORY_SEPARATOR.'process'.DIRECTORY_SEPARATOR);
define('DB',ROOT.'includes'.DIRECTORY_SEPARATOR.'db'.DIRECTORY_SEPARATOR);
define('CONFIG',ROOT.'site'.DIRECTORY_SEPARATOR);
define('SECTIONS',ROOT.'sections'.DIRECTORY_SEPARATOR);
define('ADMIN',ROOT.'admin'.DIRECTORY_SEPARATOR);
define('TEMPLATES',ROOT.'templates'.DIRECTORY_SEPARATOR);
define('CLASSES',ROOT.'classes'.DIRECTORY_SEPARATOR);
define('SETUP',ROOT.'setup'.DIRECTORY_SEPARATOR);
define('UPDATE',ROOT.'update'.DIRECTORY_SEPARATOR);
define('IMAGES',ROOT.'images'.DIRECTORY_SEPARATOR);
define('USER_IMAGES',ROOT.'user_images'.DIRECTORY_SEPARATOR);
require(CONFIG.'config.php');
$php_version = phpversion();
$current_page = $base_url."/index.php?".$_SERVER['QUERY_STRING'];


function php_timezone($input) {
	$timezones = array( 
        '-12'=>'Pacific/Kwajalein', 
        '-11'=>'Pacific/Midway', 
        '-10'=>'Pacific/Honolulu', 
        '-9'=>'America/Anchorage', 
        '-8'=>'America/Los_Angeles', 
        '-7'=>'America/Denver', 
        '-6'=>'America/Mexico_City', 
        '-5'=>'America/New_York', 
        '-4'=>'America/Caracas', 
        '-3.5'=>'America/St_Johns', 
        '-3'=>'America/Argentina/Buenos_Aires', 
        '-2'=>'Atlantic/South_Georgia',
        '-1'=>'Atlantic/Azores', 
        '0'=>'Europe/London', 
        '1'=>'Europe/Paris', 
        '2'=>'Europe/Helsinki', 
        '3'=>'Europe/Moscow', 
        '3.5'=>'Asia/Tehran', 
        '4'=>'Asia/Baku', 
        '4.5'=>'Asia/Kabul', 
        '5'=>'Asia/Karachi', 
        '5.5'=>'Asia/Calcutta', 
        '6'=>'Asia/Colombo', 
        '7'=>'Asia/Bangkok', 
        '8'=>'Asia/Singapore', 
        '9'=>'Asia/Tokyo', 
        '9.5'=>'Australia/Darwin', 
        '10'=>'Pacific/Guam', 
        '11'=>'Asia/Magadan', 
        '12'=>'Asia/Kamchatka',
		'13'=>'Pacific/Tongatapu',
    );
	
    return ($timezones[$input]);
}

$timezone = php_timezone($row_prefs['prefsTimeZone']);
date_default_timezone_set($timezone);
?>