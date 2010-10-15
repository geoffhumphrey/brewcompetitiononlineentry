<?php if (greaterDate($today,$deadline)) echo "<div class='info'>If your competition awards strata is for the overall category only, select the placing entry's category and leave the subcategory blank.</div>"; ?>

<h2>Entries</h2>
<?php if ($action != "print") { ?>
<form name="form1" method="post" action="includes/process.inc.php?action=update&amp;dbTable=brewing&amp;filter=<?php echo $filter; ?>&amp;bid=<?php echo $bid; ?>&amp;sort=<?php echo $sort; ?>&amp;dir=<?php echo $dir; ?>">
<table class="dataTable">
<tr>
  <td class="dataList" width="5%" nowrap="nowrap"><span class="icon"><img src="images/arrow_left.png" alt="Back"></span><a class="data" href="index.php?section=admin">Back to Admin</a></td>
  <?php if ($dbTable != "default") { ?>
  <td class="dataList" width="5%" nowrap="nowrap"><span class="icon"><img src="images/arrow_left.png" alt="Back"></span><a class="data" href="index.php?section=admin&amp;go=archive">Back to Archives</a></td>
  <?php } ?>
  <td class="dataList" width="5%" nowrap="nowrap"><span class="icon"><img src="images/page_edit.png"  /></span><a class="data" href="index.php?section=brew&amp;go=entries&amp;action=add&amp;filter=admin">Add Entry</a></td>
  <?php if ($dbTable == "default") { ?>
  	<?php if (($filter != "default") || ($bid != "default")) { ?>
  	<td class="dataList" width="5%" nowrap="nowrap"><span class="icon"><img src="images/page.png"  /></span><a href="index.php?section=admin&amp;go=entries">View Entries in All Categories</a></td>
  	<?php } ?>
  <?php } ?>
  <td class="dataList">
  <span class="icon"><img src="images/printer.png" /></span>
  <div class="menuBar"><a class="menuButton" href="#" onclick="#" onmouseover="buttonMouseover(event, 'printMenu_entries');">Print This List of Entries</a>
  </div>
  <div id="printMenu_entries" class="menu" onmouseover="menuMouseover(event)">
  <a class="menuItem thickbox" href="print.php?section=<?php echo $section; ?>&amp;go=<?php echo $go; ?>&amp;filter=<?php echo $filter; ?>&amp;action=print&amp;bid=<?php echo $bid; ?>&amp;sort=id&amp;dir=ASC&amp;TB_iframe=true&amp;height=450&amp;width=750&amp;KeepThis=true">By Entry Number</a>
  <a class="menuItem thickbox" href="print.php?section=<?php echo $section; ?>&amp;go=<?php echo $go; ?>&amp;filter=<?php echo $filter; ?>&amp;action=print&amp;bid=<?php echo $bid; ?>&amp;sort=brewCategorySort&amp;dir=ASC&amp;TB_iframe=true&amp;height=450&amp;width=750&amp;KeepThis=true">By Category</a>
  <a class="menuItem thickbox" href="print.php?section=<?php echo $section; ?>&amp;go=<?php echo $go; ?>&amp;filter=<?php echo $filter; ?>&amp;action=print&amp;bid=<?php echo $bid; ?>&amp;sort=brewBrewerLastName&amp;dir=ASC&amp;TB_iframe=true&amp;height=450&amp;width=750&amp;KeepThis=true">By Brewer Last Name</a>
  </div>
  </td>
</tr>
</table>
<?php } ?>
<table class="dataTable">
<tr>
  <td class="dataHeading" width="5%">Total Entries<?php if ($filter != "default") echo " In This Category"; ?>:</td>
  <td class="data"><?php echo $totalRows_log; ?></td>
</tr>
<tr>
  <td class="dataHeading">Total Entry Fees<?php if ($filter != "default") echo " for This Category"; ?>:</td>
  <td class="data">$<?php echo ($totalRows_log * $row_contest_info['contestEntryFee']); ?></td>
</tr>
<tr>
  <td class="dataHeading">Total Paid Entry Fees<?php if ($filter != "default") echo " for This Category"; ?>:</td>
  <td class="data">$<?php echo ($totalRows_log_paid * $row_contest_info['contestEntryFee']); ?></td>
</tr>
<tr>
  	<td colspan="2">&nbsp;</td>
  </tr>
</table>
<?php if ($totalRows_log > 0) { ?>
	<?php if ($action != "print") { ?>
	<p><input type="submit" name="Submit" class="button" value="Update Entries" /></p>
	<script type="text/javascript" language="javascript">
	 $(document).ready(function() {
		$('#sortable').dataTable( {
			"bStateSave" : false,
			"sPaginationType" : "full_numbers",
			"aaSorting": [[2,'asc']],
			"bLengthChange" : true,
			"iDisplayLength" : 25,
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
  <?php if ($action != "print") { ?>
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
  <td <?php if ($action != "print") { ?>width="10%"<?php } else { ?>width="20%"<?php } ?> class="dataList <?php if ($action == "print") echo " bdr1B"; ?>"><?php if (($filter == "default") && ($bid == "default")) { ?><a href="index.php?section=admin&amp;go=entries&amp;filter=<?php echo $row_log['brewCategorySort']; ?>" title="See only the <?php echo $styleConvert; ?> entries"><?php } if ($action != "print") echo $row_log['brewCategory'].$row_log['brewSubCategory']; else echo $row_log['brewCategorySort'].$row_log['brewSubCategory'].": ".$styleConvert; if (($filter == "default") && ($bid == "default")) { ?></a><?php } ?></td>
  <td width="20%" class="dataList <?php if ($action == "print") echo " bdr1B"; ?>"><?php if ($bid == "default") { ?><a href="index.php?section=admin&amp;go=entries&amp;bid=<?php echo $row_log['brewBrewerID']; ?>" title="See only the <?php echo $row_log['brewBrewerFirstName']." ".$row_log['brewBrewerLastName']."&rsquo;s"; ?> entries"><?php } echo  $row_log['brewBrewerLastName'].", ".$row_log['brewBrewerFirstName']; ?><?php if ($bid == "default") { ?></a><?php } ?></td>
  <td width="5%" class="dataList <?php if ($action == "print") echo " bdr1B"; ?>"><?php if ($action != "print") { ?><input id="brewReceived" name="brewReceived<?php echo $row_log['id']; ?>" type="checkbox" value="Y" <?php if ($row_log['brewReceived'] == "Y") echo "checked"; else ""; ?> /><?php } else { if ($row_log['brewReceived'] == "Y") echo "X"; } ?></td>
  <td width="5%" class="dataList <?php if ($action == "print") echo " bdr1B"; ?>"><?php if ($action != "print") { ?><input id="brewPaid" name="brewPaid<?php echo $row_log['id']; ?>" type="checkbox" value="Y" <?php if ($row_log['brewPaid'] == "Y") echo "checked"; else ""; ?> /><?php } else { if ($row_log['brewPaid'] == "Y") echo "X"; } ?></td>
  <td width="5%" class="dataList <?php if ($action == "print") echo " bdr1B"; ?>"><?php if ($action != "print") { ?><input id="brewWinner" name="brewWinner<?php echo $row_log['id']; ?>" type="checkbox" value="Y" <?php if ($row_log['brewWinner'] == "Y") echo "checked"; else ""; ?> /><?php } else { if ($row_log['brewWinner'] == "Y") echo "X"; } ?></td>
  <td width="3%" class="dataList <?php if ($action == "print") echo " bdr1B"; ?>">
  <?php if ($action != "print") { ?>   
  <select name="brewWinnerCat<?php echo $row_log['id']; ?>" id="brewWinnerCat">
  	  <option value="">&nbsp;&nbsp;&nbsp;</option>
      <?php do { ?>
      <option value="<?php echo $row_styles_num['brewStyleGroup']; ?>" <?php if ($row_log['brewWinnerCat'] == $row_styles_num['brewStyleGroup']) echo "SELECTED"; ?>><?php echo $row_styles_num['brewStyleGroup']; ?></option>
      <?php } while ($row_styles_num = mysql_fetch_assoc($styles_num)); ?>
    </select>
  <?php } else echo $row_log['brewWinnerCat']; ?>
  </td>
  <td width="3%" class="dataList <?php if ($action == "print") echo " bdr1B"; ?>">
  <?php if ($action != "print") { ?>   
    <select name="brewWinnerSubCat<?php echo $row_log['id']; ?>" id="brewWinnerSubCat">
  	  <option value="">&nbsp;&nbsp;&nbsp;</option>
      <option value="A" <?php if ($row_log['brewWinnerSubCat'] == "A") echo "SELECTED"; ?>>A</option>
      <option value="B" <?php if ($row_log['brewWinnerSubCat'] == "B") echo "SELECTED"; ?>>B</option>
      <option value="C" <?php if ($row_log['brewWinnerSubCat'] == "C") echo "SELECTED"; ?>>C</option>
      <option value="D" <?php if ($row_log['brewWinnerSubCat'] == "D") echo "SELECTED"; ?>>D</option>
      <option value="E" <?php if ($row_log['brewWinnerSubCat'] == "E") echo "SELECTED"; ?>>E</option>
      <option value="F" <?php if ($row_log['brewWinnerSubCat'] == "F") echo "SELECTED"; ?>>F</option>
      <option value="G" <?php if ($row_log['brewWinnerSubCat'] == "G") echo "SELECTED"; ?>>G</option>
    </select>
    <?php } else echo $row_log['brewWinnerSubCat']; ?>
    </td>
  <td width="3%" class="dataList <?php if ($action == "print") echo " bdr1B"; ?>">
      <?php if ($action != "print") { ?>
      <select name="brewWinnerPlace<?php echo $row_log['id']; ?>" id="brewWinnerPlace">
  	  <option value="">&nbsp;&nbsp;&nbsp;</option>
      <option value="1" <?php if ($row_log['brewWinnerPlace'] == "1") echo "SELECTED"; ?>>1</option>
      <option value="2" <?php if ($row_log['brewWinnerPlace'] == "2") echo "SELECTED"; ?>>2</option>
      <option value="3" <?php if ($row_log['brewWinnerPlace'] == "3") echo "SELECTED"; ?>>3</option>
      <option value="HM" <?php if ($row_log['brewWinnerPlace'] == "HM") echo "SELECTED"; ?>>HM</option>
      </select>
      <?php } else echo $row_log['brewWinnerPlace']; ?>  </td>
  <td width="3%" class="dataList<?php if ($action == "print") echo " bdr1B"; ?>"><?php if ($action != "print") { ?><input id="breBOSRound" name="brewBOSRound<?php echo $row_log['id']; ?>" type="checkbox" value="Y" <?php if ($row_log['brewBOSRound'] == "Y") echo "checked"; else ""; ?> /><?php } else { if ($row_log['brewBOSRound'] == "Y") echo "X"; } ?></td>
  <td width="3%" class="dataList<?php if ($action == "print") echo " bdr1B"; ?>">
  	  <?php if ($action != "print") { ?>
      <select name="brewBOSPlace<?php echo $row_log['id']; ?>" id="brewBOSPlace">
  	  <option value="">&nbsp;&nbsp;&nbsp;</option>
      <option value="1" <?php if ($row_log['brewBOSPlace'] == "1") echo "SELECTED"; ?>>1</option>
      <option value="2" <?php if ($row_log['brewBOSPlace'] == "2") echo "SELECTED"; ?>>2</option>
      <option value="3" <?php if ($row_log['brewBOSPlace'] == "3") echo "SELECTED"; ?>>3</option>
      <option value="HM" <?php if ($row_log['brewBOSPlace'] == "HM") echo "SELECTED"; ?>>HM</option>
      </select>
      <?php } else echo $row_log['brewBOSPlace']; ?>  </td>
  <?php if ($action != "print") { ?>
  <td class="dataList" nowrap="nowrap">
  <span class="icon"><a href="index.php?section=brew&amp;go=<?php echo $go; ?>&amp;filter=<?php echo $row_log['brewBrewerID']; ?>&amp;action=edit&amp;id=<?php echo $row_log['id']; ?>"><img src="images/pencil.png"  border="0" alt="Edit <?php echo $row_log['brewName']; ?>" title="Edit <?php echo $row_log['brewName']; ?>"></a></span><span class="icon"><a href="javascript:DelWithCon('includes/process.inc.php?section=<?php echo $section; ?>&amp;go=<?php echo $go; ?>&amp;filter=<?php echo $filter; ?>&amp;dbTable=brewing&amp;action=delete','id',<?php echo $row_log['id']; ?>,'Are you sure you want to delete the entry called <?php echo $row_log['brewName']; ?>? This cannot be undone.');"><img src="images/bin_closed.png"  border="0" alt="Delete <?php echo $row_log['brewName']; ?>" title="Delete <?php echo $row_log['brewName']; ?>"></a></span><span class="icon"><a class="thickbox" href="sections/entry.sec.php?id=<?php echo $row_log['id']; ?>&amp;bid=<?php echo $row_log['brewBrewerID']; ?>&KeepThis=true&amp;TB_iframe=true&amp;height=425&amp;width=700" title="Print the Entry Forms for <?php echo $row_log['brewName']; ?>"><img src="images/printer.png"  border="0" alt="Print the Entry Forms for <?php echo $row_log['brewName']; ?>" title="Print the Entry Forms for <?php echo $row_log['brewName']; ?>"></a></span></td>
  <?php } ?>
  </tr>
  <?php } } while($row_log = mysql_fetch_assoc($log)) ?>
</tbody>
</table>
<?php if ($action != "print") { ?>
<p><input type="submit" name="Submit" class="button" value="Update Entries" /></p>
</form>
<?php } 
} else echo "<div class=\"error\">There are no entires.</div>"; ?>
