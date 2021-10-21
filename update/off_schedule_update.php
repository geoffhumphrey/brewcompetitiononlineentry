<?php

if (!isset($output)) $output = "";

/**
 * ----------------------------------------------- 2.1.5 -----------------------------------------------
 * Make sure all items are present from last "official" update
 * -----------------------------------------------------------------------------------------------------
 */

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

/**
 * ----------------------------------------------- 2.1.8 -----------------------------------------------
 * Check for setup_last_step and add
 * Also add "example" sub-styles for BJCP2015 21A (Specialty IPA) and 27A (Historical Beer)
 * -----------------------------------------------------------------------------------------------------
 */

if (!check_update("setup_last_step", $prefix."bcoem_sys")) {
	// Add setup_last_step column to system table
	$updateSQL = sprintf("ALTER TABLE `%s` ADD `setup_last_step` INT(3) NULL DEFAULT NULL;",$prefix."bcoem_sys");
	mysqli_select_db($connection,$database);
	mysqli_real_escape_string($connection,$updateSQL);
	$result = mysqli_query($connection,$updateSQL);

	$updateSQL = sprintf("UPDATE `%s` SET `setup_last_step` = '8';",$prefix."bcoem_sys");
	mysqli_select_db($connection,$database);
	mysqli_real_escape_string($connection,$updateSQL);
	$result = mysqli_query($connection,$updateSQL);
}

$output .= "<li>System table updated.</li>";

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

$output .= "<li>Styles table updated with current sub-style information.</li>";

/**
 * ----------------------------------------------- 2.1.9 -----------------------------------------------
 * Correct the problem with new BJCP "example" substyles not being saved correctly
 * -----------------------------------------------------------------------------------------------------
 */

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

$output .= "<li>Entry table updated to correct style saving bug.</li>";


if (!check_update("assignRoles", $prefix."judging_assignments")) {
	$updateSQL = sprintf("ALTER TABLE `%s` ADD `assignRoles` VARCHAR(25) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL;",$prefix."judging_assignments");
	mysqli_select_db($connection,$database);
	mysqli_real_escape_string($connection,$updateSQL);
	$result = mysqli_query($connection,$updateSQL);
}

$output .= "<li>Judging Assignments table updated.</li>";


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

if (!check_update("prefsCAPTCHA", $prefix."preferences")) {

	$updateSQL = sprintf("ALTER TABLE `%s` ADD `prefsCAPTCHA` tinyint(1) COLLATE utf8mb4_unicode_ci DEFAULT NULL;",$prefix."preferences");
	mysqli_select_db($connection,$database);
	mysqli_real_escape_string($connection,$updateSQL);
	$result = mysqli_query($connection,$updateSQL);

	$updateSQL = sprintf("UPDATE `%s` SET prefsCAPTCHA='0';",$prefix."preferences");
	mysqli_select_db($connection,$database);
	mysqli_real_escape_string($connection,$updateSQL);
	$result = mysqli_query($connection,$updateSQL);

}

if (!check_update("prefsPaypalIPN", $prefix."preferences")) {
	$updateSQL = sprintf("ALTER TABLE `%s` ADD `prefsPaypalIPN` TINYINT(1) NULL DEFAULT NULL AFTER `prefsPaypalAccount`;",$prefix."preferences");
	mysqli_select_db($connection,$database);
	mysqli_real_escape_string($connection,$updateSQL);
	$result = mysqli_query($connection,$updateSQL);

	$updateSQL = sprintf("UPDATE `%s` SET prefsPayPalIPN='0';",$prefix."preferences");
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

if ((!check_update("prefsCompOrg", $prefix."preferences")) && (!check_update("prefsProEdition", $prefix."preferences"))) {
	// If not there add it
	$updateSQL = sprintf("ALTER TABLE `%s` ADD `prefsProEdition` TINYINT(1) NULL DEFAULT NULL AFTER `prefsPaypalAccount`;",$prefix."preferences");
	mysqli_select_db($connection,$database);
	mysqli_real_escape_string($connection,$updateSQL);
	$result = mysqli_query($connection,$updateSQL);

	// Set the new pref to false
	$updateSQL = sprintf("UPDATE `%s` SET prefsProEdition='0';",$prefix."preferences");
	mysqli_select_db($connection,$database);
	mysqli_real_escape_string($connection,$updateSQL);
	$result = mysqli_query($connection,$updateSQL);

}

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

			if ((check_setup($prefix."brewer_".$row_archive['archiveSuffix'],$database)) && (!check_update("brewerBreweryName", $prefix."brewer_".$row_archive['archiveSuffix']))) {
				$updateSQL = sprintf("ALTER TABLE `%s` ADD `brewerBreweryName` VARCHAR(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL, ADD `brewerBreweryTTB` VARCHAR(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL;", $prefix."brewer_".$row_archive['archiveSuffix']);
				mysqli_select_db($connection,$database);
				mysqli_real_escape_string($connection,$updateSQL);
				$result = mysqli_query($connection,$updateSQL);
			}

			if (check_setup($prefix."brewing_".$row_archive['archiveSuffix'],$database)) {

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

include (CLASSES.'capitalize_name/parser.php');
$name_parser = new FullNameParser();

// Standardize the proper names of entrants and locations
$query_names = sprintf("SELECT * FROM %s",$prefix."brewer");
$names = mysqli_query($connection,$query_names) or die (mysqli_error($connection));
$row_names = mysqli_fetch_assoc($names);
$totalRows_names = mysqli_num_rows($names);

if ($totalRows_names > 0) {

	do {

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

		if ((isset($_SESSION['prefsLanguageFolder'])) && (in_array($_SESSION['prefsLanguageFolder'], $name_check_langs))) {

		    $name_to_parse = $fname." ".$lname;
		    $parsed_name = $name_parser->parse_name($name_to_parse);
		    
		    $first_name = "";
		    if (!empty($parsed_name['salutation'])) $first_name .= $parsed_name['salutation']." ";
		    $first_name .= $parsed_name['fname'];
		    if (!empty($parsed_name['initials'])) $first_name .= " ".$parsed_name['initials'];
		    
		    $last_name = "";
		    if ((isset($_SESSION['prefsLanguageFolder'])) && (in_array($_SESSION['prefsLanguageFolder'], $last_name_exception_langs))) $last_name .= standardize_name($parsed_name['lname']);
		    else $last_name .= $parsed_name['lname']; 
		    if (!empty($parsed_name['suffix'])) $last_name .= " ".$parsed_name['suffix'];
		}

		else {
		    $first_name = $fname;
		    $last_name = $lname;
		    $first_name = filter_var($first_name,FILTER_SANITIZE_STRING);
		    $last_name = filter_var($last_name,FILTER_SANITIZE_STRING);
		}

		$address = standardize_name($purifier->purify($row_names['brewerAddress']));
		$address = filter_var($address,FILTER_SANITIZE_STRING);
		$city = standardize_name($purifier->purify($row_names['brewerCity']));
		$city = filter_var($city,FILTER_SANITIZE_STRING);
		$state = $purifier->purify($row_names['brewerState']);
		if (strlen($state) > 2) $state = standardize_name($state);
		else $state = strtoupper($state);
		$state = filter_var($state,FILTER_SANITIZE_STRING);
		
		if (!empty($row_names['brewerJudgeID'])) {
			$brewerJudgeID = sterilize($row_names['brewerJudgeID']);
			$brewerJudgeID = filter_var($brewerJudgeID,FILTER_SANITIZE_STRING);
			$brewerJudgeID = strtoupper($brewerJudgeID);
		}
		else $brewerJudgeID = "";

		if (!empty($row_names['brewerClubs'])) {
			$brewerClubs = $purifier->purify($row_names['brewerClubs']);
		}
		else $brewerClubs = "";

		if (!empty($row_names['brewerJudgeNotes'])) {
			$brewerJudgeNotes = $purifier->purify($row_names['brewerJudgeNotes']);
			$brewerJudgeNotes = filter_var($brewerJudgeNotes,FILTER_SANITIZE_STRING);
		}
		else $brewerJudgeNotes = "";

		$updateSQL = sprintf("UPDATE %s SET
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
							$prefix."brewer",
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
$query_entry_names = sprintf("SELECT id,brewName,brewInfo,brewComments,brewCoBrewer,brewJudgingNumber FROM %s",$prefix."brewing");
$entry_names = mysqli_query($connection,$query_entry_names) or die (mysqli_error($connection));
$row_entry_names = mysqli_fetch_assoc($entry_names);
$totalRows_entry_names = mysqli_num_rows($entry_names);

if ($totalRows_entry_names > 0) {

	do {

		if (isset($row_entry_names['brewComments'])) $brewComments = $purifier->purify($row_entry_names['brewComments']);
		else $brewComments = "";

		if (isset($row_entry_names['brewCoBrewer'])) {

			$brewCoBrewer = $purifier->purify($row_entry_names['brewCoBrewer']);
			
			if ((isset($_SESSION['prefsLanguageFolder'])) && (in_array($_SESSION['prefsLanguageFolder'], $name_check_langs))) {
		    	
		    	$parsed_name = $name_parser->parse_name($brewCoBrewer);

		    	$first_name = "";
			    if (!empty($parsed_name['salutation'])) $first_name .= $parsed_name['salutation']." ";
			    $first_name .= $parsed_name['fname'];
			    if (!empty($parsed_name['initials'])) $first_name .= " ".$parsed_name['initials'];
			    
			    $last_name = "";
			    if ((isset($_SESSION['prefsLanguageFolder'])) && (in_array($_SESSION['prefsLanguageFolder'], $last_name_exception_langs))) $last_name .= standardize_name($parsed_name['lname']);
			    else $last_name .= $parsed_name['lname']; 
			    if (!empty($parsed_name['suffix'])) $last_name .= " ".$parsed_name['suffix']; 

			    $brewCoBrewer = $first_name." ".$last_name;

			}

			$brewCoBrewer = filter_var($brewCoBrewer,FILTER_SANITIZE_STRING);

		}
		
		else $brewCoBrewer = "";

		if (isset($row_entry_names['brewInfo'])) {
			$brewInfo = $purifier->purify($row_entry_names['brewInfo']);
			$brewInfo = filter_var($brewInfo,FILTER_SANITIZE_STRING);
		}
		else $brewInfo = "";

		$brewName = standardize_name($purifier->purify($row_entry_names['brewName']));
		$brewName = filter_var($brewName,FILTER_SANITIZE_STRING);

		$updateSQL = sprintf("UPDATE %s SET
				brewJudgingNumber=%s,
				brewComments=%s,
				brewCoBrewer=%s,
				brewInfo=%s,
				brewName=%s
				",
							$prefix."brewing",
							GetSQLValueString(strtolower($row_entry_names['brewJudgingNumber']), "text"),
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

$output .= "<li>Added db columns in Brewer table to store Pro Edition data such as Brewery Name and TTB Number.</li>";
$output .= "<li>Added db columns in Preferences table to allow for PayPal IPN use.</li>";
$output .= "<li>Added db columns in Preferences table to accommodate best brewer preferences.</li>";
$output .= "<li>Altered prefsStyleSet in Preferences table to accommodate BA style data and BreweryDB key.</li>";
$output .= "<li>Updated Archive tables to accommodate Pro Edition and BA Styles.</li>";

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

$output .= "<li>PDF file name in the user_docs directory converted to lowercase (including extension).</li>";

/**
 * ----------------------------------------------- 2.1.12 ----------------------------------------------
 * Add Certified Cider Judge designation
 * Change unused archive column to archiveScoresheet
 * Saves the preference from current when archiving for correct display of archived scoresheets
 */


if (!check_update("brewerJudgeCider", $prefix."brewer")) {

	$updateSQL = sprintf("ALTER TABLE `%s` ADD `brewerJudgeCider` CHAR(1) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL AFTER `brewerJudgeMead`;",$prefix."brewer");
	mysqli_select_db($connection,$database);
	mysqli_real_escape_string($connection,$updateSQL);
	$result = mysqli_query($connection,$updateSQL);

	// Set Cider Judge designation to N for all users
	$updateSQL = sprintf("UPDATE `%s` SET brewerJudgeCider='N';",$prefix."brewer");
	mysqli_select_db($connection,$database);
	mysqli_real_escape_string($connection,$updateSQL);
	$result = mysqli_query($connection,$updateSQL);

}

$output .= "<li>Added Certified Cider Judge designation.</li>";

if (!check_update("archiveScoresheet", $prefix."archive")) {

	$updateSQL = sprintf("ALTER TABLE `%s` CHANGE `archiveBrewingTableName` `archiveScoresheet` CHAR(1) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL;",$prefix."archive");
	mysqli_select_db($connection,$database);
	mysqli_real_escape_string($connection,$updateSQL);
	$result = mysqli_query($connection,$updateSQL);

	// Set all to 'J'
	$updateSQL = sprintf("UPDATE `%s` SET archiveScoresheet='J';",$prefix."archive");
	mysqli_select_db($connection,$database);
	mysqli_real_escape_string($connection,$updateSQL);
	$result = mysqli_query($connection,$updateSQL);

}

$output .= "<li>Updated archive table for proper access of archived scoresheets.</li>";

/**
 * ----------------------------------------------- 2.1.13 ----------------------------------------------
 * Add BA styles to styles DB table
 * As of April 2018, BreweryDB not issuing API keys; installations not able to use BA styles
 * -----------------------------------------------------------------------------------------------------
 */

$ba_styles_present = FALSE;

$query_ba_present = sprintf("SELECT COUNT(*) AS 'count' FROM %s WHERE brewStyle = 'Energy Enhanced Malt Beverage' AND brewStyleOwn='bcoe'",$prefix."styles");
$ba_present = mysqli_query($connection,$query_ba_present) or die (mysqli_error($connection));
$row_ba_present = mysqli_fetch_assoc($ba_present);

if ($row_ba_present['count'] > 0) $ba_styles_present = TRUE;

$styles_db_table = $prefix."styles";

$updateSQL = "
INSERT INTO `$styles_db_table` (`brewStyleNum`, `brewStyle`, `brewStyleCategory`, `brewStyleOG`, `brewStyleOGMax`, `brewStyleFG`, `brewStyleFGMax`, `brewStyleABV`, `brewStyleABVMax`, `brewStyleIBU`, `brewStyleIBUMax`, `brewStyleSRM`, `brewStyleSRMMax`, `brewStyleType`, `brewStyleInfo`, `brewStyleLink`, `brewStyleGroup`, `brewStyleActive`, `brewStyleOwn`, `brewStyleVersion`, `brewStyleReqSpec`, `brewStyleStrength`, `brewStyleCarb`, `brewStyleSweet`, `brewStyleTags`, `brewStyleComEx`, `brewStyleEntry`) VALUES
('001', 'Classic Australian-Style Pale Ale', 'Other Origin Ales', '1.040', '1.052', '1.004', '1.010', '4.0', '6.0', '15', '35', '3', '10', '1', 'Perceivable fruity-estery aroma and flavor should be present, and are defining character of this beer style, balanced by low to medium hop aroma. Overall flavor impression is mild. Clean yeasty, bready character may be evident. Yeast in suspension if present may impact overall perception of bitterness. Diacetyl if present should be very low. DMS should not be present.', 'https://www.brewersassociation.org/resources/brewers-association-beer-style-guidelines/', '06', 'N', 'bcoe', 'BA', 0, 0, 0, 0, '2018', NULL, NULL),
('002', 'Juicy or Hazy Pale Ale', 'North American Origin Ales', '1.044', '1.050', '1.008', '1.014', '4.4', '5.4', '30', '50', '4', '7', '1', NULL, 'https://www.brewersassociation.org/resources/brewers-association-beer-style-guidelines', '03', 'N', 'bcoe', 'BA', 0, 0, 0, 0, '2018', NULL, NULL),
('003', 'Juicy or Hazy India Pale Ale', 'North American Origin Ales', '1.060', '1.070', '1.008', '1.016', '6.3', '7.5', '50', '70', '4', '7', '1', NULL, 'https://www.brewersassociation.org/resources/brewers-association-beer-style-guidelines/', '03', 'N', 'bcoe', 'BA', 0, 0, 0, 0, '2018', NULL, NULL),
('004', 'Juicy or Hazy Imperial or Double India Pale Ale', 'North American Origin Ales', '1.070', '1.100', '1.012', '1.020', '7.6', '10.6', '65', '100', '4', '7', NULL, NULL, 'https://www.brewersassociation.org/resources/brewers-association-beer-style-guidelines/', '03', 'N', 'bcoe', 'BA', 0, 0, 0, 0, '2018', NULL, NULL),
('005', 'Contemporary American-Style Pilsener', 'North American Origin Lagers', '1.045', '1.060', '1.012', '1.018', '4.9', '6.0', '25', '40', '3', '6', '1', 'Up to 25% corn and/or rice in the grist should be used. Beers in this category diverge from American-style lagers typical of the pre-Prohibition era by virtue of a wide range of hop aroma and flavor attributes.', 'https://www.brewersassociation.org/resources/brewers-association-beer-style-guidelines/', '08', 'N', 'bcoe', 'BA', 0, 0, 0, 0, '2018', NULL, NULL),
('006', 'Wild Beer', 'Hybrid/Mixed Lagers or Ales', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '1', 'Wild Beers are any range of color. These beers may be clear or hazy due to yeast, chill haze or hop haze. Aromas may vary tremendously due to fermentation characters contributed by various known and unknown microorganisms. The overall balance should be complex and balanced. Hop aroma very low to high. Usually because of a high degree of attenuation in these beers, malt character is very low to low. If there are exceptions that are malty, the overall balance of complexity of other characters should be in harmony. Hop flavor very low to high. Hop bitterness is perceived at varying levels depending on the overall balance, but usually perceived as very low to low. Wild beers are \"spontaneously\" fermented with microorganisms that the brewer has introduced from the ambient air/environment in the vicinity of the brewery in which the beer is brewed. Wild beers may not be fermented with any cultured strains of yeast or bacteria. Wild beer may or may not be perceived as acidic. It may include a wildly variable spectrum of flavors and aromas derived from the wild microorganisms with which it was fermented. The overall balance of flavors, aromas, appearance and body is an important factor in assessing these beers. Body is very low to medium. Spontaneously fermented beers with fruit, spice or other ingredients would be appropriately entered as Wild Beer. For purposes of competition, entries which could be appropriately entered in an existing classic or traditional category such as Belgian-Style Lambic, Gueuze, Fruit Lambic, etc. should be entered in that category and not entered as a Wild Beer.', 'https://www.brewersassociation.org/resources/brewers-association-beer-style-guidelines/', '11', 'N', 'bcoe', 'BA', 1, 0, 0, 0, '2015', NULL, NULL),
('007', 'Chili Pepper Beer', 'Hybrid/Mixed Lagers or Ales', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '1', 'Chili Pepper Beers are any range of color from pale to very dark depending on the underlying style. Clear or hazy beer is acceptable in appearance. Chili Beers are any beers using chili peppers as a flavor, aroma or “heat” inducing adjunct to create distinct and balanced (ranging from subtle to intense) character. Chili pepper aromas ranging from subtle to intense may or may not be evident, and should not be overpowered by hop aromas. Malt sweetness can vary from very low to medium-high levels, depending on the underlying beer style. Hop bitterness is very low to medium-high. Chili pepper aroma and flavor qualities should not be overpowered by hop aroma and flavor, and should be present in harmony with characteristics typical of the underlying beer style. Chili pepper qualities may vary widely as vegetal, spicy or \"heat\" inducing flavors and/or aromas.', 'https://www.brewersassociation.org/resources/brewers-association-beer-style-guidelines/', '11', 'N', 'bcoe', 'BA', 1, 0, 0, 0, '2015', NULL, NULL),
('008', 'Mixed Culture Brett Beer', 'Hybrid/Mixed Lagers or Ales', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '1', 'Mixed Culture Brett Beers are any range of color and may take on the color of added fruits or other ingredients. Chill haze, bacteria and yeast-induced haze are allowable at low to medium levels at any temperature. Moderate to intense yet balanced fruity-ester aromas are evident. In darker versions, roasted malt, caramel-like and chocolatelike aromas are subtly present. Diacetyl and DMS aromas should not be perceived. Hop aroma evident over a full range from low to high. In darker versions, roasted malt, caramel-like and chocolate-like flavors are subtly present. Fruited versions will exhibit fruit flavors in harmonious balance with other characters. Hop flavor is evident over a full range from low to high. Hop bitterness is evident over a full range from low to high. The evolution of natural acidity develops balanced complexity. Horsey, goaty, leathery, phenolic and light to moderate and/or fruity acidic character evolved from Brettanomyces organisms may be evident, not dominant and in balance with other character. Cultured yeast may be used in the fermentation. Bacteria should be used and in evidence in this style of beer. Acidity will be contributed by bacteria, but may or may not dominate. Moderate to intense yet balanced fruity-ester flavors are evident. Diacetyl and DMS flavors should not be perceived. Wood vessels may be used during the fermentation and aging process, but wood-derived flavors such as vanillin must not be present. Residual flavors that come from liquids previously aged in a barrel such as bourbon or sherry should not be present. Body is evident over a full range from low to high. For purposes of competition entries exhibiting wood-derived characters or characters of liquids previously aged in wood would more appropriately be entered in other Wood-Aged Beer categories. Wood- and barrel-aged sour ales should not be entered here and are classified elsewhere.', 'https://www.brewersassociation.org/resources/brewers-association-beer-style-guidelines/', '11', 'N', 'bcoe', 'BA', 1, 0, 0, 0, '2015', NULL, NULL),
('009', 'Dutch-Style Kuit, Kuyt or Koyt', 'Other Origin Ales', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '1', 'Dutch-Style Kuit, Kuyt or Koyts are gold to copper colored ale. Chill haze and other haze is allowable. The overall aroma character of this beer is grain emphasized with a grainy-bready accent. Hop aroma is very low to low from noble hops or other traditional European varieties. The distinctive character comes from use of minimum 45% oat malt, minimum 20% wheat malt and the remainder pale malt. Hop flavor is very low to low from noble or other traditional European varieties. Hop bitterness is medium-low to medium in perceived intensity. Esters may be present at low levels. Very low levels of diacetyl are acceptable. Acidity and sweet corn-like DMS (dimethylsulfide) should not be perceived. This style of beer was popular in the Netherlands from 1400-1550. Body is low to medium.', 'https://www.brewersassociation.org/resources/brewers-association-beer-style-guidelines/', '06', 'N', 'bcoe', 'BA', 0, 0, 0, 0, '2015', NULL, NULL),
('010', 'Belgian-Style Fruit Beer', 'Hybrid/Mixed Lagers or Ales', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '1', 'Belgian-Style Fruit Beers are any range of color from pale to dark depending on underlying Belgian style being fruited. Clear to hazy beer is acceptable in appearance. Fruit aromas ranging from subtle to intense should be evident, and should not be overpowered by hop aromas. Belgian-Style Fruit Beers are fermented with traditional Belgian-, farmhouse-, saison- and/or Brettanomyces-type yeast using fruit or fruit extracts as an adjunct in either the mash, kettle, primary or secondary fermentation providing obvious (ranging from subtle to intense), yet harmonious, fruit qualities. Malt sweetness can vary from not perceived to medium-high levels. Acidic bacterial (not wild yeast) fermentation characters may be evident (but not necessary) and if present contribute to acidity and enhance fruity balance. Body is variable with style. Classifying these beers is complex, with exemplary versions depending on the exhibition of fruit characters more so than the addition of fruit itself, within a Belgian beer style. As an example, a fruited Saison exhibiting some Brett character would be appropriately considered as Belgian Fruit Beer; whereas a fruited Brett Beer might more appropriately be considered as Brett Beer. Lambic-Style fruit beers should be entered in the Belgian-Style Fruit Lambic category. Fruited Belgian style beers brewed with additional unusual fermentables should be entered in this category. Fruit beers fermented using German, British or American ale or lager yeast would be more appropriately categorized as American-Style Fruit Beer or as Fruit Wheat Beer. For purposes of competition coconut is defined as a vegetable; beers exhibiting coconut character would be appropriately entered as Field Beer.', 'https://www.brewersassociation.org/resources/brewers-association-beer-style-guidelines/', '11', 'N', 'bcoe', 'BA', 1, 0, 0, 0, '2015', NULL, NULL),
('011', 'Session India Pale Ale', 'North American Origin Ales', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '1', 'Session India Pale Ales are gold to copper. Chill haze is allowable at cold temperatures and hop haze is allowable at any temperature. Fruity-ester aroma is light to moderate. Hop aroma is medium to high with qualities from a wide variety of hops from all over the world. Low to medium maltiness is present. Hop flavor is strong, characterized by flavors from a wide variety of hops. Hop bitterness is medium to high. Fruity-ester flavors are low to moderate. Diacetyl is absent or at very low levels. Body is low to medium.', 'https://www.brewersassociation.org/resources/brewers-association-beer-style-guidelines/', '03', 'N', 'bcoe', 'BA', 0, 0, 0, 0, '2015', NULL, NULL),
('012', 'Contemporary-Style Gose', 'British Origin Ales', '1.036', '1.056', '1.004', '1.012', '4.4', '5.4', '5', '30', '3', '9', '1', 'Contemporary Goses are straw to medium amber, or, may take on the hue of added fruits or other ingredients if present. Appearance is cloudy/hazy with yeast character, and may have evidence of continued fermentation activity. A wide variety of herbal, spice, floral or fruity aromas other than found in traditional Leipzig-Style Gose are present, in harmony with other aromas. Horsey, leathery or earthy aromas contributed by Brettanomyces yeasts may be evident but have a very low profile, as this beer is not excessively aged. Hop aroma is not perceived. Malt sweetness is not perceived to very low. They typically contain malted barley and unmalted wheat, with some traditional varieties containing oats. Hop flavor is not perceived. Hop bitterness is not perceived. A wide variety of herbal, spice, floral or fruity flavors other than found in traditional Leipzig-Style Gose, are present in harmony with the overall flavor profile. Salt (table salt) character is traditional in low amounts, but may vary from absent to present in Contemporary Gose. Horsey, leathery or earthy flavors contributed by Brettanomyces yeasts may be evident but have a very low profile, as this beer is not excessively aged. Contemporary Gose may be fermented with pure beer yeast strains, or with yeast mixed with bacteria. Contemporary Gose may be spontaneously fermented, similarly to Belgian-style gueuze/lambic beers, and should exhibit complexity of acidic, flavor and aroma contributed by introduction of wild yeast and bacteria into the fermentation. Low to medium lactic acid character is evident in all examples as sharp, refreshing sourness. A primary difference between Belgian Gueuze and Gose is that Gose is served at a much younger age. Gose is typically enjoyed fresh and carbonated. Overall complexity of flavors and aromas sought while maintaining a balance between acidity, yeast-enhanced spice and refreshment is ideal. Body is low to medium-low.', 'https://www.brewersassociation.org/resources/brewers-association-beer-style-guidelines/', '01', 'N', 'bcoe', 'BA', 0, 0, 0, 0, '2015', NULL, NULL),
('013', 'Flavored Malt Beverage', 'Malternative Beverages', NULL, NULL, NULL, NULL, '4.0', '6.0', NULL, NULL, NULL, NULL, '1', 'Flavored malt beverages are brewery products that are different from traditional malt beverages like malt liquor, ales, and lagers in several aspects. Flavored malt beverages exhibit little or no traditional beer or malt beverage flavor characteristics. Their flavor is primarily derived from added flavoring rather than from malt and other materials used in fermentation. However, flavored malt beverages are marketed in traditional beer bottles and cans and distributed to the alcohol beverage market through beer and malt beverage wholesalers. Their alcohol content is similar to other malt beverages – in the range of 4-6% alcohol by volume.', 'https://www.brewersassociation.org/resources/brewers-association-beer-style-guidelines/', '14', 'N', 'bcoe', 'BA', 1, 0, 0, 0, '2015', NULL, NULL),
('014', 'Energy Enhanced Malt Beverage', 'Malternative Beverages', NULL, NULL, NULL, NULL, '1.0', NULL, NULL, NULL, NULL, NULL, '1', 'Energy enhanced malt beverages are alcoholic drinks that are supplemented with caffeine or other stimulants such as guarana, ginseng, or taurine. At a minimum, these beverages contain at least one percent of alcohol by volume.', 'https://www.brewersassociation.org/resources/brewers-association-beer-style-guidelines/', '14', 'N', 'bcoe', 'BA', 1, 0, 0, 0, '2015', NULL, NULL),
('015', 'Double Red Ale', 'British Origin Ales', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '1', 'Double Red Ales are deep amber to dark copper/reddish brown. A small amount of chill haze is allowable at cold temperatures. Fruity-ester aroma is medium. Hop aroma is high, arising from any variety of hops. Medium to medium-high caramel malt character is present. Low to medium biscuit or toasted characters may also be present. Hop flavor is high and balanced with other beer characters. Hop bitterness is high to very high. Alcohol content is medium to high. Complex alcohol flavors may be evident. Fruity-ester flavors are medium. Diacetyl should not be perceived. Body is medium to full.', 'https://www.brewersassociation.org/resources/brewers-association-beer-style-guidelines/', '01', 'N', 'bcoe', 'BA', 0, 0, 0, 0, '2015', NULL, NULL),
('016', 'Adambier', 'German Origin Ales', '1.070', '1.090', '1.010', '1.020', '7.1', '8.7', '30', '50', '15', '35', '1', 'Adambier is light brown to very dark in color. It may or may not use wheat in its formulation. Original styles of this beer may have a low or medium low degree of smokiness. Smoke character may be absent in contemporary versions of this beer. Astringency of highly roasted malt should be absent. Toast and caramel-like malt characters may be evident. Low to medium hop bitterness are perceived. Low hop flavor and aroma are perceived. It is originally a style from Dortmund. Adambier is a strong, dark, hoppy, sour ale extensively aged in wood barrels. Extensive aging and the acidification of this beer can mask malt and hop character to varying degrees. Traditional and non-hybrid varieties of European hops were traditionally used. A Kölsch-like ale fermentation is typical Aging in barrels may contribute some level of Brettanomyces and lactic character. The end result is a medium to full bodied complex beer in hop, malt, Brett and acidic balance.', 'https://www.brewersassociation.org/resources/brewers-association-beer-style-guidelines/', '04', 'N', 'bcoe', 'BA', 0, 0, 0, 0, '2015', NULL, NULL),
('017', 'Grodziskie', 'Other Origin Ales', '1.028', '1.036', '1.006', '1.010', '2.1', '2.9', '15', '25', '3', '6', '1', 'Grodziskies are straw to golden colored. Chill haze is allowable at cold temperatures. Aroma is dominated by oak smoke notes. Fruity-ester aroma can be low. Diacetyl and DMS aromas should not be perceived. Hop aroma is not perceived to very low European noble hop aroma notes. Distinctive character comes from 100% oak wood smoked wheat malt. Overall balance is a sessionably medium to medium-high assertively oak-smoky malt emphasized beer. Hop flavor is very low to low European noble hop flavor notes. Hop bitterness is medium-low to medium clean hop bitterness. Ale fermentation temperatures are managed to lend a crisp overall flavor impression. Low fruity-ester flavor may be present. Sourness, diacetyl, and DMS should not be perceived on the palate. Grodziskie (also known as Grätzer) is a Polish ale style. Historic versions were most often bottle conditioned to relatively high carbonation levels. Body is low to medium low.', 'https://www.brewersassociation.org/resources/brewers-association-beer-style-guidelines/', '06', 'N', 'bcoe', 'BA', 0, 0, 0, 0, '2015', NULL, NULL),
('018', 'Apple Wine', 'Mead, Cider, and Perry', '1.070', '1.100', '0.995', '1.010', '9.0', '12.0', NULL, NULL, NULL, NULL, '2', 'Like a dry white wine, balanced, and with low astringency and bitterness.', 'https://www.brewersassociation.org/resources/brewers-association-beer-style-guidelines/', '12', 'N', 'bcoe', 'BA', 0, 0, 1, 1, '2015', NULL, NULL),
('019', 'Other Specialty Cider or Perry', 'Mead, Cider, and Perry', '1.045', '1.100', '0.995', '1.020', '5.0', '12.0', NULL, NULL, NULL, NULL, '2', NULL, 'https://www.brewersassociation.org/resources/brewers-association-beer-style-guidelines/', '12', 'N', 'bcoe', 'BA', 1, 0, 1, 1, '2015', NULL, NULL),
('020', 'American-Style Imperial Porter', 'North American Origin Ales', '1.080', '1.100', '1.020', '1.030', '5.5', '9.5', '35', '50', '40', '40', '1', 'American-style imperial porters are black in color. No roast barley or strong burnt/astringent black malt character should be perceived. Medium malt, caramel and cocoa-like sweetness. Hop bitterness is perceived at a medium-low to medium level. Hop flavor and aroma may vary from being low to medium-high. This is a full bodied beer. Ale-like fruity esters should be evident but not overpowering and compliment hop character and malt derived sweetness. Diacetyl (butterscotch) levels should be absent.', 'https://www.brewersassociation.org/resources/brewers-association-beer-style-guidelines/', '03', 'N', 'bcoe', 'BA', 0, 0, 0, 0, '2015', NULL, NULL),
('021', 'Common Perry', 'Mead, Cider, and Perry', '1.050', '1.060', '1.000', '1.020', '5.0', '7.0', NULL, NULL, NULL, NULL, '2', 'Mild. Medium to medium-sweet. Still to lightly sparkling. Only very slight acetification is acceptable. Mousiness, ropy/oily characters are serious faults.', 'https://www.brewersassociation.org/resources/brewers-association-beer-style-guidelines/', '12', 'N', 'bcoe', 'BA', 0, 0, 1, 1, '2015', NULL, NULL),
('022', 'Traditional Perry', 'Mead, Cider, and Perry', '1.050', '1.070', '1.000', '1.020', '5.0', '9.0', NULL, NULL, NULL, NULL, '2', 'Tannic. Medium to medium-sweet. Still to lightly sparkling. Only very slight acetification is acceptable. Mousiness, ropy/oily characters are serious faults.', 'https://www.brewersassociation.org/resources/brewers-association-beer-style-guidelines/', '12', 'N', 'bcoe', 'BA', 1, 0, 1, 1, '2015', NULL, NULL),
('023', 'New England Cider', 'Mead, Cider, and Perry', '1.060', '1.100', '0.995', '1.010', '7.0', '13.0', NULL, NULL, NULL, NULL, '2', 'Substantial body and character.', 'https://www.brewersassociation.org/resources/brewers-association-beer-style-guidelines/', '12', 'N', 'bcoe', 'BA', 1, 0, 1, 1, '2015', NULL, NULL),
('024', 'Fruit Cider', 'Mead, Cider, and Perry', '1.045', '1.070', '0.995', '1.010', '5.0', '9.0', NULL, NULL, NULL, NULL, '2', 'Like a dry wine with complex flavors. The apple character must marry with the added fruit so that neither dominates the other.', 'https://www.brewersassociation.org/resources/brewers-association-beer-style-guidelines/', '12', 'N', 'bcoe', 'BA', 1, 0, 1, 1, '2015', NULL, NULL),
('025', 'Open Category Mead', 'Mead, Cider, and Perry', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '3', 'This mead should exhibit the character of all of the ingredients in varying degrees, and should show a good blending or balance between the various flavor elements. Whatever ingredients are included, the result should be identifiable as a honey-based fermented beverage.', 'https://www.brewersassociation.org/resources/brewers-association-beer-style-guidelines/', '12', 'N', 'bcoe', 'BA', 1, 1, 1, 1, '2015', NULL, NULL),
('026', 'Common Cider', 'Mead, Cider, and Perry', '1.045', '1.065', '1.000', '1.020', '5.0', '8.0', NULL, NULL, NULL, NULL, '2', 'Variable, but should be a medium, refreshing drink. Sweet ciders must not be cloying. Dry ciders must not be too austere. An ideal cider serves well as a “session” drink, and suitably accompanies a wide variety of food.', 'https://www.brewersassociation.org/resources/brewers-association-beer-style-guidelines/', '12', 'N', 'bcoe', 'BA', 0, 0, 1, 1, '2015', NULL, NULL),
('027', 'English Cider', 'Mead, Cider, and Perry', '1.050', '1.075', '0.995', '1.010', '6.0', '9.0', NULL, NULL, NULL, NULL, '2', 'Generally dry, full-bodied, austere.', 'https://www.brewersassociation.org/resources/brewers-association-beer-style-guidelines/', '12', 'N', 'bcoe', 'BA', 0, 0, 1, 1, '2015', NULL, NULL),
('028', 'French Cider', 'Mead, Cider, and Perry', '1.050', '1.065', '1.010', '1.020', '3.0', '6.0', NULL, NULL, NULL, NULL, '2', 'Medium to sweet, full-bodied, rich.', 'https://www.brewersassociation.org/resources/brewers-association-beer-style-guidelines/', '12', 'N', 'bcoe', 'BA', 1, 0, 1, 1, '2015', NULL, NULL),
('029', 'Braggot', 'Mead, Cider, and Perry', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '3', 'A harmonious blend of mead and beer, with the distinctive characteristics of both. A wide range of results are possible, depending on the base style of beer, variety of honey and overall sweetness and strength. Beer flavors tend to somewhat mask typical honey flavors found in other meads.', 'https://www.brewersassociation.org/resources/brewers-association-beer-style-guidelines/', '12', 'N', 'bcoe', 'BA', 0, 1, 1, 1, '2015', NULL, NULL),
('030', 'Other Fruit Melomel', 'Mead, Cider, and Perry', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '3', 'In well-made examples of the style, the fruit is both distinctive and well-incorporated into the honey-sweet-acid-tannin-alcohol balance of the mead. Different types of fruit can result in widely different characteristics; allow for a variation in the final product.', 'https://www.brewersassociation.org/resources/brewers-association-beer-style-guidelines/', '12', 'N', 'bcoe', 'BA', 1, 1, 1, 1, '2015', NULL, NULL),
('031', 'Metheglin', 'Mead, Cider, and Perry', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '3', 'In well-made examples of the style, the herbs/spices are both distinctive and well-incorporated into the honey-sweet-acid-tannin-alcohol balance of the mead. Different types of herbs/spices can result in widely different characteristics; allow for a variation in the final product.', 'https://www.brewersassociation.org/resources/brewers-association-beer-style-guidelines/', '12', 'N', 'bcoe', 'BA', 1, 1, 1, 1, '2015', NULL, NULL),
('032', 'Pyment (Grape Melomel)', 'Mead, Cider, and Perry', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '3', 'In well-made examples of the style, the grape is both distinctively vinous and well-incorporated into the honey-sweet-acid-tannin-alcohol balance of the mead. White and red versions can be quite different, and the overall impression should be characteristic of the type of grapes used and suggestive of a similar variety wine.', 'https://www.brewersassociation.org/resources/brewers-association-beer-style-guidelines/', '12', 'N', 'bcoe', 'BA', 0, 1, 1, 1, '2015', NULL, NULL),
('033', 'Sweet Mead', 'Mead, Cider, and Perry', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '3', 'Similar in balance, body, finish and flavor intensity to a well-made dessert wine (such as Sauternes), with a pleasant mixture of honey character, residual sweetness, soft fruity esters, and clean alcohol. Complexity, harmony, and balance of sensory elements are most desirable, with no inconsistencies in color, aroma, flavor or aftertaste. The proper balance of sweetness, acidity, alcohol and honey character is the essential final measure of any mead.', 'https://www.brewersassociation.org/resources/brewers-association-beer-style-guidelines/', '12', 'N', 'bcoe', 'BA', 0, 1, 1, 0, '2015', NULL, NULL),
('034', 'Cyser (Apple Melomel)', 'Mead, Cider, and Perry', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '3', 'In well-made examples of the style, the fruit is both distinctive and well-incorporated into the honey-sweet-acid-tannin-alcohol balance of the mead. Some of the best strong examples have the taste and aroma of an aged Calvados (apple brandy from northern France), while subtle, dry versions can taste similar to many fine white wines.', 'https://www.brewersassociation.org/resources/brewers-association-beer-style-guidelines/', '12', 'N', 'bcoe', 'BA', 0, 1, 1, 1, '2015', NULL, NULL),
('035', 'Semi-Sweet Mead', 'Mead, Cider, and Perry', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '3', 'Similar in balance, body, finish and flavor intensity to a semisweet (or medium-dry) white wine, with a pleasant mixture of honey character, light sweetness, soft fruity esters, and clean alcohol. Complexity, harmony, and balance of sensory elements are most desirable, with no inconsistencies in color, aroma, flavor or aftertaste. The proper balance of sweetness, acidity, alcohol and honey character is the essential final measure of any mead.', 'https://www.brewersassociation.org/resources/brewers-association-beer-style-guidelines/', '12', 'N', 'bcoe', 'BA', 0, 1, 1, 0, '2015', NULL, NULL),
('036', 'Dry Mead', 'Mead, Cider, and Perry', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '3', 'Similar in balance, body, finish and flavor intensity to a dry white wine, with a pleasant mixture of subtle honey character, soft fruity esters, and clean alcohol. Complexity, harmony, and balance of sensory elements are most desirable, with no inconsistencies in color, aroma, flavor or aftertaste. The proper balance of sweetness, acidity, alcohol and honey character is the essential final measure of any mead.', 'https://www.brewersassociation.org/resources/brewers-association-beer-style-guidelines/', '12', 'N', 'bcoe', 'BA', 0, 1, 1, 0, '2015', NULL, NULL),
('037', 'Aged Beer (Ale or Lager)', 'Hybrid/Mixed Lagers or Ales', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '1', 'Beers aged for over one year. Generally beers with high hopping rates, roast malt content, high alcohol content, complex herbal, smoke or fruit content (Wood aging, Brettanomyces characters and acidic beers must be classified or entered into other categories if that option is available), A brewer may brew any type of beer of any strength and enhance its character with extended and creative aging conditions. Beers in this category may be aged in bottles or any type of food grade vessel. In competition brewers may be required to state age of beer. Competition organizer may develop guidelines in which aged beers are subcategorized by aging time, vessel, styles, etc. Brewers should provide a statement describing the nature or style of the beer. This statement could include classic or other style, special ingredients, length of aging time, etc.', 'https://www.brewersassociation.org/resources/brewers-association-beer-style-guidelines/', '11', 'N', 'bcoe', 'BA', 1, 0, 0, 0, '2015', NULL, NULL),
('038', 'Other Strong Ale or Lager', 'Hybrid/Mixed Lagers or Ales', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '1', 'Any style of beer can be made stronger than the classic style guidelines. The goal should be to reach a balance between the style\'s character and the additional alcohol. Refer to this guide when making styles stronger and appropriately identify the style created (for example: double alt, triple fest, or quadruple Pilsener).', 'https://www.brewersassociation.org/resources/brewers-association-beer-style-guidelines/', '11', 'N', 'bcoe', 'BA', 1, 0, 0, 0, '2015', NULL, NULL),
('039', 'Non-Alcoholic Malt Beverage', 'Hybrid/Mixed Lagers or Ales', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '1', 'Non-alcoholic (NA) malt beverages should emulate the character of a previously listed category/subcategory designation but without the alcohol (less than 0.5 percent). Non-alcoholic malt beverages will inherently have a profile lacking the complexity and balance of flavors which can be attributed to alcohol. They should accordingly not be assessed negatively for reasons related to the absence of alcohol.', 'https://www.brewersassociation.org/resources/brewers-association-beer-style-guidelines/', '11', 'N', 'bcoe', 'BA', 1, 0, 0, 0, '2018', NULL, NULL),
('040', 'Wood- and Barrel-Aged Sour Beer', 'Hybrid/Mixed Lagers or Ales', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '1', 'A wood- or barrel-aged sour beer is any lager, ale or hybrid beer, either a traditional style or a unique experimental beer that has been aged for a period of time in a wooden barrel or in contact with wood and has developed a bacterial induced natural acidity. This beer is aged in wood with the intention of introducing the micro flora present in the wood. Sometimes wood aging is intended to impart the particularly unique character of the wood, but wood-aged is not necessarily synonymous with imparting wood-flavors. Wood character can be characterized as a complex blend of vanillin and unique wood character. Wood-derived character can also be characterized by flavors of the product that was in the barrel during prior use. These wood-derived flavors, if present in this style, can be very low in character and barely perceived or evident or assertive as wood-derived flavors. Any degree of woodderived flavors should be in balance with other beer character. Fruit and herb/spiced versions may take on the hue, flavors and aromas of added ingredients. Usually bacteria and \"wild\" yeasts fermentation contributes complex esters and results in a dry to very dry beer. Ultimately a balance of flavor, aroma and mouthfeel are sought with the marriage of acidity, complex esters, and new beer with wood and/or barrel flavors. Beers in this style may or may not have Brettanomyces character. Brewers when entering this category should specify type of barrel used and any other special treatment or ingredients used. Competition managers may create style subcategories to differentiate between high alcohol and low alcohol beers and very dark and lighter colored beer as well as for fruit beers and non-fruit beers. Competitions may develop guidelines requesting brewers to specify what kind of wood (new or used oak, other wood varieties). The brewer may be asked to explain the special nature (wood used, base beer style(s) and achieved character) of the beer.', 'https://www.brewersassociation.org/resources/brewers-association-beer-style-guidelines/', '11', 'N', 'bcoe', 'BA', 1, 0, 0, 0, '2015', NULL, NULL),
('041', 'Wood- and Barrel-Aged Strong Beer', 'Hybrid/Mixed Lagers or Ales', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '1', 'Any strong classic style or unique, experimental style of beer can be wood or barrel-aged for a period of time in a wooden barrel or in contact with wood. This beer is aged with the intention of imparting the particularly unique character of the wood and/or what has previously been in the barrel. New wood character can be characterized as a complex blend of vanillin and unique wood character but wood aged is not necessarily synonymous with imparting wood-flavors. Used sherry, rum, bourbon, scotch, port, wine and other barrels are often used, imparting complexity and uniqueness to beer. Ultimately a balance of flavor, aroma and mouthfeel are sought with the marriage of new beer with wood and/or barrel flavors. Primary character of the beer style may or may not be apparent. Sour wood-aged beer of any color is outlined in other categories. Beers in this style may or may not have Brettanomyces character. The brewer must explain the special nature of the beer to allow for accurate judging. Comments could include: type of wood used (new or old, oak or other wood type), type of barrel used (new, port/ whiskey/ wine/ sherry/ other), base beer style or achieved character. Beer entries not accompanied by this information will be at a disadvantage during judging.', 'https://www.brewersassociation.org/resources/brewers-association-beer-style-guidelines/', '11', 'N', 'bcoe', 'BA', 1, 0, 0, 0, '2015', NULL, NULL),
('042', 'Wood- and Barrel-Aged Pale to Amber Beer', 'Hybrid/Mixed Lagers or Ales', NULL, NULL, NULL, NULL, '3.8', '6.5', NULL, NULL, '4', '18', '1', 'Any classic style or unique experimental beer that has been aged for a period of time in a wooden barrel or in contact with wood. This beer is aged with the intention of imparting the particularly unique character of the wood and/or what has previously been in the barrel. New wood character can be characterized as a complex blend of vanillin and/or other unique wood character but wood aged is not necessarily synonymous with imparting wood-flavors. Used sherry, rum, bourbon, scotch, port, wine and other barrels are often used, imparting complexity and uniqueness to beer. Ultimately a balance of flavor, aroma and mouthfeel are sought with the marriage of new beer with wood and/or barrel flavors. Primary character of the beer style may or may not be apparent. Sour wood-aged beer of any color is outlined in other categories. Fruited or spiced beer that is wood and barrel aged would also be appropriately entered in this category. Beers in this style may or may not have Brettanomyces character. The brewer should explain the special nature of the beer to allow for accurate judging. Comments could include: type of wood used (new or old, oak or other wood type), type of barrel used (new, port/ whiskey/ wine/ sherry/ other), base beer style or achieved character. Beer entries not accompanied by this information will be at a disadvantage during judging.', 'https://www.brewersassociation.org/resources/brewers-association-beer-style-guidelines/', '11', 'N', 'bcoe', 'BA', 1, 0, 0, 0, '2015', NULL, NULL),
('043', 'Wood- and Barrel-Aged Dark Beer', 'Hybrid/Mixed Lagers or Ales', NULL, NULL, NULL, NULL, '3.8', '6.5', NULL, NULL, NULL, NULL, '1', 'Any classic style or unique experimental style of dark beer can be wood or barrel-aged for a period of time in a wooden barrel or in contact with wood. This beer is aged with the intention of imparting the particularly unique character of the wood and/or what has previously been in the barrel. New wood character can be characterized as a complex blend of vanillin and/or other unique wood character but wood-aged is not necessarily synonymous with imparting wood-flavors. Used sherry, rum, bourbon, scotch, port, wine and other barrels are often used, imparting complexity and uniqueness to beer. Ultimately a balance of flavor, aroma and mouthfeel are sought with the marriage of new beer with wood and/or barrel flavors. Primary character of the beer style may or may not be apparent. Sour wood-aged beer of any color is outlined in other categories. Dark fruited or spiced beer would also be appropriately entered in this category. Beers in this style may or may not have Brettanomyces character. The brewer should explain the special nature of the beer to allow for accurate judging. Comments could include: type of wood used (new or old, oak or other wood type), type of barrel used (new, port/ whiskey/ wine/ sherry/ other), base beer style or achieved character. Beer entries not accompanied by this information will be at a disadvantage during judging.', 'https://www.brewersassociation.org/resources/brewers-association-beer-style-guidelines/', '11', 'N', 'bcoe', 'BA', 1, 0, 0, 0, '2015', NULL, NULL),
('044', 'Wood- and Barrel-Aged Beer', 'Hybrid/Mixed Lagers or Ales', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '1', 'A wood- or barrel-aged beer is any lager, ale or hybrid beer, either a traditional style or a unique experimental beer that has been aged for a period of time in a wooden barrel or in contact with wood. This beer is aged with the intention of imparting the particularly unique character of the wood and/or what has previously been in the barrel. New wood character can be characterized as a complex blend of vanillin and/or other unique wood character, but wood aged is not necessarily synonymous with imparting wood-flavors. Used sherry, rum, bourbon, scotch, port, wine and other barrels are often used, imparting complexity and uniqueness to beer. Ultimately a balance of flavor, aroma and mouthfeel are sought with the marriage of new beer with wood and/or barrel flavors. Beers in this style may or may not have Brettanomyces character. Brewers when entering this category should specify type of barrel and/or wood used and any other special treatment or ingredients used. Competition managers may create style subcategories to differentiate between high alcohol and low alcohol beers and very dark and lighter colored beer as well as for fruit beers and non-fruit beers. Competitions may develop guidelines requesting brewers to specify what kind of wood (new or used oak, other wood varieties) and/or barrel (whiskey, port, sherry, wine, etc.) was used in the process. The brewer may be asked to explain the special nature (wood used, base beer style(s) and achieved character) of the beer.', 'https://www.brewersassociation.org/resources/brewers-association-beer-style-guidelines/', '11', 'N', 'bcoe', 'BA', 1, 0, 0, 0, '2015', NULL, NULL),
('045', 'Historical Beer', 'Hybrid/Mixed Lagers or Ales', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '1', 'Historical Beers are any range of color. Malt sweetness will vary dramatically depending on overall balance desired. Hop bitterness is very low to very high. Above all beers in this category are reflective of an established historical beer and/or brewing heritage from any period of time or part of the world, that are not already a beer style already established in these guidelines. This beer commemorates combinations of unique brewing ingredients and/or techniques established in past periods. Examples of Historical Beers might include current day versions of historic styles which are not represented elsewhere in these guidelines, such as Finnish-style Sahti, South American Chicha, Nepalese Chong/Chang, African sorghum based beers, and others. In evaluating these beers, judges will weigh several factors such as uniqueness, heritage, regional distinction, technical brewing skills, and balance of character, background story & information and overall spirit of the intent of this category. \"Historical beers\" that are not represented elsewhere as a definitive style in these guidelines could possibly be entered in such categories as Experimental, Herb & Spice, Field Beer, etc. but by choice a brewer may categorize (and enter) their beer as Historical beer.', 'https://www.brewersassociation.org/resources/brewers-association-beer-style-guidelines/', '11', 'N', 'bcoe', 'BA', 1, 0, 0, 0, '2015', NULL, NULL),
('046', 'Smoke Beer (Lager or Ale)', 'Hybrid/Mixed Lagers or Ales', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '1', 'Any style of beer can be smoked; the goal is to reach a balance between the style\'s character and the smoky properties. Type of wood or other sources of smoke should be specified as well as the style the beer is based upon.', 'https://www.brewersassociation.org/resources/brewers-association-beer-style-guidelines/', '11', 'N', 'bcoe', 'BA', 1, 0, 0, 0, '2015', NULL, NULL),
('047', 'Experimental Beer (Lager or Ale)', 'Hybrid/Mixed Lagers or Ales', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '1', 'An experimental beer is any beer (lager, ale or other) that is primarily grain-based and employs unusual techniques and/or ingredients. A minimum 51% of the fermentable carbohydrates must be derived from malted grains. The overall uniqueness of the process, ingredients used and creativity should be considered. Beers such as garden (vegetable), fruit, chocolate, coffee, spice, specialty or other beers that match existing categories should not be entered into this category. Beers not easily matched to existing style categories in a competition would often be entered into this category. Beers that are a combination of other categories (spice, smoke, specialty, porter, etc.) could also be entered into this category. A statement by the brewer explaining the experimental or other nature of the beer is essential in order for fair assessment in competitions. Generally, a 25-word statement would suffice in explaining the experimental nature of the beer.', 'https://www.brewersassociation.org/resources/brewers-association-beer-style-guidelines/', '11', 'N', 'bcoe', 'BA', 1, 0, 0, 0, '2015', NULL, NULL),
('048', 'Indigenous Beer (Lager or Ale)', 'Hybrid/Mixed Lagers or Ales', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '1', 'This beer style is unusual in that its impetus is to commemorate combinations of ingredients and/or techniques adopted by or unique to a particular region. At least one regional combination of ingredients and/or techniques must be unique and differentiated from ingredients and/or techniques commonly used by brewers throughout the world. There are many excellent and popular beers that are brewed with either non-traditional or traditional ingredients and/or processes yet their character may distinctively vary from all other styles currently defined or included in these guidelines. These grain-based beers are brewed reflecting local beer culture (process, ingredients, climate, etc.). This category recognizes uniquely local or regional beer types and beers distinctively not defined in any recognized style in these guidelines. They may be light or dark, strong or weak, hoppy or not hoppy. They may have characters which are unique to yeast, fermentation techniques, aging conditions, carbonation level - or higher or lower levels of profound characters normally associated with other beer types. Examples of indigenous beers might include current day versions of highly regional and/or historic styles which are not represented elsewhere in these guidelines, such as Finnish-style sahti, S American chicha, African sorghum based beers, and others. Other examples might include beers made wholly unique by use of multiple local ingredients and/or techniques, with the resulting beer being highly representative of location, as well as differentiated from traditional beer style categories. Beers brewed with non-traditional hop varieties, grains, malt, yeast or other ingredient that still closely approximate an existing classical category would be more appropriately entered into the classical category. New and innovative beers that do not represent locally adopted techniques or grown ingredients would be more appropriately entered into the experimental category. Proper evaluation of entries in this category requires the need to provide judges with additional information about the beer. A written summary illustrating the intent, background, history, design and/or development of the beer as well as describing any regional and/or stylistic context, choice of ingredients, process and any other unique information, helps establish a basis for comparison between highly diverse entries. Entering brewers must provide a statement of 100 words or less illustrating the above and why it is an indigenous beer without revealing the company\'s identity. This statement should be carefully crafted and will be evaluated by judges and carry significant weight in their decisions. Statements that contain information which might identify or otherwise create bias towards the entry will be modified by the Competition Manager. Entries not accompanied by this information will be at a profound disadvantage during judging.', 'https://www.brewersassociation.org/resources/brewers-association-beer-style-guidelines/', '11', 'N', 'bcoe', 'BA', 1, 0, 0, 0, '2015', NULL, NULL),
('049', 'Gluten-Free Beer', 'Hybrid/Mixed Lagers or Ales', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '1', 'A beer (lager, ale or other) that is made from fermentable sugars, grains and converted carbohydrates. Ingredients do not contain gluten, in other words zero gluten (No barley, wheat, spelt, oats, rye, etc). May or may not contain malted grains that do not contain gluten. Brewers may or may not design and identify these beers along other style guidelines with regard to flavor, aroma and appearance profile. The beer\'s overall balance and character should be based on its own merits and not necessarily compared with traditional styles of beer. In competitions, brewers identify ingredients and fermentation type. NOTE: These guidelines do not supersede any government regulations. Wine, mead, flavored malt beverages or beverages other than beer as defined by the TTB (U.S. Trade and Tax Bureau) are not considered \"gluten-free beer\" under these guidelines.', 'https://www.brewersassociation.org/resources/brewers-association-beer-style-guidelines/', '11', 'N', 'bcoe', 'BA', 1, 0, 0, 0, '2015', NULL, NULL),
('050', 'Specialty Honey Lager or Ale', 'Hybrid/Mixed Lagers or Ales', '1.030', NULL, '1.006', '1.030', '2.5', '12.0', NULL, '100', '1', '100', '1', 'These beers are brewed using honey in addition to malted barley. Beers may be brewed to a traditional style or may be experimental. Character of honey should be evident in flavor and aroma and balanced with the other components without overpowering them. A statement by the brewer explaining the classic or other style of the beer, and the type of honey used is essential in order for fair assessment in competitions.', 'https://www.brewersassociation.org/resources/brewers-association-beer-style-guidelines/', '11', 'N', 'bcoe', 'BA', 1, 0, 0, 0, '2015', NULL, NULL),
('051', 'Specialty Beer', 'Hybrid/Mixed Lagers or Ales', '1.030', NULL, '1.006', '1.030', '2.5', '25.0', NULL, '100', '1', '100', '1', 'These beers are brewed using unusual fermentable sugars, grains and starches that contribute to alcohol content other than, or in addition to, malted barley. Nuts generally have some degree of fermentables, thus beers brewed with nuts would appropriately be entered in this category. The distinctive characters of these special ingredients should be evident either in the aroma, flavor or overall balance of the beer, but not necessarily in overpowering quantities. For example, maple syrup or potatoes would be considered unusual. Rice, corn, or wheat are not considered unusual. Special ingredients must be listed when competing. A statement by the brewer explaining the special nature of the beer, ingredient(s) and achieved character is essential in order for fair assessment in competitions. If this beer is a classic style with some specialty ingredient(s), the brewer should also specify the classic style. Guidelines for competing: Spiced beers using unusual fermentables should be entered in the experimental category. Fruit beers using unusual fermentables should be entered in the fruit beer category.', 'https://www.brewersassociation.org/resources/brewers-association-beer-style-guidelines/', '11', 'N', 'bcoe', 'BA', 1, 0, 0, 0, '2015', NULL, NULL),
('052', 'Herb and Spice Beer', 'Hybrid/Mixed Lagers or Ales', '1.030', NULL, '1.006', '1.030', '2.5', '12.0', '5', '70', '5', '50', '1', 'Herb beers use herbs or spices (derived from roots, seeds, fruits, vegetable, flowers, etc.) other than or in addition to hops to create a distinct (ranging from subtle to intense) character, though individual characters of herbs and/or spices used may not always be identifiable. Under hopping often, but not always, allows the spice or herb to contribute to the flavor profile. Positive evaluations are significantly based on perceived balance of flavors. Note: Chili-flavored beers that emphasize heat rather than chili flavor should be entered as a \"spiced\" beer. A statement by the brewer explaining what herbs or spices are used is essential in order for fair assessment in competitions. Specifying a style upon which the beer is based may help evaluation. If this beer is a classic style with an herb or spice, the brewer should specify the classic style. If no Chocolate or Coffee category exists in a competition, then chocolate and coffee beers should be entered in this category.', 'https://www.brewersassociation.org/resources/brewers-association-beer-style-guidelines/', '11', 'N', 'bcoe', 'BA', 1, 0, 0, 0, '2015', NULL, NULL),
('053', 'Chocolate / Cocoa-Flavored Beer', 'Hybrid/Mixed Lagers or Ales', '1.030', NULL, '1.006', '1.030', '2.5', '12.0', '15', '40', '15', '50', '1', 'Chocolate beers use \"dark\" chocolate or cocoa in any of its forms other than or in addition to hops to create a distinct (ranging from subtle to intense) character. Under hopping allows chocolate to contribute to the flavor profile while not becoming excessively bitter. White Chocolate should not be entered into this category. If this beer is a classic style with chocolate or cocoa, the brewer should specify the classic style.', 'https://www.brewersassociation.org/resources/brewers-association-beer-style-guidelines/', '11', 'N', 'bcoe', 'BA', 1, 0, 0, 0, '2015', NULL, NULL),
('054', 'Coffee-Flavored Beer', 'Hybrid/Mixed Lagers or Ales', '1.030', NULL, '1.006', '1.030', '2.5', '12.0', '15', '40', '8', '50', '1', 'Coffee beers use coffee in any of its forms other than or in addition to hops to create a distinct (ranging from subtle to intense) character. Under hopping allows coffee to contribute to the flavor profile while not becoming excessively bitter. If this beer is a classic style with coffee flavor, the brewer should specify the classic style.', 'https://www.brewersassociation.org/resources/brewers-association-beer-style-guidelines/', '11', 'N', 'bcoe', 'BA', 1, 0, 0, 0, '2015', NULL, NULL),
('055', 'Pumpkin Beer', 'Hybrid/Mixed Lagers or Ales', '1.030', NULL, '1.006', '1.030', '2.5', '12.0', '5', '70', '5', '50', '1', 'Pumpkin beers are any beers using pumpkins (Cucurbito pepo) as an adjunct in either mash, kettle, primary or secondary fermentation, providing obvious (ranging from subtle to intense), yet harmonious, qualities. Pumpkin qualities should not be overpowered by hop character. These may or may not be spiced or flavored with other things. A statement by the brewer explaining the nature of the beer is essential for fair assessment in competitions. If this beer is a classic style with pumpkin, the brewer should also specify the classic style.', 'https://www.brewersassociation.org/resources/brewers-association-beer-style-guidelines/', '11', 'N', 'bcoe', 'BA', 1, 0, 0, 0, '2015', NULL, NULL),
('056', 'Field Beer', 'Hybrid/Mixed Lagers or Ales', '1.030', NULL, '1.006', '1.030', '2.5', '13.1', '5', '70', '5', '50', '1', 'Field beers are any beers using vegetables as an adjunct in either the mash, kettle, primary or secondary fermentation, providing obvious (ranging from subtle to intense), yet harmonious, qualities. Vegetable qualities should not be overpowered by hop character. If a vegetable has an herbal or spice quality (such as the \"heat\" of a chili pepper), it should be classified as herb/spice beer style. Note Chili-flavored beer with notable roast or vegetal character is evident it should be entered as Field Beer. A statement by the brewer explaining what vegetables are used is essential in order for fair assessment in competitions. If this beer is a classic style with vegetables, the brewer should also specify the classic style. Note: If no Pumpkin beer category exists in a competition, then Pumpkin beers should be entered in this category.', 'https://www.brewersassociation.org/resources/brewers-association-beer-style-guidelines/', '11', 'N', 'bcoe', 'BA', 1, 0, 0, 0, '2015', NULL, NULL),
('057', 'German-Style Rye Ale (Roggenbier) with or without Yeast', 'Hybrid/Mixed Lagers or Ales', '1.047', NULL, '1.008', '1.016', '4.9', '5.5', '10', '15', '4', '25', '1', 'This beer can be made using phenol producing ale yeast. It should be brewed with at least 30 percent rye malt, and hop rates will be low. A banana -like fruity-estery aroma and flavor are typical but at low levels; phenolic, clove-like characteristics should also be perceived. Color is straw to dark amber, and the body should be light to medium in character. Diacetyl should not be perceived. If this style is packaged and served without yeast, no yeast characters should be evident in mouthfeel, flavor, or aroma. If the beer is served with yeast, the character should portray a full yeasty mouthfeel and appear hazy to very cloudy. Yeast flavor and aroma should be low to medium but not overpowering the balance and character of rye and barley malt and hops. Darker versions of this style will be dark amber to dark brown, and the body should be light to medium in character. Roasted malts are optionally evident in aroma and flavor with a low level of roast malt astringency acceptable when appropriately balanced with malt sweetness. Roast malts may be evident as a cocoa/chocolate or light caramel character. Aromatic toffee-like, caramel, or biscuit-like characters may be part of the overall flavor/aroma profile. As in the lighter colored versions, diacetyl should not be perceived.', 'https://www.brewersassociation.org/resources/brewers-association-beer-style-guidelines/', '11', 'N', 'bcoe', 'BA', 0, 0, 0, 0, '2015', NULL, NULL),
('058', 'Fruit Beer', 'Hybrid/Mixed Lagers or Ales', '1.030', NULL, '1.006', '1.030', '2.5', '12.0', '5', '70', '5', '50', '1', 'Fruit beers are any beers using fruit or fruit extracts as an adjunct in either, the mash, kettle, primary or secondary fermentation providing obvious (ranging from subtle to intense), yet harmonious, fruit qualities. Fruit qualities should not be overpowered by hop character. If a fruit (such as juniper berry) has an herbal or spice quality, it is more appropriate to consider it in the herb and spice beers category. Acidic bacterial (not wild yeast) fermentation characters may be evident (but not necessary) they would contribute to acidity and enhance fruity balance. Clear or hazy beer is acceptable in appearance. A statement by the brewer explaining what fruits are used is essential in order for fair assessment in competitions. If this beer is a classic style with fruit, the brewer should also specify the classic style.', 'https://www.brewersassociation.org/resources/brewers-association-beer-style-guidelines/', '11', 'N', 'bcoe', 'BA', 1, 0, 0, 0, '2015', NULL, NULL),
('059', 'Rye Ale or Lager with or without Yeast', 'Hybrid/Mixed Lagers or Ales', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '1', 'Rye beers can be made using either ale or lager yeast. It should be brewed with at least 20 percent rye malt, and low to medium perception of hop bitterness. Hop aroma and flavor can be low to medium-high. These are often versions of classic styles that contain noticeable rye character in balance with other qualities of the beer. A spicy, fruity-estery aroma and flavor are typical but at low levels; however, phenolic, clove-like characteristics should not be perceived. Color is straw to amber, and the body should be light to medium in character. Diacetyl should not be perceived. If this style is packaged and served without yeast, no yeast characters should be evident in mouthfeel, flavor, or aroma. A low level of tannin derived astringency may be perceived. If this style is served with yeast, the character should portray a full yeasty mouthfeel and appear hazy to very cloudy. Yeast flavor and aroma should be low to medium but not overpowering the balance and character of rye and barley malt and hops. Darker versions of this style will be dark amber to dark brown, and the body should be light to medium in character. Roasted malts are optionally evident in aroma and flavor with a low level of roast malt astringency acceptable when appropriately balanced with malt sweetness. Roast malts may be evident as a cocoa/chocolate or caramel character. Aromatic toffee-like, caramel, or biscuit-like characters may be part of the overall flavor/aroma profile. As in the lighter colored versions, diacetyl should not be perceived. Competition directors may create specific styles of rye beer, such as Rye Pale Ale or Rye Brown Ale. A statement by the brewer indicating if the beer is based on a classic style is essential for accurate judging.', 'https://www.brewersassociation.org/resources/brewers-association-beer-style-guidelines/', '11', 'N', 'bcoe', 'BA', 1, 0, 0, 0, '2015', NULL, NULL),
('060', 'Dark American Wheat Ale or Lager without Yeast', 'Hybrid/Mixed Lagers or Ales', '1.036', NULL, '1.004', '1.016', '3.8', '5.0', '10', '25', '9', '22', '1', 'This beer can be made using either ale or lager yeast. It can be brewed with 30 to 75 percent malted wheat, and hop rates may be low to medium. A fruity-estery aroma and flavor are typical but at low levels; however, phenolic, clove-like characteristics should not be perceived. Color is dark amber to dark brown, and the body should be light to medium in character. Roasted malts are optionally evident in aroma and flavor with a low level of roast malt astringency acceptable when appropriately balanced with malt sweetness. Roast malts may be evident as a cocoa/chocolate or caramel character. Aromatic toffee-like, caramel, or biscuit-like characters may be part of the overall flavor/aroma profile. Diacetyl should not be perceived. Because this style is packaged and served without yeast, no yeast characters should be evident in mouthfeel, flavor, or aroma. Chill haze is also acceptable.', 'https://www.brewersassociation.org/resources/brewers-association-beer-style-guidelines/', '11', 'N', 'bcoe', 'BA', 0, 0, 0, 0, '2015', NULL, NULL),
('061', 'Dark American Wheat Ale or Lager with Yeast', 'Hybrid/Mixed Lagers or Ales', '1.036', NULL, '1.004', '1.016', '3.8', '5.0', '10', '25', '9', '22', '1', 'This beer can be made using either ale or lager yeast. It can be brewed with 30 to 75 percent malt wheat, and hop rates may be low to medium. Fruity-estery aroma and flavor are typical but at low levels; however, phenolic, clove-like characteristics should not be perceived. Color is dark amber to dark brown, and the body should be light to medium in character. Roasted malts are optionally evident in aroma and flavor with a low level of roast malt astringency acceptable when appropriately balanced with malt sweetness. Roast malts may be evident as a cocoa/chocolate or caramel character. Aromatic toffee-like, caramel, or biscuit-like characters may be part of the overall flavor/aroma profile. Diacetyl should not be perceived. Because this style is intended to be served with yeast the character should portray a full yeasty mouthfeel and appear hazy to very cloudy. Chill haze is acceptable. Yeast flavor and aroma should be low to medium but not overpowering the balance and character of malt and hops.', 'https://www.brewersassociation.org/resources/brewers-association-beer-style-guidelines/', '11', 'N', 'bcoe', 'BA', 0, 0, 0, 0, '2015', NULL, NULL),
('062', 'Fruit Wheat Ale or Lager with or without Yeast', 'Hybrid/Mixed Lagers or Ales', '1.036', NULL, '1.004', '1.016', '3.8', '5.0', '10', '35', '2', '10', '1', 'This beer can be made using either ale or lager yeast. It can be brewed with 30 to 75 percent malted wheat. Fruit or fruit extracts contribute flavor and/or aroma. Perceived fruit qualities should be authentic and replicate true fruit complexity as much as possible. Color should reflect a degree of fruit\'s color. Hop rates may be low to medium. Hop characters may be light to moderate in bitterness, flavor and aroma. Fruity-estery aroma and flavor from yeast can be typical but at low levels; however, phenolic, clovelike characteristics should not be perceived. Body should be light to medium in character. Diacetyl should not be perceived. When this style is served with yeast the character should portray a full yeasty mouthfeel and appear hazy to very cloudy. Chill haze is also acceptable. Yeast flavor and aroma should be low to medium but not overpowering the balance and character of malt and hops. Brewer may indicate on the bottle whether the yeast should be intentionally roused or if they prefer that the entry be poured as quietly as possible.', 'https://www.brewersassociation.org/resources/brewers-association-beer-style-guidelines/', '11', 'N', 'bcoe', 'BA', 1, 0, 0, 0, '2015', NULL, NULL),
('063', 'Light American Wheat Ale or Lager without Yeast', 'Hybrid/Mixed Lagers or Ales', '1.036', NULL, '1.004', '1.016', '3.8', '5.0', '10', '35', '2', '10', '1', 'This beer can be made using either ale or lager yeast. It can be brewed with 30 to 75 percent wheat malt, and hop rates may be low to medium. Hop characters may be light to moderate in bitterness, flavor and aroma. A fruity-estery aroma and flavor are typical but at low levels however, phenolic, clove-like characteristics should not be perceived. Appearance can be clear or with chill haze, golden to light amber, and the body should be light to medium in character. Diacetyl should not be perceived. Because this style is packaged and served without yeast, no yeast characters should be evident in mouthfeel, flavor, or aroma.', 'https://www.brewersassociation.org/resources/brewers-association-beer-style-guidelines/', '11', 'N', 'bcoe', 'BA', 0, 0, 0, 0, '2015', NULL, NULL),
('064', 'Light American Wheat Ale or Lager with Yeast', 'Hybrid/Mixed Lagers or Ales', '1.036', NULL, '1.006', '1.018', '3.5', '5.5', '10', '35', '4', '10', '1', 'This beer can be made using either ale or lager yeast. It can be brewed with 30 to 75 percent wheat malt, and hop rates may be low to medium. Hop characters may be light to moderate in bitterness, flavor and aroma. Fruity-estery aroma and flavor are typical but at low levels however, phenolic, clove-like characteristics should not be perceived. Color is usually straw to light amber, and the body should be light to medium in character. Diacetyl should not be perceived. Because this style is served with yeast the character should portray a full yeasty mouthfeel and appear hazy to very cloudy. Chill haze is also acceptable. Yeast flavor and aroma should be low to medium but not overpowering the balance and character of malt and hops. These beers are typically served with the yeast in the bottle, and are cloudy when served.', 'https://www.brewersassociation.org/resources/brewers-association-beer-style-guidelines/', '11', 'N', 'bcoe', 'BA', 0, 0, 0, 0, '2015', NULL, NULL),
('065', 'Ginjo Beer or Sake-Yeast Beer', 'Hybrid/Mixed Lagers or Ales', '1.040', NULL, '1.008', '1.018', '4.2', '7.0', '12', '35', '4', '20', '1', 'A beer brewed with Sake yeast or Sake (koji) enzymes. Color depends on malts used. The unique flavor and aroma of the byproducts of sake yeast and/or koji enzymes should be distinctive and harmonize with the other malt and hop characters. Sake character may best be described as having mild fruitiness and a gentle and mild yeast extract-Vitamin B character. Hop bitterness, flavor and aroma should be low to medium and should harmonize with sake-like characters. High carbonation should be evident and a higher amount of alcohol may be evident. Body and mouth feel will vary depending on base style and original gravity. A slight chill haze is permissible. A very low amount of diacetyl may be perceived.', 'https://www.brewersassociation.org/resources/brewers-association-beer-style-guidelines/', '11', 'N', 'bcoe', 'BA', 1, 0, 0, 0, '2015', NULL, NULL),
('066', 'American-Style Cream Ale or Lager', 'Hybrid/Mixed Lagers or Ales', '1.044', NULL, '1.004', '1.010', '4.2', '5.6', '10', '22', '2', '5', '1', 'Mild, pale, light-bodied ale, made using a warm fermentation (top or bottom) and cold lagering. Hop bitterness and flavor range from very low to low. Hop aroma is often absent. Sometimes referred to as cream ales, these beers are crisp and refreshing. Pale malt character predominates. Caramelized malt character should be absent. A fruity or estery aroma may be perceived. Diacetyl and chill haze should not be perceived. Sulfur character and/or sweet corn-like dimethylsulfide (DMS) should be extremely low or absent from this style of beer.', 'https://www.brewersassociation.org/resources/brewers-association-beer-style-guidelines/', '11', 'N', 'bcoe', 'BA', 0, 0, 0, 0, '2015', NULL, NULL),
('067', 'California Common Beer', 'Hybrid/Mixed Lagers or Ales', '1.045', NULL, '1.010', '1.018', '4.5', '5.6', '35', '45', '8', '15', '1', 'California Common Beer is light amber to amber in color and is medium bodied. There is a noticeable degree of caramel-type malt character in flavor and often in aroma. Hop bitterness impression is medium to medium high and is balanced with a low to medium-low degree of fruity esters and malt character and give an impression of balance and drinkability. Hop flavor and aroma is low to medium-low. California Common Beer is a style of beer brewed with lager yeasts but at ale fermentation temperatures. Diacetyl and chill haze should be absent.', 'https://www.brewersassociation.org/resources/brewers-association-beer-style-guidelines/', '11', 'N', 'bcoe', 'BA', 0, 0, 0, 0, '2015', NULL, NULL),
('068', 'International-Style Pilsener', 'International Styles', '1.044', NULL, '1.008', '1.010', '4.5', '5.3', '17', '30', '3', '4', '1', 'International Pilseners are straw/golden in color and are well attenuated. This medium-bodied beer is often brewed with rice, corn, wheat, or other grain or sugar adjuncts making up part of the mash. Hop bitterness is low to medium. Hop flavor and aroma are low. Residual malt sweetness is low--it does not predominate but may be perceived. Fruity esters and diacetyl should not be perceived. Very low levels of sweet corn-like dimethylsulfide (DMS) character, if perceived, are acceptable. There should be no chill haze.', 'https://www.brewersassociation.org/resources/brewers-association-beer-style-guidelines/', '10', 'N', 'bcoe', 'BA', 0, 0, 0, 0, '2015', NULL, NULL),
('069', 'Dry Lager', 'International Styles', '1.040', NULL, '1.004', '1.008', '4.3', '5.5', '15', '23', '2', '4', '1', 'This straw-colored lager lacks sweetness, is light in body, and is only mildly flavored by malt. Its alcoholic strength may contribute to the overall flavor character. Bitterness is low and carbonation is high. Chill haze, fruity esters, and diacetyl should be absent.', 'https://www.brewersassociation.org/resources/brewers-association-beer-style-guidelines/', '10', 'N', 'bcoe', 'BA', 0, 0, 0, 0, '2015', NULL, NULL),
('070', 'Session Beer', 'Hybrid/Mixed Lagers or Ales', '1.034', NULL, '1.004', '1.010', '4.0', '5.1', '10', '30', '2', '2', '1', 'Any style of beer can be made lower in strength than described in the classic style guidelines. The goal should be to reach a balance between the style\'s character and the lower alcohol content. Drinkability is a character in the overall balance of these beers. Beers in this category must not exceed 4.1% alcohol by weight (5.1% alcohol by volume).', 'https://www.brewersassociation.org/resources/brewers-association-beer-style-guidelines/', '11', 'N', 'bcoe', 'BA', 1, 0, 0, 0, '2015', NULL, NULL),
('071', 'Australasian, Latin American or Tropical-Style Light Lager', 'Other Origin Lagers', '1.038', NULL, '1.006', '1.010', '3.8', '5.0', '9', '18', '2', '5', '1', 'Australasian or Tropical light lagers are very light in color and light bodied. They have no hop flavor or aroma, and hop bitterness is negligibly to moderately perceived. Sugar adjuncts are often used to lighten the body and flavor, sometimes contributing to a slight apple-like-like fruity ester. Sugar, corn, rice, and other cereal grains are used as an adjunct. Chill haze and diacetyl should be absent. Fruity esters should be very low.', 'https://www.brewersassociation.org/resources/brewers-association-beer-style-guidelines/', '09', 'N', 'bcoe', 'BA', 0, 0, 0, 0, '2015', NULL, NULL),
('072', 'Baltic-Style Porter', 'Other Origin Lagers', '1.072', NULL, '1.016', '1.022', '7.5', '9.0', '35', '40', '40', '40', '1', 'A true smooth cold-fermented and cold lagered beer, brewed with lager yeast. Black to very deep ruby/garnet in color. Overall, Baltic Porters have a very smooth lagered character with distinctive caramelized sugars, licorice and chocolate-like character of roasted malts and dark sugars. Roasted dark malts should not contribute bitterness, or astringent roast character. A low degree of smokiness from malt may be evident. Debitterized roast malts are best used for this style. Because of its alcoholic strength, aroma may include gentle (low) lager fruitiness (berries, grapes, plums, not banana; ale-like fruitiness from warm temperature fermentation is not appropriate), complex alcohols, cocoa-like, roast malt (and sometimes coffee-like roast barley, yet not bitter). Hop aroma is very low, though a hint of floral or sweet hop aroma can complement aromatics and flavor without dominance. Baltic Porters are not hop bitter dominated and expressed as low to medium-low. Baltic porters range from having medium to full body complemented with a medium-low to medium level of malty sweetness. No butterscotch-like diacetyl or sweet corn-like dimethylsulfide (DMS) should be apparent in aroma or flavor.', 'https://www.brewersassociation.org/resources/brewers-association-beer-style-guidelines/', '09', 'N', 'bcoe', 'BA', 0, 0, 0, 0, '2015', NULL, NULL),
('073', 'American-Style Dark Lager', 'North American Origin Lagers', '1.040', NULL, '1.008', '1.012', '4.0', '5.5', '14', '20', '14', '25', '1', 'This beer\'s malt aroma and flavor are low but notable. Its color ranges from a very deep copper to a deep, dark brown. It has a clean, light body with discreet contributions from caramel and roasted malts. Non-malt adjuncts are often used, and hop rates are low. Hop bitterness is clean and has a short duration of impact. Hop flavor, and aroma are low. Carbonation is high. Fruity esters, diacetyl, and chill haze should not be perceived.', 'https://www.brewersassociation.org/resources/brewers-association-beer-style-guidelines/', '08', 'N', 'bcoe', 'BA', 0, 0, 0, 0, '2015', NULL, NULL),
('074', 'American-Style Maerzen / Oktoberfest', 'North American Origin Lagers', '1.050', NULL, '1.012', '1.020', '5.3', '5.9', '20', '30', '4', '15', '1', 'The American style of these classic German beers is distinguished by a comparatively greater degree of hop character. In general the style is characterized by a medium body and broad range of color from golden to reddish brown. Sweet maltiness should dominate over clean hop bitterness. The bitterness should not be aggressive or harsh. Malt character should be light-toasted rather than strongly caramel (though a low level of light caramel character is acceptable). Bread or biscuit-like malt character is acceptable in aroma and flavor. Hop aroma and flavor should be notable but at low to medium levels. Fruity esters should not be perceived. Diacetyl and chill haze should not be perceived.', 'https://www.brewersassociation.org/resources/brewers-association-beer-style-guidelines/', '08', 'N', 'bcoe', 'BA', 0, 0, 0, 0, '2015', NULL, NULL),
('075', 'American-Style Malt Liquor', 'North American Origin Lagers', '1.050', NULL, '1.004', '1.010', '6.3', '7.5', '12', '23', '2', '5', '1', 'High in starting gravity and alcoholic strength, this style is somewhat diverse. Some American malt liquors are just slightly stronger than American lagers, while others approach bock strength. Some residual sweetness is perceived. Hop rates are very low, contributing little bitterness and virtually no hop aroma or flavor. Perception of sweet-fruity esters and complex alcohols (though not solvent-like) are acceptable at low levels. Chill haze and diacetyl should not be perceived.', 'https://www.brewersassociation.org/resources/brewers-association-beer-style-guidelines/', '08', 'N', 'bcoe', 'BA', 0, 0, 0, 0, '2015', NULL, NULL),
('076', 'American-Style Amber Lager', 'North American Origin Lagers', '1.042', NULL, '1.010', '1.018', '4.8', '5.4', '18', '30', '6', '14', '1', 'American-style amber lagers are light amber to amber or copper colored. They are medium bodied. There is a noticeable degree of caramel-type malt character in flavor and often in aroma. This is a broad category in which the hop bitterness, flavor, and aroma may be accentuated or may only be present at relatively low levels, yet noticeable. Fruity esters, diacetyl, and chill haze should be absent.', 'https://www.brewersassociation.org/resources/brewers-association-beer-style-guidelines/', '08', 'N', 'bcoe', 'BA', 0, 0, 0, 0, '2015', NULL, NULL),
('077', 'American-Style Pilsener', 'North American Origin Lagers', '1.045', NULL, '1.012', '1.018', '5.0', '6.0', '25', '40', '3', '6', '1', 'This classic and unique pre-Prohibition American-style Pilsener is straw to deep gold in color. Hop bitterness, flavor and aroma are medium to high, and use of noble-type hops for flavor and aroma is preferred. Up to 25 percent corn and/or rice in the grist should be used. Malt flavor and aroma are medium. This is a light-medium to medium-bodied beer. Sweet corn-like dimethylsulfide (DMS), fruity esters and American hop-derived citrus flavors or aromas should not be perceived. Diacetyl is not acceptable. There should be no chill haze. Competition organizers may wish to subcategorize this style into rice and corn subcategories.', 'https://www.brewersassociation.org/resources/brewers-association-beer-style-guidelines/', '08', 'N', 'bcoe', 'BA', 0, 0, 0, 0, '2015', NULL, NULL),
('078', 'American-Style Ice Lager', 'North American Origin Lagers', '1.040', NULL, '1.006', '1.014', '4.8', '6.3', '7', '20', '2', '8', '1', 'This style is slightly higher in alcohol than most other light-colored, American-style lagers. Its body is low to medium and has low residual malt sweetness. It has few or no adjuncts. Color is very pale to golden. Hop bitterness is low but certainly perceptible. Hop aroma and flavor are low. Chill haze, fruity esters, and diacetyl should not be perceived. Typically these beers are chilled before filtration so that ice crystals (which may or may not be removed) are formed. This can contribute to a higher alcohol content (up to 0.5% more).', 'https://www.brewersassociation.org/resources/brewers-association-beer-style-guidelines/', '08', 'N', 'bcoe', 'BA', 0, 0, 0, 0, '2015', NULL, NULL),
('079', 'American-Style Light Lager', 'North American Origin Lagers', '1.024', NULL, '0.992', '1.004', '3.5', '4.4', '3', '10', '2', '10', '1', 'These beers are extremely light straw to light amber in color, light in body, and high in carbonation. They should have a maximum carbohydrate level of 3.0 gm per 12 oz. (356 ml). These beers are characterized by extremely high degree of attenuation (often final gravity is less than 1.000 (0 ºPlato), but with typical American-style light lager alcohol levels. Corn, rice, or other grain adjuncts are often used. Flavor is very light/mild and very dry. Hop flavor, aroma and bitterness are negligible to very low. Very low yeasty flavors and fruity esters are acceptable in aroma and flavor. Chill haze and diacetyl should not be perceived.', 'https://www.brewersassociation.org/resources/brewers-association-beer-style-guidelines/', '08', 'N', 'bcoe', 'BA', 0, 0, 0, 0, '2015', NULL, NULL),
('080', 'American-Style Amber Light Lager', 'North American Origin Lagers', '1.024', NULL, '1.002', '1.008', '3.5', '4.4', '8', '15', '4', '12', '1', 'These beers are pale golden to amber in color, light to medium-light in body, and high in carbonation. Calorie level should not exceed 125 per 12 ounce serving. Corn, rice, or other grain or sugar adjuncts may be used but all malt formulations are also made. Malt and hop flavors are mild yet evident. Hop bitterness is evident and hop aroma may be negligible to evident. Light fruity esters are acceptable. Chill haze and diacetyl should be absent.', 'https://www.brewersassociation.org/resources/brewers-association-beer-style-guidelines/', '08', 'N', 'bcoe', 'BA', 0, 0, 0, 0, '2015', NULL, NULL),
('081', 'American-Style Lager', 'North American Origin Lagers', '1.040', NULL, '1.006', '1.010', '3.8', '5.0', '5', '13', '2', '4', '1', 'Light in body and very light to straw in color, American lagers are very clean and crisp and aggressively carbonated. Flavor components should b e subtle and complex, with no one ingredient dominating the others. Malt sweetness is light to mild. Corn, rice, or other grain or sugar adjuncts are often used. Hop bitterness, flavor and aroma are negligible to very light. Light fruity esters are acceptable. Chill haze and diacetyl should be absent.', 'https://www.brewersassociation.org/resources/brewers-association-beer-style-guidelines/', '08', 'N', 'bcoe', 'BA', 0, 0, 0, 0, '2015', NULL, NULL),
('082', 'German-Style Eisbock', 'European-Germanic Lager', '1.074', NULL, NULL, NULL, NULL, '33.0', NULL, '50', NULL, NULL, '1', 'A stronger version of Doppelbock. Malt character can be very sweet. The body is very full and deep copper to almost black in color. Alcoholic strength is very high. Hop bitterness is subdued. Hop flavor and aroma are absent. Fruity esters may be evident but not overpowering. Typically these beers are brewed by freezing a Doppelbock and removing resulting ice to increase alcohol content. Diacetyl should be absent', 'https://www.brewersassociation.org/resources/brewers-association-beer-style-guidelines/', '07', 'N', 'bcoe', 'BA', 0, 0, 0, 0, '2015', NULL, NULL),
('083', 'Kellerbier (Cellar beer) or Zwickelbier - Lager', 'European-Germanic Lager', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '1', 'Traditional Version of Kellerbier: Unfiltered lagered versions of Germanic lager styles of beer such as Münchner-Style Helles and Dunkel, Dortmunder/European-Style Export, Bohemian-style Pilsener and German-style Pilsener. Kellerbier is noticeably less carbonated. Subtle or low levels of esters may be apparent. This is an unfiltered beer but it may be naturally clear due to settling of yeast during aging. They may or may not be clear. Exhibiting a small amount of yeast haze in the appearance is acceptable. Low to moderately low levels of yeast-generated sulfur compounds in aroma and flavor should be apparent, and low levels of acetaldehyde or other volatiles normally scrubbed during fermentation may or may not be apparent. The sulfur and acetaldehyde characters should contribute positively to the beer drinking experience. There should be no diacetyl detectable. Dry hopping is acceptable. Head retention may not be optimal. Contemporary Version of Kellerbier: Beers that are packaged or on draft which are unfiltered versions of Other Origin Ales styles. These may share many attributes of traditional versions, but are generally fully carbonated, fully lagered, with full head retention and absent of acetaldehyde.', 'https://www.brewersassociation.org/resources/brewers-association-beer-style-guidelines/', '07', 'N', 'bcoe', 'BA', 0, 0, 0, 0, '2015', NULL, NULL),
('084', 'German-Style Heller Bock/Maibock', 'European-Germanic Lager', '1.066', NULL, '1.012', '1.020', '6.0', '8.0', '20', '38', '4', '10', '1', 'The German word helle means light colored, and as such, a heller Bock is light straw to deep golden in color. Maibocks are also light-colored bocks. The sweet malty character should come through in the aroma and flavor. A lightly toasted and/or bready malt character is often evident. Roast or heavy toast/caramel malt character should be absent. Body is medium to full. Hop bitterness should be low, while noble-type hop aroma and flavor may be at low to medium levels. Bitterness increases with gravity. Fruity esters may be perceived at low levels. Diacetyl should be absent. Chill haze should not be perceived.', 'https://www.brewersassociation.org/resources/brewers-association-beer-style-guidelines/', '07', 'N', 'bcoe', 'BA', 0, 0, 0, 0, '2015', NULL, NULL),
('085', 'German-Style Doppelbock', 'European-Germanic Lager', '1.074', NULL, '1.014', '1.020', '6.5', '8.0', '17', '27', '12', '30', '1', 'Malty sweetness is dominant but should not be cloying. Malt character is more reminiscent of fresh and lightly toasted Munich- style malt, more so than caramel or toffee malt character. Some elements of caramel and toffee can be evident and contribute to complexity, but the predominant malt character is an expression of toasted barley malt. Doppelbocks are full bodied and deep amber to dark brown in color. Astringency from roast malts is absent. Alcoholic strength is high, and hop rates increase with gravity. Hop bitterness and flavor should be low and hop aroma absent. Fruity esters are commonly perceived but at low to moderate levels. Diacetyl should be absent', 'https://www.brewersassociation.org/resources/brewers-association-beer-style-guidelines/', '07', 'N', 'bcoe', 'BA', 0, 0, 0, 0, '2015', NULL, NULL),
('086', 'Bamberg-Style Bock Rauchbier', 'European-Germanic Lager', '1.066', NULL, '1.018', '1.024', '6.0', '7.5', '20', '30', '20', '30', '1', 'Bamberg-style Bock Rauchbier should have beech wood smoky characters that range from detectable to prevalent in the aroma and flavor. Smoke character is not harshly phenolic, but rather very smooth, almost rendering a perception of mild sweetness to this style of beer. The Bock beer character should manifest itself as a strong, malty, medium- to full-bodied with moderate hop bitterness that should increase proportionately with the starting gravity. Hop flavor should be low and hop aroma should be very low. Bocks can range in color from deep copper to dark brown. Fruity esters should be minimal. Diacetyl and chill haze should not be perceived.', 'https://www.brewersassociation.org/resources/brewers-association-beer-style-guidelines/', '07', 'N', 'bcoe', 'BA', 0, 0, 0, 0, '2015', NULL, NULL),
('087', 'Traditional German-Style Bock', 'European-Germanic Lager', '1.066', NULL, '1.018', '1.024', '6.3', '7.5', '20', '30', '20', '30', '1', 'Traditional bocks are made with all malt and are strong, malty, medium- to full-bodied, bottom-fermented beers with moderate hop bitterness that should increase proportionately with the starting gravity. Malt character should be a balance of sweetness and toasted/nut-like malt; not caramel. Hop flavor should be low and hop aroma should be very low. Bocks can range in color from deep copper to dark brown. Fruity esters should be minimal. Diacetyl should be absent.', 'https://www.brewersassociation.org/resources/brewers-association-beer-style-guidelines/', '07', 'N', 'bcoe', 'BA', 0, 0, 0, 0, '2015', NULL, NULL),
('088', 'Bamberg-Style Märzen Rauchbier', 'European-Germanic Lager', '1.050', NULL, '1.012', '1.020', '5.3', '5.9', '18', '25', '4', '15', '1', 'Bamberg-style Rauchbier Märzen should have beechwood smoky characters that range from detectable to prevalent in the aroma and flavor. Smoke character is neither harshly phenolic nor acrid, but rather very smooth, almost rendering a perception of mild sweetness to this style of beer. The beer is generally toasted malty sweet and full-bodied with low to medium-low hop bitterness. Noble-type hop flavor is low but may be perceptible. The aroma should strike a balance between malt, hop, and smoke. Fruity esters, diacetyl, and chill haze should not be perceived.', 'https://www.brewersassociation.org/resources/brewers-association-beer-style-guidelines/', '07', 'N', 'bcoe', 'BA', 0, 0, 0, 0, '2015', NULL, NULL),
('089', 'Bamberg-Style Helles Rauchbier', 'European-Germanic Lager', '1.044', NULL, '1.008', '1.012', '4.5', '5.5', '18', '25', '4', '6', '1', 'Helles Rauchbier should have beech wood smoky characters that range from detectable to prevalent in the aroma and flavor. Smoke character is not harshly phenolic, but rather very smooth, almost rendering a perception of mild sweetness to this style of beer. This is a medium-bodied, smoke and malt-emphasized beer, with malt character often balanced with low levels of yeast produced sulfur compounds (character). This beer should be perceived as having low bitterness. Certain renditions of this beer style approach a perceivable level of hop flavor (note: hop flavor does not imply hop bitterness) and character but it is essentially balanced with malt character to retain its style identity. Helles Rauchbier malt character is reminiscent of freshly and very lightly toasted sweet malted barley. There should not be any caramel character. Color is light straw to golden. Noble-type hop flavor is low but may be perceptible. The aroma should strike a balance between malt, hop, and smoke. Fruity esters, diacetyl, and chill haze should not be perceived.', 'https://www.brewersassociation.org/resources/brewers-association-beer-style-guidelines/', '07', 'N', 'bcoe', 'BA', 0, 0, 0, 0, '2015', NULL, NULL),
('090', 'European-Style Dark / Münchner Dunkel', 'European-Germanic Lager', '1.048', NULL, '1.014', '1.018', '4.5', '5.0', '16', '25', '15', '20', '1', 'These light brown to dark brown beers have a pronounced malty aroma and flavor that dominates over the clean, crisp, moderate hop bitterness. This beer does not offer an overly sweet impression, but rather a mild balance between malt sweetness, hop bitterness and light to moderate mouthfeel. A classic Münchner Dunkel should have chocolate-like, roast malt, bread-like or biscuit-like aroma that comes from the use of Munich dark malt. Chocolate or roast malts can be used, but the percentage used should be minimal. Noble-type hop flavor and aroma should be low but perceptible. Diacetyl should not be perceived. Ale-like fruity esters and chill haze should not be perceived.', 'https://www.brewersassociation.org/resources/brewers-association-beer-style-guidelines/', '07', 'N', 'bcoe', 'BA', 0, 0, 0, 0, '2015', NULL, NULL),
('091', 'German-Style Schwarzbier', 'European-Germanic Lager', '1.044', NULL, '1.010', '1.016', '3.8', '5.0', '22', '30', '25', '30', '1', 'These very dark brown to black beers have a mild roasted malt character without the associated bitterness. This is not a fullbodied beer, but rather a moderate body gently enhances malt flavor and aroma with low to moderate levels of sweetness. Hop bitterness is low to medium in character. Noble-type hop flavor and aroma should be low but perceptible. There should be no fruity esters. Diacetyl should not be perceived.', 'https://www.brewersassociation.org/resources/brewers-association-beer-style-guidelines/', '07', 'N', 'bcoe', 'BA', 0, 0, 0, 0, '2015', NULL, NULL),
('092', 'German-Style Oktoberfest / Wiesen (Meadow)', 'European-Germanic Lager', '1.048', NULL, '1.010', '1.014', '5.0', '6.0', '23', '29', '3', '5', '1', 'Today\'s Oktoberfest beers are characterized by a medium body and light, golden color. Sweet maltiness is mild with an equalizing balance of clean, hop bitterness. Hop aroma and flavor should be low but notable. Ale-like fruity esters should not be perceived. Diacetyl and chill haze should not be perceived. Similar or equal to Dortmunder/European-Style Export', 'https://www.brewersassociation.org/resources/brewers-association-beer-style-guidelines/', '07', 'N', 'bcoe', 'BA', 0, 0, 0, 0, '2015', NULL, NULL),
('093', 'Vienna-Style Lager', 'European-Germanic Lager', '1.046', NULL, '1.012', '1.018', '4.8', '5.4', '22', '28', '12', '16', '1', 'Beers in this category are reddish brown or copper colored. They are medium in body. The beer is characterized by malty aroma and slight malt sweetness. The malt aroma and flavor should have a notable degree of toasted and/or slightly roasted malt character. Hop bitterness is clean and crisp. Noble-type hop aromas and flavors should be low or mild. Diacetyl, chill haze and ale-like fruity esters should not be perceived.', 'https://www.brewersassociation.org/resources/brewers-association-beer-style-guidelines/', '07', 'N', 'bcoe', 'BA', 0, 0, 0, 0, '2015', NULL, NULL),
('094', 'German-Style Märzen', 'European-Germanic Lager', '1.050', NULL, '1.012', '1.020', '5.3', '5.9', '18', '25', '4', '15', '1', 'Märzens are characterized by a medium body and broad range of color. They can range from golden to reddish orange. Sweet maltiness should dominate slightly over a clean hop bitterness. Malt character should be light-toasted rather than strongly caramel (though a low level of light caramel character is acceptable). Bread or biscuit-like malt character is acceptable in aroma and flavor. Hop aroma and flavor should be low but notable. Ale-like fruity esters should not be perceived. Diacetyl and chill haze should not be perceived.', 'https://www.brewersassociation.org/resources/brewers-association-beer-style-guidelines/', '07', 'N', 'bcoe', 'BA', 0, 0, 0, 0, '2015', NULL, NULL),
('095', 'Münchner (Munich)-Style Helles', 'European-Germanic Lager', '1.044', NULL, '1.008', '1.012', '4.5', '5.5', '18', '25', '4', '6', '1', 'This beer should be perceived as having low bitterness. It is a medium-bodied, malt-emphasized beer with malt character often balanced with low levels of yeast produced sulfur compounds (character). Certain renditions of this beer style have a perceivable level of hop flavor (note: hop flavor does not imply hop bitterness) and character but it is essentially balanced with malt character to retain its style identity. Malt character is sometimes bread-like yet always reminiscent of freshly and very lightly toasted malted barley. There should not be any caramel character. Color is light straw to golden. Fruity esters and diacetyl should not be perceived. There should be no chill haze.', 'https://www.brewersassociation.org/resources/brewers-association-beer-style-guidelines/', '07', 'N', 'bcoe', 'BA', 0, 0, 0, 0, '2015', NULL, NULL),
('096', 'Dortmunder / European-Style Export', 'European-Germanic Lager', '1.048', NULL, '1.010', '1.014', '5.0', '6.0', '23', '29', '3', '6', '1', 'Dortmunder has medium hop bitterness. Hop flavor and aroma from noble hops are perceptible but low. Sweet malt flavor can be low and should not be caramel-like. The color of this style is straw to deep golden. The body will be medium bodied. Fruity esters, chill haze, and diacetyl should not be perceived.', 'https://www.brewersassociation.org/resources/brewers-association-beer-style-guidelines/', '07', 'N', 'bcoe', 'BA', 0, 0, 0, 0, '2015', NULL, NULL),
('097', 'German-Style Leichtbier', 'European-Germanic Lager', '1.026', NULL, '1.006', '1.010', '2.5', '3.6', '16', '24', '2', '4', '1', 'These beers are very light in body and color. Malt sweetness is perceived at low to medium levels, while hop bitterness character is perceived at medium levels. Hop flavor and aroma may be low to medium. These beers should be clean with no perceived fruity esters or diacetyl. Very low levels of sulfur related compounds acceptable. Chill haze is not acceptable.', 'https://www.brewersassociation.org/resources/brewers-association-beer-style-guidelines/', '07', 'N', 'bcoe', 'BA', 0, 0, 0, 0, '2015', NULL, NULL),
('098', 'Bohemian-Style Pilsener', 'European-Germanic Lager', '1.044', NULL, '1.014', '1.020', '4.0', '5.0', '30', '45', '3', '7', '1', 'Traditional Bohemian Pilseners are medium bodied, and they can be as dark as a light amber color. This style balances moderate bitterness and noble-type hop aroma and flavor with a malty, slightly sweet, medium body. Extremely low levels of diacetyl and low levels of sweet corn-like dimethylsulfide (DMS) character, if perceived, are characteristic of this style and both may accent malt aroma. A toasted-, biscuit-like, bready malt character along with low levels of sulfur compounds may be evident. There should be no chill haze. Its head should be dense and rich.', 'https://www.brewersassociation.org/resources/brewers-association-beer-style-guidelines/', '07', 'N', 'bcoe', 'BA', 0, 0, 0, 0, '2015', NULL, NULL),
('099', 'Australian-Style Pale Ale', 'Other Origin Ales', '1.040', NULL, '1.006', '1.012', '4.2', '6.2', '20', '40', '5', '14', '1', 'Australian-Style Pale Ales are light amber to light brown. Chill or hop haze may be evident. Hop aroma is often reminiscent of tropical fruit such as mango, passion fruit and other tropical fruit character. Intensity can be low to medium-high. Malt character has a perceived low to medium caramel-candy sweetness. Hop flavor is aligned with aroma; tropical fruit such as mango, passion fruit and other tropical fruit character. Intensity can be low to mediumhigh. Hop bitterness is low to medium. Fruity-ester aroma should be perceived. Diacetyl should be very low if present. DMS aroma should not be present. Body is low to medium.', 'https://www.brewersassociation.org/resources/brewers-association-beer-style-guidelines/', '06', 'N', 'bcoe', 'BA', 0, 0, 0, 0, '2015', NULL, NULL),
('100', 'German-Style Pilsener', 'European-Germanic Lager', '1.044', NULL, '1.006', '1.012', '4.0', '5.0', '25', '40', '3', '4', '1', 'A classic German Pilsener is very light straw or golden in color and well hopped. Perception of hop bitterness is medium to high. Noble-type hop aroma and flavor are moderate and quite obvious. It is a well-attenuated, medium-light bodied beer, but a malty residual sweetness can be perceived in aroma and flavor. Very low levels of sweet corn-like dimethylsulfide (DMS) character are below most beer drinkers\' taste thresholds and are usually not detectable except to the trained or sensitive palate. Other fermentation or hop related sulfur compounds, when perceived at low levels, may be characteristic of this style. Fruity esters and diacetyl should not be perceived. There should be no chill haze. Its head should be dense and rich.', 'https://www.brewersassociation.org/resources/brewers-association-beer-style-guidelines/', '07', 'N', 'bcoe', 'BA', 0, 0, 0, 0, '2015', NULL, NULL),
('101', 'International-Style Pale Ale', 'Other Origin Ales', '1.044', NULL, '1.008', '1.014', '4.5', '5.5', '30', '42', '6', '14', '1', 'International-style pale ales range from deep golden to copper in color. The style is characterized by wide range of hop characters unlike fruity, floral and citrus-like American-variety hop character and unlike earthy, herbal English-variety hop character. Moderate to high hop bitterness, flavor, and aroma is evident. International pale ales have medium body and low to medium maltiness. Low caramel character is allowable. Fruity-ester flavor and aroma should be moderate to strong. Diacetyl should be absent or present at very low levels. Chill haze is allowable at cold temperatures.', 'https://www.brewersassociation.org/resources/brewers-association-beer-style-guidelines/', '06', 'N', 'bcoe', 'BA', 0, 0, 0, 0, '2015', NULL, NULL),
('102', 'French & Belgian-Style Saison', 'Belgian And French Origin Ales', '1.055', NULL, '1.004', '1.016', '4.5', '8.5', '20', '40', '4', '14', '1', 'Beers in this category are golden to deep amber in color. There may be quite a variety of characters within this style. Generally: They are light to medium in body. Malt aroma is low to medium-low. Esters are medium to high in aroma, while, complex alcohols, herbs, spices, low Brettanomyces character and even clove and smoke-like phenolics may or may not be evident in the overall balanced beer. Hop aroma and flavor may be at low to medium levels. Malt flavor is low but provides foundation for the overall balance. Hop bitterness is moderate to moderately assertive. Herb and/or spice flavors, including black pepper-like notes, may or may not be evident. Fruitiness from fermentation is generally in character. A balanced small amount of sour or acidic flavors is acceptable when in balance with other components. Earthy, cellar-like, musty aromas are okay. Diacetyl should not be perceived. Chill or slight yeast haze is okay. Often bottle conditioned with some yeast character and high carbonation. French & Belgian-Style Saison may have Brettanomyces characters that are slightly acidity, fruity, horsey, goaty and/or leather-like.', 'https://www.brewersassociation.org/resources/brewers-association-beer-style-guidelines/', '05', 'N', 'bcoe', 'BA', 0, 0, 0, 0, '2015', NULL, NULL),
('103', 'Other Belgian-Style Ales', 'Belgian And French Origin Ales', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '1', 'Recognizing the uniqueness and traditions of several other styles of Belgian Ales, the beers entered in this category will be assessed on the merits that they do not fit existing style guidelines and information that the brewer provides explaining the history and tradition of the style. Balance of character is a key component when assessing these beers. Barrel or wood-aged entries in competitions may be directed to other categories by competition director. In competitions the brewer must provide the historical or regional tradition of the style, or his interpretation of the style, in order to be assessed properly by the judges.', 'https://www.brewersassociation.org/resources/brewers-association-beer-style-guidelines/', '05', 'N', 'bcoe', 'BA', 1, 0, 0, 0, '2015', NULL, NULL),
('104', 'French-Style Bière de Garde', 'Belgian And French Origin Ales', '1.060', NULL, '1.012', '1.024', '4.5', '8.0', '20', '30', '8', '16', '1', 'Beers in this category are golden to deep copper or light brown in color. They are light to medium in body. This style of beer is characterized by a toasted malt aroma, slight malt sweetness in flavor, and low to medium hop bitterness. Noble-type hop aromas and flavors should be low to medium. Fruity esters can be light to medium in intensity. Flavor of alcohol is evident. Earthy, cellarlike, musty aromas are okay. Diacetyl should not be perceived but chill haze is okay. Often bottle conditioned with some yeast character. French-Style Biére de Garde may have Brettanomyces characters that are slightly acidity, fruity, horsey, goaty and/or leather-like.', 'https://www.brewersassociation.org/resources/brewers-association-beer-style-guidelines/', '05', 'N', 'bcoe', 'BA', 0, 0, 0, 0, '2015', NULL, NULL),
('105', 'Belgian-Style Table Beer', 'Belgian And French Origin Ales', '1.008', NULL, '1.004', '1.034', '0.5', '3.5', '5', '15', '5', '50', '1', 'These ales and lagers are very low in alcohol and historically in Belgium enjoyed with meals by both adults and children. Pale to very dark brown in color. Additions of caramel coloring are sometimes employed to adjust color. They are light bodied with relatively low carbonation with limited aftertaste. The mouth feel is light to moderate, though higher than one might anticipate, usually because of unfermented sugars/malt sugars. Malted barley, wheat and rye may be used as well as unmalted wheat, rye, oats and corn. A mild malt character could be evident. Aroma/Flavor hops are most commonly used to employ a flavor balance that is only low in bitterness. Traditional versions do not use artificial sweeteners nor are they excessively sweet. More modern versions of this beer incorporate sweeteners such as sugar and saccharine added post fermentation to sweeten the palate and add to a perception of smoothness. Spices (such as orange and lemon peel, as well as coriander) may be added in barely perceptible amounts, but this is not common. Diacetyl should not be perceived. Competition directors may choose to break out subcategories of Traditional and Modern.', 'https://www.brewersassociation.org/resources/brewers-association-beer-style-guidelines/', '05', 'N', 'bcoe', 'BA', 0, 0, 0, 0, '2015', NULL, NULL),
('106', 'Belgian-Style Fruit Lambic', 'Belgian And French Origin Ales', '1.040', NULL, '1.008', '1.016', '5.6', '8.6', '15', '21', NULL, NULL, '1', 'These beers, also known by the names framboise, kriek, peche, cassis, etc., are characterized by fruit flavors and aromas. The color reflects the choice of fruit. Sourness is an important part of the flavor profile, though sweetness may compromise the intensity. These flavored lambic beers may be very dry or mildly sweet and range from a dry to a full-bodied mouthfeel. Characteristic horsey, goaty, leathery and phenolic character evolved from Brettanomyces yeast is often present at moderate levels. Vanillin and other woody flavors should not be evident. Versions of this beer made outside of the Brussels area of Belgium cannot be true lambics. These versions are said to be \"lambic-style\" and may be made to resemble many of the beers of true origin. Historically, traditional lambics are dry and completely attenuated, exhibiting no residual sweetness either from malt, sugar, fruit or artificial sweeteners. Some versions often have a degree of sweetness, contributed by fruit sugars, other sugars or artificial sweeteners. Competition organizers may choose to subcategorize this style into A) Traditional and B) Sweet. Artificial sweeteners are sometimes used in some brands.', 'https://www.brewersassociation.org/resources/brewers-association-beer-style-guidelines/', '05', 'N', 'bcoe', 'BA', 0, 0, 0, 0, '2015', NULL, NULL),
('107', 'Belgian-Style Gueuze Lambic', 'Belgian And French Origin Ales', '1.044', NULL, '1.000', '1.010', '6.8', '8.6', '11', '23', '6', '13', '1', 'Old lambic is blended with newly fermenting young lambic to create this special style of lambic. Gueuze is always referrmented in the bottle. These unflavored blended and secondary fermented lambic beers may be very dry or mildly sweet and are characterized by intense fruity-estery, sour, and acidic aromas and flavors. These pale beers are brewed with unmalted wheat, malted barley, and stale, aged hops. Sweet malt characters are not perceived. They are very low in hop bitterness. Diacetyl should be absent. Characteristic horsey, goaty, leathery and phenolic character evolved from Brettanomyces yeast is often present at moderate levels. Cloudiness is acceptable. These beers are quite dry and light bodied. Vanillin and other wood-derived flavors should not be evident. Versions of this beer made outside of the Brussels area of Belgium cannot be true lambics. These versions are said to be \"lambic-style\" and may be made to resemble many of the beers of true origin. Historically, traditional gueuze lambics are dry and completely attenuated, exhibiting no residual sweetness either from malt, sugar or artificial sweeteners. Some versions often have a degree of sweetness, contributed by sugars or artificial sweeteners. Competition organizers may choose to subcategorize this style into A) Traditional and B) Sweet. Artificial sweeteners are sometimes used in some brands.', 'https://www.brewersassociation.org/resources/brewers-association-beer-style-guidelines/', '05', 'N', 'bcoe', 'BA', 0, 0, 0, 0, '2015', NULL, NULL),
('108', 'Belgian-Style Lambic', 'Belgian And French Origin Ales', '1.047', NULL, '1.000', '1.010', '6.2', '8.1', '11', '23', '6', '13', '1', 'Unblended, naturally and spontaneously fermented lambic is intensely estery, sour, and sometimes, but not necessarily, acetic flavored. Low in carbon dioxide, these hazy beers are brewed with unmalted wheat and malted barley. Sweet malt characters are not perceived. They are very low in hop bitterness. Cloudiness is acceptable. These beers are quite dry and light bodied. Characteristic horsey, goaty, leathery and phenolic character evolved from Brettanomyces yeast is often present at moderate levels. Versions of this beer made outside of the Brussels area of Belgium cannot be true lambics. These versions are said to be \"lambicstyle\" and may be made to resemble many of the beers of true origin. Vanillin and other wood-derived flavors should not be evident. Historically, traditional lambic is dry and completely attenuated, exhibiting no residual sweetness either from malt, sugar or artificial sweeteners. Sweet versions may be created through addition of sugars or artificial sweeteners. Competition organizers may choose to subcategorize this style into A) Traditional and B) Sweet. Artificial sweeteners are sometimes used in some brands.', 'https://www.brewersassociation.org/resources/brewers-association-beer-style-guidelines/', '05', 'N', 'bcoe', 'BA', 0, 0, 0, 0, '2015', NULL, NULL),
('109', 'Belgian-Style White (or Wit) / Belgian-Style Wheat', 'Belgian And French Origin Ales', '1.044', NULL, '1.006', '1.010', '4.8', '5.2', '10', '17', '2', '4', '1', 'Belgian white ales are very pale in color and are brewed using unmalted wheat and malted barley and are spiced with coriander and orange peel. Coriander and light orange peel aroma should be perceived as such or as an unidentified spiciness. Phenolic spiciness and yeast flavors may be evident at mild levels. These beers are traditionally bottle conditioned and served cloudy. An unfiltered starch and yeast haze should be part of the appearance. The low to medium body should have some degree of creaminess from wheat starch. The style is further characterized by the use of noble-type hops to achieve low hop bitterness and little to no apparent hop flavor. This beer has no diacetyl and a low to medium fruity-ester level. Mild acidity is appropriate.', 'https://www.brewersassociation.org/resources/brewers-association-beer-style-guidelines/', '05', 'N', 'bcoe', 'BA', 0, 0, 0, 0, '2015', NULL, NULL),
('110', 'Belgian-Style Dark Strong Ale', 'Belgian And French Origin Ales', '1.064', NULL, '1.012', '1.024', '7.0', '11.0', '20', '50', '9', '35', '1', 'Belgian dark strong ales are amber to dark brown in color. Often, though not always, brewed with dark Belgian \"candy\" sugar, these beers can be well attenuated, ranging from medium to full-bodied. The perception of hop bitterness is low to medium, with hop flavor and aroma also in this range. Fruity complexity along with the soft flavors of roasted malts add distinct character. The alcohol strength of these beers can often be deceiving to the senses. The intensity of malt character can be rich, creamy, and sweet with intensities ranging from medium to high. Very little or no diacetyl is perceived. Herbs and spices are sometimes used to delicately flavor these strong ales. Low levels of phenolic spiciness from yeast byproducts may also be perceived. Chill haze is allowable at cold temperatures.', 'https://www.brewersassociation.org/resources/brewers-association-beer-style-guidelines/', '05', 'N', 'bcoe', 'BA', 0, 0, 0, 0, '2015', NULL, NULL),
('111', 'Belgian-Style Pale Ale', 'Belgian And French Origin Ales', '1.044', NULL, '1.008', '1.014', '4.0', '6.0', '20', '30', '6', '12', '1', 'Belgian-style pale ales are characterized by low but noticeable hop bitterness, flavor, and aroma. Light to medium body and low malt aroma are typical. They are light amber to deep amber in color. Noble-type hops are commonly used. Low to medium fruity esters are evident in aroma and flavor. Low levels of phenolic spiciness from yeast byproducts may be perceived. Low caramel or toasted malt flavor is okay. Diacetyl should not be perceived. Chill haze is allowable at cold temperatures.', 'https://www.brewersassociation.org/resources/brewers-association-beer-style-guidelines/', '05', 'N', 'bcoe', 'BA', 0, 0, 0, 0, '2015', NULL, NULL),
('112', 'Belgian-Style Pale Strong Ale', 'Belgian And French Origin Ales', '1.064', NULL, '1.012', '1.024', '7.0', '11.0', '20', '50', '4', '10', '1', 'Belgian pale strong ales are pale to golden in color with relatively light body for a beer of its alcoholic strength. Often brewed with light colored Belgian \"candy\" sugar, these beers are well attenuated. The perception of hop bitterness is medium-low to medium -high, with hop flavor and aroma also in this range. These beers are highly attenuated and have a perceptively deceiving high alcoholic character-being light to medium bodied rather than full bodied. The intensity of malt character should be low to medium, often surviving along with a complex fruitiness. Very little or no diacetyl is perceived. Herbs and spices are sometimes used to delicately flavor these strong ales. Low levels of phenolic spiciness from yeast byproducts may also be perceived. Chill haze is allowable at cold temperatures.', 'https://www.brewersassociation.org/resources/brewers-association-beer-style-guidelines/', '05', 'N', 'bcoe', 'BA', 0, 0, 0, 0, '2015', NULL, NULL),
('113', 'Belgian-Style Quadrupel', 'Belgian And French Origin Ales', '1.084', NULL, '1.014', '1.020', '9.0', '14.0', '25', '50', '8', '20', '1', 'Quadrupels or \"Quads\" are characterized by the immense presence of alcohol and balanced flavor, bitterness and aromas. Its color is deep amber to rich chestnut/garnet brown. Often characterized by a mousse-like dense, sometimes amber head will top off a properly poured and served quad. Complex fruity aroma and flavor emerge reminiscent of raisins, dates, figs, grapes, plums often accompanied with a hint of winy character. Caramel, dark sugar and malty sweet flavors and aromas can be intense, not cloying, while complementing fruitiness. Though well attenuated it usually has a full, creamy body. Hop characters do not dominate; low to low-medium bitterness is perceived. Perception of alcohol can be extreme. Clove-like phenolic flavor and aroma should not be evident. Chill haze is acceptable at low serving temperatures. Diacetyl and DMS should not be perceived. Well balanced with savoring/sipping drinkability. Oxidative character if evident in aged Quads should be mild and pleasant.', 'https://www.brewersassociation.org/resources/brewers-association-beer-style-guidelines/', '05', 'N', 'bcoe', 'BA', 0, 0, 0, 0, '2015', NULL, NULL),
('114', 'Belgian-Style Blonde Ale', 'Belgian And French Origin Ales', '1.054', NULL, '1.008', '1.014', '6.0', '7.8', '15', '30', '4', '7', '1', 'Belgian-style blond ales are characterized by low yet evident hop bitterness, flavor, and sometimes aroma. Light to medium body and low malt aroma with a sweet, spiced and a low to medium fruity-ester character orchestrated in flavor and aroma. Sugar may be used to lighten perceived body. They are blonde to golden in color. Noble-type hops are commonly used. Low levels of phenolic spiciness from yeast byproducts may be perceived. Diacetyl should not be perceived. Acidic character should not be present. Chill haze is allowable at cold temperatures.', 'https://www.brewersassociation.org/resources/brewers-association-beer-style-guidelines/', '05', 'N', 'bcoe', 'BA', 0, 0, 0, 0, '2015', NULL, NULL),
('115', 'Belgian-Style Tripel', 'Belgian And French Origin Ales', '1.070', NULL, '1.010', '1.018', '7.0', '10.0', '20', '45', '4', '9', '1', 'Tripels are often characterized by a complex, sometimes mild spicy character. Clove-like phenolic flavor and aroma may be evident at extremely low levels. Yeast-generated fruity esters, including banana, are also common, but not necessary. These pale/light-colored ales may finish sweet, though any sweet finish should be light. The beer is characteristically medium and clean in body with an equalizing hop/malt balance and a perception of medium to medium high hop bitterness. Traditional Belgian Tripels are often well attenuated. Brewing sugar may be used to lighten the perception of body. Its sweetness will come from very pale malts. There should not be character from any roasted or dark malts. Low hop flavor is acceptable. Alcohol strength and flavor should be perceived as evident. Head retention is dense and mousse-like. Chill haze is acceptable at low serving temperatures. Traditional Tripels are bottle conditioned, may exhibit slight yeast haze but the yeast should not be intentionally roused. Oxidative character if evident in aged Tripels should be mild and pleasant.', 'https://www.brewersassociation.org/resources/brewers-association-beer-style-guidelines/', '05', 'N', 'bcoe', 'BA', 0, 0, 0, 0, '2015', NULL, NULL),
('116', 'Belgian-Style Flanders Oud Bruin or Oud Red Ales', 'Belgian And French Origin Ales', '1.044', NULL, '1.008', '1.016', '4.8', '6.5', '15', '25', '12', '20', '1', 'This light- to medium-bodied deep copper to brown ale is characterized by a slight to strong lactic sourness, and with \"Reds\" sometimes a balanced degree of acetic acid. Brettanomyces produced flavors and aromas are not part of character. A fruity-estery character which is often cherry-like is apparent with no hop flavor or aroma. Flanders brown ales have low to medium bitterness and a cocoa-like character from roast malt. Roasted malt character in aroma and flavor is acceptable at low levels. A very low degree of malt sweetness may be present and in balance with the acidity produced by Lactobacillus activity. Oak-like or woody characters may be pleasantly integrated into overall palate. Chill haze is acceptable at low serving temperatures. Some versions may be more highly carbonated and, when bottle conditioned, may appear cloudy (yeast) when served. These final beers are often blended old with new before packaging in order to create the brewer\'s intended balance of characters.', 'https://www.brewersassociation.org/resources/brewers-association-beer-style-guidelines/', '05', 'N', 'bcoe', 'BA', 0, 0, 0, 0, '2015', NULL, NULL),
('117', 'Kellerbier (Cellar beer) or Zwickelbier - Ale', 'German Origin Ales', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '1', 'These beers are unfiltered German-style Altbier and Kölsch. They are packaged and/or served intentionally with low to moderate amounts of yeast. Products may be filtered and again dosed with yeast in the package, manifesting themselves as bottle conditioned beers or unfiltered beer with yeast present. They will most likely not be clear, and may appear slightly hazy to moderately cloudy. Yeast character, flavor and aroma are desirable, yet should be low to medium but not overpowering the balance and character of malt and hops. Low to moderately low levels of yeast-generated sulfur containing compounds should be apparent in aroma and flavor, and low levels of acetaldehyde or other volatiles normally removed during fermentation may or may not be apparent. The sulfur and acetaldehyde characters should contribute positively to the beer drinking experience. Head retention may not be optimal. The brewer must indicate the classic style on which the entry is based to allow for accurate judging. Beer entries not accompanied by this information will be at a disadvantage during evaluation.', 'https://www.brewersassociation.org/resources/brewers-association-beer-style-guidelines/', '04', 'N', 'bcoe', 'BA', 1, 0, 0, 0, '2015', NULL, NULL),
('118', 'Belgian-Style Dubbel', 'Belgian And French Origin Ales', '1.060', NULL, '1.012', '1.016', '6.3', '7.5', '20', '30', '16', '36', '1', 'This medium-bodied, red to dark brown colored ale has a malty sweetness and chocolate-like caramel aroma. A light hop flavor and/or aroma is acceptable. Dubbels are also characterized by low-medium to medium bitterness. No diacetyl is acceptable. Yeastgenerated fruity esters (especially banana) are appropriate at low levels. Head retention is dense and mousse-like. Chill haze is acceptable at low serving temperatures. Often bottle conditioned a slight yeast haze and flavor may be evident.', 'https://www.brewersassociation.org/resources/brewers-association-beer-style-guidelines/', '05', 'N', 'bcoe', 'BA', 0, 0, 0, 0, '2015', NULL, NULL),
('119', 'Bamberg-Style Weiss (Smoke) Rauchbier (Dunkel or Helles)', 'German Origin Ales', '1.047', NULL, '1.008', '1.016', '4.9', '5.5', '10', '15', '4', '18', '1', 'Bamberg-style Weiss Rauchbier should have smoky characters that range from detectable to prevalent in the aroma and flavor. Smoke character is not harshly phenolic, but rather very smooth, almost rendering a perception of mild sweetness to this style of beer. The aroma and flavor of a Weissbier with yeast is decidedly fruity and phenolic. The phenolic characteristics are often described as clove- or nutmeg-like and can be smoky or even vanilla-like. Banana-like esters are often present. These beers are made with at least 50 percent malted wheat, and hop rates are quite low. Hop flavor and aroma are absent. Weissbier is well attenuated and very highly carbonated and a medium- to full-bodied beer. The color is very pale to very dark amber. Darker (dunkel) styles should have a detectable degree of roast malt in the balance without being robust in overall character. Because yeast is present, the beer will have yeast flavor and a characteristically fuller mouthfeel and may be appropriately very cloudy. No diacetyl should be perceived.', 'https://www.brewersassociation.org/resources/brewers-association-beer-style-guidelines/', '04', 'N', 'bcoe', 'BA', 0, 0, 0, 0, '2015', NULL, NULL),
('120', 'German-Style Altbier', 'German Origin Ales', '1.044', NULL, '1.008', '1.014', '4.3', '5.5', '25', '52', '11', '19', '1', 'German-Style Altbiers are copper to dark brown ales, originally from the Düsseldorf area. No chill haze should be perceived. A variety of malts including wheat may be used to produce medium-low to medium malt aroma. Fruityester aroma can be low. No diacetyl aroma should be perceived. Hop aroma is low to medium. A variety of malts including wheat may be used to produce medium-low to medium level malty flavor. Hop flavor is low to medium. Hop bitterness is medium to very high (although the 25 to 35 IBU range is more normal for the majority of Altbiers from Düsseldorf). Fruity-ester flavors can be low. No diacetyl should be perceived. The overall impression is clean, crisp, and flavorful often with a dry finish. Body is medium.', 'https://www.brewersassociation.org/resources/brewers-association-beer-style-guidelines/', '04', 'N', 'bcoe', 'BA', 0, 0, 0, 0, '2015', NULL, NULL),
('121', 'South German-Style Weizenbock / Weissbock', 'German Origin Ales', '1.066', NULL, '1.016', '1.028', '6.9', '9.3', '15', '35', '5', '30', '1', 'This style can be either pale or dark (golden to dark brown in color) and has a high starting gravity and alcohol content. The malty sweetness of a Weizenbock is balanced with a clove-like phenolic and fruity-estery banana element to produce a wellrounded aroma and flavor. As is true with all German wheat beers, hop bitterness is low and carbonation is high. Hop flavor and aroma are absent. It has a medium to full body. If dark, a mild roast malt character should emerge in flavor and to a lesser degree in the aroma. If this is served with yeast the beer may be appropriately very cloudy. No diacetyl should be perceived.', 'https://www.brewersassociation.org/resources/brewers-association-beer-style-guidelines/', '04', 'N', 'bcoe', 'BA', 0, 0, 0, 0, '2015', NULL, NULL),
('122', 'South German-Style Bernsteinfarbenes Weizen / Weissbier', 'German Origin Ales', '1.048', NULL, '1.008', '1.016', '4.8', '5.4', '10', '15', '9', '13', '1', 'The German word Bernsteinfarben means amber colored, and as such, a Bernsteinfarbenes Weizen is dark yellow to amber in color. This beer style is characterized by a distinct sweet maltiness and caramel or bready character from the use of medium colored malts. Estery and phenolic elements of this Weissbier should be evident but subdued. Bernsteinfarbenes Weissbier is well attenuated and very highly carbonated, and hop bitterness is low. Hop flavor and aroma are absent. The percentage of wheat malt is at least 50 percent. If this is served with yeast, the beer may be appropriately very cloudy. No diacetyl should be perceived.', 'https://www.brewersassociation.org/resources/brewers-association-beer-style-guidelines/', '04', 'N', 'bcoe', 'BA', 0, 0, 0, 0, '2015', NULL, NULL),
('123', 'South German-Style Dunkel Weizen / Dunkel Weissbier', 'German Origin Ales', '1.048', NULL, '1.008', '1.016', '4.8', '5.4', '10', '15', '10', '19', '1', 'This beer style is characterized by a distinct sweet maltiness and a chocolate-like character from roasted malt. Estery and phenolic elements of this Weissbier should be evident but subdued. Color can range from copper-brown to dark brown. Dunkel Weissbier is well attenuated and very highly carbonated, and hop bitterness is low. Hop flavor and aroma are absent. Usually dark barley malts are used in conjunction with dark cara or color malts, and the percentage of wheat malt is at least 50 percent. If this is served with yeast, the beer may be appropriately very cloudy. No diacetyl should be perceived.', 'https://www.brewersassociation.org/resources/brewers-association-beer-style-guidelines/', '04', 'N', 'bcoe', 'BA', 0, 0, 0, 0, '2015', NULL, NULL),
('124', 'South German-Style Hefeweizen / Hefeweissbier', 'German Origin Ales', '1.047', NULL, '1.008', '1.016', '4.9', '5.5', '10', '15', '3', '9', '1', 'The aroma and flavor of a Weissbier with yeast is decidedly fruity and phenolic. The phenolic characteristics are often described as clove-, nutmeg-like, mildly smoke-like or even vanilla-like. Banana-like esters should be present at low to medium-high levels. These beers are made with at least 50 percent malted wheat, and hop rates are quite low. Hop flavor and aroma are absent or present at very low levels. Weissbier is well attenuated and very highly carbonated and a medium to full bodied beer. The color is very pale to pale amber. Because yeast is present, the beer will have yeast flavor and a characteristically fuller mouthfeel and may be appropriately very cloudy. No diacetyl should be perceived.', 'https://www.brewersassociation.org/resources/brewers-association-beer-style-guidelines/', '04', 'N', 'bcoe', 'BA', 0, 0, 0, 0, '2015', NULL, NULL),
('125', 'South German-Style Kristall Weizen / Kristall Weissbier', 'German Origin Ales', '1.047', NULL, '1.008', '1.016', '4.9', '5.5', '10', '15', '3', '9', '1', 'The aroma and flavor of a Weissbier without yeast is very similar to Weissbier with yeast (Hefeweizen/Hefeweissbier) with the caveat that fruity and phenolic characters are not combined with the yeasty flavor and fuller-bodied mouthfeel of yeast. The phenolic characteristics are often described as clove- or nutmeg-like and can be smoky or even vanilla-like. Banana-like esters are often present. These beers are made with at least 50 percent malted wheat, and hop rates are quite low. Hop flavor and aroma are absent. Weissbier is well attenuated and very highly carbonated, yet its relatively high starting gravity and alcohol content make it a medium- to full-bodied beer. The color is very pale to deep golden. Because the beer has been filtered, yeast is not present. The beer will have no flavor of yeast and a cleaner, drier mouthfeel. The beer should be clear with no chill haze present. No diacetyl should be perceived.', 'https://www.brewersassociation.org/resources/brewers-association-beer-style-guidelines/', '04', 'N', 'bcoe', 'BA', 0, 0, 0, 0, '2015', NULL, NULL),
('126', 'German-Style Leichtes Weizen / Weissbier', 'German Origin Ales', '1.028', NULL, '1.004', '1.008', '2.5', '3.5', '10', '20', '4', '15', '1', 'The German word leicht means light, and as such these beers are light versions of Hefeweizen. Leicht Weissbier is top fermented and cloudy like Hefeweizen. The phenolic and estery aromas and flavors typical of Weissbiers are more subdued in Leichtes Weizen. Hop flavor and aroma are normally absent. The overall flavor profile is less complex than Hefeweizen due to decreased alcohol content. There is less yeasty flavor present. Leichtes Weissbier has diminished mouth feel relative to Hefeweizen, and is a low-bodied beer. No diacetyl should be perceived. The beer may have a broad range of color from pale golden to pale amber.', 'https://www.brewersassociation.org/resources/brewers-association-beer-style-guidelines/', '04', 'N', 'bcoe', 'BA', 0, 0, 0, 0, '2015', NULL, NULL),
('127', 'Leipzig-Style Gose', 'German Origin Ales', '1.036', '1.056', '1.008', '1.012', '4.4', '5.4', '10', '15', '3', '9', '1', 'Traditional examples of Gose are spontaneously fermented, similarly to Belgian-style gueuze/lambic beers, and should exhibit complexity of acidic, flavor and aroma contributed by introduction of wild yeast and bacteria into the fermentation. A primary difference between Belgian Gueuze and German Gose is that Gose is served at a much younger age. Gose is typically pale gold to pale amber in color and typically contains malted barley, unmalted wheat with some traditional varieties containing oats. Hop character and malt flavors and aromas are negligible. Lemony or other citrus-like qualities are often present in aroma and on the palate. Some versions may have the spicy character of added coriander in aroma and on the palate at low to medium levels. Salt (table salt) character is also traditional in low amounts. Horsey, leathery, earthy aroma and flavors contributed by Brettanomyces yeasts may be evident but have a very low profile, as this beer is not excessively aged. Modern German Gose breweries typically introduce only pure beer yeast strains for fermentation. Low to medium lactic acid character is evident in all examples as sharp, refreshing sourness. Gose is typically enjoyed fresh, carbonated, and cloudy/hazy with yeast character, and may have evidence of continued fermentation activity. Overall complexity of flavors and aromas are sought while maintaining an ideal balance between acidity, yeast-enhanced spice and refreshment is ideal.', 'https://www.brewersassociation.org/resources/brewers-association-beer-style-guidelines/', '04', 'N', 'bcoe', 'BA', 0, 0, 0, 0, '2015', NULL, NULL),
('128', 'Berliner-Style Weisse (Wheat)', 'German Origin Ales', '1.028', NULL, '1.004', '1.006', '2.8', '3.4', '3', '6', '2', '4', '1', 'This is very pale in color and the lightest of all the German wheat beers. The unique combination of yeast and lactic acid bacteria fermentation yields a beer that is acidic, highly attenuated, and very light bodied. The carbonation of a Berliner Weisse is high, and hop rates are very low. Clarity may be hazy or cloudy from yeast or chill haze. Hop character should not be perceived. Fruity esters will be evident. No diacetyl should be perceived.', 'https://www.brewersassociation.org/resources/brewers-association-beer-style-guidelines/', '04', 'N', 'bcoe', 'BA', 0, 0, 0, 0, '2015', NULL, NULL),
('129', 'Specialty Stouts', 'North American Origin Ales', '1.080', NULL, '1.020', '1.030', '7.0', '12.0', '35', '50', '40', '40', '1', 'See British Origin American-Style Imperial Porter Imperial porters are very dark brown to black in color. No roast barley or strong burnt/astringent black malt character should be perceived. Medium malt, caramel and cocoa-like sweetness should be in harmony with medium-low to medium hop bitterness. This is a full bodied beer. Ale-like fruity esters should be evident but not overpowering and compliment malt derived sweetness and hop character. Hop flavor and aroma may vary from being low to medium-high. Diacetyl (butterscotch) levels should be absent.', 'https://www.brewersassociation.org/resources/brewers-association-beer-style-guidelines/', '03', 'N', 'bcoe', 'BA', 1, 0, 0, 0, '2015', NULL, NULL),
('130', 'German-Style Kölsch / Köln-Style Kölsch', 'German Origin Ales', '1.042', NULL, '1.006', '1.010', '4.8', '5.3', '18', '25', '4', '6', '1', 'Kölsch is warm fermented and aged at cold temperatures (German ale or alt-style beer). Kölsch is characterized by a golden to straw color and a slightly dry, subtly sweet softness on the palate, yet crisp. Good, dense head retention is desirable. Light pearapple-Riesling wine-like fruitiness may be apparent, but is not necessary for this style. Caramel character should not be evident. The body is light to medium-light. This beer has low hop flavor and aroma with medium bitterness. Wheat can be used in brewing this beer. Ale yeast is used for fermentation, though lager yeast is sometimes used in the bottle or final cold conditioning process. Fruity esters should be minimally perceived, if at all. Chill haze should be absent.', 'https://www.brewersassociation.org/resources/brewers-association-beer-style-guidelines/', '04', 'N', 'bcoe', 'BA', 0, 0, 0, 0, '2015', NULL, NULL),
('131', 'American-Style Imperial Stout', 'North American Origin Ales', '1.080', NULL, '1.020', '1.030', '7.0', '12.0', '50', '80', '40', '40', '1', 'Black in color, American-style imperial stouts typically have a high alcohol content. Generally characterized as very robust. The extremely rich malty flavor and aroma are balanced with assertive hopping and fruity-ester characteristics. Bitterness should be moderately high to very high and balanced with full sweet malt character. Roasted malt astringency and bitterness can be moderately perceived but should not overwhelm the overall character. Hop aroma is usually moderately-high to overwhelmingly hop-floral, -citrus or -herbal. Diacetyl (butterscotch) levels should be absent.', 'https://www.brewersassociation.org/resources/brewers-association-beer-style-guidelines/', '03', 'N', 'bcoe', 'BA', 0, 0, 0, 0, '2015', NULL, NULL),
('132', 'American-Style Sour Ale', 'North American Origin Ales', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '1', 'American sour ales can be very light to black or take on the color of added fruits or other ingredients. There is no Brettanomyces character in this style of beer. Wood- and barrel-aged sour ales are classified elsewhere. If acidity is present it is usually in the form of lactic, acetic and other organic acids naturally developed with acidified malt in the mash or in fermentation by the use of various microorganisms including certain bacteria and yeasts. Acidic character can be a complex balance of several types of acid and characteristics of age. The evolution of natural acidity develops balanced complexity. Residual flavors that come from liquids previously aged in a barrel such as bourbon or sherry should not be present. Wood vessels may be used during the fermentation and aging process, but wood-derived flavors such as vanillin must not be present. In darker versions, roasted malt, caramel-like and chocolate-like characters should be subtle in both flavor and aroma. American sour may have evident full range of hop aroma and hop bitterness with a full range of body. Estery and fruity-ester characters are evident, sometimes moderate and sometimes intense, yet balanced. Diacetyl and sweet corn-like dimethylsulfide (DMS) should not be perceived. Chill haze, bacteria and yeast-induced haze are allowable at low to medium levels at any temperature. Fruited American-Style Sour Ales will exhibit fruit flavors in harmonious balance with other characters.', 'https://www.brewersassociation.org/resources/brewers-association-beer-style-guidelines/', '03', 'N', 'bcoe', 'BA', 0, 0, 0, 0, '2015', NULL, NULL),
('133', 'Brett Beer', 'Hybrid/Mixed Lagers or Ales', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '1', 'Brett Beers are any range of color and may take on the color of added fruits or other ingredients. Chill haze, bacteria and yeast-induced haze are allowable at low to medium levels at any temperature. Moderate to intense yet balanced fruity-ester aromas are evident. In darker versions, roasted malt, caramel-like and chocolate-like aromas are subtly present. Diacetyl and DMS aromas should not be perceived. Hop aroma is evident over a full range from low to high. In darker versions, roasted malt, caramel-like and chocolate-like flavors are subtly present. Fruited versions will exhibit fruit flavors in harmonious balance with other characters. Hop flavor is evident over a full range from low to high. Hop bitterness is evident over a full range from low to high. The evolution of natural acidity develops balanced complexity. Horsey, goaty, leathery, phenolic and light to moderate and/or fruity acidic character evolved from Brettanomyces organisms may be evident, not dominant and in balance with other character. Cultured yeast strains may be used in the fermentation. Beers in this style should not use bacteria or exhibit bacteria-derived characters. Moderate to intense yet balanced fruity-ester flavors are evident. Diacetyl and DMS flavors should not be perceived. Wood vessels may be used during the fermentation and aging process, but wood-derived flavors such as vanillin must not be present. Residual flavors that come from liquids previously aged in a barrel such as bourbon or sherry should not be present. Body is evident over a full range from low to high. For purposes of competition entries exhibiting wood-derived characters or characters of liquids previously aged in wood would more appropriately be entered in other Wood-Aged Beer categories. Wood- and barrel-aged sour ales should not be entered here and are classified elsewhere.', 'https://www.brewersassociation.org/resources/brewers-association-beer-style-guidelines/', '11', 'N', 'bcoe', 'BA', 1, 0, 0, 0, '2015', NULL, NULL),
('134', 'American-Style Stout', 'North American Origin Ales', '1.050', NULL, '1.010', '1.022', '5.7', '8.8', '35', '60', '40', '40', '1', 'Initial low to medium malt sweetness with a degree of caramel, chocolate and/or roasted coffee flavor with a distinctive dryroasted bitterness in the finish. Coffee-like roasted barley and roasted malt aromas are prominent. Some slight roasted malt acidity is permissible and a medium- to full-bodied mouthfeel is appropriate. Hop bitterness may be moderate to high. Hop aroma and flavor is moderate to high, often with American citrus-type and/or resiny hop character. The perception of fruity esters is low. Roasted malt/barley astringency may be low but not excessive. Diacetyl (butterscotch) should be negligible or not perceived. Head retention is excellent.', 'https://www.brewersassociation.org/resources/brewers-association-beer-style-guidelines/', '03', 'N', 'bcoe', 'BA', 0, 0, 0, 0, '2015', NULL, NULL),
('135', 'American-Style Black Ale', 'North American Origin Ales', '1.056', NULL, '1.012', '1.018', '6.0', '7.5', '50', '70', '35', '35', '1', 'American-style Black Ales are very dark to black and perceived to have medium high to high hop bitterness, flavor and aroma with medium-high alcohol content, balanced with a medium body. Fruity, floral and herbal character from hops of all origins may contribute character. The style is further characterized by a balanced and moderate degree of caramel malt and dark roasted malt flavor and aroma. High astringency and high degree of burnt roast malt character should be absent.', 'https://www.brewersassociation.org/resources/brewers-association-beer-style-guidelines/', '03', 'N', 'bcoe', 'BA', 0, 0, 0, 0, '2015', NULL, NULL),
('136', 'American-Style Brown Ale', 'North American Origin Ales', '1.040', NULL, '1.010', '1.018', '4.0', '6.4', '25', '45', '15', '26', '1', 'American brown ales range from deep copper to brown in color. Roasted malt caramel-like and chocolate-like characters should be of medium intensity in both flavor and aroma. American brown ales have evident low to medium hop flavor and aroma, medium to high hop bitterness, and a medium body. Estery and fruity-ester characters should be subdued. Diacetyl should not be perceived. Chill haze is allowable at cold temperatures.', 'https://www.brewersassociation.org/resources/brewers-association-beer-style-guidelines/', '03', 'N', 'bcoe', 'BA', 0, 0, 0, 0, '2015', NULL, NULL),
('137', 'Smoke Porter', 'North American Origin Ales', '1.040', NULL, '1.006', '1.014', '5.0', '8.7', '20', '40', '20', '20', '1', 'Smoke porters are chestnut brown to black in color. They can exhibit a mild to assertive smoke character in balance with other beer characters. Black malt character can be perceived in some porters, while others may be absent of strong roast character. Roast barley character should be absent. Medium to full malt sweetness, caramel and chocolate are acceptable along with medium to medium-high hop bitterness. These beers are usually medium to full bodied. Fruity esters are acceptable. Hop flavor and aroma may vary from being negligible to medium in character.', 'https://www.brewersassociation.org/resources/brewers-association-beer-style-guidelines/', '03', 'N', 'bcoe', 'BA', 0, 0, 0, 0, '2015', NULL, NULL),
('138', 'American-Style Wheat Wine Ale', 'North American Origin Ales', '1.088', NULL, '1.024', '1.032', '8.4', '12.0', '45', '85', '8', '15', '1', 'American style wheat wines range from gold to deep amber and are brewed with 50% or more wheat malt. They have full body and high residual malty sweetness. Perception of bitterness is moderate to medium -high. Fruity-ester characters are often high and counterbalanced by complexity of alcohols and high alcohol content. Hop aroma and flavor are at low to medium levels. Very low levels of diacetyl may be acceptable. Bready, wheat, honey-like and/or caramel aroma and flavor are often part of the character. Phenolic yeast character, sulfur, and/or sweet corn-like dimethylsulfide (DMS) should not be present. Oxidized, stale and aged characters are not typical of this style. Chill haze is allowable.', 'https://www.brewersassociation.org/resources/brewers-association-beer-style-guidelines/', '03', 'N', 'bcoe', 'BA', 0, 0, 0, 0, '2015', NULL, NULL),
('139', 'Golden or Blonde Ale', 'North American Origin Ales', '1.045', NULL, '1.008', '1.016', '4.0', '5.0', '15', '25', '3', '7', '1', 'Golden or Blonde ales are straw to golden blonde in color. They have a crisp, dry palate, light to medium body, and light malt sweetness. Low to medium hop aroma may be present but does not dominate. Bitterness is low to medium. Fruity esters may be perceived but do not predominate. Diacetyl should not be perceived. Chill haze should be absent.', 'https://www.brewersassociation.org/resources/brewers-association-beer-style-guidelines/', '03', 'N', 'bcoe', 'BA', 0, 0, 0, 0, '2015', NULL, NULL),
('140', 'American-Style Barley Wine Ale', 'North American Origin Ales', '1.090', NULL, '1.024', '1.028', '8.4', '12.0', '60', '100', '11', '22', '1', 'American style barley wines range from amber to deep copper-garnet in color and have a full body and high residual malty sweetness. Complexity of alcohols and fruity-ester characters are often high and counterbalanced by assertive bitterness and extraordinary alcohol content. Hop aroma and flavor are at medium to very high levels. American type hops are often used but not necessary for this style. Very low levels of diacetyl may be acceptable. A caramel and/or toffee aroma and flavor are often part of the character. Characters indicating oxidation, such as vinous (sometimes sherry-like) aromas and/or flavors, are not generally acceptable in American-style Barley Wine Ale, however if a low level of age-induced oxidation character harmonizes and enhances the overall experience this can be regarded favorably. Chill haze is allowable at cold temperatures.', 'https://www.brewersassociation.org/resources/brewers-association-beer-style-guidelines/', '03', 'N', 'bcoe', 'BA', 0, 0, 0, 0, '2015', NULL, NULL),
('141', 'American-Style Amber/Red Ale', 'North American Origin Ales', '1.048', NULL, '1.012', '1.018', '4.5', '6.0', '30', '40', '11', '18', '1', 'American amber/red ales range from light copper to light brown in color. They are characterized by American-variety hops used to produce the perception of medium hop bitterness, flavor, and medium aroma. Amber ales have medium-high to high maltiness with medium to low caramel character. They should have medium to medium-high body. The style may have low levels of fruityester flavor and aroma. Diacetyl can be either absent or barely perceived at very low levels. Chill haze is allowable at cold temperatures. Slight yeast haze is acceptable for bottle-conditioned products.', 'https://www.brewersassociation.org/resources/brewers-association-beer-style-guidelines/', '03', 'N', 'bcoe', 'BA', 0, 0, 0, 0, '2015', NULL, NULL),
('142', 'Imperial Red Ale', 'North American Origin Ales', '1.080', NULL, '1.020', '1.028', '7.9', '10.5', '55', '85', '10', '15', '1', 'Imperial Red Ales are deep amber to dark copper/reddish brown. A small amount of chill haze is allowable at cold temperatures. Fruity-ester aroma is medium. Hop aroma is intense, arising from any variety of hops. Medium to high caramel malt character is present. Hop flavor is intense, and balanced with other beer characters. They may use any variety of hops. Hop bitterness is intense. Alcohol content is very high and of notable character. Complex alcohol flavors may be evident. Fruity-ester flavors are medium. Diacetyl should not be perceived. Body is full.', 'https://www.brewersassociation.org/resources/brewers-association-beer-style-guidelines/', '03', 'N', 'bcoe', 'BA', 0, 0, 0, 0, '2015', NULL, NULL),
('143', 'Imperial or Double India Pale Ale', 'North American Origin Ales', '1.075', NULL, '1.012', '1.020', '7.5', '10.5', '65', '100', '5', '13', '1', 'Imperial or Double India Pale Ales have intense hop bitterness, flavor and aroma. Alcohol content is medium-high to high and notably evident. They range from deep golden to medium copper in color. The style may use any variety of hops. Though the hop character is intense it\'s balanced with complex alcohol flavors, moderate to high fruity esters and medium to high malt character. Hop character should be fresh and lively and should not be harsh in quality. The use of large amounts of hops may cause a degree of appropriate hop haze. Imperial or Double India Pale Ales have medium-high to full body. Diacetyl should not be perceived. The intention of this style of beer is to exhibit the fresh and bright character of hops. Oxidative character and aged character should not be present.', 'https://www.brewersassociation.org/resources/brewers-association-beer-style-guidelines/', '03', 'N', 'bcoe', 'BA', 0, 0, 0, 0, '2015', NULL, NULL),
('144', 'American-Style Strong Pale Ale', 'North American Origin Ales', '1.050', NULL, '1.008', '1.016', '5.5', '6.3', '40', '50', '6', '14', '1', 'American strong pale ales range from deep golden to copper in color. The style is characterized by floral and citrus-like American-variety hops used to produce high hop bitterness, flavor, and aroma. Note that floral, fruity, citrus-like, piney, resinous, or sulfur-like American-variety hop character is the perceived end, but may be a result of the skillful use of hops of other national origins. American strong pale ales have medium body and low to medium maltiness. Low caramel character is allowable. Fruityester flavor and aroma should be moderate to strong. Diacetyl should be absent or present at very low levels. Chill haze is allowable at cold temperatures.', 'https://www.brewersassociation.org/resources/brewers-association-beer-style-guidelines/', '03', 'N', 'bcoe', 'BA', 0, 0, 0, 0, '2015', NULL, NULL),
('145', 'American-Style India Pale Ale', 'North American Origin Ales', '1.060', NULL, '1.012', '1.018', '6.3', '7.5', '50', '70', '6', '14', '1', 'American-style India pale ales are perceived to have medium-high to intense hop bitterness, flavor and aroma with medium-high alcohol content. The style is further characterized by floral, fruity, citrus-like, piney, resinous, or sulfur-like American-variety hop character. Note that one or more of these American-variety hop characters is the perceived end, but the hop characters may be a result of the skillful use of hops of other national origins. The use of water with high mineral content results in a crisp, dry beer. This pale gold to deep copper-colored ale has a full, flowery hop aroma and may have a strong hop flavor (in addition to the perception of hop bitterness). India pale ales possess medium maltiness which contributes to a medium body. Fruity-ester flavors and aromas are moderate to very strong. Diacetyl can be absent or may be perceived at very low levels. Chill and/or hop haze is allowable at cold temperatures. (English and citrus-like American hops are considered enough of a distinction justifying separate American-style IPA and English-style IPA categories or subcategories. Hops of Other Origin Ales may be used for bitterness or approximating traditional American or English character. See English-style India Pale Ale', 'https://www.brewersassociation.org/resources/brewers-association-beer-style-guidelines/', '03', 'N', 'bcoe', 'BA', 0, 0, 0, 0, '2015', NULL, NULL),
('146', 'Dark American-Belgo-Style Ale', 'North American Origin Ales', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '16', '16', '1', 'These beers must portray the unique characters imparted by yeasts typically used in fruity and big Belgian-Style ales - These beers are not traditional Belgian styles which are already defined. They are unique beers unto themselves. Notes of banana, berry, apple, sometimes coriander spice-like and/or smoky-phenolic characters should be portrayed with balance of hops and malt character when fermented with such yeast. Hop aroma, flavor and bitterness not usually found in the base style, can be medium to very high and must show the characters of American hop varieties. Dark color falls in the deep amber/brown to black range. Roasted malts or barley may have a range of character from subtle to robust, and should be reflected in the overall character and balance of the beer. Esters should be at medium to high levels. Diacetyl should not be evident. Chill haze may be evident. Sulfurlike yeast character should be absent. No Brettanomyces character should be present. An ale which exhibits Brettanomyces character would be classified as \"American-style Brett Ale.\"A statement by the brewer that could include information such as style being elaborated upon, and other information about the entry with regard to flavor, aroma or appearance, is essential for fair assessment in competitions. Beers with Brettanomyces may be subcategorized under this category', 'https://www.brewersassociation.org/resources/brewers-association-beer-style-guidelines/', '03', 'N', 'bcoe', 'BA', 1, 0, 0, 0, '2015', NULL, NULL),
('147', 'Fresh \"Wet\" Hop Ale', 'North American Origin Ales', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '1', 'Any style of ale can be made into a fresh hop or wet hop version. These ales are hopped predominantly with fresh (newly harvested and kilned) and/or undried (“wet”) hops. These beers will exhibit especially aromas and flavors of green, almost chlorophyll-like or other fresh hop characters, in harmony with the characters of the base style of the beer. These beers may be aged and enjoyed after the initial “fresh-hop” character diminishes. Unique character from “aged” fresh hop beers may emerge, but they have yet to be defined.', 'https://www.brewersassociation.org/resources/brewers-association-beer-style-guidelines/', '03', 'N', 'bcoe', 'BA', 0, 0, 0, 0, '2015', NULL, NULL),
('148', 'Pale American-Belgo-Style Ale', 'North American Origin Ales', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '5', NULL, '1', 'These beers must portray the unique characters imparted by yeasts typically used in fruity and big Belgian-Style ales - These beers are not traditional Belgian styles which are already defined. They are unique beers unto themselves. Notes of banana, berry, apple, sometimes coriander spice-like and/or smoky-phenolic characters should be portrayed with balance of hops and malt character when fermented with such yeast. Hop aroma, flavor and bitterness not usually found in the base style, can be medium to very high and must show the characters of American hop varieties. Color falls in the blonde to amber range. Esters should be at medium to high levels. Diacetyl should not be evident. Chill haze may be evident. Sulfur-like yeast character should be absent.. No Brettanomyces character should be present. An ale which exhibits Brettanomyces character would be classified as \"American-style Brett Ale.\" A statement by the brewer that could include information such as style being elaborated upon, and other information about the entry with regard to flavor, aroma or appearance, is essential for fair assessment in competitions. Beers with Brettanomyces may be subcategorized under this category.', 'https://www.brewersassociation.org/resources/brewers-association-beer-style-guidelines/', '03', 'N', 'bcoe', 'BA', 1, 0, 0, 0, '2015', NULL, NULL),
('149', 'Foreign (Export)-Style Stout', 'Irish Origin Ales', '1.052', NULL, '1.008', '1.020', '5.7', '9.3', '30', '60', '40', '40', '1', 'As with classic dry stouts, foreign-style stouts have an initial malt sweetness and caramel flavor with a distinctive dry-roasted bitterness in the finish. Coffee-like roasted barley and roasted malt aromas are prominent. Some slight acidity is permissible and a medium- to full-bodied mouthfeel is appropriate. Bitterness may be high but the perception is often compromised by malt sweetness. Hop aroma and flavor should not be perceived. The perception of fruity esters is low. Diacetyl (butterscotch) should be negligible or not perceived. Head retention is excellent.', 'https://www.brewersassociation.org/resources/brewers-association-beer-style-guidelines/', '02', 'N', 'bcoe', 'BA', 0, 0, 0, 0, '2015', NULL, NULL),
('150', 'American-Style Pale Ale', 'North American Origin Ales', '1.044', NULL, '1.008', '1.014', '4.5', '5.6', '30', '42', '6', '14', '1', 'American pale ales range from deep golden to copper in color. The style is characterized by fruity, floral and citrus-like American-variety hop character producing medium to medium-high hop bitterness, flavor, and aroma. Note that the \"traditional\" style of this beer has its origins with certain floral, fruity, citrus-like, piney, resinous, or sulfur-like American hop varietals. One or more of these hop characters is the perceived end, but the perceived hop characters may be a result of the skillful use of hops of other national origins. American pale ales have medium body and low to medium maltiness. Low caramel character is allowable. Fruity-ester flavor and aroma should be moderate to strong. Diacetyl should be absent or present at very low levels. Chill haze is allowable at cold temperatures.', 'https://www.brewersassociation.org/resources/brewers-association-beer-style-guidelines/', '03', 'N', 'bcoe', 'BA', 0, 0, 0, 0, '2015', NULL, NULL),
('151', 'Classic Irish-Style Dry Stout', 'Irish Origin Ales', '1.038', NULL, '1.008', '1.012', '3.8', '5.0', '30', '40', '40', '40', '1', 'Dry stouts have an initial malt and light caramel flavor profile with a distinctive dry-roasted bitterness in the finish. Dry stouts achieve a dry-roasted character through the use of roasted barley. The emphasis of coffee-like roasted barley and a moderate degree of roasted malt aromas define much of the character. Some slight acidity may be perceived but is not necessary. European hop aroma and flavor should be low or not perceived. Dry stouts have medium-light to medium body. Fruity esters are minimal and overshadowed by malt, high hop bitterness, and roasted barley character. Diacetyl (butterscotch) should be very low or not perceived. Head retention and rich character should be part of its visual character.', 'https://www.brewersassociation.org/resources/brewers-association-beer-style-guidelines/', '02', 'N', 'bcoe', 'BA', 0, 0, 0, 0, '2015', NULL, NULL),
('152', 'Irish-Style Red Ale', 'Irish Origin Ales', '1.040', NULL, '1.010', '1.014', '4.0', '4.5', '20', '28', '11', '18', '1', 'Irish-style red ales range from light red-amber-copper to light brown in color. These ales have a medium hop bitterness and flavor. They often don\'t have hop aroma. Irish-style red ales have low to medium candy-like caramel malt sweetness and may have a balanced subtle degree of roast barley or roast malt character and complexity. Irish-style Red Ales have a medium body. The style may have low levels of fruity-ester flavor and aroma. Diacetyl should be absent or at very low levels. Chill haze is allowable at cold temperatures. Slight yeast haze is acceptable for bottle-conditioned products.', 'https://www.brewersassociation.org/resources/brewers-association-beer-style-guidelines/', '02', 'N', 'bcoe', 'BA', 0, 0, 0, 0, '2015', NULL, NULL),
('153', 'Oatmeal Stout', 'British Origin Ales', '1.038', NULL, '1.008', '1.020', '3.8', '6.0', '20', '40', '20', '20', '1', 'Oatmeal stouts include oatmeal in their grist, resulting in a pleasant, full flavor and a smooth profile that is rich without being grainy. A roasted malt character which is caramel-like and chocolate-like should be evident - smooth and not bitter. Coffee-like roasted barley and roasted malt aromas (chocolate and nut-like) are prominent. Color is dark brown to black. Bitterness is moderate, not high. Hop flavor and aroma are optional but should not overpower the overall balance if present. This is a medium- to full- bodied beer, with minimal fruity esters. Diacetyl should be absent or at extremely low levels. Original gravity range and alcohol levels are indicative of English tradition of oatmeal stout.', 'https://www.brewersassociation.org/resources/brewers-association-beer-style-guidelines/', '01', 'N', 'bcoe', 'BA', 0, 0, 0, 0, '2015', NULL, NULL),
('154', 'Sweet or Cream Stout', 'British Origin Ales', '1.045', NULL, '1.012', '1.020', '3.0', '6.0', '15', '25', '40', '40', '1', 'Sweet stouts, also referred to as cream stouts, have less roasted bitter flavor and a full-bodied mouthfeel. The style can be given more body with milk sugar (lactose) before bottling. Malt sweetness, chocolate, and caramel flavor should dominate the flavor profile and contribute to the aroma. Hops should balance and suppress some of the sweetness without contributing apparent flavor or aroma. The overall impression should be sweet and full-bodied.', 'https://www.brewersassociation.org/resources/brewers-association-beer-style-guidelines/', '01', 'N', 'bcoe', 'BA', 0, 0, 0, 0, '2015', NULL, NULL),
('155', 'Brown Porter', 'British Origin Ales', '1.040', NULL, '1.006', '1.014', '4.5', '6.0', '20', '30', '20', '35', '1', 'Brown porters are mid to dark brown (may have red tint) in color. No roast barley or strong burnt/black malt character should be perceived. Low to medium malt sweetness, caramel and chocolate is acceptable along with medium hop bitterness. This is a lightto medium-bodied beer. Fruity esters are acceptable. Hop flavor and aroma may vary from being negligible to medium in character.', 'https://www.brewersassociation.org/resources/brewers-association-beer-style-guidelines/', '01', 'N', 'bcoe', 'BA', 0, 0, 0, 0, '2015', NULL, NULL),
('156', 'Robust Porter', 'British Origin Ales', '1.045', NULL, '1.008', '1.016', '5.0', '6.5', '25', '40', '30', '30', '1', 'Robust porters are black in color and have a roast malt flavor, often reminiscent of cocoa, but no roast barley flavor. These porters have a sharp bitterness of black malt without a highly burnt/charcoal flavor. Caramel and other malt sweetness should be present and in harmony with other distinguishing porter characters. Robust porters range from medium to full in body and have a malty sweetness. Hop bitterness is medium to high, with hop aroma and flavor ranging from negligible to medium. Diacetyl is acceptable at very low levels. Fruity esters should be evident, balanced with roast malt and hop bitterness.', 'https://www.brewersassociation.org/resources/brewers-association-beer-style-guidelines/', '01', 'N', 'bcoe', 'BA', 0, 0, 0, 0, '2015', NULL, NULL),
('157', 'British-Style Imperial Stout', 'British Origin Ales', '1.080', NULL, '1.020', '1.030', '7.0', '12.0', '45', '65', '20', '35', '1', 'Dark copper to very dark brown, British-style imperial stouts typically have high alcohol content. The extremely rich malty flavor (often characterized as toffee-like or caramel-like) and aroma are balanced with medium hopping and high fruity-ester characteristics. Bitterness should be moderate and balanced with sweet malt character. The bitterness may be higher in the darker versions. Roasted malt astringency is very low or absent. Bitterness should not overwhelm the overall character. Hop aroma can be subtle to moderately hop-floral, -citrus or -herbal. Diacetyl (butterscotch) levels should be absent.', 'https://www.brewersassociation.org/resources/brewers-association-beer-style-guidelines/', '01', 'N', 'bcoe', 'BA', 0, 0, 0, 0, '2015', NULL, NULL),
('158', 'British-Style Barley Wine Ale', 'British Origin Ales', '1.085', NULL, '1.024', '1.028', '8.4', '12.0', '40', '60', '14', '22', '1', 'British-style barley wines range from tawny copper to dark brown in color and have a full body and high residual malty sweetness. Complexity of alcohols and fruity-ester characters are often high and counterbalanced by the perception of low to medium bitterness and extraordinary alcohol content. Hop aroma and flavor may be minimal to medium. English type hops are often used but not necessary for this style. Low levels of diacetyl may be acceptable. Caramel and some characters indicating oxidation, such as vinous (sometimes sherry-like) aromas and/or flavors, may be considered positive. Chill haze is allowable at cold temperatures.', 'https://www.brewersassociation.org/resources/brewers-association-beer-style-guidelines/', '01', 'N', 'bcoe', 'BA', 0, 0, 0, 0, '2015', NULL, NULL),
('159', 'Strong Ale', 'British Origin Ales', '1.060', NULL, '1.014', '1.040', '7.0', '11.0', '30', '65', '8', '21', '1', 'Light amber to mid-range brown in color, strong ales are medium to full bodied with a malty sweetness and may have low levels of roast malt character. Hop aroma should be minimal and flavor can vary from none to medium in character intensity. Fruity-ester flavors and aromas can contribute to the character of this ale. Bitterness should be minimal but evident and balanced with malt and/or caramel-like sweetness. Alcohol types can be varied and complex. A rich, often sweet and complex estery character may be evident. Very low levels of diacetyl are acceptable. Chill haze is acceptable at low temperatures. (This style may often be split into two categories, strong and very strong.)', 'https://www.brewersassociation.org/resources/brewers-association-beer-style-guidelines/', '01', 'N', 'bcoe', 'BA', 0, 0, 0, 0, '2015', NULL, NULL),
('160', 'Scotch Ale', 'British Origin Ales', '1.072', NULL, '1.016', '1.028', '6.2', '8.0', '25', '35', '15', '30', '1', 'Scotch ales are overwhelmingly malty and full-bodied. Perception of hop bitterness is very low. Hop flavor and aroma are very low or nonexistent. Color ranges from deep copper to brown. The clean alcohol flavor balances the rich and dominant sweet maltiness in flavor and aroma. A caramel character is often a part of the profile. Dark roasted malt flavors and aroma may be evident at low levels. If present, fruity esters are generally at low aromatic and flavor levels. Low diacetyl levels are acceptable. Chill haze is allowable at cold temperatures. Though there is little evidence suggesting that traditionally made strong Scotch ales exhibited peat smoke character, the current marketplace offers many Scotch Ales with peat or smoke character present at low to medium levels. Thus a peaty/smoky character may be evident at low levels (ales with medium or higher smoke character would be considered a smoke flavored beer and considered in another category). Scotch Ales may be split into two subcategories: Traditional (no smoke character) and Peated (low level of peat smoke character).', 'https://www.brewersassociation.org/resources/brewers-association-beer-style-guidelines/', '01', 'N', 'bcoe', 'BA', 0, 0, 0, 0, '2015', NULL, NULL),
('161', 'Old Ale', 'British Origin Ales', '1.058', NULL, '1.014', '1.030', '6.0', '9.0', '30', '65', '12', '30', '1', 'Dark amber to brown in color, old ales are medium to full bodied with a malty sweetness. Hop aroma should be minimal and flavor can vary from none to medium in character intensity. Fruity-ester flavors and aromas can contribute to the character of this ale. Bitterness should be minimal but evident and balanced with malt and/or caramel-like sweetness. Alcohol types can be varied and complex. A distinctive quality of these ales is that they undergo an aging process (often for years) on their yeast either in bulk storage or through conditioning in the bottle, which contributes to a rich, wine-like and often sweet oxidation character. Complex estery characters may also emerge. Some very low diacetyl character may be evident and acceptable. Wood aged characters such as vanillin and other woody characters are acceptable. Horsey, goaty, leathery and phenolic character evolved from Brettanomyces organisms and acidity may be present but should be at low levels and balanced with other flavors Residual flavors that come from liquids previously aged in a barrel such as bourbon or sherry should not be present. Chill haze is acceptable at low temperatures. (This style may often be split into two categories, strong and very strong. Brettanomyces organisms and acidic characters reflect historical character. Competition organizers may choose to distinguish these types of old ale from modern versions.)', 'https://www.brewersassociation.org/resources/brewers-association-beer-style-guidelines/', '01', 'N', 'bcoe', 'BA', 0, 0, 0, 0, '2015', NULL, NULL),
('162', 'English-Style Dark Mild Ale', 'British Origin Ales', '1.030', NULL, '1.004', '1.008', '3.2', '4.0', '10', '24', '17', '34', '1', 'English dark mild ales range from deep copper to dark brown (often with a red tint) in color. Malt flavor and caramel are part of the flavor and aroma profile while, licorice and roast malt tones may sometimes contribute to the flavor and aroma profile. Body should be low-medium to medium. These beers have very little hop flavor or aroma. Very low diacetyl flavors may be appropriate in this low-alcohol beer. Fruity-ester level is very low.', 'https://www.brewersassociation.org/resources/brewers-association-beer-style-guidelines/', '01', 'N', 'bcoe', 'BA', 0, 0, 0, 0, '2015', NULL, NULL),
('163', 'English-Style Pale Mild Ale', 'British Origin Ales', '1.030', NULL, '1.004', '1.008', '3.2', '4.0', '10', '20', '8', '17', '1', 'English pale mild ales range from golden to amber in color. Malt flavor dominates the flavor profile with little hop bitterness or flavor. Hop aroma can be light. Very low diacetyl flavors may be appropriate in this low-alcohol beer. Fruity-ester level is very low. Chill haze is allowable at cold temperatures.', 'https://www.brewersassociation.org/resources/brewers-association-beer-style-guidelines/', '01', 'N', 'bcoe', 'BA', 0, 0, 0, 0, '2015', NULL, NULL),
('164', 'Scottish-Style Light Ale', 'British Origin Ales', '1.030', NULL, '1.006', '1.012', '2.8', '3.5', '9', '20', '8', '17', '1', 'Scottish light ales are light bodied. Little bitterness is perceived, and hop flavor or aroma should not be perceived. Despite its lightness, Scottish light ale will have a degree of malty, caramel-like, soft and chewy character. Yeast characters such as diacetyl (butterscotch) and sulfuriness are acceptable at very low levels. The color will range from golden amber to deep brown Bottled versions of this traditional draft beer may contain higher amounts of carbon dioxide than is typical for mildly carbonated draft versions. Chill haze is acceptable at low temperatures. Though there is little evidence suggesting that traditionally made Scottishstyle light ales exhibited peat smoke character, the current marketplace offers many Scottish-style light ales with peat or smoke character present at low to medium levels. Thus a peaty/smoky character may be evident at low levels (ales with medium or higher smoke character would be considered a smoke flavored beer and considered in another category). Scottish-style light ales may be split into two subcategories: Traditional (no smoke character) and Peated (low level of peat smoke character).', 'https://www.brewersassociation.org/resources/brewers-association-beer-style-guidelines/', '01', 'N', 'bcoe', 'BA', 0, 0, 0, 0, '2015', NULL, NULL),
('165', 'English-Style Brown Ale', 'British Origin Ales', '1.040', NULL, '1.008', '1.014', '4.0', '5.5', '15', '25', '13', '25', '1', 'English brown ales range from copper to brown in color. They have medium body and range from dry to sweet maltiness with very little hop flavor or aroma. Roast malt tones may sometimes contribute to the flavor and aroma profile. Low to medium-low levels of fruity-ester flavors are appropriate. Diacetyl should be very low, if evident. Chill haze is allowable at cold temperatures.', 'https://www.brewersassociation.org/resources/brewers-association-beer-style-guidelines/', '01', 'N', 'bcoe', 'BA', 0, 0, 0, 0, '2015', NULL, NULL),
('166', 'Scottish-Style Export Ale', 'British Origin Ales', '1.040', NULL, '1.010', '1.018', '4.0', '5.3', '15', '25', '10', '19', '1', 'The overriding character of Scottish export ale is sweet, caramel-like, and malty. Its bitterness is perceived as low to medium. Hop flavor or aroma should not be perceived. It has medium body. Fruity-ester character may be apparent. Yeast characters such as diacetyl (butterscotch) and sulfuriness are acceptable at very low levels. The color will range from golden amber to deep brown. Bottled versions of this traditional draft beer may contain higher amounts of carbon dioxide than is typical for mildly carbonated draft versions. Chill haze is acceptable at low temperatures. Though there is little evidence suggesting that traditionally made Scottish-style export ales exhibited peat smoke character, the current marketplace offers many Scottish-style export ales with peat or smoke character present at low to medium levels. Thus a peaty/smoky character may be evident at low to medium levels (ales with medium-high or higher smoke character would be considered a smoke flavored beer and considered in another category). Scottish-style export ales may be split into two subcategories: Traditional (no smoke character) and Peated (low level of peat smoke character).', 'https://www.brewersassociation.org/resources/brewers-association-beer-style-guidelines/', '01', 'N', 'bcoe', 'BA', 0, 0, 0, 0, '2015', NULL, NULL),
('167', 'Scottish-Style Heavy Ale', 'British Origin Ales', '1.035', NULL, '1.010', '1.014', '3.5', '4.0', '12', '20', '10', '19', '1', 'Scottish heavy ale is moderate in strength and dominated by a smooth, sweet maltiness balanced with low, but perceptible, hop bitterness. Hop flavor or aroma should not be perceived. Scottish heavy ale will have a medium degree of malty, caramel-like, soft and chewy character in flavor and mouthfeel. It has medium body, and fruity esters are very low, if evident. Yeast characters such as diacetyl (butterscotch) and sulfuriness are acceptable at very low levels. The color will range from golden amber to deep brown. Bottled versions of this traditional draft beer may contain higher amounts of carbon dioxide than is typical for mildly carbonated draft versions. Chill haze is acceptable at low temperatures. Though there is little evidence suggesting that traditionally made Scottish-style heavy ales exhibited peat smoke character, the current marketplace offers many Scottish-style heavy ales with peat or smoke character present at low to medium levels. Thus a peaty/smoky character may be evident at low to medium levels (ales with medium-high or higher smoke character would be considered a smoke flavored beer and considered in another category). Scottish-style heavy ales may be split into two subcategories: Traditional (no smoke character) and Peated (low level of peat smoke character).', 'https://www.brewersassociation.org/resources/brewers-association-beer-style-guidelines/', '01', 'N', 'bcoe', 'BA', 0, 0, 0, 0, '2015', NULL, NULL),
('168', 'Classic English-Style Pale Ale', 'British Origin Ales', '1.040', NULL, '1.008', '1.016', '4.5', '5.5', '20', '40', '5', '5', '1', 'Classic English pale ales are golden to copper colored and display earthy, herbal English-variety hop character. Note that \"earthy, herbal English-variety hop character\" is the perceived end, but may be a result of the skillful use of hops of other national origins. Medium to high hop bitterness, flavor, and aroma should be evident. This medium-bodied pale ale has low to medium malt flavor and aroma. Low caramel character is allowable. Fruity-ester flavors and aromas are moderate to strong. Chill haze may be in evidence only at very cold temperatures. The absence of diacetyl is desirable, though, diacetyl (butterscotch character) is acceptable and characteristic when at very low levels.', 'https://www.brewersassociation.org/resources/brewers-association-beer-style-guidelines/', '01', 'N', 'bcoe', 'BA', 0, 0, 0, 0, '2015', NULL, NULL),
('169', 'English-Style India Pale Ale', 'British Origin Ales', '1.050', NULL, '1.012', '1.018', '5.0', '7.0', '35', '63', '6', '14', '1', 'Most traditional interpretations of English-style India pale ales are characterized by medium-high hop bitterness with a medium to medium-high alcohol content. Hops from a variety of origins may be used to contribute to a high hopping rate. Earthy and herbal English-variety hop character is the perceived end, but may be a result of the skillful use of hops of other national origins. The use of water with high mineral content results in a crisp, dry beer, sometimes with subtle and balanced character of sulfur compounds. This pale gold to deep copper-colored ale has a medium to high, flowery hop aroma and may have a medium to strong hop flavor (in addition to the hop bitterness). English-style India pale ales possess medium maltiness and body. Fruity-ester flavors and aromas are moderate to very strong. Diacetyl can be absent or may be perceived at very low levels. Chill haze is allowable at cold temperatures. Hops of Other Origin Ales may be used for bitterness or approximating traditional English character.', 'https://www.brewersassociation.org/resources/brewers-association-beer-style-guidelines/', '01', 'N', 'bcoe', 'BA', 0, 0, 0, 0, '2015', NULL, NULL),
('170', 'Ordinary Bitter', 'British Origin Ales', '1.033', NULL, '1.006', '1.012', '3.0', '4.1', '20', '35', '5', '12', '1', 'Ordinary bitter is gold to copper colored with medium bitterness, light to medium body, and low to medium residual malt sweetness. Hop flavor and aroma character may be evident at the brewer\'s discretion. Mild carbonation traditionally characterizes draft-cask versions, but in bottled versions, a slight increase in carbon dioxide content is acceptable. Fruity-ester character and very low diacetyl (butterscotch) character are acceptable in aroma and flavor, but should be minimized in this form of bitter. Chill haze is allowable at cold temperatures. (English and American hop character may be specified in subcategories.)', 'https://www.brewersassociation.org/resources/brewers-association-beer-style-guidelines/', '01', 'N', 'bcoe', 'BA', 0, 0, 0, 0, '2015', NULL, NULL),
('171', 'Special Bitter or Best Bitter', 'British Origin Ales', '1.038', NULL, '1.006', '1.012', '4.1', '4.8', '28', '40', '6', '14', '1', 'Special bitter is more robust than ordinary bitter. It has medium body and medium residual malt sweetness. It is deep gold to copper colored. Hop bitterness should be medium and absent of harshness. Hop flavor and aroma character may be evident at the brewer\'s discretion. Mild carbonation traditionally characterizes draft-cask versions, but in bottled versions, a slight increase in carbon dioxide content is acceptable. Fruity-ester character is acceptable in aroma and flavor. Diacetyl (butterscotch character) is acceptable and characteristic when at very low levels. The absence of diacetyl is also acceptable. Chill haze is allowable at cold temperatures. (English and American hop character may be specified in subcategories.)', 'https://www.brewersassociation.org/resources/brewers-association-beer-style-guidelines/', '01', 'N', 'bcoe', 'BA', 0, 0, 0, 0, '2015', NULL, NULL),
('172', 'Extra Special Bitter', 'British Origin Ales', '1.046', NULL, '1.010', '1.016', '4.8', '5.8', '30', '45', '8', '14', '1', 'Extra special bitter possesses medium to strong hop aroma, flavor, and bitterness. The residual malt and defining sweetness of this richly flavored, full-bodied bitter is more pronounced than in other styles of bitter. It is light amber to copper colored with medium to medium-high bitterness. Mild carbonation traditionally characterizes draft-cask versions, but in bottled versions, a slight increase in carbon dioxide content is acceptable. Fruity-ester character is acceptable in aroma and flavor. Diacetyl (butterscotch character) is acceptable and characteristic when at very low levels. The absence of diacetyl is also acceptable. Chill haze is allowable at cold temperatures. English or American hops may be used. (English and American hop character may be specified in subcategories.)', 'https://www.brewersassociation.org/resources/brewers-association-beer-style-guidelines/', '01', 'N', 'bcoe', 'BA', 0, 0, 0, 0, '2015', NULL, NULL),
('173', 'English-Style Summer Ale', 'British Origin Ales', '1.036', NULL, '1.006', '1.012', '3.6', '5.0', '20', '30', '4', '7', '1', 'English Summer Ale is light straw to golden colored with medium-low to medium bitterness, light to medium-light body, and low to medium residual malt sweetness. Torrefied and/or malted wheat are often used in quantities of 25% or less. Malt flavor may be biscuit-like. English, American or Noble-type hop, character, flavor and aroma are evident and may or may not be assertive yet always well balanced with malt character. Mild carbonation traditionally characterizes draft-cask versions. In bottled versions, normal or lively carbon dioxide content is appropriate. The overall impression is refreshing and thirst quenching. Fruity-ester characters are acceptable at low to moderate levels. No butterscotch-like diacetyl or sweet corn-like dimethylsulfide (DMS) should be apparent in aroma or flavor. Chill haze is allowable at cold temperatures.', 'https://www.brewersassociation.org/resources/brewers-association-beer-style-guidelines/', '01', 'N', 'bcoe', 'BA', 0, 0, 0, 0, '2015', NULL, NULL);
";

if (!$ba_styles_present) {
	mysqli_real_escape_string($connection,$updateSQL);
	$result = mysqli_query($connection,$updateSQL) or die (mysqli_error($connection));
}

$output .= "<li>BA styles added.</li>";

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
	$updateSQL = sprintf("ALTER TABLE `%s` ADD `prefsBestUseBOS` TINYINT(1) NULL DEFAULT NULL;",$prefix."preferences");
	mysqli_select_db($connection,$database);
	mysqli_real_escape_string($connection,$updateSQL);
	$result = mysqli_query($connection,$updateSQL);

	$updateSQL = sprintf("UPDATE `%s` SET prefsBestUseBOS='1' WHERE id='1';",$prefix."preferences");
	mysqli_select_db($connection,$database);
	mysqli_real_escape_string($connection,$updateSQL);
	$result = mysqli_query($connection,$updateSQL);
}

$query_mead_cider_present = sprintf("SELECT COUNT(*) AS 'count' FROM %s WHERE styleTypeName = 'Mead/Cider'",$prefix."style_types");
$mead_cider_present = mysqli_query($connection,$query_mead_cider_present) or die (mysqli_error($connection));
$row_mead_cider_present = mysqli_fetch_assoc($mead_cider_present);

if ($row_mead_cider_present['count'] == 0) {
	$updateSQL = sprintf("INSERT INTO `%s` (`id`, `styleTypeName`, `styleTypeOwn`, `styleTypeBOS`, `styleTypeBOSMethod`) VALUES (NULL, 'Mead/Cider', 'bcoe', 'N', '1');", $prefix."style_types");
	mysqli_select_db($connection,$database);
	mysqli_real_escape_string($connection,$updateSQL);
	$result = mysqli_query($connection,$updateSQL);
}

else {
	$updateSQL = sprintf("UPDATE `%s` SET styleTypeOwn='bcoe' WHERE styleTypeName='Mead/Cider';",$prefix."style_types");
	mysqli_select_db($connection,$database);
	mysqli_real_escape_string($connection,$updateSQL);
	$result = mysqli_query($connection,$updateSQL);
}

if (check_update("brewWinnerPlace", $prefix."brewing")) {
	$updateSQL = sprintf("ALTER TABLE `%s` CHANGE `brewWinnerPlace` `brewAdminNotes` TINYTEXT NULL DEFAULT NULL COMMENT 'Notes about the entry for Admin use';",$prefix."brewing");
	mysqli_select_db($connection,$database);
	mysqli_real_escape_string($connection,$updateSQL);
	$result = mysqli_query($connection,$updateSQL);
}

if (check_update("brewBOSRound", $prefix."brewing")) {
	$updateSQL = sprintf("ALTER TABLE `%s` CHANGE `brewBOSRound` `brewStaffNotes` TINYTEXT NULL DEFAULT NULL COMMENT 'Notes about the entry for Staff use';",$prefix."brewing");
	mysqli_select_db($connection,$database);
	mysqli_real_escape_string($connection,$updateSQL);
	$result = mysqli_query($connection,$updateSQL);
}

if (check_update("brewBOSPlace", $prefix."brewing")) {
	$updateSQL = sprintf("ALTER TABLE `%s` CHANGE `brewBOSPlace` `brewPossAllergens` TINYTEXT NULL DEFAULT NULL COMMENT 'Notes about the entry from entrant about possible allergens';",$prefix."brewing");
	mysqli_select_db($connection,$database);
	mysqli_real_escape_string($connection,$updateSQL);
	$result = mysqli_query($connection,$updateSQL);
}

$updateSQL = sprintf("ALTER TABLE `%s` CHANGE `prefsLanguage` `prefsLanguage` VARCHAR(25) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL",$prefix."preferences");
mysqli_select_db($connection,$database);
mysqli_real_escape_string($connection,$updateSQL);
$result = mysqli_query($connection,$updateSQL);

$updateSQL = sprintf("UPDATE `%s` SET prefsLanguage='en-US' WHERE id='1';",$prefix."preferences");
mysqli_select_db($connection,$database);
mysqli_real_escape_string($connection,$updateSQL);
$result = mysqli_query($connection,$updateSQL);

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

if (!check_new_style("17","A1","Burton Ale")) {

	$updateSQL = sprintf("INSERT INTO `%s` (`brewStyleNum`, `brewStyle`, `brewStyleCategory`, `brewStyleOG`, `brewStyleOGMax`, `brewStyleFG`, `brewStyleFGMax`, `brewStyleABV`, `brewStyleABVMax`, `brewStyleIBU`, `brewStyleIBUMax`, `brewStyleSRM`, `brewStyleSRMMax`, `brewStyleType`, `brewStyleInfo`, `brewStyleLink`, `brewStyleGroup`, `brewStyleActive`, `brewStyleOwn`, `brewStyleVersion`, `brewStyleReqSpec`, `brewStyleStrength`, `brewStyleCarb`, `brewStyleSweet`, `brewStyleTags`, `brewStyleComEx`, `brewStyleEntry`) VALUES ('A1', 'Burton Ale', 'British Strong Ale', '1.055', '1.075', '1.018', '1.024', '5.0', '7.5', '40', '50', '14', '22', '1', 'A rich, malty, sweet, and bitter dark ale of moderately strong alcohol. Full bodied and chewy with a balanced hoppy finish and complex malty and hoppy aroma. Fruity notes accentuate the malt richness, while the hops help balance the sweeter finish. Has some similarity in malt flavor to Wee Heavy, but with substantially more bitterness. Less strong than an English Barleywine.', 'http://dev.bjcp.org/beer-styles/17a-british-strong-ale-burton-ale/', '17', 'Y', 'bcoe', 'BJCP2015', 0, 0, 0, 0, 'high-strength, traditional-style, balanced, strong-ale-family, british-isles, brown-color, top-fermented', 'The Laboratory Brewery Gone for a Burton', NULL);",$prefix."styles");
	mysqli_select_db($connection,$database);
	mysqli_real_escape_string($connection,$updateSQL);
	$result = mysqli_query($connection,$updateSQL);

}

if (!check_new_style("21","B7","New England IPA")) {

	$updateSQL = sprintf("INSERT INTO `%s` (`brewStyleNum`, `brewStyle`, `brewStyleCategory`, `brewStyleOG`, `brewStyleOGMax`, `brewStyleFG`, `brewStyleFGMax`, `brewStyleABV`, `brewStyleABVMax`, `brewStyleIBU`, `brewStyleIBUMax`, `brewStyleSRM`, `brewStyleSRMMax`, `brewStyleType`, `brewStyleInfo`, `brewStyleLink`, `brewStyleGroup`, `brewStyleActive`, `brewStyleOwn`, `brewStyleVersion`, `brewStyleReqSpec`, `brewStyleStrength`, `brewStyleCarb`, `brewStyleSweet`, `brewStyleTags`, `brewStyleComEx`, `brewStyleEntry`) VALUES ('B7', 'New England IPA', 'Specialty IPA', '1.060', '1.085', '1.010', '1.015', '6.0', '9.0', '25', '60', '3', '7', '1', 'An American IPA with intense fruit flavors and aromas, a soft body, and smooth mouthfeel, and often opaque with substantial haze. Less perceived bitterness than traditional IPAs but always massively hop forward. This emphasis on late hopping, especially dry hopping, with hops with tropical fruit qualities lends the specific \'juicy\' character for which this style is known. The style is still evolving, but this style is essentially a smoother, hazier, juicier American IPA. In this context, ‘juicy’ refers to a mental impression of fruit juice or eating fresh, fully ripe fruit. Heavy examples suggestive of milkshakes, creamsicles, or fruit smoothies are beyond this range; IPAs should always be drinkable. Haziness comes from the dry hopping regime, not suspended yeast, starch haze, set pectins, or other techniques; a hazy shine is desirable, not a cloudy, murky mess.', 'http://dev.bjcp.org/beer-styles/21b-specialty-ipa-new-england-ipa/', '21', 'Y', 'bcoe', 'BJCP2015', 1, 0, 0, 0, 'bitter, craft-style, pale-color, high-strength, hoppy, ipa-family, north-america, specialty-family, top-fermented', 'Hill Farmstead Susan, Other Half Green Diamonds Double IPA, Tired Hands Alien Church, Tree House Julius, Trillium Congress Street, WeldWerks Juicy Bits', 'Entrant MUST specify a strength (session: 3.0-5.0%%, standard: 5.0-7.5%%, double: 7.5-9.5%%).');",$prefix."styles");
	mysqli_select_db($connection,$database);
	mysqli_real_escape_string($connection,$updateSQL);
	$result = mysqli_query($connection,$updateSQL);

}

if (!check_new_style("PR","X1","Dorada Pampeana")) {

	$updateSQL = sprintf("INSERT INTO `%s` (`brewStyleNum`, `brewStyle`, `brewStyleCategory`, `brewStyleOG`, `brewStyleOGMax`, `brewStyleFG`, `brewStyleFGMax`, `brewStyleABV`, `brewStyleABVMax`, `brewStyleIBU`, `brewStyleIBUMax`, `brewStyleSRM`, `brewStyleSRMMax`, `brewStyleType`, `brewStyleInfo`, `brewStyleLink`, `brewStyleGroup`, `brewStyleActive`, `brewStyleOwn`, `brewStyleVersion`, `brewStyleReqSpec`, `brewStyleStrength`, `brewStyleCarb`, `brewStyleSweet`, `brewStyleTags`, `brewStyleComEx`, `brewStyleEntry`) VALUES ('X1', 'Dorada Pampeana', 'Provisional Styles', '1.042', '1.054', '1.009', '1.013', '4.3', '5.5', '15', '22', '3', '5', '1', 'At the beginning argentine homebrewers were very limited: there wasn\'t extract - they could use only pils malt, Cascade hops and dry yeast, commonly Nottingham, Windsor or Safale. With these ingredients, Argentine brewers developed a specific version of Blond Ale, named Dorada Pampeana. Ingredients: usually only pale or pils malt, although may include low rates of caramelized malt. Commonly Cascade hops. Clean American yeast, slightly fruity British or Kölsch, usually packaged in cold.', 'http://dev.bjcp.org/beer-styles/x1-dorada-pampeana/', 'PR', 'Y', 'bcoe', 'BJCP2015', 0, 0, 0, 0, NULL, NULL, NULL);",$prefix."styles");
	mysqli_select_db($connection,$database);
	mysqli_real_escape_string($connection,$updateSQL);
	$result = mysqli_query($connection,$updateSQL);

}

if (!check_new_style("PR","X2","IPA Argenta")) {

	$updateSQL = sprintf("INSERT INTO `%s` (`brewStyleNum`, `brewStyle`, `brewStyleCategory`, `brewStyleOG`, `brewStyleOGMax`, `brewStyleFG`, `brewStyleFGMax`, `brewStyleABV`, `brewStyleABVMax`, `brewStyleIBU`, `brewStyleIBUMax`, `brewStyleSRM`, `brewStyleSRMMax`, `brewStyleType`, `brewStyleInfo`, `brewStyleLink`, `brewStyleGroup`, `brewStyleActive`, `brewStyleOwn`, `brewStyleVersion`, `brewStyleReqSpec`, `brewStyleStrength`, `brewStyleCarb`, `brewStyleSweet`, `brewStyleTags`, `brewStyleComEx`, `brewStyleEntry`) VALUES ('X2', 'IPA Argenta', 'Provisional Styles', '1.055', '1.065', '1.008', '1.015', '5.0', '6.5', '35', '60', '6', '15', '1', 'A decidedly hoppy and bitter, refreshing, and moderately strong Argentine pale ale. The clue is drinkability without harshness and best balance. An Argentine version of the historical English style, developed in 2013 from Somos Cerveceros Association meetings, when its distinctive characteristics were defined. Different from an American IPA in that it is brewed with wheat and using Argentine hops (Cascade, Mapuche and Nugget are typical, although Spalt, Victoria or Bullion may be used to add complexity), with its unique flavor and aroma characteristics. Based on a citrus (from Argetine hops) and wheat pairing idea, like in a Witbier. Low amounts of wheat are similar to a Kölsch grist, as is some fruitiness from fermentation.', 'http://dev.bjcp.org/beer-styles/x2-ipa-argenta/', 'PR', 'Y', 'bcoe', 'BJCP2015', 0, 0, 0, 0, NULL, 'Antares Ipa Argenta, Kerze Ipa Argenta.', NULL);",$prefix."styles");
	mysqli_select_db($connection,$database);
	mysqli_real_escape_string($connection,$updateSQL);
	$result = mysqli_query($connection,$updateSQL);

}

if (!check_new_style("PR","X3","Italian Grape Ale")) {

	$updateSQL = sprintf("INSERT INTO `%s` (`brewStyleNum`, `brewStyle`, `brewStyleCategory`, `brewStyleOG`, `brewStyleOGMax`, `brewStyleFG`, `brewStyleFGMax`, `brewStyleABV`, `brewStyleABVMax`, `brewStyleIBU`, `brewStyleIBUMax`, `brewStyleSRM`, `brewStyleSRMMax`, `brewStyleType`, `brewStyleInfo`, `brewStyleLink`, `brewStyleGroup`, `brewStyleActive`, `brewStyleOwn`, `brewStyleVersion`, `brewStyleReqSpec`, `brewStyleStrength`, `brewStyleCarb`, `brewStyleSweet`, `brewStyleTags`, `brewStyleComEx`, `brewStyleEntry`) VALUES ('X3', 'Italian Grape Ale', 'Provisional Styles', '1.043', '1.090', '1.007', '1.015', '4.8', '10', '10', '30', '5', '30', '1', 'A sometimes refreshing, sometimes more complex Italian ale characterized by different varieties of grapes.', 'http://dev.bjcp.org/beer-styles/x3-italian-grape-ale/', 'PR', 'Y', 'bcoe', 'BJCP2015', 0, 0, 0, 0, NULL, 'Montegioco Tibir, Montegioco Open Mind, Birranova Moscata, LoverBeer BeerBera, Loverbeer D\'uvaBeer, Birra del Borgo Equilibrista, Barley BB10, Barley BBevò, Cudera, Pasturana Filare!, Gedeone PerBacco! Toccalmatto Jadis, Rocca dei Conti Tarì Giacchè', NULL);",$prefix."styles");
	mysqli_select_db($connection,$database);
	mysqli_real_escape_string($connection,$updateSQL);
	$result = mysqli_query($connection,$updateSQL);

}

if (!check_new_style("PR","X4","Catharina Sour")) {

	$updateSQL = sprintf("INSERT INTO `%s` (`brewStyleNum`, `brewStyle`, `brewStyleCategory`, `brewStyleOG`, `brewStyleOGMax`, `brewStyleFG`, `brewStyleFGMax`, `brewStyleABV`, `brewStyleABVMax`, `brewStyleIBU`, `brewStyleIBUMax`, `brewStyleSRM`, `brewStyleSRMMax`, `brewStyleType`, `brewStyleInfo`, `brewStyleLink`, `brewStyleGroup`, `brewStyleActive`, `brewStyleOwn`, `brewStyleVersion`, `brewStyleReqSpec`, `brewStyleStrength`, `brewStyleCarb`, `brewStyleSweet`, `brewStyleTags`, `brewStyleComEx`, `brewStyleEntry`) VALUES ('X4', 'Catharina Sour', 'Provisional Styles', '1.039', '1.048', '1.002', '1.008', '4.0', '5.5', '2', '68', '2', '7', '1', 'A light and refreshing wheat ale with a clean lactic sourness that is balanced by a fresh fruit addition. The low bitterness, light body, moderate alcohol content, and moderately high carbonation allow the flavor and aroma of the fruit to be the primary focus of the beer. The fruit is often, but not always, tropical in nature. This beer is stronger than a Berliner Weiss and typically features fresh fruit. The kettle souring method allows for fast production of the beer, so this is typically a present-use style. It may be bottled or canned, but it should be consumed while fresh.', 'http://dev.bjcp.org/beer-styles/x4-catharina-sour/', 'PR', 'Y', 'bcoe', 'BJCP2015', 1, 0, 0, 0, 'craft-style, fruit, sour, specialty-beer', 'Itajahy Catharina Araca Sour, Blumenau Catharina Sour Sun of a Peach, Lohn Bier Catharina Sour Jaboticaba, Liffey Coroa Real, UNIKA Tangerina, Armada Daenerys.', 'Entrant must specify the types of fresh fruit(s) used.');",$prefix."styles");
	mysqli_select_db($connection,$database);
	mysqli_real_escape_string($connection,$updateSQL);
	$result = mysqli_query($connection,$updateSQL);

}

if (!check_new_style("PR","X5","New Zealand Pilsner")) {

	$updateSQL = sprintf("INSERT INTO `%s` (`brewStyleNum`, `brewStyle`, `brewStyleCategory`, `brewStyleOG`, `brewStyleOGMax`, `brewStyleFG`, `brewStyleFGMax`, `brewStyleABV`, `brewStyleABVMax`, `brewStyleIBU`, `brewStyleIBUMax`, `brewStyleSRM`, `brewStyleSRMMax`, `brewStyleType`, `brewStyleInfo`, `brewStyleLink`, `brewStyleGroup`, `brewStyleActive`, `brewStyleOwn`, `brewStyleVersion`, `brewStyleReqSpec`, `brewStyleStrength`, `brewStyleCarb`, `brewStyleSweet`, `brewStyleTags`, `brewStyleComEx`, `brewStyleEntry`) VALUES ('X5', 'New Zealand Pilsner', 'Provisional Styles', '1.044', '1.046', '1.009', '1.014', '4.5', '5.8', '25', '45', '2', '7', '1', 'A pale, dry, golden-colored, cleanly-fermented beer showcasing the characteristic tropical, citrusy, fruity, grassy New Zealand-type hops. Medium body, soft mouthfeel, and smooth palate and finish, with a neutral to bready malt base provide the support for this very drinkable, refreshing, hop-forward beer.', 'http://dev.bjcp.org/beer-styles/x5-new-zealand-pilsner/', 'PR', 'Y', 'bcoe', 'BJCP2015', 0, 0, 0, 0, 'bitter, pale-color, standard-strength, bottom-fermented, hoppy, pilsner-family, lagered, craft-style, pacific', 'Croucher New Zealand Pilsner, Emerson’s Pilsner, Liberty Halo Pilsner, Panhead Port Road Pilsner, Sawmill Pilsner, Tuatara Mot Eureka', NULL);",$prefix."styles");
	mysqli_select_db($connection,$database);
	mysqli_real_escape_string($connection,$updateSQL);
	$result = mysqli_query($connection,$updateSQL);

}

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

/**
 * ----------------------------------------------- 2.1.14 ----------------------------------------------
 * Pro-Am indication. Change brewerJudgeBOS to brewerProAm
 * -----------------------------------------------------------------------------------------------------
 */

if (check_update("brewerJudgeBOS", $prefix."brewer")) {
	$updateSQL = sprintf("ALTER TABLE `%s` CHANGE `brewerJudgeBOS` `brewerProAm` TINYINT(2) NULL DEFAULT NULL",$prefix."brewer");
	mysqli_select_db($connection,$database);
	mysqli_real_escape_string($connection,$updateSQL);
	$result = mysqli_query($connection,$updateSQL);
}

$output .= "<li>Previous Pro-Am award indication from entrants added.</li>";

if (isset($_SESSION['prefsStyleSet'])) {
	// Correct bug introduced in 2.1.13 regarding display of required info box
	// @ https://github.com/geoffhumphrey/brewcompetitiononlineentry/issues/980
	$updateSQL = sprintf("UPDATE `%s` SET brewStyleVersion='%s' WHERE brewStyleOwn='custom'",$prefix."styles",$_SESSION['prefsStyleSet']);
	mysqli_select_db($connection,$database);
	mysqli_real_escape_string($connection,$updateSQL);
	$result = mysqli_query($connection,$updateSQL);
}

/**
 * ----------------------------------------------- 2.1.15 ----------------------------------------------
 * Make sure that the Scoresheet Upload File Names preference is set to J if not set.
 * Change incorrect BJCP name for style 17A (from English Strong Ale to British Strong Ale)
 * -----------------------------------------------------------------------------------------------------
 */

if ((empty($_SESSION['prefsDisplaySpecial'])) || (!isset($_SESSION['prefsDisplaySpecial']))) {
	$updateSQL = sprintf("UPDATE `%s` SET prefsDisplaySpecial='J'",$prefix."preferences");
	mysqli_select_db($connection,$database);
	mysqli_real_escape_string($connection,$updateSQL);
	$result = mysqli_query($connection,$updateSQL);
}

$updateSQL = sprintf("UPDATE `%s` SET brewStyle='British Strong Ale' WHERE brewStyle='English Strong Ale' AND brewStyleVersion='BJCP2015'",$prefix."styles");
mysqli_select_db($connection,$database);
mysqli_real_escape_string($connection,$updateSQL);
$result = mysqli_query($connection,$updateSQL);


/**
 * ----------------------------------------------- 2.1.19 ----------------------------------------------
 * Change mis-spelled BJCP name for Speciality Fruit Beer style
 * -----------------------------------------------------------------------------------------------------
 */

$updateSQL = sprintf("UPDATE `%s` SET brewStyle='Specialty Fruit Beer' WHERE brewStyle='Speciality Fruit Beer' AND brewStyleVersion='BJCP2015'",$prefix."styles");
mysqli_select_db($connection,$database);
mysqli_real_escape_string($connection,$updateSQL);
$result = mysqli_query($connection,$updateSQL);

// Update the entries db table and change it as well.
$updateSQL = sprintf("UPDATE `%s` SET brewStyle='Specialty Fruit Beer' WHERE brewStyle='Speciality Fruit Beer'", $prefix."brewing");
mysqli_select_db($connection,$database);
mysqli_real_escape_string($connection,$updateSQL);
$result = mysqli_query($connection,$updateSQL);

$output .= "<li>Corrected BJCP 2015 Speciality Fruit Beer spelling in DB row.</li>";

/**
 * ----------------------------------------------- 2.1.19 ----------------------------------------------
 * Change prefsTimeZone column to FLOAT to accomodate fractional time zone numbers
 * Reported to GitHub https://github.com/geoffhumphrey/brewcompetitiononlineentry/issues/1150
 * -----------------------------------------------------------------------------------------------------
 */

$updateSQL = sprintf("ALTER TABLE `%s` CHANGE `prefsTimeZone` `prefsTimeZone` FLOAT NULL DEFAULT NULL;", $prefix."preferences");
mysqli_select_db($connection,$database);
mysqli_real_escape_string($connection,$updateSQL);
$result = mysqli_query($connection,$updateSQL);

$output .= "<li>Altered prefsTimeZone DB column to FLOAT to accomodate fractional time zone numbers.</li>";

/**
 * ----------------------------------------------- 2.1.19 ----------------------------------------------
 * Add Australian Amateur Brewing Championship (AABC) styles to DB.
 * AABC Styles are largely based upon BJCP 2015, but are categorized differently.
 * As such, much of the following are duplicates of BJCP 2015.
 * In a future release, only add the AABC-specific styles and reference BJCP 2015 already in place.
 * Requested via GitHub https://github.com/geoffhumphrey/brewcompetitiononlineentry/issues/1153
 * -----------------------------------------------------------------------------------------------------
 */

// First, check if AABC styles already exist and if so, don't execute the insert query.
$aabc_styles_present = FALSE;
if (check_new_style("01","01","Light Australian Lager [AABC]")) $aabc_styles_present = TRUE;

$updateSQL = "
INSERT INTO `$styles_db_table` (`brewStyleNum`, `brewStyle`, `brewStyleCategory`, `brewStyleOG`, `brewStyleOGMax`, `brewStyleFG`, `brewStyleFGMax`, `brewStyleABV`, `brewStyleABVMax`, `brewStyleIBU`, `brewStyleIBUMax`, `brewStyleSRM`, `brewStyleSRMMax`, `brewStyleType`, `brewStyleInfo`, `brewStyleLink`, `brewStyleGroup`, `brewStyleActive`, `brewStyleOwn`, `brewStyleVersion`, `brewStyleReqSpec`, `brewStyleStrength`, `brewStyleCarb`, `brewStyleSweet`, `brewStyleTags`, `brewStyleComEx`, `brewStyleEntry`) VALUES
('01', 'Light Australian Lager [AABC]', 'Low Alcohol', '1.028', '1.035', '1.004', '1.008', '2.8', '3.5', '10', '15', '2', '4', '1', 'Light-coloured, clean tasting beer. Low flavour levels make off-flavours obvious', 'http://www.aabc.org.au/docs/AABC2019StyleGuidelines.pdf', '01', 'Y', 'bcoe', 'AABC', 0, 0, 0, 0, 'session-strength, amber-color, top-fermented, british-isles, traditional-style, amber-ale-family, malty', 'Cascade Premium Light', NULL),
('02', 'Scottish Light [BJCP 14A]', 'Low Alcohol', '1.03', '1.035', '1.01', '1.013', '2.5', '3.2', '10', '20', '13', '22', '1', 'A malt-focused, generally caramelly beer with perhaps a few esters and occasionally a butterscotch aftertaste. Hops only to balance and support the malt. The malt character can range from dry and grainy to rich, toasty, and caramelly, but is never roasty and especially never has a peat smoke character. ', 'http://bjcp.org/stylecenter.php', '01', 'Y', 'bcoe', 'AABC', 0, 0, 0, 0, 'session-strength, amber-color, top-fermented, british-isles, traditional-style, amber-ale-family, malty', 'McEwans 60.', NULL),
('03', 'London Brown Ale [BJCP 27]', 'Low Alcohol', '1.033', '1.038', '1.012', '1.015', '2.8', '3.6', '15', '20', '22', '35', '1', 'A luscious, sweet, malt-oriented dark brown ale, with caramel and toffee malt complexity and a sweet finish.', 'http://bjcp.org/stylecenter.php', '01', 'Y', 'bcoe', 'AABC', 0, 0, 0, 0, 'session-strength, dark-color, top-fermented, britishisles, historical-style, brown-ale-family, malty, sweet', 'Harveys Bloomsbury Brown Ale, Mann&rsquo;s Brown Ale', 'Entrant MUST specify a strength (session: 3.0-5.0%, standard: 5.0-7.5%, double: 7.5-9.5%).'),
('04', 'Dark Mild [BJCP 13A]', 'Low Alcohol', '1.03', '1.038', '1.008', '1.013', '3', '3.8', '10', '25', '12', '25', '1', 'A dark, low-gravity, malt-focused English session ale readily suited to drinking in quantity. Refreshing, yet flavorful, with a wide range of dark malt or dark sugar expression. ', 'http://bjcp.org/stylecenter.php', '01', 'Y', 'bcoe', 'AABC', 0, 0, 0, 0, 'session-strength, dark-color, top-fermented, british-isles, traditional-style, brown-ale-family, malty', 'Moorhouses Black Cat, Cains Dark Mild, Theakston Traditional Mild, Highgate Mild, Brains Dark, Bankss Dark Mild.', NULL),
('05', 'German Leichtbier [BJCP 5A]', 'Low Alcohol', '1.026', '1.034', '1.006', '1.01', '2.4', '3.6', '15', '28', '2', '5', '1', 'A pale, highly-attenuated, light-bodied German lager with lower alcohol and calories than normal-strength beers. Moderately bitter with noticeable malt and hop flavors, the beer is still interesting to drink.', 'http://bjcp.org/stylecenter.php', '01', 'Y', 'bcoe', 'AABC', 0, 0, 0, 0, 'session-strength, pale-color, bottom-fermented, lagered, central-europe, traditional-style, pale-lager-family, bitter, hoppy', 'Bitburger Light, Becks Light, Paulaner Munchner Hell Leicht, Paulaner Premium Leicht, Mahrs Leicht.', NULL),
('06', 'Czech Pale Lager [BJCP 3A]', 'Low Alcohol', '1.036', '1.044', '1.008', '1.014', '3', '4', '25', '35', '3', '6', '1', 'A lighter-bodied, rich, refreshing, hoppy, bitter, crisp pale Czech lager having the familiar flavors of the stronger Czech Pilsner-type beer but in a lower alcohol, lighter-bodied, and slightly less intense format.', 'http://bjcp.org/stylecenter.php', '01', 'Y', 'bcoe', 'AABC', 0, 0, 0, 0, 'session-strength, pale-color, bottom-fermented, lagered, central-europe, traditional-style, pale-lager-family, bitter, hoppy', 'Uneticke Pivo 10, Pivovar Kout na Sumave Koutska 10, Novosad Glassworks Brewery Hutske Vycepni 8, Cernyy Orel Svetle 11, Breznak Svetle Vycepni Pivo, Notch Session Pils.', NULL),
('07', 'Ordinary Bitter [BJCP 11A]', 'Low Alcohol', '1.03', '1.038', '1.007', '1.011', '3.2', '3.8', '25', '35', '8', '14', '1', 'Low gravity, low alcohol levels, and low carbonation make this an easy-drinking session beer. The malt profile can vary in flavor and intensity, but should never override the overall bitter impression. Drinkability is a critical component of the style. ', 'http://bjcp.org/stylecenter.php', '01', 'Y', 'bcoe', 'AABC', 0, 0, 0, 0, 'session-strength, amber-color, top-fermented, british-isles, traditional-style, amber-ale-family, bitter', 'Fullers Chiswick Bitter, Adnams Bitter, Youngs Bitter, Greene King IPA, Brains Bitter, Tetleys Original Bitter.', NULL),
('01', 'Australian Lager [AABC]', 'Pale Lager', '1.04', '1.05', '1.004', '1.01', '4.5', '5.1', '10', '20', '2', '4', '1', 'Light, refreshing and thirst quenching. ', 'http://www.aabc.org.au/docs/AABC2019StyleGuidelines.pdf', '02', 'Y', 'bcoe', 'AABC', 0, 0, 0, 0, 'standard-strength, amber-color, bottom-fermented, lagered, traditional-style, amber-lager-family, malty', 'Fosters Lager, Carlton Draught, XXXX, and Tooheys New', NULL),
('02', 'Helles Bock [BJCP 4C]', 'Pale Lager', '1.064', '1.072', '1.011', '1.008', '6.3', '7.4', '23', '35', '6', '11', '1', 'A relatively pale, strong, malty German lager beer with a nicely attenuated finish that enhances drinkability. The hop character is generally more apparent than in other bocks.', 'http://bjcp.org/stylecenter.php', '02', 'Y', 'bcoe', 'AABC', 0, 0, 0, 0, 'high-strength, pale-color, bottom-fermented, lagered, central-europe, traditional-style, bock-family, malty', 'Ayinger Maibock, Mahrs Bock, Hacker-Pschorr Hubertus Bock, Altenmunster Maibock, Capital Maibock, Einbecker Mai-Urbock, Blind Tiger Maibock.', NULL),
('03', 'Australian Premium Lager [AABC]', 'Pale Lager', '1.045', '1.055', '1.008', '1.012', '4.7', '6', '15', '25', '2', '6', '1', 'A clean, crisp lager, designed basically for quaffing, but containing more interest and more malt and hop character than the typical Australian session lagers.', 'http://www.aabc.org.au/docs/AABC2019StyleGuidelines.pdf', '02', 'Y', 'bcoe', 'AABC', 0, 0, 0, 0, 'standard-strength, amber-color, bottom-fermented, lagered, traditional-style, amber-lager-family, malty', 'Malt Shovel Pilsner,Boags Premium Lager.', NULL),
('04', 'International Pale Lager [BJCP 2A]', 'Pale Lager', '1.042', '1.055', '1.008', '1.014', '4.6', '6', '8', '25', '7', '14', '1', 'A well-attenuated malty amber lager with an interesting caramel or toast quality and restrained bitterness. Usually fairly well-attenuated, and can have an adjunct quality to it. Smooth, easily-drinkable lager character.', 'http://bjcp.org/stylecenter.php', '02', 'Y', 'bcoe', 'AABC', 0, 0, 0, 0, 'standard-strength, amber-color, bottom-fermented, lagered, traditional-style, amber-lager-family, malty', 'Schells Oktoberfest, Capital Winter Skal, Dos Equis Amber, Yuengling Lager, Brooklyn Lager.', NULL),
('10', 'Munich Helles [BJCP 4A]', 'Pale Lager', '1.044', '1.048', '1.006', '1.012', '4.7', '5.4', '16', '22', '3', '5', '1', 'A clean, malty, gold-colored German lager with a smooth grainy-sweet malty flavor and a soft, dry finish. Subtle spicy, floral, or herbal hops and restrained bitterness help keep the balance malty but not sweet, which helps make this beer a refreshing, everyday drink.', 'http://bjcp.org/stylecenter.php', '02', 'Y', 'bcoe', 'AABC', 0, 0, 0, 0, 'standard-strength, pale-color, bottom-fermented, lagered, central-europe, traditional-style, pale-lager-family, malty', 'Weihenstephaner Original, Hacker-Pschorr Munchner Gold, Burgerbrau Wolznacher Hell Naturtrub, Paulaner Premium Lager, Spaten Premium Lager, Lowenbrau Original.', NULL),
('05', 'German Dortmunder [BJCP 5C]', 'Pale Lager', '1.05', '1.056', '1.01', '1.015', '4.8', '6', '20', '30', '4', '7', '1', 'A pale, well-balanced, smooth German lager that is slightly stronger than the average beer with a moderate body and a mild, aromatic hop and malt character. ', 'http://bjcp.org/stylecenter.php', '02', 'Y', 'bcoe', 'AABC', 0, 0, 0, 0, 'standard-strength, pale-color, bottom-fermented, lagered, central-europe, traditional-style, pale-lager-family, balanced', 'DAB Original, Dortmunder Union Export, Dortmunder Kronen, Great Lakes Dortmunder Gold, Barrel House Duvenecks Dortmunder, Gordon Biersch Golden Export, Flensburger Gold.', NULL),
('06', 'Pre Prohibition Lager [BJCP 27]', 'Pale Lager', '1.044', '1.06', '1.01', '1.015', '4.5', '6', '25', '40', '3', '6', '1', 'A clean, refreshing, but bitter pale lager, often showcasing a grainy-sweet corn flavor. All malt or rice-based versions have a crisper, more neutral character. The higher bitterness level is the largest differentiator between this style and most modern mass-market pale lagers, but the more robust flavor profile also sets it apart.', 'http://bjcp.org/stylecenter.php', '02', 'Y', 'bcoe', 'AABC', 0, 0, 0, 0, 'standard-strength, pale-color, bottom-fermented, lagered, north-america, historical-style, pilsner-family, bitter, hoppy', 'Anchor California Lager, Coors Batch 19, Little Harpeth Chicken Scratch', NULL),
('07', 'German Pils [BJCP 5D]', 'Pale Lager', '1.044', '1.05', '1.008', '1.013', '4.4', '5.2', '25', '40', '2', '5', '1', 'A light-bodied, highly-attenuated, gold-colored, bottom-fermented bitter German beer showing excellent head retention and an elegant, floral hop aroma. Crisp, clean, and refreshing, a German Pils showcases the finest quality German malt and hops. ', 'http://bjcp.org/stylecenter.php', '02', 'Y', 'bcoe', 'AABC', 0, 0, 0, 0, 'standard-strength, pale-color, bottom-fermented, lagered, central-europe, traditional-style, pilsner-family, bitter, hoppy', 'Schonramer Pils, Trumer Pils, Konig Pilsener, Paulaner Premium Pils, Stoudt Pils, Troegs Sunshine Pils.', NULL),
('08', 'Czech Premium Pale Lager [BJCP 3B]', 'Pale Lager', '1.044', '1.056', '1.013', '1.017', '4.2', '5.8', '30', '45', '3.5', '6', '1', 'Rich, characterful pale Czech lager, with considerable malt and hop character and a long, crisp finish. Complex yet well-balanced and refreshing. The malt flavors are complex for a Pilsner-type beer, and the bitterness is strong but clean and without harshness, which gives a rounded impression that enhances drinkability.', 'http://bjcp.org/stylecenter.php', '02', 'Y', 'bcoe', 'AABC', 0, 0, 0, 0, 'standard-strength, pale-color, bottom-fermented, lagered, central-europe, traditional-style, pilsner-family, balanced, hoppy', 'Kout na Sumave Koutska 12, Uneticka 12, Pilsner Urquell, Bernard Svatecn', NULL),
('09', 'Festbier [BJCP 4B]', 'Pale Lager', '1.054', '1.057', '1.01', '1.012', '5.8', '6.3', '18', '24', '4', '6', '1', 'A smooth, clean, pale German lager with a moderately strong malty flavor and a light hop character. Deftly balances strength and drinkability, with a palate impression and finish that encourages drinking. Showcases elegant German malt flavors without becoming too heavy or filling.', 'http://bjcp.org/stylecenter.php', '02', 'Y', 'bcoe', 'AABC', 0, 0, 0, 0, 'standard-strength, pale-color, bottom-fermented, lagered, central-europe, traditional-style, pale-lager-family, malty', 'Paulaner Wiesn, Lowenbrau Oktoberfestbier, Hofbrau Festbier, Hacker-Pschorr Superior Festbier, Augustiner Oktoberfest, Schonramer Gold.', NULL),
('01', 'International Amber Lager [BJCP 2B]', 'Amber and Dark Lager', '1.042', '1.055', '1.008', '1.014', '4.6', '6', '8', '25', '7', '14', '1', 'A well-attenuated malty amber lager with an interesting caramel or toast quality and restrained bitterness. Usually fairly well-attenuated, and can have an adjunct quality to it. Smooth, easily-drinkable lager character.', 'http://bjcp.org/stylecenter.php', '03', 'Y', 'bcoe', 'AABC', 0, 0, 0, 0, 'standard-strength, amber-color, bottom-fermented, lagered, traditional-style, amber-lager-family, malty', 'Schells Oktoberfest, Capital Winter Skal, Dos Equis Amber, Yuengling Lager, Brooklyn Lager.', NULL),
('02', 'Czech Amber Lager [BJCP 3C]', 'Amber and Dark Lager', '1.044', '1.056', '1.013', '1.017', '4.4', '5.8', '20', '35', '10', '16', '1', 'Malt-driven amber Czech lager with hop character that can vary from low to quite significant. The malt flavors can vary quite a bit, leading to different interpretations ranging from drier, bready, and slightly biscuity to sweeter and somewhat caramelly. ', 'http://bjcp.org/stylecenter.php', '03', 'Y', 'bcoe', 'AABC', 0, 0, 0, 0, 'standard-strength, amber-color, bottom-fermented, lagered, central-europe, traditional-style, amber-lager-family, balanced', 'Cerny Orel polotmava 12, Primator polotmavy 13, Jihlavsky Radnicni Pivovar Zikmund, Pivovar Vysoky Chlumec Demon, Pivovar Benesov Sedm kuli, Bernard Jantar.', NULL),
('03', 'Munich Dunkel [BJCP 8A]', 'Amber and Dark Lager', '1.048', '1.056', '1.01', '1.016', '4.5', '5.6', '18', '28', '14', '28', '1', 'Characterized by depth, richness and complexity typical of darker Munich malts with the accompanying Maillard products. Deeply bready-toasty, often with chocolate-like flavors in the freshest examples, but never harsh, roasty, or astringent; a decidedly malt-balanced beer, yet still easily drinkable.', 'http://bjcp.org/stylecenter.php', '03', 'Y', 'bcoe', 'AABC', 0, 0, 0, 0, 'standard-strength, dark-color, bottom-fermented, lagered, central-europe, traditional-style, malty, dark-lager-family', 'Ayinger Altbairisch Dunkel, Hacker-Pschorr Alt Munich Dark, Weltenburger Kloster Barock-Dunkel, Ettaler Kloster Dunkel, Chuckanut Dunkel.', NULL),
('04', 'Vienna Lager [BJCP 7A]', 'Amber and Dark Lager', '1.048', '1.055', '1.01', '1.014', '4.7', '5.5', '18', '30', '9', '15', '1', 'A moderate-strength amber lager with a soft, smooth maltiness and moderate bitterness, yet finishing relatively dry. The malt flavor is clean, bready-rich, and somewhat toasty, with an elegant impression derived from quality base malts and process, not specialty malts and adjuncts.', 'http://bjcp.org/stylecenter.php', '03', 'Y', 'bcoe', 'AABC', 0, 0, 0, 0, 'standard-strength, amber-color, bottom-fermented, lagered, central-europe, traditional-style, amber-lager-family, balanced', 'Clipper City Heavy Seas Vienna Lager, Cuauhtemoc Noche Buena, Chuckanut Vienna Lager, Devils Backbone Vienna Lager, Schells Firebrick, Figueroa Mountain Danish Red Lager.', NULL),
('05', 'Marzen [BJCP 6A]', 'Amber and Dark Lager', '1.054', '1.06', '1.01', '1.014', '5.8', '6.3', '18', '24', '10', '17', '1', 'An elegant, malty German amber lager with a clean, rich, toasty and bready malt flavor, restrained bitterness, and a dry finish that encourages another drink. The overall malt impression is soft, elegant, and complex, with a rich aftertaste that is never cloying or heavy.', 'http://bjcp.org/stylecenter.php', '03', 'Y', 'bcoe', 'AABC', 0, 0, 0, 0, 'standard-strength, amber-color, bottom-fermented, lagered, central-europe, traditional-style, amber-lager-family, malty', 'Buergerliches Brauhaus Saalfeld Ur-Saalfelder, Paulaner Oktoberfest, Ayinger Oktoberfest-Marzen, Hacker-Pschorr Original Oktoberfest, Weltenburg Kloster Anno 1050.', NULL),
('06', 'Czech Dark Lager [BJCP 3D]', 'Amber and Dark Lager', '1.044', '1.056', '1.013', '1.017', '4.4', '5.8', '18', '38', '14', '35', '1', 'A rich, dark, malty Czech lager with a roast character that can vary from almost absent to quite prominent. Malty with an interesting and complex flavor profile, with variable levels of hopping providing a range of possible interpretations. ', 'http://bjcp.org/stylecenter.php', '03', 'Y', 'bcoe', 'AABC', 0, 0, 0, 0, 'standard-strength, dark-color, bottom-fermented, lagered, central-europe, traditional-style, dark-lager-family, balanced', 'Kout na Sumav Tmavy 14, Pivovar Breznice Herold, U Fleku, Budvar Tmavy Lezak, Bohemian Brewery Cherny Bock 4, Devils Backbone Moran, Notch Cerne Pivo.', NULL),
('07', 'Schwarzbier [BJCP 8B]', 'Amber and Dark Lager', '1.046', '1.052', '1.01', '1.016', '4.4', '5.4', '20', '30', '17', '30', '1', 'A dark German lager that balances roasted yet smooth malt flavors with moderate hop bitterness. The lighter body, dryness, and lack of a harsh, burnt, or heavy aftertaste helps make this beer quite drinkable.', 'http://bjcp.org/stylecenter.php', '03', 'Y', 'bcoe', 'AABC', 0, 0, 0, 0, 'standard-strength, dark-color, bottom-fermented, lagered, central-europe, traditional-style, balanced, dark-lager-family', 'Kostritzer Schwarzbier, Kulmbacher Monchshof Premium Schwarzbier, Original Badebier, Einbecker Schwarzbier, TAPS Schwarzbier, Devils Backbone Schwartz Bier.', NULL),
('08', 'California Common [BJCP 19B]', 'Amber and Dark Lager', '1.048', '1.054', '1.011', '1.014', '4.5', '5.5', '30', '45', '10', '14', '1', 'A lightly fruity beer with firm, grainy maltiness, interesting toasty and caramel flavors, and showcasing the signature Northern Brewer varietal hop character.', 'http://bjcp.org/stylecenter.php', '03', 'Y', 'bcoe', 'AABC', 0, 0, 0, 0, 'standard-strength, amber-color, bottom-fermented, north-america, traditional-style, amber-lager-family, bitter, hoppy', 'Anchor Steam, Steamworks Steam Engine Lager, Flying Dog Old Scratch Amber Lager, Schlafly Pi Common.', NULL),
('09', 'Dunkles Bock [BJCP 6C]', 'Amber and Dark Lager', '1.054', '1.072', '0.013', '1.019', '6.3', '7.2', '20', '27', '14', '22', '1', 'A dark, strong, malty German lager beer that emphasizes the malty-rich and somewhat toasty qualities of continental malts without being sweet in the finish.', 'http://bjcp.org/stylecenter.php', '03', 'Y', 'bcoe', 'AABC', 0, 0, 0, 0, 'high-strength, amber-color, bottom-fermented, lagered, central-europe, traditional-style, bock-family, malty', 'Einbecker Ur-Bock Dunkel, Kneitinger Bock, Aass Bock, Great Lakes Rockefeller Bock, New Glarus Uff-da Bock, Penn Brewery St. Nikolaus Bock.', NULL),
('01', 'Cream Ale [BJCP 1C]', 'Pale Ale', '1.042', '1.055', '1.006', '1.012', '4.2', '5.6', '15', '20', '2.5', '5', '1', 'A clean, well-attenuated, flavorful American lawnmower beer. Easily drinkable and refreshing, with more character than typical American lagers.', 'http://bjcp.org/stylecenter.php', '04', 'Y', 'bcoe', 'AABC', 0, 0, 0, 0, 'standard-strength, pale-color, any-fermentation, north-america, traditional-style, pale-ale-family, balanced', 'Genesee Cream Ale, Little Kings Cream Ale, Sleeman Cream Ale, Liebotschaner Cream Ale, New Glarus Spotted Cow, Old Style.', NULL),
('02', 'Blonde Ale [BJCP 18A]', 'Pale Ale', '1.038', '1.054', '1.008', '1.013', '3.8', '5.5', '15', '28', '3', '6', '1', 'Easy-drinking, approachable, malt-oriented American craft beer, often with interesting fruit, hop, or character malt notes. Well-balanced and clean, is a refreshing pint without aggressive flavors.', 'http://bjcp.org/stylecenter.php', '04', 'Y', 'bcoe', 'AABC', 0, 0, 0, 0, 'standard-strength, pale-color, any-fermentation, north-america, craft-style, pale-ale-family, balanced', 'Kona Big Wave, Pelican Kiwanda Cream Ale, Victory Summer Love, Russian River Aud Blonde, Widmer Blonde Ale.', NULL),
('03', 'Kolsch [BJCP 5B]', 'Pale Ale', '1.044', '1.05', '1.007', '1.011', '4.4', '5.2', '18', '30', '3.5', '5', '1', 'A clean, crisp, delicately-balanced beer usually with a very subtle fruit and hop character. Subdued maltiness throughout leads into a pleasantly well-attenuated and refreshing finish. Freshness makes a huge difference with this beer, as the delicate character can fade quickly with age. ', 'http://bjcp.org/stylecenter.php', '04', 'Y', 'bcoe', 'AABC', 0, 0, 0, 0, 'standard-strength, pale-color, top-fermented, lagered, central-europe, traditional-style, pale-ale-family, balanced', 'Fruh Kolsch, Reissdorf Kolsch, Gaffel Kolsch, Sunner Kolsch, Muhlen Kolsch, Sion Kolsch.', NULL),
('04', 'Belgian Pale Ale [BJCP 24B]', 'Pale Ale', '1.048', '1.055', '1.01', '1.014', '4.8', '5.5', '20', '30', '8', '14', '1', 'A moderately malty, somewhat fruity-spicy, easy-drinking, copper-colored Belgian ale that is somewhat less aggressive in flavor profile than many other Belgian beers. The malt character tends to be a bit biscuity with light toasty, honey-like, or caramelly components; the fruit character is noticeable and complementary to the malt. The bitterness level is generally moderate, but may not seem as high due to the flavorful malt profile.', 'http://bjcp.org/stylecenter.php', '04', 'Y', 'bcoe', 'AABC', 0, 0, 0, 0, 'standard-strength, amber-color, top-fermented, western-europe, traditional-style, pale-ale-family, balanced', 'De Koninck, Speciale Palm, Dobble Palm, Russian River Perdition, Ginder Ale, Op-Ale, St. Pieters Zinnebir, Brewers Art House Pale Ale, Avery Karma, Eisenbahn Pale Ale, Blue Moon Pale Moon.', NULL),
('05', 'Australian Sparkling Ale [BJCP 12B]', 'Pale Ale', '1.038', '1.05', '1.004', '1.006', '4.5', '6', '20', '35', '4', '7', '1', 'Smooth and balanced, all components merge together with similar intensities. Moderate flavors showcasing Australian ingredients. Large flavor dimension. Very drinkable, suited to a hot climate. Relies on yeast character. ', 'http://bjcp.org/stylecenter.php', '04', 'Y', 'bcoe', 'AABC', 0, 0, 0, 0, 'standard-strength, pale-color, top-fermented, pacific, traditional-style, pale-ale-family, bitter', 'Coopers Sparkling Ale, Coopers Original Pale Ale.', NULL),
('01', 'American Pale Ale [BJCP 18B]', 'American Pale Ale', '1.045', '1.06', '1.01', '1.015', '4.5', '6.2', '30', '50', '5', '10', '1', 'A pale, refreshing and hoppy ale, yet with sufficient supporting malt to make the beer balanced and drinkable. The clean hop presence can reflect classic or modern American or New World hop varieties with a wide range of characteristics. An average-strength hop-forward pale American craft beer, generally balanced to be more accessible than modern American IPAs. ', 'http://bjcp.org/stylecenter.php', '05', 'Y', 'bcoe', 'AABC', 0, 0, 0, 0, 'standard-strength, pale-color, top-fermented, north-america, craft-style, pale-ale-family, bitter, hoppy', 'Sierra Nevada Pale Ale, Firestone Walker Pale 31, Deschutes Mirror Pond, Great Lakes Burning River, Flying Dog Doggie Style, Troegs Pale Ale, Big Sky Scape Goat.', NULL),
('01', 'Australian Bitter Ale [AABC]', 'Bitter Ale', '1.038', '1.048', '1.005', '1.008', '4.2', '5.2', '25', '40', '8', '14', '1', 'A crisp, light flavoured, thirst-quenching Bitter  ideally suited to a hot climate. Traditionally served well chilled and highly carbonated  accentuating the characteristic tangy hop bitterness.', 'http://www.aabc.org.au/docs/AABC2019StyleGuidelines.pdf', '06', 'Y', 'bcoe', 'AABC', 0, 0, 0, 0, 'standard-strength, pale-color, top-fermented, british-isles, craft-style, pale-ale-family, bitter, hoppy', 'The major Bitter Ale brand names have survived but the modern versions are all lagers and the term “Ale” has been dropped from labelling (eg. Victoria Bitter, Melbourne Bitter, Emu Bitter). ', NULL),
('02', 'British Golden Ale [BJCP 12A]', 'Bitter Ale', '1.038', '1.053', '1.006', '1.012', '3.8', '5', '20', '45', '2', '6', '1', 'A hop-forward, average-strength to moderately-strong pale bitter. Drinkability and a refreshing quality are critical components of the style.', 'http://bjcp.org/stylecenter.php', '06', 'Y', 'bcoe', 'AABC', 0, 0, 0, 0, 'standard-strength, pale-color, top-fermented, british-isles, craft-style, pale-ale-family, bitter, hoppy', 'Hop Backs Summer Lightning, Golden Hills Exmoor Gold, Oakhams Jeffrey Hudson Bitter, Fullers Discovery, Kelham Islands Pale Rider, Crouch Vales Brewers Gold, Morland Old Golden Hen.', NULL),
('03', 'Best Bitter [BJCP 11B]', 'Bitter Ale', '1.04', '1.048', '1.008', '1.012', '3.8', '4.6', '25', '40', '8', '16', '1', 'A flavorful, yet refreshing, session beer. Some examples can be more malt balanced, but this should not override the overall bitter impression. Drinkability is a critical component of the style. ', 'http://bjcp.org/stylecenter.php', '06', 'Y', 'bcoe', 'AABC', 0, 0, 0, 0, 'standard-strength, amber-color, top-fermented, british-isles, traditional-style, amber-ale-family, bitter', 'Timothy Taylor Landlord, Fullers London Pride, Coniston Bluebird Bitter, Adnams SSB, Youngs Special, Shepherd Neame Masterbrew Bitter.', NULL),
('04', 'American Amber Ale [BJCP 19A]', 'Bitter Ale', '1.045', '1.06', '1.01', '1.015', '4.5', '6.2', '25', '40', '10', '17', '1', 'An amber, hoppy, moderate-strength American craft beer with a caramel malty flavor. The balance can vary quite a bit, with some versions being fairly malty and others being aggressively hoppy. Hoppy and bitter versions should not have clashing flavors with the caramel malt profile. ', 'http://bjcp.org/stylecenter.php', '06', 'Y', 'bcoe', 'AABC', 0, 0, 0, 0, 'standard-strength, amber-color, top-fermented, north-america, craft-style, amber-ale-family, balanced, hoppy', 'Troegs HopBack Amber Ale, Kona Lavaman Red Ale, Full Sail Amber, Deschutes Cinder Cone Red, Rogue American Amber Ale, Anderson Valley Boont Amber Ale, McNeills Firehouse Amber Ale, Mendocino Red Tail Ale.', NULL),
('05', 'Altbier [BJCP 7B]', 'Bitter Ale', '1.044', '1.052', '1.008', '1.014', '4.3', '5.5', '25', '50', '11', '17', '1', 'A well-balanced, well-attenuated, bitter yet malty, clean, and smooth, amber- to copper-colored German beer. The bitterness is balanced by the malt richness, but the malt intensity and character can range from moderate to high (the bitterness increases with the malt richness). ', 'http://bjcp.org/stylecenter.php', '06', 'Y', 'bcoe', 'AABC', 0, 0, 0, 0, 'standard-strength, amber-color, top-fermented, lagered, central-europe, traditional-style, amber-ale-family, bitter', 'Zum Uerige, Im Fuuchschen, Schumacher, Zum Schluussel, Schlosser Alt, Bolten Alt, Diebels Alt, Frankenheim Alt, Southampton Alt, BluCreek Altbier.', NULL),
('06', 'Strong Bitter [BJCP 11C]', 'Bitter Ale', '1.048', '1.06', '1.01', '1.016', '4.6', '6.2', '30', '50', '8', '18', '1', 'An average-strength to moderately-strong English bitter ale. The balance may be fairly even between malt and hops to somewhat bitter. Drinkability is a critical component of the style. A rather broad style that allows for considerable interpretation by the brewer.', 'http://bjcp.org/stylecenter.php', '06', 'Y', 'bcoe', 'AABC', 0, 0, 0, 0, 'session-strength, amber-color, top-fermented, british-isles, traditional-style, amber-ale-family, bitter', 'Shepherd Neame Bishops Finger, Youngs Ram Rod, Samuel Smiths Old Brewery Pale Ale, Bass Ale, Whitbread Pale Ale, Shepherd Neame Spitfire.', NULL),
('01', 'Scottish Heavy [BJCP 14B]', 'Brown Ale', '1.035', '1.04', '1.01', '1.015', '3.2', '3.9', '10', '20', '13', '22', '1', 'A malt-focused, generally caramelly beer with perhaps a few esters and occasionally a butterscotch aftertaste. Hops only to balance and support the malt. The malt character can range from dry and grainy to rich, toasty, and caramelly, but is never roasty and especially never has a peat smoke character. ', 'http://bjcp.org/stylecenter.php', '07', 'Y', 'bcoe', 'AABC', 0, 0, 0, 0, 'session-strength, amber-color, top-fermented, british-isles, traditional-style, amber-ale-family, malty', 'Caledonia Smooth, Orkney Raven ale, Broughton Greenmantle Ale, McEwans 70, Tennents Special Ale.', NULL),
('02', 'Scottish Export [BJCP 14C]', 'Brown Ale', '1.04', '1.06', '1.01', '1.016', '3.9', '6', '15', '30', '13', '22', '1', 'A malt-focused, generally caramelly beer with perhaps a few esters and occasionally a butterscotch aftertaste. Hops only to balance and support the malt. The malt character can range from dry and grainy to rich, toasty, and caramelly, but is never roasty and especially never has a peat smoke character. ', 'http://bjcp.org/stylecenter.php', '07', 'Y', 'bcoe', 'AABC', 0, 0, 0, 0, 'standard-strength, amber-color, top-fermented, british-isles, traditional-style, amber-ale-family, malty', 'Orkney Dark Island, Belhaven Scottish ale, Broughton Excisemans ale, Weasel Boy Plaid Ferret Scottish ale.', NULL),
('03', 'Irish Red Ale [BJCP 15A]', 'Brown Ale', '1.036', '1.046', '1.01', '1.014', '3.8', '5', '18', '28', '9', '14', '1', 'An easy-drinking pint, often with subtle flavors. Slightly malty in the balance sometimes with an initial soft toffee/caramel sweetness, a slightly grainy-biscuity palate, and a touch of roasted dryness in the finish. Some versions can emphasize the caramel and sweetness more, while others will favor the grainy palate and roasted dryness. ', 'http://bjcp.org/stylecenter.php', '07', 'Y', 'bcoe', 'AABC', 0, 0, 0, 0, 'standard-strength, amber-color, top-fermented, british-isles, traditional-style, amber-ale-family, balanced', 'OHaras Irish Red Ale, Franciscan Well Rebel Red, Smithwicks Irish Ale, Kilkenny Irish Beer, Caffreys Irish Ale, Wexford Irish Cream Ale.', NULL),
('04', 'Australian Dark/Old Ale [AABC]', 'Brown Ale', '1.04', '1.05', '1.01', '1.016', '4.5', '5.3', '15', '25', '15', '25', '1', 'A dry, mildly flavoured session beer. Malt evident but evenly balanced by hop bitterness. ', 'http://www.aabc.org.au/docs/AABC2019StyleGuidelines.pdf', '07', 'Y', 'bcoe', 'AABC', 0, 0, 0, 0, 'standard-strength, amber-color, top-fermented, british-isles, traditional-style, amber-ale-family, balanced', 'Tooheys Old Ale', NULL),
('05', 'British Brown Ale [BJCP 13B]', 'Brown Ale', '1.04', '1.052', '1.008', '1.013', '4.2', '5.4', '20', '30', '12', '22', '1', 'A malty, brown caramel-centric English ale without the roasted flavors of a Porter.', 'http://bjcp.org/stylecenter.php', '07', 'Y', 'bcoe', 'AABC', 0, 0, 0, 0, 'standard-strength, amber-color, top-fermented, british-isles, traditional-style, brown-ale-family, malty', 'Black Sheep Riggwelter Yorkshire Ale, Wychwood Hobgoblin, Maxim Double Maxim, Newcastle Brown Ale, Samuel Smiths Nut Brown Ale.', NULL),
('06', 'American Brown Ale [BJCP 19C]', 'Brown Ale', '1.045', '1.06', '1.01', '1.016', '4.3', '6.2', '20', '30', '18', '35', '1', 'A malty but hoppy beer frequently with chocolate and caramel flavors. The hop flavor and aroma complements and enhances the malt rather than clashing with it. ', 'http://bjcp.org/stylecenter.php', '07', 'Y', 'bcoe', 'AABC', 0, 0, 0, 0, 'standard-strength, dark-color, top-fermented, north-america, craft-style, brown-ale-family, balanced, hoppy', 'Big Sky Moose Drool Brown Ale, Cigar City Maduro Brown Ale, Bells Best Brown, Smuttynose Old Brown Dog Ale, Brooklyn Brown Ale, Lost Coast Downtown Brown, Avery Ellies Brown Ale.', NULL),
('01', 'English Porter [BJCP 13C]', 'Porter', '1.04', '1.052', '1.008', '1.014', '4', '5.4', '18', '35', '20', '30', '1', 'A moderate-strength brown beer with a restrained roasty character and bitterness. May have a range of roasted flavors, generally without burnt qualities, and often has a chocolate-caramel-malty profile. .', 'http://bjcp.org/stylecenter.php', '08', 'Y', 'bcoe', 'AABC', 0, 0, 0, 0, 'standard-strength, dark-color, top-fermented, british-isles, traditional-style, porter-family, malty, roasty', 'Fullers London Porter, Samuel Smith Taddy Porter, Burton Bridge Burton Porter, RCH Old Slug Porter, Nethergate Old Growler Porter.', NULL),
('02', 'American Porter [BJCP 20A]', 'Porter', '1.05', '1.07', '1.012', '1.018', '4.8', '6.5', '25', '50', '22', '40', '1', 'A substantial, malty dark beer with a complex and flavorful dark malt character.', 'http://bjcp.org/stylecenter.php', '08', 'Y', 'bcoe', 'AABC', 0, 0, 0, 0, 'standard-strength, dark-color, top-fermented, north-america, craft-style, porter-family, bitter, roasty, hoppy', 'Great Lakes Edmund Fitzgerald Porter, Anchor Porter, Smuttynose Robust Porter, Sierra Nevada Porter, Deschutes Black Butte Porter, Boulevard Bully! Porter.', NULL),
('03', 'Baltic Porter [BJCP 9C]', 'Porter', '1.06', '1.09', '1.016', '1.024', '6.5', '9.5', '20', '40', '17', '30', '1', 'Baltic Porter often has the malt flavors reminiscent of an English brown porter and the restrained roast of a schwarzbier, but with a higher OG and alcohol content than either. Very complex, with multi-layered malt and dark fruit flavors.', 'http://bjcp.org/stylecenter.php', '08', 'Y', 'bcoe', 'AABC', 0, 0, 0, 0, 'high-strength, dark-color, any-fermentation, lagered, eastern-europe, traditional-style, porter-family, malty', 'Sinebrychoff Porter (Finland), Okocim Porter (Poland), Aldaris Porteris (Latvia), Baltika 6 Porter (Russia), Utenos Porter (Lithuania), Stepan Razin Porter (Russia), Zywiec Porter (Poland), Nogne o Porter (Norway), Neuzeller Kloster-Brou Neuzeller Porter (Germany).', NULL),
('01', 'Sweet Stout [BJCP 16A]', 'Stout', '1.044', '1.06', '1.012', '1.024', '4', '6', '20', '40', '30', '40', '1', 'A very dark, sweet, full-bodied, slightly roasty ale that can suggest coffee-and-cream, or sweetened espresso. ', 'http://bjcp.org/stylecenter.php', '09', 'Y', 'bcoe', 'AABC', 0, 0, 0, 0, 'standard-strength, dark-color, top-fermented, british-isles, traditional-style, stout-family, malty, roasty, sweet', 'Mackesons XXX Stout, Watneys Cream Stout, St. Peters Cream Stout, Marstons Oyster Stout, Samuel Adams Cream Stout, Left Hand Milk Stout, Lancaster Milk Stout.', NULL),
('02', 'Irish Stout [BJCP 15B]', 'Stout', '1.036', '1.044', '1.007', '1.011', '4', '4.5', '25', '45', '25', '40', '1', 'A black beer with a pronounced roasted flavor, often similar to coffee. The balance can range from fairly even to quite bitter, with the more balanced versions having a little malty sweetness and the bitter versions being quite dry. Draught versions typically are creamy from a nitro pour, but bottled versions will not have this dispense-derived character. The roasted flavor can be dry and coffee-like to somewhat chocolaty.', 'http://bjcp.org/stylecenter.php', '09', 'Y', 'bcoe', 'AABC', 0, 0, 0, 0, 'standard-strength, dark-color, top-fermented, british-isles, traditional-style, stout-family, bitter, roasty', 'Guinness Draught, OHaras Irish Stout, Beamish Irish Stout, Murphys Irish Stout, Harpoon Boston Irish Stout.', NULL),
('03', 'Oatmeal Stout [BJCP 16B]', 'Stout', '1.045', '1.065', '1.01', '1.018', '4.2', '5.9', '25', '40', '22', '40', '1', 'A very dark, full-bodied, roasty, malty ale with a complementary oatmeal flavor. The sweetness, balance, and oatmeal impression can vary considerably.', 'http://bjcp.org/stylecenter.php', '09', 'Y', 'bcoe', 'AABC', 0, 0, 0, 0, 'standard-strength, dark-color, top-fermented, british-isles, traditional-style, stout-family, balanced, roasty', 'Samuel Smith Oatmeal Stout, Youngs Oatmeal Stout, McAuslan Oatmeal Stout, Maclays Oat Malt Stout, Broughton Kinmount Willie Oatmeal Stout, Anderson Valley Barney Flats Oatmeal Stout, Troegs Oatmeal Stout, New Holland The Poet, Goose Island Oatmeal Stout, Wolavers Oatmeal Stout.', NULL),
('04', 'Irish Extra Stout [BJCP 15C]', 'Stout', '1.052', '1.062', '1.01', '1.014', '5.5', '6.5', '35', '50', '25', '40', '1', 'A fuller-bodied black beer with a pronounced roasted flavor, often similar to coffee and dark chocolate with some malty complexity. The balance can range from moderately bittersweet to bitter, with the more balanced versions having up to moderate malty richness and the bitter versions being quite dry.', 'http://bjcp.org/stylecenter.php', '09', 'Y', 'bcoe', 'AABC', 0, 0, 0, 0, 'high-strength, dark-color, top-fermented, british-isles, traditional-style, stout-family, bitter, roasty', 'Guinness Extra Stout, OHaras Leann Follain.', NULL),
('01', 'Tropical Stout [BJCP 16C]', 'Strong Stout ', '1.056', '1.075', '1.01', '1.018', '5.5', '8', '30', '50', '30', '40', '1', 'A very dark, sweet, fruity, moderately strong ale with smooth roasty flavors without a burnt harshness. ', 'http://bjcp.org/stylecenter.php', '10', 'Y', 'bcoe', 'AABC', 0, 0, 0, 0, 'high-strength, dark-color, top-fermented, british-isles, traditional-style, stout-family, malty, roasty, sweet', 'Lion Stout (Sri Lanka), Dragon Stout (Jamaica), ABC Stout (Singapore), Royal Extra &quot;The Lion Stout&quot; (Trinidad), Jamaica Stout (Jamaica).', NULL),
('02', 'Foreign Extra Stout [BJCP 16D]', 'Strong Stout ', '1.056', '1.075', '1.01', '1.018', '6.5', '8', '50', '70', '30', '40', '1', 'A very dark, moderately strong, fairly dry, stout with prominent roast flavors.', 'http://bjcp.org/stylecenter.php', '10', 'Y', 'bcoe', 'AABC', 0, 0, 0, 0, 'high-strength, dark-color, top-fermented, british-isles, traditional-style, stout-family, balanced, roasty', 'Guinness Foreign Extra Stout, Ridgeway Foreign Export Stout, Coopers Best Extra Stout, Elysian Dragonstooth Stout.', NULL),
('03', 'American Stout [BJCP 20B]', 'Strong Stout ', '1.05', '1.075', '1.01', '1.022', '5', '7', '35', '75', '30', '40', '1', 'A fairly strong, highly roasted, bitter, hoppy dark stout. Has the body and dark flavors typical of stouts with a more aggressive American hop character and bitterness.', 'http://bjcp.org/stylecenter.php', '10', 'Y', 'bcoe', 'AABC', 0, 0, 0, 0, 'high-strength, dark-color, top-fermented, north-america, craft-style, stout-family, bitter, roasty, hoppy', 'Rogue Shakespeare Stout, Deschutes Obsidian Stout, Sierra Nevada Stout, North Coast Old No. 38, Avery Out of Bounds Stout.', NULL),
('04', 'Imperial Stout [BJCP 20C]', 'Strong Stout ', '1.075', '1.115', '1.018', '1.03', '8', '12', '50', '90', '30', '40', '1', 'An intensely-flavored, big, dark ale with a wide range of flavor balances and regional interpretations. Roasty-burnt malt with deep dark or dried fruit flavors, and a warming, bittersweet finish. Despite the intense flavors, the components need to meld together to create a complex, harmonious beer, not a hot mess.', 'http://bjcp.org/stylecenter.php', '10', 'Y', 'bcoe', 'AABC', 0, 0, 0, 0, 'very-high-strength, dark-color, top-fermented, british-isles, north-america, traditional-style, craft-style, stout-family, malty, bitter, roasty', 'American - North Coast Old Rasputin Imperial Stout, Cigar City Marshal Zhukovs Imperial Stout; English - Courage Imperial Russian Stout, Le Coq Imperial Extra Double Stout, Samuel Smith Imperial Stout.', NULL),
('01', 'English IPA [BJCP 12C]', 'India Pale Ale ', '1.05', '1.075', '1.01', '1.018', '5', '7.5', '40', '60', '6', '14', '1', 'A hoppy, moderately-strong, very well-attenuated pale English ale with a dry finish and a hoppy aroma and flavor. Classic English ingredients provide the best flavor profile.', 'http://bjcp.org/stylecenter.php', '11', 'Y', 'bcoe', 'AABC', 0, 0, 0, 0, 'high-strength, pale-color, top-fermented, british-isles, traditional-style, ipa-family, bitter, hoppy', 'Freeminer Trafalgar IPA, Fullers Bengal Lancer IPA, Worthington White Shield, Ridgeway IPA, Emersons 1812 IPA, Meantime India Pale Ale, Summit India Pale Ale, Samuel Smiths India Ale, Hampshire Pride of Romsey IPA, Burton Bridge Empire IPA, Marstons Old Empire, Belhaven Twisted Thistle IPA.', NULL),
('02', 'American IPA [BJCP 21A]', 'India Pale Ale ', '1.056', '1.07', '1.008', '1.014', '5.5', '7.5', '40', '70', '6', '14', '1', 'A decidedly hoppy and bitter, moderately strong American pale ale, showcasing modern American and New World hop varieties. The balance is hop-forward, with a clean fermentation profile, dryish finish, and clean, supporting malt allowing a creative range of hop character to shine through.', 'http://bjcp.org/stylecenter.php', '11', 'Y', 'bcoe', 'AABC', 0, 0, 0, 0, 'high-strength, pale-color, top-fermented, north-america, craft-style, ipa-family, bitter, hoppy', 'Russian River Blind Pig IPA, Bells Two-Hearted Ale, Firestone Walker Union Jack, Alpine Duet, New Belgium Ranger IPA, Fat Heads Head Hunter, Stone IPA, Lagunitas IPA.', NULL),
('01', 'New England IPA [BJCP 21B]', 'Specialty IPA ', '1.06', '1.085', '1.01', '1.015', '6', '9', '25', '60', '3', '7', '1', 'Recognizable as an IPA by balance - a hop-forward, bitter, dryish beer - with something else present to distinguish it from the standard categories. Should have good drinkability, regardless of the form. Excessive harshness and heaviness are typically faults, as are strong flavor clashes between the hops and the other specialty ingredients.', 'http://bjcp.org/stylecenter.php', '12', 'Y', 'bcoe', 'AABC', 1, 0, 0, 0, 'high-strength, pale-color, top-fermented, north-america, craft-style, ipa-family, specialty-family, bitter, hoppy', 'Hill Farmstead Susan, Other Half Green Diamonds Double IPA,  Tired Hands Alien Church,  Tree House Julius,  Trillium Congress Street. WeldWerks Juicy Bits', NULL),
('02', 'White IPA [BJCP 21B]', 'Specialty IPA ', '1.056', '1.065', '1.01', '1.016', '5.5', '7', '40', '70', '5', '8', '1', 'A fruity, spicy, refreshing version of an American IPA, but with a lighter color, less body, and featuring either the distinctive yeast and/or spice additions typical of a Belgian witbier.', 'http://bjcp.org/stylecenter.php', '12', 'Y', 'bcoe', 'AABC', 1, 0, 0, 0, 'high-strength, pale-color, top-fermented, north-america, craft-style, ipa-family, specialty-family, bitter, hoppy, spice', 'Blue Point White IPA, Deschutes Chainbreaker IPA, Harpoon The Long Thaw, New Belgium Accumulation', 'Entrant MUST specify a strength (session: 3.0-5.0%, standard: 5.0-7.5%, double: 7.5-9.5%).'),
('03', 'Red IPA [BJCP 21B]', 'Specialty IPA ', '1.056', '1.07', '1.008', '1.016', '5.5', '7.5', '40', '70', '11', '19', '1', 'Hoppy, bitter, and moderately strong like an American IPA, but with some caramel, toffee, and/or dark fruit malt character. Retaining the dryish finish and lean body that makes IPAs so drinkable, a Red IPA is a little more flavorful and malty than an American IPA without being sweet or heavy.', 'http://bjcp.org/stylecenter.php', '12', 'Y', 'bcoe', 'AABC', 1, 0, 0, 0, 'high-strength, amber-color, top-fermented, north-america, craft-style, ipa-family, specialty-family, bitter, hoppy', 'Green Flash Hop Head Red Double Red IPA (double), Midnight Sun Sockeye Red, Sierra Nevada Flipside Red IPA, Summit Horizon Red IPA, Odell Runoff Red IPA', 'Entrant MUST specify a strength (session: 3.0-5.0%, standard: 5.0-7.5%, double: 7.5-9.5%).'),
('04', 'Brown IPA [BJCP 21B]', 'Specialty IPA ', '1.056', '1.07', '1.008', '1.016', '5.5', '7.5', '40', '70', '11', '19', '1', 'Hoppy, bitter, and moderately strong like an American IPA, but with some caramel, chocolate, toffee, and/or dark fruit malt character as in an American Brown Ale. Retaining the dryish finish and lean body that makes IPAs so drinkable, a Brown IPA is a little more flavorful and malty than an American IPA without being sweet or heavy.', 'http://bjcp.org/stylecenter.php', '12', 'Y', 'bcoe', 'AABC', 1, 0, 0, 0, 'high-strength, dark-color, top-fermented, north-america, craft-style, ipa-family, specialty-family, bitter, hoppy', 'Dogfish Head Indian Brown Ale, Grand Teton Bitch Creek, Harpoon Brown IPA, Russian River Janet', 'Entrant MUST specify a strength (session: 3.0-5.0%, standard: 5.0-7.5%, double: 7.5-9.5%).'),
('05', 'Rye IPA [BJCP 21B]', 'Specialty IPA ', '1.056', '1.075', '1.008', '1.014', '5.5', '8', '50', '75', '6', '14', '1', 'A decidedly hoppy and bitter, moderately strong American pale ale, showcasing modern American and New World hop varieties and rye malt. The balance is hop-forward, with a clean fermentation profile, dry finish, and clean, supporting malt allowing a creative range of hop character to shine through.', 'http://bjcp.org/stylecenter.php', '12', 'Y', 'bcoe', 'AABC', 1, 0, 0, 0, 'high-strength, amber-color, top-fermented, north-america, craft-style, ipa-family, specialty-family, bitter, hoppy', 'Arcadia Sky High Rye, Bear Republic Hop Rod Rye, Founders Reds Rye, Great Lakes Rye of the Tiger, Sierra Nevada Ruthless Rye', 'Entrant MUST specify a strength (session: 3.0-5.0%, standard: 5.0-7.5%, double: 7.5-9.5%).'),
('06', 'Belgian IPA [BJCP 21B]', 'Specialty IPA', '1.058', '1.08', '1.008', '1.016', '6.2', '9.5', '50', '100', '5', '15', '1', 'An IPA with the fruitiness and spiciness derived from the use of Belgian yeast. The examples from Belgium tend to be lighter in color and more attenuated, similar to a tripel that has been brewed with more hops. This beer has a more complex flavor profile and may be higher in alcohol than a typical IPA.', 'http://bjcp.org/stylecenter.php', '12', 'Y', 'bcoe', 'AABC', 1, 0, 0, 0, 'high-strength, pale-color, top-fermented, north-america, craft-style, ipa-family, specialty-family, bitter, hoppy', 'Brewery Vivant Triomphe, Houblon Chouffe, Epic Brainless IPA, Green Flash Le Freak, Stone Cali-Belgique, Urthel Hop It', 'Entrant MUST specify a strength (session: 3.0-5.0%, standard: 5.0-7.5%, double: 7.5-9.5%).'),
('07', 'Black IPA [BJCP 21B]', 'Specialty IPA', '1.05', '1.085', '1.01', '1.018', '5.5', '9', '50', '90', '25', '40', '1', 'A beer with the dryness, hop-forward balance, and flavor characteristics of an American IPA, only darker in color ', 'http://bjcp.org/stylecenter.php', '12', 'Y', 'bcoe', 'AABC', 1, 0, 0, 0, 'high-strength, dark-color, top-fermented, north-america, craft-style, ipa-family, specialty-family, bitter, hoppy', '21st Amendment Back in Black (standard), Deschutes Hop in the Dark CDA (standard), Rogue Dad', 'Entrant MUST specify a strength (session: 3.0-5.0%, standard: 5.0-7.5%, double: 7.5-9.5%).'),
('08', 'Double IPA [BJCP 22A]', 'Specialty IPA', '1.065', '1.085', '1.008', '1.018', '7.5', '10', '60', '120', '6', '14', '1', 'An intensely hoppy, fairly strong pale ale without the big, rich, complex maltiness and residual sweetness and body of an American barleywine. Strongly hopped, but clean, dry, and lacking harshness. Drinkability is an important characteristic; this should not be a heavy, sipping beer.', 'http://bjcp.org/stylecenter.php', '12', 'Y', 'bcoe', 'AABC', 0, 0, 0, 0, 'very-high-strength, pale-color, top-fermented, north-america, craft-style, ipa-family, bitter, hoppy', 'Russian River Pliny the Elder, Port Brewing Hop 15, Three Floyds Dreadnaught, Avery Majaraja, Firestone Walker Double Jack, Alchemist Heady Topper, Bells Hopslam, Stone Ruination IPA, Great Divide Hercules Double IPA, Rogue XS Imperial India Pale Ale, Fat Heads Hop Juju, Alesmith Yulesmith Summer, Sierra Nevada Hoptimum.', NULL),
('01', 'Weissbier [BJCP 10A]', 'Wheat and Ryle Ale', '1.044', '1.052', '1.01', '1.014', '4.3', '5.6', '8', '16', '2', '6', '1', 'A pale, refreshing German wheat beer with high carbonation, dry finish, a fluffy mouthfeel, and a distinctive banana-and-clove yeast character.', 'http://bjcp.org/stylecenter.php', '13', 'Y', 'bcoe', 'AABC', 0, 0, 0, 0, 'standard-strength, pale-color, top-fermented, central-europe, traditional-style, wheat-beer-family, malty', 'Weihenstephaner Hefeweissbier, Schneider Weisse Weizenhell, Paulaner Hefe-Weizen, Hacker-Pschorr Weisse, Ayinger Brau Weisse.', NULL),
('02', 'Witbier [BJCP 24A]', 'Wheat and Ryle Ale', '1.044', '1.052', '1.008', '1.012', '4.5', '5.5', '8', '20', '2', '4', '1', 'A refreshing, elegant, tasty, moderate-strength wheat-based ale.', 'http://bjcp.org/stylecenter.php', '13', 'Y', 'bcoe', 'AABC', 0, 0, 0, 0, 'standard-strength, pale-color, top-fermented, western-europe, traditional-style, wheat-beer-family, spice', 'Hoegaarden Wit, St. Bernardus Blanche, Celis White, Vuuve 5, Brugs Tarwebier (Blanche de Bruges), Wittekerke, Allagash White, Blanche de Bruxelles, Ommegang Witte, Avery White Rascal, Unibroue Blanche de Chambly, Sterkens White Ale, Bells Winter White Ale, Victory Whirlwind Witbier, Hitachino Nest White Ale.', NULL),
('03', 'Dunkles Weissbier [BJCP 10B]', 'Wheat and Ryle Ale', '1.044', '1.056', '1.01', '1.014', '4.3', '5.6', '10', '18', '14', '23', '1', 'A moderately dark German wheat beer with a distinctive banana-and-clove yeast character, supported by a toasted bread or caramel malt flavor. Highly carbonated and refreshing, with a creamy, fluffy texture and light finish that encourages drinking. ', 'http://bjcp.org/stylecenter.php', '13', 'Y', 'bcoe', 'AABC', 0, 0, 0, 0, 'standard-strength, amber-color, top-fermented, central-europe, traditional-style, wheat-beer-family, malty', 'Weihenstephaner Hefeweissbier Dunkel, Ayinger Ur-Weisse, Franziskaner Dunkel Hefe-Weisse, Ettaler Weissbier Dunkel, Hacker-Pschorr Weisse Dark, Tucher Dunkles Hefe Weizen.', NULL),
('04', 'Roggenbier [BJCP 27]', 'Wheat and Ryle Ale', '1.046', '1.056', '1.01', '1.014', '4.5', '6', '10', '20', '14', '19', '1', 'A dunkelweizen made with rye rather than wheat, but with a greater body and light finishing hops.', 'http://bjcp.org/stylecenter.php', '13', 'Y', 'bcoe', 'AABC', 0, 0, 0, 0, 'standard-strength, amber-color, top-fermenting, central-europe, historical-style, wheat-beer-family', 'Thurn und Taxis Roggen', NULL),
('05', 'American Wheat Beer [BJCP1D]', 'Wheat and Ryle Ale', '1.04', '1.055', '1.008', '1.013', '4', '5.5', '15', '30', '3', '6', '1', 'Refreshing wheat beers that can display more hop character and less yeast character than their German cousins. A clean fermentation character allows bready, doughy, or grainy wheat flavors to be complemented by hop flavor and bitterness rather than yeast qualities.', 'http://bjcp.org/stylecenter.php', '13', 'Y', 'bcoe', 'AABC', 0, 0, 0, 0, 'standard-strength, pale-color, any-fermentation, north-america, craft-style, wheat-beer-family, balanced', 'Bells Oberon, Goose Island 312 Urban Wheat Ale, Widmer Hefeweizen, Boulevard Wheat Beer. ', NULL),
('06', 'Weizenbock [BJCP10C]', 'Wheat and Ryle Ale', '1.064', '1.09', '1.015', '1.022', '6.5', '9', '15', '30', '6', '25', '1', 'A strong, malty, fruity, wheat-based ale combining the best malt and yeast flavors of a weissbier (pale or dark) with the malty-rich flavor, strength, and body of a bock (standard or doppelbock). A weissbier brewed to bock or doppelbock strength. Schneider also produces an Eisbock version. Pale and dark versions exist, although dark are more common. Pale versions have less rich malt complexity and often more hops, as with doppelbocks. Lightly oxidized Maillard products can produce some rich, intense flavors and aromas that are often seen in aged imported commercial products; fresher versions will not have this character. Well-aged examples might also take on a slight sherry-like complexity.', 'http://bjcp.org/stylecenter.php', '13', 'Y', 'bcoe', 'AABC', 1, 0, 0, 0, 'high-strength, amber-color, pale-color, top-fermented, central-europe, traditional-style, wheat-beer-family, malty', 'Dark - Schneider Aventinus, Schneider Aventinus Eisbock, Eisenbahn Vigorosa, Plank Bavarian Dunkler Weizenbock; Pale - Weihenstephaner Vitus, Plank Bavarian Heller Weizenbock.', 'The entrant must specify whether the entry is a pale or a dark variant.'),
('07', 'Wheatwine [BJCP22D]', 'Wheat and Ryle Ale', '1.08', '1.12', '1.016', '1.03', '8', '12', '30', '60', '8', '15', '1', 'A richly textured, high alcohol sipping beer with a significant grainy, bready flavor and sleek body. The emphasis is first on the bready, wheaty flavors with interesting complexity from malt, hops, fruity yeast character and alcohol complexity.', 'http://bjcp.org/stylecenter.php', '13', 'Y', 'bcoe', 'AABC', 0, 0, 0, 0, 'very-high-strength, amber-color, top-fermented, north-america, craft-style, strong-ale-family, wheat-beer-family, balanced, hoppy', 'Rubicon Brewing Company Winter Wheat Wine, Two Brothers Bare Trees Weiss Wine, Smuttynose Wheat Wine, Boulevard Brewing Company Harvest Dance, Portsmouth Wheat Wine.', NULL),
('01', 'Berliner Weisse [BJCP 23A]', 'Sour Ale', '1.028', '1.032', '1.003', '1.006', '2.8', '3.8', '3', '8', '2', '3', '1', 'A very pale, refreshing, low-alcohol German wheat beer with a clean lactic sourness and a very high carbonation level. A light bread dough malt flavor supports the sourness, which shouldnt seem artificial or funky.', 'http://bjcp.org/stylecenter.php', '14', 'Y', 'bcoe', 'AABC', 0, 0, 0, 0, 'session-beer, pale-color, top-fermented, central-europe, traditional-style, wheat-beer-family, sour', 'Schultheiss Berliner Weisse, Berliner Kindl Weisse, Nodding Head Berliner Weisse, Bahnhof Berliner Style Weisse, New Glarus Berliner Weiss.', NULL),
('02', 'Gose [BJCP 27]', 'Sour Ale', '1.036', '1.056', '1.006', '1.01', '4.2', '4.8', '5', '12', '3', '4', '1', 'A highly-carbonated, tart and fruity wheat ale with a restrained coriander and salt character and low bitterness. Very refreshing, with bright flavors and high attenuation.', 'http://bjcp.org/stylecenter.php', '14', 'Y', 'bcoe', 'AABC', 0, 0, 0, 0, 'standard-strength, pale-color, top-fermented, centraleurope, historical-style, wheat-beer-family, sour, spice', 'Anderson Valley Gose, Bayerisch Bahnhof Leipziger Gose, Dollnitzer Ritterguts Gose', NULL),
('03', 'Flanders Red Ale [BJCP 23B]', 'Sour Ale', '1.048', '1.057', '1.002', '1.012', '4.6', '6.5', '10', '25', '10', '16', '1', 'A complex, sour, fruity, red wine-like Belgian-style ale with interesting supportive malt flavors and a melange of fruit complexity. The dry finish and tannin completes the mental image of a fine red wine.', 'http://bjcp.org/stylecenter.php', '14', 'Y', 'bcoe', 'AABC', 0, 0, 0, 0, 'standard-strength, amber-color, top-fermenting, western-europe, traditional-style, sour-ale-family, balanced, sour, wood', 'Rodenbach Grand Cru, Rodenbach Klassiek, Bellegems Bruin, Duchesse de Bourgogne, Petrus Oud Bruin, Southampton Flanders Red Ale.', NULL),
('04', 'Lambic [BJCP 23D]', 'Sour Ale', '1.04', '1.054', '1.001', '1.01', '5', '6.5', '0', '10', '3', '7', '1', 'A fairly sour, often moderately funky wild Belgian wheat beer with sourness taking the place of hop bitterness in the balance. Traditionally spontaneously fermented in the Brussels area and served uncarbonated, the refreshing acidity makes for a very pleasant cafe drink.', 'http://bjcp.org/stylecenter.php', '14', 'Y', 'bcoe', 'AABC', 0, 0, 0, 0, 'standard-strength, pale-color, wild-fermented, western-europe, traditional-style, wheat-beer-family, sour', 'The only bottled version readily available is Cantillon Grand Cru Bruocsella of whatever single batch vintage the brewer deems worthy to bottle. De Cam sometimes bottles their very old ( years) lambic. In and around Brussels there are specialty cafes that often have draught lambics from traditional brewers or blenders such as Boon, De Cam, Cantillon, Drie Fonteinen, Lindemans, Timmermans and Girardin.', NULL),
('05', 'Gueuze [BJCP 23E]', 'Sour Ale', '1.04', '1.06', '1', '1.006', '5', '8', '0', '10', '3', '7', '1', 'A complex, pleasantly sour but balanced wild Belgian wheat beer that is highly carbonated and very refreshing. The spontaneous fermentation character can provide a very interesting complexity, with a wide range of wild barnyard, horse blanket, or leather characteristics intermingling with citrusy-fruity flavors and acidity', 'http://bjcp.org/stylecenter.php', '14', 'Y', 'bcoe', 'AABC', 0, 0, 0, 0, 'high-strength, pale-color, wild-fermented, western-europe, traditional-style, wheat-beer-family, aged, sour', 'Boon Oude Gueuze, Boon Oude Gueuze Mariage Parfait, De Cam Gueuze, De Cam/Drei Fonteinen Millennium Gueuze, Drie Fonteinen Oud Gueuze, Cantillon Gueuze, Hanssens Oude Gueuze, Lindemans Gueuze Cuvee Rene, Girardin Gueuze (Black Label), Mort Subite (Unfiltered) Gueuze, Oud Beersel Oude Gueuze.', NULL),
('06', 'Fruit Lambic [BJCP 23F]', 'Sour Ale', '1.04', '1.06', '1', '1.006', '5', '7', '0', '10', '3', '7', '1', 'A complex, fruity, pleasantly sour, wild wheat ale fermented by a variety of Belgian microbiota, and showcasing the fruit contributions blended with the wild character.', 'http://bjcp.org/stylecenter.php', '14', 'Y', 'bcoe', 'AABC', 1, 0, 0, 0, 'standard-strength, pale-color, wild-fermented, western-europe, traditional-style, wheat-beer-family, sour, fruit', 'Boon Framboise Marriage Parfait, Boon Kriek Mariage Parfait, Boon Oude Kriek, Cantillon Fou Foune, Cantillon Kriek, Cantillon Lou Pepe Kriek, Cantillon Lou Pepe Framboise, Cantillon Rose de Gambrinus, Cantillon St. Lamvinus, Cantillon Vigneronne, De Cam Oude Kriek, Drie Fonteinen Kriek, Girardin Kriek, Hanssens Oude Kriek, Oud Beersel Kriek, Mort Subite Kriek.', 'The type of fruit used must be specified. The brewer must declare a carbonation level (low, medium, high) and a sweetness level (low/none, medium, high).'),
('07', 'Oud Bruin [BJCP 23C]', 'Sour Ale', '1.04', '1.074', '1.008', '1.012', '4', '8', '20', '25', '15', '22', '1', 'A malty, fruity, aged, somewhat sour Belgian-style brown ale.', 'http://bjcp.org/stylecenter.php', '14', 'Y', 'bcoe', 'AABC', 0, 0, 0, 0, 'standard-strength, dark-color, top-fermented, western-europe, traditional-style, sour-ale-family, malty, sour', 'Liefmans Goudenband, Liefmans Odnar, Liefmans Oud Bruin, Ichtegem Old Brown, Riva Vondel.', NULL),
('08', 'Brett Beer [BJCP 28A]', 'Sour Ale', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '1', 'An interesting and refreshing variation on the base style, often drier and fruitier than expected, with at most a light acidity. Funky notes are generally restrained in 100% Brett examples, except in older examples.', 'http://bjcp.org/stylecenter.php', '14', 'Y', 'bcoe', 'AABC', 1, 0, 0, 0, 'wild-fermentation, north-america, craft-style, specialty-beer', 'Boulevard Saison Brett, Hill Farmstead Arthur,  Logsdon Seizoen Bretta, Russian River Sanctification,  The Bruery Saison Rue,  Victory Helios', 'The entrant must specify either a base beer style (Classic Style, or a generic style family) or provide a description of the ingredients/specs/desired character. The entrant must specify if a 100% Brett fermentation was conducted. The entrant may specify the strain(s) of Brettanomyces used.'),
('09', 'Mixed Fermentation Sour [BJCP 28]', 'Sour Ale', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '1', 'A sour and/or funky version of a base style of beer.', 'http://bjcp.org/stylecenter.php', '14', 'Y', 'bcoe', 'AABC', 1, 0, 0, 0, 'wild-fermentation, north-america, craft-style, specialty-beer, sour', 'Bruery Tart of Darkness, Jolly Pumpkin Calabaza Blanca, Cascade Vlad the Imp Aler, Russian River Temptation, Boulevard Love Child, Hill Farmstead Bi', 'The entrant must specify a description of the beer, identifying the yeast/bacteria used and either a base style or the ingredients/specs/target character of the beer.'),
('01', 'Saison [BJCP 25B]', 'Belgian Ale', '1.048', '1.065', '1.002', '1.008', '3.5', '9.5', '20', '35', '5', '22', '1', 'Most commonly, a pale, refreshing, highly-attenuated, moderately-bitter, moderate-strength Belgian ale with a very dry finish. Typically highly carbonated, and using non-barley cereal grains and optional spices for complexity, as complements the expressive yeast character that is fruity, spicy, and not overly phenolic. Less common variations include both lower-alcohol and higher-alcohol products, as well as darker versions with additional malt character.', 'http://bjcp.org/stylecenter.php', '15', 'Y', 'bcoe', 'AABC', 1, 0, 0, 0, 'standard-strength, pale-color, top-fermented, western-europe, traditional-style, bitter', 'Ellezelloise Saison, Fanome Saison, Lefebvre Saison 1900, Saison Dupont Vieille Provision, Saison de Pipaix, Saison Regal, Saison Voisin, Boulevard Tank 7 Farmhouse Ale.', 'The entrant must specify the strength (table, standard, super) and the color (pale, dark). '),
('02', 'Biere de Garde [BJCP 24C]', 'Belgian Ale', '1.06', '1.08', '1.008', '1.016', '6', '8.5', '18', '28', '6', '19', '1', 'A fairly strong, malt-accentuated, lagered artisanal beer with a range of malt flavors appropriate for the color. All are malty yet dry, with clean flavors and a smooth character. Three main variations are included in the style: the brown (brune), the blond (blonde), and the amber (ambree). The darker versions will have more malt character, while the paler versions can have more hops (but still are malt-focused beers). A related style is Biere de Mars, which is brewed in March (Mars) for present use and will not age as well. Attenuation rates are in the 80-85% range. Some fuller-bodied examples exist, but these are somewhat rare. Age and oxidation in imports often increases fruitiness, caramel flavors, and adds corked and musty notes; these are all signs of mishandling, not characteristic elements of the style.', 'http://bjcp.org/stylecenter.php', '15', 'Y', 'bcoe', 'AABC', 1, 0, 0, 0, 'high-strength, pale-color, amber-color, any-fermentation, lagered, western-europe, traditional-style, amber-ale-family, malty', 'Ch&quot;Ti (brown and blond), Jenlain (amber and blond), La Choulette (all 3 versions), St. Amand (brown), Saint Sylvestre 3 Monts (blond), Russian River Perdition.', 'Entrant must specify blond, amber, or brown biere de garde. If no color is specified, the judge should attempt to judge based on initial observation, expecting a malt flavor and balance that matches the color.'),
('03', 'Trappist Single [BJCP 26A]', 'Belgian Ale', '1.044', '1.054', '1.004', '1.01', '4.8', '6', '25', '45', '3', '5', '1', 'A pale, bitter, highly attenuated and well carbonated Trappist ale, showing a fruity-spicy Trappist yeast character, a spicy-floral hop profile, and a soft, supportive grainy-sweet malt palate.', 'http://bjcp.org/stylecenter.php', '15', 'Y', 'bcoe', 'AABC', 0, 0, 0, 0, 'standard-strength, pale-color, top-fermenting, western-europe, craft-style, bitter, hoppy', 'Westvleteren Blond (green cap), Westmalle Extra, Achel 5 Blond, Chimay Doree, Lost Abbey Devotion.', NULL),
('04', 'Belgian Blond Ale [BJCP 25A]', 'Belgian Ale', '1.062', '1.075', '1.008', '1.018', '6', '7.5', '15', '30', '4', '7', '1', 'A moderate-strength golden ale that has a subtle fruity-spicy Belgian yeast complexity, slightly malty-sweet flavor, and dry finish.', 'http://bjcp.org/stylecenter.php', '15', 'Y', 'bcoe', 'AABC', 0, 0, 0, 0, 'high-strength, pale-color, top-fermented, western-europe, traditional-style, balanced', 'Leffe Blond, Affligem Blond, La Trappe (Koningshoeven) Blond, Grimbergen Blond, Val-Dieu Blond.', NULL),
('05', 'Belgian Dubbel [BJCP 26B]', 'Belgian Ale', '1.062', '1.075', '1.008', '1.018', '6', '7.6', '15', '25', '10', '17', '1', 'A deep reddish-copper, moderately strong, malty, complex Trappist ale with rich malty flavors, dark or dried fruit esters, and light alcohol blended together in a malty presentation that still finishes fairly dry. Comments: Most commercial examples are in the 6.5 - 7% ABV range. Traditionally bottle-conditioned (&quot;refermented in the bottle&quot;). ', 'http://bjcp.org/stylecenter.php', '15', 'Y', 'bcoe', 'AABC', 0, 0, 0, 0, 'high-strength, amber-color, top-fermented, western-europe, traditional-style, malty', 'Westmalle Dubbel, St. Bernardus Pater 6, La Trappe Dubbel, Corsendonk Abbey Brown Ale, Grimbergen Double, Affligem Dubbel, Chimay Premiere (Red), Pater Lieven Bruin, Duinen Dubbel, St. Feuillien Brune, New Belgium Abbey Belgian Style Ale, Stoudts Abbey Double Ale, Russian River Benediction, Flying Fish Dubbel, Lost Abbey Lost and Found Abbey Ale, Allagash Double.', NULL),
('06', 'Belgian Tripel [BJCP 26C]', 'Belgian Ale', '1.075', '1.085', '1.008', '1.014', '7.5', '9.5', '20', '40', '4.5', '7', '1', 'A pale, somewhat spicy, dry, strong Trappist ale with a pleasant rounded malt flavor and firm bitterness. Quite aromatic, with spicy, fruity, and light alcohol notes combining with the supportive clean malt character to produce a surprisingly drinkable beverage considering the high alcohol level.', 'http://bjcp.org/stylecenter.php', '15', 'Y', 'bcoe', 'AABC', 0, 0, 0, 0, 'high-strength, pale-color, top-fermented, western-europe, traditional-style, bitter', 'Westmalle Tripel, La Rulles Tripel, St. Bernardus Tripel, Chimay Cinq Cents (White), Watou Tripel, Val-Dieu Triple, Affligem Tripel, Grimbergen Tripel, La Trappe Tripel, Witkap Pater Tripel, Corsendonk Abbey Pale Ale, St. Feuillien Tripel.', NULL),
('07', 'Belgian Golden Strong Ale [BJCP 25C]', 'Belgian Ale', '1.07', '1.095', '1.005', '1.016', '7.5', '10.5', '22', '35', '3', '6', '1', 'A pale, complex, effervescent, strong Belgian-style ale that is highly attenuated and features fruity and hoppy notes in preference to phenolics.', 'http://bjcp.org/stylecenter.php', '15', 'Y', 'bcoe', 'AABC', 0, 0, 0, 0, 'very-high-strength, pale-color, top-fermented, western-europe, traditional-style, bitter', 'Duvel, Russian River Damnation, Hapkin, Lucifer, Brigand, Judas, Delirium Tremens, Dulle Teve, Piraat, Great Divide Hades, Avery Salvation, North Coast Pranqster, Unibroue Eau Benite, AleSmith Horny Devil.', NULL),
('08', 'Belgian Dark Strong Ale [BJCP 26D]', 'Belgian Ale', '1.075', '1.11', '1.01', '1.024', '8', '11', '20', '35', '12', '22', '1', 'A dark, complex, very strong Belgian ale with a delicious blend of malt richness, dark fruit flavors, and spicy elements. Complex, rich, smooth and dangerous.', 'http://bjcp.org/stylecenter.php', '15', 'Y', 'bcoe', 'AABC', 0, 0, 0, 0, 'very-high-strength, amber-color, top-fermented, western-europe, traditional-style, malty', 'Westvleteren 12, Rochefort 10, St. Bernardus Abt 12, Gouden Carolus Grand Cru of the Emperor, Achel Extra Brune, Rochefort 8, Southampton Abbot 12, Chimay Grande Reserve, Lost Abbey Judgment Day.', NULL),
('01', 'Wee Heavy [BJCP 17C]', 'Strong Ales and Lagers', '1.07', '1.013', '1.018', '1.04', '6.5', '10', '17', '35', '14', '25', '1', 'Rich, malty, dextrinous, and usually caramel-sweet, these beers can give an impression that is suggestive of a dessert. Complex secondary malt and alcohol flavors prevent a one-dimensional quality. Strength and maltiness can vary, but should not be cloying or syrupy. ', 'http://bjcp.org/stylecenter.php', '16', 'Y', 'bcoe', 'AABC', 0, 0, 0, 0, 'high-strength, amber-color, top-fermented, british-isles, traditional-style, strong-ale-family, malty', 'Traquair House Ale, Belhaven Wee Heavy, McEwans Scotch Ale, MacAndrews Scotch Ale, Orkney Skull Splitter, Inveralmond Black Friar, Broughton Old Jock, Gordon Highland Scotch Ale, AleSmith Wee Heavy.', NULL),
('02', 'Doppelbock [BJCP 9A]', 'Strong Ales and Lagers', '1.072', '1.112', '1.016', '1.024', '7', '10', '16', '26', '6', '25', '1', 'A strong, rich, and very malty German lager that can have both pale and dark variants. The darker versions have more richly-developed, deeper malt flavors, while the paler versions have slightly more hops and dryness.', 'http://bjcp.org/stylecenter.php', '16', 'Y', 'bcoe', 'AABC', 1, 0, 0, 0, 'high-strength, amber-color, pale-color, bottom-fermented, lagered, central-europe, traditional-style, bock-family, malty', 'Dark Versions - Andechser Doppelbock Dunkel, Ayinger Celebrator, Paulaner Salvator, Spaten Optimator, Troegs Troegenator, Weihenstephaner Korbinian. Pale Versions - Eggenberg Urbock 23, EKU 28, Plank Bavarian Heller Doppelbock.', 'The entrant must specify whether the entry is a pale or a dark variant.'),
('03', 'Eisbock [BJCP 9B]', 'Strong Ales and Lagers', '1.078', '1.12', '1.02', '1.035', '9', '14', '25', '35', '18', '30', '1', 'A strong, full-bodied, rich, and malty dark German lager often with a viscous quality and strong flavors. Even though flavors are concentrated, the alcohol should be smooth and warming, not burning. ', 'http://bjcp.org/stylecenter.php', '16', 'Y', 'bcoe', 'AABC', 0, 0, 0, 0, 'very-high-strength, amber-color, bottom-fermented, lagered, central-europe, traditional-style, bock-family, malty', 'Kulmbacher Eisbock, Eggenberg Urbock Dunkel Eisbock, Niagara Eisbock, Southampton Double Ice Bock, Capital Eisphyre.', NULL),
('04', 'British Strong Ale [BJCP 17A]', 'Strong Ales and Lagers', '1.055', '1.08', '1.015', '1.022', '5.5', '8', '30', '60', '8', '22', '1', 'An ale of respectable alcoholic strength, traditionally bottled-conditioned and cellared. Can have a wide range of interpretations, but most will have varying degrees of malty richness, late hops and bitterness, fruity esters, and alcohol warmth. Judges should allow for a significant range in character, as long as the beer is within the alcohol strength range and has an interesting &quot;English&quot; character, it likely fits the style. The malt and adjunct flavors and intensity can vary widely, but any combination should result in an agreeable palate experience.', 'http://bjcp.org/stylecenter.php', '16', 'Y', 'bcoe', 'AABC', 0, 0, 0, 0, 'high-strength, amber-color, top-fermented, british-isles, traditional-style, strong-ale-family, malty', 'Fullers 1845, Youngs Special London Ale, Harveys Elizabethan Ale, J.W. Lees Manchester Star, Sarah Hughes Dark Ruby Mild, Samuel Smiths Winter Welcome, Fullers ESB, Adnams Broadside, Youngs Winter Warmer.', NULL),
('05', 'Old Ale [BJCP 17B]', 'Strong Ales and Lagers', '1.055', '1.088', '1.015', '1.022', '5.5', '9', '30', '60', '10', '22', '1', 'An ale of moderate to fairly significant alcoholic strength, bigger than standard beers, though usually not as strong or rich as barleywine. Often tilted towards a maltier balance. &quot;It should be a warming beer of the type that is best drunk in half pints by a warm fire on a cold winters night&quot; - Michael Jackson. ', 'http://bjcp.org/stylecenter.php', '16', 'Y', 'bcoe', 'AABC', 0, 0, 0, 0, 'high-strength, amber-color, top-fermented, british-isles, traditional-style, strong-ale-family, malty, aged', 'Gales Prize Old Ale, Burton Bridge Olde Expensive, Marston Owd Roger, Greene King Strong Suffolk Ale, Theakston Old Peculier.', NULL),
('06', 'American Strong Ale [BJCP 22B]', 'Strong Ales and Lagers', '1.062', '1.09', '1.014', '1.024', '6.3', '10', '50', '100', '7', '19', '1', 'A strong, full-flavored American ale that challenges and rewards the palate with full malty and hoppy flavors and substantial bitterness. The flavors are bold but complementary, and are stronger and richer than average-strength pale and amber American ales.', 'http://bjcp.org/stylecenter.php', '16', 'Y', 'bcoe', 'AABC', 0, 0, 0, 0, 'high-strength, amber-color, top-fermented, north-america, craft-style, strong-ale-family, bitter, hoppy', 'Stone Arrogant Bastard, Great Lakes Nosferatu, Bear Republic Red Rocket Ale, Terrapin Big Hoppy Monster, Lagunitas Censored, Port Brewing Shark Attack Double Red.', NULL),
('07', 'English Barleywine [BJCP 17D]', 'Strong Ales and Lagers', '1.08', '1.12', '1.018', '1.03', '8', '12', '35', '70', '8', '22', '1', 'A showcase of malty richness and complex, intense flavors. Chewy and rich in body, with warming alcohol and a pleasant fruity or hoppy interest. When aged, it can take on port-like flavors. A wintertime sipper. ', 'http://bjcp.org/stylecenter.php', '16', 'Y', 'bcoe', 'AABC', 0, 0, 0, 0, 'very-high-strength, amber-color, top-fermented, british-isles, traditional-style, strong-ale-family, malty', 'Adnams Tally Ho, Burton Bridge Thomas Sykes Old Ale, J.W. Lees Vintage Harvest Ale, Fullers Vintage Ale, Robinsons Old Tom, Fullers Golden Pride, Whitbread Gold Label.', NULL),
('08', 'American Barleywine [BJCP 22C]', 'Strong Ales and Lagers', '1.08', '1.12', '1.016', '1.03', '8', '12', '50', '100', '10', '19', '1', 'A well-hopped American interpretation of the richest and strongest of the English ales. The hop character should be evident throughout, but does not have to be unbalanced. The alcohol strength and hop bitterness often combine to leave a very long finish.', 'http://bjcp.org/stylecenter.php', '16', 'Y', 'bcoe', 'AABC', 0, 0, 0, 0, 'very-high-strength, amber-color, top-fermented, north-america, craft-style, strong-ale-family, bitter, hoppy', 'Sierra Nevada Bigfoot, Great Divide Old Ruffian, Victory Old Horizontal, Rogue Old Crustacean, Avery Hog Heaven Barleywine, Bells Third Coast Old Ale, Anchor Old Foghorn, Three Floyds Behemoth, Stone Old Guardian, Bridgeport Old Knucklehead, Hair of the Dog Doggie Claws, Lagunitas Olde GnarleyWine, Smuttynose Barleywine, Flying Dog Horn Dog.', NULL),
('01', 'Fruit Beer [BJCP 29A]', 'Fruit/Spice/Herb/Vegetable Beer', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '1', 'A harmonious marriage of fruit and beer, but still recognizable as a beer. The fruit character should be evident but in balance with the beer, not so forward as to suggest an artificial product.', 'http://bjcp.org/stylecenter.php', '17', 'Y', 'bcoe', 'AABC', 1, 0, 0, 0, 'specialty-beer, fruit', 'Bells Cherry Stout, Dogfish Head Aprihop, Great Divide Wild Raspberry Ale, Ebulum Elderberry Black Ale.', 'The entrant must specify a base style; the declared style does not have to be a Classic Style. The entrant must specify the type of fruit used.  Soured fruit beers that aren’t lambics should be entered in the Speciality category as a Belgian Speciality Ale'),
('02', 'Spice Herb Vegetable Beer [BJCP 30A]', 'Fruit/Spice/Herb/Vegetable Beer', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '1', 'A harmonious marriage of SHV and beer, but still recognizable as a beer. The SHV character should be evident but in balance with the beer, not so forward as to suggest an artificial product.', 'http://bjcp.org/stylecenter.php', '17', 'Y', 'bcoe', 'AABC', 1, 0, 0, 0, 'specialty-beer, spice', 'Alesmith Speedway Stout, Founders Breakfast Stout, Traquair Jacobite Ale, Rogue Chipotle Ale, Youngs Double Chocolate Stout, Bells Java Stout, Elysian Avatar IPA.', 'The entrant must specify a base style; the declared style does not have to be a Classic Style. The entrant must specify the type of spices, herbs, or vegetables used; individual ingredients do not need to be specified if a well-known blend of spices is used (e.g., apple pie spice, chilli).'),
('03', 'Autumn Seasonal Beer [BJCP 30B]', 'Fruit/Spice/Herb/Vegetable Beer', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '1', 'An amber to copper, spiced beer that often has a moderately rich body and slightly warming finish suggesting a good accompaniment for the cool fall season, and often evocative of Thanksgiving traditions.', 'http://bjcp.org/stylecenter.php', '17', 'Y', 'bcoe', 'AABC', 1, 0, 0, 0, 'specialty-beer, spice', 'Dogfish Head Punkin Ale, Southampton Pumpkin Ale.', 'The entrant must specify a base style; the declared style does not have to be a Classic Style. The entrant must specify the type of spices, herbs, or vegetables used; individual ingredients do not need to be specified if a well-known blend of spices is used (e.g., pumpkin pie spice). The beer must contain spices, and may contain vegetables and/or sugars.'),
('04', 'Winter Seasonal Beer [BJCP 30C]', 'Fruit/Spice/Herb/Vegetable Beer', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '1', 'A stronger, darker, spiced beer that often has a rich body and warming finish suggesting a good accompaniment for the cold winter season.', 'http://bjcp.org/stylecenter.php', '17', 'Y', 'bcoe', 'AABC', 1, 0, 0, 0, 'specialty-beer, spice', 'Anchor Our Special Ale, Harpoon Winter Warmer, Weyerbacher Winter Ale, Goose Island Christmas Ale, Great Lakes Christmas Ale, Lakefront Holiday Spice Lager Beer.', 'The entrant must specify a base style; the declared style does not have to be a Classic Style. The entrant must specify the type of spices, sugars, fruits, or additional fermentables used; individual ingredients do not need to be specified if a well-known blend of spices is used (e.g., mulling spice).'),
('01', 'Rauchbier [BJCP 6B]', 'Specialty Beer ', '1.05', '1.057', '1.012', '1.015', '4.8', '6', '20', '30', '12', '22', '1', 'Medium body. Medium to medium-high carbonation. Smooth lager character. Significant astringent, phenolic harshness is inappropriate.', 'http://bjcp.org/stylecenter.php', '18', 'Y', 'bcoe', 'AABC', 0, 0, 0, 0, 'standard-strength, amber-color, bottom-fermented, lagered, central-europe, traditional-style, amber-lager-family, malty, smoke', 'Schlenkerla Rauchbier Marzen, Kaiserdom Rauchbier, Eisenbahn Defumada, Spezial Rauchbier Marzen, Victory Scarlet Fire Rauchbier.', NULL),
('02', 'Classic Style Smoked Beer [BJCP 32A]', 'Specialty Beer ', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '1', 'A smoke-enhanced beer showing good balance between the smoke and beer character, while remaining pleasant to drink. Balance in the use of smoke, hops and malt character is exhibited by the better examples.', 'http://bjcp.org/stylecenter.php', '18', 'Y', 'bcoe', 'AABC', 1, 0, 0, 0, 'specialty-beer, smoke', 'Alaskan Smoked Porter, Spezial Lagerbier, Weissbier and Bockbier, Stone Smoked Porter, Schlenkerla Weizen Rauchbier and Ur-Bock Rauchbier.', 'The entrant must specify a Classic Style base beer. The entrant must specify the type of wood or smoke if a varietal smoke character is noticeable.'),
('03', 'Specialty Smoked Beer [BJCP 32B]', 'Specialty Beer ', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '1', 'A smoke-enhanced beer showing good balance between the smoke, the beer character, and the added ingredients, while remaining pleasant to drink. Balance in the use of smoke, hops and malt character is exhibited by the better examples.', 'http://bjcp.org/stylecenter.php', '18', 'Y', 'bcoe', 'AABC', 1, 0, 0, 0, 'specialty-beer, smoke', NULL, 'The entrant must specify a base beer style; the base beer does not have to be a Classic Style. The entrant must specify the type of wood or smoke if a varietal smoke character is noticeable. The entrant must specify the additional ingredients or processes that make this a specialty smoked beer.'),
('04', 'Wood Aged Beer [BJCP 33A]', 'Specialty Beer ', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '1', 'A harmonious blend of the base beer style with characteristics from aging in contact with wood. The best examples will be smooth, flavorful, well-balanced and well-aged.', 'http://bjcp.org/stylecenter.php', '18', 'Y', 'bcoe', 'AABC', 1, 0, 0, 0, 'specialty-beer, wood', NULL, 'The entrant must specify the type of wood used and the char level (if charred). The entrant must specify the base style; the base style can be either a classic BJCP style (i.e., a named subcategory) or may be a generic type of beer (e.g., porter, brown ale). If an unusual wood has been used, the entrant must supply a brief description of the sensory aspects the wood adds to beer.'),
('05', 'Specialty Wood?Aged Beer [BJCP 33B]', 'Specialty Beer ', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '1', 'A harmonious blend of the base beer style with characteristics from aging in contact with wood (including alcoholic products previously in contact with the wood). The best examples will be smooth, flavorful, well-balanced and well-aged.', 'http://bjcp.org/stylecenter.php', '18', 'Y', 'bcoe', 'AABC', 1, 0, 0, 0, 'specialty-beer, wood', 'The Lost Abbey Angel&quot;s Share Ale, J.W. Lees Harvest Ale in Port, Sherry, Lagavulin Whisky or Calvados Casks, Founders Kentucky Breakfast Stout, Goose Island Bourbon County Stout, many microbreweries have specialty beers served only on premises often directly from the cask.', 'The entrant must specify the additional alcohol character, with information about the barrel if relevant to the finished flavor profile. The entrant must specify the base style; the base style can be either a classic BJCP style (i.e., a named subcategory) or may be a generic type of beer (e.g., porter, brown ale). If an unusual wood or ingredient has been used, the entrant must supply a brief description of the sensory aspects the ingredients adds to the beer.'),
('06', 'Belgian Specialty Ale [AABC]', 'Specialty Beer ', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '1', 'This is a catch-all style for any Belgian-style beer not fitting any other Belgian style. The style can be used for clones of specific beers (e.g., Orval, La Chouffe); to produce a beer fitting a broader style that doesn&quot;t have its own style or to create an artisanal or experimental beer of the brewer&quot;s own choosing (e.g., strong Belgian golden ale with spices, something unique). Creativity is the only limit in brewing but the entrants must identify what is special about their entry', 'http://www.aabc.org.au/docs/AABC2019StyleGuidelines.pdf', '18', 'Y', 'bcoe', 'AABC', 1, 0, 0, 0, 'specialty-beer', 'Orval; De Dolle’s Arabier, Oerbier, Boskeun and Still Nacht; La Chouffe, McChouffe,', 'The judges must understand the brewer’s intent in order to properly judge an entry in this style. THE BREWER MUST SPECIFY EITHER THE BEER BEING CLONED, THE NEW STYLE BEING PRODUCED OR THE SPECIAL INGREDIENTS OR PROCESSES USED. Additional background information on the style and/or beer may be provided to judges to assist in the judging, including style parameters or detailed descriptions of the beer. Beers fitting other Belgian categories should not be entered in this style. '),
('07', 'Alternative Grain Beer [BJCP 31A]', 'Specialty Beer ', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '1', 'A base beer enhanced by the flavor of additional grain.', 'http://bjcp.org/stylecenter.php', '18', 'Y', 'bcoe', 'AABC', 1, 0, 0, 0, 'specialty-beer', 'Green’s Indian Pale Ale, Lakefront New Grist', 'The entrant must specify a base style; the declared style does not have to be a Classic Style. The entrant must specify the type of alternative grain used.'),
('08', 'Alternative Sugar Beer [BJCP 31B]', 'Specialty Beer ', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '1', 'A harmonious marriage of sugar and beer, but still recognizable as a beer. The sugar character should both be evident but in balance with the beer, not so forward as to suggest an artificial product.', 'http://bjcp.org/stylecenter.php', '18', 'Y', 'bcoe', 'AABC', 1, 0, 0, 0, 'specialty-beer', 'Bell’s Hopslam, Fullers Honey Dew, Lagunitas Brown Shugga', 'The entrant must specify a base style; the declared style does not have to be a Classic Style. The entrant must specify the type of sugar used.'),
('09', 'Experimental Beer [BJCP 34C]', 'Specialty Beer ', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '1', 'This style is the ultimate in creativity, since it cannot represent a well-known commercial beer (otherwise it would be a clone beer) and cannot fit into any other existing Specialty-Type style (including those within this major category).', 'http://bjcp.org/stylecenter.php', '18', 'Y', 'bcoe', 'AABC', 1, 0, 0, 0, 'specialty-beer', NULL, 'The entrant must specify the special nature of the experimental beer, including the special ingredients or processes that make it not fit elsewhere in the guidelines. The entrant must provide vital statistics for the beer, and either a brief sensory description or a list of ingredients used in making the beer. Without this information, judges will have no basis for comparison.'),
('01', 'Dry Mead [BJCP M1A]', 'Mead ', NULL, NULL, NULL, NULL, NULL, NULL, 'N/A', 'N/A', 'N/A', 'N/A', '3', 'Similar in balance, body, finish and flavor intensity to a dry white wine, with a pleasant mixture of subtle honey character, soft fruity esters, and clean alcohol. Complexity, harmony, and balance of sensory elements are most desirable, with no inconsistencies in color, aroma, flavor or aftertaste. The proper balance of sweetness, acidity, alcohol, and honey character is the essential final measure of any mead.', 'http://bjcp.org/stylecenter.php', '19', 'Y', 'bcoe', 'AABC', 0, 1, 1, 0, NULL, 'White Winter Dry Mead, Sky River Dry Mead, Intermiel Bouquet Printanier.', 'Entry Instructions: Entrants must specify carbonation level and strength. Sweetness is assumed to be DRY in this category. Entrants may specify honey varieties.'),
('02', 'Semi Sweet Mead [BJCP M1B]', 'Mead ', NULL, NULL, NULL, NULL, NULL, NULL, 'N/A', 'N/A', 'N/A', 'N/A', '3', 'Similar in balance, body, finish and flavor intensity to a semisweet (or medium-dry) white wine, with a pleasant mixture of honey character, light sweetness, soft fruity esters, and clean alcohol. Complexity, harmony, and balance of sensory elements are most desirable, with no inconsistencies in color, aroma, flavor or aftertaste. The proper balance of sweetness, acidity, alcohol, and honey character is the essential final measure of any mead.', 'http://bjcp.org/stylecenter.php', '19', 'Y', 'bcoe', 'AABC', 0, 1, 1, 0, NULL, 'Lurgashall English Mead, Redstone Traditional Mountain Honey Wine, Sky River Semi-Sweet Mead, Intermiel Verge dOr and Melilot.', 'Entrants must specify carbonation level and strength. Sweetness is assumed to be SEMI-SWEET in this category. Entrants MAY specify honey varieties.'),
('03', 'Sweet Mead [BJCP M1C]', 'Mead ', NULL, NULL, NULL, NULL, NULL, NULL, 'N/A', 'N/A', 'N/A', 'N/A', '3', 'Similar in balance, body, finish and flavor intensity to a well-made dessert wine (such as Sauternes), with a pleasant mixture of honey character, residual sweetness, soft fruity esters, and clean alcohol. Complexity, harmony, and balance of sensory elements are most desirable, with no inconsistencies in color, aroma, flavor or aftertaste. The proper balance of sweetness, acidity, alcohol, and honey character is the essential final measure of any mead.', 'http://bjcp.org/stylecenter.php', '19', 'Y', 'bcoe', 'AABC', 0, 1, 1, 0, NULL, 'Moonlight Sensual, Lurgashall Christmas Mead, Chaucers Mead, Rabbits Foot Sweet Wildflower Honey Mead, Intermiel Benoite.', 'Entrants MUST specify carbonation level and strength. Sweetness is assumed to be SWEET in this category. Entrants MAY specify honey varieties.'),
('04', 'Fruit Mead  [BJCP M2]', 'Mead ', NULL, NULL, NULL, NULL, NULL, NULL, 'N/A', 'N/A', 'N/A', 'N/A', '3', 'In well-made examples of the style, the fruit is both distinctive and well-incorporated into the honey-sweet-acid-tannin-alcohol balance of the mead. Different types of fruit can result in widely different characteristics; allow for a variation in the final product.', 'http://bjcp.org/stylecenter.php', '19', 'Y', 'bcoe', 'AABC', 1, 1, 1, 1, NULL, 'Moonlight Blissful, Wild, Caress, and Mischief, White Winter Blueberry, Raspberry and Strawberry Melomels, Celestial Meads Miel Noir, Redstone Black Raspberry Nectar, Bees Brothers Raspberry Mead, Intermiel Honey Wine and Raspberries, Honey Wine and Blueberries, and Honey Wine and Blackcurrants, Mountain Meadows Cranberry Mead.', 'Entrants must specify carbonation level, strength, and sweetness. Entrants may specify honey varieties. Entrants must specify the varieties of fruit used. A mead made with both berries and non-berry fruit (including apples and grapes) should be entered as a Melomel. A berry mead that is spiced should be entered as a Fruit and Spice Mead. A berry mead containing other ingredients should be entered as an Experimental Mead.'),
('05', 'Fruit  Spice Mead [BJCP M3A]', 'Mead ', NULL, NULL, NULL, NULL, NULL, NULL, 'N/A', 'N/A', 'N/A', 'N/A', '3', 'In well-made examples of the style, the fruits and spices are both distinctive and well-incorporated into the honey-sweet-acid-tannin-alcohol balance of the mead. Different types of fruits and spices can result in widely different characteristics; allow for significant variation in the final product.', 'http://bjcp.org/stylecenter.php', '19', 'Y', 'bcoe', 'AABC', 1, 1, 1, 1, NULL, 'Moonlight Kurts Apple Pie, Mojo, Flame, Fling, and Deviant, Celestial Meads Scheherazade, Rabbits Foot Private Reserve Pear Mead, Intermiel Rosee.', 'Entrants must specify carbonation level, strength, and sweetness. Entrants may specify honey varieties. Entrants must specify the types of spices used, (although well-known spice blends may be referred to by common name, such as apple pie spices). Entrants must specify the types of fruits used. If only combinations of spices are used, enter as a Spice, Herb, or Vegetable Mead. If only combinations of fruits are used, enter as a Melomel. If other types of ingredients are used, enter as an Experimental Mead.'),
('06', 'Braggot [BJCP M4A]', 'Mead ', NULL, NULL, NULL, NULL, NULL, NULL, 'N/A', 'N/A', 'N/A', 'N/A', '3', 'A harmonious blend of mead and beer, with the distinctive characteristics of both. A wide range of results are possible, depending on the base style of beer, variety of honey and overall sweetness and strength. Beer flavors tend to somewhat mask typical honey flavors found in other meads. and honey, although the specific balance is open to creative interpretation by brewers.', 'http://bjcp.org/stylecenter.php', '19', 'Y', 'bcoe', 'AABC', 0, 1, 1, 1, NULL, 'Rabbits Foot Diabhal and Biere de Miele, Magic Hat Braggot, Brother Adams Braggot Barleywine Ale, White Winter Traditional Brackett.', 'Entrants MUST specify carbonation level, strength, and sweetness. Entrants MAY specify honey varieties. Entrants MAY specify the base style or beer or types of malt used. Products with a relatively low proportion of honey should be entered in the Spiced Beer category as a Honey Beer.'),
('07', 'Other Mead [BJCP M3B, M4B, M4C]', 'Mead ', NULL, NULL, NULL, NULL, NULL, NULL, 'N/A', 'N/A', 'N/A', 'N/A', '3', 'This mead should exhibit the character of all of the ingredients in varying degrees, and should show a good blending or balance between the various flavor elements. Whatever ingredients are included, the result should be identifiable as a honey-based fermented beverage.', 'http://bjcp.org/stylecenter.php', '19', 'Y', 'bcoe', 'AABC', 1, 1, 1, 1, NULL, 'Moonlight Utopian, Hanssens/Lurgashall Mead the Gueuze, White Winter Cherry Bracket, Mountain Meadows Tricksters Treat Agave Mead.', 'Entrants MUST specify carbonation level, strength, and sweetness. Entrants MAY specify honey varieties. Entrants MUST specify the special nature of the mead, whether it is a combination of existing styles, an experimental mead, or some other creation. Any special ingredients that impart an identifiable character MAY be declared.'),
('01', 'New World Cider [BJCP C1A]', 'Cider', '1.045', '1.065', '0.995', '1.02', '5', '8', 'N/A', 'N/A', 'N/A', 'N/A', '2', 'A refreshing drink of some substance - not bland or watery. Sweet ciders must not be cloying. Dry ciders must not be too austere. ', 'http://bjcp.org/stylecenter.php', '20', 'Y', 'bcoe', 'AABC', 0, 0, 1, 1, NULL, '[US] Uncle Johns Fruit House Winery Apple Hard Cider, Tandem Ciders Pretty Penny (MI), Bellwether Spyglass (NY), West County Pippin (MA), White Winter Hard Apple Cider (WI), Wandering Aengus Ciderworks Bloom (OR), &Aelig;ppeltreow Appely Brut and Doux (WI).', NULL),
('02', 'English Cider [BJCP C1B]', 'Cider', '1.05', '1.075', '0.995', '1.015', '6', '9', 'N/A', 'N/A', 'N/A', 'N/A', '2', 'Generally dry, full-bodied, austere. Complex flavor profile, long finish.', 'http://bjcp.org/stylecenter.php', '20', 'Y', 'bcoe', 'AABC', 0, 0, 1, 1, NULL, '[US] Westcott Bay Traditional Very Dry, Dry and Medium Sweet (WA), Farnum Hill Extra-Dry, Dry, and Farmhouse (NH), Wandering Aengus Dry Cider (OR), Montana CiderWorks North Fork (MT), Bellwether Heritage (NY). [UK] Olivers Traditional Dry, Hogans Dry and Medium Dry, Henneys Dry and Vintage Still, Burrow Hill Medium, Aspall English Imperial.', NULL),
('03', 'French Cider [BJCP C1C]', 'Cider', '1.05', '1.065', '1.01', '1.02', '3', '6', 'N/A', 'N/A', 'N/A', 'N/A', '2', 'Medium to sweet, full-bodied, rich.', 'http://bjcp.org/stylecenter.php', '20', 'Y', 'bcoe', 'AABC', 0, 0, 1, 1, NULL, '[US] West County Reine de Pomme (MA), [France] Eric Bordelet (various), Etienne Dupont, Etienne Dupont Organic, Bellot.', NULL),
('04', 'Perry (New World, Traditional) [BJCP C1D]', 'Cider', '1.05', '1.06', '1', '1.02', '5', '7', 'N/A', 'N/A', 'N/A', 'N/A', '2', 'Mild. Medium to medium-sweet. Still to lightly sparkling. Only very slight acetification is acceptable. Mousiness, ropy/oily characters are serious faults.', 'http://bjcp.org/stylecenter.php', '20', 'Y', 'bcoe', 'AABC', 0, 0, 1, 1, NULL, '[US] White Winter Hard Pear Cider (WI), Uncle Johns Fruit House Winery Perry (MI).', NULL),
('05', 'Other Cider/Perry [BJCP C2]', 'Cider', '1.045', '1.1', '0.995', '1.02', '5', '12', 'N/A', 'N/A', 'N/A', 'N/A', '2', 'This is an open-ended category for cider or perry with other ingredients such that it does not fit any of the other BJCP categories.', 'http://bjcp.org/stylecenter.php', '20', 'Y', 'bcoe', 'AABC', 1, 0, 1, 1, NULL, NULL, 'Entrants MUST specify all ingredients. Entrants MUST specify carbonation level (levels). Entrants MUST specify sweetness (categories).');
";
mysqli_select_db($connection,$database);
mysqli_real_escape_string($connection,$updateSQL);
if (!$aabc_styles_present) $result = mysqli_query($connection,$updateSQL);

$output .= "<li>Added Australian Amateur Brewing Championship (AABC) styles.</li>";


/**
 * ----------------------------------------------------------------------------------------------------
 * Update BA Styles for 2019 and 2020
 * ----------------------------------------------------------------------------------------------------
 */

$updateSQL = "
INSERT INTO `$styles_db_table` (`brewStyleNum`, `brewStyle`, `brewStyleCategory`, `brewStyleOG`, `brewStyleOGMax`, `brewStyleFG`, `brewStyleFGMax`, `brewStyleABV`, `brewStyleABVMax`, `brewStyleIBU`, `brewStyleIBUMax`, `brewStyleSRM`, `brewStyleSRMMax`, `brewStyleType`, `brewStyleInfo`, `brewStyleLink`, `brewStyleGroup`, `brewStyleActive`, `brewStyleOwn`, `brewStyleVersion`, `brewStyleReqSpec`, `brewStyleStrength`, `brewStyleCarb`, `brewStyleSweet`, `brewStyleTags`, `brewStyleComEx`, `brewStyleEntry`) VALUES
(NULL, 'Experimental India Pale Ale', 'Hybrid/Mixed Lagers or Ales', '1.06', '1.1', '0.994', '1.02', '6.3', '10.6', '30', '100', '4', '40', '1', 'Beers in this category recognize the cutting edge of American IPA brewing. Experimental India Pale Ales are either 1) any of White, Red, Brown, Brut or many other IPA or Imperial IPA types or combinations thereof currently in production, and fruited or spiced versions of these, or 2) fruited or spiced versions of classic American, Juicy Hazy, and Imperial IPA categories. They range widely in color, hop and malt intensity and attributes, hop bitterness, balance, alcohol content, body and overall flavor experience. ', 'https://www.brewersassociation.org/edu/brewers-association-beer-style-guidelines/', '11', 'N', 'bcoe', 'BA', 1, 0, 0, 0, '', '', NULL),
(NULL, 'Juicy or Hazy Strong Pale Ale', 'North American Origin Ales', '1.05', '1.065', '1.008', '1.016', '5.6', '7', '40', '50', '4', '9', '1', 'Grist may include oats, wheat or other adjuncts to promote haziness. The term \"juicy\" is frequently used to describe taste and aroma attributes often present in these beers which result from late, often very large, additions of hops. A juicy character is not required, however. Other hopderived attributes such as citrus, pine, spice, floral or others may be present with or without the presence of juicy attributes.', 'https://www.brewersassociation.org/edu/brewers-association-beer-style-guidelines/', '03', 'N', 'bcoe', 'BA', 0, 0, 0, 0, '', '', NULL),
(NULL, 'Contemporary Belgian-Style Gueuze Lambic', 'Belgian And French Origin Ales', '1.044', '1.056', '1', '1.01', '5', '8.9', '11', '23', '6', '40', '', 'Gueuze Lambics, whose origin is the Brussels area of Belgium, are often simply called Gueuze Lambic. Versions of this beer style made outside of the Brussels area are said to be \"BelgianStyle Gueuze Lambics.\" The Belgian-style versions are made to resemble many of the beers of true origin. While Traditional Gueuze Lambics are dry, Contemporary Gueuze Lambics may have a degree of sweetness contributed by sugars or other sweeteners. Traditionally, Gueuze is brewed with unmalted wheat, malted barley, and stale, aged hops. Whereas Contemporary Gueuze Lambics may incorporate specialty malts that influence the hue, flavor and aroma of the finished beer.', 'https://www.brewersassociation.org/edu/brewers-association-beer-style-guidelines/', '05', 'N', 'bcoe', 'BA', 0, 0, 0, 0, '', '', NULL),
(NULL, 'Franconian-Style Rotbier', 'European Origin Lagers', '1.046', '1.056', '1.008', '1.01', '4.8', '5.6', '20', '28', '13', '23', '1', '', 'https://www.brewersassociation.org/edu/brewers-association-beer-style-guidelines/', '07', 'N', 'bcoe', 'BA', 0, 0, 0, 0, '', '', NULL),
(NULL, 'American-Style India Pale Lager', 'North American Origin Lagers', '1.05', '1.065', '1.008', '1.016', '5.6', '7', '30', '70', '3', '6', '1', 'This style of beer should exhibit the fresh character of hops.', 'https://www.brewersassociation.org/edu/brewers-association-beer-style-guidelines/', '08', 'N', 'bcoe', 'BA', 0, 0, 0, 0, '', '', NULL),
(NULL, 'Contemporary American-Style Lager', 'North American Origin Lagers', '1.04', '1.048', '1.006', '1.012', '4.1', '5.1', '5', '19', '2', '4', '1', 'Corn, rice, or other grain or sugar adjuncts are often used, but all-malt formulations are also made. Contemporary American Lagers typically exhibit increased hop aroma and flavor compared to traditional versions, are clean and crisp, and aggressively carbonated.', 'https://www.brewersassociation.org/edu/brewers-association-beer-style-guidelines/', '08', 'N', 'bcoe', 'BA', 0, 0, 0, 0, '', '', NULL),
(NULL, 'Contemporary American-Style Light Lager', 'North American Origin Lagers', '1.024', '1.04', '0.992', '1.008', '3.5', '4.4', '4', '15', '1.5', '12', '', 'Corn, rice or other grain or sugar adjuncts may be used but all-malt formulations are also made. These beers are high in carbonation. Hop attributes, though subtle, are more evident than in traditional American-Style Light Lager. Calories should not exceed 125 per 12-ounce serving. Low carb beers should have a maximum carbohydrate level of 3.0 gm per 12 oz. (355 ml).', 'https://www.brewersassociation.org/edu/brewers-association-beer-style-guidelines/', '08', 'N', 'bcoe', 'BA', 0, 0, 0, 0, '', '', NULL);
";
mysqli_real_escape_string($connection,$updateSQL);

if (!check_new_style("08","","Contemporary American-Style Lager","ignore_style_num")) {
	
	$result = mysqli_query($connection,$updateSQL) or die (mysqli_error($connection));

	$query_ba_missing = sprintf("SELECT id,brewStyleNum FROM %s WHERE brewStyleNum IS NULL",$prefix."styles");
	$ba_missing = mysqli_query($connection,$query_ba_missing) or die (mysqli_error($connection));
	$row_ba_missing = mysqli_fetch_assoc($ba_missing);

	do {
		// Get last style BA number from DB
		// BA style numbers are not official - only generated and kept in the DB for ID purposes in this installation
		$query_ba_last_num = sprintf("SELECT brewStyleNum FROM %s WHERE brewStyleVersion='BA' ORDER BY brewStyleNum DESC LIMIT 1",$prefix."styles");
		$ba_last_num = mysqli_query($connection,$query_ba_last_num) or die (mysqli_error($connection));
		$row_ba_last_num = mysqli_fetch_assoc($ba_last_num);

		$ba_num_add_one = $row_ba_last_num['brewStyleNum'] + 1;

		$updateSQL = sprintf("UPDATE %s SET brewStyleNum='%s' WHERE id='%s'",$prefix."styles",$ba_num_add_one,$row_ba_missing['id']);
		mysqli_real_escape_string($connection,$updateSQL);
		$result = mysqli_query($connection,$updateSQL) or die (mysqli_error($connection));

	} while ($row_ba_missing = mysqli_fetch_assoc($ba_missing));
}

$output .= "<li>Updated Brewers Association (BA) styles.</li>";

/**
 * ----------------------------------------------------------------------------------------------------
 * Update all custom style brewStyleGroup columns to 35 or above (if not already)
 * ----------------------------------------------------------------------------------------------------
 */

// First, search DB for custom styles with brewStyleGroup column values under 35

$query_cust_st = sprintf("SELECT id,brewStyleGroup FROM %s WHERE brewStyleOwn='custom' AND brewStyleGroup < 35 ORDER BY brewStyleGroup ASC",$prefix."styles");
$cust_st = mysqli_query($connection,$query_cust_st) or die (mysqli_error($connection));
$row_cust_st = mysqli_fetch_assoc($cust_st);
$totalRows_cust_st = mysqli_num_rows($cust_st);

// Then, loop through the results to a) change the number in the styles table to the next available if over 35
// and b) change any style numbers in the entries table to the new style number

if ($totalRows_cust_st > 0) {

	// Get the last custom style number if it's 35 or over
	$query_st_num = sprintf("SELECT brewStyleGroup FROM %s WHERE brewStyleOwn='custom' AND brewStyleGroup >= 35 ORDER BY brewStyleGroup DESC LIMIT 1",$prefix."styles");
	$st_num = mysqli_query($connection,$query_st_num) or die (mysqli_error($connection));
	$row_st_num = mysqli_fetch_assoc($st_num);
	$totalRows_st_num = mysqli_num_rows($st_num);

	if ($totalRows_st_num > 0) $new_style_number = $row_st_num['brewStyleGroup'];
	else $new_style_number = 35;

	do {

		$updateSQL = sprintf("UPDATE %s SET brewStyleGroup='%s' WHERE id='%s'",$prefix."styles",$new_style_number,$row_cust_st['id']);
		mysqli_real_escape_string($connection,$updateSQL);
		$result = mysqli_query($connection,$updateSQL) or die (mysqli_error($connection));

		$new_style_number++;

	} while ($row_cust_st = mysqli_fetch_assoc($cust_st));

}
	
/*
 * ----------------------------------------------------------------------------------------------------
 * Update all custom style types ids to greater than 15 (if not already)
 * This reserves 1-15 for system use.
 * ----------------------------------------------------------------------------------------------------
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

// Blow away the current state of the table
$updateSQL = sprintf("TRUNCATE %s",$prefix."style_types");
mysqli_real_escape_string($connection,$updateSQL);
$result = mysqli_query($connection,$updateSQL) or die (mysqli_error($connection));

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

// Add the new style types by looping through the array
foreach ($new_style_types as $key => $value) {

    $updateSQL = sprintf("INSERT INTO `%s` (`id`, `styleTypeName`, `styleTypeOwn`, `styleTypeBOS`, `styleTypeBOSMethod`) VALUES (%s, '%s', 'bcoe', 'N', 1);", $prefix."style_types",$value, $key);
    mysqli_real_escape_string($connection,$updateSQL);
    $result = mysqli_query($connection,$updateSQL) or die (mysqli_error($connection));

}

// Start mySQL auto increment at 16
$updateSQL = sprintf("ALTER TABLE %s AUTO_INCREMENT = 16;", $prefix."style_types");
mysqli_real_escape_string($connection,$updateSQL);
$result = mysqli_query($connection,$updateSQL) or die (mysqli_error($connection));

// Finally, add the remaining custom styles to the table.
// If one matches a core style type, update the styles table 
// with the new relational id.
// If it does not match, add it to the table, query the table
// for the new id and update the corresponding relational id 
// in the styles table.

do {

    // Check against new style types array that was just added
    // If the key exists, update the styles table with the new id
    if (array_key_exists($row_current_st['styleTypeName'], $all_style_types)) {

        // Only worry about any style types that were custom in the "old state"
        if ($row_current_st['id'] > 4) {
            $updateSQL = sprintf("UPDATE %s SET brewStyleType='%s' WHERE brewStyleType='%s';",$prefix."styles",$all_style_types[$row_current_st['styleTypeName']],$row_current_st['id']);
            mysqli_real_escape_string($connection,$updateSQL);
            $result = mysqli_query($connection,$updateSQL) or die (mysqli_error($connection));
        }

        // Apply current styleTypeBOS and styleTypeBOSMethod values for matching styles
        $updateSQL = sprintf("UPDATE %s SET styleTypeBOS='%s',styleTypeBOSMethod='%s' WHERE styleTypeName='%s';",$prefix."style_types",$row_current_st['styleTypeBOS'],$row_current_st['styleTypeBOSMethod'],$row_current_st['styleTypeName']);
        mysqli_real_escape_string($connection,$updateSQL);
        $result = mysqli_query($connection,$updateSQL) or die (mysqli_error($connection));
    }


    // If not, add the style type to table
    // Then, query the table for the new id
    // Finally, update the styles table with the new relational id
    else {
        
        $updateSQL = sprintf("INSERT INTO `%s` (`styleTypeName`, `styleTypeOwn`, `styleTypeBOS`, `styleTypeBOSMethod`) VALUES ('%s', 'custom', '%s', '%s');", 
            $prefix."style_types",$row_current_st['styleTypeName'],$row_current_st['styleTypeBOS'],$row_current_st['styleTypeBOSMethod']);
        mysqli_real_escape_string($connection,$updateSQL);
        $result = mysqli_query($connection,$updateSQL) or die (mysqli_error($connection));

        $query_new_st = sprintf("SELECT id FROM %s ORDER BY id DESC LIMIT 1",$prefix."style_types");
        $new_st = mysqli_query($connection,$query_new_st) or die (mysqli_error($connection));
        $row_new_st = mysqli_fetch_assoc($new_st);

        $updateSQL = sprintf("UPDATE %s SET brewStyleType='%s' WHERE brewStyleType='%s';",$prefix."styles",$row_new_st['id'],$row_current_st['id']);
        mysqli_real_escape_string($connection,$updateSQL);
        $result = mysqli_query($connection,$updateSQL) or die (mysqli_error($connection));

    }

} while($row_current_st = mysqli_fetch_assoc($current_st));
	

$output .= "<li>Add new style types: Wine, Rice Wine, Spirits, Kombucha, and Pulque.</li>";

/**
 * ----------------------------------------------------------------------------------------------------
 * Make sure all judging numbers are converted to lower case.
 * Report from GitHub: https://github.com/geoffhumphrey/brewcompetitiononlineentry/issues/1145
 * Makes sure all judging numbers that employ alpha characters can be matched up with 
 * corresponding uploaded scoresheets.
 * ----------------------------------------------------------------------------------------------------
 */

$updateSQL = sprintf("UPDATE %s SET brewJudgingNumber = LOWER(brewJudgingNumber)", $prefix."brewing");
mysqli_real_escape_string($connection,$updateSQL);
$result = mysqli_query($connection,$updateSQL) or die (mysqli_error($connection));

$output .= "<li>Converted all alpha-numeric judging numbers to lower case.</li>";

/**
 * ----------------------------------------------- 2.2.0 ---------------------------------------------
 * Provide options for judging session type and end date. Rename current unused column judgingTime.
 * Helpful for comps that want to hold virtual or distributed judging sessions over a period of days.
 * ---------------------------------------------------------------------------------------------------
 */

if (check_update("judgingTime", $prefix."judging_locations")) {
	$updateSQL = sprintf("ALTER TABLE `%s` CHANGE `judgingTime` `judgingDateEnd` VARCHAR(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL;", $prefix."judging_locations");
	mysqli_real_escape_string($connection,$updateSQL);
	$result = mysqli_query($connection,$updateSQL) or die (mysqli_error($connection));

	$updateSQL = sprintf("UPDATE `%s` SET judgingDateEnd=NULL;", $prefix."judging_locations");
	mysqli_real_escape_string($connection,$updateSQL);
	$result = mysqli_query($connection,$updateSQL) or die (mysqli_error($connection));
}

if (!check_update("judgingLocType", $prefix."judging_locations")) {
	$updateSQL = sprintf("ALTER TABLE `%s` ADD `judgingLocType` TINYINT(2) NULL DEFAULT NULL AFTER `id`;", $prefix."judging_locations");
	mysqli_real_escape_string($connection,$updateSQL);
	$result = mysqli_query($connection,$updateSQL) or die (mysqli_error($connection));

	$updateSQL = sprintf("UPDATE `%s` SET judgingLocType='0';", $prefix."judging_locations");
	mysqli_real_escape_string($connection,$updateSQL);
	$result = mysqli_query($connection,$updateSQL) or die (mysqli_error($connection));
}

$output .= "<li>Add judging session type and end date columns.</li>";

/**
 * ---------------------------------------------------------------------------------------------------
 * Provide new columns to enable the use of Tables Planning Mode.
 * Helpful for admins who wish to plan tables and assignments prior to entry sorting.
 * ---------------------------------------------------------------------------------------------------
 */

if (!check_update("assignPlanning", $prefix."judging_assignments")) {
	$sql = sprintf("ALTER TABLE `%s` ADD `assignPlanning` TINYINT(1) NULL;",$prefix."judging_assignments");
	mysqli_select_db($connection,$database);
	mysqli_real_escape_string($connection,$sql);
	$result = mysqli_query($connection,$sql);
}

if (!check_update("flightPlanning", $prefix."judging_flights")) {
	$sql = sprintf("ALTER TABLE `%s` ADD `flightPlanning` TINYINT(1) NULL;",$prefix."judging_flights");
	mysqli_select_db($connection,$database);
	mysqli_real_escape_string($connection,$sql);
	$result = mysqli_query($connection,$sql);
}

if (!check_update("jPrefsTablePlanning", $prefix."judging_preferences")) {
	$sql = sprintf("ALTER TABLE `%s` ADD `jPrefsTablePlanning` TINYINT(1) NULL;",$prefix."judging_preferences");
	mysqli_select_db($connection,$database);
	mysqli_real_escape_string($connection,$sql);
	$result = mysqli_query($connection,$sql);
}

$output .= "<li>Added columns to facilitate Tables Planning Mode.</li>";


if (!check_update("prefsEmailCC", $prefix."preferences")) {
	$updateSQL = sprintf("ALTER TABLE `%s` ADD `prefsEmailCC` TINYINT(1) NULL DEFAULT NULL AFTER `prefsEmailRegConfirm`;",$prefix."preferences");
	mysqli_select_db($connection,$database);
	mysqli_real_escape_string($connection,$updateSQL);
	$result = mysqli_query($connection,$updateSQL);

	$updateSQL = sprintf("UPDATE `%s` SET prefsEmailCC='0';", $prefix."preferences");
	mysqli_real_escape_string($connection,$updateSQL);
	$result = mysqli_query($connection,$updateSQL) or die (mysqli_error($connection));
}

$output .= "<li>Added column to enable or disable carbon copying contact messages.</li>";

/**
 * ---------------------------------------------------------------------------------------------------
 * Add the winner display method for Archives
 * Add display winners on past winners list toggle preference for Archives
 * -- Both allow for display of past winners from archived db tables
 * ---------------------------------------------------------------------------------------------------
 */

if (!check_update("archiveWinnerMethod", $prefix."archive")) {
	$updateSQL = sprintf("ALTER TABLE `%s` ADD `archiveWinnerMethod` tinyint(1) NULL DEFAULT NULL COMMENT 'Method comp uses to choose winners: 0=by table; 1=by category; 2=by sub-category';",$prefix."archive");
	mysqli_select_db($connection,$database);
	mysqli_real_escape_string($connection,$updateSQL);
	$result = mysqli_query($connection,$updateSQL);

	$updateSQL = sprintf("UPDATE `%s` SET archiveWinnerMethod='0';", $prefix."archive");
	mysqli_real_escape_string($connection,$updateSQL);
	$result = mysqli_query($connection,$updateSQL) or die (mysqli_error($connection));
}

if (!check_update("archiveDisplayWinners", $prefix."archive")) {
	$updateSQL = sprintf("ALTER TABLE `%s` ADD `archiveDisplayWinners` char(1) NULL DEFAULT NULL;",$prefix."archive");
	mysqli_select_db($connection,$database);
	mysqli_real_escape_string($connection,$updateSQL);
	$result = mysqli_query($connection,$updateSQL);
	
	$updateSQL = sprintf("UPDATE `%s` SET archiveDisplayWinners='N';", $prefix."archive");
	mysqli_real_escape_string($connection,$updateSQL);
	$result = mysqli_query($connection,$updateSQL) or die (mysqli_error($connection));
}

// Get all of the archive suffix records
// Check if all necessary DB tables are present for each suffix,
// If not, add them.

$query_archive = sprintf("SELECT archiveSuffix FROM %s",$prefix."archive");
$archive = mysqli_query($connection,$query_archive);
$row_archive = mysqli_fetch_assoc($archive);
$totalRows_archive = mysqli_num_rows($archive);

//echo $query_archive;

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

// if ($_SESSION['prefsEval'] == 1) $tables_array[] = $prefix."evaluation";

if ($totalRows_archive > 0) {

	do {

		foreach ($tables_array as $table) {

			$table_archive = $table."_".$row_archive['archiveSuffix'];

			if (!check_setup($table_archive,$database)) {

				$updateSQL = "CREATE TABLE ".$table_archive." LIKE ".$table.";";
				mysqli_real_escape_string($connection,$updateSQL);
				$result = mysqli_query($connection,$updateSQL) or die (mysqli_error($connection));

				$updateSQL = "TRUNCATE TABLE ".$table_archive.";";
				mysqli_real_escape_string($connection,$updateSQL);
				$result = mysqli_query($connection,$updateSQL) or die (mysqli_error($connection));

			}

			if ($table_archive == $brewer_db_table."_".$row_archive['archiveSuffix']) {

				if (!check_update("brewerJudgeMead",$brewer_db_table."_".$row_archive['archiveSuffix'])) {
					$updateSQL = sprintf("ALTER TABLE `%s` ADD `brewerJudgeMead` char(1) NULL DEFAULT NULL;",$brewer_db_table."_".$row_archive['archiveSuffix']);
					mysqli_select_db($connection,$database);
					mysqli_real_escape_string($connection,$updateSQL);
					$result = mysqli_query($connection,$updateSQL);
				}

				if (!check_update("brewerBreweryName",$brewer_db_table."_".$row_archive['archiveSuffix'])) {
					$updateSQL = sprintf("ALTER TABLE `%s` ADD `brewerBreweryName` varchar(255) NULL DEFAULT NULL;",$brewer_db_table."_".$row_archive['archiveSuffix']);
					mysqli_select_db($connection,$database);
					mysqli_real_escape_string($connection,$updateSQL);
					$result = mysqli_query($connection,$updateSQL);
				}

				if (!check_update("brewerProAm",$brewer_db_table."_".$row_archive['archiveSuffix'])) {
					$updateSQL = sprintf("ALTER TABLE `%s` ADD `brewerProAm` tinyint(1) NULL DEFAULT NULL;",$brewer_db_table."_".$row_archive['archiveSuffix']);
					mysqli_select_db($connection,$database);
					mysqli_real_escape_string($connection,$updateSQL);
					$result = mysqli_query($connection,$updateSQL);
				}

				if (!check_update("brewerDiscount",$brewer_db_table."_".$row_archive['archiveSuffix'])) {
					$updateSQL = sprintf("ALTER TABLE `%s` ADD `brewerDiscount` char(1) NULL DEFAULT NULL;",$brewer_db_table."_".$row_archive['archiveSuffix']);
					mysqli_select_db($connection,$database);
					mysqli_real_escape_string($connection,$updateSQL);
					$result = mysqli_query($connection,$updateSQL);
				}

			}

			if ($table_archive == $brewing_db_table."_".$row_archive['archiveSuffix']) {

				if (!check_update("brewJudgingNumber",$brewing_db_table."_".$row_archive['archiveSuffix'])) {
					$updateSQL = sprintf("ALTER TABLE `%s` ADD `brewJudgingNumber` char(1) NULL DEFAULT NULL;",$brewing_db_table."_".$row_archive['archiveSuffix']);
					mysqli_select_db($connection,$database);
					mysqli_real_escape_string($connection,$updateSQL);
					$result = mysqli_query($connection,$updateSQL);
				}

				if (!check_update("brewStaffNotes",$brewing_db_table."_".$row_archive['archiveSuffix'])) {
					$updateSQL = sprintf("ALTER TABLE `%s` ADD `brewStaffNotes` char(1) NULL DEFAULT NULL;",$brewing_db_table."_".$row_archive['archiveSuffix']);
					mysqli_select_db($connection,$database);
					mysqli_real_escape_string($connection,$updateSQL);
					$result = mysqli_query($connection,$updateSQL);
				}

				if (!check_update("brewAdminNotes",$brewing_db_table."_".$row_archive['archiveSuffix'])) {
					$updateSQL = sprintf("ALTER TABLE `%s` ADD `brewAdminNotes` char(1) NULL DEFAULT NULL;",$brewing_db_table."_".$row_archive['archiveSuffix']);
					mysqli_select_db($connection,$database);
					mysqli_real_escape_string($connection,$updateSQL);
					$result = mysqli_query($connection,$updateSQL);
				}

				if (!check_update("brewPossAllergens",$brewing_db_table."_".$row_archive['archiveSuffix'])) {
					$updateSQL = sprintf("ALTER TABLE `%s` ADD `brewPossAllergens` char(1) NULL DEFAULT NULL;",$brewing_db_table."_".$row_archive['archiveSuffix']);
					mysqli_select_db($connection,$database);
					mysqli_real_escape_string($connection,$updateSQL);
					$result = mysqli_query($connection,$updateSQL);
				}

				if (!check_update("brewJudgingNumber",$brewing_db_table."_".$row_archive['archiveSuffix'])) {
					$updateSQL = sprintf("ALTER TABLE `%s` ADD `brewJudging` int(6) NULL DEFAULT NULL;",$brewing_db_table."_".$row_archive['archiveSuffix']);
					mysqli_select_db($connection,$database);
					mysqli_real_escape_string($connection,$updateSQL);
					$result = mysqli_query($connection,$updateSQL);
				}

			}

		}

		if (check_setup($prefix."judging_scores_".$row_archive['archiveSuffix'],$database)) {
			if (get_archive_count($prefix."judging_scores_".$row_archive['archiveSuffix']) > 0) {
        		$updateSQL = sprintf("UPDATE `%s` SET archiveDisplayWinners='Y' WHERE archiveSuffix='%s';", $prefix."archive",$row_archive['archiveSuffix']);
				mysqli_real_escape_string($connection,$updateSQL);
				$result = mysqli_query($connection,$updateSQL) or die (mysqli_error($connection));
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
	$updateSQL = sprintf("ALTER TABLE `%s` ADD `prefsEval` TINYINT(1) NULL DEFAULT NULL;",$prefix."preferences");
	mysqli_select_db($connection,$database);
	mysqli_real_escape_string($connection,$updateSQL);
	$result = mysqli_query($connection,$updateSQL);

	if (EVALUATION) $updateSQL = sprintf("UPDATE `%s` SET prefsEval='1';", $prefix."preferences");
	else $updateSQL = sprintf("UPDATE `%s` SET prefsEval='0';", $prefix."preferences");
	mysqli_real_escape_string($connection,$updateSQL);
	$result = mysqli_query($connection,$updateSQL) or die (mysqli_error($connection));

	if (EVALUATION) $_SESSION['prefsEval'] = 1;
	else $_SESSION['prefsEval'] = 0;
}

$output .= "<li>Added column to enable or disable Electronic Scoresheets functionality.</li>";

/** --- Future Release ---
 * Finally, after all updates have been implemented, 
 * make sure the character set is utf8mb4 and coallation 
 * is utf8mb4_unicode_ci. 
require_once(UPDATE.'char_set_update.php');
 */

/**
 * ----------------------------------------------------------------------------------------------------
 * Change the version number and date
 * ALWAYS the final script
 * ----------------------------------------------------------------------------------------------------
 */

$updateSQL = sprintf("UPDATE %s SET version='%s', version_date='%s', data_check=%s WHERE id=1", $prefix."bcoem_sys", $current_version, $current_version_date_display, "NOW()");
mysqli_real_escape_string($connection,$updateSQL);
$result = mysqli_query($connection,$updateSQL) or die (mysqli_error($connection));

// Force reset of preferences
unset($_SESSION['prefs'.$prefix_session]);

?>