<?php

// A participant who is already a judge/steward must still be able to reach this block to opt
// back out, even once the competition's judge/steward cap has been reached - otherwise, once
// the cap is hit, no existing judge/steward could ever remove themselves again.
$already_judge = (($action == "edit") && ($row_brewer['brewerJudge'] == "Y"));
$already_steward = (($action == "edit") && ($row_brewer['brewerSteward'] == "Y"));

/**
 * GitHub issue #1752: $table_assignment (the person already has a real table
 * assignment somewhere, so changing their Judge/Steward Y/N status or session
 * availability could orphan/conflict with it) used to gate this ENTIRE file - not
 * just those two fields, but every unrelated preference too (Likes/Dislikes, BJCP
 * Rank, Mead/Cider certification, BJCP ID, Experience, Notes). None of those have
 * anything to do with table assignments, and were being needlessly locked (and,
 * before a since-fixed bug, silently wiped on any edit - see the hidden-field
 * preservation below) for any already-assigned judge/steward. Confirmed live against
 * a real assigned judge (Daniel Perrigan, motownmash_) whose Likes/Dislikes had
 * gone NULL this way. $show_assignment_sensitive_fields_pb now scopes the
 * table-assignment gate to just Judge/Steward Y/N + session availability per role;
 * everything else renders normally regardless of table assignment, gated only by
 * $entrant_type_brewery (a separate, legitimate reason to hide all of it - a
 * brewery-type entrant doesn't judge/steward at all).
 */
$show_assignment_sensitive_fields_pb = (!$table_assignment) || ($go == "admin");

$show_judge_section_pb = ((((!$judge_limit) || $already_judge) && ($go == "account")) || (($_SESSION['userLevel'] <= 1) && (($go == "admin") || ($go == "account")))) && (!$entrant_type_brewery);
$show_steward_section_pb = ((((!$steward_limit) || $already_steward) && ($go == "account")) || (($_SESSION['userLevel'] <= 1) && (($go == "admin") || ($go == "account")))) && (!$entrant_type_brewery);

if ($show_judge_section_pb) {

    $styles_selected = array();
    $styles_selected = json_decode($_SESSION['prefsSelectedStyles'],true);

    if (!empty($styles_selected)) {

        /**
         * The one-time migration that originally populated prefsSelectedStyles
         * (v2.6.2.0, update/run_update.php) never wrote an 'id' key into each entry's
         * value - only later code paths (adding a custom style; the "Accepted
         * Styles" bulk resubmit in admin/styles.admin.php) started doing that. Every
         * entry's own array key has always correctly been the style id in every
         * format, though - backfill it into the value here (before array_multisort()
         * below re-indexes the array itself, which would otherwise make the array
         * key useless as a fallback) so the isset($value['id']) check a few lines
         * down doesn't silently skip every style from that original migration
         * format, which is exactly what made every Likes/Non-Preferred style
         * checkbox vanish for any install that had never since done a full
         * "Accepted Styles" resubmit.
         */
        foreach ($styles_selected as $style_key_pb => &$style_value_pb) {
            if ((!isset($style_value_pb['id'])) || ($style_value_pb['id'] === "")) $style_value_pb['id'] = $style_key_pb;
        }
        unset($style_value_pb);

        if ($_SESSION['style_set_no_numbering']) array_multisort(array_column($styles_selected, 'brewStyle'), SORT_ASC, array_column($styles_selected, 'brewStyleNum'), SORT_ASC, $styles_selected);
        else array_multisort(array_column($styles_selected, 'brewStyleGroup'), SORT_ASC, array_column($styles_selected, 'brewStyleNum'), SORT_ASC, $styles_selected);

        $j_likes_form_elements = "";
        $j_dislikes_form_elements = "";

        $a = array();
        $b = array();

        if (isset($row_brewer['brewerJudgeLikes'])) {
            $a = explode(",", $row_brewer['brewerJudgeLikes']);
        }

        if (isset($row_brewer['brewerJudgeDislikes'])) {
            $b = explode(",", $row_brewer['brewerJudgeDislikes']);
        }

        foreach($styles_selected as $key => $value) {

            if ((isset($value['id'])) && (!empty($value['id']))) {

                $style_display = "";
                $style_selected_likes = "";
                if (in_array($value['id'], $a)) $style_selected_likes = "CHECKED";

                $style_selected_dislikes = "";
                if (in_array($value['id'], $b)) $style_selected_dislikes = "CHECKED";

                if ($_SESSION['style_set_no_numbering']) {
                    $style_display .= $value['brewStyle'];
                }

                else $style_display .= ltrim($value['brewStyleGroup'], "0").$value['brewStyleNum'].": ".$value['brewStyle'];

                $j_likes_form_elements .= "<div class=\"checkbox\">\n";
                $j_likes_form_elements .= "<label>\n";
                $j_likes_form_elements .= sprintf("<input name=\"brewerJudgeLikes[]\" type=\"checkbox\" value=\"%s\" %s>\n", $value['id'], $style_selected_likes);
                $j_likes_form_elements .= $style_display;
                $j_likes_form_elements .= "\n</label>\n";
                $j_likes_form_elements .= "</div>\n";

                $j_dislikes_form_elements .= "<div class=\"checkbox\">\n";
                $j_dislikes_form_elements .= "<label>\n";
                $j_dislikes_form_elements .= sprintf("<input name=\"brewerJudgeDislikes[]\" type=\"checkbox\" value=\"%s\" %s>\n", $value['id'], $style_selected_dislikes);
                $j_dislikes_form_elements .= $style_display;
                $j_dislikes_form_elements .= "\n</label>\n";
                $j_dislikes_form_elements .= "</div>\n";

            }

        }

    }


    $judge_checked = FALSE;
    if ((($action == "add") || ($action == "register")) && ($go == "judge")) $judge_checked = TRUE;
    if (($action == "edit") && ($row_brewer['brewerJudge'] == "Y")) $judge_checked = TRUE;

?>
<a name="judge-info"></a>
<section id="judge-preferences">
    <?php if ($show_assignment_sensitive_fields_pb) { ?>
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
    <?php } else { ?>
    <input type="hidden" name="brewerJudge" value="<?php echo h($row_brewer['brewerJudge']); ?>">
    <?php } ?>

    <div id="bjcp-id" class="mb-3 row">
        <label for="brewerJudgeID" class="col-xs-12 col-sm-3 col-lg-2 col-form-label"><strong><?php echo $label_bjcp_id; ?></strong></label>
        <div class="col-xs-12 col-sm-9 col-lg-10">
            <input class="form-control" id="brewerJudgeID" name="brewerJudgeID" type="text" value="<?php if ($action == "edit") echo $row_brewer['brewerJudgeID']; ?>" placeholder="" pattern="(TEMP|temp)\d{4}|(?=.*[A-Za-z]).{5}" title="<?php echo $brewer_text_057; ?>" <?php if ($psort == "judge") echo "autofocus"; ?>>
            <div class="invalid-feedback"><?php echo $brewer_text_057; ?></div>
        </div>
    </div>

    <div id="brewerJudgeFields">

        <?php if ($show_assignment_sensitive_fields_pb) { ?>

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

        <?php } else {
        // GitHub issue #1752: brewerJudgeLocation is posted by the interactive form as
        // one array entry per location ("Y-9", "N-15", ...) via repeated
        // brewerJudgeLocation[] selects - preserve that same shape here, not the raw
        // comma-joined DB string, or process_brewer_info.inc.php's per-location loop
        // would explode() one giant malformed "value" instead of each location's own
        // token.
        $preserve_judge_locations_pb = array_filter(explode(",", (string) ($row_brewer['brewerJudgeLocation'] ?? "")), function($v) { return $v !== ""; });
        foreach ($preserve_judge_locations_pb as $preserve_judge_location_pb) { ?>
        <input type="hidden" name="brewerJudgeLocation[]" value="<?php echo h($preserve_judge_location_pb); ?>">
        <?php } } ?>

        <div class="mb-3 row">
            <label for="brewerJudgeMead" class="col-xs-12 col-sm-3 col-lg-2 col-form-label"><strong>BJCP <?php echo $label_bjcp_mead; ?></strong></label>
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
            <label for="brewerJudgeCider" class="col-xs-12 col-sm-3 col-lg-2 col-form-label"><strong>BJCP <?php echo $label_bjcp_cider; ?></strong></label>
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
                    <input class="form-check-input" type="radio" name="brewerJudgeRank[]" value="Mead/Cider Only" <?php if (($action == "edit") && (in_array("Mead/Cider Only",$judge_array))) echo "CHECKED"; else echo "CHECKED" ?>>
                    <label class="form-check-label">BJCP Certified Mead and/or Cider Only</label>
                </div>
                <div class="form-check form-check-inline">
                    <input class="form-check-input" type="radio" name="brewerJudgeRank[]" value="Rank Pending" <?php if (($action == "edit")  && in_array("Rank Pending",$judge_array)) echo "CHECKED"; ?>>
                    <label class="form-check-label">Rank Pending</label>
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
                    <input class="form-check-input" type="radio" name="brewerJudgeRank[]" value="Distinguished Certified" <?php if (($action == "edit") && in_array("Distinguished Certified",$judge_array)) echo "CHECKED"; ?>>
                    <label class="form-check-label">Distinguished Certified</label>
                </div>
                <div class="form-check form-check-inline">
                    <input class="form-check-input" type="radio" name="brewerJudgeRank[]" value="National" <?php if (($action == "edit") && in_array("National",$judge_array)) echo "CHECKED"; ?>>
                    <label class="form-check-label">National</label>
                </div>
                <div class="form-check form-check-inline">
                    <input class="form-check-input" type="radio" name="brewerJudgeRank[]" value="Distinguished National" <?php if (($action == "edit") && in_array("Distinguished National",$judge_array)) echo "CHECKED"; ?>>
                    <label class="form-check-label">Distinguished National</label>
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
                        <?php echo $brewer_text_008; ?>
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
                    <input class="form-check-input" type="checkbox" name="brewerJudgeRank[]" value="Certified Cider Guide" <?php if (($action == "edit") && in_array("Certified Cider Guide",$judge_array)) echo "CHECKED"; ?>>
                    <label class="form-check-label">Certified Cider Guide</label>
                </div>
                <div class="form-check form-check-inline">
                    <input class="form-check-input" type="checkbox" name="brewerJudgeRank[]" value="Certified Pommelier" <?php if (($action == "edit") && in_array("Certified Pommelier",$judge_array)) echo "CHECKED"; ?>>
                    <label class="form-check-label">Certified Pommelier</label>
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

        <?php if (!empty($styles_selected)) { ?>

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
                    <?php echo $j_likes_form_elements; ?>
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
                    <?php echo $j_dislikes_form_elements; ?>
                </div>
            </div>
        </div>

        <?php } ?>

    </div><!-- ./ brewerJudgeFields -->
</section><!-- ./ judge-preferences -->
<?php }
/**
 * GitHub issue #1752: the whole judge section above is unreachable here (judge cap
 * reached and this person was never already a judge, or an unrelated
 * entrant_type_brewery edit) - preserve every field it owns via hidden fields so an
 * edit that can't reach this section is a no-op rather than resetting them, matching
 * the fix shape used throughout this file family. Harmless even for the judge-cap
 * case (someone who was never a judge has nothing real to preserve), and necessary
 * for the entrant_type_brewery case (switching entrant type on an existing account
 * that previously had real judge data).
 */
else {
    $preserve_judge_locations_pb = array_filter(explode(",", (string) ($row_brewer['brewerJudgeLocation'] ?? "")), function($v) { return $v !== ""; });
    $preserve_judge_likes_pb = array_filter(explode(",", (string) ($row_brewer['brewerJudgeLikes'] ?? "")), function($v) { return $v !== ""; });
    $preserve_judge_dislikes_pb = array_filter(explode(",", (string) ($row_brewer['brewerJudgeDislikes'] ?? "")), function($v) { return $v !== ""; });
    $preserve_judge_rank_pb = array_filter(explode(",", (string) ($row_brewer['brewerJudgeRank'] ?? "")), function($v) { return $v !== ""; });
    ?>
<input type="hidden" name="brewerJudge" value="<?php echo h($row_brewer['brewerJudge']); ?>">
<input type="hidden" name="brewerJudgeMead" value="<?php echo h($row_brewer['brewerJudgeMead']); ?>">
<input type="hidden" name="brewerJudgeCider" value="<?php echo h($row_brewer['brewerJudgeCider']); ?>">
<input type="hidden" name="brewerJudgeID" value="<?php echo h($row_brewer['brewerJudgeID']); ?>">
<input type="hidden" name="brewerJudgeExp" value="<?php echo h($row_brewer['brewerJudgeExp']); ?>">
<?php foreach ($preserve_judge_locations_pb as $preserve_judge_location_pb) { ?>
<input type="hidden" name="brewerJudgeLocation[]" value="<?php echo h($preserve_judge_location_pb); ?>">
<?php } ?>
<?php foreach ($preserve_judge_likes_pb as $preserve_judge_like_pb) { ?>
<input type="hidden" name="brewerJudgeLikes[]" value="<?php echo h($preserve_judge_like_pb); ?>">
<?php } ?>
<?php foreach ($preserve_judge_dislikes_pb as $preserve_judge_dislike_pb) { ?>
<input type="hidden" name="brewerJudgeDislikes[]" value="<?php echo h($preserve_judge_dislike_pb); ?>">
<?php } ?>
<?php foreach ($preserve_judge_rank_pb as $preserve_judge_rank_item_pb) { ?>
<input type="hidden" name="brewerJudgeRank[]" value="<?php echo h($preserve_judge_rank_item_pb); ?>">
<?php } ?>
<?php } // end if ($show_judge_section_pb) / else ?>

<?php if ($show_steward_section_pb) { ?>
<a name="steward-info"></a>
<section id="steward-preferences">
    <?php if ($show_assignment_sensitive_fields_pb) { ?>
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
    <?php } else { ?>
    <input type="hidden" name="brewerSteward" value="<?php echo h($row_brewer['brewerSteward']); ?>">
    <?php } ?>

    <?php if ($show_assignment_sensitive_fields_pb) { ?>

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

    <?php } else {
    // GitHub issue #1752: same shape/reasoning as brewerJudgeLocation above.
    $preserve_steward_locations_pb = array_filter(explode(",", (string) ($row_brewer['brewerStewardLocation'] ?? "")), function($v) { return $v !== ""; });
    foreach ($preserve_steward_locations_pb as $preserve_steward_location_pb) { ?>
    <input type="hidden" name="brewerStewardLocation[]" value="<?php echo h($preserve_steward_location_pb); ?>">
    <?php } } ?>

</section><!-- ./ steward-preferences -->
<?php }
// GitHub issue #1752: same reasoning as the judge section's else branch above.
else { ?>
<input type="hidden" name="brewerSteward" value="<?php echo h($row_brewer['brewerSteward']); ?>">
<?php
$preserve_steward_locations_pb = array_filter(explode(",", (string) ($row_brewer['brewerStewardLocation'] ?? "")), function($v) { return $v !== ""; });
foreach ($preserve_steward_locations_pb as $preserve_steward_location_pb) { ?>
<input type="hidden" name="brewerStewardLocation[]" value="<?php echo h($preserve_steward_location_pb); ?>">
<?php } } // end if ($show_steward_section_pb) / else ?>

<?php if (!$entrant_type_brewery) { ?>
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
<?php }
/**
 * GitHub issue #1752: brewerJudgeWaiver isn't preserved here - it's a mandatory,
 * always-"Y", always-required checkbox on the interactive form, so
 * process_brewer_info.inc.php's "Y" default already matches the only value it could
 * ever legitimately hold. brewerJudgeNotes has no such safe default, though.
 */
else { ?>
<input type="hidden" name="brewerJudgeNotes" value="<?php echo h($row_brewer['brewerJudgeNotes']); ?>">
<?php } ?>
