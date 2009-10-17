<?php if ($_SESSION["loginUsername"] != $row_user['user_name']) { ?>
<p>Please <a href="index.php?section=login">log in</a> or <a href="index.php?section=register">register</a> to view your list of brews entered into the <?php echo $row_contest_info['contestName']; ?> organized by <?php echo $row_contest_info['contestHost']; ?>, <?php echo $row_contest_info['contestHostLocation']; ?>.</p> 
<?php } else { ?>
<div id="header">
	<div id="header-inner"><h1>Your List of Entries and Brewer Info</h1></div>
</div>
<?php 
if ($msg == "1") echo "<div class=\"error\">Information added successfully.</div>"; 
if ($msg == "2") echo "<div class=\"error\">Information edited successfully.</div>"; 
if ($msg == "3") echo "<div class=\"error\">Your email address has been updated.</div>"; 
if ($msg == "4") echo "<div class=\"error\">Your password has been updated.</div>"; 
if ($msg == "5") echo "<div class=\"error\">Information deleted successfully.</div>"; 
if ($msg == "6") echo "<div class=\"error\">You should verify all your entries imported using BeerXML.</div>"; 
?>
<p>Thank you for entering the <?php echo $row_contest_info['contestName']; ?> homebrew contest, <?php echo $row_name['brewerFirstName']; ?>. Below is a list of your entires where you can edit or delete any or all of them. Be sure to print recipe sheets and bottle labels for each of your entries.</p>

<table>
 <tr>
   <td><img src="images/book_add.png" align="absmiddle" /><a class="data" href="index.php?section=brew">Add an Entry</a></td>
   <td><img src="images/page_code.png" align="absmiddle" /><a class="data" href="index.php?section=beerxml">Import an Entry Using BeerXML</a></td>
 </tr>
</table>
<br />
<?php if ($totalRows_log > 0) { ?>
<table>
 <tr>
  <td class="dataHeading bdr1B">Entry Name</td>
  <td class="dataHeading bdr1B">Style</td>
  <td colspan="3" class="dataHeading bdr1B">Actions</td>
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
  <td class="dataList"><?php echo $row_log['brewName']; ?></td>
  <td class="dataList"><?php echo $row_log['brewCategory'].$row_log['brewSubCategory'].": ".$row_style['brewStyle']; ?></td>
  <td class="icon" nowrap><img src="images/pencil.png" align="absmiddle" border="0" alt="Edit <?php echo $row_log['brewName']; ?>" title="Edit <?php echo $row_log['brewName']; ?>"><br /><a href="index.php?section=brew&action=edit&id=<?php echo $row_log['id']; ?>" title="Edit <?php echo $row_log['brewName']; ?>">Edit</a></td>
  <td class="icon" nowrap><img src="images/bin_closed.png" align="absmiddle" border="0" alt="Delete <?php echo $row_log['brewName']; ?>" title="Delete <?php echo $row_log['brewName']; ?>?"><br /><a href="javascript:DelWithCon('includes/process.inc.php?section=<?php echo $section; ?>&dbTable=brewing&action=delete','id',<?php echo $row_log['id']; ?>,'Are you sure you want to delete your entry called <?php echo str_replace("'", "\'", $row_log['brewName']); ?>? This cannot be undone.');" title="Delete <?php echo $row_log['brewName']; ?>?">Delete</a></td>
  <td class="icon" nowrap><img src="images/printer.png" align="absmiddle" border="0" alt="Print the Entry Forms for <?php echo $row_log['brewName']; ?>" title="Print the Entry Forms for <?php echo $row_log['brewName']; ?>"><br /><a class="thickbox" href="sections/entry.sec.php?id=<?php echo $row_log['id']; ?>&KeepThis=true&TB_iframe=true&height=425&width=700" title="Print the Entry Forms for <?php echo $row_log['brewName']; ?>">Print</a></td>
 </tr>
  <?php if ($color == $color1) { $color = $color2; } else { $color = $color1; } ?>
  <?php } while ($row_log = mysql_fetch_assoc($log)); ?>
 <tr>
  <td colspan="5" align="right" class="dataHeading bdr1T">Total Entry Fees: <?php echo $row_prefs['prefsCurrency']; echo ($totalRows_log * $row_contest_info['contestEntryFee']); if ($row_contest_info['contestEntryFee'] > 0) { ?>&nbsp;&nbsp;&nbsp;<a href="index.php?section=pay">Pay Entry Fees</a><?php } ?>
</table>
<?php } else echo "<p>You have not entered any brews yet.</p>"; ?>
<br /><br />
<table>
	<tr>
    	<td><img src="images/user_edit.png" align="absmiddle" /><a class="data" href="index.php?section=brewer&action=edit&id=<?php echo $row_brewer['id']; ?>">Edit Your Info</a></td>
    	<td><img src="images/email_edit.png" align="absmiddle" /><a class="data" href="index.php?section=user&action=username&id=<?php echo $row_user['id']; ?>">Change Email Address</a></td>
        <td><img src="images/key.png" align="absmiddle" /><a class="data" href="index.php?section=user&action=password&id=<?php echo $row_user['id']; ?>">Change Password</a></td>
    </tr>
</table>
<br />
<table border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td class="dataLabel">Name:</td>
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
    <td class="dataLabel">Steward?</td>
    <td class="data"><?php if ($row_brewer['brewerSteward'] != "") echo $row_brewer['brewerSteward']; else echo "None entered"; ?></td>
  </tr>
  <tr>
    <td class="dataLabel">Judge?</td>
    <td class="data"><?php if ($row_brewer['brewerJudge'] != "") echo  $row_brewer['brewerJudge']; else echo "None entered"; ?></td>
  </tr>
  <tr>
    <td class="dataLabel">BJCP Judge ID:</td>
    <td class="data"><?php  if ($row_brewer['brewerJudgeID'] != "") echo $row_brewer['brewerJudgeID']; else echo "None entered"; ?></td>
  </tr>
  <tr>
    <td class="dataLabel">BJCP Rank:</td>
    <td class="data"><?php  if ($row_brewer['brewerJudgeRank'] != "") echo  $row_brewer['brewerJudgeRank']; else echo "None entered"; ?></td>
  </tr>
  <tr>
    <td class="dataLabel">Categories Preferred:</td>
    <td class="data"><?php if ($row_brewer['brewerJudgeLikes'] != "") echo $row_brewer['brewerJudgeLikes']; else echo "None entered"; ?></td>
  </tr>
  <tr>
    <td class="dataLabel">Catagories Not Preferred:</td>
    <td class="data"><?php if ($row_brewer['brewerJudgeDislikes'] != "") echo $row_brewer['brewerJudgeDislikes']; else echo "None entered"; ?></td>
  </tr>
</table>
<?php } ?>