<?php
/**
 * The get_magic_quotes_gpc() function is deprecated in php 7.2
 * and elimnated in php 8.0.
 * Removed and replaced with function defined in paths.php file 
 * (this function needed to be defined early in processing).
 * 
 * @author Geoff Humphrey
 */

$id = "default";
$uid = "default";
$cid = "default";
$section = "default";
$action = "default";
$msg = "default";
$go = "default";
$username = "default";
$dbTable = "default";
$filter = "default";
$bid = "default";
$view = "default";
$token = "default";
$tb = "default";
$psort = "default";
$limit = "999999";
$purge = "default";
$round = "default";
$location = "default";
$sort = "default";
$pg = "default";
$dir = "ASC";
$inserted = "default";

if (isset($_GET['id'])) $id = sterilize($_GET['id']);
if (isset($_GET['uid'])) $uid = sterilize($_GET['uid']);
if (isset($_GET['cid'])) $cid = sterilize($_GET['cid']);
if (isset($_GET['section'])) $section = sterilize($_GET['section']);
if (isset($_GET['action'])) $action = sterilize($_GET['action']);
if (isset($_GET['msg'])) $msg = sterilize($_GET['msg']);
if (isset($_GET['go'])) $go = sterilize($_GET['go']);
if (isset($_GET['username'])) $username = sterilize($_GET['username']);
if (isset($_GET['dbTable'])) $dbTable = sterilize($_GET['dbTable']);
if (isset($_GET['filter'])) $filter = sterilize($_GET['filter']);
if (isset($_GET['bid'])) $bid = sterilize($_GET['bid']);
if (isset($_GET['view'])) $view = sterilize($_GET['view']);
if (isset($_GET['token'])) $token = sterilize($_GET['token']);
if (isset($_GET['tb'])) $tb = sterilize($_GET['tb']);
if (isset($_GET['psort'])) $psort = sterilize($_GET['psort']);
if (isset($_GET['limit'])) $limit = sterilize($_GET['limit']);
if (isset($_GET['purge'])) $purge = sterilize($_GET['purge']);
if (isset($_GET['round'])) $round = sterilize($_GET['round']);
if (isset($_GET['location'])) $location = sterilize($_GET['location']);
if (isset($_GET['sort'])) $sort = sterilize($_GET['sort']);
if (isset($_GET['pg'])) $pg = sterilize($_GET['pg']);
if (isset($_GET['dir'])) $dir = sterilize($_GET['dir']);
if (isset($_GET['inserted'])) $inserted = sterilize($_GET['inserted']);
if (isset($_GET['redirect-url'])) $redirect_url = sterilize($_GET['redirect-url']);
?>