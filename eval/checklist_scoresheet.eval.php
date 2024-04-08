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
$checklist_factors = array($label_none,$label_low,$label_med,$label_high); 

$cl_ar_desc_malt = array($label_grainy,$label_caramel,$label_bready,$label_rich,$label_dark_fruit,$label_toasty,$label_roasty,$label_burnt);
$cl_ar_desc_hops = array($label_citrusy,$label_earthy,$label_floral,$label_grassy,$label_herbal,$label_piney,$label_spicy,$label_woody);
$cl_ar_desc_esters = array($label_fruity,$label_apple_pear,$label_banana,$label_berry,$label_citrus,$label_dried_fruit,$label_grape,$label_stone_fruit);
$cl_ar_desc_other  = array($label_brettanomyces,$label_fruit,$label_lactic,$label_smoke,$label_spice,$label_vinous,$label_wood);

$cl_ap_desc_color = array($label_straw,$label_yellow,$label_gold,$label_amber,$label_copper,$label_brown,$label_black);
$cl_ap_desc_head  = array($label_white,$label_ivory,$label_cream,$label_beige,$label_tan,$label_brown);
$cl_ap_desc_other = array($label_flat,$label_lacing,$label_legs,$label_opaque);

$cl_fl_desc_malt = array($label_grainy,$label_caramel,$label_bready,$label_rich,$label_dark_fruit,$label_toasty,$label_roasty,$label_burnt);
$cl_fl_desc_hops = array($label_citrusy,$label_earthy,$label_floral,$label_grassy,$label_herbal,$label_piney,$label_spicy,$label_woody);
$cl_fl_desc_esters = array($label_fruity,$label_apple_pear,$label_banana,$label_berry,$label_citrus,$label_dried_fruit,$label_grape,$label_stone_fruit);
$cl_fl_desc_other = array($label_brettanomyces,$label_fruit,$label_lactic,$label_smoke,$label_spice,$label_vinous,$label_wood);
$cl_fl_desc_bal = array($label_malty,$label_hoppy,$label_even);

$cl_mf_desc_flaws = array($label_flat,$label_gushed,$label_hot,$label_harsh,$label_slick);
$cl_mf_desc_finish = array($label_cloying,$label_sweet,$label_med,$label_dry,$label_biting);
?>
<!-- Checklist Evaluation Form -->
<input type="hidden" name="evalFormType" value="2">
<!-- Aroma Checklist -->
<h3 class="section-heading"><?php echo $label_aroma; ?></h3>
<div class="form-horizontal">
  <div class="form-group">
    <label class="col-sm-3 control-label"><?php echo $label_malt; ?></label>
    <div class="col-sm-9">
        <?php 
        $id_value = 1; 
        foreach($checklist_factors as $value) { 
        $cl_value = $label_malt.": ".$value;
        ?>
        <label class="radio-inline">
            <input type="radio" name="evalAromaMalt" id="evalAromaMalt<?php echo $id_value++; ?>" value="<?php echo $cl_value; ?>" data-error="<?php echo $evaluation_info_062; ?>" required <?php if ($action == "edit") { if (strpos($row_eval['evalAromaChecklist'],$cl_value) !== false) echo "checked"; } ?>> <?php echo $value; ?>
        </label>
        <?php } ?>
        <div class="help-block small with-errors"></div>
  	</div>
  </div>
  <div class="form-group">
    <label class="col-sm-3 control-label"><?php echo $label_hops; ?></label>
    <div class="col-sm-9">
    	<?php 
        $id_value = 1; 
        foreach($checklist_factors as $value) { 
        $cl_value = $label_hops.": ".$value;
        ?>
        <label class="radio-inline">
            <input type="radio" name="evalAromaHops" id="evalAromaHops<?php echo $id_value++; ?>" value="<?php echo $cl_value; ?>" data-error="<?php echo $evaluation_info_062; ?>" required <?php if ($action == "edit") { if (strpos($row_eval['evalAromaChecklist'],$cl_value) !== false) echo "checked"; } ?>> <?php echo $value; ?>
        </label>
        <?php } ?>
        <div class="help-block small with-errors"></div>
  	</div>
  </div>
  <div class="form-group">
    <label class="col-sm-3 control-label"><?php echo $label_esters; ?></label>
    <div class="col-sm-9">
    	<?php
        $id_value = 1; 
        foreach($checklist_factors as $value) { 
        $cl_value = $label_esters.": ".$value;
        ?>
        <label class="radio-inline">
            <input type="radio" name="evalAromaEsters" id="evalAromaEsters<?php echo $id_value++; ?>" value="<?php echo $cl_value; ?>" data-error="<?php echo $evaluation_info_062; ?>" required <?php if ($action == "edit") { if (strpos($row_eval['evalAromaChecklist'],$cl_value) !== false) echo "checked"; } ?>> <?php echo $value; ?>
        </label>
        <?php } ?>
        <div class="help-block small with-errors"></div>
  	</div>
  </div>
  <div class="form-group">
    <label class="col-sm-3 control-label"><?php echo $label_phenols; ?></label>
    <div class="col-sm-9">
    	<?php 
        $id_value = 1; 
        foreach($checklist_factors as $value) { 
        $cl_value = $label_phenols.": ".$value;
        ?>
        <label class="radio-inline">
            <input type="radio" name="evalAromaPhenols" id="evalAromaPhenols<?php echo $id_value++; ?>" value="<?php echo $cl_value; ?>" data-error="<?php echo $evaluation_info_062; ?>" required <?php if ($action == "edit") { if (strpos($row_eval['evalAromaChecklist'],$cl_value) !== false) echo "checked"; } ?>> <?php echo $value; ?>
        </label>
        <?php } ?>
        <div class="help-block small with-errors"></div>
  	</div>
  </div>
  <div class="form-group">
    <label class="col-sm-3 control-label"><?php echo $label_alcohol; ?></label>
    <div class="col-sm-9">
    	<?php 
        $id_value = 1; 
        foreach($checklist_factors as $value) { 
        $cl_value = $label_alcohol.": ".$value;
        ?>
        <label class="radio-inline">
            <input type="radio" name="evalAromaAlcohol" id="evalAromaAlcohol<?php echo $id_value++; ?>" value="<?php echo $cl_value; ?>" data-error="<?php echo $evaluation_info_062; ?>" required <?php if ($action == "edit") { if (strpos($row_eval['evalAromaChecklist'],$cl_value) !== false) echo "checked"; } ?>> <?php echo $value; ?>
        </label>
        <?php } ?>
        <div class="help-block small with-errors"></div>
  	</div>
  </div>
  <div class="form-group">
    <label class="col-sm-3 control-label"><?php echo $label_sweetness; ?></label>
    <div class="col-sm-9">
    	<?php
        $id_value = 1; 
        foreach($checklist_factors as $value) { 
        $cl_value = $label_sweetness.": ".$value; 
        ?>
        <label class="radio-inline">
            <input type="radio" name="evalAromaSweetness" id="evalAromaSweetness<?php echo $id_value++; ?>" value="<?php echo $cl_value; ?>" data-error="<?php echo $evaluation_info_062; ?>" required <?php if ($action == "edit") { if (strpos($row_eval['evalAromaChecklist'],$cl_value) !== false) echo "checked"; } ?>> <?php echo $value; ?>
        </label>
        <?php } ?>
        <div class="help-block small with-errors"></div>
  	</div>
  </div>
  <div class="form-group">
    <label class="col-sm-3 control-label"><?php echo $label_acidity; ?></label>
    <div class="col-sm-9">
    	<?php 
        $id_value = 1; 
        foreach($checklist_factors as $value) { 
        $cl_value = $label_acidity.": ".$value;
        ?>
        <label class="radio-inline">
            <input type="radio" name="evalAroma<?php echo $label_acidity; ?>" id="evalAromaAcidity<?php echo $id_value++; ?>" value="<?php echo $cl_value; ?>" data-error="<?php echo $evaluation_info_062; ?>" required <?php if ($action == "edit") { if (strpos($row_eval['evalAromaChecklist'],$cl_value) !== false) echo "checked"; } ?>> <?php echo $value; ?>
        </label>
        <?php } ?>
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
    <textarea class="form-control" id="evalAromaComments" name="evalAromaComments" rows="6" placeholder="" data-error="<?php echo $evaluation_info_061; ?>" required><?php if ($action == "edit") echo htmlentities($row_eval['evalAromaComments']); ?></textarea>
    <div class="help-block small with-errors"></div>
    <div class="help-block small" id="evalAromaComments-words"></div>
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
        <label for="evalAromaMalt"><?php echo $label_malt; ?></label>
            <?php 
            $id_value = 1; 
            foreach($cl_ar_desc_malt as $value) { 
            $cl_value = $label_malt.": ".$value;
            ?>
            <div class="checkbox">
            <label>
                <input type="checkbox" name="evalAromaChecklistDesc[]" id="evalAromaMaltDesc<?php echo $id_value++; ?>" value="<?php echo $cl_value; ?>" <?php if ($action == "edit") { if (strpos($row_eval['evalAromaChecklistDesc'],$cl_value) !== false) echo "checked"; } ?>> <?php echo $value; ?>
            </label>
            </div>
            <?php } ?>
      </div>
    </div>
    <div class="col col-lg-2 col-md-2 col-sm-4 col-xs-6">
      <div class="form-group">
        <label for="evalAromaHops"><?php echo $label_hops; ?></label>
            <?php 
            foreach($cl_ar_desc_hops as $value) { 
            $cl_value = $label_hops.": ".$value;
            ?>
            <div class="checkbox">
            <label>
                <input type="checkbox" name="evalAromaChecklistDesc[]" id="evalAromaHopsDesc<?php echo $id_value++; ?>" value="<?php echo $cl_value; ?>" <?php if ($action == "edit") { if (strpos($row_eval['evalAromaChecklistDesc'],$cl_value) !== false) echo "checked"; } ?>> <?php echo $value; ?>
            </label>
            </div>
            <?php } ?>
      </div>
    </div>
    <div class="col col-lg-2 col-md-2 col-sm-4 col-xs-6">
      <div class="form-group">
        <label for="evalAromaEsters"><?php echo $label_esters; ?></label>
            <?php  
            foreach($cl_ar_desc_esters as $value) { 
            $cl_value = $label_esters.": ".$value;
            ?>
            <div class="checkbox">
            <label>
                <input type="checkbox" name="evalAromaChecklistDesc[]" id="evalAromaEstersDesc<?php echo $id_value++; ?>" value="<?php echo $cl_value; ?>" <?php if ($action == "edit") { if (strpos($row_eval['evalAromaChecklistDesc'],$cl_value) !== false) echo "checked"; } ?>> <?php echo $value; ?>
            </label>
            </div>
            <?php } ?>
        </div>
    </div>
    <div class="col col-lg-2 col-md-2 col-sm-4 col-xs-6">
      <div class="form-group">
        <label for="evalAromaOther"><?php echo $label_other; ?></label>
            <?php 
            foreach($cl_ar_desc_other as $value) { 
            $cl_value = $label_other.": ".$value;
            ?>
            <div class="checkbox">
            <label>
                <input type="checkbox" name="evalAromaChecklistDesc[]" id="evalAromaOtherDesc<?php echo $id_value++; ?>" value="<?php echo $cl_value; ?>" <?php if ($action == "edit") { if (strpos($row_eval['evalAromaChecklistDesc'],$cl_value) !== false) echo "checked"; } ?>> <?php echo $value; ?>
            </label>
            </div>
            <?php } ?>
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
        <?php 
        $id_value = 1; 
        foreach($checklist_factors as $value) { 
        $cl_value = $label_clarity.": ".$value;
        ?>
        <label class="radio-inline">
            <input type="radio" name="evalAppearanceClarity" id="evalAppearanceClarity<?php echo $id_value++; ?>" value="<?php echo $cl_value; ?>" data-error="<?php echo $evaluation_info_062; ?>" required <?php if ($action == "edit") { if (strpos($row_eval['evalAppearanceChecklist'],$cl_value) !== false) echo "checked"; } ?>> <?php echo $value; ?>
        </label>
        <?php } ?>
        <div class="help-block small with-errors"></div>
  	</div>
  </div>
  <div class="form-group">
    <label class="col-sm-3 control-label"><?php echo $label_head_size; ?></label>
    <div class="col-sm-9">
        <?php 
        $id_value = 1; 
        foreach($checklist_factors as $value) { 
        $cl_value = $label_head_size.": ".$value;
        ?>
        <label class="radio-inline">
            <input type="radio" name="evalAppearanceHeadSize" id="evalAppearanceHeadSize<?php echo $id_value++; ?>" value="<?php echo $cl_value; ?>" data-error="<?php echo $evaluation_info_062; ?>" required <?php if ($action == "edit") { if (strpos($row_eval['evalAppearanceChecklist'],$cl_value) !== false) echo "checked"; } ?>> <?php echo $value; ?>
        </label>
        <?php } ?>
        <div class="help-block small with-errors"></div>
  	</div>
  </div>
  <div class="form-group">
    <label class="col-sm-3 control-label"><?php echo $label_head_retention; ?></label>
    <div class="col-sm-9">
        <?php 
        $id_value = 1; 
        foreach($checklist_factors as $value) { 
        $cl_value = $label_head_retention.": ".$value;
        ?>
        <label class="radio-inline">
            <input type="radio" name="evalAppearanceHeadRetention" id="evalAppearanceHeadRetention<?php echo $id_value++; ?>" value="<?php echo $cl_value; ?>" data-error="<?php echo $evaluation_info_062; ?>" required <?php if ($action == "edit") { if (strpos($row_eval['evalAppearanceChecklist'],$cl_value) !== false) echo "checked"; } ?>> <?php echo $value; ?>
        </label>
        <?php } ?>
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
    <textarea class="form-control" id="evalAppearanceComments" name="evalAppearanceComments" rows="6" placeholder="" data-error="<?php echo $evaluation_info_061; ?>" required><?php if ($action == "edit") echo htmlentities($row_eval['evalAppearanceComments']); ?></textarea>
    <div class="help-block small with-errors"></div>
    <div class="help-block small" id="evalAppearanceComments-words"></div>
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
            <?php 
            $id_value = 1; 
            foreach($cl_ap_desc_color as $value) { 
            $cl_value = $label_color.": ".$value;
            ?>
            <div class="checkbox">
            <label>
                <input type="checkbox" name="evalAppearanceChecklistDesc[]" id="evalAppearanceColorDesc<?php echo $id_value++; ?>" value="<?php echo $cl_value; ?>" <?php if ($action == "edit") { if (strpos($row_eval['evalAppearanceChecklistDesc'],$cl_value) !== false) echo "checked"; } ?>> <?php echo $value; ?>
            </label>
            </div>
            <?php } ?>
        </div>
      </div>
      <div class="col col-lg-2 col-md-2 col-sm-4 col-xs-6">
        <div class="form-group">
        <label for="appearanceHeadColor"><?php echo $label_head_color; ?></label>
            <?php 
            foreach($cl_ap_desc_head as $value) { 
            $cl_value = $label_head_color.": ".$value;
            ?>
            <div class="checkbox">
            <label>
                <input type="checkbox" name="evalAppearanceChecklistDesc[]" id="evalAppearanceHeadDesc<?php echo $id_value++; ?>" value="<?php echo $cl_value; ?>" <?php if ($action == "edit") { if (strpos($row_eval['evalAppearanceChecklistDesc'],$cl_value) !== false) echo "checked"; } ?>> <?php echo $value; ?>
            </label>
            </div>
            <?php } ?>
        </div>
      </div>
      <div class="col col-lg-2 col-md-2 col-sm-4 col-xs-6">
        <div class="form-group">
        <label for="appearanceOther"><?php echo $label_other; ?></label>
            <?php 
            foreach($cl_ap_desc_other as $value) { 
            $cl_value = $label_other.": ".$value;
            ?>
            <div class="checkbox">
            <label>
                <input type="checkbox" name="evalAppearanceChecklistDesc[]" id="evalAppearanceOtherDesc<?php echo $id_value++; ?>" value="<?php echo $cl_value; ?>" <?php if ($action == "edit") { if (strpos($row_eval['evalAppearanceChecklistDesc'],$cl_value) !== false) echo "checked"; } ?>> <?php echo $value; ?>
            </label>
            </div>
            <?php } ?>
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
        <?php 
        $id_value = 1; 
        foreach($checklist_factors as $value) { 
        $cl_value = $label_malt.": ".$value;
        ?>
        <label class="radio-inline">
            <input type="radio" name="evalFlavorMalt" id="evalFlavorMalt<?php echo $id_value++; ?>" value="<?php echo $cl_value; ?>" data-error="<?php echo $evaluation_info_062; ?>" required <?php if ($action == "edit") { if (strpos($row_eval['evalFlavorChecklist'],$cl_value) !== false) echo "checked"; } ?>> <?php echo $value; ?>
        </label>
        <?php } ?>
        <div class="help-block small with-errors"></div>
  	</div>
  </div>
  <div class="form-group">
    <label class="col-sm-3 control-label"><?php echo $label_hops; ?></label>
    <div class="col-sm-9">
        <?php 
        $id_value = 1; 
        foreach($checklist_factors as $value) { 
        $cl_value = $label_hops.": ".$value;
        ?>
        <label class="radio-inline">
            <input type="radio" name="evalFlavorHops" id="evalFlavorHops<?php echo $id_value++; ?>" value="<?php echo $cl_value; ?>" data-error="<?php echo $evaluation_info_062; ?>" required <?php if ($action == "edit") { if (strpos($row_eval['evalFlavorChecklist'],$cl_value) !== false) echo "checked"; } ?>> <?php echo $value; ?>
        </label>
        <?php } ?>
        <div class="help-block small with-errors"></div>
  	</div>
  </div>
  <div class="form-group">
    <label class="col-sm-3 control-label"><?php echo $label_esters; ?></label>
    <div class="col-sm-9">
    	<?php 
        $id_value = 1; 
        foreach($checklist_factors as $value) { 
        $cl_value = $label_esters.": ".$value;
        ?>
        <label class="radio-inline">
            <input type="radio" name="evalFlavorEsters" id="evalFlavorEsters<?php echo $id_value++; ?>" value="<?php echo $cl_value; ?>" data-error="<?php echo $evaluation_info_062; ?>" required <?php if ($action == "edit") { if (strpos($row_eval['evalFlavorChecklist'],$cl_value) !== false) echo "checked"; } ?>> <?php echo $value; ?>
        </label>
        <?php } ?>
        <div class="help-block small with-errors"></div>
  	</div>
  </div>
  <div class="form-group">
    <label class="col-sm-3 control-label"><?php echo $label_phenols; ?></label>
    <div class="col-sm-9">
        <?php 
        $id_value = 1; 
        foreach($checklist_factors as $value) { 
        $cl_value = $label_phenols.": ".$value;
        ?>
        <label class="radio-inline">
            <input type="radio" name="evalFlavorPhenols" id="evalFlavorPhenols<?php echo $id_value++; ?>" value="<?php echo $cl_value; ?>" data-error="<?php echo $evaluation_info_062; ?>" required <?php if ($action == "edit") { if (strpos($row_eval['evalFlavorChecklist'],$cl_value) !== false) echo "checked"; } ?>> <?php echo $value; ?>
        </label>
        <?php } ?>
        <div class="help-block small with-errors"></div>
  	</div>
  </div>
   <div class="form-group">
    <label class="col-sm-3 control-label"><?php echo $label_sweetness; ?></label>
    <div class="col-sm-9">
    	<?php 
        $id_value = 1; 
        foreach($checklist_factors as $value) { 
        $cl_value = $label_sweetness.": ".$value;
        ?>
        <label class="radio-inline">
            <input type="radio" name="evalFlavorSweetness" id="evalFlavorSweetness<?php echo $id_value++; ?>" value="<?php echo $cl_value; ?>" data-error="<?php echo $evaluation_info_062; ?>" required <?php if ($action == "edit") { if (strpos($row_eval['evalFlavorChecklist'],$cl_value) !== false) echo "checked"; } ?>> <?php echo $value; ?>
        </label>
        <?php } ?>
        <div class="help-block small with-errors"></div>
  	</div>
  </div>
  <div class="form-group">
    <label class="col-sm-3 control-label"><?php echo $label_bitterness; ?></label>
    <div class="col-sm-9">
    	<?php 
        $id_value = 1; 
        foreach($checklist_factors as $value) { 
        $cl_value = $label_bitterness.": ".$value;
        ?>
        <label class="radio-inline">
            <input type="radio" name="evalFlavorBitterness" id="evalFlavorBitterness<?php echo $id_value++; ?>" value="<?php echo $cl_value; ?>" data-error="<?php echo $evaluation_info_062; ?>" required <?php if ($action == "edit") { if (strpos($row_eval['evalFlavorChecklist'],$cl_value) !== false) echo "checked"; } ?>> <?php echo $value; ?>
        </label>
        <?php } ?>
        <div class="help-block small with-errors"></div>
  	</div>
  </div>
  <div class="form-group">
    <label class="col-sm-3 control-label"><?php echo $label_alcohol; ?></label>
    <div class="col-sm-9">
    	<?php 
        $id_value = 1; 
        foreach($checklist_factors as $value) { 
        $cl_value = $label_alcohol.": ".$value;
        ?>
        <label class="radio-inline">
            <input type="radio" name="evalFlavorAlcohol" id="evalFlavorAlcohol<?php echo $id_value++; ?>" value="<?php echo $cl_value; ?>" data-error="<?php echo $evaluation_info_062; ?>" required <?php if ($action == "edit") { if (strpos($row_eval['evalFlavorChecklist'],$cl_value) !== false) echo "checked"; } ?>> <?php echo $value; ?>
        </label>
        <?php } ?>
        <div class="help-block small with-errors"></div>
  	</div>
  </div>
  <div class="form-group">
    <label class="col-sm-3 control-label"><?php echo $label_acidity; ?></label>
    <div class="col-sm-9">
    	<?php 
        $id_value = 1; 
        foreach($checklist_factors as $value) { 
        $cl_value = $label_acidity.": ".$value;
        ?>
        <label class="radio-inline">
            <input type="radio" name="evalFlavorAcidity" id="evalFlavorAcidity<?php echo $id_value++; ?>" value="<?php echo $cl_value; ?>" data-error="<?php echo $evaluation_info_062; ?>" required <?php if ($action == "edit") { if (strpos($row_eval['evalFlavorChecklist'],$cl_value) !== false) echo "checked"; } ?>> <?php echo $value; ?>
        </label>
        <?php } ?>
        <div class="help-block small with-errors"></div>
  	</div>
  </div>
  <div class="form-group">
    <label class="col-sm-3 control-label"><?php echo $label_harshness; ?></label>
    <div class="col-sm-9">
    	<?php 
        $id_value = 1; 
        foreach($checklist_factors as $value) { 
        $cl_value = $label_harshness.": ".$value;
        ?>
        <label class="radio-inline">
            <input type="radio" name="evalFlavorHarshness" id="evalFlavorHarshness<?php echo $id_value++; ?>" value="<?php echo $cl_value; ?>" data-error="<?php echo $evaluation_info_062; ?>" required <?php if ($action == "edit") { if (strpos($row_eval['evalFlavorChecklist'],$cl_value) !== false) echo "checked"; } ?>> <?php echo $value; ?>
        </label>
        <?php } ?>
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
    <textarea class="form-control" id="evalFlavorComments" name="evalFlavorComments" rows="6" placeholder="" data-error="<?php echo $evaluation_info_062; ?>" required><?php if ($action == "edit") echo htmlentities($row_eval['evalFlavorComments']); ?></textarea>
    <div class="help-block small with-errors"></div>
    <div class="help-block small" id="evalFlavorComments-words"></div>
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
            <?php 
            $id_value = 1; 
            foreach($cl_fl_desc_malt as $value) { 
            $cl_value = $label_malt.": ".$value;
            ?>
            <div class="checkbox">
            <label>
                <input type="checkbox" name="evalFlavorChecklistDesc[]" id="evalFlavorMaltDesc<?php echo $id_value++; ?>" value="<?php echo $cl_value; ?>" <?php if ($action == "edit") { if (strpos($row_eval['evalFlavorChecklistDesc'],$cl_value) !== false) echo "checked"; } ?>> <?php echo $value; ?>
            </label>
            </div>
            <?php } ?>
        </div>
      </div>
      <div class="col col-lg-2 col-md-2 col-sm-4 col-xs-6">
        <div class="form-group">
          <label for="flavorHops"><?php echo $label_hops; ?></label>
            <?php 
            foreach($cl_fl_desc_hops as $value) { 
            $cl_value = $label_hops.": ".$value;
            ?>
            <div class="checkbox">
            <label>
                <input type="checkbox" name="evalFlavorChecklistDesc[]" id="evalFlavorHopsDesc<?php echo $id_value++; ?>" value="<?php echo $cl_value; ?>" <?php if ($action == "edit") { if (strpos($row_eval['evalFlavorChecklistDesc'],$cl_value) !== false) echo "checked"; } ?>> <?php echo $value; ?>
            </label>
            </div>
            <?php } ?>
        </div>
      </div>
      <div class="col col-lg-2 col-md-2 col-sm-4 col-xs-6">
        <div class="form-group">
          <label for="flavorEsters"><?php echo $label_esters; ?></label>
            <?php 
            foreach($cl_fl_desc_esters as $value) { 
            $cl_value = $label_esters.": ".$value;
            ?>
            <div class="checkbox">
            <label>
                <input type="checkbox" name="evalFlavorChecklistDesc[]" id="evalFlavorEstersDesc<?php echo $id_value++; ?>" value="<?php echo $cl_value; ?>" <?php if ($action == "edit") { if (strpos($row_eval['evalFlavorChecklistDesc'],$cl_value) !== false) echo "checked"; } ?>> <?php echo $value; ?>
            </label>
            </div>
            <?php } ?>
        </div>
      </div>
      <div class="col col-lg-2 col-md-2 col-sm-4 col-xs-6">
        <div class="form-group">
          <label for="flavorOther"><?php echo $label_other; ?></label>
            <?php 
            foreach($cl_fl_desc_other as $value) { 
            $cl_value = $label_other.": ".$value;
            ?>
            <div class="checkbox">
            <label>
                <input type="checkbox" name="evalFlavorChecklistDesc[]" id="evalFlavorOtherDesc<?php echo $id_value++; ?>" value="<?php echo $cl_value; ?>" <?php if ($action == "edit") { if (strpos($row_eval['evalFlavorChecklistDesc'],$cl_value) !== false) echo "checked"; } ?>> <?php echo $value; ?>
            </label>
            </div>
            <?php } ?>
        </div>
      </div>
      <div class="col col-lg-2 col-md-2 col-sm-4 col-xs-6">
        <div class="form-group">
        <label for="flavorBalance"><?php echo $label_balance; ?></label>
            <?php 
            foreach($cl_fl_desc_bal as $value) { 
            $cl_value = $label_balance.": ".$value;
            ?>
            <div class="checkbox">
            <label>
                <input type="checkbox" name="evalFlavorChecklistDesc[]" id="evalFlavorBalanceDesc<?php echo $id_value++; ?>" value="<?php echo $cl_value; ?>" <?php if ($action == "edit") { if (strpos($row_eval['evalFlavorChecklistDesc'],$cl_value) !== false) echo "checked"; } ?>> <?php echo $value; ?>
            </label>
            </div>
            <?php } ?>
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
        <?php 
        $id_value = 1; 
        foreach($checklist_factors as $value) { 
        $cl_value = $label_body.": ".$value;
        ?>
        <label class="radio-inline">
            <input type="radio" name="evalMouthfeelBody" id="evalMouthfeelBody<?php echo $id_value++; ?>" value="<?php echo $cl_value; ?>" data-error="<?php echo $evaluation_info_062; ?>" required <?php if ($action == "edit") { if (strpos($row_eval['evalMouthfeelChecklist'],$cl_value) !== false) echo "checked"; } ?>> <?php echo $value; ?>
        </label>
        <?php } ?>
        <div class="help-block small with-errors"></div>
  	</div>
  </div>
  <div class="form-group">
    <label class="col-sm-3 control-label"><?php echo $label_carbonation; ?></label>
    <div class="col-sm-9">
        <?php 
        $id_value = 1; 
        foreach($checklist_factors as $value) { 
        $cl_value = $label_carbonation.": ".$value;
        ?>
        <label class="radio-inline">
            <input type="radio" name="evalMouthfeelCarbonation" id="evalMouthfeelCarbonation<?php echo $id_value++; ?>" value="<?php echo $cl_value; ?>" data-error="<?php echo $evaluation_info_062; ?>" required <?php if ($action == "edit") { if (strpos($row_eval['evalMouthfeelChecklist'],$cl_value) !== false) echo "checked"; } ?>> <?php echo $value; ?>
        </label>
        <?php } ?>
        <div class="help-block small with-errors"></div>
  	</div>
  </div>
  <div class="form-group">
    <label class="col-sm-3 control-label"><?php echo $label_warmth; ?></label>
    <div class="col-sm-9">
        <?php 
        $id_value = 1; 
        foreach($checklist_factors as $value) { 
        $cl_value = $label_warmth.": ".$value;
        ?>
        <label class="radio-inline">
            <input type="radio" name="evalMouthfeelWarmth" id="evalMouthfeelWarmth<?php echo $id_value++; ?>" value="<?php echo $cl_value; ?>" data-error="<?php echo $evaluation_info_062; ?>" required <?php if ($action == "edit") { if (strpos($row_eval['evalMouthfeelChecklist'],$cl_value) !== false) echo "checked"; } ?>> <?php echo $value; ?>
        </label>
        <?php } ?>
        <div class="help-block small with-errors"></div>
  	</div>
  </div>
  <div class="form-group">
    <label class="col-sm-3 control-label"><?php echo $label_creaminess; ?></label>
    <div class="col-sm-9">
        <?php 
        $id_value = 1; 
        foreach($checklist_factors as $value) { 
        $cl_value = $label_creaminess.": ".$value;
        ?>
        <label class="radio-inline">
            <input type="radio" name="evalMouthfeelCreaminess" id="evalMouthfeelCreaminess<?php echo $id_value++; ?>" value="<?php echo $cl_value; ?>" data-error="<?php echo $evaluation_info_062; ?>" required <?php if ($action == "edit") { if (strpos($row_eval['evalMouthfeelChecklist'],$cl_value) !== false) echo "checked"; } ?>> <?php echo $value; ?>
        </label>
        <?php } ?>
        <div class="help-block small with-errors"></div>
  	</div>
  </div>
  <div class="form-group">
    <label class="col-sm-3 control-label"><?php echo $label_astringency; ?></label>
    <div class="col-sm-9">
        <?php 
        $id_value = 1; 
        foreach($checklist_factors as $value) { 
        $cl_value = $label_astringency.": ".$value;
        ?>
        <label class="radio-inline">
            <input type="radio" name="evalMouthfeelAstringency" id="evalMouthfeelAstringency<?php echo $id_value++; ?>" value="<?php echo $cl_value; ?>" data-error="<?php echo $evaluation_info_062; ?>" required <?php if ($action == "edit") { if (strpos($row_eval['evalMouthfeelChecklist'],$cl_value) !== false) echo "checked"; } ?>> <?php echo $value; ?>
        </label>
        <?php } ?>
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
    <textarea class="form-control" id="evalMouthfeelComments" name="evalMouthfeelComments" rows="6" placeholder="" data-error="<?php echo $evaluation_info_062; ?>" required><?php if ($action == "edit") echo htmlentities($row_eval['evalMouthfeelComments']); ?></textarea>
    <div class="help-block small with-errors"></div>
    <div class="help-block small" id="evalMouthfeelComments-words"></div>
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
            <?php 
            $id_value = 1; 
            foreach($cl_mf_desc_flaws as $value) { 
            $cl_value = $label_flaw.": ".$value;
            ?>
            <div class="checkbox">
            <label>
                <input type="checkbox" name="evalMouthfeelChecklistDesc[]" id="evalMouthfeelChecklistDesc<?php echo $id_value++; ?>" value="<?php echo $cl_value; ?>" <?php if ($action == "edit") { if (strpos($row_eval['evalMouthfeelChecklistDesc'],$cl_value) !== false) echo "checked"; } ?>> <?php echo $value; ?>
            </label>
            </div>
            <?php } ?>
        </div>
      </div>
      <div class="col col-lg-2 col-md-2 col-sm-4 col-xs-6">
        <div class="form-group">
          <label for="mouthfeelFinish"><?php echo $label_finish; ?></label>
            <?php 
            foreach($cl_mf_desc_finish as $value) { 
            $cl_value = $label_finish.": ".$value;
            ?>
            <div class="checkbox">
            <label>
                <input type="checkbox" name="evalMouthfeelChecklistDesc[]" id="evalMouthfeelChecklistDesc<?php echo $id_value++; ?>" value="<?php echo $cl_value; ?>" <?php if ($action == "edit") { if (strpos($row_eval['evalMouthfeelChecklistDesc'],$cl_value) !== false) echo "checked"; } ?>> <?php echo $value; ?>
            </label>
            </div>
            <?php } ?>
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
                <input type="radio" name="evalDrinkability" id="evalDrinkability1" data-error="<?php echo $evaluation_info_062; ?>" value="<?php echo $evaluation_info_063; ?>" required <?php if (($action == "edit") && ($row_eval['evalDrinkability'] == $evaluation_info_063)) echo "checked"; ?>> <?php echo $evaluation_info_063; ?>
            </label>
        </div>
        <div class="radio">
            <label>
                <input type="radio" name="evalDrinkability" id="evalDrinkability2" data-error="<?php echo $evaluation_info_062; ?>" value="<?php echo $evaluation_info_064; ?>" required <?php if (($action == "edit") && ($row_eval['evalDrinkability'] == $evaluation_info_064)) echo "checked"; ?>> <?php echo $evaluation_info_064; ?>
            </label>
        </div>
        <div class="radio">
            <label>
                <input type="radio" name="evalDrinkability" id="evalDrinkability3" data-error="<?php echo $evaluation_info_062; ?>" value="<?php echo $evaluation_info_065; ?>" required <?php if (($action == "edit") && ($row_eval['evalDrinkability'] == $evaluation_info_065)) echo "checked"; ?>> <?php echo $evaluation_info_065; ?>
            </label>
        </div>
        <div class="radio">
            <label>
                <input type="radio" name="evalDrinkability" id="evalDrinkability4" data-error="<?php echo $evaluation_info_062; ?>" value="<?php echo $evaluation_info_066; ?>" required <?php if (($action == "edit") && ($row_eval['evalDrinkability'] == $evaluation_info_066)) echo "checked"; ?>> <?php echo $evaluation_info_066; ?>
            </label>
        </div>
        <div class="help-block small with-errors"></div>
      </div>
    </div>
</div>
<div class="form-group">
    <label for="evalOverallComments"><?php echo sprintf("%s: %s",$label_overall_impression,$label_comments); ?></label>
    <textarea class="form-control" id="evalOverallComments" name="evalOverallComments" rows="6" placeholder="" data-error="<?php echo $evaluation_info_061; ?>" required><?php if ($action == "edit") echo htmlentities($row_eval['evalOverallComments']); ?></textarea>
    <div class="help-block small with-errors"></div>
    <div class="help-block small" id="evalOverallComments-words"></div>
</div>

<!-- Flaws -->
<h3 class="section-heading"><?php echo $label_flaws; ?></h3>
<?php 
foreach ($flaws as $flaw) { 
$id_value = 1;
?>
<div class="form-group">
  <div class="row">
    <div class="col col-sm-3">
        <label for="evalFlaws"><?php echo $flaw; ?></label>
    </div>
    <div class="col col-sm-3">
        <div class="checkbox">
            <label>
                <input type="checkbox" name="evalFlaws[]" id="evalFlaws<?php echo str_replace(' ', '', $flaw).$id_value++; ?>" value="<?php echo $label_aroma.": ".$flaw; ?>" <?php if ($action == "edit") { if (strpos($row_eval['evalFlaws'],$label_aroma.": ".$flaw) !== false) echo "checked"; } ?>> <?php echo $label_aroma; ?>
            </label>
        </div>
    </div>
    <div class="col col-sm-3">
        <div class="checkbox">
            <label>
                <input type="checkbox" name="evalFlaws[]" id="evalFlaws<?php echo str_replace(' ', '', $flaw).$id_value++; ?>" value="<?php echo $label_flavor.": ".$flaw; ?>" <?php if ($action == "edit") { if (strpos($row_eval['evalFlaws'],$label_flavor.": ".$flaw) !== false) echo "checked"; } ?>> <?php echo $label_flavor; ?>
            </label>
        </div>
    </div>
    <div class="col col-sm-3">
        <?php if (in_array($flaw, $flaws_mouthfeel)) { ?>
        <div class="checkbox">
            <label>
                <input type="checkbox" name="evalFlaws[]" id="evalFlaws<?php echo str_replace(' ', '', $flaw).$id_value++; ?>" value="<?php echo $label_mouthfeel.": ".$flaw; ?>" <?php if ($action == "edit") { if (strpos($row_eval['evalFlaws'],$label_mouthfeel.": ".$flaw) !== false) echo "checked"; } ?>> <?php echo $label_mouthfeel; ?>
            </label>
        </div>
      <?php } ?>
    </div>
  </div>
</div>
<?php } ?>