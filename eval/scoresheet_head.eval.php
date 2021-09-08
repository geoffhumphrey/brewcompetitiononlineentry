<?php 
$beer = FALSE;
$cider = FALSE;
$mead = FALSE;
$anonymous_eval = TRUE;
$scoresheet_type = "";
include (EVALS.'descriptors.eval.php');

if ($row_style['brewStyleType'] == 2) {
  $cider = TRUE;
  $scoresheet_type = "Cider";
}

elseif ($row_style['brewStyleType'] == 3) {
  $mead = TRUE;
  $scoresheet_type = "Mead";
}

else {
  $beer = TRUE;
  $scoresheet_type = "Beer";
}

if ($_SESSION['prefsDisplaySpecial'] == "E") $entry_id = $row_entry_info['id'];
else $entry_id = $row_entry_info['brewJudgingNumber'];
$entry_id = sprintf("%06s",$entry_id);
$score = ($row_eval['evalAromaScore'] + $row_eval['evalAppearanceScore'] + $row_eval['evalFlavorScore'] + $row_eval['evalMouthfeelScore'] + $row_eval['evalOverallScore']);
$evalSpecialIngredients = str_replace("^", " ", $row_eval['evalSpecialIngredients']);
$evalOtherNotes = $row_eval['evalOtherNotes'];

/*
$notes_max_length = 65;
if ((!empty($row_eval['evalSpecialIngredients'])) && (strlen($row_eval['evalSpecialIngredients']) > $notes_max_length)) $evalSpecialIngredients = truncate($row_eval['evalSpecialIngredients'],$notes_max_length,"...");
if ((!empty($row_eval['evalOtherNotes'])) && (strlen($row_eval['evalOtherNotes']) > $notes_max_length)) $evalOtherNotes = truncate($row_eval['evalOtherNotes'],$notes_max_length,"...");
*/

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
        <p><img style="max-width: 60px; min-width: 40px;" src="<?php echo $base_url."images/bjcp_logo.jpg"; ?>"></p>
    </div>
    <div class="col col-lg-8 col-md-8 col-sm-8 col-xs-8">
        <h2 class="text-center" style="margin:0; padding:0;"><?php if (!empty($scoresheet_type)) echo $scoresheet_type." "; ?>Scoresheet</h2>
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
        <p class="pull-right"><img style="max-width: 60px; min-width: 40px;" src="<?php echo $base_url."images/aha_logo.jpg"; ?>"></p>
    </div>
</div><!-- ./row (header) -->
<!-- Entry Info Row -->
<div class="row" style="padding-top: 10px;">
    <div class="col col-lg-6 col-md-6 col-sm-6 col-xs-6">
        <!-- judge name, ID, email -->
        <div class="row">
            <div class="col col-lg-2 col-md-3 col-sm-4 col-xs-3">
            <strong>Judge<span class="hidden-sm hidden-xs"> Name</span>:</strong>
            </div>
            <div class="col col-lg-10 col-md-9 col-sm-8 col-xs-9">
            <?php echo $row_judge['brewerFirstName']." ".$row_judge['brewerLastName']; ?>
            </div>
        </div><!-- /row for judge name -->
        <div class="row">
            <div class="col col-lg-2 col-md-3 col-sm-4 col-xs-3">
            <strong>BJCP ID:</strong>
            </div>
            <div class="col col-lg-10 col-md-9 col-sm-8 col-xs-9">
            <?php echo $row_judge['brewerJudgeID']; ?>
            </div>
        </div><!-- /row for judge ID -->
        <div class="row">
            <div class="col col-lg-2 col-md-3 col-sm-4 col-xs-3">
            <strong>BJCP Rank:</strong>
            </div>
            <div class="col col-lg-10 col-md-9 col-sm-8 col-xs-9">
            <?php echo str_replace(",", ", ", $row_judge['brewerJudgeRank']); ?>
            </div>
        </div><!-- /row for judge rank -->
        <div class="row">
            <div class="col col-lg-2 col-md-3 col-sm-4 col-xs-3">
            <strong>Email:</strong>
            </div>
            <div class="col col-lg-10 col-md-9 col-sm-8 col-xs-9">
            <?php echo $row_judge['brewerEmail']; ?>
            </div>
        </div><!-- /row for judge email -->
    </div>
    <div class="col col-lg-6 col-md-6 col-sm-6 col-xs-6">
        <div class="row">
            <div class="col col-lg-2 col-md-3 col-sm-4 col-xs-3">
            <strong>Category:</strong>
            </div>
            <div class="col col-lg-10 col-md-9 col-sm-8 col-xs-9">
            <?php echo $row_style['brewStyle']." (".style_number_const($row_style['brewStyleGroup'],$row_style['brewStyleNum'],$_SESSION['style_set_display_separator'],0).")"; ?>
            </div>
        </div>
        <?php if (!empty($evalSpecialIngredients)) { ?>
         <div class="row">
            <div class="col col-lg-2 col-md-3 col-sm-4 col-xs-3">
            <strong>Special Ing<span class="hidden-sm hidden-xs">redients</span>:</strong>
            </div>
            <div class="col col-lg-10 col-md-9 col-sm-8 col-xs-9">
            <?php echo $evalSpecialIngredients; ?>
            </div>
        </div>
        <?php } ?>
        <div class="row">
            <div class="col col-lg-2 col-md-3 col-sm-4 col-xs-3">
            <strong>Bottle<span class="hidden-sm hidden-xs"> Inspection</span>:</strong>
            </div>
            <div class="col col-lg-10 col-md-9 col-sm-8 col-xs-9">
            <span class="fa fa-fw <?php if ($row_eval['evalBottle'] == 1) echo "fa-check-square-o"; else echo "fa-square-o"; ?>"></span> Appropriate size, cap, fill level, label removal, etc.
            <?php if (!empty($row_eval['evalBottleNotes'])) echo "<br><em>".$row_eval['evalBottleNotes']."</em>"; ?>
            </div>
        </div>
        <?php if (!empty($evalOtherNotes)) { ?>
        <div class="row">
            <div class="col col-lg-2 col-md-3 col-sm-4 col-xs-4">
            <strong>Notes:</strong>
            </div>
            <div class="col col-lg-10 col-md-9 col-sm-8 col-xs-8">
            <?php echo $evalOtherNotes; ?>
            </div>
        </div>
        <?php } ?>
    </div>
</div>
<!-- ./Entry Info Row -->