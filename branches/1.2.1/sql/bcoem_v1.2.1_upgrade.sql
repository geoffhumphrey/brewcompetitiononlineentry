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
ADD  `prefsWinnerDelay` INT( 11 ) NULL DEFAULT NULL COMMENT  'Hours after last judging date beginning time to delay displaying winners' AFTER `prefsDisplayWinners`;

UPDATE  `preferences` SET  `prefsTimeZone` =  '0', `prefsEntryLimit` =  '500', `prefsTimeFormat` =  '0', `prefsGoogle` = 'N' WHERE `id` = '1';

ALTER TABLE  `contest_info` 
CHANGE  `contestRegistrationOpen`  `contestRegistrationOpen` VARCHAR(255) NULL DEFAULT NULL ,
CHANGE  `contestRegistrationDeadline`  `contestRegistrationDeadline` VARCHAR(255) NULL DEFAULT NULL ,
CHANGE  `contestEntryOpen`  `contestEntryOpen` VARCHAR(255) NULL DEFAULT NULL ,
CHANGE  `contestEntryDeadline`  `contestEntryDeadline` VARCHAR(255) NULL DEFAULT NULL ,
ADD  `contestJudgeOpen` VARCHAR(255) NULL AFTER  `contestEntryDeadline` ,
ADD  `contestJudgeDeadline` VARCHAR(255) NULL AFTER  `contestJudgeOpen ;

ALTER TABLE  `brewing` CHANGE  `brewScore`  `brewUpdated` TIMESTAMP NULL DEFAULT NULL COMMENT 'Timestamp of when the entry was last updated.';
ALTER TABLE  `users` ADD  `userCreated` TIMESTAMP NULL DEFAULT NULL COMMENT 'Timestamp of when the user was created.';

ALTER TABLE  `judging_locations` CHANGE  `judgingDate`  `judgingDate` VARCHAR( 255 ) NOT NULL ;