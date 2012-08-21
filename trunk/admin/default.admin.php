
<div id="header">	
	<div id="header-inner"><h1><?php echo $header_output; ?></h1></div>
</div>
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

function admin_help($go,$header_output,$action,$filter) {
	
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
	
	$return = '<p><span class="icon"><img src="images/help.png" /></span><a id="modal_window_link" href="http://help.brewcompetition.com/files/'.$page.'.html" title="BCOE&amp;M Help for '.$header_output.'">Help</a></p>';
	return $return;	
}

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



?>


<script type="text/javascript" language="javascript" src="js_includes/toggle.js"></script>
<?php } ?>

<?php 
if ($setup_free_access == TRUE) echo "<div class='error'>The &#36;setup_free_access variable in config.php is currently set to TRUE. For security reasons, the setting should returned to FALSE. You will need to edit config.php directly and re-upload to your server to do this.</div>";
if (($action != "print") && ($msg != "default")) echo $msg_output; 
if (($action != "print") && ($go != "default")) echo admin_help($go,$header_output,$action,$filter); ?>
<?php if (($section == "admin") && ($go == "default")) { ?>
<div class="at-a-glance">
<h3>Numbers at a Glance</h3> 
<table>
	<tr>
    	<td colspan="6">As of <?php echo getTimeZoneDateTime($row_prefs['prefsTimeZone'], time(), $row_prefs['prefsDateFormat'], $row_prefs['prefsTimeFormat'], "long", "date-time"); ?></td>
	</tr>
    <tr>
		<td class="dataLabel"><a href="index.php?section=admin&amp;go=entries">Entries</a> (Confirmed/Unconfirmed):</td>
        <td class="data"><?php echo $totalRows_log_confirmed."/".$entries_unconfirmed; ?></td>
		<td class="dataLabel">Total Fees:</td>
        <td class="data"><?php echo $row_prefs['prefsCurrency'].$total_fees; ?></td>
        <td class="dataLabel"><a href="index.php?section=admin&amp;go=participants">Total Participants</a>:</td>
        <td class="data"><?php echo get_participant_count('default'); ?></td>
	</tr>
    <tr>
    	<td class="dataLabel">Paid, Rec'd &amp; Confirmed Entries:</td>
        <td class="data"><?php echo get_entry_count(); ?></td>
		<td class="dataLabel">Total Paid Fees:</td>
        <td class="data"><?php echo $row_prefs['prefsCurrency'].$total_fees_paid; ?></td>
        <td class="dataLabel"><a href="index.php?section=admin&amp;go=participants&amp;filter=judges">Available Judges</a>:</td>
        <td class="data"><?php echo get_participant_count('judge'); ?></td>
	</tr>
    <?php if (($row_contest_info['contestEntryFeePassword'] != "") && ($row_contest_info['contestEntryFeePasswordNum'] != "")) { ?>
    <tr>
        <td class="dataLabel">Participants Who Redeemed Discount:</td>
        <td class="data"><?php $a = explode("^",total_discount()); echo $a[0]; ?></td>
        <td class="dataLabel">Total Discounted Entries:</td>
        <td class="data"><?php echo $a[1]; ?></td>
        <td class="dataLabel">Total Fees at Discount:</td>
        <td class="data"><?php echo $row_prefs['prefsCurrency'].($a[1] * $row_contest_info['contestEntryFeePasswordNum']); ?></td>
    </tr>
    <?php } ?>
</table>
</div>
<?php } if ($row_user['userLevel'] == "1") {
			if (($totalRows_dropoff == "0") && ($go == "default")) echo "<div class='error'>No drop-off locations have been specified. <a href=\"index.php?section=admin&amp;action=add&amp;go=dropoff\">Add a drop-off location</a>?</div>";
			if (($totalRows_judging == "0") && ($go == "default")) echo "<div class='error'>No judging dates/locations have been specified. <a href=\"index.php?section=admin&amp;action=add&amp;go=judging\">Add a judging location</a>?</div>"; 
if ($go == "default") { 
if (($registration_open == "2") && ($row_prefs['prefsCompOrg'] == "N")) echo "<div class='info'>Now that registration is closed, keep your entry database up to date by 1) adding any participants and their associated entries who did not register online and 2) finalizing judge and steward assignments.</div>";

?>
<div id="menu_container">
<div id="outer">
<p>Click the headings below to expand and collapse each category.</p>
	<div class="menus">
		<h4 class="trigger"><span class="icon"><img src="images/help.png"  /></span>Help</h4>
        <div class="toggle_container">
        	<p class="admin_default_header">Quick Links</p>
        	<ul class="admin_default">
				<li><a href="http://help.brewcompetition.com/files/gone_through.html" title="Help" id="modal_window_link">I've Gone Through Set Up, What Do I Do Now?</a></li>
    			<li><a href="http://help.brewcompetition.com/files/comp_contacts.html" title="Help" id="modal_window_link">How Do I Add More Contacts?</a></li>
                <li><a href="http://help.brewcompetition.com/files/drop_off.html" title="Help" id="modal_window_link">How Do I Add More Drop Off Locations?</a></li>
    			<li><a href="http://help.brewcompetition.com/files/sponsors.html" title="Help" id="modal_window_link">How Do I Display Sponsors?</a></li>			
            </ul>
            <ul class="admin_default">
				<li><a href="http://help.brewcompetition.com/files/style_types.html" title="Help" id="modal_window_link">What Are Style Types?</a></li>
    			<li><a href="http://help.brewcompetition.com/files/tables.html" title="Help" id="modal_window_link">How Do I Set Up Tables?</a></li>
                <?php if ($row_judging_prefs['jPrefsQueued'] == "N") { ?>
                <li><a href="http://help.brewcompetition.com/files/flights.html" title="Help" id="modal_window_link">How Do I Define Flights?</a></li>			
            	<?php } ?>
                <li><a href="http://help.brewcompetition.com/files/judges.html" title="Help" id="modal_window_link">How Do I Assign Judges/Stewards to Tables?</a></li>
    		</ul>
            <ul class="admin_default">
				<li><a href="http://help.brewcompetition.com/files/scoring.html" title="Help" id="modal_window_link">How Do I Enter Scores?</a></li>
                <li><a href="http://help.brewcompetition.com/files/archiving.html" title="Help" id="modal_window_link">What are Archives?</a></li>		
            </ul>
            <p class="admin_default_header">More Help</p>
            <ul class="admin_default">
				<li><a href="http://help.brewcompetition.com" title="Help" target="_blank">All Help Topics</a></li>
    		</ul>
        </div>
		<h4 class="trigger"><span class="icon"><img src="images/cog.png"  /></span>Defining Preferences</h4>
		<div class="toggle_container">
			<p class="admin_default_header">Define</p>
			<ul class="admin_default">
				<li><a href="index.php?section=admin&amp;go=preferences">Site Preferences</a></li>
    			<li><a href="index.php?section=admin&amp;go=judging_preferences">Competition Organization Preferences</a></li>
			</ul>
		</div>
		<h4 class="trigger"><span class="icon"><img src="images/wrench.png"  /></span>Preparing</h4>
		<div class="toggle_container">
        	<p class="admin_default_header">Manage/View</p>
			<ul class="admin_default">
            	<li><a href="index.php?section=admin&amp;go=style_types">Style Types</a></li>
			    <li><a href="index.php?section=admin&amp;go=styles">Accepted Style Categories</a></li>   
			</ul>
            <ul class="admin_default">
            	<li><a href="index.php?section=admin&amp;go=special_best">Custom Winner Categories</a></li>
			</ul>
            <ul class="admin_default">
    			<li><a href="index.php?section=admin&amp;go=judging">Judging Locations</a></li>
    			<li><a href="index.php?section=admin&amp;go=contacts">Competition Contacts</a></li>
    			<li><a href="index.php?section=admin&amp;go=dropoff">Drop-Off Locations</a></li>
    			<li><a href="index.php?section=admin&amp;go=sponsors">Sponsors</a></li>
			</ul>
            <p class="admin_default_header">Add</p>
			<ul class="admin_default">
   			 	<li><a href="index.php?section=admin&amp;go=style_types&amp;action=add">A Style Type</a></li>
                <li><a href="index.php?section=admin&amp;go=styles&amp;action=add">A Custom Style Category</a></li>
			</ul>
            <ul class="admin_default">
            	<li><a href="index.php?section=admin&amp;go=special_best&amp;action=add">A Custom Winning Category</a></li>
			</ul>
			<ul class="admin_default">
			    <li><a href="index.php?section=admin&amp;go=judging&amp;action=add">A Judging Location</a></li>
			    <li><a href="index.php?section=admin&amp;go=dropoff&amp;action=add">A Drop-Off Location</a></li>
			    <li><a href="index.php?section=admin&amp;go=sponsors&amp;action=add">A Sponsor</a></li>
			    <li><a href="index.php?section=admin&amp;go=contacts&amp;action=add">A Competition Contact</a></li>
			</ul>
			<p class="admin_default_header">Edit</p>
			<ul class="admin_default">
				<li><a href="index.php?section=admin&amp;go=contest_info">Competition Info</a></li>
			</ul>
			<p class="admin_default_header">Upload</p>
			<ul class="admin_default">
				<li><a href="admin/upload.admin.php" title="Upload Competition Logo Image" id="modal_window_link">A Competition Logo</a></li>
			    <li><a href="admin/upload.admin.php" title="Upload Sponsor Logo Image" id="modal_window_link">Sponsor Logos</a></li>
			</ul>
		</div>
		<h4 class="trigger"><span class="icon"><img src="images/note.png"  /></span>Entry and Data Gathering</h4>
		<div class="toggle_container">
			<p class="admin_default_header">Manage/View</p>
			<ul class="admin_default">
			    <li><a href="index.php?section=admin&amp;go=style_types">Style Types</a></li>
                <li><a href="index.php?section=admin&amp;go=styles">Accepted Style Categories</a></li>
			</ul>
            <ul class="admin_default">
				<li><a href="index.php?section=admin&amp;go=participants">Participants</a></li>
			    <li><a href="index.php?section=admin&amp;go=entries">Entries</a></li>
            </ul>
			<ul class="admin_default">
    			<li><a href="index.php?section=admin&amp;go=judging">Judging Locations</a></li>
    			<li><a href="index.php?section=admin&amp;go=participants&amp;filter=judges">Available Judges</a></li>
    			<li><a href="index.php?section=admin&amp;go=participants&amp;filter=stewards">Available Stewards</a></li>
            </ul>
            <ul class="admin_default">
    			<li><a href="index.php?section=admin&amp;go=contacts">Competition Contacts</a></li>
			</ul>
			<ul class="admin_default">
    			<li><a href="index.php?section=admin&amp;go=dropoff">Drop-Off Locations</a></li>
    			<li><a href="index.php?section=admin&amp;go=sponsors">Sponsors</a></li>
			</ul>
			<p class="admin_default_header">Add</p>
			<ul class="admin_default">
  				<li><a href="index.php?section=admin&amp;go=style_types&amp;action=add">A Style Type</a></li>
            	<li><a href="index.php?section=admin&amp;go=styles&amp;action=add">A Custom Style Category</a></li>
			</ul>
            <ul class="admin_default">
    			<li><a href="index.php?section=admin&amp;go=entrant&amp;action=register">A Participant</a></li>
    			<li><a href="index.php?section=admin&amp;go=judge&amp;action=register">A Participant as a Judge/Steward</a></li></li>
   			 	<li><a href="index.php?section=brew&amp;go=entries&amp;action=add&amp;filter=admin">A Participant's Entry</a></li>
			</ul>
            <ul class="admin_default">
		      <li><a href="index.php?section=admin&amp;go=judging&amp;action=add">A Judging Location</a></li>
            </ul>
            <ul class="admin_default">
            	<li><a href="index.php?section=admin&amp;go=contacts&amp;action=add">A Competition Contact</a></li>
            </ul>
            <ul class="admin_default">
			    <li><a href="index.php?section=admin&amp;go=dropoff&amp;action=add">A Drop-Off Location</a></li>
			    <li><a href="index.php?section=admin&amp;go=sponsors&amp;action=add">A Sponsor</a></li>

			</ul>
			<p class="admin_default_header">Assign</p>
			<ul class="admin_default">
				<li><a href="index.php?section=admin&amp;action=assign&amp;go=judging&amp;filter=judges">Participants as Judges</a></li>
				<li><a href="index.php?section=admin&amp;action=assign&amp;go=judging&amp;filter=stewards">Participants as Stewards</a></li>
			    <li><a href="index.php?section=admin&amp;action=assign&amp;go=judging&amp;filter=staff">Participants as Staff</a></li>
			</ul>
		</div>
		<h4 class="trigger"><span class="icon"><img src="images/arrow_refresh.png"  /></span>Sorting Received Entries</h4>
		<div class="toggle_container">
			<p class="admin_default_header">Manage/View</p>
			<ul class="admin_default">
    			<li><a href="index.php?section=admin&amp;go=participants">Participants</a></li>
    			<li><a href="index.php?section=admin&amp;go=entries">Entries</a></li>
			</ul>
			<ul class="admin_default">
			    <li>Mark Entries as Paid/Received for Category:</li>
			    <li><?php echo style_choose($section,"entries",$action,$filter,$view,"index.php","none"); ?></li>
			</ul>
			<p class="admin_default_header">Add</p>
			<ul class="admin_default">
			    <li><a href="index.php?section=admin&amp;go=entrant&amp;action=regiser">A Participant</a></li>
			    <li><a href="index.php?section=admin&amp;go=judge&amp;action=register">A Participant as a Judge/Steward</a></li>
			    <li><a href="index.php?section=brew&amp;go=entries&amp;action=add&amp;filter=admin">A Participant's Entry</a></li>
			</ul>
      <p class="admin_default_header">Regenerate</p>
			<ul class="admin_default">
            	<li>Entry Judging Numbers:</li>
                <!--
                <li>
                <select name="generate_choice_1" id="generate_choice_1" onchange="jumpMenu('self',this,0)" >
				<option>Choose Below:</option>
                <option value="includes/process.inc.php?section=admin&amp;go=entries&amp;action=generate_judging_numbers&amp;sort=id&amp;dir=ASC">Key Off Entry Number (Ascending)</option>
                <option value="includes/process.inc.php?section=admin&amp;go=entries&amp;action=generate_judging_numbers&amp;sort=id&amp;dir=DESC">Key Off Entry Number (Descending)</option>
                <option value="includes/process.inc.php?section=admin&amp;go=entries&amp;action=generate_judging_numbers&amp;sort=brewName&amp;dir=ASC">Key Off Entry Name (Ascending)</option>
                <option value="includes/process.inc.php?section=admin&amp;go=entries&amp;action=generate_judging_numbers&amp;sort=brewName&amp;dir=DESC">Key Off Entry Name (Descending)</option>
                </select>
                                -->
                <li>
                <div class="menuBar">
                <a class="menuButton" href="#" onclick="#" onmouseover="buttonMouseover(event, 'menu_generate');">Key Off Of... (Select One)</a>
                <div id="menu_generate" class="menu" onmouseover="menuMouseover(event)">
                <a class="menuItem" onclick="return confirm('Are you sure you want to regenerate judging numbers for all entries?');" href="includes/process.inc.php?section=admin&amp;go=entries&amp;action=generate_judging_numbers&amp;sort=id&amp;dir=ASC">Entry Number (Ascending)</a>
                <a class="menuItem" onclick="return confirm('Are you sure you want to regenerate judging numbers for all entries?');" href="includes/process.inc.php?section=admin&amp;go=entries&amp;action=generate_judging_numbers&amp;sort=id&amp;dir=DESC">Entry Number (Descending)</a>
                <a class="menuItem" onclick="return confirm('Are you sure you want to regenerate judging numbers for all entries?');" href="includes/process.inc.php?section=admin&amp;go=entries&amp;action=generate_judging_numbers&amp;sort=brewName&amp;dir=ASC">Entry Name (Ascending)</a>
                <a class="menuItem" onclick="return confirm('Are you sure you want to regenerate judging numbers for all entries?');" href="includes/process.inc.php?section=admin&amp;go=entries&amp;action=generate_judging_numbers&amp;sort=brewName&amp;dir=DESC">Entry Name (Descending)</a>
                </div>
                </div>
                </li>

			    <!-- <li>Entry Judging Numbers for Category:</li>
                <li><?php //echo style_choose($section,"entries","generate_judging_numbers",$filter,"includes/process.inc.php","none"); ?></li> -->
			</ul>
			<p class="admin_default_header">Print</p>
            <ul class="admin_default">
            	<li>Sorting Sheets:</li>
				<li><a id="modal_window_link" href="output/sorting.php?section=admin&amp;go=default&amp;filter=default">All Categories</a></li>
                <li>For Category:</li>
				<li><?php echo style_choose($section,"default",$action,$filter,$view,"output/sorting.php","thickbox"); ?></li>
            </ul>
            <ul class="admin_default">
            	<li>Entry Number / Judging Number Cheat Sheets:</li>
				<li><a id="modal_window_link" href="output/sorting.php?section=admin&amp;go=cheat&amp;filter=default">All Categories</a></li>
                <li>For Category:</li>
				<li><?php echo style_choose($section,"cheat",$action,$filter,$view,"output/sorting.php","thickbox"); ?></li>
            </ul>
			<ul class="admin_default">
				<li>Bottle Labels (Using <em>Entry Numbers</em> - Avery 5160):</li>
			    <li><a href="output/labels.php?section=admin&amp;go=entries&amp;action=bottle-entry&amp;filter=default">All Categories</a></li>
				<li>For Category:</li>
				<li><?php echo style_choose($section,"entries","bottle-entry",$filter,$view,"output/labels.php","none"); ?></li>
			</ul>
            <ul class="admin_default">
				<li>Bottle Labels (Using <em>Judging Numbers</em> - Avery 5160):</li>
			    <li><a href="output/labels.php?section=admin&amp;go=entries&amp;action=bottle-judging&amp;filter=default">All Categories</a></li>
				<li>For Category:</li>
				<li><?php echo style_choose($section,"entries","bottle-judging",$filter,$view,"output/labels.php","none"); ?></li>
			</ul>
            <ul class="admin_default">
				<li>Bottle Labels with Special Ingredients (Using <em>Judging Numbers</em> - Avery 5160):</li>
			    <li><a href="output/labels.php?section=admin&amp;go=entries&amp;action=bottle-judging&amp;filter=default&amp;view=special">All Categories</a></li>
				<li>For Category:</li>
				<li><?php echo style_choose($section,"entries","bottle-judging",$filter,"special","output/labels.php","none"); ?></li>
			</ul>
		</div>
		<h4 class="trigger"><span class="icon"><img src="images/book.png" alt="" /></span>Organizing</h4>
		<div class="toggle_container">
			<p class="admin_default_header">Manage/View</p>
			<ul class="admin_default">
			    <li><a href="index.php?section=admin&amp;go=participants">Participants</a></li>
			    <li><a href="index.php?section=admin&amp;go=entries">Entries</a></li>
			</ul>
	  		<?php if ($row_prefs['prefsCompOrg'] == "Y") { ?>
			<ul class="admin_default">
  				<li><a href="index.php?section=admin&amp;go=judging_tables">Tables</a></li>
                <?php if ($row_judging_prefs['jPrefsQueued'] == "N") { ?>
   			 	<li><a href="index.php?section=admin&amp;go=judging_flights">Flights</a></li>
                <?php } ?>
			</ul>
			<ul class="admin_default">
  				<li><a href="index.php?section=admin&amp;go=styles&amp;filter=orphans">Styles Without a Valid Style Type</a></li>
  				<li><a href="index.php?section=admin&amp;go=judging_tables&amp;filter=orphans">Styles Not Assigned to Tables</a></li>
			</ul>
			<?php } ?>
			<ul class="admin_default">
    			<li><a href="index.php?section=admin&amp;go=participants&amp;filter=assignJudges">Assigned Judges</a></li>
    			<li><a href="index.php?section=admin&amp;go=participants&amp;filter=assignStewards">Assigned Stewards</a></li>
			</ul>
			<p class="admin_default_header">Add</p>
			<ul class="admin_default">
    			<li><a href="index.php?section=admin&amp;go=participants&amp;action=add">A Participant</a></li>
    			<li><a href="index.php?section=admin&amp;go=judge&amp;action=register">A Participant as a Judge/Steward</a></li>
    			<li><a href="index.php?section=brew&amp;go=entries&amp;action=add&amp;filter=admin">A Participant's Entry</a></li>
			</ul>
  			<?php if ($row_prefs['prefsCompOrg'] == "Y") { ?>
	  <ul class="admin_default">
   			 	<li><a href="index.php?section=admin&amp;go=judging_tables&amp;action=add">A Table</a></li>
                <?php if ($row_judging_prefs['jPrefsQueued'] == "N") { ?>
    			<li><a href="index.php?section=admin&amp;go=judging_flights">Flights to Tables</a></li>
                <?php } ?>
			</ul>
  			<?php } ?>
<p class="admin_default_header">Assign</p>
  <?php if ($row_prefs['prefsCompOrg'] == "N") { ?>
			<ul class="admin_default">
				<li><a href="index.php?section=admin&amp;go=judging&amp;action=assign&amp;filter=judges">Judges (Final Assignments)</a></li>
				<li><a href="index.php?section=admin&amp;go=judging&amp;action=assign&amp;filter=stewards">Stewards (Final Assignments)</a></li>
			</ul>
  	<?php if ($totalRows_judging > 1) { ?>
			<ul class="admin_default">
				<li><a href="index.php?section=admin&amp;go=judging&amp;action=update&amp;filter=judges">Judges to a Location</a></li>
				<li><a href="index.php?section=admin&amp;go=judging&amp;action=update&amp;filter=stewards">Stewards to a Location</a></li>
			</ul>
 	 <?php } ?>
  <?php } ?>
  <?php if ($row_prefs['prefsCompOrg'] == "Y") { ?>
  			<ul class="admin_default">
				<li><a href="index.php?section=admin&amp;action=assign&amp;go=judging&amp;filter=judges">Participants as Judges</a></li>
				<li><a href="index.php?section=admin&amp;action=assign&amp;go=judging&amp;filter=stewards">Participants as Stewards</a></li>
			    <li><a href="index.php?section=admin&amp;action=assign&amp;go=judging&amp;filter=staff">Participants as Staff</a></li>
			</ul>
  	<?php if ($totalRows_tables > 1) { ?>
			<ul class="admin_default">
            	<li><a href="index.php?section=admin&amp;go=judging_flights&amp;action=assign&amp;filter=rounds"><?php echo $assign_to; ?> to Rounds</a></li>
            </ul>
            <ul class="admin_default">
				<li><a href="index.php?section=admin&amp;go=judging_tables&amp;action=assign">Judges or Stewards to a Table</a></li>
            </ul>
            <ul class="admin_default">
				<li><a href="index.php?section=admin&amp;action=assign&amp;go=judging&amp;filter=bos">Best of Show Judges</a></li>
			</ul>
  	<?php } ?>
  <?php } ?>
</div>
<?php if ($row_prefs['prefsCompOrg'] == "Y") { ?>
<h4 class="trigger"><span class="icon"><img src="images/rosette.png"  /></span>Scoring</h4>
<div class="toggle_container">
<p class="admin_default_header">Manage/View</p>
			<ul class="admin_default">
				<li><a href="index.php?section=admin&amp;go=participants">Participants</a></li>
				<li><a href="index.php?section=admin&amp;go=entries">Entries</a></li>
				<li><a href="index.php?section=admin&amp;go=judging_tables">Tables</a></li> 
			</ul>
			<ul class="admin_default">
  				<li><a href="index.php?section=admin&amp;go=judging_scores">Scores by Table</a></li>
				<li><a href="index.php?section=admin&amp;go=judging_scores&amp;filter=category">Scores by Category</a></li>
				<li><a href="index.php?section=admin&amp;go=judging_scores_bos">BOS Entries and Places</a></li>
			</ul>
            <ul class="admin_default">
  				<li><a href="index.php?section=admin&amp;go=special_best">Custom Winning Categories</a></li>
				<li><a href="index.php?section=admin&amp;go=special_best_data">Custom Winning Category Entries</a></li>
			</ul>
			<p class="admin_default_header">Add</p>
			<ul class="admin_default">
				<li>Scores For:</li>
				<li><?php echo score_table_choose($dbTable,$judging_tables_db_table,$judging_scores_db_table); ?></li>
            </ul>
            <ul class="admin_default">
				<li>Winners for Custom Winning Category:</li>
				<li><?php echo score_custom_winning_choose($special_best_info_db_table,$special_best_data_db_table); ?></li>
            </ul>
</div>
<h4 class="trigger"><span class="icon"><img src="images/printer.png"  /></span>Printing and Reporting</h4>
<div class="toggle_container">
<p class="admin_default_header">Before Judging</p>
			<?php if ($totalRows_tables > 0) { ?>
			<ul class="admin_default">
				<li>Print Pullsheets:</li>
				<li><a id="modal_window_link" href="output/pullsheets.php?section=admin&amp;go=judging_tables&amp;id=default" title="Print All Table Pullsheets">All Tables</a></li>
				<li>
    				<div class="menuBar"><a class="menuButton" href="#" onclick="#" onmouseover="buttonMouseover(event, 'pullsheets');">For Table #...</a></div>
    				<div id="pullsheets" class="menu" onmouseover="menuMouseover(event)">
    				<?php do { ?>
						<a id="modal_window_link" class="menuItem" style="font-size: .9em; padding: 1px;" href="output/pullsheets.php?section=admin&amp;go=judging_tables&amp;id=<?php echo $row_tables['id']; ?>" title="Print Pullsheet for Table # <?php echo $row_tables['tableNumber'].": ".$row_tables['tableName']; ?>"><?php echo $row_tables['tableNumber'].": ".$row_tables['tableName']; ?></a>
    				<?php } while ($row_tables = mysql_fetch_assoc($tables)); ?>
    				</div>
    			</li>
			</ul>
			<ul class="admin_default">
				<li>Print Table Cards:</li>
				<li><a id="modal_window_link" href="output/table_cards.php?section=admin&amp;go=judging_tables&amp;id=default" title="Print Table Cards">All Tables</a></li>
				<li>
                	<div class="menuBar"><a class="menuButton" href="#" onclick="#" onmouseover="buttonMouseover(event, 'table_cards');">For Table #...</a></div>
                    <div id="table_cards" class="menu" onmouseover="menuMouseover(event)">
                        <?php do { ?>
                        <a id="modal_window_link" class="menuItem" style="font-size: .9em; padding: 1px;" href="output/table_cards.php?section=admin&amp;go=judging_tables&amp;id=<?php echo $row_tables_edit['id']; ?>" title="Print Table Card for Table #<?php echo $row_tables_edit['tableNumber'].": ".$row_tables_edit['tableName']; ?>"><?php echo $row_tables_edit['tableNumber'].": ".$row_tables_edit['tableName']; ?></a>
                        <?php } while ($row_tables_edit = mysql_fetch_assoc($tables_edit)); ?>
                    </div>
    			</li>
			</ul>
			<ul class="admin_default">
				<li>Print Judge Assignments:</li>
				<li><a id="modal_window_link" href="output/assignments.php?section=admin&amp;go=judging_assignments&amp;filter=judges&amp;view=name" title="Print Judge Assignments by Name">By Judge Last Name</a></li>
				<li><a id="modal_window_link" href="output/assignments.php?section=admin&amp;go=judging_assignments&amp;filter=judges&amp;view=table" title="Print Judge Assignments by Table">By Table</a></li>
   				<?php if ($totalRows_judging > 1) { ?>
				<li><a id="modal_window_link" href="output/assignments.php?section=admin&amp;go=judging_assignments&amp;filter=judges&amp;view=location" title="Print Judge Assignments by Location">By Location</a></li>
    			<?php } ?>
			</ul>
			<ul class="admin_default">
				<li>Print Steward Assignments:</li>
				<li><a id="modal_window_link" href="output/assignments.php?section=admin&amp;go=judging_assignments&amp;filter=stewards&amp;view=name" title="Print Steward Assignments by Name">By Steward Last Name</a></li>
				<li><a id="modal_window_link" href="output/assignments.php?section=admin&amp;go=judging_assignments&amp;filter=stewards&amp;view=table" title="Print Steward Assignments by Table">By Table</a></li>
    			<?php if ($totalRows_judging > 1) { ?>
				<li><a id="modal_window_link" href="output/assignments.php?section=admin&amp;go=judging_assignments&amp;filter=stewards&amp;view=location" title="Print Steward Assignments by Location">By Location</a></li>
				<?php } ?>
			</ul>
            <?php } ?>
			<ul class="admin_default">
				<li>Print Sign-in Sheets:</li>
				<li><a id="modal_window_link" href="output/assignments.php?section=admin&amp;go=judging_assignments&amp;filter=judges&amp;view=sign-in" title="Print a Judge Sign-in Sheet">Judges</a></li>   
				<li><a id="modal_window_link" href="output/assignments.php?section=admin&amp;go=judging_assignments&amp;filter=stewards&amp;view=sign-in" title="Print a Steward Sign-in Sheet">Stewards</a></li>   
			</ul>
            <ul class="admin_default">
				<li>Judge Scoresheet Labels (Avery 5150):</li>
            	<li><a href="output/labels.php?section=admin&go=participants&action=judging_labels">Download PDF</a> (All Assigned Judges, 30 Labels per Judge)</li>
            </ul>
			<?php if ($row_scores['count'] > 0) { ?>
<p class="admin_default_header">During Judging</p>
            <ul class="admin_default">
				<li>Print BOS Pullsheets:
    			<ul>
        			<li><a id="modal_window_link" href="output/pullsheets.php?section=admin&amp;go=judging_scores_bos" title="Print All BOS Pullsheets">All</a></li>
    	  			<?php do { ?>
          			<?php if ($row_style_type['styleTypeBOS'] == "Y") { ?><li><a id="modal_window_link" href="output/pullsheets.php?section=admin&amp;go=judging_scores_bos&amp;id=<?php echo $row_style_type['id']; ?>"  title="Print the <?php echo $row_style_type['styleTypeName']; ?> BOS Pullsheet"><?php echo $row_style_type['styleTypeName']; ?></a></li><?php } ?>
          			<?php } while ($row_style_type = mysql_fetch_assoc($style_type)) ?>
        		</ul>
    			</li>
			</ul>
            <?php } ?>
			<p class="admin_default_header">After Judging</p>
			<?php if ($totalRows_tables > 0) { ?>
            <ul class="admin_default">
				<li>Results Report <?php echo $method; ?> (with Scores):</li>
				<li><a id="modal_window_link" href="output/results.php?section=admin&amp;go=judging_scores&amp;action=print&amp;filter=scores&amp;view=default" title="Results Report by Table (All with Scores)">Print (All)</a></li>
				<li><a id="modal_window_link" href="output/results.php?section=admin&amp;go=judging_scores&amp;action=print&amp;filter=scores&amp;view=winners" title="Results Report by Table (Winners Only with Scores)">Print (Winners Only)</a></li>
			</ul>
			<ul class="admin_default">
				<li>Results Report by <?php echo $method; ?> (without Scores):</li>
				<li><a id="modal_window_link" href="output/results.php?section=admin&amp;go=judging_scores&amp;action=print&amp;filter=none&amp;view=default" title="Results Report by Table (All with Scores)">Print (All)</a></li>
				<li><a id="modal_window_link" href="output/results.php?section=admin&amp;go=judging_scores&amp;action=print&amp;filter=none&amp;view=winners" title="Results Report by Table (Winners Only without Scores)">Print (Winners Only)</a></li>
				<li><a href="output/results_download.php?section=admin&amp;go=judging_scores&amp;action=default&amp;filter=none&amp;view=pdf">Download PDF (Winners Only)</a></li>
				<li><a href="output/results_download.php?section=admin&amp;go=judging_scores&amp;action=default&amp;filter=none&amp;view=html">Download HTML (Winners Only)</a></li>
			</ul>
			<ul class="admin_default">
				<li>BOS Round(s) Results Report:</li>
				<li><a id="modal_window_link" href="output/results.php?section=admin&amp;go=judging_scores_bos&amp;action=print&amp;filter=bos&amp;view=default" title="BOS Round(s) Results Report">Print</a></li>
				<li><a href="output/results_download.php?section=admin&amp;go=judging_scores_bos&amp;action=download&amp;filter=default&amp;view=pdf">Download PDF</a></li>
				<li><a href="output/results_download.php?section=admin&amp;go=judging_scores_bos&amp;action=download&amp;filter=default&amp;view=html">Download HTML</a></li>	
			</ul>
			<ul class="admin_default">
				<li>BJCP Judge/Steward/Staff Points Report:</li>
				<li><a id="modal_window_link" href="output/staff_points.php?section=admin&amp;go=judging_assignments&amp;action=download&amp;filter=default&amp;view=default" title="BJCP Judge/Steward/Staff Points Report">Print</a></li>
				<li><a href="output/staff_points.php?section=admin&amp;go=judging_assignments&amp;action=download&amp;filter=default&amp;view=pdf">Download PDF</a></li>
				<li><a href="output/staff_points.php?section=admin&amp;go=judging_assignments&amp;action=download&amp;filter=default&amp;view=xml">Download XML</a></li>
			</ul>
            <?php if ($row_scores['count'] > 0) { ?>
			<ul class="admin_default">
				<li>Award Labels (Avery 5160):</li>
				<li><a href="output/labels.php?section=admin&amp;go=judging_scores&amp;action=awards&amp;filter=default">Download PDF</a></li>
			</ul>
            <?php } } ?>
			<ul class="admin_default">
				<li>Participant Address Labels (Avery 5160):</li>
				<li><a href="output/labels.php?section=admin&amp;go=participants&amp;action=address_labels&amp;filter=default">Download PDF</a></li>
			</ul>
</div>
<?php } ?>
<h4 class="trigger"><span class="icon"><img src="images/page_go.png"  /></span>Exporting</h4>
<div class="toggle_container">
<p class="admin_default_header">Email Addresses (CSV Files)</p>
			<ul class="admin_default">
				<li><a href="output/email_export.php">All Participants</a></li>          
				<li><a href="output/email_export.php?section=admin&amp;go=csv&amp;filter=judges&amp;action=email">All Judges</a></li>
				<li><a href="output/email_export.php?section=admin&amp;go=csv&amp;filter=stewards&amp;action=email">All Stewards</a></li>
            </ul>
            <ul class="admin_default">
            	<li><a href="output/entries_export.php?section=admin&amp;go=csv&amp;action=email">All Entries</a></li>
				<li><a href="output/entries_export.php?section=admin&amp;go=csv&amp;filter=paid&amp;action=email">Paid & Received Entries</a><a href="output/email_export.php?section=admin&amp;filter=judges"></a></li>
				<li><a href="output/entries_export.php?section=admin&amp;go=csv&amp;filter=nopay&amp;action=email">Non-Paid & Received Entries</a></li>
			</ul>
  			
		<?php if ($row_prefs['prefsCompOrg'] == "N") { ?>
			<?php if ($totalRows_judging1 > 1) { ?>
			<ul class="admin_default">
				<li>Judges for Location:
        		<select name="judge_location" id="judge_location" onchange="jumpMenu('self',this,0)">
          			<option value=""></option>
          			<option value="output/email_export.php?section=all&amp;go=csv&amp;filter=judges&amp;">All Locations</option>
          			<?php do { ?>
          			<option value="output/email_export.php?section=loc&amp;go=csv&amp;filter=judges&amp;bid=<?php echo $row_judging1['id']; ?>"><?php echo $row_judging1['judgingLocName']." ("; echo date_convert($row_judging1['judgingDate'], 3, $row_prefs['prefsDateFormat']).")"; ?></option>
          			<?php } while ($row_judging1 = mysql_fetch_assoc($judging1)) ?>
          		</select>
    			</li>
				<li><em>* Be sure to make final <a href="index.php?section=admin&amp;action=assign&amp;go=judging&amp;filter=judges">judging</a> and <a href="index.php?section=admin&amp;action=assign&amp;go=judging&amp;filter=stewards">stewarding</a> location assignments before exporting.</em></li>
			</ul>
			<ul class="admin_default">
				<li>Stewards for Location:
        		<select name="judge_location" id="judge_location" onchange="jumpMenu('self',this,0)">
          			<option value=""></option>
         			<option value="output/email_export.php?section=all&amp;go=csv&amp;filter=stewards">All Locations</option>
          			<?php do { ?>
         			<option value="output/email_export.php?section=loc&amp;go=csv&amp;filter=stewards&amp;bid=<?php echo $row_stewarding['id']; ?>"><?php echo $row_stewarding['judgingLocName']." ("; echo date_convert($row_stewarding['judgingDate'], 3, $row_prefs['prefsDateFormat']).")"; ?></option>
          			<?php } while ($row_stewarding = mysql_fetch_assoc($stewarding)) ?>
          		</select>
    			</li>
				<li><em>* Be sure to make final <a href="index.php?section=admin&amp;action=assign&amp;go=judging&amp;filter=judges">judging</a> and <a href="index.php?section=admin&amp;action=assign&amp;go=judging&amp;filter=stewards">stewarding</a> location assignments before exporting.</em>    </li>
			</ul>
 			<?php } ?>
 		<?php } ?>
			<p class="admin_default_header">Tab Delimited Files</li>
			<p>For importing into the Homebrew Competition Coordination Program (HCCP), available for download <a href="http://www.folsoms.net/hccp/" target="_blank">here</a>. <?php if ($totalRows_judging1 > 1) { ?>The tab delimited file for <em>each location</em> should be imported into HCCP as it's own database. Refer to the <a href="http://www.folsoms.net/hccp/hccp.pdf" target="_blank">HCCP documentation</a> for import instructions.<?php } ?></p> 
			<ul class="admin_default">
				<li><a href="output/participants_export.php?section=admin&amp;go=tab">All Participants</a></li>
				<li><a href="output/entries_export.php?section=admin&amp;go=tab&amp;filter=paid">Paid & Received Entries</a></li>
				<li><a href="output/entries_export.php?section=admin&amp;go=tab">All Entries</a></li>
			</ul>
  		<?php if ($row_prefs['prefsCompOrg'] == "N") { ?>
  			<?php if ($totalRows_judging1 > 1) { ?>
			<ul class="admin_default">
				<li>Participants for Location:
        		<select name="tab_choice" id="tab_choice" onchange="jumpMenu('self',this,0)">
          			<option value=""></option>
          			<?php do { ?>
          			<option value="output/participants_export.php?section=loc&amp;go=tab&amp;bid=<?php echo $row_stewarding2['id']; ?>"><?php echo $row_stewarding2['judgingLocName']." ("; echo date_convert($row_stewarding2['judgingDate'], 3, $row_prefs['prefsDateFormat']).")"; ?></option>
          			<?php } while ($row_stewarding2 = mysql_fetch_assoc($stewarding2)) ?>
        		</select>
    			</li>
				<li><em>* Be sure to make final <a href="/index.php?section=admin&amp;go=styles&amp;filter=judging">style</a>, <a href="index.php?section=admin&amp;action=assign&amp;go=judging&amp;filter=judges">judging</a> and <a href="index.php?section=admin&amp;action=assign&amp;go=judging&amp;filter=stewards">stewarding</a> location assignments before exporting.</em></li>
			</ul>
			<ul class="admin_default">
				<li>Paid and Received Entries for Location:
        		<select name="tab_choice" id="tab_choice" onchange="jumpMenu('self',this,0)">
          			<option value=""></option>
          			<?php do { ?>
          			<option value="output/entries_export.php?section=loc&amp;go=tab&amp;filter=paid&amp;bid=<?php echo $row_judging2['id']; ?>"><?php echo $row_judging2['judgingLocName']." ("; echo date_convert($row_judging2['judgingDate'], 3, $row_prefs['prefsDateFormat']).")"; ?></option>
          			<?php } while ($row_judging2 = mysql_fetch_assoc($judging2)) ?>
        		</select>
    			</li>
			</ul>   
  			<?php } ?>
  		<?php } ?>
			<p class="admin_default_header">CSV Files</p>
			<ul class="admin_default">
				<li><a href="output/entries_export.php?section=admin&amp;go=csv">All Entries</a> (Limited Data)</li>
                <li><a href="output/entries_export.php?section=admin&amp;go=csv&amp;action=all&amp;filter=all">All Entries</a> (All Data)</li>
            </ul>
			<ul class="admin_default">
				<li><a href="output/entries_export.php?section=admin&amp;go=csv&amp;filter=paid">Paid & Received Entries</a> (Limited Data)</li>
				<li><a href="output/entries_export.php?section=admin&amp;go=csv&amp;filter=nopay&amp;action=hccp">Non-Paid & Received Entries</a><a href="output/entries_export.php?go=csv"></a> (Limited Data)</li>
			</ul>
			<ul class="admin_default">
				<li><a href="output/participants_export.php?section=admin&amp;go=csv">All Participants</a></li>
				<li><a href="output/entries_export.php?section=admin&amp;go=csv&amp;filter=winners">Winners</a></li>
			</ul>
			<p class="admin_default_header">Promo Materials</p>
			<ul class="admin_default">
				<li><a href="output/promo_export.php?section=admin&amp;go=html&amp;action=html">HTML</a> (.html)</li>
				<li><a href="output/promo_export.php?section=admin&amp;go=word&amp;action=word">Word</a> (.doc)</li>
                <li><a href="output/promo_export.php?section=admin&amp;go=word&amp;action=bbcode">Bulletin Board Code (BBC)</a> (.txt)</li>
			</ul>
		</div>
		<h4 class="trigger"><span class="icon"><img src="images/camera_add.png" /></span>Archiving</h4>
		<div class="toggle_container">
			<p class="admin_default_header">Manage/View</p>
			<ul class="admin_default">
				<li><a href="index.php?section=admin&amp;go=archive">Archives</a></li>
			</ul>
		</div>
	</div>
</div>
</div>
<?php 	} 
if ($go == "contest_info") 				include (ADMIN.'competition_info.admin.php');
if ($go == "preferences") 				include (ADMIN.'site_preferences.admin.php');
if ($go == "judging") 	    			include (ADMIN.'judging_locations.admin.php');
if ($go == "judging_preferences") 	    include (ADMIN.'judging_preferences.admin.php');
if ($go == "judging_tables") 	    	include (ADMIN.'judging_tables.admin.php');
if ($go == "judging_flights") 	    	include (ADMIN.'judging_flights.admin.php');
if ($go == "judging_scores") 	    	include (ADMIN.'judging_scores.admin.php');
if ($go == "judging_scores_bos")    	include (ADMIN.'judging_scores_bos.admin.php');
if ($go == "participants") 				include (ADMIN.'participants.admin.php');
if ($go == "entries") 					include (ADMIN.'entries.admin.php');
if ($go == "make_admin") 				include (ADMIN.'make_admin.admin.php');
if ($go == "archive") 	    			include (ADMIN.'archive.admin.php');
if ($go == "sponsors") 	   			 	include (ADMIN.'sponsors.admin.php');
if ($go == "contacts") 	    			include (ADMIN.'contacts.admin.php');
if ($go == "styles") 	    			include (ADMIN.'styles.admin.php');
if ($go == "style_types")    			include (ADMIN.'style_types.admin.php');
if ($go == "dropoff") 	    			include (ADMIN.'dropoff.admin.php');
if ($go == "special_best") 	    		include (ADMIN.'special_best.admin.php');
if ($go == "special_best_data") 	    include (ADMIN.'special_best_data.admin.php');
if (($action == "register") && ($go == "judge")) 	include (SECTIONS.'register.sec.php');
if (($action == "register") && ($go == "entrant")) 	include (SECTIONS.'register.sec.php');
}
else echo "<div class=\"error\">You do not have sufficient privileges to access this area.</div>";
?>
