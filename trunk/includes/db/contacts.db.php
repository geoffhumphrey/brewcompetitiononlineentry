<?php
if (NHC) {
	// Place NHC SQL calls below
	
	
}
// end if (NHC)

else {
	
	$query_contact = "SELECT * FROM $contacts_db_table";
	if ($action == "edit")  $query_contact .= " WHERE id='$id'"; else $query_contact .= " ORDER BY contactLastName,contactPosition"; 
	$contact = mysql_query($query_contact, $brewing) or die(mysql_error());
	$row_contact = mysql_fetch_assoc($contact);
	$totalRows_contact = mysql_num_rows($contact);

}
?>