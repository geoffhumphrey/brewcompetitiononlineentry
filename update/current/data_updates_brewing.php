<?php 
// -----------------------------------------------------------
// Data Updates: Brewing Table
//   Convert brewPaid, brewWinner, and brewReceived to
//   boolean values.
// -----------------------------------------------------------
if ($totalRows_log > 0) {
	do {
		if ($row_log['brewPaid'] == "Y") $brewPaid = "1"; else $brewPaid = "0";
		if ($row_log['brewWinner'] == "Y") $brewWinner = "1"; else $brewWinner = "0";
		if ($row_log['brewReceived'] == "Y") $brewReceived = "1"; else $brewReceived = "0";
		
		
		$updateSQL = sprintf("UPDATE ".$prefix."brewing SET 
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
			$result = mysql_query($updateSQL, $brewing); 
			//$output .= $updateSQL."<br>";
		
		/*
		
		// ---------------------
		// Not in 1.2.1.0 Release
		// ---------------------
		
		if ($row_log['brewCategorySort'] > 28) {
			$query_style = sprintf("SELECT id FROM styles_custom WHERE style_cat='%s' and style_subcat='%s'", $row_log['brewCategorySort'],$row_log['brewSubCategory']);
			$style = mysql_query($query_style, $brewing);
			$row_style = mysql_fetch_assoc($style);
			
			$updateSQL = sprintf("UPDATE $brewing_db_table SET 
								 brewStyle='%s',
								 brewCategory='%s',
								 brewPaid='%s',
								 brewWinner='%s',
								 brewReceived='%s'
								 WHERE id='%s'",
								 $row_style['id'],
								 "C",
								 $brewPaid,
								 $brewWinner,
								 $brewReceived,
								 $row_log['id']);
			mysql_select_db($database, $brewing);
			//$result = mysql_query($updateSQL, $brewing); 
	$output .= $updateSQL."<br>";
			//$output .= $updateSQL."<br>";
		}
		
		else {
			$query_style = sprintf("SELECT id FROM $styles_db_table WHERE style_cat='%s' AND style_subcat='%s'", $row_log['brewCategorySort'],$row_log['brewSubCategory']);
			$style = mysql_query($query_style, $brewing);
			$row_style = mysql_fetch_assoc($style);
			
			$updateSQL = sprintf("UPDATE $brewing_db_table SET 
								 brewStyle='%s',
								 brewCategory='%s',
								 brewPaid='%s',
								 brewWinner='%s',
								 brewReceived='%s'
								 WHERE id='%s'",
								 $row_style['id'],
								 "M",
								 $brewPaid,
								 $brewWinner,
								 $brewReceived,
								 $row_log['id']);
			mysql_select_db($database, $brewing);
			//$result = mysql_query($updateSQL, $brewing); 
	$output .= $updateSQL."<br>";
			//$output .= $updateSQL."<br>";
		}
	*/	
	} while ($row_log = mysql_fetch_assoc($log));
	$output .= "<li>All entry data updated.</li>";
}
$updateSQL = "ALTER TABLE  `".$prefix."brewing` 
CHANGE  `brewPaid`  `brewPaid` TINYINT( 1 ) NULL DEFAULT NULL COMMENT '1=true; 0=false';";
mysql_select_db($database, $brewing);
$result = mysql_query($updateSQL, $brewing); 
//$output .= $updateSQL."<br>";
$updateSQL = "ALTER TABLE  `".$prefix."brewing` CHANGE  `brewReceived`  `brewReceived` TINYINT( 1 ) NULL DEFAULT NULL COMMENT '1=true; 0=false';";
mysql_select_db($database, $brewing);
$result = mysql_query($updateSQL, $brewing);  
//$output .= $updateSQL."<br>";
$output .= "<li>Conversion of paid and received rows to new schema in brewing table completed.</li>";
?>