<script type="text/javascript" language="javascript" src="js_includes/jquery.js"></script>
<script type="text/javascript" language="javascript" src="js_includes/jquery.dataTables.js"></script>
<div id="header">	
	<div id="header-inner"><h1><?php echo $header_output; ?></h1></div>
</div>
<?php 
if ($msg != "default") echo $msg_output; 
if ($row_user['userLevel'] == "1") {
			if (($totalRows_dropoff == "0") && ($go == "default")) echo "<div class='error'>No drop-off locations have been specified. <a href=\"index.php?section=admin&amp;action=add&amp;go=dropoff\">Add a drop-off location</a>?</div>";
			if (($totalRows_judging == "0") && ($go == "default")) echo "<div class='error'>No judging dates/locations have been specified. <a href=\"index.php?section=admin&amp;action=add&amp;go=judging\">Add a judging location</a>?</div>"; 
if ($go == "default") { 
if (greaterDate($today,$deadline)) echo "<div class='info'>Now that registration is closed, keep your entry database up to date by 1) adding any participants and their associated entries who did not register online and 2) finalizing judge and steward assignments.</div>";

?>
<p>Use the items below to manage and customize your competition's BCOE installation.</p>
<table>
  <tr>
    <td width="5%" class="dataLabel bdr1T"><span class="icon"><img src="images/book.png" ></span>Manage/View:</td>
    <td width="15%" nowrap="nowrap" class="data bdr1T"><a href="index.php?section=admin&amp;go=participants">Participants</a></td>
    <td width="15%" nowrap="nowrap" class="data bdr1T"><a href="index.php?section=admin&amp;go=entries">Entries</a></td>
    <td nowrap="nowrap" class="data bdr1T"><a href="index.php?section=admin&amp;go=styles">Accepted Style Categories</a><a href="index.php?section=admin&amp;go=entries"></a></td>
    <td nowrap="nowrap" class="data bdr1T"><?php if ($totalRows_judging > 1) { ?><a href="index.php?section=admin&amp;go=styles&amp;filter=judging">Style Categories for Judging Locations</a><?php } else echo ""; ?></td>
  </tr>
  <tr>
    <td class="dataLabel">&nbsp;</td>
    <td nowrap="nowrap" class="data"><a href="index.php?section=admin&amp;go=judging">Judging Locations</a></td>
    <td nowrap="nowrap" class="data"><a href="index.php?section=admin&amp;go=participants&amp;filter=judges">Available Judges</a></td>
    <td nowrap="nowrap" class="data"><a href="index.php?section=admin&amp;go=participants&amp;filter=stewards">Available Stewards</a><a href="index.php?section=admin&amp;go=dropoff"></a></td>
    <td nowrap="nowrap" class="data"><a href="index.php?section=admin&amp;go=contacts">Competition Contacts</a></td>
  </tr>
  <tr>
    <td class="dataLabel">&nbsp;</td>
    <td nowrap="nowrap" class="data"><a href="index.php?section=admin&amp;go=dropoff">Drop-Off Locations</a></td>
    <td nowrap="nowrap" class="data"><a href="index.php?section=admin&amp;go=participants&amp;filter=assignJudges">Assigned Judges</a></td>
    <td nowrap="nowrap" class="data"><a href="index.php?section=admin&amp;go=participants&amp;filter=assignStewards">Assigned Stewards</a></td>
    <td nowrap="nowrap" class="data"><a href="index.php?section=admin&amp;go=sponsors">Sponsors</a></a></td>
  </tr>
  <tr>
    <td width="5%" class="dataLabel bdr1T_dashed"><span class="icon"><img src="images/page_edit.png" ></span>Add:</td>
    <td width="15%" nowrap="nowrap" class="data bdr1T_dashed"><a href="index.php?section=admin&amp;go=participants&amp;action=add">A Participant</a></td>
    <td nowrap="nowrap" class="data bdr1T_dashed"><a href="index.php?section=brew&amp;go=entries&amp;action=add&amp;filter=admin">A Participant's Entry</a></td>
    <td nowrap="nowrap" class="data bdr1T_dashed"><a href="index.php?section=admin&amp;go=judging&amp;action=add">A Judging Location</a><a href="index.php?section=admin&amp;go=dropoff&amp;action=add"></a></td>
    <td nowrap="nowrap" class="data bdr1T_dashed"><a href="index.php?section=admin&amp;go=contacts&amp;action=add">A Competition Contact</a></td>
  </tr>
  <tr>
    <td class="dataLabel">&nbsp;</td>
    <td nowrap="nowrap" class="data"><a href="index.php?section=admin&amp;go=styles&amp;action=add">A Custom Style Category</a></td>
    <td nowrap="nowrap" class="data"><a href="index.php?section=admin&amp;go=dropoff&amp;action=add">A Drop-Off Location</a></td>
    <td nowrap="nowrap" class="data"><a href="index.php?section=admin&amp;go=sponsors&amp;action=add">A Sponsor</a></td>
    <td nowrap="nowrap" class="data">&nbsp;</td>
  </tr>
  <tr>
    <td width="5%" class="dataLabel bdr1T_dashed"><span class="icon"><img src="images/pencil.png" ></span>Edit:</td>
    <td nowrap="nowrap" class="data bdr1T_dashed"><a href="index.php?section=admin&amp;go=contest_info">Competition Info</a></td>
    <td colspan="3" nowrap="nowrap" class="data bdr1T_dashed"><a href="index.php?section=admin&amp;go=preferences">Preferences</a></td>
  </tr>
  <tr>
    <td class="dataLabel bdr1T_dashed"><span class="icon"><img src="images/user_edit.png"  /></span>Assign:</td>
    <td nowrap="nowrap" class="data bdr1T_dashed"><a href="index.php?section=admin&amp;action=assign&amp;go=judging&amp;filter=judges">Judges (Final Assignments)</a></td>
    <td nowrap="nowrap" colspan="3" class="data bdr1T_dashed"><a href="index.php?section=admin&amp;action=assign&amp;go=judging&amp;filter=stewards">Stewards (Final Assignments)</a></td>
  </tr>
  <?php if ($totalRows_judging > 1) { ?>
  <tr>
    <td class="dataLabel">&nbsp;</td>
    <td nowrap="nowrap" class="data"><a href="index.php?section=admin&amp;action=update&amp;go=judging&amp;filter=judges">Judges to a Location</a></td>
    <td nowrap="nowrap" colspan="3" class="data"><a href="index.php?section=admin&amp;action=update&amp;go=judging&amp;filter=stewards">Stewards to a Location</a></td>
  </tr>
  <?php } ?>
  <tr>
    <td class="dataLabel bdr1T_dashed"><span class="icon"><img src="images/picture_add.png" ></span>Upload:</td>
    <td width="15%" nowrap="nowrap" class="data bdr1T_dashed"><a href="admin/upload.admin.php?KeepThis=true&amp;TB_iframe=true&amp;height=350&amp;width=800" title="Upload Competition Logo Image" class="thickbox">Competition Logo</a></td>
    <td nowrap="nowrap" colspan="3" class="data bdr1T_dashed"><a href="admin/upload.admin.php?KeepThis=true&amp;TB_iframe=true&amp;height=350&amp;width=800" title="Upload Sponsor Logo Image" class="thickbox">Sponsor Logos</a></td>
  </tr>
  <tr>
    <td width="5%" class="dataLabel bdr1T_dashed"><span class="icon"><img src="images/page_world.png" ></span>Export Promo Materials:</td>
    <td width="15%" nowrap="nowrap" class="data bdr1T_dashed"><a href="admin/promo_export.admin.php?action=html">HTML</a></td>
    <td colspan="3" width="15%" nowrap="nowrap" class="data bdr1T_dashed"><a href="admin/promo_export.admin.php?action=word">Word</a></td>
  </tr>
  <tr>
    <td class="dataLabel bdr1T_dashed"><span class="icon"><img src="images/email_go.png" ></span>Export Email Addresses (CSV):</td>
    <td width="15%" nowrap="nowrap" class="data bdr1T_dashed"><a href="admin/email_export.php">All Participants</a></td>
    <td width="15%" nowrap="nowrap" class="data bdr1T_dashed"><a href="admin/entries_export.php?go=csv&amp;filter=paid&amp;action=email">Paid & Received Entries</a><a href="admin/email_export.php?filter=judges"></a></td>
    <td nowrap="nowrap" class="data bdr1T_dashed"><a href="admin/entries_export.php?go=csv&amp;action=email">All Entries</a></td>
    <td nowrap="nowrap" class="data bdr1T_dashed"><a href="admin/entries_export.php?go=csv&amp;filter=nopay&amp;action=email">Non-Paid & Received Entries</a><a href="admin/email_export.php?filter=stewards"></a><a href="admin/entries_export.php?go=csv&amp;filter=paid&amp;action=email"></a> <a href="admin/email_export.php?filter=judges"></a><a href="admin/entries_export.php?go=csv&amp;filter=nopay&amp;action=email"></a><a href="admin/email_export.php?filter=stewards"></a></td>
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
            <option value="admin/email_export.php?go=csv&amp;filter=judges&amp;section=all">All Locations</option>
            <?php do { ?>
    		<option value="admin/email_export.php?go=csv&amp;filter=judges&amp;section=loc&amp;bid=<?php echo $row_judging1['id']; ?>"><?php echo $row_judging1['judgingLocName']." ("; echo dateconvert($row_judging1['judgingDate'], 3).")"; ?></option>
		    <?php } while ($row_judging1 = mysql_fetch_assoc($judging1)) ?>
          </select>
  	</form> 
    <em>* Be sure to make final <a href="index.php?section=admin&amp;action=assign&amp;go=judging&amp;filter=judges">judging</a> and <a href="index.php?section=admin&amp;action=assign&amp;go=judging&amp;filter=stewards">stewarding</a> location assignments before exporting.</em></td>
  </tr>
  <tr>
    <td class="dataLabel">&nbsp;</td>
    <td colspan="4" nowrap="nowrap" class="data">Stewards for Location:<br />
    <form name="judgeChoice" id="judgeChoice">
       	  <select name="judge_location" id="judge_location" onchange="jumpMenu('self',this,0)">
    		<option value=""></option>
            <option value="admin/email_export.php?go=csv&amp;filter=stewards&amp;section=all">All Locations</option>
            <?php do { ?>
    		<option value="admin/email_export.php?go=csv&amp;filter=stewards&amp;section=loc&amp;bid=<?php echo $row_stewarding['id']; ?>"><?php echo $row_stewarding['judgingLocName']." ("; echo dateconvert($row_stewarding['judgingDate'], 3).")"; ?></option>
		    <?php } while ($row_stewarding = mysql_fetch_assoc($stewarding)) ?>
          </select>
  	</form>
    <em>* Be sure to make final <a href="index.php?section=admin&amp;action=assign&amp;go=judging&amp;filter=judges">judging</a> and <a href="index.php?section=admin&amp;action=assign&amp;go=judging&amp;filter=stewards">stewarding</a> location assignments before exporting.</em>    </td>
  </tr>
  <?php } ?>
  <tr>
    <td width="5%" class="dataLabel bdr1T_dashed"><span class="icon"><img src="images/page_go.png" ></span>Export Tab Delimited Files:</td>
    <td width="15%" nowrap="nowrap" class="data bdr1T_dashed"><a href="admin/participants_export.php?go=tab">All Participants</a></td>
    <td width="15%" class="data bdr1T_dashed"><a href="admin/entries_export.php?go=tab&amp;filter=paid">Paid & Received Entries</a></td>
    <td colspan="2" nowrap="nowrap" class="data bdr1T_dashed"><a href="admin/entries_export.php?go=tab">All Entries</a></td>
  </tr>
  <?php if ($totalRows_judging1 > 1) { ?>
  <tr>
    <td class="dataLabel">&nbsp;</td>
    <td colspan="4" nowrap="nowrap" class="data">Participants for Location:
    <form name="tabChoice" id="tabChoice">
       	  <select name="tab_choice" id="tab_choice" onchange="jumpMenu('self',this,0)">
    		<option value=""></option>
            <?php do { ?>
    		<option value="admin/participants_export.php?section=loc&amp;go=tab&amp;bid=<?php echo $row_stewarding2['id']; ?>"><?php echo $row_stewarding2['judgingLocName']." ("; echo dateconvert($row_stewarding2['judgingDate'], 3).")"; ?></option>
		    <?php } while ($row_stewarding2 = mysql_fetch_assoc($stewarding2)) ?>
          </select>
  	</form>
    <em>* Be sure to make final <a href="/index.php?section=admin&amp;go=styles&amp;filter=judging">style</a>, <a href="index.php?section=admin&amp;action=assign&amp;go=judging&amp;filter=judges">judging</a> and <a href="index.php?section=admin&amp;action=assign&amp;go=judging&amp;filter=stewards">stewarding</a> location assignments before exporting.</em></td>
  </tr>
  <tr>
    <td class="dataLabel">&nbsp;</td>
    <td colspan="4" nowrap="nowrap" class="data">Paid and Received Entries for Location:
    <form name="tabChoice" id="tabChoice">
       	  <select name="tab_choice" id="tab_choice" onchange="jumpMenu('self',this,0)">
    		<option value=""></option>
            <?php do { ?>
    		<option value="admin/entries_export.php?section=loc&amp;go=tab&amp;filter=paid&amp;bid=<?php echo $row_judging2['id']; ?>"><?php echo $row_judging2['judgingLocName']." ("; echo dateconvert($row_judging2['judgingDate'], 3).")"; ?></option>
		    <?php } while ($row_judging2 = mysql_fetch_assoc($judging2)) ?>
          </select>
  	</form>    </td>
  </tr>
  <?php } ?>
  <tr>
    <td class="dataLabel">&nbsp;</td>
    <td colspan="4" class="data">For importing into the Homebrew Competition Coordination Program (HCCP), available for download <a href="http://www.folsoms.net/hccp/" target="_blank">here</a>.
    <?php if ($totalRows_judging1 > 1) { ?><p>The tab delimited file for <em>each location</em> should be imported into HCCP as it's own database. Refer to the <a href="http://www.folsoms.net/hccp/hccp.pdf" target="_blank">HCCP documentation</a> for import instructions.</p><?php } ?>    </td>
  </tr>
  <tr>
    <td width="5%" class="dataLabel bdr1T_dashed"><span class="icon"><img src="images/page_excel_go.png" ></span>Export CSV Files:</td>
    <td width="15%" nowrap="nowrap" class="data bdr1T_dashed"><a href="admin/participants_export.php?go=csv">All Participants</a></td>
    <td width="15%" class="data bdr1T_dashed"><a href="admin/entries_export.php?go=csv&amp;filter=paid">Paid & Received Entries</a></td>
    <td width="15%" nowrap="nowrap" class="data bdr1T_dashed"><a href="admin/entries_export.php?go=csv">All Entries</a></td>
    <td class="data bdr1T_dashed"><a href="admin/entries_export.php?go=csv&amp;filter=nopay&amp;action=hccp"></a><a href="admin/entries_export.php?go=csv"></a> <a href="admin/entries_export.php?go=csv&amp;filter=nopay&amp;action=hccp">Non-Paid & Received Entries</a><a href="admin/entries_export.php?go=csv"></a></td>
  </tr>
  <tr>
    <td class="dataLabel bdr1T_dashed"><span class="icon"><img src="images/cog.png"  /></span>Maintenance:</td>
    <td colspan="4" nowrap="nowrap" class="data bdr1T_dashed"><a href="index.php?section=admin&amp;go=archive">Archives</a></td>
  </tr>
  <tr>
    <td class="dataLabel bdr1B">&nbsp;</td>
    <td colspan="4" nowrap="nowrap" class="data bdr1B">View and create archived information from previous competitions.</td>
  </tr>
</table>
<?php 	} ?>
<?php 
if ($go == "contest_info") 	include ('admin/contest_info.admin.php');
if ($go == "preferences") 	include ('admin/preferences.admin.php');
if ($go == "participants") 	include ('admin/participants.admin.php');
if ($go == "entries") 		include ('admin/entries.admin.php');
if ($go == "make_admin") 	include ('admin/make_admin.admin.php');
if ($go == "archive") 	    include ('admin/archive.admin.php');
if ($go == "sponsors") 	    include ('admin/sponsors.admin.php');
if ($go == "contacts") 	    include ('admin/contacts.admin.php');
if ($go == "judging") 	    include ('admin/judging.admin.php');
if ($go == "styles") 	    include ('admin/styles.admin.php');
if ($go == "dropoff") 	    include ('admin/dropoff.admin.php');
}
else echo "<div class=\"error\">You do not have sufficient privileges to access this area.</div>";
?>
