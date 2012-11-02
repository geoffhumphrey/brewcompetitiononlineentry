<?php 
/**
 * Module:      judging_locations.admin.php
 * Description: This module houses all participant (brewer) related functionality
 *              involved in assigning participants a role - judge, steward, staff.
 *              Also provids judging location related functions - add, edit, delete.
 *
 */

if ($section != "step5") include(DB.'judging_locations.db.php'); 

// Page specific functions

function brewer_assignment_checked($a,$b) {
	if (($a == "judges") && ($b == "J")) $r = "CHECKED"; 
	elseif (($a == "stewards") && ($b == "S")) $r = "CHECKED";
	elseif (($a == "staff") && ($b == "X")) $r = "CHECKED";
	elseif (($a == "bos") && ($b == "Y")) $r = "CHECKED"; 
	elseif (($a == "staff") && ($b == "O")) $r = "DISABLED";
	elseif (($a == "stewards") && ($b == "O")) $r = "DISABLED";
	elseif (($a == "judges") && ($b == "O")) $r = "DISABLED";
	elseif (($a == "bos") && ($b == "O")) $r = "DISABLED";
	else $r = "";
	return $r;
}
?>
<h2><?php if ($action == "add") echo "Add a Judging Location"; elseif ($action == "edit") echo "Edit a Judging Location"; elseif ($action == "update") { echo "Make Final"; if ($filter == "judges") echo " Judge";  elseif ($filter == "stewards") echo " Steward"; else echo ""; echo " Location Assignments"; } elseif ($action == "assign") { echo "Assign Participants as"; if ($filter == "judges") echo " Judges";  elseif ($filter == "stewards") echo " Stewards"; elseif ($filter == "staff") echo " Staff"; else echo "";  } else echo "Judging Locations &amp; Dates"; ?></h2>
<?php if (($filter == "default") && ($msg == "9")) { ?>
<div class="error">Add another judging location, date, or time?</div>
<p><a href="<?php if ($section == "step5") echo "setup.php?section=step5"; else echo "index.php?section=admin&amp;go=judging"; ?>">Yes</a>&nbsp;&nbsp;&nbsp;<a href="<?php if ($section == "step5") echo "setup.php?section=step6"; else echo "index.php?section=admin"; ?>">No</a>
<?php } else { ?> 
    <?php if ($section == "admin") { ?>
	<?php if (($action == "update") || ($action == "assign")) { ?><p><?php if (($bid == "default") && ($filter != "bos")) echo "Choose ".$filter." to assign.";  elseif ($bid != "default") echo "Check below which ".$filter." will be assigned to the ".$row_judging['judgingLocName']. " location."; elseif ($filter == "bos") echo "Choose the judges that will judge in the Best of Show round(s) and be awarded 0.5 BJCP experience points."; else echo "" ?></p><?php }?>
<div class="adminSubNavContainer">
   	<?php if (($action == "default") || ($action == "update") || ($action == "assign")) { ?>
    <span class="adminSubNav">
    	<span class="icon"><img src="<?php echo $base_url; ?>/images/arrow_left.png" alt="Back"></span><a href="<?php echo $base_url; ?>/index.php?section=admin">Back to Admin Dashboard</a>
   	</span>
    <span class="adminSubNav">
    	<span class="icon"><img src="<?php echo $base_url; ?>/images/arrow_left.png" alt="Back"></span><a href="<?php echo $base_url; ?>/index.php?section=admin&amp;go=participants">Back to Participants</a>
   	</span>
	<?php } ?>
   	<?php if ((($action == "add") || ($action == "edit")) && ($section != "step3")) { ?>
    <span class="adminSubNav">
    	<span class="icon"><img src="<?php echo $base_url; ?>/images/arrow_left.png" alt="Back"></span><a href="<?php echo $base_url; ?>/index.php?section=admin&amp;go=judging">Back to Judging Location List</a>
    </span>
	<?php } elseif (($section != "step3") && ($filter == "default")) { ?>
     <span class="adminSubNav">
    	<span class="icon"><img src="<?php echo $base_url; ?>/images/award_star_add.png"  /></span><a href="<?php echo $base_url; ?>/index.php?section=admin&amp;go=judging&amp;action=add">Add a Judging Location</a>
     </span>
	 <?php } ?>
</div>
<?php if (($action == "update") || ($action == "assign")) { ?>
<div class="adminSubNavContainer">
 	<span class="adminSubNav">
    	<span class="icon"><img src="<?php echo $base_url; ?>/images/user_edit.png"  /></span><a href="<?php echo $base_url; ?>/index.php?section=admin&amp;action=assign&amp;go=judging&amp;filter=judges">Assign Judges</a>
 	</span>
    <span class="adminSubNav">
    	<span class="icon"><img src="<?php echo $base_url; ?>/images/user_edit.png"  /></span><a href="<?php echo $base_url; ?>/index.php?section=admin&amp;action=assign&amp;go=judging&amp;filter=stewards">Assign Stewards</a>
 	</span>
    <span class="adminSubNav">
    	<span class="icon"><img src="<?php echo $base_url; ?>/images/user_edit.png"  /></span><a href="<?php echo $base_url; ?>/index.php?section=admin&amp;action=assign&amp;go=judging&amp;filter=staff">Assign Staff</a>
 	</span>
</div>
	<?php } // end if ($section == "admin") {?>
	<?php } ?>
    <?php if (($action == "default") && ($section != "step5")) {  ?>
    <?php 
	mysql_select_db($database, $brewing);
	$query_judging_locs = "SELECT * FROM $judging_locations_db_table ORDER by judgingDate ASC";
	$judging_locs = mysql_query($query_judging_locs, $brewing) or die(mysql_error());
	$row_judging_locs = mysql_fetch_assoc($judging_locs);
	$totalRows_judging_locs = mysql_num_rows($judging_locs);
	if ($totalRows_judging_locs > 0) { ?>
<script type="text/javascript" language="javascript">
	 $(document).ready(function() {
		$('#sortable').dataTable( {
			"bPaginate" : true,
			"sPaginationType" : "full_numbers",
			"bLengthChange" : true,
			"iDisplayLength" : <?php echo round($row_prefs['prefsRecordPaging']); ?>,
			"sDom": 'irtip',
			"bStateSave" : false,
			"aaSorting": [[1,'asc']],
			"bProcessing" : true,
			"aoColumns": [
				null,
				null,
				null,
				null,
				null,
				{ "asSorting": [  ] }
				]
			} );
		} );
</script>
<table class="dataTable" id="sortable">
<thead>
 <tr>
  <th class="dataHeading bdr1B">Name</th>
  <th class="dataHeading bdr1B">Date</th>
  <th class="dataHeading bdr1B">Start Time</th>
  <th class="dataHeading bdr1B">Address</th>
  <th class="dataHeading bdr1B"># of Rounds</th>
  <th class="dataHeading bdr1B">Actions</th>
 </tr>
</thead>
<tbody>
 <?php do { ?>
 <tr>
  <td width="25%" class="dataList"><?php echo $row_judging_locs['judgingLocName']; ?></td>
  <td width="15%" class="dataList"><?php echo getTimeZoneDateTime($row_prefs['prefsTimeZone'], $row_judging_locs['judgingDate'], $row_prefs['prefsDateFormat'],  $row_prefs['prefsTimeFormat'], "long", "date"); ?></td>
  <td width="15%" class="dataList"><?php echo getTimeZoneDateTime($row_prefs['prefsTimeZone'], $row_judging_locs['judgingDate'], $row_prefs['prefsDateFormat'],  $row_prefs['prefsTimeFormat'], "long", "time-gmt"); ?></td>
  <td width="30%" class="dataList"><?php echo $row_judging_locs['judgingLocation']; ?></td>
  <td width="10%" class="dataList"><?php echo $row_judging_locs['judgingRounds']; ?></td>
  <td class="dataList" nowrap="nowrap">
  <span class="icon"><a href="<?php echo $base_url; ?>/index.php?section=admin&amp;go=<?php echo $go; ?>&amp;action=edit&amp;id=<?php echo $row_judging_locs['id']; ?>"><img src="<?php echo $base_url; ?>/images/pencil.png"  border="0" alt="Edit <?php echo $row_judging_locs['judgingLocName']; ?>" title="Edit <?php echo $row_judging_locs['judgingLocName']; ?>"></a></span><span class="icon"><a href="javascript:DelWithCon('includes/process.inc.php?section=admin&amp;go=<?php echo $go; ?>&amp;dbTable=<?php echo $judging_locations_db_table; ?>&amp;action=delete','id',<?php echo $row_judging_locs['id']; ?>,'Are you sure you want to delete the <?php echo $row_judging_locs['judgingLocName']; ?> location?\nThis cannot be undone and will affect all judges and stewards who indicated this location as a preference.');"><img src="<?php echo $base_url; ?>/images/bin_closed.png"  border="0" alt="Delete <?php echo $row_judging_locs['judgingLocName']; ?>" title="Delete <?php echo $row_judging_locs['judgingLocName']; ?>"></a></span></td>
 </tr>
  <?php } while($row_judging_locs = mysql_fetch_assoc($judging_locs)) ?>
</tbody>
</table>
	<?php } else echo "<p>No judging dates/locations have been specified.</p>"; ?>
    <?php } // end if (($action == "default") && ($section != "step5")) ?>
    <?php if ((($action == "add") || ($action == "edit")) || ($section == "step5")) { ?>
    <script>
	$(function() {
		$('#judgingDate').datepicker({ dateFormat: 'yy-mm-dd', showOtherMonths: true, selectOtherMonths: true, changeMonth: true, changeYear: true });
		$('#judgingTime').timepicker({ showPeriod: true, showLeadingZero: true });
		
	});
	</script>
	<form method="post" action="<?php echo $base_url; ?>/includes/process.inc.php?section=<?php echo $section; ?>&amp;action=<?php if ($section == "step5") echo "add"; else echo $action; ?>&amp;dbTable=<?php echo $judging_locations_db_table; ?>&amp;go=<?php if ($go == "default") echo "setup"; else echo $go; if ($action == "edit") echo "&amp;id=".$id; ?>" name="form1" onSubmit="return CheckRequiredFields()">
<table>
  <tr>
    <td class="dataLabel">Date:</td>
    <td class="data"><input id="judgingDate" name="judgingDate" type="text" size="20" value="<?php if ($action == "edit") echo getTimeZoneDateTime($row_prefs['prefsTimeZone'], $row_judging['judgingDate'], $row_prefs['prefsDateFormat'],  $row_prefs['prefsTimeFormat'], "system", "date"); ?>"></td>
    <td class="data"><span class="required">Required</span></td>
  </tr>
  <tr>
    <td class="dataLabel">Start Time:</td>
    <td class="data"><input id="judgingTime" name="judgingTime" size="10" value="<?php if ($action == "edit") echo getTimeZoneDateTime($row_prefs['prefsTimeZone'], $row_judging['judgingDate'], $row_prefs['prefsDateFormat'],  $row_prefs['prefsTimeFormat'], "system", "time"); ?>"></td>
    <td class="data"><span class="required">Required</span></td>
  </tr>
  <tr>
    <td class="dataLabel">Name:</td>
    <td class="data"><input name="judgingLocName" size="30" value="<?php if ($action == "edit") echo $row_judging['judgingLocName']; ?>"></td>
    <td class="data"><span class="required">Required</span> <em>Provide the name of the judging location.</em></td>
  </tr>
  
  <tr>
    <td class="dataLabel">Address:</td>
    <td class="data"><input name="judgingLocation" size="50" value="<?php if ($action == "edit") echo $row_judging['judgingLocation']; ?>"></td>
    <td class="data"><span class="required">Required</span> <em>Provide the street address, city, and zip code.</em></td>
  </tr>
  <tr>
    	<td class="dataLabel">Judging Rounds:</td>
    	<td class="data"><input name="judgingRounds" size="5" value="<?php if ($action == "edit") echo $row_judging['judgingRounds']; else echo "2"; ?>"></td>
        <td class="data"><span class="required">Required</span> <em>Provide the number of judging rounds anticipated for this location (<strong>not</strong> including Best of Show).</em></td>
  	</tr>
</table>
<input type="submit" class="button" value="<?php if ($action == "edit") echo "Update"; else echo "Submit";?>">
<input type="hidden" name="relocate" value="<?php echo relocate($_SERVER['HTTP_REFERER'],"default",$msg,$id); ?>">
</form>
<?php } // end else ?>


<?php if ((($action == "update") && ($filter != "default") && ($bid != "default")) || ($action == "assign")) { ?>
<?php if ($totalRows_brewer > 0) { ?>
	<script type="text/javascript" language="javascript">
	 $(document).ready(function() {
		$('#sortable').dataTable( {
			"bPaginate" : true,
			"sPaginationType" : "full_numbers",
			"bLengthChange" : true,
			"iDisplayLength" : <?php echo round($row_prefs['prefsRecordPaging']); ?>,
			"sDom": 'irftip',
			"bStateSave" : false,
			<?php if (($filter == "judges") || ($filter == "bos")) { ?>"aaSorting": [[4,'desc']],<?php } else { ?>
			"aaSorting": [[1,'asc']],
			<?php } ?>
			"bProcessing" : true,
			"aoColumns": [
				{ "asSorting": [  ] },
				null,
				null,
				<?php if (($filter == "judges") || ($filter == "bos")) { ?>
				null,
				null,
				<?php } ?>
				<?php if ($bid != "default") { ?>
				null
				<?php } ?>
				]
			} );
		} );
	</script>

<form name="form1" method="post" action="<?php echo $base_url; ?>/includes/process.inc.php?action=update&amp;dbTable=<?php echo $brewer_db_table; ?>&amp;filter=<?php echo $filter; if ($bid != "default") echo "&amp;bid=".$bid; ?>">
<?php if ($filter == "staff") { 
$query_brewers = "SELECT * FROM $brewer_db_table ORDER BY brewerLastName";
$brewers = mysql_query($query_brewers, $brewing) or die(mysql_error());
$row_brewers = mysql_fetch_assoc($brewers);
?>
<h3>Organizer</h3>
<p><strong>Designate the Competition Organizer:</strong> <span class="data"><select name="Organizer">
	<option value="">Choose Below:</option>
    <?php do { ?>
   	<option value="<?php echo $row_brewers['uid']; ?>" <?php if (($row_brewers['brewerAssignment'] == "O")) echo "SELECTED";?>><?php echo $row_brewers['brewerLastName'].", ".$row_brewers['brewerFirstName']; ?></option>
    <?php } while ($row_brewers = mysql_fetch_assoc($brewers)); ?>
   </select>
</span></p>
<p>According to <a href="http://www.bjcp.org/rules.php" target="_blank">BJCP rules</a>, the Organizer is "...the single program participant who completes and signs the application to register or sanction a competition and who in all ways assumes responsibility for the direction of that competition."</p>
<p>If the organizer is not on this list, <a href="<?php echo $base_url; ?>/index.php?section=admin&go=participants&action=add">add them to the database</a>.</p>
<h3>Staff</h3>
<p>According to <a href="http://www.bjcp.org/rules.php" target="_blank">BJCP rules</a>, staff members are "...program participants who, under the direction of the Organizer, perform an active role in support of the competition other than as a Judge, Steward, or BOS Judge."
<p>If a staff member is not on this list, <a href="<?php echo $base_url; ?>/index.php?section=admin&go=participants&action=add">add them to the database</a>.</p>
<?php } ?>
<p><input type="submit" class="button" name="Submit" value="<?php if ($action == "update") echo "Assign to ".$row_judging['judgingLocName']; elseif ($action == "assign") echo "Assign as ".brewer_assignment($filter,"3"); else echo "Submit"; ?>" />&nbsp;<span class="required">Click "<?php if ($action == "update") echo "Assign to ".$row_judging['judgingLocName']; elseif ($action == "assign") echo "Assign as ".brewer_assignment($filter,"3"); else echo "Submit"; ?>" <em>before</em> paging through records.</span></p>

<table class="dataTable" id="sortable">
<thead>
 <tr>
  <th class="dataHeading bdr1B">&nbsp;</th>
  <th class="dataHeading bdr1B">Name</th>
  <th class="dataHeading bdr1B">Assigned As</th>
  <?php if (($filter == "judges") || ($filter == "bos")) { ?>
  <th class="dataHeading bdr1B">ID</th>
  <th class="dataHeading bdr1B">Rank</th>
  <?php } ?>
  <?php if ($bid != "default") { ?>
  <th class="dataHeading bdr1B">Location Preferences</th>
  <?php } ?>
  </thead>
  <tbody>
  <?php 
 	do { 
	if ($filter == "bos") $assignment = $row_brewer['brewerJudgeBOS'];
	else $assignment = $row_brewer['brewerAssignment'];
 ?>
 <tr>
  <input type="hidden" name="id[]" value="<?php echo $row_brewer['id']; ?>" />
  <?php if ($bid == "default") { ?>
  <td width="1%" class="dataList"><input name="brewerAssignment<?php echo $row_brewer['id']; ?>" type="checkbox" value="<?php echo brewer_assignment($filter,"2"); ?>" <?php echo brewer_assignment_checked($filter,$assignment);?>></td>
  <?php } else { ?>
  <td width="1%" class="dataList"><input name="<?php if ($filter == "judges") echo "brewerJudgeAssignedLocation".$row_brewer['id']; if ($filter == "stewards") echo "brewerStewardAssignedLocation".$row_brewer['id']; ?>" type="checkbox" value="<?php echo $bid; ?>" <?php if (($filter == "judges") && strstr($row_brewer['brewerJudgeAssignedLocation'], $bid)) echo "CHECKED"; if (($filter == "stewards") && strstr($row_brewer['brewerStewardAssignedLocation'], $bid)) echo "CHECKED"; ?>></td>
  <?php } ?>
  <td width="10%" class="dataList"><?php echo $row_brewer['brewerLastName'].", ".$row_brewer['brewerFirstName']; ?></td>
  <td width="5%" class="dataList"><?php echo brewer_assignment($row_brewer['brewerAssignment'],"1"); ?></td>
  <?php if (($filter == "judges") || ($filter == "bos")) { ?>
  <td width="5%" class="dataList"><?php echo $row_brewer['brewerJudgeID']; ?></td>
  <td width="5%" class="dataList"><?php echo bjcp_rank($row_brewer['brewerJudgeRank'],1); if ($row_brewer['brewerJudgeMead'] == "Y") echo "<br /><span class='icon'><img src='".$base_url."/images/star.png' alt='' title='Certified Mead Judge'></span>Certified Mead Judge"; ?></td>
  	<?php } if ($bid != "default") { ?>
  <td class="dataList">
  	<table class="dataTableCompact">
		<?php 
		if ($row_brewer['brewerAssignment'] == "J") $a = explode(",",$row_brewer['brewerJudgeLocation']);
		if ($row_brewer['brewerAssignment'] == "S") $a = explode(",",$row_brewer['brewerStewardLocation']);
		arsort($a);
		foreach ($a as $value) {
			if ($value != "") {
				$b = substr($value, 2);
				$query_judging_loc3 = sprintf("SELECT judgingLocName,judgingDate,judgingLocation FROM $judging_locations_db_table WHERE id='%s'", $b);
				$judging_loc3 = mysql_query($query_judging_loc3, $brewing) or die(mysql_error());
				$row_judging_loc3 = mysql_fetch_assoc($judging_loc3);
				if (substr($value, 0, 1) == "Y") { 
					echo "<tr>\n<td>".substr($value, 0, 1).":</td>\n<td>".$row_judging_loc3['judgingLocName']." ("; 
					echo getTimeZoneDateTime($row_prefs['prefsTimeZone'], $row_judging_loc3['judgingDate'], $row_prefs['prefsDateFormat'],  $row_prefs['prefsTimeFormat'], "long", "date-time").")</td>\n";
					echo "</td>\n</tr>";
				}
				}
			}
		?>
    	</table>
  </td>
  </tr>
  <?php } ?>
  <?php } while ($row_brewer = mysql_fetch_assoc($brewer)); ?>
</tbody>
</table>
<p><input type="submit" class="button" name="Submit" value="<?php if ($action == "update") echo "Assign to ".$row_judging['judgingLocName']; elseif ($action == "assign") echo "Assign as ".brewer_assignment($filter,"3"); else echo "Submit"; ?>" />&nbsp;<span class="required">Click "<?php if ($action == "update") echo "Assign to ".$row_judging['judgingLocName']; elseif ($action == "assign") echo "Assign as ".brewer_assignment($filter,"3"); else echo "Submit"; ?>" <em>before</em> paging through records.</span></p>
<input type="hidden" name="relocate" value="<?php echo relocate($current_page,"default",$msg,$id); ?>">
</form>
<?php } else { if ($action == "update") echo "<div class='error'>No $filter have been assigned.</div>"; else echo "<div class='error'>No participants have indicated that they would like to be a ".rtrim($filter, "s").".</div>"; } ?>
<?php } // end if ((($action == "update") && ($filter != "default") && ($bid != "default")) || ($action == "assign")) ?>






<?php if (($action == "update") && ($bid == "default")) {  ?>
<table>
 <tr>
   <td class="dataLabel">Assign <?php echo brewer_assignment($filter,"3"); ?> To:</td>
   <td class="data">
   <select name="judge_loc" id="judge_loc" onchange="jumpMenu('self',this,0)">
	<option value=""></option>
    <?php do { ?>
	<option value="index.php?section=admin&amp;action=update&amp;go=judging&amp;filter=<?php echo $filter; ?>&amp;bid=<?php echo $row_judging['id']; ?>"><?php  echo $row_judging['judgingLocName']." ("; echo getTimeZoneDateTime($row_prefs['prefsTimeZone'], $row_judging['judgingDate'], $row_prefs['prefsDateFormat'],  $row_prefs['prefsTimeFormat'], "long", "date-time").")"; ?></option>
    <?php } while ($row_judging = mysql_fetch_assoc($judging)); ?>
   </select>
  </td>
</tr>
</table>
<?php } // end if (($action == "update") && ($bid == "default")) ?>
<?php } ?>