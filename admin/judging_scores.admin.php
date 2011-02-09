<h2><?php 
if ($action == "edit") echo "Edit Scores for Table #".$row_tables_edit['tableNumber'].": ".$row_tables_edit['tableName'];  
elseif ($action == "add") echo "Add Scores for Table #".$row_tables_edit['tableNumber'].": ".$row_tables_edit['tableName'];  
else echo "Scores"; 
if ($dbTable != "default") echo ": ".ltrim($dbTable, "brewer_"); 
?></h2>

<?php if (($action == "add") || ($action == "edit")) { ?>
<table class="dataTable" style="margin: 0 0 20px 0;">
<tbody>
<tr>
  	<td class="dataList" width="5%" nowrap="nowrap"><span class="icon"><img src="images/arrow_left.png" alt="Back"></span><a class="data" href="index.php?section=admin">Back to Admin</a></td> 
  	<td class="dataList" width="5%" nowrap="nowrap"><span class="icon"><img src="images/arrow_left.png" alt="Back"></span><a class="data" href="index.php?section=admin&go=judging_tables">Back to Tables List</a></td>
    <td class="dataList" nowrap="nowrap"><span class="icon"><img src="images/note_add.png" alt="Define Another Flight"></span><a class="data" href="index.php?section=admin&go=judging_scores">Define/Edit Another Table's Scores</a></td>
</tr>
</tbody>
</table>

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
    	<th class="dataList" width="1%" nowrap="nowrap">#</th>
        <th class="dataList" width="35%">Category</th>
    	<th class="dataList" width="1%" nowrap="nowrap">Score</th>
        <th class="dataList">Place</th>
    </tr>
</thead>
<tbody>
	<?php 
	$a = explode(",", $row_tables_edit['tableStyles']); 
	//echo $row_tables_edit['tableStyles'];
	
	foreach (array_unique($a) as $value) {
		
		$query_styles = sprintf("SELECT brewStyle FROM styles WHERE id='%s'", $value);
		$styles = mysql_query($query_styles, $brewing) or die(mysql_error());
		$row_styles = mysql_fetch_assoc($styles);
		
		$query_entries = sprintf("SELECT id,brewBrewerID,brewStyle,brewCategorySort,brewCategory,brewSubCategory,brewInfo FROM brewing WHERE brewStyle='%s' AND brewPaid='Y' AND brewReceived='Y'", $row_styles['brewStyle']);
		$entries = mysql_query($query_entries, $brewing) or die(mysql_error());
		$row_entries = mysql_fetch_assoc($entries);
		$style = $row_entries['brewCategory'].$row_entries['brewSubCategory'];
		
		

		do {

			if ($action == "edit") {
			$query_scores = sprintf("SELECT * FROM judging_scores WHERE eid='%s'", $row_entries['id']);
			$scores = mysql_query($query_scores, $brewing) or die(mysql_error());
			$row_scores = mysql_fetch_assoc($scores);
			}
		
	?>

	<tr>
		<?php if ($action == "add") $id = $row_entries['id']; else $id = $row_scores['id']; ?>
        <input type="hidden" name="id[]" value="<?php echo $id; ?>" />
    	<input type="hidden" name="eid<?php echo $id; ?>" value="<?php if ($action == "add") echo $row_entries['id']; if ($action == "edit") echo $row_scores['eid']; ?>" />
        <input type="hidden" name="bid<?php echo $id; ?>" value="<?php if ($action == "add") echo $row_entries['brewBrewerID']; if ($action == "edit") echo $row_scores['bid']?>" />
        <input type="hidden" name="scoreTable<?php echo $id; ?>" value="<?php echo $row_tables_edit['id']; ?>" />
        <td><?php echo $row_entries['id']; ?></td>
        <td><?php echo $style." ".style_convert($row_entries['brewCategorySort'],1).": ".$row_entries['brewStyle']; ?></td>
    	<td class="data"><input type="text" name="scoreEntry<?php echo $id; ?>" size="5" maxlength="2" value="<?php if ($action == "edit") echo $row_scores['scoreEntry']; ?>" /></td>
        <td>
        <select name="scorePlace<?php echo $id; ?>">
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
<input type="hidden" name="relocate" value="<?php echo relocate($_SERVER['HTTP_REFERER']); ?>">
</form>
<?php } ?>
