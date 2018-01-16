<?php
/*
 * Module:      process_special_best_data.inc.php
 * Description: This module does all the heavy lifting for adding/editing info in the "special_best_data" table
 */

$table_id = $id;

if ((isset($_SERVER['HTTP_REFERER'])) && ((isset($_SESSION['loginUsername'])) && ($_SESSION['userLevel'] <= 1))) {

		if ($action == "add") {
			foreach($_POST['id'] as $id){
				if ($_POST['sbd_judging_no'.$id] != "") {

					$cleaned = str_replace("-","",$_POST['sbd_judging_no'.$id]); // remove dash if present
					$cleaned = ltrim($cleaned,"0"); // remove leading zero if present
					$cleaned = sprintf('%05d',$cleaned); // standard in DB is to store a 6 digit number

					$query_entry = sprintf("SELECT * FROM $brewing_db_table WHERE brewJudgingNumber='%s'", $cleaned);
					$entry = mysqli_query($connection,$query_entry) or die (mysqli_error($connection));
					$row_entry = mysqli_fetch_assoc($entry);
					$totalRows_entry = mysqli_num_rows($entry);

					if ($totalRows_entry == 0) {
						// if did not find 5 digit number, try a 6 digit (barcode standard)
						$cleaned = sprintf('%06d',$cleaned);
						$query_entry = sprintf("SELECT * FROM $brewing_db_table WHERE brewJudgingNumber='%s'", $cleaned);
						$entry = mysqli_query($connection,$query_entry) or die (mysqli_error($connection));
						$row_entry = mysqli_fetch_assoc($entry);
						$totalRows_entry = mysqli_num_rows($entry);
					}

					//echo $query_entry."<br>";

					if ($totalRows_entry == 1) {

						if (isset($_POST['sbd_place'.$id])) $sbd_place = sterilize($_POST['sbd_place'.$id]);
						else $sbd_place = "";

						if (isset($_POST['sbd_comments'.$id])) $sbd_comments = sterilize($_POST['sbd_comments'.$id]);
						else $sbd_comments = "";

						$insertSQL = sprintf("INSERT INTO $special_best_data_db_table (sid, bid, eid, sbd_place, sbd_comments) VALUES (%s, %s, %s, %s, %s)",
										   GetSQLValueString(sterilize($_POST['sid'.$id]), "int"),
										   GetSQLValueString($row_entry['brewBrewerID'], "int"),
										   GetSQLValueString($row_entry['id'], "int"),
										   GetSQLValueString($sbd_place, "int"),
										   GetSQLValueString($sbd_comments, "text")
										   );

						mysqli_real_escape_string($connection,$insertSQL);
						$result = mysqli_query($connection,$insertSQL) or die (mysqli_error($connection));

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
				$redirect_go_to = sprintf("Location: %s", stripslashes($insertGoTo));
			}
			else {
				$errorGoTo = $base_url."index.php?section=admin&go=special_best_data&action=edit&id=$table_id&msg=24";
				//echo $errorGoTo."<br>";
				$redirect_go_to = sprintf("Location: %s", stripslashes($errorGoTo));
			}
			//exit;
		}

		if ($action == "edit") {

			foreach($_POST['id'] as $id){

				$cleaned = str_replace("-","",$_POST['sbd_judging_no'.$id]); // remove dash if present
				$cleaned = ltrim($cleaned,"0"); // remove leading zero if present
				$cleaned = sprintf('%05d',$cleaned); // standard in DB is to store a 5 digit number

				$query_entry = sprintf("SELECT * FROM $brewing_db_table WHERE brewJudgingNumber='%s'", $cleaned);
				$entry = mysqli_query($connection,$query_entry) or die (mysqli_error($connection));
				$row_entry = mysqli_fetch_assoc($entry);
				$totalRows_entry = mysqli_num_rows($entry);

				if ($totalRows_entry == 0) {
					// if did not find 5 digit number, try a 6 digit (barcode standard)
					$cleaned = sprintf('%06d',$cleaned);
					$query_entry = sprintf("SELECT * FROM $brewing_db_table WHERE brewJudgingNumber='%s'", $cleaned);
					$entry = mysqli_query($connection,$query_entry) or die (mysqli_error($connection));
					$row_entry = mysqli_fetch_assoc($entry);
					$totalRows_entry = mysqli_num_rows($entry);
				}

				//echo $query_entry."<br>";

				if ($_POST['entry_exists'.$id] == "Y") {

					if ($totalRows_entry == 1) {

						if (isset($_POST['sbd_place'.$id])) $sbd_place = sterilize($_POST['sbd_place'.$id]);
						else $sbd_place = "";

						if (isset($_POST['sbd_comments'.$id])) $sbd_comments = sterilize($_POST['sbd_comments'.$id]);
						else $sbd_comments = "";

						$updateSQL = sprintf("UPDATE $special_best_data_db_table SET sid=%s, bid=%s, eid=%s, sbd_place=%s, sbd_comments=%s WHERE id=%s",
											GetSQLValueString(sterilize($_POST['sid'.$id]), "int"),
											GetSQLValueString($row_entry['brewBrewerID'], "int"),
											GetSQLValueString($row_entry['id'], "int"),
											GetSQLValueString($sbd_place, "int"),
											GetSQLValueString($sbd_comments, "text"),
											GetSQLValueString($id, "int"));

						mysqli_real_escape_string($connection,$updateSQL);
						$result = mysqli_query($connection,$updateSQL) or die (mysqli_error($connection));

						$a[] = 0;
					}

					else {
						$a[] = 1;
						//echo "YES!";
					}
				}
				if (($_POST['entry_exists'.$id] == "N") && ($_POST['sbd_judging_no'.$id] != "")) {

					if ($totalRows_entry == 1) {

						if (isset($_POST['sbd_place'.$id])) $sbd_place = sterilize($_POST['sbd_place'.$id]);
						else $sbd_place = "";

						if (isset($_POST['sbd_comments'.$id])) $sbd_comments = sterilize($_POST['sbd_comments'.$id]);
						else $sbd_comments = "";

						$insertSQL = sprintf("INSERT INTO $special_best_data_db_table (sid, bid, eid, sbd_place, sbd_comments) VALUES (%s, %s, %s, %s, %s)",
										   GetSQLValueString(sterilize($_POST['sid'.$id]), "int"),
										   GetSQLValueString($row_entry['brewBrewerID'], "int"),
										   GetSQLValueString($row_entry['id'], "int"),
										   GetSQLValueString($sbd_place, "int"),
										   GetSQLValueString($sbd_comments, "text")
										   );

						mysqli_real_escape_string($connection,$insertSQL);
						$result = mysqli_query($connection,$insertSQL) or die (mysqli_error($connection));

					}

					else {
						$a[] = 1;
					}
				}
			}

			$pattern = array('\'', '"');
			if (array_sum($a) == 0) $updateGoTo = $base_url."index.php?section=admin&go=special_best_data&msg=2";
			else $updateGoTo = $base_url."index.php?section=admin&go=special_best_data&action=edit&id=$table_id&msg=24";
			$updateGoTo = str_replace($pattern, "", $updateGoTo);
			$redirect_go_to = sprintf("Location: %s", stripslashes($updateGoTo));
		}

} else {
	$redirect_go_to = sprintf("Location: %s", $base_url."index.php?msg=98");
}
?>