<?php
/**
 * Hero Images Setup Script
 * 
 * Adds prefsHeroImages column to site_preferences table if it doesn't exist
 * Initializes default values
 * 
 */

// This script would be run as part of the update process
// It adds the prefsHeroImages column to site_preferences if needed

$add_column_sql = "ALTER TABLE `".$prefix."site_preferences` ADD COLUMN `prefsHeroImages` LONGTEXT DEFAULT NULL COMMENT 'JSON array of hero image filename => active status' AFTER `prefsRegistrationFormClose`;";

// Check if column exists before trying to add it
$check_column = "SELECT COLUMN_NAME FROM INFORMATION_SCHEMA.COLUMNS WHERE TABLE_NAME = '".$prefix."site_preferences' AND COLUMN_NAME = 'prefsHeroImages';";
$result = mysqli_query($connection, $check_column);

if (mysqli_num_rows($result) == 0) {
    // Column doesn't exist, add it
    if (mysqli_query($connection, $add_column_sql)) {
        // Column added successfully
        // Initialize with default values
        include(LIB.'hero_images.lib.php');
        initialize_hero_images_preferences($connection, $prefix);
    }
}

?>
