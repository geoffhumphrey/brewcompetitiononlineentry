<?php 
if (greaterDate($today,$deadline)) echo "<div class='info'>If your competition awards strata is for the overall category only, select the placing entry's category and leave the subcategory blank.</div>"; 
?>
<h2>Entries<?php if ($dbTable != "default") echo ": ".ltrim($dbTable, "brewing_"); ?></h2>
<?php if ($action != "print") { ?>
<form name="form1" method="post" action="includes/process.inc.php?action=update&amp;dbTable=brewing&amp;filter=<?php echo $filter; ?>&amp;bid=<?php echo $bid; ?>&amp;sort=<?php echo $sort; ?>&amp;dir=<?php echo $dir; ?>">
<table class="dataTable">
<tr>
  <td class="dataList" width="5%" nowrap="nowrap"><span class="icon"><img src="images/arrow_left.png" alt="Back"></span><a class="data" href="index.php?section=admin">Back to Admin</a></td>
  <?php if ($dbTable != "default") { ?>
  <td class="dataList" width="5%" nowrap="nowrap"><span class="icon"><img src="images/arrow_left.png" alt="Back"></span><a class="data" href="index.php?section=admin&amp;go=archive">Back to Archives</a></td>
  <?php } ?>
  <?php if ($dbTable == "default") { ?>
  <td class="dataList" width="5%" nowrap="nowrap"><span class="icon"><img src="images/page_edit.png"  /></span><a class="data" href="index.php?section=brew&amp;go=entries&amp;action=add&amp;filter=admin">Add Entry</a></td>
  	<?php if (($filter != "default") || ($bid != "default")) { ?>
  	<td class="dataList" width="5%" nowrap="nowrap"><span class="icon"><img src="images/page.png"  /></span><a href="index.php?section=admin&amp;go=entries">View Entries in All Categories</a></td>
  	<?php } ?>
  <?php } ?>
  <td class="dataList" width="5%" nowrap="nowrap">
  <span class="icon"><img src="images/printer.png" /></span>
  <div class="menuBar"><a class="menuButton" href="#" onclick="#" onmouseover="buttonMouseover(event, 'printMenu_entries');">Print <em>This</em> List</a>
  </div>
  <div id="printMenu_entries" class="menu" onmouseover="menuMouseover(event)">
  	<a class="menuItem thickbox" href="print.php?<?php echo $_SERVER['QUERY_STRING']; ?>&amp;action=print&amp;psort=entry_number&amp;TB_iframe=true&amp;height=450&amp;width=750&amp;KeepThis=true">By Entry Number</a>
  	<a class="menuItem thickbox" href="print.php?<?php echo $_SERVER['QUERY_STRING']; ?>&amp;action=print&amp;psort=category&amp;TB_iframe=true&amp;height=450&amp;width=750&amp;KeepThis=true">By Category</a>
  	<a class="menuItem thickbox" href="print.php?<?php echo $_SERVER['QUERY_STRING']; ?>&amp;action=print&amp;psort=brewer_name&amp;TB_iframe=true&amp;height=450&amp;width=750&amp;KeepThis=true">By Brewer Last Name</a>
  	<a class="menuItem thickbox" href="print.php?<?php echo $_SERVER['QUERY_STRING']; ?>&amp;action=print&amp;psort=entry_name&amp;TB_iframe=true&amp;height=450&amp;width=750&amp;KeepThis=true">By Entry Name</a>
  </div>
  </td>
  <td class="dataList">
  <?php if (($totalRows_entry_count > $limit) && ($filter == "default")) { ?>
  <span class="icon"><img src="images/printer.png" /></span>
  <div class="menuBar"><a class="menuButton" href="#" onclick="#" onmouseover="buttonMouseover(event, 'printMenu_entries_all');">Print <em>All</em></a>
  </div>
  <div id="printMenu_entries_all" class="menu" onmouseover="menuMouseover(event)">
  	<a class="menuItem thickbox" href="print.php?<?php echo $_SERVER['QUERY_STRING']; ?>&amp;action=print&amp;view=all&amp;psort=entry_number&amp;TB_iframe=true&amp;height=450&amp;width=750&amp;KeepThis=true">By Entry Number</a>
  	<a class="menuItem thickbox" href="print.php?<?php echo $_SERVER['QUERY_STRING']; ?>&amp;action=print&amp;view=all&amp;psort=category&amp;TB_iframe=true&amp;height=450&amp;width=750&amp;KeepThis=true">By Category</a>
  	<a class="menuItem thickbox" href="print.php?<?php echo $_SERVER['QUERY_STRING']; ?>&amp;action=print&amp;view=all&amp;psort=brewer_name&amp;TB_iframe=true&amp;height=450&amp;width=750&amp;KeepThis=true">By Brewer Last Name</a>
  	<a class="menuItem thickbox" href="print.php?<?php echo $_SERVER['QUERY_STRING']; ?>&amp;action=print&amp;view=all&amp;psort=entry_name&amp;TB_iframe=true&amp;height=450&amp;width=750&amp;KeepThis=true">By Entry Name</a>
  </div>
  <?php } else echo "&nbsp;"; ?>
  </td>
</tr>
</table>
<?php } ?>
<?php if ($dbTable == "default") { ?>
<table class="dataTable">
<tr>
  <td class="dataHeading" width="5%">Total Entries<?php if ($filter != "default") echo " In This Category"; ?>:</td>
  <td class="data"><?php if ($filter == "default") echo $totalRows_entry_count; else echo $totalRows_log; ?></td>
</tr>
<tr>
  <td class="dataHeading">Total Entry Fees:</td>
  <td class="data"><?php echo $row_prefs['prefsCurrency'].$total_entry_fees; ?></td>
</tr>
<tr>
  <td class="dataHeading">Total Paid Entry Fees:</td>
  <td class="data"><?php echo $row_prefs['prefsCurrency'].$total_paid_entry_fees; ?></td>
</tr>

<tr>
  <td class="dataHeading">Total Unpaid Entry Fees:</td>
  <td class="data"><?php echo $row_prefs['prefsCurrency'].($total_entry_fees - $total_paid_entry_fees); ?></td>
</tr>

<tr>
  	<td colspan="2">&nbsp;</td>
  </tr>
</table>
<?php } ?>
<?php if ($totalRows_log > 0) { ?>
	<?php if ($action != "print") { ?>
  	<?php if ($dbTable == "default") { ?><p><input type="submit" name="Submit" class="button" value="Update Entries" /></p><?php } ?>
    	<?php 
		if (($dbTable == "default") && ($totalRows_entry_count > $row_prefs['prefsRecordLimit']))	{ 
			$of = $start + $totalRows_log;
			echo "<div id=\"sortable_info\" class=\"dataTables_info\">Showing ".$start_display." to ".$of." of ".$totalRows_entry_count." entries</div>";
			}
	 	?>
	<script type="text/javascript" language="javascript">
	 $(document).ready(function() {
		$('#sortable').dataTable( {
			<?php if (($totalRows_entry_count <= $row_prefs['prefsRecordLimit']) || ((($section == "admin") && ($go == "entries") && ($filter == "default")  && ($dbTable != "default")))) { ?>
			"bPaginate" : true,
			"sPaginationType" : "full_numbers",
			"bLengthChange" : true,
			"iDisplayLength" :  <?php echo round($row_prefs['prefsRecordPaging']); ?>,
			"sDom": 'irtip',
			"bStateSave" : false,
			<?php } else { ?>
			"bPaginate" : false,
			"sDom": 'rt',
			"bStateSave" : false,
			"bLengthChange" : false,
			<?php } ?>
			"aaSorting": [[2,'asc']],
			"bProcessing" : true,
			"aoColumns": [
				null,
				null,
				null,
				null,
				{ "asSorting": [  ] },
				{ "asSorting": [  ] },
				{ "asSorting": [  ] },
				{ "asSorting": [  ] },
				{ "asSorting": [  ] },
				{ "asSorting": [  ] },
				{ "asSorting": [  ] },
				{ "asSorting": [  ] },
				<?php if ($dbTable == "default") { ?>
				{ "asSorting": [  ] },
				<?php } ?>
				]
			} );
		} );
	</script>
	<?php } ?>
	<?php if ($action == "print") { ?>
	<script type="text/javascript" language="javascript">
	 $(document).ready(function() {
		$('#sortable').dataTable( {
			"bPaginate" : false,
			"sDom": 'rt',
			"bStateSave" : false,
			"bLengthChange" : false,
			<?php if ($psort == "entry_number") { ?>"aaSorting": [[0,'asc']],<?php } ?>
			<?php if ($psort == "category") { ?>"aaSorting": [[2,'asc']],<?php } ?>
			<?php if ($psort == "brewer_name") { ?>"aaSorting": [[3,'asc']],<?php } ?>
			<?php if ($psort == "entry_name") { ?>"aaSorting": [[1,'asc']],<?php } ?>
			"bProcessing" : false,
			"aoColumns": [
				{ "asSorting": [  ] },
				{ "asSorting": [  ] },
				{ "asSorting": [  ] },
				{ "asSorting": [  ] },
				{ "asSorting": [  ] },
				{ "asSorting": [  ] },
				{ "asSorting": [  ] },
				{ "asSorting": [  ] },
				{ "asSorting": [  ] },
				{ "asSorting": [  ] },
				{ "asSorting": [  ] },
				{ "asSorting": [  ] },
				]
			} );
		} );
	</script>
	<?php } ?>
<table class="dataTable" id="sortable">
<thead>
 <tr>
  <th class="dataHeading bdr1B">Ent. #</th>
  <th class="dataHeading bdr1B">Name</th>
  <th class="dataHeading bdr1B">Category</th>
  <th class="dataHeading bdr1B">Brewer</th>
  <th class="dataHeading bdr1B">Rec'd?</th>
  <th class="dataHeading bdr1B">Paid?</th>
  <th class="dataHeading bdr1B">Win?</th>
  <th class="dataHeading bdr1B">Category</th>
  <th class="dataHeading bdr1B">Sub-cat.</th>
  <th class="dataHeading bdr1B">Place</th>
  <th class="dataHeading bdr1B">BOS?</th>
  <th class="dataHeading bdr1B">BOS Place</th>
  <?php if (($action != "print") && ($dbTable == "default")) { ?>
  <th class="dataHeading bdr1B">Actions</th>
  <?php } ?>
 </tr>
 </thead>
 <tbody>
 <?php 
 	do {
	{  
    include ('includes/style_convert.inc.php');
	mysql_select_db($database, $brewing);
	if ($row_log['brewCategory'] < 10) $fix = "0"; else $fix = "";
	$query_style = sprintf("SELECT * FROM styles WHERE brewStyleGroup = '%s' AND brewStyleNum = '%s'", $fix.$row_log['brewCategory'], $row_log['brewSubCategory']);
	$style = mysql_query($query_style, $brewing) or die(mysql_error());
	$row_style = mysql_fetch_assoc($style);
	
	$query_styles_num = "SELECT DISTINCT brewStyleGroup FROM styles ORDER BY brewStyleGroup ASC";
	$styles_num = mysql_query($query_styles_num, $brewing) or die(mysql_error());
	$row_styles_num = mysql_fetch_assoc($styles_num);
	$totalRows_styles_num = mysql_num_rows($styles_num);
	
	?>
 <tr>
   <input type="hidden" name="id[]" value="<?php echo $row_log['id']; ?>" />
  <td width="5%" class="dataList <?php if ($action == "print") echo " bdr1B"; ?>"><?php echo $row_log['id']; ?></td>
  <td <?php if ($action != "print") { ?>width="20%"<?php } else { ?>width="20%"<?php } ?> class="dataList <?php if ($action == "print") echo " bdr1B"; ?>"><?php echo $row_log['brewName']; ?></td>
  <td <?php if ($action != "print") { ?>width="10%"<?php } else { ?>width="20%"<?php } ?> class="dataList <?php if ($action == "print") echo " bdr1B"; ?>"><?php if (($filter == "default") && ($bid == "default") && ($dbTable == "default")) { ?><a href="index.php?section=admin&amp;go=entries&amp;filter=<?php echo $row_log['brewCategorySort']; ?>" title="See only the <?php echo $styleConvert; ?> entries"><?php } if ($action != "print") echo $row_log['brewCategorySort'].$row_log['brewSubCategory']; else echo $row_log['brewCategorySort'].$row_log['brewSubCategory'].": ".$styleConvert; if (($filter == "default") && ($bid == "default") && ($dbTable == "default")) { ?></a><?php } ?></td>
  <td width="20%" class="dataList <?php if ($action == "print") echo " bdr1B"; ?>"><?php if (($bid == "default") && ($dbTable == "default")) { ?><a href="index.php?section=admin&amp;go=entries&amp;bid=<?php echo $row_log['brewBrewerID']; ?>" title="See only the <?php echo $row_log['brewBrewerFirstName']." ".$row_log['brewBrewerLastName']."&rsquo;s"; ?> entries"><?php } echo  $row_log['brewBrewerLastName'].", ".$row_log['brewBrewerFirstName']; ?><?php if (($bid == "default") && ($dbTable == "default")) { ?></a><?php } ?></td>
  <td width="5%" class="dataList <?php if ($action == "print") echo " bdr1B"; ?>"><?php if (($action != "print") && ($dbTable == "default")) { ?><input id="brewReceived" name="brewReceived<?php echo $row_log['id']; ?>" type="checkbox" value="Y" <?php if ($row_log['brewReceived'] == "Y") echo "checked"; else ""; ?> /><?php } else { if (($row_log['brewReceived'] == "Y") && ($dbTable != "default")) echo "X"; } ?></td>
  <td width="5%" class="dataList <?php if ($action == "print") echo " bdr1B"; ?>"><?php if (($action != "print") && ($dbTable == "default")) { ?><input id="brewPaid" name="brewPaid<?php echo $row_log['id']; ?>" type="checkbox" value="Y" <?php if ($row_log['brewPaid'] == "Y") echo "checked"; else ""; ?> /><?php } else { if (($row_log['brewPaid'] == "Y") && ($dbTable != "default")) echo "X"; } ?></td>
  <td width="5%" class="dataList <?php if ($action == "print") echo " bdr1B"; ?>"><?php if (($action != "print") && ($dbTable == "default")) { ?><input id="brewWinner" name="brewWinner<?php echo $row_log['id']; ?>" type="checkbox" value="Y" <?php if ($row_log['brewWinner'] == "Y") echo "checked"; else ""; ?> /><?php } else { if (($row_log['brewWinner'] == "Y") && ($dbTable != "default")) echo "X"; } ?></td>
  <td width="3%" class="dataList <?php if ($action == "print") echo " bdr1B"; ?>">
  <?php if (($action != "print") && ($dbTable == "default")) { ?>   
  <input type="text" name="brewWinnerCat<?php echo $row_log['id']; ?>" id="brewWinnerCat" size="3" value="<?php echo $row_log['brewWinnerCat']; ?>" />
  <?php } else echo $row_log['brewWinnerCat']; ?>
  </td>
  <td width="3%" class="dataList <?php if ($action == "print") echo " bdr1B"; ?>">
  <?php if (($action != "print") && ($dbTable == "default")) { ?>   
  <input type="text" name="brewWinnerSubCat<?php echo $row_log['id']; ?>" id="brewWinnerSubCat" size="3" value="<?php echo $row_log['brewWinnerSubCat']; ?>" />
  <?php } else echo $row_log['brewWinnerSubCat']; ?>
  </td>
  <td width="3%" class="dataList <?php if ($action == "print") echo " bdr1B"; ?>">
  <?php if (($action != "print") && ($dbTable == "default")) { ?>
  <input type="text" name="brewWinnerPlace<?php echo $row_log['id']; ?>" id="brewWinnerPlace" size="3" value="<?php echo $row_log['brewWinnerPlace']; ?>" />
  <?php } else echo $row_log['brewWinnerPlace']; ?>
  </td>
  <td width="3%" class="dataList<?php if ($action == "print") echo " bdr1B"; ?>"><?php if (($action != "print") && ($dbTable == "default")) { ?><input id="brewBOSRound" name="brewBOSRound<?php echo $row_log['id']; ?>" type="checkbox" value="Y" <?php if ($row_log['brewBOSRound'] == "Y") echo "checked"; else ""; ?> /><?php } else { if (($row_log['brewBOSRound'] == "Y") && ($dbTable != "default")) echo "X"; } ?></td>
  <td width="3%" class="dataList<?php if ($action == "print") echo " bdr1B"; ?>">
  	  <?php if (($action != "print") && ($dbTable == "default")) { ?>
      <input type="text" name="brewBOSPlace<?php echo $row_log['id']; ?>" id="brewBOSPlace" size="3" value="<?php echo $row_log['brewBOSPlace']; ?>" />
      <?php } else echo $row_log['brewBOSPlace']; ?>  </td>
  <?php if (($action != "print") && ($dbTable == "default")) { ?>
  <td class="dataList" nowrap="nowrap">
  <span class="icon"><a href="index.php?section=brew&amp;go=<?php echo $go; ?>&amp;filter=<?php echo $row_log['brewBrewerID']; ?>&amp;action=edit&amp;id=<?php echo $row_log['id']; ?>"><img src="images/pencil.png"  border="0" alt="Edit <?php echo $row_log['brewName']; ?>" title="Edit <?php echo $row_log['brewName']; ?>"></a></span><span class="icon"><a href="javascript:DelWithCon('includes/process.inc.php?section=<?php echo $section; ?>&amp;go=<?php echo $go; ?>&amp;filter=<?php echo $filter; ?>&amp;dbTable=brewing&amp;action=delete','id',<?php echo $row_log['id']; ?>,'Are you sure you want to delete the entry called <?php echo $row_log['brewName']; ?>? This cannot be undone.');"><img src="images/bin_closed.png"  border="0" alt="Delete <?php echo $row_log['brewName']; ?>" title="Delete <?php echo $row_log['brewName']; ?>"></a></span><span class="icon"><a class="thickbox" href="sections/entry.sec.php?id=<?php echo $row_log['id']; ?>&amp;bid=<?php echo $row_log['brewBrewerID']; ?>&KeepThis=true&amp;TB_iframe=true&amp;height=425&amp;width=700" title="Print the Entry Forms for <?php echo $row_log['brewName']; ?>"><img src="images/printer.png"  border="0" alt="Print the Entry Forms for <?php echo $row_log['brewName']; ?>" title="Print the Entry Forms for <?php echo $row_log['brewName']; ?>"></a></span>
  </td>
  <?php } ?>
  </tr>
  <?php } } while($row_log = mysql_fetch_assoc($log)) ?>
</tbody>
</table>

<?php if ($action != "print") {  
	if (($dbTable == "default") && ($totalRows_entry_count >= $row_prefs['prefsRecordLimit']))	{
	if (($filter == "default") && ($bid == "default")) $total_paginate = $totalRows_entry_count;
	else $total_paginate = $totalRows_log;
	paginate($row_prefs['prefsRecordPaging'], $pg, $total_paginate);
	}
?>
<?php if ($dbTable == "default") { ?><p><input type="submit" name="Submit" class="button" value="Update Entries" /></p><?php } ?>
</form>

<?php } 
} else echo "<div class=\"error\">There are no entires.</div>"; ?>
