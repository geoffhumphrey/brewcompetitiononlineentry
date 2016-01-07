<?php
// BY TABLE	
if ($_SESSION['prefsWinnerMethod'] == 0) {
	$query_scores = sprintf("SELECT eid,scorePlace FROM $judging_scores_db_table WHERE scoreTable='%s' AND scorePlace IS NOT NULL ORDER BY scorePlace ASC", $row_sql['id']);
	$scores = mysql_query($query_scores, $brewing) or die(mysql_error());
	$row_scores = mysql_fetch_assoc($scores);
	$totalRows_scores = mysql_num_rows($scores);
	
	//echo $query_scores."<br>";
	if ($totalRows_scores > 0) {
		do { 
			mysql_select_db($database, $brewing);
			$query_entries = sprintf("SELECT id,brewBrewerID,brewCoBrewer,brewName,brewStyle,brewCategorySort,brewCategory,brewSubCategory,brewBrewerFirstName,brewBrewerLastName,brewJudgingNumber FROM $brewing_db_table WHERE id='%s'", $row_scores['eid']);
			$entries = mysql_query($query_entries, $brewing) or die(mysql_error());
			$row_entries = mysql_fetch_assoc($entries);
			
			$query_brewer = sprintf("SELECT id,brewerFirstName,brewerLastName,brewerClubs,brewerEmail,brewerAddress,brewerCity,brewerState,brewerZip,brewerCountry,brewerPhone1 FROM $brewer_db_table WHERE uid='%s'", $row_entries['brewBrewerID']);
			$brewer = mysql_query($query_brewer, $brewing) or die(mysql_error());
			$row_brewer = mysql_fetch_assoc($brewer);
			
			$a[] = array($row_sql['tableNumber'],$row_sql['tableName'],$row_entries['brewCategory'],$row_entries['brewSubCategory'],$row_entries['brewStyle'],$row_scores['scorePlace'],strtr($row_brewer['brewerLastName'],$html_remove),strtr($row_brewer['brewerFirstName'],$html_remove),$row_brewer['brewerEmail'],$row_brewer['brewerAddress'],$row_brewer['brewerCity'],$row_brewer['brewerState'],$row_brewer['brewerZip'],$row_brewer['brewerCountry'],$row_brewer['brewerPhone1'],strtr($row_entries['brewName'],$html_remove),$row_brewer['brewerClubs'],$row_entries['brewCoBrewer']);
			
		} while ($row_scores = mysql_fetch_assoc($scores)); 
	}
}

// BY CATEGORY
if ($_SESSION['prefsWinnerMethod'] == 1) {
	
	$query_styles = sprintf("SELECT brewStyleGroup FROM %s WHERE brewStyleActive='Y' AND (brewStyleVersion='%s' OR brewStyleOwn='custom') ORDER BY brewStyleGroup ASC", $styles_db_table, $_SESSION['prefsStyleSet']);
	$styles = mysql_query($query_styles, $brewing) or die(mysql_error());
	$row_styles = mysql_fetch_assoc($styles);
	$totalRows_styles = mysql_num_rows($styles);
	do { $z[] = $row_styles['brewStyleGroup']; } while ($row_styles = mysql_fetch_assoc($styles));
	
	foreach (array_unique($z) as $style) {
		$query_entry_count = sprintf("SELECT COUNT(*) as 'count' FROM %s WHERE brewCategorySort='%s' AND brewReceived='1'", $brewing_db_table,  $style);
		$entry_count = mysql_query($query_entry_count, $brewing) or die(mysql_error());
		$row_entry_count = mysql_fetch_assoc($entry_count);
		
		$query_score_count = sprintf("SELECT  COUNT(*) as 'count' FROM %s a, %s b, %s c WHERE b.brewCategorySort='%s' AND a.eid = b.id AND c.uid = b.brewBrewerID", $judging_scores_db_table, $brewing_db_table, $brewer_db_table, $style);
		if (($action == "print") && ($view == "winners")) $query_score_count .= " AND a.scorePlace IS NOT NULL";
		if (($action == "default") && ($view == "default")) $query_score_count .= " AND a.scorePlace IS NOT NULL";
		$score_count = mysql_query($query_score_count, $brewing) or die(mysql_error());
		$row_score_count = mysql_fetch_assoc($score_count);
		
		if ($row_score_count['count'] > "0")   {
			
			$query_scores = sprintf("SELECT a.scoreTable, a.scorePlace, a.scoreEntry, b.brewName, b.brewCategory, b.brewCategorySort, b.brewSubCategory, b.brewStyle, b.brewCoBrewer, c.brewerLastName, c.brewerFirstName, c.brewerEmail, c.brewerClubs, c.brewerAddress, c.brewerState, c.brewerCity, c.brewerZip, c.brewerPhone1, c.brewerCountry FROM %s a, %s b, %s c WHERE b.brewCategorySort='%s' AND a.eid = b.id AND c.uid = b.brewBrewerID", $judging_scores_db_table, $brewing_db_table, $brewer_db_table, $style);
			$query_scores .= " AND a.scorePlace IS NOT NULL";
			$query_scores .= " ORDER BY b.brewCategory,a.scorePlace ASC";
			//echo $query_scores."<br>";
			$scores = mysql_query($query_scores, $brewing) or die(mysql_error());
			$row_scores = mysql_fetch_assoc($scores);
			$totalRows_scores = mysql_num_rows($scores);
					
			do { 
				$query_table_name = sprintf("SELECT tableName,tableNumber from %s WHERE id = '%s'",$judging_tables_db_table,$row_scores['scoreTable']);
				$table_name = mysql_query($query_table_name, $brewing) or die(mysql_error());
				$row_table_name = mysql_fetch_assoc($table_name);
			
				$a[] = array($row_table_name['tableNumber'],strtr($row_table_name['tableName'],$html_remove),$row_scores['brewCategory'],$row_scores['brewSubCategory'],$row_scores['brewStyle'],$row_scores['scorePlace'],strtr($row_scores['brewerLastName'],$html_remove),strtr($row_scores['brewerFirstName'],$html_remove),$row_scores['brewerEmail'],$row_scores['brewerAddress'],$row_scores['brewerCity'],$row_scores['brewerState'],$row_scores['brewerZip'],$row_scores['brewerCountry'],$row_scores['brewerPhone1'],strtr($row_scores['brewName'],$html_remove),$row_scores['brewerClubs'],$row_scores['brewCoBrewer']);
			} while ($row_scores = mysql_fetch_assoc($scores));
		}
	}	
} // end if ($_SESSION['prefsWinnerMethod'] == 1)

// BY SUB-CATEGORY
if ($_SESSION['prefsWinnerMethod'] == 2) {
	
	$query_styles = sprintf("SELECT brewStyleGroup,brewStyleNum,brewStyleFROM %s WHERE brewStyleActive='Y' AND (brewStyleVersion='%s' OR brewStyleOwn='custom') ORDER BY brewStyleGroup,brewStyleNum ASC", $styles_db_table, $_SESSION['prefsStyleSet']);
	$styles = mysql_query($query_styles, $brewing) or die(mysql_error());
	$row_styles = mysql_fetch_assoc($styles);
	$totalRows_styles = mysql_num_rows($styles);
	do { $b[] = $row_styles['brewStyleGroup']."-".$row_styles['brewStyleNum']; } while ($row_styles = mysql_fetch_assoc($styles));
	
	foreach (array_unique($b) as $style) {
		$style = explode("-",$style);
		
		$query_score_count = sprintf("SELECT  COUNT(*) as 'count' FROM %s a, %s b, %s c WHERE b.brewCategorySort='%s' AND b.brewSubCategory='%s' AND a.eid = b.id AND a.scorePlace IS NOT NULL AND c.uid = b.brewBrewerID", $judging_scores_db_table, $brewing_db_table, $brewer_db_table, $style[0], $style[1]);
		$score_count = mysql_query($query_score_count, $brewing) or die(mysql_error());
		$row_score_count = mysql_fetch_assoc($score_count);
		
		//echo $row_score_count['count'];
		//echo $query_score_count;
		if ($row_score_count['count'] > 0)   {
		
			$query_scores = sprintf("SELECT a.scoreTable, a.scorePlace, a.scoreEntry, b.brewName, b.brewCategory, b.brewCategorySort, b.brewSubCategory, b.brewStyle, b.brewCoBrewer, c.brewerLastName, c.brewerFirstName, c.brewerClubs, c.brewerAddress, c.brewerState, c.brewerCity, c.brewerZip, c.brewerPhone1, c.brewerCountry FROM %s a, %s b, %s c WHERE b.brewCategorySort='%s' AND b.brewSubCategory='%s' AND a.eid = b.id  AND c.uid = b.brewBrewerID", $judging_scores_db_table, $brewing_db_table, $brewer_db_table, $style[0],$style[1]);
			$query_scores .= " AND a.scorePlace IS NOT NULL";
			$query_scores .= " ORDER BY b.brewCategory,b.brewSubCategory,a.scorePlace";
			//echo $query_scores;
			$scores = mysql_query($query_scores, $brewing) or die(mysql_error());
			$row_scores = mysql_fetch_assoc($scores);
			$totalRows_scores = mysql_num_rows($scores);
					
			do { 
			$style = $row_scores['brewCategory'].$row_scores['brewSubCategory'];
			if ($row_scores['brewCategorySort'] > 28) $style_long = style_convert($row_scores['brewCategorySort'],1);
			else $style_long = $row_scores['brewStyle'];
			
				$query_table_name = sprintf("SELECT tableName,tableNumber from %s WHERE id = '%s'",$judging_tables_db_table,$row_scores['scoreTable']);
				$table_name = mysql_query($query_table_name, $brewing) or die(mysql_error());
				$row_table_name = mysql_fetch_assoc($table_name);
			
				$a[] = array($row_table_name['tableNumber'],strtr($row_table_name['tableName'],$html_remove),$row_scores['brewCategory'],$row_scores['brewSubCategory'],$style_long,$row_scores['scorePlace'],strtr($row_scores['brewerLastName'],$html_remove),strtr($row_scores['brewerFirstName'],$html_remove),$row_scores['brewerEmail'],$row_scores['brewerAddress'],$row_scores['brewerCity'],$row_brewer['brewerState'],$row_scores['brewerZip'],$row_scores['brewerCountry'],$row_scores['brewerPhone1'],strtr($row_scores['brewName'],$html_remove),$row_scores['brewerClubs'],$row_scores['brewCoBrewer']);
			} while ($row_scores = mysql_fetch_assoc($scores));
		}
	}
} // end if ($_SESSION['prefsWinnerMethod'] == 2)
?>