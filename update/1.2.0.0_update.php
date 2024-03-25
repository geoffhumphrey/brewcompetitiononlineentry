<?php 

if (!function_exists('check_update')) {
	$redirect = "../../403.php";
	$redirect_go_to = sprintf("Location: %s", $redirect);
	header($redirect_go_to);
	exit();
}
 
// Need to escape!
$output .= "<h4>Version 1.2.0.0</h4>";

if (!check_update("brewerDiscount", $prefix."brewer")) {
	$output .= "<ul>";
	$updateSQL = "RENAME TABLE `".$prefix."judging` TO `".$prefix."judging_locations`;";
	mysqli_real_escape_string($connection,$updateSQL);
	$result = mysqli_query($connection,$updateSQL) or die (mysqli_error($connection));
	
	$updateSQL = "CREATE TABLE IF NOT EXISTS `".$prefix."judging_preferences` (`id` int(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,  `jPrefsQueued` char(1) DEFAULT NULL COMMENT 'Whether to use the Queued Judging technique from AHA', `jPrefsFlightEntries` int(11) DEFAULT NULL COMMENT 'Maximum amount of entries per flight', `jPrefsMaxBOS` INT(11) NULL DEFAULT NULL COMMENT 'Maximum amount of places awarded for each BOS style type',`jPrefsRounds` INT(11) NULL DEFAULT NULL COMMENT 'Maximum amount of rounds per judging location') ENGINE=MyISAM ;"; 
	mysqli_real_escape_string($connection,$updateSQL);
	$result = mysqli_query($connection,$updateSQL) or die (mysqli_error($connection));
	
	$updateSQL = "INSERT INTO `".$prefix."judging_preferences` (`id` , `jPrefsQueued` , `jPrefsFlightEntries` , `jPrefsMaxBOS`, `jPrefsRounds`) VALUES ('1' , 'N', '12', '7', '3');";
	mysqli_real_escape_string($connection,$updateSQL);
	$result = mysqli_query($connection,$updateSQL) or die (mysqli_error($connection));
	$output .= "<li>Judging preferences added successfully.</li>";
	
	$updateSQL = "CREATE TABLE IF NOT EXISTS `".$prefix."judging_tables` (
	  `id` int(11) NOT NULL AUTO_INCREMENT PRIMARY KEY ,
	  `tableName` varchar(255) DEFAULT NULL COMMENT 'Name of table that will judge the prescribed categories',
	  `tableStyles` TEXT DEFAULT NULL COMMENT 'Array of ids from styles table',
	  `tableNumber` int(11) DEFAULT NULL COMMENT 'User defined for sorting',
	  `tableLocation` int(11) DEFAULT NULL COMMENT 'Physical location of table (if more than one judging location) - relational to judging table',
	  `tableJudges` VARCHAR(255) NULL COMMENT 'Array of ids from brewer table',
	  `tableStewards` VARCHAR(255) NULL COMMENT 'Array of ids from brewer table'
	) ENGINE=MyISAM ;"; 
	mysqli_real_escape_string($connection,$updateSQL);
	$result = mysqli_query($connection,$updateSQL) or die (mysqli_error($connection));
	
	$updateSQL = "CREATE TABLE IF NOT EXISTS `".$prefix."judging_flights` (`id` int(11) NOT NULL AUTO_INCREMENT PRIMARY KEY , `flightTable` int(11) DEFAULT NULL COMMENT 'id of Table from tables', `flightNumber` int(11) DEFAULT NULL, `flightEntryID` TEXT NULL DEFAULT NULL COMMENT 'array of ids of each entry from the brewing table', `flightRound` int(11) DEFAULT NULL) ENGINE=MyISAM ;"; 
	mysqli_real_escape_string($connection,$updateSQL);
	$result = mysqli_query($connection,$updateSQL) or die (mysqli_error($connection));
	
	$updateSQL = "CREATE TABLE IF NOT EXISTS `".$prefix."judging_scores` (`id` INT(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,`eid` INT(11) NULL COMMENT 'entry id from brewing table',`bid` INT(11) NULL COMMENT 'brewer id from brewer table',`scoreTable` INT(11) NULL COMMENT 'id of table from judging_tables table',`scoreEntry` INT(11) NULL COMMENT 'numerical score assigned by judges',`scorePlace` FLOAT(11) NULL COMMENT 'place of entry as assigned by judges',`scoreType` CHAR(1) NULL COMMENT 'type of entry used for custom styles') ENGINE = MYISAM;"; 
	mysqli_real_escape_string($connection,$updateSQL);
	$result = mysqli_query($connection,$updateSQL) or die (mysqli_error($connection));
	
	$updateSQL = "CREATE TABLE IF NOT EXISTS `".$prefix."judging_scores_bos` (
		`id` INT(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
		`eid` INT(11) NULL COMMENT 'entry id from brewing table',
		`bid` INT(11) NULL COMMENT 'brewer id from brewer table',
		`scoreEntry` INT(11) NULL COMMENT 'numerical score assigned by judges',
		`scorePlace` FLOAT(11) NULL COMMENT 'place of entry as assigned by judges',
		`scoreType` CHAR(1) NULL COMMENT 'type of entry used for custom styles'
		) ENGINE = MYISAM;"; 
	mysqli_real_escape_string($connection,$updateSQL);
	$result = mysqli_query($connection,$updateSQL) or die (mysqli_error($connection));
	
	$updateSQL = "CREATE TABLE IF NOT EXISTS `".$prefix."judging_assignments` (
		`id` INT( 11 ) NOT NULL AUTO_INCREMENT PRIMARY KEY ,
		`bid` INT( 11 ) NULL COMMENT 'id from brewer table',
		`assignment` CHAR ( 1 ) NULL,
		`assignTable` INT( 11 ) NULL COMMENT 'id from judging_tables table',
		`assignFlight` INT( 11 ) NULL ,
		`assignRound` INT( 11 ) NULL,
		`assignLocation` INT ( 11 ) NULL
		) ENGINE = MYISAM ;
	"; 
	mysqli_real_escape_string($connection,$updateSQL);
	$result = mysqli_query($connection,$updateSQL) or die (mysqli_error($connection));
	$output .= "<li>Judging-related tables added successfully.</li>";
	
	$updateSQL = "CREATE TABLE IF NOT EXISTS `".$prefix."style_types` (
		`id` INT( 11 ) NOT NULL AUTO_INCREMENT PRIMARY KEY ,
		`styleTypeName` VARCHAR( 255 ) NULL,
		`styleTypeOwn` VARCHAR( 255 ) NULL,
		`styleTypeBOS` CHAR( 1 ) NULL,
		`styleTypeBOSMethod` INT( 11 ) NULL
		) ENGINE = MYISAM ;"; 
	mysqli_real_escape_string($connection,$updateSQL);
	$result = mysqli_query($connection,$updateSQL) or die (mysqli_error($connection));
	
	$updateSQL = "INSERT INTO `".$prefix."style_types` (`id` ,`styleTypeName`,`styleTypeOwn`,`styleTypeBOS`,`styleTypeBOSMethod`)
	VALUES ('1', 'Beer', 'bcoe', 'Y', '1'), ('2', 'Cider', 'bcoe', 'Y', '1'), ('3', 'Mead', 'boce', 'Y', '1');"; 
	mysqli_real_escape_string($connection,$updateSQL);
	$result = mysqli_query($connection,$updateSQL) or die (mysqli_error($connection));
	$output .= "<li>Style Types table added successfully.</li>";
	
	$updateSQL = "CREATE TABLE IF NOT EXISTS `".$prefix."themes` (
		`id` INT( 11 ) NOT NULL AUTO_INCREMENT ,
		`themeTitle` VARCHAR( 255 ) NULL ,
		`themeFileName` VARCHAR( 255 ) NULL ,
		PRIMARY KEY ( `id` )
		) ENGINE = MYISAM ;"; 
	mysqli_real_escape_string($connection,$updateSQL);
	$result = mysqli_query($connection,$updateSQL) or die (mysqli_error($connection));
	
	$updateSQL = "INSERT INTO `".$prefix."themes` (`id`, `themeTitle`, `themeFileName`) VALUES (NULL, 'BCOE&amp;M Default', 'default');";
	mysqli_real_escape_string($connection,$updateSQL);
	$result = mysqli_query($connection,$updateSQL) or die (mysqli_error($connection));
	
	$updateSQL = "INSERT INTO `".$prefix."themes` (`id`, `themeTitle`, `themeFileName`) VALUES (NULL, 'Bruxellensis', 'bruxellensis');";
	mysqli_real_escape_string($connection,$updateSQL);
	$result = mysqli_query($connection,$updateSQL) or die (mysqli_error($connection));
	
	$updateSQL = "INSERT INTO `".$prefix."themes` (`id`, `themeTitle`, `themeFileName`) VALUES (NULL, 'Claussenii', 'claussenii'); ";
	mysqli_real_escape_string($connection,$updateSQL);
	$result = mysqli_query($connection,$updateSQL) or die (mysqli_error($connection));
	$output .= "<li>Themes table added successfully.</li>";
	
	$updateSQL = "CREATE TABLE IF NOT EXISTS `".$prefix."countries` (
		`id` INT( 11 ) NULL ,
		`name` VARCHAR( 255 ) NULL ,
		PRIMARY KEY ( `id` )
		) ENGINE = MYISAM ;"; 
	mysqli_real_escape_string($connection,$updateSQL);
	$result = mysqli_query($connection,$updateSQL) or die (mysqli_error($connection));
	
	$updateSQL = "INSERT INTO `".$prefix."countries` (`id`, `name`) VALUES
		(1, 'United States'),
		(2, 'Australia'),
		(3, 'Canada'),
		(4, 'Ireland'),
		(5, 'United Kingdom'),
		(101, 'Afghanistan'),
		(102, 'Albania'),
		(103, 'Algeria'),
		(104, 'American Samoa'),
		(105, 'Andorra'),
		(106, 'Angola'),
		(107, 'Anguilla'),
		(108, 'Antarctica'),
		(109, 'Antigua and Barbuda'),
		(110, 'Argentina'),
		(111, 'Armenia'),
		(113, 'Aruba'),
		(115, 'Austria'),
		(116, 'Azerbaijan'),
		(118, 'Bahamas'),
		(119, 'Bahrain'),
		(120, 'Bangladesh'),
		(121, 'Barbados'),
		(122, 'Belarus'),
		(123, 'Belgium'),
		(124, 'Belize'),
		(125, 'Benin'),
		(126, 'Bermuda'),
		(127, 'Bhutan'),
		(128, 'Bolivia'),
		(129, 'Bosnia and Herzegovina'),
		(130, 'Botswana'),
		(131, 'Bouvet Island'),
		(132, 'Brazil'),
		(133, 'British Indian Ocean Territory'),
		(134, 'Brunei Darussalam'),
		(135, 'Bulgaria'),
		(136, 'Burkina Faso'),
		(137, 'Burundi'),
		(138, 'Cambodia'),
		(139, 'Cameroon'),
		(141, 'Cape Verde'),
		(142, 'Cayman Islands'),
		(143, 'Central African Republic'),
		(144, 'Chad'),
		(145, 'Chile'),
		(146, 'China'),
		(147, 'Christmas Island'),
		(148, 'Cocos (Keeling) Islands'),
		(149, 'Colombia'),
		(150, 'Comoros'),
		(151, 'Congo'),
		(152, 'Congo, The Democratic Republic of The'),
		(153, 'Cook Islands'),
		(154, 'Costa Rica'),
		(155, 'Cote D\'ivoire'),
		(156, 'Croatia'),
		(157, 'Cuba'),
		(158, 'Cyprus'),
		(160, 'Czech Republic'),
		(161, 'Denmark'),
		(162, 'Djibouti'),
		(163, 'Dominica'),
		(164, 'Dominican Republic'),
		(165, 'Easter Island'),
		(166, 'Ecuador'),
		(167, 'Egypt'),
		(168, 'El Salvador'),
		(169, 'Equatorial Guinea'),
		(170, 'Eritrea'),
		(171, 'Estonia'),
		(172, 'Ethiopia'),
		(173, 'Falkland Islands (Malvinas)'),
		(174, 'Faroe Islands'),
		(175, 'Fiji'),
		(176, 'Finland'),
		(177, 'France'),
		(178, 'French Guiana'),
		(179, 'French Polynesia'),
		(180, 'French Southern Territories'),
		(181, 'Gabon'),
		(182, 'Gambia'),
		(183, 'Georgia'),
		(185, 'Germany'),
		(186, 'Ghana'),
		(187, 'Gibraltar'),
		(188, 'Greece'),
		(189, 'Greenland'),
		(191, 'Grenada'),
		(192, 'Guadeloupe'),
		(193, 'Guam'),
		(194, 'Guatemala'),
		(195, 'Guinea'),
		(196, 'Guinea-bissau'),
		(197, 'Guyana'),
		(198, 'Haiti'),
		(199, 'Heard Island and Mcdonald Islands'),
		(200, 'Honduras'),
		(201, 'Hong Kong'),
		(202, 'Hungary'),
		(203, 'Iceland'),
		(204, 'India'),
		(205, 'Indonesia'),
		(207, 'Iran'),
		(208, 'Iraq'),
		(210, 'Israel'),
		(211, 'Italy'),
		(212, 'Jamaica'),
		(213, 'Japan'),
		(214, 'Jordan'),
		(215, 'Kazakhstan'),
		(217, 'Kenya'),
		(218, 'Kiribati'),
		(219, 'Korea, North'),
		(220, 'Korea, South'),
		(221, 'Kosovo'),
		(222, 'Kuwait'),
		(223, 'Kyrgyzstan'),
		(224, 'Laos'),
		(225, 'Latvia'),
		(226, 'Lebanon'),
		(227, 'Lesotho'),
		(228, 'Liberia'),
		(229, 'Libyan Arab Jamahiriya'),
		(230, 'Liechtenstein'),
		(231, 'Lithuania'),
		(232, 'Luxembourg'),
		(233, 'Macau'),
		(234, 'Macedonia'),
		(235, 'Madagascar'),
		(236, 'Malawi'),
		(237, 'Malaysia'),
		(238, 'Maldives'),
		(239, 'Mali'),
		(240, 'Malta'),
		(241, 'Marshall Islands'),
		(242, 'Martinique'),
		(243, 'Mauritania'),
		(244, 'Mauritius'),
		(245, 'Mayotte'),
		(246, 'Mexico'),
		(247, 'Micronesia, Federated States of'),
		(248, 'Moldova, Republic of'),
		(249, 'Monaco'),
		(250, 'Mongolia'),
		(251, 'Montenegro'),
		(252, 'Montserrat'),
		(253, 'Morocco'),
		(254, 'Mozambique'),
		(255, 'Myanmar'),
		(256, 'Namibia'),
		(257, 'Nauru'),
		(258, 'Nepal'),
		(259, 'Netherlands'),
		(260, 'Netherlands Antilles'),
		(261, 'New Caledonia'),
		(262, 'New Zealand'),
		(263, 'Nicaragua'),
		(264, 'Niger'),
		(265, 'Nigeria'),
		(266, 'Niue'),
		(267, 'Norfolk Island'),
		(268, 'Northern Mariana Islands'),
		(269, 'Norway'),
		(270, 'Oman'),
		(271, 'Pakistan'),
		(272, 'Palau'),
		(273, 'Palestinian Territory'),
		(274, 'Panama'),
		(275, 'Papua New Guinea'),
		(276, 'Paraguay'),
		(277, 'Peru'),
		(278, 'Philippines'),
		(279, 'Pitcairn'),
		(280, 'Poland'),
		(281, 'Portugal'),
		(282, 'Puerto Rico'),
		(283, 'Qatar'),
		(284, 'Reunion'),
		(285, 'Romania'),
		(286, 'Russia'),
		(287, 'Russia'),
		(288, 'Rwanda'),
		(289, 'Saint Helena'),
		(290, 'Saint Kitts and Nevis'),
		(291, 'Saint Lucia'),
		(292, 'Saint Pierre and Miquelon'),
		(293, 'Saint Vincent and The Grenadines'),
		(294, 'Samoa'),
		(295, 'San Marino'),
		(296, 'Sao Tome and Principe'),
		(297, 'Saudi Arabia'),
		(298, 'Senegal'),
		(299, 'Serbia and Montenegro'),
		(300, 'Seychelles'),
		(301, 'Sierra Leone'),
		(302, 'Singapore'),
		(303, 'Slovakia'),
		(304, 'Slovenia'),
		(305, 'Solomon Islands'),
		(306, 'Somalia'),
		(307, 'South Africa'),
		(308, 'South Georgia/South Sandwich Islands'),
		(309, 'Spain'),
		(310, 'Sri Lanka'),
		(311, 'Sudan'),
		(312, 'Suriname'),
		(313, 'Svalbard and Jan Mayen'),
		(314, 'Swaziland'),
		(315, 'Sweden'),
		(316, 'Switzerland'),
		(317, 'Syria'),
		(318, 'Taiwan'),
		(319, 'Tajikistan'),
		(320, 'Tanzania, United Republic of'),
		(321, 'Thailand'),
		(322, 'Timor-leste'),
		(323, 'Togo'),
		(324, 'Tokelau'),
		(325, 'Tonga'),
		(326, 'Trinidad and Tobago'),
		(327, 'Tunisia'),
		(328, 'Turkey'),
		(330, 'Turkmenistan'),
		(331, 'Turks and Caicos Islands'),
		(332, 'Tuvalu'),
		(333, 'Uganda'),
		(334, 'Ukraine'),
		(335, 'United Arab Emirates'),
		(338, 'United States Minor Outlying Islands'),
		(339, 'Uruguay'),
		(340, 'Uzbekistan'),
		(341, 'Vanuatu'),
		(342, 'Vatican City'),
		(343, 'Venezuela'),
		(344, 'Vietnam'),
		(345, 'Virgin Islands, British'),
		(346, 'Virgin Islands, U.S.'),
		(347, 'Wallis and Futuna'),
		(348, 'Western Sahara'),
		(349, 'Yemen'),
		(351, 'Zambia'),
		(352, 'Zimbabwe'),
		(353, 'Other');
	"; 
	mysqli_real_escape_string($connection,$updateSQL);
	$result = mysqli_query($connection,$updateSQL) or die (mysqli_error($connection));
	$output .= "<li>Countries table and data added successfully.</li>";
	
	$updateSQL = "ALTER TABLE `".$prefix."brewing` ADD `brewScore` INT( 8 ) NULL ;"; 
	mysqli_real_escape_string($connection,$updateSQL);
	$result = mysqli_query($connection,$updateSQL) or die (mysqli_error($connection));
	
	$updateSQL = "ALTER TABLE `".$prefix."judging_locations` ADD `judgingRounds` INT( 11 ) NULL DEFAULT '1' COMMENT 'number of rounds at location';"; 
	mysqli_real_escape_string($connection,$updateSQL);
	$result = mysqli_query($connection,$updateSQL) or die (mysqli_error($connection));
	
	$updateSQL = "ALTER TABLE `".$prefix."contest_info` CHANGE `contestEntryFee` `contestEntryFee` INT( 11 ) NULL DEFAULT NULL;"; 
	mysqli_real_escape_string($connection,$updateSQL);
	$result = mysqli_query($connection,$updateSQL) or die (mysqli_error($connection));
	
	$updateSQL = "ALTER TABLE `".$prefix."contest_info` CHANGE `contestEntryFee2` `contestEntryFee2` INT( 11 ) NULL DEFAULT NULL ;"; 
	mysqli_real_escape_string($connection,$updateSQL);
	$result = mysqli_query($connection,$updateSQL) or die (mysqli_error($connection));
	
	$updateSQL = "ALTER TABLE `".$prefix."contest_info` ADD `contestEntryFeePassword` VARCHAR( 255 ) NULL ;"; 
	mysqli_real_escape_string($connection,$updateSQL);
	$result = mysqli_query($connection,$updateSQL) or die (mysqli_error($connection));
	
	$updateSQL = "ALTER TABLE `".$prefix."contest_info` ADD `contestEntryFeePasswordNum` INT( 11 ) NULL ;"; 
	mysqli_real_escape_string($connection,$updateSQL);
	$result = mysqli_query($connection,$updateSQL) or die (mysqli_error($connection));
	
	$updateSQL = "ALTER TABLE `".$prefix."contest_info` ADD `contestID` VARCHAR( 11 ) NULL ;"; 
	mysqli_real_escape_string($connection,$updateSQL);
	$result = mysqli_query($connection,$updateSQL) or die (mysqli_error($connection));
	
	$updateSQL = "ALTER TABLE `".$prefix."preferences` ADD `prefsCompOrg` CHAR( 1 ) NULL;"; 
	mysqli_real_escape_string($connection,$updateSQL);
	$result = mysqli_query($connection,$updateSQL) or die (mysqli_error($connection));
	
	$updateSQL = "ALTER TABLE `".$prefix."preferences` ADD `prefsTheme` VARCHAR( 255 ) NULL;"; 
	mysqli_real_escape_string($connection,$updateSQL);
	$result = mysqli_query($connection,$updateSQL) or die (mysqli_error($connection));
	
	$updateSQL = "ALTER TABLE `".$prefix."preferences` ADD `prefsDateFormat` CHAR( 1 ) NULL;"; 
	mysqli_real_escape_string($connection,$updateSQL);
	$result = mysqli_query($connection,$updateSQL) or die (mysqli_error($connection));
	
	$updateSQL = "ALTER TABLE `".$prefix."preferences` ADD `prefsContact` CHAR( 1 ) NULL DEFAULT NULL;"; 
	mysqli_real_escape_string($connection,$updateSQL);
	$result = mysqli_query($connection,$updateSQL) or die (mysqli_error($connection));
	
	$updateSQL = "UPDATE `".$prefix."preferences` SET `prefsCompOrg` = 'Y' WHERE `id` ='1';"; 
	mysqli_real_escape_string($connection,$updateSQL);
	$result = mysqli_query($connection,$updateSQL) or die (mysqli_error($connection));
	
	$updateSQL = "UPDATE `".$prefix."preferences` SET `prefsTheme` = 'default' WHERE `id` ='1';"; 
	mysqli_real_escape_string($connection,$updateSQL);
	$result = mysqli_query($connection,$updateSQL) or die (mysqli_error($connection));
	
	$updateSQL = "UPDATE `".$prefix."preferences` SET `prefsContact` = 'Y' WHERE `id` ='1';"; 
	mysqli_real_escape_string($connection,$updateSQL);
	$result = mysqli_query($connection,$updateSQL) or die (mysqli_error($connection));
	
	$updateSQL = "ALTER TABLE `".$prefix."brewer` ADD `brewerDiscount` CHAR( 1 ) NULL COMMENT 'Y or N if this participant receives a discount';"; 
	mysqli_real_escape_string($connection,$updateSQL);
	$result = mysqli_query($connection,$updateSQL) or die (mysqli_error($connection));
	
	$updateSQL = "ALTER TABLE `".$prefix."brewer` DROP `brewerJudgeLocation2` ;"; 
	mysqli_real_escape_string($connection,$updateSQL);
	$result = mysqli_query($connection,$updateSQL) or die (mysqli_error($connection));
	
	$updateSQL = "ALTER TABLE `".$prefix."brewer` DROP `brewerStewardLocation2` ;"; 
	mysqli_real_escape_string($connection,$updateSQL);
	$result = mysqli_query($connection,$updateSQL) or die (mysqli_error($connection));
	
	$updateSQL = "ALTER TABLE `".$prefix."brewer` ADD `brewerJudgeBOS` CHAR ( 1 ) NULL COMMENT 'Y if judged in BOS round';"; 
	mysqli_real_escape_string($connection,$updateSQL);
	$result = mysqli_query($connection,$updateSQL) or die (mysqli_error($connection));
	
	$updateSQL = "ALTER TABLE `".$prefix."brewer` CHANGE `brewerJudgeLocation` `brewerJudgeLocation` TEXT NULL DEFAULT NULL;"; 
	mysqli_real_escape_string($connection,$updateSQL);
	$result = mysqli_query($connection,$updateSQL) or die (mysqli_error($connection));
	
	$updateSQL = "ALTER TABLE `".$prefix."brewer` CHANGE `brewerStewardLocation` `brewerStewardLocation` TEXT NULL DEFAULT NULL;"; 
	mysqli_real_escape_string($connection,$updateSQL);
	$result = mysqli_query($connection,$updateSQL) or die (mysqli_error($connection));
	
	$updateSQL = "ALTER TABLE `".$prefix."brewer` CHANGE `brewerJudgeAssignedLocation` `brewerJudgeAssignedLocation` TEXT NULL DEFAULT NULL;"; 
	mysqli_real_escape_string($connection,$updateSQL);
	$result = mysqli_query($connection,$updateSQL) or die (mysqli_error($connection));
	
	$updateSQL = "ALTER TABLE `".$prefix."brewer` CHANGE `brewerStewardAssignedLocation` `brewerStewardAssignedLocation` TEXT NULL DEFAULT NULL;"; 
	mysqli_real_escape_string($connection,$updateSQL);
	$result = mysqli_query($connection,$updateSQL) or die (mysqli_error($connection));
	
	$updateSQL = "ALTER TABLE `".$prefix."styles` CHANGE  `brewStyleGroup`  `brewStyleGroup` VARCHAR( 3 ) NULL DEFAULT NULL;"; 
	mysqli_real_escape_string($connection,$updateSQL);
	$result = mysqli_query($connection,$updateSQL) or die (mysqli_error($connection));
	
	$updateSQL = "ALTER TABLE `".$prefix."styles` CHANGE  `brewStyleNum`  `brewStyleNum` VARCHAR( 3 ) NULL DEFAULT NULL;"; 
	mysqli_real_escape_string($connection,$updateSQL);
	$result = mysqli_query($connection,$updateSQL) or die (mysqli_error($connection));
	$output .= "<li>All table data updated successfully.</li>";
	
	// Need to add "archive" tables for all judging organization tables
	$query_archive_1200 = "SELECT archiveSuffix FROM $archive_db_table";
	$archive_1200 = mysqli_query($connection,$query_archive_1200) or die (mysqli_error($connection));
	$row_archive_1200 = mysqli_fetch_assoc($archive_1200);
	$totalRows_archive_1200 = mysqli_num_rows($archive_1200);

	$a_1200 = array();
	
	if ($totalRows_archive_1200 > 0) {
		do { $a_1200[] = $row_archive_1200['archiveSuffix']; } while ($row_archive_1200 = mysqli_fetch_assoc($archive_1200));
		
		foreach ($a_1200 as $suffix) {
			
			$suffix = rtrim($suffix, "_"); // HACK - could not isolate code where there's an extra "_"
			if (strpos($suffix,'_') !== false) $suffix = $suffix;
			//if (substr($suffix,0,1) == '_') !== false) $suffix = "_".$suffix;
			else $suffix = "_".$suffix;
			
			$updateSQL = "CREATE TABLE IF NOT EXISTS `".$prefix."judging_tables".$suffix."` (
			  `id` int(11) NOT NULL AUTO_INCREMENT PRIMARY KEY ,
			  `tableName` varchar(255) DEFAULT NULL COMMENT 'Name of table that will judge the prescribed categories',
			  `tableStyles` TEXT DEFAULT NULL COMMENT 'Array of ids from styles table',
			  `tableNumber` int(11) DEFAULT NULL COMMENT 'User defined for sorting',
			  `tableLocation` int(11) DEFAULT NULL COMMENT 'Physical location of table (if more than one judging location) - relational to judging table',
			  `tableJudges` VARCHAR(255) NULL COMMENT 'Array of ids from brewer table',
			  `tableStewards` VARCHAR(255) NULL COMMENT 'Array of ids from brewer table'
			) ENGINE=MyISAM ;"; 
			mysqli_real_escape_string($connection,$updateSQL);
			$result = mysqli_query($connection,$updateSQL) or die (mysqli_error($connection));
			
			$updateSQL = "CREATE TABLE IF NOT EXISTS `".$prefix."judging_flights".$suffix."` (`id` int(11) NOT NULL AUTO_INCREMENT PRIMARY KEY , `flightTable` int(11) DEFAULT NULL COMMENT 'id of Table from tables', `flightNumber` int(11) DEFAULT NULL, `flightEntryID` TEXT NULL DEFAULT NULL COMMENT 'array of ids of each entry from the brewing table', `flightRound` int(11) DEFAULT NULL) ENGINE=MyISAM ;"; 
			mysqli_real_escape_string($connection,$updateSQL);
			$result = mysqli_query($connection,$updateSQL) or die (mysqli_error($connection));
			
			$updateSQL = "CREATE TABLE IF NOT EXISTS `".$prefix."judging_scores".$suffix."` (`id` INT(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,`eid` INT(11) NULL COMMENT 'entry id from brewing table',`bid` INT(11) NULL COMMENT 'brewer id from brewer table',`scoreTable` INT(11) NULL COMMENT 'id of table from judging_tables table',`scoreEntry` INT(11) NULL COMMENT 'numerical score assigned by judges',`scorePlace` FLOAT(11) NULL COMMENT 'place of entry as assigned by judges',`scoreType` CHAR(1) NULL COMMENT 'type of entry used for custom styles') ENGINE = MYISAM;"; 
			mysqli_real_escape_string($connection,$updateSQL);
			$result = mysqli_query($connection,$updateSQL) or die (mysqli_error($connection));
			
			$updateSQL = "CREATE TABLE IF NOT EXISTS `".$prefix."judging_scores_bos".$suffix."` (
				`id` INT(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
				`eid` INT(11) NULL COMMENT 'entry id from brewing table',
				`bid` INT(11) NULL COMMENT 'brewer id from brewer table',
				`scoreEntry` INT(11) NULL COMMENT 'numerical score assigned by judges',
				`scorePlace` FLOAT(11) NULL COMMENT 'place of entry as assigned by judges',
				`scoreType` CHAR(1) NULL COMMENT 'type of entry used for custom styles'
				) ENGINE = MYISAM;"; 
			mysqli_real_escape_string($connection,$updateSQL);
			$result = mysqli_query($connection,$updateSQL) or die (mysqli_error($connection));
			
			$updateSQL = "CREATE TABLE IF NOT EXISTS `".$prefix."judging_assignments".$suffix."` (
				`id` INT( 11 ) NOT NULL AUTO_INCREMENT PRIMARY KEY ,
				`bid` INT( 11 ) NULL COMMENT 'id from brewer table',
				`assignment` CHAR ( 1 ) NULL,
				`assignTable` INT( 11 ) NULL COMMENT 'id from judging_tables table',
				`assignFlight` INT( 11 ) NULL ,
				`assignRound` INT( 11 ) NULL,
				`assignLocation` INT ( 11 ) NULL
				) ENGINE = MYISAM ;
			"; 
			mysqli_real_escape_string($connection,$updateSQL);
			$result = mysqli_query($connection,$updateSQL) or die (mysqli_error($connection));
			
			$updateSQL = "CREATE TABLE IF NOT EXISTS `".$prefix."style_types".$suffix."` (
				`id` INT( 11 ) NOT NULL AUTO_INCREMENT PRIMARY KEY ,
				`styleTypeName` VARCHAR( 255 ) NULL,
				`styleTypeOwn` VARCHAR( 255 ) NULL,
				`styleTypeBOS` CHAR( 1 ) NULL,
				`styleTypeBOSMethod` INT( 11 ) NULL
				) ENGINE = MYISAM ;"; 
			mysqli_real_escape_string($connection,$updateSQL);
			$result = mysqli_query($connection,$updateSQL) or die (mysqli_error($connection));
			
			$updateSQL = "INSERT INTO `".$prefix."style_types".$suffix."` (`id` ,`styleTypeName`,`styleTypeOwn`,`styleTypeBOS`,`styleTypeBOSMethod`)
			VALUES ('1', 'Beer', 'bcoe', 'Y', '1'), ('2', 'Cider', 'bcoe', 'Y', '1'), ('3', 'Mead', 'boce', 'Y', '1');"; 
			mysqli_real_escape_string($connection,$updateSQL);
			$result = mysqli_query($connection,$updateSQL) or die (mysqli_error($connection));
					
		}
		$output .= "<li>Judging-related tables added successfully (archives).</li>";
		$output .= "<li>Style Types table added successfully (archives).</li>";
	}
	$output .= "</ul>";
}
else $output .= "<p>None</p>";
?>