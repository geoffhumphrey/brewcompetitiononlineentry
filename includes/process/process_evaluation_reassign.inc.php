<?php
/*
 * Module:      process_evaluation_reassign.inc.php
 * Description: Admin-only fix for a judge scoring the wrong entry - moves a
 *              single evaluation row onto the correct entry. Deliberately
 *              narrow (GitHub #1756): only allowed before "Import Score
 *              Data" has run for the evaluation's current entry, so there's
 *              no possibility of an already-imported judging_scores row
 *              sitting on incorrect grounds. The full-scope version (moving
 *              evalTable/evalSpecialIngredients/uid too, resetting
 *              evalPlace/evalMiniBOS/evalPosition, warning on stale official
 *              scores) is filed in the BCOE&M Enhancement Ledger for later.
 */

if ((isset($_SERVER['HTTP_REFERER'])) && ((isset($_SESSION['loginUsername'])) && (isset($_SESSION['userLevel'])))) {

	$errors = FALSE;
	$error_output = array();
	$_SESSION['error_output'] = "";

	$reassignGoTo = clean_up_url($_SERVER['HTTP_REFERER']);

	if ($_SESSION['userLevel'] == 0) {

		$id = sterilize($id);
		$target_entry_number = isset($_GET['target_entry_number']) ? sterilize($_GET['target_entry_number']) : "";

		$db_conn->where('id', $id);
		$row_eval_current = $db_conn->getOne($prefix."evaluation");

		if (empty($row_eval_current)) {
			$errors = TRUE;
			$error_output[] = "Evaluation not found.";
		}

		else {

			// Resolve the target entry the same way a judge's own free-text entry-number
			// field does when adding a scoresheet on the fly (eval/scoresheet.eval.php:161-176).
			if ($_SESSION['prefsDisplaySpecial'] == "J") $db_conn->where('brewJudgingNumber', $target_entry_number);
			else $db_conn->where('id', ltrim($target_entry_number,"0"));
			$row_target_entry = $db_conn->getOne($prefix."brewing");

			if (empty($row_target_entry)) {
				$errors = TRUE;
				$error_output[] = "Target entry not found.";
			}

			elseif ($row_target_entry['id'] == $row_eval_current['eid']) {
				$errors = TRUE;
				$error_output[] = "That is already this evaluation's entry.";
			}

			else {

				// Gate: only allowed while the CURRENT entry has no judging_scores row yet -
				// judging_scores has no FK to evaluation and is only ever populated by manual
				// transcription or the separate Import Score Data action, so this reduces to
				// "Import Score Data hasn't run for this entry yet."
				$db_conn->where('eid', $row_eval_current['eid']);
				$row_scores_existing = $db_conn->getOne($prefix."judging_scores");

				if (!empty($row_scores_existing)) {
					$errors = TRUE;
					$error_output[] = "This entry's scores have already been recorded/imported - reassignment is only available before that happens.";
				}

				else {

					// Guard: block a duplicate evaluation for the same judge on the target entry.
					$db_conn->where('eid', $row_target_entry['id']);
					$db_conn->where('evalJudgeInfo', $row_eval_current['evalJudgeInfo']);
					$row_eval_duplicate = $db_conn->getOne($prefix."evaluation");

					if (!empty($row_eval_duplicate)) {
						$errors = TRUE;
						$error_output[] = "This judge already has a separate evaluation for that entry.";
					}

					else {

						// Recompute evalStyle against the target entry's current style, mirroring
						// eval/db.eval.php's own empty-evalStyle resolution logic.
						$styles_db_table = $prefix."styles";

						if ($_SESSION['prefsStyleSet'] == "BJCP2025") {
							$query_style = "SELECT id FROM ".$styles_db_table." WHERE brewStyle=? AND brewStyleGroup=? AND brewStyleNum=? AND (brewStyleVersion='BJCP2025' OR brewStyleVersion='BJCP2021')";
							$params_style = array($row_target_entry['brewStyle'], $row_target_entry['brewCategorySort'], $row_target_entry['brewSubCategory']);
						}

						elseif ($_SESSION['prefsStyleSet'] == "BJCP2026") {
							$query_style = "SELECT id FROM ".$styles_db_table." WHERE brewStyle=? AND brewStyleGroup=? AND brewStyleNum=? AND (brewStyleVersion='BJCP2026' OR brewStyleVersion='BJCP2025' OR brewStyleVersion='BJCP2021')";
							$params_style = array($row_target_entry['brewStyle'], $row_target_entry['brewCategorySort'], $row_target_entry['brewSubCategory']);
						}

						elseif ($_SESSION['prefsStyleSet'] == "AABC2025") {
							$query_style = "SELECT id FROM ".$styles_db_table." WHERE brewStyle=? AND brewStyleGroup=? AND brewStyleNum=? AND (brewStyleVersion='AABC2025' OR brewStyleVersion='AABC2022')";
							$params_style = array($row_target_entry['brewStyle'], $row_target_entry['brewCategorySort'], $row_target_entry['brewSubCategory']);
						}

						else {
							$query_style = "SELECT id FROM ".$styles_db_table." WHERE brewStyle=? AND brewStyleGroup=? AND brewStyleNum=? AND brewStyleVersion=?";
							$params_style = array($row_target_entry['brewStyle'], $row_target_entry['brewCategorySort'], $row_target_entry['brewSubCategory'], $_SESSION['prefsStyleSet']);
						}

						$row_target_style = $db_conn->rawQueryOne($query_style, $params_style);
						$target_eval_style = (!empty($row_target_style)) ? $row_target_style['id'] : "";

						$db_conn->where('id', $id);
						$result = $db_conn->update($prefix."evaluation", array(
							'eid' => $row_target_entry['id'],
							'evalStyle' => $target_eval_style,
							'evalUpdatedDate' => time()
						));

						if (!$result) {
							$error_output[] = $db_conn->getLastError();
							$errors = TRUE;
						}

						// msg=2 matches the existing "Updated!" message already mapped for
						// section=evaluation in includes/headers.inc.php - same code
						// eval/process.eval.php's own action=="edit" success path uses.
						else $reassignGoTo = clean_up_url($_SERVER['HTTP_REFERER'])."&msg=2";

					}

				}

			}

		}

	}

	else {
		$errors = TRUE;
		$error_output[] = "Not authorized.";
	}

	if (!empty($error_output)) $_SESSION['error_output'] = $error_output;
	// Matches process_delete.inc.php's own error-redirect convention for this same row type.
	if ($errors) $reassignGoTo = $base_url."index.php?section=admin&msg=3";

	$reassignGoTo = prep_redirect_link($reassignGoTo);
	$redirect_go_to = sprintf("Location: %s", $reassignGoTo);

}

else {

	$redirect = $base_url."index.php?msg=98";
	$redirect = prep_redirect_link($redirect);
	$redirect_go_to = sprintf("Location: %s", $redirect);

}
?>
