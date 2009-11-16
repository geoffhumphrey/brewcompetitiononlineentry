<ul id="nav">
  <li><?php if ($section != "default") { ?><a href="index.php"><?php echo $row_contest_info['contestName']; ?> Home</a><?php } else { echo $row_contest_info['contestName']; ?> Home<?php } ?></li>
  <li><?php if ($section != "rules") { ?><a href="index.php?section=rules">Rules</a><?php } else { ?>Rules<?php } ?></li>
  <li><?php if ($section != "entry") { ?><a href="index.php?section=entry">Entry Info</a><?php } else { ?>Entry Info<?php } ?></li>
  <?php if (($row_prefs['prefsSponsors'] == "Y") && ($row_prefs['prefsSponsorLogos'] == "Y") && ($totalRows_sponsors > 0)) { ?><li><?php if ($section != "sponsors") { ?><a href="index.php?section=sponsors">Sponsors</a><?php } else { ?>Sponsors<?php } ?></li><?php } ?>
  <?php if (greaterDate($today,$deadline)) echo ""; else { ?>
  <?php if (!isset($_SESSION["loginUsername"])){ ?><li><?php if ($section != "register") { ?><a href="index.php?section=register">Register</a><?php } else { ?>Register<?php } ?></li><?php } else { ?><li>||</li><li><?php if ($section != "list") { ?><a href="index.php?section=list">List of Entries</a><?php } else { ?>List of Entries<?php } ?></li><?php } ?>
  <?php if ((isset($_SESSION["loginUsername"])) && ($row_contest_info['contestEntryFee'] > 0)) { ?><li><?php if ($section != "pay") { ?><a href="index.php?section=pay">Pay Entry Fees</a><?php } else { ?>Pay Entry Fees<?php } ?></li><?php } ?>
  <?php } ?>
  <?php if ((isset($_SESSION["loginUsername"])) && ($row_user['userLevel'] == "1")) { ?><li>||</li><li><div class="menuBar"><a class="menuButton" href="index.php?section=admin" onclick="index.php?section=admin" onmouseover="buttonMouseover(event, 'adminMenu');">Admin</a>
</div><?php } ?>
  <li><?php sessionAuthenticateNav(); ?></li>
</ul>

<!-- 1st Tier Sub Menus Use <div class="menuItemSep"></div> for separator. -->

<div id="adminMenu" class="menu" onmouseover="menuMouseover(event)">
<a class="menuItem" href="" onclick="return false;" onmouseover="menuItemMouseover(event, 'adminMenu1');"><span class="menuItemText">Edit</span><span class="menuItemArrow">&#9654;</span></a>
<a class="menuItem" href="" onclick="return false;" onmouseover="menuItemMouseover(event, 'adminMenu2');"><span class="menuItemText">Manage/View</span><span class="menuItemArrow">&#9654;</span></a>
<a class="menuItem" href="" onclick="return false;" onmouseover="menuItemMouseover(event, 'adminMenu3');"><span class="menuItemText">Export</span><span class="menuItemArrow">&#9654;</span></a>
<a class="menuItem" href="" onclick="return false;" onmouseover="menuItemMouseover(event, 'adminMenu4');"><span class="menuItemText">Upload</span><span class="menuItemArrow">&#9654;</span></a>
<a class="menuItem" href="" onclick="return false;" onmouseover="menuItemMouseover(event, 'adminMenu5');"><span class="menuItemText">Maintenance</span><span class="menuItemArrow">&#9654;</span></a>
</div>

<!-- 2nd Tier sub menus  -->

<div id="adminMenu1" class="menu">
<a class="menuItem" href="index.php?section=admin&go=contest_info">Contest Info</a>
<a class="menuItem" href="index.php?section=admin&go=preferences">Preferences</a>
</div>

<div id="adminMenu2" class="menu" onmouseover="menuMouseover(event)">
<a class="menuItem" href="index.php?section=admin&go=participants">Participants</a>
<a class="menuItem" href="index.php?section=admin&go=participants&filter=judges">Available Judges</a>
<a class="menuItem" href="index.php?section=admin&go=participants&filter=stewards">Available Stewards</a>
<a class="menuItem" href="index.php?section=admin&go=entries">Entries</a>
<a class="menuItem" href="" onclick="return false;"  onmouseover="menuItemMouseover(event, 'adminMenu2_3');"><span class="menuItemText">Sponsors</span><span class="menuItemArrow">&#9654;</span></a>
</div>

<div id="adminMenu3" class="menu" onmouseover="menuMouseover(event)">
<a class="menuItem" href="" onclick="return false;"  onmouseover="menuItemMouseover(event, 'adminMenu3_1');"><span class="menuItemText">Promo Materials</span><span class="menuItemArrow">&#9654;</span></a>
<a class="menuItem" href="" onclick="return false;"  onmouseover="menuItemMouseover(event, 'adminMenu3_2');"><span class="menuItemText">Email Addresses (CSV)</span><span class="menuItemArrow">&#9654;</span></a>
<a class="menuItem" href="" onclick="return false;"  onmouseover="menuItemMouseover(event, 'adminMenu3_3');"><span class="menuItemText">Tab Delimited Files</span><span class="menuItemArrow">&#9654;</span></a>
<a class="menuItem" href="" onclick="return false;"  onmouseover="menuItemMouseover(event, 'adminMenu3_4');"><span class="menuItemText">CSV Files</span><span class="menuItemArrow">&#9654;</span></a>
</div>

<div id="adminMenu4" class="menu">
<a class="menuItem thickbox" href="admin/upload.admin.php?KeepThis=true&TB_iframe=true&height=350&width=800" title="Upload Competition Logo Image">Competition Logo</a>
<a class="menuItem thickbox" href="admin/upload.admin.php?KeepThis=true&TB_iframe=true&height=350&width=800" title="Upload Sponsor Logo Image">Sponsor Logos</a>
</div>

<div id="adminMenu5" class="menu">
<a class="menuItem" href="index.php?section=admin&go=archive">Archives</a>
</div>

<!-- 3rd Tier sub menus -->

<div id="adminMenu2_3" class="menu">
<a class="menuItem" href="index.php?section=admin&go=sponsors">Manage Sponsors</a>
<a class="menuItem" href="index.php?section=admin&go=sponsors&action=add">Add Sponsor</a>
<a class="menuItem thickbox" href="admin/upload.admin.php?KeepThis=true&TB_iframe=true&height=350&width=800">Add Sponsor Logo</a>
</div>

<div id="adminMenu3_1" class="menu">
<a class="menuItem" href="admin/promo_export.admin.php?action=html">HTML</a>
<a class="menuItem" href="admin/promo_export.admin.php?action=word">Word</a>
</div>

<div id="adminMenu3_2" class="menu">
<a class="menuItem" href="admin/email_export.php">All Participants</a>
<a class="menuItem" href="admin/email_export.php?filter=judges">Judges</a>
<a class="menuItem" href="admin/email_export.php?filter=judges">Stewards</a>
</div>

<div id="adminMenu3_3" class="menu">
<a class="menuItem" href="admin/participants_export.php?go=tab">All Participants</a>
<a class="menuItem" href="admin/entries_export.php?go=tab&filter=paid">Paid &amp; Received Entries</a>
<a class="menuItem" href="admin/entries_export.php?go=tab">All Entries</a>
</div>

<div id="adminMenu3_4" class="menu">
<a class="menuItem" href="admin/participants_export.php?go=csv">All Participants</a>
<a class="menuItem" href="admin/entries_export.php?go=csv&filter=paid">Paid &amp; Received Entries</a>
<a class="menuItem" href="admin/entries_export.php?go=csv">All Entries</a>
</div>