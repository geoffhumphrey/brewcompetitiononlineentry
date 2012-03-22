<?php
$totalRows_entry_count = total_paid_received($go,"default");
if ($section == "list") { 
	$query_log = sprintf("SELECT * FROM brewing WHERE brewBrewerID = '%s' ORDER BY brewCategorySort, brewSubCategory, brewName $dir", $row_name['uid']); 
	$query_log_paid = "SELECT * FROM brewing WHERE brewBrewerID = '%s' AND NOT brewPaid='Y'"; 
	}
		
elseif ($section == "pay") { 
	$query_log = sprintf("SELECT * FROM brewing WHERE brewBrewerID = '%s' ORDER BY brewCategorySort, brewSubCategory, brewName $dir",  $row_name['uid']); 
	$query_log_paid = "SELECT * FROM brewing WHERE brewPaid='Y'"; 
	}
	
elseif (($section == "brew") && ($action == "edit")) { 
	$query_log = "SELECT * FROM brewing WHERE id = '$id'"; 
	$query_log_paid = "SELECT * FROM brewing WHERE brewPaid='Y'"; 
	}
	
elseif (($section == "admin") && ($go == "entries") && ($filter == "default") && ($dbTable == "default") && ($bid == "default")) { 
	$query_log = "SELECT * FROM brewing ORDER BY $sort $dir";
	if (($totalRows_entry_count > $row_prefs['prefsRecordLimit']) && ($view == "default")) $query_log .= " LIMIT $start, $display";
	$query_log_paid = "SELECT * FROM brewing WHERE brewPaid='Y'"; 
	}
elseif (($section == "admin") && ($go == "entries") && ($filter != "default") && ($dbTable == "default") && ($bid == "default")) { 
	$query_log = "SELECT * FROM brewing WHERE brewCategorySort='$filter' ORDER BY $sort $dir";
	if (($totalRows_entry_count > $row_prefs['prefsRecordLimit']) && ($view == "default")) $query_log .= " LIMIT $start, $display";
	$query_log_paid = "SELECT * FROM brewing WHERE brewCategorySort='$filter' AND brewPaid='Y'"; 
	}
elseif (($section == "admin") && ($go == "entries") && ($filter == "default") && ($dbTable != "default") && ($bid == "default")) { 
	$query_log = "SELECT * FROM $dbTable ORDER BY $sort $dir";
	//if ($view == "default") $query_log .= " LIMIT $start, $display";
	$query_log_paid = "SELECT * FROM $dbTable WHERE brewPaid='Y'"; 
	}
elseif (($section == "admin") && ($go == "entries") && ($filter != "default") && ($dbTable != "default") && ($bid == "default")) { 
	$query_log = "SELECT * FROM $dbTable WHERE brewCategorySort='$filter' ORDER BY $sort $dir"; 
	//if ($view == "default") $query_log .= " LIMIT $start, $display";
	$query_log_paid = "SELECT * FROM $dbTable WHERE brewCategorySort='$filter' AND brewPaid='Y'"; 
	}
elseif (($section == "admin") && ($go == "entries") && ($filter == "default") && ($bid != "default")) { 
	$query_log = "SELECT * FROM brewing WHERE brewBrewerID='$bid' ORDER BY $sort $dir";
	if (($totalRows_entry_count > $row_prefs['prefsRecordLimit']) && ($view == "default")) $query_log .= " LIMIT $start, $display";
	$query_log_paid = "SELECT * FROM brewing WHERE brewBrewerID='$bid' AND brewPaid='Y'"; 
	}
else { 
	$query_log = "SELECT * FROM brewing";
	$query_log_paid = "SELECT * FROM brewing WHERE brewPaid='Y'"; 
	}
$log = mysql_query($query_log, $brewing) or die(mysql_error());
$row_log = mysql_fetch_assoc($log);
$totalRows_log = mysql_num_rows($log);
	
$log_paid = mysql_query($query_log_paid, $brewing) or die(mysql_error());
$row_log_paid = mysql_fetch_assoc($log_paid);
$totalRows_log_paid = mysql_num_rows($log_paid);
?>