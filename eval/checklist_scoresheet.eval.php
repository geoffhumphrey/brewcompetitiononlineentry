<!-- Checklist Evaluation Form -->
<input type="hidden" name="evalFormType" value="2">
<!-- Aroma Checklist -->
<h3 class="section-heading">Aroma</h3>
<div class="form-horizontal">
  <div class="form-group">
    <label class="col-sm-3 control-label">Malt</label>
    <div class="col-sm-9">
    	<label class="radio-inline">
          	<input type="radio" name="evalAromaMalt" id="evalAromaMalt1" value="No Malt" data-error="Please choose a malt aroma descriptor." required <?php if ($action == "edit") { if (strpos($row_eval['evalAromaChecklist'],"No Malt") !== false) echo "checked"; } ?>> None
        </label>
        <label class="radio-inline">
          	<input type="radio" name="evalAromaMalt" id="evalAromaMalt2" value="Low Malt" data-error="Please choose a malt aroma descriptor." required <?php if ($action == "edit") { if (strpos($row_eval['evalAromaChecklist'],"Low Malt") !== false) echo "checked"; } ?>> Low
        </label>
        <label class="radio-inline">
          	<input type="radio" name="evalAromaMalt" id="evalAromaMalt3" value="Medium Malt" data-error="Please choose a malt aroma descriptor." required <?php if ($action == "edit") { if (strpos($row_eval['evalAromaChecklist'],"Medium Malt") !== false) echo "checked"; } ?>> Medium
        </label>
        <label class="radio-inline">
          	<input type="radio" name="evalAromaMalt" id="evalAromaMalt4" value="High Malt" data-error="Please choose a malt aroma descriptor." required <?php if ($action == "edit") { if (strpos($row_eval['evalAromaChecklist'],"High Malt") !== false) echo "checked"; } ?>> High
        </label>
        <div class="help-block small with-errors"></div>
  	</div>
  </div>
  <div class="form-group">
    <label class="col-sm-3 control-label">Hops</label>
    <div class="col-sm-9">
    	<label class="radio-inline">
          	<input type="radio" name="evalAromaHops" id="evalAromaHops1" value="No Hops" data-error="Please choose a hop aroma descriptor." required <?php if ($action == "edit") { if (strpos($row_eval['evalAromaChecklist'],"No Hops") !== false) echo "checked"; } ?>> None
        </label>
        <label class="radio-inline">
          	<input type="radio" name="evalAromaHops" id="evalAromaHops2" value="Low Hops" data-error="Please choose a hop aroma descriptor." required <?php if ($action == "edit") { if (strpos($row_eval['evalAromaChecklist'],"Low Hops") !== false) echo "checked"; } ?>> Low
        </label>
        <label class="radio-inline">
          	<input type="radio" name="evalAromaHops" id="evalAromaHops3" value="Medium Hops" data-error="Please choose a hop aroma descriptor." required <?php if ($action == "edit") { if (strpos($row_eval['evalAromaChecklist'],"Medium Hops") !== false) echo "checked"; } ?>> Medium
        </label>
        <label class="radio-inline">
          	<input type="radio" name="evalAromaHops" id="evalAromaHops4" value="High Hops" data-error="Please choose a hop aroma descriptor." required <?php if ($action == "edit") { if (strpos($row_eval['evalAromaChecklist'],"High Hops") !== false) echo "checked"; } ?>> High
        </label>
        <div class="help-block small with-errors"></div>
  	</div>
  </div>
  <div class="form-group">
    <label class="col-sm-3 control-label">Esters</label>
    <div class="col-sm-9">
    	<label class="radio-inline">
          	<input type="radio" name="evalAromaEsters" id="evalAromaEsters1" value="No Esters" data-error="Please choose an ester aroma descriptor." required <?php if ($action == "edit") { if (strpos($row_eval['evalAromaChecklist'],"No Esters") !== false) echo "checked"; } ?>> None
        </label>
        <label class="radio-inline">
          	<input type="radio" name="evalAromaEsters" id="evalAromaEsters2" value="Low Esters" data-error="Please choose an ester aroma descriptor." required <?php if ($action == "edit") { if (strpos($row_eval['evalAromaChecklist'],"Low Esters") !== false) echo "checked"; } ?>> Low
        </label>
        <label class="radio-inline">
          	<input type="radio" name="evalAromaEsters" id="evalAromaEsters3" value="Medium Esters" data-error="Please choose an ester aroma descriptor." required <?php if ($action == "edit") { if (strpos($row_eval['evalAromaChecklist'],"Medium Esters") !== false) echo "checked"; } ?>> Medium
        </label>
        <label class="radio-inline">
          	<input type="radio" name="evalAromaEsters" id="evalAromaEsters4" value="High Esters" data-error="Please choose an ester aroma descriptor." required <?php if ($action == "edit") { if (strpos($row_eval['evalAromaChecklist'],"High Esters") !== false) echo "checked"; } ?>> High
        </label>
        <div class="help-block small with-errors"></div>
  	</div>
  </div>
  <div class="form-group">
    <label class="col-sm-3 control-label">Phenols</label>
    <div class="col-sm-9">
    	<label class="radio-inline">
          	<input type="radio" name="evalAromaPhenols" id="evalAromaPhenols1" value="No Phenols" data-error="Please choose a phenol aroma descriptor." required  <?php if ($action == "edit") { if (strpos($row_eval['evalAromaChecklist'],"No Phenols") !== false) echo "checked"; } ?>> None
        </label>
        <label class="radio-inline">
          	<input type="radio" name="evalAromaPhenols" id="evalAromaPhenols2" value="Low Phenols" data-error="Please choose a phenol aroma descriptor." required <?php if ($action == "edit") { if (strpos($row_eval['evalAromaChecklist'],"Low Phenols") !== false) echo "checked"; } ?>> Low
        </label>
        <label class="radio-inline">
          	<input type="radio" name="evalAromaPhenols" id="evalAromaPhenols3" value="Medium Phenols" data-error="Please choose a phenol aroma descriptor." required <?php if ($action == "edit") { if (strpos($row_eval['evalAromaChecklist'],"Medium Phenols") !== false) echo "checked"; } ?>> Medium
        </label>
        <label class="radio-inline">
          	<input type="radio" name="evalAromaPhenols" id="evalAromaPhenols4" value="High Phenols" data-error="Please choose a phenol aroma descriptor." required <?php if ($action == "edit") { if (strpos($row_eval['evalAromaChecklist'],"High Phenols") !== false) echo "checked"; } ?>> High
        </label>
        <div class="help-block small with-errors"></div>
  	</div>
  </div>
  <div class="form-group">
    <label class="col-sm-3 control-label">Alcohol</label>
    <div class="col-sm-9">
    	<label class="radio-inline">
          	<input type="radio" name="evalAromaAlcohol" id="evalAromaAlcohol1" value="No Alcohol" data-error="Please choose an alcohol aroma descriptor." required <?php if ($action == "edit") { if (strpos($row_eval['evalAromaChecklist'],"No Alcohol") !== false) echo "checked"; } ?>> None
        </label>
        <label class="radio-inline">
          	<input type="radio" name="evalAromaAlcohol" id="evalAromaAlcohol2" value="Low Alcohol" data-error="Please choose an alcohol aroma descriptor." required <?php if ($action == "edit") { if (strpos($row_eval['evalAromaChecklist'],"Low Alcohol") !== false) echo "checked"; } ?>> Low
        </label>
        <label class="radio-inline">
          	<input type="radio" name="evalAromaAlcohol" id="evalAromaAlcohol3" value="Medium Alcohol" data-error="Please choose an alcohol aroma descriptor." required <?php if ($action == "edit") { if (strpos($row_eval['evalAromaChecklist'],"Medium Alcohol") !== false) echo "checked"; } ?>> Medium
        </label>
        <label class="radio-inline">
          	<input type="radio" name="evalAromaAlcohol" id="evalAromaAlcohol4" value="High Alcohol" data-error="Please choose an alcohol aroma descriptor." required <?php if ($action == "edit") { if (strpos($row_eval['evalAromaChecklist'],"High Alcohol") !== false) echo "checked"; } ?>> High
        </label>
        <div class="help-block small with-errors"></div>
  	</div>
  </div>
  <div class="form-group">
    <label class="col-sm-3 control-label">Sweetness</label>
    <div class="col-sm-9">
    	<label class="radio-inline">
          	<input type="radio" name="evalAromaSweetness" id="evalAromaSweetness1" value="No Sweetness" data-error="Please choose a sweetness aroma descriptor." required <?php if ($action == "edit") { if (strpos($row_eval['evalAromaChecklist'],"No Sweetness") !== false) echo "checked"; } ?>> None
        </label>
        <label class="radio-inline">
          	<input type="radio" name="evalAromaSweetness" id="evalAromaSweetness2" value="Low Sweetness" data-error="Please choose a sweetness aroma descriptor." required <?php if ($action == "edit") { if (strpos($row_eval['evalAromaChecklist'],"Low Sweetness") !== false) echo "checked"; } ?>> Low
        </label>
        <label class="radio-inline">
          	<input type="radio" name="evalAromaSweetness" id="evalAromaSweetness3" value="Medium Sweetness" data-error="Please choose a sweetness aroma descriptor." required <?php if ($action == "edit") { if (strpos($row_eval['evalAromaChecklist'],"Medium Sweetness") !== false) echo "checked"; } ?>> Medium
        </label>
        <label class="radio-inline">
          	<input type="radio" name="evalAromaSweetness" id="evalAromaSweetness4" value="High Sweetness" data-error="Please choose a sweetness aroma descriptor." required <?php if ($action == "edit") { if (strpos($row_eval['evalAromaChecklist'],"High Sweetness") !== false) echo "checked"; } ?>> High
        </label>
        <div class="help-block small with-errors"></div>
  	</div>
  </div>
  <div class="form-group">
    <label class="col-sm-3 control-label">Acidity</label>
    <div class="col-sm-9">
    	<label class="radio-inline">
          	<input type="radio" name="evalAromaAcidity" id="evalAromaAcidity1" value="No Acidity" data-error="Please choose an acidity aroma descriptor." required <?php if ($action == "edit") { if (strpos($row_eval['evalAromaChecklist'],"No Acidity") !== false) echo "checked"; } ?>> None
        </label>
        <label class="radio-inline">
          	<input type="radio" name="evalAromaAcidity" id="evalAromaAcidity2" value="Low Acidity" data-error="Please choose an acidity aroma descriptor." required <?php if ($action == "edit") { if (strpos($row_eval['evalAromaChecklist'],"Low Acidity") !== false) echo "checked"; } ?>> Low
        </label>
        <label class="radio-inline">
          	<input type="radio" name="evalAromaAcidity" id="evalAromaAcidity3" value="Medium Acidity" data-error="Please choose an acidity aroma descriptor." required <?php if ($action == "edit") { if (strpos($row_eval['evalAromaChecklist'],"Medium Acidity") !== false) echo "checked"; } ?>> Medium
        </label>
        <label class="radio-inline">
          	<input type="radio" name="evalAromaAcidity" id="evalAromaAcidity4" value="High Acidity" data-error="Please choose an acidity aroma descriptor." required <?php if ($action == "edit") { if (strpos($row_eval['evalAromaChecklist'],"High Acidity") !== false) echo "checked"; } ?>> High
        </label>
        <div class="help-block small with-errors"></div>
  	</div>
  </div>
</div>
<!-- Aroma Score -->
<div class="form-group">
    <label for="evalAromaScore">Aroma Score (<?php echo $aroma_points; ?> points possible)</label>
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
    <label for="evalAromaComments">Aroma Comments</label>
    <textarea class="form-control" name="evalAromaComments" rows="6" placeholder="Brief comments relating to aroma; 450 characters maximum." maxlength="450" data-error="Please provide some brief aroma comments" required><?php if ($action == "edit") echo $row_eval['evalAromaComments']; ?></textarea>
    <div class="help-block small with-errors"></div>
</div>
<!-- Aroma Descriptors
<div class="bcoem-admin-element">
	<button class="btn btn-info" type="button" data-toggle="collapse" data-target="#evalAromaDescriptors" aria-expanded="false" aria-controls="evalAromaDescriptors">Aroma Descriptors (Select to Expand/Collapse)</button>
</div> -->
<p><strong>Aroma Descriptors</strong></p>
<div class="collapse in" id="evalAromaDescriptors">
  <section class="well">
    <div class="row">
    <div class="col col-lg-2 col-md-2 col-sm-4 col-xs-6">
      <div class="form-group">
        <label for="evalAromaMalt">Malt</label>
            <div class="checkbox">
            <label>
                <input type="checkbox" name="evalAromaChecklistDesc[]" id="evalAromaMaltDesc1" value="Grainy Malt" <?php if ($action == "edit") { if (strpos($row_eval['evalAromaChecklistDesc'],"Grainy Malt") !== false) echo "checked"; } ?>> Grainy
            </label>
            </div>
            <div class="checkbox">
            <label>
                <input type="checkbox" name="evalAromaChecklistDesc[]" id="evalAromaMaltDesc2" value="Caramel Malt" <?php if ($action == "edit") { if (strpos($row_eval['evalAromaChecklistDesc'],"Caramel Malt") !== false) echo "checked"; } ?>> Caramel
            </label>
            </div>
            <div class="checkbox">
            <label>
                <input type="checkbox" name="evalAromaChecklistDesc[]" id="evalAromaMaltDesc3" value="Bready Malt" <?php if ($action == "edit") { if (strpos($row_eval['evalAromaChecklistDesc'],"Bready Malt") !== false) echo "checked"; } ?>> Bready
            </label>
            </div>
            <div class="checkbox">
            <label>
                <input type="checkbox" name="evalAromaChecklistDesc[]" id="evalAromaMaltDesc4" value="Rich Malt" <?php if ($action == "edit") { if (strpos($row_eval['evalAromaChecklistDesc'],"Rich Malt") !== false) echo "checked"; } ?>> Rich
            </label>
            </div>
            <div class="checkbox">
            <label>
                <input type="checkbox" name="evalAromaChecklistDesc[]" id="evalAromaMaltDesc5" value="Dark Fruit Malt" <?php if ($action == "edit") { if (strpos($row_eval['evalAromaChecklistDesc'],"Dark Fruit Malt") !== false) echo "checked"; } ?>> Dark Fruit
            </label>
            </div>
            <div class="checkbox">
            <label>
                <input type="checkbox" name="evalAromaChecklistDesc[]" id="evalAromaMaltDesc6" value="Toasty Malt" <?php if ($action == "edit") { if (strpos($row_eval['evalAromaChecklistDesc'],"Toasty Malt") !== false) echo "checked"; } ?>> Toasty
            </label>
            </div>
            <div class="checkbox">
            <label>
                <input type="checkbox" name="evalAromaChecklistDesc[]" id="evalAromaMaltDesc7" value="Roasty Malt" <?php if ($action == "edit") { if (strpos($row_eval['evalAromaChecklistDesc'],"Roasty Malt") !== false) echo "checked"; } ?>> Roasty
            </label>
            </div>
            <div class="checkbox">
            <label>
                <input type="checkbox" name="evalAromaChecklistDesc[]" id="evalAromaMaltDesc8" value="Burnt Malt" <?php if ($action == "edit") { if (strpos($row_eval['evalAromaChecklistDesc'],"Burnt Malt") !== false) echo "checked"; } ?>> Burnt
            </label>
            </div>
      </div>
    </div>
    <div class="col col-lg-2 col-md-2 col-sm-4 col-xs-6">
      <div class="form-group">
        <label for="evalAromaHops">Hops</label>
            <div class="checkbox">
            <label>
                <input type="checkbox" name="evalAromaChecklistDesc[]" id="evalAromaHopsDesc1" value="Citrusy Hops" <?php if ($action == "edit") { if (strpos($row_eval['evalAromaChecklistDesc'],"Citrusy Hops") !== false) echo "checked"; } ?>> Citrusy
            </label>
            </div>
            <div class="checkbox">
            <label>
                <input type="checkbox" name="evalAromaChecklistDesc[]" id="evalAromaHopsDesc2" value="Earthy Hops" <?php if ($action == "edit") { if (strpos($row_eval['evalAromaChecklistDesc'],"Earthy Hops") !== false) echo "checked"; } ?>> Earthy
            </label>
            </div>
            <div class="checkbox">
            <label>
                <input type="checkbox" name="evalAromaChecklistDesc[]" id="evalAromaHopsDesc3" value="Floral Hops" <?php if ($action == "edit") { if (strpos($row_eval['evalAromaChecklistDesc'],"Floral Hops") !== false) echo "checked"; } ?>> Floral
            </label>
            </div>
            <div class="checkbox">
            <label>
                <input type="checkbox" name="evalAromaChecklistDesc[]" id="evalAromaHopsDesc4" value="Grassy Hops" <?php if ($action == "edit") { if (strpos($row_eval['evalAromaChecklistDesc'],"Grassy Hops") !== false) echo "checked"; } ?>> Grassy
            </label>
            </div>
            <div class="checkbox">
            <label>
                <input type="checkbox" name="evalAromaChecklistDesc[]" id="evalAromaHopsDesc5" value="Herbal Hops" <?php if ($action == "edit") { if (strpos($row_eval['evalAromaChecklistDesc'],"Herbal Hops") !== false) echo "checked"; } ?>> Herbal
            </label>
            </div>
            <div class="checkbox">
            <label>
                <input type="checkbox" name="evalAromaChecklistDesc[]" id="evalAromaHopsDesc6" value="Piney Hops" <?php if ($action == "edit") { if (strpos($row_eval['evalAromaChecklistDesc'],"Piney Hops") !== false) echo "checked"; } ?>> Piney
            </label>
            </div>
            <div class="checkbox">
            <label>
                <input type="checkbox" name="evalAromaChecklistDesc[]" id="evalAromaHopsDesc7" value="Spicy Hops" <?php if ($action == "edit") { if (strpos($row_eval['evalAromaChecklistDesc'],"Spicy Hops") !== false) echo "checked"; } ?>> Spicy
            </label>
            </div>
            <div class="checkbox">
            <label>
                <input type="checkbox" name="evalAromaChecklistDesc[]" id="evalAromaHopsDesc8" value="Woody Hops" <?php if ($action == "edit") { if (strpos($row_eval['evalAromaChecklistDesc'],"Woody Hops") !== false) echo "checked"; } ?>> Woody
            </label>
            </div>
      </div>
    </div>
    <div class="col col-lg-2 col-md-2 col-sm-4 col-xs-6">
      <div class="form-group">
        <label for="evalAromaEsters">Esters</label>
            <div class="checkbox">
            <label>
                <input type="checkbox" name="evalAromaChecklistDesc[]" id="evalAromaEstersDesc1" value="Fruity Esters" <?php if ($action == "edit") { if (strpos($row_eval['evalAromaChecklistDesc'],"Fruity Esters") !== false) echo "checked"; } ?>> Fruity
            </label>
            </div>
            <div class="checkbox">
            <label>
                <input type="checkbox" name="evalAromaChecklistDesc[]" id="evalAromaEstersDesc2" value="Apple/Pear Esters" <?php if ($action == "edit") { if (strpos($row_eval['evalAromaChecklistDesc'],"Apple/Pear Esters") !== false) echo "checked"; } ?>> Apple/Pear
            </label>
            </div>
            <div class="checkbox">
            <label>
                <input type="checkbox" name="evalAromaChecklistDesc[]" id="evalAromaEstersDesc3" value="Banana Esters" <?php if ($action == "edit") { if (strpos($row_eval['evalAromaChecklistDesc'],"Banana Esters") !== false) echo "checked"; } ?>> Banana
            </label>
            </div>
            <div class="checkbox">
            <label>
                <input type="checkbox" name="evalAromaChecklistDesc[]" id="evalAromaEstersDesc4" value="Berry Esters" <?php if ($action == "edit") { if (strpos($row_eval['evalAromaChecklistDesc'],"Berry Esters") !== false) echo "checked"; } ?>> Berry
            </label>
            </div>
            <div class="checkbox">
            <label>
                <input type="checkbox" name="evalAromaChecklistDesc[]" id="evalAromaEstersDesc5" value="Citrus Esters" <?php if ($action == "edit") { if (strpos($row_eval['evalAromaChecklistDesc'],"Citrus Esters") !== false) echo "checked"; } ?>> Citrus
            </label>
            </div>
            <div class="checkbox">
            <label>
                <input type="checkbox" name="evalAromaChecklistDesc[]" id="evalAromaEstersDesc6" value="Dried Fruit Esters" <?php if ($action == "edit") { if (strpos($row_eval['evalAromaChecklistDesc'],"Dried Fruit Esters") !== false) echo "checked"; } ?>> Dried Fruit
            </label>
            </div>
            <div class="checkbox">
            <label>
                <input type="checkbox" name="evalAromaChecklistDesc[]" id="evalAromaEstersDesc7" value="Grape Esters" <?php if ($action == "edit") { if (strpos($row_eval['evalAromaChecklistDesc'],"Grape Esters") !== false) echo "checked"; } ?>> Grape
            </label>
            </div>
            <div class="checkbox">
            <label>
                <input type="checkbox" name="evalAromaChecklistDesc[]" id="evalAromaEstersDesc8" value="Stone Fruit Esters" <?php if ($action == "edit") { if (strpos($row_eval['evalAromaChecklistDesc'],"Stone Fruit Esters") !== false) echo "checked"; } ?>> Stone Fruit
            </label>
            </div>
        </div>
    </div>
    <div class="col col-lg-2 col-md-2 col-sm-4 col-xs-6">
      <div class="form-group">
        <label for="evalAromaOther">Other</label>
            <div class="checkbox">
            <label>
                <input type="checkbox" name="evalAromaChecklistDesc[]" id="evalAromaOtherDesc1" value="Brett" <?php if ($action == "edit") { if (strpos($row_eval['evalAromaChecklistDesc'],"Brett") !== false) echo "checked"; } ?>> Brett
            </label>
            </div>
            <div class="checkbox">
            <label>
                <input type="checkbox" name="evalAromaChecklistDesc[]" id="evalAromaOtherDesc2" value="Fruit" <?php if ($action == "edit") { if (strpos($row_eval['evalAromaChecklistDesc'],"Fruit") !== false) echo "checked"; } ?>> Fruit
            </label>
            </div>
            <div class="checkbox">
            <label>
                <input type="checkbox" name="evalAromaChecklistDesc[]" id="evalAromaOtherDesc3" value="Lactic" <?php if ($action == "edit") { if (strpos($row_eval['evalAromaChecklistDesc'],"Lactic") !== false) echo "checked"; } ?>> Lactic
            </label>
            </div>
            <div class="checkbox">
            <label>
                <input type="checkbox" name="evalAromaChecklistDesc[]" id="evalAromaOtherDesc4" value="Smoke" <?php if ($action == "edit") { if (strpos($row_eval['evalAromaChecklistDesc'],"Smoke") !== false) echo "checked"; } ?>> Smoke
            </label>
            </div>
            <div class="checkbox">
            <label>
                <input type="checkbox" name="evalAromaChecklistDesc[]" id="evalAromaOtherDesc5" value="Spice" <?php if ($action == "edit") { if (strpos($row_eval['evalAromaChecklistDesc'],"Spice") !== false) echo "checked"; } ?>> Spice
            </label>
            </div>
            <div class="checkbox">
            <label>
                <input type="checkbox" name="evalAromaChecklistDesc[]" id="evalAromaOtherDesc6" value="Vinous" <?php if ($action == "edit") { if (strpos($row_eval['evalAromaChecklistDesc'],"Vinous") !== false) echo "checked"; } ?>> Vinous
            </label>
            </div>
            <div class="checkbox">
            <label>
                <input type="checkbox" name="evalAromaChecklistDesc[]" id="evalAromaOtherDesc7" value="Wood" <?php if ($action == "edit") { if (strpos($row_eval['evalAromaChecklistDesc'],"Wood") !== false) echo "checked"; } ?>> Wood
            </label>
            </div>
        </div>
    </div>
  </div><!-- END Row -->
  </section>
</div><!-- END Aroma Collapse -->
<!-- Appearance Checklist -->
<h3 class="section-heading">Appearance</h3>
<div class="form-horizontal">
  <div class="form-group">
    <label class="col-sm-3 control-label">Clarity</label>
    <div class="col-sm-9">
    	<label class="radio-inline">
          	<input type="radio" name="evalAppearanceClarity" id="evalAppearanceClarity1" value="No Clarity" data-error="Please choose a clarity descriptor." required <?php if ($action == "edit") { if (strpos($row_eval['evalAppearanceChecklist'],"No Clarity") !== false) echo "checked"; } ?>> None
        </label>
        <label class="radio-inline">
          	<input type="radio" name="evalAppearanceClarity" id="evalAppearanceClarity2" value="Low Clarity" data-error="Please choose a clarity descriptor." required <?php if ($action == "edit") { if (strpos($row_eval['evalAppearanceChecklist'],"Low Clarity") !== false) echo "checked"; } ?>> Low
        </label>
        <label class="radio-inline">
          	<input type="radio" name="evalAppearanceClarity" id="evalAppearanceClarity3" value="Medium Clarity" data-error="Please choose a clarity descriptor." required <?php if ($action == "edit") { if (strpos($row_eval['evalAppearanceChecklist'],"Medium Clarity") !== false) echo "checked"; } ?>> Medium
        </label>
        <label class="radio-inline">
          	<input type="radio" name="evalAppearanceClarity" id="evalAppearanceClarity4" value="High Clarity" data-error="Please choose a clarity descriptor." required <?php if ($action == "edit") { if (strpos($row_eval['evalAppearanceChecklist'],"High Clarity") !== false) echo "checked"; } ?>> High
        </label>
        <div class="help-block small with-errors"></div>
  	</div>
  </div>
  <div class="form-group">
    <label class="col-sm-3 control-label">Head Size</label>
    <div class="col-sm-9">
    	<label class="radio-inline">
          	<input type="radio" name="evalAppearanceHeadSize" id="evalAppearanceHeadSize1" value="No Head" data-error="Please choose a head size descriptor." required <?php if ($action == "edit") { if (strpos($row_eval['evalAppearanceChecklist'],"No Head") !== false) echo "checked"; } ?>> None
        </label>
        <label class="radio-inline">
          	<input type="radio" name="evalAppearanceHeadSize" id="evalAppearanceHeadSize2" value="Low Head" data-error="Please choose a head size descriptor." required <?php if ($action == "edit") { if (strpos($row_eval['evalAppearanceChecklist'],"Low Head") !== false) echo "checked"; } ?>> Low
        </label>
        <label class="radio-inline">
          	<input type="radio" name="evalAppearanceHeadSize" id="evalAppearanceHeadSize3" value="Medium Head" data-error="Please choose a head size descriptor." required <?php if ($action == "edit") { if (strpos($row_eval['evalAppearanceChecklist'],"Medium Head") !== false) echo "checked"; } ?>> Medium
        </label>
        <label class="radio-inline">
          	<input type="radio" name="evalAppearanceHeadSize" id="evalAppearanceHeadSize4" value="High Head" data-error="Please choose a head size descriptor." required <?php if ($action == "edit") { if (strpos($row_eval['evalAppearanceChecklist'],"High Head") !== false) echo "checked"; } ?>> High
        </label>
        <div class="help-block small with-errors"></div>
  	</div>
  </div>
  <div class="form-group">
    <label class="col-sm-3 control-label">Head Retention</label>
    <div class="col-sm-9">
    	<label class="radio-inline">
          	<input type="radio" name="evalAppearanceHeadRetention" id="evalAppearanceHeadRetention1" value="No Head Retention" data-error="Please choose head retention descriptor." required <?php if ($action == "edit") { if (strpos($row_eval['evalAppearanceChecklist'],"No Head Retention") !== false) echo "checked"; } ?>> None
        </label>
        <label class="radio-inline">
          	<input type="radio" name="evalAppearanceHeadRetention" id="evalAppearanceHeadRetention2" value="Low Head Retention" data-error="Please choose a head retention descriptor." required <?php if ($action == "edit") { if (strpos($row_eval['evalAppearanceChecklist'],"Low Head Retention") !== false) echo "checked"; } ?>> Low
        </label>
        <label class="radio-inline">
          	<input type="radio" name="evalAppearanceHeadRetention" id="evalAppearanceHeadRetention3" value="Medium Head Retention" data-error="Please choose a head retention descriptor." required <?php if ($action == "edit") { if (strpos($row_eval['evalAppearanceChecklist'],"Medium Head Retention") !== false) echo "checked"; } ?>> Medium
        </label>
        <label class="radio-inline">
          	<input type="radio" name="evalAppearanceHeadRetention" id="evalAppearanceHeadRetention4" value="High Head Retention" data-error="Please choose a head retention descriptor." required <?php if ($action == "edit") { if (strpos($row_eval['evalAppearanceChecklist'],"High Head Retention") !== false) echo "checked"; } ?>> High
        </label>
        <div class="help-block small with-errors"></div>
  	</div>
  </div>
</div>
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
    <textarea class="form-control" name="evalAppearanceComments" rows="6" placeholder="Brief comments relating to appearance; 450 characters maximum." maxlength="450" data-error="Please provide some brief appearance comments." required><?php if ($action == "edit") echo $row_eval['evalAppearanceComments']; ?></textarea>
    <div class="help-block small with-errors"></div>
</div>
<!-- Appearance Descriptors
<div class="bcoem-admin-element">
	<button class="btn btn-info" type="button" data-toggle="collapse" data-target="#appearanceDescriptors" aria-expanded="false" aria-controls="appearanceDescriptors">Appearance Descriptors (Select to Expand/Collapse)</button>
</div>
-->
<p><strong>Appearance Descriptors</strong></p>
<div class="collapse in" id="appearanceDescriptors">
  <section class="well">
    <div class="row">
      <div class="col col-lg-2 col-md-2 col-sm-4 col-xs-6">
        <div class="form-group">
        <label for="appearanceColor">Beer Color</label>
            <div class="checkbox">
            <label>
                <input type="checkbox" name="evalAppearanceChecklistDesc[]" id="evalAppearanceColorDesc1" value="Straw Color" <?php if ($action == "edit") { if (strpos($row_eval['evalAppearanceChecklistDesc'],"Straw Color") !== false) echo "checked"; } ?>> Straw
            </label>
            </div>
            <div class="checkbox">
            <label>
                <input type="checkbox" name="evalAppearanceChecklistDesc[]" id="evalAppearanceColorDesc2" value="Yellow Color" <?php if ($action == "edit") { if (strpos($row_eval['evalAppearanceChecklistDesc'],"Yellow Color") !== false) echo "checked"; } ?>> Yellow
            </label>
            </div>
            <div class="checkbox">
            <label>
                <input type="checkbox" name="evalAppearanceChecklistDesc[]" id="evalAppearanceColorDesc3" value="Gold Color" <?php if ($action == "edit") { if (strpos($row_eval['evalAppearanceChecklistDesc'],"Gold Color") !== false) echo "checked"; } ?>> Gold
            </label>
            </div>
            <div class="checkbox">
            <label>
                <input type="checkbox" name="evalAppearanceChecklistDesc[]" id="evalAppearanceColorDesc4" value="Amber Color" <?php if ($action == "edit") { if (strpos($row_eval['evalAppearanceChecklistDesc'],"Amber Color") !== false) echo "checked"; } ?>> Amber
            </label>
            </div>
            <div class="checkbox">
            <label>
                <input type="checkbox" name="evalAppearanceChecklistDesc[]" id="evalAppearanceColorDesc5" value="Copper Color" <?php if ($action == "edit") { if (strpos($row_eval['evalAppearanceChecklistDesc'],"Copper Color") !== false) echo "checked"; } ?>> Copper
            </label>
            </div>
            <div class="checkbox">
            <label>
                <input type="checkbox" name="evalAppearanceChecklistDesc[]" id="evalAppearanceColorDesc6" value="Brown Color" <?php if ($action == "edit") { if (strpos($row_eval['evalAppearanceChecklistDesc'],"Brown Color") !== false) echo "checked"; } ?>> Brown
            </label>
            </div>
            <div class="checkbox">
            <label>
                <input type="checkbox" name="evalAppearanceChecklistDesc[]" id="evalAppearanceColorDesc7" value="Black Color" <?php if ($action == "edit") { if (strpos($row_eval['evalAppearanceChecklistDesc'],"Black Color") !== false) echo "checked"; } ?>> Black
            </label>
            </div>
        </div>
      </div>
      <div class="col col-lg-2 col-md-2 col-sm-4 col-xs-6">
        <div class="form-group">
        <label for="appearanceHeadColor">Head Color</label>
            <div class="checkbox">
            <label>
                <input type="checkbox" name="evalAppearanceChecklistDesc[]" id="evalAppearanceHeadDesc1" value="White Head Color" <?php if ($action == "edit") { if (strpos($row_eval['evalAppearanceChecklistDesc'],"White Head Color") !== false) echo "checked"; } ?>> White
            </label>
            </div>
            <div class="checkbox">
            <label>
                <input type="checkbox" name="evalAppearanceChecklistDesc[]" id="evalAppearanceHeadDesc2" value="Ivory Head Color" <?php if ($action == "edit") { if (strpos($row_eval['evalAppearanceChecklistDesc'],"Ivory Head Color") !== false) echo "checked"; } ?>> Ivory
            </label>
            </div>
            <div class="checkbox">
            <label>
                <input type="checkbox" name="evalAppearanceChecklistDesc[]" id="evalAppearanceHeadDesc3" value="Cream Head Color" <?php if ($action == "edit") { if (strpos($row_eval['evalAppearanceChecklistDesc'],"Cream Head Color") !== false) echo "checked"; } ?>> Cream
            </label>
            </div>
            <div class="checkbox">
            <label>
                <input type="checkbox" name="evalAppearanceChecklistDesc[]" id="evalAppearanceHeadDesc4" value="Beige Head Color" <?php if ($action == "edit") { if (strpos($row_eval['evalAppearanceChecklistDesc'],"Beige Head Color") !== false) echo "checked"; } ?>> Beige
            </label>
            </div>
            <div class="checkbox">
            <label>
                <input type="checkbox" name="evalAppearanceChecklistDesc[]" id="evalAppearanceHeadDesc5" value="Tan Head Color" <?php if ($action == "edit") { if (strpos($row_eval['evalAppearanceChecklistDesc'],"Tan Head Color") !== false) echo "checked"; } ?>> Tan
            </label>
            </div>
            <div class="checkbox">
            <label>
                <input type="checkbox" name="evalAppearanceChecklistDesc[]" id="evalAppearanceHeadDesc6" value="Brown Head Color" <?php if ($action == "edit") { if (strpos($row_eval['evalAppearanceChecklistDesc'],"Brown Head Color") !== false) echo "checked"; } ?>> Brown
            </label>
            </div>
        </div>
      </div>
      <div class="col col-lg-2 col-md-2 col-sm-4 col-xs-6">
        <div class="form-group">
        <label for="appearanceOther">Other</label>
            <div class="checkbox">
            <label>
                <input type="checkbox" name="evalAppearanceChecklistDesc[]" id="evalAppearanceOtherDesc1" value="Flat" <?php if ($action == "edit") { if (strpos($row_eval['evalAppearanceChecklistDesc'],"Flat") !== false) echo "checked"; } ?>> Flat
            </label>
            </div>
            <div class="checkbox">
            <label>
                <input type="checkbox" name="evalAppearanceChecklistDesc[]" id="evalAppearanceOtherDesc2" value="Lacing" <?php if ($action == "edit") { if (strpos($row_eval['evalAppearanceChecklistDesc'],"Lacing") !== false) echo "checked"; } ?>> Lacing
            </label>
            </div>
            <div class="checkbox">
            <label>
                <input type="checkbox" name="evalAppearanceChecklistDesc[]" id="evalAppearanceOtherDesc3" value="Legs" <?php if ($action == "edit") { if (strpos($row_eval['evalAppearanceChecklistDesc'],"Legs") !== false) echo "checked"; } ?>> Legs
            </label>
            </div>
            <div class="checkbox">
            <label>
                <input type="checkbox" name="evalAppearanceChecklistDesc[]" id="evalAppearanceOtherDesc4" value="Opaque" <?php if ($action == "edit") { if (strpos($row_eval['evalAppearanceChecklistDesc'],"Opaque") !== false) echo "checked"; } ?>> Opaque
            </label>
            </div>
        </div>
      </div>
    </div><!-- End Row -->
  </section>
</div><!-- End Collapse -->
<!-- Flavor Checklist -->
<h3 class="section-heading">Flavor</h3>
<div class="form-horizontal">
  <div class="form-group">
    <label class="col-sm-3 control-label">Malt</label>
    <div class="col-sm-9">
    	<label class="radio-inline">
          	<input type="radio" name="evalFlavorMalt" id="evalFlavorMalt1" value="No Malt" data-error="Please choose a malt flavor descriptor." required <?php if ($action == "edit") { if (strpos($row_eval['evalFlavorChecklist'],"No Malt") !== false) echo "checked"; } ?>> None
        </label>
        <label class="radio-inline">
          	<input type="radio" name="evalFlavorMalt" id="evalFlavorMalt2" value="Low Malt" data-error="Please choose a malt flavor descriptor." required <?php if ($action == "edit") { if (strpos($row_eval['evalFlavorChecklist'],"Low Malt") !== false) echo "checked"; } ?>> Low
        </label>
        <label class="radio-inline">
          	<input type="radio" name="evalFlavorMalt" id="evalFlavorMalt3" value="Medium Malt" data-error="Please choose a malt flavor descriptor." required <?php if ($action == "edit") { if (strpos($row_eval['evalFlavorChecklist'],"Medium Malt") !== false) echo "checked"; } ?>> Medium
        </label>
        <label class="radio-inline">
          	<input type="radio" name="evalFlavorMalt" id="evalFlavorMalt4" value="High Malt" data-error="Please choose a malt flavor descriptor." required <?php if ($action == "edit") { if (strpos($row_eval['evalFlavorChecklist'],"High Malt") !== false) echo "checked"; } ?>> High
        </label>
        <div class="help-block small with-errors"></div>
  	</div>
  </div>
  <div class="form-group">
    <label class="col-sm-3 control-label">Hops</label>
    <div class="col-sm-9">
    	<label class="radio-inline">
          	<input type="radio" name="evalFlavorHops" id="evalFlavorHops1" value="No Hops" data-error="Please choose a hop flavor descriptor." required <?php if ($action == "edit") { if (strpos($row_eval['evalFlavorChecklist'],"No Hops") !== false) echo "checked"; } ?>> None
        </label>
        <label class="radio-inline">
          	<input type="radio" name="evalFlavorHops" id="evalFlavorHops2" value="Low Hops" data-error="Please choose a hop flavor descriptor." required <?php if ($action == "edit") { if (strpos($row_eval['evalFlavorChecklist'],"Low Hops") !== false) echo "checked"; } ?>> Low
        </label>
        <label class="radio-inline">
          	<input type="radio" name="evalFlavorHops" id="evalFlavorHops3" value="Medium Hops" data-error="Please choose a hop flavor descriptor." required <?php if ($action == "edit") { if (strpos($row_eval['evalFlavorChecklist'],"Medium Hops") !== false) echo "checked"; } ?>> Medium
        </label>
        <label class="radio-inline">
          	<input type="radio" name="evalFlavorHops" id="evalFlavorHops4" value="High Hops" data-error="Please choose a hop flavor descriptor." required <?php if ($action == "edit") { if (strpos($row_eval['evalFlavorChecklist'],"High Hops") !== false) echo "checked"; } ?>> High
        </label>
        <div class="help-block small with-errors"></div>
  	</div>
  </div>
  <div class="form-group">
    <label class="col-sm-3 control-label">Esters</label>
    <div class="col-sm-9">
    	<label class="radio-inline">
          	<input type="radio" name="evalFlavorEsters" id="evalFlavorEsters1" value="No Esters" data-error="Please choose an ester flavor descriptor." required <?php if ($action == "edit") { if (strpos($row_eval['evalFlavorChecklist'],"No Esters") !== false) echo "checked"; } ?>> None
        </label>
        <label class="radio-inline">
          	<input type="radio" name="evalFlavorEsters" id="evalFlavorEsters2" value="Low Esters" data-error="Please choose an ester flavor descriptor." required <?php if ($action == "edit") { if (strpos($row_eval['evalFlavorChecklist'],"Low Esters") !== false) echo "checked"; } ?>> Low
        </label>
        <label class="radio-inline">
          	<input type="radio" name="evalFlavorEsters" id="evalFlavorEsters3" value="Medium Esters" data-error="Please choose an ester flavor descriptor." required <?php if ($action == "edit") { if (strpos($row_eval['evalFlavorChecklist'],"Medium Esters") !== false) echo "checked"; } ?>> Medium
        </label>
        <label class="radio-inline">
          	<input type="radio" name="evalFlavorEsters" id="evalFlavorEsters4" value="High Esters" data-error="Please choose an ester flavor descriptor." required <?php if ($action == "edit") { if (strpos($row_eval['evalFlavorChecklist'],"High Esters") !== false) echo "checked"; } ?>> High
        </label>
        <div class="help-block small with-errors"></div>
  	</div>
  </div>
  <div class="form-group">
    <label class="col-sm-3 control-label">Phenols</label>
    <div class="col-sm-9">
    	<label class="radio-inline">
          	<input type="radio" name="evalFlavorPhenols" id="evalFlavorPhenols1" value="No Phenols" data-error="Please choose a phenol flavor descriptor." required <?php if ($action == "edit") { if (strpos($row_eval['evalFlavorChecklist'],"No Phenols") !== false) echo "checked"; } ?>> None
        </label>
        <label class="radio-inline">
          	<input type="radio" name="evalFlavorPhenols" id="evalFlavorPhenols2" value="Low Phenols" data-error="Please choose a phenol flavor descriptor." required <?php if ($action == "edit") { if (strpos($row_eval['evalFlavorChecklist'],"Low Phenols") !== false) echo "checked"; } ?>> Low
        </label>
        <label class="radio-inline">
          	<input type="radio" name="evalFlavorPhenols" id="evalFlavorPhenols3" value="Medium Phenols" data-error="Please choose a phenol flavor descriptor." required <?php if ($action == "edit") { if (strpos($row_eval['evalFlavorChecklist'],"Medium Phenols") !== false) echo "checked"; } ?>> Medium
        </label>
        <label class="radio-inline">
          	<input type="radio" name="evalFlavorPhenols" id="evalFlavorPhenols4" value="High Phenols" data-error="Please choose a phenol flavor descriptor." required <?php if ($action == "edit") { if (strpos($row_eval['evalFlavorChecklist'],"High Phenols") !== false) echo "checked"; } ?>> High
        </label>
        <div class="help-block small with-errors"></div>
  	</div>
  </div>
   <div class="form-group">
    <label class="col-sm-3 control-label">Sweetness</label>
    <div class="col-sm-9">
    	<label class="radio-inline">
          	<input type="radio" name="evalFlavorSweetness" id="evalFlavorSweetness1" value="No Sweetness" data-error="Please choose a sweetness flavor descriptor." required <?php if ($action == "edit") { if (strpos($row_eval['evalFlavorChecklist'],"No Sweetness") !== false) echo "checked"; } ?>> None
        </label>
        <label class="radio-inline">
          	<input type="radio" name="evalFlavorSweetness" id="evalFlavorSweetness2" value="Low Sweetness" data-error="Please choose a sweetness flavor descriptor." required <?php if ($action == "edit") { if (strpos($row_eval['evalFlavorChecklist'],"Low Sweetness") !== false) echo "checked"; } ?>> Low
        </label>
        <label class="radio-inline">
          	<input type="radio" name="evalFlavorSweetness" id="evalFlavorSweetness3" value="Medium Sweetness" data-error="Please choose a sweetness flavor descriptor." required <?php if ($action == "edit") { if (strpos($row_eval['evalFlavorChecklist'],"Medium Sweetness") !== false) echo "checked"; } ?>> Medium
        </label>
        <label class="radio-inline">
          	<input type="radio" name="evalFlavorSweetness" id="evalFlavorSweetness4" value="High Sweetness" data-error="Please choose a sweetness flavor descriptor." required <?php if ($action == "edit") { if (strpos($row_eval['evalFlavorChecklist'],"High Sweetness") !== false) echo "checked"; } ?>> High
        </label>
        <div class="help-block small with-errors"></div>
  	</div>
  </div>
  <div class="form-group">
    <label class="col-sm-3 control-label">Bitterness</label>
    <div class="col-sm-9">
    	<label class="radio-inline">
          	<input type="radio" name="evalFlavorBitterness" id="evalFlavorBitterness1" value="No Bitterness" data-error="Please choose a bitterness flavor descriptor." required <?php if ($action == "edit") { if (strpos($row_eval['evalFlavorChecklist'],"No Bitterness") !== false) echo "checked"; } ?>> None
        </label>
        <label class="radio-inline">
          	<input type="radio" name="evalFlavorBitterness" id="evalFlavorBitterness2" value="Low Bitterness" data-error="Please choose a bitterness flavor descriptor." required <?php if ($action == "edit") { if (strpos($row_eval['evalFlavorChecklist'],"Low Bitterness") !== false) echo "checked"; } ?>> Low
        </label>
        <label class="radio-inline">
          	<input type="radio" name="evalFlavorBitterness" id="evalFlavorBitterness3" value="Medium Bitterness" data-error="Please choose a bitterness flavor descriptor." required <?php if ($action == "edit") { if (strpos($row_eval['evalFlavorChecklist'],"Medium Bitterness") !== false) echo "checked"; } ?>> Medium
        </label>
        <label class="radio-inline">
          	<input type="radio" name="evalFlavorBitterness" id="evalFlavorBitterness4" value="High Bitterness" data-error="Please choose a bitterness flavor descriptor." required <?php if ($action == "edit") { if (strpos($row_eval['evalFlavorChecklist'],"High Bitterness") !== false) echo "checked"; } ?>> High
        </label>
        <div class="help-block small with-errors"></div>
  	</div>
  </div>
  <div class="form-group">
    <label class="col-sm-3 control-label">Alcohol</label>
    <div class="col-sm-9">
    	<label class="radio-inline">
          	<input type="radio" name="evalFlavorAlcohol" id="evalFlavorAlcohol1" value="No Alcohol" data-error="Please choose an alcohol flavor descriptor." required <?php if ($action == "edit") { if (strpos($row_eval['evalFlavorChecklist'],"No Alcohol") !== false) echo "checked"; } ?>> None
        </label>
        <label class="radio-inline">
          	<input type="radio" name="evalFlavorAlcohol" id="evalFlavorAlcohol2" value="Low Alcohol" data-error="Please choose an alcohol flavor descriptor." required <?php if ($action == "edit") { if (strpos($row_eval['evalFlavorChecklist'],"Low Alcohol") !== false) echo "checked"; } ?>> Low
        </label>
        <label class="radio-inline">
          	<input type="radio" name="evalFlavorAlcohol" id="evalFlavorAlcohol3" value="Medium Alcohol" data-error="Please choose an alcohol flavor descriptor." required <?php if ($action == "edit") { if (strpos($row_eval['evalFlavorChecklist'],"Medium Alcohol") !== false) echo "checked"; } ?>> Medium
        </label>
        <label class="radio-inline">
          	<input type="radio" name="evalFlavorAlcohol" id="evalFlavorAlcohol4" value="High Alcohol" data-error="Please choose an alcohol flavor descriptor." required <?php if ($action == "edit") { if (strpos($row_eval['evalFlavorChecklist'],"High Alcohol") !== false) echo "checked"; } ?>> High
        </label>
        <div class="help-block small with-errors"></div>
  	</div>
  </div>
  <div class="form-group">
    <label class="col-sm-3 control-label">Acidity</label>
    <div class="col-sm-9">
    	<label class="radio-inline">
          	<input type="radio" name="evalFlavorAcidity" id="evalFlavorAcidity1" value="No Acidity" data-error="Please choose an acidity flavor descriptor." required <?php if ($action == "edit") { if (strpos($row_eval['evalFlavorChecklist'],"No Acidity") !== false) echo "checked"; } ?>> None
        </label>
        <label class="radio-inline">
          	<input type="radio" name="evalFlavorAcidity" id="evalFlavorAcidity2" value="Low Acidity" data-error="Please choose an acidity flavor descriptor." required <?php if ($action == "edit") { if (strpos($row_eval['evalFlavorChecklist'],"Low Acidity") !== false) echo "checked"; } ?>> Low
        </label>
        <label class="radio-inline">
          	<input type="radio" name="evalFlavorAcidity" id="evalFlavorAcidity3" value="Medium Acidity" data-error="Please choose an acidity flavor descriptor." required <?php if ($action == "edit") { if (strpos($row_eval['evalFlavorChecklist'],"Medium Acidity") !== false) echo "checked"; } ?>> Medium
        </label>
        <label class="radio-inline">
          	<input type="radio" name="evalFlavorAcidity" id="evalFlavorAcidity4" value="High Acidity" data-error="Please choose an acidity flavor descriptor." required <?php if ($action == "edit") { if (strpos($row_eval['evalFlavorChecklist'],"High Acidity") !== false) echo "checked"; } ?>> High
        </label>
        <div class="help-block small with-errors"></div>
  	</div>
  </div>
  <div class="form-group">
    <label class="col-sm-3 control-label">Harshness</label>
    <div class="col-sm-9">
    	<label class="radio-inline">
          	<input type="radio" name="evalFlavorHarshness" id="evalFlavorHarshness1" value="No Harshness" data-error="Please choose a harshness flavor descriptor." required <?php if ($action == "edit") { if (strpos($row_eval['evalFlavorChecklist'],"No Harshness") !== false) echo "checked"; } ?>> None
        </label>
        <label class="radio-inline">
          	<input type="radio" name="evalFlavorHarshness" id="evalFlavorHarshness2" value="Low Harshness" data-error="Please choose a harshness flavor descriptor." required <?php if ($action == "edit") { if (strpos($row_eval['evalFlavorChecklist'],"Low Harshness") !== false) echo "checked"; } ?>> Low
        </label>
        <label class="radio-inline">
          	<input type="radio" name="evalFlavorHarshness" id="evalFlavorHarshness3" value="Medium Harshness" data-error="Please choose a harshness flavor descriptor." required <?php if ($action == "edit") { if (strpos($row_eval['evalFlavorChecklist'],"Medium Harshness") !== false) echo "checked"; } ?>> Medium
        </label>
        <label class="radio-inline">
          	<input type="radio" name="evalFlavorHarshness" id="evalFlavorHarshness" value="High Harshness" data-error="Please choose a harshness flavor descriptor." required <?php if ($action == "edit") { if (strpos($row_eval['evalFlavorChecklist'],"High Harshness") !== false) echo "checked"; } ?>> High
        </label>
        <div class="help-block small with-errors"></div>
  	</div>
  </div>
</div>
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
    <textarea class="form-control" name="evalFlavorComments" rows="6" placeholder="Brief comments relating to flavor; 450 characters maximum." maxlength="450" data-error="Please provide some brief flavor comments." required><?php if ($action == "edit") echo $row_eval['evalFlavorComments']; ?></textarea>
    <div class="help-block small with-errors"></div>
</div>
<!-- Flavor Descriptors
<div class="bcoem-admin-element">
	<button class="btn btn-info" type="button" data-toggle="collapse" data-target="#flavorDescriptors" aria-expanded="false" aria-controls="flavorDescriptors">Flavor Descriptors (Select to Expand/Collapse)</button>
</div>
-->
<p><strong>Flavor Descriptors</strong></p>
<div class="collapse in" id="flavorDescriptors">
  <div class="well">
    <div class="row">
      <div class="col col-lg-2 col-md-2 col-sm-4 col-xs-6">
        <div class="form-group">
          <label for="flavorMalt">Malt</label>
              <div class="checkbox">
              <label>
                  <input type="checkbox" name="evalFlavorChecklistDesc[]" id="evalFlavorMaltDesc1" value="Grainy Malt" <?php if ($action == "edit") { if (strpos($row_eval['evalFlavorChecklistDesc'],"Grainy Malt") !== false) echo "checked"; } ?>> Grainy
              </label>
              </div>
              <div class="checkbox">
              <label>
                  <input type="checkbox" name="evalFlavorChecklistDesc[]" id="evalFlavorMaltDesc2" value="Caramel Malt" <?php if ($action == "edit") { if (strpos($row_eval['evalFlavorChecklistDesc'],"Caramel Malt") !== false) echo "checked"; } ?>> Caramel
              </label>
              </div>
              <div class="checkbox">
              <label>
                  <input type="checkbox" name="evalFlavorChecklistDesc[]" id="evalFlavorMaltDesc3" value="Bready Malt" <?php if ($action == "edit") { if (strpos($row_eval['evalFlavorChecklistDesc'],"Bready Malt") !== false) echo "checked"; } ?>> Bready
              </label>
              </div>
              <div class="checkbox">
              <label>
                  <input type="checkbox" name="evalFlavorChecklistDesc[]" id="evalFlavorMaltDesc4" value="Rich Malt" <?php if ($action == "edit") { if (strpos($row_eval['evalFlavorChecklistDesc'],"Rich Malt") !== false) echo "checked"; } ?>> Rich
              </label>
              </div>
              <div class="checkbox">
              <label>
                  <input type="checkbox" name="evalFlavorChecklistDesc[]" id="evalFlavorMaltDesc5" value="Dark Fruit Malt" <?php if ($action == "edit") { if (strpos($row_eval['evalFlavorChecklistDesc'],"Dark Fruit Malt") !== false) echo "checked"; } ?>> Dark Fruit
              </label>
              </div>
              <div class="checkbox">
              <label>
                  <input type="checkbox" name="evalFlavorChecklistDesc[]" id="evalFlavorMaltDesc6" value="Toasty Malt" <?php if ($action == "edit") { if (strpos($row_eval['evalFlavorChecklistDesc'],"Toasty Malt") !== false) echo "checked"; } ?>> Toasty
              </label>
              </div>
              <div class="checkbox">
              <label>
                  <input type="checkbox" name="evalFlavorChecklistDesc[]" id="evalFlavorMaltDesc7" value="Roasty Malt" <?php if ($action == "edit") { if (strpos($row_eval['evalFlavorChecklistDesc'],"Roasty Malt") !== false) echo "checked"; } ?>> Roasty
              </label>
              </div>
              <div class="checkbox">
              <label>
                  <input type="checkbox" name="evalFlavorChecklistDesc[]" id="evalFlavorMaltDesc8" value="Burnt Malt" <?php if ($action == "edit") { if (strpos($row_eval['evalFlavorChecklistDesc'],"Burnt Malt") !== false) echo "checked"; } ?>> Burnt
              </label>
              </div>
        </div>
      </div>
      <div class="col col-lg-2 col-md-2 col-sm-4 col-xs-6">
        <div class="form-group">
          <label for="flavorHops">Hops</label>
              <div class="checkbox">
              <label>
                  <input type="checkbox" name="evalFlavorChecklistDesc[]" id="evalFlavorHopsDesc1" value="Citrusy Hops" <?php if ($action == "edit") { if (strpos($row_eval['evalFlavorChecklistDesc'],"Citrusy Hops") !== false) echo "checked"; } ?>> Citrusy
              </label>
              </div>
              <div class="checkbox">
              <label>
                  <input type="checkbox" name="evalFlavorChecklistDesc[]" id="evalFlavorHopsDesc2" value="Earthy Hops" <?php if ($action == "edit") { if (strpos($row_eval['evalFlavorChecklistDesc'],"Earthy Hops") !== false) echo "checked"; } ?>> Earthy
              </label>
              </div>
              <div class="checkbox">
              <label>
                  <input type="checkbox" name="evalFlavorChecklistDesc[]" id="evalFlavorHopsDesc3" value="Floral Hops" <?php if ($action == "edit") { if (strpos($row_eval['evalFlavorChecklistDesc'],"Floral Hops") !== false) echo "checked"; } ?>> Floral
              </label>
              </div>
              <div class="checkbox">
              <label>
                  <input type="checkbox" name="evalFlavorChecklistDesc[]" id="evalFlavorHopsDesc4" value="Grassy Hops" <?php if ($action == "edit") { if (strpos($row_eval['evalFlavorChecklistDesc'],"Grassy Hops") !== false) echo "checked"; } ?>> Grassy
              </label>
              </div>
              <div class="checkbox">
              <label>
                  <input type="checkbox" name="evalFlavorChecklistDesc[]" id="evalFlavorHopsDesc5" value="Herbal Hops" <?php if ($action == "edit") { if (strpos($row_eval['evalFlavorChecklistDesc'],"Herbal Hops") !== false) echo "checked"; } ?>> Herbal
              </label>
              </div>
              <div class="checkbox">
              <label>
                  <input type="checkbox" name="evalFlavorChecklistDesc[]" id="evalFlavorHopsDesc6" value="Piney Hops" <?php if ($action == "edit") { if (strpos($row_eval['evalFlavorChecklistDesc'],"Piney Hops") !== false) echo "checked"; } ?>> Piney
              </label>
              </div>
              <div class="checkbox">
              <label>
                  <input type="checkbox" name="evalFlavorChecklistDesc[]" id="evalFlavorHopsDesc7" value="Spicy Hops" <?php if ($action == "edit") { if (strpos($row_eval['evalFlavorChecklistDesc'],"Spicy Hops") !== false) echo "checked"; } ?>> Spicy
              </label>
              </div>
              <div class="checkbox">
              <label>
                  <input type="checkbox" name="evalFlavorChecklistDesc[]" id="evalFlavorHopsDesc8" value="Woody Hops" <?php if ($action == "edit") { if (strpos($row_eval['evalFlavorChecklistDesc'],"Woody Hops") !== false) echo "checked"; } ?>> Woody
              </label>
              </div>
        </div>
      </div>
      <div class="col col-lg-2 col-md-2 col-sm-4 col-xs-6">
        <div class="form-group">
          <label for="flavorEsters">Esters</label>
              <div class="checkbox">
              <label>
                  <input type="checkbox" name="evalFlavorChecklistDesc[]" id="evalFlavorEstersDesc1" value="Fruity Esters" <?php if ($action == "edit") { if (strpos($row_eval['evalFlavorChecklistDesc'],"Fruity Esters") !== false) echo "checked"; } ?>> Fruity
              </label>
              </div>
              <div class="checkbox">
              <label>
                  <input type="checkbox" name="evalFlavorChecklistDesc[]" id="evalFlavorEstersDesc2" value="Apple/Pear Esters" <?php if ($action == "edit") { if (strpos($row_eval['evalFlavorChecklistDesc'],"Apple/Pear Esters") !== false) echo "checked"; } ?>> Apple/Pear
              </label>
              </div>
              <div class="checkbox">
              <label>
                  <input type="checkbox" name="evalFlavorChecklistDesc[]" id="evalFlavorEstersDesc3" value="Banana Esters" <?php if ($action == "edit") { if (strpos($row_eval['evalFlavorChecklistDesc'],"Banana Esters") !== false) echo "checked"; } ?>> Banana
              </label>
              </div>
              <div class="checkbox">
              <label>
                  <input type="checkbox" name="evalFlavorChecklistDesc[]" id="evalFlavorEstersDesc4" value="Berry Esters" <?php if ($action == "edit") { if (strpos($row_eval['evalFlavorChecklistDesc'],"Berry Esters") !== false) echo "checked"; } ?>> Berry
              </label>
              </div>
              <div class="checkbox">
              <label>
                  <input type="checkbox" name="evalFlavorChecklistDesc[]" id="evalFlavorEstersDesc5" value="Citrus Esters" <?php if ($action == "edit") { if (strpos($row_eval['evalFlavorChecklistDesc'],"Citrus Esters") !== false) echo "checked"; } ?>> Citrus
              </label>
              </div>
              <div class="checkbox">
              <label>
                  <input type="checkbox" name="evalFlavorChecklistDesc[]" id="evalFlavorEstersDesc6" value="Dried Fruit Esters" <?php if ($action == "edit") { if (strpos($row_eval['evalFlavorChecklistDesc'],"Dried Fruit Esters") !== false) echo "checked"; } ?>> Dried Fruit
              </label>
              </div>
              <div class="checkbox">
              <label>
                  <input type="checkbox" name="evalFlavorChecklistDesc[]" id="evalFlavorEstersDesc7" value="Grape Esters" <?php if ($action == "edit") { if (strpos($row_eval['evalFlavorChecklistDesc'],"Grape Esters") !== false) echo "checked"; } ?>> Grape
              </label>
              </div>
              <div class="checkbox">
              <label>
                  <input type="checkbox" name="evalFlavorChecklistDesc[]" id="evalFlavorEstersDesc8" value="Stone Fruit Esters" <?php if ($action == "edit") { if (strpos($row_eval['evalFlavorChecklistDesc'],"Stone Fruit Esters") !== false) echo "checked"; } ?>> Stone Fruit
              </label>
              </div>
          </div>
      </div>
      <div class="col col-lg-2 col-md-2 col-sm-4 col-xs-6">
        <div class="form-group">
          <label for="flavorOther">Other</label>
              <div class="checkbox">
              <label>
                  <input type="checkbox" name="evalFlavorChecklistDesc[]" id="evalFlavorOtherDesc1" value="Brett" <?php if ($action == "edit") { if (strpos($row_eval['evalFlavorChecklistDesc'],"Brett") !== false) echo "checked"; } ?>> Brett
              </label>
              </div>
              <div class="checkbox">
              <label>
                  <input type="checkbox" name="evalFlavorChecklistDesc[]" id="evalFlavorOtherDesc2" value="Fruit" <?php if ($action == "edit") { if (strpos($row_eval['evalFlavorChecklistDesc'],"Fruit") !== false) echo "checked"; } ?>> Fruit
              </label>
              </div>
              <div class="checkbox">
              <label>
                  <input type="checkbox" name="evalFlavorChecklistDesc[]" id="evalFlavorOtherDesc3" value="Lactic" <?php if ($action == "edit") { if (strpos($row_eval['evalFlavorChecklistDesc'],"Lactic") !== false) echo "checked"; } ?>> Lactic
              </label>
              </div>
              <div class="checkbox">
              <label>
                  <input type="checkbox" name="evalFlavorChecklistDesc[]" id="evalFlavorOtherDesc4" value="Smoke" <?php if ($action == "edit") { if (strpos($row_eval['evalFlavorChecklistDesc'],"Smoke") !== false) echo "checked"; } ?>> Smoke
              </label>
              </div>
              <div class="checkbox">
              <label>
                  <input type="checkbox" name="evalFlavorChecklistDesc[]" id="evalFlavorOtherDesc5" value="Spice" <?php if ($action == "edit") { if (strpos($row_eval['evalFlavorChecklistDesc'],"Spice") !== false) echo "checked"; } ?>> Spice
              </label>
              </div>
              <div class="checkbox">
              <label>
                  <input type="checkbox" name="evalFlavorChecklistDesc[]" id="evalFlavorOtherDesc6" value="Vinous" <?php if ($action == "edit") { if (strpos($row_eval['evalFlavorChecklistDesc'],"Vinous") !== false) echo "checked"; } ?>> Vinous
              </label>
              </div>
              <div class="checkbox">
              <label>
                  <input type="checkbox" name="evalFlavorChecklistDesc[]" id="evalFlavorOtherDesc7" value="Wood" <?php if ($action == "edit") { if (strpos($row_eval['evalFlavorChecklistDesc'],"Wood") !== false) echo "checked"; } ?>> Wood
              </label>
              </div>
          </div>
      </div>
      <div class="col col-lg-2 col-md-2 col-sm-4 col-xs-6">
        <div class="form-group">
        <label for="flavorBalance">Balance</label>
            <div class="checkbox">
            <label>
                <input type="checkbox" name="evalFlavorChecklistDesc[]" id="evalFlavorBalanceDesc1" value="Malty Balance" <?php if ($action == "edit") { if (strpos($row_eval['evalFlavorChecklistDesc'],"Malty Balance") !== false) echo "checked"; } ?>> Malty
            </label>
            </div>
            <div class="checkbox">
            <label>
                <input type="checkbox" name="evalFlavorChecklistDesc[]" id="evalFlavorBalanceDesc2" value="Hoppy Balance" <?php if ($action == "edit") { if (strpos($row_eval['evalFlavorChecklistDesc'],"Hoppy Balance") !== false) echo "checked"; } ?>> Hoppy
            </label>
            </div>
            <div class="checkbox">
            <label>
                <input type="checkbox" name="evalFlavorChecklistDesc[]" id="evalFlavorBalanceDesc3" value="Even Balance" <?php if ($action == "edit") { if (strpos($row_eval['evalFlavorChecklistDesc'],"Even Balance") !== false) echo "checked"; } ?>> Even
            </label>
            </div>
        </div>
      </div>
    </div><!-- ./row -->
  	</div>
</div>
<!-- Mouthfeel -->
<h3 class="section-heading">Mouthfeel</h3>
<!-- Mouthfeel Checklist -->
<div class="form-horizontal">
  <div class="form-group">
    <label class="col-sm-3 control-label">Body</label>
    <div class="col-sm-9">
    	<label class="radio-inline">
          	<input type="radio" name="evalMouthfeelBody" id="evalMouthfeelBody1" value="No Body" data-error="Please choose a body descriptor." required <?php if ($action == "edit") { if (strpos($row_eval['evalMouthfeelChecklist'],"No Body") !== false) echo "checked"; } ?>> None
        </label>
        <label class="radio-inline">
          	<input type="radio" name="evalMouthfeelBody" id="evalMouthfeelBody2" value="Low Body" data-error="Please choose a body descriptor." required <?php if ($action == "edit") { if (strpos($row_eval['evalMouthfeelChecklist'],"Low Body") !== false) echo "checked"; } ?>> Low
        </label>
        <label class="radio-inline">
          	<input type="radio" name="evalMouthfeelBody" id="mevalMouthfeelBody3" value="Medium Body" data-error="Please choose a body descriptor." required <?php if ($action == "edit") { if (strpos($row_eval['evalMouthfeelChecklist'],"Medium Body") !== false) echo "checked"; } ?>> Medium
        </label>
        <label class="radio-inline">
          	<input type="radio" name="evalMouthfeelBody" id="evalMouthfeelBody4" value="High Body" data-error="Please choose a body descriptor." required <?php if ($action == "edit") { if (strpos($row_eval['evalMouthfeelChecklist'],"High Body") !== false) echo "checked"; } ?>> High
        </label>
        <div class="help-block small with-errors"></div>
  	</div>
  </div>
  <div class="form-group">
    <label class="col-sm-3 control-label">Carbonation</label>
    <div class="col-sm-9">
    	<label class="radio-inline">
          	<input type="radio" name="evalMouthfeelCarbonation" id="evalMouthfeelCarbonation1" value="No Carbonation" data-error="Please choose a carbonation descriptor." required <?php if ($action == "edit") { if (strpos($row_eval['evalMouthfeelChecklist'],"No Carbonation") !== false) echo "checked"; } ?>> None
        </label>
        <label class="radio-inline">
          	<input type="radio" name="evalMouthfeelCarbonation" id="evalMouthfeelCarbonation2" value="Low Carbonation" data-error="Please choose a carbonation descriptor." required <?php if ($action == "edit") { if (strpos($row_eval['evalMouthfeelChecklist'],"Low Carbonation") !== false) echo "checked"; } ?>> Low
        </label>
        <label class="radio-inline">
          	<input type="radio" name="evalMouthfeelCarbonation" id="evalMouthfeelCarbonation3" value="Medium Carbonation" data-error="Please choose a carbonation descriptor." required <?php if ($action == "edit") { if (strpos($row_eval['evalMouthfeelChecklist'],"Medium Carbonation") !== false) echo "checked"; } ?>> Medium
        </label>
        <label class="radio-inline">
          	<input type="radio" name="evalMouthfeelCarbonation" id="evalMouthfeelCarbonation4" value="High Carbonation" data-error="Please choose a carbonation descriptor." required <?php if ($action == "edit") { if (strpos($row_eval['evalMouthfeelChecklist'],"High Carbonation") !== false) echo "checked"; } ?>> High
        </label>
        <div class="help-block small with-errors"></div>
  	</div>
  </div>
  <div class="form-group">
    <label class="col-sm-3 control-label">Warmth</label>
    <div class="col-sm-9">
    	<label class="radio-inline">
          	<input type="radio" name="evalMouthfeelWarmth" id="evalMouthfeelWarmth1" value="No Warmth" data-error="Please choose warmth descriptor." required <?php if ($action == "edit") { if (strpos($row_eval['evalMouthfeelChecklist'],"No Warmth") !== false) echo "checked"; } ?>> None
        </label>
        <label class="radio-inline">
          	<input type="radio" name="evalMouthfeelWarmth" id="evalMouthfeelWarmth2" value="Low Warmth" data-error="Please choose warmth descriptor." required <?php if ($action == "edit") { if (strpos($row_eval['evalMouthfeelChecklist'],"Low Warmth") !== false) echo "checked"; } ?>> Low
        </label>
        <label class="radio-inline">
          	<input type="radio" name="evalMouthfeelWarmth" id="evalMouthfeelWarmth3" value="Medium Warmth" data-error="Please choose warmth descriptor." required <?php if ($action == "edit") { if (strpos($row_eval['evalMouthfeelChecklist'],"Medium Warmth") !== false) echo "checked"; } ?>> Medium
        </label>
        <label class="radio-inline">
          	<input type="radio" name="evalMouthfeelWarmth" id="evalMouthfeelWarmth3" value="High Warmth" data-error="Please choose a warmth descriptor." required <?php if ($action == "edit") { if (strpos($row_eval['evalMouthfeelChecklist'],"High Warmth") !== false) echo "checked"; } ?>> High
        </label>
        <div class="help-block small with-errors"></div>
  	</div>
  </div>
  <div class="form-group">
    <label class="col-sm-3 control-label">Creaminess</label>
    <div class="col-sm-9">
    	<label class="radio-inline">
          	<input type="radio" name="evalMouthfeelCreaminess" id="evalMouthfeelCreaminess1" value="No Creaminess" data-error="Please choose a creaminess descriptor." required <?php if ($action == "edit") { if (strpos($row_eval['evalMouthfeelChecklist'],"No Creaminess") !== false) echo "checked"; } ?>> None
        </label>
        <label class="radio-inline">
          	<input type="radio" name="evalMouthfeelCreaminess" id="evalMouthfeelCreaminess2" value="Low Creaminess" data-error="Please choose a creaminess descriptor." required <?php if ($action == "edit") { if (strpos($row_eval['evalMouthfeelChecklist'],"Low Creaminess") !== false) echo "checked"; } ?>> Low
        </label>
        <label class="radio-inline">
          	<input type="radio" name="evalMouthfeelCreaminess" id="evalMouthfeelCreaminess3" value="Medium Creaminess" data-error="Please choose a creaminess descriptor." required <?php if ($action == "edit") { if (strpos($row_eval['evalMouthfeelChecklist'],"Medium Creaminess") !== false) echo "checked"; } ?>> Medium
        </label>
        <label class="radio-inline">
          	<input type="radio" name="evalMouthfeelCreaminess" id="evalMouthfeelCreaminess4" value="High Creaminess" data-error="Please choose a creaminess descriptor." required <?php if ($action == "edit") { if (strpos($row_eval['evalMouthfeelChecklist'],"High Creaminess") !== false) echo "checked"; } ?>> High
        </label>
        <div class="help-block small with-errors"></div>
  	</div>
  </div>
  <div class="form-group">
    <label class="col-sm-3 control-label">Astringency</label>
    <div class="col-sm-9">
    	<label class="radio-inline">
          	<input type="radio" name="evalMouthfeelAstringency" id="evalMouthfeelAstringency1" value="No Astringency" data-error="Please choose an astringency descriptor." required <?php if ($action == "edit") { if (strpos($row_eval['evalMouthfeelChecklist'],"No Astringency") !== false) echo "checked"; } ?>> None
        </label>
        <label class="radio-inline">
          	<input type="radio" name="evalMouthfeelAstringency" id="evalMouthfeelAstringency2" value="Low Astringency" data-error="Please choose an astringency descriptor." required <?php if ($action == "edit") { if (strpos($row_eval['evalMouthfeelChecklist'],"Low Astringency") !== false) echo "checked"; } ?>> Low
        </label>
        <label class="radio-inline">
          	<input type="radio" name="evalMouthfeelAstringency" id="evalMouthfeelAstringency3" value="Medium Astringency" data-error="Please choose an astringency descriptor." required <?php if ($action == "edit") { if (strpos($row_eval['evalMouthfeelChecklist'],"Medium Astringency") !== false) echo "checked"; } ?>> Medium
        </label>
        <label class="radio-inline">
          	<input type="radio" name="evalMouthfeelAstringency" id="evalMouthfeelAstringency4" value="High Astringency" data-error="Please choose an astringency descriptor." required <?php if ($action == "edit") { if (strpos($row_eval['evalMouthfeelChecklist'],"High Astringency") !== false) echo "checked"; } ?>> High
        </label>
        <div class="help-block small with-errors"></div>
  	</div>
  </div>
</div>
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
<div class="form-group">
    <label for="evalMouthfeelComments">Mouthfeel Comments</label>
    <textarea class="form-control" name="evalMouthfeelComments" rows="6" placeholder="Brief comments relating to mouthfeel; 450 characters maximum." maxlength="450" data-error="Please provide some brief mouthfeel comments." required><?php if ($action == "edit") echo $row_eval['evalMouthfeelComments']; ?></textarea>
    <div class="help-block small with-errors"></div>
</div>
<!-- Mouthfeel Descriptors 
<div class="bcoem-admin-element">
	<button class="btn btn-info" type="button" data-toggle="collapse" data-target="#mouthfeelDescriptors" aria-expanded="false" aria-controls="mouthfeelDescriptors">Mouthfeel Descriptors (Select to Expand/Collapse)</button>
</div>
-->
<p><strong>Mouthfeel Descriptors</strong></p>
<div class="collapse in bcoem-admin-element" id="mouthfeelDescriptors">
  <div class="well">
    <div class="row">
      <div class="col-lg-2 col-md-2 col-sm-4 col-xs-6">
        <div class="form-group">
          <label for="mouthfeelFlaws">Flaws</label>
              <div class="checkbox">
              <label>
                  <input type="checkbox" name="evalMouthfeelChecklistDesc[]" id="evalMouthfeelFlawsDesc1" value="Flat" <?php if ($action == "edit") { if (strpos($row_eval['evalMouthfeelChecklistDesc'],"Flat") !== false) echo "checked"; } ?>> Flat
              </label>
              </div>
              <div class="checkbox">
              <label>
                  <input type="checkbox" name="evalMouthfeelChecklistDesc[]" id="evalMouthfeelFlawsDesc2" value="Gushed" <?php if ($action == "edit") { if (strpos($row_eval['evalMouthfeelChecklistDesc'],"Gushed") !== false) echo "checked"; } ?>> Gushed
              </label>
              </div>
              <div class="checkbox">
              <label>
                  <input type="checkbox" name="evalMouthfeelChecklistDesc[]" id="evalMouthfeelFlawslDesc3" value="Hot" <?php if ($action == "edit") { if (strpos($row_eval['evalMouthfeelChecklistDesc'],"Hot") !== false) echo "checked"; } ?>> Hot
              </label>
              </div>
              <div class="checkbox">
              <label>
                  <input type="checkbox" name="evalMouthfeelChecklistDesc[]" id="evalMouthfeelFlawsDesc4" value="Harsh" <?php if ($action == "edit") { if (strpos($row_eval['evalMouthfeelChecklistDesc'],"Harsh") !== false) echo "checked"; } ?>> Harsh
              </label>
              </div>
              <div class="checkbox">
              <label>
                  <input type="checkbox" name="evalMouthfeelChecklistDesc[]" id="evalMouthfeelFlawsDesc5" value="Slick" <?php if ($action == "edit") { if (strpos($row_eval['evalMouthfeelChecklistDesc'],"Slick") !== false) echo "checked"; } ?>> Slick
              </label>
              </div>
        </div>
      </div>
      <div class="col col-lg-2 col-md-2 col-sm-4 col-xs-6">
        <div class="form-group">
          <label for="mouthfeelFinish">Finish</label>
              <div class="checkbox">
              <label>
                  <input type="checkbox" name="evalMouthfeelChecklistDesc[]" id="evalMouthfeelFinishDesc1" value="Cloying Finish" <?php if ($action == "edit") { if (strpos($row_eval['evalMouthfeelChecklistDesc'],"Cloying Finish") !== false) echo "checked"; } ?>> Cloying
              </label>
              </div>
              <div class="checkbox">
              <label>
                  <input type="checkbox" name="evalMouthfeelChecklistDesc[]" id="evalMouthfeelFinishDesc2" value="Sweet Finish" <?php if ($action == "edit") { if (strpos($row_eval['evalMouthfeelChecklistDesc'],"Sweet Finish") !== false) echo "checked"; } ?>> Sweet
              </label>
              </div>
              <div class="checkbox">
              <label>
                  <input type="checkbox" name="evalMouthfeelChecklistDesc[]" id="evalMouthfeelFinishDesc3" value="Medium Finish" <?php if ($action == "edit") { if (strpos($row_eval['evalMouthfeelChecklistDesc'],"Medium Finish") !== false) echo "checked"; } ?>> Medium
              </label>
              </div>
              <div class="checkbox">
              <label>
                  <input type="checkbox" name="evalMouthfeelChecklistDesc[]" id="evalMouthfeelFinishDesc4" value="Dry Finish" <?php if ($action == "edit") { if (strpos($row_eval['evalMouthfeelChecklistDesc'],"Dry Finish") !== false) echo "checked"; } ?>> Dry
              </label>
              </div>
              <div class="checkbox">
              <label>
                  <input type="checkbox" name="evalMouthfeelChecklistDesc[]" id="evalMouthfeelFinishDesc5" value="Biting Finish" <?php if ($action == "edit") { if (strpos($row_eval['evalMouthfeelChecklistDesc'],"Biting Finish") !== false) echo "checked"; } ?>> Biting
              </label>
              </div>
        </div>
      </div>
    </div>
  	</div>
</div>
<!-- Overall Impression -->
<h3 class="section-heading">Overall Impression</h3>
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


<!-- Style Accuracy -->
    <div class="form-group">
        <div class="row">
          <div class="col-sm-3 col-xs-12">
            <label for="evalStyleAccuracy">Stylistic Accuracy</label>
          </div>
          <div class="col-sm-9 col-xs-12">
            <input class="form-control" type="text" name="evalStyleAccuracy" data-provide="slider" data-slider-ticks="[1, 2, 3, 4, 5]" data-slider-ticks-labels='["low", "", "medium", "", "high"]' data-slider-min="1" data-slider-max="5" data-slider-step="1" data-slider-value="<?php if ($action == "edit") echo $row_eval['evalStyleAccuracy']; ?>" data-slider-tooltip="show">
          </div>
        </div>
        <div class="help-block small with-errors"></div>
  	</div>

<!-- Technical Merit -->
    <div class="form-group">
        <div class="row">
          <div class="col-sm-3 col-xs-12">
            <label for="evalTechMerit">Technical Merit</label>
          </div>
          <div class="col-sm-9 col-xs-12">
            <input class="form-control" type="text" name="evalTechMerit" data-provide="slider" data-slider-ticks="[1, 2, 3, 4, 5]" data-slider-ticks-labels='["low", "", "medium", "", "high"]' data-slider-min="1" data-slider-max="5" data-slider-step="1" data-slider-value="<?php if ($action == "edit") echo $row_eval['evalTechMerit']; ?>" data-slider-tooltip="show">
          </div>
        </div>
        <div class="help-block small with-errors"></div>
    </div>

 <!-- Intangibles -->
<div class="form-group">
    <div class="row">
      <div class="col-sm-3 col-xs-12">
        <label for="evalIntangibles">Intangibles</label>
      </div>
      <div class="col-sm-9 col-xs-12">
        <input class="form-control" type="text" name="evalIntangibles" data-provide="slider" data-slider-ticks="[1, 2, 3, 4, 5]" data-slider-ticks-labels='["low", "", "medium", "", "high"]' data-slider-min="1" data-slider-max="5" data-slider-step="1" data-slider-value="<?php if ($action == "edit") echo $row_eval['evalIntangibles']; ?>" data-slider-tooltip="show">
      </div>
    </div>
    <div class="help-block small with-errors"></div>
</div>

<!-- Drinkability -->
<div class="form-group">
    <div class="row">
      <div class="col-sm-3 col-xs-12">
        <label for="evalDrinkability">Drinkability</label>
      </div>
      <div class="col-sm-9 col-xs-12">
        <div class="radio">
            <label>
                <input type="radio" name="evalDrinkability" id="evalDrinkability0" data-error="Please choose a drinkability descriptor." value="I did not finish this sample." required <?php if (($action == "edit") && ($row_eval['evalDrinkability'] == "I did not finish this sample.")) echo "checked"; ?>> I did not finish this sample.
            </label>
        </div>
        <div class="radio">
            <label>
                <input type="radio" name="evalDrinkability" id="evalDrinkability1" data-error="Please choose a drinkability descriptor." value="I would finish this sample." required <?php if (($action == "edit") && ($row_eval['evalDrinkability'] == "I would finish this sample.")) echo "checked"; ?>> I would finish this sample.
            </label>
        </div>
        <div class="radio">
            <label>
                <input type="radio" name="evalDrinkability" id="evalDrinkability2" data-error="Please choose a drinkability descriptor." value="I would drink a pint of this beer." required <?php if (($action == "edit") && ($row_eval['evalDrinkability'] == "I would drink a pint of this beer.")) echo "checked"; ?>> I would drink a pint of this beer.
            </label>
        </div>
        <div class="radio">
            <label>
                <input type="radio" name="evalDrinkability" id="evalDrinkability3" data-error="Please choose a drinkability descriptor." value="I would pay money for this beer." required <?php if (($action == "edit") && ($row_eval['evalDrinkability'] == "I would pay money for this beer.")) echo "checked"; ?>> I would pay money for this beer.
            </label>
        </div>
        <div class="radio">
            <label>
                <input type="radio" name="evalDrinkability" id="evalDrinkability4" data-error="Please choose a drinkability descriptor." value="I would recommend this beer." required <?php if (($action == "edit") && ($row_eval['evalDrinkability'] == "I would recommend this beer.")) echo "checked"; ?>> I would recommend this beer.
            </label>
        </div>
        <div class="help-block small with-errors"></div>
      </div>
    </div>
</div>
<div class="form-group">
    <label for="evalOverallComments">Overall Impression Comments</label>
    <textarea class="form-control" name="evalOverallComments" rows="6" placeholder="Brief comments relating to your overall impression; 800 characters maximum." maxlength="800" data-error="Please provide some brief overall comments." required><?php if ($action == "edit") echo $row_eval['evalOverallComments']; ?></textarea>
    <div class="help-block small with-errors"></div>
</div>

<!-- Flaws -->
<h3 class="section-heading">Flaws</h3>
<?php foreach ($flaws as $flaw) { ?>
<div class="form-group">
  <div class="row">
    <div class="col col-sm-3">
        <label for="evalFlaws"><?php echo $flaw; ?></label>
    </div>
    <div class="col col-sm-3">
        <div class="checkbox">
            <label>
                <input type="checkbox" name="evalFlaws[]" id="evalFlaws<?php echo $flaw; ?>" value="<?php echo $flaw; ?> Aroma" <?php if ($action == "edit") { if (strpos($row_eval['evalFlaws'],$flaw." Aroma") !== false) echo "checked"; } ?>> Aroma
            </label>
        </div>
    </div>
    <div class="col col-sm-3">
        <div class="checkbox">
            <label>
                <input type="checkbox" name="evalFlaws[]" id="evalFlaws<?php echo $flaw; ?>" value="<?php echo $flaw; ?> Flavor" <?php if ($action == "edit") { if (strpos($row_eval['evalFlaws'],$flaw." Flavor") !== false) echo "checked"; } ?>> Flavor
            </label>
        </div>
    </div>
    <div class="col col-sm-3">
        <?php if (in_array($flaw, $flaws_mouthfeel)) { ?>
        <div class="checkbox">
            <label>
                <input type="checkbox" name="evalFlaws[]" id="evalFlaws<?php echo $flaw; ?>" value="<?php echo $flaw; ?> Mouthfeel" <?php if ($action == "edit") { if (strpos($row_eval['evalFlaws'],$flaw." Mouthfeel") !== false) echo "checked"; } ?>> Mouthfeel
            </label>
        </div>
      <?php } ?>
    </div>
  </div>
</div>
<?php } ?>