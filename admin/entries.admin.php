<?php
include(DB.'entries.db.php');
if ((greaterDate($today,$row_contest_info['contestRegistrationDeadline'])) && ($row_prefs['prefsCompOrg'] == "N")) echo "<div class='info'>If your competition awards strata is for the overall category only, select the placing entry's category and leave the subcategory blank.</div>"; 
?>
<h2>Entries<?php if ($dbTable != "default") echo ": ".ltrim($dbTable, "brewing_"); ?></h2>
<?php if ($action != "print") { ?>
<form name="form1" method="post" action="includes/process.inc.php?action=update&amp;dbTable=brewing&amp;filter=<?php echo $filter; ?>&amp;bid=<?php echo $bid; ?>&amp;sort=<?php echo $sort; ?>&amp;dir=<?php echo $dir; ?>">
<div class="adminSubNavContainer">
  	<span class="adminSubNav">
    	<span class="icon"><img src="images/arrow_left.png" alt="Back"></span><a href="index.php?section=admin">Back to Admin</a>
  	</span>
	<?php if ($dbTable != "default") { ?>
  	<span class="adminSubNav">
    	<span class="icon"><img src="images/arrow_left.png" alt="Back"></span><a href="index.php?section=admin&amp;go=archive">Back to Archives</a>
  	</span>
	<?php } ?>
  	<?php if ($dbTable == "default") { ?>
  	<span class="adminSubNav">
    	<span class="icon"><img src="images/page_edit.png"  /></span><a href="index.php?section=brew&amp;go=entries&amp;action=add&amp;filter=admin">Add an Entry</a>
  	</span>
	<?php if (($filter != "default") || ($bid != "default")) { ?>
  	<span class="adminSubNav">
    	<span class="icon"><img src="images/page.png"  /></span><a href="index.php?section=admin&amp;go=entries">View Entries <?php if ($filter != "default") echo " for All Categories"; if ($bid != "default") echo " for all Particpants";?></a>
   	</span>
    <?php } ?>
	
  	<span class="adminSubNav">
    	<span class="icon"><img src="images/printer.png" /></span><div class="menuBar"><a class="menuButton" href="#" onclick="#" onmouseover="buttonMouseover(event, 'printMenu_entries');">Print <em>This</em> List</a></div>
  		<div id="printMenu_entries" class="menu" onmouseover="menuMouseover(event)">
  			<a class="menuItem thickbox" href="output/print.php?<?php echo $_SERVER['QUERY_STRING']; ?>&amp;action=print&amp;psort=entry_number&amp;TB_iframe=true&amp;height=450&amp;width=750&amp;KeepThis=true">By Entry Number</a>
  			<a class="menuItem thickbox" href="output/print.php?<?php echo $_SERVER['QUERY_STRING']; ?>&amp;action=print&amp;psort=category&amp;TB_iframe=true&amp;height=450&amp;width=750&amp;KeepThis=true">By Category</a>
  			<a class="menuItem thickbox" href="output/print.php?<?php echo $_SERVER['QUERY_STRING']; ?>&amp;action=print&amp;psort=brewer_name&amp;TB_iframe=true&amp;height=450&amp;width=750&amp;KeepThis=true">By Brewer Last Name</a>
  			<a class="menuItem thickbox" href="output/print.php?<?php echo $_SERVER['QUERY_STRING']; ?>&amp;action=print&amp;psort=entry_name&amp;TB_iframe=true&amp;height=450&amp;width=750&amp;KeepThis=true">By Entry Name</a>
  		</div>
  </span>
  <?php if (($totalRows_entry_count > $limit) && ($filter == "default")) { ?>
  <span class="adminSubNav">
    	<span class="icon"><img src="images/printer.png" /></span><div class="menuBar"><a class="menuButton" href="#" onclick="#" onmouseover="buttonMouseover(event, 'printMenu_entries_all');">Print <em>All</em></a></div>
  		<div id="printMenu_entries_all" class="menu" onmouseover="menuMouseover(event)">
  			<a class="menuItem thickbox" href="output/print.php?<?php echo $_SERVER['QUERY_STRING']; ?>&amp;action=print&amp;view=all&amp;psort=entry_number&amp;TB_iframe=true&amp;height=450&amp;width=750&amp;KeepThis=true">By Entry Number</a>
  			<a class="menuItem thickbox" href="output/print.php?<?php echo $_SERVER['QUERY_STRING']; ?>&amp;action=print&amp;view=all&amp;psort=category&amp;TB_iframe=true&amp;height=450&amp;width=750&amp;KeepThis=true">By Category</a>
  			<a class="menuItem thickbox" href="output/print.php?<?php echo $_SERVER['QUERY_STRING']; ?>&amp;action=print&amp;view=all&amp;psort=brewer_name&amp;TB_iframe=true&amp;height=450&amp;width=750&amp;KeepThis=true">By Brewer Last Name</a>
  			<a class="menuItem thickbox" href="output/print.php?<?php echo $_SERVER['QUERY_STRING']; ?>&amp;action=print&amp;view=all&amp;psort=entry_name&amp;TB_iframe=true&amp;height=450&amp;width=750&amp;KeepThis=true">By Entry Name</a>
  		</div>
  </span>
  <?php } ?>
  <?php } ?>
</div>
<?php if ($dbTable == "default") { ?>
<div class="adminSubNavContainer">
  	<span class="adminSubNav">
    <span class="icon"><img src="images/tick.png"  /></span>Mark Entries as Paid/Received for Category: <?php echo style_choose($section,$go,$action,$filter,"index.php","none"); ?></span>
</div>
<?php } 
} ?>
<?php if ($dbTable == "default") { 
$total_fees = total_fees($row_contest_info['contestEntryFee'], $row_contest_info['contestEntryFee2'], $row_contest_info['contestEntryFeeDiscount'], $row_contest_info['contestEntryFeeDiscountNum'], $row_contest_info['contestEntryCap'], $row_contest_info['contestEntryFeePasswordNum'], $bid, $filter);
$total_fees_paid = total_fees_paid($row_contest_info['contestEntryFee'], $row_contest_info['contestEntryFee2'], $row_contest_info['contestEntryFeeDiscount'], $row_contest_info['contestEntryFeeDiscountNum'], $row_contest_info['contestEntryCap'], $row_contest_info['contestEntryFeePasswordNum'], $bid, $filter);
$total_fees_unpaid = ($total_fees - $total_fees_paid);
/*
$total_fees = total_fees($bid, $row_contest_info['contestEntryFee'], $row_contest_info['contestEntryFee2'], $row_contest_info['contestEntryFeeDiscount'], $row_contest_info['contestEntryFeeDiscountNum'], $row_contest_info['contestEntryCap'], $filter);
$total_fees_paid = total_fees_paid($bid, $row_contest_info['contestEntryFee'], $row_contest_info['contestEntryFee2'], $row_contest_info['contestEntryFeeDiscount'], $row_contest_info['contestEntryFeeDiscountNum'], $row_contest_info['contestEntryCap'], $filter);
$total_fees_unpaid = ($total_fees - $total_fees_paid);
*/
?>
<table class="dataTable">
<tr>
  <td class="dataHeading" width="5%">Total Entries<?php if ($filter != "default") echo " in this Category"; if ($bid != "default") echo " for this Particpant";?>:</td>
  <td class="data"><?php if (($filter == "default") && ($bid == "default")) echo $totalRows_entry_count; else  echo $totalRows_log; ?></td>
</tr>
<tr>
  <td class="dataHeading">Total Entry Fees<?php if ($filter != "default") echo " in this Category"; if ($bid != "default") echo " for this Particpant";?>:</td>
  <td class="data"><?php echo $row_prefs['prefsCurrency'].$total_fees; ?></td>
</tr>
<tr>
  <td class="dataHeading">Total Paid Entry Fees<?php if ($filter != "default") echo " in this Category"; if ($bid != "default") echo " for this Particpant";?>:</td>
  <td class="data"><?php echo $row_prefs['prefsCurrency'].$total_fees_paid; ?></td>
</tr>
<tr>
  <td class="dataHeading">Total Unpaid Entry Fees<?php if ($filter != "default") echo " in this Category"; if ($bid != "default") echo " for this Particpant";?>:</td>
  <td class="data"><?php echo $row_prefs['prefsCurrency'].$total_fees_unpaid; ?></td>
</tr>
</table>
<?php } ?>
<?php if ($totalRows_log > 0) { ?>
	<?php if ($action != "print") { ?>
  	<?php if ($dbTable == "default") { ?>
    <p><input type="submit" name="Submit" class="button" value="Update Entries" />&nbsp;<span class="required">Click "Update Entries" <em>before</em> paging through records.</span></p>
	<?php } ?>
    	<?php 
		if (($dbTable == "default") && ($totalRows_entry_count > $row_prefs['prefsRecordLimit']))	{ 
			$of = $start + $totalRows_log;
			echo "<div id=\"sortable_info\" class=\"dataTables_info\">Showing ".$start_display." to ".$of;
			if ($bid != "default") echo " of ".$totalRows_log." entries</div>";
			if ($bid == "default") echo " of ".$totalRows_entry_count." entries</div>";
			}
	 	?>
	<script type="text/javascript" language="javascript">
	 $(document).ready(function() {
		$('#sortable').dataTable( {
			<?php if (($totalRows_entry_count <= $row_prefs['prefsRecordLimit']) || ((($section == "admin") && ($go == "entries") && ($filter == "default")  && ($dbTable != "default")))) { ?>
			"bPaginate" : true,
			"sPaginationType" : "full_numbers",
			"bLengthChange" : false,
			"iDisplayLength" :  <?php echo round($row_prefs['prefsRecordPaging']); ?>,
			"sDom": 'ifrtip',
			"bStateSave" : false,
			<?php } else { ?>
			"bPaginate" : false,
			"sDom": 'rt',
			"bStateSave" : false,
			"bLengthChange" : false,
			<?php } ?>
			"aaSorting": [[3,'asc']],
			"bProcessing" : true,
			"aoColumns": [
				null,
				null,
				null,
				null,
				null,
				{ "asSorting": [  ] },
				{ "asSorting": [  ] }
				<?php if ($row_prefs['prefsCompOrg'] == "N") { ?>,
				{ "asSorting": [  ] },
				{ "asSorting": [  ] },
				{ "asSorting": [  ] },
				{ "asSorting": [  ] },
				{ "asSorting": [  ] },
				{ "asSorting": [  ] }
				<?php } ?>
				<?php if ($dbTable == "default") { ?>,
				{ "asSorting": [  ] }
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
			<?php if ($psort == "judging_number") { ?>"aaSorting": [[1,'asc']],<?php } ?>
			<?php if ($psort == "entry_name") { ?>"aaSorting": [[2,'asc']],<?php } ?>
			<?php if ($psort == "category") { ?>"aaSorting": [[3,'asc']],<?php } ?>
			<?php if ($psort == "brewer_name") { ?>"aaSorting": [[4,'asc']],<?php } ?>
			
			"aoColumns": [
				null,
				null,
				null,
				null,
				null,
				null,
				null<?php if ($row_prefs['prefsCompOrg'] == "N") { ?>,
				{ "asSorting": [  ] },
				{ "asSorting": [  ] },
				{ "asSorting": [  ] },
				{ "asSorting": [  ] },
				{ "asSorting": [  ] },
				{ "asSorting": [  ] }
			<?php } ?>
				
				]
			} );
		} );
	</script>
	<?php } ?>
<table class="dataTable" id="sortable">
<thead>
 <tr>
  <th width="7%" class="dataHeading bdr1B">Entry #</th>
  <th width="7%" class="dataHeading bdr1B">Judging #</th>
  <th width="15%" class="dataHeading bdr1B">Name</th>
  <th width="15%" class="dataHeading bdr1B">Category</th>
  <th width="15%" class="dataHeading bdr1B">Brewer</th>
  <th width="3%" class="dataHeading bdr1B">Paid?</th>
  <th width="3%" class="dataHeading bdr1B">Rec'd?</th>
  <?php if ($row_prefs['prefsCompOrg'] == "N") { ?>
  <th width="3%" class="dataHeading bdr1B">Win?</th>
  <th width="3%" class="dataHeading bdr1B">Category</th>
  <th width="3%" class="dataHeading bdr1B">Sub-cat.</th>
  <th width="3%" class="dataHeading bdr1B">Place</th>
  <th width="3%" class="dataHeading bdr1B">BOS?</th>
  <th width="3%" class="dataHeading bdr1B">BOS Place</th>
  <?php } ?>
  <?php if (($action != "print") && ($dbTable == "default")) { ?>
  <th class="dataHeading bdr1B">Actions</th>
  <?php } ?>
 </tr>
 </thead>
 <tbody>
 <?php 
 	do {
	{  
	mysql_select_db($database, $brewing);
	if ($row_log['brewCategory'] < 10) $fix = "0"; else $fix = "";
	$query_style = sprintf("SELECT * FROM styles WHERE brewStyleGroup = '%s' AND brewStyleNum = '%s'", $fix.$row_log['brewCategory'], $row_log['brewSubCategory']);
	$style = mysql_query($query_style, $brewing) or die(mysql_error());
	$row_style = mysql_fetch_assoc($style);
	
	$query_brewer = sprintf("SELECT brewerDiscount FROM brewer WHERE uid='%s'",$row_log['brewBrewerID']);
	$brewer = mysql_query($query_brewer, $brewing) or die(mysql_error());
	$row_brewer = mysql_fetch_array($brewer);
	
	$query_styles_num = "SELECT DISTINCT brewStyleGroup FROM styles ORDER BY brewStyleGroup ASC";
	$styles_num = mysql_query($query_styles_num, $brewing) or die(mysql_error());
	$row_styles_num = mysql_fetch_assoc($styles_num);
	$totalRows_styles_num = mysql_num_rows($styles_num);
	
	$styleConvert = style_convert($row_log['brewCategorySort'], 1);
	
	
	?>
 <tr>
  <input type="hidden" name="id[]" value="<?php echo $row_log['id']; ?>" />
  <td class="dataList <?php if ($action == "print") echo " bdr1B"; ?>"><?php echo $row_log['id']; ?></td>
  <td class="dataList <?php if ($action == "print") echo " bdr1B"; ?>"><?php echo $row_log['brewJudgingNumber']; ?></td>
  <td class="dataList <?php if ($action == "print") echo " bdr1B"; ?>"><?php echo $row_log['brewName']; ?></td>
  <td class="dataList <?php if ($action == "print") echo " bdr1B"; ?>"><?php if (($filter == "default") && ($bid == "default") && ($dbTable == "default")) { ?><a href="index.php?section=admin&amp;go=entries&amp;filter=<?php echo $row_log['brewCategorySort']; ?>" title="See only the <?php echo $styleConvert; ?> entries"><?php } echo $row_log['brewCategorySort'].$row_log['brewSubCategory'].": ".$row_log['brewStyle']; if (($filter == "default") && ($bid == "default") && ($dbTable == "default")) { ?></a><?php } ?></td>
  <td class="dataList <?php if ($action == "print") echo " bdr1B"; ?>"><?php if (($bid == "default") && ($dbTable == "default")) { ?><a href="index.php?section=admin&amp;go=entries&amp;bid=<?php echo $row_log['brewBrewerID']; ?>" title="See only the <?php echo $row_log['brewBrewerFirstName']." ".$row_log['brewBrewerLastName']."&rsquo;s"; ?> entries"><?php } echo  $row_log['brewBrewerLastName'].", ".$row_log['brewBrewerFirstName']; ?><?php if (($bid == "default") && ($dbTable == "default")) { ?></a><?php } ?></td>
  <td nowrap="nowrap" class="dataList <?php if ($action == "print") echo " bdr1B"; ?>"><?php if (($action != "print") && ($dbTable == "default")) { ?><input id="brewPaid" name="brewPaid<?php echo $row_log['id']; ?>" type="checkbox" value="Y" <?php if ($row_log['brewPaid'] == "Y") echo "checked"; else ""; ?> /><?php if ($row_brewer['brewerDiscount'] == "Y") echo "&nbsp;<span class='icon'><img src='images/star.png' title='Redeemed Discount Code'></span>"; } else { if ($row_log['brewPaid'] == "Y") echo "X"; } ?></td>
  <td class="dataList <?php if ($action == "print") echo " bdr1B"; ?>"><?php if (($action != "print") && ($dbTable == "default")) { ?><input id="brewReceived" name="brewReceived<?php echo $row_log['id']; ?>" type="checkbox" value="Y" <?php if ($row_log['brewReceived'] == "Y") echo "checked"; else ""; ?> /><?php } else { if ($row_log['brewReceived'] == "Y") echo "X"; } ?></td>
  
<?php if ($row_prefs['prefsCompOrg'] == "N") { ?>
  <td class="dataList <?php if ($action == "print") echo " bdr1B"; ?>"><?php if (($action != "print") && ($dbTable == "default")) { ?><input id="brewWinner" name="brewWinner<?php echo $row_log['id']; ?>" type="checkbox" value="Y" <?php if ($row_log['brewWinner'] == "Y") echo "checked"; ?> /><?php } else { if (($row_log['brewWinner'] == "Y") && ($dbTable != "default")) echo "X"; } ?></td>
  <td class="dataList <?php if ($action == "print") echo " bdr1B"; ?>">
  <?php if (($action != "print") && ($dbTable == "default")) { ?>   
  <input type="text" name="brewWinnerCat<?php echo $row_log['id']; ?>" id="brewWinnerCat" size="3" value="<?php echo $row_log['brewWinnerCat']; ?>" />
  <?php } else echo $row_log['brewWinnerCat']; ?>
  </td>
  <td class="dataList <?php if ($action == "print") echo " bdr1B"; ?>">
  <?php if (($action != "print") && ($dbTable == "default")) { ?>   
  <input type="text" name="brewWinnerSubCat<?php echo $row_log['id']; ?>" id="brewWinnerSubCat" size="3" value="<?php echo $row_log['brewWinnerSubCat']; ?>" />
  <?php } else echo $row_log['brewWinnerSubCat']; ?>
  </td>
  <td class="dataList <?php if ($action == "print") echo " bdr1B"; ?>">
  <?php if (($action != "print") && ($dbTable == "default")) { ?>
  <input type="text" name="brewWinnerPlace<?php echo $row_log['id']; ?>" id="brewWinnerPlace" size="3" value="<?php echo $row_log['brewWinnerPlace']; ?>" />
  <?php } else echo $row_log['brewWinnerPlace']; ?>
  </td>
  <td class="dataList<?php if ($action == "print") echo " bdr1B"; ?>"><?php if (($action != "print") && ($dbTable == "default")) { ?><input id="brewBOSRound" name="brewBOSRound<?php echo $row_log['id']; ?>" type="checkbox" value="Y" <?php if ($row_log['brewBOSRound'] == "Y") echo "checked"; else ""; ?> /><?php } else { if (($row_log['brewBOSRound'] == "Y") && ($dbTable != "default")) echo "X"; } ?></td>
  <td class="dataList<?php if ($action == "print") echo " bdr1B"; ?>">
  	  <?php if (($action != "print") && ($dbTable == "default")) { ?>
      <input type="text" name="brewBOSPlace<?php echo $row_log['id']; ?>" id="brewBOSPlace" size="3" value="<?php echo $row_log['brewBOSPlace']; ?>" />
      <?php } else echo $row_log['brewBOSPlace']; ?>
  </td>
<?php } // end if ($row_prefs['prefsCompOrg'] == "N") ?>
  <?php if (($action != "print") && ($dbTable == "default")) { ?>
  <td class="dataList" nowrap="nowrap">
  <span class="icon"><a href="index.php?section=brew&amp;go=<?php echo $go; ?>&amp;filter=<?php echo $row_log['brewBrewerID']; ?>&amp;action=edit&amp;id=<?php echo $row_log['id']; ?>"><img src="images/pencil.png"  border="0" alt="Edit <?php echo $row_log['brewName']; ?>" title="Edit <?php echo $row_log['brewName']; ?>"></a></span><span class="icon"><a href="javascript:DelWithCon('includes/process.inc.php?section=<?php echo $section; ?>&amp;go=<?php echo $go; ?>&amp;filter=<?php echo $filter; ?>&amp;dbTable=brewing&amp;action=delete','id',<?php echo $row_log['id']; ?>,'Are you sure you want to delete the entry called <?php echo $row_log['brewName']; ?>? This cannot be undone.');"><img src="images/bin_closed.png"  border="0" alt="Delete <?php echo $row_log['brewName']; ?>" title="Delete <?php echo $row_log['brewName']; ?>"></a></span><span class="icon"><a class="thickbox" href="output/entry.php?id=<?php echo $row_log['id']; ?>&amp;bid=<?php echo $row_log['brewBrewerID']; ?>&KeepThis=true&amp;TB_iframe=true&amp;height=450&amp;width=800" title="Print the Entry Forms for <?php echo $row_log['brewName']; ?>"><img src="images/printer.png"  border="0" alt="Print the Entry Forms for <?php echo $row_log['brewName']; ?>" title="Print the Entry Forms for <?php echo $row_log['brewName']; ?>"></a></span>
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
<?php if ($dbTable == "default") { ?>
<p><input type="submit" name="Submit" class="button" value="Update Entries" />&nbsp;<span class="required">Click "Update Entries" <em>before</em> paging through records.</span></p><?php } ?>
<input type="hidden" name="relocate" value="<?php echo relocate($current_page,$pg); ?>">
</form>

<?php } 
} else echo "<div class=\"error\">There are no entires.</div>"; ?>
