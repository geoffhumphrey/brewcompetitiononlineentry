    
    <!-- Load jQuery / http://jquery.com/ -->
    <script src="<?php echo $base_url; ?>libraries/public/jquery/jquery.min.js"></script>
    
    <!-- Load Bootstrap / http://www.getbootsrap.com -->
    <link rel="stylesheet" type="text/css" href="<?php echo $base_url; ?>libraries/admin/bootstrap/css/bootstrap.min.css" />
    <script src="<?php echo $base_url; ?>libraries/admin/bootstrap/js/bootstrap.min.js"></script>
        
    <!--[if lt IE 9]>
      <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
      <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
    
    <?php if (in_array($section,$datatables_load)) { ?>
    <!-- Load DataTables / https://www.datatables.net -->
    <link rel="stylesheet" type="text/css" href="<?php echo $base_url; ?>libraries/admin/datatables/dataTables.bootstrap.min.css" />
    <link rel="stylesheet" type="text/css" href="<?php echo $base_url; ?>libraries/admin/datatables/dataTables.fontAwesome.css" />
    <link rel="stylesheet" type="text/css" href="<?php echo $base_url; ?>libraries/public/font-awesome/4.5.0/css/font-awesome.min.css">
    <script type="text/javascript" src="<?php echo $base_url; ?>libraries/admin/datatables/jquery.dataTables.min.js"></script>
    <script type="text/javascript" src="<?php echo $base_url; ?>libraries/admin/datatables/dataTables.bootstrap.min.js"></script>
    <?php } ?>
    
    <!-- Load Fancybox / http://www.fancyapps.com -->
    <script type="text/javascript" src="<?php echo $base_url; ?>libraries/public/fancybox/jquery.fancybox.min.js"></script>
    <link rel="stylesheet" type="text/css" href="<?php echo $base_url; ?>libraries/public/fancybox/jquery.fancybox.min.css" />
    <script type="text/javascript" src="<?php echo $base_url; ?>libraries/public/jquery-easing/jquery.easing.min.js"></script>
    <script type="text/javascript" src="<?php echo $base_url; ?>libraries/public/jquery-mousewheel/jquery.mousewheel.min.js"></script> 

    <!-- Load Moment -->
    <script type="text/javascript" src="<?php echo $base_url; ?>libraries/public/moment/moment-with-locales.min.js"></script>
    
    <!-- Load Bootstrap DateTime Picker / http://eonasdan.github.io/bootstrap-datetimepicker/ -->
    <link rel="stylesheet" type="text/css" href="<?php echo $base_url; ?>libraries/admin/bootstrap-datetimepicker/bootstrap-datetimepicker.min.css" />
    <script type="text/javascript" src="<?php echo $base_url; ?>libraries/admin/bootstrap-datetimepicker/bootstrap-datetimepicker.min.js"></script>

    <?php if ((($section == "admin") || (strpos($section, 'step') !== FALSE)) && (in_array($go,$tinymce_load))) { ?>
    <!-- Load TinyMCE / https://www.tinymce.com/ -->
    <?php if (ENABLE_MARKDOWN) { ?>
    <link rel="stylesheet" type="text/css" href="<?php echo $base_url; ?>libraries/admin/bootstrap-markdown-editor/bootstrap-markdown-editor.css">
    <script src="<?php echo $base_url; ?>libraries/admin/bootstrap-markdown-editor/bootstrap-markdown-editor.js"></script>
    <script src="<?php echo $base_url; ?>libraries/admin/ace/ace.js"></script>
    <script src="<?php echo $base_url; ?>libraries/admin/marked/marked.min.js"></script>
    <?php } else { ?>
    <script src="<?php echo $base_url; ?>libraries/admin/tinymce/tinymce.min.js"></script>
    <script src="<?php echo $js_url; ?>tinymce-init.min.js"></script>
    <?php } ?>
    <?php } ?>
    
    <?php if (($logged_in) && ($_SESSION['userLevel'] <= 1)) { ?>
    
    <!-- Load Jasny Off-Canvas Menu for Admin / http://www.jasny.net/bootstrap -->
    <link rel="stylesheet" type="text/css" href="<?php echo $base_url; ?>libraries/admin/jasny-bootstrap/jasny-bootstrap.min.css">
    <script src="<?php echo $base_url; ?>libraries/admin/jasny-bootstrap/jasny-bootstrap.min.js"></script>
    
	<?php if ((($section == "admin") || (strpos($section, 'step') !== FALSE)) && (($go == "upload") || ($go == "upload_scoresheets"))) { ?>
    <!-- Load DropZone / http://www.dropzonejs.com -->
    <link rel="stylesheet" type="text/css" href="<?php echo $base_url; ?>libraries/admin/dropzone/dropzone.min.css" />
    <script src="<?php echo $base_url; ?>libraries/admin/dropzone/dropzone.min.js"></script>
    <script src="<?php echo $js_url; ?>dz.min.js"></script>
    <?php } ?>
    
    <?php } ?>
    
    <!-- Load Bootstrap Form Validator / http://1000hz.github.io/bootstrap-validator -->
    <script src="<?php echo $base_url; ?>libraries/admin/1000hz-bootstrap-validator/validator.min.js"></script>
    
    <!-- Load Bootstrap-Select / http://silviomoreto.github.io/bootstrap-select -->
    <link rel="stylesheet" type="text/css" href="<?php echo $base_url; ?>libraries/admin/bootstrap-select/bootstrap-select.min.css">	
    <script src="<?php echo $base_url; ?>libraries/admin/bootstrap-select/bootstrap-select.min.js"></script>

    <!-- Load Font Awesome / https://fortawesome.github.io/Font-Awesome -->
    <link rel="stylesheet" type="text/css" href="<?php echo $base_url; ?>libraries/admin/font-awesome/5.15.2/css/all.min.css" />
    <link rel="stylesheet" type="text/css" href="<?php echo $base_url; ?>libraries/admin/font-awesome/5.15.2/css/v4-shims.min.css" />
    
	<?php if (($section == "register") || ($section == "step1") || (($section == "login") && ($go == "password") && ($action == "reset-password")) || (($section == "user") && ($go == "account") && ($action == "password")) || (($section == "admin") && ($go == "change_user_password") && ($action == "edit")) || (($section == "admin") && ($action == "register"))) { ?>
	<!-- Load jQuery Password Strength Meter for Twitter Bootstrap / https://github.com/ablanco/jquery.pwstrength.bootstrap -->
	<script type="text/javascript" src="<?php echo $base_url; ?>libraries/admin/zxcvbn/dist/zxcvbn.js"></script>
	<script type="text/javascript" src="<?php echo $base_url; ?>libraries/admin/pwstrength-bootstrap/dist/pwstrength-bootstrap.min.js"></script>
	<?php } ?>