ALTER TABLE  `brewing` ADD  `brewJudgingNumber` VARCHAR( 10 ) NULL;
ALTER TABLE  `brewer` ADD  `brewerAssignmentStaff` CHAR( 1 ) NULL AFTER  `brewerAssignment`;
ALTER TABLE  `brewer` ADD  `brewerJudgeMead` CHAR( 1 ) NULL AFTER  `brewerJudgeID` ;
ALTER TABLE  `contest_info` ADD  `contestCircuit` TEXT NULL ;