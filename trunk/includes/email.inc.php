<?php
$email = $_GET['email'];
if (!preg_match('/^[_a-z0-9-]+(.[_a-z0-9-]+)*@[a-z0-9-]+(.[a-z0-9-]+)*(.[a-z]{2,3})$/i', $email)) echo "&nbsp;Email Format: <font color=\"red\">Invalid";
else  echo "Email Format: <font color=\"green\">Valid</font>";

?> 