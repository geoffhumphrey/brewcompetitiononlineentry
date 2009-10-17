<?php
$email=$_GET['email'];
//echo $email;
if (!eregi("^[_a-z0-9-]+(.[_a-z0-9-]+)*@[a-z0-9-]+(.[a-z0-9-]+)*(.[a-z]{2,3})$", $email))
	{
	echo "<font color=\"red\">Please provide an email address (must have an @ symbol).</font>";
	}
else
	{
	echo "<font color=\"green\">Your email address format is valid.</font>";
	}
?> 