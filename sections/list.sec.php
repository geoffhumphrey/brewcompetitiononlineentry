<?php 
/**
 * Module:      list.sec.php 
 * Description: This module displays user-related data including personal information,
 *              judging/stewarding information, and the participant's associated entries. 
 * 
 */



if ($_SESSION["loginUsername"] != $row_user['user_name']) { ?>
<p>Please <a href="index.php?section=login">log in</a> or <a href="index.php?section=register">register</a> to view your list of brews entered into the <?php echo $row_contest_info['contestName']; ?> organized by <?php echo $row_contest_info['contestHost']; ?>, <?php echo $row_contest_info['contestHostLocation']; ?>.</p> 
<?php } 
else 
{ 
$total_not_paid = total_not_paid_brewer($row_user['id']);
require(DB.'brewer.db.php');
include(DB.'entries.db.php');
include(DB.'judging_locations.db.php');
include(INCLUDES.'db_tables.inc.php');

//$entry_total_final = unpaid_fees($total_not_paid, $row_contest_info['contestEntryFeeDiscount'],$row_contest_info['contestEntryFeeDiscountNum'], $row_contest_info['contestEntryCap'], $row_contest_info['contestEntryFee'], $row_contest_info['contestEntryFee2'], $row_brewer['brewerDiscount'], $row_contest_info['contestEntryFeePasswordNum ']);
/*
if ($row_contest_info['contestEntryFeeDiscount'] == "Y") {
	$discount = discount_display($total_not_paid,$row_contest_info['contestEntryFeeDiscountNum'],$row_contest_info['contestEntryFee'], $row_contest_info['contestEntryFee2'], $row_contest_info['contestEntryCap']);
}
*/
if (($action != "print") && ($msg != "default")) echo $msg_output;
?>
<?php if ($action != "print") { ?>
<p><span class="icon"><img src="images/help.png"  /></span><a class="thickbox" href="http://help.brewcompetition.com/files/my_info.html?KeepThis=true&amp;TB_iframe=true&amp;height=450&amp;width=800" title="BCOE&amp;M Help: My Info and Entries">My Info and Entries Help</a></p>
<?php } ?>
<p>Thank you for entering the <?php echo $row_contest_info['contestName']; ?>, <?php echo $row_name['brewerFirstName']; ?>.</p>
<h2>Info</h2>
<?php if ($action != "print") { ?>

<div class="adminSubNavContainer">
	<span class="adminSubNav">
        <span class="icon"><img src="images/user_edit.png" /></span><a href="index.php?<?php if ($row_brewer['id'] != "") echo "section=brewer&amp;action=edit&amp;id=".$row_brewer['id']; else echo "action=add&amp;section=brewer&amp;go=judge"; ?>">Edit Your Info</a>
    </span>
    <span class="adminSubNav">
        <span class="icon"><img src="images/email_edit.png" /></span><a href="index.php?section=user&amp;action=username&amp;id=<?php echo $row_user['id']; ?>">Change Your Email Address</a>
    </span>
    <span class="adminSubNav">
        <span class="icon"><img src="images/key.png" /></span><a href="index.php?section=user&amp;action=password&amp;id=<?php echo $row_user['id']; ?>">Change Your Password</a>
    </span>
</div>
<?php } 
//echo "Query: ".$query_brewer;
?>
<table class="dataTable">
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
    	<td class="data"><?php if ($row_brewer['brewerAHA'] != "") echo $row_brewer['brewerAHA']; else echo "Not provided"; ?></td>
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
    	<td class="dataLabel">Judging Availability<br />Locations:</td>
    	<td class="data">
    	<table class="dataTableCompact">
		<?php 
		$a = explode(",",$row_brewer['brewerJudgeLocation']);
		arsort($a);
		foreach ($a as $value) {
			if ($value != "0-0") {
				$b = substr($value, 2);
				$query_judging_loc3 = sprintf("SELECT judgingLocName,judgingDate,judgingLocation,judgingTime FROM judging_locations WHERE id='%s'", $b);
				$judging_loc3 = mysql_query($query_judging_loc3, $brewing) or die(mysql_error());
				$row_judging_loc3 = mysql_fetch_assoc($judging_loc3);
				echo "<tr>\n<td>".substr($value, 0, 1).":</td>\n<td>".$row_judging_loc3['judgingLocName']." ("; 
				echo date_convert($row_judging_loc3['judgingDate'], 3, $row_prefs['prefsDateFormat'])." - ".$row_judging_loc3['judgingTime'].")</td>\n";
				echo "</td>\n</tr>";
				}
			else echo "";
			}
		?>
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
		<td class="data"><span class="icon"><img src="images/page_white_acrobat.png"  border="0" alt="Print your judging scoresheet labels" title="Judging scoresheet labels"></span><a href="output/labels.php?section=admin&amp;go=participants&amp;action=judging_labels&amp;id=<?php echo $row_brewer['id']; ?>">Print Judging Scoresheet Labels</a></span><span class="data">(Avery 5160 PDF Download)</td>
  	</tr>
  	<?php } ?>
	<?php if ($totalRows_judging3 > 1) { ?>
  	<tr>
    	<td class="dataLabel">Location<?php if ($totalRows_judging > 1) echo "(s)"; ?>:</td>
    	<td class="data">
    <?php if (($row_brewer['brewerJudgeAssignedLocation'] != "") || ($row_brewer['brewerStewardAssignedLocation'] != "")) { ?>
    	<table class="dataTableCompact">
		<?php 
		if ($row_brewer['brewerAssignment'] == "J") $a = explode(",",$row_brewer['brewerJudgeAssignedLocation']);
		if ($row_brewer['brewerAssignment'] == "S") $a = explode(",",$row_brewer['brewerStewardAssignedLocation']);
		sort($a);
		foreach ($a as $value) {
			if (($value != "") || ($value != 0)) {
				$query_judging_loc3 = sprintf("SELECT judgingLocName,judgingDate,judgingLocation,judgingTime FROM judging_locations WHERE id='%s'", $value);
				$judging_loc3 = mysql_query($query_judging_loc3, $brewing) or die(mysql_error());
				$row_judging_loc3 = mysql_fetch_assoc($judging_loc3);
				echo "<tr>\n<td>".$value.":</td>\n<td>".$row_judging_loc3['judgingLocName']." ("; 
				echo date_convert($row_judging_loc3['judgingDate'], 3, $row_prefs['prefsDateFormat'])." - ".$row_judging_loc3['judgingTime'].")</td>\n";
				echo "</td>\n</tr>";
				}
			}
		?>
    	</table>
	<?php 
	} else echo "Contact the competition coordinator for your assignment."; 
	?>
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
      <td width="10%" class="dataLabel">Mead Judge Rank/Endorsement:</td>
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
    	<td class="dataLabel">Catagories Not Preferred:</td>
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
    	<td class="dataLabel">Stewarding Availability<br />Locations:</td>
    	<td class="data">
    	<table class="dataTableCompact">
		<?php 
		$a = explode(",",$row_brewer['brewerStewardLocation']);
		arsort($a);
		foreach ($a as $value) {
			if ($value != "0-0") {
				$b = substr($value, 2);
				$query_judging_loc3 = sprintf("SELECT judgingLocName,judgingDate,judgingLocation,judgingTime FROM judging_locations WHERE id='%s'", $b);
				$judging_loc3 = mysql_query($query_judging_loc3, $brewing) or die(mysql_error());
				$row_judging_loc3 = mysql_fetch_assoc($judging_loc3);
				echo "<tr>\n<td>".substr($value, 0, 1).":</td>\n<td>".$row_judging_loc3['judgingLocName']." ("; 
				echo date_convert($row_judging_loc3['judgingDate'], 3, $row_prefs['prefsDateFormat'])." - ".$row_judging_loc3['judgingTime'].")</td>\n";
				echo "</td>\n</tr>";
				}
			else echo "";
			}
		?>
    	</table>
    	</td>
  </tr>
  <?php } ?>
  <?php } ?>
</table>
<?php } ?>
<h2>Entries</h2>
<?php if ($totalRows_log > 0) { ?>
<p><?php echo $row_name['brewerFirstName']; ?>, you have <?php echo $totalRows_log; if ($totalRows_log <= 1) echo " entry"; else echo " entries"; ?>, listed below. <?php if (judging_date_return() > 0) echo "Be sure to print entry forms and bottle labels for each."; else echo "Judging has taken place."; ?></p>
<?php } ?>
<?php if ($action != "print") { ?>
<?php if (!greaterDate($today,$row_contest_info['contestRegistrationDeadline'])) { ?>
<div class="adminSubNavContainer">
   	<span class="adminSubNav">
        <span class="icon"><img src="images/book_add.png"  /></span><a href="index.php?section=brew&amp;action=add">Add an Entry</a>
   	</span>
    <span class="adminSubNav">
        <span class="icon"><img src="images/page_code.png"  /></span><a href="index.php?section=beerxml">Import Entries Using BeerXML</a>
   	</span>
    <span class="adminSubNav">
        <span class="icon"><img src="images/printer.png"  border="0" alt="Print" /></span><a class="thickbox" href="output/print.php?section=list&amp;action=print&amp;KeepThis=true&amp;TB_iframe=true&amp;height=450&amp;width=800" title="Print Your List of Entries and Info">Print Your List of Entries and Info</a>
	</span>
</div>
<?php if ((judging_date_return() >0) && ($totalRows_log > 0)) { 

$total_entry_fees = total_fees($row_contest_info['contestEntryFee'], $row_contest_info['contestEntryFee2'], $row_contest_info['contestEntryFeeDiscount'], $row_contest_info['contestEntryFeeDiscountNum'], $row_contest_info['contestEntryCap'], $row_contest_info['contestEntryFeePasswordNum'], $row_brewer['uid'], $filter);
$total_paid_entry_fees = total_fees_paid($row_contest_info['contestEntryFee'], $row_contest_info['contestEntryFee2'], $row_contest_info['contestEntryFeeDiscount'], $row_contest_info['contestEntryFeeDiscountNum'], $row_contest_info['contestEntryCap'], $row_contest_info['contestEntryFeePasswordNum'], $row_brewer['uid'], $filter);
$total_to_pay = $total_entry_fees - $total_paid_entry_fees; 
?>
<div class="adminSubNavContainer">
	<span class="adminSubNav">
		<span class="icon"><img src="images/money.png"  border="0" alt="Entry Fees" title="Entry Fees"></span>You currently have <?php echo $total_not_paid; ?> <strong>unpaid</strong> <?php if ($total_not_paid == "1") echo "entry. "; else echo "entries. "; ?> Your total entry fees are <?php echo $row_prefs['prefsCurrency'].$total_entry_fees.". You need to pay ".$row_prefs['prefsCurrency'].$total_to_pay."."; ?>
	</span>
    <?php if (($row_brewer['brewerDiscount'] == "Y") && ($row_contest_info['contestEntryFeePasswordNum'] != "")) { ?>
	<span class="adminSubNav">
		<span class="icon"><img src="images/star.png"  border="0" alt="Discounted!" title="Discounted Entry Fees"></span>Your fees have been discounted to <?php echo $row_prefs['prefsCurrency'].$row_contest_info['contestEntryFeePasswordNum']; ?> per entry.
	</span>
<?php } ?>
   	<span class="adminSubNav">
        <?php if (($total_not_paid > 0) && ($row_contest_info['contestEntryFee'] > 0)) { ?><span class="icon"><img src="images/exclamation.png"  border="0" alt="Entry Fees" title="Entry Fees"></span><a href="index.php?section=pay">Pay Entry Fees</a><?php } elseif ($totalRows_log == 0) echo ""; else { ?><span class="icon"><img src="images/thumb_up.png"  border="0" alt="Entry Fees" title="Entry Fees"></span>Your fees have been paid. Thank you!<?php } ?>
    </span>
</div>
<?php } // end if ((judging_date_return() > 0) && ($totalRows_log > 0)) ?>
<?php } // end if (!greaterDate($today,$row_contest_info['contestRegistrationDeadline'])) ?>
<?php } // end if ($action != "print")
if ($totalRows_log > 0) { ?>
<script type="text/javascript" language="javascript">
	 $(document).ready(function() {
		$('#sortable').dataTable( {
			"bPaginate" : false,
			"sDom": 'rt',
			"bStateSave" : false,
			"bLengthChange" : false,
			"aaSorting": [[2,'asc']],
			"aoColumns": [
				null,
				null,
				null,
				null<?php if ($action != "print") { ?>,
				<?php if (judging_date_return() == 0) { ?>
				null,
				null,
				<?php } ?>
				<?php if (judging_date_return() > 0)  { ?>
				{ "asSorting": [  ] }
				<?php } ?>
  				<?php } ?>
				]
			} );
		} );
</script>
<table class="dataTable" id="sortable">
<thead>
 <tr>
  	<th class="dataHeading bdr1B" width="5%">Entry #</th>
  	<th class="dataHeading bdr1B" width="20%">Entry Name</th>
  	<th class="dataHeading bdr1B" width="20%">Style</th>
  	<th class="dataHeading bdr1B" width="5%">Paid?</th> 
  	<?php if (judging_date_return() == 0) { ?>
  	<th class="dataHeading bdr1B" width="8%">Score</th>
  	<th class="dataHeading bdr1B" width="8%">Winner?</th>
  	<?php } ?>
    <?php if ($action != "print") { ?>
    <?php if (judging_date_return() > 0) { ?>
  	<th class="dataHeading bdr1B">Actions</th>
    <?php } ?>
  	<?php } ?>
 </tr>
</thead>
<tbody>
 <?php do { 
	mysql_select_db($database, $brewing);
	if ($row_log['brewCategory'] < 10) $fix = "0"; else $fix = "";
	$query_style = sprintf("SELECT * FROM styles WHERE brewStyleGroup = '%s' AND brewStyleNum = '%s'", $fix.$row_log['brewCategory'], $row_log['brewSubCategory']);
	$style = mysql_query($query_style, $brewing) or die(mysql_error());
	$row_style = mysql_fetch_assoc($style);
	$totalRows_style = mysql_num_rows($style);
	?>
 <tr>
 	<td class="dataList">
		<?php echo $row_log['id']; ?>
    </td>
  	<td class="dataList">
		<?php echo $row_log['brewName']; if ($row_log['brewCoBrewer'] != "") echo "<br><em>Co-Brewer: ".$row_log['brewCoBrewer']."</em>"; ?>
    </td>
  	<td class="dataList">
		<?php if ($row_style['brewStyleActive'] == "Y") echo $row_log['brewCategorySort'].$row_log['brewSubCategory'].": ".$row_style['brewStyle']; else echo "<span class='required'>Style entered NOT accepted - Please change</span>"; ?>
    </td>
  	<td class="dataList">
		<?php if ($row_log['brewPaid'] == "Y")  { if ($action != "print") echo "<img src='images/tick.png'>"; else echo "Y"; } else { if ($action != "print") echo "<img src='images/cross.png'>"; else echo "N"; } ?>
    </td>
    
  <?php if (judging_date_return() == 0) { ?>
  <td class="dataList">
  <?php if ($row_prefs['prefsDisplayWinners'] == "Y") echo score_check($row_log['id'],$scores_db_table);
	else echo "&nbsp;"; ?>
    </td>
    <td class="dataList"><?php if ($row_prefs['prefsDisplayWinners'] == "Y") echo winner_check($row_log['id'],$scores_db_table,$tables_db_table,1); else echo "&nbsp;"; ?>
    </td>
    <?php } if ($action != "print") { ?>
    <?php if (judging_date_return() > 0) { ?>
  <td class="dataList">
  	<span class="icon"><img src="images/pencil.png"  border="0" alt="Edit <?php echo $row_log['brewName']; ?>" title="Edit <?php echo $row_log['brewName']; ?>"></span><a href="index.php?section=brew&amp;action=edit&amp;id=<?php echo $row_log['id']; ?>" title="Edit <?php echo $row_log['brewName']; ?>">Edit</a>
  <span class="icon"><img src="images/bin_closed.png"  border="0" alt="Delete <?php echo $row_log['brewName']; ?>" title="Delete <?php echo $row_log['brewName']; ?>?"></span><a href="javascript:DelWithCon('includes/process.inc.php?section=<?php echo $section; ?>&amp;dbTable=brewing&amp;action=delete','id',<?php echo $row_log['id']; ?>,'Are you sure you want to delete your entry called <?php echo str_replace("'", "\'", $row_log['brewName']); ?>? This cannot be undone.');" title="Delete <?php echo $row_log['brewName']; ?>?">Delete</a> 	
  	<span class="icon"><img src="images/printer.png"  border="0" alt="Print Entry Forms and Bottle Labels for <?php echo $row_log['brewName']; ?>" title="Print Entry Forms and Bottle Labels for <?php echo $row_log['brewName']; ?>"></span><a class="thickbox" href="output/entry.php?id=<?php echo $row_log['id']; ?>&amp;bid=<?php echo $row_brewer['uid']; ?>&amp;KeepThis=true&amp;TB_iframe=true&amp;height=450&amp;width=800" title="Print Entry Forms and Bottle Labels for <?php echo $row_log['brewName']; ?>">Print Entry Forms and Bottle Labels</a>
  </td>
  <?php } ?>
  <?php } ?>
 </tr>
<?php } while ($row_log = mysql_fetch_assoc($log)); ?>
</tbody>
</table>
<?php 
} else echo "<p>You do not have any entries.</p>"; ?>