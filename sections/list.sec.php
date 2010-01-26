<?php if ($_SESSION["loginUsername"] != $row_user['user_name']) { ?>
<p>Please <a href="index.php?section=login">log in</a> or <a href="index.php?section=register">register</a> to view your list of brews entered into the <?php echo $row_contest_info['contestName']; ?> organized by <?php echo $row_contest_info['contestHost']; ?>, <?php echo $row_contest_info['contestHostLocation']; ?>.</p> 
<?php } else { ?>
<div id="header">
	<div id="header-inner"><h1>Your List of Entries and Info</h1></div>
</div>
<?php 
$entry_total = $totalRows_log * $row_contest_info['contestEntryFee'];
if (($row_contest_info['contestEntryCap'] != "") && ($entry_total > $row_contest_info['contestEntryCap'])) { $fee = ($row_contest_info['contestEntryCap'] * .029); $entry_total_final = $row_contest_info['contestEntryCap']; } else { $fee = ($entry_total * .029); $entry_total_final = $entry_total; }
if ($row_contest_info['contestEntryCap'] == "") { $fee = ($entry_total * .029); $entry_total_final = $entry_total; }

if ($msg == "1") echo "<div class=\"error\">Information added successfully.</div>"; 
if ($msg == "2") echo "<div class=\"error\">Information edited successfully.</div>"; 
if ($msg == "3") echo "<div class=\"error\">Your email address has been updated.</div>"; 
if ($msg == "4") echo "<div class=\"error\">Your password has been updated.</div>"; 
if ($msg == "5") echo "<div class=\"error\">Information deleted successfully.</div>"; 
if ($msg == "6") echo "<div class=\"error\">You should verify all your entries imported using BeerXML.</div>"; 
if ($msg == "7") echo "<div class=\"error\">You have registered as a judge or steward. Thank you!</div>"; 
?>
<?php if ($action != "print") { ?>
<p>Thank you for entering the <?php echo $row_contest_info['contestName']; ?>, <?php echo $row_name['brewerFirstName']; ?>.</p>
<?php } else { ?>
<p><?php echo $row_name['brewerFirstName']; ?>, you have <?php echo $totalRows_log; ?> entries in the <?php echo $row_contest_info['contestName'];?>.<?php } ?>
<h2>Entries</h2>
<?php if ($action != "print") { ?>
<?php if ($totalRows_log > 0) { ?>
<p>Below is a list of your entires. Be sure to print recipe sheets and bottle labels for each.</p>
<?php } ?>
<?php if (greaterDate($today,$deadline)) echo ""; else { ?>
<table class="dataTable">
 <tr>
   <td class="dataList" width="5%" nowrap="nowrap"><span class="icon"><img src="images/book_add.png" align="absmiddle" /></span><a class="data" href="index.php?section=brew&action=add">Add an Entry</a></td>
   <td class="dataList" width="5%" nowrap="nowrap"><span class="icon"><img src="images/page_code.png" align="absmiddle" /></span><a class="data" href="index.php?section=beerxml">Import an Entry Using BeerXML</a></td>
   <td class="dataList"><span class="icon"><img src="images/printer.png" align="absmiddle" /></span><a class="data" href="#" onClick="window.open('print.php?section=list&action=print','','height=600,width=800,toolbar=no,resizable=yes,scrollbars=yes'); return false;">Print List of Entries/Info</a></td>
 </tr>
</table>
<?php } ?>
<?php } if ($totalRows_log > 0) { ?>
<table class="dataTable">
 <tr>
  <td class="dataHeading bdr1B">Entry Name</td>
  <td class="dataHeading bdr1B">Style</td>
  <?php if ($action != "print") { ?>
  <td colspan="3" class="dataHeading bdr1B">Actions</td>
  <?php } ?>
 </tr>
 <?php do { 
	mysql_select_db($database, $brewing);
	if ($row_log['brewCategory'] < 10) $fix = "0"; else $fix = "";
	$query_style = sprintf("SELECT * FROM styles WHERE brewStyleGroup = '%s' AND brewStyleNum = '%s'", $fix.$row_log['brewCategory'], $row_log['brewSubCategory']);
	$style = mysql_query($query_style, $brewing) or die(mysql_error());
	$row_style = mysql_fetch_assoc($style);
	$totalRows_style = mysql_num_rows($style);
	?>
 <tr <?php echo " style=\"background-color:$color\"";?>>
  <td class="dataList" width="25%"><?php echo $row_log['brewName']; ?></td>
  <td class="dataList" width="25%"><?php echo $row_log['brewCategory'].$row_log['brewSubCategory'].": ".$row_style['brewStyle']; ?></td>
  <?php if ($action != "print") { ?>
  <?php if (greaterDate($today,$deadline)) echo ""; else { ?>
  <td class="dataList" width="5%" nowrap="nowrap"> <span class="icon"><img src="images/pencil.png" align="absmiddle" border="0" alt="Edit <?php echo $row_log['brewName']; ?>" title="Edit <?php echo $row_log['brewName']; ?>"></span><a href="index.php?section=brew&action=edit&id=<?php echo $row_log['id']; ?>" title="Edit <?php echo $row_log['brewName']; ?>">Edit</a></td>
  <td class="dataList" width="5%" nowrap="nowrap"><span class="icon"><img src="images/bin_closed.png" align="absmiddle" border="0" alt="Delete <?php echo $row_log['brewName']; ?>" title="Delete <?php echo $row_log['brewName']; ?>?"></span><a href="javascript:DelWithCon('includes/process.inc.php?section=<?php echo $section; ?>&dbTable=brewing&action=delete','id',<?php echo $row_log['id']; ?>,'Are you sure you want to delete your entry called <?php echo str_replace("'", "\'", $row_log['brewName']); ?>? This cannot be undone.');" title="Delete <?php echo $row_log['brewName']; ?>?">Delete</a></td>
  <?php } ?>
  <td class="dataList"><span class="icon"><img src="images/printer.png" align="absmiddle" border="0" alt="Print the Entry Forms for <?php echo $row_log['brewName']; ?>" title="Print the Entry Forms for <?php echo $row_log['brewName']; ?>"></span><a class="thickbox" href="sections/entry.sec.php?id=<?php echo $row_log['id']; ?>&bid=<?php echo $row_log['brewBrewerID']; ?>&KeepThis=true&TB_iframe=true&height=425&width=700" title="Print the Entry Forms for <?php echo $row_log['brewName']; ?>">Print</a></td>
  <?php } ?>
 </tr>
  <?php if ($color == $color1) { $color = $color2; } else { $color = $color1; } ?>
  <?php } while ($row_log = mysql_fetch_assoc($log)); ?>
 <tr>
  <td colspan="5" class="dataHeading bdr1T"><?php if ($action != "print") { ?><span class="icon"><img src="images/money.png" align="absmiddle" border="0" alt="Entry Fees" title="Entry Fees"></span><span class="data"><?php } ?>Total Entry Fees: <?php echo $row_prefs['prefsCurrency']; echo $entry_total_final; ?><?php if ($action != "print") { ?></span><?php } if ($action != "print") { if ($row_contest_info['contestEntryFee'] > 0) { ?><span class="data"><a href="index.php?section=pay">Pay Entry Fees</a></span><?php } } ?>
  </td>
 </tr>
</table>
<?php 
} else echo "<p>You do not have any entries.</p>"; ?>
<h2>Info</h2>
<?php if ($action != "print") { ?>
<?php if (greaterDate($today,$deadline)) echo ""; else { ?>
<table class="dataTable">
	<tr>
    	<td class="dataList" width="5%" nowrap="nowrap"><span class="icon"><img src="images/user_edit.png" align="absmiddle" /></span><a class="data" href="index.php?<?php if ($row_brewer['id'] != "") echo "section=brewer&action=edit&id=".$row_brewer['id']; else echo "action=add&section=brewer&go=judge"; ?>">Edit Your Info</a></td>
    	<td class="dataList" width="5%" nowrap="nowrap"><span class="icon"><img src="images/email_edit.png" align="absmiddle" /></span><a class="data" href="index.php?section=user&action=username&id=<?php echo $row_user['id']; ?>">Change Email Address</a></td>
        <td class="dataList"><span class="icon"><img src="images/key.png" align="absmiddle" /></span><a class="data" href="index.php?section=user&action=password&id=<?php echo $row_user['id']; ?>">Change Password</a></td>
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
    <td class="dataLabel">Phone Numbers:</td>
    <td class="data"><?php  if ($row_brewer['brewerPhone1'] != "") echo $row_brewer['brewerPhone1']." (H) ";  if ($row_brewer['brewerPhone2'] != "") echo $row_brewer['brewerPhone2']." (W)"; ?></td>
  </tr>
  <tr>
    <td class="dataLabel">Club:</td>
    <td class="data"><?php if ($row_brewer['brewerClubs'] != "") echo $row_brewer['brewerClubs']; else echo "None entered"; ?></td>
  </tr>
  <tr>
    <td class="dataLabel">Judge?</td>
    <td class="data"><?php if ($row_brewer['brewerJudge'] != "") echo  $row_brewer['brewerJudge']; else echo "None entered"; ?></td>
  </tr>
  <tr>
    <td class="dataLabel">Steward?</td>
    <td class="data"><?php if ($row_brewer['brewerSteward'] != "") echo $row_brewer['brewerSteward']; else echo "None entered"; ?></td>
  </tr>
  <?php if ($row_brewer['brewerJudge'] == "Y") { ?>
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
    <td class="data"><?php if ($row_brewer['brewerJudgeLikes'] != "") echo $row_brewer['brewerJudgeLikes']; else echo "N/A"; ?></td>
  </tr>
  <tr>
    <td class="dataLabel">Catagories Not Preferred:</td>
    <td class="data"><?php if ($row_brewer['brewerJudgeDislikes'] != "") echo $row_brewer['brewerJudgeDislikes']; else echo "N/A"; ?></td>
  </tr>
  <?php } ?>
</table>
<?php } ?>