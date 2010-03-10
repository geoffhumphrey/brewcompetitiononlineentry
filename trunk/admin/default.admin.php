<div id="header">	
	<div id="header-inner"><h1><?php echo $header_output; ?></h1></div>
</div>
<?php 
if ($msg != "default") echo $msg_output; 
if ($row_user['userLevel'] == "1") {
			if (($totalRows_dropoff == "0") && ($go == "default")) echo "<div class='error'>No drop-off locations have been specified. <a href=\"index.php?section=admin&action=add&go=dropoff\">Add a drop-off location</a>?</div>";
			if (($totalRows_judging == "0") && ($go == "default")) echo "<div class='error'>No judging dates/locations have been specified. <a href=\"index.php?section=admin&action=add&go=judging\">Add a judging location</a>?</div>"; 
if ($go == "default") { 
if (greaterDate($today,$deadline)) echo "<div class='info'>Now that registration is closed, keep your entry database up to date by 1) adding any participants and their associated entries who did not register online and 2) finalizing judge and steward assignments.</div>";

?>
<p>Use the items below to manage and customize your competition's BCOE installation.</p>
<table>
  <tr>
    <td width="5%" class="dataLabel bdr1T"><span class="icon"><img src="images/book.png" align="absmiddle"></span>Manage/View:</td>
    <td width="15%" nowrap="nowrap" class="data bdr1T"><a href="index.php?section=admin&go=participants">Participants</a></td>
    <td width="15%" nowrap="nowrap" class="data bdr1T"><a href="index.php?section=admin&go=entries">Entries</a></td>
    <td nowrap="nowrap" class="data bdr1T"><a href="index.php?section=admin&go=styles">Accepted Style Categories</a><a href="index.php?section=admin&go=entries"></a></td>
    <td nowrap="nowrap" class="data bdr1T"><a href="index.php?section=admin&go=sponsors">Sponsors</a><a href="index.php?section=admin&go=participants&filter=judges"></a><a href="index.php?section=admin&go=participants&filter=stewards"></a></td>
  </tr>
  <tr>
    <td class="dataLabel">&nbsp;</td>
    <td nowrap="nowrap" class="data"><a href="index.php?section=admin&go=judging">Judging Locations</a></td>
    <td nowrap="nowrap" class="data"><a href="index.php?section=admin&go=participants&filter=judges">Available Judges</a></td>
    <td nowrap="nowrap" class="data"><a href="index.php?section=admin&go=participants&filter=stewards">Available Stewards</a></td>
    <td nowrap="nowrap" class="data"><a href="index.php?section=admin&go=dropoff">Drop-Off Locations</a></td>
  </tr>
  <tr>
    <td width="5%" class="dataLabel bdr1T_dashed"><span class="icon"><img src="images/page_edit.png" align="absmiddle"></span>Add:</td>
    <td width="15%" nowrap="nowrap" class="data bdr1T_dashed"><a href="index.php?section=admin&go=participants&action=add">A Participant</a></td>
    <td nowrap="nowrap" class="data bdr1T_dashed"><a href="index.php?section=brew&go=entries&action=add&filter=admin">A Participant's Entry</a></td>
    <td colspan="2" nowrap="nowrap" class="data bdr1T_dashed"><a href="index.php?section=admin&go=judging&action=add">A Judging Location</a><a href="index.php?section=admin&go=dropoff&action=add"></a></td>
  </tr>
  <tr>
    <td class="dataLabel">&nbsp;</td>
    <td nowrap="nowrap" class="data"><a href="index.php?section=admin&amp;go=styles&amp;action=add">A Custom Style Category</a></td>
    <td colspan="3" nowrap="nowrap" class="data"><a href="index.php?section=admin&amp;go=dropoff&amp;action=add">A Drop-Off Location</a></td>
  </tr>
  <tr>
    <td width="5%" class="dataLabel bdr1T_dashed"><span class="icon"><img src="images/pencil.png" align="absmiddle"></span>Edit:</td>
    <td nowrap="nowrap" class="data bdr1T_dashed"><a href="index.php?section=admin&go=contest_info">Competition Info</a></td>
    <td colspan="3" nowrap="nowrap" class="data bdr1T_dashed"><a href="index.php?section=admin&go=preferences">Preferences</a></td>
  </tr>
  <tr>
    <td class="dataLabel bdr1T_dashed"><span class="icon"><img src="images/user_edit.png" align="absmiddle" /></span>Assign:</td>
    <td nowrap="nowrap" class="data bdr1T_dashed"><a href="index.php?section=admin&action=assign&go=judging&filter=judges">Judges (Final Assignments)</a></td>
    <td nowrap="nowrap" colspan="3" class="data bdr1T_dashed"><a href="index.php?section=admin&action=assign&go=judging&filter=stewards">Stewards (Final Assignments)</a></td>
  </tr>
  <?php if ($totalRows_judging > 1) { ?>
  <tr>
    <td class="dataLabel">&nbsp;</td>
    <td nowrap="nowrap" class="data"><a href="index.php?section=admin&action=update&go=judging&filter=judges">Judges to a Location</a></td>
    <td nowrap="nowrap" colspan="3" class="data"><a href="index.php?section=admin&action=update&go=judging&filter=stewards">Stewards to a Location</a></td>
  </tr>
  <?php } ?>
  <tr>
    <td class="dataLabel bdr1T_dashed"><span class="icon"><img src="images/picture_add.png" align="absmiddle"></span>Upload:</td>
    <td width="15%" nowrap="nowrap" class="data bdr1T_dashed"><a href="admin/upload.admin.php?KeepThis=true&TB_iframe=true&height=350&width=800" title="Upload Competition Logo Image" class="thickbox">Competition Logo</a></td>
    <td nowrap="nowrap" colspan="3" class="data bdr1T_dashed"><a href="admin/upload.admin.php?KeepThis=true&TB_iframe=true&height=350&width=800" title="Upload Sponsor Logo Image" class="thickbox">Sponsor Logos</a></td>
  </tr>
  <tr>
    <td width="5%" class="dataLabel bdr1T_dashed"><span class="icon"><img src="images/page_world.png" align="absmiddle"></span>Export Promo Materials:</td>
    <td width="15%" nowrap="nowrap" class="data bdr1T_dashed"><a href="admin/promo_export.admin.php?action=html">HTML</a></td>
    <td colspan="3" width="15%" nowrap="nowrap" class="data bdr1T_dashed"><a href="admin/promo_export.admin.php?action=word">Word</a></td>
  </tr>
  <tr>
    <td class="dataLabel bdr1T_dashed"><span class="icon"><img src="images/email_go.png" align="absmiddle"></span>Export Email Addresses (CSV):</td>
    <td width="15%" nowrap="nowrap" class="data bdr1T_dashed"><a href="admin/email_export.php">All Participants</a></td>
    <td width="15%" nowrap="nowrap" class="data bdr1T_dashed"><a href="admin/entries_export.php?go=csv&filter=paid&action=email">Paid & Received Entries</a><a href="admin/email_export.php?filter=judges"></a></td>
    <td nowrap="nowrap" class="data bdr1T_dashed"><a href="admin/entries_export.php?go=csv&action=email">All Entries</a></td>
    <td nowrap="nowrap" class="data bdr1T_dashed"><a href="admin/entries_export.php?go=csv&filter=nopay&action=email">Non-Paid & Received Entries</a><a href="admin/email_export.php?filter=stewards"></a><a href="admin/entries_export.php?go=csv&filter=paid&action=email"></a> <a href="admin/email_export.php?filter=judges"></a><a href="admin/entries_export.php?go=csv&filter=nopay&action=email"></a><a href="admin/email_export.php?filter=stewards"></a></td>
  </tr>
  <?php if ($totalRows_judging == 1) { ?>
  <tr>
    <td class="dataLabel">&nbsp;</td>
    <td nowrap="nowrap" class="data"><a href="admin/email_export.php?filter=judges">Judges</a>
    <a href="admin/email_export.php?filter=stewards"></a></td>
    <td colspan="3" nowrap="nowrap" class="data"><a href="admin/email_export.php?filter=stewards">Stewards</a></td>
  </tr>
  <?php } ?>
  <?php if ($totalRows_judging1 > 1) { ?>
  <tr>
    <td class="dataLabel">&nbsp;</td>
    <td colspan="4" nowrap="nowrap" class="data">Judges for Location:<br />
    <form name="judgeChoice" id="judgeChoice">
       	  <select name="judge_location" id="judge_location" onchange="jumpMenu('self',this,0)">
    		<option value=""></option>
            <option value="admin/email_export.php?go=csv&filter=judges&section=all">All Locations</option>
            <?php do { ?>
    		<option value="admin/email_export.php?go=csv&filter=judges&section=loc&bid=<?php echo $row_judging1['id']; ?>"><?php echo $row_judging1['judgingLocName']." ("; echo dateconvert($row_judging1['judgingDate'], 3).")"; ?></option>
		    <?php } while ($row_judging1 = mysql_fetch_assoc($judging1)) ?>
          </select>
  	</form> 
    <em>* Be sure to make final <a href="index.php?section=admin&action=assign&go=judging&filter=judges">judging</a> and <a href="index.php?section=admin&action=assign&go=judging&filter=stewards">stewarding</a> location assignments before exporting.</em></td>
  </tr>
  <tr>
    <td class="dataLabel">&nbsp;</td>
    <td colspan="4" nowrap="nowrap" class="data">Stewards for Location:<br />
    <form name="judgeChoice" id="judgeChoice">
       	  <select name="judge_location" id="judge_location" onchange="jumpMenu('self',this,0)">
    		<option value=""></option>
            <option value="admin/email_export.php?go=csv&filter=stewards&section=all">All Locations</option>
            <?php do { ?>
    		<option value="admin/email_export.php?go=csv&filter=stewards&section=loc&bid=<?php echo $row_stewarding['id']; ?>"><?php echo $row_stewarding['judgingLocName']." ("; echo dateconvert($row_stewarding['judgingDate'], 3).")"; ?></option>
		    <?php } while ($row_stewarding = mysql_fetch_assoc($stewarding)) ?>
          </select>
  	</form>
    <em>* Be sure to make final <a href="index.php?section=admin&action=assign&go=judging&filter=judges">judging</a> and <a href="index.php?section=admin&action=assign&go=judging&filter=stewards">stewarding</a> location assignments before exporting.</em>    </td>
  </tr>
  <?php } ?>
  <tr>
    <td width="5%" class="dataLabel bdr1T_dashed"><span class="icon"><img src="images/page_go.png" align="absmiddle"></span>Export Tab Delimited Files:</td>
    <td width="15%" nowrap="nowrap" class="data bdr1T_dashed"><a href="admin/participants_export.php?go=tab">All Participants</a></td>
    <td width="15%" class="data bdr1T_dashed"><a href="admin/entries_export.php?go=tab&filter=paid">Paid & Received Entries</a></td>
    <td colspan="2" nowrap="nowrap" class="data bdr1T_dashed"><a href="admin/entries_export.php?go=tab">All Entries</a></td>
  </tr>
  <?php if ($totalRows_judging1 > 1) { ?>
  <tr>
    <td class="dataLabel">&nbsp;</td>
    <td colspan="4" nowrap="nowrap" class="data">Judges and Stewards for Location:
    <form name="tabChoice" id="tabChoice">
       	  <select name="tab_choice" id="tab_choice" onchange="jumpMenu('self',this,0)">
    		<option value=""></option>
            <option value="admin/participants_export.php?section=all&go=tab">All Locations</option>
            <?php do { ?>
    		<option value="admin/participants_export.php?section=loc&go=tab&bid=<?php echo $row_stewarding2['id']; ?>"><?php echo $row_stewarding2['judgingLocName']." ("; echo dateconvert($row_stewarding2['judgingDate'], 3).")"; ?></option>
		    <?php } while ($row_stewarding2 = mysql_fetch_assoc($stewarding2)) ?>
          </select>
  	</form>
    <em>* Be sure to make final <a href="index.php?section=admin&action=assign&go=judging&filter=judges">judging</a> and <a href="index.php?section=admin&action=assign&go=judging&filter=stewards">stewarding</a> location assignments before exporting.</em></td>
  </tr>
  <tr>
    <td class="dataLabel">&nbsp;</td>
    <td colspan="4" nowrap="nowrap" class="data">Styles; Paid and Received Entries for Location:
    <form name="tabChoice" id="tabChoice">
       	  <select name="tab_choice" id="tab_choice" onchange="jumpMenu('self',this,0)">
    		<option value=""></option>
            <?php do { ?>
    		<option value="admin/entries_export.php?section=loc&go=tab&filter=paid&bid=<?php echo $row_judging2['id']; ?>"><?php echo $row_judging2['judgingLocName']." ("; echo dateconvert($row_judging2['judgingDate'], 3).")"; ?></option>
		    <?php } while ($row_judging2 = mysql_fetch_assoc($judging2)) ?>
          </select>
  	</form>    </td>
  </tr>
  <?php } ?>
  <tr>
    <td class="dataLabel">&nbsp;</td>
    <td colspan="4" class="data">For importing into the Homebrew Competition Coordination Program (HCCP), available for download <a href="http://www.folsoms.net/hccp/" target="_blank">here</a>.
    <?php if ($totalRows_judging1 > 1) { ?><p>The tab delimited file for <em>each location</em> should be imported into HCCP as it's own database. Refer to the <a href="http://www.folsoms.net/hccp/hccp.pdf" target="_blank">HCCP documentation</a> for import instructions.</p><?php } ?>
    </td>
  </tr>
  <tr>
    <td width="5%" class="dataLabel bdr1T_dashed"><span class="icon"><img src="images/page_excel_go.png" align="absmiddle"></span>Export CSV Files:</td>
    <td width="15%" nowrap="nowrap" class="data bdr1T_dashed"><a href="admin/participants_export.php?go=csv">All Participants</a></td>
    <td width="15%" class="data bdr1T_dashed"><a href="admin/entries_export.php?go=csv&filter=paid">Paid & Received Entries</a></td>
    <td width="15%" nowrap="nowrap" class="data bdr1T_dashed"><a href="admin/entries_export.php?go=csv">All Entries</a></td>
    <td class="data bdr1T_dashed"><a href="admin/entries_export.php?go=csv&filter=nopay&action=hccp"></a><a href="admin/entries_export.php?go=csv"></a> <a href="admin/entries_export.php?go=csv&filter=nopay&action=hccp">Non-Paid & Received Entries</a><a href="admin/entries_export.php?go=csv"></a></td>
  </tr>
  <tr>
    <td class="dataLabel bdr1T_dashed"><span class="icon"><img src="images/cog.png" align="absmiddle" /></span>Maintenance:</td>
    <td colspan="4" nowrap="nowrap" class="data bdr1T_dashed"><a href="index.php?section=admin&go=archive">Archives</a></td>
  </tr>
  <tr>
    <td class="dataLabel bdr1B">&nbsp;</td>
    <td colspan="4" nowrap="nowrap" class="data bdr1B">View and create archived information from previous competitions.</td>
  </tr>
</table>
<?php 	} 
if ($go == "contest_info") 	include ('admin/contest_info.admin.php');
if ($go == "preferences") 	include ('admin/preferences.admin.php');
if ($go == "participants") 	include ('admin/participants.admin.php');
if ($go == "entries") 		include ('admin/entries.admin.php');
if ($go == "make_admin") 	include ('admin/make_admin.admin.php');
if ($go == "archive") 	    include ('admin/archive.admin.php');
if ($go == "sponsors") 	    include ('admin/sponsors.admin.php');
if ($go == "judging") 	    include ('admin/judging.admin.php');
if ($go == "styles") 	    include ('admin/styles.admin.php');
if ($go == "dropoff") 	    include ('admin/dropoff.admin.php');
}
else echo "<div class=\"error\">You do not have sufficient privileges to access this area.</div>";
?>
