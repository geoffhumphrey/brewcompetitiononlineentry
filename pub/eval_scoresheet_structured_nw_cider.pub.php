<?php 
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
<h3 class="section-heading mt-4 pt-4"><?php echo $label_appearance; ?></h3>

<!-- Appearance: NW Cider Color -->
<div class="mb-3 row">
    <div class="col-12 col-sm-12 col-md-3">
		<label class="form-label" for="evalAppearanceColor"><strong><?php echo $label_color; ?></strong></label>
	</div>
    <div class="col-12 col-sm-12 col-md-3 small">
	    <?php foreach ($color_array as $value) { ?>
	    <div class="form-check">
	    	<input class="form-check-input" type="radio" name="evalAppearanceColorChoice" value="<?php echo $value; ?>" <?php if (($action == "add") && ($value == $label_pale)) echo "checked"; if ($action == "edit") { if ((isset($appearance_data['evalAppearanceColor'])) && ($appearance_data['evalAppearanceColor'] == $value)) echo "checked"; } ?>>
	        <label class="form-check-label"><?php echo $value; ?></label>
	    </div>
	    <?php } ?>
	    <div class="form-check">
	    	<input class="form-check-input" id="evalAppearanceColorOtherChoice" type="radio" name="evalAppearanceColorChoice" value="999"
	    	<?php if ($action == "edit") if ((isset($appearance_data['evalAppearanceColorOther'])) && (!in_array($appearance_data['evalAppearanceColor'],$color_array))) echo "checked"; ?>>
	        <label class="form-check-label"><?php echo $label_other; ?></label>
	    </div>
	    <div class="mt-2" id="evalAppearanceColorOther">
	    	<input class="form-control" type="text" name="evalAppearanceColorOther" maxlength="50" placeholder="<?php echo $label_other." - ".$evaluation_info_101; ?>" value="<?php if ($action == "edit") { if (isset($appearance_data['evalAppearanceColorOther'])) echo htmlentities($appearance_data['evalAppearanceColorOther']); } ?>">
	    	<div class="help-block invalid-feedback"></div>
		</div>
	</div>
	<div class="col-12 col-sm-12 col-md-6 small">
		<div class="form-check">
			<input class="form-check-input" type="checkbox" name="evalAppearanceColorInappr" id="evalAppearanceColornappr" value="1" <?php if ($action == "edit") { if ((isset($appearance_data['evalAppearanceColorInappr'])) && ($appearance_data['evalAppearanceColorInappr'] == "1")) echo "checked"; } ?>>
        	<label class="form-check-label"><?php echo $label_inappropriate; ?></label>
        </div>
	</div>
</div>

<!-- Appearace: Clarity -->
<div class="mb-3 row">
	<div class="col-12 col-sm-12 col-md-3">
		<label class="form-label" for="evalAppearanceClarity"><strong><?php echo $label_clarity; ?></strong></label>
	</div>
	<div class="col-12 col-sm-12 col-md-3 small">
		<div class="ms-2 small">
			<input class="form-control" type="text" id="evalAppearanceClarity" name="evalAppearanceClarity" data-provide="slider" data-slider-ticks="[0,1,2,3,4]" data-slider-ticks-labels='["<?php echo $label_opaque; ?>", "<?php echo $label_cloudy; ?>", "<?php echo $label_hazy; ?>", "<?php echo $label_clear; ?>", "<?php echo $label_brilliant; ?>"]' data-slider-step="1" data-slider-value="<?php if ($action == "edit") echo $appearance_data['evalAppearanceClarity']; ?>" data-slider-tooltip="hide" required>
		</div>
		<div class="help-block invalid-feedback"></div>
	</div>
	<div class="col-12 col-sm-12 col-md-6 small">
		<div class="form-check">
			<input class="form-check-input" type="checkbox" name="evalAppearanceClarityInappr" id="evalAppearanceClarityInappr" value="1" <?php if ($action == "edit") { if ((isset($appearance_data['evalAppearanceClarityInappr'])) && ($appearance_data['evalAppearanceClarityInappr'] == "1")) echo "checked"; } ?>>
        	<label class="form-label"><?php echo $label_inappropriate; ?></label>
        </div>
	</div>
</div>

<!-- Appearance: Carbonation -->
<div class="mb-3 row">
	<div class="col-12 col-sm-12 col-md-3">
		<label class="form-label" for="evalAppearanceCarb"><strong><?php echo $label_carbonation; ?></strong></label>
	</div>
	<div class="col-12 col-sm-12 col-md-3 small">
		<div class="ms-2 small">
			<input class="form-control" type="text" id="evalAppearanceCarb" name="evalAppearanceCarb" data-provide="slider" data-slider-ticks="[0,1,2,3,4]" data-slider-ticks-labels='["<?php echo $label_still; ?>", "", "<?php echo $label_petillant; ?>", "", "<?php echo $label_sparkling; ?>"]' data-slider-step="1" data-slider-value="<?php if ($action == "edit") echo $appearance_data['evalAppearanceCarb']; ?>" data-slider-tooltip="hide" required>
			<div class="help-block invalid-feedback"></div>
		</div>
	</div>
	<div class="col-12 col-sm-12 col-md-6 small">
		<div class="form-check">
			<input class="form-check-input" type="checkbox" name="evalAppearanceCarbInappr" id="evalAppearanceCarbInappr" value="1" <?php if ($action == "edit") { if ((isset($appearance_data['evalAppearanceCarbInappr'])) && ($appearance_data['evalAppearanceCarbInappr'] == "1")) echo "checked"; } ?>>
        	<label class="form-label"><?php echo $label_inappropriate; ?></label>
        </div>
	</div>
</div>

<!-- Aroma -->
<h3 class="section-heading mt-4 pt-4"><?php echo $label_aroma; ?></h3>

<!-- Aroma Characteristics-->
<div class="mb-3 row">
	<div class="col-12 col-sm-12 col-md-3">
		<label class="form-label" for="evalAromaCharacteristics"><strong><?php echo $label_characteristics; ?></strong></label>
	</div>
	<div class="col-md-9 col-sm-12 col-xs-12">
		<input class="form-control" type="text" id="evalAromaCharacteristics" name="evalAromaCharacteristics" placeholder="" value="<?php if ($action == "edit") { if (isset($aroma_data['evalAromaCharacteristics'])) echo htmlentities($aroma_data['evalAromaCharacteristics']); } ?>" required>
	<div class="help-block small invalid-feedback"></div>
	</div>
</div>

<!-- Aroma Intensity -->
<div class="mb-3 row">
	<div class="col-12 col-sm-12 col-md-3">
		<label class="form-label" for="evalAromaIntensity"><strong><?php echo $label_intensity; ?></strong></label>
	</div>
	<div class="col-12 col-sm-12 col-md-3 small">
		<div class="ms-2 small">
			<input class="form-control" type="text" id="evalAromaIntensity" name="evalAromaIntensity" data-provide="slider" data-slider-ticks="[0,1,2,3,4]" data-slider-ticks-labels='["<?php echo $label_low; ?>", "", "<?php echo $label_medium; ?>", "", "<?php echo $label_high; ?>"]' data-slider-step="1" data-slider-value="<?php if ($action == "edit") echo $aroma_data['evalAromaIntensity']; ?>" data-slider-tooltip="hide" required>
		<div class="help-block invalid-feedback"></div>
		</div>
	</div>
	<div class="col-12 col-sm-12 col-md-6 small">
		<div class="form-check">
			<input class="form-check-input" type="checkbox" name="evalAromaIntensityInappr" id="evalAromaIntensityInappr" value="1" <?php if ($action == "edit") { if ((isset($aroma_data['evalAromaIntensityInappr'])) && ($aroma_data['evalAromaIntensityInappr'] == "1")) echo "checked"; } ?>>
        	<label class="form-label"><?php echo $label_inappropriate; ?></label>
        </div>
	</div>
</div>

<!-- Aroma Quality -->
<div class="mb-3 row">
	<div class="col-12 col-sm-12 col-md-3">
		<label class="form-label" for="evalAromaQuality"><strong><?php echo $label_complexity; ?></strong></label>
	</div>
	<div class="col-12 col-sm-12 col-md-3 small">
		<div class="ms-2 small">
			<input class="form-control" type="text" id="evalAromaQuality" name="evalAromaQuality" data-provide="slider" data-slider-ticks="[0,1,2,3,4]" data-slider-ticks-labels='["<?php echo $label_low; ?>", "", "<?php echo $label_medium; ?>", "", "<?php echo $label_high; ?>"]' data-slider-step="1" data-slider-value="<?php if ($action == "edit") echo $aroma_data['evalAromaQuality']; ?>" data-slider-tooltip="hide" required>
		<div class="help-block small invalid-feedback"></div>
		</div>
	</div>
	<div class="col-12 col-sm-12 col-md-6 small">
		<div class="form-check">
			<input class="form-check-input" type="checkbox" name="evalAromaQualityInappr" id="evalAromaQualityInappr" value="1" <?php if ($action == "edit") { if ((isset($aroma_data['evalAromaQualityInappr'])) && ($aroma_data['evalAromaQualityInappr'] == "1")) echo "checked"; } ?>>
        	<label class="form-label"><?php echo $label_inappropriate; ?></label>
        </div>
	</div>
</div>

<!-- Palate -->
<h3 class="section-heading mt-4 pt-4"><?php echo $label_palate; ?></h3>

<!-- Flavor Characteristics-->
<div class="mb-3">
	<div class="row">
		<div class="col-12 col-sm-12 col-md-3">
			<label class="form-label" for="evalFlavorCharacteristics"><strong><?php echo $label_flavor . " - " . $label_characteristics; ?></strong></label>
		</div>
		<div class="col-md-9 col-sm-12 col-xs-12">
			<input class="form-control" type="text" id="evalFlavorCharacteristics" name="evalFlavorCharacteristics" placeholder="" value="<?php if ($action == "edit") { if (isset($flavor_data['evalFlavorCharacteristics'])) echo htmlentities($flavor_data['evalFlavorCharacteristics']); } ?>" required>
		<div class="help-block small invalid-feedback"></div>
		</div>
	</div>
</div>

<!-- Flavor Intensity -->
<div class="mb-3 row">
	<div class="col-12 col-sm-12 col-md-3">
		<label class="form-label" for="evalFlavorIntensity"><strong><?php echo $label_flavor . " - " . $label_intensity; ?></strong></label>
	</div>
	<div class="col-12 col-sm-12 col-md-3 small">
		<div class="ms-2 small">
			<input class="form-control" type="text" id="evalFlavorIntensity" name="evalFlavorIntensity" data-provide="slider" data-slider-ticks="[0,1,2,3,4]" data-slider-ticks-labels='["<?php echo $label_low; ?>", "", "<?php echo $label_medium; ?>", "", "<?php echo $label_high; ?>"]' data-slider-step="1" data-slider-value="<?php if ($action == "edit") echo $flavor_data['evalFlavorIntensity']; ?>" data-slider-tooltip="hide" required>
		<div class="help-block small invalid-feedback"></div>
		</div>
	</div>
	<div class="col-12 col-sm-12 col-md-6 small">
		<div class="form-check">
			<input class="form-check-input" type="checkbox" name="evalFlavorIntensityInappr" id="evalFlavorIntensityInappr" value="1" <?php if ($action == "edit") { if ((isset($flavor_data['evalFlavorIntensityInappr'])) && ($flavor_data['evalFlavorIntensityInappr'] == "1")) echo "checked"; } ?>>
        <label class="form-label"><?php echo $label_inappropriate; ?></label>
        </div>
	</div>
</div>

<!-- Flavor Quality -->
<div class="mb-3 row">
	<div class="col-12 col-sm-12 col-md-3">
		<label class="form-label" for="evalFlavorQuality"><strong><?php echo $label_flavor . " - " . $label_complexity; ?></strong></label>
	</div>
	<div class="col-12 col-sm-12 col-md-3 small">
		<div class="ms-2 small">
			<input class="form-control" type="text" id="evalFlavorQuality" name="evalFlavorQuality" data-provide="slider" data-slider-ticks="[0,1,2,3,4]" data-slider-ticks-labels='["<?php echo $label_low; ?>", "", "<?php echo $label_medium; ?>", "", "<?php echo $label_high; ?>"]' data-slider-step="1" data-slider-value="<?php if ($action == "edit") echo $flavor_data['evalFlavorQuality']; ?>" data-slider-tooltip="hide" required>
		<div class="help-block small invalid-feedback"></div>
		</div>
	</div>
	<div class="col-12 col-sm-12 col-md-6 small">
		<div class="form-check">
			<input class="form-check-input" type="checkbox" name="evalFlavorQualityInappr" id="evalFlavorQualityInappr" value="1" <?php if ($action == "edit") { if ((isset($flavor_data['evalFlavorQualityInappr'])) && ($flavor_data['evalFlavorQualityInappr'] == "1")) echo "checked"; } ?>>
        	<label class="form-label"><?php echo $label_inappropriate; ?></label>
        </div>
	</div>
</div>

<!-- Sweetness -->
<div class="mb-3 row">
    <div class="col-12 col-sm-12 col-md-3">
		<label class="form-label" for="evalMouthfeelSweetness"><strong><?php echo $label_sweetness; ?></strong></label>
	</div>
    <div class="col-12 col-sm-12 col-md-3 small">
    	<div class="form-check">
    		<input class="form-check-input" type="radio" name="evalMouthfeelSweetness" value="<?php echo $label_dry; ?>" <?php if ($action == "add") echo "checked"; if ($action == "edit") { if ((isset($mouthfeel_data['evalAppearanceColor'])) && ($mouthfeel_data['evalMouthfeelSweetness'] == $label_dry)) echo "checked"; } ?>>
    		<label class="form-label"><?php echo $label_dry; ?></label>
    	</div>

    	<div class="form-check">
    		<input class="form-check-input" type="radio" name="evalMouthfeelSweetness" value="<?php echo $label_medium_dry; ?>" <?php if ($action == "edit") { if ((isset($mouthfeel_data['evalMouthfeelSweetness'])) && ($mouthfeel_data['evalMouthfeelSweetness'] == $label_medium_dry)) echo "checked"; } ?>>
    		<label class="form-label"><?php echo $label_medium_dry; ?></label>
    	</div>

    	<div class="form-check">
    		<input class="form-check-input" type="radio" name="evalMouthfeelSweetness" value="<?php echo $label_medium; ?>" <?php if ($action == "edit") { if ((isset($mouthfeel_data['evalMouthfeelSweetness'])) && ($mouthfeel_data['evalMouthfeelSweetness'] == $label_medium)) echo "checked"; } ?>>
    		<label class="form-label"><?php echo $label_medium; ?></label>
    	</div>

    	<div class="form-check">
    		<input class="form-check-input" type="radio" name="evalMouthfeelSweetness" value="<?php echo $label_medium_sweet; ?>" <?php if ($action == "edit") { if ((isset($mouthfeel_data['evalMouthfeelSweetness'])) && ($mouthfeel_data['evalMouthfeelSweetness'] == $label_medium_sweet)) echo "checked"; } ?>>
    		<label class="form-label"><?php echo $label_medium_sweet; ?></label>
    	</div>

    	<div class="form-check">
    		<input class="form-check-input" type="radio" name="evalMouthfeelSweetness" value="<?php echo $label_sweet; ?>" <?php if ($action == "edit") { if ((isset($mouthfeel_data['evalMouthfeelSweetness'])) && ($mouthfeel_data['evalMouthfeelSweetness'] == $label_sweet)) echo "checked"; } ?>>
    		<label class="form-label"><?php echo $label_sweet; ?></label>
    	</div>
	</div>
	<div class="col-12 col-sm-12 col-md-6 small">
		<div class="form-check">
			<input class="form-check-input" type="checkbox" name="evalMouthfeelSweetnessInappr" id="evalFlavorSweetnessInappr" value="1" <?php if ($action == "edit") { if ((isset($mouthfeel_data['evalMouthfeelSweetnessInappr'])) && ($mouthfeel_data['evalMouthfeelSweetnessInappr'] == "1")) echo "checked"; } ?>>
        	<label class="form-label"><?php echo $label_inappropriate; ?></label>
        </div>
	</div>
</div>

<!-- Acidity -->
<div class="mb-3 row">
	<div class="col-12 col-sm-12 col-md-3">
		<label class="form-label" for="evalMouthfeelAcidity"><strong><?php echo $label_acidity; ?></strong></label>
	</div>
	<div class="col-12 col-sm-12 col-md-3 small">
		<div class="ms-2 small">
			<input class="form-control" type="text" id="evalMouthfeelAcidity" name="evalMouthfeelAcidity" data-provide="slider" data-slider-ticks="[0,1,2,3,4]" data-slider-ticks-labels='["<?php echo $label_low; ?>", "", "<?php echo $label_medium; ?>", "", "<?php echo $label_high; ?>"]' data-slider-step="1" data-slider-value="<?php if ($action == "edit") echo $mouthfeel_data['evalMouthfeelAcidity']; ?>" data-slider-tooltip="hide" required>
		<div class="help-block small invalid-feedback"></div>
		</div>
	</div>
	<div class="col-12 col-sm-12 col-md-6 small">
		<div class="form-check">
			<input class="form-check-input" type="checkbox" name="evalMouthfeelAcidityInappr" id="evalMouthfeelAcidityInappr" value="1" <?php if ($action == "edit") { if ((isset($mouthfeel_data['evalMouthfeelAcidityInappr'])) && ($mouthfeel_data['evalMouthfeelAcidityInappr'] == "1")) echo "checked"; } ?>>
        	<label class="form-label"><?php echo $label_inappropriate; ?></label>
        </div>
	</div>
</div>

<!-- Tannin Bitterness -->
<div class="mb-3 row">
	<div class="col-12 col-sm-12 col-md-3">
		<label class="form-label" for="evalMouthfeelTanninBitter"><strong><?php echo $label_tannin . " - " . $label_bitterness; ?></strong></label>
	</div>
	<div class="col-12 col-sm-12 col-md-3 small">
		<div class="ms-2 small">
			<input class="form-control" type="text" id="evalMouthfeelTanninBitter" name="evalMouthfeelTanninBitter" data-provide="slider" data-slider-ticks="[0,1,2,3,4]" data-slider-ticks-labels='["<?php echo $label_low; ?>", "", "<?php echo $label_medium; ?>", "", "<?php echo $label_high; ?>"]' data-slider-step="1" data-slider-value="<?php if ($action == "edit") echo $mouthfeel_data['evalMouthfeelTanninBitter']; ?>" data-slider-tooltip="hide" required>
		<div class="help-block small invalid-feedback"></div>
		</div>
	</div>
	<div class="col-12 col-sm-12 col-md-6 small">
		<div class="form-check">
			<input class="form-check-input" type="checkbox" name="evalMouthfeelTanninBitterInappr" id="evalMouthfeelTanninBitterInappr" value="1" <?php if ($action == "edit") { if ((isset($mouthfeel_data['evalMouthfeelTanninBitterInappr'])) && ($mouthfeel_data['evalMouthfeelTanninBitterInappr'] == "1")) echo "checked"; } ?>>
        	<label class="form-label"><?php echo $label_inappropriate; ?></label>
        </div>
	</div>
</div>

<!-- Tannin Astringency -->
<div class="mb-3 row">
	<div class="col-12 col-sm-12 col-md-3">
		<label class="form-label" for="evalMouthfeelTanninAstringency"><strong><?php echo $label_tannin . " - " . $label_astringency; ?></strong></label>
	</div>
	<div class="col-12 col-sm-12 col-md-3 small">
		<div class="ms-2 small">
			<input class="form-control" type="text" id="evalMouthfeelTanninAstringency" name="evalMouthfeelTanninAstringency" data-provide="slider" data-slider-ticks="[0,1,2,3,4]" data-slider-ticks-labels='["<?php echo $label_low; ?>", "", "<?php echo $label_medium; ?>", "", "<?php echo $label_high; ?>"]' data-slider-step="1" data-slider-value="<?php if ($action == "edit") echo $mouthfeel_data['evalMouthfeelTanninAstringency']; ?>" data-slider-tooltip="hide" required>
		<div class="help-block small invalid-feedback"></div>
		</div>
	</div>
	<div class="col-12 col-sm-12 col-md-6 small">
		<div class="form-check">
			<input class="form-check-input" type="checkbox" name="evalMouthfeelTanninAstringencyInappr" id="evalMouthfeelTanninAstringencyInappr" value="1" <?php if ($action == "edit") { if ((isset($mouthfeel_data['evalMouthfeelTanninAstringencyInappr'])) && ($mouthfeel_data['evalMouthfeelTanninAstringencyInappr'] == "1")) echo "checked"; } ?>>
        	<label class="form-label"><?php echo $label_inappropriate; ?></label>
        </div>
	</div>
</div>

<!-- Body -->
<div class="mb-3 row">
	<div class="col-12 col-sm-12 col-md-3">
		<label class="form-label" for="evalMouthfeelBody"><strong><?php echo $label_body; ?></strong></label>
	</div>
	<div class="col-12 col-sm-12 col-md-3 small">
		<div class="ms-2 small">
			<input class="form-control" type="text" id="evalMouthfeelBody" name="evalMouthfeelBody" data-provide="slider" data-slider-ticks="[0,1,2,3,4]" data-slider-ticks-labels='["<?php echo $label_low; ?>", "", "<?php echo $label_medium; ?>", "", "<?php echo $label_high; ?>"]' data-slider-step="1" data-slider-value="<?php if ($action == "edit") echo $mouthfeel_data['evalMouthfeelBody']; ?>" data-slider-tooltip="hide" required>
		<div class="help-block small invalid-feedback"></div>
		</div>
	</div>
	<div class="col-12 col-sm-12 col-md-6 small">
		<div class="form-check">
			<input class="form-check-input" type="checkbox" name="evalMouthfeelBodyInappr" id="evalMouthfeelBodyInappr" value="1" <?php if ($action == "edit") { if ((isset($mouthfeel_data['evalMouthfeelBodyInappr'])) && ($mouthfeel_data['evalMouthfeelBodyInappr'] == "1")) echo "checked"; } ?>>
        	<label class="form-label"><?php echo $label_inappropriate; ?></label>
        </div>
	</div>
</div>

<!-- Length -->
<div class="mb-3 row">
	<div class="col-12 col-sm-12 col-md-3">
		<label class="form-label" for="evalMouthfeelLength"><strong><?php echo $label_length; ?></strong></label>
	</div>
	<div class="col-12 col-sm-12 col-md-3 small">
		<div class="ms-2 small">
			<input class="form-control" type="text" id="evalMouthfeelLength" name="evalMouthfeelLength" data-provide="slider" data-slider-ticks="[0,1,2,3,4]" data-slider-ticks-labels='["<?php echo $label_low; ?>", "", "<?php echo $label_medium; ?>", "", "<?php echo $label_high; ?>"]' data-slider-step="1" data-slider-value="<?php if ($action == "edit") echo $mouthfeel_data['evalMouthfeelLength']; ?>" data-slider-tooltip="hide" required>
		<div class="help-block small invalid-feedback"></div>
		</div>
	</div>
	<div class="col-12 col-sm-12 col-md-6 small">
		<div class="form-check">
			<input class="form-check-input" type="checkbox" name="evalMouthfeelLengthInappr" id="evalMouthfeelLengthInappr" value="1" <?php if ($action == "edit") { if ((isset($mouthfeel_data['evalMouthfeelLengthInappr'])) && ($mouthfeel_data['evalMouthfeelLengthInappr'] == "1")) echo "checked"; } ?>>
        	<label class="form-label"><?php echo $label_inappropriate; ?></label>
        </div>
	</div>
</div>

<!-- Balance -->
<div class="mb-3 row">
	<div class="col-12 col-sm-12 col-md-3">
		<label class="form-label" for="evalMouthfeelBalance"><strong><?php echo $label_balance; ?></strong></label>
	</div>
	<div class="col-12 col-sm-12 col-md-3 small">
		<div class="ms-2 small">
			<input class="form-control" type="text" id="evalMouthfeelBalance" name="evalMouthfeelBalance" data-provide="slider" data-slider-ticks="[0,1,2,3,4]" data-slider-ticks-labels='["<?php echo $label_low; ?>", "", "<?php echo $label_medium; ?>", "", "<?php echo $label_high; ?>"]' data-slider-step="1" data-slider-value="<?php if ($action == "edit") echo $mouthfeel_data['evalMouthfeelBalance']; ?>" data-slider-tooltip="hide" required>
		<div class="help-block small invalid-feedback"></div>
		</div>
	</div>
	<div class="col-12 col-sm-12 col-md-6 small">
		<div class="form-check">
        	<input class="form-check-input" type="checkbox" name="evalMouthfeelBalanceInappr" id="evalMouthfeelBalanceInappr" value="1" <?php if ($action == "edit") { if ((isset($mouthfeel_data['evalMouthfeelBalanceInappr'])) && ($mouthfeel_data['evalMouthfeelBalanceInappr'] == "1")) echo "checked"; } ?>>
        	<label class="form-label"><?php echo $label_inappropriate; ?></label>
        </div>
	</div>
</div>

<!-- Overall Impression -->
<h3 class="section-heading mt-4 pt-4"><?php echo $label_summary_overall_impression; ?></h3>

<!-- Overall Feedback -->
<div class="mb-3">
    <label class="form-label" for="evalOverallComments"><strong><?php echo $label_feedback; ?></strong></label>
    <textarea class="form-control" id="evalOverallComments" name="evalOverallComments" rows="6" placeholder="" data-error="<?php echo $evaluation_info_061; ?>" required><?php if ($action == "edit") echo htmlentities($row_eval['evalOverallComments']); ?></textarea>
    <div class="help-block small invalid-feedback"><?php echo $evaluation_info_061; ?></div>
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