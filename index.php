<?php 
/**
 * Module:      index.php 
 * Description: This module is the delivery vehicle for all modules.
 * 
 */

// $locale = system('locale -a');
require('paths.php');
require(CONFIG.'bootstrap.php');

// Load any mods
include(DB.'mods.db.php');

$account_pages = array("list","pay","brewer","user","brew","beerxml","pay");
if ((!$logged_in) && (in_array($section,$account_pages))) {
	header(sprintf("Location: %s", $base_url."index.php?section=login&msg=99")); exit;
}
	
if ($section == "admin") {
	
	// Redirect if non-admins try to access admin functions
	if (!$logged_in) { header(sprintf("Location: %s", $base_url."index.php?section=login&msg=0")); exit; }
	if (($logged_in) && ($_SESSION['userLevel'] > 1)) { header(sprintf("Location: %s", $base_url."index.php?msg=4")); exit; }
	
	include(LIB.'admin.lib.php');
	include(DB.'admin_common.db.php');
	include(DB.'judging_locations.db.php'); 
	include(DB.'stewarding.db.php'); 
	include(DB.'dropoff.db.php'); 
	include(DB.'contacts.db.php');
	
	if (($go == "default") || ($go == "entries")) {
		$totalRows_entry_count = total_paid_received("default","default");
	}
}

if (TESTING) {
	$mtime = microtime(); 
	$mtime = explode(" ",$mtime); 
	$mtime = $mtime[1] + $mtime[0]; 
	$starttime = $mtime; 
}

if (HOSTED) check_hosted_gh();

if (strpos($section, 'step') === FALSE)  {
	version_check($version,$current_version);
}

if ($section == "admin") {
	$container_main = "container-fluid";
	$nav_container = "navbar-inverse";
}
	
else { 
	$container_main = "container";
	$nav_container = "navbar-default";
}

// Load libraries only when needed - for performance
$tinymce_load = array("contest_info","special_best","styles","default");
$datetime_load = array("contest_info","judging","testing","preferences");
if ((judging_date_return() == 0) && ($registration_open == 2)) $datatables_load = array("admin","list","default");
else $datatables_load = array("admin","list");

if (($section == "admin") && (($filter == "default") && ($bid == "default") && ($view == "default"))) $entries_unconfirmed = ($totalRows_entry_count - $totalRows_log_confirmed); else $entries_unconfirmed = ($totalRows_log - $totalRows_log_confirmed);

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="Content-type" content="text/html; charset=UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?php echo $_SESSION['contestName']; ?> - Brew Competition Online Entry &amp; Management</title>
    
	<?php 
	// Default - Use CDN
	include(INCLUDES.'load_cdn_libraries.inc.php');
	
	// Load Locally
	// Refer to instructions at http://brewcompetition.com/local-load
	// To load libraries locally uncomment the line below and comment out line 81 above 
	// include(INCLUDES.'load_local_libraries.inc.php'); 
	
	?>
    
    <!-- Load BCOE&M Custom Theme CSS - Contains Bootstrap overrides and custom classes -->
    <link rel="stylesheet" type="text/css" href="<?php echo $theme; ?>" />
	
	<!-- Load BCOE&M Custom JS -->
    <script src="<?php echo $base_url; ?>js_includes/bcoem_custom.min.js"></script>
    <?php if (($section == "admin") && (in_array($go,$tinymce_load))) include(INCLUDES."tinymce_init_js.inc.php"); ?>
    
    <!-- Opengraph Implementation -->
    <?php if (!empty($_SESSION['contestName'])) { ?>
    <meta property="og:title" content="<?php echo $_SESSION['contestName']?>" />
    <?php } ?>
    <?php if (!empty($_SESSION['contestLogo'])) { ?>
    <meta property="og:image" content="<?php echo $base_url."user_images/".$_SESSION['contestLogo']?>" />
    <?php } ?>
    <meta property="og:url" content="<?php echo "http" . ((!empty($_SERVER['HTTPS'])) ? "s://" : "://") . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI']; ?>" />
  
</head>
<body>
	
    <!-- MAIN NAV -->
	<div class="<?php echo $container_main; ?> hidden-print">
		<?php include (SECTIONS.'nav.sec.php'); ?>
	</div><!-- container -->   
    <!-- ./MAIN NAV -->
    
    <!-- ALERTS -->
    <div class="<?php echo $container_main; ?> bcoem-warning-container">
    	<?php include (SECTIONS.'alerts.sec.php'); ?>
    </div><!-- ./container --> 
    <!-- ./ALERTS -->
    
    <?php if ($_SESSION['prefsUseMods'] == "Y") { ?>
    <!-- MODS TOP -->
    <?php include(INCLUDES.'mods_top.inc.php'); ?>
    <!-- ./MODS TOP -->
    <?php } ?>
    
    <?php if (($section == "admin") && (($logged_in) && ($_SESSION['userLevel'] <= 1))) { ?>
    <!-- Admin Pages (Fluid Layout) -->
    <div class="container-fluid">
       	<?php if ($go == "default") { ?>
        <!-- Admin Dashboard - Has sidebar -->
        <div class="row">
        	<div class="col col-lg-9 col-md-8 col-sm-12 col-xs-12">
            <div class="page-header">
        		<h1><?php echo $header_output; ?></h1>
            </div>    
            <?php include (ADMIN.'default.admin.php'); ?> 
            </div><!-- ./left column -->
            <div class="sidebar col col-lg-3 col-md-4 col-sm-12 col-xs-12">
            	<?php include (ADMIN.'sidebar.admin.php'); ?>       
            </div><!-- ./sidebar -->
        </div><!-- ./row -->
        <?php } else { ?>
      	<!-- Admin Page - full width of viewport -->
        	<div class="page-header">
        		<h1><?php echo $header_output; ?></h1>
        	</div>
            <?php 
			
			if ($go == "judging") 	    			include (ADMIN.'judging_locations.admin.php');
			if ($go == "judging_preferences") 	    include (ADMIN.'judging_preferences.admin.php');
			if ($go == "judging_tables") 	    	include (ADMIN.'judging_tables.admin.php');
			if ($go == "judging_flights") 	    	include (ADMIN.'judging_flights.admin.php');
			if ($go == "judging_scores") 	    	include (ADMIN.'judging_scores.admin.php');
			if ($go == "judging_scores_bos")    		include (ADMIN.'judging_scores_bos.admin.php');
			if ($go == "participants") 				include (ADMIN.'participants.admin.php');
			if ($go == "entries") 					include (ADMIN.'entries.admin.php');
			if ($go == "contacts") 	    			include (ADMIN.'contacts.admin.php');
			if ($go == "dropoff") 	    			include (ADMIN.'dropoff.admin.php');
			if ($go == "checkin") 	    			include (ADMIN.'barcode_check-in.admin.php');
			if ($go == "count_by_style")				include (ADMIN.'entries_by_style.admin.php');
			if ($go == "count_by_substyle")			include (ADMIN.'entries_by_substyle.admin.php');
			if ($action == "register")				include (SECTIONS.'register.sec.php');
			if ($go == "upload_scoresheets")			include (ADMIN.'upload_scoresheets.admin.php');
			
				if ($_SESSION['userLevel'] == "0") {
					if ($go == "styles") 	    		include (ADMIN.'styles.admin.php');
					if ($go == "archive") 	    		include (ADMIN.'archive.admin.php');
					if ($go == "make_admin") 			include (ADMIN.'make_admin.admin.php');
					if ($go == "contest_info") 			include (ADMIN.'competition_info.admin.php');
					if ($go == "preferences") 			include (ADMIN.'site_preferences.admin.php');
					if ($go == "sponsors") 	   			include (ADMIN.'sponsors.admin.php');
					if ($go == "style_types")    		include (ADMIN.'style_types.admin.php');
					if ($go == "special_best") 	    	include (ADMIN.'special_best.admin.php');
					if ($go == "special_best_data") 		include (ADMIN.'special_best_data.admin.php');
					if ($go == "mods") 	    			include (ADMIN.'mods.admin.php');
					if ($go == "upload")					include (ADMIN.'upload.admin.php');
					if ($go == "change_user_password") 	include (ADMIN.'change_user_password.admin.php');
				}
			
			} ?>
    </div><!-- ./container-fluid -->    
    <!-- ./Admin Pages -->
    <?php } else { ?>
    <!-- Public Pages (Fixed Layout with Sidebar) -->
    <div class="container"> 
    	<div class="row">
    		<div class="col col-lg-9 col-md-8 col-sm-12 col-xs-12">
            <div class="page-header">
        		<h1><?php echo $header_output; ?></h1>
        	</div>             
        	<?php 
				
				if ($section == "default") 		include (SECTIONS.'default.sec.php');
				if ($section == "entry") 		include (SECTIONS.'entry_info.sec.php');
				if ($section == "contact") 		include (SECTIONS.'contact.sec.php');
				if ($section == "volunteers")	include (SECTIONS.'volunteers.sec.php');
				if ($section == "sponsors") 		include (SECTIONS.'sponsors.sec.php');
				if ($section == "register")		include (SECTIONS.'register.sec.php');
				if ($section == "brewer") 		include (SECTIONS.'brewer.sec.php');
				if ($section == "login")			include (SECTIONS.'login.sec.php');
				
				if ($logged_in) {
					if ($section == "list") 		include (SECTIONS.'list.sec.php');
					if ($section == "brew") 			include (SECTIONS.'brew.sec.php');
					if ($section == "pay") 		include (SECTIONS.'pay.sec.php');
					if ($section == "user") 		include (SECTIONS.'user.sec.php');
					if ($section == "beerxml") 		include (SECTIONS.'beerxml.sec.php');
				}
			?>
            </div><!-- ./left column -->
            <div class="sidebar col col-lg-3 col-md-4 col-sm-12 col-xs-12">
            	<?php include (SECTIONS.'sidebar.sec.php'); ?>         
            </div><!-- ./sidebar -->
        </div><!-- ./row -->
    	<!-- ./Public Pages -->
        <?php } ?>
    </div><!-- ./container -->
    <!-- ./Public Pages -->
    
    <?php if ($_SESSION['prefsUseMods'] == "Y") { ?>
    <!-- Mods Bottom -->
    <?php include(INCLUDES.'mods_bottom.inc.php'); ?>
    <!-- ./Mods Bottom -->
    <?php } ?>
    
    <!-- Footer -->
    <footer class="footer hidden-xs hidden-sm hidden-md">
    	<div class="navbar <?php echo $nav_container; ?> navbar-fixed-bottom bcoem-footer">
            <div class="<?php echo $container_main; ?> text-center">
                <p class="navbar-text col-md-12 col-sm-12 col-xs-12 text-muted small"><?php include (SECTIONS.'footer.sec.php'); ?></p>
            </div>
    	</div>
    </footer><!-- ./footer --> 
	<!-- ./ Footer -->
    
</body>
</html>