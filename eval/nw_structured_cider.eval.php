<?php 

/*
// Redirect if directly accessed without authenticated session
if ((!isset($_SESSION['loginUsername'])) || ((isset($_SESSION['loginUsername'])) && (!isset($base_url)))) {
	$redirect = "../../403.php";
	$redirect_go_to = sprintf("Location: %s", $redirect);
	header($redirect_go_to);
	exit();
}
*/

$color_array = array(
	$label_pale,
	$label_straw,
	$label_gold,
	$label_deep_gold,
	$label_amber,
	$label_copper,
	$label_chestnut,
	$label_pink,
	$label_red,
	$label_purple,
	$label_garnet
);

if ($action == "edit") {
	$aroma_data = json_decode($row_eval['evalAromaChecklist'], true);
	$appearance_data = json_decode($row_eval['evalAppearanceChecklist'], true);
	$flavor_data = json_decode($row_eval['evalFlavorChecklist'], true);
	$mouthfeel_data = json_decode($row_eval['evalMouthfeelChecklist'], true);
}

$other_show = FALSE;
if ((isset($appearance_data['evalAppearanceColorOther'])) && (!in_array($appearance_data['evalAppearanceColor'],$color_array))) $other_show = TRUE; 

?>
<input type="hidden" name="evalFormType" value="4">

<!-- Appearance -->
<h3 class="section-heading"><?php echo $label_appearance; ?></h3>

<!-- Appearance: NW Cider Color -->
<div class="form-group">
	<div class="row">
	    <div class="col-md-3 col-sm-12 col-xs-12">
			<label for="evalAppearanceColor"><?php echo $label_color; ?></label>
		</div>
	    <div class="col-md-3 col-sm-12 col-xs-12 small">
		    <?php foreach ($color_array as $value) { ?>
		    <div class="radio">
		        <label>
		            <input type="radio" name="evalAppearanceColorChoice" value="<?php echo $value; ?>" <?php if (($action == "add") && ($value == $label_pale)) echo "checked"; if ($action == "edit") { if ((isset($appearance_data['evalAppearanceColor'])) && ($appearance_data['evalAppearanceColor'] == $value)) echo "checked"; } ?>> <?php echo $value; ?>
		        </label>
		    </div>
		    <?php } ?>
		    <div class="radio">
		        <label>
		            <input id="evalAppearanceColorOtherChoice" type="radio" name="evalAppearanceColorChoice" value="999"
		            <?php if ($action == "edit") if ((isset($appearance_data['evalAppearanceColorOther'])) && (!in_array($appearance_data['evalAppearanceColor'],$color_array))) echo "checked"; ?>><?php echo $label_other; ?>
		        </label>
		    </div>
		    <div id="evalAppearanceColorOther">
		    <input class="form-control" type="text" name="evalAppearanceColorOther" maxlength="50" placeholder="<?php echo $label_other." - ".$evaluation_info_101; ?>" value="<?php if ($action == "edit") { if (isset($appearance_data['evalAppearanceColorOther'])) echo htmlentities($appearance_data['evalAppearanceColorOther']); } ?>">
		    <div class="help-block with-errors"></div>
			</div>
		</div>
		<div class="col-md-6 col-sm-12 col-xs-12 small">
			<div class="checkbox">
            <label>
                <input type="checkbox" name="evalAppearanceColorInappr" id="evalAppearanceColornappr" value="1" <?php if ($action == "edit") { if ((isset($appearance_data['evalAppearanceColorInappr'])) && ($appearance_data['evalAppearanceColorInappr'] == "1")) echo "checked"; } ?>> <?php echo $label_inappropriate; ?>
            </label>
            </div>
		</div>
</div>

<!-- Appearace: Clarity -->
<div class="form-group">
	<div class="row">
		<div class="col-md-3 col-sm-12 col-xs-12">
			<label for="evalAppearanceClarity"><?php echo $label_clarity; ?></label>
		</div>
		<div class="col-md-3 col-sm-12 col-xs-12 small">
			<div class="small" style="margin-left: 10px">
				<input class="form-control" type="text" id="evalAppearanceClarity" name="evalAppearanceClarity" data-provide="slider" data-slider-ticks="[0,1,2,3,4]" data-slider-ticks-labels='["<?php echo $label_opaque; ?>", "<?php echo $label_cloudy; ?>", "<?php echo $label_hazy; ?>", "<?php echo $label_clear; ?>", "<?php echo $label_brilliant; ?>"]' data-slider-step="1" data-slider-value="<?php if ($action == "edit") echo $appearance_data['evalAppearanceClarity']; ?>" data-slider-tooltip="hide" required>
			</div>
			<div class="help-block small with-errors"></div>
		</div>
		<div class="col-md-6 col-sm-12 col-xs-12 small">
			<div class="checkbox">
            <label>
                <input type="checkbox" name="evalAppearanceClarityInappr" id="evalAppearanceClarityInappr" value="1" <?php if ($action == "edit") { if ((isset($appearance_data['evalAppearanceClarityInappr'])) && ($appearance_data['evalAppearanceClarityInappr'] == "1")) echo "checked"; } ?>> <?php echo $label_inappropriate; ?>
            </label>
            </div>
		</div>
	</div>
</div>

<!-- Appearance: Carbonation -->
<div class="form-group">
	<div class="row">
		<div class="col-md-3 col-sm-12 col-xs-12">
			<label for="evalAppearanceCarb"><?php echo $label_carbonation; ?></label>
		</div>
		<div class="col-md-3 col-sm-12 col-xs-12 small">
			<div class="small" style="margin-left: 10px">
				<input class="form-control" type="text" id="evalAppearanceCarb" name="evalAppearanceCarb" data-provide="slider" data-slider-ticks="[0,1,2,3,4]" data-slider-ticks-labels='["<?php echo $label_still; ?>", "", "<?php echo $label_petillant; ?>", "", "<?php echo $label_sparkling; ?>"]' data-slider-step="1" data-slider-value="<?php if ($action == "edit") echo $appearance_data['evalAppearanceCarb']; ?>" data-slider-tooltip="hide" required>
			<div class="help-block small with-errors"></div>
			</div>
		</div>
		<div class="col-md-6 col-sm-12 col-xs-12 small">
			<div class="checkbox">
            <label>
                <input type="checkbox" name="evalAppearanceCarbInappr" id="evalAppearanceCarbInappr" value="1" <?php if ($action == "edit") { if ((isset($appearance_data['evalAppearanceCarbInappr'])) && ($appearance_data['evalAppearanceCarbInappr'] == "1")) echo "checked"; } ?>> <?php echo $label_inappropriate; ?>
            </label>
            </div>
		</div>
	</div>
</div>

<!-- Aroma -->
<h3 class="section-heading"><?php echo $label_aroma; ?></h3>

<!-- Aroma Characteristics-->
<div class="form-group">
	<div class="row">
		<div class="col-md-3 col-sm-12 col-xs-12">
			<label for="evalAromaCharacteristics"><?php echo $label_characteristics; ?></label>
		</div>
		<div class="col-md-9 col-sm-12 col-xs-12">
			<input class="form-control" type="text" id="evalAromaCharacteristics" name="evalAromaCharacteristics" placeholder="" value="<?php if ($action == "edit") { if (isset($aroma_data['evalAromaCharacteristics'])) echo htmlentities($aroma_data['evalAromaCharacteristics']); } ?>" required>
		<div class="help-block small with-errors"></div>
		</div>
	</div>
</div>

<!-- Aroma Intensity -->
<div class="form-group">
	<div class="row">
		<div class="col-md-3 col-sm-12 col-xs-12">
			<label for="evalAromaIntensity"><?php echo $label_intensity; ?></label>
		</div>
		<div class="col-md-3 col-sm-12 col-xs-12 small">
			<div class="small" style="margin-left: 10px">
				<input class="form-control" type="text" id="evalAromaIntensity" name="evalAromaIntensity" data-provide="slider" data-slider-ticks="[0,1,2,3,4]" data-slider-ticks-labels='["<?php echo $label_low; ?>", "", "<?php echo $label_medium; ?>", "", "<?php echo $label_high; ?>"]' data-slider-step="1" data-slider-value="<?php if ($action == "edit") echo $aroma_data['evalAromaIntensity']; ?>" data-slider-tooltip="hide" required>
			<div class="help-block small with-errors"></div>
			</div>
		</div>
		<div class="col-md-6 col-sm-12 col-xs-12 small">
			<div class="checkbox">
            <label>
                <input type="checkbox" name="evalAromaIntensityInappr" id="evalAromaIntensityInappr" value="1" <?php if ($action == "edit") { if ((isset($aroma_data['evalAromaIntensityInappr'])) && ($aroma_data['evalAromaIntensityInappr'] == "1")) echo "checked"; } ?>> <?php echo $label_inappropriate; ?>
            </label>
            </div>
		</div>
	</div>
</div>

<!-- Aroma Quality -->
<div class="form-group">
	<div class="row">
		<div class="col-md-3 col-sm-12 col-xs-12">
			<label for="evalAromaQuality"><?php echo $label_complexity; ?></label>
		</div>
		<div class="col-md-3 col-sm-12 col-xs-12 small">
			<div class="small" style="margin-left: 10px">
				<input class="form-control" type="text" id="evalAromaQuality" name="evalAromaQuality" data-provide="slider" data-slider-ticks="[0,1,2,3,4]" data-slider-ticks-labels='["<?php echo $label_low; ?>", "", "<?php echo $label_medium; ?>", "", "<?php echo $label_high; ?>"]' data-slider-step="1" data-slider-value="<?php if ($action == "edit") echo $aroma_data['evalAromaQuality']; ?>" data-slider-tooltip="hide" required>
			<div class="help-block small with-errors"></div>
			</div>
		</div>
		<div class="col-md-6 col-sm-12 col-xs-12 small">
			<div class="checkbox">
            <label>
                <input type="checkbox" name="evalAromaQualityInappr" id="evalAromaQualityInappr" value="1" <?php if ($action == "edit") { if ((isset($aroma_data['evalAromaQualityInappr'])) && ($aroma_data['evalAromaQualityInappr'] == "1")) echo "checked"; } ?>> <?php echo $label_inappropriate; ?>
            </label>
            </div>
		</div>
	</div>
</div>

<!-- Palate -->
<h3 class="section-heading"><?php echo $label_palate; ?></h3>

<!-- Flavor Characteristics-->
<div class="form-group">
	<div class="row">
		<div class="col-md-3 col-sm-12 col-xs-12">
			<label for="evalFlavorCharacteristics"><?php echo $label_flavor . " - " . $label_characteristics; ?></label>
		</div>
		<div class="col-md-9 col-sm-12 col-xs-12">
			<input class="form-control" type="text" id="evalFlavorCharacteristics" name="evalFlavorCharacteristics" placeholder="" value="<?php if ($action == "edit") { if (isset($flavor_data['evalFlavorCharacteristics'])) echo htmlentities($flavor_data['evalFlavorCharacteristics']); } ?>" required>
		<div class="help-block small with-errors"></div>
		</div>
	</div>
</div>

<!-- Flavor Intensity -->
<div class="form-group">
	<div class="row">
		<div class="col-md-3 col-sm-12 col-xs-12">
			<label for="evalFlavorIntensity"><?php echo $label_flavor . " - " . $label_intensity; ?></label>
		</div>
		<div class="col-md-3 col-sm-12 col-xs-12 small">
			<div class="small" style="margin-left: 10px">
				<input class="form-control" type="text" id="evalFlavorIntensity" name="evalFlavorIntensity" data-provide="slider" data-slider-ticks="[0,1,2,3,4]" data-slider-ticks-labels='["<?php echo $label_low; ?>", "", "<?php echo $label_medium; ?>", "", "<?php echo $label_high; ?>"]' data-slider-step="1" data-slider-value="<?php if ($action == "edit") echo $flavor_data['evalFlavorIntensity']; ?>" data-slider-tooltip="hide" required>
			<div class="help-block small with-errors"></div>
			</div>
		</div>
		<div class="col-md-6 col-sm-12 col-xs-12 small">
			<div class="checkbox">
            <label>
                <input type="checkbox" name="evalFlavorIntensityInappr" id="evalFlavorIntensityInappr" value="1" <?php if ($action == "edit") { if ((isset($flavor_data['evalFlavorIntensityInappr'])) && ($flavor_data['evalFlavorIntensityInappr'] == "1")) echo "checked"; } ?>> <?php echo $label_inappropriate; ?>
            </label>
            </div>
		</div>
	</div>
</div>

<!-- Flavor Quality -->
<div class="form-group">
	<div class="row">
		<div class="col-md-3 col-sm-12 col-xs-12">
			<label for="evalFlavorQuality"><?php echo $label_flavor . " - " . $label_complexity; ?></label>
		</div>
		<div class="col-md-3 col-sm-12 col-xs-12 small">
			<div class="small" style="margin-left: 10px">
				<input class="form-control" type="text" id="evalFlavorQuality" name="evalFlavorQuality" data-provide="slider" data-slider-ticks="[0,1,2,3,4]" data-slider-ticks-labels='["<?php echo $label_low; ?>", "", "<?php echo $label_medium; ?>", "", "<?php echo $label_high; ?>"]' data-slider-step="1" data-slider-value="<?php if ($action == "edit") echo $flavor_data['evalFlavorQuality']; ?>" data-slider-tooltip="hide" required>
			<div class="help-block small with-errors"></div>
			</div>
		</div>
		<div class="col-md-6 col-sm-12 col-xs-12 small">
			<div class="checkbox">
            <label>
                <input type="checkbox" name="evalFlavorQualityInappr" id="evalFlavorQualityInappr" value="1" <?php if ($action == "edit") { if ((isset($flavor_data['evalFlavorQualityInappr'])) && ($flavor_data['evalFlavorQualityInappr'] == "1")) echo "checked"; } ?>> <?php echo $label_inappropriate; ?>
            </label>
            </div>
		</div>
	</div>
</div>
<!-- Sweetness -->
<div class="form-group">
	<div class="row" style="margin-bottom: 20px;">
	    <div class="col-md-3 col-sm-12 col-xs-12">
			<label for="evalMouthfeelSweetness"><?php echo $label_sweetness; ?></label>
		</div>
	    <div class="col-md-3 col-sm-12 col-xs-12 small">	    
		    <div class="radio">
		        <label>
		            <input type="radio" name="evalMouthfeelSweetness" value="<?php echo $label_dry; ?>" <?php if ($action == "add") echo "checked"; if ($action == "edit") { if ((isset($mouthfeel_data['evalAppearanceColor'])) && ($mouthfeel_data['evalMouthfeelSweetness'] == $label_dry)) echo "checked"; } ?>> <?php echo $label_dry; ?>
		        </label>
		    </div>
		    <div class="radio">
		        <label>
		            <input type="radio" name="evalMouthfeelSweetness" value="<?php echo $label_medium_dry; ?>" <?php if ($action == "edit") { if ((isset($mouthfeel_data['evalMouthfeelSweetness'])) && ($mouthfeel_data['evalMouthfeelSweetness'] == $label_medium_dry)) echo "checked"; } ?>> <?php echo $label_medium_dry; ?>
		        </label>
		    </div>
		    <div class="radio">
		        <label>
		            <input type="radio" name="evalMouthfeelSweetness" value="<?php echo $label_medium; ?>" <?php if ($action == "edit") { if ((isset($mouthfeel_data['evalMouthfeelSweetness'])) && ($mouthfeel_data['evalMouthfeelSweetness'] == $label_medium)) echo "checked"; } ?>> <?php echo $label_medium; ?>
		        </label>
		    </div>
		    <div class="radio">
		        <label>
		            <input type="radio" name="evalMouthfeelSweetness" value="<?php echo $label_medium_sweet; ?>" <?php if ($action == "edit") { if ((isset($mouthfeel_data['evalMouthfeelSweetness'])) && ($mouthfeel_data['evalMouthfeelSweetness'] == $label_medium_sweet)) echo "checked"; } ?>> <?php echo $label_medium_sweet; ?>
		        </label>
		    </div>
		    <div class="radio">
		        <label>
		            <input type="radio" name="evalMouthfeelSweetness" value="<?php echo $label_sweet; ?>" <?php if ($action == "edit") { if ((isset($mouthfeel_data['evalMouthfeelSweetness'])) && ($mouthfeel_data['evalMouthfeelSweetness'] == $label_sweet)) echo "checked"; } ?>> <?php echo $label_sweet; ?>
		        </label>
		    </div>
		</div>
		<div class="col-md-6 col-sm-12 col-xs-12 small">
			<div class="checkbox">
            <label>
                <input type="checkbox" name="evalMouthfeelSweetnessInappr" id="evalFlavorSweetnessInappr" value="1" <?php if ($action == "edit") { if ((isset($mouthfeel_data['evalMouthfeelSweetnessInappr'])) && ($mouthfeel_data['evalMouthfeelSweetnessInappr'] == "1")) echo "checked"; } ?>> <?php echo $label_inappropriate; ?>
            </label>
            </div>
		</div>
</div>

<!-- Acidity -->
<div class="form-group">
	<div class="row">
		<div class="col-md-3 col-sm-12 col-xs-12">
			<label for="evalMouthfeelAcidity"><?php echo $label_acidity; ?></label>
		</div>
		<div class="col-md-3 col-sm-12 col-xs-12 small">
			<div class="small" style="margin-left: 10px">
				<input class="form-control" type="text" id="evalMouthfeelAcidity" name="evalMouthfeelAcidity" data-provide="slider" data-slider-ticks="[0,1,2,3,4]" data-slider-ticks-labels='["<?php echo $label_low; ?>", "", "<?php echo $label_medium; ?>", "", "<?php echo $label_high; ?>"]' data-slider-step="1" data-slider-value="<?php if ($action == "edit") echo $mouthfeel_data['evalMouthfeelAcidity']; ?>" data-slider-tooltip="hide" required>
			<div class="help-block small with-errors"></div>
			</div>
		</div>
		<div class="col-md-6 col-sm-12 col-xs-12 small">
			<div class="checkbox">
            <label>
                <input type="checkbox" name="evalMouthfeelAcidityInappr" id="evalMouthfeelAcidityInappr" value="1" <?php if ($action == "edit") { if ((isset($mouthfeel_data['evalMouthfeelAcidityInappr'])) && ($mouthfeel_data['evalMouthfeelAcidityInappr'] == "1")) echo "checked"; } ?>> <?php echo $label_inappropriate; ?>
            </label>
            </div>
		</div>
	</div>
</div>

<!-- Tannin Bitterness -->
<div class="form-group">
	<div class="row">
		<div class="col-md-3 col-sm-12 col-xs-12">
			<label for="evalMouthfeelTanninBitter"><?php echo $label_tannin . " - " . $label_bitterness; ?></label>
		</div>
		<div class="col-md-3 col-sm-12 col-xs-12 small">
			<div class="small" style="margin-left: 10px">
				<input class="form-control" type="text" id="evalMouthfeelTanninBitter" name="evalMouthfeelTanninBitter" data-provide="slider" data-slider-ticks="[0,1,2,3,4]" data-slider-ticks-labels='["<?php echo $label_low; ?>", "", "<?php echo $label_medium; ?>", "", "<?php echo $label_high; ?>"]' data-slider-step="1" data-slider-value="<?php if ($action == "edit") echo $mouthfeel_data['evalMouthfeelTanninBitter']; ?>" data-slider-tooltip="hide" required>
			<div class="help-block small with-errors"></div>
			</div>
		</div>
		<div class="col-md-6 col-sm-12 col-xs-12 small">
			<div class="checkbox">
            <label>
                <input type="checkbox" name="evalMouthfeelTanninBitterInappr" id="evalMouthfeelTanninBitterInappr" value="1" <?php if ($action == "edit") { if ((isset($mouthfeel_data['evalMouthfeelTanninBitterInappr'])) && ($mouthfeel_data['evalMouthfeelTanninBitterInappr'] == "1")) echo "checked"; } ?>> <?php echo $label_inappropriate; ?>
            </label>
            </div>
		</div>
	</div>
</div>

<!-- Tannin Astringency -->
<div class="form-group">
	<div class="row">
		<div class="col-md-3 col-sm-12 col-xs-12">
			<label for="evalMouthfeelTanninAstringency"><?php echo $label_tannin . " - " . $label_astringency; ?></label>
		</div>
		<div class="col-md-3 col-sm-12 col-xs-12 small">
			<div class="small" style="margin-left: 10px">
				<input class="form-control" type="text" id="evalMouthfeelTanninAstringency" name="evalMouthfeelTanninAstringency" data-provide="slider" data-slider-ticks="[0,1,2,3,4]" data-slider-ticks-labels='["<?php echo $label_low; ?>", "", "<?php echo $label_medium; ?>", "", "<?php echo $label_high; ?>"]' data-slider-step="1" data-slider-value="<?php if ($action == "edit") echo $mouthfeel_data['evalMouthfeelTanninAstringency']; ?>" data-slider-tooltip="hide" required>
			<div class="help-block small with-errors"></div>
			</div>
		</div>
		<div class="col-md-6 col-sm-12 col-xs-12 small">
			<div class="checkbox">
            <label>
                <input type="checkbox" name="evalMouthfeelTanninAstringencyInappr" id="evalMouthfeelTanninAstringencyInappr" value="1" <?php if ($action == "edit") { if ((isset($mouthfeel_data['evalMouthfeelTanninAstringencyInappr'])) && ($mouthfeel_data['evalMouthfeelTanninAstringencyInappr'] == "1")) echo "checked"; } ?>> <?php echo $label_inappropriate; ?>
            </label>
            </div>
		</div>
	</div>
</div>

<!-- Body -->
<div class="form-group">
	<div class="row">
		<div class="col-md-3 col-sm-12 col-xs-12">
			<label for="evalMouthfeelBody"><?php echo $label_body; ?></label>
		</div>
		<div class="col-md-3 col-sm-12 col-xs-12 small">
			<div class="small" style="margin-left: 10px">
				<input class="form-control" type="text" id="evalMouthfeelBody" name="evalMouthfeelBody" data-provide="slider" data-slider-ticks="[0,1,2,3,4]" data-slider-ticks-labels='["<?php echo $label_low; ?>", "", "<?php echo $label_medium; ?>", "", "<?php echo $label_high; ?>"]' data-slider-step="1" data-slider-value="<?php if ($action == "edit") echo $mouthfeel_data['evalMouthfeelBody']; ?>" data-slider-tooltip="hide" required>
			<div class="help-block small with-errors"></div>
			</div>
		</div>
		<div class="col-md-6 col-sm-12 col-xs-12 small">
			<div class="checkbox">
            <label>
                <input type="checkbox" name="evalMouthfeelBodyInappr" id="evalMouthfeelBodyInappr" value="1" <?php if ($action == "edit") { if ((isset($mouthfeel_data['evalMouthfeelBodyInappr'])) && ($mouthfeel_data['evalMouthfeelBodyInappr'] == "1")) echo "checked"; } ?>> <?php echo $label_inappropriate; ?>
            </label>
            </div>
		</div>
	</div>
</div>

<!-- Length -->
<div class="form-group">
	<div class="row">
		<div class="col-md-3 col-sm-12 col-xs-12">
			<label for="evalMouthfeelLength"><?php echo $label_length; ?></label>
		</div>
		<div class="col-md-3 col-sm-12 col-xs-12 small">
			<div class="small" style="margin-left: 10px">
				<input class="form-control" type="text" id="evalMouthfeelLength" name="evalMouthfeelLength" data-provide="slider" data-slider-ticks="[0,1,2,3,4]" data-slider-ticks-labels='["<?php echo $label_low; ?>", "", "<?php echo $label_medium; ?>", "", "<?php echo $label_high; ?>"]' data-slider-step="1" data-slider-value="<?php if ($action == "edit") echo $mouthfeel_data['evalMouthfeelLength']; ?>" data-slider-tooltip="hide" required>
			<div class="help-block small with-errors"></div>
			</div>
		</div>
		<div class="col-md-6 col-sm-12 col-xs-12 small">
			<div class="checkbox">
            <label>
                <input type="checkbox" name="evalMouthfeelLengthInappr" id="evalMouthfeelLengthInappr" value="1" <?php if ($action == "edit") { if ((isset($mouthfeel_data['evalMouthfeelLengthInappr'])) && ($mouthfeel_data['evalMouthfeelLengthInappr'] == "1")) echo "checked"; } ?>> <?php echo $label_inappropriate; ?>
            </label>
            </div>
		</div>
	</div>
</div>

<!-- Balance -->
<div class="form-group">
	<div class="row">
		<div class="col-md-3 col-sm-12 col-xs-12">
			<label for="evalMouthfeelBalance"><?php echo $label_balance; ?></label>
		</div>
		<div class="col-md-3 col-sm-12 col-xs-12 small">
			<div class="small" style="margin-left: 10px">
				<input class="form-control" type="text" id="evalMouthfeelBalance" name="evalMouthfeelBalance" data-provide="slider" data-slider-ticks="[0,1,2,3,4]" data-slider-ticks-labels='["<?php echo $label_low; ?>", "", "<?php echo $label_medium; ?>", "", "<?php echo $label_high; ?>"]' data-slider-step="1" data-slider-value="<?php if ($action == "edit") echo $mouthfeel_data['evalMouthfeelBalance']; ?>" data-slider-tooltip="hide" required>
			<div class="help-block small with-errors"></div>
			</div>
		</div>
		<div class="col-md-6 col-sm-12 col-xs-12 small">
			<div class="checkbox">
            <label>
                <input type="checkbox" name="evalMouthfeelBalanceInappr" id="evalMouthfeelBalanceInappr" value="1" <?php if ($action == "edit") { if ((isset($mouthfeel_data['evalMouthfeelBalanceInappr'])) && ($mouthfeel_data['evalMouthfeelBalanceInappr'] == "1")) echo "checked"; } ?>> <?php echo $label_inappropriate; ?>
            </label>
            </div>
		</div>
	</div>
</div>

<!-- Overall Impression -->
<h3 class="section-heading"><?php echo $label_summary_overall_impression; ?></h3>

<!-- Overall Feedback -->
<div class="form-group">
    <label for="evalOverallComments"><?php echo $label_feedback; ?></label>
    <textarea class="form-control" id="evalOverallComments" name="evalOverallComments" rows="6" placeholder="" data-error="<?php echo $evaluation_info_061; ?>" required><?php if ($action == "edit") echo htmlentities($row_eval['evalOverallComments']); ?></textarea>
    <div class="help-block small with-errors"></div>
    <div class="help-block small" id="evalOverallComments-words"></div>
</div>

<script type="text/javascript">
var edit = <?php if ($action == "edit") echo "true"; else echo "false"; ?>;
var other_show = <?php if ($other_show) echo "true"; else echo "false"; ?>;
$(document).ready(function() {

	$("#evalAppearanceColorOther").hide();
	if ((edit) && (other_show)) $("#evalAppearanceColorOther").show();

	$("input[name$='evalAppearanceColorChoice']").click(function() {
        if ($(this).val() == "999") {
            $("#evalAppearanceColorOther").show("fast");
            $("input[name='evalAppearanceColorOther']").prop("required", true)
        } else {
            $("#evalAppearanceColorOther").hide("fast");
            $("input[name='evalAppearanceColorOther']").prop("required", false);
            $("input[name='evalAppearanceColorOther']").val("")
        }
    });

});

</script>