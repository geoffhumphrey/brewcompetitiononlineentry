<?php

if (((!$table_assignment) || ($go == "admin")) && (!$entrant_type_brewery)) { 

    if (((!$judge_limit) && ($go == "account")) || (($_SESSION['userLevel'] <= 1) && (($go == "admin") || ($go == "account")))) { 
        $judge_checked = FALSE;
        if ((($action == "add") || ($action == "register")) && ($go == "judge")) $judge_checked = TRUE;
        if (($action == "edit") && ($row_brewer['brewerJudge'] == "Y")) $judge_checked = TRUE;

?>
<a name="judge-info"></a>
<section id="judge-preferences">
    <div class="mb-3 row">
        <label for="brewerJudge" class="col-xs-12 col-sm-3 col-lg-2 col-form-label"><strong><?php echo $label_judging; ?></strong></label>
        <div class="col-xs-12 col-sm-9 col-lg-10">
            <div class="form-check form-check-inline">
                <input class="form-check-input" type="radio" name="brewerJudge" value="Y" id="brewerJudge_0" <?php if ($judge_checked) echo "CHECKED"; ?>> 
                <label class="form-check-label"><?php echo $label_yes; ?></label>
            </div>
            <div class="form-check form-check-inline">
                <input class="form-check-input" type="radio" name="brewerJudge" value="N" id="brewerJudge_1" <?php if (!$judge_checked) echo "CHECKED"; ?>> 
                <label class="form-check-label">
                    <?php echo $label_no; ?>
                </label>
            </div>
            <div class="help-block mt-1"><?php echo $brewer_text_006; ?></div>
        </div>
    </div>

    <div id="bjcp-id" class="mb-3 row">
        <label for="brewerJudgeID" class="col-xs-12 col-sm-3 col-lg-2 col-form-label"><strong><?php echo $label_bjcp_id; ?></strong></label>
        <div class="col-xs-12 col-sm-9 col-lg-10">
            <input class="form-control" id="brewerJudgeID" name="brewerJudgeID" type="text" value="<?php if ($action == "edit") echo $row_brewer['brewerJudgeID']; ?>" placeholder="" <?php if ($psort == "judge") echo "autofocus"; ?>>
        </div>
    </div>

    <div id="brewerJudgeFields">

        <?php if (($judging_location_count == 1) && (($go != "admin") && ($filter == "default"))) echo $judge_single_option; ?>

        <?php if (($judging_location_count > 1) || (($go == "admin") && ($filter != "default"))) { ?>
        <div class="mb-3 row">
            <?php if (!empty($judge_location_avail)) { ?>
            <label for="brewerJudgeLocation" class="col-xs-12 col-sm-3 col-lg-2 col-form-label"><strong><?php echo $label_judging_avail; ?></strong></label>
            <div class="col-xs-12 col-sm-9 col-lg-10">
            <?php echo $judge_location_avail; ?>
            </div>
            <?php } ?>
        </div>
        <?php } ?>

        <div class="mb-3 row">
            <label for="brewerJudgeMead" class="col-xs-12 col-sm-3 col-lg-2 col-form-label"><strong><?php echo $label_bjcp_mead; ?></strong></label>
            <div class="col-xs-12 col-sm-9 col-lg-10">
                <div class="form-check form-check-inline">
                    <input class="form-check-input" type="radio" name="brewerJudgeMead" value="Y" id="brewerJudgeMead_0" <?php if (($action == "edit") && ($row_brewer['brewerJudgeMead'] == "Y")) echo "CHECKED"; ?>> 
                    <label class="form-check-label"><?php echo $label_yes; ?></label>
                </div>
                <div class="form-check form-check-inline">
                    <input class="form-check-input" type="radio" name="brewerJudgeMead" value="N" id="brewerJudgeMead_1" <?php if (($action == "edit") && (($row_brewer['brewerJudgeMead'] == "N") || ($row_brewer['brewerJudgeMead'] == ""))) echo "CHECKED"; ?>> 
                    <label class="form-check-label">
                        <?php echo $label_no; ?>
                    </label>
                </div>
                <div class="help-block mt-1"><?php echo $brewer_text_007; ?></div> 
            </div>
        </div>

        <div class="mb-3 row">
            <label for="brewerJudgeCider" class="col-xs-12 col-sm-3 col-lg-2 col-form-label"><strong><?php echo $label_bjcp_cider; ?></strong></label>
            <div class="col-xs-12 col-sm-9 col-lg-10">
                <div class="form-check form-check-inline">
                    <input class="form-check-input" type="radio" name="brewerJudgeCider" value="Y" id="brewerJudgeCider_0" <?php if (($action == "edit") && ($row_brewer['brewerJudgeCider'] == "Y")) echo "CHECKED"; ?>> 
                    <label class="form-check-label"><?php echo $label_yes; ?></label>
                </div>
                <div class="form-check form-check-inline">
                    <input class="form-check-input" type="radio" name="brewerJudgeCider" value="N" id="brewerJudgeCider_1" <?php if (($action == "edit") && (($row_brewer['brewerJudgeCider'] == "N") || ($row_brewer['brewerJudgeCider'] == ""))) echo "CHECKED"; ?>> 
                    <label class="form-check-label">
                        <?php echo $label_no; ?>
                    </label>
                </div>
                <div class="help-block mt-1"><?php echo $brewer_text_035; ?></div>
            </div>
        </div>

        <?php $judge_array = explode(",",$row_brewer['brewerJudgeRank']); ?>
        <div class="mb-3 row">
            <label for="brewerJudgeRank" class="col-xs-12 col-sm-3 col-lg-2 col-form-label"><strong><?php echo $label_bjcp_rank; ?></strong></label>
            <div class="col-xs-12 col-sm-9 col-lg-10">
                <div class="form-check form-check-inline">
                    <input class="form-check-input" type="radio" name="brewerJudgeRank[]" value="Non-BJCP" <?php if (($action == "edit") && (in_array("Non-BJCP",$judge_array) || in_array("Novice",$judge_array))) echo "CHECKED"; else echo "CHECKED" ?>>
                    <label class="form-check-label">Non-BJCP *</label>
                </div>
                <div class="form-check form-check-inline">
                    <input class="form-check-input" type="radio" name="brewerJudgeRank[]" value="Rank Pending" <?php if (($action == "edit")  && in_array("Rank Pending",$judge_array)) echo "CHECKED"; ?>>
                    <label class="form-check-label">Rank Pending</label>
                </div>
                <div class="form-check form-check-inline">
                    <input class="form-check-input" type="radio" name="brewerJudgeRank[]" value="Provisional" <?php if (($action == "edit") && in_array("Provisional",$judge_array)) echo "CHECKED"; ?>>
                    <label class="form-check-label">Provisional **</label>
                </div>
                <div class="form-check form-check-inline">
                    <input class="form-check-input" type="radio" name="brewerJudgeRank[]" value="Recognized" <?php if (($action == "edit") && in_array("Recognized",$judge_array)) echo "CHECKED"; ?>>
                    <label class="form-check-label">Recognized</label>
                </div>
                <div class="form-check form-check-inline">
                    <input class="form-check-input" type="radio" name="brewerJudgeRank[]" value="Certified" <?php if (($action == "edit") && in_array("Certified",$judge_array)) echo "CHECKED"; ?>>
                    <label class="form-check-label">Certified</label>
                </div>
                <div class="form-check form-check-inline">
                    <input class="form-check-input" type="radio" name="brewerJudgeRank[]" value="National" <?php if (($action == "edit") && in_array("National",$judge_array)) echo "CHECKED"; ?>>
                    <label class="form-check-label">National</label>
                </div>
                <div class="form-check form-check-inline">
                    <input class="form-check-input" type="radio" name="brewerJudgeRank[]" value="Master" <?php if (($action == "edit") && in_array("Master",$judge_array)) echo "CHECKED"; ?>>
                    <label class="form-check-label">Master</label>
                </div>
                <div class="form-check form-check-inline">
                    <input class="form-check-input" type="radio" name="brewerJudgeRank[]" value="Honorary Master" <?php if (($action == "edit") && in_array("Honorary Master",$judge_array)) echo "CHECKED"; ?>>
                    <label class="form-check-label">Honorary Master</label>
                </div>
                <div class="form-check form-check-inline">
                    <input class="form-check-input" type="radio" name="brewerJudgeRank[]" value="Grand Master" <?php if (($action == "edit") && in_array("Grand Master",$judge_array)) echo "CHECKED"; ?>>
                    <label class="form-check-label">Grand Master</label>
                </div>
                <div class="form-check form-check-inline">
                    <input class="form-check-input" type="radio" name="brewerJudgeRank[]" value="Honorary Grand Master" <?php if (($action == "edit") && in_array("Honorary Grand Master",$judge_array)) echo "CHECKED"; ?>>
                    <label class="form-check-label">Honorary Grand Master</label>
                </div>
                <div class="help-block mt-1">
                    <p class="mt-1">
                        <?php echo $brewer_text_008; ?><br>
                        <?php echo $brewer_text_009; ?>
                    </p>
                </div>
            </div>
        </div>

        <div class="mb-3 row">
            <label for="brewerJudgeRank" class="col-xs-12 col-sm-3 col-lg-2 col-form-label"><strong><?php echo $label_designations; ?></strong></label>
            <div class="col-xs-12 col-sm-9 col-lg-10">
                <div class="form-check form-check-inline">
                    <input class="form-check-input" type="checkbox" name="brewerJudgeRank[]" value="Judge with Sensory Training" <?php if (($action == "edit") && in_array("Judge with Sensory Training",$judge_array)) echo "CHECKED"; ?>>
                    <label class="form-check-label">Judge with Sensory Training</label>
                </div>
                <div class="form-check form-check-inline">
                    <input class="form-check-input" type="checkbox" name="brewerJudgeRank[]" value="Professional Brewer" <?php if (($action == "edit") && in_array("Professional Brewer",$judge_array)) echo "CHECKED"; ?>>
                    <label class="form-check-label">Professional Brewer</label>
                </div>
                <div class="form-check form-check-inline">
                    <input class="form-check-input" type="checkbox" name="brewerJudgeRank[]" value="Professional Mead Maker" <?php if (($action == "edit") && in_array("Professional Mead Maker",$judge_array)) echo "CHECKED"; ?>>
                    <label class="form-check-label">Professional Mead Maker</label>
                </div>
                <div class="form-check form-check-inline">
                    <input class="form-check-input" type="checkbox" name="brewerJudgeRank[]" value="Professional Cider Maker" <?php if (($action == "edit") && in_array("Professional Cider Maker",$judge_array)) echo "CHECKED"; ?>>
                    <label class="form-check-label">Professional Cider Maker</label>
                </div>
                <div class="form-check form-check-inline">
                    <input class="form-check-input" type="checkbox" name="brewerJudgeRank[]" value="Certified Cicerone" <?php if (($action == "edit") && in_array("Certified Cicerone",$judge_array)) echo "CHECKED"; ?>>
                    <label class="form-check-label">Certified Cicerone</label>
                </div>
                <div class="form-check form-check-inline">
                    <input class="form-check-input" type="checkbox" name="brewerJudgeRank[]" value="Advanced Cicerone" <?php if (($action == "edit") && in_array("Advanced Cicerone",$judge_array)) echo "CHECKED"; ?>>
                    <label class="form-check-label">Advanced Cicerone</label>
                </div>
                <div class="form-check form-check-inline">
                    <input class="form-check-input" type="checkbox" name="brewerJudgeRank[]" value="Master Cicerone" <?php if (($action == "edit") && in_array("Master Cicerone",$judge_array)) echo "CHECKED"; ?>>
                    <label class="form-check-label">Master Cicerone</label>
                </div>
                <div class="help-block mt-1"><?php echo $brewer_text_010; ?></div>
            </div>
        </div>

        <div class="mb-3 row">
            <label for="brewerJudgeExp" class="col-xs-12 col-sm-3 col-lg-2 col-form-label"><strong><?php echo $label_judge_comps; ?></strong></label>
            <div class="col-xs-12 col-sm-9 col-lg-10">
            <select class="form-select bootstrap-select mb-1" name="brewerJudgeExp" id="brewerJudgeExp" required>
                <option value="0"<?php if (($action == "edit") && ($row_brewer['brewerJudgeExp'] == "0")) echo " SELECTED"; ?>>0</option>
                <option value="1-5"<?php if (($action == "edit") && ($row_brewer['brewerJudgeExp'] == "1-5")) echo " SELECTED"; ?>>1-5</option>
                <option value="6-10"<?php if (($action == "edit") && ($row_brewer['brewerJudgeExp'] == "6-10")) echo " SELECTED"; ?>>6-10</option>
                <option value="10+"<?php if (($action == "edit") && ($row_brewer['brewerJudgeExp'] == "10+")) echo " SELECTED"; ?>>10+</option>
            </select>
            <div class="help-block mt-1"><?php echo $brewer_text_011; ?></div>
            </div>
        </div>

        <div class="mb-3 row">
            <label for="brewerJudgeLikes" class="col-xs-12 col-sm-3 col-lg-2 col-form-label"><strong><?php echo $label_judge_preferred; ?></strong></label>
            <div class="col-xs-12 col-sm-9 col-md-6 d-grid">
                <button class="btn btn-secondary" type="button" data-bs-toggle="collapse" data-bs-target="#collapsePref" aria-expanded="false" aria-controls="collapsePref"><?php echo $label_judge_preferred; ?></button>
                <div class="help-block mt-1"><?php echo $brewer_text_017; ?></div>
            </div>
        </div>

        <div class="collapse" id="collapsePref">
            <div class="mb-3 row">
                <label for="brewerJudgeLikes" class="col-xs-12 col-sm-3 col-lg-2 col-form-label"></label>
                <div class="col-xs-12 col-sm-9 col-lg-10">
                <p class="mb-1 small text-danger"><strong><?php echo $brewer_text_012; ?></strong></p>
                    <?php do {
                        $style_display = "";
                        if ($_SESSION['prefsStyleSet'] == "BA") {
                            if ($row_styles['brewStyleOwn'] == "bcoe") $style_display .= $row_styles['brewStyleCategory'].": ".$row_styles['brewStyle'];
                            elseif ($row_styles['brewStyleOwn'] == "custom") $style_display .= "Custom: ".$row_styles['brewStyle'];
                            else $style_display .= $row_styles['brewStyle'];
                        }
                        else $style_display .= ltrim($row_styles['brewStyleGroup'], "0").$row_styles['brewStyleNum'].": ".$row_styles['brewStyle'];
                        ?>
                        <div class="form-check small">
                            <input class="form-check-input" name="brewerJudgeLikes[]" type="checkbox" value="<?php echo $row_styles['id']; ?>" <?php if (isset($row_brewer['brewerJudgeLikes'])) { $a = explode(",", $row_brewer['brewerJudgeLikes']); $b = $row_styles['id']; foreach ($a as $value) { if ($value == $b) echo "CHECKED"; } } ?>>
                            <label class="form-check-label"><?php echo $style_display; ?></label>
                        </div>
                    <?php } while ($row_styles = mysqli_fetch_assoc($styles)); ?>
                </div>
            </div>
        </div>

        <div class="mb-3 row">
        <label for="brewerJudgeDislikes" class="col-xs-12 col-sm-3 col-lg-2 col-form-label"><strong><?php echo $label_judge_non_preferred; ?></strong></label>
            <div class="col-xs-12 col-sm-9 col-md-6 d-grid">
                <button class="btn btn-secondary" type="button" data-bs-toggle="collapse" data-bs-target="#collapseNonPref" aria-expanded="false" aria-controls="collapseNonPref"><?php echo $label_judge_non_preferred; ?></button>
                <span class="help-block mt-1"><?php echo $brewer_text_013; ?></span>
            </div>
        </div>

        <div class="collapse" id="collapseNonPref">
            <div class="mb-3 row">
                <label for="brewJudgeDislikes" class="col-xs-12 col-sm-3 col-lg-2 col-form-label"></label>
                <div class="col-xs-12 col-sm-9 col-lg-10">
                    <p class="mb-1 small text-danger"><strong><?php echo $brewer_text_014; ?></strong></p>
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
                        <div class="form-check small">
                            <input class="form-check-input" name="brewerJudgeDislikes[]" type="checkbox" value="<?php echo $row_styles2['id']; ?>" <?php if (isset($row_brewer['brewerJudgeDislikes'])) { $a = explode(",", $row_brewer['brewerJudgeDislikes']); $b = $row_styles2['id']; foreach ($a as $value) { if ($value == $b) echo "CHECKED"; } } ?>>
                            <label class="form-check-label"><?php echo $style_display; ?></label>
                        </div>
                    <?php } while ($row_styles2 = mysqli_fetch_assoc($styles2)); ?>
                    <!-- </div> -->
                </div>
            </div>
        </div>

    </div><!-- ./ brewerJudgeFields -->
</section><!-- ./ judge-preferences -->
<?php } // end if (((!$judge_limit) && ($go == "account")) || (($_SESSION['userLevel'] <= 1) && (($go == "admin") || ($go == "account")))) ?>

<?php if (((!$steward_limit) && ($go == "account")) || (($_SESSION['userLevel'] <= 1) && (($go == "admin") || ($go == "account")))) { ?>
<a name="steward-info"></a>
<section id="steward-preferences">
    <div class="mb-3 row">
        <label for="brewerSteward" class="col-xs-12 col-sm-3 col-lg-2 col-form-label"><strong><?php echo $label_stewarding; ?></strong></label>
        <div class="col-xs-12 col-sm-9 col-lg-10">
            <div class="form-check form-check-inline">
                <input class="form-check-input" type="radio" name="brewerSteward" value="Y" id="brewerSteward_0" <?php if (($action == "add") && ($go == "judge")) echo "CHECKED"; if (($action == "edit") && ($row_brewer['brewerSteward'] == "Y")) echo "CHECKED"; ?>> 
                <label class="form-check-label"><?php echo $label_yes; ?></label>
            </div>
            <div class="form-check form-check-inline">
                <input class="form-check-input" type="radio" name="brewerSteward" value="N" id="brewerSteward_1" <?php if (($action == "add") && ($go == "default")) echo "CHECKED"; if (($action == "edit") && (($row_brewer['brewerSteward'] == "N") || ($row_brewer['brewerSteward'] == ""))) echo "CHECKED"; ?>> 
                <label class="form-check-label"><?php echo $label_no; ?></label>
            </div>
            <div class="help-block mt-1"><?php echo $brewer_text_015; ?></div>
        </div>
    </div>

    <?php if (($judging_location_count == 1) && (($go != "admin") && ($filter == "default"))) echo $steward_single_option; ?>
   
    <?php if (($judging_location_count > 1) || (($go == "admin") && ($filter != "default"))) { ?>
    <div id="brewerStewardFields">
        <?php if (!empty($steward_location_avail)) { ?>
        <div class="mb-3 row">
            <label for="brewerStewardLocation" class="col-xs-12 col-sm-3 col-lg-2 col-form-label"><strong><?php echo $label_stewarding_avail; ?></strong></label>
            <div class="col-xs-12 col-sm-9 col-lg-10">
            <?php echo $steward_location_avail; ?>
            </div>
        </div>
        <?php } ?>
    </div>
    <?php } // end if (($totalRows_judging > 1) || (($go == "admin") && ($filter != "default"))) ?>

</section><!-- ./ steward-preferences -->
<?php } // end if (((!$steward_limit) && ($go == "account")) || (($_SESSION['userLevel'] <= 1) && (($go == "admin") || ($go == "account")))) ?>

<section id="judge-steward-waiver">
    <div id="judge-waiver" class="mb-3 row">
        <label for="brewerJudgeWaiver" class="col-xs-12 col-sm-3 col-lg-2 col-form-label"><strong><?php echo $label_waiver; ?></strong></label>
        <div class="col-xs-12 col-sm-9 col-lg-10">
            <p><?php echo $brewer_text_016; ?></p>
            <div class="form-check">
                <input class="form-check-input" type="checkbox" name="brewerJudgeWaiver" value="Y" id="brewerJudgeWaiver_0" checked required />
                <label class="form-check-label"><?php echo $brewer_text_018; ?></label>
            </div>
            <div class="help-block invalid-feedback text-danger"></div>
        </div>
    </div>
</section>
<section id="judge-steward-notes">
    <div class="mb-3 row">
        <label for="brewerJudgeNotes" class="col-xs-12 col-sm-3 col-lg-2 col-form-label"><strong><?php echo $label_org_notes; ?></strong></label>
        <div class="col-xs-12 col-sm-9 col-lg-10">
            <input class="form-control" name="brewerJudgeNotes" type="text" value="<?php if ($action == "edit") echo $row_brewer['brewerJudgeNotes']; ?>" placeholder="">
            <div class="help-block mt-1"><?php echo $brewer_text_004; ?></div>
        </div>
    </div>
</section>

<?php } // end if ((!$table_assignment) || ($go == "admin")) ?>