    <!-- Load jQuery / http://jquery.com/ -->
    <script type="text/javascript" src="<?php echo $base_url; ?>libraries/public/jquery/jquery.min.js"></script>
    
    <!-- Load Fancybox / http://www.fancyapps.com -->
    <script type="text/javascript" src="<?php echo $base_url; ?>libraries/public/fancybox/jquery.fancybox.min.js"></script>
    <link rel="stylesheet" href="<?php echo $base_url; ?>libraries/public/fancybox/jquery.fancybox.min.css" />
    <script type="text/javascript" src="<?php echo $base_url; ?>libraries/public/jquery-easing/jquery.easing.min.js"></script>
    <script type="text/javascript" src="<?php echo $base_url; ?>libraries/public/jquery-mousewheel/jquery.mousewheel.min.js"></script> 

    <?php if (in_array($section,$datatables_load)) { ?>

    <!-- Load DataTables / https://www.datatables.net -->
    <link rel="stylesheet" type="text/css" href="<?php echo $base_url; ?>libraries/public/datatables/dataTables.bootstrap5.min.css" />
    <script type="text/javascript" src="<?php echo $base_url; ?>libraries/public/datatables/dataTables.min.js"></script>
    <script type="text/javascript" src="<?php echo $base_url; ?>libraries/public/datatables/dataTables.bootstrap5.min.js"></script>
    <link rel="stylesheet" type="text/css" href="<?php echo $base_url; ?>libraries/public/datatables/dataTables.fontAwesome.css" />
    
    <!-- Load Font Awesome 4.5 for use with DataTables -->
    <link rel="stylesheet" href="<?php echo $base_url; ?>libraries/public/font-awesome/4.5.0/css/font-awesome.min.css">
    
    <?php } ?>

    <!-- Load Bootstrap 5.X / https://getbootstrap.com/ -->
    <!-- 
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous"> 
    -->

    <!-- Load Ninja Bootstrap (REPLACES and EXTENDS Bootstrap's CSS) / https://bootstrap.ninja/ninjabootstrap/ -->
    <link href="<?php echo $base_url; ?>libraries/public/ninjabootstrap/bootstrap.min.css" rel="stylesheet">

    <!-- Load Animate Styles / https://animate.style -->
    <link rel="stylesheet" href="<?php echo $base_url; ?>libraries/public/animate/animate.min.css" />
    
    <!-- Load Font Awesome / https://fortawesome.github.io/Font-Awesome -->
    <link rel="stylesheet" href="<?php echo $base_url; ?>libraries/public/font-awesome/6.7.2/css/all.min.css" />
    <script type="text/javascript">
        window.FontAwesomeConfig = { autoReplaceSvg: false }
    </script>
    <script src="<?php echo $base_url; ?>libraries/public/font-awesome/6.7.2/js/all.min.js"></script>
    <link rel="stylesheet" href="<?php echo $base_url; ?>libraries/public/font-awesome/6.7.2/css/v4-shims.min.css" />

    <!-- Load Tom Select / https://github.com/orchidjs/tom-select / https://tom-select.js.org/ -->
    <link rel="stylesheet" href="<?php echo $base_url; ?>libraries/public/tom-select/tom-select.bootstrap5.min.css" />
    <script src="<?php echo $base_url; ?>libraries/public/tom-select/tom-select.complete.min.js"></script>

    <!-- Load Moment -->
    <script type="text/javascript" src="<?php echo $base_url; ?>libraries/public/moment/moment-with-locales.min.js"></script>

    <?php if (($section == "register") || ($section == "login") || ($section == "step1") || (($section == "user") && ($go == "account") && ($action == "password")) || (($section == "admin") && ($go == "change_user_password") && ($action == "edit")) || (($section == "admin") && ($action == "register"))) { ?>
        
    <!-- Load Password Strength Indicator / https://github.com/ablanco/jquery.pwstrength.bootstrap -->
    <script type="text/javascript" src="<?php echo $base_url; ?>libraries/public/zxcvbn/zxcvbn.js"></script>
    <script type="text/javascript" src="<?php echo $base_url; ?>libraries/public/pwstrength-bootstrap/pwstrength-bootstrap.min.js"></script>

    <?php } ?>