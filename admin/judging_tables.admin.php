<?php 
// Rebuild 04.27.17

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

if (($action == "default") && ($filter == "default")) {

    $manage_tables_default = TRUE;
    $sub_lead_text .= "<p>To ensure accuracy, verify that all paid and received entries have been marked as such via the <a href=\"".$base_url."index.php?section=admin&amp;go=entries\">Manage Entries</a> screen.</p>";
	
	if ($totalRows_tables_edit > 0) {
 
		do {
	
			$flight_count = table_choose($section,$go,$action,$row_tables_edit['id'],$view,"default","flight_choose");
			$flight_count = explode("^",$flight_count);
	
			$flight_choose .= "<option value=\"".$base_url;
			$flight_choose .= "index.php?section=admin&amp;go=judging_flights&amp;action=";
			if ($flight_count[0] > 0) $flight_choose .= "edit";
			else $flight_choose .= "add";
			$flight_choose .= "&amp;id=".$row_tables_edit['id']."\">";
			$flight_choose .= "#".$row_tables_edit['tableNumber'].": ".$row_tables_edit['tableName'];
			$flight_choose .= "</option>";
	
			$score_count = table_count_total($row_tables_edit['id']);
			$score_choose .= "<option value=\"".$base_url;
			$score_choose .= "index.php?section=admin&amp;go=judging_scores&amp;action=";
			if ($score_count  > 0) $score_choose .= "edit";
			else $score_choose .= "add";
			$score_choose .= "&amp;id=".$row_tables_edit['id']."\">";
			$score_choose .= "#".$row_tables_edit['tableNumber'].": ".$row_tables_edit['tableName'];
			$score_choose .= "</option>";
	
		} while ($row_tables_edit = mysqli_fetch_assoc($tables_edit));
	
	}
	
	else {
		
		$score_choose .= "<option disabled>No tables have been defined.</option>";
		$flight_choose .= "<option disabled>No tables have been defined.</option>";
		
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

        $loc_total = get_table_info(1,"count_total","default","default",$row_judging['id']);
        $all_loc_total[] = $loc_total;

        $sidebar_assigned_entries_by_location .= "<div class=\"bcoem-sidebar-panel\">";
        $sidebar_assigned_entries_by_location .= "<strong class=\"text-info\">";
        $sidebar_assigned_entries_by_location .= $row_judging['judgingLocName'];
        $sidebar_assigned_entries_by_location .= "</strong>";
        $sidebar_assigned_entries_by_location .= "<span class=\"pull-right\">";
        $sidebar_assigned_entries_by_location .= $loc_total;
        $sidebar_assigned_entries_by_location .= "</span>";
        $sidebar_assigned_entries_by_location .= "</div>";

    } while ($row_judging = mysqli_fetch_assoc($judging));

    do {

        $a = array(get_table_info("1","list",$row_tables['id'],$dbTable,"default"));
        $styles = display_array_content($a,1);
        $received = get_table_info("1","count_total",$row_tables['id'],$dbTable,"default");
        $scored =  get_table_info("1","score_total",$row_tables['id'],$dbTable,"default");

        //get_table_info($input,$method,$id,$dbTable,$param)

        if (($received > $scored) && ($dbTable == "default")) $scored = $scored." <a class=\"hidden-print\" href=\"".$base_url."index.php?section=admin&amp;go=judging_scores&amp;action=edit&amp;id=".$row_tables['id']."\" data-toggle=\"tooltip\" data-placement=\"top\" title=\"Not all scores have been entered for this table. Click to add/edit scores.\"><span class=\"fa fa-lg fa-exclamation-circle text-danger\"></span></a>";
        else $scored = $scored;

        $assigned_judges = assigned_judges($row_tables['id'],$dbTable,$judging_assignments_db_table);
        $assigned_stewards = assigned_stewards($row_tables['id'],$dbTable,$judging_assignments_db_table);

        if (score_count($row_tables['id'],1)) $scoreAction = "edit";
        else $scoreAction = "add";

        $manage_tables_default_tbody .= "<tr>";
        $manage_tables_default_tbody .= "<td class=\"hidden-xs hidden-sm\">".$row_tables['tableNumber']."</td>";
        $manage_tables_default_tbody .= "<td>".$row_tables['tableName']."</td>";
        $manage_tables_default_tbody .= "<td>".$styles."</td>";
        $manage_tables_default_tbody .= "<td>".$received."</td>";
        $manage_tables_default_tbody .= "<td class=\"hidden-xs hidden-sm\">".$scored."</td>";
        $manage_tables_default_tbody .= "<td>".$assigned_judges."</td>";
        $manage_tables_default_tbody .= "<td>".$assigned_stewards."</td>";
        if (($totalRows_judging > 1) && ($dbTable == "default")) $manage_tables_default_tbody .= "<td class=\"hidden-xs hidden-sm\">".table_location($row_tables['id'],$_SESSION['prefsDateFormat'],$_SESSION['prefsTimeZone'],$_SESSION['prefsTimeFormat'],"default")."</td>";
        if ($dbTable == "default") {
            $manage_tables_default_tbody .= "<td nowrap=\"nowrap\" class=\"hidden-print\">";

            // Build edit link
            $manage_tables_default_tbody .= "<a href=\"".$base_url."index.php?section=admin&amp;go=".$go;
            $manage_tables_default_tbody .= "&amp;action=edit&amp;id=".$row_tables['id']."\" data-toggle=\"tooltip\" data-placement=\"top\" title=\"Edit Table ".$row_tables['tableNumber'].": ".$row_tables['tableName']."\">";
            $manage_tables_default_tbody .= "<span class=\"fa fa-lg fa-pencil\"></span>";
            $manage_tables_default_tbody .= "</a> ";

            // Build print pullsheet link
            $manage_tables_default_tbody .= "<a id=\"modal_window_link\" href=\"".$base_url."output/print.output.php?section=pullsheets&amp;go=judging_tables&amp;id=".$row_tables['id']."\" data-toggle=\"tooltip\" data-placement=\"top\" title=\"Print the pullsheet for Table ".$row_tables['tableNumber'].": ".$row_tables['tableName']."\">";
            $manage_tables_default_tbody .= "<span class=\"fa fa-lg fa-print\"></span>";
            $manage_tables_default_tbody .= "</a> ";

            // Build flight link
            if (($_SESSION['jPrefsQueued'] == "N") && (flight_count($row_tables['id'],1))) {
                $manage_tables_default_tbody .= "<a href=\"".$base_url."index.php?section=admin&amp;go=judging_flights&amp;filter=define&amp;action=edit&amp;id=".$row_tables['id']."\" data-toggle=\"tooltip\" data-placement=\"top\" title=\"Add/edit flights for Table ".$row_tables['tableNumber'].": ".$row_tables['tableName']."\">";
                $manage_tables_default_tbody .= "<span class=\"fa fa-lg fa-send\"></span>";
                $manage_tables_default_tbody .= "</a> ";
            }

            //Build add scores link
            $manage_tables_default_tbody .= "<a href=\"".$base_url."index.php?section=admin&amp;go=judging_scores&amp;action=".$scoreAction."&amp;id=".$row_tables['id']."\" data-toggle=\"tooltip\" data-placement=\"top\" title=\"Add/edit scores for Table ".$row_tables['tableNumber'].": ".$row_tables['tableName']."\">";
            $manage_tables_default_tbody .= "<span class=\"fa fa-lg fa-trophy\"></span>";
            $manage_tables_default_tbody .= "</a> ";

            // Build delete link
            $manage_tables_default_tbody .= "<a href=\"".$base_url;
            $manage_tables_default_tbody .= "includes/process.inc.php?section=".$section."&amp;go=".$go."&amp;filter=".$filter."&amp;dbTable=".$judging_tables_db_table."&amp;action=delete&amp;id=".$row_tables['id']."\" data-toggle=\"tooltip\" data-placement=\"top\" title=\"Delete Table ".$row_tables['tableNumber'].": ".$row_tables['tableName']." data-confirm=\"Are you sure you want to delete Table ".$row_tables['tableNumber'].": ".$row_tables['tableName']."? ALL associated FLIGHTS and SCORES will be deleted as well. This cannot be undone.\">";
            $manage_tables_default_tbody .= "<span class=\"fa fa-lg fa-trash-o\"></span>";
            $manage_tables_default_tbody .= "</a> ";
            $manage_tables_default_tbody .= "</td>";
        }

        $manage_tables_default_tbody .= "";
        $manage_tables_default_tbody .= "";
        $manage_tables_default_tbody .= "</tr>";

    } while ($row_tables = mysqli_fetch_assoc($tables));

    do {

        $bos_modal_body .= "<li>";
        $bos_modal_body .= $row_style_type['styleTypeName']." (".bos_method($row_style_type['styleTypeBOSMethod'])." from each table to BOS).";
        $bos_modal_body .= "</li>";

    } while ($row_style_type = mysqli_fetch_assoc($style_type));

    if ($totalRows_tables > 0) {
		
		$a[] = "";
		$y[] = "";
		$z[] = 0;
			
        if (strpos($_SESSION['prefsStyleSet'],"BABDB") !== false) {
			
			// Get custom style ids
			if ($totalRows_styles_custom > 0) {
				do {
					if (get_table_info($row_styles_custom['brewStyleNum']."^".$row_styles_custom['brewStyleGroup'],"count","",$dbTable,"default")) {
						
						$a[] = $row_styles_custom['id']."c"; // Need to add differentiator for custom styles
						$y[] = $row_styles_custom['id']."^".$row_styles_custom['brewStyle']."^Custom";
						
						if (!get_table_info($row_styles_custom['id'],"styles","default","default","default")) {
							$z[] = 1;
							$orphan_modal_body_2 .= "<li>".$row_styles_custom['brewStyleGroup'].$row_styles_custom['brewStyleNum']." ".style_convert($row_styles_custom['brewStyleGroup'],"1").": ".$row_styles_custom['brewStyle']." (".get_table_info($row_styles_custom['brewStyleNum']."^".$row_styles_custom['brewStyleGroup'],"count","default",$dbTable,"default")." entries)</li>";
						}
					}
				} while ($row_styles_custom = mysqli_fetch_assoc($styles_custom));
			}
			
			// Get BA style ids
            foreach ($_SESSION['styles'] as $ba_styles => $stylesData) {
                if (is_array($stylesData) || is_object($stylesData)) {
                    foreach ($stylesData as $key => $ba_style) {
                        $style_value = $ba_style['category']['id']."^".$ba_style['id'];
                        if (get_table_info($style_value,"count","default",$dbTable,"default")) {
							
							$a[] = $ba_style['id'];
							$y[] = $ba_style['id']."^".$ba_style['name']."^".$ba_style['category']['name'];
							
                            if (!get_table_info($ba_style['id'],"styles","default","default","default")) {
                                $z[] = 1;
                                $orphan_modal_body_2 .= "<li>".$ba_style['name']." (".get_table_info($style_value,"count","default",$dbTable,"default")." entries)</li>";
                            }
                        }
                    }
                }
            }
        } else {
            do {
                if (get_table_info($row_styles['brewStyleNum']."^".$row_styles['brewStyleGroup'],"count","","default","default")) {
                    if (!get_table_info($row_styles['id'],"styles","styles","default","default","default")) {
                        $a[] = $row_styles['id'];
						$z[] = 1;
                        $orphan_modal_body_2 .= "<li>".$row_styles['brewStyleGroup'].$row_styles['brewStyleNum']." ".style_convert($row_styles['brewStyleGroup'],"1").": ".$row_styles['brewStyle']." (".get_table_info($row_styles['brewStyleNum']."^".$row_styles['brewStyleGroup'],"count","default",$dbTable,"default")." entries)</li>";
                    }
                }
            } while ($row_styles = mysqli_fetch_assoc($styles));
        }

        $b = array_sum($z);
        if ($b == 0) $orphan_modal_body_1 .= "<p>All styles with entries have been assigned to tables.</p>";
        else $orphan_modal_body_1 .= "<p>The following styles with entries have not been assigned to tables:</p>";
		
    } // end if ($totalRows_tables > 0)

    else {
        $orphan_modal_body_1 .= "<p>No tables have been defined.";
        if ($go == "judging_tables") $orphan_modal_body_1 .= " <a href='index.php?section=admin&amp;go=judging_tables&amp;action=add'>Add a table?</a>";
        $orphan_modal_body_1 .= "</p>";
    } // end else

} // end if (($action == "default") && ($dbTable == "default"))


if (($action == "add") || ($action == "edit")) {

    $all_table_styles_concat = "";
    $all_table_numbers_array = "";
    $all_table_styles_array = "";
    $table_styles_available = "";
	$current_table_styles_array = "";
    $table_numbers_available = "";
    $table_locations_available = "";
    $table_numbers = "";

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

    if ($totalRows_all_table_styles > 0) {

        do { 
            $all_table_styles_concat .= $row_all_table_styles['tableStyles'].",";
            $all_table_numbers_array[] =  $row_all_table_styles['tableNumber'];
        } while ($row_all_table_styles = mysqli_fetch_assoc($all_table_styles));
        
        $all_table_styles_concat = rtrim($all_table_styles_concat,",");
        $all_table_styles_array = explode(",",$all_table_styles_concat);

    }

    for($i=1; $i<=75; $i++) {
		
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

    do {
		
		$selected_table_location = "";
		$disabled_table_location = "";
		
		
        if (($action == "edit") && ($row_tables_edit['tableLocation'] == $row_judging['id'])) $selected_table_location = " SELECTED";
        $table_locations_available .= "<option value=\"".$row_judging['id']."\"".$selected_table_location.">";
        $table_locations_available .= $row_judging['judgingLocName']." (".getTimeZoneDateTime($_SESSION['prefsTimeZone'], $row_judging['judgingDate'], $_SESSION['prefsDateFormat'],  $_SESSION['prefsTimeFormat'], "short", "date-time-no-gmt").")";
        $table_locations_available .= "</option>\n";
		
    } while ($row_judging = mysqli_fetch_assoc($judging));
    
    // BJCP Styles

    if (strpos($_SESSION['prefsStyleSet'],"BABDB") === false) {
        
        do { 
            
            $style_value = $row_styles['brewStyleNum']."^".$row_styles['brewStyleGroup'];
			$received_entry_count_style = get_table_info($style_value,"count","default",$dbTable,"default");
			$table_row_class = "";
			$style_assigned_location = "";
			$style_no_entries = "";
			$disabled_styles = "";
			$selected_styles = "";
			$table_row_class = "bg-success text-success";
			
			$show = get_table_info($row_styles['id'],"styles","default",$dbTable,"default");
			
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
			
			if ($action == "edit") {
			
				$style_assigned_this = get_table_info($row_styles['id'],"styles",$row_tables_edit['id'],$dbTable,"default");
				if (in_array($row_styles['id'],$all_table_styles_array)) $disabled_styles = "DISABLED";
				if (in_array($row_styles['id'],$current_table_styles_array)) $disabled_styles = "";
				if ($style_assigned_this) {
					$table_row_class = "bg-warning";
					$selected_styles = "CHECKED";
				}
			
			}
			
			$disabled_selected_styles = $selected_styles." ".$disabled_styles;
			
			if ((($action == "add") && (!$show)) || ($action == "edit")) {
			
				$table_styles_available .= "<tr class=\"".$table_row_class."\">";
				$table_styles_available .= "<td><input type=\"checkbox\" name=\"tableStyles[]\" value=\"".$row_styles['id']."\" ".$disabled_selected_styles."></td>";
				$table_styles_available .= "<td>".$row_styles['brewStyleGroup'].$row_styles['brewStyleNum']."</td>";
				$table_styles_available .= "<td>".style_convert($row_styles['brewStyleGroup'],"1")."</td>";
				$table_styles_available .= "<td>".$row_styles['brewStyle'].$style_no_entries.$style_assigned_location."</td>";
				$table_styles_available .= "<td>".$received_entry_count_style."</td>";
				$table_styles_available .= "</tr>";
			
			}
            
        } while ($row_styles = mysqli_fetch_assoc($styles));
    
    }
    
    if (strpos($_SESSION['prefsStyleSet'],"BABDB") !== false) {
		
		if ($totalRows_styles_custom > 0) {
			
			do {
				
				$style_value = $row_styles_custom['brewStyleNum']."^".$row_styles_custom['brewStyleGroup']."^Custom";
				$received_entry_count_style = get_table_info($style_value,"count","default",$dbTable,"default");
				$table_row_class = "";
				$style_assigned_location = "";
				$style_no_entries = "";
				$disabled_styles = "";
				$selected_styles = "";
				$table_row_class = "bg-success text-success";
				
				$show = get_table_info($row_styles_custom['id'],"styles","default",$dbTable,"default");
				
				if ($received_entry_count_style == 0) {
					$disabled_styles = "DISABLED";
					$style_no_entries = "<br><em>Disabled. No entries were received for this style.</em>";
					$table_row_class = "bg-grey text-muted";
				}
				
				if (($action == "edit") && (in_array($row_styles_custom['id'],$current_table_styles_array))) $style_assigned_location = "<br><em>Style currently assigned to this table.</em>";
				else $style_assigned_location = get_table_info($row_styles_custom['id'],"assigned","default",$dbTable,"default");
				
				if (!empty($style_assigned_location)) {
					$table_row_class = "bg-danger";
					$disabled_styles = "DISABLED";
				}
				
				if ($action == "edit") {
				
					$style_assigned_this = get_table_info($row_styles_custom['id'],"styles",$row_tables_edit['id'],$dbTable,"default");
					if (in_array($row_styles_custom['id'],$all_table_styles_array)) $disabled_styles = "DISABLED";
					if (in_array($row_styles_custom['id'],$current_table_styles_array)) $disabled_styles = "";
					if ($style_assigned_this) {
						$table_row_class = "bg-warning";
						$selected_styles = "CHECKED";
					}
				
				}
				
				$disabled_selected_styles = $selected_styles." ".$disabled_styles;
				
				if ((($action == "add") && (!$show)) || ($action == "edit")) {
				
					$table_styles_available .= "<tr class=\"".$table_row_class."\">";
					$table_styles_available .= "<td><input type=\"checkbox\" name=\"tableStyles[]\" value=\"".$row_styles_custom['id']."\"".$disabled_selected_styles."></td>";
					$table_styles_available .= "<td>Custom</td>";
					$table_styles_available .= "<td>".$row_styles_custom['brewStyle'].$style_no_entries.$style_assigned_location."</td>";
					$table_styles_available .= "<td>".$received_entry_count_style."</td>";
					$table_styles_available .= "</tr>";
				
				}
				
			} while ($row_styles_custom = mysqli_fetch_assoc($styles_custom));
		}
		
		foreach ($_SESSION['styles'] as $ba_styles => $stylesData) {
                
            if (is_array($stylesData) || is_object($stylesData)) {
                
                foreach ($stylesData as $key => $ba_style) { 
                    
                    $style_value = $ba_style['category']['id']."^".$ba_style['id'];
                    $received_entry_count_style = get_table_info($style_value,"count","default",$dbTable,"default");
                    $table_row_class = "";
                    $style_assigned_location = "";
					$style_no_entries = "";
					$disabled_ba_styles = "";
                    $selected_ba_styles = "";
					$table_row_class = "bg-success text-success";
                    
                    if ($ba_style['category']['name'] == "Hybrid/mixed Beer") $categoryName = "Hybrid/Mixed Beer"; 
                    elseif ($ba_style['category']['name'] == "European-germanic Lager") $categoryName = "European-Germanic Lager";
                    else $categoryName = ucwords($ba_style['category']['name']);
                    
                    $show = get_table_info($ba_style['id'],"styles","default",$dbTable,"default");
                
					if ($received_entry_count_style == 0) {
						$disabled_ba_styles = "DISABLED";
						$style_no_entries = "<br><em>Disabled. No entries were received for this style.</em>";
						$table_row_class = "bg-grey text-muted";
					}
					
					if (($action == "edit") && (in_array($ba_style['id'],$current_table_styles_array))) $style_assigned_location = "<br><em>Style currently assigned to this table.</em>";
					// else $style_assigned_location = get_table_info($ba_style['id'],"assigned","default",$dbTable,"default");
					
					if (!empty($style_assigned_location)) { 
						$table_row_class = "bg-danger";
						$disabled_ba_styles = "DISABLED";
					}
					
					if ((is_array($all_table_styles_array)) && (in_array($ba_style['id'],$all_table_styles_array))) {
						$style_assigned_location = get_table_info($ba_style['id'],"assigned","default",$dbTable,"default");
						$table_row_class = "bg-danger";
						$disabled_ba_styles = "DISABLED";
					}
					
					if ($action == "edit") {
						
						$style_assigned_this = get_table_info($ba_style['id'],"styles",$row_tables_edit['id'],$dbTable,"default");
					
						if (in_array($ba_style['id'],$current_table_styles_array)) $disabled_ba_styles = "";
						if ($style_assigned_this) { 
							$table_row_class = "bg-warning";
							$selected_ba_styles = "CHECKED";
						}
						
					}
					
					$disabled_selected_ba_styles = $selected_ba_styles." ".$disabled_ba_styles;
					
					if ((($action == "add") && (!$show)) || ($action == "edit")) {
					
						$table_styles_available .= "<tr class=\"".$table_row_class."\">\n"; 
						$table_styles_available .= "<td><input type=\"checkbox\" name=\"tableStyles[]\" value=\"".$ba_style['id']."\" ".$disabled_selected_ba_styles."></td>\n";
						$table_styles_available .= "<td>".$categoryName."</td>";
						$table_styles_available .= "<td>".$ba_style['name'].$style_no_entries.$style_assigned_location."</td>\n";
						$table_styles_available .= "<td>".$received_entry_count_style."</td>\n";
						$table_styles_available .= "</tr>\n";
					
					}
                
                } // end foreach ($stylesData as $data => $ba_style)
                
            } // end if (is_array($stylesData) || is_object($stylesData))
            
        } // end foreach ($_SESSION['styles'] as $styles => $stylesData)
        
    }
	
} // end if (($action == "add") || ($action == "edit"))
?>
<p class="lead"><?php echo $_SESSION['contestName'].$title;  ?></p>
<?php if ($dbTable == "default") echo $sub_lead_text; ?>
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
            <li class="small"><a id="modal_window_link" href="<?php echo $base_url; ?>output/print.output.php?section=assignments&amp;go=judging_assignments&amp;filter=judges&amp;view=name&amp;tb=view" title="View Assignments by Name">Judge Assignments By Last Name</a></li>
            <li class="small"><a id="modal_window_link" href="<?php echo $base_url; ?>output/print.output.php?section=assignments&amp;go=judging_assignments&amp;filter=judges&amp;view=table&amp;tb=view" title="View Assignments by Table">Judge Assignments By Table</a></li>
            <li class="small"><a id="modal_window_link" href="<?php echo $base_url; ?>output/print.output.php?section=assignments&amp;go=judging_assignments&amp;filter=stewards&amp;view=name&amp;tb=view" title="View Assignments by Name">Steward Assignments By Last Name</a></li>
            <li class="small"><a id="modal_window_link" href="<?php echo $base_url; ?>output/print.output.php?section=assignments&amp;go=judging_assignments&amp;filter=stewards&amp;view=table&amp;tb=view" title="View Assignments by Table">Steward Assignments By Table</a></li>
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
            <li class="small"><a href="javascript:window.print()">Tables List</a></li>
    		<li class="small"><a id="modal_window_link" href="<?php echo $base_url; ?>output/print.output.php?section=pullsheets&amp;go=judging_tables&amp;id=default">Pullsheets by Table</a></li>
            <li class="small"><a id="modal_window_link" href="<?php echo $base_url; ?>output/print.output.php?section=assignments&amp;go=judging_assignments&amp;filter=judges&amp;view=name" title="Print Judge Assignments by Name">Judge Assignments By Last Name</a></li>
			<li class="small"><a id="modal_window_link" href="<?php echo $base_url; ?>output//print.output.php?section=assignments&amp;go=judging_assignments&amp;filter=judges&amp;view=table" title="Print Judge Assignments by Table">Judge Assignments By Table</a></li>
   			<?php if ($totalRows_judging > 1) { ?>
			<li class="small"><a id="modal_window_link" href="<?php echo $base_url; ?>output//print.output.php?section=assignments&amp;go=judging_assignments&amp;filter=judges&amp;view=location" title="Print Judge Assignments by Location">Judge Assignments By Location</a></li>
    		<?php } ?>
            <li class="small"><a id="modal_window_link" href="<?php echo $base_url; ?>output//print.output.php?section=assignments&amp;go=judging_assignments&amp;filter=stewards&amp;view=name" title="Print Steward Assignments by Name">Steward Assignments By Last Name</a></li>
			<li class="small"><a id="modal_window_link" href="<?php echo $base_url; ?>output//print.output.php?section=assignments&amp;go=judging_assignments&amp;filter=stewards&amp;view=table" title="Print Steward Assignments by Table">Steward Assignments By Table</a></li>
   			<?php if ($totalRows_judging > 1) { ?>
			<li class="small"><a id="modal_window_link" href="<?php echo $base_url; ?>output//print.output.php?section=assignments&amp;go=judging_assignments&amp;filter=stewards&amp;view=location" title="Print Steward Assignments by Location">Steward Assignments By Location</a></li>
    		<?php } ?>
        </ul>
    </div>
	<?php } ?>
    <?php } ?>
</div>


<?php if (($action == "default") && ($dbTable == "default")) { ?>
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
						<div id="collapseStep2" class="panel-collapse collapse">
							<div class="panel-body">
								<ul class="list-unstyled">
                                	<li><a href="<?php echo $base_url; ?>index.php?section=admin&amp;go=judging_tables&amp;action=add">Add a Table</a></li>
									<li><a href="#" data-toggle="modal" data-target="#orphanModal">Styles Not Assigned to Tables</a></li>
                                </ul>
							</div>
						</div>
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
                                        <select class="selectpicker" name="tables" id="tables" onchange="jumpMenu('self',this,0)" data-size="10" data-width="auto">
                                        <option value="" selected disabled>For Table...</option>
                                         <?php echo $flight_choose; ?>
                                        </select>
                                     <?php } else { ?>
                                        <ul class="list-unstyled">
											<li>Disabled... Queued judging selected</li>
                                    	</ul>
                                   	<?php } ?>
                                	</div>
                                </div><!-- ./row -->
							</div>
						</div>
					</div><!-- ./accordion -->
					<div class="panel panel-default">
						<div class="panel-heading">
							<h4 class="panel-title">
								<a data-toggle="collapse" data-parent="#accordion1" href="#collapseStep4">Step 4: <?php echo "Assign ".$assign_to." to Rounds"; ?><span class="fa fa-exchange pull-right"></span></a>
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
                    <div class="panel panel-default">
						<div class="panel-heading">
							<h4 class="panel-title">
								<a data-toggle="collapse" data-parent="#accordion2" href="#collapseStep6">Step 6: Add or Update Scores<span class="fa fa-trophy pull-right"></span></a>
							</h4>
						</div>
						<div id="collapseStep6" class="panel-collapse collapse">
							<div class="panel-body">
								<ul class="list-unstyled">
                                	<li><a href="<?php echo $base_url; ?>index.php?section=admin&amp;go=judging_scores">All Scores</a></li>
                                </ul>
                            	<div class="row">
                                	<div class="col col-lg-6 col-md-12 col-sm-12 col-xs-12">
                                        <select class="selectpicker" name="tables" id="tables" onchange="jumpMenu('self',this,0)" data-size="10" data-width="auto">
                                        <option value="" selected disabled>For Table...</option>
                                            <?php echo $score_choose; ?>
                                        </select>
                                	</div>
                                </div><!-- ./row -->
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
            <?php if ($sidebar_entries_assigned) { echo $sidebar_assigned_entries_by_location; 
				if ($sidebar_all_sessions) { ?>
                <div class="bcoem-sidebar-panel">
                	<strong class="text-info">All Sessions</strong>
                    <span class="pull-right"><?php echo array_sum($all_loc_total); ?> of <a href="<?php echo $base_url; ?>/index.php?section=admin&amp;go=entries" data-toggle="tooltip" data-placement="top" title="View all entries."><?php echo $row_entry_count['count']; ?></a></span>
                </div>
                <?php } ?>
            <?php } ?>
          	</div>
     	</div>        
    </div><!-- ./right sidebar -->
</div><!-- ./row -->
</div><!-- ./bcoem-admin-dashboard-accordion -->
<?php } 
if ($manage_tables_default) { ?>
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
                        <h4 class="modal-title" id="compOrgModalLabel">Competition Organization Info</h4>
                    </div>
                    <div class="modal-body">
                        <p><a href="<?php echo $base_url; ?>index.php?section=admin&amp;go=judging_preferences">Competition organization preferences</a> are set to:</p>
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
                        <a href="<?php echo $base_url; ?>index.php?section=admin&amp;go=judging_preferences" class="btn btn-primary">Update Preferences</a> 
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
                        <p>A Best of Show round is enabled for the following <a href="<?php echo $base_url; ?>index.php?section=admin&amp;go=style_types">Style Types</a>:</p>
                    <ul>
                        <?php echo $bos_modal_body; ?>
                    </ul>
                    </div>
                    <div class="modal-footer">
                        <a href="<?php echo $base_url; ?>index.php?section=admin&amp;go=style_types" class="btn btn-primary">Update BOS Settings</a>
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
                    echo $orphan_modal_body;
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
			{ "asSorting": [  ] }				
			]
		} );
	} );
</script>
<table class="table table-responsive table-bordered table-striped" id="judgingTables"> 
	<thead>
    <tr>
    	<th class="hidden-xs hidden-sm">No.</th>
        <th>Name</th>
        <th width="25%">Style(s)</th>
        <th><em>Rec'd</em> Entries</th>
        <th class="hidden-xs hidden-sm"><em>Scored</em> Entries</th>
        <th>Judges</th>
        <th>Stew<span class="hidden-xs">ards</span></th>
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
else echo "<p>No tables have been defined yet.</p><p><a class=\"btn btn-primary\" role=\"button\" href=\"".$base_url."index.php?section=admin&amp;go=judging_tables&amp;action=add\"><span class=\"fa fa-plus-circle\"></span> Add a table?</a></p>";
} // end if ($action == "default") 
if ($action == "add") { ?>
<script type="text/javascript" language="javascript">
 $(document).ready(function() {
	$('#sortable').dataTable( {
		"bPaginate" : false,
		"sDom": 'frt',
		"bStateSave" : false,
		"bLengthChange" : false,
		<?php if (strpos($_SESSION['prefsStyleSet'],"BABDB") === false) { ?>"aaSorting": [[1,'asc']],<?php } ?>
		<?php if (strpos($_SESSION['prefsStyleSet'],"BABDB") !== false) { ?>"aaSorting": [[1,'asc'],[2,'asc']],<?php } ?>
		"bProcessing" : false,
		"aoColumns": [
			{ "asSorting": [  ] },
			<?php if (strpos($_SESSION['prefsStyleSet'],"BABDB") === false) { ?>null,<?php } ?>
			null,
			null,
			null
			]
		} );
	} );
</script>
<form data-toggle="validator" role="form" class="form-horizontal" method="post" action="<?php echo $base_url; ?>includes/process.inc.php?section=<?php echo $section; ?>&amp;action=<?php echo $action; ?>&amp;dbTable=<?php echo $judging_tables_db_table; ?>&amp;go=<?php echo $go; ?>" name="form1" id="form1">
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
					<?php if (strpos($_SESSION['prefsStyleSet'],"BABDB") === false) { ?><th width="1%">#</th><?php } ?>
                    <?php if (strpos($_SESSION['prefsStyleSet'],"BABDB") !== false) { ?><th>Category</th><?php } ?>
					<th>Style</th>
					<?php if (strpos($_SESSION['prefsStyleSet'],"BABDB") === false) { ?><th>Style</th><?php } ?>
                    <th width="20%"><em>Received</em> Entries</th>
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
			<input type="submit" class="btn btn-primary" value="Add Table">
		</div>
	</div>
</div>
<?php if (isset($_SERVER['HTTP_REFERER'])) { ?>
<input type="hidden" name="relocate" value="<?php echo relocate($_SERVER['HTTP_REFERER'],"default",$msg,$id); ?>">
<?php } else { ?>
<input type="hidden" name="relocate" value="<?php echo relocate($base_url."index.php?section=admin&go=judging_tables","default",$msg,$id); ?>">
<?php } ?>
</form>
<?php } // end if ($action == "add") ?>
<?php if ($action == "edit") { ?>
<!-- Edit a Table -->
<script type="text/javascript" language="javascript">
 $(document).ready(function() {
	$('#sortable').dataTable( {
		"bPaginate" : false,
		"sDom": 'frt',
		"bStateSave" : false,
		"bLengthChange" : false,
		<?php if (strpos($_SESSION['prefsStyleSet'],"BABDB") === false) { ?>"aaSorting": [[1,'asc']],<?php } ?>
		<?php if (strpos($_SESSION['prefsStyleSet'],"BABDB") !== false) { ?>"aaSorting": [[1,'asc'],[2,'asc']],<?php } ?>
		"bProcessing" : false,
		"aoColumns": [
			{ "asSorting": [  ] },
			<?php if (strpos($_SESSION['prefsStyleSet'],"BABDB") === false) { ?>null,<?php } ?>
			null,
			null,
			null
			]
		} );
	} );
</script>
<form class="form-horizontal" method="post" action="<?php echo $base_url; ?>includes/process.inc.php?section=<?php echo $section; ?>&amp;action=<?php echo $action; ?>&amp;dbTable=<?php echo $judging_tables_db_table; ?>&amp;go=<?php echo $go."&amp;id=".$row_tables_edit['id']; ?>" name="form1" id="form1" onSubmit="return CheckRequiredFields()">

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
					<?php if (strpos($_SESSION['prefsStyleSet'],"BABDB") === false) { ?><th width="1%">#</th><?php } ?>
                    <?php if (strpos($_SESSION['prefsStyleSet'],"BABDB") !== false) { ?><th>Category</th><?php } ?>
					<th>Style</th>
					<?php if (strpos($_SESSION['prefsStyleSet'],"BABDB") === false) { ?><th>Style</th><?php } ?>
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
    $(document).ready(function(){
        $('input[type="checkbox"]').click(function(){
            if($(this).is(":checked")){
                confirm("Are you sure you want to change this table\'s styles? All scores entered for the table will be deleted if you add this style.");
            }
            else if($(this).is(":not(:checked)")){
                confirm("Are you sure you want to change this table\'s styles? All scores entered for the table will be deleted if you remove this style.");
            }
        });
    });
</script>
<?php } 
} // end if ($action == "edit") ?>


<?php if (($action == "assign") && ($filter == "default")) { ?>
<div class="form-horizontal">
    <div class="form-group"><!-- Form Group NOT REQUIRED Select -->
        <label for="assign_judges" class="col-lg-2 col-md-3 col-sm-4 col-xs-12 control-label">Assign Judges To</label>
        <div class="col-lg-6 col-md-6 col-sm-8 col-xs-12">
        <!-- Input Here -->
        <select class="selectpicker" name="assign_judges" id="assign_judges" onchange="jumpMenu('self',this,0)" data-live-search="true" data-size="10" data-width="auto">
            <option value="" disabled selected>Choose Below...</option>
            <?php do { ?>
            <option value="index.php?section=admin&amp;action=assign&amp;go=judging_tables&amp;filter=judges&amp;id=<?php echo $row_tables['id']; ?>"><?php echo "Table ".$row_tables['tableNumber'].": ".$row_tables['tableName']; ?></option>
            <?php } while ($row_tables = mysqli_fetch_assoc($tables)); ?>
       </select>
        </div>
    </div><!-- ./Form Group -->
    
    <div class="form-group"><!-- Form Group NOT REQUIRED Select -->
        <label for="assign_judges" class="col-lg-2 col-md-3 col-sm-4 col-xs-12 control-label">Assign Stewards To</label>
        <div class="col-lg-6 col-md-6 col-sm-8 col-xs-12">
        <!-- Input Here -->
        <select class="selectpicker" name="assign_judges" id="assign_judges" onchange="jumpMenu('self',this,0)" data-live-search="true" data-size="10" data-width="auto">
            <option value="" disabled selected>Choose Below...</option>
            <?php do { ?>
            <option value="index.php?section=admin&amp;action=assign&amp;go=judging_tables&amp;filter=stewards&amp;id=<?php echo $row_tables_edit['id']; ?>"><?php echo "Table ".$row_tables_edit['tableNumber']." ".$row_tables_edit['tableName']; ?></option>
            <?php } while ($row_tables_edit = mysqli_fetch_assoc($tables_edit)); ?>
       </select>
        </div>
    </div><!-- ./Form Group -->
</div>
<?php } ?>
<?php if (($action == "assign") && ($filter != "default") && ($id != "default")) include ('judging_assign.admin.php'); ?>