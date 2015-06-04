-- Brew Contest Online Signup
-- http://www.brewcompetition.com
-- Direct inquiries to prost@brewcompetition.com
-- Release 1.1.1 December 2009
-- This software is free, open source and is covered under the 
-- General Public License (GPL) from the Open Source Initiative.
-- As such, you are permitted to download the full source code of 
-- the software for your own use. Feel free to customize it for 
-- your own purposes.

-- --------------------------------------------------------

ALTER TABLE `brewing` CHANGE `id` `id` INT( 8 ) NOT NULL AUTO_INCREMENT;
ALTER TABLE `brewer` CHANGE `id` `id` INT( 8 ) NOT NULL AUTO_INCREMENT;
ALTER TABLE `styles` CHANGE `id` `id` INT( 8 ) NOT NULL AUTO_INCREMENT;
ALTER TABLE `preferences` CHANGE `id` `id` INT( 1 ) NOT NULL AUTO_INCREMENT;
ALTER TABLE `contest_info` ADD `contestWinnersComplete` TEXT NULL ;