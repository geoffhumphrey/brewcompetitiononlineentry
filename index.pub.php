<?php if ($section != "admin") { 

    include (DB.'sponsors.db.php');

    if ($section == "default") {
        include (DB.'dropoff.db.php');
        include (DB.'contacts.db.php');
    } 

    $salutation = "";
    if (($section != "default") && (!is_numeric($section))) {
        $salutation .= "<h1 class='fw-bold animate__animated animate__fadeInDown'>".$_SESSION['contestName']."</h1>";
        if ($logged_in) $salutation .= sprintf("<p class='landing-page-salutation animate__animated animate__fadeInUp animate__delay-3s'><small>%s %s!</small></p>",$default_page_text_006,$_SESSION['brewerFirstName']);
    }
    
    if (($section == "default") || (is_numeric($section))) {

        if (is_numeric($section)) {
            if ($section == "400") $error_header = sprintf("<strong>%s %s.</strong> <small class=\"text-secondary\">%s</small>", $section, ucfirst($label_error), $error_text_400);
            elseif ($section == "401") $error_header = sprintf("<strong>%s %s.</strong> <small class=\"text-secondary\">%s</small>", $section, ucfirst($label_error), $error_text_401);
            elseif ($section == "403") $error_header = sprintf("<strong>%s %s.</strong> <small class=\"text-secondary\">%s</small>", $section, ucfirst($label_error), $error_text_403);
            elseif ($section == "404") $error_header = sprintf("<strong>%s %s.</strong> <small class=\"text-secondary\">%s</small>", $section, ucfirst($label_error), $error_text_404);
            elseif ($section == "500") $error_header = sprintf("<strong>%s %s.</strong> <small class=\"text-secondary\">%s</small>", $section, ucfirst($label_error), $error_text_500);
            else $error_header = sprintf("<strong>%s %s</strong>", $section, ucfirst($label_error));
            $salutation .= sprintf("<p class='landing-page-salutation animate__animated animate__fadeInUp animate__delay-3s'>%s</p>",$error_header);
        }
        
        else {

            if ($logged_in) {
                $salutation .= sprintf("<p class='landing-page-salutation animate__animated animate__fadeInDown animate__delay-3s'>%s %s!</p>",$default_page_text_006,$_SESSION['brewerFirstName']); 
            }
            $salutation .= "<p class='lead landing-page-salutation fw-light animate__animated animate__fadeInUp animate__delay-5s'><small>";
            $salutation .= sprintf("%s %s %s ",$default_page_text_022, $_SESSION['contestName'], $default_page_text_023);
            if ($_SESSION['contestHostWebsite'] != "") $salutation .= sprintf("<a class='hide-loader' href='%s' target='_blank'>%s</a>",$_SESSION['contestHostWebsite'],$_SESSION['contestHost']);
            else $salutation .= $_SESSION['contestHost'];
            if (!empty($_SESSION['contestHostLocation'])) $salutation .= sprintf(", %s",$_SESSION['contestHostLocation']);
            $salutation .= ".";
            $salutation .= "</small></p>";

        }

        
    }

    if ($section == "past-winners") {
        $salutation .= "<p class='lead landing-page-salutation fw-light animate__animated animate__fadeInUp animate__delay-5s'><small>";
        $salutation .= $label_past_winners." &ndash; ".$filter;
        $salutation .= "</small></p>";
    }

    $archive_alert_content = "";
    $archive_alert = FALSE;
    $archive_alert_count = 0;
    $archive_alert_display = "";

    if ((isset($_SESSION['contestWinnerLink'])) && (!empty($_SESSION['contestWinnerLink']))) $archive_alert = TRUE;

    if (!HOSTED) {

        if ($totalRows_archive > 0) {

            do {

                if (($row_archive['archiveDisplayWinners'] == "Y") && ($row_archive['archiveStyleSet'] != "")) {
                    $table_archive = $prefix."judging_scores_".$row_archive['archiveSuffix'];
                    if (table_exists($table_archive)) {
                        if (get_archive_count($table_archive) > 0) {
                            $archive_link = build_public_url("past-winners",$row_archive['archiveSuffix'],"default","default",$sef,$base_url,"default");
                            $archive_alert_count++;
                            if ($go == $row_archive['archiveSuffix']) $archive_alert_content .= "<li class=\"nav-item\"><i class=\"fa fa-fw fa-trophy text-gold me-2\"></i><strong class=\"nav-text\">".$row_archive['archiveSuffix']."</strong></li>";
                            else $archive_alert_content .= "<li class=\"nav-item\"><a class=\"nav-link\" href=\"".$archive_link."\"><i class=\"fa fa-fw fa-trophy text-silver me-2\"></i>".$row_archive['archiveSuffix']."</a></li>";
                        }
                    }
                }   

            } while($row_archive = mysqli_fetch_assoc($archive));

        }

        if ($archive_alert_count > 0) $archive_alert = TRUE;
        
    }

    if ($archive_alert) {

        if ((isset($_SESSION['contestWinnerLink'])) && (!empty($_SESSION['contestWinnerLink']))) {
            if ($archive_alert_count == 0) $archive_alert_content .= sprintf("<li class=\"nav-item\"><a class=\"nav-link\" href=\"%s\" target=\"_blank\">%s<i class=\"fa fa-fw fa-external-link-alt ms-2\"></i></a></li>",$_SESSION['contestWinnerLink'],$label_view);
            else $archive_alert_content .= sprintf("<li class=\"nav-item\"><i class=\"fa fa-fw fa-external-link-alt text-gold me-2\"></i><a class=\"nav-link\" href=\"%s\" target=\"_blank\">%s</a></li>",$_SESSION['contestWinnerLink'],$label_more_info);
        }
        
        $archive_alert_display .= "<div class=\"offcanvas offcanvas-end\" data-bs-scroll=\"true\" data-bs-theme=\"dark\" tabindex=\"-1\" id=\"archive-list\" aria-labelledby=\"archive-list-label\">";
        $archive_alert_display .= "<div class=\"offcanvas-header\">";
        $archive_alert_display .= sprintf("<h4 class=\"offcanvas-title\" id=\"archive-list-label\">%s</h4>",$label_past_winners);
        $archive_alert_display .= "<button type=\"button\" class=\"btn-close\" data-bs-dismiss=\"offcanvas\" aria-label=\"Close\"></button>";
        $archive_alert_display .= "</div>"; // end offcanvas-header
        $archive_alert_display .= "<div class=\"offcanvas-body\">";
        $archive_alert_display .= "<p>".$past_winners_text_000."</p>";
        $archive_alert_display .= "<ul class=\"navbar-nav justify-content-end flex-grow-1\">";
        $archive_alert_display .= $archive_alert_content;
        $archive_alert_display .= "</ul>";
        $archive_alert_display .= "</div>"; // end offcanvas-body
        $archive_alert_display .= "</div>"; // end offcanvas
        
    }

?>

<body data-bs-spy="scroll" data-bs-target="#site-nav">

<div id="sticky-home" class="contains-link d-print-none">
    <a href="#home"><i class="fas fa-2x fa-chevron-circle-up"></i></a>
</div>

<!-- LOADER -->
<div id="loader-submit" class="d-print-none">
    <div class="center">
        <span class="fa fa-spinner fa-spin-pulse fa-spin-reverse fa-5x fa-fw mb-4"></span>
        <p><strong><?php echo $label_working; ?>.<br><?php echo $output_text_030." ".$output_text_031; ?></strong></p>
    </div>
</div>

<!-- HEADER - also contains hero -->
<header id="home" class="site-header">

    <!-- MAIN NAV -->
    <div class="hidden-print d-print-none">
        <?php include (PUB.'nav.pub.php'); ?>
    </div>

    <div class="hidden-print d-print-none">
        <?php include (PUB.'alerts.pub.php'); ?>
    </div>
    
    <?php 

    if (($section == "default") || (is_numeric($section))) {

        $hero_inner = "";

        if ((isset($_SESSION['contestLogo'])) && (!empty($_SESSION['contestLogo'])) && (file_exists(USER_IMAGES.$_SESSION['contestLogo']))) {
            $competition_logo = "<img src=\"".$base_url."user_images/".$_SESSION['contestLogo']."\" style=\"min-width: 150px; max-width: 225px\" class=\"float-end me-5 d-none d-sm-none d-md-none d-lg-inline-block animate__animated animate__fadeInRight\" alt=\"Competition Logo\" title=\"Competition Logo\" />";

            $hero_inner .= "<div class=\"row align-items-center p-3 g-3\">";
            $hero_inner .= "<div class=\"col-12 col-lg-9\">";
            $hero_inner .= "<h1 class=\"text-center animate__animated animate__fadeInLeft\">".$_SESSION['contestName']."</h1>";
            $hero_inner .= "</div>";
            $hero_inner .= "<div class=\"col-12 col-lg-3\">";
            $hero_inner .= $competition_logo;
            $hero_inner .= "</div>";
            $hero_inner .= "</div>"; // end row

            
        }
        
        else {
            $hero_inner .= "<h1 class=\"text-center animate__animated animate__fadeInUp\">".$_SESSION['contestName']."</h1>";
        }

    ?>
    
    <!-- HERO -->
    <div id="hero" class="layout-hero text-light d-flex align-items-center d-print-none">
        <section class="container-fluid shadow-text color-hero px-3">
            <header><?php echo $hero_inner; ?></header>
        </section>
    </div>
    <?php } ?>

    <div id="salutation" class="text-light bg-black pt-4 pb-3 d-print-none">
        <section class="<?php echo $container_main; ?>">
            <?php echo $salutation; ?>
        </section>
    </div>

    <?php } ?>

</header>
<!-- ./HEADER -->

<?php 

// Top-of-screen items
// include (PUB.'alerts.pub.php'); 
if (DEBUG_SESSION_VARS) include (DEBUGGING.'session_vars.debug.php');
if ($_SESSION['prefsUseMods'] == "Y") include (INCLUDES.'mods_top.inc.php');
    
if (ENABLE_MARKDOWN) {
    include (CLASSES.'parsedown/Parsedown.php');
    $Parsedown = new Parsedown();
}

?>

<script>
    try {
        var ua = window.navigator.userAgent;
        var msie = ua.indexOf("MSIE ");
        if (msie > 0 || !!navigator.userAgent.match(/Trident.*rv\:11\./)) {
            alert(user_agent_msg)
        }
    } catch (error) {
        console.error('Error checking user agent.', error)
    }
</script>

<!-- Public Pages -->

<div id="main-content" class="container-xxl">

    <div class="d-none d-print-block landing-page-section p-3">
        <h1 class="fs-1 fw-bold"><?php echo $_SESSION['contestName'] ?></h1>
    </div>

    <?php if ($section == "default") { ?>

    <section id="at-a-glance" class="landing-page-section p-3">
        <?php include (PUB.'default.pub.php'); ?>
    </section>

    <?php if (!$judging_started) { ?>
    <section id="rules" class="landing-page-section pb-3">
        <header class="landing-page-section-header py-2">
            <h1><?php echo $label_rules; ?></h1>
        </header>
        <?php include (PUB.'reg_open.pub.php'); ?>
    </section>
    
    <section id="entry-info" class="landing-page-section pb-3">
        <header class="landing-page-section-header py-2">
            <h1><?php echo $label_entry_info; ?></h1>
        </header>
        <?php include (PUB.'entry_info.pub.php'); ?>
    </section>

    <section id="volunteers" class="landing-page-section pb-3">
        <header class="landing-page-section-header py-2">
            <h1><?php echo $label_volunteers; ?></h1>
        </header>
        <?php include (PUB.'volunteers.pub.php'); ?>
    </section>
    <?php } ?>

    <?php if (($_SESSION['prefsSponsors'] == "Y") && ($totalRows_sponsors > 0)) { ?>

    <section id="sponsors" class="landing-page-section pb-3 d-print-none">
        <header class="landing-page-section-header py-2">
            <h1><?php echo $label_sponsors; ?></h1>
        </header>
        <?php include (PUB.'sponsors.pub.php'); ?>
    </section>

    <?php } ?>
    
    <section id="contact" class="landing-page-section pb-3 d-print-none">
        <header class="landing-page-section-header py-2">
            <h1><?php echo $label_contact; ?></h1>
        </header>
        <?php include (PUB.'contact.pub.php'); ?>
    </section>
    
    <?php } // end if ($section == "default") ?>

    <?php if ($section == "past-winners") { ?>
        <section id="past-winners" class="landing-page-section pb-3">
            <?php include (PUB.'past_winners.pub.php'); ?>
        </section>
    <?php } ?>

    <?php if ($section == "list") { ?>
        <section id="list" class="landing-page-section pb-3">
            <header class="landing-page-section-header py-2">
                <h1><?php echo $header_output; ?></h1>
            </header>
            <?php include (PUB.'list.pub.php'); ?>
        </section>
    <?php } ?>

    <?php if ($section == "brew") { ?>
        <section id="brew" class="landing-page-section pb-3">
            <header class="landing-page-section-header py-2">
                <h1><a name="add-entry"></a><?php echo $header_output; ?></h1>
            </header>
            <?php include (PUB.'brew.pub.php'); ?>
        </section>
    <?php } ?>

    <?php if ($section == "brewer") { ?>
        <section id="brewer" class="landing-page-section pb-3">
            <header class="landing-page-section-header py-2">
                <h1><a name="edit-account"></a><?php echo $header_output; ?></h1>
            </header>
            <?php include (PUB.'brewer.pub.php'); ?>
        </section>
    <?php } ?>

    <?php if ($section == "user") { ?>
        <section id="user" class="landing-page-section pb-3">
            <header class="landing-page-section-header py-2">
                <h1><a name="user-account"></a><?php echo $header_output; ?></h1>
            </header>
            <?php include (PUB.'user.pub.php'); ?>
        </section>
    <?php } ?>

    <?php if ($section == "register") { ?>
        <section id="login" class="landing-page-section pb-3">
            <header class="landing-page-section-header py-2">
                <h1><?php echo $header_output; ?></h1>
            </header>
            <?php include (PUB.'register.pub.php'); ?>
        </section>
    <?php } ?>

    <?php if (($_SESSION['prefsEval'] == 1) && ($section == "evaluation") && ($logged_in)) { ?>

        <section id="login" class="landing-page-section pb-3">
            <header class="landing-page-section-header py-2">
                <h1><?php echo $header_output; ?></h1>
            </header>
            <?php include (PUB.'electronic_scoresheets.pub.php'); ?>
        </section>

    <?php } ?>

    <?php if (is_numeric($section)) { ?>

        <section id="error-page" class="landing-page-section mt-4 mb-3">
            <h4><?php echo $header_text_014; ?></h4>
            <p class="lead"><?php echo $error_text_000; ?></p>
            <p class="lead"><small><?php echo $error_text_001; ?></small></p>
            <p><?php echo $label_cheers; ?>!</p>
        </section>
    
    <?php } ?>
    
</div>

<!-- Login Modals -->
<?php

$sign_in_card_body = "";
$sign_in_card_body .= "<div class=\"form-floating mb-3\">";
$sign_in_card_body .= "<input class=\"form-control form-control-lg mb-3\" id=\"login-user-name\" type=\"email\" name=\"loginUsername\" placeholder=\"".$label_email."\" required>";
$sign_in_card_body .= "<label for=\"login-user-name\">".$label_email."</label>";
$sign_in_card_body .= "</div>";
$sign_in_card_body .= sprintf("<div class=\"invalid-feedback mb-4\">%s %s</div>",$login_text_018,$login_text_021);
$sign_in_card_body .= "<div class=\"form-floating mb-3\">";
$sign_in_card_body .= "<input class=\"form-control form-control-lg mb-3\" id=\"login-password\" type=\"password\" name=\"loginPassword\" placeholder=\"".$label_password."\" required>";
$sign_in_card_body .= "<label for=\"login-user-name\">".$label_password."</label>";
$sign_in_card_body .= "</div>";
$sign_in_card_body .= "<div class=\"invalid-feedback mb-3\">".$login_text_019."</div>";
$sign_in_post_action = $base_url."includes/process.inc.php?section=login&action=login";

$forgot_password_card_footer = "";
$forgot_password_card_body = "";
$forgot_password_card_body .= "<p class=\"lead\">".$login_text_006."</p>";
$forgot_password_card_body .= "<div class=\"form-floating mb-3\">";
$forgot_password_card_body .= "<input class=\"form-control form-control-lg mb-3\" onkeyup=\"check_valid_email(base_url,'forgot-user-name','forgot-user-name-email-status')\" name=\"forgot-user-name\" id=\"forgot-user-name\" type=\"text\" placeholder=\"".$label_email."\">";
$forgot_password_card_body .= "<label for=\"login-user-name\">".$label_email."</label>";
//$forgot_password_card_body .= "<div class=\"invalid-feedback mb-4\">".$login_text_018."</div>";
$forgot_password_card_body .= "</div>";
$forgot_password_card_body .= "<div id=\"forgot-user-name-email-status\" class=\"mb-3\"></div>";
$forgot_password_card_body .= "<div id=\"forgot-user-name-status\" class=\"mb-3\"></div>";
$forgot_password_card_body .= "<div id=\"security-question-response-status\" class=\"mb-3\"></div>";
$forgot_password_card_footer .= "<div class=\"d-grid\">";
$forgot_password_card_footer .= "<button id=\"forgot-user-name-check-button\" class=\"btn btn-block btn-lg btn-primary mb-3\" onclick=\"check_user_name_avail(base_url,'forgot-user-name','forgot-user-name-status','forgot_password')\">".$label_verify."<i class=\"fas fa-search ps-2\"></i></button>";
$forgot_password_card_footer .= "<button id=\"forgot-user-name-clear-button\" class=\"btn btn-block btn-lg btn-secondary\" onclick=\"forgot_password_clear_all()\">".$label_clear."<i class=\"fas fa-eraser ps-2\"></i></button>";
$forgot_password_card_footer .= "</div>";

?>

<div class="modal fade" id="login-modal" aria-hidden="true" aria-labelledby="login-modal-label" tabindex="-1">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h1 class="modal-title fs-5" id="login-modal-label"><?php echo $label_log_in; ?></h1>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <?php 
        
        ?>
        <form class="needs-validation" method="post" action="<?php echo $sign_in_post_action; ?>" novalidate>
            <?php echo $sign_in_card_body; ?>
            <div class="d-grid gap-2 mx-auto mb-4">
              <button id="login-button" class="btn btn-lg btn-success" type="submit"><?php echo $label_log_in; ?><i class="fas fa-sign-in-alt ps-2"></i></button>
            </div>
        </form>
        <?php echo sprintf("<div class=\"text-center\">%s <button class=\"btn btn-sm btn-warning\" data-bs-target=\"#forgot-modal\" data-bs-toggle=\"modal\">%s</button></div>",$login_text_004,$login_text_005); ?>
      </div>
      
    </div>
  </div>
</div>
<div class="modal fade" id="forgot-modal" aria-hidden="true" aria-labelledby="login-modal-label2" tabindex="-1">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h1 class="modal-title fs-5" id="login-modal-label2"><?php echo $label_password_reset; ?></h1>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
            <div id="forgot-password">
                <div id="sign-in-form" class="container h-100 links-body">
                    <div class="row h-100 justify-content-center align-items-center">
                        <?php echo $forgot_password_card_body; ?>
                    </div>
                </div>
            </div>
        <div>
            <?php echo $forgot_password_card_footer; ?>
        </div> 
      </div>
      <div class="modal-footer">
        <button class="btn btn-default" data-bs-target="#login-modal" data-bs-toggle="modal"><i class="fas fa-chevron-left pe-2"></i><?php echo $label_log_in; ?></button>
      </div>
    </div>
  </div>
</div>


<!-- ./Public Pages -->

<?php 
if (DEBUG) include (DEBUGGING.'query_count_end.debug.php'); 
if ($_SESSION['prefsUseMods'] == "Y") include (INCLUDES.'mods_bottom.inc.php');
?>

<!-- Footer -->
<footer class="site-footer bg-dark text-light justify-content-center container-fluid fixed-bottom pt-3 d-print-none">
     <p class="text-center"><?php include (PUB.'footer.pub.php'); ?></p>
</footer>

<?php 
session_write_close();
if ($logged_in) {
    $session_end_seconds = (time() + ($session_expire_after * 60));
    $session_end = date('Y-m-d H:i:s',$session_end_seconds);
    if (!empty($error_output)) $_SESSION['error_output'] = $error_output;
?>

    <!-- Session Timer Displays and Auto Logout -->
    <?php if ((!in_array($go,$datetime_load)) || ($go == "default")) { ?>
    
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/moment-timezone/0.5.32/moment-timezone-with-data.min.js"></script>
    <script>
        var session_end = moment.tz("<?php echo $session_end; ?>","<?php echo get_timezone($_SESSION['prefsTimeZone']); ?>");
        var session_end_min = "<?php echo $session_expire_after; ?>";
        var session_end_seconds = "<?php echo $session_end_seconds; ?>";
        var session_end_redirect = "<?php echo $base_url; ?>includes/process.inc.php?section=logout&action=logout";
    </script>
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jquery.countdown/2.2.0/jquery.countdown.min.js"></script>
    <script type="text/javascript" src="<?php echo $js_url; ?>autologout.min.js"></script>

    <!-- Session Expiring Modal: 2 Minute Warning -->
    <div class="modal fade" id="session-expire-warning" tabindex="-1" role="dialog" aria-labelledby="session-expire-warning-label">
      <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <h4 class="modal-title" id="session-expire-warning-label"><?php echo $label_session_expire; ?></h4>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          <div class="modal-body">
            <p><?php echo $alert_text_090; ?></p>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-default" data-bs-dismiss="modal"><?php echo $label_stay_here; ?></button>
            <button type="button" class="btn btn-success" data-bs-dismiss="modal" onclick="window.location.reload()"><?php echo $label_refresh; ?></button>
            <button type="button" class="btn btn-danger" data-bs-dismiss="modal" onclick="window.location.replace('<?php echo $base_url; ?>includes/process.inc.php?section=logout&action=logout')"><?php echo $label_log_out; ?></button>
          </div>
        </div>
      </div>
    </div>

    <!-- Session Expiring Modal: 30 Second Warning -->
    <div class="modal fade" id="session-expire-warning-30" tabindex="-1" role="dialog" aria-labelledby="session-expire-warning-30-label">
      <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <h4 class="modal-title" id="session-expire-warning-30-label"><?php echo $label_session_expire; ?></h4>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          <div class="modal-body">
            <p><?php echo $alert_text_091; ?></p>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-success" data-bs-dismiss="modal" onclick="window.location.reload()"><?php echo $label_refresh; ?></button>
            <button type="button" class="btn btn-danger" data-bs-dismiss="modal" onclick="window.location.replace('<?php echo $base_url; ?>includes/process.inc.php?section=logout&action=logout')"><?php echo $label_log_out; ?></button>
          </div>
        </div>
      </div>
    </div>

    <?php } ?>

<?php } // end if ($logged_in) ?>

    <script type="text/javascript">
        var section = "<?php echo $section; ?>";
        var action = "<?php echo $action; ?>";
        var go = "<?php echo $go; ?>";
        if (section != "brew") var edit_style = "<?php echo $action; ?>";
        else edit_style = edit_style;
        var user_level = "<?php if ((isset($_SESSION['userLevel'])) && ($bid != "default")) echo $_SESSION['userLevel']; else echo "2"; ?>";
    <?php if (($section == "admin") && ($go == "styles") && ($action != "default")) { ?>
        var specialty_ipa_subs = <?php echo json_encode($specialty_ipa_subs); ?>;
        var historical_subs = <?php echo json_encode($historical_subs); ?>;
        if (edit_style == "edit") {
            var req_special = "<?php echo $row_styles['brewStyleReqSpec']; ?>";
            var style_type = "<?php echo $row_styles['brewStyleType']; ?>";
        } else { 
            var req_special = "0";
            var style_type = "1";
        }
    <?php } ?>

    </script>

<?php if ($section == "brewer") {
    $brewery_ttb = "false";
    $brewery_prod = "false";
    if (isset($row_brewer['brewerBreweryInfo'])) { 
        $brewery_info = json_decode($row_brewer['brewerBreweryInfo'],true); 
        if (isset($brewery_info['TTB'])) $brewery_ttb = "true";
        if (isset($brewery_info['Production'])) $brewery_prod = "true";
    }
?> 
    <script type='text/javascript'>
    var club_other = <?php if ($club_other) echo "true"; else echo "false"; ?>;
    var brewer_judge = "N";
    var brewer_steward = "N";
    var brewer_staff = "N";
    var brewer_brewery_ttb = <?php echo $brewery_ttb; ?>;
    var brewer_brewery_prod = <?php echo $brewery_prod; ?>;
    var user_question_answer = "<?php if (isset($_SESSION['userQuestionAnswer'])) echo $_SESSION['userQuestionAnswer']; ?>"
    if (action == "edit") {
        var brewer_country = "<?php if (isset($row_brewer)) echo $row_brewer['brewerCountry']; ?>";
        var brewer_judge = "<?php if (isset($row_brewer)) echo $row_brewer['brewerJudge']; ?>";
        var brewer_steward = "<?php if (isset($row_brewer)) echo $row_brewer['brewerSteward']; ?>";
        var brewer_staff = "<?php if (isset($row_brewer)) echo $row_brewer['brewerStaff']; ?>";
    }
    </script>
<?php } // end if ($section == "brewer") ?>

    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js" integrity="sha384-I7E8VVD/ismYTF4hNIPjVp/Zjvgyol6VFvRkX/vR+Vc4jQkC+hVqc2pM8ODewa9r" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.min.js" integrity="sha384-BBtl+eGJRgqQAUMxJ7pMwbEyER4l1g+O15P+16Ep7Q9Q+zqX6gSbd85u4mG4QzX+" crossorigin="anonymous"></script>
    <script src="<?php echo $js_app_pub_url; ?>"></script>
    <script type="text/javascript">
        $(document).ready(function () {
                document
                    .querySelectorAll('.bootstrap-select')
                    .forEach((el) => {
                        let settings = {
                            maxOptions: 1000,
                            allowEmptyOption: false
                        };
                    new TomSelect(el, settings)
                });
                const tooltipTriggerList = document.querySelectorAll('[data-bs-toggle="tooltip"]');
                const tooltipList = [...tooltipTriggerList].map(tooltipTriggerEl => new bootstrap.Tooltip(tooltipTriggerEl));
                var popoverTriggerList = []
                    .slice
                    .call(document.querySelectorAll('[data-bs-toggle="popover"]'));
                var popoverList = popoverTriggerList.map(function (popoverTriggerEl) {
                    return new bootstrap.Popover(popoverTriggerEl)
                });
                var topoffset = 55;
                $('.navbar a[href*=\\#]:not([href=\\#])').click(function () {
                    if (location.pathname.replace(/^\//, '') === this.pathname.replace(/^\//, '') && location.hostname === this.hostname) {
                        var target = $(this.hash);
                        target = target.length
                            ? target
                            : $('[name=' + this.hash.slice(1) + ']');
                        if (target.length) {
                            $('html,body').animate({
                                scrollTop: target
                                    .offset()
                                    .top - topoffset + 2
                            }, 500);
                            return false
                        }
                    }
                });
                $('.modal-body a[href*=\\#]:not([href=\\#])').click(function () {
                    if (location.pathname.replace(/^\//, '') === this.pathname.replace(/^\//, '') && location.hostname === this.hostname) {
                        var target = $(this.hash);
                        target = target.length
                            ? target
                            : $('[name=' + this.hash.slice(1) + ']');
                        if (target.length) {
                            $('.modal').modal('hide');
                            $('html,body').animate({
                                scrollTop: target
                                    .offset()
                                    .top - topoffset + 2
                            }, 500);
                            return false
                        }
                    }
                });
                $('.contains-link a[href*=\\#]:not([href=\\#])').click(function () {
                    if (location.pathname.replace(/^\//, '') === this.pathname.replace(/^\//, '') && location.hostname === this.hostname) {
                        var target = $(this.hash);
                        target = target.length
                            ? target
                            : $('[name=' + this.hash.slice(1) + ']');
                        if (target.length) {
                            $('html,body').animate({
                                scrollTop: target
                                    .offset()
                                    .top - topoffset + 2
                            }, 500);
                            return false
                        }
                    }
                });
                $(window).on('activate.bs.scrollspy', function () {
                    var hash = $('.site-nav')
                        .find('a.active')
                        .attr('href');
                    if (hash === '#home') {
                        $('#sticky-home').slideUp(500)
                    } else {
                        $('#sticky-home').slideDown(500)
                    }
                });
                const animateCSS = (element, animation, prefix = 'animate__') => new Promise((resolve, reject) => {
                    const animationName = `${prefix}${animation}`;
                    const node = document.querySelector(element);
                    node
                        .classList
                        .add(`${prefix}animated`, animationName);
                    function handleAnimationEnd(event) {
                        event.stopPropagation();
                        node
                            .classList
                            .remove(`${prefix}animated`, animationName);
                        resolve('Animation ended')
                    }
                    node.addEventListener('animationend', handleAnimationEnd, {once: true})
                })
            });
        (() => {
            'use strict';
            const forms = document.querySelectorAll('.needs-validation');
            Array
                .from(forms)
                .forEach(form => {
                    form.addEventListener('submit', event => {
                        if (!form.checkValidity()) {
                            document
                                .getElementById('loader-submit')
                                .style
                                .display = 'none';
                            event.preventDefault();
                            event.stopPropagation();
                            $("#form-submit-button-disabled-msg-required").modal('show')
                        } else {
                            document
                                .getElementById('loader-submit')
                                .style
                                .display = 'block'
                        }
                        form
                            .classList
                            .add('was-validated')
                    }, false)
                })
        })();
    </script>

    <?php 
    if (($_SESSION['prefsEval'] == 1) && ($section == "evaluation")) include (PUB.'eval_warnings.pub.php');
    // echo "<div id=\"no-js-alert\" class=\"d-print-none\">".create_bs_alert("no-js-alert-msg","danger","",$alert_text_087,"fa-exclamation-circle","",FALSE)."</div>";
    ?>

    <!-- Required Info Missing Modal -->
    <div class="modal modal-lg fade" id="form-submit-button-disabled-msg-required" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="form-submit-button-disabled-msg-required-label" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                <h1 class="modal-title fs-5" id="form-submit-button-disabled-msg-required-label"><?php echo $label_required_info; ?></h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                <?php echo sprintf("<p><strong>%s</strong></p><p>%s</p>",$form_required_fields_00,$form_required_fields_01); ?>
                </div>
                <div class="modal-footer">
                <button type="button" class="btn btn-primary" data-bs-dismiss="modal"><?php echo $label_understand; ?></button>
                </div>
            </div>
        </div>
    </div>
</body>