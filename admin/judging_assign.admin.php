<?php 

/**
 * Module:      participants.admin.php
 * Description: This module houses all participant (brewer) related functionality
 *              including viewing a participant list, add/edit/delete, 
 *              Also provids judging location related functions - add, edit, delete.
 *
 */

/* ---------------- Rebuild Info ---------------------

Beginning with the 1.3.0 release, an effort was begun to separate the programming
layer from the presentation layer for all scripts with this header.

All Admin pages have certain variables in common that build the page:
     
  $output_datatables_head = the output for DataTables placed in the <thead> tag
  $output_datatables_body = the output for DataTables placed in the <tbody> tag
  $output_datatables_add_link = the link to add a record
  $output_datatables_edit_link = the link to edit the record
  $output_datatables_delete_link = the link to delete the record
  $output_datatables_print_link = the link to print the record or output to print
  $output_datatables_other_link = misc use link
  $output_datatables_view_link = the link to view the record's detail
  $output_datatables_actions = compiles all of the "actions" links (edit, delete, print, view, etc.)
  
  $output_datatables_head = "";
  $output_datatables_body = "";
  $output_datatables_add_link = "";
  $output_datatables_edit_link = "";
  $output_datatables_delete_link = "";
  $output_datatables_print_link = "";
  $output_datatables_other_link = "";
  $output_datatables_view_link = "";
  $output_datatables_actions = "";
  
  ADD/EDIT SCREENS VARIABLE
  $output_add_edit = whether to run/display the add/edit functions - default is FALSE

 * ---------------- END Rebuild Info --------------------- */
 
$queued = $_SESSION['jPrefsQueued'];
$location = $row_tables_edit['tableLocation'];

include (DB.'admin_judging_assign.db.php');

 // --------------- END Custom Functions --------------------- //

$output_datatables_head = "";
$output_datatables_body = "";
$output_datatables_add_link = "";
$output_datatables_edit_link = "";
$output_datatables_delete_link = "";
$output_datatables_print_link = "";
$output_datatables_other_link = "";
$output_datatables_view_link = "";
$output_datatables_actions = "";
$output_available_modal_body = "";
$output_at_table_modal_body = "";
$ranked = 0;
$nonranked = 0;
$ranked_judge = "";
$nonranked_judge = "";


// Build DataTables Header
$output_datatables_head .= "<tr>";
$output_datatables_head .= "<th>Name</th>";
if ($filter == "judges") { 
	$output_datatables_head .= "<th>BJCP Rank</th>";
	$output_datatables_head .= "<th class=\"hidden-xs hidden-sm hidden-md\">BJCP #</th>";
	$output_datatables_head .= "<th>Comps Judged</th>";
	// $output_datatables_head .= "<th>Judge Role(s) <a href=\"#\" role=\"button\" data-toggle=\"modal\" data-target=\"#rolesModal\"><span class=\"fa fa-lg fa-question-circle\"></span></a></th>";
}
for($i=1; $i<$row_flights['flightRound']+1; $i++) {
		$output_datatables_head .= "<th>Round ".$i."</th>";
}

$output_datatables_head .= "</tr>";

do {
	$table_location = "Y-".$row_tables_edit['tableLocation'];
	$judge_info = judge_info($row_brewer['uid']);
	$judge_info = explode("^",$judge_info);
	$bjcp_rank = explode(",",$judge_info[5]);
	$display_rank = bjcp_rank($bjcp_rank[0],1);	
	
	$assign_row_color = "";
	$flights_display = "";
	$assign_flag = "";
	
	for($i=1; $i<$row_flights['flightRound']+1; $i++) {
		
		// Get role from judging_assignments table
		$query_judge_roles = sprintf("SELECT assignRoles FROM %s WHERE (bid='%s' AND assignTable='%s' AND assignRound='%s')", $prefix."judging_assignments", $row_brewer['uid'], $row_tables_edit['id'], $i);
		$judge_roles = mysqli_query($connection,$query_judge_roles) or die (mysqli_error($connection));
		$row_judge_roles = mysqli_fetch_assoc($judge_roles);
		
		$checked_head_judge = "";
		$checked_lead_judge = "";
		$checked_minibos_judge = "";
		$roles_previously_defined = 0;
		
		/*
		if (!empty($row_judge_roles['assignRoles'])) {
			$roles_previously_defined = 1;
		}
		
		if (strpos($row_judge_roles['assignRoles'],"HJ") !== FALSE) { 
			$checked_head_judge = "CHECKED";
		}
		
		if (strpos($row_judge_roles['assignRoles'],"LJ") !== FALSE) {
			$checked_lead_judge = "CHECKED";
		}
		
		if (strpos($row_judge_roles['assignRoles'],"MBOS") !== FALSE) {
			$checked_minibos_judge = "CHECKED";
		}
		*/
		
		if  (table_round($row_tables_edit['id'],$i)) {
			
			$flights_display .= "<td>";
			
			if (at_table($row_brewer['uid'],$row_tables_edit['id'])) {
				$assign_row_color = "bg-orange text-orange";
				$assign_flag = "<span class=\"fa fa-lg fa-check\"></span> <strong>Assigned.</strong> Participant is assigned to this table.";
				$rank_number = filter_var($display_rank,FILTER_SANITIZE_NUMBER_FLOAT);
				//$rank_number = preg_replace("/[^0-9,.]/", "", $display_rank);
				if ($rank_number >= 2) {
					$ranked_judge[] = 1;
					$nonranked_judge[] = 0;
				}
				else {
					$ranked_judge[] = 0;
					$nonranked_judge[] = 1;
				}
			}
			
			else {
				$judge_alert = judge_alert($i,$row_brewer['uid'],$row_tables_edit['id'],$location,$judge_info[2],$judge_info[3],$row_tables_edit['tableStyles'],$row_tables_edit['id']);
				$judge_alert = explode("|",$judge_alert);
				$assign_row_color = $judge_alert[0];
				$assign_flag = "<div>".$judge_alert[1]."</div>";
			}
			
			$random = random_generator(8,2);
			
			$unavailable = unavailable($row_brewer['uid'],$row_tables_edit['tableLocation'],$i,$row_tables_edit['id']);
			$flights_display .= $assign_flag;
			$flights_display .= assign_to_table($row_tables_edit['id'],$row_brewer['uid'],$filter,$total_flights,$i,$location,$row_tables_edit['tableStyles'],$queued,$random);
			$flights_display .= "</td>";
			
		}
		
	}
	
	
	if ($judge_info[4] == "Y") $display_rank .= "<br /><em>Certified Mead Judge</em>";
	 
	if (!empty($bjcp_rank[1])) {
		$display_rank .= "<em>".designations($judge_info[5],$bjcp_rank[0])."</em>";
	}

	if ($filter == "stewards") $locations = explode(",",$judge_info[7]); 
	else $locations = explode(",",$judge_info[8]);
	
	if (in_array($table_location,$locations)) {
		
		$output_datatables_body .= "<tr class=\"".$assign_row_color."\">\n";
		
		$output_datatables_body .= "<td nowrap>";
		$output_datatables_body .= "<a href=\"".$base_url."index.php?section=brewer&amp;go=admin&amp;action=edit&amp;filter=".$row_brewer['uid']."&amp;id=".$judge_info[11]."\" data-toggle=\"tooltip\" title=\"Edit ".$judge_info[0]." ".$judge_info[1]."&rsquo;s account info\">".$judge_info[1].", ".$judge_info[0]."</a>"; 
		$output_datatables_body .= "</td>";
		
		if ($filter == "judges") { 
			$output_datatables_body .= "<td>".$display_rank."</td>";
			$output_datatables_body .= "<td class=\"hidden-xs hidden-sm hidden-md\">";
			if (($judge_info[6] != "") && ($judge_info[6] != "0")) $output_datatables_body .= strtoupper($judge_info[6]); 
			else $output_datatables_body .= "N/A";
			if (!empty($judge_info[10])) $output_datatables_body .= "<br><strong>Judge&rsquo;s Notes to Organizers:</strong> <em>".$judge_info[10]."</em>";
			$output_datatables_body .= "</td>";
			$output_datatables_body .= "<td>".$judge_info[9]."</td>";
			/*
			$output_datatables_body .= "<td>";
			$output_datatables_body .= "<input type=\"hidden\" name=\"rolesPrevDefined".$random."\" value=\"".$roles_previously_defined."\">";
			$output_datatables_body .= "<div class=\"checkbox\"><label><input name=\"head_judge".$random."\" type=\"checkbox\" value=\"HJ\" ".$checked_head_judge." /> Head Judge</label></div><br>";
			$output_datatables_body .= "<div class=\"checkbox\"><label><input name=\"lead_judge".$random."\" type=\"checkbox\" value=\"LJ\" ".$checked_lead_judge." /> Lead Judge</label></div><br>";
			$output_datatables_body .= "<div class=\"checkbox\"><label><input name=\"minibos_judge".$random."\" type=\"checkbox\" value=\"MBOS\" ".$checked_minibos_judge." /> Mini-BOS Judge</label></div><br>";
			$output_datatables_body .= "</td>";
			*/
		}
		
		$modal_rank = $bjcp_rank[0];
		if (empty($modal_rank)) $modal_rank = "Non-BJCP";
		
		$at_table = at_table($row_brewer['uid'],$row_tables_edit['id']);
		
		// Build Assigned Modal
		if (($at_table) && ($unavailable)) {
			
			$output_at_table_modal_body .= "<tr>\n";
			$output_at_table_modal_body .= "<td class=\"small\">".$judge_info[1].", ".$judge_info[0]."</td>";
			$output_at_table_modal_body .= "<td class=\"small\">".$modal_rank."</td>";
			if ($_SESSION['jPrefsQueued'] == "N") $output_at_table_modal_body .= "<td class=\"small\">".$judge_info[12]."</td>";
			if ($_SESSION['jPrefsQueued'] == "N") $output_at_table_modal_body .= "<td class=\"small\">".$judge_info[13]."</td>";
			$output_at_table_modal_body .= "</tr>\n";
			
		}
		
		// Build Available Modal
		if ((!$at_table) && (!$unavailable)) $output_available_modal_body .= "<tr><td class=\"small\">".$judge_info[1].", ".$judge_info[0]."</td><td class=\"small\">".$modal_rank."</td></tr>";

		$output_datatables_body .= $flights_display;
		$output_datatables_body .= "</tr>\n";
		
	}
} while ($row_brewer = mysqli_fetch_assoc($brewer)); 
if (is_array($ranked_judge)) $ranked = array_sum($ranked_judge); else $ranked = $ranked; 
if (is_array($nonranked_judge)) $nonranked = array_sum($nonranked_judge); else $nonranked = $nonranked; 
?>
<div class="bcoem-admin-element hidden-print">
	<div class="btn-group" role="group" aria-label="modals">
        <!-- Input Here -->
        <select class="selectpicker" name="assign_table" id="assign_table" onchange="jumpMenu('self',this,0)" data-width="auto">
        <option value="" disabled selected>Assign <?php if ($filter == "stewards") echo "Stewards"; else echo "Judges"; ?> to Another Table...</option>
            <?php do { ?>
				<option value="index.php?section=admin&amp;action=assign&amp;go=judging_tables&amp;filter=<?php echo $filter; ?>&amp;id=<?php echo $row_tables['id']; ?>"><?php echo "Table ".$row_tables['tableNumber'].": ".$row_tables['tableName']; ?></option>
    		<?php } while ($row_tables = mysqli_fetch_assoc($tables)); ?>
        </select>
    </div>
    <div class="btn-group" role="group" aria-label="modals">
    		<?php if (!empty($output_available_modal_body)) { ?>
            <button type="button" class="btn btn-info" data-toggle="modal" data-target="#availModal">
              Available <span class="text-capitalize"><?php echo $filter; ?></span>
            </button>
            <?php } ?>
        	<?php if (!empty($output_at_table_modal_body)) { ?>
            <button type="button" class="btn btn-info" data-toggle="modal" data-target="#atTableModal">
              <span class="text-capitalize"><?php echo $filter; ?> Assigned to this Table</span>
            </button>
            <?php } ?>
     </div>   
</div>
<p>Make sure you have <a href="<?php echo $base_url; ?>index.php?section=admin&go=judging_flights&action=assign&filter=rounds">assigned all tables <?php if ($_SESSION['jPrefsQueued'] == "N") echo "and flights"; ?> to rounds</a> <em>before</em> assigning <?php echo $filter; ?> to a table.</p>
<?php if ($totalRows_judging > 1) { ?>
<p>If no <?php echo $filter; ?> are listed below, there are two possibilities:</p>
<ol>	
	<li>No <?php echo $filter; ?> have been assigned to the pool via the <a href="<?php echo $base_url; ?>index.php?section=admin&amp;go=judging&amp;action=assign&amp;filter=<?php echo $filter; ?>">assign participants as <?php echo $filter; ?></a> screen.</li>
	<li>No <?php echo rtrim($filter,"s"); ?> indicated that they are available for this table's location. To make <?php echo $filter; ?> available, you will need to edit their preferences via the <a href="<?php echo $base_url; ?>index.php?section=admin&amp;go=participants">participants list</a>.</li>
</ol>
<?php } ?>

<h4>Assign <span class="text-capitalize"><?php echo $filter; ?></span> to Table <?php echo $row_tables_edit['tableNumber']." &ndash; ".$row_tables_edit['tableName']; $entry_count = get_table_info(1,"count_total",$row_tables_edit['id'],$dbTable,"default"); echo " (".$entry_count." entries)"; ?> <small><?php echo table_location($row_tables_edit['id'],$_SESSION['prefsDateFormat'],$_SESSION['prefsTimeZone'],$_SESSION['prefsTimeFormat'],"default"); ?></small></h4>
<?php if ((!empty($output_at_table_modal_body)) && ($filter == "judges"))  { ?>
<p>Currently, there are <?php echo $ranked; ?> ranked judges and <?php echo $nonranked; ?> non-ranked judges at this table.</p>
<?php } ?>
<p><strong>Number of Flights:</strong> <?php echo $row_flights['flightNumber']; ?>
<?php if ($_SESSION['jPrefsQueued'] == "N") { ?>
<ul class="list-unstyled">
	<?php for($c=1; $c<$row_flights['flightNumber']+1; $c++) { 
	$flight_entry_count = flight_entry_count($row_tables_edit['id'], $c);
	?>
	<li><?php echo "<strong>Flight ".$c.":</strong> ".$flight_entry_count." entries"; ?></li>
    <?php } ?>
</ul>
<?php } ?>
</p>
<?php if ($row_rounds['flightRound'] != "") { ?>	
<script type="text/javascript" language="javascript">
	 $(document).ready(function() {
		$('#sortable').dataTable( {
			"bPaginate" : true,
			"sPaginationType" : "full_numbers",
			"bLengthChange" : true,
			"iDisplayLength" : <?php echo round($_SESSION['prefsRecordPaging']); ?>,
			"sDom": 'rtp',
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
				null,
				<?php if ($_SESSION['jPrefsQueued'] == "N") { ?>,
				null,
				null
				<?php } ?>
				]
			} );
		} );
	</script>
<?php if (!empty($output_available_modal_body)) { ?>
<script type="text/javascript" language="javascript">
 $(document).ready(function() {
	$('#sortable3').dataTable( {
		"bPaginate" : false,
		"sDom": 'rt',
		"bStateSave" : false,
		"bLengthChange" : false,
		"aaSorting": [[0,'asc']],
		"bProcessing" : false,
		"aoColumns": [
			null,
			null				]
		} );
	} );
</script>
<?php if ($filter == "judges") { ?>
<!-- Judge Roles Modal -->
<!-- Modal -->
<div class="modal fade" id="rolesModal" tabindex="-1" role="dialog" aria-labelledby="rolesModalLabel">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header bcoem-admin-modal">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="rolesModalLabel">Judge Roles</span> </h4>
            </div>
            <div class="modal-body">
                <p>Use the checkboxes to designate the role of the judge at the table.</p>
            	<p><strong>Head Judge</strong> - <a href="http://www.bjcp.org/judgeprocman.php" target="_blank">According to the BJCP</a>, the head judge is a single judge responsible for reviewing all scores and paperwork for accuracy:</p>
                <p><small><em>&ldquo;Once judging is complete, the head judge is responsible for ensuring that scoresheets, cover sheets, and flight summary sheets are filled out and turned in to the judge director or competition organizer as directed by the competition management.&rdquo;</em></small></p>
              	<p><strong>Lead Judge</strong> - the Lead Judge is a role that defines the ranking judge in a judge pair.</p>
                <p><strong>Mini-BOS Judge</strong> - the Mini-BOS Judge is one of the judges at the table designated to participate in the <a href="http://www.bjcp.org/docs/MiniBOS.pdf" target="_blank">Mini-BOS</a> to determine placing entries.</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div><!-- ./modal -->
<?php } ?>
<!-- Available Judges Modal -->
<!-- Modal -->
<div class="modal fade" id="availModal" tabindex="-1" role="dialog" aria-labelledby="availModalLabel">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header bcoem-admin-modal">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="availModalLabel">Available <span class="text-capitalize"><?php echo $filter; ?></span> </h4>
            </div>
            <div class="modal-body">
                <p>The following <?php echo $filter; ?> have not been assigned to a table at this location, in this round:</p>
            	<table class="table table-responsive table-striped table-bordered table-condensed" id="sortable3">
                <thead>
                    <th>Name</th>
                    <th>Rank</th>
                </thead>
                <tbody>
                    <?php echo $output_available_modal_body; ?>	
                </tbody>
                </table>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div><!-- ./modal -->
<?php } ?>

<?php if (!empty($output_at_table_modal_body)) { ?>
<script type="text/javascript" language="javascript">
 $(document).ready(function() {
	$('#sortable4').dataTable( {
		"bPaginate" : false,
		"sDom": 'rt',
		"bStateSave" : false,
		"bLengthChange" : false,
		"aaSorting": [[0,'asc']],
		"bProcessing" : false,
		"aoColumns": [
			null,
			null<?php if ($_SESSION['jPrefsQueued'] == "N") { ?>,
			null,
			null
			<?php } ?>
			
							]
		} );
	} );
</script>
<!-- At Table Judges Modal -->
<!-- Modal -->
<div class="modal fade" id="atTableModal" tabindex="-1" role="dialog" aria-labelledby="atTableModalLabel">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header bcoem-admin-modal">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="atTableModalLabel"><span class="text-capitalize"><?php echo $filter; ?></span> Assigned to Table <?php echo $row_tables_edit['tableNumber']." &ndash; ".$row_tables_edit['tableName']; $entry_count = get_table_info(1,"count_total",$row_tables_edit['id'],$dbTable,"default"); echo " (".$entry_count." entries)"; ?> </h4>
            </div>
            <div class="modal-body">
            	<?php if ($filter == "judges") { ?>
                <p>There are <?php echo $ranked; ?> ranked judges and <?php echo $nonranked; ?> non-ranked judges at this table.</p>
                <?php } ?>
                <p>The following <?php echo $filter; ?> have been assigned to this table.</p>
            	<table class="table table-responsive table-striped table-bordered table-condensed" id="sortable4">
                <thead>
                    <th>Name</th>
                    <th>Rank</th>
                    <?php if ($_SESSION['jPrefsQueued'] == "N") { ?>
                    <th>Flight</th>
                    <th>Round</th>
                    <?php } ?>
                </thead>
                <tbody>
                    <?php echo $output_at_table_modal_body; ?>	
                </tbody>
                </table>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div><!-- ./modal -->
<?php } ?>
<form name="form1" method="post" action="<?php echo $base_url; ?>includes/process.inc.php?action=update&amp;dbTable=<?php echo $judging_assignments_db_table; ?>&amp;filter=<?php echo $filter; ?>&amp;limit=<?php echo $row_rounds['flightRound']; ?>&amp;view=<?php echo $_SESSION['jPrefsQueued']; ?>&amp;id=<?php echo $row_tables_edit['id']; ?>">
<table class="table table-responsive table-bordered table" id="sortable">
<thead>
 	<?php echo $output_datatables_head; ?>
</thead>
<tbody>
	<?php echo $output_datatables_body; ?>	
</tbody>
</table>
<p><input type="submit" class="btn btn-primary" name="Submit" value="Assign to Table <?php echo $row_tables_edit['tableNumber']; ?>" /></p>
<?php if (isset($_SERVER['HTTP_REFERER'])) { ?>
<input type="hidden" name="relocate" value="<?php echo relocate($_SERVER['HTTP_REFERER'],"default",$msg,$row_tables_edit['id']); if ($msg != "default") echo "&id=".$row_tables_edit['id']; ?>">
<?php } else { ?>
<input type="hidden" name="relocate" value="<?php echo relocate($base_url."index.php?section=admin","default",$msg,$id); ?>">
<?php } ?>
</form>
<?php
} // end if ($row_rounds['flightRound'] != "")
else { 
	if ($_SESSION['jPrefsQueued'] == "N") "<p>Flights from this table have not been assigned to rounds yet. <a href=\"".$base_url."index.php?section=admin&amp;go=judging_flights&amp;action=assign&amp;filter=rounds\">Assign flights to rounds?</a></p>"; 
	else echo "<p>This table has not been assigned to a round yet. <a href=\"".$base_url."index.php?section=admin&amp;go=judging_flights&amp;action=assign&amp;filter=rounds\">Assign to a round?</a></p>";
	}
?>