<?php
$email=$_GET['email'];

if (!eregi("^[_a-z0-9-]+(.[_a-z0-9-]+)*@[a-z0-9-]+(.[a-z0-9-]+)*(.[a-z]{2,3})$", $email))
echo "Email Format: <font color=\"red\">Invalid";

else  echo "Email Format: <font color=\"green\">Valid</font>";

?> 