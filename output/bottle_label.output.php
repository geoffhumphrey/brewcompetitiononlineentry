<?php

// Redirect if directly accessed without authenticated session
if (!isset($_SESSION['loginUsername'])) {
    $redirect = "../../403.php";
    $redirect_go_to = sprintf("Location: %s", $redirect);
    header($redirect_go_to);
    exit();
}

// Set up vars
$page_info0 = "";
$page_info1 = "";

$_POST = filter_input_array(INPUT_POST,FILTER_SANITIZE_NUMBER_INT);

if (isset($_SESSION['loginUsername'])) {

    // Get id array from $_POST of the entry label(s) user wishes to print,
    // or a single id - render_brewer_bottle_labels() always expects an array
    // (null means "every entry", used only by the admin batch-reprint flow).
    $entry_arr = array();

    if ($id == "default") {
      foreach($_POST['id'] as $entry_id) {
        $entry_id = filter_var($entry_id,FILTER_SANITIZE_NUMBER_INT);
        $entry_arr[] = $entry_id;
      }
    }

    else $entry_arr[] = $id;

    if ($bid == "default") $bid = $_SESSION['uid'];

    // Ownership check: a non-staff user may only print labels for their own entries.
    if (((!isset($_SESSION['userLevel'])) || ($_SESSION['userLevel'] > 1)) && ($bid != $_SESSION['uid'])) {
        header("Location: ../../403.php");
        exit();
    }

    $label_prefs = get_bottle_label_prefs();

    $page_info0 = sprintf("<strong>%s</strong> %s", strtoupper($bottle_labels_001), $bottle_labels_008);
    $page_info1 = render_brewer_bottle_labels($bid, $entry_arr, $label_prefs);

}

else {
  $page_info0 = "No user recognized.";
  $page_info1 = $bottle_labels_000;
}

include (OUTPUT.'bottle_label_document.inc.php');

?>
