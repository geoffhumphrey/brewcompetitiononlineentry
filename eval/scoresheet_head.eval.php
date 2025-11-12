<?php 

$beer = FALSE;
$cider = FALSE;
$mead = FALSE;
$nw_cider = FALSE;
$anonymous_eval = TRUE;
$scoresheet_type = "";
include (EVALS.'descriptors.eval.php');

if ($row_style['brewStyleType'] == 2) {
  $cider = TRUE;
  $scoresheet_type = "Cider";
  $label_scoresheet = $label_cider_scoresheet;
}

elseif ($row_style['brewStyleType'] == 3) {
  $mead = TRUE;
  $scoresheet_type = "Mead";
  $label_scoresheet = $label_mead_scoresheet;
}

else {
  $beer = TRUE;
  $scoresheet_type = "Beer";
  $label_scoresheet = $label_beer_scoresheet;
}

if (($cider) && ($row_eval['evalScoresheet'] == 4)) $nw_cider = TRUE;

if ($_SESSION['prefsDisplaySpecial'] == "E") $entry_id = $row_entry_info['id'];
else $entry_id = $row_entry_info['brewJudgingNumber'];
$entry_id = sprintf("%06s",$entry_id);
$score = ($row_eval['evalAromaScore'] + $row_eval['evalAppearanceScore'] + $row_eval['evalFlavorScore'] + $row_eval['evalMouthfeelScore'] + $row_eval['evalOverallScore']);
$evalSpecialIngredients = str_replace("^", " ", $row_eval['evalSpecialIngredients']);
$evalOtherNotes = $row_eval['evalOtherNotes'];

$head_ordinal = FALSE;
$head_miniBOS = FALSE;
$head_rt_col = "col-xs-12";

if (!empty($row_eval['evalPosition'])) $head_ordinal = TRUE;
if ((!empty($row_eval['evalMiniBOS'])) || (!empty($row_eval['scoreMiniBOS']))) $head_miniBOS = TRUE;
if (($head_ordinal) || ($head_miniBOS)) $head_rt_col = "col-xs-6";

$show_rank = TRUE;

$rank = str_replace(",", ", ", $row_judge['brewerJudgeRank']);
if ($nw_cider) {
    $rank = str_replace("Non-BJCP,", "", $row_judge['brewerJudgeRank']);
    $rank = str_replace("Non-BJCP", "", $row_judge['brewerJudgeRank']);
}

if (($nw_cider) && (empty($rank))) $show_rank = FALSE;

$rank = rtrim($rank,",");
$rank = ltrim($rank,",");
$rank = trim($rank);

if ($row_judge['brewerJudgeMead'] == "Y") {
    if (empty($rank)) $rank .= "Certified Mead Judge";
    else $rank .= "<br>Certified Mead Judge";
}

if ($row_judge['brewerJudgeCider'] == "Y") {
    if (empty($rank)) $rank .= "Certified Cider Judge";
    else $rank .= "<br>Certified Cider Judge";
}

$mhp_qr_data = "";

/**
 * For MHP QR Code (see email from mhpsecretary@gmail.com - July 6, 2025)
 * ---
 * Entrant => entrant_name
 * Co-entrant(s) => co_entrant
 * Competition Name => competition_name
 * Competition Year => competition_year
 * Entry Style => style_id
 *             => style_name
 * Judge Name => judge_name
 * Judge Rank => judge_rank
 * Judge MHP => judge_mhp
 * Score Sheet 1 Score => judge_score
 * Consensus Score => consensus_score
 * Competition Site (Location?) => competition_location
 * Competition BJCP ID => competition_bjcp_id
 * Competition Instance ID => ??
 */

/*
$judge_mhp = "";
if (!empty($row_judge['brewerMHP'])) $judge_mhp = $row_judge['brewerMHP'];

$competition_bjcp_id = "";
if (!empty($_SESSION['contestID'])) $competition_bjcp_id = $_SESSION['contestID'];

$competition_location = "";
if (!empty($_SESSION['contestHostLocation'])) $competition_location = $_SESSION['contestHostLocation'];

$mhp_rank = explode(",",$row_judge['brewerJudgeRank']);

$eval_entrant = brewer_info($row_entry_info['brewBrewerID']);
$eval_entrant = explode("^",$eval_entrant);

$mhp_qr_data = array(
    "entrant_name" => $row_entry_info['brewBrewerFirstName']." ".$row_entry_info['brewBrewerLastName'],
    "entrant_mhp" => $eval_entrant[5],
    "co_entrant" => $row_entry_info['brewCoBrewer'],
    "competition_name" => $_SESSION['contestName'],
    "competition_year" => getTimeZoneDateTime($_SESSION['prefsTimeZone'], $row_eval['evalInitialDate'], $_SESSION['prefsDateFormat'], $_SESSION['prefsTimeFormat'], "short", "year"),
    "style_id" => $row_style['brewStyleGroup'].$row_style['brewStyleNum'],
    "style_name" => $row_style['brewStyle'],
    "judge_name" => $row_judge['brewerFirstName']." ".$row_judge['brewerLastName'],
    "judge_rank" => $mhp_rank[0],
    "judge_mhp" => $judge_mhp,
    "judge_score" => $score,
    "consensus_score" => $row_eval['evalFinalScore'],
    "competition_location" => $competition_location,
    "competition_bjcp_id" => $competition_bjcp_id
);

$mhp_qr_data = json_encode($mhp_qr_data, JSON_NUMERIC_CHECK);
*/

?>

<link rel="stylesheet" type="text/css" href="<?php echo $css_url; ?>scoresheet_output.css">

<!-- Header Row -->
<h1 class="text-center" style="margin:0; padding:0; font-weight: bolder;"><?php echo $label_scoresheet; ?><br><small><?php echo $_SESSION['contestName']; ?></small></h1>
<div class="text-center">
    <p style="margin: 5px 0; padding:0;" >
        <?php 
        echo sprintf("%s: %s", $label_recorded, getTimeZoneDateTime($_SESSION['prefsTimeZone'], $row_eval['evalInitialDate'], $_SESSION['prefsDateFormat'], $_SESSION['prefsTimeFormat'], "short", "date-time")); 
        if ((isset($row_eval['evalUpdatedDate'])) && ($row_eval['evalUpdatedDate'] != $row_eval['evalInitialDate'])) echo sprintf("<br>%s: %s", $label_updated, getTimeZoneDateTime($_SESSION['prefsTimeZone'], $row_eval['evalUpdatedDate'], $_SESSION['prefsDateFormat'], $_SESSION['prefsTimeFormat'], "short", "date-time"));
        ?>  
    </p>
</div>
<div class="row" style="margin-top: 10px; margin-bottom: 5px;">
    <div class="col col-xs-2"></div>
    <div class="col col-xs-5">
        <div class="center-block" style="border: 1px solid #000; border-radius: 5px; min-width: 200px; max-width: 250px; padding: 0;">
            <p class="text-center" style="font-size: 1.8em; padding: 5px; margin: 0;"><strong><?php if (!empty($row_eval['evalFinalScore'])) echo "<span style=\"font-size: .7em;\">".$label_assigned_score.":</span> ".$row_eval['evalFinalScore']; ?></strong></p>
        </div>
    </div>
    <div class="col col-xs-5">
        <p style="font-size: 1.2em;">
            <strong>
            <?php 
            echo $label_entry_number.": ".$entry_id; 
            if (($head_ordinal) && ((isset($row_eval['evalPosition'])) && (!empty($row_eval['evalPosition'])))) {
                $evalPosition = explode(",",$row_eval['evalPosition']);
                if ((isset($evalPosition[0])) && (isset($evalPosition[1]))) echo "<br>".$label_ordinal_position.": ".$evalPosition[0]." of ".$evalPosition[1];
            }
            if (($head_miniBOS) && (!empty($row_eval['evalMiniBOS']))) echo "<br><i class=\"fa fa-check-square-o\"></i> This entry advanced to a mini-BOS round";
            ?>
            </strong>
        </p>
    </div>
    <div class="col col-xs-2"></div>
</div>

<!-- Entry Info Row -->
<div class="row" style="padding-top: 10px;">
    <div class="col col-lg-6 col-md-6 col-sm-12 col-xs-12">
        <!-- judge name, ID, email -->
        <div class="row">
            <div class="col col-lg-5 col-md-4 col-sm-3 col-xs-3">
            <strong><?php echo $label_judge; ?>:</strong>
            </div>
            <div class="col col-lg-7 col-md-8 col-sm-9 col-xs-9">
            <?php echo $row_judge['brewerFirstName']." ".$row_judge['brewerLastName']; ?>
            </div>
        </div><!-- /row for judge name -->
        <?php if (strpos($row_judge['brewerJudgeRank'],"Non-BJCP") === false) { ?>
        <div class="row">
            <div class="col col-lg-5 col-md-4 col-sm-3 col-xs-3">
            <strong><?php echo $label_bjcp_id; ?>:</strong>
            </div>
            <div class="col col-lg-7 col-md-8 col-sm-9 col-xs-9">
            <?php echo $row_judge['brewerJudgeID']; ?>
            </div>
        </div><!-- /row for judge ID -->
        <?php } if ($show_rank) { ?>
        <div class="row">
            <div class="col col-lg-5 col-md-4 col-sm-3 col-xs-3">
            <strong><?php if ($nw_cider) echo $label_designations; else echo $label_bjcp_rank; ?>:</strong>
            </div>
            <div class="col col-lg-7 col-md-8 col-sm-9 col-xs-9">
            <?php echo $rank; ?>
            </div>
        </div><!-- /row for judge rank -->
        <?php } ?>
        <?php if (!empty($row_judge['brewerMHP'])) { ?>
        <div class="row">
            <div class="col col-lg-5 col-md-4 col-sm-3 col-xs-3"><strong>MHP#:</strong></div>
            <div class="col col-lg-7 col-md-8 col-sm-9 col-xs-9"><?php echo $row_judge['brewerMHP']; ?></div>
        </div>
        <?php } ?>
        <div class="row">
            <div class="col col-lg-5 col-md-4 col-sm-3 col-xs-3">
            <strong><?php echo $label_email; ?>:</strong>
            </div>
            <div class="col col-lg-7 col-md-8 col-sm-9 col-xs-9">
            <?php echo $row_judge['brewerEmail']; ?>
            </div>
        </div><!-- /row for judge email -->
    </div>
    <div class="col col-lg-6 col-md-6 col-sm-12 col-xs-12">
        <div class="row">
            <div class="col col-lg-5 col-md-4 col-sm-3 col-xs-3">
            <strong><?php echo $label_category; ?>:</strong>
            </div>
            <div class="col col-lg-7 col-md-8 col-sm-9 col-xs-9">
            <?php echo $row_style['brewStyle']." (".style_number_const($row_style['brewStyleGroup'],$row_style['brewStyleNum'],$_SESSION['style_set_display_separator'],0).")"; ?>
            </div>
        </div>
        <?php if (!empty($evalSpecialIngredients)) { ?>
         <div class="row">
            <div class="col col-lg-5 col-md-4 col-sm-3 col-xs-3">
            <strong><?php if ((($_SESSION['prefsStyleSet'] == "BJCP2021") || ($_SESSION['prefsStyleSet'] == "BJCP2021")) && ($row_style['brewStyleGroup'] == "2") && ($row_style['brewStyleNum'] == "A")) echo $label_regional_variation; else echo $label_required_info; ?></span>:</strong>
            </div>
            <div class="col col-lg-7 col-md-8 col-sm-9 col-xs-9">
            <?php echo $evalSpecialIngredients; ?>
            </div>
        </div>
        <?php } ?>
        <?php if (!$nw_cider) { ?>
        <div class="row">
            <div class="col col-lg-5 col-md-4 col-sm-3 col-xs-3">
            <strong><?php echo $label_bottle_inspection; ?></span>:</strong>
            </div>
            <div class="col col-lg-7 col-md-8 col-sm-9 col-xs-9">
            <span class="fa fa-fw <?php if ($row_eval['evalBottle'] == 1) echo "fa-check-square-o"; else echo "fa-square-o"; ?>"></span> Appropriate size, cap, fill level, label removal, etc.
            <?php if (!empty($row_eval['evalBottleNotes'])) echo "<br><em>".$row_eval['evalBottleNotes']."</em>"; ?>
            </div>
        </div>
        <?php if (!empty($evalOtherNotes)) { ?>
        <div class="row">
            <div class="col col-lg-5 col-md-4 col-sm-3 col-xs-3">
            <strong><?php echo $label_notes; ?>:</strong>
            </div>
            <div class="col col-lg-7 col-md-8 col-sm-9 col-xs-9">
            <?php echo $evalOtherNotes; ?>
            </div>
        </div>
        <?php } ?>
        <?php } ?>
    </div>
</div>
<!-- ./Entry Info Row -->