<?php if ($totalRows_bos > 0) { ?>
<h2>Best of Show Winners<?php if ($section == "past_winners") echo ": ".ltrim($dbTable, "brewing_"); ?></h2>
<?php if (($row_prefs['prefsBOSCider'] == "Y") || ($row_prefs['prefsBOSMead'] == "Y")) echo "<h3>Beer Categories</h3>"; ?>
<div class="bos">Congratulations to <?php if (($row_prefs['prefsBOSCider'] == "Y") || ($row_prefs['prefsBOSMead'] == "Y")) echo "Beer"; ?> Best of Show Winner <?php echo $row_bos_winner['brewBrewerFirstName']." ".$row_bos_winner['brewBrewerLastName'];; ?>, whose entry, <em><?php echo $row_bos_winner['brewName']; ?></em>, garnered the top <?php if (($row_prefs['prefsBOSCider'] == "Y") || ($row_prefs['prefsBOSMead'] == "Y")) echo "beer"; ?> prize in the <?php echo $row_contest_info['contestName']; ?>!</div>
<?php if ($row_contest_info['contestBOSAward'] != "") echo $row_contest_info['contestBOSAward']; ?>
<table class="dataTable">
<thead>
 <tr>
  <th class="dataHeading bdr1B" width="5%" nowrap="nowrap">Place</th>
  <th class="dataHeading bdr1B" width="25%">Category</th>
  <th class="dataHeading bdr1B" width="25%">Brewer</th>
  <th class="dataHeading bdr1B" width="25%">Entry Name</th>
  <th class="dataHeading bdr1B">Club</th>
 </tr>
</thead>
<tbody>
 <?php do { 
    include ('includes/style_convert.inc.php');
	mysql_select_db($database, $brewing);
	//if ($row_log_bos['brewWinnerCat'] < 10) $fix = "0"; else $fix = "";
	$query_style = sprintf("SELECT * FROM styles WHERE brewStyleGroup = '%s' AND brewStyleNum = '%s'", $row_bos['brewWinnerCat'], $row_bos['brewWinnerSubCat']);
	$style = mysql_query($query_style, $brewing) or die(mysql_error());
	$row_style = mysql_fetch_assoc($style);
	
	$query_user1 = sprintf("SELECT * FROM users WHERE id = '%s'", $row_bos['brewBrewerID']);
	$user1 = mysql_query($query_user1, $brewing) or die(mysql_error());
	$row_user1 = mysql_fetch_assoc($user1);
	
	$query_club = sprintf("SELECT brewerClubs FROM brewer WHERE brewerEmail = '%s'", $row_user1['user_name']);
	$club = mysql_query($query_club, $brewing) or die(mysql_error());
	$row_club = mysql_fetch_assoc($club);
	?>
 <tr <?php echo " style=\"background-color:$color\"";?>>
  <td class="dataList" nowrap="nowrap"><span class="icon"><img src="images/<?php if ($row_bos['brewBOSPlace'] == "1") echo "medal_gold_3"; elseif ($row_bos['brewBOSPlace'] == "2") echo "medal_silver_3"; elseif ($row_bos['brewBOSPlace'] == "3") echo "medal_bronze_3"; else echo "thumb_up"; ?>.png"  /></span><?php echo $row_bos['brewBOSPlace']; ?></td>
  <td class="dataList"><?php echo $styleConvert3; if ($row_bos['brewWinnerSubCat']!= "") { echo ": ".$row_style['brewStyle']." (".$row_bos['brewWinnerCat']; if ($row_bos['brewWinnerSubCat']!= "") echo $row_bos['brewSubCategory']; echo ")"; } ?></td>
  <td class="dataList"><?php echo $row_bos['brewBrewerFirstName']." ".$row_bos['brewBrewerLastName']; ?></td>
  <td class="dataList"><?php echo $row_bos['brewName']; ?></td>
  <td class="dataList"><?php echo $row_club['brewerClubs']; ?></td>
 </tr>
  <?php if ($color == $color1) { $color = $color2; } else { $color = $color1; } ?>
  <?php } while ($row_bos = mysql_fetch_assoc($bos)); ?>
  <tr>
  	<td class="bdr1T" colspan="5">&nbsp;</td>
  </tr>
</tbody>
</table>
<?php } ?>

<?php if (($row_prefs['prefsBOSMead'] == "Y") && ($totalRows_bos3 > 0)) { ?>
<h3>Mead Categories</h3>
<?php if ($totalRows_bos_winner3 > 0) { ?>
<div class="bos">Congratulations to Mead Best of Show Winner <?php echo $row_bos_winner3['brewBrewerFirstName']." ".$row_bos_winner3['brewBrewerLastName'];; ?>, whose entry, <em><?php echo $row_bos_winner3['brewName']; ?></em>, garnered the top mead prize in the <?php echo $row_contest_info['contestName']; ?>!</div>
<?php } ?>
<table class="dataTable">
<thead>
 <tr>
  <th class="dataHeading bdr1B" width="5%" nowrap="nowrap">Place</th>
  <th class="dataHeading bdr1B" width="25%">Category</th>
  <th class="dataHeading bdr1B" width="25%">Brewer</th>
  <th class="dataHeading bdr1B" width="25%">Entry Name</th>
  <th class="dataHeading bdr1B">Club</th>
 </tr>
</thead>
<tbody>
 <?php do { 
    include ('includes/style_convert.inc.php');
	mysql_select_db($database, $brewing);
	//if ($row_log_bos['brewWinnerCat'] < 10) $fix = "0"; else $fix = "";
	$query_style = sprintf("SELECT * FROM styles WHERE brewStyleGroup = '%s' AND brewStyleNum = '%s'", $row_bos3['brewWinnerCat'], $row_bos3['brewWinnerSubCat']);
	$style = mysql_query($query_style, $brewing) or die(mysql_error());
	$row_style = mysql_fetch_assoc($style);
	
	$query_user1 = sprintf("SELECT * FROM users WHERE id = '%s'", $row_bos3['brewBrewerID']);
	$user1 = mysql_query($query_user1, $brewing) or die(mysql_error());
	$row_user1 = mysql_fetch_assoc($user1);
	
	$query_club = sprintf("SELECT brewerClubs FROM brewer WHERE brewerEmail = '%s'", $row_user1['user_name']);
	$club = mysql_query($query_club, $brewing) or die(mysql_error());
	$row_club = mysql_fetch_assoc($club);
	?>
 <tr <?php echo " style=\"background-color:$color\"";?>>
  <td class="dataList" nowrap="nowrap"><span class="icon"><img src="images/<?php if ($row_bos3['brewBOSPlace'] == "1") echo "medal_gold_3"; elseif ($row_bos3['brewBOSPlace'] == "2") echo "medal_silver_3"; elseif ($row_bos3['brewBOSPlace'] == "3") echo "medal_bronze_3"; else echo "thumb_up"; ?>.png"  /></span><?php echo $row_bos3['brewBOSPlace']; ?></td>
  <td class="dataList"><?php echo $styleConvert6; if ($row_bos3['brewWinnerSubCat']!= "") { echo ": ".$row_style['brewStyle']." (".$row_bos3['brewWinnerCat']; if ($row_bos3['brewWinnerSubCat']!= "") echo $row_bos3['brewSubCategory']; echo ")"; } ?></td>
  <td class="dataList"><?php echo $row_bos3['brewBrewerFirstName']." ".$row_bos3['brewBrewerLastName']; ?></td>
  <td class="dataList"><?php echo $row_bos3['brewName']; ?></td>
  <td class="dataList"><?php echo $row_club['brewerClubs']; ?></td>
 </tr>
  <?php if ($color == $color1) { $color = $color2; } else { $color = $color1; } ?>
  <?php } while ($row_bos3 = mysql_fetch_assoc($bos3)); ?>
  <tr>
  	<td class="bdr1T" colspan="5">&nbsp;</td>
  </tr>
</tbody>
</table>
<?php } // end if BOS cider ?>

<?php if (($row_prefs['prefsBOSCider'] == "Y") && ($totalRows_bos2 > 0)) { ?>
<h3>Cider Categories</h3>
<?php if ($totalRows_bos_winner2 > 0) { ?>
<div class="bos">Congratulations to Cider Best of Show Winner <?php echo $row_bos_winner2['brewBrewerFirstName']." ".$row_bos_winner2['brewBrewerLastName']; ?>, whose entry, <em><?php echo $row_bos_winner2['brewName']; ?></em>, garnered the top cider prize in the <?php echo $row_contest_info['contestName']; ?>!</div>
<?php } ?>
<table class="dataTable">
<thead>
 <tr>
  <th class="dataHeading bdr1B" width="5%" nowrap="nowrap">Place</th>
  <th class="dataHeading bdr1B" width="25%">Category</th>
  <th class="dataHeading bdr1B" width="25%">Brewer</th>
  <th class="dataHeading bdr1B" width="25%">Entry Name</th>
  <th class="dataHeading bdr1B">Club</th>
 </tr>
</thead>
<tbody>
 <?php do { 
    include ('includes/style_convert.inc.php');
	mysql_select_db($database, $brewing);
	//if ($row_log_bos['brewWinnerCat'] < 10) $fix = "0"; else $fix = "";
	$query_style = sprintf("SELECT * FROM styles WHERE brewStyleGroup = '%s' AND brewStyleNum = '%s'", $row_bos2['brewWinnerCat'], $row_bos2['brewWinnerSubCat']);
	$style = mysql_query($query_style, $brewing) or die(mysql_error());
	$row_style = mysql_fetch_assoc($style);
	
	$query_user1 = sprintf("SELECT * FROM users WHERE id = '%s'", $row_bos2['brewBrewerID']);
	$user1 = mysql_query($query_user1, $brewing) or die(mysql_error());
	$row_user1 = mysql_fetch_assoc($user1);
	
	$query_club = sprintf("SELECT brewerClubs FROM brewer WHERE brewerEmail = '%s'", $row_user1['user_name']);
	$club = mysql_query($query_club, $brewing) or die(mysql_error());
	$row_club = mysql_fetch_assoc($club);
	?>
 <tr <?php echo " style=\"background-color:$color\"";?>>
  <td class="dataList" nowrap="nowrap"><span class="icon"><img src="images/<?php if ($row_bos2['brewBOSPlace'] == "1") echo "medal_gold_3"; elseif ($row_bos2['brewBOSPlace'] == "2") echo "medal_silver_3"; elseif ($row_bos2['brewBOSPlace'] == "3") echo "medal_bronze_3"; else echo "thumb_up"; ?>.png"  /></span><?php echo $row_bos2['brewBOSPlace']; ?></td>
  <td class="dataList"><?php echo $styleConvert7; if ($row_bos2['brewWinnerSubCat']!= "") { echo ": ".$row_style['brewStyle']." (".$row_bos2['brewWinnerCat']; if ($row_bos2['brewWinnerSubCat']!= "") echo $row_bos2['brewSubCategory']; echo ")"; } ?></td>
  <td class="dataList"><?php echo $row_bos2['brewBrewerFirstName']." ".$row_bos2['brewBrewerLastName']; ?></td>
  <td class="dataList"><?php echo $row_bos2['brewName']; ?></td>
  <td class="dataList"><?php echo $row_club['brewerClubs']; ?></td>
 </tr>
  <?php if ($color == $color1) { $color = $color2; } else { $color = $color1; } ?>
  <?php } while ($row_bos2 = mysql_fetch_assoc($bos2)); ?>
  <tr>
  	<td class="bdr1T" colspan="5">&nbsp;</td>
  </tr>
</tbody>
</table>
<?php } // end if BOS cider ?>