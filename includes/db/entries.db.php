<?php
if(NHC) $totalRows_entry_count = total_paid_received($go,0);
else $totalRows_entry_count = total_paid_received($go,"default");
if ($section == "list") { 
	$query_log = sprintf("SELECT * FROM $brewing_db_table WHERE brewBrewerID = '%s' ORDER BY brewCategorySort, brewSubCategory, brewName $dir", $_SESSION['user_id']); 
	$query_log_paid = sprintf("SELECT * FROM $brewing_db_table WHERE brewBrewerID = '%s' AND NOT brewPaid='1'", $_SESSION['user_id']); 
	$query_log_confirmed = sprintf("SELECT * FROM $brewing_db_table  WHERE brewBrewerID = '%s' AND brewConfirmed='1'", $_SESSION['user_id']);
	}
		
elseif ($section == "pay") { 
	$query_log = sprintf("SELECT * FROM $brewing_db_table WHERE brewBrewerID = '%s' AND brewConfirmed='1' ORDER BY id ASC",  $_SESSION['user_id']); 
	$query_log_paid = "SELECT * FROM $brewing_db_table WHERE brewPaid='1' AND brewConfirmed='1'"; 
	$query_log_confirmed = "SELECT * FROM $brewing_db_table WHERE brewConfirmed='1'";
	}
	
elseif (($section == "brew") && ($action == "add")) {  
	$query_log = sprintf("SELECT * FROM $brewing_db_table WHERE brewBrewerID = '%s'", $_SESSION['user_id']); 
	$query_log_paid = sprintf("SELECT * FROM $brewing_db_table WHERE brewPaid='1'", $_SESSION['user_id']); 
	$query_log_confirmed = sprintf("SELECT * FROM $brewing_db_table WHERE brewConfirmed='1'", $_SESSION['user_id']);
	}

elseif (($section == "brew") && ($action == "edit")) {  
	$query_log = "SELECT * FROM $brewing_db_table WHERE id = '$id'"; 
	$query_log_paid = "SELECT * FROM $brewing_db_table WHERE brewPaid='1'"; 
	$query_log_confirmed = "SELECT * FROM $brewing_db_table WHERE brewConfirmed='1'";
	}
	
elseif (($section == "admin") && ($go == "entries") && ($filter == "default") && ($dbTable == "default") && ($bid == "default") && ($view == "default")) { 
	$query_log = "SELECT * FROM $brewing_db_table ORDER BY $sort $dir";
	if (($totalRows_entry_count > $_SESSION['prefsRecordLimit']) && ($view == "default")) $query_log .= " LIMIT $start, $display";
	$query_log_paid = "SELECT * FROM $brewing_db_table WHERE brewPaid='1'";
	$query_log_confirmed = "SELECT * FROM $brewing_db_table WHERE brewConfirmed='1'";
	}
	
elseif (($section == "admin") && ($go == "entries") && ($filter == "default") && ($dbTable == "default") && ($bid == "default") && ($view == "paid")) { 
	$query_log = "SELECT * FROM $brewing_db_table WHERE brewPaid='1'";
	if (($totalRows_entry_count > $_SESSION['prefsRecordLimit']) && ($view == "default")) $query_log .= " LIMIT $start, $display";
	$query_log_paid = "SELECT * FROM $brewing_db_table WHERE brewPaid='1'"; 
	$query_log_confirmed = "SELECT * FROM $brewing_db_table WHERE brewPaid='1' AND brewConfirmed='1'";
	}
elseif (($section == "admin") && ($go == "entries") && ($filter == "default") && ($dbTable == "default") && ($bid == "default") && ($view == "unpaid")) { 
	$query_log = "SELECT * FROM $brewing_db_table WHERE brewPaid='' OR brewPaid='0' OR brewPaid IS NULL";
	if (($totalRows_entry_count > $_SESSION['prefsRecordLimit']) && ($view == "default")) $query_log .= " LIMIT $start, $display";
	$query_log_paid = "SELECT * FROM $brewing_db_table WHERE brewPaid='' OR brewPaid='0' OR brewPaid IS NULL";
	$query_log_confirmed = "SELECT * FROM $brewing_db_table WHERE brewPaid='' OR brewPaid='0' OR brewPaid IS NULL AND brewConfirmed='1'";
	}
elseif (($section == "admin") && ($go == "entries") && ($filter != "default") && ($dbTable == "default") && ($bid == "default") && ($view == "default")) { 
	$query_log = "SELECT * FROM $brewing_db_table WHERE brewCategorySort='$filter' ORDER BY $sort $dir";
	if (($totalRows_entry_count > $_SESSION['prefsRecordLimit']) && ($view == "default")) $query_log .= " LIMIT $start, $display";
	$query_log_paid = "SELECT * FROM $brewing_db_table WHERE brewCategorySort='$filter' AND brewPaid='1' AND brewConfirmed='1'"; 
	$query_log_confirmed = "SELECT * FROM $brewing_db_table WHERE brewCategorySort='$filter' AND brewConfirmed='1'";
	}
elseif (($section == "admin") && ($go == "entries") && ($filter == "default") && ($dbTable != "default") && ($bid == "default") && ($view == "default")) { 
	$query_log = "SELECT * FROM $brewing_db_table ORDER BY $sort $dir";
	$query_log_paid = "SELECT * FROM $brewing_db_table WHERE brewPaid='1'";
	$query_log_confirmed = "SELECT * FROM $brewing_db_table WHERE brewConfirmed='1'";
	}
elseif (($section == "admin") && ($go == "entries") && ($filter != "default") && ($dbTable != "default") && ($bid == "default") && ($view == "default")) { 
	$query_log = "SELECT * FROM $brewing_db_table WHERE brewCategorySort='$filter' ORDER BY $sort $dir"; 
	$query_log_paid = "SELECT * FROM $brewing_db_table WHERE brewCategorySort='$filter' AND brewPaid='1'"; 
	$query_log_confirmed = "SELECT * FROM $brewing_db_table WHERE brewCategorySort='$filter' AND brewPaid='1' AND brewConfirmed='1'";
	}
elseif (($section == "admin") && ($go == "entries") && ($filter == "default") && ($bid != "default") && ($view == "default")) { 
	$query_log = "SELECT * FROM $brewing_db_table WHERE brewBrewerID='$bid' ORDER BY $sort $dir";
	if (($totalRows_entry_count > $_SESSION['prefsRecordLimit']) && ($view == "default")) $query_log .= " LIMIT $start, $display";
	$query_log_paid = "SELECT * FROM $brewing_db_table WHERE brewBrewerID='$bid' AND brewPaid='1'"; 
	$query_log_confirmed = "SELECT * FROM $brewing_db_table WHERE brewBrewerID='$bid' AND brewConfirmed='1'";
	}
else { 
	if (isset($_SESSION['loginUsername'])) $query_log = sprintf("SELECT * FROM $brewing_db_table WHERE brewBrewerID = '%s' ORDER BY brewCategorySort, brewSubCategory, brewName $dir", $_SESSION['user_id']);
	else $query_log = "SELECT * FROM $brewing_db_table";
	$query_log_paid = "SELECT * FROM $brewing_db_table WHERE brewPaid='1'"; 
	$query_log_confirmed = "SELECT * FROM $brewing_db_table WHERE brewConfirmed='1'";
	}
/*
echo $query_log."<br>";
echo $query_log_paid."<br>";
echo $query_confirmed."<br>";
echo $brewing_db_table."<br>";
echo $dbTable."<br>";
*/
$log = mysql_query($query_log, $brewing) or die(mysql_error());
$row_log = mysql_fetch_assoc($log);
$totalRows_log = mysql_num_rows($log); 
	
$log_paid = mysql_query($query_log_paid, $brewing) or die(mysql_error());
$row_log_paid = mysql_fetch_assoc($log_paid);
$totalRows_log_paid = mysql_num_rows($log_paid);

$log_confirmed = mysql_query($query_log_confirmed, $brewing) or die(mysql_error());
$row_log_confirmed = mysql_fetch_assoc($log_confirmed);
$totalRows_log_confirmed = mysql_num_rows($log_confirmed);

?>