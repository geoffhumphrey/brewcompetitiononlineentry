<?php

function authenticateUserNav($connection, $username, $password)
{
  // Test the username and password parameters
  if (!isset($username) || !isset($password))
    return false;

  // Formulate the SQL find the user
  $query = "SELECT password FROM users WHERE user_name = '{$username}'
            AND password = '{$password}'";

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

function sessionAuthenticateNav()
{
  // Check if the user hasn't logged in
  if (!isset($_SESSION["loginUsername"]))  { echo "Already Registered? <a href=\"index.php?section=login&action=login\">Log In</a>"; }
  if (isset($_SESSION["loginUsername"]))   { echo "<a href=\"includes/logout.inc.php\">Log Out</a><div id=\"break\">Logged in as ".$_SESSION["loginUsername"]."</div>"; }
}

?>
