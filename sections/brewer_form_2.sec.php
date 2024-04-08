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

if (((!$table_assignment) || ($go == "admin")) && (!$entrant_type_brewery)) { 

    if (((!$judge_limit) && ($go == "account")) || (($_SESSION['userLevel'] <= 1) && (($go == "admin") || ($go == "account")))) { 
        $judge_checked = FALSE;
        if ((($action == "add") || ($action == "register")) && ($go == "judge")) $judge_checked = TRUE;
        if (($action == "edit") && ($row_brewer['brewerJudge'] == "Y")) $judge_checked = TRUE;

?>

<section id="judge-preferences">
    <div class="form-group">
        <label for="brewerJudge" class="col-lg-3 col-md-3 col-sm-4 col-xs-12 control-label"><?php echo $label_judging; ?></label>
        <div class="col-lg-9 col-md-9 col-sm-8 col-xs-12">
            <div class="input-group">
                <label class="radio-inline">
                    <input type="radio" name="brewerJudge" value="Y" id="brewerJudge_0" <?php if ($judge_checked) echo "CHECKED"; ?>> <?php echo $label_yes; ?>
                </label>
                <label class="radio-inline">
                    <input type="radio" name="brewerJudge" value="N" id="brewerJudge_1" <?php if (!$judge_checked) echo "CHECKED"; ?>> <?php echo $label_no; ?>
                </label>
            </div>
            <span class="help-block"><?php echo $brewer_text_006; ?></span>
        </div>
    </div>

    <div id="bjcp-id" class="form-group">
        <label for="brewerJudgeID" class="col-lg-3 col-md-3 col-sm-4 col-xs-12 control-label"><?php echo $label_bjcp_id; ?></label>
        <div class="col-lg-9 col-md-9 col-sm-8 col-xs-12">
            
            <input class="form-control" id="brewerJudgeID" name="brewerJudgeID" type="text" value="<?php if ($action == "edit") echo $row_brewer['brewerJudgeID']; ?>" placeholder="" <?php if ($psort == "judge") echo "autofocus"; ?>>
        </div>
    </div>

    <?php 
    $org_array_lower = array();
    foreach ($org_array as $value) {
        $org_array_lower[] = strtolower($value);
    }
    $org_array = implode(",",$org_array_lower);

    if ($_SESSION['prefsProEdition'] == 1) $participant_orgs_label = $label_industry_affiliations;
    else $participant_orgs_label = $label_brewing_partners;

    ?>

    <div id="brewerJudgeFields">

        <?php if (($totalRows_judging > 0) || (($go == "admin") && ($filter != "default"))) { ?>
        <div class="form-group">
            <?php if (!empty($judge_location_avail)) { ?>
            <label for="brewerJudgeLocation" class="col-lg-3 col-md-3 col-sm-4 col-xs-12 control-label"><?php echo $label_judging_avail; ?></label>
            <div class="col-lg-9 col-md-9 col-sm-8 col-xs-12">
            <?php echo $judge_location_avail; ?>
            </div>
            <?php } // end if (!empty($judge_location_avail))?>
        </div>
        <?php } // end if (($totalRows_judging > 1) || (($go == "admin") && ($filter != "default"))) } ?>

        <div class="form-group">
            <label for="brewerJudgeMead" class="col-lg-3 col-md-3 col-sm-4 col-xs-12 control-label"><?php echo $label_bjcp_mead; ?></label>
            <div class="col-lg-9 col-md-9 col-sm-8 col-xs-12">
                <div class="input-group">
                    <label class="radio-inline">
                        <input type="radio" name="brewerJudgeMead" value="Y" id="brewerJudgeMead_0" <?php if (($action == "edit") && ($row_brewer['brewerJudgeMead'] == "Y")) echo "CHECKED"; ?>> <?php echo $label_yes; ?>
                    </label>
                    <label class="radio-inline">
                        <input type="radio" name="brewerJudgeMead" value="N" id="brewerJudgeMead_1" <?php if (($action == "edit") && (($row_brewer['brewerJudgeMead'] == "N") || ($row_brewer['brewerJudgeMead'] == ""))) echo "CHECKED"; ?>> <?php echo $label_no; ?>
                    </label>
                </div>
                <span class="help-block"><?php echo $brewer_text_007; ?></span>
            </div>
        </div>

        <div class="form-group">
            <label for="brewerJudgeMead" class="col-lg-3 col-md-3 col-sm-4 col-xs-12 control-label"><?php echo $label_bjcp_cider; ?></label>
            <div class="col-lg-9 col-md-9 col-sm-8 col-xs-12">
                <div class="input-group">
                    <label class="radio-inline">
                        <input type="radio" name="brewerJudgeCider" value="Y" id="brewerJudgeCider_0" <?php if (($action == "edit") && ($row_brewer['brewerJudgeCider'] == "Y")) echo "CHECKED"; ?>> <?php echo $label_yes; ?>
                    </label>
                    <label class="radio-inline">
                        <input type="radio" name="brewerJudgeCider" value="N" id="brewerJudgeCider_1" <?php if (($action == "edit") && (($row_brewer['brewerJudgeCider'] == "N") || ($row_brewer['brewerJudgeCider'] == ""))) echo "CHECKED"; ?>> <?php echo $label_no; ?>
                    </label>
                </div>
                <span class="help-block"><?php echo $brewer_text_035; ?></span>
            </div>
        </div>

        <?php $judge_array = explode(",",$row_brewer['brewerJudgeRank']); ?>
        <div class="form-group"><!-- Form Group Radio STACKED -->
            <label for="brewerJudgeRank" class="col-lg-3 col-md-3 col-sm-4 col-xs-12 control-label"><?php echo $label_bjcp_rank; ?></label>
            <div class="col-lg-9 col-md-9 col-sm-8 col-xs-12">
                <div class="input-group">
                    <div class="radio">
                        <label>
                            <input type="radio" name="brewerJudgeRank[]" value="Non-BJCP" <?php if (($action == "edit") && (in_array("Non-BJCP",$judge_array) || in_array("Novice",$judge_array))) echo "CHECKED"; else echo "CHECKED" ?>> Non-BJCP *
                        </label>
                    </div>
                    <div class="radio">
                        <label>
                            <input type="radio" name="brewerJudgeRank[]" value="Rank Pending" <?php if (($action == "edit")  && in_array("Rank Pending",$judge_array)) echo "CHECKED"; ?>> Rank Pending
                        </label>
                    </div>
                    <div class="radio">
                        <label>
                             <input type="radio" name="brewerJudgeRank[]" value="Provisional" <?php if (($action == "edit") && in_array("Provisional",$judge_array)) echo "CHECKED"; ?>> Provisional **
                        </label>
                    </div>
                    <div class="radio">
                        <label>
                            <input type="radio" name="brewerJudgeRank[]" value="Recognized" <?php if (($action == "edit") && in_array("Recognized",$judge_array)) echo "CHECKED"; ?>> Recognized
                        </label>
                    </div>
                    <div class="radio">
                        <label>
                            <input type="radio" name="brewerJudgeRank[]" value="Certified" <?php if (($action == "edit") && in_array("Certified",$judge_array)) echo "CHECKED"; ?>> Certified
                        </label>
                    </div>
                    <div class="radio">
                        <label>
                            <input type="radio" name="brewerJudgeRank[]" value="National" <?php if (($action == "edit") && in_array("National",$judge_array)) echo "CHECKED"; ?>> National
                        </label>
                    </div>
                    <div class="radio">
                        <label>
                             <input type="radio" name="brewerJudgeRank[]" value="Master" <?php if (($action == "edit") && in_array("Master",$judge_array)) echo "CHECKED"; ?>> Master
                        </label>
                    </div>
                    <div class="radio">
                        <label>
                            <input type="radio" name="brewerJudgeRank[]" value="Honorary Master" <?php if (($action == "edit") && in_array("Honorary Master",$judge_array)) echo "CHECKED"; ?>> Honorary Master
                        </label>
                    </div>
                    <div class="radio">
                        <label>
                            <input type="radio" name="brewerJudgeRank[]" value="Grand Master" <?php if (($action == "edit") && in_array("Grand Master",$judge_array)) echo "CHECKED"; ?>> Grand Master
                        </label>
                    </div>
                    <div class="radio">
                        <label>
                            <input type="radio" name="brewerJudgeRank[]" value="Honorary Grand Master" <?php if (($action == "edit") && in_array("Honorary Grand Master",$judge_array)) echo "CHECKED"; ?>>Honorary Grand Master
                        </label>
                    </div>
                </div>
                <span class="help-block">
                    <p><?php echo $brewer_text_008; ?></p>
                    <p><?php echo $brewer_text_009; ?></p>
                    </p>
                </span>
            </div>
        </div>

        <div class="form-group">
            <label for="brewerJudgeRank" class="col-lg-3 col-md-3 col-sm-4 col-xs-12 control-label"><?php echo $label_designations; ?></label>
            <div class="col-lg-9 col-md-9 col-sm-8 col-xs-12">
                <div class="input-group">
                    
                    <div class="checkbox">
                        <label>
                             <input type="checkbox" name="brewerJudgeRank[]" value="Judge with Sensory Training" <?php if (($action == "edit") && in_array("Judge with Sensory Training",$judge_array)) echo "CHECKED"; ?>> Judge with Sensory Training
                        </label>
                    </div>
                    <div class="checkbox">
                        <label>
                            <input type="checkbox" name="brewerJudgeRank[]" value="Professional Brewer" <?php if (($action == "edit") && in_array("Professional Brewer",$judge_array)) echo "CHECKED"; ?>> Professional Brewer
                        </label>
                    </div>
                    <div class="checkbox">
                        <label>
                            <input type="checkbox" name="brewerJudgeRank[]" value="Professional Mead Maker" <?php if (($action == "edit") && in_array("Professional Mead Maker",$judge_array)) echo "CHECKED"; ?>> Professional Mead Maker
                        </label>
                    </div>
                    <div class="checkbox">
                        <label>
                            <input type="checkbox" name="brewerJudgeRank[]" value="Professional Cider Maker" <?php if (($action == "edit") && in_array("Professional Cider Maker",$judge_array)) echo "CHECKED"; ?>> Professional Cider Maker
                        </label>
                    </div>
                    <div class="checkbox">
                        <label>
                            <input type="checkbox" name="brewerJudgeRank[]" value="Certified Cicerone" <?php if (($action == "edit") && in_array("Certified Cicerone",$judge_array)) echo "CHECKED"; ?>> Certified Cicerone
                        </label>
                    </div>
                    <div class="checkbox">
                        <label>
                            <input type="checkbox" name="brewerJudgeRank[]" value="Advanced Cicerone" <?php if (($action == "edit") && in_array("Advanced Cicerone",$judge_array)) echo "CHECKED"; ?>> Advanced Cicerone
                        </label>
                    </div>
                    <div class="checkbox">
                        <label>
                            <input type="checkbox" name="brewerJudgeRank[]" value="Master Cicerone" <?php if (($action == "edit") && in_array("Master Cicerone",$judge_array)) echo "CHECKED"; ?>> Master Cicerone
                        </label>
                    </div>

                 </div>
                <span class="help-block"><?php echo $brewer_text_010; ?></span>
            </div>
        </div>

         <div class="form-group">
            <label for="brewerJudgeExp" class="col-lg-3 col-md-3 col-sm-4 col-xs-12 control-label"><?php echo $label_judge_comps; ?></label>
            <div class="col-lg-9 col-md-9 col-sm-8 col-xs-12">
            
            <select class="selectpicker" name="brewerJudgeExp" id="brewerJudgeExp" data-width="auto" required>
                <option value="0"<?php if (($action == "edit") && ($row_brewer['brewerJudgeExp'] == "0")) echo " SELECTED"; ?>>0</option>
                <option value="1-5"<?php if (($action == "edit") && ($row_brewer['brewerJudgeExp'] == "1-5")) echo " SELECTED"; ?>>1-5</option>
                <option value="6-10"<?php if (($action == "edit") && ($row_brewer['brewerJudgeExp'] == "6-10")) echo " SELECTED"; ?>>6-10</option>
                <option value="10+"<?php if (($action == "edit") && ($row_brewer['brewerJudgeExp'] == "10+")) echo " SELECTED"; ?>>10+</option>
            </select>
            <span class="help-block"><?php echo $brewer_text_011; ?></span>
            </div>
        </div>

        <div class="form-group">
            <label for="brewerJudgeLikes" class="col-lg-3 col-md-3 col-sm-4 col-xs-12 control-label">&nbsp;</label>
            <div class="col-lg-9 col-md-9 col-sm-8 col-xs-12">
                <button class="btn btn-info btn-block" type="button" data-toggle="collapse" data-target="#collapsePref" aria-expanded="false" aria-controls="collapsePref"><?php echo $label_judge_preferred; ?></button>
                <span class="help-block"><?php echo $brewer_text_017; ?></span>
            </div>
        </div>

        <div class="collapse" id="collapsePref">
            <div class="form-group"><!-- Form Group Checkbox  -->
                <label for="brewerJudgeLikes" class="col-lg-3 col-md-3 col-sm-4 col-xs-12 control-label"><?php echo $label_judge_preferred; ?></label>
                <div class="col-lg-9 col-md-9 col-sm-8 col-xs-12">
                <p><strong class="text-danger"><?php echo $brewer_text_012; ?></strong></p>
                    <?php do {
                        $style_display = "";
                        if ($_SESSION['prefsStyleSet'] == "BA") {
                            if ($row_styles['brewStyleOwn'] == "bcoe") $style_display .= $row_styles['brewStyleCategory'].": ".$row_styles['brewStyle'];
                            elseif ($row_styles['brewStyleOwn'] == "custom") $style_display .= "Custom: ".$row_styles['brewStyle'];
                            else $style_display .= $row_styles['brewStyle'];
                        }
                        else $style_display .= ltrim($row_styles['brewStyleGroup'], "0").$row_styles['brewStyleNum'].": ".$row_styles['brewStyle'];
                        ?>
                        <div class="checkbox">
                            <label>
                                <input name="brewerJudgeLikes[]" type="checkbox" value="<?php echo $row_styles['id']; ?>" <?php if (isset($row_brewer['brewerJudgeLikes'])) { $a = explode(",", $row_brewer['brewerJudgeLikes']); $b = $row_styles['id']; foreach ($a as $value) { if ($value == $b) echo "CHECKED"; } } ?>> <?php echo $style_display; ?>
                            </label>
                        </div>
                    <?php } while ($row_styles = mysqli_fetch_assoc($styles)); ?>
                </div>
            </div>
        </div>

        <div class="form-group">
        <label for="brewerJudgeDislikes" class="col-lg-3 col-md-3 col-sm-4 col-xs-12 control-label">&nbsp;</label>
                <div class="col-lg-9 col-md-9 col-sm-8 col-xs-12">
                    <button class="btn btn-info btn-block" type="button" data-toggle="collapse" data-target="#collapseNonPref" aria-expanded="false" aria-controls="collapseNonPref"><?php echo $label_judge_non_preferred; ?></button>
                    <span class="help-block"><?php echo $brewer_text_013; ?></span>
                </div>
        </div>

        <div class="collapse" id="collapseNonPref">
            <div class="form-group"><!-- Form Group Checkbox  -->
                <label for="brewJudgeDislikes" class="col-lg-3 col-md-3 col-sm-4 col-xs-12 control-label"><?php echo $label_judge_non_preferred; ?></label>
                <div class="col-lg-9 col-md-9 col-sm-8 col-xs-12">
                    <p><strong class="text-danger"><?php echo $brewer_text_014; ?></strong></p>
                    <!-- <div class="row"> -->
                    <?php do {
                        $style_display = "";
                        if ($_SESSION['prefsStyleSet'] == "BA") {
                            if ($row_styles2['brewStyleOwn'] == "bcoe") $style_display .= $row_styles2['brewStyleCategory'].": ".$row_styles2['brewStyle'];
                            elseif ($row_styles2['brewStyleOwn'] == "custom") $style_display .= "Custom: ".$row_styles2['brewStyle'];
                            else $style_display .= $row_styles2['brewStyle'];
                        }
                        else $style_display .= ltrim($row_styles2['brewStyleGroup'], "0").$row_styles2['brewStyleNum'].": ".$row_styles2['brewStyle'];
                        ?>
                    
                        <div class="checkbox">
                            <label>
                                <input name="brewerJudgeDislikes[]" type="checkbox" value="<?php echo $row_styles2['id']; ?>" <?php if (isset($row_brewer['brewerJudgeDislikes'])) { $a = explode(",", $row_brewer['brewerJudgeDislikes']); $b = $row_styles2['id']; foreach ($a as $value) { if ($value == $b) echo "CHECKED"; } } ?>> <?php echo $style_display; ?>
                            </label>
                        </div>
                    <?php } while ($row_styles2 = mysqli_fetch_assoc($styles2)); ?>
                    <!-- </div> -->
                </div>
            </div>
        </div>

    </div><!-- ./ brewerJudgeFields -->
</section><!-- ./ judge-preferences -->
<?php } // end if (((!$judge_limit) && ($go == "account")) || (($_SESSION['userLevel'] <= 1) && (($go == "admin") || ($go == "account")))) ?>

<?php 
    if (((!$steward_limit) && ($go == "account")) || (($_SESSION['userLevel'] <= 1) && (($go == "admin") || ($go == "account")))) { ?>
<section id="steward-preferences">
    <div class="form-group">
        <label for="brewerSteward" class="col-lg-3 col-md-3 col-sm-4 col-xs-12 control-label"><?php echo $label_stewarding; ?></label>
        <div class="col-lg-9 col-md-9 col-sm-8 col-xs-12">
            <div class="input-group">
                
                <label class="radio-inline">
                    <input type="radio" name="brewerSteward" value="Y" id="brewerSteward_0" <?php if (($action == "add") && ($go == "judge")) echo "CHECKED"; if (($action == "edit") && ($row_brewer['brewerSteward'] == "Y")) echo "checked"; ?>> <?php echo $label_yes; ?>
                </label>
                <label class="radio-inline">
                    <input type="radio" name="brewerSteward" value="N" id="brewerSteward_1" <?php if (($action == "add") && ($go == "default")) echo "CHECKED"; if (($action == "edit") && ($row_brewer['brewerSteward'] == "N")) echo "checked"; ?>> <?php echo $label_no; ?>
                </label>
            </div>
            <span class="help-block"><?php echo $brewer_text_015; ?></span>
        </div>
    </div>

    <?php if (($totalRows_judging == 1) && (($go != "admin") && ($filter == "default"))) {?>
    <input name="brewerStewardLocation" type="hidden" value="<?php echo "Y-".$row_judging3['id']; ?>" />
    <?php } ?>

    <?php if (($totalRows_judging > 1) || (($go == "admin") && ($filter != "default"))) { ?>
    <div id="brewerStewardFields">
        <?php if (!empty($steward_location_avail)) { ?>
        <div class="form-group"><!-- Form Group NOT REQUIRED Select -->
            <label for="brewerStewardLocation" class="col-lg-3 col-md-3 col-sm-4 col-xs-12 control-label"><?php echo $label_stewarding_avail; ?></label>
            <div class="col-lg-9 col-md-9 col-sm-8 col-xs-12">
            <?php echo $steward_location_avail; ?>
            </div>
        </div>
        <?php } ?>
    </div>
    <?php } // end if (($totalRows_judging > 1) || (($go == "admin") && ($filter != "default"))) ?>

</section><!-- ./ steward-preferences -->
<?php } // end if (((!$steward_limit) && ($go == "account")) || (($_SESSION['userLevel'] <= 1) && (($go == "admin") || ($go == "account")))) ?>

<section id="participant-orgs">
    <div class="form-group">
        <label for="brewerAssignment" class="col-lg-3 col-md-3 col-sm-4 col-xs-12 control-label"><?php echo $participant_orgs_label; ?></label>
        <div class="col-lg-9 col-md-6 col-sm-8 col-xs-12">  
        <select class="selectpicker" multiple name="brewerAssignment[]" id="brewerAssignment" data-live-search="true" data-size="40" data-width="auto" data-show-tick="true" data-header="<?php echo $participant_orgs_label." - ".$label_select_below; ?>" title="<?php echo $participant_orgs_label." - ".$label_select_below; ?>">
            <?php echo $org_options; ?>
        </select>
        <span class="help-block"><?php if ($_SESSION['prefsProEdition'] == 1) echo $brewer_text_051; else echo $brewer_text_055; ?></span>
        </div>
    </div>
    <input name="allOrgs" type="hidden" value="<?php echo $org_array; ?>">
    <div id="brewerAssignmentOther" class="form-group">
        <label for="brewerAssignmentOther" class="col-lg-3 col-md-3 col-sm-4 col-xs-12 control-label"><?php echo $participant_orgs_label." &ndash; ".$label_other; ?></label>
        <div class="col-lg-9 col-md-9 col-sm-8 col-xs-12">
            <input class="form-control" name="brewerAssignmentOther" type="text" value="<?php if (($action == "edit") && (!empty($org_other))) echo str_replace(",",", ",$org_other); ?>" placeholder="" pattern="[^%\x22]+">
            <div class="help-block">
                <p><?php if ($_SESSION['prefsProEdition'] == 1) echo $brewer_text_052; else echo $brewer_text_054; ?></p>
            </div>
        </div>
    </div>
</section>

<section id="judge-steward-waiver">
    <div id="judge-waiver" class="form-group">
        <label for="brewerJudgeWaiver" class="col-lg-3 col-md-3 col-sm-4 col-xs-12 control-label"><?php echo $label_waiver; ?></label>
        <div class="col-lg-9 col-md-9 col-sm-8 col-xs-12">
            <div class="checkbox">
                <p><?php echo $brewer_text_016; ?></p>
                <label>
                    <input type="checkbox" name="brewerJudgeWaiver" value="Y" id="brewerJudgeWaiver_0" checked required /><?php echo $brewer_text_018; ?>
                </label>
            </div>
            <div class="help-block with-errors"></div>
        </div>
    </div>
</section>
<section id="judge-steward-notes">
    <div class="form-group">
        <label for="brewerJudgeNotes" class="col-lg-3 col-md-3 col-sm-4 col-xs-12 control-label"><?php echo $label_org_notes; ?></label>
        <div class="col-lg-9 col-md-9 col-sm-8 col-xs-12">
            
            <input class="form-control" name="brewerJudgeNotes" type="text" value="<?php if ($action == "edit") echo $row_brewer['brewerJudgeNotes']; ?>" placeholder="">
            <span class="help-block"><?php echo $brewer_text_004; ?></span>
        </div>
    </div>
</section>

<?php } // end if ((!$table_assignment) || ($go == "admin")) ?>