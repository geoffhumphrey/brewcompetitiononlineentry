<?php

$totalRows_entry_count = total_paid_received($go,0);

if (NHC) {
	// Place NHC SQL calls below

	$query_package_count = sprintf("SELECT a.scorePlace, a.scoreEntry FROM %s a, %s b, %s c WHERE a.eid = b.id AND c.uid = b.brewBrewerID AND b.brewBrewerID = '%s'", $judging_scores_db_table, $brewing_db_table, $brewer_db_table, $_SESSION['user_id']);
	if ($prefix != "final_") $query_package_count .= " AND a.scoreEntry >=25";
	$package_count = mysqli_query($connection,$query_package_count) or die (mysqli_error($connection));
	$row_package_count = mysqli_fetch_assoc($package_count);
	$totalRows_package_count = mysqli_num_rows($package_count);
	//echo $totalRows_package_count;

	$query_admin_adv = sprintf("SELECT COUNT(*) AS 'count' FROM $brewing_db_table WHERE brewBrewerID = '%s' AND brewWinner='6'", $_SESSION['user_id']);
	$admin_adv = mysqli_query($connection,$query_admin_adv) or die (mysqli_error($connection));
	$row_admin_adv = mysqli_fetch_assoc($admin_adv);
}
// end if (NHC)

else {

	if ($section == "list") {

		if (SINGLE) {
			$query_log = sprintf("SELECT * FROM %s WHERE brewBrewerID = '%s' AND comp_id='%s'", $brewing_db_table, $_SESSION['user_id'], $_SESSION['comp_id']);
			$query_log_paid = sprintf("SELECT * FROM %s WHERE brewBrewerID = '%s' AND comp_id='%s' AND NOT brewPaid='1'", $brewing_db_table, $_SESSION['user_id'], $_SESSION['comp_id']);
			$query_log_confirmed = sprintf("SELECT * FROM %s WHERE brewBrewerID = '%s' AND comp_id='%s' AND brewConfirmed='1'", $brewing_db_table, $_SESSION['user_id'], $_SESSION['comp_id']);
		}

		else {
			$query_log = sprintf("SELECT * FROM %s WHERE brewBrewerID = '%s'", $brewing_db_table, $_SESSION['user_id']);
			$query_log_paid = sprintf("SELECT * FROM %s WHERE brewBrewerID = '%s' AND NOT brewPaid='1'", $brewing_db_table, $_SESSION['user_id']);
			$query_log_confirmed = sprintf("SELECT * FROM %s WHERE brewBrewerID = '%s' AND brewConfirmed='1'", $brewing_db_table, $_SESSION['user_id']);
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

		if (SINGLE) {

			$query_log = sprintf("SELECT * FROM %s WHERE brewBrewerID = '%s' AND comp_id='%s'", $brewing_db_table, $_SESSION['user_id'], $_SESSION['comp_id']);
			$query_log_paid = sprintf("SELECT * FROM %s WHERE brewBrewerID = '%s' AND comp_id='%s' AND NOT brewPaid='1'", $brewing_db_table, $_SESSION['user_id'], $_SESSION['comp_id']);
			$query_log_confirmed = sprintf("SELECT * FROM %s WHERE brewBrewerID = '%s' AND comp_id='%s' AND brewConfirmed='1'", $brewing_db_table, $_SESSION['user_id'], $_SESSION['comp_id']);

			$query_contest_info = sprintf("SELECT contestEntryFeePassword FROM %s WHERE id='%s'", $prefix."contest_info", $_SESSION['comp_id']);
			$contest_info = mysqli_query($connection,$query_contest_info) or die (mysqli_error($connection));
			$row_contest_info = mysqli_fetch_assoc($contest_info);

		}

		else {

			$query_log = sprintf("SELECT * FROM %s WHERE brewBrewerID = '%s'", $brewing_db_table, $_SESSION['user_id']);
			$query_log_paid = sprintf("SELECT * FROM %s WHERE brewBrewerID = '%s' AND NOT brewPaid='1'", $brewing_db_table, $_SESSION['user_id']);
			$query_log_confirmed = sprintf("SELECT * FROM %s WHERE brewBrewerID = '%s' AND brewConfirmed='1'", $brewing_db_table, $_SESSION['user_id']);

			$query_contest_info = sprintf("SELECT contestEntryFeePassword FROM %s WHERE id=1", $prefix."contest_info");
			$contest_info = mysqli_query($connection,$query_contest_info) or die (mysqli_error($connection));
			$row_contest_info = mysqli_fetch_assoc($contest_info);

		}

	}

	elseif ((($section == "brew") || ($section == "beerxml")) && ($action == "add")) {

		if (SINGLE) {

			$query_log = sprintf("SELECT * FROM %s WHERE brewBrewerID = '%s' AND comp_id='%s'", $brewing_db_table, $_SESSION['user_id'], $_SESSION['comp_id']);
			$query_log_paid = sprintf("SELECT * FROM %s WHERE brewPaid='1' AND comp_id='%s'", $brewing_db_table, $_SESSION['user_id'], $_SESSION['comp_id']);
			$query_log_confirmed = sprintf("SELECT * FROM %s WHERE brewBrewerID = '%s' AND comp_id='%s' AND brewConfirmed='1'", $brewing_db_table, $_SESSION['user_id'], $_SESSION['comp_id']);

		}

		else {

			$query_log = sprintf("SELECT * FROM $brewing_db_table WHERE brewBrewerID = '%s'", $_SESSION['user_id']);
			$query_log_paid = sprintf("SELECT * FROM $brewing_db_table WHERE brewPaid='1'", $_SESSION['user_id']);
			$query_log_confirmed = sprintf("SELECT * FROM $brewing_db_table WHERE brewConfirmed='1'", $_SESSION['user_id']);

		}

	}

	elseif ((($section == "brew") || ($section == "beerxml")) && ($action == "edit")) {

		if (SINGLE) {

			$query_log = sprintf("SELECT * FROM %s WHERE id = '%S' AND comp_id='%s'", $brewing_db_table, $id, $_SESSION['comp_id']);
			$query_log_paid = sprintf("SELECT * FROM %s WHERE brewPaid='1' AND comp_id='%s'", $brewing_db_table, $_SESSION['comp_id']);
			$query_log_confirmed = sprintf("SELECT * FROM %s WHERE brewConfirmed='1' AND comp_id='%s'", $brewing_db_table, $_SESSION['comp_id']);

		}

		else {

			$query_log = sprintf("SELECT * FROM %s WHERE id = '%s'", $brewing_db_table, $id);
			$query_log_paid = sprintf("SELECT * FROM %s WHERE brewPaid='1'", $brewing_db_table);
			$query_log_confirmed = sprintf("SELECT * FROM %s WHERE brewConfirmed='1'", $brewing_db_table);

		}

	}

	elseif (($section == "admin") && ($go == "entries") && ($filter == "default") && ($dbTable == "default") && ($bid == "default") && ($view == "default")) {

		if (SINGLE) {

			$query_log = sprintf("SELECT * FROM %s", $brewing_db_table);
			$query_log_paid = sprintf("SELECT * FROM %s WHERE brewPaid='1' AND comp_id='%s'", $brewing_db_table, $_SESSION['comp_id']);
			$query_log_confirmed = sprintf("SELECT * FROM %s WHERE brewConfirmed='1' AND comp_id='%s'", $brewing_db_table, $_SESSION['comp_id']);

			$query_total_count = sprintf("SELECT COUNT(*) as 'count' FROM %s WHERE comp_id='%s'", $brewing_db_table, $_SESSION['comp_id']);
			$total_count = mysqli_query($connection,$query_total_count) or die (mysqli_error($connection));
			$row_total_count = mysqli_fetch_assoc($total_count);


		}

		else {

			$query_log = sprintf("SELECT * FROM %s", $brewing_db_table);
			$query_log_paid = sprintf("SELECT * FROM %s WHERE brewPaid='1'", $brewing_db_table);
			$query_log_confirmed = sprintf("SELECT * FROM %s WHERE brewConfirmed='1'", $brewing_db_table);

			$query_total_count = sprintf("SELECT COUNT(*) as 'count' FROM %s", $brewing_db_table);
			$total_count = mysqli_query($connection,$query_total_count) or die (mysqli_error($connection));
			$row_total_count = mysqli_fetch_assoc($total_count);

		}

	}

	elseif (($section == "admin") && ($go == "entries") && ($filter == "default") && ($dbTable == "default") && ($bid == "default") && ($view == "paid")) {

		if (SINGLE) {

			$query_log = sprintf("SELECT * FROM %s WHERE brewPaid='1'", $brewing_db_table);
			$query_log_paid = sprintf("SELECT * FROM %s WHERE brewPaid='1'", $brewing_db_table);
			$query_log_confirmed = sprintf("SELECT * FROM $brewing_db_table WHERE brewPaid='1' AND brewConfirmed='1'", $brewing_db_table);

			$query_total_count = sprintf("SELECT COUNT(*) as 'count' FROM %s WHERE comp_id='%s'", $brewing_db_table, $_SESSION['comp_id']);
			$total_count = mysqli_query($connection,$query_total_count) or die (mysqli_error($connection));
			$row_total_count = mysqli_fetch_assoc($total_count);

		}

		else {

			$query_log = sprintf("SELECT * FROM %s WHERE brewPaid='1'", $brewing_db_table);
			$query_log_paid = sprintf("SELECT * FROM %s WHERE brewPaid='1'", $brewing_db_table);
			$query_log_confirmed = sprintf("SELECT * FROM $brewing_db_table WHERE brewPaid='1' AND brewConfirmed='1'", $brewing_db_table);

			$query_total_count = sprintf("SELECT COUNT(*) as 'count' FROM %s", $brewing_db_table);
			$total_count = mysqli_query($connection,$query_total_count) or die (mysqli_error($connection));
			$row_total_count = mysqli_fetch_assoc($total_count);

		}

	}

	elseif (($section == "admin") && ($go == "entries") && ($filter == "default") && ($dbTable == "default") && ($bid == "default") && ($view == "unpaid")) {

		if (SINGLE) {

			$query_log = sprintf("SELECT * FROM %s WHERE (brewPaid='' OR brewPaid='0' OR brewPaid IS NULL) AND comp_id='%s'", $brewing_db_table, $_SESSION['comp_id']);
			$query_log_paid = sprintf("SELECT * FROM %s WHERE (brewPaid='' OR brewPaid='0' OR brewPaid IS NULL) AND comp_id='%s'", $brewing_db_table, $_SESSION['comp_id']);
			$query_log_confirmed = sprintf("SELECT * FROM %s WHERE (brewPaid='' OR brewPaid='0' OR brewPaid IS NULL) AND brewConfirmed='1' AND comp_id='%s'", $brewing_db_table, $_SESSION['comp_id']);

			$query_total_count = sprintf("SELECT COUNT(*) as 'count' FROM %s WHERE comp_id='%s'", $brewing_db_table, $_SESSION['comp_id']);
			$total_count = mysqli_query($connection,$query_total_count) or die (mysqli_error($connection));
			$row_total_count = mysqli_fetch_assoc($total_count);

		}

		else {

			$query_log = sprintf("SELECT * FROM %s WHERE brewPaid='' OR brewPaid='0' OR brewPaid IS NULL", $brewing_db_table);
			$query_log_paid = sprintf("SELECT * FROM %s WHERE brewPaid='' OR brewPaid='0' OR brewPaid IS NULL", $brewing_db_table);
			$query_log_confirmed = sprintf("SELECT * FROM %s WHERE brewPaid='' OR brewPaid='0' OR brewPaid IS NULL AND brewConfirmed='1'", $brewing_db_table);

			$query_total_count = sprintf("SELECT COUNT(*) as 'count' FROM %s", $brewing_db_table);
			$total_count = mysqli_query($connection,$query_total_count) or die (mysqli_error($connection));
			$row_total_count = mysqli_fetch_assoc($total_count);

		}

	}

	elseif (($section == "admin") && ($go == "entries") && ($filter != "default") && ($dbTable == "default") && ($bid == "default") && ($view == "default")) {

		if (SINGLE) {

			$query_log = sprintf("SELECT * FROM %s WHERE brewCategorySort='%s' AND comp_id='%s'",$brewing_db_table, $filter, $_SESSION['comp_id']);
			$query_log_paid = sprintf("SELECT * FROM %s WHERE brewCategorySort='%s' AND brewPaid='1' AND brewConfirmed='1' AND comp_id='%s'",$brewing_db_table, $filter, $_SESSION['comp_id']);
			$query_log_confirmed = sprintf("SELECT * FROM %s WHERE brewCategorySort='%s' AND brewConfirmed='1' AND comp_id='%s'",$brewing_db_table, $filter, $_SESSION['comp_id']);

			$query_total_count = sprintf("SELECT COUNT(*) as 'count' FROM %s WHERE comp_id='%s'", $brewing_db_table, $_SESSION['comp_id']);
			$total_count = mysqli_query($connection,$query_total_count) or die (mysqli_error($connection));
			$row_total_count = mysqli_fetch_assoc($total_count);

		}

		else {

			$query_log = sprintf("SELECT * FROM %s WHERE brewCategorySort='%s'",$brewing_db_table, $filter);
			$query_log_paid = sprintf("SELECT * FROM %s WHERE brewCategorySort='%s' AND brewPaid='1' AND brewConfirmed='1'",$brewing_db_table,$filter);
			$query_log_confirmed = sprintf("SELECT * FROM %s WHERE brewCategorySort='%s' AND brewConfirmed='1'",$brewing_db_table,$filter);

			$query_total_count = sprintf("SELECT COUNT(*) as 'count' FROM %s", $brewing_db_table);
			$total_count = mysqli_query($connection,$query_total_count) or die (mysqli_error($connection));
			$row_total_count = mysqli_fetch_assoc($total_count);

		}

	}

	elseif (($section == "admin") && ($go == "entries") && ($filter == "default") && ($dbTable != "default") && ($bid == "default") && ($view == "default")) {

		if (SINGLE) {

			$query_log = sprintf("SELECT * FROM %s AND comp_id='%s'",$brewing_db_table, $_SESSION['comp_id']);
			$query_log_paid = sprintf("SELECT * FROM %s WHERE brewPaid='1' AND comp_id='%s'",$brewing_db_table, $_SESSION['comp_id']);
			$query_log_confirmed = sprintf("SELECT * FROM %s WHERE brewConfirmed='1' AND comp_id='%s'",$brewing_db_table, $_SESSION['comp_id']);

			$query_total_count = sprintf("SELECT COUNT(*) as 'count' FROM %s WHERE comp_id='%s'", $brewing_db_table, $_SESSION['comp_id']);
			$total_count = mysqli_query($connection,$query_total_count) or die (mysqli_error($connection));
			$row_total_count = mysqli_fetch_assoc($total_count);

		}

		else {

			$query_log = sprintf("SELECT * FROM %s",$brewing_db_table);
			$query_log_paid = sprintf("SELECT * FROM %s WHERE brewPaid='1'",$brewing_db_table);
			$query_log_confirmed = sprintf("SELECT * FROM %s WHERE brewConfirmed='1'",$brewing_db_table);

			$query_total_count = sprintf("SELECT COUNT(*) as 'count' FROM %s", $brewing_db_table);
			$total_count = mysqli_query($connection,$query_total_count) or die (mysqli_error($connection));
			$row_total_count = mysqli_fetch_assoc($total_count);

		}

	}

	elseif (($section == "admin") && ($go == "entries") && ($filter != "default") && ($dbTable != "default") && ($bid == "default") && ($view == "default")) {

		if (SINGLE) {

			$query_log = sprintf("SELECT * FROM %s WHERE brewCategorySort='%s' AND comp_id='%s'",$brewing_db_table, $filter, $_SESSION['comp_id']);
			$query_log_paid = sprintf("SELECT * FROM %s WHERE brewCategorySort='%s' AND brewPaid='1' AND comp_id='%s'",$brewing_db_table, $filter, $_SESSION['comp_id']);
			$query_log_confirmed = sprintf("SELECT * FROM %s WHERE brewCategorySort='%s' AND brewConfirmed='1' AND comp_id='%s'",$brewing_db_table, $filter, $_SESSION['comp_id']);

			$query_total_count = sprintf("SELECT COUNT(*) as 'count' FROM %s WHERE comp_id='%s'", $brewing_db_table, $_SESSION['comp_id']);
			$total_count = mysqli_query($connection,$query_total_count) or die (mysqli_error($connection));
			$row_total_count = mysqli_fetch_assoc($total_count);

		}

		else {

			$query_log = sprintf("SELECT * FROM %s WHERE brewCategorySort='%s'",$brewing_db_table, $filter);
			$query_log_paid = sprintf("SELECT * FROM %s WHERE brewCategorySort='%s' AND brewPaid='1'",$brewing_db_table,$filter);
			$query_log_confirmed = sprintf("SELECT * FROM %s WHERE brewCategorySort='%s' AND brewConfirmed='1'",$brewing_db_table,$filter);

			$query_total_count = sprintf("SELECT COUNT(*) as 'count' FROM %s", $brewing_db_table);
			$total_count = mysqli_query($connection,$query_total_count) or die (mysqli_error($connection));
			$row_total_count = mysqli_fetch_assoc($total_count);

		}


	}

	elseif (($section == "admin") && ($go == "entries") && ($filter == "default") && ($bid != "default") && ($view == "default")) {

		if (SINGLE) {

			$query_log = sprintf("SELECT * FROM %s WHERE brewBrewerID='%s' AND comp_id='%s'", $brewing_db_table, $bid, $_SESSION['comp_id']);
			$query_log_paid = sprintf("SELECT * FROM %s WHERE brewBrewerID='%s' AND brewPaid='1' AND comp_id='%s'", $brewing_db_table, $bid, $_SESSION['comp_id']);
			$query_log_confirmed = sprintf("SELECT * FROM %s WHERE brewBrewerID='%s' AND brewConfirmed='1' AND comp_id='%s'", $brewing_db_table, $bid, $_SESSION['comp_id']);

			$query_total_count = sprintf("SELECT COUNT(*) as 'count' FROM %s WHERE comp_id='%s'", $brewing_db_table, $_SESSION['comp_id']);
			$total_count = mysqli_query($connection,$query_total_count) or die (mysqli_error($connection));
			$row_total_count = mysqli_fetch_assoc($total_count);

		}

		else {

			$query_log = sprintf("SELECT * FROM %s WHERE brewBrewerID='%s'", $brewing_db_table, $bid);
			$query_log_paid = sprintf("SELECT * FROM %s WHERE brewBrewerID='%s' AND brewPaid='1'", $brewing_db_table, $bid);
			$query_log_confirmed = sprintf("SELECT * FROM %s WHERE brewBrewerID='%s' AND brewConfirmed='1'", $brewing_db_table, $bid);

			$query_total_count = sprintf("SELECT COUNT(*) as 'count' FROM %s", $brewing_db_table);
			$total_count = mysqli_query($connection,$query_total_count) or die (mysqli_error($connection));
			$row_total_count = mysqli_fetch_assoc($total_count);


		}


	}

	elseif ($section == "notes") {
		$query_log = sprintf("SELECT * FROM %s WHERE brewPossAllergens IS NOT NULL", $brewing_db_table, $bid);
		$query_log_paid = sprintf("SELECT * FROM %s WHERE brewPossAllergens IS NOT NULL AND brewPaid='1'", $brewing_db_table, $bid);
		$query_log_confirmed = sprintf("SELECT * FROM %s WHERE brewPossAllergens IS NOT NULL AND brewConfirmed='1'", $brewing_db_table, $bid);
	}

	elseif (($section == "eval") && ($id != "default")) {
		$query_log = sprintf("SELECT * FROM %s WHERE id='%s'", $brewing_db_table, $id);
		$query_log_paid = sprintf("SELECT * FROM %s WHERE brewPossAllergens IS NOT NULL AND brewPaid='1'", $brewing_db_table, $bid);
		$query_log_confirmed = sprintf("SELECT * FROM %s WHERE brewPossAllergens IS NOT NULL AND brewConfirmed='1'", $brewing_db_table, $bid);
	}

	else {

		if (SINGLE) {

			if ((isset($_SESSION['loginUsername'])) && ($section != "admin")) $query_log = sprintf("SELECT * FROM %s WHERE brewBrewerID = '%s' AND comp_id='%s'", $brewing_db_table, $_SESSION['user_id'], $_SESSION['comp_id']);
			else $query_log = sprintf("SELECT * FROM %s WHERE comp_id='%s'", $brewing_db_table, $_SESSION['comp_id']);
			$query_log_paid = sprintf("SELECT * FROM %s WHERE brewPaid='1' AND comp_id='%s'", $brewing_db_table, $_SESSION['comp_id']);
			$query_log_confirmed = sprintf("SELECT * FROM %s WHERE brewConfirmed='1' AND comp_id='%s'", $brewing_db_table, $_SESSION['comp_id']);

		}

		else {

			if ((isset($_SESSION['loginUsername'])) && ($section != "admin")) $query_log = sprintf("SELECT * FROM %s WHERE brewBrewerID = '%s'", $brewing_db_table, $_SESSION['user_id']);
			else $query_log = sprintf("SELECT * FROM %s", $brewing_db_table);
			$query_log_paid = sprintf("SELECT * FROM %s WHERE brewReceived='1'", $brewing_db_table);
			$query_log_confirmed = sprintf("SELECT * FROM %s WHERE brewConfirmed='1'", $brewing_db_table);

		}

	}

	/*
	echo $query_log."<br>";
	echo $query_log_paid."<br>";
	echo $query_log_confirmed."<br>";
	echo $brewing_db_table."<br>";
	echo $dbTable."<br>";
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


}
?>