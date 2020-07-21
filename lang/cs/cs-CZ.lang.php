<?php
/**
 * Module:      cs-CZ.lang.php
 * Description: This module houses all display text in the Czech language.
 * Updated:     July 10, 2020
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
		if (($judge_limit) && (!$steward_limit)) $j_s_text = "Obsluha"; // missing punctuation intentional
		elseif ((!$judge_limit) && ($steward_limit)) $j_s_text = "Degustátor"; // missing punctuation intentional
		else $j_s_text = "Obsluha nebo degustátor"; // missing punctuation intentional
	}
}

// -------------------- Global Labels - mostly used for titles and navigation --------------------
// All labels are capitalized and without punctuation

$label_home = "Domů";
$label_welcome = "Vítejte";
$label_comps = "Seznam soutěží";
$label_info = "Informace";
$label_volunteers = "Dobrovolníci";
$label_register = "Registrace";
$label_help = "Nápověda";
$label_print = "Tisk";
$label_my_account = "Můj účet";
$label_yes = "Ano";
$label_no = "Ne";
$label_low_none = "Nízký/žádný";
$label_low = "Nízký";
$label_med = "Střední";
$label_high = "Vysoký";
$label_pay = "Platba přihlášky";
$label_reset_password = "Obnovit heslo";
$label_log_in = "Přihlásit se";
$label_logged_in = "Přihlášen";
$label_log_out = "Odhlásit se";
$label_logged_out = "Odhlášen";
$label_sponsors = "Sponzoři";
$label_rules = "Pravidla";
$label_volunteer_info = "Informace pro dobrovolníky";
$label_reg = $label_register;
$label_judge_reg = "Registrace degustátorů";
$label_steward_reg = "Registrace obsluhy";
$label_past_winners = "Dřívější výherci";
$label_contact = "Kontakt";
$label_style = "Styl";
$label_entry = "Vzorek";
$label_add_entry = "Přihlásit vzorek";
$label_edit_entry = "Upravit vzorek";
$label_upload = "Nahrát";
$label_bos = "Best of Show";
$label_brewer = "Sládek";
$label_cobrewer = "Podsládek";
$label_entry_name = "Název vzorku";
$label_required_info = "Požadované údaje";
$label_character_limit = "znakový limit. Použijte klíčová slova a zkratky, pokud vám nestačí místo.<br>Použito znaků: ";
$label_carbonation = "Nasycení";
$label_sweetness = "Sladkost";
$label_strength = "Síla";
$label_color = 	"Barva";
$label_table = "Stolní";
$label_standard = "Standard";
$label_super = "Super";
$label_session = "Session";
$label_double = "Double";
$label_blonde = "Blonde";
$label_amber = "Polotmavé";
$label_brown = "Hnědé";
$label_pale = "Světlé";
$label_dark = "Tmavé";
$label_hydromel = "Hydromel";
$label_sack = "Sáček";
$label_still = "Neperlivý";
$label_petillant = "Jemně perlivý";
$label_sparkling = "Perlivé";
$label_dry = "Suchý";
$label_med_dry = "Polosuchý";
$label_med_sweet = "Polosladký";
$label_sweet = "Sladký";
$label_brewer_specifics = "Zvláštnosti vzorku";
$label_general = "Všeobecně";
$label_amount_brewed = "Uvařené množství";
$label_specific_gravity = "Původní stupňovitost";
$label_fermentables = "Zkvasitelné přísady";
$label_malt_extract = "Sladový výtažek";
$label_grain = "Slad";
$label_hops = "Chmel";
$label_hop = "Chmel";
$label_mash = "Rmutované";
$label_steep = "Máčené";
$label_other = "Ostatní";
$label_weight = "Hmotnost";
$label_use = "Použití";
$label_time = "Čas";
$label_first_wort = "Předek";
$label_boil = "Chmelovar";
$label_aroma = "Aroma";
$label_dry_hop = "Dry Hop";
$label_type = "Druh";
$label_bittering = "Hořký";
$label_both = "Aromaticko-hořký";
$label_form = "Forma";
$label_whole = "Hlávky";
$label_pellets = "Granule";
$label_plug = "Lisovaný";
$label_extract = "Extrakt";
$label_date = "Datum";
$label_bottled = "Lahvováno";
$label_misc = "Miscellaneous";
$label_minutes = "Minut";
$label_hours = "Hodin";
$label_step = "Krok";
$label_temperature = "Teplota";
$label_water = "Voda";
$label_amount = "Množství";
$label_yeast = "Kvasnice";
$label_name = "Jméno";
$label_manufacturer = "Výrobce";
$label_nutrients = "Živiny";
$label_liquid = "Tekuté";
$label_ale = "Svrchní";
$label_lager = "Spodní";
$label_wine = "Víno";
$label_champagne = "Šampaňské";
$label_boil = "Chmelovar";
$label_fermentation = "Kvašení";
$label_finishing = "Finishing";
$label_finings = "Čiřidla";
$label_primary = "Hlavní kvašení";
$label_secondary = "Dokvašování";
$label_days = "Dnů";
$label_forced = "Nuceně syceno CO2";
$label_bottle_cond = "Dokvašováno v lahvi";
$label_volume = "Objem";
$label_og = "Původní stupňovitost";
$label_fg = "Konečná stupňovitost";
$label_starter = "Zákvas";
$label_password = "Heslo";
$label_judging_number = "Judging Number";
$label_check_in = "Check In Entry";
$label_box_number = "Box Number";
$label_first_name = "Jméno";
$label_last_name = "Příjmení";
$label_secret_01 = "Jakou značku piva máte nejraději?";
$label_secret_02 = "Jak se jmenoval váš první domácí mazlíček?";
$label_secret_03 = "Na jaké ulici jste vyrostli?";
$label_secret_04 = "Jakého maskota měla vaše středí škola?";
$label_security_answer = "Odpověď na bezpečnostní otázku";
$label_security_question = "Bezpečnostní otázka";
$label_judging = "Degustace";
$label_judge = "Degustátor";
$label_steward = "Obsluha";
$label_account_info = "Údaje účtu";
$label_street_address = "Ulice";
$label_address = "Adresa";
$label_city = "Město";
$label_state_province = "Stát/kraj";
$label_zip = "PSČ";
$label_country = "Země";
$label_phone = "Telefon";
$label_phone_primary = "Hlavní telefon";
$label_phone_secondary = "Vedlejší telefon";
$label_drop_off = "Místo příjmu vzorků";
$label_drop_offs = "Místa příjmu vzorků";
$label_club = "Klub";
$label_aha_number = "Číslo člena AHA";
$label_org_notes = "Poznámky organizátorovi";
$label_avail = "Dostupnost";
$label_location = "Místo";
$label_judging_avail = "Dostupnost degustace";
$label_stewarding = "Obsluha";
$label_stewarding_avail = "Dostupnost obsluhy";
$label_bjcp_id = "BJCP ID";
$label_bjcp_mead = "Degustátor medoviny";
$label_bjcp_rank = "Úroveň BJCB";
$label_designations = "Zkušenosti";
$label_judge_sensory = "Degustátor s degustačními zkouškami";
$label_judge_pro = "Profesionální sládek";
$label_judge_comps = "Posouzených soutěží";
$label_judge_preferred = "Preferované styly";
$label_judge_non_preferred = "Nepreferované styl";
$label_waiver = "Vyloučení odpovědnosti";
$label_add_admin = "Přidat údaje správce";
$label_add_account = "Přidat údaje účtu";
$label_edit_account = "Upravit účet";
$label_entries = "Vzorků";
$label_confirmed = "Potvrzeno";
$label_paid = "Uhrazeno";
$label_updated = "Aktualizováno";
$label_mini_bos = "Mini-BOS";
$label_actions = "Akce";
$label_score = "Skóre";
$label_winner = "Vítěz?";
$label_change_email = "Změnit email";
$label_change_password = "Změnit heslo";
$label_add_beerXML = "Přidat vzorek pomocí BeerXML";
$label_none_entered = "Nezadáno";
$label_none = "Žádné";
$label_discount = "Sleva";
$label_subject = "Předmět";
$label_message = "Zpráva";
$label_send_message = "Odeslat zprávu";
$label_email = "Emailová adresa";
$label_account_registration = "Registrace účtů:";
$label_entry_registration = "Registrace vzorků:";
$label_entry_fees = "Poplatky za vzorek";
$label_entry_limit = "Omezení počtu vzorků";
$label_entry_info = "Informace o vzorku";
$label_entry_per_entrant = "Omezení počtu vzorků na účastníka";
$label_categories_accepted = "Přijímané styly";
$label_judging_categories = "Degustační kategorie";
$label_entry_acceptance_rules = "Pravidla příjmu vzorků";
$label_shipping_info = "Zasílání poštou";
$label_packing_shipping = "Balení a zasílání";
$label_awards = "Ceny";
$label_awards_ceremony = "Udílení cen";
$label_circuit = "Circuit Qualification";
$label_hosted = "Hostovaná verze";
$label_entry_check_in = "Entry Check-In";
$label_cash = "Hotovost";
$label_check = "Šek";
$label_pay_online = "Platba online";
$label_cancel = "Zrušit";
$label_understand = "Rozumím";
$label_fee_discount = "Zlevněná přihláška";
$label_discount_code = "Slevový kód";
$label_register_judge = "Registrujete se jako soutěžící, degustátor nebo obsluha?";
$label_register_judge_standard = "Zaregistrovat se jako degustátor nebo obsluha (standardně)";
$label_register_judge_quick = "Zaregistrovat se jako degustátor nebo obsluha (zrychleně)";
$label_all_participants = "Všichni účastníci";
$label_open = "Otevřeno";
$label_closed = "Uzavřeno";
$label_judging_loc = "Místa a data degustace";
$label_new = "Nový";
$label_old = "Starý";
$label_sure = "Jste si jisti?";
$label_judges = "Degustátoři";
$label_stewards = "Obsluha";
$label_staff = "Personál";
$label_category = "Kategorie";
$label_delete = "Smazat";
$label_undone = "Toto nelze vzít zpět";
$label_bitterness = "Hořkost";
$label_close = "Uzavřít";
$label_custom_style = "Vlastní styl";
$label_custom_style_types = "Vlastní typy stylů";
$label_assigned_to_table = "Přiřazen ke stolu";
$label_organizer = "Organizátor";
$label_by_table = "Podle stolu";
$label_by_category = "Podle stylu";
$label_by_subcategory = "Podle podstylu";
$label_by_last_name = "Podle příjmení";
$label_by_table = "Podle stolu";
$label_by_location = "Podle místa degustace";
$label_shipping_entries = "Zaslání vzorků";
$label_no_availability = "Dostupnost není definována";
$label_error = "Chyba";
$label_round = "Kolo";
$label_flight = "Sada vzorků";
$label_rounds = "Kola";
$label_flights = "Sady vzorků";
$label_sign_in = "Přihlásit se";
$label_signature = "Podpis";
$label_assignment = "Přiřazení";
$label_assignments = "Přiřazení";
$label_letter = "Letter";
$label_re_enter = "Re-Enter";
$label_website = "Webová stránka";
$label_place = "Místo";
$label_cheers = "Na zdraví";
$label_count = "Počet";
$label_total = "Celkem";
$label_participant = "Účastník";
$label_entrant = "Účastník";
$label_received = "Přijato";
$label_please_note = "Vezměte prosím na vědomí";
$label_pull_order = "Pull Order";
$label_box = "Box";
$label_sorted = "Seřazeno";
$label_subcategory = "Podkategorie";
$label_affixed = "Štítek přichycen?";
$label_points = "Bodů";
$label_comp_id = "ID soutěže BJCP";
$label_days = "Dnů";
$label_sessions = "Degustací";
$label_number = "Číslo";
$label_more_info = "Další informace";
$label_entry_instructions = "Instrukce k přihlašování";
$label_commercial_examples = "Komerční příklady";
$label_users = "Uživatelé";
$label_participants = "Účastníci";
$label_please_confirm = "Prosím potvrďte";
$label_undone = "Toto nelze vzít zpět.";
$label_data_retain = "Data k zachování";
$label_comp_portal = "Adresář soutěží";
$label_comp = "Soutěž";
$label_continue = "Pokračovat";
$label_host = "Hostitel";
$label_closing_soon = "Brzy se uzavře";
$label_access = "Přístup";
$label_length = "Délka";

// Admin
$label_admin = "Správa";
$label_admin_short = "Správa";
$label_admin_dashboard = "Nástěnka";
$label_admin_judging = $label_judging;
$label_admin_stewarding = "Obsluha";
$label_admin_participants = $label_participants;
$label_admin_entries = "Vzorky";
$label_admin_comp_info = "Informace o soutěži";
$label_admin_web_prefs = "Nastavení webu";
$label_admin_judge_prefs = "Nastavení předvoleb soutěže";
$label_admin_archives = "Archiv";
$label_admin_style = $label_style;
$label_admin_styles = "Styly";
$label_admin_dropoff = $label_drop_offs;
$label_admin_judging_loc = $label_judging_loc;
$label_admin_contacts = "Kontakty";
$label_admin_tables = "Stoly";
$label_admin_scores = "Skóre";
$label_admin_bos = $label_bos;
$label_admin_bos_acr = "BOS";
$label_admin_style_types = "Typy stylů";
$label_admin_custom_cat = "Vlastní kategorie";
$label_admin_custom_cat_data = "Položky vlastní kategorie";
$label_admin_sponsors = $label_sponsors;
$label_admin_entry_count = "Počet vzorků podle stylu";
$label_admin_entry_count_sub = "Počet vzorků podle podstylu";
$label_admin_custom_mods = "Vlastní moduly";
$label_admin_check_in = $label_entry_check_in;
$label_admin_make_admin = "Upravit uživatelskou úroveň";
$label_admin_register = "Přihlásit účastníka";
$label_admin_upload_img = "Nahrát obrázky";
$label_admin_upload_doc = "Nahrát scoresheety a další dokumenty";
$label_admin_password = "Změnit uživatelské heslo";
$label_admin_edit_account = "Upravit uživatelský účet";

// Sidebar Labels
$label_account_summary = "Přehled mého účtu";
$label_confirmed_entries = "Potvrzených vzorků";
$label_unpaid_confirmed_entries = "Neuhrazených potvrzených vzorků";
$label_total_entry_fees = "Celkové poplatky";
$label_entry_fees_to_pay = "Poplatky k úhradě";
$label_entry_drop_off = "Entry Drop-Off";

// v2.1.9
$label_maintenance = "Údržba";
$label_judge_info = "Informace o degustátorovi";
$label_cellar = "Můj sklep";
$label_verify = "Ověřit";
$label_entry_number = "Číslo vzorku";

// -------------------- Headers --------------------
// Missing punctuation intentional for all
$header_text_000 = "Instalace byla úspěšná.";
$header_text_001 = "Jste nyní přihlášeni, vše je připraveno pro další nastavení webu vaší soutěže.";
$header_text_002 = "Bohužel, oprávnění k vašemu config.php se nepodařilo změnit.";
$header_text_003 = "Je důrazně doporučeno změnit oprávnění (chmod) config.php na 555. Abyste to mohli provést, musíte mít přístup k souboru na vašem serveru.";
$header_text_004 = "Navc, proměnná &#36;setup_free_access v config.php je momentálně nastavena na TRUE. Z bezpečnostních důvodů by měla být upravena zpět na FALSE. Je třeba, abyste config.php upravili ručně a nahráli jej znovu na server.";
$header_text_005 = "Informace úspěšně přidány.";
$header_text_006 = "Informace úspěšně upraveny.";
$header_text_007 = "Chyba.";
$header_text_008 = "Prosím, zkuste to znovu.";
$header_text_009 = "Abyste mohli přistoupit ke funkcím správce, musíte být správcem.";
$header_text_010 = "Změnit";
$header_text_011 = $label_email;
$header_text_012 = $label_password;
$header_text_013 = "Poskytnutá emailová adresa se již používá, prosím zadejte jinou.";
$header_text_014 = "Při zpracování posledního požadavku nastaly potíže, zkuste to prosím znovu.";
$header_text_015 = "Vaše současné heslo je chybné.";
$header_text_016 = "Prosím, zadejte emailovou adresu.";
$header_text_017 = "Omlouváme se, při pokusu o přihlášení nastaly potíže.";
$header_text_018 = "Omlouváme se, zadané uživatelské jméno je již obsazeno.";
$header_text_019 = "Nemáte již založený účet s tímto jménem?";
$header_text_020 = "Pokud je tomu tak, přihlaste se zde.";
$header_text_021 = "Zadané uživatelské jméno není platnou emailovou adresou.";
$header_text_022 = "Prosím, zadejte platnou emailovou adresu.";
$header_text_023 = "Neplatná CAPTCHA.";
$header_text_024 = "Zadané emailové adresy se neshodují.";
$header_text_025 = "Zadané číslo AHA se již používá.";
$header_text_026 = "Vaše online platba byla přijata a transakce ukončena. Upozorňujeme, že to může pár minut trvat, než se stav vaší platby změní i u nás v systému - obnovte tuto stránku nebo si otevřete seznam vašich vzorků. Potvrzení platby obdržíte emailem od PayPalu.";
$header_text_027 = "Prosím, vytiskněte toto potvrzení a přiložte je k jednomu z vašich vzorků jako osvědčení o úhradě.";
$header_text_028 = "Vaše online platba byla zrušena.";
$header_text_029 = "Kód byl ověřen.";
$header_text_030 = "Omlouváme se, zadaný kód je neplatný.";
$header_text_031 = "Musíte se přihlásit a disponovat právy správce abyste mohli používat správcovské funkce.";
$header_text_032 = "Omlouváme se, při posledním pokusu o přihlášení došlo k potížím.";
$header_text_033 = "Ujistěte se, že vaše emailová adresa a heslo jsou správné.";
$header_text_034 = "Emailem, na adresu přiřazenou k vašemu účtu, jsme zaslali vygenerovaný kód pro obnovení hesla.";
$header_text_035 = "- nyní se můžete přihlásit současným uživatelským jménem a novým heslem.";
$header_text_036 = "Byli jste odlášeni.";
$header_text_037 = "Chcete se znovu přihlásit?";
$header_text_038 = "Vaše ověřovací otázka se neshoduje s tou v naší databázi.";
$header_text_039 = "Informace k ověření identity byly zaslány na emailovou adresu spojenou s vašim účtem.";
$header_text_040 = "Vaše zpráva byla odeslána na";
$header_text_041 = $header_text_023;
$header_text_042 = "Vaše emailová adresa byla aktualizována.";
$header_text_043 = "Vaše heslo bylo aktualizováno.";
$header_text_044 = "Informace úspěšně smazány.";
$header_text_045 = "Veškeré vzorky naimportované z BeerXML byste měli zkontrolovat.";
$header_text_046 = "Registrace proběhla.";
$header_text_047 = "Dosáhli jste maximálního počtu přihlášek.";
$header_text_048 = "Vaše přihláška nebyla vložena.";
$header_text_049 = "Dosáhli jste maximálního počtu přihlášek pro podkategorii.";
$header_text_050 = "Instalace: Instalace databázových tabulek a dat";
$header_text_051 = "Instalace: Vytvoření účtu správce";
$header_text_052 = "Instalace: Přidání údajů o správci";
$header_text_053 = "Instalace: Úprava předvoleb stránek";
$header_text_054 = "Instalace: Přidání údajů soutěže";
$header_text_055 = "Instalace: Přidání míst degustace";
$header_text_056 = "Instalace: Přidání místa příjmu vzorků";
$header_text_057 = "Instalace: Výběr přijímaných stylů";
$header_text_058 = "Instalace: Nastavení předvoleb degustace";
$header_text_059 = "Vložit vzorek za pomocí BeerXML";
$header_text_060 = "Váš vzorek byl zaznamenán.";
$header_text_061 = "Váš vzorek byl potvrzen.";
$header_text_065 = "Všechny přijaté vzorky byly zkontrolovány a ty, které ještě neměly přiřazený stůl, byly přiřazeny.";
$header_text_066 = "Údaje úspěšně aktualizovány.";
$header_text_067 = "Přípona, kterou jste zadali, se již používá. Prosím, zvolte jinou.";
$header_text_068 = "Údaje vybrané soutěže byly smazány.";
$header_text_069 = "Archivy byly úspěšně vytvořeny. ";
$header_text_070 = "Klikněte na název archivu pro jeho zobrazení.";
$header_text_071 = "Nezapomeňte zaktualizovat ".$label_admin_comp_info." a ".$label_admin_judging_loc." při spuštění nové soutěže.";
$header_text_072 = "Archiv \"".$filter."\" byl smazán.";
$header_text_073 = "Záznamy byly zaktualizovány.";
$header_text_074 = "Zadané uživatelské jméno je již obsazeno.";
$header_text_075 = "Přida další místo příjmu vzorků?";
$header_text_076 = "Přidat další místo degustace, datum nebo čas?";
$header_text_077 = "Stůl, který jste právě vytvořili, nemá přiřazené žádné styly.";
$header_text_078 = "Jeden nebo více povinných údajů chybí - červeně zvýrazněno níže.";
$header_text_079 = "Zadané emailové adresy se neshodují.";
$header_text_080 = "Zadané číslo AHA je již uloženo u jiného účtu.";
$header_text_081 = "Všechny vzorky byly označeny jako zaplacené.";
$header_text_082 = "Všechny vzorky byly označeny jako doručené.";
$header_text_083 = "Všechny nepotvrzené vzorky byly nyní označeny jako potvrzené.";
$header_text_084 = "Všechna přiřazení účastníků byla smazána.";
$header_text_085 = "Zadané degustační číslo nebylo v databázi nalezeno.";
$header_text_086 = "Všechny styly vzorků byly převedeny z BJCP 2008 na BJCP 2015.";
$header_text_087 = "Data úspěšně smazána.";
$header_text_088 = "Osoba degustátora/obsluhy byla úspěšně přidána. Nezapomeňte zvolit, zda jde o degustátora nebo obsluhu před přiřazením ke stolu.";
$header_text_089 = "Soubor úspěšně nahrán. Výsledek si můžete ověřit v seznamu.";
$header_text_090 = "Soubor, který jste se pokusili nahrát, není mezi povolenými druhy souborů.";
$header_text_091 = "Soubor(y) úspěšně smazán(y).";
$header_text_092 = "Odeslali jsme vám testovací email. Zkontrolujte si složku spamu.";
$header_text_093 = "Heslo uživatele bylo změněno, prosím informujte jej o jeho znění!";
$header_text_094 = "Změna oprávnění složky user_images na 755 selhala.";
$header_text_095 = "Musíte změnit oprávnění složky ručně. Nahlédněte prosím do dokumentace příkazu chmod (změna oprávnění) svého FTP programu nebo hostingu.";
$header_text_096 = "Degustační čísla byla znovu vytvořena.";
$header_text_097 = "Vaše instalace byla úspěšně dokončena!";
$header_text_098 = "Z BEZPEČNOSTNÍCH DŮVODŮ ihned změňte proměnnou &#36;setup_free_access v config.php na FALSE.";
$header_text_099 = "Jinak je vaše instalace a server v nebezpečí útoku zvenčí.";
$header_text_100 = "Nyní se přihlaste pro otevření nástěnky správce";
$header_text_101 = "Vaše instalace byla úspěšně zaktualizována!";
$header_text_102 = "Emailové adresy se neshodují.";
$header_text_103 = "Prosím, přihlaste se pro přístup k vašemu účtu.";
$header_text_104 = "Nedisponujete dostatečnými oprávněními pro zobrazení této stránky.";
$header_text_105 = "Pro příjmutí a potvrzení vašeho vzorku je třeba více informací.";
$header_text_106 = "Prohlédněte si ČERVENĚ zvýrazněné oblasti níže.";
$header_text_107 = "Prosím, zvolte styl.";
$header_text_108 = "Tento vzorek nelze příjmout ani potvrdit, dokud nebyl vybrán styl. Nepotvrzené vzorky mohou být ze systému smazány bez předchozího upozornění.";

// v2.1.9
$header_text_109 = "Byli jste zaregistrováni jako obsluha.";
$header_text_110 = "Všechny vzorky byly označeny jako neuhrazené.";
$header_text_111 = "Všechny vzorky byly označeny jako nepřijaté.";

// -------------------- Navigation --------------------



// -------------------- Alerts --------------------
$alert_text_000 = "Oprávnění nejvyššího správce a správce přidělujte uživatelům s opatrností.";
$alert_text_001 = "Vyčištění dat dokončeno.";
$alert_text_002 = "Proměnná &#36;setup_free_access v config.php je momentálně nastavena na TRUE.";
$alert_text_003 = "Z bezpečnostních důvodů je třeba ji vrátit zpět na FALSE. Musíte ručně upravit config.php a nahrát jej na server.";
$alert_text_005 = "Nebyla zvolena žádná místa příjmu vzorků.";
$alert_text_006 = "Přidat místo příjmu vzorků?";
$alert_text_008 = "Nebyla zvolena žádná místa a data degustace.";
$alert_text_009 = "Přidat místo degustace?";
$alert_text_011 = "Nebyly přidány žádné kontakty na soutěž.";
$alert_text_012 = "Přidat kontakt na soutěž?";
$alert_text_014 = "Zvolené styly jsou nyní podle BJCP 2008.";
$alert_text_015 = "Chcete všechny vzorky převést na BJCP 2015?";
$alert_text_016 = "Jste si jisti? Tento úkon převede všechny vzorky v databázi tak, aby vyhovovaly pravidlům BJCP pro rok 2015. Kategorie budou, pokud to bude možné, převedeny 1:1, některé zvláštní styly však může být nutné upravit ručně soutěžícím.";
$alert_text_017 = "Abyste zachovali funkčnost, převod musí být proveden <em>před</em> definicí stolů.";
$alert_text_019 = "Všechny nepotvrzené vzorky byly z databáze smazány.";
$alert_text_020 = "Nepotvrzené vzorky jsou níže zvýrazněny a uvozeny ikonou <span class=\"fa fa-lg fa-exclamation-triangle text-danger\"></span>.";
$alert_text_021 = "Měli byste kontaktovat soutěžící s níže uvedenými vzorky. Tyto vzorky nejsou zahrnuty ve výpočtu poplatků.";
$alert_text_023 = "Přidat místo příjmu vzorků?";
$alert_text_024 = $label_yes;
$alert_text_025 = $label_no;
$alert_text_027 = "Příjem vzorků ještě nezačal.";
$alert_text_028 = "Příjem vzorků již skončil.";
$alert_text_029 = "Přidání vzorku není možné.";
$alert_text_030 = "Limit počtu vzorků byl dosažen.";
$alert_text_031 = "Limit personálu byl dosažen.";
$alert_text_032 = "Vzorky bude možné registrovat od ".$entry_open.".";
$alert_text_033 = "Soutěžící se mohou registrovat od ".$reg_open.".";
$alert_text_034 = "Prosím, stavte se později, abyste se mohli zaregistrovat.";
$alert_text_036 = "Registrace vzorků se otevře ".$entry_open.".";
$alert_text_037 = "Prosím, stavte se později, abyste mohli zaregistrovat své vzorky.";
$alert_text_039 = "Registrace personálu se otevře ".$judge_open.".";
$alert_text_040 = "Prosím, stavte se později, abyste se mohli zaregistrovat jako degustátor nebo obsluha.";
$alert_text_042 = "Registrace vzorků je otevřena!";
$alert_text_043 = "Do ".$current_time." jsme zatím přijali ".$total_entries." přihlášek.";
$alert_text_044 = "Registrace se uzavře ";
$alert_text_046 = "Maximální počet vzorků je téměř dosažen!";
$alert_text_047 = "Do ".$current_time." bylo vloženo celkem ".$total_entries." vzorků z maxima ".$row_limits['prefsEntryLimit'].".";
$alert_text_049 = "Maximální počet vzorků byl dosažen.";
$alert_text_050 = "Maximální počet vzorků ".$row_limits['prefsEntryLimit']." byl dosažen. Registrace vzorků byla uzavřena.";
$alert_text_052 = "Maximální počet uhrazených vzorků byl dosažen.";
$alert_text_053 = "Maximální počet <em>uhrazených</em> vzorků ".$row_limits['prefsEntryLimitPaid']." byl dosažen. Registrace vzorků byla uzavřena.";
$alert_text_055 = "Registrace vzorků je uzavřena.";
$alert_text_056 = "Pokud jste již zaregistrováni,";
$alert_text_057 = "přihlaste se"; // lower-case and missing punctuation intentional
$alert_text_059 = "Registrace vzorků je uzavřena.";
$alert_text_060 = "Celkem bylo do systému vloženo ".$total_entries." vzorků.";
$alert_text_062 = "Místo příjmu vzorků je uzavřeno.";
$alert_text_063 = "Vzorky již nelze na místech příjmu vzorků předat.";
$alert_text_065 = "Zasílání vzorků je uzavřeno.";
$alert_text_066 = "Zaslané vzorky již nelze na poštovní adrese příjmout.";
$alert_text_068 = $j_s_text." je otevřena.";
$alert_text_069 = "Zaregistrovat"; // missing punctuation intentional
$alert_text_070 = "Registrace ".$j_s_text." se uzavře ".$judge_closed.".";
$alert_text_072 = "Maximální počet degustátorů byl dosažen.";
$alert_text_073 = "Další degustátoři již nebudou přijímáni.";
$alert_text_074 = "Stále se můžete zaregistrovat jako obsluha.";
$alert_text_076 = "Maximální počet obsluhujících byl dosažen.";
$alert_text_077 = "Další registrace obsluhy již nebudou přijímány.";
$alert_text_078 = "Stále se můžete zaregistrovat jako degustátor.";
$alert_text_080 = "Chybné heslo.";
$alert_text_081 = "Heslo přijato.";

$alert_email_valid = "Formát emailu je platný!";
$alert_email_not_valid = "Formát email je neplatný!";
$alert_email_in_use = "Zadaná emailová adresa se již používá, prosím zvolte jinou.";
$alert_email_not_in_use = "Gratulujeme! Zadaná emailová adresa je volná.";

// ----------------------------------------------------------------------------------
// Public Pages
// ----------------------------------------------------------------------------------

// v2.1.9
$comps_text_000 = "Prosím, zvolte v seznamu níže, kterou soutěž chcete otevřít.";
$comps_text_001 = "Aktuální soutěž:";
$comps_text_002 = "Momentálně nejsou k dispozici žádné soutěže s otevřeným přihlašováním.";
$comps_text_003 = "Momentálně nejsou k dispozici žádné soutěže s přihlašováním, které se uzavírá během následujících 7 dnů.";

// -------------------- BeerXML --------------------

$beerxml_text_000 = "Import vzorků není dostupný.";
$beerxml_text_001 = "byl nahrán a pivo přidáno do vašeho seznamu vzorků.";
$beerxml_text_002 = "Omlouváme se, ale tento druh souboru není povolen. Povoleny jsou pouze soubory s příponou .xml.";
$beerxml_text_003 = "Velikost soubor je přes 2 MB. Prosím, upravte velikost a zkuste to znovu.";
$beerxml_text_004 = "Vybrán neplatný soubor.";
$beerxml_text_005 = "Nebyl však ještě potvrzen. Pro potvrzení vzorku si otevřete seznam vzorků a následujte pokyny. Taktéž můžete nahrát další vzorek v BeerXML níže.";
$beerxml_text_006 = "Verze PHP na vašem serveru nepodporuje funkci importu BeerXML.";
$beerxml_text_007 = "Pro funkčnost je nutná verze PHP 5.x nebo vyšší &mdash; tento server běží na verzi ".$php_version.".";
$beerxml_text_008 = "Zvolte na svém disku platný soubor BeerXML a klikněte na <em>Nahrát</em>.";
$beerxml_text_009 = "Zvolte soubor BeerXML";
$beerxml_text_010 = "Soubor nevybrán...";
$beerxml_text_011 = "vzorků přidáno"; // lower-case and missing punctuation intentional
$beerxml_text_012 = "vzorek přidán"; // lower-case and missing punctuation intentional

// -------------------- Best of Show --------------------
// None


// -------------------- Brew (Add Entry) --------------------

if ($section == "brew") {
	$brew_text_000 = "Klikněte sem pro zobrazení podrobností stylu"; // missing punctuation intentional
	$brew_text_001 = "Degustátoři nebudou znát název vašeho vzorku.";
	$brew_text_002 = "[vypnuto - limit počtu vzorků ve stylu dosažen]"; // missing punctuation intentional
	$brew_text_003 = "[vypnuto - limit počtu vzorků uživatele ve stylu dosažen]"; // missing punctuation intentional
	$brew_text_004 = "Je požadován přesný druh, zvláštní suroviny, název klasického stylu, síla (u pivních stylů) nebo barva.";
	$brew_text_005 = "Zadání síly je povinné"; // missing punctuation intentional
	$brew_text_006 = "Zadání nasycení je povinné"; // missing punctuation intentional
	$brew_text_007 = "Zadání sladkosti je povinné"; // missing punctuation intentional
	$brew_text_008 = "Tento styl vyžaduje zadání podrobností k vašemu vzorku.";
	$brew_text_009 = "Požadavky na"; // missing punctuation intentional
	$brew_text_010 = "K tomuto stylu jsou požadovány doplňující informace. Prosím, zadejte je do nabízených polí.";
	$brew_text_011 = "Název vzorku je povinný.";
	$brew_text_012 = "***NEPOVINNÉ*** Uveďte, pouze pokud chcete, aby degustátoři vzali v úvahu to, co uvádíte, při posuzování a hodnocení vašeho vzorku. Uveďte pouze informace, které NENÍ MOŽNO ZADAT do jiných polí (např. způsob chmelení, druh chmele, druh medu, druh hroznů, druh hrušek, atd.)";
	$brew_text_013 = "NEPOUŽÍVEJTE toto pole pro uvedení zvláštních surovin, názvu klasického stylu, síly nebo barvy.";
	$brew_text_014 = "Použijte pouze, pokud chcete, aby tyto informace vzali degustátoři na vědomí při posuzování a hodnocení vašeho vzorku.";
	$brew_text_015 = "Druh výtažku (např. světlý, tmavý) nebo značka.";
	$brew_text_016 = "Druh sladu (plzeňský, pale ale, atd.)";
	$brew_text_017 = "Druh nebo název suroviny";
	$brew_text_018 = "Název chmelu.";
	$brew_text_019 = "Pouze čísla (např. 12.2, 6.6, atd.).";
	$brew_text_020 = "Název kmene (např. 1056 American Ale).";
	$brew_text_021 = "Wyeast, White Labs, atd.";
	$brew_text_022 = "1 balíček, 2 ampule, 2000 ml, atd.";
	$brew_text_023 = "Délka hlavního kvašení ve dnech.";
	$brew_text_024 = "Cukrotvorná teplota, atd.";
	$brew_text_025 = "Délka dokvašování ve dnech.";
	$brew_text_026 = "Délka dalšího kvašení ve dnech.";

}

// -------------------- Brewer (Account) --------------------

if (($section == "brewer") || ($section == "register") || ($section == "step2") || (($section == "admin") && ($go == "entrant")) || (($section == "admin") && ($go == "judge")) || (($section == "admin") && ($go == "steward"))) {
	$brewer_text_000 = "Prosím, zadejte jméno pouze <em>jedné</em> osoby.";
	$brewer_text_001 = "Zvolte jednu z otázek. Tato otázka bude použita pro ověření vaší identity když zapomente heslo.";
	$brewer_text_003 = "Abyste mohli být vybráni na GABF Pro-Am brewing opportunity, musíte být členem AHA.";
	$brewer_text_004 = "Uveďte jakékoli informace, které si myslíte, že by měl organizátor, koordinátor degustátorů nebo personál vědět (např. alergie, speciální výživové požadavky, velikost trička atd.).";
	$brewer_text_005 = "Nevyužiji; vzorky zasílám";
	$brewer_text_006 = "Chcete se účastnit jako degustátor a máte na to dostatečnou kvalifikaci?";
	$brewer_text_007 = "Absolvovali jste zkoušku BJCP Mead Judge?";
	$brewer_text_008 = "* Úroveň <em>Non-BJCP</em> je pro ty, kteří ještě neabsolvovali BJCP Beer Judge Entrance Exam a <em>nejsou</em> profesionálními sládky.";
	$brewer_text_009 = "** Úroveň <em>Provisional</em> je pro ty, kteří již absolvovali BJCP Beer Judge Entrance Exam, ale ještě neabsolvovali BJCP Beer Judging Exam.";
	$brewer_text_010 = "Na scoresheetech se zobrazí pouze první dvě role.";
	$brewer_text_011 = "Kolika soutěží jste se už zúčastnili jako <strong>".strtolower($label_judge)."</strong>?";
	$brewer_text_012 = "Pouze pro volbu obliby. Pokud necháte styl nezaškrtnutý, značí to, že jej můžete posuzovat. Není třeba zaškrtávat všechny styly, které byste mohli posuzovat.";
	$brewer_text_013 = "Klikněte nebo se dotkněte tlačítka výše pro rozbalení seznamu neoblíbených stylů.";
	$brewer_text_014 = "Není třeba označovat styly, ve kterých máte přihlášené vzorky; systém nepovolí, abyste byli přiřazeni ke stolu, kde by se nacházel váš vzorek.";
	$brewer_text_015 = "Jste ochotni se soutěže účastnit jako obsluha?";
	$brewer_text_016 = "Moje účast v této degustaci je zcela dobrovolná. Jsem si vědom(a) toho, že degustace zahrnuje konzumaci alkoholických nápojů a může ovlivnit moje vnímání a reakce.";
	$brewer_text_017 = "Klikněte nebo se dotkněte tlačítka výše pro rozbalení oblíbených pivních stylů.";
	$brewer_text_018 = "Zaškrtnutím tohoto políčka fakticky podepisuji právní dokument, kterým přijímám odpovědnost za svoje činy a chování a zbavuji organizátory, jednotlivě nebo společně, odpovědnosti za tyto činy a chování.";

	// v2.1.9
	$brewer_text_019 = "Pokud se chcete účastnit soutěže jako degustátor, klikněte nebo se dotkněte tlačítka výše pro zadání informací souvisejících s touto rolí.";
	$brewer_text_020 = "Jste ochotni se soutěže účastnit jako personál?";
	$brewer_text_021 = "Personál soutěže jsou osoby, které plní rozličné role v organizaci a uskutečnění soutěže před, během a po skončení degustace. Degustátoři a obsluha mohou taktéž být členy personálu. Pokud je soutěž uznaná BJCP, získají tito, po skončení soutěže, body do svého profilu.";

}

// -------------------- Contact --------------------

if ($section == "contact") {

	$contact_text_000 = "Pro kontaktování osob zodpovědných za soutěž využijte odkazy níže:";
	$contact_text_001 = "Pro kontaktování organizátora využijte formulář níže. Pole označená hvězdičkou jsou povinná.";
	$contact_text_002 = "Kopie byla odeslána na emailovou adresu, které jse uvedli.";
	$contact_text_003 = "Chcete odeslat další zprávu?";

}

// -------------------- Default (Home) -------------------

if (($section == "default") || ($section == "results")) { // Changed for 2.1.14

	$default_page_text_000 = "Nebyly zvolena žádná místa příjmu vzorků.";
	$default_page_text_001 = "Přidat místo příjmu vzorků?";
	$default_page_text_002 = "Nebyla zvolena žádná data/místa degustace.";
	$default_page_text_003 = "Přidat místo degustace?";
	$default_page_text_004 = "Výherci";
	$default_page_text_005 = "Výherci budou uvedeni od";
	$default_page_text_006 = "Vítejte";
	$default_page_text_007 = "Prohlédněte si podrobnosti svého účtu a seznam vzorků.";
	$default_page_text_008 = "Zobrazit podrobnosti účtu.";
	$default_page_text_009 = "Výherci Best of Show";
	$default_page_text_010 = "Výherci";
	$default_page_text_011 = "Své údaje zadáváte pouze jednou, následně se můžete na web vrátit a vložit další vzorky nebo upravit ty už existující.";
	$default_page_text_012 = "Můžete taktéž zaplatit poplatky za vzorky online.";
	$default_page_text_013 = "Organizátor soutěže";
	$default_page_text_014 = "Organizátoři soutěže";
	$default_page_text_015 = "Každé z osob můžete zaslat email prostřednictvím stránky ";
	$default_page_text_016 = "je hrdý/jsou hrdi, že mají";
	$default_page_text_017 = "na";
	$default_page_text_018 = "Stáhněte si výherce Best of Show ve formátu PDF.";
	$default_page_text_019 = "Stáhněte si výherce Best of Show ve formátu HTML.";
	$default_page_text_020 = "Stáhněte si vítězné vzorky ve formátu PDF.";
	$default_page_text_021 = "Stáhněte si vítězné vzorky ve formátu HTML.";
	$default_page_text_022 = "Děkujeme za váš zájem o soutěž";
	$default_page_text_023 = ", která se koná v";

	$reg_open_text_000 = "Registrace degustátorů a obsluhy je";
	$reg_open_text_001 = "otevřená";
	$reg_open_text_002 = "Pokud jste se <em>ještě nezaregistrovali</em>, a chcete být dobrovolníkem,";
	$reg_open_text_003 = "zaregistrujte se, prosím";
	$reg_open_text_004 = "Pokud jste se <em>již zaregistrovali</em>, přihlaste se a zvolte <em>Upravit účet</em> přes Můj účet označený ikonou";
	$reg_open_text_005 = "v horní nabídce.";
	$reg_open_text_006 = "Protože jste již zaregistrováni, můžete";
	$reg_open_text_007 = "si prohlédnout údaje svého účtu";
	$reg_open_text_008 = "abyste si ověřili, zda jste povolili svoji účast jako degustátor nebo obsluha.";
	$reg_open_text_009 = "Pokud se chcete účastnit jako degustátor nebo obsluha, prosím, vraťte se znovu pro svou registraci po";
	$reg_open_text_010 = "Registrace vzorků je";
	$reg_open_text_011 = "Pro přidání vzorků do systému";
	$reg_open_text_012 = "projděte, prosím, registračním procesem";
	$reg_open_text_013 = ", pokud již máte účet.";
	$reg_open_text_014 = "použijte formulář přidání vzorků,";

	// v2.1.9
	$reg_open_text_015 = "Registrace degustátorů je";
	$reg_open_text_016 = "Registrace obsluhy je";
	$reg_closed_text_000 = "Díky a hodně štěstí všem, kteří se zaregistrovali do soutěže";
	$reg_closed_text_001 = "Momentálně máme";
	$reg_closed_text_002 = "zaregistrovaných soutěžících, degustátorů a obsluhujících.";
	$reg_closed_text_003 = "zaregistrovaných vzorků a";
	$reg_closed_text_004 = "zaregistrovaných soutěžících, degustátorů a obsluhujících.";
	$reg_closed_text_005 = "Ke dni celkem";
	$reg_closed_text_006 = "přijatých a zpracovaných vzorků. Číslo se bude měnit podle toho, jak budeme přebírat vzorky na místě příjmu vzorků pro posouzení.";
	$reg_closed_text_007 = "Data degustace nebyla zatím určena. Prosím, navštivte web později.";
	$reg_closed_text_008 = "Map to";
	$judge_closed_000 = "Díky všem, kteří se zúčastnili soutěže";
	$judge_closed_001 = "Celkem bylo posouzeno";
	$judge_closed_002 = "vzorků a účastnilo se";
	$judge_closed_003 = "soutěžících, degustátorů a obsluhujících.";

}

// -------------------- Entry Info --------------------

$entry_info_text_000 = "Účet si budete moci vytvořit počínaje";
$entry_info_text_001 = "do";
$entry_info_text_002 = "Degustátoři a obsluha se mohou registrovat od";
$entry_info_text_003 = "za vzorek";
$entry_info_text_004 = "Můžete se zaregistrovat ode dneška do";
$entry_info_text_005 = "Degustátoři a obsluha se mohou registrovat ode dneška do";
$entry_info_text_006 = "Registrace";
$entry_info_text_007 = "degustátorů a obsluhy";
$entry_info_text_008 = "přijímány do";
$entry_info_text_009 = "Registrace je <strong class=\"text-danger\">uzavřena</strong>.";
$entry_info_text_010 = "Vítejte";
$entry_info_text_011 = "Prohlédněte si podrobnosti svého účtu a seznam vzorků.";
$entry_info_text_012 = "Prohlédněte si údaje svého účtu níže.";
$entry_info_text_013 = "na vzorek po";
$entry_info_text_014 = "Do systému budete moci vkládat své vzorky počínaje";
$entry_info_text_015 = "Své vzorky můžete vkládat ode dneška do";
$entry_info_text_016 = "Registrace vzorků je <strong class=\"text-danger\">uzavřena</strong>.";
$entry_info_text_017 = "for unlimited entries.";
$entry_info_text_018 = "pro členy AHA";
$entry_info_text_019 = "Tato soutěž má omezení počtu na";
$entry_info_text_020 = "vzorků.";
$entry_info_text_021 = "Každý soutěžící může vložit nejvíce";
$entry_info_text_022 = strtolower($label_entry);
$entry_info_text_023 = "vzorků";
$entry_info_text_024 = "vzorek na podkategorii";
$entry_info_text_025 = "vzorků na podkategorii";
$entry_info_text_026 = "výjimky jsou uvedeny níže";
$entry_info_text_027 = "podkategorii";
$entry_info_text_028 = "podkategoriích";
$entry_info_text_029 = "vzorek v následujících";
$entry_info_text_030 = "vzorků v následující";
$entry_info_text_031 = "Po vytvoření účtu a vložení vzorků do systému musíte uhradit poplatek za vzorky. Přijímáme tyto druhy plateb:";
$entry_info_text_032 = $label_cash;
$entry_info_text_033 = $label_check.", vystavený na";
$entry_info_text_034 = "Kreditní/debetní karta a e-check (prostřednictvím PayPalu)";
$entry_info_text_035 = "Data degustace ještě nebyla určena. Prosím, navštivte náš web později.";
$entry_info_text_036 = "Lahve se vzorky přijímáme na poštovní adrese počínaje";
$entry_info_text_037 = "Vzorky zasílejte na následující adresu:";
$entry_info_text_038 = "Pečlivě zabalte své vzorky do pevné krabice. Vnitřek krabice vyložte plastovým pytlem na odpad. Oddělte a zabalte každou lahev odpovídajícím balicím materiálem. S množstvím materiálu to nepřehánějte!";
$entry_info_text_039 = "Na balík napište: <em>Křehké. Touto stranou vzůhru.</em> Pro balení používejte pouze bublinkovou fólii.";
$entry_info_text_040 = "<em>Každý</em> ze štítků na vašich lahvích zabalete do malého pytlíku před jeho upevněním na lahev. Tímto způsobem nám usnadníte určení, který vzorek se rozbil, pokud se něco stane při přepravě.";
$entry_info_text_041 = "Vynasnažíme se kontaktovat soutěžící, jejichž lahve se rozbily, kvůli zaslání nových lahví.";
$entry_info_text_042 = "Pokud se soutěž koná ve Spojených státech, mějte na paměti, že je <strong>zakázáno</strong> posílat vzorky pomocí United States Postal Service (USPS). <em>Pozn. překl.: Na zásilky zaslané prostřednictvím České pošty se žádný podobný zákaz nevztahuje.</em><br />Soukromé přepravní společnosti vám mohou odmítnout přepravu, pokud se dozví, že zásilka obsahuje sklo nebo alkoholické nápoje. Berte na vědomí, že vzorky zaslané mezinárodně mohou podléhat celnímu řízení. Zásilky mohou být otevřeny a vráceny odesilateli celní správou dle jejího uvážení. Je vaší povinností se řídit příslušnými zákony a nařízeními.";
$entry_info_text_043 = "Lahve se vzorky přijímáme v místech příjmu vzorků počínaje";
$entry_info_text_044 = "Zobrazit na mapě místo";
$entry_info_text_045 = "Klikněte/dotkněte se pro požadované informace o vzorku";
$entry_info_text_046 = "Pokud je na názvu stylu odkaz, má zvláštní požadavky na vzorek. Klikněte nebo se dotkněte názvu pro zobrazení požadavků podkategorie.";


// -------------------- Footer --------------------

// -------------------- Judge Info --------------------

// -------------------- List (User Entry List) --------------------

	$brewer_entries_text_000 = "U prohlížeče Firefox existuje známá chyba s tiskem.";
	$brewer_entries_text_001 = "Máte nepotvrzené vzorky.";
	$brewer_entries_text_002 = "U každého vzorku níže, označeného ikonou <span class=\"fa fa-lg fa-exclamation-circle text-danger\"></span>, klikněte na ikonu <span class=\"fa fa-lg fa-pencil text-primary\"></span> pro jeho prohlédnutí a potvrzení údajů. Nepotvrzené vzorky mohou být smazány ze systému bez předchozího upozornění.";
	$brewer_entries_text_003 = "NEMŮŽETE zaplatit za vzorky dokud jste všechny nepotvrdili.";
	$brewer_entries_text_004 = "Přihlásili jste vzorky, které vyžadují definici specifického stylu, zvláštních surovin, názvu klasického stylu, síly nebo barvy.";
	$brewer_entries_text_005 = "U každého vzorku níže, označeného ikonou <span class=\"fa fa-lg fa-exclamation-circle text-danger\"></span>, klikněte na ikonu <span class=\"fa fa-lg fa-pencil text-primary\"></span> pro zadání požadovaných údajů. Vzorky bez uvedeného specifického stylu, zvláštních surovin, názvu klasického stylu, síly nebo barvy mohou být smazány ze systému bez předchozího upozornění.";
	$brewer_entries_text_006 = "Stáhnout scoresheety degustátorů pro";
	$brewer_entries_text_007 = "Styl NEZADÁN";
	$brewer_entries_text_008 = "Entry Form and";
	$brewer_entries_text_009 = "Štítky lahví";
	$brewer_entries_text_010 = "Vytisknout formulář receptu";
	$brewer_entries_text_011 = "Taktéž nebudete moci přidat další vzorek protože byl dosažen maximální počet vzorků soutěže. Klikněte na Zrušit v tomto poli a potom místo toho vzorek upravte, pokud jej chcete zachovat.";
	$brewer_entries_text_012 = "Opravdu chcete smazat vzorek s názvem";
	$brewer_entries_text_013 = "Následně budete moci přidat další vzorky do";
	$brewer_entries_text_014 = "Zatím jste nevložili žádné vzorky.";
	$brewer_entries_text_015 = "Momentálně nelze smazat tento vzorek.";

// -------------------- Login --------------------

// -------------------- Past Winners --------------------
if ($section == "past_winners") {
	$past_winners_text_000 = "Zobrazit dřívější výherce:";
}

// -------------------- Pay for Entries --------------------

$pay_text_000 = "Jelikož již uplynul čas pro registraci, vkládání vzorků, zasílání a přejímku, platby nejsou již přijímány.";
$pay_text_001 = "Kontaktujte zástupce soutěže, máte-li nějaké dotazy.";
$pay_text_002 = "následující jsou vaše možnosti úhrady poplatků.";
$pay_text_003 = "Poplatek činí";
$pay_text_004 = "za vzorek";
$pay_text_005 = "za vzorek od";
$pay_text_006 = "pro neomezený počet vzorků";
$pay_text_007 = "Vaše poplatky byly sníženy na";
$pay_text_008 = "Celková částka k úhradě je";
$pay_text_009 = "Je třeba uhradit";
$pay_text_010 = "Vaše poplatky byly uhrazeny. Děkujeme!";
$pay_text_011 = "Aktuálně máte";
$pay_text_012 = "potvrzených, neuhrazených";
$pay_text_013 = "Přiložte šek na celkovou částku k jedné z vašich lahví. Šek vystavte na";
$pay_text_014 = "Kopie vašeho šeku nebo proplacený šek je dokladem o zaplacení.";
$pay_text_015 = "Přiložte částku v hotovosti za všechny vzorky v <em>zalepené obálce</em> k jedné z vašich lahví.";
$pay_text_016 = "Vrácené scoresheety poslouží jako potvrzení příjmu vzorků.";
$pay_text_017 = "Email s potvrzením o platbě je dokladem o zaplacení. Přiložte jej k vašim vzorkům pro potvrzení.";
$pay_text_018 = "Klikněte na tlačítko <em>Pay with PayPal</em> pro zaplacení online.";
$pay_text_019 = "Prosím, vezměte na vědomí, že k vaší platbě bude naúčtováno";
$pay_text_020 = "jako poplatek za transakci.";
$pay_text_021 = "Abyste zajistili, že bude platba PayPalem zaregistrována jako <strong>uhrazená</strong> na <strong>tomto webu</strong>, klikněte na potvrzovací obrazovce PayPalu na odkaz <em>Return to...</em> <strong>po</strong> uhrazení platby. Taktéž si vytiskněte potvrzení a přiložte jej k jedné z vašich lahví.";
$pay_text_022 = "Klikněte na <em>Return To...</em> po dokončení platby";
$pay_text_023 = "Zadejte slevový kód pro získání slevy z poplatku.";
$pay_text_024 = $pay_text_010;
$pay_text_025 = "Zatím jste nevložili žádné vzorky.";
$pay_text_026 = "Své vzorky nemůžete uhradit, jelikož některé z nich nebyly dosud potvrzeny.";
$pay_text_027 = "Klikněte na <em>Můj účet</em> výše pro zobrazení seznamu nepotvrzených vzorků.";
$pay_text_028 = "Máte nepotvrzené vzorky, které <em>nejsou</em> zahrnuty do celkového součtu poplatků níže.";
$pay_text_029 = "Prosím, otevřete si seznam vzorků a potvrďte všechny vaše vzorky. Nepotvrzené vzorky mohou být smazány ze systému bez předchozího varování.";

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
	if (strpos($view, "^") !== FALSE) $qr_text_002 = sprintf("Vzorek číslo <span class=\"text-danger\">%s</span> je označen degustačním číslem <span class=\"text-danger\">%s</span>.",$qr_text_019,$qr_text_020); else $qr_text_002 = "";
	$qr_text_003 = "Pokud je toto degustační číslo <em>chybné</em>, <strong>naskenujte kód znovu a znovu zadejte správné degustační číslo.";
	if (strpos($view, "^") !== FALSE) $qr_text_004 = sprintf("Vzorek číslo %s byl přijat.",$qr_text_019); else $qr_text_004 = "";
	if (strpos($view, "^") !== FALSE) $qr_text_005 = sprintf("Vzorek číslo %s nebyl v databázi nalezen. Lahve odložte stranou a upozorňete organizátora soutěže.",$qr_text_019); else $qr_text_005 = "";
	if (strpos($view, "^") !== FALSE) $qr_text_006 = sprintf("Zadané degustační číslo - %s - je již přiřazeno vzorku %s.",$qr_text_020,$qr_text_019); else $qr_text_006 = "";
	$qr_text_007 = "Příjem vzorků pomocí QR kódů";
	$qr_text_008 = "Abyste mohli přijímat vzorky QR kódem, zadejte platné heslo. Heslo musíte zadat pouze jednou, nechte však po celou dobu spuštěnou skenovací aplikaci.";
	$qr_text_009 = "Přiřadit degustační číslo nebo číslo krabice vzorku";
	$qr_text_010 = "Zadejte degustační číslo POUZE V PŘÍPADĚ, že vaše soutěž používá u třídění vzorků štítky s degustačními čísly.";
	$qr_text_011 = "Šestičíslí k uvozujícími nulami, např. 000021.";
	$qr_text_012 = "Zkontrolujte pečlivě zadané údaje a upevněte štítek s odpovídajícím degustačním číslem na všechny lahve nebo případné etikety lahví.";
	$qr_text_013 = "Degustační čísla musejí být šestiznaková a nesmí obsahovat znak ^.";
	$qr_text_014 = "Čekám na naskenovaný QR kód.";
	$qr_text_015 = "Spustit nebo se vrátit zpět do aplikace mobilního zařízení pro naskenování QR kódu.";
	$qr_text_016 = "Potřebujete aplikaci pro skenování QR kódů? Hledejte na <a class=\"hide-loader\" href=\"https://play.google.com/store/search?q=qr%20code%20scanner&c=apps&hl=en\" target=\"_blank\">Google Play</a> (Android) nebo na <a class=\"hide-loader\" href=\"https://itunes.apple.com/store/\" target=\"_blank\">iTunes</a> (iOS).";
	$qr_text_017 = "Pro využití této funkce je nutná aplikace pro skenování QR kódů.";
	$qr_text_018 = "Spusťte aplikaci na svém mobilním zařízení, naskenujte QR kód na štítku lahve a zadejte heslo. Tím příjmete vzorek.";


// -------------------- Registration Open --------------------

// -------------------- Registration Closed --------------------

// -------------------- Register --------------------

if (($section == "register") || ($section == "brewer") || ($action == "register") || ($go == "account") || ($section == "step2")) {

	$register_text_000 = "Je dobrovolník ";
	$register_text_001 = "Jste ";
	$register_text_002 = "Registrace je uzavřena.";
	$register_text_003 = "Děkujeme za zájem.";
	$register_text_004 = "Údaje, které poskytnete nad rozsah vašeho jména, příjmení a klubu jsou striktně pro náš záznam a případnou potřebu vás kontaktovat.";
	$register_text_005 = "Podmínkou vložení vzorku do soutěže je poskytnutí těchto informací. Vaše jméno a klub mohu být uvedeny u vašeho vzorku, žádné další údaje však nezveřejníme.";
	$register_text_006 = "Upozornění: Smíte se zaregistrovat pouze do jedné oblasti. Jakmile si zvolíte místo příjmu vzorků, NELZE jej už změnit.";
	$register_text_007 = "Zrychleně";
	$register_text_008 = "Zaregistrovat se jako";
	$register_text_009 = "degustátor";
	$register_text_010 = "soutěžící";
	$register_text_011 = "Abyste se zaregistrovali, vytvořte si online účet vyplněním polí níže.";
	$register_text_012 = "Zrychleně přidat osobu do seznamu degustátorů/obsluhy soutěže. Pro osoby přidané přes tuto obrazovku bude použita výplňová adresa a telefonní číslo, stejně jako výchozí heslo <em>bcoem</em>.";
	$register_text_013 = "Vstup do této soutěže je proveden kompletně online.";
	$register_text_014 = "Pro vložení vzorků nebo označení sebe jako degustátora či obsluhu (tito taktéž mohou vložit své vzorky) si musíte vytvořit účet v našem systému.";
	$register_text_015 = "Uživatelským jménem bude vaše emailová adresa. Tuto použijeme pro rozlišení účastníků v soutěži. Ujistěte se, že je správně zadaná.";
	$register_text_016 = "Jakmile se zaregistrujete, můžete projít procesem vložení vzorku.";
	$register_text_017 = "Každému vzorku systém automaticky přidělí číslo.";
	$register_text_018 = "Zvolte jednu z otázek. Otázka bude použita pro ověření vaší identity když zapomenete heslo.";
	$register_text_019 = "Prosím, zadejte emailovou adresu.";
	$register_text_020 = "Zadané emailové adresy se neshodují.";
	$register_text_021 = "Emailové adresy slouží jako uživatelská jména.";
	$register_text_022 = "Prosím, zadejte heslo.";
	$register_text_023 = "Prosím, zadejte odpověď na bezpečnostní otázku.";
	$register_text_024 = "Uveďte odpověď, jakou si snadno vybavíte pouze vy!";
	$register_text_025 = "Prosím, zadejte jméno.";
	$register_text_026 = "Prosím, zadejte příjmení.";
	$register_text_027 = "obsluha";
	$register_text_028 = "Prosím, zadejte ulici.";
	$register_text_029 = "Prosím, zadejte město.";
	$register_text_030 = "Prosím, zadejte kraj nebo oblast.";
	$register_text_031 = "Prosím zadejte PSČ.";
	$register_text_032 = "Prosím, zadejte hlavní telefonní číslo.";
	$register_text_033 = "Pouze členové American Homebrewers Association mají nárok na příležitost Great American Beer Festival Pro-Am.";
	$register_text_034 = "Abyste se mohli zaregistrovat, musíte potvrdit zaškrtnutím pole, že souhlasíte s prohlášením níže.";

}

// -------------------- Sidebar --------------------

$sidebar_text_000 = "Registrace degustátorů a obsluhy přijímáme od";
$sidebar_text_001 = "Registrace obsluhy přijímáme od";
$sidebar_text_002 = "Registrace degustátorů";
$sidebar_text_003 = "Registrace je uzavřena. Byl dosažen maximální počet degustátorů a obsluhy.";
$sidebar_text_004 = "do";
$sidebar_text_005 = "Registrace účtů přijímáme od";
$sidebar_text_006 = "je otevřena pouze pro degustátory a obsluhu";
$sidebar_text_007 = "je otevřena pouze pro obsluhu";
$sidebar_text_008 = "je otevřena pouze pro degustátory";
$sidebar_text_009 = "Registrace vzorků otevřena od";
$sidebar_text_010 = "Maximální počet uhrazených vzorků v soutěži byl dosažen.";
$sidebar_text_011 = "Maximální počet vzorků v soutěži byl dosažen.";
$sidebar_text_012 = "Prohlédněte si seznam vašich vzorků.";
$sidebar_text_013 = "Klikněte sem pro uhrazení poplatků.";
$sidebar_text_014 = "Poplatky za vzorku nezahrnují nepotvrzené vzorky.";
$sidebar_text_015 = "Máte nepotvrzené vzorky - musíte je potvrdit.";
$sidebar_text_016 = "Prohlédněte si seznam svých vzorků.";
$sidebar_text_017 = "Zbývá";
$sidebar_text_018 = "před tím, než dosáhnete limitu";
$sidebar_text_019 = "na účastníka";
$sidebar_text_020 = "Dosáhli jste limitu";
$sidebar_text_021 = "v této soutěži";
$sidebar_text_022 = "Vzorky se příjmají v";
$sidebar_text_023 = "místě zaslání vzorků";
$sidebar_text_024 = "Data degustací nebyla ještě stanovena. Prosím, vraťte se později.";
$sidebar_text_025 = "bylo vloženo do systému k datu";

// -------------------- Sponsors --------------------
// NONE


// -------------------- Styles ---------------------

$styles_entry_text_07C = "Účastník musí uvést, zda je vzorek Munich Kellerbier (světlý, založený na Helles) nebo Franconian Kellerbier (polotmavý, založený na Märzenu). Účastník může taktéž uvést jiný druh Kellerbieru založený na jiném základním stylu, jakým může být například Pils, Bock nebo Schwarzbier, musí však uvést popis tohoto stylu pro degustátory.";
$styles_entry_text_09A = "Účastník musí uvést, zda je vzorek světlou nebo tmavou variantou.";
$styles_entry_text_10C = "Účastník musí uvést, zda je vzorek světlou nebo tmavou variantou.";
$styles_entry_text_21B = "Účastník musí uvést sílu (session: 3,0-5,0 %, standard: 5,0-7,5 %, double: 7,5-9,5 %); pokud není síla uvedena, považuje se síla vzorku za 'standard'. Účastník musí zvolit konkrétní druh Specialty IPA z knihovny známých druhů uvedených ve Style Guidelines a na webu BJCP nebo druh popsat včetně jeho klíčových vlastností v poli pro komentář takže buou degustátoři vědět, co mají očekávat. Účastníci mohou uvést druhy použitých chmelů, pokud se domnívají, že by degustátoři nemuseli charakteristiku těchto chmelů poznat (v případě nových odrůd). Účastníci mohou uvést kombinaci stylů (např. Black Rye IPA) i bez uvedení podrobného popisu. Účastníci mohou využít tuto kategorii pro IPA s odlišnou sílou než je uvedena v příslušné podkategorii (např. pro session American nebo English IPA) s výjimkou případů, kde již příslušná podkategorie existuje (např. double [American] IPA). Aktuálně definované druhy: Black IPA, Brown IPA, White IPA, Rye IPA, Belgian IPA, Red IPA.";
$styles_entry_text_23F = "Musí být uveden druh použitého ovoce. Sládek musí uvést úroveň nasycení (nízká, střední, vysoká) a sladkosti (žádná/nízká, střední, vysoká).";
$styles_entry_text_24C = "Účastník musí uvést druh - blond, polotmavé nebo hnědé biere de garde. Pokud není barva uvedena, degustátor by se měl pokusit posoudit pivo na základě prvního dojmu a očekávat sladový profil a vyvážení odpovídající barvě.";
$styles_entry_text_25B = "Účastník musí uvést sílu (stolní, standard, super) a barvu (světlá, tmavá).";
$styles_entry_text_27A = "Účastník musí uvést buď styl s popisem daným BJCP nebo poskytnout odpovídající popis degustátorům. Pokud je pivo vloženo pouze s názvem stylu a bez popisu, je velmi nepravděpodobné, že budou degustátoři vědět, jak ho mají posoudit. Aktuálně definované příklady: Gose, Piwo Grodziskie, Lichtenhainer, Roggenbier, Sahti, Kentucky Common, Pre-Prohibition Lager, Pre-Prohibition Porter, London Brown Ale.";
$styles_entry_text_28A = "Účastník musí uvést buď základní styl (klasický BJCP styl nebo rodinu stylů) nebo poskytnout popis použitých surovin, specifikaci a požadovaný charakter. Účastník musí uvést, zda bylo použito kvašení se 100 % brettanomyces. Účastník může uvést kmen či kmeny použitých brettanomyces spolu s krátkým popisem jejich charakteristik.";
$styles_entry_text_28B = "Účastník musí uvést popis piva, identifikovat kvasnice/bakterie a buď základní styl nebo suroviny, specifikaci a požadovaný charakter piva.";
$styles_entry_text_28C = "Účastník musí uvést druh použitého ovoce, koření, bylin nebo dřeva. Účastník musí uvést popis piva, identifikovat kvasnice/bakterie a buď základní styl nebo suroviny, specifikaci a požadovaný charakter piva. Obecný popis zvláštností piva může pokrýt veškeré vyžadované údaje.";
$styles_entry_text_29A = "Účastník musí uvést základní styl, ten však nemusí být klasickým stylem. Účastník musí uvést druh použitého ovoce. Kyselá piva, která však nejsou lambiky, by měla být vložena do kategorie American Wild Ale.";
$styles_entry_text_29B = "Účastník musí uvést základní styl, ten však nemusí být klasickým stylem. Účastník musí uvést druh použitého ovoce, koření, bylin nebo zeleniny. Pokud je použito známé míchané přísady (např. koření na apple pie), není třeba uvádět jednotlivé složky.";
$styles_entry_text_29C = "Účastník musí uvést základní styl, ten však nemusí být klasickým stylem. Účastník musí uvést druh použitého ovoce. Účastník musí uvést druh navíc dodaných zkvasitelných cukrů nebo použitý zvláštní proces.";
$styles_entry_text_30A = "Účastník musí uvést základní styl, ten však nemusí být klasickým stylem. Účastník musí uvést druh použitého koření, bylin nebo zeleniny. Pokud je použito známé míchané přísady (např. koření na apple pie), není třeba uvádět jednotlivé složky.";
$styles_entry_text_30B = "Účastník musí uvést základní styl, ten však nemusí být klasickým stylem. Účastník musí uvést druh použitého koření, bylin nebo zeleniny. Pokud je použito známé míchané přísady (např. koření na apple pie), není třeba uvádět jednotlivé složky. Pivo musí obsahovat koření a může obsahovat zeleninu nebo cukry.";
$styles_entry_text_30C = "Účastník musí uvést základní styl, ten však nemusí být klasickým stylem. Účastník musí uvést druh kořetní, cukrů, ovoce nebo dalších zkvasitelných složek. Pokud je použito známého míchaného koření (např. koření na svařené víno), není třeba uvádět jednotlivé složky.";
$styles_entry_text_31A = "Účastník musí uvést základní styl, ten však nemusí být klasickým stylem. Účastník musí uvést druh použité alternativní obilniny.";
$styles_entry_text_31B = "Účastník musí uvést základní styl, ten však nemusí být klasickým stylem. Účastník musí uvést druh použitého cukru.";
$styles_entry_text_32A = "Účastník musí uvést klasický styl základního piva. Účastník musí uvést druh dřeva nebo kouře, pokud je konkrétní druh rozpoznatelný.";
$styles_entry_text_32B = "Účastník musí uvést základní styl, ten však nemusí být klasickým stylem. Účastník musí uvést druh dřeva nebo kouře, pokud je konkrétní druh rozpoznatelný. Účastník musí uvést dodatečné suroviny nebo postupy, které ze vzorku dělají specialty smoked beer.";
$styles_entry_text_33A = "Účastník musí uvést druh dřeva a úroveň požehnutí (bylo-li požehnuto). Účastník musí uvést základní styl. Ten může být buď klasickým BJCP stylem (např. pojmenovaná podkategorie) nebo obecným názvem stylu (např. porter nebo brown ale). Pokud bylo použito nezvyklé dřevo, musí účastník uvést krátký popis senzorického projevu dřeva v pivu.";
$styles_entry_text_33B = "Účastník musí uvést charakter alkoholu spolu s informacemi o sudu, je-li sud relevantní pro konečný profil piva. Účastník musí uvést základní styl. Ten může být buď klasickým BJCP stylem (např. pojmenovaná podkategorie) nebo obecným názvem stylu (např. porter nebo brown ale). Pokud bylo použito nezvyklé dřevo, musí účastník uvést krátký popis senzorického projevu dřeva v pivu.";
$styles_entry_text_34A = "Účastník musí uvést název klonovaného komerčního piva, specifikaci (základní vlastnosti) a buď krátký popis nebo seznam použitých surovin. Bez těchto informací nebudou mít degustátoři, kteří klonované pivo neznají, možnost posoudit shodu.";
$styles_entry_text_34B = "Účastník musí uvést styly míchaných piv. Účastník může poskytnout další popis senzorického profilu výsledného piva nebo jeho základní údaje.";
$styles_entry_text_34C = "Účastník musí uvést, čím je toto experimentální pivo zvláštní včetně uvedení zvláštních surovin a postupů, kterými se nehodí do jiné kategorie guidelines. Účastník musí uvést základní údaje piva a buď krátký popis senzorických vlastností nebo seznam použitých surovin. Bez těchto informací nebudou mít degustátoři s čím srovnávat.";
///TODO: Mead categories remain untraslated for the first release of the Czech localisation
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

$user_text_000 = "Je vyžadována nová emailová adresa v platném tvaru.";
$user_text_001 = "Zadejte původní heslo.";
$user_text_002 = "Zadejte nové heslo.";
$user_text_003 = "Prosím, zaškrtněte toto pole, pokud chcete změnit svou emailovou adresu.";

// -------------------- Volunteers --------------------

if ($section == "volunteers") {

	$volunteers_text_000 = "Pokud jste již zaregistrovaní,";
	$volunteers_text_001 = "a potovm zvolte <em>Upravit účet</em> z nabídky můj účet uvozené ikonou";
	$volunteers_text_002 = "v horní nabídce";
	$volunteers_text_003 = "a";
	$volunteers_text_004 = "Pokud ještě <em>nejste</em> zaregistrováni a chcete se zúčastnit jako degustátor nebo obsluha, prosím zaregistrujte se";
	$volunteers_text_005 = "Jelikož jste již zaregistrováni,";
	$volunteers_text_006 = "otevřete si podrobnosti svého účtu,";
	$volunteers_text_007 = "abyste zjistili, zda jste se přihlásili jako dobrovolník pro degustace nebo obsluhování";
	$volunteers_text_008 = "Pokud se chcete účastnit jako degustátor nebo obsluha, vraťte se prosím nejdříve";

	// v2.1.9
	$volunteers_text_009 = "Pokud chcete být dobrovolníkem, součástí personálu soutěže, prosím zaregistrujte se nebo ve svém účtu zvolte, že chcete být součástí personálu.";
	$volunteers_text_010 = "";

}

$login_text_000 = "Jste již přihlášeni.";
$login_text_001 = "V systému není žádná shodující se emailová adresa s tou, kterou jste zadali.";
$login_text_002 = "Zkusit znovu?";
$login_text_003 = "Máte již zaregistrovaný účet?";
$login_text_004 = "Zapomněli jste heslo?";
$login_text_005 = "Obnovte si jej";
$login_text_006 = "Pro obnovu hesla zadejte emailovou adresu použitou při registraci.";
$login_text_007 = "Ověřit";
$login_text_008 = "Náhodně vygenerovaná";
$login_text_009 = "<strong>Nedostupné.</strong> Váš účet byl vytvořen správcem a odpověď na vaši bezpečnostní otázku je náhdoně vygenerovaná. Prosím, kontaktujte správce webu pro obnovení nebo změnu vašeho hesla.";
$login_text_010 = "Nebo použijte možnost emailu níže.";
$login_text_011 = "Vaše bezpečností otázka je...";
$login_text_012 = "Pokud jste neobdrželi email,";
$login_text_013 = "Bude vám odeslán email s vaší ověřovací otázkou a odpovědí. Zkontrolujte svoji složku spamu.";
$login_text_014 = "klikněte sem pro znovuzaslání na";
$login_text_015 = "Pokud si nevybavíte odpověď na bezpečnostní otázku, kontaktujte zástupce soutěže nebo správce webu.";
$login_text_016 = "Zaslat na";

// -------------------- Winners --------------------

$winners_text_000 = "U tohoto stolu nebyli zadáni žádní výherci. Zkuste to později, prosím.";
$winners_text_001 = "Vítězové nebyli ještě zveřejněni. Zkuste to později, prosím.";
$winners_text_002 = "Zvolená struktura udílení cen je udílení podle stolů. Zvolte místa udílení stolu jako celku níže.";
$winners_text_003 = "Zvolená struktura udílení cen je udílení podle kategorie. Zvolte místa udílení pro každou kategorii jako celku (u tohoto stolu může být více než jedna).";
$winners_text_004 = "Zvolená struktura udílení cen je podle podkategorie. Zvolte místa udílení pro každou z podkategorií níže (u tohoto stolu může být více než jedna).";

// ----------------------------------------------------------------------------------
// Output
// ----------------------------------------------------------------------------------

$output_text_000 = "Děkujeme za účast v soutěži";
$output_text_001 = "Souhrn vašich vzorků, hodnocení a míst je níže.";
$output_text_002 = "Souhrn pro";
$output_text_003 = "vzorků posouzeno";
$output_text_004 = "Vaše scoresheety nelze řádně vygenerovat. Prosím, kontaktujte organizátory soutěže.";
$output_text_005 = "Nebyla definována žádná přiřazení degustátorů/obsluhy";
$output_text_006 = "pro toto umístění";
$output_text_007 = "Pokud chcete vytisknout prázdné karty stolů, zavřete toto okno a zvolte <em>Vytisknout karty stolů: Všechny stoly</em> z nabídky <em>Reporting</em>.";
$output_text_008 = "Prosím, zkontrolujte, zda je vaše BJCP ID správné. Pokud není, nebo pokud jím disponujete a přitom není na seznamu, zadejte jej do formuláře.";
$output_text_009 = "Pokud není vaše jméno na seznamu níže, zapište se na přiložených sheetech.";
$output_text_010 = "Abyste dosali body za degustace, ujistěte se, že je vaše BJCP ID zadáno správně.";
$output_text_011 = "Nebyla provedena žádná přiřazení.";
$output_text_012 = "Celkem vzorků v tomto umístění";
$output_text_013 = "Žádní účastníci neposkytli organizátorům poznámky.";
$output_text_014 = "Následují poznámky organizátorům zadané degustátory.";
$output_text_015 = "Žádní účastníci neposkytli organizátorům poznámky.";
$output_text_016 = "Inventář vzorků po degustacích";
$output_text_017 = "Pokud se níže nezobrazují žádné vzorky, nebyly sady vzorků ještě přiřazeny ke kolům.";
$output_text_018 = "Pokud některé vzorky chybí, nebyly ještě všechny vzorku přiřazeny k sadám nebo kolům NEBO byly přiřazeny k jinému kolu.";
$output_text_019 = "Pokud nejsou níže žádné vzorky, nebyl tento stůl ještě přiřazen ke kolu.";
$output_text_020 = "Žádné vzorky nejsou způsobilé.";
$output_text_021 = "Tahák k číslu vzorku / degustačnímu číslu";
$output_text_022 = "Body v tomto reportu jsou odvozeny od oficiálních požadavků na soutěže dle BJCP dostupných na";
$output_text_023 = "zahrnuje Best of Show";
$output_text_024 = "Report s BJCP body";
$output_text_025 = "Celkem dostupných bodů pro personál";
$output_text_026 = "Styly této kategorie nejsou v této soutěži přijímány.";
$output_text_027 = "odkaz na Beer Judge Certification Program Style Guidelines";

// ----------------------------------------------------------------------------------
// Maintenance
// ----------------------------------------------------------------------------------

// v2.1.9
$maintenance_text_000 = "Správce webu stránku dočasně odpojil pro provedení údržby.";
$maintenance_text_001 = "Zkuste to později, prosím.";

// ----------------------------------------------------------------------------------
// Version 2.1.10-2.1.12 Additions
// ----------------------------------------------------------------------------------

// -------------------- Labels --------------------
$label_entry_numbers = "Čísla vzorků"; // For PayPal IPN Email
$label_status = "Stav"; // For PayPal IPN Email
$label_transaction_id = "ID transakce"; // For PayPal IPN Email
$label_organization = "Organizace";
$label_ttb = "Číslo TTB";
$label_username = "Uživatelské jméno";
$label_from = "Odesilatel"; // For email headers
$label_to = "Adresát"; // For email headers
$label_varies = "Různé";
$label_styles_accepted = "Přijímané styly";
$label_judging_styles = "Posuzované styly";
$label_select_club = "Zvolte nebo vyhledejte svůj klub";
$label_select_style = "Zvolte nebo vyhledejte styl svého vzorku";
$label_select_country = "Zvolte nebo vyhledejte svoji zemi";
$label_select_dropoff = "Zvolte místo příjmu vzorků";
$label_club_enter = "Zadejte název klubu";
$label_secret_05 = "Jaké je jméno jméno vaší babičky z matčiny strany za svobodna?";
$label_secret_06 = "Jak se jmenoval váš první partner?";
$label_secret_07 = "Jaký je výrobce a model vašeho prvního auta?";
$label_secret_08 = "Jaké je příjmení vašeho třídního ve třetí třídě?";
$label_secret_09 = "V jakém městě nebo obci jste potkali svého partnera?";
$label_secret_10 = "Jaké je křestní jméno vašeho nejlepšího kamaráda nebo kamarádky v šesté třídě?";
$label_secret_11 = "Jaký je váš oblíbený interpret nebo skupina?";
$label_secret_12 = "Jakou jste v dětství měli přezdívku?";
$label_secret_13 = "Jak se jmenoval učitel, který vám poprvé dal pětku?";
$label_secret_14 = "Jak se jmenoval váš nejlepší kamarád z dětství?";
$label_secret_15 = "Ve kterém městě se setkal váš otec s vaší matkou?";
$label_secret_16 = "Jaké telefonní číslo jste měli v dětství, včetně předčíslí?";
$label_secret_17 = "Jaké místo jste v dětství rádi navštěvovali?";
$label_secret_18 = "Kde jste se poprvé líbali?";
$label_secret_19 = "Ve kterém městě nebo obci jste získali svoje první zaměstnání?";
$label_secret_20 = "Ve kterém městě nebo obci jste byli na Nový rok 2000?";
$label_secret_21 = "Jak se jmenovala univerzita, kam jste se přihlásili, ale nenastoupili?";
$label_secret_22 = "Jak se křesním jménem jmenoval kluk nebo dívka, se kterou nebo kterým jste se poprvé líbali?";
$label_secret_23 = "Jak se jmenovalo vaše první plyšové zvíře, panenka nebo akční hrdina?";
$label_secret_24 = "Ve kterém městě nebo obci jste potkali svého partnera nebo manžela/manželku?";
$label_secret_25 = "Ve které ulici jste bydleli v první třídě?";
$label_secret_26 = "Jaká je rychlost letu nezatížené vlaštovky?";
$label_secret_27 = "Jak se jmenoval váš oblíbený, již zrušený, televizní pořad?";
$label_pro = "Profesionální";
$label_amateur = "Amatérský";
$label_hosted = "Hosted";
$label_edition = "Edition";
$label_pro_comp_edition = "Professional Competition Edition";
$label_amateur_comp_edition = "Amateur Competition Edition";
$label_optional_info = "Nepovinné informace";
$label_or = "Nebo";
$label_admin_payments = "Platby";
$label_payer = "Plátce";
$label_pay_with_paypal = "Uhradit PayPalem";
$label_submit = "Odeslat";
$label_id_verification_question = "Otázka pro ověření identity";
$label_id_verification_answer = "Odpověď pro ověření identity";
$label_server = "Server";
$label_password_reset = "Obnovit heslo";
$label_id_verification_request = "Požadavk pro ověření identity";
$label_new_password = "Nové heslo";
$label_confirm_password = "Potvrzení hesla";
$label_with_token = "S tokenem";
$label_password_strength = "Síla hesla";
$label_entry_shipping = "Zaslání vzorku";
$label_jump_to = "Přeskočit na...";
$label_top = "Nahoru";
$label_bjcp_cider = "Degustátor cideru";

// -------------------- Headers --------------------
$header_text_112 = "Nemáte dostatečná oprávnění pro provedení této akce.";
$header_text_113 = "Můžete upravovat údaje pouze svého účtu.";
$header_text_114 = "Jako správce můžete upravit údaje uživatelských účtů přes nabídku Admin &quot; Účastníci a vzorky &quot; Správa účastníků.";
$header_text_115 = "Výsledky byly zveřejněny.";
$header_text_116 = "Pokud v rozumném čase nedostanete email, kontaktujte představitele soutěže nebo správce webu aby vám obnovil heslo.";

// -------------------- Alerts --------------------
$alert_text_082 = "Jelikož jste se zaregistrovali jako degustátor nebo obsluha, nemáte dovoleno vkládat do vašeho účtu vzorky. Toto mohou činit pouze představitelé organizací.";
$alert_text_083 = "Přidávání nebo úprava vzorků nejsou k dispozici.";
$alert_text_084 = "Jako správce můžete přidat vzorky k účtu organizace pomocí položky &quot;Přidat vzorek pro...&quot; z rozbalovacího menu na stránce správy Vzorky a účastníci &gt; Správa vzorků.";
$alert_text_085 = "Nebudete si moci vytisknout žádné dokumenty vzorku (např. štítky na lahve) dokud nebude platba potvrzena a vzorek níže označen jako &quot;uhrazený&quot;.";

// -------------------- Brew (Add Entry) --------------------
if ($section == "brew") {
	$brew_text_027 = "Tento styl Brewers Association vyžaduje prohlášení od sládka ohledně zvláštního charakteru produktu. Prohlédněte si <a href=\"https://www.brewersassociation.org/resources/brewers-association-beer-style-guidelines/\" target=\"_blank\">BA Style Guidelines</a> pro podrobné informace.";
	$brew_text_028 = "***NENÍ POVINNÉ*** Vložte sem informace, které jsou uvedeny v guidelines jako ty, které MŮŽETE uvést.";
	$brew_text_029 = "Úprava správce vypnuta. Váš profil je považován za osobní a ne profil organizace a tudíž nemá nárok na přidání vzorků. Pro přidání vzorků za organizaci, otevřete si seznam Správa vzorků a zvolte organizaci v nabídce &quot;Přidat vzorek pro...&quot;";

	// v2.1.13
	$brew_text_030 = "mléko / laktóza";
	$brew_text_031 = "vejce";
	$brew_text_032 = "ryby";
	$brew_text_033 = "korýši";
	$brew_text_034 = "skořápkové plody";
	$brew_text_035 = "podzemnice olejná (arašídy)";
	$brew_text_036 = "pšenice";
	$brew_text_037 = "sójové boby";
	$brew_text_038 = "Obsahuje tento vzorek alergeny? Běžnými alergeny může být mléko (včetně laktózy), vejce, ryby, korýři, skořápkové plody, podzemnice olejná (arašídy), pšenice, sójové boby a podobně.";
}

// -------------------- Brewer (Account) --------------------
if (($section == "brewer") || ($section == "register") || ($section == "step2") || (($section == "admin") && ($go == "entrant")) || (($section == "admin") && ($go == "judge")) || (($section == "admin") && ($go == "steward"))) {
	$brewer_text_022 = "Podsládka budete moci uvést při vkládání vzorků.";
	$brewer_text_023 = "Zvolte &quot;Žádný&quot;, pokud nejste členy žádného klubu. Zvolte &quot;Jiný&quot; pokud není váš klub na seznamu - <strong>použijte funkci hledání</strong>.";
    $brewer_text_024 = "Prosím, zadejte křestní jméno.";
    $brewer_text_025 = "Prosím, zadejte příjmení.";
    $brewer_text_026 = "Prosím, zadejte své telefonní číslo.";
    $brewer_text_027 = "Prosím, zadejte svou adresu.";
    $brewer_text_028 = "Prosím, zadejte město.";
    $brewer_text_029 = "Prosím, zadejte stát nebo kraj.";
    $brewer_text_030 = "Prosím, zadejte PSČ.";
    $brewer_text_031 = "Prosím, zvolte zemi.";
    $brewer_text_032 = "Prosím, zadejte název své organizace.";
    $brewer_text_033 = "Prosím, zvolte bezpečnostní otázku.";
    $brewer_text_034 = "Prosím, zadejte odpověď na bezpečnostní otázku.";

    // v2.1.12
    $brewer_text_035 = "Složili jste zkoušku BJCP Cider Judge?";
}

// -------------------- Entry Info --------------------
if ($section == "entry") {
	$entry_info_text_047 = "Pokud je u názvu stylu odkaz, má tento zvláštní požadavky na vzorek. Klikněte nebo se dotkněte názvu pro otevření stylů Brewers Association tak, jak jsou uvedeny na jejich webu.";
}

// -------------------- List (User Entry List) --------------------
if (($section == "list") || ($section == "account") || ($go == "entries")) {
	$brewer_entries_text_016 = "Zadaný styl NENÍ přijatelný";
	$brewer_entries_text_017 = "Vzorky nebudou zobrazeny jako přijaté, dokud je personál soutěže takto neoznačí. Obvykle se tak stane PO TOM, co jsou přijaty veškeré vzorky ze všech míst příjmu a zaslání vzorků.";
	$brewer_entries_text_018 = "Dokumenty ke vzorku (např. štítky na lahve) nebude možné vytisknout dokud nebudou vzorky označeny jako uhrazené.";
	$brewer_entries_text_019 = "Tisk dokumentů vzorku není momentálně k dispozici.";
	$brewer_entries_text_020 = "Úprava vzorků není momentálně k dispozici. Pokud chcete vzorek upravit, kontaktujte představitele soutěže.";
}

if (SINGLE) $brewer_info_000 = "Dobrý den";
else $brewer_info_000 = "Děkujeme za účast na";
$brewer_info_001 = "Údaje vašeho účtu byly naposledy upraveny";
$brewer_info_002 = "Věnujte chvíli <a href=\"#entries\">kontrole svých vzorků</a>";
$brewer_info_003 = "uhradit poplatky za účast</a>";
$brewer_info_004 = "za vzorek";
$brewer_info_005 = "Členství v American Homebrewers Association (AHA) je povinné, pokud je některý z vašich vzorků vybrán pro Great American Beer Festival Pro-Am.";
$brewer_info_006 = "Vytisknout štítky lahví pro připevnění ke krabicím s vašimi lahvemi.";
$brewer_info_007 = "Vytisknout poštovní štítky";
$brewer_info_008 = "Byli jste již přiřazeni ke stolu jako";
$brewer_info_009 = "Pokud chcete upravit svoji dostupnost nebo zrušit roli, kontaktujte organizátora soutěže nebo koordinátora degustátorů.";
$brewer_info_010 = "Byli jste přiřazeni jako";
$brewer_info_011 = "nebo";
$brewer_info_012 = "Vytisknout štítky scoresheetů ";

// -------------------- Pay --------------------
$pay_text_030 = "Kliknutím na tlačítko &quot;Rozumím&quot; níže budete přesměrováni na PayPal pro provedení platby. Jakmile platbu <strong>dokončíte</strong>, PayPal vás přesměruje zpět na tuto stránku a odešle email s potvrzením transakce. <strong>Pokud bude vaše platba úspěšná, stav úhrady se zaktualizuje automaticky. Prosím, mějte na paměti, že to může chvilku trvat.</strong> Platební stránku obnovujte nebo si otevřete seznam svých vzorků.";
$pay_text_031 = "Budete přesměrováni jinam";
$pay_text_032 = "Platba není nutná. Děkujeme!";
$pay_text_033 = "Máte jeden nebo několik neuhrazených vzorků. Klikněte nebo se dotkněte pro uhrazení.";

// -------------------- Register --------------------
if (($section == "register") || ($section == "brewer") || ($action == "register") || ($go == "account") || ($section == "step2")) {
	$register_text_035 = "Informace, které poskytnete nad rámec názvu organizace jsou striktně pro náš záznam a případnou potřebu vás kontaktovat.";
	$register_text_036 = "Podmínkou vložení vzorku do soutěže je poskytnutí těchto informací včetně emailu kontaktní osoby a telefonního čísla. Název organizace může být uveden u vašeho vzorku, žádné další údaje však nezveřejníme.";
	$register_text_037 = "Potvrzení registace";
	$register_text_038 = "Správce vám vytvořil účet. Následující je potvrzením zadaných údajů:";
	$register_text_039 = "Děkujeme za registraci účtu. Následující je potvrzením údajů, které jste poskytli:";
	$register_text_040 = "Pokud je kterákoli informace výše nesprávná,";
	$register_text_041 = "přihlaste se do svého účtu";
	$register_text_042 = "a proveďte odpovídající změny. Hodně štěstí v soutěži!";
	$register_text_043 = "Prosím, nereagujte na tento email, jelikož je automaticky vygenerovaný. Odesílající účet není aktivní nebo monitorován.";
	$register_text_044 = "Prosím, zadejte název organizace.";
	$register_text_045 = "Zadejte název pivovaru, pivovarské pivnice, atd. Ujistěte se, že rozumíte pravidlům soutěže týkající se druhů přijímaných nápojů.";
	$register_text_046 = "Pouze pro americké organizace.";
}

// -------------------- User Registration --------------------
$user_text_004 = "Ujistěte se, že používáte malá a velká písmena, číslice a zvláštní znaky pro vytvoření silnějšího hesla.";
$user_text_005 = "Vaše aktuální emailová adresa je";

// -------------------- Login --------------------
$login_text_017 = "Zašlete mi odpověď na moji bezpečnostní otázku";
$login_text_018 = "Je vyžadováno vaše uživatelské jméno (emailová adresa).";
$login_text_019 = "Je vyžadováno heslo.";
$login_text_020 = "Zadaný token je již neplatný nebo byl již využit. Prosím, použijte funkci zapomenutého hesla znovu.";
$login_text_021 = "Zadaný token je již neplatný. Prosím, použijte funkci zapomenutého hesla znovu.";
$login_text_022 = "Zadaná emailová adresa není spojena s poskytnutým tokenem. Prosím, zkuste to znovu.";
$login_text_023 = "Hesla se neshodují. Zkuste to znovu.";
$login_text_024 = "Je vyžadováno potvrzení hesla.";
$login_text_025 = "Zapomněli jste heslo?";
$login_text_026 = "Zadejte emailovou adresu a nové heslo níže.";
$login_text_027 = "Vaše heslo bylo úspěšně obnoveno. Nyní se můžete přihlásit s novým heslem.";

// -------------------- Winners --------------------
$winners_text_005 = "Vítězové Best of Show nebyli ještě zveřejněni. Zkuste to později, prosím.";

// -------------------- Output - PayPal Response --------------------
$paypal_response_text_000 = "Platba byla úspěšně dokončena. Podrobnosti transakce jsou uvedeny níže.";
$paypal_response_text_001 = "Prosím, berte na vědomí, že dostanete ještě oficiální email od PayPalu na adresu uvedenou níže.";
$paypal_response_text_002 = "Hodně štěstí v soutěži!";
$paypal_response_text_003 = "Prosím, nereagujte na tento email, jelikož je automaticky vygenerovaný. Odesílající účet není aktivní nebo monitorován.";
$paypal_response_text_004 = "PayPal zpracoval vaši transakci.";
$paypal_response_text_005 = "Stav vaší platby přes PayPal je:";
$paypal_response_text_006 = "Odpověď PayPalu je &quot;neplatná.&quot;. Prosím, zkuste provést platbu znovu.";
$paypal_response_text_007 = "Prosím, kontaktujte organizátora soutěže, máte-li nějaké dotazy.";
$paypal_response_text_008 = "Neplatná platba přes PayPal";
$paypal_response_text_009 = "Podrobnosti platby přes PayPal";

// -------------------- Output - Password reset email text --------------------
$pwd_email_reset_text_000 = "Na webu";
$pwd_email_reset_text_001 = "byl vytvořen požadavek na ověření identity prostřednictvím emailu. Pokud jste tento požadavek nevytvořili vy, kontaktujte organizátora soutěže.";
$pwd_email_reset_text_002 = "Odpověď na ověření identity rozlišuje malá a velká písmena";
$pwd_email_reset_text_003 = "Na webu";
$pwd_email_reset_text_004 = "byl vytvořen požadavek pro obnovení vašeho hesla. Pokud jste tento požadavek nevytvořili vy, nemějte obavy. Vaše heslo nemůže být obnoveno bez použití odkazu níže.";
$pwd_email_reset_text_005 = "Pro obnovu vašeho hesla klikněte na odkaz níže nebo jej zkopírujte do svého prohlížeče.";

// -------------------- Best Brewer --------------------
$best_brewer_text_000 = "zúčastnění sládkové";
$best_brewer_text_001 = "HM";
$best_brewer_text_002 = "Hodnocení a rozstřely byly použity v souladu <a href=\"#\" data-toggle=\"modal\" data-target=\"#scoreMethod\">metodikou hodnocení</a>. Zobrazená čísla jsou zaokrouhlena na setinu. Přejeďte myší nebo klepněte na ikonu otazníku (<span class=\"fa fa-question-circle\"></span>) pro zobrazení přesné hodnoty.";
$best_brewer_text_003 = "Metodika hodnocení";
$best_brewer_text_004 = "Každý vzorek, který se umístil, byl oceněn následujícím počtem bodů:";
$best_brewer_text_005 = "Následující rozstřely byly použity, seřazeny podle priority:";
$best_brewer_text_006 = "Nejvyšší souhrnný počet prvních, druhých a třetích míst.";
$best_brewer_text_007 = "Nejvyšší počet prvních, druhých, třetích a čtvrtých míst a čestných uznání.";
$best_brewer_text_008 = "Nejvýšší počet prvních míst.";
$best_brewer_text_009 = "Nejnižší počet vzorků.";
$best_brewer_text_010 = "Nejvyšší minimální hodnocení.";
$best_brewer_text_011 = "Nejvyšší maximální hodnocení.";
$best_brewer_text_012 = "Nejvyšší průměrné hodnocení.";
$best_brewer_text_013 = "Nepoužito.";
$best_brewer_text_014 = "zúčastněné kluby";

// Version 2.1.12
$dropoff_qualifier_text_001 = "Prosím, vezměte na vědomí poznámky uvedené u každého místa příjmu vzorků. Některá místa mohou mít dřívější uzavření příjmových oken, určité časové rámce, kdy se vzorky příjmají nebo určité osoby, které vzorky převezmou a podobně. <strong class=\"text-danger\">Všichni soutěžící jsou povinni si přečíst pokyny poskytnuté organizátory ke každému místu příjmu vzorků.</strong>";

$brewer_text_036 = "Jelikož jste vybrali \"<em>Jiný</em>,\" ujistěte se, že zadaný klub není na našem seznamu v nějaké jiné podobě.";
$brewer_text_037 = "Například jste mohli hledat zkratku klubu místo jeho celého názvu.";
$brewer_text_038 = "Konzistentní název klubu je důležitý pro výpočet ocenění \"Nejlepší klub\", je-li v této soutěži použito.";
$brewer_text_039 = "Klub, který jste zadali, se neshoduje s klubem v seznamu.";
$brewer_text_040 = "Prosím, zvolte název klubu ze seznamu nebo zvolte <em>Jiný</em> a zadejte název klubu.";

// ----------------------------------------------------------------------------------
// Version 2.1.13 Additions
// ----------------------------------------------------------------------------------

$entry_info_text_048 = "Soutěžicí musí zadat dodatečné informace o nápoji pro zajištění správného posouzení.";
$entry_info_text_049 = "Soutěžicí musí zadat sílu nápoje pro zajištění správného posouzení.";
$entry_info_text_050 = "Soutěžicí musí zadat nasycení nápoje pro zajištění správného posouzení.";
$entry_info_text_051 = "Soutěžicí musí zadat sladkost nápoje pro zajištění správného posouzení.";
$entry_info_text_052 = "Soutěžicí musí zadat dodatečné informace o nápoji pro zajištění správného posouzení. Čím víc informací, tím lépe.";

$output_text_028 = "Následující vzorky obsahují tyto alergeny zadané soutěžícím.";
$output_text_029 = "Žádní soutěžící neuvedli informace o alergenech ve svých vzorcích.";

$label_this_style = "Tento styl (možná v angličtině)";
$label_notes = "Poznámky";
$label_possible_allergens = "Možné alergeny";
$label_please_choose = "Prosím zvolte";
$label_mead_cider_info = "Informace o medovině/cideru";

// ----------------------------------------------------------------------------------
// Version 2.1.14 Additions
// ----------------------------------------------------------------------------------

// Labels
$label_winners = "Vítězové";
$label_unconfirmed_entries = "Nepotvrzené vzorky";
$label_recipe = "Recept";
$label_view = "Zobrazit";
$label_number_bottles = "Počet vyžadovaných lahví od každého vzorku";
$label_pro_am = "Pro-Am";

// Pay screen
$pay_text_034 = "Maximální počet uhrazených vzorků byl dosažen - další platby již nejsou přijímány.";

// Bottle Labels
$bottle_labels_000 = "Štítky momentálně nelze vygenerovat";
$bottle_labels_001 = "Štítek připevněte k lahvi POUZE za pomocí gumičky.";
$bottle_labels_002 = "Use clear packing tape to attach to the barrel of each bottle.";
$bottle_labels_003 = "Etiketu zcela zakryjte!";
if (isset($_SESSION['jPrefsBottleNum'])) $bottle_labels_004 = "Upozornění: 4 štítky poskytujemem volně k dispozici. Tato soutěž vyžaduje ".$_SESSION['jPrefsBottleNum']." lahví od každého vzorku. Přebytečné štítky můžete vyhodit.";
else $bottle_labels_004 = "Upozornění: 4 štítky poskytujeme volně k dispozici. Přebytečné štítky můžete vyhodit.";
$bottle_labels_005 = "Pokud nějaký vzorek chybí, zavřete toto okno a upravte jej.";
$bottle_labels_006 = "Prostor vyhrazený pro personál soutěže.";
$bottle_labels_007 = "TENTO FORMULÁŘ RECEPTU JE POUZE PRO VAŠE POZNÁMKY - prosím, NEPŘIKLÁDEJTE JEJ k vašim vzorkům.";

// Add/Edit Entry
$brew_text_040 = "Lepek není třeba uvádět jako alergen; předpokládá se jeho přítomnost ve všech pivních stylech. Bezlepková piva by měla být přihlašována do kategorie Gluten-Free Beer (BA) nebo Alternative Grain Beer (BJCP). Lepek jako alergen uvádějte v medovině nebo cideru, pokud použitá cukernatá surovina obsahuje lepek (např. ječný, pšeničný nebo žitný slad) nebo pokud bylo použito pivovarských kvasnic.";

// Pro-Am
$brewer_text_041 = "Dostali jste již možnost Pro-Am, abyste mohli soutěži na nadcházející soutěži Great American Beer Festival Pro-Am?";
$brewer_text_042 = "Jestliže jste již obdrželi Pro-Am, uveďte to zde. Pomůžete personálu soutěže a představitelům pivovaru Pro-Am  (pokud je v této soutěži použito) vybrat vzorky Pro-Am od sládků, kteří ještě Pro-Am nemají.";

// ----------------------------------------------------------------------------------
// Version 2.1.15 Additions
// ----------------------------------------------------------------------------------

$label_submitting = "Odesílám";
$label_additional_info = "Vzorky s doplňujícími informacemi";
$label_working = "Pracuji";

$output_text_030 = "Prosím čekejte";

$brewer_entries_text_021 = "Zaškrtněte vzorky k tisku štítků. Zvolte zaškrtávací pole nahoře pro zaškrtnutí všech polí ve sloupci.";
$brewer_entries_text_022 = "Vytisknout štítky lahví všech zaškrtnutých vzorků";
$brewer_entries_text_023 = "Štítky lahví se otevřou na nové záložce nebo v novém okně.";
$brewer_entries_text_024 = "Vytisknout štítky lahví";

// ----------------------------------------------------------------------------------
// Version 2.1.18 Additions
// ----------------------------------------------------------------------------------

$output_text_031 = "Stisknutím klávesy Esc skryjete.";
$styles_entry_text_21X = "Účastník musí zadat sílu (relace: 3,0-5,0%, standard: 5,0-7,5%, dvojnásobek: 7,5-9,5%).";
$styles_entry_text_PRX4 = "Účastník musí uvést druhy použitých čerstvých plodů.";

// ----------------------------------------------------------------------------------
// Version 2.1.19 Additions (Via Google Translate English to Czech)
// ----------------------------------------------------------------------------------
$output_text_032 = "Počet vstupů odráží pouze účastníky, kteří ve svém profilu účtu uvedli místo, kde došlo k výpadku. Skutečný počet záznamů může být vyšší nebo nižší.";
$brewer_text_043 = "Nebo jste, nebo jste někdy byli zaměstnáni v pivovarnickém personálu v kterémkoli pivovaru? To zahrnuje pozice sládků, laboratorní techniky, posádky sklepů, plnění lahví / konzerváren atd. Současní a bývalí zaměstnanci pivovarnictví se nemohou účastnit soutěže Great American Beer Festival Pro-Am.";
$label_entrant_reg = "Vstupní Registrace";
$sidebar_text_026 = "jsou v systému k datu";
$label_paid_entries = "Placené položky";

// ***********************************************************************************
// END TRANSLATIONS
// ***********************************************************************************

// ----------------------------------------------------------------------------------
// Various conditionals
// No translations below this line
// ----------------------------------------------------------------------------------

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