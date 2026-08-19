<?php

declare(strict_types=1);

function directory_contents_dropdown(string $directory,$file_name_selected,string $method="1"): string|array {

	$handle = opendir($directory);
	$filelist = [];

	while ($file = readdir($handle)) {

	   if ((!is_dir($file)) && (!is_link($file))) {
			$filelist[] = $file;
	   }

	}

	sort($filelist, SORT_NATURAL | SORT_FLAG_CASE);

	// Return dropdown options
	// For one-time use
	if ($method === "1") {
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
	if ($method === "2") {
		$return = [];
		$return = $filelist;
	}

	return $return;
}

function table_count_total($input): int {
	require(CONFIG.'config.php');
	$db_conn = new MysqliDb($connection);

	$db_conn->where('scoreTable', $input);
	$row_scores_1 = $db_conn->getOne($prefix."judging_scores", "COUNT(*) as 'count'");

	return $row_scores_1['count'];
}

function bos_place($eid): string {
	require(CONFIG.'config.php');
	$db_conn = new MysqliDb($connection);

	$db_conn->where('eid', $eid);
	$row_bos_place = $db_conn->getOne($prefix."judging_scores_bos", "scorePlace,scoreEntry");

	$return = $row_bos_place['scorePlace']."-".$row_bos_place['scoreEntry'];
	return $return;
}

function bos_method($value): string {

	$bos_method = match ($value) {
        "1" => "1st place only",
        "2" => "1st and 2nd places",
        "3" => "1st, 2nd, and 3rd places",
        "4" => "1st, 2nd, and 3rd places with HM",
        default => "Defined by Admin",
    };

	return $bos_method;
}

function bos_entry_info($eid,$table_id,$filter): string {

	require(CONFIG.'config.php');
	$local_db_conn = new MysqliDb($connection);

	if ($table_id == "default") $table_id = 1; else $table_id = $table_id;

	if ($filter != "default") {
		$filter_clean = preg_replace("/[^a-zA-Z0-9]+/", "", $filter);
		$entry_db_table = $prefix."brewing_".$filter_clean;
		$judging_tables_db_table = $prefix."judging_tables_".$filter_clean;
		$bos_scores_db_table = $prefix."judging_scores_bos_".$filter_clean;
		$brewer_db_table = $prefix."brewer_".$filter_clean;
	}

	else {
		$entry_db_table = $prefix."brewing";
		$judging_tables_db_table = $prefix."judging_tables";
		$bos_scores_db_table = $prefix."judging_scores_bos";
		$brewer_db_table = $prefix."brewer";
	}

	// Each table below may point at an archived competition (via $filter), and any one of them
	// may no longer exist - rawQuery()-family calls throw rather than fail gracefully in that case.
	$row_entries_1 = null;
	if (table_exists($entry_db_table)) {
		$local_db_conn->where("id", $eid);
		$row_entries_1 = $local_db_conn->getOne($entry_db_table, "id,brewStyle,brewCategorySort,brewCategory,brewSubCategory,brewName,brewBrewerFirstName,brewBrewerLastName,brewJudgingNumber,brewBrewerID");
	}
	// $row_entries_1 can legitimately come back null against an archive - a scored record in
	// judging_scores_<suffix> whose entry id has no corresponding row in brewing_<suffix> (e.g.
	// orphaned by a partial archive or an entry deleted after being scored). Every field pulled
	// from it below is guarded rather than assumed present.
	$style = ($row_entries_1['brewCategorySort'] ?? "").($row_entries_1['brewSubCategory'] ?? "");

	$row_tables_1 = null;
	if (table_exists($judging_tables_db_table)) {
		$local_db_conn->where("id", $table_id);
		$row_tables_1 = $local_db_conn->getOne($judging_tables_db_table, "id,tableName,tableNumber");
	}

	$row_bos_place_1 = null;
	if (table_exists($bos_scores_db_table)) {
		$local_db_conn->where("eid", $eid);
		$row_bos_place_1 = $local_db_conn->getOne($bos_scores_db_table, "id,scorePlace,scoreEntry");
	}

	$row_brewer = null;
	if ((isset($row_entries_1['brewBrewerID'])) && (table_exists($brewer_db_table))) {
		$local_db_conn->where("uid", $row_entries_1['brewBrewerID']);
		$row_brewer = $local_db_conn->getOne($brewer_db_table, "brewerLastName,brewerFirstName,brewerBreweryName");
	}

	$return = "";
	if (isset($row_entries_1['brewStyle'])) $return .= $row_entries_1['brewStyle']."^";  			// 0
	else $return .= " ^";
	if (isset($row_entries_1['brewCategorySort'])) $return .= $row_entries_1['brewCategorySort']."^";  	// 1
	else $return .= " ^";
	if (isset($row_entries_1['brewCategory'])) $return .= $row_entries_1['brewCategory']."^";  		// 2
	else $return .= " ^";
	if (isset($row_entries_1['brewSubCategory'])) $return .= $row_entries_1['brewSubCategory']."^";  		// 3
	else $return .= " ^";
	if (isset($row_brewer['brewerFirstName'])) $return .= $row_brewer['brewerFirstName']."^";  		// 4
	else $return .= " ^";
	if (isset($row_brewer['brewerLastName'])) $return .= $row_brewer['brewerLastName']."^";  			// 5
	else $return .= " ^";
	if (isset($row_entries_1['brewJudgingNumber'])) $return .= $row_entries_1['brewJudgingNumber']."^";   	// 6
	else $return .= " ^";
	if (isset($row_tables_1['id'])) $return .= $row_tables_1['id']."^";  						// 7
	else $return .= " ^";
	if (isset($row_tables_1['tableName'])) $return .= $row_tables_1['tableName']."^";   		// 8
	else $return .= " ^";
	if (isset($row_tables_1['tableNumber'])) $return .= $row_tables_1['tableNumber']."^";  		// 9
	else $return .= " ^";
	if (isset($row_bos_place_1['scorePlace'])) $return .= $row_bos_place_1['scorePlace']."^";  	// 10
	else $return .= " ^";
	if (isset($row_bos_place_1['scoreEntry'])) $return .= $row_bos_place_1['scoreEntry']."^";  	// 11
	else $return .= " ^";
	if (isset($row_entries_1['brewName'])) $return .= $row_entries_1['brewName']."^";  			// 12
	else $return .= " ^";
	// Position 13 falls back to the $eid this function was asked to look up (rather than a blank)
	// when the entry row itself is missing, since that id is still known and useful for tracing
	// the orphaned record back to its source.
	if (isset($row_entries_1['id'])) $return .= $row_entries_1['id']."^";   					// 13
	else $return .= $eid."^";
	if (isset($row_bos_place_1['id'])) $return .= $row_bos_place_1['id']."^";   				// 14
	else $return .= "N^";
	if (isset($row_entries_1['brewBrewerID'])) $return .= $row_entries_1['brewBrewerID']."^"; 			// 15
	else $return .= " ^";
	if (isset($row_brewer['brewerBreweryName'])) $return .= $row_brewer['brewerBreweryName']; 			//16

	return $return;
}

function style_type_info($type,$suffix="default"): string {
	require(CONFIG.'config.php');
	$db_conn = new MysqliDb($connection);

	if ($suffix == "default") $dbTable = $prefix."style_types";
	else $dbTable = $prefix."style_types_".$suffix;

	$row_style_type = null;
	if (table_exists($dbTable)) {
		$db_conn->where('id', $type);
		$row_style_type = $db_conn->getOne($dbTable);
	}

	$return = ($row_style_type['styleTypeBOS'] ?? "")."^".($row_style_type['styleTypeBOSMethod'] ?? "")."^".($row_style_type['styleTypeName'] ?? "");
	return $return;
}


function score_style_data($value): string {

	require(CONFIG.'config.php');
	require(LANG.'language.lang.php');
	$db_conn = new MysqliDb($connection);

	$return = "";

	/*
	if (HOSTED) $styles_db_table = "bcoem_shared_styles";
	else
	*/
	$styles_db_table = $prefix."styles";

	/*
	if (HOSTED) $query_styles = sprintf("SELECT brewStyleGroup,brewStyleNum,brewStyle,brewStyleType FROM %s WHERE id='%s' UNION ALL SELECT brewStyleGroup,brewStyleNum,brewStyle,brewStyleType FROM %s WHERE id='%s'", $prefix."styles", $value, $styles_db_table, $value);
	else
	*/
	$db_conn->where('id', $value);
	$row_styles = $db_conn->getOne($styles_db_table, "brewStyleGroup,brewStyleNum,brewStyle,brewStyleType");

	if ($row_styles) {
		$return =
		$row_styles['brewStyleGroup']."^". //0
		$row_styles['brewStyleNum']."^". //1
		$row_styles['brewStyle']."^". //2
		$row_styles['brewStyleType']; //3
	}
	
	return $return;

}

function score_entry_data($value): string {

	require(CONFIG.'config.php');
	$db_conn = new MysqliDb($connection);

	$db_conn->where('eid', $value);
	$row_scores = $db_conn->getOne($prefix."judging_scores", "id,eid,bid,scoreEntry,scorePlace,scoreMiniBOS");

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


function text_number(int|string $n): string {
    # Array holding the teen numbers. If the last 2 numbers of $n are in this array, then we'll add 'th' to the end of $n
    $teen_array = [11, 12, 13, 14, 15, 16, 17, 18, 19];

    # Array holding all the single digit numbers. If the last number of $n, or if $n itself, is a key in this array, then we'll add that key's value to the end of $n
    $single_array = [1 => 'st', 2 => 'nd', 3 => 'rd', 4 => 'th', 5 => 'th', 6 => 'th', 7 => 'th', 8 => 'th', 9 => 'th', 0 => 'th'];

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

function table_choose($section,$go,$action,$filter,$view,$script_name,$method): string {
	require(CONFIG.'config.php');
	$db_conn = new MysqliDb($connection);

	$table_choose = "";

	if ($method == "flight_choose") {
		$db_conn->where('flightTable', $filter);
		$row_flights = $db_conn->getOne($prefix."judging_flights", "flightTable");
		$totalRows_flights = $db_conn->count;
		if ($totalRows_flights > 0) $table_choose = $totalRows_flights."^".$row_flights['flightTable'];
	}

	
	elseif ($method == "form_select") {

		$db_conn->orderBy('tableNumber', 'ASC');
		$rows_tables = $db_conn->get($prefix."judging_tables");
		$totalRows_tables = $db_conn->count;

		if ($totalRows_tables > 0) {
			foreach ($rows_tables as $row_tables) {
				$table_choose .= '<option value="'.$row_tables['id'].'">'.$row_tables['tableNumber'].': '.$row_tables['tableName'].'</option>';
			}
		}

	}

	else {
		if ($method == "thickbox") $class = 'class="modal-window-link hide-loader menuItem"';
		if ($method == "none") $class = 'class="menuItem"';

		$random = random_generator(7,2);

		$db_conn->orderBy('tableNumber', 'ASC');
		$rows_tables = $db_conn->get($prefix."judging_tables");
		$totalRows_tables = $db_conn->count;

		if ($totalRows_tables > 0) {
			foreach ($rows_tables as $row_tables) {
				if ($filter == "mini_bos") $table_choose .= '<li class="small"><a data-fancybox data-type="iframe" class="modal-window-link hide-loader" href="'.$script_name.'?section='.$section.'&go='.$go.'&action='.$action.'&filter='.$filter.'&view='.$view.'&id='.$row_tables['id'].'" title="Print '.$row_tables['tableName'].'">'.$row_tables['tableNumber'].': '.$row_tables['tableName'].' (Mini-BOS)</a></li>';
				else $table_choose .= '<li class="small"><a data-fancybox data-type="iframe" class="modal-window-link hide-loader" href="'.$script_name.'?section='.$section.'&go='.$go.'&action='.$action.'&filter='.$filter.'&view='.$view.'&id='.$row_tables['id'].'" title="Print '.$row_tables['tableName'].'">'.$row_tables['tableNumber'].': '.$row_tables['tableName'].' </a></li>';
			}
		}

	}

	return $table_choose;

}

// Apparently unused.
function style_choose($section,$go,$action,$filter,$view,$script_name,$method): string {

	require(CONFIG.'config.php');
	mysqli_select_db($connection,$database);

	/*
	if (HOSTED) $styles_db_table = "bcoem_shared_styles";
	else
	*/
	$styles_db_table = $prefix."styles";

	$end = $_SESSION['style_set_category_end'];

	if ($method == "thickbox") { 
		$suffix = '';
		$class = 'class="modal-window-link hide-loader menuItem"'; 
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
			$style_choose .= '<a '.$class.' style="font-size: 0.9em; padding: 1px;" href="'.$script_name.'?section='.$section.'&go='.$go.'&action='.$action.'&filter='.$num.$suffix.'&view='.$view.'" title="Print '.style_convert($i,"1",$base_url).'">'.$num.' '.style_convert($i,"1",$base_url).' ('.$row['count'].' entries)</a>'; 
		}

	}

	/*
	if (HOSTED) $query_styles = sprintf("SELECT brewStyle,brewStyleGroup FROM `%s` WHERE brewStyleGroup > '%s' UNION ALL SELECT brewStyle,brewStyleGroup FROM `%s` WHERE brewStyleGroup > '%s'", $styles_db_table, $end, $prefix."styles", $end);
	else 
	*/
	$query_styles = sprintf("SELECT brewStyle,brewStyleGroup FROM `%s` WHERE brewStyleGroup > '%s'", $prefix."styles",$end);
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

function flight_count($table_id,$method): bool|int {
	require(CONFIG.'config.php');
	$db_conn = new MysqliDb($connection);

	$db_conn->where('flightTable', $table_id);
	$row_flights = $db_conn->getOne($prefix."judging_flights", "COUNT(*) as 'count'");
    return match ($method) {
        "1" => $row_flights['count'] > 0,
        "2" => $row_flights['count'],
        default => false,
    };
}

function orphan_styles(): string {
	require(CONFIG.'config.php');
	$db_conn = new MysqliDb($connection);

	/*
	if (HOSTED) $styles_db_table = "bcoem_shared_styles";
	else
	*/
	$styles_db_table = $prefix."styles";

	$end = $_SESSION['style_set_category_end'];

	/*
	if (HOSTED) $query_styles = sprintf("SELECT id,brewStyle,brewStyleType WHERE brewStyleGroup >= %s FROM %s UNION ALL SELECT id,brewStyle,brewStyleType FROM %s WHERE brewStyleGroup >= %s;", $styles_db_table, $end, $prefix."styles", $end);
	else
	*/
	$db_conn->where('brewStyleGroup', $end, '>=');
	$rows_styles = $db_conn->get($prefix."styles", null, "id,brewStyle,brewStyleType");
	$totalRows_styles = $db_conn->count;

	$db_conn->where('styleTypeOwn', 'custom');
	$rows_style_types = $db_conn->get($prefix."style_types", null, "id");

	$a = [];
	foreach ($rows_style_types as $row_style_types) { $a[] = style_type($row_style_types['id'], "2", "bcoe"); }

	$return = "";
	if ($totalRows_styles > 0) {
		foreach ($rows_styles as $row_styles) {
			if (!in_array($row_styles['brewStyleType'], $a)) {
				if ($row_styles['brewStyleType'] > 3) $return .= "<p><a href='index.php?section=admin&amp;go=styles&amp;action=edit&amp;id=".$row_styles['id']."'><span class='icon'><img src='".$base_url."images/pencil.png' alt='Edit ".$row_styles['brewStyle']."' title='Edit ".$row_styles['brewStyle']."'></span></a>".$row_styles['brewStyle']."</p>";
			}
		}
	}

	if ($return === "") $return .= "<p>All custom styles have a valid style type associated with them.</p>";
	return $return;

}

function score_table_choose($dbTable,$judging_tables_db_table,$judging_scores_db_table): string {

	require(CONFIG.'config.php');
	$db_conn = new MysqliDb($connection);

	$db_conn->orderBy('tableNumber', 'ASC');
	$rows_tables = $db_conn->get($judging_tables_db_table, null, "id,tableNumber,tableName");
	$totalRows_tables = $db_conn->count;

	$r = "";

	if ($totalRows_tables > 0) {

		foreach ($rows_tables as $row_tables) {

			$db_conn->where('scoreTable', $row_tables['id']);
			$row_scores = $db_conn->getOne($judging_scores_db_table, "COUNT(*) as 'count'");

			if ($row_scores['count'] > 0) $a = "edit"; else $a = "add";
        	$r .= "<li class=\"small\"><a href=\"index.php?section=admin&amp;&go=judging_scores&amp;action=".$a."&amp;id=".$row_tables['id']."\">Table ".$row_tables['tableNumber'].": ".$row_tables['tableName']."</a></li>";

		}

	}
	
	else $r = "<li class=\"disabled small\"><a href=\"#\">No tables have been defined</a></li>";
	return $r;
}

function score_custom_winning_choose($special_best_info_db_table,$special_best_data_db_table): string {
	require(CONFIG.'config.php');
	$db_conn = new MysqliDb($connection);

	$db_conn->orderBy('sbi_name', 'ASC');
	$rows_sbi = $db_conn->get($special_best_info_db_table, null, "id,sbi_name");
	$totalRows_sbi = $db_conn->count;

	$r = "";

	if ($totalRows_sbi > 0) {

		foreach ($rows_sbi as $row_sbi) {

			$db_conn->where('sid', $row_sbi['id']);
			$row_scores = $db_conn->getOne($special_best_data_db_table, "COUNT(*) as 'count'");

			if ($row_scores['count'] > 0) $a = "edit";
			else $a = "add";

        	$r .= "<li class=\"small\"><a href=\"index.php?section=admin&amp;&go=special_best_data&amp;action=".$a."&amp;id=".$row_sbi['id']."\">".$row_sbi['sbi_name']."</a></li>";

		}

	}
	
	else {
		
		$r = "<li class=\"disabled small\"><a href=\"#\">No custom categories have been defined</a></li>";
		$r .= "<li role=\"separator\" class=\"divider\"></li>";
		$r .= "<li class=\"small\"><a href=\"".$base_url."index.php?section=admin&amp;go=special_best&amp;action=add\">Add a Custom Category</a></li>";

	}

	return $r;
}

function participant_choose($brewer_db_table,$pro_edition,$judge,$evaluation='0'): string {
	require(CONFIG.'config.php');
	$db_conn = new MysqliDb($connection);

	if ($pro_edition == 1) {
		if (($evaluation == 1) && ($judge == 1)) {
			$db_conn->where('brewerJudge', 'Y');
			$db_conn->orderBy('brewerLastName', 'ASC');
			$rows_brewers = $db_conn->get($brewer_db_table, null, "uid,brewerFirstName,brewerLastName");
		}
		else {
			$db_conn->where('brewerBreweryName IS NOT NULL');
			$db_conn->orderBy('brewerBreweryName', 'ASC');
			$rows_brewers = $db_conn->get($brewer_db_table, null, "uid,brewerBreweryName");
		}
	}

	else {
		if ($judge == 1) {
			$db_conn->where('brewerJudge', 'Y');
			$db_conn->orderBy('brewerLastName', 'ASC');
			$rows_brewers = $db_conn->get($brewer_db_table, null, "uid,brewerFirstName,brewerLastName");
		}
		else {
			$db_conn->orderBy('brewerLastName', 'ASC');
			$rows_brewers = $db_conn->get($brewer_db_table, null, "uid,brewerFirstName,brewerLastName");
		}
	}

	$output = "";
	$output .= "<select class=\"selectpicker\" name=\"participants\" id=\"participants\"";
	if ($judge == 0) $output .= " onchange=\"jumpMenu('self',this,0)\"";
	if ($judge == 1) $output .= " required";
	$output .= " data-size=\"15\" data-width=\"auto\" data-live-search=\"true\">";
	
	if ($judge == 0) $output .= "<option value=\"\" selected disabled data-icon=\"fa fa-plus-circle\">Add an Entry For...</option>";
	else $output .= "<option value=\"\"></option>";
	
	if ($rows_brewers) {

		foreach ($rows_brewers as $row_brewers) {

			if ($judge == 1) {
				$output .= "<option value=\"".$row_brewers['uid']."\">".$row_brewers['brewerLastName'].", ".$row_brewers['brewerFirstName']."</option>";
			}

			else {
				if ($pro_edition == 1) $output .= "<option value=\"index.php?section=admin&amp;go=entries&amp;action=add&amp;bid=".$row_brewers['uid']."\" data-content=\"<span class='small'>".$row_brewers['brewerBreweryName']."</span>\">".$row_brewers['brewerBreweryName']."</option>";
				else $output .= "<option value=\"index.php?section=admin&amp;go=entries&amp;action=add&amp;bid=".$row_brewers['uid']."\" data-content=\"<span class='small'>".$row_brewers['brewerLastName'].", ".$row_brewers['brewerFirstName']."</span>\">".$row_brewers['brewerLastName'].", ".$row_brewers['brewerFirstName']."</option>";
			}

		}

	}

	$output .= "</select>";

	return $output;
}

function admin_help($go,$header_output,$action,$filter): string {
	include (CONFIG.'config.php');
	switch($go) {
		case "preferences": $page = "site_prefs";
		break;

		case "judging_preferences": $page = "comp_org_prefs";
		break;

		case "style_types": $page = "style_types";
		break;

		case "styles":
			$page = match ($action) {
                "add", "edit" => "custom_style",
                default => "accepted_style",
            };
		break;

		case "special_best":
		case "special_best_data": $page = "custom_winner";
		break;

		case "judging":
			$page = match ($filter) {
                "judges", "stewards", "staff" => "assigning",
                default => "judging_locations",
            };
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
			$page = match ($filter) {
                "judges", "assignJudges" => "judges",
                "stewards", "assignStewards" => "stewards",
                default => "participants",
            };
		break;


		case "entries": $page = "entries";
		break;

		case "assign": $page = "assigning";
		break;

		case "judging_tables":
			$page = match ($action) {
                "assign" => "assigning",
                default => "tables",
            };
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

	$return = '<p><span class="icon"><img src="'.$base_url.'/images/help.png" /></span><a data-fancybox data-type="iframe" class="modal-window-link hide-loader" href="http://brewingcompetitions.com/'.$page.'.html" title="BCOE&amp;M Help for '.$header_output.'">Help</a></p>';
	return $return;
}

function custom_modules($type,$method): bool|string {
	require(CONFIG.'config.php');
	$db_conn = new MysqliDb($connection);

	if ($type == "reports") { $type = 1; $modal = "class='modal-window-link hide-loader'"; }
	if ($type == "exports") { $type = 2; $modal = ""; }

	if ($method == 1) {

		$db_conn->where('mod_type', $type);
		$row_custom_number = $db_conn->getOne($prefix."mods", "COUNT(*) as 'count'");

		if ($row_custom_number['count'] > 0) return TRUE;
	}

	if ($method == 2) {

		$db_conn->where('mod_type', $type);
		$db_conn->orderBy('mod_name', 'ASC');
		$rows_custom_mod = $db_conn->get($prefix."mods");
		$output = "";
		foreach ($rows_custom_mod as $row_custom_mod) {
			$output .= "<li><a ".$modal." href='".$base_url."mods/".$row_custom_mod['mod_filename']."'>".$row_custom_mod['mod_name']."</a></li>";
		}

		return $output;
	}

	return false;
}

function total_discount(): string {
	require(CONFIG.'config.php');
	$db_conn = new MysqliDb($connection);

	$db_conn->where('brewerDiscount', 'Y');
	$rows_discount = $db_conn->get($prefix."brewer", null, "uid");
	$totalRows_discount = $db_conn->count;

	foreach ($rows_discount as $row_discount) { $a[] = $row_discount['uid']; }

	foreach ($a as $brewer_id) {

		$db_conn->where('brewBrewerId', $brewer_id);
		$row_discount_number = $db_conn->getOne($prefix."brewing", "COUNT(*) as 'count'");
		$b[] = $row_discount_number['count'];

	}

	$return = $totalRows_discount."^".array_sum($b);
	return $return;
}

function flight_entry_info($entry_id): ?string {
	require(CONFIG.'config.php');
	$db_conn = new MysqliDb($connection);

	$db_conn->where('flightEntryID', $entry_id);
	$row_flight_number = $db_conn->getOne($prefix."judging_flights", "id,flightNumber,flightEntryID,flightRound");

	if ($row_flight_number) return $row_flight_number['id']."^".$row_flight_number['flightNumber']."^".$row_flight_number['flightEntryID']."^".$row_flight_number['flightRound'];

	return null;
}

function flight_round_number($flight_table,$flight_number): string {

	require(CONFIG.'config.php');
	$db_conn = new MysqliDb($connection);

	// $received = get_table_info("1","count_total",$flight_table,"default","default");

	$db_conn->where('flightTable', $flight_table);
	$db_conn->where('flightNumber', $flight_number);
	$rows_round_no = $db_conn->get($prefix."judging_flights", null, "flightRound");
	$totalRows_round_no = $db_conn->count;

	$all_recorded = [];

	foreach ($rows_round_no as $row_round_no) {

		if (!empty($row_round_no['flightRound'])) $all_recorded[] = 1;
		else $all_recorded[] = 0;

	}

	$all_recorded_sum = array_sum($all_recorded);

	if ($totalRows_round_no == $all_recorded_sum) {
		$db_conn->where('flightTable', $flight_table);
		$db_conn->where('flightNumber', $flight_number);
		$db_conn->orderBy('id', 'DESC');
		$row_round_no = $db_conn->getOne($prefix."judging_flights", "flightRound");
		return $row_round_no['flightRound'];
	}
	return "";

}

// Define Custom Functions
function bos_judge_eligible($uid): string {

	require(CONFIG.'config.php');
	$db_conn = new MysqliDb($connection);

	$query_eligible = "SELECT a.scorePlace,scoreTable FROM ".$prefix."judging_scores"." a, ".$prefix."brewing"." b WHERE a.scorePlace IS NOT NULL AND a.eid = b.id AND b.brewBrewerID = ? ORDER BY scoreTable ASC";
	$rows_eligible = $db_conn->rawQuery($query_eligible, [$uid]);
	$totalRows_eligible = $db_conn->count;

	$return = "";
	unset($first_places);
	unset($second_places);
	unset($third_places);

	if ($totalRows_eligible > 0) {
		$places = [];
		foreach ($rows_eligible as $row_eligible) {
			$places[] = $row_eligible['scorePlace']."-".$row_eligible['scoreTable'];
		}
		$places = implode("|",$places);
		$return .= $places;
	}

	return $return;

}

function judging_location_avail(string $loc_id,string $judge_avail,int $method=0): string {
	require(CONFIG.'config.php');
	$db_conn = new MysqliDb($connection);

	$return = "";

	$db_conn->where('id', $loc_id);
	$row_judging_loc3 = $db_conn->getOne($prefix."judging_locations", "judgingLocName,judgingDate,judgingLocation,judgingLocType");

	if ($row_judging_loc3) {
		if (($method === 0) && (str_starts_with($judge_avail, "Y")) && (!empty($row_judging_loc3['judgingLocName'])) && ($row_judging_loc3['judgingLocType'] < 2)) $return = $row_judging_loc3['judgingLocName']."<br>";
		else if (($method === 1) && (str_starts_with($judge_avail, "Y")) && (!empty($row_judging_loc3['judgingLocName'])) && ($row_judging_loc3['judgingLocType'] == 2)) $return = $row_judging_loc3['judgingLocName']."<br>";
	}
	
	return $return;

}

function table_score_data($eid,$score_table,$suffix): string {

	require(CONFIG.'config.php');
	$db_conn = new MysqliDb($connection);

	// $suffix is used as part of a raw table name below, which MysqliDb does not escape,
	// so it's allow-listed to word characters since it's spliced rather than bound.
	if ($suffix != "default") $suffix = "_".preg_replace("/[^a-zA-Z0-9_]+/", "", $suffix); else $suffix = "";

	// Each table below may point at an archived competition (via $suffix), and any one of them
	// may no longer exist - rawQuery()-family calls throw rather than fail gracefully in that case.
	$row_entries = null;
	if (table_exists($prefix."brewing".$suffix)) {
		$db_conn->where('id', $eid);
		$row_entries = $db_conn->getOne($prefix."brewing".$suffix, "id, brewStyle,brewCategorySort,brewCategory,brewSubCategory,brewName,brewBrewerFirstName,brewBrewerLastName,brewJudgingNumber,brewBrewerID");
	}
	// $row_entries can legitimately come back null against an archive - a scored record in
	// judging_scores<suffix> whose entry id has no corresponding row in brewing<suffix> (e.g.
	// orphaned by a partial archive or an entry deleted after being scored). Every field pulled
	// from it below is guarded rather than assumed present, matching bos_entry_info() above.
	$style = ($row_entries['brewCategorySort'] ?? "").($row_entries['brewSubCategory'] ?? "");

	$style_name = $row_entries['brewStyle'] ?? "";

	$row_tables = null;
	if (table_exists($prefix."judging_tables".$suffix)) {
		$db_conn->where('id', $score_table);
		$row_tables = $db_conn->getOne($prefix."judging_tables".$suffix, "id,tableName,tableNumber");
	}
	$totalRows_tables = $db_conn->count;

	$row_brewer = null;
	if ((isset($row_entries['brewBrewerID'])) && (table_exists($prefix."brewer".$suffix))) {
		$db_conn->where('uid', $row_entries['brewBrewerID']);
		$row_brewer = $db_conn->getOne($prefix."brewer".$suffix, "brewerLastName,brewerFirstName,brewerBreweryName");
	}

	// Position 0 falls back to $eid (rather than blank) when the entry row itself is missing,
	// since that id is still known and useful for tracing the orphaned record back to its source.
	$return =
	($row_entries['id'] ?? $eid)."^". //0
	($row_entries['brewStyle'] ?? "")."^". //1
	($row_entries['brewCategory'] ?? "")."^". //2
	($row_entries['brewName'] ?? "")."^". //3
	($row_brewer['brewerFirstName'] ?? "")."^". //4
	($row_brewer['brewerLastName'] ?? "")."^". //5
	($row_entries['brewJudgingNumber'] ?? "")."^". //6
	($row_entries['brewBrewerID'] ?? "")."^". //7
	($row_entries['brewCategorySort'] ?? "")."^". //8
	($row_tables['id'] ?? "")."^". //9
	($row_tables['tableName'] ?? "")."^". //10
	($row_tables['tableNumber'] ?? "")."^". //11
	$style."^". //12
	$style_name."^". //13
	($row_brewer['brewerBreweryName'] ?? "")."^". //14
	($row_entries['brewSubCategory'] ?? ""); //15

	return $return;

}


function received_entries(): string {

	require(CONFIG.'config.php');
	$db_conn = new MysqliDb($connection);

	/*
	if (HOSTED) $styles_db_table = "bcoem_shared_styles";
	else
	*/
	$styles_db_table = $prefix."styles";

	$style_array = [];

	if ($_SESSION['prefsStyleSet'] == "BJCP2025") {
		$query_styles = "SELECT brewStyle FROM ".$prefix."styles"." WHERE (brewStyleVersion='BJCP2025' AND brewStyleType='2') OR (brewStyleVersion='BJCP2021' AND brewStyleType !='2') OR brewStyleOwn='custom'";
		$rows_styles = $db_conn->rawQuery($query_styles);
	}
	else {
		$query_styles = "SELECT brewStyle FROM ".$prefix."styles"." WHERE (brewStyleVersion=? OR brewStyleOwn='custom')";
		$rows_styles = $db_conn->rawQuery($query_styles, [$_SESSION['prefsStyleSet']]);
	}

	foreach ($rows_styles as $row_styles) { $style_array[] = $row_styles['brewStyle']; }

	$a = [];
	foreach ($style_array as $style) {
		$db_conn->where('brewStyle', $style);
		$db_conn->where('brewReceived', '1');
		$row = $db_conn->getOne($prefix."brewing", "COUNT(*) as 'count'");
		if ($row['count'] > 0) $a[] = $style;
	}
	
	if (!empty($b))	return implode(",",$a);
	return "";

}


function assigned_judges($tid,$dbTable,$judging_assignments_db_table,$method=0): string|int {

	require(CONFIG.'config.php');
	$db_conn = new MysqliDb($connection);

	$db_conn->where('assignTable', $tid);
	$db_conn->where('assignment', 'J');
	$row_assignments = $db_conn->getOne($judging_assignments_db_table, "COUNT(*) as 'count'");

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
		return $row_assignments['count'];
	}
	
	return $r;
}

function assigned_stewards($tid,$dbTable,$judging_assignments_db_table): string|int {

	require(CONFIG.'config.php');
	$db_conn = new MysqliDb($connection);

	$db_conn->where('assignTable', $tid);
	$db_conn->where('assignment', 'S');
	$row_assignments = $db_conn->getOne($judging_assignments_db_table, "COUNT(*) as 'count'");
	if ($row_assignments['count'] == 0) {
		$icon = "fa-plus-circle";
		$title = "Add stewards to this table.";
	}
	
	else {
		$icon = "fa-edit";
		$title = "Edit stewards assigned to this table.";
	}
	
	if ($dbTable == "default") return '<span id="delete-stewards-'.$tid.'-count">'.$row_assignments['count'].'</span> <a href="'.$base_url.'index.php?section=admin&action=assign&go=judging_tables&filter=stewards&id='.$tid.'" data-toggle="tooltip" data-placement="top" title="'.$title.'"><span id="delete-stewards-'.$tid.'-icon" class="fa fa-lg '.$icon.'"></span></a>';
	
	return $row_assignments['count'];

}

function date_created($uid,$date_format,$time_format,$timezone,$dbTable): string {

	require(CONFIG.'config.php');
	$db_conn = new MysqliDb($connection);
	if ($dbTable != "default") $dbTable = $dbTable; else $dbTable = $prefix."users";
	// $dbTable is allow-listed to word characters at the source (includes/url_variables.inc.php)
	// since it's spliced directly into SQL as a table name rather than passed as a bound parameter.
	$query1 = sprintf("SHOW COLUMNS FROM `%s` LIKE 'userCreated'",$dbTable);
	$rows1 = $db_conn->rawQuery($query1);
	$exists = !empty($rows1);

	if ($exists) {

		$db_conn->where('id', $uid);
		$row_user = $db_conn->getOne($dbTable, "userCreated");
		$totalRows_user = $db_conn->count;

		if (($totalRows_user == 1) && ($row_user['userCreated'] != "")) {
			$result = "<span class=\"hidden\">".strtotime($row_user['userCreated'])."</span>".getTimeZoneDateTime($timezone, strtotime($row_user['userCreated']), $date_format,  $time_format, "short", "date-time-no-gmt");
		}

		else $result = "&nbsp;";
	}
	
	else $result = "&nbsp;";
	
	return $result;
}

function user_info($uid): string {

	require(CONFIG.'config.php');
	$db_conn = new MysqliDb($connection);

	$db_conn->where('id', $uid);
	$row_user1 = $db_conn->getOne($prefix."users", "id,userLevel,userAdminObfuscate");
	if ($row_user1) return $row_user1['id']."^".$row_user1['userLevel']."^".$row_user1['userAdminObfuscate'];
	
	return "";

}

function sbd_count($id): int {

	require(CONFIG.'config.php');
	$db_conn = new MysqliDb($connection);

	$db_conn->where('sid', $id);
	$row_sbd = $db_conn->getOne($prefix."special_best_data", "COUNT(*) as 'count'");
	return $row_sbd['count'];

}

function special_best_info($sid): string {

	require(CONFIG.'config.php');
	$db_conn = new MysqliDb($connection);

	$db_conn->where('id', $sid);
	$row_sbi = $db_conn->getOne($prefix."special_best_info", "id,sbi_name");
	$totalRows_sbi = $db_conn->count;

	return $row_sbi['id']."^".$row_sbi['sbi_name'];

}

// --------------- Custom Functions --------------------- //

 function table_round($tid,$round): bool {
	require(CONFIG.'config.php');
	$db_conn = new MysqliDb($connection);

	$db_conn->where('flightTable', $tid);
	$db_conn->where('flightRound', $round);
	$row_flight_round = $db_conn->getOne($prefix."judging_flights", "COUNT(*) as 'count'");

	return $row_flight_round['count'] > 0;

}

function flight_round($tid,$flight,$round): bool {
	require(CONFIG.'config.php');
	$db_conn = new MysqliDb($connection);

	$db_conn->where('flightTable', $tid);
	$db_conn->where('flightNumber', $flight);
	$row_flight_round = $db_conn->getOne($prefix."judging_flights", "flightRound");

	return $row_flight_round['flightRound'] == $round;

}

function already_assigned($bid,$tid,$flight,$round): bool {
	require(CONFIG.'config.php');
	$db_conn = new MysqliDb($connection);

	$db_conn->where('bid', $bid);
	$db_conn->where('assignTable', $tid);
	$db_conn->where('assignFlight', $flight);
	$db_conn->where('assignRound', $round);
	$row_assignments = $db_conn->getOne($prefix."judging_assignments", "COUNT(*) as 'count'");

	return $row_assignments['count'] == 1;

}

function at_table($bid,$tid): bool {
	require(CONFIG.'config.php');
	$db_conn = new MysqliDb($connection);

	$db_conn->where('bid', $bid);
	$rows_assignments = $db_conn->get($prefix."judging_assignments", null, "assignTable");

	$a = [];

	if (!empty($rows_assignments)) {
		foreach ($rows_assignments as $row_assignments) {
			$a[] = $row_assignments['assignTable'];
		}
	}

	return in_array($tid,$a);

}

function unavailable($bid,$location,$round,$tid): bool {
	require(CONFIG.'config.php');
	$db_conn = new MysqliDb($connection);

	$db_conn->where('bid', $bid);
	$db_conn->where('assignRound', $round);
	$db_conn->where('assignLocation', $location);
	$row_assignments = $db_conn->getOne($prefix."judging_assignments", "COUNT(*) as 'count'");

	return $row_assignments['count'] > 0;

}

function like_dislike($likes,$dislikes,$styles): string {
	require(CONFIG.'config.php');

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

	if (($c > 0) && ($f === 0)) {
		$r .= "bg-success text-success|<span class=\"text-success\"><span class=\"fa fa-thumbs-o-up\"></span> <strong>Available and Preferred Style(s).</strong><span>"; // 1 or more likes matched, color table cell green
		$r .= " <a class=\"hide-loader\" tabindex=\"0\" role=\"button\" data-toggle=\"popover\" data-placement=\"right\" data-trigger=\"click hover focus\" data-content=\"Paricipant is available for this round. One or more styles at the table are on the participant&rsquo;s &ldquo;likes&rdquo; list.\"><span class=\"fa fa-info-circle\"></span></a>";
	}
	
	elseif (($c === 0) && ($f > 0)) {
		$r .= "bg-danger text-danger|<span class=\"text-danger\"><span class=\"fa fa-thumbs-o-down\"></span> <strong>Available but Non-Preferred Style(s).</strong></span>";
		$r .= " <a class=\"hide-loader\" tabindex=\"0\" role=\"button\" data-toggle=\"popover\" data-placement=\"right\" data-trigger=\"click hover focus\" data-content=\"Paricipant is available for this round. One or more styles are on the participant&rsquo;s &ldquo;dislikes&rdquo; list.\"><span class=\"fa fa-info-circle\"></span></a>";
		// 1 or more dislikes matched, color table cell red
	}
	
	else {
		$r .="bg-grey text-grey|<span class=\"text-orange\"><span class=\"fa fa-star-o\"></span> <strong>Available.</strong></span>";
		$r .= " <a class=\"hide-loader\" tabindex=\"0\" role=\"button\" data-toggle=\"popover\" data-placement=\"right\" data-trigger=\"click hover focus\" data-content=\"Paricipant is available for this round.\"><span class=\"fa fa-info-circle\"></span></a>";
	}

	return $r;

}

function entry_conflict($bid,$table_styles): bool {
	require(CONFIG.'config.php');
	$db_conn = new MysqliDb($connection);

	/*
	if (HOSTED) $styles_db_table = "bcoem_shared_styles";
	else
	*/
	$styles_db_table = $prefix."styles";

	$d = 0;

	if (!empty($table_styles)) {

		$b = explode(",",$table_styles);

		foreach ($b as $style) {

			/*
			if (HOSTED) $query_style = sprintf("SELECT brewStyleGroup,brewStyleNum FROM %s WHERE id='%s' UNION ALL SELECT brewStyleGroup,brewStyleNum FROM %s WHERE id='%s'", $styles_db_table, $style, $prefix."styles", $style);
			else
			*/
			$db_conn->where('id', $style);
			$row_style = $db_conn->getOne($prefix."styles", "brewStyleGroup,brewStyleNum");

			if (($row_style) && ($bid != "999999999")) {

				$db_conn->where('brewBrewerID', $bid);
				$db_conn->where('brewCategorySort', $row_style['brewStyleGroup']);
				$db_conn->where('brewSubCategory', $row_style['brewStyleNum']);
				if ($_SESSION['jPrefsTablePlanning'] == 0) $db_conn->where('brewReceived', '1');
				$row_entries = $db_conn->getOne($prefix."brewing", "COUNT(*) as 'count'");

				if (($row_entries) && ($row_entries['count'] > 0)) $d += 1;

			}

		}

	}

	return $d > 0;
	
}

function unassign($bid,$location,$round,$tid): int {
	require(CONFIG.'config.php');
	$db_conn = new MysqliDb($connection);
	$db_conn->where('bid', $bid);
	$db_conn->where('assignRound', $round);
	$db_conn->where('assignLocation', $location);
	$row_assignments = $db_conn->getOne($prefix."judging_assignments", "id");


	if (!empty($row_assignments)) return $row_assignments['id'];
	
	return 0;
}
         
function assign_to_table($tid,$bid,$filter,$total_flights,$round,$location,$table_styles,$queued,$random,$ind_aff_flag): string {

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
	$disabled = "";
	if (entry_conflict($bid,$table_styles)) $disabled = "disabled"; 
	// if ($ind_aff_flag) $disabled = "disabled"; 
	
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
		$r .= '<div class="checkbox" style="padding-bottom: 10px;">';
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
		$r .= '<div class="input-group" style="padding-bottom: 10px;">';
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
						$output = 'Assigned'; 
						$selected = 'selected'; 
					}

					else {
						$output = 'Assign'; 
						$selected = ''; 
					}

					$r .= '<option value="'.$f.'" '.$selected.' />'.$output.' to Flight '.$f.'</option>';

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
	$save_column = "save_column('".$ajax_url."','assignFlight','judging_assignments','".$bid."','".$tid."','".$role."','".$round."','".$random."','assign-flight-".$location."')";

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

function judge_alert($round,$bid,$tid,$location,$likes,$dislikes,$table_styles,$id,$ind_aff_flag): string {
	
	if (table_round($tid,$round)) {
		
		$unavailable = unavailable($bid,$location,$round,$tid);
		$entry_conflict = entry_conflict($bid,$table_styles);
		$at_table = at_table($bid,$tid);
		
		if ($unavailable) {
		    
		    $r = "bg-purple text-purple|";
		    if ($ind_aff_flag) $r .= "<span class=\"text-purple\"><span class=\"fa fa-check\"></span> <strong>Assigned.</strong> Participant is assigned to another table in this round.</span><br><span class=\"fa fa-exclamation-circle\"></span> <strong>Conflict.</strong> Participant has reported an affiliation with one or more participants who have entries at this table. <strong>You are able to assign them to this table if you wish, but do so with caution and due diligence by checking their affiliation(s) via Manage Entries.</strong>";
		    else $r .= "<span class=\"text-purple\"><span class=\"fa fa-check\"></span> <strong>Assigned.</strong> Participant is assigned to another table in this round.</span>";
		    
		}
		
		if ($entry_conflict) $r = "bg-info text-info|<span class=\"text-info\"><span class=\"fa fa-ban\"></span> <strong>Disabled.</strong> Participant has an entry at this table.</span>";

		if ((!$unavailable) && (!$entry_conflict)) {
			
			if ($ind_aff_flag) {
				
				$r = "bg-teal text-teal|<span class=\"fa fa-exclamation-circle\"></span> <strong>Conflict.</strong> Participant has reported an affiliation with one or more participants who have entries at this table. <strong>You are able to assign them to this table if you wish, but do so with caution and due diligence by checking their affiliation(s) via Manage Entries.</strong>";

			}
			
			$r = like_dislike($likes,$dislikes,$table_styles);
		}

	}
	
	else $r = '';
	return $r;
}

function judge_info($uid): string {
	require(CONFIG.'config.php');
	$db_conn = new MysqliDb($connection);

	$r = "";

	$db_conn->where('uid', $uid);
	$row_brewer_info = $db_conn->getOne($prefix."brewer", "id,brewerFirstName,brewerLastName,brewerJudgeLikes,brewerJudgeDislikes,brewerJudgeMead,brewerJudgeCider,brewerJudgeRank,brewerJudgeID,brewerStewardLocation,brewerJudgeLocation,brewerJudgeExp,brewerJudgeNotes,brewerAssignment");


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

	if (isset($row_brewer_info['brewerAssignment'])) $r .= "^".$row_brewer_info['brewerAssignment'];
	else $r .= "^";

	if ($_SESSION['jPrefsQueued'] == "N") {

		$db_conn->where('bid', $uid);
		$row_judge_info = $db_conn->getOne($prefix."judging_assignments", "assignFlight,assignRound");

		if (!empty($row_judge_info)) $r .= "^".$row_judge_info['assignFlight']."^".$row_judge_info['assignRound'];

	}

	return $r;

}

function flight_entry_count($table_id,$flight): int {
	require(CONFIG.'config.php');
	$db_conn = new MysqliDb($connection);

	$db_conn->where('flightTable', $table_id);
	$db_conn->where('flightNumber', $flight);
	$row_entry_count = $db_conn->getOne($prefix."judging_flights", "COUNT(*) as 'count'");

	return $row_entry_count['count'];

}

function not_assigned(string $method): string {
	require(CONFIG.'config.php');
	$db_conn = new MysqliDb($connection);

	$return = "";
	$assignment = "";

	if ($method === "J") {
		$query_brewer = sprintf("SELECT a.uid, b.uid FROM %s a, %s b WHERE b.staff_judge='1' AND a.uid=b.uid",$prefix."brewer",$prefix."staff");
		$human_readable = "judge";
	}

	if ($method === "S") {
		$query_brewer = sprintf("SELECT a.uid, b.uid FROM %s a, %s b WHERE b.staff_steward='1' AND a.uid=b.uid",$prefix."brewer",$prefix."staff");
		$human_readable = "steward";
	}

	$rows_brewer = $db_conn->rawQuery($query_brewer);
	$totalRows_brewer = $db_conn->count;

	if ($totalRows_brewer > 0) {

		$user[] = "";

		foreach ($rows_brewer as $row_brewer) { $user[] = $row_brewer['uid']; }

		foreach($user as $bid) {

			if ($method === "J") {

				$db_conn->where('bid', $bid);
				$db_conn->where('assignment', 'J');
				$row_assignments = $db_conn->getOne($prefix."judging_assignments", "COUNT(*) as 'count'");

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

			if ($method === "S") {

				$db_conn->where('bid', $bid);
				$db_conn->where('assignment', 'S');
				$row_assignments = $db_conn->getOne($prefix."judging_assignments", "COUNT(*) as 'count'");

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

function virtual_locations(): array {
	
	require(CONFIG.'config.php');
	$db_conn = new MysqliDb($connection);

	$db_conn->where('judgingLocType', 1);
	$rows_virtual_locations = $db_conn->get($prefix."judging_locations", null, "id");

	$return = [];

	foreach ($rows_virtual_locations as $row_virtual_locations) {

		$return[] = [
			'id' => $row_virtual_locations['id'],
			'check' => 'Y-' . $row_virtual_locations['id']
		];

	}

	return $return;
}
?>