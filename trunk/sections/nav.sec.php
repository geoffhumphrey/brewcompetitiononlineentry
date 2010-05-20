
<?php if (strstr($section,"step")) { ?>
<p class="setupTitle">Set Up Your Brew Competition Online Signup Site</p>
<?php } else { ?>
<ul id="nav">
  <li><?php if ($section != "default") { ?><a href="index.php"><?php echo $row_contest_info['contestName']; ?> Home</a><?php } else { echo $row_contest_info['contestName']; ?> Home<?php } ?></li>
  <li><?php if ($section != "rules") { ?><a href="index.php?section=rules">Rules</a><?php } else { ?>Rules<?php } ?></li>
  <li><?php if ($section != "entry") { ?><a href="index.php?section=entry">Entry Information</a><?php } else { ?>Entry Information<?php } ?></li>
  <?php if (($row_prefs['prefsSponsors'] == "Y") && ($row_prefs['prefsSponsorLogos'] == "Y") && ($totalRows_sponsors > 0)) { ?><li><?php if ($section != "sponsors") { ?><a href="index.php?section=sponsors">Sponsors</a><?php } else { ?>Sponsors<?php } ?></li><?php } ?>
  <?php if (!lesserDate($today,$reg_open)) { ?>
  <?php if (!isset($_SESSION["loginUsername"])) { ?><li><?php if ($section != "register") { ?><a href="index.php?section=register">Register</a><?php } else { ?>Register<?php } ?></li><?php } ?>
  <?php if ((isset($_SESSION["loginUsername"])) && ($row_contest_info['contestEntryFee'] > 0)) { ?><li><?php if ($section != "pay") { ?><a href="index.php?section=pay">Pay My Fees</a><?php } else { ?>Pay My Fees<?php } ?></li><?php } ?>
  <?php } ?>
  <?php if (isset($_SESSION["loginUsername"])) { ?><li><?php if ($section != "list") { ?><a href="index.php?section=list">My Entries and Info</a><?php } else { ?>My Entries and Info<?php } ?></li><?php } ?>
  <?php if ((isset($_SESSION["loginUsername"])) && ($row_user['userLevel'] == "1")) { ?><li><div class="menuBar"><a class="menuButton" href="index.php?section=admin" onclick="index.php?section=admin" onmouseover="buttonMouseover(event, 'adminMenu');">Admin</a>
</div><?php } ?>
  <li><?php sessionAuthenticateNav(); ?></li>
</ul>
<?php } ?>
<?php if ((isset($_SESSION["loginUsername"])) && ($row_user['userLevel'] == "1")) { ?>
<!-- 1st Tier Sub Menus -->
<div id="adminMenu" class="menu" onmouseover="menuMouseover(event)">
<a class="menuItem" href="" onclick="return false;" onmouseover="menuItemMouseover(event, 'adminMenu2');"><span class="menuItemText">Manage/View</span><span class="menuItemArrow">&#9654;</span></a>
<a class="menuItem" href="" onclick="return false;" onmouseover="menuItemMouseover(event, 'adminMenu0');"><span class="menuItemText">Add</span><span class="menuItemArrow">&#9654;</span></a>
<a class="menuItem" href="" onclick="return false;" onmouseover="menuItemMouseover(event, 'adminMenu1');"><span class="menuItemText">Edit</span><span class="menuItemArrow">&#9654;</span></a>
<a class="menuItem" href="" onclick="return false;" onmouseover="menuItemMouseover(event, 'adminMenu6');"><span class="menuItemText">Assign</span><span class="menuItemArrow">&#9654;</span></a>
<a class="menuItem" href="" onclick="return false;" onmouseover="menuItemMouseover(event, 'adminMenu4');"><span class="menuItemText">Upload</span><span class="menuItemArrow">&#9654;</span></a>
<a class="menuItem" href="" onclick="return false;" onmouseover="menuItemMouseover(event, 'adminMenu3');"><span class="menuItemText">Export</span><span class="menuItemArrow">&#9654;</span></a>
<a class="menuItem" href="" onclick="return false;" onmouseover="menuItemMouseover(event, 'adminMenu5');"><span class="menuItemText">Maintenance</span><span class="menuItemArrow">&#9654;</span></a>
<a class="menuItem" href="http://code.google.com/p/brewcompetitiononlineentry/issues/entry" target="_blank">Report a Bug</a>
</div>

<!-- 2nd Tier sub menus  -->
<div id="adminMenu0" class="menu">
<a class="menuItem" href="index.php?section=admin&amp;go=participants&amp;action=add">A Participant</a>
<a class="menuItem" href="index.php?section=brew&amp;go=entries&amp;action=add&amp;filter=admin">A Participant's Entry</a>
<a class="menuItem" href="index.php?section=admin&amp;go=judging&amp;action=add">A Judging Location/Date</a>
<a class="menuItem" href="index.php?section=admin&amp;go=styles&amp;action=add">A Custom Style Category</a>
<a class="menuItem" href="index.php?section=admin&amp;go=dropoff&amp;action=add">A Drop-Off Location</a>
</div>

<div id="adminMenu1" class="menu">
<a class="menuItem" href="index.php?section=admin&amp;go=contest_info">Competition Info</a>
<a class="menuItem" href="index.php?section=admin&amp;go=preferences">Preferences</a>
</div>

<div id="adminMenu2" class="menu" onmouseover="menuMouseover(event)">
<a class="menuItem" href="index.php?section=admin&amp;go=participants">Participants</a>
<a class="menuItem" href="index.php?section=admin&amp;go=entries">Entries</a>
<a class="menuItem" href="index.php?section=admin&amp;go=judging">Judging Locations/Dates</a>
<a class="menuItem" href="index.php?section=admin&amp;go=styles">Accepted Style Categories</a>
<?php if ($totalRows_judging > 1) { ?><a class="menuItem" href="index.php?section=admin&amp;go=styles&amp;filter=judging">Style Categories for Judging Locations</a><?php } else echo ""; ?>
<a class="menuItem" href="" onclick="return false;"  onmouseover="menuItemMouseover(event, 'adminMenu2_3');"><span class="menuItemText">Sponsors</span><span class="menuItemArrow">&#9654;</span></a>
<a class="menuItem" href="index.php?section=admin&amp;go=participants&amp;filter=judges">Available Judges</a>
<a class="menuItem" href="index.php?section=admin&amp;go=participants&amp;filter=stewards">Available Stewards</a>
<a class="menuItem" href="index.php?section=admin&amp;go=dropoff">Drop-Off Locations</a>
</div>

<div id="adminMenu3" class="menu" onmouseover="menuMouseover(event)">
<a class="menuItem" href="" onclick="return false;"  onmouseover="menuItemMouseover(event, 'adminMenu3_1');"><span class="menuItemText">Promo Materials</span><span class="menuItemArrow">&#9654;</span></a>
<a class="menuItem" href="" onclick="return false;"  onmouseover="menuItemMouseover(event, 'adminMenu3_2');"><span class="menuItemText">Email Addresses (CSV)</span><span class="menuItemArrow">&#9654;</span></a>
<a class="menuItem" href="" onclick="return false;"  onmouseover="menuItemMouseover(event, 'adminMenu3_3');"><span class="menuItemText">Tab Delimited Files</span><span class="menuItemArrow">&#9654;</span></a>
<a class="menuItem" href="" onclick="return false;"  onmouseover="menuItemMouseover(event, 'adminMenu3_4');"><span class="menuItemText">CSV Files</span><span class="menuItemArrow">&#9654;</span></a>
</div>

<div id="adminMenu4" class="menu">
<a class="menuItem thickbox" href="admin/upload.admin.php?KeepThis=true&amp;TB_iframe=true&amp;height=350&amp;width=800" title="Upload Competition Logo Image">Competition Logo</a>
<a class="menuItem thickbox" href="admin/upload.admin.php?KeepThis=true&amp;TB_iframe=true&amp;height=350&amp;width=800" title="Upload Sponsor Logo Image">Sponsor Logos</a>
</div>

<div id="adminMenu5" class="menu">
<a class="menuItem" href="index.php?section=admin&amp;go=archive">Archives</a>
</div>

<div id="adminMenu6" class="menu">
<a class="menuItem" href="index.php?section=admin&amp;action=assign&amp;go=judging&amp;filter=judges">Judges (Final Assignments)</a>
<a class="menuItem" href="index.php?section=admin&amp;action=assign&amp;go=judging&amp;filter=stewardss">Stewards (Final Assignments)</a>
<?php if ($totalRows_judging > 1) { ?>
<a class="menuItem" href="index.php?section=admin&amp;action=update&amp;go=judging&amp;filter=judges">Judges to a Location</a>
<a class="menuItem" href="index.php?section=admin&amp;action=update&amp;go=judging&amp;filter=stewards">Stewards to a Location</a>
<?php } ?>
</div>

<!-- 3rd Tier sub menus -->
<div id="adminMenu2_3" class="menu">
<a class="menuItem" href="index.php?section=admin&amp;go=sponsors">Manage Sponsors</a>
<a class="menuItem" href="index.php?section=admin&amp;go=sponsors&amp;action=add">Add Sponsor</a>
<a class="menuItem thickbox" href="admin/upload.admin.php?KeepThis=true&amp;TB_iframe=true&amp;height=350&amp;width=800">Add Sponsor Logo</a>
</div>

<div id="adminMenu3_1" class="menu">
<a class="menuItem" href="admin/promo_export.admin.php?action=html">HTML</a>
<a class="menuItem" href="admin/promo_export.admin.php?action=word">Word</a>
</div>

<div id="adminMenu3_2" class="menu">
<a class="menuItem" href="admin/email_export.php">All Participants</a>
<a class="menuItem" href="admin/email_export.php?filter=judges">All Judges</a>
<a class="menuItem" href="admin/email_export.php?filter=stewards">All Stewards</a>
<a class="menuItem" href="admin/entries_export.php?go=csv&amp;filter=paid&amp;action=email">Paid &amp; Received Entries</a>
<a class="menuItem" href="admin/entries_export.php?go=csv&amp;filter=nopay&amp;action=email">Non-Paid &amp; Received Entries</a>
</div>

<div id="adminMenu3_3" class="menu">
<a class="menuItem" href="admin/participants_export.php?go=tab">All Participants</a>
<a class="menuItem" href="admin/entries_export.php?go=tab&amp;filter=paid&amp;action=hccp">Paid &amp; Received Entries</a>
<a class="menuItem" href="admin/entries_export.php?go=tab">All Entries</a>
</div>

<div id="adminMenu3_4" class="menu">
<a class="menuItem" href="admin/participants_export.php?go=csv">All Participants</a>
<a class="menuItem" href="admin/entries_export.php?go=csv&amp;filter=paid&amp;action=hccp">Paid &amp; Received Entries</a>
<a class="menuItem" href="admin/entries_export.php?go=csv&amp;filter=nopay&amp;action=hccp">Non-Paid &amp; Received Entries</a>
<a class="menuItem" href="admin/entries_export.php?go=csv">All Entries</a>
</div>
<?php } ?>