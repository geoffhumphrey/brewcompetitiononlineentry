<h2>
<?php if ($action == "enter") echo "Enter/Edit BOS Places - ".$row_style_type['styleTypeName']; else echo "Best of Show (BOS) Entries and Places"; 
if ($dbTable != "default") echo ": ".get_suffix($dbTable); 
do { $a[] = $row_style_types['id']; } while ($row_style_types = mysql_fetch_assoc($style_types));
?>
</h2>
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
    <span class="adminSubNav">
		<span class="icon"><img src="<?php echo $base_url; ?>images/arrow_left.png" alt="Back"></span><a href="<?php echo $base_url; ?>index.php?section=admin&amp;go=judging_scores">Back to Scores List</a>
    </span>
    <?php if ($action != "default") { ?>
    <span class="adminSubNav">
		<span class="icon"><img src="<?php echo $base_url; ?>images/arrow_left.png" alt="Back"></span><a href="<?php echo $base_url; ?>index.php?section=admin&amp;go=judging_scores_bos">Back to BOS Entries and Places List</a>
    </span>
	<?php } ?>
    <?php if ($action == "default") { ?>
    <span class="adminSubNav">
    <span class="icon"><img src="<?php echo $base_url; ?>images/rosette_add.png" alt="Enter/Edit scores" title="Enter/Edit scores" /></span>
		<div class="menuBar"><a class="menuButton" href="#" onclick="#" onmouseover="buttonMouseover(event, 'scoresMenu_bos_2');">Enter/Edit...</a></div>
		<div id="scoresMenu_bos_2" class="menu" onmouseover="menuMouseover(event)">
		<?php do { 
			if ($row_style_types_2['styleTypeBOS'] == "Y") { ?>
			<a class="menuItem" href="<?php echo $base_url; ?>index.php?section=admin&amp;go=judging_scores_bos&amp;action=enter&amp;filter=<?php echo $row_style_types_2['id'] ?>">BOS Places - <?php echo $row_style_types_2['styleTypeName']; ?></a>
		<?php 
			}
		} while ($row_style_types_2 = mysql_fetch_assoc($style_types_2));
		?>
		</div>
	</span>
    <?php } ?>
</div>
<?php } ?>
<?php if ($dbTable == "default") { ?>
<div class="adminSubNavContainer">
	<span class="adminSubNav">
    	<span class="icon"><img src="<?php echo $base_url; ?>images/note.png"  /></span><a href="<?php echo $base_url; ?>index.php?section=admin&amp;go=styles">View Accepted Styles</a>
    </span>
	<span class="adminSubNav">
    	<span class="icon"><img src="<?php echo $base_url; ?>images/note_add.png"  /></span><a href="<?php echo $base_url; ?>index.php?section=admin&amp;go=styles&amp;action=add">Add a Custom Style Category</a>
    </span>
    <span class="adminSubNav">
    	<span class="icon"><img src="<?php echo $base_url; ?>images/page.png"  /></span><a href="<?php echo $base_url; ?>index.php?section=admin&amp;go=style_types">View Style Types</a>
    </span>
    <span class="adminSubNav">
    	<span class="icon"><img src="<?php echo $base_url; ?>images/page_add.png"  /></span><a href="<?php echo $base_url; ?>index.php?section=admin&amp;go=style_types&amp;action=add">Add a Custom Style Type</a>
    </span>
</div>
<?php } if ($action == "default") { 
sort($a);
foreach ($a as $type) {
	$query_style_type = "SELECT * FROM $style_types_db_table WHERE id='$type'";
	$style_type = mysql_query($query_style_type, $brewing) or die(mysql_error());
	$row_style_type = mysql_fetch_assoc($style_type);

if ($row_style_type['styleTypeBOS'] == "Y") { 

	$query_bos = "SELECT * FROM $judging_scores_db_table";
	if ($row_style_type['styleTypeBOSMethod'] == "1") $query_bos .= " WHERE scoreType='$type' AND scorePlace='1'";
	if ($row_style_type['styleTypeBOSMethod'] == "2") $query_bos .= " WHERE scoreType='$type' AND (scorePlace='1' OR scorePlace='2')";
	if ($row_style_type['styleTypeBOSMethod'] == "3") $query_bos .= " WHERE (scoreType='$type' AND scorePlace='1') OR (scoreType='$type' AND scorePlace='2') OR (scoreType='$type' AND scorePlace='3')";
	$query_bos .= " ORDER BY scoreTable ASC";

	$bos = mysql_query($query_bos, $brewing) or die(mysql_error());
	$row_bos = mysql_fetch_assoc($bos);
	$totalRows_bos = mysql_num_rows($bos);

?>
<a name="<?php echo $type; ?>"></a><h3>BOS Entries and Places - <?php echo $row_style_type['styleTypeName']; ?></h3>
<?php if ($totalRows_bos > 0) { ?>
<?php if ($dbTable == "default") { ?>
<div class="adminSubNavContainer">
<span class="adminSubNav">
<span class="icon"><img src="<?php echo $base_url; ?>images/rosette_add.png" alt="Enter/Edit BOS Entries and Places" title="Enter/Edit BOS Entries and Places" /></span><a href="<?php echo $base_url; ?>index.php?section=admin&amp;go=judging_scores_bos&amp;action=enter&amp;filter=<?php echo $type; ?>">Enter/Edit BOS Entries and Places - <?php echo $row_style_type['styleTypeName']; ?></a></span>
</span> 
</div>
<?php } ?>
<script type="text/javascript" language="javascript">
	 $(document).ready(function() {
		$('#sortable<?php echo $type; ?>').dataTable( {
			"bPaginate" : false,
			"sPaginationType" : "full_numbers",
			"bLengthChange" : true,
		"iDisplayLength" :  <?php echo round($_SESSION['prefsRecordPaging']); ?>,
			"sDom": 'irti',
			"bStateSave" : false,
			"aaSorting": [[2,'asc'],[6,'asc'],[7,'asc']],
			"bProcessing" : true,
			"aoColumns": [
				null,
				null,
				null,
				null,
				null,
				null,
				null,
			null,
				null
				]
			} );
		} );
	</script>
<table class="dataTable" id="sortable<?php echo $type; ?>">
<thead>
	<tr>
    	<th class="dataList bdr1B" width="1%" nowrap="nowrap">Entry #</th>
        <th class="dataList bdr1B" width="1%" nowrap="nowrap">Judging #</th>
        <th class="dataList bdr1B" width="1%" nowrap="nowrap">Table #</th>
        <th class="dataList bdr1B" width="15%" nowrap="nowrap">Table Name</th>
        <th class="dataList bdr1B">Category</th>
        <?php if ($dbTable == "default") { ?>
    	<th class="dataList bdr1B" width="1%" nowrap="nowrap">Table Score</th>
        <th class="dataList bdr1B" width="1%" nowrap="nowrap">Table Place</th>
        <?php } ?>
        <?php if ($dbTable != "default") { ?>
        <th class="dataList bdr1B" width="15%" nowrap="nowrap">Brewer</th>
        <th class="dataList bdr1B" width="15%" nowrap="nowrap">Entry Name</th>
        <?php } ?>
        <th class="dataList bdr1B" width="1%" nowrap="nowrap">BOS Score</th>
        <th class="dataList bdr1B" width="10%" nowrap="nowrap">BOS Place</th>
    </tr>
</thead>
<tbody>
	<?php do {
	$query_entries_1 = sprintf("SELECT brewStyle,brewCategorySort,brewCategory,brewSubCategory,brewName,brewBrewerFirstName,brewBrewerLastName,brewJudgingNumber FROM $brewing_db_table WHERE id='%s'", $row_bos['eid']);
	$entries_1 = mysql_query($query_entries_1, $brewing) or die(mysql_error());
	$row_entries_1 = mysql_fetch_assoc($entries_1);
	$style = $row_entries_1['brewCategorySort'].$row_entries_1['brewSubCategory'];
	
	$query_tables_1 = sprintf("SELECT id,tableName,tableNumber FROM $judging_tables_db_table WHERE id='%s'", $row_bos['scoreTable']);
	$tables_1 = mysql_query($query_tables_1, $brewing) or die(mysql_error());
	$row_tables_1 = mysql_fetch_assoc($tables_1);
$totalRows_tables = mysql_num_rows($tables_1);
	
	$query_bos_place_1 = sprintf("SELECT scorePlace,scoreEntry FROM $judging_scores_bos_db_table WHERE eid='%s'", $row_bos['eid']);
	$bos_place_1 = mysql_query($query_bos_place_1, $brewing) or die(mysql_error());
	$row_bos_place_1 = mysql_fetch_assoc($bos_place_1);
	
	?>
	<tr>
    	<td><?php echo sprintf("%04s",$row_bos['eid']); ?></td>
        <td class="data"><?php echo readable_judging_number($row_entries_1['brewCategory'],$row_entries_1['brewJudgingNumber']); ?></td>
        <td class="data"><?php echo $row_tables_1['tableNumber']; ?></td>
        <td class="data"><?php echo $row_tables_1['tableName']; ?></td>
        <td class="data"><?php echo $style." ".style_convert($row_entries_1['brewCategorySort'],1).": ".$row_entries_1['brewStyle']; ?></td>
        <?php if ($dbTable == "default") { ?>
        <td class="data"><?php echo $row_bos['scoreEntry']; ?></td>
        <td class="data"><?php echo $row_bos['scorePlace']; ?></td>
        <?php } ?>
        <?php if ($dbTable != "default") { ?>
        <td class="data"><?php echo $row_entries_1['brewBrewerLastName'].", ".$row_entries_1['brewBrewerFirstName']; ?></td>
        <td class="data"><?php echo $row_entries_1['brewName'] ?></td>
        
        <?php } ?>
        <td class="data"><?php echo $row_bos_place_1['scoreEntry']; ?></td>
        <td class="data"><?php echo $row_bos_place_1['scorePlace']; ?></td>
    </tr>
    <?php } while ($row_bos = mysql_fetch_assoc($bos)); 
	mysql_free_result($bos);
	mysql_free_result($style_type);
	mysql_free_result($tables_1);
	mysql_free_result($bos_place_1);
	mysql_free_result($entries_1);
	?>
</tbody>
</table>
<?php } else echo "<p style='margin: 0 0 40px 0'>No entries are eligible.</p>"; 
} 

?>
<?php } ?>

<?php } // end if ($action == "default") ?>

<?php if ($action == "enter") { ?>
<?php if ($totalRows_enter_bos > 0) { ?>
<form name="scores" method="post" action="<?php echo $base_url; ?>includes/process.inc.php?action=<?php echo $action; ?>&amp;dbTable=<?php echo $judging_scores_bos_db_table; ?>">
<script type="text/javascript" language="javascript">
	 $(document).ready(function() {
		$('#sortable').dataTable( {
			"bPaginate" : false,
			"sDom": 'rt',
			"bStateSave" : false,
			"bLengthChange" : false,
			"aaSorting": [[2,'asc']],
			"bProcessing" : false,
			"aoColumns": [
				null,
				null,
				null,
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
    	<th class="dataList bdr1B" width="1%" nowrap="nowrap">Score</th>
        <th class="dataList bdr1B">Place</th>
    </tr>
</thead>
<tbody>
	<?php 
	do {	
		$query_entries = sprintf("SELECT id,brewBrewerID,brewStyle,brewCategorySort,brewCategory,brewSubCategory,brewInfo,brewJudgingNumber FROM $brewing_db_table WHERE id='%s'", $row_enter_bos['eid']);
		$entries = mysql_query($query_entries, $brewing) or die(mysql_error());
		$row_entries = mysql_fetch_assoc($entries);
		$style = $row_entries['brewCategorySort'].$row_entries['brewSubCategory'];
		
		$query_scores = sprintf("SELECT * FROM $judging_scores_bos_db_table WHERE eid='%s'", $row_enter_bos['eid']);
		$scores = mysql_query($query_scores, $brewing) or die(mysql_error());
		$row_scores = mysql_fetch_assoc($scores);
	?>
	<tr>
		<?php $score_id = $row_entries['id']; ?>
        <input type="hidden" name="score_id[]" value="<?php echo $score_id; ?>" />
        <input type="hidden" name="scorePrevious<?php echo $score_id; ?>" value="<?php if ($row_scores['id'] != "") echo "Y"; else echo "N"; ?>" />
        <input type="hidden" name="eid<?php echo $score_id; ?>" value="<?php echo $row_entries['id']; ?>" />
        <input type="hidden" name="bid<?php echo $score_id; ?>" value="<?php echo $row_entries['uid']; ?>" />
        <input type="hidden" name="scoreType<?php echo $score_id; ?>" value="<?php echo $filter; ?>" />
        <?php if ($row_scores['id'] != "") { ?>
        <input type="hidden" name="id<?php echo $score_id; ?>" value="<?php echo $row_scores['id']; ?>" />
        <?php } ?>
        <td><?php echo sprintf("%04s",$row_enter_bos['eid']);  ?></td>
        <td class="data"><?php echo readable_judging_number($row_entries['brewCategory'],$row_entries['brewJudgingNumber']); ?></td>
        <td class="data"><?php echo $style." ".style_convert($row_entries['brewCategorySort'],1).": ".$row_entries['brewStyle']; ?></td>
    	<td class="data"><input type="text" name="scoreEntry<?php echo $score_id; ?>" size="5" maxlength="2" value="<?php echo $row_scores['scoreEntry']; ?>" /></td>
        <td>
        <select name="scorePlace<?php echo $score_id; ?>">
          <option value=""></option>
          <?php for($i=1; $i<$_SESSION['jPrefsMaxBOS']+1; $i++) { ?>
          <option value="<?php echo $i; ?>" <?php if ($row_scores['scorePlace'] == $i) echo "selected"; ?>><?php echo text_number($i); ?></option>
          <?php } ?>
        </select>
        </td>
	</tr>
    <?php 
	mysql_free_result($entries);
	} while ($row_enter_bos = mysql_fetch_assoc($enter_bos)); 
	?>	
</tbody>
</table>
<p><input type="submit" class="button" value="Enter"></p>
<input type="hidden" name="relocate" value="<?php echo relocate($_SERVER['HTTP_REFERER'],"default",$msg,$id); ?>">
</form>
<?php } 
else echo "<p>There are no qualifying entries available.</p>";
}
?>