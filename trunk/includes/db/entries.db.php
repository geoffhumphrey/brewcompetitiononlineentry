<?php
$totalRows_entry_count = total_paid_received($go,"default");
if ($section == "list") { 
	$query_log = sprintf("SELECT * FROM $brewing_db_table WHERE brewBrewerID = '%s' ORDER BY brewCategorySort, brewSubCategory, brewName $dir", $row_name['uid']); 
	$query_log_paid = "SELECT * FROM $brewing_db_table WHERE brewBrewerID = '%s' AND NOT brewPaid='1'"; 
	$query_log_confirmed = "SELECT * FROM $brewing_db_table  WHERE brewBrewerID = '%s' AND brewConfirmed='1'";
	}
		
elseif ($section == "pay") { 
	$query_log = sprintf("SELECT * FROM $brewing_db_table WHERE brewBrewerID = '%s' AND brewConfirmed='1' ORDER BY id ASC",  $row_name['uid']); 
	$query_log_paid = "SELECT * FROM $brewing_db_table WHERE brewPaid='1' AND brewConfirmed='1'"; 
	$query_log_confirmed = "SELECT * FROM $brewing_db_table WHERE brewConfirmed='1'";
	}
	
elseif (($section == "brew") && ($action == "edit")) {  
	$query_log = "SELECT * FROM $brewing_db_table WHERE id = '$id'"; 
	$query_log_paid = "SELECT * FROM $brewing_db_table WHERE brewPaid='1'"; 
	$query_log_confirmed = "SELECT * FROM $brewing_db_table WHERE brewConfirmed='1'";
	}
	
elseif (($section == "admin") && ($go == "entries") && ($filter == "default") && ($dbTable == "default") && ($bid == "default") && ($view == "default")) { 
	$query_log = "SELECT * FROM $brewing_db_table ORDER BY $sort $dir";
	if (($totalRows_entry_count > $row_prefs['prefsRecordLimit']) && ($view == "default")) $query_log .= " LIMIT $start, $display";
	$query_log_paid = "SELECT * FROM $brewing_db_table WHERE brewPaid='1'";
	$query_log_confirmed = "SELECT * FROM $brewing_db_table WHERE brewConfirmed='1'";
	}
elseif (($section == "admin") && ($go == "entries") && ($filter == "default") && ($dbTable == "default") && ($bid == "default") && ($view == "paid")) { 
	$query_log = "SELECT * FROM $brewing_db_table WHERE brewPaid='1'";
	if (($totalRows_entry_count > $row_prefs['prefsRecordLimit']) && ($view == "default")) $query_log .= " LIMIT $start, $display";
	$query_log_paid = "SELECT * FROM $brewing_db_table WHERE brewPaid='1'"; 
	$query_log_confirmed = "SELECT * FROM $brewing_db_table WHERE brewPaid='1' AND brewConfirmed='1'";
	}
elseif (($section == "admin") && ($go == "entries") && ($filter == "default") && ($dbTable == "default") && ($bid == "default") && ($view == "unpaid")) { 
	$query_log = "SELECT * FROM $brewing_db_table WHERE brewPaid='' OR brewPaid='0'";
	if (($totalRows_entry_count > $row_prefs['prefsRecordLimit']) && ($view == "default")) $query_log .= " LIMIT $start, $display";
	$query_log_paid = "SELECT * FROM $brewing_db_table WHERE brewPaid='' OR brewPaid='0'";
	$query_log_confirmed = "SELECT * FROM $brewing_db_table WHERE brewPaid='' OR brewPaid='0' AND brewConfirmed='1'";
	}
elseif (($section == "admin") && ($go == "entries") && ($filter != "default") && ($dbTable == "default") && ($bid == "default") && ($view == "default")) { 
	$query_log = "SELECT * FROM $brewing_db_table WHERE brewCategorySort='$filter' ORDER BY $sort $dir";
	if (($totalRows_entry_count > $row_prefs['prefsRecordLimit']) && ($view == "default")) $query_log .= " LIMIT $start, $display";
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
	if (($totalRows_entry_count > $row_prefs['prefsRecordLimit']) && ($view == "default")) $query_log .= " LIMIT $start, $display";
	$query_log_paid = "SELECT * FROM $brewing_db_table WHERE brewBrewerID='$bid' AND brewPaid='1' AND brewConfirmed='1'"; 
	$query_confirmed = "SELECT * FROM $brewing_db_table WHERE brewBrewerID='$bid' AND brewConfirmed='1' ORDER BY $sort $dir";
	}
else { 
	$query_log = "SELECT * FROM $brewing_db_table";
	$query_log_paid = "SELECT * FROM $brewing_db_table WHERE brewPaid='1'"; 
	$query_log_confirmed = "SELECT * FROM $brewing_db_table WHERE brewConfirmed='1'";
	}
	
	
//echo $query_log."<br>";
//echo $query_log_paid."<br>";
//echo $query_confirmed."<br>";


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