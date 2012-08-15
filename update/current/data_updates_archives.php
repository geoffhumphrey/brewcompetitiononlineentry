<?php

// -----------------------------------------------------------
// Data Updates: Archive Tables
//   Convert the data in archived brewer tables to be compatible
//   with the new boolean schema for paid, received, and winner 
// -----------------------------------------------------------

// -----------------------------------------------------------
// FUTURE Data Updates: Archive Tables
//   Convert the data in the brewStyle row table to key off
//   of the id row of either the 'styles_XXX' table or 'styles_custom'
//   table INSTEAD of the style name.
//   Designate whether the style of the entry is from the main
//   set designated by an admin (M) or whether it is a custom style
//   (C).
// -----------------------------------------------------------

$query_archive = "SELECT archiveSuffix FROM $archive_db_table";
$archive = mysql_query($query_archive, $brewing) or die(mysql_error());
$row_archive = mysql_fetch_assoc($archive);
$totalRows_archive = mysql_num_rows($archive);

if ($totalRows_archive > 0) {
	
	do { $a[] = $row_archive['archiveSuffix']; } while ($row_archive = mysql_fetch_assoc($archive));
	
	foreach ($a as $suffix) {
			
			
		$query_log = sprintf("SELECT brewPaid,brewWinner,brewReceived,brewUpdated,brewConfirmed,id FROM %s",$prefix."brewing_".$suffix);
		$log = mysql_query($query_log, $brewing) or die(mysql_error());
		$row_log = mysql_fetch_assoc($log);
		$totalRows_log = mysql_num_rows($log);
		
		//echo $query_log."<br>";
		
		do {
		if ($row_log['brewPaid'] == "Y") $brewPaid = "1"; else $brewPaid = "0";
		if ($row_log['brewWinner'] == "Y") $brewWinner = "1"; else $brewWinner = "0";
		if ($row_log['brewReceived'] == "Y") $brewReceived = "1"; else $brewReceived = "0";
		
		
		$updateSQL = sprintf("UPDATE ".$prefix."brewing_".$suffix." SET 
								 brewPaid='%s',
								 brewWinner='%s',
								 brewReceived='%s',
								 brewConfirmed='%s',
								 brewUpdated=%s
								 WHERE id='%s';",
								 $brewPaid,
								 $brewWinner,
								 $brewReceived,
								 "1",
								 "NOW()",
								 $row_log['id']);
			mysql_select_db($database, $brewing);
			$result = mysql_query($updateSQL, $brewing) or die(mysql_error()); 
			
		} while ($row_log = mysql_fetch_assoc($log));
		echo "<ul><li>All archive entry data updated.</li></ul>";
		
		/*
		// ------------------------ 
		// Not in 1.2.1.0 - Applies only a future release with multiple style sets
		// ------------------------
		
		do {
			
			$query_style = sprintf("SELECT id FROM styles_bjcp_2008 WHERE style_cat='%s' AND style_subcat='%s'", $row_log['brewCategorySort'],$row_log['brewSubCategory']);
			$style = mysql_query($query_style, $brewing) or die(mysql_error());
			$row_style = mysql_fetch_assoc($style);
			
			$updateSQL = sprintf("UPDATE brewing_$suffix SET brewStyle='%s',brewCategory='%s' WHERE id='%s'",$row_style['id'],"M",$row_log['id']);
			mysql_select_db($database, $brewing);
			$result = mysql_query($updateSQL, $brewing) or die(mysql_error());
			//echo $updateSQL."<br>";
			
		} while ($row_log = mysql_fetch_assoc($log));
		
		echo "<ul><li>Updates to brewing_$suffix table completed.</li></ul>";
		
		*/
		
		
		/* 
		
		// ------------------------ 
		// Not in 1.2.1.0 - Applies only a future release with multiple staff additions
		// ------------------------
		
		
		$query_brewer = "SELECT * FROM ".$prefix."brewer_".$suffix;
		$brewer = mysql_query($query_brewer, $brewing) or die(mysql_error());
		$row_brewer = mysql_fetch_assoc($brewer);

		do { 

			if ($row_brewer['brewerAssignmentJudge'] == "J") { 
				$brewerAssignmentJudge = "1";
				$brewerAssignmentStaff = "0";
				$brewerAssignmentSteward = "0"; 
				$brewerAssignmentOrganizer = "0";
				}
				
			if ($row_brewer['brewerAssignmentJudge'] == "S") { 
				$brewerAssignmentJudge = "0";
				$brewerAssignmentStaff = "0";
				$brewerAssignmentSteward = "1"; 
				$brewerAssignmentOrganizer = "0";
				}
				
			if ($row_brewer['brewerAssignmentJudge'] == "X") { 
				$brewerAssignmentJudge = "0";
				$brewerAssignmentStaff = "1";
				$brewerAssignmentSteward = "0"; 
				$brewerAssignmentOrganizer = "0";
				}
			
			if ($row_brewer['brewerAssignmentJudge'] == "O") { 
				$brewerAssignmentJudge = "0";
				$brewerAssignmentStaff = "0";
				$brewerAssignmentSteward = "0"; 
				$brewerAssignmentOrganizer = "1";
				}
			
			if ($row_brewer['brewerAssignmentJudge'] == "") { 
				$brewerAssignmentJudge = "0";
				$brewerAssignmentStaff = "0";
				$brewerAssignmentSteward = "0"; 
				$brewerAssignmentOrganizer = "0";
				}
			if ($row_brewer['brewerJudge'] == "Y") $brewerJudge = "1"; else $brewerJudge = "0";
			if ($row_brewer['brewerSteward'] == "Y") $brewerSteward = "1"; else $brewerSteward = "0";
			if ($row_brewer['brewerJudgeBOS'] == "Y") $brewerJudgeBOS = "1"; else $brewerJudgeBOS = "0";
			if ($row_brewer['brewerDiscount'] == "Y") $brewerDiscount = "1"; else $brewerDiscount = "0";
			
			$updateSQL = sprintf("UPDATE ".$prefix."brewer_".$suffix." SET 
								 brewerJudge='%s',
								 brewerSteward='%s',
								 brewerAssignmentJudge='%s', 
								 brewerAssignmentStaff='%s', 
								 brewerAssignmentSteward='%s', 
								 brewerAssignmentOrganizer='%s', 
								 brewerJudgeBOS='%s', 
								 brewerDiscount='%s'
								 WHERE id='%s'", 
								 $brewerJudge,
								 $brewerSteward,
								 $brewerAssignmentJudge, 
								 $brewerAssignmentStaff,
								 $brewerAssignmentSteward, 
								 $brewerAssignmentOrganizer, 
								 $brewerJudgeBOS, 
								 $brewerDiscount, 
								 $row_brewer['id']);
			
			mysql_select_db($database, $brewing);
			$result = mysql_query($updateSQL, $brewing) or die(mysql_error());
			//echo $updateSQL."<br>";

			//echo "<ul><li>Record for ".$row_brewer['brewerLastName'].", ".$row_brewer['brewerFirstName']." updated.</li></ul>";

			} 
			while ($row_brewer = mysql_fetch_assoc($brewer));

			// Change rows to tinyint type
			$updateSQL = "ALTER TABLE  `".$prefix."brewer_".$suffix."` CHANGE  `brewerAssignmentJudge`  `brewerAssignmentJudge` TINYINT(1) NULL DEFAULT NULL COMMENT '1 for true; 0 for false', CHANGE  `brewerJudgeBOS`  `brewerJudgeBOS` TINYINT(1) NULL DEFAULT NULL COMMENT '1 for true; 0 for false', CHANGE  `brewerDiscount`  `brewerDiscount` TINYINT(1) NULL DEFAULT NULL COMMENT '1 for true; 0 for false', CHANGE  `brewerJudge`  `brewerJudge` TINYINT(1) NULL DEFAULT NULL COMMENT '1 for true; 0 for false', CHANGE  `brewerSteward`  `brewerSteward` TINYINT(1) NULL DEFAULT NULL COMMENT '1 for true; 0 for false'";
			//echo $updateSQL."<br>";
			mysql_select_db($database, $brewing);
			$result = mysql_query($updateSQL, $brewing) or die(mysql_error());
			//echo $updateSQL."<br>";
			echo "<ul><li>Updates to ".$prefix."brewer_".$suffix." table completed.</li></ul>";
		   */
		  
		  
	} // end foreach
} // end if ($totalRows_archive > 0) 

?>