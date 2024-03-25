<?php

/**
 * Module:      judging_locations.admin.php
 * Description: This module houses all participant (brewer) related functionality
 *              involved in assigning participants a role - judge, steward, staff.
 *              Also provids judging location related functions - add, edit, delete.
 *
 */

// Redirect if directly accessed without authenticated session
if ((!isset($_SESSION['loginUsername'])) || ((isset($_SESSION['loginUsername'])) && ($_SESSION['userLevel'] > 1))) {
    $redirect = "../../403.php";
    $redirect_go_to = sprintf("Location: %s", $redirect);
    header($redirect_go_to);
    exit();
}

// Set Vars
$output_datatables_head = "";
$output_datatables_body = "";
$output_assignment_modals = "";
$output_add_edit = "";
$filter_readable = "";
$primary_page_info = "";
$secondary_nav = "";
$secondary_page_info = "";
$form_submit_url = "";
$form_submit_button = "";
$output_no_records = "";
$goto_nav = "";
$judge_loc_url_yes = "";
$judge_loc_url_no = "";
$assignment_modal_body = "";

if ($filter != "default") {
	$filter_readable = ucwords($filter);
	if ($filter == "judges") 	$staff_row_field = "staff_judge";
	if ($filter == "stewards") 	$staff_row_field = "staff_steward";
	if ($filter == "staff") 	$staff_row_field = "staff_staff";
}

// *****************************************************************************
// ---------------------- Top of Page Vars -------------------------------------
// *****************************************************************************


// Build Subtitle
$subtitle = "";
if ($section != "step5") {
	if ($action == "add") $subtitle .= ": Add a Non-Judging Session";
	elseif ($action == "edit") $subtitle .= ": Edit a Non-Judging Session";
	else $subtitle .= ": Non-Judging Sessions";
}

// Build Secondary Page Info

$secondary_page_info .= "<h5>Staff</h5>";
$secondary_page_info .= "<p>According to <a class='hide-loader' href='http://www.bjcp.org/rules.php' target='_blank'>BJCP rules</a>, staff members are &quot;...program participants who, under the direction of the Organizer, perform an active role in support of the competition other than as a Judge, Steward, or BOS Judge.&quot;";

// Judging Locations & Dates List
if ($section != "step5") {
	
	if (($action == "default") && ($totalRows_judging_locs > 0)) {
		
		$output_datatables_aaSorting = "[2,'asc']";
		$output_datatables_aoColumns = "null, null, null, { \"asSorting\": [  ] }";
		
		$output_datatables_head .= "<tr>";
		$output_datatables_head .= "<th>Name</th>";
		$output_datatables_head .= "<th>Start Date/Time</th>";
		$output_datatables_head .= "<th>Address</th>";
		$output_datatables_head .= "<th>Actions</th>";
		$output_datatables_head .= "</tr>";

		do {

			$output_datatables_edit_link = build_action_link("fa-pencil",$base_url,"admin","non-judging","edit",$filter,$row_judging_locs['id'],$dbTable,"default",0,"Edit ".$row_judging_locs['judgingLocName']);

			$output_datatables_delete_link = build_action_link("fa-trash-o",$base_url,"admin","non-judging","delete",$filter,$row_judging_locs['id'],$judging_locations_db_table,"Are you sure you want to delete ".$row_judging_locs['judgingLocName']."? All judge/steward account location preferences for this location will be removed. This cannot be undone.",0,"Delete ".$row_judging_locs['judgingLocName']);

			$output_datatables_actions = $output_datatables_edit_link." ".$output_datatables_delete_link;

			$output_datatables_body .= "<tr>";
			$output_datatables_body .= "<td>".$row_judging_locs['judgingLocName']."</td>";
			$output_datatables_body .= "<td><span class=\"hidden\">".$row_judging_locs['judgingDate']."</span>".getTimeZoneDateTime($_SESSION['prefsTimeZone'], $row_judging_locs['judgingDate'], $_SESSION['prefsDateFormat'],  $_SESSION['prefsTimeFormat'], "short", "date-time")."</td>";
			$output_datatables_body .= "<td>".$row_judging_locs['judgingLocation']."</td>";
			$output_datatables_body .= "<td>".$output_datatables_actions."</td>";
			$output_datatables_body .= "</tr>";

		} while($row_judging_locs = mysqli_fetch_assoc($judging_locs));

	} // end if (($totalRows_judging_locs > 0) && ($action == "default"))

} // end if ($section != "step5")

// *****************************************************************************
// ---------------------- Add/Edit Non-Judging Locations ---------------------------
// *****************************************************************************

if ((($action == "add") || ($action == "edit")) || ($section == "step5")) {
	$output_add_edit = TRUE;

	if ($section == "step5") $action = "add"; else $action = $action;
	if ($go == "default") $go = "setup"; else $go = $go;
	
	if ($section == "step5") $form_submit_url .= build_form_action($base_url,$section,$go,$action,$filter,"1",$judging_locations_db_table,TRUE);
	else {
		if ($action == "add") $form_submit_url .= build_form_action($base_url,$section,$go,$action,$filter,"1",$judging_locations_db_table,TRUE);
		if ($action == "edit") $form_submit_url .= build_form_action($base_url,$section,$go,$action,$filter,$row_judging['id'],$judging_locations_db_table,TRUE);
	}

	if ($action == "add") $form_submit_button .= "Add Non-Judging Session";
	elseif ($action == "edit") $form_submit_button .= "Edit Non-Judging Session";
	else $form_submit_button .= "Submit";

	$judging_date = "";
	$judging_end_date = "";
	$judging_time = "";
	if ($action == "edit") {
		$judging_date .= getTimeZoneDateTime($_SESSION['prefsTimeZone'], $row_judging['judgingDate'], $_SESSION['prefsDateFormat'],  $_SESSION['prefsTimeFormat'], "system", "date-time-system");
		if (!empty($row_judging['judgingDateEnd'])) $judging_end_date .= getTimeZoneDateTime($_SESSION['prefsTimeZone'], $row_judging['judgingDateEnd'], $_SESSION['prefsDateFormat'],  $_SESSION['prefsTimeFormat'], "system", "date-time-system");
	}

} // end if ((($action == "add") || ($action == "edit")) || ($section == "step5"))



// ----------------------------------------- Presentation ------------------------------------------


// Display Top Of Page Elements (Subtitle, Primary Page Info, Nav, and Secondary Page Info)
//echo $subtitle;
//echo $primary_page_info;
//if ($section != "step5") echo $goto_nav;
//echo $secondary_nav;
//echo $secondary_page_info;

// Display HTML/JS elements and compiled PHP elements
//if (!empty($output_no_records)) echo $output_no_records;
?>
<?php if (!empty($form_submit_url)) echo $form_submit_url; ?>
<p class="lead"><?php echo $_SESSION['contestName'].$subtitle; ?></p>
<div class="bcoem-admin-element hidden-print">
	<!-- Page Navigation Elements -->
	<?php if (($action == "add") || ($action == "edit")) { ?>
	<!-- Postion 1: View All Button -->
	<div class="btn-group bcoem-admin-element" role="group" aria-label="...">
        <a class="btn btn-default" href="<?php echo $base_url; ?>index.php?section=admin&amp;go=non-judging"><span class="fa fa-arrow-circle-left"></span> All Non-Judging Sessions</a>
    </div><!-- ./button group -->
	<?php } ?>
	
	<?php if (($action == "default") || ($action == "edit")) { ?>
	<div class="btn-group bcoem-admin-element" role="group" aria-label="...">
        <a class="btn btn-default" href="<?php echo $base_url; ?>index.php?section=admin&amp;go=non-judging&amp;action=add"><span class="fa fa-plus-circle"></span> Add a Non-Judging Session</a>
    </div><!-- ./button group -->
	<?php } ?>
</div><!-- ./bcoem-admin-element hidden-print -->
<p>Non-judging sessions are scheduled periods of time that necessitate staffing, such as entry pick-up, entry sorting, judge check-in, awards preparation, etc.</p>
<p>Anyone with an account who inicates they are willing to serve as staff will also have the option to indictate their availability for each non-judging session.</p>
<?php if (!empty($output_datatables_body)) {
echo $output_assignment_modals;
?>
<script type="text/javascript" language="javascript">
	 $(document).ready(function() {
		$('#sortable').dataTable( {
			"bPaginate" : <?php echo $output_datatables_bPaginate; ?>,
			"sPaginationType" : "<?php echo $output_datatables_sPaginationType; ?>",
			"bLengthChange" : <?php echo $output_datatables_bLengthChange; ?>,
			"iDisplayLength" : <?php echo round($_SESSION['prefsRecordPaging']); ?>,
			"sDom": 'fprtp',
			"bStateSave" : <?php echo $output_datatables_bStateSave; ?>,
			"aaSorting": [<?php echo $output_datatables_aaSorting; ?>],
			"bProcessing" : <?php echo $output_datatables_bProcessing; ?>,
			"aoColumns": [ <?php echo $output_datatables_aoColumns; ?> ]
			} );
		} );
</script>
<table class="table table-responsive table-bordered <?php if ($filter != "bos") echo "table-striped"; ?>" id="sortable">
<thead>
<?php echo $output_datatables_head; ?>
</thead>
<tbody>
<?php echo $output_datatables_body; ?>
</tbody>
</table>
<?php if (!empty($form_submit_url)) { ?>
<div class="bcoem-admin-element hidden-print">
	<input type="submit" name="Submit" id="helpUpdateJudgeAssign" class="btn btn-primary" aria-describedby="helpBlock" value="<?php echo $form_submit_button; ?>" />
    <span id="helpBlock" class="help-block"><?php echo $form_submit_button_help; ?></span>
</div>
</form>
<?php } ?>

<?php } // end if (($action == "default") && (!empty($output_datatables_body)))

// -------------------------------- Add/Edit Form ---------------------------------------------

if (($output_add_edit) && ($msg != 9)) { 
	if (!empty($form_submit_url)) echo $form_submit_url; 
?>

<input type="hidden" name="token" value ="<?php if (isset($_SESSION['token'])) echo $_SESSION['token']; ?>">
<script type="text/javascript">
	$(document).ready(function() {
		$('#judgingDate').datetimepicker({
			format: 'YYYY-MM-DD hh:mm A'
		});
	});
</script>
<input type="hidden" name="judgingLocType" value="2">
<div class="form-group"><!-- Form Group REQUIRED Text Input -->
	<label for="judgingLocName" class="col-lg-2 col-md-3 col-sm-4 col-xs-12 control-label">Session Name</label>
	<div class="col-lg-6 col-md-6 col-sm-8 col-xs-12">
		<div class="input-group has-warning">
			<!-- Input Here -->
			<input class="form-control" id="judgingLocName" name="judgingLocName" type="text" size="10" maxlength="255" value="<?php if ($action == "edit") echo $row_judging['judgingLocName']; ?>" placeholder="" autofocus required>
			<span class="input-group-addon" id="judgingTime2" data-tooltip="true" title="<?php echo $form_required_fields_02; ?>"><span class="fa fa-star"></span></span>
		</div>
		<span class="help-block">Provide the name of the judging location.</span>
	</div>
</div><!-- ./Form Group -->

<div class="form-group"><!-- Form Group REQUIRED Text Input -->
	<label for="judgingDate" class="col-lg-2 col-md-3 col-sm-4 col-xs-12 control-label">Session Start Date/Time</label>
	<div class="col-lg-6 col-md-6 col-sm-8 col-xs-12">
		<div class="input-group date has-warning">
			<!-- Input Here -->
			<input class="form-control" id="judgingDate" name="judgingDate" type="text" value="<?php if ($action == "edit") echo $judging_date; ?>" placeholder="<?php if (strpos($section, "step") === FALSE) echo $current_date." ".$current_time; ?>" required>
			<span class="input-group-addon" data-tooltip="true" title="<?php echo $form_required_fields_02; ?>"><span class="fa fa-star"></span></span>
		</div>
		<span class="help-block">Provide an start date and time for the session.</span>
	</div>
</div><!-- ./Form Group -->

<div class="form-group"><!-- Form Group REQUIRED Text Input -->
	<label id="judgingLocationLabel" for="judgingLocation" class="col-lg-2 col-md-3 col-sm-4 col-xs-12 control-label">Session Address</label>
	<div class="col-lg-6 col-md-6 col-sm-8 col-xs-12">
		<div class="input-group has-warning">
			<!-- Input Here -->
			<input class="form-control" id="judgingLocation" name="judgingLocation" type="text" size="10" maxlength="255" value="<?php if ($action == "edit") echo $row_judging['judgingLocation']; ?>" placeholder="" required>
			<span class="input-group-addon" data-tooltip="true" title="<?php echo $form_required_fields_02; ?>"><span class="fa fa-star"></span></span>
		</div>
        <span id="helpBlockLocation1" class="help-block">Provide the street address, city, and zip/postal code where the session will take place.</span>
	</div>
</div><!-- ./Form Group -->

<?php if (!empty($form_submit_button)) { ?>
<div class="bcoem-admin-element hidden-print">
	<div class="form-group">
		<div class="col-lg-offset-2 col-md-offset-3 col-sm-offset-4">
			<input type="submit" name="Submit" id="helpUpdateJudgeAssign" class="btn btn-primary" value="<?php echo $form_submit_button; ?>" />
		</div>
	</div>
</div>
<?php } ?>
<input type="hidden" name="relocate" value="<?php echo $base_url."index.php?section=admin&amp;go=non-judging"; ?>">
</form>
<?php } ?>