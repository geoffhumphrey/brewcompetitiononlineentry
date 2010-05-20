-- Brew Contest Online Signup
-- http://www.brewcompetition.com
-- Direct inquiries to prost@brewcompetition.com
-- Release 1.1.0 August 2009
-- This software is free, open source and is covered under the 
-- General Public License (GPL) from the Open Source Initiative.
-- As such, you are permitted to download the full source code of 
-- the software for your own use. Feel free to customize it for 
-- your own purposes.

-- --------------------------------------------------------

ALTER TABLE `brewing`
ADD `brewExtract1Use` VARCHAR(255) NULL, 
ADD `brewExtract2Use` VARCHAR(255) NULL, 
ADD `brewExtract3Use` VARCHAR(255) NULL, 
ADD `brewExtract4Use` VARCHAR(255) NULL, 
ADD `brewExtract5Use` VARCHAR(255) NULL,
ADD `brewGrain1Use` VARCHAR(255) NULL, 
ADD `brewGrain2Use` VARCHAR(255) NULL, 
ADD `brewGrain3Use` VARCHAR(255) NULL, 
ADD `brewGrain4Use` VARCHAR(255) NULL, 
ADD `brewGrain5Use` VARCHAR(255) NULL,
ADD `brewGrain6Use` VARCHAR(255) NULL, 
ADD `brewGrain7Use` VARCHAR(255) NULL, 
ADD `brewGrain8Use` VARCHAR(255) NULL, 
ADD `brewGrain9Use` VARCHAR(255) NULL,
ADD `brewAddition1Use` VARCHAR(255) NULL, 
ADD `brewAddition2Use` VARCHAR(255) NULL, 
ADD `brewAddition3Use` VARCHAR(255) NULL, 
ADD `brewAddition4Use` VARCHAR(255) NULL, 
ADD `brewAddition5Use` VARCHAR(255) NULL,
ADD `brewAddition6Use` VARCHAR(255) NULL, 
ADD `brewAddition7Use` VARCHAR(255) NULL, 
ADD `brewAddition8Use` VARCHAR(255) NULL, 
ADD `brewAddition9Use` VARCHAR(255) NULL,
ADD `brewPaid` CHAR( 1 ) NULL ,

ADD `brewWinner` CHAR( 1 ) NULL ,
ADD `brewWinnerCat` VARCHAR( 3 ) NULL ,
ADD `brewWinnerSubCat` VARCHAR( 3 ) NULL ,
ADD `brewWinnerPlace` VARCHAR( 3 ) NULL ,
ADD `brewBOSRound` CHAR( 1 ) NULL ,
ADD `brewBOSPlace` VARCHAR( 3 ) NULL ;

ALTER TABLE `preferences` 
ADD `prefsCash` CHAR( 1 ) NULL ,
ADD `prefsCheck` CHAR( 1 ) NULL ,
ADD `prefsCheckPayee` VARCHAR( 255 ) NULL,
ADD `prefsTransFee` CHAR( 1 ) NULL ,
ADD `prefsSponsors` CHAR( 1 ) NULL ,
ADD `prefsSponsorLogos` CHAR( 1 ) NULL ,
ADD `prefsSponsorLogoSize` VARCHAR( 255 ) NULL,
ADD `prefsCompLogoSize` VARCHAR( 255 ) NULL,
ADD `prefsDisplayWinners` CHAR( 1 ) NULL ;

UPDATE `preferences` 
SET 
`prefsCash` = 'Y',
`prefsCheck` = 'Y',
`prefsCheckPayee` = '',
`prefsTransFee` = 'N', 
`prefsSponsors`= 'Y',
`prefsSponsorLogos` = 'N',
`prefsSponsorLogoSize` = '175',
`prefsCompLogoSize` = '250',
`prefsDisplayWinners` = 'Y'
WHERE `id` = 1 LIMIT 1 ;

ALTER TABLE `contest_info` 
ADD `contestLogo` VARCHAR( 255 ) NULL, 
ADD `contestBOSAward` TEXT NULL;

 CREATE TABLE `sponsors` (
`id` INT( 8 ) NOT NULL AUTO_INCREMENT PRIMARY KEY ,
`sponsorName` VARCHAR( 255 ) NULL ,
`sponsorURL` VARCHAR( 255 ) NULL ,
`sponsorImage` VARCHAR( 255 ) NULL,
`sponsorText` TEXT NULL,
`sponsorLocation` TEXT NULL
) ENGINE = MYISAM;

ALTER TABLE `brewing` ADD `brewReceived` CHAR ( 1 ) NULL;

