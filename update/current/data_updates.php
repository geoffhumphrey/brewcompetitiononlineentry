<?php
// -----------------------------------------------------------
// Version 2.0.0.0
// Data Updates
// -----------------------------------------------------------

// Enable display of all sponsors. Admins can change if desired.
$updateSQL = sprintf("UPDATE %s SET sponsorEnable = '1';",$sponsors_db_table);
mysql_select_db($database, $brewing);
mysql_real_escape_string($updateSQL);
$result = mysql_query($updateSQL, $brewing) or die(mysql_error());	

// Copy entry open/close values to newly created shipping open/close dates
$updateSQL = sprintf("UPDATE %s SET contestShippingOpen = contestEntryOpen",$contest_info_db_table);
mysql_select_db($database, $brewing);
mysql_real_escape_string($updateSQL);
$result = mysql_query($updateSQL, $brewing) or die(mysql_error());

$updateSQL = sprintf("UPDATE %s SET contestShippingDeadline = contestEntryDeadline",$contest_info_db_table);
mysql_select_db($database, $brewing);
mysql_real_escape_string($updateSQL);
$result = mysql_query($updateSQL, $brewing) or die(mysql_error());		

// Change incorrect name and info for BJCP2015 style 28C
$query_wild = sprintf("SELECT brewStyle FROM %s WHERE brewStyle='Soured Fruit Beer'",$styles_db_table);
$wild = mysql_query($query_wild, $brewing) or die(mysql_error());
$row_wild = mysql_fetch_assoc($wild);
$totalRows_wild = mysql_num_rows($wild);

if ($totalRows_wild > 0) {

	$updateSQL = sprintf("UPDATE %s SET brewStyle='Wild Specialty Beer', brewStyleInfo='A sour and/or funky version of a fruit, herb, or spice beer, or a wild beer aged in wood. If wood-aged, the wood should not be the primary or dominant character. Entry Instructions: Entrant must specify the type of fruit, spice, herb, or wood used. Entrant must specify a description of the beer, identifying the yeast/bacteria used and either a base style or the ingredients/specs/target character of the beer. A general description of the special nature of the beer can cover all the required items. Commercial Examples: Cascade Bourbonic Plague, Jester King Atrial Rubicite, New Belgium Eric’s Ale, New Glarus Belgian Red, Russian River Supplication, The Lost Abbey 
Cuvee de Tomme.' WHERE id=187",$styles_db_table);
	mysql_select_db($database, $brewing);
	mysql_real_escape_string($updateSQL);
	$result1 = mysql_query($updateSQL, $brewing) or die(mysql_error());
	
	$updateSQL = sprintf("UPDATE %s SET brewStyle='Wild Specialty Beer' WHERE brewStyle='Soured Fruit Beer'",$brewing_db_table);
	mysql_select_db($database, $brewing);
	mysql_real_escape_string($updateSQL);
	$result1 = mysql_query($updateSQL, $brewing) or die(mysql_error());
	
}

// BJCP2015 style 21B needs to require special ingredients
$query_saison = sprintf("SELECT brewStyleReqSpec FROM %s WHERE id = 178",$styles_db_table);
$saison = mysql_query($query_saison, $brewing) or die(mysql_error());
$row_saison = mysql_fetch_assoc($saison);

if ($row_saison['brewStyleReqSpec'] == 0) {
	$updateSQL = sprintf("UPDATE %s SET brewStyleReqSpec = '1' WHERE id = 178;",$styles_db_table);
	mysql_select_db($database, $brewing);
	mysql_real_escape_string($updateSQL);
	$result1 = mysql_query($updateSQL, $brewing) or die(mysql_error());	
}




?>