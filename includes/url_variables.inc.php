<?php

/*
Checked Single
2016-06-06
*/

$id = "default";
if (isset($_GET['id'])) {
  $id = addslashes($_GET['id']);
}

$uid = "default";
if (isset($_GET['uid'])) {
  $uid = addslashes($_GET['uid']);
}

$section = "default";
if (isset($_GET['section'])) {
  $section = addslashes($_GET['section']);
}

$action = "default";
if (isset($_GET['action'])) {
  $action = addslashes($_GET['action']);
}

$msg = "default";
if (isset($_GET['msg'])) {
  $msg = addslashes($_GET['msg']);
}

$go = "default";
if (isset($_GET['go'])) {
  $go = addslashes($_GET['go']);
}

$username = "default";
if (isset($_GET['username'])) {
  $username = addslashes($_GET['username']);
}

$dbTable = "default";
if (isset($_GET['dbTable'])) {
  $dbTable = addslashes($_GET['dbTable']);
}

$filter = "default";
if (isset($_GET['filter'])) {
  $filter = addslashes($_GET['filter']);
}

$bid = "default";
if (isset($_GET['bid'])) {
  $bid = addslashes($_GET['bid']);
}

$view = "default";
if (isset($_GET['view'])) {
  $view = addslashes($_GET['view']);
}

// ------------------ Token for authorized password changes ------------------
$token = "default";
if (isset($_GET['token'])) {
  $token = addslashes($_GET['token']);
}

// ------------------ Only apply to print functions ------------------
$tb = "default";
if (isset($_GET['tb'])) {
  $tb = addslashes($_GET['tb']);
}

$psort = "default";
if (isset($_GET['psort'])) {
  $psort = addslashes($_GET['psort']);
}

// ------------------ Only applies to process funtions ------------------
$limit = "999999";
if (isset($_GET['limit'])) {
  $limit = addslashes($_GET['limit']);
}

// ------------------ Only applies to function utilized in the /includes/data_cleanup.inc.php file ------------------
$purge = "default";
if (isset($_GET['purge'])) {
  $purge = addslashes($_GET['purge']);
}

// ------------------ The following only apply to printing of specific location and rounds in admin/default.admin.php ------------------
$round = "default";
if (isset($_GET['round'])) {
  $round = addslashes($_GET['round']);
}

$location = "default";
if (isset($_GET['location'])) {
  $location = addslashes($_GET['location']);
}

// ------------------ Apparently unused ------------------
$inserted = "default";
if (isset($_GET['inserted'])) {
  $inserted = addslashes($_GET['inserted']);
}

$dir = "ASC";
if (isset($_GET['dir'])) {
  $dir = addslashes($_GET['dir']);
}

$pg = "default";
if (isset($_GET['pg'])) {
  $pg = addslashes($_GET['pg']);
}

$sort = "brewCategorySort, brewSubCategory, brewBrewerLastName";
if (isset($_GET['sort'])) {
  $sort = addslashes($_GET['sort']);
}

?>
