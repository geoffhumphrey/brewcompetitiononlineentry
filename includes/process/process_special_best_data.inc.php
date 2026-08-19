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

					/**
					 * The following was changed 04.15.19 (v2.1.17)
					 * Judging numbers with a dash (-) were being rejected by the $cleaned var scripting below. 
					 * Judging numbers went to 6-character a standard many releases ago, and worked until the
					 * introduction of the use of dashes in judging numbers in v2.1.12 (?).
					 */

					/*
					$cleaned = str_replace("-","",$_POST['sbd_judging_no'.$id]); // remove dash if present
					$cleaned = ltrim($_POST['sbd_judging_no'.$id],"0"); // remove leading zero if present
					$cleaned = sprintf('%05d',$cleaned); // standard in DB is to store a 6 digit number
					*/

					$cleaned = strtolower(sterilize($_POST['sbd_judging_no'.$id]));

					$db_conn->where("brewJudgingNumber", $cleaned);
					$row_entry = $db_conn->getOne($brewing_db_table);
					$totalRows_entry = $db_conn->count;

					// echo $query_entry."<br>";
					// exit;

					if ($totalRows_entry == 1) {

						if (isset($_POST['sbd_place'.$id])) $sbd_place = sterilize($_POST['sbd_place'.$id]);
						else $sbd_place = "";

						// Plain strip_tags/trim, no entity encoding - matches process_special_best_info.inc.php's
						// fix for the same purify()/sterilize()-then-h() double-encoding shape (sbd_comments
						// is rendered through h() in output/export.output.php).
						if (isset($_POST['sbd_comments'.$id])) $sbd_comments = trim(strip_tags($_POST['sbd_comments'.$id]));
						else $sbd_comments = "";

						$data = [
							'sid' => blank_to_null(sterilize($_POST['sid'.$id])),
							'bid' => blank_to_null($row_entry['brewBrewerID']),
							'eid' => blank_to_null($row_entry['id']),
							'sbd_place' => blank_to_null($sbd_place),
							'sbd_comments' => blank_to_null($sbd_comments)
						];
						$result = $db_conn->insert($special_best_data_db_table, $data);
						if (!$result) {
							$error_output[] = $db_conn->getLastError();
							$errors = TRUE;
						}

						$a[] = 0;

					}

					else $a[] = 1;
				
				}

			}

			$pattern = ['\'', '"'];
			$insertGoTo = str_replace($pattern, "", $insertGoTo);

			if (array_sum($a) == 0) {
				$insertGoTo = $base_url."index.php?section=admin&go=special_best_data&msg=1";
				$redirect_go_to = sprintf("Location: %s", stripslashes($insertGoTo));
			}
			else {
				$errorGoTo = $base_url."index.php?section=admin&go=special_best_data&action=edit&id=$table_id&msg=24";
				$redirect_go_to = sprintf("Location: %s", stripslashes($errorGoTo));
			}

		}

		if ($action == "edit") {

			foreach($_POST['id'] as $id) {

				/*
				$cleaned = str_replace("-","",$_POST['sbd_judging_no'.$id]); // remove dash if present
				$cleaned = ltrim($_POST['sbd_judging_no'.$id],"0"); // remove leading zero if present
				$cleaned = sprintf('%05d',$cleaned); // standard in DB is to store a 5 digit number
				*/

				$cleaned = strtolower(sterilize($_POST['sbd_judging_no'.$id]));

				$db_conn->where("brewJudgingNumber", $cleaned);
				$row_entry = $db_conn->getOne($brewing_db_table);
				$totalRows_entry = $db_conn->count;

				// echo $query_entry."<br>";
				// echo $_POST['entry_exists'.$id]."<br>";

				if ($_POST['entry_exists'.$id] == "Y") {

					if ($totalRows_entry == 1) {

						if (isset($_POST['sbd_place'.$id])) $sbd_place = sterilize($_POST['sbd_place'.$id]);
						else $sbd_place = "";

						// Plain strip_tags/trim, no entity encoding - matches process_special_best_info.inc.php's
						// fix for the same purify()/sterilize()-then-h() double-encoding shape (sbd_comments
						// is rendered through h() in output/export.output.php).
						if (isset($_POST['sbd_comments'.$id])) $sbd_comments = trim(strip_tags($_POST['sbd_comments'.$id]));
						else $sbd_comments = "";

						$data = [
							'sid' => blank_to_null(sterilize($_POST['sid'.$id])),
							'bid' => blank_to_null($row_entry['brewBrewerID']),
							'eid' => blank_to_null($row_entry['id']),
							'sbd_place' => blank_to_null($sbd_place),
							'sbd_comments' => blank_to_null($sbd_comments)
						];
						$db_conn->where('id', sterilize($id));
						$result = $db_conn->update($special_best_data_db_table, $data);
						if (!$result) {
							$error_output[] = $db_conn->getLastError();
							$errors = TRUE;
						}
						// echo $updateSQL."<br>";

						$a[] = 0;
					}

					else $a[] = 1;
				
				}

				if (($_POST['entry_exists'.$id] == "N") && ($_POST['sbd_judging_no'.$id] != "")) {

					if ($totalRows_entry == 1) {

						if (isset($_POST['sbd_place'.$id])) $sbd_place = sterilize($_POST['sbd_place'.$id]);
						else $sbd_place = "";

						// Plain strip_tags/trim, no entity encoding - matches process_special_best_info.inc.php's
						// fix for the same purify()/sterilize()-then-h() double-encoding shape (sbd_comments
						// is rendered through h() in output/export.output.php).
						if (isset($_POST['sbd_comments'.$id])) $sbd_comments = trim(strip_tags($_POST['sbd_comments'.$id]));
						else $sbd_comments = "";

						$data = [
							'sid' => blank_to_null(sterilize($_POST['sid'.$id])),
							'bid' => blank_to_null($row_entry['brewBrewerID']),
							'eid' => blank_to_null($row_entry['id']),
							'sbd_place' => blank_to_null($sbd_place),
							'sbd_comments' => blank_to_null($sbd_comments)
						];
						$result = $db_conn->insert($special_best_data_db_table, $data);
						if (!$result) {
							$error_output[] = $db_conn->getLastError();
							$errors = TRUE;
						}
						// echo $updateSQL."<br>";

					}

					else {
						$a[] = 1;
					}

				}

			}

			// exit;

			$pattern = ['\'', '"'];
			if (array_sum($a) == 0) $updateGoTo = $base_url."index.php?section=admin&go=special_best_data&msg=2";
			else $updateGoTo = $base_url."index.php?section=admin&go=special_best_data&action=edit&id=$table_id&msg=24";
			$updateGoTo = str_replace($pattern, "", $updateGoTo);
			$redirect_go_to = sprintf("Location: %s", stripslashes($updateGoTo));
		
		}

} else {
	$redirect_go_to = sprintf("Location: %s", $base_url."index.php?msg=98");
}
?>