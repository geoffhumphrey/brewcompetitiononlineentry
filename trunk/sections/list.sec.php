<?php 
/**
 * Module:      list.sec.php 
 * Description: This module displays user-related data including personal information,
 *              judging/stewarding information, and the participant's associated entries. 
 * 
 */

function pay_to_print($prefs_pay,$entry_paid) { 
	if (($prefs_pay == "Y") && ($entry_paid == "1")) return TRUE;
	elseif (($prefs_pay == "Y") && ($entry_paid == "0")) return FALSE;
	elseif ($prefs_pay == "N") return TRUE;
}

if ($_SESSION["loginUsername"] != $row_user['user_name']) { ?>
<p>Please <a href="<?php echo build_public_url("login","default","default",$sef,$base_url); ?>">log in</a> or <a href="<?php echo build_public_url("register","default","default",$sef,$base_url); ?>">register</a> to view your list of brews entered into the <?php echo $row_contest_info['contestName']; ?> organized by <?php echo $row_contest_info['contestHost']; ?>, <?php echo $row_contest_info['contestHostLocation']; ?>.</p> 
<?php } 
else 
{ 
$total_not_paid = total_not_paid_brewer($row_user['id']);
include(DB.'judging_locations.db.php');
if (($action != "print") && ($msg != "default")) echo $msg_output;
if ($row_prefs['prefsUseMods'] == "Y") include(INCLUDES.'mods_top.inc.php');
?>
<?php if ($action != "print") { ?>
<p><span class="icon"><img src="<?php echo $base_url; ?>images/help.png"  /></span><a id="modal_window_link" href="http://help.brewcompetition.com/files/my_info.html" title="BCOE&amp;M Help: My Info and Entries">My Info and Entries Help</a></p>
<?php } ?>
<p>Thank you for entering the <?php echo $row_contest_info['contestName'].", ".$row_name['brewerFirstName']."."; if (($totalRows_log > 0) && ($action != "print")) { ?> <a href="#list">View your entries</a>.<?php } ?></p>
<h2>Info</h2>
<?php 
if ($action != "print") { ?>

<div class="adminSubNavContainer">
	<span class="adminSubNav">
        <span class="icon"><img src="<?php echo $base_url; ?>images/user_edit.png" /></span><a href="<?php echo $base_url; ?>index.php?<?php if ($row_brewer['id'] != "") echo "section=brewer&amp;action=edit&amp;id=".$row_brewer['id']; else echo "action=add&amp;section=brewer&amp;go=judge"; ?>">Edit Your Info</a>
    </span>
    <?php if (!NHC) { ?>
    <span class="adminSubNav">
        <span class="icon"><img src="<?php echo $base_url; ?>images/email_edit.png" /></span><a href="<?php echo $base_url; ?>index.php?section=user&amp;action=username&amp;id=<?php echo $row_user['id']; ?>">Change Your Email Address</a>
    </span>
	<?php } ?>
    <span class="adminSubNav">
        <span class="icon"><img src="<?php echo $base_url; ?>images/key.png" /></span><a href="<?php echo $base_url; ?>index.php?section=user&amp;action=password&amp;id=<?php echo $row_user['id']; ?>">Change Your Password</a>
    </span>
</div>
<?php } 
//echo "Query: ".$query_brewer;
?>
<table class="dataTableCompact">
  	<tr>
    	<td class="dataLabel" width="5%">Name:</td>
    	<td class="data"><?php echo $row_brewer['brewerFirstName']." ".$row_brewer['brewerLastName']; ?></td>
  	</tr>
  	<tr>
   	 	<td class="dataLabel">Email:</td>
   	 	<td class="data"><?php echo $row_brewer['brewerEmail']; ?></td>
  	</tr>
  	<tr>
    	<td class="dataLabel">Address:</td>
    	<td class="data"><?php if ($row_brewer['brewerAddress'] != "") echo $row_brewer['brewerAddress']; else echo "None entered"; ?></td>
  	</tr>
  	<tr>
  	  <td class="dataLabel">City:</td>
  	  <td class="data"><?php if ($row_brewer['brewerCity'] != "") echo $row_brewer['brewerCity']; else echo "None entered"; ?></td>
  </tr>
  	<tr>
  	  <td class="dataLabel">State or Province:</td>
  	  <td class="data"><?php if ($row_brewer['brewerState'] != "") echo $row_brewer['brewerState']; else echo "None entered"; ?></td>
  </tr>
  	<tr>
  	  <td class="dataLabel">Zip or Postal Code:</td>
  	  <td class="data"><?php if ($row_brewer['brewerZip'] != "") echo $row_brewer['brewerZip']; else echo "None entered"; ?></td>
  </tr>
  	<tr>
  	  <td class="dataLabel">Country:</td>
  	  <td class="data"><?php if ($row_brewer['brewerCountry'] != "") echo $row_brewer['brewerCountry']; else echo "None entered"; ?></td>
  </tr>
  	<tr>
    	<td class="dataLabel">Phone Number(s):</td>
    	<td class="data"><?php  if ($row_brewer['brewerPhone1'] != "") echo $row_brewer['brewerPhone1']." (1) ";  if ($row_brewer['brewerPhone2'] != "") echo "<br>".$row_brewer['brewerPhone2']." (2)"; ?></td>
  	</tr>
  	<tr>
  	  <td class="dataLabel">Drop Off Location:</td>
  	  <td class="data"><?php echo dropoff_location($row_brewer['brewerDropOff']); ?></td>
  </tr>
  	<tr>
    	<td class="dataLabel">Club:</td>
    	<td class="data"><?php if ($row_brewer['brewerClubs'] != "") echo $row_brewer['brewerClubs']; else echo "None entered"; ?></td>
  	</tr>
    <?php if (($row_brewer['brewerDiscount'] == "Y") && ($row_contest_info['contestEntryFeePasswordNum'] != "")) { ?>
    <tr>
    	<td class="dataLabel">Entry Fee Discount:</td>
    	<td class="data">Yes (<?php echo $row_prefs['prefsCurrency'].$row_contest_info['contestEntryFeePasswordNum']; ?> per entry)</td>
  	</tr>
    <?php } ?>
  	<tr>
    	<td class="dataLabel">AHA Member Number:</td>
    	<td class="data"><?php if ($row_brewer['brewerAHA'] < "999999994") echo $row_brewer['brewerAHA']; elseif ($row_brewer['brewerAHA'] >= "999999994") echo "Pending"; if ($row_brewer['brewerAHA'] == "") echo "Not provided"; ?></td>
  	</tr>
  	<?php if ($row_brewer['brewerAssignment'] == "") { ?>
  	<tr>
    	<td class="dataLabel">Judge?</td>
    	<td class="data">
		<?php if ($row_brewer['brewerJudge'] != "") echo  $row_brewer['brewerJudge']; else echo "None entered"; ?>
    	</td>
  	</tr>
  	<?php 
	if (($row_brewer['brewerJudge'] == "Y") && ($totalRows_judging3 > 1)) { ?>
  	<tr>
    	<td class="dataLabel">Judging Availability:</td>
    	<td>
        <script type="text/javascript" language="javascript">
			 $(document).ready(function() {
				$('#sortable_judge').dataTable( {
					"bPaginate" : false,
					"sDom": 'rt',
					"bStateSave" : false,
					"bLengthChange" : false,
					"aaSorting": [[2,'asc']],
					"aoColumns": [
						null,
						null,
						null
						]
					} );
				} );
		</script> 
    	<table id="sortable_judge" class="dataTable" style="width:50%;float:left;">
        <thead>
        <tr>
        	<th class="dataHeading bdr1B" width="10%">Yes/No</th>
            <th class="dataHeading bdr1B" width="35%">Location</th>
            <th class="dataHeading bdr1B">Date/Time</th>
        </tr>
        </thead>
        <tbody>
		<?php 
		$a = explode(",",$row_brewer['brewerJudgeLocation']);
		arsort($a);
		foreach ($a as $value) {
			if ($value != "0-0") {
				$b = substr($value, 2);
				$query_judging_loc3 = sprintf("SELECT judgingLocName,judgingDate,judgingLocation,judgingTime FROM $judging_locations_db_table WHERE id='%s'", $b);
				$judging_loc3 = mysql_query($query_judging_loc3, $brewing) or die(mysql_error());
				$row_judging_loc3 = mysql_fetch_assoc($judging_loc3);
				echo "<tr>\n";
				if ($action == "print") echo "<td class='dataList bdr1B'>"; else echo "<td class='dataList'>";
				echo substr($value, 0, 1);
				echo "</td>\n";
				if ($action == "print") echo "<td class='dataList bdr1B'>"; else echo "<td class='dataList'>";
				echo $row_judging_loc3['judgingLocName'];
				echo "</td>\n";
				if ($action == "print") echo "<td class='dataList bdr1B'>"; else echo "<td class='dataList'>";
				echo getTimeZoneDateTime($row_prefs['prefsTimeZone'], $row_judging_loc3['judgingDate'], $row_prefs['prefsDateFormat'],  $row_prefs['prefsTimeFormat'], "long", "date-time");
				echo "</td>\n";
				echo "</tr>";
				}
			else echo "";
			}
		?>
        </tbody>
    	</table>
    	</td>
  	</tr>
  	<?php } ?>
  	<?php } else { ?>
  	<tr>
    	<td class="dataLabel">Assigned As:</td>
		<td class="data"><?php if ($row_brewer['brewerAssignment'] == "J") echo "Judge"; elseif ($row_brewer['brewerAssignment'] == "S") echo "Steward"; else echo "Not Assigned"; ?></td>
  	</tr>
    <?php if (($row_brewer['brewerAssignment'] == "J") && ($action != "print")) { ?>
    <tr>
    	<td class="dataLabel">&nbsp;</td>
		<td class="data"><span class="icon"><img src="<?php echo $base_url; ?>images/page_white_acrobat.png"  border="0" alt="Print your judging scoresheet labels" title="Judging scoresheet labels"></span><a href="<?php echo $base_url; ?>output/labels.php?section=admin&amp;go=participants&amp;action=judging_labels&amp;id=<?php echo $row_brewer['id']; ?>">Print Judging Scoresheet Labels</a></span><span class="data">(Avery 5160 PDF Download)</td>
  	</tr>
  	<?php } ?>
	<?php if ($totalRows_judging3 > 1) { ?>
  	<tr>
    	<td class="dataLabel">Location<?php if ($totalRows_judging > 1) echo "(s)"; ?>:</td>
    	<td>
    	<?php echo table_assignments($row_user['id'],"J",$row_prefs['prefsTimeZone'],$row_prefs['prefsDateFormat'],$row_prefs['prefsTimeFormat']); ?>
    	</td>
  	</tr>
  	<?php } ?>
  	<?php } ?>
  	<?php if ($row_brewer['brewerJudge'] == "Y") { ?>
  	<tr>
    	<td class="dataLabel">BJCP Judge ID:</td>
    	<td class="data"><?php  if ($row_brewer['brewerJudgeID'] != "0") echo $row_brewer['brewerJudgeID']; else echo "N/A"; ?></td>
	</tr>
    <tr>
      <td width="10%" class="dataLabel">Mead Judge Endorsement:</td>
      <td colspan="2" class="data"><?php echo $row_brewer['brewerJudgeMead']; ?></td>
    </tr>
  	<tr>
    	<td class="dataLabel">BJCP Judge Rank:</td>
   	 <td class="data"><?php  if ($row_brewer['brewerJudgeRank'] != "") echo  $row_brewer['brewerJudgeRank']; else echo "N/A"; ?></td>
  	</tr>
  	<tr>
    	<td class="dataLabel">Categories Preferred:</td>
    	<td class="data">
		<?php 
		if ($row_brewer['brewerJudgeLikes'] != "") echo rtrim(display_array_content(style_convert($row_brewer['brewerJudgeLikes'],4),2),", ");
	    else echo "N/A";		
		?>
        </td>
  	</tr>
  	<tr>
    	<td class="dataLabel">Categories Not Preferred:</td>
    	<td class="data">
        <?php 
		if ($row_brewer['brewerJudgeDislikes'] != "") echo rtrim(display_array_content(style_convert($row_brewer['brewerJudgeDislikes'],4),2),", "); 
	    else echo "N/A";		
		?>
        </td>
  	</tr>
  	<?php } ?>
  	<?php if ($row_brewer['brewerAssignment'] == "") { ?>
  	<tr>
    	<td class="dataLabel">Steward?</td>
    	<td class="data"><?php if ($row_brewer['brewerSteward'] != "") echo $row_brewer['brewerSteward']; else echo "None entered"; ?></td>
  	</tr>
  	<?php  if (($row_brewer['brewerSteward'] == "Y") && ($totalRows_judging3 > 1)) { ?>
  	<tr>
    	<td class="dataLabel">Stewarding Availability:</td>
    	<td>
    	<script type="text/javascript" language="javascript">
			 $(document).ready(function() {
				$('#sortable_steward').dataTable( {
					"bPaginate" : false,
					"sDom": 'rt',
					"bStateSave" : false,
					"bLengthChange" : false,
					"aaSorting": [[2,'asc']],
					"aoColumns": [
						null,
						null,
						null
						]
					} );
				} );
		</script>
        <table id="sortable_steward" class="dataTable"  style="width:50%;float:left;">
        <thead>
        <tr>
        	<th class="dataHeading bdr1B" width="10%">Yes/No</th>
            <th class="dataHeading bdr1B" width="35%">Location</th>
            <th class="dataHeading bdr1B">Date/Time</th>
        </tr>
        </thead>
        <tbody>
		<?php 
		$a = explode(",",$row_brewer['brewerStewardLocation']);
		arsort($a);
		foreach ($a as $value) {
			if ($value != "0-0") {
				$b = substr($value, 2);
				$query_judging_loc3 = sprintf("SELECT judgingLocName,judgingDate,judgingLocation,judgingTime FROM $judging_locations_db_table WHERE id='%s'", $b);
				$judging_loc3 = mysql_query($query_judging_loc3, $brewing) or die(mysql_error());
				$row_judging_loc3 = mysql_fetch_assoc($judging_loc3);
				echo "<tr>\n";
				if ($action == "print") echo "<td class='dataList bdr1B'>"; else echo "<td class='dataList'>";
				echo substr($value, 0, 1);
				echo "</td>\n";
				if ($action == "print") echo "<td class='dataList bdr1B'>"; else echo "<td class='dataList'>";
				echo $row_judging_loc3['judgingLocName'];
				echo "</td>\n";
				if ($action == "print") echo "<td class='dataList bdr1B'>"; else echo "<td class='dataList'>";
				echo getTimeZoneDateTime($row_prefs['prefsTimeZone'], $row_judging_loc3['judgingDate'], $row_prefs['prefsDateFormat'],  $row_prefs['prefsTimeFormat'], "long", "date-time");
				echo "</td>\n";
				echo "</tr>";
				}
			else echo "";
			}
		?>
        </tbody>
    	</table>
    	</td>
  </tr>
  <?php } ?>
  <?php } ?>
</table>
<?php } ?>
<h2>Entries</h2>
<?php if (($totalRows_log > 0) && ($registration_open > 0)) { 
if (entries_unconfirmed($row_user['id']) > 0) { echo "<div class='error'>You have unconfirmed entries. For each unconfirmed entry below marked in yellow and with a <span class='icon'><img src='".$base_url."images/exclamation.png'></span> icon, click \"Edit\" to review and confirm all your entry data. Unconfirmed entries will be deleted automatically after 24 hours."; if ($row_prefs['prefsPayToPrint'] == "Y") echo " You CANNOT pay for your entries until all entries are confirmed."; echo "</div>"; }

if (entries_no_special($row_user['id']) > 0) echo "<div class='error2'>You have entries that require you to define special ingredients. For each entry below marked in orange and with a <span class='icon'><img src='".$base_url."images/exclamation.png'></span> icon, click \"Edit\" to add your special ingredients. Entries without special ingredients in categories that require them will be deleted automatically after 24 hours.</div>";
?>
<div class="adminSubNavContainer">
	<span class="adminSubNav">
    	<span class="icon"><img src="<?php echo $base_url; ?>images/information.png" alt="Info" title="Info" /></span><?php echo $row_name['brewerFirstName']; ?>, you have <?php echo readable_number($totalRows_log); if ($totalRows_log == 1) echo " entry"; else echo " entries"; ?>, listed below.
    </span>
</div>
<?php } ?>
<?php if ($action != "print") { ?>
<?php if (($registration_open == "1") && (!open_limit($totalRows_entry_count,$row_prefs['prefsEntryLimit'],$registration_open)))  { ?>
<?php if (($row_prefs['prefsUserEntryLimit'] != "") && ($registration_open == "1")) { ?>
<div class="adminSubNavContainer">
	<span class="adminSubNav">
        <span class="icon"><img src="<?php echo $base_url; ?>images/information.png"  border="0" alt="Entry Limit" title="Entry Limit" /></span><?php if ($remaining_entries > 0) { ?>You have <strong><?php echo readable_number($remaining_entries)." (".$remaining_entries.")"; ?></strong> <?php if ($remaining_entries == 1) echo "entry"; else echo "entries"; ?> left before you reach the limit of <?php echo readable_number($row_prefs['prefsUserEntryLimit'])." (".$row_prefs['prefsUserEntryLimit'].")"; if ($row_prefs['prefsUserEntryLimit'] > 1) echo " entries"; else echo " entry"; ?> per participant in this competition.<?php } if ($totalRows_log == $row_prefs['prefsUserEntryLimit']) { ?><strong>You have reached the limit of <?php echo readable_number($row_prefs['prefsUserEntryLimit'])." (".$row_prefs['prefsUserEntryLimit'].")"; if ($row_prefs['prefsUserEntryLimit'] > 1) echo " entries"; else echo " entry"; ?>  per participant in this competition.</strong><?php } ?>
	</span>
</div>
<?php } if ($remaining_entries > 0) { ?>
<div class="adminSubNavContainer">
   	<span class="adminSubNav">
        <span class="icon"><img src="<?php echo $base_url; ?>images/book_add.png"  border="0" alt="Add" title="Add" /></span><a href="<?php if ($row_user['userLevel'] <= "1") echo "index.php?section=brew&amp;go=entries&amp;action=add&amp;filter=admin"; else echo "index.php?section=brew&amp;action=add"; ?>">Add an Entry</a>
   	</span>
    <?php if ($row_prefs['prefsHideRecipe'] == "N") { ?>
    <span class="adminSubNav">
        <span class="icon"><img src="<?php echo $base_url; ?>images/page_code.png"  border="0"  alt="BeerXML" title="BeerXML" /></span><a href="<?php echo build_public_url("beerxml","default","default",$sef,$base_url); ?>">Import Entries Using BeerXML</a>
   	</span>
    <?php } ?>
</div>
<?php } // end  if ($remaining_entries > 0) ?>
<?php } ?>
<div class="adminSubNavContainer">
    <span class="adminSubNav">
        <span class="icon"><img src="<?php echo $base_url; ?>images/printer.png" border="0" alt="Print" /></span><a id="modal_window_link" href="<?php echo $base_url; ?>output/print.php?section=list&amp;action=print" title="Print Your List of Entries and Info">Print Your List of Entries and Info</a>
	</span>
</div>
<?php if (($totalRows_log > 0) && ($registration_open > 0)) { 
$total_entry_fees = total_fees($row_contest_info['contestEntryFee'], $row_contest_info['contestEntryFee2'], $row_contest_info['contestEntryFeeDiscount'], $row_contest_info['contestEntryFeeDiscountNum'], $row_contest_info['contestEntryCap'], $row_contest_info['contestEntryFeePasswordNum'], $row_brewer['uid'], $filter);
$total_paid_entry_fees = total_fees_paid($row_contest_info['contestEntryFee'], $row_contest_info['contestEntryFee2'], $row_contest_info['contestEntryFeeDiscount'], $row_contest_info['contestEntryFeeDiscountNum'], $row_contest_info['contestEntryCap'], $row_contest_info['contestEntryFeePasswordNum'], $row_brewer['uid'], $filter);
$total_to_pay = $total_entry_fees - $total_paid_entry_fees; 
?>
<div class="adminSubNavContainer">
	<span class="adminSubNav">
		<span class="icon"><img src="<?php echo $base_url; ?>images/money.png"  border="0" alt="Entry Fees" title="Entry Fees"></span>You currently have <?php echo readable_number($total_not_paid); ?> <strong>unpaid</strong> <?php if ($total_not_paid == "1") echo "entry. "; else echo "entries. "; ?> Your total entry fees are <?php echo $row_prefs['prefsCurrency'].$total_entry_fees; if ((NHC) && ($row_brewer['brewerDiscount'] != "Y")) echo " (as a non-AHA member)"; echo ". You need to pay ".$row_prefs['prefsCurrency'].$total_to_pay."."; ?>
	</span>
    <?php if (($row_brewer['brewerDiscount'] == "Y") && ($row_contest_info['contestEntryFeePasswordNum'] != "")) { ?>
	<span class="adminSubNav">
		<span class="icon"><img src="<?php echo $base_url; ?>images/star.png"  border="0" alt="Discounted!" title="Discounted Entry Fees"></span><?php if (NHC) echo "As an AHA member, your entry fees are "; else echo "Your fees have been discounted to "; echo $row_prefs['prefsCurrency'].$row_contest_info['contestEntryFeePasswordNum']; ?> per entry.
	</span>
	<?php } ?>
</div>
<div class="adminSubNavContainer">
   	<span class="adminSubNav">
        <?php if (($total_not_paid > 0) && ($row_contest_info['contestEntryFee'] > 0)) { ?>
        <?php if (($row_prefs['prefsPayToPrint'] == "Y") && ($totalRows_log_confirmed == $totalRows_log)) { ?>
        <span class="icon"><img src="<?php echo $base_url; ?>images/exclamation.png" border="0" alt="Entry Fees" title="Entry Fees"></span><a href="<?php echo build_public_url("pay","default","default",$sef,$base_url); ?>">Pay Your Fees</a><?php if ($row_prefs['prefsPayToPrint'] == "Y") echo " <em>** Please note that you will not be able to print your bottle labels and entry forms until you pay for your entries.</em>"; ?>
        <?php } ?>
        <?php if (($row_prefs['prefsPayToPrint'] == "Y") && ($totalRows_log_confirmed != $totalRows_log)) { ?>
        <span class="icon"><img src="<?php echo $base_url; ?>images/exclamation.png" border="0" alt="Entry Fees" title="Entry Fees"></span><span class="red">You have unconfirmed entries. <strong>You cannot pay for your entries until ALL are confirmed</strong>.</span> Confirm each entry by clicking its corresponding &ldquo;Edit&rdquo; link.
        <?php } ?>
		<?php } elseif ($totalRows_log == 0) echo ""; else { ?>
        <span class="icon"><img src="<?php echo $base_url; ?>images/thumb_up.png"  border="0" alt="Entry Fees" title="Entry Fees"></span>Your fees have been paid. Thank you!
		<?php } ?>
    </span>
</div>
<?php if (NHC) { ?>
<div class="adminSubNavContainer">
   	<span class="adminSubNav">
    	<span class="icon"><img src="<?php echo $base_url; ?>images/exclamation.png" border="0" alt="NHC Paid" title="NHC Paid"></span>Your entries are not completely entered until they have been confirmed and entry fees have been paid.  Entries not paid within 24 hours of registration will be deleted from the competition database.
    </span>
</div>
<?php } ?>
<?php } // end if ((judging_date_return() > 0) && ($totalRows_log > 0)) ?>
<?php } // end if ($action != "print")
if (($totalRows_log > 0) && ($registration_open > 0)) { ?>

<script type="text/javascript">
var win;

function popUp(w,h,page)
{
   //get the width and height of the window
    var wide = w;
    var high = h;
    
    //this gets the total available width and height of the users screen
    screen_height = window.screen.availHeight;
    screen_width = window.screen.availWidth; 

    //this gets the left and top point by saying total width of the screen divided by 2 (center), minus the width of your window divided by 2, which make its center point the middle of the screen. Same for the top
    left_point = parseInt(screen_width/2)-(wide/2); 
    top_point = parseInt(screen_height/2)-(high/2); 

    //just doing a simple popup window here, but plugging your info into it, and setting the top and left corners to be our calculated points that will center your window.
    win = window.open(page, 'win', 'width='+wide+',height='+high+',left='+left_point+',top='+top_point+',toolbar=no,location=no,scrollbars=yes,status=no,resizable=yes,fullscreen=no');     //make sure your window is in the front 
    win.focus(); 
}
</script>



<script type="text/javascript" language="javascript">
	 $(document).ready(function() {
		$('#sortable').dataTable( {
			"bPaginate" : false,
			"sDom": 'rt',
			"bStateSave" : false,
			"bLengthChange" : false,
			"aaSorting": [[0,'asc']],
			"aoColumns": [
				null,
				null,
				null,
				null,
				null,
				null<?php if ($action != "print") { ?>,
				<?php if ((judging_date_return() == 0) && ($row_prefs['prefsDisplayWinners'] == "Y")) { ?>
				null,
				null,
				null,
				<?php } ?>
				<?php if (($registration_open == "1") || (($registration_open == "2") && (judging_date_return() > 0))) { ?>
				{ "asSorting": [  ] }
				<?php } ?>
  				<?php } ?>
				]
			} );
		} );
</script>
<a name="list"></a>
<table class="dataTable" id="sortable">
<thead>
 <tr>
  	<th class="dataHeading bdr1B" width="5%">Entry #</th>
  	<th class="dataHeading bdr1B" width="20%">Entry Name</th>
  	<th class="dataHeading bdr1B" width="20%">Style</th>
  	<th class="dataHeading bdr1B" width="8%">Confirmed?</th>
  	<th class="dataHeading bdr1B" width="8%">Paid?</th> 
    <th class="dataHeading bdr1B" width="12%">Updated</th>
  	<?php if ((judging_date_return() == 0) && ($row_prefs['prefsDisplayWinners'] == "Y")) { ?>
  	<th class="dataHeading bdr1B" width="8%">Score</th>
    <th class="dataHeading bdr1B" width="8%">Mini-BOS?</th>
  	<th class="dataHeading bdr1B">Winner?</th>
  	<?php } ?>
    <?php if ($action != "print") { ?>
    <?php if (($registration_open == "1") || (($registration_open == "2") && (judging_date_return() > 0))) { ?>
  	<th class="dataHeading bdr1B">Actions</th>
    <?php } ?>
  	<?php } ?>
 </tr>
</thead>
<tbody>
 <?php do { 
	mysql_select_db($database, $brewing);
	if ($row_log['brewCategory'] < 10) $fix = "0"; else $fix = "";
	$entry_style = $row_log['brewCategorySort'].$row_log['brewSubCategory'];
	$query_style = sprintf("SELECT * FROM $styles_db_table WHERE brewStyleGroup = '%s' AND brewStyleNum = '%s'", $fix.$row_log['brewCategory'], $row_log['brewSubCategory']);
	$style = mysql_query($query_style, $brewing) or die(mysql_error());
	$row_style = mysql_fetch_assoc($style);
	$totalRows_style = mysql_num_rows($style);
	?>
 <tr<?php if (($row_log['brewConfirmed'] == "0") && ($action != "print")) echo " style='background-color: #fc3; border-top: 1px solid #F90; border-bottom: 1px solid #F90;'"; if ((style_convert($entry_style,"3") == TRUE) && ($row_log['brewInfo'] == "") && ($action != "print")) echo " style='background-color: #f90; border-top: 1px solid #FF6600; border-bottom: 1px solid #FF6600;'"; ?>>
 	<td class="dataList<?php if ($action == "print") echo " bdr1B"; ?>">
		<?php echo sprintf("%04s",$row_log['id']); ?>
    </td>
  	<td class="dataList<?php if ($action == "print") echo " bdr1B"; ?>">
		<?php echo $row_log['brewName']; if ($row_log['brewCoBrewer'] != "") echo "<br><em>Co-Brewer: ".$row_log['brewCoBrewer']."</em>"; ?>
    </td>
  	<td class="dataList<?php if ($action == "print") echo " bdr1B"; ?>">
		<?php if ($row_style['brewStyleActive'] == "Y") echo $row_log['brewCategorySort'].$row_log['brewSubCategory'].": ".$row_style['brewStyle']; else echo "<span class='required'>Style entered NOT accepted - Please change</span>"; ?>
    </td>
    <td class="dataList<?php if ($action == "print") echo " bdr1B"; ?>">
	<?php if ($row_log['brewConfirmed'] == "0") { 
	if ($action != "print") echo " <span class='icon'><img src='".$base_url."images/exclamation.png'  border='0' alt='Unconfirmed entry!' title='Unconfirmed entry! Click Edit to review and confirm the entry data.'></span>"; else echo "Y";
	} 
	
	elseif ((style_convert($entry_style,"3") == TRUE) && ($row_log['brewInfo'] == "")) { 
	if ($action != "print") echo " <span class='icon'><img src='".$base_url."images/exclamation.png'  border='0' alt='Unconfirmed entry!' title='Unconfirmed entry! Click Edit to review and confirm the entry data.'></span>"; else echo "Y";
	} 
	
	else { 
	if ($action != "print") echo " <span class='icon'><img src='".$base_url."images/tick.png'  border='0' alt='Confirmed Entry!' title='Confirmed entry.'></span>"; else echo "Y";
	} ?>
    </td>
  	<td class="dataList<?php if ($action == "print") echo " bdr1B"; ?>">
		<?php if ($row_log['brewPaid'] == "1")  { if ($action != "print") echo "<img src='".$base_url."images/tick.png'>"; else echo "Y"; } else { if ($action != "print") echo "<img src='".$base_url."images/cross.png'>"; else echo "N"; } ?>
    </td>
    <td class="dataList<?php if ($action == "print") echo " bdr1B"; ?>"><?php if ($row_log['brewUpdated'] != "") echo getTimeZoneDateTime($row_prefs['prefsTimeZone'], strtotime($row_log['brewUpdated']), $row_prefs['prefsDateFormat'],  $row_prefs['prefsTimeFormat'], "short", "date-time-no-gmt"); else echo "&nbsp;"; ?></td>
  	<?php if ((judging_date_return() == 0) && ($row_prefs['prefsDisplayWinners'] == "Y")) { ?>
  	<td class="dataList<?php if ($action == "print") echo " bdr1B"; ?>"><?php echo score_check($row_log['id'],$judging_scores_db_table); ?></td>
    <td class="dataList<?php if ($action == "print") echo " bdr1B"; ?>"><?php if (minibos_check($row_log['id'],$judging_scores_db_table)) { if ($action != "print") echo "<img src='".$base_url."images/tick.png'>"; else echo "Y"; }?></td>
    <td class="dataList<?php if ($action == "print") echo " bdr1B"; ?>"><?php if ($action == "print") echo winner_check($row_log['id'],$judging_scores_db_table,$judging_tables_db_table,$brewing_db_table,$row_prefs['prefsWinnerMethod']); else echo winner_check($row_log['id'],$judging_scores_db_table,$judging_tables_db_table,$brewing_db_table,$row_prefs['prefsWinnerMethod']); ?></td>
    <?php } if ($action != "print") { ?>
    <?php if (($registration_open == "1") || (($registration_open == "2") && (judging_date_return() > 0))) {  ?>
  	<td class="dataList<?php if ($action == "print") echo " bdr1B"; ?>" nowrap="nowrap"><span class="icon"><img src="<?php echo $base_url; ?>images/pencil.png"  border="0" alt="Edit <?php echo $row_log['brewName']; ?>" title="Edit <?php echo $row_log['brewName']; ?>"></span><a href="<?php echo $base_url; ?>index.php?section=brew&amp;action=edit&amp;id=<?php echo $row_log['id']; if ($row_log['brewConfirmed'] == 0) echo "&amp;msg=1-".$row_log['brewCategory']."-".$row_log['brewSubCategory']; else echo "&amp;view=".$row_log['brewCategory']."-".$row_log['brewSubCategory']; ?>" title="Edit <?php echo $row_log['brewName']; ?>">Edit</a>&nbsp;&nbsp;
	<?php if ((NHC) && ($row_log['brewPaid'] != 1)) { ?>
  		<span class="icon"><img src="<?php echo $base_url; ?>images/bin_closed.png"  border="0" alt="Delete <?php echo $row_log['brewName']; ?>" title="Delete <?php echo $row_log['brewName']; ?>?"></span><a href="javascript:DelWithCon('includes/process.inc.php?section=<?php echo $section; ?>&amp;dbTable=<?php echo $brewing_db_table; ?>&amp;action=delete','id',<?php echo $row_log['id']; ?>,'Are you sure you want to delete your entry called <?php echo str_replace("'", "\'", $row_log['brewName']); ?>? This cannot be undone.');" title="Delete <?php echo $row_log['brewName']; ?>?">Delete</a>&nbsp;&nbsp;
	<?php } if (pay_to_print($row_prefs['prefsPayToPrint'],$row_log['brewPaid']) && (!NHC)) { ?>
    	<span class="icon"><img src="<?php echo $base_url; ?>images/printer.png"  border="0" alt="Print Entry Forms and Bottle Labels for <?php echo $row_log['brewName']; ?>" title="Print Entry Forms and Bottle Labels for <?php echo $row_log['brewName']; ?>"></span><a id="modal_window_link" href="<?php echo $base_url; ?>output/entry.php?id=<?php echo $row_log['id']; ?>&amp;bid=<?php echo $row_brewer['uid']; ?>" title="Print Entry Forms and Bottle Labels for <?php echo $row_log['brewName']; ?>">Print Entry Forms and Bottle Labels</a>
	<?php } ?>
    
    <?php if ((pay_to_print($row_prefs['prefsPayToPrint'],$row_log['brewPaid'])) && (NHC)) { ?>
  		<span class="icon"><img src="<?php echo $base_url; ?>images/printer.png"  border="0" alt="Print Entry Forms and Bottle Labels" title="Print Entry Forms and Bottle Labels"></span><a href="javascript:popUp('1024','768','<?php echo $base_url; ?>output/entry.php?id=<?php echo $row_log['id']; ?>&amp;bid=<?php echo $row_brewer['uid']; ?>')" title="Print Entry Forms and Bottle Labels">Print Entry Forms and Bottle Labels</a>
  	<?php } ?>
    
    
  </td>
  <?php } ?>
  <?php } ?>
 </tr>
<?php } while ($row_log = mysql_fetch_assoc($log)); ?>
</tbody>
</table>
<?php 
} else { echo "<p>You do not have any entries.</p>"; if ($registration_open == "0") echo "<p>You can add your entries on or after $reg_open.</p>"; } 

if ($row_prefs['prefsUseMods'] == "Y") include(INCLUDES.'mods_bottom.inc.php');
?>