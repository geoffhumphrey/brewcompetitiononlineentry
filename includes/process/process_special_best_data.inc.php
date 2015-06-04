<?php 
/*
 * Module:      process_special_best_data.inc.php
 * Description: This module does all the heavy lifting for adding/editing info in the "special_best_data" table
 */
$table_id = $id;
//echo $id."<br>";

if ((isset($_SESSION['loginUsername'])) && ($_SESSION['userLevel'] <= 1)) {
	
	if (NHC) {
		// Place NHC SQL calls below
		
		
	}
	
	else {
	

		if ($action == "add") {
			foreach($_POST['id'] as $id){
				if ($_POST['sbd_judging_no'.$id] != "") {
			
					$cleaned = str_replace("-","",$_POST['sbd_judging_no'.$id]); // remove dash if present
					$cleaned = ltrim($cleaned,"0"); // remove leading zero if present
					$cleaned = sprintf('%05d',$cleaned); // standard in DB is to store a 5 digit number
					
					$query_entry = sprintf("SELECT * FROM $brewing_db_table WHERE brewJudgingNumber='%s'", $cleaned);
					$entry = mysql_query($query_entry, $brewing) or die(mysql_error());
					$row_entry = mysql_fetch_assoc($entry);
					$totalRows_entry = mysql_num_rows($entry);
					
					//echo $query_entry."<br>";
					
					if ($totalRows_entry == 1) {
					
						$insertSQL = sprintf("INSERT INTO $special_best_data_db_table (sid, bid, eid, sbd_place, sbd_comments) VALUES (%s, %s, %s, %s, %s)",
										   GetSQLValueString($_POST['sid'.$id], "int"),
										   GetSQLValueString($row_entry['brewBrewerID'], "int"),
										   GetSQLValueString($row_entry['id'], "int"),
										   GetSQLValueString($_POST['sbd_place'.$id], "int"),
										   GetSQLValueString($_POST['sbd_comments'.$id], "text")
										   );
					
						mysql_select_db($database, $brewing);
						mysql_real_escape_string($insertSQL);
						$result1 = mysql_query($insertSQL, $brewing) or die(mysql_error());
						//echo $insertSQL."<br>";
						$a[] = 0;
					
					}
					
					else {
						$a[] = 1;
					}
					
				}
			}
			$pattern = array('\'', '"');
			$insertGoTo = str_replace($pattern, "", $insertGoTo);
			
			if (array_sum($a) == 0) {	
				//echo $insertGoTo."<br>";
				$insertGoTo = $base_url."index.php?section=admin&go=special_best_data&msg=1";
				header(sprintf("Location: %s", stripslashes($insertGoTo)));
			}
			else {
				$errorGoTo = $base_url."index.php?section=admin&go=special_best_data&action=edit&id=$table_id&msg=24";
				//echo $errorGoTo."<br>";
				header(sprintf("Location: %s", stripslashes($errorGoTo)));
			}
			//exit;				   
		}
		
		if ($action == "edit") {
			
			foreach($_POST['id'] as $id){
				
				$cleaned = str_replace("-","",$_POST['sbd_judging_no'.$id]); // remove dash if present
				$cleaned = ltrim($cleaned,"0"); // remove leading zero if present
				$cleaned = sprintf('%05d',$cleaned); // standard in DB is to store a 5 digit number
				
				$query_entry = sprintf("SELECT * FROM $brewing_db_table WHERE brewJudgingNumber='%s'", $cleaned);
				$entry = mysql_query($query_entry, $brewing) or die(mysql_error());
				$row_entry = mysql_fetch_assoc($entry);
				$totalRows_entry = mysql_num_rows($entry);
				
				//echo $query_entry."<br>";
					
				if ($_POST['entry_exists'.$id] == "Y") {
					
					if ($totalRows_entry == 1) {
						$updateSQL = sprintf("UPDATE $special_best_data_db_table SET sid=%s, bid=%s, eid=%s, sbd_place=%s, sbd_comments=%s WHERE id=%s",
											GetSQLValueString($_POST['sid'.$id], "int"),
											GetSQLValueString($row_entry['brewBrewerID'], "int"),
											GetSQLValueString($row_entry['id'], "int"),
											GetSQLValueString($_POST['sbd_place'.$id], "int"),
											GetSQLValueString($_POST['sbd_comments'.$id], "text"),
											GetSQLValueString($id, "int"));
					
						mysql_select_db($database, $brewing);
						mysql_real_escape_string($updateSQL);
						$result1 = mysql_query($updateSQL, $brewing) or die(mysql_error());
						echo $updateSQL." (Entry Exists)<br>";
						$a[] = 0;
					}
					
					else {
						$a[] = 1;
						//echo "YES!";
					}
				}
				if (($_POST['entry_exists'.$id] == "N") && ($_POST['sbd_judging_no'.$id] != "")) {
					
					if ($totalRows_entry == 1) {
					
						$insertSQL = sprintf("INSERT INTO $special_best_data_db_table (sid, bid, eid, sbd_place, sbd_comments) VALUES (%s, %s, %s, %s, %s)",
										   GetSQLValueString($_POST['sid'.$id], "int"),
										   GetSQLValueString($row_entry['brewBrewerID'], "int"),
										   GetSQLValueString($row_entry['id'], "int"),
										   GetSQLValueString($_POST['sbd_place'.$id], "int"),
										   GetSQLValueString($_POST['sbd_comments'.$id], "text")
										   );
					
						mysql_select_db($database, $brewing);
						mysql_real_escape_string($insertSQL);
						$result1 = mysql_query($insertSQL, $brewing) or die(mysql_error());
						echo $insertSQL."<br>";
						//$a[] = 0;
					
					}
					
					else {
						$a[] = 1;
						//echo "YES!";
					}
				}
			}
			
			$pattern = array('\'', '"');
			if (array_sum($a) == 0) $updateGoTo = $base_url."index.php?section=admin&go=special_best_data&msg=2";
			else $updateGoTo = $base_url."index.php?section=admin&go=special_best_data&action=edit&id=$table_id&msg=24";
			$updateGoTo = str_replace($pattern, "", $updateGoTo);
			//echo $updateGoTo;
			header(sprintf("Location: %s", stripslashes($updateGoTo)));	
			//exit;		   
		}
	
	} // end else NHC
	
} else //echo "<p>Not available.</p>";

?>