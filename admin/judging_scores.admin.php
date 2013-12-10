<h2><?php 
if (($action == "edit") && ($id != "default")) echo "Edit Scores for Table #".$row_tables_edit['tableNumber'].": ".$row_tables_edit['tableName'];  
elseif (($action == "add") && ($id != "default")) echo "Add Scores for Table #".$row_tables_edit['tableNumber'].": ".$row_tables_edit['tableName'];  
else echo "Scores"; 
if ($dbTable != "default") echo ": ".get_suffix($dbTable); 
$totalRows_entry_count = total_paid_received($go,"default");
?></h2>
<div class="adminSubNavContainer">
  	<span class="adminSubNav">
		<span class="icon"><img src="<?php echo $base_url; ?>images/arrow_left.png" alt="Back"></span><a href="<?php echo $base_url; ?>index.php?section=admin">Back to Admin Dashboard</a>
  	</span>
    <?php if  ($dbTable != "default") { ?>
	<span class="adminSubNav">
        <span class="icon"><img src="<?php echo $base_url; ?>images/arrow_left.png" alt="Back"></span><a href="<?php echo $base_url; ?>index.php?section=admin&amp;go=archive">Back to Archives</a>
     </span> 
	<?php } ?>
    <?php if ($dbTable == "default") { ?>
    <span class="adminSubNav">
		<span class="icon"><img src="<?php echo $base_url; ?>images/arrow_left.png" alt="Back"></span><a href="<?php echo $base_url; ?>index.php?section=admin&amp;go=judging_tables">Back to Tables List</a>
    </span>
	<?php if ($action != "default") { ?>
    <span class="adminSubNav">
		<span class="icon"><img src="<?php echo $base_url; ?>images/arrow_left.png" alt="Back"></span><a href="<?php echo $base_url; ?>index.php?section=admin&amp;go=judging_scores">Back to Scores List</a>
    </span>
	<?php } ?>
   	<?php if ($action == "default") { ?>
    <?php if ($totalRows_tables > 0) { ?>
    <span class="adminSubNav">
		<span class="icon"><img src="<?php echo $base_url; ?>images/rosette_add.png" alt="Enter/Edit scores" title="Enter/Edit scores" /></span>
    		<div class="menuBar"><a class="menuButton" href="#" onclick="#" onmouseover="buttonMouseover(event, 'scoresMenu_tables');">Enter/Edit Scores for...</a></div>
    		<div id="scoresMenu_tables" class="menu" onmouseover="menuMouseover(event)">
    			<?php 
				
				do { 
					$table_count_total = table_count_total($row_tables_edit_2['id']);
				?>
   				<a class="menuItem" href="<?php echo $base_url; ?>index.php?section=admin&amp;go=judging_scores&amp;action=<?php if ($table_count_total > 0) echo "edit&amp;id=".$row_tables_edit_2['id']; else echo "add&amp;id=".$row_tables_edit_2['id']; ?>"><?php echo "Table #".$row_tables_edit_2['tableNumber'].": ".$row_tables_edit_2['tableName']; ?></a>
   			 	<?php  } while ($row_tables_edit_2 = mysql_fetch_assoc($tables_edit_2)); ?>
    		</div>
  	</span>
    <?php } // end if ($totalRows_tables > 0) 
	} ?>
    <?php if (((NHC) && ($prefix == "_final")) || (!NHC)) { ?>
    <span class="adminSubNav">
		<span class="icon"><img src="<?php echo $base_url; ?>images/award_star_gold_2.png" alt="View BOS Entries and Places" title="View BOS Entries and Places" /></span><a href="<?php echo $base_url; ?>index.php?section=admin&amp;go=judging_scores_bos">View BOS Entries and Places</a>
    </span>
    <?php } } // end if ($dbTable == "default") ?>
</div>
<?php if ($dbTable == "default") { ?>
<div class="adminSubNavContainer">
<p>Scores have been entered for <?php echo $totalRows_scores; ?> of <?php echo $totalRows_entry_count; ?> entries marked as paid and received.</p>
<?php 
if ($id != "default") echo winner_method($_SESSION['prefsWinnerMethod'],2); ?> 
</div>
<?php } // end if ($dbTable == "default") ?>

<?php if (($action == "default") && ($id == "default")) { ?>
<?php if ($totalRows_scores > 0) { ?>
<script type="text/javascript" language="javascript">
	 $(document).ready(function() {
		$('#sortable').dataTable( {
			"bPaginate" : true,
			"sPaginationType" : "full_numbers",
			"bLengthChange" : true,
			"iDisplayLength" :  <?php echo round($_SESSION['prefsRecordPaging']); ?>,
			"sDom": 'ipfrtip',
			"bStateSave" : false,
			<?php if ($filter == "category") { ?>
			"aaSorting": [[4,'asc'],[6,'asc'],[5,'desc']],
			<?php } elseif ($dbTable != "default") { ?>
			"aaSorting": [[2,'asc'],[8,'asc']],
			<?php } else { ?>
			"aaSorting": [[2,'asc'],[6,'asc'],[5,'desc']],
			<?php } ?>
			"bProcessing" : true,
			"aoColumns": [
				null,
				null,
				null,
				null,
				null,
				null,
				<?php if ($dbTable != "default") { ?>
				null,
				null,
				<?php } ?>
				null,
				{ "asSorting": [  ] }<?php if ($dbTable == "default") { ?>,
				{ "asSorting": [  ] }
				<?php } ?>
				]
			} );
		} );
	</script>
<table class="dataTable" id="sortable">
<thead>
	<tr>
    	<th class="dataList bdr1B" width="1%" nowrap="nowrap">Entry #</th>
    	<th class="dataList bdr1B" width="1%" nowrap="nowrap">Judging #</th>
        <th class="dataList bdr1B" width="1%" nowrap="nowrap">Table #</th>
        <th class="dataList bdr1B" width="15%" nowrap="nowrap">Table Name</th>
        <th class="dataList bdr1B">Category</th>
        <?php if ($dbTable != "default") { ?>
        <th class="dataList bdr1B" width="15%" nowrap="nowrap">Brewer</th>
        <th class="dataList bdr1B" width="15%" nowrap="nowrap">Entry Name</th>
        <?php } ?>
    	<th class="dataList bdr1B" width="1%" nowrap="nowrap">Score</th>
        <th class="dataList bdr1B" width="1%" nowrap="nowrap">Place</th>
        <th class="dataList bdr1B" width="1%" nowrap="nowrap">Mini-BOS?</th>
        <?php if ($dbTable == "default") { ?>
        <th class="dataList bdr1B" width="30%">Actions</th>
        <?php } ?>
    </tr>
</thead>
<tbody>
	<?php 
	do {
	
	$table_score_data = table_score_data($row_scores['eid'],$row_scores['scoreTable']); 
	$table_score_data = explode("^",$table_score_data);
	
	if ((NHC) && ($prefix == "final_")) $entry_number = sprintf("%06s",$table_score_data[0]); 
	else $entry_number = sprintf("%04s",$table_score_data[0]);
	
	if ((NHC) || ($_SESSION['prefsEntryForm'] == "N")) $judging_number = sprintf("%06s",$table_score_data[6]); 
	else $judging_number = readable_judging_number($table_score_data[1],$table_score_data[6]);
	
	if ($row_scores['scorePlace'] == "5") $score_place = "HM"; 
	elseif ($row_scores['scorePlace'] == "6") $score_place =  "Admin Avance"; 
	elseif ($row_scores['scorePlace'] == "") $score_place = "<span style='display:none'>N/A</span>"; 
	else $score_place =  $row_scores['scorePlace'];
	
	if ($row_scores['scoreMiniBOS'] == "1") $mini_bos = "<img src='".$base_url."images/tick.png' /></span>";
	else $mini_bos = "&nbsp;";
	
	
	?>
	<tr>
    	<td><?php echo $entry_number; ?></td>
        <td class="data"><?php echo $judging_number;  ?></td>
        <td class="data"><?php echo $table_score_data[11]; ?></td>
        <td class="data"><?php echo $table_score_data[10]; ?></td>
        <td class="data"><?php echo $table_score_data[12]." ".style_convert($table_score_data[8],1).": ".$table_score_data[13]; ?></td>
        
        <?php if ($dbTable != "default") { ?>
        <td class="data"><?php echo $table_score_data[5].", ".$table_score_data[4]; ?></td>
        <td class="data"><?php echo $table_score_data[3]; ?></td>
        <?php } ?>
        <td class="data"><?php echo $row_scores['scoreEntry']; ?></td>
        <td class="data"><?php echo $score_place; ?></td>  
        <td class="data"><?php echo $mini_bos; ?></td>
		<?php if ($dbTable == "default") { ?>
        <td class="data" width="5%" nowrap="nowrap"><span class="icon"><a href="<?php echo $base_url; ?>index.php?section=admin&amp;go=<?php echo $go; ?>&amp;action=edit&amp;id=<?php echo $table_score_data[9]; ?>"><img src="<?php echo $base_url; ?>images/pencil.png"  border="0" alt="Edit the <?php echo $table_score_data[10]; ?> scores" title="Edit the <?php echo $table_score_data[10]; ?> scores"></a></span><span class="icon"><a id="modal_window_link" href="reports.php?section=admin&amp;go=judging_scores&amp;id=<?php echo $table_score_data[9]; ?>"><img src="<?php echo $base_url; ?>images/printer.png"  border="0" alt="Print the scores for <?php echo $table_score_data[10]; ?>" title="Print the scores for <?php echo $table_score_data[10]; ?>"></a></span>
        </td>
        <?php } ?>
    </tr>
    <?php 
		//}
	} while ($row_scores = mysql_fetch_assoc($scores)); ?>
</tbody>
</table>
<?php } // end if ($totalRows_scores > 0) 
else echo "<p>No scores have been entered. If tables have been defined, use the &ldquo;Enter/Edit Scores for...&rdquo; menu above to add scores.</p>"; ?>
<?php } // end if (($action == "default") && ($id == "default")) ?>

<?php if ((($action == "add") || ($action == "edit")) && ($dbTable == "default")) { 
if (NHC) echo "<div class='error'>A requirement for the NHC is to enter scores for <em>all</em> entries and the top three places for each BJCP category. For an entry to advance to the final round, it must be designated here as 1st, 2nd, or 3rd in its category and achieve a score of 30 or more.</div>";
?>
<?php if ($id != "default") { ?>
<form name="scores" method="post" action="<?php echo $base_url; ?>includes/process.inc.php?action=<?php echo $action; ?>&amp;dbTable=<?php echo $judging_scores_db_table; ?>">
<script type="text/javascript" language="javascript">
$('.fDrop').live('change', function (event) {
    var cI = $(this);
    $('.fDrop option:selected').each(function (i, e) {
        //Check if values match AND if not default AND not match changed item to self
        if ($(e).val() == cI.val() && $(e).val() != '' && $(e).parent().index() != cI.index()) {
            alert('Duplicate place detected. Is this correct?');
            // cI.val('');
        }
    });
}); 
</script>
<script type="text/javascript" language="javascript">
$(document).ready(function() {
	$('#sortable').dataTable( {
		"bPaginate" : false,
		"sDom": 'irt',
		"bStateSave" : false,
		"bLengthChange" : false,
		"aaSorting": [[2,'asc']],
		"bProcessing" : false,
		"aoColumns": [
			null,
			null,
			null,
			{ "asSorting": [  ] },
			{ "asSorting": [  ] },
			{ "asSorting": [  ] }
		]
	} );
} );
</script>
<table class="dataTable" id="sortable">
<thead>
	<tr>
    	<th class="dataList bdr1B" width="1%" nowrap="nowrap">Entry #</th>
        <th class="dataList bdr1B" width="1%" nowrap="nowrap">Judging #</th>
        <th class="dataList bdr1B" width="35%">Category</th>
        <th class="dataList bdr1B" width="1%" nowrap="nowrap">Mini-BOS?</th>
    	<th class="dataList bdr1B" width="1%" nowrap="nowrap">Score</th>
        <th class="dataList bdr1B">Place</th>
    </tr>
</thead>
<tbody>
<?php
	$a = explode(",", $row_tables_edit['tableStyles']); 
	
	foreach (array_unique($a) as $value) {
		
		$score_style_data = score_style_data($value);
		$score_style_data = explode("^",$score_style_data);
		
		include(DB.'admin_judging_scores.db.php'); // moved to a separate document to not have MySQL queries within loops
		$style = $row_entries['brewCategorySort'].$row_entries['brewSubCategory'];
		
		do {

			if ($totalRows_entries > 0) {
				
				if ($action == "edit") {
					$score_entry_data = score_entry_data($row_entries['id']);
					$score_entry_data = explode("^",$score_entry_data);
				}
				
				if (($action == "edit") && (!empty($score_entry_data[0]))) $score_id = $score_entry_data[0]; 
				else $score_id = $row_entries['id'];
				
				if (!empty($score_entry_data[3])) $score_previous = "Y";
				elseif (!empty($score_entry_data[4])) $score_previous = "Y";
				else $score_previous = "N";
				
				$eid = $row_entries['id'];
				
				// $bid is the brewBrewerID/uid
				$bid = $row_entries['brewBrewerID'];
				
				if ((NHC) && ($prefix == "final_")) $entry_number = sprintf("%06s",$row_entries['id']);
				else $entry_number = sprintf("%04s",$row_entries['id']);
				
				if ((NHC) || ($_SESSION['prefsEntryForm'] == "N")) $judging_number = sprintf("%06s",$row_entries['brewJudgingNumber']); 
				else $judging_number = readable_judging_number($table_score_data[1],$row_entries['brewJudgingNumber']);
				
				$judging_number = sprintf("%06s",$row_entries['brewJudgingNumber']);
				 
				$style_display = $style." ".style_convert($row_entries['brewCategorySort'],1).": ".$score_style_data[2];
			
	?>
	<tr>
        <input type="hidden" name="score_id[]" value="<?php echo $score_id; ?>" />
        <?php if ($action == "edit") { ?>
        <input type="hidden" name="scorePrevious<?php echo $score_id; ?>" value="<?php echo $score_previous; ?>" />
    	<?php } ?>
        <input type="hidden" name="eid<?php echo $score_id; ?>" value="<?php echo $eid; ?>" />
        <input type="hidden" name="bid<?php echo $score_id; ?>" value="<?php echo $bid; ?>" />
        <input type="hidden" name="scoreTable<?php echo $score_id; ?>" value="<?php echo $id; ?>" />
        <input type="hidden" name="scoreType<?php echo $score_id; ?>" value="<?php echo style_type($score_style_data[3],"1","bcoe"); ?>" />
        <td><?php echo $entry_number; ?></td>
        <td class="data"><?php echo $judging_number; ?></td>
        <td class="data"><?php echo $style_display; ?></td>
        <td class="data"><input type="checkbox" name="scoreMiniBOS<?php echo $score_id; ?>" value="1" <?php if (($action == "edit") && ($score_entry_data[5] == "1")) echo "CHECKED"; ?> /></td>
        
    	<td class="data"><input type="text" name="scoreEntry<?php echo $score_id; ?>" size="6" maxlength="6" value="<?php if ($action == "edit") echo $score_entry_data[3]; ?>" /></td>
        <td>
        <select class="fDrop" name="scorePlace<?php echo $score_id; ?>">
          <option value=""></option>
          <option value="1" <?php if (($action == "edit") && ($score_entry_data[4] == "1")) echo "SELECTED"; ?>>1st</option>
          <option value="2" <?php if (($action == "edit") && ($score_entry_data[4] == "2")) echo "SELECTED"; ?>>2nd</option>
          <option value="3" <?php if (($action == "edit") && ($score_entry_data[4] == "3")) echo "SELECTED"; ?>>3rd</option>
          <?php if (!NHC) { ?>
          <option value="4" <?php if (($action == "edit") && ($score_entry_data[4] == "4")) echo "SELECTED"; ?>>4th</option>
          <option value="5" <?php if (($action == "edit") && ($score_entry_data[4] == "5")) echo "SELECTED"; ?>>Hon. Men.</option>
          <?php } ?>
        </select>
        </td>
	</tr>
    <?php }
		} while ($row_entries = mysql_fetch_assoc($entries));
	} // end foreach ?> 
</tbody>
</table>

<p><input type="submit" class="button" value="<?php if ($action == "edit") echo "Update"; else echo "Submit"; ?>"></p>
<input type="hidden" name="relocate" value="<?php echo relocate($_SERVER['HTTP_REFERER'],"default",$msg,$id); ?>">
</form>

<?php } // end if ($id != "default") ?>
<?php } // end if ((($action == "add") || ($action == "edit")) && ($dbTable == "default")) ?>