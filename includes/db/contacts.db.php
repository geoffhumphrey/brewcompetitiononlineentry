<?php
$query_contact = "SELECT * FROM $contacts_db_table";
if ($action == "edit") $query_contact .= " WHERE id='$id'"; 
else $query_contact .= " ORDER BY contactLastName,contactPosition"; 
$contact = mysqli_query($connection,$query_contact) or die (mysqli_error($connection));
$row_contact = mysqli_fetch_assoc($contact);
$totalRows_contact = mysqli_num_rows($contact);
?>