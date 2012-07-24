<?php 
echo "<h2>Performing Updates for Version 1.2.0.0...</h2>";

$updateSQL = "RENAME TABLE `judging` TO `judging_locations`;"; 
mysql_select_db($database, $brewing);
$result = mysql_query($updateSQL, $brewing) or die(mysql_error());

$updateSQL = "CREATE TABLE IF NOT EXISTS `judging_preferences` (`id` int(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,  `jPrefsQueued` char(1) DEFAULT NULL COMMENT 'Whether to use the Queued Judging technique from AHA', `jPrefsFlightEntries` int(11) DEFAULT NULL COMMENT 'Maximum amount of entries per flight', `jPrefsMaxBOS` INT(11) NULL DEFAULT NULL COMMENT 'Maximum amount of places awarded for each BOS style type',`jPrefsRounds` INT(11) NULL DEFAULT NULL COMMENT 'Maximum amount of rounds per judging location') ENGINE=MyISAM ;"; 
mysql_select_db($database, $brewing);
$result = mysql_query($updateSQL, $brewing) or die(mysql_error());

$updateSQL = "INSERT INTO `judging_preferences` (`id` , `jPrefsQueued` , `jPrefsFlightEntries` , `jPrefsMaxBOS`, `jPrefsRounds`) VALUES ('1' , 'N', '12', '7', '3');"; 
mysql_select_db($database, $brewing);
$result = mysql_query($updateSQL, $brewing) or die(mysql_error());

$updateSQL = "CREATE TABLE IF NOT EXISTS `judging_flights` (`id` int(11) NOT NULL AUTO_INCREMENT PRIMARY KEY , `flightTable` int(11) DEFAULT NULL COMMENT 'id of Table from tables', `flightNumber` int(11) DEFAULT NULL, `flightEntryID` TEXT NULL DEFAULT NULL COMMENT 'array of ids of each entry from the brewing table', `flightRound` int(11) DEFAULT NULL) ENGINE=MyISAM ;"; 
mysql_select_db($database, $brewing);
$result = mysql_query($updateSQL, $brewing) or die(mysql_error());

$updateSQL = "CREATE TABLE `judging_scores` (`id` INT(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,`eid` INT(11) NULL COMMENT 'entry id from brewing table',`bid` INT(11) NULL COMMENT 'brewer id from brewer table',`scoreTable` INT(11) NULL COMMENT 'id of table from judging_tables table',`scoreEntry` INT(11) NULL COMMENT 'numerical score assigned by judges',`scorePlace` FLOAT(11) NULL COMMENT 'place of entry as assigned by judges',`scoreType` CHAR(1) NULL COMMENT 'type of entry used for custom styles') ENGINE = MYISAM;"; 
mysql_select_db($database, $brewing);
$result = mysql_query($updateSQL, $brewing) or die(mysql_error());

echo "<ul><li>Judging tables added successfully.</li></ul>";

?>