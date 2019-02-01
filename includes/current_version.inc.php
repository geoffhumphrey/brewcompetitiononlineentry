<?php
// For storage in DB and update script use
$current_version = "2.1.16.0";

// For on-screen display and consistency
$current_version_display = "2.1.16";

// Add if pre-release - alpha, beta, etc., otherwise leave empty
$current_version_display_append = "";
if (!empty($current_version_display_append)) $current_version_display .= " ".$current_version_display_append;

// Change date for each pre-release and release. Will trigger a force update.
$current_version_date_display = "2019-02-01";

// Convert current version date to Unix timestamp
$current_version_date = strtotime($current_version_date_display);
?>