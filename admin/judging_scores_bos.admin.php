<h2>
<?php if ($action == "enter") echo "Enter/Edit BOS Places - ".$row_style_type['styleTypeName']; else echo "Best of Show (BOS) Entries and Places"; 
if ($dbTable != "default") echo ": ".get_suffix($dbTable); 
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
    <?php if (($action == "default") && ($totalRows_style_type > 0)) { ?>
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
<?php } 
if (($action == "default") && ($totalRows_style_type > 0)) {
do { $a[] = $row_style_types['id']; } while ($row_style_types = mysql_fetch_assoc($style_types));
sort($a);

foreach ($a as $type) {
	$style_type_info = style_type_info($type);
	$style_type_info = explode("^",$style_type_info);
	
if ($style_type_info[0] == "Y") { 

include(DB.'admin_judging_scores_bos.db.php');

?>
<a name="<?php echo $type; ?>"></a><h3>BOS Entries and Places - <?php echo $style_type_info[2]; ?></h3>
<?php if ($totalRows_bos > 0) { ?>
<?php if ($dbTable == "default") { ?>
<div class="adminSubNavContainer">
<span class="adminSubNav">
<span class="icon"><img src="<?php echo $base_url; ?>images/rosette_add.png" alt="Enter/Edit BOS Entries and Places" title="Enter/Edit BOS Entries and Places" /></span><a href="<?php echo $base_url; ?>index.php?section=admin&amp;go=judging_scores_bos&amp;action=enter&amp;filter=<?php echo $type; ?>">Enter/Edit BOS Entries and Places - <?php echo $style_type_info[2]; ?></a></span>
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
		
	$bos_entry_info = bos_entry_info($row_bos['eid'], $row_bos['scoreTable'],$filter);
	$bos_entry_info = explode("^",$bos_entry_info);
	$style = $bos_entry_info[1].$bos_entry_info[3];
	
	?>
	<tr>
    	<td><?php echo sprintf("%04s",$row_bos['eid']); ?></td>
        <td class="data"><?php echo readable_judging_number($bos_entry_info[1],$bos_entry_info[6]); ?></td>
        <td class="data"><?php echo $bos_entry_info[9] ?></td>
        <td class="data"><?php echo $bos_entry_info[8]; ?></td>
        <td class="data"><?php echo $style." ".style_convert($bos_entry_info[1],1).": ".$bos_entry_info[0]; ?></td>
        <?php if ($dbTable == "default") { ?>
        <td class="data"><?php echo $row_bos['scoreEntry']; ?></td>
        <td class="data"><?php echo $row_bos['scorePlace']; ?></td>
        <?php } ?>
        <?php if ($dbTable != "default") { ?>
        <td class="data"><?php echo $bos_entry_info[5].", ".$bos_entry_info[4]; ?></td>
        <td class="data"><?php echo $bos_entry_info[12]; ?></td>
        
        <?php } ?>
        <td class="data"><?php echo $bos_entry_info[11]; ?></td>
        <td class="data"><?php echo $bos_entry_info[10] ?></td>
    </tr>
    <?php } while ($row_bos = mysql_fetch_assoc($bos)); 
	mysql_free_result($bos);
	?>
</tbody>
</table>
<?php } else echo "<p style='margin: 0 0 40px 0'>No entries are eligible.</p>"; 
} 

?>
<?php } ?>

<?php 
//else echo "<p style='margin: 0 0 40px 0'>No Best of Show <a href='".$base_url."index.php?section=admin&amp;go=style_types' title='Enable Best of Show for one or more style types'>has been enabled</a> for any style type.</p>";
} // end if ($action == "default")
?>

<?php if ($action == "enter") { 
include(DB.'admin_judging_scores_bos.db.php');

?>
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
		
		$bos_entry_info = bos_entry_info($row_enter_bos['eid'], "default");
		$bos_entry_info = explode("^",$bos_entry_info);
		//print_r($bos_entry_info);
		//echo "<br>";
		$style = $bos_entry_info[1].$bos_entry_info[3];
		
	?>
	<tr>
		<?php $score_id = $bos_entry_info[13]; ?>
        <input type="hidden" name="score_id[]" value="<?php echo $score_id; ?>" />
        <input type="hidden" name="scorePrevious<?php echo $score_id; ?>" value="<?php if (!empty($bos_entry_info[10])) echo "Y"; elseif (!empty($bos_entry_info[11])) echo "Y"; else echo "N"; ?>" />
        <input type="hidden" name="eid<?php echo $score_id; ?>" value="<?php echo $score_id; ?>" />
        <input type="hidden" name="bid<?php echo $score_id; ?>" value="<?php echo $bos_entry_info[15]; ?>" />
        <input type="hidden" name="scoreType<?php echo $score_id; ?>" value="<?php echo $filter; ?>" />
        <?php if (!empty($bos_entry_info[14])) { ?>
        <input type="hidden" name="id<?php echo $score_id; ?>" value="<?php echo $bos_entry_info[14]; ?>" />
        <?php } ?>
        <td><?php echo sprintf("%04s",$row_enter_bos['eid']); ?></td>
        <td class="data"><?php echo readable_judging_number($bos_entry_info[2],$bos_entry_info[6]); ?></td>
        <td class="data"><?php echo $style." ".style_convert($bos_entry_info[1],1).": ".$bos_entry_info[0]; ?></td>
    	<td class="data"><input type="text" name="scoreEntry<?php echo $score_id; ?>" size="5" maxlength="2" value="<?php echo $bos_entry_info[11]; ?>" /></td>
        <td>
        <select name="scorePlace<?php echo $score_id; ?>">
          <option value=""></option>
          <?php for($i=1; $i<$_SESSION['jPrefsMaxBOS']+1; $i++) { ?>
          <option value="<?php echo $i; ?>" <?php if ($bos_entry_info[10] == $i) echo "selected"; ?>><?php echo text_number($i); ?></option>
          <?php } ?>
        </select>
        </td>
	</tr>
    <?php 
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