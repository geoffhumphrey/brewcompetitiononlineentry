<?php
declare(strict_types=1);

/**
 * Module:      en-US_admin.lang.php
 * Description: This module houses all display text in the English language.
 *
 */

/*

--------------------------------------------------------------------------------------------------

To translate this file, first make a copy of it and rename it with the language name in the title.

==============================

Use ISO 169-2 Standards for and WWW3C Language Tag Standards for naming of language files. Use the
ALPHA-2 letter code whenever possible.

ISO 169-2:
https://www.loc.gov/standards/iso639-2/php/code_list.php

WWW3 Language Tags:
https://www.w3.org/International/articles/language-tags/

WWW3 Choosing a Language Tag:
https://www.w3.org/International/questions/qa-choosing-language-tags

According to the WWW3:

"Always bear in mind that the golden rule is to keep your language tag as short as possible. Only
add further subtags to your language tag *if they are needed to distinguish the language from
something else in the context where your content is used...*

"Unless you specifically need to highlight that you are talking about Italian as spoken in Italy
you should use it for Italian, and not it-IT. The same goes for any other possible combination."

To determine a subtag, go to the IANA Language Subtag Registry:
http://www.iana.org/assignments/language-subtag-registry

==============================

Items that need translation into other languages are housed here in PHP variables - each start with
a dollar sign ($). The words, phrases, etc. (called strings) that need to be translated are housed
between double-quotes ("). Please, ONLY alter the text between the double quotes!

For example, a translated PHP variable would look like this:

English (US) before translation:
$label_volunteer_info = "Volunteer Info";

Spanish translated:
$label_volunteer_info = "Información de Voluntarios";

Portuguese translated:
$label_volunteer_info = "Informações Voluntário";

==============================

Please note: the strings that need to be translated MAY contain HTML code. Please leave this code
intact! For example:

English (US):
$beerxml_text_008 = "Browse for your BeerXML compliant file on your hard drive and select <em>Upload</em>.";

Spanish:
$beerxml_text_008 = "Buscar su archivo compatible BeerXML en su disco duro y haga clic en <em>Cargar</em>.";

Note that the <em>...</em> tags were not altered. Just the word "Upload" to "Cargar" betewen those tags.

==============================

*/

// -------------------- Archive --------------------

$archive_text_000 = "Due to server storage limitations, archiving of hosted BCOE&amp;M account data is not available. To utilize the software for a new competition or simply to clear the database of data, use the buttons below.";
$archive_text_001 = "Custom category, custom style type, drop-off location, judging location, and sponsor data <strong class=\"text-success\">will not be purged</strong>. Admins will need to update these for future competition instances.";
$archive_text_002 = "Option 1";
$archive_text_003 = "Are you sure you want to clear the current competition&rsquo;s data? This CANNOT be undone.";
$archive_text_004 = "Clear All Participant, Entry, Judging, and Scoring Data";
$archive_text_005 = "This option clears all non-admin participant accounts as well as all entry, judging, and scoring data, including all uploaded scoresheets. Provides a clean slate.";
$archive_text_006 = "Option 2";
$archive_text_007 = "Are you sure you want to clear the current competition&rsquo;s data? This CANNOT be undone.";
$archive_text_008 = "Clear Entry, Judging, and Scoring Data Only";
$archive_text_009 = "This option clears all entry, judging, and scoring data, including all uploaded scoresheets, but retains the participant data. Useful if you want don't want to have participants create new account profiles.";
$archive_text_010 = "To archive data currently stored in the database, provide a name of the archive. It is suggested that choose a name that is unique to this data set. For example, if you hold your competition annually, the name could be the year it was held. If you host successive competitions on a single installation, the name of the competition and the year could serve as the name.";
$archive_text_011 = "Alpha numeric characters only - all others will be omitted.";
$archive_text_012 = "Check the information you would like to retain for use in future competition instances.";
$archive_text_013 = "Are you sure you want to archive current data?";
$archive_text_014 = "Then, choose what data you would like to retain.";

// -------------------- Hero Images --------------------

$hero_images_text_001 = "Banner Images";
$admin_hero_images_title = "Banner Images";
$admin_hero_images_description = "Select which banner images are displayed on the homepage. Images are randomly selected based on your competition's accepted style types.";
$admin_hero_images_saved = "Banner image preferences saved successfully.";
$admin_hero_images_error = "Error saving banner image preferences.";
$admin_hero_how_it_works_title = "How it works";
$admin_hero_how_it_works_body = "Banner images appear as a large background strip at the top of the competition homepage. One image is picked at random each time a visitor loads the page. Images are grouped by category &mdash; Miscellaneous images can appear at any time, while Beer, Cider, and Mead images only appear when your competition accepts entries in those categories. Use the checkboxes below to choose which images are in the rotation, then click <strong>Save Changes</strong>. To add a new image, use the upload panel above and choose the matching category.";
$admin_hero_upload_note_title = "File naming";
$admin_hero_upload_note_body = "The uploaded file is automatically renamed using the selected category as a prefix &mdash; for example, uploading <em>sunset.jpg</em> in the Beer category saves as <code>beer-sunset.jpg</code>. You do not need to rename the file before uploading.";
$admin_hero_upload_note_size_title = "Size &amp; format";
$admin_hero_upload_note_size_body = "Recommended 3000&times;500 px (6:1 ratio). Minimum width 1200 px with at least a 3.5:1 aspect ratio. Accepted formats: JPG, PNG, GIF, WebP. Maximum file size: 2 MB.";
$admin_hero_category_misc = "Miscellaneous";
$admin_hero_category_beer = "Beer";
$admin_hero_category_cider = "Cider";
$admin_hero_category_mead = "Mead";
$admin_hero_category_shown_all = "Shown on all pages";
$admin_hero_category_shown_beer = "Shown when beer category is active";
$admin_hero_category_shown_cider = "Shown when cider category is active";
$admin_hero_category_shown_mead = "Shown when mead category is active";
$admin_hero_no_images = "No images found";
$admin_hero_save_button = "Save Changes";
$admin_hero_select_all = "Select All";
$admin_hero_deselect_all = "Deselect All";

// Old strings kept for backwards compatibility
$hero_images_text_002 = "Upload New Banner Image";
$hero_images_text_003 = "Upload background images for the competition homepage. Recommended size: 3000x500 pixels (6:1 ratio). Acceptable formats: JPG, PNG, GIF, WebP. Maximum file size: 2 MB.";
$hero_images_text_004 = "Image File";
$hero_images_text_005 = "Choose File";
$hero_images_text_006 = "No file chosen...";
$hero_images_text_007 = "Category";
$hero_images_text_008 = "Select a category...";
$hero_images_text_009 = "Images are randomly selected based on your competition's accepted style types. Miscellaneous images appear on all pages.";
$hero_images_text_010 = "Active";
$hero_images_text_011 = "Include this image in the homepage rotation";
$hero_images_text_012 = "Upload Image";
$hero_images_text_013 = "Guidelines";
$hero_images_text_014 = "Size";
$hero_images_text_015 = "Ratio";
$hero_images_text_016 = "Formats";
$hero_images_text_017 = "Categories";
$hero_images_text_018 = "Existing Banner Images";
$hero_images_text_019 = "Preview";
$hero_images_text_020 = "File Name";
$hero_images_text_021 = "Actions";
$hero_images_text_022 = "Click to enlarge";
$hero_images_text_023 = "Yes";
$hero_images_text_024 = "No";
$hero_images_text_025 = "Delete";
$hero_images_text_026 = "Are you sure? This will remove the image named";
$hero_images_text_027 = "from the server.";
$hero_images_text_028 = "Error updating image. Please try again.";
$hero_images_text_029 = "Error deleting image. Please try again.";
$hero_images_text_030 = "No hero images have been uploaded yet. Upload your first image above.";
$archive_text_015 = "This will delete the archive called";
$archive_text_016 = "All associated records will be removed as well.";

/*
 * --------------------- v 2.2.0 -----------------------
 */
$archive_text_017 = "Edit your archive information with caution. Changing any of the following may result in unexpected behavior when attempting to access archived data.";
$archive_text_018 = "The files will be moved to a sub-folder with the same name of your archive in the user_docs directory.";
$archive_text_019 = "Archived winner list(s) available for public viewing.";
$archive_text_020 = "Generally, this should only be changed if this archive's winner list is displaying incorrectly.";
$archive_text_021 = "PDF scoresheets have been saved for this archive. This is the naming convention of each file used by the system when accessing them.";
$archive_text_022 = "Disabled. No results data exists for this archive.";
$archive_text_023 = "A style set is not specified. Archived entry, scoring, and box data may not display correctly.";

$label_uploaded_scoresheets = "Uploaded Scoresheets (PDF Files)";
$label_admin_comp_type = "Competition Type";
$label_admin_styleset = "Style Set";
$label_admin_winner_display = "Winner Display";
$label_admin_enable = "Enable";
$label_admin_disable = "Disable";
$label_admin_winner_dist = "Winner Place Distribution Method";
$label_admin_archive = "Archive";
$label_admin_scoresheet_names = "Scoresheet Upload File Names";
$label_six_char_judging = "6-Character Judging Number";
$label_six_digit_entry = "6-Digit Entry Number";
$label_not_archived = "Not Archived";

// -------------------- Barcode Check-In --------------------



// -------------------- Navigation --------------------

?>
