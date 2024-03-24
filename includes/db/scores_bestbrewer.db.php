<?php
$query_bb_prefs = sprintf("SELECT prefsBestBrewerTitle, prefsBestClubTitle, prefsFirstPlacePts, prefsSecondPlacePts, prefsThirdPlacePts, prefsFourthPlacePts, prefsHMPts, prefsTieBreakRule1, prefsTieBreakRule2, prefsTieBreakRule3, prefsTieBreakRule4, prefsTieBreakRule5, prefsTieBreakRule6, prefsBestUseBOS, prefsScoringCOA, prefsWinnerMethod FROM %s WHERE id='1'", $prefix."preferences");
$bb_prefs = mysqli_query($connection,$query_bb_prefs) or die (mysqli_error($connection));
$row_bb_prefs = mysqli_fetch_assoc($bb_prefs);

$query_scores = sprintf("SELECT a.scorePlace, a.scoreEntry, a.scoreTable, b.brewCoBrewer, b.brewCategory, b.brewCategorySort, b.brewSubCategory, c.uid, c.brewerLastName, c.brewerFirstName, c.brewerBreweryName, c.brewerClubs FROM %s a, %s b, %s c WHERE a.eid = b.id AND c.uid = b.brewBrewerID AND a.scorePlace IS NOT NULL", $judging_scores_db_table, $brewing_db_table, $brewer_db_table);
$bb_scores = mysqli_query($connection, $query_scores) or die(mysqli_error($connection));
$bb_row_scores = mysqli_fetch_assoc($bb_scores);
$bb_totalRows_scores = mysqli_num_rows($bb_scores);

if ($row_bb_prefs['prefsBestUseBOS'] == 1) {
    $query_bos_scores = sprintf("SELECT a.scorePlace, a.scoreEntry, b.brewCategory, b.brewCategorySort, b.brewSubCategory, c.brewerClubs, c.uid FROM %s a, %s b, %s c WHERE a.eid = b.id AND c.uid = a.bid AND a.scorePlace IS NOT NULL", $judging_scores_bos_db_table, $brewing_db_table, $brewer_db_table);
    $bb_bos_scores = mysqli_query($connection, $query_bos_scores) or die(mysqli_error($connection));
    $bb_row_bos_scores = mysqli_fetch_assoc($bb_bos_scores);
    $bb_totalRows_bos_scores = mysqli_num_rows($bb_bos_scores);
}

if ($row_bb_prefs['prefsScoringCOA'] == 0) {

    $bb_points_prefs = array($row_bb_prefs['prefsFirstPlacePts'],$row_bb_prefs['prefsSecondPlacePts'],$row_bb_prefs['prefsThirdPlacePts'],$row_bb_prefs['prefsFourthPlacePts'],$row_bb_prefs['prefsHMPts']);

}

else {

    $bb_points_prefs = array();

    // Get overall entries...
    // Per table
    if ($row_bb_prefs['prefsWinnerMethod'] == 0) {

        // Query tables for ids.
        $query_table_ids = sprintf("SELECT id FROM `%s`", $prefix."judging_tables");
        $table_ids = mysqli_query($connection, $query_table_ids);
        $row_table_ids = mysqli_fetch_assoc($table_ids);
        $totalRows_table_ids = mysqli_num_rows($table_ids);

        if ($row_table_ids) {

            do {

                $query_table_count = sprintf("SELECT COUNT(*) AS 'count' FROM `%s` WHERE scoreTable='%s'", $judging_scores_db_table, $row_table_ids['id']);
                $table_count = mysqli_query($connection, $query_table_count);
                $row_table_count = mysqli_fetch_assoc($table_count);

                $bb_points_prefs[$row_table_ids['id']] = $row_table_count['count'];

            } while($row_table_ids = mysqli_fetch_assoc($table_ids));

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

$bb_tiebreaker_prefs = array($row_bb_prefs['prefsTieBreakRule1'],$row_bb_prefs['prefsTieBreakRule2'],$row_bb_prefs['prefsTieBreakRule3'],$row_bb_prefs['prefsTieBreakRule4'],$row_bb_prefs['prefsTieBreakRule5'],$row_bb_prefs['prefsTieBreakRule6']);
?>