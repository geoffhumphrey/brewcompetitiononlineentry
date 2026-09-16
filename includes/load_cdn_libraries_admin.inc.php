<?php
/**
 * Admin Bootstrap 3->5 migration, Phase 0 (gradual/parallel rollout):
 * every admin/eval page still loads the legacy Bootstrap 3 asset set
 * below by default - zero behavior change for anything not yet
 * converted. A page opts into the Bootstrap 5 set (matching what
 * includes/load_cdn_libraries_public.inc.php already runs in
 * production) by adding its $go value to $admin_bs5_pages, once that
 * page's own markup has actually been converted (Phase 4/5 work). The
 * old block is retired entirely once every page has opted in. See the
 * BCOE&M Enhancement Ledger, "Admin BS5 Migration" Phase 0-5 cards.
 *
 * Known deferred gaps in the Bootstrap 5 block below, intentionally not
 * solved here because no page needs them yet:
 *  - bootstrap-datetimepicker has no Bootstrap-5 equivalent loaded yet
 *    (Tempus Dominus v6, per the ledger's Phase 0 research) - add it
 *    when all_dates.admin.php / judging_locations.admin.php /
 *    non-judging_locations.admin.php are actually converted.
 *  - Jasny Bootstrap (the admin off-canvas mobile menu) is loaded in
 *    BOTH blocks unconditionally, not just the legacy one - sections/
 *    nav.sec.php (unconditionally included on every admin page,
 *    regardless of which block is active) still emits Jasny-dependent
 *    markup until Phase 5 converts it to Bootstrap 5's native
 *    `offcanvas` component. Removing Jasny from the Bootstrap 5 block
 *    before then would break the off-canvas menu on every converted
 *    page in the interim.
 */
$admin_bs5_pages = array("upload", "hero_images", "upload_scoresheets", "change_user_password", "contacts", "dropoff");

/**
 * setup.php's step6 (setup/drop-off.setup.php) includes admin/dropoff.admin.php
 * directly and shares this same loader, but never sets $go via URL - it stays at
 * its "default" fallback (includes/url_variables.inc.php) throughout the whole
 * setup wizard, so $go alone can't distinguish "converted step" from "not yet
 * converted step" there. Recognize $section=="step6" explicitly once dropoff is
 * the only setup step that resolves to a converted page; add more step checks
 * here only as their own admin/*.admin.php file actually gets converted, not
 * ahead of it - step1-5/7-8 all still resolve to unconverted BS3 pages today.
 */
if ((in_array($go, $admin_bs5_pages)) || ($section == "step6")) { ?>

    <!-- ================= Bootstrap 5 asset set (converted admin pages) ================= -->
    <!-- Matches includes/load_cdn_libraries_public.inc.php's composition exactly where a
         public-facing equivalent exists to match against. -->

    <!-- Load jQuery / http://jquery.com/ -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>

    <!-- Load Fancybox / http://www.fancyapps.com -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/fancybox/3.5.7/jquery.fancybox.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/fancybox/3.5.7/jquery.fancybox.min.css"/>
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jquery-easing/1.3/jquery.easing.min.js"></script>
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jquery-mousewheel/3.1.13/jquery.mousewheel.min.js"></script>

<?php if (in_array($section,$datatables_load)) { ?>
    <!-- Load DataTables / https://www.datatables.net -->
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/2.1.8/css/dataTables.bootstrap5.min.css" />
    <script type="text/javascript" src="https://cdn.datatables.net/2.1.8/js/dataTables.min.js"></script>
    <script type="text/javascript" src="https://cdn.datatables.net/2.1.8/js/dataTables.bootstrap5.min.js"></script>
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/plug-ins/1.10.12/integration/font-awesome/dataTables.fontAwesome.css" />
<?php } ?>

    <!-- Load Bootstrap 5.X / https://getbootstrap.com/ -->
    <!-- Load Ninja Bootstrap (REPLACES and EXTENDS Bootstrap's CSS) / https://bootstrap.ninja/ninjabootstrap/ -->
    <link href="https://cdn.jsdelivr.net/gh/livecanvas-team/ninjabootstrap/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap 5's own JS bundle (popper + bootstrap.min.js) is deliberately NOT loaded
         here - it loads at the end of <body> instead (index.legacy.php, right before
         $js_app_url), matching index.pub.php's placement. Loading it here in <head> makes
         Bootstrap 5's jQuery-plugin shim run before document.body exists, which throws and
         silently breaks .modal()/.dropdown() for every jQuery caller (app.js's data-confirm
         handler among them) - found live during Phase 4 testing of upload_scoresheets.admin.php. -->

    <!-- Load Animate Styles / https://animate.style -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css">

    <!-- Load Font Awesome / https://fortawesome.github.io/Font-Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css" />
    <script type="text/javascript">
        window.FontAwesomeConfig = { autoReplaceSvg: false }
    </script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/js/all.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/v4-shims.min.css" />

    <!-- Load Tom Select / https://github.com/orchidjs/tom-select / https://tom-select.js.org/ -->
    <!-- Replaces bootstrap-select - a <select> opts in via class="form-select bootstrap-select",
         auto-upgraded by invoke.js, same as every pub/ page already does. -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/tom-select@2.3.1/dist/css/tom-select.bootstrap5.min.css">
    <script src="https://cdn.jsdelivr.net/npm/tom-select/dist/js/tom-select.complete.min.js"></script>

    <!-- Load Moment -->
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.4/moment-with-locales.min.js"></script>
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/moment-timezone/0.6.3/moment-timezone-with-data.min.js"></script>

    <!-- Load jquery Countdown -->
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jquery.countdown/2.2.0/jquery.countdown.min.js"></script>

<?php if ((($section == "admin") || (strpos($section, 'step') !== FALSE)) && (in_array($go,$tinymce_load))) { ?>

<?php if (ENABLE_MARKDOWN) { ?>
    <!-- Load Bootstrap Markdown Editor / https://github.com/inacho/bootstrap-markdown-editor -->
    <link rel="stylesheet" type="text/css" href="//cdnjs.cloudflare.com/ajax/libs/bootstrap-markdown-editor/2.0.2/css/bootstrap-markdown-editor.css">
    <script src="//cdnjs.cloudflare.com/ajax/libs/ace/1.1.3/ace.js"></script>
    <script src="//cdnjs.cloudflare.com/ajax/libs/marked/0.3.2/marked.min.js"></script>
    <script src="//cdnjs.cloudflare.com/ajax/libs/bootstrap-markdown-editor/2.0.2/js/bootstrap-markdown-editor.js"></script>
  <?php } else { ?>
    <!-- Load TinyMCE / https://www.tinymce.com/ -->
    <!-- Still 4.9.11 here too - not Bootstrap-version-coupled, its own major-version
         upgrade is a deliberately separate task per the ledger's Phase 0 card. -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/tinymce/4.9.11/tinymce.min.js"></script>
    <script src="<?php echo $js_url; ?>tinymce-init.min.js"></script>
  <?php } ?>
<?php } ?>

<?php if (($logged_in) && ((isset($_SESSION['userLevel'])) && ($_SESSION['userLevel'] <= 1)) && (strpos($section, 'step') === FALSE)) { ?>
    <!-- Load Jasny Off-Canvas Menu for Admin / http://www.jasny.net/bootstrap -->
    <!-- Still loaded here too, not yet replaced with Bootstrap 5's native `offcanvas` -
         sections/nav.sec.php (shared/unconditional on every admin page) still emits
         Jasny-dependent markup until Phase 5 converts it. See file header comment. -->
    <link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/jasny-bootstrap/3.1.3/css/jasny-bootstrap.min.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jasny-bootstrap/3.1.3/js/jasny-bootstrap.min.js"></script>
  <?php if ((($section == "admin") || (strpos($section, 'step') !== FALSE)) && (($go == "upload") || ($go == "upload_scoresheets"))) { ?>
    <!-- Load DropZone / http://www.dropzonejs.com -->
    <link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/dropzone/5.9.3/min/dropzone.min.css" />
    <script src="https://cdnjs.cloudflare.com/ajax/libs/dropzone/5.9.3/min/dropzone.min.js"></script>
    <script src="<?php echo $js_url; ?>dz.min.js"></script>
  <?php } ?>
<?php } ?>

<?php if (($section == "register") || ($section == "step1") || (($section == "login") && ($go == "password") && ($action == "reset-password")) || (($section == "user") && ($go == "account") && ($action == "password")) || (($section == "admin") && ($go == "change_user_password") && ($action == "edit")) || (($section == "admin") && ($action == "register"))) { ?>
    <!-- Load jQuery Password Strength Meter for Twitter Bootstrap / https://github.com/ablanco/jquery.pwstrength.bootstrap -->
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/zxcvbn/4.4.2/zxcvbn.js"></script>
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/pwstrength-bootstrap/3.1.3/pwstrength-bootstrap.min.js"></script>
<?php } ?>

<?php } else { ?>

    <!-- ================= Bootstrap 3 asset set (legacy, not yet converted) ================= -->

    <!-- Load jQuery / http://jquery.com/ -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.2.4/jquery.min.js"></script>

    <!-- Load Bootstrap / http://www.getbootsrap.com -->
    <link rel="stylesheet" type="text/css" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css" />
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>

    <!--[if lt IE 9]>
      <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
      <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->

<?php if (in_array($section,$datatables_load)) { ?>
    <!-- Load DataTables / https://www.datatables.net -->
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.12/css/dataTables.bootstrap.min.css" />
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/plug-ins/1.10.12/integration/font-awesome/dataTables.fontAwesome.css" />
    <script type="text/javascript" src="https://cdn.datatables.net/1.10.12/js/jquery.dataTables.min.js"></script>
    <script type="text/javascript" src="https://cdn.datatables.net/1.10.12/js/dataTables.bootstrap.min.js"></script>
<?php } ?>

    <!-- Load Fancybox / http://www.fancyapps.com -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/fancybox/3.5.7/jquery.fancybox.js"></script>
    <link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/fancybox/3.5.7/jquery.fancybox.min.css"/>
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jquery-easing/1.3/jquery.easing.min.js"></script>
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jquery-mousewheel/3.1.13/jquery.mousewheel.min.js"></script>

    <!-- Load Moment -->
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.4/moment-with-locales.min.js"></script>

    <!-- Load Bootstrap DateTime Picker / http://eonasdan.github.io/bootstrap-datetimepicker/ -->
    <!-- Version 4.17.X have display issues. Keep 4.15.35 as it is most compatible with BS 3.X -->
    <link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datetimepicker/4.15.35/css/bootstrap-datetimepicker.min.css" />
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datetimepicker/4.15.35/js/bootstrap-datetimepicker.min.js"></script>

<?php if ((($section == "admin") || (strpos($section, 'step') !== FALSE)) && (in_array($go,$tinymce_load))) { ?>

<?php if (ENABLE_MARKDOWN) { ?>
    <!-- Load Bootstrap Markdown Editor / https://github.com/inacho/bootstrap-markdown-editor -->
    <link rel="stylesheet" type="text/css" href="//cdnjs.cloudflare.com/ajax/libs/bootstrap-markdown-editor/2.0.2/css/bootstrap-markdown-editor.css">
    <script src="//cdnjs.cloudflare.com/ajax/libs/ace/1.1.3/ace.js"></script>
    <script src="//cdnjs.cloudflare.com/ajax/libs/marked/0.3.2/marked.min.js"></script>
    <script src="//cdnjs.cloudflare.com/ajax/libs/bootstrap-markdown-editor/2.0.2/js/bootstrap-markdown-editor.js"></script>
  <?php } else { ?>
    <!-- Load TinyMCE / https://www.tinymce.com/ -->
    <!-- 4.9.11 is the final 4.x release - EOL, no further patches ever coming.
         Deliberately not moved to 5+: that's a different UI/dialog architecture
         (new toolbar framework, new modal system, changed init config), and
         this loads only into Admin's Bootstrap 3.X pages - the same class of
         dialog/z-index clash risk already hit with bootstrap-datetimepicker
         4.17.X above. A 5+ upgrade needs real in-browser verification against
         the admin UI, not just a version bump; tracked as a separate task,
         same status as the Admin Bootstrap 3.X -> 5 migration itself. -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/tinymce/4.9.11/tinymce.min.js"></script>
    <script src="<?php echo $js_url; ?>tinymce-init.min.js"></script>
  <?php } ?>
<?php } ?>

<?php if (($logged_in) && ((isset($_SESSION['userLevel'])) && ($_SESSION['userLevel'] <= 1)) && (strpos($section, 'step') === FALSE)) { ?>
    <!-- Load Jasny Off-Canvas Menu for Admin / http://www.jasny.net/bootstrap -->
    <link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/jasny-bootstrap/3.1.3/css/jasny-bootstrap.min.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jasny-bootstrap/3.1.3/js/jasny-bootstrap.min.js"></script>
  <?php if ((($section == "admin") || (strpos($section, 'step') !== FALSE)) && (($go == "upload") || ($go == "upload_scoresheets"))) { ?>
    <!-- Load DropZone / http://www.dropzonejs.com -->
    <link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/dropzone/5.9.3/min/dropzone.min.css" />
    <script src="https://cdnjs.cloudflare.com/ajax/libs/dropzone/5.9.3/min/dropzone.min.js"></script>
    <script src="<?php echo $js_url; ?>dz.min.js"></script>
  <?php } ?>
<?php } ?>

    <!-- Load Bootstrap Form Validator / http://1000hz.github.io/bootstrap-validator -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/1000hz-bootstrap-validator/0.9.0/validator.min.js"></script>

    <!-- Load Bootstrap-Select / https://developer.snapappointments.com/bootstrap-select -->
    <link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.13.18/css/bootstrap-select.min.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.13.18/js/bootstrap-select.min.js"></script>

    <!-- Load Font Awesome / https://fortawesome.github.io/Font-Awesome -->
    <link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css" />
    <link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/v4-shims.min.css" />

<?php if (($section == "register") || ($section == "step1") || (($section == "login") && ($go == "password") && ($action == "reset-password")) || (($section == "user") && ($go == "account") && ($action == "password")) || (($section == "admin") && ($go == "change_user_password") && ($action == "edit")) || (($section == "admin") && ($action == "register"))) { ?>
    <!-- Load jQuery Password Strength Meter for Twitter Bootstrap / https://github.com/ablanco/jquery.pwstrength.bootstrap -->
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/zxcvbn/4.4.2/zxcvbn.js"></script>
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/pwstrength-bootstrap/2.1.0/pwstrength-bootstrap.min.js"></script>
<?php } ?>

<?php } ?>
