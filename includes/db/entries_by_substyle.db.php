<?php
$count_beer = TRUE;
$count_mead = FALSE;
$count_mead_cider = FALSE;
$count_cider = FALSE;
$other_count = FALSE;

$substyle_count = mysqli_query($connection,$query_substyle_count) or die (mysqli_error($connection));
$row_substyle_count = mysqli_fetch_assoc($substyle_count);

$substyle_count_logged = mysqli_query($connection,$query_substyle_count_logged) or die (mysqli_error($connection));
$row_substyle_count_logged = mysqli_fetch_assoc($substyle_count_logged);

/*
if (HOSTED) $styles_db_table = "bcoem_shared_styles";
else $styles_db_table = $prefix."styles";
*/

if (($row_substyle_count_logged > 0) || ($row_substyle_count > 0)) {

	$substyle_cat_num = ltrim($substyle[0],"0");

	if ($_SESSION['prefsStyleSet'] == "BA") {

		if ($substyle_cat_num != 12) {
			$count_mead_cider = FALSE;
			$count_beer = TRUE;
		}

		else {
			$count_mead_cider = TRUE;
			$count_beer = FALSE;
		}

	}

	else {

		if ($substyle_cat_num <= $beer_end) {
			$count_beer = TRUE;
			$count_mead = FALSE;
			$count_cider = FALSE;
			$other_count = FALSE;
		}

		if (in_array($substyle_cat_num,$mead_array)) {
			$count_mead = TRUE;
			$count_beer = FALSE;
			$count_cider = FALSE;
			$other_count = FALSE;
		}

		if (in_array($substyle_cat_num,$cider_array)) {
			$count_cider = TRUE;
			$count_beer = FALSE;
			$count_mead = FALSE;
			$other_count = FALSE;
		}

		if ($substyle_cat_num > $category_end) {

			/*
			if (HOSTED) $query_style_type = sprintf("SELECT brewStyleType FROM %s WHERE brewStyleGroup='%s' UNION ALL SELECT brewStyleType FROM %s WHERE brewStyleGroup='%s'", $styles_db_table, $substyle_cat_num, $prefix."styles", $substyle_cat_num);
			else 
			*/
			$query_style_type = sprintf("SELECT brewStyleType FROM %s WHERE brewStyleGroup='%s'", $styles_db_table, $substyle_cat_num);
			$style_type = mysqli_query($connection,$query_style_type) or die (mysqli_error($connection));
			$row_style_type = mysqli_fetch_assoc($style_type);
			$count_cider = FALSE;
			$count_beer = FALSE;
			$count_mead = FALSE;
			$other_count = TRUE;

		}

	}

	if ($count_beer) {

		$style_type = "Beer";
		$style_beer_count[] .= $row_substyle_count['count'];
		$style_beer_count_logged[] .= $row_substyle_count_logged['count'];

	}


	if ($_SESSION['prefsStyleSet'] == "BA") {

		if ($count_mead_cider)  {

			$style_type = "Mead/Cider";
			$style_mead_cider_count[] .= $row_substyle_count['count'];
			$style_mead_cider_count_logged[] .= $row_substyle_count_logged['count'];

		}

	}

	else {

		if ($count_mead) {

			$style_type = "Mead";
			$style_mead_count[] .= $row_substyle_count['count'];
			$style_mead_count_logged[] .= $row_substyle_count_logged['count'];

		}

		if ($count_cider)  {

			$style_type = "Cider";
			$style_cider_count[] .= $row_substyle_count['count'];
			$style_cider_count_logged[] .= $row_substyle_count_logged['count'];

		}

	}

	if ($other_count) {

		if ((!empty($row_style_type['brewStyleType'])) && ($row_style_type['brewStyleType'] <= 3)) $source = "bcoe";
		else  $source = "custom";

		if (empty($row_style_type['brewStyleType'])) $style_type = "other";
		else $style_type = style_type($row_style_type['brewStyleType'],"2",$source);

		if ($style_type == "Beer") {
			$style_beer_count[] .= $row_substyle_count['count'];
			$style_beer_count_logged[] .= $row_substyle_count_logged['count'];
		}

		elseif ($style_type == "Mead") {
			$style_mead_count[] .= $row_substyle_count['count'];
			$style_mead_count_logged[] .= $row_substyle_count_logged['count'];
			$style_mead_cider_count[] .= $row_substyle_count['count'];
			$style_mead_cider_count_logged[] .= $row_substyle_count_logged['count'];
		}

		elseif ($style_type == "Cider") {
			$style_cider_count[] .= $row_substyle_count['count'];
			$style_cider_count_logged[] .= $row_substyle_count_logged['count'];
			$style_mead_cider_count[] .= $row_substyle_count['count'];
			$style_mead_cider_count_logged[] .= $row_substyle_count_logged['count'];
		}

		else {
			$style_other_count[] .= $row_substyle_count['count'];
			$style_other_count_logged[] .= $row_substyle_count_logged['count'];
		}

	}

}
?>