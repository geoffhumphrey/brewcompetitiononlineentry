<!-- Checklist Evaluation Form -->
<input type="hidden" name="evalFormType" value="2">
<!-- Aroma Checklist -->
<h3 class="section-heading"><?php echo $label_aroma; ?></h3>
<div class="form-horizontal">
  <div class="form-group">
    <label class="col-sm-3 control-label"><?php echo $label_malt; ?></label>
    <div class="col-sm-9">
    	<label class="radio-inline">
          	<input type="radio" name="evalAromaMalt" id="evalAromaMalt1" value="No Malt" data-error="<?php echo $evaluation_info_062; ?>" required <?php if ($action == "edit") { if (strpos($row_eval['evalAromaChecklist'],"No Malt") !== false) echo "checked"; } ?>> <?php echo $label_none; ?>
        </label>
        <label class="radio-inline">
          	<input type="radio" name="evalAromaMalt" id="evalAromaMalt2" value="Low Malt" data-error="<?php echo $evaluation_info_062; ?>" required <?php if ($action == "edit") { if (strpos($row_eval['evalAromaChecklist'],"Low Malt") !== false) echo "checked"; } ?>> <?php echo $label_low; ?>
        </label>
        <label class="radio-inline">
          	<input type="radio" name="evalAromaMalt" id="evalAromaMalt3" value="Medium Malt" data-error="<?php echo $evaluation_info_062; ?>" required <?php if ($action == "edit") { if (strpos($row_eval['evalAromaChecklist'],"Medium Malt") !== false) echo "checked"; } ?>> <?php echo $label_med; ?>
        </label>
        <label class="radio-inline">
          	<input type="radio" name="evalAromaMalt" id="evalAromaMalt4" value="High Malt" data-error="<?php echo $evaluation_info_062; ?>" required <?php if ($action == "edit") { if (strpos($row_eval['evalAromaChecklist'],"High Malt") !== false) echo "checked"; } ?>> <?php echo $label_high; ?>
        </label>
        <div class="help-block small with-errors"></div>
  	</div>
  </div>
  <div class="form-group">
    <label class="col-sm-3 control-label"><?php echo $label_hops; ?></label>
    <div class="col-sm-9">
    	<label class="radio-inline">
          	<input type="radio" name="evalAromaHops" id="evalAromaHops1" value="No Hops" data-error="<?php echo $evaluation_info_062; ?>" required <?php if ($action == "edit") { if (strpos($row_eval['evalAromaChecklist'],"No Hops") !== false) echo "checked"; } ?>> <?php echo $label_none; ?>
        </label>
        <label class="radio-inline">
          	<input type="radio" name="evalAromaHops" id="evalAromaHops2" value="Low Hops" data-error="<?php echo $evaluation_info_062; ?>" required <?php if ($action == "edit") { if (strpos($row_eval['evalAromaChecklist'],"Low Hops") !== false) echo "checked"; } ?>> <?php echo $label_low; ?>
        </label>
        <label class="radio-inline">
          	<input type="radio" name="evalAromaHops" id="evalAromaHops3" value="Medium Hops" data-error="<?php echo $evaluation_info_062; ?>" required <?php if ($action == "edit") { if (strpos($row_eval['evalAromaChecklist'],"Medium Hops") !== false) echo "checked"; } ?>> <?php echo $label_med; ?>
        </label>
        <label class="radio-inline">
          	<input type="radio" name="evalAromaHops" id="evalAromaHops4" value="High Hops" data-error="<?php echo $evaluation_info_062; ?>" required <?php if ($action == "edit") { if (strpos($row_eval['evalAromaChecklist'],"High Hops") !== false) echo "checked"; } ?>> <?php echo $label_high; ?>
        </label>
        <div class="help-block small with-errors"></div>
  	</div>
  </div>
  <div class="form-group">
    <label class="col-sm-3 control-label"><?php echo $label_esters; ?></label>
    <div class="col-sm-9">
    	<label class="radio-inline">
          	<input type="radio" name="evalAromaEsters" id="evalAromaEsters1" value="No Esters" data-error="<?php echo $evaluation_info_062; ?>" required <?php if ($action == "edit") { if (strpos($row_eval['evalAromaChecklist'],"No Esters") !== false) echo "checked"; } ?>> <?php echo $label_none; ?>
        </label>
        <label class="radio-inline">
          	<input type="radio" name="evalAromaEsters" id="evalAromaEsters2" value="Low Esters" data-error="<?php echo $evaluation_info_062; ?>" required <?php if ($action == "edit") { if (strpos($row_eval['evalAromaChecklist'],"Low Esters") !== false) echo "checked"; } ?>> <?php echo $label_low; ?>
        </label>
        <label class="radio-inline">
          	<input type="radio" name="evalAromaEsters" id="evalAromaEsters3" value="Medium Esters" data-error="<?php echo $evaluation_info_062; ?>" required <?php if ($action == "edit") { if (strpos($row_eval['evalAromaChecklist'],"Medium Esters") !== false) echo "checked"; } ?>> <?php echo $label_med; ?>
        </label>
        <label class="radio-inline">
          	<input type="radio" name="evalAromaEsters" id="evalAromaEsters4" value="High Esters" data-error="<?php echo $evaluation_info_062; ?>" required <?php if ($action == "edit") { if (strpos($row_eval['evalAromaChecklist'],"High Esters") !== false) echo "checked"; } ?>> <?php echo $label_high; ?>
        </label>
        <div class="help-block small with-errors"></div>
  	</div>
  </div>
  <div class="form-group">
    <label class="col-sm-3 control-label"><?php echo $label_phenols; ?></label>
    <div class="col-sm-9">
    	<label class="radio-inline">
          	<input type="radio" name="evalAromaPhenols" id="evalAromaPhenols1" value="No Phenols" data-error="<?php echo $evaluation_info_062; ?>" required  <?php if ($action == "edit") { if (strpos($row_eval['evalAromaChecklist'],"No Phenols") !== false) echo "checked"; } ?>> <?php echo $label_none; ?>
        </label>
        <label class="radio-inline">
          	<input type="radio" name="evalAromaPhenols" id="evalAromaPhenols2" value="Low Phenols" data-error="<?php echo $evaluation_info_062; ?>" required <?php if ($action == "edit") { if (strpos($row_eval['evalAromaChecklist'],"Low Phenols") !== false) echo "checked"; } ?>> <?php echo $label_low; ?>
        </label>
        <label class="radio-inline">
          	<input type="radio" name="evalAromaPhenols" id="evalAromaPhenols3" value="Medium Phenols" data-error="<?php echo $evaluation_info_062; ?>" required <?php if ($action == "edit") { if (strpos($row_eval['evalAromaChecklist'],"Medium Phenols") !== false) echo "checked"; } ?>> <?php echo $label_med; ?>
        </label>
        <label class="radio-inline">
          	<input type="radio" name="evalAromaPhenols" id="evalAromaPhenols4" value="High Phenols" data-error="<?php echo $evaluation_info_062; ?>" required <?php if ($action == "edit") { if (strpos($row_eval['evalAromaChecklist'],"High Phenols") !== false) echo "checked"; } ?>> <?php echo $label_high; ?>
        </label>
        <div class="help-block small with-errors"></div>
  	</div>
  </div>
  <div class="form-group">
    <label class="col-sm-3 control-label"><?php echo $label_alcohol; ?></label>
    <div class="col-sm-9">
    	<label class="radio-inline">
          	<input type="radio" name="evalAromaAlcohol" id="evalAromaAlcohol1" value="No Alcohol" data-error="<?php echo $evaluation_info_062; ?>" required <?php if ($action == "edit") { if (strpos($row_eval['evalAromaChecklist'],"No Alcohol") !== false) echo "checked"; } ?>> <?php echo $label_none; ?>
        </label>
        <label class="radio-inline">
          	<input type="radio" name="evalAromaAlcohol" id="evalAromaAlcohol2" value="Low Alcohol" data-error="<?php echo $evaluation_info_062; ?>" required <?php if ($action == "edit") { if (strpos($row_eval['evalAromaChecklist'],"Low Alcohol") !== false) echo "checked"; } ?>> <?php echo $label_low; ?>
        </label>
        <label class="radio-inline">
          	<input type="radio" name="evalAromaAlcohol" id="evalAromaAlcohol3" value="Medium Alcohol" data-error="<?php echo $evaluation_info_062; ?>" required <?php if ($action == "edit") { if (strpos($row_eval['evalAromaChecklist'],"Medium Alcohol") !== false) echo "checked"; } ?>> <?php echo $label_med; ?>
        </label>
        <label class="radio-inline">
          	<input type="radio" name="evalAromaAlcohol" id="evalAromaAlcohol4" value="High Alcohol" data-error="<?php echo $evaluation_info_062; ?>" required <?php if ($action == "edit") { if (strpos($row_eval['evalAromaChecklist'],"High Alcohol") !== false) echo "checked"; } ?>> <?php echo $label_high; ?>
        </label>
        <div class="help-block small with-errors"></div>
  	</div>
  </div>
  <div class="form-group">
    <label class="col-sm-3 control-label"><?php echo $label_sweetness; ?></label>
    <div class="col-sm-9">
    	<label class="radio-inline">
          	<input type="radio" name="evalAromaSweetness" id="evalAromaSweetness1" value="No Sweetness" data-error="<?php echo $evaluation_info_062; ?>" required <?php if ($action == "edit") { if (strpos($row_eval['evalAromaChecklist'],"No Sweetness") !== false) echo "checked"; } ?>> <?php echo $label_none; ?>
        </label>
        <label class="radio-inline">
          	<input type="radio" name="evalAromaSweetness" id="evalAromaSweetness2" value="Low Sweetness" data-error="<?php echo $evaluation_info_062; ?>" required <?php if ($action == "edit") { if (strpos($row_eval['evalAromaChecklist'],"Low Sweetness") !== false) echo "checked"; } ?>> <?php echo $label_low; ?>
        </label>
        <label class="radio-inline">
          	<input type="radio" name="evalAromaSweetness" id="evalAromaSweetness3" value="Medium Sweetness" data-error="<?php echo $evaluation_info_062; ?>" required <?php if ($action == "edit") { if (strpos($row_eval['evalAromaChecklist'],"Medium Sweetness") !== false) echo "checked"; } ?>> <?php echo $label_med; ?>
        </label>
        <label class="radio-inline">
          	<input type="radio" name="evalAromaSweetness" id="evalAromaSweetness4" value="High Sweetness" data-error="<?php echo $evaluation_info_062; ?>" required <?php if ($action == "edit") { if (strpos($row_eval['evalAromaChecklist'],"High Sweetness") !== false) echo "checked"; } ?>> <?php echo $label_high; ?>
        </label>
        <div class="help-block small with-errors"></div>
  	</div>
  </div>
  <div class="form-group">
    <label class="col-sm-3 control-label"><?php echo $label_acidity; ?></label>
    <div class="col-sm-9">
    	<label class="radio-inline">
          	<input type="radio" name="evalAromaAcidity" id="evalAromaAcidity1" value="No Acidity" data-error="<?php echo $evaluation_info_062; ?>" required <?php if ($action == "edit") { if (strpos($row_eval['evalAromaChecklist'],"No Acidity") !== false) echo "checked"; } ?>> <?php echo $label_none; ?>
        </label>
        <label class="radio-inline">
          	<input type="radio" name="evalAromaAcidity" id="evalAromaAcidity2" value="Low Acidity" data-error="<?php echo $evaluation_info_062; ?>" required <?php if ($action == "edit") { if (strpos($row_eval['evalAromaChecklist'],"Low Acidity") !== false) echo "checked"; } ?>> <?php echo $label_low; ?>
        </label>
        <label class="radio-inline">
          	<input type="radio" name="evalAromaAcidity" id="evalAromaAcidity3" value="Medium Acidity" data-error="<?php echo $evaluation_info_062; ?>" required <?php if ($action == "edit") { if (strpos($row_eval['evalAromaChecklist'],"Medium Acidity") !== false) echo "checked"; } ?>> <?php echo $label_med; ?>
        </label>
        <label class="radio-inline">
          	<input type="radio" name="evalAromaAcidity" id="evalAromaAcidity4" value="High Acidity" data-error="<?php echo $evaluation_info_062; ?>" required <?php if ($action == "edit") { if (strpos($row_eval['evalAromaChecklist'],"High Acidity") !== false) echo "checked"; } ?>> <?php echo $label_high; ?>
        </label>
        <div class="help-block small with-errors"></div>
  	</div>
  </div>
</div>
<!-- Aroma Score -->
<div class="form-group">
    <label for="evalAromaScore"><?php echo sprintf("%s: %s (%s %s)",$label_aroma,$label_score,$aroma_points,$label_possible_points); ?></label>
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
<!-- Aroma Comments -->
<div class="form-group">
    <label for="evalAromaComments"><?php echo sprintf("%s: %s",$label_aroma,$label_comments); ?></label>
    <textarea class="form-control" name="evalAromaComments" rows="6" placeholder="<?php echo "450 ".$evaluation_info_060; ?>" maxlength="450" data-error="<?php echo $evaluation_info_061; ?>" required><?php if ($action == "edit") echo $row_eval['evalAromaComments']; ?></textarea>
    <div class="help-block small with-errors"></div>
</div>
<!-- Aroma Descriptors
<div class="bcoem-admin-element">
	<button class="btn btn-info" type="button" data-toggle="collapse" data-target="#evalAromaDescriptors" aria-expanded="false" aria-controls="evalAromaDescriptors">Aroma Descriptors (Select to Expand/Collapse)</button>
</div> -->
<p><strong><?php echo sprintf("%s: %s",$label_aroma,$label_descriptors); ?></strong></p>
<div class="collapse in" id="evalAromaDescriptors">
  <section class="well">
    <div class="row">
    <div class="col col-lg-2 col-md-2 col-sm-4 col-xs-6">
      <div class="form-group">
        <label for="evalAromaMalt">Malt</label>
            <div class="checkbox">
            <label>
                <input type="checkbox" name="evalAromaChecklistDesc[]" id="evalAromaMaltDesc1" value="Grainy Malt" <?php if ($action == "edit") { if (strpos($row_eval['evalAromaChecklistDesc'],"Grainy Malt") !== false) echo "checked"; } ?>> <?php echo $label_grainy; ?>
            </label>
            </div>
            <div class="checkbox">
            <label>
                <input type="checkbox" name="evalAromaChecklistDesc[]" id="evalAromaMaltDesc2" value="Caramel Malt" <?php if ($action == "edit") { if (strpos($row_eval['evalAromaChecklistDesc'],"Caramel Malt") !== false) echo "checked"; } ?>> <?php echo $label_caramel; ?>
            </label>
            </div>
            <div class="checkbox">
            <label>
                <input type="checkbox" name="evalAromaChecklistDesc[]" id="evalAromaMaltDesc3" value="Bready Malt" <?php if ($action == "edit") { if (strpos($row_eval['evalAromaChecklistDesc'],"Bready Malt") !== false) echo "checked"; } ?>> <?php echo $label_bready; ?>
            </label>
            </div>
            <div class="checkbox">
            <label>
                <input type="checkbox" name="evalAromaChecklistDesc[]" id="evalAromaMaltDesc4" value="Rich Malt" <?php if ($action == "edit") { if (strpos($row_eval['evalAromaChecklistDesc'],"Rich Malt") !== false) echo "checked"; } ?>> <?php echo $label_rich; ?>
            </label>
            </div>
            <div class="checkbox">
            <label>
                <input type="checkbox" name="evalAromaChecklistDesc[]" id="evalAromaMaltDesc5" value="Dark Fruit Malt" <?php if ($action == "edit") { if (strpos($row_eval['evalAromaChecklistDesc'],"Dark Fruit Malt") !== false) echo "checked"; } ?>> <?php echo $label_dark_fruit; ?>
            </label>
            </div>
            <div class="checkbox">
            <label>
                <input type="checkbox" name="evalAromaChecklistDesc[]" id="evalAromaMaltDesc6" value="Toasty Malt" <?php if ($action == "edit") { if (strpos($row_eval['evalAromaChecklistDesc'],"Toasty Malt") !== false) echo "checked"; } ?>> <?php echo $label_toasty; ?>
            </label>
            </div>
            <div class="checkbox">
            <label>
                <input type="checkbox" name="evalAromaChecklistDesc[]" id="evalAromaMaltDesc7" value="Roasty Malt" <?php if ($action == "edit") { if (strpos($row_eval['evalAromaChecklistDesc'],"Roasty Malt") !== false) echo "checked"; } ?>> <?php echo $label_roasty; ?>
            </label>
            </div>
            <div class="checkbox">
            <label>
                <input type="checkbox" name="evalAromaChecklistDesc[]" id="evalAromaMaltDesc8" value="Burnt Malt" <?php if ($action == "edit") { if (strpos($row_eval['evalAromaChecklistDesc'],"Burnt Malt") !== false) echo "checked"; } ?>> <?php echo $label_burnt; ?>
            </label>
            </div>
      </div>
    </div>
    <div class="col col-lg-2 col-md-2 col-sm-4 col-xs-6">
      <div class="form-group">
        <label for="evalAromaHops"><?php echo $label_hops; ?></label>
            <div class="checkbox">
            <label>
                <input type="checkbox" name="evalAromaChecklistDesc[]" id="evalAromaHopsDesc1" value="Citrusy Hops" <?php if ($action == "edit") { if (strpos($row_eval['evalAromaChecklistDesc'],"Citrusy Hops") !== false) echo "checked"; } ?>> <?php echo $label_citrusy; ?>
            </label>
            </div>
            <div class="checkbox">
            <label>
                <input type="checkbox" name="evalAromaChecklistDesc[]" id="evalAromaHopsDesc2" value="Earthy Hops" <?php if ($action == "edit") { if (strpos($row_eval['evalAromaChecklistDesc'],"Earthy Hops") !== false) echo "checked"; } ?>> <?php echo $label_earthy; ?>
            </label>
            </div>
            <div class="checkbox">
            <label>
                <input type="checkbox" name="evalAromaChecklistDesc[]" id="evalAromaHopsDesc3" value="Floral Hops" <?php if ($action == "edit") { if (strpos($row_eval['evalAromaChecklistDesc'],"Floral Hops") !== false) echo "checked"; } ?>> <?php echo $label_floral; ?>
            </label>
            </div>
            <div class="checkbox">
            <label>
                <input type="checkbox" name="evalAromaChecklistDesc[]" id="evalAromaHopsDesc4" value="Grassy Hops" <?php if ($action == "edit") { if (strpos($row_eval['evalAromaChecklistDesc'],"Grassy Hops") !== false) echo "checked"; } ?>> <?php echo $label_grassy; ?>
            </label>
            </div>
            <div class="checkbox">
            <label>
                <input type="checkbox" name="evalAromaChecklistDesc[]" id="evalAromaHopsDesc5" value="Herbal Hops" <?php if ($action == "edit") { if (strpos($row_eval['evalAromaChecklistDesc'],"Herbal Hops") !== false) echo "checked"; } ?>> <?php echo $label_herbal; ?>
            </label>
            </div>
            <div class="checkbox">
            <label>
                <input type="checkbox" name="evalAromaChecklistDesc[]" id="evalAromaHopsDesc6" value="Piney Hops" <?php if ($action == "edit") { if (strpos($row_eval['evalAromaChecklistDesc'],"Piney Hops") !== false) echo "checked"; } ?>> <?php echo $label_piney; ?>
            </label>
            </div>
            <div class="checkbox">
            <label>
                <input type="checkbox" name="evalAromaChecklistDesc[]" id="evalAromaHopsDesc7" value="Spicy Hops" <?php if ($action == "edit") { if (strpos($row_eval['evalAromaChecklistDesc'],"Spicy Hops") !== false) echo "checked"; } ?>> <?php echo $label_spicy; ?>
            </label>
            </div>
            <div class="checkbox">
            <label>
                <input type="checkbox" name="evalAromaChecklistDesc[]" id="evalAromaHopsDesc8" value="Woody Hops" <?php if ($action == "edit") { if (strpos($row_eval['evalAromaChecklistDesc'],"Woody Hops") !== false) echo "checked"; } ?>> <?php echo $label_woody; ?>
            </label>
            </div>
      </div>
    </div>
    <div class="col col-lg-2 col-md-2 col-sm-4 col-xs-6">
      <div class="form-group">
        <label for="evalAromaEsters"><?php echo $label_esters; ?></label>
            <div class="checkbox">
            <label>
                <input type="checkbox" name="evalAromaChecklistDesc[]" id="evalAromaEstersDesc1" value="Fruity Esters" <?php if ($action == "edit") { if (strpos($row_eval['evalAromaChecklistDesc'],"Fruity Esters") !== false) echo "checked"; } ?>> <?php echo $label_fruity; ?>
            </label>
            </div>
            <div class="checkbox">
            <label>
                <input type="checkbox" name="evalAromaChecklistDesc[]" id="evalAromaEstersDesc2" value="Apple/Pear Esters" <?php if ($action == "edit") { if (strpos($row_eval['evalAromaChecklistDesc'],"Apple/Pear Esters") !== false) echo "checked"; } ?>> <?php echo $label_apple_pear; ?>
            </label>
            </div>
            <div class="checkbox">
            <label>
                <input type="checkbox" name="evalAromaChecklistDesc[]" id="evalAromaEstersDesc3" value="Banana Esters" <?php if ($action == "edit") { if (strpos($row_eval['evalAromaChecklistDesc'],"Banana Esters") !== false) echo "checked"; } ?>> <?php echo $label_banana; ?>
            </label>
            </div>
            <div class="checkbox">
            <label>
                <input type="checkbox" name="evalAromaChecklistDesc[]" id="evalAromaEstersDesc4" value="Berry Esters" <?php if ($action == "edit") { if (strpos($row_eval['evalAromaChecklistDesc'],"Berry Esters") !== false) echo "checked"; } ?>> <?php echo $label_berry; ?>
            </label>
            </div>
            <div class="checkbox">
            <label>
                <input type="checkbox" name="evalAromaChecklistDesc[]" id="evalAromaEstersDesc5" value="Citrus Esters" <?php if ($action == "edit") { if (strpos($row_eval['evalAromaChecklistDesc'],"Citrus Esters") !== false) echo "checked"; } ?>> <?php echo $label_citrus; ?>
            </label>
            </div>
            <div class="checkbox">
            <label>
                <input type="checkbox" name="evalAromaChecklistDesc[]" id="evalAromaEstersDesc6" value="Dried Fruit Esters" <?php if ($action == "edit") { if (strpos($row_eval['evalAromaChecklistDesc'],"Dried Fruit Esters") !== false) echo "checked"; } ?>> <?php echo $label_dried_fruit; ?>
            </label>
            </div>
            <div class="checkbox">
            <label>
                <input type="checkbox" name="evalAromaChecklistDesc[]" id="evalAromaEstersDesc7" value="Grape Esters" <?php if ($action == "edit") { if (strpos($row_eval['evalAromaChecklistDesc'],"Grape Esters") !== false) echo "checked"; } ?>> <?php echo $label_grape; ?>
            </label>
            </div>
            <div class="checkbox">
            <label>
                <input type="checkbox" name="evalAromaChecklistDesc[]" id="evalAromaEstersDesc8" value="Stone Fruit Esters" <?php if ($action == "edit") { if (strpos($row_eval['evalAromaChecklistDesc'],"Stone Fruit Esters") !== false) echo "checked"; } ?>> <?php echo $label_stone_fruit; ?>
            </label>
            </div>
        </div>
    </div>
    <div class="col col-lg-2 col-md-2 col-sm-4 col-xs-6">
      <div class="form-group">
        <label for="evalAromaOther"><?php echo $label_other; ?></label>
            <div class="checkbox">
            <label>
                <input type="checkbox" name="evalAromaChecklistDesc[]" id="evalAromaOtherDesc1" value="Brett" <?php if ($action == "edit") { if (strpos($row_eval['evalAromaChecklistDesc'],"Brett") !== false) echo "checked"; } ?>> <?php echo $label_brettanomyces; ?>
            </label>
            </div>
            <div class="checkbox">
            <label>
                <input type="checkbox" name="evalAromaChecklistDesc[]" id="evalAromaOtherDesc2" value="Fruit" <?php if ($action == "edit") { if (strpos($row_eval['evalAromaChecklistDesc'],"Fruit") !== false) echo "checked"; } ?>> <?php echo $label_fruit; ?>
            </label>
            </div>
            <div class="checkbox">
            <label>
                <input type="checkbox" name="evalAromaChecklistDesc[]" id="evalAromaOtherDesc3" value="Lactic" <?php if ($action == "edit") { if (strpos($row_eval['evalAromaChecklistDesc'],"Lactic") !== false) echo "checked"; } ?>> <?php echo $label_lactic; ?>
            </label>
            </div>
            <div class="checkbox">
            <label>
                <input type="checkbox" name="evalAromaChecklistDesc[]" id="evalAromaOtherDesc4" value="Smoke" <?php if ($action == "edit") { if (strpos($row_eval['evalAromaChecklistDesc'],"Smoke") !== false) echo "checked"; } ?>> <?php echo $label_smoke; ?>
            </label>
            </div>
            <div class="checkbox">
            <label>
                <input type="checkbox" name="evalAromaChecklistDesc[]" id="evalAromaOtherDesc5" value="Spice" <?php if ($action == "edit") { if (strpos($row_eval['evalAromaChecklistDesc'],"Spice") !== false) echo "checked"; } ?>> <?php echo $label_spice; ?>
            </label>
            </div>
            <div class="checkbox">
            <label>
                <input type="checkbox" name="evalAromaChecklistDesc[]" id="evalAromaOtherDesc6" value="Vinous" <?php if ($action == "edit") { if (strpos($row_eval['evalAromaChecklistDesc'],"Vinous") !== false) echo "checked"; } ?>> <?php echo $label_vinous; ?>
            </label>
            </div>
            <div class="checkbox">
            <label>
                <input type="checkbox" name="evalAromaChecklistDesc[]" id="evalAromaOtherDesc7" value="Wood" <?php if ($action == "edit") { if (strpos($row_eval['evalAromaChecklistDesc'],"Wood") !== false) echo "checked"; } ?>> <?php echo $label_wood; ?>
            </label>
            </div>
        </div>
    </div>
  </div><!-- END Row -->
  </section>
</div><!-- END Aroma Collapse -->
<!-- Appearance Checklist -->
<h3 class="section-heading"><?php echo $label_appearance; ?></h3>
<div class="form-horizontal">
  <div class="form-group">
    <label class="col-sm-3 control-label"><?php echo $label_clarity; ?></label>
    <div class="col-sm-9">
    	<label class="radio-inline">
          	<input type="radio" name="evalAppearanceClarity" id="evalAppearanceClarity1" value="No Clarity" data-error="<?php echo $evaluation_info_062; ?>" required <?php if ($action == "edit") { if (strpos($row_eval['evalAppearanceChecklist'],"No Clarity") !== false) echo "checked"; } ?>> <?php echo $label_none; ?>
        </label>
        <label class="radio-inline">
          	<input type="radio" name="evalAppearanceClarity" id="evalAppearanceClarity2" value="Low Clarity" data-error="<?php echo $evaluation_info_062; ?>" required <?php if ($action == "edit") { if (strpos($row_eval['evalAppearanceChecklist'],"Low Clarity") !== false) echo "checked"; } ?>> <?php echo $label_low; ?>
        </label>
        <label class="radio-inline">
          	<input type="radio" name="evalAppearanceClarity" id="evalAppearanceClarity3" value="Medium Clarity" data-error="<?php echo $evaluation_info_062; ?>" required <?php if ($action == "edit") { if (strpos($row_eval['evalAppearanceChecklist'],"Medium Clarity") !== false) echo "checked"; } ?>> <?php echo $label_med; ?>
        </label>
        <label class="radio-inline">
          	<input type="radio" name="evalAppearanceClarity" id="evalAppearanceClarity4" value="High Clarity" data-error="<?php echo $evaluation_info_062; ?>" required <?php if ($action == "edit") { if (strpos($row_eval['evalAppearanceChecklist'],"High Clarity") !== false) echo "checked"; } ?>> <?php echo $label_high; ?>
        </label>
        <div class="help-block small with-errors"></div>
  	</div>
  </div>
  <div class="form-group">
    <label class="col-sm-3 control-label"><?php echo $label_head_size; ?></label>
    <div class="col-sm-9">
    	<label class="radio-inline">
          	<input type="radio" name="evalAppearanceHeadSize" id="evalAppearanceHeadSize1" value="No Head" data-error="<?php echo $evaluation_info_062; ?>" required <?php if ($action == "edit") { if (strpos($row_eval['evalAppearanceChecklist'],"No Head") !== false) echo "checked"; } ?>> <?php echo $label_none; ?>
        </label>
        <label class="radio-inline">
          	<input type="radio" name="evalAppearanceHeadSize" id="evalAppearanceHeadSize2" value="Low Head" data-error="<?php echo $evaluation_info_062; ?>" required <?php if ($action == "edit") { if (strpos($row_eval['evalAppearanceChecklist'],"Low Head") !== false) echo "checked"; } ?>> <?php echo $label_low; ?>
        </label>
        <label class="radio-inline">
          	<input type="radio" name="evalAppearanceHeadSize" id="evalAppearanceHeadSize3" value="Medium Head" data-error="<?php echo $evaluation_info_062; ?>" required <?php if ($action == "edit") { if (strpos($row_eval['evalAppearanceChecklist'],"Medium Head") !== false) echo "checked"; } ?>> <?php echo $label_med; ?>
        </label>
        <label class="radio-inline">
          	<input type="radio" name="evalAppearanceHeadSize" id="evalAppearanceHeadSize4" value="High Head" data-error="<?php echo $evaluation_info_062; ?>" required <?php if ($action == "edit") { if (strpos($row_eval['evalAppearanceChecklist'],"High Head") !== false) echo "checked"; } ?>> <?php echo $label_high; ?>
        </label>
        <div class="help-block small with-errors"></div>
  	</div>
  </div>
  <div class="form-group">
    <label class="col-sm-3 control-label"><?php echo $label_head_retention; ?></label>
    <div class="col-sm-9">
    	<label class="radio-inline">
          	<input type="radio" name="evalAppearanceHeadRetention" id="evalAppearanceHeadRetention1" value="No Head Retention" data-error="<?php echo $evaluation_info_062; ?>" required <?php if ($action == "edit") { if (strpos($row_eval['evalAppearanceChecklist'],"No Head Retention") !== false) echo "checked"; } ?>> <?php echo $label_none; ?>
        </label>
        <label class="radio-inline">
          	<input type="radio" name="evalAppearanceHeadRetention" id="evalAppearanceHeadRetention2" value="Low Head Retention" data-error="Please choose a head retention descriptor." required <?php if ($action == "edit") { if (strpos($row_eval['evalAppearanceChecklist'],"Low Head Retention") !== false) echo "checked"; } ?>> <?php echo $label_low; ?>
        </label>
        <label class="radio-inline">
          	<input type="radio" name="evalAppearanceHeadRetention" id="evalAppearanceHeadRetention3" value="Medium Head Retention" data-error="Please choose a head retention descriptor." required <?php if ($action == "edit") { if (strpos($row_eval['evalAppearanceChecklist'],"Medium Head Retention") !== false) echo "checked"; } ?>> <?php echo $label_med; ?>
        </label>
        <label class="radio-inline">
          	<input type="radio" name="evalAppearanceHeadRetention" id="evalAppearanceHeadRetention4" value="High Head Retention" data-error="Please choose a head retention descriptor." required <?php if ($action == "edit") { if (strpos($row_eval['evalAppearanceChecklist'],"High Head Retention") !== false) echo "checked"; } ?>> <?php echo $label_high; ?>
        </label>
        <div class="help-block small with-errors"></div>
  	</div>
  </div>
</div>
<!-- Appearance Score -->
<div class="form-group">
    <label for="evalAppearanceScore"><?php echo sprintf("%s: %s (%s %s)",$label_appearance,$label_score,$appearance_points,$label_possible_points); ?></label>
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
    <label for="evalAppearanceComments"><?php echo sprintf("%s: %s",$label_appearance,$label_comments); ?></label>
    <textarea class="form-control" name="evalAppearanceComments" rows="6" placeholder="<?php echo "450 ".$evaluation_info_060; ?>" maxlength="450" data-error="<?php echo $evaluation_info_061; ?>" required><?php if ($action == "edit") echo $row_eval['evalAppearanceComments']; ?></textarea>
    <div class="help-block small with-errors"></div>
</div>
<!-- Appearance Descriptors
<div class="bcoem-admin-element">
	<button class="btn btn-info" type="button" data-toggle="collapse" data-target="#appearanceDescriptors" aria-expanded="false" aria-controls="appearanceDescriptors">Appearance Descriptors (Select to Expand/Collapse)</button>
</div>
-->
<p><strong><?php echo sprintf("%s: %s",$label_appearance,$label_descriptors); ?></strong></p>
<div class="collapse in" id="appearanceDescriptors">
  <section class="well">
    <div class="row">
      <div class="col col-lg-2 col-md-2 col-sm-4 col-xs-6">
        <div class="form-group">
        <label for="appearanceColor"><?php echo $label_color; ?></label>
            <div class="checkbox">
            <label>
                <input type="checkbox" name="evalAppearanceChecklistDesc[]" id="evalAppearanceColorDesc1" value="Straw Color" <?php if ($action == "edit") { if (strpos($row_eval['evalAppearanceChecklistDesc'],"Straw Color") !== false) echo "checked"; } ?>> <?php echo $label_straw; ?>
            </label>
            </div>
            <div class="checkbox">
            <label>
                <input type="checkbox" name="evalAppearanceChecklistDesc[]" id="evalAppearanceColorDesc2" value="Yellow Color" <?php if ($action == "edit") { if (strpos($row_eval['evalAppearanceChecklistDesc'],"Yellow Color") !== false) echo "checked"; } ?>> <?php echo $label_yellow; ?>
            </label>
            </div>
            <div class="checkbox">
            <label>
                <input type="checkbox" name="evalAppearanceChecklistDesc[]" id="evalAppearanceColorDesc3" value="Gold Color" <?php if ($action == "edit") { if (strpos($row_eval['evalAppearanceChecklistDesc'],"Gold Color") !== false) echo "checked"; } ?>> <?php echo $label_gold; ?>
            </label>
            </div>
            <div class="checkbox">
            <label>
                <input type="checkbox" name="evalAppearanceChecklistDesc[]" id="evalAppearanceColorDesc4" value="Amber Color" <?php if ($action == "edit") { if (strpos($row_eval['evalAppearanceChecklistDesc'],"Amber Color") !== false) echo "checked"; } ?>> <?php echo $label_amber; ?>
            </label>
            </div>
            <div class="checkbox">
            <label>
                <input type="checkbox" name="evalAppearanceChecklistDesc[]" id="evalAppearanceColorDesc5" value="Copper Color" <?php if ($action == "edit") { if (strpos($row_eval['evalAppearanceChecklistDesc'],"Copper Color") !== false) echo "checked"; } ?>> <?php echo $label_copper; ?>
            </label>
            </div>
            <div class="checkbox">
            <label>
                <input type="checkbox" name="evalAppearanceChecklistDesc[]" id="evalAppearanceColorDesc6" value="Brown Color" <?php if ($action == "edit") { if (strpos($row_eval['evalAppearanceChecklistDesc'],"Brown Color") !== false) echo "checked"; } ?>> <?php echo $label_brown; ?>
            </label>
            </div>
            <div class="checkbox">
            <label>
                <input type="checkbox" name="evalAppearanceChecklistDesc[]" id="evalAppearanceColorDesc7" value="Black Color" <?php if ($action == "edit") { if (strpos($row_eval['evalAppearanceChecklistDesc'],"Black Color") !== false) echo "checked"; } ?>> <?php echo $label_black; ?>
            </label>
            </div>
        </div>
      </div>
      <div class="col col-lg-2 col-md-2 col-sm-4 col-xs-6">
        <div class="form-group">
        <label for="appearanceHeadColor"><?php echo $label_head_color; ?></label>
            <div class="checkbox">
            <label>
                <input type="checkbox" name="evalAppearanceChecklistDesc[]" id="evalAppearanceHeadDesc1" value="White Head Color" <?php if ($action == "edit") { if (strpos($row_eval['evalAppearanceChecklistDesc'],"White Head Color") !== false) echo "checked"; } ?>> <?php echo $label_white; ?>
            </label>
            </div>
            <div class="checkbox">
            <label>
                <input type="checkbox" name="evalAppearanceChecklistDesc[]" id="evalAppearanceHeadDesc2" value="Ivory Head Color" <?php if ($action == "edit") { if (strpos($row_eval['evalAppearanceChecklistDesc'],"Ivory Head Color") !== false) echo "checked"; } ?>> <?php echo $label_ivory; ?>
            </label>
            </div>
            <div class="checkbox">
            <label>
                <input type="checkbox" name="evalAppearanceChecklistDesc[]" id="evalAppearanceHeadDesc3" value="Cream Head Color" <?php if ($action == "edit") { if (strpos($row_eval['evalAppearanceChecklistDesc'],"Cream Head Color") !== false) echo "checked"; } ?>> <?php echo $label_cream; ?>
            </label>
            </div>
            <div class="checkbox">
            <label>
                <input type="checkbox" name="evalAppearanceChecklistDesc[]" id="evalAppearanceHeadDesc4" value="Beige Head Color" <?php if ($action == "edit") { if (strpos($row_eval['evalAppearanceChecklistDesc'],"Beige Head Color") !== false) echo "checked"; } ?>> <?php echo $label_beige; ?>
            </label>
            </div>
            <div class="checkbox">
            <label>
                <input type="checkbox" name="evalAppearanceChecklistDesc[]" id="evalAppearanceHeadDesc5" value="Tan Head Color" <?php if ($action == "edit") { if (strpos($row_eval['evalAppearanceChecklistDesc'],"Tan Head Color") !== false) echo "checked"; } ?>> <?php echo $label_tan; ?>
            </label>
            </div>
            <div class="checkbox">
            <label>
                <input type="checkbox" name="evalAppearanceChecklistDesc[]" id="evalAppearanceHeadDesc6" value="Brown Head Color" <?php if ($action == "edit") { if (strpos($row_eval['evalAppearanceChecklistDesc'],"Brown Head Color") !== false) echo "checked"; } ?>> <?php echo $label_brown; ?>
            </label>
            </div>
        </div>
      </div>
      <div class="col col-lg-2 col-md-2 col-sm-4 col-xs-6">
        <div class="form-group">
        <label for="appearanceOther"><?php echo $label_other; ?></label>
            <div class="checkbox">
            <label>
                <input type="checkbox" name="evalAppearanceChecklistDesc[]" id="evalAppearanceOtherDesc1" value="Flat" <?php if ($action == "edit") { if (strpos($row_eval['evalAppearanceChecklistDesc'],"Flat") !== false) echo "checked"; } ?>> <?php echo $label_flat; ?>
            </label>
            </div>
            <div class="checkbox">
            <label>
                <input type="checkbox" name="evalAppearanceChecklistDesc[]" id="evalAppearanceOtherDesc2" value="Lacing" <?php if ($action == "edit") { if (strpos($row_eval['evalAppearanceChecklistDesc'],"Lacing") !== false) echo "checked"; } ?>> <?php echo $label_lacing; ?>
            </label>
            </div>
            <div class="checkbox">
            <label>
                <input type="checkbox" name="evalAppearanceChecklistDesc[]" id="evalAppearanceOtherDesc3" value="Legs" <?php if ($action == "edit") { if (strpos($row_eval['evalAppearanceChecklistDesc'],"Legs") !== false) echo "checked"; } ?>> <?php echo $label_legs; ?>
            </label>
            </div>
            <div class="checkbox">
            <label>
                <input type="checkbox" name="evalAppearanceChecklistDesc[]" id="evalAppearanceOtherDesc4" value="Opaque" <?php if ($action == "edit") { if (strpos($row_eval['evalAppearanceChecklistDesc'],"Opaque") !== false) echo "checked"; } ?>> <?php echo $label_opaque; ?>
            </label>
            </div>
        </div>
      </div>
    </div><!-- End Row -->
  </section>
</div><!-- End Collapse -->
<!-- Flavor Checklist -->
<h3 class="section-heading"><?php echo $label_flavor; ?></h3>
<div class="form-horizontal">
  <div class="form-group">
    <label class="col-sm-3 control-label"><?php echo $label_malt; ?></label>
    <div class="col-sm-9">
    	<label class="radio-inline">
          	<input type="radio" name="evalFlavorMalt" id="evalFlavorMalt1" value="No Malt" data-error="<?php echo $evaluation_info_062; ?>" required <?php if ($action == "edit") { if (strpos($row_eval['evalFlavorChecklist'],"No Malt") !== false) echo "checked"; } ?>> <?php echo $label_none; ?>
        </label>
        <label class="radio-inline">
          	<input type="radio" name="evalFlavorMalt" id="evalFlavorMalt2" value="Low Malt" data-error="<?php echo $evaluation_info_062; ?>" required <?php if ($action == "edit") { if (strpos($row_eval['evalFlavorChecklist'],"Low Malt") !== false) echo "checked"; } ?>> <?php echo $label_low; ?>
        </label>
        <label class="radio-inline">
          	<input type="radio" name="evalFlavorMalt" id="evalFlavorMalt3" value="Medium Malt" data-error="<?php echo $evaluation_info_062; ?>" required <?php if ($action == "edit") { if (strpos($row_eval['evalFlavorChecklist'],"Medium Malt") !== false) echo "checked"; } ?>> <?php echo $label_med; ?>
        </label>
        <label class="radio-inline">
          	<input type="radio" name="evalFlavorMalt" id="evalFlavorMalt4" value="High Malt" data-error="<?php echo $evaluation_info_062; ?>" required <?php if ($action == "edit") { if (strpos($row_eval['evalFlavorChecklist'],"High Malt") !== false) echo "checked"; } ?>> <?php echo $label_high; ?>
        </label>
        <div class="help-block small with-errors"></div>
  	</div>
  </div>
  <div class="form-group">
    <label class="col-sm-3 control-label"><?php echo $label_hops; ?></label>
    <div class="col-sm-9">
    	<label class="radio-inline">
          	<input type="radio" name="evalFlavorHops" id="evalFlavorHops1" value="No Hops" data-error="<?php echo $evaluation_info_062; ?>" required <?php if ($action == "edit") { if (strpos($row_eval['evalFlavorChecklist'],"No Hops") !== false) echo "checked"; } ?>> <?php echo $label_none; ?>
        </label>
        <label class="radio-inline">
          	<input type="radio" name="evalFlavorHops" id="evalFlavorHops2" value="Low Hops" data-error="<?php echo $evaluation_info_062; ?>" required <?php if ($action == "edit") { if (strpos($row_eval['evalFlavorChecklist'],"Low Hops") !== false) echo "checked"; } ?>> <?php echo $label_low; ?>
        </label>
        <label class="radio-inline">
          	<input type="radio" name="evalFlavorHops" id="evalFlavorHops3" value="Medium Hops" data-error="<?php echo $evaluation_info_062; ?>" required <?php if ($action == "edit") { if (strpos($row_eval['evalFlavorChecklist'],"Medium Hops") !== false) echo "checked"; } ?>> <?php echo $label_med; ?>
        </label>
        <label class="radio-inline">
          	<input type="radio" name="evalFlavorHops" id="evalFlavorHops4" value="High Hops" data-error="<?php echo $evaluation_info_062; ?>" required <?php if ($action == "edit") { if (strpos($row_eval['evalFlavorChecklist'],"High Hops") !== false) echo "checked"; } ?>> <?php echo $label_high; ?>
        </label>
        <div class="help-block small with-errors"></div>
  	</div>
  </div>
  <div class="form-group">
    <label class="col-sm-3 control-label"><?php echo $label_esters; ?></label>
    <div class="col-sm-9">
    	<label class="radio-inline">
          	<input type="radio" name="evalFlavorEsters" id="evalFlavorEsters1" value="No Esters" data-error="<?php echo $evaluation_info_062; ?>" required <?php if ($action == "edit") { if (strpos($row_eval['evalFlavorChecklist'],"No Esters") !== false) echo "checked"; } ?>> <?php echo $label_none; ?>
        </label>
        <label class="radio-inline">
          	<input type="radio" name="evalFlavorEsters" id="evalFlavorEsters2" value="Low Esters" data-error="<?php echo $evaluation_info_062; ?>" required <?php if ($action == "edit") { if (strpos($row_eval['evalFlavorChecklist'],"Low Esters") !== false) echo "checked"; } ?>> <?php echo $label_low; ?>
        </label>
        <label class="radio-inline">
          	<input type="radio" name="evalFlavorEsters" id="evalFlavorEsters3" value="Medium Esters" data-error="<?php echo $evaluation_info_062; ?>" required <?php if ($action == "edit") { if (strpos($row_eval['evalFlavorChecklist'],"Medium Esters") !== false) echo "checked"; } ?>> <?php echo $label_med; ?>
        </label>
        <label class="radio-inline">
          	<input type="radio" name="evalFlavorEsters" id="evalFlavorEsters4" value="High Esters" data-error="<?php echo $evaluation_info_062; ?>" required <?php if ($action == "edit") { if (strpos($row_eval['evalFlavorChecklist'],"High Esters") !== false) echo "checked"; } ?>> <?php echo $label_high; ?>
        </label>
        <div class="help-block small with-errors"></div>
  	</div>
  </div>
  <div class="form-group">
    <label class="col-sm-3 control-label"><?php echo $label_phenols; ?></label>
    <div class="col-sm-9">
    	<label class="radio-inline">
          	<input type="radio" name="evalFlavorPhenols" id="evalFlavorPhenols1" value="No Phenols" data-error="<?php echo $evaluation_info_062; ?>" required <?php if ($action == "edit") { if (strpos($row_eval['evalFlavorChecklist'],"No Phenols") !== false) echo "checked"; } ?>> <?php echo $label_none; ?>
        </label>
        <label class="radio-inline">
          	<input type="radio" name="evalFlavorPhenols" id="evalFlavorPhenols2" value="Low Phenols" data-error="<?php echo $evaluation_info_062; ?>" required <?php if ($action == "edit") { if (strpos($row_eval['evalFlavorChecklist'],"Low Phenols") !== false) echo "checked"; } ?>> <?php echo $label_low; ?>
        </label>
        <label class="radio-inline">
          	<input type="radio" name="evalFlavorPhenols" id="evalFlavorPhenols3" value="Medium Phenols" data-error="<?php echo $evaluation_info_062; ?>" required <?php if ($action == "edit") { if (strpos($row_eval['evalFlavorChecklist'],"Medium Phenols") !== false) echo "checked"; } ?>> <?php echo $label_med; ?>
        </label>
        <label class="radio-inline">
          	<input type="radio" name="evalFlavorPhenols" id="evalFlavorPhenols4" value="High Phenols" data-error="<?php echo $evaluation_info_062; ?>" required <?php if ($action == "edit") { if (strpos($row_eval['evalFlavorChecklist'],"High Phenols") !== false) echo "checked"; } ?>> <?php echo $label_high; ?>
        </label>
        <div class="help-block small with-errors"></div>
  	</div>
  </div>
   <div class="form-group">
    <label class="col-sm-3 control-label"><?php echo $label_sweetness; ?></label>
    <div class="col-sm-9">
    	<label class="radio-inline">
          	<input type="radio" name="evalFlavorSweetness" id="evalFlavorSweetness1" value="No Sweetness" data-error="<?php echo $evaluation_info_062; ?>" required <?php if ($action == "edit") { if (strpos($row_eval['evalFlavorChecklist'],"No Sweetness") !== false) echo "checked"; } ?>> <?php echo $label_none; ?>
        </label>
        <label class="radio-inline">
          	<input type="radio" name="evalFlavorSweetness" id="evalFlavorSweetness2" value="Low Sweetness" data-error="<?php echo $evaluation_info_062; ?>" required <?php if ($action == "edit") { if (strpos($row_eval['evalFlavorChecklist'],"Low Sweetness") !== false) echo "checked"; } ?>> <?php echo $label_low; ?>
        </label>
        <label class="radio-inline">
          	<input type="radio" name="evalFlavorSweetness" id="evalFlavorSweetness3" value="Medium Sweetness" data-error="<?php echo $evaluation_info_062; ?>" required <?php if ($action == "edit") { if (strpos($row_eval['evalFlavorChecklist'],"Medium Sweetness") !== false) echo "checked"; } ?>> <?php echo $label_med; ?>
        </label>
        <label class="radio-inline">
          	<input type="radio" name="evalFlavorSweetness" id="evalFlavorSweetness4" value="High Sweetness" data-error="<?php echo $evaluation_info_062; ?>" required <?php if ($action == "edit") { if (strpos($row_eval['evalFlavorChecklist'],"High Sweetness") !== false) echo "checked"; } ?>> <?php echo $label_high; ?>
        </label>
        <div class="help-block small with-errors"></div>
  	</div>
  </div>
  <div class="form-group">
    <label class="col-sm-3 control-label"><?php echo $label_bitterness; ?></label>
    <div class="col-sm-9">
    	<label class="radio-inline">
          	<input type="radio" name="evalFlavorBitterness" id="evalFlavorBitterness1" value="No Bitterness" data-error="<?php echo $evaluation_info_062; ?>" required <?php if ($action == "edit") { if (strpos($row_eval['evalFlavorChecklist'],"No Bitterness") !== false) echo "checked"; } ?>> <?php echo $label_none; ?>
        </label>
        <label class="radio-inline">
          	<input type="radio" name="evalFlavorBitterness" id="evalFlavorBitterness2" value="Low Bitterness" data-error="<?php echo $evaluation_info_062; ?>" required <?php if ($action == "edit") { if (strpos($row_eval['evalFlavorChecklist'],"Low Bitterness") !== false) echo "checked"; } ?>> <?php echo $label_low; ?>
        </label>
        <label class="radio-inline">
          	<input type="radio" name="evalFlavorBitterness" id="evalFlavorBitterness3" value="Medium Bitterness" data-error="<?php echo $evaluation_info_062; ?>" required <?php if ($action == "edit") { if (strpos($row_eval['evalFlavorChecklist'],"Medium Bitterness") !== false) echo "checked"; } ?>> <?php echo $label_med; ?>
        </label>
        <label class="radio-inline">
          	<input type="radio" name="evalFlavorBitterness" id="evalFlavorBitterness4" value="High Bitterness" data-error="<?php echo $evaluation_info_062; ?>" required <?php if ($action == "edit") { if (strpos($row_eval['evalFlavorChecklist'],"High Bitterness") !== false) echo "checked"; } ?>> <?php echo $label_high; ?>
        </label>
        <div class="help-block small with-errors"></div>
  	</div>
  </div>
  <div class="form-group">
    <label class="col-sm-3 control-label"><?php echo $label_alcohol; ?></label>
    <div class="col-sm-9">
    	<label class="radio-inline">
          	<input type="radio" name="evalFlavorAlcohol" id="evalFlavorAlcohol1" value="No Alcohol" data-error="<?php echo $evaluation_info_062; ?>" required <?php if ($action == "edit") { if (strpos($row_eval['evalFlavorChecklist'],"No Alcohol") !== false) echo "checked"; } ?>> <?php echo $label_none; ?>
        </label>
        <label class="radio-inline">
          	<input type="radio" name="evalFlavorAlcohol" id="evalFlavorAlcohol2" value="Low Alcohol" data-error="<?php echo $evaluation_info_062; ?>" required <?php if ($action == "edit") { if (strpos($row_eval['evalFlavorChecklist'],"Low Alcohol") !== false) echo "checked"; } ?>> <?php echo $label_low; ?>
        </label>
        <label class="radio-inline">
          	<input type="radio" name="evalFlavorAlcohol" id="evalFlavorAlcohol3" value="Medium Alcohol" data-error="<?php echo $evaluation_info_062; ?>" required <?php if ($action == "edit") { if (strpos($row_eval['evalFlavorChecklist'],"Medium Alcohol") !== false) echo "checked"; } ?>> <?php echo $label_med; ?>
        </label>
        <label class="radio-inline">
          	<input type="radio" name="evalFlavorAlcohol" id="evalFlavorAlcohol4" value="High Alcohol" data-error="<?php echo $evaluation_info_062; ?>" required <?php if ($action == "edit") { if (strpos($row_eval['evalFlavorChecklist'],"High Alcohol") !== false) echo "checked"; } ?>> <?php echo $label_high; ?>
        </label>
        <div class="help-block small with-errors"></div>
  	</div>
  </div>
  <div class="form-group">
    <label class="col-sm-3 control-label"><?php echo $label_acidity; ?></label>
    <div class="col-sm-9">
    	<label class="radio-inline">
          	<input type="radio" name="evalFlavorAcidity" id="evalFlavorAcidity1" value="No Acidity" data-error="<?php echo $evaluation_info_062; ?>" required <?php if ($action == "edit") { if (strpos($row_eval['evalFlavorChecklist'],"No Acidity") !== false) echo "checked"; } ?>> <?php echo $label_none; ?>
        </label>
        <label class="radio-inline">
          	<input type="radio" name="evalFlavorAcidity" id="evalFlavorAcidity2" value="Low Acidity" data-error="<?php echo $evaluation_info_062; ?>" required <?php if ($action == "edit") { if (strpos($row_eval['evalFlavorChecklist'],"Low Acidity") !== false) echo "checked"; } ?>> <?php echo $label_low; ?>
        </label>
        <label class="radio-inline">
          	<input type="radio" name="evalFlavorAcidity" id="evalFlavorAcidity3" value="Medium Acidity" data-error="<?php echo $evaluation_info_062; ?>" required <?php if ($action == "edit") { if (strpos($row_eval['evalFlavorChecklist'],"Medium Acidity") !== false) echo "checked"; } ?>> <?php echo $label_med; ?>
        </label>
        <label class="radio-inline">
          	<input type="radio" name="evalFlavorAcidity" id="evalFlavorAcidity4" value="High Acidity" data-error="<?php echo $evaluation_info_062; ?>" required <?php if ($action == "edit") { if (strpos($row_eval['evalFlavorChecklist'],"High Acidity") !== false) echo "checked"; } ?>> <?php echo $label_high; ?>
        </label>
        <div class="help-block small with-errors"></div>
  	</div>
  </div>
  <div class="form-group">
    <label class="col-sm-3 control-label"><?php echo $label_harshness; ?></label>
    <div class="col-sm-9">
    	<label class="radio-inline">
          	<input type="radio" name="evalFlavorHarshness" id="evalFlavorHarshness1" value="No Harshness" data-error="<?php echo $evaluation_info_062; ?>" required <?php if ($action == "edit") { if (strpos($row_eval['evalFlavorChecklist'],"No Harshness") !== false) echo "checked"; } ?>> <?php echo $label_none; ?>
        </label>
        <label class="radio-inline">
          	<input type="radio" name="evalFlavorHarshness" id="evalFlavorHarshness2" value="Low Harshness" data-error="<?php echo $evaluation_info_062; ?>" required <?php if ($action == "edit") { if (strpos($row_eval['evalFlavorChecklist'],"Low Harshness") !== false) echo "checked"; } ?>> <?php echo $label_low; ?>
        </label>
        <label class="radio-inline">
          	<input type="radio" name="evalFlavorHarshness" id="evalFlavorHarshness3" value="Medium Harshness" data-error="<?php echo $evaluation_info_062; ?>" required <?php if ($action == "edit") { if (strpos($row_eval['evalFlavorChecklist'],"Medium Harshness") !== false) echo "checked"; } ?>> <?php echo $label_med; ?>
        </label>
        <label class="radio-inline">
          	<input type="radio" name="evalFlavorHarshness" id="evalFlavorHarshness" value="High Harshness" data-error="<?php echo $evaluation_info_062; ?>" required <?php if ($action == "edit") { if (strpos($row_eval['evalFlavorChecklist'],"High Harshness") !== false) echo "checked"; } ?>> <?php echo $label_high; ?>
        </label>
        <div class="help-block small with-errors"></div>
  	</div>
  </div>
</div>
<!-- Flavor Score -->
<div class="form-group">
    <label for="evalFlavorScore"><?php echo sprintf("%s: %s (%s %s)",$label_flavor,$label_score,$flavor_points,$label_possible_points); ?></label>
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
    <label for="evalFlavorComments"><?php echo sprintf("%s: %s",$label_flavor,$label_comments); ?></label>
    <textarea class="form-control" name="evalFlavorComments" rows="6" placeholder="<?php echo "450 ".$evaluation_info_060; ?>" maxlength="450" data-error="<?php echo $evaluation_info_062; ?>" required><?php if ($action == "edit") echo $row_eval['evalFlavorComments']; ?></textarea>
    <div class="help-block small with-errors"></div>
</div>
<!-- Flavor Descriptors
<div class="bcoem-admin-element">
	<button class="btn btn-info" type="button" data-toggle="collapse" data-target="#flavorDescriptors" aria-expanded="false" aria-controls="flavorDescriptors">Flavor Descriptors (Select to Expand/Collapse)</button>
</div>
-->
<p><strong><?php echo sprintf("%s: %s",$label_flavor,$label_descriptors); ?></strong></p>
<div class="collapse in" id="flavorDescriptors">
  <div class="well">
    <div class="row">
      <div class="col col-lg-2 col-md-2 col-sm-4 col-xs-6">
        <div class="form-group">
          <label for="flavorMalt"><?php echo $label_malt; ?></label>
              <div class="checkbox">
              <label>
                  <input type="checkbox" name="evalFlavorChecklistDesc[]" id="evalFlavorMaltDesc1" value="Grainy Malt" <?php if ($action == "edit") { if (strpos($row_eval['evalFlavorChecklistDesc'],"Grainy Malt") !== false) echo "checked"; } ?>> <?php echo $label_grainy; ?>
              </label>
              </div>
              <div class="checkbox">
              <label>
                  <input type="checkbox" name="evalFlavorChecklistDesc[]" id="evalFlavorMaltDesc2" value="Caramel Malt" <?php if ($action == "edit") { if (strpos($row_eval['evalFlavorChecklistDesc'],"Caramel Malt") !== false) echo "checked"; } ?>> <?php echo $label_caramel; ?>
              </label>
              </div>
              <div class="checkbox">
              <label>
                  <input type="checkbox" name="evalFlavorChecklistDesc[]" id="evalFlavorMaltDesc3" value="Bready Malt" <?php if ($action == "edit") { if (strpos($row_eval['evalFlavorChecklistDesc'],"Bready Malt") !== false) echo "checked"; } ?>> <?php echo $label_bready; ?>
              </label>
              </div>
              <div class="checkbox">
              <label>
                  <input type="checkbox" name="evalFlavorChecklistDesc[]" id="evalFlavorMaltDesc4" value="Rich Malt" <?php if ($action == "edit") { if (strpos($row_eval['evalFlavorChecklistDesc'],"Rich Malt") !== false) echo "checked"; } ?>> <?php echo $label_rich; ?>
              </label>
              </div>
              <div class="checkbox">
              <label>
                  <input type="checkbox" name="evalFlavorChecklistDesc[]" id="evalFlavorMaltDesc5" value="Dark Fruit Malt" <?php if ($action == "edit") { if (strpos($row_eval['evalFlavorChecklistDesc'],"Dark Fruit Malt") !== false) echo "checked"; } ?>> <?php echo $label_dark_fruit; ?>
              </label>
              </div>
              <div class="checkbox">
              <label>
                  <input type="checkbox" name="evalFlavorChecklistDesc[]" id="evalFlavorMaltDesc6" value="Toasty Malt" <?php if ($action == "edit") { if (strpos($row_eval['evalFlavorChecklistDesc'],"Toasty Malt") !== false) echo "checked"; } ?>> <?php echo $label_toasty; ?>
              </label>
              </div>
              <div class="checkbox">
              <label>
                  <input type="checkbox" name="evalFlavorChecklistDesc[]" id="evalFlavorMaltDesc7" value="Roasty Malt" <?php if ($action == "edit") { if (strpos($row_eval['evalFlavorChecklistDesc'],"Roasty Malt") !== false) echo "checked"; } ?>> <?php echo $label_roasty; ?>
              </label>
              </div>
              <div class="checkbox">
              <label>
                  <input type="checkbox" name="evalFlavorChecklistDesc[]" id="evalFlavorMaltDesc8" value="Burnt Malt" <?php if ($action == "edit") { if (strpos($row_eval['evalFlavorChecklistDesc'],"Burnt Malt") !== false) echo "checked"; } ?>> <?php echo $label_burnt; ?>
              </label>
              </div>
        </div>
      </div>
      <div class="col col-lg-2 col-md-2 col-sm-4 col-xs-6">
        <div class="form-group">
          <label for="flavorHops"><?php echo $label_hops; ?></label>
              <div class="checkbox">
              <label>
                  <input type="checkbox" name="evalFlavorChecklistDesc[]" id="evalFlavorHopsDesc1" value="Citrusy Hops" <?php if ($action == "edit") { if (strpos($row_eval['evalFlavorChecklistDesc'],"Citrusy Hops") !== false) echo "checked"; } ?>> <?php echo $label_citrusy; ?>
              </label>
              </div>
              <div class="checkbox">
              <label>
                  <input type="checkbox" name="evalFlavorChecklistDesc[]" id="evalFlavorHopsDesc2" value="Earthy Hops" <?php if ($action == "edit") { if (strpos($row_eval['evalFlavorChecklistDesc'],"Earthy Hops") !== false) echo "checked"; } ?>> <?php echo $label_earthy; ?>
              </label>
              </div>
              <div class="checkbox">
              <label>
                  <input type="checkbox" name="evalFlavorChecklistDesc[]" id="evalFlavorHopsDesc3" value="Floral Hops" <?php if ($action == "edit") { if (strpos($row_eval['evalFlavorChecklistDesc'],"Floral Hops") !== false) echo "checked"; } ?>> <?php echo $label_floral; ?>
              </label>
              </div>
              <div class="checkbox">
              <label>
                  <input type="checkbox" name="evalFlavorChecklistDesc[]" id="evalFlavorHopsDesc4" value="Grassy Hops" <?php if ($action == "edit") { if (strpos($row_eval['evalFlavorChecklistDesc'],"Grassy Hops") !== false) echo "checked"; } ?>> <?php echo $label_grassy; ?>
              </label>
              </div>
              <div class="checkbox">
              <label>
                  <input type="checkbox" name="evalFlavorChecklistDesc[]" id="evalFlavorHopsDesc5" value="Herbal Hops" <?php if ($action == "edit") { if (strpos($row_eval['evalFlavorChecklistDesc'],"Herbal Hops") !== false) echo "checked"; } ?>> <?php echo $label_herbal; ?>
              </label>
              </div>
              <div class="checkbox">
              <label>
                  <input type="checkbox" name="evalFlavorChecklistDesc[]" id="evalFlavorHopsDesc6" value="Piney Hops" <?php if ($action == "edit") { if (strpos($row_eval['evalFlavorChecklistDesc'],"Piney Hops") !== false) echo "checked"; } ?>> <?php echo $label_piney; ?>
              </label>
              </div>
              <div class="checkbox">
              <label>
                  <input type="checkbox" name="evalFlavorChecklistDesc[]" id="evalFlavorHopsDesc7" value="Spicy Hops" <?php if ($action == "edit") { if (strpos($row_eval['evalFlavorChecklistDesc'],"Spicy Hops") !== false) echo "checked"; } ?>> <?php echo $label_spicy; ?>
              </label>
              </div>
              <div class="checkbox">
              <label>
                  <input type="checkbox" name="evalFlavorChecklistDesc[]" id="evalFlavorHopsDesc8" value="Woody Hops" <?php if ($action == "edit") { if (strpos($row_eval['evalFlavorChecklistDesc'],"Woody Hops") !== false) echo "checked"; } ?>> <?php echo $label_woody; ?>
              </label>
              </div>
        </div>
      </div>
      <div class="col col-lg-2 col-md-2 col-sm-4 col-xs-6">
        <div class="form-group">
          <label for="flavorEsters"><?php echo $label_esters; ?></label>
              <div class="checkbox">
              <label>
                  <input type="checkbox" name="evalFlavorChecklistDesc[]" id="evalFlavorEstersDesc1" value="Fruity Esters" <?php if ($action == "edit") { if (strpos($row_eval['evalFlavorChecklistDesc'],"Fruity Esters") !== false) echo "checked"; } ?>> <?php echo $label_fruity; ?>
              </label>
              </div>
              <div class="checkbox">
              <label>
                  <input type="checkbox" name="evalFlavorChecklistDesc[]" id="evalFlavorEstersDesc2" value="Apple/Pear Esters" <?php if ($action == "edit") { if (strpos($row_eval['evalFlavorChecklistDesc'],"Apple/Pear Esters") !== false) echo "checked"; } ?>> <?php echo $label_apple_pear; ?>
              </label>
              </div>
              <div class="checkbox">
              <label>
                  <input type="checkbox" name="evalFlavorChecklistDesc[]" id="evalFlavorEstersDesc3" value="Banana Esters" <?php if ($action == "edit") { if (strpos($row_eval['evalFlavorChecklistDesc'],"Banana Esters") !== false) echo "checked"; } ?>> <?php echo $label_banana; ?>
              </label>
              </div>
              <div class="checkbox">
              <label>
                  <input type="checkbox" name="evalFlavorChecklistDesc[]" id="evalFlavorEstersDesc4" value="Berry Esters" <?php if ($action == "edit") { if (strpos($row_eval['evalFlavorChecklistDesc'],"Berry Esters") !== false) echo "checked"; } ?>> <?php echo $label_berry; ?>
              </label>
              </div>
              <div class="checkbox">
              <label>
                  <input type="checkbox" name="evalFlavorChecklistDesc[]" id="evalFlavorEstersDesc5" value="Citrus Esters" <?php if ($action == "edit") { if (strpos($row_eval['evalFlavorChecklistDesc'],"Citrus Esters") !== false) echo "checked"; } ?>> <?php echo $label_citrus; ?>
              </label>
              </div>
              <div class="checkbox">
              <label>
                  <input type="checkbox" name="evalFlavorChecklistDesc[]" id="evalFlavorEstersDesc6" value="Dried Fruit Esters" <?php if ($action == "edit") { if (strpos($row_eval['evalFlavorChecklistDesc'],"Dried Fruit Esters") !== false) echo "checked"; } ?>> <?php echo $label_dried_fruit; ?>
              </label>
              </div>
              <div class="checkbox">
              <label>
                  <input type="checkbox" name="evalFlavorChecklistDesc[]" id="evalFlavorEstersDesc7" value="Grape Esters" <?php if ($action == "edit") { if (strpos($row_eval['evalFlavorChecklistDesc'],"Grape Esters") !== false) echo "checked"; } ?>> <?php echo $label_grape; ?>
              </label>
              </div>
              <div class="checkbox">
              <label>
                  <input type="checkbox" name="evalFlavorChecklistDesc[]" id="evalFlavorEstersDesc8" value="Stone Fruit Esters" <?php if ($action == "edit") { if (strpos($row_eval['evalFlavorChecklistDesc'],"Stone Fruit Esters") !== false) echo "checked"; } ?>> <?php echo $label_stone_fruit; ?>
              </label>
              </div>
          </div>
      </div>
      <div class="col col-lg-2 col-md-2 col-sm-4 col-xs-6">
        <div class="form-group">
          <label for="flavorOther"><?php echo $label_other; ?></label>
              <div class="checkbox">
              <label>
                  <input type="checkbox" name="evalFlavorChecklistDesc[]" id="evalFlavorOtherDesc1" value="Brett" <?php if ($action == "edit") { if (strpos($row_eval['evalFlavorChecklistDesc'],"Brett") !== false) echo "checked"; } ?>> <?php echo $label_brettanomyces; ?>
              </label>
              </div>
              <div class="checkbox">
              <label>
                  <input type="checkbox" name="evalFlavorChecklistDesc[]" id="evalFlavorOtherDesc2" value="Fruit" <?php if ($action == "edit") { if (strpos($row_eval['evalFlavorChecklistDesc'],"Fruit") !== false) echo "checked"; } ?>> <?php echo $label_fruit; ?>
              </label>
              </div>
              <div class="checkbox">
              <label>
                  <input type="checkbox" name="evalFlavorChecklistDesc[]" id="evalFlavorOtherDesc3" value="Lactic" <?php if ($action == "edit") { if (strpos($row_eval['evalFlavorChecklistDesc'],"Lactic") !== false) echo "checked"; } ?>> <?php echo $label_lactic; ?>
              </label>
              </div>
              <div class="checkbox">
              <label>
                  <input type="checkbox" name="evalFlavorChecklistDesc[]" id="evalFlavorOtherDesc4" value="Smoke" <?php if ($action == "edit") { if (strpos($row_eval['evalFlavorChecklistDesc'],"Smoke") !== false) echo "checked"; } ?>> <?php echo $label_smoke; ?>
              </label>
              </div>
              <div class="checkbox">
              <label>
                  <input type="checkbox" name="evalFlavorChecklistDesc[]" id="evalFlavorOtherDesc5" value="Spice" <?php if ($action == "edit") { if (strpos($row_eval['evalFlavorChecklistDesc'],"Spice") !== false) echo "checked"; } ?>> <?php echo $label_spice; ?>
              </label>
              </div>
              <div class="checkbox">
              <label>
                  <input type="checkbox" name="evalFlavorChecklistDesc[]" id="evalFlavorOtherDesc6" value="Vinous" <?php if ($action == "edit") { if (strpos($row_eval['evalFlavorChecklistDesc'],"Vinous") !== false) echo "checked"; } ?>> <?php echo $label_vinous; ?>
              </label>
              </div>
              <div class="checkbox">
              <label>
                  <input type="checkbox" name="evalFlavorChecklistDesc[]" id="evalFlavorOtherDesc7" value="Wood" <?php if ($action == "edit") { if (strpos($row_eval['evalFlavorChecklistDesc'],"Wood") !== false) echo "checked"; } ?>> <?php echo $label_wood; ?>
              </label>
              </div>
          </div>
      </div>
      <div class="col col-lg-2 col-md-2 col-sm-4 col-xs-6">
        <div class="form-group">
        <label for="flavorBalance"><?php echo $label_balance; ?></label>
            <div class="checkbox">
            <label>
                <input type="checkbox" name="evalFlavorChecklistDesc[]" id="evalFlavorBalanceDesc1" value="Malty Balance" <?php if ($action == "edit") { if (strpos($row_eval['evalFlavorChecklistDesc'],"Malty Balance") !== false) echo "checked"; } ?>> <?php echo $label_malty; ?>
            </label>
            </div>
            <div class="checkbox">
            <label>
                <input type="checkbox" name="evalFlavorChecklistDesc[]" id="evalFlavorBalanceDesc2" value="Hoppy Balance" <?php if ($action == "edit") { if (strpos($row_eval['evalFlavorChecklistDesc'],"Hoppy Balance") !== false) echo "checked"; } ?>> <?php echo $label_hoppy; ?>
            </label>
            </div>
            <div class="checkbox">
            <label>
                <input type="checkbox" name="evalFlavorChecklistDesc[]" id="evalFlavorBalanceDesc3" value="Even Balance" <?php if ($action == "edit") { if (strpos($row_eval['evalFlavorChecklistDesc'],"Even Balance") !== false) echo "checked"; } ?>> <?php echo $label_even; ?>
            </label>
            </div>
        </div>
      </div>
    </div><!-- ./row -->
  	</div>
</div>
<!-- Mouthfeel -->
<h3 class="section-heading"><?php echo $label_mouthfeel; ?></h3>
<!-- Mouthfeel Checklist -->
<div class="form-horizontal">
  <div class="form-group">
    <label class="col-sm-3 control-label"><?php echo $label_body; ?></label>
    <div class="col-sm-9">
    	<label class="radio-inline">
          	<input type="radio" name="evalMouthfeelBody" id="evalMouthfeelBody1" value="No Body" data-error="<?php echo $evaluation_info_062; ?>" required <?php if ($action == "edit") { if (strpos($row_eval['evalMouthfeelChecklist'],"No Body") !== false) echo "checked"; } ?>> <?php echo $label_none; ?>
        </label>
        <label class="radio-inline">
          	<input type="radio" name="evalMouthfeelBody" id="evalMouthfeelBody2" value="Low Body" data-error="<?php echo $evaluation_info_062; ?>" required <?php if ($action == "edit") { if (strpos($row_eval['evalMouthfeelChecklist'],"Low Body") !== false) echo "checked"; } ?>> <?php echo $label_low; ?>
        </label>
        <label class="radio-inline">
          	<input type="radio" name="evalMouthfeelBody" id="mevalMouthfeelBody3" value="Medium Body" data-error="<?php echo $evaluation_info_062; ?>" required <?php if ($action == "edit") { if (strpos($row_eval['evalMouthfeelChecklist'],"Medium Body") !== false) echo "checked"; } ?>> <?php echo $label_med; ?>
        </label>
        <label class="radio-inline">
          	<input type="radio" name="evalMouthfeelBody" id="evalMouthfeelBody4" value="High Body" data-error="<?php echo $evaluation_info_062; ?>" required <?php if ($action == "edit") { if (strpos($row_eval['evalMouthfeelChecklist'],"High Body") !== false) echo "checked"; } ?>> <?php echo $label_high; ?>
        </label>
        <div class="help-block small with-errors"></div>
  	</div>
  </div>
  <div class="form-group">
    <label class="col-sm-3 control-label"><?php echo $label_carbonation; ?></label>
    <div class="col-sm-9">
    	<label class="radio-inline">
          	<input type="radio" name="evalMouthfeelCarbonation" id="evalMouthfeelCarbonation1" value="No Carbonation" data-error="<?php echo $evaluation_info_062; ?>" required <?php if ($action == "edit") { if (strpos($row_eval['evalMouthfeelChecklist'],"No Carbonation") !== false) echo "checked"; } ?>> <?php echo $label_none; ?>
        </label>
        <label class="radio-inline">
          	<input type="radio" name="evalMouthfeelCarbonation" id="evalMouthfeelCarbonation2" value="Low Carbonation" data-error="<?php echo $evaluation_info_062; ?>" required <?php if ($action == "edit") { if (strpos($row_eval['evalMouthfeelChecklist'],"Low Carbonation") !== false) echo "checked"; } ?>> <?php echo $label_low; ?>
        </label>
        <label class="radio-inline">
          	<input type="radio" name="evalMouthfeelCarbonation" id="evalMouthfeelCarbonation3" value="Medium Carbonation" data-error="<?php echo $evaluation_info_062; ?>" required <?php if ($action == "edit") { if (strpos($row_eval['evalMouthfeelChecklist'],"Medium Carbonation") !== false) echo "checked"; } ?>> <?php echo $label_med; ?>
        </label>
        <label class="radio-inline">
          	<input type="radio" name="evalMouthfeelCarbonation" id="evalMouthfeelCarbonation4" value="High Carbonation" data-error="<?php echo $evaluation_info_062; ?>" required <?php if ($action == "edit") { if (strpos($row_eval['evalMouthfeelChecklist'],"High Carbonation") !== false) echo "checked"; } ?>> <?php echo $label_high; ?>
        </label>
        <div class="help-block small with-errors"></div>
  	</div>
  </div>
  <div class="form-group">
    <label class="col-sm-3 control-label"><?php echo $label_warmth; ?></label>
    <div class="col-sm-9">
    	<label class="radio-inline">
          	<input type="radio" name="evalMouthfeelWarmth" id="evalMouthfeelWarmth1" value="No Warmth" data-error="<?php echo $evaluation_info_062; ?>" required <?php if ($action == "edit") { if (strpos($row_eval['evalMouthfeelChecklist'],"No Warmth") !== false) echo "checked"; } ?>> <?php echo $label_none; ?>
        </label>
        <label class="radio-inline">
          	<input type="radio" name="evalMouthfeelWarmth" id="evalMouthfeelWarmth2" value="Low Warmth" data-error="<?php echo $evaluation_info_062; ?>" required <?php if ($action == "edit") { if (strpos($row_eval['evalMouthfeelChecklist'],"Low Warmth") !== false) echo "checked"; } ?>> <?php echo $label_low; ?>
        </label>
        <label class="radio-inline">
          	<input type="radio" name="evalMouthfeelWarmth" id="evalMouthfeelWarmth3" value="Medium Warmth" data-error="<?php echo $evaluation_info_062; ?>" required <?php if ($action == "edit") { if (strpos($row_eval['evalMouthfeelChecklist'],"Medium Warmth") !== false) echo "checked"; } ?>> <?php echo $label_med; ?>
        </label>
        <label class="radio-inline">
          	<input type="radio" name="evalMouthfeelWarmth" id="evalMouthfeelWarmth3" value="High Warmth" data-error="Please choose a warmth descriptor." required <?php if ($action == "edit") { if (strpos($row_eval['evalMouthfeelChecklist'],"High Warmth") !== false) echo "checked"; } ?>> <?php echo $label_high; ?>
        </label>
        <div class="help-block small with-errors"></div>
  	</div>
  </div>
  <div class="form-group">
    <label class="col-sm-3 control-label"><?php echo $label_creaminess; ?></label>
    <div class="col-sm-9">
    	<label class="radio-inline">
          	<input type="radio" name="evalMouthfeelCreaminess" id="evalMouthfeelCreaminess1" value="No Creaminess" data-error="<?php echo $evaluation_info_062; ?>" required <?php if ($action == "edit") { if (strpos($row_eval['evalMouthfeelChecklist'],"No Creaminess") !== false) echo "checked"; } ?>> <?php echo $label_none; ?>
        </label>
        <label class="radio-inline">
          	<input type="radio" name="evalMouthfeelCreaminess" id="evalMouthfeelCreaminess2" value="Low Creaminess" data-error="<?php echo $evaluation_info_062; ?>" required <?php if ($action == "edit") { if (strpos($row_eval['evalMouthfeelChecklist'],"Low Creaminess") !== false) echo "checked"; } ?>> <?php echo $label_low; ?>
        </label>
        <label class="radio-inline">
          	<input type="radio" name="evalMouthfeelCreaminess" id="evalMouthfeelCreaminess3" value="Medium Creaminess" data-error="<?php echo $evaluation_info_062; ?>" required <?php if ($action == "edit") { if (strpos($row_eval['evalMouthfeelChecklist'],"Medium Creaminess") !== false) echo "checked"; } ?>> <?php echo $label_med; ?>
        </label>
        <label class="radio-inline">
          	<input type="radio" name="evalMouthfeelCreaminess" id="evalMouthfeelCreaminess4" value="High Creaminess" data-error="<?php echo $evaluation_info_062; ?>" required <?php if ($action == "edit") { if (strpos($row_eval['evalMouthfeelChecklist'],"High Creaminess") !== false) echo "checked"; } ?>> <?php echo $label_high; ?>
        </label>
        <div class="help-block small with-errors"></div>
  	</div>
  </div>
  <div class="form-group">
    <label class="col-sm-3 control-label"><?php echo $label_astringency; ?></label>
    <div class="col-sm-9">
    	<label class="radio-inline">
          	<input type="radio" name="evalMouthfeelAstringency" id="evalMouthfeelAstringency1" value="No Astringency" data-error="<?php echo $evaluation_info_062; ?>" required <?php if ($action == "edit") { if (strpos($row_eval['evalMouthfeelChecklist'],"No Astringency") !== false) echo "checked"; } ?>> <?php echo $label_none; ?>
        </label>
        <label class="radio-inline">
          	<input type="radio" name="evalMouthfeelAstringency" id="evalMouthfeelAstringency2" value="Low Astringency" data-error="<?php echo $evaluation_info_062; ?>" required <?php if ($action == "edit") { if (strpos($row_eval['evalMouthfeelChecklist'],"Low Astringency") !== false) echo "checked"; } ?>> <?php echo $label_low; ?>
        </label>
        <label class="radio-inline">
          	<input type="radio" name="evalMouthfeelAstringency" id="evalMouthfeelAstringency3" value="Medium Astringency" data-error="<?php echo $evaluation_info_062; ?>" required <?php if ($action == "edit") { if (strpos($row_eval['evalMouthfeelChecklist'],"Medium Astringency") !== false) echo "checked"; } ?>> <?php echo $label_med; ?>
        </label>
        <label class="radio-inline">
          	<input type="radio" name="evalMouthfeelAstringency" id="evalMouthfeelAstringency4" value="High Astringency" data-error="<?php echo $evaluation_info_062; ?>" required <?php if ($action == "edit") { if (strpos($row_eval['evalMouthfeelChecklist'],"High Astringency") !== false) echo "checked"; } ?>> <?php echo $label_high; ?>
        </label>
        <div class="help-block small with-errors"></div>
  	</div>
  </div>
</div>
<!-- Mouthfeel Score -->
<div class="form-group">
    <label for="evalMouthfeelScore"><?php echo sprintf("%s: %s (%s %s)",$label_mouthfeel,$label_score,$mouthfeel_points,$label_possible_points); ?></label>
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
<div class="form-group">
    <label for="evalMouthfeelComments"><?php echo sprintf("%s: %s",$label_mouthfeel,$label_comments); ?></label>
    <textarea class="form-control" name="evalMouthfeelComments" rows="6" placeholder="<?php echo "450 ".$evaluation_info_060; ?>" maxlength="450" data-error="<?php echo $evaluation_info_062; ?>" required><?php if ($action == "edit") echo $row_eval['evalMouthfeelComments']; ?></textarea>
    <div class="help-block small with-errors"></div>
</div>
<!-- Mouthfeel Descriptors 
<div class="bcoem-admin-element">
	<button class="btn btn-info" type="button" data-toggle="collapse" data-target="#mouthfeelDescriptors" aria-expanded="false" aria-controls="mouthfeelDescriptors">Mouthfeel Descriptors (Select to Expand/Collapse)</button>
</div>
-->
<p><strong><?php echo sprintf("%s: %s",$label_mouthfeel,$label_descriptors); ?></strong></p>
<div class="collapse in bcoem-admin-element" id="mouthfeelDescriptors">
  <div class="well">
    <div class="row">
      <div class="col-lg-2 col-md-2 col-sm-4 col-xs-6">
        <div class="form-group">
          <label for="mouthfeelFlaws"><?php echo $label_flaws; ?></label>
              <div class="checkbox">
              <label>
                  <input type="checkbox" name="evalMouthfeelChecklistDesc[]" id="evalMouthfeelFlawsDesc1" value="Flat" <?php if ($action == "edit") { if (strpos($row_eval['evalMouthfeelChecklistDesc'],"Flat") !== false) echo "checked"; } ?>> <?php echo $label_flat; ?>
              </label>
              </div>
              <div class="checkbox">
              <label>
                  <input type="checkbox" name="evalMouthfeelChecklistDesc[]" id="evalMouthfeelFlawsDesc2" value="Gushed" <?php if ($action == "edit") { if (strpos($row_eval['evalMouthfeelChecklistDesc'],"Gushed") !== false) echo "checked"; } ?>> <?php echo $label_gushed; ?>
              </label>
              </div>
              <div class="checkbox">
              <label>
                  <input type="checkbox" name="evalMouthfeelChecklistDesc[]" id="evalMouthfeelFlawslDesc3" value="Hot" <?php if ($action == "edit") { if (strpos($row_eval['evalMouthfeelChecklistDesc'],"Hot") !== false) echo "checked"; } ?>> <?php echo $label_hot; ?>
              </label>
              </div>
              <div class="checkbox">
              <label>
                  <input type="checkbox" name="evalMouthfeelChecklistDesc[]" id="evalMouthfeelFlawsDesc4" value="Harsh" <?php if ($action == "edit") { if (strpos($row_eval['evalMouthfeelChecklistDesc'],"Harsh") !== false) echo "checked"; } ?>> <?php echo $label_harsh; ?>
              </label>
              </div>
              <div class="checkbox">
              <label>
                  <input type="checkbox" name="evalMouthfeelChecklistDesc[]" id="evalMouthfeelFlawsDesc5" value="Slick" <?php if ($action == "edit") { if (strpos($row_eval['evalMouthfeelChecklistDesc'],"Slick") !== false) echo "checked"; } ?>> <?php echo $label_slick; ?>
              </label>
              </div>
        </div>
      </div>
      <div class="col col-lg-2 col-md-2 col-sm-4 col-xs-6">
        <div class="form-group">
          <label for="mouthfeelFinish"><?php echo $label_finish; ?></label>
              <div class="checkbox">
              <label>
                  <input type="checkbox" name="evalMouthfeelChecklistDesc[]" id="evalMouthfeelFinishDesc1" value="Cloying Finish" <?php if ($action == "edit") { if (strpos($row_eval['evalMouthfeelChecklistDesc'],"Cloying Finish") !== false) echo "checked"; } ?>> <?php echo $label_cloying; ?>
              </label>
              </div>
              <div class="checkbox">
              <label>
                  <input type="checkbox" name="evalMouthfeelChecklistDesc[]" id="evalMouthfeelFinishDesc2" value="Sweet Finish" <?php if ($action == "edit") { if (strpos($row_eval['evalMouthfeelChecklistDesc'],"Sweet Finish") !== false) echo "checked"; } ?>> <?php echo $label_sweet; ?>
              </label>
              </div>
              <div class="checkbox">
              <label>
                  <input type="checkbox" name="evalMouthfeelChecklistDesc[]" id="evalMouthfeelFinishDesc3" value="Medium Finish" <?php if ($action == "edit") { if (strpos($row_eval['evalMouthfeelChecklistDesc'],"Medium Finish") !== false) echo "checked"; } ?>> <?php echo $label_med; ?>
              </label>
              </div>
              <div class="checkbox">
              <label>
                  <input type="checkbox" name="evalMouthfeelChecklistDesc[]" id="evalMouthfeelFinishDesc4" value="Dry Finish" <?php if ($action == "edit") { if (strpos($row_eval['evalMouthfeelChecklistDesc'],"Dry Finish") !== false) echo "checked"; } ?>> <?php echo $label_dry; ?>
              </label>
              </div>
              <div class="checkbox">
              <label>
                  <input type="checkbox" name="evalMouthfeelChecklistDesc[]" id="evalMouthfeelFinishDesc5" value="Biting Finish" <?php if ($action == "edit") { if (strpos($row_eval['evalMouthfeelChecklistDesc'],"Biting Finish") !== false) echo "checked"; } ?>> <?php echo $label_biting; ?>
              </label>
              </div>
        </div>
      </div>
    </div>
  	</div>
</div>
<!-- Overall Impression -->
<h3 class="section-heading"><?php echo $label_overall_impression; ?></h3>
<div class="form-group">
    <label for="evalOverallScore"><?php echo sprintf("%s: %s (%s %s)",$label_overall_impression,$label_score,$overall_points,$label_possible_points); ?></label>
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

<!-- Style Accuracy -->
    <div class="form-group">
        <div class="row">
          <div class="col-sm-3 col-xs-12">
            <label for="evalStyleAccuracy"><?php echo $label_style_accuracy; ?></label>
          </div>
          <div class="col-sm-9 col-xs-12">
            <input class="form-control" type="text" name="evalStyleAccuracy" data-provide="slider" data-slider-ticks="[1, 2, 3, 4, 5]" data-slider-ticks-labels='["<?php echo $label_low; ?>", "", "<?php echo $label_med; ?>", "", "<?php echo $label_high; ?>"]' data-slider-min="1" data-slider-max="5" data-slider-step="1" data-slider-value="<?php if ($action == "edit") echo $row_eval['evalStyleAccuracy']; ?>" data-slider-tooltip="show">
          </div>
        </div>
        <div class="help-block small with-errors"></div>
  	</div>

<!-- Technical Merit -->
    <div class="form-group">
        <div class="row">
          <div class="col-sm-3 col-xs-12">
            <label for="evalTechMerit"><?php echo $label_tech_merit; ?></label>
          </div>
          <div class="col-sm-9 col-xs-12">
            <input class="form-control" type="text" name="evalTechMerit" data-provide="slider" data-slider-ticks="[1, 2, 3, 4, 5]" data-slider-ticks-labels='["<?php echo $label_low; ?>", "", "<?php echo $label_med; ?>", "", "<?php echo $label_high; ?>"]' data-slider-min="1" data-slider-max="5" data-slider-step="1" data-slider-value="<?php if ($action == "edit") echo $row_eval['evalTechMerit']; ?>" data-slider-tooltip="show">
          </div>
        </div>
        <div class="help-block small with-errors"></div>
    </div>

 <!-- Intangibles -->
<div class="form-group">
    <div class="row">
      <div class="col-sm-3 col-xs-12">
        <label for="evalIntangibles"><?php echo $label_intangibles; ?></label>
      </div>
      <div class="col-sm-9 col-xs-12">
        <input class="form-control" type="text" name="evalIntangibles" data-provide="slider" data-slider-ticks="[1, 2, 3, 4, 5]" data-slider-ticks-labels='["<?php echo $label_low; ?>", "", "<?php echo $label_med; ?>", "", "<?php echo $label_high; ?>"]' data-slider-min="1" data-slider-max="5" data-slider-step="1" data-slider-value="<?php if ($action == "edit") echo $row_eval['evalIntangibles']; ?>" data-slider-tooltip="show">
      </div>
    </div>
    <div class="help-block small with-errors"></div>
</div>

<!-- Drinkability -->
<div class="form-group">
    <div class="row">
      <div class="col-sm-3 col-xs-12">
        <label for="evalDrinkability"><?php echo $label_drinkability; ?></label>
      </div>
      <div class="col-sm-9 col-xs-12">
        <div class="radio">
            <label>
            </label>
        </div>
        <div class="radio">
            <label>
                <input type="radio" name="evalDrinkability" id="evalDrinkability1" data-error="<?php echo $evaluation_info_062; ?>" value="I would finish this sample." required <?php if (($action == "edit") && ($row_eval['evalDrinkability'] == "I would finish this sample.")) echo "checked"; ?>> <?php echo $evaluation_info_063; ?>
            </label>
        </div>
        <div class="radio">
            <label>
                <input type="radio" name="evalDrinkability" id="evalDrinkability2" data-error="<?php echo $evaluation_info_062; ?>" value="I would drink a pint of this beer." required <?php if (($action == "edit") && ($row_eval['evalDrinkability'] == "I would drink a pint of this beer.")) echo "checked"; ?>> <?php echo $evaluation_info_064; ?>
            </label>
        </div>
        <div class="radio">
            <label>
                <input type="radio" name="evalDrinkability" id="evalDrinkability3" data-error="<?php echo $evaluation_info_062; ?>" value="I would pay money for this beer." required <?php if (($action == "edit") && ($row_eval['evalDrinkability'] == "I would pay money for this beer.")) echo "checked"; ?>> <?php echo $evaluation_info_065; ?>
            </label>
        </div>
        <div class="radio">
            <label>
                <input type="radio" name="evalDrinkability" id="evalDrinkability4" data-error="<?php echo $evaluation_info_062; ?>" value="I would recommend this beer." required <?php if (($action == "edit") && ($row_eval['evalDrinkability'] == "I would recommend this beer.")) echo "checked"; ?>> <?php echo $evaluation_info_066; ?>
            </label>
        </div>
        <div class="help-block small with-errors"></div>
      </div>
    </div>
</div>
<div class="form-group">
    <label for="evalOverallComments"><?php echo sprintf("%s: %s",$label_overall_impression,$label_comments); ?></label>
    <textarea class="form-control" name="evalOverallComments" rows="6" placeholder="<?php echo "800 ".$evaluation_info_060; ?>" maxlength="800" data-error="<?php echo $evaluation_info_061; ?>" required><?php if ($action == "edit") echo $row_eval['evalOverallComments']; ?></textarea>
    <div class="help-block small with-errors"></div>
</div>

<!-- Flaws -->
<h3 class="section-heading"><?php echo $label_flaws; ?></h3>
<?php foreach ($flaws as $flaw) { ?>
<div class="form-group">
  <div class="row">
    <div class="col col-sm-3">
        <label for="evalFlaws"><?php echo $flaw; ?></label>
    </div>
    <div class="col col-sm-3">
        <div class="checkbox">
            <label>
                <input type="checkbox" name="evalFlaws[]" id="evalFlaws<?php echo $flaw; ?>" value="<?php echo $flaw; ?> Aroma" <?php if ($action == "edit") { if (strpos($row_eval['evalFlaws'],$flaw." Aroma") !== false) echo "checked"; } ?>> <?php echo $label_aroma; ?>
            </label>
        </div>
    </div>
    <div class="col col-sm-3">
        <div class="checkbox">
            <label>
                <input type="checkbox" name="evalFlaws[]" id="evalFlaws<?php echo $flaw; ?>" value="<?php echo $flaw; ?> Flavor" <?php if ($action == "edit") { if (strpos($row_eval['evalFlaws'],$flaw." Flavor") !== false) echo "checked"; } ?>> <?php echo $label_flavor; ?>
            </label>
        </div>
    </div>
    <div class="col col-sm-3">
        <?php if (in_array($flaw, $flaws_mouthfeel)) { ?>
        <div class="checkbox">
            <label>
                <input type="checkbox" name="evalFlaws[]" id="evalFlaws<?php echo $flaw; ?>" value="<?php echo $flaw; ?> Mouthfeel" <?php if ($action == "edit") { if (strpos($row_eval['evalFlaws'],$flaw." Mouthfeel") !== false) echo "checked"; } ?>> <?php echo $label_mouthfeel; ?>
            </label>
        </div>
      <?php } ?>
    </div>
  </div>
</div>
<?php } ?>