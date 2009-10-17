<div id="header">	
	<div id="header-inner"><h1>Contest Entry Administration</h1></div>
</div>
<?php 
if ($msg == "1") echo "<div class=\"error\">Information added successfully.</div>"; 
if ($msg == "2") echo "<div class=\"error\">Information edited successfully.</div>"; 
if ($msg == "5") echo "<div class=\"error\">Information deleted successfully.</div>";
if ($msg == "6") echo "<div class=\"error\">The suffix you entered is already in use, please enter a different one.</div>"; 
if ($msg == "7") echo "<div class=\"error\">Archives created successfully. Click the archive name to view.</div>";
if ($msg == "8") echo "<div class=\"error\">Archive \"".$filter."\" deleted.</div>"; 
?>
<?php 
if ($row_user['userLevel'] == "1") {
	if ($go == "default") { ?>
<table>
  <tr bgcolor="<?php echo $color;?>">
    <td width="5%" class="dataLabel bdr1T"><img src="images/pencil.png" align="absmiddle"> Edit:</td>
    <td width="5%" nowrap="nowrap" class="data bdr1T"><a href="index.php?section=admin&go=contest_info">Contest Info</a></td>
    <td width="5%" colspan="3" nowrap="nowrap" class="data bdr1T"><a href="index.php?section=admin&go=preferences">Preferences</a></td>
  </tr>
  <tr bgcolor="<?php echo $color1;?>">
    <td width="5%" class="dataLabel"><img src="images/book.png" align="absmiddle"> Manage/View:</td>
    <td width="5%" nowrap="nowrap" class="data"><a href="index.php?section=admin&go=participants">Participants</a></td>
    <td width="5%" nowrap="nowrap" class="data"><a href="index.php?section=admin&go=entries">Entries</a></td>
    <td width="5%" nowrap="nowrap" class="data"><a href="index.php?section=admin&go=participants&filter=judges">Available Judges</a></td>
    <td class="data"><a href="index.php?section=admin&go=participants&filter=stewards">Available Stewards</a></td>
  </tr>
  <tr bgcolor="<?php echo $color;?>">
    <td width="5%" class="dataLabel"><img src="images/cog.png" align="absmiddle"> Maintenance:</td>
    <td width="5%" nowrap="nowrap" class="data"><a href="index.php?section=admin&go=archive">Archives</a></td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td class="data">View and create archived information from previous competitions.</td>
  </tr>
  <tr bgcolor="<?php echo $color1;?>">
    <td class="dataLabel"><img src="images/email_go.png" align="absmiddle"> Export Email Addresses (CSV):</td>
    <td nowrap="nowrap" class="data"><a href="admin/email_export.php">All Participants</a></td>
    <td nowrap="nowrap" class="data"><a href="admin/email_export.php?filter=judges">Judges</a></td>
    <td nowrap="nowrap" class="data"><a href="admin/email_export.php?filter=stewards">Stewards</a></td>
    <td class="data">For importing into an email or contact management program.</td>
  </tr>
  <tr bgcolor="<?php echo $color;?>">
    <td width="5%" class="dataLabel"><img src="images/page.png" align="absmiddle"> Export Tab Delimited File:</td>
    <td width="5%" nowrap="nowrap" class="data"><a href="admin/participants_export.php?go=tab">Participants</a></td>
    <td width="5%" nowrap="nowrap" class="data"><a href="admin/entries_export.php?go=tab">Entries</a></td>
    <td>&nbsp;</td>
    <td class="data">For importing into the Homebrew Competition Coordination Program (HCCP).</td>
  </tr>
  <tr bgcolor="<?php echo $color1;?>">
    <td width="5%" class="dataLabel bdr1B"><img src="images/page_excel.png" align="absmiddle"> Export CSV File:</td>
    <td width="5%" nowrap="nowrap" class="data bdr1B"><a href="admin/participants_export.php?go=csv">Participants</a></td>
    <td width="5%" nowrap="nowrap" class="data bdr1B"><a href="admin/entries_export.php?go=csv">Entries</a></td>
    <td class="data bdr1B">&nbsp;</td>
    <td class="data bdr1B">For importing into a spreadsheet program such as Microsoft Excel.</td>
  </tr>
<!--
  <tr>
    <td width="5%" class="dataLabel"><img src="images/page_world.png" align="absmiddle"> Export Promo Flyer:</td>
    <td width="5%" nowrap="nowrap" class="data"><a href="admin/promo_export.php?go=html">HTML</a></td>
    <td width="5%" nowrap="nowrap" class="data"><a href="admin/promo_export.php?go=word">Word</a></td>
    <td colspan="2" class="data"><a href="admin/promo_export.php?go=pdf">PDF</a></td>
  </tr>
-->
</table>
<?php 	} 
if ($go == "contest_info") 	include ('admin/contest_info.admin.php');
if ($go == "preferences") 	include ('admin/preferences.admin.php');
if ($go == "participants") 	include ('admin/participants.admin.php');
if ($go == "entries") 		include ('admin/entries.admin.php');
if ($go == "make_admin") 	include ('admin/make_admin.admin.php');
if ($go == "archive") 	    include ('admin/archive.admin.php');
}
else echo "<div class=\"error\">You do not have sufficient privileges to access this area.</div>";
?>
