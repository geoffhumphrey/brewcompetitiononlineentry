-- Brew Contest Online Signup
-- http://www.brewcompetition.com
-- Direct inquiries to prost@brewcompetition.com
-- Release 1.1.3 April 2010
-- This software is free, open source and is covered under the 
-- General Public License (GPL) from the Open Source Initiative.
-- As such, you are permitted to download the full source code of 
-- the software for your own use. Feel free to customize it for 
-- your own purposes.

-- --------------------------------------------------------

ALTER TABLE `contest_info` 
DROP `contestDate`,
DROP `contestJudgingLocation`,
ADD `contestEntryFee2` VARCHAR( 255 ) NULL AFTER `contestEntryFee`,
ADD `contestEntryFeeDiscount` CHAR( 1 ) NULL AFTER `contestEntryFee2`,
ADD `contestEntryFeeDiscountNum` CHAR( 4 ) NULL AFTER `contestEntryFeeDiscount`,
ADD `contestAwardsLocName` VARCHAR( 255 ) NULL AFTER `contestAwardsLocation`,
ADD `contestAwardsLocDate` DATE NULL AFTER `contestAwardsLocName`,
ADD `contestAwardsLocTime` VARCHAR( 255 ) NULL AFTER `contestAwardsLocDate`;

UPDATE `contest_info` SET `contestEntryFeeDiscount` = 'N' WHERE `id` =1;

CREATE TABLE `judging` (
`id` INT( 8 ) NOT NULL AUTO_INCREMENT PRIMARY KEY,
`judgingDate` DATE NOT NULL,
`judgingTime` VARCHAR( 255 ) NOT NULL,
`judgingLocName` VARCHAR( 255 ) NOT NULL,
`judgingLocation` TEXT NOT NULL 
) ENGINE = MYISAM ;

ALTER TABLE `brewer` 
ADD `uid` INT (8) NULL AFTER `id`,
ADD `brewerJudgeLocation` TEXT NULL,
ADD `brewerJudgeLocation2` TEXT NULL,
ADD `brewerStewardLocation` TEXT NULL,
ADD `brewerStewardLocation2` TEXT NULL,
ADD `brewerJudgeAssignedLocation` INT( 8 ) NULL,
ADD `brewerStewardAssignedLocation` INT( 8 ) NULL,
ADD `brewerAssignment` CHAR( 1 ) NULL;

ALTER TABLE `styles` 
ADD `brewStyleActive` CHAR( 1 ) NULL DEFAULT 'Y',
ADD `brewStyleOwn` VARCHAR( 255 ) NULL DEFAULT 'bcoe',
ADD `brewStyleJudgingLoc` INT( 8 ) NULL;

ALTER TABLE `brewing` 
ADD `brewJudgingLocation` INT( 8 ) NULL;

CREATE TABLE `drop_off` (
`id` INT( 8 ) NOT NULL AUTO_INCREMENT PRIMARY KEY ,
`dropLocation` TEXT NULL ,
`dropLocationName` VARCHAR( 255 ) NULL ,
`dropLocationPhone` VARCHAR( 255 ) NULL,
`dropLocationWebsite` VARCHAR( 255 ) NULL
) ENGINE = MYISAM ;

ALTER TABLE `contest_info` CHANGE `contestDropOff` `contestShippingName` VARCHAR( 255 ) NULL;
UPDATE `contest_info` SET `contestShippingName` = '' WHERE `id` =1;

ALTER TABLE `preferences` ADD `prefsDisplaySpecial` CHAR( 1 ) NULL;