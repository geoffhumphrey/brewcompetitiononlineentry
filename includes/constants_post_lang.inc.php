<?php
// ---------------------------- Various Functions ----------------------------------

// Testing
if ((TESTING) || (DEBUG)) {
    $mtime = microtime();
    $mtime = explode(" ",$mtime);
    $mtime = $mtime[1] + $mtime[0];
    $starttime = $mtime;
}

if (DEBUG) include (DEBUGGING.'query_count_begin.debug.php');

// Perform version check if NOT going into setup
if (strpos($section, 'step') === FALSE)  {
    version_check($version,$current_version,$current_version_date_display);
}

/**
 * Generate a CSRF token on every page load.
 * This will be used to prevent cross-site request forgeries
 * when processing form data.
 * First check for php 7 compatible random_bytes.
 * If not, use mcrypt_create_iv (deprecated in php 7.1 removed in 7.2)
 * If that's not available, default to openssl_random_pseudo_bytes.
 */

if (function_exists('random_bytes')) $_SESSION['token'] = bin2hex(random_bytes(32));
elseif (function_exists('mcrypt_create_iv')) $_SESSION['token'] = bin2hex(mcrypt_create_iv(32,MCRYPT_DEV_URANDOM));
else $_SESSION['token'] = bin2hex(openssl_random_pseudo_bytes(32));

// Bootstrap layout containers
if (($section == "admin") || ($view == "admin")) {
    $container_main = "container-fluid";
    $nav_container = "navbar-inverse";
}

else {
    $container_main = "container";
    $nav_container = "navbar-default";
}

$security_question = array($label_secret_01, $label_secret_05, $label_secret_06, $label_secret_07, $label_secret_08, $label_secret_09, $label_secret_10, $label_secret_11, $label_secret_12, $label_secret_13, $label_secret_14, $label_secret_15, $label_secret_16, $label_secret_17, $label_secret_18, $label_secret_19, $label_secret_20, $label_secret_21, $label_secret_22, $label_secret_23, $label_secret_25, $label_secret_26, $label_secret_27);

if ($section == "past-winners") {

    $query_disp_archive_winners = sprintf("SELECT * FROM %s WHERE archiveSuffix='%s'",$prefix."archive",$go);
    $disp_archive_winners = mysqli_query($connection,$query_disp_archive_winners);
    $row_disp_archive_winners = mysqli_fetch_assoc($disp_archive_winners);
    $totalRows_disp_archive_winners = mysqli_num_rows($disp_archive_winners);
    
    $archive_winner_display = FALSE;
    
    if (($totalRows_disp_archive_winners > 0) && ($row_disp_archive_winners['archiveDisplayWinners'] == "Y") && ($row_disp_archive_winners['archiveStyleSet'] != "")) {

        $query_disp_archive_winners = sprintf("SELECT * FROM %s WHERE archiveSuffix='%s'",$prefix."archive",$go);
        $disp_archive_winners = mysqli_query($connection,$query_disp_archive_winners);
        $row_disp_archive_winners = mysqli_fetch_assoc($disp_archive_winners);

        if ((check_setup($prefix."brewer_".$go,$database)) && (check_setup($prefix."brewing_".$go,$database)) && (check_setup($prefix."judging_scores_".$go,$database))) {

            $archive_count = get_archive_count($prefix."judging_scores_".$go);
            if ($archive_count > 0) $archive_winner_display = TRUE;
        }

    }

    if (!$archive_winner_display) header(sprintf("Location: %s", $base_url."index.php?msg=8"));
}

if (($row_system) && (!empty($row_system['update_date'])) && ($row_system['update_date'] >= (time() - 86400))) {
    $recently_updated = TRUE;
    $_SESSION['update_summary'] = $row_system['update_summary'];
    if (strpos($row_system['update_summary'], 'Warning: Errors') !== false) $_SESSION['update_errors'] = 1;
}

$style_types_translations = array(
    1 => $label_beer,
    2 => $label_cider,
    3 => $label_mead,
    4 => $label_mead_cider,
    5 => $label_wine,
    6 => $label_rice_wine,
    7 => $label_spirits,
    8 => $label_kombucha,
    9 => $label_pulque
);

$packaging_display = array(
    "750" => "750 ml ".$label_bottle,
    "500" => "500 ml ".$label_bottle,
    "375" => "375 ml ".$label_bottle,
    "22" => "22 oz ".$label_bottle,
    "12" => "12 oz ".$label_bottle,
    "Other-Bottle" => $label_bottle." - ".$label_other_size,
    "19.2" => "19.2 oz ".$label_can,
    "16" => "16 oz ".$label_can,
    "12" => "12 oz ".$label_can,
    "Other-Can" => $label_can." - ".$label_other_size
);
?>