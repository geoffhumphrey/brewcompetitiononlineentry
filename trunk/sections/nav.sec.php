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
  <li><?php if ($section != "default") { ?><a href="<?php if ($base_url == "") echo "index.php"; else echo $base_url; ?>">Home</a><?php } else { ?> Home<?php } ?></li>
  <li><?php if ($section != "rules") { ?><a href="<?php echo build_public_url("rules","default","default",$sef,$base_url); ?>">Rules</a><?php } else { ?>Rules<?php } ?></li>
  <li><?php if ($section != "entry") { ?><a href="<?php echo build_public_url("entry","default","default",$sef,$base_url); ?>">Entry Info</a><?php } else { ?>Entry Info<?php } ?></li>
  <li><?php if ($section != "volunteers") { ?><a href="<?php echo build_public_url("volunteers","default","default",$sef,$base_url); ?>">Volunteer Info</a><?php } else { ?>Volunteer Info<?php } ?></li>
  <?php if (($row_prefs['prefsSponsors'] == "Y") && ($row_prefs['prefsSponsorLogos'] == "Y") && ($totalRows_sponsors > 0)) { ?><li><?php if ($section != "sponsors") { ?><a href="<?php echo build_public_url("sponsors","default","default",$sef,$base_url); ?>">Sponsors</a><?php } else { ?>Sponsors<?php } ?></li><?php } ?>
  <?php if (get_contact_count() > 0) { ?>
  <li><?php if ($section != "contact") { ?><a href="<?php echo build_public_url("contact","default","default",$sef,$base_url); ?>">Contact</a><?php } else { ?>Contact<?php } ?></li>
  <?php } ?>
  <?php if (($registration_open == 1) && (!open_limit($totalRows_entry_count,$row_prefs['prefsEntryLimit'],$registration_open)))  { ?>
  <?php if (!isset($_SESSION["loginUsername"])) { ?><li><?php if ($section != "register") { ?><a href="<?php echo build_public_url("register","default","default",$sef,$base_url); ?>">Register</a><?php } else { ?>Register<?php } ?></li><?php } ?>
  <?php } ?>
  <?php if (($registration_open > "0") && (isset($_SESSION["loginUsername"])))  { ?>
  		<?php if (NHC) { ?>
  			<?php if (($row_contest_info['contestEntryFee'] > 0) && ($totalRows_log > 0)) { ?><li><?php if ($section != "pay") { ?><a href="<?php echo build_public_url("pay","default","default",$sef,$base_url); ?>">Pay My Fees</a><?php } else { ?>Pay My Fees<?php } ?></li><?php } ?>
  		<?php } else { ?> 
  			<?php if (($row_contest_info['contestEntryFee'] > 0) && (judging_date_return() > 0) && ($totalRows_log > 0)) { ?><li><?php if ($section != "pay") { ?><a href="<?php echo build_public_url("pay","default","default",$sef,$base_url); ?>">Pay My Fees</a><?php } else { ?>Pay My Fees<?php } ?></li><?php } ?>
  		<?php } ?>
  <li><div class="menuBar"><a class="menuButton" href="<?php echo build_public_url("list","default","default",$sef,$base_url); ?>" onclick="<?php echo build_public_url("list","default","default",$sef,$base_url); ?>" onmouseover="buttonMouseover(event, 'myInfoMenu');"><?php if ($section == "list") echo "<strong>My Info and Entries</strong>"; else echo "My Info and Entries"; ?></a></div></li>
  <?php } ?>
  <?php if ((isset($_SESSION["loginUsername"])) && ($row_user['userLevel'] <= "1")) { ?>
  <li><div class="menuBar"><a class="menuButton" href="<?php echo $base_url; ?>index.php?section=admin" onclick="<?php echo $base_url; ?>index.php?section=admin" onmouseover="buttonMouseover(event, 'adminMenu');"><?php if ($section == "admin") echo "<strong>Admin</strong>"; else echo "Admin"; ?></a></div></li>
<?php } ?>
  <li><a href="http://help.brewcompetition.com/index.html" title="BCOE&amp;M Help" target="_blank">BCOE&amp;M Help Site</a></li>
  <li><?php sessionAuthenticateNav(); ?></li>
</ul>
<?php } ?>
<?php if (($registration_open > "0") && (isset($_SESSION["loginUsername"])))  { ?> 
<div id="myInfoMenu" class="menu" onmouseover="menuMouseover(event)">
	<a class="menuItem" href="<?php echo build_public_url("list","default","default",$sef,$base_url); ?>">Info and List of Entries</a>
    <a class="menuItem"  href="<?php echo $base_url; ?>index.php?<?php if ($row_brewer['id'] != "") echo "section=brewer&amp;action=edit&amp;id=".$row_brewer['id']; else echo "action=add&amp;section=brewer&amp;go=judge"; ?>">Edit Your Info</a>
    <?php if (!NHC) { ?>
    <a class="menuItem"  href="<?php echo $base_url; ?>index.php?section=user&amp;action=username&amp;id=<?php echo $row_user['id']; ?>">Change Your Email Address</a>
	<?php } ?>
    <a class="menuItem"  href="<?php echo $base_url; ?>index.php?section=user&amp;action=password&amp;id=<?php echo $row_user['id']; ?>">Change Your Password</a>
    <?php if ($remaining_entries > 0) { ?>
	<a class="menuItem" href="<?php if ($row_user['userLevel'] <= "1") echo $base_url."index.php?section=brew&amp;go=entries&amp;action=add&amp;filter=admin"; else echo $base_url."index.php?section=brew&amp;action=add"; ?>">Add an Entry</a>
    <?php } ?>
    <?php if ($row_prefs['prefsHideRecipe'] == "N") { ?>
    <a class="menuItem" href="<?php echo build_public_url("beerxml","default","default",$sef,$base_url); ?>">Import Entries Using BeerXML</a>
    <?php } ?>
</div>
<?php } ?>
<?php if ((isset($_SESSION["loginUsername"])) && ($row_user['userLevel'] <= "1")) { ?>
<div id="adminMenu" class="menu" onmouseover="menuMouseover(event)">
<!-- Admin Main Link -->
	<a class="menuItem" href="<?php echo $base_url; ?>index.php?section=admin">Admin Dashboard</a>
<?php if ($row_user['userLevel'] == "0") { ?>
<?php if ($row_prefs['prefsUseMods'] == "Y") { ?>
<!-- Defining Preferences Menu -->
	<a class="menuItem" href="" onclick="return false;" onmouseover="menuItemMouseover(event, 'adminMenuCat_Custom');"><span class="menuItemText">Custom Modules</span><span class="menuItemArrow">&#9654;</span></a>
<?php } ?>
<!-- Defining Preferences Menu -->
	<a class="menuItem" href="" onclick="return false;" onmouseover="menuItemMouseover(event, 'adminMenuCat_Prefs');"><span class="menuItemText">Defining Preferences</span><span class="menuItemArrow">&#9654;</span></a>
<?php } ?>
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
<?php if ($row_user['userLevel'] == "0") { ?>	
<!-- Archiving Menu -->
	<a class="menuItem" href="" onclick="return false;" onmouseover="menuItemMouseover(event, 'adminMenuCat_Archiving');"><span class="menuItemText">Archiving</span><span class="menuItemArrow">&#9654;</span></a>
<?php } ?>
<!-- Bug Reporting -->
<a class="menuItem" href="http://code.google.com/p/brewcompetitiononlineentry/issues/entry" target="_blank">Report a Bug</a>
</div>

<?php if ($row_prefs['prefsUseMods'] == "Y") { ?>
<div id="adminMenuCat_Custom" class="menu">
	<a class="menuItem" href="" onclick="return false;" onmouseover="menuItemMouseover(event, 'adminMenu_Custom_Manage');"><span class="menuItemText">Manage/View</span><span class="menuItemArrow">&#9654;</span></a>
	<a class="menuItem" href="" onclick="return false;" onmouseover="menuItemMouseover(event, 'adminMenu_Custom_Add');"><span class="menuItemText">Add</span><span class="menuItemArrow">&#9654;</span></a>
</div>
<?php } ?>

<div id="adminMenuCat_Prefs" class="menu">
	<a class="menuItem" href="" onclick="return false;" onmouseover="menuItemMouseover(event, 'adminMenu_Prefs_Define');"><span class="menuItemText">Define</span><span class="menuItemArrow">&#9654;</span></a>
</div>

<div id="adminMenuCat_Preparing" class="menu">
	<a class="menuItem" href="" onclick="return false;" onmouseover="menuItemMouseover(event, 'adminMenu_Preparing_Manage');"><span class="menuItemText">Manage/View</span><span class="menuItemArrow">&#9654;</span></a>
	<a class="menuItem" href="" onclick="return false;" onmouseover="menuItemMouseover(event, 'adminMenu_Preparing_Add');"><span class="menuItemText">Add</span><span class="menuItemArrow">&#9654;</span></a>
    <?php if ($row_user['userLevel'] == "0") { ?>
	<a class="menuItem" href="" onclick="return false;" onmouseover="menuItemMouseover(event, 'adminMenu_Preparing_Edit');"><span class="menuItemText">Edit</span><span class="menuItemArrow">&#9654;</span></a>
	<a class="menuItem" href="" onclick="return false;" onmouseover="menuItemMouseover(event, 'adminMenu_Preparing_Upload');"><span class="menuItemText">Upload</span><span class="menuItemArrow">&#9654;</span></a>
    <?php } ?>
</div>

<div id="adminMenuCat_Entry" class="menu">
	<a class="menuItem" href="" onclick="return false;" onmouseover="menuItemMouseover(event, 'adminMenu_Entry_Manage');"><span class="menuItemText">Manage/View</span><span class="menuItemArrow">&#9654;</span></a>
	<a class="menuItem" href="" onclick="return false;" onmouseover="menuItemMouseover(event, 'adminMenu_Entry_Add');"><span class="menuItemText">Add</span><span class="menuItemArrow">&#9654;</span></a>
	<a class="menuItem" href="" onclick="return false;" onmouseover="menuItemMouseover(event, 'adminMenu_Entry_Assign');"><span class="menuItemText">Assign</span><span class="menuItemArrow">&#9654;</span></a>
</div>

<div id="adminMenuCat_Sorting" class="menu">
	<a class="menuItem" href="" onclick="return false;" onmouseover="menuItemMouseover(event, 'adminMenu_Sorting_Manage');"><span class="menuItemText">Manage/View</span><span class="menuItemArrow">&#9654;</span></a>
	<a class="menuItem" href="" onclick="return false;" onmouseover="menuItemMouseover(event, 'adminMenu_Sorting_Add');"><span class="menuItemText">Add</span><span class="menuItemArrow">&#9654;</span></a>
    <?php if (($row_user['userLevel'] == "0") && (!NHC)) { ?>
    <a class="menuItem" href="" onclick="return false;" onmouseover="menuItemMouseover(event, 'adminMenu_Sorting_Regenerate');"><span class="menuItemText">Regenerate</span><span class="menuItemArrow">&#9654;</span></a>
    <?php } ?>
    <?php if (!NHC) { ?>
	<a class="menuItem" href="" onclick="return false;" onmouseover="menuItemMouseover(event, 'adminMenu_Sorting_Print');"><span class="menuItemText">Print</span><span class="menuItemArrow">&#9654;</span></a>
    <?php } ?>
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
    <?php if (!NHC) { ?>
	<a class="menuItem" href="" onclick="return false;" onmouseover="menuItemMouseover(event, 'adminMenu_Exporting_Tab');"><span class="menuItemText">Tab Delimited Files</span><span class="menuItemArrow">&#9654;</span></a>
    <?php } ?>
	<a class="menuItem" href="" onclick="return false;" onmouseover="menuItemMouseover(event, 'adminMenu_Exporting_CSV');"><span class="menuItemText">CSV Files</span><span class="menuItemArrow">&#9654;</span></a>
    <?php if (!NHC) { ?>
	<a class="menuItem" href="" onclick="return false;" onmouseover="menuItemMouseover(event, 'adminMenu_Exporting_Promo');"><span class="menuItemText">Promo Materials</span><span class="menuItemArrow">&#9654;</span></a>
    <?php } ?>
</div>

<div id="adminMenuCat_Archiving" class="menu">
	<a class="menuItem" href="" onclick="return false;" onmouseover="menuItemMouseover(event, 'adminMenu_Archiving_Manage');"><span class="menuItemText">Manage/View</span><span class="menuItemArrow">&#9654;</span></a>

</div>
    
<!-- Third Tier Menus -->

<?php if ($row_prefs['prefsUseMods'] == "Y") { ?>
<div id="adminMenu_Custom_Manage" class="menu">
	<a class="menuItem" href="<?php echo $base_url; ?>index.php?section=admin&amp;go=mods">Custom Modules</a>
</div>
<div id="adminMenu_Custom_Add" class="menu">
	<a class="menuItem" href="<?php echo $base_url; ?>index.php?section=admin&amp;go=mods&amp;action=add">A Custom Module</a>
</div>
<?php } ?>
<?php if ($row_user['userLevel'] == "0") { ?>
<div id="adminMenu_Prefs_Define" class="menu">
	<a class="menuItem" href="<?php echo $base_url; ?>index.php?section=admin&amp;go=preferences">Site Preferences</a>
	<a class="menuItem" href="<?php echo $base_url; ?>index.php?section=admin&amp;go=judging_preferences">Competition Organization Preferences</a>
</div>
<?php } ?>

<div id="adminMenu_Preparing_Manage" class="menu">
	<?php if ($row_user['userLevel'] == "0") { ?>
	<a class="menuItem" href="<?php echo $base_url; ?>index.php?section=admin&amp;go=style_types">Style Types</a>
    <a class="menuItem" href="<?php echo $base_url; ?>index.php?section=admin&amp;go=styles">Accepted Style Categories</a>
    <a class="menuItem" href="<?php echo $base_url; ?>index.php?section=admin&amp;go=special_best">Custom Winning Categories</a>
    <a class="menuItem" href="<?php echo $base_url; ?>index.php?section=admin&amp;go=special_best_data">Custom Winning Category Entries</a>
    <?php } ?>
    <a class="menuItem" href="<?php echo $base_url; ?>index.php?section=admin&amp;go=judging">Judging Locations &amp; Dates</a>
    <a class="menuItem" href="<?php echo $base_url; ?>index.php?section=admin&amp;go=contacts">Competition Contacts</a>
    <a class="menuItem" href="<?php echo $base_url; ?>index.php?section=admin&amp;go=dropoff">Drop-Off Locations</a>
    <?php if ($row_user['userLevel'] == "0") { ?>
    <a class="menuItem" href="<?php echo $base_url; ?>index.php?section=admin&amp;go=sponsors">Sponsors</a>
    <?php } ?>
</div>
<div id="adminMenu_Preparing_Add" class="menu">
	<?php if ($row_user['userLevel'] == "0") { ?>
	<a class="menuItem" href="<?php echo $base_url; ?>index.php?section=admin&amp;go=style_types&amp;action=add">A Style Type</a>
    <a class="menuItem" href="<?php echo $base_url; ?>index.php?section=admin&amp;go=styles&amp;action=add">A Custom Style Category</a>
    <a class="menuItem" href="<?php echo $base_url; ?>index.php?section=admin&amp;go=special_best">A Custom Winning Category</a>
    <?php } ?>
    <a class="menuItem" href="<?php echo $base_url; ?>index.php?section=admin&amp;go=judging&amp;action=add">A Judging Location</a>
    <a class="menuItem" href="<?php echo $base_url; ?>index.php?section=admin&amp;go=contacts&amp;action=add">A Competition Contact</a>
    <a class="menuItem" href="<?php echo $base_url; ?>index.php?section=admin&amp;go=dropoff&amp;action=add">A Drop-Off Location</a>
    <?php if ($row_user['userLevel'] == "0") { ?>
    <a class="menuItem" href="<?php echo $base_url; ?>index.php?section=admin&amp;go=sponsors&amp;action=add">A Sponsor</a>
    <?php } ?>
</div>
<div id="adminMenu_Preparing_Edit" class="menu">
	<a class="menuItem" href="<?php echo $base_url; ?>index.php?section=admin&amp;go=contest_info">Competition Info</a>
</div>
<div id="adminMenu_Preparing_Upload" class="menu">
	<a id="modal_window_link" class="menuItem" href="<?php echo $base_url; ?>admin/upload.admin.php" title="Upload Competition Logo Image">A Competition Logo</a>
	<a id="modal_window_link" class="menuItem" href="<?php echo $base_url; ?>admin/upload.admin.php" title="Upload Sponsor Logo Image">A Sponsor Logo</a>
</div>

<div id="adminMenu_Entry_Manage" class="menu">
<?php if ($row_user['userLevel'] == "0") { ?>	
	<a class="menuItem" href="<?php echo $base_url; ?>index.php?section=admin&amp;go=style_types">Style Types</a>
    <a class="menuItem" href="<?php echo $base_url; ?>index.php?section=admin&amp;go=styles">Accepted Style Categories</a>
<?php } ?>
	<a class="menuItem" href="<?php echo $base_url; ?>index.php?section=admin&amp;go=participants">Participants</a>
	<a class="menuItem" href="<?php echo $base_url; ?>index.php?section=admin&amp;go=entries">Entries</a>
	<a class="menuItem" href="<?php echo $base_url; ?>index.php?section=admin&amp;go=judging">Judging Locations &amp; Dates</a>		
    <a class="menuItem" href="<?php echo $base_url; ?>index.php?section=admin&amp;go=participants&amp;filter=judges">Available Judges</a>
	<a class="menuItem" href="<?php echo $base_url; ?>index.php?section=admin&amp;go=participants&amp;filter=stewards">Available Stewards</a>
<?php if ($row_user['userLevel'] == "0") { ?>	
	<a class="menuItem" href="<?php echo $base_url; ?>index.php?section=admin&amp;go=contacts">Competition Contacts</a>
	<a class="menuItem" href="<?php echo $base_url; ?>index.php?section=admin&amp;go=dropoff">Drop-Off Locations</a>
	<a class="menuItem" href="<?php echo $base_url; ?>index.php?section=admin&amp;go=sponsors">Sponsors</a>
<?php } ?>
</div>
<div id="adminMenu_Entry_Add" class="menu">
<?php if ($row_user['userLevel'] == "0") { ?>	
	<a class="menuItem" href="<?php echo $base_url; ?>index.php?section=admin&amp;go=style_types&amp;action=add">A Style Type</a>
	<a class="menuItem" href="<?php echo $base_url; ?>index.php?section=admin&amp;go=styles&amp;action=add">A Custom Style Category</a>
<?php } ?>
	<a class="menuItem" href="<?php echo $base_url; ?>index.php?section=admin&amp;go=entrant&amp;action=register">A Participant</a>
    <a class="menuItem" href="<?php echo $base_url; ?>index.php?section=admin&amp;go=judge&amp;action=register">A Participant as a Judge/Steward</a></li>
<?php if ($row_user['userLevel'] == "0") { ?>	
    <a class="menuItem" href="<?php echo $base_url; ?>index.php?section=admin&amp;go=judging&amp;action=add">A Judging Location</a>
    <a class="menuItem" href="<?php echo $base_url; ?>index.php?section=admin&amp;go=contacts&amp;action=add">A Competition Contact</a>
	<a class="menuItem" href="<?php echo $base_url; ?>index.php?section=admin&amp;go=dropoff&amp;action=add">A Drop-Off Location</a>
	<a class="menuItem" href="<?php echo $base_url; ?>index.php?section=admin&amp;go=sponsors&amp;action=add">A Sponsor</a>
<?php } ?>
</div>
<div id="adminMenu_Entry_Assign" class="menu">
	<a class="menuItem" href="<?php echo $base_url; ?>index.php?section=admin&amp;action=assign&amp;go=judging&amp;filter=judges">Participants as Judges</a>
	<a class="menuItem" href="<?php echo $base_url; ?>index.php?section=admin&amp;action=assign&amp;go=judging&amp;filter=stewards">Participants as Stewards</a>
    <a class="menuItem" href="<?php echo $base_url; ?>index.php?section=admin&amp;action=assign&amp;go=judging&amp;filter=staff">Participants as Staff</a>
</div>

<div id="adminMenu_Sorting_Manage" class="menu" onmouseover="menuMouseover(event)">
	<a class="menuItem" href="<?php echo $base_url; ?>index.php?section=admin&amp;go=participants">Participants</a>
	<a class="menuItem" href="<?php echo $base_url; ?>index.php?section=admin&go=entries">Entries</a>
</div>
<?php if (($row_user['userLevel'] == "0") && (!NHC)) { ?>
<div id="adminMenu_Sorting_Regenerate" class="menu" onmouseover="menuMouseover(event)">
	<a class="menuItem" onclick="return confirm('Are you sure you want to regenerate judging numbers for all entries?');" href="<?php echo $base_url; ?>includes/process.inc.php?section=admin&amp;go=entries&amp;action=generate_judging_numbers&amp;sort=id&amp;dir=ASC">Entry Judging Numbers</a>
</div>
<?php } ?>
<div id="adminMenu_Sorting_Add" class="menu" onmouseover="menuMouseover(event)">
	<a class="menuItem" href="<?php echo $base_url; ?>index.php?section=admin&amp;go=entrant&amp;action=register">A Participant</a>
    <a class="menuItem" href="<?php echo $base_url; ?>index.php?section=admin&amp;go=judge&amp;action=register">A Participant as a Judge/Steward</a>
</div>
<div id="adminMenu_Sorting_Print" class="menu" onmouseover="menuMouseover(event)">
	<a class="menuItem" href="<?php echo $base_url; ?>output/sorting.php?section=admin&amp;go=default&amp;filter=default">Sorting Sheets - All Categories</a>
    <a class="menuItem" href="<?php echo $base_url; ?>output/labels.php?section=admin&amp;go=entries&amp;action=bottle-entry&amp;filter=default">Bottle Labels Using Entry Numbers - All Categories</a>
    <a class="menuItem" href="<?php echo $base_url; ?>output/labels.php?section=admin&amp;go=entries&amp;action=bottle-judging&amp;filter=default">Bottle Labels Using Judging Numbers - All Categories</a>
</div>

<div id="adminMenu_Organizing_Manage" class="menu" onmouseover="menuMouseover(event)">
	<a class="menuItem" href="<?php echo $base_url; ?>index.php?section=admin&amp;go=participants">Participants</a>
	<a class="menuItem" href="<?php echo $base_url; ?>index.php?section=admin&go=entries">Entries</a>
	<a class="menuItem" href="<?php echo $base_url; ?>index.php?section=admin&amp;go=judging_tables">Tables</a>
    <?php if ($row_judging_prefs['jPrefsQueued'] == "N") { ?>
    <a class="menuItem" href="<?php echo $base_url; ?>index.php?section=admin&amp;go=judging_flights">Flights</a>
    <?php } ?>
    <?php if ($row_user['userLevel'] == "0") { ?>
    <a class="menuItem" href="<?php echo $base_url; ?>index.php?section=admin&go=styles&filter=orphans">Styles Without a Valid Style Type</a>
    <?php } ?>
	<a class="menuItem" href="<?php echo $base_url; ?>index.php?section=admin&go=judging_tables&filter=orphans">Styles Not Assigned to Tables</a>
	<a class="menuItem" href="<?php echo $base_url; ?>index.php?section=admin&amp;go=participants&amp;filter=assignJudges">Assigned Judges</a>
	<a class="menuItem" href="<?php echo $base_url; ?>index.php?section=admin&amp;go=participants&amp;filter=assignStewards">Assigned Stewards</a>
</div>
<div id="adminMenu_Organizing_Add" class="menu" onmouseover="menuMouseover(event)">
	<a class="menuItem" href="<?php echo $base_url; ?>index.php?section=admin&go=entrant&action=register">A Participant</a>
    <a class="menuItem" href="<?php echo $base_url; ?>index.php?section=admin&amp;go=judge&amp;action=register">A Participant as a Judge/Steward</a></li>
	<a class="menuItem" href="<?php echo $base_url; ?>index.php?section=admin&go=judging_tables&action=add">A Table</a>
    <?php if ($row_judging_prefs['jPrefsQueued'] == "N") { ?>
    <a class="menuItem" href="<?php echo $base_url; ?>index.php?section=admin&go=judging_tables&action=add">Flights to Tables</a>
    <?php } ?>
</div>
<div id="adminMenu_Organizing_Assign" class="menu">
	<a class="menuItem" href="<?php echo $base_url; ?>index.php?section=admin&amp;action=assign&amp;go=judging&amp;filter=judges">Participants as Judges</a>
	<a class="menuItem" href="<?php echo $base_url; ?>index.php?section=admin&amp;action=assign&amp;go=judging&amp;filter=stewards">Participants as Stewards</a>
    <a class="menuItem" href="<?php echo $base_url; ?>index.php?section=admin&amp;action=assign&amp;go=judging&amp;filter=staff">Participants as Staff</a>
	<?php  if ($row_judging_prefs['jPrefsQueued'] == "Y") { ?>
	<a class="menuItem" href="<?php echo $base_url; ?>index.php?section=admin&go=judging_flights&amp;action=assign&amp;filter=rounds">Tables to Rounds</a>
	<?php } if ($row_judging_prefs['jPrefsQueued'] == "N") { ?>
    <a class="menuItem" href="<?php echo $base_url; ?>index.php?section=admin&amp;go=judging_flights&amp;action=rounds">Flights to Rounds</a>
	<?php } ?>
    <a class="menuItem" href="<?php echo $base_url; ?>index.php?section=admin&amp;go=judging_tables&amp;action=assign">Judges or Stewards to a Table</a>
    <a class="menuItem" href="<?php echo $base_url; ?>index.php?section=admin&amp;action=assign&amp;go=judging&amp;filter=bos">Best of Show Judges</a>
</div>

<div id="adminMenu_Scoring_Manage" class="menu">
	<a class="menuItem" href="<?php echo $base_url; ?>index.php?section=admin&amp;go=participants">Participants</a>
	<a class="menuItem" href="<?php echo $base_url; ?>index.php?section=admin&amp;go=entries">Entries</a>
	<a class="menuItem" href="<?php echo $base_url; ?>index.php?section=admin&amp;go=judging_tables">Tables</a>
	<a class="menuItem" href="<?php echo $base_url; ?>index.php?section=admin&amp;go=judging_scores">Scores by Table</a>
	<a class="menuItem" href="<?php echo $base_url; ?>index.php?section=admin&amp;go=judging_scores&amp;filter=category">Scores by Category</a>
	<a class="menuItem" href="<?php echo $base_url; ?>index.php?section=admin&amp;go=judging_scores_bos">BOS Entries and Places</a>
	<a class="menuItem" href="<?php echo $base_url; ?>index.php?section=admin&amp;go=special_best">Custom Winning Categoires</a>
	<a class="menuItem" href="<?php echo $base_url; ?>index.php?section=admin&amp;go=special_best_data">Custom Winning Category Entries</a>
</div>

<div id="adminMenu_Scoring_Add" class="menu">
	<a class="menuItem" href="<?php echo $base_url; ?>index.php?section=admin&amp;go=judging_scores&amp;action=add">Scores by Table</a>
	<a class="menuItem" href="<?php echo $base_url; ?>index.php?section=admin&amp;go=judging_scores&amp;action=add&amp;filter=category">Scores by Category</a>
</div>

<div id="adminMenu_Printing_Before" class="menu">
<?php if ($totalRows_tables > 0) { ?>
	<a id="modal_window_link" class="menuItem" href="<?php echo $base_url; ?>output/pullsheets.php?section=admin&amp;go=judging_tables&amp;id=default" title="Print Pullsheets for All Tables">Pullsheets for All Tables</a>
	<a id="modal_window_link" class="menuItem" href="<?php echo $base_url; ?>output/table_cards.php?section=admin&amp;go=judging_tables&amp;id=default" title="Print Table Cards for All Tables">Table Cards for All Tables</a>
	<a id="modal_window_link" class="menuItem" href="<?php echo $base_url; ?>output/assignments.php?section=admin&amp;go=judging_assignments&amp;filter=judges&amp;view=name" title="Judging Assignments by Last Name">Judge Assignments by Last Name</a>
    <a id="modal_window_link" class="menuItem" href="<?php echo $base_url; ?>output/assignments.php?section=admin&amp;go=judging_assignments&amp;filter=stewards&amp;view=name" title="Judging Assignments by Last Name">Steward Assignments by Last Name</a>
    <?php } ?>
    <a id="modal_window_link" class="menuItem" href="<?php echo $base_url; ?>output/assignments.php?section=admin&amp;go=judging_assignments&amp;filter=judges&amp;view=sign-in" title="Judge Sign-in Sheet">Judge Sign-in Sheet</a>
	<a id="modal_window_link" class="menuItem" href="<?php echo $base_url; ?>output/assignments.php?section=admin&amp;go=judging_assignments&amp;filter=stewards&amp;view=sign-in" title="Steward Sign-in Sheet">Steward Sign-in Sheet</a>
    <a class="menuItem" href="<?php echo $base_url; ?>output/labels.php?section=admin&amp;go=participants&amp;action=judging_labels">Judge Scoresheet Labels (All)</a>
    <a class="menuItem" href="<?php echo $base_url; ?>output/labels.php?section=admin&amp;go=participants&amp;action=judging_nametags">Judge, Steward and Staff Name Tags (All)</a>
</div>
<?php if ($row_scores['count'] > 0) { ?>
<div id="adminMenu_Printing_During" class="menu">
	<a id="modal_window_link" class="menuItem" href="<?php echo $base_url; ?>output/pullsheets.php?section=admin&amp;go=judging_scores_bos">All BOS Pullsheets</a>
</div>
<?php } ?>
<div id="adminMenu_Printing_After" class="menu">
	<?php if ($totalRows_tables > 0) { 
	$method = winner_method($row_prefs['prefsWinnerMethod'],1);
	?>
	<a id="modal_window_link" class="menuItem" href="<?php echo $base_url; ?>output/results.php?section=admin&amp;go=judging_scores&amp;action=print&amp;filter=scores&amp;view=default" title="Results Report <?php echo $method; ?> (All with Scores)">Print Results Report <?php echo $method; ?> (All with Scores)</a>
	<a id="modal_window_link" class="menuItem" href="<?php echo $base_url; ?>output/results.php?section=admin&amp;go=judging_scores&amp;action=print&amp;filter=scores&amp;view=winners" title="Results Report <?php echo $method; ?> (Winners Only with Scores)">Print Results Report <?php echo $method; ?> (Winners Only with Scores)</a>
    <a id="modal_window_link" class="menuItem" href="<?php echo $base_url; ?>output/results.php?section=admin&amp;go=judging_scores&amp;action=print&amp;filter=none&amp;view=default" title="Results Report <?php echo $method; ?> (All Without Scores)">Print Report <?php echo $method; ?> (All Without Scores)</a>
	<a id="modal_window_link" class="menuItem" href="<?php echo $base_url; ?>output/results.php?section=admin&amp;go=judging_scores&amp;action=print&amp;filter=none&amp;view=winners" title="Results Report <?php echo $method; ?> (Winners Only Without Scores)">Print Results Report <?php echo $method; ?> (Winners Only Without Scores)</a>
	<a id="modal_window_link" class="menuItem" href="<?php echo $base_url; ?>output/results.php?section=admin&amp;go=judging_scores_bos&amp;action=print&amp;filter=bos&amp;view=default" title="BOS Round(s) Results Report">Print BOS Round Results</a>
	<a id="modal_window_link" class="menuItem" href="<?php echo $base_url; ?>output/staff_points.php?section=admin&amp;go=judging_assignments&amp;action=download&amp;filter=default&amp;view=default" title="BJCP Judge/Steward/Staff Points Report">Print BJCP Judge/Steward/Staff Points Report</a>
	<?php if ($row_scores['count'] > 0) { ?>
    <a class="menuItem" href="<?php echo $base_url; ?>output/labels.php?section=admin&amp;go=judging_scores&amp;action=awards&amp;filter=default">Print Award Labels (PDF)</a>
    <?php } 
	} ?>
	<a class="menuItem" href="<?php echo $base_url; ?>output/labels.php?section=admin&amp;go=participants&amp;action=address_labels&amp;filter=default">Print Participant Address Labels (PDF)</a>
</div>

<div id="adminMenu_Exporting_Email" class="menu">
	<a class="menuItem" href="<?php echo $base_url; ?>output/email_export.php">All Participants</a>
	<a class="menuItem" href="<?php echo $base_url; ?>output/email_export.php?section=admin&amp;go=csv&amp;filter=judges&amp;action=email">All Judges</a>
	<a class="menuItem" href="<?php echo $base_url; ?>output/email_export.php?section=admin&amp;go=csv&amp;filter=stewards&amp;action=email">All Stewards</a>
    <a class="menuItem" href="<?php echo $base_url; ?>output/entries_export.php?section=admin&amp;go=csv&amp;action=email">All Entries</a>
    <a class="menuItem" href="<?php echo $base_url; ?>output/entries_export.php?section=admin&amp;go=csv&amp;filter=paid&amp;action=email&amp;view=all">All Paid Entries</a>
	<a class="menuItem" href="<?php echo $base_url; ?>output/entries_export.php?section=admin&amp;go=csv&amp;filter=paid&amp;action=email">Paid &amp; Received Entries</a>
    <a class="menuItem" href="<?php echo $base_url; ?>output/entries_export.php?section=admin&amp;go=csv&amp;filter=nopay&amp;action=email&amp;action=hccp">All Non-Paid  Entries</a>
	<a class="menuItem" href="<?php echo $base_url; ?>output/entries_export.php?section=admin&amp;go=csv&amp;filter=nopay&amp;action=email">Non-Paid &amp; Received Entries</a>
</div>
<div id="adminMenu_Exporting_Tab" class="menu">
	<a class="menuItem" href="<?php echo $base_url; ?>output/participants_export.php?section=admin&amp;go=tab&amp;action=hccp">All Participants</a>
	<a class="menuItem" href="<?php echo $base_url; ?>output/entries_export.php?section=admin&amp;go=tab&amp;filter=paid&amp;action=hccp">Paid &amp; Received Entries</a>
	<a class="menuItem" href="<?php echo $base_url; ?>output/entries_export.php?section=admin&amp;go=tab&amp;action=hccp">All Entries</a>
</div>
<div id="adminMenu_Exporting_CSV" class="menu">
    <a class="menuItem" href="<?php echo $base_url; ?>output/entries_export.php?section=admin&amp;go=csv&amp;action=all&amp;filter=all">All Entries (All Data)</a>
    <a class="menuItem" href="<?php echo $base_url; ?>output/entries_export.php?section=admin&amp;go=csv">All Entries (Limited Data)</a>
    <a class="menuItem" href="<?php echo $base_url; ?>output/entries_export.php?section=admin&amp;go=csv&amp;filter=paid&amp;view=all">All Paid Entries</a>
	<a class="menuItem" href="<?php echo $base_url; ?>output/entries_export.php?section=admin&amp;go=csv&amp;filter=paid&amp;action=hccp">Paid &amp; Received Entries</a>
    <a class="menuItem" href="<?php echo $base_url; ?>output/entries_export.php?section=admin&amp;go=csv&amp;filter=nopay&amp;view=all">All Non-Paid Entries</a>
	<a class="menuItem" href="<?php echo $base_url; ?>output/entries_export.php?section=admin&amp;go=csv&amp;filter=nopay&amp;action=hccp">Non-Paid &amp; Received Entries</a>
    <a class="menuItem" href="<?php echo $base_url; ?>output/participants_export.php?section=admin&amp;go=csv">All Participants</a>
	<a class="menuItem" href="<?php echo $base_url; ?>output/participants_export.php?section=admin&amp;go=csv&amp;filter=winners">Winners</a>
</div>
<div id="adminMenu_Exporting_Promo" class="menu">
	<a class="menuItem" href="<?php echo $base_url; ?>output/promo_export.php?section=admin&amp;action=html">HTML</a>
	<a class="menuItem" href="<?php echo $base_url; ?>output/promo_export.php?section=admin&amp;action=word">Word</a>
    <a class="menuItem" href="<?php echo $base_url; ?>output/promo_export.php?section=admin&amp;go=word&amp;action=bbcode">Bulletin Board Code (BBC)</a>
</div>

<div id="adminMenu_Archiving_Manage" class="menu">
	<a class="menuItem" href="<?php echo $base_url; ?>index.php?section=admin&amp;go=archive">Archived Competition Data</a>
</div>

<?php } ?>