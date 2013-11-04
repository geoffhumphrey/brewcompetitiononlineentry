<?php 
$queued = $_SESSION['jPrefsQueued'];
$location = $row_tables_edit['tableLocation'];

include(DB.'judging_assign.db.php');

?>
<div class="adminSubNavContainer">
		<span class="adminSubNav">
        	<span class="icon"><img src="<?php echo $base_url; ?>images/monitor.png"  /></span><a id="modal_window_link" href="<?php echo $base_url; ?>output/assignments.php?section=admin&amp;go=judging_assignments&amp;filter=<?php echo $filter; ?>&amp;view=name&amp;tb=view" title="View Assignments by Name">View All <?php if ($filter == "stewards") echo "Steward"; else echo "Judge"; ?> Assignments By Last Name</a>
        </span>
        <span class="adminSubNav">
        	<span class="icon"><img src="<?php echo $base_url; ?>images/monitor.png"  /></span><a id="modal_window_link" href="<?php echo $base_url; ?>output/assignments.php?section=admin&amp;go=judging_assignments&amp;filter=<?php echo $filter; ?>&amp;view=table&amp;tb=view" title="View Assignments by Table">View All <?php if ($filter == "stewards") echo "Steward"; else echo "Judge"; ?> Assignments By Table</a>
        </span>
        <span class="adminSubNav">
        	<span class="icon data"><img src="<?php echo $base_url; ?>images/monitor.png"  /></span><a id="modal_window_link" href="<?php echo $base_url; ?>output/assignments.php?section=admin&amp;go=judging_assignments&amp;filter=<?php echo $filter; ?>&amp;view=name&amp;tb=view&amp;id=<?php echo $row_tables_edit['id']; ?>" title="View Assignments for this Table">View <?php if ($filter == "stewards") echo "Steward"; else echo "Judge"; ?> Assignments for this Table</a>
        </span>
</div>
<div class="info">Make sure you have <a href="<?php echo $base_url; ?>index.php?section=admin&go=judging_flights&action=assign&filter=rounds">assigned all tables <?php if ($_SESSION['jPrefsQueued'] == "N") echo "and flights"; ?> to rounds</a> <em>before</em> assigning <?php echo $filter; ?> to a table.
<?php if ($totalRows_judging > 1) { ?>
<br />
If no judges are listed below, no judge indicated that they are available for this table's location. To make judges available, you will need to edit their preferences via the <a href="<?php echo $base_url; ?>index.php?section=admin&amp;go=participants">participants list</a>.<?php } ?>
</div>
<h3>Assign <?php if ($filter == "stewards") echo "Stewards"; else echo "Judges"; ?> to Another Table</h3>
<table>
 <tr>
   <td class="dataLabel">Assign <?php if ($filter == "stewards") echo "Stewards"; else echo "Judges"; ?> To:</td>
   <td class="data">
   <select name="assign_table" id="assign_table" onchange="jumpMenu('self',this,0)">
	<option value="">Choose Below:</option>
    <?php do { ?>
	<option value="index.php?section=admin&amp;action=assign&amp;go=judging_tables&amp;filter=<?php echo $filter; ?>&amp;id=<?php echo $row_tables['id']; ?>"><?php echo "Table #".$row_tables['tableNumber'].": ".$row_tables['tableName']; ?></option>
    <?php } while ($row_tables = mysql_fetch_assoc($tables)); ?>
   </select>
  </td>
</tr>
</table>
<h3>Assign <?php if ($filter == "stewards") echo "Stewards"; else echo "Judges"; ?> to Table #<?php echo $row_tables_edit['tableNumber'].": ".$row_tables_edit['tableName']; $entry_count = get_table_info(1,"count_total",$row_tables_edit['id'],$dbTable,"default"); echo " (".$entry_count." entries)"; ?></h3>
<table class="dataTableCompact">
	<tr>
    	<td class="dataLabel">Location:</td>
        <td class="data"><?php echo table_location($row_tables_edit['id'],$_SESSION['prefsDateFormat'],$_SESSION['prefsTimeZone'],$_SESSION['prefsTimeFormat'],"default"); ?></td>
     </tr>
     <!-- 
     <tr>
        <td class="dataLabel">Available Judges<br />At This Location:</td>
        <td class="data"><?php // echo available_at_location($row_tables_edit['tableLocation'],$filter,"default"); ?></td>
    </tr>
    -->
<?php if ($row_rounds['flightRound'] != "") { ?>
<?php if ($_SESSION['jPrefsQueued'] == "N") { ?>
	<tr>
    	<td class="dataLabel">Number of Flights:</td>
		<td class="data"><?php echo $row_flights['flightNumber']; ?></td>
    </tr>
    <tr>
    	<td colspan="2">
            <ul>
            <?php 
                for($c=1; $c<$row_flights['flightNumber']+1; $c++) {
				
				$flight_entry_count = flight_entry_count($row_tables_edit['id'], $c);
                
                echo "<li>Flight $c: ".$flight_entry_count." entries.</li>";
                }
            ?>
            </ul>
<?php } ?>
</table>
<script type="text/javascript" language="javascript">
	 $(document).ready(function() {
		$('#sortable').dataTable( {
			"bPaginate" : true,
			"sPaginationType" : "full_numbers",
			"bLengthChange" : true,
			"iDisplayLength" : <?php echo round($_SESSION['prefsRecordPaging']); ?>,
			"sDom": 'irtip',
			"bStateSave" : false,
			<?php if ($filter == "judges") { ?>
			"aaSorting": [[1,'desc']],
			<?php } ?>
			<?php if ($filter == "stewards") { ?>
			"aaSorting": [[0,'asc']],
			<?php } ?>
			"bProcessing" : false,
			<?php if ($filter == "judges") { ?>
			"aoColumns": [
				null,
				null,
				null<?php for($i=1; $i<$row_flights['flightRound']+1; $i++) {  
			    if  (table_round($row_tables_edit['id'],$i)) { 
				?>, null<?php } } ?>
				]
			} );
			<?php } ?>
			<?php if ($filter == "stewards") { ?>
			"aoColumns": [
				null<?php for($i=1; $i<$row_flights['flightRound']+1; $i++) {  
			    if  (table_round($row_tables_edit['id'],$i)) { 
				?>, null<?php } } ?>
				]
			} );
	 		<?php } ?>
		} );
</script>
<script type="text/javascript" language="javascript">
	 $(document).ready(function() {
		$('#sortable1').dataTable( {
			"bPaginate" : false,
			"sDom": 'rt',
			"bStateSave" : false,
			"bLengthChange" : false,
			"aaSorting": [[1,'asc'],[0,'asc']],
			"bProcessing" : false,
			"aoColumns": [
				null,
				null,
				null
				<?php if ($_SESSION['jPrefsQueued'] == "N") { ?>,
				null,
				null
				<?php } ?>
				]
			} );
		} );
	</script>
<form name="form1" method="post" action="<?php echo $base_url; ?>includes/process.inc.php?action=update&amp;dbTable=<?php echo $judging_assignments_db_table; ?>&amp;filter=<?php echo $filter; ?>&amp;limit=<?php echo $row_rounds['flightRound']; ?>&amp;view=<?php echo $_SESSION['jPrefsQueued']; ?>&amp;id=<?php echo $row_tables_edit['id']; ?>">
<table class="dataTable">
    <tr>
        <td width="45%">
            <table class="dataTable bdr1">
            <thead>
                <tr>
                    <th class="dataHeading bdr1B" colspan="2">Legend</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td class="red" style="color: #FFF;">Red</td>
                    <td class="data red" style="color: #FFF;">One or more styles are on the participant's "dislikes" list.</td> 
                </tr>
                <tr>
                    <td class="green" style="color: #FFF;">Green</td>
                    <td class="data green" style="color: #FFF;">One or more styles are on the participant's "likes" list.</td>
                </tr>
                <tr>
                  <td class="blue" style="color: #FFF;">Blue</td>
                  <td class="data blue" style="color: #FFF;">Disabled; participant has an entry at this table.</td>
                </tr>
                <tr>
                  <td class="purple" style="color: #FFF;">Purple</td>
                  <td class="data purple" style="color: #FFF;">Particpant is already assigned to this table/flight.</td>
                </tr>
                <tr>
                    <td class="orange">Orange</td>
                    <td class="data orange">Participant is already assigned to this round at another table/flight.</td> 
                </tr>
            </tbody>
            </table>
        </td>
        <td>
        <?php if ($totalRows_assignments > 0) { ?>
        	<table class="dataTable bdr1">
            <tr>
            	<th class="dataHeading bdr1B">Judges/Stewards Assigned to this Table</th>
            </tr>
            <tr>
            <td style="padding:0;">
            <table class="dataTable" id="sortable1">
                <thead>
                    <tr style="display:none">
                        <td class="dataHeading bdr1B">Name</td>
                        <td class="dataHeading bdr1B">Assignment</td>
                        <td class="dataHeading bdr1B">Rank</td>
                        <?php if ($_SESSION['jPrefsQueued'] == "N") { ?>
                        <td class="dataHeading bdr1B">Flight</td>
                        <td class="dataHeading bdr1B">Round</td>
                        <?php } ?>
                    </tr>
                </thead>
                <tbody>
                	<?php do { 
					$judge_info = explode("^",brewer_info($row_assignments['bid']));
					?>
                    <tr>
                        <td width="25%" nowrap="nowrap"><?php echo ucwords(strtolower($judge_info['1'])).", ".ucwords(strtolower($judge_info['0'])); ?></td>
                        <td class="data" width="10%"><?php if ($row_assignments['assignment'] == "S") echo "Steward"; else echo "Judge"; ?></td>
                        <td class="data"><?php echo str_replace(",",", ",$judge_info['3']); ?></td>
                        <?php if ($_SESSION['jPrefsQueued'] == "N") { ?>
                        <td class="data" width="15%"><?php echo "Flight ".$row_assignments['assignFlight']; ?></td>
                        <td class="data" width="15%"><?php echo "Round ".$row_assignments['assignRound']; ?></td>
                        <?php } ?>
                    </tr>
                    <?php } while ($row_assignments = mysql_fetch_assoc($assignments)); ?>
                </tbody>
            </table>
            </td>
            </tr>
            </table>
        </td>
        <?php } ?>
    </tr>
</table>

<p><input type="submit" class="button" name="Submit" value="Assign to Table #<?php echo $row_tables_edit['tableNumber']; ?>" /></p>
<table class="dataTable" id="sortable">
<thead>
 	<tr>
  		<th class="dataHeading bdr1B" width="20%">Name</th>
        <?php if ($filter == "judges") { ?>
        <th class="dataHeading bdr1B" width="8%">BJCP Rank</th>
        <th class="dataHeading bdr1B" width="8%">BJCP #</th>
        <?php } 
   			for($i=1; $i<$row_flights['flightRound']+1; $i++) {  
			    if  (table_round($row_tables_edit['id'],$i)) { 
			?>
  		<th class="dataHeading bdr1B">Round <?php echo $i; ?></th>
        	<?php
			   } 
			} 
			?>
	</tr>
</thead>
<tbody>
<?php do { 
$table_location = "Y-".$row_tables_edit['tableLocation'];

$judge_info = judge_info($row_brewer['uid']);
$judge_info = explode("^",$judge_info);

$r = 
$row_brewer_info['brewerFirstName']."^".
$row_brewer_info['brewerLastName']."^".
$row_brewer_info['brewerJudgeLikes']."^".
$row_brewer_info['brewerJudgeDislikes']."^".
$row_brewer_info['brewerJudgeMead']."^".
$row_brewer_info['brewerJudgeRank']."^".
$row_brewer_info['brewerJudgeID']."^".
$row_brewer_info['brewerStewardLocation']."^".
$row_brewer_info['brewerJudgeLocation'];

$bjcp_rank = explode(",",$judge_info[5]);
$display_rank = bjcp_rank($bjcp_rank[0],1);
if ($judge_info[4] == "Y") $display_rank .= "<br /><em>Certified Mead Judge</em>"; 
if (!empty($bjcp_rank[1])) {
	$display_rank .= "<em>".designations($judge_info[5],$bjcp_rank[0])."</em>";
}

if ($filter == "stewards") $locations = explode(",",$judge_info[7]); else $locations = explode(",",$judge_info[8]);
if (in_array($table_location,$locations)) {
?>
	<tr> 
    	<td nowrap="nowrap">
		<?php 
		echo $judge_info[1].", ".$judge_info[0]; 
		/*
		echo "<br>Likes: ".$judge_info[2];
		echo "<br>Dislikes: ".$judge_info[3];
		echo "<br>Mead: ".$judge_info[4];
		echo "<br>Rank: ".$judge_info[5];
		echo "<br>ID: ".$judge_info[6];
		echo "<br>Steward Location: ".$judge_info[7];
		echo "<br>Judge Location: ".$judge_info[8];
		*/
		?>
        </td>
        <?php if ($filter == "judges") { ?>
        <td nowrap="nowrap"><?php echo $display_rank; ?></td>
        <td nowrap="nowrap"><?php if (($judge_info[6] != "") && ($judge_info[6] != "0")) echo strtoupper($judge_info[6]); else echo "N/A"; ?></td>
        <?php } ?>
		<?php for($i=1; $i<$row_flights['flightRound']+1; $i++) {  
			    if  (table_round($row_tables_edit['id'],$i)) {  
		?>
        <td class="data">
        <?php 
		
		if (at_table($row_brewer['uid'],$row_tables_edit['id'])) echo '<div class="purple judge-alert">Already Assigned to this Table</div>'; 
		else echo judge_alert($i,$row_brewer['uid'],$row_tables_edit['id'],$location,$judge_info[2],$judge_info[3],$row_tables_edit['tableStyles'],$row_tables_edit['id']);
		?>
        <?php echo assign_to_table($row_tables_edit['id'],$row_brewer['uid'],$filter,$total_flights,$i,$location,$row_tables_edit['tableStyles'],$queued); ?> 
        </td>
		<?php }
		} // end for loop ?>
    </tr> 
<?php }
} while ($row_brewer = mysql_fetch_assoc($brewer)); 
?>	
</tbody>
</table>
<p><input type="submit" class="button" name="Submit" value="Assign to Table #<?php echo $row_tables_edit['tableNumber']; ?>" /></p>
<input type="hidden" name="relocate" value="<?php echo relocate($_SERVER['HTTP_REFERER'],"default",$msg,$row_tables_edit['id']); if ($msg != "default") echo "&id=".$row_tables_edit['id']; ?>">
</form>
<?php
//mysql_free_result($styles);
mysql_free_result($tables);
mysql_free_result($tables_edit);
} // end if ($row_rounds['flightRound'] != "")
else { 
	if ($_SESSION['jPrefsQueued'] == "N") "<p>Flights from this table have not been assigned to rounds yet. <a href='index.php?section=admin&amp;go=judging_flights&amp;action=assign&amp;filter=rounds'>Assign flights to rounds?</a></p>"; 
	else echo "<p>This table has not been assigned to a round yet. <a href='index.php?section=admin&amp;go=judging_flights&amp;action=assign&amp;filter=rounds'>Assign to a round?</a></p>";
	}
?>