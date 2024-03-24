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

$archive_text_000 = "En raison des limitations de stockage du serveur, l'archivage des données du compte BCOE&amp;M hébergé n'est pas disponible. Pour utiliser le logiciel pour une nouvelle compétition ou simplement pour effacer la base de données des données, utilisez les boutons ci-dessous.";
$archive_text_001 = "Les données de catégorie personnalisée, de type de style personnalisé, de lieu de dépôt, de lieu de jugement et de sponsor <strong class=\"text-success\">ne seront pas supprimées</strong>. Les administrateurs devront les mettre à jour pour les futures instances de compétition.";
$archive_text_002 = "Option 1";
$archive_text_003 = "Êtes-vous sûr de vouloir effacer les données de la compétition actuelle ? CECI ne peut pas être annulé.";
$archive_text_004 = "Effacer toutes les données des participants, des entrées, des jugements et des évaluations";
$archive_text_005 = "Cette option efface tous les comptes des participants non administratifs ainsi que toutes les données d'entrée, de jugement et d'évaluation, y compris toutes les fiches de dégustation téléchargées. Offre un nouveau départ.";
$archive_text_006 = "Option 2";
$archive_text_007 = "Êtes-vous sûr de vouloir effacer les données de la compétition actuelle ? CECI ne peut pas être annulé.";
$archive_text_008 = "Effacer uniquement les données d'entrée, de jugement et d'évaluation";
$archive_text_009 = "Cette option efface toutes les données d'entrée, de jugement et d'évaluation, y compris toutes les fiches de dégustation téléchargées, mais conserve les données des participants. Utile si vous ne voulez pas que les participants créent de nouveaux profils de compte.";
$archive_text_010 = "Pour archiver les données actuellement stockées dans la base de données, donnez un nom à l'archive. Il est suggéré de choisir un nom qui soit unique à cet ensemble de données. Par exemple, si vous organisez votre compétition chaque année, le nom pourrait être l'année où elle a eu lieu. Si vous organisez des compétitions successives sur une seule installation, le nom de la compétition et l'année pourraient servir de nom.";
$archive_text_011 = "Caractères alphanumériques uniquement - tous les autres seront omis.";
$archive_text_012 = "Cochez les informations que vous souhaitez conserver pour une utilisation ultérieure dans les futures instances de compétition.";
$archive_text_013 = "Êtes-vous sûr de vouloir archiver les données actuelles ?";
$archive_text_014 = "Ensuite, choisissez quelles données vous souhaitez conserver.";
$archive_text_015 = "Cela supprimera l'archive appelée";
$archive_text_016 = "Tous les enregistrements associés seront également supprimés.";

/**
 * ------------------------------------------------------------------------
 * Version 2.2.0 Additions
 * ------------------------------------------------------------------------
 */
$archive_text_017 = "Modifiez vos informations d'archive avec précaution. Tout changement de ce qui suit peut entraîner un comportement inattendu lors de l'accès aux données archivées.";
$archive_text_018 = "Les fichiers seront déplacés vers un sous-dossier portant le même nom que votre archive dans le répertoire user_docs.";
$archive_text_019 = "Liste des gagnants archivée disponible pour consultation publique.";
$archive_text_020 = "En général, cela ne devrait être modifié que si la liste des gagnants de cette archive s'affiche incorrectement.";
$archive_text_021 = "Les fiches de dégustation au format PDF ont été enregistrées pour cette archive. Il s'agit de la convention de dénomination de chaque fichier utilisé par le système lors de leur accès.";
$archive_text_022 = "Désactivé. Aucune donnée de résultats n'existe pour cette archive.";
$archive_text_023 = "Aucun ensemble de styles n'est spécifié. Les données archivées d'entrée, d'évaluation et de boîte peuvent ne pas s'afficher correctement.";

$label_uploaded_scoresheets = "Fiches de dégustation téléchargées (Fichiers PDF)";
$label_admin_comp_type = "Type de Compétition Administrateur";
$label_admin_styleset = "Ensemble de Styles Administrateur";
$label_admin_winner_display = "Affichage des Gagnants Administrateur";
$label_admin_enable = "Activer";
$label_admin_disable = "Désactiver";
$label_admin_winner_dist = "Méthode de Distribution des Places des Gagnants Administrateur";
$label_admin_archive = "Archiver";
$label_admin_scoresheet_names = "Noms des Fiches de Dégustation Téléchargées";
$label_six_char_judging = "Numéro de Jugement à 6 Caractères";
$label_six_digit_entry = "Numéro d'Entrée à 6 Chiffres";
$label_not_archived = "Non Archivé";

// -------------------- Barcode Check-In --------------------



// -------------------- Navigation --------------------

?>