<?php 
include(DB.'judging_locations.db.php'); 
include(DB.'styles.db.php');
include(DB.'brewer.db.php');
function assigned_judges($tid,$dbTable,$judging_assignments_db_table){
	include(CONFIG.'config.php');
	//include(INCLUDES.'db_tables.inc.php');
	mysql_select_db($database, $brewing);
	$query_assignments = sprintf("SELECT COUNT(*) as 'count' FROM %s WHERE assignTable='%s' AND assignment='J'", $judging_assignments_db_table, $tid);
	$assignments = mysql_query($query_assignments, $brewing) or die(mysql_error());
	$row_assignments = mysql_fetch_assoc($assignments);
	if ($dbTable == "default") $r = '<a href="index.php?section=admin&action=assign&go=judging_tables&filter=judges&id='.$tid.'" title="Assign Judges to this Table">'.$row_assignments['count']."</a>";
	else $r = $row_assignments['count'];
	return $r;
}

function assigned_stewards($tid,$dbTable,$judging_assignments_db_table){
	include(CONFIG.'config.php');	
	//include(INCLUDES.'db_tables.inc.php');
	mysql_select_db($database, $brewing);
	$query_assignments = sprintf("SELECT COUNT(*) as 'count' FROM %s WHERE assignTable='%s' AND assignment='S'", $judging_assignments_db_table, $tid);
	$assignments = mysql_query($query_assignments, $brewing) or die(mysql_error());
	$row_assignments = mysql_fetch_assoc($assignments);
	if ($dbTable == "default") $r = '<a href="index.php?section=admin&action=assign&go=judging_tables&filter=stewards&id='.$tid.'" title="Assign Stewards to this Table">'.$row_assignments['count']."</a>";
	else $r = $row_assignments['count'];
	return $r;
}
?>
<h2><?php 
if ($action == "edit") echo "Edit Table"; 
elseif ($action == "add") echo "Add a Table"; 
else echo "All Tables"; 
if ($dbTable != "default") echo ": ".get_suffix($dbTable); ?></h2>
<?php if ($action != "print") { ?>
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
    <?php if  ($dbTable == "default") { ?>
<span class="adminSubNav">
  		<span class="icon"><img src="images/printer.png" alt="Print" title="Print..." /></span>
  		<div class="menuBar"><a class="menuButton" href="#" onclick="#" onmouseover="buttonMouseover(event, 'printMenu_tables');">Print...</a></div>
  		<div id="printMenu_tables" class="menu" onmouseover="menuMouseover(event)">
  			<a id="modal_window_link" class="menuItem" href="output/print.php?section=admin&amp;go=judging_tables&amp;action=print">Tables List</a>
    		<a id="modal_window_link" class="menuItem" href="output/pullsheets.php?section=admin&amp;go=judging_tables&amp;id=default">Pullsheets by Table</a>
		</div>
	</span>
    <?php } ?>
</div>
<?php } ?>
<?php if ((($action == "default") || ($action == "edit"))  && ($dbTable == "default")) { ?>
<table class="dataTableCompact">
<tr>
	<td><strong>Step 1: </strong>Assign Judges and Stewards</td>
	<td>
    	<span class="icon"><img src="images/user_add.png" alt="Back"></span><a href="index.php?section=admin&amp;action=assign&amp;go=judging&amp;filter=judges">Assign Particpants as Judges</a>
    </td>
    <td>
        <span class="icon"><img src="images/user_add.png" alt="Back"></span><a href="index.php?section=admin&amp;action=assign&amp;go=judging&amp;filter=stewards">Assign Particpants as Stewards</a>
    </td>
</tr>
<tr>
	<td><strong>Step 2: </strong>Define All Tables</span>
	<td>
    	<span class="icon"><img src="images/application_add.png" alt="Back"></span><a href="index.php?section=admin&amp;go=judging_tables&amp;action=add">Add a Table</a>
    </td>
    <?php if ($filter != "orphans") { ?>
	<td>
  		<span class="icon"><img src="images/application_view_list.png" alt="Orphans" title="Styles Not Assigned to Tables" /></span><a href="index.php?section=admin&amp;go=judging_tables&amp;filter=orphans">View Styles Not Assigned to Tables</a>
    </td>
	<?php } ?>
</tr>
<?php } ?>
<?php if (($action == "default")  && ($dbTable == "default")) { ?>
<?php if (($totalRows_tables > 0) && ($dbTable == "default")) { ?>
<tr>
	<td><strong>Step 3: </strong>Define All Flights</td>
    <td class="data">
	<?php if ($row_judging_prefs['jPrefsQueued'] == "N") { ?>
	
    	<span class="icon"><img src="images/application_form_add.png" alt="Define/Edit flights" title="Define/Edit flights" /></span>
<div class="menuBar"><a class="menuButton" href="#" onclick="#" onmouseover="buttonMouseover(event, 'flightsMenu_tables');">Define/Edit Flights for...</a></div>
    	<div id="flightsMenu_tables" class="menu" onmouseover="menuMouseover(event)">
    		<?php do { 
			$query_flights_2 = sprintf("SELECT COUNT(*) as 'count' FROM $judging_flights_db_table WHERE flightTable='%s'", $row_tables_edit['id']);
			$flights_2 = mysql_query($query_flights_2, $brewing) or die(mysql_error());
			$row_flights_2 = mysql_fetch_assoc($flights_2);
			$totalRows_flights_2 = $row_flights_2['count'];
			?>
    		<a class="menuItem" href="index.php?section=admin&amp;go=judging_flights&amp;action=<?php if ($totalRows_flights_2 > 0) echo "edit"; else echo "add"; echo "&amp;id=".$row_tables_edit['id']; ?>"><?php echo "Table #".$row_tables_edit['tableNumber'].": ".$row_tables_edit['tableName']; ?></a>
    		<?php mysql_free_result($flights_2); 
			} while ($row_tables_edit = mysql_fetch_assoc($tables_edit)); ?>
    	</div>
	
    <?php } else echo "Skipped. Defining flights is disabled for queued judging...less work for you!"; ?>
    </td>
    <td class="data">&nbsp;</td>
</tr>
<tr>
	<td><strong>Step 4: </strong><?php echo "Assign ".$assign_to." to Rounds"; ?></td>
    <td class="data">
    	<span class="icon"><img src="images/application_form_add.png" alt="<?php echo "Assign ".$assign_to." to Rounds"; ?>" /></span><a href="index.php?section=admin&amp;go=judging_flights&amp;action=assign&amp;&filter=rounds"><?php echo "Assign ".$assign_to." to Rounds"; ?></a>
    </td>
    <td class="data">&nbsp;</td>
</tr>
<tr>
	<td><strong>Step 5: </strong>Assign Judges and Stewards to Tables</td>
    <td class="data">
    	<span class="icon"><img src="images/user_add.png" alt="Assign Judges/Stewards to Tables"></span><a href="index.php?section=admin&amp;action=assign&amp;go=judging_tables">Assign Judges/Stewards to Tables</a>
    </td>
    <td class="data">&nbsp;</td>
</tr>
<?php } // end if ($totalRows_tables > 0) ?>
<?php if (($totalRows_tables > 0) && ($dbTable == "default")) { ?>
<tr>
	<td><strong>Step 6: </strong>Enter or Add Scores</td>
	<td class="data">
    	<span class="icon"><img src="images/rosette_add.png" alt="Enter/Edit scores" title="Enter/Edit scores" /></span>
		<div class="menuBar"><a class="menuButton" href="#" onclick="#" onmouseover="buttonMouseover(event, 'scoresMenu_tables');">Enter/Edit Scores for...</a></div>
    	<div id="scoresMenu_tables" class="menu" onmouseover="menuMouseover(event)">
    		<?php do { 
			$query_scores_2 = sprintf("SELECT COUNT(*) as 'count' FROM $judging_scores_db_table WHERE scoreTable='%s'", $row_tables_edit_2['id']);
			$scores_2 = mysql_query($query_scores_2, $brewing) or die(mysql_error());
			$row_scores_2 = mysql_fetch_assoc($scores_2);
			$totalRows_scores_2 = $row_scores_2['count'];
			?>
    		<a class="menuItem" href="index.php?section=admin&amp;go=judging_scores&amp;action=<?php if ($totalRows_scores_2  > 0) echo "edit&amp;id=".$row_tables_edit_2['id']; else echo "add&amp;id=".$row_tables_edit_2['id']; ?>"><?php echo "Table #".$row_tables_edit_2['tableNumber'].": ".$row_tables_edit_2['tableName']; ?></a>
    		<?php mysql_free_result($scores_2);	} while ($row_tables_edit_2 = mysql_fetch_assoc($tables_edit_2)); ?>
		</div>
	</td>
    <td class="data">
    	<span class="icon"><img src="images/rosette.png" alt="View Scores"></span>
    	<div class="menuBar"><a class="menuButton" href="#" onclick="#" onmouseover="buttonMouseover(event, 'scoreMenu');">View Scores...</a></div>
  		<div id="scoreMenu" class="menu" onmouseover="menuMouseover(event)">
  			<a class="menuItem" href="index.php?section=admin&go=judging_scores">By Table</a>
    		<a class="menuItem" href="index.php?section=admin&go=judging_scores&amp;filter=category">By Category</a>
  		</div>
	</td>
</tr>
<tr>
	<td><strong>Step 7: </strong>Enter or Edit BOS Entries</td>
    <td class="data">
    	<span class="icon"><img src="images/rosette_add.png" alt="Enter/Edit scores" title="Enter/Edit scores" /></span>
		<div class="menuBar"><a class="menuButton" href="#" onclick="#" onmouseover="buttonMouseover(event, 'scoresMenu_bos_2');">Enter/Edit BOS Places For...</a></div>
		<div id="scoresMenu_bos_2" class="menu" onmouseover="menuMouseover(event)">
		<?php do { 
			if ($row_style_types['styleTypeBOS'] == "Y") { ?>
			<a class="menuItem" href="index.php?section=admin&amp;go=judging_scores_bos&amp;action=enter&amp;filter=<?php echo $row_style_types['id'] ?>">BOS Places - <?php echo $row_style_types['styleTypeName']; ?></a>
		<?php 
			}
		} while ($row_style_types = mysql_fetch_assoc($style_types));
		?>
		</div>
    </td>
     <td class="data">
		<span class="icon"><img src="images/rosette.png" alt="Enter/Edit scores" title="Enter/Edit scores" /></span><a href="index.php?section=admin&amp;go=judging_scores_bos">View BOS Entries and Places</a>
    </td>
</tr>

<?php } // end if (($totalRows_tables > 0) && ($dbTable == "default")) ?>
</table>
<?php } // end if ($action == "default")?>
<?php if ($action != "print") { ?>
<?php if (($totalRows_judging > 1) && ($dbTable == "default")) { ?>
<table>
	<td colspan="2" class="dataLabel">Total Entries by Location</th>
	<?php do { ?>
	<tr>
    	<td class="dataLeft" nowrap="nowrap" width="1%" style="padding-left:0;"><?php echo $row_judging['judgingLocName']." &ndash; ".getTimeZoneDateTime($row_prefs['prefsTimeZone'], $row_judging['judgingDate'], $row_prefs['prefsDateFormat'],  $row_prefs['prefsTimeFormat'], "long", "date-time"); ?></td>
        <td class="data"><?php echo get_table_info(1,"count_total","default","default",$row_judging['id']); ?></td>
     </tr>  
	<?php } while ($row_judging = mysql_fetch_assoc($judging)); ?>
</table>
<?php } ?>
<?php if (($action == "default") && ($filter == "default") && ($dbTable == "default")) { ?>
<p>To ensure accuracy, verify that all paid and received entries have been marked as so via the <a href="index.php?section=admin&amp;go=entries">Manage Entries</a> screen.</p>
<table class="dataTable" style="margin-bottom: 2em;">
<tbody>
	<tr>
    	<td width="30%" nowrap="nowrap"><a href="index.php?section=admin&amp;go=judging_preferences">Competition organization preferences</a> are set to:<br />
        	<ul>
				<li><?php if ($row_judging_prefs['jPrefsQueued'] == "Y") echo "Queued Judging (no flights)."; else echo "Non-Queued Judging (with flights)."; ?></li>
				<li>Maximum Rounds <?php if ($totalRows_judging > 0) echo "(per location)"; ?>: <?php echo $row_judging_prefs['jPrefsRounds']; ?>.</li>
                <li>Maximum BOS Places (per style type): <?php echo $row_judging_prefs['jPrefsMaxBOS']; ?>.</li> 
    			<?php if ($row_judging_prefs['jPrefsQueued'] == "N") { ?>
    			<li>Maximum Entries per Flight: <?php echo $row_judging_prefs['jPrefsFlightEntries']; ?>.</li> 
    			<?php } ?>
       		</ul>
        </td>
        <td>A Best of Show round is enabled for the following <a href="index.php?section=admin&amp;go=style_types">Style Types</a>:<br />
        	<ul>
            	<?php do { ?>
            	<li><?php echo $row_style_type['styleTypeName']." (".bos_method($row_style_type['styleTypeBOSMethod'])." from each table to BOS)."; ?></li>
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
        <th class="dataHeading bdr1B">Judges</th>
        <th class="dataHeading bdr1B">Stewards</th>
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
        <td class="data<?php if ($action == "print") echo " bdr1B"; ?>" width="10%"><?php echo $row_tables['tableName']; ?></td>
        <td class="data<?php if ($action == "print") echo " bdr1B"; ?>" width="15%"><?php $a = array(get_table_info(1,"list",$row_tables['id'],$dbTable,"default")); echo display_array_content($a,1); ?></td>
        <td class="data<?php if ($action == "print") echo " bdr1B"; ?>" width="8%"><?php echo get_table_info(1,"count_total",$row_tables['id'],$dbTable,"default"); ?></td>
        <td class="data<?php if ($action == "print") echo " bdr1B"; ?>" width="8%"><?php echo assigned_judges($row_tables['id'],$dbTable,$judging_assignments_db_table); ?></td>
        <td class="data<?php if ($action == "print") echo " bdr1B"; ?>" width="8%"><?php echo assigned_stewards($row_tables['id'],$dbTable,$judging_assignments_db_table); ?></td>
		<?php if (($totalRows_judging > 1) && ($dbTable == "default")) { ?>
        <td class="data<?php if ($action == "print") echo " bdr1B"; ?>"><?php echo table_location($row_tables['id'],$row_prefs['prefsDateFormat'],$row_prefs['prefsTimeZone'],$row_prefs['prefsTimeFormat'],"default") ?></td>
        <?php } ?>
        <?php if (($action != "print") && ($dbTable == "default")) { ?>
        <td class="data<?php if ($action == "print") echo " bdr1B"; ?>" width="5%" nowrap="nowrap"><span class="icon"><a href="index.php?section=admin&amp;go=<?php echo $go; ?>&amp;action=edit&amp;id=<?php echo $row_tables['id']; ?>"><img src="images/pencil.png"  border="0" alt="Edit the <?php echo $row_tables['tableName']; ?> table" title="Edit the <?php echo $row_tables['tableName']; ?> table"></a></span><span class="icon"><a href="javascript:DelWithCon('includes/process.inc.php?section=<?php echo $section; ?>&amp;go=<?php echo $go; ?>&amp;filter=<?php echo $filter; ?>&amp;dbTable=<?php echo $judging_tables_db_table; ?>&amp;go=judging_tables&amp;action=delete','id',<?php echo $row_tables['id']; ?>,'Are you sure you want to delete the <?php echo $row_tables['tableName']; ?> table?\nALL associated FLIGHTS and SCORES will be deleted as well.\nThis cannot be undone.');"><img src="images/bin_closed.png"  border="0" alt="Delete the <?php echo $row_tables['tableName']; ?> table" title="Delete the <?php echo $row_tables['tableName']; ?> table"></a></span><span class="icon"><a id="modal_window_link" href="output/pullsheets.php?section=admin&amp;go=judging_tables&amp;id=<?php echo $row_tables['id']; ?>"><img src="images/printer.png"  border="0" alt="Print the pullsheet for <?php echo $row_tables['tableName']; ?>" title="Print the pullsheet for <?php echo $row_tables['tableName']; ?>"></a></span><?php if ($row_judging_prefs['jPrefsQueued'] == "N") { if (flight_count($row_tables['id'],1)) { ?><span class="icon"><a href="index.php?section=admin&amp;go=judging_flights&amp;action=edit&amp;id=<?php echo $row_tables['id']; ?>"><img src="images/application_form_edit.png" alt="Edit flights for <?php echo $row_tables['tableName']; ?>" title="Edit flights for <?php echo $row_tables['tableName']; ?>" /></a></span><?php } elseif (($totalRows_flights == 0) && ($entry_count > $row_judging_prefs['jPrefsFlightEntries'])) { ?><span class="icon"><a href="index.php?section=admin&amp;go=judging_flights&amp;action=add&amp;id=<?php echo $row_tables['id']; ?>"><img src="images/application_form_add.png" alt="Define flights for <?php echo $row_tables['tableName']; ?>" title="Define flights for <?php echo $row_tables['tableName']; ?>" /></a></span><?php } else { ?><span class="icon"><img src="images/application_form_fade.png" alt="No need to define flights for <?php echo $row_tables['tableName']; ?>" title="No need to define flights for <?php echo $row_tables['tableName']; ?>" /></span><?php } ?><?php } // end if ($row_judging_prefs['jPrefsQueued'] == "N") ?><?php if (score_count($row_tables['id'],1)) { ?><span class="icon"><a href="index.php?section=admin&amp;go=judging_scores&amp;action=edit&amp;id=<?php echo $row_tables['id']; ?>"><img src="images/rosette_edit.png" alt="Edit scores for <?php echo $row_tables['tableName']; ?>" title="Edit scores for <?php echo $row_tables['tableName']; ?>" /></a></span><?php } else { ?><span class="icon"><a href="index.php?section=admin&amp;go=judging_scores&amp;action=add&amp;id=<?php echo $row_tables['id']; ?>"><img src="images/rosette_add.png" alt="Enter scores for <?php echo $row_tables['tableName']; ?>" title="Enter scores for <?php echo $row_tables['tableName']; ?>" /></a></span><?php } ?>
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
<form method="post" action="includes/process.inc.php?section=<?php echo $section; ?>&amp;action=<?php echo $action; ?>&amp;dbTable=<?php echo $judging_tables_db_table; ?>&amp;go=<?php echo $go; if ($action == "edit") echo "&amp;id=".$id; ?>" name="form1" id="form1" onSubmit="return CheckRequiredFields()">
<p><input type="submit" class="button" value="<?php if ($action == "edit") echo "Update Table"; else echo "Add Table"; ?>"></p>
<table summary="Define a judging table and its associated name, number, style(s), and location.">
  <tbody>
  <tr>
    <td class="dataLabel">Table Name:</td>
    <td class="data"><input name="tableName" size="30" value="<?php if ($action == "edit") echo $row_tables_edit['tableName']; ?>"><span class="required">Required</span></td>
  </tr>
  <tr>
    <td class="dataLabel">Table Number:</td>
    <td class="data">
    <?php 
	$query_table_number = "SELECT tableNumber FROM $judging_tables_db_table ORDER BY tableNumber";
	$table_number = mysql_query($query_table_number, $brewing) or die(mysql_error());
	$row_table_number = mysql_fetch_assoc($table_number);
	do { $a[] = $row_table_number['tableNumber']; } while ($row_table_number = mysql_fetch_assoc($table_number));
	//print_r($a);
		?>
    
    <select name="tableNumber" id="tableNumber">
    	<?php		
		for($i=1; $i<150+1; $i++) { 
		?>
    	<option value="<?php echo $i; ?>" <?php if (($action == "edit") && ($row_tables_edit['tableNumber'] == $i)) echo "selected"; elseif (in_array($i,$a)) echo "disabled"; elseif (($action != "edit") && (($row_last_table_number['tableNumber'] + 1) == $i)) echo "selected"; else echo ""; ?>><?php echo $i; ?></option>
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
          <option value="<?php echo $row_judging1['id']; ?>" <?php if ($row_tables_edit['tableLocation'] == $row_judging1['id']) echo "selected"; ?>><?php echo $row_judging1['judgingLocName']." ("; echo getTimeZoneDateTime($row_prefs['prefsTimeZone'], $row_judging1['judgingDate'], $row_prefs['prefsDateFormat'],  $row_prefs['prefsTimeFormat'], "short", "date-time-no-gmt").")"; ?></option>
          <?php } while ($row_judging1 = mysql_fetch_assoc($judging1)) ?>
    </select>
    </td>
  </tr>
  <?php } else { ?>
  <input type="hidden" name="tableLocation"  value="<?php echo $row_judging1['id']; ?>" />
  <?php } ?>
  <tr>
    <td class="dataLabel">Style(s):</td>
    <td class="data">
    <?php 
    $query_entry_count = "SELECT COUNT(*) as 'count' FROM $brewing_db_table WHERE brewReceived='1'"; // Changed to only "brewReceived" by request as of 1.2.1.0
	$result = mysql_query($query_entry_count, $brewing) or die(mysql_error());
	$row = mysql_fetch_array($result);

    if ($row['count'] > 0) { ?>
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
            <?php if (get_table_info($row_styles['brewStyle'],"count","",$dbTable,"default") > 0) { ?>
            <tr>
            	<td><input name="tableStyles[]" type="checkbox" value="<?php echo $row_styles['id']; ?>" <?php if ($action == "edit") { if (get_table_info($row_styles['id'],"styles",$id,$dbTable,"default")) echo "checked "; elseif (get_table_info($row_styles['id'],"styles","default",$dbTable,"default")) echo "disabled"; else echo ""; }  if ($action == "add") { if (get_table_info($row_styles['id'],"styles","default",$dbTable,"default")) echo "disabled"; } ?>></td>
                <td><?php echo $row_styles['brewStyleGroup'].$row_styles['brewStyleNum']; ?></td>
                <td class="data"><?php echo style_convert($row_styles['brewStyleGroup'],"1"); ?>
                <td class="data"><?php echo $row_styles['brewStyle'].get_table_info($row_styles['id'],"assigned","default",$dbTable,"default"); ?></td>
                <td class="data" style="text-align:right;"><?php echo get_table_info($row_styles['brewStyle'],"count","default",$dbTable,"default"); ?></td>
            </tr>
            <?php } ?>
            <?php } while ($row_styles = mysql_fetch_assoc($styles)); ?>
        	</tbody>
        </table>
    <?php } else echo "There are no styles available with entries marked as both paid and received."; ?>
    </td>
  </tr>
  </tbody>
</table>
<p><input type="submit" class="button" value="<?php if ($action == "edit") echo "Update Table"; else echo "Add Table"; ?>"></p>
<input type="hidden" name="relocate" value="<?php echo relocate($_SERVER['HTTP_REFERER'],"default",$msg,$id); ?>">
</form>
<?php } // end if (($action == "add") || ($action == "edit")) ?>

<?php if (($action == "default") && ($filter == "orphans")) { ?>
<h3>Style Categories with Entries Not Assigned to Tables</h3>
<?php if  ($totalRows_tables > 0) {
	do { 
		if (get_table_info($row_styles['brewStyle'],"count","",$dbTable,"default")) { 
			$a[] = 0;
			if (!get_table_info($row_styles['id'],"styles",$id,$dbTable,"default")) { 
				$a[] = $row_styles['id'];
 				echo "<ul><li>".$row_styles['brewStyleGroup'].$row_styles['brewStyleNum']." ".style_convert($row_styles['brewStyleGroup'],"1").": ".$row_styles['brewStyle']." (".get_table_info($row_styles['brewStyle'],"count","default",$dbTable,"default")." entries)</li></ul>";  
			}
	 	} 
	} while ($row_styles = mysql_fetch_assoc($styles));
	$b = array_sum($a);
	if ($b == 0) echo "<p>All style categories with entries have been assigned to tables.</p>";
	else { echo "<p>No tables have been defined."; if ($dbTable == "judging_tables") echo "<a href='index.php?section=admin&amp;go=judging_tables&amp;action=add'>Add a table?</a></p>"; } }
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

<?php if (($action == "assign") && ($filter != "default") && ($id != "default")) include ('judging_assign.admin.php'); ?>