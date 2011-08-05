<?php
/**
 * Module:      nav.sec.php 
 * Description: This module houses the main navigation. 
 * 
 */


include(DB.'sponsors.db.php');
if (strstr($section,"step")) { ?>
<div class="setupTitle">Set Up Your Brew Competition Online Entry and Managment Site</div>
<?php } else { ?>
<ul id="nav">
  <li><?php if ($section != "default") { ?><a href="index.php"><?php echo $row_contest_info['contestName']; ?> Home</a><?php } else { echo $row_contest_info['contestName']; ?> Home<?php } ?></li>
  <li><?php if ($section != "rules") { ?><a href="index.php?section=rules">Rules</a><?php } else { ?>Rules<?php } ?></li>
  <li><?php if ($section != "entry") { ?><a href="index.php?section=entry">Entry Information</a><?php } else { ?>Entry Information<?php } ?></li>
  <?php if (($row_prefs['prefsSponsors'] == "Y") && ($row_prefs['prefsSponsorLogos'] == "Y") && ($totalRows_sponsors > 0)) { ?><li><?php if ($section != "sponsors") { ?><a href="index.php?section=sponsors">Sponsors</a><?php } else { ?>Sponsors<?php } ?></li><?php } ?>
  <?php if (get_contact_count() > 0) { ?>
  <li><?php if ($section != "contact") { ?><a href="index.php?section=contact">Contact</a><?php } else { ?>Contact<?php } ?></li>
  <?php } ?>
  <?php if (!greaterDate($today,$row_contest_info['contestRegistrationDeadline']) && (greaterDate($today,$row_contest_info['contestRegistrationOpen'])))  { ?>
  <?php if (!isset($_SESSION["loginUsername"])) { ?><li><?php if ($section != "register") { ?><a href="index.php?section=register">Register</a><?php } else { ?>Register<?php } ?></li><?php } ?>
  <?php if ((isset($_SESSION["loginUsername"])) && ($row_contest_info['contestEntryFee'] > 0)) { ?><li><?php if ($section != "pay") { ?><a href="index.php?section=pay">Pay My Fees</a><?php } else { ?>Pay My Fees<?php } ?></li><?php } ?>
  <?php } ?>
  <?php if (isset($_SESSION["loginUsername"])) { ?><li><?php if ($section != "list") { ?><a href="index.php?section=list">My Info and Entries</a><?php } else { ?>My Info and Entries<?php } ?></li><?php } ?>
  <?php if ((isset($_SESSION["loginUsername"])) && ($row_user['userLevel'] == "1")) { ?><li><div class="menuBar"><a class="menuButton" href="index.php?section=admin" onclick="index.php?section=admin" onmouseover="buttonMouseover(event, 'adminMenu');">Admin</a>
</div><?php } ?>
  <li><a href="http://help.brewcompetition.com/index.html" title="BCOE&amp;M Help" target="_blank">Help</a></li>
  <li><?php sessionAuthenticateNav(); ?></li>
</ul>
<?php } ?>
<?php if ((isset($_SESSION["loginUsername"])) && ($row_user['userLevel'] == "1")) { ?>
<div id="adminMenu" class="menu" onmouseover="menuMouseover(event)">

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
	<a class="menuItem" href="" onclick="return false;" onmouseover="menuItemMouseover(event, 'adminMenu_Printing_During');"><span class="menuItemText">During Judging</span><span class="menuItemArrow">&#9654;</span></a>
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
    <a class="menuItem" href="index.php?section=admin&amp;go=styles">Accepted Style Types</a>
    <a class="menuItem" href="index.php?section=admin&amp;go=judging">Judging Locations</a>
    <a class="menuItem" href="index.php?section=admin&amp;go=contacts">Competition Contacts</a>
    <a class="menuItem" href="index.php?section=admin&amp;go=dropoff">Drop-Off Locations</a>
    <a class="menuItem" href="index.php?section=admin&amp;go=sponsors">Sponsors</a>
</div>
<div id="adminMenu_Preparing_Add" class="menu">
	<a class="menuItem" href="index.php?section=admin&amp;go=style_types&amp;action=add">Style Types</a>
    <a class="menuItem" href="index.php?section=admin&amp;go=styles&amp;action=add">Accepted Style Types</a>
    <a class="menuItem" href="index.php?section=admin&amp;go=judging&amp;action=add">Judging Locations</a>
    <a class="menuItem" href="index.php?section=admin&amp;go=contacts&amp;action=add">Competition Contacts</a>
    <a class="menuItem" href="index.php?section=admin&amp;go=dropoff&amp;action=add">Drop-Off Locations</a>
    <a class="menuItem" href="index.php?section=admin&amp;go=sponsors&amp;action=add">Sponsors</a>
</div>
<div id="adminMenu_Preparing_Edit" class="menu">
	<a class="menuItem" href="index.php?section=admin&amp;go=contest_info">Competition Info</a>
</div>
<div id="adminMenu_Preparing_Upload" class="menu">
	<a class="menuItem thickbox" href="admin/upload.admin.php?KeepThis=true&amp;TB_iframe=true&amp;height=450&amp;width=800" title="Upload Competition Logo Image">Competition Logo</a>
	<a class="menuItem thickbox" href="admin/upload.admin.php?KeepThis=true&amp;TB_iframe=true&amp;height=450&amp;width=800" title="Upload Sponsor Logo Image">Sponsor Logos</a>
</div>

<div id="adminMenu_Entry_Manage" class="menu">
	<a class="menuItem" href="index.php?section=admin&amp;go=styles">Accepted Style Categories</a>
	<a class="menuItem" href="index.php?section=admin&go=styles&filter=judging">Style Categories for Judging Locations</a>
	<a class="menuItem" href="index.php?section=admin&amp;go=participants">Participants</a>
	<a class="menuItem" href="index.php?section=admin&amp;go=entries">Entries</a>
	<a class="menuItem" href="index.php?section=admin&amp;go=judging">Judging Locations</a>		
    <a class="menuItem" href="index.php?section=admin&amp;go=participants&amp;filter=judges">Available Judges</a>
	<a class="menuItem" href="index.php?section=admin&amp;go=participants&amp;filter=stewards">Available Stewards</a>
	<a class="menuItem" href="index.php?section=admin&amp;go=contacts">Competition Contacts</a>
	<a class="menuItem" href="index.php?section=admin&amp;go=dropoff">Drop-Off Locations</a>
	<a class="menuItem" href="index.php?section=admin&amp;go=sponsors">Sponsors</a>
</div>
<div id="adminMenu_Entry_Add" class="menu">
	<a class="menuItem" href="index.php?section=admin&amp;go=style_types&amp;action=add">A Style Type</a>
	<a class="menuItem" href="index.php?section=admin&amp;go=styles&amp;action=add">A Custom Style Category</a>
	<a class="menuItem" href="index.php?section=admin&amp;go=participants&amp;action=add">A Participant</a>
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
<div id="adminMenu_Sorting_Add" class="menu" onmouseover="menuMouseover(event)">
	<a class="menuItem" href="index.php?section=admin&amp;go=participants&amp;action=add">A Participant</a>
	<a class="menuItem" href="index.php?section=brew&amp;go=entries&amp;action=add&amp;filter=admin">A Participant's Entry</a>
</div>
<div id="adminMenu_Sorting_Print" class="menu" onmouseover="menuMouseover(event)">
	<a class="menuItem" href="output/sorting.php?section=admin&amp;go=default&amp;filter=default&amp;KeepThis=true&amp;TB_iframe=true&amp;height=450&amp;width=800">Sorting Sheets - All Categories</a>
    <a class="menuItem" href="http://test.brewcompetition.com/output/labels.php?section=admin&amp;go=entries&amp;action=bottle&amp;filter=default">Bottle Labels - All Categories</a>
</div>

<div id="adminMenu_Organizing_Manage" class="menu" onmouseover="menuMouseover(event)">
	<a class="menuItem" href="index.php?section=admin&amp;go=participants">Participants</a>
	<a class="menuItem" href="index.php?section=admin&go=entries">Entries</a>
	<a class="menuItem" href="index.php?section=admin&amp;go=judging_tables">Tables</a>
	<a class="menuItem" href="index.php?section=admin&go=judging_tables&filter=orphans">Styles Not Assigned to Tables</a>
	<a class="menuItem" href="index.php?section=admin&amp;go=judging_flights">Flights</a>
	<a class="menuItem" href="index.php?section=admin&amp;go=participants&amp;filter=assignJudges">Assigned Judges</a>
	<a class="menuItem" href="index.php?section=admin&amp;go=participants&amp;filter=assignStewards">Assigned Stewards</a>
</div>
<div id="adminMenu_Organizing_Add" class="menu" onmouseover="menuMouseover(event)">
	<a class="menuItem" href="index.php?section=admin&go=participants&action=add">A Participant</a>
	<a class="menuItem" href="index.php?section=brew&go=entries&action=add&filter=admin">A Participant's Entry</a>
	<a class="menuItem" href="index.php?section=admin&go=judging_tables&action=add">A Table</a>
	<a class="menuItem" href="index.php?section=admin&go=judging_flights">Flights to Tables</a>
	<a class="menuItem" href="index.php?section=admin&go=judging_flights&action=rounds">Flights to Rounds</a>
	<a class="menuItem" href="index.php?section=admin&go=judging_scores">Scores by Table</a>
</div>
<div id="adminMenu_Organizing_Assign" class="menu">
	<a class="menuItem" href="index.php?section=admin&amp;action=assign&amp;go=judging&amp;filter=judges">Participants as Judges</a>
	<a class="menuItem" href="index.php?section=admin&amp;action=assign&amp;go=judging&amp;filter=stewardss">Participants as Stewards</a>
    <a class="menuItem" href="index.php?section=admin&amp;action=assign&amp;go=judging&amp;filter=staff">Participants as Staff</a>
<?php if (($totalRows_judging > 1) && ($row_prefs['prefsCompOrg'] == "N")) { ?>
	<a class="menuItem" href="index.php?section=admin&amp;action=update&amp;go=judging&amp;filter=judges">Judges to a Location</a>
	<a class="menuItem" href="index.php?section=admin&amp;action=update&amp;go=judging&amp;filter=stewards">Stewards to a Location</a>
<?php } ?>
	<a class="menuItem" href="index.php?section=admin&go=judging_tables&action=assign">Judges or Stewards to a Table</a>
</div>

<div id="adminMenu_Scoring_Manage" class="menu">
	<a class="menuItem" href="index.php?section=admin&amp;go=participants">Participants</a>
	<a class="menuItem" href="index.php?section=admin&amp;go=entries">Entries</a>
	<a class="menuItem" href="index.php?section=admin&amp;go=judging_tables">Tables</a>
	<a class="menuItem" href="index.php?section=admin&amp;go=judging_scores">Scores by Table</a>
	<a class="menuItem" href="index.php?section=admin&amp;go=judging_scores&amp;filter=category">Scores by Category</a>
	<a class="menuItem" href="index.php?section=admin&amp;go=judging_scores_bos">BOS Entries and Places</a>
</div>

<div id="adminMenu_Scoring_Add" class="menu">
	<a class="menuItem" href="index.php?section=admin&amp;go=judging_scores&amp;action=add">Scores by Table</a>
	<a class="menuItem" href="index.php?section=admin&amp;go=judging_scores&amp;action=add&amp;filter=category">Scores by Category</a>
</div>

<div id="adminMenu_Printing_Before" class="menu">
	<a class="menuItem thickbox" href="output/pullsheets.php?section=admin&amp;go=judging_tables&amp;id=default&amp;KeepThis=true&amp;TB_iframe=true&amp;height=450&amp;width=800" title="Print Pullsheets for All Tables">Pullsheets for All Tables</a>
	<a class="menuItem thickbox" href="output/table_cards.php?section=admin&amp;go=judging_tables&amp;id=default&amp;KeepThis=true&amp;TB_iframe=true&amp;height=450&amp;width=800" title="Print Table Cards for All Tables">Table Cards for All Tables</a>
	<a class="menuItem thickbox" href="output/assignments.php?section=admin&amp;go=judging_assignments&amp;filter=judges&amp;view=name&amp;KeepThis=true&amp;TB_iframe=true&amp;height=450&amp;width=800" title="Judging Assignments by Last Name">Judging Assignments by Last Name</a>
    <a class="menuItem thickbox" href="output/assignments.php?section=admin&amp;go=judging_assignments&amp;filter=stewards&amp;view=name&amp;KeepThis=true&amp;TB_iframe=true&amp;height=450&amp;width=800" title="Judging Assignments by Last Name">Judging Assignments by Last Name</a>
	<a class="menuItem thickbox" href="output/assignments.php?section=admin&amp;go=judging_assignments&amp;filter=judges&amp;view=sign-in&amp;KeepThis=true&amp;TB_iframe=true&amp;height=450&amp;width=800" title="Judge Sign-in Sheet">Judge Sign-in Sheet</a>
	<a class="menuItem thickbox" href="output/assignments.php?section=admin&amp;go=judging_assignments&amp;filter=stewards&amp;view=sign-in&amp;KeepThis=true&amp;TB_iframe=true&amp;height=450&amp;width=800" title="Steward Sign-in Sheet">Steward Sign-in Sheet</a>
</div>
<div id="adminMenu_Printing_During" class="menu">
	<a class="menuItem" href="output/pullsheets.php?section=admin&amp;go=judging_scores_bos&amp;KeepThis=true&amp;TB_iframe=true&amp;height=450&amp;width=800">All BOS Pullsheets</a>
</div>
<div id="adminMenu_Printing_After" class="menu">
	<a class="menuItem" href="output/results.php?section=admin&amp;go=judging_scores&amp;action=default&amp;filter=scores&amp;view=default&amp;KeepThis=true&amp;TB_iframe=true&amp;height=450&amp;width=800">Results by Table With Scores (All)</a>
	<a class="menuItem" href="output/results.php?section=admin&amp;go=judging_scores&amp;action=default&amp;filter=scores&amp;view=winners&amp;KeepThis=true&amp;TB_iframe=true&amp;height=450&amp;width=800">Results by Table With Scores (Winners Only)</a>
    <a class="menuItem" href="output/results.php?section=admin&amp;go=judging_scores&amp;action=default&amp;filter=none&amp;view=default&amp;KeepThis=true&amp;TB_iframe=true&amp;height=450&amp;width=800">Results by Table Without Scores (All)</a>
	<a class="menuItem" href="output/results.php?section=admin&amp;go=judging_scores&amp;action=default&amp;filter=none&amp;view=winners&amp;KeepThis=true&amp;TB_iframe=true&amp;height=450&amp;width=800">Results by Table Without Scores (Winners Only)</a>
    <a class="menuItem" href="output/results.php?section=admin&amp;go=judging_scores_bos&amp;action=default&amp;filter=bos&amp;view=default&amp;KeepThis=true&amp;TB_iframe=true&amp;height=450&amp;width=800">BOS Round(s) Results Report</a>
	<a class="menuItem" href="output/staff_points.php?section=admin&amp;go=judging_assignments&amp;action=download&amp;filter=default&amp;view=default&amp;KeepThis=true&amp;TB_iframe=true&amp;height=450&amp;width=800">BJCP Judge/Steward/Staff Points Report</a>
	<a class="menuItem" href="output/labels.php?section=admin&amp;go=judging_scores&amp;action=awards&amp;filter=default">Award Labels</a>
	<a class="menuItem" href="output/labels.php?section=admin&amp;go=participants&amp;action=address_labels&amp;filter=default">Participant Address Labels</a>
</div>

<div id="adminMenu_Exporting_Email" class="menu">
	<a class="menuItem" href="output/email_export.php">All Participants</a>
	<a class="menuItem" href="output/email_export.php?filter=judges">All Judges</a>
	<a class="menuItem" href="output/email_export.php?filter=stewards">All Stewards</a>
    <a class="menuItem" href="output/entries_export.php?section=admin&amp;go=csv&amp;action=email">All Entries</a>
	<a class="menuItem" href="output/entries_export.php?go=csv&amp;filter=paid&amp;action=email">Paid &amp; Received Entries</a>
	<a class="menuItem" href="output/entries_export.php?go=csv&amp;filter=nopay&amp;action=email">Non-Paid &amp; Received Entries</a>
</div>
<div id="adminMenu_Exporting_Tab" class="menu">
	<a class="menuItem" href="output/participants_export.php?go=tab">All Participants</a>
	<a class="menuItem" href="output/entries_export.php?go=tab&amp;filter=paid&amp;action=hccp">Paid &amp; Received Entries</a>
	<a class="menuItem" href="output/entries_export.php?go=tab">All Entries</a>
</div>
<div id="adminMenu_Exporting_CSV" class="menu">
	<a class="menuItem" href="output/entries_export.php?go=csv">All Entries</a>
	<a class="menuItem" href="output/entries_export.php?go=csv&amp;filter=paid&amp;action=hccp">Paid &amp; Received Entries</a>
	<a class="menuItem" href="output/entries_export.php?go=csv&amp;filter=nopay&amp;action=hccp">Non-Paid &amp; Received Entries</a>
    <a class="menuItem" href="output/participants_export.php?go=csv">All Participants</a>
	<a class="menuItem" href="output/participants_export.php?go=csv&amp;filter=winners">Winners</a>
</div>
<div id="adminMenu_Exporting_Promo" class="menu">
	<a class="menuItem" href="output/promo_export.php?action=html">HTML</a>
	<a class="menuItem" href="output/promo_export.php?action=word">Word</a>
</div>

<div id="adminMenu_Archiving_Manage" class="menu">
	<a class="menuItem" href="index.php?section=admin&amp;go=archive">Archive Competition Data</a>
</div>

<?php } ?>