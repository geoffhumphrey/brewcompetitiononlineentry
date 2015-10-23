<?php 
/**
 * Module:      default.admin.php
 * Description: This module houses links to all administration functions.
 *
 */
include(LIB.'admin.lib.php');
include(DB.'admin_common.db.php');
include(DB.'judging_locations.db.php'); 
include(DB.'stewarding.db.php'); 
include(DB.'dropoff.db.php'); 
include(DB.'contacts.db.php');
include(INCLUDES.'form_check.inc.php'); 

if ($go == "default") {
	$totalRows_entry_count = total_paid_received("default","default");
	$entries_unconfirmed = ($totalRows_entry_count - $totalRows_log_confirmed);
}

if ($check_judging_flights) {
	include(PROCESS.'process_judging_flight_check.inc.php'); 
}


/*
// Future use for granular BOS mats and pullsheets.

function style_types_bos_print($file_name,$section,$go,$view) {
	
	require(CONfIG.'config.php');
	$query_style_types = sprintf("SELECT id FROM %s", $prefix."style_types");
	$style_types = mysql_query($query_style_types, $brewing) or die(mysql_error());
	$row_style_types = mysql_fetch_assoc($style_types);
	
	$return = '';
	
	do {
		
		if ($row_style_type['styleTypeBOS'] == "Y") {
			
			$return .= '<li>';
			$return .= '<a id="modal_window_link" href="'.$base_url.'output/'.$file_name.'?section='.$section.'&amp;go='.$go.'&amp;id='.$row_style_type['id'].'"  title="Print">';
			$return .= $row_style_type['styleTypeName'];;
			$return .= '</a>';
			$return .= '</li>';
			
		}
		
	} while ($row_style_type = mysql_fetch_assoc($style_type));
	
}

*/

?>
<script type="text/javascript" language="javascript">
function toggleChecked(status) {
	$(".checkbox").each( function() {
		$(this).attr("checked",status);
	})
}
</script>
<div id="header">	
	<div id="header-inner"><h1><?php echo $header_output; ?></h1></div>
</div>
<?php 

/*
echo "Tables: ".$query_tables."<br>";
echo "Tables Edit: ".$query_tables_edit."<br>";
echo "Style Type: ".$query_style_type."<br>";
echo "Total tables: ".$totalRows_tables;
*/

if ($purge == "cleanup") echo "<div class='error'>Data clean-up completed.</div>"; 
if ($_SESSION['prefsUseMods'] == "Y") include(INCLUDES.'mods_top.inc.php');
if (($setup_free_access == TRUE) && ($action != "print")) echo "<div class='error'>The &#36;setup_free_access variable in config.php is currently set to TRUE. For security reasons, the setting should returned to FALSE. You will need to edit config.php directly and re-upload to your server to do this.</div>";
if (($action != "print") && ($msg != "default")) echo $msg_output; 
if (($action != "print") && ($go != "default")) echo admin_help($go,$header_output,$action,$filter);
if ($_SESSION['userLevel'] <= "1") {
			if (($totalRows_dropoff == "0") && ($go == "default")) echo "<div class='error' style='margin-top: 15px;'>No drop-off locations have been specified. <a href=\"index.php?section=admin&amp;action=add&amp;go=dropoff\">Add a drop-off location</a>?</div>";
			if (($totalRows_judging == "0") && ($go == "default")) echo "<div class='error' style='margin-top: 15px;'>No judging dates/locations have been specified. <a href=\"index.php?section=admin&amp;action=add&amp;go=judging\">Add a judging location</a>?</div>";
			if (($totalRows_contact == "0") && ($go == "default")) echo "<div class='error' style='margin-top: 15px;'>No competition contacts have been specified. <a href=\"index.php?section=admin&amp;action=add&amp;go=contacts\">Add a competition contact</a>?</div>";
			
if ($go == "default") { ?>
<script type="text/javascript" src="<?php echo $base_url; ?>js_includes/toggle.js"></script>
<?php if ($fx) { ?>
<div class="warning">There is a known issue with printing from the Firefox browser. To print all pages properly, RIGHT CLICK on the print link and choose "Open Link in New Tab." Then, use Firefox's native printing function (Edit > Print) to print your documents. Be aware that you should use the browser's File > Page Setup... function to specify portrait or landscape, margins, etc.</div>
<?php } ?>
<?php 
if (($totalRows_log  > 0) && ($_SESSION['prefsStyleSet'] == "BJCP2008") && ($_SESSION['userLevel'] == 0)) {
	
	include(DB.'admin_judging_tables.db.php');
	
	$query_flights = sprintf("SELECT id FROM %s", $judging_flights_db_table);
	$flights = mysql_query($query_flights, $brewing) or die(mysql_error());
	$totalRows_flights = mysql_num_rows($flights);
	
	if (($totalRows_table_number == 0) && ($totalRows_flights == 0)) {
?>
<div class="info">Your current style set is BJCP 2008. Do you want to <a href="<?php echo $base_url."includes/process.inc.php?action=convert_bjcp"; ?>" onclick="return confirm('Are you sure? This action will convert all entries in the database to conform to the BJCP 2015 style guidelines. Categories will be 1:1 where possible, however some specialty styles may need to be updated by the entrant.');">convert all entries to BJCP 2015</a>? You must do this <em>before</em> defining tables.</div>
<?php } 
} ?>
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
        <td class="data"><?php echo $currency_symbol.$total_fees; ?></td>
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
        <td class="data"><?php echo $currency_symbol.$total_fees_paid; ?></td>
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
        <td class="dataLabel" colspan="2">Entry Counts by: <a href="<?php echo $base_url; ?>index.php?section=admin&amp;go=count_by_style">Style</a> or <a href="<?php echo $base_url; ?>index.php?section=admin&amp;go=count_by_substyle">Sub-Style</a></td>
        <td class="dataLabel"><a href="<?php echo $base_url; ?>index.php?section=admin&amp;go=participants&amp;filter=stewards">Available Stewards</a>:</td>
        <td class="data"><?php echo get_participant_count('steward'); ?></td>
	</tr>
    <?php if ((!empty($_SESSION['contestEntryFeePassword'])) && (!empty($_SESSION['contestEntryFeePasswordNum']))) { ?>
    <tr>
        <td class="dataLabel">Participants Who Redeemed Discount:</td>
        <td class="data"><?php $a = explode("^",total_discount()); echo $a[0]; ?></td>
        <td class="dataLabel">Total Discounted Entries:</td>
        <td class="data"><?php echo $a[1]; ?></td>
        <td class="dataLabel">Total Fees at Discount:</td>
        <td class="data"><?php echo $currency_symbol.($a[1] * $_SESSION['contestEntryFeePasswordNum']); ?></td>
    </tr>
    <?php } ?>
</table>
</div>
<div id="menu_container">
<div id="outer">

<p>Click the headings below to expand and collapse each category.</p>
	<div class="menus">
	  <h4 class="trigger"><span class="icon"><img src="<?php echo $base_url; ?>images/help.png"  /></span>Help</h4>
        <div class="toggle_container">
        	<p class="admin_default_header">Quick Links</p>
            <?php if ($_SESSION['userLevel'] == "0") { ?>
        	<ul class="admin_default">
            	<li><a href="http://help.brewcompetition.com/files/whatsnew.html" title="Help" id="modal_window_link">What&rsquo;s New in Version <?php echo $current_version; ?>?</a></li>
				<li><a href="http://help.brewcompetition.com/files/gone_through.html" title="Help" id="modal_window_link">I've Gone Through Set Up, What Do I Do Now?</a></li>
            </ul>
            <ul class="admin_default">
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
        
        
        
        <?php if ($_SESSION['userLevel'] == "0") { ?>
        
        
        
        <!-- DB Maintenance -->
		<h4 class="trigger"><span class="icon"><img src="<?php echo $base_url; ?>images/wand.png"  /></span>Database Maintenance</h4>
		<div class="toggle_container">
			<p class="admin_default_header">Data Integrity</p>
			<ul class="admin_default">
				<li><a href="<?php echo $base_url; ?>includes/data_cleanup.inc.php?action=cleanup" onclick="return confirm('Are you sure? This will check the database for duplicate entries, duplicate scores for a single entry, users without associated personal data [no first name, no last name], etc.');">Perform Data Clean-Up</a></li>
			</ul>
            <p class="admin_default_header">Confirm or Purge Unconfirmed Entries</p>
			<ul class="admin_default">
				<li><a href="<?php echo $base_url; ?>includes/process.inc.php?action=confirmed&amp;dbTable=<?php echo $brewing_db_table; ?>" onclick="return confirm('Are you sure? This will mark ALL entries as confirmed and could be a large pain to undo.');">Confirm All</a></li>
                <li><a href="<?php echo $base_url; ?>includes/data_cleanup.inc.php?action=purge&amp;go=unconfirmed" onclick="return confirm('Are you sure? This will delete ALL unconfirmed entries and/or entries without special ingredients/classic style info that require them from the database - even those that are less than 24 hours old. This cannot be undone.');">Purge All</a></li>
			</ul>
            <p class="admin_default_header">Purge Data</p>
			<ul class="admin_default">
				<li><a href="<?php echo $base_url; ?>includes/data_cleanup.inc.php?action=purge&amp;go=entries" onclick="return confirm('Are you sure you want to delete all entries and associated data including scores and bos scores? This cannot be undone.');">Entries</a></li>
                <li><a href="<?php echo $base_url; ?>includes/data_cleanup.inc.php?action=purge&amp;go=participants" onclick="return confirm('Are you sure you want to delete all non-admin participants and associated data? This cannot be undone.');">Participants</a></li>
                <li><a href="<?php echo $base_url; ?>includes/data_cleanup.inc.php?action=purge&amp;go=tables" onclick="return confirm('Are you sure you want to delete all judging tables and associated data including judging/stewarding table assignments? This cannot be undone.');">Judging Tables</a></li>
                <li><a href="<?php echo $base_url; ?>includes/data_cleanup.inc.php?action=purge&amp;go=scores" onclick="return confirm('Are you sure you want to delete all scoring data from the database including best of show? This cannot be undone.');">Scores</a></li>
                <li><a href="<?php echo $base_url; ?>includes/data_cleanup.inc.php?action=purge&amp;go=custom" onclick="return confirm('Are you sure you want to delete all custom winning categories and associated data? This cannot be undone.');">Custom Winning Categories</a></li>
            </ul>
            <ul class="admin_default">
				<li><a href="<?php echo $base_url; ?>includes/data_cleanup.inc.php?action=purge&amp;go=purge-all" onclick="return confirm('Are you sure you want to delete entry, participant, judging table, score, and custom winner category data? This cannot be undone.');">Purge All of the Above</a></li>
            </ul>
		</div>
        
        <!-- Preferences -->
        <h4 class="trigger"><span class="icon"><img src="<?php echo $base_url; ?>images/cog.png"  /></span>Defining Preferences</h4>
		<div class="toggle_container">
			<p class="admin_default_header">Define</p>
			<ul class="admin_default">
				<li><a href="<?php echo $base_url; ?>index.php?section=admin&amp;go=preferences">Site Preferences</a></li>
    			<li><a href="<?php echo $base_url; ?>index.php?section=admin&amp;go=judging_preferences">Competition Organization Preferences</a></li>
			</ul>
		</div>
        <?php } ?>
      
        <?php if (($_SESSION['prefsUseMods'] == "Y") && (!HOSTED)) { ?>
        <!-- Custom Modules -->
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
        
		<h4 class="trigger"><span class="icon"><img src="<?php echo $base_url; ?>images/wrench.png"  /></span>Preparing</h4>
		<div class="toggle_container">
        	<p class="admin_default_header">Manage/View</p>
            <?php if ($_SESSION['userLevel'] == "0") { ?>
			<ul class="admin_default">
            	<li><a href="<?php echo $base_url; ?>index.php?section=admin&amp;go=style_types">Style Types</a></li>
			    <li><a href="<?php echo $base_url; ?>index.php?section=admin&amp;go=styles">Accepted Style Categories</a></li>   
			</ul>
            <ul class="admin_default">
            	<li><a href="<?php echo $base_url; ?>index.php?section=admin&amp;go=special_best">Custom Winning Categories</a></li>
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
        	<p class="admin_default_header">Manage/View</p>
        	<?php if ($_SESSION['userLevel'] == "0") { ?>
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
            <p class="admin_default_header">Add</p>
			<ul class="admin_default">
			    <li><a href="<?php echo $base_url; ?>index.php?section=admin&amp;go=entrant&amp;action=regiser">A Participant</a></li>
			    <li><a href="<?php echo $base_url; ?>index.php?section=admin&amp;go=judge&amp;action=register">A Participant as a Judge/Steward</a></li>
			    <li>An Entry For: <?php echo participant_choose($brewer_db_table); ?></li>
			</ul>
          	<?php if (!NHC) { ?>
        	<p class="admin_default_header">Regenerate</p>
			<ul class="admin_default">
            	<li class="admin_default_label">Entry Judging Numbers:</li>
                <li>
                <div class="menuBar">
                <a class="menuButton" href="#" onclick="#" onmouseover="buttonMouseover(event, 'menu_generate');">Key Off Of... (Select One)</a>
                <div id="menu_generate" class="menu" onmouseover="menuMouseover(event)">
                <a class="menuItem" onclick="return confirm('Are you sure you want to regenerate judging numbers for all entries?<?php if ($_SESSION['prefsEntryForm'] == "N") echo " THIS WILL OVER-WRITE *ALL* JUDGING NUMBERS, including those that have been assigned via the barcode scanning function."; ?>');" href="<?php echo $base_url; ?>includes/process.inc.php?section=admin&amp;go=default&amp;action=generate_judging_numbers&amp;sort=id&amp;dir=ASC">Entry Number (Ascending)</a>
                <a class="menuItem" onclick="return confirm('Are you sure you want to regenerate judging numbers for all entries?<?php if ($_SESSION['prefsEntryForm'] == "N") echo " THIS WILL OVER-WRITE *ALL* JUDGING NUMBERS, including those that have been assigned via the barcode scanning function."; ?>');" href="<?php echo $base_url; ?>includes/process.inc.php?section=admin&amp;go=default&amp;action=generate_judging_numbers&amp;sort=id&amp;dir=DESC">Entry Number (Descending)</a>
                <a class="menuItem" onclick="return confirm('Are you sure you want to regenerate judging numbers for all entries?<?php if ($_SESSION['prefsEntryForm'] == "N") echo " THIS WILL OVER-WRITE *ALL* JUDGING NUMBERS, including those that have been assigned via the barcode scanning function."; ?>');" href="<?php echo $base_url; ?>includes/process.inc.php?section=admin&amp;go=default&amp;action=generate_judging_numbers&amp;sort=brewName&amp;dir=ASC">Entry Name (Ascending)</a>
                <a class="menuItem" onclick="return confirm('Are you sure you want to regenerate judging numbers for all entries?<?php if ($_SESSION['prefsEntryForm'] == "N") echo " THIS WILL OVER-WRITE *ALL* JUDGING NUMBERS, including those that have been assigned via the barcode scanning function."; ?>');" href="<?php echo $base_url; ?>includes/process.inc.php?section=admin&amp;go=default&amp;action=generate_judging_numbers&amp;sort=brewName&amp;dir=DESC">Entry Name (Descending)</a>
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
            	<li class="admin_default_label">Sorting Sheets:</li>
                <li><a id="modal_window_link" href="<?php echo $base_url; ?>output/sorting.php?section=admin&amp;go=default&amp;filter=default">All Categories</a></li>
                <li><a id="modal_window_link" href="<?php echo $base_url; ?>output/sorting.php?section=admin&amp;go=default&amp;filter=default&amp;view=entry">All Categories</a> (Entry Numbers Only)</li>
            </ul>
            <ul class="admin_default">
            	<li class="admin_default_label">Entry Number / Judging Number Cheat Sheets:</li>
				<li><a id="modal_window_link" href="<?php echo $base_url; ?>output/sorting.php?section=admin&amp;go=cheat&amp;filter=default">All Categories</a></li>
            </ul>
			<ul class="admin_default">
				<li class="admin_default_label">Bottle Labels (Using <em>Entry</em> Numbers):</li>
			    <li><a href="<?php echo $base_url; ?>output/labels.php?section=admin&amp;go=entries&amp;action=bottle-entry&amp;filter=default&amp;psort=5160">Avery 5160 (Letter)</a></li>
                <li><a href="<?php echo $base_url; ?>output/labels.php?section=admin&amp;go=entries&amp;action=bottle-entry&amp;filter=default&amp;psort=3422">Avery 3422 (A4)</a></li>
			</ul>
            
            <ul class="admin_default">
				<li class="admin_default_label">Bottle Labels (Using <em>Judging</em> Numbers):</li>
			    <li><a href="<?php echo $base_url; ?>output/labels.php?section=admin&amp;go=entries&amp;action=bottle-judging&amp;filter=default&amp;psort=5160">Avery 5160 (Letter)</a></li>
                <li><a href="<?php echo $base_url; ?>output/labels.php?section=admin&amp;go=entries&amp;action=bottle-judging&amp;filter=default&amp;psort=3422">Avery 3422 (A4)</a></li>
			</ul>
            <?php } ?>
            <?php if (!NHC) { ?>
            <ul class="admin_default">
				<li class="admin_default_label">Bottle Labels with Special Ingredients, Mead/Cider Info (Using <em>Entry</em> Numbers):</li>
            </ul>
            <ul class="admin_default">
			    <li>Avery 5160 (Letter) - 
                <select name="special_labels" id="special_labels" onchange="jumpMenu('self',this,0)">
                	<option value=""></option>
                    <?php for($i=1; $i<=6; $i++) { ?>
                    <option value="<?php echo $base_url; ?>output/labels.php?section=admin&amp;go=entries&amp;action=bottle-entry&amp;filter=default&amp;view=special&amp;&amp;psort=5160&amp;sort=<?php echo $i; ?>"><?php echo $i; ?></option>
                    <?php } ?>
                </select> label(s) per entry
                </li>
                <li>Avery 3422 (A4) -
                <select name="special_labels" id="special_labels" onchange="jumpMenu('self',this,0)">
                	<option value=""></option>
                    <?php for($i=1; $i<=6; $i++) { ?>
                    <option value="<?php echo $base_url; ?>output/labels.php?section=admin&amp;go=entries&amp;action=bottle-entry&amp;filter=default&amp;view=special&amp;&amp;psort=3422&amp;sort=<?php echo $i; ?>"><?php echo $i; ?></option>
                    <?php } ?>
                </select> label(s) per entry
                </li>
			</ul>
            <?php } ?>
            <ul class="admin_default">
				<li class="admin_default_label">Bottle Labels with Special Ingredients, Mead/Cider Info (Using <em>Judging</em> Numbers):</li>
            </ul>
            <ul class="admin_default">
			    <li>Avery 5160 -
                <select name="special_labels" id="special_labels" onchange="jumpMenu('self',this,0)">
                	<option value=""></option>
                    <?php for($i=1; $i<=6; $i++) { ?>
                    <option value="<?php echo $base_url; ?>output/labels.php?section=admin&amp;go=entries&amp;action=bottle-judging&amp;filter=default&amp;view=special&amp;&amp;psort=5160&amp;sort=<?php echo $i; ?>"><?php echo $i; ?></option>
                    <?php } ?>
                </select> label(s) per entry
                </li>
                <li>Avery 3422 (A4) -
                <select name="special_labels" id="special_labels" onchange="jumpMenu('self',this,0)">
                	<option value=""></option>
                    <?php for($i=1; $i<=6; $i++) { ?>
                    <option value="<?php echo $base_url; ?>output/labels.php?section=admin&amp;go=entries&amp;action=bottle-judging&amp;filter=default&amp;view=special&amp;&amp;psort=3422&amp;sort=<?php echo $i; ?>"><?php echo $i; ?></option>
                    <?php } ?>
                </select> label(s) per entry
                </li>
			</ul>
            <?php if (!NHC) { ?>
            <ul class="admin_default">
            	<li class="admin_default_label">Round Bottle Labels (Using <em>Entry</em> Numbers):</li>
            </ul>
            <ul class="admin_default">
                <li><a href="http://www.onlinelabels.com/Products/OL32.htm" target="_blank">0.50 Inch Labels</a>, All Categories: <select name="round_entry" id="round_entry" onchange="jumpMenu('self',this,0)">
                	<option value=""></option>
                    <?php for($i=1; $i<=12; $i++) { ?>
                    <option value="<?php echo $base_url; ?>output/labels.php?section=admin&amp;go=entries&amp;action=bottle-entry-round&amp;filter=default&amp;sort=<?php echo $i; ?>&amp;psort=OL32"><?php echo $i; ?></option>
                    <?php } ?>                    
                </select> label(s) per entry</li>
                <li>Entries Added by Admins (After Registration Close): 
                  <select name="round_entry_recent" id="round_entry_recent" onchange="jumpMenu('self',this,0)">
                	<option value=""></option>
                    <?php for($i=1; $i<=12; $i++) { ?>
                    <option value="<?php echo $base_url; ?>output/labels.php?section=admin&amp;go=entries&amp;action=bottle-entry-round&amp;filter=recent&amp;sort=<?php echo $i; ?>&amp;psort=OL32"><?php echo $i; ?></option>
                    <?php } ?>
                </select> label(s) per entry</li>
            </ul>
            <ul class="admin_default">
                <li><a href="http://www.onlinelabels.com/Products/OL5275WR.htm" target="_blank"> 0.75 Inch Labels</a>, All Categories: <select name="round_entry" id="round_entry" onchange="jumpMenu('self',this,0)">
                	<option value=""></option>
                    <?php for($i=1; $i<=12; $i++) { ?>
                    <option value="<?php echo $base_url; ?>output/labels.php?section=admin&amp;go=entries&amp;action=bottle-entry-round&amp;filter=default&amp;sort=<?php echo $i; ?>&amp;psort=OL5275WR"><?php echo $i; ?></option>
                    <?php } ?>
                </select> label(s) per entry</li>
                <li>Entries Added by Admins (After Registration Close): 
                  <select name="round_entry_recent" id="round_entry_recent" onchange="jumpMenu('self',this,0)">
                	<option value=""></option>                    
                    <?php for($i=1; $i<=12; $i++) { ?>
                    <option value="<?php echo $base_url; ?>output/labels.php?section=admin&amp;go=entries&amp;action=bottle-entry-round&amp;filter=recent&amp;sort=<?php echo $i; ?>&amp;psort=OL5275WR"><?php echo $i; ?></option>
                    <?php } ?>
                </select> label(s) per entry</li>
            </ul>
            <?php } ?>
            <ul class="admin_default">
            	<li class="admin_default_label">Round Bottle Labels (Using <em>Judging</em> Numbers):</li>
            </ul>
            <ul class="admin_default">
                <li><a href="http://www.onlinelabels.com/Products/OL32.htm" target="_blank">0.50 Inch Labels</a>, All Categories: <select name="round_judging" id="round_judging" onchange="jumpMenu('self',this,0)">
                	<option value=""></option>
                    <?php for($i=1; $i<=12; $i++) { ?>
                    <option value="<?php echo $base_url; ?>output/labels.php?section=admin&amp;go=entries&amp;action=bottle-judging-round&amp;filter=default&amp;sort=<?php echo $i; ?>&amp;psort=OL32"><?php echo $i; ?></option>
                    <?php } ?>                    
                </select> label(s) per entry</li>
                <li>Entries Added by Admins (After Registration Close): 
                  <select name="round_judging_recent" id="round_judging_recent" onchange="jumpMenu('self',this,0)">
                	<option value=""></option>
                    <?php for($i=1; $i<=12; $i++) { ?>
                    <option value="<?php echo $base_url; ?>output/labels.php?section=admin&amp;go=entries&amp;action=bottle-judging-round&amp;filter=recent&amp;sort=<?php echo $i; ?>&amp;psort=OL32"><?php echo $i; ?></option>
                    <?php } ?>
                </select> label(s) per entry</li>
            </ul>
            <ul class="admin_default">
                <li><a href="http://www.onlinelabels.com/Products/OL5275WR.htm" target="_blank">0.75 Inch Labels</a>, All Categories: <select name="round_judging" id="round_judging" onchange="jumpMenu('self',this,0)">
                	<option value=""></option>
                    <?php for($i=1; $i<=12; $i++) { ?>
                    <option value="output/labels.php?section=admin&amp;go=entries&amp;action=bottle-judging-round&amp;filter=default&amp;sort=<?php echo $i; ?>&amp;psort=OL5275WR"><?php echo $i; ?></option>
                    <?php } ?>
                </select> label(s) per entry</li>
                <li>Entries Added by Admins (After Registration Close): 
                  <select name="round_judging_recent" id="round_judging_recent" onchange="jumpMenu('self',this,0)">
                	<option value=""></option>                    
                    <?php for($i=1; $i<=12; $i++) { ?>
                    <option value="<?php echo $base_url; ?>output/labels.php?section=admin&amp;go=entries&amp;action=bottle-judging-round&amp;filter=recent&amp;sort=<?php echo $i; ?>&amp;psort=OL5275WR"><?php echo $i; ?></option>
                    <?php } ?>
                </select> label(s) per entry</li>
            </ul>
             <ul class="admin_default">
            	<li class="admin_default_label">Round Bottle Labels (Category Number and Subcategory Letter Only):</li>
             </ul>
            <ul class="admin_default">
                <li><a href="http://www.onlinelabels.com/Products/OL32.htm" target="_blank">0.50 Inch Labels</a>, All Categories: <select name="round_judging" id="round_judging" onchange="jumpMenu('self',this,0)">
                	<option value=""></option>
                    <?php for($i=1; $i<=12; $i++) { ?>
                    <option value="<?php echo $base_url; ?>output/labels.php?section=admin&amp;go=entries&amp;action=bottle-category-round&amp;filter=default&amp;sort=<?php echo $i; ?>&amp;psort=OL32"><?php echo $i; ?></option>
                    <?php } ?>
                </select> label(s) per entry
             	</li>
            </ul>
            <ul class="admin_default">
             	<li><a href="http://www.onlinelabels.com/Products/OL5275WR.htm" target="_blank"> 0.75 Inch Labels</a>, All Categories: <select name="round_judging" id="round_judging" onchange="jumpMenu('self',this,0)">
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
				<li class="admin_default_label">Scores For:</li>
				<li><?php echo score_table_choose($dbTable,$judging_tables_db_table,$judging_scores_db_table); ?></li>
            </ul>
            <?php if ($_SESSION['userLevel'] == "0") { ?>
            <ul class="admin_default">
				<li class="admin_default_label">Winners for Custom Winning Category:</li>
				<li><?php echo score_custom_winning_choose($special_best_info_db_table,$special_best_data_db_table); ?></li>
            </ul>
            <?php } ?>
            </div>
            <h4 class="trigger"><span class="icon"><img src="<?php echo $base_url; ?>images/printer.png"  /></span>Printing and Reporting</h4>
            <div class="toggle_container">
			<p class="admin_default_header">Before Judging</p>
            <ul class="admin_default">
				<li class="admin_default_label">Print Drop Off Location Reports:</li>
                <li><a id="modal_window_link" href="<?php echo $base_url; ?>output/dropoff.php"  title="Print Total Entries by Location">Entry Totals by Drop-Off Location</a></li>
                <li><a id="modal_window_link" href="<?php echo $base_url; ?>output/dropoff.php?section=check"  title="Print Entries By Drop-Off Location">List of Entries By Drop-Off Location</a></li>
            </ul>
			<?php if ($totalRows_tables > 0) { ?>
            <?php if (!NHC) { ?>
            <ul class="admin_default">
				<li class="admin_default_label">Print Pullsheets (Using <em>Entry</em> Numbers):</li>
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
				<li class="admin_default_label">Print Pullsheets (Using <em>Judging</em> Numbers):</li>
				<li><a id="modal_window_link" href="<?php echo $base_url; ?>output/pullsheets.php?section=admin&amp;go=judging_tables&amp;id=default" title="Print All Table Pullsheets">All Tables</a></li>
				<li><?php echo table_choose($section,"judging_tables",$action,$filter,$view,"output/pullsheets.php","thickbox"); ?></li>
                <li>
                    <div class="menuBar"><a class="menuButton" href="#" onclick="#" onmouseover="buttonMouseover(event, 'pullsheetsloc_judging');">For Location...</a></div>
                    <div id="pullsheetsloc_judging" class="menu" onmouseover="menuMouseover(event)">
                     <?php do { 
					 	for ($round=1; $round <= $row_judging1['judgingRounds']; $round++) { 
					 		$location_date = getTimeZoneDateTime($_SESSION['prefsTimeZone'], $row_judging1['judgingDate'], $_SESSION['prefsDateFormat'], $_SESSION['prefsTimeFormat'], "long", "date-time-no-gmt");
					 ?>
                    <a id="modal_window_link" class="menuItem" style="font-size: .9em; padding: 1px;" href="<?php echo $base_url; ?>output/pullsheets.php?section=admin&amp;go=judging_locations&amp;location=<?php echo $row_judging1['id']?>&amp;round=<?php echo $round; ?>" title="Print Pullsheet for Location <?php echo $row_judging1['judgingLocName'] . " - " . $location_date. ", Round " . $round; ?>"><?php echo $row_judging1['judgingLocName'] . " - " . $location_date. ", Round " . $round; ?></a>
                    <?php }
					} while ($row_judging1 = mysql_fetch_assoc($judging1)); 
					?>
                    </div>
                </li>
			</ul>
            <ul class="admin_default">
				<li class="admin_default_label">Print Table Cards:</li>
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
				<li class="admin_default_label">Print Judge Assignments:</li>
				<li><a id="modal_window_link" href="<?php echo $base_url; ?>output/assignments.php?section=admin&amp;go=judging_assignments&amp;filter=judges&amp;view=name" title="Print Judge Assignments by Name">By Judge Last Name</a></li>
				<li><a id="modal_window_link" href="<?php echo $base_url; ?>output/assignments.php?section=admin&amp;go=judging_assignments&amp;filter=judges&amp;view=table" title="Print Judge Assignments by Table">By Table</a></li>
   				<?php if ($totalRows_judging > 1) { ?>
				<li><a id="modal_window_link" href="<?php echo $base_url; ?>output/assignments.php?section=admin&amp;go=judging_assignments&amp;filter=judges&amp;view=location" title="Print Judge Assignments by Location">By Location</a></li>
    			<?php } ?>
			</ul>
			<ul class="admin_default">
				<li class="admin_default_label">Print Steward Assignments:</li>
				<li><a id="modal_window_link" href="<?php echo $base_url; ?>output/assignments.php?section=admin&amp;go=judging_assignments&amp;filter=stewards&amp;view=name" title="Print Steward Assignments by Name">By Steward Last Name</a></li>
				<li><a id="modal_window_link" href="<?php echo $base_url; ?>output/assignments.php?section=admin&amp;go=judging_assignments&amp;filter=stewards&amp;view=table" title="Print Steward Assignments by Table">By Table</a></li>
    			<?php if ($totalRows_judging > 1) { ?>
				<li><a id="modal_window_link" href="<?php echo $base_url; ?>output/assignments.php?section=admin&amp;go=judging_assignments&amp;filter=stewards&amp;view=location" title="Print Steward Assignments by Location">By Location</a></li>
				<?php } ?>
			</ul>
            <?php } ?>
			<ul class="admin_default">
				<li class="admin_default_label">Print Sign-in Sheets:</li>
				<li><a id="modal_window_link" href="<?php echo $base_url; ?>output/assignments.php?section=admin&amp;go=judging_assignments&amp;filter=judges&amp;view=sign-in" title="Print a Judge Sign-in Sheet">Judges</a></li>   
				<li><a id="modal_window_link" href="<?php echo $base_url; ?>output/assignments.php?section=admin&amp;go=judging_assignments&amp;filter=stewards&amp;view=sign-in" title="Print a Steward Sign-in Sheet">Stewards</a></li>   
			</ul>
            <ul class="admin_default">
				<li class="admin_default_label">Judge Scoresheet Labels:</li>
            	<li><a href="<?php echo $base_url; ?>output/labels.php?section=admin&go=participants&action=judging_labels&amp;psort=5160">Avery 5160 (Letter)</a> 30 labels per judge</li>
                <li><a href="<?php echo $base_url; ?>output/labels.php?section=admin&go=participants&action=judging_labels&amp;psort=3422">Avery 3422 (A4)</a> 24 labels per judge</li>
            </ul>
            <ul class="admin_default">
				<li class="admin_default_label">Judge, Steward and Staff Name Tags:</li>
            	<li><a href="<?php echo $base_url; ?>output/labels.php?section=admin&go=participants&action=judging_nametags">Avery 5395</a></li>
            </ul>
            <?php if (((NHC) && ($prefix == "final_")) || (!NHC)) { ?>			
			<p class="admin_default_header">During Judging</p>
			<ul class="admin_default">
				<li class="admin_default_label">Print BOS Pullsheets:</li>
    			<li><a id="modal_window_link" href="<?php echo $base_url; ?>output/pullsheets.php?section=admin&amp;go=judging_scores_bos" title="Print All BOS Pullsheets">All</a></li>
                    <?php do { ?>
          			<?php if ($row_style_type['styleTypeBOS'] == "Y") { ?><li><a id="modal_window_link" href="<?php echo $base_url; ?>output/pullsheets.php?section=admin&amp;go=judging_scores_bos&amp;id=<?php echo $row_style_type['id']; ?>"  title="Print the <?php echo $row_style_type['styleTypeName']; ?> BOS Pullsheet"><?php echo $row_style_type['styleTypeName']; ?></a></li><?php } ?>
          			<?php } while ($row_style_type = mysql_fetch_assoc($style_type)); ?>
			</ul>
            <ul class="admin_default">
				<li class="admin_default_label">BOS Cup Mats:</li>
                <li><a id="modal_window_link" href="<?php echo $base_url; ?>output/bos_mat.php" title="Print BOS Cup Mats">Print</a> (Using <em>Judging</em> Numbers)</li>
                <li><a id="modal_window_link" href="<?php echo $base_url; ?>output/bos_mat.php?filter=entry" title="Print BOS Cup Mats">Print</a> (Using <em>Entry</em> Numbers)</li>
                <li><em>For the mats to print properly, set your browser's printing margins to .25 inch and the orientation to landscape.</em></li>
            </ul>
            <?php } ?>
			<p class="admin_default_header">After Judging</p>
			<?php if ($totalRows_tables > 0) { ?>
            <ul class="admin_default">
				<li class="admin_default_label">Results Report (with Scores):</li>
				<li><a id="modal_window_link" href="<?php echo $base_url; ?>output/results.php?section=admin&amp;go=judging_scores&amp;action=print&amp;filter=scores&amp;view=default" title="Results Report <?php echo $method; ?> (All with Scores)">Print</a> (All)</li>
				<li><a id="modal_window_link" href="<?php echo $base_url; ?>output/results.php?section=admin&amp;go=judging_scores&amp;action=print&amp;filter=scores&amp;view=winners" title="Results Report <?php echo $method; ?> (Winners Only with Scores)">Print</a> (Winners Only)</li>
			</ul>
			<ul class="admin_default">
				<li class="admin_default_label">Results Report (without Scores):</li>
				<li><a id="modal_window_link" href="<?php echo $base_url; ?>output/results.php?section=admin&amp;go=judging_scores&amp;action=print&amp;filter=none&amp;view=default" title="Results Report <?php echo $method; ?> (All with Scores)">Print</a> (All)</li>
				<li><a id="modal_window_link" href="<?php echo $base_url; ?>output/results.php?section=admin&amp;go=judging_scores&amp;action=print&amp;filter=none&amp;view=winners" title="Results Report <?php echo $method; ?> (Winners Only without Scores)">Print</a> (Winners Only)</li>
				<li><a href="<?php echo $base_url; ?>output/results_download.php?section=admin&amp;go=judging_scores&amp;action=default&amp;filter=none&amp;view=pdf">PDF</a> (Winners Only)</li>
				<li><a href="<?php echo $base_url; ?>output/results_download.php?section=admin&amp;go=judging_scores&amp;action=default&amp;filter=none&amp;view=html">HTML</a> (Winners Only)</li>
			</ul>
            <?php if (((NHC) && ($prefix == "_final")) || (!NHC)) { ?>
			<ul class="admin_default">
				<li class="admin_default_label">BOS Round(s) Results Report:</li>
				<li><a id="modal_window_link" href="<?php echo $base_url; ?>output/results.php?section=admin&amp;go=judging_scores_bos&amp;action=print&amp;filter=bos&amp;view=default" title="BOS Round(s) Results Report">Print</a></li>
				<li><a href="<?php echo $base_url; ?>output/results_download.php?section=admin&amp;go=judging_scores_bos&amp;action=download&amp;filter=default&amp;view=pdf">PDF</a></li>
				<li><a href="<?php echo $base_url; ?>output/results_download.php?section=admin&amp;go=judging_scores_bos&amp;action=download&amp;filter=default&amp;view=html">HTML</a></li>	
			</ul>
            <?php } ?>
			<ul class="admin_default">
				<li class="admin_default_label">BJCP Judge/Steward/Staff Points Report:</li>
				<li><a id="modal_window_link" href="<?php echo $base_url; ?>output/staff_points.php?section=admin&amp;go=judging_assignments&amp;action=download&amp;filter=default&amp;view=default" title="BJCP Judge/Steward/Staff Points Report">Print</a></li>
				<li><a href="<?php echo $base_url; ?>output/staff_points.php?section=admin&amp;go=judging_assignments&amp;action=download&amp;filter=default&amp;view=pdf">PDF</a></li>
				<li><a href="<?php echo $base_url; ?>output/staff_points.php?section=admin&amp;go=judging_assignments&amp;action=download&amp;filter=default&amp;view=xml">XML</a></li>
			</ul>
            <?php if ($row_scores['count'] > 0) { ?>
			<ul class="admin_default">
				<li class="admin_default_label">Award Labels:</li>
                <li><a href="<?php echo $base_url; ?>output/labels.php?section=admin&amp;go=judging_scores&amp;action=awards&amp;filter=default&amp;psort=5160">Avery 5160 (Letter)</a></li>
				<li><a href="<?php echo $base_url; ?>output/labels.php?section=admin&amp;go=judging_scores&amp;action=awards&amp;filter=default&amp;psort=3422">Avery 3422 (A4)</a></li>
			</ul>
            <?php } } ?>
			<ul class="admin_default">
				<li class="admin_default_label">Participant Address Labels:</li>
				<li>All Participants - <a href="<?php echo $base_url; ?>output/labels.php?section=admin&amp;go=participants&amp;action=address_labels&amp;filter=default&amp;psort=5160">Avery 5160 (Letter)</a> or <a href="<?php echo $base_url; ?>output/labels.php?section=admin&amp;go=participants&amp;action=address_labels&amp;filter=default&amp;psort=3422">Avery 3422 (A4)</a></li>
                <li>All Participants with Entries - <a href="<?php echo $base_url; ?>output/labels.php?section=admin&amp;go=participants&amp;action=address_labels&amp;filter=with_entries&psort=5160">Avery 5160 (Letter)</a> or <a href="<?php echo $base_url; ?>output/labels.php?section=admin&amp;go=participants&amp;action=address_labels&amp;filter=with_entries&psort=3422">Avery 3422 (A4)</a></li>
			</ul>
            <ul class="admin_default">
				<li class="admin_default_label">Participant Summaries:</li>
				<li><a id="modal_window_link" href="<?php echo $base_url; ?>output/participant_summary.php" title="Print Participant Summaries (Each on a Separate Piece of 8 1/2 X 11 Paper)">Print</a> (All Participants with Entries)</li>
			</ul>
            
            <ul class="admin_default">
				<li class="admin_default_label">Post-Judging Bottle Inventory:</li>
                <li><a id="modal_window_link" href="<?php echo $base_url; ?>output/post_judge_inventory.php?section=scores" title="Post-Judging Inventory">Print</a> (with Scores)</li>
				<li><a id="modal_window_link" href="<?php echo $base_url; ?>output/post_judge_inventory.php" title="Post-Judging Inventory">Print</a> (without Scores)</li>
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
<p class="admin_default_header">Participant Names and Email Addresses (CSV Files)</p>
			<ul class="admin_default">
				<li><a href="<?php echo $base_url; ?>output/email_export.php">All Participants</a></li>
                <li><a href="<?php echo $base_url; ?>output/email_export.php?section=admin&amp;go=csv&amp;filter=avail_judges&amp;action=email">All Available Judges</a></li>
				<li><a href="<?php echo $base_url; ?>output/email_export.php?section=admin&amp;go=csv&amp;filter=avail_stewards&amp;action=email">All Available Stewards</a></li>
                <li><a href="<?php echo $base_url; ?>output/email_export.php?section=admin&amp;go=csv&amp;filter=judges&amp;action=email">All Assigned Judges</a></li>
				<li><a href="<?php echo $base_url; ?>output/email_export.php?section=admin&amp;go=csv&amp;filter=stewards&amp;action=email">All Assigned Stewards</a></li>                
				<li><a href="<?php echo $base_url; ?>output/email_export.php?section=admin&amp;go=csv&amp;filter=staff&amp;action=email">All Assigned Staff</a></li>
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
            <!--
  			<p class="admin_default_header">Tab Delimited Files</li>
			<p>For importing into the Homebrew Competition Coordination Program (HCCP), available for download <a href="http://www.folsoms.net/hccp/" target="_blank">here</a>. <?php if ($totalRows_judging1 > 1) { ?>The tab delimited file for <em>each location</em> should be imported into HCCP as it's own database. Refer to the <a href="http://www.folsoms.net/hccp/hccp.pdf" target="_blank">HCCP documentation</a> for import instructions.<?php } ?></p> 
			<p><em>*Please note: this function is deprecated and will be removed in a subsequent release.</em></p>
            <ul class="admin_default">
				<li><a href="<?php echo $base_url; ?>output/participants_export.php?section=admin&amp;go=tab&amp;action=hccp">All Participants</a></li>
				<li><a href="<?php echo $base_url; ?>output/entries_export.php?section=admin&amp;go=tab&amp;filter=paid&amp;action=hccp">Paid & Received Entries</a></li>
				<li><a href="<?php echo $base_url; ?>output/entries_export.php?section=admin&amp;go=tab&amp;action=hccp">All Entries</a></li>
			</ul>
            -->
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
if ($go == "judging_scores_bos")    		include (ADMIN.'judging_scores_bos.admin.php');
if ($go == "participants") 				include (ADMIN.'participants.admin.php');
if ($go == "entries") 					include (ADMIN.'entries.admin.php');
if ($go == "contacts") 	    			include (ADMIN.'contacts.admin.php');
if ($go == "dropoff") 	    			include (ADMIN.'dropoff.admin.php');
if ($go == "checkin") 	    			include (ADMIN.'barcode_check-in.admin.php');
if ($go == "count_by_style")				include (ADMIN.'entries_by_style.admin.php');
if ($go == "count_by_substyle")			include (ADMIN.'entries_by_substyle.admin.php');
if (($action == "register") && ($go == "judge")) 		include (SECTIONS.'register.sec.php');
if (($action == "register") && ($go == "entrant")) 	include (SECTIONS.'register.sec.php');

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
	}
}
else echo "<div class='error'>You do not have sufficient privileges to access this area.</div>";
?>
