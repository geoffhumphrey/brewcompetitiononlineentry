    
    <!-- Load jQuery / http://jquery.com/ -->
    <script src="<?php echo $base_url; ?>libraries/jquery.min.js"></script>
    
    <!-- Load Bootstrap / http://www.getbootsrap.com -->
    <link rel="stylesheet" type="text/css" href="<?php echo $base_url; ?>libraries/bootstrap/css/bootstrap.min.css" />
    <script src="<?php echo $base_url; ?>libraries/bootstrap/js/bootstrap.min.js"></script>
        
    <!--[if lt IE 9]>
      <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
      <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
    
    <?php if (in_array($section,$datatables_load)) { ?>
    <!-- Load DataTables / https://www.datatables.net -->
    <link rel="stylesheet" type="text/css" href="<?php echo $base_url; ?>libraries/datatables/dataTables.bootstrap.min.css" />
    <link rel="stylesheet" type="text/css" href="<?php echo $base_url; ?>libraries/datatables/dataTables.fontAwesome.css" />
    <script type="text/javascript" src="<?php echo $base_url; ?>libraries/datatables/jquery.dataTables.min.js"></script>
    <script type="text/javascript" src="<?php echo $base_url; ?>libraries/datatables/dataTables.bootstrap.min.js"></script>
    <?php } ?>
    
    <!-- Load Fancybox / http://www.fancyapps.com -->
    <link rel="stylesheet" type="text/css" href="<?php echo $base_url; ?>libraries/fancybox/source/jquery.fancybox.css" media="screen" />
    <script type="text/javascript" src="<?php echo $base_url; ?>libraries/fancybox/jquery.easing.1.3.min.js"></script>
    <script type="text/javascript" src="<?php echo $base_url; ?>libraries/fancybox/lib/jquery.mousewheel-3.0.6.pack.js"></script>
    <script type="text/javascript" src="<?php echo $base_url; ?>libraries/fancybox/source/jquery.fancybox.pack.js"></script>
    
    <?php if (($section == "admin") && (in_array($go,$datetime_load)) || ($section == "brew")) { ?>
    
    <!-- Load Bootstrap DateTime Picker / http://eonasdan.github.io/bootstrap-datetimepicker/ -->
    <link rel="stylesheet" type="text/css" href="<?php echo $base_url; ?>libraries/date-time-picker/css/bootstrap-datetimepicker.min.css" />
    <script type="text/javascript" src="<?php echo $base_url; ?>libraries/date-time-picker/js/moment-with-locales.js"></script>
    <script type="text/javascript" src="<?php echo $base_url; ?>libraries/date-time-picker/js/bootstrap-datetimepicker.min.js"></script>
    <script src="<?php echo $base_url;?>js_includes/date-time.min.js"></script>
    <?php } ?>
    
    <?php if (($section == "admin") && (in_array($go,$tinymce_load))) { ?>
    <!-- Load TinyMCE / https://www.tinymce.com/ -->
    <script src="<?php echo $base_url; ?>libraries/tinymce/js/tinymce/tinymce.min.js"></script>
	<script src="<?php echo $base_url;?>js_includes/tinymce-init.min.js"></script>    
    <?php } ?>
    
    <?php if ((($logged_in) && ($_SESSION['userLevel'] <= 1)) || (($logged_in) && ($section == "beerxml"))) { ?>
    
    <!-- Load Jasny Off-Canvas Menu for Admin / http://www.jasny.net/bootstrap -->
    <link rel="stylesheet" href="<?php echo $base_url; ?>libraries/jasny-bootstrap/css/jasny-bootstrap.min.css">
    <script src="<?php echo $base_url; ?>libraries/jasny-bootstrap/js/jasny-bootstrap.min.js"></script>
    
	<?php if (($section == "admin") && (($go == "upload") || ($go == "upload_scoresheets"))) { ?>
    <!-- Load DropZone / http://www.dropzonejs.com -->
    <link rel="stylesheet" type="text/css" href="<?php echo $base_url; ?>libraries/dropzone/dropzone.min.css" />
    <script src="<?php echo $base_url; ?>libraries/dropzone/dropzone.min.js"></script>
    <script src="<?php echo $base_url;?>js_includes/dz.min.js"></script>
    <?php } ?>
    
    <?php } ?>
    
    <!-- Load Bootstrap Form Validator / http://1000hz.github.io/bootstrap-validator -->
    <script src="<?php echo $base_url; ?>libraries/validator/validator.min.js"></script>
    
    <!-- Load Bootstrap-Select / http://silviomoreto.github.io/bootstrap-select -->
    <link rel="stylesheet" href="<?php echo $base_url; ?>libraries/bootstrap-select/bootstrap-select.min.css">	
    <script src="<?php echo $base_url; ?>libraries/bootstrap-select/bootstrap-select.min.js"></script>
    
    <!-- Load Font Awesome / https://fortawesome.github.io/Font-Awesome -->
    <link rel="stylesheet" href="<?php echo $base_url; ?>libraries/font-awesome/css/font-awesome.min.css">
    
	<?php if (($section == "register") || (($section == "login") && ($go == "password") && ($action == "reset-password")) || (($section == "user") && ($go == "account") && ($action == "password")) || (($section == "admin") && ($go == "change_user_password") && ($action == "edit")) || (($section == "admin") && ($action == "register"))) { ?>
	<!-- Load jQuery Password Strength Meter for Twitter Bootstrap / https://github.com/ablanco/jquery.pwstrength.bootstrap -->
	<script type="text/javascript" src="<?php echo $base_url; ?>libraries/zxcvbn/dist/zxcvbn.js"></script>
	<script type="text/javascript" src="<?php echo $base_url; ?>libraries/pwstrength-bootstrap/dist/pwstrength-bootstrap.min.js"></script>
	<?php } ?>