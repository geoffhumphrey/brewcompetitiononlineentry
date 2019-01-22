<?php

if ($setup_free_access == TRUE) {

	// Clear out any previous sessions. Just in case.
	session_unset();
	session_destroy();
	session_write_close();
	setcookie(session_name($prefix_session),'',0,'/');

	$output = "";

	if ($action == "default") {

		$setup_alerts .= "<div class=\"alert alert-info alert-dismissible hidden-print fade in\" role=\"alert\"><button type=\"button\" class=\"close\" data-dismiss=\"alert\" aria-label=\"Close\"><span aria-hidden=\"true\">&times;</span></button><span class=\"fa fa-lg fa-info-circle\"></span> <strong>To begin setup and install, the necessary database tables need to be installed.</strong> These tables hold all of your competition data.</div>";
		$output .= "<p>Click the &ldquo;Install DB Tables and Data&rdquo; button below to install the database schema";
		if (!HOSTED) $output .= " into the following database:";
		else $output .= ".";
		$output .= "</p>";
		if (!HOSTED) {
		$output .= "<ul>";
		$output .= "<li>".$database."</li>";
		$output .= "</ul>";
			if ($prefix != "") {
				$output .= "<p>Each table in the database will be prepended with the following prefix:</p>";
				$output .= "<ul>";
				$output .= "<li>".$prefix."</li>";
				$output .= "</ul>";
			}
		}

		$output .= "<a class=\"btn btn-lg btn-primary hide-loader\" href=\"".$base_url."setup.php?section=step0&amp;action=install-db\" data-confirm=\"Are you sure? This will install all database elements.\"><span class=\"fa fa-lg fa-download\"></span> Install DB Tables and Data</a>";

		}

	if ($action == "install-db") {

		$setup_alerts .= "<div class=\"alert alert-success alert-dismissible hidden-print fade in\" role=\"alert\"><button type=\"button\" class=\"close\" data-dismiss=\"alert\" aria-label=\"Close\"><span aria-hidden=\"true\">&times;</span></button><span class=\"fa fa-lg fa-check-circle\"></span> <strong>All database tables and default data have been installed successfully.</strong></div>
		<div class=\"alert alert-info\"><strong><span class=\"fa fa-lg fa-info-circle\"></span> The setup process is not done.</strong> Click &ldquo;Continue&rdquo; below to setup and customize your installation.</div>";
		$output .= "<div class=\"bcoem-admin-element\"><a class=\"btn btn-primary btn-lg\" href=\"".$base_url."setup.php?section=step1\">Continue <span class=\"fa fa-lg fa-chevron-right\"><span></a></div>";

		//$setup_alerts .= "<div class=\"alert alert-info\"><span class=\"fa fa-lg fa-info-circle\"></span> <strong>Database tables install details are below.</strong></div>";
		$output .= "<div class=\"panel panel-primary\">";
		$output .= "<div class=\"panel-heading\"><strong>Table Installation Details</strong></div>";
		$output .= "<div class=\"panel-body\">";
		$output .= "<p>Status of database tables installation.</p>";
		$output .= "</div>";
		$output .= "<ul class=\"list-group\">";

		// -------------------
		// Make sure default character set is utf8mb4 and collation is utf8mb4_unicode_ci
		// -------------------

		$sql = sprintf("ALTER DATABASE `%s` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;",$database);
		mysqli_select_db($connection,$database);
		mysqli_real_escape_string($connection,$sql);
		$result = mysqli_query($connection,$sql) or die (mysqli_error($connection));

		// -------------------
		// Archive Table
		// -------------------

		$sql = "
		CREATE TABLE IF NOT EXISTS `$archive_db_table` (
			`id` int(11) NOT NULL AUTO_INCREMENT,
			`archiveProEdition` tinyint(1) DEFAULT NULL,
			`archiveStyleSet` varchar(25) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
			`archiveScoresheet` char(1) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
			`archiveSuffix` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
			PRIMARY KEY (`id`)
		) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE utf8mb4_unicode_ci COLLATE utf8mb4_unicode_ci;
		";

		mysqli_select_db($connection,$database);
		mysqli_real_escape_string($connection,$sql);
		$result = mysqli_query($connection,$sql) or die (mysqli_error($connection));
		 //echo "<p>".$sql."</p>";

		$output .= "<li class=\"list-group-item\"><span class=\"fa fa-lg fa-check text-success\"></span> The <strong>Archive</strong> table was installed successfully.</li>";


		// -------------------
		// Brewer Table
		// -------------------

		$sql = "
		CREATE TABLE IF NOT EXISTS `$brewer_db_table` (
			`id` int(11) NOT NULL AUTO_INCREMENT,
			`uid` int(8) DEFAULT NULL,
			`brewerFirstName` varchar(200) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
			`brewerLastName` varchar(200) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
			`brewerAddress` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
			`brewerCity` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
			`brewerState` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
			`brewerZip` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
			`brewerCountry` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
			`brewerPhone1` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
			`brewerPhone2` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
			`brewerClubs` mediumtext COLLATE utf8mb4_unicode_ci,
			`brewerEmail` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
			`brewerStaff` char(1) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
			`brewerSteward` char(1) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
			`brewerJudge` char(1) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
			`brewerJudgeID` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
			`brewerJudgeMead` char(1) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
			`brewerJudgeCider` char(1) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
			`brewerJudgeRank` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
			`brewerJudgeLikes` mediumtext COLLATE utf8mb4_unicode_ci,
			`brewerJudgeDislikes` mediumtext COLLATE utf8mb4_unicode_ci,
			`brewerJudgeLocation` mediumtext COLLATE utf8mb4_unicode_ci,
			`brewerStewardLocation` mediumtext COLLATE utf8mb4_unicode_ci,
			`brewerJudgeExp` varchar(25) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
			`brewerJudgeNotes` mediumtext COLLATE utf8mb4_unicode_ci,
			`brewerAssignment` char(1) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
			`brewerJudgeWaiver` char(1) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
			`brewerAHA` int(11) DEFAULT NULL,
			`brewerDiscount` char(1) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
			`brewerJudgeBOS` char(1) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
			`brewerDropOff` int(4) DEFAULT NULL COMMENT 'Location where brewer will drop off their entries; 0=shipping or relational to dropoff table',
			`brewerBreweryName` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
			`brewerBreweryTTB` varchar(20) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
		  	PRIMARY KEY (`id`)
		) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE utf8mb4_unicode_ci;
		";

		mysqli_select_db($connection,$database);
		mysqli_real_escape_string($connection,$sql);
		$result = mysqli_query($connection,$sql) or die (mysqli_error($connection));
		 //echo "<p>".$sql."</p>";

		$output .= "<li class=\"list-group-item\"><span class=\"fa fa-lg fa-check text-success\"></span> The <strong>Participants</strong> table was installed successfully.</li>";

		// -------------------
		// Brewing Table
		// -------------------

		$sql = "CREATE TABLE IF NOT EXISTS `$brewing_db_table` (
			`id` int(11) NOT NULL AUTO_INCREMENT,
			`brewName` varchar(250) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
			`brewStyle` varchar(250) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
			`brewCategory` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
			`brewCategorySort` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
			`brewSubCategory` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
			`brewBottleDate` date DEFAULT NULL,
			`brewDate` date DEFAULT NULL,
			`brewYield` varchar(10) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
			`brewInfo` mediumtext COLLATE utf8mb4_unicode_ci,
			`brewMead1` varchar(25) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
			`brewMead2` varchar(25) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
			`brewMead3` varchar(25) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
			`brewExtract1` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
			`brewExtract1Weight` varchar(10) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
			`brewExtract2` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
			`brewExtract2Weight` varchar(10) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
			`brewExtract3` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
			`brewExtract3Weight` varchar(4) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
			`brewExtract4` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
			`brewExtract4Weight` varchar(10) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
			`brewExtract5` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
			`brewExtract5Weight` varchar(10) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
			`brewGrain1` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
			`brewGrain1Weight` varchar(10) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
			`brewGrain2` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
			`brewGrain2Weight` varchar(10) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
			`brewGrain3` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
			`brewGrain3Weight` varchar(10) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
			`brewGrain4` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
			`brewGrain4Weight` varchar(10) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
			`brewGrain5` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
			`brewGrain5Weight` varchar(10) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
			`brewGrain6` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
			`brewGrain6Weight` varchar(10) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
			`brewGrain7` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
			`brewGrain7Weight` varchar(10) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
			`brewGrain8` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
			`brewGrain8Weight` varchar(10) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
			`brewGrain9` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
			`brewGrain10` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
			`brewGrain11` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
			`brewGrain12` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
			`brewGrain13` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
			`brewGrain14` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
			`brewGrain15` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
			`brewGrain16` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
			`brewGrain17` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
			`brewGrain18` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
			`brewGrain19` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
			`brewGrain20` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
			`brewGrain9Weight` varchar(10) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
			`brewGrain10Weight` varchar(10) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
			`brewGrain11Weight` varchar(10) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
			`brewGrain12Weight` varchar(10) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
			`brewGrain13Weight` varchar(10) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
			`brewGrain14Weight` varchar(10) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
			`brewGrain15Weight` varchar(10) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
			`brewGrain16Weight` varchar(10) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
			`brewGrain17Weight` varchar(10) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
			`brewGrain18Weight` varchar(10) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
			`brewGrain19Weight` varchar(10) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
			`brewGrain20Weight` varchar(10) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
			`brewAddition1` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
			`brewAddition1Amt` varchar(10) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
			`brewAddition2` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
			`brewAddition2Amt` varchar(10) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
			`brewAddition3` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
			`brewAddition3Amt` varchar(10) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
			`brewAddition4` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
			`brewAddition4Amt` varchar(10) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
			`brewAddition5` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
			`brewAddition5Amt` varchar(10) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
			`brewAddition6` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
			`brewAddition6Amt` varchar(10) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
			`brewAddition7` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
			`brewAddition7Amt` varchar(10) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
			`brewAddition8` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
			`brewAddition8Amt` varchar(10) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
			`brewAddition9` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
			`brewAddition10` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
			`brewAddition11` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
			`brewAddition12` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
			`brewAddition13` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
			`brewAddition14` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
			`brewAddition15` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
			`brewAddition16` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
			`brewAddition17` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
			`brewAddition18` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
			`brewAddition19` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
			`brewAddition20` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
			`brewAddition9Amt` varchar(10) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
			`brewAddition10Amt` varchar(25) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
			`brewAddition11Amt` varchar(25) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
			`brewAddition12Amt` varchar(25) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
			`brewAddition13Amt` varchar(25) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
			`brewAddition14Amt` varchar(25) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
			`brewAddition15Amt` varchar(25) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
			`brewAddition16Amt` varchar(25) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
			`brewAddition17Amt` varchar(25) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
			`brewAddition18Amt` varchar(25) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
			`brewAddition19Amt` varchar(25) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
			`brewAddition20Amt` varchar(25) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
			`brewHops1` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
			`brewHops1Weight` varchar(10) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
			`brewHops1IBU` varchar(10) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
			`brewHops1Time` varchar(10) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
			`brewHops2` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
			`brewHops2Weight` varchar(10) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
			`brewHops2IBU` varchar(10) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
			`brewHops2Time` varchar(10) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
			`brewHops3` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
			`brewHops3Weight` varchar(10) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
			`brewHops3IBU` varchar(10) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
			`brewHops3Time` varchar(10) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
			`brewHops4` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
			`brewHops4Weight` varchar(10) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
			`brewHops4IBU` varchar(10) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
			`brewHops4Time` varchar(10) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
			`brewHops5` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
			`brewHops5Weight` varchar(10) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
			`brewHops5IBU` varchar(10) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
			`brewHops5Time` varchar(10) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
			`brewHops6` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
			`brewHops6Weight` varchar(10) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
			`brewHops6IBU` varchar(10) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
			`brewHops6Time` varchar(10) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
			`brewHops7` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
			`brewHops7Weight` varchar(10) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
			`brewHops7IBU` varchar(10) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
			`brewHops7Time` varchar(10) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
			`brewHops8` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
			`brewHops8Weight` varchar(10) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
			`brewHops8IBU` varchar(10) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
			`brewHops8Time` varchar(10) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
			`brewHops9` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
			`brewHops10` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
			`brewHops11` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
			`brewHops12` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
			`brewHops13` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
			`brewHops14` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
			`brewHops15` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
			`brewHops16` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
			`brewHops17` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
			`brewHops18` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
			`brewHops19` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
			`brewHops20` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
			`brewHops9Weight` varchar(10) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
			`brewHops10Weight` varchar(10) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
			`brewHops11Weight` varchar(10) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
			`brewHops12Weight` varchar(10) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
			`brewHops13Weight` varchar(10) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
			`brewHops14Weight` varchar(10) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
			`brewHops15Weight` varchar(10) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
			`brewHops16Weight` varchar(10) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
			`brewHops17Weight` varchar(10) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
			`brewHops18Weight` varchar(10) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
			`brewHops19Weight` varchar(10) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
			`brewHops20Weight` varchar(10) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
			`brewHops9IBU` varchar(10) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
			`brewHops10IBU` varchar(6) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
			`brewHops11IBU` varchar(6) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
			`brewHops12IBU` varchar(6) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
			`brewHops13IBU` varchar(6) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
			`brewHops14IBU` varchar(6) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
			`brewHops15IBU` varchar(6) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
			`brewHops16IBU` varchar(6) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
			`brewHops17IBU` varchar(6) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
			`brewHops18IBU` varchar(6) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
			`brewHops19IBU` varchar(6) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
			`brewHops20IBU` varchar(6) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
			`brewHops9Time` varchar(10) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
			`brewHops10Time` varchar(10) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
			`brewHops11Time` varchar(10) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
			`brewHops12Time` varchar(10) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
			`brewHops13Time` varchar(10) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
			`brewHops14Time` varchar(10) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
			`brewHops15Time` varchar(10) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
			`brewHops16Time` varchar(10) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
			`brewHops17Time` varchar(10) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
			`brewHops18Time` varchar(10) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
			`brewHops19Time` varchar(10) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
			`brewHops20Time` varchar(10) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
			`brewHops1Use` varchar(10) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
			`brewHops2Use` varchar(10) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
			`brewHops3Use` varchar(10) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
			`brewHops4Use` varchar(10) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
			`brewHops5Use` varchar(10) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
			`brewHops6Use` varchar(10) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
			`brewHops7Use` varchar(10) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
			`brewHops8Use` varchar(10) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
			`brewHops9Use` varchar(10) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
			`brewHops10Use` varchar(10) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
			`brewHops11Use` varchar(10) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
			`brewHops12Use` varchar(10) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
			`brewHops13Use` varchar(10) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
			`brewHops14Use` varchar(10) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
			`brewHops15Use` varchar(10) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
			`brewHops16Use` varchar(10) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
			`brewHops17Use` varchar(10) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
			`brewHops18Use` varchar(10) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
			`brewHops19Use` varchar(10) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
			`brewHops20Use` varchar(10) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
			`brewHops1Type` varchar(10) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
			`brewHops2Type` varchar(10) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
			`brewHops3Type` varchar(10) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
			`brewHops4Type` varchar(10) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
			`brewHops5Type` varchar(10) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
			`brewHops6Type` varchar(10) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
			`brewHops7Type` varchar(10) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
			`brewHops8Type` varchar(10) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
			`brewHops9Type` varchar(10) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
			`brewHops10Type` varchar(10) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
			`brewHops11Type` varchar(10) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
			`brewHops12Type` varchar(10) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
			`brewHops13Type` varchar(10) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
			`brewHops14Type` varchar(10) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
			`brewHops15Type` varchar(10) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
			`brewHops16Type` varchar(10) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
			`brewHops17Type` varchar(10) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
			`brewHops18Type` varchar(10) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
			`brewHops19Type` varchar(10) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
			`brewHops20Type` varchar(10) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
			`brewHops1Form` varchar(10) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
			`brewHops2Form` varchar(10) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
			`brewHops3Form` varchar(10) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
			`brewHops4Form` varchar(10) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
			`brewHops5Form` varchar(10) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
			`brewHops6Form` varchar(10) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
			`brewHops7Form` varchar(10) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
			`brewHops8Form` varchar(10) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
			`brewHops9Form` varchar(10) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
			`brewHops10Form` varchar(10) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
			`brewHops11Form` varchar(10) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
			`brewHops12Form` varchar(10) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
			`brewHops13Form` varchar(10) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
			`brewHops14Form` varchar(10) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
			`brewHops15Form` varchar(10) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
			`brewHops16Form` varchar(10) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
			`brewHops17Form` varchar(10) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
			`brewHops18Form` varchar(10) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
			`brewHops19Form` varchar(10) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
			`brewHops20Form` varchar(10) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
			`brewYeast` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
			`brewYeastMan` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
			`brewYeastForm` varchar(10) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
			`brewYeastType` varchar(10) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
			`brewYeastAmount` varchar(25) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
			`brewYeastStarter` char(1) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
			`brewYeastNutrients` mediumtext COLLATE utf8mb4_unicode_ci,
			`brewOG` varchar(10) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
			`brewFG` varchar(10) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
			`brewPrimary` varchar(10) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
			`brewPrimaryTemp` varchar(10) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
			`brewSecondary` varchar(10) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
			`brewSecondaryTemp` varchar(10) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
			`brewOther` varchar(10) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
			`brewOtherTemp` varchar(10) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
			`brewComments` mediumtext COLLATE utf8mb4_unicode_ci,
			`brewMashStep1Name` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
			`brewMashStep1Temp` char(3) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
			`brewMashStep1Time` char(3) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
			`brewMashStep2Name` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
			`brewMashStep2Temp` char(3) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
			`brewMashStep2Time` char(3) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
			`brewMashStep3Name` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
			`brewMashStep3Temp` char(3) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
			`brewMashStep3Time` char(3) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
			`brewMashStep4Name` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
			`brewMashStep4Temp` char(3) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
			`brewMashStep4Time` char(3) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
			`brewMashStep5Name` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
			`brewMashStep6Name` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
			`brewMashStep7Name` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
			`brewMashStep8Name` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
			`brewMashStep9Name` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
			`brewMashStep10Name` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
			`brewMashStep5Temp` char(3) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
			`brewMashStep6Temp` char(3) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
			`brewMashStep7Temp` char(3) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
			`brewMashStep8Temp` char(3) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
			`brewMashStep9Temp` char(3) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
			`brewMashStep10Temp` char(3) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
			`brewMashStep5Time` char(3) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
			`brewMashStep6Time` char(3) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
			`brewMashStep7Time` char(3) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
			`brewMashStep8Time` char(3) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
			`brewMashStep9Time` char(3) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
			`brewMashStep10Time` char(3) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
			`brewFinings` mediumtext COLLATE utf8mb4_unicode_ci,
			`brewWaterNotes` mediumtext COLLATE utf8mb4_unicode_ci,
			`brewBrewerID` varchar(8) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
			`brewCarbonationMethod` char(1) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
			`brewCarbonationVol` varchar(10) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
			`brewCarbonationNotes` mediumtext COLLATE utf8mb4_unicode_ci,
			`brewBoilHours` varchar(5) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
			`brewBoilMins` varchar(5) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
			`brewBrewerFirstName` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
			`brewBrewerLastName` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
			`brewExtract1Use` varchar(10) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
			`brewExtract2Use` varchar(10) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
			`brewExtract3Use` varchar(10) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
			`brewExtract4Use` varchar(10) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
			`brewExtract5Use` varchar(10) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
			`brewGrain1Use` varchar(10) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
			`brewGrain2Use` varchar(10) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
			`brewGrain3Use` varchar(10) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
			`brewGrain4Use` varchar(10) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
			`brewGrain5Use` varchar(10) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
			`brewGrain6Use` varchar(10) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
			`brewGrain7Use` varchar(10) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
			`brewGrain8Use` varchar(10) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
			`brewGrain9Use` varchar(10) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
			`brewGrain10Use` varchar(25) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
			`brewGrain11Use` varchar(25) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
			`brewGrain12Use` varchar(25) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
			`brewGrain13Use` varchar(25) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
			`brewGrain14Use` varchar(25) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
			`brewGrain15Use` varchar(25) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
			`brewGrain16Use` varchar(25) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
			`brewGrain17Use` varchar(25) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
			`brewGrain18Use` varchar(25) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
			`brewGrain19Use` varchar(25) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
			`brewGrain20Use` varchar(25) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
			`brewAddition1Use` varchar(10) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
			`brewAddition2Use` varchar(10) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
			`brewAddition3Use` varchar(10) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
			`brewAddition4Use` varchar(10) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
			`brewAddition5Use` varchar(10) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
			`brewAddition6Use` varchar(10) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
			`brewAddition7Use` varchar(10) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
			`brewAddition8Use` varchar(10) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
			`brewAddition9Use` varchar(10) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
			`brewAddition10Use` varchar(25) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
			`brewAddition11Use` varchar(25) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
			`brewAddition12Use` varchar(25) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
			`brewAddition13Use` varchar(25) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
			`brewAddition14Use` varchar(25) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
			`brewAddition15Use` varchar(25) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
			`brewAddition16Use` varchar(25) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
			`brewAddition17Use` varchar(25) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
			`brewAddition18Use` varchar(25) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
			`brewAddition19Use` varchar(25) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
			`brewAddition20Use` varchar(25) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
			`brewPaid` tinyint(1) DEFAULT NULL COMMENT '1=true; 0=false',
			`brewWinner` char(1) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
			`brewWinnerCat` varchar(3) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
			`brewInfoOptional` text COLLATE utf8mb4_unicode_ci,
			`brewWinnerPlace` varchar(3) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
			`brewBOSRound` char(1) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
			`brewBOSPlace` varchar(3) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
			`brewReceived` tinyint(1) DEFAULT NULL COMMENT '1=true; 0=false',
			`brewJudgingLocation` int(8) DEFAULT NULL,
			`brewCoBrewer` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
			`brewJudgingNumber` varchar(10) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
			`brewUpdated` timestamp NULL DEFAULT NULL COMMENT 'Timestamp of when the entry was last updated',
			`brewConfirmed` tinyint(1) DEFAULT NULL COMMENT '1=true - 2=false',
			`brewBoxNum` varchar(10) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
			PRIMARY KEY (`id`)
		) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE utf8mb4_unicode_ci;
		";

		mysqli_select_db($connection,$database);
		mysqli_real_escape_string($connection,$sql);
		$result = mysqli_query($connection,$sql) or die (mysqli_error($connection));
		 //echo "<p>".$sql."</p>";

		$output .= "<li class=\"list-group-item\"><span class=\"fa fa-lg fa-check text-success\"></span> The <strong>Entries</strong> table was installed successfully.</li>";


		// -------------------
		// Contacts Table
		// -------------------

		$sql = "
		CREATE TABLE IF NOT EXISTS `$contacts_db_table` (
			`id` int(11) NOT NULL AUTO_INCREMENT,
			`contactFirstName` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
			`contactLastName` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
			`contactPosition` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
			`contactEmail` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
			PRIMARY KEY (`id`)
		) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE utf8mb4_unicode_ci;
		";

		mysqli_select_db($connection,$database);
		mysqli_real_escape_string($connection,$sql);
		$result = mysqli_query($connection,$sql) or die (mysqli_error($connection));
		 //echo "<p>".$sql."</p>";

		$output .= "<li class=\"list-group-item\"><span class=\"fa fa-lg fa-check text-success\"></span> The <strong>Contacts</strong> table was installed successfully.</li>";

		// -------------------
		// Competition Info Table
		// -------------------

		$sql = "
		CREATE TABLE IF NOT EXISTS `$contest_info_db_table` (
			`id` int(11) NOT NULL AUTO_INCREMENT,
			`contestName` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
			`contestHost` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
			`contestHostWebsite` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
			`contestHostLocation` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
			`contestRegistrationOpen` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
			`contestRegistrationDeadline` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
			`contestEntryOpen` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
			`contestEntryDeadline` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
			`contestJudgeOpen` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
			`contestJudgeDeadline` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
			`contestRules` mediumtext COLLATE utf8mb4_unicode_ci,
			`contestAwardsLocation` mediumtext COLLATE utf8mb4_unicode_ci,
			`contestAwardsLocName` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
			`contestAwardsLocDate` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
			`contestAwardsLocTime` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
			`contestShippingOpen` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
			`contestShippingDeadline` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
			`contestEntryFee` float(6,2) DEFAULT NULL,
			`contestEntryFee2` float(6,2) DEFAULT NULL,
			`contestEntryFeeDiscount` char(1) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
			`contestEntryFeeDiscountNum` char(4) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
			`contestDropoffOpen` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
			`contestBottles` mediumtext COLLATE utf8mb4_unicode_ci,
			`contestShippingAddress` mediumtext COLLATE utf8mb4_unicode_ci,
			`contestShippingName` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
			`contestAwards` mediumtext COLLATE utf8mb4_unicode_ci,
			`contestLogo` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
			`contestBOSAward` mediumtext COLLATE utf8mb4_unicode_ci,
			`contestDropoffDeadline` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
			`contestEntryCap` int(8) DEFAULT NULL,
			`contestEntryFeePassword` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
			`contestEntryFeePasswordNum` int(11) DEFAULT NULL,
			`contestID` varchar(11) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
			`contestCircuit` mediumtext COLLATE utf8mb4_unicode_ci,
			`contestVolunteers` mediumtext COLLATE utf8mb4_unicode_ci,
			`contestCheckInPassword` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
			PRIMARY KEY (`id`)
		) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE utf8mb4_unicode_ci;
		";

		mysqli_select_db($connection,$database);
		mysqli_real_escape_string($connection,$sql);
		$result = mysqli_query($connection,$sql) or die (mysqli_error($connection));
		 //echo "<p>".$sql."</p>";

		$output .= "<li class=\"list-group-item\"><span class=\"fa fa-lg fa-check text-success\"></span> The <strong>Competition Info</strong> table was installed successfully.</li>";

		// -------------------
		// Drop-Off Table
		// -------------------

		$sql = "
		CREATE TABLE IF NOT EXISTS `$drop_off_db_table` (
			`id` int(11) NOT NULL AUTO_INCREMENT,
			`dropLocation` mediumtext COLLATE utf8mb4_unicode_ci,
			`dropLocationName` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
			`dropLocationPhone` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
			`dropLocationWebsite` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
			`dropLocationNotes` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
			PRIMARY KEY (`id`)
		) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE utf8mb4_unicode_ci;
		";

		mysqli_select_db($connection,$database);
		mysqli_real_escape_string($connection,$sql);
		$result = mysqli_query($connection,$sql) or die (mysqli_error($connection));
		 //echo "<p>".$sql."</p>";

		$output .= "<li class=\"list-group-item\"><span class=\"fa fa-lg fa-check text-success\"></span> The <strong>Drop-Off Locations</strong> table was installed successfully.</li>";

		// -------------------
		// Judging Assignments Table
		// -------------------

		$sql = "
		CREATE TABLE `$judging_assignments_db_table` (
			`id` int(11) NOT NULL AUTO_INCREMENT,
			`bid` int(11) DEFAULT NULL COMMENT 'id from brewer',
			`assignment` char(1) COLLATE utf8_unicode_ci DEFAULT NULL,
			`assignTable` int(11) DEFAULT NULL COMMENT 'id from judging_tables',
			`assignFlight` int(11) DEFAULT NULL,
			`assignRound` int(11) DEFAULT NULL,
			`assignLocation` int(11) DEFAULT NULL,
			`assignRoles` varchar(25) COLLATE utf8_unicode_ci DEFAULT NULL,
			PRIMARY KEY (`id`)
		) ENGINE = MYISAM ;
		";

		mysqli_select_db($connection,$database);
		mysqli_real_escape_string($connection,$sql);
		$result = mysqli_query($connection,$sql) or die (mysqli_error($connection));
		//echo "<p>".$sql."</p>";

		$output .= "<li class=\"list-group-item\"><span class=\"fa fa-lg fa-check text-success\"></span> The <strong>Judging Assignments</strong> table was installed successfully.</li>";

		// -------------------
		// Judging Flights Table
		// -------------------

		$sql = "
		CREATE TABLE IF NOT EXISTS `$judging_flights_db_table` (
			`id` int(11) NOT NULL AUTO_INCREMENT,
			`flightTable` int(8) DEFAULT NULL COMMENT 'id of Table from tables',
			`flightNumber` int(8) DEFAULT NULL,
			`flightEntryID` int(11) DEFAULT NULL COMMENT 'id of entry from the brewing table',
			`flightRound` int(8) DEFAULT NULL,
			PRIMARY KEY (`id`)
		) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE utf8mb4_unicode_ci;
		";

		mysqli_select_db($connection,$database);
		mysqli_real_escape_string($connection,$sql);
		$result = mysqli_query($connection,$sql) or die (mysqli_error($connection));
		 //echo "<p>".$sql."</p>";

		$output .= "<li class=\"list-group-item\"><span class=\"fa fa-lg fa-check text-success\"></span> The <strong>Judging Flights</strong> table was installed successfully.</li>";

		// -------------------
		// Judging Locations Table
		// -------------------

		$sql = "
		CREATE TABLE IF NOT EXISTS `$judging_locations_db_table` (
			`id` int(11) NOT NULL AUTO_INCREMENT,
			`judgingDate` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
			`judgingTime` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
			`judgingLocName` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
			`judgingLocation` mediumtext COLLATE utf8mb4_unicode_ci,
			`judgingRounds` int(11) DEFAULT '1' COMMENT 'number of rounds at location',
			PRIMARY KEY (`id`)
		) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE utf8mb4_unicode_ci;
		";

		mysqli_select_db($connection,$database);
		mysqli_real_escape_string($connection,$sql);
		$result = mysqli_query($connection,$sql) or die (mysqli_error($connection));
		 //echo "<p>".$sql."</p>";

		$output .= "<li class=\"list-group-item\"><span class=\"fa fa-lg fa-check text-success\"></span> The <strong>Judging Locations</strong> table was installed successfully.</li>";


		// -------------------
		// Judging Preferences Table
		// -------------------

		$sql = "
		CREATE TABLE IF NOT EXISTS `$judging_preferences_db_table` (
			`id` int(11) NOT NULL AUTO_INCREMENT,
			`jPrefsQueued` char(1) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
			`jPrefsFlightEntries` int(11) DEFAULT NULL COMMENT 'Maximum amount of entries per flight',
			`jPrefsMaxBOS` int(11) DEFAULT NULL COMMENT 'Maximum amount of places awarded for each BOS style type',
			`jPrefsRounds` int(11) DEFAULT NULL COMMENT 'Maximum amount of rounds per judging location',
			`jPrefsCapJudges` int(3) DEFAULT NULL,
			`jPrefsCapStewards` int(3) DEFAULT NULL,
			`jPrefsBottleNum` int(3) DEFAULT NULL,
			PRIMARY KEY (`id`)
		) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE utf8mb4_unicode_ci ;
		";
		mysqli_select_db($connection,$database);
		mysqli_real_escape_string($connection,$sql);
		$result = mysqli_query($connection,$sql) or die (mysqli_error($connection));

		$sql = "
		INSERT INTO `$judging_preferences_db_table` (`id`, `jPrefsQueued`, `jPrefsFlightEntries`, `jPrefsMaxBOS`, `jPrefsRounds`) VALUES (1, 0, 12, 7, 3);
		";

		mysqli_select_db($connection,$database);
		mysqli_real_escape_string($connection,$sql);
		$result = mysqli_query($connection,$sql) or die (mysqli_error($connection));
		 //echo "<p>".$sql."</p>";

		$output .= "<li class=\"list-group-item\"><span class=\"fa fa-lg fa-check text-success\"></span> The <strong>Judging Preferences</strong> table was installed successfully.</li>";


		// -------------------
		// Judging Scores Table
		// -------------------

		$sql = "
		CREATE TABLE IF NOT EXISTS `$judging_scores_db_table` (
			`id` int(11) NOT NULL AUTO_INCREMENT,
			`eid` int(11) DEFAULT NULL COMMENT 'entry id from brewing table',
			`bid` int(11) DEFAULT NULL COMMENT 'brewer id from brewer table',
			`scoreTable` int(11) DEFAULT NULL COMMENT 'id of table from judging_tables table',
			`scoreEntry` float DEFAULT NULL COMMENT 'Numerical score assigned by judges',
			`scorePlace` varchar(3) DEFAULT NULL COMMENT 'place of entry as assigned by judges',
			`scoreType` char(1) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
			`scoreMiniBOS` tinyint(1) DEFAULT NULL COMMENT 'Did the entry go to the MiniBOS? 1=Yes, 0=No',
			PRIMARY KEY (`id`)
		) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE utf8mb4_unicode_ci;
		";

		mysqli_select_db($connection,$database);
		mysqli_real_escape_string($connection,$sql);
		$result = mysqli_query($connection,$sql) or die (mysqli_error($connection));
		 //echo "<p>".$sql."</p>";

		$output .= "<li class=\"list-group-item\"><span class=\"fa fa-lg fa-check text-success\"></span> The <strong>Judging Scores</strong> table was installed successfully.</li>";


		// -------------------
		// Judging Scores BOS Table
		// -------------------

		$sql = "
		CREATE TABLE IF NOT EXISTS `$judging_scores_bos_db_table` (
			`id` int(11) NOT NULL AUTO_INCREMENT,
			`eid` int(11) DEFAULT NULL COMMENT 'entry id from brewing table',
			`bid` int(11) DEFAULT NULL COMMENT 'brewer id from brewer table',
			`scoreEntry` varchar(3) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
			`scorePlace` varchar(3) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
			`scoreType` char(1) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
			PRIMARY KEY (`id`)
		) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE utf8mb4_unicode_ci;
		";

		mysqli_select_db($connection,$database);
		mysqli_real_escape_string($connection,$sql);
		$result = mysqli_query($connection,$sql) or die (mysqli_error($connection));
		 //echo "<p>".$sql."</p>";

		$output .= "<li class=\"list-group-item\"><span class=\"fa fa-lg fa-check text-success\"></span> The <strong>Judging Scores BOS</strong> table was installed successfully.</li>";


		// -------------------
		// Judging Tables Table
		// -------------------

		$sql = "
		CREATE TABLE IF NOT EXISTS `$judging_tables_db_table` (
			`id` int(11) NOT NULL AUTO_INCREMENT,
			`tableName` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
			`tableStyles` mediumtext COLLATE utf8mb4_unicode_ci,
			`tableNumber` int(5) DEFAULT NULL COMMENT 'User defined for sorting',
			`tableLocation` int(5) DEFAULT NULL COMMENT 'Physical location of table (if more than one judging location) - relational to judging table',
			`tableJudges` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
			`tableStewards` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
			PRIMARY KEY (`id`)
		) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE utf8mb4_unicode_ci ;
		";

		mysqli_select_db($connection,$database);
		mysqli_real_escape_string($connection,$sql);
		$result = mysqli_query($connection,$sql) or die (mysqli_error($connection));
		 //echo "<p>".$sql."</p>";

		$output .= "<li class=\"list-group-item\"><span class=\"fa fa-lg fa-check text-success\"></span> The <strong>Judging Tables</strong> table was installed successfully.</li>";

		// -------------------
		// Mods Table
		// -------------------

		$sql = "CREATE TABLE IF NOT EXISTS `$mods_db_table` (
			`id` int(11) NOT NULL AUTO_INCREMENT,
			`mod_name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
			`mod_type` tinyint(2) DEFAULT NULL COMMENT 'Type of module: 0=informational 1=report 2=export 3=other',
			`mod_extend_function` tinyint(2) DEFAULT NULL COMMENT 'If the custom module extends a core function. 0=all 1=home 2=rules 3=volunteer 4=sponsors 5=contact 6=register 7=pay 8=list 9=admin',
			`mod_extend_function_admin` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
			`mod_filename` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
			`mod_description` mediumtext COLLATE utf8mb4_unicode_ci,
			`mod_permission` tinyint(1) DEFAULT NULL COMMENT 'Who has permission to view the module. 0=uber-admin 1=admin 2=all',
			`mod_rank` int(3) DEFAULT NULL COMMENT 'Rank order of the mod on the admin mods list',
			`mod_display_rank` tinyint(1) DEFAULT NULL COMMENT '0=normal 1=above default content',
			`mod_enable` tinyint(1) DEFAULT NULL COMMENT '0=no 1=yes',
			PRIMARY KEY (`id`)
		) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE utf8mb4_unicode_ci";
		mysqli_select_db($connection,$database);
		mysqli_real_escape_string($connection,$sql);
		$result = mysqli_query($connection,$sql) or die (mysqli_error($connection));

		// -------------------
		// Preferences Table
		// -------------------

		$sql = "
		CREATE TABLE IF NOT EXISTS `$preferences_db_table` (
			`id` int(11) NOT NULL AUTO_INCREMENT,
			`prefsTemp` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
			`prefsWeight1` varchar(20) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
			`prefsWeight2` varchar(20) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
			`prefsLiquid1` varchar(20) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
			`prefsLiquid2` varchar(20) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
			`prefsPaypal` char(1) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
			`prefsPaypalAccount` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
			`prefsPaypalIPN` tinyint(1) DEFAULT NULL,
			`prefsCurrency` varchar(20) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
			`prefsCash` char(1) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
			`prefsCheck` char(1) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
			`prefsCheckPayee` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
			`prefsTransFee` char(1) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
			`prefsCAPTCHA` tinyint(1) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
			`prefsGoogleAccount` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
			`prefsSponsors` char(1) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
			`prefsSponsorLogos` char(1) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
			`prefsSponsorLogoSize` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
			`prefsCompLogoSize` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
			`prefsDisplayWinners` char(1) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
			`prefsWinnerDelay` int(11) DEFAULT NULL COMMENT 'Hours after last judging date beginning time to delay displaying winners',
			`prefsWinnerMethod` int(11) DEFAULT NULL COMMENT 'Method comp uses to choose winners: 0=by table; 1=by category; 2=by sub-category',
			`prefsDisplaySpecial` char(1) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
			`prefsBOSMead` char(1) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
			`prefsBOSCider` char(1) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
			`prefsShowBestBrewer` int(1) DEFAULT NULL,
			`prefsBestBrewerTitle` varchar(255) DEFAULT NULL,
			`prefsShowBestClub` int(1) DEFAULT NULL,
			`prefsBestClubTitle` varchar(255) DEFAULT NULL,
			`prefsFirstPlacePts` int(1) DEFAULT 0,
			`prefsSecondPlacePts` int(1) DEFAULT 0,
			`prefsThirdPlacePts` int(1) DEFAULT 0,
			`prefsFourthPlacePts` int(1) DEFAULT 0,
			`prefsHMPts` int(1) DEFAULT 0,
			`prefsTieBreakRule1` varchar(255) DEFAULT NULL,
			`prefsTieBreakRule2` varchar(255) DEFAULT NULL,
			`prefsTieBreakRule3` varchar(255) DEFAULT NULL,
			`prefsTieBreakRule4` varchar(255) DEFAULT NULL,
			`prefsTieBreakRule5` varchar(255) DEFAULT NULL,
			`prefsTieBreakRule6` varchar(255) DEFAULT NULL,
			`prefsEntryForm` char(1) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
			`prefsRecordLimit` int(11) DEFAULT '300' COMMENT 'User defined record limit for using DataTables vs. PHP paging',
			`prefsRecordPaging` int(11) DEFAULT '100' COMMENT 'User defined per page record limit',
			`prefsProEdition` tinyint(1) DEFAULT NULL,
			`prefsTheme` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
			`prefsDateFormat` char(1) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
			`prefsContact` char(1) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
			`prefsTimeZone` int(11) DEFAULT NULL,
			`prefsEntryLimit` int(11) DEFAULT NULL,
			`prefsTimeFormat` tinyint(1) DEFAULT NULL,
			`prefsUserEntryLimit` varchar(4) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
			`prefsUserSubCatLimit` varchar(4) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
			`prefsUSCLEx` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
			`prefsUSCLExLimit` varchar(4) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
			`prefsPayToPrint` char(1) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
			`prefsHideRecipe` char(1) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
			`prefsUseMods` char(1) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
			`prefsSEF` char(1) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
			`prefsSpecialCharLimit` int(3) DEFAULT NULL COMMENT 'Character limit for special ingredients field',
			`prefsStyleSet` text COLLATE utf8mb4_unicode_ci,
			`prefsAutoPurge` tinyint(1) DEFAULT NULL,
			`prefsEntryLimitPaid` int(4) DEFAULT NULL,
			`prefsEmailRegConfirm` tinyint(1) DEFAULT NULL,
			`prefsLanguage` varchar(25) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
			`prefsSpecific` tinyint(1) DEFAULT NULL,
			`prefsDropOff` tinyint(1) DEFAULT NULL,
			`prefsShipping` tinyint(1) DEFAULT NULL,
			PRIMARY KEY (`id`)
		) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE utf8mb4_unicode_ci;
		";

		mysqli_select_db($connection,$database);
		mysqli_real_escape_string($connection,$sql);
		$result = mysqli_query($connection,$sql) or die (mysqli_error($connection));
		 //echo "<p>".$sql."</p>";

		$output .= "<li class=\"list-group-item\"><span class=\"fa fa-lg fa-check text-success\"></span> The <strong>Preferences</strong> table was installed successfully.</li>";

		// -------------------
		// Special Best Data Table
		// -------------------

		$sql = "
		CREATE TABLE IF NOT EXISTS `$special_best_data_db_table` (
			`id` int(11) NOT NULL AUTO_INCREMENT,
			`sid` int(11) DEFAULT NULL COMMENT 'relational to special_best_info table',
			`bid` int(11) DEFAULT NULL COMMENT 'relational to brewer table - bid row',
			`eid` int(11) DEFAULT NULL COMMENT 'relational to brewing table - id (entry number)',
			`sbd_place` int(11) DEFAULT NULL,
			`sbd_comments` mediumtext COLLATE utf8mb4_unicode_ci,
			PRIMARY KEY (`id`)
		) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE utf8mb4_unicode_ci;
		";

		mysqli_select_db($connection,$database);
		mysqli_real_escape_string($connection,$sql);
		$result = mysqli_query($connection,$sql) or die (mysqli_error($connection));
		 //echo "<p>".$sql."</p>";

		$output .= "<li class=\"list-group-item\"><span class=\"fa fa-lg fa-check text-success\"></span> The <strong>Special Best Data</strong> table was installed successfully.</li>";

		// -------------------
		// Special Best Info Table
		// -------------------

		$sql = "
		CREATE TABLE IF NOT EXISTS `$special_best_info_db_table` (
			`id` int(11) NOT NULL AUTO_INCREMENT,
			`sbi_name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
			`sbi_description` mediumtext COLLATE utf8mb4_unicode_ci,
			`sbi_places` int(11) DEFAULT NULL,
			`sbi_rank` int(11) DEFAULT NULL,
			`sbi_display_places` tinyint(1) DEFAULT NULL COMMENT '1=true; 0=false',
			PRIMARY KEY (`id`)
		) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE utf8mb4_unicode_ci;
		";

		mysqli_select_db($connection,$database);
		mysqli_real_escape_string($connection,$sql);
		$result = mysqli_query($connection,$sql) or die (mysqli_error($connection));
		 //echo "<p>".$sql."</p>";

		$output .= "<li class=\"list-group-item\"><span class=\"fa fa-lg fa-check text-success\"></span> The <strong>Special Best Info</strong> table was installed successfully.</li>";

		// -------------------
		// Sponsors Table
		// -------------------

		$sql = "
		CREATE TABLE IF NOT EXISTS `$sponsors_db_table` (
			`id` int(11) NOT NULL AUTO_INCREMENT,
			`sponsorName` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
			`sponsorURL` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
			`sponsorImage` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
			`sponsorText` mediumtext COLLATE utf8mb4_unicode_ci,
			`sponsorLocation` mediumtext COLLATE utf8mb4_unicode_ci,
			`sponsorLevel` mediumtext COLLATE utf8mb4_unicode_ci,
			`sponsorEnable` tinyint(1) DEFAULT NULL,
			PRIMARY KEY (`id`)
		) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE utf8mb4_unicode_ci;
		";

		mysqli_select_db($connection,$database);
		mysqli_real_escape_string($connection,$sql);
		$result = mysqli_query($connection,$sql) or die (mysqli_error($connection));
		 //echo "<p>".$sql."</p>";

		$output .= "<li class=\"list-group-item\"><span class=\"fa fa-lg fa-check text-success\"></span> The <strong>Sponsors</strong> table was installed successfully.</li>";

		// -------------------
		// Staff Table
		// -------------------

		$sql = "
		CREATE TABLE IF NOT EXISTS `$staff_db_table` (
			`id` int(11) NOT NULL AUTO_INCREMENT,
			`uid` int(11) DEFAULT NULL COMMENT 'user''s id from user table',
			`staff_judge` tinyint(2) DEFAULT '0' COMMENT '0=no; 1=yes',
			`staff_judge_bos` tinyint(2) DEFAULT '0' COMMENT '0=no; 1=yes',
			`staff_steward` tinyint(2) DEFAULT '0' COMMENT '0=no; 1=yes',
			`staff_organizer` tinyint(2) DEFAULT '0' COMMENT '0=no; 1=yes',
			`staff_staff` tinyint(2) DEFAULT '0' COMMENT '0=no; 1=yes',
			PRIMARY KEY (`id`)
		) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE utf8mb4_unicode_ci;
		";

		mysqli_select_db($connection,$database);
		mysqli_real_escape_string($connection,$sql);
		$result = mysqli_query($connection,$sql) or die (mysqli_error($connection));
		 //echo "<p>".$sql."</p>";

		$output .= "<li class=\"list-group-item\"><span class=\"fa fa-lg fa-check text-success\"></span> The <strong>Staff</strong> table was installed successfully.</li>";

		// -------------------
		// Styles Table
		// -------------------

		$updateSQL = "DROP TABLE IF EXISTS ".$prefix."styles; ";
		mysqli_select_db($connection,$database);
		mysqli_real_escape_string($connection,$updateSQL);
		$result = mysqli_query($connection,$updateSQL) or die (mysqli_error($connection));

		$updateSQL = "
		CREATE TABLE ".$prefix."styles (
			`id` int(11) NOT NULL AUTO_INCREMENT,
			`brewStyleNum` varchar(3) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
			`brewStyle` varchar(250) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
			`brewStyleCategory` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
			`brewStyleOG` varchar(20) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
			`brewStyleOGMax` varchar(25) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
			`brewStyleFG` varchar(20) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
			`brewStyleFGMax` varchar(25) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
			`brewStyleABV` varchar(250) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
			`brewStyleABVMax` varchar(25) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
			`brewStyleIBU` varchar(250) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
			`brewStyleIBUMax` varchar(25) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
			`brewStyleSRM` varchar(250) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
			`brewStyleSRMMax` varchar(25) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
			`brewStyleType` varchar(25) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
			`brewStyleInfo` mediumtext COLLATE utf8mb4_unicode_ci,
			`brewStyleLink` varchar(200) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
			`brewStyleGroup` varchar(3) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
			`brewStyleActive` char(1) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
			`brewStyleOwn` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
			`brewStyleVersion` varchar(20) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
			`brewStyleReqSpec` tinyint(1) DEFAULT NULL COMMENT 'Does the style require special ingredients be input? 1=yes 0=no',
			`brewStyleStrength` int(1) DEFAULT NULL COMMENT 'Requires strength? 0=No, 1=Yes',
			`brewStyleCarb` int(1) DEFAULT NULL COMMENT 'Requires carbonation? 0=No, 1=Yes',
			`brewStyleSweet` int(1) DEFAULT NULL COMMENT 'Requires sweetness? 0=No, 1=Yes',
			`brewStyleTags` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
			`brewStyleComEx` mediumtext COLLATE utf8mb4_unicode_ci,
			`brewStyleEntry` mediumtext COLLATE utf8mb4_unicode_ci,
			PRIMARY KEY (`id`)
		)
		ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE utf8mb4_unicode_ci;
		";
		mysqli_select_db($connection,$database);
		mysqli_real_escape_string($connection,$updateSQL);
		$result = mysqli_query($connection,$updateSQL) or die (mysqli_error($connection));


		$updateSQL = "INSERT INTO ".$prefix."styles (id, brewStyleNum, brewStyle, brewStyleCategory, brewStyleOG, brewStyleOGMax, brewStyleFG, brewStyleFGMax, brewStyleABV, brewStyleABVMax, brewStyleIBU, brewStyleIBUMax, brewStyleSRM, brewStyleSRMMax, brewStyleType, brewStyleInfo, brewStyleLink, brewStyleGroup, brewStyleActive, brewStyleOwn, brewStyleVersion, brewStyleReqSpec, brewStyleStrength, brewStyleCarb, brewStyleSweet, brewStyleTags, brewStyleComEx, brewStyleEntry) VALUES ";
		$updateSQL .= "(1, 'A', 'Lite American Lager', 'Light Lager', '1.028', '1.04', '0.998', '1.008', '3.2', '4.2', '8', '12', '2', '3', 'Lager', 'A lower gravity and lower calorie beer than standard international lagers. Strong flavors are a fault. Designed to appeal to the broadest range of the general public as possible.', 'http://www.bjcp.org/2008styles/style01.php#1a', '01', 'Y', 'bcoe', 'BJCP2008', 0, 0, 0, 0, '', 'Bitburger Light, Sam Adams Light, Heineken Premium Light, Miller Lite, Bud Light, Coors Light, Baltika 1 Light, Old Milwaukee Light, Amstel Light. ', ''), ";
		$updateSQL .= "(2, 'B', 'Standard American Lager', 'Light Lager', '1.04', '1.05', '1.004', '1.01', '4.2', '5.3', '8', '15', '2', '4', 'Lager', 'Strong flavors are a fault. An international style including the standard mass-market lager from most countries.', 'http://www.bjcp.org/2008styles/style01.php#1b', '01', 'Y', 'bcoe', 'BJCP2008', 0, 0, 0, 0, '', 'Pabst Blue Ribbon, Miller High Life, Budweiser, Baltika #3 Classic, Kirin Lager, Grain Belt Premium Lager, Molson Golden, Labatt Blue, Coors Original, Foster&rsquo;s Lager.', ''), ";
		$updateSQL .= "(3, 'C', 'Premium American Lager', 'Light Lager', '1.046', '1.056', '1.008', '1.012', '4.6', '6', '15', '25', '2', '6', 'Lager', 'Premium beers tend to have fewer adjuncts than standard/lite lagers, and can be all-malt. Strong flavors are a fault, but premium lagers have more flavor than standard/lite lagers. A broad style of international mass-market lagers ranging from up-scale American lagers to the typical &rsquo;import&rsquo; or &rsquo;green bottle&rsquo; international beers found in America.', 'http://www.bjcp.org/2008styles/style01.php#1c', '01', 'Y', 'bcoe', 'BJCP2008', 0, 0, 0, 0, '', 'Full Sail Session Premium Lager, Miller Genuine Draft, Corona Extra, Michelob, Coors Extra Gold, Birra Moretti, Heineken, Beck&rsquo;s, Stella Artois, Red Stripe, Singha.', ''), ";
		$updateSQL .= "(4, 'D', 'Munich Helles', 'Light Lager', '1.045', '1.051', '1.008', '1.012', '4.7', '5.4', '16', '22', '3', '5', 'Lager', 'Unlike Pilsner but like its cousin, Munich Dunkel, Helles is a malt-accentuated beer that is not overly sweet, but rather focuses on malt flavor with underlying hop bitterness in a supporting role.', 'http://www.bjcp.org/2008styles/style01.php#1d', '01', 'Y', 'bcoe', 'BJCP2008', 0, 0, 0, 0, '', 'Weihenstephaner Original, Hacker-Pschorr Munchner Gold, Burgerbrau Wolznacher Hell Naturtrub, Mahr&rsquo;s Hell, Paulaner Premium Lager, Spaten Premium Lager, Stoudt&rsquo;s Gold Lager.', ''), ";
		$updateSQL .= "(5, 'E', 'Dortmunder Export', 'Light Lager', '1.048', '1.056', '1.01', '1.015', '4.8', '6', '23', '30', '4', '6', 'Lager', 'Brewed to a slightly higher starting gravity than other light lagers, providing a firm malty body and underlying maltiness to complement the sulfate-accentuated hop bitterness. The term &rsquo;Export&rsquo; is a beer strength category under German beer tax law, and is not strictly synonymous with the &rsquo;Dortmunder&rsquo; style. Beer from other cities or regions can be brewed to Export strength, and labeled as such.', 'http://www.bjcp.org/2008styles/style01.php#1e', '01', 'Y', 'bcoe', 'BJCP2008', 0, 0, 0, 0, '', 'DAB Export, Dortmunder Union Export, Dortmunder Kronen, Ayinger Jahrhundert, Great Lakes Dortmunder Gold, Barrel House Duveneck&rsquo;s Dortmunder, Bell&rsquo;s Lager, Dominion Lager, Gordon Biersch Golden Export, Flensburger Gold.', ''), ";
		$updateSQL .= "(6, 'A', 'German Pilsner (Pils)', 'Pilsner', '1.044', '1.05', '1.008', '1.013', '4.4', '5.2', '25', '45', '2', '5', 'Lager', 'Drier and crisper than a Bohemian Pilsener with a bitterness that tends to linger more in the aftertaste due to higher attenuation and higher-sulfate water. Lighter in body and color, and with higher carbonation than a Bohemian Pilsener. Modern examples of German pilsners tend to become paler in color, drier in finish, and more bitter as you move from South to North in Germany. ', 'http://www.bjcp.org/2008styles/style02.php#1a', '02', 'Y', 'bcoe', 'BJCP2008', 0, 0, 0, 0, '', 'Victory Prima Pils, Bitburger, Warsteiner, Trumer Pils, Old Dominion Tupper&rsquo;s Hop Pocket Pils, Konig Pilsener, Jever Pils, Left Hand Polestar Pilsner, Holsten Pils, Spaten Pils, Brooklyn Pilsner. ', ''), ";
		$updateSQL .= "(7, 'B', 'Bohemian Pilsener', 'Pilsner', '1.044', '1.056', '1.013', '1.017', '4.2', '5.4', '35', '45', '3.5', '6', 'Lager', 'Uses Moravian malted barley and a decoction mash for rich, malt character. Saaz hops and low sulfate, low carbonate water provide a distinctively soft, rounded hop profile. Traditional yeast sometimes can provide a background diacetyl note. Dextrins provide additional body, and diacetyl enhances the perception of a fuller palate. ', 'http://www.bjcp.org/2008styles/style02.php#1b', '02', 'Y', 'bcoe', 'BJCP2008', 0, 0, 0, 0, '', 'Pilsner Urquell, Kruovice Imperial 12&deg;, Budweiser Budvar (Czechvar in the US), Czech Rebel, Staropramen, Gambrinus Pilsner, Zlaty Bazant Golden Pheasant, Dock Street Bohemian Pilsner. ', ''), ";
		$updateSQL .= "(8, 'C', 'Classic American Pilsner', 'Pilsner', '1.044', '1.06', '1.01', '1.015', '4.5', '6', '25', '40', '3', '6', 'Lager', 'A substantial Pilsner that can stand up to the classic European Pilsners, but exhibiting the native American grains and hops available to German brewers who initially brewed it in the USA. Refreshing, but with the underlying malt and hops that stand out when compared to other modern American light lagers. Maize lends a distinctive grainy sweetness. Rice contributes a crisper, more neutral character. A version of Pilsner brewed in the USA by immigrant German brewers who brought the process and yeast with them when they settled in America. They worked with the ingredients that were native to America to create a unique version of the original Pilsner. This style died out after Prohibition but was resurrected as a home-brewed style by advocates of the hobby.', 'http://www.bjcp.org/2008styles/style02.php#1c', '02', 'Y', 'bcoe', 'BJCP2008', 0, 0, 0, 0, '', 'Occasional brewpub and microbrewery specials. ', ''), ";
		$updateSQL .= "(9, 'A', 'Vienna Lager', 'European Amber Lager', '1.046', '1.052', '1.01', '1.014', '4.5', '5.7', '18', '30', '10', '16', 'Lager', 'Characterized by soft, elegant maltiness that dries out in the finish to avoid becoming sweet.', 'http://www.bjcp.org/2008styles/style03.php#1a', '03', 'Y', 'bcoe', 'BJCP2008', 0, 0, 0, 0, '', 'Great Lakes Eliot Ness, Boulevard Bob&rsquo;s 47 Munich-Style Lager, Negra Modelo, Old Dominion Aviator Amber Lager, Gordon Biersch Vienna Lager, Capital Wisconsin Amber, Olde Saratoga Lager, Penn Pilsner. ', ''), ";
		$updateSQL .= "(10, 'B', 'Oktoberfest/Marzen', 'European Amber Lager', '1.05', '1.057', '1.012', '1.016', '4.8', '5.7', '20', '28', '7', '14', 'Lager', 'Smooth, clean, and rather rich, with a depth of malt character. This is one of the classic malty styles, with a maltiness that is often described as soft, complex, and elegant but never cloying.', 'http://www.bjcp.org/2008styles/style03.php#1b', '03', 'Y', 'bcoe', 'BJCP2008', 0, 0, 0, 0, '', 'Paulaner Oktoberfest, Ayinger Oktoberfest-Marzen, Hacker-Pschorr Original Oktoberfest, Hofbrau Oktoberfest, Victory Festbier, Great Lakes Oktoberfest, Spaten Oktoberfest, Capital Oktoberfest, Gordon Biersch Marzen, Goose Island Oktoberfest, Samuel Adams Oktoberfest. ', ''); ";

		mysqli_select_db($connection,$database);
		mysqli_real_escape_string($connection,$updateSQL);
		$result = mysqli_query($connection,$updateSQL) or die (mysqli_error($connection));

		$updateSQL = "INSERT INTO ".$prefix."styles (id, brewStyleNum, brewStyle, brewStyleCategory, brewStyleOG, brewStyleOGMax, brewStyleFG, brewStyleFGMax, brewStyleABV, brewStyleABVMax, brewStyleIBU, brewStyleIBUMax, brewStyleSRM, brewStyleSRMMax, brewStyleType, brewStyleInfo, brewStyleLink, brewStyleGroup, brewStyleActive, brewStyleOwn, brewStyleVersion, brewStyleReqSpec, brewStyleStrength, brewStyleCarb, brewStyleSweet, brewStyleTags, brewStyleComEx, brewStyleEntry) VALUES ";
		$updateSQL .= "(11, 'A', 'Dark American Lager', 'Dark Lager', '1.044', '1.056', '1.008', '1.012', '4.2', '6', '8', '20', '14', '22', 'Lager', 'A somewhat sweeter version of standard/premium lager with a little more body and flavor.', 'http://www.bjcp.org/2008styles/style04.php#1a', '04', 'Y', 'bcoe', 'BJCP2008', 0, 0, 0, 0, '', 'Dixie Blackened Voodoo, Shiner Bock, San Miguel Dark, Baltika #4, Beck&rsquo;s Dark, Saint Pauli Girl Dark, Warsteiner Dunkel, Heineken Dark Lager, Crystal Diplomat Dark Beer. ', ''), ";
		$updateSQL .= "(12, 'B', 'Munich Dunkel', 'Dark Lager', '1.048', '1.056', '1.01', '1.016', '4.5', '5.6', '18', '28', '14', '28', 'Lager', 'Characterized by depth and complexity of Munich malt and the accompanying melanoidins. Rich Munich flavors, but not as intense as a bock or as roasted as a schwarzbier.', 'http://www.bjcp.org/2008styles/style04.php#1b', '04', 'Y', 'bcoe', 'BJCP2008', 0, 0, 0, 0, '', 'Ayinger Altbairisch Dunkel, Hacker-Pschorr Alt Munich Dark, Paulaner Alt Munchner Dunkel, Weltenburger Kloster Barock-Dunkel, Ettaler Kloster Dunkel, Hofbrau Dunkel, Penn Dark Lager, Konig Ludwig Dunkel, Capital Munich Dark, Harpoon Munich-type Dark Beer, Gordon Biersch Dunkels, Dinkel Acker Dark. In Bavaria, Ettaler Dunkel, Lowenbrau Dunkel, Hartmann Dunkel, Kneitinger Dunkel, Augustiner Dunkel.', ''), ";
		$updateSQL .= "(13, 'C', 'Schwarzbier', 'Dark Lager', '1.046', '1.052', '1.01', '1.016', '4.4', '5.4', '22', '32', '17', '30', 'Lager', 'A dark German lager that balances roasted yet smooth malt flavors with moderate hop bitterness.', 'http://www.bjcp.org/2008styles/style04.php#1c', '04', 'Y', 'bcoe', 'BJCP2008', 0, 0, 0, 0, '', 'Kostritzer Schwarzbier, Kulmbacher Monchshof Premium Schwarzbier, Samuel Adams Black Lager, Kruovice Cerne, Original Badebier, Einbecker Schwarzbier, Gordon Biersch Schwarzbier, Weeping Radish Black Radish Dark Lager, Sprecher Black Bavarian.', ''), ";
		$updateSQL .= "(14, 'A', 'Maibock/Helles Bock', 'Bock', '1.064', '1.072', '1.011', '1.018', '6.3', '7.4', '23', '35', '6', '11', 'Lager', 'A relatively pale, strong, malty lager beer. Designed to walk a fine line between blandness and too much color. Hop character is generally more apparent than in other bocks.', 'http://www.bjcp.org/2008styles/style05.php#1a', '05', 'Y', 'bcoe', 'BJCP2008', 0, 0, 0, 0, '', 'Ayinger Maibock, Mahr&rsquo;s Bock, Hacker-Pschorr Hubertus Bock, Capital Maibock, Einbecker Mai-Urbock, Hofbrau Maibock, Victory St. Boisterous, Gordon Biersch Blonde Bock, Smuttynose Maibock.', ''), ";
		$updateSQL .= "(15, 'B', 'Traditional Bock', 'Bock', '1.064', '1.072', '1.013', '1.019', '6.3', '7.2', '20', '27', '14', '22', 'Lager', 'A dark, strong, malty lager beer.', 'http://www.bjcp.org/2008styles/style05.php#1b', '05', 'Y', 'bcoe', 'BJCP2008', 0, 0, 0, 0, '', 'Einbecker Ur-Bock Dunkel, Pennsylvania Brewing St. Nick Bock, Aass Bock, Great Lakes Rockefeller Bock, Stegmaier Brewhouse Bock.', ''), ";
		$updateSQL .= "(16, 'C', 'Doppelbock', 'Bock', '1.072', '1.096', '1.016', '1.024', '7', '10', '16', '25', '6', '25', 'Lager', 'A very strong and rich lager. A bigger version of either a traditional bock or a helles bock.', 'http://www.bjcp.org/2008styles/style05.php#1c', '05', 'Y', 'bcoe', 'BJCP2008', 0, 0, 0, 0, '', 'Paulaner Salvator, Ayinger Celebrator, Weihenstephaner Korbinian, Andechser Doppelbock Dunkel, Spaten Optimator, Tucher Bajuvator, Weltenburger Kloster Asam-Bock, Capital Autumnal Fire, EKU 28, Eggenberg Urbock 23º, Bell&rsquo;s Consecrator, Moretti La Rossa, Samuel Adams Double Bock.', ''), ";
		$updateSQL .= "(17, 'D', 'Eisbock', 'Bock', '1.078', '1.12', '1.02', '1.035', '9', '14', '25', '35', '18', '30', 'Lager', 'An extremely strong, full and malty dark lager.', 'http://www.bjcp.org/2008styles/style05.php#1d', '05', 'Y', 'bcoe', 'BJCP2008', 0, 0, 0, 0, '', 'Kulmbacher Reichelbrau Eisbock, Eggenberg Urbock Dunkel Eisbock, Niagara Eisbock, Capital Eisphyre, Southampton Eisbock.', ''), ";
		$updateSQL .= "(18, 'A', 'Cream Ale', 'Light Hybrid Beer', '1.042', '1.055', '1.006', '1.012', '4.2', '5.6', '15', '20', '2.5', '5', 'Mixed', 'A clean, well-attenuated, flavorful American lawnmower beer.', 'http://www.bjcp.org/2008styles/style06.php#1a', '06', 'Y', 'bcoe', 'BJCP2008', 0, 0, 0, 0, '', 'Genesee Cream Ale, Little Kings Cream Ale (Hudepohl), Anderson Valley Summer Solstice Cerveza Crema, Sleeman Cream Ale, New Glarus Spotted Cow, Wisconsin Brewing Whitetail Cream Ale.', ''), ";
		$updateSQL .= "(19, 'B', 'Blonde Ale', 'Light Hybrid Beer', '1.038', '1.054', '1.008', '1.013', '4.2', '5.5', '15', '28', '3', '6', 'Mixed', 'Easy-drinking, approachable, malt-oriented American craft beer.', 'http://www.bjcp.org/2008styles/style06.php#1b', '06', 'Y', 'bcoe', 'BJCP2008', 0, 0, 0, 0, '', 'Pelican Kiwanda Cream Ale, Russian River Aud Blonde, Rogue Oregon Golden Ale, Widmer Blonde Ale, Fuller&rsquo;s Summer Ale, Hollywood Blonde, Redhook Blonde.', ''), ";
		$updateSQL .= "(20, 'C', 'Kolsch', 'Light Hybrid Beer', '1.044', '1.05', '1.007', '1.011', '4.4', '5.2', '20', '30', '3.5', '5', 'Mixed', 'A clean, crisp, delicately balanced beer usually with very subtle fruit flavors and aromas. Subdued maltiness throughout leads to a pleasantly refreshing tang in the finish. To the untrained taster easily mistaken for a light lager, a somewhat subtle pilsner, or perhaps a blonde ale.', 'http://www.bjcp.org/2008styles/style06.php#1c', '06', 'Y', 'bcoe', 'BJCP2008', 0, 0, 0, 0, '', 'Available in Cologne only: PJ Fruh, Hellers, Malzmuhle, Paeffgen, Sion, Peters, Dom; import versions available in parts of North America: Reissdorf, Gaffel; Non-German versions: Eisenbahn Dourada, Goose Island Summertime, Alaska Summer Ale, Harpoon Summer Beer, New Holland Lucid, Saint Arnold Fancy Lawnmower, Capitol City Capitol Kolsch, Shiner Kolsch.', ''); ";

		mysqli_select_db($connection,$database);
		mysqli_real_escape_string($connection,$updateSQL);
		$result = mysqli_query($connection,$updateSQL) or die (mysqli_error($connection));

		$updateSQL = "INSERT INTO ".$prefix."styles (id, brewStyleNum, brewStyle, brewStyleCategory, brewStyleOG, brewStyleOGMax, brewStyleFG, brewStyleFGMax, brewStyleABV, brewStyleABVMax, brewStyleIBU, brewStyleIBUMax, brewStyleSRM, brewStyleSRMMax, brewStyleType, brewStyleInfo, brewStyleLink, brewStyleGroup, brewStyleActive, brewStyleOwn, brewStyleVersion, brewStyleReqSpec, brewStyleStrength, brewStyleCarb, brewStyleSweet, brewStyleTags, brewStyleComEx, brewStyleEntry) VALUES ";
		$updateSQL .= "(21, 'D', 'American Wheat or Rye Beer', 'Light Hybrid Beer', '1.04', '1.055', '1.008', '1.013', '4', '5.5', '15', '30', '3', '6', 'Mixed', 'Refreshing wheat or rye beers that can display more hop character and less yeast character than their German cousins.', 'http://www.bjcp.org/2008styles/style06.php#1d', '06', 'Y', 'bcoe', 'BJCP2008', 1, 0, 0, 0, '', 'Bell&rsquo;s Oberon, Harpoon UFO Hefeweizen, Three Floyds Gumballhead, Pyramid Hefe-Weizen, Widmer Hefeweizen, Sierra Nevada Unfiltered Wheat Beer, Anchor Summer Beer, Redhook Sunrye, Real Ale Full Moon Pale Rye.', 'Entrant must specify whether wheat or rye is used.'), ";
		$updateSQL .= "(22, 'A', 'Northern German Altbier', 'Amber Hybrid Beer', '1.046', '1.054', '1.01', '1.015', '4.5', '5.2', '25', '40', '13', '19', 'Ale', 'A very clean and relatively bitter beer, balanced by some malt character. Generally darker, sometimes more caramelly, and usually sweeter and less bitter than Dusseldorf Altbier.', 'http://www.bjcp.org/2008styles/style07.php#1a', '07', 'Y', 'bcoe', 'BJCP2008', 0, 0, 0, 0, '', 'DAB Traditional, Hannen Alt, Schwelmer Alt, Grolsch Amber, Alaskan Amber, Long Trail Ale, Otter Creek Copper Ale, Schmaltz&rsquo; Alt. ', ''), ";
		$updateSQL .= "(23, 'B', 'California Common Beer', 'Amber Hybrid Beer', '1.048', '1.054', '1.011', '1.014', '4.5', '5.5', '30', '45', '10', '14', 'Ale', 'A lightly fruity beer with firm, grainy maltiness, interesting toasty and caramel flavors, and showcasing the signature Northern Brewer varietal hop character.', 'http://www.bjcp.org/2008styles/style07.php#1b', '07', 'Y', 'bcoe', 'BJCP2008', 0, 0, 0, 0, '', 'Anchor Steam, Southampton Steem Beer, Flying Dog Old Scratch Amber Lager. ', ''), ";
		$updateSQL .= "(24, 'C', 'Dusseldorf Altbier', 'Amber Hybrid Beer', '1.046', '1.054', '1.01', '1.015', '4.5', '5.2', '35', '50', '11', '17', 'Ale', 'A well balanced, bitter yet malty, clean, smooth, well-attenuated copper-colored German ale.', 'http://www.bjcp.org/2008styles/style07.php#1c', '07', 'Y', 'bcoe', 'BJCP2008', 0, 0, 0, 0, '', 'Altstadt brewpubs: Zum Uerige, Im Fuchschen, Schumacher, Zum Schlussel; other examples: Diebels Alt, Schlosser Alt, Frankenheim Alt.', ''), ";
		$updateSQL .= "(25, 'A', 'Standard/Ordinary Bitter', 'English Pale Ale', '1.032', '1.04', '1.007', '1.011', '3.2', '3.8', '25', '35', '4', '14', 'Ale', 'Low gravity, low alcohol levels and low carbonation make this an easy-drinking beer. Some examples can be more malt balanced, but this should not override the overall bitter impression. Drinkability is a critical component of the style; emphasis is still on the bittering hop addition as opposed to the aggressive middle and late hopping seen in American ales.', 'http://www.bjcp.org/2008styles/style08.php#1a', '08', 'Y', 'bcoe', 'BJCP2008', 0, 0, 0, 0, '', 'Fuller&rsquo;s Chiswick Bitter, Adnams Bitter, Young&rsquo;s Bitter, Greene King IPA, Oakham Jeffrey Hudson Bitter (JHB), Brain&rsquo;s Bitter, Tetley&rsquo;s Original Bitter, Brakspear Bitter, Boddington&rsquo;s Pub Draught.', ''), ";
		$updateSQL .= "(26, 'B', 'Special/Best/Premium Bitter', 'English Pale Ale', '1.04', '1.048', '1.008', '1.012', '3.8', '4.6', '25', '40', '5', '16', 'Ale', 'A flavorful, yet refreshing, session beer. Some examples can be more malt balanced, but this should not override the overall bitter impression. Drinkability is a critical component of the style; emphasis is still on the bittering hop addition as opposed to the aggressive middle and late hopping seen in American ales.', 'http://www.bjcp.org/2008styles/style08.php#1b', '08', 'Y', 'bcoe', 'BJCP2008', 0, 0, 0, 0, '', 'Fuller&rsquo;s London Pride, Coniston Bluebird Bitter, Timothy Taylor Landlord, Adnams SSB, Young&rsquo;s Special, Shepherd Neame Masterbrew Bitter, Greene King Ruddles County Bitter, RCH Pitchfork Rebellious Bitter, Brains SA, Black Sheep Best Bitter, Goose Island Honkers Ale, Rogue Younger&rsquo;s Special Bitter.', ''), ";
		$updateSQL .= "(27, 'C', 'Extra Special/Strong Bitter (English Pale Ale)', 'English Pale Ale', '1.048', '1.06', '1.01', '1.016', '4.6', '6.2', '30', '50', '6', '18', 'Ale', 'An average-strength to moderately-strong English ale. The balance may be fairly even between malt and hops to somewhat bitter. Drinkability is a critical component of the style; emphasis is still on the bittering hop addition as opposed to the aggressive middle and late hopping seen in American ales. A rather broad style that allows for considerable interpretation by the brewer.', 'http://www.bjcp.org/2008styles/style08.php#1c', '08', 'Y', 'bcoe', 'BJCP2008', 0, 0, 0, 0, '', 'Fullers ESB, Adnams Broadside, Shepherd Neame Bishop&rsquo;s Finger, Young&rsquo;s Ram Rod, Samuel Smith&rsquo;s Old Brewery Pale Ale, Bass Ale, Whitbread Pale Ale, Shepherd Neame Spitfire, Marston&rsquo;s Pedigree, Black Sheep Ale, Vintage Henley, Mordue Workie Ticket, Morland Old Speckled Hen, Greene King Abbot Ale, Bateman&rsquo;s XXXB, Gale&rsquo;s Hordean Special Bitter (HSB), Ushers 1824 Particular Ale, Hopback Summer Lightning, Great Lakes Moondog Ale, Shipyard Old Thumper, Alaskan ESB, Geary&rsquo;s Pale Ale, Cooperstown Old Slugger, Anderson Valley Boont ESB, Avery 14&rsquo;er ESB, Redhook ESB. ', ''), ";
		$updateSQL .= "(28, 'A', 'Scottish Light 60/-', 'Scottish and Irish Ale', '1.03', '1.035', '1.01', '1.013', '2.5', '3.2', '10', '20', '9', '17', 'Ale', 'Cleanly malty with a drying finish, perhaps a few esters, and on occasion a faint bit of peaty earthiness (smoke). Most beers finish fairly dry considering their relatively sweet palate, and as such have a different balance than strong Scotch ales.', 'http://www.bjcp.org/2008styles/style09.php#1a', '09', 'Y', 'bcoe', 'BJCP2008', 0, 0, 0, 0, '', 'Belhaven 60/-, McEwan&rsquo;s 60/-, Maclay 60/- Light (all are cask-only products not exported to the US).', ''), ";
		$updateSQL .= "(29, 'B', 'Scottish Heavy 70/-', 'Scottish and Irish Ale', '1.035', '1.04', '1.01', '1.015', '3.2', '3.9', '10', '25', '9', '17', 'Ale', 'Cleanly malty with a drying finish, perhaps a few esters, and on occasion a faint bit of peaty earthiness (smoke). Most beers finish fairly dry considering their relatively sweet palate, and as such have a different balance than strong Scotch ales.', 'http://www.bjcp.org/2008styles/style09.php#1b', '09', 'Y', 'bcoe', 'BJCP2008', 0, 0, 0, 0, '', 'Caledonian 70/- (Caledonian Amber Ale in the US), Belhaven 70/-, Orkney Raven Ale, Maclay 70/-, Tennents Special, Broughton Greenmantle Ale.', ''), ";
		$updateSQL .= "(30, 'C', 'Scottish Export 80/-', 'Scottish and Irish Ale', '1.04', '1.054', '1.01', '1.016', '3.9', '5', '15', '30', '9', '17', 'Ale', 'Cleanly malty with a drying finish, perhaps a few esters, and on occasion a faint bit of peaty earthiness (smoke). Most beers finish fairly dry considering their relatively sweet palate, and as such have a different balance than strong Scotch ales.', 'http://www.bjcp.org/2008styles/style09.php#1c', '09', 'Y', 'bcoe', 'BJCP2008', 0, 0, 0, 0, '', 'Orkney Dark Island, Caledonian 80/- Export Ale, Belhaven 80/- (Belhaven Scottish Ale in the US), Southampton 80 Shilling, Broughton Exciseman&rsquo;s 80/-, Belhaven St. Andrews Ale, McEwan&rsquo;s Export (IPA), Inveralmond Lia Fail, Broughton Merlin&rsquo;s Ale, Arran Dark', ''); ";

		mysqli_select_db($connection,$database);
		mysqli_real_escape_string($connection,$updateSQL);
		$result = mysqli_query($connection,$updateSQL) or die (mysqli_error($connection));

		$updateSQL = "INSERT INTO ".$prefix."styles (id, brewStyleNum, brewStyle, brewStyleCategory, brewStyleOG, brewStyleOGMax, brewStyleFG, brewStyleFGMax, brewStyleABV, brewStyleABVMax, brewStyleIBU, brewStyleIBUMax, brewStyleSRM, brewStyleSRMMax, brewStyleType, brewStyleInfo, brewStyleLink, brewStyleGroup, brewStyleActive, brewStyleOwn, brewStyleVersion, brewStyleReqSpec, brewStyleStrength, brewStyleCarb, brewStyleSweet, brewStyleTags, brewStyleComEx, brewStyleEntry) VALUES ";
		$updateSQL .= "(31, 'D', 'Irish Red Ale', 'Scottish and Irish Ale', '1.044', '1.06', '1.01', '1.014', '4', '6', '17', '28', '9', '18', 'Ale', 'An easy-drinking pint. Malt-focused with an initial sweetness and a roasted dryness in the finish.', 'http://www.bjcp.org/2008styles/style09.php#1d', '09', 'Y', 'bcoe', 'BJCP2008', 0, 0, 0, 0, '', 'Three Floyds Brian Boru Old Irish Ale, Great Lakes Conway&rsquo;s Irish Ale (a bit strong at 6.5%), Kilkenny Irish Beer, O&rsquo;Hara&rsquo;s Irish Red Ale, Smithwick&rsquo;s Irish Ale, Beamish Red Ale, Caffrey&rsquo;s Irish Ale, Goose Island Kilgubbin Red Ale, Murphy&rsquo;s Irish Red (lager), Boulevard Irish Ale, Harpoon Hibernian Ale.', ''), ";
		$updateSQL .= "(32, 'E', 'Strong Scotch Ale', 'Scottish and Irish Ale', '1.07', '1.13', '1.018', '1.056', '6.5', '10', '17', '35', '14', '25', 'Ale', 'Rich, malty and usually sweet, which can be suggestive of a dessert. Complex secondary malt flavors prevent a one-dimensional impression. Strength and maltiness can vary.', 'http://www.bjcp.org/2008styles/style09.php#1e', '09', 'Y', 'bcoe', 'BJCP2008', 0, 0, 0, 0, '', 'Traquair House Ale, Belhaven Wee Heavy, McEwan&rsquo;s Scotch Ale, Founders Dirty Bastard, MacAndrew&rsquo;s Scotch Ale, AleSmith Wee Heavy, Orkney Skull Splitter, Inveralmond Black Friar, Broughton Old Jock, Gordon Highland Scotch Ale, Dragonmead Under the Kilt.', ''), ";
		$updateSQL .= "(33, 'A', 'American Pale Ale', 'American Ale', '1.045', '1.06', '1.01', '1.015', '4.5', '6', '30', '45', '5', '14', 'Ale', 'Refreshing and hoppy, yet with sufficient supporting malt.', 'http://www.bjcp.org/2008styles/style10.php#1a', '10', 'Y', 'bcoe', 'BJCP2008', 0, 0, 0, 0, '', 'Sierra Nevada Pale Ale, Stone Pale Ale, Great Lakes Burning River Pale Ale, Bear Republic XP Pale Ale, Anderson Valley Poleeko Gold Pale Ale, Deschutes Mirror Pond, Full Sail Pale Ale, Three Floyds X-Tra Pale Ale, Firestone Pale Ale, Left Hand Brewing Jackman&rsquo;s Pale Ale. ', ''), ";
		$updateSQL .= "(34, 'B', 'American Amber Ale', 'American Ale', '1.045', '1.06', '1.01', '1.015', '4.5', '6.2', '25', '40', '10', '17', 'Ale', 'Like an American pale ale with more body, more caramel richness, and a balance more towards malt than hops (although hop rates can be significant).', 'http://www.bjcp.org/2008styles/style10.php#1b', '10', 'Y', 'bcoe', 'BJCP2008', 0, 0, 0, 0, '', 'North Coast Red Seal Ale, Troegs HopBack Amber Ale, Deschutes Cinder Cone Red, Pyramid Broken Rake, St. Rogue Red Ale, Anderson Valley Boont Amber Ale, Lagunitas Censored Ale, Avery Redpoint Ale, McNeill&rsquo;s Firehouse Amber Ale, Mendocino Red Tail Ale, Bell&rsquo;s Amber.', ''), ";
		$updateSQL .= "(35, 'C', 'American Brown Ale', 'American Ale', '1.045', '1.06', '1.01', '1.016', '4.3', '6.2', '20', '40', '18', '35', 'Ale', 'Can be considered a bigger, maltier, hoppier interpretation of Northern English brown ale or a hoppier, less malty Brown Porter, often including the citrus-accented hop presence that is characteristic of American hop varieties.', 'http://www.bjcp.org/2008styles/style10.php#1c', '10', 'Y', 'bcoe', 'BJCP2008', 0, 0, 0, 0, '', 'Bell&rsquo;s Best Brown, Smuttynose Old Brown Dog Ale, Big Sky Moose Drool Brown Ale, North Coast Acme Brown, Brooklyn Brown Ale, Lost Coast Downtown Brown, Left Hand Deep Cover Brown Ale.', ''), ";
		$updateSQL .= "(36, 'A', 'Mild', 'English Brown Ale', '1.03', '1.038', '1.008', '1.013', '2.8', '4.5', '10', '25', '12', '25', 'Ale', 'A light-flavored, malt-accented beer that is readily suited to drinking in quantity. Refreshing, yet flavorful. Some versions may seem like lower gravity brown porters.', 'http://www.bjcp.org/2008styles/style11.php#1a', '11', 'Y', 'bcoe', 'BJCP2008', 0, 0, 0, 0, '', 'Moorhouse Black Cat, Gale&rsquo;s Festival Mild, Theakston Traditional Mild, Highgate Mild, Sainsbury Mild, Brain&rsquo;s Dark, Banks&rsquo;s Mild, Coach House Gunpowder Strong Mild, Woodforde&rsquo;s Mardler&rsquo;s Mild, Greene King XX Mild, Motor City Brewing Ghettoblaster.', ''), ";
		$updateSQL .= "(37, 'B', 'Southern English Brown Ale', 'English Brown Ale', '1.033', '1.042', '1.011', '1.014', '2.8', '4.2', '12', '20', '19', '35', 'Ale', 'A luscious, malt-oriented brown ale, with a caramel, dark fruit complexity of malt flavor. May seem somewhat like a smaller version of a sweet stout or a sweet version of a dark mild.', 'http://www.bjcp.org/2008styles/style11.php#1b', '11', 'Y', 'bcoe', 'BJCP2008', 0, 0, 0, 0, '', 'Mann&rsquo;s Brown Ale (bottled, but not available in the US), Harvey&rsquo;s Nut Brown Ale, Woodeforde&rsquo;s Norfolk Nog.', ''), ";
		$updateSQL .= "(38, 'C', 'Northern English Brown Ale', 'English Brown Ale', '1.04', '1.052', '1.008', '1.013', '4.2', '5.4', '20', '30', '12', '22', 'Ale', 'English mild ale or pale ale malt base with caramel malts. May also have small amounts darker malts (e.g., chocolate) to provide color and the nutty character. English hop varieties are most authentic. Moderate carbonate water.', 'http://www.bjcp.org/2008styles/style11.php#1c', '11', 'Y', 'bcoe', 'BJCP2008', 0, 0, 0, 0, '', 'Newcastle Brown Ale, Samuel Smith&rsquo;s Nut Brown Ale, Riggwelter Yorkshire Ale, Wychwood Hobgoblin, Troegs Rugged Trail Ale, Alesmith Nautical Nut Brown Ale, Avery Ellie&rsquo;s Brown Ale, Goose Island Nut Brown Ale, Samuel Adams Brown Ale.', ''), ";
		$updateSQL .= "(39, 'A', 'Brown Porter', 'Porter', '1.04', '1.052', '1.008', '1.014', '4', '5.4', '18', '35', '20', '30', 'Ale', 'A fairly substantial English dark ale with restrained roasty characteristics.', 'http://www.bjcp.org/2008styles/style12.php#1a', '12', 'Y', 'bcoe', 'BJCP2008', 0, 0, 0, 0, '', 'Fuller&rsquo;s London Porter, Samuel Smith Taddy Porter, Burton Bridge Burton Porter, RCH Old Slug Porter, Nethergate Old Growler Porter, Hambleton Nightmare Porter, Harvey&rsquo;s Tom Paine Original Old Porter, Salopian Entire Butt English Porter, St. Peters Old-Style Porter, Shepherd Neame Original Porter, Flag Porter, Wasatch Polygamy Porter.', ''), ";
		$updateSQL .= "(40, 'B', 'Robust Porter', 'Porter', '1.048', '1.065', '1.012', '1.016', '4.8', '6.5', '25', '50', '22', '35', 'Ale', 'A substantial, malty dark ale with a complex and flavorful roasty character.', 'http://www.bjcp.org/2008styles/style12.php#1b', '12', 'Y', 'bcoe', 'BJCP2008', 0, 0, 0, 0, '', 'Great Lakes Edmund Fitzgerald Porter, Meantime London Porter, Anchor Porter, Smuttynose Robust Porter, Sierra Nevada Porter, Deschutes Black Butte Porter, Boulevard Bully! Porter, Rogue Mocha Porter, Avery New World Porter, Bell&rsquo;s Porter, Great Divide Saint Bridget&rsquo;s Porter.', ''); ";

		mysqli_select_db($connection,$database);
		mysqli_real_escape_string($connection,$updateSQL);
		$result = mysqli_query($connection,$updateSQL) or die (mysqli_error($connection));

		$updateSQL = "INSERT INTO ".$prefix."styles (id, brewStyleNum, brewStyle, brewStyleCategory, brewStyleOG, brewStyleOGMax, brewStyleFG, brewStyleFGMax, brewStyleABV, brewStyleABVMax, brewStyleIBU, brewStyleIBUMax, brewStyleSRM, brewStyleSRMMax, brewStyleType, brewStyleInfo, brewStyleLink, brewStyleGroup, brewStyleActive, brewStyleOwn, brewStyleVersion, brewStyleReqSpec, brewStyleStrength, brewStyleCarb, brewStyleSweet, brewStyleTags, brewStyleComEx, brewStyleEntry) VALUES ";
		$updateSQL .= "(41, 'C', 'Baltic Porter', 'Porter', '1.06', '1.09', '1.016', '1.024', '5.5', '9.5', '20', '40', '17', '30', 'Ale', 'A Baltic Porter often has the malt flavors reminiscent of an English brown porter and the restrained roast of a schwarzbier, but with a higher OG and alcohol content than either. Very complex, with multi-layered flavors.', 'http://www.bjcp.org/2008styles/style12.php#1c', '12', 'Y', 'bcoe', 'BJCP2008', 0, 0, 0, 0, '', 'Sinebrychoff Porter (Finland), Okocim Porter (Poland), Zywiec Porter (Poland), Baltika #6 Porter (Russia), Carnegie Stark Porter (Sweden), Aldaris Porteris (Latvia), Utenos Porter (Lithuania), Stepan Razin Porter (Russia), N&oslash;gne o porter (Norway), Neuzeller Kloster-Brau Neuzeller Porter (Germany), Southampton Imperial Baltic Porter.', ''), ";
		$updateSQL .= "(42, 'A', 'Dry Stout', 'Stout', '1.036', '1.05', '1.007', '1.011', '4', '5', '30', '45', '25', '40', 'Ale', 'A very dark, roasty, bitter, creamy ale.', 'http://www.bjcp.org/2008styles/style13.php#1a', '13', 'Y', 'bcoe', 'BJCP2008', 0, 0, 0, 0, '', 'Guinness Draught Stout (also canned), Murphy&rsquo;s Stout, Beamish Stout, O&rsquo;Hara&rsquo;s Celtic Stout, Russian River O.V.L. Stout, Three Floyd&rsquo;s Black Sun Stout, Dorothy Goodbody&rsquo;s Wholesome Stout, Orkney Dragonhead Stout, Old Dominion Stout, Goose Island Dublin Stout, Brooklyn Dry Stout.', ''), ";
		$updateSQL .= "(43, 'B', 'Sweet Stout', 'Stout', '1.044', '1.06', '1.012', '1.024', '4', '6', '20', '40', '30', '40', 'Ale', 'A very dark, sweet, full-bodied, slightly roasty ale. Often tastes like sweetened espresso.', 'http://www.bjcp.org/2008styles/style13.php#1b', '13', 'Y', 'bcoe', 'BJCP2008', 0, 0, 0, 0, '', 'Mackeson&rsquo;s XXX Stout, Watney&rsquo;s Cream Stout, Farson&rsquo;s Lacto Stout, St. Peter&rsquo;s Cream Stout, Marston&rsquo;s Oyster Stout, Sheaf Stout, Hitachino Nest Sweet Stout (Lacto), Samuel Adams Cream Stout, Left Hand Milk Stout, Widmer Snowplow Milk Stout.', ''), ";
		$updateSQL .= "(44, 'C', 'Oatmeal Stout', 'Stout', '1.048', '1.065', '1.01', '1.018', '4.2', '5.9', '25', '40', '22', '40', 'Ale', 'A very dark, full-bodied, roasty, malty ale with a complementary oatmeal flavor.', 'http://www.bjcp.org/2008styles/style13.php#1c', '13', 'Y', 'bcoe', 'BJCP2008', 0, 0, 0, 0, '', 'Samuel Smith Oatmeal Stout, Young&rsquo;s Oatmeal Stout, McAuslan Oatmeal Stout, Maclay&rsquo;s Oat Malt Stout, Broughton Kinmount Willie Oatmeal Stout, Anderson Valley Barney Flats Oatmeal Stout, Troegs Oatmeal Stout, New Holland The Poet, Goose Island Oatmeal Stout, Wolaver&rsquo;s Oatmeal Stout.', ''), ";
		$updateSQL .= "(45, 'D', 'Foreign Extra Stout', 'Stout', '1.056', '1.075', '1.01', '1.018', '5.5', '8', '30', '70', '30', '40', 'Ale', 'A very dark, moderately strong, roasty ale. Tropical varieties can be quite sweet, while export versions can be drier and fairly robust.', 'http://www.bjcp.org/2008styles/style13.php#1d', '13', 'Y', 'bcoe', 'BJCP2008', 0, 0, 0, 0, '', 'Tropical-Type: Lion Stout (Sri Lanka), Dragon Stout (Jamaica), ABC Stout (Singapore), Royal Extra &ldquo;The Lion Stout&rdquo; (Trinidad), Jamaica Stout (Jamaica), Export-Type: Freeminer Deep Shaft Stout, Guinness Foreign Extra Stout (bottled, not sold in the US), Ridgeway of Oxfordshire Foreign Extra Stout, Coopers Best Extra Stout, Elysian Dragonstooth Stout.', ''), ";
		$updateSQL .= "(46, 'E', 'American Stout', 'Stout', '1.05', '1.075', '1.01', '1.022', '5', '7', '35', '75', '30', '40', 'Ale', 'A hoppy, bitter, strongly roasted Foreign-style Stout (of the export variety).', 'http://www.bjcp.org/2008styles/style13.php#1e', '13', 'Y', 'bcoe', 'BJCP2008', 0, 0, 0, 0, '', 'Rogue Shakespeare Stout, Deschutes Obsidian Stout, Sierra Nevada Stout, North Coast Old No. 38, Bar Harbor Cadillac Mountain Stout, Avery Out of Bounds Stout, Lost Coast 8 Ball Stout, Mad River Steelhead Extra Stout.', ''), ";
		$updateSQL .= "(47, 'F', 'Imperial Stout', 'Stout', '1.075', '1.115', '1.018', '1.03', '8', '12', '50', '90', '30', '40', 'Ale', 'An intensely flavored, big, dark ale. Roasty, fruity, and bittersweet, with a noticeable alcohol presence. Dark fruit flavors meld with roasty, burnt, or almost tar-like sensations. Like a black barleywine with every dimension of flavor coming into play.', 'http://www.bjcp.org/2008styles/style13.php#1f', '13', 'Y', 'bcoe', 'BJCP2008', 0, 0, 0, 0, '', 'Three Floyd&rsquo;s Dark Lord, Bell&rsquo;s Expedition Stout, North Coast Old Rasputin Imperial Stout, Stone Imperial Stout, Samuel Smith Imperial Stout, Scotch Irish Tsarina Katarina Imperial Stout, Thirsty Dog Siberian Night, Deschutes The Abyss, Great Divide Yeti, Southampton Russian Imperial Stout, Rogue Imperial Stout, Bear Republic Big Bear Black Stout, Great Lakes Blackout Stout, Avery The Czar, Founders Imperial Stout, Victory Storm King, Brooklyn Black Chocolate Stout. ', ''), ";
		$updateSQL .= "(48, 'A', 'English IPA', 'India Pale Ale', '1.05', '1.075', '1.01', '1.018', '5', '7.5', '40', '60', '8', '14', 'Ale', 'A hoppy, moderately strong pale ale that features characteristics consistent with the use of English malt, hops and yeast. Has less hop character and a more pronounced malt flavor than American versions.', 'http://www.bjcp.org/2008styles/style14.php#1a', '14', 'Y', 'bcoe', 'BJCP2008', 0, 0, 0, 0, '', 'Meantime India Pale Ale, Freeminer Trafalgar IPA, Fuller&rsquo;s IPA, Ridgeway Bad Elf, Summit India Pale Ale, Samuel Smith&rsquo;s India Ale, Hampshire Pride of Romsey IPA, Burton Bridge Empire IPA,Middle Ages ImPailed Ale, Goose Island IPA, Brooklyn East India Pale Ale.', ''), ";
		$updateSQL .= "(49, 'B', 'American IPA', 'India Pale Ale', '1.056', '1.075', '1.01', '1.018', '5.5', '7.5', '40', '70', '6', '15', 'Ale', 'A decidedly hoppy and bitter, moderately strong American pale ale', 'http://www.bjcp.org/2008styles/style14.php#1b', '14', 'Y', 'bcoe', 'BJCP2008', 0, 0, 0, 0, '', 'Bell&rsquo;s Two-Hearted Ale, AleSmith IPA, Russian River Blind Pig IPA, Stone IPA, Three Floyds Alpha King, Great Divide Titan IPA, Bear Republic Racer 5 IPA, Victory Hop Devil, Sierra Nevada Celebration Ale, Anderson Valley Hop Ottin&rsquo;, Dogfish Head 60 Minute IPA, Founder&rsquo;s Centennial IPA, Anchor Liberty Ale, Harpoon IPA, Avery IPA.', ''), ";
		$updateSQL .= "(50, 'C', 'Imperial IPA', 'India Pale Ale', '1.075', '1.09', '1.01', '1.02', '7.5', '10', '60', '120', '8', '15', 'Ale', 'An intensely hoppy, very strong pale ale without the big maltiness and/or deeper malt flavors of an American barleywine. Strongly hopped, but clean, lacking harshness, and a tribute to historical IPAs. Drinkability is an important characteristic; this should not be a heavy, sipping beer. It should also not have much residual sweetness or a heavy character grain profile.', 'http://www.bjcp.org/2008styles/style14.php#1c', '14', 'Y', 'bcoe', 'BJCP2008', 0, 0, 0, 0, '', 'Russian River Pliny the Elder, Three Floyd&rsquo;s Dreadnaught, Avery Majaraja, Bell&rsquo;s Hop Slam, Stone Ruination IPA, Great Divide Hercules Double IPA, Surly Furious, Rogue I2PA, Moylan&rsquo;s Hopsickle Imperial India Pale Ale, Stoudt&rsquo;s Double IPA, Dogfish Head 90-minute IPA, Victory Hop Wallop.', ''); ";

		mysqli_select_db($connection,$database);
		mysqli_real_escape_string($connection,$updateSQL);
		$result = mysqli_query($connection,$updateSQL) or die (mysqli_error($connection));

		$updateSQL = "INSERT INTO ".$prefix."styles (id, brewStyleNum, brewStyle, brewStyleCategory, brewStyleOG, brewStyleOGMax, brewStyleFG, brewStyleFGMax, brewStyleABV, brewStyleABVMax, brewStyleIBU, brewStyleIBUMax, brewStyleSRM, brewStyleSRMMax, brewStyleType, brewStyleInfo, brewStyleLink, brewStyleGroup, brewStyleActive, brewStyleOwn, brewStyleVersion, brewStyleReqSpec, brewStyleStrength, brewStyleCarb, brewStyleSweet, brewStyleTags, brewStyleComEx, brewStyleEntry) VALUES ";
		$updateSQL .= "(51, 'A', 'Weizen/Weissbier', 'German Wheat and Rye Beer', '1.044', '1.052', '1.01', '1.014', '4.3', '5.6', '8', '15', '2', '8', 'Ale', 'A pale, spicy, fruity, refreshing wheat-based ale.', 'http://www.bjcp.org/2008styles/style15.php#1a', '15', 'Y', 'bcoe', 'BJCP2008', 0, 0, 0, 0, '', 'Weihenstephaner Hefeweissbier, Schneider Weisse Weizenhell, Paulaner Hefe-Weizen, Hacker-Pschorr Weisse, Plank Bavarian Hefeweizen, Ayinger Brau Weisse, Ettaler Weissbier Hell, Franziskaner Hefe-Weisse, Andechser Weissbier Hefetrub, Kapuziner Weissbier, Erdinger Weissbier, Penn Weizen, Barrelhouse Hocking Hills HefeWeizen, Eisenbahn Weizenbier.', ''), ";
		$updateSQL .= "(52, 'B', 'Dunkelweizen', 'German Wheat and Rye Beer', '1.044', '1.056', '1.01', '1.014', '4.3', '5.6', '10', '18', '14', '23', 'Ale', 'A moderately dark, spicy, fruity, malty, refreshing wheat-based ale. Reflecting the best yeast and wheat character of a hefe-weizen blended with the malty richness of a Munich dunkel.', 'http://www.bjcp.org/2008styles/style15.php#1b', '15', 'Y', 'bcoe', 'BJCP2008', 0, 0, 0, 0, '', 'Weihenstephaner Hefeweissbier Dunkel, Ayinger Ur-Weisse, Franziskaner Dunkel Hefe-Weisse, Schneider Weisse (Original), Ettaler Weissbier Dunkel, Hacker-Pschorr Weisse Dark, Tucher Dunkles Hefe Weizen, Edelweiss Dunkel Weissbier, Erdinger Weissbier Dunkel, Kapuziner Weissbier Schwarz. ', ''), ";
		$updateSQL .= "(53, 'C', 'Weizenbock', 'German Wheat and Rye Beer', '1.064', '1.09', '1.015', '1.022', '6.5', '8', '15', '30', '12', '25', 'Ale', 'A strong, malty, fruity, wheat-based ale combining the best flavors of a dunkelweizen and the rich strength and body of a bock.', 'http://www.bjcp.org/2008styles/style15.php#1c', '15', 'Y', 'bcoe', 'BJCP2008', 0, 0, 0, 0, '', 'Schneider Aventinus, Schneider Aventinus Eisbock, Plank Bavarian Dunkler Weizenbock, Plank Bavarian Heller Weizenbock, AleSmith Weizenbock, Erdinger Pikantus, Mahr&rsquo;s Der Weisse Bock, Victory Moonglow Weizenbock, High Point Ramstein Winter Wheat, Capital Weizen Doppelbock, Eisenbahn Vigorosa.', ''), ";
		$updateSQL .= "(54, 'D', 'Roggenbier (German Rye Beer)', 'German Wheat and Rye Beer', '1.046', '1.056', '1.01', '1.014', '4.5', '6', '10', '20', '14', '19', 'Ale', 'A dunkelweizen made with rye rather than wheat, but with a greater body and light finishing hops.', 'http://www.bjcp.org/2008styles/style15.php#1d', '15', 'Y', 'bcoe', 'BJCP2008', 0, 0, 0, 0, '', 'Paulaner Roggen, Burgerbrau Wolznacher Roggenbier. ', ''), ";
		$updateSQL .= "(55, 'A', 'Witbier', 'Belgian and French Ale', '1.044', '1.052', '1.008', '1.012', '4.5', '5.5', '10', '20', '2', '4', 'Ale', 'A refreshing, elegant, tasty, moderate-strength wheat-based ale.', 'http://www.bjcp.org/2008styles/style16.php#1a', '16', 'Y', 'bcoe', 'BJCP2008', 0, 0, 0, 0, '', 'Hoegaarden Wit, St. Bernardus Blanche, Celis White, Vuuve 5, Brugs Tarwebier (Blanche de Bruges), Wittekerke, Allagash White, Blanche de Bruxelles, Ommegang Witte, Avery White Rascal, Unibroue Blanche de Chambly, Sterkens White Ale, Bell&rsquo;s Winter White Ale, Victory Whirlwind Witbier, Hitachino Nest White Ale.', ''), ";
		$updateSQL .= "(56, 'B', 'Belgian Pale Ale', 'Belgian and French Ale', '1.048', '1.054', '1.01', '1.014', '4.8', '5.5', '20', '30', '8', '14', 'Ale', 'A fruity, moderately malty, somewhat spicy, easy-drinking, copper-colored ale. ', 'http://www.bjcp.org/2008styles/style16.php#1b', '16', 'Y', 'bcoe', 'BJCP2008', 0, 0, 0, 0, '', 'De Koninck, Speciale Palm, Dobble Palm, Russian River Perdition, Ginder Ale, Op-Ale, St. Pieters Zinnebir, Brewer&rsquo;s Art House Pale Ale, Avery Karma, Eisenbahn Pale Ale, Ommegang Rare Vos.', ''), ";
		$updateSQL .= "(57, 'C', 'Saison', 'Belgian and French Ale', '1.048', '1.065', '1.002', '1.012', '5', '7', '20', '35', '5', '14', 'Ale', 'A medium to strong ale with a distinctive yellow-orange color, highly carbonated, well hopped, fruity and dry with a quenching acidity.', 'http://www.bjcp.org/2008styles/style16.php#1c', '16', 'Y', 'bcoe', 'BJCP2008', 0, 0, 0, 0, '', 'Saison Dupont Vieille Provision; Fant&oslash;me Saison D&rsquo;Erezee - Printemps; Saison de Pipaix; Saison Regal; Saison Voisin; Lefebvre Saison 1900; Ellezelloise Saison 2000; Saison Silly; Southampton Saison; New Belgium Saison; Pizza Port SPF 45; Lost Abbey Red Barn Ale; Ommegang Hennepin.', ''), ";
		$updateSQL .= "(58, 'D', 'Biere de Garde', 'Belgian and French Ale', '1.06', '1.08', '1.008', '1.016', '6', '8.5', '18', '28', '6', '19', 'Ale', 'A fairly strong, malt-accentuated, lagered artisanal farmhouse beer.', 'http://www.bjcp.org/2008styles/style16.php#1d', '16', 'Y', 'bcoe', 'BJCP2008', 0, 0, 0, 0, '', 'Jenlain (amber), Jenlain Biere de Printemps (blond), St. Amand (brown), Ch&rsquo;Ti Brun (brown), Ch&rsquo;Ti Blond (blond), La Choulette (all 3 versions), La Choulette Biere des Sans Culottes (blond), Saint Sylvestre 3 Monts (blond), Biere Nouvelle (brown), Castelain (blond), Jade (amber), Brasseurs Biere de Garde (amber), Southampton Biere de Garde (amber), Lost Abbey Avante Garde (blond).', ''), ";
		$updateSQL .= "(59, 'E', 'Belgian Specialty Ale', 'Belgian and French Ale', '', '', '', '', '', '', '', '', '', '', 'Ale', 'Variable. This category encompasses a wide range of Belgian ales produced by truly artisanal brewers more concerned with creating unique products than in increasing sales.', 'http://www.bjcp.org/2008styles/style16.php#1e', '16', 'Y', 'bcoe', 'BJCP2008', 1, 0, 0, 0, '', 'Orval; De Dolle&rsquo;s Arabier, Oerbier, Boskeun and Stille Nacht; La Chouffe, McChouffe, Chouffe Bok and N&rsquo;ice Chouffe; Ellezelloise Hercule Stout and Quintine Amber; Unibroue Ephemere, Maudite, Don de Dieu, etc.; Minty; Zatte Bie; Caracole Amber, Saxo and Nostradamus; Silenrieu Sara and Joseph; Fantôme Black Ghost and Speciale Noel; Dupont Moinette, Moinette Brune, and Avec Les Bons Voeux de la Brasserie Dupont; St. Fullien Noel; Gouden Carolus Noel; Affligem Noel; Guldenburg and Pere Noel; De Ranke XX Bitter and Guldenberg; Poperings Hommelbier; Bush (Scaldis); Moinette Brune; Grottenbier; La Trappe Quadrupel; Weyerbacher QUAD; Biere de Miel; Verboden Vrucht; New Belgium 1554 Black Ale; Cantillon Iris; Russian River Temptation; Lost Abbey Cuvee de Tomme and Devotion, Lindemans Kriek and Framboise, and many more.', 'Entrant must specify the beer being cloned, the new style being produced, or the special ingredients or process used. Beers fitting other Belgian categories should not be entered in this category.'), ";
		$updateSQL .= "(60, 'A', 'Berliner Weisse', 'Sour Ale', '1.028', '1.032', '1.003', '1.006', '2.8', '3.8', '3', '8', '2', '3', 'Ale', 'A very pale, sour, refreshing, low-alcohol wheat ale.', 'http://www.bjcp.org/2008styles/style17.php#1a', '17', 'Y', 'bcoe', 'BJCP2008', 0, 0, 0, 0, '', 'Schultheiss Berliner Weisse, Berliner Kindl Weisse, Nodding Head Berliner Weisse, Weihenstephan 1809, Bahnhof Berliner Style Weisse, Southampton Berliner Weisse, Bethlehem Berliner Weisse, Three Floyds Deesko.', ''); ";

		mysqli_select_db($connection,$database);
		mysqli_real_escape_string($connection,$updateSQL);
		$result = mysqli_query($connection,$updateSQL) or die (mysqli_error($connection));

		$updateSQL = "INSERT INTO ".$prefix."styles (id, brewStyleNum, brewStyle, brewStyleCategory, brewStyleOG, brewStyleOGMax, brewStyleFG, brewStyleFGMax, brewStyleABV, brewStyleABVMax, brewStyleIBU, brewStyleIBUMax, brewStyleSRM, brewStyleSRMMax, brewStyleType, brewStyleInfo, brewStyleLink, brewStyleGroup, brewStyleActive, brewStyleOwn, brewStyleVersion, brewStyleReqSpec, brewStyleStrength, brewStyleCarb, brewStyleSweet, brewStyleTags, brewStyleComEx, brewStyleEntry) VALUES ";
		$updateSQL .= "(61, 'B', 'Flanders Red Ale', 'Sour Ale', '1.048', '1.057', '1.002', '1.012', '4.6', '6.5', '10', '25', '10', '16', 'Ale', 'A complex, sour, red wine-like Belgian-style ale.', 'http://www.bjcp.org/2008styles/style17.php#1b', '17', 'Y', 'bcoe', 'BJCP2008', 0, 0, 0, 0, '', 'Rodenbach Klassiek, Rodenbach Grand Cru, Bellegems Bruin, Duchesse de Bourgogne, New Belgium La Folie, Petrus Oud Bruin, Southampton Flanders Red Ale, Verhaege Vichtenaar, Monk&rsquo;s Cafe Flanders Red Ale, New Glarus Enigma, Panil Barriquee, Mestreechs Aajt.', ''), ";
		$updateSQL .= "(62, 'C', 'Flanders Brown Ale/Oud Bruin', 'Sour Ale', '1.04', '1.074', '1.008', '1.012', '4', '8', '20', '25', '15', '22', 'Ale', 'A malty, fruity, aged, somewhat sour Belgian-style brown ale.', 'http://www.bjcp.org/2008styles/style17.php#1c', '17', 'Y', 'bcoe', 'BJCP2008', 0, 0, 0, 0, '', 'Liefman&rsquo;s Goudenband, Liefman&rsquo;s Odnar, Liefman&rsquo;s Oud Bruin, Ichtegem Old Brown, Riva Vondel. ', ''), ";
		$updateSQL .= "(63, 'D', 'Straight (Unblended) Lambic', 'Sour Ale', '1.04', '1.054', '1.001', '1.01', '5', '6.5', '0', '10', '3', '7', 'Ale', 'Complex, sour/acidic, pale, wheat-based ale fermented by a variety of Belgian microbiota.', 'http://www.bjcp.org/2008styles/style17.php#1d', '17', 'Y', 'bcoe', 'BJCP2008', 0, 0, 0, 0, '', 'Cantillon Grand Cru Bruocsella.', ''), ";
		$updateSQL .= "(64, 'E', 'Gueuze', 'Sour Ale', '1.04', '1.06', '1', '1.006', '5', '8', '0', '10', '3', '7', 'Ale', 'Complex, pleasantly sour/acidic, balanced, pale, wheat-based ale fermented by a variety of Belgian microbiota.', 'http://www.bjcp.org/2008styles/style17.php#1e', '17', 'Y', 'bcoe', 'BJCP2008', 0, 0, 0, 0, '', 'Boon Oude Gueuze, Boon Oude Gueuze Mariage Parfait, De Cam Gueuze, De Cam/Drei Fonteinen Millennium Gueuze, Drie Fonteinen Oud Gueuze, Cantillon Gueuze, Hanssens Oude Gueuze, Lindemans Gueuze Cuvee Rene, Girardin Gueuze (Black Label), Mort Subite (Unfiltered) Gueuze, Oud Beersel Oude Gueuze.', ''), ";
		$updateSQL .= "(65, 'F', 'Fruit Lambic', 'Sour Ale', '1.04', '1.06', '1', '1.01', '5', '7', '0', '10', '3', '7', 'Ale', 'Complex, fruity, pleasantly sour/acidic, balanced, pale, wheat-based ale fermented by a variety of Belgian microbiota. A lambic with fruit, not just a fruit beer.', 'http://www.bjcp.org/2008styles/style17.php#1f', '17', 'Y', 'bcoe', 'BJCP2008', 1, 0, 0, 0, '', 'Boon Framboise Marriage Parfait, Boon Kriek Mariage Parfait, Boon Oude Kriek, Cantillon Fou&rsquo; Foune (apricot), Cantillon Kriek, Cantillon Lou Pepe Kriek, Cantillon Lou Pepe Framboise, Cantillon Rose de Gambrinus, Cantillon St. Lamvinus (merlot grape), Cantillon Vigneronne (Muscat grape), De Cam Oude Kriek, Drie Fonteinen Kriek, Girardin Kriek, Hanssens Oude Kriek, Oud Beersel Kriek, Mort Subite Kriek.', 'Entrant must specify the type of fruit(s) used in the making of the lambic.'), ";
		$updateSQL .= "(66, 'A', 'Belgian Blond Ale', 'Belgian Strong Ale', '1.062', '1.075', '1.008', '1.018', '6', '7.5', '15', '30', '4', '7', 'Ale', 'A moderate-strength golden ale that has a subtle Belgian complexity, slightly sweet flavor, and dry finish. History: Relatively recent development to further appeal to European Pils drinkers, becoming more popular as it is widely marketed and distributed.', 'http://www.bjcp.org/2008styles/style18.php#1a', '18', 'Y', 'bcoe', 'BJCP2008', 0, 0, 0, 0, '', 'Leffe Blond, Affligem Blond, La Trappe (Koningshoeven) Blond, Grimbergen Blond, Val-Dieu Blond, Straffe Hendrik Blonde, Brugse Zot, Pater Lieven Blond Abbey Ale, Troubadour Blond Ale.', ''), ";
		$updateSQL .= "(67, 'B', 'Belgian Dubbel', 'Belgian Strong Ale', '1.062', '1.075', '1.008', '1.018', '6', '7.6', '15', '25', '10', '17', 'Ale', 'A deep reddish, moderately strong, malty, complex Belgian ale.', 'http://www.bjcp.org/2008styles/style18.php#1b', '18', 'Y', 'bcoe', 'BJCP2008', 0, 0, 0, 0, '', 'Westmalle Dubbel, St. Bernardus Pater 6, La Trappe Dubbel, Corsendonk Abbey Brown Ale, Grimbergen Double, Affligem Dubbel, Chimay Premiere (Red), Pater Lieven Bruin, Duinen Dubbel, St. Feuillien Brune, New Belgium Abbey Belgian Style Ale, Stoudts Abbey Double Ale, Russian River Benediction, Flying Fish Dubbel, Lost Abbey Lost and Found Abbey Ale, Allagash Double.', ''), ";
		$updateSQL .= "(68, 'C', 'Belgian Tripel', 'Belgian Strong Ale', '1.075', '1.085', '1.008', '1.014', '7.5', '9.5', '20', '40', '4.5', '7', 'Ale', 'Strongly resembles a Strong Golden Ale but slightly darker and somewhat fuller-bodied. Usually has a more rounded malt flavor but should not be sweet.', 'http://www.bjcp.org/2008styles/style18.php#1c', '18', 'Y', 'bcoe', 'BJCP2008', 0, 0, 0, 0, '', 'Westmalle Tripel, La Rulles Tripel, St. Bernardus Tripel, Chimay Cinq Cents (White), Watou Tripel, Val-Dieu Triple, Affligem Tripel, Grimbergen Tripel, La Trappe Tripel, Witkap Pater Tripel, Corsendonk Abbey Pale Ale, St. Feuillien Tripel, Bink Tripel, Tripel Karmeliet, New Belgium Trippel, Unibroue La Fin du Monde, Dragonmead Final Absolution, Allagash Tripel Reserve, Victory Golden Monkey.', ''), ";
		$updateSQL .= "(69, 'D', 'Belgian Golden Strong Ale', 'Belgian Strong Ale', '1.07', '1.095', '1.005', '1.016', '7.5', '10.5', '22', '35', '3', '6', 'Ale', 'A golden, complex, effervescent, strong Belgian-style ale.', 'http://www.bjcp.org/2008styles/style18.php#1d', '18', 'Y', 'bcoe', 'BJCP2008', 0, 0, 0, 0, '', 'Duvel, Russian River Damnation, Hapkin, Lucifer, Brigand, Judas, Delirium Tremens, Dulle Teve, Piraat, Great Divide Hades, Avery Salvation, North Coast Pranqster, Unibroue Eau Benite, AleSmith Horny Devil.', ''), ";
		$updateSQL .= "(70, 'E', 'Belgian Dark Strong Ale', 'Belgian Strong Ale', '1.075', '1.11', '1.01', '1.024', '8', '11', '20', '35', '12', '22', 'Ale', 'A dark, very rich, complex, very strong Belgian ale. Complex, rich, smooth and dangerous.', 'http://www.bjcp.org/2008styles/style18.php#1e', '18', 'Y', 'bcoe', 'BJCP2008', 0, 0, 0, 0, '', 'Westvleteren 12 (yellow cap), Rochefort 10 (blue cap), St. Bernardus Abt 12, Gouden Carolus Grand Cru of the Emperor, Achel Extra Brune, Rochefort 8 (green cap), Southampton Abbot 12, Chimay Grande Reserve (Blue), Brasserie des Rocs Grand Cru, Gulden Draak, Kasteelbier Biere du Chateau Donker, Lost Abbey Judgment Day, Russian River Salvation.', ''); ";

		mysqli_select_db($connection,$database);
		mysqli_real_escape_string($connection,$updateSQL);
		$result = mysqli_query($connection,$updateSQL) or die (mysqli_error($connection));

		$updateSQL = "INSERT INTO ".$prefix."styles (id, brewStyleNum, brewStyle, brewStyleCategory, brewStyleOG, brewStyleOGMax, brewStyleFG, brewStyleFGMax, brewStyleABV, brewStyleABVMax, brewStyleIBU, brewStyleIBUMax, brewStyleSRM, brewStyleSRMMax, brewStyleType, brewStyleInfo, brewStyleLink, brewStyleGroup, brewStyleActive, brewStyleOwn, brewStyleVersion, brewStyleReqSpec, brewStyleStrength, brewStyleCarb, brewStyleSweet, brewStyleTags, brewStyleComEx, brewStyleEntry) VALUES ";
		$updateSQL .= "(71, 'A', 'Old Ale', 'Strong Ale', '1.06', '1.09', '1.015', '1.022', '6', '9', '30', '60', '10', '22', 'Ale', 'An ale of significant alcoholic strength, bigger than strong bitters and brown porters, though usually not as strong or rich as barleywine. Usually tilted toward a sweeter, maltier balance. &ldquo;It should be a warming beer of the type that is best drunk in half pints by a warm fire on a cold winter&rsquo;s night&rdquo; &ndash; Michael Jackson.', 'http://www.bjcp.org/2008styles/style19.php#1a', '19', 'Y', 'bcoe', 'BJCP2008', 0, 0, 0, 0, '', 'Gale&rsquo;s Prize Old Ale, Burton Bridge Olde Expensive, Marston Owd Roger, Greene King Olde Suffolk Ale , J.W. Lees Moonraker, Harviestoun Old Engine Oil, Fuller&rsquo;s Vintage Ale, Harvey&rsquo;s Elizabethan Ale, Theakston Old Peculier (peculiar at OG 1.057), Young&rsquo;s Winter Warmer, Sarah Hughes Dark Ruby Mild, Samuel Smith&rsquo;s Winter Welcome, Fuller&rsquo;s 1845, Fuller&rsquo;s Old Winter Ale, Great Divide Hibernation Ale, Founders Curmudgeon, Cooperstown Pride of Milford Special Ale, Coniston Old Man Ale, Avery Old Jubilation.', ''), ";
		$updateSQL .= "(72, 'B', 'English Barleywine', 'Strong Ale', '1.08', '1.12', '1.018', '1.03', '8', '12', '35', '70', '8', '22', 'Ale', 'The richest and strongest of the English Ales. A showcase of malty richness and complex, intense flavors. The character of these ales can change significantly over time; both young and old versions should be appreciated for what they are. The malt profile can vary widely; not all examples will have all possible flavors or aromas.', 'http://www.bjcp.org/2008styles/style19.php#1b', '19', 'Y', 'bcoe', 'BJCP2008', 0, 0, 0, 0, '', 'Thomas Hardy&rsquo;s Ale, Burton Bridge Thomas Sykes Old Ale, J.W. Lee&rsquo;s Vintage Harvest Ale, Robinson&rsquo;s Old Tom, Fuller&rsquo;s Golden Pride, AleSmith Old Numbskull, Young&rsquo;s Old Nick (unusual in its 7.2% ABV), Whitbread Gold Label, Old Dominion Millenium, North Coast Old Stock Ale (when aged), Weyerbacher Blithering Idiot.', ''), ";
		$updateSQL .= "(73, 'C', 'American Barleywine', 'Strong Ale', '1.08', '1.12', '1.016', '1.03', '8', '12', '50', '120', '10', '19', 'Ale', 'A well-hopped American interpretation of the richest and strongest of the English ales. The hop character should be evident throughout, but does not have to be unbalanced. The alcohol strength and hop bitterness often combine to leave a very long finish.', 'http://www.bjcp.org/2008styles/style19.php#1c', '19', 'Y', 'bcoe', 'BJCP2008', 0, 0, 0, 0, '', 'Sierra Nevada Bigfoot, Great Divide Old Ruffian, Victory Old Horizontal, Rogue Old Crustacean, Avery Hog Heaven Barleywine, Bell&rsquo;s Third Coast Old Ale, Anchor Old Foghorn, Three Floyds Behemoth, Stone Old Guardian, Bridgeport Old Knucklehead, Hair of the Dog Doggie Claws, Lagunitas Olde GnarleyWine, Smuttynose Barleywine, Flying Dog Horn Dog.', ''), ";
		$updateSQL .="(74, 'A', 'Fruit Beer', 'Fruit Beer', '', '', '', '', '', '', '', '', '', '', '', 'A harmonious marriage of fruit and beer. The key attributes of the underlying style will be different with the addition of fruit; do not expect the base beer to taste the same as the unadulterated version. Judge the beer based on the pleasantness and balance of the resulting combination.', 'http://www.bjcp.org/2008styles/style20.php#1a', '20', 'Y', 'bcoe', 'BJCP2008', 1, 0, 0, 0, '', 'New Glarus Belgian Red and Raspberry Tart, Bell&rsquo;s Cherry Stout, Dogfish Head Aprihop, Great Divide Wild Raspberry Ale, Founders Rubæus, Ebulum Elderberry Black Ale, Stiegl Radler, Weyerbacher Raspberry Imperial Stout, Abita Purple Haze, Melbourne Apricot Beer and Strawberry Beer, Saxer Lemon Lager, Magic Hat #9, Grozet Gooseberry and Wheat Ale, Pyramid Apricot Ale, Dogfish Head Fort.', 'Entrant must specify the underlying beer style as well as the type of fruit(s) used.'), ";
		$updateSQL .= "(75, 'A', 'Spice, Herb, or Vegetable Beer', 'Spice / Herb / Vegetable Beer', '', '', '', '', '', '', '', '', '', '', '', 'A harmonious marriage of spices, herbs and/or vegetables and beer. The key attributes of the underlying style will be different with the addition of spices, herbs and/or vegetables; do not expect the base beer to taste the same as the unadulterated version. Judge the beer based on the pleasantness and balance of the resulting combination.', 'http://www.bjcp.org/2008styles/style21.php#1a', '21', 'Y', 'bcoe', 'BJCP2008', 1, 0, 0, 0, '', 'Alesmith Speedway Stout, Founders Breakfast Stout, Traquair Jacobite Ale, Rogue Chipotle Ale, Young&rsquo;s Double Chocolate Stout, Bell&rsquo;s Java Stout, Fraoch Heather Ale, Southampton Pumpkin Ale, Rogue Hazelnut Nectar, Hitachino Nest Real Ginger Ale, Breckenridge Vanilla Porter, Left Hand JuJu Ginger Beer, Dogfish Head Punkin Ale, Dogfish Head Midas Touch, Redhook Double Black Stout, Buffalo Bill&rsquo;s Pumpkin Ale, BluCreek Herbal Ale, Christian Moerlein Honey Almond, Rogue Chocolate Stout, Birrificio Baladin Nora, Cave Creek Chili Beer.', 'Entrant must specify the underlying beer style as well as the type of spices, herbs, or vegetables used. Classic styles do not have to be cited.'), ";
		$updateSQL .= "(76, 'B', 'Christmas/Winter Specialty Spiced Beer', 'Spice / Herb / Vegetable Beer', '', '', '', '', '', '', '', '', '', '', '', 'A stronger, darker, spiced beer that often has a rich body and warming finish suggesting a good accompaniment for the cold winter season.', 'http://www.bjcp.org/2008styles/style21.php#1b', '21', 'Y', 'bcoe', 'BJCP2008', 1, 0, 0, 0, '', 'Anchor Our Special Ale, Harpoon Winter Warmer, Weyerbacher Winter Ale, Nils Oscar Julol, Goose Island Christmas Ale, North Coast Wintertime Ale, Great Lakes Christmas Ale, Lakefront Holiday Spice Lager Beer, Samuel Adams Winter Lager, Troegs The Mad Elf, Jamtlands Julol.', 'Entrant may specify an underlying beer style as well as the special ingredients used. The base style, spices, or other ingredients need not be identified. The beer must include spices and may inlcude other fermentables or fruit.'), ";
		$updateSQL .= "(77, 'A', 'Classic Rauchbier', 'Smoke-Flavored and Wood-Aged Beer', '1.05', '1.057', '1.012', '1.016', '4.8', '6', '20', '30', '12', '22', 'Lager', 'Marzen/Oktoberfest-style beer with a sweet, smoky aroma and flavor and a somewhat darker color.', 'http://www.bjcp.org/2008styles/style22.php#1a', '22', 'Y', 'bcoe', 'BJCP2008', 0, 0, 0, 0, '', 'Schlenkerla Rauchbier Marzen, Kaiserdom Rauchbier, Eisenbahn Rauchbier, Victory Scarlet Fire Rauchbier, Spezial Rauchbier Marzen, Saranac Rauchbier.', ''), ";
		$updateSQL .= "(78, 'B', 'Other Smoked Beer', 'Smoke-Flavored and Wood-Aged Beer', '', '', '', '', '', '', '', '', '', '', '', 'This is any beer that is exhibiting smoke as a principle flavor and aroma characteristic other than the Bamberg-style Rauchbier (i.e. beechwood-smoked Marzen). Balance in the use of smoke, hops and malt character is exhibited by the better examples.', 'http://www.bjcp.org/2008styles/style22.php#1b', '22', 'Y', 'bcoe', 'BJCP2008', 1, 0, 0, 0, '', 'Alaskan Smoked Porter, O&rsquo;Fallons Smoked Porter, Spezial Lagerbier, Weissbier and Bockbier, Stone Smoked Porter, Schlenkerla Weizen Rauchbier and Ur-Bock Rauchbier, Rogue Smoke, Oskar Blues Old Chub, Left Hand Smoke Jumper, Dark Horse Fore Smoked Stout, Magic Hat Jinx. ', 'Entrant must specify the type of wood or other source of smoke. If the beer is based on a classic style then the specific style must be specified. Classic styles do not have to be cited.'), ";
		$updateSQL .= "(79, 'C', 'Wood-Aged Beer', 'Smoke-Flavored and Wood-Aged Beer', '', '', '', '', '', '', '', '', '', '', '', 'A harmonious blend of the base beer style with characteristics from aging in contact with wood (including any alcoholic products previously in contact with the wood). The best examples will be smooth, flavorful, well-balanced and well-aged. ', 'http://www.bjcp.org/2008styles/style22.php#1c', '22', 'Y', 'bcoe', 'BJCP2008', 1, 0, 0, 0, '', 'The Lost Abbey Angel&rsquo;s Share Ale, J.W. Lees Harvest Ale in Port, Sherry, Lagavulin Whisky or Calvados Casks, Bush Prestige, Petrus Aged Pale, Firestone Walker Double Barrel Ale, Dominion Oak Barrel Stout, New Holland Dragons Milk, Great Divide Oak Aged Yeti Imperial Stout, Goose Island Bourbon County Stout, Le Coq Imperial Extra Double Stout, Harviestoun Old Engine Oil Special Reserve, many microbreweries have specialty beers served only on premises often directly from the cask.', 'Entrant must specify the type of wood if a varietal character is noticeable. If the beer is based on a classic style then the specific style must be specified. Classic styles do not have to be cited.'), ";
		$updateSQL .= "(80, 'A', 'Specialty Beer', 'Specialty Beer', '', '', '', '', '', '', '', '', '', '', '', 'A harmonious marriage of ingredients, processes and beer. The key attributes of the underlying style (if declared) will be atypical due to the addition of special ingredients or techniques; do not expect the base beer to taste the same as the unadulterated version. Judge the beer based on the pleasantness and harmony of the resulting combination. The overall uniqueness of the process, ingredients used, and creativity should be considered. The overall rating of the beer depends heavily on the inherently subjective assessment of distinctiveness and drinkability. Entry Instructions: The brewer must specify the experimental nature of the beer (e.g., the type of special ingredients used, process utilized, or historical style being brewed), or why the beer doesn&rsquo;t fit into an established style.', 'http://www.bjcp.org/2008styles/style23.php#1a', '23', 'Y', 'bcoe', 'BJCP2008', 1, 0, 0, 0, '', 'Bell&rsquo;s Rye Stout, Bell&rsquo;s Eccentric Ale, Samuel Adams Triple Bock and Utopias, Hair of the Dog Adam, Great Alba Scots Pine, Tommyknocker Maple Nut Brown Ale, Great Divide Bee Sting Honey Ale, Stoudt&rsquo;s Honey Double Mai Bock, Rogue Dad&rsquo;s Little Helper, Rogue Honey Cream Ale, Dogfish Head India Brown Ale, Zum Uerige Sticke and Doppel Sticke Altbier, Yards Brewing Company General Washington Tavern Porter, Rauchenfels Steinbier, Odells 90 Shilling Ale, Bear Republic Red Rocket Ale, Stone Arrogant Bastard.', 'The brewer must specify the experimental nature of the beer (e.g., the type of special ingredients used, process utilized, or historical style being brewed), or why the beer doesn&rsquo;t fit into an established style.'); ";

		mysqli_select_db($connection,$database);
		mysqli_real_escape_string($connection,$updateSQL);
		$result = mysqli_query($connection,$updateSQL) or die (mysqli_error($connection));

		$updateSQL = "INSERT INTO ".$prefix."styles (id, brewStyleNum, brewStyle, brewStyleCategory, brewStyleOG, brewStyleOGMax, brewStyleFG, brewStyleFGMax, brewStyleABV, brewStyleABVMax, brewStyleIBU, brewStyleIBUMax, brewStyleSRM, brewStyleSRMMax, brewStyleType, brewStyleInfo, brewStyleLink, brewStyleGroup, brewStyleActive, brewStyleOwn, brewStyleVersion, brewStyleReqSpec, brewStyleStrength, brewStyleCarb, brewStyleSweet, brewStyleTags, brewStyleComEx, brewStyleEntry) VALUES ";
		$updateSQL .= "(81, 'A', 'Dry Mead', 'Traditional Mead', '', '', '', '', '', '', 'N/A', 'N/A', 'N/A', 'N/A', 'Mead', 'Similar in balance, body, finish and flavor intensity to a dry white wine, with a pleasant mixture of subtle honey character, soft fruity esters, and clean alcohol. Complexity, harmony, and balance of sensory elements are most desirable, with no inconsistencies in color, aroma, flavor or aftertaste. The proper balance of sweetness, acidity, alcohol and honey character is the essential final measure of any mead.', 'http://www.bjcp.org/2008styles/style24.php#1a', '24', 'Y', 'bcoe', 'BJCP2008', 0, 1, 1, 1, '', 'White Winter Dry Mead, Sky River Dry Mead, Intermiel Bouquet Printanier.', ''), ";
		$updateSQL .= "(82, 'B', 'Semi-Sweet Mead', 'Traditional Mead', '', '', '', '', '', '', 'N/A', 'N/A', 'N/A', 'N/A', 'Mead', 'Similar in balance, body, finish and flavor intensity to a semisweet (or medium-dry) white wine, with a pleasant mixture of honey character, light sweetness, soft fruity esters, and clean alcohol. Complexity, harmony, and balance of sensory elements are most desirable, with no inconsistencies in color, aroma, flavor or aftertaste. The proper balance of sweetness, acidity, alcohol and honey character is the essential final measure of any mead.', 'http://www.bjcp.org/2008styles/style24.php#1b', '24', 'Y', 'bcoe', 'BJCP2008', 0, 1, 1, 0, '', 'Lurgashall English Mead, Redstone Traditional Mountain Honey Wine, Sky River Semi-Sweet Mead, Intermiel Verge d&rsquo;Or and Melilot. ', ''), ";
		$updateSQL .= "(83, 'C', 'Sweet Mead', 'Traditional Mead', '', '', '', '', '', '', 'N/A', 'N/A', 'N/A', 'N/A', 'Mead', 'Similar in balance, body, finish and flavor intensity to a well-made dessert wine (such as Sauternes), with a pleasant mixture of honey character, residual sweetness, soft fruity esters, and clean alcohol. Complexity, harmony, and balance of sensory elements are most desirable, with no inconsistencies in color, aroma, flavor or aftertaste. The proper balance of sweetness, acidity, alcohol and honey character is the essential final measure of any mead.', 'http://www.bjcp.org/2008styles/style24.php#1c', '24', 'Y', 'bcoe', 'BJCP2008', 0, 1, 1, 0, '', 'Lurgashall Christmas Mead, Chaucer&rsquo;s Mead, Rabbit&rsquo;s Foot Sweet Wildflower Honey Mead, Intermiel Benoîte.', ''), ";
		$updateSQL .= "(84, 'A', 'Cyser', 'Melomel (Fruit Mead)', '', '', '', '', '', '', 'N/A', 'N/A', 'N/A', 'N/A', 'Mead', 'In well-made examples of the style, the fruit is both distinctive and well-incorporated into the honey-sweet-acid-tannin-alcohol balance of the mead. Some of the best strong examples have the taste and aroma of an aged Calvados (apple brandy from northern France), while subtle, dry versions can taste similar to many fine white wines.', 'http://www.bjcp.org/2008styles/style25.php#1a', '25', 'Y', 'bcoe', 'BJCP2008', 0, 1, 1, 0, '', 'White Winter Cyser, Rabbit&rsquo;s Foot Apple Cyser, Long Island Meadery Apple Cyser.', ''), ";
		$updateSQL .= "(85, 'B', 'Pyment', 'Melomel (Fruit Mead)', '', '', '', '', '', '', 'N/A', 'N/A', 'N/A', 'N/A', 'Mead', 'In well-made examples of the style, the grape is both distinctively vinous and well-incorporated into the honey-sweet-acid-tannin-alcohol balance of the mead. White and red versions can be quite different, and the overall impression should be characteristic of the type of grapes used and suggestive of a similar variety wine.', 'http://www.bjcp.org/2008styles/style25.php#1b', '25', 'Y', 'bcoe', 'BJCP2008', 0, 1, 1, 1, '', 'Redstone Pinot Noir and White Pyment Mountain Honey Wines.', ''), ";
		$updateSQL .= "(86, 'C', 'Other Fruit Melomel', 'Melomel (Fruit Mead)', '', '', '', '', '', '', 'N/A', 'N/A', 'N/A', 'N/A', 'Mead', 'In well-made examples of the style, the fruit is both distinctive and well-incorporated into the honey-sweet-acid-tannin-alcohol balance of the mead. Different types of fruit can result in widely different characteristics; allow for a variation in the final product.', 'http://www.bjcp.org/2008styles/style25.php#1c', '25', 'Y', 'bcoe', 'BJCP2008', 1, 1, 1, 1, '', 'White Winter Blueberry, Raspberry and Strawberry Melomels, Redstone Black Raspberry and Sunshine Nectars, Bees Brothers Raspberry Mead, Intermiel Honey Wine and Raspberries, Honey Wine and Blueberries, and Honey Wine and Blackcurrants, Long Island Meadery Blueberry Mead, Mountain Meadows Cranberry and Cherry Meads.', 'Entrants must specify the varieties of fruit used.'), ";
		$updateSQL .= "(87, 'A', 'Metheglin', 'Other Mead', '', '', '', '', '', '', 'N/A', 'N/A', 'N/A', 'N/A', 'Mead', 'Often, a blend of spices may give a character greater than the sum of its parts. The better examples of this style use spices/herbs subtly and when more than one are used, they are carefully selected so that they blend harmoniously.', 'http://www.bjcp.org/2008styles/style26.php#1a', '26', 'Y', 'bcoe', 'BJCP2008', 1, 1, 1, 1, '', 'Bonair Chili Mead, Redstone Juniper Mountain Honey Wine, Redstone Vanilla Beans and Cinnamon Sticks Mountain Honey Wine, Long Island Meadery Vanilla Mead, iQhilika Africa Birds Eye Chili Mead, Mountain Meadows Spice Nectar.', 'Entrants must specify the types of spices used.'), ";
		$updateSQL .= "(88, 'B', 'Braggot', 'Other Mead', '', '', '', '', '', '', 'N/A', 'N/A', 'N/A', 'N/A', 'Mead', 'A harmonious blend of mead and beer, with the distinctive characteristics of both. A wide range of results are possible, depending on the base style of beer, variety of honey and overall sweetness and strength. Beer flavors tend to somewhat mask typical honey flavors found in other meads.', 'http://www.bjcp.org/2008styles/style26.php#1b', '26', 'Y', 'bcoe', 'BJCP2008', 0, 1, 1, 1, '', 'Rabbit&rsquo;s Foot Diabhal and Biere de Miele, Magic Hat Braggot, Brother Adams Braggot Barleywine Ale, White Winter Traditional Brackett.', ''), ";
		$updateSQL .= "(89, 'C', 'Open Category Mead', 'Other Mead', '', '', '', '', '', '', 'N/A', 'N/A', 'N/A', 'N/A', 'Mead', 'See standard description for entrance requirements. Entrants MUST specify carbonation level, strength, and sweetness. Entrants MAY specify honey varieties.', 'http://www.bjcp.org/2008styles/style26.php#1c', '26', 'Y', 'bcoe', 'BJCP2008', 1, 1, 1, 1, '', 'Jadwiga, Hanssens/Lurgashall Mead the Gueuze, Rabbit&rsquo;s Foot Private Reserve Pear Mead, White Winter Cherry Bracket, Saba Tej, Mountain Meadows Trickster&rsquo;s Treat Agave Mead, Intermiel Rosee.', 'See standard description for entrance requirements. Entrants must specify carbonation level, strength, and sweetness. Entrants may specify honey varieties. Entrants must specify the special nature of the mead, whether it is a combination of existing styles, an experimental mead, a historical mead, or some other creation. Any special ingredients that impart an identifiable character may be declared.'), ";
		$updateSQL .= "(90, 'A', 'Common Cider', 'Standard Cider and Perry', '1.045', '1.065', '1', '1.02', '5', '8', 'N/A', 'N/A', 'N/A', 'N/A', 'Cider', 'Variable, but should be a medium, refreshing drink. Sweet ciders must not be cloying. Dry ciders must not be too austere. An ideal cider serves well as a &quot;session&quot; drink, and suitably accompanies a wide variety of food.', 'http://www.bjcp.org/2008styles/style27.php#1a', '27', 'Y', 'bcoe', 'BJCP2008', 0, 0, 1, 1, '', '[US] Red Barn Cider Jonagold Semi-Dry and Sweetie Pie (WA), AEppelTreow Barn Swallow Draft Cider (WI), Wandering Aengus Heirloom Blend Cider (OR), Uncle John&rsquo;s Fruit House Winery Apple Hard Cider (MI), Bellwether Spyglass (NY), West County Pippin (MA), White Winter Hard Apple Cider (WI), Harpoon Cider (MA)', ''); ";

		mysqli_select_db($connection,$database);
		mysqli_real_escape_string($connection,$updateSQL);
		$result = mysqli_query($connection,$updateSQL) or die (mysqli_error($connection));

		$updateSQL = "INSERT INTO ".$prefix."styles (id, brewStyleNum, brewStyle, brewStyleCategory, brewStyleOG, brewStyleOGMax, brewStyleFG, brewStyleFGMax, brewStyleABV, brewStyleABVMax, brewStyleIBU, brewStyleIBUMax, brewStyleSRM, brewStyleSRMMax, brewStyleType, brewStyleInfo, brewStyleLink, brewStyleGroup, brewStyleActive, brewStyleOwn, brewStyleVersion, brewStyleReqSpec, brewStyleStrength, brewStyleCarb, brewStyleSweet, brewStyleTags, brewStyleComEx, brewStyleEntry) VALUES ";
		$updateSQL .= "(91, 'B', 'English Cider', 'Standard Cider and Perry', '1.05', '1.075', '0.995', '1.01', '6', '9', 'N/A', 'N/A', 'N/A', 'N/A', 'Cider', 'Generally dry, full-bodied, austere.', 'http://www.bjcp.org/2008styles/style27.php#1b', '27', 'Y', 'bcoe', 'BJCP2008', 0, 0, 1, 1, '', '[US] Westcott Bay Traditional Very Dry, Traditional Dry and Traditional Medium Sweet (WA), Farnum Hill Extra-Dry, Dry, and Farmhouse (NH), Wandering Aengus Dry Cider (OR), Red Barn Cider Burro Loco (WA), Bellwether Heritage (NY); [UK] Oliver&rsquo;s Herefordshire Dry Cider, various from Hecks, Dunkerton, Burrow Hill, Gwatkin Yarlington Mill, Aspall Dry Cider', ''), ";
		$updateSQL .= "(92, 'C', 'French Cider', 'Standard Cider and Perry', '1.05', '1.065', '1.01', '1.02', '3', '6', 'N/A', 'N/A', 'N/A', 'N/A', 'Cider', 'Medium to sweet, full-bodied, rich.', 'http://www.bjcp.org/2008styles/style27.php#1c', '27', 'Y', 'bcoe', 'BJCP2008', 0, 0, 1, 1, '', '[US] West County Reine de Pomme (MA), Rhyne Cider (CA); [France] Eric Bordelet (various), Etienne Dupont, Etienne Dupont Organic, Bellot.', ''), ";
		$updateSQL .= "(93, 'D', 'Common Perry', 'Standard Cider and Perry', '1.05', '1.06', '1', '1.02', '5', '7', 'N/A', 'N/A', 'N/A', 'N/A', 'Cider', 'Mild. Medium to medium-sweet. Still to lightly sparkling. Only very slight acetification is acceptable. Mousiness, ropy/oily characters are serious faults', 'http://www.bjcp.org/2008styles/style27.php#1d', '27', 'Y', 'bcoe', 'BJCP2008', 0, 0, 1, 1, '', '[US] White Winter Hard Pear Cider (WI), AEppelTreow Perry (WI), Blossomwood Laughing Pig Perry (CO), Uncle John&rsquo;s Fruit House Winery Perry (MI).', ''), ";
		$updateSQL .= "(94, 'E', 'Traditional Perry', 'Standard Cider and Perry', '1.05', '1.07', '1', '1.02', '5', '9', 'N/A', 'N/A', 'N/A', 'N/A', 'Cider', 'Tannic. Medium to medium-sweet. Still to lightly sparkling. Only very slight acetification is acceptable. Mousiness, ropy/oily characters are serious faults.', 'http://www.bjcp.org/2008styles/style27.php#1e', '27', 'Y', 'bcoe', 'BJCP2008', 1, 0, 1, 1, '', '[France] Bordelet Poire Authentique and Poire Granit, Christian Drouin Poire, [UK] Gwatkin Blakeney Red Perry, Oliver&rsquo;s Blakeney Red Perry and Herefordshire Dry Perry.', 'Entrant must specify the variety of pears used.'), ";
		$updateSQL .= "(95, 'A', 'New England Cider', 'Specialty Cider and Perry', '1.06', '1.1', '0.995', '1.01', '7', '13', 'N/A', 'N/A', 'N/A', 'N/A', 'Cider', 'Adjuncts may include white and brown sugars, molasses, small amounts of honey, and raisins. Adjuncts are intended to raise OG well above that which would be achieved by apples alone. This style is sometimes barrel-aged, in which case there will be oak character as with a barrel-aged wine. If the barrel was formerly used to age spirits, some flavor notes from the spirit (e.g., whisky or rum) may also be present, but must be subtle.', 'http://www.bjcp.org/2008styles/style28.php#1a', '28', 'Y', 'bcoe', 'BJCP2008', 1, 0, 1, 1, '', '', 'Entrants must specify if the cider was barrel-fermented or aged. Entrants must specify carbonation level (still, petillant, or sparkling). Entrants must specify sweetness (dry, medium, or sweet).'), ";
		$updateSQL .= "(96, 'B', 'Fruit Cider', 'Specialty Cider and Perry', '1.045', '1.07', '0.995', '1.01', '5', '9', 'N/A', 'N/A', 'N/A', 'N/A', 'Cider', 'Like a dry wine with complex flavors. The apple character must marry with the added fruit so that neither dominates the other. ', 'http://www.bjcp.org/2008styles/style28.php#1b', '28', 'Y', 'bcoe', 'BJCP2008', 1, 0, 1, 1, '', '[US] West County Blueberry-Apple Wine (MA), AEppelTreow Red Poll Cran-Apple Draft Cider (WI), Bellwether Cherry Street (NY), Uncle John&rsquo;s Fruit Farm Winery Apple Cherry Hard Cider (MI).', 'Entrants must specify what fruit(s) and/or fruit juice(s) were added.'), ";
		$updateSQL .= "(97, 'C', 'Applewine', 'Specialty Cider and Perry', '1.07', '1.1', '0.995', '1.01', '9', '12', 'N/A', 'N/A', 'N/A', 'N/A', 'Cider', 'Like a dry white wine, balanced, and with low astringency and bitterness.', 'http://www.bjcp.org/2008styles/style28.php#1c', '28', 'Y', 'bcoe', 'BJCP2008', 1, 0, 1, 1, '', '[US] AEppelTreow Summer&rsquo;s End (WI), Wandering Aengus Pommeau (OR), Uncle John&rsquo;s Fruit House Winery Fruit House Apple (MI), Irvine&rsquo;s Vintage Ciders (WA).', ''), ";
		$updateSQL .= "(98, 'D', 'Other Specialty Cider or Perry', 'Specialty Cider and Perry', '1.045', '1.1', '0.995', '1.02', '5', '12', 'N/A', 'N/A', 'N/A', 'N/A', 'Cider', '', 'http://www.bjcp.org/2008styles/style28.php#1d', '28', 'Y', 'bcoe', 'BJCP2008', 1, 0, 1, 1, '', '[US] Red Barn Cider Fire Barrel (WA), AEppelTreow Pear Wine and Sparrow Spiced Cider (WI).', 'Entrants must specify all major ingredients and adjuncts. Entrants must specify carbonation level (still, petillant, or sparkling). Entrants must specify sweetness (dry or medium).'); ";

		mysqli_select_db($connection,$database);
		mysqli_real_escape_string($connection,$updateSQL);
		$result = mysqli_query($connection,$updateSQL) or die (mysqli_error($connection));

		$updateSQL = "INSERT INTO ".$prefix."styles (id, brewStyleNum, brewStyle, brewStyleCategory, brewStyleOG, brewStyleOGMax, brewStyleFG, brewStyleFGMax, brewStyleABV, brewStyleABVMax, brewStyleIBU, brewStyleIBUMax, brewStyleSRM, brewStyleSRMMax, brewStyleType, brewStyleInfo, brewStyleLink, brewStyleGroup, brewStyleActive, brewStyleOwn, brewStyleVersion, brewStyleReqSpec, brewStyleStrength, brewStyleCarb, brewStyleSweet, brewStyleTags, brewStyleComEx, brewStyleEntry) VALUES ";
		$updateSQL = "INSERT INTO ".$prefix."styles (id, brewStyleNum, brewStyle, brewStyleCategory, brewStyleOG, brewStyleOGMax, brewStyleFG, brewStyleFGMax, brewStyleABV, brewStyleABVMax, brewStyleIBU, brewStyleIBUMax, brewStyleSRM, brewStyleSRMMax, brewStyleType, brewStyleInfo, brewStyleLink, brewStyleGroup, brewStyleActive, brewStyleOwn, brewStyleVersion, brewStyleReqSpec, brewStyleStrength, brewStyleCarb, brewStyleSweet, brewStyleTags, brewStyleComEx, brewStyleEntry) VALUES ";
		$updateSQL .= "(99, 'A', 'American Light Lager', 'Standard American Beer', '1.028', '1.04', '0.998', '1.008', '2.8', '4.2', '8', '12', '2', '3', 'Lager', 'Highly carbonated, very light-bodied, nearly flavorless lager designed to be consumed very cold. Very refreshing and thirst quenching. ', 'http://bjcp.org/stylecenter.php', '01', 'Y', 'bcoe', 'BJCP2015', 0, 0, 0, 0, 'session-strength, pale-color, bottom-fermented, lagered, north-america, traditional-style, pale-lager-family, balanced', 'Miller Lite, Bud Light, Coors Light, Old Milwaukee Light, Keystone Light, Michelob Light.', ''), ";
		$updateSQL .= "(100, 'B', 'American Lager', 'Standard American Beer', '1.04', '1.05', '1.004', '1.01', '4.5', '5.3', '8', '18', '2', '4', 'Lager', 'A very pale, highly-carbonated, light-bodied, well-attenuated lager with a very neutral flavor profile and low bitterness. Served very cold, it can be a very refreshing and thirst quenching drink.', 'http://bjcp.org/stylecenter.php', '01', 'Y', 'bcoe', 'BJCP2015', 0, 0, 0, 0, 'standard-strength, pale-color, bottom-fermented, lagered, north-america, traditional-style, pale-lager-family, balanced', 'Pabst Blue Ribbon, Miller High Life, Budweiser, Grain Belt Premium Lager, Coors Original, Special Export.', ''), ";
		$updateSQL .= "(101, 'C', 'Cream Ale', 'Standard American Beer', '1.042', '1.055', '1.006', '1.012', '4.2', '5.6', '15', '20', '2.5', '5', 'Ale', 'A clean, well-attenuated, flavorful American lawnmower beer. Easily drinkable and refreshing, with more character than typical American lagers.', 'http://bjcp.org/stylecenter.php', '01', 'Y', 'bcoe', 'BJCP2015', 0, 0, 0, 0, 'standard-strength, pale-color, any-fermentation, north-america, traditional-style, pale-ale-family, balanced', 'Genesee Cream Ale, Little Kings Cream Ale, Sleeman Cream Ale, Liebotschaner Cream Ale, New Glarus Spotted Cow, Old Style.', ''), ";
		$updateSQL .= "(102, 'D', 'American Wheat Beer', 'Standard American Beer', '1.04', '1.055', '1.008', '1.013', '4', '5.5', '15', '30', '3', '6', 'Ale', 'Refreshing wheat beers that can display more hop character and less yeast character than their German cousins. A clean fermentation character allows bready, doughy, or grainy wheat flavors to be complemented by hop flavor and bitterness rather than yeast qualities.', 'http://bjcp.org/stylecenter.php', '01', 'Y', 'bcoe', 'BJCP2015', 0, 0, 0, 0, 'standard-strength, pale-color, any-fermentation, north-america, craft-style, wheat-beer-family, balanced', 'Bell&rsquo;s Oberon, Goose Island 312 Urban Wheat Ale, Widmer Hefeweizen, Boulevard Wheat Beer. ', ''), ";
		$updateSQL .= "(103, 'A', 'International Pale Lager', 'International Lager', '1.042', '1.05', '1.008', '1.012', '4.6', '6', '18', '25', '2', '6', 'Lager', 'A highly-attenuated pale lager without strong flavors, typically well-balanced and highly carbonated. Served cold, it is refreshing and thirst-quenching.', 'http://bjcp.org/stylecenter.php', '02', 'Y', 'bcoe', 'BJCP2015', 0, 0, 0, 0, 'standard-strength, pale-color, bottom-fermented, lagered, traditional-style, pale-lager-family, balanced', 'Heineken, Corona Extra, Asahi Super Dry, Full Sail Session Premium Lager, Birra Moretti, Red Stripe, Singha, Devils Backbone Gold Leaf Lager.', ''), ";
		$updateSQL .= "(104, 'B', 'International Amber Lager', 'International Lager', '1.042', '1.055', '1.008', '1.014', '4.6', '6', '8', '25', '7', '14', 'Lager', 'A well-attenuated malty amber lager with an interesting caramel or toast quality and restrained bitterness. Usually fairly well-attenuated, and can have an adjunct quality to it. Smooth, easily-drinkable lager character.', 'http://bjcp.org/stylecenter.php', '02', 'Y', 'bcoe', 'BJCP2015', 0, 0, 0, 0, 'standard-strength, amber-color, bottom-fermented, lagered, traditional-style, amber-lager-family, malty', 'Schell&rsquo;s Oktoberfest, Capital Winter Skal, Dos Equis Amber, Yuengling Lager, Brooklyn Lager.', ''), ";
		$updateSQL .= "(105, 'C', 'International Dark Lager', 'International Lager', '1.044', '1.056', '1.008', '1.012', '4.2', '6', '8', '20', '14', '22', 'Lager', 'A darker and somewhat sweeter version of international pale lager with a little more body and flavor, but equally restrained in bitterness. The low bitterness leaves the malt as the primary flavor element, and the low hop levels provide very little in the way of balance.', 'http://bjcp.org/stylecenter.php', '02', 'Y', 'bcoe', 'BJCP2015', 0, 0, 0, 0, 'standard-strength, dark-color, bottom-fermented, lagered, traditional-style, dark-lager-family, malty', 'Dixie Blackened Voodoo, Shiner Bock, San Miguel Dark, Baltika 4, Saint Pauli Girl Dark.', ''), ";
		$updateSQL .= "(106, 'A', 'Czech Pale Lager', 'Czech Lager', '1.036', '1.044', '1.008', '1.014', '3', '4', '25', '35', '3', '6', 'Lager', 'A lighter-bodied, rich, refreshing, hoppy, bitter, crisp pale Czech lager having the familiar flavors of the stronger Czech Pilsner-type beer but in a lower alcohol, lighter-bodied, and slightly less intense format.', 'http://bjcp.org/stylecenter.php', '03', 'Y', 'bcoe', 'BJCP2015', 0, 0, 0, 0, 'session-strength, pale-color, bottom-fermented, lagered, central-europe, traditional-style, pale-lager-family, bitter, hoppy', 'Uneticke Pivo 10&deg;, Pivovar Kout na Sumave Koutska 10&deg;, Novosad Glassworks Brewery Hutske Vycepni 8&deg;, Cernyy Orel Svetle 11&deg;, Breznak Svetle Vycepni Pivo, Notch Session Pils.', ''), ";
		$updateSQL .= "(107, 'B', 'Czech Premium Pale Lager', 'Czech Lager', '1.044', '1.056', '1.013', '1.017', '4.2', '5.8', '30', '45', '3.5', '6', 'Lager', 'Rich, characterful pale Czech lager, with considerable malt and hop character and a long, crisp finish. Complex yet well-balanced and refreshing. The malt flavors are complex for a Pilsner-type beer, and the bitterness is strong but clean and without harshness, which gives a rounded impression that enhances drinkability.', 'http://bjcp.org/stylecenter.php', '03', 'Y', 'bcoe', 'BJCP2015', 0, 0, 0, 0, 'standard-strength, pale-color, bottom-fermented, lagered, central-europe, traditional-style, pilsner-family, balanced, hoppy', 'Kout na Sumave Koutska 12&deg;, Uneticka 12&deg;, Pilsner Urquell, Bernard Svatecn', ''), ";
		$updateSQL .= "(108, 'C', 'Czech Amber Lager', 'Czech Lager', '1.044', '1.056', '1.013', '1.017', '4.4', '5.8', '20', '35', '10', '16', 'Lager', 'Malt-driven amber Czech lager with hop character that can vary from low to quite significant. The malt flavors can vary quite a bit, leading to different interpretations ranging from drier, bready, and slightly biscuity to sweeter and somewhat caramelly. ', 'http://bjcp.org/stylecenter.php', '03', 'Y', 'bcoe', 'BJCP2015', 0, 0, 0, 0, 'standard-strength, amber-color, bottom-fermented, lagered, central-europe, traditional-style, amber-lager-family, balanced', 'Cerny Orel polotmava 12&deg;, Primator polotmavy 13&deg;, Jihlavsky Radnicni Pivovar Zikmund, Pivovar Vysoky Chlumec Demon, Pivovar Benesov Sedm kuli, Bernard Jantar.', ''), ";
		$updateSQL .= "(109, 'D', 'Czech Dark Lager', 'Czech Lager', '1.044', '1.056', '1.013', '1.017', '4.4', '5.8', '18', '38', '14', '35', 'Lager', 'A rich, dark, malty Czech lager with a roast character that can vary from almost absent to quite prominent. Malty with an interesting and complex flavor profile, with variable levels of hopping providing a range of possible interpretations. ', 'http://bjcp.org/stylecenter.php', '03', 'Y', 'bcoe', 'BJCP2015', 0, 0, 0, 0, 'standard-strength, dark-color, bottom-fermented, lagered, central-europe, traditional-style, dark-lager-family, balanced', 'Kout na Sumav Tmavy 14&deg;, Pivovar Breznice Herold, U Fleku, Budvar Tmavy Lezak, Bohemian Brewery Cherny Bock 4, Devils Backbone Moran, Notch Cerne Pivo.', ''), ";
		$updateSQL .= "(110, 'A', 'Munich Helles', 'Pale Malty European Lager', '1.044', '1.048', '1.006', '1.012', '4.7', '5.4', '16', '22', '3', '5', 'Lager', 'A clean, malty, gold-colored German lager with a smooth grainy-sweet malty flavor and a soft, dry finish. Subtle spicy, floral, or herbal hops and restrained bitterness help keep the balance malty but not sweet, which helps make this beer a refreshing, everyday drink.', 'http://bjcp.org/stylecenter.php', '04', 'Y', 'bcoe', 'BJCP2015', 0, 0, 0, 0, 'standard-strength, pale-color, bottom-fermented, lagered, central-europe, traditional-style, pale-lager-family, malty', 'Weihenstephaner Original, Hacker-Pschorr Munchner Gold, Burgerbrau Wolznacher Hell Naturtrub, Paulaner Premium Lager, Spaten Premium Lager, Lowenbrau Original.', ''); ";

		mysqli_select_db($connection,$database);
		mysqli_real_escape_string($connection,$updateSQL);
		$result = mysqli_query($connection,$updateSQL) or die (mysqli_error($connection));

		$updateSQL = "INSERT INTO ".$prefix."styles (id, brewStyleNum, brewStyle, brewStyleCategory, brewStyleOG, brewStyleOGMax, brewStyleFG, brewStyleFGMax, brewStyleABV, brewStyleABVMax, brewStyleIBU, brewStyleIBUMax, brewStyleSRM, brewStyleSRMMax, brewStyleType, brewStyleInfo, brewStyleLink, brewStyleGroup, brewStyleActive, brewStyleOwn, brewStyleVersion, brewStyleReqSpec, brewStyleStrength, brewStyleCarb, brewStyleSweet, brewStyleTags, brewStyleComEx, brewStyleEntry) VALUES ";
		$updateSQL .= "(111, 'B', 'Festbier', 'Pale Malty European Lager', '1.054', '1.057', '1.01', '1.012', '5.8', '6.3', '18', '24', '4', '6', 'Lager', 'A smooth, clean, pale German lager with a moderately strong malty flavor and a light hop character. Deftly balances strength and drinkability, with a palate impression and finish that encourages drinking. Showcases elegant German malt flavors without becoming too heavy or filling.', 'http://bjcp.org/stylecenter.php', '04', 'Y', 'bcoe', 'BJCP2015', 0, 0, 0, 0, 'standard-strength, pale-color, bottom-fermented, lagered, central-europe, traditional-style, pale-lager-family, malty', 'Paulaner Wiesn, Lowenbrau Oktoberfestbier, Hofbrau Festbier, Hacker-Pschorr Superior Festbier, Augustiner Oktoberfest, Schonramer Gold.', ''), ";
		$updateSQL .= "(112, 'C', 'Helles Bock', 'Pale Malty European Lager', '1.064', '1.072', '1.011', '1.008', '6.3', '7.4', '23', '35', '6', '11', 'Lager', 'A relatively pale, strong, malty German lager beer with a nicely attenuated finish that enhances drinkability. The hop character is generally more apparent than in other bocks.', 'http://bjcp.org/stylecenter.php', '04', 'Y', 'bcoe', 'BJCP2015', 0, 0, 0, 0, 'high-strength, pale-color, bottom-fermented, lagered, central-europe, traditional-style, bock-family, malty', 'Ayinger Maibock, Mahr&rsquo;s Bock, Hacker-Pschorr Hubertus Bock, Altenmunster Maibock, Capital Maibock, Einbecker Mai-Urbock, Blind Tiger Maibock.', ''), ";
		$updateSQL .= "(113, 'A', 'German Leichtbier', 'Pale Bitter European Beer', '1.026', '1.034', '1.006', '1.01', '2.4', '3.6', '15', '28', '2', '5', 'Lager', 'A pale, highly-attenuated, light-bodied German lager with lower alcohol and calories than normal-strength beers. Moderately bitter with noticeable malt and hop flavors, the beer is still interesting to drink.', 'http://bjcp.org/stylecenter.php', '05', 'Y', 'bcoe', 'BJCP2015', 0, 0, 0, 0, 'session-strength, pale-color, bottom-fermented, lagered, central-europe, traditional-style, pale-lager-family, bitter, hoppy', 'Bitburger Light, Beck&rsquo;s Light, Paulaner Munchner Hell Leicht, Paulaner Premium Leicht, Mahr&rsquo;s Leicht.', ''), ";
		$updateSQL .= "(114, 'B', 'Kolsch', 'Pale Bitter European Beer', '1.044', '1.05', '1.007', '1.011', '4.4', '5.2', '18', '30', '3.5', '5', 'Mixed', 'A clean, crisp, delicately-balanced beer usually with a very subtle fruit and hop character. Subdued maltiness throughout leads into a pleasantly well-attenuated and refreshing finish. Freshness makes a huge difference with this beer, as the delicate character can fade quickly with age. ', 'http://bjcp.org/stylecenter.php', '05', 'Y', 'bcoe', 'BJCP2015', 0, 0, 0, 0, 'standard-strength, pale-color, top-fermented, lagered, central-europe, traditional-style, pale-ale-family, balanced', 'Fruh Kolsch, Reissdorf Kolsch, Gaffel Kolsch, Sunner Kolsch, Muhlen Kolsch, Sion Kolsch.', ''), ";
		$updateSQL .= "(115, 'C', 'German Exportbier', 'Pale Bitter European Beer', '1.05', '1.056', '1.01', '1.015', '4.8', '6', '20', '30', '4', '7', 'Lager', 'A pale, well-balanced, smooth German lager that is slightly stronger than the average beer with a moderate body and a mild, aromatic hop and malt character. ', 'http://bjcp.org/stylecenter.php', '05', 'Y', 'bcoe', 'BJCP2015', 0, 0, 0, 0, 'standard-strength, pale-color, bottom-fermented, lagered, central-europe, traditional-style, pale-lager-family, balanced', 'DAB Original, Dortmunder Union Export, Dortmunder Kronen, Great Lakes Dortmunder Gold, Barrel House Duveneck&rsquo;s Dortmunder, Gordon Biersch Golden Export, Flensburger Gold.', ''), ";
		$updateSQL .= "(116, 'D', 'German Pils', 'Pale Bitter European Beer', '1.044', '1.05', '1.008', '1.013', '4.4', '5.2', '25', '40', '2', '5', 'Lager', 'A light-bodied, highly-attenuated, gold-colored, bottom-fermented bitter German beer showing excellent head retention and an elegant, floral hop aroma. Crisp, clean, and refreshing, a German Pils showcases the finest quality German malt and hops. ', 'http://bjcp.org/stylecenter.php', '05', 'Y', 'bcoe', 'BJCP2015', 0, 0, 0, 0, 'standard-strength, pale-color, bottom-fermented, lagered, central-europe, traditional-style, pilsner-family, bitter, hoppy', 'Schonramer Pils, Trumer Pils, Konig Pilsener, Paulaner Premium Pils, Stoudt Pils, Troegs Sunshine Pils.', ''), ";
		$updateSQL .= "(117, 'A', 'Marzen', 'Amber Malty European Lager', '1.054', '1.06', '1.01', '1.014', '5.8', '6.3', '18', '24', '10', '17', 'Lager', 'An elegant, malty German amber lager with a clean, rich, toasty and bready malt flavor, restrained bitterness, and a dry finish that encourages another drink. The overall malt impression is soft, elegant, and complex, with a rich aftertaste that is never cloying or heavy.', 'http://bjcp.org/stylecenter.php', '06', 'Y', 'bcoe', 'BJCP2015', 0, 0, 0, 0, 'standard-strength, amber-color, bottom-fermented, lagered, central-europe, traditional-style, amber-lager-family, malty', 'Buergerliches Brauhaus Saalfeld Ur-Saalfelder, Paulaner Oktoberfest, Ayinger Oktoberfest-Marzen, Hacker-Pschorr Original Oktoberfest, Weltenburg Kloster Anno 1050.', ''), ";
		$updateSQL .= "(118, 'B', 'Rauchbier', 'Amber Malty European Lager', '1.05', '1.057', '1.012', '1.015', '4.8', '6', '20', '30', '12', '22', 'Lager', 'Medium body. Medium to medium-high carbonation. Smooth lager character. Significant astringent, phenolic harshness is inappropriate.', 'http://bjcp.org/stylecenter.php', '06', 'Y', 'bcoe', 'BJCP2015', 0, 0, 0, 0, 'standard-strength, amber-color, bottom-fermented, lagered, central-europe, traditional-style, amber-lager-family, malty, smoke', 'Schlenkerla Rauchbier Marzen, Kaiserdom Rauchbier, Eisenbahn Defumada, Spezial Rauchbier Marzen, Victory Scarlet Fire Rauchbier.', ''), ";
		$updateSQL .= "(119, 'C', 'Dunkels Bock', 'Amber Malty European Lager', '1.054', '1.072', '0.013', '1.019', '6.3', '7.2', '20', '27', '14', '22', 'Lager', 'A dark, strong, malty German lager beer that emphasizes the malty-rich and somewhat toasty qualities of continental malts without being sweet in the finish.', 'http://bjcp.org/stylecenter.php', '06', 'Y', 'bcoe', 'BJCP2015', 0, 0, 0, 0, 'high-strength, amber-color, bottom-fermented, lagered, central-europe, traditional-style, bock-family, malty', 'Einbecker Ur-Bock Dunkel, Kneitinger Bock, Aass Bock, Great Lakes Rockefeller Bock, New Glarus Uff-da Bock, Penn Brewery St. Nikolaus Bock.', ''), ";
		$updateSQL .= "(120, 'A', 'Vienna Lager', 'Amber Bitter European Beer', '1.048', '1.055', '1.01', '1.014', '4.7', '5.5', '18', '30', '9', '15', 'Lager', 'A moderate-strength amber lager with a soft, smooth maltiness and moderate bitterness, yet finishing relatively dry. The malt flavor is clean, bready-rich, and somewhat toasty, with an elegant impression derived from quality base malts and process, not specialty malts and adjuncts.', 'http://bjcp.org/stylecenter.php', '07', 'Y', 'bcoe', 'BJCP2015', 0, 0, 0, 0, 'standard-strength, amber-color, bottom-fermented, lagered, central-europe, traditional-style, amber-lager-family, balanced', 'Clipper City Heavy Seas Vienna Lager, Cuauhtemoc Noche Buena, Chuckanut Vienna Lager, Devils Backbone Vienna Lager, Schell&rsquo;s Firebrick, Figueroa Mountain Danish Red Lager.', ''); ";
		mysqli_select_db($connection,$database);
		mysqli_real_escape_string($connection,$updateSQL);
		$result = mysqli_query($connection,$updateSQL) or die (mysqli_error($connection));

		$updateSQL = "INSERT INTO ".$prefix."styles (id, brewStyleNum, brewStyle, brewStyleCategory, brewStyleOG, brewStyleOGMax, brewStyleFG, brewStyleFGMax, brewStyleABV, brewStyleABVMax, brewStyleIBU, brewStyleIBUMax, brewStyleSRM, brewStyleSRMMax, brewStyleType, brewStyleInfo, brewStyleLink, brewStyleGroup, brewStyleActive, brewStyleOwn, brewStyleVersion, brewStyleReqSpec, brewStyleStrength, brewStyleCarb, brewStyleSweet, brewStyleTags, brewStyleComEx, brewStyleEntry) VALUES ";
		$updateSQL .= "(121, 'B', 'Altbier', 'Amber Bitter European Beer', '1.044', '1.052', '1.008', '1.014', '4.3', '5.5', '25', '50', '11', '17', 'Ale', 'A well-balanced, well-attenuated, bitter yet malty, clean, and smooth, amber- to copper-colored German beer. The bitterness is balanced by the malt richness, but the malt intensity and character can range from moderate to high (the bitterness increases with the malt richness). ', 'http://bjcp.org/stylecenter.php', '07', 'Y', 'bcoe', 'BJCP2015', 0, 0, 0, 0, 'standard-strength, amber-color, top-fermented, lagered, central-europe, traditional-style, amber-ale-family, bitter', 'Zum Uerige, Im Fuuchschen, Schumacher, Zum Schluussel, Schlosser Alt, Bolten Alt, Diebels Alt, Frankenheim Alt, Southampton Alt, BluCreek Altbier.', ''), ";
		$updateSQL .= "(122, 'C', 'Kellerbier', 'Amber Bitter European Beer', '', '', '', '', '', '', '', '', '', '', 'Lager', 'Young, unfiltered, unpasteurized versions of the traditional German beer styles, traditionally served on tap from the lagering vessel. The name literally means &quot;cellar beer&quot; - implying a young, fresh beer served straight from the lagering cellar. Since this serving method can be applied to a wide range of beers, the style is somewhat hard to pin down. However, there are several common variants that can be described and used as templates for other versions. Sometimes described as Naturtrub or naturally cloudy. Also sometimes called Zwickelbier, after the name of the tap used to sample from a lagering tank.', 'http://bjcp.org/stylecenter.php', '07', 'Y', 'bcoe', 'BJCP2015', 1, 0, 0, 0, 'standard-strength, pale-color, amber-color, bottom-fermented, central-europe, traditional-style, balanced, pale-lager-family, amber-lager-family', '', 'The entrant must specify whether the entry is a Munich Kellerbier (pale, based on Helles) or a Franconian Kellerbier (amber, based on Marzen). The entrant may specify another type of Kellerbier based on other base styles such as Pils, Bock, Schwarzbier, but should supply a style description for judges.'), ";
		$updateSQL .= "(123, 'A', 'Munich Dunkel', 'Dark European Lager', '1.048', '1.056', '1.01', '1.016', '4.5', '5.6', '18', '28', '14', '28', 'Lager', 'Characterized by depth, richness and complexity typical of darker Munich malts with the accompanying Maillard products. Deeply bready-toasty, often with chocolate-like flavors in the freshest examples, but never harsh, roasty, or astringent; a decidedly malt-balanced beer, yet still easily drinkable.', 'http://bjcp.org/stylecenter.php', '08', 'Y', 'bcoe', 'BJCP2015', 0, 0, 0, 0, 'standard-strength, dark-color, bottom-fermented, lagered, central-europe, traditional-style, malty, dark-lager-family', 'Ayinger Altbairisch Dunkel, Hacker-Pschorr Alt Munich Dark, Weltenburger Kloster Barock-Dunkel, Ettaler Kloster Dunkel, Chuckanut Dunkel.', ''), ";
		$updateSQL .= "(124, 'B', 'Schwarzbier', 'Dark European Lager', '1.046', '1.052', '1.01', '1.016', '4.4', '5.4', '20', '30', '17', '30', 'Lager', 'A dark German lager that balances roasted yet smooth malt flavors with moderate hop bitterness. The lighter body, dryness, and lack of a harsh, burnt, or heavy aftertaste helps make this beer quite drinkable.', 'http://bjcp.org/stylecenter.php', '08', 'Y', 'bcoe', 'BJCP2015', 0, 0, 0, 0, 'standard-strength, dark-color, bottom-fermented, lagered, central-europe, traditional-style, balanced, dark-lager-family', 'Kostritzer Schwarzbier, Kulmbacher Monchshof Premium Schwarzbier, Original Badebier, Einbecker Schwarzbier, TAPS Schwarzbier, Devils Backbone Schwartz Bier.', ''), ";
		$updateSQL .= "(125, 'A', 'Doppelbock', 'Strong European Beer', '1.072', '1.112', '1.016', '1.024', '7', '10', '16', '26', '6', '25', 'Lager', 'A strong, rich, and very malty German lager that can have both pale and dark variants. The darker versions have more richly-developed, deeper malt flavors, while the paler versions have slightly more hops and dryness.', 'http://bjcp.org/stylecenter.php', '09', 'Y', 'bcoe', 'BJCP2015', 1, 0, 0, 0, 'high-strength, amber-color, pale-color, bottom-fermented, lagered, central-europe, traditional-style, bock-family, malty', 'Dark Versions - Andechser Doppelbock Dunkel, Ayinger Celebrator, Paulaner Salvator, Spaten Optimator, Troegs Troegenator, Weihenstephaner Korbinian. Pale Versions - Eggenberg Urbock 23, EKU 28, Plank Bavarian Heller Doppelbock.', 'The entrant must specify whether the entry is a pale or a dark variant.'), ";
		$updateSQL .= "(126, 'B', 'Eisbock', 'Strong European Beer', '1.078', '1.12', '1.02', '1.035', '9', '14', '25', '35', '18', '30', 'Lager', 'A strong, full-bodied, rich, and malty dark German lager often with a viscous quality and strong flavors. Even though flavors are concentrated, the alcohol should be smooth and warming, not burning. ', 'http://bjcp.org/stylecenter.php', '09', 'Y', 'bcoe', 'BJCP2015', 0, 0, 0, 0, 'very-high-strength, amber-color, bottom-fermented, lagered, central-europe, traditional-style, bock-family, malty', 'Kulmbacher Eisbock, Eggenberg Urbock Dunkel Eisbock, Niagara Eisbock, Southampton Double Ice Bock, Capital Eisphyre.', ''), ";
		$updateSQL .= "(127, 'C', 'Baltic Porter', 'Strong European Beer', '1.06', '1.09', '1.016', '1.024', '6.5', '9.5', '20', '40', '17', '30', 'Lager', 'Baltic Porter often has the malt flavors reminiscent of an English brown porter and the restrained roast of a schwarzbier, but with a higher OG and alcohol content than either. Very complex, with multi-layered malt and dark fruit flavors.', 'http://bjcp.org/stylecenter.php', '09', 'Y', 'bcoe', 'BJCP2015', 0, 0, 0, 0, 'high-strength, dark-color, any-fermentation, lagered, eastern-europe, traditional-style, porter-family, malty', 'Sinebrychoff Porter (Finland), Okocim Porter (Poland), Aldaris Porteris (Latvia), Baltika 6 Porter (Russia), Utenos Porter (Lithuania), Stepan Razin Porter (Russia), Zywiec Porter (Poland), Nogne o Porter (Norway), Neuzeller Kloster-Brou Neuzeller Porter (Germany).', ''), ";
		$updateSQL .= "(128, 'A', 'Weissbier', 'German Wheat Beer', '1.044', '1.052', '1.01', '1.014', '4.3', '5.6', '8', '16', '2', '6', 'Ale', 'A pale, refreshing German wheat beer with high carbonation, dry finish, a fluffy mouthfeel, and a distinctive banana-and-clove yeast character.', 'http://bjcp.org/stylecenter.php', '10', 'Y', 'bcoe', 'BJCP2015', 0, 0, 0, 0, 'standard-strength, pale-color, top-fermented, central-europe, traditional-style, wheat-beer-family, malty', 'Weihenstephaner Hefeweissbier, Schneider Weisse Weizenhell, Paulaner Hefe-Weizen, Hacker-Pschorr Weisse, Ayinger Brau Weisse.', ''), ";
		$updateSQL .= "(129, 'B', 'Dunkels Weissbier', 'German Wheat Beer', '1.044', '1.056', '1.01', '1.014', '4.3', '5.6', '10', '18', '14', '23', 'Ale', 'A moderately dark German wheat beer with a distinctive banana-and-clove yeast character, supported by a toasted bread or caramel malt flavor. Highly carbonated and refreshing, with a creamy, fluffy texture and light finish that encourages drinking. ', 'http://bjcp.org/stylecenter.php', '10', 'Y', 'bcoe', 'BJCP2015', 0, 0, 0, 0, 'standard-strength, amber-color, top-fermented, central-europe, traditional-style, wheat-beer-family, malty', 'Weihenstephaner Hefeweissbier Dunkel, Ayinger Ur-Weisse, Franziskaner Dunkel Hefe-Weisse, Ettaler Weissbier Dunkel, Hacker-Pschorr Weisse Dark, Tucher Dunkles Hefe Weizen.', ''), ";
		$updateSQL .= "(130, 'C', 'Weizenbock', 'German Wheat Beer', '1.064', '1.09', '1.015', '1.022', '6.5', '9', '15', '30', '6', '25', 'Ale', 'A strong, malty, fruity, wheat-based ale combining the best malt and yeast flavors of a weissbier (pale or dark) with the malty-rich flavor, strength, and body of a bock (standard or doppelbock). A weissbier brewed to bock or doppelbock strength. Schneider also produces an Eisbock version. Pale and dark versions exist, although dark are more common. Pale versions have less rich malt complexity and often more hops, as with doppelbocks. Lightly oxidized Maillard products can produce some rich, intense flavors and aromas that are often seen in aged imported commercial products; fresher versions will not have this character. Well-aged examples might also take on a slight sherry-like complexity.', 'http://bjcp.org/stylecenter.php', '10', 'Y', 'bcoe', 'BJCP2015', 1, 0, 0, 0, 'high-strength, amber-color, pale-color, top-fermented, central-europe, traditional-style, wheat-beer-family, malty', 'Dark - Schneider Aventinus, Schneider Aventinus Eisbock, Eisenbahn Vigorosa, Plank Bavarian Dunkler Weizenbock; Pale - Weihenstephaner Vitus, Plank Bavarian Heller Weizenbock.', 'The entrant must specify whether the entry is a pale or a dark variant.'); ";
		mysqli_select_db($connection,$database);
		mysqli_real_escape_string($connection,$updateSQL);
		$result = mysqli_query($connection,$updateSQL) or die (mysqli_error($connection));

		$updateSQL = "INSERT INTO ".$prefix."styles (id, brewStyleNum, brewStyle, brewStyleCategory, brewStyleOG, brewStyleOGMax, brewStyleFG, brewStyleFGMax, brewStyleABV, brewStyleABVMax, brewStyleIBU, brewStyleIBUMax, brewStyleSRM, brewStyleSRMMax, brewStyleType, brewStyleInfo, brewStyleLink, brewStyleGroup, brewStyleActive, brewStyleOwn, brewStyleVersion, brewStyleReqSpec, brewStyleStrength, brewStyleCarb, brewStyleSweet, brewStyleTags, brewStyleComEx, brewStyleEntry) VALUES ";
		$updateSQL .= "(131, 'A', 'Ordinary Bitter', 'British Beer', '1.03', '1.038', '1.007', '1.011', '3.2', '3.8', '25', '35', '8', '14', 'Ale', 'Low gravity, low alcohol levels, and low carbonation make this an easy-drinking session beer. The malt profile can vary in flavor and intensity, but should never override the overall bitter impression. Drinkability is a critical component of the style. ', 'http://bjcp.org/stylecenter.php', '11', 'Y', 'bcoe', 'BJCP2015', 0, 0, 0, 0, 'session-strength, amber-color, top-fermented, british-isles, traditional-style, amber-ale-family, bitter', 'Fuller&rsquo;s Chiswick Bitter, Adnams Bitter, Young&rsquo;s Bitter, Greene King IPA, Brains Bitter, Tetley&rsquo;s Original Bitter.', ''), ";
		$updateSQL .= "(132, 'B', 'Best Bitter', 'British Beer', '1.04', '1.048', '1.008', '1.012', '3.8', '4.6', '25', '40', '8', '16', 'Ale', 'A flavorful, yet refreshing, session beer. Some examples can be more malt balanced, but this should not override the overall bitter impression. Drinkability is a critical component of the style. ', 'http://bjcp.org/stylecenter.php', '11', 'Y', 'bcoe', 'BJCP2015', 0, 0, 0, 0, 'standard-strength, amber-color, top-fermented, british-isles, traditional-style, amber-ale-family, bitter', 'Timothy Taylor Landlord, Fuller&rsquo;s London Pride, Coniston Bluebird Bitter, Adnams SSB, Young&rsquo;s Special, Shepherd Neame Masterbrew Bitter.', ''), ";
		$updateSQL .= "(133, 'C', 'Strong Bitter', 'British Beer', '1.048', '1.06', '1.01', '1.016', '4.6', '6.2', '30', '50', '8', '18', 'Ale', 'An average-strength to moderately-strong English bitter ale. The balance may be fairly even between malt and hops to somewhat bitter. Drinkability is a critical component of the style. A rather broad style that allows for considerable interpretation by the brewer.', 'http://bjcp.org/stylecenter.php', '11', 'Y', 'bcoe', 'BJCP2015', 0, 0, 0, 0, 'session-strength, amber-color, top-fermented, british-isles, traditional-style, amber-ale-family, bitter', 'Shepherd Neame Bishop&rsquo;s Finger, Young&rsquo;s Ram Rod, Samuel Smith&rsquo;s Old Brewery Pale Ale, Bass Ale, Whitbread Pale Ale, Shepherd Neame Spitfire.', ''), ";
		$updateSQL .= "(134, 'A', 'English Golden Ale', 'Pale Commonwealth Beer', '1.038', '1.053', '1.006', '1.012', '3.8', '5', '20', '45', '2', '6', 'Ale', 'A hop-forward, average-strength to moderately-strong pale bitter. Drinkability and a refreshing quality are critical components of the style.', 'http://bjcp.org/stylecenter.php', '12', 'Y', 'bcoe', 'BJCP2015', 0, 0, 0, 0, 'standard-strength, pale-color, top-fermented, british-isles, craft-style, pale-ale-family, bitter, hoppy', 'Hop Back&rsquo;s Summer Lightning, Golden Hill&rsquo;s Exmoor Gold, Oakham&rsquo;s Jeffrey Hudson Bitter, Fuller&rsquo;s Discovery, Kelham Island&rsquo;s Pale Rider, Crouch Vale&rsquo;s Brewers Gold, Morland Old Golden Hen.', ''), ";
		$updateSQL .= "(135, 'B', 'Australian Sparkling Ale', 'Pale Commonwealth Beer', '1.038', '1.05', '1.004', '1.006', '4.5', '6', '20', '35', '4', '7', 'Ale', 'Smooth and balanced, all components merge together with similar intensities. Moderate flavors showcasing Australian ingredients. Large flavor dimension. Very drinkable, suited to a hot climate. Relies on yeast character. ', 'http://bjcp.org/stylecenter.php', '12', 'Y', 'bcoe', 'BJCP2015', 0, 0, 0, 0, 'standard-strength, pale-color, top-fermented, pacific, traditional-style, pale-ale-family, bitter', 'Coopers Sparkling Ale, Coopers Original Pale Ale.', ''), ";
		$updateSQL .= "(136, 'C', 'English IPA', 'Pale Commonwealth Beer', '1.05', '1.075', '1.01', '1.018', '5', '7.5', '40', '60', '6', '14', 'Ale', 'A hoppy, moderately-strong, very well-attenuated pale English ale with a dry finish and a hoppy aroma and flavor. Classic English ingredients provide the best flavor profile.', 'http://bjcp.org/stylecenter.php', '12', 'Y', 'bcoe', 'BJCP2015', 0, 0, 0, 0, 'high-strength, pale-color, top-fermented, british-isles, traditional-style, ipa-family, bitter, hoppy', ' Freeminer Trafalgar IPA, Fuller&rsquo;s Bengal Lancer IPA, Worthington White Shield, Ridgeway IPA, Emerson&rsquo;s 1812 IPA, Meantime India Pale Ale, Summit India Pale Ale, Samuel Smith&rsquo;s India Ale, Hampshire Pride of Romsey IPA, Burton Bridge Empire IPA, Marston&rsquo;s Old Empire, Belhaven Twisted Thistle IPA.', ''), ";
		$updateSQL .= "(137, 'A', 'Dark Mild', 'Brown British Beer', '1.03', '1.038', '1.008', '1.013', '3', '3.8', '10', '25', '12', '25', 'Ale', 'A dark, low-gravity, malt-focused English session ale readily suited to drinking in quantity. Refreshing, yet flavorful, with a wide range of dark malt or dark sugar expression. ', 'http://bjcp.org/stylecenter.php', '13', 'Y', 'bcoe', 'BJCP2015', 0, 0, 0, 0, 'session-strength, dark-color, top-fermented, british-isles, traditional-style, brown-ale-family, malty', 'Moorhouse&rsquo;s Black Cat, Cain&rsquo;s Dark Mild, Theakston Traditional Mild, Highgate Mild, Brain&rsquo;s Dark, Banks&rsquo;s Dark Mild.', ''), ";
		$updateSQL .= "(138, 'B', 'English Brown Ale', 'Brown British Beer', '1.04', '1.052', '1.008', '1.013', '4.2', '5.4', '20', '30', '12', '22', 'Ale', 'A malty, brown caramel-centric English ale without the roasted flavors of a Porter.', 'http://bjcp.org/stylecenter.php', '13', 'Y', 'bcoe', 'BJCP2015', 0, 0, 0, 0, 'standard-strength, amber-color, top-fermented, british-isles, traditional-style, brown-ale-family, malty', 'Black Sheep Riggwelter Yorkshire Ale, Wychwood Hobgoblin, Maxim Double Maxim, Newcastle Brown Ale, Samuel Smith&rsquo;s Nut Brown Ale.', ''), ";
		$updateSQL .= "(139, 'C', 'English Porter', 'Brown British Beer', '1.04', '1.052', '1.008', '1.014', '4', '5.4', '18', '35', '20', '30', 'Ale', 'A moderate-strength brown beer with a restrained roasty character and bitterness. May have a range of roasted flavors, generally without burnt qualities, and often has a chocolate-caramel-malty profile. .', 'http://bjcp.org/stylecenter.php', '13', 'Y', 'bcoe', 'BJCP2015', 0, 0, 0, 0, 'standard-strength, dark-color, top-fermented, british-isles, traditional-style, porter-family, malty, roasty', 'Fuller&rsquo;s London Porter, Samuel Smith Taddy Porter, Burton Bridge Burton Porter, RCH Old Slug Porter, Nethergate Old Growler Porter.', ''),";
		$updateSQL .= "(140, 'A', 'Scottish Light', 'Scottish Ale', '1.03', '1.035', '1.01', '1.013', '2.5', '3.2', '10', '20', '13', '22', 'Ale', 'A malt-focused, generally caramelly beer with perhaps a few esters and occasionally a butterscotch aftertaste. Hops only to balance and support the malt. The malt character can range from dry and grainy to rich, toasty, and caramelly, but is never roasty and especially never has a peat smoke character. ', 'http://bjcp.org/stylecenter.php', '14', 'Y', 'bcoe', 'BJCP2015', 0, 0, 0, 0, 'session-strength, amber-color, top-fermented, british-isles, traditional-style, amber-ale-family, malty', 'McEwan&rsquo;s 60.', ''); ";
		mysqli_select_db($connection,$database);
		mysqli_real_escape_string($connection,$updateSQL);
		$result = mysqli_query($connection,$updateSQL) or die (mysqli_error($connection));

		$updateSQL = "INSERT INTO ".$prefix."styles (id, brewStyleNum, brewStyle, brewStyleCategory, brewStyleOG, brewStyleOGMax, brewStyleFG, brewStyleFGMax, brewStyleABV, brewStyleABVMax, brewStyleIBU, brewStyleIBUMax, brewStyleSRM, brewStyleSRMMax, brewStyleType, brewStyleInfo, brewStyleLink, brewStyleGroup, brewStyleActive, brewStyleOwn, brewStyleVersion, brewStyleReqSpec, brewStyleStrength, brewStyleCarb, brewStyleSweet, brewStyleTags, brewStyleComEx, brewStyleEntry) VALUES ";
		$updateSQL .= "(141, 'B', 'Scottish Heavy', 'Scottish Ale', '1.035', '1.04', '1.01', '1.015', '3.2', '3.9', '10', '20', '13', '22', 'Ale', 'A malt-focused, generally caramelly beer with perhaps a few esters and occasionally a butterscotch aftertaste. Hops only to balance and support the malt. The malt character can range from dry and grainy to rich, toasty, and caramelly, but is never roasty and especially never has a peat smoke character. ', 'http://bjcp.org/stylecenter.php', '14', 'Y', 'bcoe', 'BJCP2015', 0, 0, 0, 0, 'session-strength, amber-color, top-fermented, british-isles, traditional-style, amber-ale-family, malty', 'Caledonia Smooth, Orkney Raven ale, Broughton Greenmantle Ale, McEwan&rsquo;s 70, Tennent&rsquo;s Special Ale.', ''), ";
		$updateSQL .= "(142, 'C', 'Scottish Export', 'Scottish Ale', '1.04', '1.06', '1.01', '1.016', '3.9', '6', '15', '30', '13', '22', 'Ale', 'A malt-focused, generally caramelly beer with perhaps a few esters and occasionally a butterscotch aftertaste. Hops only to balance and support the malt. The malt character can range from dry and grainy to rich, toasty, and caramelly, but is never roasty and especially never has a peat smoke character. ', 'http://bjcp.org/stylecenter.php', '14', 'Y', 'bcoe', 'BJCP2015', 0, 0, 0, 0, 'standard-strength, amber-color, top-fermented, british-isles, traditional-style, amber-ale-family, malty', 'Orkney Dark Island, Belhaven Scottish ale, Broughton Exciseman&rsquo;s ale, Weasel Boy Plaid Ferret Scottish ale.', ''), ";
		$updateSQL .= "(143, 'A', 'Irish Red Ale', 'Irish Beer', '1.036', '1.046', '1.01', '1.014', '3.8', '5', '18', '28', '9', '14', 'Ale', 'An easy-drinking pint, often with subtle flavors. Slightly malty in the balance sometimes with an initial soft toffee/caramel sweetness, a slightly grainy-biscuity palate, and a touch of roasted dryness in the finish. Some versions can emphasize the caramel and sweetness more, while others will favor the grainy palate and roasted dryness. ', 'http://bjcp.org/stylecenter.php', '15', 'Y', 'bcoe', 'BJCP2015', 0, 0, 0, 0, 'standard-strength, amber-color, top-fermented, british-isles, traditional-style, amber-ale-family, balanced', 'O&rsquo;Hara&rsquo;s Irish Red Ale, Franciscan Well Rebel Red, Smithwick&rsquo;s Irish Ale, Kilkenny Irish Beer, Caffrey&rsquo;s Irish Ale, Wexford Irish Cream Ale.', ''), ";
		$updateSQL .= "(144, 'B', 'Irish Stout', 'Irish Beer', '1.036', '1.044', '1.007', '1.011', '4', '4.5', '25', '45', '25', '40', 'Ale', 'A black beer with a pronounced roasted flavor, often similar to coffee. The balance can range from fairly even to quite bitter, with the more balanced versions having a little malty sweetness and the bitter versions being quite dry. Draught versions typically are creamy from a nitro pour, but bottled versions will not have this dispense-derived character. The roasted flavor can be dry and coffee-like to somewhat chocolaty.', 'http://bjcp.org/stylecenter.php', '15', 'Y', 'bcoe', 'BJCP2015', 0, 0, 0, 0, 'standard-strength, dark-color, top-fermented, british-isles, traditional-style, stout-family, bitter, roasty', 'Guinness Draught, O&rsquo;Hara&rsquo;s Irish Stout, Beamish Irish Stout, Murphy&rsquo;s Irish Stout, Harpoon Boston Irish Stout.', ''), ";
		$updateSQL .= "(145, 'C', 'Irish Extra Stout', 'Irish Beer', '1.052', '1.062', '1.01', '1.014', '5.5', '6.5', '35', '50', '25', '40', 'Ale', 'A fuller-bodied black beer with a pronounced roasted flavor, often similar to coffee and dark chocolate with some malty complexity. The balance can range from moderately bittersweet to bitter, with the more balanced versions having up to moderate malty richness and the bitter versions being quite dry.', 'http://bjcp.org/stylecenter.php', '15', 'Y', 'bcoe', 'BJCP2015', 0, 0, 0, 0, 'high-strength, dark-color, top-fermented, british-isles, traditional-style, stout-family, bitter, roasty', 'Guinness Extra Stout, O&rsquo;Hara&rsquo;s Leann Follain.', ''), ";
		$updateSQL .= "(146, 'A', 'Sweet Stout', 'Dark British Beer', '1.044', '1.06', '1.012', '1.024', '4', '6', '20', '40', '30', '40', 'Ale', 'A very dark, sweet, full-bodied, slightly roasty ale that can suggest coffee-and-cream, or sweetened espresso. ', 'http://bjcp.org/stylecenter.php', '16', 'Y', 'bcoe', 'BJCP2015', 0, 0, 0, 0, 'standard-strength, dark-color, top-fermented, british-isles, traditional-style, stout-family, malty, roasty, sweet', 'Mackeson&rsquo;s XXX Stout, Watney&rsquo;s Cream Stout, St. Peter&rsquo;s Cream Stout, Marston&rsquo;s Oyster Stout, Samuel Adams Cream Stout, Left Hand Milk Stout, Lancaster Milk Stout.', ''), ";
		$updateSQL .= "(147, 'B', 'Oatmeal Stout', 'Dark British Beer', '1.045', '1.065', '1.01', '1.018', '4.2', '5.9', '25', '40', '22', '40', 'Ale', 'A very dark, full-bodied, roasty, malty ale with a complementary oatmeal flavor. The sweetness, balance, and oatmeal impression can vary considerably.', 'http://bjcp.org/stylecenter.php', '16', 'Y', 'bcoe', 'BJCP2015', 0, 0, 0, 0, 'standard-strength, dark-color, top-fermented, british-isles, traditional-style, stout-family, balanced, roasty', 'Samuel Smith Oatmeal Stout, Young&rsquo;s Oatmeal Stout, McAuslan Oatmeal Stout, Maclay&rsquo;s Oat Malt Stout, Broughton Kinmount Willie Oatmeal Stout, Anderson Valley Barney Flats Oatmeal Stout, Troegs Oatmeal Stout, New Holland The Poet, Goose Island Oatmeal Stout, Wolaver&rsquo;s Oatmeal Stout.', ''), ";
		$updateSQL .= "(148, 'C', 'Tropical Stout', 'Dark British Beer', '1.056', '1.075', '1.01', '1.018', '5.5', '8', '30', '50', '30', '40', 'Ale', 'A very dark, sweet, fruity, moderately strong ale with smooth roasty flavors without a burnt harshness. ', 'http://bjcp.org/stylecenter.php', '16', 'Y', 'bcoe', 'BJCP2015', 0, 0, 0, 0, 'high-strength, dark-color, top-fermented, british-isles, traditional-style, stout-family, malty, roasty, sweet', 'Lion Stout (Sri Lanka), Dragon Stout (Jamaica), ABC Stout (Singapore), Royal Extra &quot;The Lion Stout&quot; (Trinidad), Jamaica Stout (Jamaica).', ''), ";
		$updateSQL .= "(149, 'D', 'Foreign Extra Stout', 'Dark British Beer', '1.056', '1.075', '1.01', '1.018', '6.5', '8', '50', '70', '30', '40', 'Ale', 'A very dark, moderately strong, fairly dry, stout with prominent roast flavors.', 'http://bjcp.org/stylecenter.php', '16', 'Y', 'bcoe', 'BJCP2015', 0, 0, 0, 0, 'high-strength, dark-color, top-fermented, british-isles, traditional-style, stout-family, balanced, roasty', 'Guinness Foreign Extra Stout, Ridgeway Foreign Export Stout, Coopers Best Extra Stout, Elysian Dragonstooth Stout.', ''), ";
		$updateSQL .= "(150, 'A', 'British Strong Ale', 'Strong British Ale', '1.055', '1.08', '1.015', '1.022', '5.5', '8', '30', '60', '8', '22', 'Ale', 'An ale of respectable alcoholic strength, traditionally bottled-conditioned and cellared. Can have a wide range of interpretations, but most will have varying degrees of malty richness, late hops and bitterness, fruity esters, and alcohol warmth. Judges should allow for a significant range in character, as long as the beer is within the alcohol strength range and has an interesting &quot;English&quot; character, it likely fits the style. The malt and adjunct flavors and intensity can vary widely, but any combination should result in an agreeable palate experience.', 'http://bjcp.org/stylecenter.php', '17', 'Y', 'bcoe', 'BJCP2015', 0, 0, 0, 0, 'high-strength, amber-color, top-fermented, british-isles, traditional-style, strong-ale-family, malty', 'Fuller&rsquo;s 1845, Young&rsquo;s Special London Ale, Harvey&rsquo;s Elizabethan Ale, J.W. Lees Manchester Star, Sarah Hughes Dark Ruby Mild, Samuel Smith&rsquo;s Winter Welcome, Fuller&rsquo;s ESB, Adnams Broadside, Young&rsquo;s Winter Warmer.', ''); ";
		mysqli_select_db($connection,$database);
		mysqli_real_escape_string($connection,$updateSQL);
		$result = mysqli_query($connection,$updateSQL) or die (mysqli_error($connection));

		$updateSQL = "INSERT INTO ".$prefix."styles (id, brewStyleNum, brewStyle, brewStyleCategory, brewStyleOG, brewStyleOGMax, brewStyleFG, brewStyleFGMax, brewStyleABV, brewStyleABVMax, brewStyleIBU, brewStyleIBUMax, brewStyleSRM, brewStyleSRMMax, brewStyleType, brewStyleInfo, brewStyleLink, brewStyleGroup, brewStyleActive, brewStyleOwn, brewStyleVersion, brewStyleReqSpec, brewStyleStrength, brewStyleCarb, brewStyleSweet, brewStyleTags, brewStyleComEx, brewStyleEntry) VALUES ";
		$updateSQL .= "(151, 'B', 'Old Ale', 'Strong British Ale', '1.055', '1.088', '1.015', '1.022', '5.5', '9', '30', '60', '10', '22', 'Ale', 'An ale of moderate to fairly significant alcoholic strength, bigger than standard beers, though usually not as strong or rich as barleywine. Often tilted towards a maltier balance. &quot;It should be a warming beer of the type that is best drunk in half pints by a warm fire on a cold winter&rsquo;s night&quot; - Michael Jackson. ', 'http://bjcp.org/stylecenter.php', '17', 'Y', 'bcoe', 'BJCP2015', 0, 0, 0, 0, 'high-strength, amber-color, top-fermented, british-isles, traditional-style, strong-ale-family, malty, aged', 'Gale&rsquo;s Prize Old Ale, Burton Bridge Olde Expensive, Marston Owd Roger, Greene King Strong Suffolk Ale, Theakston Old Peculier.', ''), ";
		$updateSQL .= "(152, 'C', 'Wee Heavy', 'Strong British Ale', '1.07', '1.013', '1.018', '1.04', '6.5', '10', '17', '35', '14', '25', 'Ale', 'Rich, malty, dextrinous, and usually caramel-sweet, these beers can give an impression that is suggestive of a dessert. Complex secondary malt and alcohol flavors prevent a one-dimensional quality. Strength and maltiness can vary, but should not be cloying or syrupy. ', 'http://bjcp.org/stylecenter.php', '17', 'Y', 'bcoe', 'BJCP2015', 0, 0, 0, 0, 'high-strength, amber-color, top-fermented, british-isles, traditional-style, strong-ale-family, malty', 'Traquair House Ale, Belhaven Wee Heavy, McEwan&rsquo;s Scotch Ale, MacAndrew&rsquo;s Scotch Ale, Orkney Skull Splitter, Inveralmond Black Friar, Broughton Old Jock, Gordon Highland Scotch Ale, AleSmith Wee Heavy.', ''), ";
		$updateSQL .= "(153, 'D', 'English Barleywine', 'Strong British Ale', '1.08', '1.12', '1.018', '1.03', '8', '12', '35', '70', '8', '22', 'Ale', 'A showcase of malty richness and complex, intense flavors. Chewy and rich in body, with warming alcohol and a pleasant fruity or hoppy interest. When aged, it can take on port-like flavors. A wintertime sipper. ', 'http://bjcp.org/stylecenter.php', '17', 'Y', 'bcoe', 'BJCP2015', 0, 0, 0, 0, 'very-high-strength, amber-color, top-fermented, british-isles, traditional-style, strong-ale-family, malty', 'Adnam&rsquo;s Tally Ho, Burton Bridge Thomas Sykes Old Ale, J.W. Lee&rsquo;s Vintage Harvest Ale, Fuller&rsquo;s Vintage Ale, Robinson&rsquo;s Old Tom, Fuller&rsquo;s Golden Pride, Whitbread Gold Label.', ''), ";
		$updateSQL .= "(154, 'A', 'Blonde Ale', 'Pale American Ale', '1.038', '1.054', '1.008', '1.013', '3.8', '5.5', '15', '28', '3', '6', 'Ale', 'Easy-drinking, approachable, malt-oriented American craft beer, often with interesting fruit, hop, or character malt notes. Well-balanced and clean, is a refreshing pint without aggressive flavors.', 'http://bjcp.org/stylecenter.php', '18', 'Y', 'bcoe', 'BJCP2015', 0, 0, 0, 0, 'standard-strength, pale-color, any-fermentation, north-america, craft-style, pale-ale-family, balanced', 'Kona Big Wave, Pelican Kiwanda Cream Ale, Victory Summer Love, Russian River Aud Blonde, Widmer Blonde Ale.', ''), ";
		$updateSQL .= "(155, 'B', 'American Pale Ale', 'Pale American Ale', '1.045', '1.06', '1.01', '1.015', '4.5', '6.2', '30', '50', '5', '10', 'Ale', 'A pale, refreshing and hoppy ale, yet with sufficient supporting malt to make the beer balanced and drinkable. The clean hop presence can reflect classic or modern American or New World hop varieties with a wide range of characteristics. An average-strength hop-forward pale American craft beer, generally balanced to be more accessible than modern American IPAs. ', 'http://bjcp.org/stylecenter.php', '18', 'Y', 'bcoe', 'BJCP2015', 0, 0, 0, 0, 'standard-strength, pale-color, top-fermented, north-america, craft-style, pale-ale-family, bitter, hoppy', 'Sierra Nevada Pale Ale, Firestone Walker Pale 31, Deschutes Mirror Pond, Great Lakes Burning River, Flying Dog Doggie Style, Troegs Pale Ale, Big Sky Scape Goat.', ''), ";
		$updateSQL .= "(156, 'A', 'American Amber Ale', 'Amber and Brown American Beer', '1.045', '1.06', '1.01', '1.015', '4.5', '6.2', '25', '40', '10', '17', 'Ale', 'An amber, hoppy, moderate-strength American craft beer with a caramel malty flavor. The balance can vary quite a bit, with some versions being fairly malty and others being aggressively hoppy. Hoppy and bitter versions should not have clashing flavors with the caramel malt profile. ', 'http://bjcp.org/stylecenter.php', '19', 'Y', 'bcoe', 'BJCP2015', 0, 0, 0, 0, 'standard-strength, amber-color, top-fermented, north-america, craft-style, amber-ale-family, balanced, hoppy', 'Troegs HopBack Amber Ale, Kona Lavaman Red Ale, Full Sail Amber, Deschutes Cinder Cone Red, Rogue American Amber Ale, Anderson Valley Boont Amber Ale, McNeill&rsquo;s Firehouse Amber Ale, Mendocino Red Tail Ale.', ''), ";
		$updateSQL .= "(157, 'B', 'California Common', 'Amber and Brown American Beer', '1.048', '1.054', '1.011', '1.014', '4.5', '5.5', '30', '45', '10', '14', 'Ale', 'A lightly fruity beer with firm, grainy maltiness, interesting toasty and caramel flavors, and showcasing the signature Northern Brewer varietal hop character.', 'http://bjcp.org/stylecenter.php', '19', 'Y', 'bcoe', 'BJCP2015', 0, 0, 0, 0, 'standard-strength, amber-color, bottom-fermented, north-america, traditional-style, amber-lager-family, bitter, hoppy', 'Anchor Steam, Steamworks Steam Engine Lager, Flying Dog Old Scratch Amber Lager, Schlafly Pi Common.', ''), ";
		$updateSQL .= "(158, 'C', 'American Brown Ale', 'Amber and Brown American Beer', '1.045', '1.06', '1.01', '1.016', '4.3', '6.2', '20', '30', '18', '35', 'Ale', 'A malty but hoppy beer frequently with chocolate and caramel flavors. The hop flavor and aroma complements and enhances the malt rather than clashing with it. ', 'http://bjcp.org/stylecenter.php', '19', 'Y', 'bcoe', 'BJCP2015', 0, 0, 0, 0, 'standard-strength, dark-color, top-fermented, north-america, craft-style, brown-ale-family, balanced, hoppy', 'Big Sky Moose Drool Brown Ale, Cigar City Maduro Brown Ale, Bell&rsquo;s Best Brown, Smuttynose Old Brown Dog Ale, Brooklyn Brown Ale, Lost Coast Downtown Brown, Avery Ellie&rsquo;s Brown Ale.', ''), ";
		$updateSQL .= "(159, 'A', 'American Porter', 'American Porter and Stout', '1.05', '1.07', '1.012', '1.018', '4.8', '6.5', '25', '50', '22', '40', 'Ale', 'A substantial, malty dark beer with a complex and flavorful dark malt character.', 'http://bjcp.org/stylecenter.php', '20', 'Y', 'bcoe', 'BJCP2015', 0, 0, 0, 0, 'standard-strength, dark-color, top-fermented, north-america, craft-style, porter-family, bitter, roasty, hoppy', 'Great Lakes Edmund Fitzgerald Porter, Anchor Porter, Smuttynose Robust Porter, Sierra Nevada Porter, Deschutes Black Butte Porter, Boulevard Bully! Porter.', ''), ";
		$updateSQL .= "(160, 'B', 'American Stout', 'American Porter and Stout', '1.05', '1.075', '1.01', '1.022', '5', '7', '35', '75', '30', '40', 'Ale', 'A fairly strong, highly roasted, bitter, hoppy dark stout. Has the body and dark flavors typical of stouts with a more aggressive American hop character and bitterness.', 'http://bjcp.org/stylecenter.php', '20', 'Y', 'bcoe', 'BJCP2015', 0, 0, 0, 0, 'high-strength, dark-color, top-fermented, north-america, craft-style, stout-family, bitter, roasty, hoppy', 'Rogue Shakespeare Stout, Deschutes Obsidian Stout, Sierra Nevada Stout, North Coast Old No. 38, Avery Out of Bounds Stout.', ''); ";
		mysqli_select_db($connection,$database);
		mysqli_real_escape_string($connection,$updateSQL);
		$result = mysqli_query($connection,$updateSQL) or die (mysqli_error($connection));

		$updateSQL = "INSERT INTO ".$prefix."styles (id, brewStyleNum, brewStyle, brewStyleCategory, brewStyleOG, brewStyleOGMax, brewStyleFG, brewStyleFGMax, brewStyleABV, brewStyleABVMax, brewStyleIBU, brewStyleIBUMax, brewStyleSRM, brewStyleSRMMax, brewStyleType, brewStyleInfo, brewStyleLink, brewStyleGroup, brewStyleActive, brewStyleOwn, brewStyleVersion, brewStyleReqSpec, brewStyleStrength, brewStyleCarb, brewStyleSweet, brewStyleTags, brewStyleComEx, brewStyleEntry) VALUES ";
		$updateSQL .= "(161, 'C', 'Imperial Stout', 'American Porter and Stout', '1.075', '1.115', '1.018', '1.03', '8', '12', '50', '90', '30', '40', 'Ale', 'An intensely-flavored, big, dark ale with a wide range of flavor balances and regional interpretations. Roasty-burnt malt with deep dark or dried fruit flavors, and a warming, bittersweet finish. Despite the intense flavors, the components need to meld together to create a complex, harmonious beer, not a hot mess.', 'http://bjcp.org/stylecenter.php', '20', 'Y', 'bcoe', 'BJCP2015', 0, 0, 0, 0, 'very-high-strength, dark-color, top-fermented, british-isles, north-america, traditional-style, craft-style, stout-family, malty, bitter, roasty', 'American - North Coast Old Rasputin Imperial Stout, Cigar City Marshal Zhukov&rsquo;s Imperial Stout; English - Courage Imperial Russian Stout, Le Coq Imperial Extra Double Stout, Samuel Smith Imperial Stout.', ''), ";
		$updateSQL .= "(162, 'A', 'American IPA', 'IPA', '1.056', '1.07', '1.008', '1.014', '5.5', '7.5', '40', '70', '6', '14', 'Ale', 'A decidedly hoppy and bitter, moderately strong American pale ale, showcasing modern American and New World hop varieties. The balance is hop-forward, with a clean fermentation profile, dryish finish, and clean, supporting malt allowing a creative range of hop character to shine through.', 'http://bjcp.org/stylecenter.php', '21', 'Y', 'bcoe', 'BJCP2015', 0, 0, 0, 0, 'high-strength, pale-color, top-fermented, north-america, craft-style, ipa-family, bitter, hoppy', 'Russian River Blind Pig IPA, Bell&rsquo;s Two-Hearted Ale, Firestone Walker Union Jack, Alpine Duet, New Belgium Ranger IPA, Fat Heads Head Hunter, Stone IPA, Lagunitas IPA.', ''), ";
		$updateSQL .= "(163, 'B', 'Specialty IPA', 'IPA', '', '', '', '', '', '', '', '', '', '', 'Ale', 'Recognizable as an IPA by balance - a hop-forward, bitter, dryish beer - with something else present to distinguish it from the standard categories. Should have good drinkability, regardless of the form. Excessive harshness and heaviness are typically faults, as are strong flavor clashes between the hops and the other specialty ingredients.', 'http://bjcp.org/stylecenter.php', '21', 'Y', 'bcoe', 'BJCP2015', 1, 0, 0, 0, 'high-strength, pale-color, top-fermented, north-america, craft-style, ipa-family, specialty-family, bitter, hoppy', '', 'Entrant must specify a strength (session: 3.0-5.0%, standard: 5.0-7.5%, double: 7.5-9.5%); if no strength is specified, standard will be assumed. Entrant must specify specific type of Specialty IPA from the library of known types listed in the Style Guidelines, or as amended by the BJCP web site; or the entrant must describe the type of Specialty IPA and its key characteristics in comment form so judges will know what to expect. Entrants may specify specific hop varieties used, if entrants feel that judges may not recognize the varietal characteristics of newer hops. Entrants may specify a combination of defined IPA types (e.g., Black Rye IPA) without providing additional descriptions. Entrants may use this category for a different strength version of an IPA defined by its own BJCP subcategory (e.g., session-strength American or English IPA) - except where an existing BJCP subcategory already exists for that style (e.g., double [American] IPA). Currently Defined Types: Black IPA, Brown IPA, White IPA, Rye IPA, Belgian IPA, Red IPA.'), ";
		$updateSQL .= "(164, 'A', 'Double IPA', 'Strong American Ale', '1.065', '1.085', '1.008', '1.018', '7.5', '10', '60', '120', '6', '14', 'Ale', 'An intensely hoppy, fairly strong pale ale without the big, rich, complex maltiness and residual sweetness and body of an American barleywine. Strongly hopped, but clean, dry, and lacking harshness. Drinkability is an important characteristic; this should not be a heavy, sipping beer.', 'http://bjcp.org/stylecenter.php', '22', 'Y', 'bcoe', 'BJCP2015', 0, 0, 0, 0, 'very-high-strength, pale-color, top-fermented, north-america, craft-style, ipa-family, bitter, hoppy', 'Russian River Pliny the Elder, Port Brewing Hop 15, Three Floyds Dreadnaught, Avery Majaraja, Firestone Walker Double Jack, Alchemist Heady Topper, Bell&rsquo;s Hopslam, Stone Ruination IPA, Great Divide Hercules Double IPA, Rogue XS Imperial India Pale Ale, Fat Heads Hop Juju, Alesmith Yulesmith Summer, Sierra Nevada Hoptimum.', ''), ";
		$updateSQL .= "(165, 'B', 'American Strong Ale', 'Strong American Ale', '1.062', '1.09', '1.014', '1.024', '6.3', '10', '50', '100', '7', '19', 'Ale', 'A strong, full-flavored American ale that challenges and rewards the palate with full malty and hoppy flavors and substantial bitterness. The flavors are bold but complementary, and are stronger and richer than average-strength pale and amber American ales.', 'http://bjcp.org/stylecenter.php', '22', 'Y', 'bcoe', 'BJCP2015', 0, 0, 0, 0, 'high-strength, amber-color, top-fermented, north-america, craft-style, strong-ale-family, bitter, hoppy', 'Stone Arrogant Bastard, Great Lakes Nosferatu, Bear Republic Red Rocket Ale, Terrapin Big Hoppy Monster, Lagunitas Censored, Port Brewing Shark Attack Double Red.', ''), ";
		$updateSQL .= "(166, 'C', 'American Barleywine', 'Strong American Ale', '1.08', '1.12', '1.016', '1.03', '8', '12', '50', '100', '10', '19', 'Ale', 'A well-hopped American interpretation of the richest and strongest of the English ales. The hop character should be evident throughout, but does not have to be unbalanced. The alcohol strength and hop bitterness often combine to leave a very long finish.', 'http://bjcp.org/stylecenter.php', '22', 'Y', 'bcoe', 'BJCP2015', 0, 0, 0, 0, 'very-high-strength, amber-color, top-fermented, north-america, craft-style, strong-ale-family, bitter, hoppy', 'Sierra Nevada Bigfoot, Great Divide Old Ruffian, Victory Old Horizontal, Rogue Old Crustacean, Avery Hog Heaven Barleywine, Bell&rsquo;s Third Coast Old Ale, Anchor Old Foghorn, Three Floyds Behemoth, Stone Old Guardian, Bridgeport Old Knucklehead, Hair of the Dog Doggie Claws, Lagunitas Olde GnarleyWine, Smuttynose Barleywine, Flying Dog Horn Dog.', ''), ";
		$updateSQL .= "(167, 'D', 'Wheatwine', 'Strong American Ale', '1.08', '1.12', '1.016', '1.03', '8', '12', '30', '60', '8', '15', 'Ale', 'A richly textured, high alcohol sipping beer with a significant grainy, bready flavor and sleek body. The emphasis is first on the bready, wheaty flavors with interesting complexity from malt, hops, fruity yeast character and alcohol complexity.', 'http://bjcp.org/stylecenter.php', '22', 'Y', 'bcoe', 'BJCP2015', 0, 0, 0, 0, 'very-high-strength, amber-color, top-fermented, north-america, craft-style, strong-ale-family, wheat-beer-family, balanced, hoppy', 'Rubicon Brewing Company Winter Wheat Wine, Two Brothers Bare Trees Weiss Wine, Smuttynose Wheat Wine, Boulevard Brewing Company Harvest Dance, Portsmouth Wheat Wine.', ''), ";
		$updateSQL .= "(168, 'A', 'Berliner Weisse', 'European Sour Ale', '1.028', '1.032', '1.003', '1.006', '2.8', '3.8', '3', '8', '2', '3', 'Ale', 'A very pale, refreshing, low-alcohol German wheat beer with a clean lactic sourness and a very high carbonation level. A light bread dough malt flavor supports the sourness, which shouldn&rsquo;t seem artificial or funky.', 'http://bjcp.org/stylecenter.php', '23', 'Y', 'bcoe', 'BJCP2015', 0, 0, 0, 0, 'session-beer, pale-color, top-fermented, central-europe, traditional-style, wheat-beer-family, sour', 'Schultheiss Berliner Weisse, Berliner Kindl Weisse, Nodding Head Berliner Weisse, Bahnhof Berliner Style Weisse, New Glarus Berliner Weiss.', ''), ";
		$updateSQL .= "(169, 'B', 'Flanders Red Ale', 'European Sour Ale', '1.048', '1.057', '1.002', '1.012', '4.6', '6.5', '10', '25', '10', '16', 'Ale', 'A complex, sour, fruity, red wine-like Belgian-style ale with interesting supportive malt flavors and a melange of fruit complexity. The dry finish and tannin completes the mental image of a fine red wine.', 'http://bjcp.org/stylecenter.php', '23', 'Y', 'bcoe', 'BJCP2015', 0, 0, 0, 0, 'standard-strength, amber-color, top-fermenting, western-europe, traditional-style, sour-ale-family, balanced, sour, wood', 'Rodenbach Grand Cru, Rodenbach Klassiek, Bellegems Bruin, Duchesse de Bourgogne, Petrus Oud Bruin, Southampton Flanders Red Ale.', ''), ";
		$updateSQL .= "(170, 'C', 'Oud Bruin', 'European Sour Ale', '1.04', '1.074', '1.008', '1.012', '4', '8', '20', '25', '15', '22', 'Ale', 'A malty, fruity, aged, somewhat sour Belgian-style brown ale.', 'http://bjcp.org/stylecenter.php', '23', 'Y', 'bcoe', 'BJCP2015', 0, 0, 0, 0, 'standard-strength, dark-color, top-fermented, western-europe, traditional-style, sour-ale-family, malty, sour', 'Liefman&rsquo;s Goudenband, Liefman&rsquo;s Odnar, Liefman&rsquo;s Oud Bruin, Ichtegem Old Brown, Riva Vondel.', ''); ";
		mysqli_select_db($connection,$database);
		mysqli_real_escape_string($connection,$updateSQL);
		$result = mysqli_query($connection,$updateSQL) or die (mysqli_error($connection));

		$updateSQL = "INSERT INTO ".$prefix."styles (id, brewStyleNum, brewStyle, brewStyleCategory, brewStyleOG, brewStyleOGMax, brewStyleFG, brewStyleFGMax, brewStyleABV, brewStyleABVMax, brewStyleIBU, brewStyleIBUMax, brewStyleSRM, brewStyleSRMMax, brewStyleType, brewStyleInfo, brewStyleLink, brewStyleGroup, brewStyleActive, brewStyleOwn, brewStyleVersion, brewStyleReqSpec, brewStyleStrength, brewStyleCarb, brewStyleSweet, brewStyleTags, brewStyleComEx, brewStyleEntry) VALUES ";
		$updateSQL .= "(171, 'D', 'Lambic', 'European Sour Ale', '1.04', '1.054', '1.001', '1.01', '5', '6.5', '0', '10', '3', '7', 'Ale', 'A fairly sour, often moderately funky wild Belgian wheat beer with sourness taking the place of hop bitterness in the balance. Traditionally spontaneously fermented in the Brussels area and served uncarbonated, the refreshing acidity makes for a very pleasant cafe drink.', 'http://bjcp.org/stylecenter.php', '23', 'Y', 'bcoe', 'BJCP2015', 0, 0, 0, 0, 'standard-strength, pale-color, wild-fermented, western-europe, traditional-style, wheat-beer-family, sour', 'The only bottled version readily available is Cantillon Grand Cru Bruocsella of whatever single batch vintage the brewer deems worthy to bottle. De Cam sometimes bottles their very old (5 years) lambic. In and around Brussels there are specialty cafes that often have draught lambics from traditional brewers or blenders such as Boon, De Cam, Cantillon, Drie Fonteinen, Lindemans, Timmermans and Girardin.', ''), ";
		$updateSQL .= "(172, 'E', 'Gueuze', 'European Sour Ale', '1.04', '1.06', '1', '1.006', '5', '8', '0', '10', '3', '7', 'Ale', 'A complex, pleasantly sour but balanced wild Belgian wheat beer that is highly carbonated and very refreshing. The spontaneous fermentation character can provide a very interesting complexity, with a wide range of wild barnyard, horse blanket, or leather characteristics intermingling with citrusy-fruity flavors and acidity', 'http://bjcp.org/stylecenter.php', '23', 'Y', 'bcoe', 'BJCP2015', 0, 0, 0, 0, 'high-strength, pale-color, wild-fermented, western-europe, traditional-style, wheat-beer-family, aged, sour', 'Boon Oude Gueuze, Boon Oude Gueuze Mariage Parfait, De Cam Gueuze, De Cam/Drei Fonteinen Millennium Gueuze, Drie Fonteinen Oud Gueuze, Cantillon Gueuze, Hanssens Oude Gueuze, Lindemans Gueuze Cuvee Rene, Girardin Gueuze (Black Label), Mort Subite (Unfiltered) Gueuze, Oud Beersel Oude Gueuze.', ''), ";
		$updateSQL .= "(173, 'F', 'Fruit Lambic', 'European Sour Ale', '1.04', '1.06', '1', '1.006', '5', '7', '0', '10', '3', '7', 'Ale', 'A complex, fruity, pleasantly sour, wild wheat ale fermented by a variety of Belgian microbiota, and showcasing the fruit contributions blended with the wild character.', 'http://bjcp.org/stylecenter.php', '23', 'Y', 'bcoe', 'BJCP2015', 1, 0, 0, 0, 'standard-strength, pale-color, wild-fermented, western-europe, traditional-style, wheat-beer-family, sour, fruit', 'Boon Framboise Marriage Parfait, Boon Kriek Mariage Parfait, Boon Oude Kriek, Cantillon Fou&rsquo; Foune, Cantillon Kriek, Cantillon Lou Pepe Kriek, Cantillon Lou Pepe Framboise, Cantillon Rose de Gambrinus, Cantillon St. Lamvinus, Cantillon Vigneronne, De Cam Oude Kriek, Drie Fonteinen Kriek, Girardin Kriek, Hanssens Oude Kriek, Oud Beersel Kriek, Mort Subite Kriek.', 'The type of fruit used must be specified. The brewer must declare a carbonation level (low, medium, high) and a sweetness level (low/none, medium, high).'), ";
		$updateSQL .= "(174, 'A', 'Witbier', 'Belgian Ale', '1.044', '1.052', '1.008', '1.012', '4.5', '5.5', '8', '20', '2', '4', 'Ale', 'A refreshing, elegant, tasty, moderate-strength wheat-based ale.', 'http://bjcp.org/stylecenter.php', '24', 'Y', 'bcoe', 'BJCP2015', 0, 0, 0, 0, 'standard-strength, pale-color, top-fermented, western-europe, traditional-style, wheat-beer-family, spice', 'Hoegaarden Wit, St. Bernardus Blanche, Celis White, Vuuve 5, Brugs Tarwebier (Blanche de Bruges), Wittekerke, Allagash White, Blanche de Bruxelles, Ommegang Witte, Avery White Rascal, Unibroue Blanche de Chambly, Sterkens White Ale, Bell&rsquo;s Winter White Ale, Victory Whirlwind Witbier, Hitachino Nest White Ale.', ''), ";
		$updateSQL .= "(175, 'B', 'Belgian Pale Ale', 'Belgian Ale', '1.048', '1.055', '1.01', '1.014', '4.8', '5.5', '20', '30', '8', '14', 'Ale', 'A moderately malty, somewhat fruity-spicy, easy-drinking, copper-colored Belgian ale that is somewhat less aggressive in flavor profile than many other Belgian beers. The malt character tends to be a bit biscuity with light toasty, honey-like, or caramelly components; the fruit character is noticeable and complementary to the malt. The bitterness level is generally moderate, but may not seem as high due to the flavorful malt profile.', 'http://bjcp.org/stylecenter.php', '24', 'Y', 'bcoe', 'BJCP2015', 0, 0, 0, 0, 'standard-strength, amber-color, top-fermented, western-europe, traditional-style, pale-ale-family, balanced', 'De Koninck, Speciale Palm, Dobble Palm, Russian River Perdition, Ginder Ale, Op-Ale, St. Pieters Zinnebir, Brewer&rsquo;s Art House Pale Ale, Avery Karma, Eisenbahn Pale Ale, Blue Moon Pale Moon.', ''), ";
		$updateSQL .= "(176, 'C', 'Biere de Garde', 'Belgian Ale', '1.06', '1.08', '1.008', '1.016', '6', '8.5', '18', '28', '6', '19', 'Ale', 'A fairly strong, malt-accentuated, lagered artisanal beer with a range of malt flavors appropriate for the color. All are malty yet dry, with clean flavors and a smooth character. Three main variations are included in the style: the brown (brune), the blond (blonde), and the amber (ambree). The darker versions will have more malt character, while the paler versions can have more hops (but still are malt-focused beers). A related style is Biere de Mars, which is brewed in March (Mars) for present use and will not age as well. Attenuation rates are in the 80-85% range. Some fuller-bodied examples exist, but these are somewhat rare. Age and oxidation in imports often increases fruitiness, caramel flavors, and adds corked and musty notes; these are all signs of mishandling, not characteristic elements of the style.', 'http://bjcp.org/stylecenter.php', '24', 'Y', 'bcoe', 'BJCP2015', 1, 0, 0, 0, 'high-strength, pale-color, amber-color, any-fermentation, lagered, western-europe, traditional-style, amber-ale-family, malty', 'Ch''Ti (brown and blond), Jenlain (amber and blond), La Choulette (all 3 versions), St. Amand (brown), Saint Sylvestre 3 Monts (blond), Russian River Perdition.', 'Entrant must specify blond, amber, or brown biere de garde. If no color is specified, the judge should attempt to judge based on initial observation, expecting a malt flavor and balance that matches the color.'), ";
		$updateSQL .= "(177, 'A', 'Belgian Blond Ale', 'Strong Belgian Ale', '1.062', '1.075', '1.008', '1.018', '6', '7.5', '15', '30', '4', '7', 'Ale', 'A moderate-strength golden ale that has a subtle fruity-spicy Belgian yeast complexity, slightly malty-sweet flavor, and dry finish.', 'http://bjcp.org/stylecenter.php', '25', 'Y', 'bcoe', 'BJCP2015', 0, 0, 0, 0, 'high-strength, pale-color, top-fermented, western-europe, traditional-style, balanced', 'Leffe Blond, Affligem Blond, La Trappe (Koningshoeven) Blond, Grimbergen Blond, Val-Dieu Blond.', ''), ";
		$updateSQL .= "(178, 'B', 'Saison', 'Strong Belgian Ale', '1.048', '1.065', '1.002', '1.008', '3.5', '9.5', '20', '35', '5', '22', 'Ale', 'Most commonly, a pale, refreshing, highly-attenuated, moderately-bitter, moderate-strength Belgian ale with a very dry finish. Typically highly carbonated, and using non-barley cereal grains and optional spices for complexity, as complements the expressive yeast character that is fruity, spicy, and not overly phenolic. Less common variations include both lower-alcohol and higher-alcohol products, as well as darker versions with additional malt character.', 'http://bjcp.org/stylecenter.php', '25', 'Y', 'bcoe', 'BJCP2015', 1, 0, 0, 0, 'standard-strength, pale-color, top-fermented, western-europe, traditional-style, bitter', 'Ellezelloise Saison, Fanome Saison, Lefebvre Saison 1900, Saison Dupont Vieille Provision, Saison de Pipaix, Saison Regal, Saison Voisin, Boulevard Tank 7 Farmhouse Ale.', 'The entrant must specify the strength (table, standard, super) and the color (pale, dark). '), ";
		$updateSQL .= "(179, 'C', 'Belgian Golden Strong Ale', 'Strong Belgian Ale', '1.07', '1.095', '1.005', '1.016', '7.5', '10.5', '22', '35', '3', '6', 'Ale', 'A pale, complex, effervescent, strong Belgian-style ale that is highly attenuated and features fruity and hoppy notes in preference to phenolics.', 'http://bjcp.org/stylecenter.php', '25', 'Y', 'bcoe', 'BJCP2015', 0, 0, 0, 0, 'very-high-strength, pale-color, top-fermented, western-europe, traditional-style, bitter', 'Duvel, Russian River Damnation, Hapkin, Lucifer, Brigand, Judas, Delirium Tremens, Dulle Teve, Piraat, Great Divide Hades, Avery Salvation, North Coast Pranqster, Unibroue Eau Benite, AleSmith Horny Devil.', ''), ";
		$updateSQL .= "(180, 'A', 'Trappist Single', 'Trappist Ale', '1.044', '1.054', '1.004', '1.01', '4.8', '6', '25', '45', '3', '5', 'Ale', 'A pale, bitter, highly attenuated and well carbonated Trappist ale, showing a fruity-spicy Trappist yeast character, a spicy-floral hop profile, and a soft, supportive grainy-sweet malt palate.', 'http://bjcp.org/stylecenter.php', '26', 'Y', 'bcoe', 'BJCP2015', 0, 0, 0, 0, 'standard-strength, pale-color, top-fermenting, western-europe, craft-style, bitter, hoppy', 'Westvleteren Blond (green cap), Westmalle Extra, Achel 5 Blond, Chimay Doree, Lost Abbey Devotion.', ''); ";
		mysqli_select_db($connection,$database);
		mysqli_real_escape_string($connection,$updateSQL);
		$result = mysqli_query($connection,$updateSQL) or die (mysqli_error($connection));

		$updateSQL = "INSERT INTO ".$prefix."styles (id, brewStyleNum, brewStyle, brewStyleCategory, brewStyleOG, brewStyleOGMax, brewStyleFG, brewStyleFGMax, brewStyleABV, brewStyleABVMax, brewStyleIBU, brewStyleIBUMax, brewStyleSRM, brewStyleSRMMax, brewStyleType, brewStyleInfo, brewStyleLink, brewStyleGroup, brewStyleActive, brewStyleOwn, brewStyleVersion, brewStyleReqSpec, brewStyleStrength, brewStyleCarb, brewStyleSweet, brewStyleTags, brewStyleComEx, brewStyleEntry) VALUES ";
		$updateSQL .= "(181, 'B', 'Belgian Dubbel', 'Trappist Ale', '1.062', '1.075', '1.008', '1.018', '6', '7.6', '15', '25', '10', '17', 'Ale', 'A deep reddish-copper, moderately strong, malty, complex Trappist ale with rich malty flavors, dark or dried fruit esters, and light alcohol blended together in a malty presentation that still finishes fairly dry. Comments: Most commercial examples are in the 6.5 - 7% ABV range. Traditionally bottle-conditioned (&quot;refermented in the bottle&quot;). ', 'http://bjcp.org/stylecenter.php', '26', 'Y', 'bcoe', 'BJCP2015', 0, 0, 0, 0, 'high-strength, amber-color, top-fermented, western-europe, traditional-style, malty', 'Westmalle Dubbel, St. Bernardus Pater 6, La Trappe Dubbel, Corsendonk Abbey Brown Ale, Grimbergen Double, Affligem Dubbel, Chimay Premiere (Red), Pater Lieven Bruin, Duinen Dubbel, St. Feuillien Brune, New Belgium Abbey Belgian Style Ale, Stoudts Abbey Double Ale, Russian River Benediction, Flying Fish Dubbel, Lost Abbey Lost and Found Abbey Ale, Allagash Double.', ''), ";
		$updateSQL .= "(182, 'C', 'Belgian Tripel', 'Trappist Ale', '1.075', '1.085', '1.008', '1.014', '7.5', '9.5', '20', '40', '4.5', '7', 'Ale', 'A pale, somewhat spicy, dry, strong Trappist ale with a pleasant rounded malt flavor and firm bitterness. Quite aromatic, with spicy, fruity, and light alcohol notes combining with the supportive clean malt character to produce a surprisingly drinkable beverage considering the high alcohol level.', 'http://bjcp.org/stylecenter.php', '26', 'Y', 'bcoe', 'BJCP2015', 0, 0, 0, 0, 'high-strength, pale-color, top-fermented, western-europe, traditional-style, bitter', 'Westmalle Tripel, La Rulles Tripel, St. Bernardus Tripel, Chimay Cinq Cents (White), Watou Tripel, Val-Dieu Triple, Affligem Tripel, Grimbergen Tripel, La Trappe Tripel, Witkap Pater Tripel, Corsendonk Abbey Pale Ale, St. Feuillien Tripel.', ''), ";
		$updateSQL .= "(183, 'D', 'Belgian Dark Strong Ale', 'Trappist Ale', '1.075', '1.11', '1.01', '1.024', '8', '11', '20', '35', '12', '22', 'Ale', 'A dark, complex, very strong Belgian ale with a delicious blend of malt richness, dark fruit flavors, and spicy elements. Complex, rich, smooth and dangerous.', 'http://bjcp.org/stylecenter.php', '26', 'Y', 'bcoe', 'BJCP2015', 0, 0, 0, 0, 'very-high-strength, amber-color, top-fermented, western-europe, traditional-style, malty', 'Westvleteren 12, Rochefort 10, St. Bernardus Abt 12, Gouden Carolus Grand Cru of the Emperor, Achel Extra Brune, Rochefort 8, Southampton Abbot 12, Chimay Grande Reserve, Lost Abbey Judgment Day.', ''), ";
		$updateSQL .= "(184, 'A', 'Historical Beer', 'Historical Beer', '', '', '', '', '', '', '', '', '', '', '', 'The Historical Beer category contains styles that either have all but died out in modern times, or that were much more popular in past times and are known only through recreations. This category can also be used for traditional or indigenous beers of cultural importance within certain countries. Placing a beer in the historical category does not imply that it is not currently being produced, just that it is a very minor style or perhaps is in the process of rediscovery by craft brewers.', 'http://bjcp.org/stylecenter.php', '27', 'Y', 'bcoe', 'BJCP2015', 1, 0, 0, 0, 'standard-strength, pale-color, top-fermented, central-europe, historical-style, wheat-beer-family, sour, spice, amber-color, north-america, historical-style, balanced, smoke, dark-color, british-isles, brown-ale-family, malty, sweet, bottom-fermented, lag', '', 'The entrant must either specify a style with a BJCP-supplied description, or provide a similar description for the judges of a different style. If a beer is entered with just a style name and no description, it is very unlikely that judges will understand how to judge it. Currently defined examples: Gose, Piwo Grodziskie, Lichtenhainer, Roggenbier, Sahti, Kentucky Common, Pre-Prohibition Lager, Pre-Prohibition Porter, London Brown Ale.'), ";
		$updateSQL .= "(185, 'A', 'Brett Beer', 'American Wild Ale', '', '', '', '', '', '', '', '', '', '', '', 'An interesting and refreshing variation on the base style, often drier and fruitier than expected, with at most a light acidity. Funky notes are generally restrained in 100% Brett examples, except in older examples.', 'http://bjcp.org/stylecenter.php', '28', 'Y', 'bcoe', 'BJCP2015', 1, 0, 0, 0, 'wild-fermentation, north-america, craft-style, specialty-beer', '', 'The entrant must specify either a base beer style (classic BJCP style, or a generic style family) or provide a description of the ingredients/specs/desired character. The entrant must specify if a 100% Brett fermentation was conducted. The entrant may specify the strain(s) of Brettanomyces used, along with a brief description of its character.'), ";
		$updateSQL .= "(186, 'B', 'Mixed Fermentation Sour Beer', 'American Wild Ale', '', '', '', '', '', '', '', '', '', '', '', 'A sour and/or funky version of a base style of beer.', 'http://bjcp.org/stylecenter.php', '28', 'Y', 'bcoe', 'BJCP2015', 1, 0, 0, 0, 'wild-fermentation, north-america, craft-style, specialty-beer, sour', 'Bruery Tart of Darkness, Jolly Pumpkin Calabaza Blanca, Cascade Vlad the Imp Aler, Russian River Temptation, Boulevard Love Child, Hill Farmstead Bi', 'The entrant must specify a description of the beer, identifying the yeast/bacteria used and either a base style or the ingredients/specs/target character of the beer.'), ";
		$updateSQL .= "(187, 'C', 'Wild Specialty Beer', 'American Wild Ale', '', '', '', '', '', '', '', '', '', '', '', 'A sour and/or funky version of a fruit, herb, or spice beer, or a wild beer aged in wood. If wood-aged, the wood should not be the primary or dominant character.', 'http://bjcp.org/stylecenter.php', '28', 'Y', 'bcoe', 'BJCP2015', 1, 0, 0, 0, 'wild-fermentation, north-america, craft-style, specialty-beer, sour, fruit', 'Cascade Bourbonic Plague, Jester King Atrial Rubicite, New Glarus Belgian Red, Russian River Supplication, The Lost Abbey Cuvee de Tomme.', 'Entrant must specify the type of fruit, spice, herb, or wood used. Entrant must specify a description of the beer, identifying the yeast/bacteria used and either a base style or the ingredients/specs/target character of the beer. A general description of the special nature of the beer can cover all the required items.'), ";
		$updateSQL .= "(188, 'A', 'Fruit Beer', 'Fruit Beer', '', '', '', '', '', '', '', '', '', '', '', 'A harmonious marriage of fruit and beer, but still recognizable as a beer. The fruit character should be evident but in balance with the beer, not so forward as to suggest an artificial product.', 'http://bjcp.org/stylecenter.php', '29', 'Y', 'bcoe', 'BJCP2015', 1, 0, 0, 0, 'specialty-beer, fruit', 'Bell&rsquo;s Cherry Stout, Dogfish Head Aprihop, Great Divide Wild Raspberry Ale, Ebulum Elderberry Black Ale.', 'The entrant must specify a base style; the declared style does not have to be a Classic Style. The entrant must specify the type of fruit used. Soured fruit beers that aren''t lambics should be entered in the American Wild Ale category.'), ";
		$updateSQL .= "(189, 'B', 'Fruit and Spice Beer', 'Fruit Beer', '', '', '', '', '', '', '', '', '', '', '', 'A harmonious marriage of fruit, spice, and beer, but still recognizable as a beer. The fruit and spice character should each be evident but in balance with the beer, not so forward as to suggest an artificial product.', 'http://bjcp.org/stylecenter.php', '29', 'Y', 'bcoe', 'BJCP2015', 1, 0, 0, 0, 'specialty-beer, fruit, spice', '', 'The entrant must specify a base style; the declared style does not have to be a Classic Style. The entrant must specify the type of fruit and spices, herbs, or vegetables (SHV) used; individual SHV ingredients do not need to be specified if a well-known blend of spices is used (e.g., apple pie spice).'), ";
		$updateSQL .= "(190, 'C', 'Speciality Fruit Beer', 'Fruit Beer', '', '', '', '', '', '', '', '', '', '', '', 'A harmonious marriage of fruit, sugar, and beer, but still recognizable as a beer. The fruit and sugar character should both be evident but in balance with the beer, not so forward as to suggest an artificial product.', 'http://bjcp.org/stylecenter.php', '29', 'Y', 'bcoe', 'BJCP2015', 1, 0, 0, 0, 'specialty-beer, fruit', '', 'The entrant must specify a base style; the declared style does not have to be a Classic Style. The entrant must specify the type of fruit used. The entrant must specify the type of additional fermentable sugar or special process employed.'); ";
		mysqli_select_db($connection,$database);
		mysqli_real_escape_string($connection,$updateSQL);
		$result = mysqli_query($connection,$updateSQL) or die (mysqli_error($connection));

		$updateSQL = "INSERT INTO ".$prefix."styles (id, brewStyleNum, brewStyle, brewStyleCategory, brewStyleOG, brewStyleOGMax, brewStyleFG, brewStyleFGMax, brewStyleABV, brewStyleABVMax, brewStyleIBU, brewStyleIBUMax, brewStyleSRM, brewStyleSRMMax, brewStyleType, brewStyleInfo, brewStyleLink, brewStyleGroup, brewStyleActive, brewStyleOwn, brewStyleVersion, brewStyleReqSpec, brewStyleStrength, brewStyleCarb, brewStyleSweet, brewStyleTags, brewStyleComEx, brewStyleEntry) VALUES ";
		$updateSQL .= "(191, 'A', 'Spice, Herb, or Vegetable Beer', 'Spiced Beer', '', '', '', '', '', '', '', '', '', '', '', 'A harmonious marriage of SHV and beer, but still recognizable as a beer. The SHV character should be evident but in balance with the beer, not so forward as to suggest an artificial product.', 'http://bjcp.org/stylecenter.php', '30', 'Y', 'bcoe', 'BJCP2015', 1, 0, 0, 0, 'specialty-beer, spice', 'Alesmith Speedway Stout, Founders Breakfast Stout, Traquair Jacobite Ale, Rogue Chipotle Ale, Young&rsquo;s Double Chocolate Stout, Bell&rsquo;s Java Stout, Elysian Avatar IPA.', 'The entrant must specify a base style; the declared style does not have to be a Classic Style. The entrant must specify the type of spices, herbs, or vegetables used; individual ingredients do not need to be specified if a well-known blend of spices is used (e.g., apple pie spice).'), ";
		$updateSQL .= "(192, 'B', 'Autumn Seasonal Beer', 'Spiced Beer', '', '', '', '', '', '', '', '', '', '', '', 'An amber to copper, spiced beer that often has a moderately rich body and slightly warming finish suggesting a good accompaniment for the cool fall season, and often evocative of Thanksgiving traditions.', 'http://bjcp.org/stylecenter.php', '30', 'Y', 'bcoe', 'BJCP2015', 1, 0, 0, 0, 'specialty-beer, spice', 'Dogfish Head Punkin Ale, Southampton Pumpkin Ale.', 'The entrant must specify a base style; the declared style does not have to be a Classic Style. The entrant must specify the type of spices, herbs, or vegetables used; individual ingredients do not need to be specified if a well-known blend of spices is used (e.g., pumpkin pie spice). The beer must contain spices, and may contain vegetables and/or sugars.'), ";
		$updateSQL .= "(193, 'C', 'Winter Seasonal Beer', 'Spiced Beer', '', '', '', '', '', '', '', '', '', '', '', 'A stronger, darker, spiced beer that often has a rich body and warming finish suggesting a good accompaniment for the cold winter season.', 'http://bjcp.org/stylecenter.php', '30', 'Y', 'bcoe', 'BJCP2015', 1, 0, 0, 0, 'specialty-beer, spice', 'Anchor Our Special Ale, Harpoon Winter Warmer, Weyerbacher Winter Ale, Goose Island Christmas Ale, Great Lakes Christmas Ale, Lakefront Holiday Spice Lager Beer.', 'The entrant must specify a base style; the declared style does not have to be a Classic Style. The entrant must specify the type of spices, sugars, fruits, or additional fermentables used; individual ingredients do not need to be specified if a well-known blend of spices is used (e.g., mulling spice).'), ";
		$updateSQL .= "(194, 'A', 'Alternative Grain Beer', 'Alternative Fermentables Beer', '', '', '', '', '', '', '', '', '', '', '', 'A base beer enhanced by the flavor of additional grain.', 'http://bjcp.org/stylecenter.php', '31', 'Y', 'bcoe', 'BJCP2015', 1, 0, 0, 0, 'specialty-beer', '', 'The entrant must specify a base style; the declared style does not have to be a Classic Style. The entrant must specify the type of alternative grain used.'), ";
		$updateSQL .= "(195, 'B', 'Alternative Sugar Beer', 'Alternative Fermentables Beer', '', '', '', '', '', '', '', '', '', '', '', 'A harmonious marriage of sugar and beer, but still recognizable as a beer. The sugar character should both be evident but in balance with the beer, not so forward as to suggest an artificial product.', 'http://bjcp.org/stylecenter.php', '31', 'Y', 'bcoe', 'BJCP2015', 1, 0, 0, 0, 'specialty-beer', '', 'The entrant must specify a base style; the declared style does not have to be a Classic Style. The entrant must specify the type of sugar used.'), ";
		$updateSQL .= "(196, 'A', 'Classic Style Smoked Beer', 'Smoked Beer', '', '', '', '', '', '', '', '', '', '', '', 'A smoke-enhanced beer showing good balance between the smoke and beer character, while remaining pleasant to drink. Balance in the use of smoke, hops and malt character is exhibited by the better examples.', 'http://bjcp.org/stylecenter.php', '32', 'Y', 'bcoe', 'BJCP2015', 1, 0, 0, 0, 'specialty-beer, smoke', 'Alaskan Smoked Porter, Spezial Lagerbier, Weissbier and Bockbier, Stone Smoked Porter, Schlenkerla Weizen Rauchbier and Ur-Bock Rauchbier.', 'The entrant must specify a Classic Style base beer. The entrant must specify the type of wood or smoke if a varietal smoke character is noticeable.'), ";
		$updateSQL .= "(197, 'B', 'Specialty Smoked Beer', 'Smoked Beer', '', '', '', '', '', '', '', '', '', '', '', 'A smoke-enhanced beer showing good balance between the smoke, the beer character, and the added ingredients, while remaining pleasant to drink. Balance in the use of smoke, hops and malt character is exhibited by the better examples.', 'http://bjcp.org/stylecenter.php', '32', 'Y', 'bcoe', 'BJCP2015', 1, 0, 0, 0, 'specialty-beer, smoke', '', 'The entrant must specify a base beer style; the base beer does not have to be a Classic Style. The entrant must specify the type of wood or smoke if a varietal smoke character is noticeable. The entrant must specify the additional ingredients or processes that make this a specialty smoked beer.'), ";
		$updateSQL .= "(198, 'A', 'Wood-Aged Beer', 'Wood Beer', '', '', '', '', '', '', '', '', '', '', '', 'A harmonious blend of the base beer style with characteristics from aging in contact with wood. The best examples will be smooth, flavorful, well-balanced and well-aged.', 'http://bjcp.org/stylecenter.php', '33', 'Y', 'bcoe', 'BJCP2015', 1, 0, 0, 0, 'specialty-beer, wood', '', 'The entrant must specify the type of wood used and the char level (if charred). The entrant must specify the base style; the base style can be either a classic BJCP style (i.e., a named subcategory) or may be a generic type of beer (e.g., porter, brown ale). If an unusual wood has been used, the entrant must supply a brief description of the sensory aspects the wood adds to beer.'), ";
		$updateSQL .= "(199, 'B', 'Specialty Wood-Aged Beer', 'Wood Beer', '', '', '', '', '', '', '', '', '', '', '', 'A harmonious blend of the base beer style with characteristics from aging in contact with wood (including alcoholic products previously in contact with the wood). The best examples will be smooth, flavorful, well-balanced and well-aged.', 'http://bjcp.org/stylecenter.php', '33', 'Y', 'bcoe', 'BJCP2015', 1, 0, 0, 0, 'specialty-beer, wood', 'The Lost Abbey Angel''s Share Ale, J.W. Lees Harvest Ale in Port, Sherry, Lagavulin Whisky or Calvados Casks, Founders Kentucky Breakfast Stout, Goose Island Bourbon County Stout, many microbreweries have specialty beers served only on premises often directly from the cask.', 'The entrant must specify the additional alcohol character, with information about the barrel if relevant to the finished flavor profile. The entrant must specify the base style; the base style can be either a classic BJCP style (i.e., a named subcategory) or may be a generic type of beer (e.g., porter, brown ale). If an unusual wood or ingredient has been used, the entrant must supply a brief description of the sensory aspects the ingredients adds to the beer.'), ";
		$updateSQL .= "(200, 'A', 'Clone Beer', 'Specialty Beer', '', '', '', '', '', '', '', '', '', '', '', 'Based on declared clone beer.', 'http://bjcp.org/stylecenter.php', '34', 'Y', 'bcoe', 'BJCP2015', 1, 0, 0, 0, 'specialty-beer', '', 'The entrant must specify the name of the commercial beer being cloned, specifications (vital statistics) for the beer, and either a brief sensory description or a list of ingredients used in making the beer. Without this information, judges who are unfamiliar with the beer will have no basis for comparison.'); ";
		mysqli_select_db($connection,$database);
		mysqli_real_escape_string($connection,$updateSQL);
		$result = mysqli_query($connection,$updateSQL) or die (mysqli_error($connection));

		$updateSQL = "INSERT INTO ".$prefix."styles (id, brewStyleNum, brewStyle, brewStyleCategory, brewStyleOG, brewStyleOGMax, brewStyleFG, brewStyleFGMax, brewStyleABV, brewStyleABVMax, brewStyleIBU, brewStyleIBUMax, brewStyleSRM, brewStyleSRMMax, brewStyleType, brewStyleInfo, brewStyleLink, brewStyleGroup, brewStyleActive, brewStyleOwn, brewStyleVersion, brewStyleReqSpec, brewStyleStrength, brewStyleCarb, brewStyleSweet, brewStyleTags, brewStyleComEx, brewStyleEntry) VALUES ";
		$updateSQL .= "(201, 'B', 'Mixed-Style Beer', 'Specialty Beer', '', '', '', '', '', '', '', '', '', '', '', 'Based on the declared base styles. As with all Specialty-Type Beers, the resulting combination of beer styles needs to be harmonious and balanced, and be pleasant to drink.', 'http://bjcp.org/stylecenter.php', '34', 'Y', 'bcoe', 'BJCP2015', 1, 0, 0, 0, 'specialty-beer', '', 'The entrant must specify the styles being mixed. The entrant may provide an additional description of the sensory profile of the beer or the vital statistics of the resulting beer.'), ";
		$updateSQL .= "(202, 'C', 'Experimental Beer', 'Specialty Beer', '', '', '', '', '', '', '', '', '', '', '', 'This style is the ultimate in creativity, since it cannot represent a well-known commercial beer (otherwise it would be a clone beer) and cannot fit into any other existing Specialty-Type style (including those within this major category).', 'http://bjcp.org/stylecenter.php', '34', 'Y', 'bcoe', 'BJCP2015', 1, 0, 0, 0, 'specialty-beer', '', ' The entrant must specify the special nature of the experimental beer, including the special ingredients or processes that make it not fit elsewhere in the guidelines. The entrant must provide vital statistics for the beer, and either a brief sensory description or a list of ingredients used in making the beer. Without this information, judges will have no basis for comparison.'), ";
		$updateSQL .= "(203, 'A', 'Dry Mead', 'Traditional Mead', '', '', '', '', '', '', 'N/A', 'N/A', 'N/A', 'N/A', 'Mead', 'Similar in balance, body, finish and flavor intensity to a dry white wine, with a pleasant mixture of subtle honey character, soft fruity esters, and clean alcohol. Complexity, harmony, and balance of sensory elements are most desirable, with no inconsistencies in color, aroma, flavor or aftertaste. The proper balance of sweetness, acidity, alcohol, and honey character is the essential final measure of any mead.', 'http://bjcp.org/stylecenter.php', 'M1', 'Y', 'bcoe', 'BJCP2015', 0, 1, 1, 0, '', 'White Winter Dry Mead, Sky River Dry Mead, Intermiel Bouquet Printanier.', 'Entry Instructions: Entrants must specify carbonation level and strength. Sweetness is assumed to be DRY in this category. Entrants may specify honey varieties.'), ";
		$updateSQL .= "(204, 'B', 'Semi-Sweet Mead', 'Traditional Mead', '', '', '', '', '', '', 'N/A', 'N/A', 'N/A', 'N/A', 'Mead', 'Similar in balance, body, finish and flavor intensity to a semisweet (or medium-dry) white wine, with a pleasant mixture of honey character, light sweetness, soft fruity esters, and clean alcohol. Complexity, harmony, and balance of sensory elements are most desirable, with no inconsistencies in color, aroma, flavor or aftertaste. The proper balance of sweetness, acidity, alcohol, and honey character is the essential final measure of any mead.', 'http://bjcp.org/stylecenter.php', 'M1', 'Y', 'bcoe', 'BJCP2015', 0, 1, 1, 0, '', 'Lurgashall English Mead, Redstone Traditional Mountain Honey Wine, Sky River Semi-Sweet Mead, Intermiel Verge d&rsquo;Or and Melilot.', 'Entrants must specify carbonation level and strength. Sweetness is assumed to be SEMI-SWEET in this category. Entrants MAY specify honey varieties.'), ";
		$updateSQL .= "(205, 'C', 'Sweet Mead', 'Traditional Mead', '', '', '', '', '', '', 'N/A', 'N/A', 'N/A', 'N/A', 'Mead', 'Similar in balance, body, finish and flavor intensity to a well-made dessert wine (such as Sauternes), with a pleasant mixture of honey character, residual sweetness, soft fruity esters, and clean alcohol. Complexity, harmony, and balance of sensory elements are most desirable, with no inconsistencies in color, aroma, flavor or aftertaste. The proper balance of sweetness, acidity, alcohol, and honey character is the essential final measure of any mead.', 'http://bjcp.org/stylecenter.php', 'M1', 'Y', 'bcoe', 'BJCP2015', 0, 1, 1, 0, '', 'Moonlight Sensual, Lurgashall Christmas Mead, Chaucer&rsquo;s Mead, Rabbit&rsquo;s Foot Sweet Wildflower Honey Mead, Intermiel Benoite.', 'Entrants MUST specify carbonation level and strength. Sweetness is assumed to be SWEET in this category. Entrants MAY specify honey varieties.'), ";
		$updateSQL .= "(206, 'A', 'Cyser', 'Fruit Mead', '', '', '', '', '', '', 'N/A', 'N/A', 'N/A', 'N/A', 'Mead', 'In well-made examples of the style, the fruit is both distinctive and well-incorporated into the honey-sweet-acid-tannin-alcohol balance of the mead. Some of the best strong examples have the taste and aroma of an aged Calvados (apple brandy from northern France), while subtle, dry versions can taste similar to many fine white wines. There should be an appealing blend of the fruit and honey character but not necessarily an even balance. Generally a good tannin-sweetness balance is desired, though very dry and very sweet examples do exist.', 'http://bjcp.org/stylecenter.php', 'M2', 'Y', 'bcoe', 'BJCP2015', 0, 1, 1, 1, '', 'Moonlight Blossom, White Winter Cyser, Rabbit&rsquo;s Foot Apple Cyser.', 'Entrants must specify carbonation level, strength, and sweetness. Entrants may specify honey varieties. Entrants may specify the varieties of apple used; if specified, a varietal character will be expected. Products with a relatively low proportion of honey are better entered as a Specialty Cider. A spiced cyser should be entered as a Fruit and Spice Mead. A cyser with other fruit should be entered as a Melomel. A cyser with additional ingredients should be entered as an Experimental mead.'), ";
		$updateSQL .= "(207, 'B', 'Pyment', 'Fruit Mead', '', '', '', '', '', '', 'N/A', 'N/A', 'N/A', 'N/A', 'Mead', 'In well-made examples of the style, the grape is both distinctively vinous and well-incorporated into the honey-sweet-acid-tannin-alcohol balance of the mead. White and red versions can be quite different, and the overall impression should be characteristic of the type of grapes used and suggestive of a similar variety wine. There should be an appealing blend of the fruit and honey character but not necessarily an even balance. Generally a good tannin-sweetness balance is desired, though very dry and very sweet examples do exist.', 'http://bjcp.org/stylecenter.php', 'M2', 'Y', 'bcoe', 'BJCP2015', 0, 1, 1, 1, '', 'Celestial Meads Que Syrah, Moonlight Slow Dance, Redstone Pinot Noir and White Pyment Mountain Honey Wines.', 'Entrants must specify carbonation level, strength, and sweetness. Entrants may specify honey varieties. Entrants may specify the varieties of grape used; if specified, a varietal character will be expected. A spiced pyment (hippocras) should be entered as a Fruit and Spice Mead. A pyment made with other fruit should be entered as a Melomel. A pyment with other ingredients should be entered as an Experimental Mead. '), ";
		$updateSQL .= "(208, 'C', 'Berry Mead', 'Fruit Mead', '', '', '', '', '', '', 'N/A', 'N/A', 'N/A', 'N/A', 'Mead', 'In well-made examples of the style, the fruit is both distinctive and well-incorporated into the honey-sweet-acid-tannin-alcohol balance of the mead. Different types of fruit can result in widely different characteristics; allow for a variation in the final product.', 'http://bjcp.org/stylecenter.php', 'M2', 'Y', 'bcoe', 'BJCP2015', 1, 1, 1, 1, '', 'Moonlight Blissful, Wild, Caress, and Mischief, White Winter Blueberry, Raspberry and Strawberry Melomels, Celestial Meads Miel Noir, Redstone Black Raspberry Nectar, Bees Brothers Raspberry Mead, Intermiel Honey Wine and Raspberries, Honey Wine and Blueberries, and Honey Wine and Blackcurrants, Mountain Meadows Cranberry Mead.', 'Entrants must specify carbonation level, strength, and sweetness. Entrants may specify honey varieties. Entrants must specify the varieties of fruit used. A mead made with both berries and non-berry fruit (including apples and grapes) should be entered as a Melomel. A berry mead that is spiced should be entered as a Fruit and Spice Mead. A berry mead containing other ingredients should be entered as an Experimental Mead.'), ";
		$updateSQL .= "(209, 'D', 'Stone Fruit Mead', 'Fruit Mead', '', '', '', '', '', '', 'N/A', 'N/A', 'N/A', 'N/A', 'Mead', 'In well-made examples of the style, the fruit is both distinctive and well-incorporated into the honey-sweet-acid-tannin-alcohol balance of the mead. Different types of fruit can result in widely different characteristics; allow for a variation in the final product.', 'http://bjcp.org/stylecenter.php', 'M2', 'Y', 'bcoe', 'BJCP2015', 1, 1, 1, 1, '', 'Mountain Meadows Cherry Mead, Moonlight Entice, Sumptuous, Flirt, and Smitten, Redstone Sunshine Nectar.', 'Entrants must specify carbonation level, strength, and sweetness. Entrants may specify honey varieties. Entrants must specify the varieties of fruit used. A stone fruit mead that is spiced should be entered as a Fruit and Spice Mead. A stone fruit mead that contains non-stone fruit should be entered as a Melomel. A stone fruit mead that contains other ingredients should be entered as an Experimental Mead.'), ";
		$updateSQL .= "(210, 'E', 'Melomel', 'Fruit Mead', '', '', '', '', '', '', 'N/A', 'N/A', 'N/A', 'N/A', 'Mead', 'In well-made examples of the style, the fruit is both distinctive and well-incorporated into the honey-sweet-acid-tannin-alcohol balance of the mead. Different types of fruit can result in widely different characteristics; allow for a variation in the final product.', 'http://bjcp.org/stylecenter.php', 'M2', 'Y', 'bcoe', 'BJCP2015', 1, 1, 1, 1, '', 'Moonlight Desire, Paramour, and Iniquity.', 'Entrants must specify carbonation level, strength, and sweetness. Entrants may specify honey varieties. Entrants must specify the varieties of fruit used. A melomel that is spiced should be entered as a Fruit and Spice Mead. A melomel containing other ingredients should be entered as an Experimental Mead. Melomels made with either apples or grapes as the only fruit source should be entered as Cysers and Pyments, respectively. Melomels with apples or grapes, plus other fruit should be entered in this category, not Experimental.'); ";
		mysqli_select_db($connection,$database);
		mysqli_real_escape_string($connection,$updateSQL);
		$result = mysqli_query($connection,$updateSQL) or die (mysqli_error($connection));

		$updateSQL = "INSERT INTO ".$prefix."styles (id, brewStyleNum, brewStyle, brewStyleCategory, brewStyleOG, brewStyleOGMax, brewStyleFG, brewStyleFGMax, brewStyleABV, brewStyleABVMax, brewStyleIBU, brewStyleIBUMax, brewStyleSRM, brewStyleSRMMax, brewStyleType, brewStyleInfo, brewStyleLink, brewStyleGroup, brewStyleActive, brewStyleOwn, brewStyleVersion, brewStyleReqSpec, brewStyleStrength, brewStyleCarb, brewStyleSweet, brewStyleTags, brewStyleComEx, brewStyleEntry) VALUES ";
		$updateSQL .= "(211, 'A', 'Fruit and Spice Mead', 'Spiced Mead', '', '', '', '', '', '', 'N/A', 'N/A', 'N/A', 'N/A', 'Mead', 'In well-made examples of the style, the fruits and spices are both distinctive and well-incorporated into the honey-sweet-acid-tannin-alcohol balance of the mead. Different types of fruits and spices can result in widely different characteristics; allow for significant variation in the final product.', 'http://bjcp.org/stylecenter.php', 'M3', 'Y', 'bcoe', 'BJCP2015', 1, 1, 1, 1, '', 'Moonlight Kurt&rsquo;s Apple Pie, Mojo, Flame, Fling, and Deviant, Celestial Meads Scheherazade, Rabbit&rsquo;s Foot Private Reserve Pear Mead, Intermiel Rosee.', 'Entrants must specify carbonation level, strength, and sweetness. Entrants may specify honey varieties. Entrants must specify the types of spices used, (although well-known spice blends may be referred to by common name, such as apple pie spices). Entrants must specify the types of fruits used. If only combinations of spices are used, enter as a Spice, Herb, or Vegetable Mead. If only combinations of fruits are used, enter as a Melomel. If other types of ingredients are used, enter as an Experimental Mead.'), ";
		$updateSQL .= "(212, 'B', 'Spice, Herb, or Vegetable Mead', 'Spiced Mead', '', '', '', '', '', '', 'N/A', 'N/A', 'N/A', 'N/A', 'Mead', 'In well-made examples of the style, the spices are both distinctive and well-incorporated into the honey-sweet-acid-tannin-alcohol balance of the mead. Different types of spices can result in widely different characteristics; allow for a variation in the final product. ', 'http://bjcp.org/stylecenter.php', 'M3', 'Y', 'bcoe', 'BJCP2015', 1, 1, 1, 1, '', 'Moonlight Wicked, Breathless, Madagascar, and Seduction, Redstone Vanilla Beans and Cinnamon Sticks Mountain Honey Wine, Bonair Chili.', 'Entrants MUST specify carbonation level, strength, and sweetness. Entrants MAY specify honey varieties. Entrants MUST specify the types of spices used (although well-known spice blends may be referred to by common name, such as apple pie spices)'), ";
		$updateSQL .= "(213, 'A', 'Braggot', 'Specialty Mead', '', '', '', '', '', '', 'N/A', 'N/A', 'N/A', 'N/A', 'Mead', 'A harmonious blend of mead and beer, with the distinctive characteristics of both. A wide range of results are possible, depending on the base style of beer, variety of honey and overall sweetness and strength. Beer flavors tend to somewhat mask typical honey flavors found in other meads. and honey, although the specific balance is open to creative interpretation by brewers.', 'http://bjcp.org/stylecenter.php', 'M4', 'Y', 'bcoe', 'BJCP2015', 0, 1, 1, 1, '', 'Rabbit&rsquo;s Foot Diabhal and Biere de Miele, Magic Hat Braggot, Brother Adams Braggot Barleywine Ale, White Winter Traditional Brackett.', 'Entrants MUST specify carbonation level, strength, and sweetness. Entrants MAY specify honey varieties. Entrants MAY specify the base style or beer or types of malt used. Products with a relatively low proportion of honey should be entered in the Spiced Beer category as a Honey Beer.'), ";
		$updateSQL .= "(214, 'B', 'Historical Mead', 'Specialty Mead', '', '', '', '', '', '', 'N/A', 'N/A', 'N/A', 'N/A', 'Mead', 'This mead should exhibit the character of all of the ingredients in varying degrees, and should show a good blending or balance between the various flavor elements. Whatever ingredients are included, the result should be identifiable as a honey-based fermented beverage.', 'http://bjcp.org/stylecenter.php', 'M4', 'Y', 'bcoe', 'BJCP2015', 1, 1, 1, 1, '', 'Jadwiga, Saba Tej.', 'Entrants MUST specify carbonation level, strength, and sweetness. Entrants MAY specify honey varieties. Entrants MUST specify the special nature of the mead, providing a description of the mead for judges if no such description is available from the BJCP.'), ";
		$updateSQL .= "(215, 'C', 'Experimental Mead', 'Specialty Mead', '', '', '', '', '', '', 'N/A', 'N/A', 'N/A', 'N/A', 'Mead', 'This mead should exhibit the character of all of the ingredients in varying degrees, and should show a good blending or balance between the various flavor elements. Whatever ingredients are included, the result should be identifiable as a honey-based fermented beverage.', 'http://bjcp.org/stylecenter.php', 'M4', 'Y', 'bcoe', 'BJCP2015', 1, 1, 1, 1, '', 'Moonlight Utopian, Hanssens/Lurgashall Mead the Gueuze, White Winter Cherry Bracket, Mountain Meadows Trickster&rsquo;s Treat Agave Mead.', 'Entrants MUST specify carbonation level, strength, and sweetness. Entrants MAY specify honey varieties. Entrants MUST specify the special nature of the mead, whether it is a combination of existing styles, an experimental mead, or some other creation. Any special ingredients that impart an identifiable character MAY be declared.'), ";
		$updateSQL .= "(216, 'A', 'New World Cider', 'Standard Cider and Perry', '1.045', '1.065', '0.995', '1.02', '5', '8', 'N/A', 'N/A', 'N/A', 'N/A', 'Cider', 'A refreshing drink of some substance - not bland or watery. Sweet ciders must not be cloying. Dry ciders must not be too austere. ', 'http://bjcp.org/stylecenter.php', 'C1', 'Y', 'bcoe', 'BJCP2015', 0, 0, 1, 1, '', '[US] Uncle John&rsquo;s Fruit House Winery Apple Hard Cider, Tandem Ciders Pretty Penny (MI), Bellwether Spyglass (NY), West County Pippin (MA), White Winter Hard Apple Cider (WI), Wandering Aengus Ciderworks Bloom (OR), &Aelig;ppeltreow Appely Brut and Doux (WI).', ''), ";
		$updateSQL .= "(217, 'B', 'English Cider', 'Standard Cider and Perry', '1.05', '1.075', '0.995', '1.015', '6', '9', 'N/A', 'N/A', 'N/A', 'N/A', 'Cider', 'Generally dry, full-bodied, austere. Complex flavor profile, long finish.', 'http://bjcp.org/stylecenter.php', 'C1', 'Y', 'bcoe', 'BJCP2015', 0, 0, 1, 1, '', '[US] Westcott Bay Traditional Very Dry, Dry and Medium Sweet (WA), Farnum Hill Extra-Dry, Dry, and Farmhouse (NH), Wandering Aengus Dry Cider (OR), Montana CiderWorks North Fork (MT), Bellwether Heritage (NY). [UK] Oliver&rsquo;s Traditional Dry, Hogan&rsquo;s Dry and Medium Dry, Henney&rsquo;s Dry and Vintage Still, Burrow Hill Medium, Aspall English Imperial.', ''), ";
		$updateSQL .= "(218, 'C', 'French Cider', 'Standard Cider and Perry', '1.05', '1.065', '1.01', '1.02', '3', '6', 'N/A', 'N/A', 'N/A', 'N/A', 'Cider', 'Medium to sweet, full-bodied, rich.', 'http://bjcp.org/stylecenter.php', 'C1', 'Y', 'bcoe', 'BJCP2015', 0, 0, 1, 1, '', '[US] West County Reine de Pomme (MA), [France] Eric Bordelet (various), Etienne Dupont, Etienne Dupont Organic, Bellot.', ''), ";
		$updateSQL .= "(219, 'D', 'New World Perry', 'Standard Cider and Perry', '1.05', '1.06', '1', '1.02', '5', '7', 'N/A', 'N/A', 'N/A', 'N/A', 'Cider', 'Mild. Medium to medium-sweet. Still to lightly sparkling. Only very slight acetification is acceptable. Mousiness, ropy/oily characters are serious faults.', 'http://bjcp.org/stylecenter.php', 'C1', 'Y', 'bcoe', 'BJCP2015', 0, 0, 1, 1, '', '[US] White Winter Hard Pear Cider (WI), Uncle John&rsquo;s Fruit House Winery Perry (MI).', ''), ";
		$updateSQL .= "(220, 'E', 'Traditional Perry', 'Standard Cider and Perry', '1.05', '1.07', '1', '1.02', '5', '9', 'N/A', 'N/A', 'N/A', 'N/A', 'Cider', 'Tannic. Medium to medium-sweet. Still to lightly sparkling. Only very slight acetification is acceptable. Mousiness and ropy/oily characters are serious faults.', 'http://bjcp.org/stylecenter.php', 'C1', 'Y', 'bcoe', 'BJCP2015', 1, 0, 1, 1, '', '[US] Appeltreow Orchard Oriole Perry (WI); [France] Bordelet Poire Authentique and Poire Granit, Christian Drouin Poire, [UK] Oliver&rsquo;s Classic, Blakeney Red, and Herefordshire Dry; Hogan&rsquo;s Vintage Perry.', 'Entrants MUST specify carbonation level (3 levels). Entrants MUST specify sweetness (5 categories). Entrants MUST state variety of pear(s) used.'); ";
		mysqli_select_db($connection,$database);
		mysqli_real_escape_string($connection,$updateSQL);
		$result = mysqli_query($connection,$updateSQL) or die (mysqli_error($connection));

		$updateSQL = "INSERT INTO ".$prefix."styles (id, brewStyleNum, brewStyle, brewStyleCategory, brewStyleOG, brewStyleOGMax, brewStyleFG, brewStyleFGMax, brewStyleABV, brewStyleABVMax, brewStyleIBU, brewStyleIBUMax, brewStyleSRM, brewStyleSRMMax, brewStyleType, brewStyleInfo, brewStyleLink, brewStyleGroup, brewStyleActive, brewStyleOwn, brewStyleVersion, brewStyleReqSpec, brewStyleStrength, brewStyleCarb, brewStyleSweet, brewStyleTags, brewStyleComEx, brewStyleEntry) VALUES ";
		$updateSQL .= "(221, 'A', 'New England Cider', 'Specialty Cider and Perry', '1.06', '1.1', '0.995', '1.02', '7', '13', 'N/A', 'N/A', 'N/A', 'N/A', 'Cider', 'Substantial body and character. Typically relatively dry, but can be somewhat sweet if in balance and not containing hot alcohol.', 'http://bjcp.org/stylecenter.php', 'C2', 'Y', 'bcoe', 'BJCP2015', 1, 0, 1, 1, '', '[US] Snowdrift Semi-Dry (WA), Blackbird Cider Works New England Style (NY).', 'Entrants MUST specify if the cider was barrel-fermented or aged. Entrants MUST specify carbonation level (3 levels). Entrants MUST specify sweetness (5 levels).'), ";
		$updateSQL .= "(222, 'B', 'Cider with Other Fruit', 'Specialty Cider and Perry', '1.045', '1.07', '0.995', '1.01', '5', '9', 'N/A', 'N/A', 'N/A', 'N/A', 'Cider', 'Substantial. May be significantly tannic, depending on fruit added.', 'http://bjcp.org/stylecenter.php', 'C2', 'Y', 'bcoe', 'BJCP2015', 1, 0, 1, 1, '', '[US] West County Blueberry-Apple Wine (MA), Bellwether Cherry Street (NY), Uncle John&rsquo;s Fruit Farm Winery Apple Cherry, Apple Blueberry, and Apricot Apple Hard Cider (MI).', 'Entrants MUST specify carbonation level (3 levels). Entrants MUST specify sweetness (5 categories). Entrants MUST specify all fruit(s) and/or fruit juice(s) added.'), ";
		$updateSQL .= "(223, 'C', 'Applewine', 'Specialty Cider and Perry', '1.07', '1.1', '0.995', '1.02', '9', '12', 'N/A', 'N/A', 'N/A', 'N/A', 'Cider', 'Typically like a dry white wine, balanced, and with low astringency and bitterness. ', 'http://bjcp.org/stylecenter.php', 'C2', 'Y', 'bcoe', 'BJCP2015', 0, 0, 1, 1, '', '[US] Uncle John&rsquo;s Fruit House Winery Fruit House Apple (MI), McClure&rsquo;s Sweet Apple Wine (IN).', 'Entrants MUST specify carbonation level (3 levels). Entrants MUST specify sweetness (5 levels).'), ";
		$updateSQL .= "(224, 'D', 'Ice Cider', 'Specialty Cider and Perry', '1.13', '1.18', '1.06', '1.085', '7', '13', 'N/A', 'N/A', 'N/A', 'N/A', 'Cider', 'This is a cider style in which the juice is concentrated before fermentation either by freezing fruit before pressing or freezing juice and removing water.', 'http://bjcp.org/stylecenter.php', 'C2', 'Y', 'bcoe', 'BJCP2015', 1, 0, 1, 0, '', '[US] various from Eden Ice Cider Company and Champlain Orchards. [Canada] Domaine Pinnacle, Les Vergers de la Colline, and Cidrerie St-Nicolas (Quebec).', 'Entrants MUST specify starting gravity, final gravity or residual sugar, and alcohol level. Entrants MUST specify carbonation level (3 levels).'), ";
		$updateSQL .= "(225, 'E', 'Cider with Herbs/Spices', 'Specialty Cider and Perry', '1.045', '1.07', '0.995', '1.01', '5', '9', 'N/A', 'N/A', 'N/A', 'N/A', 'Cider', 'Like a white wine with complex flavors. The apple character must marry with the botanicals and give a balanced result.', 'http://bjcp.org/stylecenter.php', 'C2', 'Y', 'bcoe', 'BJCP2015', 1, 0, 1, 1, '', '[US] Colorado Cider Grasshop-ah (CO), Wandering Aengus Anthem Hops (OR).', 'Entrants MUST specify carbonation level (3 levels). Entrants MUST specify sweetness (5 categories). Entrants MUST specify all botanicals added. If hops are used, entrant must specify variety/varieties used.'), ";
		$updateSQL .= "(226, 'F', 'Specialty Cider/Perry', 'Specialty Cider and Perry', '1.045', '1.1', '0.995', '1.02', '5', '12', 'N/A', 'N/A', 'N/A', 'N/A', 'Cider', 'This is an open-ended category for cider or perry with other ingredients such that it does not fit any of the other BJCP categories.', 'http://bjcp.org/stylecenter.php', 'C2', 'Y', 'bcoe', 'BJCP2015', 1, 0, 1, 1, '', '', 'Entrants MUST specify all ingredients. Entrants MUST specify carbonation level (3 levels). Entrants MUST specify sweetness (5 categories).'),";

		$updateSQL .= "(227, 'B3', 'Brown IPA', 'Specialty IPA', '1.056', '1.070', '1.008', '1.016', '5.5', '7.5', '40', '70', '11', '19', '1', 'Hoppy, bitter, and moderately strong like an American IPA, but with some caramel, chocolate, toffee, and/or dark fruit malt character as in an American Brown Ale. Retaining the dryish finish and lean body that makes IPAs so drinkable, a Brown IPA is a little more flavorful and malty than an American IPA without being sweet or heavy.', 'http://bjcp.org/stylecenter.php', '21', 'Y', 'bcoe', 'BJCP2015', 1, 0, 0, 0, 'high-strength, dark-color, top-fermented, north-america, craft-style, ipa-family, specialty-family, bitter, hoppy', 'Dogfish Head Indian Brown Ale, Grand Teton Bitch Creek, Harpoon Brown IPA, Russian River Janet’s Brown Ale', 'Entrant MUST specify a strength (session: 3.0-5.0%, standard: 5.0-7.5%, double: 7.5-9.5%).'),
(228, 'B4', 'Red IPA', 'Specialty IPA', '1.056', '1.070', '1.008', '1.016', '5.5', '7.5', '40', '70', '11', '19', '1', 'Hoppy, bitter, and moderately strong like an American IPA, but with some caramel, toffee, and/or dark fruit malt character. Retaining the dryish finish and lean body that makes IPAs so drinkable, a Red IPA is a little more flavorful and malty than an American IPA without being sweet or heavy.', 'http://bjcp.org/stylecenter.php', '21', 'Y', 'bcoe', 'BJCP2015', 1, 0, 0, 0, 'high-strength, amber-color, top-fermented, north-america, craft-style, ipa-family, specialty-family, bitter, hoppy', 'Green Flash Hop Head Red Double Red IPA (double), Midnight Sun Sockeye Red, Sierra Nevada Flipside Red IPA, Summit Horizon Red IPA, Odell Runoff Red IPA', 'Entrant MUST specify a strength (session: 3.0-5.0%, standard: 5.0-7.5%, double: 7.5-9.5%).'),
(229, 'B5', 'Rye IPA', 'Specialty IPA', '1.056', '1.075', '1.008', '1.014', '5.5', '8.0', '50', '75', '6', '14', '1', 'A decidedly hoppy and bitter, moderately strong American pale ale, showcasing modern American and New World hop varieties and rye malt. The balance is hop-forward, with a clean fermentation profile, dry finish, and clean, supporting malt allowing a creative range of hop character to shine through.', 'http://bjcp.org/stylecenter.php', '21', 'Y', 'bcoe', 'BJCP2015', 1, 0, 0, 0, 'high-strength, amber-color, top-fermented, north-america, craft-style, ipa-family, specialty-family, bitter, hoppy', 'Arcadia Sky High Rye, Bear Republic Hop Rod Rye, Founders Reds Rye, Great Lakes Rye of the Tiger, Sierra Nevada Ruthless Rye', 'Entrant MUST specify a strength (session: 3.0-5.0%, standard: 5.0-7.5%, double: 7.5-9.5%).'),
(230, 'B6', 'White IPA', 'Specialty IPA', '1.056', '1.065', '1.010', '1.016', '5.5', '7.0', '40', '70', '5', '8', '1', 'A fruity, spicy, refreshing version of an American IPA, but with a lighter color, less body, and featuring either the distinctive yeast and/or spice additions typical of a Belgian witbier.', 'http://bjcp.org/stylecenter.php', '21', 'Y', 'bcoe', 'BJCP2015', 1, 0, 0, 0, 'high-strength, pale-color, top-fermented, north-america, craft-style, ipa-family, specialty-family, bitter, hoppy, spice', 'Blue Point White IPA, Deschutes Chainbreaker IPA, Harpoon The Long Thaw, New Belgium Accumulation', 'Entrant MUST specify a strength (session: 3.0-5.0%, standard: 5.0-7.5%, double: 7.5-9.5%).'),
(231, 'A9', 'London Brown Ale', 'Historical Beer', '1.033', '1.038', '1.012', '1.015', '2.8', '3.6', '15', '20', '22', '35', '1', 'A luscious, sweet, malt-oriented dark brown ale, with caramel and toffee malt complexity and a sweet finish.', 'http://bjcp.org/stylecenter.php', '27', 'Y', 'bcoe', 'BJCP2015', 0, 0, 0, 0, 'session-strength, dark-color, top-fermented, britishisles, historical-style, brown-ale-family, malty, sweet', 'Harveys Bloomsbury Brown Ale, Mann''s Brown Ale', 'Entrant MUST specify a strength (session: 3.0-5.0%, standard: 5.0-7.5%, double: 7.5-9.5%).'),
(232, 'B1', 'Belgian IPA', 'Specialty IPA', '1.058', '1.080', '1.008', '1.016', '6.2', '9.5', '50', '100', '5', '15', '1', 'An IPA with the fruitiness and spiciness derived from the use of Belgian yeast. The examples from Belgium tend to be lighter in color and more attenuated, similar to a tripel that has been brewed with more hops. This beer has a more complex flavor profile and may be higher in alcohol than a typical IPA.', 'http://bjcp.org/stylecenter.php', '21', 'Y', 'bcoe', 'BJCP2015', 1, 0, 0, 0, 'high-strength, pale-color, top-fermented, north-america, craft-style, ipa-family, specialty-family, bitter, hoppy', 'Brewery Vivant Triomphe, Houblon Chouffe, Epic Brainless IPA, Green Flash Le Freak, Stone Cali-Belgique, Urthel Hop It', 'Entrant MUST specify a strength (session: 3.0-5.0%, standard: 5.0-7.5%, double: 7.5-9.5%).'),
(233, 'B2', 'Black IPA', 'Specialty IPA', '1.050', '1.085', '1.010', '1.018', '5.5', '9.0', '50', '90', '25', '40', '1', 'A beer with the dryness, hop-forward balance, and flavor characteristics of an American IPA, only darker in color – but without strongly roasted or burnt flavors. The flavor of darker malts is gentle and supportive, not a major flavor component. Drinkability is a key characteristic.', 'http://bjcp.org/stylecenter.php', '21', 'Y', 'bcoe', 'BJCP2015', 1, 0, 0, 0, 'high-strength, dark-color, top-fermented, north-america, craft-style, ipa-family, specialty-family, bitter, hoppy', '21st Amendment Back in Black (standard), Deschutes Hop in the Dark CDA (standard), Rogue Dad’s Little Helper (standard), Southern Tier Iniquity (double), Widmer Pitch Black IPA (standard)', 'Entrant MUST specify a strength (session: 3.0-5.0%, standard: 5.0-7.5%, double: 7.5-9.5%).'),
(234, 'A8', 'Pre-Prohibition Porter', 'Historical Beer', '1.046', '1.060', '1.010', '1.016', '4.5', '6.0', '20', '30', '18', '30', '1', 'An American adaptation of English Porter using American ingredients, including adjuncts.', 'http://bjcp.org/stylecenter.php', '27', 'Y', 'bcoe', 'BJCP2015', 0, 0, 0, 0, 'standard-strength, dark-color, any-fermentation, northamerica, historical-style, porter-family, malty', 'Stegmaier Porter, Yuengling Porter', NULL),
(235, 'A4', 'Roggenbier', 'Historical Beer', '1.046', '1.056', '1.010', '1.014', '4.5', '6.0', '10', '20', '14', '19', '1', 'A dunkelweizen made with rye rather than wheat, but with a greater body and light finishing hops.', 'http://bjcp.org/stylecenter.php', '27', 'Y', 'bcoe', 'BJCP2015', 0, 0, 0, 0, 'standard-strength, amber-color, top-fermenting, central-europe, historical-style, wheat-beer-family', 'Thurn und Taxis Roggen', NULL),
(236, 'A5', 'Sahti', 'Historical Beer', '1.076', '1.120', '1.016', '1.020', '7.0', '11.0', '7', '15', '4', '22', '1', 'A sweet, heavy, strong traditional Finnish beer with a rye, juniper, and juniper berry flavor and a strong banana-clove yeast character.', 'http://bjcp.org/stylecenter.php', '27', 'Y', 'bcoe', 'BJCP2015', 0, 0, 0, 0, 'high-strength, amber-color, top-fermented, centraleurope, historical-style, spice', NULL, NULL),
(237, 'A6', 'Kentucky Common', 'Historical Beer', '1.044', '1.055', '1.010', '1.018', '4.0', '5.5', '15', '30', '11', '20', '1', 'A darker-colored, light-flavored, malt-accented beer with a dry finish and interesting character malt flavors. Refreshing due to its high carbonation and mild flavors, and highly  sessionable due to being served very fresh and with restrained alcohol levels.', 'http://bjcp.org/stylecenter.php', '27', 'Y', 'bcoe', 'BJCP2015', 0, 0, 0, 0, 'standard-strength, amber-color, top-fermented, north america,historical-style, balanced', 'Apocalypse Brew Works Ortel''s 1912', NULL),
(238, 'A7', 'Pre-Prohibition Lager', 'Historical Beer', '1.044', '1.060', '1.010', '1.015', '4.5', '6.0', '25', '40', '3', '6', '1', 'A clean, refreshing, but bitter pale lager, often showcasing a grainy-sweet corn flavor. All malt or rice-based versions have a crisper, more neutral character. The higher bitterness level is the largest differentiator between this style and most modern mass-market pale lagers, but the more robust flavor profile also sets it apart.', 'http://bjcp.org/stylecenter.php', '27', 'Y', 'bcoe', 'BJCP2015', 0, 0, 0, 0, 'standard-strength, pale-color, bottom-fermented, lagered, north-america, historical-style, pilsner-family, bitter, hoppy', 'Anchor California Lager, Coors Batch 19, Little Harpeth Chicken Scratch', NULL),
(239, 'A1', 'Gose', 'Historical Beer', '1.036', '1.056', '1.006', '1.010', '4.2', '4.8', '5', '12', '3', '4', '1', 'A highly-carbonated, tart and fruity wheat ale with a restrained coriander and salt character and low bitterness. Very refreshing, with bright flavors and high attenuation.', 'http://bjcp.org/stylecenter.php', '27', 'Y', 'bcoe', 'BJCP2015', 0, 0, 0, 0, 'standard-strength, pale-color, top-fermented, centraleurope, historical-style, wheat-beer-family, sour, spice', 'Anderson Valley Gose, Bayerisch Bahnhof Leipziger Gose, Dollnitzer Ritterguts Gose', NULL),
(240, 'A2', 'Piwo Grodziskie', 'Historical Beer', '1.028', '1.032', '1.010', '1.015', '4.5', '6.0', '25', '40', '3', '6', '1', 'A low-gravity, highly-carbonated, light bodied ale combining an oak-smoked flavor with a clean hop bitterness. Highly sessionable.', 'http://bjcp.org/stylecenter.php', '27', 'Y', 'bcoe', 'BJCP2015', 0, 0, 0, 0, 'standard-strength, pale-color, bottom-fermented,lagered, north-america, historical-style, pilsner-family, bitter, hoppy', NULL, NULL),
(241, 'A3', 'Lichtenhainer', 'Historical Beer', '1.032', '1.040', '1.004', '1.008', '3.5', '4.7', '5', '12', '3', '6', '1', 'A sour, smoked, lower-gravity historical German wheat beer. Complex yet refreshing character due to high attenuation and carbonation, along with low bitterness and moderate sourness. ', 'http://bjcp.org/stylecenter.php', '27', 'Y', 'bcoe', 'BJCP2015', 0, 0, 0, 0, 'standard-strength, pale-color, top-fermented, centraleurope, historical-style, wheat-beer-family, sour, smoke', NULL, NULL);";

		mysqli_select_db($connection,$database);
		mysqli_real_escape_string($connection,$updateSQL);
		$result = mysqli_query($connection,$updateSQL) or die (mysqli_error($connection));

		$output .= "<li class=\"list-group-item\"><span class=\"fa fa-lg fa-check text-success\"></span> <strong>Styles</strong> data installed successfully.</li>";

		// -------------------
		// Style Types Table
		// -------------------

		$sql = "
		CREATE TABLE IF NOT EXISTS `$style_types_db_table` (
			`id` int(11) NOT NULL AUTO_INCREMENT,
			`styleTypeName` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
			`styleTypeOwn` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
			`styleTypeBOS` char(1) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
			`styleTypeBOSMethod` int(11) DEFAULT NULL,
			PRIMARY KEY (`id`)
		) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE utf8mb4_unicode_ci;
		";
		mysqli_select_db($connection,$database);
		mysqli_real_escape_string($connection,$sql);
		$result = mysqli_query($connection,$sql) or die (mysqli_error($connection));
		$output .= "<li class=\"list-group-item\"><span class=\"fa fa-lg fa-check text-success\"></span> The <strong>Style Types</strong> table was installed successfully.</li>";

		$sql = "
		INSERT INTO `$style_types_db_table` (`id`, `styleTypeName`, `styleTypeOwn`, `styleTypeBOS`, `styleTypeBOSMethod`) VALUES
		(1, 'Beer', 'bcoe', 'Y', 1),
		(2, 'Cider', 'bcoe', 'Y', 1),
		(3, 'Mead', 'bcoe', 'Y', 1);
		";
		mysqli_select_db($connection,$database);
		mysqli_real_escape_string($connection,$sql);
		$result = mysqli_query($connection,$sql) or die (mysqli_error($connection));
		 //echo "<p>".$sql."</p>";

		$output .= "<li class=\"list-group-item\"><span class=\"fa fa-lg fa-check text-success\"></span> <strong>Style Types</strong> data installed successfully.</li>";


		// -------------------
		// System Table
		// -------------------

		$sql = "
		CREATE TABLE IF NOT EXISTS `$system_db_table` (
			`id` int(11) NOT NULL AUTO_INCREMENT,
			`version` varchar(12) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
			`version_date` date DEFAULT NULL,
			`data_check` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
			`setup` tinyint(1) DEFAULT NULL COMMENT 'Has setup run? 1=true, 0=false.',
			`setup_last_step` int(3) DEFAULT NULL,
			PRIMARY KEY (`id`)
		) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE utf8mb4_unicode_ci ;
		";
		mysqli_select_db($connection,$database);
		mysqli_real_escape_string($connection,$sql);
		$result = mysqli_query($connection,$sql) or die (mysqli_error($connection));
		$output .= "<li class=\"list-group-item\"><span class=\"fa fa-lg fa-check text-success\"></span> The <strong>System</strong> table was installed successfully.</li>";

		$sql = "INSERT INTO `$system_db_table` (`id`, `version`, `version_date`, `data_check`,`setup`, `setup_last_step`) VALUES (1, '2.1.10.0', '".$current_version_date_display."', NOW( ),'0','0');";
		mysqli_select_db($connection,$database);
		mysqli_real_escape_string($connection,$sql);
		$result = mysqli_query($connection,$sql) or die (mysqli_error($connection));
		 //echo "<p>".$sql."</p>";

		$output .= "<li class=\"list-group-item\"><span class=\"fa fa-lg fa-check text-success\"></span> <strong>System</strong> data installed successfully.</li>";

		// -------------------
		// Users Table
		// -------------------

		$sql = "
		CREATE TABLE IF NOT EXISTS `$users_db_table` (
			`id` int(11) NOT NULL AUTO_INCREMENT,
			`user_name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
			`password` varchar(250) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
			`userLevel` char(1) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
			`userQuestion` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
			`userQuestionAnswer` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
			`userCreated` timestamp NULL DEFAULT NULL COMMENT 'Timestamp of when the user was created.',
			PRIMARY KEY (`id`)
		) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE utf8mb4_unicode_ci ;
		";

		mysqli_select_db($connection,$database);
		mysqli_real_escape_string($connection,$sql);
		$result = mysqli_query($connection,$sql) or die (mysqli_error($connection));

		$output .= "<li class=\"list-group-item\"><span class=\"fa fa-lg fa-check text-success\"></span> The <strong>Users</strong> table was installed successfully.</li>";

		$output .=  "</ul>";
		$output .=  "</div>";


		/* -------------------------------------------------
		 * Make sure all off-schedule updates have also
		 * been instantiated.
		 * -------------------------------------------------
		 */

		$output .=  "<h3>Other Installation Items</h3>";
		$output .=  "<ul>";
		include(UPDATE.'off_schedule_update.php');
		$output .=  "</ul>";

	}

	//echo $output;

}

?>
