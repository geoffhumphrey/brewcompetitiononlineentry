CREATE TABLE IF NOT EXISTS `judging_preferences` (
  `id` int(8) NOT NULL AUTO_INCREMENT,
  `jPrefsQueued` char(1) DEFAULT NULL COMMENT 'Whether to use the Queued Judging technique from AHA',
  `jPrefsFlightEntries` int(8) DEFAULT NULL COMMENT 'Maximum amount of entries per flight',
  `jPrefsFlightJudges` int(8) DEFAULT NULL COMMENT 'Maximum amount judges per flight',
  `jPrefsRounds` int(8) DEFAULT NULL COMMENT 'Number of judging rounds',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM ;

CREATE TABLE IF NOT EXISTS `judging_tables` (
  `id` int(8) NOT NULL AUTO_INCREMENT,
  `tableName` varchar(255) DEFAULT NULL COMMENT 'Name of table that will judge the prescribed categories',
  `tableStyles` varchar(255) DEFAULT NULL COMMENT 'Array of ids from styles table',
  `tableNumber` int(5) DEFAULT NULL COMMENT 'User defined for sorting',
  `tableRound` int(5) DEFAULT NULL COMMENT 'User defined based upon jPrefsRounds judging_preferences table row',
  `tableLocation` int(5) DEFAULT NULL COMMENT 'Physical location of table (if more than one judging location) - relational to judging table',
  `tableJudges` VARCHAR( 255 ) NULL COMMENT 'Array of ids from brewer table',
  `tableStewards` VARCHAR( 255 ) NULL COMMENT 'Array of ids from brewer table',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM ;

CREATE TABLE IF NOT EXISTS `judging_flights` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `flightTable` int(8) DEFAULT NULL COMMENT 'id of Table from tables',
  `flightNumber` int(8) DEFAULT NULL,
  `flightEntries` varchar(255) DEFAULT NULL,
  `flightRound` int(8) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM ;

ALTER TABLE `brewing` ADD `brewScore` INT( 8 ) NULL ;