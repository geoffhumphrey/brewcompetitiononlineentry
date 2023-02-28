<?php

$add_edit_entry_modals = "";
$special_beer = array();
$special_beer_info = array();
$carb_str_only = array();
$carb_str_sweet_special = array();
$carb_str_sweet_special_info = array();
$sweet_carb_only = array();
$sweet_carb_str_only = array();
$spec_sweet_carb_only = array();
$spec_sweet_carb_only_info = array();
$spec_carb_only = array();
$spec_carb_only_info = array();

$replacement1 = array('Entry Instructions:','Commercial Examples:','must specify','may specify','MUST specify','MAY specify','must provide','must be specified','must declare','must either','must supply','may provide','MUST state');
if ($go == "default") $replacement2 = array('<strong class="text-danger">Entry Instructions:</strong>','<strong class="text-info">Commercial Examples:</strong>','<strong><u>MUST</u></strong> specify','<strong><u>MAY</u></strong> specify','<strong><u>MUST</u></strong> specify','<strong><u>MAY</u></strong> specify','<strong><u>MUST</u></strong> provide','<strong><u>MUST</u></strong> declare','<strong><u>MUST</u></strong> either','<strong><u>MUST</u></strong> supply','<strong><u>MAY</u></strong> provide','<strong><u>MUST</u></strong> state');
else $replacement2 = array('<strong class="text-danger">Entry Instructions:</strong>','<strong class="text-info">Commercial Examples:</strong>','<strong><u>MUST</u></strong> specify','<strong><u>MAY</u></strong> specify','<strong><u>MUST</u></strong> specify','<u>MAY</u> specify','<u>MUST</u> provide','<strong><u>MUST</u></strong> be specified','<strong><u>MUST</u></strong> declare','<strong><u>MUST</u></strong> either','<strong><u>MUST</u></strong> supply','<strong><u>MAY</u></strong> provide','<strong><u>MUST</u></strong> state');
$replacement3 = array('Entry Instructions:','Commercial Examples:','<strong><u>MUST</u></strong> specify','<strong><u>MAY</u></strong> specify (using the <em>Optional Info</em> field below)','<strong><u>MUST</u></strong> specify','<strong><u>MAY</u></strong> specify (using the <em>Optional Info</em> field below)','<strong><u>MUST</u></strong> provide','<strong><u>MUST</u></strong> be specified','<strong><u>MUST</u></strong> declare','<strong><u>MUST</u></strong> either','<strong><u>MUST</u></strong> supply','<strong><u>MAY</u></strong> provide (using the <em>Optional Info</em> field below)','<strong><u>MUST</u></strong> state');

$replacement4 = array('must specify','may specify','MUST specify','MAY specify','must provide','must be specified','must declare','must either','must supply','may provide','MUST state');
$replacement5 = array('<strong><u>MUST</u></strong> specify','<strong><u>MAY</u></strong> specify (using the <em>Optional Info</em> field below)','<strong><u>MUST</u></strong> specify','<strong><u>MAY</u></strong> specify (using the <em>Optional Info</em> field below)','<strong><u>MUST</u></strong> provide','<strong><u>MUST</u></strong> be specified','<strong><u>MUST</u></strong> declare','<strong><u>MUST</u></strong> either','<strong><u>MUST</u></strong> supply','<strong><u>MAY</u></strong> provide (using the <em>Optional Info</em> field below)','<strong><u>MUST</u></strong> state');

if (($_SESSION['prefsLanguage'] == "en-US") && (($_SESSION['prefsStyleSet'] == "BJCP2015") || ($_SESSION['prefsStyleSet'] == "BJCP2021"))) {
	$styles_entry_text = array(
	    "7-C" => str_replace($replacement4,$replacement5,$styles_entry_text_07C),
	    "9-A" => str_replace($replacement4,$replacement5,$styles_entry_text_09A),
	    "10-C" => str_replace($replacement4,$replacement5,$styles_entry_text_10C),
	    "21-B" => str_replace($replacement4,$replacement5,$styles_entry_text_21B),
	    "23-F" => str_replace($replacement4,$replacement5,$styles_entry_text_23F),
	    "24-C" => str_replace($replacement4,$replacement5,$styles_entry_text_24C),
	    "25-B" => str_replace($replacement4,$replacement5,$styles_entry_text_25B),
	    "27-A" => str_replace($replacement4,$replacement5,$styles_entry_text_27A),
	    "28-A" => str_replace($replacement4,$replacement5,$styles_entry_text_28A),
	    "28-B" => str_replace($replacement4,$replacement5,$styles_entry_text_28C),
	    "29-A" => str_replace($replacement4,$replacement5,$styles_entry_text_29A),
	    "29-B" => str_replace($replacement4,$replacement5,$styles_entry_text_29B),
	    "29-C" => str_replace($replacement4,$replacement5,$styles_entry_text_29C),
	    "30-A" => str_replace($replacement4,$replacement5,$styles_entry_text_30A),
	    "30-B" => str_replace($replacement4,$replacement5,$styles_entry_text_30B),
	    "30-C" => str_replace($replacement4,$replacement5,$styles_entry_text_30C),
	    "31-A" => str_replace($replacement4,$replacement5,$styles_entry_text_30A),
	    "31-B" => str_replace($replacement4,$replacement5,$styles_entry_text_31B),
	    "32-A" => str_replace($replacement4,$replacement5,$styles_entry_text_32A),
	    "32-B" => str_replace($replacement4,$replacement5,$styles_entry_text_32B),
	    "33-A" => str_replace($replacement4,$replacement5,$styles_entry_text_33A),
	    "33-B" => str_replace($replacement4,$replacement5,$styles_entry_text_33B),
	    "34-A" => str_replace($replacement4,$replacement5,$styles_entry_text_34A),
	    "34-B" => str_replace($replacement4,$replacement5,$styles_entry_text_34B),
	    "34-C" => str_replace($replacement4,$replacement5,$styles_entry_text_34C),
	    "M1-A" => str_replace($replacement4,$replacement5,$styles_entry_text_M1A),
	    "M1-B" => str_replace($replacement4,$replacement5,$styles_entry_text_M1B),
	    "M1-C" => str_replace($replacement4,$replacement5,$styles_entry_text_M1C),
	    "M2-A" => str_replace($replacement4,$replacement5,$styles_entry_text_M2A),
	    "M2-B" => str_replace($replacement4,$replacement5,$styles_entry_text_M2B),
	    "M2-C" => str_replace($replacement4,$replacement5,$styles_entry_text_M2C),
	    "M2-D" => str_replace($replacement4,$replacement5,$styles_entry_text_M2D),
	    "M2-E" => str_replace($replacement4,$replacement5,$styles_entry_text_M2E),
	    "M3-A" => str_replace($replacement4,$replacement5,$styles_entry_text_M3A),
	    "M3-B" => str_replace($replacement4,$replacement5,$styles_entry_text_M3B),
	    "M4-A" => str_replace($replacement4,$replacement5,$styles_entry_text_M4A),
	    "M4-B" => str_replace($replacement4,$replacement5,$styles_entry_text_M4B),
	    "M4-C" => str_replace($replacement4,$replacement5,$styles_entry_text_M4C),
	    "C1-A" => str_replace($replacement4,$replacement5,$styles_entry_text_C1A),
	    "C1-B" => str_replace($replacement4,$replacement5,$styles_entry_text_C1B),
	    "C1-C" => str_replace($replacement4,$replacement5,$styles_entry_text_C1C),
	    "C1-E" => str_replace($replacement4,$replacement5,$styles_entry_text_C1E),
	    "C2-A" => str_replace($replacement4,$replacement5,$styles_entry_text_C2A),
	    "C2-B" => str_replace($replacement4,$replacement5,$styles_entry_text_C2B),
	    "C2-C" => str_replace($replacement4,$replacement5,$styles_entry_text_C2C),
	    "C2-D" => str_replace($replacement4,$replacement5,$styles_entry_text_C2D),
	    "C2-E" => str_replace($replacement4,$replacement5,$styles_entry_text_C2E),
	    "C2-F" => str_replace($replacement4,$replacement5,$styles_entry_text_C2F),
	    "21-B1" => str_replace($replacement4,$replacement5,$styles_entry_text_21X),
	    "21-B2" => str_replace($replacement4,$replacement5,$styles_entry_text_21X),
	    "21-B3" => str_replace($replacement4,$replacement5,$styles_entry_text_21X),
	    "21-B4" => str_replace($replacement4,$replacement5,$styles_entry_text_21X),
	    "21-B5" => str_replace($replacement4,$replacement5,$styles_entry_text_21X),
	    "21-B6" => str_replace($replacement4,$replacement5,$styles_entry_text_21X),
	    "21-B7" => str_replace($replacement4,$replacement5,$styles_entry_text_21X),
	    "PR-X3" => str_replace($replacement4,$replacement5,$styles_entry_text_PRX3),
	    "PR-X4" => str_replace($replacement4,$replacement5,$styles_entry_text_PRX4),
	    "LS-X3" => str_replace($replacement4,$replacement5,$styles_entry_text_PRX3),
	    "LS-X4" => str_replace($replacement4,$replacement5,$styles_entry_text_PRX4),
	);
}

elseif (($_SESSION['prefsLanguage'] != "en-US") && (($_SESSION['prefsStyleSet'] == "BJCP2015") || ($_SESSION['prefsStyleSet'] == "BJCP2021"))) {
	$styles_entry_text = array(
	    "7-C" => $styles_entry_text_07C,
	    "9-A" => $styles_entry_text_09A,
	    "10-C" => $styles_entry_text_10C,
	    "21-B" => $styles_entry_text_21B,
	    "23-F" => $styles_entry_text_23F,
	    "24-C" => $styles_entry_text_24C,
	    "25-B" => $styles_entry_text_25B,
	    "27-A" => $styles_entry_text_27A,
	    "28-A" => $styles_entry_text_28A,
	    "28-B" => $styles_entry_text_28C,
	    "29-A" => $styles_entry_text_29A,
	    "29-B" => $styles_entry_text_29B,
	    "29-C" => $styles_entry_text_29C,
	    "30-A" => $styles_entry_text_30A,
	    "30-B" => $styles_entry_text_30B,
	    "30-C" => $styles_entry_text_30C,
	    "31-A" => $styles_entry_text_30A,
	    "31-B" => $styles_entry_text_31B,
	    "32-A" => $styles_entry_text_32A,
	    "32-B" => $styles_entry_text_32B,
	    "33-A" => $styles_entry_text_33A,
	    "33-B" => $styles_entry_text_33B,
	    "34-A" => $styles_entry_text_34A,
	    "34-B" => $styles_entry_text_34B,
	    "34-C" => $styles_entry_text_34C,
	    "M1-A" => $styles_entry_text_M1A,
	    "M1-B" => $styles_entry_text_M1B,
	    "M1-C" => $styles_entry_text_M1C,
	    "M2-A" => $styles_entry_text_M2A,
	    "M2-B" => $styles_entry_text_M2B,
	    "M2-C" => $styles_entry_text_M2C,
	    "M2-D" => $styles_entry_text_M2D,
	    "M2-E" => $styles_entry_text_M2E,
	    "M3-A" => $styles_entry_text_M3A,
	    "M3-B" => $styles_entry_text_M3B,
	    "M4-A" => $styles_entry_text_M4A,
	    "M4-B" => $styles_entry_text_M4B,
	    "M4-C" => $styles_entry_text_M4C,
	    "C1-E" => $styles_entry_text_C1E,
	    "C2-A" => $styles_entry_text_C2A,
	    "C2-B" => $styles_entry_text_C2B,
	    "C2-C" => $styles_entry_text_C2C,
	    "C2-D" => $styles_entry_text_C2D,
	    "C2-E" => $styles_entry_text_C2E,
	    "C2-F" => $styles_entry_text_C2F,
	    "21-B1" => $styles_entry_text_21X,
	    "21-B2" => $styles_entry_text_21X,
	    "21-B3" => $styles_entry_text_21X,
	    "21-B4" => $styles_entry_text_21X,
	    "21-B5" => $styles_entry_text_21X,
	    "21-B6" => $styles_entry_text_21X,
	    "21-B7" => $styles_entry_text_21X,
	    "PR-X3" => $styles_entry_text_PRX3,
	    "PR-X4" => $styles_entry_text_PRX4,
	    "LS-X3" => $styles_entry_text_PRX3,
	    "LS-X4" => $styles_entry_text_PRX4,
	);
}

else $styles_entry_text = array();

// --------------------------------------If BJCP Styles --------------------------------------

if ($_SESSION['prefsStyleSet'] != "BA") $styleSet = str_replace("2"," 2",$_SESSION['prefsStyleSet']);
else $styleSet = $_SESSION['prefsStyleSet'];

/**
 * Loop through and make arrays of styles that require or allow:
 * - Special Ingredients
 * - Strength
 * - Carbonation
 * - Sweetness
 * - Optional Info ($optional_info_styles array already exists in constants)
 */

$query_required_optional = sprintf("SELECT * FROM %s WHERE (brewStyleVersion = '%s' OR brewStyleOwn = 'custom')", $styles_db_table, $_SESSION['prefsStyleSet']);
$required_optional = mysqli_query($connection,$query_required_optional) or die (mysqli_error($connection));
$row_required_optional = mysqli_fetch_assoc($required_optional);
$totalRows_required_optional = mysqli_num_rows($required_optional);

$req_special_ing_styles = array();
$req_strength_styles = array();
$req_sweetness_styles = array();
$req_carb_styles = array();

$cider_sweetness_custom_styles = array();
$mead_sweetness_custom_styles = array();

do {

	$style_id = ltrim($row_required_optional['brewStyleGroup'],"0")."-".$row_required_optional['brewStyleNum'];

	if ($row_required_optional['brewStyleReqSpec'] == 1) $req_special_ing_styles[] = $style_id;
	if ($row_required_optional['brewStyleStrength'] == 1) $req_strength_styles[] = $style_id;
	if ($row_required_optional['brewStyleSweet'] == 1) $req_sweetness_styles[] = $style_id;
	if ($row_required_optional['brewStyleCarb'] == 1) $req_carb_styles[] = $style_id;
	if (($row_required_optional['brewStyleType'] == 2) && (is_numeric($row_required_optional['brewStyleGroup']))) $cider_sweetness_custom_styles[] = $style_id;
	if (($row_required_optional['brewStyleType'] == 3) && (is_numeric($row_required_optional['brewStyleGroup']))) $mead_sweetness_custom_styles[] = $style_id;

	// If BJCP 2021, add style 2A to the list
	if (($row_required_optional['brewStyleVersion'] == "BJCP2021") && ($style_id == "2-A")) {
		$req_special_ing_styles[] = $style_id;
	}

	if ($_SESSION['prefsStyleSet'] == "BA") {
		if (!empty($row_required_optional['brewStyleInfo'])) $styles_entry_text[$style_id] = str_replace($replacement1,$replacement2,$row_required_optional['brewStyleInfo']);
	}
	
	else {
		if (!empty($row_required_optional['brewStyleEntry'])) {
			if ($_SESSION['prefsLanguage'] == "en-US") $styles_entry_text[$style_id] = str_replace($replacement4,$replacement5,$row_required_optional['brewStyleEntry']);
			else $styles_entry_text[$style_id] = $row_required_optional['brewStyleEntry'];
		}
	}

	// Build Modals
	if ($section == "brew") {

		if (($row_required_optional['brewStyleReqSpec'] == 1) || ($row_required_optional['brewStyleStrength'] == 1) || ($row_required_optional['brewStyleSweet'] == 1) || ($row_required_optional['brewStyleCarb'] == 1)) {

			$info = str_replace($replacement1,$replacement2,"<p>".$row_required_optional['brewStyleInfo']."</p>");
			if ($_SESSION['prefsStyleSet'] == "BA") $info .= "<p>".$entry_info_text_052."</p>";

			if ($row_required_optional['brewStyleOwn'] == "custom") $styleSet = "Custom"; else $styleSet = $styleSet;

			if (!empty($row_required_optional['brewStyleComEx'])) $info .= str_replace($replacement1,$replacement2,"<p>".$label_commercial_examples.": ".$row_required_optional['brewStyleComEx']."</p>");
			
			if (array_key_exists($style_id, $styles_entry_text)) {
				if ($_SESSION['prefsLanguage'] == "en-US") $info .= str_replace($replacement1,$replacement2,"<p>".$label_entry_instructions.": ".$styles_entry_text[$style_id]."</p>");
				else $info .= "<p>".$label_entry_instructions.": ".$styles_entry_text[$style_id]."</p>";
			}
			
			else {
				if (!empty($row_required_optional['brewStyleEntry'])) {
					if ($_SESSION['prefsLanguage'] == "en-US") $info .= str_replace($replacement1,$replacement2,"<p>".$label_entry_instructions.": ".$row_required_optional['brewStyleEntry']."</p>");
					else $info .= "<p>".$label_entry_instructions.": ".$row_required_optional['brewStyleEntry']."</p>";
				}
			}

			if (empty($row_required_optional['brewStyleOG'])) $styleOG = "Varies";
			else $styleOG = number_format((float)$row_required_optional['brewStyleOG'], 3, '.', '')." &ndash; ".number_format((float)$row_required_optional['brewStyleOGMax'], 3, '.', '');

			if (empty($row_required_optional['brewStyleFG'])) $styleFG = "Varies";
			else $styleFG = number_format((float)$row_required_optional['brewStyleFG'], 3, '.', '')." &ndash; ".number_format((float)$row_required_optional['brewStyleFGMax'], 3, '.', '');

			if (empty($row_required_optional['brewStyleABV'])) $styleABV = "Varies";
			else $styleABV = $row_required_optional['brewStyleABV']." &ndash; ".$row_required_optional['brewStyleABVMax'];

			if (empty($row_required_optional['brewStyleIBU']))  $styleIBU = "Varies";
			elseif ($row_required_optional['brewStyleIBU'] == "N/A") $styleIBU =  "N/A";
			elseif (!empty($row_required_optional['brewStyleIBU'])) $styleIBU = ltrim($row_required_optional['brewStyleIBU'], "0")." &ndash; ".ltrim($row_required_optional['brewStyleIBUMax'], "0")." IBU";
			else $styleIBU = "&nbsp;";

			if (empty($row_required_optional['brewStyleSRM'])) $styleColor = "Varies";
			elseif ($row_required_optional['brewStyleSRM'] == "N/A") $styleColor = "N/A";
			elseif (!empty($row_required_optional['brewStyleSRM'])) {
				$SRMmin = ltrim ($row_required_optional['brewStyleSRM'], "0");
				$SRMmax = ltrim ($row_required_optional['brewStyleSRMMax'], "0");
				if ($SRMmin >= "15") $color1 = "#ffffff"; else $color1 = "#000000";
				if ($SRMmax >= "15") $color2 = "#ffffff"; else $color2 = "#000000";

				$styleColor = "<span class=\"badge\" style=\"background-color: ".srm_color($SRMmin,"srm")."; color: ".$color1."\">&nbsp;".$SRMmin."&nbsp;</span>";
				$styleColor .= " &ndash; ";
				$styleColor .= "<span class=\"badge\" style=\"background-color: ".srm_color($SRMmax,"srm")."; color: ".$color2."\">&nbsp;".$SRMmax."&nbsp;</span> <small class=\"text-muted\"><em>SRM</em></small>";

			}
			else $styleColor = "&nbsp;";

			if ($styleSet == "BA") $style_name = $styleSet." Style: ".$row_required_optional['brewStyle'];
			elseif ($styleSet == "AABC") $style_name = $styleSet." Style ".ltrim($row_required_optional['brewStyleGroup'],"0").".".ltrim($row_required_optional['brewStyleNum'],"0").": ".$row_required_optional['brewStyle'];
			else $style_name = $_SESSION['style_set_short_name']." Style ".ltrim($row_required_optional['brewStyleGroup'],"0").ltrim($row_required_optional['brewStyleNum'],"0").": ".$row_required_optional['brewStyle'];

			$info .= "<table class=\"table table-bordered table-striped\">";
			$info .= "<tr>";
			$info .= "<th class=\"dataLabel data bdr1B\">".$label_og."</th>";
			$info .= "<th class=\"dataLabel data bdr1B\">".$label_fg."</th>";
			$info .= "<th class=\"dataLabel data bdr1B\">ABV</th>";
			$info .= "<th class=\"dataLabel data bdr1B\">".$label_bitterness."</th>";
			$info .= "<th class=\"dataLabel data bdr1B\">".$label_color."</th>";
			$info .= "</tr>";
			$info .= "<tr>";
			$info .= "<td nowrap>".$styleOG."</td>";
			$info .= "<td nowrap>".$styleFG."</td>";
			$info .= "<td nowrap>".$styleABV."</td>";
			$info .= "<td nowrap>".$styleIBU."</td>";
			$info .= "<td>".$styleColor."</td>";
			$info .= "</tr>";
			$info .= "</table>";

			$add_edit_entry_modals .= "<!-- Modal -->";
			$add_edit_entry_modals .= "<div class=\"modal fade\" id=\"".$style_id."-modal\" tabindex=\"-1\" role=\"dialog\" aria-labelledby=\"".$style_id."Label\">";
			$add_edit_entry_modals .= "<div class=\"modal-dialog modal-lg\" role=\"document\">";
			$add_edit_entry_modals .= "<div class=\"modal-content\">";
			$add_edit_entry_modals .= "<div class=\"modal-header\">";
			$add_edit_entry_modals .= "<button type=\"button\" class=\"close\" data-dismiss=\"modal\" aria-label=\"Close\"><span aria-hidden=\"true\">&times;</span></button>";
			$add_edit_entry_modals .= "<h4 class=\"modal-title\" id=\"".$style_id."Label\">".$style_name."</h4>";
			$add_edit_entry_modals .= "</div>";
			$add_edit_entry_modals .= "<div class=\"modal-body\">".$info."</div>";
			$add_edit_entry_modals .= "<div class=\"modal-footer\">";
			$add_edit_entry_modals .= "<button type=\"button\" class=\"btn btn-danger\" data-dismiss=\"modal\">Close</button>";
			$add_edit_entry_modals .= "</div>";
			$add_edit_entry_modals .= "</div>";
			$add_edit_entry_modals .= "</div>";
			$add_edit_entry_modals .= "</div>";

		}

	}
	
} while($row_required_optional = mysqli_fetch_assoc($required_optional));

?>