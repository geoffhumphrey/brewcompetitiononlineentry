<?php 

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
<h3 class="section-heading mt-4 pt-4"><?php echo $label_aroma; ?> <a role="button" id="show-hide-aroma-btn" data-bs-toggle="collapse" href="#scoresheet-aroma" aria-expanded="true" aria-controls="scoresheet-aroma"><i id="toggle-icon-aroma" class="fa fa-chevron-circle-down"></i></a> <i id="score-icon-aroma" class="fa fa-check-circle text-success"></i></h3>
<!-- Aroma Score -->
<div class="mb-3 row">
    <div class="row">
        <div class="col-12 col-sm-12 col-md-3">
            <label class="form-label" for="evalAromaScore"><?php echo sprintf("<strong>%s</strong> (%s %s)",$label_score,$aroma_points,$label_possible_points); ?></label>
        </div>
        <div class="col-12 col-sm-12 col-md-9">
            <select class="form-control bootstrap-select score-choose" name="evalAromaScore" id="evalAromaScore" data-size="10" required>
            <option value=""></option>
            <?php 
            for($i=$aroma_points; $i>=1; $i--) {
              if (($action == "edit") && ($i == $row_eval['evalAromaScore'])) $selected = "selected";
              else $selected = "";
                ?>
            <option value="<?php echo $i; ?>" <?php echo $selected; ?>><?php echo $i; ?></option>
            <?php } ?>
            </select>
            <div class="help-block small invalid-feedback text-danger"></div>
        </div>
    </div>
</div>
<section class="collapse show" id="scoresheet-aroma">
    <div class="mb-3 row">
        <label class="col-12 col-sm-12 col-md-3 form-label"><strong><?php echo $label_malt; ?></strong></label>
        <div class="col-12 col-sm-12 col-md-9">
            <?php 
            $id_value = 1; 
            foreach($checklist_factors as $value) { 
            $cl_value = $label_malt.": ".$value;
            ?>
            <div class="form-check form-check-inline">
                <input class="form-check-input" type="radio" name="evalAromaMalt" id="evalAromaMalt<?php echo $id_value++; ?>" value="<?php echo $cl_value; ?>" required <?php if ($action == "edit") { if (strpos($row_eval['evalAromaChecklist'],$cl_value) !== false) echo "checked"; } ?>>
                <label class="form-check-label"><?php echo $value; ?></label>
            </div>
            <?php } ?>
            <div class="help-block small invalid-feedback text-danger"><?php echo $evaluation_info_062; ?></div>
      	</div>
    </div>
    <div class="mb-3 row">
        <label class="col-12 col-sm-12 col-md-3 form-label"><strong><?php echo $label_hops; ?></strong></label>
        <div class="col-12 col-sm-12 col-md-9">
        	<?php 
            $id_value = 1; 
            foreach($checklist_factors as $value) { 
            $cl_value = $label_hops.": ".$value;
            ?>
            <div class="form-check form-check-inline">
                <input class="form-check-input" type="radio" name="evalAromaHops" id="evalAromaHops<?php echo $id_value++; ?>" value="<?php echo $cl_value; ?>" required <?php if ($action == "edit") { if (strpos($row_eval['evalAromaChecklist'],$cl_value) !== false) echo "checked"; } ?>>
                <label class="form-check-label"><?php echo $value; ?></label>
            </div>
            <?php } ?>
            <div class="help-block small invalid-feedback text-danger"><?php echo $evaluation_info_062; ?></div>
      	</div>
    </div>
    <div class="mb-3 row">
        <label class="col-12 col-sm-12 col-md-3 form-label"><strong><?php echo $label_esters; ?></strong></label>
        <div class="col-12 col-sm-12 col-md-9">
        	<?php
            $id_value = 1; 
            foreach($checklist_factors as $value) { 
            $cl_value = $label_esters.": ".$value;
            ?>
            <div class="form-check form-check-inline">
                <input class="form-check-input" type="radio" name="evalAromaEsters" id="evalAromaEsters<?php echo $id_value++; ?>" value="<?php echo $cl_value; ?>" required <?php if ($action == "edit") { if (strpos($row_eval['evalAromaChecklist'],$cl_value) !== false) echo "checked"; } ?>>
                <label class="form-check-label"><?php echo $value; ?></label>
            </div>
            <?php } ?>
            <div class="help-block small invalid-feedback text-danger"><?php echo $evaluation_info_062; ?></div>
      	</div>
    </div>
    <div class="mb-3 row">
        <label class="col-12 col-sm-12 col-md-3 form-label"><strong><?php echo $label_phenols; ?></strong></label>
        <div class="col-12 col-sm-12 col-md-9">
        	<?php 
            $id_value = 1; 
            foreach($checklist_factors as $value) { 
            $cl_value = $label_phenols.": ".$value;
            ?>
            <div class="form-check form-check-inline">
                <input class="form-check-input" type="radio" name="evalAromaPhenols" id="evalAromaPhenols<?php echo $id_value++; ?>" value="<?php echo $cl_value; ?>" required <?php if ($action == "edit") { if (strpos($row_eval['evalAromaChecklist'],$cl_value) !== false) echo "checked"; } ?>> 
                <label class="form-check-label"><?php echo $value; ?></label>
            </div>
            <?php } ?>
            <div class="help-block small invalid-feedback text-danger"><?php echo $evaluation_info_062; ?></div>
      	</div>
    </div>
    <div class="mb-3 row">
        <label class="col-12 col-sm-12 col-md-3 form-label"><strong><?php echo $label_alcohol; ?></strong></label>
        <div class="col-12 col-sm-12 col-md-9">
        	<?php 
            $id_value = 1; 
            foreach($checklist_factors as $value) { 
            $cl_value = $label_alcohol.": ".$value;
            ?>
            <div class="form-check form-check-inline">
                <input class="form-check-input" type="radio" name="evalAromaAlcohol" id="evalAromaAlcohol<?php echo $id_value++; ?>" value="<?php echo $cl_value; ?>" required <?php if ($action == "edit") { if (strpos($row_eval['evalAromaChecklist'],$cl_value) !== false) echo "checked"; } ?>>
                <label class="form-check-label"><?php echo $value; ?></label>
            </div>
            <?php } ?>
            <div class="help-block small invalid-feedback text-danger"><?php echo $evaluation_info_062; ?></div>
      	</div>
    </div>
    <div class="mb-3 row">
        <label class="col-12 col-sm-12 col-md-3 form-label"><strong><?php echo $label_sweetness; ?></strong></label>
        <div class="col-12 col-sm-12 col-md-9">
        	<?php
            $id_value = 1; 
            foreach($checklist_factors as $value) { 
            $cl_value = $label_sweetness.": ".$value; 
            ?>
            <div class="form-check form-check-inline">
                <input class="form-check-input" type="radio" name="evalAromaSweetness" id="evalAromaSweetness<?php echo $id_value++; ?>" value="<?php echo $cl_value; ?>" required <?php if ($action == "edit") { if (strpos($row_eval['evalAromaChecklist'],$cl_value) !== false) echo "checked"; } ?>>
                <label class="form-check-label"><?php echo $value; ?></label>
            </div>
            <?php } ?>
            <div class="help-block small invalid-feedback text-danger"><?php echo $evaluation_info_062; ?></div>
      	</div>
    </div>
    <div class="mb-3 row">
        <label class="col-12 col-sm-12 col-md-3 form-label"><strong><?php echo $label_acidity; ?></strong></label>
        <div class="col-12 col-sm-12 col-md-9">
        	<?php 
            $id_value = 1; 
            foreach($checklist_factors as $value) { 
            $cl_value = $label_acidity.": ".$value;
            ?>
            <div class="form-check form-check-inline">
                <input class="form-check-input" type="radio" name="evalAroma<?php echo $label_acidity; ?>" id="evalAromaAcidity<?php echo $id_value++; ?>" value="<?php echo $cl_value; ?>" required <?php if ($action == "edit") { if (strpos($row_eval['evalAromaChecklist'],$cl_value) !== false) echo "checked"; } ?>>
                <label class="form-check-label"><?php echo $value; ?></label>
            </div>
            <?php } ?>
            <div class="help-block small invalid-feedback text-danger"><?php echo $evaluation_info_062; ?></div>
      	</div>
    </div>
    <!-- Aroma Comments -->
    <div class="mb-3">
        <label class="form-label" for="evalAromaComments"><strong><?php echo $label_comments; ?></strong></label>
        <textarea class="form-control" id="evalAromaComments" name="evalAromaComments" rows="6" placeholder="" required><?php if ($action == "edit") echo htmlentities($row_eval['evalAromaComments']); ?></textarea>
        <div class="help-block small invalid-feedback text-danger"><?php echo $evaluation_info_061; ?></div>
        <div class="help-block small" id="evalAromaComments-words"></div>
    </div>
    <label class="form-label"><strong><?php echo $label_descriptors; ?></strong></label>
    <div class="p-3 text-secondary-emphasis bg-secondary-subtle border border-secondary-subtle rounded-2 small">
        <div class="row">
            <div class="col-6 col-sm-4 col-md-2 col-lg-2">
                <label class="form-label" for="evalAromaMalt"><strong><?php echo $label_malt; ?></strong></label>
                <?php 
                $id_value = 1; 
                foreach($cl_ar_desc_malt as $value) { 
                $cl_value = $label_malt.": ".$value;
                ?>
                <div class="form-check">
                    <input class="form-check-input" type="checkbox" name="evalAromaChecklistDesc[]" id="evalAromaMaltDesc<?php echo $id_value++; ?>" value="<?php echo $cl_value; ?>" <?php if ($action == "edit") { if (strpos($row_eval['evalAromaChecklistDesc'],$cl_value) !== false) echo "checked"; } ?>>
                    <label class="form-check-label"><?php echo $value; ?></label>
                </div>
                <?php } ?>
            </div>
            <div class="col-6 col-sm-4 col-md-2 col-lg-2">
                <label for="evalAromaHops"><strong><?php echo $label_hops; ?></strong></label>
                <?php 
                foreach($cl_ar_desc_hops as $value) { 
                $cl_value = $label_hops.": ".$value;
                ?>
                <div class="form-check">
                    <input class="form-check-input" type="checkbox" name="evalAromaChecklistDesc[]" id="evalAromaHopsDesc<?php echo $id_value++; ?>" value="<?php echo $cl_value; ?>" <?php if ($action == "edit") { if (strpos($row_eval['evalAromaChecklistDesc'],$cl_value) !== false) echo "checked"; } ?>>
                    <label class="form-check-label"><?php echo $value; ?></label>
                </div>
                <?php } ?>
            </div>
            <div class="col-6 col-sm-4 col-md-2 col-lg-2">
                <label for="evalAromaEsters"><strong><?php echo $label_esters; ?></strong></label>
                <?php  
                foreach($cl_ar_desc_esters as $value) { 
                $cl_value = $label_esters.": ".$value;
                ?>
                <div class="form-check">
                    <input class="form-check-input" type="checkbox" name="evalAromaChecklistDesc[]" id="evalAromaEstersDesc<?php echo $id_value++; ?>" value="<?php echo $cl_value; ?>" <?php if ($action == "edit") { if (strpos($row_eval['evalAromaChecklistDesc'],$cl_value) !== false) echo "checked"; } ?>>
                    <label class="form-check-label"><?php echo $value; ?></label>
                </div>
                <?php } ?>
            </div>
            <div class="col-6 col-sm-4 col-md-2 col-lg-2">
                <label for="evalAromaOther"><strong><?php echo $label_other; ?></strong></label>
                <?php 
                foreach($cl_ar_desc_other as $value) { 
                $cl_value = $label_other.": ".$value;
                ?>
                <div class="form-check">
                    <input class="form-check-input" type="checkbox" name="evalAromaChecklistDesc[]" id="evalAromaOtherDesc<?php echo $id_value++; ?>" value="<?php echo $cl_value; ?>" <?php if ($action == "edit") { if (strpos($row_eval['evalAromaChecklistDesc'],$cl_value) !== false) echo "checked"; } ?>>
                    <label class="form-check-label"><?php echo $value; ?></label>
                </div>
                <?php } ?>
            </div>
        </div>
    </div>
</section>

<!-- Appearance Checklist -->
<h3 class="section-heading mt-4 pt-4"><?php echo $label_appearance; ?> <a role="button" id="show-hide-appearance-btn" data-bs-toggle="collapse" href="#scoresheet-appearance" aria-expanded="true" aria-controls="scoresheet-appearance"><i id="toggle-icon-appearance" class="fa fa-chevron-circle-down"></i></a> <i id="score-icon-appearance" class="fa fa-check-circle text-success"></i></h3>

<!-- Appearance Score -->
<div class="mb-3 row">
    <div class="col-12 col-sm-12 col-md-3">
        <label for="evalAppearanceScore"><?php echo sprintf("<strong>%s</strong> (%s %s)",$label_score,$appearance_points,$label_possible_points); ?></label>
    </div>
    <div class="col-12 col-sm-12 col-md-9">
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
</div>
<section class="collapse show" id="scoresheet-appearance">
    <div class="mb-3 row">
        <label class="col-12 col-sm-12 col-md-3 form-label"><strong><?php echo $label_clarity; ?></strong></label>
        <div class="col-12 col-sm-12 col-md-9">
            <?php 
            $id_value = 1; 
            foreach($checklist_factors as $value) { 
            $cl_value = $label_clarity.": ".$value;
            ?>
            <div class="form-check form-check-inline">
                <input class="form-check-input" type="radio" name="evalAppearanceClarity" id="evalAppearanceClarity<?php echo $id_value++; ?>" value="<?php echo $cl_value; ?>" required <?php if ($action == "edit") { if (strpos($row_eval['evalAppearanceChecklist'],$cl_value) !== false) echo "checked"; } ?>>
                <label class="form-check-label"><?php echo $value; ?></label>
            </div>
            <?php } ?>
            <div class="help-block small invalid-feedback text-danger"><?php echo $evaluation_info_062; ?></div>
      	</div>
    </div>
    <div class="mb-3 row">
        <label class="col-12 col-sm-12 col-md-3 form-label"><strong><?php echo $label_head_size; ?></strong></label>
        <div class="col-12 col-sm-12 col-md-9">
            <?php 
            $id_value = 1; 
            foreach($checklist_factors as $value) { 
            $cl_value = $label_head_size.": ".$value;
            ?>
            <div class="form-check form-check-inline">
                <input class="form-check-input" type="radio" name="evalAppearanceHeadSize" id="evalAppearanceHeadSize<?php echo $id_value++; ?>" value="<?php echo $cl_value; ?>" required <?php if ($action == "edit") { if (strpos($row_eval['evalAppearanceChecklist'],$cl_value) !== false) echo "checked"; } ?>>
                <label class="form-check-label"><?php echo $value; ?></label>
            </div>
            <?php } ?>
            <div class="help-block small invalid-feedback text-danger"><?php echo $evaluation_info_062; ?></div>
      	</div>
    </div>
    <div class="mb-3 row">
        <label class="col-12 col-sm-12 col-md-3 form-label"><strong><?php echo $label_head_retention; ?></strong></label>
        <div class="col-12 col-sm-12 col-md-9">
            <?php 
            $id_value = 1; 
            foreach($checklist_factors as $value) { 
            $cl_value = $label_head_retention.": ".$value;
            ?>
            <div class="form-check form-check-inline">
                <input class="form-check-input" type="radio" name="evalAppearanceHeadRetention" id="evalAppearanceHeadRetention<?php echo $id_value++; ?>" value="<?php echo $cl_value; ?>" required <?php if ($action == "edit") { if (strpos($row_eval['evalAppearanceChecklist'],$cl_value) !== false) echo "checked"; } ?>>
                <label class="form-check-label"><?php echo $value; ?></label>
            </div>
            <?php } ?>
            <div class="help-block small invalid-feedback text-danger"><?php echo $evaluation_info_062; ?></div>
      	</div>
    </div>
    
    <!-- Appearance Comments -->
    <div class="mb-3">
        <label class="form-label" for="evalAppearanceComments"><strong><?php echo $label_comments; ?></strong></label>
        <textarea class="form-control" id="evalAppearanceComments" name="evalAppearanceComments" rows="6" placeholder="" required><?php if ($action == "edit") echo htmlentities($row_eval['evalAppearanceComments']); ?></textarea>
        <div class="help-block small invalid-feedback text-danger"><?php echo $evaluation_info_061; ?></div>
        <div class="help-block small" id="evalAppearanceComments-words"></div>
    </div>

    <label class="form-label"><strong><?php echo $label_descriptors; ?></strong></label>
    <div class="p-3 text-secondary-emphasis bg-secondary-subtle border border-secondary-subtle rounded-2 small">
        <div class="row">
            <div class="col-6 col-sm-4 col-md-2 col-lg-2">
                <label class="form-label" for="appearanceColor"><strong><?php echo $label_color; ?></strong></label>
                <?php 
                $id_value = 1; 
                foreach($cl_ap_desc_color as $value) { 
                $cl_value = $label_color.": ".$value;
                ?>
                <div class="form-check">
                    <input class="form-check-input" type="checkbox" name="evalAppearanceChecklistDesc[]" id="evalAppearanceColorDesc<?php echo $id_value++; ?>" value="<?php echo $cl_value; ?>" <?php if ($action == "edit") { if (strpos($row_eval['evalAppearanceChecklistDesc'],$cl_value) !== false) echo "checked"; } ?>>
                    <label class="form-check-label"><?php echo $value; ?></label>
                </div>
                <?php } ?>
            </div>
            <div class="col-6 col-sm-4 col-md-2 col-lg-2">
                <label class="form-label" for="appearanceHeadColor"><strong><?php echo $label_head_color; ?></strong></label>
                <?php 
                foreach($cl_ap_desc_head as $value) { 
                $cl_value = $label_head_color.": ".$value;
                ?>
                <div class="form-check">
                    <input class="form-check-input" type="checkbox" name="evalAppearanceChecklistDesc[]" id="evalAppearanceHeadDesc<?php echo $id_value++; ?>" value="<?php echo $cl_value; ?>" <?php if ($action == "edit") { if (strpos($row_eval['evalAppearanceChecklistDesc'],$cl_value) !== false) echo "checked"; } ?>>
                    <label class="form-check-label"><?php echo $value; ?></label>
                </div>
                <?php } ?>
            </div>
            <div class="col-6 col-sm-4 col-md-2 col-lg-2">
                <label class="form-label" for="appearanceOther"><strong><?php echo $label_other; ?></strong></label>
                <?php 
                foreach($cl_ap_desc_other as $value) { 
                $cl_value = $label_other.": ".$value;
                ?>
                <div class="form-check">
                    <input class="form-check-input" type="checkbox" name="evalAppearanceChecklistDesc[]" id="evalAppearanceOtherDesc<?php echo $id_value++; ?>" value="<?php echo $cl_value; ?>" <?php if ($action == "edit") { if (strpos($row_eval['evalAppearanceChecklistDesc'],$cl_value) !== false) echo "checked"; } ?>>
                    <label class="form-check-label"><?php echo $value; ?></label>
                </div>
                <?php } ?>
            </div>
        </div><!-- End Row -->
    </div>
</section>
<!-- Flavor Checklist -->
<h3 class="section-heading mt-4 pt-4"><?php echo $label_flavor; ?> <a role="button" id="show-hide-flavor-btn" data-bs-toggle="collapse" href="#scoresheet-flavor" aria-expanded="true" aria-controls="scoresheet-flavor"><i id="toggle-icon-flavor" class="fa fa-chevron-circle-down"></i></a> <i id="score-icon-flavor" class="fa fa-check-circle text-success"></i></h3>
<!-- Flavor Score -->
<div class="mb-3 row">
    <div class="row">
        <div class="col-12 col-sm-12 col-md-3">
            <label for="evalFlavorScore"><?php echo sprintf("<strong>%s</strong> (%s %s)",$label_score,$flavor_points,$label_possible_points); ?></label>
        </div>
        <div class="col-12 col-sm-12 col-md-9">
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
    </div>
</div>
<section class="collapse show" id="scoresheet-flavor">
    <div class="form-horizontal">
      <div class="mb-3 row">
        <label class="col-12 col-sm-12 col-md-3 form-label"><strong><?php echo $label_malt; ?></strong></label>
        <div class="col-12 col-sm-12 col-md-9">
            <?php 
            $id_value = 1; 
            foreach($checklist_factors as $value) { 
            $cl_value = $label_malt.": ".$value;
            ?>
            <div class="form-check form-check-inline">
                <input class="form-check-input" type="radio" name="evalFlavorMalt" id="evalFlavorMalt<?php echo $id_value++; ?>" value="<?php echo $cl_value; ?>" required <?php if ($action == "edit") { if (strpos($row_eval['evalFlavorChecklist'],$cl_value) !== false) echo "checked"; } ?>>
                <label class="form-check-label"><?php echo $value; ?></label>
            </div>
            <?php } ?>
            <div class="help-block small invalid-feedback text-danger"><?php echo $evaluation_info_062; ?></div>
      	</div>
      </div>
      <div class="mb-3 row">
        <label class="col-12 col-sm-12 col-md-3 form-label"><strong><?php echo $label_hops; ?></strong></label>
        <div class="col-12 col-sm-12 col-md-9">
            <?php 
            $id_value = 1; 
            foreach($checklist_factors as $value) { 
            $cl_value = $label_hops.": ".$value;
            ?>
            <div class="form-check form-check-inline">
                <input class="form-check-input" type="radio" name="evalFlavorHops" id="evalFlavorHops<?php echo $id_value++; ?>" value="<?php echo $cl_value; ?>" required <?php if ($action == "edit") { if (strpos($row_eval['evalFlavorChecklist'],$cl_value) !== false) echo "checked"; } ?>>
                <label class="form-check-label"><?php echo $value; ?></label>
            </div>
            <?php } ?>
            <div class="help-block small invalid-feedback text-danger"><?php echo $evaluation_info_062; ?></div>
      	</div>
      </div>
      <div class="mb-3 row">
        <label class="col-12 col-sm-12 col-md-3 form-label"><strong><?php echo $label_esters; ?></strong></label>
        <div class="col-12 col-sm-12 col-md-9">
        	<?php 
            $id_value = 1; 
            foreach($checklist_factors as $value) { 
            $cl_value = $label_esters.": ".$value;
            ?>
            <div class="form-check form-check-inline">
                <input class="form-check-input" type="radio" name="evalFlavorEsters" id="evalFlavorEsters<?php echo $id_value++; ?>" value="<?php echo $cl_value; ?>" required <?php if ($action == "edit") { if (strpos($row_eval['evalFlavorChecklist'],$cl_value) !== false) echo "checked"; } ?>>
                <label class="form-check-label"><?php echo $value; ?></label>
            </div>
            <?php } ?>
            <div class="help-block small invalid-feedback text-danger"><?php echo $evaluation_info_062; ?></div>
      	</div>
      </div>
      <div class="mb-3 row">
        <label class="col-12 col-sm-12 col-md-3 form-label"><strong><?php echo $label_phenols; ?></strong></label>
        <div class="col-12 col-sm-12 col-md-9">
            <?php 
            $id_value = 1; 
            foreach($checklist_factors as $value) { 
            $cl_value = $label_phenols.": ".$value;
            ?>
            <div class="form-check form-check-inline">
                <input class="form-check-input" type="radio" name="evalFlavorPhenols" id="evalFlavorPhenols<?php echo $id_value++; ?>" value="<?php echo $cl_value; ?>" required <?php if ($action == "edit") { if (strpos($row_eval['evalFlavorChecklist'],$cl_value) !== false) echo "checked"; } ?>>
                <label class="form-check-label"><?php echo $value; ?></label>
            </div>
            <?php } ?>
            <div class="help-block small invalid-feedback text-danger"><?php echo $evaluation_info_062; ?></div>
      	</div>
      </div>
       <div class="mb-3 row">
        <label class="col-12 col-sm-12 col-md-3 form-label"><strong><?php echo $label_sweetness; ?></strong></label>
        <div class="col-12 col-sm-12 col-md-9">
        	<?php 
            $id_value = 1; 
            foreach($checklist_factors as $value) { 
            $cl_value = $label_sweetness.": ".$value;
            ?>
            <div class="form-check form-check-inline">
                <input class="form-check-input" type="radio" name="evalFlavorSweetness" id="evalFlavorSweetness<?php echo $id_value++; ?>" value="<?php echo $cl_value; ?>" required <?php if ($action == "edit") { if (strpos($row_eval['evalFlavorChecklist'],$cl_value) !== false) echo "checked"; } ?>>
                <label class="form-check-label"><?php echo $value; ?></label>
            </div>
            <?php } ?>
            <div class="help-block small invalid-feedback text-danger"><?php echo $evaluation_info_062; ?></div>
      	</div>
      </div>
      <div class="mb-3 row">
        <label class="col-12 col-sm-12 col-md-3 form-label"><strong><?php echo $label_bitterness; ?></strong></label>
        <div class="col-12 col-sm-12 col-md-9">
        	<?php 
            $id_value = 1; 
            foreach($checklist_factors as $value) { 
            $cl_value = $label_bitterness.": ".$value;
            ?>
            <div class="form-check form-check-inline">
                <input class="form-check-input" type="radio" name="evalFlavorBitterness" id="evalFlavorBitterness<?php echo $id_value++; ?>" value="<?php echo $cl_value; ?>" required <?php if ($action == "edit") { if (strpos($row_eval['evalFlavorChecklist'],$cl_value) !== false) echo "checked"; } ?>>
                <label class="form-check-label"><?php echo $value; ?></label>
            </div>
            <?php } ?>
            <div class="help-block small invalid-feedback text-danger"><?php echo $evaluation_info_062; ?></div>
      	</div>
      </div>
      <div class="mb-3 row">
        <label class="col-12 col-sm-12 col-md-3 form-label"><strong><?php echo $label_alcohol; ?></strong></label>
        <div class="col-12 col-sm-12 col-md-9">
        	<?php 
            $id_value = 1; 
            foreach($checklist_factors as $value) { 
            $cl_value = $label_alcohol.": ".$value;
            ?>
            <div class="form-check form-check-inline">
                <input class="form-check-input" type="radio" name="evalFlavorAlcohol" id="evalFlavorAlcohol<?php echo $id_value++; ?>" value="<?php echo $cl_value; ?>" required <?php if ($action == "edit") { if (strpos($row_eval['evalFlavorChecklist'],$cl_value) !== false) echo "checked"; } ?>>
                <label class="form-check-label"><?php echo $value; ?></label>
            </div>
            <?php } ?>
            <div class="help-block small invalid-feedback text-danger"><?php echo $evaluation_info_062; ?></div>
      	</div>
      </div>
      <div class="mb-3 row">
        <label class="col-12 col-sm-12 col-md-3 form-label"><strong><?php echo $label_acidity; ?></strong></label>
        <div class="col-12 col-sm-12 col-md-9">
        	<?php 
            $id_value = 1; 
            foreach($checklist_factors as $value) { 
            $cl_value = $label_acidity.": ".$value;
            ?>
            <div class="form-check form-check-inline">
                <input class="form-check-input" type="radio" name="evalFlavorAcidity" id="evalFlavorAcidity<?php echo $id_value++; ?>" value="<?php echo $cl_value; ?>" required <?php if ($action == "edit") { if (strpos($row_eval['evalFlavorChecklist'],$cl_value) !== false) echo "checked"; } ?>>
                <label class="form-check-label"><?php echo $value; ?></label>
            </div>
            <?php } ?>
            <div class="help-block small invalid-feedback text-danger"><?php echo $evaluation_info_062; ?></div>
      	</div>
      </div>
      <div class="mb-3 row">
        <label class="col-12 col-sm-12 col-md-3 form-label"><strong><?php echo $label_harshness; ?></strong></label>
        <div class="col-12 col-sm-12 col-md-9">
        	<?php 
            $id_value = 1; 
            foreach($checklist_factors as $value) { 
            $cl_value = $label_harshness.": ".$value;
            ?>
            <div class="form-check form-check-inline">
                <input class="form-check-input" type="radio" name="evalFlavorHarshness" id="evalFlavorHarshness<?php echo $id_value++; ?>" value="<?php echo $cl_value; ?>" required <?php if ($action == "edit") { if (strpos($row_eval['evalFlavorChecklist'],$cl_value) !== false) echo "checked"; } ?>>
                <label class="form-check-label"><?php echo $value; ?></label>
            </div>
            <?php } ?>
            <div class="help-block small invalid-feedback text-danger"><?php echo $evaluation_info_062; ?></div>
      	</div>
      </div>
    </div>
    <!-- Flavor Comments -->
    <div class="mb-3">
        <label class="form-label" for="evalFlavorComments"><strong><?php echo $label_flavor; ?></strong></label>
        <textarea class="form-control" id="evalFlavorComments" name="evalFlavorComments" rows="6" placeholder=""  required><?php if ($action == "edit") echo htmlentities($row_eval['evalFlavorComments']); ?></textarea>
        <div class="help-block small invalid-feedback text-danger"><?php echo $evaluation_info_061; ?></div>
        <div class="help-block small" id="evalFlavorComments-words"></div>
    </div>
    <label class="form-label"><strong><?php echo $label_descriptors; ?></strong></label>
    <div class="p-3 text-secondary-emphasis bg-secondary-subtle border border-secondary-subtle rounded-2 small">
        <div class="row">
            <div class="col-6 col-sm-4 col-md-2 col-lg-2">
                <label class="form-label" for="flavorMalt"><strong><?php echo $label_malt; ?></strong></label>
                <?php 
                $id_value = 1; 
                foreach($cl_fl_desc_malt as $value) { 
                $cl_value = $label_malt.": ".$value;
                ?>
                <div class="form-check">
                    <input class="form-check-input" type="checkbox" name="evalFlavorChecklistDesc[]" id="evalFlavorMaltDesc<?php echo $id_value++; ?>" value="<?php echo $cl_value; ?>" <?php if ($action == "edit") { if (strpos($row_eval['evalFlavorChecklistDesc'],$cl_value) !== false) echo "checked"; } ?>>
                    <label class="form-check-label"><?php echo $value; ?></label>
                </div>
                <?php } ?>
            </div>
            <div class="col-6 col-sm-4 col-md-2 col-lg-2">
                <label class="form-label" for="flavorHops"><strong><?php echo $label_hops; ?></strong></label>
                <?php 
                foreach($cl_fl_desc_hops as $value) { 
                $cl_value = $label_hops.": ".$value;
                ?>
                <div class="form-check">
                    <input class="form-check-input" type="checkbox" name="evalFlavorChecklistDesc[]" id="evalFlavorHopsDesc<?php echo $id_value++; ?>" value="<?php echo $cl_value; ?>" <?php if ($action == "edit") { if (strpos($row_eval['evalFlavorChecklistDesc'],$cl_value) !== false) echo "checked"; } ?>>
                    <label class="form-check-label"><?php echo $value; ?></label>
                </div>
                <?php } ?>
            </div>
            <div class="col-6 col-sm-4 col-md-2 col-lg-2">
                <label class="form-label" for="flavorEsters"><strong><?php echo $label_esters; ?></strong></label>
                <?php 
                foreach($cl_fl_desc_esters as $value) { 
                $cl_value = $label_esters.": ".$value;
                ?>
                <div class="form-check">
                    <input class="form-check-input" type="checkbox" name="evalFlavorChecklistDesc[]" id="evalFlavorEstersDesc<?php echo $id_value++; ?>" value="<?php echo $cl_value; ?>" <?php if ($action == "edit") { if (strpos($row_eval['evalFlavorChecklistDesc'],$cl_value) !== false) echo "checked"; } ?>>
                    <label class="form-check-label"><?php echo $value; ?></label>
                </div>
                <?php } ?>
            </div>
            <div class="col-6 col-sm-4 col-md-2 col-lg-2">
                <label class="form-label" for="flavorOther"><strong><?php echo $label_other; ?></strong></label>
                <?php 
                foreach($cl_fl_desc_other as $value) { 
                $cl_value = $label_other.": ".$value;
                ?>
                <div class="form-check">
                    <input class="form-check-input" type="checkbox" name="evalFlavorChecklistDesc[]" id="evalFlavorOtherDesc<?php echo $id_value++; ?>" value="<?php echo $cl_value; ?>" <?php if ($action == "edit") { if (strpos($row_eval['evalFlavorChecklistDesc'],$cl_value) !== false) echo "checked"; } ?>>
                    <label class="form-check-label"><?php echo $value; ?></label>
                </div>
                <?php } ?>
            </div>
            <div class="col-6 col-sm-4 col-md-2 col-lg-2">
                <label for="flavorBalance"><strong><?php echo $label_balance; ?></strong></label>
                <?php 
                foreach($cl_fl_desc_bal as $value) { 
                $cl_value = $label_balance.": ".$value;
                ?>
                <div class="form-check">
                    <input class="form-check-input" type="checkbox" name="evalFlavorChecklistDesc[]" id="evalFlavorBalanceDesc<?php echo $id_value++; ?>" value="<?php echo $cl_value; ?>" <?php if ($action == "edit") { if (strpos($row_eval['evalFlavorChecklistDesc'],$cl_value) !== false) echo "checked"; } ?>>
                    <label class="form-check-label"><?php echo $value; ?></label>
                </div>
                <?php } ?>
            </div>
        </div><!-- ./row -->
    </div>
</section>

<!-- Mouthfeel -->
<h3 class="section-heading mt-4 pt-4"><?php echo $label_mouthfeel; ?> <a role="button" id="show-hide-mouthfeel-btn" data-bs-toggle="collapse" href="#scoresheet-mouthfeel" aria-expanded="true" aria-controls="scoresheet-mouthfeel"><i id="toggle-icon-mouthfeel" class="fa fa-chevron-circle-down"></i></a> <i id="score-icon-mouthfeel" class="fa fa-check-circle text-success"></i></h3>

<!-- Mouthfeel Score -->
<div class="mb-3">
    <div class="row">
        <div class="col-12 col-sm-12 col-md-3">
            <label for="evalMouthfeelScore"><?php echo sprintf("<strong>%s</strong> (%s %s)",$label_score,$mouthfeel_points,$label_possible_points); ?></label>
        </div>
        <div class="col-12 col-sm-12 col-md-9">
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
    </div>
</div>

<section class="collapse show" id="scoresheet-mouthfeel">
    <!-- Mouthfeel Checklist -->
    <div class="form-horizontal">
      <div class="mb-3 row">
        <label class="col-12 col-sm-12 col-md-3 form-label"><strong><?php echo $label_body; ?></strong></label>
        <div class="col-12 col-sm-12 col-md-9">
            <?php 
            $id_value = 1; 
            foreach($checklist_factors as $value) { 
            $cl_value = $label_body.": ".$value;
            ?>
            <div class="form-check form-check-inline">
                <input class="form-check-input" type="radio" name="evalMouthfeelBody" id="evalMouthfeelBody<?php echo $id_value++; ?>" value="<?php echo $cl_value; ?>" required <?php if ($action == "edit") { if (strpos($row_eval['evalMouthfeelChecklist'],$cl_value) !== false) echo "checked"; } ?>> 
                <label class="form-check-label"><?php echo $value; ?></label>
            </div>
            <?php } ?>
            <div class="help-block small invalid-feedback text-danger"><?php echo $evaluation_info_062; ?></div>
      	</div>
      </div>
      <div class="mb-3 row">
        <label class="col-12 col-sm-12 col-md-3 form-label"><strong><?php echo $label_carbonation; ?></strong></label>
        <div class="col-12 col-sm-12 col-md-9">
            <?php 
            $id_value = 1; 
            foreach($checklist_factors as $value) { 
            $cl_value = $label_carbonation.": ".$value;
            ?>
            <div class="form-check form-check-inline">
                <input class="form-check-input" type="radio" name="evalMouthfeelCarbonation" id="evalMouthfeelCarbonation<?php echo $id_value++; ?>" value="<?php echo $cl_value; ?>" required <?php if ($action == "edit") { if (strpos($row_eval['evalMouthfeelChecklist'],$cl_value) !== false) echo "checked"; } ?>>
                <label class="form-check-label"><?php echo $value; ?></label>
            </div>
            <?php } ?>
            <div class="help-block small invalid-feedback text-danger"><?php echo $evaluation_info_062; ?></div>
      	</div>
      </div>
      <div class="mb-3 row">
        <label class="col-12 col-sm-12 col-md-3 form-label"><strong><?php echo $label_warmth; ?></strong></label>
        <div class="col-12 col-sm-12 col-md-9">
            <?php 
            $id_value = 1; 
            foreach($checklist_factors as $value) { 
            $cl_value = $label_warmth.": ".$value;
            ?>
            <div class="form-check form-check-inline">
                <input class="form-check-input" type="radio" name="evalMouthfeelWarmth" id="evalMouthfeelWarmth<?php echo $id_value++; ?>" value="<?php echo $cl_value; ?>" required <?php if ($action == "edit") { if (strpos($row_eval['evalMouthfeelChecklist'],$cl_value) !== false) echo "checked"; } ?>>
                <label class="form-check-label"><?php echo $value; ?></label>
            </div>
            <?php } ?>
            <div class="help-block small invalid-feedback text-danger"><?php echo $evaluation_info_062; ?></div>
      	</div>
      </div>
      <div class="mb-3 row">
        <label class="col-12 col-sm-12 col-md-3 form-label"><strong><?php echo $label_creaminess; ?></strong></label>
        <div class="col-12 col-sm-12 col-md-9">
            <?php 
            $id_value = 1; 
            foreach($checklist_factors as $value) { 
            $cl_value = $label_creaminess.": ".$value;
            ?>
            <div class="form-check form-check-inline">
                <input class="form-check-input" type="radio" name="evalMouthfeelCreaminess" id="evalMouthfeelCreaminess<?php echo $id_value++; ?>" value="<?php echo $cl_value; ?>" required <?php if ($action == "edit") { if (strpos($row_eval['evalMouthfeelChecklist'],$cl_value) !== false) echo "checked"; } ?>> 
                <label class="form-check-label"><?php echo $value; ?></label>
            </div>
            <?php } ?>
      	</div>
        <div class="help-block small invalid-feedback text-danger"><?php echo $evaluation_info_062; ?></div>
      </div>
      <div class="mb-3 row">
        <label class="col-12 col-sm-12 col-md-3 form-label"><strong><?php echo $label_astringency; ?></strong></label>
        <div class="col-12 col-sm-12 col-md-9">
            <?php 
            $id_value = 1; 
            foreach($checklist_factors as $value) { 
            $cl_value = $label_astringency.": ".$value;
            ?>
            <div class="form-check form-check-inline">
                <input class="form-check-input" type="radio" name="evalMouthfeelAstringency" id="evalMouthfeelAstringency<?php echo $id_value++; ?>" value="<?php echo $cl_value; ?>"  required <?php if ($action == "edit") { if (strpos($row_eval['evalMouthfeelChecklist'],$cl_value) !== false) echo "checked"; } ?>>
                <label class="form-check-label"><?php echo $value; ?></label>
            </div>
            <?php } ?>
      	</div>
        <div class="help-block small invalid-feedback text-danger"><?php echo $evaluation_info_062; ?></div>
      </div>
    </div>

    <div class="mb-3">
        <label for="evalMouthfeelComments"><strong><?php echo $label_comments; ?></strong></label>
        <textarea class="form-control" id="evalMouthfeelComments" name="evalMouthfeelComments" rows="6" placeholder="" required><?php if ($action == "edit") echo htmlentities($row_eval['evalMouthfeelComments']); ?></textarea>
        <div class="help-block small invalid-feedback text-danger"><?php echo $evaluation_info_061; ?></div>
        <div class="help-block small" id="evalMouthfeelComments-words"></div>
    </div>
    <label class="form-label"><strong><?php echo $label_descriptors; ?></strong></label>
    <div class="p-3 text-secondary-emphasis bg-secondary-subtle border border-secondary-subtle rounded-2 small">
        <div class="row">
            <div class="col-lg-2 col-md-2 col-sm-4 col-xs-6">
                <label class="form-label" for="mouthfeelFlaws"><strong><?php echo $label_flaws; ?></strong></label>
                <?php 
                $id_value = 1; 
                foreach($cl_mf_desc_flaws as $value) { 
                $cl_value = $label_flaw.": ".$value;
                ?>
                <div class="form-check">
                    <input class="form-check-input" type="checkbox" name="evalMouthfeelChecklistDesc[]" id="evalMouthfeelChecklistDesc<?php echo $id_value++; ?>" value="<?php echo $cl_value; ?>" <?php if ($action == "edit") { if (strpos($row_eval['evalMouthfeelChecklistDesc'],$cl_value) !== false) echo "checked"; } ?>>
                    <label class="form-check-label"><?php echo $value; ?></label>
                </div>
                <?php } ?>
            </div>
            <div class="col-6 col-sm-4 col-md-2 col-lg-2">
                <label class="form-label" for="mouthfeelFinish"><strong><?php echo $label_finish; ?></strong></label>
                <?php 
                foreach($cl_mf_desc_finish as $value) { 
                $cl_value = $label_finish.": ".$value;
                ?>
                <div class="form-check">
                    <input class="form-check-input" type="checkbox" name="evalMouthfeelChecklistDesc[]" id="evalMouthfeelChecklistDesc<?php echo $id_value++; ?>" value="<?php echo $cl_value; ?>" <?php if ($action == "edit") { if (strpos($row_eval['evalMouthfeelChecklistDesc'],$cl_value) !== false) echo "checked"; } ?>>
                    <label class="form-check-label"><?php echo $value; ?></label>
                </div>
                <?php } ?>
            </div>
        </div>
    </div>
</section>

<!-- Overall Impression -->
<h3 class="section-heading mt-4 pt-4"><?php echo $label_overall_impression; ?> <a role="button" id="show-hide-overall-btn" data-bs-toggle="collapse" href="#scoresheet-overall" aria-expanded="true" aria-controls="scoresheet-overall"><i id="toggle-icon-overall" class="fa fa-chevron-circle-down"></i></a> <i id="score-icon-overall" class="fa fa-check-circle text-success"></i></h3>

<div class="mb-3 row">
    <div class="col-12 col-sm-12 col-md-3">
        <label class="form-label" for="evalOverallScore"><?php echo sprintf("<strong>%s</strong> (%s %s)",$label_score,$overall_points,$label_possible_points); ?></label>
    </div>
    <div class="col-12 col-sm-12 col-md-9">
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
</div>
<section class="collapse show" id="scoresheet-overall">

    <div class="mb-3">
        <label class="form-label" for="evalOverallComments"><strong><?php echo $label_comments; ?></strong></label>
        <textarea class="form-control" id="evalOverallComments" name="evalOverallComments" rows="6" placeholder="" required><?php if ($action == "edit") echo htmlentities($row_eval['evalOverallComments']); ?></textarea>
        <div class="help-block small invalid-feedback text-danger"><?php echo $evaluation_info_061; ?></div>
        <div class="help-block small" id="evalOverallComments-words"></div>
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

    <!-- Drinkability -->
    <div class="mb-3 row">
        <div class="col-sm-2 col-xs-12">
            <label class="form-label" for="evalDrinkability"><strong><?php echo $label_drinkability; ?></strong></label>
        </div>
        <div class="col-sm-10 col-xs-12 small">
            <div class="form-check">
                <input class="form-check-input" type="radio" name="evalDrinkability" id="evalDrinkability1" value="<?php echo $evaluation_info_063; ?>" required <?php if (($action == "edit") && ($row_eval['evalDrinkability'] == $evaluation_info_063)) echo "checked"; ?>>
                <label class="form-check-label"><?php echo $evaluation_info_063; ?></label>
            </div>
            <div class="form-check">
                <input class="form-check-input" type="radio" name="evalDrinkability" id="evalDrinkability2" value="<?php echo $evaluation_info_064; ?>" required <?php if (($action == "edit") && ($row_eval['evalDrinkability'] == $evaluation_info_064)) echo "checked"; ?>>
                <label class="form-check-label"><?php echo $evaluation_info_064; ?></label>
            </div>
            <div class="form-check">
                <input class="form-check-input" type="radio" name="evalDrinkability" id="evalDrinkability3" value="<?php echo $evaluation_info_065; ?>" required <?php if (($action == "edit") && ($row_eval['evalDrinkability'] == $evaluation_info_065)) echo "checked"; ?>>
                <label class="form-check-label"><?php echo $evaluation_info_065; ?></label>
            </div>
            <div class="form-check">
                <input class="form-check-input" type="radio" name="evalDrinkability" id="evalDrinkability4" value="<?php echo $evaluation_info_066; ?>" required <?php if (($action == "edit") && ($row_eval['evalDrinkability'] == $evaluation_info_066)) echo "checked"; ?>>
                <label class="form-check-label"><?php echo $evaluation_info_066; ?></label>
            </div>
            <div class="help-block small invalid-feedback text-danger"><?php echo $evaluation_info_062; ?></div>
        </div>
    </div>

</section>

<!-- Flaws -->
<h3 class="section-heading mt-4 pt-4"><?php echo $label_flaws; ?> <a role="button" id="show-hide-flaws-btn" data-bs-toggle="collapse" href="#scoresheet-flaws" aria-expanded="true" aria-controls="scoresheet-flaws"><i id="toggle-icon-flaws" class="fa fa-chevron-circle-down"></i></a></h3>
<section class="collapse show" id="scoresheet-flaws">
    <?php 
    foreach ($flaws as $flaw) { 
    $id_value = 1;
    ?>
    <div class="row">
        <div class="col-3">
            <label class="form-label" for="evalFlaws"><strong><?php echo $flaw; ?></strong></label>
        </div>
        <div class="col-3 col-md-2 col-lg-1 small">
            <div class="form-check">
                <input class="form-check-input" type="checkbox" name="evalFlaws[]" id="evalFlaws<?php echo str_replace(' ', '', $flaw).$id_value++; ?>" value="<?php echo $label_aroma.": ".$flaw; ?>" <?php if ($action == "edit") { if (strpos($row_eval['evalFlaws'],$label_aroma.": ".$flaw) !== false) echo "checked"; } ?>>
                <label class="form-check-label"><?php echo $label_aroma; ?></label>
            </div>
        </div>
        <div class="col-3 col-md-2 col-lg-1 small">
            <div class="form-check">
                <input class="form-check-input" type="checkbox" name="evalFlaws[]" id="evalFlaws<?php echo str_replace(' ', '', $flaw).$id_value++; ?>" value="<?php echo $label_flavor.": ".$flaw; ?>" <?php if ($action == "edit") { if (strpos($row_eval['evalFlaws'],$label_flavor.": ".$flaw) !== false) echo "checked"; } ?>>
                <label class="form-check-label"><?php echo $label_flavor; ?></label>
            </div>
        </div>
        <div class="col-3 col-md-2 col-lg-1 small">
            <?php if (in_array($flaw, $flaws_mouthfeel)) { ?>
            <div class="form-check">
                <input class="form-check-input" type="checkbox" name="evalFlaws[]" id="evalFlaws<?php echo str_replace(' ', '', $flaw).$id_value++; ?>" value="<?php echo $label_mouthfeel.": ".$flaw; ?>" <?php if ($action == "edit") { if (strpos($row_eval['evalFlaws'],$label_mouthfeel.": ".$flaw) !== false) echo "checked"; } ?>>
                <label class="form-check-label"><?php echo $label_mouthfeel; ?></label>
            </div>
          <?php } ?>
        </div>
    </div>
    <?php } ?>
</section>