<?php
/**
 * Module:      en-US.lang.php
 * Description: This module houses all display text in the English language.
 * Updated:     September 21, 2018
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

To determine a subtag, go to the IANA Language Subtag Registry:
http://www.iana.org/assignments/language-subtag-registry

According to the WWW3:

"Always bear in mind that the golden rule is to keep your language tag as short as possible. Only
add further subtags to your language tag *if they are needed to distinguish the language from
something else in the context where your content is used..."

"Unless you specifically need to highlight that you are talking about Italian as spoken in Italy
you should use it 'for Italian, and not it-IT. The same goes for any other possible combination."

"You should only use a region subtag if it contributes information needed in a particular context
to distinguish this language tag from another one; otherwise leave it out."

================ FORMAT =================

Always indicate the primary languge subtag first, then a dash (-) and then the region subtag. The
region subtag is in all capital letters or a three digit number.

Examples:
en-US
English spoken in the United States
en is the PRIMARY language subtag
US is the REGION subtag (note the capitalization)

es-ES
Spanish spoken in Spain

es-419
Spanish spoken in Latin America

========================================

Items that need translation into other languages are housed here in PHP variables - each start with
a dollar sign ($). The words, phrases, etc. (called strings) that need to be translated are housed
between double-quotes ("). Please, ONLY alter the text between the double quotes!

For example, a translated PHP variable would look like this (encoding is utf8mb4; therefore, accented and other special characters are acceptable):

English (US) before translation:
$label_volunteer_info = "Volunteer Info";

Spanish translated:
$label_volunteer_info = "Información de Voluntarios";

Portuguese translated:
$label_volunteer_info = "Informações Voluntário";

========================================

Please note: the strings that need to be translated MAY contain HTML code. Please leave this code intact! For example:

English (US):
$beerxml_text_008 = "Browse for your BeerXML compliant file on your hard drive and click <em>Upload</em>.";

Spanish:
$beerxml_text_008 = "Buscar su archivo compatible BeerXML en su disco duro y haga clic en <em>Cargar</em>.";

Note that the <em>...</em> tags were not altered. Just the word "Upload" to "Cargar" between those tags.

==============================

*/

include (INCLUDES.'url_variables.inc.php');

if (isset($entry_open)) $entry_open = $entry_open;
else $entry_open = "";

if (isset($judge_open)) $judge_open = $judge_open;
else $judge_open = "";

if (isset($judge_closed)) $judge_closed = $judge_closed;
else $judge_closed = "";

if (isset($reg_open)) $reg_open = $reg_open;
else $reg_open = "";

if (isset($total_entries)) $total_entries = $total_entries;
else $total_entries = "";

if (isset($current_time)) $current_time = $current_time;
else $current_time = "";

if (isset($current_time)) $current_time = $current_time;
else $current_time = "";

if (isset($row_limits['prefsEntryLimit'])) $row_limits['prefsEntryLimit'] = $row_limits['prefsEntryLimit'];
else $row_limits['prefsEntryLimit'] = "";

if (isset($row_limits['prefsEntryLimitPaid'])) $row_limits['prefsEntryLimitPaid'] = $row_limits['prefsEntryLimitPaid'];
else $row_limits['prefsEntryLimitPaid'] = "";

$php_version = phpversion();

// ***********************************************************************************
// BEGIN TRANSLATIONS BELOW!
// ***********************************************************************************

$j_s_text = "";
if (strpos($section, "step") === FALSE) {
	if ((isset($judge_limit)) && (isset($steward_limit))) {
		if (($judge_limit) && (!$steward_limit)) $j_s_text = "Steward"; // missing punctuation intentional
		elseif ((!$judge_limit) && ($steward_limit)) $j_s_text = "Judge"; // missing punctuation intentional
		else $j_s_text = "Judge or steward"; // missing punctuation intentional
	}
}

// -------------------- Global Labels - mostly used for titles and navigation --------------------
// All labels are capitalized and without punctuation

$label_home = "Home";
$label_welcome = "Welcome";
$label_comps = "Competition Directory";
$label_info = "Info";
$label_volunteers = "Volunteers";
$label_register = "Register";
$label_help = "Help";
$label_print = "Print";
$label_my_account = "My Account";
$label_yes = "Yes";
$label_no = "No";
$label_low_none = "Low/None";
$label_low = "Low";
$label_med = "Medium";
$label_high = "High";
$label_pay = "Pay Entry Fees";
$label_reset_password = "Reset Password";
$label_log_in = "Log In";
$label_logged_in = "Logged In";
$label_log_out = "Log Out";
$label_logged_out = "Logged Out";
$label_sponsors = "Sponsors";
$label_rules = "Rules";
$label_volunteer_info = "Volunteer Info";
$label_reg = $label_register;
$label_judge_reg = "Judge Registration";
$label_steward_reg = "Steward Registration";
$label_past_winners = "Past Winners";
$label_contact = "Contact";
$label_style = "Style";
$label_entry = "Entry";
$label_add_entry = "Add Entry";
$label_edit_entry = "Edit Entry";
$label_upload = "Upload";
$label_bos = "Best of Show";
$label_brewer = "Brewer";
$label_cobrewer = "Co-Brewer";
$label_entry_name = "Entry Name";
$label_required_info = "Required Info";
$label_character_limit = " character limit - use keywords and abbreviations if space is limited.<br>Characters used: ";
$label_carbonation = "Carbonation";
$label_sweetness = "Sweetness";
$label_strength = "Strength";
$label_color = 	"Color";
$label_table = "Table";
$label_standard = "Standard";
$label_super = "Super";
$label_session = "Session";
$label_double = "Double";
$label_blonde = "Blonde";
$label_amber = "Amber";
$label_brown = "Brown";
$label_pale = "Pale";
$label_dark = "Dark";
$label_hydromel = "Hydromel";
$label_sack = "Sack";
$label_still = "Still";
$label_petillant = "Petillant";
$label_sparkling = "Sparkling";
$label_dry = "Dry";
$label_med_dry = "Medium Dry";
$label_med_sweet = "Medium Sweet";
$label_sweet = "Sweet";
$label_brewer_specifics = "Brewer's Specifics";
$label_general = "General";
$label_amount_brewed = "Amount Brewed";
$label_specific_gravity = "Specific Gravity";
$label_fermentables = "Fermentables";
$label_malt_extract = "Malt Extract";
$label_grain = "Grain";
$label_hops = "Hops";
$label_hop = "Hop";
$label_mash = "Mash";
$label_steep = "Steep";
$label_other = "Other";
$label_weight = "Weight";
$label_use = "Use";
$label_time = "Time";
$label_first_wort = "First Wort";
$label_boil = "Boil";
$label_aroma = "Aroma";
$label_dry_hop = "Dry Hop";
$label_type = "Type";
$label_bittering = "Bittering";
$label_both = "Both";
$label_form = "Form";
$label_whole = "Whole";
$label_pellets = "Pellets";
$label_plug = "Plug";
$label_extract = "Extract";
$label_date = "Date";
$label_bottled = "Bottled";
$label_misc = "Miscellaneous";
$label_minutes = "Mintues";
$label_hours = "Hours";
$label_step = "Step";
$label_temperature = "Temperature";
$label_water = "Water";
$label_amount = "Amount";
$label_yeast = "Yeast";
$label_name = "Name";
$label_manufacturer = "Manufacturer";
$label_nutrients = "Nutrients";
$label_liquid = "Liquid";
$label_ale = "Ale";
$label_lager = "Lager";
$label_wine = "Wine";
$label_champagne = "Champagne";
$label_boil = "Boil";
$label_fermentation = "Fermentation";
$label_finishing = "Finishing";
$label_finings = "Finings";
$label_primary = "Primary";
$label_secondary = "Secondary";
$label_days = "Days";
$label_forced = "Forced CO2";
$label_bottle_cond = "Bottle Conditioned";
$label_volume = "Volume";
$label_og = "Original Gravity";
$label_fg = "Final Gravity";
$label_starter = "Starter";
$label_password = "Password";
$label_judging_number = "Judging Number";
$label_check_in = "Check In Entry";
$label_box_number = "Box Number";
$label_first_name = "First Name";
$label_last_name = "Last Name";
$label_secret_01 = "What is your favorite all-time beer to drink?";
$label_secret_02 = "What was the name of your first pet?";
$label_secret_03 = "What was the name of the street you grew up on?";
$label_secret_04 = "What was your high school mascot?";
$label_security_answer = "Security Question Answer";
$label_security_question = "Security Question";
$label_judging = "Judging";
$label_judge = "Judge";
$label_steward = "Steward";
$label_account_info = "Account Info";
$label_street_address = "Street Address";
$label_address = "Address";
$label_city = "City";
$label_state_province = "State/Province";
$label_zip = "Zip/Postal Code";
$label_country = "Country";
$label_phone = "Phone";
$label_phone_primary = "Primary Phone";
$label_phone_secondary = "Secondary Phone";
$label_drop_off = "Drop-Off Location";
$label_drop_offs = "Drop-Off Locations";
$label_club = "Club";
$label_aha_number = "AHA Member Number";
$label_org_notes = "Notes to Organizer";
$label_avail = "Availability";
$label_location = "Location";
$label_judging_avail = "Judging Session Availability";
$label_stewarding = "Stewarding";
$label_stewarding_avail = "Stewarding Session Availability";
$label_bjcp_id = "BJCP ID";
$label_bjcp_mead = "Mead Judge";
$label_bjcp_rank = "BJCP Rank";
$label_designations = "Designations";
$label_judge_sensory = "Judge with Sensory Training";
$label_judge_pro = "Professional Brewer";
$label_judge_comps = "Competitions Judged";
$label_judge_preferred = "Preferred Styles";
$label_judge_non_preferred = "Non-Preferred Styles";
$label_waiver = "Waiver";
$label_add_admin = "Add Admin User Info";
$label_add_account = "Add Account Info";
$label_edit_account = "Edit Account Info";
$label_entries = "Entries";
$label_confirmed = "Confirmed";
$label_paid = "Paid";
$label_updated = "Updated";
$label_mini_bos = "Mini-BOS";
$label_actions = "Actions";
$label_score = "Score";
$label_winner = "Winner?";
$label_change_email = "Change Email";
$label_change_password = "Change Password";
$label_add_beerXML = "Add an Entry Using BeerXML";
$label_none_entered = "None entered";
$label_none = "None";
$label_discount = "Discount";
$label_subject = "Subject";
$label_message = "Message";
$label_send_message = "Send Message";
$label_email = "Email Address";
$label_account_registration = "Account Registration";
$label_entry_registration = "Entry Registration";
$label_entry_fees = "Entry Fees";
$label_entry_limit = "Entry Limit";
$label_entry_info = "Entry Info";
$label_entry_per_entrant = "Per Entrant Limits";
$label_categories_accepted = "Styles Accepted";
$label_judging_categories = "Judging Categories";
$label_entry_acceptance_rules = "Entry Acceptance Rules";
$label_shipping_info = "Shipping Info";
$label_packing_shipping = "Packing and Shipping";
$label_awards = "Awards";
$label_awards_ceremony = "Awards Ceremony";
$label_circuit = "Circuit Qualification";
$label_hosted = "Hosted Edition";
$label_entry_check_in = "Entry Check-In";
$label_cash = "Cash";
$label_check = "Check";
$label_pay_online = "Pay Online";
$label_cancel = "Cancel";
$label_understand = "I Understand";
$label_fee_discount = "Discounted Entry Fee";
$label_discount_code = "Discount Code";
$label_register_judge = "Are You Registering as a Entrant, Judge or Steward?";
$label_register_judge_standard = "Register a Judge or Steward (Standard)";
$label_register_judge_quick = "Register a Judge or Steward (Quick)";
$label_all_participants = "All Participants";
$label_open = "Open";
$label_closed = "Closed";
$label_judging_loc = "Judging Session Locations and Dates";
$label_new = "New";
$label_old = "Old";
$label_sure = "Are You Sure?";
$label_judges = "Judges";
$label_stewards = "Stewards";
$label_staff = "Staff";
$label_category = "Category";
$label_delete = "Delete";
$label_undone = "This Cannot Be Undone";
$label_bitterness = "Bitterness";
$label_close = "Close";
$label_custom_style = "Custom Style";
$label_custom_style_types = "Custom Style Types";
$label_assigned_to_table = "Assigned to Table";
$label_organizer = "Organizer";
$label_by_table = "By Table";
$label_by_category = "By Style";
$label_by_subcategory = "By Sub-Style";
$label_by_last_name = "By Last Name";
$label_by_table = "By Table";
$label_by_location = "By Session Location";
$label_shipping_entries = "Shipping Entries";
$label_no_availability = "No Availability Defined";
$label_error = "Error";
$label_round = "Round";
$label_flight = "Flight";
$label_rounds = "Rounds";
$label_flights = "Flights";
$label_sign_in = "Sign In";
$label_signature = "Signature";
$label_assignment = "Assignment";
$label_assignments = "Assignments";
$label_letter = "Letter";
$label_re_enter = "Re-Enter";
$label_website = "Website";
$label_place = "Place";
$label_cheers = "Cheers";
$label_count = "Count";
$label_total = "Total";
$label_participant = "Participant";
$label_entrant = "Entrant";
$label_received = "Received";
$label_please_note = "Please Note";
$label_pull_order = "Pull Order";
$label_box = "Box";
$label_sorted = "Sorted";
$label_subcategory = "Subcategory";
$label_affixed = "Label Affixed?";
$label_points = "Points";
$label_comp_id = "BJCP Competition ID";
$label_days = "Days";
$label_sessions = "Sessions";
$label_number = "Number";
$label_more_info = "More Info";
$label_entry_instructions = "Entry Instructions";
$label_commercial_examples = "Commercial Examples";
$label_users = "Users";
$label_participants = "Participants";
$label_please_confirm = "Please Confirm";
$label_undone = "This cannot be undone.";
$label_data_retain = "Data to Retain";
$label_comp_portal = "Competition Directory";
$label_comp = "Competition";
$label_continue = "Continue";
$label_host = "Host";
$label_closing_soon = "Closing Soon";
$label_access = "Access";
$label_length = "Length";

// Admin
$label_admin = "Administration";
$label_admin_short = "Admin";
$label_admin_dashboard = "Dashboard";
$label_admin_judging = $label_judging;
$label_admin_stewarding = "Stewarding";
$label_admin_participants = $label_participants;
$label_admin_entries = $label_entries;
$label_admin_comp_info = "Competition Info";
$label_admin_web_prefs = "Website Preferences";
$label_admin_judge_prefs = "Competition Organization Preferences";
$label_admin_archives = "Archives";
$label_admin_style = $label_style;
$label_admin_styles = "Styles";
$label_admin_dropoff = $label_drop_offs;
$label_admin_judging_loc = $label_judging_loc;
$label_admin_contacts = "Contacts";
$label_admin_tables = "Tables";
$label_admin_scores = "Scores";
$label_admin_bos = $label_bos;
$label_admin_bos_acr = "BOS";
$label_admin_style_types = "Style Types";
$label_admin_custom_cat = "Custom Categories";
$label_admin_custom_cat_data = "Custom Category Entries";
$label_admin_sponsors = $label_sponsors;
$label_admin_entry_count = "Entry Count By Style";
$label_admin_entry_count_sub = "Entry Count By Sub-Style";
$label_admin_custom_mods = "Custom Modules";
$label_admin_check_in = $label_entry_check_in;
$label_admin_make_admin = "Change User Level";
$label_admin_register = "Register a Participant";
$label_admin_upload_img = "Upload Images";
$label_admin_upload_doc = "Upload Scoresheets and Other Documents";
$label_admin_password = "Change User Password";
$label_admin_edit_account = "Edit User Account";

// Sidebar Labels
$label_account_summary = "My Account Summary";
$label_confirmed_entries = "Confirmed Entries";
$label_unpaid_confirmed_entries = "Unpaid Confirmed Entries";
$label_total_entry_fees = "Total Entry Fees";
$label_entry_fees_to_pay = "Entry Fees to Pay";
$label_entry_drop_off = "Entry Drop-Off";

// v2.1.9
$label_maintenance = "Maintenance";
$label_judge_info = "Judge Information";
$label_cellar = "My Cellar";
$label_verify = "Verify";
$label_entry_number = "Entry Number";

// -------------------- Headers --------------------
// Missing punctuation intentional for all
$header_text_000 = "Setup was successful.";
$header_text_001 = "You are now logged in and ready to further customize your competition's site.";
$header_text_002 = "However, your config.php permissions file could not be changed.";
$header_text_003 = "It is highly recommended that you change the server permissions (chmod) on the config.php file to 555. To do this, you will need to access the file on your server.";
$header_text_004 = "Additionally, the &#36;setup_free_access variable in config.php is currently set to TRUE. For security reasons, the setting should returned to FALSE. You will need to edit config.php directly and re-upload to your server to do this.";
$header_text_005 = "Info added successfully.";
$header_text_006 = "Info edited successfully.";
$header_text_007 = "There was an error.";
$header_text_008 = "Please try again.";
$header_text_009 = "You must be an administrator to access the admin functions.";
$header_text_010 = "Change";
$header_text_011 = $label_email;
$header_text_012 = $label_password;
$header_text_013 = "The email address provided is already in use, please provide another email address.";
$header_text_014 = "There was a problem with the last request, please try again.";
$header_text_015 = "Your current password was incorrect.";
$header_text_016 = "Please provide an email address.";
$header_text_017 = "Sorry, there was a problem with your last login attempt.";
$header_text_018 = "Sorry, the user name you entered is already in use.";
$header_text_019 = "Perhaps you have already created an account?";
$header_text_020 = "If so, log in here.";
$header_text_021 = "The user name provided is not a valid email address.";
$header_text_022 = "Please enter a valid email address.";
$header_text_023 = "CAPTCHA was not successful.";
$header_text_024 = "The email addresses you entered do not match.";
$header_text_025 = "The AHA number you entered is already in the system.";
$header_text_026 = "Your online payment has been received and the transaction has been completed. Please note that you may need to wait a few minutes for the payment status to be updated here - be sure to refresh this page or access your entries list. You will receive a payment receipt via email from PayPal.";
$header_text_027 = "Please make sure to print the receipt and attach it to one of your entries as proof of payment.";
$header_text_028 = "Your online payment has been cancelled.";
$header_text_029 = "The code has been verified.";
$header_text_030 = "Sorry, the code you entered was incorrect.";
$header_text_031 = "You must log in and have admin privileges to access administration functions.";
$header_text_032 = "Sorry, there was a problem with your last login attempt.";
$header_text_033 = "Please make sure your email address and password are correct.";
$header_text_034 = "A password reset token has been generated and emailed to the address associated with your account.";
$header_text_035 = "- you can now log in using your current username and the new password.";
$header_text_036 = "You have been logged out.";
$header_text_037 = "Log in again?";
$header_text_038 = "Your verification question does not match what is in the database.";
$header_text_039 = "Your ID verification information has been sent to the email address associated with your account.";
$header_text_040 = "Your message has been sent to";
$header_text_041 = $header_text_023;
$header_text_042 = "Your email address has been updated.";
$header_text_043 = "Your password has been updated.";
$header_text_044 = "Info deleted successfully.";
$header_text_045 = "You should verify all your entries imported using BeerXML.";
$header_text_046 = "You have registered.";
$header_text_047 = "You have reached the entry limit.";
$header_text_048 = "Your entry was not added.";
$header_text_049 = "You have reached the entry limit for the subcategory.";
$header_text_050 = "Set Up: Install DB Tables and Data";
$header_text_051 = "Set Up: Create Admin User";
$header_text_052 = "Set Up: Add Admin User Info";
$header_text_053 = "Set Up: Set Website Preferences";
$header_text_054 = "Set Up: Add Competition Info";
$header_text_055 = "Set Up: Add Judging Session Locations";
$header_text_056 = "Set Up: Add Drop-Off Locations";
$header_text_057 = "Set Up: Designate Accepted Styles";
$header_text_058 = "Set Up: Set Judging Preferences";
$header_text_059 = "Import an Entry Using BeerXML";
$header_text_060 = "Your entry has been recorded.";
$header_text_061 = "Your entry has been confirmed.";
$header_text_065 = "All received entries have been checked and those not assigned to tables have been assigned.";
$header_text_066 = "Info updated successfully.";
$header_text_067 = "The suffix you entered is already in use, please enter a different one.";
$header_text_068 = "The specified competition data has been cleared.";
$header_text_069 = "Archives created successfully. ";
$header_text_070 = "Click the archive name to view.";
$header_text_071 = "Remember to update your ".$label_admin_comp_info." and your ".$label_admin_judging_loc." if you are starting a new competition.";
$header_text_072 = "Archive \"".$filter."\" deleted.";
$header_text_073 = "The records have been updated.";
$header_text_074 = "The username you have entered is already in use.";
$header_text_075 = "Add another drop-off location?";
$header_text_076 = "Add another judging location, date, or time?";
$header_text_077 = "The table that was just defined does not have any associated styles.";
$header_text_078 = "One or more pieces of required data are missing - outlined in red below.";
$header_text_079 = "The email addresses you entered do not match.";
$header_text_080 = "The AHA number you entered is already in the system.";
$header_text_081 = "All entries have been marked as paid.";
$header_text_082 = "All entries have been marked as received.";
$header_text_083 = "All unconfirmed entries are now marked as confirmed.";
$header_text_084 = "All participant assignments have been cleared.";
$header_text_085 = "A judging number you entered wasn't found in the database.";
$header_text_086 = "All entry styles have been converted from BJCP 2008 to BJCP 2015.";
$header_text_087 = "Data deleted successfully.";
$header_text_088 = "The judge/steward has been added successfully. Remember to assign the user as a judge or steward before assigning to tables.";
$header_text_089 = "The file has been uploaded successfully. Check the list to verify.";
$header_text_090 = "The file that was attempted to be uploaded is not an accepted file type.";
$header_text_091 = "File(s) deleted successfully.";
$header_text_092 = "The test email has been generated. Be sure to check your spam folder.";
$header_text_093 = "The user&rsquo;s password has been changed. Be sure to let them know what their new password is!";
$header_text_094 = "Change permission of user_images folder to 755 has failed.";
$header_text_095 = "You will need to change the folder&rsquo;s permission manually. Consult your FTP program or ISP&rsquo;s documentation for chmod (folder permissions).";
$header_text_096 = "Judging Numbers have been regenerated.";
$header_text_097 = "Your installation has been set up successfully!";
$header_text_098 = "FOR SECURITY REASONS you should immediately set the &#36;setup_free_access variable in config.php to FALSE.";
$header_text_099 = "Otherwise, your installation and server are vulerable to security breaches.";
$header_text_100 = "Log in now to access the Admin Dashboard";
$header_text_101 = "Your installation has been updated successfully!";
$header_text_102 = "The email addresses do not match.";
$header_text_103 = "Please log in to access your account.";
$header_text_104 = "You do not have sufficient access privileges to view this page.";
$header_text_105 = "More information is required for your entry to be accepted and confirmed.";
$header_text_106 = "See the area(s) highlighted in RED below.";
$header_text_107 = "Please choose a style.";
$header_text_108 = "This entry cannot be accepted or confirmed until a style has been chosen. Unconfirmed entries may be deleted from the system without warning.";

// v2.1.9
$header_text_109 = "You have registered as a steward.";
$header_text_110 = "All entries have been un-marked as paid.";
$header_text_111 = "All entries have been un-marked as received.";

// -------------------- Navigation --------------------



// -------------------- Alerts --------------------
$alert_text_000 = "Grant users top-level admin and admin access with caution.";
$alert_text_001 = "Data clean-up completed.";
$alert_text_002 = "The &#36;setup_free_access variable in config.php is currently set to TRUE.";
$alert_text_003 = "For security reasons, the setting should returned to FALSE. You will need to edit config.php directly and re-upload the file to your server.";
$alert_text_005 = "No drop-off locations have been specified.";
$alert_text_006 = "Add a drop-off location?";
$alert_text_008 = "No judging dates/locations have been specified.";
$alert_text_009 = "Add a judging location?";
$alert_text_011 = "No competition contacts have been specified.";
$alert_text_012 = "Add a competition contact?";
$alert_text_014 = "Your current style set is BJCP 2008.";
$alert_text_015 = "Do you want to convert all entries to BJCP 2015?";
$alert_text_016 = "Are you sure? This action will convert all entries in the database to conform to the BJCP 2015 style guidelines. Categories will be 1:1 where possible, however some specialty styles may need to be updated by the entrant.";
$alert_text_017 = "To retain functionality, the conversion must be performed <em>before</em> defining tables.";
$alert_text_019 = "All unconfirmed entries have been deleted from the database.";
$alert_text_020 = "Unconfirmed entries are highlighted and denoted with a <span class=\"fa fa-lg fa-exclamation-triangle text-danger\"></span> icon below.";
$alert_text_021 = "Owners of these entries should be contacted. These entries are not included in fee calculations.";
$alert_text_023 = "Add a Drop-Off Location?";
$alert_text_024 = $label_yes;
$alert_text_025 = $label_no;
$alert_text_027 = "Entry registration has not opened yet.";
$alert_text_028 = "Entry registration has closed.";
$alert_text_029 = "Adding entries is not available.";
$alert_text_030 = "The competition entry limit has been reached.";
$alert_text_031 = "Your personal entry limit has been reached.";
$alert_text_032 = "You will be able to add entries on or after ".$entry_open.".";
$alert_text_033 = "Account registration will open ".$reg_open.".";
$alert_text_034 = "Please return then to register your account.";
$alert_text_036 = "Entry registration will open ".$entry_open.".";
$alert_text_037 = "Please return then to add your entries to the system.";
$alert_text_039 = "Judge and steward registration will open ".$judge_open.".";
$alert_text_040 = "Please return then to register as a judge or steward.";
$alert_text_042 = "Entry registration is open!";
$alert_text_043 = "A total of ".$total_entries." entries have been added to the system as of ".$current_time.".";
$alert_text_044 = "Registration will close ";
$alert_text_046 = "The entry limit nearly reached!";
$alert_text_047 = $total_entries." of ".$row_limits['prefsEntryLimit']." maximum entries have been added into the system as of ".$current_time.".";
$alert_text_049 = "The entry limit has been reached.";
$alert_text_050 = "The limit of ".$row_limits['prefsEntryLimit']." entries has been reached. No further entries will be accepted.";
$alert_text_052 = "The paid entry limit has been reached.";
$alert_text_053 = "The limit of ".$row_limits['prefsEntryLimitPaid']." <em>paid</em> entries has been reached. No further entries will be accepted.";
$alert_text_055 = "Registration is closed.";
$alert_text_056 = "If you already registered an account,";
$alert_text_057 = "log in here"; // lower-case and missing punctuation intentional
$alert_text_059 = "Entry registration is closed.";
$alert_text_060 = "A total of ".$total_entries." entries were added into the system.";
$alert_text_062 = "Entry drop-off is closed.";
$alert_text_063 = "Entry bottles are no longer accepted at drop-off locations.";
$alert_text_065 = "Entry shipping is closed.";
$alert_text_066 = "Entry bottles are no longer accepted at the shipping location.";
$alert_text_068 = $j_s_text." registration open.";
$alert_text_069 = "Register here"; // missing punctuation intentional
$alert_text_070 = $j_s_text." registration will close ".$judge_closed.".";
$alert_text_072 = "The limit of registered judges has been reached.";
$alert_text_073 = "No further judge registrations will be accepted.";
$alert_text_074 = "Registering as a steward is still available.";
$alert_text_076 = "The limit of registered stewards has been reached.";
$alert_text_077 = "No further steward registrations will be accepted.";
$alert_text_078 = "Registering as a judge is still available.";
$alert_text_080 = "Password incorrect.";
$alert_text_081 = "Password accepted.";

$alert_email_valid = "Email format is valid!";
$alert_email_not_valid = "Email format is not valid!";
$alert_email_in_use = "The email address you entered is already in use. Please choose another.";
$alert_email_not_in_use = "Congratulations! The email address you entered is not in use.";

// ----------------------------------------------------------------------------------
// Public Pages
// ----------------------------------------------------------------------------------

// v2.1.9
$comps_text_000 = "Choose the competition you wish to access from the list below.";
$comps_text_001 = "Current competition:";
$comps_text_002 = "There are no competitions with entry windows open now.";
$comps_text_003 = "There are no competitions with entry windows closing in the next 7 days.";

// -------------------- BeerXML --------------------

$beerxml_text_000 = "Importing entries is not available.";
$beerxml_text_001 = "has been uploaded and the brew has been added to your list of entries.";
$beerxml_text_002 = "Sorry, that file type is not allowed to be uploaded.  Only .xml file extensions are allowed.";
$beerxml_text_003 = "The file size is over 2MB.  Please adjust the size and try again.";
$beerxml_text_004 = "Invalid file specified.";
$beerxml_text_005 = "However, it has not been confirmed. To confirm your entry, access your entries list for further instructions. Or, you can add upload another BeerXML entry below.";
$beerxml_text_006 = "Your server's version of PHP does not support the BeerXML import feature.";
$beerxml_text_007 = "PHP version 5.x or higher is required &mdash; this server is running PHP version ".$php_version.".";
$beerxml_text_008 = "Browse for your BeerXML compliant file on your hard drive and click <em>Upload</em>.";
$beerxml_text_009 = "Choose BeerXML File";
$beerxml_text_010 = "No file chosen...";
$beerxml_text_011 = "entries added"; // lower-case and missing punctuation intentional
$beerxml_text_012 = "entry added"; // lower-case and missing punctuation intentional

// -------------------- Best of Show --------------------
// None


// -------------------- Brew (Add Entry) --------------------

if ($section == "brew") {
	$brew_text_000 = "Click for specifics about style"; // missing punctuation intentional
	$brew_text_001 = "Judges will not know the name of your entry.";
	$brew_text_002 = "[disabled - style entry limit reached]"; // missing punctuation intentional
	$brew_text_003 = "[disabled - style entry limit reached for user]"; // missing punctuation intentional
	$brew_text_004 = "Specific type, special ingredients, classic style, strength (for beer styles), and/or color are required.";
	$brew_text_005 = "Strength required (mead only)"; // missing punctuation intentional
	$brew_text_006 = "Carbonation level required (mead and cider only)"; // missing punctuation intentional
	$brew_text_007 = "Sweetness level required (mead and cider only)"; // missing punctuation intentional
	$brew_text_008 = "This style requires that you provide specific information for entry.";
	$brew_text_009 = "Requirements for"; // missing punctuation intentional
	$brew_text_010 = "This style requires more information. Please enter in the provided area.";
	$brew_text_011 = "The entry's name is required.";
	$brew_text_012 = "***NOT REQUIRED*** Provide ONLY if you wish the judges to fully consider what you write here when evaluating and scoring your entry. Use to record specifics that you would like judges to consider when evaluating your entry that you have NOT SPECIFIED in other fields (e.g., mash technique, hop variety, honey variety, grape variety, pear variety, etc.).";
	$brew_text_013 = "DO NOT use this field to specify special ingredients, classic style, strength (for beer entries), or color.";
	$brew_text_014 = "Provide only if you wish the judges to fully consider what you specify when evaluating and scoring your entry.";
	$brew_text_015 = "Type of extract (e.g., light, dark) or brand.";
	$brew_text_016 = "Type of grain (e.g., pilsner, pale ale, etc.)";
	$brew_text_017 = "Type of ingredient or name.";
	$brew_text_018 = "Hop name.";
	$brew_text_019 = "Numbers only (e.g., 12.2, 6.6, etc.).";
	$brew_text_020 = "Name of strain (e.g., 1056 American Ale).";
	$brew_text_021 = "Wyeast, White Labs, etc.";
	$brew_text_022 = "1 smackpack, 2 vials, 2000 ml, etc.";
	$brew_text_023 = "Primary fermentation in days.";
	$brew_text_024 = "Saccharification rest, etc.";
	$brew_text_025 = "Secondary fermentation in days.";
	$brew_text_026 = "Other fermentation in days.";
}

// -------------------- Brewer (Account) --------------------

if (($section == "brewer") || ($section == "register") || ($section == "step2") || (($section == "admin") && ($go == "entrant")) || (($section == "admin") && ($go == "judge")) || (($section == "admin") && ($go == "steward"))) {
	$brewer_text_000 = "Please enter only <em>one</em> person's name.";
	$brewer_text_001 = "Choose one. This question will be used to verify your identity should you forget your password.";
	$brewer_text_003 = "To be considered for a GABF Pro-Am brewing opportunity you must be an AHA member.";
	$brewer_text_004 = "Provide any information that you believe the competition organizer, judge coordinator, or competition staff should know (e.g., allergies, special dietary restrictions, shirt size, etc.).";
	$brewer_text_005 = "Not Applicable / Shipping Entries";
	$brewer_text_006 = "Are you willing and qualified to serve as a judge in this competition?";
	$brewer_text_007 = "Have you passed the BJCP Mead Judge exam?";
	$brewer_text_008 = "* The <em>Non-BJCP</em> rank is for those who haven't taken the BJCP Beer Judge Entrance Exam, and are <em>not</em> a professional brewer.";
	$brewer_text_009 = "** The <em>Provisional</em> rank is for those have taken and passed the BJCP Beer Judge Entrance Exam, but have not yet taken the BJCP Beer Judging Exam.";
	$brewer_text_010 = "Only the first two designations will appear on your printed scoresheet labels.";
	$brewer_text_011 = "How many competitions have you previously served as a <strong>".strtolower($label_judge)."</strong>?";
	$brewer_text_012 = "For preferences ONLY. Leaving a style unchecked indicates that you are available to judge it – there's no need to check all styles that your available to judge.";
	$brewer_text_013 = "Click or tap the button above to expand the non-preferred styles to judge list.";
	$brewer_text_014 = "There is no need to mark those styles for which you have entries; the system will not allow you to be assigned to any table where you have entries.";
	$brewer_text_015 = "Are you willing to serve as a steward in this competition?";
	$brewer_text_016 = "My participation in this judging is entirely voluntary. I know that participation in this judging involves consumption of alcoholic beverages and that this consumption may affect my perceptions and reactions.";
	$brewer_text_017 = "Click or tap the button above to expand the preferred styles to judge list.";
	$brewer_text_018 = "By checking this box, I am effectively signing a legal document wherein I accept responsibility for my conduct, behavior and actions and completely absolve the competition and its organizers, individually or collectively, of responsibility for my conduct, behavior and actions.";

	// v2.1.9
	$brewer_text_019 = "If you are planning to serve as a judge in any competition, click or tap the button above to enter your judge-related information.";
	$brewer_text_020 = "Are you willing to serve as a staff member in this competition?";
	$brewer_text_021 = "Competition staff are people that serve in various roles to assist in the organization and execution of the competition before, during, and after judging. Judges and stewards can also serve as staff members. Staff members can earn BJCP points if the competition is sanctioned.";

}

// -------------------- Contact --------------------

if ($section == "contact") {

	$contact_text_000 = "Use the links below to contact individuals involved with coordinating this competition:";
	$contact_text_001 = "Use the form below to contact a competition official. All fields with a star are required.";
	$contact_text_002 = "Additionally, a copy has been sent to the email address you provided.";
	$contact_text_003 = "Would you like to send another message?";

}

// -------------------- Default (Home) -------------------

if (($section == "default") || ($section == "results")) { // Changed for 2.1.14

	$default_page_text_000 = "No drop-off locations have been specified.";
	$default_page_text_001 = "Add a drop-off location?";
	$default_page_text_002 = "No judging dates/locations have been specified.";
	$default_page_text_003 = "Add a judging location?";
	$default_page_text_004 = "Winning Entries";
	$default_page_text_005 = "Winners will be posted on or after";
	$default_page_text_006 = "Welcome";
	$default_page_text_007 = "See your account details and list of entries.";
	$default_page_text_008 = "View your account details here.";
	$default_page_text_009 = "Best of Show Winners";
	$default_page_text_010 = "Winning Entries";
	$default_page_text_011 = "You only need to register your information once and can return to this site to enter more brews or edit the brews you've entered.";
	$default_page_text_012 = "You can even pay your entry fees online if you wish.";
	$default_page_text_013 = "Competition Official";
	$default_page_text_014 = "Competition Officials";
	$default_page_text_015 = "You can send an email to any of the following individuals via ";
	$default_page_text_016 = "is proud to have the following";
	$default_page_text_017 = "for the";
	$default_page_text_018 = "Download the Best of Show winners in PDF format.";
	$default_page_text_019 = "Download the Best of Show winners in HTML format.";
	$default_page_text_020 = "Download the winning entries in PDF format.";
	$default_page_text_021 = "Download the winning entries in HTML format.";
	$default_page_text_022 = "Thank you for your interest in the";
	$default_page_text_023 = "organized by";

	$reg_open_text_000 = "Judge and Steward Registration is";
	$reg_open_text_001 = "Open";
	$reg_open_text_002 = "If you <em>have not</em> registered and are willing to be a volunteer,";
	$reg_open_text_003 = "please register";
	$reg_open_text_004 = "If you <em>have</em> registered, log in and then choose <em>Edit Account</em> from the My Account menu indicated by the";
	$reg_open_text_005 = "icon on the top menu.";
	$reg_open_text_006 = "Since you have already registered, you can";
	$reg_open_text_007 = "check your account info";
	$reg_open_text_008 = "to see whether you have indicated that you are willing to judge and/or steward.";
	$reg_open_text_009 = "If you are willing to judge or steward, please return to register on or after";
	$reg_open_text_010 = "Entry Registration is";
	$reg_open_text_011 = "To add your entries into the system";
	$reg_open_text_012 = "please proceed through the registration process";
	$reg_open_text_013 = "if you already have an account.";
	$reg_open_text_014 = "use the add an entry form";

	// v2.1.9
	$reg_open_text_015 = "Judge Registration is";
	$reg_open_text_016 = "Steward Registration is";
	$reg_closed_text_000 = "Thanks and Good Luck To All Who Entered the";
	$reg_closed_text_001 = "There are";
	$reg_closed_text_002 = "registered participants, judges, and stewards.";
	$reg_closed_text_003 = "registered entries and";
	$reg_closed_text_004 = "registered participants, judges, and stewards.";
	$reg_closed_text_005 = "As of";
	$reg_closed_text_006 = "received and processed entries (this number will update as entries are picked up from drop-off locations and organized for judging).";
	$reg_closed_text_007 = "Competition judging dates are yet to be determined. Please check back later.";
	$reg_closed_text_008 = "Map to";
	$judge_closed_000 = "Thanks to all who participated in the";
	$judge_closed_001 = "There were";
	$judge_closed_002 = "entries judged and";
	$judge_closed_003 = "registered participants, judges, and stewards.";

}

// -------------------- Entry Info --------------------

if ($section == "entry") {

	$entry_info_text_000 = "You will be able to create your account beginning";
	$entry_info_text_001 = "through";
	$entry_info_text_002 = "Judges and stewards may register beginning";
	$entry_info_text_003 = "per entry";
	$entry_info_text_004 = "You can create your account today through";
	$entry_info_text_005 = "Judges and stewards may register now through";
	$entry_info_text_006 = "Registrations for";
	$entry_info_text_007 = "judges and stewards only";
	$entry_info_text_008 = "accepted through";
	$entry_info_text_009 = "Registration is <strong class=\"text-danger\">closed</strong>.";
	$entry_info_text_010 = "Welcome";
	$entry_info_text_011 = "See your account details and list of entries.";
	$entry_info_text_012 = "View your account information here.";
	$entry_info_text_013 = "per entry after the";
	$entry_info_text_014 = "You will be able to add your entries to the system beginning";
	$entry_info_text_015 = "You can add your entries to the system today through";
	$entry_info_text_016 = "Entry registration is <strong class=\"text-danger\">closed</strong>.";
	$entry_info_text_017 = "for unlimited entries.";
	$entry_info_text_018 = "for AHA members.";
	$entry_info_text_019 = "There is a limit of";
	$entry_info_text_020 = "entries for this competition.";
	$entry_info_text_021 = "Each entrant is limited to";
	$entry_info_text_022 = strtolower($label_entry);
	$entry_info_text_023 = strtolower($label_entries);
	$entry_info_text_024 = "entry per subcategory";
	$entry_info_text_025 = "entries per subcategory";
	$entry_info_text_026 = "exceptions are detailed below";
	$entry_info_text_027 = strtolower($label_subcategory);
	$entry_info_text_028 = "subcategories";
	$entry_info_text_029 = "entry for the following";
	$entry_info_text_030 = "entries for for the following";
	$entry_info_text_031 = "After creating your account and adding your entries to the system, you must pay your entry fee(s). Accepted payment methods are:";
	$entry_info_text_032 = $label_cash;
	$entry_info_text_033 = $label_check.", made out to";
	$entry_info_text_034 = "Credit/debit card and e-check, via PayPal";
	$entry_info_text_035 = "Competition judging dates are yet to be determined. Please check back later.";
	$entry_info_text_036 = "Entry bottles accepted at our shipping location from";
	$entry_info_text_037 = "Ship entries to:";
	$entry_info_text_038 = "Carefully pack your entries in a sturdy box. Line the inside of your carton with a plastic trash bag. Partition and pack each bottle with adequate packaging material. Please do not over pack!";
	$entry_info_text_039 = "Write clearly: <em>Fragile. This Side Up.</em> on the package. Please only use bubble wrap as your packing material.";
	$entry_info_text_040 = "Enclose <em>each</em> of your bottle labels in a small zip-top bag before attaching to their respective bottles. This way it makes it possible for the organizer to identify specifically which entry has broken if there is damage during shipment.";
	$entry_info_text_041 = "Every reasonable effort will be made to contact entrants whose bottles have broken to make arrangements for sending replacement bottles.";
	$entry_info_text_042 = "If you live in the United States, please note that it is <strong>illegal</strong> to ship your entries via the United States Postal Service (USPS). Private shipping companies have the right to refuse your shipment if they are informed that the package contains glass and/or alcoholic beverages. Be aware that entries mailed internationally are often required by customs to have proper documentation. These entries might be opened and/or returned to the shipper by customs officials at their discretion. It is solely your responsibility to follow all applicable laws and regulations.";
	$entry_info_text_043 = "Entry bottles accepted at our drop-off locations from";
	$entry_info_text_044 = "Map to";
	$entry_info_text_045 = "Click/Tap for Required Entry Info";
	$entry_info_text_046 = "If a style's name is hyperlinked, it has specific entry requirements. Click or tap on the name to view the subcategory's requirements.";

}

// -------------------- Footer --------------------

// -------------------- Judge Info --------------------

// -------------------- List (User Entry List) --------------------

$brewer_entries_text_000 = "There is a known issue with printing from the Firefox browser.";
$brewer_entries_text_001 = "You have unconfirmed entries.";
$brewer_entries_text_002 = "For each entry below with a <span class=\"fa fa-lg fa-exclamation-circle text-danger\"></span> icon, click the <span class=\"fa fa-lg fa-pencil text-primary\"></span> icon to review and confirm all your entry data. Unconfirmed entries may be deleted from the system without warning.";
$brewer_entries_text_003 = "You CANNOT pay for your entries until all entries are confirmed.";
$brewer_entries_text_004 = "You have entries that require you to define a specific type, special ingredients, classic style, strength, and/or color.";
$brewer_entries_text_005 = "For each entry below with a <span class=\"fa fa-lg fa-exclamation-circle text-danger\"></span> icon, click the <span class=\"fa fa-lg fa-pencil text-primary\"></span> icon to enter the required information. Entries without a specific type, special ingredients, classic style, strength, and/or color in categories that require them may be deleted by the system without warning.";
$brewer_entries_text_006 = "Download judges&rsquo; scoresheets for";
$brewer_entries_text_007 = "Style NOT Entered";
$brewer_entries_text_008 = "Entry Form and";
$brewer_entries_text_009 = "Bottle Labels";
$brewer_entries_text_010 = "Print Recipe Form for";
$brewer_entries_text_011 = "Also, you will not be able to add another entry since the entry limit for the competition has been reached. Click Cancel in this box and then edit the entry instead if you wish to keep it.";
$brewer_entries_text_012 = "Are you sure you want to delete the entry called";
$brewer_entries_text_013 = "You will be able to add entries on or after";
$brewer_entries_text_014 = "You have not added any entries to the system.";
$brewer_entries_text_015 = "You cannot delete your entry at this time.";

// -------------------- Login --------------------

// -------------------- Past Winners --------------------
if ($section == "past_winners") {
	$past_winners_text_000 = "View past winners:";
}

// -------------------- Pay for Entries --------------------

$pay_text_000 = "Since the account registration, entry registration, shipping, and drop-off deadlines have all passed, payments are no longer being accepted.";
$pay_text_001 = "Contact a competition official if you have any questions.";
$pay_text_002 = "the following are your options for paying your entry fees.";
$pay_text_003 = "Fees are";
$pay_text_004 = "per entry";
$pay_text_005 = "per entry after the";
$pay_text_006 = "for unlimited entries";
$pay_text_007 = "Your fees have been discounted to";
$pay_text_008 = "Your total entry fees are";
$pay_text_009 = "You need to pay";
$pay_text_010 = "Your fees have been marked as paid. Thank you!";
$pay_text_011 = "You currently have";
$pay_text_012 = "unpaid confirmed";
$pay_text_013 = "Attach a check for the entire entry amount to one of your bottles. Checks should be made out to";
$pay_text_014 = "Your check carbon or cashed check is your entry receipt.";
$pay_text_015 = "Attach cash payment for the entire entry amount in a <em>sealed envelope</em> to one of  your bottles.";
$pay_text_016 = "Your returned score sheets will serve as your entry receipt.";
$pay_text_017 = "Your payment confirmation email is your entry receipt. Include a copy with your entries as proof of payment.";
$pay_text_018 = "Click the <em>Pay with PayPal</em> button below to pay online.";
$pay_text_019 = "Please note that a transaction fee of";
$pay_text_020 = "will be added into your total.";
$pay_text_021 = "To make sure your PayPal payment is marked <strong>paid</strong> on <strong>this site</strong>, make sure to click the <em>Return to...</em> link on PayPal&rsquo;s confirmation screen <strong>after</strong> you have sent your payment. Also, make sure to print your payment receipt and attach it to one of your entry bottles.";
$pay_text_022 = "Make Sure to Click <em>Return To...</em> After Paying Your Fees";
$pay_text_023 = "Enter the code supplied by the competition organizers for a discounted entry fee.";
$pay_text_024 = $pay_text_010;
$pay_text_025 = "You have not logged any entries yet.";
$pay_text_026 = "You cannot pay for your entries because one or more of your entries is unconfirmed.";
$pay_text_027 = "Click <em>My Account</em> above to review your unconfirmed entries.";
$pay_text_028 = "You have unconfirmed entries that are <em>not</em> reflected in your fee totals below.";
$pay_text_029 = "Please go to your entry list to confirm all your entry data. Unconfirmed entries may be deleted from the system without warning.";

// -------------------- QR --------------------

    // Ignore the next four lines
	if (strpos($view, "^") !== FALSE) {
		$qr_text_019 = sprintf("%04d",$checked_in_numbers[0]);
		if (is_numeric($checked_in_numbers[1])) $qr_text_020 = sprintf("%06d",$checked_in_numbers[1]);
		else $qr_text_020 = $checked_in_numbers[1];
	}

	$qr_text_000 = $alert_text_080;
	$qr_text_001 = $alert_text_081;

    // Begin translations here
	if (strpos($view, "^") !== FALSE) $qr_text_002 = sprintf("Entry number <span class=\"text-danger\">%s</span> is checked in with <span class=\"text-danger\">%s</span> as its judging number.",$qr_text_019,$qr_text_020); else $qr_text_002 = "";
	$qr_text_003 = "If this judging number is <em>not</em> correct, <strong>re-scan the code and re-enter the correct judging number.";
	if (strpos($view, "^") !== FALSE) $qr_text_004 = sprintf("Entry number %s is checked in.",$qr_text_019); else $qr_text_004 = "";
	if (strpos($view, "^") !== FALSE) $qr_text_005 = sprintf("Entry number %s was not found in the database. Set the bottle(s) aside and alert the competition organizer.",$qr_text_019); else $qr_text_005 = "";
	if (strpos($view, "^") !== FALSE) $qr_text_006 = sprintf("The judging number you entered - %s - is already assigned to entry number %s.",$qr_text_020,$qr_text_019); else $qr_text_006 = "";
	$qr_text_007 = "QR Code Entry Check-In";
	$qr_text_008 = "To check in entries via QR code, please provide the correct password. You will only need to provide the password once per session - be sure to keep the QR Code scanning app open.";
	$qr_text_009 = "Assign a judging number and/or box number to entry";
	$qr_text_010 = "ONLY inupt a judging number if your competition is using judging number labels at sorting.";
	$qr_text_011 = "Six numbers with leading zeros - e.g., 000021.";
	$qr_text_012 = "Be sure to double-check your input and affix the appropriate judging number labels to each bottle and bottle label (if applicable).";
	$qr_text_013 = "Judging numbers must be six characters and cannot include the ^ character.";
	$qr_text_014 = "Waiting for scanned QR code input.";
	$qr_text_015 = "Launch or go back to your mobile device's scanning app to scan a QR code.";
	$qr_text_016 = "Need a QR Code scanning app? Search <a class=\"hide-loader\" href=\"https://play.google.com/store/search?q=qr%20code%20scanner&c=apps&hl=en\" target=\"_blank\">Google Play</a> (Android) or <a class=\"hide-loader\" href=\"https://itunes.apple.com/store/\" target=\"_blank\">iTunes</a> (iOS).";
	$qr_text_017 = "A QR Code scanning app is required to utilize this feature.";
	$qr_text_018 = "Launch the app on your mobile device, scan a QR Code located on a bottle label, enter the required password, and check in the entry.";


// -------------------- Registration Open --------------------

// -------------------- Registration Closed --------------------

// -------------------- Register --------------------

if (($section == "register") || ($section == "brewer") || ($action == "register") || ($go == "account") || ($section == "step2")) {

	$register_text_000 = "Is the volunteer ";
	$register_text_001 = "Are you ";
	$register_text_002 = "Registration has closed.";
	$register_text_003 = "Thank you for your interest.";
	$register_text_004 = "The information you provide beyond your first name, last name, and club is strictly for record-keeping and contact purposes.";
	$register_text_005 = "A condition of entry into the competition is providing this information. Your name and club may be displayed should one of your entries place, but no other information will be made public.";
	$register_text_006 = "Reminder: You are only allowed to enter one region and once you have registered at a location, you will NOT be able to change it.";
	$register_text_007 = "Quick";
	$register_text_008 = "Register";
	$register_text_009 = "a Judge";
	$register_text_010 = "a Participant";
	$register_text_011 = "To register, create your online account by filling out the fields below.";
	$register_text_012 = "Quickly add a participant to the competition&rsquo;s judge/steward pool. A dummy address and phone number will be used and a default password of <em>bcoem</em> will be given to each participant added via this screen.";
	$register_text_013 = "Entry into this competition is conducted completely online.";
	$register_text_014 = "To add your entries and/or indicate that you are willing to judge or steward (judges and stewards can also add entries), you will need to create an account on our system.";
	$register_text_015 = "Your email address will be your user name and will be used as a means of information dissemination by the competition staff. Please make sure it is correct.";
	$register_text_016 = "Once you have registered, you can proceed through the entry process.";
	$register_text_017 = "Each entry you add will automatically be assigned a number by the system.";
	$register_text_018 = "Choose one. This question will be used to verify your identity should you forget your password.";
	$register_text_019 = "Please provide an email address.";
	$register_text_020 = "The email addresses you entered don't match.";
	$register_text_021 = "Email addresses serve as user names.";
	$register_text_022 = "Please provide a password.";
	$register_text_023 = "Please provide an answer to your security question.";
	$register_text_024 = "Make your security answer something only you will easily remember!";
	$register_text_025 = "Please provide a first name.";
	$register_text_026 = "Please provide a last name.";
	$register_text_027 = "a Steward";
	$register_text_028 = "Please provide a street address.";
	$register_text_029 = "Please provide a city.";
	$register_text_030 = "Please provide a state or province.";
	$register_text_031 = "Please provide a zip or postal code.";
	$register_text_032 = "Please provide a primary telephone number.";
	$register_text_033 = "Only American Homebrewers Association members are eligible for a Great American Beer Festival Pro-Am opportunity.";
	$register_text_034 = "To register, you must check the box, indicating that you agree to the waiver statement.";

}

// -------------------- Sidebar --------------------

$sidebar_text_000 = "Registrations for judges or stewards";
$sidebar_text_001 = "Registrations for stewards";
$sidebar_text_002 = "Registrations for judges";
$sidebar_text_003 = "Registrations are no longer accepted. The limit of judges and stewards has been reached.";
$sidebar_text_004 = "through";
$sidebar_text_005 = "Account registrations accepted";
$sidebar_text_006 = "is Open for Judges or Stewards Only";
$sidebar_text_007 = "is Open for Stewards Only";
$sidebar_text_008 = "is Open for Judges Only";
$sidebar_text_009 = "Entry registrations accepted";
$sidebar_text_010 = "The competition paid entry limit has been reached.";
$sidebar_text_011 = "The competition entry limit has been reached.";
$sidebar_text_012 = "See your list of entries.";
$sidebar_text_013 = "Click here to pay your fees.";
$sidebar_text_014 = "Entry fees do not include unconfirmed entries.";
$sidebar_text_015 = "You have unconfirmed entries - action is needed to confirm.";
$sidebar_text_016 = "See your list of entries.";
$sidebar_text_017 = "You have";
$sidebar_text_018 = "left before you reach the limit of";
$sidebar_text_019 = "per participant";
$sidebar_text_020 = "You have reached the limit of";
$sidebar_text_021 = "in this competition";
$sidebar_text_022 = "Entry bottles accepted at";
$sidebar_text_023 = "the shipping location";
$sidebar_text_024 = "Competition judging dates are yet to be determined. Please check back later.";
$sidebar_text_025 = "have been added to the system as of";

// -------------------- Sponsors --------------------
// NONE


// -------------------- Styles ---------------------

$styles_entry_text_07C = "The entrant must specify whether the entry is a Munich Kellerbier (pale, based on Helles) or a Franconian Kellerbier (amber, based on Marzen). The entrant may specify another type of Kellerbier based on other base styles such as Pils, Bock, Schwarzbier, but should supply a style description for judges.";
$styles_entry_text_09A = "The entrant must specify whether the entry is a pale or a dark variant.";
$styles_entry_text_10C = "The entrant must specify whether the entry is a pale or a dark variant.";
$styles_entry_text_21B = "Entrant must specify a strength (session: 3.0-5.0%, standard: 5.0-7.5%, double: 7.5-9.5%); if no strength is specified, standard will be assumed. Entrant must specify specific type of Specialty IPA from the library of known types listed in the Style Guidelines, or as amended by the BJCP web site; or the entrant must describe the type of Specialty IPA and its key characteristics in comment form so judges will know what to expect. Entrants may specify specific hop varieties used, if entrants feel that judges may not recognize the varietal characteristics of newer hops. Entrants may specify a combination of defined IPA types (e.g., Black Rye IPA) without providing additional descriptions. Entrants may use this category for a different strength version of an IPA defined by its own BJCP subcategory (e.g., session-strength American or English IPA) - except where an existing BJCP subcategory already exists for that style (e.g., double [American] IPA). Currently Defined Types: Black IPA, Brown IPA, White IPA, Rye IPA, Belgian IPA, Red IPA.";
$styles_entry_text_23F = "The type of fruit used must be specified. The brewer must declare a carbonation level (low, medium, high) and a sweetness level (low/none, medium, high).";
$styles_entry_text_24C = "Entrant must specify blond, amber, or brown biere de garde. If no color is specified, the judge should attempt to judge based on initial observation, expecting a malt flavor and balance that matches the color.";
$styles_entry_text_25B = "The entrant must specify the strength (table, standard, super) and the color (pale, dark).";
$styles_entry_text_27A = "The entrant must either specify a style with a BJCP-supplied description, or provide a similar description for the judges of a different style. If a beer is entered with just a style name and no description, it is very unlikely that judges will understand how to judge it. Currently defined examples: Gose, Piwo Grodziskie, Lichtenhainer, Roggenbier, Sahti, Kentucky Common, Pre-Prohibition Lager, Pre-Prohibition Porter, London Brown Ale.";
$styles_entry_text_28A = "The entrant must specify either a base beer style (classic BJCP style, or a generic style family) or provide a description of the ingredients/specs/desired character. The entrant must specify if a 100% Brett fermentation was conducted. The entrant may specify the strain(s) of Brettanomyces used, along with a brief description of its character.";
$styles_entry_text_28B = "The entrant must specify a description of the beer, identifying the yeast/bacteria used and either a base style or the ingredients/specs/target character of the beer.";
$styles_entry_text_28C = "Entrant must specify the type of fruit, spice, herb, or wood used. Entrant must specify a description of the beer, identifying the yeast/bacteria used and either a base style or the ingredients/specs/target character of the beer. A general description of the special nature of the beer can cover all the required items.";
$styles_entry_text_29A = "The entrant must specify a base style; the declared style does not have to be a Classic Style. The entrant must specify the type of fruit used. Soured fruit beers that aren't lambics should be entered in the American Wild Ale category.";
$styles_entry_text_29B = "The entrant must specify a base style; the declared style does not have to be a Classic Style. The entrant must specify the type of fruit and spices, herbs, or vegetables (SHV) used; individual SHV ingredients do not need to be specified if a well-known blend of spices is used (e.g., apple pie spice).";
$styles_entry_text_29C = "The entrant must specify a base style; the declared style does not have to be a Classic Style. The entrant must specify the type of fruit used. The entrant must specify the type of additional fermentable sugar or special process employed.";
$styles_entry_text_30A = "The entrant must specify a base style; the declared style does not have to be a Classic Style. The entrant must specify the type of spices, herbs, or vegetables used; individual ingredients do not need to be specified if a well-known blend of spices is used (e.g., apple pie spice).";
$styles_entry_text_30B = "The entrant must specify a base style; the declared style does not have to be a Classic Style. The entrant must specify the type of spices, herbs, or vegetables used; individual ingredients do not need to be specified if a well-known blend of spices is used (e.g., pumpkin pie spice). The beer must contain spices, and may contain vegetables and/or sugars.";
$styles_entry_text_30C = "The entrant must specify a base style; the declared style does not have to be a Classic Style. The entrant must specify the type of spices, sugars, fruits, or additional fermentables used; individual ingredients do not need to be specified if a well-known blend of spices is used (e.g., mulling spice).";
$styles_entry_text_31A = "The entrant must specify a base style; the declared style does not have to be a Classic Style. The entrant must specify the type of alternative grain used.";
$styles_entry_text_31B = "The entrant must specify a base style; the declared style does not have to be a Classic Style. The entrant must specify the type of sugar used.";
$styles_entry_text_32A = "The entrant must specify a Classic Style base beer. The entrant must specify the type of wood or smoke if a varietal smoke character is noticeable.";
$styles_entry_text_32B = "The entrant must specify a base beer style; the base beer does not have to be a Classic Style. The entrant must specify the type of wood or smoke if a varietal smoke character is noticeable. The entrant must specify the additional ingredients or processes that make this a specialty smoked beer.";
$styles_entry_text_33A = "The entrant must specify the type of wood used and the char level (if charred). The entrant must specify the base style; the base style can be either a classic BJCP style (i.e., a named subcategory) or may be a generic type of beer (e.g., porter, brown ale). If an unusual wood has been used, the entrant must supply a brief description of the sensory aspects the wood adds to beer.";
$styles_entry_text_33B = "The entrant must specify the additional alcohol character, with information about the barrel if relevant to the finished flavor profile. The entrant must specify the base style; the base style can be either a classic BJCP style (i.e., a named subcategory) or may be a generic type of beer (e.g., porter, brown ale). If an unusual wood or ingredient has been used, the entrant must supply a brief description of the sensory aspects the ingredients adds to the beer.";
$styles_entry_text_34A = "The entrant must specify the name of the commercial beer being cloned, specifications (vital statistics) for the beer, and either a brief sensory description or a list of ingredients used in making the beer. Without this information, judges who are unfamiliar with the beer will have no basis for comparison.";
$styles_entry_text_34B = "The entrant must specify the styles being mixed. The entrant may provide an additional description of the sensory profile of the beer or the vital statistics of the resulting beer.";
$styles_entry_text_34C = " The entrant must specify the special nature of the experimental beer, including the special ingredients or processes that make it not fit elsewhere in the guidelines. The entrant must provide vital statistics for the beer, and either a brief sensory description or a list of ingredients used in making the beer. Without this information, judges will have no basis for comparison.";
$styles_entry_text_M1A = "Entry Instructions: Entrants must specify carbonation level and strength. Sweetness is assumed to be DRY in this category. Entrants may specify honey varieties.";
$styles_entry_text_M1B = "Entrants must specify carbonation level and strength. Sweetness is assumed to be SEMI-SWEET in this category. Entrants MAY specify honey varieties.";
$styles_entry_text_M1C = "Entrants MUST specify carbonation level and strength. Sweetness is assumed to be SWEET in this category. Entrants MAY specify honey varieties.";
$styles_entry_text_M2A = "Entrants must specify carbonation level, strength, and sweetness. Entrants may specify honey varieties. Entrants may specify the varieties of apple used; if specified, a varietal character will be expected. Products with a relatively low proportion of honey are better entered as a Specialty Cider. A spiced cyser should be entered as a Fruit and Spice Mead. A cyser with other fruit should be entered as a Melomel. A cyser with additional ingredients should be entered as an Experimental mead.";
$styles_entry_text_M2B = "Entrants must specify carbonation level, strength, and sweetness. Entrants may specify honey varieties. Entrants may specify the varieties of grape used; if specified, a varietal character will be expected. A spiced pyment (hippocras) should be entered as a Fruit and Spice Mead. A pyment made with other fruit should be entered as a Melomel. A pyment with other ingredients should be entered as an Experimental Mead.";
$styles_entry_text_M2C = "Entrants must specify carbonation level, strength, and sweetness. Entrants may specify honey varieties. Entrants must specify the varieties of fruit used. A mead made with both berries and non-berry fruit (including apples and grapes) should be entered as a Melomel. A berry mead that is spiced should be entered as a Fruit and Spice Mead. A berry mead containing other ingredients should be entered as an Experimental Mead.";
$styles_entry_text_M2D = "Entrants must specify carbonation level, strength, and sweetness. Entrants may specify honey varieties. Entrants must specify the varieties of fruit used. A stone fruit mead that is spiced should be entered as a Fruit and Spice Mead. A stone fruit mead that contains non-stone fruit should be entered as a Melomel. A stone fruit mead that contains other ingredients should be entered as an Experimental Mead.";
$styles_entry_text_M2E = "Entrants must specify carbonation level, strength, and sweetness. Entrants may specify honey varieties. Entrants must specify the varieties of fruit used. A melomel that is spiced should be entered as a Fruit and Spice Mead. A melomel containing other ingredients should be entered as an Experimental Mead. Melomels made with either apples or grapes as the only fruit source should be entered as Cysers and Pyments, respectively. Melomels with apples or grapes, plus other fruit should be entered in this category, not Experimental.";
$styles_entry_text_M3A = "Entrants must specify carbonation level, strength, and sweetness. Entrants may specify honey varieties. Entrants must specify the types of spices used, (although well-known spice blends may be referred to by common name, such as apple pie spices). Entrants must specify the types of fruits used. If only combinations of spices are used, enter as a Spice, Herb, or Vegetable Mead. If only combinations of fruits are used, enter as a Melomel. If other types of ingredients are used, enter as an Experimental Mead.";
$styles_entry_text_M3B = "Entrants MUST specify carbonation level, strength, and sweetness. Entrants MAY specify honey varieties. Entrants MUST specify the types of spices used (although well-known spice blends may be referred to by common name, such as apple pie spices).";
$styles_entry_text_M4A = "Entrants MUST specify carbonation level, strength, and sweetness. Entrants MAY specify honey varieties. Entrants MAY specify the base style or beer or types of malt used. Products with a relatively low proportion of honey should be entered in the Spiced Beer category as a Honey Beer.";
$styles_entry_text_M4B = "Entrants MUST specify carbonation level, strength, and sweetness. Entrants MAY specify honey varieties. Entrants MUST specify the special nature of the mead, providing a description of the mead for judges if no such description is available from the BJCP.";
$styles_entry_text_M4C = "Entrants MUST specify carbonation level, strength, and sweetness. Entrants MAY specify honey varieties. Entrants MUST specify the special nature of the mead, whether it is a combination of existing styles, an experimental mead, or some other creation. Any special ingredients that impart an identifiable character MAY be declared.";
$styles_entry_text_C1E = "Entrants MUST specify carbonation level (3 levels). Entrants MUST specify sweetness (5 categories). Entrants MUST state variety of pear(s) used.";
$styles_entry_text_C2A = "Entrants MUST specify if the cider was barrel-fermented or aged. Entrants MUST specify carbonation level (3 levels). Entrants MUST specify sweetness (5 levels).";
$styles_entry_text_C2B = "Entrants MUST specify carbonation level (3 levels). Entrants MUST specify sweetness (5 categories). Entrants MUST specify all fruit(s) and/or fruit juice(s) added.";
$styles_entry_text_C2C = "Entrants MUST specify carbonation level (3 levels). Entrants MUST specify sweetness (5 levels).";
$styles_entry_text_C2D = "Entrants MUST specify starting gravity, final gravity or residual sugar, and alcohol level. Entrants MUST specify carbonation level (3 levels).";
$styles_entry_text_C2E = "Entrants MUST specify carbonation level (3 levels). Entrants MUST specify sweetness (5 categories). Entrants MUST specify all botanicals added. If hops are used, entrant must specify variety/varieties used.";
$styles_entry_text_C2F = "Entrants MUST specify all ingredients. Entrants MUST specify carbonation level (3 levels). Entrants MUST specify sweetness (5 categories).";


// -------------------- User (Edit Email) --------------------

$user_text_000 = "A new email address is required and must be in valid form.";
$user_text_001 = "Enter the old password.";
$user_text_002 = "Enter the new password.";
$user_text_003 = "Please check this box if you wish to proceed with changing your email address.";

// -------------------- Volunteers --------------------

if ($section == "volunteers") {

	$volunteers_text_000 = "If you have registered,";
	$volunteers_text_001 = "and then choose <em>Edit Account</em> from the My Account menu indicated by the";
	$volunteers_text_002 = "icon on the top menu";
	$volunteers_text_003 = "and";
	$volunteers_text_004 = "If you have <em>not</em> registered and are willing to be a judge or steward, please register";
	$volunteers_text_005 = "Since you have already registered,";
	$volunteers_text_006 = "access your account";
	$volunteers_text_007 = "to see if you have volunteered to be a judge or steward";
	$volunteers_text_008 = "If you are willing to judge or steward, please return to register on or after";

	// v2.1.9
	$volunteers_text_009 = "If you would like to volunteer to be a competition staff member, please register or update your account to indicate that you wish to be a part of the competition staff.";
	$volunteers_text_010 = "";

}

$login_text_000 = "You are already logged in.";
$login_text_001 = "There is no email address in the system that matches the one you entered.";
$login_text_002 = "Try again?";
$login_text_003 = "Have you registered your account yet?";
$login_text_004 = "Forgot your password?";
$login_text_005 = "Reset it";
$login_text_006 = "To reset your password, enter the email address you used when you registered.";
$login_text_007 = "Verify";
$login_text_008 = "Randomly generated.";
$login_text_009 = "<strong>Unavailable.</strong> Your account was created by an administrator and your &quot;secret answer&quot; was randomly generated. Please contact a website administrator to recover or change your password.";
$login_text_010 = "Or, use the email option below.";
$login_text_011 = "Your security question is...";
$login_text_012 = "If you didn't receive the email,";
$login_text_013 = "An email will be sent to you with your verification question and answer. Be sure to check your SPAM folder.";
$login_text_014 = "click here to resend it to";
$login_text_015 = "If you can't remember the answer to your security question, contact a competition official or site administrator.";
$login_text_016 = "Get it emailed to";

// -------------------- Winners --------------------

$winners_text_000 = "No winners have been entered for this table. Please check back later.";
$winners_text_001 = "Winning entries have not been posted yet. Please check back later.";
$winners_text_002 = "Your chosen award structure is to award places by table. Select the award places for the table as a whole below.";
$winners_text_003 = "Your chosen award structure is to award places by category. Select the award places for each overall category below (there may be more than one at this table).";
$winners_text_004 = "Your chosen award structure is to award places by subcategory. Select the award places for each subcategory below (there may be more than one at this table).";

// ----------------------------------------------------------------------------------
// Output
// ----------------------------------------------------------------------------------

$output_text_000 = "Thank you for participating our competition";
$output_text_001 = "A summary of your entries, scores, and places is below.";
$output_text_002 = "Summary for";
$output_text_003 = "entries were judged";
$output_text_004 = "Your scoresheets could not be properly generated. Please contact the organizers of the competition.";
$output_text_005 = "No judge/steward assignments have been defined";
$output_text_006 = "for this location";
$output_text_007 = "If you would like to print blank table cards, close this window and choose <em>Print Table Cards: All Tables</em> from the <em>Reporting</em> menu.";
$output_text_008 = "Please be sure to check if your BJCP Judge ID is correct. If it is not, or if you have one and it is not listed, please enter it on the form.";
$output_text_009 = "If your name is not on the list below, sign in on the attached sheet(s).";
$output_text_010 = "To receive judging credit, please be sure to enter your BJCP Judge ID correctly and legibly.";
$output_text_011 = "No assignments have been made.";
$output_text_012 = "Total entries at this location";
$output_text_013 = "No participants provided notes to organizers.";
$output_text_014 = "The following are the notes to organizers entered by judges or stewards.";
$output_text_015 = "No participants provided notes to organizers.";
$output_text_016 = "Post-Judging Entry Inventory";
$output_text_017 = "If there are no entries showing below, flights at this table have not been assigned to rounds.";
$output_text_018 = "If entries are missing, all entries have not been assigned to a flight or round OR they have been assigned to a different round.";
$output_text_019 = "If there are no entries below, this table has not been assigned to a round.";
$output_text_020 = "No entries are eligible.";
$output_text_021 = "Entry Number / Judging Number Cheat Sheet";
$output_text_022 = "The points in this report are derived from the official BJCP Sanctioned Competition Requirements, available at";
$output_text_023 = "includes Best of Show";
$output_text_024 = "BJCP Points Report";
$output_text_025 = "Total Staff Points Available";
$output_text_026 = "Styles in this category are not accepted in this competition.";
$output_text_027 = "link to Beer Judge Certification Program Style Guidelines";

// ----------------------------------------------------------------------------------
// Maintenance
// ----------------------------------------------------------------------------------

// v2.1.9
$maintenance_text_000 = "The site administrator has taken the site offline to undergo maintenance.";
$maintenance_text_001 = "Please check back later.";

// ----------------------------------------------------------------------------------
// Version 2.1.10-2.1.12 Additions
// ----------------------------------------------------------------------------------

// -------------------- Labels --------------------
$label_entry_numbers = "Entry Number(s)"; // For PayPal IPN Email
$label_status = "Status"; // For PayPal IPN Email
$label_transaction_id = "Transaction ID"; // For PayPal IPN Email
$label_organization = "Organization";
$label_ttb = "TTB Number";
$label_username = "Username";
$label_from = "From"; // For email headers
$label_to = "To"; // For email headers
$label_varies = "Varies";
$label_styles_accepted = "Styles Accepted";
$label_judging_styles = "Judging Styles";
$label_select_club = "Select or Search for Your Club";
$label_select_style = "Select or Search for Your Entry's Style";
$label_select_country = "Select or Search Your Country";
$label_select_dropoff = "Select Your Drop-Off Location";
$label_club_enter = "Enter Club Name";
$label_secret_05 = "What is your maternal grandmother&rsquo;s maiden name?";
$label_secret_06 = "What was the first name of your first girlfriend or boyfriend?";
$label_secret_07 = "What was the make and model of your first vehicle?";
$label_secret_08 = "What was the last name of your third grade teacher?";
$label_secret_09 = "In what city or town did you meet your significant other?";
$label_secret_10 = "What was the first name of your best friend in sixth grade?";
$label_secret_11 = "What is the name your favorite musical artist or group?";
$label_secret_12 = "What was your childhood nickname?";
$label_secret_13 = "What is the last name of the teacher who gave you your first failing grade?";
$label_secret_14 = "What is the name of your favorite childhood friend?";
$label_secret_15 = "In what town or city did your mother and father meet?";
$label_secret_16 = "What was childhood telephone number that you remember most including area code?";
$label_secret_17 = "What was your favorite place to visit as a child?";
$label_secret_18 = "Where were you when you had your first kiss?";
$label_secret_19 = "In what city or town was your first job?";
$label_secret_20 = "In what city or town were you on New Year&rsquo;s 2000?";
$label_secret_21 = "What is the name of a college you applied to but did not attend?";
$label_secret_22 = "What is the first name of the boy or girl that you first kissed?";
$label_secret_23 = "What was the name of your first stuffed animal, doll, or action figure?";
$label_secret_24 = "In what city or town did you meet your spouse/significant other?";
$label_secret_25 = "What street did you live on in first grade?";
$label_secret_26 = "What is the air speed velocity of an unladen swallow?";
$label_secret_27 = "What is the name of your favorite cancelled TV show?";
$label_pro = "Professional";
$label_amateur = "Amateur";
$label_hosted = "Hosted";
$label_edition = "Edition";
$label_pro_comp_edition = "Professional Competition Edition";
$label_amateur_comp_edition = "Amateur Competition Edition";
$label_optional_info = "Optional Info";
$label_or = "Or";
$label_admin_payments = "Payments";
$label_payer = "Payer";
$label_pay_with_paypal = "Pay with PayPal";
$label_submit = "Submit";
$label_id_verification_question = "ID Verfication Question";
$label_id_verification_answer = "ID Verification Answer";
$label_server = "Server";
$label_password_reset = "Password Reset";
$label_id_verification_request = "ID Verification Request";
$label_new_password = "New Password";
$label_confirm_password = "Confirm Password";
$label_with_token = "With Token";
$label_password_strength = "Password Strength";
$label_entry_shipping = "Entry Shipping";
$label_jump_to = "Jump to...";
$label_top = "Top";
$label_bjcp_cider = "Cider Judge";

// -------------------- Headers --------------------
$header_text_112 = "You do not have sufficient access privileges to perform this action.";
$header_text_113 = "You can only edit your own account information.";
$header_text_114 = "As an admin, you can change a user's account information via Admin > Entries and Particpants > Manage Participants.";
$header_text_115 = "Results have been published.";
$header_text_116 = "If you do not receive the email within a reasonable amount of time, contact a competition official or site administrator to reset your password for you.";

// -------------------- Alerts --------------------
$alert_text_082 = "Since you signed up as a judge or steward, you are not allowed to add entries to your account. Only representatives of an organization are able to add entries to their accounts.";
$alert_text_083 = "Adding and edting of entries is not available.";
$alert_text_084 = "As an Administrator, you can add an entry to an organization's account by using the &quot;Add Entry For...&quot; dropdown menu on the Admin &gt; Entries and Participants &gt; Manage Entries page.";
$alert_text_085 = "You will not be able to print any entry's paperwork (bottle labels, etc.) until payment for it has been confirmed and it has been marked as &quot;paid&quot; below.";

// -------------------- Brew (Add Entry) --------------------
if ($section == "brew") {
	$brew_text_027 = "This Brewers Association style requires a statement from the brewer regarding the special nature of the product. See the <a href=\"https://www.brewersassociation.org/resources/brewers-association-beer-style-guidelines/\" target=\"_blank\">BA Style Guidelines</a> for specific guidance.";
	$brew_text_028 = "***NOT REQUIRED*** Add information here that is detailed in the style guidelines as a characteristic that you MAY declare.";
	$brew_text_029 = "Admin editing disabled. Your profile is considered a personal profile and not a organizational profile, and thus, not eligible to add entries. To add an entry for an organization, access the Manage Entries list and choose an organization from the &quot;Add an Entry For...&quot; dropdown.";

    // v2.1.13
    $brew_text_030 = "milk / lactose";
    $brew_text_031 = "eggs";
    $brew_text_032 = "fish";
    $brew_text_033 = "crustacean shellfish";
    $brew_text_034 = "tree nuts";
    $brew_text_035 = "peanuts";
    $brew_text_036 = "wheat";
    $brew_text_037 = "soybeans";
    $brew_text_038 = "Does this entry have possible food allergens? Common food allergens include milk (including lactose), eggs, fish, crustaceans, tree nuts, peanuts, wheat, soybeans, etc.";
    $brew_text_039 = "Please specify any and all possible allergen(s)";
}

// -------------------- Brewer (Account) --------------------
if (($section == "brewer") || ($section == "register") || ($section == "step2") || (($section == "admin") && ($go == "entrant")) || (($section == "admin") && ($go == "judge")) || (($section == "admin") && ($go == "steward"))) {
	$brewer_text_022 = "You will be able to identify a co-brewer when adding your entries.";
	$brewer_text_023 = "Select &quot;None&quot; if you are not affiliated with a club. Select &quot;Other&quot; if your club is not on the list - <strong>be sure to use the search box</strong>.";
    $brewer_text_024 = "Please provide your first name.";
    $brewer_text_025 = "Please provide your last name.";
    $brewer_text_026 = "Please provide your phone number.";
    $brewer_text_027 = "Please provide your address.";
    $brewer_text_028 = "Please provide your city.";
    $brewer_text_029 = "Please provide your state or province.";
    $brewer_text_030 = "Pleaee provide your zip or postal code.";
    $brewer_text_031 = "Please choose your country.";
    $brewer_text_032 = "Please provide your organization name.";
    $brewer_text_033 = "Please choose a security question.";
    $brewer_text_034 = "Please provide a response to your security question.";

    // v2.1.12
    $brewer_text_035 = "Have you passed the BJCP Cider Judge exam?";
}

// -------------------- Entry Info --------------------
if ($section == "entry") {
	$entry_info_text_047 = "If a style's name is hyperlinked, it has specific entry requirements. Click or tap on the name to access the Brewers Association styles as listed on their website.";
}

// -------------------- List (User Entry List) --------------------
if (($section == "list") || ($section == "account") || ($go == "entries")) {
	$brewer_entries_text_016 = "Style Entered NOT Accepted";
	$brewer_entries_text_017 = "Entries will not be displayed as received until the competition staff has marked them as such in the system. Typically, this occurs AFTER all entries have been collected from all drop-off and shipping locations and sorted.";
	$brewer_entries_text_018 = "You will not be able to print this entry's paperwork (bottle labels, etc.) until it has been marked as paid.";
	$brewer_entries_text_019 = "Printing of entry paperwork is not available at this time.";
	$brewer_entries_text_020 = "Editing of entries is not available at this time. If you wish to edit your entry, contact a competition official.";
}

if (SINGLE) $brewer_info_000 = "Hello";
else $brewer_info_000 = "Thank you for participating in the";
$brewer_info_001 = "Your account details were last updated";
$brewer_info_002 = "Take a moment to <a href=\"#entries\">review your entries</a>";
$brewer_info_003 = "pay your entry fees</a>";
$brewer_info_004 = "per entry";
$brewer_info_005 = "An American Homebrewers Association (AHA) membership is required if one of your entries is selected for a Great American Beer Festival Pro-Am.";
$brewer_info_006 = "Print shipping labels to attach to your box(es) of bottles.";
$brewer_info_007 = "Print Shipping Labels";
$brewer_info_008 = "You have already been assigned to a table as a";
$brewer_info_009 = "If you wish to change your availabilty and/or withdraw your role, contact the competition organizer or judge coordinator.";
$brewer_info_010 = "You have already been assigned as a";
$brewer_info_011 = "or";
$brewer_info_012 = "Print your judging scoresheet labels ";

// -------------------- Pay --------------------
$pay_text_030 = "By clicking the &quot;I Understand&quot; button below, you will be directed to PayPal to make your payment. Once you have <strong>completed</strong> your payment, PayPal will redirect you back to this site and will email you a receipt for the transaction. <strong>If your payment was successful, your paid status will be updated automatically. Please note that you may need wait a few minutes for the payment status to be updated.</strong> Be sure to refresh the pay page or access your entries list.";
$pay_text_031 = "About to Leave this Site";
$pay_text_032 = "No payment is necessary. Thank you!";
$pay_text_033 = "You have unpaid entries. Click or tap to pay for your entries.";

// -------------------- Register --------------------
if (($section == "register") || ($section == "brewer") || ($action == "register") || ($go == "account") || ($section == "step2")) {
	$register_text_035 = "The information you provide beyond your organization's name is strictly for record-keeping and contact purposes.";
	$register_text_036 = "A condition of entry into the competition is providing this information, including a contact person's email address and phone number. Your organization's name may be displayed should one of your entries place, but no other information will be made public.";
	$register_text_037 = "Registration Confirmation";
	$register_text_038 = "An administrator has registerd you for an account. The following is confirmation of the information input:";
	$register_text_039 = "Thank you for registering an account. The following is confirmation of the information you provided:";
	$register_text_040 = "If any of the above information is incorrect,";
	$register_text_041 = "log in to your account";
	$register_text_042 = "and make the necessary changes. Best of luck in the competition!";
	$register_text_043 = "Please do not reply to this email as it is automatically generated. The originating account is not active or monitored.";
	$register_text_044 = "Please provide an organization name.";
	$register_text_045 = "Provide a brewery name, brewpub name, etc. Be sure to check the competition information for types of beverages accepted.";
	$register_text_046 = "For U.S. organizations only.";
}

// -------------------- User Registration --------------------
$user_text_004 = "Be sure to use upper and lower case letters, numbers, and special characters for a stronger password.";
$user_text_005 = "Your current email address is";

// -------------------- Login --------------------
$login_text_017 = "Email Me My Security Question Answer";
$login_text_018 = "Your user name (email address) is required.";
$login_text_019 = "Your password is required.";
$login_text_020 = "The token provided is invalid or has already been used. Please use the forgot password function again to generate a new password reset token.";
$login_text_021 = "The token provided has expired. Please use the forgot password function again to generate a new password reset token.";
$login_text_022 = "The email you entered is not associated with the provided token. Please try again.";
$login_text_023 = "The passwords do not match. Please try again.";
$login_text_024 = "A confirmation password is required.";
$login_text_025 = "Forgot your password?";
$login_text_026 = "Enter your account email address and new password below.";
$login_text_027 = "Your password has been reset successfully. You may now log in with the new password.";

// -------------------- Winners --------------------
$winners_text_005 = "Best of Show winner(s) have not been posted yet. Please check back later.";

// -------------------- Output - PayPal Response --------------------
$paypal_response_text_000 = "Your payment has been completed. The transaction details are provided here for your convience.";
$paypal_response_text_001 = "Please note that you will receive an official commuication from PayPal at the email address listed below.";
$paypal_response_text_002 = "Best of luck in the competition!";
$paypal_response_text_003 = "Please do not reply to this email as it is automatically generated. The originating account is not active or monitored.";
$paypal_response_text_004 = "PayPal has processed your transaction.";
$paypal_response_text_005 = "The status of your PayPal payment is:";
$paypal_response_text_006 = "Paypal response was &quot;invalid.&quot;. Please try to make your payment again.";
$paypal_response_text_007 = "Please contact the competition organizer if you have any questions.";
$paypal_response_text_008 = "Invalid PayPal Payment";
$paypal_response_text_009 = "PayPal Payment Details";

// -------------------- Output - Password reset email text --------------------
$pwd_email_reset_text_000 = "A request was made to verify the account at the";
$pwd_email_reset_text_001 = "website using the ID Verfication email function. If you did not initiate this, please contact the competition's organizer.";
$pwd_email_reset_text_002 = "The ID verification answer is case sensitive";
$pwd_email_reset_text_003 = "A request was made to change your password at the";
$pwd_email_reset_text_004 = "website. If you did not initiate this, don't worry. Your password cannot be reset without the link below.";
$pwd_email_reset_text_005 = "To reset your password, click the link below or copy/paste it into your browser.";

// -------------------- Best Brewer --------------------
$best_brewer_text_000 = "participating brewers";
$best_brewer_text_001 = "HM";
$best_brewer_text_002 = "Scores and tie-breakers have been applied in accordance with the <a href=\"#\" data-toggle=\"modal\" data-target=\"#scoreMethod\">scoring methodology</a>. Numbers reflected are rounded to the hundredth place. Hover over or tap the question mark icon (<span class=\"fa fa-question-circle\"></span>) for the actual calculated value.";
$best_brewer_text_003 = "Scoring Methodology";
$best_brewer_text_004 = "Each placing entry is given the following points:";
$best_brewer_text_005 = "The following tie-breakers have been applied, in order of priority:";
$best_brewer_text_006 = "The highest total number of first, second and third places.";
$best_brewer_text_007 = "The highest total number of first, second, third, fourth, and honorable mention places.";
$best_brewer_text_008 = "The highest number of first places.";
$best_brewer_text_009 = "The lowest number of entries.";
$best_brewer_text_010 = "The highest minimum score.";
$best_brewer_text_011 = "The highest maximum score.";
$best_brewer_text_012 = "The highest average score.";
$best_brewer_text_013 = "Unused.";
$best_brewer_text_014 = "participating clubs";

// Version 2.1.12
$dropoff_qualifier_text_001 = "Please pay attention to the notes provided for each drop-off location. There could be earlier deadlines for some drop-off locations listed, particular hours when entries are accepted, certain individuals to leave your entries with, etc. <strong class=\"text-danger\">All entrants are responsible for reading the information provided by the organizers for each drop-off location.</strong>";

$brewer_text_036 = "Since you have chosen \"<em>Other</em>,\" please make sure the club you have entered is not on our list in some similar form.";
$brewer_text_037 = "For example, you may have entered the acronnym of your club instead of the full name.";
$brewer_text_038 = "Consistent club names across users is essential if for \"Best Club\" calculations if implemented for this competition.";
$brewer_text_039 = "The club you entered previously does not match one on our list.";
$brewer_text_040 = "Please choose from the list or choose <em>Other</em> and enter your club name.";

// ----------------------------------------------------------------------------------
// Version 2.1.13 Additions
// ----------------------------------------------------------------------------------

$entry_info_text_048 = "To ensure proper judging, the entrant must provide additional information about the beverage.";
$entry_info_text_049 = "To ensure proper judging, the entrant must provide the strength level of the beverage.";
$entry_info_text_050 = "To ensure proper judging, the entrant must provide the carbonation level of the beverage.";
$entry_info_text_051 = "To ensure proper judging, the entrant must provide the sweetness level of the beverage.";
$entry_info_text_052 = "If entering this category, the entrant must provide further information in order for the entry to be judged accurately. The more information, the better.";

$output_text_028 = "The following entries have possible allergens - as input by particpants.";
$output_text_029 = "No participants provided allergen information for their entries.";

$label_this_style = "This Style";
$label_notes = "Notes";
$label_possible_allergens = "Possible Allergens";
$label_please_choose = "Please Choose";
$label_mead_cider_info = "Mead/Cider Info";

// ----------------------------------------------------------------------------------
// Version 2.1.14 Additions
// ----------------------------------------------------------------------------------

// Labels
$label_winners = "Winners";
$label_unconfirmed_entries = "Unconfirmed Entries";
$label_recipe = "Recipe";
$label_view = "View";
$label_number_bottles = "Number of Bottles Required Per Entry";
$label_pro_am = "Pro-Am";

// Pay screen
$pay_text_034 = "The limit of paid entires has been reached - further entry payments are not being accepted.";

// Bottle Labels
$bottle_labels_000 = "Labels cannot be generated at this time.";
$bottle_labels_001 = "Attach label ONLY with a rubber band.";
$bottle_labels_002 = "Use clear packing tape to attach to the barrel of each bottle.";
$bottle_labels_003 = "Cover the label completely!";
if (isset($_SESSION['jPrefsBottleNum'])) $bottle_labels_004 = "Please note: 4 labels are provided as a courtesy. This competition requires ".$_SESSION['jPrefsBottleNum']." bottles per entry. Discard any extra labels.";
else $bottle_labels_004 = "Please note: 4 labels are provided as a courtesy. Discard any extra labels.";
$bottle_labels_005 = "If any items are missing, close this window and edit the entry.";
$bottle_labels_006 = "Space reserved for competition staff use.";
$bottle_labels_007 = "THIS RECIPE FORM IS FOR YOUR RECORDS ONLY - please DO NOT include a copy of it with your entry shipment.";

// Add/Edit Entry
$brew_text_040 = "There is no need to specify gluten as an allergen for any beer style; it is assumed that it will be present. Gluten-free beers should be entered into the Gluten-Free Beer category (BA) or the Alternative Grain Beer category (BJCP). Only specify gluten as an allergen in mead or cider styles if a source fermentable contains gluten (e.g., barley, wheat, or rye malt) or if a brewer's yeast was used.";

// Pro-Am
$brewer_text_041 = "Have you already been awarded a Pro-Am opportunity to compete in the upcoming Great American Beer Festival Pro-Am Competition?";
$brewer_text_042 = "If you have already been awarded a Pro-Am, please indicate so here. This will help competition staff and Pro-Am brewery representatives (if applicable for this competition) to choose Pro-Am entries from brewers that have not secured one.";

// ----------------------------------------------------------------------------------
// Version 2.1.15 Additions
// ----------------------------------------------------------------------------------

$label_submitting = "Submitting";
$label_additional_info = "Entries with Additional Info";
$label_working = "Working";

$output_text_030 = "Please stand by";

$brewer_entries_text_021 = "Check the entries to print their bottle labels. Select the top checkbox to check or uncheck all the boxes in the column.";
$brewer_entries_text_022 = "Print All Bottle Labels for Checked Entries";
$brewer_entries_text_023 = "The bottle labels will open in a new tab or window.";
$brewer_entries_text_024 = "Print Bottle Labels";


/**
 * ----------------------------------------------------------------------------------
 * END TRANSLATIONS
 * ----------------------------------------------------------------------------------
 */

/**
 * ----------------------------------------------------------------------------------
 * Various conditionals
 * No translations below this line
 * ----------------------------------------------------------------------------------
 */

if (strpos($section, "step") === FALSE) $alert_text_032 = $alert_text_032; else $alert_text_032 = "";
if (strpos($section, "step") === FALSE) $alert_text_033 = $alert_text_033; else $alert_text_033 = "";
if (strpos($section, "step") === FALSE) $alert_text_036 = $alert_text_036; else $alert_text_036 = "";
if (strpos($section, "step") === FALSE) $alert_text_039 = $alert_text_039; else $alert_text_039 = "";
if ((strpos($section, "step") === FALSE) && ((isset($_SESSION['prefsProEdition'])) && ($_SESSION['prefsProEdition'] == 0))) $alert_text_043 = $alert_text_043; else $alert_text_043 = "";
if ((strpos($section, "step") === FALSE) && ((isset($_SESSION['prefsProEdition'])) && ($_SESSION['prefsProEdition'] == 0))) $alert_text_047 = $alert_text_047; else $alert_text_047 = "";
if (strpos($section, "step") === FALSE) $alert_text_050 = $alert_text_050; else $alert_text_050 = "";
if (strpos($section, "step") === FALSE) $alert_text_053 = $alert_text_053; else $alert_text_053 = "";
if ((strpos($section, "step") === FALSE) && ((isset($_SESSION['prefsProEdition'])) && ($_SESSION['prefsProEdition'] == 0))) $alert_text_060 = $alert_text_060; else $alert_text_060 = "";
if (strpos($section, "step") === FALSE) $alert_text_068 = $alert_text_068; else $alert_text_068 = "";
if (strpos($section, "step") === FALSE) $alert_text_070 = $alert_text_070; else $alert_text_070 = "";
if (strpos($section, "step") === FALSE) $label_character_limit = $label_character_limit; else $label_character_limit = "";
if (strpos($section, "step") === FALSE) $header_text_031 = $header_text_031; else $header_text_031 = "";
if (strpos($section, "step") === FALSE) $beerxml_text_007 = $beerxml_text_007; else $beerxml_text_007 = "";


// ----------------------------------------------------------------------------------
// Admin Pages - Admin pages will be included in a future release
// ----------------------------------------------------------------------------------
// if ($section == "admin") include (LANG.'en_admin.lang.php');

?>