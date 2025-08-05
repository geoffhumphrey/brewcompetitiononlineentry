<?php

include(LIB.'output.lib.php');

$eid = "";
$uid = "";
$style = "";
$entry_info_html = "";
$judge_info_html = "";
$scoresheet_version = "";
$entry_not_found = "";
$header_elements = "";
$sticky_score_tally = "";
$eval_nav_buttons = "";
$eval_score = 0;
$eval_prevent_edit = FALSE;
$entry_found = FALSE;
$mead_cider = FALSE;
$beer = FALSE;
$cider = FALSE;
$mead = FALSE;
$nw_cider = FALSE;
$scored_previously = FALSE;
$consensus_match = FALSE;
$auto_logout_extension = FALSE;
$evals = array();
$judge_scores = array();
$consensus_scores = array();
$other_judge_scores = "";
$other_judge_consensus_scores = "";
$other_judge_previous_consensus = array();
$my_consensus_score = "";
$evalPosition = "";

/*
if (HOSTED) $styles_db_table = "bcoem_shared_styles";
else
*/
$styles_db_table = $prefix."styles";

/**
 * Default judge range is 7 points, a commonly accepted
 * range. 
 */

if (isset($_SESSION['jPrefsScoreDispMax'])) $score_range = $_SESSION['jPrefsScoreDispMax'];
else $score_range = 7;

/**
 * BJCP 2015 and BJCP 2021 exceptions.
 * BJCP 2021 will be integrated when the BJCP website is updated
 * with the 2021 guidelines. See coding below.
 */

$bjcp2015_exceptions = array(
  "27A1" => "//bjcp.org/style/2015/27/27A/historical-beer-gose/",
  "27A2" => "//bjcp.org/style/2015/27/27A/historical-beer-piwo-grodziskie/",
  "27A3" => "//bjcp.org/style/2015/27/27A/historical-beer-lichtenhainer/",
  "27A4" => "//bjcp.org/style/2015/27/27A/historical-beer-roggenbier/",
  "27A5" => "//bjcp.org/style/2015/27/27A/historical-beer-sahti/",
  "27A6" => "//bjcp.org/style/2015/27/27A/historical-beer-kentucky-common/",
  "27A7" => "//bjcp.org/style/2015/27/27A/historical-beer-pre-prohibition-lager/",
  "27A8" => "//bjcp.org/style/2015/27/27A/historical-beer-pre-prohibition-porter/",
  "27A9" => "//bjcp.org/style/2015/27/27A/historical-beer-london-brown-ale/",
  "21B1" => "//bjcp.org/style/2015/21/21B/specialty-ipa-belgian-ipa/",
  "21B2" => "//bjcp.org/style/2015/21/21B/specialty-ipa-black-ipa/",
  "21B3" => "//bjcp.org/style/2015/21/21B/specialty-ipa-brown-ipa/",
  "21B4" => "//bjcp.org/style/2015/21/21B/specialty-ipa-red-ipa/",
  "21B5" => "//bjcp.org/style/2015/21/21B/specialty-ipa-rye-ipa/",
  "21B6" => "//bjcp.org/style/2015/21/21B/specialty-ipa-white-ipa/",
  "21B7" => "//bjcp.org/beer-styles/21b-specialty-ipa-new-england-ipa/",
  "17A1" => "//bjcp.org/beer-styles/17a-british-strong-ale-burton-ale/",
  "PRX1" => "//bjcp.org/beer-styles/x1-dorada-pampeana/",
  "PRX2" => "//bjcp.org/beer-styles/x2-ipa-argenta/",
  "PRX3" => "//bjcp.org/beer-styles/x3-italian-grape-ale/",
  "PRX4" => "//bjcp.org/beer-styles/x4-catharina-sour/",
  "PRX5" => "//bjcp.org/beer-styles/x5-new-zealand-pilsner/"
);

$bjcp2021_exceptions = array(
  "17A1" => "//bjcp.org/beer-styles/17a-british-strong-ale-burton-ale/",
  "21B1" => "//bjcp.org/style/2021/21/21B/specialty-ipa-belgian-ipa/",
  "21B2" => "//bjcp.org/style/2021/21/21B/specialty-ipa-black-ipa/",
  "21B3" => "//bjcp.org/style/2021/21/21B/specialty-ipa-brown-ipa/",
  "21B4" => "//bjcp.org/style/2021/21/21B/specialty-ipa-red-ipa/",
  "21B5" => "//bjcp.org/style/2021/21/21B/specialty-ipa-rye-ipa/",
  "21B6" => "//bjcp.org/style/2021/21/21B/specialty-ipa-white-ipa/",
  "27A1" => "//bjcp.org/style/2021/27/27A/historical-beer-kellerbier/",
  "27A2" => "//bjcp.org/style/2021/27/27A/historical-beer-kentucky-common/",
  "27A3" => "//bjcp.org/style/2021/27/27A/historical-beer-lichtenhainer/",
  "27A4" => "//bjcp.org/style/2021/27/27A/historical-beer-london-brown-ale/",
  "27A5" => "//bjcp.org/style/2021/27/27A/historical-beer-piwo-grodziskie/",
  "27A6" => "//bjcp.org/style/2021/27/27A/historical-beer-pre-prohibition-lager/",
  "27A7" => "//bjcp.org/style/2021/27/27A/historical-beer-pre-prohibition-porter/",
  "27A8" => "//bjcp.org/style/2021/27/27A/historical-beer-roggenbier/",
  "27A9" => "//bjcp.org/style/2021/27/27A/historical-beer-sahti/",
  "LSX1" => "//bjcp.org/beer-styles/x1-dorada-pampeana/",
  "LSX2" => "//bjcp.org/beer-styles/x2-ipa-argenta/",
  "LSX3" => "//bjcp.org/beer-styles/x3-italian-grape-ale/",
  "LSX4" => "//bjcp.org/beer-styles/x4-catharina-sour/",
  "LSX5" => "//bjcp.org/beer-styles/x5-new-zealand-pilsner/"
);

/**
 * When admins edit a scoresheet, the $bid var will be in the URL.
 * $bid is judge's user id.
 */

if ($bid == "default") {
  $judge_id = $_SESSION['user_id'];
  $eval_source = 1; // From user judging dashboard
}

else {
  $judge_id = $bid;
  $eval_source = 0; // From Admin
}

if (isset($_POST['participants'])) $eval_source = 0;

if (empty($row_judging_prefs['jPrefsScoresheet'])) $judging_scoresheet = 1;
elseif (!isset($_SESSION['jPrefsScoresheet'])) $judging_scoresheet = 1;
else $judging_scoresheet = $_SESSION['jPrefsScoresheet'];

if (is_numeric($sort)) $judging_scoresheet = $sort; 

if ($judging_scoresheet == 1) {
  $output_form = "full_output.eval.php";
  $scoresheet_form = "eval_scoresheet_full.pub.php";
  $process_type = "process-eval-full";
  $scoresheet_version = $label_classic_version;
}

if ($judging_scoresheet == 2) {
  $output_form = "checklist_output.eval.php";
  $scoresheet_form = "eval_scoresheet_checklist.pub.php";
  $process_type = "process-eval-checklist";
  $scoresheet_version = $label_checklist_version;
}

if (($judging_scoresheet == 3) || ($judging_scoresheet == 4)) {
  $output_form = "structured_output.eval.php";
  $scoresheet_form = "eval_scoresheet_structured.pub.php";
  $process_type = "process-eval-structured";
  $scoresheet_version = $label_structured_version;
}

/** 
 * When a user is adding a new evaluation.
 * If there's an entry_number $_POST var, indicates
 * that the scoresheet is being added by a non-admin
 * on-the-fly.
 */

$query_style = "";

if ($action == "add") {

  $submit_button_text = $label_submit_evaluation;

  if (isset($_POST['entry_number'])) {

    $id = ltrim(sterilize($_POST['entry_number']),"0");
    
    if ($_SESSION['prefsDisplaySpecial'] == "E") {
      $query_entry_info = sprintf("SELECT * FROM %s WHERE id='%s'",$prefix."brewing",$id);
    }
    
    if ($_SESSION['prefsDisplaySpecial'] == "J") {
      $judging_number = sterilize($_POST['entry_number']);
      $query_entry_info = sprintf("SELECT * FROM %s WHERE brewJudgingNumber='%s'",$prefix."brewing",$judging_number);
    }

  }
  
  else $query_entry_info = sprintf("SELECT * FROM %s WHERE id='%s'",$prefix."brewing",$id);
  $entry_info = mysqli_query($connection,$query_entry_info) or die (mysqli_error($connection));
  $row_entry_info = mysqli_fetch_assoc($entry_info);
  $totalRows_entry_info = mysqli_num_rows($entry_info);

  if ($totalRows_entry_info > 0) {
    
    /*
    if (HOSTED) $query_style = sprintf("SELECT * FROM %s WHERE brewStyleGroup = '%s' AND brewStyleNum = '%s' AND brewStyleVersion='%s' UNION ALL SELECT * FROM %s WHERE brewStyleGroup = '%s' AND brewStyleNum = '%s' AND brewStyleVersion='%s'", $styles_db_table, $row_entry_info['brewCategorySort'], $row_entry_info['brewSubCategory'], $_SESSION['prefsStyleSet'], $prefix."styles", $row_entry_info['brewCategorySort'], $row_entry_info['brewSubCategory'], $_SESSION['prefsStyleSet']);
    else
    */
    $query_style = sprintf("SELECT * FROM %s WHERE brewStyleGroup = '%s' AND brewStyleNum = '%s' AND brewStyleVersion='%s'", $prefix."styles", $row_entry_info['brewCategorySort'], $row_entry_info['brewSubCategory'], $_SESSION['prefsStyleSet']);
  }

}

/**
 * When a user is editing an evaluation.
 * Checks are in place to determine whether the 
 * current user is associated with the original
 * eval add.
 */
if ($action == "edit") {

  $submit_button_text = $label_edit_evaluation;
 
  if ($id == "default") $query_eval = sprintf("SELECT * FROM evaluation WHERE evalToken='%s'", $token);
  else $query_eval = sprintf("SELECT * FROM %s WHERE id=%s",$prefix."evaluation",$id);
  $eval = mysqli_query($connection,$query_eval) or die (mysqli_error($connection));
  $row_eval = mysqli_fetch_assoc($eval);
  $totalRows_eval = mysqli_num_rows($eval);

  if ($totalRows_eval > 0) {

    $evals = eval_exits($row_eval['eid'],"default",$dbTable);
    $evals_json = json_encode($evals);
 
    $eval_score = $row_eval['evalAromaScore'] + $row_eval['evalAppearanceScore'] + $row_eval['evalFlavorScore'] + $row_eval['evalMouthfeelScore'] + $row_eval['evalOverallScore']; 
    $eid = $row_eval['eid'];
    $uid = $row_eval['uid'];
    $style = $row_eval['evalStyle'];
    if (($_SESSION['userLevel'] > 1) && ($row_eval['evalJudgeInfo'] != $_SESSION['user_id'])) $eval_prevent_edit = TRUE;
    
    $query_entry_info = sprintf("SELECT * FROM %s WHERE id='%s'", $prefix."brewing", $eid);
    $entry_info = mysqli_query($connection,$query_entry_info) or die (mysqli_error($connection));
    $row_entry_info = mysqli_fetch_assoc($entry_info);
    $totalRows_entry_info = mysqli_num_rows($entry_info);
    
    if ($totalRows_entry_info > 0) {
      /*
      if (HOSTED) $query_style = sprintf("SELECT * FROM %s WHERE id='%s' UNION ALL SELECT * FROM %s WHERE id='%s'", $styles_db_table, $style, $prefix."styles", $style);
      else 
      */
      $query_style = sprintf("SELECT * FROM %s WHERE id='%s'", $prefix."styles", $style);
    }

  }

}

if (!empty($query_style)) {
  $style = mysqli_query($connection,$query_style) or die (mysqli_error($connection));
  $row_style = mysqli_fetch_assoc($style);
  $totalRows_style = mysqli_num_rows($style);
}

if ($totalRows_entry_info > 0) {
  $judge_scores = eval_exits($row_entry_info['id'],"judge_scores",$dbTable);
  if ($action == "add") $flight_count_info = flight_count_info($id,0);
  if ($action == "edit") $flight_count_info = flight_count_info($eid,0);

  if (!empty($judge_scores)) {
    $scored_previously = TRUE;
    $consensus_scores = eval_exits($row_entry_info['id'],"consensus_scores",$dbTable);
    if (count(array_unique($consensus_scores)) === 1) $consensus_match = TRUE;
    $other_judge_scores .= sprintf("%s: ".rtrim(display_array_content($judge_scores,2),", "),$label_judge_score);
    $other_judge_consensus_scores .= sprintf("%s: ".rtrim(display_array_content($consensus_scores,2),", "),$label_judge_consensus_scores);
    if (isset($row_eval['evalFinalScore'])) $my_consensus_score .= sprintf("%s: <span id=\"my-consensus-score\">".$row_eval['evalFinalScore']."</span>",$label_your_consensus_score);
  }

  if (($action == "edit") && (!$consensus_match)) $consensus_scores = array_diff($consensus_scores,array($row_eval['evalFinalScore']));

  if (isset($_POST['entry_number'])) {
    
    // Get table info
    $query_flight_info = sprintf("SELECT flightTable FROM %s WHERE flightEntryID='%s'",$prefix."judging_flights",$row_entry_info['id']);
    $flight_info = mysqli_query($connection,$query_flight_info) or die (mysqli_error($connection));
    $row_flight_info = mysqli_fetch_assoc($flight_info);

    if ($row_flight_info) $filter = $row_flight_info['flightTable'];

  }

}


/**
 * Included Descriptors are used by multiple functions.
 * Depends upon query of style db table.
 */
include (EVALS.'descriptors.eval.php');

if ($totalRows_entry_info > 0) $entry_found = TRUE;

if ($entry_found) {

  if ($row_style['brewStyleType'] == 2) $cider = TRUE;
  elseif ($row_style['brewStyleType'] == 3) $mead = TRUE;
  else $beer = TRUE;

  if ($judging_scoresheet == 4) {
    $cider = TRUE;
    $nw_cider = TRUE;
    if ($sort == 4) $scoresheet_version .= " &ndash; Northwest Cider Cup";
    else $scoresheet_version .= " &ndash; ".$_SESSION['style_set_long_name'];
  }

  // If style is Cider (2) or Mead (3), only use full scoresheet instad of checklist
  if ((($judging_scoresheet == 1) || ($judging_scoresheet == 2)) && (($cider) || ($mead))) {
    $output_form = "full_output.eval.php";
    $scoresheet_form = "eval_scoresheet_full.pub.php";
    $process_type = "process-eval-full";
    $mead_cider = TRUE;
    $scoresheet_version = $label_classic_version;
  }

  if ($action == "add") {
    $eid = $id;
    if (isset($_POST['entry_number'])) {
      $eid = $row_entry_info['id'];
    }
    $uid = $row_entry_info['brewBrewerID'];
    $style = $row_style['id'];
  }

  // Standardize entry identifier to 6-digits
  if ($_SESSION['prefsDisplaySpecial'] == "J") $number = sprintf("%06s",$row_entry_info['brewJudgingNumber']);
  else $number = sprintf("%06s",$row_entry_info['id']);

  // Standardize style number display
  $style_num = style_number_const($row_style['brewStyleGroup'],$row_style['brewStyleNum'],$_SESSION['style_set_display_separator'],0);

  // Build auto logout extended display
  if ($auto_logout_extension) {
    $entry_info_html .= sprintf("<div class=\"alert alert-info\"><strong><i class=\"fa fa-info-circle\"></i> %s:</strong> %s</div>",$label_please_note,$evaluation_info_072);
  }

  // Build entry info display
  //$entry_info_html .= "<h3>".$label_info." <a id=\"show-hide-entry-info-btn\" data-bs-toggle=\"collapse\" href=\"#scoresheet-entry-info\" aria-controls=\"scoresheet-entry-info\"><i id=\"toggle-icon-entry-info\" class=\"fa fa-chevron-circle-up\"></i></a></h3>";

  $entry_info_html .= "<h3>".$label_info."</h3>";
  $entry_info_html .= "<section class=\"row mb-3\">";
  $entry_info_html .= "<div class=\"col-12 ps-0\">";

  $entry_info_html .= "<section class=\"alert alert-teal\">";

  $entry_info_html .= "<div class=\"row mb-3\">";
  $entry_info_html .= "<div class=\"col-12 col-lg-3 col-md-4 col-sm-4\"><strong>".$label_number."</strong></div>";
  $entry_info_html .= "<div class=\"col-12 col-lg-9 col-md-8 col-sm-8\">".$number."</div>";
  $entry_info_html .= "</div>";

  $entry_info_html .= "<div class=\"row mb-3\">";
  if ($nw_cider) $entry_info_html .= "<div class=\"col-12 col-lg-3 col-md-4 col-sm-4\"><strong>".$_SESSION['style_set_short_name']." ".$label_category."</strong></div>";
  else $entry_info_html .= "<div class=\"col-12 col-lg-3 col-md-4 col-sm-4\"><strong>".$_SESSION['style_set_short_name']." ".$label_style."</strong></div>";
  $entry_info_html .= "<div class=\"col-12 col-lg-9 col-md-8 col-sm-8\">";

  // Style Links
  $style_link = "";
  $style_concat = ltrim($row_style['brewStyleGroup'],"0").strtoupper($row_style['brewStyleNum']);

  if (!empty($row_style['brewStyleLink'])) {
    
    if ($_SESSION['prefsStyleSet'] == "BJCP2015") {

      if (array_key_exists($style_concat, $bjcp2015_exceptions)) $style_link = $bjcp2015_exceptions[$style_concat];
      else $style_link = "//bjcp.org/style/2015/".ltrim($row_style['brewStyleGroup'],"0")."/".$style_concat."/";
      
    }
    
    else $style_link = $row_style['brewStyleLink'];

  }

  elseif (($_SESSION['prefsStyleSet'] == "BJCP2021") || ($_SESSION['prefsStyleSet'] == "BJCP2025")) {

    $first_character = mb_substr($row_style['brewStyleGroup'], 0, 1);

    // Exceptions
    if (array_key_exists($style_concat, $bjcp2021_exceptions)) $style_link = $bjcp2021_exceptions[$style_concat];

    // 2021 update was beer only; find numbered styles
    elseif (is_numeric(ltrim($row_style['brewStyleGroup'],"0"))) $style_link = "//bjcp.org/style/2021/".ltrim($row_style['brewStyleGroup'],"0")."/".$style_concat."/";

    // 2025 update was cider only; find styles that begin with C
    elseif (($_SESSION['prefsStyleSet'] == "BJCP2025") && ($first_character == "C")) $style_link = "//bjcp.org/style/2025/".ltrim($row_style['brewStyleGroup'],"0")."/".$style_concat."/";

    // If mead, use 2015 link
    else $style_link = "//bjcp.org/style/2015/".ltrim($row_style['brewStyleGroup'],"0")."/".$style_concat."/";

  }

  if (empty($style_link)) {

    $entry_info_html .= $style_num." ".$row_style['brewStyle'];
    if (($_SESSION['prefsStyleSet'] == "BJCP2021") || ($_SESSION['prefsStyleSet'] == "BJCP2025")) $entry_info_html .= "<a style=\"margin-left:10px;\" href=\"https://www.bjcp.org/bjcp-style-guidelines\" target=\"_blank\"><i class=\"small fa fa-external-link\"></i></a>";
    if (($_SESSION['prefsStyleSet'] == "AABC") || ($_SESSION['prefsStyleSet'] == "AABC2022") || ($_SESSION['prefsStyleSet'] == "AABC2025")) $entry_info_html .= "<a style=\"margin-left:10px;\" href=\"https://aabc.asn.au/docs/AABC2025StyleGuidelines.pdf\" target=\"_blank\"><i class=\"small fa fa-external-link\"></i></a>";
    if ($_SESSION['prefsStyleSet'] == "BA") $entry_info_html .= "<a style=\"margin-left:10px;\" href=\"https://www.brewersassociation.org/edu/brewers-association-beer-style-guidelines/\" target=\"_blank\"><i class=\"small fa fa-external-link\"></i></a>";

  }

  else {

    $entry_info_html .= "<a href=\"".$style_link."\" target=\"_blank\">";
    $entry_info_html .= $style_num." ".$row_style['brewStyle'];
    $entry_info_html .= "<i style=\"margin-left:10px;\" class=\"small fa fa-external-link\"></i></a>";

  }

  $entry_info_html .= "</div>";
  $entry_info_html .= "</div>";


  if (!empty($row_entry_info['brewInfo'])) {
    $entry_info_html .= "<div class=\"row mb-3\">";
    if ((($_SESSION['prefsStyleSet'] == "BJCP2021") || ($_SESSION['prefsStyleSet'] == "BJCP2025")) && ($style_num == "2A")) $entry_info_html .= "<div class=\"col-12 col-lg-3 col-md-4 col-sm-4\"><strong>".$label_regional_variation."</strong></div>";
    else $entry_info_html .= "<div class=\"col-12 col-lg-3 col-md-4 col-sm-4\"><strong>".$label_required_info."</strong></div>";
    $entry_info_html .= "<div class=\"col-12 col-lg-9 col-md-8 col-sm-8\">".str_replace("^", " - ", $row_entry_info['brewInfo'])."</div>";
    $entry_info_html .= "</div>";
  }

  if (!empty($row_entry_info['brewInfoOptional'])) {
    $entry_info_html .= "<div class=\"row mb-3\">";
    $entry_info_html .= "<div class=\"col-12 col-lg-3 col-md-4 col-sm-4\"><strong>".$label_optional_info."</strong></div>";
    $entry_info_html .= "<div class=\"col-12 col-lg-9 col-md-8 col-sm-8\">".$row_entry_info['brewInfoOptional']."</div>";
    $entry_info_html .= "</div>";
  }

  if (!empty($row_entry_info['brewComments'])) {
    $entry_info_html .= "<div class=\"row mb-3\">";
    $entry_info_html .= "<div class=\"col-12 col-lg-3 col-md-4 col-sm-4\"><strong>".$label_brewer_specifics."</strong></div>";
    $entry_info_html .= "<div class=\"col-12 col-lg-9 col-md-8 col-sm-8\">".$row_entry_info['brewComments']."</div>";
    $entry_info_html .= "</div>";
  }

  if (!empty($row_entry_info['brewMead1'])) {
    $entry_info_html .= "<div class=\"row mb-3\">";
    $entry_info_html .= "<div class=\"col-12 col-lg-3 col-md-4 col-sm-4\"><strong>".$label_carbonation."</strong></div>";
    $entry_info_html .= "<div class=\"col-12 col-lg-9 col-md-8 col-sm-8\">".$row_entry_info['brewMead1']."</div>";
    $entry_info_html .= "</div>";
  }

  if (!empty($row_entry_info['brewMead3'])) {
    $entry_info_html .= "<div class=\"row mb-3\">";
    $entry_info_html .= "<div class=\"col-12 col-lg-3 col-md-4 col-sm-4\"><strong>".$label_strength."</strong></div>";
    $entry_info_html .= "<div class=\"col-12 col-lg-9 col-md-8 col-sm-8\">".$row_entry_info['brewMead3']."</div>";
    $entry_info_html .= "</div>";
  }

  if (!empty($row_entry_info['brewMead2'])) {
    $entry_info_html .= "<div class=\"row mb-3\">";
    $entry_info_html .= "<div class=\"col-12 col-lg-3 col-md-4 col-sm-4\"><strong>".$label_sweetness."</strong></div>";
    $entry_info_html .= "<div class=\"col-12 col-lg-9 col-md-8 col-sm-8\">".$row_entry_info['brewMead2']."</div>";
    $entry_info_html .= "</div>";
  }

  if (($_SESSION['prefsStyleSet'] == "NWCiderCup") && (!empty($row_entry_info['brewSweetnessLevel']))) {
    $entry_info_html .= "<div class=\"row mb-3\">";
    $entry_info_html .= "<div class=\"col-12 col-lg-3 col-md-4 col-sm-4\"><strong>".$label_final_gravity."</strong></div>";
    $entry_info_html .= "<div class=\"col-12 col-lg-9 col-md-8 col-sm-8\">".$row_entry_info['brewSweetnessLevel']."</div>";
    $entry_info_html .= "</div>";
  }

  if (($_SESSION['prefsStyleSet'] != "NWCiderCup") && (!empty($row_entry_info['brewSweetnessLevel']))) {

    $sweetness_json = json_decode($row_entry_info['brewSweetnessLevel'],true);
    
    if (json_last_error() === JSON_ERROR_NONE) {

      if (!empty($sweetness_json['OG'])) {
        $entry_info_html .= "<div class=\"row bcoem-admin-element\">";
        $entry_info_html .= "<div class=\"col col-lg-3 col-md-4 col-sm-4 col-xs-12\"><strong>".$label_original_gravity."</strong></div>";
        $entry_info_html .= "<div class=\"col col-lg-9 col-md-8 col-sm-8 col-xs-12\">".$sweetness_json['OG']."</div>";
        $entry_info_html .= "</div>";
      }

      if (!empty($sweetness_json['FG'])) {
        $entry_info_html .= "<div class=\"row bcoem-admin-element\">";
        $entry_info_html .= "<div class=\"col col-lg-3 col-md-4 col-sm-4 col-xs-12\"><strong>".$label_final_gravity."</strong></div>";
        $entry_info_html .= "<div class=\"col col-lg-9 col-md-8 col-sm-8 col-xs-12\">".$sweetness_json['FG']."</div>";
        $entry_info_html .= "</div>";
      }

    }
    
    else {

      $entry_info_html .= "<div class=\"row bcoem-admin-element\">";
      $entry_info_html .= "<div class=\"col col-lg-3 col-md-4 col-sm-4 col-xs-12\"><strong>".$label_final_gravity."</strong></div>";
      $entry_info_html .= "<div class=\"col col-lg-9 col-md-8 col-sm-8 col-xs-12\">".$row_entry_info['brewSweetnessLevel']."</div>";
      $entry_info_html .= "</div>";

      $sweetness_level_display .= "<strong>".$label_final_gravity.":</strong> ".$row_entries['brewSweetnessLevel'];
    }

  }

  if (!empty($row_entry_info['brewABV'])) {
    $entry_info_html .= "<div class=\"row mb-3\">";
    $entry_info_html .= "<div class=\"col-12 col-lg-3 col-md-4 col-sm-4\"><strong>".$label_abv."</strong></div>";
    $entry_info_html .= "<div class=\"col-12 col-lg-9 col-md-8 col-sm-8\">".number_format($row_entry_info['brewABV'],1)."&#37;</div>";
    $entry_info_html .= "</div>";
  }

  if (!empty($row_entry_info['brewPossAllergens'])) {
    $entry_info_html .= "<div class=\"row mb-3\">";
    $entry_info_html .= "<div class=\"col-12 col-lg-3 col-md-4 col-sm-4\"><strong>".$label_possible_allergens."</strong></div>";
    $entry_info_html .= "<div class=\"col-12 col-lg-9 col-md-8 col-sm-8\">".$row_entry_info['brewPossAllergens']."</div>";
    $entry_info_html .= "</div>";
  }

  if ((!empty($row_entry_info['brewPouring'])) && ((!empty($row_entry_info['brewStyleType'])) && ($row_entry_info['brewStyleType'] == 1))) {
    
    $pouring_arr = json_decode($row_entry_info['brewPouring'],true);

    $entry_info_html .= "<div class=\"row mb-3\">";
    $entry_info_html .= "<div class=\"col-12 col-lg-3 col-md-4 col-sm-4\"><strong>".$label_pouring."</strong></div>";
    $entry_info_html .= "<div class=\"col-12 col-lg-9 col-md-8 col-sm-8\">".$pouring_arr['pouring']."</div>";
    $entry_info_html .= "</div>";

    if ((isset($pouring_arr['pouring_notes'])) && (!empty($pouring_arr['pouring_notes'])))  {
      $entry_info_html .= "<div class=\"row mb-3\">";
      $entry_info_html .= "<div class=\"col-12 col-lg-3 col-md-4 col-sm-4\"><strong>".$label_pouring_notes."</strong></div>";
      $entry_info_html .= "<div class=\"col-12 col-lg-9 col-md-8 col-sm-8\">".$pouring_arr['pouring_notes']."</div>";
      $entry_info_html .= "</div>";
    }

    $entry_info_html .= "<div class=\"row mb-3\">";
    $entry_info_html .= "<div class=\"col-12 col-lg-3 col-md-4 col-sm-4\"><strong>".$label_rouse_yeast."</strong></div>";
    $entry_info_html .= "<div class=\"col-12 col-lg-9 col-md-8 col-sm-8\">".$pouring_arr['pouring_rouse']."</div>";
    $entry_info_html .= "</div>";

  }

  if (!empty($row_entry_info['brewStaffNotes'])) {
    $entry_info_html .= "<div class=\"row mb-3\">";
    $entry_info_html .= "<div class=\"col-12 col-lg-3 col-md-4 col-sm-4\"><strong>".$label_notes." &ndash; ".$label_staff."</strong></div>";
    $entry_info_html .= "<div class=\"col-12 col-lg-9 col-md-8 col-sm-8\">".$row_entry_info['brewStaffNotes']."</div>";
    $entry_info_html .= "</div>";
  }

  if (!empty($row_entry_info['brewAdminNotes'])) {
    $entry_info_html .= "<div class=\"row mb-3\">";
    $entry_info_html .= "<div class=\"col-12 col-lg-3 col-md-4 col-sm-4\"><strong>".$label_notes." &ndash; ".$label_admin_short."</strong></div>";
    $entry_info_html .= "<div class=\"col-12 col-lg-9 col-md-8 col-sm-8\">".$row_entry_info['brewAdminNotes']."</div>";
    $entry_info_html .= "</div>";
  }


  $entry_info_html .= "</section>"; // end alert-teal

  if ((isset($_POST['participants'])) || ($bid != "default")) {
    
    $eval_source = 0;

    if (isset($_POST['participants'])) {
      $judge_id = $_POST['participants'];
      $eval_judge = brewer_info($_POST['participants']);
      $view = "admin";
    }

    else {
      $judge_id = $bid;
      $eval_judge = brewer_info($bid);
    }

    

    $eval_judge = explode("^",$eval_judge);

    if (strpos($eval_judge[3], ",") !== false) {
        $judge_rank = explode(",",$eval_judge[3]);
        $judge_rank_display = $judge_rank[0];
    }

    else $judge_rank_display = $eval_judge[3];

    $judge_info_html .= "<section class=\"alert alert-info\">";
    $judge_info_html .= "<div class=\"row mb-3\">";
    $judge_info_html .= "<div class=\"col-12 col-lg-3 col-md-4 col-sm-4\"><strong>".$label_judge."</strong></div>";
    $judge_info_html .= "<div class=\"col-12 col-lg-9 col-md-8 col-sm-8\">".$eval_judge[0]." ".$eval_judge[1]."</div>";
    $judge_info_html .= "</div>"; // end row
    $judge_info_html .= "<div class=\"row mb-3\">";
    $judge_info_html .= "<div class=\"col-12 col-lg-3 col-md-4 col-sm-4\">";
    $judge_info_html .= "<strong>".$label_bjcp_rank;
    if (($eval_judge[4] != "&nbsp;") && ((isset($judge_rank)) && ($judge_rank[0] != "Non-BJCP"))) $judge_info_html .= " (".$label_bjcp_id.")";
    $judge_info_html .= "</strong>";
    $judge_info_html .= "</div>";
    $judge_info_html .= "<div class=\"col-12 col-lg-9 col-md-8 col-sm-8\">";
    $judge_info_html .= $judge_rank_display;
    if (($eval_judge[4] != "&nbsp;") && ((isset($judge_rank)) && ($judge_rank[0] != "Non-BJCP"))) $judge_info_html .= " (".$eval_judge[4].")";
    if ((isset($judge_rank)) && (is_array($judge_rank))) {
      $judge_credentials = "<br>";
      foreach ($judge_rank as $key => $value) {
        if ($key != 0) $judge_credentials .= $value.", ";
      }
      $judge_credentials = rtrim($judge_credentials, ", ");
      $judge_info_html .= $judge_credentials;
    }
    $judge_info_html .= "</div>";
    $judge_info_html .= "</div>"; // end row
    $judge_info_html .= "</section>"; // end alert-info
  
  }

  if (!empty($judge_info_html)) $entry_info_html .= $judge_info_html;

  $entry_info_html .= "</div>"; // end col-12 ps-0

  $entry_info_html .= "</section>"; // end row mb-3

  
  
  
  // If admin is adding eval on behalf of a judge, or if editing a judge's evaluation, display their judge's info
  
  
  // Sticky score
  $sticky_score_tally = "\n\n<div id=\"sticky-score\" class=\"float-end\">";

  $sticky_score_tally .= "<div class=\"float-end mb-3 pt-2 pb-2\">";
  $sticky_score_tally .= "<i id=\"warning-indicator-icon\" class=\"fa fa-lg fa-exclamation-triangle text-warning-emphasis mt-2 mb-2 me-1\"></i>";
  $sticky_score_tally .= "<a class=\"mt-2 mb-2\" role=\"button\" id=\"show-hide-status-btn\" data-bs-toggle=\"collapse\" href=\"#scoring-guide-status\" aria-controls=\"scoring-guide-status\"><i id=\"toggle-icon\" class=\"fa fa-lg fa-chevron-circle-up\"></i></a>";
  $sticky_score_tally .= "</div>";

  if (!$nw_cider) { 
    $sticky_score_tally .= "<section class=\"mb-3\">";
    $sticky_score_tally .= "<div class=\"fs-4\">";
    $sticky_score_tally .= "<span id=\"scoring-guide-badge\" class=\"badge text-bg-dark shadow\">".$label_score.": <span id=\"judge-score\" class=\"pe-2\">".$eval_score."</span> <span id=\"scoring-guide\" class=\"fw-light small\"></span></span>";
    $sticky_score_tally .= "</div>";
    $sticky_score_tally .= "</section>";
  }

  $sticky_score_tally .= "<section id=\"scoring-guide-status\" class=\"p-3 w-100 border border-secondary-subtle bg-secondary-subtle shadow rounded-3 collapse show\">";

  if (!$nw_cider) {
    
    $sticky_score_tally .= "<p class=\"mb-0 pb-1\"><i class=\"fa fa-info-circle\"></i> <strong>".$label_status." &ndash; ".$label_admin_scores."</strong></p>";
    $sticky_score_tally .= "<section class=\"row small\">";
    $sticky_score_tally .= "<div class=\"col-10\">";
    $sticky_score_tally .= "<i id=\"score-icon-aroma-status\" class=\"fa fa-fw text-danger fa-times-circle me-1\"></i>".truncate($label_aroma,"10","");
    $sticky_score_tally .= "</div>";
    $sticky_score_tally .= "<div class=\"col-2 text-end\">";
    $sticky_score_tally .= "<span id=\"score-aroma-status\"></span>";
    $sticky_score_tally .= "</div>";
    $sticky_score_tally .= "</section>";

    $sticky_score_tally .= "<section class=\"row small\">";
    $sticky_score_tally .= "<div class=\"col-10\">";
    $sticky_score_tally .= "<i id=\"score-icon-appearance-status\" class=\"fa fa-fw text-danger fa-times-circle me-1\"></i>".truncate($label_appearance,"10","");
    $sticky_score_tally .= "</div>";
    $sticky_score_tally .= "<div class=\"col-2 text-end\">";
    $sticky_score_tally .= "<span id=\"score-appearance-status\"></span>";
    $sticky_score_tally .= "</div>";
    $sticky_score_tally .= "</section>";

    $sticky_score_tally .= "<section class=\"row small\">";
    $sticky_score_tally .= "<div class=\"col-10\">";
    $sticky_score_tally .= "<i id=\"score-icon-flavor-status\" class=\"fa fa-fw text-danger fa-times-circle me-1\"></i>".truncate($label_flavor,"10","");
    $sticky_score_tally .= "</div>";
    $sticky_score_tally .= "<div class=\"col-2 text-end\">";
    $sticky_score_tally .= "<span id=\"score-flavor-status\"></span>";
    $sticky_score_tally .= "</div>";
    $sticky_score_tally .= "</section>";

    if ($beer) {
      $sticky_score_tally .= "<section class=\"row small\">";
      $sticky_score_tally .= "<div class=\"col-10\">";
      $sticky_score_tally .= "<i id=\"score-icon-mouthfeel-status\" class=\"fa fa-fw text-danger fa-times-circle me-1\"></i>".truncate($label_mouthfeel,"10","");
      $sticky_score_tally .= "</div>";
      $sticky_score_tally .= "<div class=\"col-2 text-end\">";
      $sticky_score_tally .= "<span id=\"score-mouthfeel-status\"></span>";
      $sticky_score_tally .= "</div>";
      $sticky_score_tally .= "</section>";
    }

    $sticky_score_tally .= "<section class=\"row small\">";
    $sticky_score_tally .= "<div class=\"col-10\">";
    $sticky_score_tally .= "<i id=\"score-icon-overall-status\" class=\"fa fa-fw text-danger fa-times-circle me-1\"></i>".truncate($label_overall_impression,"10","");
    $sticky_score_tally .= "</div>";
    $sticky_score_tally .= "<div class=\"col-2 text-end\">";
    $sticky_score_tally .= "<span id=\"score-overall-status\"></span>";
    $sticky_score_tally .= "</div>";
    $sticky_score_tally .= "</section>";

    $sticky_score_tally .= "<section class=\"row small\">";
    $sticky_score_tally .= "<div class=\"col-10\">";
    $sticky_score_tally .= "<i id=\"score-icon-consensus-status\" class=\"fa fa-fw text-danger fa-times-circle me-1\"></i>".truncate($label_assigned_score,"10","");
    $sticky_score_tally .= "</div>";
    $sticky_score_tally .= "<div class=\"col-2 text-end\">";
    $sticky_score_tally .= "<span id=\"score-consensus-status\"></span>";
    $sticky_score_tally .= "</div>";
    $sticky_score_tally .= "</section>";

  }

  // Elapsed time
  $sticky_score_tally .= "<p class=\"mt-3 mb-0 pb-1\"><span id=\"elapsed-time-p\"><i class=\"fa fa-clock\"></i> <strong>".$label_elapsed_time.": <span id=\"elapsed-time\"></span></strong></span><br><small id=\"session-end-eval-p\">".$label_auto_log_out." <span id=\"session-end-eval\"></span></small>";
  $sticky_score_tally .= "</p>";

  // 15-minute courtesy warning.
  $sticky_score_tally .= "<p class=\"mt-3 mb-0 pb-1\" id=\"courtesy-alert-warning-15\">";
  $sticky_score_tally .= "<span id=\"courtesy-alert-warning-15-header\"><i class=\"fa fa-exclamation-circle\"></i> <strong>".$label_please_note."<span id=\"elapsed-time\"></strong></span>";
  $sticky_score_tally .= "<br>";
  $sticky_score_tally .= "<small>";
  $sticky_score_tally .= $evaluation_info_071;
  $sticky_score_tally .= "</small>";
  $sticky_score_tally .= "</p>";

  // Show score range and consensus statuses if scored previously
  if ($scored_previously) {
    $sticky_score_tally .= "<p class=\"mt-3 mb-0 pb-1 lh-sm\">";
    $sticky_score_tally .= "<i id=\"scoring-guide-status-icon\" class=\"fa fa-chevron-circle-right\"></i> <span id=\"scoring-guide-status-msg\"><strong>".$label_score_range_status."</strong></span>";
    if (!empty($other_judge_scores)) $sticky_score_tally .= "<br><small>".$other_judge_scores."</small>";
    // if (!empty($other_judge_consensus_scores)) $sticky_score_tally .= "<br><small>".$other_judge_consensus_scores."</small>";
    // if (!empty($my_consensus_score)) $sticky_score_tally .= "<br><small>".$my_consensus_score."</small>";
    $sticky_score_tally .= "</p>";
    $sticky_score_tally .= "<p class=\"mt-3 mb-0 pb-1 lh-sm\">";
    $sticky_score_tally .= sprintf("<i id=\"consensus-status-icon\" class=\"fa fa-chevron-circle-right\"></i> <span id=\"consensus-status-msg\"><strong>%s</strong></span>",$label_consensus_status);
    if (!empty($my_consensus_score)) $sticky_score_tally .= "<br><small>".$my_consensus_score."</small>";
    if (!empty($other_judge_consensus_scores)) $sticky_score_tally .= "<br><small>".$other_judge_consensus_scores."</small>";
    $sticky_score_tally .= "</p>";
  }


  $sticky_score_tally .= "</section>"; // end scoring-guide-status

  $sticky_score_tally .= "</div>\n\n"; // end sticky-score

  

}

else {
  $header_elements .= sprintf("<p class=\"alert alert-danger\"><strong><i class=\"fa fa-exclamation-triangle\"></i> %s</strong> %s</p>",$evaluation_info_013,$evaluation_info_014); 
  $entry_info_html .= "<form class=\"hide-loader-form-submit form-horizontal needs-validation\" name=\"form1\" role=\"form\" action=\"".$base_url."index.php?section=evaluation&amp;go=scoresheet&amp;action=add\" method=\"post\" novalidate>";
  $entry_info_html .= "<div class=\"mb-3 row\">";
  $entry_info_html .= "<div class=\"col-3\">";
  $entry_info_html .= sprintf("<label for=\"entry_number\" class=\"form-label\"><strong>%s</strong></label>",$label_entry_number);
  $entry_info_html .= "</div>";
  $entry_info_html .= "<div class=\"col-4\">";
  $entry_info_html .= "<input id=\"entry-number-input\" name=\"entry_number\" type=\"text\" pattern=\".{6,6}\" maxlength=\"6\" class=\"form-control small\" data-error=\"".$evaluation_info_015."\" required>";
  $entry_info_html .= "<div class=\"help-block small invalid-feedback text-danger\">".$evaluation_info_015."</div>";
  $entry_info_html .= "</div>";
  $entry_info_html .= "<div class=\"col-3\">";
  $entry_info_html .= sprintf("<button class=\"btn btn-sm btn-success\" type=\"submit\">%s</button>",$label_go);
  $entry_info_html .= "</div>";
  $entry_info_html .= "</div>";
  if (isset($_POST['participants'])) $entry_info_html .= "<input type=\"hidden\" name=\"participants\" value=\"".$_POST['participants']."\">";
  $entry_info_html .= "</form>";
  $scoresheet_version = "";
}

// Sub-nav Buttons
$eval_nav_buttons .= "<div class=\"d-print-none mb-3\">";
if ($eval_source == 0) $eval_nav_buttons .= "<a class=\"btn btn-dark me-2\" href=\"".$base_url."index.php?section=admin&amp;go=evaluation&amp;filter=default&amp;view=admin\"><i class=\"fa fa-chevron-circle-left me-2\"></i>".$label_admin.": ".$label_evaluations."</a>";
else $eval_nav_buttons .= "<button class=\"btn btn-dark\" data-bs-toggle=\"modal\" data-bs-target=\"#unsaved-modal\"><i class=\"fa fa-chevron-circle-left me-2\"></i>".$label_judging_dashboard."</button>";
$eval_nav_buttons .= "</div>";
if ($eval_prevent_edit) $header_elements .= sprintf("<p>%s</p>",$header_text_104);
?>
<!-- Unsaved Data Modal -->
<div id="unsaved-modal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="unsaved-modal-label">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h4 class="modal-title" id="unsaved-modal-label">Caution &ndash; Possible Data Loss</h4>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <?php echo sprintf("<p>%s</p><p>%s</p><p>%s</p>",$evaluation_info_073,$evaluation_info_074,$evaluation_info_075); ?>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-dark" data-bs-dismiss="modal"><?php echo $label_close; ?></button>
        <a class="btn btn-primary" onclick="localStorage.clear();" href="<?php echo build_public_url("evaluation","default","default","default",$sef,$base_url,"default"); ?>"><?php echo $label_judging_dashboard; ?></a>
      </div>
    </div><!-- /.modal-content -->
  </div><!-- /.modal-dialog modal-lg -->
</div><!-- /.modal -->
<!-- Load Bootstrap Slider -->
<!-- https://github.com/seiyria/bootstrap-slider -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-slider/11.0.2/bootstrap-slider.min.js"></script>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-slider/11.0.2/css/bootstrap-slider.min.css" />
<script>
var judgeScores = <?php echo json_encode($judge_scores); ?>;
var consensusScores = <?php echo json_encode($consensus_scores); ?>;
var score_range = <?php echo $score_range; ?>;
var consensus_caution = "<?php echo $label_consensus_caution; ?>";
var consensus_caution_text = "<?php echo $evaluation_info_044; ?>";
var consensus_caution_output = "<span><strong class=\"text-danger\">" + consensus_caution + "</strong><br><small><strong class=\"text-danger-emphasis\">" +consensus_caution_text + "</strong></small></span>";
var consensus_match = "<?php echo $label_consensus_match; ?>";
var consensus_match_text = "<?php echo $evaluation_info_045; ?>";
var consensus_match_output = "<span><strong class=\"text-success\">" + consensus_match + "</strong><br><small><strong class=\"text-success-emphasis\">" +consensus_match_text + "</strong></small></span>";
var score_problematic = "<?php echo $label_problematic; ?>";
var score_fair = "<?php echo $label_fair; ?>";
var score_good = "<?php echo $label_good; ?>";
var score_very_good = "<?php echo $label_very_good; ?>";
var score_excellent = "<?php echo $label_excellent; ?>";
var score_outstanding = "<?php echo $label_outstanding; ?>";
var score_range_caution = "<?php echo $label_score_range_caution; ?>";
var score_range_caution_text = "<?php echo $evaluation_info_046; ?>";
var score_range_caution_output = "<span class=\"text-danger\"><strong>" + score_range_caution + "</strong><br><small><strong>" + score_range_caution_text + " " + score_range + ".</strong></small></span>";
var score_range_ok = "<?php echo $label_score_range_ok; ?>";
var score_range_ok_text = "<?php echo $evaluation_info_047; ?>";
var score_range_ok_output = "<span><strong class=\"text-success\">" + score_range_ok + "</strong><br><small><strong class=\"text-success-emphasis\">" + score_range_ok_text + "</strong></small></span>";
</script>
<script src="<?php echo $js_eval_url; ?>"></script>
<script>
$(document).ready(function() {

  $("#courtesy-alert-warning-15").hide();
  $("#warning-indicator-icon").hide();
  $("#score-icon-aroma").hide();
  $("#score-icon-appearance").hide();
  $("#score-icon-flavor").hide();
  $("#score-icon-mouthfeel").hide();
  $("#score-icon-overall").hide();
  $("#appearance-icon-aroma").hide();
  $("#flavor-icon-aroma").hide();
  $("#mouthfeel-icon-aroma").hide();
  $("#overall-icon-aroma").hide();
  
  <?php if ($action == "edit") { ?>
  displayCalc(<?php echo $eval_score; ?>);
  checkScoreRange(<?php echo $eval_score; ?>,judgeScores,score_range,0);
  checkConsensus(consensusScores);
  <?php }?>
  
  $('#show-hide-status-btn').click(function(){
      $('#toggle-icon').toggleClass('fa-chevron-circle-up fa-chevron-circle-down');
  });

  $('#show-hide-aroma-btn').click(function(){
      $('#toggle-icon-aroma').toggleClass('fa-chevron-circle-up fa-chevron-circle-down');
  });

  $('#show-hide-appearance-btn').click(function(){
      $('#toggle-icon-appearance').toggleClass('fa-chevron-circle-up fa-chevron-circle-down');
  });

  $('#show-hide-flavor-btn').click(function(){
      $('#toggle-icon-flavor').toggleClass('fa-chevron-circle-up fa-chevron-circle-down');
  });

  $('#show-hide-mouthfeel-btn').click(function(){
      $('#toggle-icon-mouthfeel').toggleClass('fa-chevron-circle-up fa-chevron-circle-down');
  });

  $('#show-hide-overall-btn').click(function(){
      $('#toggle-icon-overall').toggleClass('fa-chevron-circle-up fa-chevron-circle-down');
  });

  $('#show-hide-flaws-btn').click(function(){
      $('#toggle-icon-flaws').toggleClass('fa-chevron-circle-up fa-chevron-circle-down');
  });

  $('#show-hide-entry-info-btn').click(function(){
      $('#toggle-icon-entry-info').toggleClass('fa-chevron-circle-up fa-chevron-circle-down');
  });

  $("#evalAromaScore").change(function() {

    if ($(this).val() == "") {
      $('#score-icon-aroma-status').attr('class', 'fa fa-fw fa-times-circle text-danger me-1');
      $("#score-icon-aroma").fadeOut('fast');
      $("#score-aroma-status").html("");
    }

    if ($(this).val() > 0) {
      $('#score-icon-aroma-status').attr('class', 'fa fa-fw fa-check-circle text-success me-1');
      $("#score-icon-aroma").fadeIn('fast');
      $("#score-aroma-status").html($(this).val());
    }   

  });

  $("#evalAppearanceScore").change(function() {

    if ($(this).val() == "") {
      $('#score-icon-appearance-status').attr('class', 'fa fa-fw fa-times-circle text-danger me-1');
      $("#score-icon-appearance").fadeOut('fast');
      $("#score-appearance-status").html("");
    }

    if ($(this).val() > 0) {
      $('#score-icon-appearance-status').attr('class', 'fa fa-fw fa-check-circle text-success me-1');
      $("#score-icon-appearance").fadeIn('fast');
      $("#score-appearance-status").html($(this).val());
    }

  });

  $("#evalFlavorScore").change(function() {

    if ($(this).val() == "") {
      $('#score-icon-flavor-status').attr('class', 'fa fa-fw fa-times-circle text-danger me-1');
      $("#score-icon-flavor").fadeOut('fast');
      $("#score-flavor-status").html("");
    }

    if ($(this).val() > 0) {
      $('#score-icon-flavor-status').attr('class', 'fa fa-fw fa-check-circle text-success me-1');
      $("#score-icon-flavor").fadeIn('fast');
      $("#score-flavor-status").html($(this).val());
    }

  });

  $("#evalMouthfeelScore").change(function() {

    if ($(this).val() == "") {
      $('#score-icon-mouthfeel-status').attr('class', 'fa fa-fw fa-times-circle text-danger me-1');
      $("#score-icon-mouthfeel").fadeOut('fast');
      $("#score-mouthfeel-status").html("");
    }

    if ($(this).val() > 0) {
      $('#score-icon-mouthfeel-status').attr('class', 'fa fa-fw fa-check-circle text-success me-1');
      $("#score-icon-mouthfeel").fadeIn('fast');
      $("#score-mouthfeel-status").html($(this).val());
    }

  });

  $("#evalOverallScore").change(function() {

    if ($(this).val() == "") {
      $('#score-icon-overall-status').attr('class', 'fa fa-fw fa-times-circle text-danger me-1');
      $("#score-icon-overall").fadeOut('fast');
      $("#score-overall-status").html("");
    }

    if ($(this).val() > 0) {
      $('#score-icon-overall-status').attr('class', 'fa fa-fw fa-check-circle text-success me-1');
      $("#score-icon-overall").fadeIn('fast');
      $("#score-overall-status").html($(this).val());
    }

  });

  $('#evalFinalScore').on('change input keyup keydown click onmouseout oninput', function() {

    var currentLength = $(this).val().length;
    if (currentLength >= 2) {
      $('#score-icon-consensus-status').attr('class', 'fa fa-fw fa-check-circle text-success me-1');
      $("#score-consensus-status").html($(this).val());
    } else {
      $('#score-icon-consensus-status').attr('class', 'fa fa-fw fa-times-circle text-danger me-1');
      $("#score-consensus-status").html("");
    }

  });

});
</script>

<style type="text/css">

.scoring-guide-bottom-text {
  font-weight: bold;
}

#sticky-score {
  position: -webkit-sticky;
  position: sticky;
  top: 70px;
  z-index: 999;
  width: 250px;
  min-width: 250px;
  max-width: 300px;
  /* font-family: initial !important; */
  font-size: .9em;
}

.section-heading {
  padding-top: 35px;
  margin-top: 35px;
  border-top: 3px solid #cccccc;
}

</style>

<?php
$evalPos = FALSE; 
if ((isset($row_eval['evalPosition'])) && (!empty($row_eval['evalPosition']))) {
  $evalPosition = explode(",", $row_eval['evalPosition']);
  $evalPos = TRUE;
} 
echo $header_elements; 
if (!empty($scoresheet_version)) echo "<h2>".$scoresheet_version."</h2>"; 
echo $eval_nav_buttons;
if ($entry_found) echo $sticky_score_tally;
echo $entry_info_html; 
if ($entry_found) {
?>

<form class="hide-loader-form-submit needs-validation" id="scoresheet-form" name="scoresheet-form" role="form" data-toggle="validator" action="<?php echo $base_url; ?>includes/process.inc.php?section=<?php echo $process_type; ?>&action=<?php echo $action; ?>&view=<?php echo $view; ?>&dbTable=<?php echo $prefix."evaluation"; if ($action == "edit") echo "&id=".$id; ?>" method="post" novalidate>
<input type="hidden" name="token" value ="<?php if (isset($_SESSION['token'])) echo $_SESSION['token']; ?>">
<!-- Provide information about the judge -->
<input type="hidden" name="evalJudgeInfo" value="<?php if ($action == "add") echo $judge_id; else echo $row_eval['evalJudgeInfo']; ?>">
<!-- Type of scoresheet -->
<input type="hidden" name="evalScoresheet" value="<?php echo $judging_scoresheet; ?>">
<!-- User and Entry IDs, if applicable -->
<input type="hidden" name="uid" value="<?php echo $uid; ?>">
<input type="hidden" name="bid" value="<?php echo $uid; ?>">
<input type="hidden" name="eid" value="<?php echo $eid; ?>">
<input type="hidden" name="evalTable" value="<?php echo $filter; ?>">
<!-- Brewer entered special ingredients, etc. -->
<input type="hidden" name="evalSpecialIngredients" value="<?php echo $row_entry_info['brewInfo']; ?>">
<input type="hidden" name="evalStyle" value="<?php echo $row_style['id']; ?>">
<!-- Source of eval form (judge user=1 or admin=0) -->
<input type="hidden" name="evalSource" value="<?php echo $eval_source; ?>">

<div class="mb-3">
    <label class="form-label" for="evalPosition_0"><strong><?php echo $label_ordinal_position; ?></strong></label>
    <div class="row">
      <div class="col-12 col-sm-12 col-md-4 ms-0 ps-0"><input type="number" class="form-control" name="evalPosition_0" min="1" id="evalPosition_0" maxlength="3" size="30" placeholder="<?php echo $label_suggested.": ".($flight_count_info['total_flight_evals']+1); ?>" value="<?php if (($action == "edit") && ($evalPos)) { if (is_numeric($evalPosition[0])) echo $evalPosition[0]; else echo ($flight_count_info['total_flight_evals']+1); } ?>">
        <div id="ordinal-help-position" class="help-block small text-danger"><?php echo $evaluation_info_050; ?></div>
      </div> 
      <div class="col-12 col-sm-12 col-md-1 text-center text-lowercase ps-0 pe-0"><?php echo $label_of; ?></div>
      <div class="col-12 col-sm-12 col-md-4 ms-0 ps-0">
        <input type="number" class="form-control" name="evalPosition_1" min="1" id="evalPosition_1" maxlength="3" size="30" placeholder="<?php echo $label_suggested.": ".$flight_count_info['total_flight_entries']; ?>" value="<?php if (($action == "edit") && ($evalPos)) { if (is_numeric($evalPosition[1])) echo $evalPosition[1]; } ?>">
        <div id="ordinal-help-total" class="help-block small text-danger"><?php echo $evaluation_info_051; ?></div>
      </div>
    </div>
</div>

<?php if (!$nw_cider) { ?>
<div class="mb-3">
  <label class="form-label" for="evalBottle"><strong><?php echo $label_bottle_inspection; ?></strong></label>
  <div class="checkbox">
      <input class="form-check-input" type="checkbox" name="evalBottle" id="evalBottle" value="1" <?php if (($action == "edit") && ($row_eval['evalBottle'] == 1)) echo "checked"; ?>> <?php echo $evaluation_info_052; ?>
  </div>
</div>
<div class="mb-3">
  <label class="form-label" for="evalBottleNotes"><strong><?php echo $label_bottle_inspection_comments; ?></strong></label>
  <input type="text" class="form-control" name="evalBottleNotes" id="evalBottleNotes" maxlength="255" placeholder="" value="<?php if ($action == "edit") echo $row_eval['evalBottleNotes']; ?>">
</div>
<?php } ?>

<?php include (PUB.$scoresheet_form); ?>

<h3 class="section-heading mt-4 pt-4"><?php echo $label_score; ?></h3>

<?php if ((($_SESSION['jPrefsScoresheet'] == 4) || ($sort == 4)) && ($cider)) { ?>
<div class="mb-3">
  <label class="form-label" for="evalOverallScore"><strong><?php echo $label_your_score; ?></strong></label>
  <input type="number" min="5" max="50" name="evalOverallScore" id="evalOverallScore" class="form-control" placeholder="" data-error="<?php echo $evaluation_info_103; ?>" value="<?php if ($action == "edit") echo $row_eval['evalOverallScore']; ?>"required>
  <div class="help-block small"><?php echo $evaluation_info_102; ?></div>
  <div class="help-block small invalid-feedback text-danger"></div>
</div>
<?php } ?>

<div class="mb-3">
  <label class="form-label" for="evalFinalScore"><strong><?php echo $label_assigned_score; ?></strong></label>
  <input type="number" min="5" max="50" name="evalFinalScore" id="evalFinalScore" class="form-control" placeholder="" data-error="<?php echo $evaluation_info_068; ?>" value="<?php if ($action == "edit") echo $row_eval['evalFinalScore']; ?>" onblur="checkConsensus(consensusScores)" required>
  <div class="help-block small"><?php echo $evaluation_info_053; ?></div>
  <div class="help-block small invalid-feedback text-danger"></div>
</div>

<!-- Scoring Guide -->
<label class="form-check-label mb-2" for="evalMiniBOS"><strong>Scoring Guide</strong></label>
<section class="alert bg-secondary-subtle mb-3">
  <div class="row small">
    <div class="col-12 col-sm-12 col-md-6">
        <div id="scoring-guide-bottom-outstanding" class="row">
              <div class="col-12 col-md-5 col-lg-4">
                <strong><?php echo $label_outstanding; ?> (45-50)</strong>
              </div>
              <div class="col-12 col-md-7 col-lg-8">
                <?php echo $descr_outstanding; ?>
              </div>
          </div>
          <div id="scoring-guide-bottom-excellent" class="row">
              <div class="col-12 col-md-5 col-lg-4">
                <strong><?php echo $label_excellent; ?> (38-44)</strong>
              </div>
              <div class="col-12 col-md-7 col-lg-8">
                <?php echo $descr_excellent; ?>
              </div>
          </div>
          <div id="scoring-guide-bottom-v-good" class="row">
              <div class="col-12 col-md-5 col-lg-4">
                <strong><?php echo $label_very_good; ?> (30-37)</strong>
              </div>
              <div class="col-12 col-md-7 col-lg-8">
                <?php echo $descr_very_good; ?>
              </div>
          </div>
      </div>
      <div class="col-12 col-sm-12 col-md-6">
        <div id="scoring-guide-bottom-good" class="row">
              <div class="col-12 col-md-5 col-lg-4">
                <strong><?php echo $label_good; ?> (21-29)</strong>
              </div>
              <div class="col-12 col-md-7 col-lg-8">
                <?php echo $descr_good; ?>
              </div>
          </div>
          <div id="scoring-guide-bottom-fair" class="row">
              <div class="col-12 col-md-5 col-lg-4">
                <strong><?php echo $label_fair; ?> (14-20)</strong>
              </div>
              <div class="col-12 col-md-7 col-lg-8">
                <?php echo $descr_fair; ?>
              </div>
          </div>
          <div id="scoring-guide-bottom-prob" class="row">
              <div class="col-12 col-md-5 col-lg-4">
                <strong><?php echo $label_problematic; ?> (00-13)</strong>
              </div>
              <div class="col-12 col-md-7 col-lg-8">
                <?php echo $descr_problematic; ?>
              </div>
          </div>
      </div>
  </div>
</section>

<!-- Mini-BOS -->
<div class="mb-3">
  <label class="form-check-label" for="evalMiniBOS"><strong><?php echo $label_mini_bos; ?></strong></label>
  <div class="form-check">
    <input class="form-check-input" type="checkbox" name="evalMiniBOS" id="evalMiniBOS" value="1" <?php if (($action == "edit") && ($row_eval['evalMiniBOS'] == 1)) echo "checked"; ?>> <?php echo $evaluation_info_054; ?>
  </div>
</div>

<!-- Minimum Words Warning -->
<div class="mb-3">
    <p class="text-danger" id="min-words-message"></p>
</div>

<!-- Submit -->
<div class="d-grid mb-3">
    <button id="submitForm" class="btn btn-lg btn-primary" type="submit"><?php echo $submit_button_text; ?></button>
</div>

</form>

<!-- Modals -->
<div class="modal fade" id="score-disparity-modal" tabindex="-1" role="dialog" aria-labelledby="score-disparity-modal-label">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h4 class="modal-title" id="score-disparity-modal-label"><?php echo $label_consensus_no_match; ?></h4>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <p><?php echo $evaluation_info_055; ?></p>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-success" data-bs-dismiss="modal"><?php echo $label_ok; ?></button>
      </div>
    </div>
  </div>
</div>

<div class="modal fade" id="score-disparity-submit-modal" tabindex="-1" role="dialog" aria-labelledby="score-disparity-submit-modal-label">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h4 class="modal-title" id="score-disparity-modal-submit-label"><?php echo $label_consensus_no_match; ?></h4>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div></h4>
      </div>
      <div class="modal-body">
        <p><?php echo $evaluation_info_055; ?></p>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-warning" data-bs-dismiss="modal"><?php echo $label_understand; ?></button>
      </div>
    </div>
  </div>
</div>

<div class="modal fade" id="score-floor-modal" tabindex="-1" role="dialog" aria-labelledby="score-floor-modal-label">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h4 class="modal-title" id="score-floor-modal-label"><?php echo $label_score_below_courtesy; ?></h4>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <p><?php echo $evaluation_info_056; ?></p>
        <p><?php echo $evaluation_info_057; ?></p>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-warning" data-bs-dismiss="modal"><?php echo $label_understand; ?></button>
      </div>
    </div>
  </div>
</div>

<div class="modal fade" id="score-ceiling-modal" tabindex="-1" role="dialog" aria-labelledby="score-ceiling-modal-label">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h4 class="modal-title" id="score-ceiling-modal-label"><?php echo $label_score_greater_50; ?></h4>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <p><?php echo $evaluation_info_058; ?></p>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-warning" data-bs-dismiss="modal"><?php echo $label_understand; ?></button>
      </div>
    </div>
  </div>
</div>

<div class="modal fade" id="score-disparity-judges-modal" tabindex="-1" role="dialog" aria-labelledby="score-disparity-judges-modal-label">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h4 class="modal-title" id="score-disparity-judges-modal-label"><?php echo $label_score_out_range; ?></h4>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <p><?php echo $evaluation_info_059; ?></p>
        <p><?php echo "<strong>".$label_score_range.":</strong> ".$score_range; ?></p>
      </div>
      <div class="modal-footer">
        <button id="disparity-button-submit" type="button" class="btn btn-primary" data-bs-dismiss="modal"><?php echo $label_submit; ?></button>
        <button type="button" class="btn btn-dark" data-bs-dismiss="modal"><?php echo $label_cancel; ?></button>
      </div>
    </div>
  </div>
</div>

<?php } ?>

<script src="<?php echo $js_url; ?>save_my_form.min.js"></script>

<script type="text/javascript">
var style_type = <?php echo $row_style['brewStyleType']; ?>;
var edit = <?php if ($action == "edit") echo "true"; else echo "false"; ?>;

$(function() {
  $('#scoresheet-form').saveMyForm();
});

if (edit) {
  localStorage.clear();
  $('#score-icon-aroma-status').attr('class', 'fa fa-fw fa-check-circle text-success me-1');
  $("#score-aroma-status").html("<?php if (isset($row_eval['evalAromaScore'])) echo $row_eval['evalAromaScore']; ?>");
  $('#score-icon-appearance-status').attr('class', 'fa fa-fw fa-check-circle text-success me-1');
  $("#score-appearance-status").html("<?php if (isset($row_eval['evalAppearanceScore'])) echo $row_eval['evalAppearanceScore']; ?>");
  $('#score-icon-flavor-status').attr('class', 'fa fa-fw fa-check-circle text-success me-1');
  $("#score-flavor-status").html("<?php if (isset($row_eval['evalFlavorScore'])) echo $row_eval['evalFlavorScore']; ?>");
  $('#score-icon-mouthfeel-status').attr('class', 'fa fa-fw fa-check-circle text-success me-1');
  $("#score-mouthfeel-status").html("<?php if (isset($row_eval['evalMouthfeelScore'])) echo $row_eval['evalMouthfeelScore']; ?>");
  $('#score-icon-overall-status').attr('class', 'fa fa-fw fa-check-circle text-success me-1');
  $("#score-overall-status").html("<?php if (isset($row_eval['evalOverallScore'])) echo $row_eval['evalOverallScore']; ?>");
  $('#score-icon-consensus-status').attr('class', 'fa fa-fw fa-check-circle text-success me-1');
  $("#score-consensus-status").html("<?php if (isset($row_eval['evalFinalScore'])) echo $row_eval['evalFinalScore']; ?>");
}

</script>

<?php if ((isset($_SESSION['jPrefsMinWords'])) && ($_SESSION['jPrefsMinWords'] > 0)) { ?>
<script type="text/javascript">
var min_words = <?php echo $_SESSION['jPrefsMinWords']; ?>;
var min_wordcount_reached = '<strong class="text-success"><?php echo $evaluation_info_089; ?></strong> <?php echo $evaluation_info_090; ?>';
var min_wordcount_not = '<?php echo $evaluation_info_091; ?>';
var word_count_so_far = '<?php echo $evaluation_info_092; ?>';
</script>

<?php if (($judging_scoresheet == 3) || ($judging_scoresheet == 4)) { ?>
<script type="text/javascript">
    if (edit) var min_words_overall_ok = true;
    else var min_words_overall_ok = false;
    function min_words_ok() {
        $('#submitForm').attr('disabled','disabled');
        if (min_words_overall_ok) {
            $('#submitForm').removeAttr('disabled');
            $('#min-words-message').hide();
        } else {
          $('#min-words-message').show();
          $('#min-words-message').html('<i class="fa fa-lg fa-exclamation-circle me-2"></i><strong><?php echo $evaluation_info_093; ?></strong>');
        }
    }

    $(document).ready(function() {

        $('#min-words-message').hide();
        $('#evalOverallComments').on('keyup keydown click onmouseout oninput', function() {

            var currentWordCount = $('#evalOverallComments').val().match(/\S+/g).length;
            if (currentWordCount >= min_words) {
                min_words_overall_ok = true;
                $('#evalOverallComments-words').html(min_wordcount_reached + currentWordCount);      
            } 

            else {
               min_words_overall_ok = false;
               $('#evalOverallComments-words').html('<strong> ' + min_wordcount_not + min_words + '</strong>');
               if (currentWordCount > 1) $('#evalOverallComments-words').html('<strong>' + min_wordcount_not + min_words + '</strong>. <strong class="text-danger">' + word_count_so_far + currentWordCount + '</strong>');
            }  

            min_words_ok();

        });

    });
</script>
<?php } // end if (($judging_scoresheet == 3) || ($judging_scoresheet == 4)) ?>

<?php if ((($judging_scoresheet == 1) || ($judging_scoresheet == 2)) && ((isset($_SESSION['jPrefsMinWords'])) && ($_SESSION['jPrefsMinWords'] > 0))) { 

    if (($cider) || ($mead)) {
      $comment_fields = array(
        "aroma" => "#evalAromaComments",
        "appearance" => "#evalAppearanceComments",
        "flavor" => "#evalFlavorComments",
        "overall" => "#evalOverallComments"
      );
    } else {
      $comment_fields = array(
          "aroma" => "#evalAromaComments",
          "appearance" => "#evalAppearanceComments",
          "flavor" => "#evalFlavorComments",
          "mouthfeel" => "#evalMouthfeelComments",
          "overall" => "#evalOverallComments"
      );
    }

?>
<script type="text/javascript">

    if (edit) {
      var min_words_aroma_ok = true;
      var min_words_appearance_ok = true;
      var min_words_flavor_ok = true;
      var min_words_mouthfeel_ok = true;  
      var min_words_overall_ok = true;
    } else {
      var min_words_aroma_ok = false;
      var min_words_appearance_ok = false;
      var min_words_flavor_ok = false;
      if ((style_type == 2) || (style_type == 3)) var min_words_mouthfeel_ok = true;
      else var min_words_mouthfeel_ok = false;
      var min_words_overall_ok = false;
    }

    function min_words_ok() {
        $('#submitForm').attr('disabled','disabled');
        if ((min_words_aroma_ok) && (min_words_appearance_ok) && (min_words_flavor_ok) && (min_words_mouthfeel_ok) && (min_words_overall_ok)) {
            $('#submitForm').removeAttr('disabled');
            $('#min-words-message').hide();
        } else {
          $('#min-words-message').show();
          $('#min-words-message').html('<i class="fa fa-lg fa-exclamation-circle"></i> <strong><?php echo $evaluation_info_094; ?></strong>');
        }

    }

    $(document).ready(function() {

        $('#min-words-message').hide();

        <?php foreach ($comment_fields as $key => $value) { 
            $value_words = $value."-words";
            $key_ok = "min_words_".$key."_ok";
        ?>

        $('<?php echo $value; ?>').on('keyup keydown click onmouseout oninput', function() { //evalFinalScore

            var currentWordCount_<?php echo $key; ?> = $('<?php echo $value; ?>').val().match(/\S+/g).length;

            if (currentWordCount_<?php echo $key; ?> >= min_words) {
                <?php echo $key_ok; ?> = true;
                $('<?php echo $value_words; ?>').html(min_wordcount_reached + currentWordCount_<?php echo $key; ?>);      
            } 

            else {
               <?php echo $key_ok; ?> = false;
               $('<?php echo $value_words; ?>').html('<strong> ' + min_wordcount_not + min_words + '</strong>');
               if (currentWordCount_<?php echo $key; ?> > 1) $('<?php echo $value_words; ?>').html('<strong>' + min_wordcount_not +  min_words + '</strong>. <strong class="text-danger">' + word_count_so_far + currentWordCount_<?php echo $key; ?> + '</strong>.');
            }

            min_words_ok();        

        });
            
        <?php } // end foreach ?>

    });
</script>
<?php } // end if ((($judging_scoresheet == 1) || ($judging_scoresheet == 2)) && ((isset($_SESSION['jPrefsMinWords'])) && ($_SESSION['jPrefsMinWords'] > 0))) ?>
<?php } // End if ((isset($_SESSION['jPrefsMinWords'])) && ($_SESSION['jPrefsMinWords'] > 0)) ?>

<script>
  $(document).ready(function() {
    initCollapseStateManager({
      collapseElementId: "scoring-guide-status",
      toggleButtonId: "show-hide-status-btn",
      toggleIconId: "toggle-icon",
      iconClassExpanded: "fa-chevron-circle-up",
      iconClassCollapsed: "fa-chevron-circle-down"
    });
  });
</script>