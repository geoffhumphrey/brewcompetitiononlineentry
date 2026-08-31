<?php
/**
 * Module:      default_language.db.php
 * Description: Resolve the site-wide default language from the DB.
 *
 * Entry labels must be uniform regardless of which language the
 * requesting user has chosen in the UI, so labels are always rendered in
 * the site default. This helper reads prefsLanguage (and derives the
 * folder) straight from the preferences table.
 */

if (!function_exists("get_default_language")) {

    /**
     * Returns array('lang' => 'ko-KR', 'folder' => 'ko') for the site
     * default language, or NULL if unavailable.
     */
    function get_default_language() {
        global $db_conn, $prefix;
        static $cached = NULL;
        if ($cached !== NULL) return $cached;

        $row = $db_conn->where("id", 1)->getOne($prefix."preferences", "prefsLanguage");
        if (($db_conn->count == 0) || (empty($row['prefsLanguage']))) {
            $cached = array('lang' => 'en-US', 'folder' => 'en');
            return $cached;
        }

        $lang = $row['prefsLanguage'];
        // Legacy "English" value normalization (same as language.lang.php)
        if (strtolower($lang) == "english") $lang = "en-US";
        $parts = explode("-", $lang);
        $cached = array('lang' => $lang, 'folder' => strtolower($parts[0]));
        return $cached;
    }

}
?>
