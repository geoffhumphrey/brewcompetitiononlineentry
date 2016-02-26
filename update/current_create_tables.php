<?php 
// -----------------------------------------------------------
// Create Tables
// Version XXXXX
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
		mysqli_select_db($connection,$database);
		mysqli_real_escape_string($connection,$updateSQL);
		$result = mysqli_query($connection,$updateSQL) or die (mysqli_error($connection));
		//echo $updateSQL."<br>";
		$output .= "<li>Staff table created.</li>";
		
*/
?>
