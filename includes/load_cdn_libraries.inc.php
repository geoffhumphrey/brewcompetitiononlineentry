  	<!-- Load jQuery / http://jquery.com/ -->
  	<script src="https://ajax.googleapis.com/ajax/libs/jquery/2.2.4/jquery.min.js"></script>

    <!-- Load Bootstrap / http://www.getbootsrap.com -->
    <link rel="stylesheet" type="text/css" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" />
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>

    <!--[if lt IE 9]>
      <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
      <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->

<?php if (in_array($section,$datatables_load)) { ?>
    <!-- Load DataTables / https://www.datatables.net -->
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.12/css/dataTables.bootstrap.min.css" />
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/plug-ins/1.10.12/integration/font-awesome/dataTables.fontAwesome.css" />
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.5.0/css/font-awesome.min.css">
    <script type="text/javascript" src="https://cdn.datatables.net/1.10.12/js/jquery.dataTables.min.js"></script>
    <script type="text/javascript" src="https://cdn.datatables.net/1.10.12/js/dataTables.bootstrap.min.js"></script>
<?php } ?>

    <!-- Load Fancybox / http://www.fancyapps.com -->
    <link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/fancybox/2.1.5/jquery.fancybox.min.css" media="screen" />
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jquery-easing/1.3/jquery.easing.min.js"></script>
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jquery-mousewheel/3.1.13/jquery.mousewheel.min.js"></script>
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/fancybox/2.1.5/jquery.fancybox.pack.js"></script>

<?php if ((($section == "admin") || (strpos($section, 'step') !== FALSE)) && (in_array($go,$datetime_load)) || ($section == "brew")) { ?>
    <!-- Load Bootstrap DateTime Picker / http://eonasdan.github.io/bootstrap-datetimepicker/ -->
    <link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datetimepicker/4.17.37/css/bootstrap-datetimepicker.min.css" />
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.1/moment-with-locales.min.js"></script>
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datetimepicker/4.17.37/js/bootstrap-datetimepicker.min.js"></script>
    <script src="<?php echo $base_url;?>js_includes/date-time.min.js"></script>
<?php } ?>

<?php if ((($section == "admin") || (strpos($section, 'step') !== FALSE)) && (in_array($go,$tinymce_load))) { ?>
    <!-- Load TinyMCE / https://www.tinymce.com/ -->
<?php if (ENABLE_MARKDOWN) { ?>
    <link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/bootstrap-markdown-editor/2.0.2/css/bootstrap-markdown-editor.css">
    <script src="//cdnjs.cloudflare.com/ajax/libs/ace/1.1.3/ace.js"></script>
    <script src="//cdnjs.cloudflare.com/ajax/libs/marked/0.3.2/marked.min.js"></script>
    <script src="//cdnjs.cloudflare.com/ajax/libs/bootstrap-markdown-editor/2.0.2/js/bootstrap-markdown-editor.js"></script>
  <?php } else { ?>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/tinymce/4.9.11/tinymce.min.js"></script>
    <!-- Future Release - TinyMCE 5 (needs testing and config options) -->
    <!-- <script src="https://cdn.tiny.cloud/1/no-api-key/tinymce/5/tinymce.min.js" referrerpolicy="origin"></script> -->
    <script src="<?php echo $base_url;?>js_includes/tinymce-init.min.js"></script>
  <?php } ?>
<?php } ?>

<?php if (($logged_in) && ((isset($_SESSION['userLevel'])) && ($_SESSION['userLevel'] <= 1)) && (strpos($section, 'step') === FALSE)) { ?>
    <!-- Load Jasny Off-Canvas Menu for Admin / http://www.jasny.net/bootstrap -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jasny-bootstrap/3.1.3/css/jasny-bootstrap.min.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jasny-bootstrap/3.1.3/js/jasny-bootstrap.min.js"></script>
  <?php if ((($section == "admin") || (strpos($section, 'step') !== FALSE)) && (($go == "upload") || ($go == "upload_scoresheets"))) { ?>
    <!-- Load DropZone / http://www.dropzonejs.com -->
    <link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/dropzone/4.2.0/min/dropzone.min.css" />
    <script src="https://cdnjs.cloudflare.com/ajax/libs/dropzone/4.2.0/min/dropzone.min.js"></script>
    <script src="<?php echo $base_url;?>js_includes/dz.min.js"></script>
  <?php } ?>
<?php } ?>

  	<!-- Load Bootstrap Form Validator / http://1000hz.github.io/bootstrap-validator -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/1000hz-bootstrap-validator/0.9.0/validator.min.js"></script>

    <!-- Load Bootstrap-Select / https://developer.snapappointments.com/bootstrap-select -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.12.0/css/bootstrap-select.min.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.12.0/js/bootstrap-select.min.js"></script>

    <!-- Load Font Awesome / https://fortawesome.github.io/Font-Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.2/css/all.min.css" integrity="sha512-HK5fgLBL+xu6dm/Ii3z4xhlSUyZgTT9tuc/hSrtw6uzJOvgRr2a9jyxxT1ely+B+xFAmJKVSTbpM/CuL7qxO8w==" crossorigin="anonymous" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.2/css/v4-shims.min.css" integrity="sha512-NfhLGuxy6G12XHj7/bvm0RC3jmR25RdpImn8P19aIMmN5pndO0fvIg78ihN2WIJtVRs+AYVrnYF4AipVikGPLg==" crossorigin="anonymous" />

<?php if (($section == "register") || ($section == "step1") || (($section == "login") && ($go == "password") && ($action == "reset-password")) || (($section == "user") && ($go == "account") && ($action == "password")) || (($section == "admin") && ($go == "change_user_password") && ($action == "edit")) || (($section == "admin") && ($action == "register"))) { ?>
    <!-- Load jQuery Password Strength Meter for Twitter Bootstrap / https://github.com/ablanco/jquery.pwstrength.bootstrap -->
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/zxcvbn/4.4.2/zxcvbn.js"></script>
  	<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/pwstrength-bootstrap/2.1.0/pwstrength-bootstrap.min.js"></script>
<?php } ?>