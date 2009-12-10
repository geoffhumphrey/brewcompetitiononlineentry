<?php if ($totalRows_bos > 0) { ?>
<h2>Best of Show Winners</h2>
<div class="bos">Congratulations to Best of Show Winner <?php echo $row_bos_winner['brewBrewerFirstName']." ".$row_bos_winner['brewBrewerLastName'];; ?>, whose entry, <em><?php echo $row_bos_winner['brewName']; ?></em>, garnered the top prize in the <?php echo $row_contest_info['contestName']; ?>!</div>
<?php if ($row_contest_info['contestBOSAward'] != "") echo $row_contest_info['contestBOSAward']; ?>
<table class="dataTable">
 <tr>
  <td class="dataHeading bdr1B" width="5%" nowrap="nowrap">Place</td>
  <td class="dataHeading bdr1B" width="25%">Category</td>
  <td class="dataHeading bdr1B" width="25%">Brewer</td>
  <td class="dataHeading bdr1B" width="25%">Entry Name</td>
  <!--<td class="dataHeading bdr1B">Club</td>-->
 </tr>
 <?php do { 
    include ('includes/style_convert.inc.php');
	mysql_select_db($database, $brewing);
	//if ($row_log_bos['brewWinnerCat'] < 10) $fix = "0"; else $fix = "";
	$query_style = sprintf("SELECT * FROM styles WHERE brewStyleGroup = '%s' AND brewStyleNum = '%s'", $row_bos['brewWinnerCat'], $row_bos['brewWinnerSubCat']);
	$style = mysql_query($query_style, $brewing) or die(mysql_error());
	$row_style = mysql_fetch_assoc($style);
	
	$query_club = sprintf("SELECT brewerClubs FROM brewer WHERE id = '%s'", $row_bos['brewBrewerID']);
	$club = mysql_query($query_club, $brewing) or die(mysql_error());
	$row_club = mysql_fetch_assoc($club);
	?>
 <tr <?php echo " style=\"background-color:$color\"";?>>
  <td class="dataList" nowrap="nowrap"><span class="icon"><img src="images/<?php if ($row_bos['brewBOSPlace'] == "1") echo "medal_gold_3"; elseif ($row_bos['brewBOSPlace'] == "2") echo "medal_silver_3"; elseif ($row_bos['brewBOSPlace'] == "3") echo "medal_bronze_3"; else echo "thumb_up"; ?>.png" align="absmiddle" /></span><?php echo $row_bos['brewBOSPlace']; ?></td>
  <td class="dataList"><?php echo $styleConvert3; if ($row_bos['brewWinnerSubCat']!= "") { echo ": ".$row_style['brewStyle']." (".$row_bos['brewWinnerCat']; if ($row_bos['brewWinnerSubCat']!= "") echo $row_bos['brewSubCategory']; echo ")"; } ?></td>
  <td class="dataList"><?php echo $row_bos['brewBrewerFirstName']." ".$row_bos['brewBrewerLastName']; ?></td>
  <td class="dataList"><?php echo $row_bos['brewName']; ?></td>
  <!--<td class="dataList"><?php // echo $row_club['brewerClubs']; ?></td> -->
 </tr>
  <?php if ($color == $color1) { $color = $color2; } else { $color = $color1; } ?>
  <?php } while ($row_bos = mysql_fetch_assoc($bos)); ?>
  <tr>
  	<td class="bdr1T" colspan="5">&nbsp;</td>
  </tr>
</table>
<?php } ?>
