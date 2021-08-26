<?php
if ($row_style['brewStyleType'] == 2) $cider = TRUE;
elseif ($row_style['brewStyleType'] == 3) $mead = TRUE;
else $beer = TRUE;
?>
<!-- Full Evaluation Form -->
<input type="hidden" name="evalFormType" value="1">
<!-- Aroma -->
<h3 class="section-heading">Aroma</h3>
<!-- Aroma Score -->
<div class="form-group">
    <label for="evalAromaScore"><?php if ($cider || $mead) echo "Bouquet/"; ?>Aroma Score (<?php echo $aroma_points; ?> points possible)</label>
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
    <label for="evalAromaComments">Aroma Comments</label>
    <textarea class="form-control" name="evalAromaComments" rows="6" placeholder="Brief comments relating to aroma; 450 characters maximum." data-error="Please provide some brief aroma comments" maxlength="450" required><?php if ($action == "edit") echo $row_eval['evalAromaComments']; ?></textarea>
</div>

<!-- Appearance -->
<h3 class="section-heading">Appearance</h3>
<!-- Appearance Score -->
<div class="form-group">
    <label for="evalAppearanceScore">Appearance Score (<?php echo $appearance_points; ?> points possible)</label>
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
    <label for="evalAppearanceComments">Appearance Comments</label>
    <textarea class="form-control" name="evalAppearanceComments" rows="6" placeholder="Brief comments relating to appearance; 450 characters maximum." data-error="Please provide some brief appearance comments." maxlength="450" required><?php if ($action == "edit") echo $row_eval['evalAppearanceComments']; ?></textarea>
    <div class="help-block small with-errors"></div>
</div>

<!-- Flavor -->
<h3 class="section-heading">Flavor</h3>
<!-- Flavor Score -->
<div class="form-group">
    <label for="evalFlavorScore">Flavor Score (<?php echo $flavor_points; ?> points possible)</label>
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
    <label for="evalFlavorComments">Flavor Comments</label>
    <textarea class="form-control" name="evalFlavorComments" rows="6" placeholder="Brief comments relating to flavor; 450 characters maximum." data-error="Please provide some brief flavor comments." maxlength="450" required><?php if ($action == "edit") echo $row_eval['evalFlavorComments']; ?></textarea>
</div>

<?php if ($beer) { ?>
<!-- Mouthfeel -->
<h3 class="section-heading">Mouthfeel</h3>
<!-- Mouthfeel Score -->
<div class="form-group">
    <label for="evalMouthfeelScore">Mouthfeel Score (<?php echo $mouthfeel_points; ?> points possible)</label>
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
    <label for="evalMouthfeelComments">Mouthfeel Comments</label>
    <textarea class="form-control" name="evalMouthfeelComments" rows="6" placeholder="Brief comments relating to mouthfeel; 450 characters maximum." data-error="Please provide some brief mouthfeel comments." maxlength="450" required><?php if ($action == "edit") echo $row_eval['evalMouthfeelComments']; ?></textarea>
    <div class="help-block small with-errors"></div>
</div>
<?php } ?>
<!-- Overall Impression -->
<h3 class="section-heading">Overall Impression</h3>
<!-- Overall Impression Score -->
<div class="form-group">
    <label for="evalOverallScore">Overall Impression Score (<?php echo $overall_points; ?> points possible)</label>
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
    <label for="evalOverallComments">Overall Impression Comments</label>
    <textarea class="form-control" name="evalOverallComments" rows="6" placeholder="Brief comments relating to your overall impression; 800 characters maximum." data-error="Please provide some brief overall comments." maxlength="800" required><?php if ($action == "edit") echo $row_eval['evalOverallComments']; ?></textarea>
    <div class="help-block small with-errors"></div>
</div>
<!-- Style Accuracy -->
<div class="form-group">
    <label for="evalStyleAccuracy">Stylistic Accuracy</label>
    <?php for($i=5; $i>=1; $i--) { ?>
    <div class="radio">
        <label class="radio-inline">
            <input type="radio" name="evalStyleAccuracy" id="evalStyleAccuracy<?php echo $i; ?>" value="<?php echo $i; ?>" data-error="Please rate stylistic accuracy." required <?php if (($action == "edit") && ($row_eval['evalStyleAccuracy'] == $i)) echo "checked"; ?>> <?php if ($i == 1) echo "Not to Style"; if ($i == 5) echo "Classic Example"; ?>
        </label>
    </div>
    <?php } ?>
    <div class="help-block with-errors"></div>
</div>

<!-- Technical Merit -->
<div class="form-group">
    <label for="evalTechMerit">Technical Merit</label>
    <?php for($i=5; $i>=1; $i--) { ?>
    <div class="radio">
        <label class="radio-inline">
            <input type="radio" name="evalTechMerit" id="techMerit<?php echo $i; ?>" value="<?php echo $i; ?>" data-error="Please rate technical merit." required  <?php if (($action == "edit") && ($row_eval['evalTechMerit'] == $i)) echo "checked"; ?>> <?php if ($i == 1) echo "Significant Flaws"; if ($i == 5) echo "Flawless"; ?>
        </label>
    </div>
    <?php } ?>
    <div class="help-block with-errors"></div>
</div>

<!-- Intangibles -->
<div class="form-group">
    <label for="evalIntangibles">Intangibles</label>
    <?php for($i=5; $i>=1; $i--) { ?>
    <div class="radio">
        <label class="radio-inline">
            <input type="radio" name="evalIntangibles" id="evalIntangibles<?php echo $i; ?>" value="<?php echo $i; ?>" data-error="Please rate intangibles." required  <?php if (($action == "edit") && ($row_eval['evalIntangibles'] == $i)) echo "checked"; ?>> <?php if ($i == 1) echo "Lifeless"; if ($i == 5) echo "Wonderful"; ?>
        </label>
    </div>
    <?php } ?>
    <div class="help-block with-errors"></div>
</div>

<!-- Descriptors -->
<h3 class="section-heading">Descriptors</h3>
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