<?php

?>
<style>
	.tick-row {
		margin-top: 3px;
		margin-bottom: 3px;
	}
</style>
<!-- Appearance -->
<h5 class="header-h5 header-bdr-bottom"><?php echo $label_appearance; ?></h5>

<?php if (isset($appearance_data['evalAppearanceColor'])) { ?>
<div class="row tick-row no-break" style="margin-bottom: 10px;">
	<div class="col col-lg-3 col-md-3 col-sm-3 col-xs-3">
	    <strong><?php echo $label_color; ?></strong>
	</div>
	<div class="col col-lg-4 col-md-4 col-sm-4 col-xs-4">
	   <?php echo $appearance_data['evalAppearanceColor']; ?>
	</div>
    <div class="col col-lg-1 col-md-1 col-sm-1 col-xs-2">
        <small><?php echo $label_inappropriate; ?></small> <i class="<?php if ((isset($appearance_data['evalAppearanceColorInappr'])) && ($appearance_data['evalAppearanceColorInappr'] == "1")) echo "fa fa-check-square-o"; else echo "fa fa-square-o"; ?>"></i>
    </div>
</div>
<?php } ?>
<div class="row tick-row no-break">
    <div class="col col-lg-3 col-md-3 col-sm-3 col-xs-3">
        <strong><?php echo $label_clarity; ?></strong>
    </div>
    <div class="col col-lg-4 col-md-4 col-sm-4 col-xs-4">
    <table class="tick-table-sm">
        <tr>
            <td width="20%"><?php echo $label_opaque; ?></td>
            <td width="20%"><?php echo $label_cloudy; ?></td>
            <td width="20%" style="text-align: center;"><?php echo $label_hazy; ?></td>
            <td width="20%" style="text-align: right;"><?php echo $label_clear; ?></td>
            <td width="20%" style="text-align: right;"><?php echo $label_brilliant; ?></td>
        </tr>
        <tr style="border-left: 1px solid #000; border-right: 1px solid #000;">
            <?php for ($i=0; $i <= 5; $i++) { ?>
            <td style="border-bottom: 1px solid #000; text-align:center;"><?php if ($appearance_data['evalAppearanceClarity'] == $i) echo "<i class=\"fa fa-lg fa-caret-down\"></i>"; else echo "&nbsp;"; ?></td>
            <?php } ?>
        </tr>
    </table>
    </div>
    <div class="col col-lg-1 col-md-1 col-sm-1 col-xs-2">
        <small><?php echo $label_inappropriate; ?></small> <i class="<?php if ((isset($appearance_data['evalAppearanceClarityInappr'])) && ($appearance_data['evalAppearanceClarityInappr'] == "1")) echo "fa fa-check-square-o"; else echo "fa fa-square-o"; ?>"></i>
    </div> 
</div><!-- ./row -->

<div class="row tick-row no-break">
    <div class="col col-lg-3 col-md-3 col-sm-3 col-xs-3">
        <strong><?php echo $label_carbonation; ?></strong>
    </div>
    <div class="col col-lg-4 col-md-4 col-sm-4 col-xs-4">
    <table class="tick-table-sm">
        <tr>
            <td width="20%"><?php echo $label_still; ?></td>
            <td width="20%"></td>
            <td width="20%" style="text-align: center;"><?php echo $label_petillant; ?></td>
            <td width="20%"></td>
            <td width="20%" style="text-align: right;"><?php echo $label_sparkling; ?></td>
        </tr>
        <tr style="border-left: 1px solid #000; border-right: 1px solid #000;">
            <?php for ($i=0; $i <=5; $i++) { ?>
            <td style="border-bottom: 1px solid #000; text-align:center;"><?php if ($appearance_data['evalAppearanceCarb'] == $i) echo "<i class=\"fa fa-lg fa-caret-down\"></i>"; else echo "&nbsp;"; ?></td>
            <?php } ?>
        </tr>
    </table>
    </div>
    <div class="col col-lg-1 col-md-1 col-sm-1 col-xs-2">
        <small><?php echo $label_inappropriate; ?></small> <i class="<?php if ((isset($appearance_data['evalAppearanceCarbInappr'])) && ($appearance_data['evalAppearanceCarbInappr'] == "1")) echo "fa fa-check-square-o"; else echo "fa fa-square-o"; ?>"></i>
    </div> 
</div><!-- ./row -->

<!-- Aroma -->
<h5 class="header-h5 header-bdr-bottom"><?php echo $label_aroma; ?></h5>
<div class="row tick-row no-break" style="margin-bottom: 10px;">
	<div class="col col-lg-3 col-md-3 col-sm-3 col-xs-3">
	    <strong><?php echo $label_characteristics; ?></strong>
	</div>
	<div class="col col-lg-9 col-md-9 col-sm-9 col-xs-9">
	   <?php echo $aroma_data['evalAromaCharacteristics']; ?>
	</div>
</div>

<div class="row tick-row no-break">
    <div class="col col-lg-3 col-md-3 col-sm-3 col-xs-3">
        <strong><?php echo $label_intensity; ?></strong>
    </div>
    <div class="col col-lg-4 col-md-4 col-sm-4 col-xs-4">
    <table class="tick-table-sm">
        <tr>
            <td width="20%"><?php echo $label_low; ?></td>
            <td width="20%"></td>
            <td width="20%" style="text-align: center;"><?php echo $label_medium; ?></td>
            <td width="20%"></td>
            <td width="20%" style="text-align: right;"><?php echo $label_high; ?></td>
        </tr>
        <tr style="border-left: 1px solid #000; border-right: 1px solid #000;">
            <?php for ($i=0; $i <=5; $i++) { ?>
            <td style="border-bottom: 1px solid #000; text-align:center;"><?php if ($aroma_data['evalAromaIntensity'] == $i) echo "<i class=\"fa fa-lg fa-caret-down\"></i>"; else echo "&nbsp;"; ?></td>
            <?php } ?>
        </tr>
    </table>
    </div>
    <div class="col col-lg-1 col-md-1 col-sm-1 col-xs-2">
        <small><?php echo $label_inappropriate; ?></small> <i class="<?php if ((isset($aroma_data['evalAromaIntensityInappr'])) && ($aroma_data['evalAromaIntensityInappr'] == "1")) echo "fa fa-check-square-o"; else echo "fa fa-square-o"; ?>"></i>
    </div> 
</div><!-- ./row -->

<div class="row tick-row no-break">
    <div class="col col-lg-3 col-md-3 col-sm-3 col-xs-3">
        <strong><?php echo $label_complexity; ?></strong>
    </div>
    <div class="col col-lg-4 col-md-4 col-sm-4 col-xs-4">
    <table class="tick-table-sm">
        <tr>
            <td width="20%"><?php echo $label_low; ?></td>
            <td width="20%"></td>
            <td width="20%" style="text-align: center;"><?php echo $label_medium; ?></td>
            <td width="20%"></td>
            <td width="20%" style="text-align: right;"><?php echo $label_high; ?></td>
        </tr>
        <tr style="border-left: 1px solid #000; border-right: 1px solid #000;">
            <?php for ($i=0; $i <=5; $i++) { ?>
            <td style="border-bottom: 1px solid #000; text-align:center;"><?php if ($aroma_data['evalAromaQuality'] == $i) echo "<i class=\"fa fa-lg fa-caret-down\"></i>"; else echo "&nbsp;"; ?></td>
            <?php } ?>
        </tr>
    </table>
    </div>
    <div class="col col-lg-1 col-md-1 col-sm-1 col-xs-2">
        <small><?php echo $label_inappropriate; ?></small> <i class="<?php if ((isset($aroma_data['evalAromaQualityInappr'])) && ($aroma_data['evalAromaQualityInappr'] == "1")) echo "fa fa-check-square-o"; else echo "fa fa-square-o"; ?>"></i>
    </div> 
</div><!-- ./row -->

<!-- Palate -->
<h5 class="header-h5 header-bdr-bottom"><?php echo $label_palate; ?></h5>

<div class="row tick-row no-break" style="margin-bottom: 10px;">
	<div class="col col-lg-3 col-md-3 col-sm-3 col-xs-3">
	    <strong><?php echo $label_characteristics; ?></strong>
	</div>
	<div class="col col-lg-9 col-md-9 col-sm-9 col-xs-9">
	   <?php echo $flavor_data['evalFlavorCharacteristics']; ?>
	</div>
</div>

<div class="row tick-row no-break">
    <div class="col col-lg-3 col-md-3 col-sm-3 col-xs-3">
        <strong><?php echo $label_flavor . " - " . $label_intensity; ?></strong>
    </div>
    <div class="col col-lg-4 col-md-4 col-sm-4 col-xs-4">
    <table class="tick-table-sm">
        <tr>
            <td width="20%"><?php echo $label_low; ?></td>
            <td width="20%"></td>
            <td width="20%" style="text-align: center;"><?php echo $label_medium; ?></td>
            <td width="20%"></td>
            <td width="20%" style="text-align: right;"><?php echo $label_high; ?></td>
        </tr>
        <tr style="border-left: 1px solid #000; border-right: 1px solid #000;">
            <?php for ($i=0; $i <=5; $i++) { ?>
            <td style="border-bottom: 1px solid #000; text-align:center;"><?php if ($flavor_data['evalFlavorIntensity'] == $i) echo "<i class=\"fa fa-lg fa-caret-down\"></i>"; else echo "&nbsp;"; ?></td>
            <?php } ?>
        </tr>
    </table>
    </div>
    <div class="col col-lg-1 col-md-1 col-sm-1 col-xs-2">
        <small><?php echo $label_inappropriate; ?></small> <i class="<?php if ((isset($flavor_data['evalFlavorIntensityInappr'])) && ($flavor_data['evalFlavorIntensityInappr'] == "1")) echo "fa fa-check-square-o"; else echo "fa fa-square-o"; ?>"></i>
    </div> 
</div><!-- ./row -->

<div class="row tick-row no-break">
    <div class="col col-lg-3 col-md-3 col-sm-3 col-xs-3">
        <strong><?php echo $label_flavor . " - " . $label_complexity; ?></strong>
    </div>
    <div class="col col-lg-4 col-md-4 col-sm-4 col-xs-4">
    <table class="tick-table-sm">
        <tr>
            <td width="20%"><?php echo $label_low; ?></td>
            <td width="20%"></td>
            <td width="20%" style="text-align: center;"><?php echo $label_medium; ?></td>
            <td width="20%"></td>
            <td width="20%" style="text-align: right;"><?php echo $label_high; ?></td>
        </tr>
        <tr style="border-left: 1px solid #000; border-right: 1px solid #000;">
            <?php for ($i=0; $i <=5; $i++) { ?>
            <td style="border-bottom: 1px solid #000; text-align:center;"><?php if ($flavor_data['evalFlavorQuality'] == $i) echo "<i class=\"fa fa-lg fa-caret-down\"></i>"; else echo "&nbsp;"; ?></td>
            <?php } ?>
        </tr>
    </table>
    </div>
    <div class="col col-lg-1 col-md-1 col-sm-1 col-xs-2">
        <small><?php echo $label_inappropriate; ?></small> <i class="<?php if ((isset($flavor_data['evalFlavorQualityInappr'])) && ($flavor_data['evalFlavorQualityInappr'] == "1")) echo "fa fa-check-square-o"; else echo "fa fa-square-o"; ?>"></i>
    </div> 
</div><!-- ./row -->
<?php if (isset($mouthfeel_data['evalMouthfeelSweetness'])) { ?>
<div class="row tick-row no-break" style="margin-bottom: 10px;">
	<div class="col col-lg-3 col-md-3 col-sm-3 col-xs-3">
	    <strong><?php echo $label_sweetness; ?></strong>
	</div>
	<div class="col col-lg-4 col-md-4 col-sm-4 col-xs-4">
	   <?php echo $mouthfeel_data['evalMouthfeelSweetness']; ?>
	</div>
    <div class="col col-lg-1 col-md-1 col-sm-1 col-xs-2">
        <small><?php echo $label_inappropriate; ?></small> <i class="<?php if ((isset($mouthfeel_data['evalMouthfeelSweetnessInappr'])) && ($mouthfeel_data['evalMouthfeelSweetnessInappr'] == "1")) echo "fa fa-check-square-o"; else echo "fa fa-square-o"; ?>"></i>
    </div> 
</div>
<?php } ?>
<div class="row tick-row no-break">
    <div class="col col-lg-3 col-md-3 col-sm-3 col-xs-3">
        <strong><?php echo $label_acidity; ?></strong>
    </div>
    <div class="col col-lg-4 col-md-4 col-sm-4 col-xs-4">
    <table class="tick-table-sm">
        <tr>
            <td width="20%"><?php echo $label_low; ?></td>
            <td width="20%"></td>
            <td width="20%" style="text-align: center;"><?php echo $label_medium; ?></td>
            <td width="20%"></td>
            <td width="20%" style="text-align: right;"><?php echo $label_high; ?></td>
        </tr>
        <tr style="border-left: 1px solid #000; border-right: 1px solid #000;">
            <?php for ($i=0; $i <=5; $i++) { ?>
            <td style="border-bottom: 1px solid #000; text-align:center;"><?php if ($mouthfeel_data['evalMouthfeelAcidity'] == $i) echo "<i class=\"fa fa-lg fa-caret-down\"></i>"; else echo "&nbsp;"; ?></td>
            <?php } ?>
        </tr>
    </table>
    </div>
    <div class="col col-lg-1 col-md-1 col-sm-1 col-xs-2">
        <small><?php echo $label_inappropriate; ?></small> <i class="<?php if ((isset($mouthfeel_data['evalMouthfeelAcidityInappr'])) && ($mouthfeel_data['evalMouthfeelAcidityInappr'] == "1")) echo "fa fa-check-square-o"; else echo "fa fa-square-o"; ?>"></i>
    </div>
</div><!-- ./row -->

<div class="row tick-row no-break">
    <div class="col col-lg-3 col-md-3 col-sm-3 col-xs-3">
        <strong><?php echo $label_tannin . " - " . $label_bitterness; ?></strong>
    </div>
    <div class="col col-lg-4 col-md-4 col-sm-4 col-xs-4">
    <table class="tick-table-sm">
        <tr>
            <td width="20%"><?php echo $label_low; ?></td>
            <td width="20%"></td>
            <td width="20%" style="text-align: center;"><?php echo $label_medium; ?></td>
            <td width="20%"></td>
            <td width="20%" style="text-align: right;"><?php echo $label_high; ?></td>
        </tr>
        <tr style="border-left: 1px solid #000; border-right: 1px solid #000;">
            <?php for ($i=0; $i <=5; $i++) { ?>
            <td style="border-bottom: 1px solid #000; text-align:center;"><?php if ($mouthfeel_data['evalMouthfeelTanninBitter'] == $i) echo "<i class=\"fa fa-lg fa-caret-down\"></i>"; else echo "&nbsp;"; ?></td>
            <?php } ?>
        </tr>
    </table>
    </div>
    <div class="col col-lg-1 col-md-1 col-sm-1 col-xs-2">
        <small><?php echo $label_inappropriate; ?></small> <i class="<?php if ((isset($mouthfeel_data['evalMouthfeelTanninBitterInappr'])) && ($mouthfeel_data['evalMouthfeelTanninBitterInappr'] == "1")) echo "fa fa-check-square-o"; else echo "fa fa-square-o"; ?>"></i>
    </div>
</div><!-- ./row -->

<div class="row tick-row no-break">
    <div class="col col-lg-3 col-md-3 col-sm-3 col-xs-3">
        <strong><?php echo $label_tannin . " - " . $label_astringency; ?></strong>
    </div>
    <div class="col col-lg-4 col-md-4 col-sm-4 col-xs-4">
    <table class="tick-table-sm">
        <tr>
            <td width="20%"><?php echo $label_low; ?></td>
            <td width="20%"></td>
            <td width="20%" style="text-align: center;"><?php echo $label_medium; ?></td>
            <td width="20%"></td>
            <td width="20%" style="text-align: right;"><?php echo $label_high; ?></td>
        </tr>
        <tr style="border-left: 1px solid #000; border-right: 1px solid #000;">
            <?php for ($i=0; $i <=5; $i++) { ?>
            <td style="border-bottom: 1px solid #000; text-align:center;"><?php if ($mouthfeel_data['evalMouthfeelTanninAstringency'] == $i) echo "<i class=\"fa fa-lg fa-caret-down\"></i>"; else echo "&nbsp;"; ?></td>
            <?php } ?>
        </tr>
    </table>
    </div>
    <div class="col col-lg-1 col-md-1 col-sm-1 col-xs-2">
        <small><?php echo $label_inappropriate; ?></small> <i class="<?php if ((isset($mouthfeel_data['evalMouthfeelTanninAstringencyInappr'])) && ($mouthfeel_data['evalMouthfeelTanninAstringencyInappr'] == "1")) echo "fa fa-check-square-o"; else echo "fa fa-square-o"; ?>"></i>
    </div>
</div><!-- ./row -->

<div class="row tick-row no-break">
    <div class="col col-lg-3 col-md-3 col-sm-3 col-xs-3">
        <strong><?php echo $label_body; ?></strong>
    </div>
    <div class="col col-lg-4 col-md-4 col-sm-4 col-xs-4">
    <table class="tick-table-sm">
        <tr>
            <td width="20%"><?php echo $label_low; ?></td>
            <td width="20%"></td>
            <td width="20%" style="text-align: center;"><?php echo $label_medium; ?></td>
            <td width="20%"></td>
            <td width="20%" style="text-align: right;"><?php echo $label_high; ?></td>
        </tr>
        <tr style="border-left: 1px solid #000; border-right: 1px solid #000;">
            <?php for ($i=0; $i <=5; $i++) { ?>
            <td style="border-bottom: 1px solid #000; text-align:center;"><?php if ($mouthfeel_data['evalMouthfeelBody'] == $i) echo "<i class=\"fa fa-lg fa-caret-down\"></i>"; else echo "&nbsp;"; ?></td>
            <?php } ?>
        </tr>
    </table>
    </div>
    <div class="col col-lg-1 col-md-1 col-sm-1 col-xs-2">
        <small><?php echo $label_inappropriate; ?></small> <i class="<?php if ((isset($mouthfeel_data['evalMouthfeelBodyInappr'])) && ($mouthfeel_data['evalMouthfeelBodyInappr'] == "1")) echo "fa fa-check-square-o"; else echo "fa fa-square-o"; ?>"></i>
    </div>
</div><!-- ./row -->

<div class="row tick-row no-break">
    <div class="col col-lg-3 col-md-3 col-sm-3 col-xs-3">
        <strong><?php echo $label_length; ?></strong>
    </div>
    <div class="col col-lg-4 col-md-4 col-sm-4 col-xs-4">
    <table class="tick-table-sm">
        <tr>
            <td width="20%"><?php echo $label_low; ?></td>
            <td width="20%"></td>
            <td width="20%" style="text-align: center;"><?php echo $label_medium; ?></td>
            <td width="20%"></td>
            <td width="20%" style="text-align: right;"><?php echo $label_high; ?></td>
        </tr>
        <tr style="border-left: 1px solid #000; border-right: 1px solid #000;">
            <?php for ($i=0; $i <=5; $i++) { ?>
            <td style="border-bottom: 1px solid #000; text-align:center;"><?php if ($mouthfeel_data['evalMouthfeelLength'] == $i) echo "<i class=\"fa fa-lg fa-caret-down\"></i>"; else echo "&nbsp;"; ?></td>
            <?php } ?>
        </tr>
    </table>
    </div>
    <div class="col col-lg-1 col-md-1 col-sm-1 col-xs-2">
        <small><?php echo $label_inappropriate; ?></small> <i class="<?php if ((isset($mouthfeel_data['evalMouthfeelLengthInappr'])) && ($mouthfeel_data['evalMouthfeelLengthInappr'] == "1")) echo "fa fa-check-square-o"; else echo "fa fa-square-o"; ?>"></i>
    </div>
</div><!-- ./row -->

<div class="row tick-row no-break">
    <div class="col col-lg-3 col-md-3 col-sm-3 col-xs-3">
        <strong><?php echo $label_balance; ?></strong>
    </div>
    <div class="col col-lg-4 col-md-4 col-sm-4 col-xs-4">
    <table class="tick-table-sm">
        <tr>
            <td width="20%"><?php echo $label_low; ?></td>
            <td width="20%"></td>
            <td width="20%" style="text-align: center;"><?php echo $label_medium; ?></td>
            <td width="20%"></td>
            <td width="20%" style="text-align: right;"><?php echo $label_high; ?></td>
        </tr>
        <tr style="border-left: 1px solid #000; border-right: 1px solid #000;">
            <?php for ($i=0; $i <=5; $i++) { ?>
            <td style="border-bottom: 1px solid #000; text-align:center;"><?php if ($mouthfeel_data['evalMouthfeelBalance'] == $i) echo "<i class=\"fa fa-lg fa-caret-down\"></i>"; else echo "&nbsp;"; ?></td>
            <?php } ?>
        </tr>
    </table>
    </div>
    <div class="col col-lg-1 col-md-1 col-sm-1 col-xs-2">
        <small><?php echo $label_inappropriate; ?></small> <i class="<?php if ((isset($mouthfeel_data['evalMouthfeelBalanceInappr'])) && ($mouthfeel_data['evalMouthfeelBalanceInappr'] == "1")) echo "fa fa-check-square-o"; else echo "fa fa-square-o"; ?>"></i>
    </div>
</div><!-- ./row -->

<!-- Overall Impression -->
<h5 class="header-h5 header-bdr-bottom"><?php echo $label_summary_overall_impression; ?></h5>
<p class="tick-row"><?php echo htmlentities($row_eval['evalOverallComments']); ?></p>
<h5 class="header-h5 header-bdr-bottom"><?php echo $label_judge. " ". $label_score; ?><span class="pull-right"><span class="judge-score"><?php echo $score; ?></span>/50</span></h5>

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