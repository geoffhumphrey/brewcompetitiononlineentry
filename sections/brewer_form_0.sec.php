<?php 

// Redirect if directly accessed without authenticated session
if ((!isset($_SESSION['loginUsername'])) || ((isset($_SESSION['loginUsername'])) && (!isset($base_url)))) {
    $redirect = "../../403.php";
    $redirect_go_to = sprintf("Location: %s", $redirect);
    header($redirect_go_to);
    exit();
}

if (((!$entrant_type_brewery) && ($table_assignment) && ($go != "admin")) && (($go != "entrant") && ($section != "step2"))) { 

?>
<!-- Already assigned to a table, can't change preferences -->
<input name="brewerJudge" type="hidden" value="<?php echo $row_brewer['brewerJudge']; ?>" />
<input name="brewerJudgeLocation" type="hidden" value="<?php echo $row_brewer['brewerJudgeLocation']; ?>" />
<input name="brewerJudgeID" type="hidden" value="<?php echo $row_brewer['brewerJudgeID']; ?>" />
<input name="brewerJudgeMead" type="hidden" value="<?php echo $row_brewer['brewerJudgeMead']; ?>" />
<input name="brewerJudgeRank" type="hidden" value="<?php echo $row_brewer['brewerJudgeRank']; ?>" />
<input name="brewerJudgeLikes" type="hidden" value="<?php echo $row_brewer['brewerJudgeLikes']; ?>" />
<input name="brewerJudgeDislikes" type="hidden" value="<?php echo $row_brewer['brewerJudgeDislikes']; ?>" />
<input name="brewerSteward" type="hidden" value="<?php echo $row_brewer['brewerSteward']; ?>" />
<input name="brewerStewardLocation" type="hidden" value="<?php echo $row_brewer['brewerStewardLocation']; ?>" />
<?php } // end  if ((!$entrant_type_brewery) && ($table_assignment) && ($go != "admin")) ?>

<?php if ($section == "step2") { ?>
<!-- ONLY for Setup -->
<input type="hidden" name="brewerBreweryName" value="">
<?php } ?>

<?php if ($pro_entrant) { ?>
<!-- PRO Edition Only: Entering on behalf of a brewery/org -->
<section id="pro-entrant">
    <div class="form-group"><!-- Form Group REQUIRED Text Input -->
        <label for="brewerBreweryName" class="col-lg-3 col-md-3 col-sm-4 col-xs-12 control-label text-warning"><i class="fa fa-sm fa-star"></i> <?php echo $label_organization." ".$label_name; ?></label>
        <div class="col-lg-9 col-md-9 col-sm-8 col-xs-12">
            <div class="input-group has-warning">
                <span class="input-group-addon" id="brewerBreweryName-addon1"><span class="fa fa-beer"></span></span>
                <!-- Input Here -->
                <input class="form-control" id="brewerBreweryName" name="brewerBreweryName" type="text" value="<?php if ($action == "edit") echo $row_brewer['brewerBreweryName']; ?>" data-error="<?php echo $register_text_044; ?>" placeholder="" data-error="<?php echo $brewer_text_032; ?>" required autofocus>
            </div>
            <div class="help-block"><?php echo $register_text_045; ?></div>
            <div class="help-block with-errors"></div>
        </div>
    </div><!-- ./Form Group -->
    <div class="form-group"><!-- Form Group Text Input -->
        <label for="brewerBreweryTTB" class="col-lg-3 col-md-3 col-sm-4 col-xs-12 control-label"><?php echo $label_organization." ".$label_ttb; ?></label>
        <div class="col-lg-9 col-md-9 col-sm-8 col-xs-12">
            <!-- Input Here -->
            <input class="form-control" id="brewerBreweryTTB" name="brewerBreweryTTB" type="text" value="<?php if ($action == "edit") echo $row_brewer['brewerBreweryTTB']; ?>" placeholder="">
        </div>
    </div><!-- ./Form Group -->
</section>
<?php } // end if ($pro_entrant) { ?>

<!-- Participant Information -->
<section id="participant-info">
    <!-- Participant Name -->
    <div class="form-group">
        <label for="brewerFirstName" class="col-lg-3 col-md-3 col-sm-4 col-xs-12 control-label text-warning"><i class="fa fa-sm fa-star"></i> <?php if ($_SESSION['prefsProEdition'] == 1) echo $label_contact." "; echo $label_first_name; ?></label>
        <div class="col-lg-9 col-md-9 col-sm-8 col-xs-12">
            <div class="input-group has-warning">
                <span class="input-group-addon" id="brewerFirstName-addon1"><span class="fa fa-user"></span></span>
                
                <input class="form-control" id="brewerFirstName" name="brewerFirstName" type="text" value="<?php if ($action == "edit") echo $row_brewer['brewerFirstName']; ?>" placeholder="" <?php if (($_SESSION['prefsProEdition'] == 0) && ($psort == "default")) echo "autofocus"; ?> data-error="<?php echo $brewer_text_024; ?>" required>
            </div>
            <div class="help-block with-errors"></div>
        </div>
    </div>
    <div class="form-group">
        <label for="brewerLastName" class="col-lg-3 col-md-3 col-sm-4 col-xs-12 control-label text-warning"><i class="fa fa-sm fa-star"></i> <?php if ($_SESSION['prefsProEdition'] == 1) echo $label_contact." "; echo $label_last_name; ?></label>
        <div class="col-lg-9 col-md-9 col-sm-8 col-xs-12">
            <div class="input-group has-warning">
                <span class="input-group-addon" id="brewerLastName-addon1"><span class="fa fa-user"></span></span>
                
                <input class="form-control" id="brewerLastName" name="brewerLastName" type="text" value="<?php if ($action == "edit") echo $row_brewer['brewerLastName']; ?>" placeholder="" data-error="<?php echo $brewer_text_025; ?>" required>
            </div>
            <div class="help-block"><?php if ($_SESSION['prefsProEdition'] == 0) echo $brewer_text_000; ?></div>
            <div class="help-block with-errors"></div>
        </div>
    </div>
    <?php if (($go != "admin") && ($section != "step2")) { ?>
    <div class="form-group"><!-- Form Group Radio INLINE -->
        <label for="changeSecurity" class="col-lg-3 col-md-3 col-sm-4 col-xs-12 control-label"><?php echo $label_change_security; ?></label>
        <div class="col-lg-9 col-md-9 col-sm-8 col-xs-12">
            <div class="input-group">
                <!-- Input Here -->
                <label class="radio-inline">
                    <input type="radio" id="change-security-1" name="changeSecurity" value="Y"> <?php echo $label_yes; ?>
                </label>
                <label class="radio-inline">
                    <input type="radio" id="change-security-0" name="changeSecurity" value="N" checked> <?php echo $label_no; ?>
                </label>
            </div>
            <span class="help-block"><?php echo $brewer_text_044; ?></span>
        </div>
    </div><!-- ./Form Group -->
    <div id="security-question-change">
        <div class="form-group">
            <label for="" class="col-lg-3 col-md-3 col-sm-4 col-xs-12 control-label"><?php if ($_SESSION['prefsProEdition'] == 1) echo $label_contact." "; echo $label_security_question; ?></label>
            <div class="col-lg-9 col-md-9 col-sm-8 col-xs-12">
                <div class="input-group">
                    <?php echo $security; ?>
                </div>
                <span class="help-block"><?php echo $brewer_text_001; ?></span>
                <div class="help-block with-errors"></div>
            </div>
        </div>
        <div class="form-group">
            <label for="" class="col-lg-3 col-md-3 col-sm-4 col-xs-12 control-label"><?php if ($_SESSION['prefsProEdition'] == 1) echo $label_contact." "; echo $label_security_answer; ?></label>
            <div class="col-lg-9 col-md-9 col-sm-8 col-xs-12">
                <div class="input-group has-warning">
                    <span class="input-group-addon" id="security-question-answer-addon1"><span class="fa fa-bullhorn"></span></span>
                    <input class="form-control" name="userQuestionAnswer" id="userQuestionAnswer" type="text" placeholder="" value="<?php if ($action == "edit") echo $_SESSION['userQuestionAnswer']; ?>" data-error="<?php echo $brewer_text_034; ?>">
                </div>
                <div class="help-block"><?php echo $register_text_050; ?></div>
                <div class="help-block with-errors"></div>
            </div>
        </div>
    </div>
    <?php } ?>
    <!-- Phone Numbers -->
    <div class="form-group">
        <label for="brewerPhone1" class="col-lg-3 col-md-3 col-sm-4 col-xs-12 control-label text-warning"><i class="fa fa-sm fa-star"></i> <?php if ($_SESSION['prefsProEdition'] == 1) echo $label_contact." "; echo $label_phone_primary; ?></label>
        <div class="col-lg-9 col-md-9 col-sm-8 col-xs-12">
            <div class="input-group has-warning">
                <span class="input-group-addon" id="brewerPhone1-addon1"><span class="fa fa-phone"></span></span>          
                <input class="form-control" id="brewerPhone1" name="brewerPhone1" type="text" value="<?php if ($action == "edit") echo $phone1; ?>" placeholder="" data-error="<?php echo $brewer_text_026; ?>" required>
            </div>
            <div class="help-block with-errors"></div>
        </div>
    </div>
    <div class="form-group">
        <label for="brewerPhone2" class="col-lg-3 col-md-3 col-sm-4 col-xs-12 control-label"><?php if ($_SESSION['prefsProEdition'] == 1) echo $label_contact." "; echo $label_phone_secondary; ?></label>
        <div class="col-lg-9 col-md-9 col-sm-8 col-xs-12">
            
            <input class="form-control" id="brewerPhone2" name="brewerPhone2" type="text" value="<?php if ($action == "edit") echo $phone2; ?>" placeholder="">
        </div>
    </div>
    <!-- Country of Residence -->
    <div class="form-group">
        <label for="brewerCountry" class="col-lg-3 col-md-3 col-sm-4 col-xs-12 control-label text-warning"><i class="fa fa-sm fa-star"></i> <?php if ($_SESSION['prefsProEdition'] == 1) echo $label_organization." "; echo $label_country; ?></label>
        <div class="col-lg-9 col-md-6 col-sm-8 col-xs-12">
            <div class="input-group has-warning">
                
                <select class="selectpicker" name="brewerCountry" id="brewerCountry" data-live-search="true" data-size="10" data-width="auto" data-show-tick="true" data-header="<?php echo $label_select_country; ?>" title="<?php echo $label_select_country; ?>" data-error="<?php echo $brewer_text_031; ?>" required>
                <option></option>
                <?php echo $country_select; ?>
                </select>
            </div>
            <div class="help-block with-errors"></div>
        </div>
    </div>
    <!-- Address -->
    <div class="form-group">
        <label for="brewerAddress" class="col-lg-3 col-md-3 col-sm-4 col-xs-12 control-label text-warning"><i class="fa fa-sm fa-star"></i> <?php if ($_SESSION['prefsProEdition'] == 1) echo $label_organization." "; echo $label_street_address; ?></label>
        <div class="col-lg-9 col-md-9 col-sm-8 col-xs-12">
            <div class="input-group has-warning">
                <span class="input-group-addon" id="brewerAddress-addon1"><span class="fa fa-home"></span></span>
                <input class="form-control" id="brewerAddress" name="brewerAddress" type="text" value="<?php if ($action == "edit") echo $row_brewer['brewerAddress']; ?>" placeholder="" data-error="<?php echo $brewer_text_027; ?>" required>
            </div>
            <div class="help-block with-errors"></div>
        </div>
    </div>

    <!-- City -->
    <div class="form-group">
        <label for="brewerCity" class="col-lg-3 col-md-3 col-sm-4 col-xs-12 control-label text-warning"><i class="fa fa-sm fa-star"></i> <?php if ($_SESSION['prefsProEdition'] == 1) echo $label_organization." "; echo $label_city; ?></label>
        <div class="col-lg-9 col-md-9 col-sm-8 col-xs-12">
            <div class="input-group has-warning">
                <input class="form-control" id="brewerCity" name="brewerCity" type="text" value="<?php if ($action == "edit") echo $row_brewer['brewerCity']; ?>" placeholder="" data-error="<?php echo $brewer_text_028; ?>" required>
            </div>
            <div class="help-block with-errors"></div>
        </div>
    </div>

    <!-- State or Province -->
    <div class="form-group">
        <label for="brewerState" class="col-lg-3 col-md-3 col-sm-4 col-xs-12 control-label text-warning"><i class="fa fa-sm fa-star"></i> <?php if ($_SESSION['prefsProEdition'] == 1) echo $label_organization." "; echo $label_state_province; ?></label>
        <div class="col-lg-9 col-md-9 col-sm-8 col-xs-12">
            <div id="non-us-state" class="input-group has-warning">
                <span class="input-group-addon" id="state-addon1"><span class="fa fa-home"></span></span>
                <input class="form-control" name="brewerStateNon" id="brewerStateNon" type="text" placeholder="" value="<?php if ($action == "edit") echo $row_brewer['brewerState']; ?>" data-header="<?php echo $label_select_state; ?>" title="<?php echo $label_select_state; ?>" data-error="<?php echo $register_text_029; ?>" required>
                <span class="input-group-addon" id="state-addon2" data-tooltip="true" title="<?php echo $form_required_fields_02; ?>"><span class="fa fa-star"></span>
            </div>
            <div id="us-state" class="has-warning">
                <select class="selectpicker" name="brewerStateUS" id="brewerStateUS" data-live-search="true" data-size="10" data-width="fit" data-header="<?php echo $label_select_state; ?>" title="<?php echo $label_select_state; ?>" data-error="<?php echo $register_text_030; ?>" required>
                    <?php echo $us_state_select; ?>
                </select>
            </div>
            <div id="aus-state" class="has-warning">
                <select class="selectpicker" name="brewerStateAUS" id="brewerStateAUS" data-live-search="true" data-size="10" data-width="fit" data-header="<?php echo $label_select_state; ?>" title="<?php echo $label_select_state; ?>" data-error="<?php echo $register_text_030; ?>" required>
                    <?php echo $aus_state_select; ?>
                </select>
            </div>
            <div id="ca-state" class="has-warning">
                <select class="selectpicker" name="brewerStateCA" id="brewerStateCA" data-live-search="true" data-size="10" data-width="fit" data-header="<?php echo $label_select_state; ?>" title="<?php echo $label_select_state; ?>" data-error="<?php echo $register_text_030; ?>" required>
                    <?php echo $ca_state_select; ?>
                </select>
            </div>
            <div class="help-block with-errors"></div>
        </div>
    </div>

    <!-- Zip/Postal Code -->
    <div class="form-group">
        <label for="brewerZip" class="col-lg-3 col-md-3 col-sm-4 col-xs-12 control-label text-warning"><i class="fa fa-sm fa-star"></i> <?php if ($_SESSION['prefsProEdition'] == 1) echo $label_organization." "; echo $label_zip; ?></label>
        <div class="col-lg-9 col-md-9 col-sm-8 col-xs-12">
            <div class="input-group has-warning">
                <input class="form-control" id="brewerZip" name="brewerZip" type="text" value="<?php if ($action == "edit") echo $row_brewer['brewerZip']; ?>" placeholder="" data-error="<?php echo $brewer_text_030; ?>" required>
            </div>
            <div class="help-block with-errors"></div>
        </div>
    </div>
</section>

<?php if (($_SESSION['prefsProEdition'] == 0) || (($_SESSION['prefsProEdition'] == 1) && ($entrant_type_brewery))) { ?>
<section id="entry-delivery"> 

    <div class="form-group"><!-- Form Group NOT REQUIRED Select -->
        <label for="brewerDropOff" class="col-lg-3 col-md-3 col-sm-4 col-xs-12 control-label text-warning"><i class="fa fa-sm fa-star"></i> <?php echo $label_drop_off; ?></label>
        <div class="col-lg-9 col-md-9 col-sm-8 col-xs-12">
            <select class="selectpicker" name="brewerDropOff" id="brewerDropOff" data-live-search="true" data-size="10" data-width="fit" data-show-tick="true" data-header="<?php echo $label_select_dropoff; ?>" title="<?php echo $label_select_dropoff; ?>" required>
            <?php if (!empty($dropoff_select)) { ?>
                <optgroup label="<?php echo $label_drop_offs; ?>">
                    <?php echo $dropoff_select; ?>
                </optgroup>
                <option data-divider="true"></option>
            <?php } if (!empty($_SESSION['contestShippingAddress'])) { ?>
                <option value="0" <?php if (($section == "step2") || (($action == "edit") && (($row_brewer['brewerDropOff'] == "0") || (empty($row_brewer['brewerDropOff']))))) echo "SELECTED"; ?>><?php echo $brewer_text_048; ?></option>
                <option data-divider="true"></option>
            <?php } ?>
                <option value="999" <?php if (($section == "step2") || (($action == "edit") && ($row_brewer['brewerDropOff'] == "999"))) echo "SELECTED"; ?>><?php echo $brewer_text_005; ?></option>
            </select>
            <?php if (!empty($_SESSION['contestShippingAddress'])) { ?><span class="help-block"><?php echo $brewer_text_050; ?></span><?php } ?>
            <span class="help-block"><?php echo $brewer_text_049; ?></span>
        </div>
    </div>
</section>
<?php } ?>