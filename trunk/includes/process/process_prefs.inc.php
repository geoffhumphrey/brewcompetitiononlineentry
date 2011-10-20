<?php 
/*
 * Module:      process_prefs_add.inc.php
 * Description: This module does all the heavy lifting for adding information to the 
 *              "preferences" table.
 */
if ($action == "add") {
	$insertSQL = sprintf("INSERT INTO preferences (
	prefsTemp, 
	prefsWeight1, 
	prefsWeight2, 
	prefsLiquid1, 
	prefsLiquid2,
	
	prefsPaypal, 
	prefsPaypalAccount, 
	prefsCurrency, 
	prefsCash, 
	prefsCheck,
	
	prefsCheckPayee, 
	prefsTransFee,
	prefsSponsors,
	prefsSponsorLogos,
	prefsSponsorLogoSize,
	
	prefsCompLogoSize,
	prefsDisplayWinners,
	prefsDisplaySpecial,
	prefsCompOrg,
	prefsEntryForm,
	
	prefsRecordLimit,
	prefsRecordPaging,
	prefsTheme,
	prefsDateFormat,
	prefsContact,
	id) VALUES (
	%s, %s, %s, %s, %s, 
	%s, %s, %s, %s, %s, 
	%s, %s, %s, %s, %s, 
	%s, %s, %s, %s, %s, 
	%s, %s, %s, %s, %s, %s)",
						   GetSQLValueString($_POST['prefsTemp'], "text"),
						   GetSQLValueString($_POST['prefsWeight1'], "text"),
						   GetSQLValueString($_POST['prefsWeight2'], "text"),
						   GetSQLValueString($_POST['prefsLiquid1'], "text"),
						   GetSQLValueString($_POST['prefsLiquid2'], "text"),
						   
						   GetSQLValueString($_POST['prefsPaypal'], "text"),
						   GetSQLValueString($_POST['prefsPaypalAccount'], "text"),
						   GetSQLValueString($_POST['prefsCurrency'], "text"),
						   GetSQLValueString($_POST['prefsCash'], "text"),
						   GetSQLValueString($_POST['prefsCheck'], "text"),
						   
						   GetSQLValueString($_POST['prefsCheckPayee'], "text"),
						   GetSQLValueString($_POST['prefsTransFee'], "text"),
						   GetSQLValueString($_POST['prefsSponsors'], "text"),
						   GetSQLValueString($_POST['prefsSponsorLogos'], "text"),
						   GetSQLValueString($_POST['prefsSponsorLogoSize'], "int"),
						   
						   GetSQLValueString($_POST['prefsCompLogoSize'], "int"),
						   GetSQLValueString($_POST['prefsDisplayWinners'], "text"),
						   GetSQLValueString($_POST['prefsDisplaySpecial'], "text"),
						   GetSQLValueString($_POST['prefsCompOrg'], "text"),
						   GetSQLValueString($_POST['prefsEntryForm'], "text"),
						   
						   GetSQLValueString($_POST['prefsRecordLimit'], "int"),
						   GetSQLValueString($_POST['prefsRecordPaging'], "int"),
						   GetSQLValueString($_POST['prefsTheme'], "text"),
						   GetSQLValueString($_POST['prefsDateFormat'], "text"),
						   GetSQLValueString($_POST['prefsContact'], "text"),
						   GetSQLValueString($id, "int"));
						   
		//echo $insertSQL;
		mysql_select_db($database, $brewing);
		$Result1 = mysql_query($insertSQL, $brewing) or die(mysql_error());
	
		$insertGoTo = "../setup.php?section=step4";
		header(sprintf("Location: %s", $insertGoTo));
}

if ($action == "edit") {
	
	$updateSQL = sprintf("UPDATE preferences SET 
	prefsTemp=%s, 
	prefsWeight1=%s, 
	prefsWeight2=%s, 
	prefsLiquid1=%s, 
	prefsLiquid2=%s, 
	prefsPaypal=%s, 
	prefsPaypalAccount=%s, 
	prefsCurrency=%s, 
	prefsCash=%s, 
	prefsCheck=%s, 
	prefsCheckPayee=%s, 
	prefsTransFee=%s, 
	prefsSponsors=%s, 
	prefsSponsorLogos=%s, 
	prefsSponsorLogoSize=%s, 
	prefsCompLogoSize=%s, 
	prefsDisplayWinners=%s, 
	prefsDisplaySpecial=%s, 
	prefsCompOrg=%s, 
	prefsEntryForm=%s,
	prefsRecordLimit=%s,
	prefsRecordPaging=%s,
	prefsTheme=%s,
	prefsDateFormat=%s,
	prefsContact=%s
	WHERE id=%s",
						   GetSQLValueString($_POST['prefsTemp'], "text"),
						   GetSQLValueString($_POST['prefsWeight1'], "text"),
						   GetSQLValueString($_POST['prefsWeight2'], "text"),
						   GetSQLValueString($_POST['prefsLiquid1'], "text"),
						   GetSQLValueString($_POST['prefsLiquid2'], "text"),
						   GetSQLValueString($_POST['prefsPaypal'], "text"),
						   GetSQLValueString($_POST['prefsPaypalAccount'], "text"),
						   GetSQLValueString($_POST['prefsCurrency'], "text"),
						   GetSQLValueString($_POST['prefsCash'], "text"),
						   GetSQLValueString($_POST['prefsCheck'], "text"),
						   GetSQLValueString($_POST['prefsCheckPayee'], "text"),
						   GetSQLValueString($_POST['prefsTransFee'], "text"),
						   GetSQLValueString($_POST['prefsSponsors'], "text"),
						   GetSQLValueString($_POST['prefsSponsorLogos'], "text"),
						   GetSQLValueString($_POST['prefsSponsorLogoSize'], "int"),
						   GetSQLValueString($_POST['prefsCompLogoSize'], "int"),
						   GetSQLValueString($_POST['prefsDisplayWinners'], "text"),
						   GetSQLValueString($_POST['prefsDisplaySpecial'], "text"),
						   GetSQLValueString($_POST['prefsCompOrg'], "text"),
						   GetSQLValueString($_POST['prefsEntryForm'], "text"),
						   GetSQLValueString($_POST['prefsRecordLimit'], "int"),
						   GetSQLValueString($_POST['prefsRecordPaging'], "int"),
						   GetSQLValueString($_POST['prefsTheme'], "text"),
						   GetSQLValueString($_POST['prefsDateFormat'], "text"),
						   GetSQLValueString($_POST['prefsContact'], "text"),
						   GetSQLValueString($id, "int"));
						   
		mysql_select_db($database, $brewing);
		$Result1 = mysql_query($updateSQL, $brewing) or die(mysql_error());
		header(sprintf("Location: %s", $updateGoTo));
	
}
?>