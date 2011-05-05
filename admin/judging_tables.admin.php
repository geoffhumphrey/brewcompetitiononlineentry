<?php 
include(DB.'brewer.db.php');
include(DB.'judging_locations.db.php'); 
include(DB.'styles.db.php');
?>
<h2><?php 
if ($action == "edit") echo "Edit Table"; 
elseif ($action == "add") echo "Add a Table"; 
else echo "Tables"; 
if ($dbTable != "default") echo ": ".ltrim($dbTable, "judging_tables_"); ?></h2>
<div class="adminSubNavContainer">
		<span class="adminSubNav">
        	<span class="icon"><img src="images/arrow_left.png" alt="Back"></span><?php if ($action == "default") { ?><a href="index.php?section=admin">Back to Admin</a><?php } else { ?><a href="index.php?section=admin&amp;go=judging_tables">Back to Tables List</a><?php } ?>
     	</span> 
<?php if  ($dbTable != "default") { ?>
		<span class="adminSubNav">
        	<span class="icon"><img src="images/arrow_left.png" alt="Back"></span><a href="index.php?section=admin&amp;go=archive">Back to Archives</a>
     	</span> 
<?php } ?>   
<?php if ($filter == "orphans") { ?>
	<span class="adminSubNav">
    	<span class="icon"><img src="images/application.png" alt="Back"></span><a href="index.php?section=admin&amp;go=judging_tables">View Tables List</a>
    </span>
<?php } ?>
<?php if ($action == "default") { ?>
<span class="adminSubNav">
  		<span class="icon"><img src="images/printer.png" alt="Print" title="Print..." /></span>
  		<div class="menuBar"><a class="menuButton" href="#" onclick="#" onmouseover="buttonMouseover(event, 'printMenu_tables');">Print...</a></div>
  		<div id="printMenu_tables" class="menu" onmouseover="menuMouseover(event)">
  			<a class="menuItem thickbox" href="output/print.php?section=admin&amp;go=judging_tables&amp;action=print&amp;KeepThis=true&amp;TB_iframe=true&amp;height=600&amp;width=800">Tables List</a>
    		<a class="menuItem thickbox" href="output/pullsheets.php?section=admin&amp;go=judging_tables&amp;id=default&amp;KeepThis=true&amp;TB_iframe=true&amp;height=600&amp;width=800">Pullsheets by Table</a>
		</div>
	</span>
<?php } ?>
</div>

<?php if ((($action == "default") || ($action == "edit"))  && ($dbTable == "default")) { ?>
<div class="adminSubNavContainer">
	<span class="adminSubNav">Step 1</span>
	<span class="adminSubNav">
    	<span class="icon"><img src="images/user_add.png" alt="Back"></span><a href="index.php?section=admin&amp;action=assign&amp;go=judging&amp;filter=judges">Assign Particpants as Judges</a>
    </span>
    <span class="adminSubNav">
        <span class="icon"><img src="images/user_add.png" alt="Back"></span><a href="index.php?section=admin&amp;action=assign&amp;go=judging&amp;filter=stewards">Assign Particpants as Stewards</a>
    </span>
</div>
<div class="adminSubNavContainer">  
	<span class="adminSubNav">Step 2</span>
	<span class="adminSubNav">
    	<span class="icon"><img src="images/application_add.png" alt="Back"></span><a href="index.php?section=admin&amp;go=judging_tables&amp;action=add">Add a Table</a>
    </span>
    <?php if ($filter != "orphans") { ?>
	<span class="adminSubNav">
  		<span class="icon"><img src="images/application_view_list.png" alt="Orphans" title="Styles Not Assigned to Tables" /></span><a href="index.php?section=admin&amp;go=judging_tables&amp;filter=orphans">View Styles Not Assigned to Tables</a>
    </span>
	<?php } ?>
</div>
<?php } ?>
<?php if (($action == "default")  && ($dbTable == "default")) { ?>
	

<?php if (($totalRows_tables > 0) && ($dbTable == "default")) { ?>
<div class="adminSubNavContainer">
	<span class="adminSubNav">Step 3</span>
	<?php if ($row_judging_prefs['jPrefsQueued'] == "N") { ?>
	<span class="adminSubNav">
    	<span class="icon"><img src="images/application_form_add.png" alt="Define/Edit flights" title="Define/Edit flights" /></span>
    	<div class="menuBar"><a class="menuButton" href="#" onclick="#" onmouseover="buttonMouseover(event, 'flightsMenu_tables');">Define/Edit Flights for...</a></div>
    	<div id="flightsMenu_tables" class="menu" onmouseover="menuMouseover(event)">
    		<?php do { 
			$query_flights_2 = sprintf("SELECT COUNT(*) as 'count' FROM judging_flights WHERE flightTable='%s'", $row_tables_edit['id']);
			$flights_2 = mysql_query($query_flights_2, $brewing) or die(mysql_error());
			$row_flights_2 = mysql_fetch_assoc($flights_2);
			$totalRows_flights_2 = $row_flights_2['count'];
			$entry_count = get_table_info(1,"count_total",$row_tables_edit['id'],$dbTable);
			if ($entry_count > $row_judging_prefs['jPrefsFlightEntries']) { 
			?>
    		<a class="menuItem" href="index.php?section=admin&amp;go=judging_flights&amp;action=<?php if ($totalRows_flights_2 > 0) echo "edit"; else echo "add"; echo "&amp;id=".$row_tables_edit['id']; ?>"><?php echo "Table #".$row_tables_edit['tableNumber'].": ".$row_tables_edit['tableName']; ?></a>
    		<?php } mysql_free_result($flights_2); } while ($row_tables_edit = mysql_fetch_assoc($tables_edit)); ?>
    	</div>
	</span>
    <?php } ?>
    <span class="adminSubNav">
    	<span class="icon"><img src="images/application_form_add.png" alt="<?php echo "Assign ".$assign_to." to Rounds"; ?>" /></span><a href="index.php?section=admin&amp;go=judging_flights&amp;action=assign&amp;&filter=rounds"><?php echo "Assign ".$assign_to." to Rounds"; ?></a>
    </span>
</div>
<div class="adminSubNavContainer">
	<span class="adminSubNav">Step 4</span>
<?php if ((($action == "assign")  && ($id != "default")) || (($action == "default") && ($dbTable == "default"))) { ?>
		<span class="adminSubNav">
        	<span class="icon"><img src="images/user_add.png" alt="Assign Judges/Stewards to Tables"></span><a href="index.php?section=admin&amp;action=assign&amp;go=judging_tables">Assign Judges/Stewards to Tables</a>
     	</span> 
	<?php } ?>
</div>
<?php } // end if ($totalRows_tables > 0) ?>

	<?php if (($totalRows_tables > 0) && ($dbTable == "default")) { ?>
<div class="adminSubNavContainer">
	<span class="adminSubNav">Step 5</span>
	<span class="adminSubNav">
    	<span class="icon"><img src="images/rosette.png" alt="View Scores"></span>
    	<div class="menuBar"><a class="menuButton" href="#" onclick="#" onmouseover="buttonMouseover(event, 'scoreMenu');">View Scores...</a></div>
  		<div id="scoreMenu" class="menu" onmouseover="menuMouseover(event)">
  			<a class="menuItem" href="index.php?section=admin&go=judging_scores">By Table</a>
    		<a class="menuItem" href="index.php?section=admin&go=judging_scores&amp;filter=category">By Category</a>
  		</div>
	</span>
	<span class="adminSubNav">
    	<span class="icon"><img src="images/rosette_add.png" alt="Enter/Edit scores" title="Enter/Edit scores" /></span>
   	 	<div class="menuBar"><a class="menuButton" href="#" onclick="#" onmouseover="buttonMouseover(event, 'scoresMenu_tables');">Enter/Edit Scores for...</a></div>
    	<div id="scoresMenu_tables" class="menu" onmouseover="menuMouseover(event)">
    		<?php do { 
			$query_scores_2 = sprintf("SELECT COUNT(*) as 'count' FROM judging_scores WHERE scoreTable='%s'", $row_tables_edit_2['id']);
			$scores_2 = mysql_query($query_scores_2, $brewing) or die(mysql_error());
			$row_scores_2 = mysql_fetch_assoc($scores_2);
			$totalRows_scores_2 = $row_scores_2['count'];
			?>
    		<a class="menuItem" href="index.php?section=admin&amp;go=judging_scores&amp;action=<?php if ($totalRows_scores_2  > 0) echo "edit&amp;id=".$row_tables_edit_2['id']; else echo "add&amp;id=".$row_tables_edit_2['id']; ?>"><?php echo "Table #".$row_tables_edit_2['tableNumber'].": ".$row_tables_edit_2['tableName']; ?></a>
    		<?php mysql_free_result($scores_2);	} while ($row_tables_edit_2 = mysql_fetch_assoc($tables_edit_2)); ?>
		</div>
	</span>
</div>
<div class="adminSubNavContainer">
	<span class="adminSubNav">Step 6</span>
    <span class="adminSubNav">
		<span class="icon"><img src="images/award_star_gold_2.png" alt="View BOS Entries and Places" title="View BOS Entries and Places" /></span><a href="index.php?section=admin&amp;go=judging_scores_bos">View BOS Entries and Places</a>
    </span>
</div>
  	<?php } // end if (($totalRows_tables > 0) && ($dbTable == "default")) ?>
<?php } // end if ($action == "default")?>
<?php if ($action != "print") { ?>
<?php if (($action == "default") && ($filter == "default") && ($dbTable == "default")) { ?>
<p>To ensure accuracy, verify that all paid and received entries have been marked as so via the <a href="index.php?section=admin&amp;go=entries">Manage Entries</a> screen.</p>
<table class="dataTable" style="margin-bottom: 2em;">
<tbody>
	<tr>
    	<td width="30%" nowrap="nowrap"><a href="index.php?section=admin&amp;go=judging_preferences">Competition organization preferences</a> are set to:<br />
        	<ul>
				<li><?php if ($row_judging_prefs['jPrefsQueued'] == "Y") echo "Queued Judging (No Flights)."; else echo "Non-Queued Judging (With Flights)."; ?></li>
				<li>Maximum Rounds <?php if ($totalRows_judging > 0) echo "(per Location)"; ?>: <?php echo $row_judging_prefs['jPrefsRounds']; ?>.</li>
                <li>Maximum BOS Places (per Style Type): <?php echo $row_judging_prefs['jPrefsMaxBOS']; ?>.</li> 
    			<?php if ($row_judging_prefs['jPrefsQueued'] == "N") { ?>
    			<li>Maximum Entries per Flight: <?php echo $row_judging_prefs['jPrefsFlightEntries']; ?>.</li> 
    			<?php } ?>
       		</ul>
        </td>
        <td>A Best of Show round is enabled for the following <a href="index.php?section=admin&amp;go=style_types">Style Types</a>:<br />
        	<ul>
            	<?php do { ?>
            	<li><?php echo $row_style_type['styleTypeName']." (".bos_method($row_style_type['styleTypeBOSMethod'])." from each Table to BOS)."; ?></li>
                <?php } while ($row_style_type = mysql_fetch_assoc($style_type)); ?>
        	</ul>
        </td>
    </tr>
</tbody>
</table>
<?php } ?>
<?php } ?>
<?php 
if ((($action == "default") && ($filter == "default")) || ($action == "print")) { 
if ($totalRows_tables > 0) { ?>
<script type="text/javascript" language="javascript">
	 $(document).ready(function() {
		$('#sortable').dataTable( {
			"bPaginate" : false,
			"sDom": 'rt',
			"bStateSave" : false,
			"bLengthChange" : false,
			"aaSorting": [[0,'asc']],
			"bProcessing" : false,
			"aoColumns": [
				null,
				null,
				null,
				null<?php if (($totalRows_judging > 1) && ($dbTable == "default"))  { ?>,
				null
				<?php } ?>
				<?php if (($action != "print") && ($dbTable == "default"))  { ?>,
				{ "asSorting": [  ] }
				<?php } ?>
				]
			} );
		} );
	</script>
<table summary="Define a judging table and its associated name, number, style(s), and location." class="dataTable" id="sortable">
	<thead>
    <tr>
    	<th class="dataHeading bdr1B">#</th>
        <th class="dataHeading bdr1B">Name</th>
        <th class="dataHeading bdr1B">Style(s)</th>
        <th class="dataHeading bdr1B">Entries</th>
        <?php if (($totalRows_judging > 1) && ($dbTable == "default"))  { ?>
        <th class="dataHeading bdr1B">Location</th>
        <?php } ?>
        <?php if (($action != "print") && ($dbTable == "default")) { ?>
        <th class="dataHeading bdr1B">Actions</th>
        <?php } ?>
    </tr>
    </thead>
    <tbody>
    <?php do { ?>
    <tr>
    	<td <?php if ($action == "print") echo "class='bdr1B'"; ?>width="5%"><?php echo $row_tables['tableNumber']; ?></td>
        <td class="data<?php if ($action == "print") echo " bdr1B"; ?>" width="20%"><?php echo $row_tables['tableName']; ?></td>
        <td class="data<?php if ($action == "print") echo " bdr1B"; ?>" width="20%"><?php $a = array(get_table_info(1,"list",$row_tables['id'],$dbTable)); echo display_array_content($a,1); ?></td>
        <td class="data<?php if ($action == "print") echo " bdr1B"; ?>" width="10%"><?php $entry_count = get_table_info(1,"count_total",$row_tables['id'],$dbTable); echo $entry_count; ?></td>
        <?php if (($totalRows_judging > 1) && ($dbTable == "default")) { ?>
        <td class="data<?php if ($action == "print") echo " bdr1B"; ?>"><?php echo table_location($row_tables['id']); ?></td>
        <?php } ?>
        <?php if (($action != "print") && ($dbTable == "default")) { ?>
        <td class="data<?php if ($action == "print") echo " bdr1B"; ?>" width="5%" nowrap="nowrap"><span class="icon"><a href="index.php?section=admin&amp;go=<?php echo $go; ?>&amp;action=edit&amp;id=<?php echo $row_tables['id']; ?>"><img src="images/pencil.png"  border="0" alt="Edit the <?php echo $row_tables['tableName']; ?> table" title="Edit the <?php echo $row_tables['tableName']; ?> table"></a></span><span class="icon"><a href="javascript:DelWithCon('includes/process.inc.php?section=<?php echo $section; ?>&amp;go=<?php echo $go; ?>&amp;filter=<?php echo $filter; ?>&amp;dbTable=judging_tables&amp;go=judging_tables&amp;action=delete','id',<?php echo $row_tables['id']; ?>,'Are you sure you want to delete the <?php echo $row_tables['tableName']; ?> table?\nALL associated FLIGHTS and SCORES will be deleted as well.\nThis cannot be undone.');"><img src="images/bin_closed.png"  border="0" alt="Delete the <?php echo $row_tables['tableName']; ?> table" title="Delete the <?php echo $row_tables['tableName']; ?> table"></a></span><span class="icon"><a class="thickbox" href="output/pullsheets.php?section=admin&amp;go=judging_tables&amp;id=<?php echo $row_tables['id']; ?>&amp;KeepThis=true&amp;TB_iframe=true&amp;height=600&amp;width=800"><img src="images/printer.png"  border="0" alt="Print the pullsheet for <?php echo $row_tables['tableName']; ?>" title="Print the pullsheet for <?php echo $row_tables['tableName']; ?>"></a></span><?php if ($row_judging_prefs['jPrefsQueued'] == "N") { if (flight_count($row_tables['id'],1)) { ?><span class="icon"><a href="index.php?section=admin&amp;go=judging_flights&amp;action=edit&amp;id=<?php echo $row_tables['id']; ?>"><img src="images/application_form_edit.png" alt="Edit flights for <?php echo $row_tables['tableName']; ?>" title="Edit flights for <?php echo $row_tables['tableName']; ?>" /></a></span><?php } elseif (($totalRows_flights == 0) && ($entry_count > $row_judging_prefs['jPrefsFlightEntries'])) { ?><span class="icon"><a href="index.php?section=admin&amp;go=judging_flights&amp;action=add&amp;id=<?php echo $row_tables['id']; ?>"><img src="images/application_form_add.png" alt="Define flights for <?php echo $row_tables['tableName']; ?>" title="Define flights for <?php echo $row_tables['tableName']; ?>" /></a></span><?php } else { ?><span class="icon"><img src="images/application_form_fade.png" alt="No need to define flights for <?php echo $row_tables['tableName']; ?>" title="No need to define flights for <?php echo $row_tables['tableName']; ?>" /></span><?php } ?><?php } // end if ($row_judging_prefs['jPrefsQueued'] == "N") ?><?php if (score_count($row_tables['id'],1)) { ?><span class="icon"><a href="index.php?section=admin&amp;go=judging_scores&amp;action=edit&amp;id=<?php echo $row_tables['id']; ?>"><img src="images/rosette_edit.png" alt="Edit scores for <?php echo $row_tables['tableName']; ?>" title="Edit scores for <?php echo $row_tables['tableName']; ?>" /></a></span><?php } else { ?><span class="icon"><a href="index.php?section=admin&amp;go=judging_scores&amp;action=add&amp;id=<?php echo $row_tables['id']; ?>"><img src="images/rosette_add.png" alt="Enter scores for <?php echo $row_tables['tableName']; ?>" title="Enter scores for <?php echo $row_tables['tableName']; ?>" /></a></span><?php } ?>
        </td>
        <?php } ?>
    </tr>
    <?php } while ($row_tables = mysql_fetch_assoc($tables)); ?>
    </tbody>
</table>
<?php } 
else echo "<p>No tables have been defined yet. <a href='index.php?section=admin&amp;go=judging_tables&amp;action=add'>Add a table?</a></p>";
} // end if ($action == "default") ?>

<?php if (($action == "add") || ($action == "edit")) { ?>
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
				{ "asSorting": [  ] },
				null,
				null,
				null,
				null
				]
			} );
		} );
	</script>
<form method="post" action="includes/process.inc.php?section=<?php echo $section; ?>&amp;action=<?php echo $action; ?>&amp;dbTable=judging_tables&amp;go=<?php echo $go; if ($action == "edit") echo "&amp;id=".$id; ?>" name="form1" onSubmit="return CheckRequiredFields()">
<table summary="Define a judging table and its associated name, number, style(s), and location.">
  <tbody>
  <tr>
    <td class="dataLabel">Table Name:</td>
    <td class="data"><input name="tableName" size="30" value="<?php if ($action == "edit") echo $row_tables_edit['tableName']; ?>"></td>
  </tr>
  <tr>
    <td class="dataLabel">Table Number:</td>
    <td class="data">
    <select name="tableNumber" id="tableNumber">
    	<?php 
		$query_last_table_number = "SELECT tableNumber FROM judging_tables ORDER BY tableNumber DESC LIMIT 1";
		$last_table_number = mysql_query($query_last_table_number, $brewing) or die(mysql_error());
		$row_last_table_number = mysql_fetch_assoc($last_table_number);
		for($i=1; $i<150+1; $i++) { 
		?>
    	<option value="<?php echo $i; ?>" <?php if (($action == "edit") && ($row_tables_edit['tableNumber'] == $i)) echo "selected"; elseif ($i <= $row_last_table_number['tableNumber']) echo "disabled"; else echo ""; ?>><?php echo $i; ?></option>
        <?php } ?>
    </select>
    </td>
  </tr>
  <?php if ($totalRows_judging1 > 1) { ?>
  <tr>
    <td class="dataLabel">Location:</td>
    <td class="data">
    <select name="tableLocation" id="tableLocation">
          <option value=""></option>
          <?php do { ?>
          <option value="<?php echo $row_judging1['id']; ?>" <?php if ($row_tables_edit['tableLocation'] == $row_judging1['id']) echo "selected"; ?>><?php echo $row_judging1['judgingLocName']." ("; echo date_convert($row_judging1['judgingDate'], 3, $row_prefs['prefsDateFormat']).")"; ?></option>
          <?php } while ($row_judging1 = mysql_fetch_assoc($judging1)) ?>
    </select>
    </td>
  </tr>
  <?php } ?>
  <tr>
    <td class="dataLabel">Style(s):</td>
    <td class="data">
    	<table class="dataTable" id="sortable">
        	<thead>
            <tr>
            	<th class="dataHeading bdr1B" width="1%">&nbsp;</th>
                <th class="dataHeading bdr1B" width="1%">#</th>
            	<th class="dataHeading bdr1B">BJCP Style</th>
                <th class="dataHeading bdr1B">Sub-Style</th>
                <th class="dataHeading bdr1B" width="10%">Ent.</th>
            </tr>
            </thead>
            <tbody>
        	<?php do { ?>
            <?php if (get_table_info($row_styles['brewStyle'],"count","",$dbTable) > 0) { ?>
            <tr>
            	<td><input name="tableStyles[]" type="checkbox" value="<?php echo $row_styles['id']; ?>" <?php if ($action == "edit") { if (get_table_info($row_styles['id'],"styles",$id,$dbTable)) echo "checked "; elseif (get_table_info($row_styles['id'],"styles","default",$dbTable)) echo "disabled"; else echo ""; }  if ($action == "add") { if (get_table_info($row_styles['id'],"styles","default",$dbTable)) echo "disabled"; } ?>></td>
                <td><?php echo $row_styles['brewStyleGroup'].$row_styles['brewStyleNum']; ?></td>
                <td class="data"><?php echo style_convert($row_styles['brewStyleGroup'],"1"); ?>
                <td class="data"><?php echo $row_styles['brewStyle'].get_table_info($row_styles['id'],"assigned","default",$dbTable); ?></td>
                <td class="data" style="text-align:right;"><?php echo get_table_info($row_styles['brewStyle'],"count","default",$dbTable); ?></td>
            </tr>
            <?php } ?>
            <?php } while ($row_styles = mysql_fetch_assoc($styles)); ?>
        	</tbody>
        </table>
    </td>
  </tr>
  </tbody>
</table>
<p><input type="submit" class="button" value="<?php if ($action == "edit") echo "Update"; else echo "Submit"; ?>"></p>
<input type="hidden" name="relocate" value="<?php echo relocate($_SERVER['HTTP_REFERER'],"default"); ?>">
</form>
<?php } // end if (($action == "add") || ($action == "edit")) ?>

<?php if (($action == "default") && ($filter == "orphans")) { ?>
<h3>Style Categories with Entries Not Assigned to Tables</h3>
<?php if  ($totalRows_tables > 0) {
	do { 
		if (get_table_info($row_styles['brewStyle'],"count","",$dbTable)) { 
			$a[] = 0;
			if (!get_table_info($row_styles['id'],"styles",$id,$dbTable)) { 
				$a[] = $row_styles['id'];
 				echo "<p>".$row_styles['brewStyleGroup'].$row_styles['brewStyleNum']."&nbsp;".style_convert($row_styles['brewStyleGroup'],"1").": ".$row_styles['brewStyle']." (Entries: ".get_table_info($row_styles['brewStyle'],"count","default",$dbTable).")</p>";  
			}
	 	} 
	} while ($row_styles = mysql_fetch_assoc($styles));
	$b = array_sum($a);
	if ($b == 0) echo "<p>All style categories with entries have been assigned to tables.</p>";
	}  else echo "<p>No tables have been defined yet. <a href='index.php?section=admin&amp;go=judging_tables&amp;action=add'>Add a table?</a></p>";
}// end if (($action == "default") && ($filter == "orphans"))
?>


<?php if (($action == "assign") && ($filter == "default") && ($id == "default")) { ?>
<h3>Assign Judges/Stewards to Tables</h3>
<table>
 <tr>
   <td class="dataLabel">Assign Judges To:</td>
   <td class="data">
   <select name="assign_table" id="assign_table" onchange="jumpMenu('self',this,0)">
	<option value="">Choose Below:</option>
    <?php do { ?>
	<option value="index.php?section=admin&amp;action=assign&amp;go=judging_tables&amp;filter=judges&amp;id=<?php echo $row_tables['id']; ?>"><?php echo "Table #".$row_tables['tableNumber'].": ".$row_tables['tableName']; ?></option>
    <?php } while ($row_tables = mysql_fetch_assoc($tables)); ?>
   </select>
  </td>
</tr>
<tr>
   <td class="dataLabel">Assign Stewards To:</td>
   <td class="data">
   <select name="assign_table" id="assign_table" onchange="jumpMenu('self',this,0)">
	<option value="">Choose Below:</option>
    <?php do { ?>
	<option value="index.php?section=admin&amp;action=assign&amp;go=judging_tables&amp;filter=stewards&amp;id=<?php echo $row_tables_edit['id']; ?>"><?php echo "Table #:".$row_tables_edit['tableNumber']." ".$row_tables_edit['tableName']; ?></option>
    <?php } while ($row_tables_edit = mysql_fetch_assoc($tables_edit)); ?>
   </select>
  </td>
</tr>
</table>
<?php } ?>

<?php if (($action == "assign") && ($filter != "default") && ($id != "default")) { 
$query_table_location = sprintf("SELECT * FROM judging_locations WHERE id='%s'",$row_tables_edit['tableLocation']);
$table_location = mysql_query($query_table_location, $brewing) or die(mysql_error());
$row_table_location = mysql_fetch_assoc($table_location);

$query_rounds = sprintf("SELECT flightRound FROM judging_flights WHERE flightTable='%s' ORDER BY flightRound DESC LIMIT 1", $row_tables_edit['id']);
$rounds = mysql_query($query_rounds, $brewing) or die(mysql_error());
$row_rounds = mysql_fetch_assoc($rounds);

$query_flights = sprintf("SELECT * FROM judging_flights WHERE flightTable='%s' ORDER BY flightNumber DESC LIMIT 1", $row_tables_edit['id']);
$flights = mysql_query($query_flights, $brewing) or die(mysql_error());
$row_flights = mysql_fetch_assoc($flights);

function table_round($table_id,$round) {
	include(CONFIG.'config.php');	
	mysql_select_db($database, $brewing);
	// get the round where the flight is assigned to
	$query_flight_round = sprintf("SELECT COUNT(*) as 'count' FROM judging_flights WHERE flightTable='%s' AND flightRound='%s' LIMIT 1", $table_id, $round);
	$flight_round = mysql_query($query_flight_round, $brewing) or die(mysql_error());
	$row_flight_round = mysql_fetch_assoc($flight_round);
	if ($row_flight_round['count'] > 0) return TRUE; else return FALSE;
    }

function flight_round($table_id,$flight,$round) {
	include(CONFIG.'config.php');	
	mysql_select_db($database, $brewing);
	// get the round where the flight is assigned to
	$query_flight_round = sprintf("SELECT flightRound FROM judging_flights WHERE flightTable='%s' AND flightNumber='%s' LIMIT 1", $table_id, $flight);
	$flight_round = mysql_query($query_flight_round, $brewing) or die(mysql_error());
	$row_flight_round = mysql_fetch_assoc($flight_round);
	if ($row_flight_round['flightRound'] == $round) return TRUE; else return FALSE;
    }

function already_assigned($bid,$table_id,$flight,$round) {
	include(CONFIG.'config.php');	
	mysql_select_db($database, $brewing);
	$query_assignments = sprintf("SELECT COUNT(*) as 'count' FROM judging_assignments WHERE (bid='%s' AND assignTable='%s' AND assignFlight='%s' AND assignRound='%s')", $bid, $table_id, $flight, $round);
	$assignments = mysql_query($query_assignments, $brewing) or die(mysql_error());
	$row_assignments = mysql_fetch_assoc($assignments);
	if ($row_assignments['count'] == 1) return TRUE; else return FALSE;
	}

function unavailable($bid,$location,$round,$table_id) { 
    // returns true a person is unavailable if they are already assigned to a table/flight in the same round at the same location
	include(CONFIG.'config.php');	
	mysql_select_db($database, $brewing);
	$query_assignments = sprintf("SELECT assignTable FROM judging_assignments WHERE bid='%s' AND assignRound='%s'", $bid, $round);
	$assignments = mysql_query($query_assignments, $brewing) or die(mysql_error());
	$row_assignments = mysql_fetch_assoc($assignments);
	$totalRows_assignments = mysql_num_rows($assignments);
	
	if ($totalRows_assignments > 0) {
		$query_table_location = sprintf("SELECT * FROM judging_tables WHERE id='%s'",$row_assignments['assignTable']);
		$table_location = mysql_query($query_table_location, $brewing) or die(mysql_error());
        $row_table_location = mysql_fetch_assoc($table_location);
		if (($row_table_location['tableLocation'] == $location) && ($row_table_location['id'] != $table_id)) return TRUE ;
	    }
	//else $r = "0";
	else return FALSE;
    }

function like_dislike($likes,$dislikes,$styles) { 
    // if a judge in the returned list listed one or more of the substyles
	// included in the table in their "likes" or "dislikes"
	include(CONFIG.'config.php');	
	mysql_select_db($database, $brewing);
	
	// get the table's associated styles from the "tables" table
	$s = explode(",",$styles);
	$r = "";	
	// check for likes
	if ($likes != "") {
	    $a = explode(",",$likes);
	        foreach ($a as $value) {
		        if (in_array($value,$s)) $b[] = 1; else $b[] = 0;
		    }
	$c = array_sum($b);
	} 
	else $c = 0;
	
	// check for dislikes
	if ($dislikes != "") {
	    $d = explode(",",$dislikes);
	        foreach ($d as $value) {
		       if (in_array($value,$s)) $e[] = 1; else $e[] = 0;
		    }
	$f = array_sum($e);
	} 
	
	else $f = 0;
	
	if (($c > 0) && ($f == 0)) $r .= ' style="background-color:#33CC00;"'; // 1 or more likes matched, color table cell green
	elseif (($c == 0) && ($f > 0)) $r .= ' style="background-color:#FF0505;"'; // 1 or more dislikes matched, color table cell red
	else $r .="";
	
	return $r;
	}

function entry_conflict($bid,$table_styles) {
	include(CONFIG.'config.php');	
	mysql_select_db($database, $brewing);
	
	$b = explode(",",$table_styles);
	
	foreach ($b as $style) {
		
		$query_style = sprintf("SELECT brewStyleGroup,brewStyleNum FROM styles WHERE id='%s'", $style);
		$style = mysql_query($query_style, $brewing) or die(mysql_error());
		$row_style = mysql_fetch_assoc($style);
		
		$query_entries = sprintf("SELECT COUNT(*) as 'count' FROM brewing WHERE brewBrewerID='%s' AND brewCategorySort='%s' AND brewSubCategory='%s'", $bid, $row_style['brewStyleGroup'],$row_style['brewStyleNum']);
		$entries = mysql_query($query_entries, $brewing) or die(mysql_error());
		$row_entries = mysql_fetch_assoc($entries);
		
		if ($row_entries['count'] > 0) $c[] = 1; else $c[] = 0;			
	}
	$d = array_sum($c);
	if ($d > 0) return TRUE;
}


function unassign($bid,$location,$round,$table_id) {
	if (unavailable($bid,$location,$round,$table_id)) {
	include(CONFIG.'config.php');	
	mysql_select_db($database, $brewing);
	$query_assignments = sprintf("SELECT id FROM judging_assignments WHERE bid='%s' AND assignRound='%s' AND assignLocation='%s'", $bid, $round, $location);
	$assignments = mysql_query($query_assignments, $brewing) or die(mysql_error());
	$row_assignments = mysql_fetch_assoc($assignments);	
	}
	$r = '<input type="checkbox" name="'.$round.'-unassign'.$bid.'" id="'.$round.'-unassign'.$bid.'" value="'.$row_assignments['id'].'"/><span style="vertical-align:middle;"> Unassign and...</span>';
	//$r = $query_assignments;
	return $r;
}
?>

        

<h3>Assign <?php if ($filter == "stewards") echo "Stewards"; else echo "Judges"; ?> to Table #<?php echo $row_tables_edit['tableNumber'].": ".$row_tables_edit['tableName']; ?></h3>
<p><strong>Location:</strong> <?php echo $row_table_location['judgingLocName']." &ndash; ".date_convert($row_table_location['judgingDate'], 2, $row_prefs['prefsDateFormat'])." at ".$row_table_location['judgingTime']; ?></p>
<?php if ($row_judging_prefs['jPrefsQueued'] == "N") { ?>
<p><strong>Number of Flights:</strong> <?php echo $row_flights['flightNumber']; ?>
<ul>
<?php 
	for($c=1; $c<$row_flights['flightNumber']+1; $c++) {
	$query_entry_count = sprintf("SELECT COUNT(*) as 'count' FROM judging_flights WHERE flightTable='%s' AND flightNumber='%s'", $row_tables_edit['id'], $c);
	$entry_count = mysql_query($query_entry_count, $brewing) or die(mysql_error());
	$row_entry_count = mysql_fetch_assoc($entry_count);
	echo "<li>Flight $c: ".$row_entry_count['count']." entries.</li>";
	}
?>
</ul>
</p>
<?php } ?>
<script type="text/javascript" language="javascript">
	 $(document).ready(function() {
		$('#sortable').dataTable( {
			"bPaginate" : true,
			"sPaginationType" : "full_numbers",
			"bLengthChange" : true,
			"iDisplayLength" : <?php echo round($row_prefs['prefsRecordPaging']); ?>,
			"sDom": 'irtip',
			"bStateSave" : false,
			"aaSorting": [[1,'desc']],
			"bProcessing" : false,
			"aoColumns": [
				null,
				<?php if ($filter == "judges") { ?>
				null,
				null<?php } for($i=1; $i<$row_flights['flightRound']+1; $i++) {  
			    if  (table_round($row_tables_edit['id'],$i)) { 
				?>, null<?php } } ?>
				]
			} );
		} );
	</script>
<form name="form1" method="post" action="includes/process.inc.php?action=update&amp;dbTable=judging_assignments&amp;filter=<?php echo $filter; ?>&amp;limit=<?php echo $row_rounds['flightRound']; ?>&amp;view=<?php echo $row_judging_prefs['jPrefsQueued']; ?>&amp;id=<?php echo $id; ?>">
<?php if ($filter == "judges") { ?>
<table class="dataTableCompact bdr1" style="margin: 20px 0 10px 0;">
<thead>
	<tr>
    	<th class="dataHeading bdr1B" colspan="2">Legend</th>
    </tr>
</thead>
<tbody>
	<tr>
        <td style="background-color:#FF9200">Orange</td>
        <td class="data" style="background-color:#FFDEB3;">Participant is already assigned to this round at another table.</td> 
    </tr>
	
    <tr>
        <td style="background-color:#FF0505;">Red</td>
        <td class="data" style="background-color:#FFD1D1;">One or more table styles are on the participant's "dislikes" list.</td> 
    </tr>
    <tr>
    	<td style="background-color:#33CC00;">Green</td>
        <td class="data" style="background-color:#D9FFCC;">One or more table styles are on the participant's "likes" list.</td>
    </tr>
    <tr>
        <td style="background-color:#FF0">Yellow</td>
        <td class="data" style="background-color:#FFFF99;">Participant is unavailable due to having an entry that will be judged at this table.</td> 
    </tr>
</tbody>
</table>
<?php } ?>
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
<?php do { ?>
	<tr> 
    	<td nowrap="nowrap"><?php echo $row_brewer['brewerLastName'].", ".$row_brewer['brewerFirstName']; // echo " - ".$row_brewer['id']; ?></td>
        <?php if ($filter == "judges") { ?>
        <td nowrap="nowrap"><?php echo bjcp_rank($row_brewer['brewerJudgeRank'],1); ?></td>
        <td nowrap="nowrap"><?php if (($row_brewer['brewerJudgeID'] != "") && ($row_brewer['brewerJudgeID'] != "0")) echo $row_brewer['brewerJudgeID']; else echo "N/A"; ?></td>
        <?php } ?>
        <input type="hidden" name="bid[]" value="<?php echo $row_brewer['id']; ?>" />
        <input type="hidden" name="assignTable<?php echo $row_brewer['id']; ?>" value="<?php echo $id; ?>" />
        <input type="hidden" name="assignment<?php echo $row_brewer['id']; ?>" value="<?php if ($filter == "stewards") echo "S"; else echo "J"; ?>" />
        <input type="hidden" name="assignLocation<?php echo $row_brewer['id']; ?>" value="<?php echo $row_tables_edit['tableLocation']; ?>" />
		<?php 	for($i=1; $i<$row_flights['flightRound']+1; $i++) { 
		        if (table_round($row_tables_edit['id'],$i)) {
			    $un = unavailable($row_brewer['id'],$row_tables_edit['tableLocation'],$i,$id);
				$entry_conflict = entry_conflict($row_brewer['id'],$row_tables_edit['tableStyles']);
				if ($un == TRUE) $ld = ' style="background-color:#FF9200"'; 
				elseif ($entry_conflict == TRUE) $ld = ' style="background-color:#FF0"';
				else $ld = like_dislike($row_brewer['brewerJudgeLikes'],$row_brewer['brewerJudgeDislikes'],$row_tables_edit['tableStyles']);
		?>
        <?php if ($un == FALSE) { ?>
        <input type="hidden" name="<?php echo $i; ?>-unassign<?php echo $row_brewer['id']; ?>" value="N" />
        <?php } ?> 
        <td class="data"<?php echo $ld; ?>>
        <?php if ($un == TRUE) { ?>
        <strong>** Already Assigned to This Round **</strong><br />
        <?php
		if ($entry_conflict == FALSE) echo unassign($row_brewer['id'],$row_tables_edit['tableLocation'],$i,$id);
		} 
		
		if ($row_judging_prefs['jPrefsQueued'] == "N") { ?>      
            <select name="<?php echo $i."-assignFlight".$row_brewer['id']; ?>" id="<?php echo $i."-assignFlight".$row_brewer['id']; ?>" <?php if ($entry_conflict == TRUE) echo "DISABLED"; ?> />
            <option value="D-<?php echo $i; ?>"><?php if ($entry_conflict == TRUE) echo "Participant Has an Entry at this Table"; else echo "Do Not Assign to a Flight"; ?></option>
        	<?php 
			for($f=1; $f<$row_flights['flightNumber']+1; $f++) {
				if (flight_round($row_tables_edit['id'],$f,$i)) { 
				$a = already_assigned($row_brewer['id'],$row_tables_edit['id'],$f,$i);
				?>
			    <option value="<?php echo $i."-".$f; if ($a == TRUE) echo "-Y"; else echo "-N" ?>" <?php if ($a == TRUE) echo 'selected="selected"'; ?>/>Assign<?php if ($a == TRUE) echo "ed"; ?> to Flight <?php echo $f; ?></option>
			<?php 
				}
			  }
			} 
			?>
        <?php if ($row_judging_prefs['jPrefsQueued'] == "Y") { ?>
        <select name="<?php echo $i."-assignTable".$row_brewer['id']; ?>" id="<?php echo $i."-assignTable".$row_brewer['id']; ?>" <?php if ($entry_conflict == TRUE) echo "DISABLED"; ?> />
            <option value="D-<?php echo $i; ?>"><?php if ($entry_conflict == TRUE) echo "Participant Has an Entry at this Table"; else echo "Do Not Assign to this Round"; ?></option>
        	<?php $a = already_assigned($row_brewer['id'],$row_tables_edit['id'],"1",$i); ?>
			    <option  value="<?php echo $i."-1"; if ($a == TRUE) echo "-Y"; else echo "-N" ?>" <?php if ($a == TRUE) echo 'selected="selected"'; ?>/>Assign<?php if ($a == TRUE) echo "ed"; ?> to This Round</option>
        	<?php } ?>
        </td> 
        <?php }  
		} ?>
    </tr> 
<?php } while ($row_brewer = mysql_fetch_assoc($brewer)); ?>	
</tbody>
</table>
<p><input type="submit" class="button" name="Submit" value="Assign to Table #<?php echo $row_tables_edit['tableNumber']; ?>" /></p>
<input type="hidden" name="relocate" value="<?php echo relocate("http://".$_SERVER['SERVER_NAME'].$_SERVER['SCRIPT_NAME']."?".$_SERVER['QUERY_STRING'],"default"); if ($msg != "default") echo "&id=".$id; ?>">
</form>
<?php } // end if ($action == "update") { ?>
<?php
//mysql_free_result($styles);
mysql_free_result($tables);
mysql_free_result($tables_edit);
?>