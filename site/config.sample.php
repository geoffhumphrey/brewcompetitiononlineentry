<?php
/**
 * Module:        config.php
 * Description:   This module houses configuration variables for DB connection, etc.
 * Last Modified: 12 August 2026
 *
 * ******************************************************************************
 * SETUP INSTRUCTIONS - READ BEFORE UPLOADING
 * ******************************************************************************
 * This is a template file. Before uploading to your server:
 *
 *   1. Fill in your own database connection details and any other settings
 *      below (look for the blank '' values with instructions above them).
 *   2. Rename this file from "config.sample.php" to "config.php".
 *   3. Upload/keep the renamed "config.php" in this same "site" folder on
 *      your server.
 *
 * Do NOT upload this file as "config.sample.php" - the application looks
 * for "config.php" specifically. Once renamed and filled in, this file
 * contains your real credentials and should never be committed to a public
 * repository or shared publicly.
 * ******************************************************************************
 *
 * ALREADY RUNNING BCOE&M AND UPDATING TO A NEW VERSION?
 * Before uploading any updated files to your server, back up your database
 * first. The update process makes real changes to your existing data, and
 * a backup lets you restore your competition's information if anything
 * goes wrong during the update.
 * ******************************************************************************
 */

/**
 * ******************************************************************************
 * Set up MySQL connection variables
 * ******************************************************************************
 *
 * Generally, 'localhost' will work for most environments. 
 * However, some environments may require another hostname.
 * *** This has been confirmed for GO DADDY shared hosting users.         
 * *** This article details how to change "localhost" to suit your Go Daddy 
 *     enviornment.
 * *** https://www.godaddy.com/help/viewing-your-database-details-with-shared-hosting-accounts-39
 */

$hostname = 'localhost';

/**
 * Enter the username for your database (generally the same as your login code 
 * for your web hosting company).
 * INSERT YOUR USERNAME BETWEEN THE SINGLE-QUOTATION MARKS ('').
 * For example, if your username is fred then the line should read 
 * $username = 'fred'.
 */


$username = '';

/**
 * INSERT YOUR PASSWORD BETWEEN THE SINGLE-QUOTATION MARKS ('').
 * For example, if your password is flintstone then the line should read 
 * $password = 'flintsone'.
 */

$password = '';

/**
 * The following line is the name of your MySQL database you set up already.
 * If you haven't set up the database yet, please refer to
 * http://brewingcompetitions.com/install-instructions for setup instructions.
 */

$database = '';

/**
 * If the database port is different from the default then overwrite as the 
 * port integer
 * Example: $database_port = 3308;
 */

$database_port = ini_get('mysqli.default_port');

/**
 * This line strings the information together and connects to MySQL.
 * If MySQL is not found or the username/password combo is not correct an
 * error will be returned.
 */

/**
 * Reuse the existing connection via $GLOBALS if one is already open and alive, 
 * rather than opening a brand new mysqli connection every time - on hosts with 
 * a low per-account connection limit, a single page load touching many such
 * functions can otherwise exhaust the limit mid-request.
 */

if ((isset($GLOBALS['connection'])) && ($GLOBALS['connection'] instanceof mysqli) && (@$GLOBALS['connection']->ping())) {
	$connection = $GLOBALS['connection'];
}

else {
	$connection = new mysqli($hostname, $username, $password, $database, $database_port);
	mysqli_set_charset($connection,'utf8mb4');
	mysqli_query($connection, "SET NAMES 'utf8mb4';");
	mysqli_query($connection, "SET CHARACTER SET 'utf8mb4';");
	mysqli_query($connection, "SET COLLATION_CONNECTION = 'utf8mb4_unicode_ci';");
	mysqli_query($connection, "SET sql_mode = '';");
	$GLOBALS['connection'] = $connection;
}

/**
 * Do not change the following line.
 */

$brewing = $connection;

/**
 * ******************************************************************************
 * End MySQL connections variables
 * ******************************************************************************
 */

/*
 * ******************************************************************************
 * DB Prefix.
 * ******************************************************************************
 * The following variable is used to define a prefix to the database tables.
 * This is useful if you wish to have separate installations or applications share
 * the same mySQL database.
 *
 * Leave as if you have a database dedicated to your BCOE&M installation.
 *
 * Suggested Usage
 * If you wish to define a prefix to the database tables, it is HIGHLY suggested
 * that you use an underscore (_), after a short descriptor that identifies which
 * install is using which tables.
 * Example:
 * $prefix = 'bcoem1_';
 * OR
 * $prefix = 'comp1_';
 *
 * For HOSTED installations only:
 * The HOSTED constant is defined in paths.php.
 * Leave the block below exactly as-is for a normal, single-tenant install;
 * it's inert (falls straight to the plain $prefix = ''; default) unless
 * HOSTED is TRUE.
 *
 * Multiple installations under one hosting account, each in its own
 * subdirectory, derive their own $prefix automatically from that
 * subdirectory's name instead of it being hand-edited per install. This also
 * canonicalizes access: if an install is reached via a subdomain matching its
 * own folder name (e.g. a legacy "installname.brewingcompetitions.com" URL
 * still pointing here), it's redirected to the canonical path-based URL
 * instead of serving duplicate content at two addresses.
 *
 * The exit() after header() is required. Without it, PHP keeps executing
 * and renders the entire page body after the redirect header - wasted work
 * on that request, and since this file is require()'d (not require_once()'d)
 * from many places elsewhere in the app, the exact same header() call fires
 * again on a later re-inclusion within the same request, by which point real
 * page output has already started - producing a "headers already sent"
 * warning instead of the redirect actually happening.
 */

$path = dirname(__FILE__);

if (HOSTED) {

	$path_parts = explode(DIRECTORY_SEPARATOR, $path);
	$install_folder = $path_parts[count($path_parts) - 2];
	$subdomain = explode('.', $_SERVER['HTTP_HOST'])[0];

	$prefix = $install_folder."_";

	if ($subdomain == $install_folder) {
		$redirect = "https://brewingcompetitions.com/".$install_folder;
		$redirect_go_to = sprintf("Location: %s", $redirect);
		header($redirect_go_to);
		exit();
	}

}

else $prefix = '';

/*
 * ******************************************************************************
 * Installation ID.
 * ******************************************************************************
 * Give your installation a unique ID. If you plan on running multiple instances
 * of BCOE&M from the same domain, you'll need to give each installation a
 * unique identifier. This prevents "cross-pollination" of session data display.
 *
 * For single installations, the default below will be sufficient. Otherwise,
 * change the variable to something completely unique for each installation.
 */

$installation_id = '';

/*
 * ******************************************************************************
 * User session time out
 * ******************************************************************************
 * Define the time (in minutes) that a user's session will be active before it
 * expires due to inactivity. Default is 30 minutes.
 */

$session_expire_after = 30;

/*
 * ******************************************************************************
 * Access control for Setup.
 * ******************************************************************************
 * If you are going to go through the installation and setup process, you will
 * need to modify the access check statement below. Change the FALSE to a TRUE
 * to disable the access check.
 *
 * After finishing setup, be sure to open this file again and change the
 * TRUE back to a FALSE!
 */

$setup_free_access = FALSE;

/*
 * ******************************************************************************
 * Set the subdirectory of your installation (if necessary).
 * ******************************************************************************
 * In most cases the default will be OK.
 *
 * IF YOU ARE RUNNING YOUR INSTANCE OF BCOE&M IN A SUBFOLDER...
 *
 * - Add the name of the subdirectory between the quotes of the $sub_directory
 *   variable.
 * - Be sure to INCLUDE a leading slash [/] and NO trailing slash [/]!
 *
 * Example:
 * $sub_directory = "/bcoem";
 *
 * WARNING!!!
 * IF you do enable the subdirectory variable, YOU MUST alter your .htaccess file
 * Otherwise, the URLs will not be generated correctly! Directions are in the
 * .htaccess file.
 */

$sub_directory = '';

/*
 * ******************************************************************************
 * Set the base URL of your installation.
 * ******************************************************************************
 * In most cases the default will be OK.
 *
 * IF you are installing on a server where you do not have a domain name set up,
 * you'll need to replace the last $base_url variable below with something
 * formatted like this:
 * $base_url .= 'yourhostingdomain/~accountname/subdirectoryname/';
 *
 * Example:
 * $base_url .= '147.21.160.5/~brewcompetition/bcoem/';
 * OR:
 * $base_url .= 'www.bluehost.com/~brewcompeition/bcoem/';
 * 
 * To override the SSL (HTTPS) check if SSL isn't implemented on your
 * server AND you're experiencing log in or session issues, or if pages are not 
 * rendering correctly, comment out the second line in the block below (the if
 * statement).
 * @fixes https://github.com/geoffhumphrey/brewcompetitiononlineentry/issues/1123
 */

$base_url = 'http://';
if (is_https()) $base_url = 'https://';
$base_url .= $_SERVER['SERVER_NAME'].$sub_directory.'/';

/*
 * ******************************************************************************
 * Set the server root for your installation.
 * ******************************************************************************
 * In most cases the default will be OK.
 *
 * IF you are installing on a server and will access the software via a sub-domain
 * (e.g. http://subdomain.domain.com), comment out the first variable below and
 * uncomment the second variable ONLY if you are experiencing issues. Otherwise,
 * the default will suffice.
 */

$server_root = $_SERVER['DOCUMENT_ROOT'];
//$server_root = $_SERVER['SUBDOMAIN_DOCUMENT_ROOT'];

?>
