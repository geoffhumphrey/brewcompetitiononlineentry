<?php
/**
 * Module:      cs-CZ.lang.php
 * Description: This module houses all display text in the Czech language.
 * Updated:     January 17, 2023
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
 * Spanish translated:
 * $label_volunteer_info = "Información de Voluntarios";
 * 
 * Portuguese translated:
 * $label_volunteer_info = "Informações Voluntário";
 * 
 * ========================================
 * 
 * Please note: the strings that need to be translated MAY contain HTML 
 * code. Please leave this code intact! For example:
 * 
 * English (US):
 * $beerxml_text_008 = "Browse for your BeerXML compliant file on your hard drive and select <em>Upload</em>.";
 * 
 * Spanish:
 * $beerxml_text_008 = "Buscar su archivo compatible BeerXML en su disco duro y haga clic en <em>Cargar</em>.";
 * 
 * Note that the <em>...</em> tags were not altered. Just the word "Upload" 
 * to "Cargar" between those tags.
 * 
 * ==============================
 * 
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
 * ------------------------------------------------------------------------
 * BEGIN TRANSLATIONS BELOW
 * ------------------------------------------------------------------------
 */

$j_s_text = "";
if (strpos($section, "step") === FALSE) {
    if ((isset($judge_limit)) && (isset($steward_limit))) {
        if (($judge_limit) && (!$steward_limit)) $j_s_text = "Obsluha"; // missing punctuation intentional
        elseif ((!$judge_limit) && ($steward_limit)) $j_s_text = "Degustátor"; // missing punctuation intentional
        else $j_s_text = "Obsluha nebo degustátor"; // missing punctuation intentional
    }
}

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
$label_past_winners = "Dřívější vítězové";
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
$label_required_info = "Upřesnění";
$label_character_limit = "znakový limit. Použijte klíčová slova a zkratky, pokud vám nestačí místo.<br>Použito znaků: ";
$label_carbonation = "Nasycení";
$label_sweetness = "Sladkost";
$label_strength = "Síla";
$label_color =  "Barva";
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
$label_sparkling = "Perlivý";
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
$label_hops = "Chmely";
$label_hop = "Chmel";
$label_mash = "Rmutované";
$label_steep = "Máčené";
$label_other = "Ostatní";
$label_weight = "Hmotnost";
$label_use = "Použití";
$label_time = "Čas";
$label_first_wort = "Předek";
$label_boil = "Chmelovar";
$label_aroma = "Aromatický";
$label_dry_hop = "Dry Hop";
$label_type = "Druh";
$label_bittering = "Hořký";
$label_both = "Dual purpose";
$label_form = "Forma";
$label_whole = "Hlávky";
$label_pellets = "Granule";
$label_plug = "Lisovaný";
$label_extract = "Extrakt";
$label_date = "Datum";
$label_bottled = "Lahvováno";
$label_misc = "Doplňky";
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
$label_judging_avail = "Mohu degustovat";
$label_stewarding = "Obsluha";
$label_stewarding_avail = "Mohu obsluhovat";
$label_bjcp_id = "BJCP ID";
$label_bjcp_mead = "Degustátor medoviny";
$label_bjcp_rank = "Úroveň BJCP";
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
$label_entry_per_entrant = "Maximální počet vzorků na účastníka";
$label_categories_accepted = "Přijímané styly";
$label_judging_categories = "Degustační kategorie";
$label_entry_acceptance_rules = "Pravidla příjmu vzorků";
$label_shipping_info = "Zasílání poštou";
$label_packing_shipping = "Balení a zasílání";
$label_awards = "Ceny";
$label_awards_ceremony = "Předávání cen";
$label_circuit = "Circuit Qualification";
$label_hosted = "Hostovaná verze";
$label_entry_check_in = "Přejímka vzorků";
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
$label_re_enter = "Zadejte znovu";
$label_website = "Webová stránka";
$label_place = "Místo";
$label_cheers = "Na zdraví";
$label_count = "Počet";
$label_total = "Celkem";
$label_participant = "Účastník";
$label_entrant = "Účastník";
$label_received = "Přijato";
$label_please_note = "Vezměte prosím na vědomí";
$label_pull_order = "Pořadí na stole";
$label_box = "Krabice";
$label_sorted = "Přijato";
$label_subcategory = "Podkategorie";
$label_affixed = "Štítek upevněn?";
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
$label_admin_upload_doc = "Nahrát degustační listy a další dokumenty";
$label_admin_password = "Změnit uživatelské heslo";
$label_admin_edit_account = "Upravit uživatelský účet";
$label_account_summary = "Přehled mého účtu";
$label_confirmed_entries = "Potvrzených vzorků";
$label_unpaid_confirmed_entries = "Neuhrazených potvrzených vzorků";
$label_total_entry_fees = "Celkové poplatky";
$label_entry_fees_to_pay = "Poplatky k úhradě";
$label_entry_drop_off = "Předání vzorků";
$label_maintenance = "Údržba";
$label_judge_info = "Informace o degustátorovi";
$label_cellar = "Můj sklep";
$label_verify = "Ověřit";
$label_entry_number = "Číslo vzorku";

$header_text_000 = "Instalace byla úspěšná.";
$header_text_001 = "Jste nyní přihlášeni, vše je připraveno pro další nastavení webu vaší soutěže.";
$header_text_002 = "Bohužel, oprávnění k vašemu config.php se nepodařilo změnit.";
$header_text_003 = "Je důrazně doporučeno změnit oprávnění (chmod) config.php na 555. Abyste to mohli provést, musíte mít přístup k souboru na vašem serveru.";
$header_text_004 = "Navc, proměnná &#36;setup_free_access v config.php je momentálně nastavena na TRUE. Z bezpečnostních důvodů by měla být upravena zpět na FALSE. Je třeba, abyste config.php upravili ručně a nahráli jej znovu na server.";
$header_text_005 = "Informace úspěšně přidány.";
$header_text_006 = "Informace úspěšně upraveny.";
$header_text_007 = "Chyba.";
$header_text_008 = "Prosím, zkuste to znovu.";
$header_text_009 = "Pro přístup do správy musíte být správce.";
$header_text_010 = "Změnit";
$header_text_011 = $label_email;
$header_text_012 = $label_password;
$header_text_013 = "Poskytnutá emailová adresa je již použita, prosím zadejte jinou.";
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
$header_text_040 = "Vaše zpráva byla odeslána uživateli";
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
$header_text_081 = "Všechny vzorky byly označeny jako uhrazené.";
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
$header_text_109 = "Byli jste zaregistrováni jako obsluha.";
$header_text_110 = "Všechny vzorky byly označeny jako neuhrazené.";
$header_text_111 = "Všechny vzorky byly označeny jako nepřijaté.";

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
$alert_text_030 = "Maximální počet vzorků byl dosažen.";
$alert_text_031 = "Všechna místa personálu jsou již obsazena.";
$alert_text_032 = "Vzorky bude možné zaregistrovat od ".$entry_open.".";
$alert_text_033 = "Soutěžící se mohou registrovat od ".$reg_open.".";
$alert_text_034 = "Pro registraci uživatelského účtu navštivte náš web později.";
$alert_text_036 = "Registrace vzorků se otevře ".$entry_open.".";
$alert_text_037 = "Pro registraci vašich vzorků navštivte náš web později.";
$alert_text_039 = "Registrace personálu se otevře ".$judge_open.".";
$alert_text_040 = "Pro registraci jako obsluha nebo degustátor navštivte náš web později.";
$alert_text_042 = "Registrace vzorků je otevřena!";
$alert_text_043 = "K datum ".$current_time." jsme zatím přijali ".$total_entries." přihlášek.";
$alert_text_044 = "Registrace se uzavře ";
$alert_text_046 = "Maximální počet vzorků byl téměř dosažen!";
$alert_text_047 = "K datu ".$current_time." bylo vloženo celkem ".$total_entries." vzorků z maxima ".$row_limits['prefsEntryLimit'].".";
$alert_text_049 = "Maximální počet vzorků byl dosažen.";
$alert_text_050 = "Maximální počet vzorků ".$row_limits['prefsEntryLimit']." byl dosažen. Registrace vzorků byla uzavřena.";
$alert_text_052 = "Maximální počet uhrazených vzorků byl dosažen.";
$alert_text_053 = "Maximální počet <em>uhrazených</em> vzorků ".$row_limits['prefsEntryLimitPaid']." byl dosažen. Registrace vzorků byla uzavřena.";
$alert_text_055 = "Registrace vzorků je uzavřena.";
$alert_text_056 = "Pokud máte na webu vytvořený účet,";
$alert_text_057 = "přihlaste se"; // lower-case and missing punctuation intentional
$alert_text_059 = "Registrace vzorků je uzavřena.";
$alert_text_060 = "Celkem bylo do systému vloženo ".$total_entries." vzorků.";
$alert_text_062 = "Místo příjmu vzorků je uzavřeno.";
$alert_text_063 = "Vzorky momentálně nelze předávat osobně.";
$alert_text_065 = "Zasílání vzorků je uzavřeno.";
$alert_text_066 = "Vzorky momentálně není možné zasílat poštou.";
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

$comps_text_000 = "Prosím, zvolte v seznamu níže, kterou soutěž chcete otevřít.";
$comps_text_001 = "Aktuální soutěž:";
$comps_text_002 = "Momentálně nejsou k dispozici žádné soutěže s otevřeným přihlašováním.";
$comps_text_003 = "Momentálně nejsou k dispozici žádné soutěže s přihlašováním, které se uzavírá během následujících 7 dnů.";

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

$brew_text_000 = "Klikněte sem pro zobrazení podrobností stylu"; // missing punctuation intentional
$brew_text_001 = "Degustátoři nebudou znát název vašeho vzorku.";
$brew_text_002 = "[vypnuto - limit počtu vzorků ve stylu dosažen]"; // missing punctuation intentional
$brew_text_003 = "[vypnuto - limit počtu vzorků uživatele ve stylu dosažen]"; // missing punctuation intentional
$brew_text_004 = "Je požadován přesný druh, zvláštní suroviny, název klasického stylu, síla (u pivních stylů) nebo barva.";
$brew_text_005 = "Musíte zvolit sílu vzorku"; // missing punctuation intentional
$brew_text_006 = "Musíte zvolit nasycení vzorku"; // missing punctuation intentional
$brew_text_007 = "Musíte zvolit sladkost vzorku"; // missing punctuation intentional
$brew_text_008 = "Tento styl vyžaduje zadání podrobností k vašemu vzorku.";
$brew_text_009 = "Požadavky na"; // missing punctuation intentional
$brew_text_010 = "K tomuto stylu potřebujeme doplňující údaje. Prosím, zadejte je do nabízených polí.";
$brew_text_011 = "Název vzorku je povinný.";
$brew_text_012 = "***NEPOVINNÉ*** Pokud chcete, aby degustátoři vzali v úvahu to, co uvádíte, při posuzování a hodnocení vašeho vzorku. Uveďte pouze informace, které NENÍ MOŽNO ZADAT do jiných polí (např. způsob chmelení, druh chmele, druh medu, druh hroznů, druh hrušek, atd.)";
$brew_text_013 = "NEPOUŽÍVEJTE toto pole pro uvedení zvláštních surovin, názvu klasického stylu, síly nebo barvy (tedy povinných údajů).";
$brew_text_014 = "Použijte pouze, pokud chcete, aby tyto informace vzali degustátoři na vědomí při posuzování a hodnocení vašeho vzorku.";
$brew_text_015 = "Druh výtažku (např. světlý, tmavý) nebo značka.";
$brew_text_016 = "Druh sladu (plzeňský, pale ale, atd.)";
$brew_text_017 = "Druh nebo název suroviny";
$brew_text_018 = "Název chmelu.";
$brew_text_019 = "Pouze čísla.";
$brew_text_020 = "Název kmene (např. 1056 American Ale).";
$brew_text_021 = "Wyeast, White Labs, atd.";
$brew_text_022 = "1 balíček, 2 ampule, 2000 ml, atd.";
$brew_text_023 = "Délka hlavního kvašení ve dnech.";
$brew_text_024 = "Cukrotvorná teplota, aj.";
$brew_text_025 = "Délka dokvašování ve dnech.";
$brew_text_026 = "Délka dalšího kvašení ve dnech.";

$brewer_text_000 = "Prosím, zadejte jméno pouze <em>jedné</em> osoby.";
$brewer_text_001 = "Zvolte jednu z otázek. Tato otázka bude použita pro ověření vaší identity když zapomente heslo.";
$brewer_text_003 = "Abyste mohli být vybráni na GABF Pro-Am brewing opportunity, musíte být členem AHA.";
$brewer_text_004 = "Uveďte jakékoli informace, které si myslíte, že by měl organizátor, koordinátor degustátorů nebo personál vědět (např. alergie, speciální výživové požadavky, velikost trička atd.).";
$brewer_text_005 = "Nepoužije se";
$brewer_text_006 = "Chcete se účastnit jako degustátor a máte na to dostatečnou kvalifikaci?";
$brewer_text_007 = "Absolvovali jste zkoušku BJCP Mead Judge?";
$brewer_text_008 = "* Úroveň <em>Non-BJCP</em> je pro ty, kteří ještě neabsolvovali BJCP Beer Judge Entrance Exam a <em>nejsou</em> profesionálními sládky.";
$brewer_text_009 = "** Úroveň <em>Provisional</em> je pro ty, kteří již absolvovali BJCP Beer Judge Entrance Exam, ale ještě neabsolvovali BJCP Beer Judging Exam.";
$brewer_text_010 = "V degustačních listech se zobrazí pouze první dvě role.";
$brewer_text_011 = "Kolika soutěží jste se už zúčastnili jako <strong>".strtolower($label_judge)."</strong>?";
$brewer_text_012 = "Pouze pro volbu obliby. Pokud necháte styl nezaškrtnutý, značí to, že jej můžete posuzovat. Není třeba zaškrtávat všechny styly, které byste mohli posuzovat.";
$brewer_text_013 = "Klikněte nebo se dotkněte tlačítka výše pro rozbalení seznamu neoblíbených stylů.";
$brewer_text_014 = "Není třeba označovat styly, ve kterých máte přihlášené vzorky; systém nepovolí, abyste byli přiřazeni ke stolu, kde by se nacházel váš vzorek.";
$brewer_text_015 = "Jste ochotni se soutěže účastnit jako obsluha?";
$brewer_text_016 = "Moje účast v této degustaci je zcela dobrovolná. Jsem si vědom(a) toho, že degustace zahrnuje konzumaci alkoholických nápojů a může ovlivnit moje vnímání a reakce.";
$brewer_text_017 = "Klikněte nebo se dotkněte tlačítka výše pro rozbalení oblíbených pivních stylů.";
$brewer_text_018 = "Zaškrtnutím tohoto políčka fakticky podepisuji právní dokument, kterým přijímám odpovědnost za svoje činy a chování a zbavuji organizátory, jednotlivě nebo společně, odpovědnosti za tyto činy a chování.";
$brewer_text_019 = "Pokud se chcete účastnit soutěže jako degustátor, klikněte nebo se dotkněte tlačítka výše pro zadání informací souvisejících s touto rolí.";
$brewer_text_020 = "Jste ochotni se soutěže účastnit jako personál?";
$brewer_text_021 = "Personál soutěže jsou osoby, které plní rozličné role v organizaci a uskutečnění soutěže před, během a po skončení degustace. Degustátoři a obsluha mohou taktéž být členy personálu. Pokud je soutěž uznaná BJCP, získají tito, po skončení soutěže, body do svého profilu.";

$contact_text_000 = "Pro kontaktování osob zodpovědných za soutěž využijte odkazy níže:";
$contact_text_001 = "Pro kontaktování organizátora využijte formulář níže. Pole označená hvězdičkou jsou povinná.";
$contact_text_002 = "Kopie byla odeslána na emailovou adresu, kterou jse uvedli.";
$contact_text_003 = "Chcete odeslat další zprávu?";

$default_page_text_000 = "Nebyla zvolena žádná místa příjmu vzorků.";
$default_page_text_001 = "Přidat místo příjmu vzorků?";
$default_page_text_002 = "Nebyla zvolena žádná data/místa degustace.";
$default_page_text_003 = "Přidat místo degustace?";
$default_page_text_004 = "Vítězové";
$default_page_text_005 = "Vítězové budou uvedeni počínaje";
$default_page_text_006 = "Vítejte";
$default_page_text_007 = "Prohlédněte si podrobnosti svého účtu a seznam vzorků.";
$default_page_text_008 = "Zobrazit podrobnosti účtu.";
$default_page_text_009 = "Vítězové Best of Show";
$default_page_text_010 = "Vítězové";
$default_page_text_011 = "Své osobní údaje do soutěže zadáváte pouze jednou. Následně se můžete na web vrátit a pokračovat ve vkládání vzorků nebo upravit ty už vložené.";
$default_page_text_012 = "Můžete taktéž zaplatit poplatky za vzorky online.";
$default_page_text_013 = "Organizátor soutěže";
$default_page_text_014 = "Organizátoři soutěže";
$default_page_text_015 = "Každé z osob můžete zaslat email prostřednictvím stránky ";
$default_page_text_016 = "děkuji subjektům níže. Tyto subjekty jsou";
$default_page_text_017 = "na";
$default_page_text_018 = "Stáhněte si vítěze Best of Show ve formátu PDF.";
$default_page_text_019 = "Stáhněte si vítěze Best of Show ve formátu HTML.";
$default_page_text_020 = "Stáhněte si vítězné vzorky ve formátu PDF.";
$default_page_text_021 = "Stáhněte si vítězné vzorky ve formátu HTML.";
$default_page_text_022 = "Děkujeme za váš zájem o soutěž";
$default_page_text_023 = ", která se koná v";

$reg_open_text_000 = "Registrace degustátorů a obsluhy je";
$reg_open_text_001 = "otevřená";
$reg_open_text_002 = "Pokud zatím <em>nemáte registraci</em>, a chcete se stát dobrovolníkem,";
$reg_open_text_003 = "zaregistrujte se, prosím";
$reg_open_text_004 = "Jestliže <em>už registraci máte</em>, přihlaste se a zvolte <em>Upravit účet</em> přes Můj účet označený ikonou";
$reg_open_text_005 = "v horní liště.";
$reg_open_text_006 = "Protože už jste registrováni, můžete";
$reg_open_text_007 = "si prohlédnout údaje svého účtu";
$reg_open_text_008 = ", kde si ověříte, zda jste se označili jako degustátor nebo obsluha v soutěži.";
$reg_open_text_009 = "Pokud se chcete účastnit jako degustátor nebo obsluha, navštivte, prosím, náš web po";
$reg_open_text_010 = "Registrace soutěžních vzorků je";
$reg_open_text_011 = "Abyste mohli vložit svá piva do soutěže";
$reg_open_text_012 = "zaregistrujte se, prosím";
$reg_open_text_013 = ", pokud již máte účet.";
$reg_open_text_014 = "použijte formulář přidání vzorků,";
$reg_open_text_015 = "Registrace degustátorů je";
$reg_open_text_016 = "Registrace obsluhy je";

$reg_closed_text_000 = "Díky a hodně štěstí všem, kteří se zaregistrovali do soutěže";
$reg_closed_text_001 = "Momentálně máme";
$reg_closed_text_002 = "zaregistrovaných soutěžících, degustátorů a obsluhujících.";
$reg_closed_text_003 = "zaregistrovaných vzorků a";
$reg_closed_text_004 = "zaregistrovaných soutěžících, degustátorů a obsluhujících.";
$reg_closed_text_005 = "Momentálně máme celkem";
$reg_closed_text_006 = "přijatých a zpracovaných vzorků. Číslo se bude měnit podle toho, jak budeme přebírat vzorky na místě příjmu vzorků pro posouzení.";
$reg_closed_text_007 = "Data degustace nebyla zatím určena. Prosím, navštivte web později.";
$reg_closed_text_008 = "Map to";

$judge_closed_000 = "Díky všem, kteří se zúčastnili soutěže";
$judge_closed_001 = "Celkem bylo posouzeno";
$judge_closed_002 = "vzorků a účastnilo se";
$judge_closed_003 = "soutěžících, degustátorů a obsluhujících.";

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
$entry_info_text_019 = "V soutěži může být nejvýše";
$entry_info_text_020 = "vzorků.";
$entry_info_text_021 = "Každý soutěžící může vložit nejvýše";
$entry_info_text_022 = strtolower($label_entry);
$entry_info_text_023 = "vzorků";
$entry_info_text_024 = "vzorek na podkategorii";
$entry_info_text_025 = "vzorků na podkategorii";
$entry_info_text_026 = "výjimky jsou uvedeny níže";
$entry_info_text_027 = "podkategorii";
$entry_info_text_028 = "podkategoriích";
$entry_info_text_029 = "vzorek v následujících";
$entry_info_text_030 = "vzorků v následující";
$entry_info_text_031 = "Po vytvoření účtu a vložení vzorků do systému musíte uhradit poplatek za vzorky. Zaplatit můžete následujícími způsoby:";
$entry_info_text_032 = $label_cash;
$entry_info_text_033 = $label_check.", vystavený na";
$entry_info_text_034 = "Kreditní/debetní karta a e-check (prostřednictvím PayPalu)";
$entry_info_text_035 = "Data degustace ještě nebyla určena. Prosím, navštivte náš web později.";
$entry_info_text_036 = "Lahve se vzorky přijímáme na poštovní adrese počínaje";
$entry_info_text_037 = "Vzorky zasílejte na následující adresu:";
$entry_info_text_038 = "Pečlivě zabalte své vzorky do pevné krabice. Vnitřek krabice vyložte plastovým pytlem na odpad. Oddělte a zabalte každou lahev odpovídajícím balicím materiálem. S množstvím materiálu to nepřehánějte!";
$entry_info_text_039 = "Na balík napište: <em>Křehké! Neklopit!</em> Pro balení používejte dostatek bublinkové fólii nebo každou lahev vložte do vaku AirCover.";
$entry_info_text_040 = "<em>Každý</em> ze štítků na vašich lahvích zabalete do malého sáčku před jeho upevněním na lahev. Tímto způsobem nám usnadníte určení, který vzorek se rozbil, pokud se něco stane při přepravě.";
$entry_info_text_041 = "Vynasnažíme se vás kontaktovat, pokud se některý z vašich vzorků při přepravě rozbije.";
$entry_info_text_042 = "Pokud se soutěž koná ve Spojených státech, mějte na paměti, že je <strong>zakázáno</strong> posílat vzorky pomocí United States Postal Service (USPS). <em>Pozn. překl.: Na zásilky zaslané prostřednictvím České pošty se žádný podobný zákaz nevztahuje.</em><br />Soukromé přepravní společnosti vám mohou odmítnout přepravu, pokud se dozví, že zásilka obsahuje sklo nebo alkoholické nápoje. Berte na vědomí, že vzorky zaslané mezinárodně mohou podléhat celnímu řízení. Zásilky mohou být otevřeny a vráceny odesilateli celní správou dle jejího uvážení. Je vaší povinností se řídit příslušnými zákony a nařízeními.";
$entry_info_text_043 = "Začátek příjmu vzorků je";
$entry_info_text_044 = "Zobrazit na mapě místo";
$entry_info_text_045 = "Klikněte pro povinné informace o vzorku";
$entry_info_text_046 = "Pokud je název stylu s odkazem, má styl povinné požadavky na vzorek. Klikněte nebo se dotkněte názvu pro zobrazení požadavků podkategorie.";

$brewer_entries_text_000 = "U prohlížeče Firefox existuje známá chyba s tiskem.";
$brewer_entries_text_001 = "Máte nepotvrzené vzorky.";
$brewer_entries_text_002 = "U každého vzorku níže, označeného ikonou <span class=\"fa fa-lg fa-exclamation-circle text-danger\"></span>, klikněte na ikonu <span class=\"fa fa-lg fa-pencil text-primary\"></span> pro jeho prohlédnutí a potvrzení údajů. Nepotvrzené vzorky mohou být smazány ze systému bez předchozího upozornění.";
$brewer_entries_text_003 = "NEMŮŽETE zaplatit za vzorky dokud jste všechny nepotvrdili.";
$brewer_entries_text_004 = "Přihlásili jste vzorky, které vyžadují definici specifického stylu, zvláštních surovin, názvu klasického stylu, síly nebo barvy.";
$brewer_entries_text_005 = "U každého vzorku níže, označeného ikonou <span class=\"fa fa-lg fa-exclamation-circle text-danger\"></span>, klikněte na ikonu <span class=\"fa fa-lg fa-pencil text-primary\"></span> pro zadání povinných údajů. Vzorky bez uvedeného specifického stylu, zvláštních surovin, názvu klasického stylu, síly nebo barvy mohou být smazány ze systému bez předchozího upozornění.";
$brewer_entries_text_006 = "Stáhnout degustační listy";
$brewer_entries_text_007 = "Styl NEZADÁN";
$brewer_entries_text_008 = "Entry Form and";
$brewer_entries_text_009 = "Štítky lahví";
$brewer_entries_text_010 = "Vytisknout formulář receptu";
$brewer_entries_text_011 = "Taktéž nebudete moci přidat další vzorek protože byl dosažen maximální počet vzorků soutěže. Klikněte na Zrušit v tomto poli a potom místo toho vzorek upravte, pokud jej chcete zachovat.";
$brewer_entries_text_012 = "Opravdu chcete smazat vzorek s názvem";
$brewer_entries_text_013 = "Následně budete moci přidat další vzorky do";
$brewer_entries_text_014 = "Zatím jste nevložili žádné vzorky.";
$brewer_entries_text_015 = "Momentálně nelze smazat tento vzorek.";

$past_winners_text_000 = "Zobrazit dřívější vítěze:";

$pay_text_000 = "Jelikož již uplynul čas pro registraci, vkládání vzorků, zasílání a přejímku, nelze již zaplatit.";
$pay_text_001 = "Kontaktujte zástupce soutěže, máte-li nějaké dotazy.";
$pay_text_002 = "následující jsou vaše možnosti úhrady poplatků.";
$pay_text_003 = "Poplatek činí";
$pay_text_004 = "za vzorek";
$pay_text_005 = "za vzorek od";
$pay_text_006 = "za neomezený počet vzorků";
$pay_text_007 = "Vaše poplatky byly sníženy na";
$pay_text_008 = "Celková částka k úhradě je";
$pay_text_009 = "Je třeba uhradit";
$pay_text_010 = "Vaše poplatky byly uhrazeny. Děkujeme!";
$pay_text_011 = "Aktuálně máte";
$pay_text_012 = "potvrzených, neuhrazených";
$pay_text_013 = "Přiložte šek na celkovou částku k jedné z vašich lahví. Šek vystavte na";
$pay_text_014 = "Kopie vašeho šeku nebo proplacený šek je dokladem o zaplacení.";
$pay_text_015 = "Přiložte částku v hotovosti za všechny vzorky v <em>zalepené obálce</em> k jedné z vašich lahví.";
$pay_text_016 = "Vrácené degustační listy poslouží jako potvrzení příjmu vzorků.";
$pay_text_017 = "Email s potvrzením o platbě je dokladem o zaplacení. Přiložte jej k vašim vzorkům pro potvrzení.";
$pay_text_018 = "Klikněte na tlačítko <em>Pay with PayPal</em> pro zaplacení online.";
$pay_text_019 = "Prosím, vezměte na vědomí, že k vaší platbě bude naúčtováno";
$pay_text_020 = "jako poplatek za transakci.";
$pay_text_021 = "Pro zajištění toho, že náš web platbu správně zaregistruje jako <strong>uhrazenou</strong>, klikněte na obrazovce potvrzení platby PayPalu na odkaz <em>Vrátit se na web...</em> <strong>po</strong> uhrazení platby. Taktéž si vytiskněte potvrzení a přiložte jej k jedné z vašich lahví.";
$pay_text_022 = "Klikněte na <em>Vrátit se zpět na web...</em> po dokončení platby";
$pay_text_023 = "Zadejte slevový kód pro získání slevy z poplatku.";
$pay_text_024 = $pay_text_010;
$pay_text_025 = "Zatím jste nevložili žádné vzorky.";
$pay_text_026 = "Své vzorky nemůžete uhradit, jelikož některé z nich nebyly dosud potvrzeny.";
$pay_text_027 = "Klikněte na <em>Můj účet</em> výše pro zobrazení seznamu nepotvrzených vzorků.";
$pay_text_028 = "Máte nepotvrzené vzorky, které <em>nejsou</em> zahrnuty do celkového součtu poplatků níže.";
$pay_text_029 = "Prosím, otevřete si seznam vzorků a potvrďte všechny vaše vzorky. Nepotvrzené vzorky mohou být smazány ze systému bez předchozího varování.";

// Ignore the next four lines
if (strpos($view, "^") !== FALSE) {
    $qr_text_019 = sprintf("%06d",$checked_in_numbers[0]);
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
$sidebar_text_022 = "Pro předání vzorku využijte";
$sidebar_text_023 = "místo zaslání vzorků";
$sidebar_text_024 = "Data degustací nebyla ještě stanovena. Prosím, vraťte se později.";
$sidebar_text_025 = "je zaregistrováno k datu";

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

$user_text_000 = "Je vyžadována nová emailová adresa v platném tvaru.";
$user_text_001 = "Zadejte původní heslo.";
$user_text_002 = "Zadejte nové heslo.";
$user_text_003 = "Prosím, zaškrtněte toto pole, pokud chcete změnit svou emailovou adresu.";

$volunteers_text_000 = "Pokud jste již zaregistrovaní,";
$volunteers_text_001 = "a potovm zvolte <em>Upravit účet</em> z nabídky můj účet uvozené ikonou";
$volunteers_text_002 = "v horní nabídce";
$volunteers_text_003 = "a";
$volunteers_text_004 = "Pokud ještě <em>nejste</em> zaregistrováni a chcete se zúčastnit jako degustátor nebo obsluha, prosím zaregistrujte se";
$volunteers_text_005 = "Jelikož jste již zaregistrováni,";
$volunteers_text_006 = "otevřete si podrobnosti svého účtu,";
$volunteers_text_007 = "pro ověření, zda jste přihlášeni jako dobrovolník pro degustace nebo obsluha";
$volunteers_text_008 = "Pokud se chcete účastnit jako degustátor nebo obsluha, vraťte se prosím nejdříve";
$volunteers_text_009 = "Pokud chcete být dobrovolníkem, součástí personálu soutěže, prosím zaregistrujte se nebo ve svém účtu zvolte, že chcete být součástí personálu.";
$volunteers_text_010 = "";

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

$winners_text_000 = "U tohoto stolu nebyli zadáni žádní vítězové. Zkuste to později, prosím.";
$winners_text_001 = "Vítězové nebyli ještě zveřejněni. Zkuste to později, prosím.";
$winners_text_002 = "Zvolená struktura udílení cen je udílení podle stolů. Zvolte místa udílení stolu jako celku níže.";
$winners_text_003 = "Zvolená struktura udílení cen je udílení podle kategorie. Zvolte místa udílení pro každou kategorii jako celku (u tohoto stolu může být více než jedna).";
$winners_text_004 = "Zvolená struktura udílení cen je podle podkategorie. Zvolte místa udílení pro každou z podkategorií níže (u tohoto stolu může být více než jedna).";

$output_text_000 = "Děkujeme za účast v soutěži";
$output_text_001 = "Souhrn vašich vzorků, hodnocení a míst je níže.";
$output_text_002 = "Souhrn pro";
$output_text_003 = "vzorků posouzeno";
$output_text_004 = "Vaše degustační listy nelze řádně vygenerovat. Prosím, kontaktujte organizátory soutěže.";
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

$maintenance_text_000 = "Správce webu stránku dočasně odpojil pro provedení údržby.";
$maintenance_text_001 = "Zkuste to později, prosím.";

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
$label_secret_08 = "Jak se jmenoval váš třídní ve třetí třídě?";
$label_secret_09 = "V jakém městě nebo obci jste potkali svého partnera?";
$label_secret_10 = "Jaké je křestní jméno vašeho nejlepšího kamaráda nebo kamarádky v šesté třídě?";
$label_secret_11 = "Jaký je váš oblíbený interpret nebo skupina?";
$label_secret_12 = "Jakou jste v dětství měli přezdívku?";
$label_secret_13 = "Jak se jmenoval učitel, který vám poprvé dal pětku?";
$label_secret_14 = "Jak se jmenoval váš nejlepší kamarád z dětství?";
$label_secret_15 = "Ve kterém městě se setkali vaši rodiče?";
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
$label_jump_to = "Přejít na...";
$label_top = "Nahoru";
$label_bjcp_cider = "Degustátor cideru";

$header_text_112 = "Nemáte dostatečná oprávnění pro provedení této akce.";
$header_text_113 = "Můžete upravovat údaje pouze svého účtu.";
$header_text_114 = "Jako správce můžete upravit údaje uživatelských účtů přes nabídku Admin &quot; Účastníci a vzorky &quot; Správa účastníků.";
$header_text_115 = "Výsledky byly zveřejněny.";
$header_text_116 = "Pokud v rozumném čase nedostanete email, kontaktujte představitele soutěže nebo správce webu aby vám obnovil heslo.";

$alert_text_082 = "Jelikož jste se zaregistrovali jako degustátor nebo obsluha, nemáte dovoleno vkládat do vašeho účtu vzorky. Toto mohou činit pouze představitelé organizací.";
$alert_text_083 = "Přidávání nebo úprava vzorků nejsou k dispozici.";
$alert_text_084 = "Jako správce můžete přidat vzorky k účtu organizace pomocí položky &quot;Přidat vzorek pro...&quot; z rozbalovacího menu na stránce správy Vzorky a účastníci &gt; Správa vzorků.";
$alert_text_085 = "Nebudete si moci vytisknout žádné dokumenty vzorku (např. štítky na lahve) dokud nebude platba potvrzena a vzorek níže označen jako &quot;uhrazený&quot;.";

$brew_text_027 = "Tento styl Brewers Association vyžaduje prohlášení od sládka ohledně zvláštního charakteru produktu. Prohlédněte si <a href=\"https://www.brewersassociation.org/resources/brewers-association-beer-style-guidelines/\" target=\"_blank\">BA Style Guidelines</a> pro podrobné informace.";
$brew_text_028 = "***NENÍ POVINNÉ*** Vložte sem informace, které jsou uvedeny v guidelines jako ty, které MŮŽETE uvést.";
$brew_text_029 = "Úprava správce vypnuta. Váš profil je považován za osobní a ne profil organizace a tudíž nemá nárok na přidání vzorků. Pro přidání vzorků za organizaci, otevřete si seznam Správa vzorků a zvolte organizaci v nabídce &quot;Přidat vzorek pro...&quot;";

$brew_text_030 = "mléko / laktóza";
$brew_text_031 = "vejce";
$brew_text_032 = "ryby";
$brew_text_033 = "korýši";
$brew_text_034 = "skořápkové plody";
$brew_text_035 = "podzemnice olejná (arašídy)";
$brew_text_036 = "pšenice";
$brew_text_037 = "sójové boby";
$brew_text_038 = "Obsahuje tento vzorek alergeny? Běžnými alergeny může být mléko (včetně laktózy), vejce, ryby, korýři, skořápkové plody, podzemnice olejná (arašídy), pšenice, sójové boby a podobně.";

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
$brewer_text_035 = "Složili jste zkoušku BJCP Cider Judge?";

$entry_info_text_047 = "Pokud je u názvu stylu odkaz, má tento zvláštní požadavky na vzorek. Klikněte nebo se dotkněte názvu pro otevření stylů Brewers Association tak, jak jsou uvedeny na jejich webu.";

$brewer_entries_text_016 = "Zadaný styl NENÍ přijatelný";
$brewer_entries_text_017 = "Vzorky nebudou zobrazeny jako přijaté, dokud je personál soutěže takto neoznačí. Obvykle se tak stane PO TOM, co jsou přijaty veškeré vzorky ze všech míst příjmu a zaslání vzorků.";
$brewer_entries_text_018 = "Dokumenty ke vzorku (např. štítky na lahve) nebude možné vytisknout dokud nebudou vzorky označeny jako uhrazené.";
$brewer_entries_text_019 = "Tisk dokumentů vzorku není momentálně k dispozici.";
$brewer_entries_text_020 = "Úprava vzorků není momentálně k dispozici. Pokud chcete vzorek upravit, kontaktujte představitele soutěže.";

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
$brewer_info_012 = "Vytisknout štítky degustačních listů ";

$pay_text_030 = "Kliknutím na tlačítko &quot;Rozumím&quot; níže budete přesměrováni na PayPal pro provedení platby. Jakmile platbu <strong>dokončíte</strong>, PayPal vás přesměruje zpět na tuto stránku a odešle email s potvrzením transakce. <strong>Pokud bude vaše platba úspěšná, stav úhrady se zaktualizuje automaticky. Prosím, mějte na paměti, že to může chvilku trvat.</strong> Platební stránku obnovujte nebo si otevřete seznam svých vzorků.";
$pay_text_031 = "Budete přesměrováni jinam";
$pay_text_032 = "Platba není nutná. Děkujeme!";
$pay_text_033 = "Máte jeden nebo několik neuhrazených vzorků. Klikněte nebo se dotkněte pro uhrazení.";

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

$user_text_004 = "Ujistěte se, že používáte malá a velká písmena, číslice a zvláštní znaky pro vytvoření silnějšího hesla.";
$user_text_005 = "Vaše aktuální emailová adresa je";

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

$winners_text_005 = "Vítězové Best of Show nebyli ještě zveřejněni. Zkuste to později, prosím.";

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

$pwd_email_reset_text_000 = "Na webu";
$pwd_email_reset_text_001 = "byl vytvořen požadavek na ověření identity prostřednictvím emailu. Pokud jste tento požadavek nevytvořili vy, kontaktujte organizátora soutěže.";
$pwd_email_reset_text_002 = "Odpověď na ověření identity rozlišuje malá a velká písmena";
$pwd_email_reset_text_003 = "Na webu";
$pwd_email_reset_text_004 = "byl vytvořen požadavek pro obnovení vašeho hesla. Pokud jste tento požadavek nevytvořili vy, nemějte obavy. Vaše heslo nemůže být obnoveno bez použití odkazu níže.";
$pwd_email_reset_text_005 = "Pro obnovu vašeho hesla klikněte na odkaz níže nebo jej zkopírujte do svého prohlížeče.";

$best_brewer_text_000 = "zúčastněných sládků";
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

$dropoff_qualifier_text_001 = "Prosím, berte na vědomí poznámky uvedené u míst přijmu vzorků. Některá místa mohou mít dřívější uzavření příjmových oken, určité časové rámce, kdy se vzorky příjmají nebo určité osoby, které vzorky převezmou a podobně. <strong class=\"text-danger\">Všichni soutěžící jsou povinni si přečíst pokyny poskytnuté organizátory k místům příjmu vzorků.</strong>";

$brewer_text_036 = "Jelikož jste vybrali \"<em>Jiný</em>,\" ujistěte se, že zadaný klub není na našem seznamu v nějaké jiné podobě.";
$brewer_text_037 = "Například jste mohli hledat zkratku klubu místo jeho celého názvu.";
$brewer_text_038 = "Konzistentní název klubu je důležitý pro výpočet ocenění \"Nejlepší klub\", je-li v této soutěži použito.";
$brewer_text_039 = "Klub, který jste zadali, se neshoduje s klubem v seznamu.";
$brewer_text_040 = "Prosím, zvolte název klubu ze seznamu nebo zvolte <em>Jiný</em> a zadejte název klubu.";

/**
 * ------------------------------------------------------------------------
 * Version 2.1.13 Additions
 * ------------------------------------------------------------------------
 */
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

/**
 * ------------------------------------------------------------------------
 * Version 2.1.14 Additions
 * ------------------------------------------------------------------------
 */

$label_winners = "Vítězové";
$label_unconfirmed_entries = "Nepotvrzené vzorky";
$label_recipe = "Recept";
$label_view = "Zobrazit";
$label_number_bottles = "Požadovaný počet lahví každého vzorku";
$label_pro_am = "Pro-Am";

$pay_text_034 = "Maximální počet uhrazených vzorků byl dosažen - další platby již nejsou přijímány.";

$bottle_labels_000 = "Štítky momentálně nelze vygenerovat";
$bottle_labels_001 = "Před odesláním se ujistěte, že jste si přečetli pravidla pro přijímání soutěžních příspěvků, kde jsou uvedeny konkrétní pokyny pro připojování štítků!";
$bottle_labels_002 = "Obvykle se používá průhledná balicí páska, která se připevní na hlaveň každé položky - zcela zakryje štítek.";
$bottle_labels_003 = "Ke každému záznamu se obvykle připevňují štítky pomocí gumičky.";
if (isset($_SESSION['jPrefsBottleNum'])) $bottle_labels_004 = "Upozornění: Ke každému vzorku systém generuje 4 štítky. Tato soutěž vyžaduje ".$_SESSION['jPrefsBottleNum']." lahví od každého vzorku. Přebytečné štítky můžete vyhodit.";
else $bottle_labels_004 = "Upozornění: 4 štítky poskytujeme volně k dispozici. Přebytečné štítky můžete vyhodit.";
$bottle_labels_005 = "Pokud nějaký vzorek chybí, zavřete toto okno, upravte jej a pak se vraťte.";
$bottle_labels_006 = "Poznámky personálu soutěže:";
$bottle_labels_007 = "TENTO FORMULÁŘ RECEPTU JE POUZE PRO VAŠE POZNÁMKY - prosím, NEPŘIKLÁDEJTE JEJ k vašim vzorkům.";

$brew_text_040 = "Lepek není třeba uvádět jako alergen; předpokládá se jeho přítomnost ve všech pivních stylech. Bezlepková piva by měla být přihlašována do kategorie Gluten-Free Beer (BA) nebo Alternative Grain Beer (BJCP). Lepek jako alergen uvádějte v medovině nebo cideru, pokud použitá cukernatá surovina obsahuje lepek (např. ječný, pšeničný nebo žitný slad) nebo pokud bylo použito pivovarských kvasnic.";

$brewer_text_041 = "Dostali jste již možnost Pro-Am, abyste mohli soutěži na nadcházející soutěži Great American Beer Festival Pro-Am?";
$brewer_text_042 = "Jestliže jste již obdrželi Pro-Am, uveďte to zde. Pomůžete personálu soutěže a představitelům pivovaru Pro-Am  (pokud je v této soutěži použito) vybrat vzorky Pro-Am od sládků, kteří ještě Pro-Am nemají.";

/**
 * ------------------------------------------------------------------------
 * Version 2.1.15 Additions
 * ------------------------------------------------------------------------
 */

$label_submitting = "Odesílám";
$label_additional_info = "Vzorky s doplňujícími informacemi";
$label_working = "Pracuji";

$output_text_030 = "Prosím čekejte";

$brewer_entries_text_021 = "Zaškrtněte vzorky k tisku štítků. Zvolte zaškrtávací pole nahoře pro zaškrtnutí všech polí ve sloupci.";
$brewer_entries_text_022 = "Vytisknout štítky lahví všech zaškrtnutých vzorků";
$brewer_entries_text_023 = "Štítky lahví se otevřou na nové záložce nebo v novém okně.";
$brewer_entries_text_024 = "Vytisknout štítky lahví";

/**
 * ------------------------------------------------------------------------
 * Version 2.1.18 Additions
 * ------------------------------------------------------------------------
 */

$output_text_031 = "Stisknutím klávesy Esc skryjete.";
$styles_entry_text_21X = "Účastník musí zadat sílu (session: 3,0-5,0%, standard: 5,0-7,5%, double: 7,5-9,5%).";
$styles_entry_text_PRX4 = "Účastník musí uvést druh(y) použitého ovoce.";

/**
 * ------------------------------------------------------------------------
 * Version 2.1.19 Additions
 * ------------------------------------------------------------------------
 */

$output_text_032 = "Počet vzorků ukazuje pouze ty účastníky, kteří si zvolili ve svém profilu místo příjmu vzorků. Skutečný počet může být vyšší nebo nižší.";
$brewer_text_043 = "Nebo jste (či byli jste) zaměstnáni v pivovaru? Tím je myšlena nejen pozice sládka, ale i laboranta, sklepáka, obsluhy lahvárny a jiné. Současní nebo minulí zaměstnanci pivovaru nemají nárok k účasti na soutěži Great American Beer Festival Pro-Am.";
$label_entrant_reg = "Registrace účastníků";
$sidebar_text_026 = "je zaregistrováno k datu";
$label_paid_entries = "Již uhrazených vzorků";

/**
 * ------------------------------------------------------------------------
 * Version 2.2.0 Additions
 * ------------------------------------------------------------------------
 */

$alert_text_086 = "Prohlížeč Internet Explorer není v BCOE&M podporován - zobrazení a funkce nebudou fungovat správně. Prosím, pořiďte si novější prohlížeč.";
$alert_text_087 = "Pro optimální zážitek a správné fungování všech funkcí a funkcí povolte prosím JavaScript, abyste mohli nadále používat tento web. Jinak se může web chovat nepředvídatelně.";
$alert_text_088 = "Prezentace vítězů bude veřejně k dispozici po zveřejnění výsledků.";
$alert_text_089 = "Archivovaná data nejsou k dispozici.";

$brewer_entries_text_025 = "Zobrazit nebo vytisknout degustační listy degustátorů";

$brewer_info_013 = "Byli jste přiřazeni jako degustátor.";
$brewer_info_014 = "K přehledu degustace, kde uvidíte vzorky k posouzení, se dostanete tlačítkem níže.";

$contact_text_004 = "Organizátoři soutěže neuvedli žádné kontakty.";

// Awards
$label_thank_you = "Děkujeme";
$label_congrats_winners = "Gratulujeme všem medailistům";
$label_placing_entries = "Vkládání záznamů";
$label_by_the_numbers = "Statistika";
$label_launch_pres = "Otevřít prezentaci vítězů";
$label_entrants = "Účastníků";

$label_judging_dashboard = "Přehled degustace";
$label_table_assignments = "Přiřazení stolů";
$label_table = "Stůl";
$label_edit = "Upravit";
$label_add = "Přidat";
$label_disabled = "Vypnuto";
$label_judging_scoresheet = "Degustační list";
$label_checklist_version = "Zaškrtávací varianta";
$label_classic_version = "Klasická varianta";
$label_structured_version = "Strukturovaná varianta";
$label_submit_evaluation = "Odeslat hodnocení";
$label_edit_evaluation = "Upravit hodnocení";
$label_your_score = "Vaše hodnocení";
$label_your_assigned_score = "Vložení konsenzuální hodnocení";
$label_assigned_score = "Konsenzuální hodnocení";
$label_accepted_score = "Oficiálně uznané hodnocení";
$label_recorded_scores = "Zadaná konsenzuální hodnocení";
$label_go = "Přejít";
$label_go_back = "Zpět";
$label_na = "N/A";
$label_evals_submitted = "Hodnocení odeslána";
$label_evaluations = "Hodnocení";
$label_submitted_by = "Zadal(a)";
$label_attention = "Pozor!";
$label_unassigned_eval = "Nepřiřazená posouzení";
$label_head_judge = "Hlavní degustátor";
$label_lead_judge = "Vedoucí degustátor";
$label_mini_bos_judge = "Mini-BOS degustátor";
$label_view_my_eval = "Zobrazit moje hodnocení";
$label_view_other_judge_eval = "Zobrazit hodnocení ostatních degustátorů";
$label_place_awarded = "Umístění vzorku";
$label_honorable_mention = "Čestné uznání";
$label_places_awarded_table = "Oceněné vzorky tohoto stolu";
$label_places_awarded_duplicate = "Duplicitní místa oceněná u tohoto stolu";

$evaluation_info_000 = "Seznam vzorků ze stolů a sad, který vám byl přiřazen, je zobrazen níže.";
$evaluation_info_001 = "Tato soutěž používá degustaci ve frontě. Pokud u vašeho stolu sedí více než jedna dvojice degutátorů, posuďte následující vzorek ve frontě.";
$evaluation_info_002 = "Abychom zajistili přesné hodnocení vzorků a hladký průběh soutěže, vy i vaši kolegové smí posoudit pouze vzorky z vašeho stolu, které ještě nikdo jiný neochutnal. Dotažte se organizátora soutěže nebo koordinátora degustační komise, pokud máte nějaké otázky.";
$evaluation_info_003 = "Očekáváme finální přijetí od správce webu.";
$evaluation_info_004 = "Vaše konsenzuální hodnocení bylo přijato.";
$evaluation_info_005 = "Tento vzorek <strong>není</strong> součástí vám přiřazené sady.";
$evaluation_info_006 = "Upravte, je-li třeba.";
$evaluation_info_007 = "U stolu si můžete vybrat z těchto vzorků.";
$evaluation_info_008 = "Zvolte &quot;Přidat&quot; u příslušného vzorku pro zaznamenání hodnocení. K dispozici jsou pouze stoly aktuálního nebo minulých kol degustace.";
$evaluation_info_009 = "Byli jste vybráni jako degustátor, nicméně nebyly vám přiřazeny žádné stoly nebo sady vzorků. Podrobnosti zjistíte u organizátora soutěže nebo koordinátora degustační komise.";
$evaluation_info_010 = "Tento vzorek je součástí vám přiřazené sady.";
$evaluation_info_011 = "Přidat hodnocení vzorku, který vám nebyl přiřazen.";
$evaluation_info_012 = "Použijte pouze, když jste požádáni o ochutnání vzorku, který není součásti vám přiřazeného stolu.";
$evaluation_info_013 = "Vzorek nebyl nalezen.";
$evaluation_info_014 = "Prosím, zkontrolujte šestimístný kód vzorku a zkuste to znovu.";
$evaluation_info_015 = "Ujistěte se, že má číslo 6 číslic.";
$evaluation_info_016 = "Hodnocení nebylo zaznamenáno.";
$evaluation_info_017 = "Konzenzuální hodnocení zadaná degustátory se neshodují.";
$evaluation_info_018 = "U následujících vzorků je nutná kontrola:";
$evaluation_info_019 = "Následující vzorky mají zadáno pouze jedno hodnocení:";
$evaluation_info_020 = "Váš přehled degustace bude k dispozici od"; // Punctuation omitted intentionally
$evaluation_info_021 = ", kdy budete moci ohodnotit vzorky, které jsme vám přiřadili"; // Punctuation omitted intentionally
$evaluation_info_022 = "Degustace a vkládání hodnocení jsou uzavřeny.";
$evaluation_info_023 = "Pokud máte nějaké dotazy, obraťte se se na organzátra soutěže nebo koordinátora degustační komise.";
$evaluation_info_024 = "Byli jste přiřazeni k následujícím stolu. Seznam každého stolu zobrazuje pouze minulá a aktuální kola degustace.";
$evaluation_info_025 = "Degustátoři přidělení k tomuto stolu:";
$evaluation_info_026 = "Všechna shodná skóre zadaná degustátory se shodují.";
$evaluation_info_027 = "Položky, které jste vyplnili nebo které administrátor zadal vaším jménem a které vám nebyly v systému přiřazeny.";
$evaluation_info_028 = "Degustace skončila.";
$evaluation_info_029 = "U následujících stolů byla udělena duplicitní místa:";
$evaluation_info_030 = "Správci budou muset zadat všechny zbývající položky umístění.";
$evaluation_info_031 = "hodnocení přidaných degustátory";
$evaluation_info_032 = "Některým z degustátorů bylo zadáno vícenásobné hodnocení.";
$evaluation_info_033 = "I když se jedná o neobvyklou událost, duplicitní hodnocení mohl být zadáno kvůli problémům s připojením a podobně. Jediné zaznamenané hodnocení pro každého degustátora by mělo být oficiálně přijato - všechna ostatní by měla být odstraněna, aby nedošlo k zmatení účastníků.";
$evaluation_info_034 = "Při hodnocení stylů speciálního typu použijte tento řádek k vyjádření charakteristik, které jsou pro něj jedinečné, jako je ovoce, koření, použitý zdroj cukrů, kyselost, atd.";
$evaluation_info_035 = "Poskytněte komentáře ke stylu, receptu, postupu a celkového dojmu. Zahrňte doporučení pro sládka.";
if (isset($_SESSION['jPrefsScoreDispMax'])) $evaluation_info_036 = "Jedno nebo více skóre rozhodčích je mimo přijatelné rozmezí skóre.  Rozsah přijatelnosti je ".$_SESSION['jPrefsScoreDispMax']. " bodů nebo méně.";
$evaluation_info_037 = "Všechna sezení tohoto stolu se zdají být dokončená.";
$evaluation_info_038 = "Jako hlavní degustátor máte odpovědnost ověřit, zda se shodují konsenzuální skóre každého vzorku, ujistit se, že všechny výsledky degustátorů pro každý vzorek jsou v příslušném rozsahu a udělit medaile určeným vzorkům.";
$evaluation_info_039 = "Vzorky tohoto stolu:";
$evaluation_info_040 = "Ohodnocené vzorky tohoto stolu:";
$evaluation_info_041 = "Ohodnocené vzorky ve vaší sadě:";
$evaluation_info_042 = "Vámi ohodnocené vzorky:";
$evaluation_info_043 = "Degustátorů s ohodnocenými vzorky tohoto stolu:";

// Scoresheet Labels and Associated Descriptions
$label_submitted = "Odesláno";
$label_ordinal_position = "Pořadí v sadě";
$label_alcoholic = "Lihovité";
$descr_alcoholic = "Vůně, chuť a hřejivost ethanolu a vyšších alkoholů.";
$descr_alcoholic_mead = "Účinky ethanolu. Hřejivé.";
$label_metallic = "Kovové"; 
$descr_metallic = "Plechovka, mince, měď, železo nebo chuť krve.";
$label_oxidized = "Oxidační";
$descr_oxidized = "Jakákoli kombinace zatuchlé, vinné, lepenkové, papírové nebo chuti či vůně podobné sherry.";
$descr_oxidized_cider = "Zatuchlost, aroma nebo chuť sherry, rozinek nebo otlučeného ovoce.";
$label_phenolic = "Fenolické";
$descr_phenolic = "Kořeněná (hřebíček, pepř), kouřová, plastová, lepicí páska nebo medicinální (chlorfenol).";
$label_vegetal = "Zeleninové";
$descr_vegetal = "Vařená, konzervovaná nebo shnilá zeleninová vůně a chuť (zelí, cibule, celer, chřest atd.).";
$label_astringent = "Svíravé";
$descr_astringent = "Přetrvávající drsnost nebo suchost v dochuti, ostrá obilnost, pluchy.";
$descr_astringent_cider = "Pocit vysušení v ústech podobný žvýkání čajového sáčku. Musí být vyvážená, pokud je přítomna";
$label_acetaldehyde = "Acetaldehyd";
$descr_acetaldehyde = "Vůně a chuť připomínající zelené jablko.";
$label_diacetyl = "Diacetyl";
$descr_diacetyl = "Vůně a chuť margarínu, másla nebo karamelek. Někdy bývá vnímám i jako olejovitý povlak jazyka.";
$descr_diacetyl_cider = "Aroma nebo chuť másla nebo karamelek.";
$label_dms = "DMS (Dimethylsulfid)";
$descr_dms = "V nízkých úrovních sladká, vařená nebo konzervovaná kukuřičná vůně a chuť.";
$label_estery = "Esterové";
$descr_estery = "Vůně jakéhokoli esteru (ovoce, ovocné příchutě nebo růží).";
$label_grassy = "Travnaté";
$descr_grassy = "Vůně čerstvě posečené trávy nebo zelených listů.";
$label_light_struck = "Letinka";
$descr_light_struck = "Podobné pachu skunka.";
$label_musty = "Zatuchlé";
$descr_musty = "Zatuchlá nebo plísňová aromata.";
$label_solvent = "Ředidlo";
$descr_solvent = "Vůně a chuti vyšších alkoholů. Aceton nebo ředidlo.";
$label_sour_acidic = "Kyselinové";
$descr_sour_acidic = "Kyselost v chuti nebo vůni. Může být ostrá a čistá (kyselina mléčná) nebo octová (kyselina octová).";
$label_sulfur = "Síra";
$descr_sulfur = "Pach shnilých vajec nebo hořící zápalky.";
$label_sulfury = "Sirné";
$label_yeasty = "Kvasničné";
$descr_yeasty = "Chlébová, sirná nebo kvasničná chuť či vůně.";
$label_acetified = "Lak na nehty";
$descr_acetified = "Etylacetát (rozpouštědlo, lak na nehty) nebo kyselina octová (ocet, hrubý dojem v krku).";
$label_acidic = "Kyselé";
$descr_acidic = "Suchá kyselá chuť. Obvykle pochází z jedné z těchto kyselin: jablečné, mléčné nebo citrónové. Musí být vyvážená.";
$descr_acidic_mead = "Čistá, kyselá chuť nebo vůně od nízkého pH. Obykle pochází od jedné z těchto kyselin: jablečné, mléčné, glukonové nebo citronové.";
$label_bitter = "Hořké";
$descr_bitter = "Ostrá chuť, která je nepříjemná ve vyšších úrovních.";
$label_farmyard = "Zemědělský dvůr";
$descr_farmyard = "Hnůj (kravský nebo prasečí) nebo hospodářský dvůr (stáj za teplého dne).";
$label_fruity = "Ovocité";
$descr_fruity = "Vůně a chuť čerstvého ovoce, která je v některých stylech žádoucí, v jiných nikoli.";
$descr_fruity_mead = "Chuti a esterové vůně často pocházejí z ovoce v melomelu. Častými vadami jsou banán a ananas.";
$label_mousy = "Myšina";
$descr_mousy = "Chuť připomínající vůni hlodavčí nory nebo kotce.";
$label_oaky = "Dřevité";
$descr_oaky = "Chuť nebo aroma díky delší době v sudu nebo na dřevěných čipsech. Chrakter &quot;barrel-aged&quot;.";
$label_oily_ropy = "Olejovité";
$descr_oily_ropy = "Odlesky ve vzhledu piva, nepříjemný viskózní dojem, který se mění na olej či ropu.";
$label_spicy_smoky = "Kořeněné či kouřové";
$descr_spicy_smoky = "Koření, hřebíček, kouř, šunka.";
$label_sulfide = "Sirovodík";
$descr_sulfide = "Dojem shnilých vajec pocházející z problémů při kvašení.";
$label_sulfite = "Siřičitan";
$descr_sulfite = "Hořící zápalky, z nadměrného nebo nedávného zasíření.";
$label_sweet = "Sladká";
$descr_sweet = "Základní sladká chuť. Musí být v rovnováze, pokud je přítomna";
$label_thin = "Prázdné";
$descr_thin = "Vodovitý dojem. Prázdné tělo.";
$label_acetic = "Octové";
$descr_acetic = "Ocet, kyselina octová, ostrost.";
$label_chemical = "Chemické";
$descr_chemical = "Vitamínová, chemická nebo chuť doplňku stravy.";
$label_cloying = "Knedlíkaté";
$descr_cloying = "Sirupovité, příliš sladké, nevyvážené kyselostí nebo trpkostí.";
$label_floral = "Květinové";
$descr_floral = "Vůně květů nebo parfému.";
$label_moldy = "Plesnivé";
$descr_moldy = "Zatuchlá, plesnivá aromata nebo aroma či chuť korku.";
$label_tannic = "Svíravé";
$descr_tannic = "Suché, svíravé. Taniny. Dojem je podobný hořkosti. Chuť silného neslazeného čaje nebo slupky grepu.";
$label_waxy = "Voskovité";
$descr_waxy = "Voskovité, lojovité, mastné.";
$label_medicinal = "Medicinální";
$label_spicy = "Pikantní";
$label_vinegary = "Octové";
$label_plastic = "Plastové";
$label_smoky = "Kouřové";

// Scoresheet Descriptors
$label_inappropriate = "Neodpovídá";
$label_possible_points = "Možné body";
$label_malt = "Slad";
$label_ferm_char = "Charakter kvašení";
$label_body = "Tělo";
$label_creaminess = "Krémovost";
$label_astringency = "Svíravost";
$label_warmth = "Hřejivost";
$label_appearance = "Vzhled";
$label_flavor = "Chuť";
$label_mouthfeel = "Pocit v ústech";
$label_overall_impression = "Celkový dojem";
$label_balance = "Vyvážení";
$label_finish_aftertaste = "Dochuť";
$label_hoppy = "Chmelové";
$label_malty = "Sladové";
$label_comments = "Komentář";
$label_flaws = "Stylové vady";
$label_flaw = "Vada";
$label_flawless = "Bezvadné";
$label_significant_flaws = "Závažné nedostatky";
$label_classic_example = "Příklad stylu";
$label_not_style = "Mimo styl";
$label_tech_merit = "Technické hledisko";
$label_style_accuracy = "Stylová přesnost";
$label_intangibles = "Subjektivní hodnocení";
$label_wonderful = "Báječné";
$label_lifeless = "Odporné";
$label_feedback = "Zpětná vazba";
$label_honey = "Med";
$label_alcohol = "Alkohol";
$label_complexity = "Komplexnost";
$label_viscous = "Viskózní";
$label_legs = "Kroužkuje"; // Used to describe liquid clinging to glass
$label_clarity = "Čirost";
$label_brilliant = "Čiré";
$label_hazy = "Opalizující";
$label_opaque = "Zakalené";
$label_fruit = "Ovoce";
$label_acidity = "Kyselost";
$label_tannin = "Trpkost";

// Appearance and Color of Entries
$label_white = "Bílá";
$label_straw = "Slámová";
$label_yellow = "Žlutá";
$label_gold = "Zlatá";
$label_copper = "Měděná";
$label_quick = "Krátká";
$label_long_lasting = "Dlouhá";
$label_ivory = "Slonová";
$label_beige = "Béžová";
$label_tan = "Okrová";
$label_lacing = "Kroužkuje";
$label_particulate = "Částice";
$label_black = "Černá";
$label_large = "Velký";
$label_small = "Malý";
$label_size = "Velikost";
$label_retention = "Trvanlivost";
$label_head = "Pěna";
$label_head_size = "Velikost pěny";
$label_head_retention = "Trvanlivost pěny";
$label_head_color = "Barva pěny";
$label_brettanomyces = "Brettanomyces";
$label_cardboard = "Lepenka";
$label_cloudy = "Zakalené";
$label_sherry = "Sherry";
$label_harsh = "Drsné";
$label_harshness = "Drsnost";
$label_full = "Úplný";
$label_suggested = "Doporučený";
$label_lactic = "Mléčná";
$label_smoke = "Kouř";
$label_spice = "Koření";
$label_vinous = "Vinné";
$label_wood = "Dřevo";
$label_cream = "Krémová";
$label_flat = "Nízká";

// Scoresheet Score Descriptions
$label_descriptor_defs = "Definice deskriptoru";
$label_outstanding = "Vynikající";
$descr_outstanding = "Světový příklad stylu.";
$label_excellent = "Vynikající";
$descr_excellent = "Dobře ilustruje styl, vyžaduje jemné doladění.";
$label_very_good = "Velmi dobré";
$descr_very_good = "Obecně v rámci parametrů stylu, drobné vady.";
$label_good = "Dobré";
$descr_good = "Není ve stylu a/nebo je postiženo drobnými vadami.";
$label_fair = "Dostatečné";
$descr_fair = "Obsahuje vady v chuti nebo vůni či má zásadní stylové nedostatky. Nepříjemné.";
$label_problematic = "Nedostatečné";
$descr_problematic = "Dominují vady v chuti a vůni. Pít se dá jen s obtížemi.";

/**
 * ------------------------------------------------------------------------
 * Version 2.3.0 Additions
 * ------------------------------------------------------------------------
 */

$winners_text_006 = "Poznámka: výsledky z této tabulky mohou být neúplné z důvodu nedostatečných informací o zadání nebo stylu.";

$label_elapsed_time = "Uplynulý čas";
$label_judge_score = "Skóre degustátora";
$label_judge_consensus_scores = "Konsensuální skóre degustátora";
$label_your_consensus_score = "Vaše konsensuální skóre";
$label_score_range_status = "Stav rozsahu skóre";
$label_consensus_caution = "Konsensuální opatrnost";
$label_consensus_match = "Konsensuální shoda";
$label_score_range_caution = "Konzenzuální opatrnost degustátorů";
$label_score_range_ok = "Rozsah skóre rozhodčích OK";
$label_auto_log_out = "Automatické odhlášení v";
$label_place_previously_selected = "Místo dříve vybrané";
$label_entry_without_eval = "Vzorek bez hodnocení";
$label_entries_with_eval = "Vzorky s hodnocením";
$label_entries_without_eval = "Vzorky bez hodnocení";
$label_judging_close = "Konec degustace";
$label_session_expire = "Platnost relace brzy vyprší";
$label_refresh = "Obnovte tuto stránku";
$label_stay_here = "Zůstat zde";
$label_bottle_inspection = "Kontrola lahví";
$label_bottle_inspection_comments = "Komentáře ke kontrole lahví";
$label_consensus_no_match = "Konsensuální skóre se neshodují";
$label_score_below_courtesy = "Zadané skóre je pod prahovou hodnotou skóre zdvořilosti";
$label_score_greater_50 = "Zadané skóre je větší než 50";
$label_score_out_range = "Skóre je mimo rozsah";
$label_score_range = "Rozsah skóre";
$label_ok = "Dobře";
$label_esters = "Estery";
$label_phenols = "Fenoly";
$label_descriptors = "Popis";
$label_grainy = "Obilné";
$label_caramel = "Karamel";
$label_bready = "Chléb";
$label_rich = "Bohaté";
$label_dark_fruit = "Tmavé ovoce";
$label_toasty = "Topinkové";
$label_roasty = "Pražené";
$label_burnt = "Spálené";
$label_citrusy = "Citrusové";
$label_earthy = "Zemité";
$label_herbal = "Bylinné";
$label_piney = "Borovice";
$label_woody = "Dřevnaté";
$label_apple_pear = "Jablko/hruška";
$label_banana = "Banán";
$label_berry = "Bobule";
$label_citrus = "Citrus";
$label_dried_fruit = "Sušené ovoce";
$label_grape = "Hrozny";
$label_stone_fruit = "Peckoviny";
$label_even = "Even";
$label_gushed = "Přepěnilo";
$label_hot = "Hřejivé";
$label_slick = "Olejovité";
$label_finish = "Dokončit";
$label_biting = "Ostré";
$label_drinkability = "Pitelnost";
$label_bouquet = "Aroma";
$label_of = "z";
$label_fault = "Vada";
$label_weeks = "Týdnů";
$label_days = "Dnů";
$label_scoresheet = "Degustační list";
$label_beer_scoresheet = "Degustační list piva";
$label_cider_scoresheet = "Degustační list cideru";
$label_mead_scoresheet = "Degustační list medoviny";
$label_consensus_status = "Stav konsensu";

$evaluation_info_044 = "Vaše konsensuální skóre neodpovídá skóre zadaným jinými degustátory.";
$evaluation_info_045 = "Zadané konsensuální skóre se shoduje s předchozími degustátory.";
$evaluation_info_046 = "Rozdíl ve skóre je větší než";
$evaluation_info_047 = "Rozdíl ve skóre je v přijatelném rozsahu.";
$evaluation_info_048 = "Místo, které jste zadali, již je na tomto stole obsazeno. Vyberte prosím jiné místo nebo žádné.";
$evaluation_info_049 = "Tyto vzorky nemají v databázi žádné hodnocení";
$evaluation_info_050 = "Uveďte, prosím, pořadové číslo vzorku v sadě.";
$evaluation_info_051 = "Uveďte, prosím, celkový počet vzorků v sadě.";
$evaluation_info_052 = "Vhodná velikost, uzávěr, úroveň naplnění, odstranění etikety atd.";
$evaluation_info_053 = "Konsensuální skóre je konečné skóre, na kterém se shodli degustátoři hodnotící daný vzorek. Pokud není konsensuální skóre v tuto chvíli známé, zadejte své vlastní skóre. Pokud se zde uvedené konsensuální skóre liší od skóre zadaného ostatními degustátory, budete upozorněni.";
$evaluation_info_054 = "Tento vzorek postoupil do finálového kola.";
$evaluation_info_055 = "Konsensuální skóre, které jste zadali, neodpovídá skóre zadanému předchozími degustátory pro tento záznam. Poraďte se prosím s dalšími degustátory hodnotícími tento vzorek a podle potřeby upravte své konsensuální skóre.";
$evaluation_info_056 = "Skóre, které jste zadali, je nižší než 13, <a href=\"https://www.bjcp.org/cep/GreatBeerJudging.pdf\" target=\"_blank\">což je běžně známý zdvořilostní práh pro degustátory BJCP</a>. Poraďte se s ostatními degustátory a podle potřeby upravte své skóre.";
$evaluation_info_057 = "Skóre nesmí být nižší než 5 a vyšší než 50.";
$evaluation_info_058 = "Skóre, které jste zadali, je vyšší než 50, maximální skóre pro jakýkoli vzorek. Zkontrolujte a zrevidujte své konsensuální skóre.";
$evaluation_info_059 = "Skóre, které jste zadali pro tento vzorek, je mimo rozsah rozdílu skóre mezi degustátory.";
$evaluation_info_060 = "znaků (maximálně)";
$evaluation_info_061 = "Uveďte prosím krátký komentář.";
$evaluation_info_062 = "Zvolte, prosím deskriptor.";
$evaluation_info_063 = "Dopil bych toto pivo.";
$evaluation_info_064 = "Vypil bych půllitr tohoto piva.";
$evaluation_info_065 = "Za toto pivo bych zaplatil.";
$evaluation_info_066 = "Doporučil bych toto pivo.";
$evaluation_info_067 = "Uveďte prosím hodnocení.";
$evaluation_info_068 = "Uveďte konsensuální skóre - minimálně 5, maximálně 50.";
$evaluation_info_069 = "Nejméně dva degustátoři ze sady, do které byl váš vzorek zadán, dosáhli konsensu o vašem konečném skóre. Není to nutně průměr jednotlivých skóre.";
$evaluation_info_070 = "Na základě degustačního listu BJCP";
$evaluation_info_071 = "Od vašeho hodnocení uplynulo 15 minut. I když máte dostatek času, než vás systém automaticky odhlásí, toto je zdvořilostní varování, abyste se ujistili, že dokončíte své hodnocení včas a abyste udrželi tempo posuzování v přijatelné míře.";
$evaluation_info_072 = "Ve výchozím nastavení je automatické odhlášení prodlouženo na 30 minut pro hodnocení vzorků.";

$alert_text_090 = "Vaše relace vyprší za dvě minuty. Můžete zůstat na aktuální stránce, abyste mohli dokončit práci, než vyprší čas, obnovit tuto stránku a pokračovat v aktuální relaci (data formuláře se mohou ztratit) nebo se odhlásit.";
$alert_text_091 = "Vaše relace vyprší za 30 sekund. Chcete-li pokračovat v aktuální relaci, obnovte stránku nebo se odhlaste.";
$alert_text_092 = "Pro přidání stolu musí být definována alespoň jedna degustační sada.";

$brewer_entries_text_026 = "Degustační listy tohoto vzorku jsou v různých formátech. Každý formát obsahuje jedno nebo několik platných hodnocení tohoto vzorku.";

// Update QR text
$qr_text_008 = "Chcete-li přijímat vzorky pomocí QR kódu, zadejte správné heslo. Heslo budete muset zadat pouze jednou za relaci - nezapomeňte mít prohlížeč nebo aplikaci pro skenování QR kódů otevřenou.";
$qr_text_015 = "Naskenujte další QR kód. U novějších operačních systémů přejděte do aplikace fotoaparátu svého mobilního zařízení. U starších operačních systémů aplikaci spusťte nebo se do ni vraťte, je-li již spuštěna.";
$qr_text_017 = "Skenování QR kódů je k dispozici nativně na většině moderních mobilních operačních systémů. Jednoduše namiřte fotoaparát na QR kód na štítku lahve a postupujte podle pokynů. U starších mobilních operačních systémů je k využití této funkce nutná aplikace pro skenování QR kódů.";
$qr_text_018 = "Naskenujte QR kód umístěný na štítku lahve, zadejte požadované heslo a přijměte vzorek.";

/**
 * ------------------------------------------------------------------------
 * Version 2.3.2 Additions
 * ------------------------------------------------------------------------
 */

$label_select_state = "Vyberte nebo vyhledejte svůj stát";
$label_select_below = "Vyberte níže";
$output_text_033 = "Při odesílání vaší zprávy BJCP je možné, že ne všechen personál na seznamu obdrží body. Doporčujeme, abyste nejprve přidělili body těm s BJCP ID.";
$styles_entry_text_PRX3 = "Účastník musí uvést odrůdu použitých hroznů nebo hroznového moštu.";
$styles_entry_text_C1A = "Účastníci MUSÍ uvést úroveň nasycení (3 úrovně). Účastníci MUSÍ uvést sladkost (5 úrovní). Pokud je OG výrazně vyšší než typické rozmezí, účastník by to měl vysvětlit, např. konkrétní odrůda jablek dává šťávu s vysokou cukernatostí.";
$styles_entry_text_C1B = "Účastníci MUSÍ uvést úroveň nasycení (3 úrovně). Účastníci MUSÍ uvést sladkost (suchý až středně sladký, 4 úrovně). Účastníci MOHOU uvést odrůdu jablek u jednodruhových moštů; pokud je uvedena, očekává se odrůdový charakter.";
$styles_entry_text_C1C = "Účastníci MUSÍ uvést úroveň sycení (3 úrovně). Účastníci MUSÍ uvést sladkost (pouze středně sladký až sladký, 3 úrovně). Účastníci MOHOU uvést odrůdu jablek pro jednoodrůdový cider; pokud je uvedena, očekává se odrůdový charakter.";
$winners_text_007 = "U tohoto stolu nejsou žádné vítězné vzorky.";

/**
 * ------------------------------------------------------------------------
 * Version 2.4.0 Additions
 * Via DeepL Translator English to Czech - and for that, I'm sorry. 
 * Again. :)
 * ------------------------------------------------------------------------
 */

$label_entries_to_judge = "Přihlášky k posouzení";
$evaluation_info_073 = "Pokud jste v tomto výsledkovém listu změnili nebo přidali nějakou položku nebo komentář, mohou být vaše údaje ztraceny, pokud přejdete z této stránky pryč.";
$evaluation_info_074 = "Pokud jste provedli změny, zavřete toto dialogové okno, přejděte na konec výsledkové listiny a vyberte možnost Odeslat hodnocení.";
$evaluation_info_075 = "Pokud jste neprovedli žádné změny, vyberte níže uvedené modré tlačítko Judging Dashboard.";
$evaluation_info_076 = "Komentář ke sladu, chmelu, esterům a dalším aromatickým látkám.";
$evaluation_info_077 = "Komentář k barvě, průzračnosti a pěně (retenci, barvě a struktuře).";
$evaluation_info_078 = "Komentář ke sladu, chmelu, kvasným vlastnostem, vyváženosti, dochuti a dalším chuťovým vlastnostem.";
$evaluation_info_079 = "Komentujte tělo, sycení, teplotu, krémovitost, trpkost a další chuťové vjemy.";
$evaluation_info_080 = "Okomentujte celkový požitek z pití spojený se vstupem, uveďte návrhy na zlepšení.";


if ($_SESSION['prefsStyleSet'] == "BJCP2021") {
    $styles_entry_text_21B = "Účastník MUSÍ zadat sílu (relační, standardní, dvojitá); pokud sílu nezadá, předpokládá se, že je standardní. Účastník MUSÍ uvést konkrétní typ Specialty IPA ze seznamu aktuálně definovaných typů uvedených v pokynech pro styly nebo ve znění prozatímních stylů na internetových stránkách BJCP; NEBO MUSÍ popsat typ Specialty IPA a jeho klíčové vlastnosti formou komentáře, aby porotci věděli, co mají očekávat. Účastníci MŮŽOU uvést konkrétní použité odrůdy chmele, pokud se domnívají, že porotci nemusí rozpoznat odrůdové vlastnosti novějších chmelů. Účastníci MOHOU uvést kombinaci definovaných typů IPA (např. Black Rye IPA) bez uvedení dalších popisů.";
    $styles_entry_text_24C = "Účastník MUSÍ uvést světlé, jantarové nebo hnědé Bière de Garde.";
    $styles_entry_text_25B = "Účastník MUSÍ uvést sílu (stolní, standardní, super) a barvu (světlá, tmavá). Účastník MŮŽE uvést použitá znaková zrna.";
    $styles_entry_text_27A = "Souhrnná kategorie pro ostatní historická piva, která NEBYLY definována BJCP. Účastník MUSÍ poskytnout porotcům popis historického stylu, který NENÍ jedním z aktuálně definovaných příkladů historických stylů poskytovaných BJCP. V současné době definované příklady: Kellerbier, Kentucky Common, Lichtenhainer, London Brown Ale, Piwo Grodziskie, Pre-Prohibition Lager, Pre-Prohibition Porter, Roggenbier, Sahti. Pokud je pivo přihlášeno pouze s názvem stylu a bez popisu, je velmi nepravděpodobné, že by porotci pochopili, jak jej hodnotit.";
    $styles_entry_text_28A = "O participante DEVE especificar ou um Estilo Base, ou fornecer uma descrição dos ingredientes, especificações, ou caráter desejado. O participante PODE especificar as linhagens de Brett utilizadas.";
    $styles_entry_text_28B = "Účastník MUSÍ uvést popis piva, identifikaci použitých kvasinek nebo bakterií a buď základní styl, nebo složky, specifikace nebo cílový charakter piva.";
    $styles_entry_text_28C = "Účastník MUSÍ uvést všechny použité složky speciálního typu (např. ovoce, koření, byliny nebo dřevo). Účastník MUSÍ uvést buď popis piva, identifikaci použitých kvasinek nebo bakterií a buď základní styl, nebo složky, specifikace nebo cílový charakter piva. Obecný popis zvláštního charakteru piva může zahrnovat všechny požadované položky.";
    $styles_entry_text_29A = "Účastník MUSÍ uvést druh (druhy) použitého ovoce. Účastník MUSÍ uvést popis piva s uvedením buď základního stylu, nebo složek, specifikací nebo cílového charakteru piva. Obecný popis zvláštního charakteru piva může zahrnovat všechny požadované položky.  Ovocná piva založená na klasickém stylu by měla být přihlášena v tomto stylu, s výjimkou Lambicu.";
    $styles_entry_text_29B = "Účastník musí uvést druh ovoce a typ použitého SHV; jednotlivé složky SHV není třeba uvádět, pokud je použita známá směs koření (např. koření na jablečný koláč). Účastník musí uvést popis piva, a to buď základní styl, nebo přísady, specifikace nebo cílový charakter piva. Obecný popis zvláštního charakteru piva může zahrnovat všechny požadované položky.";
    $styles_entry_text_29C = "Účastník MUSÍ uvést druh použitého ovoce. Účastník MUSÍ uvést druh další přísady (podle úvodu) nebo použitý zvláštní postup. Účastník MUSÍ uvést popis piva s uvedením buď základního stylu, nebo přísad, specifikací nebo cílového charakteru piva. Obecný popis zvláštního charakteru piva může zahrnovat všechny požadované položky.";
    $styles_entry_text_29D = "Účastník MUSÍ uvést druh použitých hroznů. Účastník MŮŽE uvést další informace o základním stylu nebo charakteristických složkách.";
    $styles_entry_text_30A = "Účastník MUSÍ uvést druh použitého koření, bylinek nebo zeleniny, ale jednotlivé ingredience nemusí být uvedeny, pokud je použita známá směs koření (např. koření na jablečný koláč, kari, chilli). Účastník MUSÍ uvést popis piva s uvedením buď základního stylu, nebo přísad, specifikací nebo cílového charakteru piva. Obecný popis zvláštního charakteru piva může zahrnovat všechny požadované položky.";
    $styles_entry_text_30B = "Účastník MUSÍ uvést druh použitého koření, bylinek nebo zeleniny; jednotlivé ingredience není třeba uvádět, pokud je použita známá směs koření (např. koření na dýňový koláč). Účastník MUSÍ uvést popis piva s uvedením buď základního stylu, nebo přísad, specifikací nebo cílového charakteru piva. Obecný popis zvláštního charakteru piva může zahrnovat všechny požadované položky.";
    $styles_entry_text_30C = "Účastník MUSÍ uvést druh použitého koření, cukrů, ovoce nebo dalších fermentovatelných látek; jednotlivé složky není třeba uvádět, pokud je použita známá směs koření (např. koření na mulling). Účastník MUSÍ uvést popis piva s uvedením buď základního stylu, nebo přísad, specifikací nebo cílového charakteru piva. Obecný popis zvláštního charakteru piva může zahrnovat všechny požadované položky.";
    $styles_entry_text_30D = "Účastník MUSÍ uvést typ použitých SHV, ale jednotlivé ingredience není třeba uvádět, pokud je použita známá směs koření (např. koření na jablečný koláč, kari, chilli). Účastník MUSÍ uvést typ další přísady (podle úvodu) nebo použitý zvláštní postup. Účastník MUSÍ uvést popis piva s uvedením buď základního stylu, nebo přísad, specifikací nebo cílového charakteru piva. Obecný popis zvláštního charakteru piva může zahrnovat všechny požadované položky.";
    $styles_entry_text_31A = "Účastník musí uvést typ použitého alternativního obilí. Účastník musí uvést popis piva s uvedením buď základního stylu, nebo složek, specifikací nebo cílového charakteru piva. Obecný popis zvláštního charakteru piva může zahrnovat všechny požadované položky.";
    $styles_entry_text_31B = "Účastník MUSÍ uvést typ použitého cukru. Účastník MUSÍ uvést popis piva s uvedením buď základního stylu, nebo složek, specifikací nebo cílového charakteru piva. Obecný popis zvláštního charakteru piva může zahrnovat všechny požadované položky.";
    $styles_entry_text_32A = "Účastník MUSÍ zadat základní styl. Účastník MUSÍ uvést druh dřeva nebo kouře, pokud je patrný odrůdový charakter kouře.";
    $styles_entry_text_32B = "Účastník MUSÍ uvést druh dřeva nebo kouře, pokud je patrný odrůdový charakter kouře. Účastník MUSÍ uvést další přísady nebo postupy, díky nimž se jedná o speciální uzené pivo. Účastník MUSÍ uvést popis piva s uvedením buď základního stylu, nebo přísad, specifikací nebo cílového charakteru piva. Obecný popis speciálního charakteru piva může zahrnovat všechny požadované položky.";
    $styles_entry_text_33A = "Účastník MUSÍ uvést druh použitého dřeva a stupeň opečení nebo zuhelnatění (pokud je použito). Pokud je použito neobvyklé odrůdové dřevo, účastník MUSÍ uvést stručný popis senzorických aspektů, které dřevo pivu dodává. Účastník MUSÍ uvést popis piva s uvedením buď základního stylu, nebo složek, specifikací nebo cílového charakteru piva. Obecný popis speciálního charakteru piva může zahrnovat všechny požadované položky.";
    $styles_entry_text_33B = "Účastník MUSÍ uvést dodatečný charakter alkoholu a informace o sudu, pokud je to relevantní pro konečný chuťový profil. Pokud bylo použito neobvyklé dřevo nebo přísada, účastník MUSÍ uvést stručný popis senzorických aspektů, které tyto přísady pivu dodávají. Účastník MUSÍ uvést popis piva s uvedením buď základního stylu, nebo složek, specifikací nebo cílového charakteru piva. Obecný popis speciálního charakteru piva může zahrnovat všechny požadované položky.";
    $styles_entry_text_34A = "Účastník MUSÍ uvést název komerčního piva, specifikace (základní statistiky) piva a buď stručný senzorický popis, nebo seznam ingrediencí použitých při výrobě piva. Bez těchto informací nebudou mít porotci, kteří pivo neznají, žádný podklad pro srovnání.";
    $styles_entry_text_34B = "Účastník MUSÍ uvést použitý základní styl nebo styly a jakékoli speciální přísady, postupy nebo varianty. Účastník MŮŽE uvést další popis senzorického profilu piva nebo životních statistik výsledného piva.";
    $styles_entry_text_PRX3 = "Účastník MUSÍ uvést druh použitých hroznů. Účastník MŮŽE uvést další informace o základním stylu nebo charakteristických složkách.";
}

/**
 * ------------------------------------------------------------------------
 * Version 2.5.0 Additions
 * Via DeepL Translator English to Portuguese - and for that, I'm sorry. 
 * Again. :)
 * ------------------------------------------------------------------------
 */

$register_text_047 = "Vaše bezpečnostní otázka a/nebo odpověď se změnila.";
$register_text_048 = "Pokud jste tuto změnu neiniciovali, může být váš účet ohrožen. Kromě aktualizace bezpečnostní otázky a odpovědi byste se měli okamžitě přihlásit ke svému účtu a změnit heslo.";
$register_text_049 = "Pokud se nemůžete přihlásit ke svému účtu, měli byste neprodleně kontaktovat správce webu a aktualizovat heslo a další důležité informace o účtu.";
$register_text_050 = "Odpověď na bezpečnostní otázku je zašifrovaná a správci webu ji nemohou přečíst. Pokud se rozhodnete změnit svou bezpečnostní otázku a/nebo odpověď, musíte ji zadat.";
$register_text_051 = "Informace o účtu Aktualizováno";
$register_text_052 = "U každého níže uvedeného místa je třeba uvést odpověď Ano nebo Ne.";
$brewer_text_044 = "Chcete změnit bezpečnostní otázku a/nebo odpověď?";
$brewer_text_045 = "Nebyly zaznamenány žádné výsledky.";
$brewer_text_046 = "Při zadávání názvu klubu ve volném tvaru nejsou povoleny některé symboly, včetně ampersandu (&amp;), jednoduchých uvozovek (&#39;), dvojitých uvozovek (&quot;), a procent (&#37;).";
$brewer_text_047 = "Pokud se nemůžete zúčastnit některého z níže uvedených zasedání, ale přesto můžete pracovat jako zaměstnanec v jiné funkci, vyberte možnost Ano.";
$brewer_text_048 = "Přepravní položky";
$brewer_text_049 = "Pokud neplánujete do soutěže přihlásit žádné příspěvky, vyberte možnost \"Nepoužije se\".";
$brewer_text_050 = "Zvolte \"Přepravní položky\", pokud plánujete své položky zabalit do krabice a odeslat na zadané přepravní místo.";
$label_change_security = "Změna bezpečnostní otázky/odpovědi?";
$label_semi_dry = "Polosuché";
$label_semi_sweet = "Polosladké";
$label_shipping_location = "Místo Odeslání";
$volunteers_text_010 = "Zaměstnanci mohou oznámit svou dostupnost pro následující zasedání, která nejsou určena pro rozhodčí:";

$evaluation_info_081 = "Komentář k medovému projevu, alkoholu, esterům, komplexnosti a dalším aromatickým látkám.";
$evaluation_info_082 = "Komentář k barvě, čirosti, nohám a sycení oxidem uhličitým.";
$evaluation_info_083 = "Komentář k medu, sladkosti, kyselosti, tříslovinám, alkoholu, vyváženosti, tělu, sycení, dochuti a případným zvláštním přísadám nebo chutím specifickým pro daný styl.";
$evaluation_info_084 = "Okomentujte celkový požitek z pití spojený se vstupem, uveďte návrhy na zlepšení.";
$evaluation_info_085 = "Barva (2), čirost (2), stupeň sycení (2).";
$evaluation_info_086 = "Vyjádření dalších složek podle potřeby.";
$evaluation_info_087 = "Vyváženost kyselosti, sladkosti, obsahu alkoholu, těla, sycení oxidem uhličitým (je-li to vhodné) (14), Další složky podle potřeby (5), Dochuť (5).";
$evaluation_info_088 = "Okomentujte celkový požitek z pití spojený se vstupem, uveďte návrhy na zlepšení.";

$evaluation_info_089 = "Dosažený nebo překročený minimální počet slov.";
$evaluation_info_090 = "Děkujeme vám za poskytnutí co nejúplnějšího hodnocení. Celkem slov: ";
$evaluation_info_091 = "Minimální počet slov potřebných pro váš komentář: ";
$evaluation_info_092 = "Dosavadní počet slov: ";
$evaluation_info_093 = "Ve výše uvedeném poli Celkové hodnocení dojmu nebylo dosaženo minimálního počtu slov.";
$evaluation_info_094 = "V jednom nebo více výše uvedených polích pro zpětnou vazbu / komentář nebylo dosaženo minimálního počtu slov.";

/**
 * ------------------------------------------------------------------------
 * Version 2.5.1 Additions
 * ------------------------------------------------------------------------
 */

$label_regional_variation = "Regionální Rozdíly";
$label_characteristics = "Charakteristika";
$label_intensity = "Intenzita";
$label_quality = "Kvalita";
$label_palate = "Patro";
$label_medium = "Střední";
$label_medium_dry = "Středně Suché";
$label_medium_sweet = "Středně Sladká";
$label_your_score = "Vaše Skóre";
$label_summary_overall_impression = "Shrnutí Hodnocení a Celkový Dojem";
$label_medal_count = "Počet Skupin Medailí";
$label_best_brewer_place = "Nejlepší Pivovarské Místo";
$label_industry_affiliations = "Členství v Průmyslových Organizacích";
$label_deep_gold = "Hluboké Zlato";
$label_chestnut = "Kaštany";
$label_pink = "Růžová";
$label_red = "Červená";
$label_purple = "Fialová";
$label_garnet = "Garnet";
$label_clear = "Přehledně";
$label_final_judging_date = "Datum Závěrečného Hodnocení";
$label_entries_judged = "Posuzované Položky";

$brew_text_041 = "Nepovinné - uveďte regionální variantu (např. mexický ležák, holandský ležák, japonský rýžový ležák atd.).";

$evaluation_info_095 = "Další přidělená hodnotící schůzka otevřena:";
$evaluation_info_096 = "Pro usnadnění přípravy jsou přidělené stoly/lety a související záznamy k dispozici deset minut před začátkem zasedání.";
$evaluation_info_097 = "Vaše další hodnotící zasedání je nyní k dispozici.";
$evaluation_info_098 = "Obnovit zobrazení.";
$evaluation_info_099 = "Minulá nebo současná soudní jednání:";
$evaluation_info_100 = "Nadcházející hodnotící zasedání:";
$evaluation_info_101 = "Uveďte prosím jiný popis barvy.";
$evaluation_info_102 = "Zadejte celkový počet bodů - maximálně 50. V případě potřeby použijte níže uvedeného průvodce bodováním.";
$evaluation_info_103 = "Uveďte své hodnocení - minimálně 5 bodů, maximálně 50 bodů.";

$brewer_text_051 = "Vyberte oborové organizace, s nimiž jste spojeni jako zaměstnanci, dobrovolníci atd. To proto, abyste se ujistili, že nedochází ke střetu zájmů při přidělování porotců a stewardů k hodnocení přihlášek.";
$brewer_text_052 = "<strong>Pokud některá z oborových organizací není v rozbalovacím seznamu výše uvedena, uveďte ji zde.</strong> Oddělte název každé organizace čárkou (,) nebo středníkem (;). Některé symboly nejsou povoleny, včetně dvojitých uvozovek (&quot;) a procent (&#37;).";

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

/**
 * ----------------------------------------------------------------------------------
 * Admin Pages - Admin pages will be included in a future release
 * ----------------------------------------------------------------------------------
 */
// if ($section == "admin") include (LANG.'en_admin.lang.php');

?>