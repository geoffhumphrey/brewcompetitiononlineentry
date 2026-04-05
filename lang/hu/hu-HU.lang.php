<?php
/**
 * Module:      hu-HU.lang.php
 * Description: This module houses all display text in the Hungarian language.
 * Updated:     April 5, 2026
 *
 * To translate this file, first make a copy of it and rename it with the
 * language name in the title.
 *
 * ==============================
 *
 * Use ISO 169-2 Standards for and WWW3C Language Tag Standards for naming
 * of language files. Use the ALPHA-2 letter code whenever possible.
 *
 * ISO 169-2:
 * @see https://www.loc.gov/standards/iso639-2/php/code_list.php
 *
 * WWW3 Language Tags:
 * @see https://www.w3.org/International/articles/language-tags/
 *
 * WWW3 Choosing a Language Tag:
 * @see https://www.w3.org/International/questions/qa-choosing-language-tags
 *
 * To determine a subtag, go to the IANA Language Subtag Registry:
 * @see http://www.iana.org/assignments/language-subtag-registry
 *
 * According to the WWW3:
 *
 * "Always bear in mind that the golden rule is to keep your language tag
 * as short as possible. Only add further subtags to your language tag if
 * they are needed to distinguish the language from something else in the
 * context where your content is used..."
 *
 * "Unless you specifically need to highlight that you are talking about
 * Italian as spoken in Italy you should use it 'for Italian, and not
 * it-IT. The same goes for any other possible combination."
 *
 * "You should only use a region subtag if it contributes information
 * needed in a particular context to distinguish this language tag from
 * another one; otherwise leave it out."
 *
 * ================ FORMAT =================
 *
 * Always indicate the primary language subtag first, then a dash (-)
 * and then the region subtag. The region subtag is in all capital letters
 * or a three digit number.
 *
 * Examples:
 * en-US
 * English spoken in the United States
 * en is the PRIMARY language subtag
 * US is the REGION subtag (note the capitalization)
 *
 * es-ES
 * Spanish spoken in Spain
 *
 * es-419
 * Spanish spoken in Latin America
 *
 * ========================================
 *
 * Items that need translation into other languages are housed here in
 * PHP variables - each start with a dollar sign ($). The words, phrases,
 * etc. (called strings) that need to be translated are housed between
 * double-quotes ("). Please, ONLY alter the text between the double quotes!
 *
 * For example, a translated PHP variable would look like this (encoding is utf8mb4; therefore,
 * accented and other special characters are acceptable):
 *
 * English (US) before translation:
 * $label_volunteer_info = "Volunteer Info";
 *
 * Hungarian translated:
 * $label_volunteer_info = "Önkéntes információ";
 *
 * ========================================
 *
 * Please note: the strings that need to be translated MAY contain HTML
 * code. Please leave this code intact! For example:
 *
 * English (US):
 * $beerxml_text_008 = "Browse for your BeerXML compliant file on your hard drive and select <em>Upload</em>.";
 *
 * Hungarian:
 * $beerxml_text_008 = "Keresse meg a BeerXML kompatibilis fájlt a merevlemezén, és válassza a <em>Feltöltés</em> lehetőséget.";
 *
 * Note that the <em>...</em> tags were not altered. Just the word "Upload"
 * to "Feltöltés" between those tags.
 *
 * ==============================
 *
 */

/**
 * ------------------------------------------
 * Set up PHP variables.
 * No translations in this section.
 * ------------------------------------------
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

/**
 * ------------------------------------------
 * BEGIN TRANSLATIONS BELOW!
 * ------------------------------------------
 */

$j_s_text = "";
if (strpos($section, "step") === FALSE) {
	if ((isset($judge_limit)) && (isset($steward_limit))) {
		if (($judge_limit) && (!$steward_limit)) $j_s_text = "Felszolgáló"; // missing punctuation intentional
		elseif ((!$judge_limit) && ($steward_limit)) $j_s_text = "Bíráló"; // missing punctuation intentional
		else $j_s_text = "Bíráló vagy felszolgáló"; // missing punctuation intentional
	}
}

/**
 * ------------------------------------------
 * Global Labels
 * Mostly used for titles and navigation.
 * All labels are capitalized and without
 * punctuation.
 * ------------------------------------------
 */
$label_home = "Kezdőlap";
$label_welcome = "Üdvözöljük";
$label_comps = "Versenykatalógus";
$label_info = "Információ";
$label_volunteers = "Önkéntesek";
$label_register = "Regisztráció";
$label_help = "Súgó";
$label_print = "Nyomtatás";
$label_my_account = "Fiókom";
$label_yes = "Igen";
$label_no = "Nem";
$label_low_none = "Alacsony/Nincs";
$label_low = "Alacsony";
$label_med = "Közepes";
$label_high = "Magas";
$label_pay = "Nevezési díj fizetése";
$label_reset_password = "Jelszó visszaállítása";
$label_log_in = "Bejelentkezés";
$label_logged_in = "Bejelentkezve";
$label_log_out = "Kijelentkezés";
$label_logged_out = "Kijelentkezve";
$label_sponsors = "Szponzorok";
$label_rules = "Szabályok";
$label_volunteer_info = "Önkéntes információ";
$label_reg = $label_register;
$label_judge_reg = "Bírálói regisztráció";
$label_steward_reg = "Felszolgálói regisztráció";
$label_past_winners = "Korábbi győztesek";
$label_contact = "Kapcsolat";
$label_style = "Stílus";
$label_entry = "Nevezés";
$label_add_entry = "Nevezés hozzáadása";
$label_edit_entry = "Nevezés szerkesztése";
$label_upload = "Feltöltés";
$label_bos = "Best of Show";
$label_brewer = "Sörfőző";
$label_cobrewer = "Társfőző";
$label_entry_name = "Nevezés neve";
$label_required_info = "Kötelező információ";
$label_character_limit = " karakter limit - használjon kulcsszavakat és rövidítéseket, ha a hely korlátozott.<br>Felhasznált karakterek: ";
$label_carbonation = "Szénsavtartalom";
$label_sweetness = "Édesség";
$label_strength = "Erősség";
$label_color = 	"Szín";
$label_table = "Asztal";
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
$label_brewer_specifics = "Sörfőző részletei";
$label_general = "Általános";
$label_amount_brewed = "Főzött mennyiség";
$label_specific_gravity = "Fajsúly";
$label_fermentables = "Erjeszthető anyagok";
$label_malt_extract = "Malátakivonat";
$label_grain = "Gabona";
$label_hops = "Komlók";
$label_hop = "Komló";
$label_mash = "Cefrézés";
$label_steep = "Áztatás";
$label_other = "Egyéb";
$label_weight = "Tömeg";
$label_use = "Felhasználás";
$label_time = "Idő";
$label_first_wort = "Első komlózás";
$label_boil = "Forralás";
$label_aroma = "Aroma";
$label_dry_hop = "Szárazkomlózás";
$label_type = "Típus";
$label_bittering = "Keserítő";
$label_both = "Mindkettő";
$label_form = "Forma";
$label_whole = "Egész";
$label_pellets = "Pellet";
$label_plug = "Dugó";
$label_extract = "Kivonat";
$label_date = "Dátum";
$label_bottled = "Palackozva";
$label_misc = "Vegyes";
$label_minutes = "Perc";
$label_hours = "Óra";
$label_step = "Lépés";
$label_temperature = "Hőmérséklet";
$label_water = "Víz";
$label_amount = "Mennyiség";
$label_yeast = "Élesztő";
$label_name = "Név";
$label_manufacturer = "Gyártó";
$label_nutrients = "Tápanyagok";
$label_liquid = "Folyékony";
$label_ale = "Ale";
$label_lager = "Lager";
$label_wine = "Bor";
$label_champagne = "Pezsgő";
$label_boil = "Forralás";
$label_fermentation = "Erjesztés";
$label_finishing = "Befejezés";
$label_finings = "Derítőanyagok";
$label_primary = "Elsődleges";
$label_secondary = "Másodlagos";
$label_days = "Napok";
$label_forced = "Kényszerített CO2";
$label_bottle_cond = "Palackban érlelt";
$label_volume = "Térfogat";
$label_og = "Eredeti fajsúly";
$label_fg = "Végső fajsúly";
$label_starter = "Előkultúra";
$label_password = "Jelszó";
$label_judging_number = "Bírálati szám";
$label_check_in = "Nevezés bejelentkeztetése";
$label_box_number = "Dobozszám";
$label_first_name = "Keresztnév";
$label_last_name = "Vezetéknév";
$label_secret_01 = "Mi a kedvenc söre?";
$label_secret_02 = "Mi volt az első háziállata neve?";
$label_secret_03 = "Mi volt annak az utcának a neve, ahol felnőtt?";
$label_secret_04 = "Mi volt a középiskolája kabalája?";
$label_security_answer = "Biztonsági kérdés válasza";
$label_security_question = "Biztonsági kérdés";
$label_judging = "Bírálat";
$label_judge = "Bíráló";
$label_steward = "Felszolgáló";
$label_account_info = "Fiókadatok";
$label_street_address = "Utca, házszám";
$label_address = "Cím";
$label_city = "Város";
$label_state_province = "Megye/Tartomány";
$label_zip = "Irányítószám";
$label_country = "Ország";
$label_phone = "Telefon";
$label_phone_primary = "Elsődleges telefonszám";
$label_phone_secondary = "Másodlagos telefonszám";
$label_drop_off = "Nevezés leadása";
$label_drop_offs = "Leadási helyszínek";
$label_club = "Klub";
$label_aha_number = "AHA tagsági szám";
$label_org_notes = "Megjegyzések a szervezőnek";
$label_avail = "Elérhetőség";
$label_location = "Helyszín";
$label_judging_avail = "Bírálati alkalom elérhetősége";
$label_stewarding = "Felszolgálás";
$label_stewarding_avail = "Felszolgálói alkalom elérhetősége";
$label_bjcp_id = "BJCP azonosító";
$label_bjcp_mead = "Minősített mézbíráló";
$label_bjcp_rank = "BJCP rang";
$label_designations = "Megjelölések";
$label_judge_sensory = "Érzékszervi képzésben részt vett bíráló";
$label_judge_pro = "Professzionális sörfőző";
$label_judge_comps = "Bírált versenyek";
$label_judge_preferred = "Preferált stílusok";
$label_judge_non_preferred = "Nem preferált stílusok";
$label_waiver = "Nyilatkozat";
$label_add_admin = "Admin felhasználói adatok hozzáadása";
$label_add_account = "Fiókadatok hozzáadása";
$label_edit_account = "Fiókadatok szerkesztése";
$label_entries = "Nevezések";
$label_confirmed = "Megerősítve";
$label_paid = "Fizetve";
$label_updated = "Frissítve";
$label_mini_bos = "Mini-BOS";
$label_actions = "Műveletek";
$label_score = "Pontszám";
$label_winner = "Győztes?";
$label_change_email = "E-mail módosítása";
$label_change_password = "Jelszó módosítása";
$label_add_beerXML = "Nevezés hozzáadása BeerXML segítségével";
$label_none_entered = "Nincs megadva";
$label_none = "Nincs";
$label_discount = "Kedvezmény";
$label_subject = "Tárgy";
$label_message = "Üzenet";
$label_send_message = "Üzenet küldése";
$label_email = "E-mail cím";
$label_account_registration = "Fiók regisztráció";
$label_entry_registration = "Nevezési regisztráció";
$label_entry_fees = "Nevezési díjak";
$label_entry_limit = "Nevezési limit";
$label_entry_info = "Nevezési információ";
$label_entry_per_entrant = "Nevezési limitek résztvevőnként";
$label_categories_accepted = "Elfogadott kategóriák";
$label_judging_categories = "Bírálati kategóriák";
$label_entry_acceptance_rules = "Nevezés elfogadási szabályok";
$label_shipping_info = "Szállítási információ";
$label_packing_shipping = "Csomagolás és szállítás";
$label_awards = "Díjak";
$label_awards_ceremony = "Díjkiosztó ünnepség";
$label_circuit = "Körverseny minősítés";
$label_hosted = "Hosztolt kiadás";
$label_entry_check_in = "Nevezés bejelentkeztetése";
$label_cash = "Készpénz";
$label_check = "Csekk";
$label_pay_online = "Online fizetés";
$label_cancel = "Mégse";
$label_understand = "Megértettem";
$label_fee_discount = "Kedvezményes nevezési díj";
$label_discount_code = "Kedvezménykód";
$label_register_judge = "Nevezőként, bírálóként vagy felszolgálóként regisztrál?";
$label_register_judge_standard = "Bíráló vagy felszolgáló regisztrálása (Standard)";
$label_register_judge_quick = "Bíráló vagy felszolgáló regisztrálása (Gyors)";
$label_all_participants = "Minden résztvevő";
$label_open = "Nyitva";
$label_closed = "Zárva";
$label_judging_loc = "Bírálati alkalmak";
$label_new = "Új";
$label_old = "Régi";
$label_sure = "Biztos benne?";
$label_judges = "Bírálók";
$label_stewards = "Felszolgálók";
$label_staff = "Személyzet";
$label_category = "Kategória";
$label_delete = "Törlés";
$label_undone = "Ez nem vonható vissza";
$label_bitterness = "Keserűség";
$label_close = "Bezárás";
$label_custom_style = "Egyéni stílus";
$label_custom_style_types = "Egyéni stílustípusok";
$label_assigned_to_table = "Asztalhoz rendelve";
$label_organizer = "Szervező";
$label_by_table = "Asztal szerint";
$label_by_category = "Stílus szerint";
$label_by_subcategory = "Alstílus szerint";
$label_by_last_name = "Vezetéknév szerint";
$label_by_table = "Asztal szerint";
$label_by_location = "Alkalom helyszíne szerint";
$label_shipping_entries = "Nevezések szállítása";
$label_no_availability = "Nincs meghatározott elérhetőség";
$label_error = "Hiba";
$label_round = "Forduló";
$label_flight = "Kóstolócsoport";
$label_rounds = "Fordulók";
$label_flights = "Kóstolócsoportok";
$label_sign_in = "Bejelentkezés";
$label_signature = "Aláírás";
$label_assignment = "Beosztás";
$label_assignments = "Beosztások";
$label_letter = "Levél";
$label_re_enter = "Újra megadás";
$label_website = "Weboldal";
$label_place = "Helyezés";
$label_cheers = "Egészségére";
$label_count = "Darabszám";
$label_total = "Összesen";
$label_participant = "Résztvevő";
$label_entrant = "Nevező";
$label_received = "Beérkezett";
$label_please_note = "Kérjük, vegye figyelembe";
$label_pull_order = "Kiválasztási sorrend";
$label_box = "Doboz";
$label_sorted = "Rendezve";
$label_subcategory = "Alkategória";
$label_affixed = "Címke felragasztva?";
$label_points = "Pontok";
$label_comp_id = "BJCP versenyazonosító";
$label_days = "Napok";
$label_sessions = "Alkalmak";
$label_number = "Szám";
$label_more_info = "További információ";
$label_entry_instructions = "Nevezési útmutató";
$label_commercial_examples = "Kereskedelmi példák";
$label_users = "Felhasználók";
$label_participants = "Résztvevők";
$label_please_confirm = "Kérjük, erősítse meg";
$label_undone = "Ez nem vonható vissza.";
$label_data_retain = "Megőrzendő adatok";
$label_comp_portal = "Versenykatalógus";
$label_comp = "Verseny";
$label_continue = "Tovább";
$label_host = "Házigazda";
$label_closing_soon = "Hamarosan zárul";
$label_access = "Hozzáférés";
$label_length = "Hossz";
$label_admin = "Adminisztráció";
$label_admin_short = "Admin";
$label_admin_dashboard = "Vezérlőpult";
$label_admin_judging = $label_judging;
$label_admin_stewarding = "Felszolgálás";
$label_admin_participants = $label_participants;
$label_admin_entries = $label_entries;
$label_admin_comp_info = "Verseny információ";
$label_admin_web_prefs = "Weboldal beállítások";
$label_admin_judge_prefs = "Bírálati/verseny szervezési beállítások";
$label_admin_archives = "Archívum";
$label_admin_style = $label_style;
$label_admin_styles = "Stílusok";
$label_admin_dropoff = $label_drop_offs;
$label_admin_judging_loc = $label_judging_loc;
$label_admin_contacts = "Kapcsolattartók";
$label_admin_tables = "Asztalok";
$label_admin_scores = "Pontszámok";
$label_admin_bos = $label_bos;
$label_admin_bos_acr = "BOS";
$label_admin_style_types = "Stílustípusok";
$label_admin_custom_cat = "Egyéni kategóriák";
$label_admin_custom_cat_data = "Egyéni kategória nevezések";
$label_admin_sponsors = $label_sponsors;
$label_admin_entry_count = "Nevezések száma stílus szerint";
$label_admin_entry_count_sub = "Nevezések száma alstílus szerint";
$label_admin_custom_mods = "Egyéni modulok";
$label_admin_check_in = $label_entry_check_in;
$label_admin_make_admin = "Felhasználói szint módosítása";
$label_admin_register = "Résztvevő regisztrálása";
$label_admin_upload_img = "Képek feltöltése";
$label_admin_upload_doc = "Pontozólapok és egyéb dokumentumok feltöltése";
$label_admin_password = "Felhasználó jelszavának módosítása";
$label_admin_edit_account = "Felhasználói fiók szerkesztése";
$label_account_summary = "Fiók összefoglaló";
$label_confirmed_entries = "Megerősített nevezések";
$label_unpaid_confirmed_entries = "Fizetetlen megerősített nevezések";
$label_total_entry_fees = "Összes nevezési díj";
$label_entry_fees_to_pay = "Fizetendő nevezési díj";
$label_entry_drop_off = "Nevezés leadása";
$label_maintenance = "Karbantartás";
$label_judge_info = "Bírálói információ";
$label_cellar = "Pincém";
$label_verify = "Ellenőrzés";
$label_entry_number = "Nevezési szám";

/**
 * ------------------------------------------
 * Headers
 * Missing punctuation intentional for all.
 * ------------------------------------------
 */
$header_text_000 = "A telepítés sikeres volt.";
$header_text_001 = "Ön most bejelentkezett, és tovább szabhatja verseny weboldalát.";
$header_text_002 = "Azonban a config.php fájl jogosultságait nem sikerült módosítani.";
$header_text_003 = "Erősen ajánlott a config.php fájl szerverjogosultságait (chmod) 555-re módosítani. Ehhez a szerveren kell hozzáférnie a fájlhoz.";
$header_text_004 = "Továbbá a config.php fájlban a &#36;setup_free_access változó jelenleg TRUE értékre van állítva. Biztonsági okokból ezt az értéket FALSE-ra kell visszaállítani. Ehhez közvetlenül kell szerkesztenie a config.php fájlt, és újra fel kell töltenie a szerverre.";
$header_text_005 = "Az információ sikeresen hozzáadva.";
$header_text_006 = "Az információ sikeresen szerkesztve.";
$header_text_007 = "Hiba történt.";
$header_text_008 = "Kérjük, próbálja újra.";
$header_text_009 = "Az adminisztrációs funkciók eléréséhez adminisztrátornak kell lennie.";
$header_text_010 = "Módosítás";
$header_text_011 = $label_email;
$header_text_012 = $label_password;
$header_text_013 = "A megadott e-mail cím már használatban van, kérjük, adjon meg másik e-mail címet.";
$header_text_014 = "Probléma történt az utolsó kéréssel, kérjük, próbálja újra.";
$header_text_015 = "A jelenlegi jelszava helytelen volt.";
$header_text_016 = "Kérjük, adjon meg egy e-mail címet.";
$header_text_017 = "Sajnáljuk, probléma történt az utolsó bejelentkezési kísérlettel.";
$header_text_018 = "Sajnáljuk, a megadott felhasználónév már használatban van.";
$header_text_019 = "Lehet, hogy már létrehozott egy fiókot?";
$header_text_020 = "Ha igen, jelentkezzen be itt.";
$header_text_021 = "A megadott felhasználónév nem érvényes e-mail cím.";
$header_text_022 = "Kérjük, adjon meg egy érvényes e-mail címet.";
$header_text_023 = "A CAPTCHA sikertelen volt.";
$header_text_024 = "A megadott e-mail címek nem egyeznek.";
$header_text_025 = "A megadott AHA szám már szerepel a rendszerben.";
$header_text_026 = "Az online fizetése beérkezett és a tranzakció befejeződött. Kérjük, vegye figyelembe, hogy a fizetési állapot frissítéséhez néhány percet várnia kell - győződjön meg róla, hogy frissíti ezt az oldalt. A PayPal-tól fizetési visszaigazolást fog kapni e-mailben.";
$header_text_027 = "Kérjük, nyomtassa ki a visszaigazolást és csatolja az egyik nevezéséhez a fizetés igazolásául.";
$header_text_028 = "Az online fizetése visszavonásra került.";
$header_text_029 = "A kód ellenőrzése megtörtént.";
$header_text_030 = "Sajnáljuk, a megadott kód helytelen volt.";
$header_text_031 = "Az adminisztrációs funkciók eléréséhez be kell jelentkeznie és adminisztrátori jogosultsággal kell rendelkeznie.";
$header_text_032 = "Sajnáljuk, probléma történt az utolsó bejelentkezési kísérlettel.";
$header_text_033 = "Kérjük, győződjön meg arról, hogy az e-mail címe és jelszava helyes.";
$header_text_034 = "Jelszó-visszaállítási token lett generálva és elküldve a fiókjához tartozó e-mail címre.";
$header_text_035 = "- most már bejelentkezhet jelenlegi felhasználónevével és az új jelszóval.";
$header_text_036 = "Ön kijelentkezett.";
$header_text_037 = "Újra bejelentkezik?";
$header_text_038 = "Az ellenőrző kérdése nem egyezik az adatbázisban tárolt adattal.";
$header_text_039 = "Az azonosító ellenőrzési információ el lett küldve a fiókjához tartozó e-mail címre.";
$header_text_040 = "Az üzenete elküldésre került a következő címzettnek:";
$header_text_041 = $header_text_023;
$header_text_042 = "Az e-mail címe frissítve lett.";
$header_text_043 = "A jelszava frissítve lett.";
$header_text_044 = "Az információ sikeresen törölve.";
$header_text_045 = "Ellenőrizze az összes BeerXML segítségével importált nevezését.";
$header_text_046 = "Ön sikeresen regisztrált.";
$header_text_047 = "Elérte a nevezési limitet.";
$header_text_048 = "A nevezése nem lett hozzáadva.";
$header_text_049 = "Elérte az alkategória nevezési limitjét.";
$header_text_050 = "Telepítés: Adatbázistáblák és adatok létrehozása";
$header_text_051 = "Telepítés: Admin felhasználó létrehozása";
$header_text_052 = "Telepítés: Admin felhasználói adatok hozzáadása";
$header_text_053 = "Telepítés: Weboldal beállítások megadása";
$header_text_054 = "Telepítés: Verseny információ hozzáadása";
$header_text_055 = "Telepítés: Bírálati alkalmak hozzáadása";
$header_text_056 = "Telepítés: Leadási helyszínek hozzáadása";
$header_text_057 = "Telepítés: Elfogadott stílusok kijelölése";
$header_text_058 = "Telepítés: Bírálati beállítások megadása";
$header_text_059 = "Nevezés importálása BeerXML segítségével";
$header_text_060 = "A nevezése rögzítésre került.";
$header_text_061 = "A nevezése megerősítésre került.";
$header_text_065 = "Minden beérkezett nevezés ellenőrizve lett, és az asztalhoz nem rendelt nevezések hozzárendelésre kerültek.";
$header_text_066 = "Az információ sikeresen frissítve.";
$header_text_067 = "A megadott utótag már használatban van, kérjük, adjon meg másikat.";
$header_text_068 = "A megadott versenyadatok törölve lettek.";
$header_text_069 = "Az archívum sikeresen létrehozva. ";
$header_text_070 = "Válassza ki a megtekinteni kívánt archívum nevét.";
$header_text_071 = "Ne felejtse el frissíteni a ".$label_admin_comp_info." és a ".$label_admin_judging_loc." menüpontokat, ha új versenyt indít.";
$header_text_072 = "A(z) \"".$filter."\" archívum törölve.";
$header_text_073 = "A rekordok frissítve lettek.";
$header_text_074 = "A megadott felhasználónév már használatban van.";
$header_text_075 = "Hozzáad másik leadási helyszínt?";
$header_text_076 = "Hozzáad másik bírálati helyszínt, dátumot vagy időpontot?";
$header_text_077 = "Az imént definiált asztalhoz nincsenek társított stílusok.";
$header_text_078 = "Egy vagy több kötelező adat hiányzik - alább pirossal kiemelve.";
$header_text_079 = "A megadott e-mail címek nem egyeznek.";
$header_text_080 = "A megadott AHA szám már szerepel a rendszerben.";
$header_text_081 = "Minden nevezés fizetettként lett jelölve.";
$header_text_082 = "Minden nevezés beérkezettként lett jelölve.";
$header_text_083 = "Minden meg nem erősített nevezés megerősítettként lett jelölve.";
$header_text_084 = "Minden résztvevő beosztása törölve lett.";
$header_text_085 = "A megadott bírálati szám nem található az adatbázisban.";
$header_text_086 = "Minden nevezési stílus BJCP 2008-ról BJCP 2015-re lett konvertálva.";
$header_text_087 = "Az adatok sikeresen törölve.";
$header_text_088 = "A bíráló/felszolgáló sikeresen hozzáadva. Ne feledje, hogy a felhasználót bírálóként vagy felszolgálóként kell kijelölni, mielőtt asztalokhoz rendeli.";
$header_text_089 = "A fájl sikeresen feltöltve. Ellenőrizze a listát a megerősítéshez.";
$header_text_090 = "A feltölteni kívánt fájl nem elfogadott fájltípus és/vagy meghaladja a maximális fájlméretet.";
$header_text_091 = "A fájl(ok) sikeresen törölve.";
$header_text_092 = "A teszt e-mail legenerálásra került. Ellenőrizze a spam mappáját is.";
$header_text_093 = "A felhasználó jelszava megváltozott. Feltétlenül közölje vele az új jelszavát!";
$header_text_094 = "A user_images mappa jogosultságának 755-re módosítása sikertelen volt.";
$header_text_095 = "A mappa jogosultságát manuálisan kell módosítania. Tekintse meg az FTP program vagy az internetszolgáltató dokumentációját a chmod (mappa jogosultságok) beállításához.";
$header_text_096 = "A bírálati számok újragenerálva.";
$header_text_097 = "A telepítés sikeresen befejeződött!";
$header_text_098 = "BIZTONSÁGI OKOKBÓL azonnal állítsa a config.php fájlban a &#36;setup_free_access változót FALSE értékre.";
$header_text_099 = "Ellenkező esetben a telepítése és szervere biztonsági réseknek van kitéve.";
$header_text_100 = "Jelentkezzen be most az Admin Vezérlőpult eléréséhez";
$header_text_101 = "A telepítés sikeresen frissítve!";
$header_text_102 = "Az e-mail címek nem egyeznek.";
$header_text_103 = "Kérjük, jelentkezzen be a fiókja eléréséhez.";
$header_text_104 = "Nem rendelkezik elegendő hozzáférési jogosultsággal az oldal megtekintéséhez.";
$header_text_105 = "A nevezés elfogadásához és megerősítéséhez további információ szükséges.";
$header_text_106 = "Lásd az alább PIROSSAL kiemelt területe(ke)t.";
$header_text_107 = "Kérjük, válasszon stílust.";
$header_text_108 = "Ez a nevezés nem fogadható el és nem erősíthető meg, amíg nem választott stílust. A meg nem erősített nevezések figyelmeztetés nélkül törölhetők a rendszerből.";
$header_text_109 = "Ön felszolgálóként regisztrált.";
$header_text_110 = "Minden nevezés fizetetlen jelölést kapott.";
$header_text_111 = "Minden nevezés nem beérkezett jelölést kapott.";

/**
 * ------------------------------------------
 * Alerts
 * ------------------------------------------
 */
$alert_text_000 = "A legfelső szintű admin és admin hozzáférést óvatosan adja meg felhasználóknak.";
$alert_text_001 = "Az adattisztítás befejeződött.";
$alert_text_002 = "A config.php fájlban a &#36;setup_free_access változó jelenleg TRUE értékre van állítva.";
$alert_text_003 = "Biztonsági okokból ezt az értéket FALSE-ra kell visszaállítani. Ehhez közvetlenül kell szerkesztenie a config.php fájlt, és újra fel kell töltenie a szerverre.";
$alert_text_005 = "Nincsenek megadva leadási helyszínek.";
$alert_text_006 = "Hozzáad leadási helyszínt?";
$alert_text_008 = "Nincsenek megadva bírálati alkalmak.";
$alert_text_009 = "Hozzáad bírálati alkalmat?";
$alert_text_011 = "Nincsenek megadva verseny kapcsolattartók.";
$alert_text_012 = "Hozzáad verseny kapcsolattartót?";
$alert_text_014 = "A jelenlegi stíluskészlete BJCP 2008.";
$alert_text_015 = "Szeretné az összes nevezést BJCP 2015-re konvertálni?";
$alert_text_016 = "Biztos benne? Ez a művelet az adatbázisban lévő összes nevezést a BJCP 2015 stílusirányelveknek megfelelően konvertálja. A kategóriák ahol lehetséges 1:1 arányban kerülnek átalakításra, azonban egyes különleges stílusokat a nevezőnek kell majd frissítenie.";
$alert_text_017 = "A funkcionalitás megőrzése érdekében a konverziót az asztalok definiálása <em>előtt</em> kell végrehajtani.";
$alert_text_019 = "Minden meg nem erősített nevezés törölve lett az adatbázisból.";
$alert_text_020 = "A meg nem erősített nevezések ki vannak emelve és <span class=\"fa fa-sm fa-exclamation-triangle text-danger\"></span> ikonnal vannak jelölve.";
$alert_text_021 = "A résztvevőkkel fel kell venni a kapcsolatot. Ezek a nevezések nem szerepelnek a díjszámításokban.";
$alert_text_023 = "Hozzáad leadási helyszínt?";
$alert_text_024 = $label_yes;
$alert_text_025 = $label_no;
$alert_text_027 = "A nevezési regisztráció még nem nyílt meg.";
$alert_text_028 = "A nevezési regisztráció lezárult.";
$alert_text_029 = "Nevezések hozzáadása nem elérhető.";
$alert_text_030 = "A verseny nevezési limitje elérve.";
$alert_text_031 = "Az Ön személyes nevezési limitje elérve.";
$alert_text_032 = "Nevezéseket ".$entry_open." időponttól vagy azt követően adhat hozzá.";
$alert_text_033 = "A fiók regisztráció ".$reg_open." időpontban nyílik meg.";
$alert_text_034 = "Kérjük, térjen vissza akkor a fiókja regisztrálásához.";
$alert_text_036 = "A nevezési regisztráció ".$entry_open." időpontban nyílik meg.";
$alert_text_037 = "Kérjük, térjen vissza akkor a nevezései hozzáadásához.";
$alert_text_039 = "A bírálói és felszolgálói regisztráció ".$judge_open." időpontban nyílik meg.";
$alert_text_040 = "Kérjük, térjen vissza akkor a bírálóként vagy felszolgálóként való regisztrációhoz.";
$alert_text_042 = "A nevezési regisztráció nyitva van!";
$alert_text_043 = "Összesen ".$total_entries." nevezés került a rendszerbe ".$current_time." időpontig.";
$alert_text_044 = "A regisztráció lezárul ";
$alert_text_046 = "A nevezési limit majdnem elérve!";
$alert_text_047 = $total_entries." a(z) ".$row_limits['prefsEntryLimit']." maximális nevezésből került a rendszerbe ".$current_time." időpontig.";
$alert_text_049 = "A nevezési limit elérve.";
$alert_text_050 = "A(z) ".$row_limits['prefsEntryLimit']." nevezéses limit elérve. További nevezések nem fogadhatók el.";
$alert_text_052 = "A fizetett nevezési limit elérve.";
$alert_text_053 = "A(z) ".$row_limits['prefsEntryLimitPaid']." <em>fizetett</em> nevezéses limit elérve. További nevezések nem fogadhatók el.";
$alert_text_055 = "A fiók regisztráció lezárult.";
$alert_text_056 = "Ha már regisztrált fiókot, kérjük, jelentkezzen be.";
$alert_text_057 = "jelentkezzen be itt"; // lower-case and missing punctuation intentional
$alert_text_059 = "A nevezési regisztráció lezárult.";
$alert_text_060 = "Összesen ".$total_entries." nevezés került a rendszerbe.";
$alert_text_062 = "A nevezések leadása lezárult.";
$alert_text_063 = "A leadási helyszíneken már nem fogadnak el palackokat.";
$alert_text_065 = "A nevezések szállítása lezárult.";
$alert_text_066 = "A szállítási helyszínen már nem fogadnak el palackokat.";
$alert_text_068 = $j_s_text." regisztráció nyitva van.";
$alert_text_069 = "Regisztráljon itt"; // missing punctuation intentional
$alert_text_070 = $j_s_text." regisztráció lezárul ".$judge_closed." időpontban.";
$alert_text_072 = "A regisztrált bírálók limitje elérve.";
$alert_text_073 = "További bírálói regisztrációk nem fogadhatók el.";
$alert_text_074 = "Felszolgálóként való regisztráció még elérhető.";
$alert_text_076 = "A regisztrált felszolgálók limitje elérve.";
$alert_text_077 = "További felszolgálói regisztrációk nem fogadhatók el.";
$alert_text_078 = "Bírálóként való regisztráció még elérhető.";
$alert_text_080 = "Helytelen jelszó.";
$alert_text_081 = "Jelszó elfogadva.";
$alert_email_valid = "Az e-mail formátum érvényes.";
$alert_email_not_valid = "Az e-mail formátum nem érvényes.";
$alert_email_in_use = "A megadott e-mail cím már használatban van &ndash; nem fogja tudni befejezni a regisztrációt. <strong>Lehet, hogy már regisztrált ezzel az e-mail címmel?</strong> Ha igen, kérjük, jelentkezzen be.";
$alert_email_not_in_use = "Gratulálunk! A megadott e-mail cím nincs használatban.";

/**
 * ------------------------------------------
 * Public Pages
 * ------------------------------------------
 */
$comps_text_000 = "Válassza ki az alábbi listából az elérni kívánt versenyt.";
$comps_text_001 = "Aktuális verseny:";
$comps_text_002 = "Jelenleg nincs nyitott nevezési időszakkal rendelkező verseny.";
$comps_text_003 = "Nincs olyan verseny, amelynek nevezési időszaka a következő 7 napban zárul.";

/**
 * ------------------------------------------
 * BeerXML
 * ------------------------------------------
 */
$beerxml_text_000 = "A nevezések importálása nem elérhető.";
$beerxml_text_001 = "feltöltésre került és a főzet hozzáadva a nevezései listájához.";
$beerxml_text_002 = "Sajnáljuk, ez a fájltípus nem tölthető fel. Csak .xml kiterjesztésű fájlok engedélyezettek.";
$beerxml_text_003 = "A fájl mérete meghaladja a 2MB-ot. Kérjük, módosítsa a méretet és próbálja újra.";
$beerxml_text_004 = "Érvénytelen fájl megadva.";
$beerxml_text_005 = "Azonban nem lett megerősítve. A nevezés megerősítéséhez lépjen be a nevezési listájába a további utasításokért. Vagy feltölthet egy másik BeerXML nevezést alább.";
$beerxml_text_006 = "A szerver PHP verziója nem támogatja a BeerXML import funkciót.";
$beerxml_text_007 = "PHP 5.x vagy újabb verzió szükséges &mdash; ezen a szerveren PHP ".$php_version." verzió fut.";
$beerxml_text_008 = "Keresse meg a BeerXML kompatibilis fájlt a merevlemezén, és válassza a <em>Feltöltés</em> lehetőséget.";
$beerxml_text_009 = "BeerXML fájl kiválasztása";
$beerxml_text_010 = "Nincs fájl kiválasztva...";
$beerxml_text_011 = "nevezés hozzáadva"; // lower-case and missing punctuation intentional
$beerxml_text_012 = "nevezés hozzáadva"; // lower-case and missing punctuation intentional

/**
 * ------------------------------------------
 * Add Entry
 * ------------------------------------------
 */
$brew_text_000 = "Válasszon a stílus részleteihez"; // missing punctuation intentional
$brew_text_001 = "A bírálók nem fogják tudni a nevezése nevét.";
$brew_text_002 = "[letiltva - a stílus vagy stílustípus nevezési limitje elérve]"; // missing punctuation intentional
$brew_text_003 = "[letiltva - nevezési limit elérve]"; // missing punctuation intentional
$brew_text_004 = "Konkrét típus, különleges összetevők, klasszikus stílus, erősség (sörstílusoknál), szín és/vagy egyéb információ szükséges";
$brew_text_005 = "Erősség megadása szükséges"; // missing punctuation intentional
$brew_text_006 = "Szénsavszint megadása szükséges"; // missing punctuation intentional
$brew_text_007 = "Édességi szint megadása szükséges"; // missing punctuation intentional
$brew_text_008 = "Ez a stílus megköveteli, hogy konkrét információt adjon meg a nevezéshez.";
$brew_text_009 = "Követelmények:"; // missing punctuation intentional
$brew_text_010 = "Ez a stílus további információt igényel. Kérjük, adja meg a megadott mezőben.";
$brew_text_011 = "A nevezés neve kötelező.";
$brew_text_012 = "***NEM KÖTELEZŐ*** Csak akkor adja meg, ha azt szeretné, hogy a bírálók teljes mértékben figyelembe vegyék az itt leírtakat a nevezése értékelésénél és pontozásánál. Használja olyan részletek rögzítésére, amelyeket szeretné, ha a bírálók figyelembe vennének, és amelyeket MÁS mezőkben NEM ADOTT MEG (pl. cefrézési technika, komlófajta, mézfajta, szőlőfajta, körtefajta stb.).";
$brew_text_013 = "NE használja ezt a mezőt különleges összetevők, klasszikus stílus, erősség (sör nevezéseknél) vagy szín megadására.";
$brew_text_014 = "Csak akkor adja meg, ha azt szeretné, hogy a bírálók teljes mértékben figyelembe vegyék a megadottakat a nevezése értékelésénél és pontozásánál.";
$brew_text_015 = "Kivonat típusa (pl. világos, sötét) vagy márka.";
$brew_text_016 = "Gabona típusa (pl. pilsner, pale ale stb.)";
$brew_text_017 = "Összetevő típusa vagy neve.";
$brew_text_018 = "Komló neve.";
$brew_text_019 = "Kérjük, csak számokat adjon meg.";
$brew_text_020 = "Törzs neve (pl. 1056 American Ale).";
$brew_text_021 = "Wyeast, White Labs stb.";
$brew_text_022 = "1 smackpack, 2 fiola, 2000 ml stb.";
$brew_text_023 = "Elsődleges erjesztés napokban.";
$brew_text_024 = "Cukrosítási pihenő stb.";
$brew_text_025 = "Másodlagos erjesztés napokban.";
$brew_text_026 = "Egyéb erjesztés napokban.";

/**
 * ------------------------------------------
 * My Account
 * ------------------------------------------
 */
$brewer_text_000 = "Kérjük, csak <em>egy</em> személy nevét adja meg.";
$brewer_text_001 = "Válasszon egyet. Ez a kérdés szolgál az Ön azonosítására, ha elfelejtené jelszavát.";
$brewer_text_003 = "A GABF Pro-Am főzési lehetőségre való jelentkezéshez AHA tagnak kell lennie.";
$brewer_text_004 = "Adjon meg minden olyan információt, amelyről úgy gondolja, hogy a verseny szervezőjének, bíráló koordinátornak vagy a verseny személyzetének tudnia kell (pl. allergiák, speciális étrendi korlátozások, pólóméret stb.).";
$brewer_text_005 = "Nem alkalmazható";
$brewer_text_006 = "Hajlandó és képes bírálóként szolgálni ezen a versenyen?";
$brewer_text_007 = "Letette a BJCP mézbírálói vizsgát?";
$brewer_text_008 = "* A <em>Nem-BJCP</em> rang azoknak szól, akik nem tették le a BJCP sörbírálói felvételi vizsgát, és <em>nem</em> professzionális sörfőzők.";
$brewer_text_009 = "** Az <em>Ideiglenes</em> rang azoknak szól, akik letették a BJCP sörbírálói felvételi vizsgát, de még nem tették le a BJCP sörbírálói vizsgát.";
$brewer_text_010 = "Csak az első két megjelölés fog megjelenni a nyomtatott pontozólap címkéin.";
$brewer_text_011 = "Hány versenyen szolgált korábban <strong>".strtolower($label_judge)."</strong>ként?";
$brewer_text_012 = "Csak preferenciák megadásához. Ha egy stílust nem jelöl be, az azt jelenti, hogy elérhető annak bírálására - nem kell az összes bírálható stílust bejelölnie.";
$brewer_text_013 = "Kattintson vagy koppintson a fenti gombra a nem preferált bírálati stílusok listájának kibontásához.";
$brewer_text_014 = "Nem kell megjelölnie azokat a stílusokat, amelyekben nevezései vannak; a rendszer nem fogja Önt olyan asztalhoz rendelni, ahol nevezése van.";
$brewer_text_015 = "Hajlandó felszolgálóként szolgálni ezen a versenyen?";
$brewer_text_016 = "A bírálaton való részvételem teljes mértékben önkéntes. Tudomásul veszem, hogy a bírálatban való részvétel alkoholos italok fogyasztásával jár, és ez befolyásolhatja az érzékelési képességeimet és reakcióimat.";
$brewer_text_017 = "Kattintson vagy koppintson a fenti gombra a preferált bírálati stílusok listájának kibontásához.";
$brewer_text_018 = "E jelölőnégyzet bejelölésével gyakorlatilag egy jogi dokumentumot írok alá, amelyben elfogadom a felelősséget a magatartásomért, viselkedésemért és cselekedeimért, és teljes mértékben felmentem a versenyt és annak szervezőit, egyénileg vagy együttesen, a magatartásomért, viselkedésemért és cselekedeimért való felelősség alól.";
$brewer_text_019 = "Ha bármelyik versenyen bírálóként kíván szolgálni, kattintson vagy koppintson a fenti gombra a bírálóval kapcsolatos információk megadásához.";
$brewer_text_020 = "Hajlandó személyzeti tagként szolgálni ezen a versenyen?";
$brewer_text_021 = "A verseny személyzete olyan személyekből áll, akik különböző szerepekben segítik a verseny szervezését és lebonyolítását a bírálat előtt, alatt és után. Bírálók és felszolgálók is lehetnek személyzeti tagok. A személyzeti tagok BJCP pontokat szerezhetnek, ha a verseny hivatalosan jóváhagyott.";

/**
 * ------------------------------------------
 * Contact
 * ------------------------------------------
 */
$contact_text_000 = "Az alábbi hivatkozások segítségével veheti fel a kapcsolatot a verseny koordinálásában részt vevő személyekkel:";
$contact_text_001 = "Az alábbi űrlap segítségével léphet kapcsolatba a verseny tisztségviselőjével. Minden csillaggal jelölt mező kötelező.";
$contact_text_002 = "Továbbá egy másolat az Ön által megadott e-mail címre is elküldésre került.";
$contact_text_003 = "Szeretne másik üzenetet küldeni?";

/**
 * ------------------------------------------
 * Home Pages
 * ------------------------------------------
 */
$default_page_text_000 = "Nincsenek megadva leadási helyszínek.";
$default_page_text_001 = "Hozzáad leadási helyszínt?";
$default_page_text_002 = "Nincsenek megadva bírálati dátumok/helyszínek.";
$default_page_text_003 = "Hozzáad bírálati helyszínt?";
$default_page_text_004 = "Győztes nevezések";
$default_page_text_005 = "A győztesek közzététele a következő dátumon vagy azt követően történik:";
$default_page_text_006 = "Üdvözöljük";
$default_page_text_007 = "Tekintse meg fiókja adatait és nevezéseinek listáját.";
$default_page_text_008 = "Tekintse meg fiókja adatait itt.";
$default_page_text_009 = "Best of Show győztesek";
$default_page_text_010 = "Győztes nevezések";
$default_page_text_011 = "Csak egyszer kell regisztrálnia adatait, és visszatérhet erre az oldalra további főzetek hozzáadásához vagy a már megadott főzetek szerkesztéséhez.";
$default_page_text_012 = "Ha szeretné, a nevezési díjakat online is kifizetheti.";
$default_page_text_013 = "Verseny tisztségviselő";
$default_page_text_014 = "Verseny tisztségviselők";
$default_page_text_015 = "E-mailt küldhet az alábbi személyek bármelyikének a következőn keresztül: ";
$default_page_text_016 = "büszkén mutatja be a következőket:";
$default_page_text_017 = "a következő versenyhez:";
$default_page_text_018 = "Best of Show győztesek letöltése PDF formátumban.";
$default_page_text_019 = "Best of Show győztesek letöltése HTML formátumban.";
$default_page_text_020 = "Győztes nevezések letöltése PDF formátumban.";
$default_page_text_021 = "Győztes nevezések letöltése HTML formátumban.";
$default_page_text_022 = "Köszönjük érdeklődését a";
$default_page_text_023 = "szervezésében:";
$reg_open_text_000 = "A bírálói és felszolgálói regisztráció";
$reg_open_text_001 = "Nyitva";
$reg_open_text_002 = "Ha <em>még nem</em> regisztrált és hajlandó önkénteskedni,";
$reg_open_text_003 = "kérjük, regisztráljon";
$reg_open_text_004 = "Ha <em>már</em> regisztrált, jelentkezzen be, majd válassza a <em>Fiók szerkesztése</em> lehetőséget a Fiókom menüből, amelyet a";
$reg_open_text_005 = "ikon jelöl a felső menüben.";
$reg_open_text_006 = "Mivel már regisztrált,";
$reg_open_text_007 = "ellenőrizze fiókja adatait";
$reg_open_text_008 = "hogy lássa, jelezte-e bírálói és/vagy felszolgálói szándékát.";
$reg_open_text_009 = "Ha hajlandó bírálóként vagy felszolgálóként részt venni, kérjük, térjen vissza a regisztrációhoz a következő időpontban vagy azt követően:";
$reg_open_text_010 = "A nevezési regisztráció";
$reg_open_text_011 = "Nevezéseinek a rendszerbe való felvételéhez";
$reg_open_text_012 = "kérjük, haladjon végig a regisztrációs folyamaton";
$reg_open_text_013 = "ha már rendelkezik fiókkal.";
$reg_open_text_014 = "használja a nevezés hozzáadása űrlapot";
$reg_open_text_015 = "A bírálói regisztráció";
$reg_open_text_016 = "A felszolgálói regisztráció";
$reg_closed_text_000 = "Köszönet és sok sikert mindenkinek, aki nevezett a";
$reg_closed_text_001 = "Jelenleg";
$reg_closed_text_002 = "regisztrált résztvevő, bíráló és felszolgáló van.";
$reg_closed_text_003 = "regisztrált nevezés és";
$reg_closed_text_004 = "regisztrált résztvevő, bíráló és felszolgáló van.";
$reg_closed_text_005 = "A következő dátum szerint:";
$reg_closed_text_006 = "beérkezett és feldolgozott nevezés (ez a szám frissülni fog, ahogy a nevezések beérkeznek a leadási pontokról és előkészítik őket a bírálatra).";
$reg_closed_text_007 = "A verseny bírálati időpontjai még nem kerültek meghatározásra. Kérjük, látogasson vissza később.";
$reg_closed_text_008 = "Térkép ide:";
$judge_closed_000 = "Köszönet mindenkinek, aki részt vett a";
$judge_closed_001 = "Összesen";
$judge_closed_002 = "nevezést bíráltak el és";
$judge_closed_003 = "regisztrált résztvevő, bíráló és felszolgáló vett részt.";

/**
 * ------------------------------------------
 * Entry Info
 * ------------------------------------------
 */
$entry_info_text_000 = "Fiókját a következő időponttól hozhatja létre:";
$entry_info_text_001 = "eddig:";
$entry_info_text_002 = "Bírálók és felszolgálók a következő időponttól regisztrálhatnak:";
$entry_info_text_003 = "nevezésenként";
$entry_info_text_004 = "Fiókját ma is létrehozhatja, a következő időpontig:";
$entry_info_text_005 = "Bírálók és felszolgálók most regisztrálhatnak, a következő időpontig:";
$entry_info_text_006 = "Regisztráció a következőhöz:";
$entry_info_text_007 = "csak bírálók és felszolgálók";
$entry_info_text_008 = "elfogadás a következő időpontig:";
$entry_info_text_009 = "A regisztráció <strong class=\"text-danger\">lezárult</strong>.";
$entry_info_text_010 = "Üdvözöljük";
$entry_info_text_011 = "Tekintse meg fiókja adatait és nevezéseinek listáját.";
$entry_info_text_012 = "Tekintse meg fiókja adatait itt.";
$entry_info_text_013 = "nevezésenként a következő dátum után:";
$entry_info_text_014 = "Nevezéseit a következő időponttól adhatja hozzá a rendszerhez:";
$entry_info_text_015 = "Nevezéseit ma is hozzáadhatja a rendszerhez, a következő időpontig:";
$entry_info_text_016 = "A nevezési regisztráció <strong class=\"text-danger\">lezárult</strong>.";
$entry_info_text_017 = "korlátlan számú nevezéshez.";
$entry_info_text_018 = "AHA tagok számára.";
$entry_info_text_019 = "A verseny nevezési korlátja:";
$entry_info_text_020 = "nevezés erre a versenyre.";
$entry_info_text_021 = "Minden résztvevő legfeljebb";
$entry_info_text_022 = strtolower($label_entry);
$entry_info_text_023 = strtolower($label_entries);
$entry_info_text_024 = "nevezés alstílusonként";
$entry_info_text_025 = "nevezés alstílusonként";
$entry_info_text_026 = "a kivételek alább részletezve";
$entry_info_text_027 = strtolower($label_subcategory);
$entry_info_text_028 = "alstílus";
$entry_info_text_029 = "nevezés a következő";
$entry_info_text_030 = "nevezés a következő";
$entry_info_text_031 = "Fiókja létrehozása és nevezéseinek a rendszerbe történő felvétele után ki kell fizetnie a nevezési díja(ka)t. Az elfogadott fizetési módok a következők:";
$entry_info_text_032 = $label_cash;
$entry_info_text_033 = $label_check.", a következő névre kiállítva:";
$entry_info_text_034 = "Hitel-/bankkártya és e-csekk, PayPal-on keresztül";
$entry_info_text_035 = "A verseny bírálati időpontjai még nem kerültek meghatározásra. Kérjük, látogasson vissza később.";
$entry_info_text_036 = "A nevezési palackok a szállítási helyszínen fogadhatók";
$entry_info_text_037 = "Nevezések postázása ide:";
$entry_info_text_038 = "Csomagolja gondosan nevezéseit egy erős dobozba. Bélelje ki a karton belsejét egy műanyag szemeteszsákkal. Válassza el és csomagolja be minden palackot megfelelő csomagolóanyaggal. Kérjük, ne csomagoljon túl!";
$entry_info_text_039 = "Írja rá jól olvashatóan: <em>Törékeny. Ez az oldal felfelé.</em> a csomagra. Kérjük, kizárólag buborékfóliát használjon csomagolóanyagként.";
$entry_info_text_040 = "Helyezze <em>minden egyes</em> palackcímkéjét egy kis zipzáras tasakba, mielőtt a megfelelő palackra erősítené. Így a szervező azonosítani tudja, melyik nevezés sérült meg szállítás közben.";
$entry_info_text_041 = "Minden ésszerű erőfeszítést megteszünk, hogy felvegyük a kapcsolatot azokkal a nevezőkkel, akiknek a palackjai eltörtek, és egyeztessünk a pótpalackok küldéséről.";
$entry_info_text_042 = "Ha az Egyesült Államokban él, kérjük, vegye figyelembe, hogy <strong>illegális</strong> nevezéseit az Egyesült Államok Postaszolgálatán (USPS) keresztül szállítani. A magán szállítmányozó cégeknek jogukban áll visszautasítani a szállítmányt, ha tudomást szereznek arról, hogy a csomag üveget és/vagy alkoholos italokat tartalmaz. Legyen tudatában, hogy a nemzetközileg postázott nevezésekhez a vámhatóság gyakran megköveteli a megfelelő dokumentációt. Ezeket a nevezéseket a vámtisztviselők saját belátásuk szerint kinyithatják és/vagy visszaküldhetik a feladónak. Kizárólag az Ön felelőssége az összes vonatkozó törvény és előírás betartása.";
$entry_info_text_043 = "A nevezési palackok a leadási helyszíneinken fogadhatók";
$entry_info_text_044 = "Térkép ide:";
$entry_info_text_045 = "Válasszon/Koppintson a szükséges nevezési információkhoz";
$entry_info_text_046 = "Ha egy stílus neve hivatkozásként jelenik meg, az adott stílusnak speciális nevezési követelményei vannak. Válassza ki vagy koppintson a névre az alkategória követelményeinek megtekintéséhez.";

/**
 * ------------------------------------------
 * My Account Entry List
 * ------------------------------------------
 */
$brewer_entries_text_000 = "Ismert probléma áll fenn a Firefox böngészőből történő nyomtatással kapcsolatban.";
$brewer_entries_text_001 = "Megerősítetlen nevezései vannak.";
$brewer_entries_text_002 = "Minden alábbi, <span class=\"fa fa-lg fa-exclamation-circle text-danger\"></span> ikonnal jelölt nevezésnél válassza a <span class=\"fa fa-lg fa-pencil text-primary\"></span> ikont a nevezési adatok áttekintéséhez és megerősítéséhez. A megerősítetlen nevezések figyelmeztetés nélkül törölhetők a rendszerből.";
$brewer_entries_text_003 = "NEM fizethet a nevezéseiért, amíg az összes nevezés nincs megerősítve.";
$brewer_entries_text_004 = "Vannak olyan nevezései, amelyeknél meg kell adnia a konkrét típust, különleges összetevőket, klasszikus stílust, erősséget és/vagy színt.";
$brewer_entries_text_005 = "Minden alábbi, <span class=\"fa fa-lg fa-exclamation-circle text-danger\"></span> ikonnal jelölt nevezésnél válassza a <span class=\"fa fa-lg fa-pencil text-primary\"></span> ikont a szükséges információk megadásához. Az olyan kategóriákban, amelyek megkövetelik a konkrét típus, különleges összetevők, klasszikus stílus, erősség és/vagy szín megadását, az ezek nélküli nevezések a rendszer által figyelmeztetés nélkül törölhetők.";
$brewer_entries_text_006 = "Bírálói pontozólapok letöltése a következőhöz:";
$brewer_entries_text_007 = "Stílus NINCS megadva";
$brewer_entries_text_008 = "Nevezési űrlap és";
$brewer_entries_text_009 = "Palackcímkék";
$brewer_entries_text_010 = "Recept űrlap nyomtatása a következőhöz:";
$brewer_entries_text_011 = "Továbbá nem fog tudni újabb nevezést hozzáadni, mivel a verseny nevezési korlátja elérte a maximumot. Válassza a Mégse gombot ebben az ablakban, majd szerkessze a meglévő nevezést, ha meg szeretné tartani.";
$brewer_entries_text_012 = "Biztosan törölni szeretné a következő nevezést:";
$brewer_entries_text_013 = "Nevezéseket a következő időponttól adhat hozzá:";
$brewer_entries_text_014 = "Még nem adott hozzá nevezéseket a rendszerhez.";
$brewer_entries_text_015 = "Jelenleg nem törölheti nevezését.";

/**
 * ------------------------------------------
 * Past Winners
 * ------------------------------------------
 */
$past_winners_text_000 = "Korábbi győztesek megtekintése:";

/**
 * ------------------------------------------
 * Pay for Entries
 * ------------------------------------------
 */
$pay_text_000 = "Mivel a fiókregisztráció, a nevezési regisztráció, a szállítási és leadási határidők mind lejártak, a fizetéseket már nem fogadjuk el.";
$pay_text_001 = "Kérdés esetén forduljon a verseny tisztségviselőjéhez.";
$pay_text_002 = "a következő lehetőségek állnak rendelkezésére a nevezési díjak kifizetéséhez.";
$pay_text_003 = "A díjak";
$pay_text_004 = "nevezésenként";
$pay_text_005 = "nevezésenként a következő dátum után:";
$pay_text_006 = "korlátlan számú nevezéshez";
$pay_text_007 = "Díjai kedvezményesre módosultak:";
$pay_text_008 = "Összes nevezési díja:";
$pay_text_009 = "Fizetendő összeg:";
$pay_text_010 = "Díjai kifizetettként lettek megjelölve. Köszönjük!";
$pay_text_011 = "Jelenleg";
$pay_text_012 = "kifizetetlen megerősített";
$pay_text_013 = "Csatoljon csekket a teljes nevezési összegről az egyik palackjához. A csekket a következő névre kell kiállítani:";
$pay_text_014 = "A csekk másolata vagy a beváltott csekk szolgál a nevezési nyugtaként.";
$pay_text_015 = "Csatolja a teljes nevezési összegnek megfelelő készpénzfizetést egy <strong>lezárt borítékban</strong> az egyik palackjához.";
$pay_text_016 = "A visszaküldött pontozólapok szolgálnak a nevezési nyugtaként.";
$pay_text_017 = "A fizetési visszaigazoló e-mail szolgál a nevezési nyugtaként.";
$pay_text_018 = "Válassza az alábbi <em>Fizetés PayPal-lal</em> gombot az online fizetéshez.";
$pay_text_019 = "Kérjük, vegye figyelembe, hogy a tranzakciós díj:";
$pay_text_020 = "hozzáadásra kerül az összeghez.";
$pay_text_021 = "Annak érdekében, hogy PayPal fizetése <strong>kifizetettként</strong> legyen megjelölve <strong>ezen az oldalon</strong>, fizetése befejezése <strong>után</strong> feltétlenül válassza a <em>Visszatérés a kereskedőhöz</em> gombot a PayPal&rsquo;s megerősítő képernyőjén.";
$pay_text_022 = "Fizetés után feltétlenül válassza a <em>Visszatérés a kereskedőhöz</em> gombot";
$pay_text_023 = "Adja meg a verseny szervezői által biztosított kódot a kedvezményes nevezési díjhoz.";
$pay_text_024 = $pay_text_010;
$pay_text_025 = "Még nem rögzített nevezéseket.";
$pay_text_026 = "Nem fizethet a nevezéseiért, mert egy vagy több nevezése megerősítetlen.";
$pay_text_027 = "Válassza a fenti <em>Fiókom</em> menüpontot a megerősítetlen nevezések áttekintéséhez.";
$pay_text_028 = "Vannak megerősítetlen nevezései, amelyek <em>nem</em> tükröződnek az alábbi díj összesítésben.";
$pay_text_029 = "Kérjük, lépjen a nevezési listájához az összes nevezési adat megerősítéséhez. A megerősítetlen nevezések figyelmeztetés nélkül törölhetők a rendszerből.";

/**
 * ------------------------------------------
 * QR Code Check-in
 * ------------------------------------------
 */
// Ignore the next four lines
if (strpos($view, "^") !== FALSE) {
	$qr_text_019 = sprintf("%06d",$checked_in_numbers[0]);
	if (is_numeric($checked_in_numbers[1])) $qr_text_020 = sprintf("%06d",$checked_in_numbers[1]);
	else $qr_text_020 = $checked_in_numbers[1];
}

$qr_text_000 = $alert_text_080;
$qr_text_001 = $alert_text_081;

// Begin translations here
if (strpos($view, "^") !== FALSE) $qr_text_002 = sprintf("A(z) <span class=\"text-danger\">%s</span> számú nevezés bejelentkeztetve, bírálati száma: <span class=\"text-danger\">%s</span>.",$qr_text_019,$qr_text_020); else $qr_text_002 = "";
$qr_text_003 = "Ha ez a bírálati szám <em>nem</em> helyes, <strong>szkennelje újra a kódot és adja meg a helyes bírálati számot.";
if (strpos($view, "^") !== FALSE) $qr_text_004 = sprintf("A(z) %s számú nevezés bejelentkeztetve.",$qr_text_019); else $qr_text_004 = "";
if (strpos($view, "^") !== FALSE) $qr_text_005 = sprintf("A(z) %s számú nevezés nem található az adatbázisban. Tegye félre a palacko(ka)t és értesítse a verseny szervezőjét.",$qr_text_019); else $qr_text_005 = "";
if (strpos($view, "^") !== FALSE) $qr_text_006 = sprintf("A megadott bírálati szám - %s - már hozzá van rendelve a(z) %s számú nevezéshez.",$qr_text_020,$qr_text_019); else $qr_text_006 = "";
$qr_text_007 = "QR-kódos nevezés bejelentkeztetés";
$qr_text_008 = "A QR-kóddal történő nevezés bejelentkeztetéshez kérjük, adja meg a helyes jelszót. A jelszót munkamenetenként csak egyszer kell megadnia - ügyeljen arra, hogy a QR-kód olvasó alkalmazás nyitva maradjon.";
$qr_text_009 = "Bírálati szám és/vagy dobozszám hozzárendelése a következő nevezéshez:";
$qr_text_010 = "CSAK akkor adjon meg bírálati számot, ha a verseny bírálati szám címkéket használ a rendezésnél.";
$qr_text_011 = "Hat számjegy vezető nullákkal - pl. 000021.";
$qr_text_012 = "Ügyeljen a bevitt adatok ellenőrzésére, és ragassza a megfelelő bírálati szám címkéket minden palackra és palackcímkére (ha alkalmazandó).";
$qr_text_013 = "A bírálati számoknak hat karakterből kell állniuk, és nem tartalmazhatják a ^ karaktert.";
$qr_text_014 = "Várakozás a beszkennelt QR-kód bemenetre.";
$qr_text_016 = "QR-kód olvasó alkalmazásra van szüksége? Keressen a <a class=\"hide-loader\" href=\"https://play.google.com/store/search?q=qr%20code%20scanner&c=apps&hl=en\" target=\"_blank\">Google Play</a> (Android) vagy az <a class=\"hide-loader\" href=\"https://itunes.apple.com/store/\" target=\"_blank\">iTunes</a> (iOS) áruházban.";

/**
 * ------------------------------------------
 * Registration
 * ------------------------------------------
 */
$register_text_000 = "Az önkéntes ";
$register_text_001 = "Ön ";
$register_text_002 = "A regisztráció lezárult.";
$register_text_003 = "Köszönjük érdeklődését.";
$register_text_004 = "A vezeték- és keresztnevén, valamint a klubján túl megadott információk kizárólag nyilvántartási és kapcsolattartási célokat szolgálnak.";
$register_text_005 = "A versenyre való nevezés feltétele ezen információk megadása. Az Ön neve és klubja megjelenítésre kerülhet, amennyiben valamelyik nevezése helyezést ér el, de egyéb információ nem kerül nyilvánosságra.";
$register_text_006 = "Emlékeztető: Csak egy régióba nevezhet, és miután regisztrált egy helyszínen, NEM fogja tudni megváltoztatni.";
$register_text_007 = "Gyors";
$register_text_008 = "Regisztráció";
$register_text_009 = "bírálóként";
$register_text_010 = "résztvevőként";
$register_text_011 = "A regisztrációhoz hozza létre fiókját az alábbi mezők kitöltésével.";
$register_text_012 = "Résztvevő gyors hozzáadása a verseny bíráló/felszolgáló állományához. Minden így hozzáadott résztvevőhöz egy helyettesítő cím és telefonszám kerül felhasználásra, és az alapértelmezett jelszó <em>bcoem</em> lesz.";
$register_text_013 = "A versenyre való nevezés teljes egészében online történik.";
$register_text_014 = "Nevezéseinek hozzáadásához és/vagy annak jelzéséhez, hogy hajlandó bírálóként vagy felszolgálóként részt venni (bírálók és felszolgálók szintén adhatnak hozzá nevezéseket), létre kell hoznia egy fiókot rendszerünkben.";
$register_text_015 = "Az e-mail címe lesz a felhasználóneve, és a verseny személyzete információk terjesztésére fogja használni. Kérjük, győződjön meg róla, hogy helyes.";
$register_text_016 = "A regisztrációt követően folytathatja a nevezési folyamatot.";
$register_text_017 = "Minden hozzáadott nevezéshez a rendszer automatikusan rendel egy számot.";
$register_text_018 = "Válasszon egyet. Ez a kérdés lesz felhasználva személyazonosságának ellenőrzésére, ha elfelejtené jelszavát.";
$register_text_019 = "Kérjük, adjon meg egy e-mail címet.";
$register_text_020 = "A megadott e-mail címek nem egyeznek.";
$register_text_021 = "Az e-mail címek felhasználónévként szolgálnak.";
$register_text_022 = "Kérjük, adjon meg egy jelszót.";
$register_text_023 = "Kérjük, adjon meg választ a biztonsági kérdésre.";
$register_text_024 = "A biztonsági válasz legyen olyan, amire csak Ön emlékszik könnyen!";
$register_text_025 = "Kérjük, adja meg a keresztnevét.";
$register_text_026 = "Kérjük, adja meg a vezetéknevét.";
$register_text_027 = "felszolgálóként";
$register_text_028 = "Kérjük, adjon meg egy utcanevet és házszámot.";
$register_text_029 = "Kérjük, adjon meg egy várost.";
$register_text_030 = "Kérjük, adjon meg egy államot vagy tartományt.";
$register_text_031 = "Kérjük, adjon meg egy irányítószámot.";
$register_text_032 = "Kérjük, adjon meg egy elsődleges telefonszámot.";
$register_text_033 = "Csak az American Homebrewers Association tagjai jogosultak a Great American Beer Festival Pro-Am lehetőségre.";
$register_text_034 = "A regisztrációhoz be kell jelölnie a jelölőnégyzetet, jelezve, hogy elfogadja a nyilatkozatot.";

/**
 * ------------------------------------------
 * Sidebar
 * ------------------------------------------
 */
$sidebar_text_000 = "Bírálói vagy felszolgálói regisztrációk elfogadása";
$sidebar_text_001 = "Felszolgálói regisztrációk elfogadása";
$sidebar_text_002 = "Bírálói regisztrációk";
$sidebar_text_003 = "A regisztrációk már nem fogadhatók el. A bírálók és felszolgálók létszámkorlátja elérte a maximumot.";
$sidebar_text_004 = "eddig:";
$sidebar_text_005 = "Fiókregisztrációk elfogadása";
$sidebar_text_006 = "nyitva csak bírálók vagy felszolgálók számára";
$sidebar_text_007 = "nyitva csak felszolgálók számára";
$sidebar_text_008 = "nyitva csak bírálók számára";
$sidebar_text_009 = "Nevezési regisztrációk elfogadása";
$sidebar_text_010 = "A verseny fizetett nevezési korlátja elérte a maximumot.";
$sidebar_text_011 = "A verseny nevezési korlátja elérte a maximumot.";
$sidebar_text_012 = "Tekintse meg nevezéseinek listáját.";
$sidebar_text_013 = "Válassza ki ide kattintva a díjak kifizetéséhez.";
$sidebar_text_014 = "A nevezési díjak nem tartalmazzák a megerősítetlen nevezéseket.";
$sidebar_text_015 = "Megerősítetlen nevezései vannak - intézkedés szükséges a megerősítéshez.";
$sidebar_text_016 = "Tekintse meg nevezéseinek listáját.";
$sidebar_text_017 = "Önnek";
$sidebar_text_018 = "maradt a következő korlát eléréséig:";
$sidebar_text_019 = "résztvevőnként";
$sidebar_text_020 = "Elérte a következő korlátot:";
$sidebar_text_021 = "ezen a versenyen";
$sidebar_text_022 = "Nevezési palackok fogadása:";
$sidebar_text_023 = "a szállítási helyszínen";
$sidebar_text_024 = "A verseny bírálati időpontjai még nem kerültek meghatározásra. Kérjük, látogasson vissza később.";
$sidebar_text_025 = "került hozzáadásra a rendszerhez a következő dátummal:";

/**
 * ------------------------------------------
 * Styles
 * ------------------------------------------
 */
$styles_entry_text_07C = "A nevezőnek meg kell adnia, hogy a nevezés müncheni Kellerbier (világos, Helles alapú) vagy frank Kellerbier (borostyán, Märzen alapú). A nevező megadhat más típusú Kellerbiert is más alapstílusok alapján, mint a Pils, Bock, Schwarzbier, de stílusleírást kell biztosítania a bírálóknak.";
$styles_entry_text_09A = "A nevezőnek meg kell adnia, hogy a nevezés világos vagy sötét változat.";
$styles_entry_text_10C = "A nevezőnek meg kell adnia, hogy a nevezés világos vagy sötét változat.";
$styles_entry_text_21B = "A nevezőnek meg kell adnia az erősséget (session: 3,0-5,0%, standard: 5,0-7,5%, dupla: 7,5-9,5%); ha nincs erősség megadva, a standard lesz feltételezve. A nevezőnek meg kell adnia a Specialty IPA konkrét típusát a Stílusútmutatóban felsorolt ismert típusok könyvtárából, vagy a BJCP weboldalán módosítottak szerint; vagy a nevezőnek le kell írnia a Specialty IPA típusát és fő jellemzőit a megjegyzés mezőben, hogy a bírálók tudják, mire számítsanak. A nevezők megadhatják a felhasznált komlófajtákat, ha úgy érzik, hogy a bírálók nem ismerik fel az újabb komlók fajtajellemzőit. A nevezők kombinálhatják a meghatározott IPA típusokat (pl. Black Rye IPA) további leírás nélkül. A nevezők használhatják ezt a kategóriát egy meglévő BJCP alkategóriával meghatározott IPA eltérő erősségű változatához (pl. session erősségű amerikai vagy angol IPA) - kivéve ahol már létezik BJCP alkategória az adott stílushoz (pl. dupla [amerikai] IPA). Jelenleg meghatározott típusok: Black IPA, Brown IPA, White IPA, Rye IPA, Belgian IPA, Red IPA.";
$styles_entry_text_23F = "Meg kell adni a felhasznált gyümölcs típusát. A sörfőzőnek nyilatkoznia kell a szénsavszintről (alacsony, közepes, magas) és az édesség szintjéről (alacsony/nincs, közepes, magas).";
$styles_entry_text_24C = "A nevezőnek meg kell adnia, hogy szőke, borostyán vagy barna bière de garde. Ha nincs szín megadva, a bíráló megpróbálja a kezdeti megfigyelés alapján értékelni, a színhez illő maltas ízt és egyensúlyt várva.";
$styles_entry_text_25B = "A nevezőnek meg kell adnia az erősséget (asztali, standard, szuper) és a színt (világos, sötét).";
$styles_entry_text_27A = "A nevezőnek vagy meg kell adnia egy stílust BJCP által biztosított leírással, vagy hasonló leírást kell adnia a bírálóknak egy másik stílusról. Ha egy sört csak stílusnévvel és leírás nélkül neveznek be, nagyon valószínűtlen, hogy a bírálók tudják, hogyan értékeljék. Jelenleg meghatározott példák: Gose, Piwo Grodziskie, Lichtenhainer, Roggenbier, Sahti, Kentucky Common, Pre-Prohibition Lager, Pre-Prohibition Porter, London Brown Ale.";
$styles_entry_text_28A = "A nevezőnek meg kell adnia vagy egy alap sörstílust (klasszikus BJCP stílus, vagy egy általános stíluscsalád) vagy az összetevők/specifikációk/kívánt jelleg leírását. A nevezőnek meg kell adnia, ha 100%-os Brett erjesztés történt. A nevező megadhatja a felhasznált Brettanomyces törzs(ek)et, annak jellegének rövid leírásával együtt.";
$styles_entry_text_28B = "A nevezőnek a sör leírását kell megadnia, azonosítva a felhasznált élesztőt/baktériumot és vagy egy alapstílust, vagy az összetevők/specifikációk/célzott jelleg leírását.";
$styles_entry_text_28C = "A nevezőnek meg kell adnia a felhasznált gyümölcs, fűszer, gyógynövény vagy fa típusát. A nevezőnek a sör leírását kell megadnia, azonosítva a felhasznált élesztőt/baktériumot és vagy egy alapstílust, vagy az összetevők/specifikációk/célzott jelleg leírását. A sör különleges jellegének általános leírása az összes szükséges elemet lefedi.";
$styles_entry_text_29A = "A nevezőnek meg kell adnia egy alapstílust; a megadott stílusnak nem kell klasszikus stílusnak lennie. A nevezőnek meg kell adnia a felhasznált gyümölcs típusát. A savanyú gyümölcssöröket, amelyek nem lambicok, az American Wild Ale kategóriába kell nevezni.";
$styles_entry_text_29B = "A nevezőnek meg kell adnia egy alapstílust; a megadott stílusnak nem kell klasszikus stílusnak lennie. A nevezőnek meg kell adnia a felhasznált gyümölcs és fűszerek, gyógynövények vagy zöldségek (FGZ) típusát; egyedi FGZ összetevőket nem kell megadni, ha ismert fűszerkeveréket használnak (pl. almás pite fűszerkeverék).";
$styles_entry_text_29C = "A nevezőnek meg kell adnia egy alapstílust; a megadott stílusnak nem kell klasszikus stílusnak lennie. A nevezőnek meg kell adnia a felhasznált gyümölcs típusát. A nevezőnek meg kell adnia a felhasznált további erjeszthető cukor vagy különleges eljárás típusát.";
$styles_entry_text_30A = "A nevezőnek meg kell adnia egy alapstílust; a megadott stílusnak nem kell klasszikus stílusnak lennie. A nevezőnek meg kell adnia a felhasznált fűszerek, gyógynövények vagy zöldségek típusát; egyedi összetevőket nem kell megadni, ha ismert fűszerkeveréket használnak (pl. almás pite fűszerkeverék).";
$styles_entry_text_30B = "A nevezőnek meg kell adnia egy alapstílust; a megadott stílusnak nem kell klasszikus stílusnak lennie. A nevezőnek meg kell adnia a felhasznált fűszerek, gyógynövények vagy zöldségek típusát; egyedi összetevőket nem kell megadni, ha ismert fűszerkeveréket használnak (pl. sütőtökös pite fűszerkeverék). A sörnek tartalmaznia kell fűszereket, és tartalmazhat zöldségeket és/vagy cukrokat.";
$styles_entry_text_30C = "A nevezőnek meg kell adnia egy alapstílust; a megadott stílusnak nem kell klasszikus stílusnak lennie. A nevezőnek meg kell adnia a felhasznált fűszerek, cukrok, gyümölcsök vagy további erjeszthető anyagok típusát; egyedi összetevőket nem kell megadni, ha ismert fűszerkeveréket használnak (pl. forralt bor fűszerkeverék).";
$styles_entry_text_31A = "A nevezőnek meg kell adnia egy alapstílust; a megadott stílusnak nem kell klasszikus stílusnak lennie. A nevezőnek meg kell adnia a felhasznált alternatív gabona típusát.";
$styles_entry_text_31B = "A nevezőnek meg kell adnia egy alapstílust; a megadott stílusnak nem kell klasszikus stílusnak lennie. A nevezőnek meg kell adnia a felhasznált cukor típusát.";
$styles_entry_text_32A = "A nevezőnek meg kell adnia egy klasszikus stílusú alapsört. A nevezőnek meg kell adnia a fa vagy füst típusát, ha egyedi füstjelleg érzékelhető.";
$styles_entry_text_32B = "A nevezőnek meg kell adnia egy alap sörstílust; az alapsörnek nem kell klasszikus stílusnak lennie. A nevezőnek meg kell adnia a fa vagy füst típusát, ha egyedi füstjelleg érzékelhető. A nevezőnek meg kell adnia a további összetevőket vagy eljárásokat, amelyek ezt különleges füstölt sörré teszik.";
$styles_entry_text_33A = "A nevezőnek meg kell adnia a felhasznált fa típusát és az elszenesítés mértékét (ha elszenesített). A nevezőnek meg kell adnia az alapstílust; az alapstílus lehet klasszikus BJCP stílus (azaz megnevezett alkategória) vagy általános sörtípus (pl. porter, brown ale). Ha szokatlan fát használtak, a nevezőnek rövid leírást kell adnia arról, milyen érzékszervi jellemzőket ad a fa a sörhöz.";
$styles_entry_text_33B = "A nevezőnek meg kell adnia az alkohol jelleget, a hordóra vonatkozó információkkal, ha azok relevánsak a végleges ízprofilhoz. A nevezőnek meg kell adnia az alapstílust; az alapstílus lehet klasszikus BJCP stílus (azaz megnevezett alkategória) vagy általános sörtípus (pl. porter, brown ale). Ha szokatlan fát vagy összetevőt használtak, a nevezőnek rövid leírást kell adnia arról, milyen érzékszervi jellemzőket adnak az összetevők a sörhöz.";
$styles_entry_text_34A = "A nevezőnek meg kell adnia a klónozott kereskedelmi sör nevét, a sör specifikációit (alapvető statisztikákat) és vagy rövid érzékszervi leírást, vagy a sör készítéséhez használt összetevők listáját. Ezen információ nélkül a sört nem ismerő bírálóknak nem lesz összehasonlítási alapjuk.";
$styles_entry_text_34B = "A nevezőnek meg kell adnia a kevert stílusokat. A nevező adhat további leírást a sör érzékszervi profiljáról vagy az eredményül kapott sör alapvető statisztikáiról.";
$styles_entry_text_34C = " A nevezőnek meg kell adnia a kísérleti sör különleges jellegét, beleértve azokat a különleges összetevőket vagy eljárásokat, amelyek miatt nem illik be máshová az útmutatóba. A nevezőnek meg kell adnia a sör alapvető statisztikáit és vagy rövid érzékszervi leírást, vagy a sör készítéséhez használt összetevők listáját. Ezen információ nélkül a bírálóknak nem lesz összehasonlítási alapjuk.";
$styles_entry_text_M1A = "Nevezési útmutató: A nevezőknek meg kell adniuk a szénsavszintet és az erősséget. Az édesség ebben a kategóriában SZÁRAZ. A nevezők megadhatják a méz fajtáit.";
$styles_entry_text_M1B = "A nevezőknek meg kell adniuk a szénsavszintet és az erősséget. Az édesség ebben a kategóriában FÉLSZÁRAZ. A nevezők megadhatják a méz fajtáit.";
$styles_entry_text_M1C = "A nevezőknek meg KELL adniuk a szénsavszintet és az erősséget. Az édesség ebben a kategóriában ÉDES. A nevezők megadhatják a méz fajtáit.";
$styles_entry_text_M2A = "A nevezőknek meg kell adniuk a szénsavszintet, erősséget és édességet. A nevezők megadhatják a méz fajtáit. A nevezők megadhatják a felhasznált almafajtákat; megadásuk esetén fajtajelleg várható. A viszonylag alacsony méztartalmú termékeket jobb Specialty Cider kategóriában nevezni. Fűszerezett cysert Gyümölcsös és fűszeres mézbor kategóriában kell nevezni. Más gyümölcsöt tartalmazó cysert Melomel kategóriában kell nevezni. További összetevőket tartalmazó cysert Kísérleti mézbor kategóriában kell nevezni.";
$styles_entry_text_M2B = "A nevezőknek meg kell adniuk a szénsavszintet, erősséget és édességet. A nevezők megadhatják a méz fajtáit. A nevezők megadhatják a felhasznált szőlőfajtákat; megadásuk esetén fajtajelleg várható. Fűszerezett pymentet (hippocras) Gyümölcsös és fűszeres mézbor kategóriában kell nevezni. Más gyümölcsöt tartalmazó pymentet Melomel kategóriában kell nevezni. Más összetevőket tartalmazó pymentet Kísérleti mézbor kategóriában kell nevezni.";
$styles_entry_text_M2C = "A nevezőknek meg kell adniuk a szénsavszintet, erősséget és édességet. A nevezők megadhatják a méz fajtáit. A nevezőknek meg kell adniuk a felhasznált gyümölcsfajtákat. Bogyósgyümölcsöket és nem bogyós gyümölcsöket (beleértve almát és szőlőt) egyaránt tartalmazó mézbort Melomel kategóriában kell nevezni. Fűszerezett bogyós mézbort Gyümölcsös és fűszeres mézbor kategóriában kell nevezni. Más összetevőket tartalmazó bogyós mézbort Kísérleti mézbor kategóriában kell nevezni.";
$styles_entry_text_M2D = "A nevezőknek meg kell adniuk a szénsavszintet, erősséget és édességet. A nevezők megadhatják a méz fajtáit. A nevezőknek meg kell adniuk a felhasznált gyümölcsfajtákat. Fűszerezett csonthéjas gyümölcsös mézbort Gyümölcsös és fűszeres mézbor kategóriában kell nevezni. Nem csonthéjas gyümölcsöt is tartalmazó csonthéjas gyümölcsös mézbort Melomel kategóriában kell nevezni. Más összetevőket tartalmazó csonthéjas gyümölcsös mézbort Kísérleti mézbor kategóriában kell nevezni.";
$styles_entry_text_M2E = "A nevezőknek meg kell adniuk a szénsavszintet, erősséget és édességet. A nevezők megadhatják a méz fajtáit. A nevezőknek meg kell adniuk a felhasznált gyümölcsfajtákat. Fűszerezett melomelt Gyümölcsös és fűszeres mézbor kategóriában kell nevezni. Más összetevőket tartalmazó melomelt Kísérleti mézbor kategóriában kell nevezni. Kizárólag almát vagy szőlőt gyümölcsforrásként tartalmazó melomeleket Cyser, illetve Pyment kategóriában kell nevezni. Almát vagy szőlőt más gyümölccsel együtt tartalmazó melomeleket ebben a kategóriában kell nevezni, nem a Kísérleti kategóriában.";
$styles_entry_text_M3A = "A nevezőknek meg kell adniuk a szénsavszintet, erősséget és édességet. A nevezők megadhatják a méz fajtáit. A nevezőknek meg kell adniuk a felhasznált fűszerek típusát (bár az ismert fűszerkeverékekre hivatkozhatnak közismert nevükön, pl. almás pite fűszerkeverék). A nevezőknek meg kell adniuk a felhasznált gyümölcsök típusát. Ha csak fűszerkombinációkat használnak, Fűszeres, gyógynövényes vagy zöldséges mézbor kategóriában kell nevezni. Ha csak gyümölcskombinációkat használnak, Melomel kategóriában kell nevezni. Ha más típusú összetevőket használnak, Kísérleti mézbor kategóriában kell nevezni.";
$styles_entry_text_M3B = "A nevezőknek meg KELL adniuk a szénsavszintet, erősséget és édességet. A nevezők megadhatják a méz fajtáit. A nevezőknek meg KELL adniuk a felhasznált fűszerek típusát (bár az ismert fűszerkeverékekre hivatkozhatnak közismert nevükön, pl. almás pite fűszerkeverék).";
$styles_entry_text_M4A = "A nevezőknek meg KELL adniuk a szénsavszintet, erősséget és édességet. A nevezők megadhatják a méz fajtáit. A nevezők megadhatják az alap sörstílust vagy a felhasznált maltatípusokat. A viszonylag alacsony méztartalmú termékeket a Fűszerezett sör kategóriában, Mézes sörként kell nevezni.";
$styles_entry_text_M4B = "A nevezőknek meg KELL adniuk a szénsavszintet, erősséget és édességet. A nevezők megadhatják a méz fajtáit. A nevezőknek meg KELL adniuk a mézbor különleges jellegét, leírást biztosítva a bírálók számára, ha ilyen leírás nem áll rendelkezésre a BJCP-től.";
$styles_entry_text_M4C = "A nevezőknek meg KELL adniuk a szénsavszintet, erősséget és édességet. A nevezők megadhatják a méz fajtáit. A nevezőknek meg KELL adniuk a mézbor különleges jellegét, legyen az meglévő stílusok kombinációja, kísérleti mézbor, vagy más alkotás. Bármely azonosítható jelleget adó különleges összetevő megadható.";
$styles_entry_text_C1E = "A nevezőknek meg KELL adniuk a szénsavszintet (3 szint). A nevezőknek meg KELL adniuk az édességet (5 kategória). A nevezőknek meg KELL adniuk a felhasznált körtefajta(ák)at.";
$styles_entry_text_C2A = "A nevezőknek meg KELL adniuk, hogy az almabor hordóban erjesztett vagy érlelt volt-e. A nevezőknek meg KELL adniuk a szénsavszintet (3 szint). A nevezőknek meg KELL adniuk az édességet (5 szint).";
$styles_entry_text_C2B = "A nevezőknek meg KELL adniuk a szénsavszintet (3 szint). A nevezőknek meg KELL adniuk az édességet (5 kategória). A nevezőknek meg KELL adniuk az összes hozzáadott gyümölcsöt és/vagy gyümölcslevet.";
$styles_entry_text_C2C = "A nevezőknek meg KELL adniuk a szénsavszintet (3 szint). A nevezőknek meg KELL adniuk az édességet (5 szint).";
$styles_entry_text_C2D = "A nevezőknek meg KELL adniuk a kiindulási fajsúlyt, a végső fajsúlyt vagy maradék cukrot, és az alkoholszintet. A nevezőknek meg KELL adniuk a szénsavszintet (3 szint).";
$styles_entry_text_C2E = "A nevezőknek meg KELL adniuk a szénsavszintet (3 szint). A nevezőknek meg KELL adniuk az édességet (5 kategória). A nevezőknek meg KELL adniuk az összes hozzáadott növényi összetevőt. Komló használata esetén a nevezőnek meg kell adnia a felhasznált fajta/fajtákat.";
$styles_entry_text_C2F = "A nevezőknek meg KELL adniuk az összes összetevőt. A nevezőknek meg KELL adniuk a szénsavszintet (3 szint). A nevezőknek meg KELL adniuk az édességet (5 kategória).";

/**
 * ------------------------------------------
 * User Edit Email
 * ------------------------------------------
 */
$user_text_000 = "Új e-mail cím szükséges, és érvényes formátumban kell lennie.";
$user_text_001 = "Adja meg a régi jelszót.";
$user_text_002 = "Adja meg az új jelszót.";
$user_text_003 = "Kérjük, jelölje be ezt a négyzetet, ha folytatni kívánja az e-mail cím módosítását.";

/**
 * ------------------------------------------
 * Volunteers
 * ------------------------------------------
 */
$volunteers_text_000 = "Ha már regisztrált,";
$volunteers_text_001 = "majd válassza a <em>Fiók szerkesztése</em> lehetőséget a Fiókom menüből, amelyet a";
$volunteers_text_002 = "ikon jelöl a felső menüben";
$volunteers_text_003 = "és";
$volunteers_text_004 = "Ha <em>még nem</em> regisztrált és hajlandó bírálóként vagy felszolgálóként részt venni, kérjük, regisztráljon";
$volunteers_text_005 = "Mivel már regisztrált,";
$volunteers_text_006 = "nyissa meg fiókját";
$volunteers_text_007 = "hogy lássa, jelentkezett-e önkéntesként bírálónak vagy felszolgálónak";
$volunteers_text_008 = "Ha hajlandó bírálóként vagy felszolgálóként részt venni, kérjük, térjen vissza a regisztrációhoz a következő időpontban vagy azt követően:";
$volunteers_text_009 = "Ha szeretne önkéntesként a verseny személyzetének tagja lenni, kérjük, regisztráljon, vagy frissítse fiókját, jelezve, hogy a verseny személyzetének része szeretne lenni.";
$volunteers_text_010 = "";

/**
 * ------------------------------------------
 * Login
 * ------------------------------------------
 */
$login_text_000 = "Már be van jelentkezve.";
$login_text_001 = "A rendszerben nincs olyan e-mail cím, amely egyezne az Ön által megadottal.";
$login_text_002 = "Próbálja újra?";
$login_text_003 = "Regisztrálta már a fiókját?";
$login_text_004 = "Elfelejtette jelszavát?";
$login_text_005 = "Visszaállítás";
$login_text_006 = "Jelszava visszaállításához adja meg a regisztrációkor használt e-mail címet.";
$login_text_007 = "Ellenőrzés";
$login_text_008 = "Véletlenszerűen generált.";
$login_text_009 = "<strong>Nem elérhető.</strong> Fiókját egy adminisztrátor hozta létre, és a &quot;titkos válasz&quot; véletlenszerűen lett generálva. Kérjük, forduljon a webhely adminisztrátorához jelszava visszaállításához vagy megváltoztatásához.";
$login_text_010 = "Vagy használja az alábbi e-mail lehetőséget.";
$login_text_011 = "Az Ön biztonsági kérdése...";
$login_text_012 = "Ha nem kapta meg az e-mailt,";
$login_text_013 = "Egy e-mailt küldünk Önnek az ellenőrző kérdéssel és válasszal. Ellenőrizze a SPAM mappát is.";
$login_text_014 = "válassza itt az újraküldéshez a következő címre:";
$login_text_015 = "Ha nem emlékszik a biztonsági kérdésre adott válaszra, forduljon a verseny tisztségviselőjéhez vagy az oldal adminisztrátorához.";
$login_text_016 = "Küldés e-mailben ide:";

/**
 * ------------------------------------------
 * Winners
 * ------------------------------------------
 */
$winners_text_000 = "Ehhez az asztalhoz nem lettek győztesek megadva. Kérjük, látogasson vissza később.";
$winners_text_001 = "A győztes nevezések még nem kerültek közzétételre. Kérjük, látogasson vissza később.";
$winners_text_002 = "Az Ön által választott díjazási struktúra asztalok szerint díjaz. Válassza ki az asztal egészének díjazási helyezéseit alább.";
$winners_text_003 = "Az Ön által választott díjazási struktúra kategóriák szerint díjaz. Válassza ki az egyes összesített kategóriák díjazási helyezéseit alább (asztalonként több is lehet).";
$winners_text_004 = "Az Ön által választott díjazási struktúra alkategóriák szerint díjaz. Válassza ki az egyes alkategóriák díjazási helyezéseit alább (asztalonként több is lehet).";

/**
 * ------------------------------------------
 * Output
 * ------------------------------------------
 */
$output_text_000 = "Köszönjük, hogy részt vett a versenyünkön";
$output_text_001 = "Az alábbiakban összefoglaló látható a nevezéseiről, pontszámairól és helyezéseiről.";
$output_text_002 = "Összesítés a következőhöz:";
$output_text_003 = "nevezést bíráltak el";
$output_text_004 = "A pontozólapok nem voltak megfelelően előállíthatók. Kérjük, lépjen kapcsolatba a verseny szervezőivel.";
$output_text_005 = "Nem lettek bíráló/felszolgáló beosztások meghatározva";
$output_text_006 = "ennél a helyszínnél";
$output_text_007 = "Ha üres asztalkártyákat szeretne nyomtatni, zárja be ezt az ablakot, és válassza az <em>Asztalkártyák nyomtatása: Minden asztal</em> lehetőséget a <em>Jelentések</em> menüből.";
$output_text_008 = "Kérjük, ellenőrizze, hogy a BJCP bírálói azonosítója helyes-e. Ha nem helyes, vagy ha rendelkezik ilyennel és nincs feltüntetve, kérjük, adja meg az űrlapon.";
$output_text_009 = "Ha az Ön neve nem szerepel az alábbi listán, jelentkezzen be a csatolt ív(ek)en.";
$output_text_010 = "A bírálati jóváírás megkapásához kérjük, adja meg BJCP bírálói azonosítóját helyesen és olvashatóan.";
$output_text_011 = "Nem történtek beosztások.";
$output_text_012 = "Összes nevezés ennél a helyszínnél";
$output_text_013 = "Egyik résztvevő sem adott meg megjegyzést a szervezőknek.";
$output_text_014 = "Az alábbiakban a bírálók vagy felszolgálók által a szervezőknek írt megjegyzések találhatók.";
$output_text_015 = "Egyik résztvevő sem adott meg megjegyzést a szervezőknek.";
$output_text_016 = "Bírálat utáni nevezési leltár";
$output_text_017 = "Ha alább nem jelennek meg nevezések, az ennél az asztalnál lévő kóstolócsoportok nem lettek fordulókhoz rendelve.";
$output_text_018 = "Ha nevezések hiányoznak, nem minden nevezés lett kóstolócsoporthoz vagy fordulóhoz rendelve, VAGY más fordulóhoz lettek rendelve.";
$output_text_019 = "Ha alább nincsenek nevezések, ez az asztal nem lett fordulóhoz rendelve.";
$output_text_020 = "Nincsenek jogosult nevezések.";
$output_text_021 = "Nevezési szám / Bírálati szám puskáló";
$output_text_022 = "A jelen jelentésben szereplő pontok a hivatalos BJCP Jóváhagyott Verseny Követelményeiből származnak, amelyek elérhetők a következő címen:";
$output_text_023 = "tartalmazza a Best of Show-t";
$output_text_024 = "BJCP Pontjelentés";
$output_text_025 = "Összes elérhető személyzeti pont";
$output_text_026 = "Ebben a kategóriában lévő stílusok nem elfogadottak ezen a versenyen.";
$output_text_027 = "hivatkozás a Beer Judge Certification Program Stílusútmutatóra";

/**
 * ------------------------------------------
 * Maintenance
 * ------------------------------------------
 */
$maintenance_text_000 = "Az oldal adminisztrátora karbantartás céljából offline állapotba helyezte az oldalt.";
$maintenance_text_001 = "Kérjük, látogasson vissza később.";

/**
 * ------------------------------------------
 * Version 2.1.10-2.1.13 Additions
 * ------------------------------------------
 */
$label_entry_numbers = "Nevezési szám(ok)"; // For PayPal IPN Email
$label_status = "Állapot"; // For PayPal IPN Email
$label_transaction_id = "Tranzakció azonosító"; // For PayPal IPN Email
$label_organization = "Szervezet";
$label_ttb = "TTB szám";
$label_username = "Felhasználónév";
$label_from = "Feladó"; // For email headers
$label_to = "Címzett"; // For email headers
$label_varies = "Változó";
$label_styles_accepted = "Elfogadott stílusok";
$label_judging_styles = "Bírálati stílusok";
$label_select_club = "Válassza ki vagy keresse meg klubját";
$label_select_style = "Válassza ki vagy keresse meg nevezése stílusát";
$label_select_country = "Válassza ki vagy keresse meg országát";
$label_select_dropoff = "Válassza ki a nevezés kézbesítési módját";
$label_club_enter = "Klubnév megadása";
$label_secret_05 = "Mi az anyai nagyanyja leánykori neve?";
$label_secret_06 = "Mi volt az első barátnője vagy barátja keresztneve?";
$label_secret_07 = "Mi volt az első járműve márkája és típusa?";
$label_secret_08 = "Mi volt a harmadik osztályos tanítója vezetékneve?";
$label_secret_09 = "Melyik városban ismerte meg a párját?";
$label_secret_10 = "Mi volt a legjobb barátja keresztneve hatodik osztályban?";
$label_secret_11 = "Mi a kedvenc zenei előadója vagy együttese neve?";
$label_secret_12 = "Mi volt a gyerekkori beceneve?";
$label_secret_13 = "Mi annak a tanárnak a vezetékneve, akitől az első elégtelen jegyét kapta?";
$label_secret_14 = "Mi a kedvenc gyerekkori barátja neve?";
$label_secret_15 = "Melyik városban ismerkedtek meg a szülei?";
$label_secret_16 = "Melyik gyerekkori telefonszámra emlékszik leginkább, körzetszámmal együtt?";
$label_secret_17 = "Mi volt a kedvenc helye, ahová gyerekként szívesen járt?";
$label_secret_18 = "Hol volt, amikor az első csókját kapta?";
$label_secret_19 = "Melyik városban volt az első munkahelye?";
$label_secret_20 = "Melyik városban volt 2000 szilveszterén?";
$label_secret_21 = "Mi annak a főiskolának a neve, ahová jelentkezett, de nem járt oda?";
$label_secret_22 = "Mi volt annak a fiúnak vagy lánynak a keresztneve, akit először megcsókolt?";
$label_secret_23 = "Mi volt az első plüssállata, babája vagy akciófigurája neve?";
$label_secret_24 = "Melyik városban ismerte meg a házastársát/élettársát?";
$label_secret_25 = "Melyik utcában lakott első osztályban?";
$label_secret_26 = "Mekkora a terheletlen fecske repülési sebessége?";
$label_secret_27 = "Mi a kedvenc törölt tévéműsora neve?";
$label_pro = "Professzionális";
$label_amateur = "Amatőr";
$label_hosted = "Házigazda";
$label_edition = "Kiadás";
$label_pro_comp_edition = "Professzionális verseny kiadás";
$label_amateur_comp_edition = "Amatőr verseny kiadás";
$label_optional_info = "Opcionális információ";
$label_or = "Vagy";
$label_admin_payments = "Fizetések";
$label_payer = "Fizető";
$label_pay_with_paypal = "Fizetés PayPal-lal";
$label_submit = "Beküldés";
$label_id_verification_question = "Személyazonosság-ellenőrző kérdés";
$label_id_verification_answer = "Személyazonosság-ellenőrző válasz";
$label_server = "Szerver";
$label_password_reset = "Jelszó visszaállítás";
$label_id_verification_request = "Személyazonosság-ellenőrzési kérelem";
$label_new_password = "Új jelszó";
$label_confirm_password = "Jelszó megerősítése";
$label_with_token = "Tokennel";
$label_password_strength = "Jelszó erőssége";
$label_entry_shipping = "Nevezés szállítása";
$label_jump_to = "Ugrás ide...";
$label_top = "Tetejére";
$label_bjcp_cider = "Minősített almabor bíráló";
$header_text_112 = "Nincs elegendő jogosultsága ennek a műveletnek a végrehajtásához.";
$header_text_113 = "Csak a saját fiókadatait szerkesztheti.";
$header_text_114 = "Adminisztrátorként a felhasználók fiókadatait az Adminisztráció > Nevezések és résztvevők > Résztvevők kezelése menüponton keresztül módosíthatja.";
$header_text_115 = "Az eredmények közzé lettek téve.";
$header_text_116 = "Ha ésszerű időn belül nem kapja meg az e-mailt, ellenőrizze e-mail fiókja SPAM mappáját. Ha ott sincs, forduljon a verseny tisztségviselőjéhez vagy az oldal adminisztrátorához jelszava visszaállításához.";
if (!$mail_use_smtp) $header_text_116 .= " <strong>Tájékoztassa a verseny tisztségviselőjét, hogy az SMTP e-mail küldés nincs engedélyezve és/vagy nem működik megfelelően.</strong>";
$alert_text_082 = "Mivel bírálóként vagy felszolgálóként regisztrált, nem adhat hozzá nevezéseket a fiókjához. Csak szervezetek képviselői adhatnak hozzá nevezéseket fiókjaikhoz.";
$alert_text_083 = "Nevezések hozzáadása és szerkesztése nem elérhető.";
$alert_text_084 = "Adminisztrátorként hozzáadhat nevezést egy szervezet fiókjához a &quot;Nevezés hozzáadása...&quot; legördülő menü használatával az Adminisztráció &gt; Nevezések és résztvevők &gt; Nevezések kezelése oldalon.";
$alert_text_085 = "Nem tudja kinyomtatni egyetlen nevezés palackcímkéit sem, amíg a fizetés nincs megerősítve és a nevezés &quot;kifizetve&quot; státuszú nem lesz alább. Jelölje be a jelölőnégyzetet minden kifizett nevezésnél, majd válassza a &quot;Kiválasztott nevezések címkéinek nyomtatása&quot; gombot. Ez egy új lapon vagy ablakban nyitja meg a nyomtatásra kész címkéket.";
$brew_text_027 = "Ez a Brewers Association stílus megköveteli a sörfőző nyilatkozatát a termék különleges jellegéről. Lásd a <a href=\"https://www.brewersassociation.org/resources/brewers-association-beer-style-guidelines/\" target=\"_blank\">BA Stílusútmutatót</a> a konkrét útmutatásért.";
$brew_text_028 = "***NEM KÖTELEZŐ*** Adjon meg itt olyan információkat, amelyeket a stílusútmutató olyan jellemzőként részletez, amelyet megadhat.";
$brew_text_029 = "Adminisztrátori szerkesztés letiltva. Profilja személyes profilnak minősül, nem szervezeti profilnak, ezért nem jogosult nevezések hozzáadására. Szervezet nevében történő nevezés hozzáadásához nyissa meg a Nevezések kezelése listát, és válasszon szervezetet a &quot;Nevezés hozzáadása...&quot; legördülő menüből.";
$brew_text_030 = "tej / laktóz";
$brew_text_031 = "tojás";
$brew_text_032 = "hal";
$brew_text_033 = "rákfélék";
$brew_text_034 = "diófélék";
$brew_text_035 = "földimogyoró";
$brew_text_036 = "búza";
$brew_text_037 = "szójabab";
$brew_text_038 = "Tartalmaz ez a nevezés lehetséges élelmiszer-allergéneket? Gyakori élelmiszer-allergének közé tartozik a tej (beleértve a laktózt), tojás, hal, rákfélék, diófélék, földimogyoró, búza, szójabab stb. Nem sör stílusok esetén adja meg a glutént allergénként, ha a forrás erjeszthető anyag tartalmazhat (pl. árpa-, búza- vagy rozsmaláta), vagy ha sörélesztőt használtak.";
$brew_text_039 = "Kérjük, adja meg az összes lehetséges allergén(eke)t";
$brewer_text_022 = "A nevezéseinek hozzáadásakor lehetősége lesz társfőzőt megjelölni.";
$brewer_text_023 = "Válassza a &quot;Nincs&quot; lehetőséget, ha nem tartozik klubhoz. Válassza az &quot;Egyéb&quot; lehetőséget, ha klubja nem szerepel a listán - <strong>használja a keresőmezőt</strong>.";
$brewer_text_024 = "Kérjük, adja meg a keresztnevét.";
$brewer_text_025 = "Kérjük, adja meg a vezetéknevét.";
$brewer_text_026 = "Kérjük, adja meg a telefonszámát.";
$brewer_text_027 = "Kérjük, adja meg a címét.";
$brewer_text_028 = "Kérjük, adja meg a városát.";
$brewer_text_029 = "Kérjük, adja meg az államát vagy tartományát.";
$brewer_text_030 = "Kérjük, adja meg az irányítószámát.";
$brewer_text_031 = "Kérjük, válassza ki az országát.";
$brewer_text_032 = "Kérjük, adja meg a szervezet nevét.";
$brewer_text_033 = "Kérjük, válasszon biztonsági kérdést.";
$brewer_text_034 = "Kérjük, adjon választ a biztonsági kérdésre.";
$brewer_text_035 = "Letette a BJCP almabor bírálói vizsgát?";
$entry_info_text_047 = "Ha egy stílus neve hiperhivatkozásként jelenik meg, az adott stílusnak speciális nevezési követelményei vannak. Válassza ki vagy koppintson a névre a Brewers Association stílusok eléréséhez a honlapjukon.";
$brewer_entries_text_016 = "A megadott stílus NEM elfogadott";
$brewer_entries_text_017 = "A nevezések nem jelennek meg beérkezettként, amíg a verseny személyzete nem jelöli meg őket a rendszerben. Ez jellemzően AZUTÁN történik, hogy az összes nevezés összegyűjtésre került az összes leadási és szállítási helyszínről, és rendezésre került.";
$brewer_entries_text_018 = "Nem tudja kinyomtatni ennek a nevezésnek a palackcímkéit, amíg nem lett kifizetettként megjelölve.";
$brewer_entries_text_019 = "A nevezési dokumentumok nyomtatása jelenleg nem elérhető.";
$brewer_entries_text_020 = "Ennek a nevezésnek a szerkesztése nem elérhető. Ennek számos oka lehet (pl. lejárt a szerkesztési határidő, vagy a nevezés már beérkezettként lett megjelölve stb.). Ha szerkeszteni szeretné ezt a nevezést, forduljon a verseny tisztségviselőjéhez.";
if (SINGLE) $brewer_info_000 = "Üdvözöljük";
else $brewer_info_000 = "Köszönjük, hogy részt vesz a";
$brewer_info_001 = "Fiókja adatai utoljára frissítve:";
$brewer_info_002 = "Szánjon egy percet a <a href=\"#entries\">nevezései áttekintésére</a>";
$brewer_info_003 = "fizesse ki a nevezési díjakat</a>";
$brewer_info_004 = "nevezésenként";
$brewer_info_005 = "Az American Homebrewers Association (AHA) tagság szükséges, ha az egyik nevezése kiválasztásra kerül a Great American Beer Festival Pro-Am versenyre.";
$brewer_info_006 = "Szállítási címkék nyomtatása a palackos doboz(ok)hoz.";
$brewer_info_007 = "Szállítási címkék nyomtatása";
$brewer_info_008 = "Ön már be lett osztva egy asztalhoz mint";
$brewer_info_009 = "Ha módosítani szeretné elérhetőségét és/vagy visszavonni szerepét, forduljon a verseny szervezőjéhez vagy a bíráló koordinátorhoz.";
$brewer_info_010 = "Ön már be lett osztva mint";
$brewer_info_011 = "vagy";
$brewer_info_012 = "Bírálati pontozólap címkék nyomtatása ";
$pay_text_030 = "Az alábbi &quot;Megértettem&quot; gomb kiválasztásával átirányítjuk a PayPal-ra a fizetés elvégzéséhez. Miután <strong>befejezte</strong> a fizetést, a PayPal visszairányítja erre az oldalra, és e-mailben küld Önnek egy nyugtát a tranzakcióról. <strong>Ha a fizetés sikeres volt, a fizetési állapota automatikusan frissül. Kérjük, vegye figyelembe, hogy a fizetési állapot frissülése néhány percet igénybe vehet.</strong>";
$pay_text_031 = "Elhagyja ezt az oldalt";
$pay_text_032 = "Fizetés nem szükséges. Köszönjük!";
$pay_text_033 = "Kifizetetlen nevezései vannak. Válassza ki vagy koppintson a nevezési díjak kifizetéséhez.";
$register_text_035 = "A szervezete nevén túl megadott információk kizárólag nyilvántartási és kapcsolattartási célokat szolgálnak.";
$register_text_036 = "A versenyre való nevezés feltétele ezen információk megadása, beleértve a kapcsolattartó e-mail címét és telefonszámát. Szervezete neve megjelenítésre kerülhet, amennyiben valamelyik nevezése helyezést ér el, de egyéb információ nem kerül nyilvánosságra.";
$register_text_037 = "Regisztrációs visszaigazolás";
$register_text_038 = "Egy adminisztrátor regisztrálta Önt. Az alábbiakban a megadott adatok visszaigazolása található:";
$register_text_039 = "Köszönjük, hogy regisztrált. Az alábbiakban az Ön által megadott adatok visszaigazolása található:";
$register_text_040 = "Ha a fenti adatok bármelyike helytelen,";
$register_text_041 = "jelentkezzen be fiókjába";
$register_text_042 = "és végezze el a szükséges módosításokat. Sok sikert a versenyhez!";
$register_text_043 = "Kérjük, ne válaszoljon erre az e-mailre, mivel automatikusan generálódott. A feladó fiók nem aktív és nem monitorozott.";
$register_text_044 = "Kérjük, adja meg a szervezet nevét.";
$register_text_045 = "Adja meg a sörfőzde, söröző stb. nevét. Ellenőrizze a verseny információkat az elfogadott italtípusokról.";
$register_text_046 = "Csak egyesült államokbeli szervezetek számára.";
$user_text_004 = "Erősebb jelszó érdekében használjon kis- és nagybetűket, számokat és speciális karaktereket.";
$user_text_005 = "Jelenlegi e-mail címe:";
$login_text_017 = "Biztonsági kérdés válaszának küldése e-mailben";
$login_text_018 = "A felhasználónév (e-mail cím) megadása kötelező.";
$login_text_019 = "A jelszó megadása kötelező.";
$login_text_020 = "A megadott token érvénytelen vagy már felhasználásra került. Kérjük, használja újra a jelszó visszaállítás funkciót egy új token generálásához.";
$login_text_021 = "A megadott token lejárt. Kérjük, használja újra a jelszó visszaállítás funkciót egy új token generálásához.";
$login_text_022 = "A megadott e-mail cím nem tartozik a megadott tokenhez. Kérjük, próbálja újra.";
$login_text_023 = "A jelszavak nem egyeznek. Kérjük, próbálja újra.";
$login_text_024 = "A megerősítő jelszó megadása kötelező.";
$login_text_025 = $login_text_004;
$login_text_026 = "Adja meg a fiókjához tartozó e-mail címet és az új jelszót alább.";
$login_text_027 = "Jelszava sikeresen visszaállításra került. Most már bejelentkezhet az új jelszóval.";
$winners_text_005 = "A Best of Show győztes(ek) még nem kerültek közzétételre. Kérjük, látogasson vissza később.";
$paypal_response_text_000 = "A fizetés megtörtént. A tranzakció részletei az alábbiakban találhatók.";
$paypal_response_text_001 = "Kérjük, vegye figyelembe, hogy hivatalos visszaigazolást kap a PayPal-tól az alább feltüntetett e-mail címre.";
$paypal_response_text_002 = "Sok sikert a versenyhez!";
$paypal_response_text_003 = "Kérjük, ne válaszoljon erre az e-mailre, mivel automatikusan generálódott. A feladó fiók nem aktív és nem monitorozott.";
$paypal_response_text_004 = "A PayPal feldolgozta a tranzakcióját.";
$paypal_response_text_005 = "A PayPal fizetés állapota:";
$paypal_response_text_006 = "A PayPal válasza &quot;érvénytelen&quot; volt. Kérjük, próbálja meg újra a fizetést.";
$paypal_response_text_007 = "Kérdés esetén forduljon a verseny szervezőjéhez.";
$paypal_response_text_008 = "Érvénytelen PayPal fizetés";
$paypal_response_text_009 = "PayPal fizetés";
$pwd_email_reset_text_000 = "Kérelem érkezett a fiók ellenőrzésére a";
$pwd_email_reset_text_001 = "weboldalon a személyazonosság-ellenőrzési e-mail funkció használatával. Ha nem Ön kezdeményezte ezt, kérjük, forduljon a verseny szervezőjéhez.";
$pwd_email_reset_text_002 = "A válasz megkülönbözteti a kis- és nagybetűket.";
$pwd_email_reset_text_003 = "Kérelem érkezett a jelszó megváltoztatására a";
$pwd_email_reset_text_004 = "weboldalon. Ha nem Ön kezdeményezte ezt, ne aggódjon. Jelszava nem állítható vissza az alábbi hivatkozás nélkül.";
$pwd_email_reset_text_005 = "Jelszava visszaállításához válassza az alábbi hivatkozást, vagy másolja be a böngészőjébe.";
$best_brewer_text_000 = "részt vevő sörfőzők";
$best_brewer_text_001 = "DM";
$best_brewer_text_002 = "A pontszámok és a holtversenyt eldöntő szempontok a <a href=\"#\" data-bs-toggle=\"modal\" data-bs-target=\"#scoreMethod\">pontozási módszertan</a> szerint lettek alkalmazva. A megjelenített számok százados pontossággal vannak kerekítve. Vigye az egeret a kérdőjel ikonra (<i class=\"fa fa-question-circle\"></i>), vagy koppintson rá a tényleges számított érték megtekintéséhez.";
$best_brewer_text_003 = "Pontozási módszertan";
$best_brewer_text_004 = "Minden helyezett nevezés a következő pontokat kapja:";
$best_brewer_text_005 = "A következő holtversenyt eldöntő szempontok kerültek alkalmazásra, prioritási sorrendben:";
$best_brewer_text_006 = "Az első, második és harmadik helyezések legmagasabb összesített száma.";
$best_brewer_text_007 = "Az első, második, harmadik, negyedik (ha alkalmazandó) és dicséretes említés helyezések legmagasabb összesített száma.";
$best_brewer_text_008 = "Az első helyezések legmagasabb száma.";
$best_brewer_text_009 = "A nevezések legalacsonyabb száma.";
$best_brewer_text_010 = "A legmagasabb minimális pontszám.";
$best_brewer_text_011 = "A legmagasabb maximális pontszám.";
$best_brewer_text_012 = "A legmagasabb átlagpontszám.";
$best_brewer_text_013 = "Nem használt.";
$best_brewer_text_014 = "részt vevő klubok";
$dropoff_qualifier_text_001 = "Kérjük, figyeljen az egyes leadási helyszíneknél megadott megjegyzésekre. Egyes leadási helyszíneknél korábbi határidők lehetnek, bizonyos órákban fogadják a nevezéseket, meghatározott személyeknél kell leadni a nevezéseket stb. <strong class=\"text-danger\">Minden nevező felelős a szervezők által az egyes leadási helyszínekhez megadott információk elolvasásáért.</strong>";
$brewer_text_036 = "Mivel az &quot;<em>Egyéb</em>&quot; lehetőséget választotta, kérjük, győződjön meg arról, hogy a megadott klub nem szerepel a listánkon valamilyen hasonló formában.";
$brewer_text_037 = "Előfordulhat például, hogy a klub rövidítését adta meg a teljes neve helyett.";
$brewer_text_038 = "A felhasználók közötti egységes klubnevek elengedhetetlenek a &quot;Legjobb klub&quot; számításokhoz, ha ez a verseny alkalmazza.";
$brewer_text_039 = "A korábban megadott klub nem egyezik a listánkon szereplővel.";
$brewer_text_040 = "Kérjük, válasszon a listából, vagy válassza az <em>Egyéb</em> lehetőséget és adja meg a klub nevét.";


/**
 * ------------------------------------------
 * Version 2.1.13 Additions
 * ------------------------------------------
 */
$entry_info_text_048 = "A megfelelő bírálat érdekében a nevezőnek további információt kell megadnia az italról.";
$entry_info_text_049 = "A megfelelő bírálat érdekében a nevezőnek meg kell adnia az ital erősségi szintjét.";
$entry_info_text_050 = "A megfelelő bírálat érdekében a nevezőnek meg kell adnia az ital szénsavszintjét.";
$entry_info_text_051 = "A megfelelő bírálat érdekében a nevezőnek meg kell adnia az ital édesség szintjét.";
$entry_info_text_052 = "Ha ebbe a kategóriába nevez, a nevezőnek további információt kell megadnia a pontos bírálat érdekében. Minél több információ, annál jobb.";
$output_text_028 = "A következő nevezések lehetséges allergéneket tartalmaznak - a résztvevők által megadva.";
$output_text_029 = "Egyik résztvevő sem adott meg allergén információt a nevezéseihez.";
$label_this_style = "Ez a stílus";
$label_notes = "Megjegyzések";
$label_possible_allergens = "Lehetséges allergének";
$label_please_choose = "Kérjük, válasszon";
$label_mead_cider_info = "Mézbor/Almabor információ";

/**
 * ------------------------------------------
 * Version 2.1.14 Additions
 * ------------------------------------------
 */
$label_winners = "Győztesek";
$label_unconfirmed_entries = "Megerősítetlen nevezések";
$label_recipe = "Recept";
$label_view = "Megtekintés";
$label_number_bottles = "Szükséges palackok száma nevezésenként";
$label_pro_am = "Pro-Am";
$pay_text_034 = "A fizetett nevezések korlátja elérte a maximumot - további nevezési fizetéseket nem fogadunk el.";
$bottle_labels_000 = "A címkék jelenleg nem generálhatók.";
$bottle_labels_001 = "A beküldés előtt feltétlenül ellenőrizze a verseny nevezésfogadási szabályait a címke felragasztási útmutatóval kapcsolatban!";
$bottle_labels_002 = "Általában átlátszó csomagolószalaggal rögzítik az egyes nevezések palackjára - a címkét teljesen fedje le.";
$bottle_labels_003 = "Általában gumiszalaggal rögzítik a címkéket az egyes nevezésekre.";
if (isset($_SESSION['jPrefsBottleNum'])) $bottle_labels_004 = "4 címke biztosított szívességből. Ez a verseny ".$_SESSION['jPrefsBottleNum']." palackot igényel nevezésenként. Előfordulhat, hogy több oldalt kell nyomtatnia a szükséges palackok számától függően.";
else $bottle_labels_004 = "4 címke biztosított szívességből. A felesleges címkéket dobja ki.";
$bottle_labels_005 = "Ha bármely elem hiányzik, zárja be ezt az ablakot és szerkessze a nevezést. Előfordulhat, hogy több oldalt kell nyomtatnia a szükséges palackok számától függően.";
$bottle_labels_006 = "A verseny személyzetének fenntartott hely.";
$bottle_labels_007 = "EZ A RECEPT ŰRLAP CSAK AZ ÖN NYILVÁNTARTÁSA SZÁMÁRA - kérjük, NE mellékelje a nevezési szállítmányhoz.";
$brew_text_040 = "A sörstílusoknál nem szükséges allergénként megadni a glutént; feltételezzük, hogy jelen lesz. A gluténmentes söröket a Gluténmentes sör kategóriában (BA) vagy az Alternatív gabona sör kategóriában (BJCP) kell nevezni.";
$brewer_text_041 = "Kapott már Pro-Am lehetőséget a közelgő Great American Beer Festival Pro-Am versenyre?";
$brewer_text_042 = "Ha már kapott Pro-Am lehetőséget, vagy bármely sörfőzde személyzetében dolgozott, kérjük, jelezze itt. Ez segíti a verseny személyzetét és a Pro-Am sörfőzde képviselőit (ha alkalmazandó erre a versenyre) a Pro-Am nevezések kiválasztásában olyan sörfőzők közül, akik még nem rendelkeznek ilyennel.";

/**
 * ------------------------------------------
 * Version 2.1.15 Additions
 * ------------------------------------------
 */
$label_submitting = "Beküldés folyamatban";
$label_additional_info = "További információval rendelkező nevezések";
$label_working = "Feldolgozás";
$output_text_030 = "Kérjük, várjon.";
$brewer_entries_text_021 = "Jelölje be a nevezéseket a palack-/doboz címkék nyomtatásához. Válassza a felső jelölőnégyzetet az oszlop összes négyzetének be- vagy kijelöléséhez.";
$brewer_entries_text_022 = "A fent bejelölt összes nevezés címkéjének nyomtatása";
$brewer_entries_text_023 = "A címkék új lapon vagy ablakban nyílnak meg.";
$brewer_entries_text_024 = "Kiválasztott nevezések címkéinek nyomtatása";

/**
 * ------------------------------------------
 * Version 2.1.18 Additions
 * ------------------------------------------
 */
$output_text_031 = "Nyomja meg az Esc billentyűt az elrejtéshez.";
$styles_entry_text_21X = "A nevezőnek meg KELL adnia az erősséget (session: 3,0-5,0%, standard: 5,0-7,5%, dupla: 7,5-9,5%).";
$styles_entry_text_PRX4 = "A nevezőnek meg kell adnia a felhasznált friss gyümölcs(ök) típusát.";

/**
 * ------------------------------------------
 * Version 2.1.19 Additions
 * ------------------------------------------
 */
$output_text_032 = "A nevezési szám csak azokat a nevezőket tükrözi, akik megadtak helyszínt a fiókprofiljukban. A tényleges nevezések száma lehet magasabb vagy alacsonyabb.";
$brewer_text_043 = "Vagy alkalmazásban áll, vagy állt-e valaha sörfőzde személyzetében? Ez magában foglalja a sörfőző pozíciókat, valamint a labortechnikusokat, pinceszemélyzetet, palackozó/dobozolósort stb. Jelenlegi és korábbi sörfőzde alkalmazottak nem jogosultak részt venni a Great American Beer Festival Pro-Am versenyen.";
$label_entrant_reg = "Nevezői regisztráció";
$sidebar_text_026 = "van a rendszerben a következő dátummal:";
$label_paid_entries = "Kifizetett nevezések";

/**
 * ------------------------------------------
 * Version 2.2.0 Additions
 * ------------------------------------------
 */
$alert_text_086 = "Az Internet Explorer nem támogatott a BCOE&M által - a funkciók nem jelennek meg megfelelően, és az élmény nem lesz optimális. Kérjük, frissítsen egy újabb böngészőre.";
$alert_text_087 = "Az optimális élmény és az összes funkció megfelelő működése érdekében kérjük, engedélyezze a JavaScriptet az oldal használatának folytatásához. Ellenkező esetben váratlan működés fordulhat elő.";
$alert_text_088 = "Az Díjátadó az eredmények közzététele után lesz nyilvánosan elérhető.";
$alert_text_089 = "Az archivált adatok nem elérhetők.";
$brewer_entries_text_025 = "Bírálói pontozólapok megtekintése vagy nyomtatása";
$brewer_info_013 = "Ön bírálóként lett beosztva.";
$brewer_info_014 = "A Bírálói irányítópultot a gomb segítségével érheti el az Önhöz rendelt nevezések értékeléséhez.";
$contact_text_004 = "A verseny szervezői nem adtak meg kapcsolattartókat.";
$label_thank_you = "Köszönjük";
$label_congrats_winners = "Gratulálunk minden érmesnek";
$label_placing_entries = "Helyezett nevezések";
$label_by_the_numbers = "Számokban";
$label_launch_pres = "Díjátadó indítása";
$label_entrants = "Nevezők";
$label_judging_dashboard = "Bírálói irányítópult";
$label_table_assignments = "Asztal beosztások";
$label_table = "Asztal";
$label_edit = "Szerkesztés";
$label_add = "Hozzáadás";
$label_disabled = "Letiltva";
$label_judging_scoresheet = "Bírálati pontozólap";
$label_checklist_version = "Ellenőrzőlista verzió";
$label_classic_version = "Klasszikus verzió";
$label_structured_version = "Strukturált verzió";
$label_submit_evaluation = "Értékelés beküldése";
$label_edit_evaluation = "Értékelés szerkesztése";
$label_your_score = "Az Ön pontszáma";
$label_your_assigned_score = "Az Ön által megadott konszenzusos pontszám";
$label_assigned_score = "Konszenzusos pontszám";
$label_accepted_score = "Hivatalos elfogadott pontszám";
$label_recorded_scores = "Megadott konszenzusos pontszámok";
$label_go = "Indítás";
$label_go_back = "Vissza";
$label_na = "N/A";
$label_evals_submitted = "Beküldött értékelések";
$label_evaluations = "Nevezési értékelések";
$label_submitted_by = "Beküldte";
$label_attention = "Figyelem!";
$label_unassigned_eval = "Nem beosztott értékelések";
$label_head_judge = "Főbíráló";
$label_lead_judge = "Vezető bíráló";
$label_mini_bos_judge = "Mini-BOS bíráló";
$label_view_my_eval = "Saját értékelésem megtekintése";
$label_view_other_judge_eval = "Másik bíráló értékelésének megtekintése";
$label_place_awarded = "Odaítélt helyezés";
$label_honorable_mention = "Dicséret";
$label_places_awarded_table = "Ennél az asztalnál odaítélt helyezések";
$label_places_awarded_duplicate = "Duplikált helyezések ennél az asztalnál";
$evaluation_info_000 = "Az Önhöz rendelt asztalok és kóstolócsoportok nevezési állománya alább részletezve található.";
$evaluation_info_001 = "Ez a verseny sorban állásos bírálást alkalmaz. Ha egynél több bírálópár van az asztalánál, a megállapított sorrendben következő nevezést értékelje.";
$evaluation_info_002 = "A pontos és zökkenőmentes verseny érdekében Ön és bírálótársa(i) KIZÁRÓLAG az asztaluknál lévő, még nem értékelt nevezéseket bírálják. Kérdés esetén forduljon a szervezőhöz vagy a bírálókoordinátorhoz.";
$evaluation_info_003 = "Egy oldal-adminisztrátor végleges jóváhagyására vár.";
$evaluation_info_004 = "Az Ön konszenzusos pontszáma rögzítésre került.";
$evaluation_info_005 = "Ez a nevezés <strong>nem része</strong> az Önhöz rendelt kóstolócsoportnak.";
$evaluation_info_006 = "Szükség szerint szerkessze.";
$evaluation_info_007 = "Értékelés rögzítéséhez válasszon az alábbi, kék &quot;Hozzáadás&quot; gombbal rendelkező nevezések közül.";
$evaluation_info_008 = "Értékelésének rögzítéséhez válassza ki a nevezés megfelelő Hozzáadás gombját. Csak a múltbeli és aktuális bírálási időszakok asztalai érhetők el.";
$evaluation_info_009 = "Ön bírálóként lett kijelölve, de a rendszerben nem lett asztal(ok)hoz vagy kóstolócsoport(ok)hoz rendelve. Kérjük, egyeztessen a szervezővel vagy a bírálókoordinátorral.";
$evaluation_info_010 = "Ez a nevezés az Önhöz rendelt kóstolócsoport része.";
$evaluation_info_011 = "Értékelés hozzáadása egy Önhöz nem rendelt nevezéshez.";
$evaluation_info_012 = "Csak akkor használja, ha olyan nevezés értékelésére kérik, amely a rendszerben nem lett Önhöz rendelve.";
$evaluation_info_013 = "A nevezés nem található.";
$evaluation_info_014 = "Kérjük, ellenőrizze a hatjegyű nevezési számot, és próbálja újra.";
$evaluation_info_015 = "Győződjön meg róla, hogy a szám 6 számjegy hosszú.";
$evaluation_info_016 = "Nem érkeztek be értékelések.";
$evaluation_info_017 = "A bírálók által megadott konszenzusos pontszámok nem egyeznek.";
$evaluation_info_018 = "A következő nevezéseknél ellenőrzés szükséges:";
$evaluation_info_019 = "A következő nevezésekhez csak egy értékelés érkezett:";
$evaluation_info_020 = "Az Ön bírálói kezelőfelülete elérhető lesz"; // Írásjelek szándékosan kihagyva
$evaluation_info_021 = "az Önhöz rendelt nevezések értékelésének hozzáadásához"; // Írásjelek szándékosan kihagyva
$evaluation_info_022 = "A bírálás és az értékelések beküldése lezárult.";
$evaluation_info_023 = "Ha bármilyen kérdése van, forduljon a verseny szervezőjéhez vagy a bírálókoordinátorhoz.";
$evaluation_info_024 = "Ön a következő asztalokhoz lett rendelve. <strong>Az egyes asztalok nevezési listái csak a <u>múltbeli</u> és <u>aktuális</u> bírálási időszakokhoz jelennek meg.</strong>";
$evaluation_info_025 = "Az asztalhoz rendelt bírálók:";
$evaluation_info_026 = "A bírálók által megadott összes konszenzusos pontszám egyezik.";
$evaluation_info_027 = "Az Ön által befejezett, vagy egy adminisztrátor által az Ön nevében rögzített nevezések, amelyek nem lettek Önhöz rendelve a rendszerben.";
$evaluation_info_028 = "A bírálási időszak véget ért.";
$evaluation_info_029 = "A következő asztaloknál duplikált helyezések lettek odaítélve:";
$evaluation_info_030 = "Az adminisztrátoroknak kell rögzíteniük a fennmaradó helyezett nevezéseket.";
$evaluation_info_031 = "értékelést adtak hozzá a bírálók";
$evaluation_info_032 = "Egy bíráló több értékelést küldött be egyetlen nevezéshez.";
$evaluation_info_033 = "Bár ez szokatlan eset, duplikált értékelés beküldése előfordulhat kapcsolódási problémák stb. miatt. Minden bírálótól egyetlen rögzített értékelést kell hivatalosan elfogadni - az összes többit törölni kell a nevezők esetleges félrevezetésének elkerülése érdekében.";
$evaluation_info_034 = "Különleges típusú stílusok értékelésekor használja ezt a sort az egyedi jellemzők, például gyümölcs, fűszer, erjeszthető alapanyag, savasság stb. megjegyzéséhez.";
$evaluation_info_035 = "Adjon észrevételeket a stílusról, a receptről, a folyamatról és az ivási élményről. Tegyen hasznos javaslatokat a sörfőzőnek.";
if (isset($_SESSION['jPrefsScoreDispMax'])) $evaluation_info_036 = "Egy vagy több bírálói pontszám az elfogadható tartományon kívül esik. Az elfogadható tartomány ".$_SESSION['jPrefsScoreDispMax']. " pont vagy kevesebb.";
$evaluation_info_037 = "Úgy tűnik, hogy ennél az asztalnál minden kóstolócsoport befejeződött.";
$evaluation_info_038 = "Főbírálóként az Ön felelőssége, hogy ellenőrizze az egyes nevezések konszenzusos pontszámainak egyezését, megbizonyosodjon arról, hogy az összes bírálói pontszám a megfelelő tartományon belül van, és helyezéseket ítéljen oda a kijelölt nevezéseknek.";
$evaluation_info_039 = "Nevezések ennél az asztalnál:";
$evaluation_info_040 = "Pontozott nevezések ennél az asztalnál:";
$evaluation_info_041 = "Pontozott nevezések az Ön kóstolócsoportjában:";
$evaluation_info_042 = "Az Ön által pontozott nevezések:";
$evaluation_info_043 = "Értékeléssel rendelkező bírálók ennél az asztalnál:";
$label_submitted = "Beküldve";
$label_ordinal_position = "Sorrendi pozíció a kóstolócsoportban";
$label_alcoholic = "Alkoholos";
$descr_alcoholic = "Az etanol és magasabb alkoholok illata, íze és melegítő hatása. Néha &quot;forrónak&quot; írják le.";
$descr_alcoholic_mead = "Az etanol hatása. Melegítő. Forró.";
$label_metallic = "Fémes";
$descr_metallic = "Bádog-, érme-, réz-, vas- vagy vérszerű íz.";
$label_oxidized = "Oxidált";
$descr_oxidized = "Állott, boros, kartonos, papíros vagy sherryszerű aromák és ízek bármely kombinációja. Állott.";
$descr_oxidized_cider = "Állottság, sherry, mazsola vagy zúzott gyümölcs illata/íze.";
$label_phenolic = "Fenolos";
$descr_phenolic = "Fűszeres (szegfűszeg, bors), füstös, műanyagos, ragtapasz-szerű és/vagy gyógyszeres (klórfenolos).";
$label_vegetal = "Zöldséges";
$descr_vegetal = "Főtt, konzervált vagy romlott zöldség illata és íze (káposzta, hagyma, zeller, spárga stb.).";
$label_astringent = "Összehúzó";
$descr_astringent = "Összehúzó, tartós kesernyésség és/vagy szárazság az utóízben; durva gabonásság; pelyvásság.";
$descr_astringent_cider = "Szájszárító érzés, hasonló a teafilter rágásához. Ha jelen van, egyensúlyban kell lennie.";
$label_acetaldehyde = "Acetaldehid";
$descr_acetaldehyde = "Zöld almára emlékeztető illat és íz.";
$label_diacetyl = "Diacetil";
$descr_diacetyl = "Művajas, vajkaramellás vagy toffee-s illat és íz. Néha a nyelven simaságként érzékelhető.";
$descr_diacetyl_cider = "Vajas vagy vajkaramellás illat vagy íz.";
$label_dms = "DMS (dimetil-szulfid)";
$descr_dms = "Alacsony szinteken édes, főtt vagy konzerv kukoricára emlékeztető illat és íz.";
$label_estery = "Észteres";
$descr_estery = "Bármely észter illata és/vagy íze (gyümölcsök, gyümölcsízesítők vagy rózsa).";
$label_grassy = "Füves";
$descr_grassy = "Frissen vágott fű vagy zöld levelek illata/íze.";
$label_light_struck = "Fénykárosodott";
$descr_light_struck = "Görényéhez hasonló illat.";
$label_musty = "Dohos";
$descr_musty = "Állott, dohos vagy penészes illatok/ízek.";
$label_solvent = "Oldószeres";
$descr_solvent = "Magasabb alkoholok (kozmás alkoholok) illata és íze. Acetonhoz vagy lakkhígítóhoz hasonló illat.";
$label_sour_acidic = "Savanyú/Savas";
$descr_sour_acidic = "Fanyarság az illatban és ízben. Lehet éles és tiszta (tejsav), vagy ecetes (ecetsav).";
$label_sulfur = "Kén";
$descr_sulfur = "Záptojás vagy égő gyufa illata.";
$label_sulfury = "Kénes";
$label_yeasty = "Élesztős";
$descr_yeasty = "Kenyeres, kénes vagy élesztőszerű illat vagy íz.";
$label_acetified = "Ecetesedett (illékony savasság, VA)";
$descr_acetified = "Etil-acetát (oldószer, körömlakk) vagy ecetsav (ecet, éles a torok hátuljában).";
$label_acidic = "Savas";
$descr_acidic = "Savanyú-fanyar íz. Jellemzően a következő savak egyikéből: almasav, tejsav vagy citromsav. Egyensúlyban kell lennie.";
$descr_acidic_mead = "Tiszta, savanyú íz/illat alacsony pH-ból. Jellemzően a következő savak egyikéből: almasav, tejsav, glükonsav vagy citromsav.";
$label_bitter = "Keserű";
$descr_bitter = "Éles íz, amely magasabb szinteken kellemetlen.";
$label_farmyard = "Istállós";
$descr_farmyard = "Trágyaszerű (tehén vagy sertés) vagy istállós (lóistálló egy meleg napon).";
$label_fruity = "Gyümölcsös";
$descr_fruity = "Friss gyümölcsök illata és íze, ami egyes stílusokban megfelelő, másokban nem.";
$descr_fruity_mead = "Íz- és illat-észterek, amelyek gyakran a melomel gyümölcseiből származnak. A banán és ananász gyakran hibás íz.";
$label_mousy = "Egérszagú";
$descr_mousy = "Rágcsáló odújának/ketrecének szagát idéző íz.";
$label_oaky = "Tölgyes";
$descr_oaky = "Íz vagy illat, amely hosszabb idejű hordóban vagy faforgácson tartásból ered. &quot;Hordójelleg.&quot;";
$label_oily_ropy = "Olajos/Nyúlós";
$descr_oily_ropy = "Csillogás a megjelenésben, kellemetlen viszkózus jelleg, amely nyúlós jelleggé fejlődik.";
$label_spicy_smoky = "Fűszeres/Füstös";
$descr_spicy_smoky = "Fűszer, szegfűszeg, füstös, sonkás.";
$label_sulfide = "Szulfid";
$descr_sulfide = "Záptojás, erjesztési problémákból.";
$label_sulfite = "Szulfit";
$descr_sulfite = "Égő gyufa, túlzott/friss kénezésből.";
$label_sweet = "Édes";
$descr_sweet = "A cukor alapíze. Ha jelen van, egyensúlyban kell lennie.";
$label_thin = "Vékony";
$descr_thin = "Vizes. Hiányzik a test vagy a &quot;tartalom.&quot;";
$label_acetic = "Ecetes";
$descr_acetic = "Ecetes, ecetsavas, éles.";
$label_chemical = "Vegyszeres";
$descr_chemical = "Vitamin-, tápanyag- vagy vegyszeríz.";
$label_cloying = "Nyomasztóan édes";
$descr_cloying = "Szirupos, túlzottan édes, sav/tannin által nem kiegyensúlyozott.";
$label_floral = "Virágos";
$descr_floral = "Virágok vagy parfüm illata.";
$label_moldy = "Penészes";
$descr_moldy = "Állott, dohos, penészes vagy dugóhibás illatok/ízek.";
$label_tannic = "Csersavas";
$descr_tannic = "Szárító, összehúzó, csücsörítő szájérzet, a keserű ízhez hasonló. Erős, cukrozatlan tea vagy szőlőhéj rágásának íze.";
$label_waxy = "Viaszos";
$descr_waxy = "Viaszszerű, faggyús, zsíros.";
$label_medicinal = "Gyógyszeres";
$label_spicy = "Fűszeres";
$label_vinegary = "Ecetes";
$label_plastic = "Műanyagos";
$label_smoky = "Füstös";
$label_inappropriate = "Nem megfelelő";
$label_possible_points = "Lehetséges pontszám";
$label_malt = "Maláta";
$label_ferm_char = "Erjesztési jelleg";
$label_body = "Test";
$label_creaminess = "Krémesség";
$label_astringency = "Összehúzó hatás";
$label_warmth = "Melegség";
$label_appearance = "Megjelenés";
$label_flavor = "Íz";
$label_mouthfeel = "Szájérzet";
$label_overall_impression = "Összhatás";
$label_balance = "Egyensúly";
$label_finish_aftertaste = "Lezárás/Utóíz";
$label_hoppy = "Komlós";
$label_malty = "Malátás";
$label_comments = "Megjegyzések";
$label_flaws = "Stílushibák";
$label_flaw = "Hiba";
$label_flawless = "Hibátlan";
$label_significant_flaws = "Jelentős hibák";
$label_classic_example = "Klasszikus példa";
$label_not_style = "Nem felel meg a stílusnak";
$label_tech_merit = "Technikai érdem";
$label_style_accuracy = "Stílushűség";
$label_intangibles = "Megfoghatatlan tényezők";
$label_wonderful = "Csodálatos";
$label_lifeless = "Élettelen";
$label_feedback = "Visszajelzés";
$label_honey = "Méz";
$label_alcohol = "Alkohol";
$label_complexity = "Komplexitás";
$label_viscous = "Viszkózus";
$label_legs = "Könnycseppek"; // Az üveg falán lecsorgó folyadékot írja le
$label_clarity = "Tisztaság";
$label_brilliant = "Ragyogó";
$label_hazy = "Zavaros";
$label_opaque = "Átlátszatlan";
$label_fruit = "Gyümölcs";
$label_acidity = "Savasság";
$label_tannin = "Tannin";
$label_white = "Fehér";
$label_straw = "Szalma";
$label_yellow = "Sárga";
$label_gold = "Arany";
$label_copper = "Réz";
$label_quick = "Gyors";
$label_long_lasting = "Tartós";
$label_ivory = "Elefántcsont";
$label_beige = "Bézs";
$label_tan = "Cserzett";
$label_lacing = "Csipkézettség";
$label_particulate = "Szemcsés";
$label_black = "Fekete";
$label_large = "Nagy";
$label_small = "Kicsi";
$label_size = "Méret";
$label_retention = "Állékonyság";
$label_head = "Hab";
$label_head_size = "Hab mérete";
$label_head_retention = "Hab állékonysága";
$label_head_color = "Hab színe";
$label_brettanomyces = "Brettanomyces";
$label_cardboard = "Kartonos";
$label_cloudy = "Zavaros";
$label_sherry = "Sherry";
$label_harsh = "Durva";
$label_harshness = "Durvaság";
$label_full = "Telt";
$label_suggested = "Javasolt";
$label_lactic = "Tejes";
$label_smoke = "Füst";
$label_spice = "Fűszer";
$label_vinous = "Boros";
$label_wood = "Fa";
$label_cream = "Krém";
$label_flat = "Lapos";
$label_descriptor_defs = "Leíró meghatározások";
$label_outstanding = "Kiváló";
$descr_outstanding = "Világszínvonalú stíluspélda.";
$label_excellent = "Kitűnő";
$descr_excellent = "Jól példázza a stílust, kisebb finomhangolást igényel.";
$label_very_good = "Nagyon jó";
$descr_very_good = "Általában a stílusparamétereken belül, néhány kisebb hibával.";
$label_good = "Jó";
$descr_good = "Nem találja el a stílust és/vagy kisebb hibák.";
$label_fair = "Elfogadható";
$descr_fair = "Hibás ízek/illatok vagy jelentős stílusbeli hiányosságok. Kellemetlen.";
$label_problematic = "Problémás";
$descr_problematic = "Jelentős hibás ízek és illatok dominálnak. Nehezen iható.";

/**
 * ------------------------------------------
 * Version 2.3.0 Additions
 * ------------------------------------------
 */
$winners_text_006 = "Kérjük, vegye figyelembe: az ennél az asztalnál lévő eredmények hiányosak lehetnek elégtelen nevezési vagy stílusinformáció miatt.";
$label_elapsed_time = "Eltelt idő";
$label_judge_score = "Bírálói pontszám(ok)";
$label_judge_consensus_scores = "Bírálói konszenzusos pontszám(ok)";
$label_your_consensus_score = "Az Ön konszenzusos pontszáma";
$label_score_range_status = "Pontszámtartomány állapota";
$label_consensus_caution = "Konszenzus figyelmeztetés";
$label_consensus_match = "Konszenzus egyezés";
$label_score_range_caution = "Bírálói pontszámtartomány figyelmeztetés";
$label_score_range_ok = "Bírálói pontszámtartomány rendben";
$label_auto_log_out = "Automatikus kijelentkezés:";
$label_place_previously_selected = "Korábban kiválasztott helyezés";
$label_entry_without_eval = "Értékelés nélküli nevezés";
$label_entries_with_eval = "Értékeléssel rendelkező nevezések";
$label_entries_without_eval = "Értékelés nélküli nevezések";
$label_judging_close = "Bírálás lezárása";
$label_session_expire = "Az időszak hamarosan lejár";
$label_refresh = "Oldal frissítése";
$label_stay_here = "Maradok itt";
$label_bottle_inspection = "Palackellenőrzés";
$label_bottle_inspection_comments = "Palackellenőrzési megjegyzések";
$label_consensus_no_match = "A konszenzusos pontszámok nem egyeznek";
$label_score_below_courtesy = "A megadott pontszám az udvariassági küszöb alatt van";
$label_score_greater_50 = "A megadott pontszám nagyobb, mint 50";
$label_score_out_range = "A pontszám a tartományon kívül esik";
$label_score_range = "Pontszámtartomány";
$label_ok = "OK";
$label_esters = "Észterek";
$label_phenols = "Fenolok";
$label_descriptors = "Leírók";
$label_grainy = "Gabonás";
$label_caramel = "Karamell";
$label_bready = "Kenyeres";
$label_rich = "Gazdag";
$label_dark_fruit = "Sötét gyümölcs";
$label_toasty = "Pirítós";
$label_roasty = "Pörkölt";
$label_burnt = "Égett";
$label_citrusy = "Citrusos";
$label_earthy = "Földes";
$label_herbal = "Gyógynövényes";
$label_piney = "Fenyős";
$label_woody = "Fás";
$label_apple_pear = "Alma/Körte";
$label_banana = "Banán";
$label_berry = "Bogyós gyümölcs";
$label_citrus = "Citrus";
$label_dried_fruit = "Aszalt gyümölcs";
$label_grape = "Szőlő";
$label_stone_fruit = "Csonthéjas gyümölcs";
$label_even = "Egyenletes";
$label_gushed = "Kiömlött";
$label_hot = "Forró";
$label_slick = "Síkos";
$label_finish = "Lezárás";
$label_biting = "Csípős";
$label_drinkability = "Ihatóság";
$label_bouquet = "Bukkészlet";
$label_of = "-ból/-ből";
$label_fault = "Hiba";
$label_weeks = "Hét";
$label_days = "Nap";
$label_scoresheet = "Pontozólap";
$label_beer_scoresheet = "Sör pontozólap";
$label_cider_scoresheet = "Almabor pontozólap";
$label_mead_scoresheet = "Mézsör pontozólap";
$label_consensus_status = "Konszenzus állapota";
$evaluation_info_044 = "Az Ön konszenzusos pontszáma nem egyezik a többi bíráló által megadottal.";
$evaluation_info_045 = "A megadott konszenzusos pontszám egyezik a korábbi bírálók által megadottal.";
$evaluation_info_046 = "A pontszámkülönbség nagyobb, mint";
$evaluation_info_047 = "A pontszámkülönbség az elfogadható tartományon belül van.";
$evaluation_info_048 = "Az Ön által megadott helyezés már rögzítve lett az asztalnál. Kérjük, válasszon másik helyezést vagy ne adjon meg helyezést (Nincs).";
$evaluation_info_049 = "Ezeknek a nevezéseknek nincs legalább egy értékelésük";
$evaluation_info_050 = "Kérjük, adja meg a nevezés sorrendi pozícióját a kóstolócsoportban.";
$evaluation_info_051 = "Kérjük, adja meg a kóstolócsoportban lévő nevezések összesített számát.";
$evaluation_info_052 = "Megfelelő méret, kupak, töltöttségi szint, címke eltávolítása stb.";
$evaluation_info_053 = "A konszenzusos pontszám az összes bíráló által elfogadott végső pontszám. Ha a konszenzusos pontszám jelenleg nem ismert, adja meg a saját pontszámát. Ha az itt megadott konszenzusos pontszám eltér a többi bíráló által megadottól, értesítést kap.";
$evaluation_info_054 = "Ez a nevezés továbbjutott a mini-BOS fordulóba.";
$evaluation_info_055 = "Az Ön által megadott konszenzusos pontszám nem egyezik a korábbi bírálók által megadottal ehhez a nevezéshez. Kérjük, egyeztessen a nevezést értékelő többi bírálóval, és szükség szerint módosítsa konszenzusos pontszámát.";
$evaluation_info_056 = "Az Ön által megadott pontszám 13 alá esik, <a href=\"https://www.bjcp.org/cep/GreatBeerJudging.pdf\" target=\"_blank\">ami a BJCP bírálók körében közismert udvariassági küszöb</a>. Kérjük, egyeztessen a többi bírálóval, és szükség szerint módosítsa pontszámát.";
$evaluation_info_057 = "A pontszám nem lehet kevesebb 5-nél és nem lehet több 50-nél.";
$evaluation_info_058 = "Az Ön által megadott pontszám meghaladja az 50-et, ami bármely nevezés maximális pontszáma. Kérjük, ellenőrizze és módosítsa konszenzusos pontszámát.";
$evaluation_info_059 = "Az Ön által megadott pontszám a bírálók közötti pontozási különbség tartományán kívül esik.";
$evaluation_info_060 = "karakter maximum";
$evaluation_info_061 = "Kérjük, adjon meg megjegyzéseket.";
$evaluation_info_062 = "Kérjük, válasszon egy leírót.";
$evaluation_info_063 = "Meginném ezt a mintát.";
$evaluation_info_064 = "Meginném egy korsóval ebből a sörből.";
$evaluation_info_065 = "Fizetnék ezért a sörért.";
$evaluation_info_066 = "Ajánlanám ezt a sört.";
$evaluation_info_067 = "Kérjük, adjon meg egy értékelést.";
$evaluation_info_068 = "Kérjük, adja meg a konszenzusos pontszámot - minimum 5, maximum 50.";
$evaluation_info_069 = "Legalább két bíráló az Ön nevezését tartalmazó kóstolócsoportból konszenzusra jutott a végső pontszámot illetően. Ez nem feltétlenül az egyéni pontszámok átlaga.";
$evaluation_info_070 = "A BJCP pontozólap alapján:";
$evaluation_info_071 = "Több mint 15 perc telt el.";
$evaluation_info_072 = "Alapértelmezés szerint az automatikus kijelentkezés 30 percre van meghosszabbítva a nevezések értékelésénél.";
$alert_text_090 = "Az Ön munkamenete két perc múlva lejár. Maradjon az aktuális oldalon, hogy befejezze munkáját, mielőtt az idő lejár. Több időre van szüksége? Frissítse ezt az oldalt a jelenlegi munkamenet folytatásához (a nem mentett űrlapadatok elveszhetnek). Vagy egyszerűen jelentkezzen ki.";
$alert_text_091 = "Az Ön munkamenete 30 másodperc múlva lejár. Frissítheti az oldalt a jelenlegi munkamenet folytatásához, vagy kijelentkezhet.";
$alert_text_092 = "Legalább egy bírálási időszakot meg kell határozni az asztal hozzáadásához.";

$brewer_entries_text_026 = "Ennek a nevezésnek a bírálói pontozólapjai több formátumban vannak. Minden formátum egy vagy több érvényes értékelést tartalmaz erről a nevezésről.";

// QR szöveg frissítése
$qr_text_008 = "A nevezések QR-kóddal történő bejelentkeztetéséhez kérjük, adja meg a helyes jelszót. A jelszót munkamenetenként csak egyszer kell megadnia - ügyeljen arra, hogy a böngészőt vagy a QR-kód olvasó alkalmazást nyitva tartsa.";
$qr_text_015 = "Olvassa be a következő QR-kódot. Zárja be ezt a böngészőlapot, ha szeretné.<br><br>Újabb operációs rendszereknél nyissa meg a mobileszköz kameraalkalmazását. Régebbi operációs rendszereknél indítsa el/váltson vissza a beolvasó alkalmazásra.";
$qr_text_017 = "A QR-kód beolvasás a legtöbb modern mobil operációs rendszeren natívan elérhető. Egyszerűen irányítsa a kamerát a palackcímkén lévő QR-kódra, és kövesse az utasításokat. Régebbi mobil operációs rendszereknél QR-kód beolvasó alkalmazás szükséges a funkció használatához.";
$qr_text_018 = "Olvassa be a palackcímkén lévő QR-kódot, adja meg a szükséges jelszót, és jelentkeztesse be a nevezést.";

/**
 * ------------------------------------------
 * Version 2.3.2 Additions
 * ------------------------------------------
 */

$label_select_state = "Válassza ki vagy keresse meg az államát";
$label_select_below = "Válasszon alább";
$output_text_033 = "Amikor jelentését benyújtja a BJCP-nek, lehetséges, hogy nem mindenki a személyzeti listán kap pontokat. Javasolt, hogy először a BJCP azonosítóval rendelkezőknek osszon pontokat.";
$styles_entry_text_PRX3 = "A nevezőnek meg kell adnia a felhasznált szőlőfajtát vagy szőlőmustot.";
$styles_entry_text_C1A = "A nevezőknek MEG KELL adniuk a szénsavszintet (3 szint). A nevezőknek MEG KELL adniuk az édességet (5 kategória). Ha az OG lényegesen meghaladja a jellemző tartományt, a nevezőnek meg kell magyaráznia, pl. egy adott almafajta, amely magas fajsúlyú levet ad.";
$styles_entry_text_C1B = "A nevezőknek MEG KELL adniuk a szénsavszintet (3 szint). A nevezőknek MEG KELL adniuk az édességet (száraztól a közepesen édesig, 4 szint). A nevezők MEGADHATJÁK az almafajtát egyfajtás almaborhoz; ha megadják, fajtajelleg várható.";
$styles_entry_text_C1C = "A nevezőknek MEG KELL adniuk a szénsavszintet (3 szint). A nevezőknek MEG KELL adniuk az édességet (csak közepestől édesig, 3 szint). A nevezők MEGADHATJÁK az almafajtát egyfajtás almaborhoz; ha megadják, fajtajelleg várható.";
$winners_text_007 = "Ennél az asztalnál nincsenek nyertes nevezések.";

/**
 * ------------------------------------------
 * Version 2.4.0 Additions
 * ------------------------------------------
 */
$label_entries_to_judge = "Bírálandó nevezések";
$evaluation_info_073 = "Ha bármilyen elemet vagy megjegyzést módosított vagy hozzáadott ezen a pontozólapon, az adatai elveszhetnek, ha elhagyja ezt az oldalt.";
$evaluation_info_074 = "Ha VÉGZETT módosításokat, zárja be ezt a párbeszédablakot, görgessen a pontozólap aljára, és válassza az Értékelés beküldése lehetőséget.";
$evaluation_info_075 = "Ha NEM végzett módosításokat, válassza az alábbi kék Bírálói kezelőfelület gombot.";
$evaluation_info_076 = "Adjon megjegyzést a malátáról, komlóról, észterekről és egyéb aromákról.";
$evaluation_info_077 = "Adjon megjegyzést a színről, tisztaságról és habról (állékonyság, szín és textúra).";
$evaluation_info_078 = "Adjon megjegyzést a malátáról, komlóról, erjesztési jellemzőkről, egyensúlyról, lezárásról/utóízről és egyéb ízjellemzőkről.";
$evaluation_info_079 = "Adjon megjegyzést a testről, szénsavról, melegségről, krémességről, összehúzó hatásról és egyéb szájérzetekről.";
$evaluation_info_080 = "Adjon megjegyzést a nevezéssel kapcsolatos általános ivási élményről, tegyen javaslatokat a fejlesztéshez.";

if ((isset($_SESSION['prefsStyleSet'])) && (($_SESSION['prefsStyleSet'] == "BJCP2021") || ($_SESSION['prefsStyleSet'] == "BJCP2025"))) {
    $styles_entry_text_21B = "A nevezőnek MEG KELL adnia az erősséget (session, standard, double); ha nincs megadva erősség, a standard értendő. A nevezőnek MEG KELL adnia az IPA-különlegesség típusát a Stílusirányelvekben meghatározott Jelenleg definiált típusok listájáról, vagy a BJCP weboldalán található Ideiglenes stílusok szerint módosítva; VAGY a nevezőnek le KELL írnia a különleges IPA típusát és fő jellemzőit megjegyzés formájában, hogy a bírálók tudják, mire számítsanak. A nevezők MEGADHATJÁK a felhasznált komlófajtákat, ha úgy érzik, hogy a bírálók nem ismerik fel az újabb komlók fajtajellegét. A nevezők MEGADHATNAK meghatározott IPA-típusok kombinációját (pl. Black Rye IPA) további leírás nélkül.";
    $styles_entry_text_24C = "A nevezőnek MEG KELL adnia: szőke, borostyán vagy barna Bière de Garde.";
    $styles_entry_text_25B = "A nevezőnek MEG KELL adnia az erősséget (asztali, standard, szuper) és a színt (világos, sötét). A nevező MEGADHATJA a felhasznált jelleg-gabonákat.";
    $styles_entry_text_27A = "Gyűjtőkategória a BJCP által NEM definiált egyéb történelmi söröknek. A nevezőnek leírást KELL adnia a bírálóknak a történelmi stílusról, amely NEM tartozik a BJCP által jelenleg meghatározott történelmi stíluspéldák közé. Jelenleg definiált példák: Kellerbier, Kentucky Common, Lichtenhainer, London Brown Ale, Piwo Grodziskie, Pre-Prohibition Lager, Pre-Prohibition Porter, Roggenbier, Sahti. Ha egy sört csak stílusnévvel, leírás nélkül neveznek be, nagyon valószínűtlen, hogy a bírálók tudni fogják, hogyan bírálják.";
    $styles_entry_text_28A = "A nevezőnek MEG KELL adnia egy alapstílust, vagy leírást kell adnia az összetevőkről, specifikációkról vagy kívánt jellegről. A nevező MEGADHATJA a felhasznált Brett törzseket.";
    $styles_entry_text_28B = "A nevezőnek MEG KELL adnia a sör leírását, azonosítva a felhasznált élesztőt vagy baktériumot, valamint egy alapstílust, vagy az összetevőket, specifikációkat, vagy a sör céljellegét.";
    $styles_entry_text_28C = "A nevezőnek MEG KELL adnia minden felhasznált különleges összetevőt (pl. gyümölcs, fűszer, gyógynövény vagy fa). A nevezőnek MEG KELL adnia a sör leírását, azonosítva a felhasznált élesztőt vagy baktériumot, valamint egy alapstílust, vagy az összetevőket, specifikációkat, vagy a sör céljellegét. A sör különleges jellegének általános leírása lefedi az összes szükséges elemet.";
    $styles_entry_text_29A = "A nevezőnek MEG KELL adnia a felhasznált gyümölcs típusát/típusait. A nevezőnek MEG KELL adnia a sör leírását, azonosítva egy alapstílust vagy az összetevőket, specifikációkat, vagy a sör céljellegét. A sör különleges jellegének általános leírása lefedi az összes szükséges elemet. A klasszikus stíluson alapuló gyümölcssöröket ebbe a stílusba kell nevezni, kivéve a Lambic-ot.";
    $styles_entry_text_29B = "A nevezőnek meg kell adnia a gyümölcs típusát és a felhasznált fűszer/gyógynövény/zöldség típusát; az egyes összetevőket nem kell megadni, ha ismert fűszerkeveréket használnak (pl. almáspite-fűszer). A nevezőnek meg kell adnia a sör leírását, egy alapstílust vagy az összetevőket, specifikációkat, vagy a sör céljellegét. A sör különleges jellegének általános leírása lefedi az összes szükséges elemet.";
    $styles_entry_text_29C = "A nevezőnek MEG KELL adnia a felhasznált gyümölcs típusát. A nevezőnek MEG KELL adnia a további összetevő típusát (a bevezetés szerint) vagy az alkalmazott különleges eljárást. A nevezőnek MEG KELL adnia a sör leírását, azonosítva egy alapstílust vagy az összetevőket, specifikációkat, vagy a sör céljellegét. A sör különleges jellegének általános leírása lefedi az összes szükséges elemet.";
    $styles_entry_text_29D = "A nevezőnek MEG KELL adnia a felhasznált szőlő típusát. A nevező MEGADHAT további információt az alapstílusról vagy jellegzetes összetevőkről.";
    $styles_entry_text_30A = "A nevezőnek MEG KELL adnia a felhasznált fűszerek, gyógynövények vagy zöldségek típusát, de az egyes összetevőket nem kell megadni, ha ismert fűszerkeveréket használnak (pl. almáspite-fűszer, curry por, chili por). A nevezőnek MEG KELL adnia a sör leírását, azonosítva egy alapstílust vagy az összetevőket, specifikációkat, vagy a sör céljellegét. A sör különleges jellegének általános leírása lefedi az összes szükséges elemet.";
    $styles_entry_text_30B = "A nevezőnek MEG KELL adnia a felhasznált fűszerek, gyógynövények vagy zöldségek típusát; az egyes összetevőket nem kell megadni, ha ismert fűszerkeveréket használnak (pl. sütőtökpite-fűszer). A nevezőnek MEG KELL adnia a sör leírását, azonosítva egy alapstílust vagy az összetevőket, specifikációkat, vagy a sör céljellegét. A sör különleges jellegének általános leírása lefedi az összes szükséges elemet.";
    $styles_entry_text_30C = "A nevezőnek MEG KELL adnia a felhasznált fűszerek, cukrok, gyümölcsök vagy további erjeszthető anyagok típusát; az egyes összetevőket nem kell megadni, ha ismert fűszerkeveréket használnak (pl. forralt bor fűszer). A nevezőnek MEG KELL adnia a sör leírását, azonosítva egy alapstílust vagy az összetevőket, specifikációkat, vagy a sör céljellegét. A sör különleges jellegének általános leírása lefedi az összes szükséges elemet.";
    $styles_entry_text_30D = "A nevezőnek MEG KELL adnia a felhasznált fűszerek/gyógynövények/zöldségek típusát, de az egyes összetevőket nem kell megadni, ha ismert fűszerkeveréket használnak (pl. almáspite-fűszer, curry por, chili por). A nevezőnek MEG KELL adnia a további összetevő típusát (a bevezetés szerint) vagy az alkalmazott különleges eljárást. A nevezőnek MEG KELL adnia a sör leírását, azonosítva egy alapstílust vagy az összetevőket, specifikációkat, vagy a sör céljellegét. A sör különleges jellegének általános leírása lefedi az összes szükséges elemet.";
    $styles_entry_text_31A = "A nevezőnek meg kell adnia a felhasznált alternatív gabona típusát. A nevezőnek meg kell adnia a sör leírását, azonosítva egy alapstílust vagy az összetevőket, specifikációkat, vagy a sör céljellegét. A sör különleges jellegének általános leírása lefedi az összes szükséges elemet.";
    $styles_entry_text_31B = "A nevezőnek MEG KELL adnia a felhasznált cukor típusát. A nevezőnek MEG KELL adnia a sör leírását, azonosítva egy alapstílust vagy az összetevőket, specifikációkat, vagy a sör céljellegét. A sör különleges jellegének általános leírása lefedi az összes szükséges elemet.";
    $styles_entry_text_32A = "A nevezőnek MEG KELL adnia egy alapstílust. A nevezőnek MEG KELL adnia a fa vagy füst típusát, ha fajtajellegű füstkarakter észlelhető.";
    $styles_entry_text_32B = "A nevezőnek MEG KELL adnia a fa vagy füst típusát, ha fajtajellegű füstkarakter észlelhető. A nevezőnek MEG KELL adnia a további összetevőket vagy eljárásokat, amelyek ezt különleges füstölt sörré teszik. A nevezőnek MEG KELL adnia a sör leírását, azonosítva egy alapstílust vagy az összetevőket, specifikációkat, vagy a sör céljellegét. A sör különleges jellegének általános leírása lefedi az összes szükséges elemet.";
    $styles_entry_text_33A = "A nevezőnek MEG KELL adnia a felhasznált fa típusát és a pörkölés vagy szénégetés szintjét (ha alkalmazták). Ha szokatlan fafajtát használtak, a nevezőnek rövid leírást KELL adnia a fa által a sörnek adott érzékszervi jellemzőkről. A nevezőnek MEG KELL adnia a sör leírását, azonosítva egy alapstílust vagy az összetevőket, specifikációkat, vagy a sör céljellegét. A sör különleges jellegének általános leírása lefedi az összes szükséges elemet.";
    $styles_entry_text_33B = "A nevezőnek MEG KELL adnia a további alkoholjelleget, a hordóra vonatkozó információkkal, ha az releváns a végső ízprofil szempontjából. Ha szokatlan fa vagy összetevő került felhasználásra, a nevezőnek rövid leírást KELL adnia az összetevők által a sörnek adott érzékszervi jellemzőkről. A nevezőnek MEG KELL adnia a sör leírását, azonosítva egy alapstílust vagy az összetevőket, specifikációkat, vagy a sör céljellegét. A sör különleges jellegének általános leírása lefedi az összes szükséges elemet.";
    $styles_entry_text_34A = "A nevezőnek MEG KELL adnia a kereskedelmi sör nevét, a sör specifikációit (létfontosságú statisztikák), valamint egy rövid érzékszervi leírást vagy a sör elkészítéséhez használt összetevők listáját. Ezen információk nélkül a sört nem ismerő bírálóknak nem lesz összehasonlítási alapjuk.";
    $styles_entry_text_34B = "A nevezőnek MEG KELL adnia a felhasznált alapstílust vagy stílusokat, valamint minden különleges összetevőt, eljárást vagy változatot. A nevező MEGADHAT további leírást a sör érzékszervi profiljáról vagy a kapott sör létfontosságú statisztikáiról.";
    $styles_entry_text_PRX3 = "A nevezőnek MEG KELL adnia a felhasznált szőlő típusát. A nevező MEGADHAT további információt az alapstílusról vagy jellegzetes összetevőkről.";
}

/**
 * ------------------------------------------
 * Version 2.5.0 Additions
 * ------------------------------------------
 */
$register_text_047 = "A biztonsági kérdése és/vagy válasza megváltozott.";
$register_text_048 = "Ha nem Ön kezdeményezte ezt a változtatást, fiókja feltört lehet. Azonnal jelentkezzen be fiókjába, és változtassa meg jelszavát, valamint frissítse biztonsági kérdését és válaszát.";
$register_text_049 = "Ha nem tud bejelentkezni fiókjába, azonnal forduljon egy oldal-adminisztrátorhoz, hogy frissítse jelszavát és egyéb létfontosságú fiókinformációit.";
$register_text_050 = "A biztonsági kérdésre adott válasza titkosítva van, és az oldal-adminisztrátorok nem tudják elolvasni. Meg kell adnia, ha úgy dönt, hogy megváltoztatja biztonsági kérdését és/vagy válaszát.";
$register_text_051 = "Fiókinformáció frissítve";
$register_text_052 = "Minden alábbi helyszínhez \"Igen\" vagy \"Nem\" válasz szükséges.";

$brewer_text_044 = "Szeretné megváltoztatni biztonsági kérdését és/vagy válaszát?";
$brewer_text_045 = "Nincsenek rögzített eredmények.";
$brewer_text_046 = "A szabad szöveges klubnév megadásakor egyes szimbólumok nem engedélyezettek, beleértve az és jelet (&amp;), az egyes idézőjeleket (&#39;), a kettős idézőjeleket (&quot;) és a százalékjelet (&#37;).";
$brewer_text_047 = "Ha a fent felsorolt időszakok egyikén sem elérhető, amikor az \"Igen\" lehetőséget választja, de más minőségben még tud személyzeti tagként szolgálni, kérjük, hagyja az \"Igen\" választ.";
$brewer_text_048 = "Nevezések postázása";
$brewer_text_049 = "Válassza a \"Nem alkalmazható\" lehetőséget, ha jelenleg nem tervez nevezést benyújtani a versenyre.";
$brewer_text_050 = "Válassza a \"Nevezések postázása\" lehetőséget, ha tervezi becsomagolni és elküldeni nevezéseit a megadott szállítási helyre.";
$label_change_security = "Biztonsági kérdés/válasz módosítása?";
$label_semi_dry = "Félszáraz";
$label_semi_sweet = "Félédes";
$label_shipping_location = "Szállítási helyszín";
$label_allergens = "Allergének";
$volunteers_text_010 = "A személyzet jelezheti elérhetőségét a következő nem bírálási időszakokra:";
$evaluation_info_081 = "Adjon megjegyzést a mézkifejezésről, alkoholról, észterekről, komplexitásról és egyéb aromákról.";
$evaluation_info_082 = "Adjon megjegyzést a színről, tisztaságról, könnycseppekről és szénsavról.";
$evaluation_info_083 = "Adjon megjegyzést a mézről, édességről, savasságról, tanninról, alkoholról, egyensúlyról, testről, szénsavról, utóízről és minden különleges összetevőről vagy stílusspecifikus ízről.";
$evaluation_info_084 = "Adjon megjegyzést a nevezéssel kapcsolatos általános ivási élményről, tegyen javaslatokat a fejlesztéshez.";
$evaluation_info_085 = "Szín (2), tisztaság (2), szénsavszint (2).";
$evaluation_info_086 = "Egyéb összetevők kifejezése a megfelelőek szerint.";
$evaluation_info_087 = "A savasság, édesség, alkoholerősség, test, szénsav (ha szükséges) egyensúlya (14),
	Egyéb összetevők a megfelelőek szerint (5), Utóíz (5).";
$evaluation_info_088 = "Adjon megjegyzést a nevezéssel kapcsolatos általános ivási élményről, tegyen javaslatokat a fejlesztéshez.";
$evaluation_info_089 = "A minimum szószám elérésre került vagy túllépve.";
$evaluation_info_090 = "Köszönjük, hogy a lehető legteljesebb értékelést adta. Összesített szószám: ";
$evaluation_info_091 = "A megjegyzésekhez szükséges minimum szószám: ";
$evaluation_info_092 = "Eddigi szószám: ";
$evaluation_info_093 = "A minimum szószámkövetelmény nem teljesült a fenti Összhatás visszajelzés mezőben.";
$evaluation_info_094 = "A minimum szószámkövetelmény nem teljesült egy vagy több fenti visszajelzés/megjegyzés mezőben.";

/**
 * ------------------------------------------
 * Version 2.6.0 Additions
 * ------------------------------------------
 */
$label_regional_variation = "Regionális változat";
$label_characteristics = "Jellemzők";
$label_intensity = "Intenzitás";
$label_quality = "Minőség";
$label_palate = "Ízlelés";
$label_medium = "Közepes";
$label_medium_dry = "Közepesen száraz";
$label_medium_sweet = "Közepesen édes";
$label_your_score = "Az Ön pontszáma";
$label_summary_overall_impression = "Az értékelés összefoglalása és összhatás";
$label_medal_count = "Éremcsoport szám";
$label_best_brewer_place = "Legjobb sörfőző helyezés";
$label_industry_affiliations = "Ipari szervezeti tagságok";
$label_deep_gold = "Mély arany";
$label_chestnut = "Gesztenye";
$label_pink = "Rózsaszín";
$label_red = "Piros";
$label_purple = "Lila";
$label_garnet = "Gránát";
$label_clear = "Tiszta";
$label_final_judging_date = "Utolsó bírálási dátum";
$label_entries_judged = "Bírált nevezések";
$label_results_export = "Eredmények exportálása";
$label_results_export_personal = "Személyes eredmények exportálása";
$brew_text_041 = "Opcionális &ndash; adjon meg egy regionális változatot (pl. mexikói lager, holland lager, japán rizslager stb.).";
$evaluation_info_095 = "Következő kijelölt bírálási időszak nyitva:";
$evaluation_info_096 = "A felkészülés segítése érdekében a kijelölt asztalok/kóstolócsoportok és a hozzájuk tartozó nevezések tíz perccel az időszak kezdete előtt elérhetők.";
$evaluation_info_097 = "A következő bírálási időszaka most elérhető.";
$evaluation_info_098 = "Frissítse a megtekintéshez.";
$evaluation_info_099 = "Múltbeli vagy aktuális bírálási időszakok:";
$evaluation_info_100 = "Közelgő bírálási időszakok:";
$evaluation_info_101 = "Kérjük, adjon meg egy másik szín leírót.";
$evaluation_info_102 = "Adja meg az összesített pontszámát - maximum 50. Szükség esetén használja az alábbi pontozási útmutatót.";
$evaluation_info_103 = "Kérjük, adja meg pontszámát - minimum 5, maximum 50.";
$brewer_text_051 = "Válassza ki azokat az ipari szervezeteket, amelyekhez alkalmazottként, önkéntesként stb. kötődik. Ez annak biztosítása érdekében szükséges, hogy ne legyen összeférhetetlenség a bírálók és felszolgálók nevezésekhez való kijelölésekor.";
$brewer_text_052 = "<strong>Ha bármely ipari szervezet <u>nem</u> szerepel a fenti legördülő listában, itt adja meg.</strong> Válassza el az egyes szervezetek nevét vesszővel (,) vagy pontosvesszővel (;). Egyes szimbólumok nem engedélyezettek, beleértve a kettős idézőjeleket (&quot;) és a százalékjelet (&#37;).";

/**
 * ------------------------------------------
 * Version 2.6.0 Additions
 * ------------------------------------------
 */
$evaluation_info_104 = "Nem minden bíráló jelezte, hogy ez a nevezés továbbjutott a mini-BOS fordulóba. Kérjük, ellenőrizze, és válassza az Igen vagy Nem lehetőséget fent.";
$evaluation_info_105 = "A következő nevezéseknél eltérő mini-BOS jelzések vannak a bírálóktól:";
$label_non_judging = "Nem bírálási időszakok";

/**
 * ------------------------------------------
 * Version 2.6.2 Additions
 * ------------------------------------------
 */
$label_mhp_number = "Master Homebrewer Program tagsági szám";
$brewer_text_053 = "A Master Homebrewer Program egy nonprofit szervezet, amelyet az amatőr sörfőzés mesterfokú elsajátításának népszerűsítésére hoztak létre.";
$best_brewer_text_015 = "Az egyes helyezett nevezések pontszámait a következő képlet alapján számítják ki, amely a Master Homebrewer Program <a href='https://www.masterhomebrewerprogram.com/circuit-of-america' target='_blank'>Circuit of America</a> versenyéhez használt képleten alapul:";

/**
 * ------------------------------------------
 * Version 2.7.0 Additions
 * ------------------------------------------
 */
$label_abv = "Alkoholtartalom (ABV)";
$label_final_gravity = "Végső fajsúly";
$label_juice_source = "Gyümölcs- vagy lé-forrás(ok)";
$label_select_all_apply = "Válassza ki az összeset, ami alkalmazható";
$label_pouring = "Töltés";
$label_pouring_notes = "Töltési megjegyzések";
$label_rouse_yeast = "Élesztő felkeverése";
$label_fast = "Gyors";
$label_slow = "Lassú";
$label_normal = "Normál";
$label_brewing_partners = "Sörfőző partnerek";
$label_entry_edit_deadline = "Nevezés szerkesztési határideje";
$brew_text_042 = "Kérjük, adja meg az alkoholtartalmat századra kerekítve.";
$brew_text_043 = "Csak számok - tizedesek elfogadhatók századra kerekítve (pl. 5,2, 12,84 stb.).";
$brew_text_044 = "Kérjük, adja meg a befejező fajsúlyt ezredre kerekítve (pl. 0,991, 1,000, 1,007 stb.).";
$brew_text_045 = "Kérjük, adja meg a vonatkozó forrás(oka)t.";
$brew_text_046 = "Kérjük, adja meg ennek az almabornak az összes gyümölcsadagolásának eredetét. A gyümölcsadagolás az italhoz adott összes gyümölcs/lé, amely nem az alma- vagy körtealap.";
$brew_text_047 = "Hogyan kell az Ön nevezését tölteni a bírálók számára?";
$brew_text_048 = "Fel kell-e keverni az élesztőt töltés előtt?";
$brew_text_049 = "Adjon további információt arról, hogyan kell az Ön nevezését tölteni, vagy egyéb kapcsolódó dolgokról (pl. lehetséges kiömlés stb.).";
$brewer_text_055 = "Válassza ki az összes sörfőző partnert, akivel kapcsolatban áll. Ez annak biztosítása érdekében szükséges, hogy ne legyen összeférhetetlenség a bírálók és felszolgálók nevezésekhez való kijelölésekor.";
$brewer_text_054 = "<strong>Ha bármely személy neve <u>nem</u> szerepel a fenti legördülő listában, itt adja meg a TELJES nevét (pl. Kovács János, Nagy Péter, Kiss Katalin stb.). Itt adja hozzá a sörfőző csapatneveket is.</strong> Válassza el az egyes csapat- vagy személyneveket vesszővel (,) vagy pontosvesszővel (;). Egyes szimbólumok nem engedélyezettek, beleértve a kettős idézőjeleket (&quot;) és a százalékjelet (&#37;).";

$brew_text_050 = "Egyes stílusok le vannak tiltva, mivel a megfelelő stílustípus (pl. sör, mézsör, almabor stb.) korlátja elérésre került.";
$entry_info_text_053 = "Nevezési korlátok stílustípusonként:";
$alert_text_093 = "Egyes nevezési korlátok elérve!";
$alert_text_094 = "A következő stílustípusokhoz nem fogadunk el több nevezést";
$label_limit = "Korlát";
$label_beer = "Sör";
$label_mead = "Mézsör";
$label_cider = "Almabor";
$label_mead_cider = "Mézsör/Almabor";
$label_wine = "Bor";
$label_rice_wine = "Rizsbor";
$label_spirits = "Szeszesitalok";
$label_kombucha = "Kombucha";
$label_pulque = "Pulque";

$form_required_fields_00 = "Nem minden kötelező mező lett kitöltve vagy kiválasztva.";
$form_required_fields_01 = "A hiányzó értékű kötelező mezők csillaggal <i class=\"fa fa-sm fa-star\"></i> és/vagy <strong class=\"text-danger\">pirossal</strong> vannak jelölve. Kérjük, szükség szerint görgessen/húzzon felfelé az összes kötelező mező megtalálásához.";
$form_required_fields_02 = "Ez a mező kötelező.";

$entry_info_text_054 = "Aktuális nevezésszám stílustípusonként és a hozzá tartozó korlátok:";

$maintenance_text_002 = "Csak a legmagasabb szintű adminisztrátorok jelentkezhetnek be, amikor az oldal karbantartási módban van.";

$brew_text_054 = "Honnan származik az alma/körte gyümölcs vagy lé? Kérjük, válassza ki az alapitalra vonatkozó összes alkalmazandót.";
$label_packaging = "Csomagolás";
$label_bottle = "Palack";
$label_other_size = "Egyéb méret";
$label_can = "Doboz";
$label_fruit_add_source = "Gyümölcsadagolás forrás(ai)";
$label_yearly_volume = "Éves mennyiség";
$label_gallons = "Gallon";
$label_barrels = "Hordó";
$label_hectoliters = "Hektoliter";

/**
 * ------------------------------------------
 * Version 2.7.1 Additions
 * ------------------------------------------
 */
$sidebar_text_027 = "érvényes eddig:";
$entry_info_text_055 = "Jelenleg nincs fizetési mód megadva a rendszerben. Ellenőrizze a versenyszabályokat, vagy forduljon a szervezőhöz.";

/**
 * ------------------------------------------
 * Version 2.7.2 Additions
 * ------------------------------------------
 */
$brew_text_055 = "Visszatér ide újabb nevezés hozzáadásához?";
$brewer_info_015 = "<p>Úgy tűnik, Ön bírálónak vagy felszolgálónak jelentkezett, de nem jelezte, hogy elérhető bármely bírálási időszakra mindkét szerepkörben.</p><p>Kérjük, válassza az alábbi gombot a fiókja szerkesztéséhez, majd válassza az \"Igen\" lehetőséget minden olyan időszakra, amelyben elérhető a bírálásra a Bírálási időszak elérhetősége részben, valamint azokra, amelyekben felszolgálóként elérhető a Felszolgálói időszak elérhetősége részben.</p><p>Ha egyik időszakra sem elérhető egyik vagy mindkét szerepkörben, kérjük, válassza a \"Nem\" lehetőséget a Bírálás és/vagy Felszolgálás részben.</p>";
$brewer_info_016 = "<p>Úgy tűnik, Ön bírálónak jelentkezett, de nem jelezte, hogy elérhető bármely bírálási időszakra.</p><p>Kérjük, válassza az alábbi \"Fiókinformáció szerkesztése\" gombot a fiókja szerkesztéséhez, majd válassza az \"Igen\" lehetőséget minden olyan időszakra, amelyben elérhető a bírálásra a Bírálási időszak elérhetősége részben.</p><p>Ha egyik időszakra sem elérhető, kérjük, válassza az alábbi \"Nem kívánok bírálóként önkénteskedni\" gombot, vagy a \"Nem\" lehetőséget a Bírálás részben a fiókinformáció szerkesztésekor.</p>";
$brewer_info_017 = "<p>Úgy tűnik, Ön felszolgálónak jelentkezett, de nem jelezte, hogy elérhető bármely bírálási időszakra.</p><p>Kérjük, válassza az alábbi \"Fiókinformáció szerkesztése\" gombot a fiókja szerkesztéséhez, majd válassza az \"Igen\" lehetőséget minden olyan időszakra, amelyben felszolgálóként elérhető a Felszolgálói időszak elérhetősége részben.</p><p>Ha egyik időszakra sem elérhető, kérjük, válassza az alábbi \"Nem kívánok felszolgálóként önkénteskedni\" gombot, vagy a \"Nem\" lehetőséget a Felszolgálás részben a fiókinformáció szerkesztésekor.</p>";
$brewer_info_018 = "<strong>Ön jelezte, hogy hajlandó bírálóként szolgálni, de nem jelezte, hogy elérhető bármely felsorolt bírálási időszakra.</strong> Kérjük, szerkessze fiókinformációit, és válassza az \"Igen\" lehetőséget egy vagy több bírálási időszakra.";
$brewer_info_019 = "<strong>Ön jelezte, hogy hajlandó felszolgálóként szolgálni, de nem jelezte, hogy elérhető bármely felsorolt felszolgálói időszakra.</strong> Kérjük, szerkessze fiókinformációit, és válassza az \"Igen\" lehetőséget egy vagy több felszolgálói időszakra.";
$brewer_info_020 = "<strong>Ön már ki lett jelölve egy asztalhoz bírálóként vagy felszolgálóként</strong>. Ha szeretné megváltoztatni elérhetőségét, kérjük, forduljon a verseny szervezőjéhez vagy a bírálókoordinátorhoz.";

/**
 * ------------------------------------------
 * Version 3.0.0 Additions
 * ------------------------------------------
 */
$label_not_started = "Nem kezdődött el";
$label_in_progress = "Folyamatban";
$label_concluded = "Lezárult";
$label_start = "Kezdés";
$label_end = "Befejezés";
$label_visit = "Meglátogatás";
$label_no_website = "Nincs weboldal";
$label_register_as_judge = "Regisztráljon bírálónak";
$label_register_as_steward = "Regisztráljon felszolgálónak";
$label_create_account = "Fiók létrehozása";
$label_log_in_to_enter = "Regisztráljon vagy jelentkezzen be a nevezéshez";
$label_not_available = "Nem elérhető";
$label_fyi = "Tájékoztatásul";
$label_results = "Eredmények";
$label_entries_remaining = "Hátralévő nevezések a jelenlegi korlátig";
$label_entry_limit_enforced = "Nevezési korlát érvényes eddig:";
$label_button_no_steward = "Nem kívánok felszolgálóként önkénteskedni";
$label_button_no_judge = "Nem kívánok bírálóként önkénteskedni";
$label_staff_availability = "Személyzeti elérhetőség";
$label_original_gravity = "Eredeti fajsúly";
$label_verified = "Ellenőrizve";
$label_style_type = "Stílustípus";
$label_entry_limit_style = "Nevezési korlátok stíluskategóriánként";
$label_current_count = "Aktuális szám";
$label_scroll = "Görgetés";
$label_opt_out = "Lemondás";
$label_opening = "Megnyitás";
$label_entry_limits = "Nevezési korlátok";
$label_entry_limit_participant = "Nevezési korlát résztvevőnként";
$label_entry_limit_substyle = "Stílusonkénti nevezési korlát";
$label_entry_limit_exception = "Stílusonkénti kivételkorlát";
$label_exceptions = "Kivételek";
$label_style_excepted = "Stíluskivételek";
$label_no_sessions = "Nincs kiválasztott időszak";
$label_resume_updates = "Frissítések folytatása";
$label_recorded = "Rögzítve";
$label_other_info = "Egyéb információ";

$brewer_text_056 = "Válassza a fenti &quot;Lemondás&quot; lehetőséget, ha nem kíván részt venni a versenyhez kapcsolódó Pro-Am lehetőségekben.";

$brew_text_056 = "Kérjük, adja meg a nevezés szénsavszintjét.";
$brew_text_057 = "Kérjük, adja meg a nevezés édességi szintjét.";
$brew_text_058 = "Kérjük, adja meg a nevezés erősségét.";
$brew_text_059 = "Kérjük, adja meg a nevezés színét.";
$brew_text_060 = "Kérjük, adja meg a fajsúlyt ezredre kerekítve (pl. 1,120 vagy 1,014 stb.).";
$brew_text_061 = "A számok kétpercenként frissülnek.";
$brew_text_062 = "A számok szünetelnek, amíg az ablak inaktív. Kattintson vagy koppintson az újraaktiváláshoz.";
$brew_text_063 = "A számok percenként frissülnek.";
$brew_text_064 = "A számfrissítések szünetelnek.";
$brew_text_065 = "A számfrissítések időtúllépés miatt leálltak. Válassza a Frissítések folytatása lehetőséget az újraindításhoz.";

$bottle_labels_008 = "Győződjön meg róla, hogy a böngésző nyomtatási beállításai <strong>álló elrendezés</strong> és <strong>100&#37; méretarány</strong>.";

$contact_text_005 = "Válasszon vagy keressen egy kapcsolattartót.";
$contact_text_006 = "Kérjük, válasszon egy személyt.";
$contact_text_007 = "Kérjük, adja meg a vezetéknevét és a keresztnevét.";
$contact_text_008 = "Kérjük, adjon meg egy érvényes e-mail címet.";
$contact_text_009 = "Kérjük, adjon meg egy tárgyat.";
$contact_text_010 = "Kérjük, írjon egy üzenetet.";

$contact_text_011 = "Válassza ki az e-mail címet a natív levelezőalkalmazás elindításához. Másolja ki a címet, és illessze be egy új üzenetbe, ha webalapú e-mail szolgáltatást használ, mint például a Gmail stb.";
$contact_text_012 = "Kérjük, vegye figyelembe: ez az ablak több, az e-mail cím spambot-gyűjtők elleni védelmét szolgáló intézkedés eredménye. Ha hiba van, vagy a verseny kapcsolattartó e-mail címe nem érhető el fent, az biztonsági okokból el lett rejtve. Ebben az esetben forduljon ehhez a versenytisztviselőhöz más módon (közösségi média, szervezet weboldala stb.).";
$contact_text_013 = "<strong>A szöveg beillesztése le van tiltva.</strong> Kérjük, gépelje be az adatait.";

$error_text_000 = "Kérjük, használja a fenti fő navigációt, hogy eljusson a kívánt helyre.";
$error_text_001 = "Ha a fenti hivatkozások nem működnek, kérjük, forduljon az oldal képviselőjéhez.";
$error_text_400 = "Érvénytelen kérés.";
$error_text_401 = "A kéréshez engedély szükséges.";
$error_text_403 = "A művelet tiltott.";
$error_text_404 = "Az oldal nem található.";
$error_text_500 = "Szerver konfigurációs hiba.";

$styles_entry_text_C1A_2025 = "A nevezőknek MEG KELL adniuk a szénsav- és édességi szintet egyaránt. A nevezők MEGADHATJÁK az almafajtákat, különösen ha azok szokatlan jellemzőket hoznak.";
$styles_entry_text_C1B_2025 = "A nevezőknek MEG KELL adniuk a szénsav- és édességi szintet egyaránt. A nevezők MEGADHATJÁK a felhasznált almafajtákat; ha megadják, fajtajelleg várható.";
$styles_entry_text_C1C_2025 = "A nevezőknek MEG KELL adniuk a szénsavszintet. A nevezőknek MEG KELL adniuk az édességet, amely száraztól félédesig terjedhet. A nevezők MEGADHATJÁK a felhasznált almafajtákat; ha megadják, fajtajelleg várható.";
$styles_entry_text_C1D_2025 = "A nevezőknek MEG KELL adniuk a szénsavszintet. A nevezőknek MEG KELL adniuk az édességet, amely közepestől édesig terjedhet. A nevezők MEGADHATJÁK a felhasznált almafajtákat; ha megadják, fajtajelleg várható.";
$styles_entry_text_C1E_2025 = "A nevezőknek MEG KELL adniuk a szénsavszintet. A nevezőknek MEG KELL adniuk az édességet, amely száraztól közepesig terjedhet. A nevezők MEGADHATJÁK a felhasznált almafajtákat; ha megadják, fajtajelleg várható.";
$styles_entry_text_C2A_2025 = "A nevezőknek MEG KELL adniuk, hogy az almabor hordóban erjesztett vagy érlelt volt-e. A nevezőknek MEG KELL adniuk a szénsav- és édességi szintet egyaránt.";
$styles_entry_text_C2B_2025 = "A nevezőknek MEG KELL adniuk a szénsav- és édességi szintet egyaránt.";
$styles_entry_text_C2C_2025 = "A nevezőknek MEG KELL adniuk a kezdő fajsúlyt, a végső fajsúlyt vagy maradék cukrot, és az alkoholszintet. A nevezőknek MEG KELL adniuk a szénsavszintet.";
$styles_entry_text_C2D_2025 = "A nevezőknek MEG KELL adniuk a kezdő fajsúlyt, a végső fajsúlyt vagy maradék cukrot, és az alkoholszintet. A nevezőknek MEG KELL adniuk a szénsavszintet.";
$styles_entry_text_C3A_2025 = "A nevezőknek MEG KELL adniuk a szénsav- és édességi szintet egyaránt. A nevezőknek MEG KELL adniuk az összes hozzáadott gyümölcsöt vagy gyümölcslevet. A nevezők MEGADHATNAK egy alap almabor stílust. A nevezők MEGADHATJÁK a hozzáadott gyümölcs színét.";
$styles_entry_text_C3B_2025 = "A nevezőknek MEG KELL adniuk a szénsav- és édességi szintet egyaránt. A nevezőknek MEG KELL adniuk az összes hozzáadott fűszert. Ha komlót használnak, a nevezőnek MEG KELL adnia a fajtákat. A nevezők MEGADHATNAK egy alap almabor stílust.";
$styles_entry_text_C3C_2025 = "A nevezőknek MEG KELL adniuk azokat az összetevőket vagy eljárásokat, amelyek a nevezést kísérleti almaborrá teszik. A nevezőknek MEG KELL adniuk a szénsav- és édességi szintet egyaránt. A nevezők MEGADHATNAK egy alapstílust, vagy részletesebb leírást adhatnak a koncepcióról.";
$styles_entry_text_C4A_2025 = "A nevezőknek MEG KELL adniuk a szénsav- és édességi szintet egyaránt.";
$styles_entry_text_C4B_2025 = "A nevezőknek MEG KELL adniuk a szénsav- és édességi szintet egyaránt.";
$styles_entry_text_C4C_2025 = "A nevezőknek MEG KELL adniuk a kezdő fajsúlyt, a végső fajsúlyt vagy maradék cukrot, és az alkoholszintet. A nevezőknek MEG KELL adniuk a szénsavszintet.";
$styles_entry_text_C4D_2025 = "A nevezőknek MEG KELL adniuk azokat az összetevőket vagy eljárásokat, amelyek a nevezést kísérleti körteborrá teszik. A nevezőknek MEG KELL adniuk a szénsav- és édességi szintet egyaránt. A nevezők MEGADHATNAK egy alapstílust, vagy részletesebb leírást adhatnak a koncepcióról.";

$login_text_028 = "A megadott felhasználónév nem található. Kérjük, ellenőrizze, és próbálja újra.";
$login_text_029 = "Ha nem emlékszik a felhasználónevére, forduljon az oldal adminisztrátorához.";
$login_text_030 = "A megadott e-mail cím érvénytelen. Kérjük, ellenőrizze, és próbálja újra.";

$entry_info_text_056 = "Korlát elérve. Nem fogadunk el több nevezést.";
$entry_info_text_057 = "<i class=\"fa fa-times-circle text-danger-emphasis me-1\"></i>Jelzi, hogy a stílus nevezési korlátja elérésre került, és az adott stílusban nem fogadunk el több nevezést.";

$output_text_034 = "Nem került sor BOS bírálói pontok kiosztására, mivel az összesített bírált nevezések száma kevesebb volt, mint a BJCP által meghatározott 30-as minimum küszöb.";

/**
 * ------------------------------------------
 * Version 3.0.1 Additions
 * ------------------------------------------
 */

$label_medal_category = "Éremkategória";
$label_entry_limit_medal_category = "Nevezési korlátok éremkategóriánként";
$label_practice_session = "Gyakorló időszak";
$label_practice_beer = "Gyakorló sör";
$label_practice_cider = "Gyakorló almabor";
$label_practice_mead = "Gyakorló mézsör";
$label_practice_entry = "Gyakorló nevezés";
$label_scoresheet_practice = "Pontozólap gyakorlat";

$entry_info_text_058 = "A helyszín és címe megtekintéséhez jelentkezzen be, ha elérhető.";

/**
 * ------------------------------------------
 * END TRANSLATIONS
 * ------------------------------------------
 *
 * ------------------------------------------
 * Various conditionals
 * No translations below this line
 * ------------------------------------------
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

?>
