<?php

/**
 * Module:      index.php
 * Description: This module is the delivery vehicle for all modules.
 *
 * ------------------------------
 * Load Config Scripts
 * ------------------------------
 */

require_once ('paths.php');
require_once (CONFIG.'bootstrap.php');
if (!HOSTED) require_once (DB.'mods.db.php');

header('Expires: Sat, 26 Jul 1997 05:00:00 GMT');
//header('Last-Modified: ' . gmdate( 'D, d M Y H:i:s' ) . ' GMT');
header('Cache-Control: no-store, no-cache, must-revalidate, max-age=0');
//header('Cache-Control: post-check=0, pre-check=0', false);
header('Pragma: no-cache');

// Good for 3.0.0+
$account_pages = array("list","pay","brewer","user","brew","pay","evaluation");

if ((!$logged_in) && (in_array($section,$account_pages))) {
    
    $redirect = $base_url."index.php?msg=99";
    $redirect = prep_redirect_link($redirect);
    $redirect_go_to = sprintf("Location: %s", $redirect);
    header($redirect_go_to);
    exit();

}

if (MAINT) {

    if ((!$logged_in) || (($logged_in) && ($_SESSION['userLevel'] > 0))) {

        if ($section != "maintenance") {
            $redirect = $base_url."index.php?section=maintenance";
            $redirect = prep_redirect_link($redirect);
            $redirect_go_to = sprintf("Location: %s", $redirect);
            header($redirect_go_to);
            exit();
        }
        
    }

}

else {

    if ($section == "maintenance") {
        $redirect = $base_url;
        $redirect = prep_redirect_link($redirect);
        $redirect_go_to = sprintf("Location: %s", $redirect);
        header($redirect_go_to);
        exit();
    }

}

/**
 * ------------------------------
 * Admin Only Functions
 * ------------------------------
 */

if ($section == "admin") {

    // Redirect if non-admins try to access admin functions
    if (!$logged_in) {

        $redirect = $base_url."index.php?msg=0";
        $redirect = prep_redirect_link($redirect);
        $redirect_go_to = sprintf("Location: %s", $redirect);
        header($redirect_go_to);
        exit();

    }

    if (($logged_in) && ($_SESSION['userLevel'] > 1)) {
        
        $redirect = $base_url."index.php?msg=4";
        $redirect = prep_redirect_link($redirect);
        $redirect_go_to = sprintf("Location: %s", $redirect);
        header($redirect_go_to);
        exit();

    }

    require_once (LIB.'admin.lib.php');
    require_once (DB.'admin_common.db.php');
    require_once (DB.'judging_locations.db.php');
    require_once (DB.'stewarding.db.php');
    require_once (DB.'dropoff.db.php');
    require_once (DB.'contacts.db.php');
}

/**
 * ------------------------------
 * Other Top-Level Constants
 * ------------------------------
 */

require_once (INCLUDES.'constants_post_lang.inc.php');

// Hosted installations only
if (HOSTED) require_once (LIB.'hosted.lib.php');

// Pay modal is defined here to make sure it's top-level
// Otherwise, the modal does not render correctly.
$pay_modal = "";
$pay_modal .= "\n<!-- PayPal Confirmation Modal -->\n";
$pay_modal .= "<div class=\"modal modal-lg fade\" id=\"confirm-submit\" tabindex=\"-1\" role=\"dialog\" aria-hidden=\"true\">\n";
$pay_modal .= "\t<div class=\"modal-dialog\">\n";
$pay_modal .= "\t\t<div class=\"modal-content\">\n";
$pay_modal .= "\t\t\t<div class=\"modal-header\">\n";
if ((isset($_SESSION['prefsPaypalIPN'])) && ($_SESSION['prefsPaypalIPN'] == 1)) $pay_modal .= sprintf("\t\t\t\t<h4 class=\"modal-title\">%s</h4>\n",$pay_text_031);
else $pay_modal .= sprintf("\t\t\t\t<h4 class=\"modal-title\">%s</h4>\n",$pay_text_022);
$pay_modal .= "\t\t\t\t<button type=\"button\" class=\"btn-close\" data-bs-dismiss=\"modal\" aria-label=\"Close\"></button>\n";
$pay_modal .= "\t\t\t</div>\n";
if ((isset($_SESSION['prefsPaypalIPN'])) && ($_SESSION['prefsPaypalIPN'] == 1)) $pay_modal .= sprintf("\t\t\t<div class=\"modal-body\">\n\t\t\t\t<p>%s</p>\n",$pay_text_030);
else $pay_modal .= sprintf("\t\t\t<div class=\"modal-body\">\n\t\t\t\t<p>%s</p>\n",$pay_text_021);
$pay_modal .= "\t\t\t</div>\n";
$pay_modal .= "\t\t\t<div class=\"modal-footer\">\n";
$pay_modal .= sprintf("\t\t\t\t<button type=\"button\" class=\"btn btn-danger\" data-bs-dismiss=\"modal\">%s</button>\n",$label_cancel);
$pay_modal .= sprintf("\t\t\t\t<a href=\"#\" id=\"submit\" class=\"btn btn-primary\">%s</a>\n",$label_understand);
$pay_modal .= "\t\t\t</div>\n";
$pay_modal .= "\t\t</div>\n";
$pay_modal .= "\t</div>\n";
$pay_modal .= "</div>\n";

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
if ($section == "admin") include (INCLUDES.'load_cdn_libraries_admin.inc.php');
else include (INCLUDES.'load_cdn_libraries_public.inc.php');
?>

    <script src="https://cdn.jsdelivr.net/npm/js-cookie@3.0.5/dist/js.cookie.min.js"></script>

    <!-- Load BCOE&M Custom CSS - Contains Bootstrap overrides and custom classes common to all BCOE&M themes -->
    <link rel="stylesheet" type="text/css" href="<?php echo $css_common_url; ?>" />
    <link rel="stylesheet" type="text/css" href="<?php echo $theme; ?>" />

    <script type="text/javascript">
        var username_url = "<?php echo $ajax_url; ?>username.ajax.php";
        var email_url="<?php echo $ajax_url; ?>valid_email.ajax.php";
        var user_agent_msg = "<?php echo $alert_text_086; ?>";
        var setup = 0;
    </script>
    
    <!-- Open Graph Implementation -->
<?php if (!empty($_SESSION['contestName'])) { ?>
    <meta property="og:title" content="<?php echo $_SESSION['contestName']?>" />
<?php } ?>
<?php if (!empty($_SESSION['contestLogo'])) { ?>
    <meta property="og:image" content="<?php echo $base_url."user_images/".$_SESSION['contestLogo']; ?>" />
<?php } ?>
    <meta property="og:url" content="<?php echo "http".((!empty($_SERVER['HTTPS'])) ? "s://" : "://").$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI']; ?>" />

</head>

<?php
if (($section == "admin") || ($admin != "default")) require ('index.legacy.php');
else require ('index.pub.php');
if (($section == "list") || ($section == "pay")) echo $pay_modal;
?>

</html>