<?php

if (!NHC) {
	// -----------------------------------------------------------
	// Create Table: staff
	//   Table to house information about staff.
	// -----------------------------------------------------------
		
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
		$result = mysql_query($updateSQL, $brewing) or die(mysql_error()); 
		//echo $updateSQL."<br>";
		$output .= "<li>Staff table created.</li>";
	
	// -----------------------------------------------------------
	// Create Table: mods
	//   Table to house information about custom module files.
	// -----------------------------------------------------------
	
	
	
		$updateSQL = "CREATE TABLE IF NOT EXISTS `$mods_db_table` (
			  `id` int(11) NOT NULL AUTO_INCREMENT,
			  `mod_name` varchar(255) DEFAULT NULL COMMENT 'Name of the custom module',
			  `mod_type` tinyint(2) DEFAULT NULL COMMENT 'Type of module: 0=informational 1=report 2=export 3=other',
			  `mod_extend_function` tinyint(2) DEFAULT NULL COMMENT 'If the custom module extends a core function. 0=all 1=home 2=rules 3=volunteer 4=sponsors 5=contact 6=register 7=pay 8=list 9=admin',
			  `mod_extend_function_admin` varchar(255) DEFAULT NULL COMMENT 'If the custom module extends an admin function (9 in mod_extend_function). Keys off of the go= variable.',
			  `mod_filename` varchar(255) DEFAULT NULL COMMENT 'File name of the custom module',
			  `mod_description` text COMMENT 'Short description of the custom module',
			  `mod_permission` tinyint(1) DEFAULT NULL COMMENT 'Who has permission to view the module. 0=uber-admin 1=admin 2=all',
			  `mod_rank` int(3) DEFAULT NULL COMMENT 'Rank order of the mod on the admin mods list',
			  `mod_display_rank` tinyint(1) DEFAULT NULL COMMENT '0=normal 1=above default content',
			  `mod_enable` tinyint(1) DEFAULT NULL COMMENT '0=no 1=yes',
			  PRIMARY KEY (`id`)
			) ENGINE=MyISAM;";
		mysql_select_db($database, $brewing);
		mysql_real_escape_string($updateSQL);
		$result = mysql_query($updateSQL, $brewing) or die(mysql_error()); 
		//echo $updateSQL."<br>";
	
	$output .=  "<li>Custom Modules table created.</li>";
	
} // end if (!NHC)
/*
if (NHC) {
	$updateSQL = "ALTER TABLE  `".$prefix."mods` ADD `mod_enable` TINYINT(1) NULL DEFAULT NULL;";
	mysql_select_db($database, $brewing);
	mysql_real_escape_string($updateSQL);
	//echo $updateSQL."<br>";
	$result = mysql_query($updateSQL, $brewing) or die(mysql_error());
}
*/
/*

// -----------------------------------------------------------
// Create Tables: Styles Index, BJCP 2008, BA 2012
//  Add new tables to accomodate more style sets other than
//  BJCP 2008.
// -----------------------------------------------------------

$updateSQL = "CREATE TABLE IF NOT EXISTS `styles_index` (`id` int(11) NOT NULL AUTO_INCREMENT,`style_name` varchar(255) DEFAULT NULL, `style_year` int(4) DEFAULT NULL, `style_info` text,`style_organization` varchar(255) DEFAULT NULL, `style_db_name` varchar(255) DEFAULT NULL, `style_owner` varchar(255) DEFAULT NULL,`style_active` tinyint(1) DEFAULT NULL COMMENT '1 for true; 0 for false',  PRIMARY KEY (`id`)) ENGINE=MyISAM;"; 
mysql_select_db($database, $brewing);
mysql_real_escape_string($updateSQL);
$result = mysql_query($updateSQL, $brewing) or die(mysql_error()); 
$output .= $updateSQL."<br>";

$updateSQL = "INSERT INTO `styles_index` (`id`, `style_name`, `style_year`, `style_info`, `style_organization`, `style_db_name`, `style_owner`, `style_active`) VALUES (1, 'BJCP', 2008, 'Beer Judge Certification Program Style Guidelines for Beer, Mead and Cider 2008 Revision of the 2004 Guidelines.', 'Beer Judge Certification Program', 'styles_bjcp_2008', 'bcoe', 1), (2, 'Brewers Association', 2011, 'The Brewers Association beer style guidelines reflect, as much as possible, historical significance, nauthenticity or a high profile in the current commercial beer market.', 'Brewers Association', 'styles_ba_2011', 'bcoe', 0);";
mysql_select_db($database, $brewing);
mysql_real_escape_string($updateSQL);
$result = mysql_query($updateSQL, $brewing) or die(mysql_error()); 
$output .= $updateSQL."<br>";

$updateSQL = "RENAME TABLE `styles` TO `styles_bjcp_2008`;";
mysql_select_db($database, $brewing);
mysql_real_escape_string($updateSQL);
$result = mysql_query($updateSQL, $brewing) or die(mysql_error()); 
$output .= $updateSQL."<br>";

$updateSQL = "ALTER TABLE `styles_bjcp_2008` CHANGE `brewStyle` `style_name` varchar(255) NULL DEFAULT NULL, CHANGE `brewStyleGroup` `style_cat` varchar(255) NULL DEFAULT NULL, CHANGE `brewStyleNum` `style_subcat` varchar(255) NULL DEFAULT NULL, CHANGE `brewStyleOG` `style_og_min` float DEFAULT NULL, CHANGE `brewStyleOGMax` `style_og_max` float DEFAULT NULL, CHANGE `brewStyleFG` `style_fg_min` float DEFAULT NULL, CHANGE `brewStyleFGMax` `style_fg_max` float DEFAULT NULL, CHANGE `brewStyleABV` `style_abv_min` float DEFAULT NULL, CHANGE `brewStyleABVMax` `style_abv_max` float DEFAULT NULL, CHANGE `brewStyleIBU` `style_ibu_min` float DEFAULT NULL, CHANGE `brewStyleIBUMax` `style_ibu_max` float DEFAULT NULL, CHANGE `brewStyleSRM` `style_srm_min` float DEFAULT NULL, CHANGE `brewStyleSRMMax` `style_srm_max` float DEFAULT NULL, CHANGE `brewStyleType` `style_type` int(11) NULL DEFAULT NULL COMMENT 'relational to the style_types table', CHANGE `brewStyleInfo` `style_info` text, CHANGE `brewStyleActive` `style_active` char(1) NULL DEFAULT NULL, CHANGE `brewStyleOwn` `style_owner` varchar(255) NULL DEFAULT NULL, CHANGE `brewStyleLink` `style_link` varchar(255) NULL DEFAULT NULL, ADD `style_spec_ingred` tinyint(1) DEFAULT NULL, CHANGE `brewStyleJudgingLoc` `style_location` int(11) NULL"; 
mysql_select_db($database, $brewing);
mysql_real_escape_string($updateSQL);
$result = mysql_query($updateSQL, $brewing) or die(mysql_error()); 
$output .= $updateSQL."<br>";

$updateSQL = "ALTER TABLE  `styles_bjcp_2008` DROP  `style_owner`";
mysql_select_db($database, $brewing);
mysql_real_escape_string($updateSQL);
$result = mysql_query($updateSQL, $brewing) or die(mysql_error()); 
$output .= $updateSQL."<br>";

$updateSQL = "CREATE TABLE IF NOT EXISTS `styles_custom` (`id` int(11) NOT NULL AUTO_INCREMENT, `style_name` varchar(255) DEFAULT NULL, `style_cat` varchar(255) DEFAULT NULL, `style_subcat` varchar(255) DEFAULT NULL, `style_og_min` float DEFAULT NULL, `style_og_max` float DEFAULT NULL, `style_fg_min` float DEFAULT NULL, `style_fg_max` float DEFAULT NULL, `style_abv_min` float DEFAULT NULL, `style_abv_max` float DEFAULT NULL, `style_ibu_min` float DEFAULT NULL, `style_ibu_max` float DEFAULT NULL, `style_srm_min` float DEFAULT NULL, `style_srm_max` float DEFAULT NULL, `style_type` int(11) DEFAULT NULL COMMENT 'relational to the style_types table', `style_info` text, `style_link` varchar(255) DEFAULT NULL, `style_active` tinyint(1) DEFAULT '1', `style_location` int(11) NULL, `style_spec_ingred` tinyint(1) DEFAULT NULL, PRIMARY KEY (`id`)) ENGINE=MyISAM;";
mysql_select_db($database, $brewing);
mysql_real_escape_string($updateSQL);
$result = mysql_query($updateSQL, $brewing) or die(mysql_error()); 
$output .= $updateSQL."<br>";

$updateSQL = "CREATE TABLE IF NOT EXISTS `ba_2012` (`id` int(11) NOT NULL AUTO_INCREMENT, `style_name` varchar(255) DEFAULT NULL, `style_cat` varchar(255) DEFAULT NULL, `style_subcat` varchar(255) DEFAULT NULL, `style_og_min` float DEFAULT NULL, `style_og_max` float DEFAULT NULL, `style_fg_min` float DEFAULT NULL, `style_fg_max` float DEFAULT NULL, `style_abv_min` float DEFAULT NULL, `style_abv_max` float DEFAULT NULL, `style_ibu_min` float DEFAULT NULL, `style_ibu_max` float DEFAULT NULL, `style_srm_min` float DEFAULT NULL, `style_srm_max` float DEFAULT NULL, `style_type` int(11) DEFAULT NULL COMMENT 'relational to the style_types table', `style_info` text, `style_link` varchar(255) DEFAULT NULL, `style_active` tinyint(1) DEFAULT '1', `style_location` int(11) NULL, `style_spec_ingred` tinyint(1) DEFAULT NULL, PRIMARY KEY (`id`)) ENGINE=MyISAM;";
mysql_select_db($database, $brewing);
mysql_real_escape_string($updateSQL);
$result = mysql_query($updateSQL, $brewing) or die(mysql_error()); 
$output .= $updateSQL."<br>";
*/


?>
