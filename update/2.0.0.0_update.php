<?php

if (!function_exists('check_update')) {
	$redirect = "../../403.php";
	$redirect_go_to = sprintf("Location: %s", $redirect);
	header($redirect_go_to);
	exit();
}
 
// -----------------------------------------------------------
// Alter Tables
// Version 2.0.0.0
// -----------------------------------------------------------

$output .= "<h4>Versions 2.0.0 and 2.0.1</h4>";
$output .= "<ul>";

// -----------------------------------------------------------
// Alter Table: Sponsors
// Adding sponsor enable flag for show/hide sponsor display
// -----------------------------------------------------------
if (!check_update("sponsorEnable", $prefix."sponsors")) {
	$updateSQL = "ALTER TABLE `".$prefix."sponsors` ADD `sponsorEnable` TINYINT(1) NULL;";
	mysqli_select_db($connection,$database);
	mysqli_real_escape_string($connection,$updateSQL);
	$result = mysqli_query($connection,$updateSQL) or die (mysqli_error($connection));
	
	$output .=  "<li>Sponsors table altered successfully.</li>";
}

// -----------------------------------------------------------
// Alter Table: Contest Info
// Adding shipping open and close dates
// Add checkin password for future QR code functionality/portal
// -----------------------------------------------------------

if (!check_update("contestShippingOpen", $prefix."contest_info")) {
	$updateSQL = "ALTER TABLE  `".$prefix."contest_info` CHANGE `contestContactName` `contestShippingOpen` VARCHAR(255) NULL DEFAULT NULL;";
	mysqli_select_db($connection,$database);
	mysqli_real_escape_string($connection,$updateSQL);
	$result = mysqli_query($connection,$updateSQL) or die (mysqli_error($connection));
	
}
if (!check_update("contestShippingDeadline", $prefix."contest_info")) {
	$updateSQL = "ALTER TABLE  `".$prefix."contest_info` CHANGE `contestContactEmail` `contestShippingDeadline` VARCHAR(255) NULL DEFAULT NULL;";
	mysqli_select_db($connection,$database);
	mysqli_real_escape_string($connection,$updateSQL);
	$result = mysqli_query($connection,$updateSQL) or die (mysqli_error($connection));
	
}

if (!check_update("contestCheckInPassword", $prefix."contest_info")) {
	$updateSQL= "ALTER TABLE  `".$prefix."contest_info` ADD `contestCheckInPassword` VARCHAR(255) NULL DEFAULT NULL;";
	mysqli_select_db($connection,$database);
	mysqli_real_escape_string($connection,$updateSQL);
	$result = mysqli_query($connection,$updateSQL) or die (mysqli_error($connection));
	
}

if (!check_update("contestDropoffOpen", $prefix."contest_info")) {
	$updateSQL = "ALTER TABLE  `".$prefix."contest_info` CHANGE `contestCategories` `contestDropoffOpen` VARCHAR(255) NULL DEFAULT NULL;";
	mysqli_select_db($connection,$database);
	mysqli_real_escape_string($connection,$updateSQL);
	$result = mysqli_query($connection,$updateSQL) or die (mysqli_error($connection));
	
}

if (!check_update("contestDropoffDeadline", $prefix."contest_info")) {
	$updateSQL = "ALTER TABLE  `".$prefix."contest_info` CHANGE `contestWinnersComplete` `contestDropoffDeadline` VARCHAR(255) NULL DEFAULT NULL;";
	mysqli_select_db($connection,$database);
	mysqli_real_escape_string($connection,$updateSQL);
	$result = mysqli_query($connection,$updateSQL) or die (mysqli_error($connection));
}

$output .=  "<li>Competition info table altered successfully.</li>";

// -----------------------------------------------------------
// Alter Table: Brewer
// Adding judge experience
// Add judge notes to organizers
// -----------------------------------------------------------

$updateSQL = "ALTER TABLE  `".$prefix."brewer` CHANGE `brewerJudgeAssignedLocation` `brewerJudgeExp` VARCHAR(25) NULL DEFAULT NULL;";
mysqli_select_db($connection,$database);
mysqli_real_escape_string($connection,$updateSQL);
$result = mysqli_query($connection,$updateSQL) or die (mysqli_error($connection));

$updateSQL = "ALTER TABLE  `".$prefix."brewer` CHANGE `brewerStewardAssignedLocation` `brewerJudgeNotes` TEXT NULL DEFAULT NULL;";
mysqli_select_db($connection,$database);
mysqli_real_escape_string($connection,$updateSQL);
$result = mysqli_query($connection,$updateSQL) or die (mysqli_error($connection));

$output .=  "<li>Brewer table updated successfully.</li>";

// -----------------------------------------------------------
// Alter Tables: Archived tables
// -----------------------------------------------------------

$query_archive_current = sprintf("SELECT archiveSuffix FROM %s",$archive_db_table);
$archive_current = mysqli_query($connection,$query_archive_current) or die (mysqli_error($connection));
$row_archive_current = mysqli_fetch_assoc($archive_current);
$totalRows_archive_current = mysqli_num_rows($archive_current);

if ($totalRows_archive_current > 0) {
	
	do { $a_current[] = $row_archive_current['archiveSuffix']; } while ($row_archive_current = mysqli_fetch_assoc($archive_current));
	
	foreach ($a_current as $suffix_current) {
		
		$suffix_current = "_".$suffix_current;
		
		// Update brewer table with changed values
		if (check_setup($prefix."brewer".$suffix_current,$database)) {
			
			$updateSQL = "ALTER TABLE  `".$prefix."brewer".$suffix_current."` CHANGE `brewerJudgeAssignedLocation` `brewerJudgeExp` VARCHAR(25) NULL DEFAULT NULL;";
			mysqli_select_db($connection,$database);
			mysqli_real_escape_string($connection,$updateSQL);
			$result = mysqli_query($connection,$updateSQL); 
			
			$updateSQL = "ALTER TABLE  `".$prefix."brewer".$suffix_current."` CHANGE `brewerStewardAssignedLocation` `brewerJudgeNotes` TEXT NULL DEFAULT NULL;";
			mysqli_select_db($connection,$database);
			mysqli_real_escape_string($connection,$updateSQL);
			$result = mysqli_query($connection,$updateSQL);
			
			
		} // end if (check_setup($prefix."brewer".$suffix_current,$database))
		
	} // end foreach ($a_current as $suffix_current)
	
} // end if ($totalRows_archive_current > 0)

$output .=  "<li>All archive brewer tables updated successfully.</li>";


// Remove countries table
$updateSQL = "DROP TABLE IF EXISTS `".$prefix."countries`";
mysqli_select_db($connection,$database);
mysqli_real_escape_string($connection,$updateSQL);
$result = mysqli_query($connection,$updateSQL) or die (mysqli_error($connection));

$output .=  "<li>Countries table removed from the database.</li>";


// Remove themes table
$updateSQL = "DROP TABLE IF EXISTS `".$prefix."themes`";
mysqli_select_db($connection,$database);
mysqli_real_escape_string($connection,$updateSQL);
$result = mysqli_query($connection,$updateSQL) or die (mysqli_error($connection));

$output .=  "<li>Themes table removed from the database.</li>";


// -----------------------------------------------------------
// Version 2.0.0.0
// Data Updates
// -----------------------------------------------------------

// Enable display of all sponsors. Admins can change if desired.
$updateSQL = sprintf("UPDATE %s SET sponsorEnable = '1';",$sponsors_db_table);
mysqli_select_db($connection,$database);
mysqli_real_escape_string($connection,$updateSQL);
$result = mysqli_query($connection,$updateSQL) or die (mysqli_error($connection));	

$updateSQL = sprintf("UPDATE %s SET contestShippingOpen = contestEntryOpen",$contest_info_db_table);
mysqli_select_db($connection,$database);
mysqli_real_escape_string($connection,$updateSQL);
$result = mysqli_query($connection,$updateSQL) or die (mysqli_error($connection));

$updateSQL = sprintf("UPDATE %s SET contestShippingDeadline = contestEntryDeadline",$contest_info_db_table);
mysqli_select_db($connection,$database);
mysqli_real_escape_string($connection,$updateSQL);
$result = mysqli_query($connection,$updateSQL) or die (mysqli_error($connection));

$updateSQL = sprintf("UPDATE %s SET contestDropoffOpen = contestEntryOpen",$contest_info_db_table);
mysqli_select_db($connection,$database);
mysqli_real_escape_string($connection,$updateSQL);
$result = mysqli_query($connection,$updateSQL) or die (mysqli_error($connection));

$updateSQL = sprintf("UPDATE %s SET contestDropoffDeadline = contestEntryDeadline",$contest_info_db_table);
mysqli_select_db($connection,$database);
mysqli_real_escape_string($connection,$updateSQL);
$result = mysqli_query($connection,$updateSQL) or die (mysqli_error($connection));

$output .=  "<li>Sponsor data updated.</li>";



// ---------------------- Style Updates ---------------------------	

// Change incorrect name and info for BJCP2015 style 28C
$query_wild = sprintf("SELECT brewStyle FROM %s WHERE brewStyle='Soured Fruit Beer'",$styles_db_table);
$wild = mysqli_query($connection,$query_wild) or die (mysqli_error($connection));
$row_wild = mysqli_fetch_assoc($wild);
$totalRows_wild = mysqli_num_rows($wild);

if ($totalRows_wild > 0) {

	$updateSQL = sprintf("UPDATE %s SET brewStyle='Wild Specialty Beer', brewStyleInfo='A sour and/or funky version of a fruit, herb, or spice beer, or a wild beer aged in wood. If wood-aged, the wood should not be the primary or dominant character. Entry Instructions: Entrant must specify the type of fruit, spice, herb, or wood used. Entrant must specify a description of the beer, identifying the yeast/bacteria used and either a base style or the ingredients/specs/target character of the beer. A general description of the special nature of the beer can cover all the required items. Commercial Examples: Cascade Bourbonic Plague, Jester King Atrial Rubicite, New Belgium Eric’s Ale, New Glarus Belgian Red, Russian River Supplication, The Lost Abbey 
Cuvee de Tomme.' WHERE id=187",$styles_db_table);
	mysqli_select_db($connection,$database);
	mysqli_real_escape_string($connection,$updateSQL);
	$result = mysqli_query($connection,$updateSQL) or die (mysqli_error($connection));
	
	$updateSQL = sprintf("UPDATE %s SET brewStyle='Wild Specialty Beer' WHERE brewStyle='Soured Fruit Beer'",$brewing_db_table);
	mysqli_select_db($connection,$database);
	mysqli_real_escape_string($connection,$updateSQL);
	$result = mysqli_query($connection,$updateSQL) or die (mysqli_error($connection));
	
}


$updateSQL = sprintf("UPDATE ".$styles_db_table." SET brewStyleInfo = '%s' WHERE id = '%s'","Variable. This category encompasses a wide range of Belgian ales produced by truly artisanal brewers more concerned with creating unique products than in increasing sales. Entry Instructions: The brewer must specify either the beer being cloned, the new style being produced or the special ingredients or processes used. Commercial Examples: Orval; De Dolle&rsquo;s Arabier, Oerbier, Boskeun and Stille Nacht; La Chouffe, McChouffe, Chouffe Bok and N&rsquo;ice Chouffe; Ellezelloise Hercule Stout and Quintine Amber; Unibroue Ephemere, Maudite, Don de Dieu, etc.; Minty; Zatte Bie; Caracole Amber, Saxo and Nostradamus; Silenrieu Sara and Joseph; Fant&ocirc;me Black Ghost and Speciale No&euml;l; Dupont Moinette, Moinette Brune, and Avec Les Bons Voeux de la Brasserie Dupont; St. Fullien No&euml;l; Gouden Carolus No&euml;l; Affligem N&ouml;el; Guldenburg and Pere No&euml;l; De Ranke XX Bitter and Guldenberg; Poperings Hommelbier; Bush (Scaldis); Moinette Brune; Grottenbier; La Trappe Quadrupel; Weyerbacher QUAD; Bi&egrave;re de Miel; Verboden Vrucht; New Belgium 1554 Black Ale; Cantillon Iris; Russian River Temptation; Lost Abbey Cuvee de Tomme and Devotion, Lindemans Kriek and Framboise, and many more.","59");
mysqli_select_db($connection,$database);
mysqli_real_escape_string($connection,$updateSQL);
$result = mysqli_query($connection,$updateSQL) or die (mysqli_error($connection));

$updateSQL = sprintf("UPDATE ".$styles_db_table." SET brewStyleInfo = '%s' WHERE id = '%s'","Complex, fruity, pleasantly sour/acidic, balanced, pale, wheat-based ale fermented by a variety of Belgian microbiota. A lambic with fruit, not just a fruit beer. Entry Instructions: Entrant must specify the type of fruit(s) used in the making of the lambic. Commercial Examples: Boon Framboise Marriage Parfait, Boon Kriek Mariage Parfait, Boon Oude Kriek, Cantillon Fou&rsquo; Foune (apricot), Cantillon Kriek, Cantillon Lou Pepe Kriek, Cantillon Lou Pepe Framboise, Cantillon Rose de Gambrinus, Cantillon St. Lamvinus (merlot grape), Cantillon Vigneronne (Muscat grape), De Cam Oude Kriek, Drie Fonteinen Kriek, Girardin Kriek, Hanssens Oude Kriek, Oud Beersel Kriek, Mort Subite Kriek.","65");
mysqli_select_db($connection,$database);
mysqli_real_escape_string($connection,$updateSQL);
$result = mysqli_query($connection,$updateSQL) or die (mysqli_error($connection));

$updateSQL = sprintf("UPDATE ".$styles_db_table." SET brewStyleInfo = '%s' WHERE id = '%s'","A harmonious marriage of fruit and beer. The key attributes of the underlying style will be different with the addition of fruit; do not expect the base beer to taste the same as the unadulterated version. Judge the beer based on the pleasantness and balance of the resulting combination. Entry Instructions: Entrant must specify the underlying beer style as well as the type of fruit(s) used. Classic styles do not have to be cited. Commercial Examples: New Glarus Belgian Red and Raspberry Tart, Bell&rsquo;s Cherry Stout, Dogfish Head Aprihop, Great Divide Wild Raspberry Ale, Founders R&uuml;b&aelig;us, Ebulum Elderberry Black Ale, Stiegl Radler, Weyerbacher Raspberry Imperial Stout, Abita Purple Haze, Melbourne Apricot Beer and Strawberry Beer, Saxer Lemon Lager, Magic Hat #9, Grozet Gooseberry and Wheat Ale,  Pyramid Apricot Ale, Dogfish Head Fort.","74");
mysqli_select_db($connection,$database);
mysqli_real_escape_string($connection,$updateSQL);
$result = mysqli_query($connection,$updateSQL) or die (mysqli_error($connection));

$updateSQL = sprintf("UPDATE ".$styles_db_table." SET brewStyleInfo = '%s' WHERE id = '%s'","A harmonious marriage of spices, herbs and/or vegetables and beer. The key attributes of the underlying style will be different with the addition of spices, herbs and/or vegetables; do not expect the base beer to taste the same as the unadulterated version. Judge the beer based on the pleasantness and balance of the resulting combination. Entry Instructions: Entrant must specify the underlying beer style as well as the type of spices, herbs, or vegetables used. Classic styles do not have to be cited. Commercial Examples: Alesmith Speedway Stout, Founders Breakfast Stout, Traquair Jacobite Ale, Rogue Chipotle Ale, Young&rsquo;s Double Chocolate Stout, Bell&rsquo;s Java Stout, Fraoch Heather Ale, Southampton Pumpkin Ale, Rogue Hazelnut Nectar, Hitachino Nest Real Ginger Ale, Breckenridge Vanilla Porter, Left Hand JuJu Ginger Beer, Dogfish Head Punkin Ale, Dogfish Head Midas Touch, Redhook Double Black Stout, Buffalo Bill&rsquo;s Pumpkin Ale,  BluCreek Herbal Ale, Christian Moerlein Honey Almond,  Rogue Chocolate Stout, Birrificio Baladin Nora, Cave Creek Chili Beer.","75");
mysqli_select_db($connection,$database);
mysqli_real_escape_string($connection,$updateSQL);
$result = mysqli_query($connection,$updateSQL) or die (mysqli_error($connection));

$updateSQL = sprintf("UPDATE ".$styles_db_table." SET brewStyleInfo = '%s' WHERE id = '%s'","A harmonious marriage of ingredients, processes and beer. The key attributes of the underlying style (if declared) will be atypical due to the addition of special ingredients or techniques; do not expect the base beer to taste the same as the unadulterated version. Judge the beer based on the pleasantness and harmony of the resulting combination. The overall uniqueness of the process, ingredients used, and creativity should be considered. The overall rating of the beer depends heavily on the inherently subjective assessment of distinctiveness and drinkability. Entry Instructions: The brewer must specify the experimental nature of the beer (e.g., the type of special ingredients used, process utilized, or historical style being brewed), or why the beer doesn&rsquo;t fit into an established style. Commercial Examples: Bell&rsquo;s Rye Stout, Bell&rsquo;s Eccentric Ale, Samuel Adams Triple Bock and Utopias, Hair of the Dog Adam, Great Alba Scots Pine, Tommyknocker Maple Nut Brown Ale, Great Divide Bee Sting Honey Ale, Stoudt&rsquo;s Honey Double Mai Bock, Rogue Dad&rsquo;s Little Helper, Rogue Honey Cream Ale, Dogfish Head India Brown Ale, Zum Uerige Sticke and Doppel Sticke Altbier, Yards Brewing Company General Washington Tavern Porter, Rauchenfels Steinbier, Odells 90 Shilling Ale, Bear Republic Red Rocket Ale, Stone Arrogant Bastard.","80");
mysqli_select_db($connection,$database);
mysqli_real_escape_string($connection,$updateSQL);
$result = mysqli_query($connection,$updateSQL) or die (mysqli_error($connection));

$updateSQL = sprintf("UPDATE ".$styles_db_table." SET brewStyleInfo = '%s' WHERE id = '%s'","In well-made examples of the style, the fruit is both distinctive and well-incorporated into the honey-sweet-acid-tannin-alcohol balance of the mead. Different types of fruit can result in widely different characteristics; allow for a variation in the final product. Entry Instructions: Entrants MUST specify the varieties of fruit used. Commercial Examples: White Winter Blueberry, Raspberry and Strawberry Melomels, Redstone Black Raspberry and Sunshine Nectars, Bees Brothers Raspberry Mead, Intermiel Honey Wine and Raspberries, Honey Wine and Blueberries, and Honey Wine and Blackcurrants, Long Island Meadery Blueberry Mead, Mountain Meadows Cranberry and Cherry Meads.","86");
mysqli_select_db($connection,$database);
mysqli_real_escape_string($connection,$updateSQL);
$result = mysqli_query($connection,$updateSQL) or die (mysqli_error($connection));

$updateSQL = sprintf("UPDATE ".$styles_db_table." SET brewStyleInfo = '%s' WHERE id = '%s'","Often, a blend of spices may give a character greater than the sum of its parts. The better examples of this style use spices/herbs subtly and when more than one are used, they are carefully selected so that they blend harmoniously. See standard description for entrance requirements. Entrants MUST specify carbonation level, strength, and sweetness. Entrants MAY specify honey varieties. Entrants MUST specify the types of spices used. Entry Instructions: Entrants MUST specify the types of spices used. Commercial Examples: Bonair Chili Mead, Redstone Juniper Mountain Honey Wine, Redstone Vanilla Beans and Cinnamon Sticks Mountain Honey Wine, Long Island Meadery Vanilla Mead, iQhilika Africa Birds Eye Chili Mead, Mountain Meadows Spice Nectar.","87");
mysqli_select_db($connection,$database);
mysqli_real_escape_string($connection,$updateSQL);
$result = mysqli_query($connection,$updateSQL) or die (mysqli_error($connection));

$updateSQL = sprintf("UPDATE ".$styles_db_table." SET brewStyleInfo = '%s' WHERE id = '%s'","See standard description for entrance requirements. Entrants MUST specify carbonation level, strength, and sweetness. Entrants MAY specify honey varieties. Entry Instructions: Entrants MUST specify the special nature of the mead, whether it is a combination of existing styles, an experimental mead, a historical mead, or some other creation. Any special ingredients that impart an identifiable character MAY be declared. Commercial Examples: Jadwiga, Hanssens/Lurgashall Mead the Gueuze, Rabbit&rsquo;s Foot Private Reserve Pear Mead, White Winter Cherry Bracket, Saba Tej, Mountain Meadows Trickster&rsquo;s Treat Agave Mead, Intermiel Ros&eacute;e.","89");
mysqli_select_db($connection,$database);
mysqli_real_escape_string($connection,$updateSQL);
$result = mysqli_query($connection,$updateSQL) or die (mysqli_error($connection));

$updateSQL = sprintf("UPDATE ".$styles_db_table." SET brewStyleInfo = '%s' WHERE id = '%s'","Adjuncts may include white and brown sugars, molasses, small amounts of honey, and raisins. Adjuncts are intended to raise OG well above that which would be achieved by apples alone. This style is sometimes barrel-aged, in which case there will be oak character as with a barrel-aged wine. If the barrel was formerly used to age spirits, some flavor notes from the spirit (e.g., whisky or rum) may also be present, but must be subtle. Entry Instructions: Entrants MUST specify if the cider was barrel-fermented or aged. Entrants MUST specify carbonation level (still, petillant, or sparkling). Entrants MUST specify sweetness (dry, medium, or sweet).","95");
mysqli_select_db($connection,$database);
mysqli_real_escape_string($connection,$updateSQL);
$result = mysqli_query($connection,$updateSQL) or die (mysqli_error($connection));

$updateSQL = sprintf("UPDATE ".$styles_db_table." SET brewStyleInfo = '%s' WHERE id = '%s'","Like a dry wine with complex flavors. The apple character must marry with the added fruit so that neither dominates the other. Entry Instructions: Entrants MUST specify what fruit(s) and/or fruit juice(s) were added. Commercial Examples: [US] West County Blueberry-Apple Wine (MA), AEppelTreow Red Poll Cran-Apple Draft Cider (WI), Bellwether Cherry Street (NY), Uncle John&rsquo;s Fruit Farm Winery Apple Cherry Hard Cider (MI).","96");
mysqli_select_db($connection,$database);
mysqli_real_escape_string($connection,$updateSQL);
$result = mysqli_query($connection,$updateSQL) or die (mysqli_error($connection));

$updateSQL = sprintf("UPDATE %s SET brewStyleReqSpec = '1' WHERE id = 178;",$styles_db_table);
mysqli_select_db($connection,$database);
mysqli_real_escape_string($connection,$updateSQL);
$result = mysqli_query($connection,$updateSQL) or die (mysqli_error($connection));

$updateSQL = sprintf("UPDATE ".$styles_db_table." SET brewStyleInfo = '%s' WHERE id = '%s'","Most commonly, a pale, refreshing, highly-attenuated, moderately-bitter, moderate-strength Belgian ale with a very dry finish. Typically highly carbonated, and using non-barley cereal grains and optional spices for complexity, as complements the expressive yeast character that is fruity, spicy, and not overly phenolic. Less common variations include both lower-alcohol and higher-alcohol products, as well as darker versions with additional malt character. Entry Instructions: The entrant must specify the strength (table, standard, super) and the color (pale, dark). Commercial Examples: Ellezelloise Saison, Fantôme Saison, Lefebvre Saison 1900, Saison Dupont Vieille Provision, Saison de Pipaix, Saison Regal, Saison Voisin,  Boulevard Tank 7 Farmhouse Ale.","178");
mysqli_select_db($connection,$database);
mysqli_real_escape_string($connection,$updateSQL);
$result = mysqli_query($connection,$updateSQL) or die (mysqli_error($connection));

$updateSQL = sprintf("UPDATE %s SET brewStyleReqSpec = '1' WHERE id = 125;",$styles_db_table);
mysqli_select_db($connection,$database);
mysqli_real_escape_string($connection,$updateSQL);
$result = mysqli_query($connection,$updateSQL) or die (mysqli_error($connection));

$updateSQL = sprintf("UPDATE ".$styles_db_table." SET brewStyleInfo = '%s' WHERE id = '%s'","A strong, rich, and very malty German lager that can have both pale and dark variants. The darker versions have more richly-developed, deeper malt flavors, while the paler versions have slightly more hops and dryness. Entry Instructions: The entrant must specify whether the entry is a pale or a dark variant. Commercial Examples: Dark Versions –Andechser Doppelbock Dunkel, Ayinger Celebrator, Paulaner Salvator, Spaten Optimator, Tröegs Troegenator, Weihenstephaner Korbinian,; Pale Versions – Eggenberg Urbock 23º, EKU 28, Plank Bavarian Heller Doppelbock.","125");
mysqli_select_db($connection,$database);
mysqli_real_escape_string($connection,$updateSQL);
$result = mysqli_query($connection,$updateSQL) or die (mysqli_error($connection));

$updateSQL = sprintf("UPDATE %s SET brewStyleReqSpec = '1' WHERE id = 176;",$styles_db_table);
mysqli_select_db($connection,$database);
mysqli_real_escape_string($connection,$updateSQL);
$result = mysqli_query($connection,$updateSQL) or die (mysqli_error($connection));

$updateSQL = sprintf("UPDATE ".$styles_db_table." SET brewStyleInfo = '%s' WHERE id = '%s'","A fairly strong, malt-accentuated, lagered artisanal beer with a range of malt flavors appropriate for the color. All are malty yet dry, with clean flavors and a smooth character. Three main variations are included in the style: the brown (brune), the blond (blonde), and the amber (ambree). The darker versions will have more malt character, while the paler versions can have more hops (but still are malt-focused beers). A related style is Biere de Mars, which is brewed in March (Mars) for present use and will not age as well. Attenuation rates are in the 80-85% range. Some fuller-bodied examples exist, but these are somewhat rare. Age and oxidation in imports often increases fruitiness, caramel flavors, and adds corked and musty notes; these are all signs of mishandling, not characteristic elements of the style. Entry Instructions: Entrant must specify blond, amber, or brown biere de garde. If no color is specified, the judge should attempt to judge based on initial observation, expecting a malt flavor and balance that matches the color. Commercial Examples: Ch&rsquo;Ti (brown and blond), Jenlain (amber and blond), La Choulette (all 3 versions), St. Amand (brown), Saint Sylvestre 3 Monts (blond), Russian River Perdition.","176");
mysqli_select_db($connection,$database);
mysqli_real_escape_string($connection,$updateSQL);
$result = mysqli_query($connection,$updateSQL) or die (mysqli_error($connection));

$updateSQL = sprintf("UPDATE %s SET brewStyleReqSpec = '1' WHERE id = 130;",$styles_db_table);
mysqli_select_db($connection,$database);
mysqli_real_escape_string($connection,$updateSQL);
$result = mysqli_query($connection,$updateSQL) or die (mysqli_error($connection));

$updateSQL = sprintf("UPDATE ".$styles_db_table." SET brewStyleInfo = '%s' WHERE id = '%s'","A strong, malty, fruity, wheat-based ale combining the best malt and yeast flavors of a weissbier (pale or dark) with the malty-rich flavor, strength, and body of a bock (standard or doppelbock).
A weissbier brewed to bock or doppelbock strength. Schneider also produces an Eisbock version. Pale and dark versions exist, although dark are more common. Pale versions have less rich malt complexity and often more hops, as with doppelbocks. Lightly oxidized Maillard products can produce some rich, intense flavors and aromas that are often seen in aged imported commercial products; fresher versions will not have this character. Well-aged examples might also take on a slight sherry-like complexity. Entry Instructions: The entrant must specify whether the entry is a pale or a dark variant.  Commercial Examples: Dark - Schneider Aventinus, Schneider Aventinus Eisbock, Eisenbahn Vigorosa, Plank Bavarian Dunkler Weizenbock; Pale - Weihenstephaner Vitus, Plank Bavarian Heller Weizenbock.","130");
mysqli_select_db($connection,$database);
mysqli_real_escape_string($connection,$updateSQL);
$result = mysqli_query($connection,$updateSQL) or die (mysqli_error($connection));

$output .=  "<li>Style data updated.</li>";

$output .= "</ul>";
?>