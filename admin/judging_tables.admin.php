<h2><?php 
if ($action == "edit") echo "Edit Table"; 
elseif ($action == "add") echo "Add a Table"; 
else echo "Tables"; 
if ($dbTable != "default") echo ": ".ltrim($dbTable, "brewer_"); ?></h2>
<?php if ($action != "print") { ?>
<p>To ensure accuracy, verify that all paid and received entries have been marked as so via the <a href="index.php?section=admin&amp;go=entries">Manage Entries</a> screen.</p>
<?php if ($row_judging_prefs['jPrefsQueued'] == "N") { ?>
<p><a href="index.php?section=admin&amp;go=judging_preferences">Competition organization preferences</a> are set to maximum <?php echo $row_judging_prefs['jPrefsFlightEntries']; ?> entries per flight.</p>
<?php } ?>
<table class="dataTable" style="margin: 0 0 20px 0;">
<tbody>
<tr>
  	<td class="dataList" width="5%" nowrap="nowrap"><span class="icon"><img src="images/arrow_left.png" alt="Back"></span><?php if ($action == "default") { ?><a class="data" href="index.php?section=admin">Back to Admin</a><?php } else { ?><a class="data" href="index.php?section=admin&amp;go=judging_tables">Back to Tables List</a><?php } ?></td> 
  	<?php if (($action == "default") || ($action == "edit")) { ?>
  	<td class="dataList" <? if ($action == "default") echo "width='5%' nowrap='nowrap'"; ?>><span class="icon"><img src="images/application_add.png" alt="Back"></span><a class="data" href="index.php?section=admin&amp;go=judging_tables&amp;action=add">Add a Table</a></td>
    <?php } ?>
    <?php if ($action == "default") { ?>
    <td class="dataList" width="5%" nowrap="nowrap">
  	<span class="icon"><img src="images/printer.png" alt="Print" title="Print..." /></span>
  	<div class="menuBar"><a class="menuButton" href="#" onclick="#" onmouseover="buttonMouseover(event, 'printMenu_tables');">Print...</a></div>
  	<div id="printMenu_tables" class="menu" onmouseover="menuMouseover(event)">
  	<a class="menuItem thickbox" href="print.php?section=admin&amp;go=judging_tables&amp;action=print&amp;KeepThis=true&amp;TB_iframe=true&amp;height=425&amp;width=700">Tables List</a>
    <a class="menuItem thickbox" href="pullsheets.php?section=admin&amp;go=judging_tables&amp;id=default&amp;KeepThis=true&amp;TB_iframe=true&amp;height=425&amp;width=700">Pullsheets by Table</a>
  	</div>
    </td>
    <?php if (($totalRows_tables > 0) && ($row_judging_prefs['jPrefsQueued'] == "N")) { ?>
    <td class="dataList" width="5%" nowrap="nowrap">
    <img src="images/application_form.png" alt="Define/Edit flights" title="Define/Edit flights" />
    <div class="menuBar"><a class="menuButton" href="#" onclick="#" onmouseover="buttonMouseover(event, 'flightsMenu_tables');">Define/Edit Flights for...</a></div>
    <div id="flightsMenu_tables" class="menu" onmouseover="menuMouseover(event)">
    <?php do { 
	$query_flights_2 = sprintf("SELECT COUNT(*) as 'count' FROM judging_flights WHERE flightTable='%s'", $row_tables_edit['id']);
	$flights_2 = mysql_query($query_flights_2, $brewing) or die(mysql_error());
	$row_flights_2 = mysql_fetch_assoc($flights_2);
	$totalRows_flights_2 = $row_flights_2['count'];
	$entry_count = get_table_info(1,"count_total",$row_tables_edit['id']);
	
	if ($entry_count > $row_judging_prefs['jPrefsFlightEntries']) { 
	?>
    <a class="menuItem" href="index.php?section=admin&amp;go=judging_flights&amp;action=<?php if ($totalRows_flights_2 > 0) echo "edit"; else echo "add"; echo "&amp;id=".$row_tables_edit['id']; ?>"><?php echo "Table #".$row_tables_edit['tableNumber'].": ".$row_tables_edit['tableName']; ?></a>
    <?php }
 	mysql_free_result($flights_2);
	} while ($row_tables_edit = mysql_fetch_assoc($tables_edit)); 
	?>
    </div>
    </td>
    <?php } // end if (($totalRows_tables > 0) && ($row_judging_prefs['jPrefsQueued'] == "N")) ?>
    <?php if ($totalRows_tables > 0) { ?>
    <td class="dataList">
    <img src="images/rosette.png" alt="Enter/Edit scores" title="Enter/Edit scores" />
    <div class="menuBar"><a class="menuButton" href="#" onclick="#" onmouseover="buttonMouseover(event, 'scoresMenu_tables');">Enter/Edit Scores for...</a></div>
    <div id="scoresMenu_tables" class="menu" onmouseover="menuMouseover(event)">
    <?php do { 
	$query_scores_2 = sprintf("SELECT COUNT(*) as 'count' FROM judging_scores WHERE scoreTable='%s'", $row_tables_edit_2['id']);
	$scores_2 = mysql_query($query_scores_2, $brewing) or die(mysql_error());
	$row_scores_2 = mysql_fetch_assoc($scores_2);
	$totalRows_scores_2 = $row_scores_2['count'];
	?>
    <a class="menuItem" href="index.php?section=admin&amp;go=judging_scores&amp;action=<?php if ($totalRows_scores_2  > 0) echo "edit&amp;id=".$row_tables_edit_2['id']; else echo "add&amp;id=".$row_tables_edit_2['id']; ?>"><?php echo "Table #".$row_tables_edit_2['tableNumber'].": ".$row_tables_edit_2['tableName']; ?></a>
    <?php 
	mysql_free_result($scores_2);
	} while ($row_tables_edit_2 = mysql_fetch_assoc($tables_edit_2)); 
	?>
    </div>
  	</td>
    <?php } // end if ($totalRows_tables > 0) ?>
  <?php } // end if ($action == "default")?>
</tr>
<tr>
	
</tr>
</tbody>
</table>
<?php } ?>
<?php if (($action == "default") || ($action == "print") && ($totalRows_tables > 0)) { ?>
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
				<?php if ($totalRows_judging > 1) { ?>
				null,
				<?php } ?>
				<?php if ($action != "print") { ?>
				{ "asSorting": [  ] },
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
        <?php if ($totalRows_judging > 1) { ?>
        <th class="dataHeading bdr1B">Location</th>
        <?php } ?>
        <?php if ($action != "print") { ?>
        <th class="dataHeading bdr1B">Actions</th>
        <?php } ?>
    </tr>
    </thead>
    <tbody>
    <?php do { 
	$query_location_3 = sprintf("SELECT * FROM judging WHERE id='%s'", $row_tables['tableLocation']);
	$location_3 = mysql_query($query_location_3, $brewing) or die(mysql_error());
	$row_location_3 = mysql_fetch_assoc($location_3);
	
	$query_flights_3 = sprintf("SELECT COUNT(*) as 'count' FROM judging_flights WHERE flightTable='%s'", $row_tables['id']);
	$flights_3 = mysql_query($query_flights_3, $brewing) or die(mysql_error());
	$row_flights_3 = mysql_fetch_assoc($flights_3);
	$totalRows_flights_3 = $row_flights_3['count'];
	
	$query_scores_3 = sprintf("SELECT COUNT(*) as 'count' FROM judging_scores WHERE scoreTable='%s'", $row_tables['id']);
	$scores_3 = mysql_query($query_scores_3, $brewing) or die(mysql_error());
	$row_scores_3 = mysql_fetch_assoc($scores_3);
	$totalRows_scores_3 = $row_scores_3["count"];
	?>
    <tr>
    	<td <?php if ($action == "print") echo "class='bdr1B'"; ?>width="5%"><?php echo $row_tables['tableNumber']; ?></td>
        <td class="data<?php if ($action == "print") echo " bdr1B"; ?>" width="20%"><?php echo $row_tables['tableName']; ?></td>
        <td class="data<?php if ($action == "print") echo " bdr1B"; ?>" width="20%"><?php $a = array(get_table_info(1,"list",$row_tables['id'])); echo displayArrayContent($a); ?></td>
        <td class="data<?php if ($action == "print") echo " bdr1B"; ?>" width="10%"><?php $entry_count = get_table_info(1,"count_total",$row_tables['id']); echo $entry_count; ?></td>
        <?php if ($totalRows_judging > 1) { ?>
        <td class="data<?php if ($action == "print") echo " bdr1B"; ?>"><?php echo $row_location_3['judgingLocName'].", ".dateconvert($row_location_3['judgingDate'], 3)." - ".$row_location_3['judgingTime']; ?></td>
        <?php } ?>
        <?php if ($action != "print") { ?>
        <td class="data<?php if ($action == "print") echo " bdr1B"; ?>" width="5%" nowrap="nowrap"><span class="icon"><a href="index.php?section=admin&amp;go=<?php echo $go; ?>&amp;action=edit&amp;id=<?php echo $row_tables['id']; ?>"><img src="images/pencil.png"  border="0" alt="Edit the <?php echo $row_tables['tableName']; ?> table" title="Edit the <?php echo $row_tables['tableName']; ?> table"></a></span><span class="icon"><a href="javascript:DelWithCon('includes/process.inc.php?section=<?php echo $section; ?>&amp;go=<?php echo $go; ?>&amp;filter=<?php echo $filter; ?>&amp;dbTable=judging_tables&amp;action=delete','id',<?php echo $row_tables['id']; ?>,'Are you sure you want to delete the <?php echo $row_tables['tableName']; ?> table? This cannot be undone.');"><img src="images/bin_closed.png"  border="0" alt="Delete the <?php echo $row_tables['tableName']; ?> table" title="Delete the <?php echo $row_tables['tableName']; ?> table"></a></span><span class="icon"><a class="thickbox" href="pullsheets.php?section=admin&amp;go=judging_tables&amp;id=<?php echo $row_tables['id']; ?>&amp;KeepThis=true&amp;TB_iframe=true&amp;height=425&amp;width=700"><img src="images/printer.png"  border="0" alt="Print the pullsheet for <?php echo $row_tables['tableName']; ?>" title="Print the pullsheet for <?php echo $row_tables['tableName']; ?>"></a></span><?php if ($row_judging_prefs['jPrefsQueued'] == "N") { ?><?php if ($totalRows_flights_3 > 0) { ?><span class="icon"><a href="index.php?section=admin&amp;go=judging_flights&amp;action=edit&amp;id=<?php echo $row_tables['id']; ?>"><img src="images/application_form_edit.png" alt="Edit flights for <?php echo $row_tables['tableName']; ?>" title="Edit flights for <?php echo $row_tables['tableName']; ?>" /></a></span><?php } elseif (($totalRows_flights == 0) && ($entry_count > $row_judging_prefs['jPrefsFlightEntries'])) { ?><span class="icon"><a href="index.php?section=admin&amp;go=judging_flights&amp;action=add&amp;id=<?php echo $row_tables['id']; ?>"><img src="images/application_form_add.png" alt="Define flights for <?php echo $row_tables['tableName']; ?>" title="Define flights for <?php echo $row_tables['tableName']; ?>" /></a></span><?php } else { ?><span class="icon"><img src="images/application_form_fade.png" alt="No need to define flights for <?php echo $row_tables['tableName']; ?>" title="No need to define flights for <?php echo $row_tables['tableName']; ?>" /></span><?php } ?><?php } // end if ($row_judging_prefs['jPrefsQueued'] == "N") ?><?php if ($totalRows_scores_3 > 0) { ?><span class="icon"><a href="index.php?section=admin&amp;go=judging_scores&amp;action=edit&amp;id=<?php echo $row_tables['id']; ?>"><img src="images/rosette_edit.png" alt="Edit scores for <?php echo $row_tables['tableName']; ?>" title="Edit scores for <?php echo $row_tables['tableName']; ?>" /></a></span><?php } else { ?><span class="icon"><a href="index.php?section=admin&amp;go=judging_scores&amp;action=add&amp;id=<?php echo $row_tables['id']; ?>"><img src="images/rosette_add.png" alt="Enter scores for <?php echo $row_tables['tableName']; ?>" title="Enter scores for <?php echo $row_tables['tableName']; ?>" /></a></span><?php } ?>
        </td>
        <?php } ?>
    </tr>
    <?php
	mysql_free_result($location_3);
	mysql_free_result($flights_3);
	mysql_free_result($scores_3);
	} while ($row_tables = mysql_fetch_assoc($tables)); ?>
    </tbody>
</table>
<?php } // end if ($action == "default") ?>

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
				null,
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
    <td class="data"><input name="tableNumber" size="5" value="<?php if ($action == "edit") echo $row_tables_edit['tableNumber']; ?>"></td>
  </tr>
  <?php if ($totalRows_judging1 > 1) { ?>
  <tr>
    <td class="dataLabel">Location:</td>
    <td class="data">
    <select name="tableLocation" id="tableLocation">
          <option value=""></option>
          <?php do { ?>
          <option value="<?php echo $row_judging1['id']; ?>" <?php if ($row_tables_edit['tableLocation'] == $row_judging1['id']) echo "selected"; ?>><?php echo $row_judging1['judgingLocName']." ("; echo dateconvert($row_judging1['judgingDate'], 3).")"; ?></option>
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
            <?php if (get_table_info($row_styles['brewStyle'],"count","")) { ?>
            <tr>
            	<td><input name="tableStyles[]" type="checkbox" value="<?php echo $row_styles['id']; ?>" <?php if ($action == "edit") { if (get_table_info($row_styles['id'],"styles",$id)) echo "checked "; elseif (get_table_info($row_styles['id'],"styles","default")) echo "disabled"; else echo ""; }  if ($action == "add") { if (get_table_info($row_styles['id'],"styles","default")) echo "disabled"; } ?>></td>
                <td><?php echo $row_styles['brewStyleGroup'].$row_styles['brewStyleNum']; ?></td>
                <td class="data"><?php echo style_convert($row_styles['brewStyleGroup'],"1"); ?>
                <td class="data"><?php echo $row_styles['brewStyle'].get_table_info($row_styles['id'],"assigned","default"); ?></td>
                <td class="data" style="text-align:right;"><?php echo get_table_info($row_styles['brewStyle'],"count","default"); ?></td>
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
<input type="hidden" name="relocate" value="<?php echo relocate($_SERVER['HTTP_REFERER']); ?>">
</form>
<?php } // end if (($action == "add") || ($action == "edit")) 
mysql_free_result($styles);
mysql_free_result($tables);
mysql_free_result($tables_edit);
?>