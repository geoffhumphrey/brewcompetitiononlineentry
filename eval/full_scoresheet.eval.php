<?php
if ($row_style['brewStyleType'] == 2) $cider = TRUE;
elseif ($row_style['brewStyleType'] == 3) $mead = TRUE;
else $beer = TRUE;
?>
<!-- Full Evaluation Form -->
<input type="hidden" name="evalFormType" value="1">
<!-- Aroma -->
<h3 class="section-heading"><?php if ($cider || $mead) echo $label_bouquet."/"; echo $label_aroma; ?> <a role="button" id="show-hide-aroma-btn" data-toggle="collapse" href="#scoresheet-aroma" aria-expanded="true" aria-controls="scoresheet-aroma"><i id="toggle-icon-aroma" class="fa fa-chevron-circle-down"></i></a> <i id="score-icon-aroma" class="fa fa-check-circle text-success"></i></h3>
<!-- Aroma Score -->
<div class="form-group">
    <label for="evalAromaScore"><?php echo $label_score; ?> (<?php echo $aroma_points; ?> <?php echo strtolower($label_possible_points); ?>)</label>
        <select class="form-control selectpicker score-choose" name="evalAromaScore" id="evalAromaScore" data-size="10" required>
          <option value=""></option>
        <?php for($i=$aroma_points; $i>=1; $i--) { 
    			if (($action == "edit") && ($i == $row_eval['evalAromaScore'])) $selected = "selected";
    			else $selected = "";
    	?>
        <option value="<?php echo $i; ?>" <?php echo $selected; ?>><?php echo $i; ?></option>
        <?php } ?>
    </select>
    <div class="help-block small with-errors"></div>
</div>
<section class="collapse in" id="scoresheet-aroma">
    <!-- Aroma Comments -->
    <div class="form-group">
        <label for="evalAromaComments"><?php echo $label_comments; ?></label>
        <p><small><?php if ($mead) echo $evaluation_info_081; elseif ($cider) echo $evaluation_info_086; else echo $evaluation_info_076; ?></small></p>
        <textarea class="form-control" id="evalAromaComments" name="evalAromaComments" rows="6" placeholder="" data-error="<?php echo $evaluation_info_061; ?>" required><?php if ($action == "edit") echo htmlentities($row_eval['evalAromaComments']); ?></textarea>
        <div class="help-block small with-errors"></div>
        <div class="help-block small" id="evalAromaComments-words"></div>
    </div>
</section>
<!-- Appearance -->
<h3 class="section-heading"><?php echo $label_appearance; ?> <a role="button" id="show-hide-appearance-btn" data-toggle="collapse" href="#scoresheet-appearance" aria-expanded="true" aria-controls="scoresheet-appearance"><i id="toggle-icon-appearance" class="fa fa-chevron-circle-down"></i></a> <i id="score-icon-appearance" class="fa fa-check-circle text-success"></i></h3>
<!-- Appearance Score -->
<div class="form-group">
    <label for="evalAppearanceScore"><?php echo $label_score; ?> (<?php echo $appearance_points; ?> <?php echo strtolower($label_possible_points); ?>)</label>
        <select class="form-control selectpicker score-choose" name="evalAppearanceScore" id="evalAppearanceScore" data-size="10" required>
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
<section class="collapse in" id="scoresheet-appearance">
    <!-- Appearance Comments -->
    <div class="form-group">
        <label for="evalAppearanceComments"><?php echo $label_comments; ?></label>
        <p><small><?php if ($mead) echo $evaluation_info_082; elseif ($cider) echo $evaluation_info_085; else echo $evaluation_info_077; ?></small></p>
        <textarea class="form-control" id="evalAppearanceComments" name="evalAppearanceComments" rows="6" placeholder="" data-error="<?php echo $evaluation_info_061; ?>" required><?php if ($action == "edit") echo htmlentities($row_eval['evalAppearanceComments']); ?></textarea>
        <div class="help-block small with-errors"></div>
        <div class="help-block small" id="evalAppearanceComments-words"></div>
    </div>
</section>
<!-- Flavor -->
<h3 class="section-heading"><?php echo $label_flavor; ?> <a role="button" id="show-hide-flavor-btn" data-toggle="collapse" href="#scoresheet-flavor" aria-expanded="true" aria-controls="scoresheet-flavor"><i id="toggle-icon-flavor" class="fa fa-chevron-circle-down"></i></a> <i id="score-icon-flavor" class="fa fa-check-circle text-success"></i></h3>
<!-- Flavor Score -->
<div class="form-group">
    <label for="evalFlavorScore"><?php echo $label_score; ?> (<?php echo $flavor_points; ?> <?php echo strtolower($label_possible_points); ?>)</label>
        <select class="form-control selectpicker score-choose" name="evalFlavorScore" id="evalFlavorScore" data-size="10" required>
        <option value=""></option>
        <?php for($i=$flavor_points; $i>=1; $i--) { 
          if (($action == "edit") && ($i == $row_eval['evalFlavorScore'])) $selected = "selected";
    			else $selected = "";
    	?>
        <option value="<?php echo $i; ?>" <?php echo $selected; ?>><?php echo $i; ?></option>
        <?php } ?>
    </select>
    <div class="help-block small with-errors"></div>
</div>
<section class="collapse in" id="scoresheet-flavor">
    <!-- Flavor Comments -->
    <div class="form-group">
        <label for="evalFlavorComments"><?php echo $label_comments; ?></label>
        <p><small><?php if ($mead) echo $evaluation_info_083; elseif ($cider) echo $evaluation_info_087; else echo $evaluation_info_078; ?></small></p>
        <textarea class="form-control" id="evalFlavorComments" name="evalFlavorComments" rows="6" placeholder="" data-error="<?php echo $evaluation_info_061; ?>" required><?php if ($action == "edit") echo htmlentities($row_eval['evalFlavorComments']); ?></textarea>
        <div class="help-block small with-errors"></div>
        <div class="help-block small" id="evalFlavorComments-words"></div>
    </div>
</section>
<?php if ($beer) { ?>
<!-- Mouthfeel -->
<h3 class="section-heading">Mouthfeel <a role="button" id="show-hide-mouthfeel-btn" data-toggle="collapse" href="#scoresheet-mouthfeel" aria-expanded="true" aria-controls="scoresheet-mouthfeel"><i id="toggle-icon-mouthfeel" class="fa fa-chevron-circle-down"></i></a> <i id="score-icon-mouthfeel" class="fa fa-check-circle text-success"></i></h3>
<!-- Mouthfeel Score -->
<div class="form-group">
    <label for="evalMouthfeelScore"><?php echo $label_score; ?> (<?php echo $mouthfeel_points; ?> <?php echo strtolower($label_possible_points); ?>)</label>
        <select class="form-control selectpicker score-choose" name="evalMouthfeelScore" id="evalMouthfeelScore" data-size="10" required>
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
<section class="collapse in" id="scoresheet-mouthfeel">
    <!-- Mouthfeel Comments -->
    <div class="form-group">
        <label for="evalMouthfeelComments"><?php echo $label_comments; ?></label>
        <p><small><?php echo $evaluation_info_079; ?></small></p>
        <textarea class="form-control" id="evalMouthfeelComments" name="evalMouthfeelComments" rows="6" placeholder="" data-error="<?php echo $evaluation_info_061; ?>" required><?php if ($action == "edit") echo htmlentities($row_eval['evalMouthfeelComments']); ?></textarea>
        <div class="help-block small with-errors"></div>
        <div class="help-block small" id="evalMouthfeelComments-words"></div>
    </div>
</section>
<?php } ?>
<!-- Overall Impression -->
<h3 class="section-heading"><?php echo $label_overall_impression; ?> <a role="button" id="show-hide-overall-btn" data-toggle="collapse" href="#scoresheet-overall" aria-expanded="true" aria-controls="scoresheet-overall"><i id="toggle-icon-overall" class="fa fa-chevron-circle-down"></i></a> <i id="score-icon-overall" class="fa fa-check-circle text-success"></i></h3>
<!-- Overall Impression Score -->
<div class="form-group">
    <label for="evalOverallScore"><?php echo $label_score; ?> (<?php echo $overall_points; ?> <?php echo strtolower($label_possible_points); ?>)</label>
        <select class="form-control selectpicker score-choose" name="evalOverallScore" id="evalOverallScore" data-size="10" required>
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
<section class="collapse in" id="scoresheet-overall">
<!-- Overall Impression Comments -->
    <div class="form-group">
        <label for="evalOverallComments"><?php echo $label_comments; ?></label>
        <p><small><?php if ($mead) echo $evaluation_info_084; elseif ($cider) echo $evaluation_info_088; else echo $evaluation_info_080; ?></small></p>
        <textarea class="form-control" id="evalOverallComments" name="evalOverallComments" rows="6" placeholder="" data-error="<?php echo $evaluation_info_061; ?>" required><?php if ($action == "edit") echo htmlentities($row_eval['evalOverallComments']); ?></textarea>
        <div class="help-block small with-errors"></div>
        <div class="help-block small" id="evalOverallComments-words"></div>
    </div>

    <!-- Style Accuracy -->
    <div class="form-group">
        <label for="evalStyleAccuracy"><?php echo $label_style_accuracy; ?></label>
        <?php for($i=5; $i>=1; $i--) { ?>
        <div class="radio">
            <label class="radio-inline">
                <input type="radio" name="evalStyleAccuracy" id="evalStyleAccuracy<?php echo $i; ?>" value="<?php echo $i; ?>" data-error="<?php echo $evaluation_info_067; ?>" required <?php if (($action == "edit") && ($row_eval['evalStyleAccuracy'] == $i)) echo "checked"; ?>> <?php if ($i == 1) echo $label_not_style; if ($i == 5) echo $label_classic_example; ?>
            </label>
        </div>
        <?php } ?>
        <div class="help-block with-errors"></div>
    </div>

    <!-- Technical Merit -->
    <div class="form-group">
        <label for="evalTechMerit"><?php echo $label_tech_merit; ?></label>
        <?php for($i=5; $i>=1; $i--) { ?>
        <div class="radio">
            <label class="radio-inline">
                <input type="radio" name="evalTechMerit" id="techMerit<?php echo $i; ?>" value="<?php echo $i; ?>" data-error="<?php echo $evaluation_info_067; ?>" required  <?php if (($action == "edit") && ($row_eval['evalTechMerit'] == $i)) echo "checked"; ?>> <?php if ($i == 1) echo $label_significant_flaws; if ($i == 5) echo $label_flawless; ?>
            </label>
        </div>
        <?php } ?>
        <div class="help-block with-errors"></div>
    </div>

    <!-- Intangibles -->
    <div class="form-group">
        <label for="evalIntangibles"><?php echo $label_intangibles; ?></label>
        <?php for($i=5; $i>=1; $i--) { ?>
        <div class="radio">
            <label class="radio-inline">
                <input type="radio" name="evalIntangibles" id="evalIntangibles<?php echo $i; ?>" value="<?php echo $i; ?>" data-error="<?php echo $evaluation_info_067; ?>" required  <?php if (($action == "edit") && ($row_eval['evalIntangibles'] == $i)) echo "checked"; ?>> <?php if ($i == 1) echo $label_lifeless; if ($i == 5) echo $label_wonderful; ?>
            </label>
        </div>
        <?php } ?>
        <div class="help-block with-errors"></div>
    </div>
</section>

<!-- Descriptors -->
<h3 class="section-heading"><?php echo $label_descriptors; ?> <a role="button" id="show-hide-flaws-btn" data-toggle="collapse" href="#scoresheet-flaws" aria-expanded="true" aria-controls="scoresheet-flaws"><i id="toggle-icon-flaws" class="fa fa-chevron-circle-down"></i></a></h3>
<section class="collapse in" id="scoresheet-flaws">
    <p>Mark all that apply.</p>
    <div class="form-group">
        <?php foreach ($descriptors as $key => $value) { ?>
        <div class="checkbox">
          <label>
            <input type="checkbox" name="evalDescriptors[]" value="<?php echo $key; ?>" <?php if ($action == "edit") { if (strpos($row_eval['evalDescriptors'],$key) !== false) echo "checked"; } ?>><strong><?php echo $key; ?></strong> – <?php echo $value; ?>
          </label>
        </div>
        <?php } ?>
    </div>
</section>