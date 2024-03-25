<?php

// Redirect if directly accessed without authenticated session
if ((!isset($_SESSION['loginUsername'])) || ((isset($_SESSION['loginUsername'])) && (!isset($base_url)))) {
    $redirect = "../../403.php";
    $redirect_go_to = sprintf("Location: %s", $redirect);
    header($redirect_go_to);
    exit();
}

if ($row_style['brewStyleType'] == 2) $cider = TRUE;
elseif ($row_style['brewStyleType'] == 3) $mead = TRUE;
else $beer = TRUE;
?>
<!-- Full Evaluation Form -->
<input type="hidden" name="evalFormType" value="1">
<!-- Aroma -->
<h3 class="section-heading"><?php if ($cider || $mead) echo $label_bouquet."/"; echo $label_aroma; ?></h3>
<!-- Aroma Score -->
<div class="form-group">
    <label for="evalAromaScore"><?php echo $label_score; ?> (<?php echo $aroma_points; ?> <?php echo strtolower($label_possible_points); ?>)</label>
        <select class="form-control selectpicker score-choose" name="evalAromaScore" id="type" data-size="10" required>
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
<!-- Aroma Comments -->
<div class="form-group">
    <label for="evalAromaComments"><?php echo $label_comments; ?></label>
    <p><small><?php if ($mead) echo $evaluation_info_081; elseif ($cider) echo $evaluation_info_086; else echo $evaluation_info_076; ?></small></p>
    <textarea class="form-control" id="evalAromaComments" name="evalAromaComments" rows="6" placeholder="" data-error="<?php echo $evaluation_info_061; ?>" required><?php if ($action == "edit") echo htmlentities($row_eval['evalAromaComments']); ?></textarea>
    <div class="help-block small with-errors"></div>
    <div class="help-block small" id="evalAromaComments-words"></div>
</div>
<!-- Appearance -->
<h3 class="section-heading"><?php echo $label_appearance; ?></h3>
<!-- Appearance Score -->
<div class="form-group">
    <label for="evalAppearanceScore"><?php echo $label_score; ?> (<?php echo $appearance_points; ?> <?php echo strtolower($label_possible_points); ?>)</label>
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
<!-- Appearance Comments -->
<div class="form-group">
    <label for="evalAppearanceComments"><?php echo $label_comments; ?></label>
    <p><small><?php if ($mead) echo $evaluation_info_082; elseif ($cider) echo $evaluation_info_085; else echo $evaluation_info_077; ?></small></p>
    <textarea class="form-control" id="evalAppearanceComments" name="evalAppearanceComments" rows="6" placeholder="" data-error="<?php echo $evaluation_info_061; ?>" required><?php if ($action == "edit") echo htmlentities($row_eval['evalAppearanceComments']); ?></textarea>
    <div class="help-block small with-errors"></div>
    <div class="help-block small" id="evalAppearanceComments-words"></div>
</div>
<!-- Flavor -->
<h3 class="section-heading"><?php echo $label_flavor; ?></h3>
<!-- Flavor Score -->
<div class="form-group">
    <label for="evalFlavorScore"><?php echo $label_score; ?> (<?php echo $flavor_points; ?> <?php echo strtolower($label_possible_points); ?>)</label>
        <select class="form-control selectpicker score-choose" name="evalFlavorScore" id="type" data-size="10" required>
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
<!-- Flavor Comments -->
<div class="form-group">
    <label for="evalFlavorComments"><?php echo $label_comments; ?></label>
    <p><small><?php if ($mead) echo $evaluation_info_083; elseif ($cider) echo $evaluation_info_087; else echo $evaluation_info_078; ?></small></p>
    <textarea class="form-control" id="evalFlavorComments" name="evalFlavorComments" rows="6" placeholder="" data-error="<?php echo $evaluation_info_061; ?>" required><?php if ($action == "edit") echo htmlentities($row_eval['evalFlavorComments']); ?></textarea>
    <div class="help-block small with-errors"></div>
    <div class="help-block small" id="evalFlavorComments-words"></div>
</div>
<?php if ($beer) { ?>
<!-- Mouthfeel -->
<h3 class="section-heading">Mouthfeel</h3>
<!-- Mouthfeel Score -->
<div class="form-group">
    <label for="evalMouthfeelScore"><?php echo $label_score; ?> (<?php echo $mouthfeel_points; ?> <?php echo strtolower($label_possible_points); ?>)</label>
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
<!-- Mouthfeel Comments -->
<div class="form-group">
    <label for="evalMouthfeelComments"><?php echo $label_comments; ?></label>
    <p><small><?php echo $evaluation_info_079; ?></small></p>
    <textarea class="form-control" id="evalMouthfeelComments" name="evalMouthfeelComments" rows="6" placeholder="" data-error="<?php echo $evaluation_info_061; ?>" required><?php if ($action == "edit") echo htmlentities($row_eval['evalMouthfeelComments']); ?></textarea>
    <div class="help-block small with-errors"></div>
    <div class="help-block small" id="evalMouthfeelComments-words"></div>
</div>
<?php } ?>
<!-- Overall Impression -->
<h3 class="section-heading"><?php echo $label_overall_impression; ?></h3>
<!-- Overall Impression Score -->
<div class="form-group">
    <label for="evalOverallScore"><?php echo $label_score; ?> (<?php echo $overall_points; ?> <?php echo strtolower($label_possible_points); ?>)</label>
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

<!-- Descriptors -->
<h3 class="section-heading"><?php echo $label_descriptors; ?></h3>
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