<?php

// Redirect if directly accessed without authenticated session
if ((!isset($_SESSION['loginUsername'])) || ((isset($_SESSION['loginUsername'])) && (!isset($base_url)))) {
    $redirect = "../../403.php";
    $redirect_go_to = sprintf("Location: %s", $redirect);
    header($redirect_go_to);
    exit();
}

?>

<!-- Aroma -->
<h5>Aroma<span class="pull-right"><?php echo $row_eval['evalAromaScore']; ?>/12</span></h5>
<p><?php echo $row_eval['evalAromaChecklist']; ?></p>
<?php if (!empty($row_eval['evalAromaChecklistDesc'])) { ?>
<p><?php echo $row_eval['evalAromaChecklistDesc']; ?></p>
<?php } ?>
<p><?php echo $row_eval['evalAromaComments']; ?></p>
<!-- Appearance -->
<h5>Appearance<span class="pull-right"><?php echo $row_eval['evalAppearanceScore']; ?>/3</span></h5>
<p><?php echo $row_eval['evalAppearanceChecklist']; ?></p>
<?php if (!empty($row_eval['evalAppearanceChecklistDesc'])) { ?>
<p><?php echo $row_eval['evalAppearanceChecklistDesc']; ?></p>
<?php } ?>
<p><?php echo $row_eval['evalAppearanceComments']; ?></p>
<!-- Flavor -->
<h5>Flavor<span class="pull-right"><?php echo $row_eval['evalFlavorScore']; ?>/20</span></h5>
<p><?php echo $row_eval['evalFlavorChecklist']; ?></p>
<?php if (!empty($row_eval['evalFlavorChecklistDesc'])) { ?>
<p><?php echo $row_eval['evalFlavorChecklistDesc']; ?></p>
<?php } ?>
<p><?php echo $row_eval['evalFlavorComments']; ?></p>
<!-- Mouthfeel -->
<h5>Mouthfeel<span class="pull-right"><?php echo $row_eval['evalMouthfeelScore']; ?>/5</span></h5>
<p><?php echo $row_eval['evalMouthfeelChecklist']; ?></p>
<?php if (!empty($row_eval['evalMouthfeelChecklistDesc'])) { ?>
<p><?php echo $row_eval['evalMouthfeelChecklistDesc']; ?></p>
<?php } ?>
<p><?php echo $row_eval['evalMouthfeelComments']; ?></p>
<!-- Overall Impression -->
<h5>Overall Impression<span class="pull-right"><?php echo $row_eval['evalOverallScore']; ?>/10</span></h5>
<p><?php echo $row_eval['evalOverallComments']; ?></p>
<div class="row" style="padding: 20px 0 0 0;">
	<div class="col col-lg-6 col-md-6 col-sm-6 col-xs-6">
    	<div class="row">
            <div class="col col-lg-4 col-md-4 col-sm-4 col-xs-4">
            <strong><?php echo $label_style_accuracy; ?></strong>
            </div>
            <div class="col col-lg-8 col-md-8 col-sm-8 col-xs-8">
            <?php echo $label_low; ?> <span class="fa <?php if ($row_eval['evalStyleAccuracy'] == 1) echo "fa-check-square-o"; else echo "fa-square-o"; ?>"></span>&nbsp;&nbsp;&nbsp;
            <span class="fa <?php if ($row_eval['evalStyleAccuracy'] == 2) echo "fa-check-square-o"; else echo "fa-square-o"; ?>"></span>&nbsp;&nbsp;&nbsp;
            <span class="fa <?php if ($row_eval['evalStyleAccuracy'] == 3) echo "fa-check-square-o"; else echo "fa-square-o"; ?>"></span>&nbsp;&nbsp;&nbsp;
            <span class="fa <?php if ($row_eval['evalStyleAccuracy'] == 4) echo "fa-check-square-o"; else echo "fa-square-o"; ?>"></span>&nbsp;&nbsp;&nbsp;
            <span class="fa <?php if ($row_eval['evalStyleAccuracy'] == 5) echo "fa-check-square-o"; else echo "fa-square-o"; ?>"></span> <?php echo $label_high; ?>
            </div>
        </div>
        <div class="row">
            <div class="col col-lg-4 col-md-4 col-sm-4 col-xs-4">
            <strong><?php echo $label_tech_merit; ?></strong>
            </div>
            <div class="col col-lg-8 col-md-8 col-sm-8 col-xs-8">
            <?php echo $label_low; ?> <span class="fa <?php if ($row_eval['evalTechMerit'] == 1) echo "fa-check-square-o"; else echo "fa-square-o"; ?>"></span>&nbsp;&nbsp;&nbsp;
            <span class="fa <?php if ($row_eval['evalTechMerit'] == 2) echo "fa-check-square-o"; else echo "fa-square-o"; ?>"></span>&nbsp;&nbsp;&nbsp;
            <span class="fa <?php if ($row_eval['evalTechMerit'] == 3) echo "fa-check-square-o"; else echo "fa-square-o"; ?>"></span>&nbsp;&nbsp;&nbsp;
            <span class="fa <?php if ($row_eval['evalTechMerit'] == 4) echo "fa-check-square-o"; else echo "fa-square-o"; ?>"></span>&nbsp;&nbsp;&nbsp;
            <span class="fa <?php if ($row_eval['evalTechMerit'] == 5) echo "fa-check-square-o"; else echo "fa-square-o"; ?>"></span> <?php echo $label_high; ?>
            </div>
        </div>
        <div class="row">
            <div class="col col-lg-4 col-md-4 col-sm-4 col-xs-4">
            <strong><?php echo $label_intangibles; ?></strong>
            </div>
            <div class="col col-lg-8 col-md-8 col-sm-8 col-xs-8">
            <?php echo $label_low; ?> <span class="fa <?php if ($row_eval['evalIntangibles'] == 1) echo "fa-check-square-o"; else echo "fa-square-o"; ?>"></span>&nbsp;&nbsp;&nbsp;
            <span class="fa <?php if ($row_eval['evalIntangibles'] == 2) echo "fa-check-square-o"; else echo "fa-square-o"; ?>"></span>&nbsp;&nbsp;&nbsp;
            <span class="fa <?php if ($row_eval['evalIntangibles'] == 3) echo "fa-check-square-o"; else echo "fa-square-o"; ?>"></span>&nbsp;&nbsp;&nbsp;
            <span class="fa <?php if ($row_eval['evalIntangibles'] == 4) echo "fa-check-square-o"; else echo "fa-square-o"; ?>"></span>&nbsp;&nbsp;&nbsp;
            <span class="fa <?php if ($row_eval['evalIntangibles'] == 5) echo "fa-check-square-o"; else echo "fa-square-o"; ?>"></span> <?php echo $label_high; ?>
            </div>
        </div>
    </div>
    <div class="col col-lg-6 col-md-6 col-sm-6 col-xs-6">
    	<strong><?php echo $label_drinkability; ?>:</strong> <?php echo $row_eval['evalDrinkability']; ?>   
    </div>  
</div><!-- ./box -->
<!-- Total -->
<h5><?php echo $label_total; ?><span class="pull-right"><?php echo $score; ?>/50</span></h5>
<!-- Scoring Guide -->
<div class="row footer-descriptor" style="padding: 20px 0 0 0;">
	<div class="col col-lg-6 col-md-6 col-sm-6 col-xs-6">
    	<div class="row <?php if ($score >= 45) echo "strong-text box-plain"; ?>">
            <div class="col col-lg-2 col-md-2 col-sm-2 col-xs-2 no-wrap">
            <?php echo $label_outstanding; ?>
            </div>
            <div class="col col-lg-2 col-md-2 col-sm-2 col-xs-2 no-wrap">
            (45-50)
            </div>
            <div class="col col-lg-8 col-md-8 col-sm-8 col-xs-8">
            <?php echo $descr_outstanding; ?>
            </div>
        </div>
        <div class="row <?php if (($score >= 38) && ($score <= 44)) echo "strong-text box-plain"; ?>">
            <div class="col col-lg-2 col-md-2 col-sm-2 col-xs-2 no-wrap">
            <?php echo $label_excellent; ?>
            </div>
            <div class="col col-lg-2 col-md-2 col-sm-2 col-xs-2 no-wrap">
            (38-44)
            </div>
            <div class="col col-lg-8 col-md-8 col-sm-8 col-xs-8">
            <?php echo $descr_excellent; ?>
            </div>
        </div>
        <div class="row <?php if (($score >= 30) && ($score <= 37)) echo "strong-text box-plain"; ?>">
            <div class="col col-lg-2 col-md-2 col-sm-2 col-xs-2 no-wrap">
            <?php echo $label_very_good; ?>
            </div>
            <div class="col col-lg-2 col-md-2 col-sm-2 col-xs-2 no-wrap">
            (30-37)
            </div>
            <div class="col col-lg-8 col-md-8 col-sm-8 col-xs-8">
            <?php echo $descr_very_good; ?>
            </div>
        </div>
    </div>
    <div class="col col-lg-6 col-md-6 col-sm-6 col-xs-6">
    	<div class="row <?php if (($score >= 21) && ($score <= 29)) echo "strong-text box-plain"; ?>">
            <div class="col col-lg-2 col-md-2 col-sm-2 col-xs-2 no-wrap">
            <?php echo $label_good; ?>
            </div>
            <div class="col col-lg-2 col-md-2 col-sm-2 col-xs-2 no-wrap">
            (21-29)
            </div>
            <div class="col col-lg-8 col-md-8 col-sm-8 col-xs-8">
            <?php echo $descr_good; ?>
            </div>
        </div>
        <div class="row <?php if (($score >= 14) && ($score <= 20)) echo "strong-text box-plain"; ?>">
            <div class="col col-lg-2 col-md-2 col-sm-2 col-xs-2 no-wrap">
            <?php echo $label_fair; ?>
            </div>
            <div class="col col-lg-2 col-md-2 col-sm-2 col-xs-2 no-wrap">
            (14-20)
            </div>
            <div class="col col-lg-8 col-md-8 col-sm-8 col-xs-8">
            <?php echo $descr_fair; ?>
            </div>
        </div>
        <div class="row <?php if (($score >= 0) && ($score <= 13)) echo "strong-text box-plain"; ?>">
            <div class="col col-lg-2 col-md-2 col-sm-2 col-xs-2 no-wrap">
            <?php echo $label_problematic; ?>
            </div>
            <div class="col col-lg-2 col-md-2 col-sm-2 col-xs-2 no-wrap">
            (00-13)
            </div>
            <div class="col col-lg-8 col-md-8 col-sm-8 col-xs-8">
            <?php echo $descr_problematic; ?>
            </div>
        </div>
    </div>
</div><!-- ./ scoring guide -->
<?php if (!empty($row_eval['evalFlaws'])) { 
$flaw_1_arr = array($label_acetaldehyde,$label_light_struck,$label_sour_acidic);
$flaw_2_arr = array($label_alcohol,$label_medicinal,$label_smoky);
$flaw_3_arr = array($label_astringent,$label_metallic,$label_spicy);
$flaw_4_arr = array($label_diacetyl,$label_musty,$label_sulfur);
$flaw_5_arr = array($label_dms,$label_oxidized,$label_vegetal);
$flaw_6_arr = array($label_estery,$label_plastic,$label_vinegary);
$flaw_7_arr = array($label_grassy,$label_solvent,$label_yeasty);
?>
<h5 style="padding-bottom: 10px"><?php echo $label_flaws; ?></h5>
<table class="table-bordered table-condensed descriptor" width="100%">
<thead>
	<tr>
        <th><?php echo $label_fault; ?></th>
        <th><?php echo $label_aroma; ?></th>
        <th><?php echo $label_flavor; ?></th>
        <th><?php echo $label_mouthfeel; ?></th>
        <th><?php echo $label_fault; ?></th>
        <th><?php echo $label_aroma; ?></th>
        <th><?php echo $label_flavor; ?></th>
        <th><?php echo $label_mouthfeel; ?></th>
        <th><?php echo $label_fault; ?></th>
        <th><?php echo $label_aroma; ?></th>
        <th><?php echo $label_flavor; ?></th>
        <th><?php echo $label_mouthfeel; ?></th>
    </tr>
</thead>
<tbody>
	<tr>
        <?php foreach ($flaw_1_arr as $flaw) { ?>
        <td><?php echo $flaw; ?></td>
        <td width="8%"><?php if (strpos($row_eval['evalFlaws'],$label_aroma.": ".$flaw) !== false) echo "<span class=\"fa fa-check\"></span>"; ?></td>
        <td width="8%"><?php if (strpos($row_eval['evalFlaws'],$label_flavor.": ".$flaw) !== false) echo "<span class=\"fa fa-check\"></span>"; ?></td>
        <?php if (in_array($flaw, $flaws_mouthfeel)) { ?>
        <td width="8%"><?php if (strpos($row_eval['evalFlaws'],$label_mouthfeel.": ".$flaw) !== false) echo "<span class=\"fa fa-check\"></span>"; ?></td>
        <?php } else { ?>
        <td width="8%">&nbsp;</td>
        <?php } ?>
        <?php } ?>
    </tr>
    <tr>
        <?php foreach ($flaw_2_arr as $flaw) { ?>
        <td><?php echo $flaw; ?></td>
        <td width="8%"><?php if (strpos($row_eval['evalFlaws'],$label_aroma.": ".$flaw) !== false) echo "<span class=\"fa fa-check\"></span>"; ?></td>
        <td width="8%"><?php if (strpos($row_eval['evalFlaws'],$label_flavor.": ".$flaw) !== false) echo "<span class=\"fa fa-check\"></span>"; ?></td>
        <?php if (in_array($flaw, $flaws_mouthfeel)) { ?>
        <td width="8%"><?php if (strpos($row_eval['evalFlaws'],$label_mouthfeel.": ".$flaw) !== false) echo "<span class=\"fa fa-check\"></span>"; ?></td>
        <?php } else { ?>
        <td width="8%">&nbsp;</td>
        <?php } ?>
        <?php } ?>
    </tr>
    <tr>
        <?php foreach ($flaw_3_arr as $flaw) { ?>
        <td><?php echo $flaw; ?></td>
        <td width="8%"><?php if (strpos($row_eval['evalFlaws'],$label_aroma.": ".$flaw) !== false) echo "<span class=\"fa fa-check\"></span>"; ?></td>
        <td width="8%"><?php if (strpos($row_eval['evalFlaws'],$label_flavor.": ".$flaw) !== false) echo "<span class=\"fa fa-check\"></span>"; ?></td>
        <?php if (in_array($flaw, $flaws_mouthfeel)) { ?>
        <td width="8%"><?php if (strpos($row_eval['evalFlaws'],$label_mouthfeel.": ".$flaw) !== false) echo "<span class=\"fa fa-check\"></span>"; ?></td>
        <?php } else { ?>
        <td width="8%">&nbsp;</td>
        <?php } ?>
        <?php } ?>
    </tr>
    <tr>
        <?php foreach ($flaw_4_arr as $flaw) { ?>
        <td><?php echo $flaw; ?></td>
        <td width="8%"><?php if (strpos($row_eval['evalFlaws'],$label_aroma.": ".$flaw) !== false) echo "<span class=\"fa fa-check\"></span>"; ?></td>
        <td width="8%"><?php if (strpos($row_eval['evalFlaws'],$label_flavor.": ".$flaw) !== false) echo "<span class=\"fa fa-check\"></span>"; ?></td>
        <?php if (in_array($flaw, $flaws_mouthfeel)) { ?>
        <td width="8%"><?php if (strpos($row_eval['evalFlaws'],$label_mouthfeel.": ".$flaw) !== false) echo "<span class=\"fa fa-check\"></span>"; ?></td>
        <?php } else { ?>
        <td width="8%">&nbsp;</td>
        <?php } ?>
        <?php } ?>
    </tr>
    <tr>
        <?php foreach ($flaw_5_arr as $flaw) { ?>
        <td><?php echo $flaw; ?></td>
        <td width="8%"><?php if (strpos($row_eval['evalFlaws'],$label_aroma.": ".$flaw) !== false) echo "<span class=\"fa fa-check\"></span>"; ?></td>
        <td width="8%"><?php if (strpos($row_eval['evalFlaws'],$label_flavor.": ".$flaw) !== false) echo "<span class=\"fa fa-check\"></span>"; ?></td>
        <?php if (in_array($flaw, $flaws_mouthfeel)) { ?>
        <td width="8%"><?php if (strpos($row_eval['evalFlaws'],$label_mouthfeel.": ".$flaw) !== false) echo "<span class=\"fa fa-check\"></span>"; ?></td>
        <?php } else { ?>
        <td width="8%">&nbsp;</td>
        <?php } ?>
        <?php } ?>
    </tr>
    <tr>
        <?php foreach ($flaw_6_arr as $flaw) { ?>
        <td><?php echo $flaw; ?></td>
        <td width="8%"><?php if (strpos($row_eval['evalFlaws'],$label_aroma.": ".$flaw) !== false) echo "<span class=\"fa fa-check\"></span>"; ?></td>
        <td width="8%"><?php if (strpos($row_eval['evalFlaws'],$label_flavor.": ".$flaw) !== false) echo "<span class=\"fa fa-check\"></span>"; ?></td>
        <?php if (in_array($flaw, $flaws_mouthfeel)) { ?>
        <td width="8%"><?php if (strpos($row_eval['evalFlaws'],$label_mouthfeel.": ".$flaw) !== false) echo "<span class=\"fa fa-check\"></span>"; ?></td>
        <?php } else { ?>
        <td width="8%">&nbsp;</td>
        <?php } ?>
        <?php } ?>
    </tr>
    <tr>
        <?php foreach ($flaw_7_arr as $flaw) { ?>
        <td><?php echo $flaw; ?></td>
        <td width="8%"><?php if (strpos($row_eval['evalFlaws'],$label_aroma.": ".$flaw) !== false) echo "<span class=\"fa fa-check\"></span>"; ?></td>
        <td width="8%"><?php if (strpos($row_eval['evalFlaws'],$label_flavor.": ".$flaw) !== false) echo "<span class=\"fa fa-check\"></span>"; ?></td>
        <?php if (in_array($flaw, $flaws_mouthfeel)) { ?>
        <td width="8%"><?php if (strpos($row_eval['evalFlaws'],$label_mouthfeel.": ".$flaw) !== false) echo "<span class=\"fa fa-check\"></span>"; ?></td>
        <?php } else { ?>
        <td width="8%">&nbsp;</td>
        <?php } ?>
        <?php } ?>
    </tr>
</tbody>
</table>
<?php } ?>
<!-- Footer -->
<p style="padding-top: 30px;"><small><em><small><em><?php echo sprintf("%s %s. &copy;%s Beer Judge Certification Program.",$evaluation_info_070,$scoresheet_type,date('Y')); ?></em></small></p>
<?php if (!empty($row_eval['evalFinalScore'])) { ?>
<p><small><em><?php echo $evaluation_info_069; ?></em></small></p>
<?php } ?>
<div style="page-break-after: always;"></div>