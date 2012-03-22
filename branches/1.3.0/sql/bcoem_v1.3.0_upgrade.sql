-- Brew Competition Online Entry & Management (BCOE&M)
-- http://www.brewcompetition.com
-- Direct inquiries to prost@brewcompetition.com
-- Release 1.3.0
-- This software is free, open source and is covered under the 
-- General Public License (GPL) from the Open Source Initiative.
-- As such, you are permitted to download the full source code of the software 
-- for your own use. Feel free to customize it for your own purposes.

-- 1.3.0 presents an overhaul of the MySQL table structure for many tables:
--  * Converting 'Yes/No' table rows to boolean values (0 = false; 1 = true)
--  * Standardizing row names used in multiple tables (i.e. brewBrewerID to bid)
--    ** uid = user id (relational to users table)
--    ** bid = brewer id (relational to brewer table)
--    ** eid = entry id (relational to brewing table)
--    ** 
--  * Removing redundant and unused table rows throughout
--  * Expanding styles to allow for use of other/custom style guideline sets

-- --------------------------------------------------------
-- Create system table to house version, etc.

CREATE TABLE IF NOT EXISTS `system` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `version` varchar(12) DEFAULT NULL,
  `version_date` date DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM;

INSERT INTO `system` (`id`, `version`) VALUES (1, '1.3.0', '2012-05-01');

-- --------------------------------------------------------
-- Add date user created and last access rows to users table

ALTER TABLE  `users` ADD  `userCreated` date NULL, ADD  `userLastAccess` timestamp NULL DEFAULT CURRENT_TIMESTAMP;

-- --------------------------------------------------------
-- New tables to house custom 'best of' categories. 

CREATE TABLE IF NOT EXISTS `special_best_info` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `sbi_name` varchar(255) DEFAULT NULL,
  `sbi_description` text,
  `sbi_places` int(11) DEFAULT NULL,
  `sbi_rank` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM ;

CREATE TABLE IF NOT EXISTS `special_best_data` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `sid` int(11) DEFAULT NULL COMMENT 'relational to special_best_info table',
  `bid` int(11) DEFAULT NULL COMMENT 'relational to brewer table - bid row',
  `eid` int(11) DEFAULT NULL COMMENT 'relational to brewing table - id (entry number)',
  `sbd_place` int(11) DEFAULT NULL,
  `sbd_comments` text,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM ;

-- --------------------------------------------------------
-- Changes to preferences table to accomodate social media widgets.

ALTER TABLE  `preferences` ADD  `prefsShare` tinyint(1) NULL, ADD  `prefsSocial` TEXT DEFAULT NULL;
UPDATE  `preferences` SET  `prefsShare` =  '0' WHERE `id` =1;

-- --------------------------------------------------------
-- Changes to brewer table. 
-- Keeping CHAR attribute for this version.
-- Will switch to INT in 1.3.0 version.

ALTER TABLE  `brewer` CHANGE  `brewerAssignment`  `brewerAssignmentJudge`tinyint(1) NULL DEFAULT NULL COMMENT  '1 for true; 0 for false',
CHANGE  `brewerAssignmentStaff`  `brewerAssignmentStaff` tinyint(1) NULL DEFAULT NULL COMMENT  '1 for true; 0 for false',
ADD  `brewerAssignmentSteward` tinyint(1) NULL DEFAULT NULL COMMENT  '1 for true; 0 for false' AFTER  `brewerAssignmentJudge`,
ADD  `brewerAssignmentOrganizer` tinyint(1) NULL DEFAULT NULL COMMENT  '1 for true; 0 for false' AFTER  `brewerAssignmentStaff`,
CHANGE  `brewerJudgeBOS`  `brewerJudgeBOS` tinyint(1) NULL DEFAULT NULL COMMENT  '1 for true; 0 for false';

-- --------------------------------------------------------

-- Add master styles index table
-- Allows for users to add their own style sets

CREATE TABLE IF NOT EXISTS `styles_index` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `style_name` varchar(255) DEFAULT NULL,
  `style_year` int(4) DEFAULT NULL,
  `style_info` text,
  `style_organization` varchar(255) DEFAULT NULL,
  `style_db_name` varchar(255) DEFAULT NULL,
  `style_active` tinyint(1) DEFAULT NULL COMMENT  '1 for true; 0 for false',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM ;

-- Add BCOE&M default style sets

INSERT INTO `styles_index` (`id`, `style_name`, `style_year`, `style_info`, `style_organization`, `style_db_name`, `style_active`) VALUES
(1, 'BJCP', 2008, 'Beer Judge Certification Program (BJCP) Style Guidelines for Beer, Mead and Cider 2008 Revision of the 2004 Guidelines.', 'Beer Judge Certification Program (BJCP)', 'styles_bjcp_2008', 1),
(2, 'Brewers Association', 2011, 'The Brewers Association beer style guidelines reflect, as much as possible, historical significance, nauthenticity or a high profile in the current commercial beer market.', 'Brewers Association', 'styles_ba_2011', 0);

-- Change BJCP 2008 styles table name

RENAME TABLE `styles` TO `styles_bjcp_2008`;

-- Rename table rows in styles_bjcp_2008 to be consistent with other style tables

ALTER TABLE `styles_bjcp_2008` CHANGE `brewStyle` `style_name` varchar(255) NULL DEFAULT NULL, CHANGE `style_cat` `style_cat` varchar(255) NULL DEFAULT NULL,
CHANGE `style_subcat` `style_subcat` varchar(255) NULL DEFAULT NULL,
CHANGE `style_og_min` `style_og_min` float DEFAULT NULL,
CHANGE `style_og_max` `style_og_max` float DEFAULT NULL,
CHANGE `style_fg_min` `style_fg_min` float DEFAULT NULL,
CHANGE `style_fg_max` `style_fg_max` float DEFAULT NULL,
CHANGE `style_abv_min` `style_abv_min` float DEFAULT NULL,
CHANGE `style_abv_max` `style_abv_max` float DEFAULT NULL,
CHANGE `style_ibu_min` `style_ibu_min` float DEFAULT NULL,
CHANGE `style_ibu_max` `style_ibu_max` float DEFAULT NULL,
CHANGE `style_srm_min` `style_srm_min` float DEFAULT NULL,
CHANGE `style_srm_max` `style_srm_max` float DEFAULT NULL,
CHANGE `style_type` `style_type` char(1) NULL DEFAULT NULL COMMENT 'relational to the style_types table',
CHANGE `style_info` `style_info` text, CHANGE `style_active` `style_active` char(1) NULL DEFAULT NULL,
CHANGE `style_owner` `style_owner` varchar(255) NULL DEFAULT NULL,
CHANGE `style_link` `style_link` varchar(255) NULL DEFAULT NULL,
DROP `brewStyleJudgingLoc`;


-- Add Custom Styles

CREATE TABLE IF NOT EXISTS `styles_custom` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `style_name` varchar(255) DEFAULT NULL,
  `style_cat` varchar(255) DEFAULT NULL,
  `style_subcat` varchar(255) DEFAULT NULL,
  `style_og_min` float DEFAULT NULL,
  `style_og_max` float DEFAULT NULL,
  `style_fg_min` float DEFAULT NULL,
  `style_fg_max` float DEFAULT NULL,
  `style_abv_min` float DEFAULT NULL,
  `style_abv_max` float DEFAULT NULL,
  `style_ibu_min` float DEFAULT NULL,
  `style_ibu_max` float DEFAULT NULL,
  `style_srm_min` float DEFAULT NULL,
  `style_srm_max` float DEFAULT NULL,
  `style_type` tinyint(1) DEFAULT NULL COMMENT 'relational to the style_types table',
  `style_info` text,
  `style_link` varchar(255) DEFAULT NULL,
  `style_active` tinyint(1) DEFAULT '1',
  `style_owner` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM;

-- Add Brewer's Association Styles Table

CREATE TABLE IF NOT EXISTS `styles_ba_2011` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `style_name` varchar(255) DEFAULT NULL,
  `style_cat` varchar(255) DEFAULT NULL,
  `style_subcat` varchar(255) DEFAULT NULL,
  `style_og_min` float DEFAULT NULL,
  `style_og_max` float DEFAULT NULL,
  `style_fg_min` float DEFAULT NULL,
  `style_fg_max` float DEFAULT NULL,
  `style_abv_min` float DEFAULT NULL,
  `style_abv_max` float DEFAULT NULL,
  `style_ibu_min` float DEFAULT NULL,
  `style_ibu_max` float DEFAULT NULL,
  `style_srm_min` float DEFAULT NULL,
  `style_srm_max` float DEFAULT NULL,
  `style_type` tinyint(1) DEFAULT NULL COMMENT 'relational to the style_types table',
  `style_info` text,
  `style_link` varchar(255) DEFAULT NULL,
  `style_active` tinyint(1) DEFAULT '1',
  `style_owner` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM;

-- NEEDED!!!
-- Insert Brewer's Association Style Guidelines 



-- --------------------------------------------------------
