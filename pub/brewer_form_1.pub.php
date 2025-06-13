<?php 

if ($_SESSION['prefsProEdition'] == 0) { 

?>
<!-- Not a Brewery; clubs, Pro-Am, AHA, Staff -->
<section id="participant-clubs">
    <div class="mb-3 row">
        <label for="brewerClubs" class="col-xs-12 col-sm-3 col-lg-2 col-form-label"><strong><?php echo $label_club; ?></strong></label>
        <div class="col-lg-9 col-md-6 col-sm-8 col-xs-12">
        
        <select class="form-select mb-1 bootstrap-select" name="brewerClubs" id="brewerClubs" placeholder="<?php echo $label_select_club; ?>" title="<?php echo $label_select_club; ?>">
            <option value="0" <?php if (($action == "edit") && (empty($row_brewer['brewerClubs']))) echo "SELECTED"; ?>>None</option>
            <option value="Other" <?php if ($club_other) echo "SELECTED"; ?>>Other</option>
            <optgroup label="<?php echo $label_select_club; ?>"></optgroup>
            <?php echo $club_options; ?>
        </select>
        <span class="help-block"><?php echo $brewer_text_023; ?></span>
        <?php echo $club_alert; ?>
        </div>
    </div>
    <div id="brewerClubsOther" class="mb-3 row">
        <label for="brewerClubsOther" class="col-xs-12 col-sm-3 col-lg-2 col-form-label"><strong><?php echo $label_club_enter; ?></strong></label>
        <div class="col-xs-12 col-sm-9 col-lg-10">
            <input class="form-control" name="brewerClubsOther" type="text" value="<?php if ($action == "edit") echo $row_brewer['brewerClubs']; ?>" placeholder="" pattern="[^%&\x22\x27]+">
            <div class="help-block">
                <p><?php echo $brewer_text_046; ?></p>
            </div>
        </div>
    </div>
</section>
<section id="proAm">
    <div class="mb-3 row">
        <label for="" class="col-xs-12 col-sm-3 col-lg-2 col-form-label"><strong><?php echo $label_pro_am; ?></strong></label>
        <div class="col-xs-12 col-sm-9 col-lg-10">
            <div class="form-check form-check-inline">
                <input class="form-check-input" type="radio" name="brewerProAm" value="1" id="brewerProAm_1" <?php if (($section != "step2") && ($row_brewer['brewerProAm'] == "1")) echo "CHECKED"; ?> />
                <label class="form-check-label"><?php echo $label_yes; ?></label>
            </div>    
            <div class="form-check form-check-inline">
                <input class="form-check-input" type="radio" name="brewerProAm" value="0" id="brewerProAm_0" <?php if (($section != "step2") && ($row_brewer['brewerProAm'] == "0") || (empty($row_brewer['brewerProAm']))) echo "CHECKED"; ?> />
                <label class="form-check-label"><?php echo $label_no; ?></label>
            </div>
            <div class="form-check form-check-inline">
                <input class="form-check-input" type="radio" name="brewerProAm" value="2" id="brewerProAm_2" <?php if (($section != "step2") && ($row_brewer['brewerProAm'] == "2")) echo "CHECKED"; ?> />
                <label class="form-check-label"><?php echo $label_opt_out; ?></label>
            </div>
            <div class="help-block">
                <p><?php echo $brewer_text_041; ?></p>
                <p><?php echo $brewer_text_043; ?></p>
                <p><?php echo $brewer_text_042; ?></p>
                <p><?php echo $brewer_text_056; ?></p>
            </div>
        </div>
    </div>
</section>
<section id="aha-number">
    <div class="mb-3 row">
        <label for="brewerAHA" class="col-xs-12 col-sm-3 col-lg-2 col-form-label"><strong><?php echo $label_aha_number; ?></strong></label>
        <div class="col-xs-12 col-sm-9 col-lg-10">
            <input class="form-control" name="brewerAHA" id="brewerAHA" type="text" pattern="[A-Za-z0-9]+" placeholder="" data-error="<?php echo $brew_text_019; ?>" value="<?php if ($action == "edit") echo $row_brewer['brewerAHA']; ?>" placeholder="">
            <div id="ahaProAmText" class="help-block"><?php echo $brewer_text_003; ?></div>
        </div>
    </div>
</section>
<section id="mhp-number">
    <div class="mb-3 row">
        <label for="brewerMHP" class="col-xs-12 col-sm-3 col-lg-2 col-form-label"><strong><?php echo $label_mhp_number; ?></strong></label>
        <div class="col-xs-12 col-sm-9 col-lg-10">
            <input class="form-control" name="brewerMHP" id="brewerMHP" type="text" pattern="\d*" placeholder="" value="<?php if ($action == "edit") echo $row_brewer['brewerMHP']; ?>" placeholder="">
            <div class="help-block"><?php echo $brewer_text_053; ?></div>
        </div>
    </div>
</section>
<?php } else { ?>
    <input type="hidden" name="brewerProAm" value="0">
    <input type="hidden" name="brewerMHP" value="0">
<?php } ?>
<a name="staff-info"></a>
<section id="staff-preferences">
    <div class="mb-3 row">
        <label for="brewerStaff" class="col-xs-12 col-sm-3 col-lg-2 col-form-label"><strong><?php echo $label_staff; ?></strong></label>
        <div class="col-xs-12 col-sm-9 col-lg-10">
            <div class="form-check form-check-inline">
                <input class="form-check-input" type="radio" name="brewerStaff" value="Y" id="brewerStaff_0" <?php if (($action == "edit") && ($row_brewer['brewerStaff'] == "Y")) echo "checked"; ?>>
                <label class="form-check-label"><?php echo $label_yes; ?></label>
            </div>
            <div class="form-check form-check-inline">
                <input class="form-check-input" type="radio" name="brewerStaff" value="N" id="brewerStaff_1" <?php if (($action == "edit") && (($row_brewer['brewerStaff'] == "N") || ($row_brewer['brewerStaff'] == ""))) echo "checked";  if ($section == "step2") echo "checked"; ?>>
                <label class="form-check-label"><?php echo $label_no; ?></label>
            </div>
            <div class="help-block"><?php echo "<p>".$brewer_text_020."</p>"; if (!empty($staff_location_avail)) echo "<p id=\"staff-help\" class=\"alert alert-teal fst-normall\">".$brewer_text_047."</p>";  ?></div>
        </div>
    </div>
    <?php if (!empty($staff_location_avail)) { ?>
    <div id="brewerStaffFields">
        <div class="mb-3 row">
            <label for="brewerStaffLocation" class="col-xs-12 col-sm-3 col-lg-2 col-form-label"><strong><?php echo $label_staff_availability; ?></strong></label>
            <div class="col-xs-12 col-sm-9 col-lg-10">
            <?php echo $staff_location_avail; ?>
            </div>
        </div>
    </div>
    <?php } // end if (!empty($staff_location_avail)) ?>
</section>