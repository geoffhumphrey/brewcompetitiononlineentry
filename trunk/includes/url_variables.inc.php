<?php

$id = "default";
if (isset($_GET['id'])) {
  $id = (get_magic_quotes_gpc()) ? $_GET['id'] : addslashes($_GET['id']);
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

$sort = "brewCategorySort, brewSubCategory, brewBrewerLastName";
if (isset($_GET['sort'])) {
  $sort = (get_magic_quotes_gpc()) ? $_GET['sort'] : addslashes($_GET['sort']);
}

$psort = "default";
if (isset($_GET['psort'])) {
  $psort = (get_magic_quotes_gpc()) ? $_GET['psort'] : addslashes($_GET['psort']);
}

$dir = "ASC";
if (isset($_GET['dir'])) {
  $dir = (get_magic_quotes_gpc()) ? $_GET['dir'] : addslashes($_GET['dir']);
}

$bid = "default";
if (isset($_GET['bid'])) {
  $bid = (get_magic_quotes_gpc()) ? $_GET['bid'] : addslashes($_GET['bid']);
}

$limit = "999999";
if (isset($_GET['limit'])) {
  $limit = (get_magic_quotes_gpc()) ? $_GET['limit'] : addslashes($_GET['limit']);
}

$view = "default";
if (isset($_GET['view'])) {
  $view = (get_magic_quotes_gpc()) ? $_GET['view'] : addslashes($_GET['view']);
}

?>