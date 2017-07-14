<?php
/**
 * Module:        config.php 
 * Description:   This module houses configuration variables for DB connection, etc.
 *              
 * Last Modified: July 15, 2017
 */
/*

/*******Set up MySQL connection variables*******/

/* Generally, this line is left alone. */

$hostname = "localhost";

/* 
Change the word root to the username for your database (generally the same as your login code for your web hosting company).
INSERT YOUR USERNAME BETWEEN THE DOUBLE-QUOTATION MARKS ("").
For example, if your username is fred then the line should read $username = "fred".
*/

$username = "";

/* 
INSERT YOUR PASSWORD BETWEEN THE DOUBLE-QUOTATION MARKS ("").
For example, if your password is flintstone then the line should read $password = "flintsone".
*/

$password = "";

/*
The following line is the name of your MySQL database you set up already.  
If you haven't set up the database yet, please refer to http://www.brewcompetition.com/index.php?page=install for setup instructions. 
*/

$database = "";

/* 
This line strings the information together and connects to MySQL.  
If MySQL is not found or the username/password combo is not correct an error will be returned.
*/

$connection = new mysqli($hostname, $username, $password, $database);
mysqli_set_charset($connection,'utf8mb4');
mysqli_query($connection, "SET NAMES 'utf8mb4';");
mysqli_query($connection, "SET CHARACTER SET 'utf8mb4';");
mysqli_query($connection, "SET COLLATION_CONNECTION = 'utf8mb4_unicode_ci';");

/* Do not change the following line. */

$brewing = $connection; 

/******End MySQL Connections*******/


/*
******************************************************************************
Set up your images directory path.
******************************************************************************
This is used for label image uploading.

The predefined variable below will be fine for most installations (ONLY change 
this line to your installation's home directory on the server if the predefined 
variable doesn't work). 

If not, use absolute paths (exemplified below). Generally something like 
/home/[account_name]/public_html/folder_name (do NOT put a forward slash [/] 
at the end).

Check your web host's documentation for the correct path. The below are examples
ONLY.

CORRECT example if installation is in the web root folder:
$images_dir = "/home/[account_name]/public_html";

CORRECT example installation is in a sub-folder on your site:
$images_dir = "/home/[account_folder]/public_html/bcoe";
*/

$images_dir = dirname( __FILE__ );

/*
******************************************************************************
DB Prefix.
******************************************************************************
The following variable is used to define a prefix to the database tables.
This is useful if you wish to have separate installations or applications share 
the same mySQL database.

Leave as if you have a database dedicated to your BCOE&M installation.

Suggested Usage 
If you wish to define a prefix to the database tables, it is HIGHLY suggested 
that you use an underscore (_), after a short descriptor that identifies which 
install is using which tables. 
Example:
$prefix = "bcoem1_";
OR
$prefix = "comp1_";
*/

$prefix = "";

/*
******************************************************************************
Installation ID.
******************************************************************************
Give your installation a unique ID. If you plan on running multiple instances
of BCOE&M from the same domain, you'll need to give each installation a 
unique identifier. This prevents "cross-pollination" of session data display.

For single installations, the default below will be sufficient. Otherwise,
change the variable to something completely unique for each installation.

*/

$installation_id = "";

/*
******************************************************************************
User session time out
******************************************************************************
Define the time (in minutes) that a user's session will be active before it 
expires due to inactivity. Default is 30 minutes.
*/

$session_expire_after = 30;

/*
******************************************************************************
Access control for Setup.
******************************************************************************
If you are going to go through the installation and setup process, you will 
need to modify the access check statement below. Change the FALSE to a TRUE 
to disable the access check.

After finishing setup, be sure to open this file again and change the 
TRUE back to a FALSE!
*/

$setup_free_access =  FALSE;

/*
******************************************************************************
Set the subdirector of your installation (if necessary). 
******************************************************************************
In most cases the default will be OK.

IF YOU ARE RUNNING YOUR INSTANCE OF BCOE&M IN A SUBFOLDER...

Add the name of the subdirectory between the quotes of the $sub_directory 
variable. 

* Be sure to INCLUDE a leading slash [/] and NO trailing slash [/]!

Example:
$sub_directory = "/bcoem"; 

WARNING!!!
IF you do enable the subdirectory variable, YOU MUST alter your .htaccess file
Otherwise, the URLs will not be generated correctly! Directions are in the 
.htaccess file.
*/

$sub_directory = "";

/*
******************************************************************************
Set the base URL of your installation. 
******************************************************************************
In most cases the default will be OK.
 
IF you are installing on a server where you do not have a domain name set up,
you'll need to replace the last $base_url variable below with something 
formatted like this:
$base_url .= "yourhostingdomain/~accountname/subdirectoryname/";

Example:
$base_url .= "147.21.160.5/~brewcompetition/bcoem/";
OR:
$base_url .= "www.bluehost.com/~brewcompeition/bcoem/";
*/

$base_url = ""; 
if ((!empty($_SERVER['HTTPS']) && ($_SERVER['HTTPS'] !== "off")) || ($_SERVER['SERVER_PORT'] == 443)) $base_url .= "https://"; 
else $base_url .= "http://"; 
$base_url .= $_SERVER['SERVER_NAME'].$sub_directory."/";

/*
******************************************************************************
Set the server root for your installation.
******************************************************************************
In most cases the default will be OK.

IF you are installing on a server and will access the software via a sub-domain
(e.g. http://subdomain.domain.com), comment out the first variable below and 
uncomment the second variable.
*/

$server_root = $_SERVER['DOCUMENT_ROOT'];
//$server_root = $_SERVER['SUBDOMAIN_DOCUMENT_ROOT']; 
?>
