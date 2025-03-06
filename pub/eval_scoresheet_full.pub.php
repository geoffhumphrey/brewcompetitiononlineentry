<?php
if ($row_style['brewStyleType'] == 2) $cider = TRUE;
elseif ($row_style['brewStyleType'] == 3) $mead = TRUE;
else $beer = TRUE;
?>
<!-- Full Evaluation Form -->
<input type="hidden" name="evalFormType" value="1">
<!-- Aroma -->
<h3 class="section-heading mt-4 pt-4"><?php if ($cider || $mead) echo $label_bouquet."/"; echo $label_aroma; ?> <a role="button" id="show-hide-aroma-btn" data-bs-toggle="collapse" href="#scoresheet-aroma" aria-expanded="true" aria-controls="scoresheet-aroma"><i id="toggle-icon-aroma" class="fa fa-chevron-circle-up"></i></a> <i id="score-icon-aroma" class="fa fa-check-circle text-success"></i></h3>
<!-- Aroma Score -->
<div class="mb-3">
    <label class="form-label" for="evalAromaScore"><strong><?php echo $label_score; ?></strong> (<?php echo $aroma_points; ?> <?php echo strtolower($label_possible_points); ?>)</label>
        <select class="form-control bootstrap-select score-choose" name="evalAromaScore" id="evalAromaScore" data-size="10" required>
          <option value=""></option>
        <?php for($i=$aroma_points; $i>=1; $i--) { 
    			if (($action == "edit") && ($i == $row_eval['evalAromaScore'])) $selected = "selected";
    			else $selected = "";
    	?>
        <option value="<?php echo $i; ?>" <?php echo $selected; ?>><?php echo $i; ?></option>
        <?php } ?>
    </select>
    <div class="help-block small invalid-feedback text-danger"></div>
</div>
<section class="collapse show" id="scoresheet-aroma">
    <!-- Aroma Comments -->
    <div class="mb-3">
        <label class="form-label" for="evalAromaComments"><strong><?php echo $label_comments; ?></strong></label>
        <p><small><?php if ($mead) echo $evaluation_info_081; elseif ($cider) echo $evaluation_info_086; else echo $evaluation_info_076; ?></small></p>
        <textarea class="form-control" id="evalAromaComments" name="evalAromaComments" rows="6" placeholder="" data-error="<?php echo $evaluation_info_061; ?>" required><?php if ($action == "edit") echo htmlentities($row_eval['evalAromaComments']); ?></textarea>
        <div class="help-block small invalid-feedback text-danger"><?php echo $evaluation_info_061; ?></div>
        <div class="help-block small" id="evalAromaComments-words"><?php if (!empty($row_judging_prefs['jPrefsMinWords'])) echo sprintf ("<strong>%s %s</strong>.", $evaluation_info_091, $row_judging_prefs['jPrefsMinWords']); ?></div>
    </div>
</section>
<!-- Appearance -->
<h3 class="section-heading mt-4 pt-4"><?php echo $label_appearance; ?> <a role="button" id="show-hide-appearance-btn" data-bs-toggle="collapse" href="#scoresheet-appearance" aria-expanded="true" aria-controls="scoresheet-appearance"><i id="toggle-icon-appearance" class="fa fa-chevron-circle-up"></i></a> <i id="score-icon-appearance" class="fa fa-check-circle text-success"></i></h3>
<!-- Appearance Score -->
<div class="mb-3">
    <label class="form-label" for="evalAppearanceScore"><strong><?php echo $label_score; ?></strong> (<?php echo $appearance_points; ?> <?php echo strtolower($label_possible_points); ?>)</label>
        <select class="form-control bootstrap-select score-choose" name="evalAppearanceScore" id="evalAppearanceScore" data-size="10" required>
        <option value=""></option>
        <?php for($i=$appearance_points; $i>=1; $i--) {
          if (($action == "edit") && ($i == $row_eval['evalAppearanceScore'])) $selected = "selected";
    			else $selected = "";
    	?>
        <option value="<?php echo $i; ?>" <?php echo $selected; ?>><?php echo $i; ?></option>
        <?php } ?>
    </select>
    <div class="help-block small invalid-feedback text-danger"></div>
</div>
<section class="collapse show" id="scoresheet-appearance">
    <!-- Appearance Comments -->
    <div class="mb-3">
        <label class="form-label" for="evalAppearanceComments"><strong><?php echo $label_comments; ?></strong></label>
        <p><small><?php if ($mead) echo $evaluation_info_082; elseif ($cider) echo $evaluation_info_085; else echo $evaluation_info_077; ?></small></p>
        <textarea class="form-control" id="evalAppearanceComments" name="evalAppearanceComments" rows="6" placeholder="" data-error="<?php echo $evaluation_info_061; ?>" required><?php if ($action == "edit") echo htmlentities($row_eval['evalAppearanceComments']); ?></textarea>
        <div class="help-block small invalid-feedback text-danger"><?php echo $evaluation_info_061; ?></div>
        <div class="help-block small" id="evalAppearanceComments-words"><?php if (!empty($row_judging_prefs['jPrefsMinWords'])) echo sprintf ("<strong>%s %s</strong>.", $evaluation_info_091, $row_judging_prefs['jPrefsMinWords']); ?></div>
    </div>
</section>
<!-- Flavor -->
<h3 class="section-heading mt-4 pt-4"><?php echo $label_flavor; ?> <a role="button" id="show-hide-flavor-btn" data-bs-toggle="collapse" href="#scoresheet-flavor" aria-expanded="true" aria-controls="scoresheet-flavor"><i id="toggle-icon-flavor" class="fa fa-chevron-circle-up"></i></a> <i id="score-icon-flavor" class="fa fa-check-circle text-success"></i></h3>
<!-- Flavor Score -->
<div class="mb-3">
    <label class="form-label" for="evalFlavorScore"><strong><?php echo $label_score; ?></strong> (<?php echo $flavor_points; ?> <?php echo strtolower($label_possible_points); ?>)</label>
        <select class="form-control bootstrap-select score-choose" name="evalFlavorScore" id="evalFlavorScore" data-size="10" required>
        <option value=""></option>
        <?php for($i=$flavor_points; $i>=1; $i--) { 
          if (($action == "edit") && ($i == $row_eval['evalFlavorScore'])) $selected = "selected";
    			else $selected = "";
    	?>
        <option value="<?php echo $i; ?>" <?php echo $selected; ?>><?php echo $i; ?></option>
        <?php } ?>
    </select>
    <div class="help-block small invalid-feedback text-danger"></div>
</div>
<section class="collapse show" id="scoresheet-flavor">
    <!-- Flavor Comments -->
    <div class="mb-3">
        <label class="form-label" for="evalFlavorComments"><strong><?php echo $label_comments; ?></strong></label>
        <p><small><?php if ($mead) echo $evaluation_info_083; elseif ($cider) echo $evaluation_info_087; else echo $evaluation_info_078; ?></small></p>
        <textarea class="form-control" id="evalFlavorComments" name="evalFlavorComments" rows="6" placeholder="" data-error="<?php echo $evaluation_info_061; ?>" required><?php if ($action == "edit") echo htmlentities($row_eval['evalFlavorComments']); ?></textarea>
        <div class="help-block small invalid-feedback text-danger"><?php echo $evaluation_info_061; ?></div>
        <div class="help-block small" id="evalFlavorComments-words"><?php if (!empty($row_judging_prefs['jPrefsMinWords'])) echo sprintf ("<strong>%s %s</strong>.", $evaluation_info_091, $row_judging_prefs['jPrefsMinWords']); ?></div>
    </div>
</section>
<?php if ($beer) { ?>
<!-- Mouthfeel -->
<h3 class="section-heading mt-4 pt-4">Mouthfeel <a role="button" id="show-hide-mouthfeel-btn" data-bs-toggle="collapse" href="#scoresheet-mouthfeel" aria-expanded="true" aria-controls="scoresheet-mouthfeel"><i id="toggle-icon-mouthfeel" class="fa fa-chevron-circle-up"></i></a> <i id="score-icon-mouthfeel" class="fa fa-check-circle text-success"></i></h3>
<!-- Mouthfeel Score -->
<div class="mb-3">
    <label class="form-label" for="evalMouthfeelScore"><strong><?php echo $label_score; ?></strong> (<?php echo $mouthfeel_points; ?> <?php echo strtolower($label_possible_points); ?>)</label>
        <select class="form-control bootstrap-select score-choose" name="evalMouthfeelScore" id="evalMouthfeelScore" data-size="10" required>
        <option value=""></option>
        <?php for($i=$mouthfeel_points; $i>=1; $i--) { 
    			if (($action == "edit") && ($i == $row_eval['evalMouthfeelScore'])) $selected = "selected";
    			else $selected = "";
        ?>
        <option value="<?php echo $i; ?>" <?php echo $selected; ?>><?php echo $i; ?></option>
        <?php } ?>
    </select>
    <div class="help-block small invalid-feedback text-danger"></div>
</div>
<section class="collapse show" id="scoresheet-mouthfeel">
    <!-- Mouthfeel Comments -->
    <div class="mb-3">
        <label class="form-label" for="evalMouthfeelComments"><strong><?php echo $label_comments; ?></strong></label>
        <p><small><?php echo $evaluation_info_079; ?></small></p>
        <textarea class="form-control" id="evalMouthfeelComments" name="evalMouthfeelComments" rows="6" placeholder="" data-error="<?php echo $evaluation_info_061; ?>" required><?php if ($action == "edit") echo htmlentities($row_eval['evalMouthfeelComments']); ?></textarea>
        <div class="help-block small invalid-feedback text-danger"><?php echo $evaluation_info_061; ?></div>
        <div class="help-block small" id="evalMouthfeelComments-words"><?php if (!empty($row_judging_prefs['jPrefsMinWords'])) echo sprintf ("<strong>%s %s</strong>.", $evaluation_info_091, $row_judging_prefs['jPrefsMinWords']); ?></div>
    </div>
</section>
<?php } ?>
<!-- Overall Impression -->
<h3 class="section-heading mt-4 pt-4"><?php echo $label_overall_impression; ?> <a role="button" id="show-hide-overall-btn" data-bs-toggle="collapse" href="#scoresheet-overall" aria-expanded="true" aria-controls="scoresheet-overall"><i id="toggle-icon-overall" class="fa fa-chevron-circle-up"></i></a> <i id="score-icon-overall" class="fa fa-check-circle text-success"></i></h3>
<!-- Overall Impression Score -->
<div class="mb-3">
    <label class="form-label" for="evalOverallScore"><strong><?php echo $label_score; ?></strong> (<?php echo $overall_points; ?> <?php echo strtolower($label_possible_points); ?>)</label>
        <select class="form-control bootstrap-select score-choose" name="evalOverallScore" id="evalOverallScore" data-size="10" required>
        <option value=""></option>
        <?php for($i=$overall_points; $i>=1; $i--) { 
    			if (($action == "edit") && ($i == $row_eval['evalOverallScore'])) $selected = "selected";
    			else $selected = ""; 
		?>
        <option value="<?php echo $i; ?>" <?php echo $selected; ?>><?php echo $i; ?></option>
        <?php } ?>
    </select>
    <div class="help-block small invalid-feedback text-danger"></div>
</div>
<section class="collapse show" id="scoresheet-overall">
<!-- Overall Impression Comments -->

    <div class="mb-3">
        <label class="form-label" for="evalOverallComments"><strong><?php echo $label_comments; ?></strong></label>
        <p><small><?php if ($mead) echo $evaluation_info_084; elseif ($cider) echo $evaluation_info_088; else echo $evaluation_info_080; ?></small></p>
        <textarea class="form-control" id="evalOverallComments" name="evalOverallComments" rows="6" placeholder="" data-error="<?php echo $evaluation_info_061; ?>" required><?php if ($action == "edit") echo htmlentities($row_eval['evalOverallComments']); ?></textarea>
        <div class="help-block small invalid-feedback text-danger"><?php echo $evaluation_info_061; ?></div>
        <div class="help-block small" id="evalOverallComments-words"><?php if (!empty($row_judging_prefs['jPrefsMinWords'])) echo sprintf ("<strong>%s %s</strong>.", $evaluation_info_091, $row_judging_prefs['jPrefsMinWords']); ?></div>
    </div>

    <!-- Style Accuracy -->
    <div class="mb-3 row">
        <div class="col-sm-2 col-xs-12">
            <label for="evalStyleAccuracy"><strong><?php echo $label_style_accuracy; ?></strong></label>
        </div>
        <div class="col-sm-10 col-xs-12 small">
            <?php for($i=5; $i>=1; $i--) { ?>
            <div class="form-check">
                <input class="form-check-input" type="radio" name="evalStyleAccuracy" id="evalStyleAccuracy<?php echo $i; ?>" value="<?php echo $i; ?>" data-error="<?php echo $evaluation_info_067; ?>" required <?php if (($action == "edit") && ($row_eval['evalStyleAccuracy'] == $i)) echo "checked"; ?>>
                <label class="form-check-label"><?php if ($i == 1) echo $label_not_style; if ($i == 5) echo $label_classic_example; ?></label>
            </div>
            <?php } ?>
            <div class="help-block small invalid-feedback"><?php echo $evaluation_info_067; ?></div>
        </div>
    </div>

    <!-- Technical Merit -->
    <div class="mb-3 row">
        <div class="col-sm-2 col-xs-12">
            <label for="evalTechMerit"><strong><?php echo $label_tech_merit; ?></strong></label>
        </div>
        <div class="col-sm-10 col-xs-12 small">
            <?php for($i=5; $i>=1; $i--) { ?>
            <div class="form-check">
                <input class="form-check-input" type="radio" name="evalTechMerit" id="techMerit<?php echo $i; ?>" value="<?php echo $i; ?>" data-error="<?php echo $evaluation_info_067; ?>" required  <?php if (($action == "edit") && ($row_eval['evalTechMerit'] == $i)) echo "checked"; ?>>
                <label class="form-check-label"><?php if ($i == 1) echo $label_significant_flaws; if ($i == 5) echo $label_flawless; ?></label>
            </div>
            <?php } ?>
            <div class="help-block small invalid-feedback"><?php echo $evaluation_info_067; ?></div>
        </div>
    </div>

    <!-- Intangibles -->
    <div class="mb-3 row">
        <div class="col-sm-2 col-xs-12">
            <label for="evalIntangibles"><strong><?php echo $label_intangibles; ?></strong></label>
        </div>
        <div class="col-sm-10 col-xs-12 small">
            <?php for($i=5; $i>=1; $i--) { ?>
            <div class="form-check">
                <input class="form-check-input" type="radio" name="evalIntangibles" id="evalIntangibles<?php echo $i; ?>" value="<?php echo $i; ?>" data-error="<?php echo $evaluation_info_067; ?>" required  <?php if (($action == "edit") && ($row_eval['evalIntangibles'] == $i)) echo "checked"; ?>>
                <label class="form-check-label"><?php if ($i == 1) echo $label_lifeless; if ($i == 5) echo $label_wonderful; ?></label>
            </div>
            <?php } ?>
            <div class="help-block small invalid-feedback"><?php echo $evaluation_info_067; ?></div>
        </div>
    </div>

</section>

<!-- Descriptors -->
<h3 class="section-heading mt-4 pt-4"><?php echo $label_descriptors; ?> <a role="button" id="show-hide-flaws-btn" data-bs-toggle="collapse" href="#scoresheet-flaws" aria-expanded="true" aria-controls="scoresheet-flaws"><i id="toggle-icon-flaws" class="fa fa-chevron-circle-up"></i></a></h3>
<section class="collapse show" id="scoresheet-flaws">
    <p>Mark all that apply.</p>
    <div class="mb-3">
        <?php foreach ($descriptors as $key => $value) { ?>
        <div class="form-check">
            <input class="form-check-input" type="checkbox" name="evalDescriptors[]" value="<?php echo $key; ?>" <?php if ($action == "edit") { if (strpos($row_eval['evalDescriptors'],$key) !== false) echo "checked"; } ?>>
            <label class="form-check-label"><strong><?php echo $key; ?></strong> – <?php echo $value; ?></label>
        </div>
        <?php } ?>
    </div>
</section>