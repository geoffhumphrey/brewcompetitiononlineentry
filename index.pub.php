<?php if ($section != "admin") { 

    $salutation = "";
    $salutation .= "";
    if ($logged_in) $salutation .= sprintf("<p class='lead landing-page-salutation fw-bold'>%s %s!</p>",$default_page_text_006,$_SESSION['brewerFirstName']); 
    if ($section == "default") {
        $salutation .= "<p class='lead landing-page-salutation fw-light'><small>";
        $salutation .= sprintf("%s %s %s ",$default_page_text_022, $_SESSION['contestName'], $default_page_text_023);
        if ($_SESSION['contestHostWebsite'] != "") $salutation .= sprintf("<a class='hide-loader' href='%s' target='_blank'>%s</a>",$_SESSION['contestHostWebsite'],$_SESSION['contestHost']);
        else $salutation .= $_SESSION['contestHost'];
        if (!empty($_SESSION['contestHostLocation'])) $salutation .= sprintf(", %s",$_SESSION['contestHostLocation']);
        $salutation .= ".";
        $salutation .= "</small></p>";
    }

?>

<body data-bs-spy="scroll" data-bs-target="#site-nav">

<div id="sticky-home" class="contains-link">
    <a href="#home"><i class="fas fa-2x fa-chevron-circle-up"></i></a>
</div>

<!-- LOADER -->
<div id="loader-submit">
    <div class="center">
        <span class="fa fa-spinner fa-spin fa-5x fa-fw mb-4"></span>
        <p><strong><?php echo $label_working; ?>.<br><?php echo $output_text_030." ".$output_text_031; ?></strong></p>
    </div>
</div>

<!-- HEADER - also contains hero -->
<header id="home" class="site-header">

    <!-- MAIN NAV -->
    <div class="hidden-print">
        <?php 
        if ($section == "admin") include (PUB.'admin-nav.pub.php'); 
        else include (PUB.'nav.pub.php'); 
        ?>
    </div>

    <?php include (PUB.'alerts.pub.php'); ?>
    
    <!-- HERO -->
    <div id="hero" class="layout-hero text-light d-flex align-items-center">
        <section class="container-fluid shadow-text color-hero px-3">
            <header>
                <h1 class="text-center"><?php echo $_SESSION['contestName']; ?></h1>
            </header>
        </section>
    </div>
    <!-- ./HERO -->

    <div id="salutation" class="text-light bg-black p-5">
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

// Admin
if (($section == "admin") && (($logged_in) && ($_SESSION['userLevel'] <= 1))) include (PUB.'admin.pub.php'); 

// Electronic scoresheets and Judging Dashboard
elseif (($_SESSION['prefsEval'] == 1) && ($section == "evaluation") && ($logged_in)) include (PUB.'electronic_scoresheets.pub.php');

// If public pages
else { 
    
    if (ENABLE_MARKDOWN) {
        include (CLASSES.'parsedown/Parsedown.php');
        $Parsedown = new Parsedown();
    }

?>

<script>
    
    $("#no-js-alert").removeClass('show');
    
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
    <?php
    if ($section == "default") { 
        include (DB.'dropoff.db.php');
        include (DB.'sponsors.db.php');
        include (DB.'contacts.db.php');
    ?>
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

    <section id="sponsors" class="landing-page-section pb-3">
        <header class="landing-page-section-header py-2">
            <h1><?php echo $label_sponsors; ?></h1>
        </header>
        <?php include (PUB.'sponsors.pub.php'); ?>
    </section>
    
    <section id="contact" class="landing-page-section pb-3">
        <header class="landing-page-section-header py-2">
            <h1><?php echo $label_contact; ?></h1>
        </header>
        <?php include (PUB.'contact.pub.php'); ?>
    </section>
    
    <?php // if (!$logged_in) { ?>
    <!--
    <section id="login" class="landing-page-section pb-3">
        <header class="landing-page-section-header py-2">
            <h1><?php echo $label_log_in; ?></h1>
        </header>
        <?php // include (PUB.'login.pub.php'); ?>
    </section>
    -->
    <?php // } ?>
    <?php } // end if ($section == "default") ?>

    <?php if ($section == "list") { ?>
        <section id="login" class="landing-page-section pb-3">
            <header class="landing-page-section-header py-2">
                <h1><?php echo $header_output; ?></h1>
            </header>
            <?php include (PUB.'list.pub.php'); ?>
        </section>
    <?php } ?>

    <?php if ($section == "brew") { ?>
        <section id="login" class="landing-page-section pb-3">
            <header class="landing-page-section-header py-2">
                <h1><a name="add-entry"></a><?php echo $header_output; ?></h1>
            </header>
            <?php include (PUB.'brew.pub.php'); ?>
        </section>
    <?php } ?>

    <?php if ($section == "brewer") { ?>
        <section id="login" class="landing-page-section pb-3">
            <header class="landing-page-section-header py-2">
                <h1><a name="edit-account"></a><?php echo $header_output; ?></h1>
            </header>
            <?php include (PUB.'brewer.pub.php'); ?>
        </section>
    <?php } ?>

    <?php if ($section == "user") { ?>
        <section id="login" class="landing-page-section pb-3">
            <header class="landing-page-section-header py-2">
                <h1><a name="edit-account"></a><?php echo $header_output; ?></h1>
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

<?php } // end else

/*
if ($logged_in) {
    if ($section == "brewer") include (SECTIONS.'brewer.pub.php');
    if ($section == "list") include (SECTIONS.'list.pub.php');
    if ($section == "brew") include (SECTIONS.'brew.pub.php');
    if ($section == "pay") include (SECTIONS.'pay.pub.php');
    if ($section == "user") include (SECTIONS.'user.pub.php');
}
*/

if (DEBUG) include (DEBUGGING.'query_count_end.debug.php'); 
if ($_SESSION['prefsUseMods'] == "Y") include (INCLUDES.'mods_bottom.inc.php');

?>

<!-- Footer -->
<?php if ($section == "admin") { ?>
<footer class="footer hidden-xs">
    <div class="navbar <?php echo $nav_container; ?> navbar-fixed-bottom">
        <div class="<?php echo $container_main; ?> text-center">
            <p class="navbar-text col-md-12 col-sm-12 col-xs-12 text-muted small bcoem-footer"><?php include (SECTIONS.'footer.sec.php'); ?></p>
        </div>
    </div>
</footer>
<?php } else { ?>
<footer class="site-footer bg-dark text-light justify-content-center container-fluid fixed-bottom pt-3">
     <p class="text-center"><?php include (PUB.'footer.pub.php'); ?></p>
</footer>
<?php } 

session_write_close(); 

?>


<?php if ($logged_in) {

    $session_end_seconds = (time() + ($session_expire_after * 60));
    $session_end = date('Y-m-d H:i:s',$session_end_seconds);
    if (!empty($error_output)) $_SESSION['error_output'] = $error_output;

    if ($section == "admin") { ?>
    <!-- Admin: Session Expiring Modal: 2 Minute Warning -->
    <div class="modal fade" id="session-expire-warning" tabindex="-1" role="dialog" aria-labelledby="session-expire-warning-label">
      <div class="modal-dialog" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            <h4 class="modal-title" id="session-expire-warning-label"><?php echo $label_session_expire; ?></h4>
          </div>
          <div class="modal-body">
            <p><?php echo $alert_text_090; ?></p>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-default" data-dismiss="modal"><?php echo $label_stay_here; ?></button>
            <button type="button" class="btn btn-success" data-dismiss="modal" onclick="window.location.reload()"><?php echo $label_refresh; ?></button>
            <button type="button" class="btn btn-danger" data-dismiss="modal" onclick="window.location.replace('<?php echo $base_url; ?>includes/process.inc.php?section=logout&action=logout')"><?php echo $label_log_out; ?></button>
          </div>
        </div>
      </div>
    </div>
    <!-- Session Expiring Modal: 30 Second Warning -->
    <div class="modal fade" id="session-expire-warning-30" tabindex="-1" role="dialog" aria-labelledby="session-expire-warning-30-label">
      <div class="modal-dialog" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            <h4 class="modal-title" id="session-expire-warning-30-label"><?php echo $label_session_expire; ?></h4>
          </div>
          <div class="modal-body">
            <p><?php echo $alert_text_091; ?></p>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-success" data-dismiss="modal" onclick="window.location.reload()"><?php echo $label_refresh; ?></button>
            <button type="button" class="btn btn-danger" data-dismiss="modal" onclick="window.location.replace('<?php echo $base_url; ?>includes/process.inc.php?section=logout&action=logout')"><?php echo $label_log_out; ?></button>
          </div>
        </div>
      </div>
    </div>
    <?php } ?>

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
    <?php } ?>

    <?php if (($_SESSION['prefsEval'] == 1) && ($section == "evaluation")) include (EVALS.'warnings.eval.php'); ?>

<?php } // end if ($logged_in) ?>

    <script type="text/javascript">
        var section = "<?php echo $section; ?>";
        var action = "<?php echo $action; ?>";
        var go = "<?php echo $go; ?>";
        var edit_style = "<?php echo $action; ?>";
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

<?php 
if (($_SESSION['prefsEval'] == 1) && ($section == "evaluation")) include (EVALS.'warnings.eval.php'); 
echo create_bs_alert("no-js-alert","danger","",$alert_text_087,"fa-exclamation-circle","",FALSE);
?>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js" integrity="sha384-I7E8VVD/ismYTF4hNIPjVp/Zjvgyol6VFvRkX/vR+Vc4jQkC+hVqc2pM8ODewa9r" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.min.js" integrity="sha384-BBtl+eGJRgqQAUMxJ7pMwbEyER4l1g+O15P+16Ep7Q9Q+zqX6gSbd85u4mG4QzX+" crossorigin="anonymous"></script>
    <script src="<?php echo $js_app_pub_url; ?>"></script>
    <script>
    // All below are additions to app.pub.min.js 
    // For V3 ONLY
    $(document).ready(function() {

        // https://tom-select.js.org/
        document.querySelectorAll('.bootstrap-select').forEach((el)=>{
            
            let settings = {
                
                // failsafe to show all options
                maxOptions: 1000, 

                // allows for value="" to be used as a first option (placeholder)
                allowEmptyOption: false 
            
            };

            new TomSelect(el,settings);
        });

        const tooltipTriggerList = document.querySelectorAll('[data-bs-toggle="tooltip"]');
        const tooltipList = [...tooltipTriggerList].map(tooltipTriggerEl => new bootstrap.Tooltip(tooltipTriggerEl));
        
        var popoverTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="popover"]'))
        var popoverList = popoverTriggerList.map(function (popoverTriggerEl) {
          return new bootstrap.Popover(popoverTriggerEl)
        });   
        
        var topoffset = 55;

        // Links in nav
        $('.navbar a[href*=\\#]:not([href=\\#])').click(
          function() {
            if (
              location.pathname.replace(/^\//, '') ===
                this.pathname.replace(/^\//, '') &&
              location.hostname === this.hostname
            ) {
              var target = $(this.hash);
              target = target.length
                ? target
                : $('[name=' + this.hash.slice(1) + ']');
              if (target.length) {
                $('html,body').animate(
                  {
                    scrollTop: target.offset().top - topoffset + 2
                  },
                  500
                );
                return false;
              }
            }
          }
        );

        // Links in Modals
        $('.modal-body a[href*=\\#]:not([href=\\#])').click(
          function() {
            if (location.pathname.replace(/^\//, '') === this.pathname.replace(/^\//, '') && location.hostname === this.hostname) {
              var target = $(this.hash);
              target = target.length
                ? target
                : $('[name=' + this.hash.slice(1) + ']');
              if (target.length) {
                $('.modal').modal('hide');
                $('html,body').animate(
                  {
                    scrollTop: target.offset().top - topoffset + 2
                  },
                  500
                );
                return false;
              }
            }
          }
        );

        // Links in sections
        $('.contains-link a[href*=\\#]:not([href=\\#])').click(
          function() {
            if (
              location.pathname.replace(/^\//, '') ===
                this.pathname.replace(/^\//, '') &&
              location.hostname === this.hostname
            ) {
              var target = $(this.hash);
              target = target.length
                ? target
                : $('[name=' + this.hash.slice(1) + ']');
              if (target.length) {
                $('html,body').animate(
                  {
                    scrollTop: target.offset().top - topoffset + 2
                  },
                  500
                );
                return false;
              }
            }
          }
        );

        $(window).on('activate.bs.scrollspy', function() {
          var hash = $('.site-nav')
            .find('a.active')
            .attr('href');

          if (hash === '#home') {
            $('#sticky-home').slideUp(500);
          } else {
            $('#sticky-home').slideDown(500);
          }
          
        });

        const animateCSS = (element, animation, prefix = 'animate__') =>

        new Promise((resolve, reject) => {
          const animationName = `${prefix}${animation}`;
          const node = document.querySelector(element);

          node.classList.add(`${prefix}animated`, animationName);

          // When the animation ends, we clean the classes and resolve the Promise
          function handleAnimationEnd(event) {
            event.stopPropagation();
            node.classList.remove(`${prefix}animated`, animationName);
            resolve('Animation ended');
          }

          node.addEventListener('animationend', handleAnimationEnd, {once: true});
        });

    });

    (() => {

      'use strict'

      // Fetch all the forms we want to apply custom Bootstrap validation styles to
      const forms = document.querySelectorAll('.needs-validation');

      // Loop over them and prevent submission
      Array.from(forms).forEach(form => {
        form.addEventListener('submit', event => {
          
          if (!form.checkValidity()) {
            document.getElementById('loader-submit').style.display = 'none';
            event.preventDefault();
            event.stopPropagation();
            $("#form-submit-button-disabled-msg-required").modal('show');        
          }

          else {
            document.getElementById('loader-submit').style.display = 'block';
          }

          form.classList.add('was-validated');
        }, false);

      });

    })()

    </script>
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