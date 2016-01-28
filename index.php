<?php 
/**
 * Module:      index.php 
 * Description: This module is the delivery vehicle for all modules.
 * 
 */

require('paths.php');
require(CONFIG.'bootstrap.php');
include(DB.'mods.db.php');

// -----------------------------------------------------------------------------------------
// Remove the following after 2.0.0 release (for those using committed code pre-release)

if (!check_update("sponsorEnable", $prefix."sponsors")) {
	$updateSQL0 = "ALTER TABLE `".$prefix."sponsors` ADD `sponsorEnable` TINYINT(1) NULL;";
	mysql_select_db($database, $brewing);
	mysql_real_escape_string($updateSQL0);
	$result0 = mysql_query($updateSQL0, $brewing); 
	
	// Enable display of all sponsors. Admins can change if desired.
	$updateSQL6 = sprintf("UPDATE %s SET sponsorEnable = '1';",$sponsors_db_table);
	mysql_select_db($database, $brewing);
	mysql_real_escape_string($updateSQL6);
	$result6 = mysql_query($updateSQL6, $brewing) or die(mysql_error());
}

if (!check_update("contestShippingOpen", $prefix."contest_info")) {
	$updateSQL1 = "ALTER TABLE  `".$prefix."contest_info` CHANGE `contestContactName` `contestShippingOpen` VARCHAR(255) NULL DEFAULT NULL;";
	mysql_select_db($database, $brewing);
	mysql_real_escape_string($updateSQL1);
	$result1 = mysql_query($updateSQL1, $brewing); 
	
	// Copy entry open/close values to newly created shipping open/close dates
	$updateSQL7 = sprintf("UPDATE %s SET contestShippingOpen = contestEntryOpen",$contest_info_db_table);
	mysql_select_db($database, $brewing);
	mysql_real_escape_string($updateSQL7);
	$result7 = mysql_query($updateSQL7, $brewing) or die(mysql_error());
}

if (!check_update("contestShippingDeadline", $prefix."contest_info")) {
	$updateSQL2 = "ALTER TABLE  `".$prefix."contest_info` CHANGE `contestContactEmail` `contestShippingDeadline` VARCHAR(255) NULL DEFAULT NULL;";
	mysql_select_db($database, $brewing);
	mysql_real_escape_string($updateSQL2);
	$result2 = mysql_query($updateSQL2, $brewing);
	
	$updateSQL8 = sprintf("UPDATE %s SET contestShippingDeadline = contestEntryDeadline",$contest_info_db_table);
	mysql_select_db($database, $brewing);
	mysql_real_escape_string($updateSQL8);
	$result8 = mysql_query($updateSQL8, $brewing) or die(mysql_error());	
	
}

if (!check_update("contestCheckInPassword", $prefix."contest_info")) {
	$updateSQL3 = "ALTER TABLE  `".$prefix."contest_info` ADD `contestCheckInPassword` VARCHAR(255) NULL DEFAULT NULL;";
	mysql_select_db($database, $brewing);
	mysql_real_escape_string($updateSQL3);
	$result3 = mysql_query($updateSQL3, $brewing); 
	
}

if (!check_update("contestDropoffOpen", $prefix."contest_info")) {
	$updateSQL4 = "ALTER TABLE  `".$prefix."contest_info` CHANGE `contestCategories` `contestDropoffOpen` VARCHAR(255) NULL DEFAULT NULL;";
	mysql_select_db($database, $brewing);
	mysql_real_escape_string($updateSQL4);
	$result4 = mysql_query($updateSQL4, $brewing); 
	
	$updateSQL9 = sprintf("UPDATE %s SET contestDropoffOpen = contestEntryOpen",$contest_info_db_table);
	mysql_select_db($database, $brewing);
	mysql_real_escape_string($updateSQL9);
	$result9 = mysql_query($updateSQL9, $brewing) or die(mysql_error());
}

if (!check_update("contestDropoffDeadline", $prefix."contest_info")) {
	$updateSQL5 = "ALTER TABLE  `".$prefix."contest_info` CHANGE `contestWinnersComplete` `contestDropoffDeadline` VARCHAR(255) NULL DEFAULT NULL;";
	mysql_select_db($database, $brewing);
	mysql_real_escape_string($updateSQL5);
	$result5 = mysql_query($updateSQL5, $brewing);
	
	$updateSQL10 = sprintf("UPDATE %s SET contestDropoffDeadline = contestEntryDeadline",$contest_info_db_table);
	mysql_select_db($database, $brewing);
	mysql_real_escape_string($updateSQL10);
	$result10 = mysql_query($updateSQL10, $brewing) or die(mysql_error());	
	
	$updateSQL = sprintf("UPDATE ".$styles_db_table." SET brewStyleInfo = '%s' WHERE id = '%s'","Variable. This category encompasses a wide range of Belgian ales produced by truly artisanal brewers more concerned with creating unique products than in increasing sales. Entry Instructions: The brewer must specify either the beer being cloned, the new style being produced or the special ingredients or processes used. Commercial Examples: Orval; De Dolle&rsquo;s Arabier, Oerbier, Boskeun and Stille Nacht; La Chouffe, McChouffe, Chouffe Bok and N&rsquo;ice Chouffe; Ellezelloise Hercule Stout and Quintine Amber; Unibroue Ephemere, Maudite, Don de Dieu, etc.; Minty; Zatte Bie; Caracole Amber, Saxo and Nostradamus; Silenrieu Sara and Joseph; Fant&ocirc;me Black Ghost and Speciale No&euml;l; Dupont Moinette, Moinette Brune, and Avec Les Bons Voeux de la Brasserie Dupont; St. Fullien No&euml;l; Gouden Carolus No&euml;l; Affligem N&ouml;el; Guldenburg and Pere No&euml;l; De Ranke XX Bitter and Guldenberg; Poperings Hommelbier; Bush (Scaldis); Moinette Brune; Grottenbier; La Trappe Quadrupel; Weyerbacher QUAD; Bi&egrave;re de Miel; Verboden Vrucht; New Belgium 1554 Black Ale; Cantillon Iris; Russian River Temptation; Lost Abbey Cuvee de Tomme and Devotion, Lindemans Kriek and Framboise, and many more.","59");
	mysql_select_db($database, $brewing);
	mysql_real_escape_string($updateSQL);
	$result = mysql_query($updateSQL, $brewing);
	
	$updateSQL = sprintf("UPDATE ".$styles_db_table." SET brewStyleInfo = '%s' WHERE id = '%s'","Complex, fruity, pleasantly sour/acidic, balanced, pale, wheat-based ale fermented by a variety of Belgian microbiota. A lambic with fruit, not just a fruit beer. Entry Instructions: Entrant must specify the type of fruit(s) used in the making of the lambic. Commercial Examples: Boon Framboise Marriage Parfait, Boon Kriek Mariage Parfait, Boon Oude Kriek, Cantillon Fou&rsquo; Foune (apricot), Cantillon Kriek, Cantillon Lou Pepe Kriek, Cantillon Lou Pepe Framboise, Cantillon Rose de Gambrinus, Cantillon St. Lamvinus (merlot grape), Cantillon Vigneronne (Muscat grape), De Cam Oude Kriek, Drie Fonteinen Kriek, Girardin Kriek, Hanssens Oude Kriek, Oud Beersel Kriek, Mort Subite Kriek.","65");
	mysql_select_db($database, $brewing);
	mysql_real_escape_string($updateSQL);
	$result = mysql_query($updateSQL, $brewing);
	
	$updateSQL = sprintf("UPDATE ".$styles_db_table." SET brewStyleInfo = '%s' WHERE id = '%s'","A harmonious marriage of fruit and beer. The key attributes of the underlying style will be different with the addition of fruit; do not expect the base beer to taste the same as the unadulterated version. Judge the beer based on the pleasantness and balance of the resulting combination. Entry Instructions: Entrant must specify the underlying beer style as well as the type of fruit(s) used. Classic styles do not have to be cited. Commercial Examples: New Glarus Belgian Red and Raspberry Tart, Bell&rsquo;s Cherry Stout, Dogfish Head Aprihop, Great Divide Wild Raspberry Ale, Founders R&uuml;b&aelig;us, Ebulum Elderberry Black Ale, Stiegl Radler, Weyerbacher Raspberry Imperial Stout, Abita Purple Haze, Melbourne Apricot Beer and Strawberry Beer, Saxer Lemon Lager, Magic Hat #9, Grozet Gooseberry and Wheat Ale,  Pyramid Apricot Ale, Dogfish Head Fort.","74");
	mysql_select_db($database, $brewing);
	mysql_real_escape_string($updateSQL);
	$result = mysql_query($updateSQL, $brewing);
	
	$updateSQL = sprintf("UPDATE ".$styles_db_table." SET brewStyleInfo = '%s' WHERE id = '%s'","A harmonious marriage of spices, herbs and/or vegetables and beer. The key attributes of the underlying style will be different with the addition of spices, herbs and/or vegetables; do not expect the base beer to taste the same as the unadulterated version. Judge the beer based on the pleasantness and balance of the resulting combination. Entry Instructions: Entrant must specify the underlying beer style as well as the type of spices, herbs, or vegetables used. Classic styles do not have to be cited. Commercial Examples: Alesmith Speedway Stout, Founders Breakfast Stout, Traquair Jacobite Ale, Rogue Chipotle Ale, Young&rsquo;s Double Chocolate Stout, Bell&rsquo;s Java Stout, Fraoch Heather Ale, Southampton Pumpkin Ale, Rogue Hazelnut Nectar, Hitachino Nest Real Ginger Ale, Breckenridge Vanilla Porter, Left Hand JuJu Ginger Beer, Dogfish Head Punkin Ale, Dogfish Head Midas Touch, Redhook Double Black Stout, Buffalo Bill&rsquo;s Pumpkin Ale,  BluCreek Herbal Ale, Christian Moerlein Honey Almond,  Rogue Chocolate Stout, Birrificio Baladin Nora, Cave Creek Chili Beer.","75");
	mysql_select_db($database, $brewing);
	mysql_real_escape_string($updateSQL);
	$result = mysql_query($updateSQL, $brewing);
	
	$updateSQL = sprintf("UPDATE ".$styles_db_table." SET brewStyleInfo = '%s' WHERE id = '%s'","A harmonious marriage of ingredients, processes and beer. The key attributes of the underlying style (if declared) will be atypical due to the addition of special ingredients or techniques; do not expect the base beer to taste the same as the unadulterated version. Judge the beer based on the pleasantness and harmony of the resulting combination. The overall uniqueness of the process, ingredients used, and creativity should be considered. The overall rating of the beer depends heavily on the inherently subjective assessment of distinctiveness and drinkability. Entry Instructions: The brewer must specify the experimental nature of the beer (e.g., the type of special ingredients used, process utilized, or historical style being brewed), or why the beer doesn't fit into an established style. Commercial Examples: Bell&rsquo;s Rye Stout, Bell&rsquo;s Eccentric Ale, Samuel Adams Triple Bock and Utopias, Hair of the Dog Adam, Great Alba Scots Pine, Tommyknocker Maple Nut Brown Ale, Great Divide Bee Sting Honey Ale, Stoudt&rsquo;s Honey Double Mai Bock, Rogue Dad&rsquo;s Little Helper, Rogue Honey Cream Ale, Dogfish Head India Brown Ale, Zum Uerige Sticke and Doppel Sticke Altbier, Yards Brewing Company General Washington Tavern Porter, Rauchenfels Steinbier, Odells 90 Shilling Ale, Bear Republic Red Rocket Ale, Stone Arrogant Bastard.","80");
	mysql_select_db($database, $brewing);
	mysql_real_escape_string($updateSQL);
	$result = mysql_query($updateSQL, $brewing);
	
	$updateSQL = sprintf("UPDATE ".$styles_db_table." SET brewStyleInfo = '%s' WHERE id = '%s'","In well-made examples of the style, the fruit is both distinctive and well-incorporated into the honey-sweet-acid-tannin-alcohol balance of the mead. Different types of fruit can result in widely different characteristics; allow for a variation in the final product. Entry Instructions: Entrants MUST specify the varieties of fruit used. Commercial Examples: White Winter Blueberry, Raspberry and Strawberry Melomels, Redstone Black Raspberry and Sunshine Nectars, Bees Brothers Raspberry Mead, Intermiel Honey Wine and Raspberries, Honey Wine and Blueberries, and Honey Wine and Blackcurrants, Long Island Meadery Blueberry Mead, Mountain Meadows Cranberry and Cherry Meads.","86");
	mysql_select_db($database, $brewing);
	mysql_real_escape_string($updateSQL);
	$result = mysql_query($updateSQL, $brewing);
	
	$updateSQL = sprintf("UPDATE ".$styles_db_table." SET brewStyleInfo = '%s' WHERE id = '%s'","Often, a blend of spices may give a character greater than the sum of its parts. The better examples of this style use spices/herbs subtly and when more than one are used, they are carefully selected so that they blend harmoniously. See standard description for entrance requirements. Entrants MUST specify carbonation level, strength, and sweetness. Entrants MAY specify honey varieties. Entrants MUST specify the types of spices used. Entry Instructions: Entrants MUST specify the types of spices used. Commercial Examples: Bonair Chili Mead, Redstone Juniper Mountain Honey Wine, Redstone Vanilla Beans and Cinnamon Sticks Mountain Honey Wine, Long Island Meadery Vanilla Mead, iQhilika Africa Birds Eye Chili Mead, Mountain Meadows Spice Nectar.","87");
	mysql_select_db($database, $brewing);
	mysql_real_escape_string($updateSQL);
	$result = mysql_query($updateSQL, $brewing);
	
	$updateSQL = sprintf("UPDATE ".$styles_db_table." SET brewStyleInfo = '%s' WHERE id = '%s'","See standard description for entrance requirements. Entrants MUST specify carbonation level, strength, and sweetness. Entrants MAY specify honey varieties. Entry Instructions: Entrants MUST specify the special nature of the mead, whether it is a combination of existing styles, an experimental mead, a historical mead, or some other creation. Any special ingredients that impart an identifiable character MAY be declared. Commercial Examples: Jadwiga, Hanssens/Lurgashall Mead the Gueuze, Rabbit&rsquo;s Foot Private Reserve Pear Mead, White Winter Cherry Bracket, Saba Tej, Mountain Meadows Trickster&rsquo;s Treat Agave Mead, Intermiel Ros&eacute;e.","89");
	mysql_select_db($database, $brewing);
	mysql_real_escape_string($updateSQL);
	$result = mysql_query($updateSQL, $brewing);
	
	$updateSQL = sprintf("UPDATE ".$styles_db_table." SET brewStyleInfo = '%s' WHERE id = '%s'","Adjuncts may include white and brown sugars, molasses, small amounts of honey, and raisins. Adjuncts are intended to raise OG well above that which would be achieved by apples alone. This style is sometimes barrel-aged, in which case there will be oak character as with a barrel-aged wine. If the barrel was formerly used to age spirits, some flavor notes from the spirit (e.g., whisky or rum) may also be present, but must be subtle. Entry Instructions: Entrants MUST specify if the cider was barrel-fermented or aged. Entrants MUST specify carbonation level (still, petillant, or sparkling). Entrants MUST specify sweetness (dry, medium, or sweet).","95");
	mysql_select_db($database, $brewing);
	mysql_real_escape_string($updateSQL);
	$result = mysql_query($updateSQL, $brewing);
	
	$updateSQL = sprintf("UPDATE ".$styles_db_table." SET brewStyleInfo = '%s' WHERE id = '%s'","Like a dry wine with complex flavors. The apple character must marry with the added fruit so that neither dominates the other. Entry Instructions: Entrants MUST specify what fruit(s) and/or fruit juice(s) were added. Commercial Examples: [US] West County Blueberry-Apple Wine (MA), AEppelTreow Red Poll Cran-Apple Draft Cider (WI), Bellwether Cherry Street (NY), Uncle John&rsquo;s Fruit Farm Winery Apple Cherry Hard Cider (MI).","96");
	mysql_select_db($database, $brewing);
	mysql_real_escape_string($updateSQL);
	$result = mysql_query($updateSQL, $brewing);	
	
	$updateSQL6 = "DROP TABLE IF EXISTS `".$prefix."countries`";
	mysql_select_db($database, $brewing);
	mysql_real_escape_string($updateSQL6);
	$result6 = mysql_query($updateSQL6, $brewing);
	
	$updateSQL6 = "DROP TABLE IF EXISTS `".$prefix."themes`";
	mysql_select_db($database, $brewing);
	mysql_real_escape_string($updateSQL6);
	$result6 = mysql_query($updateSQL6, $brewing);
	
	// -----------------------------------------------------------
	// Alter Table: Brewer
	// Adding judge experience
	// Add judge notes to organizers
	// -----------------------------------------------------------
	
	$updateSQL4 = "ALTER TABLE  `".$prefix."brewer` CHANGE `brewerJudgeAssignedLocation` `brewerJudgeExp` VARCHAR(25) NULL DEFAULT NULL;";
	mysql_select_db($database, $brewing);
	mysql_real_escape_string($updateSQL4);
	$result4 = mysql_query($updateSQL4, $brewing); 
	
	$updateSQL5 = "ALTER TABLE  `".$prefix."brewer` CHANGE `brewerStewardAssignedLocation` `brewerJudgeNotes` TEXT NULL DEFAULT NULL;";
	mysql_select_db($database, $brewing);
	mysql_real_escape_string($updateSQL5);
	$result5 = mysql_query($updateSQL5, $brewing); 
	
	// -----------------------------------------------------------
	// Alter Tables: Archived tables
	// -----------------------------------------------------------
	
	$query_archive_current = sprintf("SELECT archiveSuffix FROM %s",$archive_db_table);
	$archive_current = mysql_query($query_archive_current, $brewing);
	$row_archive_current = mysql_fetch_assoc($archive_current);
	$totalRows_archive_current = mysql_num_rows($archive_current);
	
	if ($totalRows_archive_current > 0) {
		
		do { $a_current[] = $row_archive_current['archiveSuffix']; } while ($row_archive_current = mysql_fetch_assoc($archive_current));
		
		foreach ($a_current as $suffix_current) {
			
			$suffix_current = "_".$suffix_current;
			
			// Update brewer table with changed values
			if (check_setup($prefix."brewer".$suffix_current,$database)) {
				
				$updateSQL4 = "ALTER TABLE  `".$prefix."brewer".$suffix_current."` CHANGE `brewerJudgeAssignedLocation` `brewerJudgeExp` VARCHAR(25) NULL DEFAULT NULL;";
				mysql_select_db($database, $brewing);
				mysql_real_escape_string($updateSQL4);
				$result4 = mysql_query($updateSQL4, $brewing); 
				
				$updateSQL5 = "ALTER TABLE  `".$prefix."brewer".$suffix_current."` CHANGE `brewerStewardAssignedLocation` `brewerJudgeNotes` TEXT NULL DEFAULT NULL;";
				mysql_select_db($database, $brewing);
				mysql_real_escape_string($updateSQL5);
				$result5 = mysql_query($updateSQL5, $brewing);
				
			} // end if (check_setup($prefix."brewer".$suffix_current,$database))
			
		} // end foreach ($a_current as $suffix_current)
		
	} // end if ($totalRows_archive_current > 0)
	
} // end if (!check_update("contestDropoffDeadline", $prefix."contest_info"))



// -----------------------------------------------------------------------------------------

$account_pages = array("list","pay","brewer","user","brew","beerxml","pay");
if ((!$logged_in) && (in_array($section,$account_pages))) {
	header(sprintf("Location: %s", $base_url."index.php?section=login&msg=99")); exit;
}
	
if ($section == "admin") {
	
	// Redirect if non-admins try to access admin functions
	if (!$logged_in) { header(sprintf("Location: %s", $base_url."index.php?section=login&msg=0")); exit; }
	if (($logged_in) && ($_SESSION['userLevel'] > 1)) { header(sprintf("Location: %s", $base_url."index.php?msg=4")); exit; }
	
	include(LIB.'admin.lib.php');
	include(DB.'admin_common.db.php');
	include(DB.'judging_locations.db.php'); 
	include(DB.'stewarding.db.php'); 
	include(DB.'dropoff.db.php'); 
	include(DB.'contacts.db.php');
	
	if (($go == "default") || ($go == "entries")) {
		$totalRows_entry_count = total_paid_received("default","default");
	}
}

if (TESTING) {
	$mtime = microtime(); 
	$mtime = explode(" ",$mtime); 
	$mtime = $mtime[1] + $mtime[0]; 
	$starttime = $mtime; 
}

if ($section == "admin") {
	$container_main = "container-fluid";
	$nav_container = "navbar-inverse";
}
	
else { 
	$container_main = "container";
	$nav_container = "navbar-default";
}

// Load libraries only when needed for performance
$tinymce_load = array("contest_info","special_best","styles");
$datetime_load = array("contest_info","judging","testing");
if ((judging_date_return() == 0) && ($registration_open == "2")) $datatables_load = array("admin","list","default");
else $datatables_load = array("admin","list");

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?php echo $_SESSION['contestName']; ?> - Brew Competition Online Entry &amp; Management</title>
    
	<!-- Load jQuery / http://jquery.com/ -->
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.4/jquery.min.js"></script>
	
    <!-- Load Bootstrap / http://www.getbootsrap.com -->
    <link rel="stylesheet" type="text/css" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css" />
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js"></script>
        
    <!--[if lt IE 9]>
      <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
      <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
   
    <?php if (in_array($section,$datatables_load)) { ?>
    <!-- Load DataTables / https://www.datatables.net -->
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.10/css/dataTables.bootstrap.min.css" />
	<link rel="stylesheet" type="text/css" href="http://cdn.datatables.net/plug-ins/1.10.10/integration/font-awesome/dataTables.fontAwesome.css" />
	<script type="text/javascript" src="https://cdn.datatables.net/1.10.10/js/jquery.dataTables.min.js"></script>
    <script type="text/javascript" src="https://cdn.datatables.net/1.10.10/js/dataTables.bootstrap.min.js"></script>
	<?php } ?>
    
    <!-- Load Fancybox / http://www.fancyapps.com -->
    <link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/fancybox/2.1.5/jquery.fancybox.min.css" media="screen" />
	<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jquery-easing/1.3/jquery.easing.min.js"></script>
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jquery-mousewheel/3.1.13/jquery.mousewheel.min.js"></script>
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/fancybox/2.1.5/jquery.fancybox.pack.js"></script>
    
    <?php if (($section == "admin") && (in_array($go,$datetime_load)) || ($section == "brew")) { ?>
    <!-- Load Bootstrap DateTime Picker / http://eonasdan.github.io/bootstrap-datetimepicker/ -->
    <link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datetimepicker/4.17.37/css/bootstrap-datetimepicker.min.css" />
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.11.1/moment-with-locales.min.js"></script>
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datetimepicker/4.17.37/js/bootstrap-datetimepicker.min.js"></script>
    <?php } ?>
	
	<?php if (($section == "admin") && (in_array($go,$tinymce_load))) { ?>
    <!-- Load TinyMCE / https://www.tinymce.com/ -->
	<script src="//cdn.tinymce.com/4/tinymce.min.js"></script>
	<?php } ?>
	
	<?php if (($logged_in) && ($_SESSION['userLevel'] <= 1)) { ?>
    <!-- Load Jasny Off-Canvas Menu for Admin / http://www.jasny.net/bootstrap -->
    <link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/jasny-bootstrap/3.1.3/css/jasny-bootstrap.min.css">
    <script src="//cdnjs.cloudflare.com/ajax/libs/jasny-bootstrap/3.1.3/js/jasny-bootstrap.min.js"></script>
		<?php if (($section == "admin") && ($go == "upload")) { ?>
        <!-- Load DropZone / http://www.dropzonejs.com -->
        <link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/dropzone/4.2.0/min/dropzone.min.css" />
        <script src="https://cdnjs.cloudflare.com/ajax/libs/dropzone/4.2.0/min/dropzone.min.js"></script>
        <script src="<?php echo $base_url;?>js_includes/dz.min.js"></script>
        <?php } ?>
    <?php } ?>
	
	<!-- Load Bootstrap Form Validator / http://1000hz.github.io/bootstrap-validator -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/1000hz-bootstrap-validator/0.9.0/validator.min.js"></script>
    
    <!-- Load Bootstrap-Select / http://silviomoreto.github.io/bootstrap-select -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.9.3/css/bootstrap-select.min.css">	
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.9.3/js/bootstrap-select.min.js"></script>
    
    <!-- Load Font Awesome / https://fortawesome.github.io/Font-Awesome -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.5.0/css/font-awesome.min.css">
    
    <!-- Load BCOE&M Custom Theme CSS - Contains Bootstrap overrides and custom classes -->
    <link rel="stylesheet" type="text/css" href="<?php echo $theme; ?>" />
	
	<!-- Load BCOE&M Custom JS -->
    <script src="<?php echo $base_url; ?>js_includes/bcoem_custom.min.js"></script>
</head>
<body>
	
    <!-- MAIN NAV -->
	<div class="<?php echo $container_main; ?> hidden-print">
		<?php include (SECTIONS.'nav.sec.php'); ?>
	</div><!-- container -->   
    <!-- ./MAIN NAV -->
    
    <!-- ALERTS -->
    <div class="<?php echo $container_main; ?> bcoem-warning-container">
    	<?php include (SECTIONS.'alerts.sec.php'); ?>
    </div><!-- ./container --> 
    <!-- ./ALERTS -->
    
    <?php if ($_SESSION['prefsUseMods'] == "Y") { ?>
    <!-- MODS TOP -->
    <?php include(INCLUDES.'mods_top.inc.php'); ?>
    <!-- ./MODS TOP -->
    <?php } ?>
    
    <?php if (($section == "admin") && (($logged_in) && ($_SESSION['userLevel'] <= 1))) { ?>
    <!-- Admin Pages (Fluid Layout) -->
    <div class="container-fluid">
       	<?php if ($go == "default") { ?>
        <!-- Admin Dashboard - Has sidebar -->
        <div class="row">
        	<div class="col col-lg-9 col-md-8 col-sm-12 col-xs-12">
            <div class="page-header">
        		<h1><?php echo $header_output; ?></h1>
            </div>    
            <?php include (ADMIN.'default.admin.php'); ?> 
            </div><!-- ./left column -->
            <div class="sidebar col col-lg-3 col-md-4 col-sm-12 col-xs-12">
            	<?php include (ADMIN.'sidebar.admin.php'); ?>       
            </div><!-- ./sidebar -->
        </div><!-- ./row -->
        <?php } else { ?>
      	<!-- Admin Page - full width of viewport -->
        	<div class="page-header">
        		<h1><?php echo $header_output; ?></h1>
        	</div>
            <?php 
			if ($go == "judging") 	    			include (ADMIN.'judging_locations.admin.php');
			if ($go == "judging_preferences") 	    include (ADMIN.'judging_preferences.admin.php');
			if ($go == "judging_tables") 	    	include (ADMIN.'judging_tables.admin.php');
			if ($go == "judging_flights") 	    	include (ADMIN.'judging_flights.admin.php');
			if ($go == "judging_scores") 	    	include (ADMIN.'judging_scores.admin.php');
			if ($go == "judging_scores_bos")    		include (ADMIN.'judging_scores_bos.admin.php');
			if ($go == "participants") 				include (ADMIN.'participants.admin.php');
			if ($go == "entries") 					include (ADMIN.'entries.admin.php');
			if ($go == "contacts") 	    			include (ADMIN.'contacts.admin.php');
			if ($go == "dropoff") 	    			include (ADMIN.'dropoff.admin.php');
			if ($go == "checkin") 	    			include (ADMIN.'barcode_check-in.admin.php');
			if ($go == "count_by_style")				include (ADMIN.'entries_by_style.admin.php');
			if ($go == "count_by_substyle")			include (ADMIN.'entries_by_substyle.admin.php');
			if ($action == "register")				include (SECTIONS.'register.sec.php');
			
				if ($_SESSION['userLevel'] == "0") {
					if ($go == "styles") 	    	include (ADMIN.'styles.admin.php');
					if ($go == "archive") 	    	include (ADMIN.'archive.admin.php');
					if ($go == "make_admin") 		include (ADMIN.'make_admin.admin.php');
					if ($go == "contest_info") 		include (ADMIN.'competition_info.admin.php');
					if ($go == "preferences") 		include (ADMIN.'site_preferences.admin.php');
					if ($go == "sponsors") 	   		include (ADMIN.'sponsors.admin.php');
					if ($go == "style_types")    	include (ADMIN.'style_types.admin.php');
					if ($go == "special_best") 	    include (ADMIN.'special_best.admin.php');
					if ($go == "special_best_data") 	include (ADMIN.'special_best_data.admin.php');
					if ($go == "mods") 	    		include (ADMIN.'mods.admin.php');
					if ($go == "upload")				include (ADMIN.'upload.admin.php');
				}
			
			} ?>
    </div><!-- ./container-fluid -->    
    <!-- ./Admin Pages -->
    <?php } else { ?>
    <!-- Public Pages (Fixed Layout with Sidebar) -->
    <div class="container"> 
    	<div class="row">
    		<div class="col col-lg-9 col-md-8 col-sm-12 col-xs-12">
            <div class="page-header">
        		<h1><?php echo $header_output; ?></h1>
        	</div>             
        	<?php 
				if ($section == "default") 		include (SECTIONS.'default.sec.php');
				if ($section == "entry") 		include (SECTIONS.'entry_info.sec.php');
				if ($section == "contact") 		include (SECTIONS.'contact.sec.php');
				if ($section == "volunteers")	include (SECTIONS.'volunteers.sec.php');
				if ($section == "sponsors") 		include (SECTIONS.'sponsors.sec.php');
				if ($section == "register")		include (SECTIONS.'register.sec.php');
				if ($section == "brew") 			include (SECTIONS.'brew.sec.php');
				if ($section == "brewer") 		include (SECTIONS.'brewer.sec.php');
				if ($section == "login")			include (SECTIONS.'login.sec.php');
				
				if ($logged_in) {
					if ($section == "list") 		include (SECTIONS.'list.sec.php');
					if ($section == "pay") 		include (SECTIONS.'pay.sec.php');
					if ($section == "user") 		include (SECTIONS.'user.sec.php');
					if ($section == "beerxml") 		include (SECTIONS.'beerxml.sec.php');
				}
			?>
            </div><!-- ./left column -->
            <div class="sidebar col col-lg-3 col-md-4 col-sm-12 col-xs-12">
            	<?php include (SECTIONS.'sidebar.sec.php'); ?>         
            </div><!-- ./sidebar -->
        </div><!-- ./row -->
    	<!-- ./Public Pages -->
        <?php } ?>
    </div><!-- ./container -->
    <!-- ./Public Pages -->
    
    <?php if ($_SESSION['prefsUseMods'] == "Y") { ?>
    <!-- Mods Bottom -->
    <?php include(INCLUDES.'mods_bottom.inc.php'); ?>
    <!-- ./Mods Bottom -->
    <?php } ?>
    
    <!-- Footer -->
    <footer class="footer hidden-xs hidden-sm hidden-md">
    	<div class="navbar <?php echo $nav_container; ?> navbar-fixed-bottom bcoem-footer">
            <div class="<?php echo $container_main; ?> text-center">
                <p class="navbar-text col-md-12 col-sm-12 col-xs-12 text-muted small"><?php include (SECTIONS.'footer.sec.php'); ?></p>
            </div>
    	</div>
    </footer><!-- ./footer --> 
	<!-- ./ Footer -->
    
</body>
</html>