<?php

/**
 * Module:      participants.admin.php
 * Description: This module houses all participant (brewer) related functionality
 *              including viewing a participant list, add/edit/delete,
 *              Also provids judging location related functions - add, edit, delete.
 *
 */

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

// Build DataTables Header
$output_datatables_head .= "<tr>";
$output_datatables_head .= "<th width=\"15%\">Name</th>";
if ($filter == "judges") {
	$output_datatables_head .= "<th width=\"15%\">BJCP Rank</th>";
	$output_datatables_head .= "<th width=\"10%\" class=\"hidden-xs hidden-sm hidden-md\">BJCP #</th>";
	// $output_datatables_head .= "<th width=\"10%\">Comps Judged</th>";
    // Activate for Roles
	if ($_SESSION['jPrefsQueued'] == "Y") {
        $output_datatables_head .= "<th width=\"10%\">";
        $output_datatables_head .= "Judge Role(s) at Table";
        $output_datatables_head .= " <a href=\"#\" role=\"button\" data-html=\"true\" data-toggle=\"popover\" data-container=\"body\" data-trigger=\"focus\" data-placement=\"top\" title=\"Designate Judge Role(s) at the Table\" data-content=\"<p>Choose roles for certain judges at this table. <p><strong>Head Judge</strong> - <a class='hide-loader' href='https://www.bjcp.org/competitions/competition-handbook/' target='_blank'>According to the BJCP</a>, the head judge is a <em>single judge</em> responsible for reviewing all scores and paperwork for accuracy.</p><p><strong>Mini-BOS Judge</strong> - the Mini-BOS Judge is one of the judges at the table designated to participate in the <a class='hide-loader' href='http://dev.bjcp.org/wp-content/uploads/2011/11/MiniBOS.pdf' target='_blank'>Mini-BOS</a> to determine placing entries.</p>\"> <span class=\"fa fa-lg fa-question-circle\"></span></a>";
        $output_datatables_head .= "</th>";
    }
}

for($i=1; $i<$row_flights['flightRound']+1; $i++) {
	$output_datatables_head .= "<th>Round ".$i."</th>";
}

$output_datatables_head .= "</tr>";

$available_count = 0;
$total_count = 0;
$table_styles = explode(",",$row_tables_edit['tableStyles']);
$co_brewers_table = array();
$industry_affliations = array();

foreach ($table_styles as $table_style) {

  $query_style = sprintf("SELECT brewStyleGroup,brewStyleNum FROM %s WHERE id='%s'", $prefix."styles", $table_style);
  $style = mysqli_query($connection,$query_style) or die (mysqli_error($connection));
  $row_style = mysqli_fetch_assoc($style);

  if ($_SESSION['prefsProEdition'] == 0) {

    $query_co_brewers = sprintf("SELECT brewCoBrewer FROM %s WHERE brewCoBrewer IS NOT NULL AND brewCategorySort='%s' AND brewSubCategory='%s'",$prefix."brewing",$row_style['brewStyleGroup'],$row_style['brewStyleNum']);
    if ($_SESSION['jPrefsTablePlanning'] == 0) $query_co_brewers .= " AND brewReceived='1'";
    $co_brewers = mysqli_query($connection,$query_co_brewers) or die (mysqli_error($connection));
    $row_co_brewers = mysqli_fetch_assoc($co_brewers);
    $totalRows_co_brewers = mysqli_num_rows($co_brewers);

    if ($totalRows_co_brewers > 0) {
      
      do {
        $co_brewers_table[] = $row_co_brewers['brewCoBrewer'];
      } while ($row_co_brewers = mysqli_fetch_assoc($co_brewers));
    
    }

  }

  if ($_SESSION['prefsProEdition'] == 1) {

    $query_ind_aff = sprintf("SELECT a.brewBrewerID, b.brewerBreweryName FROM %s a, %s b WHERE a.brewBrewerID = b.uid AND b.brewerBreweryName IS NOT NULL AND brewCategorySort='%s' AND brewSubCategory='%s'",$prefix."brewing", $prefix."brewer", $row_style['brewStyleGroup'], $row_style['brewStyleNum']);
    $ind_aff = mysqli_query($connection,$query_ind_aff) or die (mysqli_error($connection));
    $row_ind_aff = mysqli_fetch_assoc($ind_aff);
    $totalRows_ind_aff = mysqli_num_rows($ind_aff);

    if ($totalRows_ind_aff > 0) {
      
      do {
        $industry_affliations[] = $row_ind_aff['brewerBreweryName'];
      } while ($row_ind_aff = mysqli_fetch_assoc($ind_aff));
    
    }

  }
    
}

if ($totalRows_brewer > 0) {
  
  do {

    $table_location = "Y-".$row_tables_edit['tableLocation'];
  	$judge_info = judge_info($row_brewer['uid']);
  	$judge_info = explode("^",$judge_info);
  	$bjcp_rank = explode(",",$judge_info[5]);
    $rank_display = bjcp_rank($bjcp_rank[0],1);
    
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
         $cb_list[] = $cb;
      }
      if ($cb_ct > 0) $co_brewer_flag = TRUE;
    }

    if ($_SESSION['prefsProEdition'] == 1) {

      if (!empty($judge_info[13])) {

        $ind_aff_0 = json_decode($judge_info[13],true);
        if (isset($ind_aff_0['affilliated'])) $ind_aff_1 = $ind_aff_0['affilliated'];
        if (isset($ind_aff_0['affilliatedOther'])) $ind_aff_2 = $ind_aff_0['affilliatedOther'];
        $ind_aff_3 = array_merge($ind_aff_1,$ind_aff_2);
        $aff_ct = 0;
        foreach ($industry_affliations as $ind_aff_4) {
           if (in_array($ind_aff_4, $ind_aff_3)) $aff_ct +=1;
        }

        if ($aff_ct > 0) $ind_aff_flag = TRUE;
      }

    }
          
  	$assign_row_color = "";
  	$flights_display = "";
  	$assign_flag = "";
    $assigned_at_this_table = FALSE;

    $checked_head_judge = "";
    $checked_lead_judge = "";
    $checked_minibos_judge = "";
    $roles_previously_defined = 0;

    $judge_roles_display = "";
    $head_judge_role_display = "";

    // Determine if this participant is assigned to this table
    $query_table_assignments = sprintf("SELECT id,bid FROM %s WHERE assignTable='%s' AND bid='%s'",$prefix."judging_assignments",$row_tables_edit['id'],$row_brewer['uid']);
    $table_assignments = mysqli_query($connection,$query_table_assignments) or die (mysqli_error($connection));
    $row_table_assignments = mysqli_fetch_assoc($table_assignments);

    // If so, see if there are any entry conflicts
    if ($row_table_assignments) $entry_conflict = entry_conflict($row_table_assignments['bid'],$row_tables_edit['tableStyles']);

    // If the participant has an entry conflict, unassign them from the table.
    // Increase the entry_conflict_count var
    if ($entry_conflict) {
      $entry_conflict_count++;
      $update_table = $prefix."judging_assignments";
      $db_conn->where ('bid', $row_brewer['uid']);
      $db_conn->where ('assignTable', $row_tables_edit['id']);
      $result = $db_conn->delete ($update_table);
    }

    $random = random_generator(6,2);

  	for($i=1; $i<$row_flights['flightRound']+1; $i++) {

  		// Get role from judging_assignments table
  		$query_judge_roles = sprintf("SELECT assignRoles FROM %s WHERE (bid='%s' AND assignTable='%s' AND assignRound='%s')", $prefix."judging_assignments", $row_brewer['uid'], $row_tables_edit['id'], $i);
  		$judge_roles = mysqli_query($connection,$query_judge_roles) or die (mysqli_error($connection));
  		$row_judge_roles = mysqli_fetch_assoc($judge_roles);

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
        }

        if ($_SESSION['jPrefsQueued'] == "N") {

          $head_judge_role_display = "<div id=\"hj-display-".$row_brewer['uid']."\" class=\"text-primary hj-display hj-select-display\"><i class=\"fa fa-gavel\"></i> ".$label_head_judge."</div>";

          if (!empty($row_judge_roles['assignRoles'])) {
            if (strpos($row_judge_roles['assignRoles'],"HJ") !== FALSE) {
              $head_judge_id[] = $row_brewer['uid'];
              $head_judge_name = $row_brewer['brewerLastName'].", ".$row_brewer['brewerFirstName']." (".$rank_display.")";
            }
            if (strpos($row_judge_roles['assignRoles'],"MBOS") !== FALSE) {
              $judge_roles_display .= "<div id=\"mbos-display-".$row_brewer['uid']."\" class=\"text-success\"><i class=\"fa fa-trophy\"></i> ".$label_mini_bos_judge."</div>";
            }
          }
        }

        if (($_SESSION['jPrefsQueued'] == "Y") && ($row_judge_roles)) {
            
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
        
        }

  		if (table_round($row_tables_edit['id'],$i)) {

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
  				$judge_alert = judge_alert($i,$row_brewer['uid'],$row_tables_edit['id'],$location,$judge_info[2],$judge_info[3],$row_tables_edit['tableStyles'],$row_tables_edit['id']);
  				$judge_alert = explode("|",$judge_alert);
  				$assign_row_color = $judge_alert[0];
  				$assign_flag = "<div style=\"padding-bottom:15px;\">".$judge_alert[1]."</div>";
  			}

  			$random = random_generator(8,2);

  			$unavailable = unavailable($row_brewer['uid'],$row_tables_edit['tableLocation'],$i,$row_tables_edit['id']);  			
        $flights_display .= $assign_flag;
  			$flights_display .= assign_to_table($row_tables_edit['id'],$row_brewer['uid'],$filter,$total_flights,$i,$location,$row_tables_edit['tableStyles'],$queued,$random,$base_url);
  			$flights_display .= "</td>";

  		}

  	}

  	if ($judge_info[4] == "Y") $display_rank .= "<br /><em>Certified Mead Judge</em>";
    if ($judge_info[12] == "Y") $display_rank .= "<br /><em>Certified Cider Judge</em>";

  	if (!empty($bjcp_rank[1])) $display_rank .= "<em>".designations($judge_info[5],$bjcp_rank[0])."</em>";

  	if ($filter == "stewards") $locations = explode(",",$judge_info[7]);
  	else $locations = explode(",",$judge_info[8]);

  	if (in_array($table_location,$locations)) {

      $total_count += 1;
  		$output_datatables_body .= "<tr class=\"".$assign_row_color."\">\n";

      /*
       * Build Name, Rank, BJCP ID, and Role columns
       * for DataTables output.
       */

      // Name Column
  		$output_datatables_body .= "<td>";
  		$output_datatables_body .= "<a href=\"".$base_url."index.php?section=brewer&amp;go=admin&amp;action=edit&amp;filter=".$row_brewer['uid']."&amp;id=".$judge_info[11]."\" data-toggle=\"tooltip\" title=\"Edit ".$judge_info[0]." ".$judge_info[1]."&rsquo;s account info\">".$judge_info[1].", ".$judge_info[0]."</a>";
      
      if ($filter == "judges") {
        $output_datatables_body .= "<br><strong>Comps Judged:</strong> ";
        if (empty($judge_info[9])) $output_datatables_body .= "0";
        else $output_datatables_body .= $judge_info[9];
        if (!empty($head_judge_role_display)) $output_datatables_body .= $head_judge_role_display;
        if (!empty($judge_roles_display)) $output_datatables_body .= $judge_roles_display;
      }

      if ($ind_aff_flag) {
        
          $judge_ind_aff = "";

          if (!empty($ind_aff_3)) {
            foreach ($ind_aff_3 as $value) {
                if (in_array($value,$industry_affliations)) $judge_ind_aff .= $value.", ";
            }
            $judge_ind_aff = trim($judge_ind_aff,", ");
          }

          $output_datatables_body .= "<br><span class=\"text-danger\"><i class=\"fa fas fa-exclamation-triangle\"></i> <strong>Affiliation conflict.</strong></span> Judge reports an industry affiliation with the following organization(s) that have entries at this table: <span class=\"text-danger\">".$judge_ind_aff."</span>"; 
      }

      if (($co_brewer_flag) && ($assign_row_color != "bg-info text-info")) $output_datatables_body .= "<br><span class=\"text-danger\"><i class=\"fa fas fa-exclamation-triangle\"></i> <strong>Possible Co-Brewer conflict (name match).</strong> <small>Verify with full co-brewer names listed above.</small></span>"; 
      
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
          $(\"input[name$='assignRound".$random."']\").click(function() {
              if ($(this).val() == \"1\") {
                  $(\"#toggleRoles".$random."\").show();
              }
              else {
                  $(\"#toggleRoles".$random."\").hide();
                  $(\"input[name='head_judge".$random."']\").prop(\"checked\", false);
                  $(\"input[name='lead_judge".$random."']\").prop(\"checked\", false);
                  $(\"input[name='minibos_judge".$random."']\").prop(\"checked\", false);
              }
          });\n
          ";

          $output_datatables_body .= "<td>";
          $output_datatables_body .= "<div id=\"toggleRoles".$random."\">";
          $output_datatables_body .= "<input type=\"hidden\" name=\"rolesPrevDefined".$random."\" value=\"".$roles_previously_defined."\">";
          $output_datatables_body .= "<div class=\"checkbox\">";
          $output_datatables_body .= "<label><input name=\"head_judge".$random."\" type=\"checkbox\" value=\"HJ\" ".$checked_head_judge." /> Head Judge</label>";
          $output_datatables_body .= "</div><br>";
          /*
          $output_datatables_body .= "<div class=\"checkbox\">";
          $output_datatables_body .= "<label><input name=\"lead_judge".$random."\" type=\"checkbox\" value=\"LJ\" ".$checked_lead_judge." /> Lead Judge</label>";
          $output_datatables_body .= "</div><br>";
          */
          $output_datatables_body .= "<div class=\"checkbox\">";
          $output_datatables_body .= "<label><input name=\"minibos_judge".$random."\" type=\"checkbox\" value=\"MBOS\" ".$checked_minibos_judge." /> Mini-BOS Judge</label>";
          $output_datatables_body .= "</div>";
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
  			if ($_SESSION['jPrefsQueued'] == "N") $output_at_table_modal_body .= "<td class=\"small\">".$judge_info[12]."</td>";
  			if ($_SESSION['jPrefsQueued'] == "N") $output_at_table_modal_body .= "<td class=\"small\">".$judge_info[13]."</td>";
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

  } while ($row_brewer = mysqli_fetch_assoc($brewer));
  
} // end if ($totalRows_brewer > 0)

$dashboard_link = build_public_url("evaluation","default","default","default",$sef,$base_url);
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
  <?php if ($_SESSION['jPrefsQueued'] == "Y") { echo $output_jquery_toggle; ?>
  <?php } else { ?>
  $(".assign-flight").change(function() {
      $(this).closest("section").find('.unassign-checkbox').prop('checked', true);
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
            <?php do { ?>
            <li class="small"><a href="<?php echo $base_url; ?>index.php?section=admin&amp;action=assign&amp;go=judging_tables&amp;filter=<?php echo $filter; ?>&amp;id=<?php echo $row_tables['id']; ?>"><?php echo "Table ".$row_tables['tableNumber'].": ".$row_tables['tableName']; ?></a></li>
            <?php } while ($row_tables = mysqli_fetch_assoc($tables)); ?>
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
<h4>Assign <span class="text-capitalize"><?php echo $filter; ?></span> to Table <?php echo $row_tables_edit['tableNumber']." &ndash; ".$row_tables_edit['tableName']; $entry_count = get_table_info(1,"count_total",$row_tables_edit['id'],$dbTable,"default"); echo " (".$entry_count." entries)"; ?><br><small><?php echo table_location($row_tables_edit['id'],$_SESSION['prefsDateFormat'],$_SESSION['prefsTimeZone'],$_SESSION['prefsTimeFormat'],"default"); ?></small></h4>
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
      <?php if (!empty($cb_list)) { ?><strong>Co-Brewer Names Associated with Entries at this Table:</strong> <?php $cb_list = implode(", ", $cb_list); echo trim($cb_list,", "); } ?>
      <?php if (!empty($industry_affliations)) { $industry_affliations = implode(", ", $industry_affliations); ?><strong>Organization(s) Associated with Entries at this Table:</strong> <?php echo trim($industry_affliations,", "); } ?>
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