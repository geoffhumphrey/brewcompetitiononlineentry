CREATE TABLE IF NOT EXISTS `judging_preferences` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `jPrefsQueued` char(1) DEFAULT NULL COMMENT 'Whether to use the Queued Judging technique from AHA',
  `jPrefsFlightEntries` int(11) DEFAULT NULL COMMENT 'Maximum amount of entries per flight',
  `jPrefsBOSMethod` INT(11) NULL DEFAULT NULL COMMENT '1= 1st place only, 2 = 1 and 2 places, 3=1,2,3 places',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM ;

INSERT INTO `judging_preferences` (
`id` ,
`jPrefsQueued` ,
`jPrefsFlightEntries` ,
`jPrefsBOSMethod`
)
VALUES ('1' , 'N', '12', '1');

CREATE TABLE IF NOT EXISTS `judging_tables` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `tableName` varchar(255) DEFAULT NULL COMMENT 'Name of table that will judge the prescribed categories',
  `tableStyles` varchar(255) DEFAULT NULL COMMENT 'Array of ids from styles table',
  `tableNumber` int(11) DEFAULT NULL COMMENT 'User defined for sorting',
  `tableRound` int(11) DEFAULT NULL COMMENT 'User defined based upon jPrefsRounds judging_preferences table row',
  `tableLocation` int(11) DEFAULT NULL COMMENT 'Physical location of table (if more than one judging location) - relational to judging table',
  `tableJudges` VARCHAR(255) NULL COMMENT 'Array of ids from brewer table',
  `tableStewards` VARCHAR(255) NULL COMMENT 'Array of ids from brewer table',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM ;

CREATE TABLE IF NOT EXISTS `judging_flights` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `flightTable` int(11) DEFAULT NULL COMMENT 'id of Table from tables',
  `flightNumber` int(11) DEFAULT NULL,
  `flightEntries` varchar(255) DEFAULT NULL,
  `flightRound` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM ;

ALTER TABLE `brewing` ADD `brewScore` INT( 8 ) NULL ;

CREATE TABLE `judging_scores` (
`id` INT(11) NOT NULL AUTO_INCREMENT ,
`eid` INT(11) NULL COMMENT 'entry id from brewing table',
`bid` INT(11) NULL COMMENT 'brewer id from brewer table',
`scoreTable` INT(11) NULL COMMENT 'id of table from judging_tables table',
`scoreEntry` INT(11) NULL COMMENT 'numerical score assigned by judges',
`scorePlace` INT(11) NULL COMMENT 'place of entry as assigned by judges',
PRIMARY KEY (`id`)
) ENGINE = MYISAM ;