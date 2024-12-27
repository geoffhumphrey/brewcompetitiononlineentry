<?php
/**
 * Module:      index.php
 * Description: This module is the delivery vehicle for all modules.
 *
 */

/**
 * ------------------------------
 * Load Config Scripts
 * ------------------------------
 */

require_once ('paths.php');
require_once (CONFIG.'bootstrap.php');
require_once (DB.'mods.db.php');

$account_pages = array("list","pay","brewer","user","brew","pay","evaluation");

if ((!$logged_in) && (in_array($section,$account_pages))) {
    $redirect = $base_url."index.php?section=login&msg=99";
    $redirect = prep_redirect_link($redirect);
    $redirect_go_to = sprintf("Location: %s", $redirect);
    header($redirect_go_to);
    exit();
}

if (MAINT) {

    if ((!$logged_in) || (($logged_in) && ($_SESSION['userLevel'] > 0))) {
        $redirect = $base_url."maintenance.php";
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

        $redirect = $base_url."index.php?section=login&msg=0";
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
    
    if (CDN) {

        if (V3) {

            if ($section == "admin") include (INCLUDES.'load_cdn_libraries_admin.inc.php');
            else include (INCLUDES.'load_cdn_libraries_public.inc.php');

        }
        
        else include (INCLUDES.'load_cdn_libraries.inc.php');

    }
    
    else include (INCLUDES.'load_local_libraries.inc.php');
?>

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
    <meta property="og:url" content="<?php echo "http" . ((!empty($_SERVER['HTTPS'])) ? "s://" : "://") . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI']; ?>" />
</head>

<?php 

if (V3) {

    if (($section == "admin") || ($admin != "default")) require ('index.legacy.php');
    else require ('index.pub.php');

}

else require ('index.legacy.php');

?>

</html>