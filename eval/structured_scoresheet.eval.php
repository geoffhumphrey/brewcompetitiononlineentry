<?php

$northwest = FALSE;
if ($_SESSION['jPrefsScoresheet'] == 4) $northwest = TRUE;

if ($beer) {
	$aroma_ticks = $aroma_ticks_beer;
	$flavor_ticks = $flavor_ticks_beer;
	$mouthfeel_ticks = $mouthfeel_ticks_beer;
	$flaws = $flaws_structured_beer;
}

if ($mead) {
	$aroma_ticks = $aroma_ticks_mead;
	$flavor_ticks = $flavor_ticks_mead;
	$flaws = $flaws_structured_mead;
}

if ($cider) {
	$aroma_ticks = $aroma_ticks_cider;
	$flavor_ticks = $flavor_ticks_cider;
	$flaws = $flaws_structured_cider;
}

asort($flaws);

if ($action == "edit") {
	$aroma_data = json_decode($row_eval['evalAromaChecklist'], true);
	$appearance_data = json_decode($row_eval['evalAppearanceChecklist'], true);
	$flavor_data = json_decode($row_eval['evalFlavorChecklist'], true);
	$mouthfeel_data = json_decode($row_eval['evalMouthfeelChecklist'], true);
}

if (($cider) && ($northwest)) include (EVALS.'nw_structured_cider.eval.php');

else {
?>

<!-- Structured Evaluation Form -->
<input type="hidden" name="evalFormType" value="3">

<!-- Aroma -->
<h3 class="section-heading"><?php if ($cider || $mead) echo $label_bouquet."/"; echo $label_aroma; ?></h3>
<!-- Aroma Score -->
<div class="form-group">
	<div class="row">
		<div class="col-md-3 col-sm-12 col-xs-12">
    		<label for="evalAromaScore"><?php echo $label_score; ?> (<?php echo $aroma_points; ?> <?php echo strtolower($label_possible_points); ?>)</label>
    	</div>
    	<div class="col-md-9 col-sm-12 col-xs-12">
	        <select class="form-control selectpicker score-choose" name="evalAromaScore" id="type" data-size="10" required>
	        <option value=""></option>
	        <?php 
	        for($i=$aroma_points; $i>=1; $i--) {
	          if (($action == "edit") && ($i == $row_eval['evalAromaScore'])) $selected = "selected";
	          else $selected = "";
			?>
	        <option value="<?php echo $i; ?>" <?php echo $selected; ?>><?php echo $i; ?></option>
	        <?php } ?>
	    	</select>
	    	<div class="help-block small with-errors"></div>
	    </div>
    </div>
</div>
<?php foreach ($aroma_ticks as $key => $value) { ?>
<div class="form-group">
	<div class="row">
		<div class="col-md-3 col-sm-12 col-xs-12">
			<label for="<?php echo $value; ?>"><?php echo $key; ?></label>
		</div>
		<div class="col-md-3 col-sm-12 col-xs-12 small">
			<div style="margin-left: 10px">
				<input class="form-control" type="text" id="<?php echo $value; ?>" name="<?php echo $value; ?>" data-provide="slider" data-slider-ticks="[0,1,2,3,4,5,6,7,8,9,10]" data-slider-ticks-labels='["<?php echo $label_none; ?>", "", "<?php echo $label_low; ?>", "", "", "<?php echo $label_med; ?>", "", "", "", "", "<?php echo $label_high; ?>"]' data-slider-step="1" data-slider-value="<?php if ($action == "edit") echo $aroma_data[$value]; ?>" data-slider-tooltip="hide" required>
			</div>
		</div>
		<div class="col-md-2 col-sm-12 col-xs-12 small">
			<div class="checkbox">
            <label>
                <input type="checkbox" name="<?php echo $value; ?>Inappr" id="<?php echo $value; ?>Inappr" value="1" <?php if ($action == "edit") { if ((isset($aroma_data[$value.'Inappr'])) && ($aroma_data[$value.'Inappr'] == "1")) echo "checked"; } ?>> <?php echo $label_inappropriate; ?>
            </label>
            </div>
		</div>
		<div class="col-md-4 col-sm-12 col-xs-12">
			<input class="form-control" type="text" id="<?php echo $value; ?>Comments" name="<?php echo $value; ?>Comments" placeholder="<?php echo $label_comments; ?>" value="<?php if ($action == "edit") { if (isset($aroma_data[$value.'Comments'])) echo htmlentities($aroma_data[$value.'Comments']); } ?>"> 
		</div>
	</div>
	<div class="help-block small with-errors"></div>
</div>
<?php } ?>
<!-- Aroma: Other -->
<div class="form-group">
	<div class="row">
		<div class="col-md-3 col-sm-12 col-xs-12">
			<label for="evalAromaOther"><?php echo $label_other; ?></label>
		</div>
		<div class="col-md-9 col-sm-12 col-xs-12">
			<input class="form-control" type="text" id="evalAromaOther" name="evalAromaOther" placeholder="" value="<?php if ($action == "edit") { if (isset($aroma_data['evalAromaOther'])) echo htmlentities($aroma_data['evalAromaOther']); } ?>">
			<div class="help-block small"><?php echo $evaluation_info_034; ?></div>
		</div>	
	</div>
</div>

<!-- Appearance -->
<h3 class="section-heading"><?php echo $label_appearance; ?></h3>
<!-- Appearance Score -->
<div class="form-group">
	<div class="row">
		<div class="col-md-3 col-sm-12 col-xs-12">
			<label for="evalAppearanceScore"><?php echo $label_score; ?> (<?php echo $appearance_points; ?> <?php echo strtolower($label_possible_points); ?>)</label>
		</div>
    	<div class="col-md-9 col-sm-12 col-xs-12">
        <select class="form-control selectpicker score-choose" name="evalAppearanceScore" id="type" data-size="10" required>
	        <option value=""></option>
	        <?php for($i=$appearance_points; $i>=1; $i--) {
	      			if (($action == "edit") && ($i == $row_eval['evalAppearanceScore'])) $selected = "selected";
	      			else $selected = "";
	      		?>
	        <option value="<?php echo $i; ?>" <?php echo $selected; ?>><?php echo $i; ?></option>
	        <?php } ?>
	    </select>
	    <div class="help-block small with-errors"></div>
		</div>
	</div>
</div>
<?php if ($beer) { ?>
<!-- Appearance: Beer Color -->
<div class="form-group">
	<div class="row">
		<div class="col-md-3 col-sm-12 col-xs-12">
			<label for="evalAppearanceColor"><?php echo $label_color; ?></label>
		</div>
		<div class="col-md-3 col-sm-12 col-xs-12 small">
			<div style="margin-left: 10px">
				<input class="form-control" type="text" id="evalAppearanceColor" name="evalAppearanceColor" data-provide="slider" data-slider-ticks="[0,1,2,3,4,5,6,7,8,9,10]" data-slider-ticks-labels='["<?php echo $label_yellow; ?>", "", "", "", "", "<?php echo $label_amber; ?>", "", "", "", "", "<?php echo $label_black; ?>"]' data-slider-step="1" data-slider-value="<?php if ($action == "edit") echo $appearance_data['evalAppearanceColor']; ?>" data-slider-tooltip="hide" required>
			</div>
		</div>
		<div class="col-md-2 col-sm-12 col-xs-12 small">
			<div class="checkbox">
            <label>
                <input type="checkbox" name="evalAppearanceColorInappr" id="evalAppearanceColorInappr" value="1" <?php if ($action == "edit") { if ((isset($appearance_data['evalAppearanceColorInappr'])) && ($appearance_data['evalAppearanceColorInappr'] == "1")) echo "checked"; } ?>> <?php echo $label_inappropriate; ?>
            </label>
            </div>
		</div>
		<div class="col-md-4 col-sm-12 col-xs-12">
			<input class="form-control" type="text" id="evalAppearanceColorOther" name="evalAppearanceColorOther" placeholder="<?php echo $label_other; ?>" value="<?php if ($action == "edit") { if (isset($appearance_data['evalAppearanceColorOther'])) echo htmlentities($appearance_data['evalAppearanceColorOther']); } ?>"> 
		</div>
	</div>
	<div class="help-block small with-errors"></div>
</div>
<?php } // end if ($beer)  ?>
<?php if ($mead) { ?>
<!-- Appearance: Mead Color -->
<div class="form-group">
	<div class="row">
		<div class="col-md-3 col-sm-12 col-xs-12">
			<label for="evalAppearanceColor"><?php echo $label_color; ?></label>
		</div>
		<div class="col-md-3 col-sm-12 col-xs-12 small">
			<div style="margin-left: 10px">
				<input class="form-control" type="text" id="evalAppearanceColor" name="evalAppearanceColor" data-provide="slider" data-slider-ticks="[0,1,2,3,4,5,6,7,8,9,10]" data-slider-ticks-labels='["<?php echo $label_white; ?>", "", "<?php echo $label_yellow; ?>", "", "", "<?php echo $label_gold; ?>", "", "<?php echo $label_amber; ?>", "", "", "<?php echo $label_brown; ?>"]' data-slider-step="1" data-slider-value="<?php if ($action == "edit") echo $appearance_data['evalAppearanceColor']; ?>" data-slider-tooltip="hide" required>
			</div>
		</div>
		<div class="col-md-2 col-sm-12 col-xs-12 small">
			<div class="checkbox">
            <label>
                <input type="checkbox" name="evalAppearanceColorInappr" id="evalAppearanceColorInappr" value="1" <?php if ($action == "edit") { if ((isset($appearance_data['evalAppearanceColorInappr'])) && ($appearance_data['evalAppearanceColorInappr'] == "1")) echo "checked"; } ?>> <?php echo $label_inappropriate; ?>
            </label>
            </div>
		</div>
		<div class="col-md-4 col-sm-12 col-xs-12">
			<input class="form-control" type="text" id="evalAppearanceColorOther" name="evalAppearanceColorOther" placeholder="<?php echo $label_other; ?>"value="<?php if ($action == "edit") { if (isset($appearance_data['evalAppearanceColorOther'])) echo htmlentities($appearance_data['evalAppearanceColorOther']); } ?>"> 
		</div>
	</div>
	<div class="help-block small with-errors"></div>
</div>
<?php } // end if ($mead)  ?>
<?php if ($cider) { ?>
<!-- Appearance: Cider Color -->
<div class="form-group">
	<div class="row">
		<div class="col-md-3 col-sm-12 col-xs-12">
			<label for="evalAppearanceColor"><?php echo $label_color; ?></label>
		</div>
		<div class="col-md-3 col-sm-12 col-xs-12 small">
			<div style="margin-left: 10px">
				<input class="form-control" type="text" id="evalAppearanceColor" name="evalAppearanceColor" data-provide="slider" data-slider-ticks="[0,1,2,3,4,5,6,7,8,9,10]" data-slider-ticks-labels='["<?php echo $label_pale; ?>", "", "<?php echo $label_yellow; ?>", "", "", "<?php echo $label_gold; ?>", "", "<?php echo $label_amber; ?>", "", "", "<?php echo $label_brown; ?>"]' data-slider-step="1" data-slider-value="<?php if ($action == "edit") echo $appearance_data['evalAppearanceColor']; ?>" data-slider-tooltip="hide" required>
			</div>
		</div>
		<div class="col-md-2 col-sm-12 col-xs-12 small">
			<div class="checkbox">
            <label>
                <input type="checkbox" name="evalAppearanceColorInappr" id="evalAppearanceColorInappr" value="1" <?php if ($action == "edit") { if ((isset($appearance_data['evalAppearanceColorInappr'])) && ($appearance_data['evalAppearanceColorInappr'] == "1")) echo "checked"; } ?>> <?php echo $label_inappropriate; ?>
            </label>
            </div>
		</div>
		<div class="col-md-4 col-sm-12 col-xs-12">
			<input class="form-control" type="text" id="evalAppearanceColorOther" name="evalAppearanceColorOther" placeholder="<?php echo $label_other; ?>" value="<?php if ($action == "edit") { if (isset($appearance_data['evalAppearanceColorOther'])) echo htmlentities($appearance_data['evalAppearanceColorOther']); } ?>"> 
		</div>
	</div>
	<div class="help-block small with-errors"></div>
</div>
<?php } // end if ($cider) ?>
<!-- Appearace: Clarity (All Style Types) -->
<div class="form-group">
	<div class="row">
		<div class="col-md-3 col-sm-12 col-xs-12">
			<label for="evalAppearanceClarity"><?php echo $label_clarity; ?></label>
		</div>
		<div class="col-md-3 col-sm-12 col-xs-12 small">
			<div style="margin-left: 10px">
				<input class="form-control" type="text" id="evalAppearanceClarity" name="evalAppearanceClarity" data-provide="slider" data-slider-ticks="[0,1,2,3,4,5,6,7,8,9,10]" data-slider-ticks-labels='["<?php echo $label_opaque; ?>", "", "", "", "", "<?php echo $label_hazy; ?>", "", "", "", "", "<?php echo $label_brilliant; ?>"]' data-slider-step="1" data-slider-value="<?php if ($action == "edit") echo $appearance_data['evalAppearanceClarity']; ?>" data-slider-tooltip="hide" required>
			</div>
		</div>
		<div class="col-md-2 col-sm-12 col-xs-12 small">
			<div class="checkbox">
            <label>
                <input type="checkbox" name="evalAppearanceClarityInappr" id="evalAppearanceClarityInappr" value="1" <?php if ($action == "edit") { if ((isset($appearance_data['evalAppearanceClarityInappr'])) && ($appearance_data['evalAppearanceClarityInappr'] == "1")) echo "checked"; } ?>> <?php echo $label_inappropriate; ?>
            </label>
            </div>
		</div>
	</div>
	<div class="help-block small with-errors"></div>
</div>
<?php if ($beer) { ?>
<!-- Appearance: Other (Beer) -->
<div class="form-group">
	<div class="row">
		<div class="col-md-3 col-sm-3 col-xs-12">
			<label for="evalAppearanceOther"><?php echo $label_other; ?></label>
		</div>
		<div class="col-md-9 col-sm-9 col-xs-12">
			<input class="form-control" type="text" id="evalAppearanceOther" name="evalAppearanceOther" placeholder="<?php echo $label_legs." ".$label_lacing." ".$label_particulate; ?>" value="<?php if ($action == "edit") { if (isset($appearance_data['evalAppearanceOther'])) echo htmlentities($appearance_data['evalAppearanceOther']); } ?>">
		</div>	
	</div>
</div>
<!-- Appearance: Beer Head  -->
<h4><?php echo $label_head; ?></h4>
<!-- Appearance: Beer Head Size -->
<div class="form-group">
	<div class="row">
		<div class="col-md-3 col-sm-12 col-xs-12">
			<label for="evalAppearanceHeadSize"><?php echo $label_size; ?></label>
		</div>
		<div class="col-md-3 col-sm-12 col-xs-12 small">
			<div style="margin-left: 10px">
				<input class="form-control" type="text" id="evalAppearanceHeadSize" name="evalAppearanceHeadSize" data-provide="slider" data-slider-ticks="[0,1,2,3,4,5,6,7,8,9,10]" data-slider-ticks-labels='["<?php echo $label_none; ?>", "", "<?php echo $label_small; ?>", "", "", "<?php echo $label_med; ?>", "", "", "", "", "<?php echo $label_large; ?>"]' data-slider-step="1" data-slider-value="<?php if ($action == "edit") echo $appearance_data['evalAppearanceHeadSize']; ?>" data-slider-tooltip="hide" required>
			</div>
		</div>
		<div class="col-md-2 col-sm-12 col-xs-12 small">
			<div class="checkbox">
            <label>
                <input type="checkbox" name="evalAppearanceHeadSizeInappr" id="evalAppearanceHeadSizeInappr" value="1" <?php if ($action == "edit") { if ((isset($appearance_data['evalAppearanceHeadSizeInappr'])) && ($appearance_data['evalAppearanceHeadSizeInappr'] == "1")) echo "checked"; } ?>> <?php echo $label_inappropriate; ?>
            </label>
            </div>
		</div>
	</div>
	<div class="help-block small with-errors"></div>
</div>
<!-- Appearance: Beer Head Retention -->
<div class="form-group">
	<div class="row">
		<div class="col-md-3 col-sm-12 col-xs-12">
			<label for="evalAppearanceHeadReten"><?php echo $label_retention; ?></label>
		</div>
		<div class="col-md-3 col-sm-12 col-xs-12 small">
			<div style="margin-left: 10px">
				<input class="form-control" type="text" id="evalAppearanceHeadReten" name="evalAppearanceHeadReten" data-provide="slider" data-slider-ticks="[0,1,2,3,4,5,6,7,8,9,10]" data-slider-ticks-labels='["<?php echo $label_quick; ?>", "", "", "", "", "", "", "", "<?php echo $label_long_lasting; ?>", "", ""]' data-slider-step="1" data-slider-value="<?php if ($action == "edit") echo $appearance_data['evalAppearanceHeadReten']; ?>" data-slider-tooltip="hide" required>
			</div>
		</div>
		<div class="col-md-2 col-sm-12 col-xs-12 small">
			<div class="checkbox">
            <label>
                <input type="checkbox" name="evalAppearanceHeadRetenInappr" id="evalAppearanceHeadRetenInappr" value="1" <?php if ($action == "edit") { if ((isset($appearance_data['evalAppearanceHeadRetenInappr'])) && ($appearance_data['evalAppearanceHeadRetenInappr'] == "1")) echo "checked"; } ?>> <?php echo $label_inappropriate; ?>
            </label>
            </div>
		</div>
	</div>
	<div class="help-block small with-errors"></div>
</div>
<!-- Appearance: Beer Head Color -->
<div class="form-group">
	<div class="row">
		<div class="col-md-3 col-sm-12 col-xs-12">
			<label for="evalAppearanceHeadColor"><?php echo $label_color; ?></label>
		</div>
		<div class="col-md-3 col-sm-12 col-xs-12 small">
			<div style="margin-left: 10px">
				<input class="form-control" type="text" id="evalAppearanceHeadColor" name="evalAppearanceHeadColor" data-provide="slider" data-slider-ticks="[0,1,2,3,4,5,6,7,8,9,10]" data-slider-ticks-labels='["<?php echo $label_white; ?>", "", "", "<?php echo $label_ivory; ?>", "", "", "", "<?php echo $label_beige; ?>", "", "", "<?php echo $label_tan; ?>"]' data-slider-step="1" data-slider-value="<?php if ($action == "edit") echo $appearance_data['evalAppearanceHeadColor']; ?>" data-slider-tooltip="hide" required>
			</div>
		</div>
		<div class="col-md-2 col-sm-12 col-xs-12 small">
			<div class="checkbox">
            <label>
                <input type="checkbox" name="evalAppearanceHeadColorInappr" id="evalAppearanceHeadColorInappr" value="1" <?php if ($action == "edit") { if ((isset($appearance_data['evalAppearanceHeadColorInappr'])) && ($appearance_data['evalAppearanceHeadColorInappr'] == "1")) echo "checked"; } ?>> <?php echo $label_inappropriate; ?>
            </label>
            </div>
		</div>
	</div>
	<div class="help-block small with-errors"></div>
</div>
<?php } ?>
<?php if (($cider) || ($mead)) { ?>
<!-- Appearance: Legs (Mead and Cider) -->
<div class="form-group">
	<div class="row">
		<div class="col-md-3 col-sm-12 col-xs-12">
			<label for="evalAppearanceLegs"><?php echo $label_legs; ?></label>
		</div>
		<div class="col-md-3 col-sm-12 col-xs-12 small">
			<div style="margin-left: 10px">
				<input class="form-control" type="text" id="evalAppearanceLegs" name="evalAppearanceLegs" data-provide="slider" data-slider-ticks="[0,1,2,3,4,5,6,7,8,9,10]" data-slider-ticks-labels='["<?php echo $label_none; ?>", "", "<?php echo $label_thin; ?>", "", "", "<?php echo $label_med; ?>", "", "", "", "", "<?php echo $label_viscous; ?>"]' data-slider-step="1" data-slider-value="<?php if ($action == "edit") echo $appearance_data['evalAppearanceLegs']; ?>" data-slider-tooltip="hide" required>
			</div>
		</div>
		<div class="col-md-2 col-sm-12 col-xs-12 small">
			<div class="checkbox">
            <label>
                <input type="checkbox" name="evalAppearanceLegsInappr" id="evalAppearanceLegsInappr" value="1" <?php if ($action == "edit") { if ((isset($appearance_data['evalAppearanceLegsInappr'])) && ($appearance_data['evalAppearanceLegsInappr'] == "1")) echo "checked"; } ?>> <?php echo $label_inappropriate; ?>
            </label>
            </div>
		</div>
	</div>
	<div class="help-block small with-errors"></div>
</div>
<!-- Appearance: Carbonation (Mead and Cider) -->
<div class="form-group">
	<div class="row">
		<div class="col-md-3 col-sm-12 col-xs-12">
			<label for="evalAppearanceCarb"><?php echo $label_carbonation; ?></label>
		</div>
		<div class="col-md-3 col-sm-12 col-xs-12 small">
			<div style="margin-left: 10px">
				<input class="form-control" type="text" id="evalAppearanceCarb" name="evalAppearanceCarb" data-provide="slider" data-slider-ticks="[0,1,2,3,4,5,6,7,8,9,10]" data-slider-ticks-labels='["<?php echo $label_none; ?>", "", "<?php echo $label_low; ?>", "", "", "<?php echo $label_med; ?>", "", "", "", "", "<?php echo $label_high; ?>"]' data-slider-step="1" data-slider-value="<?php if ($action == "edit") echo $appearance_data['evalAppearanceCarb']; ?>" data-slider-tooltip="hide" required>
			</div>
		</div>
		<div class="col-md-2 col-sm-12 col-xs-12 small">
			<div class="checkbox">
            <label>
                <input type="checkbox" name="evalAppearanceCarbInappr" id="evalAppearanceCarbInappr" value="1" <?php if ($action == "edit") { if ((isset($appearance_data['evalAppearanceCarbInappr'])) && ($appearance_data['evalAppearanceCarbInappr'] == "1")) echo "checked"; } ?>> <?php echo $label_inappropriate; ?>
            </label>
            </div>
		</div>
	</div>
	<div class="help-block small with-errors"></div>
</div>
<!-- Appearance: Other (Mead and Cider) -->
<div class="form-group">
	<div class="row">
		<div class="col-md-3 col-sm-3 col-xs-12">
			<label for="evalAppearanceOther"><?php echo $label_other; ?></label>
		</div>
		<div class="col-md-9 col-sm-9 col-xs-12">
			<input class="form-control" type="text" id="evalAppearanceOther" name="evalAppearanceOther" placeholder="" >
			<div class="help-block small"><?php echo $evaluation_info_034; ?></div>
		</div>	
	</div>
</div>
<?php } // end if (($cider) || ($mead)) ?>

<!-- Flavor -->
<h3 class="section-heading"><?php echo $label_flavor; ?></h3>
<!-- Flavor Score -->
<div class="form-group">
	<div class="row">
		<div class="col-md-3 col-sm-12 col-xs-12">
			<label for="evalFlavorScore"><?php echo $label_score; ?> (<?php echo $flavor_points; ?> <?php echo strtolower($label_possible_points); ?>)</label>
		</div>
		<div class="col-md-9 col-sm-12 col-xs-12">
			<select class="form-control selectpicker score-choose" name="evalFlavorScore" id="type" data-size="10" required>
		        <option value=""></option>
		        <?php for($i=$flavor_points; $i>=1; $i--) {
		    			if (($action == "edit") && ($i == $row_eval['evalFlavorScore'])) $selected = "selected";
		    			else $selected = "";
		    		?>
		        <option value="<?php echo $i;?>" <?php echo $selected; ?>><?php echo $i; ?></option>
		        <?php } ?>
		    </select>
	    	<div class="help-block small with-errors"></div>
		</div>
	</div> 
</div>
<?php foreach ($flavor_ticks as $key => $value) { ?>
<div class="form-group">
	<div class="row">
		<div class="col-md-3 col-sm-12 col-xs-12">
			<label for="<?php echo $value; ?>"><?php echo $key; ?></label>
		</div>
		<div class="col-md-3 col-sm-12 col-xs-12 small">
			<div style="margin-left: 10px">
				<input class="form-control" type="text" id="<?php echo $value; ?>" name="<?php echo $value; ?>" data-provide="slider" data-slider-ticks="[0,1,2,3,4,5,6,7,8,9,10]" data-slider-ticks-labels='["<?php echo $label_none; ?>", "", "<?php echo $label_low; ?>", "", "", "<?php echo $label_med; ?>", "", "", "", "", "<?php echo $label_high; ?>"]' data-slider-step="1" data-slider-value="<?php if ($action == "edit") echo $flavor_data[$value]; ?>" data-slider-tooltip="hide" required>
			</div>
		</div>
		<div class="col-md-2 col-sm-12 col-xs-12 small">
			<div class="checkbox">
            <label>
                <input type="checkbox" name="<?php echo $value; ?>Inappr" id="<?php echo $value; ?>Inappr" value="1" <?php if ($action == "edit") { if ((isset($flavor_data[$value.'Inappr'])) && ($flavor_data[$value.'Inappr'] == "1")) echo "checked"; } ?>> <?php echo $label_inappropriate; ?>
            </label>
            </div>
		</div>
		<div class="col-md-4 col-sm-12 col-xs-12">
			<input class="form-control" type="text" id="<?php echo $value; ?>Comments" name="<?php echo $value; ?>Comments" placeholder="<?php echo $label_comments; ?>" value="<?php if ($action == "edit") { if (isset($flavor_data[$value.'Comments'])) echo htmlentities($flavor_data[$value.'Comments']); } ?>"> 
		</div>
	</div>
	<div class="help-block small with-errors"></div>
</div>
<?php } ?>
<?php if ($beer) { ?>
<!-- Flavor: Balance (Beer) -->
<div class="form-group">
	<div class="row">
		<div class="col-md-3 col-sm-12 col-xs-12">
			<label for="<?php echo $value; ?>"><?php echo $label_balance; ?></label>
		</div>
		<div class="col-md-3 col-sm-12 col-xs-12 small">
			<div style="margin-left: 10px">
				<input class="form-control" type="text" id="evalFlavorBalance" name="evalFlavorBalance" data-provide="slider" data-slider-ticks="[0,1,2,3,4,5,6,7,8,9,10]" data-slider-ticks-labels='["<?php echo $label_hoppy; ?>", "", "", "", "", "", "", "", "", "", "<?php echo $label_malty; ?>"]' data-slider-step="1" data-slider-value="<?php if ($action == "edit") echo $flavor_data['evalFlavorBalance']; ?>" data-slider-tooltip="hide" required>
			</div>
		</div>
		<div class="col-md-2 col-sm-12 col-xs-12 small">
			<div class="checkbox">
            <label>
                <input type="checkbox" name="evalFlavorBalanceInappr" id="evalFlavorBalanceInappr" value="1" <?php if ($action == "edit") { if ((isset($flavor_data['evalFlavorBalanceInappr'])) && ($flavor_data['evalFlavorBalanceInappr'] == "1")) echo "checked"; } ?>> <?php echo $label_inappropriate; ?>
            </label>
            </div>
		</div>
		<div class="col-md-4 col-sm-12 col-xs-12">
			<input class="form-control" type="text" id="evalFlavorBalanceComments" name="evalFlavorBalanceComments" placeholder="<?php echo $label_comments; ?>"  value="<?php if ($action == "edit") { if (isset($flavor_data['evalFlavorBalanceComments'])) echo htmlentities($flavor_data['evalFlavorBalanceComments']); } ?>"> 
		</div>
	</div>
	<div class="help-block small with-errors"></div>
</div>
<!-- Flavor: Aftertaste (Beer) -->
<div class="form-group">
	<div class="row">
		<div class="col-md-3 col-sm-12 col-xs-12">
			<label for="<?php echo $value; ?>"><?php echo $label_finish_aftertaste; ?></label>
		</div>
		<div class="col-md-3 col-sm-12 col-xs-12 small">
			<div style="margin-left: 10px">
				<input class="form-control" type="text" id="evalFlavorFinish" name="evalFlavorFinish" data-provide="slider" data-slider-ticks="[0,1,2,3,4,5,6,7,8,9,10]" data-slider-ticks-labels='["<?php echo $label_dry; ?>", "", "", "", "", "", "", "", "", "", "<?php echo $label_sweet; ?>"]' data-slider-step="1" data-slider-value="<?php if ($action == "edit") echo $flavor_data['evalFlavorFinish']; ?>" data-slider-tooltip="hide" required>
			</div>
		</div>
		<div class="col-md-2 col-sm-12 col-xs-12 small">
			<div class="checkbox">
            <label>
                <input type="checkbox" name="evalFlavorFinishInappr" id="evalFlavorFinishInappr" value="1" <?php if ($action == "edit") { if ((isset($flavor_data['evalFlavorFinishInappr'])) && ($flavor_data['evalFlavorFinishInappr'] == "1")) echo "checked"; } ?>> <?php echo $label_inappropriate; ?>
            </label>
            </div>
		</div>
		<div class="col-md-4 col-sm-12 col-xs-12">
			<input class="form-control" type="text" id="evalFlavorFinishComments" name="evalFlavorFinishComments" placeholder="<?php echo $label_comments; ?>" value="<?php if ($action == "edit") { if (isset($flavor_data['evalFlavorFinishComments'])) echo htmlentities($flavor_data['evalFlavorFinishComments']); } ?>"> 
		</div>
	</div>
	<div class="help-block small with-errors"></div>
</div>
<?php } // end if ($beer) ?>

<?php if (($mead) || ($cider)) { ?>
<!-- Flavor: Body (Mead and Cider) -->
<div class="form-group">
	<div class="row">
		<div class="col-md-3 col-sm-12 col-xs-12">
			<label for="evalFlavorBody"><?php echo $label_body; ?></label>
		</div>
		<div class="col-md-3 col-sm-12 col-xs-12 small">
			<div style="margin-left: 10px">
				<input class="form-control" type="text" id="evalFlavorBody" name="evalFlavorBody" data-provide="slider" data-slider-ticks="[0,1,2,3,4,5,6,7,8,9,10]" data-slider-ticks-labels='["<?php echo $label_thin; ?>", "", "", "", "", "", "", "", "", "", "<?php echo $label_full; ?>"]' data-slider-step="1" data-slider-value="<?php if ($action == "edit") echo $flavor_data['evalFlavorBody']; ?>" data-slider-tooltip="hide" required>
			</div>
		</div>
		<div class="col-md-2 col-sm-12 col-xs-12 small">
			<div class="checkbox">
            <label>
                <input type="checkbox" name="evalFlavorBodyInappr" id="evalFlavorBodyInappr" value="1" <?php if ($action == "edit") { if ((isset($flavor_data['evalFlavorBodyInappr'])) && ($flavor_data['evalFlavorBodyInappr'] == "1")) echo "checked"; } ?>> <?php echo $label_inappropriate; ?>
            </label>
            </div>
		</div>
		<div class="col-md-4 col-sm-12 col-xs-12">
			<input class="form-control" type="text" id="evalFlavorBodyComments" name="evalFlavorBodyComments" placeholder="<?php echo $label_comments; ?>" value="<?php if ($action == "edit") { if (isset($flavor_data['evalFlavorBodyComments'])) echo htmlentities($flavor_data['evalFlavorBodyComments']); } ?>"> 
		</div>
	</div>
	<div class="help-block small with-errors"></div>
</div>
<!-- Flavor: Aftertaste (Mead and Cider) -->
<div class="form-group">
	<div class="row">
		<div class="col-md-3 col-sm-12 col-xs-12">
			<label for="<?php echo $value; ?>"><?php echo $label_finish_aftertaste; ?></label>
		</div>
		<div class="col-md-3 col-sm-12 col-xs-12 small">
			<div style="margin-left: 10px">
				<input class="form-control" type="text" id="evalFlavorFinish" name="evalFlavorFinish" data-provide="slider" data-slider-ticks="[0,1,2,3,4,5,6,7,8,9,10]" data-slider-ticks-labels='["<?php echo $label_quick; ?>", "", "", "", "", "", "", "", "<?php echo $label_long_lasting; ?>", "", ""]' data-slider-step="1" data-slider-value="<?php if ($action == "edit") echo $flavor_data['evalFlavorFinish']; ?>" data-slider-tooltip="hide" required>
			</div>
		</div>
		<div class="col-md-2 col-sm-12 col-xs-12 small">
			<div class="checkbox">
            <label>
                <input type="checkbox" name="evalFlavorFinishInappr" id="evalFlavorFinishInappr" value="1" <?php if ($action == "edit") { if ((isset($flavor_data['evalFlavorFinishInappr'])) && ($flavor_data['evalFlavorFinishInappr'] == "1")) echo "checked"; } ?>> <?php echo $label_inappropriate; ?>
            </label>
            </div>
		</div>
		<div class="col-md-4 col-sm-12 col-xs-12">
			<input class="form-control" type="text" id="evalFlavorFinishComments" name="evalFlavorFinishComments" placeholder="<?php echo $label_comments; ?>" value="<?php if ($action == "edit") { if (isset($flavor_data['evalFlavorFinishComments'])) echo htmlentities($flavor_data['evalFlavorFinishComments']); } ?>"> 
		</div>
	</div>
	<div class="help-block small with-errors"></div>
</div>
<!-- Flavor: Balance (Mead and Cider) -->
<div class="form-group">
	<div class="row">
		<div class="col-md-3 col-sm-3 col-xs-12">
			<label for="evalFlavorBalance"><?php echo $label_balance; ?></label>
		</div>
		<div class="col-md-9 col-sm-9 col-xs-12">
			<input class="form-control" type="text" id="evalFlavorBalance" name="evalFlavorBalance" placeholder="" value="<?php if ($action == "edit") { if (isset($flavor_data['evalFlavorBalance'])) echo $flavor_data['evalFlavorBalance']; } ?>">
		</div>	
	</div>
</div>
<?php } // end if (($mead) || ($cider))  ?>
<!-- Flavor: Other -->
<div class="form-group">
	<div class="row">
		<div class="col-md-3 col-sm-3 col-xs-12">
			<label for="evalFlavorOther"><?php echo $label_other; ?></label>
		</div>
		<div class="col-md-9 col-sm-9 col-xs-12">
			<input class="form-control" type="text" id="evalFlavorOther" name="evalFlavorOther" placeholder="" value="<?php if ($action == "edit") { if (isset($flavor_data['evalFlavorOther'])) echo htmlentities($flavor_data['evalFlavorOther']); } ?>">
			<div class="help-block small"><?php echo $evaluation_info_034; ?></div>
		</div>	
	</div>
</div>
<?php if ($beer) { // Mouthfeel for Beer only ?>
<!-- Mouthfeel -->
<h3 class="section-heading"><?php echo $label_mouthfeel; ?></h3>
<!-- Mouthfeel Score -->
<div class="form-group">
	<div class="row">
		<div class="col-md-3 col-sm-12 col-xs-12">
			<label for="evalMouthfeelScore"><?php echo $label_score; ?> (<?php echo $mouthfeel_points; ?> <?php echo strtolower($label_possible_points); ?>)</label>
		</div>
		<div class="col-md-9 col-sm-12 col-xs-12">
			<select class="form-control selectpicker score-choose" name="evalMouthfeelScore" id="type" data-size="10" required>
		        <option value=""></option>
		        <?php for($i=$mouthfeel_points; $i>=1; $i--) {
		    			if (($action == "edit") && ($i == $row_eval['evalMouthfeelScore'])) $selected = "selected";
		    			else $selected = "";
		    		?>
		        <option value="<?php echo $i; ?>" <?php echo $selected; ?>><?php echo $i; ?></option>
		        <?php } ?>
		    </select>
		    <div class="help-block small with-errors"></div>
		</div>
	</div>
</div>
<?php foreach ($mouthfeel_ticks as $key => $value) { ?>
<div class="form-group">
	<div class="row">
		<div class="col-md-3 col-sm-12 col-xs-12">
			<label for="<?php echo $value; ?>"><?php echo $key; ?></label>
		</div>
		<div class="col-md-3 col-sm-12 col-xs-12 small">
			<div style="margin-left: 10px">
				<input class="form-control" type="text" id="<?php echo $value; ?>" name="<?php echo $value; ?>" data-provide="slider" data-slider-ticks="[0,1,2,3,4,5,6,7,8,9,10]" data-slider-ticks-labels='["<?php if (strpos($key, $label_body) !== false) echo $label_thin; else echo $label_none; ?>", "", "<?php if (strpos($key, $label_body) === false) echo $label_low; ?>", "", "", "<?php echo $label_med; ?>", "", "", "", "", "<?php if (strpos($key, $label_body) !== false) echo $label_full; else echo $label_high; ?>"]' data-slider-step="1" data-slider-value="<?php if ($action == "edit") echo $mouthfeel_data[$value]; ?>" data-slider-tooltip="hide" required>
			</div>
		</div>
		<div class="col-md-2 col-sm-12 col-xs-12 small">
			<div class="checkbox">
            <label>
                <input type="checkbox" name="<?php echo $value; ?>Inappr" id="<?php echo $value; ?>Inappr" value="1" <?php if ($action == "edit") { if ((isset($mouthfeel_data[$value.'Inappr'])) && ($mouthfeel_data[$value.'Inappr'] == "1")) echo "checked"; } ?>> <?php echo $label_inappropriate; ?>
            </label>
            </div>
		</div>
		<div class="col-md-4 col-sm-12 col-xs-12">
			<input class="form-control" type="text" id="<?php echo $value; ?>Comments" name="<?php echo $value; ?>Comments" placeholder="<?php echo $label_comments; ?>" value="<?php if ($action == "edit") { if (isset($mouthfeel_data[$value.'Comments'])) echo htmlentities($mouthfeel_data[$value.'Comments']); } ?>"> 
		</div>
	</div>
	<div class="help-block small with-errors"></div>
</div>
<?php } ?>
<div class="form-group">
	<div class="row">
		<div class="col-md-3 col-sm-12 col-xs-12">
			<label for="evalMouthfeelOther"><?php echo $label_other; ?></label>
		</div>
		<div class="col-md-9 col-sm-12 col-xs-12">
			<input class="form-control" type="text" id="evalMouthfeelOther" name="evalMouthfeelOther" placeholder="" value="<?php if ($action == "edit") { if (isset($mouthfeel_data['evalMouthfeelOther'])) echo htmlentities($mouthfeel_data['evalMouthfeelOther']); } ?>">
			<div class="help-block small"><?php echo $evaluation_info_034; ?></div>
		</div>	
	</div>
</div>
<?php } // end if ($beer) ?>
<!-- Overall Impression -->
<h3 class="section-heading"><?php echo $label_overall_impression; ?></h3>
<div class="form-group">
	<div class="row">
		<div class="col-md-3 col-sm-12 col-xs-12">
			<label for="evalOverallScore"><?php echo $label_score; ?> (<?php echo $overall_points; ?> <?php echo strtolower($label_possible_points); ?>)</label>
		</div>
		<div class="col-md-9 col-sm-12 col-xs-12">
			<select class="form-control selectpicker score-choose" name="evalOverallScore" id="type" data-size="10" required>
		        <option value=""></option>
		        <?php for($i=$overall_points; $i>=1; $i--) {
		    			if (($action == "edit") && ($i == $row_eval['evalOverallScore'])) $selected = "selected";
		    			else $selected = "";
		    		?>
		        <option value="<?php echo $i; ?>" <?php echo $selected; ?>><?php echo $i; ?></option>
		        <?php } ?>
		    </select>
		    <div class="help-block small with-errors"></div>
		</div>
	</div>
</div>
<!-- Style Accuracy -->
<div class="form-group">
    <div class="row">
      <div class="col-md-3 col-sm-12 col-xs-12">
        <label for="evalStyleAccuracy"><?php echo $label_style_accuracy; ?></label>
      </div>
      <div class="col-md-9 col-sm-12 col-xs-12 small">
      	<div style="margin-left: 10px">
	        <input class="form-control" type="text" name="evalStyleAccuracy" data-provide="slider" data-slider-ticks="[0,1,2,3,4,5,6,7,8,9,10]" data-slider-ticks-labels='["<?php echo $label_not_style; ?>", "", "", "", "", "", "", "", "", "", "<?php echo $label_classic_example; ?>"]' data-slider-min="0" data-slider-max="7" data-slider-step="1" data-slider-value="<?php if ($action == "edit") echo $row_eval['evalStyleAccuracy']; ?>" data-slider-tooltip="hide">
	    </div>
      </div>
    </div>
    <div class="help-block small with-errors"></div>
</div>
<!-- Technical Merit -->
<div class="form-group">
    <div class="row">
      <div class="col-md-3 col-sm-12 col-xs-12">
        <label for="evalTechMerit"><?php echo $label_tech_merit; ?></label>
      </div>
      <div class="col-md-9 col-sm-12 col-xs-12 small">
      	<div style="margin-left: 10px">
	        <input class="form-control" type="text" name="evalTechMerit" data-provide="slider" data-slider-ticks="[0,1,2,3,4,5,6,7,8,9,10]" data-slider-ticks-labels='["<?php echo $label_significant_flaws; ?>", "", "", "", "", "", "", "", "", "", "<?php echo $label_flawless; ?>"]' data-slider-min="0" data-slider-max="7" data-slider-step="1" data-slider-value="<?php if ($action == "edit") echo $row_eval['evalTechMerit']; ?>" data-slider-tooltip="hide">
	    </div>
      </div>
    </div>
    <div class="help-block small with-errors"></div>
</div>
<!-- Intangibles -->
<div class="form-group">
    <div class="row">
      <div class="col-md-3 col-sm-12 col-xs-12">
        <label for="evalIntangibles"><?php echo $label_intangibles; ?></label>
      </div>
      <div class="col-md-9 col-sm-12 col-xs-12 small">
      	<div style="margin-left: 10px">
	        <input class="form-control" type="text" name="evalIntangibles" data-provide="slider" data-slider-ticks="[0,1,2,3,4,5,6,7,8,9,10]" data-slider-ticks-labels='["<?php echo $label_lifeless; ?>", "", "", "", "", "", "", "", "", "", "<?php echo $label_wonderful; ?>"]' data-slider-min="0" data-slider-max="7" data-slider-step="1" data-slider-value="<?php if ($action == "edit") echo $row_eval['evalIntangibles']; ?>" data-slider-tooltip="hide">
	    </div>
      </div>
    </div>
    <div class="help-block small with-errors"></div>
</div>
<!-- Overall Feedback -->
<div class="form-group">
    <label for="evalOverallComments"><?php echo $label_feedback; ?></label>
    <textarea class="form-control" id="evalOverallComments" name="evalOverallComments" rows="6" placeholder="" data-error="<?php echo $evaluation_info_061; ?>" required><?php if ($action == "edit") echo htmlentities($row_eval['evalOverallComments']); ?></textarea>
    <div class="help-block small"><?php echo $evaluation_info_035; ?></div>
    <div class="help-block small with-errors"></div>
    <div class="help-block small" id="evalOverallComments-words"></div>
</div>
<!-- Flaws -->
<h3 class="section-heading"><?php echo $label_flaws; ?></h3>
<?php foreach ($flaws as $flaw) {
	$flaw_none = FALSE;
	$flaw_low = FALSE;
	$flaw_med = FALSE;
	$flaw_high = FALSE;
	if ($action == "edit") { 
		if (strpos($row_eval['evalFlaws'],$flaw." Low") !== false) $flaw_low = TRUE;
		elseif (strpos($row_eval['evalFlaws'],$flaw." Medium") !== false) $flaw_med = TRUE;
		elseif (strpos($row_eval['evalFlaws'],$flaw." High") !== false) $flaw_high = TRUE;
		else $flaw_none = TRUE;
	}
?>
<div class="form-group">
  <div class="row">
    <div class="col-md-3 col-sm-12 col-xs-12">
        <label for="evalFlaws"><?php echo $flaw; ?></label>
    </div>
    <div class="col-md-9 col-sm-12 col-xs-12 small">
    	<label class="radio-inline">
			<input type="radio" name="evalFlaws<?php echo $flaw; ?>" value="" <?php if (($action == "add") || ($flaw_none)) echo "checked"; ?>><?php echo $label_na; ?>
		</label>
		<label class="radio-inline">
			<input type="radio" name="evalFlaws<?php echo $flaw; ?>" value="<?php echo $flaw; ?> Low" <?php if ($flaw_low) echo "checked"; ?>><?php echo $label_low; ?>
		</label>
		<label class="checkbox-inline">
			<input type="radio" name="evalFlaws<?php echo $flaw; ?>" value="<?php echo $flaw; ?> Medium" <?php if ($flaw_med) echo "checked"; ?>> <?php echo $label_med; ?>
		</label>
		<label class="radio-inline">
			<input type="radio" name="evalFlaws<?php echo $flaw; ?>" value="<?php echo $flaw; ?> High" <?php if ($flaw_high) echo "checked"; ?>> <?php echo $label_high; ?>
		</label>
    </div>
  </div>
</div>
<?php } ?>
<?php } // end else ?>
