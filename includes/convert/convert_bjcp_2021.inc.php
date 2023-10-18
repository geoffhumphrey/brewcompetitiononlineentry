<?php 
/**
 * Get all ids from db of BJCP 2015 and BJCP 2021 styles.
 * Map ids from 2015 to 2021.
 */

/*
if (HOSTED) $styles_db_table = "bcoem_shared_styles";
else
*/
$styles_db_table = $prefix."styles";

/*
if (HOSTED) $query_style_ids = sprintf("SELECT id,brewStyleGroup,brewStyleNum,brewStyleVersion FROM `%s` WHERE brewStyleVersion='BJCP2015' OR brewStyleVersion='BJCP2021' UNION ALL SELECT id,brewStyleGroup,brewStyleNum,brewStyleVersion FROM `%s` WHERE brewStyleVersion='BJCP2015' OR brewStyleVersion='BJCP2021' ORDER BY brewStyleVersion,id ASC", $styles_db_table, $prefix."styles");
else 
*/ 
$query_style_ids = sprintf("SELECT id, brewStyleGroup, brewStyleNum, brewStyleVersion FROM %s WHERE brewStyleVersion='BJCP2015' OR brewStyleVersion='BJCP2021' ORDER BY brewStyleVersion, id ASC", $styles_db_table);
$style_ids = mysqli_query($connection,$query_style_ids) or die (mysqli_error($connection));
$row_style_ids = mysqli_fetch_assoc($style_ids);

$styles_2015 = array();
$styles_2021 = array();
$mapped_style_ids = array();

if (!isset($output)) $output = "";

do {

	$style_num = $row_style_ids['brewStyleGroup'].$row_style_ids['brewStyleNum'];

	if ($row_style_ids['brewStyleVersion'] == "BJCP2015") {
		$styles_2015[$style_num] = $row_style_ids['id'];
	}
	
	if ($row_style_ids['brewStyleVersion'] == "BJCP2021") {
		$styles_2021[$style_num] = $row_style_ids['id'];
	}		

} while($row_style_ids = mysqli_fetch_assoc($style_ids));

foreach ($styles_2015 as $key => $id_2015) {
	// Convert the 2015 style to 2021
	$mapped_style_to_2021 = bjcp_map_2015_2021($key,1,$prefix,1);
	$mapped_style_ids[$id_2015] = $mapped_style_to_2021;
}

/*
print_r($styles_2015);
echo "<br><br>";

print_r($styles_2021);
echo "<br><br>";

print_r($mapped_style_ids);
echo "<br><br>";
*/

/**
 * Update judge likes and dislikes from 2015 to analogous 2021 styles
 */

$query_judge_likes = sprintf("SELECT * FROM %s WHERE (brewerJudgeLikes IS NOT NULL OR brewerJudgeDislikes IS NOT NULL) OR (brewerJudgeLikes !='' OR brewerJudgeDislikes !='') ORDER BY id ASC", $prefix."brewer");
$judge_likes = mysqli_query($connection,$query_judge_likes) or die (mysqli_error($connection));
$row_judge_likes = mysqli_fetch_assoc($judge_likes);
$totalRows_judge_likes = mysqli_num_rows($judge_likes);

if ($totalRows_judge_likes > 0) {

    do {

        $likes_arr_new = array();
        $dislikes_arr_new = array();
        $likes_new = "";
        $dislikes_new = "";
        
        $current_likes_2015 = array();
        $current_dislikes_2015 = array();
        $bjcp_2021_likes = array();
        $bjcp_2021_dislikes = array();

        if (!empty($row_judge_likes['brewerJudgeLikes'])) {
            $likes_arr = explode(",",$row_judge_likes['brewerJudgeLikes']);
            foreach ($likes_arr as $value) {
                $current_likes_2015[] = array_search($value,$styles_2015);
                if (array_key_exists($value, $mapped_style_ids)) {
                    $new_style_num = $mapped_style_ids[$value];
                    $likes_arr_new[] = $styles_2021[$new_style_num];
                    $bjcp_2021_likes[] = $new_style_num;
                }
            }
        }

        if (!empty($row_judge_likes['brewerJudgeDislikes'])) {
            $dislikes_arr = explode(",",$row_judge_likes['brewerJudgeDislikes']);
            foreach ($dislikes_arr as $value) {
                $current_dislikes_2015[] = array_search($value,$styles_2015);
                if (array_key_exists($value, $mapped_style_ids)) {
                    $new_style_num = $mapped_style_ids[$value];
                    $dislikes_arr_new[] = $styles_2021[$new_style_num];
                    $bjcp_2021_dislikes[] = $new_style_num;
                }
            }
        }

        if (!empty($likes_arr_new)) $likes_new = implode(",",$likes_arr_new);
        if (!empty($dislikes_arr_new)) $dislikes_new = implode(",",$dislikes_arr_new);

        $current_likes = implode(",",$current_likes_2015);
        $current_dislikes = implode(",",$current_dislikes_2015);
        $likes_2015 = implode(",",$bjcp_2021_likes);
        $dislikes_2015 = implode(",",$bjcp_2021_dislikes);
        
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
        print_r($current_likes_2015);
        echo "<br>";
        print_r($bjcp_2021_likes);
        echo "<br><br>";            
        print_r($current_dislikes_2015);
        echo "<br>";            
        print_r($bjcp_2021_dislikes);
        echo "<br><br>";
        if (isset($updateSQL)) echo $updateSQL."<br><br>";
        echo "<hr><br><br>";   
        */

    } while($row_judge_likes = mysqli_fetch_assoc($judge_likes));

} // end if ($totalRows_judge_likes > 0)

/**
 * Update defined 2015 styles for any table to 2021
 */

$query_tables = sprintf("SELECT * FROM %s ORDER BY id ASC", $prefix."judging_tables");
$tables = mysqli_query($connection,$query_tables) or die (mysqli_error($connection));
$row_tables = mysqli_fetch_assoc($tables);
$totalRows_tables = mysqli_num_rows($tables);

if ($totalRows_tables > 0) {

    do {

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
            if ($db_conn->update ($update_table, $data)) $output .= "<li>Table styles updated to BJCP 2021 for ".$row_tables['tableName']."</li>";
            else $output .= "<li>Judge likes NOT updated to BJCP 2021  for ".$row_tables['tableName'].". Error: ".$db_conn->getLastError()."</li>";

        }
        
        /*
        echo $row_tables['tableName']."<br>";
        print_r($table_styles_arr);
        echo "<br>";
        print_r($table_styles_arr_new);
        echo "<br>";
        echo $updateSQL."<br><br><hr><br><br>";
        */

    } while ($row_tables = mysqli_fetch_assoc($tables));

} // end if ($totalRows_tables > 0)

/**
 * Update any 2021 styles in the styles table as active if
 * 2015 counterpart was active as well.
 */

/*
if (HOSTED) $query_styles_active = sprintf("SELECT * FROM %s WHERE brewStyleVersion='BJCP2015' AND brewStyleActive='Y' UNION ALL SELECT * FROM %s WHERE brewStyleVersion='BJCP2015' AND brewStyleActive='Y'", $styles_db_table, $prefix."styles");
else 
*/
$query_styles_active = sprintf("SELECT * FROM %s WHERE brewStyleVersion='BJCP2015' AND brewStyleActive='Y'", $styles_db_table);
$styles_active = mysqli_query($connection,$query_styles_active) or die (mysqli_error($connection));
$row_styles_active = mysqli_fetch_assoc($styles_active);
$totalRows_styles_active = mysqli_num_rows($styles_active);

if ($totalRows_styles_active > 0) {

    // First, "deselect" all styles in the DB for BJCP2021
    $update_table = $prefix."styles";
    $data = array('brewStyleActive' => 'N');
    $db_conn->where ('brewStyleVersion', 'BJCP2021');
    $db_conn->update ($update_table, $data);

    if (HOSTED) {
        $update_table = $styles_db_table;
        $data = array('brewStyleActive' => 'N');
        $db_conn->where ('brewStyleVersion', 'BJCP2021');
        $result = $db_conn->update ($update_table, $data);
    }

    do {

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

    } while ($row_styles_active = mysqli_fetch_assoc($styles_active));

} // end if ($totalRows_styles_active > 0)

/**
 * Update any entries in the brewing table to analogous 2021 styles
 */

$query_brews = sprintf("SELECT id,brewName,brewCategory,brewCategorySort,brewSubCategory,brewStyle FROM %s ORDER BY brewCategorySort,brewSubCategory", $prefix."brewing");
$brews = mysqli_query($connection,$query_brews) or die (mysqli_error($connection));
$row_brews = mysqli_fetch_assoc($brews);
$totalRows_brews = mysqli_num_rows($brews);

$current_active = array();

if ($totalRows_brews > 0) {

	do {

		$style = $row_brews['brewCategorySort'].$row_brews['brewSubCategory'];
        $sql = "";
        $sql .= bjcp_map_2015_2021($style,0,$prefix,$row_brews['id']);
        if (!empty($sql)) {
            $current_active[] = bjcp_map_2015_2021($style,2,$prefix,$row_brews['id']);
            $result = $db_conn->rawQuery($sql);
        }

	} while ($row_brews = mysqli_fetch_assoc($brews));
	
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

// Update all custom styles
$update_table = $prefix."styles";
$data = array('brewStyleVersion' => 'BJCP2021');
$db_conn->where ('brewStyleOwn', NULL, 'IS');
$db_conn->orWhere ('brewStyleOwn', 'custom');
if ($db_conn->update ($update_table, $data)) $output .= "<li>Custom styles updated to BJCP 2021.</li>";
else $output .= "Custom styles NOT updated to BJCP 2021. <li>Error: ".$db_conn->getLastError()."</li>";

$update_table = $prefix."preferences";
$data = array('prefsStyleSet' => 'BJCP2021');
$db_conn->where ('id', 1);
if ($db_conn->update ($update_table, $data)) $output .= "<li>Preferences set to BJCP 2021.</li>";
else $output .= "<li>Preferences NOT set to BJCP 2021. Error: ".$db_conn->getLastError()."</li>";

unset($_SESSION['prefs'.$prefix_session]);
?>