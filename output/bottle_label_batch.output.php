<?php
/**
 * Module:      bottle_label_batch.output.php
 * Description: Admin-only batch reprint of bottle labels for a set of
 *              selected participants (one combined print job, all of each
 *              participant's entries, a forced page break between
 *              participants, and - only when the active prefsEntryForm is an
 *              anonymous variant - an identifying line outside each label's
 *              own border). Added to let an admin bulk-reprint
 *              correct anonymous labels for bottles that already shipped or
 *              were dropped off under a wrong (non-anonymous) label.
 *
 *              Reached only via includes/output.inc.php when
 *              $section == "entry-form-batch".
 */

// Redirect if directly accessed without authenticated session, or by anyone
// other than the top-tier admin - this feature surfaces participant identity
// specifically to work around anonymous-judging obfuscation, so it's held to
// the same tier this codebase already uses elsewhere to gate identity/
// judging-number-linked tools from non-owner staff (see $_SESSION['userAdminObfuscate']
// usage in admin/default.admin.php and admin/entries.admin.php).
if ((!isset($_SESSION['loginUsername'])) || ($_SESSION['userAdminObfuscate'] != 0)) {
    $redirect = "../../403.php";
    $redirect_go_to = sprintf("Location: %s", $redirect);
    header($redirect_go_to);
    exit();
}

$pro_edition = $_SESSION['prefsProEdition'];

$_POST = filter_input_array(INPUT_POST,FILTER_SANITIZE_NUMBER_INT);

$bid_arr = array();
if (!empty($_POST['bid'])) {
    foreach ($_POST['bid'] as $one_bid) {
        $bid_arr[] = filter_var($one_bid,FILTER_SANITIZE_NUMBER_INT);
    }
}

$label_prefs = get_bottle_label_prefs();

$page_info0 = "";
$page_info1 = "";

$rendered_count = 0;

foreach ($bid_arr as $one_bid) {

    $db_conn->where('uid', $one_bid);
    $row_brewer = $db_conn->getOne($prefix."brewer");

    // Selected participant no longer exists (removed between modal load and
    // submit) - skip silently rather than render an empty, confusing block.
    if (!$row_brewer) continue;

    $header_text = "Bottle labels for ";
    if ($pro_edition == 1) $header_text .= $row_brewer['brewerBreweryName'];
    else $header_text .= $row_brewer['brewerLastName'].", ".$row_brewer['brewerFirstName'];

    // render_brewer_bottle_labels() only actually prints $header_text when
    // the active prefsEntryForm is an anonymous variant (label_prefs['anon'])
    // - harmless to always compute and pass it here either way.
    $brewer_block = render_brewer_bottle_labels($one_bid, null, $label_prefs, $header_text);

    // render_brewer_bottle_labels() returns '' when this brewer has zero
    // entries - skip the forced page break too, so a stale selection doesn't
    // waste a blank page in the middle of the batch.
    if ($brewer_block === '') continue;

    if ($rendered_count > 0) $page_info1 .= "<div style=\"page-break-before: always\"></div>";
    $page_info1 .= $brewer_block;
    $rendered_count++;

}

include (OUTPUT.'bottle_label_document.inc.php');

?>
