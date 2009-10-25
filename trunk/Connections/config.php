<?php
/*******Set up MySQL connection variables*******
Generally, this line is left alone.
*/
$hostname = "localhost";

/* 
Change the word root to the username for your database (generally the same as your login code for your web hosting company).
INSERT YOUR USERNAME BETWEEN THE DOUBLE-QUOTATION MARKS ("").
For example, if your username is fred then the line should read $username_brewblog = "fred".
*/
$username = "";
/* 
INSERT YOUR PASSWORD BETWEEN THE DOUBLE-QUOTATION MARKS ("").
For example, if your password is flintstone then the line should read $password_brewblog = "flintsone".
*/
$password = "";

/*
The following line is the name of your MySQL database you set up already.  
If you haven't set up the database yet, please refer to http://www.brewblogger.net/ for setup instructions. 
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
Change this line to your installation's home directory on the server.
Generally something like /home/your_user_name/public_html/folder_name (do NOT put a forward slash [/] at the end).

******************************************************************************

CORRECT example if installation is in the web root folder:
$images_dir = "/home/public_html";

CORRECT example installation is in a sub-folder on your site:
$images_dir = "/home/public_html/brewblogger";

******************************************************************************

INCORRECT examples in config.php:
$images_dir = "/home/public_html/label_images";
$images_dir = "/home/public_html/brewblogger/label_images";

******************************************************************************

*/
$images_dir = "";

?>
