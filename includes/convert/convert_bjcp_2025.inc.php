<?php 
/**
 * Get all ids from db of BJCP 2021 and BJCP 2025 styles.
 * Map ids from 2021 to 2025.
 * 2025 update was cider only.
 */

$styles_db_table = $prefix."styles";

$db_conn->where('brewStyleVersion', array('BJCP2021','BJCP2025'), 'in');
$db_conn->orderBy('brewStyleVersion', 'ASC');
$db_conn->orderBy('id', 'ASC');
$rows_style_ids = $db_conn->get($styles_db_table, null, "id, brewStyleGroup, brewStyleNum, brewStyleVersion");

$styles_2021 = array();
$styles_2025 = array();
$mapped_style_ids = array();

if (!isset($output)) $output = "";

foreach ($rows_style_ids as $row_style_ids) {

	$style_num = $row_style_ids['brewStyleGroup'].$row_style_ids['brewStyleNum'];

	if ($row_style_ids['brewStyleVersion'] == "BJCP2021") {
		$styles_2021[$style_num] = $row_style_ids['id'];
	}

	if ($row_style_ids['brewStyleVersion'] == "BJCP2025") {
		$styles_2025[$style_num] = $row_style_ids['id'];
	}

}

foreach ($styles_2021 as $key => $id_2021) {
	// Convert the 2021 style to 2025
	$mapped_style_to_2021 = bjcp_map_2021_2025($key,1,$prefix,1);
	$mapped_style_ids[$id_2021] = $mapped_style_to_2025;
}

/*
print_r($styles_2021);
echo "<br><br>";

print_r($styles_2021);
echo "<br><br>";

print_r($mapped_style_ids);
echo "<br><br>";
*/

/**
 * Update judge likes and dislikes from 2021 to analogous 2025 styles
 */

$db_conn->where("(brewerJudgeLikes IS NOT NULL OR brewerJudgeDislikes IS NOT NULL) OR (brewerJudgeLikes !='' OR brewerJudgeDislikes !='')");
$db_conn->orderBy('id', 'ASC');
$rows_judge_likes = $db_conn->get($prefix."brewer");
$totalRows_judge_likes = $db_conn->count;

if ($totalRows_judge_likes > 0) {

    foreach ($rows_judge_likes as $row_judge_likes) {

        $likes_arr_new = array();
        $dislikes_arr_new = array();
        $likes_new = "";
        $dislikes_new = "";
        
        $current_likes_2021 = array();
        $current_dislikes_2021 = array();
        $bjcp_2025_likes = array();
        $bjcp_2025_dislikes = array();

        if (!empty($row_judge_likes['brewerJudgeLikes'])) {
            $likes_arr = explode(",",$row_judge_likes['brewerJudgeLikes']);
            foreach ($likes_arr as $value) {
                $current_likes_2021[] = array_search($value,$styles_2021);
                if (array_key_exists($value, $mapped_style_ids)) {
                    $new_style_num = $mapped_style_ids[$value];
                    $likes_arr_new[] = $styles_2021[$new_style_num];
                    $bjcp_2025_likes[] = $new_style_num;
                }
            }
        }

        if (!empty($row_judge_likes['brewerJudgeDislikes'])) {
            $dislikes_arr = explode(",",$row_judge_likes['brewerJudgeDislikes']);
            foreach ($dislikes_arr as $value) {
                $current_dislikes_2021[] = array_search($value,$styles_2021);
                if (array_key_exists($value, $mapped_style_ids)) {
                    $new_style_num = $mapped_style_ids[$value];
                    $dislikes_arr_new[] = $styles_2021[$new_style_num];
                    $bjcp_2025_dislikes[] = $new_style_num;
                }
            }
        }

        if (!empty($likes_arr_new)) $likes_new = implode(",",$likes_arr_new);
        if (!empty($dislikes_arr_new)) $dislikes_new = implode(",",$dislikes_arr_new);

        $current_likes = implode(",",$current_likes_2021);
        $current_dislikes = implode(",",$current_dislikes_2021);
        $likes_2015 = implode(",",$bjcp_2025_likes);
        $dislikes_2015 = implode(",",$bjcp_2025_dislikes);
        
        if ((!empty($current_likes)) || (!empty($current_dislikes))) {

            $update_table = $prefix."brewer";
            $data = array(
                'brewerJudgeLikes' => $likes_new,
                'brewerJudgeDislikes' => $dislikes_new
            );
            $db_conn->where ('id', $row_judge_likes['id']);
            if ($db_conn->update ($update_table, $data)) $output .= "<li>Judge likes updated to BJCP 2021 for ".$row_judge_likes['brewerLastName'].", ".$row_judge_likes['brewerFirstName']."</li>";
            else $output .= "<li>Judge likes NOT updated to BJCP 2021 for ".$row_judge_likes['brewerLastName'].", ".$row_judge_likes['brewerFirstName'].". Error: ".$db_conn->getLastError()."</li>";

        }

        /*
        echo $row_judge_likes['brewerJudgeLikes']."<br><br>";
        print_r($current_likes_2021);
        echo "<br>";
        print_r($bjcp_2025_likes);
        echo "<br><br>";            
        print_r($current_dislikes_2021);
        echo "<br>";            
        print_r($bjcp_2025_dislikes);
        echo "<br><br>";
        if (isset($updateSQL)) echo $updateSQL."<br><br>";
        echo "<hr><br><br>";
        */

    }

} // end if ($totalRows_judge_likes > 0)

/**
 * Update defined 2021 styles for any table to 2025
 */

$db_conn->orderBy('id', 'ASC');
$rows_tables = $db_conn->get($prefix."judging_tables");
$totalRows_tables = $db_conn->count;

if ($totalRows_tables > 0) {

    foreach ($rows_tables as $row_tables) {

        $table_styles_arr_new = array();

        if (!empty($row_tables['tableStyles'])) {
            
            $table_styles_arr = explode(",",$row_tables['tableStyles']);
            
            foreach ($table_styles_arr as $value) {
                if (array_key_exists($value, $mapped_style_ids)) {
                    $new_style_num = $mapped_style_ids[$value];
                    $table_styles_arr_new[] = $styles_2021[$new_style_num];
                }
            }

        }

        if (!empty($table_styles_arr_new)) {
            
            $table_styles_new = implode(",",$table_styles_arr_new);

            $update_table = $prefix."judging_tables";
            $data = array('tableStyles' => $table_styles_new);
            $db_conn->where ('id', $row_tables['id']);
            if ($db_conn->update ($update_table, $data)) $output .= "<li>Table styles updated to BJCP 2025 for ".$row_tables['tableName']."</li>";
            else $output .= "<li>Judge likes NOT updated to BJCP 2025  for ".$row_tables['tableName'].". Error: ".$db_conn->getLastError()."</li>";

        }
        
        /*
        echo $row_tables['tableName']."<br>";
        print_r($table_styles_arr);
        echo "<br>";
        print_r($table_styles_arr_new);
        echo "<br>";
        echo $updateSQL."<br><br><hr><br><br>";
        */

    }

} // end if ($totalRows_tables > 0)

/**
 * Update any 2025 styles in the styles table as active if
 * 2021 counterpart was active as well.
 */

$db_conn->where('brewStyleVersion', 'BJCP2021');
$db_conn->where('brewStyleActive', 'Y');
$rows_styles_active = $db_conn->get($styles_db_table);
$totalRows_styles_active = $db_conn->count;

if ($totalRows_styles_active > 0) {

    // First, "deselect" all styles in the DB for BJCP2025
    $update_table = $prefix."styles";
    $data = array('brewStyleActive' => 'N');
    $db_conn->where ('brewStyleVersion', 'BJCP2025');
    $db_conn->update ($update_table, $data);

    if (HOSTED) {
        $update_table = $styles_db_table;
        $data = array('brewStyleActive' => 'N');
        $db_conn->where ('brewStyleVersion', 'BJCP2025');
        $result = $db_conn->update ($update_table, $data);
    }

    foreach ($rows_styles_active as $row_styles_active) {

        $style = $row_styles_active['brewStyleGroup'].$row_styles_active['brewStyleNum'];

        if (in_array($style, $mapped_style_ids)) {
            
            $key = array_search($style, $mapped_style_ids);
            $new_style_num = $mapped_style_ids[$key];
            $id = $styles_2021[$new_style_num];

            $update_table = $prefix."styles";
            $data = array('brewStyleActive' => 'Y');
            $db_conn->where ('id', $id);
            $result = $db_conn->update ($update_table, $data);

            if (HOSTED) {
                $update_table = $styles_db_table;
                $data = array('brewStyleActive' => 'Y');
                $db_conn->where ('id', $id);
                $result = $db_conn->update ($update_table, $data);
            }

        }

    }

} // end if ($totalRows_styles_active > 0)

/**
 * Update any entries in the brewing table to analogous 2025 styles
 */

$db_conn->orderBy('brewCategorySort', 'ASC');
$db_conn->orderBy('brewSubCategory', 'ASC');
$rows_brews = $db_conn->get($prefix."brewing", null, "id,brewName,brewCategory,brewCategorySort,brewSubCategory,brewStyle");
$totalRows_brews = $db_conn->count;

$current_active = array();

if ($totalRows_brews > 0) {

	foreach ($rows_brews as $row_brews) {

		$style = $row_brews['brewCategorySort'].$row_brews['brewSubCategory'];
        $sql = "";
        $sql .= bjcp_map_2021_2025($style,0,$prefix,$row_brews['id']);
        if (!empty($sql)) {
            $current_active[] = bjcp_map_2021_2025($style,2,$prefix,$row_brews['id']);
            $result = $db_conn->rawQuery($sql);
        }

	}

} // end if ($totalRows_brews > 0)

// Activate all styles that have been converted.
// Failsafe just in case comp converts during entry window.

if (!empty($current_active)) {

    $update_table = $prefix."styles";

    foreach($current_active as $value) {

        $style_parts = explode("-", $value);
        $data = array('brewStyleActive' => 'Y');
        $db_conn->where ('brewStyleGroup', $style_parts[0]);
        $db_conn->where ('brewStyleNum', $style_parts[1]);
        $db_conn->update ($update_table, $data);

    }

}

$output .= "<ul>";

// Update all custom styles
$update_table = $prefix."styles";
$data = array('brewStyleVersion' => 'BJCP2025');
$db_conn->where ('brewStyleOwn', NULL, 'IS');
$db_conn->orWhere ('brewStyleOwn', 'custom');
if ($db_conn->update ($update_table, $data)) $output .= "<li>Custom styles updated to BJCP 2025.</li>";
else $output .= "Custom styles NOT updated to BJCP 2025. <li>Error: ".$db_conn->getLastError()."</li>";

$update_table = $prefix."preferences";
$data = array('prefsStyleSet' => 'BJCP2025');
$db_conn->where ('id', 1);
if ($db_conn->update ($update_table, $data)) $output .= "<li>Preferences set to BJCP 2025.</li>";
else $output .= "<li>Preferences NOT set to BJCP 2025. Error: ".$db_conn->getLastError()."</li>";

$output .= "</ul>";

unset($_SESSION['prefs'.$prefix_session]);
?>