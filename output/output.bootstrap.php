<?php 
define('ROOT',$_SERVER['DOCUMENT_ROOT'].DIRECTORY_SEPARATOR);
define('INCLUDES',ROOT.'includes'.DIRECTORY_SEPARATOR);
define('CONFIG',ROOT.'Connections'.DIRECTORY_SEPARATOR);
define('SECTIONS',ROOT.'sections'.DIRECTORY_SEPARATOR);
define('ADMIN',ROOT.'admin'.DIRECTORY_SEPARATOR);
define('TEMPLATES',ROOT.'templates'.DIRECTORY_SEPARATOR);
define('DB',ROOT.'includes'.DIRECTORY_SEPARATOR.'db'.DIRECTORY_SEPARATOR);
require(CONFIG.'config.php');
require(INCLUDES.'authentication_nav.inc.php');  session_start();
?>
