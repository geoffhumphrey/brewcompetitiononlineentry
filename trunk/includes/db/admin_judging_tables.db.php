<?php
if (NHC) {
	// Place NHC SQL calls below
	
	
}
// end if (NHC)

else {
	
	if ($action == "add") {
		
		$with_received_entries =  explode(",",received_entries());
		
		$query_table_number = "SELECT tableNumber FROM $judging_tables_db_table ORDER BY tableNumber";
		$table_number = mysql_query($query_table_number, $brewing) or die(mysql_error());
		$row_table_number = mysql_fetch_assoc($table_number);
		
		$query_table_number_last = "SELECT tableNumber FROM $judging_tables_db_table ORDER BY tableNumber DESC LIMIT 1";
		$table_number_last = mysql_query($query_table_number_last, $brewing) or die(mysql_error());
		$row_table_number_last = mysql_fetch_assoc($table_number_last);	
		
		$query_entry_count = "SELECT COUNT(*) as 'count' FROM $brewing_db_table";
		$result = mysql_query($query_entry_count, $brewing) or die(mysql_error());
		$row = mysql_fetch_array($result);
		
	}
	
	if ($action == "edit") {
	
		$query_table_number = "SELECT tableNumber FROM $judging_tables_db_table ORDER BY tableNumber";
		$table_number = mysql_query($query_table_number, $brewing) or die(mysql_error());
		$row_table_number = mysql_fetch_assoc($table_number);
		
		$query_entry_count = "SELECT COUNT(*) as 'count' FROM $brewing_db_table";
		$result = mysql_query($query_entry_count, $brewing) or die(mysql_error());
		$row = mysql_fetch_array($result);
		
	}
	
}

?>