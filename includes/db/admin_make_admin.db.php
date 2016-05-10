<?php
$query_username = sprintf("SELECT * FROM %s WHERE id='%s'",$users_db_table,$row_brewer['uid']);
$username = mysqli_query($connection,$query_username) or die (mysqli_error($connection));
$row_username = mysqli_fetch_assoc($username);
?>