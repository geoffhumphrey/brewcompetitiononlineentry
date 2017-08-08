<?php
// For storage in DB and update script use
$current_version = "2.1.10.0";

// For on-screen display and consistency
$current_version_display = "2.1.10";

// Add if pre-release - alpha, beta, etc., otherwise leave empty
$current_version_display_append = "(Beta)";
if (!empty($current_version_display_append)) $current_version_display .= " ".$current_version_display_append;

 // Do not change date if going from pre-release to full - doing so will trigger a false positive in the update script
$current_version_date_display = "2017-07-25";

// Convert to Unix timestamp
$current_version_date = strtotime($current_version_date_display);
?>