<?php

/**
 * Hero Images Library Functions
 *
 * Manages hero background image preferences stored in the
 * prefsHeroImages column of the preferences table.
 */

/**
 * Load preferences from the preferences table.
 * Returns defaults when no stored value exists or it contains invalid JSON.
 */
function load_hero_images_preferences($db_conn, $prefix, $all_images) {
    $default_prefs = get_default_hero_preferences($all_images);

    try {
        $db_conn->where('id', 1);
        $row_prefs = $db_conn->getOne($prefix."preferences", "prefsHeroImages");
    }
    catch (Exception $e) {
        return $default_prefs;
    }

    if ((!$row_prefs) || (empty($row_prefs['prefsHeroImages']))) {
        return $default_prefs;
    }

    $decoded = json_decode($row_prefs['prefsHeroImages'], true);
    if (!is_array($decoded)) {
        return $default_prefs;
    }

    // Keep only currently-discovered images, preserving choices and enabling new files by default.
    $normalized = array();
    foreach ($default_prefs as $image => $enabled_default) {
        if (array_key_exists($image, $decoded)) {
            $normalized[$image] = $decoded[$image];
        }
        else {
            $normalized[$image] = true;
        }
    }

    return $normalized;
}

/**
 * Map a filename to hero category id string.
 * Supported prefixes:
 * - Numeric: 0-, 1-, 2-, 3-
 * - Named: misc-, beer-, cider-, mead-
 */
function hero_image_category_from_filename($filename) {
    $filename = strtolower($filename);

    if (preg_match('/^([0-3])-/', $filename, $matches)) {
        return $matches[1];
    }

    if (strpos($filename, 'misc-') === 0) return "0";
    if (strpos($filename, 'beer-') === 0) return "1";
    if (strpos($filename, 'cider-') === 0) return "2";
    if (strpos($filename, 'mead-') === 0) return "3";

    // Default unknown naming patterns to miscellaneous.
    return "0";
}

/**
 * Determine whether a file in /images is a hero image candidate.
 *
 * Accept if either:
 * - filename ends with _3000x500 and a supported extension
 * - filename uses a known hero category prefix and a supported extension
 */
function is_hero_image_candidate($filename) {
    if (!preg_match('/\.(jpg|jpeg|png|gif|webp)$/i', $filename)) {
        return false;
    }

    if (preg_match('/_3000x500\.(jpg|jpeg|png|gif|webp)$/i', $filename)) {
        return true;
    }

    if (preg_match('/^(misc|beer|cider|mead)-/i', $filename)) {
        return true;
    }

    if (preg_match('/^[0-3]-/', $filename)) {
        return true;
    }

    // Filename conventions are not required: include wide banner-like images.
    $image_path = USER_IMAGES.$filename;
    if (file_exists($image_path)) {
        $size = @getimagesize($image_path);
        if (($size !== false) && isset($size[0]) && isset($size[1]) && ((int)$size[1] > 0)) {
            $width = (int)$size[0];
            $height = (int)$size[1];
            $ratio = $width / $height;

            if (($width >= 1200) && ($ratio >= 3.5)) {
                return true;
            }
        }
    }

    return false;
}

/**
 * Build default image preference map with all available images set active.
 */
function get_default_hero_preferences($all_images) {
    $images_array = array();

    foreach ($all_images as $images) {
        foreach ($images as $image) {
            $images_array[$image] = true;
        }
    }

    return $images_array;
}

/**
 * Filenames bundled with the app itself (shipped in /images, not uploaded)
 * that are always offered as toggleable hero images - a failsafe fallback
 * set so hero banners can never end up with nothing to show. These can be
 * enabled/disabled via the admin UI like any other hero image, but never
 * deleted - see is_bundled_hero_image() / hero_image_directory(). Same
 * misc-/beer-/cider-/mead- naming convention as uploaded images, so
 * hero_image_category_from_filename() categorizes them the same way.
 */
function get_bundled_hero_images() {
    return array(
        "misc-cropped-bottles_3000x500.webp",
        "misc-brussels-bottles_3000x500.webp",
        "misc-plzen-fermenters_3000x500.webp",
        "misc-bottles_3000x500.webp",
        "beer-barley-malt_3000x500.webp",
        "beer-brussels-barrels_3000x500.webp",
        "beer-hop-cones_3000x500.webp",
        "beer-kegs_3000x500.webp",
        "beer-munich-mugs_3000x500.webp",
        "beer-on-bar_3000x500.webp",
        "cider-bottles_3000x500.webp",
        "mead-bottles_3000x500.webp",
    );
}

/**
 * Whether $filename is one of the app's bundled fallback hero images (in
 * /images) rather than an admin-uploaded one (in /user_images).
 */
function is_bundled_hero_image($filename) {
    return in_array($filename, get_bundled_hero_images(), true);
}

/**
 * Filesystem directory a hero image actually lives in.
 */
function hero_image_directory($filename) {
    return is_bundled_hero_image($filename) ? IMAGES : USER_IMAGES;
}

/**
 * Public URL for a hero image, matching hero_image_directory()'s split.
 * $images_url is the caller's own (possibly HOSTED-shared) /images URL;
 * admin-uploaded images always resolve to this installation's own
 * /user_images, regardless of HOSTED status.
 */
function hero_image_url($filename, $base_url, $images_url) {
    return is_bundled_hero_image($filename) ? ($images_url.$filename) : ($base_url."user_images/".$filename);
}

/**
 * Scan /user_images for uploaded hero images, plus the bundled fallback
 * images (in /images, see get_bundled_hero_images()), grouped by category.
 */
function get_all_available_hero_images() {
    $hero_images = array(
        "0" => array(),
        "1" => array(),
        "2" => array(),
        "3" => array(),
    );

    // GLOB_BRACE isn't defined on every platform (e.g. musl-based Linux such as Alpine),
    // and referencing an undefined constant is a fatal error since PHP 8.0 - glob per
    // extension instead so this doesn't depend on GLOB_BRACE being available.
    $files = array();
    foreach (array('jpg', 'jpeg', 'png', 'gif', 'webp') as $extension) {
        $matches = glob(USER_IMAGES.'*.'.$extension);
        if ($matches !== false) $files = array_merge($files, $matches);
    }

    $filenames = array();
    foreach ($files as $path) {
        $filenames[] = basename($path);
    }

    foreach (get_bundled_hero_images() as $bundled_filename) {
        if ((!in_array($bundled_filename, $filenames)) && (file_exists(IMAGES.$bundled_filename))) {
            $filenames[] = $bundled_filename;
        }
    }

    foreach ($filenames as $file) {
        if (!is_hero_image_candidate($file)) continue;

        $category = hero_image_category_from_filename($file);
        if (($category !== null) && isset($hero_images[$category])) {
            $hero_images[$category][] = $file;
        }
    }

    foreach ($hero_images as &$category_images) {
        sort($category_images);
    }

    return $hero_images;
}

/**
 * Return active hero images allowed for the currently selected style types.
 */
function get_active_hero_images($db_conn, $prefix, $style_types = array()) {
    $active_images = array();
    $all_images = get_all_available_hero_images();
    $hero_prefs = load_hero_images_preferences($db_conn, $prefix, $all_images);
    if (!is_array($hero_prefs)) return $active_images;

    $allowed_types = array("0");
    foreach ($style_types as $type) {
        $type_str = (string)$type;
        if (($type_str !== "0") && in_array($type_str, array("1", "2", "3"))) {
            $allowed_types[] = $type_str;
        }
    }

    foreach ($hero_prefs as $image => $is_active) {
        if (!($is_active === true || $is_active === 1 || $is_active === "1" || $is_active === "true")) continue;

        $category = hero_image_category_from_filename($image);
        if (($category !== null) && in_array($category, $allowed_types)) {
            $active_images[] = $image;
        }
    }

    return $active_images;
}

/**
 * Save hero image preferences to the preferences table.
 */
function save_hero_images_preferences($db_conn, $prefix, $images_array) {
    $json_flags = JSON_PRETTY_PRINT;
    if (defined('JSON_INVALID_UTF8_SUBSTITUTE')) {
        $json_flags |= JSON_INVALID_UTF8_SUBSTITUTE;
    }

    $json_data = json_encode($images_array, $json_flags);
    if ($json_data === false) {
        return false;
    }

    try {
        $db_conn->where('id', 1);
        return $db_conn->update($prefix."preferences", array('prefsHeroImages' => $json_data));
    }
    catch (Exception $e) {
        return false;
    }
}

/**
 * Initialize preferences with all discovered hero images enabled.
 */
function initialize_hero_images_preferences($db_conn, $prefix) {
    $all_images = get_all_available_hero_images();
    $images_array = get_default_hero_preferences($all_images);

    return save_hero_images_preferences($db_conn, $prefix, $images_array);
}
