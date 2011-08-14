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

?>
