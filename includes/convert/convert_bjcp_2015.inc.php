<?php 
declare(strict_types=1);
/**
 * Get all ids from db of BJCP 2008 and BJCP 2015 styles
 * Map ids from 2008 to 2015
 */

/*
if (HOSTED) $styles_db_table = "bcoem_shared_styles";
else
*/
$styles_db_table = $prefix."styles";

/*
if (HOSTED) $query_style_ids = sprintf("SELECT id,brewStyleGroup,brewStyleNum,brewStyleVersion FROM `%s` WHERE brewStyleVersion='BJCP2008' OR brewStyleVersion='BJCP2015' UNION ALL SELECT id,brewStyleGroup,brewStyleNum,brewStyleVersion FROM `%s` WHERE brewStyleVersion='BJCP2008' OR brewStyleVersion='BJCP2015' ORDER BY brewStyleVersion,id ASC", $styles_db_table, $prefix."styles");
else 
*/
$db_conn->where('brewStyleVersion', array('BJCP2008','BJCP2015'), 'in');
$db_conn->orderBy('brewStyleVersion', 'ASC');
$db_conn->orderBy('id', 'ASC');
$rows_style_ids = $db_conn->get($styles_db_table, null, "id,brewStyleGroup,brewStyleNum,brewStyleVersion");

$styles_2008 = array();
$styles_2015 = array();
$mapped_style_ids = array();

if (!isset($output)) $output = "";

foreach ($rows_style_ids as $row_style_ids) {

    $style_num = $row_style_ids['brewStyleGroup'].$row_style_ids['brewStyleNum'];

    if ($row_style_ids['brewStyleVersion'] == "BJCP2008") {
        $styles_2008[$style_num] = $row_style_ids['id'];
    }

    if ($row_style_ids['brewStyleVersion'] == "BJCP2015") {
        $styles_2015[$style_num] = $row_style_ids['id'];
    }

}

// Map ids from 2008 to 2015
foreach ($styles_2008 as $key => $id_2008) {
    // Convert the 2008 style to 2015
    $mapped_style_to_2015 = bjcp_map_2008_2015($key,1,$prefix,1);
    $mapped_style_ids[$id_2008] = $mapped_style_to_2015;
}

/*
print_r($styles_2008);
echo "<br><br>";

print_r($styles_2015);
echo "<br><br>";

print_r($mapped_style_ids);
echo "<br><br>";
*/

/**
 * Look up particpants that have judging likes and dislikes
 * Break up their like and dislike lists into an arrays
 * Loop through like and dislike arrays and compile new mapped ids
 * Implode and update db column
 */

$db_conn->where('(brewerJudgeLikes IS NOT NULL OR brewerJudgeDislikes IS NOT NULL)');
$db_conn->orderBy('id', 'ASC');
$rows_judge_likes = $db_conn->get($prefix."brewer");
$totalRows_judge_likes = $db_conn->count;

if ($totalRows_judge_likes > 0) {

    foreach ($rows_judge_likes as $row_judge_likes) {

        $likes_arr_new = array();
        $dislikes_arr_new = array();
        $likes_new = "";
        $dislikes_new = "";
        
        $current_likes_2008 = array();
        $current_dislikes_2008 = array();
        $bjcp_2015_likes = array();
        $bjcp_2015_dislikes = array();

        if (!empty($row_judge_likes['brewerJudgeLikes'])) {
            $likes_arr = explode(",",$row_judge_likes['brewerJudgeLikes']);
            foreach ($likes_arr as $value) {
                
                $current_likes_2008[] = array_search($value,$styles_2008);
                
                if (array_key_exists($value, $mapped_style_ids)) {
                    $new_style_num = $mapped_style_ids[$value];
                    $likes_arr_new[] = $styles_2015[$new_style_num];
                    $bjcp_2015_likes[] = $new_style_num;
                }
            }
        }

        if (!empty($row_judge_likes['brewerJudgeDisLikes'])) {
            $dislikes_arr = explode(",",$row_judge_likes['brewerJudgeDisLikes']);
            foreach ($dislikes_arr as $value) {
                
                $current_dislikes_2008[] = array_search($value,$styles_2008);
                
                if (array_key_exists($value, $mapped_style_ids)) {
                    $new_style_num = $mapped_style_ids[$value];
                    $dislikes_arr_new[] = $styles_2015[$new_style_num];
                    $bjcp_2015_dislikes[] = $new_style_num;
                }
            }
        }

        if (!empty($likes_arr_new)) $likes_new = implode(",",$likes_arr_new);
        if (!empty($dislikes_arr_new)) $dislikes_new = implode(",",$dislikes_arr_new);
        
        $current_likes = implode(",",$current_likes_2008);
        $current_dislikes = implode(",",$current_dislikes_2008);
        $likes_2015 = implode(",",$bjcp_2015_likes);
        $dislikes_2015 = implode(",",$bjcp_2015_dislikes);

        /*
        print_r($current_likes);
        echo "<br><br>";
        
        print_r($likes_2015);
        echo "<br><br>";
        
        print_r($current_dislikes);
        echo "<br><br>";
        
        print_r($dislikes_2015);
        echo "<br><br>";
        */

        $update_table = $prefix."brewer";
        $data = array(
            'brewerJudgeLikes' => $likes_new,
            'brewerJudgeDislikes' => $dislikes_new
        );
        $db_conn->where ('id', $row_judge_likes['id']);
        if ($db_conn->update ($update_table, $data)) $output_run_update .= "<li>Judge likes updated to BJCP 2015 for ".$row_judge_likes['brewerLastName'].", ".$row_judge_likes['brewerFirstName']."</li>";
        else $output_run_update .= "<li>Judge likes NOT updated to BJCP 2015 for ".$row_judge_likes['brewerLastName'].", ".$row_judge_likes['brewerFirstName'].". Error: ".$db_conn->getLastError()."</li>";

    }

} // end if ($totalRows_judge_likes > 0)


/**
 * Update defined 2008 styles for any table to 2015
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
                    $table_styles_arr_new[] = $styles_2015[$new_style_num];
                }
            }
        }

        if (!empty($table_styles_arr_new)) {
            
            $table_styles_new = implode(",",$table_styles_arr_new);
            
            $update_table = $prefix."judging_tables";
            $data = array('tableStyles' => $table_styles_new);
            $db_conn->where ('id', $row_tables['id']);
            if ($db_conn->update ($update_table, $data)) $output_run_update .= "<li>Table styles updated to BJCP 2015 for ".$row_tables['tableName']."</li>";
            else $output_run_update .= "<li>Judge likes NOT updated to BJCP 2015  for ".$row_tables['tableName'].". Error: ".$db_conn->getLastError()."</li>";

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
 * Update any 2015 styles in the styles table as active if
 * 2008 counterpart was active as well.
 */

/*
if (HOSTED) $query_styles_active = sprintf("SELECT * FROM %s WHERE brewStyleVersion='BJCP2008' AND brewStyleActive='Y' UNION ALL SELECT * FROM %s WHERE brewStyleVersion='BJCP2008' AND brewStyleActive='Y'", $styles_db_table, $prefix."styles");
else 
*/
$db_conn->where('brewStyleVersion', 'BJCP2008');
$db_conn->where('brewStyleActive', 'Y');
$rows_styles_active = $db_conn->get($styles_db_table);
$totalRows_styles_active = $db_conn->count;

if ($totalRows_styles_active > 0) {

    // First, "deselect" all styles in the DB for BJCP2015
    $update_table = $prefix."styles";
    $data = array('brewStyleActive' => 'N');
    $db_conn->where ('brewStyleVersion', 'BJCP2015');
    $result = $db_conn->update ($update_table, $data);

    if (HOSTED) {
        $update_table = $styles_db_table;
        $data = array('brewStyleActive' => 'N');
        $db_conn->where ('brewStyleVersion', 'BJCP2015');
        $result = $db_conn->update ($update_table, $data);
    }

    foreach ($rows_styles_active as $row_styles_active) {

        $style = $row_styles_active['brewStyleGroup'].$row_styles_active['brewStyleNum'];

        if (in_array($style, $mapped_style_ids)) {
            
            $key = array_search($style, $mapped_style_ids);
            $new_style_num = $mapped_style_ids[$key];
            $id = $styles_2015[$new_style_num];

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

        /*
        echo $style."<br>";
        if (isset($updateSQL)) echo $updateSQL."<br><br>";
        */

    }

} // end if ($totalRows_styles_active > 0)

$db_conn->orderBy('brewCategorySort', 'ASC');
$db_conn->orderBy('brewSubCategory', 'ASC');
$rows_brews = $db_conn->get($prefix."brewing", null, "id,brewName,brewCategory,brewCategorySort,brewSubCategory,brewStyle");
$totalRows_brews = $db_conn->count;

if ($totalRows_brews > 0) {

    // Loop through entries and convert to 2015 styles
    foreach ($rows_brews as $row_brews) {

        $style = $row_brews['brewCategorySort'].$row_brews['brewSubCategory'];
        $sql = "";
        $sql .= bjcp_map_2008_2015($style,0,$prefix,$row_brews['id']);
        if (!empty($sql)) $result = $db_conn->rawQuery($sql);

    }

} //end if ($totalRows_brews > 0)

// Update all custom styles
$update_table = $prefix."styles";
$data = array('brewStyleVersion' => 'BJCP2015');
$db_conn->where ('brewStyleOwn', NULL, 'IS');
$db_conn->orWhere ('brewStyleOwn', 'custom');
$result = $db_conn->update ($update_table, $data);
?>