<?php 
/**
 * Get all ids from db of BJCP 2008 and BJCP 2015 styles
 * Map ids from 2008 to 2015
 */

$query_style_ids = sprintf("SELECT id,brewStyleGroup,brewStyleNum,brewStyleVersion FROM %s WHERE brewStyleVersion='BJCP2008' OR brewStyleVersion='BJCP2015' ORDER BY brewStyleVersion,id ASC", $prefix."styles");
$style_ids = mysqli_query($connection,$query_style_ids) or die (mysqli_error($connection));
$row_style_ids = mysqli_fetch_assoc($style_ids);

$styles_2008 = array();
$styles_2015 = array();
$mapped_style_ids = array();

do {

	$style_num = $row_style_ids['brewStyleGroup'].$row_style_ids['brewStyleNum'];
	
	if ($row_style_ids['brewStyleVersion'] == "BJCP2008") {
		$styles_2008[$style_num] = $row_style_ids['id'];
	}
	
	if ($row_style_ids['brewStyleVersion'] == "BJCP2015") {
		$styles_2015[$style_num] = $row_style_ids['id'];
	}

} while($row_style_ids = mysqli_fetch_assoc($style_ids));

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

$query_judge_likes = sprintf("SELECT id, brewerJudgeLikes, brewerJudgeDislikes FROM %s WHERE (brewerJudgeLikes IS NOT NULL OR brewerJudgeDislikes IS NOT NULL) ORDER BY id ASC", $prefix."brewer");
$judge_likes = mysqli_query($connection,$query_judge_likes) or die (mysqli_error($connection));
$row_judge_likes = mysqli_fetch_assoc($judge_likes);
$totalRows_judge_likes = mysqli_num_rows($judge_likes);

if ($totalRows_judge_likes > 0) {

	do {

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
		

		$updateSQL = sprintf("UPDATE %s SET brewerJudgeLikes='%s', brewerJudgeDislikes='%s' WHERE id='%s'", $prefix."brewer", $likes_new, $dislikes_new, $row_judge_likes['id']);
		mysqli_select_db($connection,$database);
		$result = mysqli_query($connection,$updateSQL) or die (mysqli_error($connection));

		// echo $updateSQL."<br>";

	} while($row_judge_likes = mysqli_fetch_assoc($judge_likes));

} // end if ($totalRows_judge_likes > 0)




/**
 * Update defined 2008 styles for any table to 2015
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
                    $table_styles_arr_new[] = $styles_2015[$new_style_num];
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
 * Update any 2015 styles in the styles table as active if
 * 2008 counterpart was active as well.
 */

$query_styles_active = sprintf("SELECT id,brewStyleGroup,brewStyleNum FROM %s WHERE brewStyleVersion='BJCP2008' AND brewStyleActive='Y'", $prefix."styles");
$styles_active = mysqli_query($connection,$query_styles_active) or die (mysqli_error($connection));
$row_styles_active = mysqli_fetch_assoc($styles_active);
$totalRows_styles_active = mysqli_num_rows($styles_active);

if ($totalRows_styles_active > 0) {

    // First, "deselect" all styles in the DB for BJCP2015
    $updateSQL = sprintf("UPDATE %s SET brewStyleActive='N' WHERE brewStyleVersion='BJCP2015'",$prefix."styles");
    mysqli_select_db($connection,$database);
    $result = mysqli_query($connection,$updateSQL) or die (mysqli_error($connection));

    //echo $updateSQL."<br><br>";

    do {

        $style = $row_styles_active['brewStyleGroup'].$row_styles_active['brewStyleNum'];

        if (in_array($style, $mapped_style_ids)) {
            $key = array_search($style, $mapped_style_ids);
            $new_style_num = $mapped_style_ids[$key];
            $id = $styles_2015[$new_style_num];
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




$query_brews = sprintf("SELECT id,brewName,brewCategory,brewCategorySort,brewSubCategory,brewStyle FROM %s ORDER BY brewCategorySort,brewSubCategory", $prefix."brewing");
$brews = mysqli_query($connection,$query_brews) or die (mysqli_error($connection));
$row_brews = mysqli_fetch_assoc($brews);
$totalRows_brews = mysqli_num_rows($brews);

if ($totalRows_brews > 0) {

	// Loop through entries and convert to 2015 styles
	do {

		$style = $row_brews['brewCategorySort'].$row_brews['brewSubCategory'];
		$updateSQL = "";
		$updateSQL = bjcp_map_2008_2015($style,0,$prefix,$row_brews['id']);

		if (!empty($updateSQL)) {
			mysqli_select_db($connection,$database);
			$result = mysqli_query($connection,$updateSQL) or die (mysqli_error($connection));
		}

	} while ($row_brews = mysqli_fetch_assoc($brews));

} //end if ($totalRows_brews > 0)

// Update all custom styles
$updateSQL = sprintf("UPDATE %s SET brewStyleVersion = 'BJCP2015' WHERE brewStyleOwn='custom' OR brewStyleOwn IS NULL",$prefix."styles");
mysqli_select_db($connection,$database);
$result = mysqli_query($connection,$updateSQL) or die (mysqli_error($connection));

?>