<?php if ($totalRows_log_winners > 0) { ?>
<h2>Winning Entries<?php if ($section == "past_winners") echo ": ".ltrim($dbTable, "brewing_"); ?></h2>
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
	//if ($row_log_winners['brewWinnerCat'] < 10) $fix = "0"; else $fix = "";
	$query_style = sprintf("SELECT * FROM styles WHERE brewStyleGroup = '%s' AND brewStyleNum = '%s'", $row_log_winners['brewWinnerCat'], $row_log_winners['brewWinnerSubCat']);
	$style = mysql_query($query_style, $brewing) or die(mysql_error());
	$row_style = mysql_fetch_assoc($style);
	
	$query_club = sprintf("SELECT brewerClubs FROM brewer WHERE id = '%s'", $row_log_winners['brewBrewerID']);
	$club = mysql_query($query_club, $brewing) or die(mysql_error());
	$row_club = mysql_fetch_assoc($club);
	?>
 <tr <?php echo " style=\"background-color:$color\"";?>>
  <td class="dataList" nowrap="nowrap"><span class="icon"><img src="images/<?php if ($row_log_winners['brewWinnerPlace'] == "1") echo "medal_gold_3"; elseif ($row_log_winners['brewWinnerPlace'] == "2") echo "medal_silver_3"; elseif ($row_log_winners['brewWinnerPlace'] == "3") echo "medal_bronze_3"; else echo "thumb_up"; ?>.png" align="absmiddle" /></span><?php echo $row_log_winners['brewWinnerPlace']; ?></td>
  <td class="dataList"><?php echo $styleConvert2; if ($row_log_winners['brewWinnerSubCat']!= "") { echo ": ".$row_style['brewStyle']." (".$row_log_winners['brewWinnerCat']; if ($row_log_winners['brewWinnerSubCat']!= "") echo $row_log_winners['brewSubCategory']; echo ")"; } ?></td>
  <td class="dataList"><?php echo $row_log_winners['brewBrewerFirstName']." ".$row_log_winners['brewBrewerLastName']; ?></td>
  <td class="dataList"><?php echo $row_log_winners['brewName']; ?></td>
  <!-- <td class="dataList"><?php //echo $row_club['brewerClubs']; ?></td> -->
 </tr>
  <?php if ($color == $color1) { $color = $color2; } else { $color = $color1; } ?>
  <?php } while ($row_log_winners = mysql_fetch_assoc($log_winners)); ?>
  <tr>
  	<td class="bdr1T" colspan="5">&nbsp;</td>
  </tr>
</table>
<?php if ($row_contest_info['contestWinnersComplete'] != "") echo $row_contest_info['contestWinnersComplete']; ?>
<?php } ?>