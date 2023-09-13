<?php
/*
 * Module:      process_delete.inc.php
 * Description: This module does all the heavy lifting for all DB deletes: new entries,
 *              new users, organization, etc.
 */

if ((isset($_SERVER['HTTP_REFERER'])) && ((isset($_SESSION['loginUsername'])) && (isset($_SESSION['userLevel'])))) {

	$query_user = sprintf("SELECT id,userLevel FROM $users_db_table WHERE user_name = '%s'", $_SESSION['loginUsername']);
	$user = mysqli_query($connection,$query_user) or die (mysqli_error($connection));
	$row_user = mysqli_fetch_assoc($user);

	$admin_user = FALSE;
	$admin_superuser = FALSE;
	
	if ($row_user['userLevel'] == 0) {
		$admin_user = TRUE;
		$admin_superuser = TRUE;
	}

	if ($row_user['userLevel'] == 1) $admin_user = TRUE;

	$errors = FALSE;
	$error_output = array();
	$_SESSION['error_output'] = "";

	if (($admin_user) && ($go == "image")) {
		
		$upload_dir = (USER_IMAGES);
		unlink($upload_dir.$filter);
		if ($view == "html") $deleteGoTo = $base_url."index.php?section=admin&go=upload&action=html&msg=31";
		else $deleteGoTo = $base_url."index.php?section=admin&go=upload&msg=31";
		
		$deleteGoTo = prep_redirect_link($deleteGoTo);
		$redirect_go_to = sprintf("Location: %s", $deleteGoTo);

	}

	elseif (($admin_user) && ($go == "doc")) {
		
		$upload_dir = (USER_DOCS);
		unlink($upload_dir.$filter);
		if ($view == "html") $deleteGoTo = $base_url."index.php?section=admin&go=upload_scoresheets&action=html&msg=31";
		else $deleteGoTo = $base_url."index.php?section=admin&go=upload_scoresheets&msg=31";
		
		$deleteGoTo = prep_redirect_link($deleteGoTo);
		$redirect_go_to = sprintf("Location: %s", $deleteGoTo);

	}

	elseif (($admin_superuser) && ($go == "judging_scores")) {

		$update_table = $prefix."judging_scores";
		$db_conn->where ('id', $id);
		$result = $db_conn->delete($update_table);
		if (!$result) {
			$error_output[] = $db_conn->getLastError();
			$errors = TRUE;
		}

		$deleteGoTo = prep_redirect_link($deleteGoTo);
		$redirect_go_to = sprintf("Location: %s", $deleteGoTo);

	}

	elseif (($admin_superuser) && ($go == "special_best")) {

		$db_conn->where ('id', $id);
		$result = $db_conn->delete($dbTable);
		if (!$result) {
			$error_output[] = $db_conn->getLastError();
			$errors = TRUE;
		}

		$query_delete_assign = sprintf("SELECT id FROM %s WHERE sid='%s'", $special_best_data_db_table, $id);
		$delete_assign = mysqli_query($connection,$query_delete_assign) or die (mysqli_error($connection));
		$row_delete_assign = mysqli_fetch_assoc($delete_assign);
		$totalRows_delete_assign = mysqli_num_rows($delete_assign);

		if ($totalRows_delete_assign > 0) {
			do { $z[] = $row_delete_assign['id']; } while ($row_delete_assign = mysqli_fetch_assoc($delete_assign));

			foreach ($z as $aid) {

				$update_table = $special_best_data_db_table;
				$db_conn->where ('id', $aid);
				$result = $db_conn->delete($update_table);
				if (!$result) {
					$error_output[] = $db_conn->getLastError();
					$errors = TRUE;
				}


				$deleteSQL = sprintf("DELETE FROM %s WHERE id='%s'", $special_best_data_db_table, $aid);
				mysqli_real_escape_string($connection,$deleteSQL);
				$result = mysqli_query($connection,$deleteSQL) or die (mysqli_error($connection));
			}
		}

	}

	elseif (($admin_user) && ($go == "judging")) {

		// remove relational location ids from affected rows in brewer's table
		$query_loc = sprintf("SELECT id, brewerJudgeLocation, brewerStewardLocation from %s", $brewer_db_table);
		$loc = mysqli_query($connection,$query_loc) or die (mysqli_error($connection));
		$row_loc = mysqli_fetch_assoc($loc);
		$totalRows_loc = mysqli_num_rows($loc);

		if ($totalRows_loc > 0) {

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

						$update_table = $prefix."brewer";
						$data = array('brewerJudgeLocation' => $d);
						$db_conn->where ('id', $row_loc['id']);
						$result = $db_conn->update ($update_table, $data);
						if (!$result) {
							$error_output[] = $db_conn->getLastError();
							$errors = TRUE;
						}

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

						$update_table = $prefix."brewer";
						$data = array('brewerStewardLocation' => $h);
						$db_conn->where ('id', $row_loc['id']);
						$result = $db_conn->update ($update_table, $data);
						if (!$result) {
							$error_output[] = $db_conn->getLastError();
							$errors = TRUE;
						}

						unset($g, $h);

					}

				unset($e);

				}

			} while ($row_loc = mysqli_fetch_assoc($loc));

		}

		$update_table = $prefix."judging_locations";
		$db_conn->where ('id', $id);
		$result = $db_conn->delete($update_table);
		if (!$result) {
			$error_output[] = $db_conn->getLastError();
			$errors = TRUE;
		}

	} // end if ($go == "judging")

	elseif (($admin_superuser) && ($go == "participants")) {

		if ($uid != "") {

			$update_table = $prefix."users";
			$db_conn->where ('id', $id);
			$result = $db_conn->delete($update_table);
			if (!$result) {
				$error_output[] = $db_conn->getLastError();
				$errors = TRUE;
			}

			$update_table = $prefix."brewer";
			$db_conn->where ('uid', $id);
			$result = $db_conn->delete($update_table);
			if (!$result) {
				$error_output[] = $db_conn->getLastError();
				$errors = TRUE;
			}

			$query_entries = sprintf("SELECT id from $brewing_db_table WHERE brewBrewerID='%s'",$id);
			$entries = mysqli_query($connection,$query_entries) or die (mysqli_error($connection));
			$row_entries = mysqli_fetch_assoc($entries);

			do { $a[] = $row_entries['id']; } while ($row_entries = mysqli_fetch_assoc($entries));

				sort($a);

				foreach ($a as $brew_id) {

					$update_table = $prefix."brewing";
					$db_conn->where ('id', $brew_id);
					$result = $db_conn->delete($update_table);
					if (!$result) {
						$error_output[] = $db_conn->getLastError();
						$errors = TRUE;
					}

					$update_table = $prefix."judging_scores";
					$db_conn->where ('eid', $brew_id);
					$result = $db_conn->delete($update_table);
					if (!$result) {
						$error_output[] = $db_conn->getLastError();
						$errors = TRUE;
					}

					$update_table = $prefix."judging_scores_bos";
					$db_conn->where ('eid', $brew_id);
					$result = $db_conn->delete($update_table);
					if (!$result) {
						$error_output[] = $db_conn->getLastError();
						$errors = TRUE;
					}

				}

			// Clear any Judging Assignments
			$query_judge_assign = sprintf("SELECT id from $judging_assignments_db_table WHERE bid='%s'",$id);
			$judge_assign = mysqli_query($connection,$query_judge_assign) or die (mysqli_error($connection));
			$row_judge_assign = mysqli_fetch_assoc($judge_assign);

			do { $b[] = $row_judge_assign['id']; } while ($row_judge_assign = mysqli_fetch_assoc($judge_assign));

				sort($b);

				foreach ($b as $judge_id) {

					$update_table = $prefix."judging_assignments";
					$db_conn->where ('id', $judge_id);
					$result = $db_conn->delete($update_table);
					if (!$result) {
						$error_output[] = $db_conn->getLastError();
						$errors = TRUE;
					}

				}

			// Clear any Staff Assignments
			$query_staff_assign = sprintf("SELECT id from %s WHERE uid='%s'",$prefix."staff",$id);
			$staff_assign = mysqli_query($connection,$query_staff_assign) or die (mysqli_error($connection));
			$row_staff_assign = mysqli_fetch_assoc($staff_assign);

			do { $c[] = $row_staff_assign['id']; } while ($row_staff_assign = mysqli_fetch_assoc($staff_assign));

				sort($c);

				foreach ($c as $staff_id) {

					$update_table = $prefix."staff";
					$db_conn->where ('id', $staff_id);
					$result = $db_conn->delete($update_table);
					if (!$result) {
						$error_output[] = $db_conn->getLastError();
						$errors = TRUE;
					}

				}

		} else {

			$update_table = $prefix."users";
			$db_conn->where ('id', $id);
			$result = $db_conn->delete($update_table);
			if (!$result) {
				$error_output[] = $db_conn->getLastError();
				$errors = TRUE;
			}

			$update_table = $prefix."brewer";
			$db_conn->where ('uid', $id);
			$result = $db_conn->delete($update_table);
			if (!$result) {
				$error_output[] = $db_conn->getLastError();
				$errors = TRUE;
			}

		}

	} // end if ($go == "participants")

	elseif (($admin_user) && ($go == "entries")) {

		$db_conn->where ('id', $id);
		$result = $db_conn->delete($dbTable);
		if (!$result) {
			$error_output[] = $db_conn->getLastError();
			$errors = TRUE;
		}

		$query_delete_entry = sprintf("SELECT id FROM $judging_scores_db_table WHERE eid='%s'", $id);
		$delete_entry = mysqli_query($connection,$query_delete_entry) or die (mysqli_error($connection));
		$row_delete_entry = mysqli_fetch_assoc($delete_entry);
		$totalRows_delete_entry = mysqli_num_rows($delete_entry);

		if ($totalRows_delete_entry > 0) {

			$update_table = $prefix."judging_scores";
			$db_conn->where ('id', $row_delete_entry['id']);
			$result = $db_conn->delete($update_table);
			if (!$result) {
				$error_output[] = $db_conn->getLastError();
				$errors = TRUE;
			}

		}			

	} // end if ($go == "entries")

	elseif (($admin_user) && ($go == "judging_tables")) {

		$query_delete_assign = sprintf("SELECT id FROM $judging_scores_db_table WHERE scoreTable='%s'", $id);
		$delete_assign = mysqli_query($connection,$query_delete_assign) or die (mysqli_error($connection));
		$row_delete_assign = mysqli_fetch_assoc($delete_assign);
		$totalRows_delete_assign = mysqli_num_rows($delete_assign);

		$a = array();
		$b = array();
		$z = array();
		$c = array();

		if ($totalRows_delete_assign > 0) {
			
			do { $z[] = $row_delete_assign['id']; } while ($row_delete_assign = mysqli_fetch_assoc($delete_assign));

			foreach ($z as $aid) {

				$update_table = $prefix."judging_assignments";
				$db_conn->where ('id', $aid);
				$result = $db_conn->delete($update_table);
				if (!$result) {
					$error_output[] = $db_conn->getLastError();
					$errors = TRUE;
				}

			}

			$query_delete_scores = sprintf("SELECT id,eid FROM $judging_scores_db_table WHERE scoreTable='%s'", $id);
			$delete_scores = mysqli_query($connection,$query_delete_scores) or die (mysqli_error($connection));
			$row_delete_scores = mysqli_fetch_assoc($delete_scores);

			do { $a[] = $row_delete_scores['id']; $c[] = $row_delete_scores['eid']; } while ($row_delete_scores = mysqli_fetch_assoc($delete_scores));

			foreach ($a as $sid) {

				$update_table = $prefix."judging_scores";
				$db_conn->where ('id', $sid);
				$result = $db_conn->delete($update_table);
				if (!$result) {
					$error_output[] = $db_conn->getLastError();
					$errors = TRUE;
				}

			}

		}

		$query_delete_flights = sprintf("SELECT id,flightTable FROM $judging_flights_db_table WHERE flightTable='%s'", $id);
		$delete_flights = mysqli_query($connection,$query_delete_flights) or die (mysqli_error($connection));
		$row_delete_flights = mysqli_fetch_assoc($delete_flights);
		$totalRows_delete_flights = mysqli_num_rows($delete_flights);

		if ($totalRows_delete_flights > 0) {
			
			do { $b[] = $row_delete_flights['id']; } while ($row_delete_flights = mysqli_fetch_assoc($delete_flights));

			foreach ($b as $fid) {

				$update_table = $prefix."judging_flights";
				$db_conn->where ('id', $fid);
				$result = $db_conn->delete($update_table);
				if (!$result) {
					$error_output[] = $db_conn->getLastError();
					$errors = TRUE;
				}

			}

			if ($c != "") {

				foreach ($c as $eid) {
					
					$query_delete_bos = sprintf("SELECT id,eid FROM $judging_scores_bos_db_table WHERE eid='%s'", $eid);
					$delete_bos = mysqli_query($connection,$query_delete_bos) or die (mysqli_error($connection));
					$row_delete_bos = mysqli_fetch_assoc($delete_bos);

					if ($eid == $row_delete_bos['eid']) {

						$update_table = $prefix."judging_scores_bos";
						$db_conn->where ('id', $row_delete_bos['id']);
						$result = $db_conn->delete($update_table);
						if (!$result) {
							$error_output[] = $db_conn->getLastError();
							$errors = TRUE;
						}

						$deleteSQL = sprintf("DELETE FROM $judging_scores_bos_db_table WHERE id='%s'", $row_delete_bos['id']);
						mysqli_real_escape_string($connection,$deleteSQL);
						$result = mysqli_query($connection,$deleteSQL) or die (mysqli_error($connection));
					}

				}

			}

		}

		$db_conn->where ('id', $id);
		$result = $db_conn->delete($dbTable);
		if (!$result) {
			$error_output[] = $db_conn->getLastError();
			$errors = TRUE;
		}

	} // end if ($go == "judging_tables")

	elseif (($admin_superuser) && ($go == "archive")) {

		$delete_suffix = "_".$filter; 

		$drop_tables_array = array(
			$prefix."brewer".$delete_suffix,
			$prefix."brewing".$delete_suffix,
			$prefix."evaluation".$delete_suffix,
			$prefix."judging_assignments".$delete_suffix,
			$prefix."judging_flights".$delete_suffix,
			$prefix."judging_scores".$delete_suffix,
			$prefix."judging_scores_bos".$delete_suffix,
			$prefix."judging_tables".$delete_suffix,
			$prefix."special_best_data".$delete_suffix,
			$prefix."special_best_info".$delete_suffix,
			$prefix."staff".$delete_suffix,
			$prefix."style_types".$delete_suffix,
			$prefix."users".$delete_suffix
		);
		
		foreach ($drop_tables_array as $table) {
			
			if (check_setup($table,$database)) {

				$sql = sprintf("DROP TABLE %s", $table);
				$db_conn->rawQuery($sql);
				if ($db_conn->getLastErrno() !== 0) {
					$error_output[] = $db_conn->getLastError();
					$errors = TRUE;
				}
				
			}

		}

		$db_conn->where ('id', $id);
		$result = $db_conn->delete($dbTable);
		if (!$result) {
			$error_output[] = $db_conn->getLastError();
			$errors = TRUE;
		}

	}

	elseif ($go == "default") {

		// Check if user is deleting their own stuff

		if ($dbTable == $prefix."brewing") {

			$entry_allow_delete = FALSE;

			if (($admin_user) || ($admin_superuser)) $entry_allow_delete = TRUE;

			if ($row_user['userLevel'] == 2) {

				$query_brews = sprintf("SELECT id FROM $brewing_db_table WHERE brewBrewerId = '%s' AND id='%s'", $row_user['id'], $id);
				$brews = mysqli_query($connection,$query_brews) or die (mysqli_error($connection));
				$row_brews = mysqli_fetch_assoc($brews);

				if ($row_brews) $entry_allow_delete = TRUE;

			}

			if ($entry_allow_delete) {

				$db_conn->where ('id', $id);
				$result = $db_conn->delete($dbTable);
				if (!$result) {
					$error_output[] = $db_conn->getLastError();
					$errors = TRUE;
				}

			}

			else {

				$redirect = $base_url."index.php?msg=98";
				$redirect = prep_redirect_link($redirect);
				$redirect_go_to = sprintf("Location: %s", $redirect);
				header($redirect_go_to);
				exit();

			}

		}

		elseif (($admin_superuser) && ($dbTable == $prefix."archive")) {

			$tables_array = array($brewer_db_table, $brewing_db_table, $judging_assignments_db_table, $judging_flights_db_table, $judging_scores_db_table, $judging_scores_bos_db_table, $judging_tables_db_table, $special_best_info_db_table, $special_best_data_db_table, $sponsors_db_table, $staff_db_table, $style_types_db_table, $users_db_table);

			foreach ($tables_array as $table) {

				$table = $table."_".$filter;

				if (table_exists($table)) {

					$sql = sprintf("DROP TABLE %s", $table);
					$db_conn->rawQuery($sql);
					if ($db_conn->getLastErrno() !== 0) {
						$error_output[] = $db_conn->getLastError();
						$errors = TRUE;
					}

				}

			}

		}

		else {

			if ($admin_superuser) {
				
				$db_conn->where ('id', $id);
				$result = $db_conn->delete($dbTable);
				if (!$result) {
					$error_output[] = $db_conn->getLastError();
					$errors = TRUE;
				}
			
			}

		}

	}

	else {

		if ($admin_superuser) {
			
			$db_conn->where ('id', $id);
			$result = $db_conn->delete($dbTable);
			if (!$result) {
				$error_output[] = $db_conn->getLastError();
				$errors = TRUE;
			}
		
		}

	}

	if (!empty($error_output)) $_SESSION['error_output'] = $error_output;

	if ($errors) $deleteGoTo = $base_url."index.php?section=admin&msg=3";
	$deleteGoTo = prep_redirect_link($deleteGoTo);
	$redirect_go_to = sprintf("Location: %s", $deleteGoTo);

} else {

	$redirect = $base_url."index.php?msg=98";
	$redirect = prep_redirect_link($redirect);
	$redirect_go_to = sprintf("Location: %s", $redirect);

}
?>