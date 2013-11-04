<?php 
include(DB.'styles.db.php'); 
include(DB.'judging_tables.db.php');
?>
<h2><?php 
if ($action == "edit") echo "Edit Table"; 
elseif ($action == "add") echo "Add a Table"; 
else echo "All Tables"; 
if ($dbTable != "default") echo ": ".get_suffix($dbTable); ?></h2>

<?php if ($action != "print") { ?>
<div class="adminSubNavContainer">
		<span class="adminSubNav">
        	<span class="icon"><img src="<?php echo $base_url; ?>images/arrow_left.png" alt="Back"></span><?php if ($action == "default") { ?><a href="<?php echo $base_url; ?>index.php?section=admin">Back to Admin Dashboard</a><?php } else { ?><a href="<?php echo $base_url; ?>index.php?section=admin&amp;go=judging_tables">Back to Tables List</a><?php } ?>
     	</span>
	<?php if  ($dbTable != "default") { ?>
		<span class="adminSubNav">
        	<span class="icon"><img src="<?php echo $base_url; ?>images/arrow_left.png" alt="Back"></span><a href="<?php echo $base_url; ?>index.php?section=admin&amp;go=archive">Back to Archives</a>
     	</span> 
	<?php } ?>   
	<?php if ($filter == "orphans") { ?>
	<span class="adminSubNav">
    	<span class="icon"><img src="<?php echo $base_url; ?>images/application.png" alt="Back"></span><a href="<?php echo $base_url; ?>index.php?section=admin&amp;go=judging_tables">View Tables List</a>
    </span>
	<?php } ?>
    <?php if  ($dbTable == "default") { ?>
<span class="adminSubNav">
  		<span class="icon"><img src="<?php echo $base_url; ?>images/printer.png" alt="Print" title="Print..." /></span>
  		<div class="menuBar"><a class="menuButton" href="#" onclick="#" onmouseover="buttonMouseover(event, 'printMenu_tables');">Print...</a></div>
  		<div id="printMenu_tables" class="menu" onmouseover="menuMouseover(event)">
  			<a id="modal_window_link" class="menuItem" href="<?php echo $base_url; ?>output/print.php?section=admin&amp;go=judging_tables&amp;action=print">Tables List</a>
    		<a id="modal_window_link" class="menuItem" href="<?php echo $base_url; ?>output/pullsheets.php?section=admin&amp;go=judging_tables&amp;id=default">Pullsheets by Table</a>
            <a id="modal_window_link" class="menuItem" href="<?php echo $base_url; ?>output/assignments.php?section=admin&amp;go=judging_assignments&amp;filter=judges&amp;view=name" title="Print Judge Assignments by Name">Judge Assignments By Last Name</a>
			<a id="modal_window_link" class="menuItem" href="<?php echo $base_url; ?>output/assignments.php?section=admin&amp;go=judging_assignments&amp;filter=judges&amp;view=table" title="Print Judge Assignments by Table">Judge Assignments By Table</a>
   			<?php if ($totalRows_judging > 1) { ?>
			<a id="modal_window_link" class="menuItem" href="<?php echo $base_url; ?>output/assignments.php?section=admin&amp;go=judging_assignments&amp;filter=judges&amp;view=location" title="Print Judge Assignments by Location">Judge Assignments By Location</a>
    		<?php } ?>
            <a id="modal_window_link" class="menuItem" href="<?php echo $base_url; ?>output/assignments.php?section=admin&amp;go=judging_assignments&amp;filter=stewards&amp;view=name" title="Print Steward Assignments by Name">Steward Assignments By Last Name</a>
			<a id="modal_window_link" class="menuItem" href="<?php echo $base_url; ?>output/assignments.php?section=admin&amp;go=judging_assignments&amp;filter=stewards&amp;view=table" title="Print Steward Assignments by Table">Steward Assignments By Table</a>
   			<?php if ($totalRows_judging > 1) { ?>
			<a id="modal_window_link" class="menuItem" href="<?php echo $base_url; ?>output/assignments.php?section=admin&amp;go=judging_assignments&amp;filter=stewards&amp;view=location" title="Print Steward Assignments by Location">Steward Assignments By Location</a>
    		<?php } ?>
            
            
		</div>
	</span>
    <?php } ?>
</div>
<?php } // if ($action != "print") ?>

<?php if (($action == "default") && ($dbTable == "default")) { ?>
<div class="adminSubNavContainer">
		<span class="adminSubNav">
        	<span class="icon"><img src="<?php echo $base_url; ?>images/monitor.png"  /></span><a id="modal_window_link" href="<?php echo $base_url; ?>output/assignments.php?section=admin&amp;go=judging_assignments&amp;filter=judges&amp;view=name&amp;tb=view" title="View Assignments by Name">View All Judge Assignments By Last Name</a>
        </span>
        <span class="adminSubNav">
        	<span class="icon"><img src="<?php echo $base_url; ?>images/monitor.png"  /></span><a id="modal_window_link" href="<?php echo $base_url; ?>output/assignments.php?section=admin&amp;go=judging_assignments&amp;filter=judges&amp;view=table&amp;tb=view" title="View Assignments by Table">View All Judge Assignments By Table</a>
        </span>
</div>
<div class="adminSubNavContainer">
        <span class="adminSubNav">
        	<span class="icon"><img src="<?php echo $base_url; ?>images/monitor.png"  /></span><a id="modal_window_link" href="<?php echo $base_url; ?>output/assignments.php?section=admin&amp;go=judging_assignments&amp;filter=stewards&amp;view=name&amp;tb=view" title="View Assignments by Name">View All Steward Assignments By Last Name</a>
        </span>
        <span class="adminSubNav">
        	<span class="icon"><img src="<?php echo $base_url; ?>images/monitor.png"  /></span><a id="modal_window_link" href="<?php echo $base_url; ?>output/assignments.php?section=admin&amp;go=judging_assignments&amp;filter=stewards&amp;view=table&amp;tb=view" title="View Assignments by Table">View All Steward Assignments By Table</a>
        </span>
</div>
<table class="dataTableCompact">

<tr>
	<td><strong>Step 1: </strong>Assign Judges and Stewards</td>
	<td><span class="icon"><img src="<?php echo $base_url; ?>images/user_add.png" alt="Back"></span><a href="<?php echo $base_url; ?>index.php?section=admin&amp;action=assign&amp;go=judging&amp;filter=judges">Assign Particpants as Judges</a></td>
    <td><span class="icon"><img src="<?php echo $base_url; ?>images/user_add.png" alt="Back"></span><a href="<?php echo $base_url; ?>index.php?section=admin&amp;action=assign&amp;go=judging&amp;filter=stewards">Assign Particpants as Stewards</a></td>
</tr>

<tr>
	<td><strong>Step 2: </strong>Define All Tables</span>
	<td><span class="icon"><img src="<?php echo $base_url; ?>images/application_add.png" alt="Back"></span><a href="<?php echo $base_url; ?>index.php?section=admin&amp;go=judging_tables&amp;action=add">Add a Table</a></td>
    <?php if ($filter != "orphans") { ?>
	<td><span class="icon"><img src="<?php echo $base_url; ?>images/application_view_list.png" alt="Orphans" title="Styles Not Assigned to Tables" /></span><a href="<?php echo $base_url; ?>index.php?section=admin&amp;go=judging_tables&amp;filter=orphans">View Styles Not Assigned to Tables</a></td>
	<?php } ?>
</tr>
<tr>
	<td><strong>Step 3: </strong>Define All Flights</td>
    <td class="data" <?php if ($_SESSION['jPrefsQueued'] == "Y") echo "colspan='2'"; ?>>
	<?php if ($_SESSION['jPrefsQueued'] == "N") { ?>
    	<span class="icon"><img src="<?php echo $base_url; ?>images/application_form_add.png" alt="Define/Edit flights" title="Define/Edit flights" /></span>
<div class="menuBar"><a class="menuButton" href="#" onclick="#" onmouseover="buttonMouseover(event, 'flightsMenu_tables');">Define/Edit Flights for...</a></div>
    	<div id="flightsMenu_tables" class="menu" onmouseover="menuMouseover(event)">
    		<?php do { 
				$flight_count = table_choose($section,$go,$action,$row_tables_edit['id'],$view,"default","flight_choose");
				$flight_count = explode("^",$flight_count);
			?>
    		<a class="menuItem" href="<?php echo $base_url; ?>index.php?section=admin&amp;go=judging_flights&amp;filter=define&amp;action=<?php if ($flight_count[0] > 0) echo "edit"; else echo "add"; echo "&amp;id=".$row_tables_edit['id']; ?>"><?php echo "Table #".$row_tables_edit['tableNumber'].": ".$row_tables_edit['tableName']; ?></a>
    		<?php  
			} while ($row_tables_edit = mysql_fetch_assoc($tables_edit)); ?>
    	</div>
    <?php } else echo "Skipped. Defining flights is disabled for queued judging...less work for you!"; ?>
    </td>
    <td class="data">&nbsp;</td>
</tr>

<tr>
	<td><strong>Step 4: </strong><?php echo "Assign ".$assign_to." to Rounds"; ?></td>
    <td class="data"><span class="icon"><img src="<?php echo $base_url; ?>images/application_form_add.png" alt="<?php echo "Assign ".$assign_to." to Rounds"; ?>" /></span><a href="<?php echo $base_url; ?>index.php?section=admin&amp;go=judging_flights&amp;action=assign&amp;filter=rounds"><?php echo "Assign ".$assign_to." to Rounds"; ?></a></td>
    <td class="data">&nbsp;</td>
</tr>

<tr>
	<td><strong>Step 5: </strong>Assign Judges and Stewards to Tables</td>
    <td class="data"><span class="icon"><img src="<?php echo $base_url; ?>images/user_add.png" alt="Assign Judges/Stewards to Tables"></span><a href="<?php echo $base_url; ?>index.php?section=admin&amp;action=assign&amp;go=judging_tables">Assign Judges/Stewards to Tables</a></td>
    <td class="data">&nbsp;</td>
</tr>
<tr>
	<td><strong>Step 6: </strong>Enter or Add Scores</td>
	<td class="data">
    	<span class="icon"><img src="<?php echo $base_url; ?>images/rosette_add.png" alt="Enter/Edit scores" title="Enter/Edit scores" /></span>
		<div class="menuBar"><a class="menuButton" href="#" onclick="#" onmouseover="buttonMouseover(event, 'scoresMenu_tables');">Enter/Edit Scores for...</a></div>
    	<div id="scoresMenu_tables" class="menu" onmouseover="menuMouseover(event)">
    		<?php do { 
				$score_count = table_count_total($row_tables_edit_2['id']);
			?>
    		<a class="menuItem" href="<?php echo $base_url; ?>index.php?section=admin&amp;go=judging_scores&amp;action=<?php if ($score_count  > 0) echo "edit&amp;id=".$row_tables_edit_2['id']; else echo "add&amp;id=".$row_tables_edit_2['id']; ?>"><?php echo "Table #".$row_tables_edit_2['tableNumber'].": ".$row_tables_edit_2['tableName']; ?></a>
    		<?php } 
			while ($row_tables_edit_2 = mysql_fetch_assoc($tables_edit_2)); ?>
		</div>
	</td>
    <td class="data">
    	<span class="icon"><img src="<?php echo $base_url; ?>images/rosette.png" alt="View Scores"></span>
    	<div class="menuBar"><a class="menuButton" href="#" onclick="#" onmouseover="buttonMouseover(event, 'scoreMenu');">View Scores...</a></div>
  		<div id="scoreMenu" class="menu" onmouseover="menuMouseover(event)">
  			<a class="menuItem" href="<?php echo $base_url; ?>index.php?section=admin&go=judging_scores">By Table</a>
    		<a class="menuItem" href="<?php echo $base_url; ?>index.php?section=admin&go=judging_scores&amp;filter=category">By Category</a>
  		</div>
	</td>
</tr>
<?php if (((NHC) && ($prefix == "_final")) || (!NHC)) { ?>
<tr>
	<td><strong>Step 7: </strong>Enter or Edit BOS Entries</td>
    <td class="data">
    	<span class="icon"><img src="<?php echo $base_url; ?>images/rosette_add.png" alt="Enter/Edit scores" title="Enter/Edit scores" /></span>
		<div class="menuBar"><a class="menuButton" href="#" onclick="#" onmouseover="buttonMouseover(event, 'scoresMenu_bos_2');">Enter/Edit BOS Places For...</a></div>
		<div id="scoresMenu_bos_2" class="menu" onmouseover="menuMouseover(event)">
		<?php do { 
			if ($row_style_types['styleTypeBOS'] == "Y") { ?>
			<a class="menuItem" href="<?php echo $base_url; ?>index.php?section=admin&amp;go=judging_scores_bos&amp;action=enter&amp;filter=<?php echo $row_style_types['id'] ?>">BOS Places - <?php echo $row_style_types['styleTypeName']; ?></a>
		<?php 
			}
		} while ($row_style_types = mysql_fetch_assoc($style_types));
		?>
		</div>
    </td>
    <td class="data">
		<span class="icon"><img src="<?php echo $base_url; ?>images/rosette.png" alt="Enter/Edit scores" title="Enter/Edit scores" /></span><a href="<?php echo $base_url; ?>index.php?section=admin&amp;go=judging_scores_bos">View BOS Entries and Places</a>
    </td>
</tr>
<?php } ?>
</table>
<?php } ?>
<?php if ($action != "print") { ?>
	<?php if (($totalRows_judging > 1) && ($dbTable == "default") && ($action == "default")) { ?>
    <table class="dataTableCompact">
        <td colspan="2" class="dataLabel">Total Entries by Location</th>
        <?php do { ?>
        <tr>
            <td class="dataLeft" nowrap="nowrap" width="1%"><?php echo $row_judging['judgingLocName']." &ndash; ".getTimeZoneDateTime($_SESSION['prefsTimeZone'], $row_judging['judgingDate'], $_SESSION['prefsDateFormat'],  $_SESSION['prefsTimeFormat'], "long", "date-time"); ?></td>
            <td class="data"><?php $loc_total = get_table_info(1,"count_total","default","default",$row_judging['id']); $all_loc_total[] = $loc_total; echo $loc_total; ?></td>
         </tr>  
        <?php } while ($row_judging = mysql_fetch_assoc($judging)); ?>
        <tr>
        	<td class="dataLeft" nowrap="nowrap" width="1%">Total</td>
            <td class="data"><?php echo array_sum($all_loc_total); ?></td>
        </tr>
            
    </table>
    <?php } // end if (($totalRows_judging > 1) && ($dbTable == "default")); ?>

	<?php if (($action == "default") && ($filter == "default") && ($dbTable == "default")) { ?>
    <p>To ensure accuracy, verify that all paid and received entries have been marked as so via the <a href="<?php echo $base_url; ?>index.php?section=admin&amp;go=entries">Manage Entries</a> screen.</p>
    <table class="dataTable" style="margin-bottom: 2em;">
    <tbody>
        <tr>
            <td width="30%" nowrap="nowrap"><a href="<?php echo $base_url; ?>index.php?section=admin&amp;go=judging_preferences">Competition organization preferences</a> are set to:<br />
                <ul>
                    <li><?php if ($_SESSION['jPrefsQueued'] == "Y") echo "Queued Judging (no flights)."; else echo "Non-Queued Judging (with flights)."; ?></li>
                    <li>Maximum Rounds <?php if ($totalRows_judging > 0) echo "(per location)"; ?>: <?php echo $_SESSION['jPrefsRounds']; ?>.</li>
                    <li>Maximum BOS Places (per style type): <?php echo $_SESSION['jPrefsMaxBOS']; ?>.</li> 
                    <?php if ($_SESSION['jPrefsQueued'] == "N") { ?>
                    <li>Maximum Entries per Flight: <?php echo $_SESSION['jPrefsFlightEntries']; ?>.</li> 
                    <?php } ?>
                </ul>
            </td>
            <?php if (((NHC) && ($prefix == "_final")) || (!NHC) && ($totalRows_style_type > 0)) {
				?>
            <td>A Best of Show round is enabled for the following <a href="<?php echo $base_url; ?>index.php?section=admin&amp;go=style_types">Style Types</a>:<br />
                <ul>
                    <?php do { ?>
                    <li><?php echo $row_style_type['styleTypeName']." (".bos_method($row_style_type['styleTypeBOSMethod'])." from each table to BOS)."; ?></li>
                    <?php } while ($row_style_type = mysql_fetch_assoc($style_type)); ?>
                </ul>
            </td>
            <?php } ?>
        </tr>
    </tbody>
    </table>
    <?php } // end if (($action == "default") && ($filter == "default") && ($dbTable == "default")); ?>
<?php } // end if ($action != "print"); ?>


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
<table class="dataTable" id="sortable"> 
	<thead>
    <tr>
    	<th class="dataHeading bdr1B">#</th>
        <th class="dataHeading bdr1B">Name</th>
        <th class="dataHeading bdr1B">Style(s)</th>
        <th class="dataHeading bdr1B"><em>Rec'd</em><br />Entries</th>
        <th class="dataHeading bdr1B"><em>Scored</em><br />Entries</th>
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
    <?php do { 
	$a = array(get_table_info(1,"list",$row_tables['id'],$dbTable,"default")); 
	$styles = display_array_content($a,1);
	$received = get_table_info(1,"count_total",$row_tables['id'],$dbTable,"default");
	$scored =  get_table_info(1,"score_total",$row_tables['id'],$dbTable,"default");
	if ($received > $scored) $scored = "<span style='color:red;' title='Not all received entries have been scored!'>".$scored."</span>"; else $scored = $scored;
	$assigned_judges = assigned_judges($row_tables['id'],$dbTable,$judging_assignments_db_table);
	$assigned_stewards = assigned_stewards($row_tables['id'],$dbTable,$judging_assignments_db_table);
	?>
    <tr>
    	<td <?php if ($action == "print") echo "class='bdr1B'"; ?>width="5%"><?php echo $row_tables['tableNumber']; ?></td>
        <td class="data" width="10%"><?php echo $row_tables['tableName']; ?></td>
        <td class="data" width="15%"><?php echo $styles; ?></td>
        <td class="data" width="8%"><?php echo $received; ?></td>
        <td class="data" width="8%"><?php echo $scored; ?></td>
        <td class="data" width="8%"><?php echo $assigned_judges; ?></td>
        <td class="data" width="8%"><?php echo $assigned_stewards; ?></td>
		<?php if (($totalRows_judging > 1) && ($dbTable == "default")) { ?>
        <td class="data"><?php echo table_location($row_tables['id'],$_SESSION['prefsDateFormat'],$_SESSION['prefsTimeZone'],$_SESSION['prefsTimeFormat'],"default") ?></td>
        <?php } ?>
        <?php if (($action != "print") && ($dbTable == "default")) { ?>
        <td class="data" width="5%" nowrap="nowrap"><span class="icon"><a href="<?php echo $base_url; ?>index.php?section=admin&amp;go=<?php echo $go; ?>&amp;action=edit&amp;id=<?php echo $row_tables['id']; ?>"><img src="<?php echo $base_url; ?>images/pencil.png"  border="0" alt="Edit the <?php echo $row_tables['tableName']; ?> table" title="Edit the <?php echo $row_tables['tableName']; ?> table"></a></span><span class="icon"><a href="javascript:DelWithCon('includes/process.inc.php?section=<?php echo $section; ?>&amp;go=<?php echo $go; ?>&amp;filter=<?php echo $filter; ?>&amp;dbTable=<?php echo $judging_tables_db_table; ?>&amp;go=judging_tables&amp;action=delete','id',<?php echo $row_tables['id']; ?>,'Are you sure you want to delete the <?php echo $row_tables['tableName']; ?> table?\nALL associated FLIGHTS and SCORES will be deleted as well.\nThis cannot be undone.');"><img src="<?php echo $base_url; ?>images/bin_closed.png"  border="0" alt="Delete the <?php echo $row_tables['tableName']; ?> table" title="Delete the <?php echo $row_tables['tableName']; ?> table"></a></span><span class="icon"><a id="modal_window_link" href="<?php echo $base_url; ?>output/pullsheets.php?section=admin&amp;go=judging_tables&amp;id=<?php echo $row_tables['id']; ?>"><img src="<?php echo $base_url; ?>images/printer.png"  border="0" alt="Print the pullsheet for <?php echo $row_tables['tableName']; ?>" title="Print the pullsheet for <?php echo $row_tables['tableName']; ?>"></a></span><?php if ($_SESSION['jPrefsQueued'] == "N") { if (flight_count($row_tables['id'],1)) { ?><span class="icon"><a href="<?php echo $base_url; ?>index.php?section=admin&amp;go=judging_flights&amp;action=edit&amp;id=<?php echo $row_tables['id']; ?>"><img src="<?php echo $base_url; ?>images/application_form_edit.png" alt="Edit flights for <?php echo $row_tables['tableName']; ?>" title="Edit flights for <?php echo $row_tables['tableName']; ?>" /></a></span><?php } elseif (($totalRows_flights == 0) && ($entry_count > $_SESSION['jPrefsFlightEntries'])) { ?><span class="icon"><a href="<?php echo $base_url; ?>index.php?section=admin&amp;go=judging_flights&amp;action=add&amp;id=<?php echo $row_tables['id']; ?>"><img src="<?php echo $base_url; ?>images/application_form_add.png" alt="Define flights for <?php echo $row_tables['tableName']; ?>" title="Define flights for <?php echo $row_tables['tableName']; ?>" /></a></span><?php } else { ?><span class="icon"><img src="<?php echo $base_url; ?>images/application_form_fade.png" alt="No need to define flights for <?php echo $row_tables['tableName']; ?>" title="No need to define flights for <?php echo $row_tables['tableName']; ?>" /></span><?php } ?><?php } // end if ($_SESSION['jPrefsQueued'] == "N") ?><?php if (score_count($row_tables['id'],1)) { ?><span class="icon"><a href="<?php echo $base_url; ?>index.php?section=admin&amp;go=judging_scores&amp;action=edit&amp;id=<?php echo $row_tables['id']; ?>"><img src="<?php echo $base_url; ?>images/rosette_edit.png" alt="Edit scores for <?php echo $row_tables['tableName']; ?>" title="Edit scores for <?php echo $row_tables['tableName']; ?>" /></a></span><?php } else { ?><span class="icon"><a href="<?php echo $base_url; ?>index.php?section=admin&amp;go=judging_scores&amp;action=add&amp;id=<?php echo $row_tables['id']; ?>"><img src="<?php echo $base_url; ?>images/rosette_add.png" alt="Enter scores for <?php echo $row_tables['tableName']; ?>" title="Enter scores for <?php echo $row_tables['tableName']; ?>" /></a></span><?php } ?>
        </td>
        <?php } ?>
    </tr>
    <?php } while ($row_tables = mysql_fetch_assoc($tables)); ?>
    </tbody>
</table>
<?php } 
else echo "<p>No tables have been defined yet. <a href='index.php?section=admin&amp;go=judging_tables&amp;action=add'>Add a table?</a></p>";
} // end if ($action == "default") ?>


<?php if ($action == "add") { ?>
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
<form method="post" action="<?php echo $base_url; ?>includes/process.inc.php?section=<?php echo $section; ?>&amp;action=<?php echo $action; ?>&amp;dbTable=<?php echo $judging_tables_db_table; ?>&amp;go=<?php echo $go; ?>" name="form1" id="form1" onSubmit="return CheckRequiredFields()">
<p><input type="submit" class="button" value="Add Table"></p>
<table>
  <tbody>
  <tr>
    <td class="dataLabel">Table Name:</td>
    <td class="data"><input name="tableName" size="30" value=""><span class="required">Required</span></td>
  </tr>
  <tr>
    <td class="dataLabel">Table Number:</td>
    <td class="data">
    <?php 
	do { $a[] = $row_table_number['tableNumber']; } while ($row_table_number = mysql_fetch_assoc($table_number));
	//print_r($a);
		?>
    
    <select name="tableNumber" id="tableNumber">
    	<?php		
		for($i=1; $i<150+1; $i++) { 
		?>
    	<option value="<?php echo $i; ?>" <?php if (in_array($i,$a)) echo "DISABLED"; if (($row_table_number_last['tableNumber'] + 1) == $i) echo "SELECTED"; ?>><?php echo $i; ?></option>
        <?php } ?>
    </select>
    </td>
  </tr>
  <tr>
    <td class="dataLabel">Location:</td>
    <td class="data">
    <select name="tableLocation" id="tableLocation">
          <?php do { ?>
          <option value="<?php echo $row_judging1['id']; ?>" <?php if ($row_tables_edit['tableLocation'] == $row_judging1['id']) echo "selected"; ?>><?php echo $row_judging1['judgingLocName']." ("; echo getTimeZoneDateTime($_SESSION['prefsTimeZone'], $row_judging1['judgingDate'], $_SESSION['prefsDateFormat'],  $_SESSION['prefsTimeFormat'], "short", "date-time-no-gmt").")"; ?></option>
          <?php } while ($row_judging1 = mysql_fetch_assoc($judging1)) ?>
    </select>
    </td>
  </tr>
  <tr>
    <td class="dataLabel">Available Style(s)<br />Not Assigned to Tables:</td>
    <td class="data">
    <?php 
    if ($row['count'] > 0) { ?>
    	<table class="dataTable" id="sortable">
        	<thead>
            <tr>
            	<th class="dataHeading bdr1B" width="1%">&nbsp;</th>
                <th class="dataHeading bdr1B" width="1%">#</th>
            	<th class="dataHeading bdr1B">BJCP Style</th>
                <th class="dataHeading bdr1B">Sub-Style</th>
                <th class="dataHeading bdr1B" width="20%"><em>Received</em> Entries</th>
            </tr>
            </thead>
            <tbody>
        	<?php do { ?>
            <?php 
			$received_entry_count_style = get_table_info($row_styles['brewStyle'],"count","default",$dbTable,"default");
			if ($received_entry_count_style > 0) { 
			//if (in_array($row_styles['brewStyle'],$with_received_entries)) {
			if (!get_table_info($row_styles['id'],"styles","default",$dbTable,"default")) {
			?>
            <tr>
            	<td><input type="checkbox" name="tableStyles[]" value="<?php echo $row_styles['id']; ?>" <?php //if (get_table_info($row_styles['id'],"styles","default",$dbTable,"default")) echo "disabled"; ?>></td>
                <td><?php echo $row_styles['brewStyleGroup'].$row_styles['brewStyleNum']; ?></td>
                <td class="data"><?php echo style_convert($row_styles['brewStyleGroup'],"1"); ?>
                <td class="data"><?php echo $row_styles['brewStyle']; //.get_table_info($row_styles['id'],"assigned","default",$dbTable,"default"); ?></td>
                <td class="data" style="text-align:right;"><?php echo $received_entry_count_style; ?></td>
            </tr>
            <?php } } ?>
            <?php } while ($row_styles = mysql_fetch_assoc($styles)); ?>
        	</tbody>
        </table>
    <?php } else echo "There are no entries."; ?>
    </td>
  </tr>
  </tbody>
</table>
<p><input type="submit" class="button" value="Add Table"></p>
<input type="hidden" name="relocate" value="<?php echo relocate($_SERVER['HTTP_REFERER'],"default",$msg,$id); ?>">
</form>
<?php } // end if ($action == "add") ?>




<?php if ($action == "edit") { ?>
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
<form method="post" action="<?php echo $base_url; ?>includes/process.inc.php?section=<?php echo $section; ?>&amp;action=<?php echo $action; ?>&amp;dbTable=<?php echo $judging_tables_db_table; ?>&amp;go=<?php echo $go."&amp;id=".$row_tables_edit['id']; ?>" name="form1" id="form1" onSubmit="return CheckRequiredFields()">
<p><input type="submit" class="button" value="Update Table"></p>
<table>
  <tbody>
  <tr>
    <td class="dataLabel">Table Name:</td>
    <td class="data"><input name="tableName" size="30" value="<?php echo $row_tables_edit['tableName']; ?>"><span class="required">Required</span></td>
  </tr>
  <tr>
    <td class="dataLabel">Table Number:</td>
    <td class="data">
    <?php 
	
	do { $a[] = $row_table_number['tableNumber']; } while ($row_table_number = mysql_fetch_assoc($table_number));
	//print_r($a);
		?>
    
    <select name="tableNumber" id="tableNumber">
    	<?php for($i=1; $i<150+1; $i++) { ?>
    	<option value="<?php echo $i; ?>" <?php if ($row_tables_edit['tableNumber'] == $i) echo "selected"; elseif (in_array($i,$a)) echo "disabled"; ?>><?php echo $i; ?></option>
        <?php } ?>
    </select>
    </td>
  </tr>
  <tr>
    <td class="dataLabel">Location:</td>
    <td class="data">
    <select name="tableLocation" id="tableLocation">
          <?php do { ?>
          <option value="<?php echo $row_judging1['id']; ?>" <?php if ($row_tables_edit['tableLocation'] == $row_judging1['id']) echo "selected"; ?>><?php echo $row_judging1['judgingLocName']." ("; echo getTimeZoneDateTime($_SESSION['prefsTimeZone'], $row_judging1['judgingDate'], $_SESSION['prefsDateFormat'],  $_SESSION['prefsTimeFormat'], "short", "date-time-no-gmt").")"; ?></option>
          <?php } while ($row_judging1 = mysql_fetch_assoc($judging1)) ?>
    </select>
    </td>
  </tr>
  <tr>
    <td class="dataLabel">Style(s):</td>
    <td class="data">
    <?php if ($row['count'] > 0) { ?>
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
            	<td><input type="checkbox" name="tableStyles[]" value="<?php echo $row_styles['id']; ?>" <?php if (get_table_info($row_styles['id'],"styles",$row_tables_edit['id'],$dbTable,"default")) echo "checked "; elseif (get_table_info($row_styles['id'],"styles","default",$dbTable,"default")) echo "disabled"; else echo ""; ?>></td>
                <td><?php echo $row_styles['brewStyleGroup'].$row_styles['brewStyleNum']; ?></td>
                <td class="data"><?php echo style_convert($row_styles['brewStyleGroup'],"1"); ?></td>
                <td class="data"><?php echo $row_styles['brewStyle'].get_table_info($row_styles['id'],"assigned","default",$dbTable,"default"); ?></td>
                <td class="data" style="text-align:right;"><?php echo get_table_info($row_styles['brewStyle'],"count","default",$dbTable,"default"); ?></td>
            </tr>
            <?php } ?>
            <?php } while ($row_styles = mysql_fetch_assoc($styles)); ?>
        	</tbody>
        </table>
    <?php } else echo "There are no entries."; ?>
    </td>
  </tr>
  </tbody>
</table>
<p><input type="submit" class="button" value="Update Table"></p>
<input type="hidden" name="relocate" value="<?php echo relocate($_SERVER['HTTP_REFERER'],"default",$msg,$id); ?>">
</form>
<?php } // end if ($action == "edit") ?>


<?php if (($action == "default") && ($filter == "orphans")) { ?>
<h3>Style Categories with Entries Not Assigned to Tables</h3>
<?php 
	if ($totalRows_tables > 0) {
		
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
	
	} // end if ($totalRows_tables > 0)
	
	else {
		echo "<p>No tables have been defined.";
		if ($go == "judging_tables") echo " <a href='index.php?section=admin&amp;go=judging_tables&amp;action=add'>Add a table?</a></p>";
	} // end else
	
}// end if (($action == "default") && ($filter == "orphans"))
?>


<?php if (($action == "assign") && ($filter == "default")) { ?>
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