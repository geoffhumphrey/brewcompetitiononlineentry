<?php
/*
Checked Single
2016-06-06
*/

$username = strtolower($username);
function authenticateUserNav($connection, $username, $password)
{
	include('../paths.php');
    require(CONFIG.'config.php');
    require(INCLUDES.'url_variables.inc.php');
	require(INCLUDES.'db_tables.inc.php');
  	// Test the username and password parameters
  	if (!isset($username) || !isset($password))
    return false;
	
	mysqli_select_db($connection,$database);
	$query = "SELECT password FROM $users_db_table WHERE user_name = '{$username}' AND password = '{$password}'";	
  	if (!$result = @ mysqli_query($connection,$query)) showerror();
  	// Is the returned result exactly one row? If so, then we have found the user
  	if (mysqli_num_rows($result) != 1)
    return false;
  	else
    return true;
}
// Connects to a session and checks that the user has authenticated and that the remote IP address matches the address used to create the session.
function sessionAuthenticateNav() {
	require(CONFIG.'config.php');
	// Check if the user hasn't logged in
  	if (!isset($_SESSION['loginUsername'])) echo "<a href=\"".$base_url."index.php?section=login\">Log In</a>"; 
  	if (isset($_SESSION['loginUsername']))  echo "<a href=\"".$base_url."includes/process.inc.php?section=logout&action=logout\">Log Out</a><div id=\"break\">Hello, ".$_SESSION['brewerFirstName']."</div>";
}
?>