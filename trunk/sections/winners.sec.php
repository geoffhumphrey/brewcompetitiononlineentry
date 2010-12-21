<?php

if (($judgingDateReturn == "true") || ($dbTable != "default")) { // check if all judging dates have past, if so, display 
	if ($totalRows_log_winners > 0) { // if winners have been designated, display 
	
	
?>
<h2>Winning Entries<?php if ($section == "past_winners") echo ": ".ltrim($dbTable, "brewing_"); ?></h2>
<script type="text/javascript" language="javascript" src="js_includes/jquery.js"></script>
<script type="text/javascript" language="javascript" src="js_includes/jquery.dataTables.js"></script>
<script type="text/javascript" language="javascript">
	 $(document).ready(function() {
		$('#sortable').dataTable( {
			"bPaginate" : false,
			"sDom": 'rt',
			"bStateSave" : false,
			"bLengthChange" : false,
			"aaSorting": [[1,'asc'],[0,'asc']],
			"aoColumns": [
				{ "aaSorting": [[1,'asc'],[0,'asc']] },
				null,
				{ "aaSorting": [[2,'asc'],[1,'asc'],[0,'asc']] },
				null,
				null,
				]
			} );
		} );
</script>
<table class="dataTable" id="sortable">
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
	//if ($row_log_winners['brewWinnerCat'] < 10) $fix = "0"; else $fix = "";
	$query_style = sprintf("SELECT * FROM styles WHERE brewStyleGroup = '%s' AND brewStyleNum = '%s'", $row_log_winners['brewWinnerCat'], $row_log_winners['brewWinnerSubCat']);
	$style = mysql_query($query_style, $brewing) or die(mysql_error());
	$row_style = mysql_fetch_assoc($style);
	
	$query_user1 = sprintf("SELECT * FROM %s WHERE id = '%s'", $user_table, $row_log_winners['brewBrewerID']);
	$user1 = mysql_query($query_user1, $brewing) or die(mysql_error());
	$row_user1 = mysql_fetch_assoc($user1);
	
	$query_club = sprintf("SELECT brewerClubs FROM %s WHERE brewerEmail = '%s'", $brewer_table, $row_user1['user_name']);
	$club = mysql_query($query_club, $brewing) or die(mysql_error());
	$row_club = mysql_fetch_assoc($club);
	?>
 <tr>
  <td class="dataList" nowrap="nowrap"><span class="icon"><img src="images/<?php if ($row_log_winners['brewWinnerPlace'] == "1") echo "medal_gold_3"; elseif ($row_log_winners['brewWinnerPlace'] == "2") echo "medal_silver_3"; elseif ($row_log_winners['brewWinnerPlace'] == "3") echo "medal_bronze_3"; else echo "thumb_up"; ?>.png"  /></span><?php echo $row_log_winners['brewWinnerPlace']; ?></td>
  <td class="dataList">
  <?php 
  echo $row_log_winners['brewWinnerCat']; if ($row_log_winners['brewWinnerSubCat']!= "") echo $row_log_winners['brewSubCategory'];$styleConvert2; if ($row_log_winners['brewWinnerSubCat']!= "") { echo ": ".$row_style['brewStyle']; } 
  if ($row_log_winners['brewWinnerCat'] >= 29)
  {
	$query_style = sprintf("SELECT * FROM styles WHERE brewStyleGroup='%s'", $row_log_winners['brewWinnerCat']);  
    $style = mysql_query($query_style, $brewing) or die(mysql_error());
	$row_style = mysql_fetch_assoc($style);
	echo ": ".$row_style['brewStyle'];
  } 
  ?></td>
  <td class="dataList"><?php echo $row_log_winners['brewBrewerLastName'].", ".$row_log_winners['brewBrewerFirstName'];  if ($row_log['brewCoBrewer'] != "") echo "<br>Co-Brewer: ".$row_log['brewCoBrewer']; ?></td>
  <td class="dataList"><?php echo $row_log_winners['brewName']; ?></td>
  <td class="dataList"><?php echo $row_club['brewerClubs']; ?></td>
 </tr>
  <?php if ($color == $color1) { $color = $color2; } else { $color = $color1; } ?>
  <?php } while ($row_log_winners = mysql_fetch_assoc($log_winners)); ?>
</tbody>
</table>
<?php if ($row_contest_info['contestWinnersComplete'] != "") echo $row_contest_info['contestWinnersComplete'];  
	} // end if winners are logged
} // end of judging date check
?>