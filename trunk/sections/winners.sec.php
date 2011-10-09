<?php
/**
 * Module:      winners.sec.php 
 * Description: This module displays the winners entered into the database.
 * 
 */

?>
<h2>Winning Entries<?php if ($section == "past_winners") echo ": ".$trimmed; if ($row_scores['count'] > 0) { if (($section == "default") && ($row_prefs['prefsCompOrg'] == "Y") && ($action != "print")){ ?><span class="icon">&nbsp;<a href="output/results_download.php?section=admin&amp;go=judging_scores&amp;action=default&amp;filter=none&amp;view=pdf"><img src="images/page_white_acrobat.png" border="0" title="Download a PDF of the Winners List"/></a></span><span class="icon"><a href="output/results_download.php?section=admin&amp;go=judging_scores&amp;action=download&amp;filter=default&amp;view=html"><img src="images/html.png" border="0" title="Download the Winners List in HTML format"/></a></span><?php } ?></h2>
<?php 
// If using BCOE for comp organization, display winners by table
if ($row_prefs['prefsCompOrg'] == "Y") { 
// Display all winners ?>
<?php
	do { 
	$entry_count = get_table_info(1,"count_total",$row_tables['id'],$dbTable,"default");
	if  (score_count($row_tables['id'],"1")) {
	?>
	<h3>Table <?php echo $row_tables['tableNumber'].": ".$row_tables['tableName']." (".$entry_count." Entries)"; ?></h3>
    <?php if ($entry_count > 0) { ?>
     <script type="text/javascript" language="javascript">
	 $(document).ready(function() {
		$('#sortable<?php echo $row_tables['id']; ?>').dataTable( {
			"bPaginate" : false,
			"sDom": 'rt',
			"bStateSave" : true,
			"bLengthChange" : false,
			"aaSorting": [[0,'asc']],
			"bProcessing" : false,
			"aoColumns": [
				{ "asSorting": [  ] },
				{ "asSorting": [  ] },
				{ "asSorting": [  ] },
				{ "asSorting": [  ] },
				{ "asSorting": [  ] }
				]
			} );
		} );
	</script>
    <table class="dataTable" id="sortable<?php echo $row_tables['id']; ?>">
    <thead>
	<tr>
    	<th class="dataList bdr1B" width="1%" nowrap="nowrap">Place</th>
        <th class="dataList bdr1B" width="25%" nowrap="nowrap">Brewer(s)</th>
        <th class="dataList bdr1B" width="25%" nowrap="nowrap">Entry Name</th>
        <th class="dataList bdr1B" width="25%" nowrap="nowrap">Style</th>
        <th class="dataList bdr1B">Club</th>
    </tr>
</thead>
    <tbody>
    <?php 
		$query_scores = sprintf("SELECT * FROM %s WHERE scoreTable='%s'", $scores_db_table, $row_tables['id']);
		$query_scores .= " AND (scorePlace='1' OR scorePlace='2' OR scorePlace='3' OR scorePlace='4' OR scorePlace='5')";	
		$scores = mysql_query($query_scores, $brewing) or die(mysql_error());
		$row_scores = mysql_fetch_assoc($scores);
		$totalRows_scores = mysql_num_rows($scores);
		
		do { 
			$query_entries = sprintf("SELECT id,brewBrewerID,brewCoBrewer,brewName,brewStyle,brewCategorySort,brewCategory,brewSubCategory,brewBrewerFirstName,brewBrewerLastName FROM  $brewing_db_table WHERE id='%s'", $row_scores['eid']);
			$entries = mysql_query($query_entries, $brewing) or die(mysql_error());
			$row_entries = mysql_fetch_assoc($entries);
			$style = $row_entries['brewCategory'].$row_entries['brewSubCategory'];
			
			$query_brewer = sprintf("SELECT id,brewerClubs FROM $brewer_db_table WHERE uid='%s'", $row_entries['brewBrewerID']);
			$brewer = mysql_query($query_brewer, $brewing) or die(mysql_error());
			$row_brewer = mysql_fetch_assoc($brewer);
			
			$style = $row_entries['brewCategory'].$row_entries['brewSubCategory'];
		
			$query_styles = sprintf("SELECT brewStyle FROM styles WHERE id='%s'", $value);
			$styles = mysql_query($query_styles, $brewing) or die(mysql_error());
			$row_styles = mysql_fetch_assoc($styles);
	?>
    <tr>
        <td class="data"><?php echo display_place($row_scores['scorePlace'],1); ?></td>
        <td class="data"><?php echo $row_entries['brewBrewerFirstName']." ".$row_entries['brewBrewerLastName']; if ($row_entries['brewCoBrewer'] != "") echo "<br>Co-Brewer: ".$row_entries['brewCoBrewer']; ?></td>
        <td class="data"><?php echo $row_entries['brewName']; ?></td>
        <td class="data"><?php echo $style.": ".$row_entries['brewStyle']; ?></td>
        <td class="data"><?php echo $row_brewer['brewerClubs']; ?></td>
    </tr>
    <?php 
			mysql_free_result($styles);
			mysql_free_result($entries);
		} while ($row_scores = mysql_fetch_assoc($scores)); ?>
    </tbody>
    </table>
    <?php } 
	}
	?>
<?php } while ($row_tables = mysql_fetch_assoc($tables)); ?>
<?php } 


// if NOT using BCOE to organize comp and if winners have been designated, display using legacy code
if (($totalRows_log_winners > 0) && ($row_prefs['prefsCompOrg'] == "N")) { 
?>
<script type="text/javascript" language="javascript">
	 $(document).ready(function() {
		$('#sortable').dataTable( {
			"bPaginate" : false,
			"sDom": 'rt',
			"bStateSave" : true,
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
	mysql_select_db($database, $brewing);
	//if ($row_log_winners['brewWinnerCat'] < 10) $fix = "0"; else $fix = "";
	$query_style = sprintf("SELECT * FROM styles WHERE brewStyleGroup = '%s' AND brewStyleNum = '%s'", $row_log_winners['brewWinnerCat'], $row_log_winners['brewWinnerSubCat']);
	$style = mysql_query($query_style, $brewing) or die(mysql_error());
	$row_style = mysql_fetch_assoc($style);
	
	$query_user1 = sprintf("SELECT * FROM %s WHERE id = '%s'", $users_db_table, $row_log_winners['brewBrewerID']);
	$user1 = mysql_query($query_user1, $brewing) or die(mysql_error());
	$row_user1 = mysql_fetch_assoc($user1);
	
	$query_club = sprintf("SELECT brewerClubs FROM %s WHERE brewerEmail = '%s'", $brewer_db_table, $row_user1['user_name']);
	$club = mysql_query($query_club, $brewing) or die(mysql_error());
	$row_club = mysql_fetch_assoc($club);
	?>
 <tr>
  <td class="dataList" nowrap="nowrap"><?php echo $row_log_winners['brewWinnerPlace']; ?></td>
  <td class="dataList">
  <?php 
  echo style_convert($row_log_winners['brewWinnerCat'], 1);  if ($row_log_winners['brewWinnerSubCat']!= "") { echo ": ".$row_style['brewStyle']." (".$row_log_winners['brewWinnerCat']; if ($row_log_winners['brewWinnerSubCat']!= "") echo $row_log_winners['brewSubCategory']; echo ")";  } 
  if ($row_log_winners['brewWinnerCat'] >= 29)
  {
	$query_style = sprintf("SELECT * FROM styles WHERE brewStyleGroup='%s'", $row_log_winners['brewWinnerCat']);  
    $style = mysql_query($query_style, $brewing) or die(mysql_error());
	$row_style = mysql_fetch_assoc($style);
	echo ": ".$row_style['brewStyle'];
  } 
  ?>
  </td>
  <td class="dataList"><?php echo $row_log_winners['brewBrewerLastName'].", ".$row_log_winners['brewBrewerFirstName'];  if ($row_log['brewCoBrewer'] != "") echo "<br>Co-Brewer: ".$row_log['brewCoBrewer']; ?></td>
  <td class="dataList"><?php echo $row_log_winners['brewName']; ?></td>
  <td class="dataList"><?php echo $row_club['brewerClubs']; ?></td>
 </tr>
  <?php if ($color == $color1) { $color = $color2; } else { $color = $color1; } ?>
  <?php } while ($row_log_winners = mysql_fetch_assoc($log_winners)); ?>
</tbody>
</table>
<?php if ($row_contest_info['contestWinnersComplete'] != "") echo $row_contest_info['contestWinnersComplete'];  
	} // end if (($totalRows_log_winners > 0) && ($row_prefs['prefsCompOrg'] == "N"))	
} else echo "</h2><p>No winners have been entered yet. Please check back later.</p>";
?>