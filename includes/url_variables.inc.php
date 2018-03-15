<?php

/*
Checked Single
2016-06-06
*/

$id = "default";
if (isset($_GET['id'])) {
  $id = (get_magic_quotes_gpc()) ? $_GET['id'] : addslashes($_GET['id']);
}

$uid = "default";
if (isset($_GET['uid'])) {
  $uid = (get_magic_quotes_gpc()) ? $_GET['uid'] : addslashes($_GET['uid']);
}

$section = "default";
if (isset($_GET['section'])) {
  $section = (get_magic_quotes_gpc()) ? $_GET['section'] : addslashes($_GET['section']);
}

$action = "default";
if (isset($_GET['action'])) {
  $action = (get_magic_quotes_gpc()) ? $_GET['action'] : addslashes($_GET['action']);
}

$msg = "default";
if (isset($_GET['msg'])) {
  $msg = (get_magic_quotes_gpc()) ? $_GET['msg'] : addslashes($_GET['msg']);
}

$go = "default";
if (isset($_GET['go'])) {
  $go = (get_magic_quotes_gpc()) ? $_GET['go'] : addslashes($_GET['go']);
}

$username = "default";
if (isset($_GET['username'])) {
  $username = (get_magic_quotes_gpc()) ? $_GET['username'] : addslashes($_GET['username']);
}

$dbTable = "default";
if (isset($_GET['dbTable'])) {
  $dbTable = (get_magic_quotes_gpc()) ? $_GET['dbTable'] : addslashes($_GET['dbTable']);
}

$filter = "default";
if (isset($_GET['filter'])) {
  $filter = (get_magic_quotes_gpc()) ? $_GET['filter'] : addslashes($_GET['filter']);
}

$bid = "default";
if (isset($_GET['bid'])) {
  $bid = (get_magic_quotes_gpc()) ? $_GET['bid'] : addslashes($_GET['bid']);
}

$view = "default";
if (isset($_GET['view'])) {
  $view = (get_magic_quotes_gpc()) ? $_GET['view'] : addslashes($_GET['view']);
}

// ------------------ Token for authorized password changes ------------------
$token = "default";
if (isset($_GET['token'])) {
  $token = (get_magic_quotes_gpc()) ? $_GET['token'] : addslashes($_GET['token']);
}

// ------------------ Only apply to print functions ------------------
$tb = "default";
if (isset($_GET['tb'])) {
  $tb = (get_magic_quotes_gpc()) ? $_GET['tb'] : addslashes($_GET['tb']);
}

$psort = "default";
if (isset($_GET['psort'])) {
  $psort = (get_magic_quotes_gpc()) ? $_GET['psort'] : addslashes($_GET['psort']);
}

// ------------------ Only applies to process funtions ------------------
$limit = "999999";
if (isset($_GET['limit'])) {
  $limit = (get_magic_quotes_gpc()) ? $_GET['limit'] : addslashes($_GET['limit']);
}

// ------------------ Only applies to function utilized in the /includes/data_cleanup.inc.php file ------------------
$purge = "default";
if (isset($_GET['purge'])) {
  $purge = (get_magic_quotes_gpc()) ? $_GET['purge'] : addslashes($_GET['purge']);
}

// ------------------ The following only apply to printing of specific location and rounds in admin/default.admin.php ------------------
$round = "default";
if (isset($_GET['round'])) {
  $round = (get_magic_quotes_gpc()) ? $_GET['round'] : addslashes($_GET['round']);
}

$location = "default";
if (isset($_GET['location'])) {
  $location = (get_magic_quotes_gpc()) ? $_GET['location'] : addslashes($_GET['location']);
}

// ------------------ Apparently unused ------------------
$inserted = "default";
if (isset($_GET['inserted'])) {
  $inserted = (get_magic_quotes_gpc()) ? $_GET['inserted'] : addslashes($_GET['inserted']);
}

$dir = "ASC";
if (isset($_GET['dir'])) {
  $dir = (get_magic_quotes_gpc()) ? $_GET['dir'] : addslashes($_GET['dir']);
}

$pg = "default";
if (isset($_GET['pg'])) {
  $pg = (get_magic_quotes_gpc()) ? $_GET['pg'] : addslashes($_GET['pg']);
}

$sort = "brewCategorySort, brewSubCategory, brewBrewerLastName";
if (isset($_GET['sort'])) {
  $sort = (get_magic_quotes_gpc()) ? $_GET['sort'] : addslashes($_GET['sort']);
}

?>