<?php

/**
 * Hero Images Library Functions
 * 
 * Manages hero background image preferences stored in site_preferences as JSON
 * Images are 3000x500 pixels optimized for background display
 * 
 * Image filenames use prefix pattern: [category]-[name]_3000x500.jpg
 * Categories: 0=misc, 1=beer, 2=cider, 3=mead
 */


/**
 * Scan /images folder and get all available hero images
 * Returns array grouped by category
 * 
 * @return array Array of images organized by category (0=misc, 1=beer, 2=cider, 3=mead)
 */
function get_all_available_hero_images() {
    $images_dir = ROOT.DIRECTORY_SEPARATOR."images";
    $hero_images = array(
        "0" => array(),  // Misc
        "1" => array(),  // Beer
        "2" => array(),  // Cider
        "3" => array(),  // Mead
    );

    if (is_dir($images_dir)) {
        $files = scandir($images_dir);
        foreach ($files as $file) {
            if ($file === '.' || $file === '..') continue;
            
            // Look for hero images (3000x500 pattern)
            if (preg_match('/3000x500\.jpg$/i', $file)) {
                // Determine category from filename prefix
                if (preg_match('/^0-/', $file)) {
                    $hero_images["0"][] = $file;
                } elseif (preg_match('/^1-/', $file)) {
                    $hero_images["1"][] = $file;
                } elseif (preg_match('/^2-/', $file)) {
                    $hero_images["2"][] = $file;
                } elseif (preg_match('/^3-/', $file)) {
                    $hero_images["3"][] = $file;
                }
            }
        }
    }

    // Sort images within each category
    foreach ($hero_images as &$category) {
        sort($category);
    }

    return $hero_images;
}

/**
 * Get active hero images from site_preferences
 * Filters by style types passed in
 * 
 * @param mysqli $connection Database connection
 * @param string $prefix Database table prefix
 * @param array $style_types Array of style type IDs (0, 1, 2, 3)
 * @return array Array of filenames to randomly select from
 */
function get_active_hero_images($connection, $prefix, $style_types = array()) {
    $active_images = array();

    // Get preferences from site_preferences
    $prefs_table = $prefix."site_preferences";
    $query = sprintf("SELECT prefsHeroImages FROM %s LIMIT 1", $prefs_table);
    $result = mysqli_query($connection, $query);
    
    if (!$result) {
        return $active_images; // Return empty if query fails
    }

    $row = mysqli_fetch_assoc($result);

    if ($row && !empty($row['prefsHeroImages'])) {
        $hero_prefs = json_decode($row['prefsHeroImages'], true);
        
        if (is_array($hero_prefs)) {
            // Always include misc (0) images
            $allowed_types = array("0");
            
            // Add other types based on style_types passed in
            foreach ($style_types as $type) {
                $type_str = (string)$type;
                if ($type_str != "0" && in_array($type_str, array("1", "2", "3"))) {
                    $allowed_types[] = $type_str;
                }
            }

            // Build list of active images from allowed categories
            foreach ($hero_prefs as $image => $is_active) {
                if ($is_active === true || $is_active === "true" || $is_active === 1 || $is_active === "1") {
                    // Extract category from image filename
                    $parts = explode("-", $image);
                    if (count($parts) > 0 && in_array($parts[0], $allowed_types)) {
                        $active_images[] = $image;
                    }
                }
            }
        }
    }

    return $active_images;
}

/**
 * Save hero images preferences to site_preferences
 * Updates prefsHeroImages field with JSON array
 * 
 * @param mysqli $connection Database connection
 * @param string $prefix Database table prefix
 * @param array $images_array Associative array of filename => active_status
 * @return bool True on success, false on failure
 */
function save_hero_images_preferences($connection, $prefix, $images_array) {
    $prefs_table = $prefix."site_preferences";
    $json_data = json_encode($images_array);
    
    // Escape for safe SQL query
    $json_safe = mysqli_real_escape_string($connection, $json_data);
    
    $query = sprintf("UPDATE %s SET prefsHeroImages = '%s'", $prefs_table, $json_safe);
    $result = mysqli_query($connection, $query);

    return ($result) ? true : false;
}

/**
 * Initialize hero images in site_preferences
 * Sets all available images to active in prefsHeroImages
 * 
 * @param mysqli $connection Database connection
 * @param string $prefix Database table prefix
 * @return bool True on success, false on failure
 */
function initialize_hero_images_preferences($connection, $prefix) {
    $all_images = get_all_available_hero_images();
    $images_array = array();

    // Flatten and set all to active
    foreach ($all_images as $category => $images) {
        foreach ($images as $image) {
            $images_array[$image] = true;
        }
    }

    return save_hero_images_preferences($connection, $prefix, $images_array);
}

?>

