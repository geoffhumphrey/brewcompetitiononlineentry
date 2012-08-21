<?php
/**
 * Module:      nav.sec.php 
 * Description: This module houses the main navigation. 
 * 
 */
include(DB.'sponsors.db.php');
if (strstr($section,"step")) { ?>
<div class="setupTitle">Set Up Your Brew Competition Online Entry and Management Site</div>
<?php } else { ?>
<ul id="nav">
  <li><?php if ($section != "default") { ?><a href="index.php">Home</a><?php } else { ?> Home<?php } ?></li>
  <li><?php if ($section != "rules") { ?><a href="index.php?section=rules">Rules</a><?php } else { ?>Rules<?php } ?></li>
  <li><?php if ($section != "entry") { ?><a href="index.php?section=entry">Entry Info</a><?php } else { ?>Entry Info<?php } ?></li>
  <li><?php if ($section != "volunteers") { ?><a href="index.php?section=volunteers">Volunteer Info</a><?php } else { ?>Volunteer Info<?php } ?></li>
  <?php if (($row_prefs['prefsSponsors'] == "Y") && ($row_prefs['prefsSponsorLogos'] == "Y") && ($totalRows_sponsors > 0)) { ?><li><?php if ($section != "sponsors") { ?><a href="index.php?section=sponsors">Sponsors</a><?php } else { ?>Sponsors<?php } ?></li><?php } ?>
  <?php if (get_contact_count() > 0) { ?>
  <li><?php if ($section != "contact") { ?><a href="index.php?section=contact">Contact</a><?php } else { ?>Contact<?php } ?></li>
  <?php } ?>
  <?php if (($registration_open < "2") && ($registration_open >= "1")  && (!open_limit($totalRows_log,$row_prefs['prefsEntryLimit'],$registration_open)))  { ?>
  <?php if (!isset($_SESSION["loginUsername"])) { ?><li><?php if ($section != "register") { ?><a href="index.php?section=register">Register</a><?php } else { ?>Register<?php } ?></li><?php } ?>
  <?php } ?>
  <?php if (($registration_open > "0") && (isset($_SESSION["loginUsername"])))  { ?> 
  <?php if (($row_contest_info['contestEntryFee'] > 0) && (judging_date_return() > 0) && ($totalRows_log > 0)) { ?><li><?php if ($section != "pay") { ?><a href="index.php?section=pay">Pay My Fees</a><?php } else { ?>Pay My Fees<?php } ?></li><?php } ?>
  <li><?php if ($section != "list") { ?><a href="index.php?section=list">My Info and Entries</a><?php } else { ?>My Info and Entries<?php } ?></li>
  <?php } ?>
  <?php if ((isset($_SESSION["loginUsername"])) && ($row_user['userLevel'] == "1")) { ?>
  <li><div class="menuBar"><a class="menuButton" href="index.php?section=admin" onclick="index.php?section=admin" onmouseover="buttonMouseover(event, 'adminMenu');">Admin</a></div></li>
<?php } ?>
  <li><a href="http://help.brewcompetition.com/index.html" title="BCOE&amp;M Help" target="_blank">BCOE&amp;M Help Site</a></li>
  <li><?php sessionAuthenticateNav(); ?></li>
</ul>
<?php } ?>
<?php if ((isset($_SESSION["loginUsername"])) && ($row_user['userLevel'] == "1")) { ?>
<div id="adminMenu" class="menu" onmouseover="menuMouseover(event)">
<!-- Admin Main Link -->
	<a class="menuItem" href="index.php?section=admin">Admin Main Menu</a>

<!-- Defining Preferences Menu -->
	<a class="menuItem" href="" onclick="return false;" onmouseover="menuItemMouseover(event, 'adminMenuCat_Prefs');"><span class="menuItemText">Defining Preferences</span><span class="menuItemArrow">&#9654;</span></a>

<!-- Preparing Menu -->
	<a class="menuItem" href="" onclick="return false;" onmouseover="menuItemMouseover(event, 'adminMenuCat_Preparing');"><span class="menuItemText">Preparing</span><span class="menuItemArrow">&#9654;</span></a>	

<!-- Entry and Data Gathering Menu -->
	<a class="menuItem" href="" onclick="return false;" onmouseover="menuItemMouseover(event, 'adminMenuCat_Entry');"><span class="menuItemText">Entry and Data Gathering</span><span class="menuItemArrow">&#9654;</span></a>

<!-- Sorting Received Entries Menu -->
	<a class="menuItem" href="" onclick="return false;" onmouseover="menuItemMouseover(event, 'adminMenuCat_Sorting');"><span class="menuItemText">Sorting Received Entries</span><span class="menuItemArrow">&#9654;</span></a>

<!-- Organizing Menu -->
	<a class="menuItem" href="" onclick="return false;" onmouseover="menuItemMouseover(event, 'adminMenuCat_Organizing');"><span class="menuItemText">Organizing</span><span class="menuItemArrow">&#9654;</span></a>

<!-- Scoring Menu -->
	<a class="menuItem" href="" onclick="return false;" onmouseover="menuItemMouseover(event, 'adminMenuCat_Scoring');"><span class="menuItemText">Scoring</span><span class="menuItemArrow">&#9654;</span></a>

<!-- Printing and Reporting Menu -->
	<a class="menuItem" href="" onclick="return false;" onmouseover="menuItemMouseover(event, 'adminMenuCat_Printing');"><span class="menuItemText">Printing and Reporting</span><span class="menuItemArrow">&#9654;</span></a>

<!-- Exporting Menu -->
	<a class="menuItem" href="" onclick="return false;" onmouseover="menuItemMouseover(event, 'adminMenuCat_Exporting');"><span class="menuItemText">Exporting</span><span class="menuItemArrow">&#9654;</span></a>
	
<!-- Archiving Menu -->
	<a class="menuItem" href="" onclick="return false;" onmouseover="menuItemMouseover(event, 'adminMenuCat_Archiving');"><span class="menuItemText">Archiving</span><span class="menuItemArrow">&#9654;</span></a>

<!-- Bug Reporting -->
<a class="menuItem" href="http://code.google.com/p/brewcompetitiononlineentry/issues/entry" target="_blank">Report a Bug</a>
</div>


<div id="adminMenuCat_Prefs" class="menu">
	<a class="menuItem" href="" onclick="return false;" onmouseover="menuItemMouseover(event, 'adminMenu_Prefs_Define');"><span class="menuItemText">Define</span><span class="menuItemArrow">&#9654;</span></a>
</div>

<div id="adminMenuCat_Preparing" class="menu">
	<a class="menuItem" href="" onclick="return false;" onmouseover="menuItemMouseover(event, 'adminMenu_Preparing_Manage');"><span class="menuItemText">Manage/View</span><span class="menuItemArrow">&#9654;</span></a>
	<a class="menuItem" href="" onclick="return false;" onmouseover="menuItemMouseover(event, 'adminMenu_Preparing_Add');"><span class="menuItemText">Add</span><span class="menuItemArrow">&#9654;</span></a>
	<a class="menuItem" href="" onclick="return false;" onmouseover="menuItemMouseover(event, 'adminMenu_Preparing_Edit');"><span class="menuItemText">Edit</span><span class="menuItemArrow">&#9654;</span></a>
	<a class="menuItem" href="" onclick="return false;" onmouseover="menuItemMouseover(event, 'adminMenu_Preparing_Upload');"><span class="menuItemText">Upload</span><span class="menuItemArrow">&#9654;</span></a>
</div>

<div id="adminMenuCat_Entry" class="menu">
	<a class="menuItem" href="" onclick="return false;" onmouseover="menuItemMouseover(event, 'adminMenu_Entry_Manage');"><span class="menuItemText">Manage/View</span><span class="menuItemArrow">&#9654;</span></a>
	<a class="menuItem" href="" onclick="return false;" onmouseover="menuItemMouseover(event, 'adminMenu_Entry_Add');"><span class="menuItemText">Add</span><span class="menuItemArrow">&#9654;</span></a>
	<a class="menuItem" href="" onclick="return false;" onmouseover="menuItemMouseover(event, 'adminMenu_Entry_Assign');"><span class="menuItemText">Assign</span><span class="menuItemArrow">&#9654;</span></a>
</div>

<div id="adminMenuCat_Sorting" class="menu">
	<a class="menuItem" href="" onclick="return false;" onmouseover="menuItemMouseover(event, 'adminMenu_Sorting_Manage');"><span class="menuItemText">Manage/View</span><span class="menuItemArrow">&#9654;</span></a>
	<a class="menuItem" href="" onclick="return false;" onmouseover="menuItemMouseover(event, 'adminMenu_Sorting_Add');"><span class="menuItemText">Add</span><span class="menuItemArrow">&#9654;</span></a>
    <a class="menuItem" href="" onclick="return false;" onmouseover="menuItemMouseover(event, 'adminMenu_Sorting_Regenerate');"><span class="menuItemText">Regenerate</span><span class="menuItemArrow">&#9654;</span></a>
	<a class="menuItem" href="" onclick="return false;" onmouseover="menuItemMouseover(event, 'adminMenu_Sorting_Print');"><span class="menuItemText">Print</span><span class="menuItemArrow">&#9654;</span></a>
</div>

<div id="adminMenuCat_Organizing" class="menu">
	<a class="menuItem" href="" onclick="return false;" onmouseover="menuItemMouseover(event, 'adminMenu_Organizing_Manage');"><span class="menuItemText">Manage/View</span><span class="menuItemArrow">&#9654;</span></a>
	<a class="menuItem" href="" onclick="return false;" onmouseover="menuItemMouseover(event, 'adminMenu_Organizing_Add');"><span class="menuItemText">Add</span><span class="menuItemArrow">&#9654;</span></a>
	<a class="menuItem" href="" onclick="return false;" onmouseover="menuItemMouseover(event, 'adminMenu_Organizing_Assign');"><span class="menuItemText">Assign</span><span class="menuItemArrow">&#9654;</span></a>
</div>

<div id="adminMenuCat_Scoring" class="menu">
	<a class="menuItem" href="" onclick="return false;" onmouseover="menuItemMouseover(event, 'adminMenu_Scoring_Manage');"><span class="menuItemText">Manage/View</span><span class="menuItemArrow">&#9654;</span></a>
	<a class="menuItem" href="" onclick="return false;" onmouseover="menuItemMouseover(event, 'adminMenu_Scoring_Add');"><span class="menuItemText">Add</span><span class="menuItemArrow">&#9654;</span></a>
</div>

<div id="adminMenuCat_Printing" class="menu">
	<a class="menuItem" href="" onclick="return false;" onmouseover="menuItemMouseover(event, 'adminMenu_Printing_Before');"><span class="menuItemText">Before Judging</span><span class="menuItemArrow">&#9654;</span></a>
	<?php if ($row_scores['count'] > 0) { ?>
    <a class="menuItem" href="" onclick="return false;" onmouseover="menuItemMouseover(event, 'adminMenu_Printing_During');"><span class="menuItemText">During Judging</span><span class="menuItemArrow">&#9654;</span></a>
	<?php } ?>
    <a class="menuItem" href="" onclick="return false;" onmouseover="menuItemMouseover(event, 'adminMenu_Printing_After');"><span class="menuItemText">After Judging</span><span class="menuItemArrow">&#9654;</span></a>
</div>

<div id="adminMenuCat_Exporting" class="menu">
	<a class="menuItem" href="" onclick="return false;" onmouseover="menuItemMouseover(event, 'adminMenu_Exporting_Email');"><span class="menuItemText">Email Addresses (CSV Files)</span><span class="menuItemArrow">&#9654;</span></a>
	<a class="menuItem" href="" onclick="return false;" onmouseover="menuItemMouseover(event, 'adminMenu_Exporting_Tab');"><span class="menuItemText">Tab Delimited Files</span><span class="menuItemArrow">&#9654;</span></a>
	<a class="menuItem" href="" onclick="return false;" onmouseover="menuItemMouseover(event, 'adminMenu_Exporting_CSV');"><span class="menuItemText">CSV Files</span><span class="menuItemArrow">&#9654;</span></a>
	<a class="menuItem" href="" onclick="return false;" onmouseover="menuItemMouseover(event, 'adminMenu_Exporting_Promo');"><span class="menuItemText">Promo Materials</span><span class="menuItemArrow">&#9654;</span></a>
</div>

<div id="adminMenuCat_Archiving" class="menu">
	<a class="menuItem" href="" onclick="return false;" onmouseover="menuItemMouseover(event, 'adminMenu_Archiving_Manage');"><span class="menuItemText">Manage/View</span><span class="menuItemArrow">&#9654;</span></a>

</div>
    
<!-- Third Tier Menus -->

<div id="adminMenu_Prefs_Define" class="menu">
	<a class="menuItem" href="index.php?section=admin&amp;go=preferences">Site Preferences</a>
	<a class="menuItem" href="index.php?section=admin&amp;go=judging_preferences">Competition Organization Preferences</a>
</div>


<div id="adminMenu_Preparing_Manage" class="menu">
	<a class="menuItem" href="index.php?section=admin&amp;go=style_types">Style Types</a>
    <a class="menuItem" href="index.php?section=admin&amp;go=styles">Accepted Style Categories</a>
    <a class="menuItem" href="index.php?section=admin&amp;go=special_best">Custom Winning Categories</a>
    <a class="menuItem" href="index.php?section=admin&amp;go=special_best_data">Custom Winning Category Entries</a>
    <a class="menuItem" href="index.php?section=admin&amp;go=judging">Judging Locations &amp; Dates</a>
    <a class="menuItem" href="index.php?section=admin&amp;go=contacts">Competition Contacts</a>
    <a class="menuItem" href="index.php?section=admin&amp;go=dropoff">Drop-Off Locations</a>
    <a class="menuItem" href="index.php?section=admin&amp;go=sponsors">Sponsors</a>
</div>
<div id="adminMenu_Preparing_Add" class="menu">
	<a class="menuItem" href="index.php?section=admin&amp;go=style_types&amp;action=add">A Style Type</a>
    <a class="menuItem" href="index.php?section=admin&amp;go=styles&amp;action=add">A Custom Style Category</a>
    <a class="menuItem" href="index.php?section=admin&amp;go=special_best">A Custom Winning Category</a>
    <a class="menuItem" href="index.php?section=admin&amp;go=judging&amp;action=add">A Judging Location</a>
    <a class="menuItem" href="index.php?section=admin&amp;go=dropoff&amp;action=add">A Drop-Off Location</a>
    <a class="menuItem" href="index.php?section=admin&amp;go=sponsors&amp;action=add">A Sponsor</a>
    <a class="menuItem" href="index.php?section=admin&amp;go=contacts&amp;action=add">A Competition Contact</a>
</div>
<div id="adminMenu_Preparing_Edit" class="menu">
	<a class="menuItem" href="index.php?section=admin&amp;go=contest_info">Competition Info</a>
</div>
<div id="adminMenu_Preparing_Upload" class="menu">
	<a id="modal_window_link" class="menuItem" href="admin/upload.admin.php" title="Upload Competition Logo Image">A Competition Logo</a>
	<a id="modal_window_link" class="menuItem" href="admin/upload.admin.php" title="Upload Sponsor Logo Image">A Sponsor Logo</a>
</div>

<div id="adminMenu_Entry_Manage" class="menu">
	<a class="menuItem" href="index.php?section=admin&amp;go=style_types">Style Types</a>
    <a class="menuItem" href="index.php?section=admin&amp;go=styles">Accepted Style Categories</a>
	<a class="menuItem" href="index.php?section=admin&amp;go=participants">Participants</a>
	<a class="menuItem" href="index.php?section=admin&amp;go=entries">Entries</a>
	<a class="menuItem" href="index.php?section=admin&amp;go=judging">Judging Locations &amp; Dates</a>		
    <a class="menuItem" href="index.php?section=admin&amp;go=participants&amp;filter=judges">Available Judges</a>
	<a class="menuItem" href="index.php?section=admin&amp;go=participants&amp;filter=stewards">Available Stewards</a>
	<a class="menuItem" href="index.php?section=admin&amp;go=contacts">Competition Contacts</a>
	<a class="menuItem" href="index.php?section=admin&amp;go=dropoff">Drop-Off Locations</a>
	<a class="menuItem" href="index.php?section=admin&amp;go=sponsors">Sponsors</a>
</div>
<div id="adminMenu_Entry_Add" class="menu">
	<a class="menuItem" href="index.php?section=admin&amp;go=style_types&amp;action=add">A Style Type</a>
	<a class="menuItem" href="index.php?section=admin&amp;go=styles&amp;action=add">A Custom Style Category</a>
	<a class="menuItem" href="index.php?section=admin&amp;go=entrant&amp;action=register">A Participant</a>
    <a class="menuItem" href="index.php?section=admin&amp;go=judge&amp;action=register">A Participant as a Judge/Steward</a></li>
	<a class="menuItem" href="index.php?section=brew&amp;go=entries&amp;action=add&amp;filter=admin">A Participant's Entry</a>
    <a class="menuItem" href="index.php?section=admin&amp;go=judging&amp;action=add">A Judging Location</a>
    <a class="menuItem" href="index.php?section=admin&amp;go=contacts&amp;action=add">A Competition Contact</a>
	<a class="menuItem" href="index.php?section=admin&amp;go=dropoff&amp;action=add">A Drop-Off Location</a>
	<a class="menuItem" href="index.php?section=admin&amp;go=sponsors&amp;action=add">A Sponsor</a>
</div>
<div id="adminMenu_Entry_Assign" class="menu">
	<a class="menuItem" href="index.php?section=admin&amp;action=assign&amp;go=judging&amp;filter=judges">Participants as Judges</a>
	<a class="menuItem" href="index.php?section=admin&amp;action=assign&amp;go=judging&amp;filter=stewards">Participants as Stewards</a>
    <a class="menuItem" href="index.php?section=admin&amp;action=assign&amp;go=judging&amp;filter=staff">Participants as Staff</a>
</div>

<div id="adminMenu_Sorting_Manage" class="menu" onmouseover="menuMouseover(event)">
	<a class="menuItem" href="index.php?section=admin&amp;go=participants">Participants</a>
	<a class="menuItem" href="index.php?section=admin&go=entries">Entries</a>
</div>
<div id="adminMenu_Sorting_Regenerate" class="menu" onmouseover="menuMouseover(event)">
	<a class="menuItem" onclick="return confirm('Are you sure you want to regenerate judging numbers for all entries?');" href="includes/process.inc.php?section=admin&amp;go=entries&amp;action=generate_judging_numbers&amp;sort=id&amp;dir=ASC">Entry Judging Numbers</a>
</div>
<div id="adminMenu_Sorting_Add" class="menu" onmouseover="menuMouseover(event)">
	<a class="menuItem" href="index.php?section=admin&amp;go=entrant&amp;action=register">A Participant</a>
    <a class="menuItem" href="index.php?section=admin&amp;go=judge&amp;action=register">A Participant as a Judge/Steward</a>
	<a class="menuItem" href="index.php?section=brew&amp;go=entries&amp;action=add&amp;filter=admin">A Participant's Entry</a>
</div>
<div id="adminMenu_Sorting_Print" class="menu" onmouseover="menuMouseover(event)">
	<a class="menuItem" href="output/sorting.php?section=admin&amp;go=default&amp;filter=default">Sorting Sheets - All Categories</a>
    <a class="menuItem" href="output/labels.php?section=admin&amp;go=entries&amp;action=bottle-entry&amp;filter=default">Bottle Labels Using Entry Numbers - All Categories</a>
    <a class="menuItem" href="output/labels.php?section=admin&amp;go=entries&amp;action=bottle-judging&amp;filter=default">Bottle Labels Using Judging Numbers - All Categories</a>
</div>

<div id="adminMenu_Organizing_Manage" class="menu" onmouseover="menuMouseover(event)">
	<a class="menuItem" href="index.php?section=admin&amp;go=participants">Participants</a>
	<a class="menuItem" href="index.php?section=admin&go=entries">Entries</a>
	<a class="menuItem" href="index.php?section=admin&amp;go=judging_tables">Tables</a>
    <?php if ($row_judging_prefs['jPrefsQueued'] == "N") { ?>
    <a class="menuItem" href="index.php?section=admin&amp;go=judging_flights">Flights</a>
    <?php } ?>
    <a class="menuItem" href="index.php?section=admin&go=styles&filter=orphans">Styles Without a Valid Style Type</a>
	<a class="menuItem" href="index.php?section=admin&go=judging_tables&filter=orphans">Styles Not Assigned to Tables</a>
	<a class="menuItem" href="index.php?section=admin&amp;go=participants&amp;filter=assignJudges">Assigned Judges</a>
	<a class="menuItem" href="index.php?section=admin&amp;go=participants&amp;filter=assignStewards">Assigned Stewards</a>
</div>
<div id="adminMenu_Organizing_Add" class="menu" onmouseover="menuMouseover(event)">
	<a class="menuItem" href="index.php?section=admin&go=entrant&action=register">A Participant</a>
    <a class="menuItem" href="index.php?section=admin&amp;go=judge&amp;action=register">A Participant as a Judge/Steward</a></li>
	<a class="menuItem" href="index.php?section=brew&go=entries&action=add&filter=admin">A Participant's Entry</a>
	<a class="menuItem" href="index.php?section=admin&go=judging_tables&action=add">A Table</a>
    <?php if ($row_judging_prefs['jPrefsQueued'] == "N") { ?>
    <a class="menuItem" href="index.php?section=admin&go=judging_tables&action=add">Flights to Tables</a>
    <?php } ?>
</div>
<div id="adminMenu_Organizing_Assign" class="menu">
	<a class="menuItem" href="index.php?section=admin&amp;action=assign&amp;go=judging&amp;filter=judges">Participants as Judges</a>
	<a class="menuItem" href="index.php?section=admin&amp;action=assign&amp;go=judging&amp;filter=stewardss">Participants as Stewards</a>
    <a class="menuItem" href="index.php?section=admin&amp;action=assign&amp;go=judging&amp;filter=staff">Participants as Staff</a>
	<?php if (($totalRows_judging > 1) && ($row_prefs['prefsCompOrg'] == "N")) { ?>
	<a class="menuItem" href="index.php?section=admin&amp;action=update&amp;go=judging&amp;filter=judges">Judges to a Location</a>
	<a class="menuItem" href="index.php?section=admin&amp;action=update&amp;go=judging&amp;filter=stewards">Stewards to a Location</a>
	<?php } if ($row_judging_prefs['jPrefsQueued'] == "Y") { ?>
	<a class="menuItem" href="index.php?section=admin&go=judging_flights&amp;action=assign&amp;filter=rounds">Tables to Rounds</a>
	<?php } if ($row_judging_prefs['jPrefsQueued'] == "N") { ?>
    <a class="menuItem" href="index.php?section=admin&amp;go=judging_flights&amp;action=rounds">Flights to Rounds</a>
	<?php } ?>
    <a class="menuItem" href="index.php?section=admin&amp;go=judging_tables&amp;action=assign">Judges or Stewards to a Table</a>
    <a class="menuItem" href="index.php?section=admin&amp;action=assign&amp;go=judging&amp;filter=bos">Best of Show Judges</a>
</div>

<div id="adminMenu_Scoring_Manage" class="menu">
	<a class="menuItem" href="index.php?section=admin&amp;go=participants">Participants</a>
	<a class="menuItem" href="index.php?section=admin&amp;go=entries">Entries</a>
	<a class="menuItem" href="index.php?section=admin&amp;go=judging_tables">Tables</a>
	<a class="menuItem" href="index.php?section=admin&amp;go=judging_scores">Scores by Table</a>
	<a class="menuItem" href="index.php?section=admin&amp;go=judging_scores&amp;filter=category">Scores by Category</a>
	<a class="menuItem" href="index.php?section=admin&amp;go=judging_scores_bos">BOS Entries and Places</a>
	<a class="menuItem" href="index.php?section=admin&amp;go=special_best">Custom Winning Categoires</a>
	<a class="menuItem" href="index.php?section=admin&amp;go=special_best_data">Custom Winning Category Entries</a>
</div>

<div id="adminMenu_Scoring_Add" class="menu">
	<a class="menuItem" href="index.php?section=admin&amp;go=judging_scores&amp;action=add">Scores by Table</a>
	<a class="menuItem" href="index.php?section=admin&amp;go=judging_scores&amp;action=add&amp;filter=category">Scores by Category</a>
</div>

<div id="adminMenu_Printing_Before" class="menu">
<?php if ($totalRows_tables > 0) { ?>
	<a id="modal_window_link" class="menuItem" href="output/pullsheets.php?section=admin&amp;go=judging_tables&amp;id=default" title="Print Pullsheets for All Tables">Pullsheets for All Tables</a>
	<a id="modal_window_link" class="menuItem" href="output/table_cards.php?section=admin&amp;go=judging_tables&amp;id=default" title="Print Table Cards for All Tables">Table Cards for All Tables</a>
	<a id="modal_window_link" class="menuItem" href="output/assignments.php?section=admin&amp;go=judging_assignments&amp;filter=judges&amp;view=name" title="Judging Assignments by Last Name">Judge Assignments by Last Name</a>
    <a id="modal_window_link" class="menuItem" href="output/assignments.php?section=admin&amp;go=judging_assignments&amp;filter=stewards&amp;view=name" title="Judging Assignments by Last Name">Steward Assignments by Last Name</a>
    <?php } ?>
    <a id="modal_window_link" class="menuItem" href="output/assignments.php?section=admin&amp;go=judging_assignments&amp;filter=judges&amp;view=sign-in" title="Judge Sign-in Sheet">Judge Sign-in Sheet</a>
	<a id="modal_window_link" class="menuItem" href="output/assignments.php?section=admin&amp;go=judging_assignments&amp;filter=stewards&amp;view=sign-in" title="Steward Sign-in Sheet">Steward Sign-in Sheet</a>
    <a class="menuItem" href="output/labels.php?section=admin&go=participants&action=judging_labels">Judge Scoresheet Labels (All)</a>
</div>
<?php if ($row_scores['count'] > 0) { ?>
<div id="adminMenu_Printing_During" class="menu">
	<a id="modal_window_link" class="menuItem" href="output/pullsheets.php?section=admin&amp;go=judging_scores_bos">All BOS Pullsheets</a>
</div>
<?php } ?>
<div id="adminMenu_Printing_After" class="menu">
	<?php if ($totalRows_tables > 0) { ?>
	<a id="modal_window_link" class="menuItem" href="output/results.php?section=admin&amp;go=judging_scores&amp;action=default&amp;filter=scores&amp;view=default" title="Print Results by Table With Scores (All)">Results <?php $method = winner_method($row_prefs['prefsWinnerMethod'],1); ?> With Scores (All)</a>
	<a id="modal_window_link" class="menuItem" href="output/results.php?section=admin&amp;go=judging_scores&amp;action=default&amp;filter=scores&amp;view=winners" title="Print Results by Table With Scores (Winners Only)">Results <?php echo $method; ?> With Scores (Winners Only)</a>
    <a id="modal_window_link" class="menuItem" href="output/results.php?section=admin&amp;go=judging_scores&amp;action=default&amp;filter=none&amp;view=default" title="Print Results by Table Without Scores (All)">Results <?php echo $method; ?> Without Scores (All)</a>
	<a id="modal_window_link" class="menuItem" href="output/results.php?section=admin&amp;go=judging_scores&amp;action=default&amp;filter=none&amp;view=winners" title="Print Results by Table Without Scores (Winners Only)">Results <?php echo $method; ?> Without Scores (Winners Only)</a>
    <a id="modal_window_link" class="menuItem" href="output/results.php?section=admin&amp;go=judging_scores_bos&amp;action=default&amp;filter=bos&amp;view=default" title="Print BOS Round(s) Results Report">BOS Round(s) Results Report</a>
	<a id="modal_window_link" class="menuItem" href="output/staff_points.php?section=admin&amp;go=judging_assignments&amp;action=download&amp;filter=default&amp;view=default" title="Print BJCP Judge/Steward/Staff Points Report">BJCP Judge/Steward/Staff Points Report</a>
	<?php if ($row_scores['count'] > 0) { ?>
    <a class="menuItem" href="output/labels.php?section=admin&amp;go=judging_scores&amp;action=awards&amp;filter=default">Award Labels</a>
    <?php } } ?>
	<a class="menuItem" href="output/labels.php?section=admin&amp;go=participants&amp;action=address_labels&amp;filter=default">Participant Address Labels</a>
</div>

<div id="adminMenu_Exporting_Email" class="menu">
	<a class="menuItem" href="output/email_export.php">All Participants</a>
	<a class="menuItem" href="output/email_export.php?section=admin&amp;go=csv&amp;filter=judges&amp;action=email">All Judges</a>
	<a class="menuItem" href="output/email_export.php?section=admin&amp;go=csv&amp;filter=stewards&amp;action=email">All Stewards</a>
    <a class="menuItem" href="output/entries_export.php?section=admin&amp;go=csv&amp;action=email">All Entries</a>
	<a class="menuItem" href="output/entries_export.php?section=admin&amp;go=csv&amp;filter=paid&amp;action=email">Paid &amp; Received Entries</a>
	<a class="menuItem" href="output/entries_export.php?section=admin&amp;go=csv&amp;filter=nopay&amp;action=email">Non-Paid &amp; Received Entries</a>
</div>
<div id="adminMenu_Exporting_Tab" class="menu">
	<a class="menuItem" href="output/participants_export.php?section=admin&amp;go=tab">All Participants</a>
	<a class="menuItem" href="output/entries_export.php?section=admin&amp;go=tab&amp;filter=paid&amp;action=hccp">Paid &amp; Received Entries</a>
	<a class="menuItem" href="output/entries_export.php?section=admin&amp;go=tab">All Entries</a>
</div>
<div id="adminMenu_Exporting_CSV" class="menu">
	<a class="menuItem" href="output/entries_export.php?section=admin&amp;go=csv">All Entries</a>
	<a class="menuItem" href="output/entries_export.php?section=admin&amp;go=csv&amp;filter=paid&amp;action=hccp">Paid &amp; Received Entries</a>
	<a class="menuItem" href="output/entries_export.php?section=admin&amp;go=csv&amp;filter=nopay&amp;action=hccp">Non-Paid &amp; Received Entries</a>
    <a class="menuItem" href="output/participants_export.php?section=admin&amp;go=csv">All Participants</a>
	<a class="menuItem" href="output/participants_export.php?section=admin&amp;go=csv&amp;filter=winners">Winners</a>
</div>
<div id="adminMenu_Exporting_Promo" class="menu">
	<a class="menuItem" href="output/promo_export.php?section=admin&amp;action=html">HTML</a>
	<a class="menuItem" href="output/promo_export.php?section=admin&amp;action=word">Word</a>
</div>

<div id="adminMenu_Archiving_Manage" class="menu">
	<a class="menuItem" href="index.php?section=admin&amp;go=archive">Archived Competition Data</a>
</div>

<?php } ?>