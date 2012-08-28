<?php
// -----------------------------------------------------------
// Create Table: system
//   Table to house system data.
// -----------------------------------------------------------

$updateSQL = "CREATE TABLE IF NOT EXISTS `".$prefix."system` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `version` varchar(12) DEFAULT NULL,
  `version_date` date DEFAULT NULL,
  `data_check` varchar(255) DEFAULT NULL COMMENT 'Date/time of the last data integrity check.',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM";
mysql_select_db($database, $brewing);
$result = mysql_query($updateSQL, $brewing) or die(mysql_error()); 
//echo $updateSQL."<br>";

$updateSQL = "INSERT INTO `".$prefix."system` (`id`, `version`, `version_date`,`data_check`) VALUES (1, '1.2.1.0', '2012-09-01', NOW( ));";
mysql_select_db($database, $brewing);
$result = mysql_query($updateSQL, $brewing) or die(mysql_error()); 
//echo $updateSQL."<br>";
echo "<ul><li>System table created.</li></ul>";

// -----------------------------------------------------------
// Create Tables: special_best_info, special_best_data
//  Tables to house custom "best of" categories and data.
// -----------------------------------------------------------

$updateSQL = "
CREATE TABLE IF NOT EXISTS `".$prefix."special_best_info` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `sbi_name` varchar(255) DEFAULT NULL,
  `sbi_description` text,
  `sbi_places` int(11) DEFAULT NULL,
  `sbi_rank` int(11) DEFAULT NULL,
  `sbi_display_places` tinyint(1) DEFAULT NULL COMMENT '1=true; 0=false',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM ;

"; 
mysql_select_db($database, $brewing);
$result = mysql_query($updateSQL, $brewing) or die(mysql_error()); 
//echo $updateSQL."<br>";

$updateSQL = "
CREATE TABLE IF NOT EXISTS `".$prefix."special_best_data` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `sid` int(11) DEFAULT NULL COMMENT 'relational to special_best_info table',
  `bid` int(11) DEFAULT NULL COMMENT 'relational to brewer table - bid row',
  `eid` int(11) DEFAULT NULL COMMENT 'relational to brewing table - id (entry number)',
  `sbd_place` int(11) DEFAULT NULL,
  `sbd_comments` text,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM ;
";
mysql_select_db($database, $brewing);
$result = mysql_query($updateSQL, $brewing) or die(mysql_error()); 
//echo $updateSQL."<br>";

echo "<ul><li>Custom &ldquo;best of&rdquo; tables created.</li></ul>";


/*

Save for next version.

// -----------------------------------------------------------
// Create Tables: Styles Index, BJCP 2008, BA 2012
//  Add new tables to accomodate more style sets other than
//  BJCP 2008.
// -----------------------------------------------------------

$updateSQL = "CREATE TABLE IF NOT EXISTS `styles_index` (`id` int(11) NOT NULL AUTO_INCREMENT,`style_name` varchar(255) DEFAULT NULL, `style_year` int(4) DEFAULT NULL, `style_info` text,`style_organization` varchar(255) DEFAULT NULL, `style_db_name` varchar(255) DEFAULT NULL, `style_owner` varchar(255) DEFAULT NULL,`style_active` tinyint(1) DEFAULT NULL COMMENT '1 for true; 0 for false',  PRIMARY KEY (`id`)) ENGINE=MyISAM;"; 
mysql_select_db($database, $brewing);
//$result = mysql_query($updateSQL, $brewing) or die(mysql_error()); 
echo $updateSQL."<br>";

$updateSQL = "INSERT INTO `styles_index` (`id`, `style_name`, `style_year`, `style_info`, `style_organization`, `style_db_name`, `style_owner`, `style_active`) VALUES (1, 'BJCP', 2008, 'Beer Judge Certification Program Style Guidelines for Beer, Mead and Cider 2008 Revision of the 2004 Guidelines.', 'Beer Judge Certification Program', 'styles_bjcp_2008', 'bcoe', 1), (2, 'Brewers Association', 2011, 'The Brewers Association beer style guidelines reflect, as much as possible, historical significance, nauthenticity or a high profile in the current commercial beer market.', 'Brewers Association', 'styles_ba_2011', 'bcoe', 0);";
mysql_select_db($database, $brewing);
//$result = mysql_query($updateSQL, $brewing) or die(mysql_error()); 
echo $updateSQL."<br>";

$updateSQL = "RENAME TABLE `styles` TO `styles_bjcp_2008`;";
mysql_select_db($database, $brewing);
//$result = mysql_query($updateSQL, $brewing) or die(mysql_error()); 
echo $updateSQL."<br>";

$updateSQL = "ALTER TABLE `styles_bjcp_2008` CHANGE `brewStyle` `style_name` varchar(255) NULL DEFAULT NULL, CHANGE `brewStyleGroup` `style_cat` varchar(255) NULL DEFAULT NULL, CHANGE `brewStyleNum` `style_subcat` varchar(255) NULL DEFAULT NULL, CHANGE `brewStyleOG` `style_og_min` float DEFAULT NULL, CHANGE `brewStyleOGMax` `style_og_max` float DEFAULT NULL, CHANGE `brewStyleFG` `style_fg_min` float DEFAULT NULL, CHANGE `brewStyleFGMax` `style_fg_max` float DEFAULT NULL, CHANGE `brewStyleABV` `style_abv_min` float DEFAULT NULL, CHANGE `brewStyleABVMax` `style_abv_max` float DEFAULT NULL, CHANGE `brewStyleIBU` `style_ibu_min` float DEFAULT NULL, CHANGE `brewStyleIBUMax` `style_ibu_max` float DEFAULT NULL, CHANGE `brewStyleSRM` `style_srm_min` float DEFAULT NULL, CHANGE `brewStyleSRMMax` `style_srm_max` float DEFAULT NULL, CHANGE `brewStyleType` `style_type` int(11) NULL DEFAULT NULL COMMENT 'relational to the style_types table', CHANGE `brewStyleInfo` `style_info` text, CHANGE `brewStyleActive` `style_active` char(1) NULL DEFAULT NULL, CHANGE `brewStyleOwn` `style_owner` varchar(255) NULL DEFAULT NULL, CHANGE `brewStyleLink` `style_link` varchar(255) NULL DEFAULT NULL, ADD `style_spec_ingred` tinyint(1) DEFAULT NULL, CHANGE `brewStyleJudgingLoc` `style_location` int(11) NULL"; 
mysql_select_db($database, $brewing);
//$result = mysql_query($updateSQL, $brewing) or die(mysql_error()); 
echo $updateSQL."<br>";

$updateSQL = "ALTER TABLE  `styles_bjcp_2008` DROP  `style_owner`";
mysql_select_db($database, $brewing);
//$result = mysql_query($updateSQL, $brewing) or die(mysql_error()); 
echo $updateSQL."<br>";

$updateSQL = "CREATE TABLE IF NOT EXISTS `styles_custom` (`id` int(11) NOT NULL AUTO_INCREMENT, `style_name` varchar(255) DEFAULT NULL, `style_cat` varchar(255) DEFAULT NULL, `style_subcat` varchar(255) DEFAULT NULL, `style_og_min` float DEFAULT NULL, `style_og_max` float DEFAULT NULL, `style_fg_min` float DEFAULT NULL, `style_fg_max` float DEFAULT NULL, `style_abv_min` float DEFAULT NULL, `style_abv_max` float DEFAULT NULL, `style_ibu_min` float DEFAULT NULL, `style_ibu_max` float DEFAULT NULL, `style_srm_min` float DEFAULT NULL, `style_srm_max` float DEFAULT NULL, `style_type` int(11) DEFAULT NULL COMMENT 'relational to the style_types table', `style_info` text, `style_link` varchar(255) DEFAULT NULL, `style_active` tinyint(1) DEFAULT '1', `style_location` int(11) NULL, `style_spec_ingred` tinyint(1) DEFAULT NULL, PRIMARY KEY (`id`)) ENGINE=MyISAM;";
mysql_select_db($database, $brewing);
//$result = mysql_query($updateSQL, $brewing) or die(mysql_error()); 
echo $updateSQL."<br>";


$updateSQL = "CREATE TABLE IF NOT EXISTS `ba_2012` (`id` int(11) NOT NULL AUTO_INCREMENT, `style_name` varchar(255) DEFAULT NULL, `style_cat` varchar(255) DEFAULT NULL, `style_subcat` varchar(255) DEFAULT NULL, `style_og_min` float DEFAULT NULL, `style_og_max` float DEFAULT NULL, `style_fg_min` float DEFAULT NULL, `style_fg_max` float DEFAULT NULL, `style_abv_min` float DEFAULT NULL, `style_abv_max` float DEFAULT NULL, `style_ibu_min` float DEFAULT NULL, `style_ibu_max` float DEFAULT NULL, `style_srm_min` float DEFAULT NULL, `style_srm_max` float DEFAULT NULL, `style_type` int(11) DEFAULT NULL COMMENT 'relational to the style_types table', `style_info` text, `style_link` varchar(255) DEFAULT NULL, `style_active` tinyint(1) DEFAULT '1', `style_location` int(11) NULL, `style_spec_ingred` tinyint(1) DEFAULT NULL, PRIMARY KEY (`id`)) ENGINE=MyISAM;";
mysql_select_db($database, $brewing);
//$result = mysql_query($updateSQL, $brewing) or die(mysql_error()); 
echo $updateSQL."<br>";
*/


?>
