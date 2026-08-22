<?php
$db_conn->where('id', '1');
$row_bb_prefs = $db_conn->getOne($prefix."preferences", "prefsBestBrewerTitle, prefsBestClubTitle, prefsFirstPlacePts, prefsSecondPlacePts, prefsThirdPlacePts, prefsFourthPlacePts, prefsHMPts, prefsTieBreakRule1, prefsTieBreakRule2, prefsTieBreakRule3, prefsTieBreakRule4, prefsTieBreakRule5, prefsTieBreakRule6, prefsBestUseBOS, prefsScoringCOA, prefsWinnerMethod");

$query_scores = "SELECT a.scorePlace, a.scoreEntry, a.scoreTable, b.brewCoBrewer, b.brewCategory, b.brewCategorySort, b.brewSubCategory, c.uid, c.brewerLastName, c.brewerFirstName, c.brewerBreweryName, c.brewerClubs FROM ".$judging_scores_db_table." a, ".$brewing_db_table." b, ".$brewer_db_table." c WHERE a.eid = b.id AND c.uid = b.brewBrewerID AND a.scorePlace IS NOT NULL";
$rows_bb_scores = $db_conn->rawQuery($query_scores);
$bb_totalRows_scores = $db_conn->count;

if ($row_bb_prefs['prefsBestUseBOS'] == 1) {
    $query_bos_scores = "SELECT a.scorePlace, a.scoreEntry, b.brewCategory, b.brewCategorySort, b.brewSubCategory, c.brewerClubs, c.uid FROM ".$judging_scores_bos_db_table." a, ".$brewing_db_table." b, ".$brewer_db_table." c WHERE a.eid = b.id AND c.uid = a.bid AND a.scorePlace IS NOT NULL";
    $rows_bb_bos_scores = $db_conn->rawQuery($query_bos_scores);
    $bb_totalRows_bos_scores = $db_conn->count;
}

if ($row_bb_prefs['prefsScoringCOA'] == 0) {

    $bb_points_prefs = [$row_bb_prefs['prefsFirstPlacePts'],$row_bb_prefs['prefsSecondPlacePts'],$row_bb_prefs['prefsThirdPlacePts'],$row_bb_prefs['prefsFourthPlacePts'],$row_bb_prefs['prefsHMPts']];

}

else {

    $bb_points_prefs = [];

    // Get overall entries...
    // Per table
    if ($row_bb_prefs['prefsWinnerMethod'] == 0) {

        // Query tables for ids.
        $rows_table_ids = $db_conn->get($prefix."judging_tables", null, "id");
        $totalRows_table_ids = $db_conn->count;

        if ($rows_table_ids) {

            foreach ($rows_table_ids as $row_table_ids) {

                $db_conn->where('scoreTable', $row_table_ids['id']);
                $row_table_count = $db_conn->getOne($judging_scores_db_table, "COUNT(*) AS 'count'");

                $bb_points_prefs[$row_table_ids['id']] = $row_table_count['count'];

            }

        }

    } // end if ($_SESSION['prefsWinnerMethod'] == 0)

    // Per style (use for both style and sub-style winner display methods)
    else {

        $active_styles = styles_active(0,$go);

        foreach (array_unique($active_styles) as $style) {

            if (!empty($style)) {

                include (DB.'winners_category.db.php');

                if (isset($style)) $bb_points_prefs[$style] = $row_entry_count['count'];

            }

        }

    }

    /*
    // Per Sub-Style
    else {

        $active_styles = styles_active(2,$go);

        foreach (array_unique($active_styles) as $style) {

            if (!empty($style)) {

                $style = explode("^",$style);

                include (DB.'winners_subcategory.db.php');

                if ((isset($style[0])) && (isset($style[1]))) {
                    $substyle = $style[0]."-".$style[1];
                    $bb_points_prefs[$substyle] = $row_entry_count['count'];
                }

            }

        }

    } // end else

    foreach ($bb_points_prefs as $key => $value) {
        echo $key.": ".$value."<br>";
        echo "<ul>";

        for ($i=1; $i <= 5; $i++) {

            if ($value > 0) {
                $points = pow((($value - $i) / $value),3);
                if ($points > 0) echo "<li>".$i." = ".$points." points</li>";
                else echo "<li>".$i." = 0 points</li>";
            }

            else echo "<li>".$i." = 0 points</li>";

        }

        echo "</ul>";

    }

    echo "<br><br>";

    */

} // end else

$bb_tiebreaker_prefs = [$row_bb_prefs['prefsTieBreakRule1'],$row_bb_prefs['prefsTieBreakRule2'],$row_bb_prefs['prefsTieBreakRule3'],$row_bb_prefs['prefsTieBreakRule4'],$row_bb_prefs['prefsTieBreakRule5'],$row_bb_prefs['prefsTieBreakRule6']];
?>