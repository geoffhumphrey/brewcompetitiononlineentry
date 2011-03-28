<?php 
include(DB.'judging_locations.db.php'); 
include(DB.'stewarding.db.php'); 
?>
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
<p>Click the headings below to expand and collapse each category.&nbsp;<a href="" alt="Get Help" title="Get Help"><span class="icon"><img src="images/help.png"  /></span></a></p>
<div id="menu_container">
<p class="trigger"><span class="icon"><img src="images/cog.png"  /></span>Preferences</p>
<div class="toggle_container">
<p class="admin_default_header">Edit&nbsp;<a href="" alt="Get Help" title="Get Help"><span class="icon"><img src="images/help.png"  /></span></a></p>
<ul class="admin_default">
	<li><a href="index.php?section=admin&amp;go=preferences">Site Preferences</a></li>
    <li><a href="index.php?section=admin&amp;go=judging_preferences">Competition Organization Preferences</a></li>
</ul>
</div>
<p class="trigger"><span class="icon"><img src="images/page.png"  /></span>Competition Info</p>
<div class="toggle_container">
<p class="admin_default_header">Edit&nbsp;<a href="" alt="Get Help" title="Get Help"><span class="icon"><img src="images/help.png"  /></span></a></p>
<ul class="admin_default">
	<li><a href="index.php?section=admin&amp;go=contest_info">Competition Info</a></li>
</ul>
<p class="admin_default_header">Upload&nbsp;<a href="" alt="Get Help" title="Get Help"><span class="icon"><img src="images/help.png"  /></span></a></p>
<ul class="admin_default">
	<li><a href="admin/upload.admin.php?KeepThis=true&amp;TB_iframe=true&amp;height=600&amp;width=800" title="Upload Competition Logo Image" class="thickbox">Competition Logo</a></li>
    <li><a href="admin/upload.admin.php?KeepThis=true&amp;TB_iframe=true&amp;height=600&amp;width=800" title="Upload Sponsor Logo Image" class="thickbox">Sponsor Logos</a></li>
</ul>
</div>
<p class="trigger"><span class="icon"><img src="images/note.png"  /></span>Entry and Data Gathering</p>
<div class="toggle_container">
<p class="admin_default_header">Manage/View&nbsp;<a href="" alt="Get Help" title="Get Help"><span class="icon"><img src="images/help.png"  /></span></a></p>
<ul class="admin_default">
	<li><a href="index.php?section=admin&amp;go=participants">Participants</a></li>
    <li><a href="index.php?section=admin&amp;go=entries">Entries</a></li>
    <li><a href="index.php?section=admin&amp;go=styles">Accepted Style Categories</a></li>
    <li><a href="index.php?section=admin&amp;go=style_types">Style Types</a></li>
</ul>
<ul class="admin_default">
    <li><a href="index.php?section=admin&amp;go=judging">Judging Locations</a></li>
    <li><a href="index.php?section=admin&amp;go=participants&amp;filter=judges">Available Judges</a></li>
    <li><a href="index.php?section=admin&amp;go=participants&amp;filter=stewards">Available Stewards</a></li>
    <li><a href="index.php?section=admin&amp;go=contacts">Competition Contacts</a></li>
</ul>
<ul class="admin_default">
    <li><a href="index.php?section=admin&amp;go=dropoff">Drop-Off Locations</a></li>
    <li><a href="index.php?section=admin&amp;go=sponsors">Sponsors</a></li>
</ul>
<p class="admin_default_header">Add&nbsp;<a href="" alt="Get Help" title="Get Help"><span class="icon"><img src="images/help.png"  /></span></a></p>
<ul class="admin_default">
    <li><a href="index.php?section=admin&amp;go=participants&amp;action=add">A Participant</a></li>
    <li><a href="index.php?section=brew&amp;go=entries&amp;action=add&amp;filter=admin">A Participant's Entry</a></li>
    <li><a href="index.php?section=admin&amp;go=styles&amp;action=add">A Custom Style Category</a></li>
  	<li><a href="index.php?section=admin&amp;go=style_types&amp;action=add">A Style Type</a></li>
</ul>
<ul class="admin_default">
    <li><a href="index.php?section=admin&amp;go=judging&amp;action=add">A Judging Location</a></li>
    <li><a href="index.php?section=admin&amp;go=dropoff&amp;action=add">A Drop-Off Location</a></li>
    <li><a href="index.php?section=admin&amp;go=sponsors&amp;action=add">A Sponsor</a></li>
    <li><a href="index.php?section=admin&amp;go=contacts&amp;action=add">A Competition Contact</a></li>
</ul>
</div>
<p class="trigger"><span class="icon"><img src="images/arrow_refresh.png"  /></span>Sorting Received Entries</p>
<div class="toggle_container">
<p class="admin_default_header">Manage/View&nbsp;<a href="" alt="Get Help" title="Get Help"><span class="icon"><img src="images/help.png"  /></span></a></p>
<ul class="admin_default">
    <li><a href="index.php?section=admin&amp;go=participants">Participants</a></li>
    <li><a href="index.php?section=admin&amp;go=entries">Entries</a></li>
</ul>
<ul class="admin_default">
    <li>Mark Entries as Paid/Received for Category:</li>
    <li><?php echo style_choose($section,"entries",$action,$filter); ?></li>
</ul>
<p class="admin_default_header">Add&nbsp;<a href="" alt="Get Help" title="Get Help"><span class="icon"><img src="images/help.png"  /></span></a></p>
<ul class="admin_default">
    <li><a href="index.php?section=admin&amp;go=participants&amp;action=add">A Participant</a></li>
    <li><a href="index.php?section=brew&amp;go=entries&amp;action=add&amp;filter=admin">A Participant's Entry</a></li>
</ul>
<p class="admin_default_header">Print&nbsp;<a href="" alt="Get Help" title="Get Help"><span class="icon"><img src="images/help.png"  /></span></a></p>
<ul class="admin_default">
    <li><a href="output/labels.php?section=admin&amp;go=entries&amp;filter=bottle&amp;KeepThis=true&amp;TB_iframe=true&amp;height=600&amp;width=800">Bottle Labels</a></li>
</ul>
</div>
<p class="trigger"><span class="icon"><img src="images/book.png" alt="" /></span>Organizing</p>
<div class="toggle_container">
<p class="admin_default_header">Manage/View&nbsp;<a href="" alt="Get Help" title="Get Help"><span class="icon"><img src="images/help.png"  /></span></a></p>
<ul class="admin_default">
    <li><a href="index.php?section=admin&amp;go=participants">Participants</a></li>
    <li><a href="index.php?section=admin&amp;go=entries">Entries</a></li>
</ul>
  <?php if ($row_prefs['prefsCompOrg'] == "Y") { ?>
<ul class="admin_default">
  	<li><a href="index.php?section=admin&amp;go=judging_tables">Tables</a></li>
    <li><a href="index.php?section=admin&amp;go=judging_flights">Flights</a></li>
</ul>
<ul class="admin_default">
  	<li><a href="index.php?section=admin&amp;go=styles&amp;filter=orphans">Styles Without a Valid Style Type</a></li>
  	<li><a href="index.php?section=admin&amp;go=judging_tables&amp;filter=orphans">Styles Not Assigned to Tables</a></li>
</ul>
  <?php } ?>
<ul class="admin_default">
    <li><a href="index.php?section=admin&amp;go=participants&amp;filter=assignJudges">Assigned Judges</a></li>
    <li><a href="index.php?section=admin&amp;go=participants&amp;filter=assignStewards">Assigned Stewards</a></li>
</ul>
<p class="admin_default_header">Add&nbsp;<a href="" alt="Get Help" title="Get Help"><span class="icon"><img src="images/help.png"  /></span></a></p>
<ul class="admin_default">
    <li><a href="index.php?section=admin&amp;go=participants&amp;action=add">A Participant</a></li>
    <li><a href="index.php?section=brew&amp;go=entries&amp;action=add&amp;filter=admin">A Participant's Entry</a></li>
</ul>
  <?php if ($row_prefs['prefsCompOrg'] == "Y") { ?>
<ul class="admin_default">
    <li><a href="index.php?section=admin&amp;go=judging_tables&amp;action=add">A Table</a></li>
    <li><a href="index.php?section=admin&amp;go=judging_flights">Flights to Tables</a></li>
    <li><a href="index.php?section=admin&amp;go=judging_flights&amp;action=assign&amp;filter=rounds">Flights to Rounds</a></li>
</ul>
  <?php } ?>
<p class="admin_default_header">Assign&nbsp;<a href="" alt="Get Help" title="Get Help"><span class="icon"><img src="images/help.png"  /></span></a></p>
  <?php if ($row_prefs['prefsCompOrg'] == "N") { ?>
<ul class="admin_default">
    <li><a href="index.php?section=admin&amp;go=judging&amp;action=assign&amp;filter=judges">Judges (Final Assignments)</a></li>
    <li><a href="index.php?section=admin&amp;go=judging&amp;action=assign&amp;filter=stewards">Stewards (Final Assignments)</a></li>
</ul>
  	<?php if ($totalRows_judging > 1) { ?>
<ul class="admin_default">
    <li><a href="index.php?section=admin&amp;go=judging&amp;action=update&amp;filter=judges">Judges to a Location</a></li>
    <li><a href="index.php?section=admin&amp;go=judging&amp;action=update&amp;filter=stewards">Stewards to a Location</a></li>
</ul>
 	 <?php } ?>
  <?php } ?>
  <?php if ($row_prefs['prefsCompOrg'] == "Y") { ?>
  	<?php if ($totalRows_tables > 1) { ?>
<ul class="admin_default">
    <li><a href="index.php?section=admin&amp;go=judging_tables&amp;action=assign">Judges or Stewards to a Table</a></li>
</ul>
  	<?php } ?>
  <?php } ?>
</div>
<?php if ($row_prefs['prefsCompOrg'] == "Y") { ?>
<p class="trigger"><span class="icon"><img src="images/rosette.png"  /></span>Scoring</p>
<div class="toggle_container">
<p class="admin_default_header">Manage/View&nbsp;<a href="" alt="Get Help" title="Get Help"><span class="icon"><img src="images/help.png"  /></span></a></p>
<ul class="admin_default">
    <li><a href="index.php?section=admin&amp;go=participants">Participants</a></li>
    <li><a href="index.php?section=admin&amp;go=entries">Entries</a></li>
    <li><a href="index.php?section=admin&amp;go=judging_tables">Tables</a></li> 
</ul>
<ul class="admin_default">
  	<li><a href="index.php?section=admin&amp;go=judging_scores">Scores by Table</a></li>
    <li><a href="index.php?section=admin&amp;go=judging_scores&amp;filter=category">Scores by Category</a></li>
    <li><a href="index.php?section=admin&amp;go=judging_scores_bos">BOS Entries and Places</a></li>
</ul>
<p class="admin_default_header">Add&nbsp;<a href="" alt="Get Help" title="Get Help"><span class="icon"><img src="images/help.png"  /></span></a></p>
<ul class="admin_default">
    <li><a href="index.php?section=admin&amp;go=judging_scores&amp;action=add">Scores</a></li>
</ul>
</div>
<p class="trigger"><span class="icon"><img src="images/printer.png"  /></span>Printing and Reporting</p>
<div class="toggle_container">
<p class="admin_default_header">Before Judging&nbsp;<a href="" alt="Get Help" title="Get Help"><span class="icon"><img src="images/help.png"  /></span></a></p>
<ul class="admin_default">
	<li>Pullsheets</li>
    <li>&#9654;</li>
    <li><a class="thickbox" href="output/pullsheets.php?section=admin&amp;go=judging_tables&amp;id=default&amp;KeepThis=true&amp;TB_iframe=true&amp;height=600&amp;width=800" title="Print All Table Pullsheets">All Tables</a></li>
    <li>
    <div class="menuBar"><a class="menuButton" href="#" onclick="#" onmouseover="buttonMouseover(event, 'pullsheets');">For Table #...</a></div>
    	<div id="pullsheets" class="menu" onmouseover="menuMouseover(event)">
    		<?php do { ?>
			<a class="menuItem thickbox" style="font-size: .9em; padding: 1px;" href="output/pullsheets.php?section=admin&amp;go=judging_tables&amp;id=<?php echo $row_tables['id']; ?>&amp;KeepThis=true&amp;TB_iframe=true&amp;height=600&amp;width=800" title="Print Pullsheet for Table # <?php echo $row_tables['tableNumber'].": ".$row_tables['tableName']; ?>"><?php echo $row_tables['tableNumber'].": ".$row_tables['tableName']; ?></a>
    		<?php } while ($row_tables = mysql_fetch_assoc($tables)); ?>
    	</div>
    </li>
</ul>
<ul class="admin_default">
	<li>Table Cards</li>
    <li>&#9654;</li>
    <li><a class="thickbox" href="output/table_cards.php?section=admin&amp;go=judging_tables&amp;id=default&amp;KeepThis=true&amp;TB_iframe=true&amp;height=600&amp;width=800" title="Print Pullsheets by Table">All Tables</a></li>
    <li>
    <div class="menuBar"><a class="menuButton" href="#" onclick="#" onmouseover="buttonMouseover(event, 'table_cards');">For Table #...</a></div>
    	<div id="table_cards" class="menu" onmouseover="menuMouseover(event)">
    		<?php do { ?>
			<a class="menuItem thickbox" style="font-size: .9em; padding: 1px;" href="output/table_cards.php?section=admin&amp;go=judging_tables&amp;id=<?php echo $row_tables_edit['id']; ?>&amp;KeepThis=true&amp;TB_iframe=true&amp;height=600&amp;width=800" title="Print Table Card for Table #<?php echo $row_tables_edit['tableNumber'].": ".$row_tables_edit['tableName']; ?>"><?php echo $row_tables_edit['tableNumber'].": ".$row_tables_edit['tableName']; ?></a>
    		<?php } while ($row_tables_edit = mysql_fetch_assoc($tables_edit)); ?>
    	</div>
    </li>
</ul>
<ul class="admin_default">
	<li>Judge Assignments</li>
    <li>&#9654;</li>
    <li><a class="thickbox" href="output/assignments.php?section=admin&amp;go=judging_assignments&amp;filter=judges&amp;view=name&amp;KeepThis=true&amp;TB_iframe=true&amp;height=600&amp;width=800" title="Print Judge Assignments by Name">By Judge Last Name</a></li>
    <li><a class="thickbox" href="output/assignments.php?section=admin&amp;go=judging_assignments&amp;filter=judges&amp;view=table&amp;KeepThis=true&amp;TB_iframe=true&amp;height=600&amp;width=800" title="Print Judge Assignments by Table">By Table</a></li>
    <?php if ($totalRows_judging > 1) { ?>
    <li><a class="thickbox" href="output/assignments.php?section=admin&amp;go=judging_assignments&amp;filter=judges&amp;view=location&amp;KeepThis=true&amp;TB_iframe=true&amp;height=600&amp;width=800" title="Print Judge Assignments by Location">By Location</a></li>
    <?php } ?>
</ul>
<ul class="admin_default">
	<li>Steward Assignments</li>
    <li>&#9654;</li>
    <li><a class="thickbox" href="output/assignments.php?section=admin&amp;go=judging_assignments&amp;filter=stewards&amp;view=name&amp;KeepThis=true&amp;TB_iframe=true&amp;height=600&amp;width=800" title="Print Steward Assignments by Name">By Steward Last Name</a></li>
    <li><a class="thickbox" href="output/assignments.php?section=admin&amp;go=judging_assignments&amp;filter=stewards&amp;view=table&amp;KeepThis=true&amp;TB_iframe=true&amp;height=600&amp;width=800" title="Print Steward Assignments by Table">By Table</a></li>
    <?php if ($totalRows_judging > 1) { ?>
    <li><a class="thickbox" href="output/assignments.php?section=admin&amp;go=judging_assignments&amp;filter=stewards&amp;view=location&amp;KeepThis=true&amp;TB_iframe=true&amp;height=600&amp;width=800" title="Print Steward Assignments by Location">By Location</a></li>
	<?php } ?>
</ul>
<ul class="admin_default">
	<li>Sign-in Sheets</li>
    <li>&#9654;</li>
   	<li><a class="thickbox" href="output/assignments.php?section=admin&amp;go=judging_assignments&amp;filter=judges&amp;view=sign-in&amp;KeepThis=true&amp;TB_iframe=true&amp;height=600&amp;width=800" title="Print a Judge Sign-in Sheet">Judges</a></li>   
	<li><a class="thickbox" href="output/assignments.php?section=admin&amp;go=judging_assignments&amp;filter=stewards&amp;view=sign-in&amp;KeepThis=true&amp;TB_iframe=true&amp;height=600&amp;width=800" title="Print a Steward Sign-in Sheet">Stewards</a></li>   
</ul>
<p class="admin_default_header">During Judging&nbsp;<a href="" alt="Get Help" title="Get Help"><span class="icon"><img src="images/help.png"  /></span></a></p>
<ul class="admin_default">
    <li>BOS Pullsheets 
    	<ul>
        	<li>&#9654;</li>
            <li><a class="thickbox" href="output/pullsheets.php?section=admin&amp;go=judging_scores_bos&amp;KeepThis=true&amp;TB_iframe=true&amp;height=600&amp;width=800" title="Print All BOS Pullsheets">All</a></li>
    	  <?php do { ?>
          	<?php if ($row_style_type['styleTypeBOS'] == "Y") { ?><li><a class="thickbox" href="output/pullsheets.php?section=admin&amp;go=judging_scores_bos&amp;id=<?php echo $row_style_type['id']; ?>&amp;KeepThis=true&amp;TB_iframe=true&amp;height=600&amp;width=800"  title="Print the <?php echo $row_style_type['styleTypeName']; ?> BOS Pullsheet"><?php echo $row_style_type['styleTypeName']; ?></a></li><?php } ?>
          <?php } while ($row_style_type = mysql_fetch_assoc($style_type)) ?>
        </ul>
    </li>
</ul>
<p class="admin_default_header">After Judging&nbsp;<a href="" alt="Get Help" title="Get Help"><span class="icon"><img src="images/help.png"  /></span></a></p>
<ul class="admin_default">
    <li><a href="">Results by Table with BOS(s)</a>
    	<ul>
        	<li>&#9654;</li>
        	<li><a href="output/results.php?section=admin&amp;go=judging_scores&amp;filter=all&amp;view=pdf">PDF</a></li>
        	<li><a href="output/results.php?section=admin&amp;go=judging_scores&amp;filter=all&amp;view=html">HTML</a></li>
       	</ul>
    </li>
</ul>
<ul class="admin_default">
    <li><a href="">BOS Round(s) Only</a>
    	<ul>
        	<li>&#9654;</li>
        	<li><a href="output/results.php?section=admin&amp;go=judging_scores&amp;filter=bos&amp;view=pdf">PDF</a></li>
        	<li><a href="output/results.php?section=admin&amp;go=judging_scores&amp;filter=bos&amp;view=html">HTML</a></li>
       	</ul>
    </li>
</ul>
<ul class="admin_default">
    <li><a href="">Judge/Steward/Staff Points</a>
    	<ul>
        	<li>&#9654;</li>
        	<li><a href="">PDF</a></li>
            <li><a href="">HTML</a></li>
        	<li><a href="">XML</a></li>
       	</ul>
    </li>
</ul>
<ul class="admin_default">
    <li>Participant Address Labels
    	<ul>
        	<li>&#9654;</li>
        	<li><a href="output/labels.php?section=admin&amp;go=participants&amp;filter=address&amp;view=pdf">PDF</a></li>
       	</ul>
    </li>
</ul>
<ul class="admin_default">
    <li><a href="">Generate a Link to Results</a> (for posting to a website, blog, Facebook, etc.)</li>
</ul>
</div>
<?php } ?>
<p class="trigger"><span class="icon"><img src="images/page_go.png"  /></span>Exporting</p>
<div class="toggle_container">
<p class="admin_default_header">Email Addresses (CSV Files)&nbsp;<a href="" alt="Get Help" title="Get Help"><span class="icon"><img src="images/help.png"  /></span></a></p>
<ul class="admin_default">
    <li><a href="output/email_export.php">All Participants</a></li>
    <li><a href="output/entries_export.php?section=admin&amp;go=csv&amp;filter=paid&amp;action=email">Paid & Received Entries</a><a href="output/email_export.php?section=admin&amp;filter=judges"></a></li>
    <li><a href="output/entries_export.php?section=admin&amp;go=csv&amp;action=email">All Entries</a></li>
    <li><a href="output/entries_export.php?section=admin&amp;go=csv&amp;filter=nopay&amp;action=email">Non-Paid & Received Entries</a><a href="output/email_export.php?section=admin&amp;filter=stewards"></a><a href="output/entries_export.php?section=admin&amp;go=csv&amp;filter=paid&amp;action=email"></a> <a href="output/email_export.php?filter=judges"></a><a href="output/entries_export.php?section=admin&amp;go=csv&amp;filter=nopay&amp;action=email"></a><a href="output/email_export.php?filter=stewards"></a></li>
</ul>
  <?php if ($totalRows_judging == 1) { ?>
<ul class="admin_default">
    <li><a href="output/email_export.php?section=admin&amp;go=csv&amp;filter=judges&amp;action=email">Judges</a></li>
    <li><a href="output/email_export.php?section=admin&amp;go=csv&amp;filter=stewards&amp;action=email">Stewards</a></li>
</ul>
<?php } ?>
<?php if ($row_prefs['prefsCompOrg'] == "N") { ?>
	<?php if ($totalRows_judging1 > 1) { ?>
<ul class="admin_default">
    <li>Judges for Location:
        <select name="judge_location" id="judge_location" onchange="jumpMenu('self',this,0)">
          <option value=""></option>
          <option value="output/email_export.php?section=all&amp;go=csv&amp;filter=judges&amp;">All Locations</option>
          <?php do { ?>
          <option value="output/email_export.php?section=loc&amp;go=csv&amp;filter=judges&amp;bid=<?php echo $row_judging1['id']; ?>"><?php echo $row_judging1['judgingLocName']." ("; echo dateconvert($row_judging1['judgingDate'], 3).")"; ?></option>
          <?php } while ($row_judging1 = mysql_fetch_assoc($judging1)) ?>
          </select>
    </li>
    <li><em>* Be sure to make final <a href="index.php?section=admin&amp;action=assign&amp;go=judging&amp;filter=judges">judging</a> and <a href="index.php?section=admin&amp;action=assign&amp;go=judging&amp;filter=stewards">stewarding</a> location assignments before exporting.</em></li>
</ul>
<ul class="admin_default">
    <li>Stewards for Location:
        <select name="judge_location" id="judge_location" onchange="jumpMenu('self',this,0)">
          <option value=""></option>
          <option value="output/email_export.php?section=all&amp;go=csv&amp;filter=stewards">All Locations</option>
          <?php do { ?>
          <option value="output/email_export.php?section=loc&amp;go=csv&amp;filter=stewards&amp;bid=<?php echo $row_stewarding['id']; ?>"><?php echo $row_stewarding['judgingLocName']." ("; echo dateconvert($row_stewarding['judgingDate'], 3).")"; ?></option>
          <?php } while ($row_stewarding = mysql_fetch_assoc($stewarding)) ?>
          </select>
    </li>
    <li><em>* Be sure to make final <a href="index.php?section=admin&amp;action=assign&amp;go=judging&amp;filter=judges">judging</a> and <a href="index.php?section=admin&amp;action=assign&amp;go=judging&amp;filter=stewards">stewarding</a> location assignments before exporting.</em>    </li>
</ul>
 <?php } ?>
 <?php } ?>
<p class="admin_default_header">Tab Delimited Files&nbsp;<a href="" alt="Get Help" title="Get Help"><span class="icon"><img src="images/help.png"  /></span></a></li>
<p>For importing into the Homebrew Competition Coordination Program (HCCP), available for download <a href="http://www.folsoms.net/hccp/" target="_blank">here</a>. <?php if ($totalRows_judging1 > 1) { ?>The tab delimited file for <em>each location</em> should be imported into HCCP as it's own database. Refer to the <a href="http://www.folsoms.net/hccp/hccp.pdf" target="_blank">HCCP documentation</a> for import instructions.<?php } ?></p> 
<ul class="admin_default">
    <li><a href="output/participants_export.php?section=admin&amp;go=tab">All Participants</a></li>
    <li><a href="output/entries_export.php?section=admin&amp;go=tab&amp;filter=paid">Paid & Received Entries</a></li>
    <li><a href="output/entries_export.php?section=admin&amp;go=tab">All Entries</a></li>
</ul>
  <?php if ($row_prefs['prefsCompOrg'] == "N") { ?>
  <?php if ($totalRows_judging1 > 1) { ?>
<ul class="admin_default">
    <li>Participants for Location:
        <select name="tab_choice" id="tab_choice" onchange="jumpMenu('self',this,0)">
          <option value=""></option>
          <?php do { ?>
          <option value="output/participants_export.php?section=loc&amp;go=tab&amp;bid=<?php echo $row_stewarding2['id']; ?>"><?php echo $row_stewarding2['judgingLocName']." ("; echo dateconvert($row_stewarding2['judgingDate'], 3).")"; ?></option>
          <?php } while ($row_stewarding2 = mysql_fetch_assoc($stewarding2)) ?>
        </select>
    </li>
    <li><em>* Be sure to make final <a href="/index.php?section=admin&amp;go=styles&amp;filter=judging">style</a>, <a href="index.php?section=admin&amp;action=assign&amp;go=judging&amp;filter=judges">judging</a> and <a href="index.php?section=admin&amp;action=assign&amp;go=judging&amp;filter=stewards">stewarding</a> location assignments before exporting.</em></li>
</ul>
<ul class="admin_default">
    <li>Paid and Received Entries for Location:
        <select name="tab_choice" id="tab_choice" onchange="jumpMenu('self',this,0)">
          <option value=""></option>
          <?php do { ?>
          <option value="output/entries_export.php?section=loc&amp;go=tab&amp;filter=paid&amp;bid=<?php echo $row_judging2['id']; ?>"><?php echo $row_judging2['judgingLocName']." ("; echo dateconvert($row_judging2['judgingDate'], 3).")"; ?></option>
          <?php } while ($row_judging2 = mysql_fetch_assoc($judging2)) ?>
        </select>
    </li>
</ul>   
  	<?php } ?>
  <?php } ?>
<p class="admin_default_header">CSV Files&nbsp;<a href="" alt="Get Help" title="Get Help"><span class="icon"><img src="images/help.png"  /></span></a></p>
<ul class="admin_default">
    <li><a href="output/entries_export.php?section=admin&amp;go=csv">All Entries</a></li>
    <li><a href="output/entries_export.php?section=admin&amp;go=csv&amp;filter=paid">Paid & Received Entries</a></li>
    <li><a href="output/entries_export.php?section=admin&amp;go=csv&amp;filter=nopay&amp;action=hccp">Non-Paid & Received Entries</a><a href="output/entries_export.php?go=csv"></a></li>
</ul>
<ul class="admin_default">
    <li><a href="output/participants_export.php?section=admin&amp;go=csv">All Participants</a></li>
    <li><a href="output/entries_export.php?section=admin&amp;go=csv&amp;filter=winners">Winners</a></li>
</ul>
<p class="admin_default_header">Promo Materials&nbsp;<a href="" alt="Get Help" title="Get Help"><span class="icon"><img src="images/help.png"  /></span></a></p>
<ul class="admin_default">
    <li><a href="output/promo_export.php?section=admin&amp;go=html&amp;action=html">HTML</a></li>
    <li><a href="output/promo_export.php?section=admin&amp;go=word&amp;action=word">Word</a></li>
</ul>
</div>
<p class="trigger"><span class="icon"><img src="images/camera_add.png" /></span>Archiving</p>
<div class="toggle_container">
<p class="admin_default_header">Manage/View&nbsp;<a href="" alt="Get Help" title="Get Help"><span class="icon"><img src="images/help.png"  /></span></a></p>
<ul class="admin_default">
	<li><a href="index.php?section=admin&amp;go=archive">Archives</a></li>
</ul>
</div>
</div>
<?php 	} 
include(DB.'admin_common.db.php');
if ($go == "contest_info") 				include (ADMIN.'competition_info.admin.php');
if ($go == "preferences") 				include (ADMIN.'site_preferences.admin.php');
if ($go == "judging") 	    			include (ADMIN.'judging_locations.admin.php');
if ($go == "judging_preferences") 	    include (ADMIN.'judging_preferences.admin.php');
if ($go == "judging_tables") 	    	include (ADMIN.'judging_tables.admin.php');
if ($go == "judging_flights") 	    	include (ADMIN.'judging_flights.admin.php');
if ($go == "judging_scores") 	    	include (ADMIN.'judging_scores.admin.php');
if ($go == "judging_scores_bos")    	include (ADMIN.'judging_scores_bos.admin.php');
if ($go == "participants") 				include (ADMIN.'participants.admin.php');
if ($go == "entries") 					include (ADMIN.'entries.admin.php');
if ($go == "make_admin") 				include (ADMIN.'make_admin.admin.php');
if ($go == "archive") 	    			include (ADMIN.'archive.admin.php');
if ($go == "sponsors") 	   			 	include (ADMIN.'sponsors.admin.php');
if ($go == "contacts") 	    			include (ADMIN.'contacts.admin.php');
if ($go == "styles") 	    			include (ADMIN.'styles.admin.php');
if ($go == "style_types")    			include (ADMIN.'style_types.admin.php');
if ($go == "dropoff") 	    			include (ADMIN.'dropoff.admin.php');

}
else echo "<div class=\"error\">You do not have sufficient privileges to access this area.</div>";
?>
