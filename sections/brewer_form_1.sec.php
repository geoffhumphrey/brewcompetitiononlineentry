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

if ($_SESSION['prefsProEdition'] == 0) { 

?>
<!-- Not a Brewery; clubs, Pro-Am, AHA, Staff -->
<section id="participant-clubs">
    <div class="form-group">
        <label for="brewerClubs" class="col-lg-3 col-md-3 col-sm-4 col-xs-12 control-label"><?php echo $label_club; ?></label>
        <div class="col-lg-9 col-md-6 col-sm-8 col-xs-12">
        
        <select class="selectpicker" name="brewerClubs" id="brewerClubs" data-live-search-normalize="true" data-live-search="true" data-size="10" data-width="fit" data-show-tick="true" data-header="<?php echo $label_select_club; ?>" title="<?php echo $label_select_club; ?>">
            <option value="" <?php if (($action == "edit") && (empty($row_brewer['brewerClubs']))) echo "SELECTED"; ?>>None</option>
            <option value="Other" <?php if ($club_other) echo "SELECTED"; ?>>Other</option>
            <option data-divider="true"></option>
            <?php echo $club_options; ?>
        </select>
        <span class="help-block"><?php echo $brewer_text_023; ?></span>
        <?php echo $club_alert; ?>
        </div>
    </div>
    <div id="brewerClubsOther" class="form-group">
        <label for="brewerClubsOther" class="col-lg-3 col-md-3 col-sm-4 col-xs-12 control-label"><?php echo $label_club_enter; ?></label>
        <div class="col-lg-9 col-md-9 col-sm-8 col-xs-12">
            <input class="form-control" name="brewerClubsOther" type="text" value="<?php if ($action == "edit") echo $row_brewer['brewerClubs']; ?>" placeholder="" pattern="[^%&\x22\x27]+">
            <div class="help-block">
                <p><?php echo $brewer_text_046; ?></p>
            </div>
        </div>
    </div>
</section>
<section id="proAm">
    <div class="form-group">
        <label for="" class="col-lg-3 col-md-3 col-sm-4 col-xs-12 control-label"><?php echo $label_pro_am; ?></label>
        <div class="col-lg-9 col-md-9 col-sm-8 col-xs-12">
            <div class="input-group">
                <label class="radio-inline">
                    <input type="radio" name="brewerProAm" value="1" id="brewerProAm_1" <?php if (($section != "step2") && ($row_brewer['brewerProAm'] == "1")) echo "CHECKED"; ?> /> <?php echo $label_yes; ?>
                </label>
                <label class="radio-inline">
                    <input type="radio" name="brewerProAm" value="0" id="brewerProAm_0" <?php if (($section != "step2") && ($row_brewer['brewerProAm'] == "0") || (empty($row_brewer['brewerProAm']))) echo "CHECKED"; ?> /> <?php echo $label_no; ?>
                </label>
            </div>
            <div class="help-block">
                <p><?php echo $brewer_text_041; ?></p>
                <p><?php echo $brewer_text_043; ?></p>
                <p><?php echo $brewer_text_042; ?></p>
            </div>
        </div>
    </div>
</section>
<section id="aha-number">
    <div class="form-group">
        <label for="brewerAHA" class="col-lg-3 col-md-3 col-sm-4 col-xs-12 control-label"><?php echo $label_aha_number; ?></label>
        <div class="col-lg-9 col-md-9 col-sm-8 col-xs-12">
            <input class="form-control" name="brewerAHA" id="brewerAHA" type="text" pattern="[A-Za-z0-9]+" placeholder="" data-error="<?php echo $brew_text_019; ?>" value="<?php if ($action == "edit") echo $row_brewer['brewerAHA']; ?>" placeholder="">
            <span id="ahaProAmText" class="help-block"><?php echo $brewer_text_003; ?></span>
        </div>
    </div>
</section>
<section id="mhp-number">
    <div class="form-group">
        <label for="brewerMHP" class="col-lg-3 col-md-3 col-sm-4 col-xs-12 control-label"><?php echo $label_mhp_number; ?></label>
        <div class="col-lg-9 col-md-9 col-sm-8 col-xs-12">
            <input class="form-control" name="brewerMHP" id="brewerMHP" type="text" pattern="\d*" placeholder="" data-error="<?php echo $brew_text_019; ?>" value="<?php if ($action == "edit") echo $row_brewer['brewerMHP']; ?>" placeholder="">
            <span class="help-block"><?php echo $brewer_text_053; ?></span>
        </div>
    </div>
</section>
<?php } else { ?>
    <input type="hidden" name="brewerProAm" value="0">
    <input type="hidden" name="brewerMHP" value="0">
<?php } ?>
<section id="staff-preferences">
    <div class="form-group">
        <label for="brewerStaff" class="col-lg-3 col-md-3 col-sm-4 col-xs-12 control-label"><?php echo $label_staff; ?></label>
        <div class="col-lg-9 col-md-9 col-sm-8 col-xs-12">
            <div class="input-group">
                
                <label class="radio-inline">
                    <input type="radio" name="brewerStaff" value="Y" id="brewerStaff_0" <?php if (($action == "edit") && ($row_brewer['brewerStaff'] == "Y")) echo "checked"; ?>> <?php echo $label_yes; ?>
                </label>
                <label class="radio-inline">
                    <input type="radio" name="brewerStaff" value="N" id="brewerStaff_1" <?php if (($action == "edit") && (($row_brewer['brewerStaff'] == "N") || ($row_brewer['brewerStaff'] == ""))) echo "checked";  if ($section == "step2") echo "checked"; ?>> <?php echo $label_no; ?>
                </label>
            </div>
            <span class="help-block"><?php echo "<p>".$brewer_text_020."</p>"; if (!empty($staff_location_avail)) echo "<p id=\"staff-help\" class=\"alert alert-info\">".$brewer_text_047."</p>";  ?></span>
        </div>
    </div>
    <?php if (!empty($staff_location_avail)) { ?>
    <div id="brewerStaffFields">
        <div class="form-group"><!-- Form Group NOT REQUIRED Select -->
            <label for="brewerStaffLocation" class="col-lg-3 col-md-3 col-sm-4 col-xs-12 control-label"><?php echo "Staff Availability"; ?></label>
            <div class="col-lg-9 col-md-9 col-sm-8 col-xs-12">
            <?php echo $staff_location_avail; ?>
            </div>
        </div>
    </div>
    <?php } // end if (!empty($staff_location_avail)) ?>
</section>