<?php 
/*
 * Module:      process_comp_info.inc.php
 * Description: This module does all the heavy lifting for adding/editing information in the 
 *              "contest_info" table.
 */
 
// --------------------------------------- Adding (SETUP ONLY) ----------------------------------------

if ($action == "add") {
	if (($_POST['contestEntryFee2'] == "") || ($_POST['contestEntryFeeDiscountNum'] == "")) $contestEntryFeeDiscount = "N"; 
	if (($_POST['contestEntryFee2'] != "") && ($_POST['contestEntryFeeDiscountNum'] != "")) $contestEntryFeeDiscount = "Y"; 
	
	$insertSQL = sprintf("INSERT INTO contest_info (
	contestName,
	contestID,
	contestHost, 
	contestHostWebsite, 
	contestHostLocation,
	
	contestRegistrationOpen,
	contestRegistrationDeadline, 
	contestEntryOpen,
	contestEntryDeadline, 
	contestRules,
	
	contestAwardsLocation, 
	contestContactName, 
	contestContactEmail, 
	contestEntryFee, 
	contestBottles, 
	
	contestShippingAddress, 
	contestShippingName, 
	contestAwards,
	contestWinnersComplete,
	contestEntryCap,
	
	contestAwardsLocName,
	contestAwardsLocDate,
	contestAwardsLocTime,
	contestEntryFee2,
	contestEntryFeeDiscount,
	
	contestEntryFeeDiscountNum,
	contestLogo,
	contestBOSAward,
	contestEntryFeePassword,
	contestEntryFeePasswordNum,
	
	contestCircuit,
	id
	) 
	VALUES 
	(
	%s, %s, %s, %s, %s, 
	%s, %s, %s, %s, %s,
	%s, %s, %s, %s, %s, 
	%s, %s, %s, %s, %s, 
	%s, %s, %s, %s, %s,
	%s, %s, %s, %s, %s,
	%s, %s)",
						   GetSQLValueString(capitalize($_POST['contestName']), "text"),
						   GetSQLValueString($_POST['contestID'], "text"),
						   GetSQLValueString(capitalize($_POST['contestHost']), "text"),
						   GetSQLValueString($_POST['contestHostWebsite'], "text"),
						   GetSQLValueString(capitalize($_POST['contestHostLocation']), "text"),
						   GetSQLValueString($_POST['contestRegistrationOpen'], "date"),
						   GetSQLValueString($_POST['contestRegistrationDeadline'], "date"),
						   GetSQLValueString($_POST['contestEntryOpen'], "date"),
						   GetSQLValueString($_POST['contestEntryDeadline'], "date"),
						   GetSQLValueString($_POST['contestRules'], "text"),
						   GetSQLValueString(capitalize($_POST['contestAwardsLocation']), "text"),
						   GetSQLValueString($_POST['contestContactName'], "text"),
						   GetSQLValueString($_POST['contestContactEmail'], "text"),
						   GetSQLValueString($_POST['contestEntryFee'], "text"),
						   GetSQLValueString($_POST['contestBottles'], "text"),
						   GetSQLValueString($_POST['contestShippingAddress'], "text"),
						   GetSQLValueString(capitalize($_POST['contestShippingName']), "text"),
						   GetSQLValueString($_POST['contestAwards'], "text"),
						   GetSQLValueString($_POST['contestWinnersComplete'], "text"),
						   GetSQLValueString($_POST['contestEntryCap'], "text"),
						   GetSQLValueString(capitalize($_POST['contestAwardsLocName']), "text"),
						   GetSQLValueString($_POST['contestAwardsLocDate'], "text"),
						   GetSQLValueString($_POST['contestAwardsLocTime'], "text"),
						   GetSQLValueString($_POST['contestEntryFee2'], "text"),
						   GetSQLValueString($contestEntryFeeDiscount, "text"),
						   GetSQLValueString($_POST['contestEntryFeeDiscountNum'], "text"),
						   GetSQLValueString($_POST['contestLogo'], "text"),
						   GetSQLValueString($_POST['contestBOSAward'], "text"),
						   GetSQLValueString($_POST['contestEntryFeePassword'], "text"),
						   GetSQLValueString($_POST['contestEntryFeePasswordNum'], "text"),
						   GetSQLValueString($_POST['contestCircuit'], "text"),
						   GetSQLValueString($id, "int"));
	
	  mysql_select_db($database, $brewing);
	  $Result1 = mysql_query($insertSQL, $brewing) or die(mysql_error());
	  
	  $insertSQL = sprintf("INSERT INTO contacts (
		contactFirstName, 
		contactLastName, 
		contactPosition, 
		contactEmail
		) 
		VALUES 
		(%s, %s, %s, %s)",
						   GetSQLValueString($_POST['contactFirstName'], "text"),
						   GetSQLValueString($_POST['contactLastName'], "text"),
						   GetSQLValueString($_POST['contactPosition'], "text"),
						   GetSQLValueString($_POST['contactEmail'], "text"));
						   
		mysql_select_db($database, $brewing);
		$Result1 = mysql_query($insertSQL, $brewing) or die(mysql_error());
		$insertGoTo = "../setup.php?section=step5";
		header(sprintf("Location: %s", $insertGoTo));
}

// --------------------------------------- Editing  ----------------------------------------
if ($action == "edit") {
	if (($_POST['contestEntryFee2'] == "") || ($_POST['contestEntryFeeDiscountNum'] == "")) $contestEntryFeeDiscount = "N"; 
	if (($_POST['contestEntryFee2'] != "") && ($_POST['contestEntryFeeDiscountNum'] != "")) $contestEntryFeeDiscount = "Y"; 
	
	$updateSQL = sprintf("UPDATE contest_info SET 
	contestName=%s,
	contestID=%s,
	contestHost=%s, 
	contestHostWebsite=%s, 
	contestHostLocation=%s,
	contestRegistrationOpen=%s, 
	contestRegistrationDeadline=%s, 
	contestEntryOpen=%s,
	contestEntryDeadline=%s, 
	contestRules=%s, 
	contestAwardsLocation=%s, 
	contestContactName=%s, 
	contestContactEmail=%s, 
	
	contestEntryFee=%s, 
	contestBottles=%s, 
	contestShippingAddress=%s, 
	contestShippingName=%s, 
	
	contestAwards=%s,
	contestWinnersComplete=%s,
	contestEntryCap=%s,
	contestAwardsLocName=%s,
	contestAwardsLocDate=%s,
	
	contestAwardsLocTime=%s,
	contestEntryFee2=%s,
	contestEntryFeeDiscount=%s,
	contestEntryFeeDiscountNum=%s,
	contestLogo=%s,
	contestBOSAward=%s,
	contestEntryFeePassword=%s,
	contestEntryFeePasswordNum=%s,
	contestCircuit=%s
	WHERE id=%s",
						   GetSQLValueString(capitalize($_POST['contestName']), "text"),
						   GetSQLValueString($_POST['contestID'], "text"),
						   GetSQLValueString(capitalize($_POST['contestHost']), "text"),
						   GetSQLValueString($_POST['contestHostWebsite'], "text"),
						   GetSQLValueString(capitalize($_POST['contestHostLocation']), "text"),
						   GetSQLValueString($_POST['contestRegistrationOpen'], "date"),
						   GetSQLValueString($_POST['contestRegistrationDeadline'], "date"),
						   GetSQLValueString($_POST['contestEntryOpen'], "date"),
						   GetSQLValueString($_POST['contestEntryDeadline'], "date"),
						   GetSQLValueString($_POST['contestRules'], "text"),
						   GetSQLValueString(capitalize($_POST['contestAwardsLocation']), "text"),
						   GetSQLValueString($_POST['contestContactName'], "text"),
						   GetSQLValueString($_POST['contestContactEmail'], "text"),
						   GetSQLValueString($_POST['contestEntryFee'], "text"),
						   GetSQLValueString($_POST['contestBottles'], "text"),
						   GetSQLValueString($_POST['contestShippingAddress'], "text"),
						   GetSQLValueString(capitalize($_POST['contestShippingName']), "text"),
						   GetSQLValueString($_POST['contestAwards'], "text"),
						   GetSQLValueString($_POST['contestWinnersComplete'], "text"),
						   GetSQLValueString($_POST['contestEntryCap'], "text"),
						   GetSQLValueString(capitalize($_POST['contestAwardsLocName']), "text"),
						   GetSQLValueString($_POST['contestAwardsLocDate'], "text"),
						   GetSQLValueString($_POST['contestAwardsLocTime'], "text"),
						   GetSQLValueString($_POST['contestEntryFee2'], "text"),
						   GetSQLValueString($contestEntryFeeDiscount, "text"),
						   GetSQLValueString($_POST['contestEntryFeeDiscountNum'], "text"),
						   GetSQLValueString($_POST['contestLogo'], "text"),
						   GetSQLValueString($_POST['contestBOSAward'], "text"),
						   GetSQLValueString($_POST['contestEntryFeePassword'], "text"),
						   GetSQLValueString($_POST['contestEntryFeePasswordNum'], "text"),
						   GetSQLValueString($_POST['contestCircuit'], "text"),
						   GetSQLValueString($id, "int"));
	
	mysql_select_db($database, $brewing);
	$Result1 = mysql_query($updateSQL, $brewing) or die(mysql_error());
	header(sprintf("Location: %s", $updateGoTo));

}
?>