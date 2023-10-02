<?php
include (DB.'styles.db.php');
include (DB.'admin_judging_tables.db.php');

if (strpos($section, "step") === FALSE) {

    if ($_SESSION['jPrefsQueued'] == "N") $assign_to = "Flights";
    else $assign_to = "Tables";

}

// Title Vars
if ($action == "edit") $title = ": Edit a Table";
elseif ($action == "add") $title = ": Add a Table";
elseif (($action == "assign") && ($filter == "default")) $title = ": Assign Judges or Stewards to Tables";
elseif (($action == "assign") && ($filter == "judges")) $title = ": Assign Judges to a Table";
elseif (($action == "assign") && ($filter == "stewards")) $title = ": Assign Stewards a to Table";
else $title = " Judging Tables"; if ($dbTable != "default") $title .= ": All Judging Tables (Archive ".get_suffix($dbTable).")";

// Boolean Vars and Conditions
$sidebar_entries_assigned = FALSE;
if (($totalRows_judging > 0) && ($dbTable == "default") && ($action == "default")) $sidebar_entries_assigned = TRUE;

$sidebar_all_sessions = FALSE;
if ($totalRows_judging > 1) $sidebar_all_sessions = TRUE;

$manage_tables_default = FALSE;
$judging_session = FALSE;
if (($totalRows_judging > 0) || ($dbTable != "default")) $judging_session = TRUE;

// Establish Vars
$output_at_table_modals = "";
$sub_lead_text = "";
$flight_choose = "";
$score_choose = "";
$style_types_list = "";
$sidebar_assigned_entries_by_location = "";
$all_loc_total = "";
$manage_tables_default_tbody = "";
$bos_modal_body = "";
$orphan_modal_body_1 = "";
$orphan_modal_body_2 = "";
$style_assigned_this = "";
$all_loc_total = array();
$mode_alert_color = "alert-teal";

$sub_lead_text_comp_mode = "<div id=\"tables-competition-mode-text\"><strong>Tables Competition Mode </strong> &ndash; to ensure accuracy, verify that all paid and received entries have been marked as such via the <a href=\"".$base_url."index.php?section=admin&amp;go=entries\">Manage Entries</a> screen.</div>";
    
$sub_lead_text_plan_mode = "<div id=\"tables-planning-mode-text\"><strong>Tables Planning Mode</strong> &ndash; creating tables, flights, rounds, and associated assignments is not bound by the system default requirement that all entries must be marked as paid and received. Pullsheets are <strong>NOT</strong> available in Tables Planning Mode.</div>";

if ($action == "default") {
    $sub_lead_text .= $sub_lead_text_plan_mode.$sub_lead_text_comp_mode;
}

else {
    if (isset($_SESSION['jPrefsTablePlanning'])) {
        if ($_SESSION['jPrefsTablePlanning'] == 0) $sub_lead_text .= $sub_lead_text_comp_mode;
        if ($_SESSION['jPrefsTablePlanning'] == 1) {
            $sub_lead_text .= $sub_lead_text_plan_mode;
            $mode_alert_color = "alert-purple";
        }
    }
}

if (($action == "default") && ($filter == "default")) {

	// Orphans styles not assigned to tables yet

	if ($totalRows_tables > 0) {

        if ($dbTable == "default") {

    		$a[] = array();
    		$y[] = array();
    		$z[] = 0;

    		do {
    			if (get_table_info($row_styles['brewStyleNum']."^".$row_styles['brewStyleGroup'],"count","default","default","default")) {
    				if (!get_table_info($row_styles['id'],"styles","default","default","default")) {
    					$a[] = $row_styles['id'];
    					$z[] = 1;
                        $orphan_modal_body_2 .= "<li>";
                        $orphan_modal_body_2 .= style_number_const($row_styles['brewStyleGroup'],$row_styles['brewStyleNum'],$_SESSION['style_set_display_separator'],0).": ";
                        $orphan_modal_body_2 .= $row_styles['brewStyle']." (".get_table_info($row_styles['brewStyleNum']."^".$row_styles['brewStyleGroup'],"count","default",$dbTable,"default")." entries)";
    					$orphan_modal_body_2 .= "</li>";
    				}
    			}
    		} while ($row_styles = mysqli_fetch_assoc($styles));

            $b = array_sum($z);
            if ($b == 0) $orphan_modal_body_1 .= "<p>All styles with entries have been assigned to tables.</p>";
            else $orphan_modal_body_1 .= "<p>The following styles with entries have not been assigned to tables:</p>";

        }

    } // end if ($totalRows_tables > 0)

    else {
        $orphan_modal_body_1 .= "<p>No tables have been defined.";
        if ($go == "judging_tables") $orphan_modal_body_1 .= " <a href='index.php?section=admin&amp;go=judging_tables&amp;action=add'>Add a table?</a>";
        $orphan_modal_body_1 .= "</p>";
    } // end else

    $manage_tables_default = TRUE;

	if ($totalRows_tables_edit > 0) {

		do {

			$flight_count = table_choose($section,$go,$action,$row_tables_edit['id'],$view,"default","flight_choose");
			$flight_count = explode("^",$flight_count);

			$flight_choose .= "<li class=\"small\"><a href=\"".$base_url;
			$flight_choose .= "index.php?section=admin&amp;go=judging_flights&amp;filter=define&amp;action=";
			if ($flight_count[0] > 0) $flight_choose .= "edit";
			else $flight_choose .= "add";
			$flight_choose .= "&amp;id=".$row_tables_edit['id']."\">";
			$flight_choose .= "#".$row_tables_edit['tableNumber'].": ".$row_tables_edit['tableName'];
			$flight_choose .= "</a></li>";

			$score_count = table_count_total($row_tables_edit['id']);
			$score_choose .= "<li class=\"small\"><a href=\"".$base_url;
			$score_choose .= "index.php?section=admin&amp;go=judging_scores&amp;action=";
			if ($score_count  > 0) $score_choose .= "edit";
			else $score_choose .= "add";
			$score_choose .= "&amp;id=".$row_tables_edit['id']."\">";
			$score_choose .= "#".$row_tables_edit['tableNumber'].": ".$row_tables_edit['tableName'];
			$score_choose .= "</a></li>";

		} while ($row_tables_edit = mysqli_fetch_assoc($tables_edit));

	}

	else {

		$score_choose .= "<li class=\"small\"><a class=\"disabled\">No tables have been defined.</a></li>";
		$flight_choose .= "<li class=\"small\"><a class=\"disabled\">No tables have been defined.</a></li>";

	}

    do {

        if ($row_style_types['styleTypeBOS'] == "Y") {

            $style_types_list .= "<li>";
            $style_types_list .= "<a href=\"".$base_url;
            $style_types_list .= "index.php?section=admin&amp;go=judging_scores_bos&amp;action=enter&amp;filter=";
            $style_types_list .= $row_style_types['id'];
			$style_types_list .= "\">BOS Places - ";
			$style_types_list .= $row_style_types['styleTypeName'];
            $style_types_list .= "</a>";
            $style_types_list .= "</li>";

        }

		else {

			$style_types_list .= "<li>";
           	$style_types_list .= "BOS Places - ".$row_style_types['styleTypeName']." [Disabled]";
            $style_types_list .= "</li>";

		}

    } while ($row_style_types = mysqli_fetch_assoc($style_types));

    do {

        $loc_total = 0;

        if ($row_judging['judgingLocType'] < 2) {
            if ($row_judging) $loc_total = get_table_info(1,"count_total","default","default",$row_judging['id']);
            $all_loc_total[] = $loc_total;

            $sidebar_assigned_entries_by_location .= "<div class=\"bcoem-sidebar-panel\">";
            $sidebar_assigned_entries_by_location .= "<strong class=\"text-info\">";
            if ($row_judging) $sidebar_assigned_entries_by_location .= $row_judging['judgingLocName'];
            $sidebar_assigned_entries_by_location .= "</strong>";
            $sidebar_assigned_entries_by_location .= "<span class=\"pull-right\">";
            $sidebar_assigned_entries_by_location .= $loc_total;
            $sidebar_assigned_entries_by_location .= "</span>";
            $sidebar_assigned_entries_by_location .= "</div>";  
        }
        

    } while ($row_judging = mysqli_fetch_assoc($judging));

    if ($totalRows_tables > 0) {

        do {

            $a = array(get_table_info("1","list",$row_tables['id'],$dbTable,"default"));
            $styles = display_array_content($a,1);
            $received = get_table_info("1","count_total",$row_tables['id'],$dbTable,"default");
            if ($_SESSION['jPrefsTablePlanning'] == 0) {
                $scored =  get_table_info("1","score_total",$row_tables['id'],$dbTable,"default");
                //get_table_info($input,$method,$id,$dbTable,$param)
                if (($received > $scored) && ($dbTable == "default") && ($_SESSION['userAdminObfuscate'] == 0)) $scored = $scored." <a class=\"hidden-print\" href=\"".$base_url."index.php?section=admin&amp;go=judging_scores&amp;action=edit&amp;id=".$row_tables['id']."\" data-toggle=\"tooltip\" data-placement=\"top\" title=\"Not all scores have been entered for this table. Select to add/edit scores.\"><span class=\"fa fa-lg fa-exclamation-circle text-danger\"></span></a>";
                else $scored = $scored;
            }

            else $scored = "<i class=\"text-danger fa fas fa-lg fa-ban\"></i> <small><em>Planning Mode</em></small>";

            $assigned_judges = assigned_judges($row_tables['id'],$dbTable,$judging_assignments_db_table);
                if ($dbTable == "default") $assigned_judges .= "<button class=\"btn-link\" data-toggle=\"tooltip\" data-placement=\"top\" title=\"Delete all judge assignments for this table.\" onclick=\"purge_data('".$base_url."','purge','judge-assignments','table-admin','delete-judges-".$row_tables['id']."');\"><i class=\"text-danger fas fa-lg fa-minus-circle\"></i></button><div><span class=\"hidden\" id=\"delete-judges-".$row_tables['id']."-status\"></span><span class=\"hidden\" id=\"delete-judges-".$row_tables['id']."-status-msg\"></span></div>";
            
            $assigned_stewards = assigned_stewards($row_tables['id'],$dbTable,$judging_assignments_db_table);
                if ($dbTable == "default") $assigned_stewards .= "<button class=\"btn-link\" data-toggle=\"tooltip\" data-placement=\"top\" title=\"Delete all steward assignments for this table.\" onclick=\"purge_data('".$base_url."','purge','steward-assignments','table-admin','delete-stewards-".$row_tables['id']."');\"><i class=\"text-danger fas fa-lg fa-minus-circle\"></i></button><div><span class=\"hidden\" id=\"delete-stewards-".$row_tables['id']."-status\"></span><span class=\"hidden\" id=\"delete-stewards-".$row_tables['id']."-status-msg\"></span></div>";

            if ($dbTable == "default") {
                if (score_count($row_tables['id'],1,$dbTable)) $scoreAction = "edit";
                else $scoreAction = "add";
            }

            $manage_tables_default_tbody .= "<tr>";
            $manage_tables_default_tbody .= "<td>".$row_tables['tableNumber']."</td>";
            $manage_tables_default_tbody .= "<td>".$row_tables['tableName']."</td>";
            $manage_tables_default_tbody .= "<td>".rtrim($styles, ",&nbsp;")."</td>";
            $manage_tables_default_tbody .= "<td>".$received."</td>";
            $manage_tables_default_tbody .= "<td class=\"hidden-xs hidden-sm\">".$scored."</td>";
            $manage_tables_default_tbody .= "<td class=\"hidden-xs hidden-sm\">".$assigned_judges."</td>";
            $manage_tables_default_tbody .= "<td class=\"hidden-xs hidden-sm\">".$assigned_stewards."</td>";
            if (($totalRows_judging > 1) && ($dbTable == "default")) $manage_tables_default_tbody .= "<td class=\"hidden-xs hidden-sm\">".table_location($row_tables['id'],$_SESSION['prefsDateFormat'],$_SESSION['prefsTimeZone'],$_SESSION['prefsTimeFormat'],"default")."</td>";
            
            if ($dbTable == "default") {
                $manage_tables_default_tbody .= "<td nowrap class=\"hidden-print\">";

                // Build edit link
                $manage_tables_default_tbody .= "<a href=\"".$base_url."index.php?section=admin&amp;go=".$go;
                $manage_tables_default_tbody .= "&amp;action=edit&amp;id=".$row_tables['id']."\" data-toggle=\"tooltip\" data-placement=\"top\" title=\"Edit Table ".$row_tables['tableNumber'].": ".$row_tables['tableName']."\">";
                $manage_tables_default_tbody .= "<span class=\"fa fa-lg fa-pencil\"></span>";
                $manage_tables_default_tbody .= "</a> ";

                if ($_SESSION['userAdminObfuscate'] == 0) {

                    if ($_SESSION['jPrefsTablePlanning'] == 0) {
                        // Build print pullsheet link
                        $manage_tables_default_tbody .= "<a id=\"modal_window_link\" class=\"hide-loader\" href=\"".$base_url."output/print.output.php?section=pullsheets&amp;go=judging_tables&amp;id=".$row_tables['id']."\" data-toggle=\"tooltip\" data-placement=\"top\" title=\"Print the pullsheet for Table ".$row_tables['tableNumber'].": ".$row_tables['tableName']."\">";
                        $manage_tables_default_tbody .= "<span class=\"fa fa-lg fa-print\"></span>";
                        $manage_tables_default_tbody .= "</a> ";

                    }

                    else $manage_tables_default_tbody .= "<span class=\"fa fa-lg fa-print text-muted\" data-toggle=\"tooltip\" data-placement=\"top\" title=\"Printing pullsheest is disabled in Tables Planning Mode\"></span> ";

                    if ($_SESSION['jPrefsTablePlanning'] == 0) {
                        // Build print pullsheet link
                        $manage_tables_default_tbody .= "<a id=\"modal_window_link\" class=\"hide-loader\" href=\"".$base_url."output/print.output.php?section=pullsheets&amp;go=all_entry_info&amp;id=".$row_tables['id']."\" data-toggle=\"tooltip\" data-placement=\"top\" title=\"Print the Entries with Additional Info Report for Table ".$row_tables['tableNumber'].": ".$row_tables['tableName']."\">";
                        $manage_tables_default_tbody .= "<span class=\"fa fa-lg fa-plus-square\"></span>";
                        $manage_tables_default_tbody .= "</a> ";
                    }

                    else $manage_tables_default_tbody .= "<span class=\"fa fa-lg fa-plus-square text-muted\" data-toggle=\"tooltip\" data-placement=\"top\" title=\"Printing the Entries with Additional Info report is disabled in Tables Planning Mode\"></span> ";

                }

                // Build flight link
                if (($_SESSION['jPrefsQueued'] == "N") && (flight_count($row_tables['id'],1))) {
                    $manage_tables_default_tbody .= "<a href=\"".$base_url."index.php?section=admin&amp;go=judging_flights&amp;filter=define&amp;action=edit&amp;id=".$row_tables['id']."\" data-toggle=\"tooltip\" data-placement=\"top\" title=\"Add/edit flights for Table ".$row_tables['tableNumber'].": ".$row_tables['tableName']."\">";
                    $manage_tables_default_tbody .= "<span class=\"fa fa-lg fa-send\"></span>";
                    $manage_tables_default_tbody .= "</a> ";
                }

                if ($_SESSION['userAdminObfuscate'] == 0) {

                    if ($_SESSION['jPrefsTablePlanning'] == 0) {
                        //Build add scores link
                        $manage_tables_default_tbody .= "<a href=\"".$base_url."index.php?section=admin&amp;go=judging_scores&amp;action=".$scoreAction."&amp;id=".$row_tables['id']."\" data-toggle=\"tooltip\" data-placement=\"top\" title=\"Add/edit scores for Table ".$row_tables['tableNumber'].": ".$row_tables['tableName']."\">";
                        $manage_tables_default_tbody .= "<span class=\"fa fa-lg fa-trophy\"></span>";
                        $manage_tables_default_tbody .= "</a> ";
                    }

                    else $manage_tables_default_tbody .= "<span class=\"fa fa-lg fa-trophy text-muted\" data-toggle=\"tooltip\" data-placement=\"top\" title=\"Add/edit scores disabled in Tables Planning Mode\"></span> ";

                }

                // Build delete link
                $manage_tables_default_tbody .= "<a class=\"hide-loader\" href=\"".$base_url;
                $manage_tables_default_tbody .= "includes/process.inc.php?section=".$section."&amp;go=".$go."&amp;filter=".$filter."&amp;dbTable=".$judging_tables_db_table."&amp;action=delete&amp;id=".$row_tables['id']."\" data-toggle=\"tooltip\" data-placement=\"top\" title=\"Delete Table ".$row_tables['tableNumber'].": ".$row_tables['tableName']."\" data-confirm=\"Are you sure you want to delete Table ".$row_tables['tableNumber'].": ".$row_tables['tableName']."? ALL associated FLIGHTS and SCORES will be deleted as well. This cannot be undone.\">";
                $manage_tables_default_tbody .= "<span class=\"fa fa-lg fa-trash-o\"></span>";
                $manage_tables_default_tbody .= "</a> ";
                $manage_tables_default_tbody .= "</td>";
            }

            $manage_tables_default_tbody .= "";
            $manage_tables_default_tbody .= "";
            $manage_tables_default_tbody .= "</tr>";

        } while ($row_tables = mysqli_fetch_assoc($tables));

    }

    do {

        $bos_modal_body .= "<li>";
        $bos_modal_body .= $row_style_type['styleTypeName']." (".bos_method($row_style_type['styleTypeBOSMethod'])." from each table to BOS).";
        $bos_modal_body .= "</li>";

    } while ($row_style_type = mysqli_fetch_assoc($style_type));   

} // end if (($action == "default") && ($dbTable == "default"))

if (($action == "add") || ($action == "edit")) {

    $all_table_styles_concat = "";
    $all_table_numbers_array = array();
    $all_table_styles_array = array();
    $table_styles_available = "";
	$current_table_styles_array = array();
    $table_numbers_available = "";
    $table_locations_available = "";
    $table_numbers = "";
    $table_total = 0;
    $style_ids_at_table = array();

    if ($judging_session) {
        
        /*
        if ($action == "add") {

            do {
                $table_numbers[] = $row_table_number['tableNumber'];
            } while($row_table_number = mysqli_fetch_assoc($table_number));

        }
        */

        if ($action == "edit") {

            $query_current_table_styles = sprintf("SELECT * FROM %s WHERE id='%s'",$judging_tables_db_table,$id);
            $current_table_styles = mysqli_query($connection,$query_current_table_styles) or die (mysqli_error($connection));
            $row_current_table_styles = mysqli_fetch_assoc($current_table_styles);

            $current_table_styles_array = explode(",",$row_current_table_styles['tableStyles']);

        }

        $query_all_table_styles = sprintf("SELECT * FROM %s",$judging_tables_db_table);
        $all_table_styles = mysqli_query($connection,$query_all_table_styles) or die (mysqli_error($connection));
        $row_all_table_styles = mysqli_fetch_assoc($all_table_styles);
        $totalRows_all_table_styles = mysqli_num_rows($all_table_styles);

        // Get the table numbers and sub-style ids from the tables that have been defined and put into arrays
        if ($totalRows_all_table_styles > 0) {

            do {
                $all_table_styles_concat .= $row_all_table_styles['tableStyles'].",";
                $all_table_numbers_array[] =  $row_all_table_styles['tableNumber'];
            } while ($row_all_table_styles = mysqli_fetch_assoc($all_table_styles));

            $all_table_styles_concat = rtrim($all_table_styles_concat,",");
            $all_table_styles_array = explode(",",$all_table_styles_concat);

        }

        // Build the table number values for drop-down
        // Disable those already used
        for($i=1; $i<=100; $i++) {

            $selected_table_number = "";
            $disabled_table_number = "";
            if (($action == "edit") && ($row_tables_edit['tableNumber'] == $i)) $selected_table_number = " SELECTED";
            elseif ((is_array($all_table_numbers_array)) && (in_array($i,$all_table_numbers_array))) $disabled_table_number = " DISABLED";

            $table_numbers_available .= "<option value=\"".$i."\"";
            $table_numbers_available .= $selected_table_number;
            $table_numbers_available .= $disabled_table_number;
            $table_numbers_available .= ">";
            $table_numbers_available .= $i;
            $table_numbers_available .= "</option>\n";

        }

        // Build location drop-down
        do {

            $selected_table_location = "";
            $disabled_table_location = "";

            if (($action == "edit") && ($row_tables_edit['tableLocation'] == $row_judging['id'])) $selected_table_location = " SELECTED";
            if ($row_judging['judgingLocType'] < 2) {
                $table_locations_available .= "<option value=\"".$row_judging['id']."\"".$selected_table_location.">";
                $table_locations_available .= $row_judging['judgingLocName']." (".getTimeZoneDateTime($_SESSION['prefsTimeZone'], $row_judging['judgingDate'], $_SESSION['prefsDateFormat'],  $_SESSION['prefsTimeFormat'], "short", "date-time-no-gmt").")";
                $table_locations_available .= "</option>\n";
            }
            

        } while ($row_judging = mysqli_fetch_assoc($judging));

        do {

            $style_value = $row_styles['brewStyleNum']."^".$row_styles['brewStyleGroup'];
            $received_entry_count_style = get_table_info($style_value,"count","default",$dbTable,"default");
            $table_row_class = "";
            $style_assigned_location = "";
            $style_no_entries = "";
            $disabled_styles = "";
            $selected_styles = "";
            $table_row_class = "bg-success text-success";

            $style_display = style_number_const($row_styles['brewStyleGroup'],$row_styles['brewStyleNum'],$_SESSION['style_set_display_separator'],"");
            $style_sort = style_number_const($row_styles['brewStyleGroup'],$row_styles['brewStyleNum'],$_SESSION['style_set_display_separator'],1);

            if ($received_entry_count_style == 0) {
                $disabled_styles = "DISABLED";
                $style_no_entries = "<br><em>Disabled. No entries were received for this style.</em>";
                $table_row_class = "bg-grey text-muted";
            }

            if (($action == "edit") && (in_array($row_styles['id'],$current_table_styles_array))) $style_assigned_location = "<br><em>Style currently assigned to this table.</em>";
            else $style_assigned_location = get_table_info($row_styles['id'],"assigned","default",$dbTable,"default");

            if (!empty($style_assigned_location)) {
                $table_row_class = "bg-danger";
                $disabled_styles = "DISABLED";
            }

            if ($action == "edit")  $style_assigned_this = get_table_info($row_styles['id'],"styles",$row_tables_edit['id'],$dbTable,"default");
            if ((is_array($all_table_styles_array)) && (in_array($row_styles['id'],$all_table_styles_array))) $disabled_styles = "DISABLED";
            if ((is_array($current_table_styles_array)) && (in_array($row_styles['id'],$current_table_styles_array))) $disabled_styles = "";
            if ($style_assigned_this) {
                $table_row_class = "bg-warning";
                $selected_styles = "CHECKED";
                $style_ids_at_table[] = $row_styles['id'];
            }

            $disabled_selected_styles = $selected_styles." ".$disabled_styles;

            $table_styles_available .= "<tr class=\"".$table_row_class."\">\n";
            $table_styles_available .= "<td><input id=\"".$row_styles['id']."\" type=\"checkbox\" name=\"tableStyles[]\" onClick=\"update_table_total('".$row_styles['id']."');\" value=\"".$row_styles['id']."\" ".$disabled_selected_styles."></td>\n";

            if ($_SESSION['prefsStyleSet'] == "BA") {
                $ba_category = style_convert($row_styles['brewStyleGroup'],1);
                $table_styles_available .= "<td>".$ba_category."</td><td>".$row_styles['brewStyle'].$style_no_entries.$style_assigned_location."</td>\n";
            }
            else {
                $table_styles_available .= "<td><span class=\"hidden\">".$style_sort."</span>".$style_display."</td>\n";
                $table_styles_available .= "<td>".style_convert($row_styles['brewStyleGroup'],1)."</td>\n";
                $table_styles_available .= "<td>".$row_styles['brewStyle'].$style_no_entries.$style_assigned_location."</td>\n";
            }
            $table_styles_available .= "<td><span id=\"".$row_styles['id']."-total\">".$received_entry_count_style."</span></td>\n";
            $table_styles_available .= "</tr>\n\n";

            if ($selected_styles == "CHECKED") $table_total += $received_entry_count_style;

        } while ($row_styles = mysqli_fetch_assoc($styles));
    }

    

} // end if (($action == "add") || ($action == "edit"))

if ($action == "default") {
?>
<script type="text/javascript">

/*
 * Function with an ajax call to convert 
 * all records in the judging_assignments 
 * and judging_flights tables to 1 (planning).
 */
function enable_planning_mode() {

    var base_url = "<?php echo $base_url; ?>";
    
    jQuery.ajax({
        
        url: base_url+"ajax/tables_mode.ajax.php?section=enable-planning",

        success:function(data) {
            
            var jsonData = JSON.parse(data);
            console.log(jsonData.status);
            
            if (jsonData.status === "1") {

                $("#competition-mode-status").hide();
                $("#competition-mode-status-text").html("Tables Planning Mode loaded successfully. Refreshing the page...");
                $("#competition-mode-status-icon").attr("class", "small text-success");
                $("#competition-mode-status-icon").attr("class", "fa fa-check-circle text-success");
                $("#competition-mode-status").show().delay(5000).hide("fast");
                $('#loader-submit').show(0).delay(30000).hide(0);

                // Refresh to get updated numbers
                window.location.reload(true);
                
            }
            
            else if (jsonData.status === "2") {

                $("#competition-mode-status").hide();
                $("#competition-mode-status-text").html("Tables Planning Mode was not loaded successfully.");
                $("#competition-mode-status-icon").attr("class", "small text-danger");
                $("#competition-mode-status-icon").attr("class", "fa fa-exclamation-circle text-warning");
                $("#competition-mode-status").show().delay(5000).hide("fast");

            }

            else {

                $("#competition-mode-status").hide();
                $("#competition-mode-status-text").html("There was an error.");
                $("#competition-mode-status-icon").attr("class", "small text-danger");
                $("#competition-mode-status-icon").attr("class", "fa fa-exclamation-circle text-warning");
                $("#competition-mode-status").show().delay(5000).hide("fast");
            
            }
        
        },

        error:function() {
            console.log('Error');
        }

    });

}

/*
 * Function with an ajax call to check all 
 * records in the judging_flights DB table
 * to verify if each relational record
 * has been marked as received in the brewing 
 * DB table. If so, retain in judging_flights. 
 * If not, delete.
 * Also converts all records in the
 * judging_assignments table to 0 (production).
 */

function enable_competition_mode() {

    var base_url = "<?php echo $base_url; ?>";

    jQuery.ajax({
        
        url: base_url+"ajax/tables_mode.ajax.php?section=enable-competition",

        success:function(data) {
            
            var jsonData = JSON.parse(data);
            console.log(jsonData.status);
            
            if (jsonData.status === "1") {

                $("#planning-mode-status").hide();
                $("#planning-mode-status-text").html("Tables Competition Mode loaded successfully. Refreshing the page...");
                $("#planning-mode-status-icon").attr("class", "small text-success");
                $("#planning-mode-status-icon").attr("class", "fa fa-check-circle text-success");
                $("#planning-mode-status").show().delay(5000).hide("fast");
                $('#loader-submit').show(0).delay(30000).hide(0);
                
                // Refresh to get updated numbers
                window.location.reload(true);
                
            }
            
            else if (jsonData.status === "2") {

                $("#planning-mode-status").hide();
                $("#planning-mode-status-text").html("Tables Competition Mode was not loaded successfully.");
                $("#planning-mode-status-icon").attr("class", "small text-danger");
                $("#planning-mode-status-icon").attr("class", "fa fa-exclamation-circle text-danger");
                $("#planning-mode-status").show().delay(5000).hide("fast");

            }

            else {
                
                $("#planning-mode-status").hide();
                $("#competition-mode-status-text").html("There was an error.");
                $("#planning-mode-status-icon").attr("class", "small text-danger");
                $("#planning-mode-status-icon").attr("class", "fa fa-exclamation-circle text-danger");
                $("#planning-mode-status").show().delay(5000).hide("fast");

            }
        
        },

        error:function() {
            console.log('Error');
        }

    });

}

$(document).ready(function(){

    // If judges and/or stewards have been un-assigned, trigger modal.
    <?php if ((isset($_SESSION['judge_unassign_flag'])) && ($_SESSION['judge_unassign_flag'] == 1)) { ?>
        $('#unassigned-modal').modal('show');
    <?php $_SESSION['judge_unassign_flag'] = 0; } ?>
    
    <?php if ($_SESSION['jPrefsTablePlanning'] == 0) { ?>

    $("#tables-competition-mode").hide();
    $("#mode-alert").attr("class", "alert alert-teal");
    $("#tables-planning-mode-text").hide();
    
    <?php } ?>

    <?php if ($_SESSION['jPrefsTablePlanning'] == 1) { ?>

    $("#tables-planning-mode").hide();
    $("#mode-alert").attr("class", "alert alert-purple");
    $("#tables-competition-mode-text").hide();
    
    <?php } ?>

    $("#table-planning-button").click(function(){ 

        $("#mode-alert").attr("class", "alert alert-purple");
        $("#tables-planning-mode").hide();
        $("#tables-planning-mode-text").show();
        $("#tables-competition-mode").show();
        $("#tables-competition-mode-text").hide();
        $("#competition-mode-status-text").html("Loading Tables Planning Mode...");
        $("#competition-mode-status-text").attr("class", "small text-grey");
        $("#competition-mode-status-icon").attr("class", "fa fa-cog fa-spin text-grey");
        $("#competition-mode-status").show(); 

        enable_planning_mode(); 
        
    });

    $("#tables-competition-button").click(function(){
        $('#tables-competition-mode-modal').modal('show');       
    });

    $("#tables-competition-button-yes").click(function(){
        $('#tables-competition-mode-modal').modal('hide');
        $("#mode-alert").attr("class", "alert alert-teal");
        $("#tables-competition-mode").hide();
        $("#tables-competition-mode-text").show();
        $("#tables-planning-mode").show();
        $("#tables-planning-mode-text").hide();
        $("#planning-mode-status-text").html("Loading Tables Competition Mode...");
        $("#planning-mode-status-text").attr("class", "small text-grey");
        $("#planning-mode-status-icon").attr("class", "fa fa-cog fa-spin text-grey");
        $("#planning-mode-status").show();
        
        enable_competition_mode();
    });

});

</script>

<div class="modal fade" id="tables-competition-mode-modal" tabindex="-1" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title">Please Confirm</h4>
            </div>
            <div class="modal-body">
                <p>Are you sure you want to switch to Tables Competition Mode? This should only be done after <strong>all</strong> entries have been sorted and those present are <strong>marked as received in the system</strong>.</p>
                <p>Before you do, take note that in Tables Competition Mode:</p>
                <ul>
                    <li>Table entry counts will only reflect entries marked as received.</li>
                    <li>Non-received entries' flight designations will be reset to 1 (default) should any be marked as received after switching to Competition Mode. Flight positions and associated rounds should be reviewed prior to judging.</li>
                    <li>If there are no entries marked as received for a particular sub-style, the sub-style will be removed from the table's styles list.</li>
                    <li>If there are no entries marked as received for all sub-styles defined for a table, that table will be deleted.</li>
                    <li>Judges and stewards that now have entries at a table where they are assigned will be un-assigned from that table as a failsafe.</li>
                </ul>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-danger" data-dismiss="modal">Cancel</button>
                <button id="tables-competition-button-yes" type="button" class="btn btn-success">Yes</button>
            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->


<!-- Delete assignments modals -->
<div class="modal fade" id="delete-all-judges" tabindex="-1" role="dialog" aria-labelledby="delete-all-judgesLabel">
    <div class="modal-dialog" role="document">
    <div class="modal-content">
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            <h4 class="modal-title" id="delete-all-judgesLabel">Please Confirm</h4>
        </div>
        <div class="modal-body">
            <p>Are you sure you want to delete ALL judge assignments? This cannot be undone.</p>
        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-danger" data-dismiss="modal">Cancel</button>
            <button type="button" class="btn btn-success" data-dismiss="modal" onclick="purge_data('<?php echo $base_url; ?>','','tables','admin-dashboard','purge-table');">Yes</button>
        </div>
        </div>
    </div>
</div>
<div class="modal fade" id="delete-all-stewards" tabindex="-1" role="dialog" aria-labelledby="delete-all-stewardsLabel">
    <div class="modal-dialog" role="document">
    <div class="modal-content">
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            <h4 class="modal-title" id="delete-all-stewardsLabel">Please Confirm</h4>
        </div>
        <div class="modal-body">
            <p>Are you sure you want to delete ALL steward assignments? This cannot be undone.</p>
        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-danger" data-dismiss="modal">Cancel</button>
            <button type="button" class="btn btn-success" data-dismiss="modal" onclick="purge_data('<?php echo $base_url; ?>','','tables','admin-dashboard','purge-table');">Yes</button>
        </div>
        </div>
    </div>
</div>

<!-- Unassigned Judges/Stewards Modal -->
<div class="modal fade" id="unassigned-modal" tabindex="-1" role="dialog" aria-labelledby="unassigned-modal-label" data-backdrop="static">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h4 style="margin-bottom:0;" class="modal-title" id="unassigned-modal-label">Caution! Judges and/or Stewards Were Un-Assigned</h4>
      </div>
      <div class="modal-body">
        <p>Upon switching to Tables Competition Mode, one or more judges/stewards were un-assigned from tables due to entry style conflicts. This can happen if the participant added or edited an entry's style that is designated at a table where they are assigned as a judge or steward.</p>
        <p>Review the list below for any changes in judge or steward counts and make adjustments accordingly.</p>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-success" data-dismiss="modal">I Understand</button>
      </div>
    </div><!-- /.modal-content -->
  </div><!-- /.modal-dialog -->
</div><!-- /.modal -->

<?php } ?>
<p class="lead"><?php echo $_SESSION['contestName'].$title;  ?></p>
<?php if ($dbTable == "default") { ?>
<div id="mode-alert" class="alert <?php echo $mode_alert_color; ?> hidden-print"><?php echo $sub_lead_text; ?></div>
<?php if ($action == "default") { ?>
<!-- Planning Mode Button -->
<div id="tables-planning-mode" class="bcoem-admin-element hidden-print">
    <button id="table-planning-button" class="btn btn-warning"><span class="fa fa-exchange"></span> Switch to Tables Planning Mode</button> <a href="#" data-toggle="popover" title="Tables Planning Mode" data-content="<p>When the Tables Planning Mode function is enabled, Admins can define tables, flights, rounds, and judge/steward assignments prior to entries being marked as paid and/or received (i.e., prior to sorting).</p><p>Any table configurations and associated assignments <strong>will not be official</strong> until an Admin returns to Tables Competition Mode after entries have been sorted and marked as received in the system. Pullsheets will <strong>not</strong> be available.</p>" data-trigger="hover click" data-placement="right" data-html="true" data-container="body"><i class="fa fa-lg fa-question-circle"></i></a>  <span id="planning-mode-status"><i id="planning-mode-status-icon" class=""></i> <span id="planning-mode-status-text" class="small"></span></span>
</div>
<div id="tables-competition-mode" class="bcoem-admin-element hidden-print">
    <button id="tables-competition-button" class="btn btn-success"><span class="fa fa-exchange"></span> Switch to Tables Competition Mode</button> <a href="#" data-toggle="popover" title="Tables Competition Mode" data-content="<p>When the Tables Competition Mode function is enabled by an admin, it indicates to the system that the planning stage is over (i.e., sorting is finished and all applicable entries have been marked as paid and received).</p><p>Table configurations and assignments can still be changed as necessary while in Competition Mode. Pullsheets will be available.</p>" data-trigger="hover click" data-placement="right" data-html="true" data-container="body"><i class="fa fa-lg fa-question-circle"></i></a> <span id="competition-mode-status"><i id="competition-mode-status-icon" class=""></i> <span id="competition-mode-status-text" class="small"></span></span>
</div>
<?php } ?>
<?php if (($_SESSION['prefsEval'] == 1) && ($dbTable == "default")) include (EVALS.'import_scores.eval.php'); ?>
<?php } ?>
<?php if ($judging_session) { ?>
<div class="bcoem-admin-element hidden-print">
	<?php if  ($dbTable != "default") { ?>
    <!-- Postion 1: View All Button -->
    <div class="btn-group" role="group" aria-label="archives">
        <a class="btn btn-default" href="<?php echo $base_url; ?>index.php?section=admin&amp;go=archive"><span class="fa fa-arrow-circle-left"></span> Archives</a>
    </div><!-- ./button group -->
	<?php } ?>
    <?php if ($dbTable == "default") { ?>
	<?php if (($action != "default") || ($filter != "default")) { ?>
    <!-- Postion 1: View All Button -->
    <div class="btn-group" role="group" aria-label="allTables">
        <a class="btn btn-default" href="<?php echo $base_url; ?>index.php?section=admin&amp;go=judging_tables"><span class="fa fa-arrow-circle-left"></span> All Tables</a>
    </div><!-- ./button group -->
    <?php } ?>
    <?php if ($action == "default") { ?>
    <!-- Postion 2: Add Button -->
    <div class="btn-group" role="group" aria-label="addTable">
        <a class="btn btn-default" href="<?php echo $base_url; ?>index.php?section=admin&amp;go=judging_tables&amp;action=add"><span class="fa fa-plus-circle"></span> Add a Table</a>
    </div><!-- ./button group -->
    <?php } ?>
	<!-- View Button Group Dropdown -->
    <div class="btn-group" role="group">
        <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
        <span class="fa fa-eye"></span> View...
        <span class="caret"></span>
        </button>
        <ul class="dropdown-menu">
            <li class="small"><a id="modal_window_link" class="hide-loader" href="<?php echo $base_url; ?>output/print.output.php?section=assignments&amp;go=judging_assignments&amp;filter=judges&amp;view=name&amp;tb=view" title="View Assignments by Name">Judge Assignments By Last Name</a></li>
            <li class="small"><a id="modal_window_link" class="hide-loader" href="<?php echo $base_url; ?>output/print.output.php?section=assignments&amp;go=judging_assignments&amp;filter=judges&amp;view=table&amp;tb=view" title="View Assignments by Table">Judge Assignments By Table</a></li>
            <li class="small"><a id="modal_window_link" class="hide-loader" href="<?php echo $base_url; ?>output/print.output.php?section=assignments&amp;go=judging_assignments&amp;filter=stewards&amp;view=name&amp;tb=view" title="View Assignments by Name">Steward Assignments By Last Name</a></li>
            <li class="small"><a id="modal_window_link" class="hide-loader" href="<?php echo $base_url; ?>output/print.output.php?section=assignments&amp;go=judging_assignments&amp;filter=stewards&amp;view=table&amp;tb=view" title="View Assignments by Table">Steward Assignments By Table</a></li>
            <li class="small"><a href="#" data-toggle="modal" data-target="#availJudgeModal">Judges Not Assigned to a Table</a></li>
            <li class="small"><a href="#" data-toggle="modal" data-target="#availStewardModal">Stewards Not Assigned to a Table</a></li>
        </ul>
    </div><!-- ./button group -->
	<?php if ($action == "default") { ?>
	<!-- Postion 4: Print Button Dropdown Group -->
    <div class="btn-group hidden-xs hidden-sm" role="group">
        <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
        <span class="fa fa-print"></span> Print...
        <span class="caret"></span>
        </button>
        <ul class="dropdown-menu">
            <li class="small"><a href="#" class="hide-loader" onclick="window.print()">Tables List</a></li>
            <?php if ($_SESSION['userAdminObfuscate'] == 0) { ?>
    		<li class="small"><a id="modal_window_link" class="hide-loader" href="<?php echo $base_url; ?>output/print.output.php?section=pullsheets&amp;go=judging_tables&amp;id=default">Pullsheets by Table</a></li>
            <?php } ?>
            <li class="small"><a id="modal_window_link" class="hide-loader" href="<?php echo $base_url; ?>output/print.output.php?section=assignments&amp;go=judging_assignments&amp;filter=judges&amp;view=name" title="Print Judge Assignments by Name">Judge Assignments By Last Name</a></li>
			<li class="small"><a id="modal_window_link" class="hide-loader" href="<?php echo $base_url; ?>output/print.output.php?section=assignments&amp;go=judging_assignments&amp;filter=judges&amp;view=table" title="Print Judge Assignments by Table">Judge Assignments By Table</a></li>
   			<?php if ($totalRows_judging > 1) { ?>
			<li class="small"><a id="modal_window_link" class="hide-loader" href="<?php echo $base_url; ?>output/print.output.php?section=assignments&amp;go=judging_assignments&amp;filter=judges&amp;view=location" title="Print Judge Assignments by Location">Judge Assignments By Location</a></li>
    		<?php } ?>
            <li class="small"><a id="modal_window_link" class="hide-loader" href="<?php echo $base_url; ?>output/print.output.php?section=assignments&amp;go=judging_assignments&amp;filter=stewards&amp;view=name" title="Print Steward Assignments by Name">Steward Assignments By Last Name</a></li>
			<li class="small"><a id="modal_window_link" class="hide-loader" href="<?php echo $base_url; ?>output/print.output.php?section=assignments&amp;go=judging_assignments&amp;filter=stewards&amp;view=table" title="Print Steward Assignments by Table">Steward Assignments By Table</a></li>
   			<?php if ($totalRows_judging > 1) { ?>
			<li class="small"><a id="modal_window_link" class="hide-loader" href="<?php echo $base_url; ?>output/print.output.php?section=assignments&amp;go=judging_assignments&amp;filter=stewards&amp;view=location" title="Print Steward Assignments by Location">Steward Assignments By Location</a></li>
    		<?php } ?>
        </ul>
    </div>
	<?php } ?>
    <?php } ?>
</div>
<?php } ?>
<?php if (($action == "default") && ($dbTable == "default") && ($judging_session)) { ?>
<!-- Manage Tables -->
<script>
 $(document).ready(function() {
	$('#sortableJ').dataTable( {
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
$(document).ready(function() {
	$('#sortableS').dataTable( {
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
<div class="modal fade" id="availJudgeModal" tabindex="-1" role="dialog" aria-labelledby="availJudgeModalLabel">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header bcoem-admin-modal">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="availJudgeModalLabel">Judges Not Assigned to a Table</h4>
            </div>
            <div class="modal-body">
                <?php echo not_assigned("J"); ?>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div><!-- ./modal -->
<!-- Available Stewards Modal -->
<!-- Modal -->
<div class="modal fade" id="availStewardModal" tabindex="-1" role="dialog" aria-labelledby="availStewardModalLabel">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header bcoem-admin-modal">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="availStewardModalLabel">Stewards Not Assigned to a Table</h4>
            </div>
            <div class="modal-body">
                <?php echo not_assigned("S"); ?>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div><!-- ./modal -->
<div class="bcoem-admin-dashboard-accordion hidden-print">
<div class="row">
	<!-- Left Column -->
	<div class="col col-lg-9 col-md-8 col-sm-12 col-xs-12">
		<!-- Start 2 Column Accordion -->
			<div class="row">
				<!-- Accordion Right Column -->
				<div class="col col-lg-6 col-md-12 col-sm-12 col-xs-12">
					<div class="panel-group" id="accordion1">
					<div class="panel panel-default">
						<div class="panel-heading">
							<h4 class="panel-title">
								<a data-toggle="collapse" data-parent="#accordion1" href="#collapseStep1">Step 1: Assign Judges or Stewards<span class="fa fa-user pull-right"></span></a>
							</h4>
						</div>
						<div id="collapseStep1" class="panel-collapse collapse">
							<div class="panel-body">
								<ul class="list-unstyled">
                                	<li><a href="<?php echo $base_url; ?>index.php?section=admin&amp;action=assign&amp;go=judging&amp;filter=judges">Assign Judges</a></li>
            						<li><a href="<?php echo $base_url; ?>index.php?section=admin&amp;action=assign&amp;go=judging&amp;filter=stewards">Assign Stewards</a></li>
                                </ul>
							</div>
						</div>
					</div><!-- ./accordion -->
					<div class="panel panel-default">
						<div class="panel-heading">
							<h4 class="panel-title">
								<a data-toggle="collapse" data-parent="#accordion1" href="#collapseStep2">Step 2: Define All Tables<span class="fa fa-tasks pull-right"></span></a>
							</h4>
						</div>
                        <?php if ($judging_session) { ?>
						<div id="collapseStep2" class="panel-collapse collapse">
							<div class="panel-body">
								<ul class="list-unstyled">
                                	<li><a href="<?php echo $base_url; ?>index.php?section=admin&amp;go=judging_tables&amp;action=add">Add a Table</a></li>
									<li><a href="#" data-toggle="modal" data-target="#orphanModal">Styles Not Assigned to Tables</a></li>
                                </ul>
							</div>
						</div>
                        <?php } ?>
					</div><!-- ./accordion -->
					<div class="panel panel-default">
						<div class="panel-heading">
							<h4 class="panel-title">
								<a data-toggle="collapse" data-parent="#accordion1" href="#collapseStep3">Step 3: Define All Flights<span class="fa fa-send pull-right"></span></a>
							</h4>
						</div>
						<div id="collapseStep3" class="panel-collapse collapse">
							<div class="panel-body">
                            	<div class="row">
                                	<div class="col col-lg-8 col-md-12 col-sm-12 col-xs-12">
                                        <?php if ($_SESSION['jPrefsQueued'] == "N") { ?>
                                        <div class="btn-group" role="group" aria-label="modals">
                                            <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                For Table... <span class="caret"></span>
                                            </button>
                                            <ul class="dropdown-menu">
                                                <?php echo $flight_choose; ?>
                                            </ul>
                                        </div>
                                        <?php } else { ?>
                                        <p>Not needed. Using queued judging.</p>
                                        <?php } ?>
                                	</div>
                                </div><!-- ./row -->
							</div>
						</div>
					</div><!-- ./accordion -->
					<div class="panel panel-default">
						<div class="panel-heading">
							<h4 class="panel-title">
								<a data-toggle="collapse" data-parent="#accordion1" href="#collapseStep4">Step 4: <?php echo "Assign ".$assign_to." to Rounds"; ?><span class="fa fa-tags pull-right"></span></a>
							</h4>
						</div>
						<div id="collapseStep4" class="panel-collapse collapse">
							<div class="panel-body">
								<ul class="list-unstyled">
                                	<li>	<a href="<?php echo $base_url; ?>index.php?section=admin&amp;go=judging_flights&amp;action=assign&amp;filter=rounds"><?php echo "Assign ".$assign_to." to Rounds"; ?></a></li>
                                </ul>
							</div>
						</div>
					</div><!-- ./accordion -->
				</div>
				</div><!-- ./accordion left column -->
				<!-- Accordion Left Column -->
				<div class="col col-lg-6 col-md-12 col-sm-12 col-xs-12">
				<div class="panel-group" id="accordion2">
					<div class="panel panel-default">
						<div class="panel-heading">
							<h4 class="panel-title">
								<a data-toggle="collapse" data-parent="#accordion2" href="#collapseStep5">Step 5: Assign Judges and Stewards to Tables<span class="fa fa-user pull-right"></span></a>
							</h4>
						</div>
						<div id="collapseStep5" class="panel-collapse collapse">
							<div class="panel-body">
								<ul class="list-unstyled">
                                	<li>	<a href="<?php echo $base_url; ?>index.php?section=admin&amp;action=assign&amp;go=judging_tables">Assign Judges and Stewards to Tables</a></li>
                                </ul>
							</div>
						</div>
					</div><!-- ./accordion -->
                    <?php if (($_SESSION['jPrefsTablePlanning'] == 0) && ($_SESSION['userAdminObfuscate'] == 0)) { ?>
                    <div class="panel panel-default">
						<div class="panel-heading">
							<h4 class="panel-title">
								<a data-toggle="collapse" data-parent="#accordion2" href="#collapseStep6">Step 6: Add or Update Scores<span class="fa fa-trophy pull-right"></span></a>
							</h4>
						</div>
						<div id="collapseStep6" class="panel-collapse collapse">
							<div class="panel-body">
								<ul class="list-unstyled">
                                	<?php if ($_SESSION['prefsEval'] == 1) { ?><li><a href="<?php echo $base_url; ?>index.php?section=evaluation&amp;go=default&amp;filter=default&amp;view=admin" data-toggle="tooltip" data-placement="top" title="Manage, View and Edit Judges' evaluations of received entries">Manage Entry Evaluations</a></li>
                                    <li><?php echo $import_scores_display; ?></li>
                                    <?php } ?>
                                    <li><a href="<?php echo $base_url; ?>index.php?section=admin&amp;go=judging_scores">All Scores</a></li>
                                </ul>
                                <div class="btn-group" role="group" aria-label="modals">
                                    <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                        For Table... <span class="caret"></span>
                                    </button>
                                    <ul class="dropdown-menu">
                                        <?php echo $score_choose; ?>
                                    </ul>
                                </div>
							</div>
						</div>
					</div><!-- ./accordion -->
                    <div class="panel panel-default">
						<div class="panel-heading">
							<h4 class="panel-title">
								<a data-toggle="collapse" data-parent="#accordion2" href="#collapseStep7">Step 7: Add or Update BOS Places<span class="fa fa-certificate pull-right"></span></a>
							</h4>
						</div>
						<div id="collapseStep7" class="panel-collapse collapse">
							<div class="panel-body">
								<ul class="list-unstyled">
                                	<?php echo $style_types_list; ?>
                                </ul>
							</div>
						</div>
					</div><!-- ./accordion -->
                <?php } ?>
				</div>
				</div><!-- ./accordion right column -->
			</div><!-- ./row -->
		<!-- End 2 Column Accordion -->
	</div><!-- ./left column -->

	<!-- Right Column -->
	<div class="sidebar col col-lg-3 col-md-4 col-sm-12 col-xs-12">
		<div class="panel panel-info">
          	<div class="panel-heading">
            	<h4 class="panel-title">Entries Assigned to Tables</h4>
          	</div>
          	<div class="panel-body">
            <?php if ($sidebar_entries_assigned) { 
                echo $sidebar_assigned_entries_by_location;
				if ($sidebar_all_sessions) { ?>
                <div class="bcoem-sidebar-panel">
                    <hr>
                </div>
                <div class="bcoem-sidebar-panel">
                	<strong class="text-info">All Judging Sessions</strong>
                    <span class="pull-right"><?php echo array_sum($all_loc_total); if ($_SESSION['jPrefsTablePlanning'] == 0) { ?> of <a href="<?php echo $base_url; ?>/index.php?section=admin&amp;go=entries" data-toggle="tooltip" data-placement="top" title="View all entries."><?php echo $row_entry_count['count']; ?></a><?php } ?></span>
                </div>
                <?php } ?>
            <?php } ?>
          	</div>
     	</div>
    </div><!-- ./right sidebar -->
</div><!-- ./row -->
</div><!-- ./bcoem-admin-dashboard-accordion -->
<?php }
if (($manage_tables_default) && ($judging_session)) { ?>
	<?php if ($dbTable == "default") { ?>
    <div class="bcoem-admin-element hidden-print">
        <div class="btn-group" role="group" aria-label="compOrgModal">
            <button type="button" class="btn btn-info btn-sm" data-toggle="modal" data-target="#compOrgModal">
               Competition Organization Info
            </button>
        </div>
        <!-- Modal -->
        <div class="modal fade" id="compOrgModal" tabindex="-1" role="dialog" aria-labelledby="compOrgModalLabel">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header bcoem-admin-modal">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                        <h4 class="modal-title" id="compOrgModalLabel">Judging/Competition Organization Info</h4>
                    </div>
                    <div class="modal-body">
                        <p>Competition organization preferences are set to:</p>
                        <ul>
                            <li><?php if ($_SESSION['jPrefsQueued'] == "Y") echo "Queued Judging (no flights)."; else echo "Non-Queued Judging (with flights)."; ?></li>
                            <li>Maximum Rounds <?php if ($totalRows_judging > 0) echo "(per location)"; ?>: <?php echo $_SESSION['jPrefsRounds']; ?>.</li>
                            <li>Maximum BOS Places (per style type): <?php echo $_SESSION['jPrefsMaxBOS']; ?>.</li>
                            <?php if ($_SESSION['jPrefsQueued'] == "N") { ?>
                            <li>Maximum Entries per Flight: <?php echo $_SESSION['jPrefsFlightEntries']; ?>.</li>
                            <?php } ?>
                        </ul>
                    </div>
                    <div class="modal-footer">
                        <?php if ($_SESSION['userLevel'] == 0) { ?>
                        <a href="<?php echo $base_url; ?>index.php?section=admin&amp;go=judging_preferences" class="btn btn-primary">Update Preferences</a>
                        <?php } ?>
                    	<button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
                    </div>
                </div>
            </div>
        </div><!-- ./modal -->
        <?php if (((NHC) && ($prefix == "_final")) || (!NHC) && ($totalRows_style_type > 0)) { ?>
        <div class="btn-group" role="group" aria-label="BOSModal">
            <button type="button" class="btn btn-info btn-sm" data-toggle="modal" data-target="#BOSModal">
               Best of Show Settings Info
            </button>
        </div>
		<!-- Modal -->
        <div class="modal fade" id="BOSModal" tabindex="-1" role="dialog" aria-labelledby="BOSModalLabel">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header bcoem-admin-modal">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                        <h4 class="modal-title" id="entryFormModalLabel">Best of Show Settings Info</h4>
                    </div>
                    <div class="modal-body">
                        <p>A Best of Show round is enabled for the following Style Types:</p>
                    <ul>
                        <?php echo $bos_modal_body; ?>
                    </ul>
                    </div>
                    <div class="modal-footer">
                        <?php if ($_SESSION['userLevel'] == 0) { ?>
                        <a href="<?php echo $base_url; ?>index.php?section=admin&amp;go=style_types" class="btn btn-primary">Update BOS Settings</a>
                        <?php } ?>
                    	<button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
                    </div>
                </div>
            </div>
        </div><!-- ./modal -->
    <?php } ?>
	<!-- Orphan Styles Modal -->
    <div class="modal fade" id="orphanModal" tabindex="-1" role="dialog" aria-labelledby="orphanModalLabel">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header bcoem-admin-modal">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title" id="orphanModalLabel">Styles with Entries Not Assigned to Tables</h4>
                </div>
                <div class="modal-body">
                    <?php
                    echo $orphan_modal_body_1;
                    if (!empty($orphan_modal_body_2)) echo "<ul>".$orphan_modal_body_2."</ul>";
                    ?>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div><!-- ./modal -->
        <div class="btn-group" role="group" aria-label="orphanModal">
                <button type="button" class="btn btn-info btn-sm" data-toggle="modal" data-target="#orphanModal">
                   Styles Not Assigned to Tables
                </button>
            </div>
        <div class="btn-group" role="group" aria-label="availJudgeModal">
            <button type="button" class="btn btn-info btn-sm" data-toggle="modal" data-target="#availJudgeModal">
               Judges Not Assigned to Tables
            </button>
        </div>
        <div class="btn-group" role="group" aria-label="availStewardModal">
            <button type="button" class="btn btn-info btn-sm" data-toggle="modal" data-target="#availStewardModal">
               Stewards Not Assigned to Tables
            </button>
        </div>
    </div>
<?php } ?>
<?php
if ($totalRows_tables > 0) { ?>
<!-- Table of Judging Tables -->
<script>
 $(document).ready(function() {
	$('#judgingTables').dataTable( {
		"bPaginate" : false,
		"sDom": 'rst',
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
			null,
			<?php if (($totalRows_judging > 1) && ($dbTable == "default"))  { ?>null,<?php } ?>
			<?php if ($dbTable == "default") { ?>{ "asSorting": [  ] }<?php } ?>
			]
		} );
	} );
</script>
<table class="table table-responsive table-bordered table-striped" id="judgingTables">
	<thead>
    <tr>
    	<th>#</th>
        <th>Name</th>
        <th>Style(s)</th>
        <th><?php if ($_SESSION['jPrefsTablePlanning'] == 0) echo "<span class=\"hidden-sm hidden-xs\"><em>Rec'd</em> </span>" ; ?>Entries</th>
        <th class="hidden-xs hidden-sm"><em>Scored</em> Entries</th>
        <th class="hidden-xs hidden-sm">Judge Assignments</th>
        <th class="hidden-xs hidden-sm">Steward Assignments</th>
        <?php if (($totalRows_judging > 1) && ($dbTable == "default"))  { ?>
        <th class="hidden-xs hidden-sm">Location</th>
        <?php } ?>
        <?php if (($action != "print") && ($dbTable == "default")) { ?>
        <th class="hidden-print">Actions</th>
        <?php } ?>
    </tr>
    </thead>
    <tbody>
    <?php echo $manage_tables_default_tbody; ?>
    </tbody>
</table>

<?php }
else {
    if ($judging_session) echo "<p>No tables have been defined yet.</p><p><a class=\"btn btn-primary\" role=\"button\" href=\"".$base_url."index.php?section=admin&amp;go=judging_tables&amp;action=add\"><span class=\"fa fa-plus-circle\"></span> Add a table?</a></p>";
    }
} // end if ($action == "default") ?>

<?php if ((($action == "add") || ($action == "edit")) && ($judging_session)) { ?>
<style>
#sticky-total {
    position: -webkit-sticky;
    position: sticky;
    top: 70px;
    z-index: 999;
    min-width: 200px;
    max-width: 225px;
    -webkit-box-shadow: 0px 0px 10px 1px rgba(0,0,0,0.4);
    -moz-box-shadow: 0px 0px 10px 1px rgba(0,0,0,0.4);
    box-shadow: 0px 0px 10px 1px rgba(0,0,0,0.4);
    /* font-family: initial !important; */
}
</style>
<script type="text/javascript">
function update_table_total(element_id) {

    // Get current total value
    var table_total = $("#table-total").text();

    // Get corresponding value
    var element_total = $("#"+element_id+"-total").text();

    table_total = Number(table_total);
    element_total = Number(element_total);

    // If checked, add value to total
    if ($("#"+element_id).prop("checked") == true){
        
        var overall_total = table_total + element_total;
        $("#table-total").html(overall_total);   
        
    }

    // If unchecked, subtract value from total
    else if ($("#"+element_id).prop("checked") == false){
       
        var overall_total = table_total - element_total;
        
        if (overall_total > 0) {
            $("#table-total").html(overall_total); 
        }

        else {
            $("#table-total").html("0");
        }

    }

    if (overall_total == 0) {
        $("#add-table-submit").prop('disabled', true);
    }

    if (overall_total > 0) {
        $("#add-table-submit").removeAttr('disabled');
    }

}  

</script>
<div id="sticky-total" class="alert bg-primary"><strong>Total for this Table:</strong> <span id="table-total"><?php echo $table_total; ?></span></div>
<?php } // end if ((($action == "add") || ($action == "edit")) && ($judging_session)) ?>

<?php if (($action == "add") && ($judging_session)) { ?>
<script type="text/javascript" language="javascript">

 $(document).ready(function() {
	$('#sortable').dataTable( {
		"bPaginate" : false,
		"sDom": 'frt',
		"bStateSave" : false,
		"bLengthChange" : false,
		"aaSorting": [[1,'asc'],[2,'asc']],
		"bProcessing" : false,
		"aoColumns": [
			{ "asSorting": [  ] },
			null,
			<?php if ($_SESSION['prefsStyleSet'] != "BA") { ?>null,<?php } ?>
			null,
			null
			]
		} );
	} );
</script>
<form data-toggle="validator" role="form" class="form-horizontal" method="post" action="<?php echo $base_url; ?>includes/process.inc.php?section=<?php echo $section; ?>&amp;action=<?php echo $action; ?>&amp;dbTable=<?php echo $judging_tables_db_table; ?>&amp;go=<?php echo $go; ?>" name="form1" id="form1">
<input type="hidden" name="token" value ="<?php if (isset($_SESSION['token'])) echo $_SESSION['token']; ?>">
<div class="bcoem-admin-element hidden-print">
    <div class="form-group"><!-- Form Group REQUIRED Text Input -->
        <label for="tableName" class="col-lg-2 col-md-3 col-sm-4 col-xs-12 control-label">Name</label>
        <div class="col-lg-6 col-md-6 col-sm-8 col-xs-12">
            <div class="input-group has-warning">
                <input class="form-control" id="tableName" name="tableName" type="text" value="" data-error="A table name is required" placeholder="" autofocus required>
                <span class="input-group-addon" id="tableName-addon2"><span class="fa fa-star"></span></span>
            </div>
            <span class="help-block with-errors"></span>
        </div>
    </div><!-- ./Form Group -->

    <div class="form-group"><!-- Form Group NOT REQUIRED Select -->
        <label for="tableNumber" class="col-lg-2 col-md-3 col-sm-4 col-xs-12 control-label">Number</label>
        <div class="col-lg-6 col-md-6 col-sm-8 col-xs-12">
        <!-- Input Here -->
        <select class="selectpicker" name="tableNumber" id="tableNumber" data-size="10" data-width="auto">
            <?php echo $table_numbers_available; ?>
        </select>
        </div>
    </div><!-- ./Form Group -->

    <div class="form-group"><!-- Form Group NOT REQUIRED Select -->
        <label for="tableLocation" class="col-lg-2 col-md-3 col-sm-4 col-xs-12 control-label">Location</label>
        <div class="col-lg-6 col-md-6 col-sm-8 col-xs-12">
        <!-- Input Here -->
        <select class="selectpicker" name="tableLocation" id="tableLocation" data-size="10" data-width="auto">
            <?php echo $table_locations_available; ?>
        </select>
        </div>
    </div><!-- ./Form Group -->

    <div class="form-group">
        <label for="tableStyles" class="col-lg-2 col-md-3 col-sm-4 col-xs-12 control-label">Available Style(s)</label>
        <div class="col-lg-6 col-md-6 col-sm-8 col-xs-12">
        <?php if ($row_entry_count['count'] > 0) { ?>
			<table class="table table-responsive table-bordered small" id="sortable">
				<thead>
				<tr>
					<th width="1%">&nbsp;</th>
					<?php if ($_SESSION['prefsStyleSet'] != "BA") { ?><th width="1%">#</th><?php } ?>
                    <th>Category</th>
					<th>Style</th>
                    <th width="20%"><?php if ($_SESSION['jPrefsTablePlanning'] == 0) echo "<em>Received</em> "; ?>Entries</th>
				</tr>
				</thead>
				<tbody>
				<?php echo $table_styles_available; ?>
				</tbody>
			</table>
		<?php } else echo "<p>There are no available sub-styles.</p>"; ?>
        </div>
    </div><!-- ./Form Group -->
</div><!-- ./bcoem-admin-element -->

<div class="bcoem-admin-element hidden-print">
	<div class="form-group">
		<div class="col-lg-offset-2 col-md-offset-3 col-sm-offset-4">
			<input id="add-table-submit" type="submit" class="btn btn-primary" value="Add Table" disabled>
		</div>
	</div>
</div>

<?php if (isset($_SERVER['HTTP_REFERER'])) { ?>
<input type="hidden" name="relocate" value="<?php echo relocate($_SERVER['HTTP_REFERER'],"default",$msg,$id); ?>">
<?php } else { ?>
<input type="hidden" name="relocate" value="<?php echo relocate($base_url."index.php?section=admin&go=judging_tables","default",$msg,$id); ?>">
<?php } ?>
</form>
<?php } // end if (($action == "add") && ($judging_session)) ?>
<?php if (($action == "edit") && ($judging_session)) { ?>
<!-- Edit a Table -->
<script type="text/javascript" language="javascript">
 $(document).ready(function() {
	$('#sortable').dataTable( {
		"bPaginate" : false,
		"sDom": 'frt',
		"bStateSave" : false,
		"bLengthChange" : false,
		"aaSorting": [[1,'asc'],[2,'asc']],
		"bProcessing" : false,
		"aoColumns": [
			{ "asSorting": [  ] },
			null,
			<?php if ($_SESSION['prefsStyleSet'] != "BA") { ?>null,<?php } ?>
			null,
			null
			]
		} );
	} );
</script>
<form class="form-horizontal" method="post" action="<?php echo $base_url; ?>includes/process.inc.php?section=<?php echo $section; ?>&amp;action=<?php echo $action; ?>&amp;dbTable=<?php echo $judging_tables_db_table; ?>&amp;go=<?php echo $go."&amp;id=".$row_tables_edit['id']; ?>" name="form1" id="form1">
<input type="hidden" name="token" value ="<?php if (isset($_SESSION['token'])) echo $_SESSION['token']; ?>">
<div class="bcoem-admin-element hidden-print">

	<div class="form-group"><!-- Form Group REQUIRED Text Input -->
        <label for="tableName" class="col-lg-2 col-md-3 col-sm-4 col-xs-12 control-label">Name</label>
        <div class="col-lg-6 col-md-6 col-sm-8 col-xs-12">
            <div class="input-group has-warning">
                <input class="form-control" id="tableName" name="tableName" type="text" value="<?php echo $row_tables_edit['tableName']; ?>" placeholder="" autofocus>
                <span class="input-group-addon" id="tableName-addon2"><span class="fa fa-star"></span></span>
            </div>
        </div>
    </div><!-- ./Form Group -->
    <div class="form-group"><!-- Form Group NOT REQUIRED Select -->
        <label for="tableNumber" class="col-lg-2 col-md-3 col-sm-4 col-xs-12 control-label">Number</label>
        <div class="col-lg-6 col-md-6 col-sm-8 col-xs-12">
        <!-- Input Here -->
        <select class="selectpicker" name="tableNumber" id="tableNumber" data-size="10" data-width="auto">
            <?php echo $table_numbers_available;  ?>
        </select>
        </div>
    </div><!-- ./Form Group -->
	<div class="form-group"><!-- Form Group NOT REQUIRED Select -->
        <label for="tableLocation" class="col-lg-2 col-md-3 col-sm-4 col-xs-12 control-label">Location</label>
        <div class="col-lg-6 col-md-6 col-sm-8 col-xs-12">
        <!-- Input Here -->
        <select class="selectpicker" name="tableLocation" id="tableLocation" data-size="10" data-width="auto">
            <?php echo $table_locations_available; ?>
        </select>
        </div>
    </div><!-- ./Form Group -->
    <div class="form-group">
        <label for="tableStyles" class="col-lg-2 col-md-3 col-sm-4 col-xs-12 control-label">Style(s)</label>
        <div class="col-lg-6 col-md-6 col-sm-8 col-xs-12">
        <?php
		if ($row_entry_count['count'] > 0) { ?>
			<table class="table table-responsive table-bordered small" id="sortable">
				<thead>
				<tr>
					<th width="1%">&nbsp;</th>
					<?php if ($_SESSION['prefsStyleSet'] != "BA") { ?><th width="1%">#</th><?php } ?>
					<th>Category</th>
					<th>Style</th>
                    <th width="20%"><em>Received</em> Entries</th>
				</tr>
				</thead>
				<tbody>
				<?php echo $table_styles_available; ?>
				</tbody>
			</table>
		<?php } else echo "There are no available sub-styles."; ?>
        </div>
    </div><!-- ./Form Group -->
</div>
<div class="bcoem-admin-element hidden-print">
	<div class="form-group">
		<div class="col-lg-offset-2 col-md-offset-3 col-sm-offset-4">
			<input type="submit" class="btn btn-primary" value="Edit Table">
		</div>
	</div>
</div>
<?php if (isset($_SERVER['HTTP_REFERER'])) { ?>
<input type="hidden" name="relocate" value="<?php echo relocate($_SERVER['HTTP_REFERER'],"default",$msg,$id); ?>">
<?php } else { ?>
<input type="hidden" name="relocate" value="<?php echo relocate($base_url."index.php?section=admin&go=judging_tables","default",$msg,$id); ?>">
<?php } ?>
</form>
<?php
if ($already_scored) {
?>
<script type="text/javascript">
    var style_ids_at_table = <?php echo json_encode($style_ids_at_table); ?>;
    $(document).ready(function(){
        $('input[type="checkbox"]').click(function(){
            if($(this).is(":checked")){
                if ($.inArray($(this).val(), style_ids_at_table) == -1) {
                    $('#tableStyleAdd').modal('show');
                }
            }
            else if($(this).is(":not(:checked)")){
                if ($.inArray($(this).val(), style_ids_at_table) != -1) {
                    $('#tableStyleRemove').modal('show');
                }
            }
        });
    });
</script>
<!-- Modal -->
<div class="modal fade" id="tableStyleAdd" tabindex="-1" role="dialog" aria-labelledby="tableStyleAddLabel">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header bcoem-admin-modal">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="tableStyleAddLabel">Are You Sure?</h4>
            </div>
            <div class="modal-body">
                <p>Are you sure you want to change this table's styles? <strong>All scores entered for the table will be deleted</strong> if you add this style.</p>
                <p>If you do not wish to delete any scores, uncheck any styles added and instead add them to one or more tables/medal groups that do not have any associated scores.</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-success" data-dismiss="modal">I Understand</button>
            </div>
        </div>
    </div>
</div><!-- ./modal -->
<!-- Modal -->
<div class="modal fade" id="tableStyleRemove" tabindex="-1" role="dialog" aria-labelledby="tableStyleRemoveLabel">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header bcoem-admin-modal">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="tableStyleRemoveLabel">Are You Sure?</h4>
            </div>
            <div class="modal-body">
                <p>Are you sure you want to change this table's styles? <strong>All scores entered for the table will be deleted</strong> if you remove this style.</p>
                <p>Recheck the style to maintain the styles defined for this table or simply navigate away from this page <strong>without</strong> editing the table.</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-success" data-dismiss="modal">I Understand</button>
            </div>
        </div>
    </div>
</div><!-- ./modal -->
<?php }
} // end if (($action == "edit") && ($judging_session))
if (!$judging_session) echo $alert_text_008." ".$alert_text_092." <a href=\"".$base_url."index.php?section=admin&amp;action=add&amp;go=judging\">".$alert_text_009."</a>";
if (($action == "assign") && ($filter == "default")) { ?>
<div class="btn-group" role="group" aria-label="modals">
    <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
        Assign Judges To... <span class="caret"></span>
    </button>
    <ul class="dropdown-menu">
        <?php do { ?>
        <li class="small"><a href="<?php echo $base_url; ?>index.php?section=admin&amp;action=assign&amp;go=judging_tables&amp;filter=judges&amp;id=<?php echo $row_tables['id']; ?>"><?php echo "Table ".$row_tables['tableNumber'].": ".$row_tables['tableName']; ?></a></li>
        <?php } while ($row_tables = mysqli_fetch_assoc($tables)); ?>
    </ul>
</div>
<div class="btn-group" role="group" aria-label="modals">
    <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
        Assign Stewards To... <span class="caret"></span>
    </button>
    <ul class="dropdown-menu">
        <?php do { ?>
        <li class="small"><a href="<?php echo $base_url; ?>index.php?section=admin&amp;action=assign&amp;go=judging_tables&amp;filter=stewards&amp;id=<?php echo $row_tables_edit['id']; ?>"><?php echo "Table ".$row_tables_edit['tableNumber'].": ".$row_tables_edit['tableName']; ?></a></li>
        <?php } while ($row_tables_edit = mysqli_fetch_assoc($tables_edit)); ?>
    </ul>
</div>
<?php } ?>
<script src="<?php echo $base_url;?>js_includes/admin_ajax.min.js"></script>
<?php if (($action == "assign") && ($filter != "default") && ($id != "default")) include ('judging_assign.admin.php'); ?>