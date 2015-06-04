<?php 
// -----------------------------------------------------------
// Create Tables
// Version 1.3.1.0
// -----------------------------------------------------------

// -----------------------------------------------------------
// Create Tables: 
// -----------------------------------------------------------

/*
EXAMPLE:

$updateSQL = "CREATE TABLE IF NOT EXISTS `$staff_db_table` (
		  `id` int(11) NOT NULL AUTO_INCREMENT,
		  `uid` int(11) DEFAULT NULL COMMENT 'user''s id from user table',
		  `staff_judge` tinyint(2) DEFAULT '0' COMMENT '0=no; 1=yes',
		  `staff_judge_bos` tinyint(2) DEFAULT '0' COMMENT '0=no; 1=yes',
		  `staff_steward` tinyint(2) DEFAULT '0' COMMENT '0=no; 1=yes',
		  `staff_organizer` tinyint(2) DEFAULT '0' COMMENT '0=no; 1=yes',
		  `staff_staff` tinyint(2) DEFAULT '0' COMMENT '0=no; 1=yes',
		  PRIMARY KEY (`id`)
		) ENGINE=MyISAM;";
		mysql_select_db($database, $brewing);
		mysql_real_escape_string($updateSQL);
		$result = mysql_query($updateSQL, $brewing); 
		//echo $updateSQL."<br>";
		$output .= "<li>Staff table created.</li>";
		
*/
?>
