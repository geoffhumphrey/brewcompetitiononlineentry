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
$head_judge_options_ranked = array();
$head_judge_options_non = array();
$assigned_at_table = array();
$head_judge_choose = "";
$head_judge_id = array();
$unavailable = "";
$at_table = "";
$output_jquery_toggle = "";
$head_judge_name = "";
$entry_conflict_count = 0;
$entry_conflict = FALSE;

// Queued mode's Head Judge checkboxes are mutually exclusive (enforced via JS
// below once one is checked), but that only covers checkboxes rendered on this
// same page load - whichever judge already holds "HJ" needs to render with
// every OTHER row's checkbox already disabled, not just after a change event.
// Head Judge isn't round-scoped (see the comment above $row_judge_roles below),
// so one table-wide lookup here covers every row in the loop.
$existing_head_judge_bid = "";
if ($_SESSION['jPrefsQueued'] == "Y") {
  $db_conn->where('assignTable', $row_tables_edit['id']);
  $db_conn->where('assignRoles', '%HJ%', 'like');
  $row_existing_head_judge = $db_conn->getOne($prefix."judging_assignments", null, "bid");
  if ($row_existing_head_judge) $existing_head_judge_bid = $row_existing_head_judge['bid'];
}

// Build DataTables Header
$output_datatables_head .= "<tr>";
$output_datatables_head .= "<th width=\"15%\">Name</th>";
if ($filter == "judges") {
	$output_datatables_head .= "<th width=\"15%\">BJCP Rank</th>";
	$output_datatables_head .= "<th width=\"10%\" class=\"hidden-xs hidden-sm hidden-md\">BJCP #</th>";
	// $output_datatables_head .= "<th width=\"10%\">Comps Judged</th>";
	if ($_SESSION['jPrefsQueued'] == "Y") {
        $output_datatables_head .= "<th width=\"10%\">";
        $output_datatables_head .= "Judge Role(s) at Table";
        $output_datatables_head .= " <a href=\"#\" role=\"button\" data-html=\"true\" data-toggle=\"popover\" data-container=\"body\" data-trigger=\"focus\" data-placement=\"top\" title=\"Designate Judge Role(s) at the Table\" data-content=\"<p>Choose roles for certain judges at this table. <p><strong>Table Head Judge</strong> - <a class='hide-loader' href='https://www.bjcp.org/competitions/competition-handbook/' target='_blank'>According to the BJCP</a>, the head judge is a <em>single judge</em> responsible for reviewing all scores and paperwork for accuracy.</p><p><strong>Mini-BOS Judge</strong> - the Mini-BOS Judge is one of the judges at the table designated to participate in the <a class='hide-loader' href='http://dev.bjcp.org/wp-content/uploads/2011/11/MiniBOS.pdf' target='_blank'>Mini-BOS</a> to determine placing entries.</p><p><strong>Team Lead Judge</strong> - the designated lead among the judges grouped into the same Judging Team at this table. Generally, this is the ranking judge on the team.</p>\"> <span class=\"fa fa-lg fa-question-circle\"></span></a>";
        $output_datatables_head .= "</th>";
    }
}

if ($_SESSION['jPrefsQueued'] == "N") {
  // Only emit a header for rounds that actually have flights for this table -
  // mirrors the table_round() gate the body loop uses below to decide whether
  // to emit a <td>. Rounds aren't guaranteed to be populated contiguously from
  // 1 (e.g. a table's only flight can be round 2 with no round 1 at all), so
  // assuming every round up to the max exists left a stray header with no
  // matching cell, shifting every row's real content one column to the left.
  for($i=1; $i<$row_flights['flightRound']+1; $i++) {
    if (table_round($row_tables_edit['id'],$i)) {
      $output_datatables_head .= "<th>Round ".$i."</th>";
    }
  }
}

if ($_SESSION['jPrefsQueued'] == "Y") {
  $output_datatables_head .= "<th>Round ".$row_flights['flightRound']."</th>";
}

$output_datatables_head .= "</tr>";

$available_count = 0;
$total_count = 0;
$table_styles = explode(",",$row_tables_edit['tableStyles']);
$co_brewers_table = array();
$industry_affliations = array();

$styles_db_table = $prefix."styles";

foreach ($table_styles as $table_style) {

  $db_conn->where ("id", $table_style);
  $db_conn->returnType = "array";
  $row_style = $db_conn->getOne ($styles_db_table, "brewStyleGroup,brewStyleNum");

  if ($_SESSION['prefsProEdition'] == 0) {

    $db_conn->where ("brewCoBrewer", NULL, 'IS NOT');
    $db_conn->where ("brewCategorySort", $row_style['brewStyleGroup']);
    $db_conn->where ("brewSubCategory", $row_style['brewStyleNum']);
    if ($_SESSION['jPrefsTablePlanning'] == 0) $db_conn->where ("brewReceived", 1);
    $db_conn->returnType = "array"; 
    $row_co_brewers = $db_conn->get($prefix."brewing");
    $totalRows_co_brewers = $db_conn->count;

    if ($totalRows_co_brewers > 0) {
      
      foreach ($row_co_brewers as $row_co_brewers) {
        $co_brewers_table[] = $row_co_brewers['brewCoBrewer'];
      }
    
    }

  }

  if ($_SESSION['prefsProEdition'] == 1) $sql = sprintf("SELECT a.brewBrewerID, b.brewerBreweryName FROM %s a, %s b WHERE a.brewBrewerID = b.uid AND b.brewerBreweryName IS NOT NULL AND brewCategorySort=? AND brewSubCategory=?",$prefix."brewing", $prefix."brewer");
  else $sql = sprintf("SELECT a.brewBrewerID, b.brewerLastName, b.brewerFirstName FROM %s a, %s b WHERE a.brewBrewerID = b.uid AND b.brewerLastName IS NOT NULL AND brewCategorySort=? AND brewSubCategory=?",$prefix."brewing", $prefix."brewer");
  $db_conn->returnType = "array";
  $row_ind_aff = $db_conn->rawQuery($sql, array($row_style['brewStyleGroup'], $row_style['brewStyleNum']));
  $totalRows_ind_aff = $db_conn->count;

  if ($totalRows_ind_aff > 0) {
    
    foreach ($row_ind_aff as $row_ind_aff) {
      if ($_SESSION['prefsProEdition'] == 1) $industry_affliations[] = $row_ind_aff['brewerBreweryName'];
      else $industry_affliations[] = $row_ind_aff['brewerFirstName']." ".$row_ind_aff['brewerLastName'];
    }
  
  }
    
}

if ($totalRows_brewer > 0) {

  foreach ($rows_brewer as $row_brewer) {

    $table_location = "Y-".$row_tables_edit['tableLocation'];
  	$judge_info = judge_info($row_brewer['uid']);
  	$judge_info = explode("^",$judge_info);
  	$bjcp_rank = explode(",",$judge_info[5]);
    $rank_display = bjcp_rank($bjcp_rank[0],1);

    $random = random_generator(8,2);
    
    if (((strpos($rank_display, "Level 0:") !== false)) && (($judge_info[4] == "Y") || ($judge_info[12] == "Y"))) $rank_display = "Level 3: Certified Cider or Mead Judge";

  	$display_rank = "<strong>".$rank_display."</strong>";
    $rank_number = preg_replace('/[^0-9]/','',$display_rank);
    //$rank_number = filter_var($display_rank,FILTER_SANITIZE_NUMBER_FLOAT);
    
    $co_brewer_flag = FALSE;
    $ind_aff_flag = FALSE;
    $ind_aff_0 = array();
    $ind_aff_1 = array();
    $ind_aff_2 = array();
    $ind_aff_3 = array();
    
    if ($_SESSION['prefsProEdition'] == 0) {
      $cb_ct = 0;
      $cb_list = array();
      foreach ($co_brewers_table as $cb) {
         if (strpos($cb, $judge_info[1]) !== false) $cb_ct +=1;
         if (!empty($cb)) $cb_list[] = $cb;
      }
      if ($cb_ct > 0) $co_brewer_flag = TRUE;
    }

    if (!empty($judge_info[13])) {

      $ind_aff_0 = json_decode($judge_info[13],true);
      if ((isset($ind_aff_0['affilliated'])) && (!empty($ind_aff_0['affilliated']))) $ind_aff_1 = $ind_aff_0['affilliated'];
      if ((isset($ind_aff_0['affilliatedOther'])) && (!empty($ind_aff_0['affilliatedOther']))) $ind_aff_2 = $ind_aff_0['affilliatedOther'];
      $ind_aff_3 = array_merge($ind_aff_1,$ind_aff_2);
      $aff_ct = 0;
      foreach ($industry_affliations as $ind_aff_4) {
         if (in_array($ind_aff_4, $ind_aff_3)) $aff_ct +=1;
      }

      if ($aff_ct > 0) $ind_aff_flag = TRUE;
    
    }
              
  	$assign_row_color = "";
  	$flights_display = "";
  	$assign_flag = "";
    $assigned_at_this_table = FALSE;

    $checked_head_judge = "";
    $checked_team_lead = "";
    $checked_minibos_judge = "";
    $roles_previously_defined = 0;

    // Reset per-brewer, not per-page: without this, once any earlier brewer
    // in this loop has a real conflict, $entry_conflict stays TRUE (it's
    // only ever reassigned below when the CURRENT brewer has an assignment
    // row to check) and the next assigned brewer with no conflict of their
    // own gets silently unassigned from the table by the check further down.
    $entry_conflict = FALSE;

    $judge_roles_display = "";
    $head_judge_role_display = "";

    // Determine if this participant is assigned to this table
    $cols = array("id","bid");
    $db_conn->where ("assignTable", $row_tables_edit['id']);
    $db_conn->where ("bid", $row_brewer['uid']);
    $row_table_assignments = $db_conn->getOne ($prefix."judging_assignments");

    // If so, see if there are any entry conflicts
    if ($row_table_assignments) {
      $entry_conflict = entry_conflict($row_table_assignments['bid'],$row_tables_edit['tableStyles']);
    }

    // If the participant has an entry conflict, unassign them from the table.
    // Increase the entry_conflict_count var
    if ($entry_conflict) {
      $entry_conflict_count++;
      $update_table = $prefix."judging_assignments";
      $db_conn->where ('bid', $row_brewer['uid']);
      $db_conn->where ('assignTable', $row_tables_edit['id']);
      $result = $db_conn->delete ($update_table);
    }

    // Get role from judging_assignments table. Not scoped to a specific
    // round: the Head Judge save (process_judging_assignments.inc.php)
    // updates assignRoles for every row matching bid+assignTable regardless
    // of assignRound, so checking a single round here (previously
    // $row_flights['flightRound'] - the round of this table's
    // highest-numbered flight, not necessarily the judge's own round) could
    // miss the very row that was just set to 'HJ', leaving the dropdown
    // showing no selection even though the designation is saved correctly.
    $db_conn->where ("bid", $row_brewer['uid']);
    $db_conn->where ("assignTable", $row_tables_edit['id']);
    $row_judge_roles = $db_conn->getOne ($prefix."judging_assignments", null, "assignRoles");
  
    if ($_SESSION['jPrefsQueued'] == "N") {

        if ($rank_number >= 2) {
          $head_judge_options_ranked[] = array(
            "uid" => $row_brewer['uid'],
            "first_name" => $row_brewer['brewerFirstName'],
            "last_name" => $row_brewer['brewerLastName'],
            "rank" => $rank_display
          );
        }
        
        if ($rank_number < 2) {
          $head_judge_options_non[] = array(
            "uid" => $row_brewer['uid'],
            "first_name" => $row_brewer['brewerFirstName'],
            "last_name" => $row_brewer['brewerLastName'],
            "rank" => $rank_display
          );
        }
        
        $head_judge_role_display = "<div id=\"hj-display-".$row_brewer['uid']."\" class=\"text-primary hj-display hj-select-display\"><i class=\"fa fa-gavel\"></i> ".$label_head_judge."</div>";

        if (!empty($row_judge_roles['assignRoles'])) {
          if (strpos($row_judge_roles['assignRoles'],"HJ") !== FALSE) {
            $head_judge_id[] = $row_brewer['uid'];
            $head_judge_name = $row_brewer['brewerLastName'].", ".$row_brewer['brewerFirstName']." (".$rank_display.")";
          }
          if (strpos($row_judge_roles['assignRoles'],"MBOS") !== FALSE) {
            $judge_roles_display .= "<div id=\"mbos-display-".$row_brewer['uid']."\" class=\"text-success\"><i class=\"fa fa-trophy\"></i> ".$label_mini_bos_judge."</div>";
          }
          if (strpos($row_judge_roles['assignRoles'],"TL") !== FALSE) {
            $judge_roles_display .= "<div id=\"tl-display-".$row_brewer['uid']."\" class=\"text-purple\"><i class=\"fa fa-star\"></i> Team Lead</div>";
          }

        }

      for($i=1; $i<$row_flights['flightRound']+1; $i++) {

        $random_dropdown = random_generator(6,2);

        if (table_round($row_tables_edit['id'],$i)) {

          $flights_display .= "<td>";

          if (at_table($row_brewer['uid'],$row_tables_edit['id'])) {
            $assigned_at_this_table = TRUE;
            $assigned_at_table[] = $row_brewer['uid'];
            $assign_row_color = "bg-orange text-orange";
            $assign_flag = "<div style=\"padding-bottom:15px;\"><span class=\"fa fa-lg fa-check\"></span> <strong>Assigned.</strong> Participant is assigned to this table/flight.</div>";
            
            //$rank_number = preg_replace("/[^0-9,.]/", "", $display_rank);
            if ($rank_number >= 2) $ranked += 1;
            if ($rank_number < 2) $nonranked += 1;
          }

          else {
            $judge_alert = judge_alert($i,$row_brewer['uid'],$row_tables_edit['id'],$location,$judge_info[2],$judge_info[3],$row_tables_edit['tableStyles'],$row_tables_edit['id'],$ind_aff_flag);
            $judge_alert = explode("|",$judge_alert);
            $assign_row_color = $judge_alert[0];
            $assign_flag = "<div style=\"padding-bottom:15px;\">".$judge_alert[1]."</div>";
          }

          
          $unavailable = unavailable($row_brewer['uid'],$row_tables_edit['tableLocation'],$i,$row_tables_edit['id']);
          $flights_display .= $assign_flag;
          $flights_display .= assign_to_table($row_tables_edit['id'],$row_brewer['uid'],$filter,$total_flights,$i,$location,$row_tables_edit['tableStyles'],$queued,$random_dropdown,$ind_aff_flag);

          // Mini-BOS Judge/Team Lead roles are a judge-only concept scoped per
          // table/round (same granularity as the judging_assignments row
          // itself) - not blanketed across rounds the way the Head Judge
          // choice (table-wide select, below) is.
          //
          // Team itself has no input here - in non-queued mode, judges who
          // share a Flight for this round are, by definition, judging the
          // same entries together, which is exactly what "Team" represents.
          // process_judging_assignments.inc.php derives assignTeam from
          // assignFlight automatically, so $existing_team (still read from
          // the DB here) reflects whatever Flight was last saved, and is used
          // below to gate both role checkboxes - neither role makes sense for
          // a judge not actually assigned to a flight this round, so both
          // stay disabled until one is picked (and reset client-side if the
          // Flight choice is cleared back to "Do Not Assign").
          if ($filter == "judges") {
            $db_conn->where('bid', $row_brewer['uid']);
            $db_conn->where('assignTable', $row_tables_edit['id']);
            $db_conn->where('assignRound', $i);
            $row_judge_team = $db_conn->getOne($prefix."judging_assignments", null, "assignTeam, assignRoles");
            $existing_team = ($row_judge_team) ? $row_judge_team['assignTeam'] : "";
            $existing_round_roles = ($row_judge_team) ? $row_judge_team['assignRoles'] : "";
            $roles_prev_defined_round = (!empty($existing_round_roles)) ? 1 : 0;
            $checked_minibos_round = ((!empty($existing_round_roles)) && (strpos($existing_round_roles,"MBOS") !== FALSE)) ? "CHECKED" : "";
            $checked_team_lead_round = ((!empty($existing_round_roles)) && (strpos($existing_round_roles,"TL") !== FALSE)) ? "CHECKED" : "";

            $flights_display .= "<input type=\"hidden\" name=\"rolesPrevDefined".$random_dropdown."\" value=\"".$roles_prev_defined_round."\">";
            // The Flight select is disabled by default whenever this judge already
            // has a conflicting assignment (see $needs_unassign in
            // lib/admin.lib.php's assign_to_table()), re-enabled only once its
            // "Unassign from their current assignment..." checkbox is checked - and
            // a disabled <select> isn't submitted at all. Without this, toggling
            // just the role checkboxes on an already-assigned judge (the common
            // case of adding a role after the fact, with no reason to touch the
            // Flight) would post no assignFlight value, and
            // process_judging_assignments.inc.php's "does this round actually have
            // a flight" check for Mini-BOS/Team Lead would read that as "no", silently
            // dropping the role. This hidden field survives the select being
            // disabled so that check can fall back to it.
            $flights_display .= "<input type=\"hidden\" name=\"flightPrevDefined".$random_dropdown."\" value=\"".(empty($existing_team) ? "0" : "1")."\">";
            $flights_display .= "<section style='margin-top:5px;'>Judge Role(s)<br>";
            $flights_display .= "<div class=\"checkbox\"><label><input name=\"minibos_judge".$random_dropdown."\" type=\"checkbox\" value=\"MBOS\" ".$checked_minibos_round." class=\"minibos-judge-checkbox\" data-team-suffix=\"".$random_dropdown."\" ".(empty($existing_team) ? "disabled" : "")." /> Mini-BOS Judge</label></div>";
            $flights_display .= "<div class=\"checkbox\" style=\"padding-left:5px;\"><label><input name=\"team_lead".$random_dropdown."\" type=\"checkbox\" value=\"TL\" ".$checked_team_lead_round." class=\"team-lead-checkbox\" data-team-suffix=\"".$random_dropdown."\" ".(empty($existing_team) ? "disabled" : "")." /> Flight/Team Lead Judge</label> <small class=\"text-muted\">(requires a Flight assignment)</small></div>";
            $flights_display .= "</section>";
          }

          $flights_display .= "</td>";

        }

      } // end for($i=1; $i<$row_flights['flightRound']+1; $i++) {

    } // end if ($_SESSION['jPrefsQueued'] == "N")

    if ($_SESSION['jPrefsQueued'] == "Y") {

      if ($row_judge_roles) {
          
          if (!empty($row_judge_roles['assignRoles'])) {
            $roles_previously_defined = 1;
          }

          if (strpos($row_judge_roles['assignRoles'],"HJ") !== FALSE) {
            $checked_head_judge = "CHECKED";
          }

          if (strpos($row_judge_roles['assignRoles'],"TL") !== FALSE) {
            $checked_team_lead = "CHECKED";
          }

          if (strpos($row_judge_roles['assignRoles'],"MBOS") !== FALSE) {
            $checked_minibos_judge = "CHECKED";
          }
      
      }

      $flights_display .= "<td>";

      if (at_table($row_brewer['uid'],$row_tables_edit['id'])) {
        $assigned_at_this_table = TRUE;
        $assigned_at_table[] = $row_brewer['uid'];
        $assign_row_color = "bg-orange text-orange";
        $assign_flag = "<span class=\"fa fa-lg fa-check\"></span> <strong>Assigned.</strong> Participant is assigned to this table/flight.";
        
        //$rank_number = preg_replace("/[^0-9,.]/", "", $display_rank);
        if ($rank_number >= 2) $ranked += 1;
        if ($rank_number < 2) $nonranked += 1;
      }

      else {
        $judge_alert = judge_alert($row_flights['flightRound'],$row_brewer['uid'],$row_tables_edit['id'],$location,$judge_info[2],$judge_info[3],$row_tables_edit['tableStyles'],$row_tables_edit['id'],$ind_aff_flag);
        $judge_alert = explode("|",$judge_alert);
        $assign_row_color = $judge_alert[0];
        $assign_flag = "<div style=\"padding-bottom:15px;\">".$judge_alert[1]."</div>";
      }

      $unavailable = unavailable($row_brewer['uid'],$row_tables_edit['tableLocation'],$row_flights['flightRound'],$row_tables_edit['id']);       
      $flights_display .= $assign_flag;
      $flights_display .= assign_to_table($row_tables_edit['id'],$row_brewer['uid'],$filter,$total_flights,$row_flights['flightRound'],$location,$row_tables_edit['tableStyles'],$queued,$random,$ind_aff_flag);
      $flights_display .= "</td>";

    } // end if ($_SESSION['jPrefsQueued'] == "Y") 

  	if ($judge_info[4] == "Y") $display_rank .= "<br /><em>Certified Mead Judge</em>";
    if ($judge_info[12] == "Y") $display_rank .= "<br /><em>Certified Cider Judge</em>";

  	if (!empty($bjcp_rank[1])) $display_rank .= "<em>".designations($judge_info[5],$bjcp_rank[0])."</em>";

  	if ($filter == "stewards") $locations = explode(",",$judge_info[7]);
  	else $locations = explode(",",$judge_info[8]);

  	/**
  	 * GitHub issue #1752: this gate used to be a plain in_array() check, so anyone
  	 * assigned to this table who has since become unavailable for its location (or
  	 * opted out of judging/stewarding entirely) was dropped from the list outright -
  	 * hiding both the stale assignment and the only UI that could remove it, forcing
  	 * a coordinator to re-register the person first just to be able to un-assign them.
  	 * $row_table_assignments (fetched above at ~line 210) already tells us whether
  	 * this person has an assignment row for this specific table, independent of their
  	 * current availability - admit them on that basis too, and flag them so the row
  	 * still visibly calls out that they're no longer eligible here.
  	 */
  	$ineligible_but_assigned = (!empty($row_table_assignments)) && (!in_array($table_location,$locations));

  	if ((in_array($table_location,$locations)) || (!empty($row_table_assignments))) {

        // Someone rendered only because they're stuck on an assignment they're no
        // longer eligible for isn't actually "available" - don't count them as such.
        if (!$ineligible_but_assigned) $total_count += 1;
        $output_datatables_body .= "<tr class=\"".$assign_row_color."\">\n";

      /*
       * Build Name, Rank, BJCP ID, and Role columns
       * for DataTables output.
       */

      // Name Column
  		$output_datatables_body .= "<td>";
  		$output_datatables_body .= "<a href=\"".$base_url."index.php?section=brewer&amp;go=admin&amp;action=edit&amp;filter=".$row_brewer['uid']."&amp;id=".$judge_info[11]."\" data-toggle=\"tooltip\" title=\"Edit ".h($judge_info[0])." ".h($judge_info[1])."&rsquo;s account info\">".h($judge_info[1]).", ".h($judge_info[0])."</a>";
      if ($ineligible_but_assigned) $output_datatables_body .= "<br><span class=\"label label-danger\" data-toggle=\"tooltip\" title=\"".h($judge_info[0])." ".h($judge_info[1])." is currently assigned to this table but is no longer available for this session - uncheck/unassign below or update their availability.\"><span class=\"fa fa-lg fa-fw fa-exclamation-triangle\"></span> No Longer Available - Unassign Now!</span>";
      //if ($ind_aff_flag) $output_datatables_body .= "<br>Affiliation Flag";
      if ($filter == "judges") {
        $output_datatables_body .= "<br><strong>Comps Judged:</strong> ";
        if (empty($judge_info[9])) $output_datatables_body .= "0";
        else $output_datatables_body .= $judge_info[9];
        if (!empty($head_judge_role_display)) $output_datatables_body .= $head_judge_role_display;
        if (!empty($judge_roles_display)) $output_datatables_body .= $judge_roles_display;
      }

      $output_datatables_body .= "<ul class='list-unstyled'>";

      if ($ind_aff_flag) {
        
          $judge_ind_aff = "";

          if (!empty($ind_aff_3)) {
            foreach ($ind_aff_3 as $value) {
                if (in_array($value,$industry_affliations)) $judge_ind_aff .= $value.", ";
            }
            $judge_ind_aff = trim($judge_ind_aff,", ");
          }

          if ($_SESSION['prefsProEdition'] == 1) $output_datatables_body .= "<li><span class=\"text-danger\"><i class=\"fa fas fa-exclamation-triangle\"></i> <strong>Affiliation conflict.</strong></span> Judge reports an industry affiliation with the following organization(s) that have entries at this table: <span class=\"text-danger\">".$judge_ind_aff."</span></li>"; 
          else $output_datatables_body .= "<li><span class=\"text-danger\"><i class=\"fa fas fa-exclamation-triangle\"></i> <strong>Brewing Team Conflict.</strong></span> Judge reports an affiliation with the following individuals or teams that have entries at this table: <span class=\"text-danger\">".$judge_ind_aff."</span></li>";
      }

      if (($co_brewer_flag) && ($assign_row_color != "bg-info text-info")) $output_datatables_body .= "<li><span class=\"text-danger\"><i class=\"fa fas fa-exclamation-triangle\"></i> <strong>Possible Co-Brewer conflict (name match).</strong> <small>Verify with full co-brewer names listed above.</small></span></li>"; 

      $output_datatables_body .= "</ul>";
      
      if (!empty($judge_info[10])) $output_datatables_body .= "<br><span class=\"text-danger\"><strong>Notes:</strong> ".$judge_info[10]."</strong>";
  		$output_datatables_body .= "</td>";

  		if ($filter == "judges") {

        // Rank Column
        $output_datatables_body .= "<td>".$display_rank."</td>";

        // BJCP ID Column
        $output_datatables_body .= "<td class=\"hidden-xs hidden-sm hidden-md\">";
        if (($judge_info[6] != "") && ($judge_info[6] != "0")) $output_datatables_body .= strtoupper($judge_info[6]);
        else $output_datatables_body .= "N/A";
        $output_datatables_body .= "</td>";

        if ($_SESSION['jPrefsQueued'] == "Y") {
          
          
          // Activate for Roles
          // Build jQuery function vars
          if ($assigned_at_this_table) $output_jquery_toggle .= "$(\"#toggleRoles".$random."\").show();\n";
          else $output_jquery_toggle .= "$(\"#toggleRoles".$random."\").hide();\n";

          $output_jquery_toggle .= "
          $(\"input[name='assignRound".$random."']\").click(function() {
              if ($(this).val() == \"".$row_flights['flightRound']."\") {
                  $(\"#toggleRoles".$random."\").show();
              }
              else {
                  $(\"#toggleRoles".$random."\").hide();
                  $(\"input[name='head_judge".$random."']\").prop(\"checked\", false);
                  $(\"input[name='minibos_judge".$random."']\").prop(\"checked\", false);
                  $(\"input[name='team_lead".$random."']\").prop(\"checked\", false).prop(\"disabled\", true);
                  $(\"select[name='assignTeam".$random."']\").val(\"\");
              }
          });\n
          ";

          // Team, scoped to this judge/table/round (same granularity as the
          // judging_assignments row itself) - not blanketed across rounds the
          // way $row_judge_roles (HJ/MBOS/TL pre-check state) is above. Looked
          // up before the role checkboxes below so the Team Lead checkbox's
          // initial disabled state (it requires a Team) can use it.
          $db_conn->where('bid', $row_brewer['uid']);
          $db_conn->where('assignTable', $row_tables_edit['id']);
          $db_conn->where('assignRound', $row_flights['flightRound']);
          $row_judge_team = $db_conn->getOne($prefix."judging_assignments", null, "assignTeam");
          $existing_team = ($row_judge_team) ? $row_judge_team['assignTeam'] : "";
          $team_previously_defined = (!empty($existing_team)) ? 1 : 0;

          $output_datatables_body .= "<td>";
          $output_datatables_body .= "<div id=\"toggleRoles".$random."\">";
          $output_datatables_body .= "<input type=\"hidden\" name=\"rolesPrevDefined".$random."\" value=\"".$roles_previously_defined."\">";
          $output_datatables_body .= "<div class=\"checkbox\">";
          $output_datatables_body .= "<label><input name=\"head_judge".$random."\" type=\"checkbox\" value=\"HJ\" ".$checked_head_judge." class=\"head-judge-checkbox\" ".(((!empty($existing_head_judge_bid)) && ($existing_head_judge_bid != $row_brewer['uid'])) ? "disabled" : "")." /> Table Head Judge</label>";
          $output_datatables_body .= "</div><br>";
          $output_datatables_body .= "<div class=\"checkbox\">";
          $output_datatables_body .= "<label><input name=\"minibos_judge".$random."\" type=\"checkbox\" value=\"MBOS\" ".$checked_minibos_judge." /> Mini-BOS Judge</label>";
          $output_datatables_body .= "</div>";
          $output_datatables_body .= "<div class=\"checkbox\">";
          $output_datatables_body .= "<label><input name=\"team_lead".$random."\" type=\"checkbox\" value=\"TL\" ".$checked_team_lead." class=\"team-lead-checkbox\" data-team-suffix=\"".$random."\" ".(empty($existing_team) ? "disabled" : "")." /> Team Lead Judge</label> </div>";

          $output_datatables_body .= "<input type=\"hidden\" name=\"teamPrevDefined".$random."\" value=\"".$team_previously_defined."\">";
          $output_datatables_body .= "<section style='margin-top:5px;'>Assign to Judging Team<br>".assign_team_dropdown($existing_team, $random)."</section>";

          $output_datatables_body .= "</div>";
          $output_datatables_body .= "</td>";

        } // end if ($_SESSION['jPrefsQueued'] == "Y")

  		} // end if ($filter == "judges")

  		$modal_rank = $bjcp_rank[0];
  		if (empty($modal_rank)) $modal_rank = "Non-BJCP";

  		$at_table = at_table($row_brewer['uid'],$row_tables_edit['id']);

  		// Build Assigned Modal
  		if (($at_table) && ($unavailable)) {

  			$output_at_table_modal_body .= "<tr>\n";
  			$output_at_table_modal_body .= "<td class=\"small\">".$judge_info[1].", ".$judge_info[0]."</td>";
  			$output_at_table_modal_body .= "<td class=\"small\">".$modal_rank."</td>";
  			if ($filter == "judges") $output_at_table_modal_body .= "<td class=\"small\">".(!empty($existing_team) ? "Team ".h($existing_team) : "")."</td>";
  			if ($filter == "judges") {
  				$existing_roles_modal = ($_SESSION['jPrefsQueued'] == "Y") ? $row_judge_roles['assignRoles'] : $existing_round_roles;
  				$modal_role_labels = array();
  				if (!empty($existing_roles_modal)) {
  					if (strpos($existing_roles_modal,"HJ") !== FALSE) $modal_role_labels[] = $label_head_judge;
  					if (strpos($existing_roles_modal,"MBOS") !== FALSE) $modal_role_labels[] = $label_mini_bos_judge;
  					if (strpos($existing_roles_modal,"TL") !== FALSE) $modal_role_labels[] = "Team Lead";
  				}
  				$output_at_table_modal_body .= "<td class=\"small\">".h(implode(", ", $modal_role_labels))."</td>";
  			}
  			if ($_SESSION['jPrefsQueued'] == "N") $output_at_table_modal_body .= "<td class=\"small\">".$judge_info[14]."</td>";
  			if ($_SESSION['jPrefsQueued'] == "N") $output_at_table_modal_body .= "<td class=\"small\">".$judge_info[15]."</td>";
  			$output_at_table_modal_body .= "</tr>\n";

  		}

  		// Build Available Modal
  		if ((!$at_table) && (!$unavailable)) {
        $output_available_modal_body .= "<tr><td class=\"small\">".$judge_info[1].", ".$judge_info[0]."</td><td class=\"small\">".$modal_rank."</td></tr>";
        $available_count += 1;
      } 

  		$output_datatables_body .= $flights_display;
  		$output_datatables_body .= "</tr>\n";

  	} // end if (in_array($table_location,$locations))

  }

} // end if ($totalRows_brewer > 0)

$dashboard_link = build_public_url("evaluation","default","default","default",$sef,$base_url,"default");
$head_judge_explain = "<p>Choose the Head Judge for this table. <p>The Head Judge, <a class='hide-loader' href='https://www.bjcp.org/exam-certification/judge-procedures-manual/' target='_blank'>according to the BJCP</a>, is a <em>single judge</em> responsible for reviewing all scores and paperwork for accuracy.</p>";
if ($_SESSION['prefsEval'] == 1) $head_judge_explain .= "<p>Additionally, Head Judges confirm consensus scores and enter the placing entries into the system via their <a href='".$dashboard_link."'>Judging Dashboard</a> after all evaluations at the table have been submitted by judges.</p>";

$head_judge_options_ranked = array_unique($head_judge_options_ranked, SORT_REGULAR);
$head_judge_options_non = array_unique($head_judge_options_non, SORT_REGULAR);

?>
<script>
$(document).ready(function() {
  <?php if ($entry_conflict_count > 0) { ?>
  $('#unassigned-modal').modal('show');
  <?php } ?>
  // Team Lead requires a Team - keep its checkbox in sync with this row's
  // Team dropdown regardless of mode (queued: one per brewer; non-queued:
  // one per round), matched by the shared "data-team-suffix" value.
  $(document).on("change", "select[name^='assignTeam']", function() {
      var suffix = $(this).data("team-suffix");
      var $lead = $(".team-lead-checkbox[data-team-suffix='" + suffix + "']");
      if ($(this).val() === "") { $lead.prop("checked", false).prop("disabled", true); }
      else { $lead.prop("disabled", false); }
      enforceTeamLeadScope();
  });
  // Non-queued mode has no Team dropdown - Team is derived from Flight
  // (process_judging_assignments.inc.php), so Team Lead's "requires a Team"
  // rule (and Mini-BOS Judge, which likewise only makes sense for a judge
  // actually assigned to a flight this round) is enforced here against the
  // Flight select instead. "0" is the Flight select's "Do Not Assign"
  // option, equivalent to "no team".
  $(document).on("change", "select[name^='assignFlight']", function() {
      var suffix = $(this).data("team-suffix");
      var $roleCheckboxes = $(".team-lead-checkbox[data-team-suffix='" + suffix + "'], .minibos-judge-checkbox[data-team-suffix='" + suffix + "']");
      if ($(this).val() === "0") { $roleCheckboxes.prop("checked", false).prop("disabled", true); }
      else { $roleCheckboxes.prop("disabled", false); }
      enforceTeamLeadScope();
  });
  // Team Lead is only meant to designate one judge per Team (queued)/Flight
  // (non-queued), unlike Head Judge above which is table-wide - Team 1 and
  // Team 2 can each have their own Team Lead, so this groups by whatever
  // value the row's Team/Flight select currently resolves to (matched by
  // the shared "data-team-suffix") rather than disabling every other
  // checkbox on the page. Also scoped by Round: non-queued mode shows every
  // round for this table on one page, and Flight numbers restart at 1 each
  // round, so "Flight 1" in Round 1 and "Flight 1" in Round 2 are unrelated
  // and must not be treated as the same group (queued mode's whole page is
  // already one round, so this is a no-op there - every suffix resolves to
  // the same round value). Recomputed from scratch on every call so a
  // checkbox this rule disabled is freed again as soon as its Team/Flight
  // no longer collides with a checked Team Lead - the "requires a
  // Team/Flight" base rule above still wins (it runs first and leaves a
  // real signal behind: an empty/"0" select value) since this only ever
  // disables an *unchecked* box, never forces one enabled.
  function teamLeadGroupKey(suffix) {
      var round = $("input[name='assignRound" + suffix + "']").val();
      var groupValue = $("select[data-team-suffix='" + suffix + "']").val();
      if ((!groupValue) || (groupValue === "0")) return null;
      return round + "|" + groupValue;
  }
  function enforceTeamLeadScope() {
      var checkedGroups = {};
      $(".team-lead-checkbox:checked").each(function() {
          var key = teamLeadGroupKey($(this).data("team-suffix"));
          if (key) checkedGroups[key] = true;
      });
      $(".team-lead-checkbox").not(":checked").each(function() {
          var $cb = $(this);
          var key = teamLeadGroupKey($cb.data("team-suffix"));
          $cb.prop("disabled", (!key) || !!checkedGroups[key]);
      });
  }
  $(document).on("change", ".team-lead-checkbox", enforceTeamLeadScope);
  enforceTeamLeadScope();
  // Head Judge (queued mode) is a single-judge-per-table designation, but is
  // offered as one checkbox per judge rather than a dropdown - enforce
  // exclusivity by disabling every other checkbox once one is checked, and
  // releasing them again if it's unchecked.
  function enforceSingleHeadJudge($keep) {
      $(".head-judge-checkbox").not($keep).prop("checked", false).prop("disabled", true);
  }
  $(document).on("change", ".head-judge-checkbox", function() {
      if ($(this).is(":checked")) {
          enforceSingleHeadJudge($(this));
      } else {
          $(".head-judge-checkbox").prop("disabled", false);
      }
  });
  // Before this exclusivity existed, admins could check more than one Head
  // Judge - clean that up on load rather than just leaving the extras checked
  // AND disabled (which would hide the conflict instead of resolving it).
  // Prefer whichever one the server already disabled the others around (the
  // saved Head Judge of record); if none is enabled for some reason, fall
  // back to the first checked box found.
  var $savedHeadJudge = $(".head-judge-checkbox:checked").not(":disabled").first();
  if ($savedHeadJudge.length === 0) $savedHeadJudge = $(".head-judge-checkbox:checked").first();
  if ($savedHeadJudge.length > 0) enforceSingleHeadJudge($savedHeadJudge);
  <?php if ($_SESSION['jPrefsQueued'] == "Y") { echo $output_jquery_toggle; ?>
  <?php } else { ?>
  $(".assign-flight").change(function() {
      $(this).closest("section").find('.unassign-checkbox').prop('checked', true);
  });
  // A Flight select that starts disabled (judge already assigned here or
  // at another table this round - see $needs_unassign in
  // lib/admin.lib.php's assign_to_table()) only re-enables once its
  // "Unassign from their current assignment..." checkbox is checked,
  // forcing the explicit two-step move instead of a silent double-booking.
  $(document).on("change", ".unassign-checkbox", function() {
      var $select = $(this).closest("section").find(".assign-flight");
      if ($select.length === 0) return;
      // This checkbox only ever renders when the select needs it (see
      // $needs_unassign in lib/admin.lib.php's assign_to_table()), so its
      // checked state alone decides the select's enabled state - checked
      // enables it, unchecked (including re-unchecking it later) disables
      // it again.
      $select.prop("disabled", !$(this).is(":checked"));
      // bootstrap-select (.selectpicker) renders its own button over the
      // real <select> - toggling .prop("disabled") on the hidden original
      // doesn't by itself update that button, so it has to be told to
      // re-sync or it stays visually/functionally stuck in its old state.
      $select.selectpicker("refresh");
  });
  <?php } ?>
});
</script>

<!-- Unassigned Judges/Stewards Modal -->
<div class="modal fade" id="unassigned-modal" tabindex="-1" role="dialog" aria-labelledby="unassigned-modal-label" data-backdrop="static">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h4 style="margin-bottom:0;" class="modal-title" id="unassigned-modal-label"><?php if ($filter == "judges") echo "Judge(s)"; if ($filter == "stewards") echo "Steward(s)"; ?> Un-Assigned</h4>
      </div>
      <div class="modal-body">
        <p>One or more <?php if ($filter == "judges") echo "judges"; if ($filter == "stewards") echo "stewards"; ?> were un-assigned from this table due to entry style conflicts.</p>
        <p>Those individuals are now unavailable to assign to this table.</p>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-success" data-dismiss="modal">I Understand</button>
      </div>
    </div><!-- /.modal-content -->
  </div><!-- /.modal-dialog -->
</div><!-- /.modal -->

<div class="bcoem-admin-element hidden-print">
	<div class="btn-group" role="group" aria-label="modals">
        <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
            Assign <?php if ($filter == "stewards") echo "Stewards"; else echo "Judges"; ?> to Another Table... <span class="caret"></span>
        </button>
        <ul class="dropdown-menu">
            <?php foreach ($rows_tables as $row_tables) { ?>
            <li class="small"><a href="<?php echo $base_url; ?>index.php?section=admin&amp;action=assign&amp;go=judging_tables&amp;filter=<?php echo $filter; ?>&amp;id=<?php echo $row_tables['id']; ?>"><?php echo "Table ".h($row_tables['tableNumber']).": ".h($row_tables['tableName']); ?></a></li>
            <?php } ?>
        </ul>
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
<h4>Assign <span class="text-capitalize"><?php echo $filter; ?></span> to Table <?php echo h($row_tables_edit['tableNumber'])." &ndash; ".h($row_tables_edit['tableName']); $entry_count = get_table_info(1,"count_total",$row_tables_edit['id'],$dbTable,"default"); echo " (".$entry_count." entries)"; ?><br><small><?php echo table_location($row_tables_edit['id'],$_SESSION['prefsDateFormat'],$_SESSION['prefsTimeZone'],$_SESSION['prefsTimeFormat'],"default"); ?></small></h4>
<!-- Table stats snapshot -->
<div class="well small">
  <div class="row">
    <div class="col-md-4">
      <strong>Total <?php echo ucwords($filter); ?> Available for This Session and Round:</strong> <?php echo $total_count; ?><br>
      <strong>Remaining <?php echo ucwords($filter); ?> Available for This Session and Round:</strong> <?php echo $available_count; ?>
    </div>
    <div class="col-md-4">
      <?php if ((!empty($output_at_table_modal_body)) && ($filter == "judges"))  { ?>
      <strong>Ranked Judges Assigned to this Table:</strong> <?php echo $ranked; ?><br>
      <strong>Non-ranked Judges Assigned to this Table:</strong> <?php echo $nonranked; ?><br>
      <?php } ?>
    </div>
    <div class="col-md-4">
      <strong>Number of Flights:</strong> <?php echo $row_flights['flightNumber']; ?>
      <?php if ($_SESSION['jPrefsQueued'] == "N") { ?>
      <ul class="list-unstyled">
        <?php for($c=1; $c<$row_flights['flightNumber']+1; $c++) {
        $flight_entry_count = flight_entry_count($row_tables_edit['id'], $c);
        ?>
        <li><?php echo "<strong>Flight ".$c.":</strong> ".$flight_entry_count." entries"; ?></li>
        <?php } ?>
      </ul>
      <?php } ?>
    </div>
  </div>
  <div class="row">
    <div class="col-md-12">
      <ul class="list-unstyled">
      <?php 
      
      if (!empty($cb_list)) {
        $cb_list = implode(", ", $cb_list);
        echo "<li><strong>Co-Brewer Names Associated with Entries at this Table:</strong> ".trim($cb_list,", ");
      }

      if (!empty($industry_affliations)) {
        $industry_affliations = implode(", ", array_unique($industry_affliations));
        if ($_SESSION['prefsProEdition'] == 1) echo "<li><strong>Organization(s) Associated with Entries at this Table:</strong> ". trim($industry_affliations,", ");
        else echo "<li><strong>Individuals and Teams with Entries at this Table:</strong> ". trim($industry_affliations,", ");
      } 

      ?>
    </ul>
    </div>
  </div>
</div>
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
        <?php if ($_SESSION['jPrefsQueued'] == "Y") { ?>
        // Activate for Roles
				{ "asSorting": [  ] },
        <?php } ?>
        <?php for($i=1; $i<$row_flights['flightRound']+1; $i++) {
			    if  (table_round($row_tables_edit['id'],$i)) {
				?>null,<?php } } ?>
				]
			} );
			<?php } ?>
			<?php if ($filter == "stewards") { ?>
			"aoColumns": [
				null,<?php for($i=1; $i<$row_flights['flightRound']+1; $i++) {
			    if  (table_round($row_tables_edit['id'],$i)) {
				?>null,<?php } } ?>
				]
	 		<?php } ?>
		});
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
				<?php if ($_SESSION['jPrefsQueued'] == "N") { ?>
				null
				<?php } ?>
				]
			});
		});
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
                <p>The following <?php echo $filter; ?> have not been assigned to a table at this location, in this round. There are <?php echo $available_count." ".$filter; ?> remaining of <?php echo $total_count; ?>.</p>
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
			null<?php if ($filter == "judges") { ?>,
			null,
			null<?php } ?><?php if ($_SESSION['jPrefsQueued'] == "N") { ?>,
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
                <h4 class="modal-title" id="atTableModalLabel"><span class="text-capitalize"><?php echo $filter; ?></span> Assigned to Table <?php echo h($row_tables_edit['tableNumber'])." &ndash; ".h($row_tables_edit['tableName']); $entry_count = get_table_info(1,"count_total",$row_tables_edit['id'],$dbTable,"default"); echo " (".$entry_count." entries)"; ?> </h4>
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
                    <?php if ($filter == "judges") { ?><th>Team</th><th>Role</th><?php } ?>
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
<input type="hidden" name="user_session_token" value ="<?php if (isset($_SESSION['user_session_token'])) echo htmlspecialchars($_SESSION['user_session_token'], ENT_QUOTES, 'UTF-8'); ?>">
<?php if ($_SESSION['jPrefsQueued'] == "N") { ?>
<script type='text/javascript'>
var hj_id = <?php if (!empty($head_judge_id)) echo max($head_judge_id); else echo "''"; ?>;

function update_hj_display() {
  var hj_id = $("#head-judge-select").val();
  $(".hj-select-display").hide();
  $("#hj-display-"+hj_id).show();
}

function show_hj_choose() {
  var options = $("#hj-choose").length;
  if (options > 0) {
    $("#head-judge-choose").show();
  }
  else {
    $("#head-judge-choose").hide();
  }
}

function hj_enable(uid,element_id) {
  
  var value = $("#"+element_id).val();
  
  if (value == 0) {
    $("#hj-choose-"+uid).hide();
    $("#hj-display-"+uid).hide();
    $("#hj-choose-"+uid).prop("selected",false);
  }
  
  else {
    $("#hj-choose-"+uid).show();
  }

}

$(document).ready(function() {
  $(".hj-display").hide();

  <?php if (!empty($head_judge_id)) { ?>
  $(".hj-display").hide();
  $("#hj-display-"+hj_id).show();
  <?php } ?>

  <?php if (empty($assigned_at_table)) { ?>
  $("#head-judge-choose").hide();
  <?php } ?>
});
</script>

<?php if ($filter == "judges") { ?>
<style>
select.custom-hj-dropdown::-ms-expand {
    display: none;
}
select.custom-hj-dropdown {
    outline : none;
    overflow : hidden;
    text-indent : 0.01px;
    text-overflow : '';
    
    background : url("https://img.icons8.com/material/24/000000/sort-down.png") no-repeat right #fff;

    -webkit-appearance: none;
       -moz-appearance: none;
        -ms-appearance: none;
         -o-appearance: none;
            appearance: none;
}
</style>
<div class="row form-group">
  <label for="assignRoles" class="col-xs-12 col-sm-4 col-md-4 col-lg-2 control-label">
  Head Judge for Table: <a href="#" role="button" data-html="true" data-toggle="popover" data-container="body" data-trigger="focus" data-placement="top" title="Designate the Head Judge" data-content="<?php echo $head_judge_explain; ?>"> <span class="fa fa-lg fa-question-circle"></span></a>
  </label>
  <div class="col-xs-12 col-sm-4 col-md-4 col-lg-6">
    <select id="head-judge-select" name="head_judge_choose" class="form-control custom-hj-dropdown" onchange="update_hj_display()">
      <option value="">None <?php if (empty($assigned_at_table)) echo "(Names will populate as you assign judges)"; ?></option>
      <?php 
      foreach ($head_judge_options_ranked as $key => $value) { 
        $enable_disable = "";
        $hj_class = "";
        if ((!empty($head_judge_id)) && (in_array($value['uid'], $head_judge_id))) $enable_disable = "selected";
        if (!in_array($value['uid'], $assigned_at_table)) $hj_class = "hj-display";
      ?>
      <option class="<?php echo $hj_class; ?>" id="hj-choose-<?php echo $value['uid']; ?>" value="<?php echo $value['uid']; ?>" <?php echo $enable_disable ?>><?php echo $value['last_name'].", ".$value['first_name']." (".$value['rank'].")"; ?></option>
      <?php } ?>
      <?php foreach ($head_judge_options_non as $key => $value) { 
        $enable_disable = "";
        $hj_class = "";
        if ((!empty($head_judge_id)) && (in_array($value['uid'], $head_judge_id))) $enable_disable = "selected";
        if (!in_array($value['uid'], $assigned_at_table)) $hj_class = "hj-display";
      ?>
      <option class="<?php echo $hj_class; ?>" id="hj-choose-<?php echo $value['uid']; ?>" value="<?php echo $value['uid']; ?>" <?php echo $enable_disable ?>><?php echo $value['last_name'].", ".$value['first_name']." (".$value['rank'].")"; ?></option>
      <?php } ?>
    </select>
  </div>
</div>
<?php } // end if ($filter == "judges")?>
<?php } // end if ($_SESSION['jPrefsQueued'] == "N") ?>
<table class="table table-responsive table-bordered table" id="sortable">
<thead>
 	<?php echo $output_datatables_head; ?>
</thead>
<tbody>
	<?php echo $output_datatables_body; ?>
</tbody>
</table>
<p><input type="submit" class="btn btn-primary" name="Submit" value="Assign to Table <?php echo h($row_tables_edit['tableNumber']); ?>" /></p>
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