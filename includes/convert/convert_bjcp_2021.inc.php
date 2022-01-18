<?php 
/**
 * Get all ids from db of BJCP 2015 and BJCP 2021 styles.
 * Map ids from 2015 to 2021.
 */

$query_style_ids = sprintf("SELECT id,brewStyleGroup,brewStyleNum,brewStyleVersion FROM %s WHERE brewStyleVersion='BJCP2015' OR brewStyleVersion='BJCP2021' ORDER BY brewStyleVersion,id ASC", $prefix."styles");
$style_ids = mysqli_query($connection,$query_style_ids) or die (mysqli_error($connection));
$row_style_ids = mysqli_fetch_assoc($style_ids);

$styles_2015 = array();
$styles_2021 = array();
$mapped_style_ids = array();

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

$query_judge_likes = sprintf("SELECT id, brewerJudgeLikes, brewerJudgeDislikes FROM %s WHERE (brewerJudgeLikes IS NOT NULL OR brewerJudgeDislikes IS NOT NULL) OR (brewerJudgeLikes !='' OR brewerJudgeDislikes !='') ORDER BY id ASC", $prefix."brewer");
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
            $updateSQL = sprintf("UPDATE %s SET brewerJudgeLikes='%s', brewerJudgeDislikes='%s' WHERE id='%s'", $prefix."brewer", $likes_new, $dislikes_new, $row_judge_likes['id']);
            mysqli_select_db($connection,$database);
            $result = mysqli_query($connection,$updateSQL) or die (mysqli_error($connection));
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

$query_tables = sprintf("SELECT id, tableStyles, tableName FROM %s ORDER BY id ASC", $prefix."judging_tables");
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
            $updateSQL = sprintf("UPDATE %s SET tableStyles='%s' WHERE id='%s'", $prefix."judging_tables", $table_styles_new, $row_tables['id']);
            mysqli_select_db($connection,$database);
            $result = mysqli_query($connection,$updateSQL) or die (mysqli_error($connection));
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

$query_styles_active = sprintf("SELECT id,brewStyleGroup,brewStyleNum FROM %s WHERE brewStyleVersion='BJCP2015' AND brewStyleActive='Y'", $prefix."styles");
$styles_active = mysqli_query($connection,$query_styles_active) or die (mysqli_error($connection));
$row_styles_active = mysqli_fetch_assoc($styles_active);
$totalRows_styles_active = mysqli_num_rows($styles_active);

if ($totalRows_styles_active > 0) {

    // First, "deselect" all styles in the DB for BJCP2021
    $updateSQL = sprintf("UPDATE %s SET brewStyleActive='N' WHERE brewStyleVersion='BJCP2021'",$prefix."styles");
    mysqli_select_db($connection,$database);
    $result = mysqli_query($connection,$updateSQL) or die (mysqli_error($connection));

    // echo $updateSQL."<br><br>";

    do {

        $style = $row_styles_active['brewStyleGroup'].$row_styles_active['brewStyleNum'];

        if (in_array($style, $mapped_style_ids)) {
            $key = array_search($style, $mapped_style_ids);
            $new_style_num = $mapped_style_ids[$key];
            $id = $styles_2021[$new_style_num];
            $updateSQL = sprintf("UPDATE %s SET brewStyleActive='Y' WHERE id='%s'",$prefix."styles",$id);
            mysqli_select_db($connection,$database);
            $result = mysqli_query($connection,$updateSQL) or die (mysqli_error($connection));
        }

        /*
        echo $style."<br>";
        if (isset($updateSQL)) echo $updateSQL."<br><br>";
        */

    } while($row_styles_active = mysqli_fetch_assoc($styles_active));

} // end if ($totalRows_styles_active > 0)




/**
 * Update any entries in the brewing table to analogous 2021 styles
 */

$query_brews = sprintf("SELECT id,brewName,brewCategory,brewCategorySort,brewSubCategory,brewStyle FROM %s ORDER BY brewCategorySort,brewSubCategory", $prefix."brewing");
$brews = mysqli_query($connection,$query_brews) or die (mysqli_error($connection));
$row_brews = mysqli_fetch_assoc($brews);
$totalRows_brews = mysqli_num_rows($brews);

if ($totalRows_brews > 0) {

	do {

		$style = $row_brews['brewCategorySort'].$row_brews['brewSubCategory'];

		$updateSQL = "";
		$updateSQL = bjcp_map_2015_2021($style,0,$prefix,$row_brews['id']);

		if (!empty($updateSQL)) {
			// echo $updateSQL."<br>";
			mysqli_select_db($connection,$database);
			$result = mysqli_query($connection,$updateSQL) or die (mysqli_error($connection));
		}

	} while ($row_brews = mysqli_fetch_assoc($brews));
	
} // end if ($totalRows_brews > 0)

// Update all custom styles
$updateSQL = sprintf("UPDATE %s SET brewStyleVersion = 'BJCP2021' WHERE brewStyleOwn='custom' OR brewStyleOwn IS NULL",$prefix."styles");
mysqli_select_db($connection,$database);
$result = mysqli_query($connection,$updateSQL) or die (mysqli_error($connection));

$updateSQL = sprintf("UPDATE %s SET prefsStyleSet='%s' WHERE id='%s'",$prefix."preferences","BJCP2021","1");
mysqli_real_escape_string($connection,$updateSQL);
$result = mysqli_query($connection,$updateSQL) or die (mysqli_error($connection));

unset($_SESSION['prefs'.$prefix_session]);
?>