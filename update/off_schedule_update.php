<?php

// ----------------------------------------------- 2.1.5 -----------------------------------------------
// Make sure all items are present from last "official" update
// -----------------------------------------------------------------------------------------------------

if (!check_update("prefsLanguage", $prefix."preferences")) {
	$updateSQL = sprintf("ALTER TABLE `%s` ADD `prefsLanguage` VARCHAR(25) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL;",$prefix."preferences");
	mysqli_select_db($connection,$database);
	mysqli_real_escape_string($connection,$updateSQL);
	$result = mysqli_query($connection,$updateSQL);
}

if (!check_update("prefsSpecific", $prefix."preferences")) {
	$updateSQL = sprintf("ALTER TABLE `%s` ADD `prefsSpecific` TINYINT(1) NULL;",$prefix."preferences");
	mysqli_select_db($connection,$database);
	mysqli_real_escape_string($connection,$updateSQL);
	$result = mysqli_query($connection,$updateSQL);
}

if (!check_update("prefsEntryLimitPaid", $prefix."preferences")) {
	$updateSQL = sprintf("ALTER TABLE `%s` ADD `prefsEntryLimitPaid` INT(4) NULL DEFAULT NULL;",$prefix."preferences");
	mysqli_select_db($connection,$database);
	mysqli_real_escape_string($connection,$updateSQL);
	$result = mysqli_query($connection,$updateSQL);
}

if (!check_update("prefsEmailRegConfirm", $prefix."preferences")) {
	$updateSQL = sprintf("ALTER TABLE `%s` ADD `prefsEmailRegConfirm` TINYINT(1) NULL DEFAULT NULL;",$prefix."preferences");
	mysqli_select_db($connection,$database);
	mysqli_real_escape_string($connection,$updateSQL);
	$result = mysqli_query($connection,$updateSQL);
}

if (!check_update("jPrefsCapJudges", $prefix."judging_preferences")) {
	$updateSQL = sprintf("ALTER TABLE `%s` ADD `jPrefsCapJudges` INT(3) NULL DEFAULT NULL;", $prefix."judging_preferences");
	mysqli_select_db($connection,$database);
	mysqli_real_escape_string($connection,$updateSQL);
	$result = mysqli_query($connection,$updateSQL);
}

if (!check_update("jPrefsCapStewards", $prefix."judging_preferences")) {
	$updateSQL = sprintf(" ALTER TABLE `%s` ADD `jPrefsCapStewards` INT(3) NULL DEFAULT NULL;",	$prefix."judging_preferences");
	mysqli_select_db($connection,$database);
	mysqli_real_escape_string($connection,$updateSQL);
	$result = mysqli_query($connection,$updateSQL);
}

if (!check_update("jPrefsBottleNum", $prefix."judging_preferences")) {
	$updateSQL = sprintf(" ALTER TABLE `%s` ADD `jPrefsBottleNum` INT(3) NULL DEFAULT NULL;",$prefix."judging_preferences");
	mysqli_select_db($connection,$database);
	mysqli_real_escape_string($connection,$updateSQL);
	$result = mysqli_query($connection,$updateSQL);
}

if (!check_update("contestCheckInPassword", $prefix."contest_info")) {
	$updateSQL= sprintf("ALTER TABLE  `%s` ADD `contestCheckInPassword` VARCHAR(255) NULL CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL;",$prefix."contest_info");
	mysqli_select_db($connection,$database);
	mysqli_real_escape_string($connection,$updateSQL);
	$result = mysqli_query($connection,$updateSQL);
}

if (!check_update("brewStyleEntry", $prefix."styles")) {
	$updateSQL= sprintf("ALTER TABLE  `%s` ADD `brewStyleEntry` TEXT CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL;",$prefix."styles");
	mysqli_select_db($connection,$database);
	mysqli_real_escape_string($connection,$updateSQL);
	$result = mysqli_query($connection,$updateSQL);
}

if (!check_update("brewStyleComEx", $prefix."styles")) {
	$updateSQL= sprintf("ALTER TABLE  `%s` ADD `brewStyleComEx` TEXT CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL;",$prefix."styles");
	mysqli_select_db($connection,$database);
	mysqli_real_escape_string($connection,$updateSQL);
	$result = mysqli_query($connection,$updateSQL);
}

// ----------------------------------------------- 2.1.8 -----------------------------------------------
// Check for setup_last_step and add
// Also add "example" sub-styles for BJCP2015 21A (Specialty IPA) and 27A (Historical Beer)
// -----------------------------------------------------------------------------------------------------

if (!check_update("setup_last_step", $prefix."system")) {
	// Add setup_last_step column to system table
	$updateSQL = sprintf("ALTER TABLE `%s` ADD `setup_last_step` INT(3) NULL DEFAULT NULL;",$prefix."system");
	mysqli_select_db($connection,$database);
	mysqli_real_escape_string($connection,$updateSQL);
	$result = mysqli_query($connection,$updateSQL);

	$updateSQL = sprintf("UPDATE `%s` SET `setup_last_step` = '8';",$prefix."system");
	mysqli_select_db($connection,$database);
	mysqli_real_escape_string($connection,$updateSQL);
	$result = mysqli_query($connection,$updateSQL);
}

// Make sure styles table is auto increment
$updateSQL = sprintf("ALTER TABLE `%s` CHANGE `id` `id` INT(11) NOT NULL AUTO_INCREMENT;",$prefix."styles");
mysqli_select_db($connection,$database);
mysqli_real_escape_string($connection,$updateSQL);
$result = mysqli_query($connection,$updateSQL);

// Update Historical Beer Entry Info
$updateSQL = sprintf("UPDATE `%s` SET `brewStyle` = 'Historical Beer', `brewStyleTags` = 'standard-strength, pale-color, top-fermented, central-europe, historical-style, wheat-beer-family, sour, spice, amber-color, north-america, historical-style, balanced, smoke, dark-color, british-isles, brown-ale-family, malty, sweet, bottom-fermented', `brewStyleEntry` = 'Catch-all category for other historical beers that have NOT been defined by the BJCP. The entrant must provide a description for the judges of the historical style that is NOT one of the currently defined historical style examples provided by the BJCP. Currently defined examples are: Gose, Piwo Grodziskie, Lichtenhainer, Roggenbier, Sahti, Kentucky Common, Pre-Prohibition Lager, Pre-Prohibition Porter, London Brown Ale. If a beer is entered with just a style name and no description, it is very unlikely that judges will understand how to judge it.' WHERE id = 184;",$prefix."styles");
mysqli_select_db($connection,$database);
mysqli_real_escape_string($connection,$updateSQL);
$result = mysqli_query($connection,$updateSQL);

// Update Specialty IPA Entry Info
$updateSQL = sprintf("UPDATE `%s` SET `brewStyleEntry` = 'Entrant MUST specify a strength (session: 3.0-5.0%%, standard: 5.0-7.5%%, double: 7.5-9.5%%); if no strength is specified, standard will be assumed. This subcategory is a catch-all for entries that DO NOT fit into one of the defined BJCP Specialty IPA types: Black IPA, Brown IPA, White IPA, Rye IPA, Belgian IPA, or Red IPA. Entrant must describe the type of Specialty IPA and its key characteristics in comment form so judges will know what to expect. Entrants may specify specific hop varieties used, if entrants feel that judges may not recognize the varietal characteristics of newer hops. Entrants may specify a combination of defined IPA types (e.g., Black Rye IPA) without providing additional descriptions. Entrants may use this category for a different strength version of an IPA defined by its own BJCP subcategory (e.g., session-strength American or English IPA) - except where an existing BJCP subcategory already exists for that style (e.g., double [American] IPA). If the entry falls into one of the currently defined types (Black IPA, Brown IPA, White IPA, Rye IPA, Belgian IPA, Red IPA), it should be entered into that salient subcategory type.' WHERE id = 163;",$prefix."styles");
mysqli_select_db($connection,$database);
mysqli_real_escape_string($connection,$updateSQL);
$result = mysqli_query($connection,$updateSQL);

// Add new specialty IPA and historical styles to styles table if not present

function check_new_style($style1, $style2, $style3) {

	require(CONFIG.'config.php');

	$query_new_style = sprintf("SELECT COUNT(*) as 'count' FROM %s WHERE brewStyleGroup='%s' AND brewStyleNum = '%s' AND  brewStyle='%s'", $prefix."styles", $style1, $style2, $style3);
	$new_style = mysqli_query($connection,$query_new_style) or die (mysqli_error($connection));
	$row_new_style = mysqli_fetch_assoc($new_style);

	if ($row_new_style['count'] > 0) return TRUE;
	else return FALSE;

}

if (!check_new_style("27","A1","Gose")) {

	$updateSQL = sprintf("INSERT INTO `%s` (`brewStyleNum`, `brewStyle`, `brewStyleCategory`, `brewStyleOG`, `brewStyleOGMax`, `brewStyleFG`, `brewStyleFGMax`, `brewStyleABV`, `brewStyleABVMax`, `brewStyleIBU`, `brewStyleIBUMax`, `brewStyleSRM`, `brewStyleSRMMax`, `brewStyleType`, `brewStyleInfo`, `brewStyleLink`, `brewStyleGroup`, `brewStyleActive`, `brewStyleOwn`, `brewStyleVersion`, `brewStyleReqSpec`, `brewStyleStrength`, `brewStyleCarb`, `brewStyleSweet`, `brewStyleTags`, `brewStyleComEx`, `brewStyleEntry`) VALUES ('A1', 'Gose', 'Historical Beer', '1.036', '1.056', '1.006', '1.010', '4.2', '4.8', '5', '12', '3', '4', '1', 'A highly-carbonated, tart and fruity wheat ale with a restrained coriander and salt character and low bitterness. Very refreshing, with bright flavors and high attenuation.', 'http://bjcp.org/stylecenter.php', '27', 'Y', 'bcoe', 'BJCP2015', 0, 0, 0, 0, 'standard-strength, pale-color, top-fermented, centraleurope, historical-style, wheat-beer-family, sour, spice', 'Anderson Valley Gose, Bayerisch Bahnhof Leipziger Gose, Dollnitzer Ritterguts Gose', NULL);",$prefix."styles");
	mysqli_select_db($connection,$database);
	mysqli_real_escape_string($connection,$updateSQL);
	$result = mysqli_query($connection,$updateSQL);

}

if (!check_new_style("27","A2","Piwo Grodziskie")) {

	$updateSQL = sprintf("INSERT INTO `%s` (`brewStyleNum`, `brewStyle`, `brewStyleCategory`, `brewStyleOG`, `brewStyleOGMax`, `brewStyleFG`, `brewStyleFGMax`, `brewStyleABV`, `brewStyleABVMax`, `brewStyleIBU`, `brewStyleIBUMax`, `brewStyleSRM`, `brewStyleSRMMax`, `brewStyleType`, `brewStyleInfo`, `brewStyleLink`, `brewStyleGroup`, `brewStyleActive`, `brewStyleOwn`, `brewStyleVersion`, `brewStyleReqSpec`, `brewStyleStrength`, `brewStyleCarb`, `brewStyleSweet`, `brewStyleTags`, `brewStyleComEx`, `brewStyleEntry`) VALUES ('A2', 'Piwo Grodziskie', 'Historical Beer', '1.028', '1.032', '1.010', '1.015', '4.5', '6.0', '25', '40', '3', '6', '1', 'A low-gravity, highly-carbonated, light bodied ale combining an oak-smoked flavor with a clean hop bitterness. Highly sessionable.', 'http://bjcp.org/stylecenter.php', '27', 'Y', 'bcoe', 'BJCP2015', 0, 0, 0, 0, 'standard-strength, pale-color, bottom-fermented,lagered, north-america, historical-style, pilsner-family, bitter, hoppy', NULL, NULL);",$prefix."styles");
	mysqli_select_db($connection,$database);
	mysqli_real_escape_string($connection,$updateSQL);
	$result = mysqli_query($connection,$updateSQL);

}

if (!check_new_style("27","A3","Lichtenhainer")) {

	$updateSQL = sprintf("INSERT INTO `%s` (`brewStyleNum`, `brewStyle`, `brewStyleCategory`, `brewStyleOG`, `brewStyleOGMax`, `brewStyleFG`, `brewStyleFGMax`, `brewStyleABV`, `brewStyleABVMax`, `brewStyleIBU`, `brewStyleIBUMax`, `brewStyleSRM`, `brewStyleSRMMax`, `brewStyleType`, `brewStyleInfo`, `brewStyleLink`, `brewStyleGroup`, `brewStyleActive`, `brewStyleOwn`, `brewStyleVersion`, `brewStyleReqSpec`, `brewStyleStrength`, `brewStyleCarb`, `brewStyleSweet`, `brewStyleTags`, `brewStyleComEx`, `brewStyleEntry`) VALUES ('A3', 'Lichtenhainer', 'Historical Beer', '1.032', '1.040', '1.004', '1.008', '3.5', '4.7', '5', '12', '3', '6', '1', 'A sour, smoked, lower-gravity historical German wheat beer. Complex yet refreshing character due to high attenuation and carbonation, along with low bitterness and moderate sourness. ', 'http://bjcp.org/stylecenter.php', '27', 'Y', 'bcoe', 'BJCP2015', 0, 0, 0, 0, 'standard-strength, pale-color, top-fermented, centraleurope, historical-style, wheat-beer-family, sour, smoke', NULL, NULL);",$prefix."styles");
	mysqli_select_db($connection,$database);
	mysqli_real_escape_string($connection,$updateSQL);
	$result = mysqli_query($connection,$updateSQL);

}

if (!check_new_style("27","A4","Roggenbier")) {

	$updateSQL = sprintf("INSERT INTO `%s` (`brewStyleNum`, `brewStyle`, `brewStyleCategory`, `brewStyleOG`, `brewStyleOGMax`, `brewStyleFG`, `brewStyleFGMax`, `brewStyleABV`, `brewStyleABVMax`, `brewStyleIBU`, `brewStyleIBUMax`, `brewStyleSRM`, `brewStyleSRMMax`, `brewStyleType`, `brewStyleInfo`, `brewStyleLink`, `brewStyleGroup`, `brewStyleActive`, `brewStyleOwn`, `brewStyleVersion`, `brewStyleReqSpec`, `brewStyleStrength`, `brewStyleCarb`, `brewStyleSweet`, `brewStyleTags`, `brewStyleComEx`, `brewStyleEntry`) VALUES ('A4', 'Roggenbier', 'Historical Beer', '1.046', '1.056', '1.010', '1.014', '4.5', '6.0', '10', '20', '14', '19', '1', 'A dunkelweizen made with rye rather than wheat, but with a greater body and light finishing hops.', 'http://bjcp.org/stylecenter.php', '27', 'Y', 'bcoe', 'BJCP2015', 0, 0, 0, 0, 'standard-strength, amber-color, top-fermenting, central-europe, historical-style, wheat-beer-family', 'Thurn und Taxis Roggen', NULL);",$prefix."styles");
	mysqli_select_db($connection,$database);
	mysqli_real_escape_string($connection,$updateSQL);
	$result = mysqli_query($connection,$updateSQL);

}

if (!check_new_style("27","A5","Sahti")) {

	$updateSQL = sprintf("INSERT INTO `%s` (`brewStyleNum`, `brewStyle`, `brewStyleCategory`, `brewStyleOG`, `brewStyleOGMax`, `brewStyleFG`, `brewStyleFGMax`, `brewStyleABV`, `brewStyleABVMax`, `brewStyleIBU`, `brewStyleIBUMax`, `brewStyleSRM`, `brewStyleSRMMax`, `brewStyleType`, `brewStyleInfo`, `brewStyleLink`, `brewStyleGroup`, `brewStyleActive`, `brewStyleOwn`, `brewStyleVersion`, `brewStyleReqSpec`, `brewStyleStrength`, `brewStyleCarb`, `brewStyleSweet`, `brewStyleTags`, `brewStyleComEx`, `brewStyleEntry`) VALUES ('A5', 'Sahti', 'Historical Beer', '1.076', '1.120', '1.016', '1.020', '7.0', '11.0', '7', '15', '4', '22', '1', 'A sweet, heavy, strong traditional Finnish beer with a rye, juniper, and juniper berry flavor and a strong banana-clove yeast character.', 'http://bjcp.org/stylecenter.php', '27', 'Y', 'bcoe', 'BJCP2015', 0, 0, 0, 0, 'high-strength, amber-color, top-fermented, centraleurope, historical-style, spice', NULL, NULL);",$prefix."styles");
	mysqli_select_db($connection,$database);
	mysqli_real_escape_string($connection,$updateSQL);
	$result = mysqli_query($connection,$updateSQL);

}

if (!check_new_style("27","A6","Kentucky Common")) {

	$updateSQL = sprintf("INSERT INTO `%s` (`brewStyleNum`, `brewStyle`, `brewStyleCategory`, `brewStyleOG`, `brewStyleOGMax`, `brewStyleFG`, `brewStyleFGMax`, `brewStyleABV`, `brewStyleABVMax`, `brewStyleIBU`, `brewStyleIBUMax`, `brewStyleSRM`, `brewStyleSRMMax`, `brewStyleType`, `brewStyleInfo`, `brewStyleLink`, `brewStyleGroup`, `brewStyleActive`, `brewStyleOwn`, `brewStyleVersion`, `brewStyleReqSpec`, `brewStyleStrength`, `brewStyleCarb`, `brewStyleSweet`, `brewStyleTags`, `brewStyleComEx`, `brewStyleEntry`) VALUES ('A6', 'Kentucky Common', 'Historical Beer', '1.044', '1.055', '1.010', '1.018', '4.0', '5.5', '15', '30', '11', '20', '1', 'A darker-colored, light-flavored, malt-accented beer with a dry finish and interesting character malt flavors. Refreshing due to its high carbonation and mild flavors, and highly  sessionable due to being served very fresh and with restrained alcohol levels.', 'http://bjcp.org/stylecenter.php', '27', 'Y', 'bcoe', 'BJCP2015', 0, 0, 0, 0, 'standard-strength, amber-color, top-fermented, north america,historical-style, balanced', 'Apocalypse Brew Works Ortel''s 1912', NULL);",$prefix."styles");
	mysqli_select_db($connection,$database);
	mysqli_real_escape_string($connection,$updateSQL);
	$result = mysqli_query($connection,$updateSQL);

}

if (!check_new_style("27","A7","Pre-Prohibition Lager")) {

	$updateSQL = sprintf("INSERT INTO `%s` (`brewStyleNum`, `brewStyle`, `brewStyleCategory`, `brewStyleOG`, `brewStyleOGMax`, `brewStyleFG`, `brewStyleFGMax`, `brewStyleABV`, `brewStyleABVMax`, `brewStyleIBU`, `brewStyleIBUMax`, `brewStyleSRM`, `brewStyleSRMMax`, `brewStyleType`, `brewStyleInfo`, `brewStyleLink`, `brewStyleGroup`, `brewStyleActive`, `brewStyleOwn`, `brewStyleVersion`, `brewStyleReqSpec`, `brewStyleStrength`, `brewStyleCarb`, `brewStyleSweet`, `brewStyleTags`, `brewStyleComEx`, `brewStyleEntry`) VALUES ('A7', 'Pre-Prohibition Lager', 'Historical Beer', '1.044', '1.060', '1.010', '1.015', '4.5', '6.0', '25', '40', '3', '6', '1', 'A clean, refreshing, but bitter pale lager, often showcasing a grainy-sweet corn flavor. All malt or rice-based versions have a crisper, more neutral character. The higher bitterness level is the largest differentiator between this style and most modern mass-market pale lagers, but the more robust flavor profile also sets it apart.', 'http://bjcp.org/stylecenter.php', '27', 'Y', 'bcoe', 'BJCP2015', 0, 0, 0, 0, 'standard-strength, pale-color, bottom-fermented, lagered, north-america, historical-style, pilsner-family, bitter, hoppy', 'Anchor California Lager, Coors Batch 19, Little Harpeth Chicken Scratch', NULL);",$prefix."styles");
	mysqli_select_db($connection,$database);
	mysqli_real_escape_string($connection,$updateSQL);
	$result = mysqli_query($connection,$updateSQL);

}

if (!check_new_style("27","A8","Pre-Prohibition Porter")) {
	$updateSQL = sprintf("INSERT INTO `%s` (`brewStyleNum`, `brewStyle`, `brewStyleCategory`, `brewStyleOG`, `brewStyleOGMax`, `brewStyleFG`, `brewStyleFGMax`, `brewStyleABV`, `brewStyleABVMax`, `brewStyleIBU`, `brewStyleIBUMax`, `brewStyleSRM`, `brewStyleSRMMax`, `brewStyleType`, `brewStyleInfo`, `brewStyleLink`, `brewStyleGroup`, `brewStyleActive`, `brewStyleOwn`, `brewStyleVersion`, `brewStyleReqSpec`, `brewStyleStrength`, `brewStyleCarb`, `brewStyleSweet`, `brewStyleTags`, `brewStyleComEx`, `brewStyleEntry`) VALUES ('A8', 'Pre-Prohibition Porter', 'Historical Beer', '1.046', '1.060', '1.010', '1.016', '4.5', '6.0', '20', '30', '18', '30', '1', 'An American adaptation of English Porter using American ingredients, including adjuncts.', 'http://bjcp.org/stylecenter.php', '27', 'Y', 'bcoe', 'BJCP2015', 0, 0, 0, 0, 'standard-strength, dark-color, any-fermentation, northamerica, historical-style, porter-family, malty', 'Stegmaier Porter, Yuengling Porter', NULL);",$prefix."styles");
	mysqli_select_db($connection,$database);
	mysqli_real_escape_string($connection,$updateSQL);
	$result = mysqli_query($connection,$updateSQL);

}

if (!check_new_style("27","A9","London Brown Ale")) {
	$updateSQL = sprintf("INSERT INTO `%s` (`brewStyleNum`, `brewStyle`, `brewStyleCategory`, `brewStyleOG`, `brewStyleOGMax`, `brewStyleFG`, `brewStyleFGMax`, `brewStyleABV`, `brewStyleABVMax`, `brewStyleIBU`, `brewStyleIBUMax`, `brewStyleSRM`, `brewStyleSRMMax`, `brewStyleType`, `brewStyleInfo`, `brewStyleLink`, `brewStyleGroup`, `brewStyleActive`, `brewStyleOwn`, `brewStyleVersion`, `brewStyleReqSpec`, `brewStyleStrength`, `brewStyleCarb`, `brewStyleSweet`, `brewStyleTags`, `brewStyleComEx`, `brewStyleEntry`) VALUES ('A9', 'London Brown Ale', 'Historical Beer', '1.033', '1.038', '1.012', '1.015', '2.8', '3.6', '15', '20', '22', '35', '1', 'A luscious, sweet, malt-oriented dark brown ale, with caramel and toffee malt complexity and a sweet finish.', 'http://bjcp.org/stylecenter.php', '27', 'Y', 'bcoe', 'BJCP2015', 0, 0, 0, 0, 'session-strength, dark-color, top-fermented, britishisles, historical-style, brown-ale-family, malty, sweet', 'Harveys Bloomsbury Brown Ale, Mann''s Brown Ale', 'Entrant MUST specify a strength (session: 3.0-5.0%%, standard: 5.0-7.5%%, double: 7.5-9.5%%).');
	",$prefix."styles");
	mysqli_select_db($connection,$database);
	mysqli_real_escape_string($connection,$updateSQL);
	$result = mysqli_query($connection,$updateSQL);

}

if (!check_new_style("21","B1","Belgian IPA")) {

	$updateSQL = sprintf("INSERT INTO `%s` (`brewStyleNum`, `brewStyle`, `brewStyleCategory`, `brewStyleOG`, `brewStyleOGMax`, `brewStyleFG`, `brewStyleFGMax`, `brewStyleABV`, `brewStyleABVMax`, `brewStyleIBU`, `brewStyleIBUMax`, `brewStyleSRM`, `brewStyleSRMMax`, `brewStyleType`, `brewStyleInfo`, `brewStyleLink`, `brewStyleGroup`, `brewStyleActive`, `brewStyleOwn`, `brewStyleVersion`, `brewStyleReqSpec`, `brewStyleStrength`, `brewStyleCarb`, `brewStyleSweet`, `brewStyleTags`, `brewStyleComEx`, `brewStyleEntry`) VALUES ('B1', 'Belgian IPA', 'Specialty IPA', '1.058', '1.080', '1.008', '1.016', '6.2', '9.5', '50', '100', '5', '15', '1', 'An IPA with the fruitiness and spiciness derived from the use of Belgian yeast. The examples from Belgium tend to be lighter in color and more attenuated, similar to a tripel that has been brewed with more hops. This beer has a more complex flavor profile and may be higher in alcohol than a typical IPA.', 'http://bjcp.org/stylecenter.php', '21', 'Y', 'bcoe', 'BJCP2015', '1', '0', '0', '0', 'high-strength, pale-color, top-fermented, north-america, craft-style, ipa-family, specialty-family, bitter, hoppy', 'Brewery Vivant Triomphe, Houblon Chouffe, Epic Brainless IPA, Green Flash Le Freak, Stone Cali-Belgique, Urthel Hop It', 'Entrant MUST specify a strength (session: 3.0-5.0%%, standard: 5.0-7.5%%, double: 7.5-9.5%%).');",$prefix."styles");
	mysqli_select_db($connection,$database);
	mysqli_real_escape_string($connection,$updateSQL);
	$result = mysqli_query($connection,$updateSQL);

}

if (!check_new_style("21","B2","Black IPA")) {

	$updateSQL = sprintf("INSERT INTO `%s` (`brewStyleNum`, `brewStyle`, `brewStyleCategory`, `brewStyleOG`, `brewStyleOGMax`, `brewStyleFG`, `brewStyleFGMax`, `brewStyleABV`, `brewStyleABVMax`, `brewStyleIBU`, `brewStyleIBUMax`, `brewStyleSRM`, `brewStyleSRMMax`, `brewStyleType`, `brewStyleInfo`, `brewStyleLink`, `brewStyleGroup`, `brewStyleActive`, `brewStyleOwn`, `brewStyleVersion`, `brewStyleReqSpec`, `brewStyleStrength`, `brewStyleCarb`, `brewStyleSweet`, `brewStyleTags`, `brewStyleComEx`, `brewStyleEntry`) VALUES ('B2', 'Black IPA', 'Specialty IPA', '1.050', '1.085', '1.010', '1.018', '5.5', '9.0', '50', '90', '25', '40', '1', 'A beer with the dryness, hop-forward balance, and flavor characteristics of an American IPA, only darker in color – but without strongly roasted or burnt flavors. The flavor of darker malts is gentle and supportive, not a major flavor component. Drinkability is a key characteristic.', 'http://bjcp.org/stylecenter.php', '21', 'Y', 'bcoe', 'BJCP2015', '1', '0', '0', '0', 'high-strength, dark-color, top-fermented, north-america, craft-style, ipa-family, specialty-family, bitter, hoppy', '21st Amendment Back in Black (standard), Deschutes Hop in the Dark CDA (standard), Rogue Dad’s Little Helper (standard), Southern Tier Iniquity (double), Widmer Pitch Black IPA (standard)', 'Entrant MUST specify a strength (session: 3.0-5.0%%, standard: 5.0-7.5%%, double: 7.5-9.5%%).');",$prefix."styles");
	mysqli_select_db($connection,$database);
	mysqli_real_escape_string($connection,$updateSQL);
	$result = mysqli_query($connection,$updateSQL);

}

if (!check_new_style("21","B3","Brown IPA")) {

	$updateSQL = sprintf("INSERT INTO `%s` (`brewStyleNum`, `brewStyle`, `brewStyleCategory`, `brewStyleOG`, `brewStyleOGMax`, `brewStyleFG`, `brewStyleFGMax`, `brewStyleABV`, `brewStyleABVMax`, `brewStyleIBU`, `brewStyleIBUMax`, `brewStyleSRM`, `brewStyleSRMMax`, `brewStyleType`, `brewStyleInfo`, `brewStyleLink`, `brewStyleGroup`, `brewStyleActive`, `brewStyleOwn`, `brewStyleVersion`, `brewStyleReqSpec`, `brewStyleStrength`, `brewStyleCarb`, `brewStyleSweet`, `brewStyleTags`, `brewStyleComEx`, `brewStyleEntry`) VALUES ('B3', 'Brown IPA', 'Specialty IPA', '1.056', '1.070', '1.008', '1.016', '5.5', '7.5', '40', '70', '11', '19', '1', 'Hoppy, bitter, and moderately strong like an American IPA, but with some caramel, chocolate, toffee, and/or dark fruit malt character as in an American Brown Ale. Retaining the dryish finish and lean body that makes IPAs so drinkable, a Brown IPA is a little more flavorful and malty than an American IPA without being sweet or heavy.', 'http://bjcp.org/stylecenter.php', '21', 'Y', 'bcoe', 'BJCP2015', '1', '0', '0', '0', 'high-strength, dark-color, top-fermented, north-america, craft-style, ipa-family, specialty-family, bitter, hoppy', 'Dogfish Head Indian Brown Ale, Grand Teton Bitch Creek, Harpoon Brown IPA, Russian River Janet’s Brown Ale', 'Entrant MUST specify a strength (session: 3.0-5.0%%, standard: 5.0-7.5%%, double: 7.5-9.5%%).');",$prefix."styles");
	mysqli_select_db($connection,$database);
	mysqli_real_escape_string($connection,$updateSQL);
	$result = mysqli_query($connection,$updateSQL);

}

if (!check_new_style("21","B4","Red IPA")) {

	$updateSQL = sprintf("INSERT INTO `%s` (`brewStyleNum`, `brewStyle`, `brewStyleCategory`, `brewStyleOG`, `brewStyleOGMax`, `brewStyleFG`, `brewStyleFGMax`, `brewStyleABV`, `brewStyleABVMax`, `brewStyleIBU`, `brewStyleIBUMax`, `brewStyleSRM`, `brewStyleSRMMax`, `brewStyleType`, `brewStyleInfo`, `brewStyleLink`, `brewStyleGroup`, `brewStyleActive`, `brewStyleOwn`, `brewStyleVersion`, `brewStyleReqSpec`, `brewStyleStrength`, `brewStyleCarb`, `brewStyleSweet`, `brewStyleTags`, `brewStyleComEx`, `brewStyleEntry`) VALUES ('B4', 'Red IPA', 'Specialty IPA', '1.056', '1.070', '1.008', '1.016', '5.5', '7.5', '40', '70', '11', '19', '1', 'Hoppy, bitter, and moderately strong like an American IPA, but with some caramel, toffee, and/or dark fruit malt character. Retaining the dryish finish and lean body that makes IPAs so drinkable, a Red IPA is a little more flavorful and malty than an American IPA without being sweet or heavy.', 'http://bjcp.org/stylecenter.php', '21', 'Y', 'bcoe', 'BJCP2015', '1', '0', '0', '0', 'high-strength, amber-color, top-fermented, north-america, craft-style, ipa-family, specialty-family, bitter, hoppy', 'Green Flash Hop Head Red Double Red IPA (double), Midnight Sun Sockeye Red, Sierra Nevada Flipside Red IPA, Summit Horizon Red IPA, Odell Runoff Red IPA', 'Entrant MUST specify a strength (session: 3.0-5.0%%, standard: 5.0-7.5%%, double: 7.5-9.5%%).');",$prefix."styles");
	mysqli_select_db($connection,$database);
	mysqli_real_escape_string($connection,$updateSQL);
	$result = mysqli_query($connection,$updateSQL);

}

if (!check_new_style("21","B5","Rye IPA")) {

	$updateSQL = sprintf("INSERT INTO `%s` (`brewStyleNum`, `brewStyle`, `brewStyleCategory`, `brewStyleOG`, `brewStyleOGMax`, `brewStyleFG`, `brewStyleFGMax`, `brewStyleABV`, `brewStyleABVMax`, `brewStyleIBU`, `brewStyleIBUMax`, `brewStyleSRM`, `brewStyleSRMMax`, `brewStyleType`, `brewStyleInfo`, `brewStyleLink`, `brewStyleGroup`, `brewStyleActive`, `brewStyleOwn`, `brewStyleVersion`, `brewStyleReqSpec`, `brewStyleStrength`, `brewStyleCarb`, `brewStyleSweet`, `brewStyleTags`, `brewStyleComEx`, `brewStyleEntry`) VALUES ('B5', 'Rye IPA', 'Specialty IPA', '1.056', '1.075', '1.008', '1.014', '5.5', '8.0', '50', '75', '6', '14', '1', 'A decidedly hoppy and bitter, moderately strong American pale ale, showcasing modern American and New World hop varieties and rye malt. The balance is hop-forward, with a clean fermentation profile, dry finish, and clean, supporting malt allowing a creative range of hop character to shine through.', 'http://bjcp.org/stylecenter.php', '21', 'Y', 'bcoe', 'BJCP2015', '1', '0', '0', '0', 'high-strength, amber-color, top-fermented, north-america, craft-style, ipa-family, specialty-family, bitter, hoppy', 'Arcadia Sky High Rye, Bear Republic Hop Rod Rye, Founders Reds Rye, Great Lakes Rye of the Tiger, Sierra Nevada Ruthless Rye', 'Entrant MUST specify a strength (session: 3.0-5.0%%, standard: 5.0-7.5%%, double: 7.5-9.5%%).');",$prefix."styles");
	mysqli_select_db($connection,$database);
	mysqli_real_escape_string($connection,$updateSQL);
	$result = mysqli_query($connection,$updateSQL);

}

if (!check_new_style("21","B6","White IPA")) {

	$updateSQL = sprintf("INSERT INTO `%s` (`brewStyleNum`, `brewStyle`, `brewStyleCategory`, `brewStyleOG`, `brewStyleOGMax`, `brewStyleFG`, `brewStyleFGMax`, `brewStyleABV`, `brewStyleABVMax`, `brewStyleIBU`, `brewStyleIBUMax`, `brewStyleSRM`, `brewStyleSRMMax`, `brewStyleType`, `brewStyleInfo`, `brewStyleLink`, `brewStyleGroup`, `brewStyleActive`, `brewStyleOwn`, `brewStyleVersion`, `brewStyleReqSpec`, `brewStyleStrength`, `brewStyleCarb`, `brewStyleSweet`, `brewStyleTags`, `brewStyleComEx`, `brewStyleEntry`) VALUES ('B6', 'White IPA', 'Specialty IPA', '1.056', '1.065', '1.010', '1.016', '5.5', '7.0', '40', '70', '5', '8', '1', 'A fruity, spicy, refreshing version of an American IPA, but with a lighter color, less body, and featuring either the distinctive yeast and/or spice additions typical of a Belgian witbier.', 'http://bjcp.org/stylecenter.php', '21', 'Y', 'bcoe', 'BJCP2015', '1', '0', '0', '0', 'high-strength, pale-color, top-fermented, north-america, craft-style, ipa-family, specialty-family, bitter, hoppy, spice', 'Blue Point White IPA, Deschutes Chainbreaker IPA, Harpoon The Long Thaw, New Belgium Accumulation', 'Entrant MUST specify a strength (session: 3.0-5.0%%, standard: 5.0-7.5%%, double: 7.5-9.5%%).');",$prefix."styles");
	mysqli_select_db($connection,$database);
	mysqli_real_escape_string($connection,$updateSQL);
	$result = mysqli_query($connection,$updateSQL);

}

// ----------------------------------------------- 2.1.9 -----------------------------------------------
// Correct the problem with new BJCP "example" substyles not being saved correctly
// -----------------------------------------------------------------------------------------------------

if (check_update("brewerNickname", $prefix."brewer")) {
	// Change brewerNickname to brewerStaff for ability for users to identify that they are interested in being a staff member
	$updateSQL = sprintf("ALTER TABLE `%s` CHANGE `brewerNickname` `brewerStaff` CHAR(1) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL;", $prefix."brewer");
	mysqli_select_db($connection,$database);
	mysqli_real_escape_string($connection,$updateSQL);
	$result = mysqli_query($connection,$updateSQL);
}

if ((!check_update("brewerNickname", $prefix."brewer")) && (!check_update("brewerStaff", $prefix."brewer"))) {
	// Change brewerNickname to brewerStaff for ability for users to identify that they are interested in being a staff member
	$updateSQL = sprintf("ALTER TABLE `%s` ADD `brewerStaff` CHAR(1) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL;", $prefix."brewer");
	mysqli_select_db($connection,$database);
	mysqli_real_escape_string($connection,$updateSQL);
	$result = mysqli_query($connection,$updateSQL);
}

// Change brewing table to be able to save new "example" substyles
$updateSQL = sprintf("ALTER TABLE `%s` CHANGE `brewCategory` `brewCategory` VARCHAR(4) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL, CHANGE `brewCategorySort` `brewCategorySort` VARCHAR(4) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL, CHANGE `brewSubCategory` `brewSubCategory` VARCHAR(4) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL;", $prefix."brewing");
mysqli_select_db($connection,$database);
mysqli_real_escape_string($connection,$updateSQL);
$result = mysqli_query($connection,$updateSQL);

if (!check_update("assignRoles", $prefix."judging_assignments")) {
	$updateSQL = sprintf("ALTER TABLE `%s` ADD `assignRoles` VARCHAR(25) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL;",$prefix."judging_assignments");
	mysqli_select_db($connection,$database);
	mysqli_real_escape_string($connection,$updateSQL);
	$result = mysqli_query($connection,$updateSQL);
}

// ----------------------------------------------- 2.1.10 ----------------------------------------------
// Add db columns to store Pro Edition data such as Brewery Name and TTB Number
// Add db columns to allow for PayPal IPN use
// Add db columns for best brewer preferences
// Alter prefsStyleSet to accommodate BA style data and BreweryDB key
// Update archive db tables to accommodate Pro Edition and BA Styles
// -----------------------------------------------------------------------------------------------------


if (!check_update("brewerBreweryName", $prefix."brewer")) {
	$updateSQL = sprintf("ALTER TABLE `%s`
		ADD `brewerBreweryName` VARCHAR(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
		ADD `brewerBreweryTTB` VARCHAR(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL;",
		$prefix."brewer");
	mysqli_select_db($connection,$database);
	mysqli_real_escape_string($connection,$updateSQL);
	$result = mysqli_query($connection,$updateSQL);
}

if (!check_update("prefsShowBestBrewer", $prefix."preferences")) {
	$updateSQL = sprintf("ALTER TABLE `%s`
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
	mysqli_real_escape_string($connection,$updateSQL);
	$result = mysqli_query($connection,$updateSQL);
}

if (!check_update("prefsPaypalIPN", $prefix."preferences")) {
	$updateSQL = sprintf("ALTER TABLE `%s` ADD `prefsPaypalIPN` TINYINT(1) NULL DEFAULT NULL AFTER `prefsPaypalAccount`;",$prefix."preferences");
	mysqli_select_db($connection,$database);
	mysqli_real_escape_string($connection,$updateSQL);
	$result = mysqli_query($connection,$updateSQL);

	$updateSQL = sprintf("UPDATE `%s` SET prefsPayPalIPN='0' WHERE id='1';",$prefix."preferences");
	mysqli_select_db($connection,$database);
	mysqli_real_escape_string($connection,$updateSQL);
	$result = mysqli_query($connection,$updateSQL);
}

if (check_update("prefsCompOrg", $prefix."preferences")) {
	// Change unused prefsCompOrg to new prefsProEdition pref
	// If there change it
	$updateSQL = sprintf("ALTER TABLE `%s` CHANGE `prefsCompOrg` `prefsProEdition` TINYINT(1) NULL DEFAULT NULL;",$prefix."preferences");
	mysqli_select_db($connection,$database);
	mysqli_real_escape_string($connection,$updateSQL);
	$result = mysqli_query($connection,$updateSQL);

}

$updateSQL = sprintf("ALTER TABLE `%s` SET prefsProEdition='0' WHERE id='1';",$prefix."preferences");
mysqli_select_db($connection,$database);
mysqli_real_escape_string($connection,$updateSQL);
$result = mysqli_query($connection,$updateSQL);

if ((!check_update("prefsCompOrg", $prefix."preferences")) && (!check_update("prefsProEdition", $prefix."preferences"))) {
	// If not there add it
	$updateSQL = sprintf("ALTER TABLE `%s` ADD `prefsProEdition` TINYINT(1) NULL DEFAULT NULL AFTER `prefsPaypalAccount`;",$prefix."preferences");
	mysqli_select_db($connection,$database);
	mysqli_real_escape_string($connection,$updateSQL);
	$result = mysqli_query($connection,$updateSQL);

}

// Set the new pref to false
$updateSQL = sprintf("UPDATE `%s` SET prefsProEdition='0' WHERE id='1';",$prefix."preferences");
mysqli_select_db($connection,$database);
mysqli_real_escape_string($connection,$updateSQL);
$result = mysqli_query($connection,$updateSQL);

// Alter the prefsStyleSet to TEXT to accommodate storing a BreweryDB API Key and chosen styles
$updateSQL = sprintf("ALTER TABLE `%s` CHANGE `prefsStyleSet` `prefsStyleSet` TEXT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL;",$prefix."preferences");
mysqli_select_db($connection,$database);
mysqli_real_escape_string($connection,$updateSQL);
$result = mysqli_query($connection,$updateSQL);

if (check_update("archiveUserTableName", $prefix."archive")) {
	// Change unused archiveUserTableName to new archiveProEdition
	$updateSQL = sprintf("ALTER TABLE `%s` CHANGE `archiveUserTableName` `archiveProEdition` TINYINT(1) NULL DEFAULT NULL;",$prefix."archive");
	mysqli_select_db($connection,$database);
	mysqli_real_escape_string($connection,$updateSQL);
	$result = mysqli_query($connection,$updateSQL);
}

if ((!check_update("archiveUserTableName", $prefix."archive")) && (!check_update("archiveProEdition", $prefix."archive"))) {
	$updateSQL = sprintf("ALTER TABLE `%s` ADD `archiveProEdition` TINYINT(1) NULL DEFAULT NULL;",$prefix."archive");
	mysqli_select_db($connection,$database);
	mysqli_real_escape_string($connection,$updateSQL);
	$result = mysqli_query($connection,$updateSQL);
}

if (check_update("archiveBrewerTableName", $prefix."archive")) {
	// Change unused archiveBrewerTableName to new archiveStyleSet
	$updateSQL = sprintf("ALTER TABLE `%s` CHANGE `archiveBrewerTableName` `archiveStyleSet` VARCHAR(25) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL;",$prefix."archive");
	mysqli_select_db($connection,$database);
	mysqli_real_escape_string($connection,$updateSQL);
	$result = mysqli_query($connection,$updateSQL);
}

if ((!check_update("archiveBrewerTableName", $prefix."archive")) && (!check_update("archiveStyleSet", $prefix."archive"))) {
	// Change unused archiveBrewerTableName to new archiveStyleSet
	$updateSQL = sprintf("ALTER TABLE `%s` ADD `archiveStyleSet` VARCHAR(25) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL;",$prefix."archive");
	mysqli_select_db($connection,$database);
	mysqli_real_escape_string($connection,$updateSQL);
	$result = mysqli_query($connection,$updateSQL);
}

if (HOSTED) {
	$updateSQL = sprintf("TRUNCATE TABLE `%s`;",$prefix."archive");
	mysqli_select_db($connection,$database);
	mysqli_real_escape_string($connection,$updateSQL);
	$result = mysqli_query($connection,$updateSQL);
}

else {

	// Update all archive "brewer" and "brewing" tables (only for non-hosted installations)
	$query_archive = sprintf("SELECT archiveSuffix FROM %s",$prefix."archive");
	$archive = mysqli_query($connection,$query_archive);
	$row_archive = mysqli_fetch_assoc($archive);
	$totalRows_archive = mysqli_num_rows($archive);

	if ($totalRows_archive > 0) {

		do {

			if (!check_update("brewerBreweryName", $prefix."brewer_".$row_archive['archiveSuffix'])) {
				$updateSQL = sprintf("ALTER TABLE `%s` ADD `brewerBreweryName` VARCHAR(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL, ADD `brewerBreweryTTB` VARCHAR(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL;", $prefix."brewer_".$row_archive['archiveSuffix']);
				mysqli_select_db($connection,$database);
				mysqli_real_escape_string($connection,$updateSQL);
				$result = mysqli_query($connection,$updateSQL);
			}

			if (check_update("brewWinnerSubCat", $prefix."brewing_".$row_archive['archiveSuffix'])) {
				$updateSQL = sprintf("ALTER TABLE `%s` CHANGE `brewWinnerSubCat` `brewInfoOptional` TEXT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL;",$prefix."brewing_".$row_archive['archiveSuffix']);
				mysqli_select_db($connection,$database);
				mysqli_real_escape_string($connection,$updateSQL);
				$result = mysqli_query($connection,$updateSQL);
			}

			if ((!check_update("brewWinnerSubCat", $prefix."brewing_".$row_archive['archiveSuffix'])) && (!check_update("brewInfoOptional", $prefix."brewing_".$row_archive['archiveSuffix']))) {
				$updateSQL = sprintf("ALTER TABLE `%s` ADD `brewInfoOptional` TEXT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL;",$prefix."brewing_".$row_archive['archiveSuffix']);
				mysqli_select_db($connection,$database);
				mysqli_real_escape_string($connection,$updateSQL);
				$result = mysqli_query($connection,$updateSQL);
			}

		} while ($row_archive = mysqli_fetch_assoc($archive));

	}

}

if (check_update("brewWinnerSubCat", $prefix."brewing")) {

	// Change unused brewWinnerSubCat to new brewInfoOptional pref
	$updateSQL = sprintf("ALTER TABLE `%s` CHANGE `brewWinnerSubCat` `brewInfoOptional` TEXT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL;",$prefix."brewing");
	mysqli_select_db($connection,$database);
	mysqli_real_escape_string($connection,$updateSQL);
	$result = mysqli_query($connection,$updateSQL);

}

if ((!check_update("brewWinnerSubCat", $prefix."brewing")) && (!check_update("brewInfoOptional", $prefix."brewing")))  {

	$updateSQL = sprintf("ALTER TABLE `%s` ADD `brewInfoOptional` TEXT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL;",$prefix."brewing");
	mysqli_select_db($connection,$database);
	mysqli_real_escape_string($connection,$updateSQL);
	$result = mysqli_query($connection,$updateSQL);

}

if (!check_update("userToken", $prefix."users")) {

	$updateSQL = sprintf("ALTER TABLE `%s` ADD `userToken` VARCHAR(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL, ADD `userTokenTime` VARCHAR(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL, ADD `userFailedLogins` INT(11) NULL DEFAULT NULL, ADD `userFailedLoginTime` VARCHAR(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL;",$prefix."users");
	mysqli_select_db($connection,$database);
	mysqli_real_escape_string($connection,$updateSQL);
	$result = mysqli_query($connection,$updateSQL);

	// Set failed logins to 0 for all users
	$updateSQL = sprintf("UPDATE `%s` SET userFailedLogins='0';",$prefix."users");
	mysqli_select_db($connection,$database);
	mysqli_real_escape_string($connection,$updateSQL);
	$result = mysqli_query($connection,$updateSQL);

}

// Alter entry fee columns to allow for decimals
$updateSQL = sprintf("ALTER TABLE `%s` CHANGE `contestEntryFee` `contestEntryFee` FLOAT(6,2) NULL DEFAULT NULL, CHANGE `contestEntryFee2` `contestEntryFee2` FLOAT(6,2) NULL DEFAULT NULL, CHANGE `contestEntryFeePasswordNum` `contestEntryFeePasswordNum` FLOAT(6,2) NULL DEFAULT NULL;",$prefix."contest_info");
mysqli_select_db($connection,$database);
mysqli_real_escape_string($connection,$updateSQL);
$result = mysqli_query($connection,$updateSQL);

// Alter BOS place column to allow for variable characters
$updateSQL = sprintf("ALTER TABLE `%s` CHANGE `scorePlace` `scorePlace` VARCHAR(3) NULL DEFAULT NULL;",$prefix."judging_scores_bos");
mysqli_select_db($connection,$database);
mysqli_real_escape_string($connection,$updateSQL);
$result = mysqli_query($connection,$updateSQL);

// Alter winner delay to accept a timestamp instead of round number hour delay
$updateSQL = sprintf("ALTER TABLE `%s` CHANGE `prefsWinnerDelay` `prefsWinnerDelay` VARCHAR(15) NULL DEFAULT NULL COMMENT 'Unix timestamp to display winners';",$prefix."preferences");
mysqli_select_db($connection,$database);
mysqli_real_escape_string($connection,$updateSQL);
$result = mysqli_query($connection,$updateSQL);

// Get the delay value from DB
$query_delay = sprintf("SELECT prefsWinnerDelay FROM %s WHERE id='1'", $prefix."preferences");
$delay = mysqli_query($connection,$query_delay) or die (mysqli_error($connection));
$row_delay = mysqli_fetch_assoc($delay);

// Check if the length is less than 10 (Unix timestamp is 10)
// If so, convert to timestamp
if ((strlen($row_delay['prefsWinnerDelay'])) < 10) {

	$query_check = sprintf("SELECT judgingDate FROM %s ORDER BY judgingDate DESC LIMIT 1", $prefix."judging_locations");
	$check = mysqli_query($connection,$query_check) or die (mysqli_error($connection));
	$row_check = mysqli_fetch_assoc($check);

	// Add the hour delay to the latest judging date
	$new_timestamp = ($row_delay['prefsWinnerDelay'] * 3600) + $row_check['judgingDate'];

	$updateSQL = sprintf("UPDATE `%s` SET prefsWinnerDelay='%s';",$prefix."preferences",$new_timestamp);
	mysqli_select_db($connection,$database);
	mysqli_real_escape_string($connection,$updateSQL);
	$result = mysqli_query($connection,$updateSQL);

	// Update the session variable
	$_SESSION['prefsWinnerDelay'] = $new_timestamp;

}

// Instantiate HTMLPurifier
require (LIB.'process.lib.php');
require (CLASSES.'htmlpurifier/HTMLPurifier.standalone.php');
$config_html_purifier = HTMLPurifier_Config::createDefault();
$purifier = new HTMLPurifier($config_html_purifier);

// Standardize the proper names of entrants and locations
$query_names = sprintf("SELECT * FROM %s",$brewer_db_table);
$names = mysqli_query($connection,$query_names) or die (mysqli_error($connection));
$row_names = mysqli_fetch_assoc($names);
$totalRows_names = mysqli_num_rows($names);

if ($totalRows_names > 0) {

	do {

		$first_name = standardize_name($purifier->purify($row_names['brewerFirstName']));
		$last_name = standardize_name($purifier->purify($row_names['brewerLastName']));
		$address = standardize_name($purifier->purify($row_names['brewerAddress']));
		$city = standardize_name($purifier->purify($row_names['brewerCity']));
		$state = $purifier->purify($row_names['brewerState']);
		if (strlen($state) > 2) $state = standardize_name($state);
		else $state = strtoupper($state);

		if (!empty($row_names['brewerJudgeID'])) {
			$brewerJudgeID = sterilize($row_names['brewerJudgeID']);
			$brewerJudgeID = strtoupper($brewerJudgeID);
		}
		else $brewerJudgeID = "";

		if (!empty($row_names['brewerClubs'])) {
			$brewerClubs = standardize_name($purifier->purify($row_names['brewerClubs']));
		}
		else $brewerClubs = "";

		if (!empty($row_names['brewerJudgeNotes'])) $brewerJudgeNotes = $purifier->purify($row_names['brewerJudgeNotes']);
		else $brewerJudgeNotes = "";

		$updateSQL = sprintf("UPDATE $brewer_db_table SET
			brewerFirstName=%s,
			brewerLastName=%s,
			brewerAddress=%s,
			brewerCity=%s,
			brewerState=%s,
			brewerClubs=%s,
			brewerEmail=%s,
			brewerJudgeID=%s,
			brewerJudgeNotes=%s
			",
							   GetSQLValueString($first_name, "text"),
							   GetSQLValueString($last_name, "text"),
							   GetSQLValueString($address, "text"),
							   GetSQLValueString($city, "text"),
							   GetSQLValueString($state, "text"),
							   GetSQLValueString($brewerClubs, "text"),
							   GetSQLValueString(filter_var($row_names['brewerEmail'],FILTER_SANITIZE_EMAIL), "text"),
							   GetSQLValueString($brewerJudgeID, "text"),
							   GetSQLValueString($brewerJudgeNotes, "text")
							   );
		$updateSQL .= sprintf(" WHERE id=%s",GetSQLValueString($row_names['id'], "int"));
		mysqli_real_escape_string($connection,$updateSQL);
		$result = mysqli_query($connection,$updateSQL) or die (mysqli_error($connection));

	} while ($row_names = mysqli_fetch_assoc($names));

}

// Standardize the names of entries
$query_entry_names = sprintf("SELECT id,brewName,brewInfo,brewComments,brewCoBrewer FROM %s",$brewing_db_table);
$entry_names = mysqli_query($connection,$query_entry_names) or die (mysqli_error($connection));
$row_entry_names = mysqli_fetch_assoc($entry_names);
$totalRows_entry_names = mysqli_num_rows($entry_names);

if ($totalRows_entry_names > 0) {

	do {

		if (isset($row_entry_names['brewComments'])) $brewComments = $purifier->purify($row_entry_names['brewComments']);
		else $brewComments = "";

		if (isset($row_entry_names['brewCoBrewer'])) $brewCoBrewer = standardize_name($purifier->purify($row_entry_names['brewCoBrewer']));
		else $brewCoBrewer = "";

		if (isset($row_entry_names['brewInfo'])) $brewInfo = $purifier->purify($row_entry_names['brewInfo']);
		else $brewInfo = "";

		$brewName = standardize_name($purifier->purify($row_entry_names['brewName']));

		$updateSQL = sprintf("UPDATE $brewing_db_table SET
				brewComments=%s,
				brewCoBrewer=%s,
				brewInfo=%s,
				brewName=%s
				",
								   GetSQLValueString($brewComments, "text"),
								   GetSQLValueString($brewCoBrewer, "text"),
								   GetSQLValueString($brewInfo, "text"),
								   GetSQLValueString($brewName, "text")
								   );

		$updateSQL .= sprintf(" WHERE id=%s",GetSQLValueString($row_entry_names['id'], "int"));
		mysqli_real_escape_string($connection,$updateSQL);
		$result = mysqli_query($connection,$updateSQL) or die (mysqli_error($connection));

	} while ($row_entry_names = mysqli_fetch_assoc($entry_names));

}

// Change the version and version date in the system table
$updateSQL = sprintf("UPDATE %s SET version='%s', version_date='%s' WHERE id=1", $prefix."system", $current_version, $current_version_date_display);
mysqli_real_escape_string($connection,$updateSQL);
$result = mysqli_query($connection,$updateSQL) or die (mysqli_error($connection));

?>