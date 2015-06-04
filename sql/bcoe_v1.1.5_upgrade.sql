-- Brew Contest Online Signup
-- http://www.brewcompetition.com
-- Direct inquiries to prost@brewcompetition.com
-- Release 1.1.5 Jul 2010
-- This software is free, open source and is covered under the 
-- General Public License (GPL) from the Open Source Initiative.
-- As such, you are permitted to download the full source code of 
-- the software for your own use. Feel free to customize it for 
-- your own purposes.

-- --------------------------------------------------------

ALTER TABLE `sponsors` ADD `sponsorLevel` TINYINT( 1 ) NULL;

CREATE TABLE `contacts` (
`id` INT( 8 ) NOT NULL AUTO_INCREMENT PRIMARY KEY ,
`contactFirstName` VARCHAR( 255 ) NULL ,
`contactLastName` VARCHAR( 255 ) NULL ,
`contactPosition` VARCHAR( 255 ) NULL ,
`contactEmail` VARCHAR( 255 ) NULL
) ENGINE = MYISAM ;

ALTER TABLE `drop_off` ADD `dropLocationNotes` VARCHAR( 255 ) NULL;

ALTER TABLE `preferences` ADD `prefsEntryForm` CHAR( 1 ) NULL ;
UPDATE `preferences` SET `prefsEntryForm` = 'B' WHERE `id` =1 ;