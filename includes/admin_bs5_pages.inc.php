<?php
/**
 * Admin Bootstrap 3->5 migration: the list of admin $go values whose markup
 * has actually been converted to Bootstrap 5. Shared by site/bootstrap.php
 * (theme selection - converted pages load pub/'s default-3.min.css/
 * common-3.min.css pair instead of the per-competition admin theme) and
 * includes/load_cdn_libraries_admin.inc.php (asset stack selection), so
 * both stay in sync off one list rather than two independently-maintained
 * copies. See the BCOE&M Enhancement Ledger, "Admin BS5 Migration" cards.
 */
$admin_bs5_pages = array("upload", "hero_images", "upload_scoresheets", "change_user_password", "contacts", "dropoff", "special_best", "style_types", "special_best_data", "sponsors", "mods", "styles", "judging_preferences", "contest_info", "payments", "judging_scores_bos", "judging_scores", "entries");

/**
 * admin/site_preferences.admin.php ($go == "preferences") is one PHP file
 * split into 5 tabs (General/default, Entries, Email, Payment, Best) via
 * $action - all sharing the single $go value above, so a plain $go-based
 * allowlist entry would switch every tab's asset stack/theme at once,
 * including tabs whose markup hasn't been converted yet. This lists which
 * $action values have actually been converted, so each tab can ship (and be
 * tested) independently - add to it as each tab's own conversion is
 * finished, not ahead of it. Both consumers above check
 * `($go == "preferences") && (in_array($action, $admin_bs5_preferences_actions))`
 * as an OR-alternative to the plain $admin_bs5_pages check.
 */
$admin_bs5_preferences_actions = array("default", "entries", "email", "payment", "best");
