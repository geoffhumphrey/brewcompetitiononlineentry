<?php
/**
 * Module:      participants.admin.php
 * Description: This module houses all participant (brewer) related functionality
 *              including viewing a participant list, add/edit/delete,
 *              Also provids judging location related functions - add, edit, delete.
 *
 */

// Redirect if directly accessed without authenticated session
if ((!isset($_SESSION['loginUsername'])) || ((isset($_SESSION['loginUsername'])) && ($_SESSION['userLevel'] > 1))) {
    if (function_exists('redirect_or_exit')) redirect_or_exit("../../403.php");
    else { header("Location: ../../403.php"); exit(); }
}

include (DB.'admin_participants.db.php');

// Set Vars
$subtitle = "";
$primary_page_info = "";
$goto_nav = "";
$secondary_nav = "";
$secondary_page_info = "";
$form_submit_url = "";
$form_submit_button = "";
$output_datatables_head = "";
$output_datatables_body = "";
$output_add_edit = "";
$filter_readable = "";
$primary_page_info = "";
$secondary_nav = "";
$secondary_page_info = "";
$form_submit_url = "";
$form_submit_button = "";
$output_no_records = "";
$output_add_edit = FALSE;
$output_hide_print = "";
$output_assignment_modals = "";
$output_user_question_modals = "";
$assignment_modal_body = "";
$all_email_display = array();


if ($dbTable == "default") $pro_edition = $_SESSION['prefsProEdition'];
else $pro_edition = $row_archive_prefs['archiveProEdition'];

if ($pro_edition == 0) $edition = $label_amateur." ".$label_edition;
if ($pro_edition == 1) $edition = $label_pro." ".$label_edition;

if ($action == "print") {
	$output_hide_print .= "hidden-print";
}
else {
	$output_hide_print .= "hidden-md hidden-sm hidden-xs";
	if (($dbTable == "default") && ($row_participant_count['count'] > $_SESSION['prefsRecordLimit']))	{
			echo "<div class='info'>The DataTables recordset paging limit of ".$_SESSION['prefsRecordLimit']." has been surpassed. Filtering and sorting capabilites are only available for this set of ".$_SESSION['prefsRecordPaging']." participants.<br />To adjust this setting, <a href='index.php?section=admin&amp;go=preferences'>change your installation's DataTables Record Threshold</a> (under the &ldquo;Performance&rdquo; heading in preferences) to a number <em>greater</em> than the total number of participants (".$row_participant_count['count'].").</div>";
	}
}


// *****************************************************************************
// ---------------------- Top of Page Vars -------------------------------------
// *****************************************************************************

// Build Subtitle
if ($filter == "judges") {
	$subtitle .= "Available Judges";
	$csv = "avail_judges";
	}
elseif ($filter == "stewards") {
	$subtitle .= "Available Stewards";
	$csv = "avail_stewards";
	}
elseif ($filter == "with_entries") {
	$subtitle .= "Participants with Entries";
	$csv = "with_entries";
	}
elseif ($action == "add") $subtitle .= "Add a Participant";
else $subtitle .= "Participants";
if ($dbTable != "default") $subtitle .= ": All Participants (Archive ".get_suffix($dbTable).")";


// *****************************************************************************
// ---------------------- List Participants ---------------------------
// *****************************************************************************

$output_datatables_aaSorting = "";
$output_datatables_aoColumns = "";

if ($action == "print") {

	$output_datatables_aaSorting .= "";
	if ($psort == "judge_rank") $output_datatables_aaSorting .= "[5,'desc']";
	if ($psort == "judge_id")  $output_datatables_aaSorting .= "[4,'asc']";
	if ($psort == "club") $output_datatables_aaSorting .= "[1,'asc']";
	if (($psort == "default") || ($psort == "brewer_name")) $output_datatables_aaSorting .= "[0,'asc']";
	if ($psort == "organization") $output_datatables_aaSorting .= "[2,'asc']";
	if ($filter == "default") 	{
		$output_datatables_aoColumns .= "{ \"asSorting\": [  ] }, { \"asSorting\": [  ] }, { \"asSorting\": [  ] }, { \"asSorting\": [  ] }, { \"asSorting\": [  ] }, { \"asSorting\": [  ] }, { \"asSorting\": [  ] }, { \"asSorting\": [  ] }";
	}

	if ($filter == "judges") 	{
		$output_datatables_aoColumns .= "{ \"asSorting\": [  ] },{ \"asSorting\": [  ] }, { \"asSorting\": [  ] }, { \"asSorting\": [  ] }, { \"asSorting\": [  ] }, { \"asSorting\": [  ] }, { \"asSorting\": [  ] }, { \"asSorting\": [  ] }, { \"asSorting\": [  ] }, { \"asSorting\": [  ] }";
	}

	if ($filter == "stewards") 	{
		$output_datatables_aoColumns .= "{ \"asSorting\": [  ] }, { \"asSorting\": [  ] }, { \"asSorting\": [  ] }, { \"asSorting\": [  ] }, { \"asSorting\": [  ] }, { \"asSorting\": [  ] }, null, null";
	}


} // end if ($action == "print")

else {

	if ($filter == "default") 	{
		if ($pro_edition == 1) $output_datatables_aaSorting .= "[1,'asc']";
		else  $output_datatables_aaSorting .= "[0,'asc']";
		$output_datatables_aoColumns .= "null, null, null, { \"asSorting\": [  ] }, { \"asSorting\": [  ] }, null, null";
		if ($dbTable == "default") 	$output_datatables_aoColumns .= ",  { \"asSorting\": [  ] }";
	}

	if ($filter == "judges") 	{
		$output_datatables_aaSorting .= "[0,'asc']";
		$output_datatables_aoColumns .= "null, null, null,	null, null, null, null, null, null, { \"asSorting\": [  ] }";
	}

	if ($filter == "stewards") 	{
		$output_datatables_aaSorting .= "[0,'asc']";
		$output_datatables_aoColumns .= "null, null, null,	null, null, null, null, { \"asSorting\": [  ] }";
	}

	if ($filter == "with_entries") 	{
		$output_datatables_aaSorting .= "[0,'asc']";
		$output_datatables_aoColumns .= "null, null, { \"asSorting\": [  ] }";
	}

}

if ($filter == "with_entries") {

	$output_datatables_head .= "<tr>";
	if ($pro_edition == 1) $output_datatables_head .= "<th>Organization</th>";
	else $output_datatables_head .= "<th>Name</th>";
	$output_datatables_head .= "<th>Entries</th>";
	if ($action != "print") $output_datatables_head .= "<th>Actions</th>";
	$output_datatables_head .= "</tr>";

	}

else {
	$output_datatables_head .= "<tr>";
	if ($pro_edition == 1) $output_datatables_head .= "<th>Contact Name</th>";
	else $output_datatables_head .= "<th>Name</th>";
	$output_datatables_head .= "<th>User Level</th>";
	if ($action == "print") $output_datatables_head .= "<th>Info</th>";
	if (($totalRows_judging > 0) && (($filter == "judges") || ($filter == "stewards"))) $output_datatables_head .= "<th class=\"".$output_hide_print."\">Location(s) Available</th>";
	else {
		if ($pro_edition == 1) $output_datatables_head .= "<th>Organization</th>";
		else $output_datatables_head .= "<th class=\"".$output_hide_print."\">Club</th>";
	}
	if ($filter == "default") {
		$output_datatables_head .= "<th class=\"".$output_hide_print."\">Steward?</th>";
		$output_datatables_head .= "<th class=\"".$output_hide_print."\">Judge?</th>";
	}
	$output_datatables_head .= "<th>Assigned As</th>";
	if ($filter != "default") {
		if ($filter == "judges") {
			$output_datatables_head .= "<th class=\"".$output_hide_print."\">ID</th>";
			$output_datatables_head .= "<th>Rank</th>";

		}
		$output_datatables_head .= "<th>Assigned to Table(s)</th>";
		$output_datatables_head .= "<th class=\"".$output_hide_print."\">Has Entries In...</th>";

	}
	$output_datatables_head .= "<th class=\"".$output_hide_print."\">Updated</th>";
	if (($action != "print") && ($dbTable == "default")) $output_datatables_head .= "<th>Actions</th>";
	$output_datatables_head .= "</tr>";

	$email_subject = "";
	if ($filter == "judges") $email_subject .= "Judging at ".$_SESSION['contestName'];
	elseif ($filter == "stewards") $email_subject .= "Stewarding at ".$_SESSION['contestName'];
	else $email_subject .= $_SESSION['contestName'];
}

if ($totalRows_brewer > 0) {

	/**
	 * Batch what used to be several queries per participant row (brewer_assignment(),
	 * table_assignments() x2 - each with its own nested get_table_info() N+1 per
	 * assignment - judge_entries() x2, and date_created() with a repeated
	 * information_schema probe) into a handful of upfront queries, run once - same
	 * pattern used throughout pub/, awards.php, and export.output.php this session.
	 */

	$participant_uids_p = array();
	$participant_user_ids_p = array();
	foreach ($rows_brewer as $row_brewer_prefetch_p) {
		if (isset($row_brewer_prefetch_p['uid'])) $participant_uids_p[] = $row_brewer_prefetch_p['uid'];
		if (isset($row_brewer_prefetch_p['user_id'])) $participant_user_ids_p[] = $row_brewer_prefetch_p['user_id'];
	}
	$participant_uids_p = array_unique($participant_uids_p);
	$participant_user_ids_p = array_unique($participant_user_ids_p);

	$archive_p = "default";
	if ($dbTable != "default") $archive_p = get_suffix($dbTable);
	$staff_db_table_p = ($archive_p != "default") ? $prefix."staff_".$archive_p : $prefix."staff";

	// Mirrors brewer_assignment()'s method=="1" logic exactly, batched.
	$staff_by_uid_p = array();
	if ((!empty($participant_uids_p)) && (table_exists($staff_db_table_p))) {
		$db_conn->where('uid', $participant_uids_p, 'in');
		$rows_staff_p = $db_conn->get($staff_db_table_p);
		foreach ($rows_staff_p as $row_staff_p) {
			$staff_by_uid_p[$row_staff_p['uid']] = $row_staff_p;
		}
	}

	// Mirrors table_assignments()'s judging_assignments query, batched across both the
	// "J" and "S" methods and every participant at once. table_assignments(),
	// judging_location_avail(), and the judges/stewards judge_entries() column are all
	// unreachable for archive views in this file (gated by !$archive_display, or by
	// filter=="judges"/"stewards" which brewer.db.php never combines with an archive
	// dbTable) - skip fetching their data entirely rather than fetching data guaranteed
	// to go unused.
	$judging_assignments_by_user_p = array();
	if ((!$archive_display) && (!empty($participant_user_ids_p)) && (table_exists($prefix."judging_assignments"))) {
		$db_conn->where('bid', $participant_user_ids_p, 'in');
		$db_conn->where('assignment', array('J','S'), 'in');
		$db_conn->orderBy('assignTable', 'ASC');
		$rows_assignments_p = $db_conn->get($prefix."judging_assignments", null, "bid,assignment,assignTable,assignRoles,assignFlight,assignRound");
		foreach ($rows_assignments_p as $row_assignment_p) {
			$judging_assignments_by_user_p[$row_assignment_p['bid'].'|'.$row_assignment_p['assignment']][] = $row_assignment_p;
		}
	}

	// Mirrors get_table_info(1,"basic",...) and get_table_info(...,"location",...), which
	// table_assignments() called once per assignment row - fetched once for every table/
	// location instead.
	$judging_tables_by_id_p = array();
	if ((!$archive_display) && (table_exists($prefix."judging_tables"))) {
		$rows_all_tables_p = $db_conn->get($prefix."judging_tables", null, "id,tableNumber,tableName,tableLocation,tableStyles");
		foreach ($rows_all_tables_p as $row_table_p) {
			$judging_tables_by_id_p[$row_table_p['id']] = $row_table_p;
		}
	}

	$judging_locations_by_id_p = array();
	if ((!$archive_display) && (table_exists($prefix."judging_locations"))) {
		$rows_all_locations_p = $db_conn->get($prefix."judging_locations", null, "id,judgingDate,judgingDateEnd,judgingLocName,judgingLocation,judgingLocType,judgingLocNotes");
		foreach ($rows_all_locations_p as $row_location_p) {
			$judging_locations_by_id_p[$row_location_p['id']] = $row_location_p;
		}
	}

	// Mirrors judge_entries()'s query exactly, batched. Used both for the assignment
	// modal (unconditional per assigned row) and the judges/stewards table column
	// (the old code ran this same query a second time for that column) - both
	// unreachable for archive views, same reasoning as above.
	$entries_by_uid_p = array();
	if ((!$archive_display) && (table_exists($prefix."brewing"))) {
		$db_conn->where('brewBrewerID', $participant_uids_p, 'in');
		$db_conn->orderBy('brewCategorySort', 'ASC');
		$rows_entries_p = $db_conn->get($prefix."brewing", null, "brewBrewerID, brewStyle, brewCategory, brewSubCategory, brewCategorySort");
		foreach ($rows_entries_p as $row_entry_p) {
			$entries_by_uid_p[$row_entry_p['brewBrewerID']][] = $row_entry_p;
		}
	}

	// Mirrors date_created()'s information_schema probe - run once for the whole page
	// instead of once per row, since $dbTable (and therefore the target table) doesn't
	// change per row - plus its per-user lookup, batched.
	$date_created_dbtable_p = ($dbTable != "default") ? $dbTable : $prefix."users";
	$db_conn->where('table_schema', $database);
	$db_conn->where('table_name', $date_created_dbtable_p);
	$db_conn->where('column_name', 'userCreated');
	$row_created_exists_p = $db_conn->getOne('information_schema.columns', 'COUNT(*) AS count');
	$date_created_exists_p = ($row_created_exists_p['count'] > 0);
	$user_created_by_id_p = array();
	if (($date_created_exists_p) && (!empty($participant_uids_p))) {
		$db_conn->where('id', $participant_uids_p, 'in');
		$rows_created_p = $db_conn->get($date_created_dbtable_p, null, "id, userCreated");
		foreach ($rows_created_p as $row_created_p) {
			$user_created_by_id_p[$row_created_p['id']] = $row_created_p['userCreated'];
		}
	}

	/**
	 * Rebuilds table_assignments()'s method2==1 output string from the batched maps
	 * above instead of a fresh judging_assignments/judging_tables/judging_locations
	 * query per participant per method.
	 */
	$table_assignments_batched_p = function($user_id, $method) use ($judging_assignments_by_user_p, $judging_tables_by_id_p, $judging_locations_by_id_p, $base_url) {
		$output = "";
		$assignments = $judging_assignments_by_user_p[$user_id.'|'.$method] ?? array();
		foreach ($assignments as $row_table_assignments) {
			$table_row = $judging_tables_by_id_p[$row_table_assignments['assignTable']] ?? null;
			if (!$table_row) continue;
			$table_info = array($table_row['tableNumber'], $table_row['tableName'], $table_row['tableLocation'], $table_row['id'], $table_row['tableStyles']);
			$location = array();
			if (isset($table_info[2])) {
				$loc_row = $judging_locations_by_id_p[$table_info[2]] ?? null;
				if ($loc_row) $location = array($loc_row['judgingDate'], $loc_row['judgingDateEnd'], $loc_row['judgingLocName'], $loc_row['judgingLocation'], $loc_row['judgingLocType'], $loc_row['judgingLocNotes']);
			}
			if ((!empty($location)) && (isset($location[2]))) {
				if ((isset($table_info[0])) && (isset($table_info[1])) && (isset($table_info[3]))) {
					if ($method == "J") $output .= $table_info[0]." - <a href='".$base_url."index.php?section=admin&amp;action=assign&amp;go=judging_tables&amp;filter=judges&id=".$table_info[3]."' data-toggle=\"tooltip\" title='Assign/Unassign Judges to Table ".$table_info[0]." - ".$table_info[1]."'>".$table_info[1]."</a>,&nbsp;";
					if ($method == "S") $output .= "<a href='".$base_url."index.php?section=admin&amp;action=assign&amp;go=judging_tables&amp;filter=stewards&id=".$table_info[3]."' data-toggle=\"tooltip\" title='Assign/Unassign Stewards to Table ".$table_info[0]." - ".$table_info[1]."'>".$table_info[0]." - ".$table_info[1]."</a>,&nbsp;";
				}
			}
		}
		return $output;
	};

	/**
	 * Rebuilds judge_entries()'s method==1 output string from the batched entries map
	 * above instead of a fresh brewing query per participant (this was called twice
	 * per row in the original - once for the assignment modal, once for the
	 * judges/stewards table column - both now share this one lookup).
	 */
	$judge_entries_batched_p = function($uid) use ($entries_by_uid_p, $base_url) {
		$entries = $entries_by_uid_p[$uid] ?? array();
		$parts = array();
		foreach ($entries as $row_judge_entries) {
			if ($_SESSION['style_set_no_numbering']) {
				$parts[] = "<a href=\"".$base_url."index.php?section=admin&amp;go=entries&amp;filter=".$row_judge_entries['brewCategorySort']."\" data-toggle=\"tooltip\" data-placement=\"top\" title=\"View the ".$row_judge_entries['brewStyle']." Entries\">".$row_judge_entries['brewStyle']."</a>";
			}
			else {
				$parts[] = "<a href=\"".$base_url."index.php?section=admin&amp;go=entries&amp;filter=".$row_judge_entries['brewCategorySort']."\" data-toggle=\"tooltip\" data-placement=\"top\" title=\"View the ".$row_judge_entries['brewStyle']." Entries\">".$row_judge_entries['brewCategory'].$row_judge_entries['brewSubCategory']."</a>";
			}
		}
		return rtrim(implode(", ", $parts), ", ");
	};

	foreach ($rows_brewer as $row_brewer) {

		$output_datatables_add_link = "";
		$output_datatables_edit_link = "";
		$output_datatables_delete_link = "";
		$output_datatables_print_link = "";
		$output_datatables_other_link = "";
		$output_datatables_other_link2 = "";
		$output_datatables_view_link = "";
		$output_datatables_email_link = "";
		$output_datatables_change_pwd = "";
		$output_datatables_user_question_link = "";
		$output_datatables_phone_link = "";
		$output_datatables_actions = "";
		$table_assign_judge = "";
		$table_assign_steward = "";
		$judge_entries = "";
		$brewer_assignment = "";
		$user_info = "";

		if ($_SESSION['brewerCountry'] == "United States") $us_phone = TRUE; else $us_phone = FALSE;

		unset($brewer_assignment);
		// Pulled from the batched staff lookup above instead of a fresh
		// brewer_assignment() query per row - mirrors its method=="1" logic exactly.
		$row_staff_assignment = $staff_by_uid_p[$row_brewer['uid']] ?? null;
		$brewer_assignment_parts = array();
		if ($row_staff_assignment) {
			if ($row_staff_assignment['staff_organizer'] == "1") $brewer_assignment_parts[] = strtolower($label_organizer);
			if ($row_staff_assignment['staff_judge_bos'] == "1") $brewer_assignment_parts[] = "BOS";
			if ($row_staff_assignment['staff_judge'] == "1") $brewer_assignment_parts[] = $label_judge;
			if ($row_staff_assignment['staff_steward'] == "1") $brewer_assignment_parts[] = $label_steward;
			if ($row_staff_assignment['staff_staff'] == "1") $brewer_assignment_parts[] = $label_staff;
		}
		$brewer_assignment = implode(", ", $brewer_assignment_parts);
		$brewer_assignment = ltrim(rtrim($brewer_assignment, ", "), ", ");
		$all_email_display[] = $row_brewer['brewerEmail'];

		// Build Action Links
		// build_action_link($icon,$base_url,$section,$go,$action,$filter,$id,$dbTable,$alt_title) {

		if (($pro_edition == 1) && (!empty($row_brewer['brewerBreweryName']))) $brewer_tooltip_display_name = html_entity_decode($row_brewer['brewerBreweryName'], ENT_QUOTES, 'UTF-8');
		else $brewer_tooltip_display_name = html_entity_decode($row_brewer['brewerFirstName']." ".$row_brewer['brewerLastName'], ENT_QUOTES, 'UTF-8');

		if (!$archive_display) {

			if ((HOSTED) && ($row_brewer['brewerEmail'] == $hosted_admin_email)) {
					$output_datatables_edit_link = "<span class=\"fa fa-lg fa-pencil text-muted\" data-toggle=\"tooltip\" data-placement=\"top\" title=\"This is the BCOE&amp;M Hosting master account. For troubleshooting purposes, it cannot be changed.\"></span>";
					$output_datatables_delete_link = "<span class=\"fa fa-lg fa-trash-o text-muted\" data-toggle=\"tooltip\" data-placement=\"top\" title=\"This is the BCOE&amp;M Hosting master account. For troubleshooting purposes, it cannot be deleted.\"></span>";
					$output_datatables_other_link = "<span class=\"fa fa-lg fa-lock text-muted\" data-toggle=\"tooltip\" data-placement=\"top\" title=\"This is the BCOE&amp;M Hosting master account. For troubleshooting purposes, it cannot be changed.\"></span>";
					$output_datatables_other_link2 = "<span class=\"fa fa-lg fa-user text-muted\" data-toggle=\"tooltip\" data-placement=\"top\" title=\"This is the BCOE&amp;M Hosting master account. For troubleshooting purposes, it cannot be changed.\"></span>";
					$output_datatables_change_pwd = "<span class=\"fa fa-lg fa-key text-muted\" data-toggle=\"tooltip\" data-placement=\"top\" title=\"This is the BCOE&amp;M Hosting master account. For troubleshooting purposes, it cannot be changed.\"></span>";
					$output_datatables_unlock_link = "";
					$output_datatables_phone_link = "<span class=\"fa fa-lg fa-phone text-muted\"></span></a>";
			}

			else {

				$output_datatables_edit_link = build_action_link("fa-pencil",$base_url,"admin","brewer","edit",$row_brewer['uid'],$row_brewer['id'],$dbTable,"default",0,"Edit ".$brewer_tooltip_display_name."'s user account information");

				if ($_SESSION['userLevel'] == 0) {

					if ($row_brewer['brewerEmail'] != $_SESSION['loginUsername']) $output_datatables_delete_link = build_action_link("fa-trash-o",$base_url,"admin","participants","delete",$row_brewer['uid'],$row_brewer['uid'],$brewer_db_table,"Are you sure you want to delete the participant account for ".$brewer_tooltip_display_name."? ALL entries for this participant WILL BE DELETED as well. This cannot be undone.",0,"Delete ".$brewer_tooltip_display_name."'s account.");
					else $output_datatables_delete_link = "<span class=\"fa fa-lg fa-trash-o text-muted\" data-toggle=\"tooltip\" data-placement=\"top\" title=\"Silly, you cannot delete yourself, ".$_SESSION['brewerFirstName']."!\"></span>";

				
					if ($row_brewer['brewerEmail'] != $_SESSION['loginUsername']) $output_datatables_other_link = build_action_link("fa-lock",$base_url,"admin","make_admin","default","default",$row_brewer['uid'],"default","default",0,"Change ".$brewer_tooltip_display_name."'s User Level");
					else $output_datatables_other_link = "<span class=\"fa fa-lg fa-lock text-muted\" data-toggle=\"tooltip\" data-placement=\"top\" title=\"You cannot change your own user level, ".$_SESSION['brewerFirstName'].".\"></span>";

					$output_datatables_other_link2 = build_action_link("fa-user",$base_url,"admin","user","username","admin",$row_brewer['user_id'],"default","default",0,"Change ".$brewer_tooltip_display_name."'s email address");

				}

				else {
					$output_datatables_other_link = "";
					$output_datatables_delete_link = "";
					$output_datatables_other_link2 = "";
				}
				
				if (strpos($brewer_assignment,'Judge') !== false)  {
					$output_datatables_view_link = "<a class=\"hide-loader\" href=\"".$base_url."includes/output.inc.php?section=labels-judge&amp;go=participants&amp;action=judging_labels&amp;id=".$row_brewer['id']."&amp;psort=5160\" data-toggle=\"tooltip\" data-placement=\"top\" title=\"Download Judge Scoresheet Labels for ".h($brewer_tooltip_display_name)." - Letter (Avery 5160)\"><span class=\"fa fa-lg fa-file\"></span></a>";
					$output_datatables_view_link2 = "<a class=\"hide-loader\" href=\"".$base_url."includes/output.inc.php?section=labels-judge&amp;go=participants&amp;action=judging_labels&amp;id=".$row_brewer['id']."&amp;psort=3422\" data-toggle=\"tooltip\" data-placement=\"top\" title=\"Download Judge Scoresheet Labels for ".h($brewer_tooltip_display_name)." - A4 (Avery 3422)\"><span class=\"fa fa-lg fa-file-text\"></span></a>";
				}

				else {
					$output_datatables_view_link = "";
					$output_datatables_view_link2 = "";
				}

				if ($us_phone) {
					$output_datatables_phone_link = "<a class=\"hide-loader\" href=\"#\" data-toggle=\"tooltip\" data-placement=\"top\" title=\"".h($brewer_tooltip_display_name)."&rsquo;s phone number: ".h(format_phone_us($row_brewer['brewerPhone1']))."\"><span class=\"fa fa-lg fa-phone\"></span></a>";
				}

				else {
					$output_datatables_phone_link = "<a class=\"hide-loader\" href=\"#\" data-toggle=\"tooltip\" data-placement=\"top\" title=\"".h($brewer_tooltip_display_name)."&rsquo;s phone number: ".h($row_brewer['brewerPhone1'])."\"><span class=\"fa fa-lg fa-phone\"></span></a>";
				}

				if ($_SESSION['userLevel'] == 0) {
					$output_datatables_change_pwd = build_action_link("fa-key",$base_url,"admin","change_user_password","edit","default",$row_brewer['user_id'],"default","default",0,"Change ".$brewer_tooltip_display_name."'s password");
				}

				else $output_datatables_change_pwd = "";

				// Only shown when the account is actually currently locked out from failed logins -
				// omitted entirely otherwise (not muted), same as $output_datatables_other_link/2 above.
				// Available to userLevel <= 1 (not just the top-admin/owner) so a second Admin can
				// clear a locked-out account - e.g. the owner's own account - without waiting out
				// the window, matching change_user_password's existing <=1 gate on the process side:
				// unlocking is strictly less powerful than a full password reset, which <=1 admins
				// can already do.
				$row_locked = (((int) $row_brewer['userFailedLogins'] >= LOGIN_LOCKOUT_THRESHOLD) && ((time() - (int) $row_brewer['userFailedLoginTime']) < LOGIN_LOCKOUT_WINDOW_SECONDS));

				if (($_SESSION['userLevel'] <= 1) && ($row_locked)) {
					$output_datatables_unlock_link = "<a class=\"hide-loader\" href=\"".$base_url."includes/process.inc.php?section=admin&amp;go=unlock_user&amp;dbTable=".$users_db_table."&amp;action=unlock_user&amp;id=".$row_brewer['user_id']."\" data-toggle=\"tooltip\" data-placement=\"top\" title=\"Unlock ".h($brewer_tooltip_display_name)."'s account\" data-confirm=\"Are you sure you want to unlock ".h($brewer_tooltip_display_name)."'s account?\"><span class=\"fa fa-lg fa-unlock\"></span></a>";
				}

				else $output_datatables_unlock_link = "";

			}

			$output_datatables_email_link .= "<a class=\"hide-loader\" href=\"mailto:".h($row_brewer['brewerEmail'])."\" data-toggle=\"tooltip\" data-placement=\"top\" title=\"Email ".h($brewer_tooltip_display_name)." at ".h($row_brewer['brewerEmail'])."\"><span class=\"fa fa-lg fa-envelope\"></span></a>";

			$output_datatables_add_link = build_action_link("fa-beer",$base_url,"admin","entries","add",$row_brewer['uid'],"default","default","default",0,"Add an entry for ".$brewer_tooltip_display_name); // /index.php?section=admin&go=entries&action=add&bid=864

			$output_actions_arr = array($output_datatables_add_link, $output_datatables_edit_link, $output_datatables_delete_link, $output_datatables_other_link, $output_datatables_email_link, $output_datatables_phone_link, $output_datatables_other_link2, $output_datatables_change_pwd, $output_datatables_unlock_link, $output_datatables_view_link, $output_datatables_view_link2);

			$output_datatables_actions = "";
			foreach ($output_actions_arr as $value) {
				if (!empty($value)) $output_datatables_actions .= "<span style=\"margin-right: .4em\">".$value."</span>";
			}

		}

		if ($filter == "with_entries") {
			unset($entries);
			unset($judging_nums);
			$output_datatables_body .= "<tr>";
			if ($pro_edition == 1) $output_datatables_body .= "<td>".$row_brewer['brewerBreweryName']."</td>";
			else {
				$output_datatables_body .= "<td>";
				$output_datatables_body .= "<a name='".$row_brewer['uid']."'></a>";
				$output_datatables_body .= h($row_brewer['brewBrewerLastName']).", ".h($row_brewer['brewBrewerFirstName']);
				$output_datatables_body .= "</td>";
			}

			$explodies = explode(",",$row_brewer['Entries']);

			foreach ($explodies as $entry_number) {
				$entry_number = sprintf("%06s",$entry_number);
				$entries[] = $entry_number;
			}

			$brewer_entries = implode(",",$entries);

			$explodies = explode(",",$row_brewer['JudgingNums']);

			foreach ($explodies as $judging_number) {
				$judging_number = sprintf("%06s",$judging_number);
				$judging_nums[] = $judging_number;
			}

			$brewer_entries_judging_nums = implode(",",$judging_nums);

			$output_datatables_body .= "<td><a href=\"".$base_url."index.php?section=admin&amp;go=entries&amp;bid=".$row_brewer['uid']."\" data-toggle=\"tooltip\" data-placement=\"top\" title=\"List ".h($brewer_tooltip_display_name)."&rsquo;s entries.\">Entry Numbers</a>: ".h(str_replace(",",", ",$brewer_entries))."<br>Judging Numbers: ".h(str_replace(",",", ",$brewer_entries_judging_nums))."</td>";
			if ($action != "print") $output_datatables_body .= "<td>".$output_datatables_actions."</td>";
			$output_datatables_body .= "</tr>";

		}

		else {

			if ($filter == "judges") $locations = $row_brewer['brewerJudgeLocation'];
			if ($filter == "stewards") $locations = $row_brewer['brewerStewardLocation'];

			if ((!empty($brewer_assignment) && (!$archive_display))) {

				// Pulled from the batched maps above instead of fresh table_assignments()/
				// judge_entries() queries per row.
				$table_assign_judge = $table_assignments_batched_p($row_brewer['user_id'],"J");
				$table_assign_judge = str_replace(",&nbsp;","<br>",$table_assign_judge);

				$table_assign_steward = $table_assignments_batched_p($row_brewer['user_id'],"S");
				$table_assign_steward = str_replace(",&nbsp;","<br>",$table_assign_steward);

				$judge_entries = $judge_entries_batched_p($row_brewer['uid']);

				if (($filter != "judges") && ($filter != "stewards")) {
				
					// Build assignment modal for participants
					unset($assignment_modal_body);
					if ((strpos($brewer_assignment,"Judge") !== false) || (strpos($brewer_assignment,"Steward") !== false) ) {

						if (strpos($brewer_assignment,"Judge") !== false) {
							if (!empty($table_assign_judge)) $assignment_modal_body = "<p>".$row_brewer['brewerFirstName']." is assigned as a <strong>judge</strong> to table(s):<br>".h($table_assign_judge)."</p>";
							else $assignment_modal_body = "<p>".$row_brewer['brewerFirstName']." has been added to the <strong>judge</strong> pool, but has not been assigned to a table yet.</p>";
						}
						if (strpos($brewer_assignment,"Steward") !== false) {
							if (!empty($table_assign_steward))  $assignment_modal_body = "<p>".$row_brewer['brewerFirstName']." is assigned as a <strong>steward</strong> to table(s):<br>".h($table_assign_steward)."</p>";
							else $assignment_modal_body = "<p>".$row_brewer['brewerFirstName']." has been added to the <strong>steward</strong> pool, but has not been assigned to a table yet.</p>";
						}
						if (!empty($judge_entries)) $assignment_modal_body .= "<p>Has entries in: ".h($judge_entries)."</p>";
						$output_assignment_modals .= "<div class=\"modal fade\" id=\"assignment-modal-".$row_brewer['uid']."\" tabindex=\"-1\" role=\"dialog\" aria-labelledby=\"assignment-modal-label-".$row_brewer['uid']."\">\n";
						$output_assignment_modals .= "\t<div class=\"modal-dialog modal-lg\" role=\"document\">\n";
						$output_assignment_modals .= "\t\t<div class=\"modal-content\">\n";
						$output_assignment_modals .= "\t\t\t<div class=\"modal-header bcoem-admin-modal\">\n";
						$output_assignment_modals .= "\t\t\t\t<button type=\"button\" class=\"close\" data-dismiss=\"modal\" aria-label=\"Close\"><span aria-hidden=\"true\">&times;</span></button>\n";
						$output_assignment_modals .= "\t\t\t\t<h4 class=\"modal-title\" id=\"assignment-modal-label-".$row_brewer['uid']."\">Assignment(s) for ".h($brewer_tooltip_display_name)."</h4>\n";
						$output_assignment_modals .= "\t\t\t</div><!-- ./modal-header -->\n";
						$output_assignment_modals .= "\t\t\t<div class=\"modal-body\">\n";
						$output_assignment_modals .= "\t\t\t\t".$assignment_modal_body."\n";
						$output_assignment_modals .= "\t\t\t</div><!-- ./modal-body -->\n";
						$output_assignment_modals .= "\t\t\t<div class=\"modal-footer\">\n";
						$output_assignment_modals .= "\t\t\t\t<button type=\"button\" class=\"btn btn-danger\" data-dismiss=\"modal\">Close</button>\n";
						$output_assignment_modals .= "\t\t\t</div><!-- ./modal-footer -->\n";
						$output_assignment_modals .= "\t\t</div><!-- ./modal-content -->\n";
						$output_assignment_modals .= "\t</div><!-- ./modal-dialog -->\n";
						$output_assignment_modals .= "</div><!-- ./modal -->\n";
					}

				}
			
			}

			$output_datatables_body .= "<tr>";

			$output_datatables_body .= "<td>";
			$output_datatables_body .= "<a name='".$row_brewer['uid']."'></a>";
			$output_datatables_body .= $row_brewer['brewerLastName'].", ".$row_brewer['brewerFirstName'];
			if (($dbTable == "default") && ($row_brewer['userLevel'] == 0)) $output_datatables_body .= " <i class=\"fa fa-sm fa-lock text-danger\"></i>";
			elseif (($dbTable == "default") && ($row_brewer['userLevel'] == 1)) $output_datatables_body .= " <i class=\"fa fa-sm fa-lock text-warning\"></i>";
			if ($action != "print") $output_datatables_body .= "<br><small>".$row_brewer['brewerCity'].", ".$row_brewer['brewerState']."</small>";
			$output_datatables_body .= "</td>";

			$output_datatables_body .= "<td>";
			if (($dbTable == "default") && ($row_brewer['userLevel'] == 0))	$output_datatables_body .= "Top-Level Admin";
			elseif (($dbTable == "default") && ($row_brewer['userLevel'] == 1))	$output_datatables_body .= "Admin";
			else $output_datatables_body .= "Participant";
			if (($dbTable == "default") && ($row_brewer['userLevel'] < 2) && ($row_brewer['userAdminObfuscate'] == 0)) $output_datatables_body .= " <i class=\"fa fa-sm fa-eye\" data-toggle=\"tooltip\" data-placement=\"top\" title=\"".h($brewer_tooltip_display_name)." can view Judging Numbers - edit their user level to change.\"></i>";
			else  $output_datatables_body .= " <i class=\"fa fa-sm fa-eye-slash\" data-toggle=\"tooltip\" data-placement=\"top\" title=\"".h($brewer_tooltip_display_name)." CANNOT view Judging Numbers - edit their user level to change.\"></i>";
			$output_datatables_body .= "</td>";

			if ($action == "print") {
				$output_datatables_body .= "<td nowrap>";
				$output_datatables_body .= "<small>".h($row_brewer['brewerEmail'])."</small><br>";
				if ($row_brewer['brewerPhone1'] != "") {
					if ($us_phone) $output_datatables_body .= h(format_phone_us($row_brewer['brewerPhone1']))." (1)<br>";
					else $output_datatables_body .= h($row_brewer['brewerPhone1'])." (1)<br>";
				}
				if ($row_brewer['brewerPhone2'] != "") {
					if ($us_phone) $output_datatables_body .= h(format_phone_us($row_brewer['brewerPhone2']))." (2)<br>";
					else $output_datatables_body .= h($row_brewer['brewerPhone2'])." (2)<br>";
				}
				$output_datatables_body .= "</td>";
			}

			if ($pro_edition == 1) $output_datatables_body .= "<td>";
			else  $output_datatables_body .= "<td class=\"".$output_hide_print."\">";
			if (($totalRows_judging > 0) && (($filter == "judges") || ($filter == "stewards"))) {
				if ($filter == "judges") $exploder = $row_brewer['brewerJudgeLocation'];
				if ($filter == "stewards") $exploder = $row_brewer['brewerStewardLocation'];
				$a = explode(",",$exploder);
				$output = "";
				if ($exploder != "") {
					sort($a);
					foreach ($a as $value) {
						if ($value != "") {
							$b = substr($value, 2);
							// Pulled from the batched locations map above instead of a fresh
							// judging_location_avail() query per location - mirrors its
							// method==0 (default) branch exactly.
							$loc_row_avail_p = $judging_locations_by_id_p[$b] ?? null;
							if (($loc_row_avail_p) && (substr($value, 0, 1) == "Y") && (!empty($loc_row_avail_p['judgingLocName'])) && ($loc_row_avail_p['judgingLocType'] < 2)) $output .= $loc_row_avail_p['judgingLocName']."<br>";
							}
						}
					}
				$output = rtrim($output,"<br>");
				$output_datatables_body .= $output;
			}
			else {
				if ($pro_edition == 1) $output_datatables_body .= $row_brewer['brewerBreweryName'];
				else $output_datatables_body .= $row_brewer['brewerClubs'];
			}
			$output_datatables_body .= "</td>";

			if ($filter == "default") {
				$output_datatables_body .= "<td class=\"".$output_hide_print."\">";
				if ($row_brewer['brewerSteward'] == "Y") {
						if ($action == "print") $output_datatables_body .= "Y";
						else $output_datatables_body .= "<span class=\"fa fa-lg fa-check text-success\"></span>";
					}
					if ($row_brewer['brewerSteward'] == "N") {
						if ($action == "print") $output_datatables_body .= "N";
						else $output_datatables_body .= "<span class=\"fa fa-lg fa-times text-danger\"></span>";
					}
				$output_datatables_body .= "</td>";
				$output_datatables_body .= "<td class=\"".$output_hide_print."\">";
				if ($row_brewer['brewerJudge'] == "Y") {
						if ($action == "print") $output_datatables_body .= "Y";
						else $output_datatables_body .= "<span class=\"fa fa-lg fa-check text-success\"></span>";
					}
					if ($row_brewer['brewerJudge'] == "N") {
						if ($action == "print") $output_datatables_body .= "N";
						else $output_datatables_body .= "<span class=\"fa fa-lg fa-times text-danger\"></span>";
					}
				$output_datatables_body .= "</td>";

			}

			$output_datatables_body .= "<td>";
			
			if (!empty($brewer_assignment)) {
				
				if (((!$archive_display) && ((strpos($brewer_assignment,"Judge") !== false) || (strpos($brewer_assignment,"Steward") !== false))) && ($filter != "judges") && ($filter != "stewards")) {
					$output_datatables_body .= "<button type=\"button\" class=\"btn btn-link\" style=\"margin:0; padding:0;\" data-toggle=\"modal\" data-target=\"#assignment-modal-".$row_brewer['uid']."\">".ucwords($brewer_assignment)."</button>";
				}
				else {
					$output_datatables_body .= ucwords($brewer_assignment);
				}
				
			}

			$output_datatables_body .= "</td>";

			if ($filter != "default") {
				if ($filter == "judges") {
					$bjcp_rank = explode(",",$row_brewer['brewerJudgeRank']);
					$display_rank = bjcp_rank($bjcp_rank[0],1);

					if (((strpos($display_rank, "Level 0:") !== false)) && (($row_brewer['brewerJudgeMead'] == "Y") || ($row_brewer['brewerJudgeCider'] == "Y"))) $display_rank = "Level 3: Certified Cider or Mead Judge";

					$output_datatables_body .= "<td class=\"".$output_hide_print."\">".$row_brewer['brewerJudgeID']."</td>";
					$output_datatables_body .= "<td>".$display_rank;
					$output_datatables_body .= "<small>";
					if ($row_brewer['brewerJudgeMead'] == "Y") $output_datatables_body .= "<br />Certified Mead Judge";
					if ($row_brewer['brewerJudgeCider'] == "Y") $output_datatables_body .= "<br />Certified Cider Judge";
					if (!empty($bjcp_rank[1])) {
						$output_datatables_body .= designations($row_brewer['brewerJudgeRank'],$bjcp_rank[0]);
					}
					$output_datatables_body .= "</small>";
					$output_datatables_body .= "</td>";

				}
				if ($filter == "judges") $output_datatables_body .= "<td>".rtrim($table_assign_judge,",&nbsp;")."</td>";
				if ($filter == "stewards") $output_datatables_body .= "<td>".rtrim($table_assign_steward,",&nbsp;")."</td>";
				$output_datatables_body .= "<td class=\"".$output_hide_print."\">".$judge_entries_batched_p($row_brewer['uid'])."</td>";

			}


			// Pulled from the batched schema-check + lookup above instead of a fresh
			// date_created() call (with its own information_schema probe) per row.
			$date_created_result_p = "&nbsp;";
			if (($date_created_exists_p) && (array_key_exists($row_brewer['uid'], $user_created_by_id_p)) && ($user_created_by_id_p[$row_brewer['uid']] != "")) {
				$date_created_result_p = "<span class=\"hidden\">".strtotime($user_created_by_id_p[$row_brewer['uid']])."</span>".getTimeZoneDateTime($_SESSION['prefsTimeZone'], strtotime($user_created_by_id_p[$row_brewer['uid']]), $_SESSION['prefsDateFormat'], $_SESSION['prefsTimeFormat'], "short", "date-time-no-gmt");
			}
			$output_datatables_body .= "<td class=\"".$output_hide_print."\">".$date_created_result_p."</td>";

			if (($action != "print") && ($dbTable == "default")) {

				$output_datatables_body .= "<td>".$output_datatables_actions."</td>";

			}

		$output_datatables_body .= "</tr>";

		}

	}

} // end if ($totalRows_brewer > 0)

if ($dbTable != "default") $subtitle .= "<p>".$edition."</p>";

// ----------------------------------------- Presentation ------------------------------------------

// Display Top Of Page Elements (Subtitle, Primary Page Info, Nav, and Secondary Page Info)
echo $output_assignment_modals;
echo $output_user_question_modals;
?>

<?php if ($action == "print") { ?>
<h1><?php echo h($_SESSION['contestName'])." ".$subtitle; ?></h1>
<?php } else { ?>
<p class="lead"><?php echo h($_SESSION['contestName'])." ".$subtitle; ?></p>
<?php } ?>
<?php if ($action !="print") { ?>
<div class="bcoem-admin-element hidden-print">
	<div class="row">
    	<div class="col-lg-10 col-md-8 col-sm-12 col-xs-12">
	<?php if ($dbTable != "default") { ?>
    <!-- Archives Button -->
	<div class="btn-group" role="group" aria-label="...">
        <a class="btn btn-default" href="<?php echo $base_url; ?>index.php?section=admin&amp;go=archive"><span class="fa fa-arrow-circle-left"></span> Archives</a>
    </div><!-- ./button group -->
	<?php } ?>
    <?php if ($dbTable == "default") { ?>
    <?php if ($filter != "default") { ?>
    <!-- All Participants Button -->
	<div class="btn-group" role="group" aria-label="...">
        <a class="btn btn-default" href="<?php echo $base_url; ?>index.php?section=admin&amp;go=participants"><span class="fa fa-arrow-circle-left"></span> All Participants</a>
    </div><!-- ./button group -->
	<?php } ?>

    <!-- View Participants Dropdown -->
    <div class="btn-group" role="group">
		<button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
		<span class="fa fa-eye"></span> View...
		<span class="caret"></span>
		</button>
		<ul class="dropdown-menu">
        	<?php if ($filter != "default") { ?>
			<li class="small"><a href="<?php echo $base_url; ?>index.php?section=admin&amp;go=participants">All Participants</a></li>
            <?php } ?>
            <?php if (($filter != "judges") || (($filter == "judges") && ($id != "default"))) { ?>
			<li class="small"><a href="<?php echo $base_url; ?>index.php?section=admin&amp;go=participants&amp;filter=judges">Available Judges</a><li>
            <?php } ?>
            <?php if (($filter != "stewards") || (($filter == "stewards") && ($id != "default"))) { ?>
			<li class="small"><a href="<?php echo $base_url; ?>index.php?section=admin&amp;go=participants&amp;filter=stewards">Available Stewards</a><li>
            <?php } ?>
            <?php if (($filter != "with_entries") || (($filter == "stewards") && ($id != "default"))) { ?>
			<li class="small"><a href="<?php echo $base_url; ?>index.php?section=admin&amp;go=participants&amp;filter=with_entries">Participants with Entries</a><li>
            <?php } ?>
		</ul>
	</div><!-- ./button group -->
    <!-- Add Participants Dropdown -->
    <div class="btn-group" role="group">
		<button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
		<span class="fa fa-plus-circle"></span> Register...
		<span class="caret"></span>
		</button>
		<ul class="dropdown-menu">
			<li class="small"><a href="<?php echo $base_url; ?>index.php?section=admin&amp;go=entrant&amp;action=register">A Participant</a></li>
			<li class="small"><a href="<?php echo $base_url; ?>index.php?section=admin&amp;go=judge&amp;action=register">A Judge (Standard)</a><li>
			<li class="small"><a href="<?php echo $base_url; ?>index.php?section=admin&amp;go=steward&amp;action=register">A Steward (Standard)</a><li>
      <li class="small"><a href="<?php echo $base_url; ?>index.php?section=admin&amp;go=judge&amp;action=register&amp;view=quick">A Judge (Quick)</a><li>
     	<li class="small"><a href="<?php echo $base_url; ?>index.php?section=admin&amp;go=steward&amp;action=register&amp;view=quick">A Steward (Quick)</a><li>
		</ul>
	</div>
    <?php if (($action != "add") && ($dbTable == "default")) { ?>
    <!-- Assign/Unassign Judges/Stewards Dropdown -->
	<div class="btn-group" role="group">
		<button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
		<span class="fa fa-check-circle"></span> Assign/Unassign...
		<span class="caret"></span>
		</button>
		<ul class="dropdown-menu">
			<li class="small"><a href="<?php echo $base_url; ?>index.php?section=admin&amp;action=assign&amp;go=judging&amp;filter=judges">Judges</a></li>
			<li class="small"><a href="<?php echo $base_url; ?>index.php?section=admin&amp;action=assign&amp;go=judging&amp;filter=bos">BOS Judges</a><li>
			<li class="small"><a href="<?php echo $base_url; ?>index.php?section=admin&amp;action=assign&amp;go=judging&amp;filter=stewards">Stewards</a><li>
			<li class="small"><a href="<?php echo $base_url; ?>index.php?section=admin&amp;action=assign&amp;go=judging&amp;filter=staff">Staff</a><li>
			<li class="small"><a href="<?php echo $base_url; ?>index.php?section=admin&amp;action=assign&amp;go=judging_tables">Judges/Stewards to Tables</a><li>
		</ul>
	</div><!-- ./button group -->
    <?php } ?>
    <!-- Print This List Dropdown -->
	<div class="btn-group hidden-xs hidden-sm hidden-md" role="group">
		<button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
		<span class="fa fa-print"></span> Print Current View...
		<span class="caret"></span>
		</button>
		<ul class="dropdown-menu">
        	<?php if ($filter == "default") { ?>
        	<li class="small"><a data-fancybox data-type="iframe" class="modal-window-link hide-loader" href="<?php echo $base_url; ?>includes/output.inc.php?section=admin&amp;go=participants&amp;action=print&amp;view=default&amp;psort=brewer_name">By Last Name</a></li>
			<?php if ($pro_edition == 0) { ?>
			<li class="small"><a data-fancybox data-type="iframe" class="modal-window-link hide-loader" href="<?php echo $base_url; ?>includes/output.inc.php?section=admin&amp;go=participants&amp;action=print&amp;view=default&amp;psort=club">By Club</a><li>
            <?php } ?>
            <?php if ($pro_edition == 1) { ?>
            <li class="small"><a data-fancybox data-type="iframe" class="modal-window-link hide-loader" href="<?php echo $base_url; ?>includes/output.inc.php?section=admin&amp;go=participants&amp;action=print&amp;view=default&amp;psort=organization">By Organization Name</a></li>
            <?php } ?>
            <?php } ?>
            <?php if ($filter == "with_entries"){ ?>
            <?php if ($pro_edition == 1) $with_entries_name = "By Organization Name"; else $with_entries_name = "By Entrant Last Name"; ?>
            <li class="small"><a data-fancybox data-type="iframe" class="modal-window-link hide-loader" href="<?php echo $base_url; ?>includes/output.inc.php?section=admin&amp;go=participants&amp;action=print&amp;view=default&amp;filter=with_entries"><?php echo $with_entries_name; ?></a><li>
            <?php } ?>
            <?php if ($filter == "judges") { ?>
			<li class="small"><a data-fancybox data-type="iframe" class="modal-window-link hide-loader" href="<?php echo $base_url; ?>includes/output.inc.php?section=admin&amp;go=participants&amp;filter=judges&amp;action=print&amp;view=default&amp;psort=judge_id">By Judge ID</a><li>
            <li class="small"><a data-fancybox data-type="iframe" class="modal-window-link hide-loader" href="<?php echo $base_url; ?>includes/output.inc.php?section=admin&amp;go=participants&amp;filter=judges&amp;action=print&amp;view=default&amp;psort=judge_rank">By Judge Rank</a><li>
            <?php } ?>
            <?php if ($filter == "stewards") { ?>
			<li class="small"><a data-fancybox data-type="iframe" class="modal-window-link hide-loader" href="<?php echo $base_url; ?>includes/output.inc.php?section=admin&amp;go=participants&amp;filter=stewards&amp;action=print&amp;view=default">By Last Name</a><li>
            <?php } ?>
		</ul>
	</div><!-- ./button group -->
    <?php if ($dbTable != "default") { ?>
    <!-- Print Participants Dropdown -->
    <div class="btn-group" role="group">
		<button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
		<span class="fa fa-print"></span> Print All...
		<span class="caret"></span>
		</button>
		<ul class="dropdown-menu">
			<li class="small"><a data-fancybox data-type="iframe" class="modal-window-link hide-loader" href="<?php echo $base_url; ?>includes/output.inc.php?section=admin&amp;go=participants&amp;action=print&amp;view=default&amp;dbTable=<?php echo $dbTable; ?>&amp;psort=brewer_name">By Last Name</a></li>
			<?php if ($pro_edition == 0) { ?>
			<li class="small"><a data-fancybox data-type="iframe" class="modal-window-link hide-loader" href="<?php echo $base_url; ?>includes/output.inc.php?section=admin&amp;go=participants&amp;action=print&amp;view=default&amp;dbTable=<?php echo $dbTable; ?>&amp;psort=club">By Club</a><li>
            <?php } ?>
            <?php if ($pro_edition == 1) { ?>
            <li class="small"><a data-fancybox data-type="iframe" class="modal-window-link hide-loader" href="<?php echo $base_url; ?>includes/output.inc.php?section=admin&amp;go=participants&amp;action=print&amp;view=default&amp;dbTable=<?php echo $dbTable; ?>&amp;psort=organization">By Organization Name</a></li>
            <?php } ?>
            <?php if ($filter == "judges") { ?>
			<li class="small"><a data-fancybox data-type="iframe" class="modal-window-link hide-loader" href="<?php echo $base_url; ?>includes/output.inc.php?section=admin&amp;go=participants&amp;filter=judges&amp;action=print&amp;view=default&amp;dbTable=<?php echo $dbTable; ?>&amp;psort=judge_id">By Judge ID</a><li>
            <li class="small"><a data-fancybox data-type="iframe" class="modal-window-link hide-loader" href="<?php echo $base_url; ?>includes/output.inc.php?section=admin&amp;go=participant&amp;filter=judgess&amp;action=print&amp;view=default&amp;dbTable=<?php echo $dbTable; ?>&amp;psort=judge_rank">By Judge Rank</a><li>
            <?php } ?>
		</ul>
	</div><!-- ./button group -->
    <?php } ?>
    <?php $all_email_display = implode(", ",array_unique($all_email_display));
	if (!empty($all_email_display))	{ ?>

	<!-- All Participant Email Addresses Modal -->
	   <div class="btn-group hidden-xs hidden-sm" role="group" aria-label="...">
			<button type="button" class="btn btn-info" data-toggle="modal" data-target="#allEmailModal">
				  All <?php echo ucwords($subtitle); ?> Email Addresses
				</button>
		</div><!-- ./button group -->
		<!-- Modal -->
		<div class="modal fade" id="allEmailModal" tabindex="-1" role="dialog" aria-labelledby="allEmailModalLabel">
			<div class="modal-dialog" role="document">
				<div class="modal-content">
					<div class="modal-header bcoem-admin-modal">
						<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
						<h4 class="modal-title" id="allEmailModalLabel">Participant Email Addresses</h4>
					</div>
					<div class="modal-body">
						<p>Copy and paste the list below into your favorite email program.</p>
						<textarea class="form-control" rows="8"><?php echo ltrim($all_email_display," ")
						?></textarea>
					</div>
					<div class="modal-footer">
					<button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
				</div>
				</div>
			</div>
		</div><!-- ./modal -->

	<?php } ?>
    </div><!-- ./ col -->
    <div class="col-lg-2 col-md-4 col-sm-12 col-xs-12">
        <!-- Participant status modal -->
        <div class="btn-group pull-right hidden-xs" role="group" aria-label="...">
            <div class="btn-group" role="group">
                <button type="button" class="btn btn-success" data-toggle="modal" data-target="#participantStatusModal">
                 Participant Status
                </button>
            </div>
        </div>
        <!-- Modal -->
        <div class="modal fade" id="participantStatusModal" tabindex="-1" role="dialog" aria-labelledby="participantStatusModalLabel">
            <div class="modal-dialog modal-sm" role="document">
                <div class="modal-content">
                    <div class="modal-header bcoem-admin-modal">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                        <h4 class="modal-title" id="participantStatusModalLabel">Participant Status</h4>
                    </div>
                    <div class="modal-body">
                        <div class="bcoem-sidebar-panel">
                            <strong class="text-info">Participants</strong><span class="pull-right"><?php echo get_participant_count('default'); ?></span>
                        </div>
                        <div class="bcoem-sidebar-panel">
                            <strong class="text-info">Participants with Entries</strong><span class="pull-right"><?php echo $row_with_entries['count']; ?></span>
                        </div>
                        <div class="bcoem-sidebar-panel">
                            <strong class="text-info">Available Judges</strong><span class="pull-right"><?php echo get_participant_count('judge'); ?></span>
                        </div>
                        <div class="bcoem-sidebar-panel">
                            <strong class="text-info">Available Stewards</strong><span class="pull-right"><?php echo get_participant_count('steward'); ?></span>
                        </div>
                    </div>
                    <div class="modal-footer">
            			<button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
            		</div>
                </div>
            </div>
        </div><!-- ./modal -->
        <?php } ?>
	</div><!-- ./col -->
</div><!-- ./row -->
</div><!-- ./bcoem-admin-element -->
<?php } ?>
<?php
if (($action == "default") || ($action == "print")) {
if ($totalRows_brewer > 0) { ?>
	<script type="text/javascript" language="javascript">
	 $(document).ready(function() {
		$('#sortable').dataTable( {
			"bPaginate" : <?php echo $output_datatables_bPaginate; ?>,
			"sPaginationType" : "<?php echo $output_datatables_sPaginationType; ?>",
			"bLengthChange" : <?php echo $output_datatables_bLengthChange; ?>,
			"iDisplayLength" : <?php if ($action == "print") echo "99999"; else echo round($_SESSION['prefsRecordPaging']); ?>,
			"sDom": '<?php echo $output_datatables_sDom; ?>',
			"bStateSave" : <?php echo $output_datatables_bStateSave; ?>,
			"aaSorting": [<?php echo $output_datatables_aaSorting; ?>],
			"bProcessing" : <?php echo $output_datatables_bProcessing; ?>,
			"aoColumns": [ <?php echo $output_datatables_aoColumns; ?> ]
			} );
		} );
</script>
<table class="table table-responsive table-bordered table-striped dataTable" id="sortable">
<thead>
<?php echo $output_datatables_head; ?>
</thead>
<tbody>
<?php  echo $output_datatables_body; ?>
</tbody>
</table>
<?php } if ($totalRows_brewer == 0) { ?>

<?php
if ($filter == "default") 	echo "<div class='error'>There are no participants yet.</div>";
if ($filter == "judges") 	echo "<div class='error'>There are no judges available yet.</div>";
if ($filter == "stewards")  echo "<div class='error'>There are no stewards available yet.</div>";
}
} // end if ($action == "default")

if ($action == "add")  {
if ($filter == "default") { ?>
<form action="<?php echo $base_url; ?>includes/process.inc.php?action=add&amp;dbTable=<?php echo $users_db_table; ?>&amp;section=<?php echo $section; ?>&amp;go=<?php echo $go; ?>" method="POST" name="form1" id="form1">
<input type="hidden" name="user_session_token" value ="<?php if (isset($_SESSION['user_session_token'])) echo htmlspecialchars($_SESSION['user_session_token'], ENT_QUOTES, 'UTF-8'); ?>">
<table>
	<tr>
    	<td class="dataLabel">Email Address:</td>
    	<td class="data"><input name="user_name" id="user_name" type="text" class="submit" size="40" onBlur="checkAvailability()" onchange="AjaxFunction(this.value);" value="<?php if ($msg == "4") echo h($_SESSION['user_name']); ?>"><div id="msg_email">Email Format:</div><div id="username-status"></div></td>
        <td class="data" id="inf_email"><span class="required">Required</span></td>
  	</tr>
  	<tr>
    	<td class="dataLabel">Password:</td>
    	<td class="data"><input name="password" id="password" type="password" class="submit" size="25"></td>
        <td class="data"><span class="required">Required</span></td>
  	</tr>

  	<tr>
    	<td class="dataLabel">&nbsp;</td>
    	<td class="data"><input type="submit" class="button" value="Register"></td>
        <td class="data">&nbsp;</td>
  	</tr>
</table>
<input type="hidden" name="userLevel" value="2" />
<?php if (isset($_SERVER['HTTP_REFERER'])) { ?>
<input type="hidden" name="relocate" value="<?php echo relocate($_SERVER['HTTP_REFERER'],"default",$msg,$id); ?>">
<?php } else { ?>
<input type="hidden" name="relocate" value="<?php echo relocate($base_url."index.php?section=admin&go=participants","default",$msg,$id); ?>">
<?php } ?>
</form>
</div>
<?php }
if ($filter == "info") {
if (($action == "add") || (($action == "edit") && (($_SESSION['loginUsername'] == $row_brewer['brewerEmail'])) || ($_SESSION['userLevel'] <= "1")))  {
?>
<form action="<?php echo $base_url; ?>includes/process.inc.php?section=<?php echo "admin&amp;go=".$go."&amp;filter=".$filter; ?>&amp;action=<?php echo $action; ?>&amp;dbTable=<?php echo $brewer_db_table; ?>" method="POST" name="form1" id="form1">
<input type="hidden" name="user_session_token" value ="<?php if (isset($_SESSION['user_session_token'])) echo htmlspecialchars($_SESSION['user_session_token'], ENT_QUOTES, 'UTF-8'); ?>">
<table class="dataTable">
<tr>
      <td class="dataLabel" width="5%">Email:</td>
      <td class="data" width="20%"><?php echo $username; ?></td>
      <td class="data">&nbsp;</td>
</tr>
<tr>
      <td class="dataLabel" width="5%">First Name:</td>
      <td class="data" width="20%"><input type="text" id="brewerFirstName" name="brewerFirstName" value="<?php if ($action == "edit") echo $row_brewer['brewerFirstName']; ?>" size="32" maxlength="20"></td>
      <td class="data"><span class="required">Required</span></td>
</tr>
<tr>
      <td class="dataLabel">Last Name:</td>
      <td class="data"><input type="text" name="brewerLastName" value="<?php if ($action == "edit") echo $row_brewer['brewerLastName']; ?>" size="32"></td>
      <td class="data"><span class="required">Required</span></td>
</tr>
<tr>
      <td class="dataLabel">Street Address:</td>
      <td class="data"><input type="text" name="brewerAddress" value="<?php if ($action == "edit") echo $row_brewer['brewerAddress']; ?>" size="32"></td>
      <td class="data"><span class="required">Required</span></td>
</tr>
<tr>
      <td class="dataLabel">City:</td>
      <td class="data"><input type="text" name="brewerCity" value="<?php if ($action == "edit") echo $row_brewer['brewerCity']; ?>" size="32"></td>
      <td class="data"><span class="required">Required</span></td>
</tr>
<tr>
      <td class="dataLabel">State/Country:</td>
      <td class="data"><input type="text" name="brewerState" value="<?php if ($action == "edit") echo $row_brewer['brewerState']; ?>" size="32"></td>
      <td class="data"><span class="required">Required</span></td>
</tr>
<tr>
      <td class="dataLabel">Zip/Postal Code:</td>
      <td class="data"><input type="text" name="brewerZip" value="<?php if ($action == "edit") echo h($row_brewer['brewerZip']); ?>" size="32"></td>
      <td class="data"><span class="required">Required</span></td>
</tr>
<tr>
      <td class="dataLabel">Phone 1:</td>
      <td class="data"><input type="text" name="brewerPhone1" value="<?php if ($action == "edit") echo h($row_brewer['brewerPhone1']); ?>" size="32"></td>
      <td class="data"><span class="required">Required</span></td>
</tr>
<tr>
      <td class="dataLabel">Phone 2:</td>
      <td class="data"><input type="text" name="brewerPhone2" value="<?php if ($action == "edit") echo h($row_brewer['brewerPhone2']); ?>" size="32"></td>
      <td class="data">&nbsp;</td>
</tr>
<tr>
      <td class="dataLabel">Club Name (if appropriate):</td>
      <td class="data"><input type="text" name="brewerClubs" value="<?php if ($action == "edit") echo h($row_brewer['brewerClubs']); ?>" size="32" maxlength="200"></td>
      <td class="data">&nbsp;</td>
</tr>
<tr>
	  <td>&nbsp;</td>
      <td colspan="2" class="data"><input name="submit" type="submit" class="button" value="Submit Brewer Information" /></td>
    </tr>
</table>
<input name="brewerEmail" type="hidden" value="<?php echo $username; ?>" />
<input name="uid" type="hidden" value="<?php echo $row_user_level['id']; ?>" />
<input name="brewerJudge" type="hidden" value="N" />
<input name="brewerSteward" type="hidden" value="N" />
</form>
<?php
	  }
   }
}
?>