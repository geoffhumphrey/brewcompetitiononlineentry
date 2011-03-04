<h2><?php 
if (($action == "edit") && ($id != "default")) echo "Edit Scores for Table #".$row_tables_edit['tableNumber'].": ".$row_tables_edit['tableName'];  
elseif (($action == "add") && ($id != "default")) echo "Add Scores for Table #".$row_tables_edit['tableNumber'].": ".$row_tables_edit['tableName'];  
else echo "Scores"; 
if ($dbTable != "default") echo ": ".ltrim($dbTable, "brewer_"); 
?></h2>
<div class="adminSubNavContainer">
  	<span class="adminSubNav">
		<span class="icon"><img src="images/arrow_left.png" alt="Back"></span><a href="index.php?section=admin">Back to Admin</a>
  	</span>
    <span class="adminSubNav">
		<span class="icon"><img src="images/arrow_left.png" alt="Back"></span><a href="index.php?section=admin&amp;go=judging_tables">Back to Tables List</a>
    </span>
	<?php if ($action != "default") { ?>
    <span class="adminSubNav">
		<span class="icon"><img src="images/arrow_left.png" alt="Back"></span><a href="index.php?section=admin&amp;go=judging_scores">Back to Scores List</a>
    </span>
	<?php } ?>
   	<?php if ($action == "default") { ?>
    <?php if ($totalRows_tables > 0) { ?>
    <span class="adminSubNav">
		<span class="icon"><img src="images/rosette_add.png" alt="Enter/Edit scores" title="Enter/Edit scores" /></span>
    		<div class="menuBar"><a class="menuButton" href="#" onclick="#" onmouseover="buttonMouseover(event, 'scoresMenu_tables');">Enter/Edit Scores for...</a></div>
    		<div id="scoresMenu_tables" class="menu" onmouseover="menuMouseover(event)">
    			<?php do { 
				$query_scores_1 = sprintf("SELECT COUNT(*) as 'count' FROM judging_scores WHERE scoreTable='%s'", $row_tables_edit_2['id']);
				$scores_1 = mysql_query($query_scores_1, $brewing) or die(mysql_error());
				$row_scores_1 = mysql_fetch_assoc($scores_1);
				$totalRows_scores_1 = $row_scores_1['count'];
				?>
   				<a class="menuItem" href="index.php?section=admin&amp;go=judging_scores&amp;action=<?php if ($totalRows_scores_1  > 0) echo "edit&amp;id=".$row_tables_edit_2['id']; else echo "add&amp;id=".$row_tables_edit_2['id']; ?>"><?php echo "Table #".$row_tables_edit_2['tableNumber'].": ".$row_tables_edit_2['tableName']; ?></a>
   			 	<?php mysql_free_result($scores_1); } while ($row_tables_edit_2 = mysql_fetch_assoc($tables_edit_2)); ?>
    		</div>
  	</span>
    <?php } // end if ($totalRows_tables > 0) 
	} ?>
    <span class="adminSubNav">
		<span class="icon"><img src="images/award_star_gold_2.png" alt="View BOS Entries and Scores" title="View BOS Entries and Scores" /></span><a href="index.php?section=admin&amp;go=judging_scores_bos">View BOS Entries and Scores</a>
    </span>
</div>
<div class="adminSubNavContainer">
<p>Scores have been entered for <?php echo $totalRows_scores; ?> of <?php echo $totalRows_entry_count; ?> entries marked as paid and received.</p>
	<span class="adminSubNav">
		<em>All</em> scores have not been entered for table(s):
	</span>
    <span class="adminSubNav">
        <select name="table_choice_1" id="table_choice_1" onchange="jumpMenu('self',this,0)">
        <option>Choose Below</option>
       	<?php do { 
		$query_scores_2 = sprintf("SELECT COUNT(*) as 'count' FROM judging_scores WHERE scoreTable='%s'", $row_tables['id']);
		$scores_2 = mysql_query($query_scores_2, $brewing) or die(mysql_error());
		$row_scores_2 = mysql_fetch_assoc($scores_2);
		if ($row_scores_2['count'] > 0) $a = "edit"; else $a = "add";
		if (!get_table_info($row_tables['id'],"count_scores",$row_tables['id'])) { ?>
        <option value="index.php?section=admin&amp;&go=judging_scores&amp;action=<?php echo $a; ?>&amp;id=<?php echo $row_tables['id']; ?>"><?php echo "Table #".$row_tables['tableNumber'].": ".$row_tables['tableName']; ?></option> 
		<?php } mysql_free_result($scores_2); ?>
		<?php } while ($row_tables = mysql_fetch_assoc($tables));?>
        </select>
     </span>
</div>
<?php if (($action == "default") && ($id == "default")) { ?>
<?php if ($totalRows_scores > 0) { ?>
<script type="text/javascript" language="javascript">
	 $(document).ready(function() {
		$('#sortable').dataTable( {
			"bPaginate" : true,
			"sPaginationType" : "full_numbers",
			"bLengthChange" : true,
			"iDisplayLength" :  <?php echo round($row_prefs['prefsRecordPaging']); ?>,
			"sDom": 'ipfrtip',
			"bStateSave" : false,
			<?php if ($filter == "category") { ?>
			"aaSorting": [[3,'asc'],[4,'desc'],[5,'asc']],
			<?php } else { ?>
			"aaSorting": [[1,'asc'],[4,'desc'],[5,'asc']],
			<?php } ?>
			"bProcessing" : true,
			"aoColumns": [
				null,
				null,
				null,
				null,
				null,
				{ "asSorting": [  ] },
				]
			} );
		} );
	</script>
<table class="dataTable" id="sortable">
<thead>
	<tr>
    	<th class="dataList bdr1B" width="1%" nowrap="nowrap">Entry #</th>
        <th class="dataList bdr1B" width="1%" nowrap="nowrap">Table #</th>
        <th class="dataList bdr1B" width="20%" nowrap="nowrap">Table Name</th>
        <th class="dataList bdr1B">Category</th>
    	<th class="dataList bdr1B" width="1%" nowrap="nowrap">Score</th>
        <th class="dataList bdr1B" width="1%" nowrap="nowrap">Place</th>
        <th class="dataList bdr1B" width="30%">Actions</th>
    </tr>
</thead>
<tbody>
	<?php do {
	$query_entries_1 = sprintf("SELECT brewStyle,brewCategorySort,brewCategory,brewSubCategory FROM brewing WHERE id='%s'", $row_scores['eid']);
	$entries_1 = mysql_query($query_entries_1, $brewing) or die(mysql_error());
	$row_entries_1 = mysql_fetch_assoc($entries_1);
	$style = $row_entries_1['brewCategorySort'].$row_entries_1['brewSubCategory'];
	
	$query_tables_1 = sprintf("SELECT id,tableName,tableNumber FROM judging_tables WHERE id='%s'", $row_scores['scoreTable']);
	$tables_1 = mysql_query($query_tables_1, $brewing) or die(mysql_error());
	$row_tables_1 = mysql_fetch_assoc($tables_1);
	$totalRows_tables = mysql_num_rows($tables_1);
		if ($row_tables_1['id'] != "") { // if table is erased.
	?>
	<tr>
    	<td><?php echo $row_scores['eid']; ?></td>
        <td class="data"><?php echo $row_tables_1['tableNumber']; ?></td>
        <td class="data"><?php echo $row_tables_1['tableName']; ?></td>
        <td class="data"><?php echo $style." ".style_convert($row_entries_1['brewCategorySort'],1).": ".$row_entries_1['brewStyle']; ?></td>
        <td class="data"><?php echo $row_scores['scoreEntry']; ?></td>
        <td class="data"><?php if ($row_scores['scorePlace'] == "5") echo "HM"; else echo $row_scores['scorePlace']; ?></td>  
        <td class="data" width="5%" nowrap="nowrap"><span class="icon"><a href="index.php?section=admin&amp;go=<?php echo $go; ?>&amp;action=edit&amp;id=<?php echo $row_tables_1['id']; ?>"><img src="images/pencil.png"  border="0" alt="Edit the <?php echo $row_tables_1['tableName']; ?> scores" title="Edit the <?php echo $row_tables_1['tableName']; ?> scores"></a></span><span class="icon"><a class="thickbox" href="reports.php?section=admin&amp;go=judging_scores&amp;id=<?php echo $row_tables_1['id']; ?>&amp;KeepThis=true&amp;TB_iframe=true&amp;height=425&amp;width=700"><img src="images/printer.png"  border="0" alt="Print the scores for <?php echo $row_tables_1['tableName']; ?>" title="Print the scores for <?php echo $row_tables_1['tableName']; ?>"></a></span>
        </td>
    </tr>
    <?php 
		}
	} while ($row_scores = mysql_fetch_assoc($scores)); ?>
</tbody>
</table>
<?php } else echo "<p>No scores have been entered yet.</p>"; ?>
<?php } // end if (($action == "default") && ($id == "default")) ?>

<?php if (($action == "add") || ($action == "edit")) { ?>
<?php if ($id != "default") { ?>
<form name="scores" method="post" action="includes/process.inc.php?action=<?php echo $action; ?>&amp;dbTable=<?php echo $go; ?>">
<script type="text/javascript" language="javascript">
	 $(document).ready(function() {
		$('#sortable').dataTable( {
			"bPaginate" : false,
			"sDom": 'rt',
			"bStateSave" : false,
			"bLengthChange" : false,
			"aaSorting": [[1,'asc']],
			"bProcessing" : false,
			"aoColumns": [
				null,
				null,
				{ "asSorting": [  ] },
				{ "asSorting": [  ] },
			]
			} );
		} );
</script>
<table class="dataTable" id="sortable">
<thead>
	<tr>
    	<th class="dataList bdr1B" width="1%" nowrap="nowrap">#</th>
        <th class="dataList bdr1B" width="35%">Category</th>
    	<th class="dataList bdr1B" width="1%" nowrap="nowrap">Score</th>
        <th class="dataList bdr1B">Place</th>
    </tr>
</thead>
<tbody>
	<?php 
	$a = explode(",", $row_tables_edit['tableStyles']); 
	//echo $row_tables_edit['tableStyles'];
	
	foreach (array_unique($a) as $value) {
		
		$query_styles = sprintf("SELECT brewStyle,brewStyleType FROM styles WHERE id='%s'", $value);
		$styles = mysql_query($query_styles, $brewing) or die(mysql_error());
		$row_styles = mysql_fetch_assoc($styles);
		
		$query_entries = sprintf("SELECT id,brewBrewerID,brewStyle,brewCategorySort,brewCategory,brewSubCategory,brewInfo FROM brewing WHERE brewStyle='%s' AND brewPaid='Y' AND brewReceived='Y'", $row_styles['brewStyle']);
		$entries = mysql_query($query_entries, $brewing) or die(mysql_error());
		$row_entries = mysql_fetch_assoc($entries);
		$style = $row_entries['brewCategorySort'].$row_entries['brewSubCategory'];
		
		do {

			if ($action == "edit") {
				$query_scores = sprintf("SELECT * FROM judging_scores WHERE eid='%s'", $row_entries['id']);
				$scores = mysql_query($query_scores, $brewing) or die(mysql_error());
				$row_scores = mysql_fetch_assoc($scores);
				}
	?>
	<tr>
		<?php if (($action == "edit") && ($row_scores['id'] != "")) $score_id = $row_scores['id']; else $score_id = $row_entries['id']; ?>
        <input type="hidden" name="score_id[]" value="<?php echo $score_id; ?>" />
        <?php if ($action == "edit") { ?>
        <input type="hidden" name="scorePrevious<?php echo $score_id; ?>" value="<?php if ($row_scores['id'] != "") echo "Y"; else echo "N"; ?>" />
    	<?php } ?>
        <input type="hidden" name="eid<?php echo $score_id; ?>" value="<?php if (($action == "edit") && ($row_scores['eid'] != "")) echo $row_scores['eid']; else echo $row_entries['id']; ?>" />
        <input type="hidden" name="bid<?php echo $score_id; ?>" value="<?php if (($action == "edit") && ($row_scores['bid'] != "")) echo $row_scores['bid']; else echo $row_entries['brewBrewerID']; ?>" />
        <input type="hidden" name="scoreTable<?php echo $score_id; ?>" value="<?php echo $id; ?>" />
        <input type="hidden" name="scoreType<?php echo $score_id; ?>" value="<?php echo style_type($row_styles['brewStyleType'],"1","bcoe"); ?>" />
        <td><?php echo $row_entries['id']; ?></td>
        <td class="data"><?php echo $style." ".style_convert($row_entries['brewCategorySort'],1).": ".$row_entries['brewStyle']; ?></td>
    	<td class="data"><input type="text" name="scoreEntry<?php echo $score_id; ?>" size="5" maxlength="2" value="<?php if ($action == "edit") echo $row_scores['scoreEntry']; ?>" /></td>
        <td>
        <select name="scorePlace<?php echo $score_id; ?>">
          <option value=""></option>
          <option value="1" <?php if (($action == "edit") && ($row_scores['scorePlace'] == "1")) echo "selected"; ?>>1st</option>
          <option value="2" <?php if (($action == "edit") && ($row_scores['scorePlace'] == "2")) echo "selected"; ?>>2nd</option>
          <option value="3" <?php if (($action == "edit") && ($row_scores['scorePlace'] == "3")) echo "selected"; ?>>3rd</option>
          <option value="4" <?php if (($action == "edit") && ($row_scores['scorePlace'] == "4")) echo "selected"; ?>>4th</option>
          <option value="5" <?php if (($action == "edit") && ($row_scores['scorePlace'] == "5")) echo "selected"; ?>>Hon. Men.</option>
        </select>
        </td>
	</tr>
    <?php } while ($row_entries = mysql_fetch_assoc($entries));
	mysql_free_result($styles);
	mysql_free_result($entries);
	} // end foreach ?>
</tbody>
</table>
<p><input type="submit" class="button" value="<?php if ($action == "edit") echo "Update"; else echo "Submit"; ?>"></p>
<input type="hidden" name="relocate" value="<?php echo relocate($_SERVER['HTTP_REFERER'],"default"); ?>">
</form>
<?php } // end if ($id != "default"); ?>
<?php } ?>
