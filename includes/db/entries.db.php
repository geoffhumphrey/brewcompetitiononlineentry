<?php

$totalRows_entry_count = total_paid_received("",0);

if ($section == "list") {

	$query_log = "SELECT * FROM ".$brewing_db_table." WHERE brewBrewerID = ? ORDER BY brewCategorySort, brewSubCategory ASC";
	$params_log = array($_SESSION['user_id']);

	$query_log_paid = "SELECT * FROM ".$brewing_db_table." WHERE brewBrewerID = ? AND NOT brewPaid='1'";
	$params_log_paid = array($_SESSION['user_id']);

	$query_log_confirmed = "SELECT * FROM ".$brewing_db_table." WHERE brewBrewerID = ? AND brewConfirmed='1'";
	$params_log_confirmed = array($_SESSION['user_id']);

	$db_conn->where("id", 1);
	$row_contest_info = $db_conn->getOne($prefix."contest_info", "contestEntryFeePassword");

	if ($msg == "13") {

		$PayerID = "";
		if (isset($_GET['PayerID'])) $PayerID = sterilize($_GET['PayerID']);

		if ((!empty($view)) && (!empty($PayerID))) {

			// If redirected from PayPal, update the brewer table to mark entries as paid
			$b = sterilize($view);
			$a = explode('-', $b);

			foreach (array_unique($a) as $value) {

				// Ownership check: only mark an entry paid if it belongs to the current session's user,
				// preventing a logged-in user from marking another user's entries as paid via a forged view param.
				$update_table = $prefix."brewing";
				$data = array('brewPaid' => 1);
				$db_conn->where ('id', $value);
				$db_conn->where ('brewBrewerID', $_SESSION['user_id']);
				$result = $db_conn->update ($update_table, $data);
				if (!$result) {
					$error_output[] = $db_conn->getLastError();
					$errors = TRUE;
				}

			}

		}

	}

}

elseif ($section == "pay") {

	$query_log = "SELECT * FROM ".$brewing_db_table." WHERE brewBrewerID = ?";
	$params_log = array($_SESSION['user_id']);

	$query_log_paid = "SELECT * FROM ".$brewing_db_table." WHERE brewBrewerID = ? AND NOT brewPaid='1'";
	$params_log_paid = array($_SESSION['user_id']);

	$query_log_confirmed = "SELECT * FROM ".$brewing_db_table." WHERE brewBrewerID = ? AND brewConfirmed='1'";
	$params_log_confirmed = array($_SESSION['user_id']);

	$db_conn->where("id", 1);
	$row_contest_info = $db_conn->getOne($prefix."contest_info", "contestEntryFeePassword");

}

elseif (($section == "brew") && ($action == "add")) {

	$query_log = "SELECT * FROM ".$brewing_db_table." WHERE brewBrewerID = ?";
	$params_log = array($_SESSION['user_id']);

	$query_log_paid = "SELECT * FROM ".$brewing_db_table." WHERE brewPaid='1'";
	$params_log_paid = array();

	$query_log_confirmed = "SELECT * FROM ".$brewing_db_table." WHERE brewConfirmed='1'";
	$params_log_confirmed = array();

	if (SINGLE) {
		$query_log .= " AND comp_id=?"; $params_log[] = $_SESSION['comp_id'];
		$query_log_paid .= " AND comp_id=?"; $params_log_paid[] = $_SESSION['comp_id'];
		$query_log_confirmed .= " AND comp_id=?"; $params_log_confirmed[] = $_SESSION['comp_id'];
	}

}

elseif (($section == "brew") && ($action == "edit")) {

	$query_log = "SELECT * FROM ".$brewing_db_table." WHERE id = ?";
	$params_log = array($id);

	$query_log_paid = "SELECT * FROM ".$brewing_db_table." WHERE brewPaid='1'";
	$params_log_paid = array();

	$query_log_confirmed = "SELECT * FROM ".$brewing_db_table." WHERE brewConfirmed='1'";
	$params_log_confirmed = array();

	if (SINGLE) {
		$query_log .= " AND comp_id=?"; $params_log[] = $_SESSION['comp_id'];
		$query_log_paid .= " AND comp_id=?"; $params_log_paid[] = $_SESSION['comp_id'];
		$query_log_confirmed .= " AND comp_id=?"; $params_log_confirmed[] = $_SESSION['comp_id'];
	}

}

elseif ($section == "admin") {

	if ($go == "entries") {

		if ($action == "edit") {
			$query_log = "SELECT * FROM ".$brewing_db_table." WHERE id = ?";
			$params_log = array($id);
			$query_log_paid = "SELECT * FROM ".$brewing_db_table." WHERE brewPaid='1'";
			$params_log_paid = array();
			$query_log_confirmed = "SELECT * FROM ".$brewing_db_table." WHERE brewConfirmed='1'";
			$params_log_confirmed = array();
		}

		else {

			if ($dbTable != "default") {

				$dbTable_clean = preg_replace("/[^a-zA-Z0-9_]+/", "", $dbTable);
				$brewing_db_table = $dbTable_clean;
				$archive_array = array();

				// Check Archives DB table. If suffix is there good to go
				$get_suffix = get_suffix($dbTable);

				$rows_archive = $db_conn->get($archive_db_table);
				$totalRows_archive = $db_conn->count;

				if ($totalRows_archive > 0) {
					foreach ($rows_archive as $row_archive) {
						$archive_array[] = $row_archive['archiveSuffix'];
					}
				}

				if ((!empty($archive_array)) && (in_array($get_suffix,$archive_array))) $brewer_db_table = $prefix."brewer_".$get_suffix;

			}

			$query_log = sprintf("SELECT a.id, a.brewBrewerID, a.brewBoxNum, a.brewName, a.brewStyle, a.brewCategory, a.brewCategorySort, a.brewSubCategory, a.brewInfo, a.brewPossAllergens, a.brewPaid, a.brewReceived, a.brewAdminNotes, a.brewStaffNotes, a.brewJudgingNumber, a.brewUpdated, a.brewConfirmed, a.brewMead1, a.brewMead2, a.brewMead3, a.brewSweetnessLevel, a.brewABV, a.brewJuiceSource, a.brewInfoOptional, a.brewPouring, a.brewStyleType, a.brewPackaging, a.brewCoBrewer, b.brewerFirstName, b.uid, b.brewerBreweryName, b.brewerBreweryInfo, b.brewerLastName, b.brewerCity, b.brewerState, b.brewerCountry, b.brewerPhone1, b.brewerClubs, b.brewerProAm, b.brewerDiscount, b.brewerEmail FROM %s a, %s b WHERE a.brewBrewerID = b.uid", $brewing_db_table, $brewer_db_table);
			$params_log = array();

			if ($view == "paid") $query_log .= " AND a.brewPaid='1'";
			if ($view == "unpaid") $query_log .= " AND (a.brewPaid='' OR a.brewPaid='0' OR a.brewPaid IS NULL)";
			if ($filter != "default") { $query_log .= " AND a.brewCategorySort=?"; $params_log[] = $filter; }
			if ($bid != "default") { $query_log .= " AND b.uid=?"; $params_log[] = $bid; }

			$query_log_paid = "SELECT * FROM ".$brewing_db_table." WHERE brewPaid='1'";
			$params_log_paid = array();
			$query_log_confirmed = "SELECT * FROM ".$brewing_db_table." WHERE brewConfirmed='1'";
			$params_log_confirmed = array();

			if (SINGLE) {
				$query_log .= " AND comp_id=?"; $params_log[] = $_SESSION['comp_id'];
				$query_log_paid .= " AND comp_id=?"; $params_log_paid[] = $_SESSION['comp_id'];
				$db_conn->where("comp_id", $_SESSION['comp_id']);
			}

			$row_total_count = $db_conn->getOne($brewing_db_table, "COUNT(*) as 'count'");

			$query_log .= " ORDER BY a.id ASC";

		}

	} // end if ($go == "entries")

	else {

		if ((isset($_SESSION['loginUsername'])) && ($section != "admin")) {
			$query_log = "SELECT * FROM ".$brewing_db_table." WHERE brewBrewerID = ?";
			$params_log = array($_SESSION['user_id']);
		}
		else {
			$query_log = "SELECT * FROM ".$brewing_db_table;
			$params_log = array();
		}
		$query_log_paid = "SELECT * FROM ".$brewing_db_table." WHERE brewReceived='1'";
		$params_log_paid = array();
		$query_log_confirmed = "SELECT * FROM ".$brewing_db_table." WHERE brewConfirmed='1'";
		$params_log_confirmed = array();

		if (SINGLE) {
			if ((isset($_SESSION['loginUsername'])) && ($section != "admin")) { $query_log .= " AND comp_id=?"; $params_log[] = $_SESSION['comp_id']; }
			else { $query_log .= " WHERE comp_id=?"; $params_log[] = $_SESSION['comp_id']; }
			$query_log_paid .= " AND comp_id=?"; $params_log_paid[] = $_SESSION['comp_id'];
			$query_log_confirmed .= " AND comp_id=?"; $params_log_confirmed[] = $_SESSION['comp_id'];
		}

	}

} // end elseif ($section == "admin")

elseif ($section == "notes") {

	if (($go == "allergens") || ($go == "org_notes")) {

		$query_log = "SELECT * FROM ".$brewing_db_table." WHERE brewPossAllergens IS NOT NULL";
		$params_log = array();
		$query_log_paid = "SELECT * FROM ".$brewing_db_table." WHERE brewPossAllergens IS NOT NULL AND brewPaid='1'";
		$params_log_paid = array();
		$query_log_confirmed = "SELECT * FROM ".$brewing_db_table." WHERE brewPossAllergens IS NOT NULL AND brewConfirmed='1'";
		$params_log_confirmed = array();

		if (SINGLE) {
			$query_log .= " AND comp_id=?"; $params_log[] = $_SESSION['comp_id'];
			$query_log_paid .= " AND comp_id=?"; $params_log_paid[] = $_SESSION['comp_id'];
			$query_log_confirmed .= " AND comp_id=?"; $params_log_confirmed[] = $_SESSION['comp_id'];
		}

	}

	if ($go == "admin") {

		$query_log = "SELECT * FROM ".$brewing_db_table." WHERE brewAdminNotes IS NOT NULL OR brewStaffNotes IS NOT NULL";
		$params_log = array();
		$query_log_paid = "SELECT * FROM ".$brewing_db_table." WHERE (brewAdminNotes IS NOT NULL OR brewStaffNotes IS NOT NULL) AND brewPaid='1'";
		$params_log_paid = array();
		$query_log_confirmed = "SELECT * FROM ".$brewing_db_table." WHERE (brewAdminNotes IS NOT NULL OR brewStaffNotes IS NOT NULL) AND brewConfirmed='1'";
		$params_log_confirmed = array();

		if (SINGLE) {
			$query_log .= " AND comp_id=?"; $params_log[] = $_SESSION['comp_id'];
			$query_log_paid .= " AND comp_id=?"; $params_log_paid[] = $_SESSION['comp_id'];
			$query_log_confirmed .= " AND comp_id=?"; $params_log_confirmed[] = $_SESSION['comp_id'];
		}

	}



}

elseif (($section == "eval") && ($id != "default")) {

	$query_log = "SELECT * FROM ".$brewing_db_table." WHERE id=?";
	$params_log = array($id);
	$query_log_paid = "SELECT * FROM ".$brewing_db_table." WHERE brewPossAllergens IS NOT NULL AND brewPaid='1'";
	$params_log_paid = array();
	$query_log_confirmed = "SELECT * FROM ".$brewing_db_table." WHERE brewPossAllergens IS NOT NULL AND brewConfirmed='1'";
	$params_log_confirmed = array();

	if (SINGLE) {
		$query_log .= " AND comp_id=?"; $params_log[] = $_SESSION['comp_id'];
		$query_log_paid .= " AND comp_id=?"; $params_log_paid[] = $_SESSION['comp_id'];
		$query_log_confirmed .= " AND comp_id=?"; $params_log_confirmed[] = $_SESSION['comp_id'];
	}

}

else {

	if ((isset($_SESSION['loginUsername'])) && (isset($_SESSION['user_id'])) && ($section != "admin")) {
		$query_log = "SELECT * FROM ".$brewing_db_table." WHERE brewBrewerID = ?";
		$params_log = array($_SESSION['user_id']);
	}
	else {
		$query_log = "SELECT * FROM ".$brewing_db_table;
		$params_log = array();
	}
	$query_log_paid = "SELECT * FROM ".$brewing_db_table." WHERE brewReceived='1'";
	$params_log_paid = array();
	$query_log_confirmed = "SELECT * FROM ".$brewing_db_table." WHERE brewConfirmed='1'";
	$params_log_confirmed = array();

	if (SINGLE) {
		if ((isset($_SESSION['loginUsername'])) && ($section != "admin")) { $query_log .= " AND comp_id=?"; $params_log[] = $_SESSION['comp_id']; }
		else { $query_log .= " WHERE comp_id=?"; $params_log[] = $_SESSION['comp_id']; }
		$query_log_paid .= " AND comp_id=?"; $params_log_paid[] = $_SESSION['comp_id'];
		$query_log_confirmed .= " AND comp_id=?"; $params_log_confirmed[] = $_SESSION['comp_id'];
	}

}

/*
echo $query_log."<br>";
echo $query_log_paid."<br>";
echo $query_log_confirmed."<br>";
echo $brewing_db_table."<br>";
echo $brewer_db_table."<br>";
echo $dbTable."<br>";
exit();
*/

$rows_log = (!empty($params_log)) ? $db_conn->rawQuery($query_log, $params_log) : $db_conn->rawQuery($query_log);
$row_log = ($rows_log && count($rows_log) > 0) ? $rows_log[0] : null;
$totalRows_log = $db_conn->count;

$rows_log_paid = (!empty($params_log_paid)) ? $db_conn->rawQuery($query_log_paid, $params_log_paid) : $db_conn->rawQuery($query_log_paid);
$row_log_paid = ($rows_log_paid && count($rows_log_paid) > 0) ? $rows_log_paid[0] : null;
$totalRows_log_paid = $db_conn->count;

$rows_log_confirmed = (!empty($params_log_confirmed)) ? $db_conn->rawQuery($query_log_confirmed, $params_log_confirmed) : $db_conn->rawQuery($query_log_confirmed);
$row_log_confirmed = ($rows_log_confirmed && count($rows_log_confirmed) > 0) ? $rows_log_confirmed[0] : null;
$totalRows_log_confirmed = $db_conn->count;

?>