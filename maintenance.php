<?php
/**
 * Module:      maintenance.php
 * Description: This page displays if the site is in maintenance mode.
 *
 */

// Kill any session that was started.
// Just in case admin puts into maint mode while users are logged in.
if (session_status() !== PHP_SESSION_NONE) {
    session_unset();
    session_destroy();
}

require_once ('paths.php');
require_once (CONFIG.'bootstrap.php');
if (!MAINT) {
    $redirect = prep_redirect_link($base_url);
    $redirect_go_to = sprintf("Location: %s", $redirect);
    header($redirect_go_to);
    exit();
}

$competition_logo = "";
if ((isset($_SESSION['contestLogo'])) && (!empty($_SESSION['contestLogo'])) && (file_exists(USER_IMAGES.$_SESSION['contestLogo']))) $competition_logo = "<img src=\"".$base_url."user_images/".$_SESSION['contestLogo']."\" class=\"bcoem-comp-logo img-responsive hidden-print center-block\" alt=\"Competition Logo\" title=\"Competition Logo\" />";
?>

<!DOCTYPE html>
<html lang="en">
  	<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?php echo $label_maintenance." &ndash; ".ucwords($maintenance_text_001); ?></title>

    <?php
    if (CDN) include (INCLUDES.'load_cdn_libraries.inc.php');
    else include (INCLUDES.'load_local_libraries.inc.php');
    ?>

    <!-- Load BCOE&M Custom CSS - Contains Bootstrap overrides and custom classes common to all BCOE&M themes -->
    <link rel="stylesheet" type="text/css" href="<?php echo $css_url."common.min.css"; ?>" />
    <link rel="stylesheet" type="text/css" href="<?php echo $theme; ?>" />
    
  </head>
<body>
	<!-- MAIN NAV -->
	<div class="container hidden-print">
        <!-- Fixed navbar -->
        <div class="navbar navbar-default navbar-fixed-top">
            <div class="container">
                <div class="navbar-header">
                    <p class="navbar-text"><i class="fa fa-lg fa-wrench"></i> <i class="fa fa-lg fa-cog"></i></p>
                </div>
                <div class="navbar-right">
                <?php if ($logged_in) { ?>
                    <p class="navbar-text"><a href="<?php echo $base_url; ?>index.php?section=admin"><?php echo $label_admin_dashboard; ?></a></p>
                <?php } else { ?>
                    <button type="button" class="btn btn-default navbar-btn" data-toggle="modal" data-target="#loginModal"><?php echo $label_log_in; ?></button></p>
                <?php } ?>
                </div>
            </div>
        </div>
    </div>
    <!-- ./MAIN NAV -->
    <!-- Container -->
    <div class="jumbotron">
    	<div class="container">
            <div class="row">
                <div class="col col-xs-12 col-sm-7 col-md-9">
                    <h1><i class="fa fa-lg fa-cog fa-spin"></i> <?php echo $label_maintenance; ?></h1>
                    <p class="lead"><?php echo $maintenance_text_000; ?></p>
                </div>
                <div class="col col-sm-5 col-md-3 hidden-xs">
                    <?php echo $competition_logo; ?>
                </div>
            </div>
        </div>
    </div>
    <div class="container">
    	<p><?php echo $maintenance_text_001; ?></p>
    </div>
    <!-- ./Container -->
    <!-- Footer -->
    <footer class="footer">
    	<nav class="navbar navbar-default navbar-fixed-bottom">
            <div class="container text-center">
                <p class="navbar-text col-md-12 col-sm-12 col-xs-12 text-muted small"><?php include (SECTIONS.'footer.sec.php'); ?></p>
            </div>
    	</nav>
    </footer>
	<!-- ./ Footer -->

    <!-- Modal -->
    <div class="modal fade" id="loginModal" tabindex="-1" role="dialog" aria-labelledby="loginModalLabel">
        <div class="modal-dialog modal-sm" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title" id="loginModalLabel"><?php echo $label_log_in; ?></h4>
                    <p><?php echo $maintenance_text_002; ?></p>
                </div>
                <div class="modal-body">
                    <form class="form-horizontal" data-toggle="validator" role="form" action="<?php echo $base_url; ?>includes/process.inc.php?section=login&action=login" method="POST" name="form1" id="form1">
                        <div class="form-group">
                            <div class="col-md-12">
                                <div class="input-group has-warning">
                                    <span class="input-group-addon" id="login-addon1"><span class="fa fa-envelope"></span></span>
                                    <!-- Input Here -->
                                    <input id="loginUsername" class="form-control" name="loginUsername" type="email" required placeholder="<?php echo $label_email; ?>" data-error="<?php echo $login_text_018; ?>">
                                    <span class="input-group-addon" id="login-addon2" data-tooltip="true" title="<?php echo $form_required_fields_02; ?>"><span class="fa fa-star"></span></span>
                                </div>
                                <span class="help-block with-errors"></span>
                            </div>
                        </div><!-- Form Group -->
                        <div class="form-group">
                            <div class="col-md-12">
                                <div class="input-group has-warning">
                                    <span class="input-group-addon" id="login-addon3"><span class="fa fa-key"></span></span>
                                    <!-- Input Here -->
                                    <input class="form-control" name="loginPassword" type="password" required placeholder="<?php echo $label_password; ?>" data-error="<?php echo $login_text_019; ?>">
                                    <span class="input-group-addon" id="login-addon4" data-tooltip="true" title="<?php echo $form_required_fields_02; ?>"><span class="fa fa-star"></span></span>
                                </div>
                                <span class="help-block with-errors"></span>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="col-md-12">
                                <!-- Input Here -->
                                <button name="submit" type="submit" class="btn btn-primary btn-block" ><?php echo $label_log_in; ?> <span class="fa fa-sign-in" aria-hidden="true"></span> </button>
                            </div>
                        </div><!-- Form Group -->
                    </form>
                </div>
            </div>
        </div>
    </div>
</body>
</html>