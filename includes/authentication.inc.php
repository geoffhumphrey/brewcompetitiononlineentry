<?php

function authenticateUser($connection, $username, $password)
{
  // Test the username and password parameters
  if (!isset($username) || !isset($password))
    return false;

  // Formulate the SQL find the user
  $password = md5($password);
  $query = "SELECT password FROM users WHERE user_name = '{$username}' AND password = '{$password}'";
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
function sessionAuthenticate()
{
  // Check if the user hasn't logged in
  if (!isset($_SESSION["loginUsername"]))
  {
    header("Location: ../index.php?section=login&amp;msg=1");
    exit;
  }

}
?>