<?php if ($_SESSION["loginUsername"] != $row_user['user_name']) { 

?>
<p>Please <a href="index.php?section=login">log in</a> or <a href="index.php?section=register">register</a> to view your list of brews entered into the <?php echo $row_contest_info['contestName']; ?> organized by <?php echo $row_contest_info['contestHost']; ?>, <?php echo $row_contest_info['contestHostLocation']; ?>.</p> 
<?php } 
else 
{ 
$query_brewNotPaid = sprintf("SELECT * FROM brewing WHERE brewBrewerID='%s' AND brewPaid='' OR brewPaid='N'", $row_brewer['uid']);
$brewNotPaid = mysql_query($query_brewNotPaid, $brewing) or die(mysql_error());
$row_brewNotPaid = mysql_fetch_assoc($brewNotPaid);
$totalRows_brewNotPaid = mysql_num_rows($brewNotPaid);

if ($row_contest_info['contestEntryFeeDiscount'] == "N") $entry_total = $totalRows_brewNotPaid * $row_contest_info['contestEntryFee'];
if (($row_contest_info['contestEntryFeeDiscount'] == "Y") && ($totalRows_brewNotPaid > $row_contest_info['contestEntryFeeDiscountNum'])) {
	$discFee = ($totalRows_brewNotPaid - $row_contest_info['contestEntryFeeDiscountNum']) * $row_contest_info['contestEntryFee2'];
	$regFee = $row_contest_info['contestEntryFeeDiscountNum'] * $row_contest_info['contestEntryFee'];
	$entry_total = $discFee + $regFee;
	}

if (($row_contest_info['contestEntryFeeDiscount'] == "Y") && ($totalRows_brewNotPaid <= $row_contest_info['contestEntryFeeDiscountNum'])) {
	if ($totalRows_brewNotPaid > 0) $entry_total = $totalRows_brewNotPaid * $row_contest_info['contestEntryFee'];
	else $entry_total = "0"; 
} 
 
if (($row_contest_info['contestEntryCap'] != "") && ($entry_total > $row_contest_info['contestEntryCap'])) { $fee = ($row_contest_info['contestEntryCap'] * .029); $entry_total_final = $row_contest_info['contestEntryCap']; } else { $fee = ($entry_total * .029); $entry_total_final = $entry_total; }
if ($row_contest_info['contestEntryCap'] == "") { $fee = ($entry_total * .029); $entry_total_final = $entry_total; }

// echo $entry_total;
if ($msg != "default") echo $msg_output;
?>
<p>Thank you for entering the <?php echo $row_contest_info['contestName']; ?>, <?php echo $row_name['brewerFirstName']; ?>.</p>
<h2>Info</h2>
<?php if ($action != "print") { ?>
<?php if (greaterDate($today,$deadline)) echo ""; else { ?>
<table class="dataTable">
	<tr>
    	<td class="dataList" width="5%" nowrap="nowrap"><span class="icon"><img src="images/user_edit.png"  /></span><a class="data" href="index.php?<?php if ($row_brewer['id'] != "") echo "section=brewer&amp;action=edit&amp;id=".$row_brewer['id']; else echo "action=add&amp;section=brewer&amp;go=judge"; ?>">Edit Your Info</a></td>
    	<td class="dataList" width="5%" nowrap="nowrap"><span class="icon"><img src="images/email_edit.png"  /></span><a class="data" href="index.php?section=user&amp;action=username&amp;id=<?php echo $row_user['id']; ?>">Change Email Address</a></td>
        <td class="dataList"><span class="icon"><img src="images/key.png"  /></span><a class="data" href="index.php?section=user&amp;action=password&amp;id=<?php echo $row_user['id']; ?>">Change Password</a></td>
    </tr>
</table>
<?php } ?>
<?php } ?>
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
    <td class="data"><?php if ($row_brewer['brewerAddress'] != "") echo $row_brewer['brewerAddress']."<br>".$row_brewer['brewerCity'].", ".$row_brewer['brewerState']." ".$row_brewer['brewerZip']." ".$row_brewer['brewerCountry']; else echo "None entered"; ?></td>
  </tr>
  <tr>
    <td class="dataLabel">Phone Number(s):</td>
    <td class="data"><?php  if ($row_brewer['brewerPhone1'] != "") echo $row_brewer['brewerPhone1']." (1) ";  if ($row_brewer['brewerPhone2'] != "") echo "<br>".$row_brewer['brewerPhone2']." (2)"; ?></td>
  </tr>
  <tr>
    <td class="dataLabel">Club:</td>
    <td class="data"><?php if ($row_brewer['brewerClubs'] != "") echo $row_brewer['brewerClubs']; else echo "None entered"; ?></td>
  </tr>
  <tr>
    <td class="dataLabel">AHA Member Number:</td>
    <td class="data"><?php if ($row_brewer['brewerAHA'] != "") echo $row_brewer['brewerAHA']; else echo "Not provided"; ?></td>
  </tr>
  <?php if ($row_brewer['brewerAssignment'] == "") { ?>
  <tr>
    <td class="dataLabel">Judge?</td>
    <td class="data"><?php if ($row_brewer['brewerJudge'] != "") echo  $row_brewer['brewerJudge']; else echo "None entered"; ?></td>
  </tr>
  <?php if (($row_brewer['brewerJudge'] == "Y") && ($totalRows_judging3 > 1)) { ?>
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
				$query_judging_loc3 = sprintf("SELECT judgingLocName,judgingDate,judgingLocation FROM judging WHERE id='%s'", $b);
				$judging_loc3 = mysql_query($query_judging_loc3, $brewing) or die(mysql_error());
				$row_judging_loc3 = mysql_fetch_assoc($judging_loc3);
				echo "<tr>\n<td>".substr($value, 0, 1).":</td>\n<td>".$row_judging_loc3['judgingLocName']." ("; 
				echo dateconvert($row_judging_loc3['judgingDate'], 3).")</td>\n";
				echo "</td>\n</tr>";
				}
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
  <?php if ($totalRows_judging3 > 1) { ?>
  <tr>
    <td class="dataLabel">Location:</td>
    <td class="data">
    <?php if (($row_brewer['brewerJudgeAssignedLocation'] != "") || ($row_brewer['brewerStewardAssignedLocation'] != "")) {
	if ($row_judging4['judgingDate'] != "") echo dateconvert($row_judging4['judgingDate'], 2)." at "; echo $row_judging4['judgingLocName']; 
	if ($row_judging4['judgingTime'] != "") echo ", ".$row_judging4['judgingTime']; if (($row_judging2['judgingLocation'] != "") && ($action != "print"))  { ?>&nbsp;&nbsp;<span class="icon"><a class="thickbox" href="http://maps.google.com/maps/api/staticmap?center=<?php echo str_replace(' ', '+', $row_judging4['judgingLocation']); ?>&amp;zoom=13&amp;size=600x400&amp;markers=color:red|<?php echo str_replace(' ', '+', $row_judging4['judgingLocation']); ?>&amp;sensor=false&amp;KeepThis=true&amp;TB_iframe=true&amp;height=420&amp;width=600" title="Map to <?php echo $row_judging4['judgingLocName']; ?>"><img src="images/map.png"  border="0" alt="Map <?php echo $row_judging4['judgingLocName']; ?>" title="Map <?php echo $row_judging4['judgingLocName']; ?>" /></a></span>
	<span class="icon"><a class="thickbox" href="http://maps.google.com/maps?f=q&amp;source=s_q&amp;hl=en&amp;q=<?php echo str_replace(' ', '+', $row_judging4['judgingLocation']); ?>&amp;KeepThis=true&amp;TB_iframe=true&amp;height=450&amp;width=900" title="Driving Directions to <?php echo $row_judging4['judgingLocName']; ?>"><img src="images/car.png"  border="0" alt="Driving Directions to <?php echo $row_judging4['judgingLocName']; ?>" title="Driving Direcitons to <?php echo $row_judging4['judgingLocName']; ?>" /></a></span>
	<?php } 
	} else echo "Contact the competition coordinator for your assignment."; 
	?>
    </td>
  </tr>
  <?php } ?>
  <?php } ?>
  <?php if ((($row_brewer['brewerJudge'] == "Y") && ($row_brewer['brewerAssignment'] == "") || ($row_brewer['brewerJudge'] == "Y") && ($row_brewer['brewerAssignment'] == "J"))) { ?>
  <tr>
    <td class="dataLabel">BJCP Judge ID:</td>
    <td class="data"><?php  if ($row_brewer['brewerJudgeID'] != "0") echo $row_brewer['brewerJudgeID']; else echo "N/A"; ?></td>
  </tr>
  <tr>
    <td class="dataLabel">BJCP Rank:</td>
    <td class="data"><?php  if ($row_brewer['brewerJudgeRank'] != "") echo  $row_brewer['brewerJudgeRank']; else echo "N/A"; ?></td>
  </tr>
  <tr>
    <td class="dataLabel">Categories Preferred:</td>
    <td class="data"><?php if ($row_brewer['brewerJudgeLikes'] != "") echo str_replace(",", ", ", $row_brewer['brewerJudgeLikes']); else echo "N/A"; ?></td>
  </tr>
  <tr>
    <td class="dataLabel">Catagories Not Preferred:</td>
    <td class="data"><?php if ($row_brewer['brewerJudgeDislikes'] != "") echo str_replace(",", ", ", $row_brewer['brewerJudgeDislikes']); else echo "N/A"; ?></td>
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
				$query_judging_loc3 = sprintf("SELECT judgingLocName,judgingDate,judgingLocation FROM judging WHERE id='%s'", $b);
				$judging_loc3 = mysql_query($query_judging_loc3, $brewing) or die(mysql_error());
				$row_judging_loc3 = mysql_fetch_assoc($judging_loc3);
				echo "<tr>\n<td>".substr($value, 0, 1).":</td>\n<td>".$row_judging_loc3['judgingLocName']." ("; 
				echo dateconvert($row_judging_loc3['judgingDate'], 3).")</td>\n";
				echo "</td>\n</tr>";
				}
			}
	?>
    </table>
    </td>
  </tr>
  <?php } ?>
  <?php } ?>
</table>
<?php } ?>

<?php if ($action != "print") { ?>
<?php } else { ?>
<p><?php echo $row_name['brewerFirstName']; ?>, you have <?php echo $totalRows_log; if ($totalRows_log <= 1) echo " entry "; else echo " entires "; ?>in the <?php echo $row_contest_info['contestName'];?>.<?php } ?>
<h2>Entries</h2>
<?php if ($action != "print") { ?>
<?php if ($totalRows_log > 0) { ?>
<p>Below is a list of your entires. <?php if ($judgingDateReturn == "false") echo "Be sure to print entry forms and bottle labels for each."; else echo "Judging has taken place."; ?></p>
<?php } ?>
<?php if (greaterDate($today,$deadline)) echo ""; else { ?>
<table class="dataTable">
<tbody>
 <tr>
   <td class="dataList" width="5%" nowrap="nowrap"><span class="icon"><img src="images/book_add.png"  /></span><a class="data" href="index.php?section=brew&amp;action=add">Add an Entry</a></td>
   <td class="dataList" width="5%" nowrap="nowrap"><span class="icon"><img src="images/page_code.png"  /></span><a class="data" href="index.php?section=beerxml">Import an Entry Using BeerXML</a></td>
   <td class="dataList"><span class="icon"><img src="images/printer.png"  border="0" alt="Print" /></span><a class="data thickbox" href="print.php?section=list&amp;action=print&amp;KeepThis=true&amp;TB_iframe=true&amp;height=450&amp;width=750" title="Print Your List of Entries and Info">Print Your List of Entries and Info</a></td>
 </tr>
</tbody>
</table>
<?php if (($judgingDateReturn == "false") && ($totalRows_log > 0)) { ?>
<table class="dataTable" style="margin: 0 0 25px 0;">
<tbody>
 <tr>
  <td class="data" width="5%" nowrap="nowrap"><span class="icon"><img src="images/money.png"  border="0" alt="Entry Fees" title="Entry Fees"></span><span class="data">Total Unpaid Entry Fees: <?php echo $row_prefs['prefsCurrency']; echo number_format($entry_total_final, 2); ?></span></span></td><br />
  <?php if (($totalRows_brewNotPaid > 0) && ($row_contest_info['contestEntryFee'] > 0)) { ?>
  <td class="data" width="5%" nowrap="nowrap"><span class="icon"><img src="images/money.png"  border="0" alt="Entry Fees" title="Entry Fees"></span><span class="data">You have <?php echo $totalRows_brewNotPaid; ?> Unpaid <?php if ($totalRows_brewNotPaid == "1") echo "Entry"; else echo "Entries"; ?></span></td>
  <?php } if ($action != "print") { ?>
  <td class="data"><?php if (($totalRows_brewNotPaid > 0) && ($row_contest_info['contestEntryFee'] > 0)) { ?><span class="icon"><img src="images/exclamation.png"  border="0" alt="Entry Fees" title="Entry Fees"></span><span class="data"><a href="index.php?section=pay">Pay Entry Fees</a></span><?php } elseif ($totalRows_log == 0) echo ""; else { ?><span class="icon"><img src="images/thumb_up.png"  border="0" alt="Entry Fees" title="Entry Fees"></span><span class="data">Your fees have been paid. Thank you!</span><?php } ?></td>
  <?php } ?>
 </tr>
</tbody>
</table>
<?php } ?>
<?php } ?>
<?php } if ($totalRows_log > 0) { ?>
<?php if ($action != "print") {?>
<script type="text/javascript" language="javascript" src="js_includes/jquery.js"></script>
<script type="text/javascript" language="javascript" src="js_includes/jquery.dataTables.js"></script>
<script type="text/javascript" language="javascript">
	 $(document).ready(function() {
		$('#sortable').dataTable( {
			"bPaginate" : false,
			"sDom": 'rt',
			"bStateSave" : false,
			"bLengthChange" : false,
			"aoColumns": [
				null,
				null,
				<?php if ($action != "print") { ?>
				<?php if (greaterDate($today,$deadline)) echo ""; else { ?>
				{ "asSorting": [  ] },
				<?php } ?>
  				<?php } ?>
				]
			} );
		} );
</script>
<?php } ?>
<table class="dataTable" id="sortable">
<thead>
 <tr>
  <th class="dataHeading bdr1B">Entry Name</th>
  <th class="dataHeading bdr1B">Style</th>
  <th class="dataHeading bdr1B">Paid?</th> 
  <?php if ($action != "print") { ?>
  <?php if (greaterDate($today,$deadline)) echo ""; else { ?>
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
  <td class="dataList" width="25%"><?php echo $row_log['brewName']; ?></td>
  <td class="dataList" <?php if ($judgingDateReturn == "false") echo "width=\"25%\""; ?>><?php if ($row_style['brewStyleActive'] == "Y") echo $row_log['brewCategorySort'].$row_log['brewSubCategory'].": ".$row_style['brewStyle']; else echo "<span class='required'>Style entered NOT accepted - Please change</span>"; ?></td>
  <td class="dataList" width="5%"><?php if ($row_log['brewPaid'] == "Y")  { if ($action != "print") echo "<img src='images/tick.png'>"; else echo "Y"; } else { if ($action != "print") echo "<img src='images/cross.png'>"; else echo "N"; } ?>
  <?php if ($action != "print") { ?>
  		<?php if (greaterDate($today,$deadline)) echo ""; else { ?>
  <td class="dataList">
  <span class="icon"><img src="images/pencil.png"  border="0" alt="Edit <?php echo $row_log['brewName']; ?>" title="Edit <?php echo $row_log['brewName']; ?>"></span><a href="index.php?section=brew&amp;action=edit&amp;id=<?php echo $row_log['id']; ?>" title="Edit <?php echo $row_log['brewName']; ?>">Edit</a>
  <span class="icon"><img src="images/bin_closed.png"  border="0" alt="Delete <?php echo $row_log['brewName']; ?>" title="Delete <?php echo $row_log['brewName']; ?>?"></span><a href="javascript:DelWithCon('includes/process.inc.php?section=<?php echo $section; ?>&amp;dbTable=brewing&amp;action=delete','id',<?php echo $row_log['id']; ?>,'Are you sure you want to delete your entry called <?php echo str_replace("'", "\'", $row_log['brewName']); ?>? This cannot be undone.');" title="Delete <?php echo $row_log['brewName']; ?>?">Delete</a>
  	<?php } 
  if (greaterDate($today,$deadline)) echo ""; else { ?>
  <span class="icon"><img src="images/printer.png"  border="0" alt="Print Entry Forms and Bottle Lables for <?php echo $row_log['brewName']; ?>" title="Print Entry Forms and Bottle Lables for <?php echo $row_log['brewName']; ?>"></span><a class="thickbox" href="sections/entry.sec.php?id=<?php echo $row_log['id']; ?>&amp;bid=<?php echo $row_log['brewBrewerID']; ?>&amp;KeepThis=true&amp;TB_iframe=true&amp;height=450&amp;width=750" title="Print Entry Forms and Bottle Lables for <?php echo $row_log['brewName']; ?>">Print Entry Forms and Bottle Lables</a>
  <?php } ?>
  </td>
  <?php  } ?>
 </tr>
<?php } while ($row_log = mysql_fetch_assoc($log)); ?>
</tbody>
</table>
<?php 
} else echo "<p>You do not have any entries.</p>"; ?>
