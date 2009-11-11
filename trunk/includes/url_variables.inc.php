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

?>