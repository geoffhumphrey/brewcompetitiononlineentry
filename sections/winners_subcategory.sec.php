<?php
/**
 * Module:      winners_subcategory.sec.php 
 * Description: This module displays the winners entered into the database.
 *              Displays by style subcategory.
 * 
 */
 
$query_styles = "SELECT brewStyleGroup,brewStyleNum,brewStyle FROM $styles_db_table WHERE brewStyleActive='Y' ORDER BY brewStyleGroup,brewStyleNum ASC";
$styles = mysql_query($query_styles, $brewing) or die(mysql_error());
$row_styles = mysql_fetch_assoc($styles);
$totalRows_styles = mysql_num_rows($styles);
do { $style[] = $row_styles['brewStyleGroup']."-".$row_styles['brewStyleNum']."-".$row_styles['brewStyle']; } while ($row_styles = mysql_fetch_assoc($styles));
// If using BCOE for comp organization, display winners by table
if ($row_prefs['prefsCompOrg'] == "Y") { 
	foreach (array_unique($style) as $style) {
		$style = explode("-",$style);
		$query_entry_count = sprintf("SELECT COUNT(*) as 'count' FROM %s WHERE brewCategorySort='%s' AND brewSubCategory='%s' AND brewReceived='Y'", $brewing_db_table,  $style[0], $style[1]);
		$entry_count = mysql_query($query_entry_count, $brewing) or die(mysql_error());
		$row_entry_count = mysql_fetch_assoc($entry_count);
		
		$query_score_count = sprintf("SELECT  COUNT(*) as 'count' FROM %s a, %s b, %s c WHERE b.brewCategorySort='%s' AND b.brewSubCategory='%s' AND a.eid = b.id AND a.scorePlace IS NOT NULL AND c.id = b.brewBrewerID", $judging_scores_db_table, $brewing_db_table, $brewer_db_table, $style[0], $style[1]);
		$score_count = mysql_query($query_score_count, $brewing) or die(mysql_error());
		$row_score_count = mysql_fetch_assoc($score_count);
		
		
		//echo $row_score_count['count'];
		//echo $query_score_count;
		// Display all winners 
		if ($row_entry_count['count'] > 0) {
?>
	<h3>Category <?php echo ltrim($style[0],"0").$style[1].": ".$style[2]." (".$row_entry_count['count']." entries)"; ?></h3>
    <?php if ($row_score_count['count'] > 0) { ?>
     <script type="text/javascript" language="javascript">
	 $(document).ready(function() {
		$('#sortable<?php echo $style[0].$style[1]; ?>').dataTable( {
			"bPaginate" : false,
			"sDom": 'rt',
			"bStateSave" : false,
			"bLengthChange" : false,
			"aaSorting": [[0,'asc']<?php if ($filter == "scores") { ?>,[5,'desc']<?php } ?>],
			"bProcessing" : false,
			"aoColumns": [
				{ "asSorting": [  ] },
				{ "asSorting": [  ] },
				{ "asSorting": [  ] },
				{ "asSorting": [  ] },
				{ "asSorting": [  ] }<?php if ($filter == "scores") { ?>,
				{ "asSorting": [  ] }
				<?php } ?>
				]
			} );
		} );
	</script>
    <table class="dataTable" id="sortable<?php echo $style[0].$style[1]; ?>">
    <thead>
	<tr>
    	<th class="dataList bdr1B" width="1%" nowrap="nowrap">Place</th>
        <th class="dataList bdr1B" width="25%">Brewer(s)</th>
        <th class="dataList bdr1B" width="25%">Entry Name</th>
        <th class="dataList bdr1B" width="25%">Style</th>
        <th class="dataList bdr1B">Club</th>
        <?php if ($filter == "scores") { ?>
        <th class="dataList bdr1B" width="1%" nowrap="nowrap">Score</th>
        <?php } ?>
    </tr>
</thead>
    <tbody>
    <?php 
		$query_scores = sprintf("SELECT a.scorePlace, a.scoreEntry, b.brewName, b.brewCategory, b.brewCategorySort, b.brewSubCategory, b.brewStyle, b.brewCoBrewer, c.brewerLastName, c.brewerFirstName, c.brewerClubs FROM %s a, %s b, %s c WHERE b.brewCategorySort='%s' AND b.brewSubCategory='%s' AND a.eid = b.id  AND c.id = b.brewBrewerID", $judging_scores_db_table, $brewing_db_table, $brewer_db_table, $style[0],$style[1]);
		if (($action == "print") && ($view == "winners")) $query_scores .= " AND a.scorePlace IS NOT NULL";
		elseif (($action == "default") && ($view == "default")) $query_scores .= " AND a.scorePlace IS NOT NULL";
		else $query_scores .= "";
		$query_scores .= " ORDER BY a.scorePlace";
		//echo $query_scores;
		$scores = mysql_query($query_scores, $brewing) or die(mysql_error());
		$row_scores = mysql_fetch_assoc($scores);
		$totalRows_scores = mysql_num_rows($scores);
				
		do { 
		$style = $row_scores['brewCategory'].$row_scores['brewSubCategory'];
	?>
    <tr>
        <td class="data"><?php if ($action != "print") echo display_place($row_scores['scorePlace'],2); else echo display_place($row_scores['scorePlace'],1); ?></td>
        <td class="data"><?php echo $row_scores['brewerFirstName']." ".$row_scores['brewerLastName']; if ($row_scores['brewCoBrewer'] != "") echo "<br>Co-Brewer: ".$row_scores['brewCoBrewer']; ?></td>
        <td class="data"><?php echo $row_scores['brewName']; ?></td>
        <td class="data"><?php echo $style.": ".$row_scores['brewStyle']; ?></td>
        <td class="data"><?php echo $row_scores['brewerClubs']; ?></td>
        <?php if ($filter == "scores") { ?>
        <td class="data"><?php echo $row_scores['scoreEntry']; ?></td>
        <?php } ?>
    </tr>
    <?php } while ($row_scores = mysql_fetch_assoc($scores)); ?>
    </tbody>
    </table>
    <?php 	} else echo "<p>No winners were reported for this category.</p>";
		} 
	} 
}
	?>
<?php // }  ?>
<?php 
// if NOT using BCOE to organize comp and if winners have been designated, display using legacy code
if (($totalRows_log_winners > 0) && ($row_prefs['prefsCompOrg'] == "N")) { 
?>
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
	mysql_select_db($database, $brewing);
	//if ($row_log_winners['brewWinnerCat'] < 10) $fix = "0"; else $fix = "";
	$query_style = sprintf("SELECT * FROM $styles_db_table WHERE brewStyleGroup = '%s' AND brewStyleNum = '%s'", $row_log_winners['brewWinnerCat'], $row_log_winners['brewWinnerSubCat']);
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
	$query_style = sprintf("SELECT * FROM $styles_db_table WHERE brewStyleGroup='%s'", $row_log_winners['brewWinnerCat']);  
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
  <?php } while ($row_log_winners = mysql_fetch_assoc($log_winners)); ?>
</tbody>
</table>
<?php if ($row_contest_info['contestWinnersComplete'] != "") echo $row_contest_info['contestWinnersComplete'];  
	} 
?>