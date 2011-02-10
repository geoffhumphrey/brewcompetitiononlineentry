<script type="text/javascript" language="javascript" src="js_includes/jquery.js"></script>
<script type="text/javascript" language="javascript" src="js_includes/jquery.dataTables.js"></script>
<?php if (($section == "admin") && ($go == "default")) { ?>
<script type="text/javascript">
$(document).ready(function(){
	$(".toggle_container").hide();
	$(".trigger").click(function(){
		$(this).next(".toggle_container").slideToggle(400);
	});
	// $("div.toggle_container:eq(0)").show();
});

/* 
// For accordian effect
$(document).ready(function()
{
	//slides the element with class "menu_body" when paragraph with class "menu_head" is clicked 
	$("#menu_container p.trigger").click(function()
    {
		$(this).css({backgroundImage:"url(images/bullet_star.png)"}).next("div.toggle_container").slideToggle(300).siblings("div.toggle_container").slideUp("slow");
       	$(this).siblings().css({backgroundImage:"url(images/bullet_go.png)"});
	});
	//slides the element with class "menu_body" when mouse is over the paragraph
	$("div.toggle_container:eq(0)").show();
});

*/

</script>
<style type="text/css">
.trigger {
	padding: 5px 0 5px 0;
	cursor: pointer;
	position: relative;
	margin: 1px;
    font-weight: bold;
	font-size: 1.2em;
}

.trigger a {
	outline: 0;
}

.trigger a:link, .trigger a:visited, .trigger a:hover, .trigger a:active {
 	text-decoration: none;
}

.toggle_container {
	background-image: none;
	/* Use the following for accordian */
	/* display: none */
}

.toggle_container a {
	/* Use the following for accordian */
	/* display: block */
}

</style>
<?php } ?>
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
<p>Click the headings below to expand and collapse each category.</p>
<div id="menu_container">
<p class="trigger"><span class="icon"><img src="images/cog.png"  /></span>Preferences</p>
<div class="toggle_container">
<table class="dataTable">
  <tr>
    <td colspan="2" class="dataLabel bdr1B_dashed">Edit</td>
  </tr>
  <tr>
    <td width="15%" class="data"><a href="index.php?section=admin&amp;go=preferences">Site Preferences</a></td>
    <td class="data"><a href="index.php?section=admin&amp;go=judging_preferences">Competition Organization Preferences</a></td>
  </tr>
</table>
</div>
<p class="trigger"><span class="icon"><img src="images/page.png"  /></span>Competition Info</p>
<div class="toggle_container">
<table class="dataTable">
  <tr>
    <td colspan="2" class="dataLabel bdr1B_dashed">Edit</td>
  </tr>
  <tr>
    <td width="15%" class="data"><a href="index.php?section=admin&amp;go=contest_info">Competition Info</a></td>
	<td class="data"></td>
  </tr>
  <tr>
    <td colspan="2" class="dataLabel bdr1B_dashed">Upload</td>
  </tr>
  <tr>
    <td class="data"><a href="admin/upload.admin.php?KeepThis=true&amp;TB_iframe=true&amp;height=350&amp;width=800" title="Upload Competition Logo Image" class="thickbox">Competition Logo</a></td>
    <td class="data"><a href="admin/upload.admin.php?KeepThis=true&amp;TB_iframe=true&amp;height=350&amp;width=800" title="Upload Sponsor Logo Image" class="thickbox">Sponsor Logos</a></td>
  </tr> 
</table>
</div>
<p class="trigger"><span class="icon"><img src="images/note.png"  /></span>Entry and Data Gathering</p>
<div class="toggle_container">
<table class="dataTable">
  <tr>
    <td colspan="4" class="dataLabel bdr1B_dashed">Manage/View</td>
  </tr>
  <tr>
    <td width="15%" class="data"><a href="index.php?section=admin&amp;go=participants">Participants</a></td>
    <td width="15%" class="data"><a href="index.php?section=admin&amp;go=entries">Entries</a></td>
    <td width="15%" class="data"><a href="index.php?section=admin&amp;go=styles">Accepted Style Categories</a><a href="index.php?section=admin&amp;go=entries"></a></td>
    <td class="data"><?php if ($totalRows_judging > 1) { ?><a href="index.php?section=admin&amp;go=styles&amp;filter=judging">Style Categories for Judging Locations</a><?php } else echo ""; ?></td>
  </tr>
  <tr>
    <td class="data"><a href="index.php?section=admin&amp;go=judging">Judging Locations</a></td>
    <td class="data"><a href="index.php?section=admin&amp;go=participants&amp;filter=judges">Available Judges</a></td>
    <td class="data"><a href="index.php?section=admin&amp;go=participants&amp;filter=stewards">Available Stewards</a><a href="index.php?section=admin&amp;go=dropoff"></a></td>
    <td class="data"><a href="index.php?section=admin&amp;go=contacts">Competition Contacts</a></td>
  </tr>
  <tr>
    <td class="data"><a href="index.php?section=admin&amp;go=dropoff">Drop-Off Locations</a></td>
    <td colspan="3" class="data"><a href="index.php?section=admin&amp;go=sponsors">Sponsors</a></td>
  </tr>
  <tr>
    <td colspan="4" class="dataLabel bdr1B_dashed">Add</td>
  </tr>
  <tr>
    <td class="data"><a href="index.php?section=admin&amp;go=participants&amp;action=add">A Participant</a></td>
    <td class="data"><a href="index.php?section=brew&amp;go=entries&amp;action=add&amp;filter=admin">A Participant's Entry</a></td>
    <td colspan="2" class="data"><a href="index.php?section=admin&amp;go=judging&amp;action=add">A Judging Location</a><a href="index.php?section=admin&amp;go=dropoff&amp;action=add"></a><a href="index.php?section=admin&amp;go=contacts&amp;action=add"></a></td>
  </tr>
  <tr>
    <td class="data"><a href="index.php?section=admin&amp;go=styles&amp;action=add">A Custom Style Category</a></td>
    <td class="data"><a href="index.php?section=admin&amp;go=dropoff&amp;action=add">A Drop-Off Location</a></td>
    <td colspan="2" class="data"><a href="index.php?section=admin&amp;go=sponsors&amp;action=add">A Sponsor</a></td>
  </tr>
  <tr>
    <td colspan="4" class="data"><a href="index.php?section=admin&amp;go=contacts&amp;action=add">A Competition Contact</a><a href="index.php?section=admin&amp;go=contacts&amp;action=add"></a></td>
    </tr> 
</table>
</div>
<p class="trigger"><span class="icon"><img src="images/arrow_refresh.png"  /></span>Sorting Received Entries</p>
<div class="toggle_container">
<table class="dataTable">
  <tr>
    <td colspan="3" class="dataLabel bdr1B_dashed">Manage/View</td>
  </tr>
  <tr>
    <td width="15%" class="data"><a href="index.php?section=admin&amp;go=participants">Participants</a></td>
    <td width="15%" class="data"><a href="index.php?section=admin&amp;go=entries">Entries</a></td>
    <td class="data">Entries by Category (Mark as Paid/Received)</td>
  </tr>
  <tr>
    <td colspan="3" class="dataLabel bdr1B_dashed">Add</td>
  </tr>
  <tr>
    <td class="data"><a href="index.php?section=admin&amp;go=participants&amp;action=add">A Participant</a></td>
    <td colspan="2" class="data"><a href="index.php?section=brew&amp;go=entries&amp;action=add&amp;filter=admin">A Participant's Entry</a></td>
  </tr>
</table>
</div>
<p class="trigger"><span class="icon"><img src="images/book.png" alt="" /></span>Organizing</p>
<div class="toggle_container">
<table class="dataTable">
  <tr>
    <td colspan="4" class="dataLabel bdr1B_dashed">Manage/View</td>
  </tr>
  <tr>
    <td width="15%" class="data"><a href="index.php?section=admin&amp;go=participants">Participants</a></td>
    <td colspan="3" class="data"><a href="index.php?section=admin&amp;go=entries">Entries</a></td>
    </tr>
  <tr>
  	<td class="data"><a href="index.php?section=admin&amp;go=judging_tables">Tables</a></td>
  	<td width="15%" class="data"><a href="index.php?section=admin&amp;go=judging_tables&amp;filter=orphans">Styles Not Assigned to Tables</a></td>
    <td colspan="2" class="data"><a href="index.php?section=admin&amp;go=judging_flights">Flights</a></td>
  </tr>
  <tr>
    <td class="data"><a href="index.php?section=admin&amp;go=assign_tables">Judging Assignments</a></td>
    <td class="data"><a href="index.php?section=admin&amp;go=participants&amp;filter=assignJudges">Assigned Judges</a></td>
    <td colspan="2" class="data"><a href="index.php?section=admin&amp;go=participants&amp;filter=assignStewards">Assigned Stewards</a></td>
    </tr>
  
  <tr>
    <td colspan="4" class="dataLabel bdr1B_dashed">Add</td>
  </tr>
  <tr>
    <td width="15%" class="data"><a href="index.php?section=admin&amp;go=participants&amp;action=add">A Participant</a></td>
    <td colspan="3" class="data"><a href="index.php?section=brew&amp;go=entries&amp;action=add&amp;filter=admin">A Participant's Entry</a></td>
    </tr>
  <tr>
    <td class="data"><a href="index.php?section=admin&amp;go=judging_tables&amp;action=add">A Table</a></td>
    <td class="data"><a href="index.php?section=admin&amp;go=judging_flights">Flights to Tables</a></td>
    <td colspan="2" class="data">Flights to Rounds</td>
  </tr>
  <tr>
    <td colspan="4" class="dataLabel bdr1B_dashed">Assign</td>
  </tr>
  <tr>
    <td class="data"><a href="index.php?section=admin&amp;action=assign&amp;go=judging&amp;filter=judges">Judges (Final Assignments)</a></td>
    <td nowrap="nowrap" colspan="3" class="data"><a href="index.php?section=admin&amp;action=assign&amp;go=judging&amp;filter=stewards">Stewards (Final Assignments)</a></td>
  </tr>
  <?php if ($totalRows_judging > 1) { ?>
  <tr>
    <td class="data"><a href="index.php?section=admin&amp;action=update&amp;go=judging&amp;filter=judges">Judges to a Location</a></td>
    <td nowrap="nowrap" colspan="3" class="data"><a href="index.php?section=admin&amp;action=update&amp;go=judging&amp;filter=stewards">Stewards to a Location</a></td>
  </tr>
  <?php } ?>
  <?php if ($totalRows_tables > 1) { ?>
  <tr>
    <td class="data"><a href="index.php?section=admin&amp;action=update&amp;go=assign_tables&amp;filter=judges">Judges to a Table</a></td>
    <td nowrap="nowrap" colspan="3" class="data"><a href="index.php?section=admin&amp;action=update&amp;go=assign_tables&amp;filter=stewards">Stewards to a Table</a></td>
  </tr>
  <?php } ?>
</table>
</div>
<p class="trigger"><span class="icon"><img src="images/rosette.png"  /></span>Scoring</p>
<div class="toggle_container">
<table class="dataTable">
  <tr>
    <td colspan="4" class="dataLabel bdr1B_dashed">Manage/View</td>
  </tr>
  <tr>
    <td width="15%"><a href="index.php?section=admin&amp;go=participants">Participants</a></td>
    <td width="15%" class="data"><a href="index.php?section=admin&amp;go=entries">Entries</a></td>
    <td colspan="2" class="data"><a href="index.php?section=admin&amp;go=judging_tables">Tables</a></td> 
  </tr>
  <tr>
  	<td class="data"><a href="index.php?section=admin&amp;go=judging_scores">Scores by Table</a></td>
    <td class="data"><a href="index.php?section=admin&amp;go=judging_scores&amp;filter=category">Scores by Category</a></td>
    <td colspan="2" class="data"><a href="index.php?section=admin&amp;go=judging_scores&amp;filter=bos">BOS Scores</a></td>
  </tr>
  <tr>
    <td colspan="4" class="dataLabel bdr1B_dashed">Add</td>
  </tr>
  <tr>
    <td><a href="index.php?section=admin&amp;go=judging_scores&amp;action=add">Scores By Table</a></td>
    <td class="data"><a href="index.php?section=admin&amp;go=judging_scores&amp;action=add&amp;filter=category">Scores by Category</a></td>
    <td colspan="2" class="data"><a href="index.php?section=admin&amp;go=judging_scores&amp;action=add&amp;filter=bos">BOS Scores</a></td>
  </tr>
</table>
</div>
<p class="trigger"><span class="icon"><img src="images/printer.png"  /></span>Printing and Reporting</p>
<div class="toggle_container">
<table class="dataTable">
<tbody>
  <tr>
    <td colspan="4" class="dataLabel bdr1B_dashed">Pre-Competition</td>
  </tr>
  <tr>
    <td width="15%"><a class="thickbox" href="pullsheets.php?section=admin&amp;go=judging_tables&amp;id=default&amp;KeepThis=true&amp;TB_iframe=true&amp;height=425&amp;width=700" title="Print Pullsheets by Table">Pullsheets by Table</a></td>
    <td colspan="3" class="data"><a class="thickbox" href="pullsheets.php?section=admin&amp;go=judging_tables&amp;filter=category&amp;id=default&amp;KeepThis=true&amp;TB_iframe=true&amp;height=425&amp;width=700" title="Print Pullsheets by Category">Pull Sheets By Category</a></td>
    </tr>
  <tr>
    <td colspan="4" class="dataLabel bdr1B_dashed">During Competition</td>
  </tr>
  <tr>
    <td class="data">Judging Assignments</td>
    <td width="15%" class="data">Steward Assignments</td>
    <td width="15%" class="data">Table Cards</td>
    <td class="data">Pullsheets for BOS</td>
  </tr>
  <tr>
    <td colspan="4" class="dataLabel bdr1B_dashed">Post-Competition</td>
  </tr>
  <tr>
    <td class="data">Results by Table</td>
    <td class="data">Results by Category</td>
    <td class="data">Judge/Steward/Staff Points</td>
    <td class="data">Judge/Steward/Staff Points (XML)</td>
  </tr>
</tbody>
</table>
</div>
<p class="trigger"><span class="icon"><img src="images/page_go.png"  /></span>Exporting</p>
<div class="toggle_container">
<table class="dataTable">
<tbody>
<tr>
    <td colspan="4" class="dataLabel bdr1B_dashed">Email Addresses (CSV Files)</td>
  </tr>
  <tr>
    <td width="15%" class="data"><a href="admin/email_export.php">All Participants</a></td>
    <td width="15%" class="data"><a href="admin/entries_export.php?go=csv&amp;filter=paid&amp;action=email">Paid & Received Entries</a><a href="admin/email_export.php?filter=judges"></a></td>
    <td width="15%" class="data"><a href="admin/entries_export.php?go=csv&amp;action=email">All Entries</a></td>
    <td class="data"><a href="admin/entries_export.php?go=csv&amp;filter=nopay&amp;action=email">Non-Paid & Received Entries</a><a href="admin/email_export.php?filter=stewards"></a><a href="admin/entries_export.php?go=csv&amp;filter=paid&amp;action=email"></a> <a href="admin/email_export.php?filter=judges"></a><a href="admin/entries_export.php?go=csv&amp;filter=nopay&amp;action=email"></a><a href="admin/email_export.php?filter=stewards"></a></td>
  </tr>
  <?php if ($totalRows_judging == 1) { ?>
  <tr>
    <td class="data"><a href="admin/email_export.php?filter=judges">Judges</a>
    <a href="admin/email_export.php?filter=stewards"></a></td>
    <td colspan="3" class="data"><a href="admin/email_export.php?filter=stewards">Stewards</a></td>
  </tr>
  <?php } ?>
  <?php if ($totalRows_judging1 > 1) { ?>
  <tr>
    <td colspan="4" class="data">Judges for Location:<br />
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
    <td colspan="4" class="data">Stewards for Location:<br />
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
    <td colspan="4" class="dataLabel bdr1B_dashed">Tab Delimited Files</td>
  </tr>
  <tr>
    <td colspan="4">For importing into the Homebrew Competition Coordination Program (HCCP), available for download <a href="http://www.folsoms.net/hccp/" target="_blank">here</a>. <?php if ($totalRows_judging1 > 1) { ?>The tab delimited file for <em>each location</em> should be imported into HCCP as it's own database. Refer to the <a href="http://www.folsoms.net/hccp/hccp.pdf" target="_blank">HCCP documentation</a> for import instructions.</p><?php } ?> 
    </td>
  </tr>
  <tr>
    <td width="15%"><a href="admin/participants_export.php?go=tab">All Participants</a></td>
    <td width="15%" class="data"><a href="admin/entries_export.php?go=tab&amp;filter=paid">Paid & Received Entries</a></td>
    <td colspan="2" class="data"><a href="admin/entries_export.php?go=tab">All Entries</a></td>
  </tr>
  <?php if ($totalRows_judging1 > 1) { ?>
  <form name="tabChoice" id="tabChoice">
  <tr>
    <td colspan="3" class="data">Participants for Location:
        <select name="tab_choice" id="tab_choice" onchange="jumpMenu('self',this,0)">
          <option value=""></option>
          <?php do { ?>
          <option value="admin/participants_export.php?section=loc&amp;go=tab&amp;bid=<?php echo $row_stewarding2['id']; ?>"><?php echo $row_stewarding2['judgingLocName']." ("; echo dateconvert($row_stewarding2['judgingDate'], 3).")"; ?></option>
          <?php } while ($row_stewarding2 = mysql_fetch_assoc($stewarding2)) ?>
        </select><br />
    <em>* Be sure to make final <a href="/index.php?section=admin&amp;go=styles&amp;filter=judging">style</a>, <a href="index.php?section=admin&amp;action=assign&amp;go=judging&amp;filter=judges">judging</a> and <a href="index.php?section=admin&amp;action=assign&amp;go=judging&amp;filter=stewards">stewarding</a> location assignments before exporting.</em></td>
  </tr>
  </form>
  <form name="tabChoice" id="tabChoice">
  <tr>
    <td colspan="4" class="data">Paid and Received Entries for Location:
        <select name="tab_choice" id="tab_choice" onchange="jumpMenu('self',this,0)">
          <option value=""></option>
          <?php do { ?>
          <option value="admin/entries_export.php?section=loc&amp;go=tab&amp;filter=paid&amp;bid=<?php echo $row_judging2['id']; ?>"><?php echo $row_judging2['judgingLocName']." ("; echo dateconvert($row_judging2['judgingDate'], 3).")"; ?></option>
          <?php } while ($row_judging2 = mysql_fetch_assoc($judging2)) ?>
        </select>
    </td>
  </tr>
  </form>    
  <?php } ?>
  <tr>
    <td colspan="4" class="dataLabel bdr1B_dashed">CSV Files</td>
  </tr>
  <tr>
    <td><a href="admin/entries_export.php?go=csv">All Entries</a></td>
    <td class="data"><a href="admin/entries_export.php?go=csv&amp;filter=paid">Paid & Received Entries</a></td>
    <td colspan="2" class="data"><a href="admin/entries_export.php?go=csv"></a><a href="admin/entries_export.php?go=csv&amp;filter=nopay&amp;action=hccp"></a><a href="admin/entries_export.php?go=csv"></a> <a href="admin/entries_export.php?go=csv&amp;filter=nopay&amp;action=hccp">Non-Paid & Received Entries</a><a href="admin/entries_export.php?go=csv"></a></td>
  </tr>
  <tr>
    <td><a href="admin/participants_export.php?go=csv">All Participants</a></td>
    <td colspan="3" class="data"><a href="admin/entries_export.php?go=csv&amp;filter=winners">Winners</a></td>
  </tr>
  <tr>
    <td colspan="4" class="dataLabel bdr1B_dashed">Promo Materials</td>
  </tr>
  <tr>
    <td width="15%" class="data"><a href="admin/promo_export.admin.php?action=html">HTML</a></td>
    <td colspan="3" width="15%" class="data"><a href="admin/promo_export.admin.php?action=word">Word</a></td>
  </tr>  
</table>
</div>
<p class="trigger"><span class="icon"><img src="images/camera_add.png" /></span>Archiving</p>
<div class="toggle_container">
<table class="dataTable">
<tbody>
<tr>
	<td class="data"><a href="index.php?section=admin&amp;go=archive">Archive Competition Data</a></td>
</tr>
</tbody>
</table>
</div>
</div>
<?php 	} 

if ($go == "contest_info") 				include ('admin/contest_info.admin.php');
if ($go == "preferences") 				include ('admin/preferences.admin.php');
if ($go == "judging") 	    			include ('admin/judging.admin.php');
if ($go == "judging_preferences") 	    include ('admin/judging_preferences.admin.php');
if ($go == "judging_tables") 	    	include ('admin/judging_tables.admin.php');
if ($go == "judging_flights") 	    	include ('admin/judging_flights.admin.php');
if ($go == "judging_scores") 	    	include ('admin/judging_scores.admin.php');
if ($go == "participants") 				include ('admin/participants.admin.php');
if ($go == "entries") 					include ('admin/entries.admin.php');
if ($go == "make_admin") 				include ('admin/make_admin.admin.php');
if ($go == "archive") 	    			include ('admin/archive.admin.php');
if ($go == "sponsors") 	   			 	include ('admin/sponsors.admin.php');
if ($go == "contacts") 	    			include ('admin/contacts.admin.php');
if ($go == "styles") 	    			include ('admin/styles.admin.php');
if ($go == "dropoff") 	    			include ('admin/dropoff.admin.php');

}
else echo "<div class=\"error\">You do not have sufficient privileges to access this area.</div>";
?>
