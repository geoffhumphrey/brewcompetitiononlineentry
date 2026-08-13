<?php
declare(strict_types=1);
/*
 * Module:      process_delete.inc.php
 * Description: This module does all the heavy lifting for all DB deletes: new entries,
 *              new users, organization, etc.
 */

if ((isset($_SERVER['HTTP_REFERER'])) && ((isset($_SESSION['loginUsername'])) && (isset($_SESSION['userLevel'])))) {

	$db_conn->where('user_name', $_SESSION['loginUsername']);
	$row_user = $db_conn->getOne($users_db_table, "id,userLevel");

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
		unlink($upload_dir.basename($filter));
		if ($view == "html") $deleteGoTo = $base_url."index.php?section=admin&go=upload&action=html&msg=31";
		else $deleteGoTo = $base_url."index.php?section=admin&go=upload&msg=31";
		
		$deleteGoTo = prep_redirect_link($deleteGoTo);
		$redirect_go_to = sprintf("Location: %s", $deleteGoTo);

	}

	elseif (($admin_user) && ($go == "doc")) {
		
		$upload_dir = (USER_DOCS);
		unlink($upload_dir.basename($filter));
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

		$db_conn->where('sid', $id);
		$rows_delete_assign = $db_conn->get($special_best_data_db_table, null, "id");
		$totalRows_delete_assign = $db_conn->count;

		if ($totalRows_delete_assign > 0) {

			foreach ($rows_delete_assign as $row_delete_assign) {

				$aid = $row_delete_assign['id'];

				// Note: the delete below is redundant with a second delete of the same row that
				// existed in the original code (a duplicate DELETE on the same table/id) — preserved
				// as a harmless no-op rather than removed, since only the query parameterization changed.
				$update_table = $special_best_data_db_table;
				$db_conn->where ('id', $aid);
				$result = $db_conn->delete($update_table);
				if (!$result) {
					$error_output[] = $db_conn->getLastError();
					$errors = TRUE;
				}

				$db_conn->where('id', $aid);
				$db_conn->delete($special_best_data_db_table);
			}
		}

	}

	elseif (($admin_user) && ($go == "judging")) {

		// remove relational location ids from affected rows in brewer's table
		$rows_loc = $db_conn->get($brewer_db_table, null, "id, brewerJudgeLocation, brewerStewardLocation");
		$totalRows_loc = $db_conn->count;

		if ($totalRows_loc > 0) {

			foreach ($rows_loc as $row_loc) {

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

			}

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

			$db_conn->where('brewBrewerID', $id);
			$rows_entries = $db_conn->get($brewing_db_table, null, "id");

			$a = array();
			foreach ($rows_entries as $row_entries) { $a[] = $row_entries['id']; }

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
			$db_conn->where('bid', $id);
			$rows_judge_assign = $db_conn->get($judging_assignments_db_table, null, "id");

			$b = array();
			foreach ($rows_judge_assign as $row_judge_assign) { $b[] = $row_judge_assign['id']; }

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
			$db_conn->where('uid', $id);
			$rows_staff_assign = $db_conn->get($prefix."staff", null, "id");

			$c = array();
			foreach ($rows_staff_assign as $row_staff_assign) { $c[] = $row_staff_assign['id']; }

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

		$db_conn->where('id', $id);
		$row_brews = $db_conn->getOne($brewing_db_table, "id,brewStyle,brewCategory,brewCategorySort,brewSubCategory");

		// Get the entry's style ID
		// Determine if the style chosen is a cider - if so, run a different query
		if ($_SESSION['prefsStyleSet'] == "BJCP2025") {
			$first_character = mb_substr($row_brews['brewCategorySort'], 0, 1);
			$style_version = ($first_character == "C") ? 'BJCP2025' : 'BJCP2021';
		}
		else $style_version = $_SESSION['prefsStyleSet'];

		$query_style_name = "SELECT id FROM ".$prefix."styles WHERE (brewStyleVersion=? OR brewStyleOwn='custom') AND brewStyleGroup=? AND brewStyleNum=?";
		$row_style_name = $db_conn->rawQueryOne($query_style_name, array($style_version, $row_brews['brewCategorySort'], $row_brews['brewSubCategory']));

		table_limit($row_style_name['id'],1);

		$db_conn->where ('id', $id);
		$result = $db_conn->delete($dbTable);

		if (!$result) {
			$error_output[] = $db_conn->getLastError();
			$errors = TRUE;
		}

		$db_conn->where('eid', $id);
		$row_delete_entry = $db_conn->getOne($judging_scores_db_table, "id");
		$totalRows_delete_entry = $db_conn->count;

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

		$db_conn->where('scoreTable', $id);
		$rows_delete_assign = $db_conn->get($judging_scores_db_table, null, "id");
		$totalRows_delete_assign = $db_conn->count;

		$a = array();
		$b = array();
		$z = array();
		$c = array();

		if ($totalRows_delete_assign > 0) {

			foreach ($rows_delete_assign as $row_delete_assign) { $z[] = $row_delete_assign['id']; }

			foreach ($z as $aid) {

				$update_table = $prefix."judging_assignments";
				$db_conn->where ('id', $aid);
				$result = $db_conn->delete($update_table);
				if (!$result) {
					$error_output[] = $db_conn->getLastError();
					$errors = TRUE;
				}

			}

			$db_conn->where('scoreTable', $id);
			$rows_delete_scores = $db_conn->get($judging_scores_db_table, null, "id,eid");

			foreach ($rows_delete_scores as $row_delete_scores) { $a[] = $row_delete_scores['id']; $c[] = $row_delete_scores['eid']; }

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

		$db_conn->where('flightTable', $id);
		$rows_delete_flights = $db_conn->get($judging_flights_db_table, null, "id,flightTable");
		$totalRows_delete_flights = $db_conn->count;

		if ($totalRows_delete_flights > 0) {

			foreach ($rows_delete_flights as $row_delete_flights) { $b[] = $row_delete_flights['id']; }

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

					$db_conn->where('eid', $eid);
					$row_delete_bos = $db_conn->getOne($judging_scores_bos_db_table, "id,eid");

					if ($eid == $row_delete_bos['eid']) {

						$update_table = $prefix."judging_scores_bos";
						$db_conn->where ('id', $row_delete_bos['id']);
						$result = $db_conn->delete($update_table);
						if (!$result) {
							$error_output[] = $db_conn->getLastError();
							$errors = TRUE;
						}

						// Note: redundant duplicate delete of the same row, preserved as a harmless
						// no-op — pre-existing behavior, not changed by this parameterization pass.
						$db_conn->where('id', $row_delete_bos['id']);
						$db_conn->delete($judging_scores_bos_db_table);
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

		// Check if user is deleting their own stuff or is an Admin

		if ($dbTable == $prefix."brewing") {

			$entry_allow_delete = FALSE;

			if (($admin_user) || ($admin_superuser)) {

				// Note: the original query passed $row_user['id'] to sprintf() but the format string
				// only had one %s placeholder (for $id) — $row_user['id'] was silently discarded.
				// Preserved exactly: only $id is used as the lookup condition here.
				$db_conn->where('id', $id);
				$row_brews = $db_conn->getOne($brewing_db_table, "id,brewStyle,brewCategory,brewCategorySort,brewSubCategory");
				$entry_allow_delete = TRUE;

			}

			if ($row_user['userLevel'] == 2) {

				$db_conn->where('brewBrewerId', $row_user['id']);
				$db_conn->where('id', $id);
				$row_brews = $db_conn->getOne($brewing_db_table, "id,brewStyle,brewCategory,brewCategorySort,brewSubCategory");
				if ($row_brews) $entry_allow_delete = TRUE;

			}

			// Get the entry's style ID
			// Determine if the style chosen is a cider - if so, run a different query
			if ($_SESSION['prefsStyleSet'] == "BJCP2025") {
				$first_character = mb_substr($row_brews['brewCategorySort'], 0, 1);
				$style_version = ($first_character == "C") ? 'BJCP2025' : 'BJCP2021';
			}
			else $style_version = $_SESSION['prefsStyleSet'];

			$query_style_name = "SELECT id FROM ".$prefix."styles WHERE (brewStyleVersion=? OR brewStyleOwn='custom') AND brewStyleGroup=? AND brewStyleNum=?";
			$row_style_name = $db_conn->rawQueryOne($query_style_name, array($style_version, $row_brews['brewCategorySort'], $row_brews['brewSubCategory']));

			if ($entry_allow_delete) {

				$db_conn->where ('id', $id);
				$result = $db_conn->delete($dbTable);

				// If deleted successfully, use the table_limit function to check if there's an entry limit imposed on it's style's associated table.
				if ($result) {
					table_limit($row_style_name['id'],1);
				}

				else {
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