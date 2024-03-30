<?php

$totalRows_entry_count = total_paid_received("",0);

if ($section == "list") {
	
	$query_log = sprintf("SELECT * FROM %s WHERE brewBrewerID = '%s'", $brewing_db_table, $_SESSION['user_id']);
	$query_log_paid = sprintf("SELECT * FROM %s WHERE brewBrewerID = '%s' AND NOT brewPaid='1'", $brewing_db_table, $_SESSION['user_id']);
	$query_log_confirmed = sprintf("SELECT * FROM %s WHERE brewBrewerID = '%s' AND brewConfirmed='1'", $brewing_db_table, $_SESSION['user_id']);

	if (SINGLE) {
		$query_log .= sprintf(" AND comp_id='%s'", $_SESSION['comp_id']);
		$query_log_paid .= sprintf(" AND comp_id='%s'", $_SESSION['comp_id']);
		$query_log_confirmed .= sprintf(" AND comp_id='%s'", $_SESSION['comp_id']);
	}
		
}

elseif ($section == "pay") {

	if ($msg == "10") {
		// If redirected from PayPal, update the brewer table to mark entries as paid
		$b = sterilize($view);
		$a = explode('-', $b);
		foreach (array_unique($a) as $value) {
			$updateSQL = "UPDATE $brewing_db_table SET brewPaid='1' WHERE id='".$value."';";
			mysqli_real_escape_string($connection,$updateSQL);
			$result = mysqli_query($connection,$updateSQL) or die (mysqli_error($connection));
		}
	}

	$query_log = sprintf("SELECT * FROM %s WHERE brewBrewerID = '%s'", $brewing_db_table, $_SESSION['user_id']);
	$query_log_paid = sprintf("SELECT * FROM %s WHERE brewBrewerID = '%s' AND NOT brewPaid='1'", $brewing_db_table, $_SESSION['user_id']);
	$query_log_confirmed = sprintf("SELECT * FROM %s WHERE brewBrewerID = '%s' AND brewConfirmed='1'", $brewing_db_table, $_SESSION['user_id']);

	if (SINGLE) {
		$query_log .= sprintf(" AND comp_id='%s'", $_SESSION['comp_id']);
		$query_log_paid .= sprintf(" AND comp_id='%s'", $_SESSION['comp_id']);
		$query_log_confirmed .= sprintf(" AND comp_id='%s'", $_SESSION['comp_id']);

		$query_contest_info = sprintf("SELECT contestEntryFeePassword FROM %s WHERE id='%s'", $prefix."contest_info", $_SESSION['comp_id']);
		$contest_info = mysqli_query($connection,$query_contest_info) or die (mysqli_error($connection));
		$row_contest_info = mysqli_fetch_assoc($contest_info);
	}

	else {
		$query_contest_info = sprintf("SELECT contestEntryFeePassword FROM %s WHERE id=1", $prefix."contest_info");
		$contest_info = mysqli_query($connection,$query_contest_info) or die (mysqli_error($connection));
		$row_contest_info = mysqli_fetch_assoc($contest_info);
	}

}

elseif (($section == "brew") && ($action == "add")) {

	$query_log = sprintf("SELECT * FROM $brewing_db_table WHERE brewBrewerID = '%s'", $_SESSION['user_id']);
	$query_log_paid = sprintf("SELECT * FROM $brewing_db_table WHERE brewPaid='1'", $_SESSION['user_id']);
	$query_log_confirmed = sprintf("SELECT * FROM $brewing_db_table WHERE brewConfirmed='1'", $_SESSION['user_id']);

	if (SINGLE) {
		$query_log .= sprintf(" AND comp_id='%s'", $_SESSION['comp_id']);
		$query_log_paid .= sprintf(" AND comp_id='%s'", $_SESSION['comp_id']);
		$query_log_confirmed .= sprintf(" AND comp_id='%s'", $_SESSION['comp_id']);
	}

}

elseif (($section == "brew") && ($action == "edit")) {

	$query_log = sprintf("SELECT * FROM %s WHERE id = '%s'", $brewing_db_table, $id);
	$query_log_paid = sprintf("SELECT * FROM %s WHERE brewPaid='1'", $brewing_db_table);
	$query_log_confirmed = sprintf("SELECT * FROM %s WHERE brewConfirmed='1'", $brewing_db_table);

	if (SINGLE) {
		$query_log .= sprintf(" AND comp_id='%s'", $_SESSION['comp_id']);
		$query_log_paid .= sprintf(" AND comp_id='%s'", $_SESSION['comp_id']);
		$query_log_confirmed .= sprintf(" AND comp_id='%s'", $_SESSION['comp_id']);
	}

}

elseif ($section == "admin") {

	if ($go == "entries") {

		if ($dbTable != "default") {
			
			$brewing_db_table = $dbTable;
			$archive_array = array();

			// Check Archives DB table. If suffix is there good to go
			$get_suffix = get_suffix($dbTable);

			$query_archive = "SELECT * FROM $archive_db_table";
			$archive = mysqli_query($connection,$query_archive) or die (mysqli_error($connection));
			$row_archive = mysqli_fetch_assoc($archive);
			$totalRows_archive = mysqli_num_rows($archive);

			if ($totalRows_archive > 0) {
				do {
					$archive_array[] = $row_archive['archiveSuffix'];
				} while($row_archive = mysqli_fetch_assoc($archive));
			}

			if ((!empty($archive_array)) && (in_array($get_suffix,$archive_array))) $brewer_db_table = $prefix."brewer_".$get_suffix;

		}

		$query_log = sprintf("SELECT a.id, a.brewBrewerID, a.brewBoxNum, a.brewName, a.brewStyle, a.brewCategory, a.brewCategorySort, a.brewSubCategory, a.brewInfo, a.brewPossAllergens, a.brewPaid, a.brewReceived, a.brewAdminNotes, a.brewStaffNotes, a.brewJudgingNumber, a.brewUpdated, a.brewConfirmed, a.brewMead1, a.brewMead2, a.brewMead3, a.brewSweetnessLevel, a.brewABV, a.brewJuiceSource, a.brewInfoOptional, a.brewPouring, a.brewStyleType, a.brewPackaging, b.brewerFirstName, b.uid, b.brewerBreweryName, b.brewerBreweryTTB, b.brewerLastName, b.brewerCity, b.brewerState, b.brewerCountry, b.brewerPhone1, b.brewerClubs, b.brewerProAm, b.brewerDiscount, b.brewerEmail FROM %s a, %s b WHERE a.brewBrewerID = b.uid", $brewing_db_table, $brewer_db_table);
			
		if ($view == "paid") $query_log .= " AND a.brewPaid='1'";
		if ($view == "unpaid") $query_log .= " AND (a.brewPaid='' OR a.brewPaid='0' OR a.brewPaid IS NULL)";
		if ($filter != "default") $query_log .= sprintf(" AND a.brewCategorySort='%s'",$filter);
		if ($bid != "default") $query_log .= sprintf(" AND b.uid='%s'",$bid);

		$query_log_paid = sprintf("SELECT * FROM %s WHERE brewPaid='1'", $brewing_db_table);
		$query_log_confirmed = sprintf("SELECT * FROM %s WHERE brewConfirmed='1'", $brewing_db_table);

		$query_total_count = sprintf("SELECT COUNT(*) as 'count' FROM %s", $brewing_db_table);
		$total_count = mysqli_query($connection,$query_total_count) or die (mysqli_error($connection));
		$row_total_count = mysqli_fetch_assoc($total_count);

		if (SINGLE) {
			$query_log .= sprintf(" AND comp_id='%s'", $_SESSION['comp_id']);
			$query_log_paid .= sprintf(" AND comp_id='%s'", $_SESSION['comp_id']);
			$query_total_count .= sprintf(" WHERE comp_id='%s'", $_SESSION['comp_id']);
		}

		$query_log .= " ORDER BY a.id ASC";

	} // end if ($go == "entries")

	else {
		
		if ((isset($_SESSION['loginUsername'])) && ($section != "admin")) $query_log = sprintf("SELECT * FROM %s WHERE brewBrewerID = '%s'", $brewing_db_table, $_SESSION['user_id']);
		else $query_log = sprintf("SELECT * FROM %s", $brewing_db_table);
		$query_log_paid = sprintf("SELECT * FROM %s WHERE brewReceived='1'", $brewing_db_table);
		$query_log_confirmed = sprintf("SELECT * FROM %s WHERE brewConfirmed='1'", $brewing_db_table);

		if (SINGLE) {
			if ((isset($_SESSION['loginUsername'])) && ($section != "admin")) $query_log .= sprintf(" AND comp_id='%s'", $_SESSION['comp_id']);
			else $query_log .= sprintf(" WHERE comp_id='%s'", $_SESSION['comp_id']);
			$query_log_paid .= sprintf(" AND comp_id='%s'", $_SESSION['comp_id']);
			$query_log_confirmed .= sprintf(" AND comp_id='%s'", $_SESSION['comp_id']);
		}

	}

} // end elseif ($section == "admin")

elseif ($section == "notes") {
	
	if (($go == "allergens") || ($go == "org_notes")) {
		
		$query_log = sprintf("SELECT * FROM %s WHERE brewPossAllergens IS NOT NULL", $brewing_db_table);
		$query_log_paid = sprintf("SELECT * FROM %s WHERE brewPossAllergens IS NOT NULL AND brewPaid='1'", $brewing_db_table);
		$query_log_confirmed = sprintf("SELECT * FROM %s WHERE brewPossAllergens IS NOT NULL AND brewConfirmed='1'", $brewing_db_table);

		if (SINGLE) {
			$query_log .= sprintf(" AND comp_id='%s'", $_SESSION['comp_id']);
			$query_log_paid .= sprintf(" AND comp_id='%s'", $_SESSION['comp_id']);
			$query_log_confirmed .= sprintf(" AND comp_id='%s'", $_SESSION['comp_id']);
		}
	
	}

	if ($go == "admin") {

		$query_log = sprintf("SELECT * FROM %s WHERE brewAdminNotes IS NOT NULL OR brewStaffNotes IS NOT NULL", $brewing_db_table, $bid);
		$query_log_paid = sprintf("SELECT * FROM %s WHERE (brewAdminNotes IS NOT NULL OR brewStaffNotes IS NOT NULL) AND brewPaid='1'", $brewing_db_table, $bid);
		$query_log_confirmed = sprintf("SELECT * FROM %s WHERE (brewAdminNotes IS NOT NULL OR brewStaffNotes IS NOT NULL) AND brewConfirmed='1'", $brewing_db_table, $bid);

		if (SINGLE) {
			$query_log .= sprintf(" AND comp_id='%s'", $_SESSION['comp_id']);
			$query_log_paid .= sprintf(" AND comp_id='%s'", $_SESSION['comp_id']);
			$query_log_confirmed .= sprintf(" AND comp_id='%s'", $_SESSION['comp_id']);
		}

	}
	


}

elseif (($section == "eval") && ($id != "default")) {
	
	$query_log = sprintf("SELECT * FROM %s WHERE id='%s'", $brewing_db_table, $id);
	$query_log_paid = sprintf("SELECT * FROM %s WHERE brewPossAllergens IS NOT NULL AND brewPaid='1'", $brewing_db_table, $bid);
	$query_log_confirmed = sprintf("SELECT * FROM %s WHERE brewPossAllergens IS NOT NULL AND brewConfirmed='1'", $brewing_db_table, $bid);

	if (SINGLE) {
		$query_log .= sprintf(" AND comp_id='%s'", $_SESSION['comp_id']);
		$query_log_paid .= sprintf(" AND comp_id='%s'", $_SESSION['comp_id']);
		$query_log_confirmed .= sprintf(" AND comp_id='%s'", $_SESSION['comp_id']);
	}

}

else {

	if ((isset($_SESSION['loginUsername'])) && (isset($_SESSION['user_id'])) && ($section != "admin")) $query_log = sprintf("SELECT * FROM %s WHERE brewBrewerID = '%s'", $brewing_db_table, $_SESSION['user_id']);
	else $query_log = sprintf("SELECT * FROM %s", $brewing_db_table);
	$query_log_paid = sprintf("SELECT * FROM %s WHERE brewReceived='1'", $brewing_db_table);
	$query_log_confirmed = sprintf("SELECT * FROM %s WHERE brewConfirmed='1'", $brewing_db_table);

	if (SINGLE) {
		if ((isset($_SESSION['loginUsername'])) && ($section != "admin")) $query_log .= sprintf(" AND comp_id='%s'", $_SESSION['comp_id']);
		else $query_log .= sprintf(" WHERE comp_id='%s'", $_SESSION['comp_id']);
		$query_log_paid .= sprintf(" AND comp_id='%s'", $_SESSION['comp_id']);
		$query_log_confirmed .= sprintf(" AND comp_id='%s'", $_SESSION['comp_id']);
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

$log = mysqli_query($connection,$query_log) or die (mysqli_error($connection));
$row_log = mysqli_fetch_assoc($log);
$totalRows_log = mysqli_num_rows($log);

$log_paid = mysqli_query($connection,$query_log_paid) or die (mysqli_error($connection));
$row_log_paid = mysqli_fetch_assoc($log_paid);
$totalRows_log_paid = mysqli_num_rows($log_paid);

$log_confirmed = mysqli_query($connection,$query_log_confirmed) or die (mysqli_error($connection));
$row_log_confirmed = mysqli_fetch_assoc($log_confirmed);
$totalRows_log_confirmed = mysqli_num_rows($log_confirmed);

?>