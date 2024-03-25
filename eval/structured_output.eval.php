<?php

// Redirect if directly accessed without authenticated session
if ((!isset($_SESSION['loginUsername'])) || ((isset($_SESSION['loginUsername'])) && (!isset($base_url)))) {
    $redirect = "../../403.php";
    $redirect_go_to = sprintf("Location: %s", $redirect);
    header($redirect_go_to);
    exit();
}


$flaws_table = "";
$entry_flaws = "";  

if ($beer) {
    $aroma_ticks = $aroma_ticks_beer;
    $flavor_ticks = $flavor_ticks_beer;
    $mouthfeel_ticks = $mouthfeel_ticks_beer;
    $flaws = $flaws_structured_beer;
    $aroma_possible = 12;
    $flavor_possible = 20;
    $appearance_possible = 3;
    $mouthfeel_possible = 5;
    $overall_possible = 10;
}

if ($mead) {
    $aroma_ticks = $aroma_ticks_mead;
    $flavor_ticks = $flavor_ticks_mead;
    $flaws = $flaws_structured_mead;
    $aroma_possible = 10;
    $flavor_possible = 24;
    $appearance_possible = 6;
    $overall_possible = 10;
}

if ($cider) {
    $aroma_ticks = $aroma_ticks_cider;
    $flavor_ticks = $flavor_ticks_cider;
    $flaws = $flaws_structured_cider;
    $aroma_possible = 10;
    $flavor_possible = 24;
    $appearance_possible = 6;
    $overall_possible = 10;
}

asort($flaws);

$aroma_data = json_decode($row_eval['evalAromaChecklist'], true);
$appearance_data = json_decode($row_eval['evalAppearanceChecklist'], true);
$flavor_data = json_decode($row_eval['evalFlavorChecklist'], true);
$mouthfeel_data = json_decode($row_eval['evalMouthfeelChecklist'], true);

// Build Flaws Table
$cols_display = 2;
$html_output = array();

if (!empty($row_eval['evalFlaws'])) {
    $entry_flaws = str_replace(", ", ",", $row_eval['evalFlaws']); // remove spaces between flaws
    $entry_flaws = explode(",", $entry_flaws); // convert flaws string to array
}        

foreach ($flaws as $flaw) {

    $flaw_level = "";
    $flaw_needle_low = $flaw." ".$label_low;
    $flaw_needle_med = $flaw." ".$label_med;
    $flaw_needle_high = $flaw." ".$label_high;

    if (is_array($entry_flaws)) {
        if (in_array($flaw_needle_low, $entry_flaws)) $flaw_level = $label_low;
        if (in_array($flaw_needle_med, $entry_flaws)) $flaw_level = $label_med;
        if (in_array($flaw_needle_high, $entry_flaws)) $flaw_level = $label_high;
    }

    $html_output[] = sprintf("<td width=\"35%%\">%s</td><td width=\"15%%\">%s</td>",$flaw,$flaw_level);

}
 
$cols_difference = count($html_output) % $cols_display;

if ($cols_difference) {
    while($cols_difference < $cols_display) {           
        $html_output[] = "<td></td><td></td>";
        $cols_difference++;      
    } 
}

$html_output = array_chunk($html_output, $cols_display);  

$flaws_table .= "<table width=\"70%\" class=\"table-condensed table-bordered\">"; 

foreach ($html_output as $current_row) {      
    $flaws_table .= "<tr>".implode("", $current_row)."</tr>"; 
}

$flaws_table .= "</table>";
if ($nw_cider) include(EVALS.'nw_structured_cider_output.eval.php');
else {
?>
<!-- Aroma -->
<h5 class="header-h5 header-bdr-bottom"><?php echo $label_aroma; ?><span class="pull-right"><span class="judge-score"><?php echo $row_eval['evalAromaScore']; ?></span>/<?php echo $aroma_possible; ?></span></h5>
<?php foreach ($aroma_ticks as $key => $value) { ?>
<div class="row tick-row no-break">
    <div class="col col-lg-2 col-md-2 col-sm-2 col-xs-2">
        <strong><?php echo $key; ?></strong>
    </div>
    <div class="col col-lg-4 col-md-4 col-sm-4 col-xs-5">
    <table class="tick-table-sm">
        <tr>
            <td width="9%"><small><?php echo $label_none; ?></small></td>
            <td width="9%"><?php echo $label_low; ?></td>
            <td width="9%"></td>
            <td width="9%"></td>
            <td width="9%"></td>
            <td width="9%" style="text-align: center;"><?php echo $label_med; ?></td>
            <td width="9%"></td>
            <td width="9%"></td>
            <td width="9%"></td>
            <td width="9%"></td>
            <td width="10%" style="text-align: right;"><?php echo $label_high; ?></td>
        </tr>
        <tr style="border-left: 1px solid #000; border-right: 1px solid #000;">
            <?php for ($i=0; $i <= 10; $i++) { ?>
            <td style="border-bottom: 1px solid #000; text-align:center;"><?php if ($aroma_data[$value] == $i) echo "<i class=\"fa fa-lg fa-caret-down\"></i>"; else echo "&nbsp;"; ?></td>
            <?php } ?>
        </tr>
    </table>
    </div>
    <div class="col col-lg-1 col-md-1 col-sm-1 col-xs-2">
        <small><?php echo $label_inappropriate; ?></small> <i class="<?php if ((isset($aroma_data[$value.'Inappr'])) && ($aroma_data[$value.'Inappr'] == "1")) echo "fa fa-check-square-o"; else echo "fa fa-square-o"; ?>"></i>
    </div>  
    <div class="col col-lg-4 col-md-4 col-sm-4 col-xs-3">
        <?php if (isset($aroma_data[$value.'Comments'])) echo htmlentities($aroma_data[$value.'Comments']); ?>
    </div>
</div><!-- ./row -->
<?php } ?>
<div class="row tick-row no-break other-row">
    <div class="col col-lg-2 col-md-2 col-sm-2 col-xs-2">
        <strong><?php echo $label_other; ?></strong>
    </div>
    <div class="col col-lg-10 col-md-10 col-sm-10 col-xs-10">
        <?php if (isset($aroma_data['evalAromaOther'])) echo htmlentities($aroma_data['evalAromaOther']); ?>
    </div>
</div><!-- ./row -->

<!-- Appearance -->
<h5 class="header-h5 header-bdr-bottom"><?php echo $label_appearance; ?><span class="pull-right"><span class="judge-score"><?php echo $row_eval['evalAppearanceScore']; ?></span>/<?php echo $appearance_possible; ?></span></h5>

<?php if ($beer) { ?>
<!-- Appearance: Color (Beer) -->
<div class="row tick-row no-break">
    <div class="col col-lg-2 col-md-2 col-sm-2 col-xs-2">
        <strong><?php echo $label_color; ?></strong>
    </div>
    <div class="col col-lg-4 col-md-4 col-sm-4 col-xs-5">
    <table class="tick-table-sm">
        <tr>
            <td width="9%"><?php echo $label_yellow; ?></td>
            <td width="9%"></td>
            <td width="9%"><?php echo $label_gold; ?></td>
            <td width="9%"></td>
            <td width="9%" style="text-align: center;"><?php echo $label_amber; ?></td>
            <td width="9%"></td>
            <td width="9%" style="text-align: center;"><?php echo $label_copper; ?></td>
            <td width="9%"></td>
            <td width="9%"><?php echo $label_brown; ?></td>
            <td width="9%"></td>
            <td width="10%" style="text-align: right;"><?php echo $label_black; ?></td>
        </tr>
        <tr style="border-left: 1px solid #000; border-right: 1px solid #000;">
            <?php for ($i=0; $i <= 10; $i++) { ?>
            <td style="border-bottom: 1px solid #000; text-align:center;"><?php if ($appearance_data['evalAppearanceColor'] == $i) echo "<i class=\"fa fa-lg fa-caret-down\"></i>"; else echo "&nbsp;"; ?></td>
            <?php } ?>
        </tr>
    </table>
    </div>
    <div class="col col-lg-1 col-md-1 col-sm-1 col-xs-2">
        <small><?php echo $label_inappropriate; ?></small> <i class="<?php if ((isset($appearance_data['evalAppearanceColorInappr'])) && ($appearance_data['evalAppearanceColorInappr'] == "1")) echo "fa fa-check-square-o"; else echo "fa fa-square-o"; ?>"></i>
    </div>  
    <div class="col col-lg-4 col-md-4 col-sm-4 col-xs-3">
        <?php if (isset($appearance_data['evalAppearanceColorOther'])) echo htmlentities($appearance_data['evalAppearanceColorOther']); ?>
    </div>
</div><!-- ./row -->
<?php } // end if ($beer); ?>
<!-- Appearace: Clarity (All Style Types) -->
<div class="row tick-row no-break">
    <div class="col col-lg-2 col-md-2 col-sm-2 col-xs-2">
        <strong><?php echo $label_clarity; ?></strong>
    </div>
    <div class="col col-lg-4 col-md-4 col-sm-4 col-xs-5">
    <table class="tick-table-sm">
        <tr>
            <td width="9%"><?php echo $label_opaque; ?></td>
            <td width="9%"></td>
            <td width="9%"></td>
            <td width="9%"></td>
            <td width="9%"></td>
            <td width="9%" style="text-align: center;"><?php echo $label_hazy; ?></td>
            <td width="9%"></td>
            <td width="9%"></td>
            <td width="9%"></td>
            <td width="9%"></td>
            <td width="10%" style="text-align: right;"><?php echo $label_brilliant; ?></td>
        </tr>
        <tr style="border-left: 1px solid #000; border-right: 1px solid #000;">
            <?php for ($i=0; $i <= 10; $i++) { ?>
            <td style="border-bottom: 1px solid #000; text-align:center;"><?php if ($appearance_data['evalAppearanceClarity'] == $i) echo "<i class=\"fa fa-lg fa-caret-down\"></i>"; else echo "&nbsp;"; ?></td>
            <?php } ?>
        </tr>
    </table>
    </div>
    <div class="col col-lg-1 col-md-1 col-sm-1 col-xs-2">
        <small><?php echo $label_inappropriate; ?></small> <i class="<?php if ((isset($appearance_data['evalAppearanceClarityInappr'])) && ($appearance_data['evalAppearanceClarityInappr'] == "1")) echo "fa fa-check-square-o"; else echo "fa fa-square-o"; ?>"></i>
    </div>  
    <div class="col col-lg-4 col-md-4 col-sm-4 col-xs-3">
        <?php if (isset($appearance_data['evalAppearanceClarityComments'])) echo htmlentities($appearance_data['evalAppearanceClarityComments']); ?>
    </div>
</div><!-- ./row -->
<?php if ($beer) { ?>
<!-- Appearance: Beer Head Size -->
<div class="row tick-row no-break">
    <div class="col col-lg-2 col-md-2 col-sm-2 col-xs-2">
        <strong><?php echo $label_head.": ".$label_size; ?></strong>
    </div>
    <div class="col col-lg-4 col-md-4 col-sm-4 col-xs-5">
    <table class="tick-table-sm">
        <tr>
            <td width="9%"><?php echo $label_none; ?></td>
            <td width="9%"></td>
            <td width="9%"><?php echo $label_small; ?></td>
            <td width="9%"></td>
            <td width="9%"></td>
            <td width="9%" style="text-align: center;"><?php echo $label_med; ?></td>
            <td width="9%"></td>
            <td width="9%"></td>
            <td width="9%"></td>
            <td width="9%"></td>
            <td width="10%" style="text-align: right;"><?php echo $label_large; ?></td>
        </tr>
        <tr style="border-left: 1px solid #000; border-right: 1px solid #000;">
            <?php for ($i=0; $i <= 10; $i++) { ?>
            <td style="border-bottom: 1px solid #000; text-align:center;"><?php if ($appearance_data['evalAppearanceHeadSize'] == $i) echo "<i class=\"fa fa-lg fa-caret-down\"></i>"; else echo "&nbsp;"; ?></td>
            <?php } ?>
        </tr>
    </table>
    </div>
    <div class="col col-lg-1 col-md-1 col-sm-1 col-xs-2">
        <small><?php echo $label_inappropriate; ?></small> <i class="<?php if ((isset($appearance_data['evalAppearanceHeadSizeInappr'])) && ($appearance_data['evalAppearanceHeadSizeInappr'] == "1")) echo "fa fa-check-square-o"; else echo "fa fa-square-o"; ?>"></i>
    </div>  
    <div class="col col-lg-4 col-md-4 col-sm-4 col-xs-3">
        <?php if (isset($appearance_data['evalAppearanceHeadSizeComments'])) echo htmlentities($appearance_data['evalAppearanceHeadSizeComments']); ?>
    </div>
</div><!-- ./row -->
<!-- Appearance: Beer Head Retention -->
<div class="row tick-row no-break">
    <div class="col col-lg-2 col-md-2 col-sm-2 col-xs-2">
        <strong><?php echo $label_head.": ".$label_retention; ?></strong>
    </div>
    <div class="col col-lg-4 col-md-4 col-sm-4 col-xs-5">
    <table class="tick-table-sm">
        <tr>
            <td width="9%"><?php echo $label_quick; ?></td>
            <td width="9%"></td>
            <td width="9%"></td>
            <td width="9%"></td>
            <td width="9%"></td>
            <td width="9%"></td>
            <td width="9%"></td>
            <td width="9%"></td>
            <td width="27%" colspan="3" style="text-align: right;"><?php echo $label_long_lasting; ?></td>
        </tr>
        <tr style="border-left: 1px solid #000; border-right: 1px solid #000;">
            <?php for ($i=0; $i <= 10; $i++) { ?>
            <td style="border-bottom: 1px solid #000; text-align:center;"><?php if ($appearance_data['evalAppearanceHeadReten'] == $i) echo "<i class=\"fa fa-lg fa-caret-down\"></i>"; else echo "&nbsp;"; ?></td>
            <?php } ?>
        </tr>
    </table>
    </div>
    <div class="col col-lg-1 col-md-1 col-sm-1 col-xs-2">
        <small><?php echo $label_inappropriate; ?></small> <i class="<?php if ((isset($appearance_data['evalAppearanceHeadRetenInappr'])) && ($appearance_data['evalAppearanceHeadRetenInappr'] == "1")) echo "fa fa-check-square-o"; else echo "fa fa-square-o"; ?>"></i>
    </div>
</div><!-- ./row -->
<!-- Appearance: Beer Head Color -->
<div class="row tick-row no-break">
    <div class="col col-lg-2 col-md-2 col-sm-2 col-xs-2">
        <strong><?php echo $label_head.": ".$label_color; ?></strong>
    </div>
    <div class="col col-lg-4 col-md-4 col-sm-4 col-xs-5">
    <table class="tick-table-sm">
        <tr>
            <td width="9%"><?php echo $label_white; ?></td>
            <td width="9%"></td>
            <td width="9%"></td>
            <td width="9%" style="text-align: center;"><?php echo $label_ivory; ?></td>
            <td width="9%"></td>
            <td width="9%"></td>
            <td width="9%"></td>
            <td width="9%" style="text-align: center;"><?php echo $label_beige; ?></td>
            <td width="9%"></td>
            <td width="9%"></td>
            <td width="10%" style="text-align: right;"><?php echo $label_tan; ?></td>
        </tr>
        <tr style="border-left: 1px solid #000; border-right: 1px solid #000;">
            <?php for ($i=0; $i <= 10; $i++) { ?>
            <td style="border-bottom: 1px solid #000; text-align:center;"><?php if ($appearance_data['evalAppearanceHeadColor'] == $i) echo "<i class=\"fa fa-lg fa-caret-down\"></i>"; else echo "&nbsp;"; ?></td>
            <?php } ?>
        </tr>
    </table>
    </div>
    <div class="col col-lg-1 col-md-1 col-sm-1 col-xs-2">
        <small><?php echo $label_inappropriate; ?></small> <i class="<?php if ((isset($appearance_data['evalAppearanceHeadColorInappr'])) && ($appearance_data['evalAppearanceHeadColorInappr'] == "1")) echo "fa fa-check-square-o"; else echo "fa fa-square-o"; ?>"></i>
    </div>
</div><!-- ./row -->
<?php } // end if ($beer); ?>

<?php if (($cider) || ($mead)) { ?>
<!-- Appearance: Legs (Mead and Cider) -->
<div class="row tick-row no-break">
    <div class="col col-lg-2 col-md-2 col-sm-2 col-xs-2">
        <strong><?php echo $label_legs; ?></strong>
    </div>
    <div class="col col-lg-4 col-md-4 col-sm-4 col-xs-5">
    <table class="tick-table-sm">
        <tr>
            <td width="9%"><small><?php echo $label_none; ?></small></td>
            <td width="9%"><?php echo $label_thin; ?></td>
            <td width="9%"></td>
            <td width="9%"></td>
            <td width="9%"></td>
            <td width="9%" style="text-align: center;"><?php echo $label_med; ?></td>
            <td width="9%"></td>
            <td width="9%"></td>
            <td width="9%"></td>
            <td width="9%"></td>
            <td width="10%" style="text-align: right;"><?php echo $label_viscous; ?></td>
        </tr>
        <tr style="border-left: 1px solid #000; border-right: 1px solid #000;">
            <?php for ($i=0; $i <= 10; $i++) { ?>
            <td style="border-bottom: 1px solid #000; text-align:center;"><?php if ($appearance_data['evalAppearanceLegs'] == $i) echo "<i class=\"fa fa-lg fa-caret-down\"></i>"; else echo "&nbsp;"; ?></td>
            <?php } ?>
        </tr>
    </table>
    </div>
    <div class="col col-lg-1 col-md-1 col-sm-1 col-xs-2">
        <small><?php echo $label_inappropriate; ?></small> <i class="<?php if ((isset($appearance_data['evalAppearanceLegsInappr'])) && ($appearance_data['evalAppearanceLegsInappr'] == "1")) echo "fa fa-check-square-o"; else echo "fa fa-square-o"; ?>"></i>
    </div>
</div><!-- ./row -->
<!-- Appearance: Carbonation (Mead and Cider) -->
<div class="row tick-row no-break">
    <div class="col col-lg-2 col-md-2 col-sm-2 col-xs-2">
        <strong><?php echo $label_carbonation; ?></strong>
    </div>
    <div class="col col-lg-4 col-md-4 col-sm-4 col-xs-5">
    <table class="tick-table-sm">
        <tr>
            <td width="9%"><small><?php echo $label_none; ?></small></td>
            <td width="9%"><?php echo $label_low; ?></td>
            <td width="9%"></td>
            <td width="9%"></td>
            <td width="9%"></td>
            <td width="9%" style="text-align: center;"><?php echo $label_med; ?></td>
            <td width="9%"></td>
            <td width="9%"></td>
            <td width="9%"></td>
            <td width="9%"></td>
            <td width="10%" style="text-align: right;"><?php echo $label_high; ?></td>
        </tr>
        <tr style="border-left: 1px solid #000; border-right: 1px solid #000;">
            <?php for ($i=0; $i <= 10; $i++) { ?>
            <td style="border-bottom: 1px solid #000; text-align:center;"><?php if ($appearance_data['evalAppearanceCarb'] == $i) echo "<i class=\"fa fa-lg fa-caret-down\"></i>"; else echo "&nbsp;"; ?></td>
            <?php } ?>
        </tr>
    </table>
    </div>
    <div class="col col-lg-1 col-md-1 col-sm-1 col-xs-2">
        <small><?php echo $label_inappropriate; ?></small> <i class="<?php if ((isset($appearance_data['evalAppearanceCarbInappr'])) && ($appearance_data['evalAppearanceCarbInappr'] == "1")) echo "fa fa-check-square-o"; else echo "fa fa-square-o"; ?>"></i>
    </div>
</div><!-- ./row -->
<?php } // end if (($cider) || ($mead)) ?>
<!-- Appearance: Other (All) -->
<div class="row tick-row no-break other-row">
    <div class="col col-lg-2 col-md-2 col-sm-2 col-xs-2">
        <strong><?php echo $label_other; ?></strong>
    </div>
    <div class="col col-lg-10 col-md-10 col-sm-10 col-xs-10">
        <?php if (isset($appearance_data['evalAppearanceOther'])) echo htmlentities($appearance_data['evalAppearanceOther']); ?>
    </div>
</div><!-- ./row -->

<!-- Flavor -->
<h5 class="header-h5 header-bdr-bottom"><?php echo $label_flavor; ?><span class="pull-right"><span class="judge-score"><?php echo $row_eval['evalFlavorScore']; ?></span>/<?php echo $flavor_possible; ?></span></h5>
<?php foreach ($flavor_ticks as $key => $value) { ?>
<div class="row tick-row no-break">
    <div class="col col-lg-2 col-md-2 col-sm-2 col-xs-2">
        <strong><?php echo $key; ?></strong>
    </div>
    <div class="col col-lg-4 col-md-4 col-sm-4 col-xs-5">
    <table class="tick-table-sm">
        <tr>
            <td width="9%"><small><?php echo $label_none; ?></small></td>
            <td width="9%"><?php echo $label_low; ?></td>
            <td width="9%"></td>
            <td width="9%"></td>
            <td width="9%"></td>
            <td width="9%" style="text-align: center;"><?php echo $label_med; ?></td>
            <td width="9%"></td>
            <td width="9%"></td>
            <td width="9%"></td>
            <td width="9%"></td>
            <td width="10%" style="text-align: right;"><?php echo $label_high; ?></td>
        </tr>
        <tr style="border-left: 1px solid #000; border-right: 1px solid #000;">
            <?php for ($i=0; $i <= 10; $i++) { ?>
            <td style="border-bottom: 1px solid #000; text-align:center;"><?php if ($flavor_data[$value] == $i) echo "<i class=\"fa fa-lg fa-caret-down\"></i>"; else echo "&nbsp;"; ?></td>
            <?php } ?>
        </tr>
    </table>
    </div>
    <div class="col col-lg-1 col-md-1 col-sm-1 col-xs-2">
        <small><?php echo $label_inappropriate; ?></small> <i class="<?php if ((isset($flavor_data[$value.'Inappr'])) && ($flavor_data[$value.'Inappr'] == "1")) echo "fa fa-check-square-o"; else echo "fa fa-square-o"; ?>"></i>
    </div>  
    <div class="col col-lg-4 col-md-4 col-sm-4 col-xs-3">
        <?php if (isset($flavor_data[$value.'Comments'])) echo htmlentities($flavor_data[$value.'Comments']); ?>
    </div>
</div><!-- ./row -->
<?php } ?>

<?php if ($beer) { ?>
<!-- Flavor: Balance (Beer) -->
<div class="row tick-row no-break">
    <div class="col col-lg-2 col-md-2 col-sm-2 col-xs-2">
        <strong><?php echo $label_balance; ?></strong>
    </div>
    <div class="col col-lg-4 col-md-4 col-sm-4 col-xs-5">
    <table class="tick-table-sm">
        <tr>
            <td width="9%"><?php echo $label_hoppy; ?></td>
            <td width="9%"></td>
            <td width="9%"></td>
            <td width="9%"></td>
            <td width="9%"></td>
            <td width="9%" style="text-align: center;"></td>
            <td width="9%"></td>
            <td width="9%"></td>
            <td width="9%"></td>
            <td width="9%"></td>
            <td width="10%" style="text-align: right;"><?php echo $label_malty; ?></td>
        </tr>
        <tr style="border-left: 1px solid #000; border-right: 1px solid #000;">
            <?php for ($i=0; $i <= 10; $i++) { ?>
            <td style="border-bottom: 1px solid #000; text-align:center;"><?php if ($flavor_data['evalFlavorBalance'] == $i) echo "<i class=\"fa fa-lg fa-caret-down\"></i>"; else echo "&nbsp;"; ?></td>
            <?php } ?>
        </tr>
    </table>
    </div>
    <div class="col col-lg-1 col-md-1 col-sm-1 col-xs-2">
        <small><?php echo $label_inappropriate; ?></small> <i class="<?php if ((isset($flavor_data['evalFlavorBalanceInappr'])) && ($flavor_data['evalFlavorBalanceInappr'] == "1")) echo "fa fa-check-square-o"; else echo "fa fa-square-o"; ?>"></i>
    </div>  
    <div class="col col-lg-4 col-md-4 col-sm-4 col-xs-3">
        <?php if (isset($flavor_data['evalFlavorBalanceComments'])) echo htmlentities($flavor_data['evalFlavorBalanceComments']); ?>
    </div>
</div><!-- ./row -->
<!-- Flavor: Aftertaste (Beer) -->
<div class="row tick-row no-break">
    <div class="col col-lg-2 col-md-2 col-sm-2 col-xs-2">
        <strong><?php echo $label_finish_aftertaste; ?></strong>
    </div>
    <div class="col col-lg-4 col-md-4 col-sm-4 col-xs-5">
    <table class="tick-table-sm">
        <tr>
            <td width="9%"><?php echo $label_dry; ?></td>
            <td width="9%"></td>
            <td width="9%"></td>
            <td width="9%"></td>
            <td width="9%"></td>
            <td width="9%" style="text-align: center;"></td>
            <td width="9%"></td>
            <td width="9%"></td>
            <td width="9%"></td>
            <td width="9%"></td>
            <td width="10%" style="text-align: right;"><?php echo $label_sweet; ?></td>
        </tr>
        <tr style="border-left: 1px solid #000; border-right: 1px solid #000;">
            <?php for ($i=0; $i <= 10; $i++) { ?>
            <td style="border-bottom: 1px solid #000; text-align:center;"><?php if ($flavor_data['evalFlavorFinish'] == $i) echo "<i class=\"fa fa-lg fa-caret-down\"></i>"; else echo "&nbsp;"; ?></td>
            <?php } ?>
        </tr>
    </table>
    </div>
    <div class="col col-lg-1 col-md-1 col-sm-1 col-xs-2">
        <small><?php echo $label_inappropriate; ?></small> <i class="<?php if ((isset($flavor_data['evalFlavorFinishInappr'])) && ($flavor_data['evalFlavorFinishInappr'] == "1")) echo "fa fa-check-square-o"; else echo "fa fa-square-o"; ?>"></i>
    </div>  
    <div class="col col-lg-4 col-md-4 col-sm-4 col-xs-3">
        <?php if (isset($flavor_data['evalFlavorFinishComments'])) echo htmlentities($flavor_data['evalFlavorFinishComments']); ?>
    </div>
</div><!-- ./row -->
<?php } // end if ($beer) ?>

<?php if (($mead) || ($cider)) { ?>
<!-- Flavor: Body (Mead and Cider) -->
<div class="row tick-row no-break">
    <div class="col col-lg-2 col-md-2 col-sm-2 col-xs-2">
        <strong><?php echo $label_body; ?></strong>
    </div>
    <div class="col col-lg-4 col-md-4 col-sm-4 col-xs-5">
    <table class="tick-table-sm">
        <tr>
            <td width="9%"><?php echo $label_thin; ?></td>
            <td width="9%"></td>
            <td width="9%"></td>
            <td width="9%"></td>
            <td width="9%"></td>
            <td width="9%" style="text-align: center;"></td>
            <td width="9%"></td>
            <td width="9%"></td>
            <td width="9%"></td>
            <td width="9%"></td>
            <td width="10%" style="text-align: right;"><?php echo $label_full; ?></td>
        </tr>
        <tr style="border-left: 1px solid #000; border-right: 1px solid #000;">
            <?php for ($i=0; $i <= 10; $i++) { ?>
            <td style="border-bottom: 1px solid #000; text-align:center;"><?php if ($flavor_data['evalFlavorBody'] == $i) echo "<i class=\"fa fa-lg fa-caret-down\"></i>"; else echo "&nbsp;"; ?></td>
            <?php } ?>
        </tr>
    </table>
    </div>
    <div class="col col-lg-1 col-md-1 col-sm-1 col-xs-2">
        <small><?php echo $label_inappropriate; ?></small> <i class="<?php if ((isset($flavor_data['evalFlavorBodyInappr'])) && ($flavor_data['evalFlavorBodyInappr'] == "1")) echo "fa fa-check-square-o"; else echo "fa fa-square-o"; ?>"></i>
    </div>  
    <div class="col col-lg-4 col-md-4 col-sm-4 col-xs-3">
        <?php if (isset($flavor_data['evalFlavorBodyComments'])) echo htmlentities($flavor_data['evalFlavorBodyComments']); ?>
    </div>
</div><!-- ./row -->

<!-- Flavor: Aftertaste (Mead and Cider) -->
<div class="row tick-row no-break">
    <div class="col col-lg-2 col-md-2 col-sm-2 col-xs-2">
        <strong><?php echo $label_finish_aftertaste; ?></strong>
    </div>
    <div class="col col-lg-4 col-md-4 col-sm-4 col-xs-5">
    <table class="tick-table-sm">
        <tr>
            <td width="9%"><?php echo $label_quick; ?></td>
            <td width="9%"></td>
            <td width="9%"></td>
            <td width="9%"></td>
            <td width="9%"></td>
            <td width="9%" style="text-align: center;"></td>
            <td width="9%"></td>
            <td width="9%"></td>
            <td width="28%" colspan="3" style="text-align: right;"><?php echo $label_long_lasting; ?></td>
        </tr>
        <tr style="border-left: 1px solid #000; border-right: 1px solid #000;">
            <?php for ($i=0; $i <= 10; $i++) { ?>
            <td style="border-bottom: 1px solid #000; text-align:center;"><?php if ($flavor_data['evalFlavorFinish'] == $i) echo "<i class=\"fa fa-lg fa-caret-down\"></i>"; else echo "&nbsp;"; ?></td>
            <?php } ?>
        </tr>
    </table>
    </div>
    <div class="col col-lg-1 col-md-1 col-sm-1 col-xs-2">
        <small><?php echo $label_inappropriate; ?></small> <i class="<?php if ((isset($flavor_data['evalFlavorFinishInappr'])) && ($flavor_data['evalFlavorFinishInappr'] == "1")) echo "fa fa-check-square-o"; else echo "fa fa-square-o"; ?>"></i>
    </div>  
    <div class="col col-lg-4 col-md-4 col-sm-4 col-xs-3">
        <?php if (isset($flavor_data['evalFlavorFinishComments'])) echo htmlentities($flavor_data['evalFlavorFinishComments']); ?>
    </div>
</div><!-- ./row -->
<!-- Flavor: Balance (Mead and Cider) -->
<div class="row tick-row no-break other-row">
    <div class="col col-lg-2 col-md-2 col-sm-2 col-xs-2">
        <strong><?php echo $label_balance; ?></strong>
    </div>
    <div class="col col-lg-10 col-md-10 col-sm-10 col-xs-10">
        <?php if (isset($flavor_data['evalFlavorBalance'])) echo $flavor_data['evalFlavorBalance']; ?>
    </div>
</div><!-- ./row -->
<?php } // end if (($mead) || ($cider)) ?>
<!-- Flavor: Other (All) -->
<div class="row tick-row no-break other-row">
    <div class="col col-lg-2 col-md-2 col-sm-2 col-xs-2">
        <strong><?php echo $label_other; ?></strong>
    </div>
    <div class="col col-lg-10 col-md-10 col-sm-10 col-xs-10">
        <?php if (isset($flavor_data['evalFlavorOther'])) echo htmlentities($flavor_data['evalFlavorOther']); ?>
    </div>
</div><!-- ./row -->
<!-- Mouthfeel (Beer only) -->
<?php if ($beer) { ?>
<h5 class="header-h5 header-bdr-bottom"><?php echo $label_mouthfeel ?><span class="pull-right"><span class="judge-score"><?php echo $row_eval['evalMouthfeelScore']; ?></span>/<?php echo $mouthfeel_possible; ?></span></h5>
<?php foreach ($mouthfeel_ticks as $key => $value) { ?>
<div class="row tick-row no-break">
    <div class="col col-lg-2 col-md-2 col-sm-2 col-xs-2">
        <strong><?php echo $key; ?></strong>
    </div>
    <div class="col col-lg-4 col-md-4 col-sm-4 col-xs-5">
    <table class="tick-table-sm no-break">
        <tr>
            <td width="9%"><?php if (strpos($key, $label_body) !== false) echo $label_thin; else echo "<small>".$label_none."</small>"; ?></td>
            <td width="9%"><?php if (strpos($key, $label_body) === false) echo $label_low; ?></td>
            <td width="9%"></td>
            <td width="9%"></td>
            <td width="9%"></td>
            <td width="9%" style="text-align: center;"><?php echo $label_med; ?></td>
            <td width="9%"></td>
            <td width="9%"></td>
            <td width="9%"></td>
            <td width="9%"></td>
            <td width="10%" style="text-align: right;"><?php if (strpos($key, $label_body) !== false) echo $label_full; else echo $label_high; ?></td>
        </tr>
        <tr style="border-left: 1px solid #000; border-right: 1px solid #000;">
            <?php for ($i=0; $i <= 10; $i++) { ?>
            <td style="border-bottom: 1px solid #000; text-align:center;"><?php if ($mouthfeel_data[$value] == $i) echo "<i class=\"fa fa-lg fa-caret-down\"></i>"; else echo "&nbsp;"; ?></td>
            <?php } ?>
        </tr>
    </table>
    </div>
    <div class="col col-lg-1 col-md-1 col-sm-1 col-xs-2">
        <small><?php echo $label_inappropriate; ?></small> <i class="<?php if ((isset($mouthfeel_data[$value.'Inappr'])) && ($mouthfeel_data[$value.'Inappr'] == "1")) echo "fa fa-check-square-o"; else echo "fa fa-square-o"; ?>"></i>
    </div>  
    <div class="col col-lg-4 col-md-4 col-sm-4 col-xs-3">
        <?php if (isset($mouthfeel_data[$value.'Comments'])) echo htmlentities($mouthfeel_data[$value.'Comments']); ?>
    </div>
</div><!-- ./row -->
<?php } ?>
<div class="row tick-row no-break other-row">
    <div class="col col-lg-2 col-md-2 col-sm-2 col-xs-2">
        <strong><?php echo $label_other; ?></strong>
    </div>
    <div class="col col-lg-10 col-md-10 col-sm-10 col-xs-10">
        <?php if (isset($mouthfeel_data['evalMouthfeelOther'])) echo htmlentities($mouthfeel_data['evalMouthfeelOther']); ?>
    </div>
</div><!-- ./row -->
<?php } // end if ($beer) ?>
<!-- Overall Impression -->
<h5 class="header-h5 header-bdr-bottom"><?php echo $label_overall_impression; ?><span class="pull-right"><span class="judge-score"><?php echo $row_eval['evalOverallScore']; ?></span>/<?php echo $overall_possible; ?></span></h5>
<p><?php echo htmlentities($row_eval['evalOverallComments']); ?></p>
<div class="row section-row no-break">
    <div class="col col-lg-2 col-md-2 col-sm-2 col-xs-2">
    <strong><?php echo $label_style_accuracy; ?></strong>
    </div>
    <div class="col col-lg-7 col-md-8 col-sm-9 col-xs-9">
    <table class="tick-table" width="700">
    	<tr>
    		<td nowrap="nowrap" width="30%" style="text-align: right; border-right: 1px solid #000; padding-right: 5px;"><?php echo $label_not_style; ?></td>
    		<?php for ($i=1; $i <= 10; $i++) { ?>
    		<td style="text-align: right; border-bottom: 1px solid #000;"><?php if ($row_eval['evalStyleAccuracy'] == $i) echo "<i class=\"fa fa-lg fa-caret-down\"></i>"; else echo "&nbsp;"; ?></td>
    		<?php } ?>
    		<td nowrap="nowrap" width="30%"  style="border-left: 1px solid #000; padding-left: 5px;"><?php echo $label_classic_example; ?></td>
    	</tr>
    </table>
    </div>
</div>
<div class="row tick-row no-break">
    <div class="col col-lg-2 col-md-2 col-sm-2 col-xs-2">
    <strong><?php echo $label_tech_merit; ?></strong>
    </div>
    <div class="col col-lg-7 col-md-8 col-sm-9 col-xs-9">
    <table class="tick-table" width="700">
    	<tr>
    		<td nowrap="nowrap" width="30%" style="text-align: right; border-right: 1px solid #000; padding-right: 5px;"><?php echo $label_significant_flaws; ?></td>
    		<?php for ($i=1; $i <= 10; $i++) { ?>
    		<td style="border-bottom: 1px solid #000; text-align: center;"><?php if ($row_eval['evalTechMerit'] == $i) echo "<i class=\"fa fa-lg fa-caret-down\"></i>"; else echo "&nbsp;"; ?></td>
    		<?php } ?>
    		<td nowrap="nowrap" width="30%" style="border-left: 1px solid #000; padding-left: 5px;"><?php echo $label_flawless; ?></td>
    	</tr>
    </table>
    </div>
</div>
<div class="row tick-row no-break">
    <div class="col col-lg-2 col-md-2 col-sm-2 col-xs-2">
    <strong><?php echo $label_intangibles; ?></strong>
    </div>
    <div class="col col-lg-7 col-md-8 col-sm-9 col-xs-9">
    <table class="tick-table" width="700">
    	<tr>
    		<td nowrap="nowrap" width="30%" style="text-align: right; border-right: 1px solid #000; padding-right: 5px;"><?php echo $label_lifeless; ?></td>
    		<?php for ($i=1; $i <= 10; $i++) { ?>
    		<td style="border-bottom: 1px solid #000; text-align: center;"><?php if ($row_eval['evalIntangibles'] == $i) echo "<i class=\"fa fa-lg fa-caret-down\"></i>"; else echo "&nbsp;"; ?></td>
    		<?php } ?>
    		<td nowrap="nowrap" width="30%"  style="border-left: 1px solid #000; padding-left: 5px;"><?php echo $label_wonderful; ?></td>
    	</tr>
    </table>
    </div>
</div>
<!-- Total -->
<h5 class="header-h5 header-bdr-bottom"><?php echo $label_flaws; ?></h5>
<div style="margin-top: 10px;">
<?php echo $flaws_table; ?>
</div>
<h5 class="header-h5 header-bdr-bottom"><?php echo $label_total; ?><span class="pull-right"><span class="judge-score"><?php echo $score; ?></span>/50</span></h5>
<!-- Scoring Guide -->
<div class="row footer-descriptor no-break" style="padding: 20px 0;">
	<div class="col col-lg-6 col-md-6 col-sm-6 col-xs-6">
    	<div class="row <?php if ($score >= 45) echo "strong-text box-plain"; ?>">
            <div class="col col-lg-2 col-md-2 col-sm-2 col-xs-2 no-wrap">
            <?php echo $label_outstanding; ?>
            </div>
            <div class="col col-lg-2 col-md-2 col-sm-2 col-xs-2 no-wrap">
            (45-50)
            </div>
            <div class="col col-lg-8 col-md-8 col-sm-8 col-xs-10">
            <?php echo $descr_outstanding; ?>
            </div>
        </div>
        <div class="row <?php if (($score >= 38) && ($score <= 44)) echo "strong-text box-plain"; ?>">
            <div class="col col-lg-2 col-md-2 col-sm-2 col-xs-2">
            <?php echo $label_excellent; ?>
            </div>
            <div class="col col-lg-2 col-md-2 col-sm-2 col-xs-2 no-wrap">
            (38-44)
            </div>
            <div class="col col-lg-8 col-md-8 col-sm-8 col-xs-10">
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
            <div class="col col-lg-8 col-md-8 col-sm-8 col-xs-10">
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
            <div class="col col-lg-8 col-md-8 col-sm-8 col-xs-10">
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
            <div class="col col-lg-8 col-md-8 col-sm-8 col-xs-10">
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
            <div class="col col-lg-8 col-md-8 col-sm-8 col-xs-10">
            <?php echo $descr_problematic; ?>
            </div>
        </div>
    </div>
</div><!-- ./ scoring guide -->
<?php } ?>

<!-- Footer -->
<?php if (!$nw_cider) { ?><p style="padding-top: 2em;"><small><em><?php echo sprintf("%s %s. &copy;%s Beer Judge Certification Program.",$evaluation_info_070,$scoresheet_type,date('Y')); ?></em></small></p><?php } ?>
<?php if (!empty($row_eval['evalFinalScore'])) { ?>
<p style="padding-top: 1em;"><small><em>** <?php echo $evaluation_info_069; ?></em></small></p>
<?php } ?>
<div style="page-break-after: always;"></div>