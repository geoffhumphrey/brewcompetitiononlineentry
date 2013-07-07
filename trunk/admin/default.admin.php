<?php 
/**
 * Module:      default.admin.php
 * Description: This module houses links to all administration functions.
 *
 */
include(DB.'admin_common.db.php');
include(DB.'judging_locations.db.php'); 
include(DB.'stewarding.db.php'); 
include(DB.'dropoff.db.php'); 
include(DB.'entries.db.php'); 
include(DB.'brewer.db.php');
include(DB.'contacts.db.php');

// Check if judging flights are up-to-date
if (!check_judging_flights()) { 
$location = $base_url."includes/process.inc.php?action=update_judging_flights&go=admin_dashboard";
header(sprintf("Location: %s", $location)); 
}

function participant_choose($brewer_db_table) {
	require(CONFIG.'config.php');	
	mysql_select_db($database, $brewing);
	
	$query_brewers = "SELECT uid,brewerFirstName,brewerLastName FROM $brewer_db_table ORDER BY brewerLastName";
	$brewers = mysql_query($query_brewers, $brewing) or die(mysql_error());
	$row_brewers = mysql_fetch_assoc($brewers);
	
	$output = "";
	$output .= "<select name=\"participants\" id=\"participants\" onchange=\"jumpMenu('self',this,0)\">";
	$output .= "<option value=\"\">Choose Below:</option>";
	do { 
		$output .= "<option value=\"index.php?section=brew&amp;go=entries&amp;filter=".$row_brewers['uid']."&amp;action=add\">".$row_brewers['brewerLastName'].", ".$row_brewers['brewerFirstName']."</option>"; 
	} while ($row_brewers = mysql_fetch_assoc($brewers)); 
	$output .= "</select>";
	
	return $output;
}


function admin_help($go,$header_output,$action,$filter) {
	include (CONFIG.'config.php');
	switch($go) {
		case "preferences": $page = "site_prefs";
		break;
		
		case "judging_preferences": $page = "comp_org_prefs";
		break;
		
		case "style_types": $page = "style_types";
		break;
		
		case "styles": 
			switch ($action) {
			
			case "add":
			case "edit": $page = "custom_style";
			break;
			
			default: $page = "accepted_style";
			break;
			}
		break;
		
		case "special_best": 
		case "special_best_data": $page = "custom_winner";
		break;
		
		case "judging":
		
			switch($filter) {
				case "judges": 
				case "stewards":
				case "staff":
				$page = "assigning";
				break;
				
				default: $page = "judging_locations";
				break;
				
				
			}
		
		
		break;
		
		case "contacts": $page = "comp_contacts";
		break;
		
		case "dropoff": $page = "drop_off";
		break;
		
		case "sponsors": $page = "sponsors";
		break;
		
		case "contest_info": $page = "competition_info";
		break;
		
		case "entrant":
		case "judge": $page = "participants";
		break;
		
		case "participants": 
			switch ($filter) {
				case "judges": 
				case "assignJudges": $page = "judges";
				break;
				
				case "stewards":
				case "assignStewards": $page = "stewards";
				break;
				
				default: $page = "participants";
				break;
			}
		
		break;
		
		
		case "entries": $page = "entries";
		break;
		
		case "assign": $page = "assigning";
		break;
		
		case "judging_tables": 
			switch ($action) {
				case "assign": $page = "assigning";
				break; 
				
				default: $page = "tables";
				break;
			}
		
		break;
		
		case "judging_flights": 
			switch ($action) {
				
				case "rounds": $page = "rounds";
				break;
				
				case "default": $page = "flights";
				break;
				
			}
			
			switch ($filter) {
				case "rounds": $page = "rounds";
				break;
				
				case "default": $page = "flights";
				break;
			}
		
		break;
		
		case "judging_scores": $page = "scores";
		break;
		
		case "judging_scores_bos": $page = "best_of_show";
		break;
		
		case "special_best_data": $page = "introduction";
		break;
		
		case "archive": $page = "archiving";
		break;
		
		default: $page = "introduction";
		break;
	}
	
	$return = '<p><span class="icon"><img src="'.$base_url.'/images/help.png" /></span><a id="modal_window_link" href="http://help.brewcompetition.com/files/'.$page.'.html" title="BCOE&amp;M Help for '.$header_output.'">Help</a></p>';
	return $return;	
}

function custom_modules($type,$method) {
	require(CONFIG.'config.php');
	
	if ($type == "reports") { $type = 1; $modal = "id='modal_window_link'"; }
	if ($type == "exports") { $type = 2; $modal = ""; }
	
	if ($method == 1) {
		
		$query_custom_number = sprintf("SELECT COUNT(*) as 'count' FROM %s WHERE mod_type='%s'", $prefix."mods", $type);
		$custom_number = mysql_query($query_custom_number, $brewing) or die(mysql_error());
		$row_custom_number = mysql_fetch_assoc($custom_number);
		
		if ($row_custom_number['count'] > 0) return TRUE;	
	}
	
	if ($method == 2) {
		
		$query_custom_mod = sprintf("SELECT * FROM %s WHERE mod_type='%s' ORDER BY mod_name ASC", $prefix."mods", $type);
		$custom_mod = mysql_query($query_custom_mod, $brewing) or die(mysql_error());
		$row_custom_mod = mysql_fetch_assoc($custom_mod);
		$output = "";
		do {
			$output .= "<li><a ".$modal." href='".$base_url."mods/".$row_custom_mod['mod_filename']."'>".$row_custom_mod['mod_name']."</a></li>";
			//$output = $query_custom_mod;
		} while ($row_custom_mod = mysql_fetch_assoc($custom_mod));
		
		return $output;
	}
}

$query_with_entries = sprintf("SELECT COUNT(DISTINCT brewBrewerId) as 'count' FROM %s",$prefix."brewing");
$with_entries = mysql_query($query_with_entries, $brewing) or die(mysql_error());
$row_with_entries = mysql_fetch_assoc($with_entries);

if ($_SESSION['prefsUseMods'] == "Y") include(INCLUDES.'mods_top.inc.php');
if (($section == "admin") && ($go == "default")) { 
$entries_unconfirmed = ($totalRows_entry_count - $totalRows_log_confirmed);
function total_discount() { 
	require(CONFIG.'config.php');
	
	$query_discount = sprintf("SELECT uid FROM %s WHERE brewerDiscount='Y'", $prefix."brewer");
	$discount = mysql_query($query_discount, $brewing) or die(mysql_error());
	$row_discount = mysql_fetch_assoc($discount);
	$totalRows_discount = mysql_num_rows($discount);
	
	do { $a[] = $row_discount['uid']; } while ($row_discount = mysql_fetch_assoc($discount));
	
	foreach ($a as $brewer_id) {
	
		$query_discount_number = sprintf("SELECT COUNT(*) as 'count' FROM %s WHERE brewBrewerId='%s'", $prefix."brewing", $brewer_id);
		$discount_number = mysql_query($query_discount_number, $brewing) or die(mysql_error());
		$row_discount_number = mysql_fetch_assoc($discount_number);
		$b[] = $row_discount_number['count']; 
		
	}
	
	$return = $totalRows_discount."^".array_sum($b);
	return $return;
}

}

?>
<div id="header">	
	<div id="header-inner"><h1><?php echo $header_output; ?></h1></div>
</div>
<script type="text/javascript" src="<?php echo $base_url; ?>js_includes/toggle.js"></script>
<?php 
/*
echo "Tables: ".$query_tables."<br>";
echo "Tables Edit: ".$query_tables_edit."<br>";
echo "Style Type: ".$query_style_type."<br>";
echo "Total tables: ".$totalRows_tables;
*/

if (($setup_free_access == TRUE) && ($action != "print")) echo "<div class='error'>The &#36;setup_free_access variable in config.php is currently set to TRUE. For security reasons, the setting should returned to FALSE. You will need to edit config.php directly and re-upload to your server to do this.</div>";
if (($action != "print") && ($msg != "default")) echo $msg_output; 
if (($action != "print") && ($go != "default")) echo admin_help($go,$header_output,$action,$filter);

?>
<?php if (($section == "admin") && ($go == "default")) { ?>
<div class="at-a-glance">
<h3>Numbers at a Glance</h3> 
<table>
	<tr>
    	<td colspan="6">As of <?php echo getTimeZoneDateTime($_SESSION['prefsTimeZone'], time(), $_SESSION['prefsDateFormat'], $_SESSION['prefsTimeFormat'], "long", "date-time"); ?></td>
	</tr>
    <tr>
		<td class="dataLabel"><a href="<?php echo $base_url; ?>index.php?section=admin&amp;go=entries">Entries</a> (Confirmed/Unconfirmed):</td>
        <td class="data"><?php echo $totalRows_log_confirmed."/".$entries_unconfirmed; ?></td>
        <?php if (!NHC) { ?>
		<td class="dataLabel">Total Fees:</td>
        <td class="data"><?php echo $_SESSION['prefsCurrency'].$total_fees; ?></td>
        <?php } else { ?>
        <td class="dataLabel">&nbsp;</td>
        <td class="data">&nbsp;</td>
        <?php } ?>
        <td class="dataLabel"><a href="<?php echo $base_url; ?>index.php?section=admin&amp;go=participants">Total Participants</a>:</td>
        <td class="data"><?php echo get_participant_count('default'); ?></td>
	</tr>
    <tr>
      <td class="dataLabel">Participants with Entries:</td>
      <td class="data"><?php echo $row_with_entries['count']; ?></td>
      <?php if (!NHC) { ?>
		<td class="dataLabel">Total Paid Fees:</td>
        <td class="data"><?php echo $_SESSION['prefsCurrency'].$total_fees_paid; ?></td>
        <?php } else { ?>
        <td class="dataLabel">&nbsp;</td>
        <td class="data">&nbsp;</td>
        <?php } ?>
      <td class="dataLabel"><a href="<?php echo $base_url; ?>index.php?section=admin&amp;go=participants&amp;filter=judges">Available Judges</a>:</td>
      <td class="data"><?php echo get_participant_count('judge'); ?></td>
    </tr>
    <tr>
    	<td class="dataLabel"><a href="<?php echo $base_url; ?>index.php?section=admin&amp;go=entries&amp;view=paid">Paid, Rec'd &amp; Confirmed Entries</a>:</td>
        <td class="data"><?php echo get_entry_count("paid-received"); ?></td>
        <td class="dataLabel" colspan="2"><a href="<?php echo $base_url; ?>index.php?section=admin&amp;go=count_by_style">Entry Counts by Style</a></td>
        <td class="dataLabel"><a href="<?php echo $base_url; ?>index.php?section=admin&amp;go=participants&amp;filter=stewards">Available Stewards</a>:</td>
        <td class="data"><?php echo get_participant_count('steward'); ?></td>
	</tr>
    <?php if (($_SESSION['contestEntryFeePassword'] != "") && ($_SESSION['contestEntryFeePasswordNum'] != "")) { ?>
    <tr>
        <td class="dataLabel">Participants Who Redeemed Discount:</td>
        <td class="data"><?php $a = explode("^",total_discount()); echo $a[0]; ?></td>
        <td class="dataLabel">Total Discounted Entries:</td>
        <td class="data"><?php echo $a[1]; ?></td>
        <td class="dataLabel">Total Fees at Discount:</td>
        <td class="data"><?php echo $_SESSION['prefsCurrency'].($a[1] * $_SESSION['contestEntryFeePasswordNum']); ?></td>
    </tr>
    <?php } ?>
</table>
</div>
<?php } if ($_SESSION['userLevel'] <= "1") {
			if (($totalRows_dropoff == "0") && ($go == "default")) echo "<div class='error' style='margin-top: 15px;'>No drop-off locations have been specified. <a href=\"index.php?section=admin&amp;action=add&amp;go=dropoff\">Add a drop-off location</a>?</div>";
			if (($totalRows_judging == "0") && ($go == "default")) echo "<div class='error'style='margin-top: 15px;'>No judging dates/locations have been specified. <a href=\"index.php?section=admin&amp;action=add&amp;go=judging\">Add a judging location</a>?</div>";
			if (($totalRows_contact == "0") && ($go == "default")) echo "<div class='error'style='margin-top: 15px;'>No competition contacts have been specified. <a href=\"index.php?section=admin&amp;action=add&amp;go=contacts\">Add a competition contact</a>?</div>";
			
if ($go == "default") { ?>
<div id="menu_container">
<div id="outer">
<p>Click the headings below to expand and collapse each category.</p>
	<div class="menus">
		<h4 class="trigger"><span class="icon"><img src="<?php echo $base_url; ?>images/help.png"  /></span>Help</h4>
        <div class="toggle_container">
        	<p class="admin_default_header">Quick Links</p>
            <?php if ($_SESSION['userLevel'] == "0") { ?>
        	<ul class="admin_default">
				<li><a href="http://help.brewcompetition.com/files/gone_through.html" title="Help" id="modal_window_link">I've Gone Through Set Up, What Do I Do Now?</a></li>
    			<li><a href="http://help.brewcompetition.com/files/comp_contacts.html" title="Help" id="modal_window_link">How Do I Add More Contacts?</a></li>
                <li><a href="http://help.brewcompetition.com/files/drop_off.html" title="Help" id="modal_window_link">How Do I Add More Drop Off Locations?</a></li>
    			<li><a href="http://help.brewcompetition.com/files/sponsors.html" title="Help" id="modal_window_link">How Do I Display Sponsors?</a></li>			
            </ul>
            <?php } ?>
            <ul class="admin_default">
    			<li><a href="http://help.brewcompetition.com/files/tables.html" title="Help" id="modal_window_link">How Do I Set Up Tables?</a></li>
                <?php if ($_SESSION['jPrefsQueued'] == "N") { ?>
                <li><a href="http://help.brewcompetition.com/files/flights.html" title="Help" id="modal_window_link">How Do I Define Flights?</a></li>			
            	<?php } ?>
                <li><a href="http://help.brewcompetition.com/files/judges.html" title="Help" id="modal_window_link">How Do I Assign Judges/Stewards to Tables?</a></li>
                <li><a href="http://help.brewcompetition.com/files/scoring.html" title="Help" id="modal_window_link">How Do I Enter Scores?</a></li>
    		</ul>
            <?php if ($_SESSION['userLevel'] == "0") { ?>
            <ul class="admin_default">
            	<li><a href="http://help.brewcompetition.com/files/archiving.html" title="Help" id="modal_window_link">What are Archives?</a></li>	
				<li><a href="http://help.brewcompetition.com/files/style_types.html" title="Help" id="modal_window_link">What Are Style Types?</a></li>
            </ul>
            <?php } ?>
            <p class="admin_default_header">More Help</p>
            <ul class="admin_default">
				<li><a href="http://help.brewcompetition.com" title="Help" target="_blank">All Help Topics</a></li>
    		</ul>
        </div>
        <?php if (($_SESSION['prefsUseMods'] == "Y") && ($_SESSION['userLevel'] == "0")) { ?>
        <h4 class="trigger"><span class="icon"><img src="<?php echo $base_url; ?>images/brick.png"  /></span>Custom Modules</h4>
		<div class="toggle_container">
        	<p class="admin_default_header">Manage/View</p>
			<ul class="admin_default">
            	<li><a href="<?php echo $base_url; ?>index.php?section=admin&amp;go=mods">Custom Modules</a></li>
            </ul>
            <p class="admin_default_header">Add</p>
			<ul class="admin_default">
            	<li><a href="<?php echo $base_url; ?>index.php?section=admin&amp;go=mods&amp;action=add">A Custom Module</a></li>
            </ul>
        </div>
        <?php } ?>
        <?php if ($_SESSION['userLevel'] == "0") { ?>
		<h4 class="trigger"><span class="icon"><img src="<?php echo $base_url; ?>images/cog.png"  /></span>Defining Preferences</h4>
		<div class="toggle_container">
			<p class="admin_default_header">Define</p>
			<ul class="admin_default">
				<li><a href="<?php echo $base_url; ?>index.php?section=admin&amp;go=preferences">Site Preferences</a></li>
    			<li><a href="<?php echo $base_url; ?>index.php?section=admin&amp;go=judging_preferences">Competition Organization Preferences</a></li>
			</ul>
		</div>
        <?php } ?>
		<h4 class="trigger"><span class="icon"><img src="<?php echo $base_url; ?>images/wrench.png"  /></span>Preparing</h4>
		<div class="toggle_container">
        	<p class="admin_default_header">Manage/View</p>
            <?php if ($_SESSION['userLevel'] == "0") { ?>
			<ul class="admin_default">
            	<li><a href="<?php echo $base_url; ?>index.php?section=admin&amp;go=style_types">Style Types</a></li>
			    <li><a href="<?php echo $base_url; ?>index.php?section=admin&amp;go=styles">Accepted Style Categories</a></li>   
			</ul>
            <ul class="admin_default">
            	<li><a href="<?php echo $base_url; ?>index.php?section=admin&amp;go=special_best">Custom Winner Categories</a></li>
			</ul>
            <?php } ?>
            <ul class="admin_default">
    			<li><a href="<?php echo $base_url; ?>index.php?section=admin&amp;go=judging">Judging Locations</a></li>
    			<li><a href="<?php echo $base_url; ?>index.php?section=admin&amp;go=dropoff">Drop-Off Locations</a></li>
                <li><a href="<?php echo $base_url; ?>index.php?section=admin&amp;go=contacts">Competition Contacts</a></li>
    			<?php if ($_SESSION['userLevel'] == "0") { ?>
    			<li><a href="<?php echo $base_url; ?>index.php?section=admin&amp;go=sponsors">Sponsors</a></li>
                <?php } ?>
			</ul>
            <p class="admin_default_header">Add</p>
            <?php if ($_SESSION['userLevel'] == "0") { ?>
			<ul class="admin_default">
   			 	<li><a href="<?php echo $base_url; ?>index.php?section=admin&amp;go=style_types&amp;action=add">A Style Type</a></li>
                <li><a href="<?php echo $base_url; ?>index.php?section=admin&amp;go=styles&amp;action=add">A Custom Style Category</a></li>
			</ul>
            <ul class="admin_default">
            	<li><a href="<?php echo $base_url; ?>index.php?section=admin&amp;go=special_best&amp;action=add">A Custom Winning Category</a></li>
			</ul>
            <?php } ?>
			<ul class="admin_default">
			    <li><a href="<?php echo $base_url; ?>index.php?section=admin&amp;go=judging&amp;action=add">A Judging Location</a></li>
			    <li><a href="<?php echo $base_url; ?>index.php?section=admin&amp;go=dropoff&amp;action=add">A Drop-Off Location</a></li>
                <li><a href="<?php echo $base_url; ?>index.php?section=admin&amp;go=contacts&amp;action=add">A Competition Contact</a></li>
                <?php if ($_SESSION['userLevel'] == "0") { ?>
			    <li><a href="<?php echo $base_url; ?>index.php?section=admin&amp;go=sponsors&amp;action=add">A Sponsor</a></li>
                <?php } ?>
			</ul>
            <?php if ($_SESSION['userLevel'] == "0") { ?>
			<p class="admin_default_header">Edit</p>
			<ul class="admin_default">
				<li><a href="<?php echo $base_url; ?>index.php?section=admin&amp;go=contest_info">Competition Info</a></li>
			</ul>
			<p class="admin_default_header">Upload</p>
			<ul class="admin_default">
				<li><a href="admin/upload.admin.php" title="Upload Competition Logo Image" id="modal_window_link">A Competition Logo</a></li>
			    <li><a href="admin/upload.admin.php" title="Upload Sponsor Logo Image" id="modal_window_link">Sponsor Logos</a></li>
			</ul>
            <?php } ?>
		</div>
		<h4 class="trigger"><span class="icon"><img src="<?php echo $base_url; ?>images/note.png"  /></span>Entry and Data Gathering</h4>
		<div class="toggle_container">
        	<?php if ($_SESSION['userLevel'] == "0") { ?>
			<p class="admin_default_header">Manage/View</p>
			<ul class="admin_default">
			    <li><a href="<?php echo $base_url; ?>index.php?section=admin&amp;go=style_types">Style Types</a></li>
                <li><a href="<?php echo $base_url; ?>index.php?section=admin&amp;go=styles">Accepted Style Categories</a></li>
			</ul>
            <?php } ?>
            <ul class="admin_default">
				<li><a href="<?php echo $base_url; ?>index.php?section=admin&amp;go=participants">Participants</a></li>
			    <li><a href="<?php echo $base_url; ?>index.php?section=admin&amp;go=entries">Entries</a></li>
            </ul>
			<ul class="admin_default">
    			<li><a href="<?php echo $base_url; ?>index.php?section=admin&amp;go=judging">Judging Locations</a></li>
    			<li><a href="<?php echo $base_url; ?>index.php?section=admin&amp;go=participants&amp;filter=judges">Available Judges</a></li>
    			<li><a href="<?php echo $base_url; ?>index.php?section=admin&amp;go=participants&amp;filter=stewards">Available Stewards</a></li>
            </ul>
            <ul class="admin_default">
    			<li><a href="<?php echo $base_url; ?>index.php?section=admin&amp;go=contacts">Competition Contacts</a></li>
			</ul>
			<ul class="admin_default">
    			<li><a href="<?php echo $base_url; ?>index.php?section=admin&amp;go=dropoff">Drop-Off Locations</a></li>
                <?php if ($_SESSION['userLevel'] == "0") { ?>
    			<li><a href="<?php echo $base_url; ?>index.php?section=admin&amp;go=sponsors">Sponsors</a></li>
                <?php } ?>
			</ul>
			<p class="admin_default_header">Add</p>
            <?php if ($_SESSION['userLevel'] == "0") { ?>
			<ul class="admin_default">
  				<li><a href="<?php echo $base_url; ?>index.php?section=admin&amp;go=style_types&amp;action=add">A Style Type</a></li>
            	<li><a href="<?php echo $base_url; ?>index.php?section=admin&amp;go=styles&amp;action=add">A Custom Style Category</a></li>
			</ul>
            <?php } ?>
            <ul class="admin_default">
    			<li><a href="<?php echo $base_url; ?>index.php?section=admin&amp;go=entrant&amp;action=register">A Participant</a></li>
    			<li><a href="<?php echo $base_url; ?>index.php?section=admin&amp;go=judge&amp;action=register">A Participant as a Judge/Steward</a></li></li>
   			 	<li>An Entry For: <?php echo participant_choose($brewer_db_table); ?></li>
			</ul>
            
            <ul class="admin_default">
		      <li><a href="<?php echo $base_url; ?>index.php?section=admin&amp;go=judging&amp;action=add">A Judging Location</a></li>
            </ul>
            <ul class="admin_default">
            	<li><a href="<?php echo $base_url; ?>index.php?section=admin&amp;go=contacts&amp;action=add">A Competition Contact</a></li>
            </ul>
            <ul class="admin_default">
			    <li><a href="<?php echo $base_url; ?>index.php?section=admin&amp;go=dropoff&amp;action=add">A Drop-Off Location</a></li>
                <?php if ($_SESSION['userLevel'] == "0") { ?>
			    <li><a href="<?php echo $base_url; ?>index.php?section=admin&amp;go=sponsors&amp;action=add">A Sponsor</a></li>
                <?php } ?>
			</ul>
			<p class="admin_default_header">Assign</p>
			<ul class="admin_default">
				<li><a href="<?php echo $base_url; ?>index.php?section=admin&amp;action=assign&amp;go=judging&amp;filter=judges">Participants as Judges</a></li>
				<li><a href="<?php echo $base_url; ?>index.php?section=admin&amp;action=assign&amp;go=judging&amp;filter=stewards">Participants as Stewards</a></li>
			    <?php if ($_SESSION['userLevel'] == "0") { ?>
                <li><a href="<?php echo $base_url; ?>index.php?section=admin&amp;action=assign&amp;go=judging&amp;filter=staff">Participants as Staff</a></li>
                <?php } ?>
			</ul>
		</div>
		<h4 class="trigger"><span class="icon"><img src="<?php echo $base_url; ?>images/arrow_refresh.png"  /></span>Sorting Received Entries</h4>
		<div class="toggle_container">
			<p class="admin_default_header">Manage/View</p>
			<ul class="admin_default">
    			<li><a href="<?php echo $base_url; ?>index.php?section=admin&amp;go=participants">Participants</a></li>
    			<li><a href="<?php echo $base_url; ?>index.php?section=admin&amp;go=entries">Entries</a></li>
			</ul>
            <!-- SIGNIFICANT performance issues if enabled
			<ul class="admin_default">
			    <li>Mark Entries as Paid/Received for Category:</li>
			    <li><?php //echo style_choose($section,"entries",$action,$filter,$view,"index.php","none"); ?></li>
			</ul>
            -->
			<p class="admin_default_header">Add</p>
			<ul class="admin_default">
			    <li><a href="<?php echo $base_url; ?>index.php?section=admin&amp;go=entrant&amp;action=regiser">A Participant</a></li>
			    <li><a href="<?php echo $base_url; ?>index.php?section=admin&amp;go=judge&amp;action=register">A Participant as a Judge/Steward</a></li>
			    <li>An Entry For: <?php echo participant_choose($brewer_db_table); ?></li>
			</ul>
          	<?php if (!NHC) { ?>
        	<p class="admin_default_header">Regenerate</p>
			<ul class="admin_default">
            	<li>Entry Judging Numbers:</li>
                <li>
                <div class="menuBar">
                <a class="menuButton" href="#" onclick="#" onmouseover="buttonMouseover(event, 'menu_generate');">Key Off Of... (Select One)</a>
                <div id="menu_generate" class="menu" onmouseover="menuMouseover(event)">
                <a class="menuItem" onclick="return confirm('Are you sure you want to regenerate judging numbers for all entries?<?php if ($_SESSION['prefsEntryForm'] == "N") echo " THIS WILL OVER-WRITE *ALL* JUDGING NUMBERS, including those that have been assigned via the barcode scanning function."; ?>');" href="<?php echo $base_url; ?>includes/process.inc.php?section=admin&amp;go=entries&amp;action=generate_judging_numbers&amp;sort=id&amp;dir=ASC">Entry Number (Ascending)</a>
                <a class="menuItem" onclick="return confirm('Are you sure you want to regenerate judging numbers for all entries?<?php if ($_SESSION['prefsEntryForm'] == "N") echo " THIS WILL OVER-WRITE *ALL* JUDGING NUMBERS, including those that have been assigned via the barcode scanning function."; ?>');" href="<?php echo $base_url; ?>includes/process.inc.php?section=admin&amp;go=entries&amp;action=generate_judging_numbers&amp;sort=id&amp;dir=DESC">Entry Number (Descending)</a>
                <a class="menuItem" onclick="return confirm('Are you sure you want to regenerate judging numbers for all entries?<?php if ($_SESSION['prefsEntryForm'] == "N") echo " THIS WILL OVER-WRITE *ALL* JUDGING NUMBERS, including those that have been assigned via the barcode scanning function."; ?>');" href="<?php echo $base_url; ?>includes/process.inc.php?section=admin&amp;go=entries&amp;action=generate_judging_numbers&amp;sort=brewName&amp;dir=ASC">Entry Name (Ascending)</a>
                <a class="menuItem" onclick="return confirm('Are you sure you want to regenerate judging numbers for all entries?<?php if ($_SESSION['prefsEntryForm'] == "N") echo " THIS WILL OVER-WRITE *ALL* JUDGING NUMBERS, including those that have been assigned via the barcode scanning function."; ?>');" href="<?php echo $base_url; ?>includes/process.inc.php?section=admin&amp;go=entries&amp;action=generate_judging_numbers&amp;sort=brewName&amp;dir=DESC">Entry Name (Descending)</a>
                </div>
                </div>
                </li>
			</ul>
        	<?php } ?>
            <?php if ($_SESSION['prefsEntryForm'] == "N") { ?>
            <p class="admin_default_header">Check-In</p>
            <ul class="admin_default">
            	<li><a href="<?php echo $base_url; ?>index.php?section=admin&amp;go=checkin">Check-In Entries with a Barcode Reader/Scanner</a></li>
                <li>This function is intended to be used with the Judging Number Barcode Labels and the Judging Number Round Labels <a href="http://www.brewcompetition.com/bottle-labels" target="_blank">available for download at brewcompetition.com</a>. Also available are <a href="http://www.brewcompetition.com/downloads/entry_check-in.pdf" target="_blank">suggested usage instructions</a>.</li>
                </ul>
            <?php } ?>
			<p class="admin_default_header">Print</p>
            <?php if (!NHC) { ?>
            <ul class="admin_default">
            	<li>Sorting Sheets:</li>
                <li><a id="modal_window_link" href="<?php echo $base_url; ?>output/sorting.php?section=admin&amp;go=default&amp;filter=default">All Categories</a></li>
                <!-- SIGNIFICANT performance issues if enabled
                <li>For Category:</li>
				<li><?php //echo style_choose($section,"default",$action,$filter,$view,"output/sorting.php","thickbox"); ?></li>
                -->
				<li><a id="modal_window_link" href="<?php echo $base_url; ?>output/sorting.php?section=admin&amp;go=default&amp;filter=default&amp;view=entry">All Categories</a> (Entry Numbers Only)</li>
                <!--
                <li>For Category (Entry Numbers Only):</li>
				<li><?php //echo style_choose($section,"default",$action,$filter,"entry","output/sorting.php","thickbox"); ?></li>
                -->
            </ul>
            <ul class="admin_default">
            	<li>Entry Number / Judging Number Cheat Sheets:</li>
				<li><a id="modal_window_link" href="<?php echo $base_url; ?>output/sorting.php?section=admin&amp;go=cheat&amp;filter=default">All Categories</a></li>
                <!-- SIGNIFICANT performance issues if enabled
                <li>For Category:</li>
				<li><?php //echo style_choose($section,"cheat",$action,$filter,$view,"output/sorting.php","thickbox"); ?></li>
                -->
            </ul>
			<ul class="admin_default">
				<li>Bottle Labels (Using <em>Entry</em> Numbers - Avery 5160):</li>
			    <li><a href="<?php echo $base_url; ?>output/labels.php?section=admin&amp;go=entries&amp;action=bottle-entry&amp;filter=default">All Categories</a></li>
                <!-- SIGNIFICANT performance issues if enabled
                <li>For Category:</li>
				<li><?php // echo style_choose($section,"entries","bottle-entry",$filter,$view,"output/labels.php","none"); ?></li>
                -->
			</ul>
            
            <ul class="admin_default">
				<li>Bottle Labels (Using <em>Judging</em> Numbers - Avery 5160):</li>
			    <li><a href="<?php echo $base_url; ?>output/labels.php?section=admin&amp;go=entries&amp;action=bottle-judging&amp;filter=default">All Categories</a></li>
                <!-- SIGNIFICANT performance issues if enabled
                <li>For Category:</li>
				<li><?php //echo style_choose($section,"entries","bottle-judging",$filter,$view,"output/labels.php","none"); ?></li>
                -->
			</ul>
            <?php } ?>
            <?php if (!NHC) { ?>
            <ul class="admin_default">
				<li>Bottle Labels with Special Ingredients, Mead/Cider Info (Using <em>Entry</em> Numbers - Avery 5160)</li>
			    <li>All Categories:
                <select name="special_labels" id="special_labels" onchange="jumpMenu('self',this,0)">
                	<option value=""></option>
                    <?php for($i=1; $i<=6; $i++) { ?>
                    <option value="<?php echo $base_url; ?>output/labels.php?section=admin&amp;go=entries&amp;action=bottle-entry&amp;filter=default&amp;view=special&amp;sort=<?php echo $i; ?>"><?php echo $i; ?></option>
                    <?php } ?>
                </select> label(s) per entry
                </li>
                <!-- SIGNIFICANT performance issues if enabled
                <li>For Category:</li>
				<li><?php //echo style_choose($section,"entries","bottle-judging",$filter,"special","output/labels.php","none"); ?></li>
                -->
			</ul>
            <?php } ?>
            <ul class="admin_default">
				<li>Bottle Labels with Special Ingredients, Mead/Cider Info (Using <em>Judging</em> Numbers - Avery 5160)</li>
			    <li>All Categories:
                <select name="special_labels" id="special_labels" onchange="jumpMenu('self',this,0)">
                	<option value=""></option>
                    <?php for($i=1; $i<=6; $i++) { ?>
                    <option value="<?php echo $base_url; ?>output/labels.php?section=admin&amp;go=entries&amp;action=bottle-judging&amp;filter=default&amp;view=special&amp;sort=<?php echo $i; ?>"><?php echo $i; ?></option>
                    <?php } ?>
                </select> label(s) per entry
                </li>
                <!-- SIGNIFICANT performance issues if enabled
                <li>For Category:</li>
				<li><?php //echo style_choose($section,"entries","bottle-judging",$filter,"special","output/labels.php","none"); ?></li>
                -->
			</ul>
            <?php if (!NHC) { ?>
            <ul class="admin_default">
            	<li>Round Bottle Labels (Using <em>Entry</em> Numbers)</li>
            </ul>
            <ul class="admin_default">
                <li><a href="http://www.onlinelabels.com/Products/OL32.htm" target="_blank">OnlineLabels.com 0.50 Inch Labels</a>, All Categories: <select name="round_entry" id="round_entry" onchange="jumpMenu('self',this,0)">
                	<option value=""></option>
                    <?php for($i=1; $i<=12; $i++) { ?>
                    <option value="<?php echo $base_url; ?>output/labels.php?section=admin&amp;go=entries&amp;action=bottle-entry-round&amp;filter=default&amp;sort=<?php echo $i; ?>&amp;psort=OL32"><?php echo $i; ?></option>
                    <?php } ?>                    
                </select> label(s) per entry</li>
                <li>All Added by Admins (After Reg. Close): <select name="round_entry_recent" id="round_entry_recent" onchange="jumpMenu('self',this,0)">
                	<option value=""></option>
                    <?php for($i=1; $i<=12; $i++) { ?>
                    <option value="<?php echo $base_url; ?>output/labels.php?section=admin&amp;go=entries&amp;action=bottle-entry-round&amp;filter=recent&amp;sort=<?php echo $i; ?>&amp;psort=OL32"><?php echo $i; ?></option>
                    <?php } ?>
                </select> label(s) per entry</li>
            </ul>
            <ul class="admin_default">
                <li><a href="http://www.onlinelabels.com/Products/OL5275WR.htm" target="_blank">OnlineLabels.com 0.75 Inch Labels</a>, All Categories: <select name="round_entry" id="round_entry" onchange="jumpMenu('self',this,0)">
                	<option value=""></option>
                    <?php for($i=1; $i<=12; $i++) { ?>
                    <option value="<?php echo $base_url; ?>output/labels.php?section=admin&amp;go=entries&amp;action=bottle-entry-round&amp;filter=default&amp;sort=<?php echo $i; ?>&amp;psort=OL5275WR"><?php echo $i; ?></option>
                    <?php } ?>
                </select> label(s) per entry</li>
                <li>All Added by Admins (After Reg. Close): <select name="round_entry_recent" id="round_entry_recent" onchange="jumpMenu('self',this,0)">
                	<option value=""></option>                    
                    <?php for($i=1; $i<=12; $i++) { ?>
                    <option value="<?php echo $base_url; ?>output/labels.php?section=admin&amp;go=entries&amp;action=bottle-entry-round&amp;filter=recent&amp;sort=<?php echo $i; ?>&amp;psort=OL5275WR"><?php echo $i; ?></option>
                    <?php } ?>
                </select> label(s) per entry</li>
            </ul>
            <?php } ?>
            <ul class="admin_default">
            	<li>Round Bottle Labels (Using <em>Judging</em> Numbers)</li>
            </ul>
            <ul class="admin_default">
                <li><a href="http://www.onlinelabels.com/Products/OL32.htm" target="_blank">OnlineLabels.com 0.50 Inch Labels</a>, All Categories: <select name="round_judging" id="round_judging" onchange="jumpMenu('self',this,0)">
                	<option value=""></option>
                    <?php for($i=1; $i<=12; $i++) { ?>
                    <option value="<?php echo $base_url; ?>output/labels.php?section=admin&amp;go=entries&amp;action=bottle-judging-round&amp;filter=default&amp;sort=<?php echo $i; ?>&amp;psort=OL32"><?php echo $i; ?></option>
                    <?php } ?>                    
                </select> label(s) per entry</li>
                <li>All Added by Admins (After Reg. Close): <select name="round_judging_recent" id="round_judging_recent" onchange="jumpMenu('self',this,0)">
                	<option value=""></option>
                    <?php for($i=1; $i<=12; $i++) { ?>
                    <option value="<?php echo $base_url; ?>output/labels.php?section=admin&amp;go=entries&amp;action=bottle-judging-round&amp;filter=recent&amp;sort=<?php echo $i; ?>&amp;psort=OL32"><?php echo $i; ?></option>
                    <?php } ?>
                </select> label(s) per entry</li>
            </ul>
            <ul class="admin_default">
                <li><a href="http://www.onlinelabels.com/Products/OL5275WR.htm" target="_blank">OnlineLabels.com 0.75 Inch Labels</a>, All Categories: <select name="round_judging" id="round_judging" onchange="jumpMenu('self',this,0)">
                	<option value=""></option>
                    <?php for($i=1; $i<=12; $i++) { ?>
                    <option value="output/labels.php?section=admin&amp;go=entries&amp;action=bottle-judging-round&amp;filter=default&amp;sort=<?php echo $i; ?>&amp;psort=OL5275WR"><?php echo $i; ?></option>
                    <?php } ?>
                </select> label(s) per entry</li>
                <li>All Added by Admins (After Reg. Close): <select name="round_judging_recent" id="round_judging_recent" onchange="jumpMenu('self',this,0)">
                	<option value=""></option>                    
                    <?php for($i=1; $i<=12; $i++) { ?>
                    <option value="<?php echo $base_url; ?>output/labels.php?section=admin&amp;go=entries&amp;action=bottle-judging-round&amp;filter=recent&amp;sort=<?php echo $i; ?>&amp;psort=OL5275WR"><?php echo $i; ?></option>
                    <?php } ?>
                </select> label(s) per entry</li>
            </ul>
            
             <ul class="admin_default">
            	<li>Round Bottle Labels (Category Number and Subcategory Letter Only)</li>
             </ul>
            <ul class="admin_default">
                <li><a href="http://www.onlinelabels.com/Products/OL32.htm" target="_blank">OnlineLabels.com 0.50 Inch Labels</a>, All Categories: <select name="round_judging" id="round_judging" onchange="jumpMenu('self',this,0)">
                	<option value=""></option>
                    <?php for($i=1; $i<=12; $i++) { ?>
                    <option value="<?php echo $base_url; ?>output/labels.php?section=admin&amp;go=entries&amp;action=bottle-category-round&amp;filter=default&amp;sort=<?php echo $i; ?>&amp;psort=OL32"><?php echo $i; ?></option>
                    <?php } ?>
                </select> label(s) per entry
             	</li>
                <!--
                <li>Category: <select name="round_judging" id="round_judging" onchange="jumpMenu('self',this,0)">
                	<option value=""></option>
                    <?php 
					/*
					$query_style_count = sprintf("SELECT brewStyleGroup FROM %s ORDER BY brewStyleGroup DESC LIMIT 1", $prefix."styles");
					$style_count = mysql_query($query_style_count, $brewing) or die(mysql_error());
					$row_style_count = mysql_fetch_assoc($style_count);

					for($i=1; $i<=$row_style_count['brewStyleGroup']; $i++) { 
					*/ ?>
                    <option value="output/labels.php?section=admin&amp;go=entries&amp;action=bottle-category-round&amp;filter=default&amp;sort=default&amp;view=<?php //echo $i; ?>"><?php // echo $i; ?></option>
                    <?php // } ?>
                </select></li>
                -->
            </ul>
            <ul class="admin_default">
             	<li><a href="http://www.onlinelabels.com/Products/OL5275WR.htm" target="_blank">OnlineLabels.com 0.75 Inch Labels</a>, All Categories: <select name="round_judging" id="round_judging" onchange="jumpMenu('self',this,0)">
                	<option value=""></option>
                    <?php for($i=1; $i<=12; $i++) { ?>
                    <option value="<?php echo $base_url; ?>output/labels.php?section=admin&amp;go=entries&amp;action=bottle-category-round&amp;filter=default&amp;sort=<?php echo $i; ?>&amp;psort=OL5275WR"><?php echo $i; ?></option>
                    <?php } ?>
                </select> label(s) per entry
             	</li>
            </ul>
		</div>
       
		<h4 class="trigger"><span class="icon"><img src="<?php echo $base_url; ?>images/book.png" alt="" /></span>Organizing</h4>
		<div class="toggle_container">
			<p class="admin_default_header">Manage/View</p>
			<ul class="admin_default">
			    <li><a href="<?php echo $base_url; ?>index.php?section=admin&amp;go=participants">Participants</a></li>
			    <li><a href="<?php echo $base_url; ?>index.php?section=admin&amp;go=entries">Entries</a></li>
			</ul>
	  		
			<ul class="admin_default">
  				<li><a href="<?php echo $base_url; ?>index.php?section=admin&amp;go=judging_tables">Tables</a></li>
                <?php if ($_SESSION['jPrefsQueued'] == "N") { ?>
   			 	<li><a href="<?php echo $base_url; ?>index.php?section=admin&amp;go=judging_flights">Flights</a></li>
                <?php } ?>
			</ul>
			<ul class="admin_default">
             	<?php if ($_SESSION['userLevel'] == "0") { ?>
  				<li><a href="<?php echo $base_url; ?>index.php?section=admin&amp;go=styles&amp;filter=orphans">Styles Without a Valid Style Type</a></li>
                <?php } ?>
  				<li><a href="<?php echo $base_url; ?>index.php?section=admin&amp;go=judging_tables&amp;filter=orphans">Styles Not Assigned to Tables</a></li>
			</ul>
			
			<ul class="admin_default">
    			<li><a href="<?php echo $base_url; ?>index.php?section=admin&amp;go=participants&amp;filter=judges">Assigned Judges</a></li>
    			<li><a href="<?php echo $base_url; ?>index.php?section=admin&amp;go=participants&amp;filter=stewards">Assigned Stewards</a></li>
			</ul>
			<p class="admin_default_header">Add</p>
			<ul class="admin_default">
    			<li><a href="<?php echo $base_url; ?>index.php?section=admin&amp;go=entrant&amp;action=register">A Participant</a></li>
    			<li><a href="<?php echo $base_url; ?>index.php?section=admin&amp;go=judge&amp;action=register">A Participant as a Judge/Steward</a></li>
    			<li>An Entry For: <?php echo participant_choose($brewer_db_table); ?></li>
			</ul>
  			
	  		<ul class="admin_default">
   			 	<li><a href="<?php echo $base_url; ?>index.php?section=admin&amp;go=judging_tables&amp;action=add">A Table</a></li>
                <?php if ($_SESSION['jPrefsQueued'] == "N") { ?>
    			<li><a href="<?php echo $base_url; ?>index.php?section=admin&amp;go=judging_flights">Flights to Tables</a></li>
                <?php } ?>
			</ul>
  			<p class="admin_default_header">Assign</p>
  			<ul class="admin_default">
				<li><a href="<?php echo $base_url; ?>index.php?section=admin&amp;action=assign&amp;go=judging&amp;filter=judges">Participants as Judges</a></li>
				<li><a href="<?php echo $base_url; ?>index.php?section=admin&amp;action=assign&amp;go=judging&amp;filter=stewards">Participants as Stewards</a></li>
			    <li><a href="<?php echo $base_url; ?>index.php?section=admin&amp;action=assign&amp;go=judging&amp;filter=staff">Participants as Staff</a></li>
			</ul>
  			<?php if ($totalRows_tables > 1) { ?>
			<ul class="admin_default">
            	<li><a href="<?php echo $base_url; ?>index.php?section=admin&amp;go=judging_flights&amp;action=assign&amp;filter=rounds"><?php echo $assign_to; ?> to Rounds</a></li>
            </ul>
            <ul class="admin_default">
				<li><a href="<?php echo $base_url; ?>index.php?section=admin&amp;go=judging_tables&amp;action=assign">Judges or Stewards to a Table</a></li>
            </ul>
            <?php if (((NHC) && ($prefix == "final_")) || (!NHC)) { ?>
            <ul class="admin_default">
				<li><a href="<?php echo $base_url; ?>index.php?section=admin&amp;action=assign&amp;go=judging&amp;filter=bos">Best of Show Judges</a></li>
			</ul>
  			<?php } } ?>
            </div>
            <h4 class="trigger"><span class="icon"><img src="<?php echo $base_url; ?>images/rosette.png"  /></span>Scoring</h4>
            <div class="toggle_container">
            <p class="admin_default_header">Manage/View</p>
			<ul class="admin_default">
				<li><a href="<?php echo $base_url; ?>index.php?section=admin&amp;go=participants">Participants</a></li>
				<li><a href="<?php echo $base_url; ?>index.php?section=admin&amp;go=entries">Entries</a></li>
				<li><a href="<?php echo $base_url; ?>index.php?section=admin&amp;go=judging_tables">Tables</a></li> 
			</ul>
			<ul class="admin_default">
  				<li><a href="<?php echo $base_url; ?>index.php?section=admin&amp;go=judging_scores">Scores by Table</a></li>
				<li><a href="<?php echo $base_url; ?>index.php?section=admin&amp;go=judging_scores&amp;filter=category">Scores by Category</a></li>
                <?php if (((NHC) && ($prefix == "_final")) || (!NHC)) { ?>
				<li><a href="<?php echo $base_url; ?>index.php?section=admin&amp;go=judging_scores_bos">BOS Entries and Places</a></li>
                <?php } ?>
			</ul>
            <?php if ($_SESSION['userLevel'] == "0") { ?>
            <ul class="admin_default">
  				<li><a href="<?php echo $base_url; ?>index.php?section=admin&amp;go=special_best">Custom Winning Categories</a></li>
				<li><a href="<?php echo $base_url; ?>index.php?section=admin&amp;go=special_best_data">Custom Winning Category Entries</a></li>
			</ul>
            <?php } ?>
			<p class="admin_default_header">Add</p>
			<ul class="admin_default">
				<li>Scores For:</li>
				<li><?php echo score_table_choose($dbTable,$judging_tables_db_table,$judging_scores_db_table); ?></li>
            </ul>
            <?php if ($_SESSION['userLevel'] == "0") { ?>
            <ul class="admin_default">
				<li>Winners for Custom Winning Category:</li>
				<li><?php echo score_custom_winning_choose($special_best_info_db_table,$special_best_data_db_table); ?></li>
            </ul>
            <?php } ?>
            </div>
            <h4 class="trigger"><span class="icon"><img src="<?php echo $base_url; ?>images/printer.png"  /></span>Printing and Reporting</h4>
            <div class="toggle_container">
			<p class="admin_default_header">Before Judging</p>
            <ul class="admin_default">
				<li>Print Drop Off Location Reports:</li>
                <li><a id="modal_window_link" href="<?php echo $base_url; ?>output/dropoff.php"  title="Print Total Entries by Location">Entry Totals by Location</a></li>
                <li><a id="modal_window_link" href="<?php echo $base_url; ?>output/dropoff.php?section=check"  title="Print Entries By Drop-Off Location">Entries By Drop-Off Location</a></li>
            </ul>
			<?php if ($totalRows_tables > 0) { ?>
            <?php if (!NHC) { ?>
            <ul class="admin_default">
				<li>Print Pullsheets (Using <em>Entry</em> Numbers):</li>
				<li><a id="modal_window_link" href="<?php echo $base_url; ?>output/pullsheets.php?section=admin&amp;go=judging_tables&amp;view=entry&amp;id=default" title="Print All Table Pullsheets">All Tables</a></li>
				<li><?php echo table_choose($section,"judging_tables",$action,$filter,"entry","output/pullsheets.php","thickbox"); ?></li>
                <li>
                    <div class="menuBar"><a class="menuButton" href="#" onclick="#" onmouseover="buttonMouseover(event, 'pullsheetsloc');">For Location...</a></div>
                    <div id="pullsheetsloc" class="menu" onmouseover="menuMouseover(event)">
                     <?php do { for ($round=1; $round <= $row_judging['judgingRounds']; $round++) { 
					 $location_date = getTimeZoneDateTime($_SESSION['prefsTimeZone'], $row_judging['judgingDate'], $_SESSION['prefsDateFormat'], $_SESSION['prefsTimeFormat'], "long", "date-time-no-gmt");
					 
					 ?>
                    <a id="modal_window_link" class="menuItem" style="font-size: .9em; padding: 1px;" href="<?php echo $base_url; ?>output/pullsheets.php?section=admin&amp;go=judging_locations&amp;view=entry&amp;location=<?php echo $row_judging['id']?>&amp;round=<?php echo $round; ?>" title="Print Pullsheet for Location <?php echo $row_judging['judgingLocName'] . " - " . $location_date. ", Round " . $round; ?>"><?php echo $row_judging['judgingLocName'] . " - " . $location_date. ", Round " . $round; ?></a>
                    <?php }} while ($row_judging = mysql_fetch_assoc($judging)); ?>
                    </div>
                </li>
			</ul>
            <?php } ?>
			<ul class="admin_default">
				<li>Print Pullsheets (Using <em>Judging</em> Numbers):</li>
				<li><a id="modal_window_link" href="<?php echo $base_url; ?>output/pullsheets.php?section=admin&amp;go=judging_tables&amp;id=default" title="Print All Table Pullsheets">All Tables</a></li>
				<li><?php echo table_choose($section,"judging_tables",$action,$filter,$view,"output/pullsheets.php","thickbox"); ?></li>
                <li>
                    <div class="menuBar"><a class="menuButton" href="#" onclick="#" onmouseover="buttonMouseover(event, 'pullsheetsloc_judging');">For Location...</a></div>
                    <div id="pullsheetsloc_judging" class="menu" onmouseover="menuMouseover(event)">
                     <?php do { for ($round=1; $round <= $row_judging1['judgingRounds']; $round++) { 
					 $location_date = getTimeZoneDateTime($_SESSION['prefsTimeZone'], $row_judging1['judgingDate'], $_SESSION['prefsDateFormat'], $_SESSION['prefsTimeFormat'], "long", "date-time-no-gmt");
					 
					 ?>
                    <a id="modal_window_link" class="menuItem" style="font-size: .9em; padding: 1px;" href="<?php echo $base_url; ?>output/pullsheets.php?section=admin&amp;go=judging_locations&amp;location=<?php echo $row_judging1['id']?>&amp;round=<?php echo $round; ?>" title="Print Pullsheet for Location <?php echo $row_judging1['judgingLocName'] . " - " . $location_date. ", Round " . $round; ?>"><?php echo $row_judging1['judgingLocName'] . " - " . $location_date. ", Round " . $round; ?></a>
                    <?php }} while ($row_judging1 = mysql_fetch_assoc($judging1)); ?>
                    </div>
                </li>
			</ul>
            <ul class="admin_default">
				<li>Print Table Cards:</li>
				<li><a id="modal_window_link" href="<?php echo $base_url; ?>output/table_cards.php?section=admin&amp;go=judging_tables&amp;id=default" title="Print Table Cards">All Tables</a></li>
                <li><?php echo table_choose($section,"judging_tables",$action,$filter,$view,"output/table_cards.php","thickbox"); ?></li>
                <li>
                <div class="menuBar"><a class="menuButton" href="#" onclick="#" onmouseover="buttonMouseover(event, 'table_cardsloc');">For Location...</a></div>
                    <div id="table_cardsloc" class="menu" onmouseover="menuMouseover(event)">
                        <?php  do { for ($round=1; $round <= $row_judging2['judgingRounds']; $round++) { 
						$location_date = getTimeZoneDateTime($_SESSION['prefsTimeZone'], $row_judging2['judgingDate'], $_SESSION['prefsDateFormat'], $_SESSION['prefsTimeFormat'], "long", "date-time-no-gmt");
						?>
                        <a id="modal_window_link" class="menuItem" style="font-size: .9em; padding: 1px;" href="<?php echo $base_url; ?>output/table_cards.php?section=admin&amp;go=judging_locations&amp;location=<?php echo $row_judging2['id']?>&amp;round=<?php echo $round; ?>" title="Print Table Cards for <?php echo $row_judging2['judgingLocName']. " - " . $location_date . ", Round " . $round; ?>"><?php echo $row_judging2['judgingLocName']. " - " . $location_date . ", Round " . $round; ?></a>
                        <?php }} while ($row_judging2 = mysql_fetch_assoc($judging2));   ?>
                    </div>
    				</li>
			</ul>
			<ul class="admin_default">
				<li>Print Judge Assignments:</li>
				<li><a id="modal_window_link" href="<?php echo $base_url; ?>output/assignments.php?section=admin&amp;go=judging_assignments&amp;filter=judges&amp;view=name" title="Print Judge Assignments by Name">By Judge Last Name</a></li>
				<li><a id="modal_window_link" href="<?php echo $base_url; ?>output/assignments.php?section=admin&amp;go=judging_assignments&amp;filter=judges&amp;view=table" title="Print Judge Assignments by Table">By Table</a></li>
   				<?php if ($totalRows_judging > 1) { ?>
				<li><a id="modal_window_link" href="<?php echo $base_url; ?>output/assignments.php?section=admin&amp;go=judging_assignments&amp;filter=judges&amp;view=location" title="Print Judge Assignments by Location">By Location</a></li>
    			<?php } ?>
			</ul>
			<ul class="admin_default">
				<li>Print Steward Assignments:</li>
				<li><a id="modal_window_link" href="<?php echo $base_url; ?>output/assignments.php?section=admin&amp;go=judging_assignments&amp;filter=stewards&amp;view=name" title="Print Steward Assignments by Name">By Steward Last Name</a></li>
				<li><a id="modal_window_link" href="<?php echo $base_url; ?>output/assignments.php?section=admin&amp;go=judging_assignments&amp;filter=stewards&amp;view=table" title="Print Steward Assignments by Table">By Table</a></li>
    			<?php if ($totalRows_judging > 1) { ?>
				<li><a id="modal_window_link" href="<?php echo $base_url; ?>output/assignments.php?section=admin&amp;go=judging_assignments&amp;filter=stewards&amp;view=location" title="Print Steward Assignments by Location">By Location</a></li>
				<?php } ?>
			</ul>
            <?php } ?>
			<ul class="admin_default">
				<li>Print Sign-in Sheets:</li>
				<li><a id="modal_window_link" href="<?php echo $base_url; ?>output/assignments.php?section=admin&amp;go=judging_assignments&amp;filter=judges&amp;view=sign-in" title="Print a Judge Sign-in Sheet">Judges</a></li>   
				<li><a id="modal_window_link" href="<?php echo $base_url; ?>output/assignments.php?section=admin&amp;go=judging_assignments&amp;filter=stewards&amp;view=sign-in" title="Print a Steward Sign-in Sheet">Stewards</a></li>   
			</ul>
            <ul class="admin_default">
				<li>Judge Scoresheet Labels (Avery 5160):</li>
            	<li><a href="<?php echo $base_url; ?>output/labels.php?section=admin&go=participants&action=judging_labels">Download PDF</a> (All Assigned Judges, 30 Labels per Judge)</li>
            </ul>
            
            <ul class="admin_default">
				<li>Judge, Steward and Staff Name Tags (Avery 5395):</li>
            	<li><a href="<?php echo $base_url; ?>output/labels.php?section=admin&go=participants&action=judging_nametags">Download PDF</a></li>
            </ul>
            
			
<p class="admin_default_header">During Judging</p>
			<?php if (((NHC) && ($prefix == "final_")) || (!NHC)) { ?>
            <ul class="admin_default">
				<li>Print BOS Pullsheets:
    			<ul>
        			<li><a id="modal_window_link" href="<?php echo $base_url; ?>output/pullsheets.php?section=admin&amp;go=judging_scores_bos" title="Print All BOS Pullsheets">All</a></li>
    	  			<?php do { ?>
          			<?php if ($row_style_type['styleTypeBOS'] == "Y") { ?><li><a id="modal_window_link" href="<?php echo $base_url; ?>output/pullsheets.php?section=admin&amp;go=judging_scores_bos&amp;id=<?php echo $row_style_type['id']; ?>"  title="Print the <?php echo $row_style_type['styleTypeName']; ?> BOS Pullsheet"><?php echo $row_style_type['styleTypeName']; ?></a></li><?php } ?>
          			<?php } while ($row_style_type = mysql_fetch_assoc($style_type)) ?>
        		</ul>
    			</li>
			</ul>
            <ul class="admin_default">
				<li>BOS Cup Mats:</li>
                <li><a id="modal_window_link" href="<?php echo $base_url; ?>output/bos_mat.php" title="Print BOS Cup Matss">Print</a></li>
                <li><em>For the mats to print properly, set your browser's printing margins to .25 inch and the orientation to landscape.</em></li>
            </ul>
            <?php } ?>
			<p class="admin_default_header">After Judging</p>
			<?php if ($totalRows_tables > 0) { ?>
            <ul class="admin_default">
				<li>Results Report  (with Scores):</li>
				<li><a id="modal_window_link" href="<?php echo $base_url; ?>output/results.php?section=admin&amp;go=judging_scores&amp;action=print&amp;filter=scores&amp;view=default" title="Results Report <?php echo $method; ?> (All with Scores)">Print</a> (All)</li>
				<li><a id="modal_window_link" href="<?php echo $base_url; ?>output/results.php?section=admin&amp;go=judging_scores&amp;action=print&amp;filter=scores&amp;view=winners" title="Results Report <?php echo $method; ?> (Winners Only with Scores)">Print</a> (Winners Only)</li>
			</ul>
			<ul class="admin_default">
				<li>Results Report  (without Scores):</li>
				<li><a id="modal_window_link" href="<?php echo $base_url; ?>output/results.php?section=admin&amp;go=judging_scores&amp;action=print&amp;filter=none&amp;view=default" title="Results Report <?php echo $method; ?> (All with Scores)">Print</a> (All)</li>
				<li><a id="modal_window_link" href="<?php echo $base_url; ?>output/results.php?section=admin&amp;go=judging_scores&amp;action=print&amp;filter=none&amp;view=winners" title="Results Report <?php echo $method; ?> (Winners Only without Scores)">Print</a> (Winners Only)</li>
				<li><a href="<?php echo $base_url; ?>output/results_download.php?section=admin&amp;go=judging_scores&amp;action=default&amp;filter=none&amp;view=pdf">Download PDF</a> (Winners Only)</li>
				<li><a href="<?php echo $base_url; ?>output/results_download.php?section=admin&amp;go=judging_scores&amp;action=default&amp;filter=none&amp;view=html">Download HTML</a> (Winners Only)</li>
			</ul>
            <?php if (((NHC) && ($prefix == "_final")) || (!NHC)) { ?>
			<ul class="admin_default">
				<li>BOS Round(s) Results Report:</li>
				<li><a id="modal_window_link" href="<?php echo $base_url; ?>output/results.php?section=admin&amp;go=judging_scores_bos&amp;action=print&amp;filter=bos&amp;view=default" title="BOS Round(s) Results Report">Print</a></li>
				<li><a href="<?php echo $base_url; ?>output/results_download.php?section=admin&amp;go=judging_scores_bos&amp;action=download&amp;filter=default&amp;view=pdf">Download PDF</a></li>
				<li><a href="<?php echo $base_url; ?>output/results_download.php?section=admin&amp;go=judging_scores_bos&amp;action=download&amp;filter=default&amp;view=html">Download HTML</a></li>	
			</ul>
            <?php } ?>
			<ul class="admin_default">
				<li>BJCP Judge/Steward/Staff Points Report:</li>
				<li><a id="modal_window_link" href="<?php echo $base_url; ?>output/staff_points.php?section=admin&amp;go=judging_assignments&amp;action=download&amp;filter=default&amp;view=default" title="BJCP Judge/Steward/Staff Points Report">Print</a></li>
				<li><a href="<?php echo $base_url; ?>output/staff_points.php?section=admin&amp;go=judging_assignments&amp;action=download&amp;filter=default&amp;view=pdf">Download PDF</a></li>
				<li><a href="<?php echo $base_url; ?>output/staff_points.php?section=admin&amp;go=judging_assignments&amp;action=download&amp;filter=default&amp;view=xml">Download XML</a></li>
			</ul>
            <?php if ($row_scores['count'] > 0) { ?>
			<ul class="admin_default">
				<li>Award Labels (Avery 5160):</li>
				<li><a href="<?php echo $base_url; ?>output/labels.php?section=admin&amp;go=judging_scores&amp;action=awards&amp;filter=default">Download PDF</a></li>
			</ul>
            <?php } } ?>
			<ul class="admin_default">
				<li>Participant Address Labels (Avery 5160):</li>
				<li><a href="<?php echo $base_url; ?>output/labels.php?section=admin&amp;go=participants&amp;action=address_labels&amp;filter=default">Download PDF</a> (All Participants)</li>
                <li><a href="<?php echo $base_url; ?>output/labels.php?section=admin&amp;go=participants&amp;action=address_labels&amp;filter=with_entries">Download PDF</a> (All Participants with Entries)</li>
			</ul>
            <ul class="admin_default">
				<li>Participant Summaries:</li>
				<li><a id="modal_window_link" href="<?php echo $base_url; ?>output/participant_summary.php" title="Print Participant Summaries (Each on a Separate Piece of 8 1/2 X 11 Paper)">Print</a> (All Participants with Entries)</li>
			</ul>
            
            <?php if (custom_modules("reports",1)) { ?>
            <p class="admin_default_header">Custom Reports</p>
			<ul class="admin_default">
				<?php echo custom_modules("reports",2); ?>
			</ul>
            <?php } ?>
</div>
<h4 class="trigger"><span class="icon"><img src="<?php echo $base_url; ?>images/page_go.png"  /></span>Exporting</h4>
<div class="toggle_container">
<p class="admin_default_header">Email Addresses (CSV Files)</p>
			<ul class="admin_default">
				<li><a href="<?php echo $base_url; ?>output/email_export.php">All Participants</a></li>
                <li><a href="<?php echo $base_url; ?>output/email_export.php?section=admin&amp;go=csv&amp;filter=avail_judges&amp;action=email">All Available Judges</a></li>
				<li><a href="<?php echo $base_url; ?>output/email_export.php?section=admin&amp;go=csv&amp;filter=avail_stewards&amp;action=email">All Available Stewards</a></li>
                <li><a href="<?php echo $base_url; ?>output/email_export.php?section=admin&amp;go=csv&amp;filter=judges&amp;action=email">All Assigned Judges</a></li>
				<li><a href="<?php echo $base_url; ?>output/email_export.php?section=admin&amp;go=csv&amp;filter=stewards&amp;action=email">All Assigned Stewards</a></li>
                <li><a href="<?php echo $base_url; ?>output/entries_export.php?section=admin&amp;go=csv&amp;filter=winners">Winners</a></li>
            </ul>
            <ul class="admin_default">
            	<li><a href="<?php echo $base_url; ?>output/entries_export.php?section=admin&amp;go=csv&amp;action=email">All Entries</a></li>
            	<li><a href="<?php echo $base_url; ?>output/entries_export.php?section=admin&amp;go=csv&amp;filter=paid&amp;action=email&amp;view=all">All Paid Entries</a></li>
				<li><a href="<?php echo $base_url; ?>output/entries_export.php?section=admin&amp;go=csv&amp;filter=paid&amp;action=email">Paid & Received Entries</a><a href="<?php echo $base_url; ?>output/email_export.php?section=admin&amp;filter=judges"></a></li>
                <li><a href="<?php echo $base_url; ?>output/entries_export.php?section=admin&amp;go=csv&amp;filter=nopay&amp;action=email&amp;view=all">All Non-Paid Entries</a></li>
				<li><a href="<?php echo $base_url; ?>output/entries_export.php?section=admin&amp;go=csv&amp;filter=nopay&amp;action=email">Non-Paid & Received Entries</a></li>
			</ul>
            <?php if (!NHC) { ?>
  			<p class="admin_default_header">Tab Delimited Files</li>
<p>For importing into the Homebrew Competition Coordination Program (HCCP), available for download <a href="http://www.folsoms.net/hccp/" target="_blank">here</a>. <?php if ($totalRows_judging1 > 1) { ?>The tab delimited file for <em>each location</em> should be imported into HCCP as it's own database. Refer to the <a href="http://www.folsoms.net/hccp/hccp.pdf" target="_blank">HCCP documentation</a> for import instructions.<?php } ?></p> 
			<ul class="admin_default">
				<li><a href="<?php echo $base_url; ?>output/participants_export.php?section=admin&amp;go=tab&amp;action=hccp">All Participants</a></li>
				<li><a href="<?php echo $base_url; ?>output/entries_export.php?section=admin&amp;go=tab&amp;filter=paid&amp;action=hccp">Paid & Received Entries</a></li>
				<li><a href="<?php echo $base_url; ?>output/entries_export.php?section=admin&amp;go=tab&amp;action=hccp">All Entries</a></li>
			</ul>
            <?php } ?>
  			<p class="admin_default_header">CSV Files</p>
			<ul class="admin_default">
                <li><a href="<?php echo $base_url; ?>output/entries_export.php?section=admin&amp;go=csv&amp;action=all&amp;filter=all">All Entries</a> (All Data)</li>
				<li><a href="<?php echo $base_url; ?>output/entries_export.php?section=admin&amp;go=csv">All Entries</a> (Limited Data)</li>
            </ul>
			<ul class="admin_default">
            	<li><a href="<?php echo $base_url; ?>output/entries_export.php?section=admin&amp;go=csv&amp;filter=paid&amp;view=all">All Paid Entries</a> (Limited Data)</li>
				<li><a href="<?php echo $base_url; ?>output/entries_export.php?section=admin&amp;go=csv&amp;filter=paid">Paid & Received Entries</a> (Limited Data)</li>
                <li><a href="<?php echo $base_url; ?>output/entries_export.php?section=admin&amp;go=csv&amp;filter=nopay&amp;view=all">All Non-Paid Entries</a> (Limited Data)</li>
				<li><a href="<?php echo $base_url; ?>output/entries_export.php?section=admin&amp;go=csv&amp;filter=nopay">Non-Paid & Received Entries</a> (Limited Data)</li>
			</ul>
			<ul class="admin_default">
				<li><a href="<?php echo $base_url; ?>output/participants_export.php?section=admin&amp;go=csv">All Participants</a></li>
				<li><a href="<?php echo $base_url; ?>output/entries_export.php?section=admin&amp;go=csv&amp;filter=winners">Winners</a></li>
			</ul>
            <?php if (!NHC) { ?>
			<p class="admin_default_header">Promo Materials</p>
			<ul class="admin_default">
				<li><a href="<?php echo $base_url; ?>output/promo_export.php?section=admin&amp;go=html&amp;action=html">HTML</a> (.html)</li>
				<li><a href="<?php echo $base_url; ?>output/promo_export.php?section=admin&amp;go=word&amp;action=word">Word</a> (.doc)</li>
                <li><a href="<?php echo $base_url; ?>output/promo_export.php?section=admin&amp;go=word&amp;action=bbcode">Bulletin Board Code (BBC)</a> (.txt)</li>
			</ul>
            <?php } ?>
            <?php if (custom_modules("exports",1)) { ?>
            <p class="admin_default_header">Custom Exports</p>
			<ul class="admin_default">
				<?php echo custom_modules("exports",2); ?>
			</ul>
            <?php } ?>
		</div>
        <?php if ($_SESSION['userLevel'] == "0") { ?>
		<h4 class="trigger"><span class="icon"><img src="<?php echo $base_url; ?>images/camera_add.png" /></span>Archiving</h4>
		<div class="toggle_container">
			<p class="admin_default_header">Manage/View</p>
			<ul class="admin_default">
				<li><a href="<?php echo $base_url; ?>index.php?section=admin&amp;go=archive">Archives</a></li>
			</ul>
		</div>
        <?php } ?>
	</div>
</div>
</div>
<?php } 
if ($go == "judging") 	    			include (ADMIN.'judging_locations.admin.php');
if ($go == "judging_preferences") 	    include (ADMIN.'judging_preferences.admin.php');
if ($go == "judging_tables") 	    	include (ADMIN.'judging_tables.admin.php');
if ($go == "judging_flights") 	    	include (ADMIN.'judging_flights.admin.php');
if ($go == "judging_scores") 	    	include (ADMIN.'judging_scores.admin.php');
if ($go == "judging_scores_bos")    	include (ADMIN.'judging_scores_bos.admin.php');
if ($go == "participants") 				include (ADMIN.'participants.admin.php');
if ($go == "entries") 					include (ADMIN.'entries.admin.php');
if ($go == "contacts") 	    			include (ADMIN.'contacts.admin.php');
if ($go == "dropoff") 	    			include (ADMIN.'dropoff.admin.php');
if ($go == "checkin") 	    			include (ADMIN.'barcode_check-in.admin.php');
if ($go == "count_by_style")			include (ADMIN.'entries_by_style.admin.php');
if (($action == "register") && ($go == "judge")) 	include (SECTIONS.'register.sec.php');
if (($action == "register") && ($go == "entrant")) 	include (SECTIONS.'register.sec.php');

if ($_SESSION['userLevel'] == "0") {
	if ($go == "styles") 	    			include (ADMIN.'styles.admin.php');
	if ($go == "archive") 	    			include (ADMIN.'archive.admin.php');
	if ($go == "make_admin") 				include (ADMIN.'make_admin.admin.php');
	if ($go == "contest_info") 				include (ADMIN.'competition_info.admin.php');
	if ($go == "preferences") 				include (ADMIN.'site_preferences.admin.php');
	if ($go == "sponsors") 	   			 	include (ADMIN.'sponsors.admin.php');
	if ($go == "style_types")    			include (ADMIN.'style_types.admin.php');
	if ($go == "special_best") 	    		include (ADMIN.'special_best.admin.php');
	if ($go == "special_best_data") 	    include (ADMIN.'special_best_data.admin.php');
	if ($go == "mods") 	    				include (ADMIN.'mods.admin.php');
}

if ($_SESSION['prefsUseMods'] == "Y") include(INCLUDES.'mods_bottom.inc.php');
}
else echo "<div class=\"error\">You do not have sufficient privileges to access this area.</div>";
?>
