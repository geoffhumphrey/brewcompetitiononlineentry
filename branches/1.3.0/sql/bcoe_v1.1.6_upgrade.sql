ALTER TABLE `brewer` 
CHANGE `brewerJudgeAssignedLocation` `brewerJudgeAssignedLocation` VARCHAR( 255 ) NULL DEFAULT NULL ,
CHANGE `brewerStewardAssignedLocation` `brewerStewardAssignedLocation` VARCHAR( 255 ) NULL DEFAULT NULL ,
CHANGE `brewerJudgeLocation` `brewerJudgeLocation` VARCHAR( 255 ) NULL DEFAULT NULL ,
CHANGE `brewerStewardLocation` `brewerStewardLocation` VARCHAR( 255 ) NULL DEFAULT NULL ;

ALTER TABLE `preferences` ADD `prefsRecordLimit` INT( 11 ) NULL DEFAULT '300' COMMENT 'User defined record limit for using DataTables vs. PHP paging';
ALTER TABLE `preferences` ADD `prefsRecordPaging` INT( 11 ) NULL DEFAULT '30' COMMENT 'User defined per page record limit';
UPDATE `preferences` SET `prefsRecordLimit` = '300', `prefsRecordPaging` = '30' WHERE `id` = 1;

-- ***************************** Future Release ***************************** --
-- ALTER TABLE `preferences` ADD `prefsGoogle` CHAR( 1 ) NULL DEFAULT 'N' ,
-- ADD `prefsGoogleMerchantID` VARCHAR( 255 ) NULL ;
-- ************************************************************************** --

ALTER TABLE `brewer` ADD `brewerAHA` INT( 11 ) NULL ;
ALTER TABLE `brewing` CHANGE `brewPaid` `brewPaid` CHAR( 1 ) NULL DEFAULT 'N';
ALTER TABLE `brewing` ADD `brewCoBrewer` VARCHAR( 255 ) NULL ;

