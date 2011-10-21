-- Brew Competition Online Entry & Management
-- http://www.brewcompetition.com
-- Direct inquiries to prost@brewcompetition.com
-- Release 1.2.0.3 October 2011
-- This software is free, open source and is covered under the 
-- General Public License (GPL) from the Open Source Initiative.
-- As such, you are permitted to download the full source code of 
-- the software for your own use. Feel free to customize it for 
-- your own purposes.

-- --------------------------------------------------------

ALTER TABLE  `brewing` ADD  `brewJudgingNumber` VARCHAR( 10 ) NULL;
ALTER TABLE  `brewer` ADD  `brewerAssignmentStaff` CHAR( 1 ) NULL AFTER  `brewerAssignment`;
ALTER TABLE  `brewer` ADD  `brewerJudgeMead` CHAR( 1 ) NULL AFTER  `brewerJudgeID` ;
ALTER TABLE  `contest_info` ADD  `contestCircuit` TEXT NULL ;