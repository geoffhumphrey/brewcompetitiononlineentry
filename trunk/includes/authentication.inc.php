<?php
function authenticateUser($connection, strtolower($username), $password) {
	
	include('../paths.php');
    require(CONFIG.'config.php');
    require(INCLUDES.'url_variables.inc.php');
	require(INCLUDES.'db_tables.inc.php');
  	// Test the username and password parameters
  	if (!isset($username) || !isset($password))
    return false;

  	// Formulate the SQL find the user
  	$password = md5($password);
  	$query = "SELECT password FROM $users_db_table WHERE user_name = '{$username}' AND password = '{$password}'";
	mysql_real_escape_string($query);
  	$result = mysql_query($query, $connection);

/*
   if(!$result || (mysql_numrows($result) < 1)){
     return false; //Indicates username failure
   }      
   
   // Retrieve password from result
   $dbarray = mysql_fetch_array($result);
   
   // Validate that password is correct
   if($password == $dbarray['password']){
      return true; //Success! Username and password confirmed
   }
   else{
      return false; //Indicates password failure
   }
*/
			
			
// Execute the query
  if (!$result = @ mysql_query ($query, $connection))
    showerror();

  // Is the returned result exactly one row? If so, then we have found the user
  if (mysql_num_rows($result) != 1)
     return false;
  else
    return true;
}

// Connects to a session and checks that the user has authenticated and that the remote IP address matches the address used to create the session.
function sessionAuthenticate() {
  require(CONFIG.'config.php');
  if (NHC) $base_url = "../";
	else $base_url = $base_url;
  // Check if the user hasn't logged in
  if (!isset($_SESSION['loginUsername'])) {
    header(sprintf("Location: %s", $base_url."index.php?section=login&msg=1"));
    exit;
  }
}
?>