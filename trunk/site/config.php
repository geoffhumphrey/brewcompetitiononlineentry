<?php
/*******Set up MySQL connection variables*******
Generally, this line is left alone.
*/
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
$connection = mysql_connect($hostname, $username, $password) or trigger_error(mysql_error());

/* 
Do not change the following line.
*/
$brewing = $connection; 


/******End MySQL Connections*******


/*
Set up your images directory path.  This is used for label image uploading.
The predefined variable below will be fine for most installations.

ONLY change this line to your installation's home directory on the server 
if the predefined variable doesn't work.

If not, use absolute paths (exemplified below).Generally something like 
/home/[account_name]/public_html/folder_name (do NOT put a forward slash [/] 
at the end).

******************************************************************************
CORRECT example if installation is in the web root folder:
$images_dir = "/home/[account_name]/public_html";

CORRECT example installation is in a sub-folder on your site:
$images_dir = "/home/[account_folder]/public_html/bcoe";
******************************************************************************
*/

$images_dir = dirname( __FILE__ );


/*
******************************************************************************
The following variable is used to define a prefix to the database tables.
This is useful if you wish to have separate installations or applications share 
the same mySQL database.

Leave as if you have a database dedicated to your BCOE&M installation.

******************************************************************************

// Suggested Usage 
If you wish to define a prefix to the database tables, it is  HIGHLY suggested that you use an underscore (_),
after a short descriptor that identifies which install is using which tables. For example:
$prefix = "bcoem1_";
or
$prefix = "comp1_";

******************************************************************************
*/

$prefix = "";


/*
******************************************************************************
Access control for setup.php.

If you are going to go through the installation and setup process, you will need to modify the access check
statement below. Change the FALSE to a TRUE to disable the access check.
After finishing the upgrade, be sure to open this file again and change the TRUE back to a FALSE!
 
*/

$setup_free_access = FALSE;

$setup_key = "default";

/*
******************************************************************************
Set the base URL of your installation. In most cases the default will be OK. 

IF YOU ARE RUNNING YOUR INSTANCE OF BCOE&M IN A SUBFOLDER...
Add the name of the subdirectory between the quotes of the $sub_directory variable.
* Be sure to INCLUDE a leading slash [/] and NO trailing slash [/]!

For example:
$sub_directory = "/bcoe"; 

LEAVE the $base_url variable below untouched.

*/

// IF you do enable the subdirectory variable, YOU MUST alter your .htaccess file
// Otherwise, the URLs will not be generated correctly
// Directions are in the .htaccess file
$sub_directory = "";

$base_url = "http://".$_SERVER['SERVER_NAME'].$sub_directory;

/*
******************************************************************************
Search Engine Friendly (SEF) URLs - the following lines are for use with installations
that can accomodate Search Engine Friendly (SEF) or "Clean" URLs

If not functioning, the URLs for "Rules", "Entry Info", "Volunteer Info", "Contact", "Login" and "My Info and Entries"
will all result in a 404 error. See below.
*/

// Comment out the following line if experiencing problems with SEF URLs
if (strpos(shell_exec('/usr/local/apache/bin/apachectl -l'), 'mod_rewrite') !== false) $sef = "true"; else

// Leave the following line alone if experiencing problems with SEF URLs
$sef = "false"; 


?>
