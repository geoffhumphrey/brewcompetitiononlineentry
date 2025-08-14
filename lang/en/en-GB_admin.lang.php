<?php
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