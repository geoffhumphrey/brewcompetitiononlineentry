<?php
/**
 * Module:      english.lang.php 
 * Description: This module houses all display text in the English language.
 * 
 */

/* 

--------------------------------------------------------------------------------------------------
To translate this file, first make a copy of it and rename it with the language name in the title.

Spanish:    es.lang.php
Portuguese: por.lang.php
French:     fr.lang.php

Etc...

Items that need translation into other languages are housed here in PHP variables - each start with
a dollar sign ($). The words, phrases, etc. (called strings) that need to be translated are housed
between double-quotes ("). Please, ONLY alter the text between the double quotes!

For example, a translated PHP variable would look like this:

English (before translation):
$label_volunteer_info = "Volunteer Info";

Spanish (translated):
$label_volunteer_info = "Información de Voluntarios";

Portuguese (translated):
$label_volunteer_info = "Informações Voluntário";

-----------

Please note: the strings that need to be translated MAY contain HTML code. Please leave this code
intact! For example:

English:
$beerxml_text_008 = "Browse for your BeerXML compliant file on your hard drive and click <em>Upload</em>.";

Spanish:
$beerxml_text_008 = "Buscar su archivo compatible BeerXML en su disco duro y haga clic en <em>Cargar</em>.";

Note that the <em>...</em> tags were not altered. Just the word "Upload" to "Cargar" betewen those tags.

-----------

*/
 
// -------------------- Global Labels - mostly used for titles and navigation --------------------
// All labels are capitalized and without punctuation

$label_home = "Home";
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
$label_info = "Info";
$label_sponsors = "Sponsors";
$label_rules = "Rules";
$label_volunteer_info = "Informações Voluntário";
$label_reg = $label_register;
$label_judge_steward_reg = "Judge/Steward Registration";
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
$label_character_limit = $_SESSION['prefsSpecialCharLimit']." character limit - use keywords and abbreviations if space is limited. Characters remaining: "; 
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
$label_petillent = "Petillent";
$label_sparkling = "Sparkling";
$label_dry = "Dry";
$label_med_dry = "Medium Dry";
$label_med_sweet = "Medium Sweet";
$label_sweet = "Sweet";
$label_brewer_specfics = "Brewer&rsquo;s Specifics";
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
$label_city = "City";
$label_state_province = "State/Province";
$label_zip = "Zip/Postal Code";
$label_country = "Country";
$label_phone = "Phone";
$label_phone_primary = "Primary Phone #";
$label_phone_secondary = "Secondary Phone #";
$label_drop_off = "Drop-Off Location";
$label_drop_offs = "Drop-Off Locations";
$label_club = "Club";
$label_aha_number = "AHA Member Number";
$label_org_notes = "Notes to Organizer"; 
$label_avail = "Availability";
$label_location = "Location";
$label_judging_avail = "Judging Location Availability";
$label_stewarding = "Stewarding";
$label_stewarding_avail = "Stewarding Location Availability";
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
$label_discount = "Discount";
$label_subject = "Subject";
$label_message = "Message";
$label_send_message = "Send Message";
$label_email = "Email Address";
$label_account_registration = "Account Registration";
$label_entry_registration = "Entry Registration";
$label_entry_fees = "Entry Fees";
$label_entry_limit = "Entry Limit";
$label_entry_per_entrant = "Per Entrant Limits";
$label_categories_accepted = "Categories Accepted";
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
$label_register_judge = "Are You Registering as a Judge or Steward?";
$label_register_judge_standard = "Register a Judge or Steward (Standard)";
$label_register_judge_quick = "Register a Judge or Steward (Quick)";
$label_all_participants = "All Participants";
$label_open = "Open";
$label_closed = "Closed";
$label_judging_loc = "Judging Locations and Dates";
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
$label_assigned_to_table = "Assigned to Table";
$label_organizer = "Organizer";
$label_by_table = "By Table";
$label_by_category = "By Category";
$label_by_subcategory = "By Sub-Category";
$label_shipping_entries = "Shipping Entries";
$label_no_availability = "No Availability Defined";

// Admin

$label_admin = "Administration";
$label_admin_short = "Admin";
$label_admin_dashboard = "Dashboard";
$label_admin_judging = $label_judging;
$label_admin_stewarding = "Stewarding";
$label_admin_participants = "Participants";
$label_admin_entries = $label_entries;
$label_admin_comp_info = "Competition Info";
$label_admin_web_prefs = "Website Preferences";
$label_admin_judge_prefs = "Competition Organization Preferences";
$label_admin_archive = "Archives";
$label_admin_style = $label_style;
$label_admin_styles = "Styles";
$label_admin_dropoff = $label_drop_offs;
$label_admin_judging_loc = $label_judging_loc;
$label_admin_contacts = "Contacts";
$label_admin_tables = "Tables";
$label_admin_scores = "Scores";
$label_admin_bos = "Best of Show";
$label_admin_style_types = "Style Types";
$label_admin_custom_cat = "Custom Categories";
$label_admin_custom_cat_data = "Custom Category Entries";
$label_admin_sponsors = $label_sponsors;
$label_admin_entry_count = "Entry Count By Style";
$label_admin_entry_count_sub = "Entry Count By Bub-Style";
$label_admin_custom_mods = "Custom Modules";
$label_admin_check_in = $label_entry_check_in;
$label_admin_make_admin = "Change User Level";
$label_admin_register = "Register a Participant";
$label_admin_upload_img = "Upload Images";
$label_admin_upload_doc = "Upload Scoresheets and Other Documents";
$label_admin_password = "Change User Password";
$label_admin_edit_account = "Edit User Account";
$label_assignment = "Assignment";
$label_letter = "Letter";
$label_re_enter = "Re-Enter";
$label_website = "Website";
$label_place = "Place";

// Sidebar Labels
$label_account_summary = "My Account Summary";
$label_confirmed_entries = "Confirmed Entries";
$label_unpaid_confirmed_entries = "Unpaid Confirmed Entries";
$label_total_entry_fees = "Total Entry Fees";
$label_entry_fees_to_pay = "Entry Fees to Pay";
$label_entry_drop_off = "Entry Drop-Off";

// -------------------- Headers --------------------
// Missing punctuation intentional for all
if (strpos($section, 'step') === FALSE) {
	if ($_SESSION['jPrefsQueued'] == "N") $assign_to = "Flights"; else $assign_to = "Tables";
}
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
$header_text_023 = "The characters you entered in the CAPTCHA section below were not correct.";
$header_text_024 = "The email addresses you entered do not match.";
$header_text_025 = "The AHA number you entered is already in the system.";
$header_text_026 = "Your online payment has been received.";
$header_text_027 = "Please make sure to print the receipt and attach it to one of your entries as proof of payment.";
$header_text_028 = "Your online payment has been cancelled.";
$header_text_029 = "The code has been verified.";
$header_text_030 = "Sorry, the code you entered was incorrect.";
$header_text_031 = "You must log in and have admin privileges to access the ".$_SESSION['contestName']." administration functions.";
$header_text_032 = "Sorry, there was a problem with your last login attempt.";
$header_text_033 = "Please make sure your email address and password are correct.";
$header_text_034 = "Your password has been randomly generated and reset to ".$go;
$header_text_035 = "- you can now log in using your current username and the new password.";
$header_text_036 = "You have been logged out.";
$header_text_037 = "Log in again?";
$header_text_038 = "Your verification question does not match what is in the database.";
$header_text_039 = "Your ID verification information has been sent to the email address associated with your account.";
$header_text_040 = "Your message has been sent to";
$header_text_041 = "The characters you entered in the CAPTCHA section below were not correct.";
$header_text_042 = "Your email address has been updated.";
$header_text_043 = "Your password has been updated.";
$header_text_044 = "Info deleted successfully.";
$header_text_045 = "You should verify all your entries imported using BeerXML.";
$header_text_046 = "You have registered as a judge or steward.";
$header_text_047 = "You have reached the entry limit.";
$header_text_048 = "Your entry was not added.";
$header_text_049 = "You have reached the entry limit for the sub-category.";
$header_text_050 = "Set Up: Install DB Tables and Data";
$header_text_051 = "Set Up: Create Admin User";
$header_text_052 = "Set Up: Add Admin User Info";
$header_text_053 = "Set Up: Set Website Preferences";
$header_text_054 = "Set Up: Add Competition Info";
$header_text_055 = "Set Up: Add Judging Locations";
$header_text_056 = "Set Up: Add Drop-Off Locations";
$header_text_057 = "Set Up: Designate Accepted Styles";
$header_text_058 = "Set Up: Set Judging Preferences";
$header_text_059 = "Import an Entry Using BeerXML";
$header_text_060 = "Your entry has been recorded.";
$header_text_061 = "Your entry has been confirmed.";
$header_text_065 = "All received entries have been checked and those not assigned to tables have been assigned.";
$header_text_066 = "Info deleted successfully.";
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
$header_text_087 = "Data has been deleted from the database.";
$header_text_088 = "The judge/steward has been added successfully. Remember to assign the user as a judge or steward before assigning to tables.";
$header_text_089 = "The file has been uploaded successfully. Check the list to verify.";
$header_text_090 = "The file that was attempted to be uploaded is not an accepted file type.";
$header_text_091 = "The file has been deleted.";
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

// -------------------- Navigation --------------------


// -------------------- Alerts --------------------

if (($judge_limit) && (!$steward_limit)) $j_s_text = "Steward"; // missing punctuation intentional
elseif ((!$judge_limit) && ($steward_limit)) $j_s_text = "Judge"; // missing punctuation intentional
else $j_s_text = "Judge or steward"; // missing punctuation intentional
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
$alert_text_023 = "Add a Drop Off Location?";
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
$alert_text_044 = "Registration will close ".$entry_closed.".";
$alert_text_046 = "The entry limit nearly reached!";
$alert_text_047 = $total_entries." of ".$row_limits['prefsEntryLimit']." maximum entries have been added into the system as of ".$current_time.".";
$alert_text_049 = "The entry limit has been reached.";
$alert_text_050 = "The limit of ".$row_limits['prefsEntryLimit']." entries has been reached. No further entries will be accepted.";
$alert_text_052 = "The paid entry limit has been reached.";
$alert_text_053 = "The limit of ".$row_limits['prefsEntryLimitPaid']." <em>paid</em> entries has been reached. No further entries will be accepted.";
$alert_text_055 = "Account registration is closed.";
$alert_text_056 = "If you already registered an account,";
$alert_text_057 = "log in here"; // lower-case and missing punctuation intentional
$alert_text_059 = "Entry registration is closed.";
$alert_text_060 = "A total of ".$total_entries." entries were added into the system.";
$alert_text_062 = "Entry drop-off is closed.";
$alert_text_063 = "Entry bottles are no longer accepted at drop-off locations.";
$alert_text_065 = "Entry shipping is closed.";
$alert_text_066 = "Entry bottles are no longer accepted at the shipping location.";
$alert_text_068 = $j_s_text." account registration open.";
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
	$brew_text_005 = "Strength required"; // missing punctuation intentional
	$brew_text_006 = "Carbonation level required"; // missing punctuation intentional
	$brew_text_007 = "Sweetness level required"; // missing punctuation intentional
	$brew_text_008 = "This style requires that you provide specific information for entry. Instructions are below.";
	$brew_text_009 = "Requirements for"; // missing punctuation intentional
	$brew_text_010 = "This style requires more information. Please provide above.";
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

if (($section == "brewer") || ($section == "register") || ($section == "step2") || (($section == "admin") && ($go == "entrant")) || (($section == "admin") && ($go == "judge"))) {
	$brewer_text_000 = "Please enter only <em>one</em> person's name. You will be able to identify a co-brewer when adding your entries.";
	$brewer_text_001 = "Choose one. This question will be used to verify your identity should you forget your password.";
	$brewer_text_002 = "You can also <a href=\"".$edit_user_password_link."\">change your password now</a> if you wish.";
	$brewer_text_003 = "To be considered for a GABF Pro-Am brewing opportunity you must be an AHA member.";
	$brewer_text_004 = "Provide any information that you believe the competition organizer should know (e.g., allergies, special dietary restrictions, shirt size, etc.).";
	$brewer_text_005 = "I'm Shipping My Entries";
	$brewer_text_006 = "Are you willing and qualified to serve as a judge in this competition?";
	$brewer_text_007 = "Have you passed the BJCP Mead Judge exam?";
	$brewer_text_008 = "* The <em>Novice</em> rank is for those who haven't taken the BJCP Beer Judge Entrance Exam, and are <em>not</em> a professional brewer.";
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
	
	
	
}

// -------------------- Contact --------------------

if ($section == "contact") {
	
	$contact_text_000 = "Use the links below to contact individuals involved with coordinating this competition:";
	$contact_text_001 = "Use the form below to contact a competition official. All fields with a star are required.";
	$contact_text_002 = "Additionally, a copy has been sent to the email address you provided.";
	$contact_text_003 = "Would you like to send another message?";

}

// -------------------- Default (Home) -------------------

if ($section == "default") {
		
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
	
	$reg_open_text_000 = "Judge and Steward Registration is";
	$reg_open_text_001 = "Open";
	$reg_open_text_002 = "If you <em>have not</em> registered and are willing to be a judge or steward,";
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
	$entry_info_text_006 = "Account registrations for";
	$entry_info_text_007 = "judges and stewards only";
	$entry_info_text_008 = "accepted through";
	$entry_info_text_009 = "Account registration is <strong class=\"text-danger\">closed</strong>.";
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
	$entry_info_text_022 = "entry for this competition";
	$entry_info_text_023 = "entries for this competition";
	$entry_info_text_024 = "entry per sub-category";
	$entry_info_text_025 = "entries per sub-category";
	$entry_info_text_026 = "exceptions are detailed below";
	$entry_info_text_027 = "sub-category";
	$entry_info_text_028 = "sub-categories";
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
	
}

// -------------------- Footer --------------------

// -------------------- Judge Info --------------------

// -------------------- List (User Entry List) --------------------

if ($section == "list") {
	
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
	
	
	$brewer_info_000 = "Thank you for entering the";
	$brewer_info_001 = "Your account details were last updated";
	$brewer_info_002 = "Take a moment to <a href=\"#entries\">review your entries</a>";
	$brewer_info_003 = "or <a href=\"".build_public_url("pay","default","default","default",$sef,$base_url)."\">pay your entry fees</a>";
	$brewer_info_004 = "per entry";
	$brewer_info_005 = "An American Homebrewers Association (AHA) membership is required if one of your entries is selected for a Great American Beer Festival Pro-Am.";
	$brewer_info_006 = "Print shipping labels to attach to your box(es) of bottles.";
	$brewer_info_007 = "Print Shipping Labels";
	$brewer_info_008 = "You have already been assigned to a table as a";
	$brewer_info_009 = "If you wish to change your availabilty and/or withdraw your role, contact the competition organizer or judge coordinator.";
	$brewer_info_010 = "You have already been assigned as a";
	
}

// -------------------- Login --------------------

// -------------------- Past Winners --------------------
if ($seciton == "past_winners") {
	$past_winners_text_000 = "View past winners:";
}

// -------------------- Pay for Entries --------------------

if ($section == "pay") {
	
	$pay_text_000 = "the payment window has passed.";
	$pay_text_001 = "Contact a competition official if you have any questions.";
	$pay_text_002 = "the following are your options for paying your entry fees.";
	$pay_text_003 = "Fees are";
	$pay_text_004 = "per entry";
	$pay_text_005 = "per entry after the";
	$pay_text_006 = "for unlimited entries";
	$pay_text_007 = "Your fees have been discounted to";
	$pay_text_008 = "Your total entry fees are";
	$pay_text_009 = "You need to pay";
	$pay_text_010 = "Your fees have been paid. Thank you!";
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
	$pay_text_024 = "Your fees have been paid. Thank you!";
	$pay_text_025 = "You have not logged any entries yet.";
	$pay_text_026 = "You cannot pay for your entries because one or more of your entries is unconfirmed.";
	$pay_text_027 = "Click <em>My Account</em> above to review your unconfirmed entries.";
	$pay_text_028 = "You have unconfirmed entries that are <em>not</em> reflected in your fee totals below.";
	$pay_text_029 = "Please go to your entry list to confirm all your entry data. Unconfirmed entries may be deleted from the system without warning.";
	
}

// -------------------- QR --------------------

if ($section == "qr") {
	
	$qr_text_017 = sprintf("%04d",$view[0]);
	$qr_text_018 = $view[1];
	$qr_text_019 = sprintf("%06d",$view[1]);
	$qr_text_020 = sprintf("%04d",$view[0]);
	
	$qr_text_000 = $alert_text_080;
	$qr_text_001 = $alert_text_081;
	$qr_text_002 = sprintf("Entry number %s is checked in with <span class=\"text-danger\">%s</span> as its judging number.",$qr_text_017,$qr_text_018);
	$qr_text_003 = "If this judging number is <em>not</em> correct, <strong>re-scan the code and re-enter the correct judging number.";
	$qr_text_004 = sprintf("Entry number %s is checked in.",$qr_text_018);
	$qr_text_005 = sprintf("Entry number %s was not found in the database. Set the bottle(s) aside and alert the competition organizer.",$qr_text_018);
	$qr_text_006 = sprintf("The judging number you entered - %s - is already assigned to entry number %s.",$qr_text_019,$qr_text_020);
	$qr_text_007 = "QR Code Entry Check-In";
	$qr_text_008 = "To check in entries via QR code, please provide the correct password. You will only need to provide the password once per session - be sure to keep the QR Code scanning app open.";
	$qr_text_009 = "Assign a judging number and/or box number to entry";
	$qr_text_010 = "ONLY inupt a judging number if your competition is using judging number labels at sorting.";
	$qr_text_011 = "Six numbers with leading zeros - e.g., 000021.";
	$qr_text_012 = "Be sure to double-check your input and affix the appropriate judging number labels to each bottle and bottle label (if applicable).";
	$qr_text_013 = "Judging number must be six digits.";
	$qr_text_014 = "Waiting for scanned QR code input.";
	$qr_text_015 = "Launch or go back to your mobile device's scanning app to scan a QR code.";
	$qr_text_016 = "Need a QR Code scanning app? Search <a href=\"https://play.google.com/store/search?q=qr%20code%20scanner&c=apps&hl=en\" target=\"_blank\">Google Play</a> (Android) or <a href=\"https://itunes.apple.com/store/\" target=\"_blank\">iTunes</a> (iOS).";
	$qr_text_017 = "A QR Code scanning app is required to utilize this feature.";
	$qr_text_018 = "Launch the app on your mobile device, scan a QR Code located on a bottle label, enter the required password, and check in the entry.";
	
}


// -------------------- Registration Open --------------------

// -------------------- Registration Closed --------------------

// -------------------- Register --------------------

if (($section == "register") || ($action == "register")) {
	
	$register_text_000 = "Is the volunteer ";
	$register_text_001 = "Are you ";
	$register_text_002 = "Account registration has closed.";
	$register_text_003 = "Thank you for your interest.";
	$register_text_004 = "The information you provide beyond your first name, last name, and club is strictly for record-keeping and contact purposes.";
	$register_text_005 = "A condition of entry into the competition is providing this information. Your name and club may be displayed should one of your entries place, but no other information will be made public.";
	$register_text_006 = "Reminder: You are only allowed to enter one region and once you have registered at a location, you will NOT be able to change it.";
	$register_text_007 = "Quick";
	$register_text_008 = "Register";
	$register_text_009 = "a Judge/Steward";
	$register_text_010 = "a Participant";
	$register_text_011 = "To register for the competition, create your online account by filling out the form below.";
	$register_text_012 = "Quickly add a participant to the competition&rsquo;s judge/steward pool. A dummy address and phone number will be used and a default password of <em>bcoem</em> will be given to each participant added via this screen.";
	$register_text_013 = "Entry into this competition is conducted completely online.";
	$register_text_014 = "To add your entries and/or indicate that you are willing to judge or steward, you will need to create an account on our system.";
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
	$register_text_027 = "";
	$register_text_028 = "Please provide a street address.";
	$register_text_029 = "Please provide a city.";
	$register_text_030 = "Please provide a state or province.";
	$register_text_031 = "Please provide a zip or postal code.";
	$register_text_032 = "Please provide a primary telephone number.";
	$register_text_033 = "Only American Homebrewers Association members are eligible for a Great American Beer Festival Pro-Am opportunity.";
	$register_text_034 = "To register, you must check the box, indicating that you agree to the waiver statement.";
	
}

// -------------------- Sidebar --------------------

$sidebar_text_000 = "Account registrations for judges or stewards";
$sidebar_text_001 = "Account registrations for stewards";
$sidebar_text_002 = "Account registrations for judges";
$sidebar_text_003 = "Account registrations are no longer accepted. The limit of judges and stewards has been reached.";
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

// -------------------- Sponsors --------------------
// NONE

// -------------------- User (Edit Email) --------------------

$user_text_000 = "A new email address is required and must be in valid form.";
$user_text_001 = "Enter the old password.";
$user_text_002 = "Enter the new password.";
$user_text_003 = "Please check this box if you wish to proceed with changing your email address.";
$user_text_004 = "";

// -------------------- Volunteers --------------------

if ($section == "volunteers") {
	
	$volunteers_text_000 = "If you have registered,";
	$volunteers_text_001 = "and then choose <em>Edit Account</em> from the My Account menu indicated by the";
	$volunteers_text_002 = "icon on the top menu";
	$volunteers_text_003 = "and";
	$volunteers_text_004 = "If you have not registered and are willing to be a judge or steward, please";
	$volunteers_text_005 = "Since you have already registered,";
	$volunteers_text_006 = "access your account";
	$volunteers_text_007 = "to see if you have volunteered to be a judge or steward";
	$volunteers_text_008 = "If you are willing to judge or steward, please return to register on or after";
	$volunteers_text_009 = "If you would like to volunteer to be a competition staff member,";
	$volunteers_text_010 = "contact the appropriate competition official";
	
}

// -------------------- Winners --------------------

$winners_text_000 = "No winners have been entered for this table. Please check back later.";
$winners_text_001 = "Winning entries have not been posted yet. Please check back later.";
$winners_text_002 = "Your chosen award structure is to award places by table. Select the award places for the table as a whole below.";
$winners_text_003 = "Your chosen award structure is to award places by category. Select the award places for each overall category below (there may be more than one at this table).";
$winners_text_004 = "Your chosen award structure is to award places by sub-category. Select the award places for each sub-category below (there may be more than one at this table).";


// ----------------------------------------------------------------------------------
// Admin Pages - Admin pages will be included in a future release
// ----------------------------------------------------------------------------------
// include(LANG.'english_admin.lang.php');

?>