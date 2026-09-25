<?php
/**
 * Code is executed when rendering a scoresheet.
 * Not used for any other purposes.
 */

$styles_db_table = $prefix."styles";
$db_conn->where("id", $id);
$row_eval = $db_conn->getOne($dbTable);

$db_conn->where("uid", $row_eval['evalJudgeInfo']);
$row_judge = $db_conn->getOne($prefix."brewer".$archive_suffix);

if (empty($row_eval['evalStyle'])) {

	$db_conn->where("id", $row_eval['eid']);
	$row_brewing = $db_conn->getOne($prefix."brewing".$archive_suffix);

	// NOTE: the BJCP2025 branch's OR-group must stay explicitly parenthesized in raw SQL text -
	// MysqliDb's where()/orWhere() builder has no parenthesization support, so chaining where()
	// calls here would silently produce "... AND brewStyleNum=? AND brewStyleVersion=? OR brewStyleVersion=?"
	// (wrong precedence) instead of the intended "... AND (brewStyleVersion=? OR brewStyleVersion=?)".
	if ($_SESSION['prefsStyleSet'] == "BJCP2025") {
		$query_style = "SELECT id,brewStyle,brewStyleGroup,brewStyleNum,brewStyleType FROM ".$styles_db_table." WHERE brewStyle=? AND brewStyleGroup=? AND brewStyleNum=? AND (brewStyleVersion='BJCP2025' OR brewStyleVersion='BJCP2021')";
		$params_style = array($row_brewing['brewStyle'], $row_brewing['brewCategorySort'], $row_brewing['brewSubCategory']);
	}

	elseif ($_SESSION['prefsStyleSet'] == "BJCP2026") {
		$query_style = "SELECT id,brewStyle,brewStyleGroup,brewStyleNum,brewStyleType FROM ".$styles_db_table." WHERE brewStyle=? AND brewStyleGroup=? AND brewStyleNum=? AND (brewStyleVersion='BJCP2026' OR brewStyleVersion='BJCP2025' OR brewStyleVersion='BJCP2021')";
		$params_style = array($row_brewing['brewStyle'], $row_brewing['brewCategorySort'], $row_brewing['brewSubCategory']);
	}

	elseif ($_SESSION['prefsStyleSet'] == "AABC2025") {
		$query_style = "SELECT id,brewStyle,brewStyleGroup,brewStyleNum,brewStyleType FROM ".$styles_db_table." WHERE brewStyle=? AND brewStyleGroup=? AND brewStyleNum=? AND (brewStyleVersion='AABC2025' OR brewStyleVersion='AABC2022')";
		$params_style = array($row_brewing['brewStyle'], $row_brewing['brewCategorySort'], $row_brewing['brewSubCategory']);
	}

	else {
		$query_style = "SELECT id,brewStyle,brewStyleGroup,brewStyleNum,brewStyleType FROM ".$styles_db_table." WHERE brewStyle=? AND brewStyleGroup=? AND brewStyleNum=? AND brewStyleVersion=?";
		$params_style = array($row_brewing['brewStyle'], $row_brewing['brewCategorySort'], $row_brewing['brewSubCategory'], $_SESSION['prefsStyleSet']);
	}

	$row_style = $db_conn->rawQueryOne($query_style, $params_style);

}

else {

	$db_conn->where("id", $row_eval['evalStyle']);
	$row_style = $db_conn->getOne($styles_db_table, "brewStyle,brewStyleGroup,brewStyleNum,brewStyleType");

}

$db_conn->where("id", $row_eval['eid']);
$row_entry_info = $db_conn->getOne($prefix."brewing".$archive_suffix);

// Staff (userLevel <= 1) may view any scoresheet. A logged-in entrant
// (userLevel > 1) may view scoresheets for their own entries, and a judge
// (userLevel > 1) may view an evaluation they themselves submitted - the
// judge who scored an entry is very rarely also that entry's own brewer,
// so without this a judge could never view/print their own just-submitted
// scoresheet (100% reproducible for a practice entry, since its "owner" is
// a synthetic account no real judge can ever be logged in as). Anonymous
// access is only valid with a token matching this specific evaluation's
// evalToken - previously $token was never actually checked against the row.
$eval_access_denied = FALSE;
$eval_release_pending = FALSE;
if ((isset($_SESSION['loginUsername'])) && ($token == "default")) {

	$eval_is_staff = ($_SESSION['userLevel'] <= 1);
	$eval_is_judge = ($row_eval['evalJudgeInfo'] == $_SESSION['user_id']);
	$eval_is_owner = ((!empty($row_entry_info)) && ($row_entry_info['brewBrewerID'] == $_SESSION['user_id']));

	if ((!$eval_is_staff) && (!$eval_is_owner) && (!$eval_is_judge)) {
		$eval_access_denied = TRUE;
	}

	// An entrant viewing their own entry's scoresheet (not staff, not the judge who
	// scored it - both already exempt above, regardless of release timing) is still
	// subject to the same early-release gate that brewer_entries.sec.php/.pub.php
	// check before ever showing the link to this endpoint. Without this, an entry's
	// plain numeric id - always visible to its owner on their own entries page,
	// gate or no gate - could be used to build this URL directly and view the
	// scoresheet before either release date. See issue #694.
	elseif (($eval_is_owner) && (!$eval_is_judge) && (!$eval_is_staff)) {

		$eval_scoresheets_released = (($_SESSION['prefsDisplayScoresheets'] == "Y") && (judging_winner_display($_SESSION['prefsScoresheetDelay'])));
		$eval_winners_released = (($_SESSION['prefsDisplayWinners'] == "Y") && (judging_winner_display($_SESSION['prefsWinnerDelay'])));

		if ((!$eval_scoresheets_released) && (!$eval_winners_released)) {
			$eval_access_denied = TRUE;
			$eval_release_pending = TRUE;
		}

	}

}
elseif ($token != "default") {
	if ((empty($row_eval['evalToken'])) || ($token != $row_eval['evalToken'])) {
		$eval_access_denied = TRUE;
	}
}
else {
	$eval_access_denied = TRUE;
}
?>