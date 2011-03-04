RENAME TABLE `judging` TO `judging_locations`;

CREATE TABLE IF NOT EXISTS `judging_preferences` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `jPrefsQueued` char(1) DEFAULT NULL COMMENT 'Whether to use the Queued Judging technique from AHA',
  `jPrefsFlightEntries` int(11) DEFAULT NULL COMMENT 'Maximum amount of entries per flight',
  `jPrefsMaxBOS` INT(11) NULL DEFAULT NULL COMMENT 'Maximum amount of places awarded for each BOS style type',
  `jPrefsRounds` INT(11) NULL DEFAULT NULL COMMENT 'Maximum amount of rounds per judging location',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM ;

INSERT INTO `judging_preferences` (
`id` ,
`jPrefsQueued` ,
`jPrefsFlightEntries` ,
`jPrefsMaxBOS`,
`jPrefsRounds`
)
VALUES ('1' , 'N', '12', '7', '3');

CREATE TABLE IF NOT EXISTS `judging_tables` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `tableName` varchar(255) DEFAULT NULL COMMENT 'Name of table that will judge the prescribed categories',
  `tableStyles` TEXT DEFAULT NULL COMMENT 'Array of ids from styles table',
  `tableNumber` int(11) DEFAULT NULL COMMENT 'User defined for sorting',
  `tableLocation` int(11) DEFAULT NULL COMMENT 'Physical location of table (if more than one judging location) - relational to judging table',
  `tableJudges` VARCHAR(255) NULL COMMENT 'Array of ids from brewer table',
  `tableStewards` VARCHAR(255) NULL COMMENT 'Array of ids from brewer table',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM ;

CREATE TABLE IF NOT EXISTS `judging_flights` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `flightTable` int(11) DEFAULT NULL COMMENT 'id of Table from tables',
  `flightNumber` int(11) DEFAULT NULL,
  `flightEntryID` TEXT NULL DEFAULT NULL COMMENT 'array of ids of each entry from the brewing table',
  `flightRound` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM ;

CREATE TABLE `judging_scores` (
`id` INT(11) NOT NULL AUTO_INCREMENT ,
`eid` INT(11) NULL COMMENT 'entry id from brewing table',
`bid` INT(11) NULL COMMENT 'brewer id from brewer table',
`scoreTable` INT(11) NULL COMMENT 'id of table from judging_tables table',
`scoreEntry` INT(11) NULL COMMENT 'numerical score assigned by judges',
`scorePlace` INT(11) NULL COMMENT 'place of entry as assigned by judges',
`scoreType` CHAR(1) NULL COMMENT 'type of entry used for custom styles: B=beer, C=cider, M=mead, S=Soda, O=other',
PRIMARY KEY (`id`)
) ENGINE = MYISAM;

CREATE TABLE `judging_scores_bos` (
`id` INT(11) NOT NULL AUTO_INCREMENT ,
`eid` INT(11) NULL COMMENT 'entry id from brewing table',
`bid` INT(11) NULL COMMENT 'brewer id from brewer table',
`scoreEntry` INT(11) NULL COMMENT 'numerical score assigned by judges',
`scorePlace` INT(11) NULL COMMENT 'place of entry as assigned by judges',
`scoreType` CHAR(1) NULL COMMENT 'type of entry used for custom styles: B=beer, C=cider, M=mead, S=Soda, O=other',
PRIMARY KEY (`id`)
) ENGINE = MYISAM;

CREATE TABLE `style_types` (
`id` INT( 11 ) NOT NULL AUTO_INCREMENT,
`styleTypeName` VARCHAR( 255 ) NULL,
`styleTypeOwn` VARCHAR( 255 ) NULL,
`styleTypeBOS` CHAR( 1 ) NULL,
`styleTypeBOSMethod` INT( 11 ) NULL,
PRIMARY KEY (`id`)
) ENGINE = MYISAM ;

INSERT INTO `style_types` (
`id` ,
`styleTypeName`,
`styleTypeOwn`,
`styleTypeBOS`,
`styleTypeBOSMethod`
)
VALUES ('1', 'Beer', 'bcoe', 'Y', '1'), ('2', 'Cider', 'bcoe', 'Y', '1'), ('3', 'Mead', 'boce', 'Y', '1');

ALTER TABLE `brewing` ADD `brewScore` INT( 8 ) NULL ;
ALTER TABLE `judging` ADD `judgingRounds` INT( 11 ) NULL DEFAULT '1' COMMENT 'number of rounds at location';

ALTER TABLE `contest_info` ADD `contestEntryFeePassword` VARCHAR( 255 ) NULL ;
ALTER TABLE `contest_info` ADD `contestEntryFeePasswordNum` INT( 11 ) NULL ;

ALTER TABLE `brewer` ADD `brewerDiscount` CHAR( 1 ) NULL COMMENT 'Y or N if this participant receives a discount';

ALTER TABLE `preferences` ADD `prefsCompOrg` CHAR( 1 ) NULL; 
UPDATE `preferences` SET `prefsCompOrg` = 'Y' WHERE `preferences`.`id` =1;