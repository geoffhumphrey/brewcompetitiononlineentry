<?php
// Updates for only NHC application
$output = "<ul>";
require(CLASSES.'phpass/PasswordHash.php');
$hasher = new PasswordHash(8, false);

$updateSQL = "ALTER TABLE  $preferences_db_table ADD  `prefsSpecialCharLimit` INT( 5 ) NULL DEFAULT NULL;";
mysql_select_db($database, $brewing);
//$result = mysql_query($updateSQL, $brewing);
echo $updateSQL."<br>";

$updateSQL = "ALTER TABLE  $mods_db_table ADD  `mod_enable` TINYINT( 1 ) NULL DEFAULT NULL;";
mysql_select_db($database, $brewing);
//$result = mysql_query($updateSQL, $brewing);
echo $updateSQL."<br>";
mysql_select_db($database, $brewing);
$query_admin_users = "SELECT * FROM $users_db_table";
$admin_users = mysql_query($query_admin_users, $brewing);
$row_admin_users = mysql_fetch_assoc($admin_users);
$totalRows_admin_users = mysql_num_rows($admin_users);

if ($totalRows_admin_users > 0) {
	
	do { 
	
		// Update all current passwords to be much more secure
		// This involves taking the existing string that already has been hashed by md5 and hashing it with phpass
		// Theres no way to "decode" a string that has been hashed by md5, so we'll "hash a hash" here 
		
		// Get password from DB
		$password = $row_admin_users['password'];
		
		// Create hash with phpass
		$hash = $hasher->HashPassword($password);
		
		$updateSQL = sprintf("UPDATE $users_db_table SET password='%s' WHERE id='%s';", $hash, $row_admin_users['id']);
		mysql_select_db($database, $brewing);
		mysql_real_escape_string($updateSQL);
		//$result = mysql_query($updateSQL, $brewing); 
echo $updateSQL."<br>";
		
		// Update all current admins to Uber Admins
		if ((!NHC) && ($row_admin_users['userLevel'] == 1)) {
			$updateSQL = sprintf("UPDATE $users_db_table SET 
								 userLevel='%s'
								 WHERE id='%s';", 
								 "0",
								 $row_admin_users['id']);
			mysql_select_db($database, $brewing);
			mysql_real_escape_string($updateSQL);
			//$result = mysql_query($updateSQL, $brewing); 
			echo $updateSQL."<br>";
		}
	} 
	
	
	while ($row_admin_users = mysql_fetch_assoc($admin_users));
	
	$output .=  "<li>Updates to user's table completed.</li>";
}

$updateSQL = sprintf("UPDATE %s SET version='%s', version_date='%s' WHERE id='%s';",$prefix."system","1.3.1.0","2015-05-31","1");
mysql_select_db($database, $brewing);
mysql_real_escape_string($updateSQL);
//$result = mysql_query($updateSQL, $brewing); 
echo $updateSQL."<br>";
$output .= "<li>Updates to system table completed.</li>";

// -----------------------------------------------------------

// Data Updates: Brewer Table

// -----------------------------------------------------------


$query_brewer = "SELECT * FROM $brewer_db_table";
$brewer = mysql_query($query_brewer, $brewing);
$row_brewer = mysql_fetch_assoc($brewer);

$query_log = "SELECT * FROM $brewing_db_table";
$log = mysql_query($query_log, $brewing) or die(mysql_error());
$row_log = mysql_fetch_assoc($log);
$totalRows_log = mysql_num_rows($log); 

do { 
	if ($row_brewer['brewerJudge'] == "Y") $brewerJudge = "1"; else $brewerJudge = "0";
	if ($row_brewer['brewerSteward'] == "Y") $brewerSteward = "1"; else $brewerSteward = "0";
	if ($row_brewer['brewerJudgeBOS'] == "Y") $brewerJudgeBOS = "1"; else $brewerJudgeBOS = "0";
	if ($row_brewer['brewerDiscount'] == "Y") $brewerDiscount = "1"; else $brewerDiscount = "0";
	
	$updateSQL = sprintf("UPDATE $brewer_db_table SET 
						 brewerJudge='%s',
						 brewerSteward='%s',
						 brewerJudgeBOS='%s', 
						 brewerDiscount='%s',
						 WHERE id='%s';", 
						 $brewerJudge,
						 $brewerSteward,
						 $brewerJudgeBOS, 
						 $brewerDiscount, 
						 $row_brewer['id']);
	
	mysql_select_db($database, $brewing);
	//$result = mysql_query($updateSQL, $brewing); 
echo $updateSQL."<br>";

}  
while ($row_brewer = mysql_fetch_assoc($brewer));

// Change rows to tinyint type

$updateSQL = "ALTER TABLE  $brewer_db_table CHANGE  `brewerJudgeBOS`  `brewerJudgeBOS` TINYINT(1) NULL DEFAULT NULL COMMENT '1 for true; 0 for false';";
mysql_select_db($database, $brewing);
//$result = mysql_query($updateSQL, $brewing);
echo $updateSQL."<br>";

$updateSQL = "ALTER TABLE  $brewer_db_table CHANGE  `brewerDiscount`  `brewerDiscount` TINYINT(1) NULL DEFAULT NULL COMMENT '1 for true; 0 for false';";
mysql_select_db($database, $brewing);
//$result = mysql_query($updateSQL, $brewing);
echo $updateSQL."<br>";

$updateSQL = "ALTER TABLE  $brewer_db_table CHANGE `brewerJudge` `brewerJudge` TINYINT(1) NULL DEFAULT NULL COMMENT '1 for true; 0 for false';";
mysql_select_db($database, $brewing);
//$result = mysql_query($updateSQL, $brewing);
echo $updateSQL."<br>";

$updateSQL = "ALTER TABLE  $brewer_db_table CHANGE  `brewerSteward`  `brewerSteward` TINYINT(1) NULL DEFAULT NULL COMMENT '1 for true; 0 for false';";
mysql_select_db($database, $brewing);
//$result = mysql_query($updateSQL, $brewing);
echo $updateSQL."<br>";

$output .= "<li>Updates to brewer table completed.</li>";


// -----------------------------------------------------------
// Data Updates: Brewing Table
//   Convert brewPaid, brewWinner, and brewReceived to
//   boolean values.
// -----------------------------------------------------------



if ($totalRows_log > 0) {

	do {
		if ($row_log['brewPaid'] == "Y") $brewPaid = "1"; else $brewPaid = "0";
		if ($row_log['brewWinner'] == "Y") $brewWinner = "1"; else $brewWinner = "0";
		if ($row_log['brewReceived'] == "Y") $brewReceived = "1"; else $brewReceived = "0";
		
		
		$updateSQL = sprintf("UPDATE ".$prefix."brewing SET 
								 brewPaid='%s',
								 brewWinner='%s',
								 brewReceived='%s',
								 brewConfirmed='%s',
								 brewUpdated=%s
								 WHERE id='%s';",
								 $brewPaid,
								 $brewWinner,
								 $brewReceived,
								 "1",
								 "NOW()",
								 $row_log['id']);
			mysql_select_db($database, $brewing);
			//$result = mysql_query($updateSQL, $brewing); 
			echo $updateSQL."<br>";
			//$output .= $updateSQL."<br>";
	} while ($row_log = mysql_fetch_assoc($log));
	$output .= "<li>All entry data updated.</li>";
}

$updateSQL = "ALTER TABLE  `".$prefix."brewing` CHANGE  `brewPaid`  `brewPaid` TINYINT( 1 ) NULL DEFAULT NULL COMMENT '1=true; 0=false';";
mysql_select_db($database, $brewing);
//$result = mysql_query($updateSQL, $brewing); 
echo $updateSQL."<br>";
//$output .= $updateSQL."<br>";

$updateSQL = "ALTER TABLE  `".$prefix."brewing` CHANGE  `brewReceived`  `brewReceived` TINYINT( 1 ) NULL DEFAULT NULL COMMENT '1=true; 0=false';";
mysql_select_db($database, $brewing);
//$result = mysql_query($updateSQL, $brewing); 
echo $updateSQL."<br>"; 

//$output .= $updateSQL."<br>";

$output .= "<li>Conversion of paid and received rows to new schema in brewing table completed.</li>";



// -----------------------------------------------------------
// Alter Tables: Styles
// -----------------------------------------------------------

$updateSQL = "ALTER TABLE  `".$prefix."styles` ADD `brewStyleReqSpec` TINYINT(1) NULL DEFAULT NULL COMMENT 'Does the style require special ingredients be input? 1=yes 0=no';";
mysql_select_db($database, $brewing);
mysql_real_escape_string($updateSQL);
//$result = mysql_query($updateSQL, $brewing);
echo $updateSQL."<br>";

$updateSQL = "UPDATE `".$prefix."styles` SET `brewStyleReqSpec` = '0';";
mysql_select_db($database, $brewing); 
mysql_real_escape_string($updateSQL);
//$result = mysql_query($updateSQL, $brewing);
echo $updateSQL."<br>";

// Designate all BJCP styles that require special ingredients
$updateSQL = "UPDATE `".$prefix."styles` SET `brewStyleReqSpec` = '1' WHERE `id` = 21;";
mysql_select_db($database, $brewing); 
mysql_real_escape_string($updateSQL);
//$result = mysql_query($updateSQL, $brewing);
echo $updateSQL."<br>";

$updateSQL = "UPDATE `".$prefix."styles` SET `brewStyleReqSpec` = '1' WHERE `id` = 59;";
mysql_select_db($database, $brewing);

mysql_real_escape_string($updateSQL);
//$result = mysql_query($updateSQL, $brewing);
echo $updateSQL."<br>";
$updateSQL = "UPDATE `".$prefix."styles` SET `brewStyleReqSpec` = '1' WHERE `id` = 65;";
mysql_select_db($database, $brewing); 
mysql_real_escape_string($updateSQL);
//$result = mysql_query($updateSQL, $brewing);
echo $updateSQL."<br>";

$updateSQL = "UPDATE `".$prefix."styles` SET `brewStyleReqSpec` = '1' WHERE `id` = 74;";
mysql_select_db($database, $brewing);
mysql_real_escape_string($updateSQL);
//$result = mysql_query($updateSQL, $brewing);
echo $updateSQL."<br>";

$updateSQL = "UPDATE `".$prefix."styles` SET `brewStyleReqSpec` = '1' WHERE `id` = 75;";
mysql_select_db($database, $brewing); 
mysql_real_escape_string($updateSQL);
//$result = mysql_query($updateSQL, $brewing);
echo $updateSQL."<br>";

$updateSQL = "UPDATE `".$prefix."styles` SET `brewStyleReqSpec` = '1' WHERE `id` = 76;";
mysql_select_db($database, $brewing); 
mysql_real_escape_string($updateSQL);
//$result = mysql_query($updateSQL, $brewing);
echo $updateSQL."<br>";

$updateSQL = "UPDATE `".$prefix."styles` SET `brewStyleReqSpec` = '1' WHERE `id` = 78;";
mysql_select_db($database, $brewing); 
mysql_real_escape_string($updateSQL);
//$result = mysql_query($updateSQL, $brewing);
echo $updateSQL."<br>";

$updateSQL = "UPDATE `".$prefix."styles` SET `brewStyleReqSpec` = '1' WHERE `id` = 79;";
mysql_select_db($database, $brewing); 
mysql_real_escape_string($updateSQL);
//$result = mysql_query($updateSQL, $brewing);
echo $updateSQL."<br>";

$updateSQL = "UPDATE `".$prefix."styles` SET `brewStyleReqSpec` = '1' WHERE `id` = 80;";
mysql_select_db($database, $brewing);
mysql_real_escape_string($updateSQL);
//$result = mysql_query($updateSQL, $brewing);
echo $updateSQL."<br>";

$updateSQL = "UPDATE `".$prefix."styles` SET `brewStyleReqSpec` = '1' WHERE `id` = 86;";
mysql_select_db($database, $brewing); 
mysql_real_escape_string($updateSQL);
//$result = mysql_query($updateSQL, $brewing);
echo $updateSQL."<br>";

$updateSQL = "UPDATE `".$prefix."styles` SET `brewStyleReqSpec` = '1' WHERE `id` = 87;";
mysql_select_db($database, $brewing); 
mysql_real_escape_string($updateSQL);
//$result = mysql_query($updateSQL, $brewing);
echo $updateSQL."<br>";

$updateSQL = "UPDATE `".$prefix."styles` SET `brewStyleReqSpec` = '1' WHERE `id` = 89;";
mysql_select_db($database, $brewing);
mysql_real_escape_string($updateSQL);
//$result = mysql_query($updateSQL, $brewing);
echo $updateSQL."<br>";

$updateSQL = "UPDATE `".$prefix."styles` SET `brewStyleReqSpec` = '1' WHERE `id` = 94;";

mysql_select_db($database, $brewing); 
mysql_real_escape_string($updateSQL);
//$result = mysql_query($updateSQL, $brewing);
echo $updateSQL."<br>";

$updateSQL = "UPDATE `".$prefix."styles` SET `brewStyleReqSpec` = '1' WHERE `id` = 95;";
mysql_select_db($database, $brewing); 
mysql_real_escape_string($updateSQL);
//$result = mysql_query($updateSQL, $brewing);
echo $updateSQL."<br>";

$updateSQL = "UPDATE `".$prefix."styles` SET `brewStyleReqSpec` = '1' WHERE `id` = 96;";
mysql_select_db($database, $brewing);
mysql_real_escape_string($updateSQL);
//$result = mysql_query($updateSQL, $brewing);
echo $updateSQL."<br>";

$updateSQL = "UPDATE `".$prefix."styles` SET `brewStyleReqSpec` = '1' WHERE `id` = 97;";
mysql_select_db($database, $brewing); 
mysql_real_escape_string($updateSQL);
//$result = mysql_query($updateSQL, $brewing);
echo $updateSQL."<br>";

$updateSQL = "UPDATE `".$prefix."styles` SET `brewStyleReqSpec` = '1' WHERE `id` = 98;";
mysql_select_db($database, $brewing); 
mysql_real_escape_string($updateSQL);
//$result = mysql_query($updateSQL, $brewing);
echo $updateSQL."<br>";

$output .=  "<li>Styles table updated.</li>";


$output .= "</ul>";

//echo $output;

?>