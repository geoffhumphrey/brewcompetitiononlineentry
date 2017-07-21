<?php

function is_dir_empty ($dir){ 
     return (($files = @scandir($dir)) && count($files) <= 2); 
}


function directory_contents_dropdown($directory,$file_name_selected) {
	
	$handle = opendir($directory);
	$filelist[] = "";
	
	while ($file = readdir($handle)) {
	   
	   if ((!is_dir($file)) && (!is_link($file))) {
			$filelist[] .= $file;
	   }
	   
	}
	
	sort($filelist, SORT_NATURAL | SORT_FLAG_CASE);
	
	$return = "";
	foreach ($filelist as $filename) {
		$selected = "";
		if ($file_name_selected == $filename) $selected = " selected";
		$return .= "<option value=\"".$filename."\"".$selected.">";
		$return .= $filename;
		$return .= "</option>";
	}
	
	return $return;
}

function table_count_total($input) {
	require(CONFIG.'config.php');
	mysqli_select_db($connection,$database);
	
	$query_scores_1 = sprintf("SELECT COUNT(*) as 'count' FROM %s WHERE scoreTable='%s'", $prefix."judging_scores", $input);
	$scores_1 = mysqli_query($connection,$query_scores_1) or die (mysqli_error($connection));
	$row_scores_1 = mysqli_fetch_assoc($scores_1);
	
	return $row_scores_1['count'];
}

function bos_place($eid) { 
	require(CONFIG.'config.php');
	mysqli_select_db($connection,$database);
	
	$query_bos_place = sprintf("SELECT scorePlace,scoreEntry FROM %s WHERE eid='$eid'", $prefix."judging_scores_bos");
	$bos_place = mysqli_query($connection,$query_bos_place) or die (mysqli_error($connection));
	$row_bos_place = mysqli_fetch_assoc($bos_place);
	
	$return = $row_bos_place['scorePlace']."-".$row_bos_place['scoreEntry'];
	return $return;
}

function bos_method($value) {
	
	switch($value) {
		case "1": $bos_method = "1st place only";
		break;
		case "2": $bos_method = "1st and 2nd places only";
		break;
		case "3": $bos_method = "1st, 2nd, and 3rd places";
		break;
		case "4": $bos_method = "Defined by Admin";
		break;
	}
	
	return $bos_method;
}

function bos_entry_info($eid,$table_id,$filter) {
	
	require(CONFIG.'config.php');
	mysqli_select_db($connection,$database);
	
	if ($table_id == "default") $table_id = 1; else $table_id = $table_id;
	
	if ($filter != "default") {
		$entry_db_table = $prefix."brewing_".$filter;
		$judging_tables_db_table = $prefix."judging_tables_".$filter;
		$bos_scores_db_table = $prefix."judging_scores_bos_".$filter;
		$brewer_db_table = $prefix."brewer_".$filter;
	}
	
	else {
		$entry_db_table = $prefix."brewing";
		$judging_tables_db_table = $prefix."judging_tables";
		$bos_scores_db_table = $prefix."judging_scores_bos";
		$brewer_db_table = $prefix."brewer";
	}
	
	$query_entries_1 = sprintf("SELECT id,brewStyle,brewCategorySort,brewCategory,brewSubCategory,brewName,brewBrewerFirstName,brewBrewerLastName,brewJudgingNumber,brewBrewerID FROM %s WHERE id='%s'", $entry_db_table, $eid);
	$entries_1 = mysqli_query($connection,$query_entries_1) or die (mysqli_error($connection));
	$row_entries_1 = mysqli_fetch_assoc($entries_1);
	$style = $row_entries_1['brewCategorySort'].$row_entries_1['brewSubCategory'];

	$query_tables_1 = sprintf("SELECT id,tableName,tableNumber FROM %s WHERE id='%s'", $judging_tables_db_table, $table_id);
	$tables_1 = mysqli_query($connection,$query_tables_1) or die (mysqli_error($connection));
	$row_tables_1 = mysqli_fetch_assoc($tables_1);
	$totalRows_tables = mysqli_num_rows($tables_1);
		
	$query_bos_place_1 = sprintf("SELECT id,scorePlace,scoreEntry FROM %s WHERE eid='%s'", $bos_scores_db_table, $eid);
	$bos_place_1 = mysqli_query($connection,$query_bos_place_1) or die (mysqli_error($connection));
	$row_bos_place_1 = mysqli_fetch_assoc($bos_place_1);
	
	$query_brewer = sprintf("SELECT brewerLastName,brewerFirstName,brewerBreweryName FROM %s WHERE id='%s'", $brewer_db_table, $row_entries_1['brewBrewerID']);
	$brewer = mysqli_query($connection,$query_brewer) or die (mysqli_error($connection));
	$row_brewer = mysqli_fetch_assoc($brewer);
	
	$return = 
	$row_entries_1['brewStyle']."^".  			// 0
	$row_entries_1['brewCategorySort']."^".  	// 1
	$row_entries_1['brewCategory']."^".  		// 2
	$row_entries_1['brewSubCategory']."^".  		// 3
	$row_brewer['brewerFirstName']."^".  	// 4
	$row_brewer['brewerLastName']."^".  	// 5
	$row_entries_1['brewJudgingNumber']."^".   	// 6
	$row_tables_1['id']."^".  					// 7
	$row_tables_1['tableName']."^".   			// 8
	$row_tables_1['tableNumber']."^".  			// 9
	$row_bos_place_1['scorePlace']."^".  		// 10
	$row_bos_place_1['scoreEntry']."^".  		// 11
	$row_entries_1['brewName']."^".  			// 12
	$row_entries_1['id']."^".   					// 13
	$row_bos_place_1['id']."^".   				// 14
	$row_entries_1['brewBrewerID']."^". 				// 15
	$row_brewer['brewerBreweryName']; //16
	
	return $return;
}

function style_type_info($type) {
	require(CONFIG.'config.php');
	mysqli_select_db($connection,$database);
	
	$query_style_type = sprintf("SELECT * FROM %s WHERE id='%s'",$prefix."style_types",$type);
	$style_type = mysqli_query($connection,$query_style_type) or die (mysqli_error($connection));
	$row_style_type = mysqli_fetch_assoc($style_type);
	
	$return = $row_style_type['styleTypeBOS']."^".$row_style_type['styleTypeBOSMethod']."^".$row_style_type['styleTypeName'];
	return $return;
}


function score_style_data($value) {
		
	require(CONFIG.'config.php');
	mysqli_select_db($connection,$database);
	
	if (strpos($_SESSION['prefsStyleSet'],"BABDB") === false) {
	
	$query_styles = sprintf("SELECT brewStyleGroup,brewStyleNum,brewStyle,brewStyleType FROM %s WHERE id='%s'", $prefix."styles", $value);
	$styles = mysqli_query($connection,$query_styles) or die (mysqli_error($connection));
	$row_styles = mysqli_fetch_assoc($styles);
	
	if ($row_styles['brewStyleType'] == "") $styleType = 1; else $styleType = $row_styles['brewStyleType'];
	
	$return = 
	$row_styles['brewStyleGroup']."^". //0
	$row_styles['brewStyleNum']."^". //1
	$row_styles['brewStyle']."^". //2
	$styleType; //3
	
	}
	
	else {
		
		include (INCLUDES.'ba_constants.inc.php');
		
		$value1 = ($value - 1);
		
		// Custom Styles
		if ($value > 500) {
			$query_styles = sprintf("SELECT brewStyleGroup,brewStyleNum,brewStyle,brewStyleType FROM %s WHERE id='%s'", $prefix."styles", $value);
			$styles = mysqli_query($connection,$query_styles) or die (mysqli_error($connection));
			$row_styles = mysqli_fetch_assoc($styles);
			
			$return = 
			$row_styles['brewStyleGroup']."^". //0
			$row_styles['brewStyleNum']."^". //1
			$row_styles['brewStyle']."^"; //2
			
			if ($row_styles['brewStyleType'] == "") $return .= 1; else $return .= $row_styles['brewStyleType'];
		}
		
		else {
			
			$return = $_SESSION['styles']['data'][$value1]['id']."^"; //0
			$return .= $_SESSION['styles']['data'][$value1]['id']."^"; //1
			$return .= $_SESSION['styles']['data'][$value1]['name']."^"; //2
			
			if (in_array($_SESSION['styles']['data'][$value1]['categoryId'],$ba_beer_categories)) $return .= 1; //3
			if ((in_array($_SESSION['styles']['data'][$value1]['categoryId'],$ba_mead_cider_categories)) && (in_array($_SESSION['styles']['data'][$value1]['id'],$ba_mead))) $return .= 3; //3
			if ((in_array($_SESSION['styles']['data'][$value1]['categoryId'],$ba_mead_cider_categories)) && (in_array($_SESSION['styles']['data'][$value1]['id'],$ba_cider))) $return .= 2; //3
		
		}
		
	}
	
	return $return;

}

function score_entry_data($value) {
	
	require(CONFIG.'config.php');
	mysqli_select_db($connection,$database);
	
	$query_scores = sprintf("SELECT id,eid,bid,scoreEntry,scorePlace,scoreMiniBOS FROM %s WHERE eid='%s'", $prefix."judging_scores", $value);
	$scores = mysqli_query($connection,$query_scores) or die (mysqli_error($connection));
	$row_scores = mysqli_fetch_assoc($scores);
	
	$return =
	$row_scores['id']."^". //0
	$row_scores['eid']."^". //1
	$row_scores['bid']."^". //2
	$row_scores['scoreEntry']."^". //3
	$row_scores['scorePlace']."^". //4
	$row_scores['scoreMiniBOS']; //5
	
	return $return;
	
}


function text_number($n) {
    # Array holding the teen numbers. If the last 2 numbers of $n are in this array, then we'll add 'th' to the end of $n
    $teen_array = array(11, 12, 13, 14, 15, 16, 17, 18, 19);
   
    # Array holding all the single digit numbers. If the last number of $n, or if $n itself, is a key in this array, then we'll add that key's value to the end of $n
    $single_array = array(1 => 'st', 2 => 'nd', 3 => 'rd', 4 => 'th', 5 => 'th', 6 => 'th', 7 => 'th', 8 => 'th', 9 => 'th', 0 => 'th');
   
    # Store the last 2 digits of $n in order to check if it's a teen number.
    $if_teen = substr($n, -2, 2);
   
    # Store the last digit of $n in order to check if it's a teen number. If $n is a single digit, $single will simply equal $n.
    $single = substr($n, -1, 1);
   
    # If $if_teen is in array $teen_array, store $n with 'th' concantenated onto the end of it into $new_n
    if (in_array($if_teen, $teen_array)) {
        $new_n = $n . 'th';
    	}
    # $n is not a teen, so concant the appropriate value of it's $single_array key onto the end of $n and save it into $new_n
    elseif ($single_array[$single])  {
        $new_n = $n . $single_array[$single];   
    	}
		
    # Return new
    return $new_n;
}

function table_choose($section,$go,$action,$filter,$view,$script_name,$method) {
	require(CONFIG.'config.php');
	mysqli_select_db($connection,$database);
	
	$table_choose = "";
	
	if ($method == "flight_choose") {
		$query_flights = sprintf("SELECT flightTable FROM %s WHERE flightTable='%s'", $prefix."judging_flights", $filter);
		$flights = mysqli_query($connection,$query_flights) or die (mysqli_error($connection));
		$row_flights = mysqli_fetch_assoc($flights);
		$totalRows_flights = mysqli_num_rows($flights);  
		$table_choose = $totalRows_flights."^".$row_flights['flightTable'];
		//$table_choose = $query_flights;
	}
	
	else {
		if ($method == "thickbox") $class = 'class="menuItem" id="modal_window_link"';
		if ($method == "none") $class = 'class="menuItem"';
		
		$random = random_generator(7,2);
		
		$query_tables = sprintf("SELECT * FROM %s ORDER BY tableNumber ASC", $prefix."judging_tables");
		$tables = mysqli_query($connection,$query_tables) or die (mysqli_error($connection));
		$row_tables = mysqli_fetch_assoc($tables);
		$totalRows_tables = mysqli_num_rows($tables);
		
		do {
			if ($filter == "mini_bos") $table_choose .= '<li class="small"><a id="modal_window_link" href="'.$script_name.'?section='.$section.'&go='.$go.'&action='.$action.'&filter='.$filter.'&view='.$view.'&id='.$row_tables['id'].'" title="Print '.$row_tables['tableName'].'">'.$row_tables['tableNumber'].': '.$row_tables['tableName'].' (Mini-BOS)</a></li>';
			else $table_choose .= '<li class="small"><a id="modal_window_link" href="'.$script_name.'?section='.$section.'&go='.$go.'&action='.$action.'&filter='.$filter.'&view='.$view.'&id='.$row_tables['id'].'" title="Print '.$row_tables['tableName'].'">'.$row_tables['tableNumber'].': '.$row_tables['tableName'].' </a></li>';
		} while ($row_tables = mysqli_fetch_assoc($tables));
		
	}
	
	return $table_choose;
	
}

function style_choose($section,$go,$action,$filter,$view,$script_name,$method) {
	
	require(CONFIG.'config.php');
	mysqli_select_db($connection,$database);
	
	if ($_SESSION['prefsStyleSet'] == "BJCP2008") $end = 28;
	if ($_SESSION['prefsStyleSet'] == "BJCP2015") $end = 34;
	
	if ($method == "thickbox") { $suffix = ''; $class = 'class="menuItem" id="modal_window_link"'; }
	
	if ($method == "none") { $suffix = '';  $class = 'class="menuItem"'; }
	
	$random = random_generator(7,2);
	
	$style_choose = '<div class="menuBar"><a class="menuButton" href="#" onclick="#" onmouseover="buttonMouseover(event, \'menu_categories'.$random.'\');">Select Below...</a></div>';
	$style_choose .= '<div id="menu_categories'.$random.'" class="menu" onmouseover="menuMouseover(event)">';
	for($i=1; $i<29; $i++) { 
		if ($i <= 9) $num = "0".$i; else $num = $i;
		$query_entry_count = sprintf("SELECT COUNT(*) as 'count' FROM %s WHERE brewCategory='%s'", $prefix."brewing", $i);
		$result = mysqli_query($connection,$query_entry_count) or die (mysqli_error($connection));
		$row = mysqli_fetch_array($result);
		//if ($num == $filter) $selected = ' "selected"'; else $selected = '';
		if ($row['count'] > 0) { $style_choose .= '<a '.$class.' style="font-size: 0.9em; padding: 1px;" href="'.$script_name.'?section='.$section.'&go='.$go.'&action='.$action.'&filter='.$num.$suffix.'&view='.$view.'" title="Print '.style_convert($i,"1").'">'.$num.' '.style_convert($i,"1").' ('.$row['count'].' entries)</a>'; }
	}
	
	$query_styles = sprintf("SELECT brewStyle,brewStyleGroup FROM %s WHERE brewStyleGroup > %s", $prefix."styles",$end);
	$styles = mysqli_query($connection,$query_styles) or die (mysqli_error($connection));
	$row_styles = mysqli_fetch_assoc($styles);
	$totalRows_styles = mysqli_num_rows($styles);
	
	do {  
		$query_entry_count = sprintf("SELECT COUNT(*) as 'count' FROM %s WHERE brewCategorySort='%s'", $prefix."brewing", $row_styles['brewStyleGroup']);
		$result = mysqli_query($connection,$query_entry_count) or die (mysqli_error($connection));
		$row = mysqli_fetch_array($result);
		//if ($row_styles['brewStyleGroup'] == $filter) $selected = ' "selected"'; else $selected = '';
		if ($row['count'] > 0) { $style_choose .= '<a '.$class.' style="font-size: 0.9em; padding: 1px;" href="'.$script_name.'?section='.$section.'&go='.$go.'&action='.$action.'&filter='.$row_styles['brewStyleGroup'].$suffix.'" title="Print '.$row_styles['brewStyle'].'">'.$row_styles['brewStyleGroup'].' '.$row_styles['brewStyle'].' ('.$row['count'].' entries)</a>'; } 
	} while ($row_styles = mysqli_fetch_assoc($styles));
	
	
	$style_choose .= '</div>';
	return $style_choose;   			
}

function flight_count($table_id,$method) {
	require(CONFIG.'config.php');
	mysqli_select_db($connection,$database);
	$query_flights = sprintf("SELECT COUNT(*) as 'count' FROM %s WHERE flightTable='%s'", $prefix."judging_flights", $table_id);
	$flights = mysqli_query($connection,$query_flights) or die (mysqli_error($connection));
	$row_flights = mysqli_fetch_assoc($flights);
	
	switch($method) {
		case "1": if ($row_flights['count'] > 0) return true; else return false;
		break;
		
		case "2": return $row_flights['count'];
		break;
	}
}

function orphan_styles() { 
	require(CONFIG.'config.php');
	
	if ($_SESSION['prefsStyleSet'] == "BJCP2008") $end = 28;
	if ($_SESSION['prefsStyleSet'] == "BJCP2015") $end = 34;
	
	$query_styles = sprintf("SELECT id,brewStyle,brewStyleType FROM %s WHERE brewStyleGroup >= %s", $prefix."styles",$end);
	$styles = mysqli_query($connection,$query_styles) or die (mysqli_error($connection));
	$row_styles = mysqli_fetch_assoc($styles);
	$totalRows_styles = mysqli_num_rows($styles);
	
	$query_style_types = sprintf("SELECT id FROM %s WHERE styleTypeOwn = 'custom'", $prefix."style_types");
	$style_types = mysqli_query($connection,$query_style_types) or die (mysqli_error($connection));
	$row_style_types = mysqli_fetch_assoc($style_types);
	$totalRows_style_types = mysqli_num_rows($style_types);
	
	do { $a[] = style_type($row_style_types['id'], "2", "bcoe"); } while ($row_style_types = mysqli_fetch_assoc($style_types));

	$return = "";
	if ($totalRows_styles > 0) {
		do {
			if (!in_array($row_styles['brewStyleType'], $a)) { 
				if ($row_styles['brewStyleType'] > 3) $return .= "<p><a href='index.php?section=admin&amp;go=styles&amp;action=edit&amp;id=".$row_styles['id']."'><span class='icon'><img src='".$base_url."images/pencil.png' alt='Edit ".$row_styles['brewStyle']."' title='Edit ".$row_styles['brewStyle']."'></span></a>".$row_styles['brewStyle']."</p>";
			}
		} while ($row_styles = mysqli_fetch_assoc($styles));
	}
	if ($return == "") $return .= "<p>All custom styles have a valid style type associated with them.</p>";
	return $return;

}

function score_table_choose($dbTable,$judging_tables_db_table,$judging_scores_db_table) {
	require(CONFIG.'config.php');
	mysqli_select_db($connection,$database);
		
	$query_tables = "SELECT id,tableNumber,tableName FROM $judging_tables_db_table ORDER BY tableNumber ASC";
	$tables = mysqli_query($connection,$query_tables) or die (mysqli_error($connection));
	$row_tables = mysqli_fetch_assoc($tables);
	$totalRows_tables = mysqli_num_rows($tables); 
	//echo $query_tables;
	
	$r = "";
	
	if ($totalRows_tables > 0) {
	//$r = "<select class=\"form-control input-sm bcoem-admin-dashboard-select\" name=\"table_choice_1\" id=\"table_choice_1\" onchange=\"jumpMenu('self',this,0)\">";
	//$r .= "<option selected disabled>Add scores to...</option>";
		do { 
		$query_scores = sprintf("SELECT COUNT(*) as 'count' FROM $judging_scores_db_table WHERE scoreTable='%s'", $row_tables['id']);
		$scores = mysqli_query($connection,$query_scores) or die (mysqli_error($connection));
		$row_scores = mysqli_fetch_assoc($scores);
		if ($row_scores['count'] > 0) $a = "edit"; else $a = "add";
        	$r .= "<li class=\"small\"><a href=\"index.php?section=admin&amp;&go=judging_scores&amp;action=".$a."&amp;id=".$row_tables['id']."\">Table ".$row_tables['tableNumber'].": ".$row_tables['tableName']."</a></li>"; 
		} while ($row_tables = mysqli_fetch_assoc($tables));
     //$r .= "</select>";
	} 
	 else $r = "<li class=\"disabled small\"><a href=\"#\">No tables have been defined</a></li>";
	 return $r;
}

function score_custom_winning_choose($special_best_info_db_table,$special_best_data_db_table) {
	require(CONFIG.'config.php');
	mysqli_select_db($connection,$database);
	$query_sbi = "SELECT id,sbi_name FROM $special_best_info_db_table ORDER BY sbi_name ASC";
	$sbi = mysqli_query($connection,$query_sbi) or die (mysqli_error($connection));
	$row_sbi = mysqli_fetch_assoc($sbi);
	$totalRows_sbi = mysqli_num_rows($sbi); 
	
	$r = "";
	
	if ($totalRows_sbi > 0) {
	//$r = "<select class=\"form-control input-sm bcoem-admin-dashboard-select\" name=\"sbi_choice_1\" id=\"sbi_choice_1\" onchange=\"jumpMenu('self',this,0)\">";
	//$r .= "<option>Choose Below:</option>";
		do { 
		$query_scores = sprintf("SELECT COUNT(*) as 'count' FROM $special_best_data_db_table WHERE sid='%s'", $row_sbi['id']);
		$scores = mysqli_query($connection,$query_scores) or die (mysqli_error($connection));
		$row_scores = mysqli_fetch_assoc($scores);
		if ($row_scores['count'] > 0) $a = "edit"; else $a = "add";
        	$r .= "<li class=\"small\"><a href=\"index.php?section=admin&amp;&go=special_best_data&amp;action=".$a."&amp;id=".$row_sbi['id']."\">".$row_sbi['sbi_name']."</a></li>";
		} while ($row_sbi = mysqli_fetch_assoc($sbi));
     //$r .= "</select>";
	} 
	else { 
		$r = "<li class=\"disabled small\"><a href=\"#\">No custom categories have been defined</a></li>";
		$r .= "<li role=\"separator\" class=\"divider\"></li>";
		$r .= "<li class=\"small\"><a href=\"".$base_url."index.php?section=admin&amp;go=special_best&amp;action=add\">Add a Custom Style</a></li>";
	}
	return $r;
}

function participant_choose($brewer_db_table,$pro_edition) {
	require(CONFIG.'config.php');	
	mysqli_select_db($connection,$database);
	
	//$query_brewers = "SELECT uid,brewerFirstName,brewerLastName FROM $brewer_db_table ORDER BY brewerLastName";
	if ($pro_edition == 1) $query_brewers = "SELECT uid,brewerBreweryName FROM $brewer_db_table WHERE brewerBreweryName IS NOT NULL ORDER BY brewerBreweryName ASC";
	else $query_brewers = "SELECT uid,brewerFirstName,brewerLastName FROM $brewer_db_table ORDER BY brewerLastName ASC";
	$brewers = mysqli_query($connection,$query_brewers) or die (mysqli_error($connection));
	$row_brewers = mysqli_fetch_assoc($brewers);
	
	$output = "";
	$output .= "<select class=\"selectpicker\" name=\"participants\" id=\"participants\" onchange=\"jumpMenu('self',this,0)\" data-size=\"15\" data-width=\"auto\" data-live-search=\"true\">";
	$output .= "<option value=\"\" selected disabled data-icon=\"fa fa-plus-circle\">Add an Entry For...</option>";
	do { 
		if ($pro_edition == 1) $output .= "<option value=\"index.php?section=brew&amp;go=entries&amp;filter=".$row_brewers['uid']."&amp;action=add\" data-content=\"<span class='small'>".$row_brewers['brewerBreweryName']."</span>\">".$row_brewers['brewerBreweryName']."</option>"; 
		
		else $output .= "<option value=\"index.php?section=brew&amp;go=entries&amp;filter=".$row_brewers['uid']."&amp;action=add\" data-content=\"<span class='small'>".$row_brewers['brewerLastName'].", ".$row_brewers['brewerFirstName']."</span>\">".$row_brewers['brewerLastName'].", ".$row_brewers['brewerFirstName']."</option>"; 
	} while ($row_brewers = mysqli_fetch_assoc($brewers)); 
	$output .= "</select>";
	
	return $output;
}

function admin_help($go,$header_output,$action,$filter) {
	include (CONFIG.'config.php');
	switch($go) {
		case "preferences": $page = "site_prefs";
		break;
		
		case "judging_preferences": $page = "comp_org_prefs";
		break;
		
		case "style_types": $page = "style_types";
		break;
		
		case "styles": 
			switch ($action) {
			
			case "add":
			case "edit": $page = "custom_style";
			break;
			
			default: $page = "accepted_style";
			break;
			}
		break;
		
		case "special_best": 
		case "special_best_data": $page = "custom_winner";
		break;
		
		case "judging":
		
			switch($filter) {
				case "judges": 
				case "stewards":
				case "staff":
				$page = "assigning";
				break;
				
				default: $page = "judging_locations";
				break;
				
				
			}
		
		
		break;
		
		case "contacts": $page = "comp_contacts";
		break;
		
		case "dropoff": $page = "drop_off";
		break;
		
		case "sponsors": $page = "sponsors";
		break;
		
		case "contest_info": $page = "competition_info";
		break;
		
		case "entrant":
		case "judge": $page = "participants";
		break;
		
		case "participants": 
			switch ($filter) {
				case "judges": 
				case "assignJudges": $page = "judges";
				break;
				
				case "stewards":
				case "assignStewards": $page = "stewards";
				break;
				
				default: $page = "participants";
				break;
			}
		
		break;
		
		
		case "entries": $page = "entries";
		break;
		
		case "assign": $page = "assigning";
		break;
		
		case "judging_tables": 
			switch ($action) {
				case "assign": $page = "assigning";
				break; 
				
				default: $page = "tables";
				break;
			}
		
		break;
		
		case "judging_flights": 
			switch ($action) {
				
				case "rounds": $page = "rounds";
				break;
				
				case "default": $page = "flights";
				break;
				
			}
			
			switch ($filter) {
				case "rounds": $page = "rounds";
				break;
				
				case "define": $page = "flights";
				break;
			}
		
		break;
		
		case "judging_scores": $page = "scores";
		break;
		
		case "judging_scores_bos": $page = "best_of_show";
		break;
		
		case "special_best_data": $page = "introduction";
		break;
		
		case "archive": $page = "archiving";
		break;
		
		case "mods": $page = "mods";
		break;
		
		default: $page = "introduction";
		break;
	}
	
	$return = '<p><span class="icon"><img src="'.$base_url.'/images/help.png" /></span><a id="modal_window_link" href="http://help.brewcompetition.com/files/'.$page.'.html" title="BCOE&amp;M Help for '.$header_output.'">Help</a></p>';
	return $return;	
}

function custom_modules($type,$method) {
	require(CONFIG.'config.php');
	
	if ($type == "reports") { $type = 1; $modal = "id='modal_window_link'"; }
	if ($type == "exports") { $type = 2; $modal = ""; }
	
	if ($method == 1) {
		
		$query_custom_number = sprintf("SELECT COUNT(*) as 'count' FROM %s WHERE mod_type='%s'", $prefix."mods", $type);
		$custom_number = mysqli_query($connection,$query_custom_number) or die (mysqli_error($connection));
		$row_custom_number = mysqli_fetch_assoc($custom_number);
		
		if ($row_custom_number['count'] > 0) return TRUE;	
	}
	
	if ($method == 2) {
		
		$query_custom_mod = sprintf("SELECT * FROM %s WHERE mod_type='%s' ORDER BY mod_name ASC", $prefix."mods", $type);
		$custom_mod = mysqli_query($connection,$query_custom_mod) or die (mysqli_error($connection));
		$row_custom_mod = mysqli_fetch_assoc($custom_mod);
		$output = "";
		do {
			$output .= "<li><a ".$modal." href='".$base_url."mods/".$row_custom_mod['mod_filename']."'>".$row_custom_mod['mod_name']."</a></li>";
			//$output = $query_custom_mod;
		} while ($row_custom_mod = mysqli_fetch_assoc($custom_mod));
		
		return $output;
	}
}

function total_discount() {
	require(CONFIG.'config.php');
	
	$query_discount = sprintf("SELECT uid FROM %s WHERE brewerDiscount='Y'", $prefix."brewer");
	$discount = mysqli_query($connection,$query_discount) or die (mysqli_error($connection));
	$row_discount = mysqli_fetch_assoc($discount);
	$totalRows_discount = mysqli_num_rows($discount);
	
	do { $a[] = $row_discount['uid']; } while ($row_discount = mysqli_fetch_assoc($discount));
	
	foreach ($a as $brewer_id) {
	
		$query_discount_number = sprintf("SELECT COUNT(*) as 'count' FROM %s WHERE brewBrewerId='%s'", $prefix."brewing", $brewer_id);
		$discount_number = mysqli_query($connection,$query_discount_number) or die (mysqli_error($connection));
		$row_discount_number = mysqli_fetch_assoc($discount_number);
		$b[] = $row_discount_number['count']; 
		
	}
	
	$return = $totalRows_discount."^".array_sum($b);
	return $return;
}

function flight_entry_info($entry_id) {
	require(CONFIG.'config.php');
	mysqli_select_db($connection,$database);
	$query_flight_number = sprintf("SELECT id,flightNumber,flightEntryID,flightRound FROM %s WHERE flightEntryID='%s'",$prefix."judging_flights",$entry_id);
	$flight_number = mysqli_query($connection,$query_flight_number) or die (mysqli_error($connection));
	$row_flight_number = mysqli_fetch_assoc($flight_number);
	return $row_flight_number['id']."^".$row_flight_number['flightNumber']."^".$row_flight_number['flightEntryID']."^".$row_flight_number['flightRound'];
}

function flight_round_number($flight_table,$flight_number) {
	
	require(CONFIG.'config.php');
	mysqli_select_db($connection,$database);
	
	$query_round_no = sprintf("SELECT flightRound FROM %s WHERE flightTable='%s' AND flightNumber='%s' ORDER BY id DESC LIMIT 1", $prefix."judging_flights", $flight_table, $flight_number);
	$round_no = mysqli_query($connection,$query_round_no) or die (mysqli_error($connection));
	$row_round_no = mysqli_fetch_assoc($round_no);
	
	$return = $row_round_no['flightRound'];
	return $return;

}

// Define Custom Functions
function bos_judge_eligible($uid) {
	require(CONFIG.'config.php');
	mysqli_select_db($connection,$database);
	
	$query_eligible = sprintf("SELECT a.scorePlace,scoreTable FROM %s a, %s b WHERE a.scorePlace IS NOT NULL AND a.eid = b.id AND b.brewBrewerID = '%s' ORDER BY scoreTable ASC", $prefix."judging_scores", $prefix."brewing", $uid);
	$eligible = mysqli_query($connection,$query_eligible) or die (mysqli_error($connection));
	$row_eligible = mysqli_fetch_assoc($eligible);
	$totalRows_eligible = mysqli_num_rows($eligible);
	
	$return = "";
	unset($first_places);
	unset($second_places);
	unset($third_places);
	
	if ($totalRows_eligible > 0) {
		do {
			$places[] = $row_eligible['scorePlace']."-".$row_eligible['scoreTable'];
		} while ($row_eligible = mysqli_fetch_assoc($eligible));
	$places = implode("|",$places);
	$return .= $places;
	}
	
	return $return;
}

function judging_location_avail($loc_id,$judge_avail) {
	require(CONFIG.'config.php');
	mysqli_select_db($connection,$database);
	
	$query_judging_loc3 = sprintf("SELECT judgingLocName,judgingDate,judgingLocation FROM %s WHERE id='%s'", $prefix."judging_locations", $loc_id);
	$judging_loc3 = mysqli_query($connection,$query_judging_loc3) or die (mysqli_error($connection));
	$row_judging_loc3 = mysqli_fetch_assoc($judging_loc3);
	if ((substr($judge_avail, 0, 1) == "Y") && (!empty($row_judging_loc3['judgingLocName']))) $return = $row_judging_loc3['judgingLocName']."<br>";
	else $return = "";
	return $return;
}

function table_score_data($eid,$score_table,$suffix) {
		
	require(CONFIG.'config.php');
	mysqli_select_db($connection,$database);
	
	if ($suffix != "default") $suffix = "_".$suffix; else $suffix = "";
	
	$query_entries = sprintf("SELECT id, brewStyle,brewCategorySort,brewCategory,brewSubCategory,brewName,brewBrewerFirstName,brewBrewerLastName,brewJudgingNumber,brewBrewerID FROM %s WHERE id='%s'", $prefix."brewing".$suffix, $eid);
	$entries = mysqli_query($connection,$query_entries) or die (mysqli_error($connection));
	$row_entries = mysqli_fetch_assoc($entries);
	$style = $row_entries['brewCategorySort'].$row_entries['brewSubCategory'];
	
	if (strpos($_SESSION['prefsStyleSet'],"BABDB") === false) {
		$query_styles = sprintf("SELECT brewStyle FROM %s WHERE (brewStyleVersion='%s' OR brewStyleOwn='custom') AND brewStyleGroup='%s' AND brewStyleNum='%s'", $prefix."styles",$_SESSION['prefsStyleSet'],$row_entries['brewCategorySort'],$row_entries['brewSubCategory']);
		$styles = mysqli_query($connection,$query_styles) or die (mysqli_error($connection));
		$row_styles = mysqli_fetch_assoc($styles);
		$style_name = $row_styles['brewStyle'];
	}
	
	else {
		$style_name = $row_entries['brewStyle'];
	}
	
	$query_tables = sprintf("SELECT id,tableName,tableNumber FROM %s WHERE id='%s'", $prefix."judging_tables".$suffix, $score_table);
	$tables = mysqli_query($connection,$query_tables) or die (mysqli_error($connection));
	$row_tables = mysqli_fetch_assoc($tables);
	$totalRows_tables = mysqli_num_rows($tables);
	
	$query_brewer = sprintf("SELECT brewerLastName,brewerFirstName,brewerBreweryName FROM %s WHERE id='%s'", $prefix."brewer".$suffix, $row_entries['brewBrewerID']);
	$brewer = mysqli_query($connection,$query_brewer) or die (mysqli_error($connection));
	$row_brewer = mysqli_fetch_assoc($brewer);
	
	$return = 
	$row_entries['id']."^". //0
	$row_entries['brewStyle']."^". //1
	$row_entries['brewCategory']."^". //2
	$row_entries['brewName']."^". //3
	$row_brewer['brewerFirstName']."^". //4
	$row_brewer['brewerLastName']."^". //5
	$row_entries['brewJudgingNumber']."^". //6
	$row_entries['brewBrewerID']."^". //7
	$row_entries['brewCategorySort']."^". //8
	$row_tables['id']."^". //9
	$row_tables['tableName']."^". //10
	$row_tables['tableNumber']."^". //11
	$style."^". //12
	$style_name."^". //13
	$row_brewer['brewerBreweryName']; //14
	
	return $return;

}


function received_entries() {
	include (CONFIG.'config.php');
	//include (INCLUDES.'db_tables.inc.php');
	mysqli_select_db($connection,$database);
	$style_array = array();
	
	$query_styles = sprintf("SELECT brewStyle FROM %s WHERE (brewStyleVersion='%s' OR brewStyleOwn='custom')", $prefix."styles",$_SESSION['prefsStyleSet']);
	$styles = mysqli_query($connection,$query_styles) or die (mysqli_error($connection));
	$row_styles = mysqli_fetch_array($styles);
	
	do { $style_array[] = $row_styles['brewStyle']; } while ($row_styles = mysqli_fetch_array($styles));
	
	foreach ($style_array as $style) {
		
		$query_entry_count = sprintf("SELECT COUNT(*) as 'count' FROM %s WHERE brewStyle='%s' AND brewReceived='1'",$prefix."brewing",$style);
		$result = mysqli_query($connection,$query_entry_count) or die (mysqli_error($connection));
		$row = mysqli_fetch_array($result);
		if ($row['count'] > 0) $a[] = $style;
	}
	if (!empty($b))	$b = implode(",",$a);
	else $b="";
	return $b;
}


function assigned_judges($tid,$dbTable,$judging_assignments_db_table){
	include (CONFIG.'config.php');
	//include (INCLUDES.'db_tables.inc.php');
	mysqli_select_db($connection,$database);
	$query_assignments = sprintf("SELECT COUNT(*) as 'count' FROM %s WHERE assignTable='%s' AND assignment='J'", $judging_assignments_db_table, $tid);
	$assignments = mysqli_query($connection,$query_assignments) or die (mysqli_error($connection));
	$row_assignments = mysqli_fetch_assoc($assignments);
	if ($row_assignments['count'] == 0) {
		$icon = "fa-plus-circle";
		$title = "Add judges to this table.";
	}
	else {
		$icon = "fa-edit";
		$title = "Edit judges assigned to this table.";
	}
	if ($dbTable == "default") $r = $row_assignments['count'].' <a href="'.$base_url.'index.php?section=admin&action=assign&go=judging_tables&filter=judges&id='.$tid.'" data-toggle="tooltip" data-placement="top" title="'.$title.'"><span class="fa fa-lg '.$icon.'"></span></a>';
	else $r = $row_assignments['count'];
	return $r;
}

function assigned_stewards($tid,$dbTable,$judging_assignments_db_table){
	include (CONFIG.'config.php');	
	mysqli_select_db($connection,$database);
	$query_assignments = sprintf("SELECT COUNT(*) as 'count' FROM %s WHERE assignTable='%s' AND assignment='S'", $judging_assignments_db_table, $tid);
	$assignments = mysqli_query($connection,$query_assignments) or die (mysqli_error($connection));
	$row_assignments = mysqli_fetch_assoc($assignments);
	if ($row_assignments['count'] == 0) {
		$icon = "fa-plus-circle";
		$title = "Add stewards to this table.";
	}
	else {
		$icon = "fa-edit";
		$title = "Edit stewards assigned to this table.";
	}
	if ($dbTable == "default") $r = $row_assignments['count'].' <a href="'.$base_url.'index.php?section=admin&action=assign&go=judging_tables&filter=stewards&id='.$tid.'" data-toggle="tooltip" data-placement="top" title="'.$title.'"><span class="fa fa-lg '.$icon.'"></span></a>';
	else $r = $row_assignments['count'];
	return $r;
}

function date_created($uid,$date_format,$time_format,$timezone,$dbTable) {
	include (CONFIG.'config.php');	
	mysqli_select_db($connection,$database);
	if ($dbTable != "default") $dbTable = $dbTable; else $dbTable = $prefix."users";
	$query1 = sprintf("SHOW COLUMNS FROM %s LIKE 'userCreated'",$dbTable);
	$result = mysqli_query($connection,$query1) or die (mysqli_error($connection));
	$exists = (mysqli_num_rows($result))?TRUE:FALSE;
	
	if ($exists) {
	
		$query_user = sprintf("SELECT userCreated FROM %s WHERE id = '%s'",$dbTable,$uid);
		$user = mysqli_query($connection,$query_user) or die (mysqli_error($connection));
		$row_user = mysqli_fetch_assoc($user);
		$totalRows_user = mysqli_num_rows($user);
		
		if (($totalRows_user == 1) && ($row_user['userCreated'] != "")) {
			$result = "<span class=\"hidden\">".strtotime($row_user['userCreated'])."</span>".getTimeZoneDateTime($timezone, strtotime($row_user['userCreated']), $date_format,  $time_format, "short", "date-time-no-gmt");
		}
		
		else $result = "&nbsp;";
	}
	else $result = "&nbsp;";
	return $result;
}

function user_info($uid) {
	include (CONFIG.'config.php');	
	mysqli_select_db($connection,$database);
	$query_user1 = sprintf("SELECT id,userLevel FROM %s WHERE id = '%s'", $prefix."users", $uid);
	$user1 = mysqli_query($connection,$query_user1) or die (mysqli_error($connection));
	$row_user1 = mysqli_fetch_assoc($user1);
	
	$return = $row_user1['id']."^".$row_user1['userLevel'];
	return $return;
	
}

function sbd_count($id) {
	include (CONFIG.'config.php');	
	mysqli_select_db($connection,$database);
	$query_sbd = sprintf("SELECT COUNT(*) as 'count' FROM %s WHERE sid='%s'",$prefix."special_best_data",$id);
	$sbd = mysqli_query($connection,$query_sbd) or die (mysqli_error($connection));
	$row_sbd = mysqli_fetch_assoc($sbd);	
	return $row_sbd['count'];
}

function special_best_info($sid) {
	include (CONFIG.'config.php');	
	mysqli_select_db($connection,$database);
	$query_sbi = sprintf("SELECT id,sbi_name FROM %s WHERE id='%s'",$prefix."special_best_info",$sid); 
	$sbi = mysqli_query($connection,$query_sbi) or die (mysqli_error($connection));
	$row_sbi = mysqli_fetch_assoc($sbi);
	$totalRows_sbi = mysqli_num_rows($sbi);
	return $row_sbi['id']."^".$row_sbi['sbi_name'];
}

// --------------- Custom Functions --------------------- //
 
 function table_round($tid,$round) {
	require(CONFIG.'config.php');
	mysqli_select_db($connection,$database);
	// get the round where the flight is assigned to
	$query_flight_round = sprintf("SELECT COUNT(*) as 'count' FROM %s WHERE flightTable='%s' AND flightRound='%s' LIMIT 1", $prefix."judging_flights", $tid, $round);
	$flight_round = mysqli_query($connection,$query_flight_round) or die (mysqli_error($connection));
	$row_flight_round = mysqli_fetch_assoc($flight_round);
	if ($row_flight_round['count'] > 0) return TRUE; else return FALSE;
}

function flight_round($tid,$flight,$round) {
	require(CONFIG.'config.php');
	mysqli_select_db($connection,$database);
	// get the round where the flight is assigned to
	$query_flight_round = sprintf("SELECT flightRound FROM %s WHERE flightTable='%s' AND flightNumber='%s' LIMIT 1", $prefix."judging_flights", $tid, $flight);
	$flight_round = mysqli_query($connection,$query_flight_round) or die (mysqli_error($connection));
	$row_flight_round = mysqli_fetch_assoc($flight_round);
	if ($row_flight_round['flightRound'] == $round) return TRUE; else return FALSE;
}

function already_assigned($bid,$tid,$flight,$round) {
	require(CONFIG.'config.php');
	mysqli_select_db($connection,$database);
	$query_assignments = sprintf("SELECT COUNT(*) as 'count' FROM %s WHERE (bid='%s' AND assignTable='%s' AND assignFlight='%s' AND assignRound='%s')", $prefix."judging_assignments", $bid, $tid, $flight, $round);
	$assignments = mysqli_query($connection,$query_assignments) or die (mysqli_error($connection));
	$row_assignments = mysqli_fetch_assoc($assignments);
	if ($row_assignments['count'] == 1) return TRUE; 
	else return FALSE;
	}

function at_table($bid,$tid) {
	require(CONFIG.'config.php');
	mysqli_select_db($connection,$database);
	$query_assignments = sprintf("SELECT assignTable FROM %s WHERE bid='%s'", $prefix."judging_assignments", $bid);
	$assignments = mysqli_query($connection,$query_assignments) or die (mysqli_error($connection));
	$row_assignments = mysqli_fetch_assoc($assignments);
	$a = array();
	do {
		$a[] .= $row_assignments['assignTable']; 
	} while ($row_assignments = mysqli_fetch_assoc($assignments));
	if (in_array($tid,$a)) return TRUE;
	else return FALSE;
	//return implode(",",$a);
}

function unavailable($bid,$location,$round,$tid) { 
	// returns true a person is unavailable (if they are already assigned to a table/flight in the same round at the same location)
	require(CONFIG.'config.php');
	mysqli_select_db($connection,$database);
	$query_assignments = sprintf("SELECT COUNT(*) AS 'count' FROM %s WHERE bid='%s' AND assignRound='%s' AND assignLocation='%s'", $prefix."judging_assignments", $bid, $round, $location);
	$assignments = mysqli_query($connection,$query_assignments) or die (mysqli_error($connection));
	$row_assignments = mysqli_fetch_assoc($assignments);
	
	if ($row_assignments['count'] > 0) return TRUE;
	else return FALSE;
}

function like_dislike($likes,$dislikes,$styles) { 
	// if a judge in the returned list listed one or more of the substyles
	// included in the table in their "likes" or "dislikes"
	require(CONFIG.'config.php');
	mysqli_select_db($connection,$database);
	
	// get the table's associated styles from the "tables" table
	$s = explode(",",$styles);
	$r = "";	
	// check for likes
	if ($likes != "") {
		$a = explode(",",$likes);
			foreach ($a as $value) {
				if (in_array($value,$s)) $b[] = 1; else $b[] = 0;
			}
	$c = array_sum($b);
	} 
	else $c = 0;
	
	// check for dislikes
	if ($dislikes != "") {
		$d = explode(",",$dislikes);
			foreach ($d as $value) {
			   if (in_array($value,$s)) $e[] = 1; else $e[] = 0;
			}
	$f = array_sum($e);
	} 
	
	else $f = 0;
	
	if (($c > 0) && ($f == 0)) $r .= "bg-success text-success|<span class=\"text-success\"><span class=\"fa fa-thumbs-o-up\"></span> <strong>Available and Preferred Style(s).</strong> Paricipant is available for this round. One or more styles at the table are on the participant&rsquo;s &ldquo;likes&rdquo; list.<span>"; // 1 or more likes matched, color table cell green
	elseif (($c == 0) && ($f > 0)) $r .= "bg-danger text-danger|<span class=\"text-danger\"><span class=\"fa fa-thumbs-o-down\"></span> <strong>Available but Non-Preferred Style(s).</strong> Paricipant is available for this round. One or more styles are on the participant&rsquo;s &ldquo;dislikes&rdquo; list.</span>"; // 1 or more dislikes matched, color table cell red
	else $r .="bg-grey text-grey|<span class=\"text-orange\"><span class=\"fa fa-star-o\"></span> <strong>Available.</strong> Paricipant is available for this round.</span>";
	
	return $r;
}

function entry_conflict($bid,$table_styles) {
	require(CONFIG.'config.php');
	mysqli_select_db($connection,$database);
	
	$b = explode(",",$table_styles);
	
	foreach ($b as $style) {
		
		$query_style = sprintf("SELECT brewStyleGroup,brewStyleNum FROM %s WHERE id='%s'", $prefix."styles", $style);
		$style = mysqli_query($connection,$query_style) or die (mysqli_error($connection));
		$row_style = mysqli_fetch_assoc($style);
		
		$query_entries = sprintf("SELECT COUNT(*) as 'count' FROM %s WHERE brewBrewerID='%s' AND brewCategorySort='%s' AND brewSubCategory='%s' AND brewReceived='1'", $prefix."brewing", $bid, $row_style['brewStyleGroup'],$row_style['brewStyleNum']);
		$entries = mysqli_query($connection,$query_entries) or die (mysqli_error($connection));
		$row_entries = mysqli_fetch_assoc($entries);
		
		if ($row_entries['count'] > 0) $c[] = 1; else $c[] = 0;			
	}
	$d = array_sum($c);
	if ($d > 0) return TRUE;
}

function unassign($bid,$location,$round,$tid) {
	//if (unavailable($bid,$location,$round,$tid)) {
		require(CONFIG.'config.php');
		mysqli_select_db($connection,$database);
		$query_assignments = sprintf("SELECT id FROM %s WHERE bid='%s' AND assignRound='%s' AND assignLocation='%s'", $prefix."judging_assignments", $bid, $round, $location);
		$assignments = mysqli_query($connection,$query_assignments) or die (mysqli_error($connection));
		$row_assignments = mysqli_fetch_assoc($assignments);	
		$r = $row_assignments['id'];
	//}
	if ($r > 0) $r = $r;
	else $r = "0";
	//$r = $query_assignments;
	return $r;
}

function assign_to_table($tid,$bid,$filter,$total_flights,$round,$location,$table_styles,$queued,$random) {
// Function almalgamates the above functions to output the correct form elements
// $bid = id of row in the brewer's table
// $tid = id of row in the judging_tables table
// $filter = judges or stewards from encoded URL
// $flight = flight number (query above)
// $round = the round number from the for loop
// $location = id of table's location from the judging_locations table

// Define variables
$unassign = unassign($bid,$location,$round,$tid);
$unavailable = unavailable($bid,$location,$round,$tid);

$r = "";
if (entry_conflict($bid,$table_styles)) $disabled = "disabled"; else $disabled = "";
if ($filter == "stewards") $role = "S"; else $role = "J";

// Build the form elements
$r .= '<input type="hidden" name="random[]" value="'.$random.'" />';
$r .= '<input type="hidden" name="bid'.$random.'" value="'.$bid.'" />';
$r .= '<input type="hidden" name="assignRound'.$random.'" value="'.$round.'" />';
$r .= '<input type="hidden" name="assignment'.$random.'" value="'.$role.'" />';
$r .= '<input type="hidden" name="assignLocation'.$random.'" value="'.$location.'" />';
$r .= '<input type="hidden" name="id'.$random.'" value="'.$unassign.'"/>';

if ($queued == "Y") { 
	if (already_assigned($bid,$tid,"1",$round)) {
		$selected = "checked"; 
		$default = ""; 
	}
	else { 
		$selected = ""; 
		$default = "checked";
	}
}

if ($unassign > 0) {
	// Check to see if the participant is already assigned to this round. 
	// If so (function returns a value greater than 0), display the following:
	$r .= '<div class="form-inline">';
	$r .= '<div class="checkbox">';
	$r .= '<label for="unassign'.$random.'">';
	$r .= '<input type="checkbox" id="unassign'.$random.'" name="unassign'.$random.'" value="'.$unassign.'"/>';
	$r .= ' Unassign and...</label>';
	$r .= '</div>';
	$r .= '</div>'; 
	}
	else {
		$r .= '<input type="hidden" name="unassign'.$random.'" value="'.$unassign.'"/>';	
	}

if ($queued == "Y") { // For queued judging only
	//if (already_assigned($bid,$tid,"1",$round)) { $selected = 'checked'; $default = ''; } else { $selected = ''; $default = 'checked'; }
	$r .= '<div class="form-inline">';
	$r .= '<div class="form-group">';
	$r .= '<div class="input-group">';
    $r .= '<label class="radio-inline">';
    $r .= '<input type="radio" name="assignRound'.$random.'" value="'.$round.'" '.$selected.' '.$disabled.' /> Assign to this Table';
    $r .= '</label>';
    $r .= '<label class="radio-inline">';
    $r .= '<input type="radio" name="assignRound'.$random.'" value="0" '.$default.' /> Do Not Assign to This Table';
    $r .= '</label>';
    $r .= '</div>';
	$r .= '</div>';
	}
	else $r .= '<input type="hidden" name="assignTable'.$random.'" value="'.$tid.'" />';

if ($queued == "N") { // Non-queued judging
	// Build the flights DropDown
	$r .= '<select class="selectpicker" name="assignFlight'.$random.'" '.$disabled.'>';
	$r .= '<option value="0" />Do Not Assign</option>';
		for($f=1; $f<$total_flights+1; $f++) {
			if (flight_round($tid,$f,$round)) { 
				if (already_assigned($bid,$tid,$f,$round)) { $output = 'Assigned'; $selected = 'selected'; $style = ' style="color: #990000;"'; } else { $output = 'Assign'; $selected = ''; $style=''; }
				$r .= '<option value="'.$f.'" '.$selected.$style.' />'.$output.' to Flight '.$f.'</option>';
			}
		} // end for loop
	$r .= '</select>';
	}
	
if ($queued == "Y") {
		$r .= '<input type="hidden" name="assignFlight'.$random.'" value="1">';
	}
return $r;
}

function get_judge_role($uid,$tid) {
	
}

function judge_alert($round,$bid,$tid,$location,$likes,$dislikes,$table_styles,$id) {
	if (table_round($tid,$round)) {
		$unavailable = unavailable($bid,$location,$round,$tid);
		$entry_conflict = entry_conflict($bid,$table_styles);
		$at_table = at_table($bid,$tid);
		if ($unavailable) $r = "bg-purple text-purple|<span class=\"text-purple\"><span class=\"fa fa-check\"></span> <strong>Assigned.</strong> Paricipant is assigned to another table in this round.</span>";
		if ($entry_conflict) $r = "bg-info text-info|<span class=\"text-info\"><span class=\"fa fa-ban\"></span> <strong>Disabled.</strong> Participant has an entry at this table.</span>";
		if ((!$unavailable) && (!$entry_conflict)) $r = like_dislike($likes,$dislikes,$table_styles);
	}
	else $r = '';
	return $r;
}

function judge_info($uid) {
	require(CONFIG.'config.php');
	mysqli_select_db($connection,$database);
	$query_brewer_info = sprintf("SELECT id,brewerFirstName,brewerLastName,brewerJudgeLikes,brewerJudgeDislikes,brewerJudgeMead,brewerJudgeRank,brewerJudgeID,brewerStewardLocation,brewerJudgeLocation,brewerJudgeExp,brewerJudgeNotes FROM %s WHERE uid='%s'", $prefix."brewer", $uid);
	$brewer_info = mysqli_query($connection,$query_brewer_info) or die (mysqli_error($connection));
	$row_brewer_info = mysqli_fetch_assoc($brewer_info);
	
	
	$r = $row_brewer_info['brewerFirstName']."^".$row_brewer_info['brewerLastName']."^".$row_brewer_info['brewerJudgeLikes']."^".$row_brewer_info['brewerJudgeDislikes']."^".$row_brewer_info['brewerJudgeMead']."^".$row_brewer_info['brewerJudgeRank']."^".$row_brewer_info['brewerJudgeID']."^".$row_brewer_info['brewerStewardLocation']."^".$row_brewer_info['brewerJudgeLocation']."^".$row_brewer_info['brewerJudgeExp']."^".$row_brewer_info['brewerJudgeNotes']."^".$row_brewer_info['id'];
	
	
	if ($_SESSION['jPrefsQueued'] == "N") {
		$query_judge_info = sprintf("SELECT assignFlight,assignRound FROM %s WHERE bid='%s'", $prefix."judging_assignments", $uid);
		$judge_info = mysqli_query($connection,$query_judge_info) or die (mysqli_error($connection));
		$row_judge_info = mysqli_fetch_assoc($judge_info);
		
		$r .= "^".$row_judge_info['assignFlight']."^".$row_judge_info['assignRound'];
	}
	
	
	return $r;
}

function flight_entry_count($table_id,$flight) {
	require(CONFIG.'config.php');
	mysqli_select_db($connection,$database);
	$query_entry_count = sprintf("SELECT COUNT(*) as 'count' FROM %s WHERE flightTable='%s' AND flightNumber='%s'", $prefix."judging_flights", $table_id, $flight);
	$entry_count = mysqli_query($connection,$query_entry_count) or die (mysqli_error($connection));
	$row_entry_count = mysqli_fetch_assoc($entry_count);
	return $row_entry_count['count'];
}

function not_assigned($method) {
	require(CONFIG.'config.php');
	mysqli_select_db($connection,$database);
	
	$return = "";
	$assignment = "";
	
	if ($method == "J") { 
		$query_brewer = sprintf("SELECT a.uid, b.uid FROM %s a, %s b WHERE b.staff_judge='1' AND a.uid=b.uid",$prefix."brewer",$prefix."staff");
		$human_readable = "judge";
	}
	if ($method == "S") {
		$query_brewer = sprintf("SELECT a.uid, b.uid FROM %s a, %s b WHERE b.staff_steward='1' AND a.uid=b.uid",$prefix."brewer",$prefix."staff");
		$human_readable = "steward";
	}
	$brewer = mysqli_query($connection,$query_brewer) or die (mysqli_error($connection));
	$row_brewer = mysqli_fetch_assoc($brewer);
	$totalRows_brewer = mysqli_num_rows($brewer);
	
	if ($totalRows_brewer > 0) {
		
		$user[] = "";
		
		do { $user[] .= $row_brewer['uid'];  } while ($row_brewer = mysqli_fetch_assoc($brewer));
	
		foreach($user as $bid) {
			
			if ($method == "J") {
				$query_assignments = sprintf("SELECT COUNT(*) as 'count' FROM %s WHERE bid='%s' AND assignment='J'", $prefix."judging_assignments", $bid);
				$assignments = mysqli_query($connection,$query_assignments) or die (mysqli_error($connection));
				$row_assignments = mysqli_fetch_assoc($assignments);
				
				// If no assignment, get info and build output
				if ($row_assignments['count'] == 0) {
					$info = judge_info($bid);
					$assignment_info = explode("^",$info);
					$judge_rank = $assignment_info[5];
					$judge_rank_explode = explode(",",$assignment_info[5]);
					$judge_rank_display = $judge_rank_explode[0];
					if (empty($judge_rank)) $judge_rank_display = "Non-BJCP";
					if (!empty($assignment_info[1])) $assignment .= "<tr><td class=\"small\">".$assignment_info[1].", ".$assignment_info[0]."</td><td class=\"small\">".$judge_rank_display."</td></tr>";
				}
				
			}
			
			if ($method == "S") {
				$query_assignments = sprintf("SELECT COUNT(*) as 'count' FROM %s WHERE bid='%s' AND assignment='S'", $prefix."judging_assignments", $bid);
				$assignments = mysqli_query($connection,$query_assignments) or die (mysqli_error($connection));
				$row_assignments = mysqli_fetch_assoc($assignments);
				
				// If no assignment, get info and build output
				if ($row_assignments['count'] == 0) {
					$info = judge_info($bid);
					$assignment_info = explode("^",$info);
					$judge_rank = $assignment_info[5];
					$judge_rank_explode = explode(",",$assignment_info[5]);
					$judge_rank_display = $judge_rank_explode[0];
					if (empty($judge_rank)) $judge_rank_display = "Non-BJCP";
					if (!empty($assignment_info[1])) $assignment .= "<tr><td class=\"small\">".$assignment_info[1].", ".$assignment_info[0]."</td><td class=\"small\">".$judge_rank_display."</td></tr>";
				}
			}
	
		}
		
		// Return the modal body text
		
		if (!empty($assignment)) {
			$return .= "<p>These ".$human_readable."s have not been assigned to any table.</p>";
			$return .= "<table class=\"table table-responsive table-striped table-bordered table-condensed\" id=\"sortable".$method."\">";
			$return .= "<thead>";
			$return .= "<tr>";
			$return .= "<th>Name</th>";
			$return .= "<th>Rank</th>";
			$return .= "</tr>";
			$return .= "</thead>";
			$return .= "<tbody>";
			$return .= $assignment;
			$return .= "</tbody>";
			$return .= "</table>";
		}
		
		else $return .= "<p>All available ".$human_readable."s have been assigned to tables.</p>";
	
	}
	
	// Return modal body text if no assignments
	else $return = "<p>No participants have been added to the ".$human_readable." pool yet. Therefore, there are no table assignments.</p>"; 
	
	return $return;
	
}
?>