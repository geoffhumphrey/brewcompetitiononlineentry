<?php
/**
 * Module:      rules.pub.php
 * Description: This module houses the intallation's landing page that includes
 *              information about the competition, registration dates/info, and
 *              winner display after all judging dates have passed.
 */

$primary_page_info = "";
$page_info = "";
$page_info10 = "";
$page_info20 = "";
$page_info30 = "";
$header1_1 = "";
$header1_10 = "";
$header1_20 = "";
$header1_30 = "";

$message1 = sprintf("<div class=\"alert alert-warning\"><span class=\"fa fa-lg fa-exclamation-triangle\"> %s <a href='index.php?section=admin&amp;action=add&amp;go=dropoff'>%s</a></div>",$default_page_text_000,$default_page_text_001);
$message2 = sprintf("<div class=\"alert alert-warning\"><span class=\"fa fa-lg fa-exclamation-triangle\"> %s <a href='index.php?section=admin&amp;action=add&amp;go=judging'>%s</a></div>",$default_page_text_002,$default_page_text_003);

include('reg_open.pub.php');

?>