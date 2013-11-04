<?php
$query_username = sprintf("SELECT * FROM %s WHERE id='%s'",$users_db_table,$row_brewer['uid']);
$username = mysql_query($query_username, $brewing) or die(mysql_error());
$row_username = mysql_fetch_assoc($username);
?>