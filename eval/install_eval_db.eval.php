<?php 
$updateSQL = sprintf("CREATE TABLE `%s` (
  `id` int(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
  `eid` int(11) DEFAULT NULL COMMENT 'ID of entry from brewing table',
  `uid` int(11) DEFAULT NULL COMMENT 'ID of entrant from users table',
  `evalToken` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Unique multi-character token to view results',
  `evalJudgeInfo` int(11) DEFAULT NULL COMMENT 'relational to users table id',
  `evalScoresheet` tinyint(1) DEFAULT NULL COMMENT 'scoresheet type used by judge',
  `evalStyle` smallint(5) DEFAULT NULL COMMENT 'ID of substyle in styles table',
  `evalSpecialIngredients` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'populated from entries table',
  `evalOtherNotes` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `evalBottle` tinyint(1) DEFAULT NULL COMMENT '0=not appropriate; 1=appropriate',
  `evalBottleNotes` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `evalAromaScore` tinyint(1) DEFAULT NULL,
  `evalAromaChecklist` mediumtext COLLATE utf8mb4_unicode_ci,
  `evalAromaChecklistDesc` mediumtext COLLATE utf8mb4_unicode_ci,
  `evalAromaComments` mediumtext COLLATE utf8mb4_unicode_ci,
  `evalAppearanceScore` tinyint(2) DEFAULT NULL,
  `evalAppearanceChecklist` mediumtext COLLATE utf8mb4_unicode_ci,
  `evalAppearanceChecklistDesc` mediumtext COLLATE utf8mb4_unicode_ci,
  `evalAppearanceComments` mediumtext COLLATE utf8mb4_unicode_ci,
  `evalFlavorScore` tinyint(2) DEFAULT NULL,
  `evalFlavorChecklist` mediumtext COLLATE utf8mb4_unicode_ci,
  `evalFlavorChecklistDesc` mediumtext COLLATE utf8mb4_unicode_ci,
  `evalFlavorComments` mediumtext COLLATE utf8mb4_unicode_ci,
  `evalMouthfeelScore` tinyint(2) DEFAULT NULL,
  `evalMouthfeelChecklist` mediumtext COLLATE utf8mb4_unicode_ci,
  `evalMouthfeelChecklistDesc` mediumtext COLLATE utf8mb4_unicode_ci,
  `evalMouthfeelComments` mediumtext COLLATE utf8mb4_unicode_ci,
  `evalOverallScore` tinyint(2) DEFAULT NULL,
  `evalOverallChecklist` mediumtext COLLATE utf8mb4_unicode_ci,
  `evalOverallChecklistDesc` mediumtext COLLATE utf8mb4_unicode_ci,
  `evalOverallComments` mediumtext COLLATE utf8mb4_unicode_ci,
  `evalStyleAccuracy` tinyint(1) DEFAULT NULL,
  `evalTechMerit` tinyint(1) DEFAULT NULL,
  `evalIntangibles` tinyint(1) DEFAULT NULL,
  `evalDescriptors` mediumtext COLLATE utf8mb4_unicode_ci,
  `evalDrinkability` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `evalFlaws` mediumtext COLLATE utf8mb4_unicode_ci,
  `evalInitialDate` int(12) DEFAULT NULL COMMENT 'UNIX timestamp of initial submit',
  `evalUpdatedDate` int(12) DEFAULT NULL COMMENT 'UNIX timestamp of edited submit',
  `evalTable` smallint(5) DEFAULT NULL COMMENT 'ID of table from judging_tables table',
  `evalFinalScore` smallint(5) DEFAULT NULL COMMENT 'final, agreed upon score',
  `evalMiniBOS` tinyint(1) DEFAULT NULL COMMENT '0=no; 1=yes',
  `evalPosition` varchar(15) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Position in flight - separated by comma',
  `evalPlace` smallint(5) DEFAULT NULL COMMENT 'Place awarded'
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;", $prefix."evaluation");
mysqli_real_escape_string($connection,$updateSQL);
$result = mysqli_query($connection,$updateSQL) or die (mysqli_error($connection));

if (!check_update("jPrefsScoresheet", $prefix."judging_preferences")) {
  $updateSQL = sprintf("ALTER TABLE `%s` 
    ADD `jPrefsJudgingOpen` int(15) NULL DEFAULT NULL AFTER `jPrefsBottleNum`, 
    ADD `jPrefsJudgingClosed` int(15) NULL DEFAULT NULL AFTER `jPrefsJudgingOpen`, 
    ADD `jPrefsScoresheet` tinyint(2) NULL DEFAULT NULL AFTER `jPrefsJudgingClosed`, 
    ADD `jPrefsScoreDispMax` tinyint(2) NULL DEFAULT NULL COMMENT 'Maximum disparity of entry scores between judges' AFTER `jPrefsScoresheet`;
    ", $prefix."judging_preferences");
  mysqli_real_escape_string($connection,$updateSQL);
  $result = mysqli_query($connection,$updateSQL) or die (mysqli_error($connection));

  $timestamp = time();
  $plus_one_week = strtotime('+14 days', $timestamp);

  // Add default values for the 
  $updateSQL = sprintf("UPDATE %s SET jPrefsJudgingOpen='%s', jPrefsJudgingClosed='%s', jPrefsScoresheet='1', jPrefsScoreDispMax='7' WHERE id='1'", $prefix."judging_preferences", $timestamp, $plus_one_week);
  mysqli_real_escape_string($connection,$updateSQL);
  $result = mysqli_query($connection,$updateSQL) or die (mysqli_error($connection));
  unset($_SESSION['prefs'.$prefix_session]);

}
?>