-- Brew Competition Online Entry & Management
-- http://www.brewcompetition.com
-- Direct inquiries to prost@brewcompetition.com
-- Release 1.2.1 April 2012
-- This software is free, open source and is covered under the 
-- General Public License (GPL) from the Open Source Initiative.
-- As such, you are permitted to download the full source code of 
-- the software for your own use. Feel free to customize it for 
-- your own purposes.

-- --------------------------------------------------------

ALTER TABLE  `preferences` 
ADD  `prefsTimeZone` INT(11) NULL , 
ADD  `prefsEntryLimit` INT(11) NULL , 
ADD  `prefsTimeFormat` TINYINT(1) NULL,
ADD  `prefsGoogle` CHAR( 1 ) NULL DEFAULT NULL AFTER  `prefsTransFee` ,
ADD  `prefsGoogleAccount` INT( 20 ) NULL DEFAULT NULL COMMENT  'Google Merchant ID' AFTER  `prefsGoogle`,
ADD  `prefsWinnerDelay` INT( 11 ) NULL DEFAULT NULL COMMENT  'Hours after last judging date beginning time to delay displaying winners' AFTER `prefsDisplayWinners`,
ADD `prefsWinnerMethod` INT NULL DEFAULT NULL COMMENT 'Method comp uses to choose winners: 0=by table; 1=by category; 2=by sub-category' AFTER `prefsWinnerDelay` ;

UPDATE  `preferences` SET  `prefsTimeZone` =  '0', `prefsEntryLimit` =  '500', `prefsTimeFormat` =  '0', `prefsGoogle` = 'N' WHERE `id` = '1';

ALTER TABLE  `contest_info` 
CHANGE  `contestRegistrationOpen`  `contestRegistrationOpen` VARCHAR(255) NULL DEFAULT NULL ,
CHANGE  `contestRegistrationDeadline`  `contestRegistrationDeadline` VARCHAR(255) NULL DEFAULT NULL ,
CHANGE  `contestEntryOpen`  `contestEntryOpen` VARCHAR(255) NULL DEFAULT NULL ,
CHANGE  `contestEntryDeadline`  `contestEntryDeadline` VARCHAR(255) NULL DEFAULT NULL ,
ADD  `contestJudgeOpen` VARCHAR(255) NULL AFTER  `contestEntryDeadline` ,
ADD  `contestJudgeDeadline` VARCHAR(255) NULL AFTER  `contestJudgeOpen` ;

ALTER TABLE  `brewing` ADD  `brewConfirmed` TINYINT( 1 ) NULL COMMENT  '0 = false; 1 = true';

ALTER TABLE  `users` ADD  `userCreated` TIMESTAMP NULL DEFAULT NULL COMMENT 'Timestamp of when the user was created.';

ALTER TABLE  `judging_locations` CHANGE  `judgingDate` `judgingDate` VARCHAR( 255 ) NOT NULL ;

ALTER TABLE  `contest_info` ADD  `contestVolunteers` TEXT NULL ;

CREATE TABLE IF NOT EXISTS `special_best_info` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `sbi_name` varchar(255) DEFAULT NULL,
  `sbi_description` text,
  `sbi_places` int(11) DEFAULT NULL,
  `sbi_rank` int(11) DEFAULT NULL,
  `sbi_display_places` int(1) DEFAULT NULL,
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

ALTER TABLE  `brewing` CHANGE  `brewScore`  `brewUpdated` TIMESTAMP NULL DEFAULT NULL COMMENT  'Timestamp of when the entry was last updated.';

CREATE TABLE IF NOT EXISTS `system` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `version` varchar(12) DEFAULT NULL,
  `version_date` date DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM;

INSERT INTO `system` (`id`, `version`, `version_date`) VALUES (1, '1.2.1', '2012-08-01');