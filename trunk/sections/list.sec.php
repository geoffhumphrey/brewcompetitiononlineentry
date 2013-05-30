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
 
if (NHC) {
	function certificate_type($score) {
		if (($score >= 25) && ($score <= 29.9)) $return = "Bronze Certificate";
		elseif (($score >= 30) && ($score <= 37.9))	$return = "Silver Certificate";
		elseif (($score >= 38) && ($score <= 50))	$return = "Gold Certificate";
		return $return;
	}	
}

$judging_date = judging_date_return();
$delay = $_SESSION['prefsWinnerDelay'] * 3600;
if (!isset($_SESSION['loginUsername'])) { ?>
<p>Please <a href="<?php echo build_public_url("login","default","default",$sef,$base_url); ?>">log in</a> or <a href="<?php echo build_public_url("register","default","default",$sef,$base_url); ?>">register</a> to view your list of brews entered into the <?php echo $_SESSION['contestName']; ?> organized by <?php echo $_SESSION['contestHost']; ?>, <?php echo $_SESSION['contestHostLocation']; ?>.</p> 
<?php } 
else 
{ 
$total_not_paid = total_not_paid_brewer($_SESSION['user_id']);
include(DB.'judging_locations.db.php');
if (($action != "print") && ($msg != "default")) echo $msg_output;
if ($_SESSION['prefsUseMods'] == "Y") include(INCLUDES.'mods_top.inc.php');
?>
<?php if ($action != "print") { ?>
<p><span class="icon"><img src="<?php echo $base_url; ?>images/help.png"  /></span><a id="modal_window_link" href="http://help.brewcompetition.com/files/my_info.html" title="BCOE&amp;M Help: My Info and Entries">My Info and Entries Help</a></p>
<?php } ?>
<p>Thank you for entering the <?php echo $_SESSION['contestName'].", ".$_SESSION['brewerFirstName']."."; if (($totalRows_log > 0) && ($action != "print")) { ?> <a href="#list">View your entries</a>.<?php } ?></p>
<?php if ((NHC) && ($prefix == "final_") && ($action != "print") && ($totalRows_log > 0)) include (MODS.'nhc_final_round_instructions.php'); ?>
<h2>Info</h2>
<?php 
if ($action != "print") { ?>
<div class="adminSubNavContainer">
	<span class="adminSubNav">
        <span class="icon"><img src="<?php echo $base_url; ?>images/user_edit.png" /></span><a href="<?php echo $base_url; ?>index.php?<?php if ($_SESSION['brewerID'] != "") echo "section=brewer&amp;action=edit&amp;id=".$_SESSION['brewerID']; else echo "action=add&amp;section=brewer&amp;go=judge"; ?>">Edit My Info</a>
    </span>
    <?php if (!NHC) { ?>
    <span class="adminSubNav">
        <span class="icon"><img src="<?php echo $base_url; ?>images/email_edit.png" /></span><a href="<?php echo $base_url; ?>index.php?section=user&amp;action=username&amp;id=<?php echo $_SESSION['user_id']; ?>">Change My Email Address</a>
    </span>
	<?php } ?>
    <span class="adminSubNav">
        <span class="icon"><img src="<?php echo $base_url; ?>images/key.png" /></span><a href="<?php echo $base_url; ?>index.php?section=user&amp;action=password&amp;id=<?php echo $_SESSION['user_id']; ?>">Change My Password</a>
    </span>
</div>
<?php } 
//echo "Query: ".$query_brewer;
?>
<table class="dataTableCompact">
  	<tr>
    	<td class="dataLabel" width="5%">Name:</td>
    	<td class="data"><?php echo $_SESSION['brewerFirstName']." ".$_SESSION['brewerLastName']; ?></td>
  	</tr>
  	<tr>
   	 	<td class="dataLabel">Email:</td>
   	 	<td class="data"><?php echo $_SESSION['brewerEmail']; ?></td>
  	</tr>
  	<tr>
    	<td class="dataLabel">Address:</td>
    	<td class="data"><?php if ($_SESSION['brewerAddress'] != "") echo $_SESSION['brewerAddress']; else echo "None entered"; ?></td>
  	</tr>
  	<tr>
  	  <td class="dataLabel">City:</td>
  	  <td class="data"><?php if ($_SESSION['brewerCity'] != "") echo $_SESSION['brewerCity']; else echo "None entered"; ?></td>
  </tr>
  	<tr>
  	  <td class="dataLabel">State or Province:</td>
  	  <td class="data"><?php if ($_SESSION['brewerState'] != "") echo $_SESSION['brewerState']; else echo "None entered"; ?></td>
  </tr>
  	<tr>
  	  <td class="dataLabel">Zip or Postal Code:</td>
  	  <td class="data"><?php if ($_SESSION['brewerZip'] != "") echo $_SESSION['brewerZip']; else echo "None entered"; ?></td>
  </tr>
  	<tr>
  	  <td class="dataLabel">Country:</td>
  	  <td class="data"><?php if ($_SESSION['brewerCountry'] != "") echo $_SESSION['brewerCountry']; else echo "None entered"; ?></td>
  </tr>
  	<tr>
    	<td class="dataLabel">Phone Number(s):</td>
    	<td class="data"><?php  if ($_SESSION['brewerPhone1'] != "") echo format_phone_us($_SESSION['brewerPhone1'])." (1) ";  if ($_SESSION['brewerPhone2'] != "") echo "<br>".format_phone_us($_SESSION['brewerPhone2'])." (2)"; ?></td>
  	</tr>
  	<tr>
  	  <td class="dataLabel">Drop Off Location:</td>
  	  <td class="data"><?php echo dropoff_location($_SESSION['brewerDropOff']); ?></td>
  </tr>
  	<tr>
    	<td class="dataLabel">Club:</td>
    	<td class="data"><?php if ($_SESSION['brewerClubs'] != "") echo $_SESSION['brewerClubs']; else echo "None entered"; ?></td>
  	</tr>
    <?php if (($_SESSION['brewerDiscount'] == "Y") && ($_SESSION['contestEntryFeePasswordNum'] != "")) { ?>
    <tr>
    	<td class="dataLabel">Entry Fee Discount:</td>
    	<td class="data">Yes (<?php echo $_SESSION['prefsCurrency'].$_SESSION['contestEntryFeePasswordNum']; ?> per entry)</td>
  	</tr>
    <?php } ?>
  	<tr>
    	<td class="dataLabel">AHA Member Number:</td>
    	<td class="data"><?php if ($_SESSION['brewerAHA'] < "999999994") echo $_SESSION['brewerAHA']; elseif ($_SESSION['brewerAHA'] >= "999999994") echo "Pending"; if ($_SESSION['brewerAHA'] == "") echo "Not provided"; ?></td>
  	</tr>
  	<?php if ($_SESSION['brewerAssignment'] == "") { ?>
  	<tr>
    	<td class="dataLabel">Judge?</td>
    	<td class="data">
		<?php if ($_SESSION['brewerJudge'] != "") echo  $_SESSION['brewerJudge']; else echo "None entered"; ?>
    	</td>
  	</tr>
  	<?php 
	if (($_SESSION['brewerJudge'] == "Y") && ($totalRows_judging3 > 1)) { ?>
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
		$a = explode(",",$_SESSION['brewerJudgeLocation']);
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
				echo getTimeZoneDateTime($_SESSION['prefsTimeZone'], $row_judging_loc3['judgingDate'], $_SESSION['prefsDateFormat'],  $_SESSION['prefsTimeFormat'], "long", "date-time");
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
  	<?php } else { 
	$brewer_assignment = brewer_assignment($_SESSION['user_id'],"1");
	if ($brewer_assignment != "") {
	?>
  	<tr>
    	<td class="dataLabel">Assigned As:</td>
		<td class="data"><?php echo $brewer_assignment; ?></td>
  	</tr>
    <?php if (strstr($brewer_assignment,"Judge") && ($action != "print")) { ?>
    <tr>
    	<td class="dataLabel">&nbsp;</td>
		<td class="data"><span class="icon"><img src="<?php echo $base_url; ?>images/page_white_acrobat.png"  border="0" alt="Print your judging scoresheet labels" title="Judging scoresheet labels"></span><a href="<?php echo $base_url; ?>output/labels.php?section=admin&amp;go=participants&amp;action=judging_labels&amp;id=<?php echo $_SESSION['brewerID']; ?>">Print Judging Scoresheet Labels</a></span><span class="data">(Avery 5160 PDF Download)</span></td>
  	</tr>
  	<?php } ?>
	<?php 
	$table_assignments = table_assignments($_SESSION['user_id'],"J",$_SESSION['prefsTimeZone'],$_SESSION['prefsDateFormat'],$_SESSION['prefsTimeFormat']);
	if (($totalRows_judging3 > 1) && ($table_assignments != "")) { ?>
  	<tr>
    	<td class="dataLabel">Location<?php if ($totalRows_judging > 1) echo "(s)"; ?>:</td>
    	<td>
    	<?php echo $table_assignments; ?>
    	</td>
  	</tr>
  	<?php } ?>
  	<?php } // end if (strstr($brewer_assignment,"Judge"))?>
    <?php } ?>
  	<?php if ($_SESSION['brewerJudge'] == "Y") { ?>
  	<tr>
    	<td class="dataLabel">BJCP Judge ID:</td>
    	<td class="data"><?php  if ($_SESSION['brewerJudgeID'] != "0") echo $_SESSION['brewerJudgeID']; else echo "N/A"; ?></td>
	</tr>
    <tr>
      <td width="10%" class="dataLabel">Mead Judge Endorsement:</td>
      <td colspan="2" class="data"><?php echo $_SESSION['brewerJudgeMead']; ?></td>
    </tr>
  	<tr>
    	<td class="dataLabel">BJCP Judge Rank:</td>
   	 <td class="data"><?php  if ($_SESSION['brewerJudgeRank'] != "") echo  $_SESSION['brewerJudgeRank']; else echo "N/A"; ?></td>
  	</tr>
  	<tr>
    	<td class="dataLabel">Categories Preferred:</td>
    	<td class="data">
		<?php 
		if ($_SESSION['brewerJudgeLikes'] != "") echo rtrim(display_array_content(style_convert($_SESSION['brewerJudgeLikes'],4),2),", ");
	    else echo "N/A";		
		?>
        </td>
  	</tr>
  	<tr>
    	<td class="dataLabel">Categories Not Preferred:</td>
    	<td class="data">
        <?php 
		if ($_SESSION['brewerJudgeDislikes'] != "") echo rtrim(display_array_content(style_convert($_SESSION['brewerJudgeDislikes'],4),2),", "); 
	    else echo "N/A";		
		?>
        </td>
  	</tr>
  	<?php } ?>
  	<?php if ($_SESSION['brewerAssignment'] == "") { ?>
  	<tr>
    	<td class="dataLabel">Steward?</td>
    	<td class="data"><?php if ($_SESSION['brewerSteward'] != "") echo $_SESSION['brewerSteward']; else echo "None entered"; ?></td>
  	</tr>
  	<?php  if (($_SESSION['brewerSteward'] == "Y") && ($totalRows_judging3 > 1)) { ?>
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
		$a = explode(",",$_SESSION['brewerStewardLocation']);
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
				echo getTimeZoneDateTime($_SESSION['prefsTimeZone'], $row_judging_loc3['judgingDate'], $_SESSION['prefsDateFormat'],  $_SESSION['prefsTimeFormat'], "long", "date-time");
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
<?php if (($totalRows_log > 0)) {
if ($action != "print") {
if (entries_unconfirmed($_SESSION['user_id']) > 0) { echo "<div class='error'>You have unconfirmed entries. For each unconfirmed entry below marked in yellow and with a <span class='icon'><img src='".$base_url."images/exclamation.png'></span> icon, click \"Edit\" to review and confirm all your entry data. Unconfirmed entries will be deleted automatically after 24 hours."; if ($_SESSION['prefsPayToPrint'] == "Y") echo " You CANNOT pay for your entries until all entries are confirmed."; echo "</div>"; }
if (entries_no_special($_SESSION['user_id']) > 0) echo "<div class='error2'>You have entries that require you to define special ingredients. For each entry below marked in orange and with a <span class='icon'><img src='".$base_url."images/exclamation.png'></span> icon, click \"Edit\" to add your special ingredients. Entries without special ingredients in categories that require them will be deleted automatically after 24 hours.</div>";
?>
<div class="adminSubNavContainer">
	<span class="adminSubNav">
    	<span class="icon"><img src="<?php echo $base_url; ?>images/information.png" alt="Info" title="Info" /></span><?php echo $_SESSION['brewerFirstName']; ?>, you have <?php echo readable_number($totalRows_log); if ($totalRows_log == 1) echo " entry"; else echo " entries"; ?>, listed below.
    </span>
</div>
<?php } } ?>
<?php if ($action != "print") { ?>
<?php if ((!open_limit($totalRows_entry_count,$row_limits['prefsEntryLimit'],$registration_open)))  { ?>
<?php if (($row_limits['prefsUserEntryLimit'] != "") && ($registration_open <= 1)) { ?>
<div class="adminSubNavContainer">
	<span class="adminSubNav">
        <span class="icon"><img src="<?php echo $base_url; ?>images/information.png"  border="0" alt="Entry Limit" title="Entry Limit" /></span><?php if ($remaining_entries > 0) { ?>You have <strong><?php echo readable_number($remaining_entries)." (".$remaining_entries.")"; ?></strong> <?php if ($remaining_entries == 1) echo "entry"; else echo "entries"; ?> left before you reach the limit of <?php echo readable_number($row_limits['prefsUserEntryLimit'])." (".$row_limits['prefsUserEntryLimit'].")"; if ($row_limits['prefsUserEntryLimit'] > 1) echo " entries"; else echo " entry"; ?> per participant in this competition.<?php } if ($totalRows_log == $row_limits['prefsUserEntryLimit']) { ?><strong>You have reached the limit of <?php echo readable_number($row_limits['prefsUserEntryLimit'])." (".$row_limits['prefsUserEntryLimit'].")"; if ($row_limits['prefsUserEntryLimit'] > 1) echo " entries"; else echo " entry"; ?>  per participant in this competition.</strong><?php } ?>
	</span>
</div>
<?php } if (($remaining_entries > 0) && ($registration_open < 2)) { ?>
<div class="adminSubNavContainer">
   	<span class="adminSubNav">
        <span class="icon"><img src="<?php echo $base_url; ?>images/book_add.png"  border="0" alt="Add" title="Add" /></span><a href="<?php if ($_SESSION['userLevel'] <= "1") echo "index.php?section=brew&amp;go=entries&amp;action=add&amp;filter=admin"; else echo "index.php?section=brew&amp;action=add"; ?>">Add an Entry</a>
   	</span>
    <?php if ((!NHC) && ($_SESSION['prefsHideRecipe'] == "N")) { ?>
    <span class="adminSubNav">
        <span class="icon"><img src="<?php echo $base_url; ?>images/page_code.png"  border="0"  alt="BeerXML" title="BeerXML" /></span><a href="<?php echo build_public_url("beerxml","default","default",$sef,$base_url); ?>">Import Entries Using BeerXML</a>
   	</span>
    <?php } ?>
</div>
<?php } // end if (($remaining_entries > 0) && ($registration_open < 2))  ?>
<?php } // end if (($row_limits['prefsUserEntryLimit'] != "") && ($registration_open <= 1)) ?>

<?php if (NHC) { ?>
<div class="adminSubNavContainer">
    <span class="adminSubNav">
        <span class="icon"><img src="<?php echo $base_url; ?>images/printer.png" border="0" alt="Print" /></span><a href="javascript:popUp('1050','700','<?php echo $base_url; ?>output/print.php?section=list&amp;action=print')" title="Print Your List of Entries and Info">Print Your List of Entries and Info</a>
	</span>
</div>
<?php } else { ?>
<div class="adminSubNavContainer">
    <span class="adminSubNav">
        <span class="icon"><img src="<?php echo $base_url; ?>images/printer.png" border="0" alt="Print" /></span><a id="modal_window_link" href="<?php echo $base_url; ?>output/print.php?section=list&amp;action=print" title="Print Your List of Entries and Info">Print Your List of Entries and Info</a>
	</span>
</div>
<?php }
if (($totalRows_log > 0)) { 
if (judging_date_return() > 0) {
$total_entry_fees = total_fees($_SESSION['contestEntryFee'], $_SESSION['contestEntryFee2'], $_SESSION['contestEntryFeeDiscount'], $_SESSION['contestEntryFeeDiscountNum'], $_SESSION['contestEntryCap'], $_SESSION['contestEntryFeePasswordNum'], $row_brewer['uid'], $filter);
$total_paid_entry_fees = total_fees_paid($_SESSION['contestEntryFee'], $_SESSION['contestEntryFee2'], $_SESSION['contestEntryFeeDiscount'], $_SESSION['contestEntryFeeDiscountNum'], $_SESSION['contestEntryCap'], $_SESSION['contestEntryFeePasswordNum'], $row_brewer['uid'], $filter);
$total_to_pay = $total_entry_fees - $total_paid_entry_fees; 

if ((!NHC) || ((NHC) && ($prefix != "final_"))) {
?>
<div class="adminSubNavContainer">
	<span class="adminSubNav">
		<span class="icon"><img src="<?php echo $base_url; ?>images/money.png"  border="0" alt="Entry Fees" title="Entry Fees"></span>You currently have <?php echo readable_number($total_not_paid); ?> <strong>unpaid</strong> <?php if ($total_not_paid == "1") echo "entry. "; else echo "entries. "; ?> Your total entry fees are <?php echo $_SESSION['prefsCurrency'].$total_entry_fees; if ((NHC) && ($_SESSION['brewerDiscount'] != "Y")) echo " (as a non-AHA member)"; echo ". You need to pay ".$_SESSION['prefsCurrency'].$total_to_pay."."; ?>
	</span>
    <?php if (($_SESSION['brewerDiscount'] == "Y") && ($_SESSION['contestEntryFeePasswordNum'] != "")) { ?>
	<span class="adminSubNav">
		<span class="icon"><img src="<?php echo $base_url; ?>images/star.png"  border="0" alt="Discounted!" title="Discounted Entry Fees"></span><?php if (NHC) echo "As an AHA member, your entry fees are "; else echo "Your fees have been discounted to "; echo $_SESSION['prefsCurrency'].$_SESSION['contestEntryFeePasswordNum']; ?> per entry.
	</span>
	<?php } ?>
</div>
<div class="adminSubNavContainer">
   	<span class="adminSubNav">
    <?php if (($total_not_paid > 0) && ($_SESSION['contestEntryFee'] > 0)) { ?>
        <?php if (($_SESSION['prefsPayToPrint'] == "Y") && ($totalRows_log_confirmed == $totalRows_log)) { ?>
        <span class="icon"><img src="<?php echo $base_url; ?>images/exclamation.png" border="0" alt="Entry Fees" title="Entry Fees"></span><a href="<?php echo build_public_url("pay","default","default",$sef,$base_url); ?>">Pay Your Fees</a><?php if ($_SESSION['prefsPayToPrint'] == "Y") echo " <em>** Please note that you will not be able to print your bottle labels and entry forms until you pay for your entries.</em>"; ?>
        <?php } ?>
        <?php if (($_SESSION['prefsPayToPrint'] == "Y") && ($totalRows_log_confirmed != $totalRows_log)) { ?>
        <span class="icon"><img src="<?php echo $base_url; ?>images/exclamation.png" border="0" alt="Entry Fees" title="Entry Fees"></span><span class="red">You have unconfirmed entries. <strong>You cannot pay for your entries until ALL are confirmed</strong>.</span> Confirm each entry by clicking its corresponding &ldquo;Edit&rdquo; link.
        <?php } ?>
	<?php } elseif ($totalRows_log == 0) echo ""; else { ?>
        <span class="icon"><img src="<?php echo $base_url; ?>images/thumb_up.png"  border="0" alt="Entry Fees" title="Entry Fees"></span>Your fees have been paid. Thank you!
	<?php } ?>
    </span>
</div>
<?php 
} // end if ($prefix != "final_")
} if (NHC) { 
if (($entry_window_open > 0) && ($prefix != "final_")) {
?>
<div class="adminSubNavContainer">
   	<span class="adminSubNav">
    	<span class="icon"><img src="<?php echo $base_url; ?>images/exclamation.png" border="0" alt="NHC Paid" title="NHC Paid"></span>Your entries are not completely entered until they have been confirmed and entry fees have been paid.  Entries not paid within 24 hours of registration will be deleted from the competition database.
    </span>
</div>
<?php }
if ((NHC) && ($prefix != "final_") && (judging_date_return() == 0) && ($_SESSION['prefsDisplayWinners'] == "Y") && (judging_winner_display($delay))) { 
$query_package_count = sprintf("SELECT a.scorePlace, a.scoreEntry FROM %s a, %s b, %s c WHERE a.eid = b.id AND c.uid = b.brewBrewerID AND b.brewBrewerID = '%s' AND a.scoreEntry >=25", $judging_scores_db_table, $brewing_db_table, $brewer_db_table, $_SESSION['user_id']); 
$package_count = mysql_query($query_package_count, $brewing) or die(mysql_error());
$row_package_count = mysql_fetch_assoc($package_count);
$totalRows_package_count = mysql_num_rows($package_count);

$query_admin_adv = sprintf("SELECT COUNT(*) AS 'count' FROM $brewing_db_table WHERE brewBrewerID = '%s' AND brewWinner='6'", $_SESSION['user_id']);
$admin_adv = mysql_query($query_admin_adv, $brewing) or die(mysql_error());
$row_admin_adv = mysql_fetch_assoc($admin_adv);

if ($totalRows_package_count > 0) {
	do { 
	if (($row_package_count['scorePlace'] != "") && ($row_package_count['scorePlace'] <= 3) && ($row_package_count['scoreEntry'] >= 30)) $count_winner[] = 1;
	else $count_winner[] = 0;
	} while ($row_package_count = mysql_fetch_assoc($package_count));
	$winner_count = array_sum($count_winner);
}
else $winner_count = 0;

if ($winner_count > 0) $winner = TRUE;
if ($row_admin_adv['count'] > 0) $admin_advance = TRUE;
if ($totalRows_package_count > 0) $certificate = TRUE;

?>
<div class="closed">
Your NHC Post-Competition Package is now available - it includes a letter from the American Homebrewers Association <?php if ($certificate) { ?> and the gold, silver, and/or bronze certificates your <?php if ($totalRows_count_winner == 1) echo "entry"; else echo "entries"; ?> earned<?php } ?>.
 Download the <a href="<?php echo $base_url; ?>mods/nhc_package.php?view=<?php if ($winner) echo "winner";  else echo "non-winner"; if ($admin_advance) echo "&amp;filter=admin_adv"; else echo "&amp;filter=default"; ?>&amp;id=<?php echo $_SESSION['user_id']; ?>">letter</a> (PDF) <?php if ($certificate) { ?> and your <a href="<?php echo $base_url; ?>mods/nhc_package_certificates.php?id=<?php echo $_SESSION['user_id']; ?>">certificates</a> (PDF)<?php } ?>.
</div>
<?php } ?>
<?php } // end if (NHC) ?>
<?php } // end if ((judging_date_return() > 0) && ($totalRows_log > 0)) ?>
<?php } // end if ($action != "print")
if (($totalRows_log > 0)) { ?>
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
				null,
				<?php if ((judging_date_return() == 0) && ($_SESSION['prefsDisplayWinners'] == "Y") && (judging_winner_display($delay))) { ?>
				null,
				{ "asSorting": [  ] },
				null,
				<?php } ?>
				<?php if ((judging_date_return() > 0) && ($action != "print")) { ?>
				{ "asSorting": [  ] }
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
  	<th class="dataHeading bdr1B" width="15%">Entry Name</th>
  	<th class="dataHeading bdr1B" width="15%">Style</th>
  	<th class="dataHeading bdr1B" width="8%">Confirmed?</th> 
  	<th class="dataHeading bdr1B" width="8%">Paid?</th> 
    <th class="dataHeading bdr1B" width="12%">Updated</th>
  	<?php if ((judging_date_return() == 0) && ($_SESSION['prefsDisplayWinners'] == "Y") && (judging_winner_display($delay))) { ?>
  	<th class="dataHeading bdr1B" width="10%">Score</th>
    <th class="dataHeading bdr1B" width="10%">Mini-BOS?</th>
  	<th class="dataHeading bdr1B">Winner?</th>
  	<?php } ?>
    <?php if (($action != "print") && (judging_date_return() > 0)) { ?>
  	<th class="dataHeading bdr1B">Actions</th>
  	<?php } ?>
 </tr>
</thead>
<tbody>
 <?php do { 
 
 	$winner = winner_check($row_log['id'],$judging_scores_db_table,$judging_tables_db_table,$brewing_db_table,$_SESSION['prefsWinnerMethod']);
	if (NHC) $admin_adv = winner_check($row_log['id'],$judging_scores_db_table,$judging_tables_db_table,$brewing_db_table,$row_log['brewWinner']);
	$winner_place = preg_replace("/[^0-9\s.-:]/", "", $winner);
 	$score = score_check($row_log['id'],$judging_scores_db_table);
 
	mysql_select_db($database, $brewing);
	if ($row_log['brewCategory'] < 10) $fix = "0"; else $fix = "";
	$entry_style = $row_log['brewCategorySort'].$row_log['brewSubCategory'];
	$query_style = sprintf("SELECT * FROM $styles_db_table WHERE brewStyleGroup = '%s' AND brewStyleNum = '%s'", $fix.$row_log['brewCategory'], $row_log['brewSubCategory']);
	$style = mysql_query($query_style, $brewing) or die(mysql_error());
	$row_style = mysql_fetch_assoc($style);
	$totalRows_style = mysql_num_rows($style);
	?>
 <tr<?php if (($row_log['brewConfirmed'] == "0") && ($action != "print")) echo " style='background-color: #fc3; border-top: 1px solid #F90; border-bottom: 1px solid #F90;'"; if ((style_convert($entry_style,"3") == TRUE) && ($row_log['brewInfo'] == "") && ($action != "print")) echo " style='background-color: #f90; border-top: 1px solid #FF6600; border-bottom: 1px solid #FF6600;'"; ?>>
 	<td class="dataList">
		<?php if ((NHC) && ($prefix == "final_")) echo sprintf("%06s",$row_log['id']); else echo sprintf("%04s",$row_log['id']); ?>
    </td>
  	<td class="dataList">
		<?php echo $row_log['brewName']; if ($row_log['brewCoBrewer'] != "") echo "<br><em>Co-Brewer: ".$row_log['brewCoBrewer']."</em>"; ?>
    </td>
  	<td class="dataList">
		<?php if ($row_style['brewStyleActive'] == "Y") echo $row_log['brewCategorySort'].$row_log['brewSubCategory'].": ".$row_style['brewStyle']; else echo "<span class='required'>Style entered NOT accepted - Please change</span>"; ?>
    </td>
    <td class="dataList">
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
  	<td class="dataList">
		<?php if ($row_log['brewPaid'] == "1")  { if ($action != "print") echo "<img src='".$base_url."images/tick.png'>"; else echo "Y"; } else { if ($action != "print") echo "<img src='".$base_url."images/cross.png'>"; else echo "N"; } ?>
    </td>
    <td class="dataList"><?php if ($row_log['brewUpdated'] != "") echo getTimeZoneDateTime($_SESSION['prefsTimeZone'], strtotime($row_log['brewUpdated']), $_SESSION['prefsDateFormat'],  $_SESSION['prefsTimeFormat'], "short", "date-time-no-gmt"); else echo "&nbsp;"; ?></td>
  	<?php if ((judging_date_return() == 0) && ($_SESSION['prefsDisplayWinners'] == "Y") && (judging_winner_display($delay))) { ?>
  	<td class="dataList"><?php echo $score; ?></td>
    <td class="dataList"><?php if (minibos_check($row_log['id'],$judging_scores_db_table)) { if ($action != "print") echo "<img src='".$base_url."images/tick.png'>"; else echo "Y"; }?></td>
    <td class="dataList"><?php echo $winner; if (NHC) echo $admin_adv; ?></td>
    <?php } if ($action != "print") { ?>
    <?php if (judging_date_return() > 0) {  ?>
  	<td class="dataList" nowrap="nowrap"><?php if ((NHC) && (($registration_open == 1) || ($entry_window_open == 1))) { ?> <span class="icon"><img src="<?php echo $base_url; ?>images/pencil.png"  border="0" alt="Edit <?php echo $row_log['brewName']; ?>" title="Edit <?php echo $row_log['brewName']; ?>"></span><a href="<?php echo $base_url; ?>index.php?section=brew&amp;action=edit&amp;id=<?php echo $row_log['id']; if ($row_log['brewConfirmed'] == 0) echo "&amp;msg=1-".$row_log['brewCategory']."-".$row_log['brewSubCategory']; else echo "&amp;view=".$row_log['brewCategory']."-".$row_log['brewSubCategory']; ?>" title="Edit <?php echo $row_log['brewName']; ?>">Edit</a>&nbsp;&nbsp;<?php } ?>
	<?php if ((NHC) && ($row_log['brewPaid'] != 1)) { ?>
  		<span class="icon"><img src="<?php echo $base_url; ?>images/bin_closed.png"  border="0" alt="Delete <?php echo $row_log['brewName']; ?>" title="Delete <?php echo $row_log['brewName']; ?>?"></span><a href="javascript:DelWithCon('includes/process.inc.php?section=<?php echo $section; ?>&amp;dbTable=<?php echo $brewing_db_table; ?>&amp;action=delete','id',<?php echo $row_log['id']; ?>,'Are you sure you want to delete your entry called <?php echo str_replace("'", "\'", $row_log['brewName']); ?>? This cannot be undone.');" title="Delete <?php echo $row_log['brewName']; ?>?">Delete</a>&nbsp;&nbsp;
	<?php } if (pay_to_print($_SESSION['prefsPayToPrint'],$row_log['brewPaid']) && (!NHC)) { ?>
    	<span class="icon"><img src="<?php echo $base_url; ?>images/printer.png"  border="0" alt="Print Entry Forms and Bottle Labels for <?php echo $row_log['brewName']; ?>" title="Print Entry Forms and Bottle Labels for <?php echo $row_log['brewName']; ?>"></span><a id="modal_window_link" href="<?php echo $base_url; ?>output/entry.php?id=<?php echo $row_log['id']; ?>&amp;bid=<?php echo $_SESSION['brewerID']; ?>" title="Print Entry Forms and Bottle Labels for <?php echo $row_log['brewName']; ?>">View/Print Entry Form and Bottle Labels</a>
	<?php } ?>
    <?php if ((pay_to_print($_SESSION['prefsPayToPrint'],$row_log['brewPaid'])) && (NHC)) { ?>
  		<span class="icon"><img src="<?php echo $base_url; ?>images/printer.png"  border="0" alt="Print Entry Forms and Bottle Labels" title="Print Entry Forms and Bottle Labels"></span><a href="javascript:popUp('1050','700','<?php echo $base_url; ?>output/entry.php?id=<?php echo $row_log['id']; ?>&amp;bid=<?php echo $_SESSION['brewerID']; ?>')" title="Print Entry Forms and Bottle Labels">View/Print Bottle Labels</a>
  	<?php } ?>
    <?php if (NHC) { ?>
    <span class="icon"><img src="<?php echo $base_url; ?>images/printer.png"  border="0" alt="Print Entry Forms and Bottle Labels" title="Print Entry Forms and Bottle Labels"></span><a href="javascript:popUp('1050','700','<?php echo $base_url; ?>output/entry.php?go=recipe&amp;id=<?php echo $row_log['id']; ?>&amp;bid=<?php echo $_SESSION['brewerID']; ?>')" title="Print Recipe">View/Print Recipe</a>
	<?php } ?>
  </td>
  <?php } // end if (judging_date_return() > 0) 
  //if ((NHC) && (judging_date_return() == 0) && ($_SESSION['prefsDisplayWinners'] == "Y") && (judging_winner_display($delay))) {
  ?>
  <!--<td class="dataList" nowrap="nowrap"><?php if ($score >=25) { ?><span class="icon"><img src="<?php echo $base_url; ?>images/rosette.png" border="0" alt="Download Certificate" title="Download Certficate for This Entry"></span><a href="<?php echo $base_url; ?>mods/nhc_certificate.php?id=<?php echo $row_log['id']; ?>">Download <?php echo certificate_type($score); ?></a><?php } ?></td> -->
  <?php // }
  } // end if ($action != "print") ?>
  </tr>
<?php } while ($row_log = mysql_fetch_assoc($log)); ?>
</tbody>
</table>
<?php 
} else { echo "<p>You do not have any entries.</p>"; if ($registration_open == "0") echo "<p>You can add your entries on or after $reg_open.</p>"; } 

if ($_SESSION['prefsUseMods'] == "Y") include(INCLUDES.'mods_bottom.inc.php');
?>