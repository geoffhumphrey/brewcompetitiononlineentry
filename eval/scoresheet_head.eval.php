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
if (!empty($row_eval['evalMiniBOS'])) $head_miniBOS = TRUE;
if (($head_ordinal) || ($head_miniBOS)) $head_rt_col = "col-xs-6";
?>

<link rel="stylesheet" type="text/css" href="<?php echo $base_url; ?>eval/scoresheet_output.css">
<!-- Header Row -->
<div class="row">
    <div class="col col-lg-2 col-md-2 col-sm-2 col-xs-2">
        <p><?php if (!$nw_cider) { ?><img style="max-width: 60px; min-width: 40px;" src="<?php echo $base_url."images/bjcp_logo.jpg"; ?>"><?php } ?></p>
    </div>
    <div class="col col-lg-8 col-md-8 col-sm-8 col-xs-8">
        <h2 class="text-center" style="margin:0; padding:0;"><?php echo $label_scoresheet; ?></h2>
        <div class="row">
            <div class="col col-xs-5 text-center">
                <h5>
                    <?php 
                    echo "<br>".$label_entry_number.": ".$entry_id; 
                    if (!empty($row_eval['evalFinalScore'])) echo "<br>".$label_assigned_score.": ".$row_eval['evalFinalScore']." **"; 
                    ?>
                </h5>
            </div>
            <?php if (($head_ordinal) || ($head_miniBOS)) { ?>
            <div class="col col-xs-7 text-center">
                <h5>
                    <?php 
                    if ((isset($row_eval['evalPosition'])) && (!empty($row_eval['evalPosition']))) {
                        $evalPosition = explode(",",$row_eval['evalPosition']);
                        echo "<br>".$label_ordinal_position.": ".$evalPosition[0]." of ".$evalPosition[1];
                    }
                    if (!empty($row_eval['evalMiniBOS'])) echo "<br><i class=\"fa fa-check-square-o\"></i> This entry advanced to a mini-BOS round";
                    ?>
                </h5>
            </div>
            <?php } ?>
        </div>

    </div>
    <div class="col col-lg-2 col-md-2 col-sm-2 col-xs-2">
        <p class="pull-right"><img style="max-width: 60px; min-width: 40px;" src="<?php if ($nw_cider) echo "https://www.nwcider.com/wp-content/themes/nwcider/assets/images/nw-cider-logo-2x.png"; else echo $base_url."images/aha_logo.jpg"; ?>"></p>
    </div>
</div><!-- ./row (header) -->
<!-- Entry Info Row -->
<div class="row" style="padding-top: 10px;">
    <div class="col col-lg-6 col-md-6 col-sm-6 col-xs-6">
        <!-- judge name, ID, email -->
        <div class="row">
            <div class="col col-lg-2 col-md-3 col-sm-4 col-xs-4">
            <strong><?php echo $label_judge; ?>:</strong>
            </div>
            <div class="col col-lg-10 col-md-9 col-sm-8 col-xs-8">
            <?php echo $row_judge['brewerFirstName']." ".$row_judge['brewerLastName']; ?>
            </div>
        </div><!-- /row for judge name -->
        <div class="row">
            <div class="col col-lg-2 col-md-3 col-sm-4 col-xs-4">
            <strong><?php echo $label_bjcp_id; ?>:</strong>
            </div>
            <div class="col col-lg-10 col-md-9 col-sm-8 col-xs-8">
            <?php echo $row_judge['brewerJudgeID']; ?>
            </div>
        </div><!-- /row for judge ID -->
        <div class="row">
            <div class="col col-lg-2 col-md-3 col-sm-4 col-xs-4">
            <strong><?php echo $label_bjcp_rank; ?>:</strong>
            </div>
            <div class="col col-lg-10 col-md-9 col-sm-8 col-xs-8">
            <?php 
            echo str_replace(",", ", ", $row_judge['brewerJudgeRank']);
            if ($row_judge['brewerJudgeMead'] == "Y") echo "<br>Certified Mead Judge";
            if ($row_judge['brewerJudgeCider'] == "Y") echo "<br>Certified Cider Judge";
            ?>
            </div>
        </div><!-- /row for judge rank -->
        <div class="row">
            <div class="col col-lg-2 col-md-3 col-sm-4 col-xs-4">
            <strong><?php echo $label_email; ?>:</strong>
            </div>
            <div class="col col-lg-10 col-md-9 col-sm-8 col-xs-8">
            <?php echo $row_judge['brewerEmail']; ?>
            </div>
        </div><!-- /row for judge email -->
    </div>
    <div class="col col-lg-6 col-md-6 col-sm-6 col-xs-6">
        <div class="row">
            <div class="col col-lg-2 col-md-3 col-sm-4 col-xs-3">
            <strong><?php echo $label_category; ?>:</strong>
            </div>
            <div class="col col-lg-10 col-md-9 col-sm-8 col-xs-9">
            <?php echo $row_style['brewStyle']." (".style_number_const($row_style['brewStyleGroup'],$row_style['brewStyleNum'],$_SESSION['style_set_display_separator'],0).")"; ?>
            </div>
        </div>
        <?php if (!empty($evalSpecialIngredients)) { ?>
         <div class="row">
            <div class="col col-lg-2 col-md-3 col-sm-4 col-xs-3">
            <strong><?php if (($_SESSION['prefsStyleSet'] == "BJCP2021") && ($row_style['brewStyleGroup'] == "2") && ($row_style['brewStyleNum'] == "A")) echo $label_regional_variation; else echo $label_required_info; ?></span>:</strong>
            </div>
            <div class="col col-lg-10 col-md-9 col-sm-8 col-xs-9">
            <?php echo $evalSpecialIngredients; ?>
            </div>
        </div>
        <?php } ?>
        <div class="row">
            <div class="col col-lg-2 col-md-3 col-sm-4 col-xs-3">
            <strong><?php echo $label_bottle_inspection; ?></span>:</strong>
            </div>
            <div class="col col-lg-10 col-md-9 col-sm-8 col-xs-9">
            <span class="fa fa-fw <?php if ($row_eval['evalBottle'] == 1) echo "fa-check-square-o"; else echo "fa-square-o"; ?>"></span> Appropriate size, cap, fill level, label removal, etc.
            <?php if (!empty($row_eval['evalBottleNotes'])) echo "<br><em>".$row_eval['evalBottleNotes']."</em>"; ?>
            </div>
        </div>
        <?php if (!empty($evalOtherNotes)) { ?>
        <div class="row">
            <div class="col col-lg-2 col-md-3 col-sm-4 col-xs-4">
            <strong><?php echo $label_notes; ?>:</strong>
            </div>
            <div class="col col-lg-10 col-md-9 col-sm-8 col-xs-8">
            <?php echo $evalOtherNotes; ?>
            </div>
        </div>
        <?php } ?>
    </div>
</div>
<!-- ./Entry Info Row -->