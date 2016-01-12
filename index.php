<?php 
/**
 * Module:      index.php 
 * Description: This module is the delivery vehicle for all modules.
 * 
 */

require('paths.php');
require(CONFIG.'bootstrap.php');
include(DB.'mods.db.php');

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
		$entries_unconfirmed = ($totalRows_entry_count - $totalRows_log_confirmed);
	}
}

if (TESTING) {
	$mtime = microtime(); 
	$mtime = explode(" ",$mtime); 
	$mtime = $mtime[1] + $mtime[0]; 
	$starttime = $mtime; 
}

if ($section == "admin") {
	$container_main = "container-fluid";
	$nav_container = "navbar-inverse";
}
	
else { 
	$container_main = "container";
	$nav_container = "navbar-default";
}

// Load libraries only when needed for performance
$tinymce_load = array("contest_info","special_best","styles");
$datetime_load = array("contest_info","judging","testing");
if ((judging_date_return() == 0) && ($registration_open == "2")) $datatables_load = array("admin","list","default");
else $datatables_load = array("admin","list");

?>
<!DOCTYPE html>
<html lang="en"><head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?php echo $_SESSION['contestName']; ?> - Brew Competition Online Entry &amp; Management</title>
    
	<!-- Load jQuery / http://jquery.com/ -->
	<script src="https://code.jquery.com/jquery-2.1.4.min.js"></script>
	
    <!-- Load Bootstrap / http://www.getbootsrap.com -->
    <link rel="stylesheet" type="text/css" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/css/bootstrap.min.css" />
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/js/bootstrap.min.js"></script>
    <!--[if lt IE 9]>
      <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
      <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
   
    <?php if (in_array($section,$datatables_load)) { ?>
    <!-- Load DataTables / https://www.datatables.net -->
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.10/css/dataTables.bootstrap.min.css" />
	<link rel="stylesheet" type="text/css" href="http://cdn.datatables.net/plug-ins/1.10.10/integration/font-awesome/dataTables.fontAwesome.css" />
	<script type="text/javascript" src="https://cdn.datatables.net/1.10.10/js/jquery.dataTables.min.js"></script>
    <script type="text/javascript" src="https://cdn.datatables.net/1.10.10/js/dataTables.bootstrap.min.js"></script>
	<?php } ?>
    
    <!-- Load Fancybox / http://www.fancyapps.com -->
    <link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/fancybox/2.1.5/jquery.fancybox.min.css" media="screen" />
	<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jquery-easing/1.3/jquery.easing.min.js"></script>
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jquery-mousewheel/3.1.13/jquery.mousewheel.min.js"></script>
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/fancybox/2.1.5/jquery.fancybox.pack.js"></script>
    
    <?php if (($section == "admin") && (in_array($go,$datetime_load))) { ?>
    <!-- Load Bootstrap DateTime Picker / http://eonasdan.github.io/bootstrap-datetimepicker/ -->
    <link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datetimepicker/4.17.37/css/bootstrap-datetimepicker.min.css" />
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.11.1/moment-with-locales.min.js"></script>
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datetimepicker/4.17.37/js/bootstrap-datetimepicker.min.js"></script>
    <script type="text/javascript" src="<?php echo $base_url; ?>js_includes/datetime.min.js"></script>
    <?php } ?>
	
	<?php if (($section == "admin") && (in_array($go,$tinymce_load))) { ?>
    <!-- Load TinyMCE -->
	<script src="//cdn.tinymce.com/4/tinymce.min.js"></script>
	<?php } ?>
	
	<?php if (($logged_in) && ($_SESSION['userLevel'] <= 1)) { ?>
    <!-- Load Off-Canvas Menu for Admin -->
    <link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/jasny-bootstrap/3.1.3/css/jasny-bootstrap.min.css">
    <script src="//cdnjs.cloudflare.com/ajax/libs/jasny-bootstrap/3.1.3/js/jasny-bootstrap.min.js"></script>
    <?php } ?>
	
	<!-- Load Bootstrap Form Validator / http://1000hz.github.io/bootstrap-validator -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/1000hz-bootstrap-validator/0.9.0/validator.min.js"></script>
    
    <!-- Load Bootstrap-Select / http://silviomoreto.github.io/bootstrap-select -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.9.3/css/bootstrap-select.min.css">	
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.9.3/js/bootstrap-select.min.js"></script>
    
    <!-- Load Font Awesome / https://fortawesome.github.io/Font-Awesome -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.5.0/css/font-awesome.min.css">
    
    <!-- Load BCOE&M Custom Theme CSS - Contains Bootstrap overrides and custom classes -->
    <link rel="stylesheet" type="text/css" href="<?php echo $theme; ?>" />
	
	<!-- Load BCOE&M Custom JS -->
    <script src="<?php echo $base_url; ?>js_includes/bcoem_custom.min.js"></script>
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
    <div class="container"> 
    	<?php include(INCLUDES.'mods_top.inc.php'); ?>
    </div>
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
			
				if ($_SESSION['userLevel'] == "0") {
					if ($go == "styles") 	    	include (ADMIN.'styles.admin.php');
					if ($go == "archive") 	    	include (ADMIN.'archive.admin.php');
					if ($go == "make_admin") 		include (ADMIN.'make_admin.admin.php');
					if ($go == "contest_info") 		include (ADMIN.'competition_info.admin.php');
					if ($go == "preferences") 		include (ADMIN.'site_preferences.admin.php');
					if ($go == "sponsors") 	   		include (ADMIN.'sponsors.admin.php');
					if ($go == "style_types")    	include (ADMIN.'style_types.admin.php');
					if ($go == "special_best") 	    include (ADMIN.'special_best.admin.php');
					if ($go == "special_best_data") 	include (ADMIN.'special_best_data.admin.php');
					if ($go == "mods") 	    		include (ADMIN.'mods.admin.php');
					if ($go == "upload")				include (ADMIN.'upload.admin.php');
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
				if ($section == "brew") 			include (SECTIONS.'brew.sec.php');
				if ($section == "brewer") 		include (SECTIONS.'brewer.sec.php');
				if ($section == "login")			include (SECTIONS.'login.sec.php');
				
				if ($logged_in) {
					if ($section == "list") 		include (SECTIONS.'list.sec.php');
					if ($section == "pay") 		include (SECTIONS.'pay.sec.php');
					if ($section == "user") 		include (SECTIONS.'user.sec.php');
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
    <div class="container"> 
    	<?php include(INCLUDES.'mods_bottom.inc.php'); ?>
    </div>
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