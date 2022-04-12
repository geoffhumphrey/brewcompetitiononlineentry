<?php

function directory_contents_dropdown($directory,$file_name_selected,$method="1") {

	$handle = opendir($directory);
	$filelist = array();

	while ($file = readdir($handle)) {

	   if ((!is_dir($file)) && (!is_link($file))) {
			$filelist[] = $file;
	   }

	}

	sort($filelist, SORT_NATURAL | SORT_FLAG_CASE);

	// Return dropdown options
	// For one-time use
	if ($method == "1") {
		$return = "";
		foreach ($filelist as $filename) {
			$selected = "";
			if ($file_name_selected == $filename) $selected = " selected";
			$return .= "<option value=\"".$filename."\"".$selected.">";
			$return .= $filename;
			$return .= "</option>";
		}
	}

	// Return an array of file names
	if ($method == "2") {
		$return = array();
		foreach ($filelist as $filename) {
			$return[] = $filename;
		}
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
		case "2": $bos_method = "1st and 2nd places";
		break;
		case "3": $bos_method = "1st, 2nd, and 3rd places";
		break;
		case "4": $bos_method = "1st, 2nd, and 3rd places with HM";
		break;
		default: $bos_method = "Defined by Admin";
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

	$query_brewer = sprintf("SELECT brewerLastName,brewerFirstName,brewerBreweryName FROM %s WHERE uid='%s'", $brewer_db_table, $row_entries_1['brewBrewerID']);
	$brewer = mysqli_query($connection,$query_brewer) or die (mysqli_error($connection));
	$row_brewer = mysqli_fetch_assoc($brewer);

	$return = "";
	$return .= $row_entries_1['brewStyle']."^";  			// 0
	$return .= $row_entries_1['brewCategorySort']."^";  	// 1
	$return .= $row_entries_1['brewCategory']."^";  		// 2
	$return .= $row_entries_1['brewSubCategory']."^";  		// 3
	$return .= $row_brewer['brewerFirstName']."^";  	// 4
	$return .= $row_brewer['brewerLastName']."^";  	// 5
	$return .= $row_entries_1['brewJudgingNumber']."^";   	// 6
	$return .= $row_tables_1['id']."^";  					// 7
	$return .= $row_tables_1['tableName']."^";   			// 8
	$return .= $row_tables_1['tableNumber']."^";  			// 9
	if (isset($row_bos_place_1['scorePlace'])) $return .= $row_bos_place_1['scorePlace']."^";  		// 10
	else $return .= " ^";
	if (isset($row_bos_place_1['scoreEntry'])) $return .= $row_bos_place_1['scoreEntry']."^";  		// 11
	else $return .= " ^";
	$return .= $row_entries_1['brewName']."^";  			// 12
	$return .= $row_entries_1['id']."^";   					// 13
	if (isset($row_bos_place_1['id'])) $return .= $row_bos_place_1['id']."^";   				// 14
	else $return .= "N^";
	$return .= $row_entries_1['brewBrewerID']."^"; 				// 15
	$return .= $row_brewer['brewerBreweryName']; //16

	return $return;
}

function style_type_info($type,$suffix="default") {
	require(CONFIG.'config.php');
	mysqli_select_db($connection,$database);

	if ($suffix == "default") $dbTable = $prefix."style_types";
	else $dbTable = $prefix."style_types_".$suffix;

	$query_style_type = sprintf("SELECT * FROM %s WHERE id='%s'",$dbTable,$type);
	$style_type = mysqli_query($connection,$query_style_type) or die (mysqli_error($connection));
	$row_style_type = mysqli_fetch_assoc($style_type);

	$return = $row_style_type['styleTypeBOS']."^".$row_style_type['styleTypeBOSMethod']."^".$row_style_type['styleTypeName'];
	return $return;
}


function score_style_data($value) {

	require(CONFIG.'config.php');
	mysqli_select_db($connection,$database);

	//if ($_SESSION['prefsStyleSet'] != "BA") {

	$query_styles = sprintf("SELECT brewStyleGroup,brewStyleNum,brewStyle,brewStyleType FROM %s WHERE id='%s'", $prefix."styles", $value);
	$styles = mysqli_query($connection,$query_styles) or die (mysqli_error($connection));
	$row_styles = mysqli_fetch_assoc($styles);

	$return =
	$row_styles['brewStyleGroup']."^". //0
	$row_styles['brewStyleNum']."^". //1
	$row_styles['brewStyle']."^". //2
	$row_styles['brewStyleType']; //3
/*
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

	*/

	return $return;

}

function score_entry_data($value) {

	require(CONFIG.'config.php');
	mysqli_select_db($connection,$database);

	$query_scores = sprintf("SELECT id,eid,bid,scoreEntry,scorePlace,scoreMiniBOS FROM %s WHERE eid='%s'", $prefix."judging_scores", $value);
	$scores = mysqli_query($connection,$query_scores) or die (mysqli_error($connection));
	$row_scores = mysqli_fetch_assoc($scores);

	$return = "";

	if (!empty($row_scores)) {
		$return =
		$row_scores['id']."^". //0
		$row_scores['eid']."^". //1
		$row_scores['bid']."^". //2
		$row_scores['scoreEntry']."^". //3
		$row_scores['scorePlace']."^". //4
		$row_scores['scoreMiniBOS']; //5
	}

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
		if ($totalRows_flights > 0) $table_choose = $totalRows_flights."^".$row_flights['flightTable'];
	}

	else {
		if ($method == "thickbox") $class = 'class="hide-loader menuItem" id="modal_window_link"';
		if ($method == "none") $class = 'class="menuItem"';

		$random = random_generator(7,2);

		$query_tables = sprintf("SELECT * FROM %s ORDER BY tableNumber ASC", $prefix."judging_tables");
		$tables = mysqli_query($connection,$query_tables) or die (mysqli_error($connection));
		$row_tables = mysqli_fetch_assoc($tables);
		$totalRows_tables = mysqli_num_rows($tables);

		do {
			if ($filter == "mini_bos") $table_choose .= '<li class="small"><a id="modal_window_link" class="hide-loader" href="'.$script_name.'?section='.$section.'&go='.$go.'&action='.$action.'&filter='.$filter.'&view='.$view.'&id='.$row_tables['id'].'" title="Print '.$row_tables['tableName'].'">'.$row_tables['tableNumber'].': '.$row_tables['tableName'].' (Mini-BOS)</a></li>';
			else $table_choose .= '<li class="small"><a id="modal_window_link" class="hide-loader" href="'.$script_name.'?section='.$section.'&go='.$go.'&action='.$action.'&filter='.$filter.'&view='.$view.'&id='.$row_tables['id'].'" title="Print '.$row_tables['tableName'].'">'.$row_tables['tableNumber'].': '.$row_tables['tableName'].' </a></li>';
		} while ($row_tables = mysqli_fetch_assoc($tables));

	}

	return $table_choose;

}

function style_choose($section,$go,$action,$filter,$view,$script_name,$method) {

	require(CONFIG.'config.php');
	mysqli_select_db($connection,$database);

	$end = $_SESSION['style_set_category_end'];

	if ($method == "thickbox") { 
		$suffix = '';
		$class = 'class="hide-loader menuItem" id="modal_window_link"'; 
	}

	if ($method == "none") { 
		$suffix = '';
		$class = 'class="menuItem"'; 
	}

	$random = random_generator(7,2);

	$style_choose = '<div class="menuBar"><a class="menuButton" href="#" onclick="#" onmouseover="buttonMouseover(event, \'menu_categories'.$random.'\');">Select Below...</a></div>';
	$style_choose .= '<div id="menu_categories'.$random.'" class="menu" onmouseover="menuMouseover(event)">';
	
	for($i=1; $i<29; $i++) {
		
		if ($i <= 9) $num = "0".$i; else $num = $i;
		
		$query_entry_count = sprintf("SELECT COUNT(*) as 'count' FROM %s WHERE brewCategory='%s'", $prefix."brewing", $i);
		$result = mysqli_query($connection,$query_entry_count) or die (mysqli_error($connection));
		$row = mysqli_fetch_array($result);
		
		if ($row['count'] > 0) { 
			$style_choose .= '<a '.$class.' style="font-size: 0.9em; padding: 1px;" href="'.$script_name.'?section='.$section.'&go='.$go.'&action='.$action.'&filter='.$num.$suffix.'&view='.$view.'" title="Print '.style_convert($i,"1").'">'.$num.' '.style_convert($i,"1").' ('.$row['count'].' entries)</a>'; 
		}

	}

	$query_styles = sprintf("SELECT brewStyle,brewStyleGroup FROM %s WHERE brewStyleGroup > %s", $prefix."styles",$end);
	$styles = mysqli_query($connection,$query_styles) or die (mysqli_error($connection));
	$row_styles = mysqli_fetch_assoc($styles);
	$totalRows_styles = mysqli_num_rows($styles);

	do {
		
		$query_entry_count = sprintf("SELECT COUNT(*) as 'count' FROM %s WHERE brewCategorySort='%s'", $prefix."brewing", $row_styles['brewStyleGroup']);
		$result = mysqli_query($connection,$query_entry_count) or die (mysqli_error($connection));
		$row = mysqli_fetch_array($result);
		
		if ($row['count'] > 0) { 
			$style_choose .= '<a '.$class.' style="font-size: 0.9em; padding: 1px;" href="'.$script_name.'?section='.$section.'&go='.$go.'&action='.$action.'&filter='.$row_styles['brewStyleGroup'].$suffix.'" title="Print '.$row_styles['brewStyle'].'">'.$row_styles['brewStyleGroup'].' '.$row_styles['brewStyle'].' ('.$row['count'].' entries)</a>'; 
		}

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
		
		case "1": if ($row_flights['count'] > 0) return true; 
		else return false;
		break;

		case "2": return $row_flights['count'];
		break;

	}

}

function orphan_styles() {
	
	require(CONFIG.'config.php');

	$end = $_SESSION['style_set_category_end'];

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

	$r = "";

	if ($totalRows_tables > 0) {
		
		do {

			$query_scores = sprintf("SELECT COUNT(*) as 'count' FROM $judging_scores_db_table WHERE scoreTable='%s'", $row_tables['id']);
			$scores = mysqli_query($connection,$query_scores) or die (mysqli_error($connection));
			$row_scores = mysqli_fetch_assoc($scores);
			
			if ($row_scores['count'] > 0) $a = "edit"; else $a = "add";
        	$r .= "<li class=\"small\"><a href=\"index.php?section=admin&amp;&go=judging_scores&amp;action=".$a."&amp;id=".$row_tables['id']."\">Table ".$row_tables['tableNumber'].": ".$row_tables['tableName']."</a></li>";

		} while ($row_tables = mysqli_fetch_assoc($tables));

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

		do {
			
			$query_scores = sprintf("SELECT COUNT(*) as 'count' FROM $special_best_data_db_table WHERE sid='%s'", $row_sbi['id']);
			$scores = mysqli_query($connection,$query_scores) or die (mysqli_error($connection));
			$row_scores = mysqli_fetch_assoc($scores);
			
			if ($row_scores['count'] > 0) $a = "edit"; 
			else $a = "add";
        	
        	$r .= "<li class=\"small\"><a href=\"index.php?section=admin&amp;&go=special_best_data&amp;action=".$a."&amp;id=".$row_sbi['id']."\">".$row_sbi['sbi_name']."</a></li>";

		} while ($row_sbi = mysqli_fetch_assoc($sbi));

	}
	
	else {
		
		$r = "<li class=\"disabled small\"><a href=\"#\">No custom categories have been defined</a></li>";
		$r .= "<li role=\"separator\" class=\"divider\"></li>";
		$r .= "<li class=\"small\"><a href=\"".$base_url."index.php?section=admin&amp;go=special_best&amp;action=add\">Add a Custom Style</a></li>";

	}

	return $r;
}

function participant_choose($brewer_db_table,$pro_edition,$judge) {
	
	require(CONFIG.'config.php');
	mysqli_select_db($connection,$database);

	if ($pro_edition == 1) $query_brewers = "SELECT uid,brewerBreweryName FROM $brewer_db_table WHERE brewerBreweryName IS NOT NULL ORDER BY brewerBreweryName ASC";
	
	else {
		
		if ($judge == 1) $query_brewers = "SELECT uid,brewerFirstName,brewerLastName FROM $brewer_db_table WHERE brewerJudge='Y' ORDER BY brewerLastName ASC";
		else $query_brewers = "SELECT uid,brewerFirstName,brewerLastName FROM $brewer_db_table ORDER BY brewerLastName ASC";
	
	}

	$brewers = mysqli_query($connection,$query_brewers) or die (mysqli_error($connection));
	$row_brewers = mysqli_fetch_assoc($brewers);

	$output = "";
	$output .= "<select class=\"selectpicker\" name=\"participants\" id=\"participants\"";
	if ($judge == 0) $output .= " onchange=\"jumpMenu('self',this,0)\"";
	if ($judge == 1) $output .= " required";
	$output .= " data-size=\"15\" data-width=\"auto\" data-live-search=\"true\">";
	
	if ($judge == 0) $output .= "<option value=\"\" selected disabled data-icon=\"fa fa-plus-circle\">Add an Entry For...</option>";
	else $output .= "<option value=\"\"></option>";
	
	if ($row_brewers) {
		
		do {

			if ($judge == 1) {
				$output .= "<option value=\"".$row_brewers['uid']."\">".$row_brewers['brewerLastName'].", ".$row_brewers['brewerFirstName']."</option>";
			}

			else {
				if ($pro_edition == 1) $output .= "<option value=\"index.php?section=brew&amp;go=entries&amp;bid=".$row_brewers['uid']."&amp;action=add\" data-content=\"<span class='small'>".$row_brewers['brewerBreweryName']."</span>\">".$row_brewers['brewerBreweryName']."</option>";
				else $output .= "<option value=\"index.php?section=brew&amp;go=entries&amp;bid=".$row_brewers['uid']."&amp;action=add\" data-content=\"<span class='small'>".$row_brewers['brewerLastName'].", ".$row_brewers['brewerFirstName']."</span>\">".$row_brewers['brewerLastName'].", ".$row_brewers['brewerFirstName']."</option>";
			}

		} while ($row_brewers = mysqli_fetch_assoc($brewers));
		
	}

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

	$return = '<p><span class="icon"><img src="'.$base_url.'/images/help.png" /></span><a id="modal_window_link" class="hide-loader" href="http://help.brewcompetition.com/files/'.$page.'.html" title="BCOE&amp;M Help for '.$header_output.'">Help</a></p>';
	return $return;
}

function custom_modules($type,$method) {
	require(CONFIG.'config.php');

	if ($type == "reports") { $type = 1; $modal = "id='modal_window_link' class='hide-loader'"; }
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

	// $received = get_table_info("1","count_total",$flight_table,"default","default");

	$query_round_no = sprintf("SELECT flightRound FROM %s WHERE flightTable='%s' AND flightNumber='%s'", $prefix."judging_flights", $flight_table, $flight_number);
	$round_no = mysqli_query($connection,$query_round_no) or die (mysqli_error($connection));
	$row_round_no = mysqli_fetch_assoc($round_no);
	$totalRows_round_no = mysqli_num_rows($round_no);

	$all_recorded = array();

	do {

		if (!empty($row_round_no['flightRound'])) $all_recorded[] = 1;
		else $all_recorded[] = 0;

	} while ($row_round_no = mysqli_fetch_assoc($round_no));

	$all_recorded_sum = array_sum($all_recorded);

	if ($totalRows_round_no == $all_recorded_sum) {
		$query_round_no = sprintf("SELECT flightRound FROM %s WHERE flightTable='%s' AND flightNumber='%s' ORDER BY id DESC LIMIT 1", $prefix."judging_flights", $flight_table, $flight_number);
		$round_no = mysqli_query($connection,$query_round_no) or die (mysqli_error($connection));
		$row_round_no = mysqli_fetch_assoc($round_no);
		$return = $row_round_no['flightRound'];
	}

	else $return = "";
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

	$style_name = $row_entries['brewStyle'];

	$query_tables = sprintf("SELECT id,tableName,tableNumber FROM %s WHERE id='%s'", $prefix."judging_tables".$suffix, $score_table);
	$tables = mysqli_query($connection,$query_tables) or die (mysqli_error($connection));
	$row_tables = mysqli_fetch_assoc($tables);
	$totalRows_tables = mysqli_num_rows($tables);

	$query_brewer = sprintf("SELECT brewerLastName,brewerFirstName,brewerBreweryName FROM %s WHERE uid='%s'", $prefix."brewer".$suffix, $row_entries['brewBrewerID']);
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
	$row_brewer['brewerBreweryName']."^". //14
	$row_entries['brewSubCategory']; //15

	return $return;

}


function received_entries() {
	
	include (CONFIG.'config.php');
	mysqli_select_db($connection,$database);
	
	$style_array = array();

	$query_styles = sprintf("SELECT brewStyle FROM %s WHERE (brewStyleVersion='%s' OR brewStyleOwn='custom')", $prefix."styles",$_SESSION['prefsStyleSet']);
	$styles = mysqli_query($connection,$query_styles) or die (mysqli_error($connection));
	$row_styles = mysqli_fetch_array($styles);

	do { $style_array[] = $row_styles['brewStyle']; } while ($row_styles = mysqli_fetch_array($styles));

	foreach ($style_array as $style) {
		$style = mysqli_real_escape_string($connection,$style);
		$query_entry_count = sprintf("SELECT COUNT(*) as 'count' FROM %s WHERE brewStyle='%s' AND brewReceived='1'", $prefix."brewing", $style);
		$result = mysqli_query($connection,$query_entry_count) or die (mysqli_error($connection));
		$row = mysqli_fetch_array($result);
		if ($row['count'] > 0) $a[] = $style;
	}
	
	if (!empty($b))	$b = implode(",",$a);
	else $b="";
	return $b;

}


function assigned_judges($tid,$dbTable,$judging_assignments_db_table,$method=0){
	
	include (CONFIG.'config.php');
	mysqli_select_db($connection,$database);
	
	$query_assignments = sprintf("SELECT COUNT(*) as 'count' FROM %s WHERE assignTable='%s' AND assignment='J'", $judging_assignments_db_table, $tid);
	$assignments = mysqli_query($connection,$query_assignments) or die (mysqli_error($connection));
	$row_assignments = mysqli_fetch_assoc($assignments);

	if ($method == 0) {
		if ($row_assignments['count'] == 0) {
			$icon = "fa-plus-circle";
			$title = "Add judges to this table.";
		}
		else {
			$icon = "fa-edit";
			$title = "Edit judges assigned to this table.";
		}
		if ($dbTable == "default") $r = '<span id="delete-judges-'.$tid.'-count">'.$row_assignments['count'].'</span> <a href="'.$base_url.'index.php?section=admin&action=assign&go=judging_tables&filter=judges&id='.$tid.'" data-toggle="tooltip" data-placement="top" title="'.$title.'"><span id="delete-judges-'.$tid.'-icon" class="fa fa-lg '.$icon.'"></span></a>';
		else $r = $row_assignments['count'];
	}

	if ($method == 1) {
		$r = $row_assignments['count'];
	}
	
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
	
	if ($dbTable == "default") $r = '<span id="delete-stewards-'.$tid.'-count">'.$row_assignments['count'].'</span> <a href="'.$base_url.'index.php?section=admin&action=assign&go=judging_tables&filter=stewards&id='.$tid.'" data-toggle="tooltip" data-placement="top" title="'.$title.'"><span id="delete-stewards-'.$tid.'-icon" class="fa fa-lg '.$icon.'"></span></a>';
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

	$return = "";
	if ($row_user1) $return = $row_user1['id']."^".$row_user1['userLevel'];
	
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
	
	$query_flight_round = sprintf("SELECT COUNT(*) as 'count' FROM %s WHERE flightTable='%s' AND flightRound='%s' LIMIT 1", $prefix."judging_flights", $tid, $round);
	$flight_round = mysqli_query($connection,$query_flight_round) or die (mysqli_error($connection));
	$row_flight_round = mysqli_fetch_assoc($flight_round);
	
	if ($row_flight_round['count'] > 0) return TRUE; 
	else return FALSE;

}

function flight_round($tid,$flight,$round) {
	
	require(CONFIG.'config.php');
	mysqli_select_db($connection,$database);
	
	$query_flight_round = sprintf("SELECT flightRound FROM %s WHERE flightTable='%s' AND flightNumber='%s' LIMIT 1", $prefix."judging_flights", $tid, $flight);
	$flight_round = mysqli_query($connection,$query_flight_round) or die (mysqli_error($connection));
	$row_flight_round = mysqli_fetch_assoc($flight_round);
	
	if ($row_flight_round['flightRound'] == $round) return TRUE; 
	else return FALSE;

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
	
	if (!empty($row_assignments)) {
		do {
			$a[] .= $row_assignments['assignTable'];
		} while ($row_assignments = mysqli_fetch_assoc($assignments));
	}
	
	if (in_array($tid,$a)) return TRUE;
	else return FALSE;

}

function unavailable($bid,$location,$round,$tid) {
	
	require(CONFIG.'config.php');
	mysqli_select_db($connection,$database);
	
	$query_assignments = sprintf("SELECT COUNT(*) AS 'count' FROM %s WHERE bid='%s' AND assignRound='%s' AND assignLocation='%s'", $prefix."judging_assignments", $bid, $round, $location);
	$assignments = mysqli_query($connection,$query_assignments) or die (mysqli_error($connection));
	$row_assignments = mysqli_fetch_assoc($assignments);

	if ($row_assignments['count'] > 0) return TRUE;
	else return FALSE;

}

function like_dislike($likes,$dislikes,$styles) {
	
	require(CONFIG.'config.php');
	mysqli_select_db($connection,$database);

	// get the table's associated styles
	$s = explode(",",$styles);
	$r = "";
	$c = 0;
	$f = 0;
	
	// check for likes
	if (!empty($likes)) {
		$a = explode(",",$likes);
		foreach ($a as $value) {
			if (in_array($value,$s)) $c += 1;
		}
	}
	
	// check for dislikes
	if (!empty($dislikes)) {
		$d = explode(",",$dislikes);
		foreach ($d as $value) {
		   if (in_array($value,$s)) $f += 1; 
		}
	}

	if (($c > 0) && ($f == 0)) {
		$r .= "bg-success text-success|<span class=\"text-success\" style=\"margin: 0 0 10px 0;\"><span class=\"fa fa-thumbs-o-up\"></span> <strong>Available and Preferred Style(s).</strong><span>"; // 1 or more likes matched, color table cell green
		$r .= " <a tabindex=\"0\" role=\"button\" data-toggle=\"popover\" data-placement=\"right\" data-trigger=\"hover focus\" data-content=\"Paricipant is available for this round. One or more styles at the table are on the participant&rsquo;s &ldquo;likes&rdquo; list.\"><span class=\"fa fa-lg fa-info-circle\"></span></a>";
	}
	
	elseif (($c == 0) && ($f > 0)) {
		$r .= "bg-danger text-danger|<span class=\"text-danger\"><span class=\"fa fa-thumbs-o-down\"></span> <strong>Available but Non-Preferred Style(s).</strong></span>";
		$r .= " <a tabindex=\"0\" role=\"button\" data-toggle=\"popover\" data-placement=\"right\" data-trigger=\"hover focus\" data-content=\"Paricipant is available for this round. One or more styles are on the participant&rsquo;s &ldquo;dislikes&rdquo; list.\"><span class=\"fa fa-lg fa-info-circle\"></span></a>";
		// 1 or more dislikes matched, color table cell red
	}
	
	else {
		$r .="bg-grey text-grey|<span class=\"text-orange\"><span class=\"fa fa-star-o\"></span> <strong>Available.</strong></span>";
		$r .= " <a tabindex=\"0\" role=\"button\" data-toggle=\"popover\" data-placement=\"right\" data-trigger=\"hover focus\" data-content=\"Paricipant is available for this round.\"><span class=\"fa fa-lg fa-info-circle\"></span></a>";
	}

	return $r;

}

function entry_conflict($bid,$table_styles) {
	
	require(CONFIG.'config.php');
	mysqli_select_db($connection,$database);

	$d = 0;

	if (!empty($table_styles)) {

		$b = explode(",",$table_styles);

		foreach ($b as $style) {

			$query_style = sprintf("SELECT brewStyleGroup,brewStyleNum FROM %s WHERE id='%s'", $prefix."styles", $style);
			$style = mysqli_query($connection,$query_style) or die (mysqli_error($connection));
			$row_style = mysqli_fetch_assoc($style);

			if (($row_style) && ($bid != "999999999")) {
				
				$query_entries = sprintf("SELECT COUNT(*) as 'count' FROM %s WHERE brewBrewerID='%s' AND brewCategorySort='%s' AND brewSubCategory='%s'", $prefix."brewing", $bid, $row_style['brewStyleGroup'],$row_style['brewStyleNum']);
				if ($_SESSION['jPrefsTablePlanning'] == 0) $query_entries .= " AND brewReceived='1'";
				$entries = mysqli_query($connection,$query_entries) or die (mysqli_error($connection));
				$row_entries = mysqli_fetch_assoc($entries);

				if (($row_entries) && ($row_entries['count'] > 0)) $d += 1;

			}

		}

	}

	if ($d > 0) return TRUE;
	else return FALSE;
	
}

function unassign($bid,$location,$round,$tid) {
	
	require(CONFIG.'config.php');
	mysqli_select_db($connection,$database);
	$query_assignments = sprintf("SELECT id FROM %s WHERE bid='%s' AND assignRound='%s' AND assignLocation='%s'", $prefix."judging_assignments", $bid, $round, $location);
	$assignments = mysqli_query($connection,$query_assignments) or die (mysqli_error($connection));
	$row_assignments = mysqli_fetch_assoc($assignments);
	
	if (!empty($row_assignments)) $r = $row_assignments['id'];
	else $r = 0;
	
	return $r;
}

function assign_to_table($tid,$bid,$filter,$total_flights,$round,$location,$table_styles,$queued,$random) {

	/**
	 * Function almalgamates the above functions to output the correct form elements
	 * @param $bid = id of row in the brewer's table
	 * @param $tid = id of row in the judging_tables table
	 * @param $filter = judges or stewards from encoded URL
	 * @param $flight = flight number (query above)
	 * @param $round = the round number from the for loop
	 * @param $location = id of table's location from the judging_locations table
	 */

	// Define variables
	$unassign = unassign($bid,$location,$round,$tid);
	$unavailable = unavailable($bid,$location,$round,$tid);

	$r = "";
	if (entry_conflict($bid,$table_styles)) $disabled = "disabled"; else $disabled = "";
	if ($filter == "stewards") $role = "S"; else $role = "J";

	$r .= "<section>";

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
		$r .= '<input class="unassign-checkbox" type="checkbox" id="unassign'.$random.'" name="unassign'.$random.'" value="'.$unassign.'" '.$disabled.'>';
		$r .= ' Unassign from their current assignment and...</label>';
		$r .= '</div>';
		$r .= '</div>';
		
	}
		
	else $r .= '<input type="hidden" name="unassign'.$random.'" value="'.$unassign.'"/>';

	if ($queued == "Y") {
		
		$r .= '<div class="form-inline">';
		$r .= '<div class="form-group">';
		$r .= '<div class="input-group">';
	    $r .= '<label class="radio-inline">';
	    $r .= '<input type="radio" name="assignRound'.$random.'" value="'.$round.'" '.$selected.' '.$disabled.' /> Assign to this Table/Round';
	    $r .= '</label>';
	    $r .= '<label class="radio-inline">';
	    $r .= '<input type="radio" name="assignRound'.$random.'" value="0" '.$default.' /> Do Not Assign to This Table';
	    $r .= '</label>';
	    $r .= '</div>';
		$r .= '</div>';

	}

	else $r .= '<input type="hidden" name="assignTable'.$random.'" value="'.$tid.'" />';

	if ($queued == "N") {

		// Build the flights DropDown
		$r .= '<select class="selectpicker assign-flight" id="assignFlight'.$random.'" name="assignFlight'.$random.'" '.$disabled.' onchange="hj_enable(\''.$bid.'\',\'assignFlight'.$random.'\')">';

		$r .= '<option value="0" />Do Not Assign</option>';
			
			for($f=1; $f<$total_flights+1; $f++) {

				if (flight_round($tid,$f,$round)) {
					
					if (already_assigned($bid,$tid,$f,$round)) {
						$output = 'Assigned'; $selected = 'selected'; $style = ' style="color: #990000;"';
					}
					else $output = 'Assign'; $selected = ''; $style='';

					$r .= '<option value="'.$f.'" '.$selected.$style.' />'.$output.' to Flight '.$f.'</option>';

				}

			} // end for loop
		
		$r .= '</select>';

	}

	if ($queued == "Y") $r .= '<input type="hidden" name="assignFlight'.$random.'" value="1">';

	$r .= "</section>";

	return $r;

}


/*****************************************
 * This version of the asssign_to_table 
 * function needs tweaking to utilize the 
 * save_column() js ajax utility.
 * ***************************************
 */

/*
function assign_to_table($tid,$bid,$filter,$total_flights,$round,$location,$table_styles,$queued,$random,$base_url) {
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

$r .= "<section>";

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
	$r .= '<input class="unassign-checkbox" type="checkbox" id="unassign'.$random.'" name="unassign'.$random.'" value="'.$unassign.'" '.$disabled.'>';
	$r .= ' Unassign from their current assignment and...</label>';
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

	$hj_add = "head_judge_add('".$bid."','assign-flight-".$random."','".$tid."')";
	$save_column = "save_column('".$base_url."','assignFlight','judging_assignments','".$bid."','".$tid."','".$role."','".$round."','".$random."','assign-flight-".$location."')";

	$r .= sprintf("\n\n<select id=\"assign-flight-%s\" class=\"selectpicker assign-flight\" name=\"assignFlight\" %s onchange=\"%s;%s;\">",$random, $disabled, $hj_add, $save_column);

	/*
	// Build the flights DropDown
	$r .= '<select class="selectpicker assign-flight" name="assignFlight'.$random.'" '.$disabled.'>';
	$r .= '<option value="0" />Do Not Assign</option>';
		for($f=1; $f<$total_flights+1; $f++) {
			if (flight_round($tid,$f,$round)) {
				if (already_assigned($bid,$tid,$f,$round)) { $output = 'Assigned'; $selected = 'selected'; $style = ' style="color: #990000;"'; } else { $output = 'Assign'; $selected = ''; $style=''; }
				$r .= '<option value="'.$f.'" '.$selected.$style.' />'.$output.' to Flight '.$f.'</option>';
			}
		} // end for loop
	$r .= '</select>';
	*//*


	$r .= '<option data-judge-id="'.$bid.'" value="0" />Do Not Assign</option>'."\n";
		for($f=1; $f<$total_flights+1; $f++) {
			if (flight_round($tid,$f,$round)) {
				if (already_assigned($bid,$tid,$f,$round)) { $output = 'Assigned'; $selected = 'selected'; $style = ' style="color: #990000;"'; } else { $output = 'Assign'; $selected = ''; $style=''; }
				$r .= '<option data-judge-id="'.$bid.'" value="'.$f.'" '.$selected.$style.' />'.$output.' to Flight '.$f.'</option>'."\n";
			}
		} // end for loop
	$r .= '</select>'."\n"."\n";

}

if ($queued == "Y") {
		$r .= '<input type="hidden" name="assignFlight'.$random.'" value="1">';
	}

$r .= "</section>";

return $r;
}
*/

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

	$r = "";

	$query_brewer_info = sprintf("SELECT id,brewerFirstName,brewerLastName,brewerJudgeLikes,brewerJudgeDislikes,brewerJudgeMead,brewerJudgeCider,brewerJudgeRank,brewerJudgeID,brewerStewardLocation,brewerJudgeLocation,brewerJudgeExp,brewerJudgeNotes FROM %s WHERE uid='%s'", $prefix."brewer", $uid);
	$brewer_info = mysqli_query($connection,$query_brewer_info) or die (mysqli_error($connection));
	$row_brewer_info = mysqli_fetch_assoc($brewer_info);


	if (!empty($row_brewer_info)) {
		$r =
		$row_brewer_info['brewerFirstName']
		."^".$row_brewer_info['brewerLastName']
		."^".$row_brewer_info['brewerJudgeLikes']
		."^".$row_brewer_info['brewerJudgeDislikes']
		."^".$row_brewer_info['brewerJudgeMead']
		."^".$row_brewer_info['brewerJudgeRank']
		."^".$row_brewer_info['brewerJudgeID']
		."^".$row_brewer_info['brewerStewardLocation']
		."^".$row_brewer_info['brewerJudgeLocation']
		."^".$row_brewer_info['brewerJudgeExp']
		."^".$row_brewer_info['brewerJudgeNotes']
		."^".$row_brewer_info['id']
		."^".$row_brewer_info['brewerJudgeCider'];
	}

	if ($_SESSION['jPrefsQueued'] == "N") {
		
		$query_judge_info = sprintf("SELECT assignFlight,assignRound FROM %s WHERE bid='%s'", $prefix."judging_assignments", $uid);
		$judge_info = mysqli_query($connection,$query_judge_info) or die (mysqli_error($connection));
		$row_judge_info = mysqli_fetch_assoc($judge_info);

		if (!empty($row_judge_info)) $r .= "^".$row_judge_info['assignFlight']."^".$row_judge_info['assignRound'];

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
					$judge_rank = "";
					
					if (isset($assignment_info[5])) {
						$judge_rank = $assignment_info[5];
						$judge_rank_explode = explode(",",$assignment_info[5]);
						$judge_rank_display = $judge_rank_explode[0];
					}

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
					
					if (isset($assignment_info[5])) {
						$judge_rank = $assignment_info[5];
						$judge_rank_explode = explode(",",$assignment_info[5]);
						$judge_rank_display = $judge_rank_explode[0];
					}
					
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

function virtual_locations()
{
	require(CONFIG.'config.php');
	mysqli_select_db($connection,$database);
	$query_virtual_locations = sprintf("SELECT id FROM %s WHERE judgingLocType = 1", $prefix."judging_locations");
	$virtual_locations = mysqli_query($connection,$query_virtual_locations) or die (mysqli_error($connection));
	$row_virtual_locations = mysqli_fetch_assoc($virtual_locations);

	$return = array();
	do {
		$return[] = array(
			'id' => $row_virtual_locations['id'],
			'check' => 'Y-' . $row_virtual_locations['id']
		);
	} while ($row_virtual_locations = mysqli_fetch_assoc($virtual_locations));
	return $return;
}
?>