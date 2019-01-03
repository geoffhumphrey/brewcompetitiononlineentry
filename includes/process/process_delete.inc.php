<?php
/*
 * Module:      process_delete.inc.php
 * Description: This module does all the heavy lifting for all DB deletes: new entries,
 *              new users, organization, etc.
 */

if ((isset($_SERVER['HTTP_REFERER'])) && ((isset($_SESSION['loginUsername'])) && (isset($_SESSION['userLevel'])))) {

		$upload_dir = (USER_IMAGES);

		if ($go == "image") {

			unlink($upload_dir.$filter);
			if ($view == "html") $deleteGoTo = $base_url."index.php?section=admin&go=upload&action=html&msg=31";
			else $deleteGoTo = $base_url."index.php?section=admin&go=upload&msg=31";
			$redirect_go_to = sprintf("Location: %s", $deleteGoTo);

		}

		elseif ($go == "doc") {

			unlink($upload_dir.$filter);
			if ($view == "html") $deleteGoTo = $base_url."index.php?section=admin&go=upload_scoresheets&action=html&msg=31";
			else $deleteGoTo = $base_url."index.php?section=admin&go=upload_scoresheets&msg=31";
			$redirect_go_to = sprintf("Location: %s", $deleteGoTo);

		}

		elseif ($go == "judging_scores") {

			$deleteSQL = sprintf("DELETE FROM %s WHERE id='%s'", $prefix."judging_scores",$id);
			mysqli_real_escape_string($connection,$deleteSQL);
			$result = mysqli_query($connection,$deleteSQL) or die (mysqli_error($connection));
			$redirect_go_to = sprintf("Location: %s", $deleteGoTo);

		}

		elseif ($go == "special_best") {

			$deleteSQL = sprintf("DELETE FROM $dbTable WHERE id='%s'", $id);
			mysqli_real_escape_string($connection,$deleteSQL);
			$result = mysqli_query($connection,$deleteSQL) or die (mysqli_error($connection));

			$query_delete_assign = sprintf("SELECT id FROM $special_best_data_db_table WHERE sid='%s'", $id);
			$delete_assign = mysqli_query($connection,$query_delete_assign) or die (mysqli_error($connection));
			$row_delete_assign = mysqli_fetch_assoc($delete_assign);
			$totalRows_delete_assign = mysqli_num_rows($delete_assign);

			if ($totalRows_delete_assign > 0) {
				do { $z[] = $row_delete_assign['id']; } while ($row_delete_assign = mysqli_fetch_assoc($delete_assign));

				foreach ($z as $aid) {
					$deleteSQL = sprintf("DELETE FROM $special_best_data_db_table WHERE id='%s'", $aid);
					mysqli_real_escape_string($connection,$deleteSQL);
					$result = mysqli_query($connection,$deleteSQL) or die (mysqli_error($connection));
				}
			}

		}

		elseif ($go == "judging") {

			// remove relational location ids from affected rows in brewer's table
			$query_loc = "SELECT id,brewerJudgeLocation,brewerStewardLocation from $brewer_db_table";
			$loc = mysqli_query($connection,$query_loc) or die (mysqli_error($connection));
			$row_loc = mysqli_fetch_assoc($loc);
			$totalRows_loc = mysqli_num_rows($loc);

			do  {

				if ($row_loc['brewerJudgeLocation'] != "") {

				$a = explode(",",$row_loc['brewerJudgeLocation']);

					if ((in_array("Y-".$id,$a)) || (in_array("N-".$id,$a))) {

						foreach ($a as $b) {
							if ($b == "Y-".$id) $c[] = "";
							elseif ($b == "N-".$id) $c[] = "";
							else $c[] = $b.",";
						}

						$d = rtrim(implode("",$c),",");
						$updateSQL = "UPDATE $brewer_db_table SET brewerJudgeLocation='".$d."' WHERE id='".$row_loc['id']."'; ";
						mysqli_real_escape_string($connection,$updateSQL);
						$result = mysqli_query($connection,$updateSQL) or die (mysqli_error($connection));

						//echo $updateSQL."<br>";
						unset($c, $d);

					}

				unset($a);

				}

				if ($row_loc['brewerStewardLocation'] != "") {
				$e = explode(",",$row_loc['brewerStewardLocation']);
					if ((in_array("Y-".$id,$e)) || (in_array("N-".$id,$e))) {

						foreach ($e as $f) {

							if ($f == "Y-".$id) $g[] = "";
							elseif ($f == "N-".$id) $g[] = "";
							else $g[] = $f.",";

						}

						$h = rtrim(implode("",$g),",");
						$updateSQL = "UPDATE $brewer_db_table SET brewerStewardLocation='".$h."' WHERE id='".$row_loc['id']."'; ";
						mysqli_real_escape_string($connection,$updateSQL);
						$result = mysqli_query($connection,$updateSQL) or die (mysqli_error($connection));

						//echo $updateSQL."<br>";
						unset($g, $h);

					}

				unset($e);

				}

			} while ($row_loc = mysqli_fetch_assoc($loc));

			$deleteSQL = sprintf("DELETE FROM %s WHERE id='%s'", $prefix."judging_locations",$id);
			mysqli_real_escape_string($connection,$deleteSQL);
			$result = mysqli_query($connection,$deleteSQL) or die (mysqli_error($connection));

		} // end if ($go == "judging")

		elseif ($go == "participants") {

			if ($uid != "") {

				$deleteSQL = sprintf("DELETE FROM $users_db_table WHERE id='%s'", $id);
				mysqli_real_escape_string($connection,$deleteSQL);
				$result = mysqli_query($connection,$deleteSQL) or die (mysqli_error($connection));

				$deleteSQL = sprintf("DELETE FROM $brewer_db_table WHERE uid='%s'", $id);
				mysqli_real_escape_string($connection,$deleteSQL);
				$result = mysqli_query($connection,$deleteSQL) or die (mysqli_error($connection));

				$query_entries = sprintf("SELECT id from $brewing_db_table WHERE brewBrewerID='%s'",$id);
				$entries = mysqli_query($connection,$query_entries) or die (mysqli_error($connection));
				$row_entries = mysqli_fetch_assoc($entries);

				do { $a[] = $row_entries['id']; } while ($row_entries = mysqli_fetch_assoc($entries));

					sort($a);

					foreach ($a as $brew_id) {
						$deleteSQL = sprintf("DELETE FROM $brewing_db_table WHERE id='%s'", $brew_id);
						mysqli_real_escape_string($connection,$deleteSQL);
						$result = mysqli_query($connection,$deleteSQL) or die (mysqli_error($connection));

						$deleteSQL = sprintf("DELETE FROM $judging_scores_db_table WHERE eid='%s'", $brew_id);
						mysqli_real_escape_string($connection,$deleteSQL);
						$result = mysqli_query($connection,$deleteSQL) or die (mysqli_error($connection));

						$deleteScoreSQL = sprintf("DELETE FROM $judging_scores_bos_db_table WHERE eid='%s'", $brew_id);
						mysqli_real_escape_string($connection,$deleteSQL);
						$result = mysqli_query($connection,$deleteSQL) or die (mysqli_error($connection));
					}


				// Clear any Judging Assignments
				$query_judge_assign = sprintf("SELECT id from $judging_assignments_db_table WHERE bid='%s'",$id);
				$judge_assign = mysqli_query($connection,$query_judge_assign) or die (mysqli_error($connection));
				$row_judge_assign = mysqli_fetch_assoc($judge_assign);

				do { $b[] = $row_judge_assign['id']; } while ($row_judge_assign = mysqli_fetch_assoc($judge_assign));

					sort($b);

					foreach ($b as $judge_id) {
						$deleteSQL = sprintf("DELETE FROM $judging_assignments_db_table WHERE id='%s'", $judge_id);
						mysqli_real_escape_string($connection,$deleteSQL);
						$result = mysqli_query($connection,$deleteSQL) or die (mysqli_error($connection));
					}

				// Clear any Staff Assignments
				$query_staff_assign = sprintf("SELECT id from %s WHERE uid='%s'",$prefix."staff",$id);
				$staff_assign = mysqli_query($connection,$query_staff_assign) or die (mysqli_error($connection));
				$row_staff_assign = mysqli_fetch_assoc($staff_assign);

				do { $c[] = $row_staff_assign['id']; } while ($row_staff_assign = mysqli_fetch_assoc($staff_assign));

					sort($c);

					foreach ($c as $staff_id) {
						$deleteSQL = sprintf("DELETE FROM %s WHERE id='%s'", $prefix."staff", $staff_id);
						mysqli_real_escape_string($connection,$deleteSQL);
						$result = mysqli_query($connection,$deleteSQL) or die (mysqli_error($connection));
					}

			} else {

				$deleteSQL = sprintf("DELETE FROM $users_db_table WHERE id='%s'", $id);
				mysqli_real_escape_string($connection,$deleteSQL);
				$result = mysqli_query($connection,$deleteSQL) or die (mysqli_error($connection));

				$deleteSQL = sprintf("DELETE FROM $brewer_db_table WHERE uid='%s'", $id);
				mysqli_real_escape_string($connection,$deleteSQL);
				$result = mysqli_query($connection,$deleteSQL) or die (mysqli_error($connection));
			}
			//exit;
		} // end if ($go == "participants")

		elseif ($go == "entries") {

			$deleteSQL = sprintf("DELETE FROM $dbTable WHERE id='%s'", $id);
			mysqli_real_escape_string($connection,$deleteSQL);
			$result = mysqli_query($connection,$deleteSQL) or die (mysqli_error($connection));

			$query_delete_entry = sprintf("SELECT id FROM $judging_scores_db_table WHERE eid='%s'", $id);
			$delete_entry = mysqli_query($connection,$query_delete_entry) or die (mysqli_error($connection));
			$row_delete_entry = mysqli_fetch_assoc($delete_entry);

			$deleteSQL = sprintf("DELETE FROM $judging_scores_db_table WHERE id='%s'", $row_delete_entry['id']);
			mysqli_real_escape_string($connection,$deleteSQL);
			$result = mysqli_query($connection,$deleteSQL) or die (mysqli_error($connection));

		} // end if ($go == "entries")

		elseif ($go == "judging_tables") {

			$query_delete_assign = sprintf("SELECT id FROM $judging_scores_db_table WHERE scoreTable='%s'", $id);
			$delete_assign = mysqli_query($connection,$query_delete_assign) or die (mysqli_error($connection));
			$row_delete_assign = mysqli_fetch_assoc($delete_assign);
			$totalRows_delete_assign = mysqli_num_rows($delete_assign);

			$c = "";

			if ($totalRows_delete_assign > 0) {
				do { $z[] = $row_delete_assign['id']; } while ($row_delete_assign = mysqli_fetch_assoc($delete_assign));

				foreach ($z as $aid) {
					$deleteSQL = sprintf("DELETE FROM $judging_assignments_db_table WHERE id='%s'", $aid);
					mysqli_real_escape_string($connection,$deleteSQL);
					$result = mysqli_query($connection,$deleteSQL) or die (mysqli_error($connection));
				}

				$query_delete_scores = sprintf("SELECT id,eid FROM $judging_scores_db_table WHERE scoreTable='%s'", $id);
				$delete_scores = mysqli_query($connection,$query_delete_scores) or die (mysqli_error($connection));
				$row_delete_scores = mysqli_fetch_assoc($delete_scores);

				do { $a[] = $row_delete_scores['id']; $c[] = $row_delete_scores['eid']; } while ($row_delete_scores = mysqli_fetch_assoc($delete_scores));

				foreach ($a as $sid) {
					$deleteSQL = sprintf("DELETE FROM $judging_scores_db_table WHERE id='%s'", $sid);
					mysqli_real_escape_string($connection,$deleteSQL);
					$result = mysqli_query($connection,$deleteSQL) or die (mysqli_error($connection));
				}
			}

			$query_delete_flights = sprintf("SELECT id,flightTable FROM $judging_flights_db_table WHERE flightTable='%s'", $id);
			$delete_flights = mysqli_query($connection,$query_delete_flights) or die (mysqli_error($connection));
			$row_delete_flights = mysqli_fetch_assoc($delete_flights);
			$totalRows_delete_flights = mysqli_num_rows($delete_flights);

			if ($totalRows_delete_flights > 0) {
				do { $b[] = $row_delete_flights['id']; } while ($row_delete_flights = mysqli_fetch_assoc($delete_flights));

				foreach ($b as $fid) {
					$deleteSQL = sprintf("DELETE FROM $judging_flights_db_table WHERE id='%s'", $fid);
					mysqli_real_escape_string($connection,$deleteSQL);
					$result = mysqli_query($connection,$deleteSQL) or die (mysqli_error($connection));

				}

				if ($c != "") {

					foreach ($c as $eid) {
						$query_delete_bos = sprintf("SELECT id,eid FROM $judging_scores_bos_db_table WHERE eid='%s'", $eid);
						$delete_bos = mysqli_query($connection,$query_delete_bos) or die (mysqli_error($connection));
						$row_delete_bos = mysqli_fetch_assoc($delete_bos);

						if ($eid == $row_delete_bos['eid']) {
							$deleteSQL = sprintf("DELETE FROM $judging_scores_bos_db_table WHERE id='%s'", $row_delete_bos['id']);
							mysqli_real_escape_string($connection,$deleteSQL);
							$result = mysqli_query($connection,$deleteSQL) or die (mysqli_error($connection));
						}
					}
				}
			}

			$deleteSQL = sprintf("DELETE FROM $dbTable WHERE id='%s'", $id);
			mysqli_real_escape_string($connection,$deleteSQL);
			$result = mysqli_query($connection,$deleteSQL) or die (mysqli_error($connection));

		} // end if ($go == "judging_tables")

		elseif ($go == "default") {

			$deleteSQL = sprintf("DELETE FROM $dbTable WHERE id='%s'", $id);
			mysqli_real_escape_string($connection,$deleteSQL);
			$result = mysqli_query($connection,$deleteSQL) or die (mysqli_error($connection));

			if ($dbTable == $prefix."archive") {

				$tables_array = array($brewer_db_table, $brewing_db_table, $judging_assignments_db_table, $judging_flights_db_table, $judging_scores_db_table, $judging_scores_bos_db_table, $judging_tables_db_table, $special_best_info_db_table, $special_best_data_db_table, $sponsors_db_table, $staff_db_table, $style_types_db_table, $users_db_table);

				foreach ($tables_array as $table) {
					$table = $table."_".$filter;
					if (table_exists($table)) {
						$deleteSQL = sprintf("DROP TABLE %s",$table);
						mysqli_real_escape_string($connection,$deleteSQL);
						$result = mysqli_query($connection,$deleteSQL) or die (mysqli_error($connection));
					}
				}
			}
		}

		else {
			$deleteSQL = sprintf("DELETE FROM $dbTable WHERE id='%s'", $id);
			mysqli_real_escape_string($connection,$deleteSQL);
			$result = mysqli_query($connection,$deleteSQL) or die (mysqli_error($connection));
		}

		$redirect_go_to = sprintf("Location: %s", $deleteGoTo);

} else {
	$redirect_go_to = sprintf("Location: %s", $base_url."index.php?msg=98");
}
?>