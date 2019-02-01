<?php
require(DB.'winners.db.php');
require(DB.'output_results.db.php');
require(DB.'score_count.db.php');

if ((isset($_SESSION['loginUsername'])) && ($_SESSION['userLevel'] <= 1)) {

    if (NHC) $base_url = "../";

	if (($go == "judging_scores") && ($action == "print"))  {
		if ($row_prefs['prefsWinnerMethod'] == "1") include (SECTIONS.'winners_category.sec.php');
		elseif ($row_prefs['prefsWinnerMethod'] == "2") include (SECTIONS.'winners_subcategory.sec.php');
		else include (SECTIONS.'winners.sec.php');
	}

	elseif (($go == "judging_scores_bos") && ($action == "print")) {
        include (SECTIONS.'bos.sec.php');
    }

    elseif (($go == "best") && ($action == "print") && (($_SESSION['prefsShowBestBrewer'] != 0) || ($_SESSION['prefsShowBestClub'] != 0)))  {
        include (SECTIONS.'bestbrewer.sec.php');
    }

    elseif (($go == "all") && ($action == "print")) {

        echo "<h1>".$_SESSION['contestName']."</h1>";
        echo sprintf("<p class=\"lead\">%s <strong>%s</strong> %s <strong>%s</strong> %s</p>",$judge_closed_001,get_entry_count('received'),$judge_closed_002,get_participant_count('default'),$judge_closed_003);
        echo "<h2>".$label_bos."</h2>";
        include (SECTIONS.'bos.sec.php');

        if (($_SESSION['prefsShowBestBrewer'] != 0) || ($_SESSION['prefsShowBestClub'] != 0)) include (SECTIONS.'bestbrewer.sec.php');

        echo "<h2>".$label_winners."</h2>";
        if ($row_prefs['prefsWinnerMethod'] == "1") include (SECTIONS.'winners_category.sec.php');
        elseif ($row_prefs['prefsWinnerMethod'] == "2") include (SECTIONS.'winners_subcategory.sec.php');
        else include (SECTIONS.'winners.sec.php');

    }

}

else echo "<p>Please log in as an Admin to view this report.</p>";

?>
