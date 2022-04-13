<?php
flush();

if (!isset($output)) $output = "";

/**
 * ---------------------------------------------- 2.3.2 ----------------------------------------------
 * SYSTEM is a reserved word in MySQL 8.
 * Check to see if 'system' DB table is present.
 * If so, change its name to 'bcoem_system'.
 * This is at the top due to cascading changes below.
 * ---------------------------------------------------------------------------------------------------
 */


if (check_setup($prefix."system",$database)) {

	$sql = sprintf("RENAME TABLE %s TO %s",$prefix."system",$prefix."bcoem_sys");
	mysqli_select_db($connection,$database);
	mysqli_real_escape_string($connection,$sql);
	$result = mysqli_query($connection,$sql) or die (mysqli_error($connection));
	if ($result) $output .= "<li>System table named changed successfully.</li>";
	else $output .= "<li>System table named change failed. Error: ".$db_conn->getLastError()."</li>";

}

/**
 * Get actual preferences from DB. Don't rely on
 * session vars since unexpected results may occur.
 */

$query_current_prefs = sprintf("SELECT * FROM %s WHERE id='1'",$prefix."preferences");
$current_prefs = mysqli_query($connection,$query_current_prefs) or die (mysqli_error($connection));
$row_current_prefs = mysqli_fetch_assoc($current_prefs);

/**
 * ---------------------------------------------- 2.1.5 ----------------------------------------------
 * Make sure all items are present from last "official" update
 * ---------------------------------------------------------------------------------------------------
 */

if (!check_update("prefsLanguage", $prefix."preferences")) {

	$sql = sprintf("ALTER TABLE `%s` ADD `prefsLanguage` VARCHAR(25) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL;",$prefix."preferences");
	mysqli_select_db($connection,$database);
	mysqli_real_escape_string($connection,$sql);
	$result = mysqli_query($connection,$sql) or die (mysqli_error($connection));
	if ($result) $output .= "<li>Language preferences added successfully.</li>";
	else $output .= "<li>Addition of language preferences failed. Error: ".$db_conn->getLastError()."</li>";

}

if (!check_update("prefsSpecific", $prefix."preferences")) {

	$sql = sprintf("ALTER TABLE `%s` ADD `prefsSpecific` TINYINT(1) NULL;",$prefix."preferences");
	mysqli_select_db($connection,$database);
	mysqli_real_escape_string($connection,$sql);
	$result = mysqli_query($connection,$sql) or die (mysqli_error($connection));
	if ($result) $output .= "<li>Hide/show Brewer's Specific field added successfully.</li>";
	else $output .= "<li>Addition of the hide/show Brewer's Specific field failed. Error: ".$db_conn->getLastError()."</li>";

}

if (!check_update("prefsEntryLimitPaid", $prefix."preferences")) {
	
	$sql = sprintf("ALTER TABLE `%s` ADD `prefsEntryLimitPaid` INT(4) NULL DEFAULT NULL;",$prefix."preferences");
	mysqli_select_db($connection,$database);
	mysqli_real_escape_string($connection,$sql);
	$result = mysqli_query($connection,$sql) or die (mysqli_error($connection));
	if ($result) $output .= "<li>Paid entry limit field added successfully.</li>";
	else $output .= "<li>Addition of the paid entry limit field failed. Error: ".$db_conn->getLastError()."</li>";

}

if (!check_update("prefsEmailRegConfirm", $prefix."preferences")) {

	$sql = sprintf("ALTER TABLE `%s` ADD `prefsEmailRegConfirm` TINYINT(1) NULL DEFAULT NULL;",$prefix."preferences");
	mysqli_select_db($connection,$database);
	mysqli_real_escape_string($connection,$sql);
	$result = mysqli_query($connection,$sql) or die (mysqli_error($connection));
	if ($result) $output .= "<li>Registration email confirmation field added successfully.</li>";
	else $output .= "<li>Addition of the registration email confirmation field failed. Error: ".$db_conn->getLastError()."</li>";
}

if (!check_update("jPrefsCapJudges", $prefix."judging_preferences")) {

	$sql = sprintf("ALTER TABLE `%s` ADD `jPrefsCapJudges` INT(3) NULL DEFAULT NULL;", $prefix."judging_preferences");
	mysqli_select_db($connection,$database);
	mysqli_real_escape_string($connection,$sql);
	$result = mysqli_query($connection,$sql) or die (mysqli_error($connection));
	if ($result) $output .= "<li>Cap judges field added successfully.</li>";
	else $output .= "<li>Addition of the cap judges field failed. Error: ".$db_conn->getLastError()."</li>";

}

if (!check_update("jPrefsCapStewards", $prefix."judging_preferences")) {

	$sql = sprintf("ALTER TABLE `%s` ADD `jPrefsCapStewards` INT(3) NULL DEFAULT NULL;", $prefix."judging_preferences");
	mysqli_select_db($connection,$database);
	mysqli_real_escape_string($connection,$sql);
	$result = mysqli_query($connection,$sql) or die (mysqli_error($connection));
	if ($result) $output .= "<li>Cap stewards field added successfully.</li>";
	else $output .= "<li>Addition of the cap stewards field failed. Error: ".$db_conn->getLastError()."</li>";

}

if (!check_update("jPrefsBottleNum", $prefix."judging_preferences")) {

	$sql = sprintf("ALTER TABLE `%s` ADD `jPrefsBottleNum` INT(3) NULL DEFAULT NULL;",$prefix."judging_preferences");
	mysqli_select_db($connection,$database);
	mysqli_real_escape_string($connection,$sql);
	$result = mysqli_query($connection,$sql) or die (mysqli_error($connection));
	if ($result) $output .= "<li>Number of bottles required field added successfully.</li>";
	else $output .= "<li>Addition of the number of bottles required field failed. Error: ".$db_conn->getLastError()."</li>";

}

if (!check_update("contestCheckInPassword", $prefix."contest_info")) {

	$sql = sprintf("ALTER TABLE `%s` ADD `contestCheckInPassword` VARCHAR(255) NULL CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL;",$prefix."contest_info");
	mysqli_select_db($connection,$database);
	mysqli_real_escape_string($connection,$sql);
	$result = mysqli_query($connection,$sql) or die (mysqli_error($connection));
	if ($result) $output .= "<li>Mobile device check-in password field added successfully.</li>";
	else $output .= "<li>Addition of the mobile device check-in password field failed. Error: ".$db_conn->getLastError()."</li>";

}

if (!check_update("brewStyleEntry", $prefix."styles")) {

	$sql = sprintf("ALTER TABLE `%s` ADD `brewStyleEntry` TEXT CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL;",$prefix."styles");
	mysqli_select_db($connection,$database);
	mysqli_real_escape_string($connection,$sql);
	$result = mysqli_query($connection,$sql) or die (mysqli_error($connection));
	if ($result) $output .= "<li>Style entry information field added successfully.</li>";
	else $output .= "<li>Addition of the style entry information field failed. Error: ".$db_conn->getLastError()."</li>";

}

if (!check_update("brewStyleComEx", $prefix."styles")) {

	$sql = sprintf("ALTER TABLE  `%s` ADD `brewStyleComEx` TEXT CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL;",$prefix."styles");
	mysqli_select_db($connection,$database);
	mysqli_real_escape_string($connection,$sql);
	$result = mysqli_query($connection,$sql) or die (mysqli_error($connection));
	if ($result) $output .= "<li>Style commercial examples field added successfully.</li>";
	else $output .= "<li>Addition of the style commercial examples field failed. Error: ".$db_conn->getLastError()."</li>";

}

/**
 * ----------------------------------------------- 2.1.8 -----------------------------------------------
 * Check for setup_last_step and add
 * Also add "example" sub-styles for BJCP2015 21A (Specialty IPA) and 27A (Historical Beer)
 * -----------------------------------------------------------------------------------------------------
 */

$output .= "<li>System table updates.</li>";

if (!check_update("setup_last_step", $prefix."bcoem_sys")) {
	// Add setup_last_step column to system table

	$sql = sprintf("ALTER TABLE `%s` ADD `setup_last_step` INT(3) NULL DEFAULT NULL;",$prefix."bcoem_sys");
	mysqli_select_db($connection,$database);
	mysqli_real_escape_string($connection,$sql);
	$result = mysqli_query($connection,$sql) or die (mysqli_error($connection));
	if ($result) $output .= "<li>Setup last step field added successfully.</li>";
	else $output .= "<li>Addition of the setup last step field failed. Error: ".$db_conn->getLastError()."</li>";

	$update_table = $prefix."bcoem_sys";
	$data = array('setup_last_step' => 8);
	$db_conn->where ('id', 1);
	if ($db_conn->update ($update_table, $data)) $output .= "<li>Setup last step value updated.</li>";
	else $output .= "<li>Setup last step value not updated. Error: ".$db_conn->getLastError()."</li>";

}

// Make sure styles table is auto increment

$sql = sprintf("ALTER TABLE `%s` CHANGE `id` `id` INT(11) NOT NULL AUTO_INCREMENT;",$prefix."styles");
mysqli_select_db($connection,$database);
mysqli_real_escape_string($connection,$sql);
$result = mysqli_query($connection,$sql) or die (mysqli_error($connection));
if ($result) $output .= "<li>Styles table set to auto increment.</li>";
else $output .= "<li>Styles table was not set to auto increment. Error: ".$db_conn->getLastError()."</li>";

$output .= "<li>Updating styles table with current sub-style information.</li>";

$update_table = $prefix."styles";
$data = array(
	'brewStyle' => 'Historical Beer',
	'brewStyleTags' => 'standard-strength, pale-color, top-fermented, central-europe, historical-style, wheat-beer-family, sour, spice, amber-color, north-america, historical-style, balanced, smoke, dark-color, british-isles, brown-ale-family, malty, sweet, bottom-fermented',
	'brewStyleEntry' => 'Catch-all category for other historical beers that have NOT been defined by the BJCP. The entrant must provide a description for the judges of the historical style that is NOT one of the currently defined historical style examples provided by the BJCP. Currently defined examples are: Gose, Piwo Grodziskie, Lichtenhainer, Roggenbier, Sahti, Kentucky Common, Pre-Prohibition Lager, Pre-Prohibition Porter, London Brown Ale. If a beer is entered with just a style name and no description, it is very unlikely that judges will understand how to judge it.'
);
$db_conn->where ('id', 184);
$db_conn->update ($update_table,$data);

$data = array(
	'brewStyleEntry' => 'Entrant MUST specify a strength (session: 3.0-5.0%%, standard: 5.0-7.5%%, double: 7.5-9.5%%); if no strength is specified, standard will be assumed. This subcategory is a catch-all for entries that DO NOT fit into one of the defined BJCP Specialty IPA types: Black IPA, Brown IPA, White IPA, Rye IPA, Belgian IPA, or Red IPA. Entrant must describe the type of Specialty IPA and its key characteristics in comment form so judges will know what to expect. Entrants may specify specific hop varieties used, if entrants feel that judges may not recognize the varietal characteristics of newer hops. Entrants may specify a combination of defined IPA types (e.g., Black Rye IPA) without providing additional descriptions. Entrants may use this category for a different strength version of an IPA defined by its own BJCP subcategory (e.g., session-strength American or English IPA) - except where an existing BJCP subcategory already exists for that style (e.g., double [American] IPA). If the entry falls into one of the currently defined types (Black IPA, Brown IPA, White IPA, Rye IPA, Belgian IPA, Red IPA), it should be entered into that salient subcategory type.'
);
$db_conn->where ('id', 163);
$db_conn->update ($update_table,$data);

// Add new specialty IPA and historical styles to styles table if not present
if (!check_new_style("27","A1","Gose")) {
	
	$data = array('brewStyleGroup' => '27',	'brewStyleNum' => 'A1',	'brewStyle' => 'Gose', 'brewStyleCategory' => 'Historical Beer', 'brewStyleVersion' => 'BJCP2015', 'brewStyleOG' => '1.036', 'brewStyleOGMax' => '1.056', 'brewStyleFG' => '1.006', 'brewStyleFGMax' => '1.010', 'brewStyleABV' => '4.2', 'brewStyleABVMax' => '4.8', 'brewStyleIBU' => '5', 'brewStyleIBUMax' => '12', 'brewStyleSRM' => '3', 'brewStyleSRMMax' => '4', 'brewStyleType' => '1', 'brewStyleInfo' => 'A highly-carbonated, tart and fruity wheat ale with a restrained coriander and salt character and low bitterness. Very refreshing, with bright flavors and high attenuation.', 'brewStyleLink' => 'http://bjcp.org/stylecenter.php', 'brewStyleActive' => 'Y', 'brewStyleOwn' => 'bcoe', 'brewStyleReqSpec' => '0', 'brewStyleStrength' => '0', 'brewStyleCarb' => '0', 'brewStyleSweet' => '0', 'brewStyleTags' => 'standard-strength, pale-color, top-fermented, centraleurope, historical-style, wheat-beer-family, sour, spice','brewStyleComEx' => 'Anderson Valley Gose, Bayerisch Bahnhof Leipziger Gose, Dollnitzer Ritterguts Gose', 'brewStyleEntry' => NULL
	);
	$result = $db->insert ($update_table, $data);
	if ($result) $output .= "<li>Gose style added to BJCP 2015 styles.</li>";
	else $output .= "<li>Gose style NOT added to BJCP 2015 styles. Error: ".$db_conn->getLastError()."</li>";

}

if (!check_new_style("27","A2","Piwo Grodziskie")) {

	$data =  array('brewStyleGroup' => '27','brewStyleNum' => 'A2','brewStyle' => 'Piwo Grodziskie','brewStyleCategory' => 'Historical Beer','brewStyleVersion' => 'BJCP2015','brewStyleOG' => '1.028','brewStyleOGMax' => '1.032','brewStyleFG' => '1.010','brewStyleFGMax' => '1.015','brewStyleABV' => '4.5','brewStyleABVMax' => '6.0','brewStyleIBU' => '25','brewStyleIBUMax' => '40','brewStyleSRM' => '3','brewStyleSRMMax' => '6','brewStyleType' => '1','brewStyleInfo' => 'A low-gravity, highly-carbonated, light bodied ale combining an oak-smoked flavor with a clean hop bitterness. Highly sessionable.','brewStyleLink' => 'http://bjcp.org/stylecenter.php','brewStyleActive' => 'Y','brewStyleOwn' => 'bcoe','brewStyleReqSpec' => '0','brewStyleStrength' => '0','brewStyleCarb' => '0','brewStyleSweet' => '0','brewStyleTags' => 'standard-strength, pale-color, bottom-fermented,lagered, north-america, historical-style, pilsner-family, bitter, hoppy','brewStyleComEx' => NULL,'brewStyleEntry' => NULL);
	$result = $db->insert ($update_table, $data);
	if ($result) $output .= "<li>Piwo Grodziskie style added to BJCP 2015 styles.</li>";
	else $output .= "<li>Piwo Grodziskie style NOT added to BJCP 2015 styles. Error: ".$db_conn->getLastError()."</li>";

}

if (!check_new_style("27","A3","Lichtenhainer")) {

	$data = array('id' => '229','brewStyleGroup' => '27','brewStyleNum' => 'A3','brewStyle' => 'Lichtenhainer','brewStyleCategory' => 'Historical Beer','brewStyleVersion' => 'BJCP2015','brewStyleOG' => '1.032','brewStyleOGMax' => '1.040','brewStyleFG' => '1.004','brewStyleFGMax' => '1.008','brewStyleABV' => '3.5','brewStyleABVMax' => '4.7','brewStyleIBU' => '5','brewStyleIBUMax' => '12','brewStyleSRM' => '3','brewStyleSRMMax' => '6','brewStyleType' => '1','brewStyleInfo' => 'A sour, smoked, lower-gravity historical German wheat beer. Complex yet refreshing character due to high attenuation and carbonation, along with low bitterness and moderate sourness. ','brewStyleLink' => 'http://bjcp.org/stylecenter.php','brewStyleActive' => 'Y','brewStyleOwn' => 'bcoe','brewStyleReqSpec' => '0','brewStyleStrength' => '0','brewStyleCarb' => '0','brewStyleSweet' => '0','brewStyleTags' => 'standard-strength, pale-color, top-fermented, centraleurope, historical-style, wheat-beer-family, sour, smoke','brewStyleComEx' => NULL,'brewStyleEntry' => NULL);
	$result = $db->insert ($update_table, $data);
	if ($result) $output .= "<li>Lichtenhainer style added to BJCP 2015 styles.</li>";
	else $output .= "<li>Lichtenhainer style NOT added to BJCP 2015 styles. Error: ".$db_conn->getLastError()."</li>";

}

if (!check_new_style("27","A4","Roggenbier")) {

	$data = array('brewStyleGroup' => '27','brewStyleNum' => 'A4','brewStyle' => 'Roggenbier','brewStyleCategory' => 'Historical Beer','brewStyleVersion' => 'BJCP2015','brewStyleOG' => '1.046','brewStyleOGMax' => '1.056','brewStyleFG' => '1.010','brewStyleFGMax' => '1.014','brewStyleABV' => '4.5','brewStyleABVMax' => '6.0','brewStyleIBU' => '10','brewStyleIBUMax' => '20','brewStyleSRM' => '14','brewStyleSRMMax' => '19','brewStyleType' => '1','brewStyleInfo' => 'A dunkelweizen made with rye rather than wheat, but with a greater body and light finishing hops.','brewStyleLink' => 'http://bjcp.org/stylecenter.php','brewStyleActive' => 'Y','brewStyleOwn' => 'bcoe','brewStyleReqSpec' => '0','brewStyleStrength' => '0','brewStyleCarb' => '0','brewStyleSweet' => '0','brewStyleTags' => 'standard-strength, amber-color, top-fermenting, central-europe, historical-style, wheat-beer-family','brewStyleComEx' => 'Thurn und Taxis Roggen','brewStyleEntry' => NULL);
	$result = $db->insert ($update_table, $data);
	if ($result) $output .= "<li>Roggenbier style added to BJCP 2015 styles.</li>";
	else $output .= "<li>Roggenbier style NOT added to BJCP 2015 styles. Error: ".$db_conn->getLastError()."</li>";

}

if (!check_new_style("27","A5","Sahti")) {

	$data = array('brewStyleGroup' => '27','brewStyleNum' => 'A5','brewStyle' => 'Sahti','brewStyleCategory' => 'Historical Beer','brewStyleVersion' => 'BJCP2015','brewStyleOG' => '1.076','brewStyleOGMax' => '1.120','brewStyleFG' => '1.016','brewStyleFGMax' => '1.020','brewStyleABV' => '7.0','brewStyleABVMax' => '11.0','brewStyleIBU' => '7','brewStyleIBUMax' => '15','brewStyleSRM' => '4','brewStyleSRMMax' => '22','brewStyleType' => '1','brewStyleInfo' => 'A sweet, heavy, strong traditional Finnish beer with a rye, juniper, and juniper berry flavor and a strong banana-clove yeast character.','brewStyleLink' => 'http://bjcp.org/stylecenter.php','brewStyleActive' => 'Y','brewStyleOwn' => 'bcoe','brewStyleReqSpec' => '0','brewStyleStrength' => '0','brewStyleCarb' => '0','brewStyleSweet' => '0','brewStyleTags' => 'high-strength, amber-color, top-fermented, centraleurope, historical-style, spice','brewStyleComEx' => NULL,'brewStyleEntry' => NULL);
	$result = $db->insert ($update_table, $data);
	if ($result) $output .= "<li>Sahti style added to BJCP 2015 styles.</li>";
	else $output .= "<li>Sahti style NOT added to BJCP 2015 styles. Error: ".$db_conn->getLastError()."</li>";

}

if (!check_new_style("27","A6","Kentucky Common")) {

	$data = array('brewStyleGroup' => '27','brewStyleNum' => 'A6','brewStyle' => 'Kentucky Common','brewStyleCategory' => 'Historical Beer','brewStyleVersion' => 'BJCP2015','brewStyleOG' => '1.044','brewStyleOGMax' => '1.055','brewStyleFG' => '1.010','brewStyleFGMax' => '1.018','brewStyleABV' => '4.0','brewStyleABVMax' => '5.5','brewStyleIBU' => '15','brewStyleIBUMax' => '30','brewStyleSRM' => '11','brewStyleSRMMax' => '20','brewStyleType' => '1','brewStyleInfo' => 'A darker-colored, light-flavored, malt-accented beer with a dry finish and interesting character malt flavors. Refreshing due to its high carbonation and mild flavors, and highly  sessionable due to being served very fresh and with restrained alcohol levels.','brewStyleLink' => 'http://bjcp.org/stylecenter.php','brewStyleActive' => 'Y','brewStyleOwn' => 'bcoe','brewStyleReqSpec' => '0','brewStyleStrength' => '0','brewStyleCarb' => '0','brewStyleSweet' => '0','brewStyleTags' => 'standard-strength, amber-color, top-fermented, north america,historical-style, balanced','brewStyleComEx' => 'Apocalypse Brew Works Ortel\'s 1912','brewStyleEntry' => NULL);
	$result = $db->insert ($update_table, $data);
	if ($result) $output .= "<li>Kentucky Common style added to BJCP 2015 styles.</li>";
	else $output .= "<li>Kentucky Common style NOT added to BJCP 2015 styles. Error: ".$db_conn->getLastError()."</li>";

}

if (!check_new_style("27","A7","Pre-Prohibition Lager")) {

	$data =  array('brewStyleGroup' => '27','brewStyleNum' => 'A7','brewStyle' => 'Pre-Prohibition Lager','brewStyleCategory' => 'Historical Beer','brewStyleVersion' => 'BJCP2015','brewStyleOG' => '1.044','brewStyleOGMax' => '1.060','brewStyleFG' => '1.010','brewStyleFGMax' => '1.015','brewStyleABV' => '4.5','brewStyleABVMax' => '6.0','brewStyleIBU' => '25','brewStyleIBUMax' => '40','brewStyleSRM' => '3','brewStyleSRMMax' => '6','brewStyleType' => '1','brewStyleInfo' => 'A clean, refreshing, but bitter pale lager, often showcasing a grainy-sweet corn flavor. All malt or rice-based versions have a crisper, more neutral character. The higher bitterness level is the largest differentiator between this style and most modern mass-market pale lagers, but the more robust flavor profile also sets it apart.','brewStyleLink' => 'http://bjcp.org/stylecenter.php','brewStyleActive' => 'Y','brewStyleOwn' => 'bcoe','brewStyleReqSpec' => '0','brewStyleStrength' => '0','brewStyleCarb' => '0','brewStyleSweet' => '0','brewStyleTags' => 'standard-strength, pale-color, bottom-fermented, lagered, north-america, historical-style, pilsner-family, bitter, hoppy','brewStyleComEx' => 'Anchor California Lager, Coors Batch 19, Little Harpeth Chicken Scratch','brewStyleEntry' => NULL);
	$result = $db->insert ($update_table, $data);
	if ($result) $output .= "<li>Pre-Prohibition Lager style added to BJCP 2015 styles.</li>";
	else $output .= "<li>Pre-Prohibition Lager style NOT added to BJCP 2015 styles. Error: ".$db_conn->getLastError()."</li>";

}

if (!check_new_style("27","A8","Pre-Prohibition Porter")) {

	$data = array('brewStyleGroup' => '27','brewStyleNum' => 'A8','brewStyle' => 'Pre-Prohibition Porter','brewStyleCategory' => 'Historical Beer','brewStyleVersion' => 'BJCP2015','brewStyleOG' => '1.046','brewStyleOGMax' => '1.060','brewStyleFG' => '1.010','brewStyleFGMax' => '1.016','brewStyleABV' => '4.5','brewStyleABVMax' => '6.0','brewStyleIBU' => '20','brewStyleIBUMax' => '30','brewStyleSRM' => '18','brewStyleSRMMax' => '30','brewStyleType' => '1','brewStyleInfo' => 'An American adaptation of English Porter using American ingredients, including adjuncts.','brewStyleLink' => 'http://bjcp.org/stylecenter.php','brewStyleActive' => 'Y','brewStyleOwn' => 'bcoe','brewStyleReqSpec' => '0','brewStyleStrength' => '0','brewStyleCarb' => '0','brewStyleSweet' => '0','brewStyleTags' => 'standard-strength, dark-color, any-fermentation, northamerica, historical-style, porter-family, malty','brewStyleComEx' => 'Stegmaier Porter, Yuengling Porter','brewStyleEntry' => NULL);
	$result = $db->insert ($update_table, $data);
	if ($result) $output .= "<li>Pre-Prohibition Porter style added to BJCP 2015 styles.</li>";
	else $output .= "<li>Pre-Prohibition Porter style NOT added to BJCP 2015 styles. Error: ".$db_conn->getLastError()."</li>";

}

if (!check_new_style("27","A9","London Brown Ale")) {

	$data = array('brewStyleGroup' => '27','brewStyleNum' => 'A9','brewStyle' => 'London Brown Ale','brewStyleCategory' => 'Historical Beer','brewStyleVersion' => 'BJCP2015','brewStyleOG' => '1.033','brewStyleOGMax' => '1.038','brewStyleFG' => '1.012','brewStyleFGMax' => '1.015','brewStyleABV' => '2.8','brewStyleABVMax' => '3.6','brewStyleIBU' => '15','brewStyleIBUMax' => '20','brewStyleSRM' => '22','brewStyleSRMMax' => '35','brewStyleType' => '1','brewStyleInfo' => 'A luscious, sweet, malt-oriented dark brown ale, with caramel and toffee malt complexity and a sweet finish.','brewStyleLink' => 'http://bjcp.org/stylecenter.php','brewStyleActive' => 'Y','brewStyleOwn' => 'bcoe','brewStyleReqSpec' => '0','brewStyleStrength' => '0','brewStyleCarb' => '0','brewStyleSweet' => '0','brewStyleTags' => 'session-strength, dark-color, top-fermented, britishisles, historical-style, brown-ale-family, malty, sweet','brewStyleComEx' => 'Harveys Bloomsbury Brown Ale, Mann\'s Brown Ale','brewStyleEntry' => 'Entrant MUST specify a strength (session: 3.0-5.0%, standard: 5.0-7.5%, double: 7.5-9.5%).');
	$result = $db->insert ($update_table, $data);
	if ($result) $output .= "<li>London Brown Ale style added to BJCP 2015 styles.</li>";
	else $output .= "<li>London Brown Ale style NOT added to BJCP 2015 styles. Error: ".$db_conn->getLastError()."</li>";

}

if (!check_new_style("21","B1","Belgian IPA")) {

	$data = array('brewStyleGroup' => '21','brewStyleNum' => 'B1','brewStyle' => 'Belgian IPA','brewStyleCategory' => 'Specialty IPA','brewStyleVersion' => 'BJCP2015','brewStyleOG' => '1.058','brewStyleOGMax' => '1.080','brewStyleFG' => '1.008','brewStyleFGMax' => '1.016','brewStyleABV' => '6.2','brewStyleABVMax' => '9.5','brewStyleIBU' => '50','brewStyleIBUMax' => '100','brewStyleSRM' => '5','brewStyleSRMMax' => '15','brewStyleType' => '1','brewStyleInfo' => 'An IPA with the fruitiness and spiciness derived from the use of Belgian yeast. The examples from Belgium tend to be lighter in color and more attenuated, similar to a tripel that has been brewed with more hops. This beer has a more complex flavor profile and may be higher in alcohol than a typical IPA.','brewStyleLink' => 'http://bjcp.org/stylecenter.php','brewStyleActive' => 'Y','brewStyleOwn' => 'bcoe','brewStyleReqSpec' => '1','brewStyleStrength' => '0','brewStyleCarb' => '0','brewStyleSweet' => '0','brewStyleTags' => 'high-strength, pale-color, top-fermented, north-america, craft-style, ipa-family, specialty-family, bitter, hoppy','brewStyleComEx' => 'Brewery Vivant Triomphe, Houblon Chouffe, Epic Brainless IPA, Green Flash Le Freak, Stone Cali-Belgique, Urthel Hop It','brewStyleEntry' => 'Entrant MUST specify a strength (session: 3.0-5.0%, standard: 5.0-7.5%, double: 7.5-9.5%).');
	$result = $db->insert ($update_table, $data);
	if ($result) $output .= "<li>Belgian IPA style added to BJCP 2015 styles.</li>";
	else $output .= "<li>Belgian IPA style NOT added to BJCP 2015 styles. Error: ".$db_conn->getLastError()."</li>";

}

if (!check_new_style("21","B2","Black IPA")) {

	$data = array('brewStyleGroup' => '21','brewStyleNum' => 'B2','brewStyle' => 'Black IPA','brewStyleCategory' => 'Specialty IPA','brewStyleVersion' => 'BJCP2015','brewStyleOG' => '1.050','brewStyleOGMax' => '1.085','brewStyleFG' => '1.010','brewStyleFGMax' => '1.018','brewStyleABV' => '5.5','brewStyleABVMax' => '9.0','brewStyleIBU' => '50','brewStyleIBUMax' => '90','brewStyleSRM' => '25','brewStyleSRMMax' => '40','brewStyleType' => '1','brewStyleInfo' => 'A beer with the dryness, hop-forward balance, and flavor characteristics of an American IPA, only darker in color – but without strongly roasted or burnt flavors. The flavor of darker malts is gentle and supportive, not a major flavor component. Drinkability is a key characteristic.','brewStyleLink' => 'http://bjcp.org/stylecenter.php','brewStyleActive' => 'Y','brewStyleOwn' => 'bcoe','brewStyleReqSpec' => '1','brewStyleStrength' => '0','brewStyleCarb' => '0','brewStyleSweet' => '0','brewStyleTags' => 'high-strength, dark-color, top-fermented, north-america, craft-style, ipa-family, specialty-family, bitter, hoppy','brewStyleComEx' => '21st Amendment Back in Black (standard), Deschutes Hop in the Dark CDA (standard), Rogue Dad’s Little Helper (standard), Southern Tier Iniquity (double), Widmer Pitch Black IPA (standard)','brewStyleEntry' => 'Entrant MUST specify a strength (session: 3.0-5.0%, standard: 5.0-7.5%, double: 7.5-9.5%).');
	$result = $db->insert ($update_table, $data);
	if ($result) $output .= "<li>Black IPA style added to BJCP 2015 styles.</li>";
	else $output .= "<li>Black IPA style NOT added to BJCP 2015 styles. Error: ".$db_conn->getLastError()."</li>";

}

if (!check_new_style("21","B3","Brown IPA")) {

	$data = array('brewStyleGroup' => '21','brewStyleNum' => 'B3','brewStyle' => 'Brown IPA','brewStyleCategory' => 'Specialty IPA','brewStyleVersion' => 'BJCP2015','brewStyleOG' => '1.056','brewStyleOGMax' => '1.070','brewStyleFG' => '1.008','brewStyleFGMax' => '1.016','brewStyleABV' => '5.5','brewStyleABVMax' => '7.5','brewStyleIBU' => '40','brewStyleIBUMax' => '70','brewStyleSRM' => '11','brewStyleSRMMax' => '19','brewStyleType' => '1','brewStyleInfo' => 'Hoppy, bitter, and moderately strong like an American IPA, but with some caramel, chocolate, toffee, and/or dark fruit malt character as in an American Brown Ale. Retaining the dryish finish and lean body that makes IPAs so drinkable, a Brown IPA is a little more flavorful and malty than an American IPA without being sweet or heavy.','brewStyleLink' => 'http://bjcp.org/stylecenter.php','brewStyleActive' => 'Y','brewStyleOwn' => 'bcoe','brewStyleReqSpec' => '1','brewStyleStrength' => '0','brewStyleCarb' => '0','brewStyleSweet' => '0','brewStyleTags' => 'high-strength, dark-color, top-fermented, north-america, craft-style, ipa-family, specialty-family, bitter, hoppy','brewStyleComEx' => 'Dogfish Head Indian Brown Ale, Grand Teton Bitch Creek, Harpoon Brown IPA, Russian River Janet’s Brown Ale','brewStyleEntry' => 'Entrant MUST specify a strength (session: 3.0-5.0%, standard: 5.0-7.5%, double: 7.5-9.5%).');
	$result = $db->insert ($update_table, $data);
	if ($result) $output .= "<li>Brown IPA style added to BJCP 2015 styles.</li>";
	else $output .= "<li>Brown IPA style NOT added to BJCP 2015 styles. Error: ".$db_conn->getLastError()."</li>";

}

if (!check_new_style("21","B4","Red IPA")) {

	$data = array('brewStyleGroup' => '21','brewStyleNum' => 'B4','brewStyle' => 'Red IPA','brewStyleCategory' => 'Specialty IPA','brewStyleVersion' => 'BJCP2015','brewStyleOG' => '1.056','brewStyleOGMax' => '1.070','brewStyleFG' => '1.008','brewStyleFGMax' => '1.016','brewStyleABV' => '5.5','brewStyleABVMax' => '7.5','brewStyleIBU' => '40','brewStyleIBUMax' => '70','brewStyleSRM' => '11','brewStyleSRMMax' => '19','brewStyleType' => '1','brewStyleInfo' => 'Hoppy, bitter, and moderately strong like an American IPA, but with some caramel, toffee, and/or dark fruit malt character. Retaining the dryish finish and lean body that makes IPAs so drinkable, a Red IPA is a little more flavorful and malty than an American IPA without being sweet or heavy.','brewStyleLink' => 'http://bjcp.org/stylecenter.php','brewStyleActive' => 'Y','brewStyleOwn' => 'bcoe','brewStyleReqSpec' => '1','brewStyleStrength' => '0','brewStyleCarb' => '0','brewStyleSweet' => '0','brewStyleTags' => 'high-strength, amber-color, top-fermented, north-america, craft-style, ipa-family, specialty-family, bitter, hoppy','brewStyleComEx' => 'Green Flash Hop Head Red Double Red IPA (double), Midnight Sun Sockeye Red, Sierra Nevada Flipside Red IPA, Summit Horizon Red IPA, Odell Runoff Red IPA','brewStyleEntry' => 'Entrant MUST specify a strength (session: 3.0-5.0%, standard: 5.0-7.5%, double: 7.5-9.5%).');
	$result = $db->insert ($update_table, $data);
	if ($result) $output .= "<li>Red IPA style added to BJCP 2015 styles.</li>";
	else $output .= "<li>Red IPA style NOT added to BJCP 2015 styles. Error: ".$db_conn->getLastError()."</li>";

}

if (!check_new_style("21","B5","Rye IPA")) {

	$data = array('brewStyleGroup' => '21','brewStyleNum' => 'B5','brewStyle' => 'Rye IPA','brewStyleCategory' => 'Specialty IPA','brewStyleVersion' => 'BJCP2015','brewStyleOG' => '1.056','brewStyleOGMax' => '1.075','brewStyleFG' => '1.008','brewStyleFGMax' => '1.014','brewStyleABV' => '5.5','brewStyleABVMax' => '8.0','brewStyleIBU' => '50','brewStyleIBUMax' => '75','brewStyleSRM' => '6','brewStyleSRMMax' => '14','brewStyleType' => '1','brewStyleInfo' => 'A decidedly hoppy and bitter, moderately strong American pale ale, showcasing modern American and New World hop varieties and rye malt. The balance is hop-forward, with a clean fermentation profile, dry finish, and clean, supporting malt allowing a creative range of hop character to shine through.','brewStyleLink' => 'http://bjcp.org/stylecenter.php','brewStyleActive' => 'Y','brewStyleOwn' => 'bcoe','brewStyleReqSpec' => '1','brewStyleStrength' => '0','brewStyleCarb' => '0','brewStyleSweet' => '0','brewStyleTags' => 'high-strength, amber-color, top-fermented, north-america, craft-style, ipa-family, specialty-family, bitter, hoppy','brewStyleComEx' => 'Arcadia Sky High Rye, Bear Republic Hop Rod Rye, Founders Reds Rye, Great Lakes Rye of the Tiger, Sierra Nevada Ruthless Rye','brewStyleEntry' => 'Entrant MUST specify a strength (session: 3.0-5.0%, standard: 5.0-7.5%, double: 7.5-9.5%).');
	$result = $db->insert ($update_table, $data);
	if ($result) $output .= "<li>Rye IPA style added to BJCP 2015 styles.</li>";
	else $output .= "<li>Rye IPA style NOT added to BJCP 2015 styles. Error: ".$db_conn->getLastError()."</li>";

}

if (!check_new_style("21","B6","White IPA")) {

	$data = array('brewStyleGroup' => '21','brewStyleNum' => 'B6','brewStyle' => 'White IPA','brewStyleCategory' => 'Specialty IPA','brewStyleVersion' => 'BJCP2015','brewStyleOG' => '1.056','brewStyleOGMax' => '1.065','brewStyleFG' => '1.010','brewStyleFGMax' => '1.016','brewStyleABV' => '5.5','brewStyleABVMax' => '7.0','brewStyleIBU' => '40','brewStyleIBUMax' => '70','brewStyleSRM' => '5','brewStyleSRMMax' => '8','brewStyleType' => '1','brewStyleInfo' => 'A fruity, spicy, refreshing version of an American IPA, but with a lighter color, less body, and featuring either the distinctive yeast and/or spice additions typical of a Belgian witbier.','brewStyleLink' => 'http://bjcp.org/stylecenter.php','brewStyleActive' => 'Y','brewStyleOwn' => 'bcoe','brewStyleReqSpec' => '1','brewStyleStrength' => '0','brewStyleCarb' => '0','brewStyleSweet' => '0','brewStyleTags' => 'high-strength, pale-color, top-fermented, north-america, craft-style, ipa-family, specialty-family, bitter, hoppy, spice','brewStyleComEx' => 'Blue Point White IPA, Deschutes Chainbreaker IPA, Harpoon The Long Thaw, New Belgium Accumulation','brewStyleEntry' => 'Entrant MUST specify a strength (session: 3.0-5.0%, standard: 5.0-7.5%, double: 7.5-9.5%).');
	$result = $db->insert ($update_table, $data);
	if ($result) $output .= "<li>White IPA style added to BJCP 2015 styles.</li>";
	else $output .= "<li>White IPA style NOT added to BJCP 2015 styles. Error: ".$db_conn->getLastError()."</li>";

}

/**
 * ----------------------------------------------- 2.1.9 -----------------------------------------------
 * Correct the problem with new BJCP "example" substyles not being saved correctly
 * -----------------------------------------------------------------------------------------------------
 */

if (check_update("brewerNickname", $prefix."brewer")) {

	$sql = sprintf("ALTER TABLE `%s` CHANGE `brewerNickname` `brewerStaff` CHAR(1) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL;", $prefix."brewer");
	mysqli_select_db($connection,$database);
	mysqli_real_escape_string($connection,$sql);
	$result = mysqli_query($connection,$sql) or die (mysqli_error($connection));
	if ($result) $output .= "<li>Participant staff opt in added to the brewer table.</li>";
	else $output .= "<li>Participant staff opt in NOT added to the brewer table. Error: ".$db_conn->getLastError()."</li>";


}

if ((!check_update("brewerNickname", $prefix."brewer")) && (!check_update("brewerStaff", $prefix."brewer"))) {

	$sql = sprintf("ALTER TABLE `%s` ADD `brewerStaff` CHAR(1) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL;", $prefix."brewer");
	mysqli_select_db($connection,$database);
	mysqli_real_escape_string($connection,$sql);
	$result = mysqli_query($connection,$sql) or die (mysqli_error($connection));
	if ($result) $output .= "<li>Participant staff opt in added to the brewer table.</li>";
	else $output .= "<li>Participant staff opt in NOT added to the brewer table. Error: ".$db_conn->getLastError()."</li>";

}

$sql = sprintf("ALTER TABLE `%s` CHANGE `brewCategory` `brewCategory` VARCHAR(4) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL, CHANGE `brewCategorySort` `brewCategorySort` VARCHAR(4) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL, CHANGE `brewSubCategory` `brewSubCategory` VARCHAR(4) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL;", $prefix."brewing");
mysqli_select_db($connection,$database);
mysqli_real_escape_string($connection,$sql);
$result = mysqli_query($connection,$sql) or die (mysqli_error($connection));
if ($result) $output .= "<li>Brewing table updated to correct style saving bug.</li>";
else $output .= "<li>Brewing table NOT updated to correct style saving bug. Error: ".$db_conn->getLastError()."</li>";

if (!check_update("assignRoles", $prefix."judging_assignments")) {

	$sql = sprintf("ALTER TABLE `%s` ADD `assignRoles` VARCHAR(25) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL;", $prefix."judging_assignments");
	mysqli_select_db($connection,$database);
	mysqli_real_escape_string($connection,$sql);
	$result = mysqli_query($connection,$sql) or die (mysqli_error($connection));
	if ($result) $output .= "<li>Judging Roles column added to the judging_assignments table.</li>";
	else $output .= "<li>Judging Roles column NOT added to the judging_assignments  table. Error: ".$db_conn->getLastError()."</li>";

}


/**
 * ----------------------------------------------- 2.1.10 ----------------------------------------------
 * Add db columns to store Pro Edition data such as Brewery Name and TTB Number
 * Add db columns to allow for PayPal IPN use
 * Add db columns for best brewer preferences
 * Alter prefsStyleSet to accommodate BA style data and BreweryDB key
 * Update archive db tables to accommodate Pro Edition and BA Styles
 * -----------------------------------------------------------------------------------------------------
 */

if (!check_update("brewerBreweryName", $prefix."brewer")) {
	
	$sql = sprintf("ALTER TABLE `%s` ADD `brewerBreweryName` VARCHAR(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL, ADD `brewerBreweryTTB` VARCHAR(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL;", $prefix."brewer");
	mysqli_select_db($connection,$database);
	mysqli_real_escape_string($connection,$sql);
	$result = mysqli_query($connection,$sql) or die (mysqli_error($connection));
	if ($result) $output .= "<li>Pro Edition brewery and TTB columns added to the brewer table.</li>";
	else $output .= "<li>Pro Edition brewery and TTB columns NOT added to the brewer table. Error: ".$db_conn->getLastError()."</li>";

}

if (!check_update("prefsShowBestBrewer", $prefix."preferences")) {

	$sql = sprintf("ALTER TABLE `%s`
		ADD `prefsShowBestBrewer` int(1) DEFAULT NULL,
		ADD `prefsBestBrewerTitle` varchar(255) DEFAULT NULL,
		ADD `prefsShowBestClub` int(1) DEFAULT NULL,
		ADD `prefsBestClubTitle` varchar(255) DEFAULT NULL,
		ADD `prefsFirstPlacePts` int(1) DEFAULT 0,
		ADD `prefsSecondPlacePts` int(1) DEFAULT 0,
		ADD `prefsThirdPlacePts` int(1) DEFAULT 0,
		ADD `prefsFourthPlacePts` int(1) DEFAULT 0,
		ADD `prefsHMPts` int(1) DEFAULT 0,
		ADD `prefsTieBreakRule1` varchar(255) DEFAULT NULL,
		ADD `prefsTieBreakRule2` varchar(255) DEFAULT NULL,
		ADD `prefsTieBreakRule3` varchar(255) DEFAULT NULL,
		ADD `prefsTieBreakRule4` varchar(255) DEFAULT NULL,
		ADD `prefsTieBreakRule5` varchar(255) DEFAULT NULL,
		ADD `prefsTieBreakRule6` varchar(255) DEFAULT NULL;",
		$prefix."preferences");
	mysqli_select_db($connection,$database);
	mysqli_real_escape_string($connection,$sql);
	$result = mysqli_query($connection,$sql) or die (mysqli_error($connection));
	if ($result) $output .= "<li>Best Brewer and Best Club columns added to preferences table.</li>";
	else $output .= "<li>Best Brewer and Best Club columns NOT added to preferences table. Error: ".$db_conn->getLastError()."</li>";

}

if (!check_update("prefsCAPTCHA", $prefix."preferences")) {

	$sql = sprintf("ALTER TABLE `%s` ADD `prefsCAPTCHA` tinyint(1) COLLATE utf8mb4_unicode_ci DEFAULT NULL;",$prefix."preferences");
	mysqli_select_db($connection,$database);
	mysqli_real_escape_string($connection,$sql);
	$result = mysqli_query($connection,$sql) or die (mysqli_error($connection));
	if ($result) $output .= "<li>CAPTCHA column added to preferences table.</li>";
	else $output .= "<li>CAPTCHA column NOT added to preferences table. Error: ".$db_conn->getLastError()."</li>";	

	if (check_update("prefsCAPTCHA", $prefix."preferences")) {
		$update_table = $prefix."preferences";
		$data = array('prefsCAPTCHA' => 0);
		$db_conn->where ('id', 1);
		if ($db_conn->update ($update_table, $data)) $output .= "<li>CAPTCHA column value updated in the preferences table.</li>";
		else $output .= "<li>CAPTCHA column value NOT updated in the preferences table. Error: ".$db_conn->getLastError()."</li>";
	}
	else $output .= "<li>CAPTCHA column missing in the preferences table.</li>";
		
}

if (!check_update("prefsPaypalIPN", $prefix."preferences")) {

	$sql = sprintf("ALTER TABLE `%s` ADD `prefsPaypalIPN` TINYINT(1) NULL DEFAULT NULL AFTER `prefsPaypalAccount`;",$prefix."preferences");
	mysqli_select_db($connection,$database);
	mysqli_real_escape_string($connection,$sql);
	$result = mysqli_query($connection,$sql) or die (mysqli_error($connection));
	if ($result) $output .= "<li>PayPal IPN column added to preferences table.</li>";
	else $output .= "<li>Paypal IPN column NOT added to preferences table. Error: ".$db_conn->getLastError()."</li>";

}

if (check_update("prefsPaypalIPN", $prefix."preferences")) {
	$update_table = $prefix."preferences";
	$data = array('prefsPaypalIPN' => 0);
	$db_conn->where ('id', 1);
	if ($db_conn->update ($update_table, $data)) $output .= "<li>Paypal IPN column value updated in the preferences table.</li>";
	else $output .= "<li>Paypal IPN column value NOT updated in the preferences table. Error: ".$db_conn->getLastError()."</li>";
}
else $output .= "<li>Paypal IPN column missing in the preferences table.</li>";

if (check_update("prefsCompOrg", $prefix."preferences")) {

	$sql = sprintf("ALTER TABLE `%s` CHANGE `prefsCompOrg` `prefsProEdition` TINYINT(1) NULL DEFAULT NULL;",$prefix."preferences");
	mysqli_select_db($connection,$database);
	mysqli_real_escape_string($connection,$sql);
	$result = mysqli_query($connection,$sql) or die (mysqli_error($connection));
	if ($result) $output .= "<li>Pro Edition column added to preferences table.</li>";
	else $output .= "<li>Pro Edition column NOT added to preferences table. Error: ".$db_conn->getLastError()."</li>";

}

if ((!check_update("prefsCompOrg", $prefix."preferences")) && (!check_update("prefsProEdition", $prefix."preferences"))) {

	$sql = sprintf("ALTER TABLE `%s` ADD `prefsProEdition` TINYINT(1) NULL DEFAULT NULL AFTER `prefsPaypalAccount`;",$prefix."preferences");
	mysqli_select_db($connection,$database);
	mysqli_real_escape_string($connection,$sql);
	$result = mysqli_query($connection,$sql) or die (mysqli_error($connection));
	if ($result) $output .= "<li>Pro Edition column added to preferences table.</li>";
	else $output .= "<li>Pro Edition column NOT added to preferences table. Error: ".$db_conn->getLastError()."</li>";

}

if (check_update("prefsProEdition", $prefix."preferences")) {
	$update_table = $prefix."preferences";
	$data = array('prefsProEdition' => 0);
	$db_conn->where ('id', 1);
	if ($db_conn->update ($update_table, $data)) $output .= "<li>Pro Edition column value updated in the preferences table.</li>";
	else $output .= "<li>Pro Edition column value NOT updated in the preferences table. Error: ".$db_conn->getLastError()."</li>";
}
else $output .= "<li>Pro Edition column missing in the preferences table.</li>";

$sql = sprintf("ALTER TABLE `%s` CHANGE `prefsStyleSet` `prefsStyleSet` TEXT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL;",$prefix."preferences");
mysqli_select_db($connection,$database);
mysqli_real_escape_string($connection,$sql);
$result = mysqli_query($connection,$sql) or die (mysqli_error($connection));
if ($result) $output .= "<li>Style Set column changed to text in the preferences table.</li>";
else $output .= "<li>Style Set column NOT changed to text in the preferences table. Error: ".$db_conn->getLastError()."</li>";

if (check_update("archiveUserTableName", $prefix."archive")) {
	
	/*
	// Change unused archiveUserTableName to new archiveProEdition
	$updateSQL = sprintf("ALTER TABLE `%s` CHANGE `archiveUserTableName` `archiveProEdition` TINYINT(1) NULL DEFAULT NULL;",$prefix."archive");
	mysqli_select_db($connection,$database);
	mysqli_real_escape_string($connection,$updateSQL);
	$result = mysqli_query($connection,$updateSQL);
	*/

	$sql = sprintf("ALTER TABLE `%s` CHANGE `archiveUserTableName` `archiveProEdition` TINYINT(1) NULL DEFAULT NULL;",$prefix."archive");
	mysqli_select_db($connection,$database);
	mysqli_real_escape_string($connection,$sql);
	$result = mysqli_query($connection,$sql) or die (mysqli_error($connection));
	if ($result) $output .= "<li>Archive Pro Edition column added to archive table.</li>";
	else $output .= "<li>Archive Pro Edition column NOT added to archive table. Error: ".$db_conn->getLastError()."</li>";

}

if ((!check_update("archiveUserTableName", $prefix."archive")) && (!check_update("archiveProEdition", $prefix."archive"))) {

	$sql = sprintf("ALTER TABLE `%s` ADD `archiveProEdition` TINYINT(1) NULL DEFAULT NULL;",$prefix."archive");
	mysqli_select_db($connection,$database);
	mysqli_real_escape_string($connection,$sql);
	$result = mysqli_query($connection,$sql) or die (mysqli_error($connection));
	if ($result) $output .= "<li>Archive Pro Edition column added to archive table.</li>";
	else $output .= "<li>Archive Pro Edition column NOT added to archive table. Error: ".$db_conn->getLastError()."</li>";

}

if (check_update("archiveBrewerTableName", $prefix."archive")) {

	$sql = sprintf("ALTER TABLE `%s` CHANGE `archiveBrewerTableName` `archiveStyleSet` VARCHAR(25) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL;",$prefix."archive");
	mysqli_select_db($connection,$database);
	mysqli_real_escape_string($connection,$sql);
	$result = mysqli_query($connection,$sql) or die (mysqli_error($connection));
	if ($result) $output .= "<li>Archive Style Set column added to archive table.</li>";
	else $output .= "<li>Archive Style Set column NOT added to archive table. Error: ".$db_conn->getLastError()."</li>";

}

if ((!check_update("archiveBrewerTableName", $prefix."archive")) && (!check_update("archiveStyleSet", $prefix."archive"))) {

	$sql = sprintf("ALTER TABLE `%s` ADD `archiveStyleSet` VARCHAR(25) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL;",$prefix."archive");
	mysqli_select_db($connection,$database);
	mysqli_real_escape_string($connection,$sql);
	$result = mysqli_query($connection,$sql) or die (mysqli_error($connection));
	if ($result) $output .= "<li>Archive Style Set column added to archive table.</li>";
	else $output .= "<li>Archive Style Set column NOT added to archive table. Error: ".$db_conn->getLastError()."</li>";

}


if (HOSTED) {

	$sql = sprintf("TRUNCATE TABLE `%s`;",$prefix."archive");
	mysqli_select_db($connection,$database);
	mysqli_real_escape_string($connection,$sql);
	$result = mysqli_query($connection,$sql) or die (mysqli_error($connection));
	if ($result) $output .= "<li>Archive table truncated.</li>";
	else $output .= "<li>Archive table not truncated. Error: ".$db_conn->getLastError()."</li>";

}

else {

	// Update all archive "brewer" and "brewing" tables (only for non-hosted installations)
	$query_archive = sprintf("SELECT archiveSuffix FROM %s",$prefix."archive");
	$archive = mysqli_query($connection,$query_archive);
	$row_archive = mysqli_fetch_assoc($archive);
	$totalRows_archive = mysqli_num_rows($archive);

	if ($totalRows_archive > 0) {

		do {

			if ((check_setup($prefix."brewer_".$row_archive['archiveSuffix'],$database)) && (!check_update("brewerBreweryName", $prefix."brewer_".$row_archive['archiveSuffix']))) {

				$sql = sprintf("ALTER TABLE `%s` ADD `brewerBreweryName` VARCHAR(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL, ADD `brewerBreweryTTB` VARCHAR(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL;", $prefix."brewer_".$row_archive['archiveSuffix']);
				mysqli_select_db($connection,$database);
				mysqli_real_escape_string($connection,$sql);
				$result = mysqli_query($connection,$sql) or die (mysqli_error($connection));
				if ($result) $output .= "<li>Brewery Name added to ".$prefix."brewer_".$row_archive['archiveSuffix']." archive table.</li>";
				else $output .= "<li>Brewery Name NOT added to ".$prefix."brewer_".$row_archive['archiveSuffix']." archive table. Error: ".$db_conn->getLastError()."</li>";

			}

			if (check_setup($prefix."brewing_".$row_archive['archiveSuffix'],$database)) {

				if (check_update("brewWinnerSubCat", $prefix."brewing_".$row_archive['archiveSuffix'])) {
					
					$sql = sprintf("ALTER TABLE `%s` CHANGE `brewWinnerSubCat` `brewInfoOptional` TEXT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL;",$prefix."brewing_".$row_archive['archiveSuffix']);
					mysqli_select_db($connection,$database);
					mysqli_real_escape_string($connection,$sql);
					$result = mysqli_query($connection,$sql) or die (mysqli_error($connection));
					if ($result) $output .= "<li>Brew Info Optional column added to ".$prefix."brewing_".$row_archive['archiveSuffix']." archive table.</li>";
					else $output .= "<li>Brew Info Optional column NOT added to ".$prefix."brewing_".$row_archive['archiveSuffix']." archive table. Error: ".$db_conn->getLastError()."</li>";

				}

				if ((!check_update("brewWinnerSubCat", $prefix."brewing_".$row_archive['archiveSuffix'])) && (!check_update("brewInfoOptional", $prefix."brewing_".$row_archive['archiveSuffix']))) {

					$sql = sprintf("ALTER TABLE `%s` ADD `brewInfoOptional` TEXT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL;",$prefix."brewing_".$row_archive['archiveSuffix']);
					mysqli_select_db($connection,$database);
					mysqli_real_escape_string($connection,$sql);
					$result = mysqli_query($connection,$sql) or die (mysqli_error($connection));
					if ($result) $output .= "<li>Brew Info Optional column added to ".$prefix."brewing_".$row_archive['archiveSuffix']." archive table.</li>";
					else $output .= "<li>Brew Info Optional column NOT added to ".$prefix."brewing_".$row_archive['archiveSuffix']." archive table. Error: ".$db_conn->getLastError()."</li>";

				}

			}

		} while ($row_archive = mysqli_fetch_assoc($archive));

	}

}

if (check_update("brewWinnerSubCat", $prefix."brewing")) {

	$sql = sprintf("ALTER TABLE `%s` CHANGE `brewWinnerSubCat` `brewInfoOptional` TEXT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL;",$prefix."brewing");
	mysqli_select_db($connection,$database);
	mysqli_real_escape_string($connection,$sql);
	$result = mysqli_query($connection,$sql) or die (mysqli_error($connection));
	if ($result) $output .= "<li>Optional Info column added to the brewing table.</li>";
	else $output .= "<li>Optional Info column NOT added to the brewing table. Error: ".$db_conn->getLastError()."</li>";

}

if ((!check_update("brewWinnerSubCat", $prefix."brewing")) && (!check_update("brewInfoOptional", $prefix."brewing")))  {

	$sql = sprintf("ALTER TABLE `%s` CHANGE `brewWinnerSubCat` `brewInfoOptional` TEXT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL;",$prefix."brewing");
	mysqli_select_db($connection,$database);
	mysqli_real_escape_string($connection,$sql);
	$result = mysqli_query($connection,$sql) or die (mysqli_error($connection));
	if ($result) $output .= "<li>Optional Info column added to the brewing table.</li>";
	else $output .= "<li>Optional Info column NOT added to the brewing table. Error: ".$db_conn->getLastError()."</li>";

}

if (!check_update("userToken", $prefix."users")) {

	$sql = sprintf("
		ALTER TABLE `%s` ADD `userToken` VARCHAR(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL, 
		ADD `userTokenTime` VARCHAR(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL, 
		ADD `userFailedLogins` INT(11) NULL DEFAULT NULL, 
		ADD `userFailedLoginTime` VARCHAR(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL;",
		$prefix."users");
	mysqli_select_db($connection,$database);
	mysqli_real_escape_string($connection,$sql);
	$result = mysqli_query($connection,$sql) or die (mysqli_error($connection));
	if ($result) $output .= "<li>Fogot password token columns added to the users table.</li>";
	else $output .= "<li>Fogot password token columns NOT added to the users table. Error: ".$db_conn->getLastError()."</li>";

	$update_table = $prefix."users";
	$data = array('userFailedLogins' => 0);
	if ($db_conn->update ($update_table, $data)) $output .= "<li>Set failed logins column to 0 for all records in the users table.</li>";
	else $output .= "<li>Failed logins columns were NOT set to 0 for any records in the users table. Error: ".$db_conn->getLastError()."</li>";

}

$sql = sprintf("ALTER TABLE `%s` CHANGE `contestEntryFee` `contestEntryFee` FLOAT(6,2) NULL DEFAULT NULL, CHANGE `contestEntryFee2` `contestEntryFee2` FLOAT(6,2) NULL DEFAULT NULL, CHANGE `contestEntryFeePasswordNum` `contestEntryFeePasswordNum` FLOAT(6,2) NULL DEFAULT NULL;",$prefix."contest_info");
mysqli_select_db($connection,$database);
mysqli_real_escape_string($connection,$sql);
$result = mysqli_query($connection,$sql) or die (mysqli_error($connection));
if ($result) $output .= "<li>Altered entry fee columns to allow for decimals in the contest_info table.</li>";
else $output .= "<li>Entry fee columns to allow for decimals in the contest_info table NOT altered. Error: ".$db_conn->getLastError()."</li>";

$sql = sprintf("ALTER TABLE `%s` CHANGE `scorePlace` `scorePlace` VARCHAR(3) NULL DEFAULT NULL;",$prefix."judging_scores_bos");
mysqli_select_db($connection,$database);
mysqli_real_escape_string($connection,$sql);
$result = mysqli_query($connection,$sql) or die (mysqli_error($connection));
if ($result) $output .= "<li>Altered place columns to allow for variable characters in the judging_scores_bos table.</li>";
else $output .= "<li>Allow for variable characters in place columns in the judging_scores_bos table NOT successful. Error: ".$db_conn->getLastError()."</li>";

$sql = sprintf("ALTER TABLE `%s` CHANGE `prefsWinnerDelay` `prefsWinnerDelay` VARCHAR(15) NULL DEFAULT NULL COMMENT 'Unix timestamp to display winners';",$prefix."preferences");
mysqli_select_db($connection,$database);
mysqli_real_escape_string($connection,$sql);
$result = mysqli_query($connection,$sql) or die (mysqli_error($connection));
if ($result) $output .= "<li>Altered winner delay column to allow for UNIX timestamp in the preferences table.</li>";
else $output .= "<li>Allow for UNIX timestamp in winner delay column in the preferences table NOT successful. Error: ".$db_conn->getLastError()."</li>";

// Get the delay value from DB
$query_delay = sprintf("SELECT prefsWinnerDelay FROM %s WHERE id='1'", $prefix."preferences");
$delay = mysqli_query($connection,$query_delay) or die (mysqli_error($connection));
$row_delay = mysqli_fetch_assoc($delay);

// Check if the length is less than 10 (Unix timestamp is 10)
// If so, convert to timestamp
if ((!empty($row_delay)) && ((strlen($row_delay['prefsWinnerDelay'])) < 10)) {

	$query_check = sprintf("SELECT judgingDate FROM %s ORDER BY judgingDate DESC LIMIT 1", $prefix."judging_locations");
	$check = mysqli_query($connection,$query_check) or die (mysqli_error($connection));
	$row_check = mysqli_fetch_assoc($check);

	// Add the hour delay to the latest judging date
	$new_timestamp = ($row_delay['prefsWinnerDelay'] * 3600) + $row_check['judgingDate'];

	/*
	$updateSQL = sprintf("UPDATE `%s` SET prefsWinnerDelay='%s';",$prefix."preferences",$new_timestamp);
	mysqli_select_db($connection,$database);
	mysqli_real_escape_string($connection,$updateSQL);
	$result = mysqli_query($connection,$updateSQL);
	*/

	$update_table = $prefix."preferences";
	$data = array('prefsWinnerDelay' => $new_timestamp);
	$db_conn->where ('id', 1);
	if ($db_conn->update ($update_table, $data)) $output .= "<li>Winner display date updated in the preferences table.</li>";
	else $output .= "<li>Winner display date NOT updated in the preferences table. Error: ".$db_conn->getLastError()."</li>";

	// Update the session variable
	$_SESSION['prefsWinnerDelay'] = $new_timestamp;

}

// Instantiate HTMLPurifier
require (LIB.'process.lib.php');
require (CLASSES.'htmlpurifier/HTMLPurifier.standalone.php');
$config_html_purifier = HTMLPurifier_Config::createDefault();
$purifier = new HTMLPurifier($config_html_purifier);

include (CLASSES.'capitalize_name/parser.php');
$name_parser = new FullNameParser();

// Standardize the proper names of entrants and locations
$query_names = sprintf("SELECT * FROM %s",$prefix."brewer");
$names = mysqli_query($connection,$query_names) or die (mysqli_error($connection));
$row_names = mysqli_fetch_assoc($names);
$totalRows_names = mysqli_num_rows($names);

$update_table = $prefix."brewer";

if ($totalRows_names > 0) {

	do {

		$brewerJudgeID = "";
		$brewerClubs = "";
		$brewerJudgeNotes = "";
		$fname = $purifier->purify($row_names['brewerFirstName']);
		$lname = $purifier->purify($row_names['brewerLastName']);

		/**
		 * Use PHP Name Parser class if using Latin-based languages in the array in /lib/process.lib.php
		 * https://github.com/joshfraser/PHP-Name-Parser
		 * Class requires a string with the entire name - concat from form post after purification.
		 * Returns an array with the following keys: "salutation", "fname", "initials", "lname", "suffix"
		 * So, if the user inputs "Dr JOHN B" in the first name field and "MacKay III" the class will 
		 * parse it out and return the individual parts with proper upper-lower case relationships
		 * to read "Dr. John B. MacKay III"
		 */

		if ((isset($row_current_prefs['prefsLanguageFolder'])) && (in_array($row_current_prefs['prefsLanguageFolder'], $name_check_langs))) {

		    $name_to_parse = $fname." ".$lname;
		    $parsed_name = $name_parser->parse_name($name_to_parse);
		    
		    $first_name = "";
		    if (!empty($parsed_name['salutation'])) $first_name .= $parsed_name['salutation']." ";
		    $first_name .= $parsed_name['fname'];
		    if (!empty($parsed_name['initials'])) $first_name .= " ".$parsed_name['initials'];
		    
		    $last_name = "";
		    if ((isset($row_current_prefs['prefsLanguageFolder'])) && (in_array($row_current_prefs['prefsLanguageFolder'], $last_name_exception_langs))) $last_name .= standardize_name($parsed_name['lname']);
		    else $last_name .= $parsed_name['lname']; 
		    if (!empty($parsed_name['suffix'])) $last_name .= " ".$parsed_name['suffix'];
		}

		else {
		    $first_name = $fname;
		    $last_name = $lname;
		}

		$first_name = filter_var($first_name,FILTER_SANITIZE_STRING);
		$last_name = filter_var($last_name,FILTER_SANITIZE_STRING);  
		$address = standardize_name($purifier->purify($row_names['brewerAddress']));
		$address = filter_var($address,FILTER_SANITIZE_STRING);
		$city = standardize_name($purifier->purify($row_names['brewerCity']));
		$city = filter_var($city,FILTER_SANITIZE_STRING);
		$state = $purifier->purify($row_names['brewerState']);
		if (strlen($state) > 2) $state = standardize_name($state);
		else $state = strtoupper($state);
		$state = filter_var($state,FILTER_SANITIZE_STRING);
		$brewerEmail = filter_var($row_names['brewerEmail'],FILTER_SANITIZE_EMAIL);
		
		if (!empty($row_names['brewerJudgeID'])) {
			$brewerJudgeID = sterilize($row_names['brewerJudgeID']);
			$brewerJudgeID = filter_var($brewerJudgeID,FILTER_SANITIZE_STRING);
			$brewerJudgeID = strtoupper($brewerJudgeID);
		}

		if (!empty($row_names['brewerClubs'])) {
			$brewerClubs = $purifier->purify($row_names['brewerClubs']);
			$brewerClubs = filter_var($brewerClubs,FILTER_SANITIZE_STRING);
		}

		if (!empty($row_names['brewerJudgeNotes'])) {
			$brewerJudgeNotes = $purifier->purify($row_names['brewerJudgeNotes']);
			$brewerJudgeNotes = filter_var($brewerJudgeNotes,FILTER_SANITIZE_STRING);
		}

		$data = array(
			'brewerFirstName' => $first_name,
			'brewerLastName' => $last_name,
			'brewerAddress' => $address,
			'brewerCity' => $city,
			'brewerState' => $state,
			'brewerClubs' => $brewerClubs,
			'brewerEmail' => $brewerEmail,
			'brewerJudgeID' => $brewerJudgeID,
			'brewerJudgeNotes' => $brewerJudgeNotes
		);

		$db_conn->where ('id', $row_names['id']);
		if ($db_conn->update ($update_table, $data)) $output .= "<li>Brewer name and associated data cleanup/standardization complete for ".$last_name.", ".$first_name."</li>";
		else $output .= "<li>Brewer name and associated data cleanup/standardization NOT complete for ".$last_name.", ".$first_name."</li>";

	} while ($row_names = mysqli_fetch_assoc($names));

}

// Standardize the names of entries
$query_entry_names = sprintf("SELECT id,brewName,brewInfo,brewComments,brewCoBrewer,brewJudgingNumber FROM %s",$prefix."brewing");
$entry_names = mysqli_query($connection,$query_entry_names) or die (mysqli_error($connection));
$row_entry_names = mysqli_fetch_assoc($entry_names);
$totalRows_entry_names = mysqli_num_rows($entry_names);

$update_table = $prefix."brewing";

if ($totalRows_entry_names > 0) {

	do {

		$brewComments = "";
		$brewCoBrewer = "";
		$brewInfo = "";
		$brewName = standardize_name($purifier->purify($row_entry_names['brewName']));
		$brewName = filter_var($brewName,FILTER_SANITIZE_STRING);

		if (isset($row_entry_names['brewComments'])) $brewComments = $purifier->purify($row_entry_names['brewComments']);

		if (isset($row_entry_names['brewCoBrewer'])) {

			$brewCoBrewer = $purifier->purify($row_entry_names['brewCoBrewer']);
			
			if ((isset($row_current_prefs['prefsLanguageFolder'])) && (in_array($row_current_prefs['prefsLanguageFolder'], $name_check_langs))) {
		    	
		    	$parsed_name = $name_parser->parse_name($brewCoBrewer);

		    	$first_name = "";
			    if (!empty($parsed_name['salutation'])) $first_name .= $parsed_name['salutation']." ";
			    $first_name .= $parsed_name['fname'];
			    if (!empty($parsed_name['initials'])) $first_name .= " ".$parsed_name['initials'];
			    
			    $last_name = "";
			    if ((isset($row_current_prefs['prefsLanguageFolder'])) && (in_array($row_current_prefs['prefsLanguageFolder'], $last_name_exception_langs))) $last_name .= standardize_name($parsed_name['lname']);
			    else $last_name .= $parsed_name['lname']; 
			    if (!empty($parsed_name['suffix'])) $last_name .= " ".$parsed_name['suffix']; 

			    $brewCoBrewer = $first_name." ".$last_name;

			}

			$brewCoBrewer = filter_var($brewCoBrewer,FILTER_SANITIZE_STRING);

		}

		if (isset($row_entry_names['brewInfo'])) {
			$brewInfo = $purifier->purify($row_entry_names['brewInfo']);
			$brewInfo = filter_var($brewInfo,FILTER_SANITIZE_STRING);
		}

		$data = array(
			'brewJudgingNumber' => strtolower($row_entry_names['brewJudgingNumber']),
			'brewComments' => $brewComments,
			'brewCoBrewer' => $brewCoBrewer,
			'brewInfo' => $brewInfo,
			'brewName' => $brewName
		);

		$db_conn->where ('id', $row_entry_names['id']);
		if ($db_conn->update ($update_table, $data)) $output .= "<li>Entry name and associated data cleanup/standardization complete for ".$brewName."</li>";
		else $output .= "<li>Entry name and associated data cleanup/standardization NOT complete for ".$brewName."</li>";

	} while ($row_entry_names = mysqli_fetch_assoc($entry_names));

}

/**
 * ----------------------------------------------- 2.1.11 ----------------------------------------------
 * All PDF files in the user_docs directory must be converted to all lowercase (including extension)
 * -----------------------------------------------------------------------------------------------------
 */

$files = new FilesystemIterator(USER_DOCS);

foreach($files as $file) {

	$mime = mime_content_type($file->getPathname());

	if (stripos($mime, "pdf") !== false) {
		$file_name_current = $file->getFilename();
		$file_name_new = strtolower($file->getFilename());
		rename(USER_DOCS.$file_name_current, USER_DOCS.$file_name_new);
	}

}

/*

if (SINGLE) {

	// Get the delay value from DB
	$query_delay = sprintf("SELECT id,prefsWinnerDelay,comp_id FROM %s", $prefix."preferences");
	$delay = mysqli_query($connection,$query_delay) or die (mysqli_error($connection));
	$row_delay = mysqli_fetch_assoc($delay);

	do {

		// Check if the length is less than 10 (Unix timestamp is 10)
		// If so, convert to timestamp
		if ((strlen($row_delay['prefsWinnerDelay'])) < 10) {

			$query_check = sprintf("SELECT judgingDate FROM %s WHERE comp_id='%s' ORDER BY judgingDate DESC LIMIT 1", $prefix."judging_locations",$row_delay['comp_id']);
			$check = mysqli_query($connection,$query_check) or die (mysqli_error($connection));
			$row_check = mysqli_fetch_assoc($check);

			// Add the hour delay to the latest judging date
			$new_timestamp = ($row_delay['prefsWinnerDelay'] * 3600) + $row_check['judgingDate'];

			$updateSQL = sprintf("UPDATE `%s` SET prefsWinnerDelay='%s' WHERE id='%s';",$prefix."preferences",$new_timestamp,$row_delay['id']);
			mysqli_select_db($connection,$database);
			mysqli_real_escape_string($connection,$updateSQL);
			$result = mysqli_query($connection,$updateSQL);

		}

	} while($row_delay = mysqli_fetch_assoc($delay));

}

*/

$output .= "<li>PDF file names in the user_docs directory converted to lowercase (including extension).</li>";

/**
 * ----------------------------------------------- 2.1.12 ----------------------------------------------
 * Add Certified Cider Judge designation
 * Change unused archive column to archiveScoresheet
 * Saves the preference from current when archiving for correct display of archived scoresheets
 */

if (!check_update("brewerJudgeCider", $prefix."brewer")) {

	$sql = sprintf("ALTER TABLE `%s` ADD `brewerJudgeCider` CHAR(1) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL AFTER `brewerJudgeMead`;",$prefix."brewer");
	mysqli_select_db($connection,$database);
	mysqli_real_escape_string($connection,$sql);
	$result = mysqli_query($connection,$sql) or die (mysqli_error($connection));
	if ($result) $output .= "<li>Certified Cider Judge designation added to brewer table.</li>";
	else $output .= "<li>Certified Cider Judge designation NOT added to brewer table. Error: ".$db_conn->getLastError()."</li>";

	if (check_update("brewerJudgeCider", $prefix."brewer")) {
		$update_table = $prefix."brewer";
		$data = array('brewerJudgeCider' => 'N');
		if ($db_conn->update ($update_table, $data)) $output .= "<li>Certified Cider Judge designation values entered.</li>";
		else $output .= "<li>Certified Cider Judge designation values NOT entered. Error: ".$db_conn->getLastError()."</li>";
	}

}

if (!check_update("archiveScoresheet", $prefix."archive")) {

	$sql = sprintf("ALTER TABLE `%s` CHANGE `archiveBrewingTableName` `archiveScoresheet` CHAR(1) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL;",$prefix."archive");
	mysqli_select_db($connection,$database);
	mysqli_real_escape_string($connection,$sql);
	$result = mysqli_query($connection,$sql) or die (mysqli_error($connection));
	if ($result) $output .= "<li>Archive table updated for proper access of archived scoresheets.</li>";
	else $output .= "<li>Archive table NOT updated for proper access of archived scoresheets. Error: ".$db_conn->getLastError()."</li>";

	if (check_update("archiveScoresheet", $prefix."archive")) {
		$update_table = $prefix."archive";
		$data = array('archiveScoresheet' => 'J');
		if ($db_conn->update ($update_table, $data)) $output .= "<li>Archive table scoresheet values updated.</li>";
		else $output .= "<li>Archive table scoresheet values NOT updated. Error: ".$db_conn->getLastError()."</li>";
	}

}

/**
 * ----------------------------------------------- 2.1.13 ----------------------------------------------
 * Add BA styles to styles DB table
 * As of April 2018, BreweryDB not issuing API keys; installations not able to use BA styles
 * -----------------------------------------------------------------------------------------------------
 */

$ba_styles_present = FALSE;
if (check_new_style("08","077","American-Style Pilsener")) $ba_styles_present = TRUE;
if (!$ba_styles_present) include (UPDATE.'styles_ba_update.php');

/**
 * ----------------------------------------------- 2.1.13 ----------------------------------------------
 * Add toggle to allow users to specify whether to use BOS in "Best of" calculations
 * Change unused brewWinnerPlace field to brewAdminNotes field
 * Change unused brewBOSRound field to brewStaffNotes field
 * Change unused brewBOSPlace field to brewPossAllergens field
 * Make sure prefsLanguage is set to en-US - it is now a choice in preferences
 * -----------------------------------------------------------------------------------------------------
 */

if (!check_update("prefsBestUseBOS", $prefix."preferences")) {

	$sql = sprintf("ALTER TABLE `%s` ADD `prefsBestUseBOS` TINYINT(1) NULL DEFAULT NULL;",$prefix."preferences");
	mysqli_select_db($connection,$database);
	mysqli_real_escape_string($connection,$sql);
	$result = mysqli_query($connection,$sql) or die (mysqli_error($connection));
	if ($result) $output .= "<li>Toggle to preferences added to allow users to specify whether to use BOS in \"Best of\" calculations.</li>";
	else $output .= "<li>Toggle to preferences NOT added to allow users to specify whether to use BOS in \"Best of\" calculations. Error: ".$db_conn->getLastError()."</li>";

	if (check_update("prefsBestUseBOS", $prefix."preferences")) {
		$update_table = $prefix."preferences";
		$data = array('prefsBestUseBOS' => 1);
		$db_conn->where ('id', 1);
		if ($db_conn->update ($update_table, $data)) $output .= "<li>Toggle to preferences value added to allow users to specify whether to use BOS in \"Best of\" calculations.</li>";
		else $output .= "<li>Toggle to preferences value NOT added to allow users to specify whether to use BOS in \"Best of\" calculations. Error: ".$db_conn->getLastError()."</li>";
	}
}

$query_mead_cider_present = sprintf("SELECT COUNT(*) AS 'count' FROM %s WHERE styleTypeName = 'Mead/Cider'",$prefix."style_types");
$mead_cider_present = mysqli_query($connection,$query_mead_cider_present) or die (mysqli_error($connection));
$row_mead_cider_present = mysqli_fetch_assoc($mead_cider_present);

if ($row_mead_cider_present['count'] == 0) {

	$update_table = $prefix."style_types";
	$data = array(
		'styleTypeName' => 'Mead/Cider',
		'styleTypeOwn' => 'bcoe',
		'styleTypeBOS' => 'N',
		'styleTypeBOSMethod' => '1'
	);
	if ($db_conn->insert ($update_table, $data)) $output .= "<li>Mead/Cider style type added.</li>";
	else $output .= "<li>Mead/Cider style type NOT added. Error: ".$db_conn->getLastError()."</li>";

}

else {

	$update_table = $prefix."style_types";
	$data = array('styleTypeOwn' => 'bcoe');
	$db_conn->where ('styleTypeName', 'Mead/Cider');
	if ($db_conn->update ($update_table, $data)) $output .= "<li>Mead/Cider style type updated.</li>";
	else $output .= "<li>Mead/Cider style type NOT updated. Error: ".$db_conn->getLastError()."</li>";

}

if (check_update("brewWinnerPlace", $prefix."brewing")) {

	$sql = sprintf("ALTER TABLE `%s` CHANGE `brewWinnerPlace` `brewAdminNotes` TINYTEXT NULL DEFAULT NULL COMMENT 'Notes about the entry for Admin use';",$prefix."brewing");
	mysqli_select_db($connection,$database);
	mysqli_real_escape_string($connection,$sql);
	$result = mysqli_query($connection,$sql) or die (mysqli_error($connection));
	if ($result) $output .= "<li>Admin notes column added to the brewing table.</li>";
	else $output .= "<li>Admin notes column NOT added to the brewing table. Error: ".$db_conn->getLastError()."</li>";

}

if (check_update("brewBOSRound", $prefix."brewing")) {

	$sql = sprintf("ALTER TABLE `%s` CHANGE `brewBOSRound` `brewStaffNotes` TINYTEXT NULL DEFAULT NULL COMMENT 'Notes about the entry for Staff use';",$prefix."brewing");
	mysqli_select_db($connection,$database);
	mysqli_real_escape_string($connection,$sql);
	$result = mysqli_query($connection,$sql) or die (mysqli_error($connection));
	if ($result) $output .= "<li>Admin staff notes column added to the brewing table.</li>";
	else $output .= "<li>Admin staff notes column NOT added to the brewing table. Error: ".$db_conn->getLastError()."</li>";

}

if (check_update("brewBOSPlace", $prefix."brewing")) {

	$sql = sprintf("ALTER TABLE `%s` CHANGE `brewBOSPlace` `brewPossAllergens` TINYTEXT NULL DEFAULT NULL COMMENT 'Notes about the entry from entrant about possible allergens';",$prefix."brewing");
	mysqli_select_db($connection,$database);
	mysqli_real_escape_string($connection,$sql);
	$result = mysqli_query($connection,$sql) or die (mysqli_error($connection));
	if ($result) $output .= "<li>Possible Allergens column added to the brewing table.</li>";
	else $output .= "<li>Possible Allergens column NOT added to the  brewing table. Error: ".$db_conn->getLastError()."</li>";

}

$sql = sprintf("ALTER TABLE `%s` CHANGE `prefsLanguage` `prefsLanguage` VARCHAR(25) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL",$prefix."preferences");
mysqli_select_db($connection,$database);
mysqli_real_escape_string($connection,$sql);
$result = mysqli_query($connection,$sql) or die (mysqli_error($connection));
if ($result) $output .= "<li>Language column updated in the preferences table.</li>";
else $output .= "<li>Language column NOT updated in the preferences table. Error: ".$db_conn->getLastError()."</li>";

$update_table = $prefix."preferences";
$data = array('prefsLanguage' => 'en-US');
$db_conn->where ('id', 1);
if ($db_conn->update ($update_table, $data))  $output .= "<li>Language column value updated in the preferences table.</li>";
else $output .= "<li>Language column value NOT updated in the preferences table. Error: ".$db_conn->getLastError()."</li>";

/**
 * ----------------------------------------------- 2.1.13 ----------------------------------------------
 * Check for BJCP 2015 Provisional Styles as of July 4, 2018
 * Provisional Styles:
 *  17A1 - British Strong Ale: Burton Ale
 *  21B7 - Specialty IPA: New England IPA
 * Also adding "Provisional Styles" - adding PR prefix for use in system:
 *  PRX1 - Dorada Pampeana
 *  PRX2 - IPA Argenta
 *  PRX3 - Italian Grape Ale
 *  PRX4 - Catharina Sour
 *  PRX5 - New Zealand Pilsner
 * Finally, convert legacy/outdated brewStyleType values to numerical values
 * -----------------------------------------------------------------------------------------------------
 */

$update_table = $prefix."styles";

if (!check_new_style("17","A1","Burton Ale")) {

	$data = array('brewStyleGroup' => '17','brewStyleNum' => 'A1','brewStyle' => 'Burton Ale','brewStyleCategory' => 'British Strong Ale','brewStyleVersion' => 'BJCP2015','brewStyleOG' => '1.055','brewStyleOGMax' => '1.075','brewStyleFG' => '1.018','brewStyleFGMax' => '1.024','brewStyleABV' => '5.0','brewStyleABVMax' => '7.5','brewStyleIBU' => '40','brewStyleIBUMax' => '50','brewStyleSRM' => '14','brewStyleSRMMax' => '22','brewStyleType' => '1','brewStyleInfo' => 'A rich, malty, sweet, and bitter dark ale of moderately strong alcohol. Full bodied and chewy with a balanced hoppy finish and complex malty and hoppy aroma. Fruity notes accentuate the malt richness, while the hops help balance the sweeter finish. Has some similarity in malt flavor to Wee Heavy, but with substantially more bitterness. Less strong than an English Barleywine.','brewStyleLink' => 'http://dev.bjcp.org/beer-styles/17a-british-strong-ale-burton-ale/','brewStyleActive' => 'Y','brewStyleOwn' => 'bcoe','brewStyleReqSpec' => '0','brewStyleStrength' => '0','brewStyleCarb' => '0','brewStyleSweet' => '0','brewStyleTags' => 'high-strength, traditional-style, balanced, strong-ale-family, british-isles, brown-color, top-fermented','brewStyleComEx' => 'The Laboratory Brewery Gone for a Burton','brewStyleEntry' => NULL);
	if ($db_conn->insert ($update_table, $data)) $output .= "<li>Burton Ale style added.</li>";
	else $output .= "<li>Burton Ale style NOT added. Error: ".$db_conn->getLastError()."</li>";

}

if (!check_new_style("21","B7","New England IPA")) {

	$data = array('id' => '416','brewStyleGroup' => '21','brewStyleNum' => 'B7','brewStyle' => 'New England IPA','brewStyleCategory' => 'Specialty IPA','brewStyleVersion' => 'BJCP2015','brewStyleOG' => '1.060','brewStyleOGMax' => '1.085','brewStyleFG' => '1.010','brewStyleFGMax' => '1.015','brewStyleABV' => '6.0','brewStyleABVMax' => '9.0','brewStyleIBU' => '25','brewStyleIBUMax' => '60','brewStyleSRM' => '3','brewStyleSRMMax' => '7','brewStyleType' => '1','brewStyleInfo' => 'An American IPA with intense fruit flavors and aromas, a soft body, and smooth mouthfeel, and often opaque with substantial haze. Less perceived bitterness than traditional IPAs but always massively hop forward. This emphasis on late hopping, especially dry hopping, with hops with tropical fruit qualities lends the specific \'juicy\' character for which this style is known. The style is still evolving, but this style is essentially a smoother, hazier, juicier American IPA. In this context, ‘juicy’ refers to a mental impression of fruit juice or eating fresh, fully ripe fruit. Heavy examples suggestive of milkshakes, creamsicles, or fruit smoothies are beyond this range; IPAs should always be drinkable. Haziness comes from the dry hopping regime, not suspended yeast, starch haze, set pectins, or other techniques; a hazy shine is desirable, not a cloudy, murky mess.','brewStyleLink' => 'http://dev.bjcp.org/beer-styles/21b-specialty-ipa-new-england-ipa/','brewStyleActive' => 'Y','brewStyleOwn' => 'bcoe','brewStyleReqSpec' => '1','brewStyleStrength' => '0','brewStyleCarb' => '0','brewStyleSweet' => '0','brewStyleTags' => 'bitter, craft-style, pale-color, high-strength, hoppy, ipa-family, north-america, specialty-family, top-fermented','brewStyleComEx' => 'Hill Farmstead Susan, Other Half Green Diamonds Double IPA, Tired Hands Alien Church, Tree House Julius, Trillium Congress Street, WeldWerks Juicy Bits','brewStyleEntry' => 'Entrant MUST specify a strength (session: 3.0-5.0%, standard: 5.0-7.5%, double: 7.5-9.5%).');
	if ($db_conn->insert ($update_table, $data)) $output .= "<li>New England IPA style added.</li>";
	else $output .= "<li>New England IPA style NOT added. Error: ".$db_conn->getLastError()."</li>";

}

if (!check_new_style("PR","X1","Dorada Pampeana")) {

	$data = array('id' => '417','brewStyleGroup' => 'PR','brewStyleNum' => 'X1','brewStyle' => 'Dorada Pampeana','brewStyleCategory' => 'Provisional Styles','brewStyleVersion' => 'BJCP2015','brewStyleOG' => '1.042','brewStyleOGMax' => '1.054','brewStyleFG' => '1.009','brewStyleFGMax' => '1.013','brewStyleABV' => '4.3','brewStyleABVMax' => '5.5','brewStyleIBU' => '15','brewStyleIBUMax' => '22','brewStyleSRM' => '3','brewStyleSRMMax' => '5','brewStyleType' => '1','brewStyleInfo' => 'At the beginning argentine homebrewers were very limited: there wasn\'t extract - they could use only pils malt, Cascade hops and dry yeast, commonly Nottingham, Windsor or Safale. With these ingredients, Argentine brewers developed a specific version of Blond Ale, named Dorada Pampeana. Ingredients: usually only pale or pils malt, although may include low rates of caramelized malt. Commonly Cascade hops. Clean American yeast, slightly fruity British or Kölsch, usually packaged in cold.','brewStyleLink' => 'http://dev.bjcp.org/beer-styles/x1-dorada-pampeana/','brewStyleActive' => 'Y','brewStyleOwn' => 'bcoe','brewStyleReqSpec' => '0','brewStyleStrength' => '0','brewStyleCarb' => '0','brewStyleSweet' => '0','brewStyleTags' => NULL,'brewStyleComEx' => NULL,'brewStyleEntry' => NULL);
	if ($db_conn->insert ($update_table, $data)) $output .= "<li>Dorada Pampeana style added.</li>";
	else $output .= "<li>Dorada Pampeana style NOT added. Error: ".$db_conn->getLastError()."</li>";

}

if (!check_new_style("PR","X2","IPA Argenta")) {

	$data = array('id' => '418','brewStyleGroup' => 'PR','brewStyleNum' => 'X2','brewStyle' => 'IPA Argenta','brewStyleCategory' => 'Provisional Styles','brewStyleVersion' => 'BJCP2015','brewStyleOG' => '1.055','brewStyleOGMax' => '1.065','brewStyleFG' => '1.008','brewStyleFGMax' => '1.015','brewStyleABV' => '5.0','brewStyleABVMax' => '6.5','brewStyleIBU' => '35','brewStyleIBUMax' => '60','brewStyleSRM' => '6','brewStyleSRMMax' => '15','brewStyleType' => '1','brewStyleInfo' => 'A decidedly hoppy and bitter, refreshing, and moderately strong Argentine pale ale. The clue is drinkability without harshness and best balance. An Argentine version of the historical English style, developed in 2013 from Somos Cerveceros Association meetings, when its distinctive characteristics were defined. Different from an American IPA in that it is brewed with wheat and using Argentine hops (Cascade, Mapuche and Nugget are typical, although Spalt, Victoria or Bullion may be used to add complexity), with its unique flavor and aroma characteristics. Based on a citrus (from Argetine hops) and wheat pairing idea, like in a Witbier. Low amounts of wheat are similar to a Kölsch grist, as is some fruitiness from fermentation.','brewStyleLink' => 'http://dev.bjcp.org/beer-styles/x2-ipa-argenta/','brewStyleActive' => 'Y','brewStyleOwn' => 'bcoe','brewStyleReqSpec' => '0','brewStyleStrength' => '0','brewStyleCarb' => '0','brewStyleSweet' => '0','brewStyleTags' => NULL,'brewStyleComEx' => 'Antares Ipa Argenta, Kerze Ipa Argenta.','brewStyleEntry' => NULL);
	if ($db_conn->insert ($update_table, $data)) $output .= "<li>IPA Argenta style added.</li>";
	else $output .= "<li>IPA Argenta style NOT added. Error: ".$db_conn->getLastError()."</li>";

}

if (!check_new_style("PR","X3","Italian Grape Ale")) {

	$data = array('id' => '419','brewStyleGroup' => 'PR','brewStyleNum' => 'X3','brewStyle' => 'Italian Grape Ale','brewStyleCategory' => 'Provisional Styles','brewStyleVersion' => 'BJCP2015','brewStyleOG' => '1.043','brewStyleOGMax' => '1.090','brewStyleFG' => '1.007','brewStyleFGMax' => '1.015','brewStyleABV' => '4.8','brewStyleABVMax' => '10','brewStyleIBU' => '10','brewStyleIBUMax' => '30','brewStyleSRM' => '5','brewStyleSRMMax' => '30','brewStyleType' => '1','brewStyleInfo' => 'A sometimes refreshing, sometimes more complex Italian ale characterized by different varieties of grapes.','brewStyleLink' => 'http://dev.bjcp.org/beer-styles/x3-italian-grape-ale/','brewStyleActive' => 'Y','brewStyleOwn' => 'bcoe','brewStyleReqSpec' => '1','brewStyleStrength' => '0','brewStyleCarb' => '0','brewStyleSweet' => '0','brewStyleTags' => NULL,'brewStyleComEx' => 'Montegioco Tibir, Montegioco Open Mind, Birranova Moscata, LoverBeer BeerBera, Loverbeer D\'uvaBeer, Birra del Borgo Equilibrista, Barley BB10, Barley BBevò, Cudera, Pasturana Filare!, Gedeone PerBacco! Toccalmatto Jadis, Rocca dei Conti Tarì Giacchè','brewStyleEntry' => NULL);
	if ($db_conn->insert ($update_table, $data)) $output .= "<li>Italian Grape Ale style added.</li>";
	else $output .= "<li>Italian Grape Ale style NOT added. Error: ".$db_conn->getLastError()."</li>";

}

if (!check_new_style("PR","X4","Catharina Sour")) {

	$data = array('id' => '420','brewStyleGroup' => 'PR','brewStyleNum' => 'X4','brewStyle' => 'Catharina Sour','brewStyleCategory' => 'Provisional Styles','brewStyleVersion' => 'BJCP2015','brewStyleOG' => '1.039','brewStyleOGMax' => '1.048','brewStyleFG' => '1.002','brewStyleFGMax' => '1.008','brewStyleABV' => '4.0','brewStyleABVMax' => '5.5','brewStyleIBU' => '2','brewStyleIBUMax' => '68','brewStyleSRM' => '2','brewStyleSRMMax' => '7','brewStyleType' => '1','brewStyleInfo' => 'A light and refreshing wheat ale with a clean lactic sourness that is balanced by a fresh fruit addition. The low bitterness, light body, moderate alcohol content, and moderately high carbonation allow the flavor and aroma of the fruit to be the primary focus of the beer. The fruit is often, but not always, tropical in nature. This beer is stronger than a Berliner Weiss and typically features fresh fruit. The kettle souring method allows for fast production of the beer, so this is typically a present-use style. It may be bottled or canned, but it should be consumed while fresh.','brewStyleLink' => 'http://dev.bjcp.org/beer-styles/x4-catharina-sour/','brewStyleActive' => 'Y','brewStyleOwn' => 'bcoe','brewStyleReqSpec' => '1','brewStyleStrength' => '0','brewStyleCarb' => '0','brewStyleSweet' => '0','brewStyleTags' => 'craft-style, fruit, sour, specialty-beer','brewStyleComEx' => 'Itajahy Catharina Araca Sour, Blumenau Catharina Sour Sun of a Peach, Lohn Bier Catharina Sour Jaboticaba, Liffey Coroa Real, UNIKA Tangerina, Armada Daenerys.','brewStyleEntry' => 'Entrant must specify the types of fresh fruit(s) used.');
	if ($db_conn->insert ($update_table, $data)) $output .= "<li>Catharina Sour style added.</li>";
	else $output .= "<li>Catharina Sour style NOT added. Error: ".$db_conn->getLastError()."</li>";

}

if (!check_new_style("PR","X5","New Zealand Pilsner")) {

	$data = array('id' => '421','brewStyleGroup' => 'PR','brewStyleNum' => 'X5','brewStyle' => 'New Zealand Pilsner','brewStyleCategory' => 'Provisional Styles','brewStyleVersion' => 'BJCP2015','brewStyleOG' => '1.044','brewStyleOGMax' => '1.046','brewStyleFG' => '1.009','brewStyleFGMax' => '1.014','brewStyleABV' => '4.5','brewStyleABVMax' => '5.8','brewStyleIBU' => '25','brewStyleIBUMax' => '45','brewStyleSRM' => '2','brewStyleSRMMax' => '7','brewStyleType' => '1','brewStyleInfo' => 'A pale, dry, golden-colored, cleanly-fermented beer showcasing the characteristic tropical, citrusy, fruity, grassy New Zealand-type hops. Medium body, soft mouthfeel, and smooth palate and finish, with a neutral to bready malt base provide the support for this very drinkable, refreshing, hop-forward beer.','brewStyleLink' => 'http://dev.bjcp.org/beer-styles/x5-new-zealand-pilsner/','brewStyleActive' => 'Y','brewStyleOwn' => 'bcoe','brewStyleReqSpec' => '0','brewStyleStrength' => '0','brewStyleCarb' => '0','brewStyleSweet' => '0','brewStyleTags' => 'bitter, pale-color, standard-strength, bottom-fermented, hoppy, pilsner-family, lagered, craft-style, pacific','brewStyleComEx' => 'Croucher New Zealand Pilsner, Emerson’s Pilsner, Liberty Halo Pilsner, Panhead Port Road Pilsner, Sawmill Pilsner, Tuatara Mot Eureka','brewStyleEntry' => NULL); 
	if ($db_conn->insert ($update_table, $data)) $output .= "<li>New Zealand Pilsner style added.</li>";
	else $output .= "<li>New Zealand Pilsner style NOT added. Error: ".$db_conn->getLastError()."</li>";

}

$update_table = $prefix."styles";
$style_type_convert = array(
	'Lager' => 1,
	'Ale' => 1,
	'Mixed' => 1,
	'Cider' => 2,
	'Mead' => 3
);

foreach ($style_type_convert as $key => $value) {

	$data = array('brewStyleType' => $value);
	$db_conn->where ('brewStyleType', $key);
	if ($db_conn->update ($update_table, $data)) $output .= "<li>Style type ".$key." updated.</li>";
	else $output .= "<li>Style type ".$key." NOT updated. Error: ".$db_conn->getLastError()."</li>";

}

/*
$updateSQL = sprintf("UPDATE `%s` SET brewStyleType='1' WHERE brewStyleType = 'Lager';",$prefix."styles");
mysqli_select_db($connection,$database);
mysqli_real_escape_string($connection,$updateSQL);
$result = mysqli_query($connection,$updateSQL);

$updateSQL = sprintf("UPDATE `%s` SET brewStyleType='1' WHERE brewStyleType = 'Ale';",$prefix."styles");
mysqli_select_db($connection,$database);
mysqli_real_escape_string($connection,$updateSQL);
$result = mysqli_query($connection,$updateSQL);

$updateSQL = sprintf("UPDATE `%s` SET brewStyleType='1' WHERE brewStyleType = 'Mixed';",$prefix."styles");
mysqli_select_db($connection,$database);
mysqli_real_escape_string($connection,$updateSQL);
$result = mysqli_query($connection,$updateSQL);

$updateSQL = sprintf("UPDATE `%s` SET brewStyleType='1' WHERE brewStyleType = '';",$prefix."styles");
mysqli_select_db($connection,$database);
mysqli_real_escape_string($connection,$updateSQL);
$result = mysqli_query($connection,$updateSQL);

$updateSQL = sprintf("UPDATE `%s` SET brewStyleType='2' WHERE brewStyleType = 'Cider';",$prefix."styles");
mysqli_select_db($connection,$database);
mysqli_real_escape_string($connection,$updateSQL);
$result = mysqli_query($connection,$updateSQL);

$updateSQL = sprintf("UPDATE `%s` SET brewStyleType='3' WHERE brewStyleType = 'Mead';",$prefix."styles");
mysqli_select_db($connection,$database);
mysqli_real_escape_string($connection,$updateSQL);
$result = mysqli_query($connection,$updateSQL);

$output .= "<li>BJCP Provisional styles added.</li>";
*/

/**
 * ----------------------------------------------- 2.1.14 ----------------------------------------------
 * Pro-Am indication. Change brewerJudgeBOS to brewerProAm
 * -----------------------------------------------------------------------------------------------------
 */

if (check_update("brewerJudgeBOS", $prefix."brewer")) {

	$sql = sprintf("ALTER TABLE `%s` CHANGE `brewerJudgeBOS` `brewerProAm` TINYINT(2) NULL DEFAULT NULL",$prefix."brewer");
	mysqli_select_db($connection,$database);
	mysqli_real_escape_string($connection,$sql);
	$result = mysqli_query($connection,$sql) or die (mysqli_error($connection));
	if ($result) $output .= "<li>Previous pro-am column added in the brewer table.</li>";
	else $output .= "<li>Previous pro-am indicator column NOT added in the brewer table. Error: ".$db_conn->getLastError()."</li>";

}

if (isset($row_current_prefs['prefsStyleSet'])) {

	$update_table = $prefix."styles";
	$data = array('brewStyleVersion' => $row_current_prefs['prefsStyleSet']);
	$db_conn->where ('brewStyleOwn', 'custom');
	if ($db_conn->update ($update_table, $data)) $output .= "<li>Custom styles updated to currently chosen style set.</li>";
	else $output .= "<li>Custom styles NOT updated to currently chosen style set. Error: ".$db_conn->getLastError()."</li>";

}

/**
 * ----------------------------------------------- 2.1.15 ----------------------------------------------
 * Make sure that the Scoresheet Upload File Names preference is set to J if not set.
 * Change incorrect BJCP name for style 17A (from English Strong Ale to British Strong Ale)
 * -----------------------------------------------------------------------------------------------------
 */

if ((empty($row_current_prefs['prefsDisplaySpecial'])) || (!isset($row_current_prefs['prefsDisplaySpecial']))) {
	
	$update_table = $prefix."preferences";
	$data = array('prefsDisplaySpecial' => 'J');
	if ($db_conn->update ($update_table, $data)) $output .= "<li>Uploaded scoresheet preferences updated (were not set).</li>";
	else $output .= "<li>Uploaded scoresheet preferences NOT updated. Error: ".$db_conn->getLastError()."</li>";

}

$update_table = $prefix."styles";
$data = array('brewStyle' => 'British Strong Ale');
$db_conn->where ('brewStyle', 'English Strong Ale');
$db_conn->where ('brewStyleVersion', 'BJCP2015');
if ($db_conn->update ($update_table, $data)) $output .= "<li>British Strong Ale name corrected in styles table.</li>";
else $output .= "<li>British Strong Ale name NOT corrected in styles table. Error: ".$db_conn->getLastError()."</li>";

/**
 * ----------------------------------------------- 2.1.19 ----------------------------------------------
 * Change mis-spelled BJCP name for Speciality Fruit Beer style
 * -----------------------------------------------------------------------------------------------------
 */

$update_table = $prefix."styles";
$data = array('brewStyle' => 'Specialty Fruit Beer');
$db_conn->where ('brewStyle', 'Speciality Fruit Beer');
$db_conn->where ('brewStyleVersion', 'BJCP2015');
if ($db_conn->update ($update_table, $data)) $output .= "<li>Specialty Fruit Beer name corrected in styles table.</li>";
else $output .= "<li>Specialty Fruit Beer name NOT corrected in styles table. Error: ".$db_conn->getLastError()."</li>";

$update_table = $prefix."brewing";
$data = array('brewStyle' => 'Specialty Fruit Beer');
$db_conn->where ('brewStyle', 'Speciality Fruit Beer');
if ($db_conn->update ($update_table, $data)) $output .= "<li>All Specialty Fruit Beer names corrected in brewing table.</li>";
else $output .= "<li>All Specialty Fruit Beer names NOT corrected in brewing table. Error: ".$db_conn->getLastError()."</li>";

/**
 * ----------------------------------------------- 2.1.19 ----------------------------------------------
 * Change prefsTimeZone column to FLOAT to accomodate fractional time zone numbers
 * Reported to GitHub https://github.com/geoffhumphrey/brewcompetitiononlineentry/issues/1150
 * -----------------------------------------------------------------------------------------------------
 */

$sql = sprintf("ALTER TABLE `%s` CHANGE `prefsTimeZone` `prefsTimeZone` FLOAT NULL DEFAULT NULL;", $prefix."preferences");
mysqli_select_db($connection,$database);
mysqli_real_escape_string($connection,$sql);
$result = mysqli_query($connection,$sql) or die (mysqli_error($connection));
if ($result) $output .= "<li>prefsTimeZone DB column altered to FLOAT to accomodate fractional time zone numbers.</li>";
else $output .= "<li>prefsTimeZone DB column NOT altered to FLOAT to accomodate fractional time zone numbers. Error: ".$db_conn->getLastError()."</li>";

// $output .= "<li>Altered prefsTimeZone DB column to FLOAT to accomodate fractional time zone numbers.</li>";

/**
 * ----------------------------------------------- 2.1.19 ----------------------------------------------
 * Add Australian Amateur Brewing Championship (AABC) styles to DB.
 * AABC Styles are largely based upon BJCP 2015, but are categorized differently.
 * As such, much of the following are duplicates of BJCP 2015.
 * In a future release, only add the AABC-specific styles and reference BJCP 2015 already in place.
 * Requested via GitHub 
 * @see https://github.com/geoffhumphrey/brewcompetitiononlineentry/issues/1153
 * -----------------------------------------------------------------------------------------------------
 */

$aabc_styles_present = FALSE;
if (!check_new_style("01","01","Light Australian Lager [AABC]")) include (UPDATE.'styles_aabc_update.php');

/**
 * ----------------------------------------------- 2.1.19 ----------------------------------------------
 * Update BA Styles for 2019 and 2020
 * -----------------------------------------------------------------------------------------------------
 */

if (!check_new_style("11","174","Experimental India Pale Ale")) include (UPDATE.'styles_ba_2020_update.php');

/**
 * Update all custom style brewStyleGroup columns to 35 or above 
 * (if not already).
 * First, search DB for custom styles with brewStyleGroup column 
 * values under 35,
 * Then, loop through the results to a) change the number in the 
 * styles table to the next available if over 35 and b) change 
 * any style numbers in the entries table to the new style number.
 */

$query_cust_st = sprintf("SELECT id,brewStyleGroup FROM %s WHERE brewStyleOwn='custom' AND brewStyleGroup < 35 ORDER BY brewStyleGroup ASC",$prefix."styles");
$cust_st = mysqli_query($connection,$query_cust_st) or die (mysqli_error($connection));
$row_cust_st = mysqli_fetch_assoc($cust_st);
$totalRows_cust_st = mysqli_num_rows($cust_st);

if ($totalRows_cust_st > 0) {

	// Get the last custom style number if it's 35 or over
	$query_st_num = sprintf("SELECT brewStyleGroup FROM %s WHERE brewStyleOwn='custom' AND brewStyleGroup >= 35 ORDER BY brewStyleGroup DESC LIMIT 1",$prefix."styles");
	$st_num = mysqli_query($connection,$query_st_num) or die (mysqli_error($connection));
	$row_st_num = mysqli_fetch_assoc($st_num);
	$totalRows_st_num = mysqli_num_rows($st_num);

	if ($totalRows_st_num > 0) $new_style_number = $row_st_num['brewStyleGroup'];
	else $new_style_number = 35;

	do {

		$update_table = $prefix."styles";
		$data = array('brewStyleGroup' => $new_style_number);
		$db_conn->where ('id', $row_cust_st['id']);
		if ($db_conn->update ($update_table, $data)) $new_style_number++;
		
	} while ($row_cust_st = mysqli_fetch_assoc($cust_st));

}
	
/**
 * Update all custom style types ids to greater than 15 (if not already)
 * This reserves 1-15 for system use.
 */

$old_style_types = array(
    "Beer" => "1",
    "Cider" => "2",
    "Mead" => "3",
    "Mead/Cider" => "4"
);

$new_style_types = array(
    "Wine" => "5",
    "Rice Wine" => "6",
    "Spirits" => "7",
    "Kombucha" => "8",
    "Pulque" => "9"
);

$all_style_types = array_merge($old_style_types,$new_style_types);

// First, gather current state of the style types table into an array to use later
$query_current_st = sprintf("SELECT * FROM %s ORDER BY id ASC",$prefix."style_types");
$current_st = mysqli_query($connection,$query_current_st) or die (mysqli_error($connection));
$row_current_st = mysqli_fetch_assoc($current_st);

/*
// Blow away the current state of the table
$updateSQL = sprintf("TRUNCATE %s",$prefix."style_types");
mysqli_real_escape_string($connection,$updateSQL);
$result = mysqli_query($connection,$updateSQL) or die (mysqli_error($connection));
*/

$sql = sprintf("TRUNCATE %s",$prefix."style_types");
mysqli_select_db($connection,$database);
mysqli_real_escape_string($connection,$sql);
$result = mysqli_query($connection,$sql) or die (mysqli_error($connection));

/*
// Rebuild the table with the current base schema 
$updateSQL = "
        INSERT INTO `$style_types_db_table` (`id`, `styleTypeName`, `styleTypeOwn`, `styleTypeBOS`, `styleTypeBOSMethod`) VALUES
        (1, 'Beer', 'bcoe', 'N', 1),
        (2, 'Cider', 'bcoe', 'N', 1),
        (3, 'Mead', 'bcoe', 'N', 1),
        (4, 'Mead/Cider', 'bcoe', 'N', 1);
        ";
mysqli_real_escape_string($connection,$updateSQL);
$result = mysqli_query($connection,$updateSQL) or die (mysqli_error($connection));
*/

$update_table = $prefix."style_types";
$data = array(
	array(
		'id' => 1,
		'styleTypeName' => 'Beer',
		'styleTypeOwn' => 'bcoe',
		'styleTypeBOS' => 'N',
		'styleTypeBOSMethod' => 1
	),
	array(
		'id' => 2,
		'styleTypeName' => 'Cider',
		'styleTypeOwn' => 'bcoe',
		'styleTypeBOS' => 'N',
		'styleTypeBOSMethod' => 1
	),
	array(
		'id' => 3,
		'styleTypeName' => 'Mead',
		'styleTypeOwn' => 'bcoe',
		'styleTypeBOS' => 'N',
		'styleTypeBOSMethod' => 1
	),
	array(
		'id' => 4,
		'styleTypeName' => 'Mead/Cider',
		'styleTypeOwn' => 'bcoe',
		'styleTypeBOS' => 'N',
		'styleTypeBOSMethod' => 1
	)
);
if ($db_conn->insertMulti($update_table, $data)) $output .= "<li>Legacy core style types reconstructed.</li>";
else $output .= "<li>Legacy core style types NOT reconstructed. Error: ".$db_conn->getLastError()."</li>";

// Add the new style types by looping through the array
foreach ($new_style_types as $key => $value) {

    $update_table = $prefix."style_types";
    $data = array(
    	'id' => $value,
    	'styleTypeName' => $key,
		'styleTypeOwn' => 'bcoe',
		'styleTypeBOS' => 'N',
		'styleTypeBOSMethod' => 1
    );
    if ($db_conn->insert ($update_table, $data)) $output .= "<li>".$key." style type added.</li>";
	else $output .= "<li>".$key." style type NOT added. Error: ".$db_conn->getLastError()."</li>";

}

/*
// Start mySQL auto increment at 16
$updateSQL = sprintf("ALTER TABLE %s AUTO_INCREMENT = 16;", $prefix."style_types");
mysqli_real_escape_string($connection,$updateSQL);
$result = mysqli_query($connection,$updateSQL) or die (mysqli_error($connection));
*/

$update_table = $prefix."style_types";
$sql = sprintf("ALTER TABLE %s AUTO_INCREMENT = 16;", $prefix."style_types");
mysqli_select_db($connection,$database);
mysqli_real_escape_string($connection,$sql);
$result = mysqli_query($connection,$sql) or die (mysqli_error($connection));

/**
 * Finally, add the remaining custom styles to the table.
 * If one matches a core style type, update the styles table 
 * with the new relational id.
 * If it does not match, add it to the table, query the table
 * for the new id and update the corresponding relational id 
 * in the styles table.
 */

do {

    // Check against new style types array that was just added
    // If the key exists, update the styles table with the new id
    if (array_key_exists($row_current_st['styleTypeName'], $all_style_types)) {

        // Only worry about any style types that were custom in the "old state"
        if ($row_current_st['id'] > 4) {

        	/*
            $updateSQL = sprintf("UPDATE %s SET brewStyleType='%s' WHERE brewStyleType='%s';",$prefix."styles",$all_style_types[$row_current_st['styleTypeName']],$row_current_st['id']);
            mysqli_real_escape_string($connection,$updateSQL);
            $result = mysqli_query($connection,$updateSQL) or die (mysqli_error($connection));
            */

            $update_table = $prefix."styles";
			$data = array('brewStyleType' => $all_style_types[$row_current_st['styleTypeName']]);
			$db_conn->where ('brewStyleType', $row_current_st['id']);
			if ($db_conn->update ($update_table, $data)) $output .= "<li>".$all_style_types[$row_current_st['styleTypeName']]." reassigned.</li>";
			else $output .= "<li>".$all_style_types[$row_current_st['styleTypeName']]." NOT reassigned. Error: ".$db_conn->getLastError()."</li>";

        }

        $update_table = $prefix."style_types";
		$data = array('styleTypeBOS' => $row_current_st['styleTypeBOS'], 'styleTypeBOSMethod' => $row_current_st['styleTypeBOSMethod']);
		$db_conn->where ('styleTypeName', $row_current_st['styleTypeName']);
		if ($db_conn->update ($update_table, $data)) $output .= "<li>".$row_current_st['styleTypeName']." reassigned.</li>";
		else $output .= "<li>".$row_current_st['styleTypeName']." NOT reassigned. Error: ".$db_conn->getLastError()."</li>";

    }

    // If not, add the style type to table
    // Then, query the table for the new id
    // Finally, update the styles table with the new relational id
    else {

    	$update_table = $prefix."style_types";
		$data = array(
			'styleTypeName' => $row_current_st['styleTypeName'],
			'styleTypeOwn' => 'custom',
			'styleTypeBOS' => $row_current_st['styleTypeBOS'], 
			'styleTypeBOSMethod' => $row_current_st['styleTypeBOSMethod']
		);
		if ($db_conn->insert ($update_table, $data)) $output .= "<li>".$row_current_st['styleTypeName']." reassigned.</li>";
		else $output .= "<li>".$row_current_st['styleTypeName']." NOT reassigned. Error: ".$db_conn->getLastError()."</li>";

        $query_new_st = sprintf("SELECT id FROM %s ORDER BY id DESC LIMIT 1",$prefix."style_types");
        $new_st = mysqli_query($connection,$query_new_st) or die (mysqli_error($connection));
        $row_new_st = mysqli_fetch_assoc($new_st);

        $update_table = $prefix."styles";
		$data = array('brewStyleType' => $row_new_st['id']);
		$db_conn->where ('brewStyleType', $row_current_st['styleTypeName']);
		if ($db_conn->update ($update_table, $data)) $output .= "<li>".$row_current_st['styleTypeName']." relational id updated in styles table.</li>";
		else $output .= "<li>".$row_current_st['styleTypeName']." relational id NOT updated in styles table. Error: ".$db_conn->getLastError()."</li>";

    }

} while($row_current_st = mysqli_fetch_assoc($current_st));
	
// $output .= "<li>Add new style types: Wine, Rice Wine, Spirits, Kombucha, and Pulque.</li>";

/**
 * ----------------------------------------------- 2.1.19 ----------------------------------------------
 * Make sure all judging numbers are converted to lower case. Report from GitHub:
 * @see https://github.com/geoffhumphrey/brewcompetitiononlineentry/issues/1145
 * Makes sure all judging numbers that employ alpha characters can be matched up with 
 * corresponding uploaded scoresheets.
 * -----------------------------------------------------------------------------------------------------
 */


$sql = sprintf("UPDATE %s SET brewJudgingNumber = LOWER(brewJudgingNumber)", $prefix."brewing");
if ($db_conn->rawQuery($sql)) $output .= "<li>All alpha-numeric judging numbers converted to lower case.</li>";
else $output .= "<li>All alpha-numeric judging numbers NOT converted to lower case. Error: ".$db_conn->getLastError()."</li>";

/**
 * ----------------------------------------------- 2.2.0 ---------------------------------------------
 * Provide options for judging session type and end date. Rename current unused 
 * column judgingTime.
 * Helpful for comps that want to hold virtual or distributed judging sessions 
 * over a period of days.
 * ---------------------------------------------------------------------------------------------------
 */

if (check_update("judgingTime", $prefix."judging_locations")) {

	$sql = sprintf("ALTER TABLE `%s` CHANGE `judgingTime` `judgingDateEnd` VARCHAR(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL;", $prefix."judging_locations");
	mysqli_select_db($connection,$database);
	mysqli_real_escape_string($connection,$sql);
	$result = mysqli_query($connection,$sql) or die (mysqli_error($connection));
	if ($result) $output .= "<li>The judgingDateEnd column was added to the judging_locations table.</li>";
	else $output .= "<li>The judgingDateEnd column was NOT added to the judging_locations table. Error: ".$db_conn->getLastError()."</li>";

	$update_table = $prefix."judging_locations";
	$data = array('judgingDateEnd' => NULL);
	if ($db_conn->update ($update_table, $data)) $output .= "<li>The judgingDateEnd value was set.</li>";
	else $output .= "<li>The judgingDateEnd value was NOT set. Error: ".$db_conn->getLastError()."</li>";

}

if (!check_update("judgingLocType", $prefix."judging_locations")) {

	$sql = sprintf("ALTER TABLE `%s` ADD `judgingLocType` TINYINT(2) NULL DEFAULT NULL AFTER `id`;", $prefix."judging_locations");
	mysqli_select_db($connection,$database);
	mysqli_real_escape_string($connection,$sql);
	$result = mysqli_query($connection,$sql) or die (mysqli_error($connection));
	if ($result) $output .= "<li>The judgingLocType column was added to the judging_locations table.</li>";
	else $output .= "<li>The judgingLocType column was NOT added to the judging_locations table. Error: ".$db_conn->getLastError()."</li>";

	$update_table = $prefix."judging_locations";
	$data = array('judgingLocType' => 0);
	if ($db_conn->update ($update_table, $data)) $output .= "<li>The judgingLocType value was set for all judging locations.</li>";
	else $output .= "<li>The judgingLocType value was NOT set for all judging locations. Error: ".$db_conn->getLastError()."</li>";


}

/**
 * ----------------------------------------------- 2.2.0 ---------------------------------------------
 * Provide new columns to enable the use of Tables Planning Mode.
 * Helpful for admins who wish to plan tables and assignments prior to 
 * entry sorting.
 * ---------------------------------------------------------------------------------------------------
 */

if (!check_update("assignPlanning", $prefix."judging_assignments")) {
	
	$sql = sprintf("ALTER TABLE `%s` ADD `assignPlanning` TINYINT(1) NULL;",$prefix."judging_assignments");
	mysqli_select_db($connection,$database);
	mysqli_real_escape_string($connection,$sql);
	$result = mysqli_query($connection,$sql) or die (mysqli_error($connection));
	if ($result) $output .= "<li>The assignPlanning column was added to the judging_assignments table.</li>";
	else $output .= "<li>The assignPlanning column was NOT added to the judging_assignments table. Error: ".$db_conn->getLastError()."</li>";

}

if (!check_update("flightPlanning", $prefix."judging_flights")) {

	$sql = sprintf("ALTER TABLE `%s` ADD `flightPlanning` TINYINT(1) NULL;",$prefix."judging_flights");
	mysqli_select_db($connection,$database);
	mysqli_real_escape_string($connection,$sql);
	$result = mysqli_query($connection,$sql) or die (mysqli_error($connection));
	if ($result) $output .= "<li>The flightPlanning column was added to the judging_flights table.</li>";
	else $output .= "<li>The flightPlanning column was NOT added to the judging_flights table. Error: ".$db_conn->getLastError()."</li>";

}

if (!check_update("jPrefsTablePlanning", $prefix."judging_preferences")) {

	$sql = sprintf("ALTER TABLE `%s` ADD `jPrefsTablePlanning` TINYINT(1) NULL;",$prefix."judging_preferences");
	mysqli_select_db($connection,$database);
	mysqli_real_escape_string($connection,$sql);
	$result = mysqli_query($connection,$sql) or die (mysqli_error($connection));
	if ($result) $output .= "<li>The jPrefsTablePlanning column was added to the judging_preferences table.</li>";
	else $output .= "<li>The jPrefsTablePlanning column was NOT added to the judging_preferences table. Error: ".$db_conn->getLastError()."</li>";

}

// $output .= "<li>Added columns to facilitate Tables Planning Mode.</li>";

if (!check_update("prefsEmailCC", $prefix."preferences")) {

	$sql = sprintf("ALTER TABLE `%s` ADD `prefsEmailCC` TINYINT(1) NULL DEFAULT NULL AFTER `prefsEmailRegConfirm`;",$prefix."preferences");
	mysqli_select_db($connection,$database);
	mysqli_real_escape_string($connection,$sql);
	$result = mysqli_query($connection,$sql) or die (mysqli_error($connection));
	if ($result) $output .= "<li>The prefsEmailCC column was added to the preferences table.</li>";
	else $output .= "<li>The prefsEmailCC column was NOT added to the preferences table. Error: ".$db_conn->getLastError()."</li>";

	$update_table = $prefix."preferences";
	$data = array('prefsEmailCC' => 0);
	if ($db_conn->update ($update_table, $data)) $output .= "<li>The prefsEmailCC value was set to 0.</li>";
	else $output .= "<li>The prefsEmailCC value was NOT set to 0. Error: ".$db_conn->getLastError()."</li>";

}

// $output .= "<li>Added column to enable or disable carbon copying contact messages.</li>";

/**
 * ----------------------------------------------- 2.2.0 ---------------------------------------------
 * Add the winner display method for Archives.
 * Add display winners on past winners list toggle preference for Archives.
 * -- Both allow for display of past winners from archived db tables.
 * ---------------------------------------------------------------------------------------------------
 */

if (!check_update("archiveWinnerMethod", $prefix."archive")) {

	$sql = sprintf("ALTER TABLE `%s` ADD `archiveWinnerMethod` tinyint(1) NULL DEFAULT NULL COMMENT 'Method comp uses to choose winners: 0=by table; 1=by category; 2=by sub-category';",$prefix."archive");
	mysqli_select_db($connection,$database);
	mysqli_real_escape_string($connection,$sql);
	$result = mysqli_query($connection,$sql) or die (mysqli_error($connection));
	if ($result) $output .= "<li>The archiveWinnerMethod column was added to the archive table.</li>";
	else $output .= "<li>The archiveWinnerMethod column was NOT added to the archive table. Error: ".$db_conn->getLastError()."</li>";

	$update_table = $prefix."archive";
	$data = array('archiveWinnerMethod' => 0);
	if ($db_conn->update ($update_table, $data)) $output .= "<li>The archiveWinnerMethod value was set to 0.</li>";
	else $output .= "<li>The archiveWinnerMethod value was NOT set to 0. Error: ".$db_conn->getLastError()."</li>";

}

if (!check_update("archiveDisplayWinners", $prefix."archive")) {

	$sql = sprintf("ALTER TABLE `%s` ADD `archiveDisplayWinners` char(1) NULL DEFAULT NULL;",$prefix."archive");
	mysqli_select_db($connection,$database);
	mysqli_real_escape_string($connection,$sql);
	$result = mysqli_query($connection,$sql) or die (mysqli_error($connection));
	if ($result) $output .= "<li>The archiveDisplayWinners column was added to the archive table.</li>";
	else $output .= "<li>The archiveDisplayWinners column was NOT added to the archive table. Error: ".$db_conn->getLastError()."</li>";

	$update_table = $prefix."archive";
	$data = array('archiveDisplayWinners' => 'N');
	if ($db_conn->update ($update_table, $data)) $output .= "<li>The archiveDisplayWinners value was set to No.</li>";
	else $output .= "<li>The archiveDisplayWinners value was NOT set to No. Error: ".$db_conn->getLastError()."</li>";

}

/** 
 * Get all of the archive suffix records.
 * Check if all necessary DB tables are present for each suffix.
 * If not, add them.
 */

$query_archive = sprintf("SELECT archiveSuffix FROM %s",$prefix."archive");
$archive = mysqli_query($connection,$query_archive);
$row_archive = mysqli_fetch_assoc($archive);
$totalRows_archive = mysqli_num_rows($archive);

$tables_array = array(
	$prefix."brewing", 
	$prefix."judging_assignments", 
	$prefix."judging_flights", 
	$prefix."judging_scores", 
	$prefix."judging_scores_bos", 
	$prefix."judging_tables", 
	$prefix."staff",
	$prefix."brewer",
	$prefix."special_best_data",
	$prefix."special_best_info",
	$prefix."style_types",
	$prefix."users"
);

if ($totalRows_archive > 0) {

	do {

		foreach ($tables_array as $table) {

			$table_archive = $table."_".$row_archive['archiveSuffix'];

			if (!check_setup($table_archive,$database)) {

				$sql = sprintf("CREATE TABLE %s LIKE %s;",$table_archive,$table);
				mysqli_select_db($connection,$database);
				mysqli_real_escape_string($connection,$sql);
				$result = mysqli_query($connection,$sql) or die (mysqli_error($connection));
				if ($result) $output .= sprintf("<li>Archive table %s created.</li>",$table_archive);
				else $output .= sprintf("<li>Archive table %s NOT created. Error: %s</li>",$table_archive,$db_conn->getLastError());

				$sql = sprintf("TRUNCATE TABLE %s;",$table_archive);
				mysqli_select_db($connection,$database);
				mysqli_real_escape_string($connection,$sql);
				$result = mysqli_query($connection,$sql) or die (mysqli_error($connection));
				if ($result) $output .= sprintf("<li>Archive table %s truncated.</li>",$table_archive);
				else $output .= sprintf("<li>Archive table %s NOT truncated. Error: %s</li>",$table_archive,$db_conn->getLastError());

			}

			if ($table_archive == $brewer_db_table."_".$row_archive['archiveSuffix']) {

				if (!check_update("brewerJudgeMead",$brewer_db_table."_".$row_archive['archiveSuffix'])) {

					$sql = sprintf("ALTER TABLE `%s` ADD `brewerJudgeMead` char(1) NULL DEFAULT NULL;",$brewer_db_table."_".$row_archive['archiveSuffix']);
					mysqli_select_db($connection,$database);
					mysqli_real_escape_string($connection,$sql);
					$result = mysqli_query($connection,$sql) or die (mysqli_error($connection));
					if ($result) $output .= sprintf("<li>The brewerJudgeMead column added to the %s archive table.</li>",$table_archive);
					else $output .= sprintf("<li>The brewerJudgeMead column NOT added to the %s archive table. Error: %s</li>",$table_archive,$db_conn->getLastError());

				}

				if (!check_update("brewerBreweryName",$brewer_db_table."_".$row_archive['archiveSuffix'])) {

					$sql = sprintf("ALTER TABLE `%s` ADD `brewerBreweryName` varchar(255) NULL DEFAULT NULL;",$brewer_db_table."_".$row_archive['archiveSuffix']);
					mysqli_select_db($connection,$database);
						mysqli_real_escape_string($connection,$sql);
						$result = mysqli_query($connection,$sql) or die (mysqli_error($connection));
						if ($result) $output .= sprintf("<li>The brewerBreweryName column added to the %s archive table.</li>",$table_archive);
					else $output .= sprintf("<li>The brewerBreweryName column NOT added to the %s archive table. Error: %s</li>",$table_archive,$db_conn->getLastError());

				}

				if (!check_update("brewerProAm",$brewer_db_table."_".$row_archive['archiveSuffix'])) {

					$sql = sprintf("ALTER TABLE `%s` ADD `brewerProAm` tinyint(1) NULL DEFAULT NULL;",$brewer_db_table."_".$row_archive['archiveSuffix']);
					mysqli_select_db($connection,$database);
					mysqli_real_escape_string($connection,$sql);
					$result = mysqli_query($connection,$sql) or die (mysqli_error($connection));
					if ($result) $output .= sprintf("<li>The brewerProAm column added to the %s archive table.</li>",$table_archive);
					else $output .= sprintf("<li>The brewerProAm column NOT added to the %s archive table. Error: %s</li>",$table_archive,$db_conn->getLastError());

				}

				if (!check_update("brewerDiscount",$brewer_db_table."_".$row_archive['archiveSuffix'])) {

					$sql = sprintf("ALTER TABLE `%s` ADD `brewerDiscount` char(1) NULL DEFAULT NULL;",$brewer_db_table."_".$row_archive['archiveSuffix']);
					mysqli_select_db($connection,$database);
					mysqli_real_escape_string($connection,$sql);
					$result = mysqli_query($connection,$sql) or die (mysqli_error($connection));
					if ($result) $output .= sprintf("<li>The brewerDiscount column added to the %s archive table.</li>",$table_archive);
					else $output .= sprintf("<li>The brewerDiscount column NOT added to the %s archive table. Error: %s</li>",$table_archive,$db_conn->getLastError());

				}

			}

			if ($table_archive == $brewing_db_table."_".$row_archive['archiveSuffix']) {

				if (!check_update("brewJudgingNumber",$brewing_db_table."_".$row_archive['archiveSuffix'])) {

					$sql = sprintf("ALTER TABLE `%s` ADD `brewJudgingNumber` char(1) NULL DEFAULT NULL;",$brewing_db_table."_".$row_archive['archiveSuffix']);
					mysqli_select_db($connection,$database);
					mysqli_real_escape_string($connection,$sql);
					$result = mysqli_query($connection,$sql) or die (mysqli_error($connection));
					if ($result) $output .= sprintf("<li>The brewJudgingNumber column added to the %s archive table.</li>",$table_archive);
					else $output .= sprintf("<li>The brewJudgingNumber column NOT added to the %s archive table. Error: %s</li>",$table_archive,$db_conn->getLastError());

				}

				if (!check_update("brewStaffNotes",$brewing_db_table."_".$row_archive['archiveSuffix'])) {

					$sql = sprintf("ALTER TABLE `%s` ADD `brewStaffNotes` char(1) NULL DEFAULT NULL;",$brewing_db_table."_".$row_archive['archiveSuffix']);
					mysqli_select_db($connection,$database);
					mysqli_real_escape_string($connection,$sql);
					$result = mysqli_query($connection,$sql) or die (mysqli_error($connection));
					if ($result) $output .= sprintf("<li>The brewStaffNotes column added to the %s archive table.</li>",$table_archive);
					else $output .= sprintf("<li>The brewStaffNotes column NOT added to the %s archive table. Error: %s</li>",$table_archive,$db_conn->getLastError());

				}

				if (!check_update("brewAdminNotes",$brewing_db_table."_".$row_archive['archiveSuffix'])) {

					$sql = sprintf("ALTER TABLE `%s` ADD `brewAdminNotes` char(1) NULL DEFAULT NULL;",$brewing_db_table."_".$row_archive['archiveSuffix']);
					mysqli_select_db($connection,$database);
					mysqli_real_escape_string($connection,$sql);
					$result = mysqli_query($connection,$sql) or die (mysqli_error($connection));
					if ($result) $output .= sprintf("<li>The brewAdminNotes column added to the %s archive table.</li>",$table_archive);
					else $output .= sprintf("<li>The brewAdminNotes column NOT added to the %s archive table. Error: %s</li>",$table_archive,$db_conn->getLastError());

				}

				if (!check_update("brewPossAllergens",$brewing_db_table."_".$row_archive['archiveSuffix'])) {

					$sql = sprintf("ALTER TABLE `%s` ADD `brewPossAllergens` char(1) NULL DEFAULT NULL;",$brewing_db_table."_".$row_archive['archiveSuffix']);
					mysqli_select_db($connection,$database);
					mysqli_real_escape_string($connection,$sql);
					$result = mysqli_query($connection,$sql) or die (mysqli_error($connection));
					if ($result) $output .= sprintf("<li>The brewPossAllergens column added to the %s archive table.</li>",$table_archive);
					else $output .= sprintf("<li>The brewPossAllergens column NOT added to the %s archive table. Error: %s</li>",$table_archive,$db_conn->getLastError());

				}

				if (!check_update("brewJudgingNumber",$brewing_db_table."_".$row_archive['archiveSuffix'])) {

					$sql = sprintf("ALTER TABLE `%s` ADD `brewJudgingNumber` char(1) NULL DEFAULT NULL;",$brewing_db_table."_".$row_archive['archiveSuffix']);
					mysqli_select_db($connection,$database);
					mysqli_real_escape_string($connection,$sql);
					$result = mysqli_query($connection,$sql) or die (mysqli_error($connection));
					if ($result) $output .= sprintf("<li>The brewJudgingNumber column added to the %s archive table.</li>",$table_archive);
					else $output .= sprintf("<li>The brewJudgingNumber column NOT added to the %s archive table. Error: %s</li>",$table_archive,$db_conn->getLastError());

				}

			}

		}

		if (check_setup($prefix."judging_scores_".$row_archive['archiveSuffix'],$database)) {
			
			if (get_archive_count($prefix."judging_scores_".$row_archive['archiveSuffix']) > 0) {

				$sql = sprintf("UPDATE `%s` SET archiveDisplayWinners='Y' WHERE archiveSuffix='%s';", $prefix."archive",$row_archive['archiveSuffix']);

				$update_table = $prefix."archive";
				$data = array('archiveDisplayWinners' => 'Y');
				$db_conn->where ('archiveSuffix', $row_archive['archiveSuffix']);
				if ($db_conn->update ($update_table, $data)) $output .= "<li>Winner display for all archives set to Yes.</li>";
				else $output .= "<li>Winner display for all archives NOT set to Yes. Error: ".$db_conn->getLastError()."</li>";

        	}
		}

	} while ($row_archive = mysqli_fetch_assoc($archive));

}

$output .= "<li>Cleaned up archive tables as necessary.</li>";

/**
 * ----------------------------------------------- 2.3.0 ---------------------------------------------
 * Electronic scoresheets added to core.
 * Add a boolean preference to enable or disable them.
 * ---------------------------------------------------------------------------------------------------
 */

if (!check_update("prefsEval", $prefix."preferences")) {


	$sql = sprintf("ALTER TABLE `%s` ADD `prefsEval` TINYINT(1) NULL DEFAULT NULL;",$prefix."preferences");
	mysqli_select_db($connection,$database);
	mysqli_real_escape_string($connection,$sql);
	$result = mysqli_query($connection,$sql) or die (mysqli_error($connection));
	if ($result) $output .= "<li>The prefsEval column was added to the preferences table.</li>";
	else $output .= "<li>The prefsEval column was NOT added to the preferences table. Error: ".$db_conn->getLastError()."</li>";

	$update_table = $prefix."preferences";
	
	if (EVALUATION) $data = array('prefsEval' => 1);
	else $data = array('prefsEval' => 0);
	$db_conn->where ('id', 1);
	if ($db_conn->update ($update_table, $data)) $output .= "<li>The prefsEval column value was set.</li>";
	else $output .= "<li>The prefsEval column value was NOT set. Error: ".$db_conn->getLastError()."</li>";

	if (EVALUATION) $_SESSION['prefsEval'] = 1;
	else $_SESSION['prefsEval'] = 0;
}

$output .= "<li>Added column to enable or disable Electronic Scoresheets functionality.</li>";

/**
 * ----------------------------------------------- 2.3.2 ---------------------------------------------
 * Require more info for Italian Grape Ale.
 * ---------------------------------------------------------------------------------------------------
 */

$update_table = $prefix."styles";
$data = array('brewStyleReqSpec' => 1);
$db_conn->where ('brewStyleGroup', 'PR');
$db_conn->where ('brewStyleNum', 'X3');
$db_conn->where ('brewStyleVersion', 'BJCP2015');
if ($db_conn->update ($update_table, $data)) $output .= "<li>Added more information requirement for Italian Grape Ale (PRX3).</li>";
else $output .= "<li>More information requirement for Italian Grape Ale (PRX3) failed. Error: ".$db_conn->getLastError()."</li>"; 

/**
 * ----------------------------------------------- 2.4.0 ---------------------------------------------
 * Add BJCP 2021 Styles to styles table. 
 * Remove BJCP 2008 as an option, but first check to see if prefs are set to 2008.
 * If so, run conversion script to BJCP 2015.
 * ---------------------------------------------------------------------------------------------------
 */

$bjcp_2021_styles_present = FALSE;
if (check_new_style("28","D","Straight Sour Beer")) $bjcp_2021_styles_present = TRUE;
if (!$bjcp_2021_styles_present) include (UPDATE.'styles_bjcp2021_update.php');
	
/**
 * ----------------------------------------------- 2.4.0 ---------------------------------------------
 * Deprecate BJCP 2008 Styles
 * First, check to see what the current style set is. If it's BJCP 2008, 
 * run 2015 conversion scripts, change preferences to 2015.
 * ---------------------------------------------------------------------------------------------------
 */

if ((!empty($row_current_prefs)) && ($row_current_prefs['prefsStyleSet'] == "BJCP2008")) {
	
	include (LIB.'convert.lib.php');
	include (INCLUDES.'convert/convert_bjcp_2015.inc.php');

	$update_table = $prefix."preferences";
	$data = array('prefsStyleSet' => 'BJCP2015');
	$db_conn->where ('id', 1);
	if ($db_conn->update ($update_table, $data)) $output .= "<li>Corrected British Golden Ale name.</li>";

	$output .= "<li>BJCP 2008 is now deprecated. Converted all entries, judge preferences, etc. to BJCP 2015.</li>";

}

$sql = sprintf("ALTER TABLE `%s` MODIFY COLUMN `brewStyleGroup` VARCHAR(3) AFTER `id`;",$styles_db_table);
mysqli_select_db($connection,$database);
mysqli_real_escape_string($connection,$sql);
$result = mysqli_query($connection,$sql) or die (mysqli_error($connection));
if ($result) $output .= "<li>The brewStyleGroup column was moved after id in the styles table.</li>";
else $output .= "<li>The brewStyleGroup column was NOT moved after id in the styles table. Error: ".$db_conn->getLastError()."</li>";

$sql = sprintf("ALTER TABLE `%s` MODIFY COLUMN `brewStyleVersion` VARCHAR(20) AFTER `brewStyleCategory`;",$styles_db_table);
mysqli_select_db($connection,$database);
mysqli_real_escape_string($connection,$sql);
$result = mysqli_query($connection,$sql) or die (mysqli_error($connection));
if ($result) $output .= "<li>The brewStyleVersion column was moved after brewStyleCategory in the styles table.</li>";
else $output .= "<li>The brewStyleVersion column was NOT moved after brewStyleCategory in the styles table. Error: ".$db_conn->getLastError()."</li>";

// Make sure evaluation table is present in the DB
if (!check_setup($prefix."evaluation",$database)) require_once (EVALS.'install_eval_db.eval.php');

$output .= "<li>Added evaluation DB table - for use with Electronic Scoresheets.</li>";

/**
 * ----------------------------------------------- 2.4.0 ---------------------------------------------
 * Change incorrect English Golden Ale name to British Golden Ale style
 * ---------------------------------------------------------------------------------------------------
 */

$update_table = $prefix."styles";
$data = array('brewStyle' => 'British Golden Ale');
$db_conn->where ('brewStyle', 'English Golden Ale');
$db_conn->where ('brewStyleVersion', 'BJCP2015');
if ($db_conn->update ($update_table, $data)) $output .= "<li>Corrected British Golden Ale name in styles table.</li>";
else $output .= "<li>Correction of British Golden Ale failed in styles table. Error: ".$db_conn->getLastError()."</li>";

$update_table = $prefix."brewing";
$data = array('brewStyle' => 'British Golden Ale');
$db_conn->where ('brewStyle', 'English Golden Ale');
if ($db_conn->update ($update_table, $data)) $output .= "<li>Corrected British Golden Ale name in brewing table.</li>";
else $output .= "<li>Correction of British Golden Ale failed in brewing table. Error: ".$db_conn->getLastError()."</li>";

/**
 * ----------------------------------------------- 2.4.1 ---------------------------------------------
 * Hash security question responses for all users.
 * @see https://github.com/geoffhumphrey/brewcompetitiononlineentry/issues/1208
 * ---------------------------------------------------------------------------------------------------
 */

$query_security_resp = sprintf("SELECT id, userQuestionAnswer FROM `%s`",$prefix."users");
$security_resp = mysqli_query($connection,$query_security_resp);
$row_security_resp = mysqli_fetch_assoc($security_resp);
$totalRows_security_resp = mysqli_num_rows($security_resp);

$total_encrypted = 0;
$total_not_encrypted = 0;

if ($totalRows_security_resp > 0) {

	require(CLASSES.'phpass/PasswordHash.php');

	do {

		/**
		 * Fail safe to prevent double hashing of question
		 * response strings.
		 * Check if string length is less than 60 characters.
		 * If so, it is expected that the response string has 
		 * NOT been hashed.
		 */

		if (strlen($row_security_resp['userQuestionAnswer']) < 60) {

			$hasher_question = new PasswordHash(8, false);
			$hash_question = $hasher_question->HashPassword($row_security_resp['userQuestionAnswer']);

			$update_table = $prefix."users";
			$data = array('userQuestionAnswer' => $hash_question);
			$db_conn->where ('id', $row_security_resp['id']);
			if ($db_conn->update ($update_table, $data)) $total_encrypted += 1;
			else $total_not_encrypted += 1;

		}

	} while($row_security_resp = mysqli_fetch_assoc($security_resp));

}

if ($total_encrypted > 0) $output .= "<li>".$total_encrypted." plain-text security question responses were hashed.</li>";
if ($total_not_encrypted > 0) $output .= "<li>".$total_not_encrypted." plain-text security question responses were NOT hashed.</li>";

/**
 * ----------------------------------------------- 2.4.1 ---------------------------------------------
 * Entry recipe fields deprecated. 
 * Remove admin ability to enable via UI.
 * ---------------------------------------------------------------------------------------------------
 */

$update_table = $prefix."preferences";
$data = array('prefsHideRecipe' => 'Y');
$db_conn->where ('id', 1);
if ($db_conn->update ($update_table, $data)) $output .= "<li>Removed entry recipe fields from UI.</li>";
else $output .= "<li>Removal of entry recipe fields from UI failed. Error: ".$db_conn->getLastError()."</li>";

$_SESSION['prefsHideRecipe'] = "Y";

/**
 * ----------------------------------------------- 2.4.1 ---------------------------------------------
 * Add 2021 and 2022 BA styles. 
 * 2022 BA styles update.
 * ---------------------------------------------------------------------------------------------------
 */

include (UPDATE.'styles_ba_2022_update.php');

/** 
 * --- Future Release ---
 * Finally, after all updates have been implemented, 
 * make sure the character set is utf8mb4 and coallation 
 * is utf8mb4_unicode_ci. 
 * require_once(UPDATE.'char_set_update.php');
 */

/**
 * ----------------------------------------------------------------------------------------------------
 * Change the version number and date.
 * ALWAYS the final script.
 * ----------------------------------------------------------------------------------------------------
 */

$update_table = $prefix."bcoem_sys";
$data = array(
	'version' => $current_version,
	'version_date' => $current_version_date_display,
	'data_check' => $db_conn->now()
);
$db_conn->where ('id', 1);
if ($db_conn->update ($update_table, $data)) $output .= "<li>Updated to latest version complete.</li>";
else $output .= "<li>Update to latest version in system table failed. Error: ".$db_conn->getLastError()."</li>";

// Force reset of some session data
unset($_SESSION['prefs'.$prefix_session]);
unset($_SESSION['contest_info_general'.$prefix_session]);
unset($_SESSION['prefsLang'.$prefix_session]);
unset($_SESSION['prefsLanguageFolder'.$prefix_session]);
?>