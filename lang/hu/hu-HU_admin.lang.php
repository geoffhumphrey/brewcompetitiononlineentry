<?php
/**
 * Module:      hu-HU_admin.lang.php
 * Description: This module houses all display text in the Hungarian language.
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

$archive_text_000 = "A szerver tárhelykorlátai miatt a tárolt BCOE&amp;M fiókadatok archiválása nem érhető el. A szoftver új versenyhez való használatához vagy az adatbázis adatainak törléséhez használja az alábbi gombokat.";
$archive_text_001 = "Az egyéni kategória, egyéni stílustípus, leadási helyszín, bírálati helyszín és szponzoradatok <strong class=\"text-success\">nem kerülnek törlésre</strong>. Az adminisztrátoroknak ezeket frissíteniük kell a jövőbeli versenyekhez.";
$archive_text_002 = "1. lehetőség";
$archive_text_003 = "Biztosan törölni szeretné az aktuális verseny adatait? Ez NEM vonható vissza.";
$archive_text_004 = "Összes résztvevő-, nevezési, bírálati és pontozási adat törlése";
$archive_text_005 = "Ez a lehetőség törli az összes nem adminisztrátori résztvevői fiókot, valamint az összes nevezési, bírálati és pontozási adatot, beleértve az összes feltöltött pontozólapot. Tiszta lapot biztosít.";
$archive_text_006 = "2. lehetőség";
$archive_text_007 = "Biztosan törölni szeretné az aktuális verseny adatait? Ez NEM vonható vissza.";
$archive_text_008 = "Csak a nevezési, bírálati és pontozási adatok törlése";
$archive_text_009 = "Ez a lehetőség törli az összes nevezési, bírálati és pontozási adatot, beleértve az összes feltöltött pontozólapot, de megtartja a résztvevői adatokat. Hasznos, ha nem szeretné, hogy a résztvevők új fiókprofilokat hozzanak létre.";
$archive_text_010 = "Az adatbázisban jelenleg tárolt adatok archiválásához adjon meg egy archívumnevet. Javasolt olyan nevet választani, amely egyedi ehhez az adatkészlethez. Például, ha évente tartja a versenyét, a név lehet a rendezés éve. Ha egymást követő versenyeket rendez egyetlen telepítésen, a verseny neve és az év szolgálhat névként.";
$archive_text_011 = "Csak alfanumerikus karakterek - minden más karakter kihagyásra kerül.";
$archive_text_012 = "Jelölje be azokat az információkat, amelyeket meg szeretne tartani a jövőbeli versenyekhez.";
$archive_text_013 = "Biztosan archiválni szeretné a jelenlegi adatokat?";
$archive_text_014 = "Ezután válassza ki, mely adatokat szeretné megtartani.";
$archive_text_015 = "Ez törli a következő nevű archívumot:";
$archive_text_016 = "Az összes kapcsolódó rekord is eltávolításra kerül.";

/*
 * --------------------- v 2.2.0 -----------------------
 */
$archive_text_017 = "Óvatosan szerkessze az archívum adatait. Bármely adat módosítása váratlan viselkedést eredményezhet az archivált adatok elérésekor.";
$archive_text_018 = "A fájlok az archívum nevével megegyező almappába kerülnek áthelyezésre a user_docs könyvtárban.";
$archive_text_019 = "Az archivált győzteslista/listák nyilvánosan megtekinthetők.";
$archive_text_020 = "Általában ezt csak akkor kell módosítani, ha az archívum győzteslistája helytelenül jelenik meg.";
$archive_text_021 = "PDF pontozólapok lettek mentve ehhez az archívumhoz. Ez az egyes fájlok elnevezési konvenciója, amelyet a rendszer az elérésükhöz használ.";
$archive_text_022 = "Letiltva. Ehhez az archívumhoz nem léteznek eredményadatok.";
$archive_text_023 = "Nincs stíluskészlet megadva. Az archivált nevezési, pontozási és dobozadatok nem feltétlenül jelennek meg helyesen.";

$label_uploaded_scoresheets = "Feltöltött pontozólapok (PDF fájlok)";
$label_admin_comp_type = "Verseny típusa";
$label_admin_styleset = "Stíluskészlet";
$label_admin_winner_display = "Győztesek megjelenítése";
$label_admin_enable = "Engedélyezés";
$label_admin_disable = "Letiltás";
$label_admin_winner_dist = "Győztesek helyezéselosztási módszere";
$label_admin_archive = "Archívum";
$label_admin_scoresheet_names = "Pontozólap feltöltési fájlnevek";
$label_six_char_judging = "6 karakteres bírálati szám";
$label_six_digit_entry = "6 számjegyű nevezési szám";
$label_not_archived = "Nincs archiválva";

// -------------------- Barcode Check-In --------------------



// -------------------- Navigation --------------------

?>
