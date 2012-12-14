-- Brew Competition Online Entry & Management
-- http://www.brewcompetition.com
-- Direct inquiries to prost@brewcompetition.com
-- Release 1.2.2.0 January 2013
-- This software is free, open source and is covered under the 
-- General Public License (GPL) from the Open Source Initiative.
-- As such, you are permitted to download the full source code of 
-- the software for your own use. Feel free to customize it for 
-- your own purposes.

-- --------------------------------------------------------

ALTER TABLE `preferences`  ADD `prefsUserEntryLimit`  INT(4) NULL DEFAULT NULL COMMENT 'Numeric limit of entries for each user'; 
ALTER TABLE `preferences`  ADD `prefsUserSubCatLimit` INT(4) NULL DEFAULT NULL COMMENT 'Numeric limit of entries for each user per subcategory';
ALTER TABLE `preferences`  ADD `prefsPayToPrint`  CHAR(1) NULL DEFAULT NULL COMMENT 'Do users need to pay before printing entry paperwork?';
ALTER TABLE `preferences`  ADD `prefsHideRecipe` CHAR(1) NULL DEFAULT NULL COMMENT 'Hide the recipe (optional) sections on the add/edit entry form?';
ALTER TABLE `preferences`  ADD `prefsUseMods` CHAR(1) NULL DEFAULT NULL COMMENT 'Use the custom modules function (advanced users)';

CREATE TABLE IF NOT EXISTS `mods` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `mod_name` varchar(255) DEFAULT NULL COMMENT 'Name of the custom module',
  `mod_type` tinyint(1) DEFAULT NULL COMMENT 'Type of module: 0=informational 1=report 2=export 3=other',
  `mod_extend_function` tinyint(1) DEFAULT NULL COMMENT 'If the custom module extends a core function. 0=none 1=home 2=rules 3=volunteer 4=sponsors 5=contact 6=register 7=pay 8=list 9=admin',
  `mod_extend_function_admin` varchar(255) DEFAULT NULL COMMENT 'If the custom module extends an admin function (9 in mod_extend_function). Keys off of the go= variable.',
  `mod_filename` varchar(255) DEFAULT NULL COMMENT 'File name of the custom module',
  `mod_description` varchar(255) DEFAULT NULL COMMENT 'Short description of the custom module',
  `mod_permission` tinyint(1) DEFAULT NULL COMMENT 'Who has permission to view the module. 0=uber-admin 1=admin 2=all',
  `mod_rank` int(3) DEFAULT NULL COMMENT 'Rank order of the mod on the admin mods list',
  `mod_display_rank` tinyint(1) DEFAULT NULL COMMENT '0=normal 1=above default content',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM ;