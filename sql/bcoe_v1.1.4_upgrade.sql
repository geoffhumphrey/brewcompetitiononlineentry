-- Brew Contest Online Signup
-- http://www.brewcompetition.com
-- Direct inquiries to prost@brewcompetition.com
-- Release 1.1.4 June 2010
-- This software is free, open source and is covered under the 
-- General Public License (GPL) from the Open Source Initiative.
-- As such, you are permitted to download the full source code of 
-- the software for your own use. Feel free to customize it for 
-- your own purposes.

-- --------------------------------------------------------


ALTER TABLE `contest_info` 
ADD `contestRegistrationOpen` DATE NULL AFTER `contestHostLocation`,
ADD `contestEntryOpen` DATE NULL AFTER `contestRegistrationDeadline`;


ALTER TABLE `preferences` ADD `prefsBOSMead` CHAR( 1 ) NULL DEFAULT 'N',
ADD `prefsBOSCider` CHAR( 1 ) NULL DEFAULT 'N';
