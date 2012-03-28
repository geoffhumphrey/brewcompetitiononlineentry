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
ADD  `prefsTimeZone` FLOAT( 11 ) NULL , 
ADD  `prefsEntryLimit` INT( 11 ) NULL , 
ADD  `prefsTimeFormat` TINYINT( 1 ) NULL ;
UPDATE  `preferences` SET  `prefsTimeZone` =  '0', `prefsEntryLimit` =  '500', `prefsTimeFormat` =  '0' WHERE `id` = '1';

ALTER TABLE  `contest_info` 
CHANGE  `contestRegistrationOpen`  `contestRegistrationOpen` DATETIME NULL DEFAULT NULL ,
CHANGE  `contestRegistrationDeadline`  `contestRegistrationDeadline` DATETIME NULL DEFAULT NULL ,
CHANGE  `contestEntryOpen`  `contestEntryOpen` DATETIME NULL DEFAULT NULL ,
CHANGE  `contestEntryDeadline`  `contestEntryDeadline` DATETIME NULL DEFAULT NULL ;