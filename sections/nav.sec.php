<?php if (strstr($section,"step")) { ?>
<div class="setupTitle">Set Up Your Brew Competition Online Entry and Managment Site</div>
<?php } else { ?>
<ul id="nav">
  <li><?php if ($section != "default") { ?><a href="index.php"><?php echo $row_contest_info['contestName']; ?> Home</a><?php } else { echo $row_contest_info['contestName']; ?> Home<?php } ?></li>
  <li><?php if ($section != "rules") { ?><a href="index.php?section=rules">Rules</a><?php } else { ?>Rules<?php } ?></li>
  <li><?php if ($section != "entry") { ?><a href="index.php?section=entry">Entry Information</a><?php } else { ?>Entry Information<?php } ?></li>
  <?php if (($row_prefs['prefsSponsors'] == "Y") && ($row_prefs['prefsSponsorLogos'] == "Y") && ($totalRows_sponsors > 0)) { ?><li><?php if ($section != "sponsors") { ?><a href="index.php?section=sponsors">Sponsors</a><?php } else { ?>Sponsors<?php } ?></li><?php } ?>
  <?php if (getContactCount() > 0) { ?>
  <li><?php if ($section != "contact") { ?><a href="index.php?section=contact">Contact</a><?php } else { ?>Contact<?php } ?></li>
  <?php } ?>
  <?php if (lesserDate($today,$row_contest_info['contestRegistrationDeadline']) && (!lesserDate($today,$row_contest_info['contestRegistrationOpen'])))  { ?>
  <?php if (!isset($_SESSION["loginUsername"])) { ?><li><?php if ($section != "register") { ?><a href="index.php?section=register">Register</a><?php } else { ?>Register<?php } ?></li><?php } ?>
  <?php if ((isset($_SESSION["loginUsername"])) && ($row_contest_info['contestEntryFee'] > 0)) { ?><li><?php if ($section != "pay") { ?><a href="index.php?section=pay&bid=<?php echo $row_user['id']; ?>">Pay My Fees</a><?php } else { ?>Pay My Fees<?php } ?></li><?php } ?>
  <?php } ?>
  <?php if (isset($_SESSION["loginUsername"])) { ?><li><?php if ($section != "list") { ?><a href="index.php?section=list">My Info and Entries</a><?php } else { ?>My Info and Entries<?php } ?></li><?php } ?>
  <?php if ((isset($_SESSION["loginUsername"])) && ($row_user['userLevel'] == "1")) { ?><li><div class="menuBar"><a class="menuButton" href="index.php?section=admin" onclick="index.php?section=admin" onmouseover="buttonMouseover(event, 'adminMenu');">Admin</a>
</div><?php } ?>
  <li><?php sessionAuthenticateNav(); ?></li>
</ul>
<?php } ?>
<?php if ((isset($_SESSION["loginUsername"])) && ($row_user['userLevel'] == "1")) { ?>
<!-- 1st Tier Sub Menus -->
<div id="adminMenu" class="menu" onmouseover="menuMouseover(event)">
<a class="menuItem" href="" onclick="return false;" onmouseover="menuItemMouseover(event, 'adminMenuCat3');"><span class="menuItemText">Preferences</span><span class="menuItemArrow">&#9654;</span></a>
<a class="menuItem" href="" onclick="return false;" onmouseover="menuItemMouseover(event, 'adminMenuCat4');"><span class="menuItemText">Competition Info</span><span class="menuItemArrow">&#9654;</span></a>
<a class="menuItem" href="" onclick="return false;" onmouseover="menuItemMouseover(event, 'adminMenuCat0');"><span class="menuItemText">Entry and Data Gathering</span><span class="menuItemArrow">&#9654;</span></a>
<a class="menuItem" href="" onclick="return false;" onmouseover="menuItemMouseover(event, 'adminMenuCat1');"><span class="menuItemText">Sorting Received Entries</span><span class="menuItemArrow">&#9654;</span></a>
<a class="menuItem" href="" onclick="return false;" onmouseover="menuItemMouseover(event, 'adminMenuCat2');"><span class="menuItemText">Organizing</span><span class="menuItemArrow">&#9654;</span></a>
<a class="menuItem" href="" onclick="return false;" onmouseover="menuItemMouseover(event, 'adminMenuCat5');"><span class="menuItemText">Scoring</span><span class="menuItemArrow">&#9654;</span></a>
<a class="menuItem" href="" onclick="return false;" onmouseover="menuItemMouseover(event, 'adminMenuCat6');"><span class="menuItemText">Printing and Reporting</span><span class="menuItemArrow">&#9654;</span></a>
<a class="menuItem" href="" onclick="return false;" onmouseover="menuItemMouseover(event, 'adminMenuCat7');"><span class="menuItemText">Exporting</span><span class="menuItemArrow">&#9654;</span></a>
<a class="menuItem" href="" onclick="return false;" onmouseover="menuItemMouseover(event, 'adminMenu1');"><span class="menuItemText">Archives</span><span class="menuItemArrow">&#9654;</span></a>
<a class="menuItem" href="http://code.google.com/p/brewcompetitiononlineentry/issues/entry" target="_blank">Report a Bug</a>
</div>

<div id="adminMenuCat0" class="menu">
<a class="menuItem" href="" onclick="return false;" onmouseover="menuItemMouseover(event, 'adminMenu2');"><span class="menuItemText">Manage/View</span><span class="menuItemArrow">&#9654;</span></a>
<a class="menuItem" href="" onclick="return false;" onmouseover="menuItemMouseover(event, 'adminMenu0');"><span class="menuItemText">Add</span><span class="menuItemArrow">&#9654;</span></a>
</div>

<div id="adminMenuCat1" class="menu">
<a class="menuItem" href="" onclick="return false;" onmouseover="menuItemMouseover(event, 'adminMenu7');"><span class="menuItemText">Manage/View</span><span class="menuItemArrow">&#9654;</span></a>
<a class="menuItem" href="" onclick="return false;" onmouseover="menuItemMouseover(event, 'adminMenu8');"><span class="menuItemText">Add</span><span class="menuItemArrow">&#9654;</span></a>
</div>

<div id="adminMenuCat2" class="menu">
<a class="menuItem" href="" onclick="return false;" onmouseover="menuItemMouseover(event, 'adminMenu9');"><span class="menuItemText">Manage/View</span><span class="menuItemArrow">&#9654;</span></a>
<a class="menuItem" href="" onclick="return false;" onmouseover="menuItemMouseover(event, 'adminMenu10');"><span class="menuItemText">Add</span><span class="menuItemArrow">&#9654;</span></a>
<a class="menuItem" href="" onclick="return false;" onmouseover="menuItemMouseover(event, 'adminMenu6');"><span class="menuItemText">Assign</span><span class="menuItemArrow">&#9654;</span></a>
</div>

<div id="adminMenuCat3" class="menu">
<a class="menuItem" href="" onclick="return false;" onmouseover="menuItemMouseover(event, 'adminMenu11');"><span class="menuItemText">Edit</span><span class="menuItemArrow">&#9654;</span></a>
</div>

<div id="adminMenuCat4" class="menu">
<a class="menuItem" href="" onclick="return false;" onmouseover="menuItemMouseover(event, 'adminMenu12');"><span class="menuItemText">Edit</span><span class="menuItemArrow">&#9654;</span></a>
<a class="menuItem" href="" onclick="return false;" onmouseover="menuItemMouseover(event, 'adminMenu13');"><span class="menuItemText">Upload</span><span class="menuItemArrow">&#9654;</span></a>
</div>

<div id="adminMenuCat5" class="menu">
<a class="menuItem" href="" onclick="return false;" onmouseover="menuItemMouseover(event, 'adminMenu14');"><span class="menuItemText">Manage/View</span><span class="menuItemArrow">&#9654;</span></a>
<a class="menuItem" href="" onclick="return false;" onmouseover="menuItemMouseover(event, 'adminMenu15');"><span class="menuItemText">Add</span><span class="menuItemArrow">&#9654;</span></a>
</div>

<div id="adminMenuCat6" class="menu">
<a class="menuItem" href="" onclick="return false;" onmouseover="menuItemMouseover(event, 'adminMenu16');"><span class="menuItemText">Pre-Competition</span><span class="menuItemArrow">&#9654;</span></a>
<a class="menuItem" href="" onclick="return false;" onmouseover="menuItemMouseover(event, 'adminMenu17');"><span class="menuItemText">During Competition</span><span class="menuItemArrow">&#9654;</span></a>
<a class="menuItem" href="" onclick="return false;" onmouseover="menuItemMouseover(event, 'adminMenu18');"><span class="menuItemText">Post-Competition</span><span class="menuItemArrow">&#9654;</span></a>
</div>

<div id="adminMenuCat7" class="menu">
<a class="menuItem" href="" onclick="return false;" onmouseover="menuItemMouseover(event, 'adminMenu19');"><span class="menuItemText">Email Addresses (CSV Files)</span><span class="menuItemArrow">&#9654;</span></a>
<a class="menuItem" href="" onclick="return false;" onmouseover="menuItemMouseover(event, 'adminMenu20');"><span class="menuItemText">Tab Delimited Files</span><span class="menuItemArrow">&#9654;</span></a>
<a class="menuItem" href="" onclick="return false;" onmouseover="menuItemMouseover(event, 'adminMenu21');"><span class="menuItemText">CSV Files</span><span class="menuItemArrow">&#9654;</span></a>
<a class="menuItem" href="" onclick="return false;" onmouseover="menuItemMouseover(event, 'adminMenu22');"><span class="menuItemText">Promo Materials</span><span class="menuItemArrow">&#9654;</span></a>
</div>

<div id="adminMenu0" class="menu">
<a class="menuItem" href="index.php?section=admin&amp;go=participants&amp;action=add">A Participant</a>
<a class="menuItem" href="index.php?section=brew&amp;go=entries&amp;action=add&amp;filter=admin">A Participant's Entry</a>
<a class="menuItem" href="index.php?section=admin&amp;go=judging&amp;action=add">A Judging Location</a>
<a class="menuItem" href="index.php?section=admin&amp;go=styles&amp;action=add">A Custom Style Category</a>
<a class="menuItem" href="index.php?section=admin&amp;go=dropoff&amp;action=add">A Drop-Off Location</a>
<a class="menuItem" href="index.php?section=admin&amp;go=sponsors&amp;action=add">A Sponsor</a>
<a class="menuItem" href="index.php?section=admin&amp;go=contacts&amp;action=add">A Competition Contact</a>
</div>

<div id="adminMenu1" class="menu">
<a class="menuItem" href="index.php?section=admin&amp;go=archive">Archive Competition Data</a>
</div>

<div id="adminMenu2" class="menu">
<a class="menuItem" href="index.php?section=admin&amp;go=participants">Participants</a>
<a class="menuItem" href="index.php?section=admin&amp;go=entries">Entries</a>
<a class="menuItem" href="index.php?section=admin&amp;go=styles">Accepted Style Categories</a>
<a class="menuItem" href="index.php?section=admin&go=styles&filter=judging">Style Categories for Judging Locations</a>
<a class="menuItem" href="index.php?section=admin&amp;go=judging">Judging Locations</a>
<a class="menuItem" href="index.php?section=admin&amp;go=participants&amp;filter=judges">Available Judges</a>
<a class="menuItem" href="index.php?section=admin&amp;go=participants&amp;filter=stewards">Available Stewards</a>
<a class="menuItem" href="index.php?section=admin&amp;go=contacts">Competition Contacts</a>
<a class="menuItem" href="index.php?section=admin&amp;go=dropoff">Drop-Off Locations</a>
<a class="menuItem" href="index.php?section=admin&amp;go=sponsors">Sponsors</a>
</div>

<div id="adminMenu6" class="menu">
<a class="menuItem" href="index.php?section=admin&amp;action=assign&amp;go=judging&amp;filter=judges">Judges (Final Assignments)</a>
<a class="menuItem" href="index.php?section=admin&amp;action=assign&amp;go=judging&amp;filter=stewardss">Stewards (Final Assignments)</a>
<?php if ($totalRows_judging > 1) { ?>
<a class="menuItem" href="index.php?section=admin&amp;action=update&amp;go=judging&amp;filter=judges">Judges to a Location</a>
<a class="menuItem" href="index.php?section=admin&amp;action=update&amp;go=judging&amp;filter=stewards">Stewards to a Location</a>
<?php } ?>
<a class="menuItem" href="index.php?section=admin&amp;action=add&amp;go=assign_tables&amp;filter=judges">Judges to a Table</a>
<a class="menuItem" href="index.php?section=admin&amp;action=add&amp;go=assign_tables&amp;filter=stewards">Stewards to a Table</a>
</div>

<div id="adminMenu7" class="menu" onmouseover="menuMouseover(event)">
<a class="menuItem" href="index.php?section=admin&amp;go=participants">Participants</a>
<a class="menuItem" href="index.php?section=admin&go=entries">Entries</a>
<a class="menuItem" href="index.php?section=admin&go=entries&action=received">Entries by Category (Mark as Paid/Received)</a>
</div>

<div id="adminMenu8" class="menu" onmouseover="menuMouseover(event)">
<a class="menuItem" href="index.php?section=admin&amp;go=participants&amp;action=add">A Participant</a>
<a class="menuItem" href="index.php?section=brew&amp;go=entries&amp;action=add&amp;filter=admin">A Participant's Entry</a>
</div>

<div id="adminMenu9" class="menu" onmouseover="menuMouseover(event)">
<a class="menuItem" href="index.php?section=admin&amp;go=participants">Participants</a>
<a class="menuItem" href="index.php?section=admin&go=entries">Entries</a>
<a class="menuItem" href="index.php?section=admin&amp;go=judging_tables">Tables</a>
<a class="menuItem" href="index.php?section=admin&go=judging_tables&filter=orphans">Styles Not Assigned to Tables</a>
<a class="menuItem" href="index.php?section=admin&amp;go=judging_flights">Flights</a>
<a class="menuItem" href="index.php?section=admin&amp;go=assign_tables">Judging Assignments</a>
<a class="menuItem" href="index.php?section=admin&amp;go=participants&amp;filter=assignJudges">Assigned Judges</a>
<a class="menuItem" href="index.php?section=admin&amp;go=participants&amp;filter=assignStewards">Assigned Stewards</a>
</div>

<div id="adminMenu10" class="menu" onmouseover="menuMouseover(event)">
<a class="menuItem" href="index.php?section=admin&go=participants&action=add">A Participant</a>
<a class="menuItem" href="index.php?section=brew&go=entries&action=add&filter=admin">A Participant's Entry</a>
<a class="menuItem" href="index.php?section=admin&go=judging_tables&action=add">A Table</a>
<a class="menuItem" href="index.php?section=admin&go=judging_flights">Flights to Tables</a>
<a class="menuItem" href="index.php?section=admin&go=judging_flights&action=rounds">Flights to Rounds</a>
<a class="menuItem" href="index.php?section=admin&go=judging_scores">Scores by Table</a>
</div>

<div id="adminMenu11" class="menu">
<a class="menuItem" href="index.php?section=admin&amp;go=preferences">Site Preferences</a>
<a class="menuItem" href="index.php?section=admin&amp;go=judging_preferences">Competition Organization Preferences</a>
</div>

<div id="adminMenu12" class="menu">
<a class="menuItem" href="index.php?section=admin&amp;go=contest_info">Competition Info</a>
</div>

<div id="adminMenu13" class="menu">
<a class="menuItem thickbox" href="admin/upload.admin.php?KeepThis=true&amp;TB_iframe=true&amp;height=350&amp;width=800" title="Upload Competition Logo Image">Competition Logo</a>
<a class="menuItem thickbox" href="admin/upload.admin.php?KeepThis=true&amp;TB_iframe=true&amp;height=350&amp;width=800" title="Upload Sponsor Logo Image">Sponsor Logos</a>
</div>

<div id="adminMenu14" class="menu">
<a class="menuItem" href="index.php?section=admin&amp;go=participants">Participants</a>
<a class="menuItem" href="index.php?section=admin&amp;go=entries">Entries</a>
<a class="menuItem" href="index.php?section=admin&amp;go=judging_tables">Tables</a>
<a class="menuItem" href="index.php?section=admin&amp;go=judging_scores">Scores by Table</a>
<a class="menuItem" href="index.php?section=admin&amp;go=judging_scores&amp;filter=category">Scores by Category</a>
<a class="menuItem" href="index.php?section=admin&amp;go=judging_scores_bos">BOS Entries and Places</a>
</div>

<div id="adminMenu15" class="menu">
<a class="menuItem" href="index.php?section=admin&amp;go=judging_scores&amp;action=add">Scores by Table</a>
<a class="menuItem" href="index.php?section=admin&amp;go=judging_scores&amp;action=add&amp;filter=category">Scores by Category</a>
</div>

<div id="adminMenu16" class="menu">
<a class="menuItem thickbox" href="output/pullsheets.php?section=admin&amp;go=judging_tables&amp;id=default&amp;KeepThis=true&amp;TB_iframe=true&amp;height=425&amp;width=700" title="Print Pullsheets by Table">Pullsheets by Table</a>
<a class="menuItem thickbox" href="output/pullsheets.php?section=admin&amp;go=judging_tables&amp;filter=category&amp;id=default&amp;KeepThis=true&amp;TB_iframe=true&amp;height=425&amp;width=700" title="Print Pullsheets by Category">Pull Sheets By Category</a>
</div>

<div id="adminMenu17" class="menu">
<a class="menuItem" href="">Judging Assignments</a>
<a class="menuItem" href="">Steward Assignments</a>
<a class="menuItem" href="">Table Cards</a>
<a class="menuItem" href="">Pullsheets for BOS</a>
</div>

<div id="adminMenu18" class="menu">
<a class="menuItem" href="">Results by Table</a>
<a class="menuItem" href="">Results by Category</a>
<a class="menuItem" href="">Judge/Steward/Staff Points</a>
<a class="menuItem" href="">Judge/Steward/Staff Points (XML)</a>
</div>

<div id="adminMenu19" class="menu">
<a class="menuItem" href="output/email_export.php">All Participants</a>
<a class="menuItem" href="output/email_export.php?filter=judges">All Judges</a>
<a class="menuItem" href="output/email_export.php?filter=stewards">All Stewards</a>
<a class="menuItem" href="output/entries_export.php?go=csv&amp;filter=paid&amp;action=email">Paid &amp; Received Entries</a>
<a class="menuItem" href="output/entries_export.php?go=csv&amp;filter=nopay&amp;action=email">Non-Paid &amp; Received Entries</a>
</div>

<div id="adminMenu20" class="menu">
<a class="menuItem" href="output/participants_export.php?go=tab">All Participants</a>
<a class="menuItem" href="output/entries_export.php?go=tab&amp;filter=paid&amp;action=hccp">Paid &amp; Received Entries</a>
<a class="menuItem" href="output/entries_export.php?go=tab">All Entries</a>
</div>

<div id="adminMenu21" class="menu">
<a class="menuItem" href="output/entries_export.php?go=csv">All Entries</a>
<a class="menuItem" href="output/entries_export.php?go=csv&amp;filter=paid&amp;action=hccp">Paid &amp; Received Entries</a>
<a class="menuItem" href="output/entries_export.php?go=csv&amp;filter=nopay&amp;action=hccp">Non-Paid &amp; Received Entries</a>
<a class="menuItem" href="output/participants_export.php?go=csv">All Participants</a>
<a class="menuItem" href="output/participants_export.php?go=csv&amp;filter=winners">Winners</a>
</div>

<div id="adminMenu22" class="menu">
<a class="menuItem" href="output/promo_export.php?action=html">HTML</a>
<a class="menuItem" href="output/promo_export.php?action=word">Word</a>
</div>

<?php } ?>