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
<?php } 

if (($pro_entrant) && (!$show_judge_steward_fields)) { 

    $brewerBreweryInfo = "";
    $brewerBreweryTTB = "";
    $brewerBreweryProd = "";

    if (($action == "edit") && (!empty($row_brewer['brewerBreweryInfo']))) {
        $brewerBreweryInfo = json_decode($row_brewer['brewerBreweryInfo'],true);
        if ((isset($brewerBreweryInfo['TTB'])) && (!empty($brewerBreweryInfo['TTB']))) $brewerBreweryTTB = $brewerBreweryInfo['TTB'];
        if ((isset($brewerBreweryInfo['Production'])) && (!empty($brewerBreweryInfo['Production']))) $brewerBreweryProd = $brewerBreweryInfo['Production'];
    }

?>
<!-- PRO Edition Only: Entering on behalf of a brewery/org -->
<section id="pro-entrant">
    <div class="mb-3 row">
        <label for="brewerBreweryName" class="col-xs-12 col-sm-3 col-lg-2 col-form-label text-teal"><i class="fa fa-star me-1"></i><strong><?php echo $label_organization." ".$label_name; ?></strong></label>
        <div class="col-xs-12 col-sm-9 col-lg-10">
            <input class="form-control" id="brewerBreweryName" name="brewerBreweryName" type="text" value="<?php if ($action == "edit") echo $row_brewer['brewerBreweryName']; ?>" data-error="<?php echo $register_text_044; ?>" placeholder="" data-error="<?php echo $brewer_text_032; ?>" required autofocus>
            <div class="help-block mb-1 invalid-feedback text-danger"></div>
            <div class="help-block"><?php echo $register_text_045; ?></div>
        </div>
    </div>
    <div class="mb-3 row">
        <label for="brewerBreweryTTB" class="col-xs-12 col-sm-3 col-lg-2 col-form-label"><strong><?php echo $label_organization." ".$label_ttb; ?></strong></label>
        <div class="col-xs-12 col-sm-9 col-lg-10">
            <input class="form-control" id="brewerBreweryTTB" name="brewerBreweryTTB" type="text" value="<?php if ($action == "edit") echo $brewerBreweryTTB; ?>" placeholder="">
        </div>
    </div>
    <?php if ($_SESSION['prefsStyleSet'] == "NWCiderCup") { ?>
    <div class="mb-3 row">
        <label for="brewerBreweryProd" class="col-xs-12 col-sm-3 col-lg-2 col-form-label"><strong><?php echo $label_organization." ".$label_yearly_volume; ?></strong></label>
        <div class="col-xs-12 col-sm-9 col-lg-10">
            <div class="form-check">
                <input class="form-check-input" type="radio" name="brewerBreweryProd" value="1 - 10,000" id="brewerBreweryProd_5" <?php if (($action == "edit") && (strpos($brewerBreweryProd, "1 - 10,000") !== false)) echo "CHECKED"; if (($action == "add") || (empty($brewerBreweryInfo))) echo "CHECKED"; ?> />
                <label>1 - 10,000 <?php echo $label_gallons; ?></label>
            </div>
            <div class="form-check">
                <input class="form-check-input" type="radio" name="brewerBreweryProd" value="10,001 - 250,000" id="brewerBreweryProd_6" <?php if (($action == "edit") && (strpos($brewerBreweryProd, "10,001 - 250,000") !== false)) echo "CHECKED"; ?> />
                <label>10,001 - 250,000 <?php echo $label_gallons; ?></label>
            </div>
            <div class="form-check">
                <input class="form-check-input" type="radio" name="brewerBreweryProd" value="250,001+" id="brewerBreweryProd_7" <?php if (($action == "edit") && (strpos($brewerBreweryProd, "250,001+") !== false)) echo "CHECKED"; ?> />
                <label>250,001+ <?php echo $label_gallons; ?></label>
            </div>
            <div>
                <input class="display-d" type="radio" name="brewerBreweryProd" value="" style="display: none;">
                <div class="help-block invalid-feedback text-danger"></div>
            </div>
        </div>
    </div>
    <input type="hidden" name="brewerBreweryProdMeas" value="<?php echo $label_gallons; ?>">
    <?php } else { ?>
    <div class="mb-3 row">
        <label id="brewery-prod-label" for="brewerBreweryProd" class="col-xs-12 col-sm-3 col-lg-2 col-form-label text-teal"><i id="brewery-prod-label-icon" class="fa fa-star me-1"></i> <strong><?php echo $label_organization." ".$label_yearly_volume; ?></strong></label>
        <div class="col-xs-12 col-sm-9 col-lg-10">
            <div class="form-check">
                <input class="form-check-input" type="radio" name="brewerBreweryProd" value="1 - 5,000" id="brewerBreweryProd_0" <?php if (($action == "edit") && (strpos($brewerBreweryProd, "1 - 5,000") !== false)) echo "CHECKED"; ?> />
                <label>1 - 5,000</label>
            </div>
            <div class="form-check">
                <input class="form-check-input" type="radio" name="brewerBreweryProd" value="5,001 - 15,000" id="brewerBreweryProd_1" <?php if (($action == "edit") && (strpos($brewerBreweryProd, "5,001 - 15,000") !== false)) echo "CHECKED"; ?> />
                <label>5,001 - 15,000</label>
            </div>
            <div class="form-check">
                <input class="form-check-input" type="radio" name="brewerBreweryProd" value="15,001 - 60,000" id="brewerBreweryProd_2" <?php if (($action == "edit") && (strpos($brewerBreweryProd, "15,001 - 60,000") !== false)) echo "CHECKED"; ?> />
                <label>15,001 - 60,000</label>
            </div>
            <div class="form-check">
                <input class="form-check-input" type="radio" name="brewerBreweryProd" value="60,001 - 599,999" id="brewerBreweryProd_3" <?php if (($action == "edit") && (strpos($brewerBreweryProd, "60,001 - 599,999") !== false)) echo "CHECKED"; ?> />
                <label>60,001 - 599,999</label>
            </div>
            <div class="form-check">
                <input class="form-check-input" type="radio" name="brewerBreweryProd" value="6,000,000+" id="brewerBreweryProd_4" <?php if (($action == "edit") && (strpos($brewerBreweryProd, "6,000,000+") !== false)) echo "CHECKED"; ?> />
                <label>6,000,000+</label>
            </div>
            <div>
                <input class="display-d" type="radio" name="brewerBreweryProd" value="" style="display: none;">
                <div class="help-block invalid-feedback text-danger"></div>
            </div>

            <div id="brewerBreweryProdMeas" class="mt-2">
                <div class="form-check form-check-inline">
                    <input class="form-check-input" type="radio" name="brewerBreweryProdMeas" value="<?php echo $label_barrels; ?>" id="brewerBreweryProdMeas_0" <?php if (($action == "edit") && (strpos($brewerBreweryProd, $label_barrels) !== false)) echo "CHECKED"; if ($action == "add") echo "CHECKED"; ?> />
                    <label class="form-check-label"><?php echo $label_barrels; ?></label>
                </div>
                <div class="form-check form-check-inline">
                    <input class="form-check-input" type="radio" name="brewerBreweryProdMeas" value="<?php echo $label_hectoliters; ?>" id="brewerBreweryProdMeas_1" <?php if (($action == "edit") && (strpos($brewerBreweryProd, $label_hectoliters) !== false)) echo "CHECKED"; ?> />
                    <label class="form-check-label"><?php echo $label_hectoliters; ?></label>
                </div>
            </div>
            <div>
                <input class="display-d" type="radio" name="brewerBreweryProdMeas" value="" style="display: none;">
                <div class="help-block invalid-feedback text-danger"></div>
            </div>
        </div>
    </div>
    <?php } ?>
</section>
<?php } // end if ($pro_entrant) ?>

<!-- Participant Information -->
<section id="participant-info">
    <!-- Participant Name -->
    <div class="mb-3 row">
        <label for="brewerFirstName" class="col-xs-12 col-sm-3 col-lg-2 col-form-label text-teal"><strong><i class="fa fa-star me-1"></i> <?php if ($_SESSION['prefsProEdition'] == 1) echo $label_contact." "; echo $label_first_name; ?></strong></label>
        <div class="col-xs-12 col-sm-9 col-lg-10">
            <input class="form-control" id="brewerFirstName" name="brewerFirstName" type="text" value="<?php if ($action == "edit") echo $row_brewer['brewerFirstName']; ?>" placeholder="" <?php if (($_SESSION['prefsProEdition'] == 0) && ($psort == "default")) echo "autofocus"; ?> data-error="<?php echo $brewer_text_024; ?>" required>
            <div class="help-block mb-1 invalid-feedback text-danger"></div>
        </div>
    </div>
    <div class="mb-3 row">
        <label for="brewerLastName" class="col-xs-12 col-sm-3 col-lg-2 col-form-label text-teal"><strong><i class="fa fa-star me-1"></i> <?php if ($_SESSION['prefsProEdition'] == 1) echo $label_contact." "; echo $label_last_name; ?></strong></label>
        <div class="col-xs-12 col-sm-9 col-lg-10">
            <input class="form-control" id="brewerLastName" name="brewerLastName" type="text" value="<?php if ($action == "edit") echo $row_brewer['brewerLastName']; ?>" placeholder="" data-error="<?php echo $brewer_text_025; ?>" required>
            <div class="help-block"><?php if ($_SESSION['prefsProEdition'] == 0) echo $brewer_text_000; ?></div>
            <div class="help-block mb-1 invalid-feedback text-danger"></div>
        </div>
    </div>
    <?php if (($go != "admin") && ($section != "step2")) { ?>
    <div class="mb-3 row">
        <label for="changeSecurity" class="col-xs-12 col-sm-3 col-lg-2 col-form-label"><strong><?php echo $label_change_security; ?></strong></label>
        <div class="col-xs-12 col-sm-9 col-lg-10">
            <div class="form-check form-check-inline">
                <input class="form-check-input" type="radio" id="change-security-1" name="changeSecurity" value="Y"> 
                <label class="form-check-label"><?php echo $label_yes; ?></label>
            </div>
            <div class="form-check form-check-inline">
                <input class="form-check-input" type="radio" id="change-security-0" name="changeSecurity" value="N" checked> 
                <label class="form-check-label"><?php echo $label_no; ?></label>
            </div>
            <div class="help-block"><?php echo $brewer_text_044; ?></div>
        </div>
    </div>
    <div id="security-question-change">
        <div class="mb-3 row">
            <label for="security" class="col-xs-12 col-sm-3 col-lg-2 col-form-label text-teal"><i class="fa fa-star me-1"></i><strong><?php if ($_SESSION['prefsProEdition'] == 1) echo $label_contact." "; echo $label_security_question; ?></strong></label>
            <div class="col-xs-12 col-sm-9 col-lg-10">
                <?php echo $security; ?>
                <div class="help-block"><?php echo $brewer_text_001; ?></div>
                <div class="help-block mb-1 invalid-feedback text-danger"></div>
            </div>
        </div>
        <div>
            <input class="display-d" type="radio" name="userQuestion" value="" style="display: none;">
            <div class="help-block invalid-feedback text-danger"></div>
        </div>
        <div class="mb-3 row">
            <label for="userQuestionAnswer" class="col-xs-12 col-sm-3 col-lg-2 col-form-label text-teal"><i class="fa fa-star me-1"></i><strong><?php if ($_SESSION['prefsProEdition'] == 1) echo $label_contact." "; echo $label_security_answer; ?></strong></label>
            <div class="col-xs-12 col-sm-9 col-lg-10">
                <input class="form-control" name="userQuestionAnswer" id="userQuestionAnswer" type="text" placeholder="" value="<?php if ($action == "edit") echo $_SESSION['userQuestionAnswer']; ?>" data-error="<?php echo $brewer_text_034; ?>">
                <div class="help-block"><?php echo $register_text_050; ?></div>
                <div class="help-block mb-1 invalid-feedback text-danger"></div>
            </div>
        </div>
    </div>
    <?php } ?>

    <!-- Phone Number -->
    <div class="mb-3 row">
        <label for="brewerPhone1" class="col-xs-12 col-sm-3 col-lg-2 col-form-label text-teal"><i class="fa fa-star me-1"></i><strong><?php if ($_SESSION['prefsProEdition'] == 1) echo $label_contact." "; echo $label_phone_primary; ?></strong></label>
        <div class="col-xs-12 col-sm-9 col-lg-10">
            <input class="form-control" id="brewerPhone1" name="brewerPhone1" type="text" value="<?php if ($action == "edit") echo $phone1; ?>" placeholder="" required>
            <div class="help-block mb-1 invalid-feedback text-danger"><?php echo $brewer_text_026; ?></div>
        </div>
    </div>

    <!--
    <div class="mb-3 row">
        <label for="brewerPhone2" class="col-xs-12 col-sm-3 col-lg-2 col-form-label"><strong><?php if ($_SESSION['prefsProEdition'] == 1) echo $label_contact." "; echo $label_phone_secondary; ?></strong></label>
        <div class="col-xs-12 col-sm-9 col-lg-10">
            <input class="form-control" id="brewerPhone2" name="brewerPhone2" type="text" value="<?php if ($action == "edit") echo $phone2; ?>" placeholder="">
        </div>
    </div>
    -->

    <!-- Country of Residence -->
    <div class="mb-3 row">
        <label for="brewerCountry" class="col-xs-12 col-sm-3 col-lg-2 col-form-label text-teal"><i class="fa fa-star me-1"></i><strong><?php if ($_SESSION['prefsProEdition'] == 1) echo $label_organization." "; echo $label_country; ?></strong></label>
        <div class="col-xs-12 col-sm-9 col-lg-10">
                <select class="form-select selectpicker mb-1 bootstrap-select" name="brewerCountry" id="brewerCountry" placeholder="<?php echo $label_select_country; ?>" title="<?php echo $label_select_country; ?>" required>
                <option value=""><?php echo $label_select_country; ?></option>
                <?php echo $country_select; ?>
                </select>
            <div class="help-block mb-1 invalid-feedback text-danger"><?php echo $brewer_text_031; ?></div>
        </div>
    </div>

    <!-- Address -->
    <div class="mb-3 row">
        <label for="brewerAddress" class="col-xs-12 col-sm-3 col-lg-2 col-form-label text-teal"><i class="fa fa-star me-1"></i><strong><?php if ($_SESSION['prefsProEdition'] == 1) echo $label_organization." "; echo $label_street_address; ?></strong></label>
        <div class="col-xs-12 col-sm-9 col-lg-10">
            <input class="form-control" id="brewerAddress" name="brewerAddress" type="text" value="<?php if ($action == "edit") echo $row_brewer['brewerAddress']; ?>" placeholder="" required>
            <div class="help-block mb-1 invalid-feedback text-danger"><?php echo $brewer_text_027; ?></div>
        </div>
    </div>

    <!-- City -->
    <div class="mb-3 row">
        <label for="brewerCity" class="col-xs-12 col-sm-3 col-lg-2 col-form-label text-teal"><i class="fa fa-star me-1"></i><strong><?php if ($_SESSION['prefsProEdition'] == 1) echo $label_organization." "; echo $label_city; ?></strong></label>
        <div class="col-xs-12 col-sm-9 col-lg-10">
            <input class="form-control" id="brewerCity" name="brewerCity" type="text" value="<?php if ($action == "edit") echo $row_brewer['brewerCity']; ?>" placeholder="" required>
            <div class="help-block mb-1 invalid-feedback text-danger"><?php echo $brewer_text_028; ?></div>
        </div>
    </div>

    <!-- State or Province -->
    <div class="mb-3 row">
        <label for="brewerState" class="col-xs-12 col-sm-3 col-lg-2 col-form-label text-teal"><i class="fa fa-star me-1"></i><strong><?php if ($_SESSION['prefsProEdition'] == 1) echo $label_organization." "; echo $label_state_province; ?></strong></label>
        <div class="col-xs-12 col-sm-9 col-lg-10">
            
            <div id="non-us-state">
                <input class="form-control" name="brewerStateNon" id="brewerStateNon" type="text" placeholder="" value="<?php if ($action == "edit") echo $row_brewer['brewerState']; ?>">
                <div class="help-block mb-1 invalid-feedback text-danger"><?php echo $register_text_029; ?></div>
            </div>
            
            <div id="us-state">
                <select class="form-select selectpicker mb-1 bootstrap-select" name="brewerStateUS" id="brewerStateUS" placeholder="<?php echo $label_select_state; ?>" title="<?php echo $label_select_state; ?>" data-error="<?php echo $register_text_030; ?>">
                    <option value=""><?php echo $label_select_state; ?></option>
                    <?php echo $us_state_select; ?>
                </select>
                <div class="help-block mb-1 invalid-feedback text-danger"><?php echo $register_text_030; ?></div>
            </div>
            
            <div id="aus-state">
                <select class="form-select selectpicker mb-1 bootstrap-select" name="brewerStateAUS" id="brewerStateAUS" placeholder="<?php echo $label_select_state; ?>" title="<?php echo $label_select_state; ?>" data-error="<?php echo $register_text_030; ?>">
                    <option value=""><?php echo $label_select_state; ?></option>
                    <?php echo $aus_state_select; ?>
                </select>
                <div class="help-block mb-1 invalid-feedback text-danger"><?php echo $register_text_030; ?></div>
            </div>

            <div id="ca-state">
                <select class="form-select selectpicker mb-1 bootstrap-select" name="brewerStateCA" id="brewerStateCA" placeholder="<?php echo $label_select_state; ?>" title="<?php echo $label_select_state; ?>" data-error="<?php echo $register_text_030; ?>">
                    <option value=""><?php echo $label_select_state; ?></option>
                    <?php echo $ca_state_select; ?>
                </select>
                <div class="help-block mb-1 invalid-feedback text-danger"><?php echo $register_text_030; ?></div>
            </div> 
                       
        </div>    
    </div>
    <!-- Zip/Postal Code -->
    <div class="mb-3 row">
        <label for="brewerZip" class="col-xs-12 col-sm-3 col-lg-2 col-form-label text-teal"><i class="fa fa-star me-1"></i><strong><?php if ($_SESSION['prefsProEdition'] == 1) echo $label_organization." "; echo $label_zip; ?></strong></label>
        <div class="col-xs-12 col-sm-9 col-lg-10">
                <input class="form-control" id="brewerZip" name="brewerZip" type="text" value="<?php if ($action == "edit") echo $row_brewer['brewerZip']; ?>" placeholder="" required>
            <div class="help-block mb-1 invalid-feedback text-danger"><?php echo $brewer_text_030; ?></div>
        </div>
    </div>
</section>

<?php if (($_SESSION['prefsProEdition'] == 0) || (($_SESSION['prefsProEdition'] == 1) && ($entrant_type_brewery))) { ?>
<section id="entry-delivery"> 
    <div class="mb-3 row">
        <label for="brewerDropOff" class="col-xs-12 col-sm-3 col-lg-2 col-form-label text-teal"><i class="fa fa-star me-1"></i><strong><?php echo $label_drop_off; ?></strong></label>
        <div class="col-xs-12 col-sm-9 col-lg-10">
            <select class="form-select selectpicker mb-1 bootstrap-select" name="brewerDropOff" id="brewerDropOff" placeholder="<?php echo $label_select_dropoff; ?>" title="<?php echo $label_select_dropoff; ?>" required>
            <option value=""><?php echo $label_select_dropoff; ?></option>
            <?php if (!empty($_SESSION['contestShippingAddress'])) { ?>
                <option value="0" <?php if (($section == "step2") || (($action == "edit") && (($row_brewer['brewerDropOff'] == "0") || (empty($row_brewer['brewerDropOff']))))) echo "SELECTED"; ?>><?php echo $brewer_text_048; ?></option>
            <?php } ?>
            <option value="999" <?php if (($section == "step2") || (($action == "edit") && ($row_brewer['brewerDropOff'] == "999"))) echo "SELECTED"; ?>><?php echo $brewer_text_005; ?></option>
            <?php if (!empty($dropoff_select)) { ?>
                <optgroup label="<?php echo $label_drop_offs; ?>">
                <?php echo $dropoff_select; ?>
                </optgroup>
            <?php } ?>
            </select>
            <?php if (!empty($_SESSION['contestShippingAddress'])) { ?>
            <div class="help-block"><?php echo $brewer_text_050; ?></div>
            <?php } ?>
            <div class="help-block"><?php echo $brewer_text_049; ?></div>
        </div>
    </div>
</section>
<?php } ?>