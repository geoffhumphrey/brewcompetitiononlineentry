<?php
/**
 * Module:      winners.sec.php 
 * Description: This module displays the winners entered into the database.
 *              Displays by table.
 * 
 */

// If using BCOE for comp organization, display winners by table
if ($row_prefs['prefsCompOrg'] == "Y") { 
	do { 
	$entry_count = get_table_info(1,"count_total",$row_tables['id'],$dbTable,"default");
	if (score_count($row_tables['id'],"1")) {
		if ($action == "print") { 
	?>
	<div id="header">	
		<div id="header-inner">
    	<?php } ?>
        	<h3>Table <?php echo $row_tables['tableNumber'].": ".$row_tables['tableName']." (".$entry_count." Entries)"; ?></h3>
    <?php 
		if ($action == "print") { 
		?>
        </div>
	</div>
    	<?php } 
	if ($entry_count > 0) { ?>
    
    
     <script type="text/javascript" language="javascript">
	 $(document).ready(function() {
		$('#sortable<?php echo $row_tables['id']; ?>').dataTable( {
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
    <table class="dataTable" id="sortable<?php echo $row_tables['id']; ?>">
    <thead>
	<tr>
    	<th class="dataList bdr1B" width="1%" nowrap="nowrap">Place</th>
        <th class="dataList bdr1B" width="25%">Brewer(s)</th>
        <th class="dataList bdr1B">Entry Name</th>
        <th class="dataList bdr1B" width="25%">Style</th>
        <th class="dataList bdr1B" width="25%">Club</th>
        <?php if ($filter == "scores") { ?>
        <th width="1%" class="dataHeading bdr1B">Score</th>
        <?php } ?>
    </tr>
</thead>
    <tbody>
    <?php 
		$query_scores = sprintf("SELECT a.scorePlace, a.scoreEntry, b.brewName, b.brewCategory, b.brewCategorySort, b.brewSubCategory, b.brewStyle, b.brewCoBrewer, c.brewerLastName, c.brewerFirstName, c.brewerClubs FROM %s a, %s b, %s c WHERE scoreTable='%s' AND a.eid = b.id AND c.id = b.brewBrewerID", $scores_db_table, $brewing_db_table, $brewer_db_table, $row_tables['id']);
		if (($action == "print") && ($view == "winners")) $query_scores .= " AND a.scorePlace IS NOT NULL";
		if (($action == "default") && ($view == "default")) $query_scores .= " AND a.scorePlace IS NOT NULL";
		$query_scores .= " ORDER BY a.scorePlace";
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
    <?php 
			} // end if ($entry_count > 0);
		} else echo "<p>No winners have been entered yet. Please check back later.</p>";
 	} while ($row_tables = mysql_fetch_assoc($tables));
} 
/*
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
  <?php } while ($row_log_winners = mysql_fetch_assoc($log_winners)); ?>
</tbody>
</table>
<?php if ($action == "print") { ?>
    <div <?php if ($view == "winners") echo "style='margin-bottom: 4em;'"; else echo "style='page-break-after:always;'"; ?>></div>
    <?php } ?>
<?php if ($row_contest_info['contestWinnersComplete'] != "") echo $row_contest_info['contestWinnersComplete'];  
	} // end if (($totalRows_log_winners > 0) && ($row_prefs['prefsCompOrg'] == "N"))	
} 
*/
?>