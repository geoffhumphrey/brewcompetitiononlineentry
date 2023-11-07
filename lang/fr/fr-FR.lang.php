<?php
/**
 * Module:      en-US.lang.php
 * Description: This module houses all display text in the English language.
 * Updated:     November 6, 2023
 * Translation: ChatGPT, DeepL
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

/**
 * Set up PHP variables. No translations in this section.
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
 * BEGIN TRANSLATIONS BELOW!
 * ------------------------------------------------------------------------
 */

$j_s_text = "";
if (strpos($section, "step") === FALSE) {
	if ((isset($judge_limit)) && (isset($steward_limit))) {
		if (($judge_limit) && (!$steward_limit)) $j_s_text = "Steward"; // missing punctuation intentional
		elseif ((!$judge_limit) && ($steward_limit)) $j_s_text = "Judge"; // missing punctuation intentional
		else $j_s_text = "Judge or steward"; // missing punctuation intentional
	}
}

/**
 * ------------------------------------------------------------------------
 * Global Labels
 * Mostly used for titles and navigation.
 * All labels are capitalized and without punctuation.
 * ------------------------------------------------------------------------
 */
$label_home = "Accueil";
$label_welcome = "Bienvenue";
$label_comps = "Répertoire des Compétitions";
$label_info = "Infos";
$label_volunteers = "Bénévoles";
$label_register = "Inscription";
$label_help = "Aide";
$label_print = "Imprimer";
$label_my_account = "Mon Compte";
$label_yes = "Oui";
$label_no = "Non";
$label_low_none = "Faible/Aucun";
$label_low = "Faible";
$label_med = "Moyen";
$label_high = "Élevé";
$label_pay = "Payer les Frais d'Inscription";
$label_reset_password = "Réinitialiser le Mot de Passe";
$label_log_in = "Connexion";
$label_logged_in = "Connecté";
$label_log_out = "Déconnexion";
$label_logged_out = "Déconnecté";
$label_sponsors = "Sponsors";
$label_rules = "Règles";
$label_volunteer_info = "Infos sur les Bénévoles";
$label_reg = $label_register;
$label_judge_reg = "Inscription Juge";
$label_steward_reg = "Inscription Steward";
$label_past_winners = "Anciens Gagnants";
$label_contact = "Contact";
$label_style = "Style";
$label_entry = "Participation";
$label_add_entry = "Ajouter une Participation";
$label_edit_entry = "Modifier une Participation";
$label_upload = "Télécharger";
$label_bos = "Meilleure de la Compétition";
$label_brewer = "Brasseur";
$label_cobrewer = "Co-Brasseur";
$label_entry_name = "Nom de la Participation";
$label_required_info = "Informations Requises";
$label_character_limit = " limite de caractères - utilisez des mots-clés et des abréviations si l'espace est limité.<br>Caractères utilisés : ";
$label_carbonation = "Carbonatation";
$label_sweetness = "Sucrosité";
$label_strength = "Teneur en Alcool";
$label_color = "Couleur";
$label_table = "Table";
$label_standard = "Standard";
$label_super = "Super";
$label_session = "Session";
$label_double = "Double";
$label_blonde = "Blonde";
$label_amber = "Ambrée";
$label_brown = "Brune";
$label_pale = "Pale";
$label_dark = "Foncée";
$label_hydromel = "Hydromel";
$label_sack = "Sack";
$label_still = "Non Pétillante";
$label_petillant = "Pétillante";
$label_sparkling = "Moussante";
$label_dry = "Sec";
$label_med_dry = "Moyennement Sec";
$label_med_sweet = "Moyennement Sucré";
$label_sweet = "Sucré";
$label_brewer_specifics = "Caractéristiques du Brasseur";
$label_general = "Général";
$label_amount_brewed = "Quantité Brassée";
$label_specific_gravity = "Densité Initiale";
$label_fermentables = "Fermentables";
$label_malt_extract = "Extrait de Malt";
$label_grain = "Grain";
$label_hops = "Houblons";
$label_hop = "Houblon";
$label_mash = "Empâtage";
$label_steep = "Infusion";
$label_other = "Autre";
$label_weight = "Poids";
$label_use = "Utilisation";
$label_time = "Temps";
$label_first_wort = "Premier Houblonnage";
$label_boil = "Ébullition";
$label_aroma = "Arôme";
$label_dry_hop = "Houblonnage à Cru";
$label_type = "Type";
$label_bittering = "Amérisant";
$label_both = "Les Deux";
$label_form = "Forme";
$label_whole = "Entier";
$label_pellets = "Granulés";
$label_plug = "Cône";
$label_extract = "Extrait";
$label_date = "Date";
$label_bottled = "Embouteillée";
$label_misc = "Divers";
$label_minutes = "Minutes";
$label_hours = "Heures";
$label_step = "Étape";
$label_temperature = "Température";
$label_water = "Eau";
$label_amount = "Quantité";
$label_yeast = "Levure";
$label_name = "Nom";
$label_manufacturer = "Fabricant";
$label_nutrients = "Nutriments";
$label_liquid = "Liquide";
$label_ale = "Ale";
$label_lager = "Lager";
$label_wine = "Vin";
$label_champagne = "Champagne";
$label_boil = "Ébullition";
$label_fermentation = "Fermentation";
$label_finishing = "Finition";
$label_finings = "Clarifiants";
$label_primary = "Primaire";
$label_secondary = "Secondaire";
$label_days = "Jours";
$label_forced = "CO2 Forcé";
$label_bottle_cond = "Conditionnée en Bouteille";
$label_volume = "Volume";
$label_og = "Densité Initiale";
$label_fg = "Densité Finale";
$label_starter = "Starter";
$label_password = "Mot de Passe";
$label_judging_number = "Numéro de Jugement";
$label_check_in = "Enregistrer la Participation";
$label_box_number = "Numéro de Boîte";
$label_first_name = "Prénom";
$label_last_name = "Nom de Famille";
$label_secret_01 = "Quelle est votre bière préférée de tous les temps ?";
$label_secret_02 = "Quel était le nom de votre premier animal de compagnie ?";
$label_secret_03 = "Quel était le nom de la rue où vous avez grandi ?";
$label_secret_04 = "Quel était la mascotte de votre lycée ?";
$label_security_answer = "Réponse à la Question de Sécurité";
$label_security_question = "Question de Sécurité";
$label_judging = "Jugement";
$label_judge = "Juge";
$label_steward = "Steward";
$label_account_info = "Informations du Compte";
$label_street_address = "Adresse";
$label_address = "Adresse";
$label_city = "Ville";
$label_state_province = "État/Province";
$label_zip = "Code Postal";
$label_country = "Pays";
$label_phone = "Téléphone";
$label_phone_primary = "Téléphone Principal";
$label_phone_secondary = "Téléphone Secondaire";
$label_drop_off = "Livraison des Participations";
$label_drop_offs = "Points de Livraison";
$label_club = "Club";
$label_aha_number = "Numéro de Membre AHA";
$label_org_notes = "Notes pour l'Organisateur";

$label_avail = "Disponibilité";
$label_location = "Emplacement";
$label_judging_avail = "Disponibilité des Séances de Jugement";
$label_stewarding = "Stewarding";
$label_stewarding_avail = "Disponibilité des Séances de Stewarding";
$label_bjcp_id = "Identifiant BJCP";
$label_bjcp_mead = "Juge de Miel Certifié BJCP";
$label_bjcp_rank = "Rang BJCP";
$label_designations = "Désignations";
$label_judge_sensory = "Juge avec Formation Sensorielle";
$label_judge_pro = "Brasseur Professionnel";
$label_judge_comps = "Compétitions Jugées";
$label_judge_preferred = "Styles Préférés";
$label_judge_non_preferred = "Styles Non Préférés";
$label_waiver = "Décharge";
$label_add_admin = "Ajouter les Infos de l'Utilisateur Administrateur";
$label_add_account = "Ajouter les Infos du Compte";
$label_edit_account = "Modifier les Infos du Compte";
$label_entries = "Participations";
$label_confirmed = "Confirmé";
$label_paid = "Payé";
$label_updated = "Mis à Jour";
$label_mini_bos = "Mini-BOS";
$label_actions = "Actions";
$label_score = "Score";
$label_winner = "Gagnant ?";
$label_change_email = "Changer l'Email";
$label_change_password = "Changer le Mot de Passe";
$label_add_beerXML = "Ajouter une Participation en Utilisant BeerXML";
$label_none_entered = "Aucune entrée";
$label_none = "Aucun";
$label_discount = "Réduction";
$label_subject = "Sujet";
$label_message = "Message";
$label_send_message = "Envoyer le Message";
$label_email = "Adresse Email";
$label_account_registration = "Inscription au Compte";
$label_entry_registration = "Inscription des Participations";
$label_entry_fees = "Frais d'Inscription";
$label_entry_limit = "Limite d'Inscription";
$label_entry_info = "Infos sur les Participations";
$label_entry_per_entrant = "Limites par Participant";
$label_categories_accepted = "Styles Acceptés";
$label_judging_categories = "Catégories de Jugement";
$label_entry_acceptance_rules = "Règles d'Acceptation des Participations";
$label_shipping_info = "Infos sur l'Expédition";
$label_packing_shipping = "Emballage et Expédition";
$label_awards = "Récompenses";
$label_awards_ceremony = "Cérémonie de Remise des Prix";
$label_circuit = "Qualification pour le Circuit";
$label_hosted = "Édition Hébergée";
$label_entry_check_in = "Enregistrement des Participations";
$label_cash = "Espèces";
$label_check = "Chèque";
$label_pay_online = "Payer en Ligne";
$label_cancel = "Annuler";
$label_understand = "Je Comprends";
$label_fee_discount = "Frais d'Inscription Réduits";
$label_discount_code = "Code de Réduction";
$label_register_judge = "Vous Inscrivez-vous en tant que Participant, Juge ou Steward ?";
$label_register_judge_standard = "Inscrire un Juge ou un Steward (Standard)";
$label_register_judge_quick = "Inscrire un Juge ou un Steward (Rapide)";
$label_all_participants = "Tous les Participants";
$label_open = "Ouvert";
$label_closed = "Fermé";
$label_judging_loc = "Séances de Jugement";
$label_new = "Nouveau";
$label_old = "Ancien";
$label_sure = "Êtes-vous sûr(e) ?";
$label_judges = "Juges";
$label_stewards = "Stewards";
$label_staff = "Personnel";
$label_category = "Catégorie";
$label_delete = "Supprimer";
$label_undone = "Cela ne peut pas être annulé";
$label_bitterness = "Amertume";
$label_close = "Fermer";
$label_custom_style = "Style Personnalisé";
$label_custom_style_types = "Types de Styles Personnalisés";
$label_assigned_to_table = "Assigné à la Table";
$label_organizer = "Organisateur";
$label_by_table = "Par Table";
$label_by_category = "Par Style";
$label_by_subcategory = "Par Sous-Style";
$label_by_last_name = "Par Nom de Famille";
$label_by_table = "Par Table";
$label_by_location = "Par Emplacement de Séance";
$label_shipping_entries = "Expédition des Participations";
$label_no_availability = "Aucune Disponibilité Définie";
$label_error = "Erreur";
$label_round = "Tour";
$label_flight = "Volée";
$label_rounds = "Tours";
$label_flights = "Volées";
$label_sign_in = "Se Connecter";
$label_signature = "Signature";
$label_assignment = "Affectation";
$label_assignments = "Affectations";
$label_letter = "Lettre";
$label_re_enter = "Ré-Entrer";
$label_website = "Site Web";
$label_place = "Place";
$label_cheers = "Santé";
$label_count = "Compte";
$label_total = "Total";
$label_participant = "Participant";
$label_entrant = "Participant";
$label_received = "Reçu";
$label_please_note = "Veuillez Noter";
$label_pull_order = "Ordre de Tirage";
$label_box = "Boîte";
$label_sorted = "Trié";
$label_subcategory = "Sous-Catégorie";
$label_affixed = "Étiquette Apposée ?";
$label_points = "Points";
$label_comp_id = "ID de Compétition BJCP";
$label_days = "Jours";
$label_sessions = "Séances";
$label_number = "Numéro";
$label_more_info = "Plus d'Infos";
$label_entry_instructions = "Instructions pour les Participations";
$label_commercial_examples = "Exemples Commerciaux";
$label_users = "Utilisateurs";
$label_participants = "Participants";
$label_please_confirm = "Veuillez Confirmer";
$label_undone = "Cela ne peut pas être annulé";
$label_data_retain = "Données à Conserver";
$label_comp_portal = "Répertoire de la Compétition";
$label_comp = "Compétition";
$label_continue = "Continuer";
$label_host = "Hôte";
$label_closing_soon = "Fermeture Imminente";
$label_access = "Accès";
$label_length = "Longueur";
$label_admin = "Administration";
$label_admin_short = "Admin";
$label_admin_dashboard = "Tableau de Bord";
$label_admin_judging = $label_judging;
$label_admin_stewarding = "Stewarding";
$label_admin_participants = $label_participants;
$label_admin_entries = $label_entries;
$label_admin_comp_info = "Informations sur la Compétition";
$label_admin_web_prefs = "Préférences du Site Web";
$label_admin_judge_prefs = "Préférences de Jugement/Organisation de la Compétition";
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
$label_admin_style_types = "Types de Styles";
$label_admin_custom_cat = "Catégories Personnalisées";
$label_admin_custom_cat_data = "Participations dans des Catégories Personnalisées";
$label_admin_sponsors = $label_sponsors;
$label_admin_entry_count = "Nombre de Participations par Style";
$label_admin_entry_count_sub = "Nombre de Participations par Sous-Style";
$label_admin_custom_mods = "Modules Personnalisés";
$label_admin_check_in = $label_entry_check_in;
$label_admin_make_admin = "Changer le Niveau d'Utilisateur";
$label_admin_register = "Inscrire un Participant";
$label_admin_upload_img = "Télécharger des Images";
$label_admin_upload_doc = "Télécharger des Feuilles de Scores et d'Autres Documents";
$label_admin_password = "Changer le Mot de Passe de l'Utilisateur";
$label_admin_edit_account = "Modifier le Compte Utilisateur";
$label_account_summary = "Résumé de Mon Compte";
$label_confirmed_entries = "Participations Confirmées";
$label_unpaid_confirmed_entries = "Participations Confirmées Impayées";
$label_total_entry_fees = "Frais d'Inscription Totaux";
$label_entry_fees_to_pay = "Frais d'Inscription à Payer";
$label_entry_drop_off = "Dépôt des Participations";
$label_maintenance = "Maintenance";
$label_judge_info = "Informations sur le Juge";
$label_cellar = "Ma Cave";
$label_verify = "Vérifier";
$label_entry_number = "Numéro de Participation";

/**
 * ------------------------------------------------------------------------
 * En-têtes
 * La ponctuation manquante est intentionnelle pour tous.
 * ------------------------------------------------------------------------
 */
$header_text_000 = "L'installation s'est déroulée avec succès.";
$header_text_001 = "Vous êtes maintenant connecté et prêt à personnaliser davantage le site de votre compétition.";
$header_text_002 = "Cependant, le fichier de permissions config.php n'a pas pu être modifié.";
$header_text_003 = "Il est fortement recommandé de changer les permissions du serveur (chmod) sur le fichier config.php en 555. Pour ce faire, vous devrez accéder au fichier sur votre serveur.";
$header_text_004 = "De plus, la variable &#36;setup_free_access dans config.php est actuellement définie sur TRUE. Pour des raisons de sécurité, ce paramètre doit être rétabli à FALSE. Vous devrez modifier directement config.php et le réuploader sur votre serveur pour ce faire.";
$header_text_005 = "Informations ajoutées avec succès.";
$header_text_006 = "Informations modifiées avec succès.";
$header_text_007 = "Une erreur s'est produite.";
$header_text_008 = "Veuillez réessayer.";
$header_text_009 = "Vous devez être administrateur pour accéder à toutes les fonctions d'administration.";
$header_text_010 = "Changer";
$header_text_011 = $label_email;
$header_text_012 = $label_password;
$header_text_013 = "L'adresse e-mail fournie est déjà utilisée, veuillez fournir une autre adresse e-mail.";
$header_text_014 = "Il y a eu un problème avec la dernière demande, veuillez réessayer.";
$header_text_015 = "Votre mot de passe actuel était incorrect.";
$header_text_016 = "Veuillez fournir une adresse e-mail.";
$header_text_017 = "Désolé, il y a eu un problème avec votre dernière tentative de connexion.";
$header_text_018 = "Désolé, le nom d'utilisateur que vous avez saisi est déjà utilisé.";
$header_text_019 = "Peut-être avez-vous déjà créé un compte ?";
$header_text_020 = "Si c'est le cas, connectez-vous ici.";
$header_text_021 = "Le nom d'utilisateur fourni n'est pas une adresse e-mail valide.";
$header_text_022 = "Veuillez entrer une adresse e-mail valide.";
$header_text_023 = "CAPTCHA n'a pas réussi.";
$header_text_024 = "Les adresses e-mail que vous avez saisies ne correspondent pas.";
$header_text_025 = "Le numéro AHA que vous avez saisi est déjà dans le système.";
$header_text_026 = "Votre paiement en ligne a été reçu et la transaction a été terminée. Veuillez noter que vous devrez peut-être attendre quelques minutes pour que le statut du paiement soit mis à jour ici - assurez-vous de rafraîchir cette page ou d'accéder à votre liste de participations. Vous recevrez un reçu de paiement par e-mail de PayPal.";
$header_text_027 = "Assurez-vous de bien imprimer le reçu et de le joindre à l'une de vos participations comme preuve de paiement.";
$header_text_028 = "Votre paiement en ligne a été annulé.";
$header_text_029 = "Le code a été vérifié.";
$header_text_030 = "Désolé, le code que vous avez saisi était incorrect.";
$header_text_031 = "Vous devez vous connecter et avoir des privilèges d'administration pour accéder aux fonctions d'administration.";
$header_text_032 = "Désolé, il y a eu un problème avec votre dernière tentative de connexion.";
$header_text_033 = "Veuillez vous assurer que votre adresse e-mail et votre mot de passe sont corrects.";
$header_text_034 = "Un jeton de réinitialisation de mot de passe a été généré et envoyé par e-mail à l'adresse associée à votre compte.";
$header_text_035 = "- vous pouvez maintenant vous connecter en utilisant votre nom d'utilisateur actuel et le nouveau mot de passe.";
$header_text_036 = "Vous avez été déconnecté.";
$header_text_037 = "Se reconnecter ?";
$header_text_038 = "Votre question de vérification ne correspond pas à ce qui se trouve dans la base de données.";
$header_text_039 = "Vos informations de vérification d'identité ont été envoyées à l'adresse e-mail associée à votre compte.";
$header_text_040 = "Votre message a été envoyé à";
$header_text_041 = $header_text_023;
$header_text_042 = "Votre adresse e-mail a été mise à jour.";
$header_text_043 = "Votre mot de passe a été mis à jour.";
$header_text_044 = "Informations supprimées avec succès.";
$header_text_045 = "Vous devriez vérifier toutes vos participations importées à l'aide de BeerXML.";
$header_text_046 = "Vous êtes inscrit.";
$header_text_047 = "Vous avez atteint la limite de participation.";
$header_text_048 = "Votre participation n'a pas été ajoutée.";
$header_text_049 = "Vous avez atteint la limite de participation pour la sous-catégorie.";
$header_text_050 = "Configuration : Installer les Tables et les Données de la Base de Données";
$header_text_051 = "Configuration : Créer un Utilisateur Administrateur";
$header_text_052 = "Configuration : Ajouter des Informations sur l'Utilisateur Administrateur";
$header_text_053 = "Configuration : Définir les Préférences du Site Web";
$header_text_054 = "Configuration : Ajouter des Informations sur la Compétition";
$header_text_055 = "Configuration : Ajouter des Sessions de Jugement";
$header_text_056 = "Configuration : Ajouter des Lieux de Dépôt";
$header_text_057 = "Configuration : Désigner les Styles Acceptés";
$header_text_058 = "Configuration : Définir les Préférences de Jugement";
$header_text_059 = "Importer une Participation à l'Aide de BeerXML";
$header_text_060 = "Votre participation a été enregistrée.";
$header_text_061 = "Votre participation a été confirmée.";
$header_text_065 = "Toutes les participations reçues ont été vérifiées et celles qui n'ont pas été assignées à des tables ont été assignées.";
$header_text_066 = "Informations mises à jour avec succès.";
$header_text_067 = "Le suffixe que vous avez saisi est déjà utilisé, veuillez en saisir un autre.";
$header_text_068 = "Les données de compétition spécifiées ont été effacées.";
$header_text_069 = "Archives créées avec succès. ";
$header_text_070 = "Sélectionnez le nom de l'archive à afficher.";
$header_text_071 = "N'oubliez pas de mettre à jour vos ".$label_admin_comp_info." et vos ".$label_admin_judging_loc." si vous lancez une nouvelle compétition.";
$header_text_072 = "Archive \"".$filter."\" supprimée.";
$header_text_073 = "Les enregistrements ont été mis à jour.";
$header_text_074 = "Le nom d'utilisateur que vous avez saisi est déjà utilisé.";
$header_text_075 = "Ajouter un autre lieu de dépôt ?";
$header_text_076 = "Ajouter une autre session de jugement, date ou heure ?";
$header_text_077 = "La table qui vient d'être définie n'a aucun style associé.";
$header_text_078 = "Une ou plusieurs données obligatoires manquent - indiquées en rouge ci-dessous.";
$header_text_079 = "Les adresses e-mail que vous avez saisies ne correspondent pas.";
$header_text_080 = "Le numéro AHA que vous avez saisi est déjà dans le système.";
$header_text_081 = "Toutes les participations ont été marquées comme payées.";
$header_text_082 = "Toutes les participations ont été marquées comme reçues.";
$header_text_083 = "Toutes les participations non confirmées sont désormais marquées comme confirmées.";
$header_text_084 = "Toutes les affectations de participants ont été effacées.";
$header_text_085 = "Un numéro de jugement que vous avez saisi n'a pas été trouvé dans la base de données.";
$header_text_086 = "Tous les styles de participation ont été convertis de BJCP 2008 à BJCP 2015.";
$header_text_087 = "Données supprimées avec succès.";
$header_text_088 = "Le juge/le steward a été ajouté avec succès. N'oubliez pas d'assigner l'utilisateur en tant que juge ou steward avant de l'assigner à des tables.";
$header_text_089 = "Le fichier a été téléchargé avec succès. Vérifiez la liste pour vérifier.";
$header_text_090 = "Le fichier qui a été tenté d'être téléchargé n'est pas un type de fichier accepté.";
$header_text_091 = "Fichier(s) supprimé(s) avec succès.";
$header_text_092 = "L'e-mail de test a été généré. Assurez-vous de vérifier votre dossier de courrier indésirable.";
$header_text_093 = "Le mot de passe de l'utilisateur a été changé. Assurez-vous de lui communiquer son nouveau mot de passe !";
$header_text_094 = "Le changement de la permission du dossier user_images en 755 a échoué.";
$header_text_095 = "Vous devrez changer manuellement la permission du dossier. Consultez votre programme FTP ou la documentation de votre FAI pour chmod (permissions de dossier).";
$header_text_096 = "Les numéros de jugement ont été régénérés.";
$header_text_097 = "Votre installation a été configurée avec succès !";
$header_text_098 = "POUR DES RAISONS DE SÉCURITÉ, vous devriez immédiatement définir la variable &#36;setup_free_access dans config.php sur FALSE.";
$header_text_099 = "Sinon, votre installation et votre serveur sont vulnérables aux violations de sécurité.";
$header_text_100 = "Connectez-vous maintenant pour accéder au Tableau de Bord de l'Administration";
$header_text_101 = "Votre installation a été mise à jour avec succès !";
$header_text_102 = "Les adresses e-mail ne correspondent pas.";
$header_text_103 = "Veuillez vous connecter pour accéder à votre compte.";
$header_text_104 = "Vous n'avez pas suffisamment de privilèges d'accès pour afficher cette page.";
$header_text_105 = "Plus d'informations sont nécessaires pour que votre participation soit acceptée et confirmée.";
$header_text_106 = "Consultez les zones surlignées en ROUGE ci-dessous.";
$header_text_107 = "Veuillez choisir un style.";
$header_text_108 = "Cette participation ne peut pas être acceptée ou confirmée tant qu'un style n'a pas été choisi. Les participations non confirmées peuvent être supprimées du système sans avertissement.";
$header_text_109 = "Vous êtes inscrit en tant que steward.";
$header_text_110 = "Toutes les participations ont été démarquées comme payées.";
$header_text_111 = "Toutes les participations ont été démarquées comme reçues.";

/**
 * ------------------------------------------------------------------------
 * Alerts
 * ------------------------------------------------------------------------
 */
$alert_text_000 = "Accordez avec précaution les droits d'administrateur principal et d'administrateur aux utilisateurs.";
$alert_text_001 = "Nettoyage des données terminé.";
$alert_text_002 = "La variable &#36;setup_free_access dans config.php est actuellement définie sur TRUE.";
$alert_text_003 = "Pour des raisons de sécurité, la valeur devrait être retournée à FALSE. Vous devrez éditer config.php directement et réuploader le fichier sur votre serveur.";
$alert_text_005 = "Aucun lieu de dépôt n'a été spécifié.";
$alert_text_006 = "Ajouter un lieu de dépôt ?";
$alert_text_008 = "Aucune session de jugement n'a été spécifiée.";
$alert_text_009 = "Ajouter une session de jugement ?";
$alert_text_011 = "Aucun contact de compétition n'a été spécifié.";
$alert_text_012 = "Ajouter un contact de compétition ?";
$alert_text_014 = "Votre ensemble de styles actuel est BJCP 2008.";
$alert_text_015 = "Voulez-vous convertir toutes les participations en BJCP 2015 ?";
$alert_text_016 = "Êtes-vous sûr ? Cette action convertira toutes les participations de la base de données pour qu'elles soient conformes aux directives de style BJCP 2015. Les catégories seront 1:1 lorsque possible, cependant, certains styles spéciaux peuvent nécessiter une mise à jour de la part de l'entrant.";
$alert_text_017 = "Pour conserver la fonctionnalité, la conversion doit être effectuée <em>avant</em> la définition des tables.";
$alert_text_019 = "Toutes les participations non confirmées ont été supprimées de la base de données.";
$alert_text_020 = "Les participations non confirmées sont mises en évidence et sont indiquées par une icône <span class=\"fa fa-sm fa-exclamation-triangle text-danger\"></span>.";
$alert_text_021 = "Les participants doivent être contactés. Ces participations ne sont pas incluses dans le calcul des frais.";
$alert_text_023 = "Ajouter un lieu de dépôt ?";
$alert_text_024 = $label_yes;
$alert_text_025 = $label_no;
$alert_text_027 = "L'inscription des participations n'est pas encore ouverte.";
$alert_text_028 = "L'inscription des participations est close.";
$alert_text_029 = "L'ajout de participations n'est pas disponible.";
$alert_text_030 = "La limite de participation à la compétition a été atteinte.";
$alert_text_031 = "Votre limite personnelle de participation a été atteinte.";
$alert_text_032 = "Vous pourrez ajouter des participations à partir du ".$entry_open.".";
$alert_text_033 = "L'inscription des comptes sera ouverte à partir du ".$reg_open.".";
$alert_text_034 = "Veuillez revenir alors pour enregistrer votre compte.";
$alert_text_036 = "L'inscription des participations sera ouverte à partir du ".$entry_open.".";
$alert_text_037 = "Veuillez revenir alors pour ajouter vos participations au système.";
$alert_text_039 = "L'inscription des juges et des stewards sera ouverte à partir du ".$judge_open.".";
$alert_text_040 = "Veuillez revenir alors pour vous inscrire en tant que juge ou steward.";
$alert_text_042 = "L'inscription des participations est ouverte !";
$alert_text_043 = "Un total de ".$total_entries." participations a été ajouté au système à compter du ".$current_time.".";
$alert_text_044 = "Les inscriptions seront closes ";
$alert_text_046 = "La limite de participation est presque atteinte !";
$alert_text_047 = $total_entries." sur un maximum de ".$row_limits['prefsEntryLimit']." participations ont été ajoutées au système à compter du ".$current_time.".";
$alert_text_049 = "La limite de participation a été atteinte.";
$alert_text_050 = "La limite de ".$row_limits['prefsEntryLimit']." participations a été atteinte. Aucune autre participation ne sera acceptée.";
$alert_text_052 = "La limite de participation payée a été atteinte.";
$alert_text_053 = "La limite de ".$row_limits['prefsEntryLimitPaid']." participations <em>payées</em> a été atteinte. Aucune autre participation ne sera acceptée.";
$alert_text_055 = "Les inscriptions sont closes.";
$alert_text_056 = "Si vous avez déjà enregistré un compte,";
$alert_text_057 = "connectez-vous ici"; // minuscule et sans ponctuation intentionnelle
$alert_text_059 = "L'inscription des participations est close.";
$alert_text_060 = "Un total de ".$total_entries." participations a été ajouté au système.";
$alert_text_062 = "La remise des participations est close.";
$alert_text_063 = "Les bouteilles de participation ne sont plus acceptées aux lieux de dépôt.";
$alert_text_065 = "L'expédition des participations est close.";
$alert_text_066 = "Les bouteilles de participation ne sont plus acceptées à l'adresse d'expédition.";
$alert_text_068 = $j_s_text." inscriptions ouvertes.";
$alert_text_069 = "Inscrivez-vous ici"; // ponctuation manquante intentionnelle
$alert_text_070 = $j_s_text." inscriptions seront closes ".$judge_closed.".";
$alert_text_072 = "La limite des juges inscrits a été atteinte.";
$alert_text_073 = "Aucune autre inscription de juge ne sera acceptée.";
$alert_text_074 = "L'inscription en tant que steward est toujours disponible.";
$alert_text_076 = "La limite des stewards inscrits a été atteinte.";
$alert_text_077 = "Aucune autre inscription de steward ne sera acceptée.";
$alert_text_078 = "L'inscription en tant que juge est toujours disponible.";
$alert_text_080 = "Mot de passe incorrect.";
$alert_text_081 = "Mot de passe accepté.";
$alert_email_valid = "Le format de l'adresse e-mail est valide.";
$alert_email_not_valid = "Le format de l'adresse e-mail n'est pas valide.";
$alert_email_in_use = "L'adresse e-mail que vous avez saisie est déjà utilisée. Veuillez en choisir une autre.";
$alert_email_not_in_use = "Félicitations ! L'adresse e-mail que vous avez saisie n'est pas utilisée.";

/**
 * ------------------------------------------------------------------------
 * Public Pages
 * ------------------------------------------------------------------------
 */
$comps_text_000 = "Choisissez la compétition que vous souhaitez accéder dans la liste ci-dessous.";
$comps_text_001 = "Compétition en cours :";
$comps_text_002 = "Il n'y a actuellement aucune compétition avec des fenêtres d'inscription ouvertes.";
$comps_text_003 = "Il n'y a actuellement aucune compétition avec des fenêtres d'inscription se fermant dans les 7 prochains jours.";

/**
 * ------------------------------------------------------------------------
 * BeerXML
 * ------------------------------------------------------------------------
 */
$beerxml_text_000 = "L'importation des participations n'est pas disponible.";
$beerxml_text_001 = "a été téléchargé et le brassin a été ajouté à votre liste de participations.";
$beerxml_text_002 = "Désolé, ce type de fichier n'est pas autorisé à être téléchargé. Seules les extensions de fichier .xml sont autorisées.";
$beerxml_text_003 = "La taille du fichier dépasse 2 Mo. Veuillez ajuster la taille et réessayer.";
$beerxml_text_004 = "Fichier non valide spécifié.";
$beerxml_text_005 = "Cependant, elle n'a pas été confirmée. Pour confirmer votre participation, accédez à votre liste de participations pour obtenir de plus amples instructions. Ou, vous pouvez ajouter une autre participation BeerXML ci-dessous.";
$beerxml_text_006 = "La version de PHP de votre serveur ne prend pas en charge la fonction d'importation BeerXML.";
$beerxml_text_007 = "La version de PHP 5.x ou supérieure est requise - ce serveur exécute la version PHP ".$php_version.".";
$beerxml_text_008 = "Recherchez votre fichier compatible BeerXML sur votre disque dur et sélectionnez <em>Télécharger</em>.";
$beerxml_text_009 = "Choisissez le fichier BeerXML";
$beerxml_text_010 = "Aucun fichier sélectionné...";
$beerxml_text_011 = "participations ajoutées"; // minuscule et sans ponctuation intentionnelle
$beerxml_text_012 = "participation ajoutée"; // minuscule et sans ponctuation intentionnelle

/**
 * ------------------------------------------------------------------------
 * Add Entry
 * ------------------------------------------------------------------------
 */
$brew_text_000 = "Sélectionnez pour des détails sur le style"; // ponctuation manquante intentionnelle
$brew_text_001 = "Les juges ne connaîtront pas le nom de votre participation.";
$brew_text_002 = "[désactivé - limite de style atteinte]"; // ponctuation manquante intentionnelle
$brew_text_003 = "[désactivé - limite de style atteinte pour l'utilisateur]"; // ponctuation manquante intentionnelle
$brew_text_004 = "Un type spécifique, des ingrédients spéciaux, un style classique, une force (pour les styles de bière) et/ou une couleur sont requis.";
$brew_text_005 = "Force requise"; // ponctuation manquante intentionnelle
$brew_text_006 = "Niveau de carbonatation requis"; // ponctuation manquante intentionnelle
$brew_text_007 = "Niveau de douceur requis"; // ponctuation manquante intentionnelle
$brew_text_008 = "Ce style nécessite que vous fournissiez des informations spécifiques pour la participation.";
$brew_text_009 = "Exigences pour"; // ponctuation manquante intentionnelle
$brew_text_010 = "Ce style nécessite plus d'informations. Veuillez les saisir dans la zone prévue.";
$brew_text_011 = "Le nom de la participation est requis.";
$brew_text_012 = "*** NON REQUIS *** Fournissez UNIQUEMENT si vous souhaitez que les juges tiennent pleinement compte de ce que vous écrivez ici lors de l'évaluation et de la notation de votre participation. Utilisez-le pour enregistrer des détails que vous souhaitez que les juges prennent en compte lors de l'évaluation de votre participation et que vous n'avez PAS SPÉCIFIÉ dans d'autres champs (par exemple, technique de brassage, variété de houblon, variété de miel, variété de raisin, variété de poire, etc.).";
$brew_text_013 = "N'utilisez PAS ce champ pour spécifier des ingrédients spéciaux, un style classique, une force (pour les participations de bière) ou une couleur.";
$brew_text_014 = "Fournissez uniquement si vous souhaitez que les juges tiennent pleinement compte de ce que vous spécifiez lors de l'évaluation et de la notation de votre participation.";
$brew_text_015 = "Type d'extrait (par exemple, clair, foncé) ou marque.";
$brew_text_016 = "Type de céréale (par exemple, pilsner, pale ale, etc.)";
$brew_text_017 = "Type d'ingrédient ou nom.";
$brew_text_018 = "Nom du houblon.";
$brew_text_019 = "Chiffres uniquement, s'il vous plaît.";
$brew_text_020 = "Nom de la souche (par exemple, 1056 American Ale).";
$brew_text_021 = "Wyeast, White Labs, etc.";
$brew_text_022 = "1 smackpack, 2 fioles, 2000 ml, etc.";
$brew_text_023 = "Fermentation primaire en jours.";
$brew_text_024 = "Repos de saccharification, etc.";
$brew_text_025 = "Fermentation secondaire en jours.";
$brew_text_026 = "Autre fermentation en jours.";

/**
 * ------------------------------------------------------------------------
 * My Account
 * ------------------------------------------------------------------------
 */
$brewer_text_000 = "Veuillez entrer uniquement <em>un</em> nom de personne.";
$brewer_text_001 = "Choisissez l'une. Cette question sera utilisée pour vérifier votre identité en cas d'oubli de votre mot de passe.";
$brewer_text_003 = "La saisie n'accepte que des caractères numériques. Pour être pris en compte pour une opportunité de brassage GABF Pro-Am, vous devez être membre de l'AHA.";
$brewer_text_004 = "Fournissez toutes les informations que vous pensez que l'organisateur de la compétition, le coordinateur des juges ou le personnel de la compétition devrait connaître (par exemple, allergies, restrictions alimentaires spéciales, taille de chemise, etc.).";
$brewer_text_005 = "Non applicable";
$brewer_text_006 = "Êtes-vous disposé et qualifié pour servir en tant que juge dans cette compétition ?";
$brewer_text_007 = "Avez-vous réussi l'examen de juge de bière BJCP ?";
$brewer_text_008 = "* Le rang <em>Non-BJCP</em> est destiné à ceux qui n'ont pas passé l'examen d'entrée au juge de bière BJCP et qui ne sont <em>pas</em> brasseurs professionnels.";
$brewer_text_009 = "** Le rang <em>Provisoire</em> est destiné à ceux qui ont passé et réussi l'examen d'entrée au juge de bière BJCP, mais qui n'ont pas encore passé l'examen de jugement de bière BJCP.";
$brewer_text_010 = "Seules les deux premières désignations apparaîtront sur vos étiquettes de feuille de scores imprimées.";
$brewer_text_011 = "Combien de compétitions avez-vous déjà servies en tant que <strong>".strtolower($label_judge)."</strong> ?";
$brewer_text_012 = "Uniquement pour les préférences. Le fait de laisser un style non coché indique que vous êtes disponible pour le juger - il n'est pas nécessaire de cocher tous les styles que vous êtes prêt à juger.";
$brewer_text_013 = "Sélectionnez ou appuyez sur le bouton ci-dessus pour développer la liste des styles non préférés à juger.";
$brewer_text_014 = "Il n'est pas nécessaire de marquer les styles pour lesquels vous avez des participations ; le système ne vous permettra pas d'être affecté à une table où vous avez des participations.";
$brewer_text_015 = "Êtes-vous disposé à servir en tant que steward dans cette compétition ?";
$brewer_text_016 = "Ma participation à ce jugement est entièrement volontaire. Je sais que ma participation à ce jugement implique la consommation de boissons alcoolisées et que cette consommation peut affecter mes perceptions et mes réactions.";
$brewer_text_017 = "Sélectionnez ou appuyez sur le bouton ci-dessus pour développer la liste des styles préférés à juger.";
$brewer_text_018 = "En cochant cette case, j'accepte effectivement un document juridique dans lequel j'accepte la responsabilité de mon comportement, de mon comportement et de mes actions et j'exonère complètement la compétition et ses organisateurs, individuellement ou collectivement, de toute responsabilité pour mon comportement, mon comportement et mes actions.";
$brewer_text_019 = "Si vous prévoyez de servir en tant que juge dans une compétition, sélectionnez ou appuyez sur le bouton ci-dessus pour saisir vos informations de juge.";
$brewer_text_020 = "Êtes-vous disposé à servir en tant que membre du personnel dans cette compétition ?";
$brewer_text_021 = "Les membres du personnel de la compétition sont des personnes qui occupent divers rôles pour aider à l'organisation et à l'exécution de la compétition avant, pendant et après le jugement. Les juges et les stewards peuvent également servir en tant que membres du personnel. Les membres du personnel peuvent gagner des points BJCP si la compétition est sanctionnée.";

/**
 * ------------------------------------------------------------------------
 * Contact
 * ------------------------------------------------------------------------
 */
$contact_text_000 = "Utilisez les liens ci-dessous pour contacter les personnes impliquées dans la coordination de cette compétition :";
$contact_text_001 = "Utilisez le formulaire ci-dessous pour contacter un responsable de la compétition. Tous les champs marqués d'une étoile (*) sont obligatoires.";
$contact_text_002 = "De plus, une copie a été envoyée à l'adresse e-mail que vous avez fournie.";
$contact_text_003 = "Souhaitez-vous envoyer un autre message ?";

/**
 * ------------------------------------------------------------------------
 * Home Pages
 * ------------------------------------------------------------------------
 */
$default_page_text_000 = "Aucun lieu de dépôt n'a été spécifié.";
$default_page_text_001 = "Ajouter un lieu de dépôt ?";
$default_page_text_002 = "Aucune date/lieu de jugement n'a été spécifiée.";
$default_page_text_003 = "Ajouter un lieu de jugement ?";
$default_page_text_004 = "Gagnants";
$default_page_text_005 = "Les gagnants seront annoncés à partir du";
$default_page_text_006 = "Bienvenue";
$default_page_text_007 = "Consultez vos informations de compte et la liste de vos participations.";
$default_page_text_008 = "Consultez vos informations de compte ici.";
$default_page_text_009 = "Meilleurs Gagnants";
$default_page_text_010 = "Gagnants";
$default_page_text_011 = "Vous n'avez besoin de vous enregistrer qu'une seule fois et pouvez revenir sur ce site pour entrer plus de brassins ou modifier les brassins que vous avez entrés.";
$default_page_text_012 = "Vous pouvez même payer vos frais de participation en ligne si vous le souhaitez.";
$default_page_text_013 = "Responsable de la compétition";
$default_page_text_014 = "Responsables de la compétition";
$default_page_text_015 = "Vous pouvez envoyer un e-mail à l'une des personnes suivantes via ";
$default_page_text_016 = "est fier d'avoir le suivant";
$default_page_text_017 = "pour le";
$default_page_text_018 = "Téléchargez les gagnants du meilleur du spectacle au format PDF.";
$default_page_text_019 = "Téléchargez les gagnants du meilleur du spectacle au format HTML.";
$default_page_text_020 = "Téléchargez les participations gagnantes au format PDF.";
$default_page_text_021 = "Téléchargez les participations gagnantes au format HTML.";
$default_page_text_022 = "Merci de votre intérêt pour le";
$default_page_text_023 = "organisé par";
$reg_open_text_000 = "L'inscription des juges et des stewards est";
$reg_open_text_001 = "Ouverte";
$reg_open_text_002 = "Si vous <em>n'avez pas</em> encore vous êtes enregistré et êtes prêt à être volontaire,";
$reg_open_text_003 = "veuillez vous inscrire";
$reg_open_text_004 = "Si vous <em>êtes</em> déjà inscrit, connectez-vous, puis choisissez <em>Modifier le compte</em> dans le menu Mon compte indiqué par";
$reg_open_text_005 = "icône dans le menu supérieur.";
$reg_open_text_006 = "Comme vous êtes déjà inscrit, vous pouvez";
$reg_open_text_007 = "vérifier vos informations de compte";
$reg_open_text_008 = "pour voir si vous avez indiqué que vous êtes prêt à juger et/ou à être steward.";
$reg_open_text_009 = "Si vous êtes prêt à juger ou à être steward, veuillez revenir vous inscrire à partir du";
$reg_open_text_010 = "L'inscription des participations est";
$reg_open_text_011 = "Pour ajouter vos participations dans le système";
$reg_open_text_012 = "veuillez suivre le processus d'inscription";
$reg_open_text_013 = "si vous avez déjà un compte.";
$reg_open_text_014 = "utilisez le formulaire d'ajout d'une participation";
$reg_open_text_015 = "L'inscription des juges est";
$reg_open_text_016 = "L'inscription des stewards est";
$reg_closed_text_000 = "Merci à tous ceux qui ont participé au";
$reg_closed_text_001 = "Il y a";
$reg_closed_text_002 = "participations jugées et";
$reg_closed_text_003 = "participants, juges et stewards enregistrés.";
$reg_closed_text_004 = "participations enregistrées et";
$reg_closed_text_005 = "participants, juges et stewards enregistrés.";
$reg_closed_text_006 = "À partir du";
$reg_closed_text_007 = "participations reçues et traitées (ce nombre sera mis à jour au fur et à mesure que les participations seront récupérées dans les lieux de dépôt et organisées pour le jugement).";
$reg_closed_text_008 = "Les dates du jugement de la compétition doivent encore être déterminées. Veuillez revenir ultérieurement.";
$reg_closed_text_009 = "Plan vers";
$judge_closed_000 = "Merci à tous ceux qui ont participé au";
$judge_closed_001 = "Il y avait";
$judge_closed_002 = "participations jugées et";
$judge_closed_003 = "participants, juges et stewards enregistrés.";

/**
 * ------------------------------------------------------------------------
 * Entry Info
 * ------------------------------------------------------------------------
 */
$entry_info_text_000 = "Vous pourrez créer votre compte à partir du";
$entry_info_text_001 = "jusqu'au";
$entry_info_text_002 = "Les juges et les stewards peuvent s'inscrire à partir du";
$entry_info_text_003 = "par participation";
$entry_info_text_004 = "Vous pouvez créer votre compte dès aujourd'hui jusqu'au";
$entry_info_text_005 = "Les juges et les stewards peuvent s'inscrire dès maintenant jusqu'au";
$entry_info_text_006 = "Inscriptions pour";
$entry_info_text_007 = "juges et stewards uniquement";
$entry_info_text_008 = "acceptées jusqu'au";
$entry_info_text_009 = "L'inscription est <strong class=\"text-danger\">fermée</strong>.";
$entry_info_text_010 = "Bienvenue";
$entry_info_text_011 = "Consultez vos informations de compte et la liste de vos participations.";
$entry_info_text_012 = "Consultez vos informations de compte ici.";
$entry_info_text_013 = "par participation après le";
$entry_info_text_014 = "Vous pourrez ajouter vos participations dans le système à partir du";
$entry_info_text_015 = "Vous pouvez ajouter vos participations dès aujourd'hui jusqu'au";
$entry_info_text_016 = "L'inscription des participations est <strong class=\"text-danger\">fermée</strong>.";
$entry_info_text_017 = "pour un nombre illimité de participations.";
$entry_info_text_018 = "pour les membres AHA.";
$entry_info_text_019 = "Il y a une limite de";
$entry_info_text_020 = "participations pour cette compétition.";
$entry_info_text_021 = "Chaque participant est limité à";
$entry_info_text_022 = strtolower($label_entry);
$entry_info_text_023 = strtolower($label_entries);
$entry_info_text_024 = "participation par sous-catégorie";
$entry_info_text_025 = "participations par sous-catégorie";
$entry_info_text_026 = "des exceptions sont détaillées ci-dessous";
$entry_info_text_027 = strtolower($label_subcategory);
$entry_info_text_028 = "sous-catégories";
$entry_info_text_029 = "participation pour les catégories suivantes";
$entry_info_text_030 = "participations pour les catégories suivantes";
$entry_info_text_031 = "Après avoir créé votre compte et ajouté vos participations dans le système, vous devez payer vos frais de participation. Les méthodes de paiement acceptées sont :";
$entry_info_text_032 = $label_cash;
$entry_info_text_033 = $label_check.", à l'ordre de";
$entry_info_text_034 = "Carte de crédit/débit et e-chèque, via PayPal";
$entry_info_text_035 = "Les dates du jugement de la compétition doivent encore être déterminées. Veuillez revenir ultérieurement.";
$entry_info_text_036 = "Les bouteilles de participation seront acceptées à notre lieu d'expédition à partir du";
$entry_info_text_037 = "Expédiez les participations à :";
$entry_info_text_038 = "Emballer soigneusement vos participations dans une boîte solide. Tapissez l'intérieur de votre carton avec un sac en plastique. Séparez et emballez chaque bouteille avec un matériau d'emballage adéquat. Veuillez ne pas surcharger !";
$entry_info_text_039 = "Écrivez clairement : <em>Fragile. This Side Up.</em> sur le paquet. Utilisez uniquement du papier bulle comme matériau d'emballage.";
$entry_info_text_040 = "Placez <em>chaque</em> étiquette de bouteille dans un petit sac zip-top avant de les attacher à leurs bouteilles respectives. De cette manière, l'organisateur pourra identifier spécifiquement quelle participation s'est cassée en cas de dommage pendant l'expédition.";
$entry_info_text_041 = "Tous les efforts raisonnables seront déployés pour contacter les participants dont les bouteilles se sont cassées afin de prendre des dispositions pour l'envoi de bouteilles de remplacement.";
$entry_info_text_042 = "Si vous résidez aux États-Unis, veuillez noter qu'il est <strong>illégal</strong> d'expédier vos participations via le United States Postal Service (USPS). Les entreprises de transport privées ont le droit de refuser votre envoi si elles sont informées que le colis contient du verre et/ou des boissons alcoolisées. Sachez que les participations envoyées à l'international sont souvent soumises par la douane à une réglementation appropriée. Ces participations peuvent être ouvertes et/ou renvoyées à l'expéditeur par les agents des douanes à leur discrétion. Il vous incombe uniquement de respecter toutes les lois et réglementations applicables.";
$entry_info_text_043 = "Les bouteilles de participation seront acceptées dans nos lieux de dépôt à partir du";
$entry_info_text_044 = "Plan vers";
$entry_info_text_045 = "Sélectionnez/Tapez pour les informations de participation requises";
$entry_info_text_046 = "Si le nom d'un style est un hyperlien, il a des exigences spécifiques pour la participation. Sélectionnez ou tapez sur le nom pour afficher les exigences de la sous-catégorie.";

/**
 * ------------------------------------------------------------------------
 * My Account Entry List
 * ------------------------------------------------------------------------
 */
$brewer_entries_text_000 = "Il existe un problème connu d'impression depuis le navigateur Firefox.";
$brewer_entries_text_001 = "Vous avez des participations non confirmées.";
$brewer_entries_text_002 = "Pour chaque participation ci-dessous avec une icône <span class=\"fa fa-lg fa-exclamation-circle text-danger\"></span>, sélectionnez l'icône <span class=\"fa fa-lg fa-pencil text-primary\"></span> pour examiner et confirmer toutes vos données de participation. Les participations non confirmées peuvent être supprimées du système sans préavis.";
$brewer_entries_text_003 = "VOUS NE POUVEZ PAS payer vos participations tant que toutes les participations ne sont pas confirmées.";
$brewer_entries_text_004 = "Vous avez des participations qui nécessitent la définition d'un type spécifique, d'ingrédients spéciaux, d'un style classique, d'une force et/ou d'une couleur spécifiques.";
$brewer_entries_text_005 = "Pour chaque participation ci-dessous avec une icône <span class=\"fa fa-lg fa-exclamation-circle text-danger\"></span>, sélectionnez l'icône <span class=\"fa fa-lg fa-pencil text-primary\"></span> pour saisir les informations requises. Les participations sans type spécifique, ingrédients spéciaux, style classique, force et/ou couleur dans les catégories qui les requièrent peuvent être supprimées par le système sans préavis.";
$brewer_entries_text_006 = "Téléchargez les fiches de dégustation des juges pour";
$brewer_entries_text_007 = "Style non saisi";
$brewer_entries_text_008 = "Formulaire de participation et";
$brewer_entries_text_009 = "Étiquettes de bouteille";
$brewer_entries_text_010 = "Imprimer le formulaire de recette pour";
$brewer_entries_text_011 = "De plus, vous ne pourrez pas ajouter une autre participation, car la limite de participation pour la compétition a été atteinte. Sélectionnez Annuler dans cette boîte, puis modifiez la participation si vous souhaitez la conserver.";
$brewer_entries_text_012 = "Êtes-vous sûr de vouloir supprimer la participation appelée";
$brewer_entries_text_013 = "Vous pourrez ajouter des participations à partir du";
$brewer_entries_text_014 = "Vous n'avez pas encore ajouté de participations au système.";
$brewer_entries_text_015 = "Vous ne pouvez pas supprimer votre participation pour le moment.";

/**
 * ------------------------------------------------------------------------
 * Past Winners
 * ------------------------------------------------------------------------
 */
$past_winners_text_000 = "Voir les anciens gagnants :";

/**
 * ------------------------------------------------------------------------
 * Pay for Entries
 * ------------------------------------------------------------------------
 */
$pay_text_000 = "Étant donné que les dates d'inscription au compte, d'inscription aux participations, d'expédition et de dépôt sont toutes passées, les paiements ne sont plus acceptés.";
$pay_text_001 = "Contactez un responsable de la compétition si vous avez des questions.";
$pay_text_002 = "Voici vos options pour payer vos frais de participation.";
$pay_text_003 = "Les frais sont de";
$pay_text_004 = "par participation";
$pay_text_005 = "par participation après le";
$pay_text_006 = "pour un nombre illimité de participations";
$pay_text_007 = "Vos frais ont été réduits à";
$pay_text_008 = "Le montant total de vos frais de participation est de";
$pay_text_009 = "Vous devez payer";
$pay_text_010 = "Vos frais ont été marqués comme payés. Merci !";
$pay_text_011 = "Vous avez actuellement";
$pay_text_012 = "participations confirmées impayées";
$pay_text_013 = "Joignez un chèque pour le montant total de la participation à l'une de vos bouteilles. Les chèques doivent être libellés à l'ordre de";
$pay_text_014 = "Votre chèque carbone ou chèque encaissé fait office de reçu de participation.";
$pay_text_015 = "Joignez le paiement en espèces pour le montant total de la participation dans une <em>enveloppe scellée</em> à l'une de vos bouteilles.";
$pay_text_016 = "Vos fiches de dégustation retournées serviront de reçu de participation.";
$pay_text_017 = "Votre e-mail de confirmation de paiement est votre reçu de participation. Incluez une copie avec vos participations comme preuve de paiement.";
$pay_text_018 = "Sélectionnez le bouton <em>Payer avec PayPal</em> ci-dessous pour payer en ligne.";
$pay_text_019 = "Veuillez noter qu'une commission de transaction de";
$pay_text_020 = "sera ajoutée à votre total.";
$pay_text_021 = "Pour vous assurer que votre paiement PayPal est marqué comme <strong>payé</strong> sur <strong>ce site</strong>, assurez-vous de sélectionner le lien <em>Retour à...</em> sur l'écran de confirmation de PayPal <strong>après</strong> avoir envoyé votre paiement. De plus, assurez-vous d'imprimer votre reçu de paiement et de le joindre à l'une de vos bouteilles de participation.";
$pay_text_022 = "Assurez-vous de sélectionner <em>Retour à...</em> après avoir payé vos frais";
$pay_text_023 = "Entrez le code fourni par les organisateurs de la compétition pour bénéficier d'une réduction sur les frais de participation.";
$pay_text_024 = $pay_text_010;
$pay_text_025 = "Vous n'avez encore enregistré aucune participation.";
$pay_text_026 = "Vous ne pouvez pas payer vos participations car l'une ou plusieurs de vos participations ne sont pas confirmées.";
$pay_text_027 = "Sélectionnez <em>Mon compte</em> ci-dessus pour examiner vos participations non confirmées.";
$pay_text_028 = "Vous avez des participations non confirmées qui ne sont <em>pas</em> reflétées dans le total des frais ci-dessous.";
$pay_text_029 = "Veuillez consulter votre liste de participations pour confirmer toutes vos données de participation. Les participations non confirmées peuvent être supprimées du système sans préavis.";

/**
 * ------------------------------------------------------------------------
 * QR Code Check-in
 * ------------------------------------------------------------------------
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
if (strpos($view, "^") !== FALSE) $qr_text_002 = sprintf("Le numéro d'entrée <span class=\"text-danger\">%s</span> est enregistré avec le numéro de jugement <span class=\"text-danger\">%s</span>.",$qr_text_019,$qr_text_020); else $qr_text_002 = "";
$qr_text_003 = "Si ce numéro de jugement n'est <em>pas</em> correct, <strong>re-scannez le code et saisissez le bon numéro de jugement.";
if (strpos($view, "^") !== FALSE) $qr_text_004 = sprintf("L'entrée numéro %s est enregistrée.",$qr_text_019); else $qr_text_004 = "";
if (strpos($view, "^") !== FALSE) $qr_text_005 = sprintf("L'entrée numéro %s n'a pas été trouvée dans la base de données. Mettez de côté la ou les bouteilles et informez l'organisateur de la compétition.",$qr_text_019); else $qr_text_005 = "";
if (strpos($view, "^") !== FALSE) $qr_text_006 = sprintf("Le numéro de jugement que vous avez saisi - %s - est déjà attribué à l'entrée numéro %s.",$qr_text_020,$qr_text_019); else $qr_text_006 = "";
$qr_text_007 = "Enregistrement d'entrée par code QR";
$qr_text_008 = "Pour enregistrer des entrées via un code QR, veuillez fournir le mot de passe correct. Vous devrez fournir le mot de passe une seule fois par session - assurez-vous de laisser l'application de numérisation de code QR ouverte.";
$qr_text_009 = "Attribuer un numéro de jugement et/ou un numéro de boîte à l'entrée";
$qr_text_010 = "SAISISSEZ UNIQUEMENT un numéro de jugement si votre compétition utilise des étiquettes de numéro de jugement pour le tri.";
$qr_text_011 = "Six chiffres avec des zéros en tête - par exemple, 000021.";
$qr_text_012 = "Assurez-vous de vérifier votre saisie et d'apposer les étiquettes de numéro de jugement appropriées sur chaque bouteille et étiquette de bouteille (le cas échéant).";
$qr_text_013 = "Les numéros de jugement doivent comporter six caractères et ne peuvent pas inclure le caractère ^.";
$qr_text_014 = "En attente de la saisie du code QR numérisé.";
$qr_text_016 = "Besoin d'une application de numérisation de code QR ? Recherchez sur <a class=\"hide-loader\" href=\"https://play.google.com/store/search?q=qr%20code%20scanner&c=apps&hl=en\" target=\"_blank\">Google Play</a> (Android) ou <a class=\"hide-loader\" href=\"https://itunes.apple.com/store/\" target=\"_blank\">iTunes</a> (iOS).";

/**
 * ------------------------------------------------------------------------
 * Registration
 * ------------------------------------------------------------------------
 */
$register_text_000 = "Êtes-vous bénévole ? ";
$register_text_001 = "Êtes-vous ";
$register_text_002 = "Les inscriptions sont closes.";
$register_text_003 = "Merci pour votre intérêt.";
$register_text_004 = "Les informations que vous fournissez au-delà de votre prénom, nom de famille et club sont strictement utilisées à des fins de conservation des dossiers et de contact.";
$register_text_005 = "Une condition d'entrée dans la compétition est de fournir ces informations. Votre nom et votre club peuvent être affichés si l'une de vos participations est récompensée, mais aucune autre information ne sera rendue publique.";
$register_text_006 = "Rappel : Vous n'êtes autorisé à entrer que dans une région et une fois que vous vous êtes inscrit à un emplacement, vous ne pourrez PAS le changer.";
$register_text_007 = "Inscription";
$register_text_008 = "rapide";
$register_text_009 = "d'un juge";
$register_text_010 = "d'un participant";
$register_text_011 = "Pour vous inscrire, créez votre compte en remplissant les champs ci-dessous.";
$register_text_012 = "Ajoutez rapidement un participant au pool de juges/intendants de la compétition. Une adresse et un numéro de téléphone factices seront utilisés, et un mot de passe par défaut <em>bcoem</em> sera attribué à chaque participant ajouté via cet écran.";
$register_text_013 = "L'inscription à cette compétition se fait entièrement en ligne.";
$register_text_014 = "Pour ajouter vos participations et/ou indiquer que vous êtes prêt à juger ou à servir d'intendant (les juges et intendants peuvent également ajouter des participations), vous devrez créer un compte sur notre système.";
$register_text_015 = "Votre adresse e-mail sera votre nom d'utilisateur et sera utilisée comme moyen de diffusion d'informations par le personnel de la compétition. Assurez-vous qu'elle est correcte.";
$register_text_016 = "Une fois inscrit, vous pourrez continuer le processus d'inscription.";
$register_text_017 = "Chaque participation que vous ajoutez sera automatiquement attribuée un numéro par le système.";
$register_text_018 = "Choisissez-en un. Cette question sera utilisée pour vérifier votre identité si vous oubliez votre mot de passe.";
$register_text_019 = "Veuillez fournir une adresse e-mail.";
$register_text_020 = "Les adresses e-mail que vous avez saisies ne correspondent pas.";
$register_text_021 = "Les adresses e-mail servent de noms d'utilisateur.";
$register_text_022 = "Veuillez fournir un mot de passe.";
$register_text_023 = "Veuillez fournir une réponse à votre question de sécurité.";
$register_text_024 = "Faites en sorte que votre réponse de sécurité soit facilement mémorisable pour vous uniquement !";
$register_text_025 = "Veuillez fournir un prénom.";
$register_text_026 = "Veuillez fournir un nom de famille.";
$register_text_027 = "d'un intendant";
$register_text_028 = "Veuillez fournir une adresse.";
$register_text_029 = "Veuillez fournir une ville.";
$register_text_030 = "Veuillez fournir un État ou une province.";
$register_text_031 = "Veuillez fournir un code postal.";
$register_text_032 = "Veuillez fournir un numéro de téléphone principal.";
$register_text_033 = "Seuls les membres de l'American Homebrewers Association sont éligibles pour une opportunité de Great American Beer Festival Pro-Am.";
$register_text_034 = "Pour vous inscrire, vous devez cocher la case indiquant que vous acceptez la déclaration de renonciation.";

/**
 * ------------------------------------------------------------------------
 * Sidebar
 * ------------------------------------------------------------------------
 */
$sidebar_text_000 = "Inscriptions pour les juges ou les intendants acceptées";
$sidebar_text_001 = "Inscriptions pour les intendants acceptées";
$sidebar_text_002 = "Inscriptions pour les juges";
$sidebar_text_003 = "Les inscriptions ne sont plus acceptées. La limite des juges et des intendants a été atteinte.";
$sidebar_text_004 = "jusqu'à";
$sidebar_text_005 = "Inscriptions de comptes acceptées";
$sidebar_text_006 = "est ouvert uniquement aux juges ou intendants";
$sidebar_text_007 = "est ouvert uniquement aux intendants";
$sidebar_text_008 = "est ouvert uniquement aux juges";
$sidebar_text_009 = "Inscriptions aux participations acceptées";
$sidebar_text_010 = "La limite de paiement des inscriptions à la compétition a été atteinte.";
$sidebar_text_011 = "La limite d'inscription à la compétition a été atteinte.";
$sidebar_text_012 = "Voir votre liste de participations.";
$sidebar_text_013 = "Sélectionnez ici pour payer vos frais.";
$sidebar_text_014 = "Les frais d'inscription n'incluent pas les participations non confirmées.";
$sidebar_text_015 = "Vous avez des participations non confirmées - une action est nécessaire pour les confirmer.";
$sidebar_text_016 = "Voir votre liste de participations.";
$sidebar_text_017 = "Il vous reste";
$sidebar_text_018 = "avant d'atteindre la limite de";
$sidebar_text_019 = "par participant";
$sidebar_text_020 = "Vous avez atteint la limite de";
$sidebar_text_021 = "dans cette compétition";
$sidebar_text_022 = "Bouteilles d'inscription acceptées à";
$sidebar_text_023 = "l'emplacement d'expédition";
$sidebar_text_024 = "Les dates de jugement de la compétition restent à déterminer. Veuillez revenir plus tard.";
$sidebar_text_025 = "ont été ajoutées au système à partir du";

/**
 * ------------------------------------------------------------------------
 * Styles
 * ------------------------------------------------------------------------
 */
$styles_entry_text_07C = "L'entrant doit préciser si la participation est une Munich Kellerbier (pâle, basée sur le Helles) ou une Franconian Kellerbier (ambre, basée sur le Marzen). L'entrant peut spécifier un autre type de Kellerbier basé sur d'autres styles de base tels que le Pils, le Bock, le Schwarzbier, mais doit fournir une description du style pour les juges.";
$styles_entry_text_09A = "L'entrant doit préciser s'il s'agit d'une variante pâle ou foncée.";
$styles_entry_text_10C = "L'entrant doit préciser s'il s'agit d'une variante pâle ou foncée.";
$styles_entry_text_21B = "L'entrant doit spécifier une puissance (session : 3,0-5,0 %, standard : 5,0-7,5 %, double : 7,5-9,5 %) ; en l'absence de spécification de la puissance, la catégorie standard sera présumée. L'entrant doit spécifier le type spécifique de Specialty IPA à partir de la bibliothèque des types connus répertoriés dans les directives de style, ou tel que modifié par le site web du BJCP ; ou l'entrant doit décrire le type de Specialty IPA et ses caractéristiques clés dans un commentaire afin que les juges sachent à quoi s'attendre. Les participants peuvent spécifier les variétés spécifiques de houblon utilisées, si les participants estiment que les juges ne reconnaîtront pas les caractéristiques variétales des houblons plus récents. Les participants peuvent spécifier une combinaison de types IPA définis (par exemple, Black Rye IPA) sans fournir de descriptions supplémentaires. Les participants peuvent utiliser cette catégorie pour une version de force différente d'une IPA définie par sa propre sous-catégorie BJCP (par exemple, American ou English IPA de force de session) - sauf si une sous-catégorie BJCP existante existe déjà pour ce style (par exemple, double [American] IPA). Types actuellement définis : Black IPA, Brown IPA, White IPA, Rye IPA, Belgian IPA, Red IPA.";
$styles_entry_text_23F = "Le type de fruit utilisé doit être spécifié. Le brasseur doit déclarer un niveau de carbonatation (faible, moyen, élevé) et un niveau de douceur (faible/aucun, moyen, élevé).";
$styles_entry_text_24C = "L'entrant doit préciser blond, ambré ou brun bière de garde. Si aucune couleur n'est spécifiée, le juge devrait essayer de juger en fonction de l'observation initiale, en s'attendant à une saveur maltée et un équilibre qui correspond à la couleur.";
$styles_entry_text_25B = "L'entrant doit préciser la puissance (table, standard, super) et la couleur (pâle, foncée).";
$styles_entry_text_27A = "L'entrant doit soit spécifier un style avec une description fournie par le BJCP, soit fournir une description similaire pour les juges d'un style différent. Si une bière est enregistrée avec seulement un nom de style et aucune description, il est très peu probable que les juges sachent comment la juger. Exemples actuellement définis : Gose, Piwo Grodziskie, Lichtenhainer, Roggenbier, Sahti, Kentucky Common, Pre-Prohibition Lager, Pre-Prohibition Porter, London Brown Ale.";
$styles_entry_text_28A = "L'entrant doit préciser soit un style de base de bière (style BJCP classique, ou une famille de styles génériques) ou fournir une description des ingrédients/spécifications/caractère souhaité. L'entrant doit préciser si une fermentation 100 % Brett a été réalisée. L'entrant peut spécifier les souches de Brettanomyces utilisées, ainsi qu'une brève description de son caractère.";
$styles_entry_text_28B = "L'entrant doit préciser une description de la bière, en identifiant la levure/les bactéries utilisées et soit un style de base, soit les ingrédients/spécifications/caractère souhaité de la bière.";
$styles_entry_text_28C = "L'entrant doit préciser le type de fruit, d'épice, d'herbe ou de bois utilisé. L'entrant doit préciser une description de la bière, en identifiant la levure/les bactéries utilisées et soit un style de base, soit les ingrédients/spécifications/caractère souhaité de la bière. Une description générale de la nature spéciale de la bière peut couvrir tous les éléments requis.";
$styles_entry_text_29A = "L'entrant doit préciser un style de base ; le style déclaré n'a pas besoin d'être un style classique. L'entrant doit spécifier le type de fruit utilisé. Les bières aigres aux fruits qui ne sont pas des lambics doivent être enregistrées dans la catégorie American Wild Ale.";
$styles_entry_text_29B = "L'entrant doit préciser un style de base ; le style déclaré n'a pas besoin d'être un style classique. L'entrant doit préciser le type de fruit et les épices, les herbes ou les légumes (EHL) utilisés ; les ingrédients EHL individuels n'ont pas besoin d'être spécifiés s'il s'agit d'un mélange d'épices bien connu (par exemple, épices pour tarte aux pommes).";
$styles_entry_text_29C = "L'entrant doit préciser un style de base ; le style déclaré n'a pas besoin d'être un style classique. L'entrant doit préciser le type de fruit utilisé. L'entrant doit préciser le type de sucre fermentescible supplémentaire ou de processus spécial utilisé.";
$styles_entry_text_30A = "L'entrant doit préciser un style de base ; le style déclaré n'a pas besoin d'être un style classique. L'entrant doit préciser le type d'épices, d'herbes ou de légumes utilisé ; les ingrédients individuels n'ont pas besoin d'être spécifiés s'il s'agit d'un mélange d'épices bien connu (par exemple, épices pour tarte aux pommes).";
$styles_entry_text_30B = "L'entrant doit préciser un style de base ; le style déclaré n'a pas besoin d'être un style classique. L'entrant doit préciser le type d'épices, d'herbes ou de légumes utilisé ; les ingrédients individuels n'ont pas besoin d'être spécifiés s'il s'agit d'un mélange d'épices bien connu (par exemple, épices pour tarte à la citrouille). La bière doit contenir des épices, et peut contenir des légumes et/ou du sucre.";
$styles_entry_text_30C = "L'entrant doit préciser un style de base ; le style déclaré n'a pas besoin d'être un style classique. L'entrant doit préciser le type d'épices, de sucres, de fruits ou de fermentables supplémentaires utilisés ; les ingrédients individuels n'ont pas besoin d'être spécifiés s'il s'agit d'un mélange d'épices bien connu (par exemple, épices pour vin chaud).";
$styles_entry_text_31A = "L'entrant doit préciser un style de base ; le style déclaré n'a pas besoin d'être un style classique. L'entrant doit préciser le type de céréale alternative utilisé.";
$styles_entry_text_31B = "L'entrant doit préciser un style de base ; le style de base n'a pas besoin d'être un style classique. L'entrant doit préciser le type de sucre utilisé.";
$styles_entry_text_32A = "L'entrant doit préciser une bière de base du style classique. L'entrant doit préciser le type de bois ou de fumée si un caractère de fumée variétal est perceptible.";
$styles_entry_text_32B = "L'entrant doit préciser un style de bière de base ; la bière de base n'a pas besoin d'être un style classique. L'entrant doit préciser le type de bois ou de fumée si un caractère de fumée variétal est perceptible. L'entrant doit préciser les ingrédients ou les processus supplémentaires qui font de cette bière fumée une bière spéciale.";
$styles_entry_text_33A = "L'entrant doit préciser le type de bois utilisé et le niveau de carbonisation (si brûlé). L'entrant doit préciser le style de base ; le style de base peut être soit un style BJCP classique (c'est-à-dire une sous-catégorie nommée) soit un type générique de bière (par exemple, porter, brown ale). Si un bois inhabituel a été utilisé, l'entrant doit fournir une brève description des aspects sensoriels que le bois ajoute à la bière.";
$styles_entry_text_33B = "L'entrant doit préciser le caractère d'alcool supplémentaire, avec des informations sur le fût si cela est pertinent pour le profil de saveur final. L'entrant doit préciser le style de base ; le style de base peut être soit un style BJCP classique (c'est-à-dire une sous-catégorie nommée) soit un type générique de bière (par exemple, porter, brown ale). Si un bois ou un ingrédient inhabituel a été utilisé, l'entrant doit fournir une brève description des aspects sensoriels que les ingrédients ajoutent à la bière.";
$styles_entry_text_34A = "L'entrant doit préciser le nom de la bière commerciale clonée, les spécifications (statistiques vitales) de la bière et soit une brève description sensorielle soit une liste des ingrédients utilisés pour fabriquer la bière. Sans ces informations, les juges qui ne sont pas familiers avec la bière n'auront aucune base de comparaison.";
$styles_entry_text_34B = "L'entrant doit préciser les styles qui sont mélangés. L'entrant peut fournir une description supplémentaire du profil sensoriel de la bière ou des statistiques vitales de la bière résultante.";
$styles_entry_text_34C = "L'entrant doit préciser la nature spéciale de la bière expérimentale, y compris les ingrédients ou les processus spéciaux qui la rendent inappropriée ailleurs dans les directives. L'entrant doit fournir des statistiques vitales pour la bière, soit une brève description sensorielle soit une liste des ingrédients utilisés pour fabriquer la bière. Sans ces informations, les juges n'auront aucune base de comparaison.";
$styles_entry_text_M1A = "Instructions d'inscription : les participants doivent préciser le niveau de carbonatation et la puissance. La douceur est présumée être SECHE dans cette catégorie. Les participants peuvent spécifier les variétés de miel.";
$styles_entry_text_M1B = "Les participants doivent préciser le niveau de carbonatation et la puissance. La douceur est présumée être SEMI-DOUCE dans cette catégorie. Les participants PEUVENT spécifier les variétés de miel.";
$styles_entry_text_M1C = "Les participants DOIVENT préciser le niveau de carbonatation et la puissance. La douceur est présumée être DOUCE dans cette catégorie. Les participants PEUVENT spécifier les variétés de miel.";
$styles_entry_text_M2A = "Les participants doivent préciser le niveau de carbonatation, la puissance et la douceur. Les participants peuvent spécifier les variétés de pommes utilisées ; si spécifiées, un caractère variétal sera attendu. Les produits avec une proportion relativement faible de miel doivent être inscrits dans la catégorie Cidre de spécialité. Un cyser épicé doit être inscrit comme un Mead aux Fruits et Épices. Un cyser avec d'autres fruits doit être inscrit comme un Mélo-mel. Un cyser avec des ingrédients supplémentaires doit être inscrit comme un Mead Expérimental.";
$styles_entry_text_M2B = "Les participants doivent préciser le niveau de carbonatation, la puissance et la douceur. Les participants peuvent spécifier les variétés de raisin utilisées ; si spécifiées, un caractère variétal sera attendu. Un pyment épicé (hippocras) doit être inscrit comme un Mead aux Fruits et Épices. Un pyment fait avec d'autres fruits doit être inscrit comme un Mélo-mel. Un pyment avec d'autres ingrédients doit être inscrit comme un Mead Expérimental.";
$styles_entry_text_M2C = "Les participants doivent préciser le niveau de carbonatation, la puissance et la douceur. Les participants doivent spécifier les variétés de fruits utilisées. Un hydromel fait à partir de baies et de fruits non-baies (y compris les pommes et les raisins) doit être inscrit comme un Mélo-mel. Un hydromel aux baies épicé doit être inscrit comme un Mead aux Fruits et Épices. Un hydromel aux baies contenant d'autres ingrédients doit être inscrit comme un Mead Expérimental.";
$styles_entry_text_M2D = "Les participants doivent préciser le niveau de carbonatation, la puissance et la douceur. Les participants doivent spécifier les variétés de fruits utilisées. Un hydromel aux fruits à noyau épicé doit être inscrit comme un Mead aux Fruits et Épices. Un hydromel aux fruits à noyau contenant des fruits non à noyau doit être inscrit comme un Mélo-mel. Un hydromel aux fruits à noyau contenant d'autres ingrédients doit être inscrit comme un Mead Expérimental.";
$styles_entry_text_M2E = "Les participants doivent préciser le niveau de carbonatation, la puissance et la douceur. Les participants doivent spécifier les variétés de fruits utilisées. Un mélo-mel épicé doit être inscrit comme un Mead aux Fruits et Épices. Un mélo-mel contenant d'autres ingrédients doit être inscrit comme un Mead Expérimental. Les mélo-mels à base de pommes ou de raisins comme seule source de fruits doivent être inscrits comme Cysers et Pyments, respectivement. Les mélo-mels aux pommes ou aux raisins, plus d'autres fruits, doivent être inscrits dans cette catégorie, et non comme expérimentaux.";
$styles_entry_text_M3A = "Les participants doivent préciser le niveau de carbonatation, la puissance et la douceur. Les participants doivent spécifier les types d'épices utilisées (bien que les mélanges d'épices bien connus puissent être désignés par leur nom commun, comme les épices pour tarte aux pommes). Les participants doivent spécifier les types de fruits utilisés. Si seuls des mélanges d'épices sont utilisés, inscrivez comme un Mead aux Épices, Herbes ou Légumes. Si seuls des mélanges de fruits sont utilisés, inscrivez comme un Mélo-mel. Si d'autres types d'ingrédients sont utilisés, inscrivez comme un Mead Expérimental.";
$styles_entry_text_M3B = "Les participants DOIVENT préciser le niveau de carbonatation, la puissance et la douceur. Les participants PEUVENT spécifier les variétés de miel utilisées. Les participants DOIVENT préciser les types d'épices utilisées (bien que les mélanges d'épices bien connus puissent être désignés par leur nom commun, comme les épices pour tarte aux pommes).";
$styles_entry_text_M4A = "Les participants DOIVENT préciser le niveau de carbonatation, la puissance et la douceur. Les participants PEUVENT spécifier les variétés de miel utilisées. Les participants PEUVENT spécifier le style de base de la bière ou les types de malt utilisés. Les produits avec une proportion relativement faible de miel doivent être inscrits dans la catégorie Bière Épicée comme une Bière au Miel.";
$styles_entry_text_M4B = "Les participants DOIVENT préciser le niveau de carbonatation, la puissance et la douceur. Les participants PEUVENT spécifier les variétés de miel utilisées. Les participants DOIVENT préciser la nature spéciale du hydromel, en fournissant une description du hydromel pour les juges si aucune description n'est disponible auprès du BJCP.";
$styles_entry_text_M4C = "Les participants DOIVENT préciser le niveau de carbonatation, la puissance et la douceur. Les participants PEUVENT spécifier les variétés de miel utilisées. Les participants DOIVENT préciser la nature spéciale du hydromel, qu'il s'agisse d'une combinaison de styles existants, d'un hydromel expérimental ou d'une autre création. Tout ingrédient spécial qui confère un caractère identifiable PEUT être déclaré.";
$styles_entry_text_C1E = "Les participants DOIVENT préciser le niveau de carbonatation (3 niveaux). Les participants DOIVENT préciser la douceur (5 catégories). Les participants DOIVENT indiquer la variété de poire(s) utilisée(s).";
$styles_entry_text_C2A = "Les participants DOIVENT préciser si le cidre a été fermenté ou vieilli en fût. Les participants DOIVENT préciser le niveau de carbonatation (3 niveaux). Les participants DOIVENT préciser la douceur (5 niveaux).";
$styles_entry_text_C2B = "Les participants DOIVENT préciser le niveau de carbonatation (3 niveaux). Les participants DOIVENT préciser la douceur (5 catégories). Les participants DOIVENT préciser tous les fruits et/ou jus de fruits ajoutés.";
$styles_entry_text_C2C = "Les participants DOIVENT préciser le niveau de carbonatation (3 niveaux). Les participants DOIVENT préciser la douceur (5 niveaux).";
$styles_entry_text_C2D = "Les participants DOIVENT préciser la densité initiale, la densité finale ou le sucre résiduel, et le taux d'alcool. Les participants DOIVENT préciser le niveau de carbonatation (3 niveaux).";
$styles_entry_text_C2E = "Les participants DOIVENT préciser tous les ingrédients. Les participants DOIVENT préciser le niveau de carbonatation (3 niveaux). Les participants DOIVENT préciser la douceur (5 niveaux).";
$styles_entry_text_C2F = "Les participants DOIVENT préciser tous les ingrédients. Les participants DOIVENT préciser le niveau de carbonatation (3 niveaux). Les participants DOIVENT préciser la douceur (5 niveaux).";

/**
 * ------------------------------------------------------------------------
 * User Edit Email
 * ------------------------------------------------------------------------
 */
$user_text_000 = "A new email address is required and must be in valid form.";
$user_text_001 = "Enter the old password.";
$user_text_002 = "Enter the new password.";
$user_text_003 = "Please check this box if you wish to proceed with changing your email address.";

/**
 * ------------------------------------------------------------------------
 * Volunteers
 * ------------------------------------------------------------------------
 */
$volunteers_text_000 = "If you have registered,";
$volunteers_text_001 = "and then choose <em>Edit Account</em> from the My Account menu indicated by the";
$volunteers_text_002 = "icon on the top menu";
$volunteers_text_003 = "and";
$volunteers_text_004 = "If you have <em>not</em> registered and are willing to be a judge or steward, please register";
$volunteers_text_005 = "Since you have already registered,";
$volunteers_text_006 = "access your account";
$volunteers_text_007 = "to see if you have volunteered to be a judge or steward";
$volunteers_text_008 = "If you are willing to judge or steward, please return to register on or after";
$volunteers_text_009 = "If you would like to volunteer to be a competition staff member, please register or update your account to indicate that you wish to be a part of the competition staff.";
$volunteers_text_010 = "";

/**
 * ------------------------------------------------------------------------
 * Login
 * ------------------------------------------------------------------------
 */
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
$login_text_014 = "select here to resend it to";
$login_text_015 = "If you can't remember the answer to your security question, contact a competition official or site administrator.";
$login_text_016 = "Get it emailed to";

/**
 * ------------------------------------------------------------------------
 * Winners
 * ------------------------------------------------------------------------
 */
$winners_text_000 = "No winners have been entered for this table. Please check back later.";
$winners_text_001 = "Winning entries have not been posted yet. Please check back later.";
$winners_text_002 = "Your chosen award structure is to award places by table. Select the award places for the table as a whole below.";
$winners_text_003 = "Your chosen award structure is to award places by category. Select the award places for each overall category below (there may be more than one at this table).";
$winners_text_004 = "Your chosen award structure is to award places by subcategory. Select the award places for each subcategory below (there may be more than one at this table).";

/**
 * ------------------------------------------------------------------------
 * Output
 * ------------------------------------------------------------------------
 */
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

/**
 * ------------------------------------------------------------------------
 * Maintenance
 * ------------------------------------------------------------------------
 */
$maintenance_text_000 = "The site administrator has taken the site offline to undergo maintenance.";
$maintenance_text_001 = "Please check back later.";

/**
 * ------------------------------------------------------------------------
 * Version 2.1.10-2.1.13 Additions
 * ------------------------------------------------------------------------
 */
$label_entry_numbers = "Numéro(s) d'inscription"; // Pour l'e-mail PayPal IPN
$label_status = "Statut"; // Pour l'e-mail PayPal IPN
$label_transaction_id = "ID de transaction"; // Pour l'e-mail PayPal IPN
$label_organization = "Organisation";
$label_ttb = "Numéro TTB";
$label_username = "Nom d'utilisateur";
$label_from = "De"; // Pour les en-têtes d'e-mail
$label_to = "À"; // Pour les en-têtes d'e-mail
$label_varies = "Varie";
$label_styles_accepted = "Styles acceptés";
$label_judging_styles = "Styles de jugement";
$label_select_club = "Sélectionner ou rechercher votre club";
$label_select_style = "Sélectionner ou rechercher le style de votre inscription";
$label_select_country = "Sélectionner ou rechercher votre pays";
$label_select_dropoff = "Sélectionner le mode de livraison de votre inscription";
$label_club_enter = "Entrer le nom du club";
$label_secret_05 = "Quel est le nom de jeune fille de votre grand-mère maternelle ?";
$label_secret_06 = "Quel était le prénom de votre premier petit ami ou de votre première petite amie ?";
$label_secret_07 = "Quelle était la marque et le modèle de votre première voiture ?";
$label_secret_08 = "Quel était le nom de famille de votre enseignant de troisième année ?";
$label_secret_09 = "Dans quelle ville ou village avez-vous rencontré votre conjoint(e) ?";
$label_secret_10 = "Quel était le prénom de votre meilleur(e) ami(e) en sixième année ?";
$label_secret_11 = "Quel est le nom de votre artiste musical ou groupe préféré ?";
$label_secret_12 = "Quel était votre surnom d'enfance ?";
$label_secret_13 = "Quel est le nom de famille de l'enseignant qui vous a donné votre première note insuffisante ?";
$label_secret_14 = "Quel est le nom de votre ami d'enfance préféré ?";
$label_secret_15 = "Dans quelle ville ou village vos parents se sont-ils rencontrés ?";
$label_secret_16 = "Quel était le numéro de téléphone de votre enfance que vous vous souvenez le plus, y compris l'indicatif régional ?";
$label_secret_17 = "Quel était votre lieu préféré à visiter lorsque vous étiez enfant ?";
$label_secret_18 = "Où étiez-vous lors de votre premier baiser ?";
$label_secret_19 = "Dans quelle ville ou village avez-vous eu votre premier emploi ?";
$label_secret_20 = "Dans quelle ville ou village étiez-vous le jour de l'an 2000 ?";
$label_secret_21 = "Quel est le nom d'une université à laquelle vous avez postulé mais que vous n'avez pas fréquentée ?";
$label_secret_22 = "Quel est le prénom du garçon ou de la fille que vous avez embrassé(e) pour la première fois ?";
$label_secret_23 = "Quel était le nom de votre premier animal en peluche, de votre première poupée ou de votre premier action figure ?";
$label_secret_24 = "Dans quelle ville ou village avez-vous rencontré votre conjoint(e) ?";
$label_secret_25 = "Dans quelle rue viviez-vous en première année ?";
$label_secret_26 = "Quelle est la vitesse de déplacement de base d'une hirondelle non chargée ?";
$label_secret_27 = "Quel est le nom de votre émission de télévision préférée qui a été annulée ?";
$label_pro = "Professionnel";
$label_amateur = "Amateur";
$label_hosted = "Hébergé";
$label_edition = "Édition";
$label_pro_comp_edition = "Édition de compétition professionnelle";
$label_amateur_comp_edition = "Édition de compétition amateur";
$label_optional_info = "Informations facultatives";
$label_or = "Ou";
$label_admin_payments = "Paiements";
$label_payer = "Payeur";
$label_pay_with_paypal = "Payer avec PayPal";
$label_submit = "Soumettre";
$label_id_verification_question = "Question de vérification de l'identité";
$label_id_verification_answer = "Réponse de vérification de l'identité";
$label_server = "Serveur";
$label_password_reset = "Réinitialisation du mot de passe";
$label_id_verification_request = "Demande de vérification de l'identité";
$label_new_password = "Nouveau mot de passe";
$label_confirm_password = "Confirmer le mot de passe";
$label_with_token = "Avec jeton";
$label_password_strength = "Force du mot de passe";
$label_entry_shipping = "Expédition de l'inscription";
$label_jump_to = "Aller à...";
$label_top = "Haut";
$label_bjcp_cider = "Juge de cidre certifié";
$header_text_112 = "Vous n'avez pas les privilèges d'accès suffisants pour effectuer cette action.";
$header_text_113 = "Vous ne pouvez modifier que les informations de votre propre compte.";
$header_text_114 = "En tant qu'administrateur, vous pouvez modifier les informations du compte d'un utilisateur via Administration > Inscriptions et Participants > Gérer les Participants.";
$header_text_115 = "Les résultats ont été publiés.";
$header_text_116 = "Si vous ne recevez pas l'e-mail dans un délai raisonnable, vérifiez le dossier SPAM de votre compte e-mail. S'il n'est pas là, contactez un responsable de la compétition ou un administrateur du site pour réinitialiser votre mot de passe.";
$alert_text_082 = "Étant donné que vous vous êtes inscrit en tant que juge ou steward, vous n'êtes pas autorisé à ajouter des inscriptions à votre compte. Seuls les représentants d'une organisation peuvent ajouter des inscriptions à leur compte.";
$alert_text_083 = "L'ajout et la modification des inscriptions ne sont pas disponibles.";
$alert_text_084 = "En tant qu'administrateur, vous pouvez ajouter une inscription au compte d'une organisation en utilisant le menu déroulant \"Ajouter une inscription pour...\" sur la page Admin > Inscriptions et Participants > Gérer les Inscriptions.";
$alert_text_085 = "Vous ne pourrez pas imprimer la documentation d'une inscription (étiquettes de bouteilles, etc.) tant que le paiement n'aura pas été confirmé et marqué comme \"payé\" ci-dessous.";
$brew_text_027 = "Ce style de l'Association des brasseurs nécessite une déclaration du brasseur concernant la nature spéciale du produit. Consultez les <a href=\"https://www.brewersassociation.org/resources/brewers-association-beer-style-guidelines/\" target=\"_blank\">Directives de style de la BA</a> pour des conseils spécifiques.";
$brew_text_028 = "***NON REQUIS*** Ajoutez ici des informations détaillées dans les directives de style en tant que caractéristique que vous POUVEZ déclarer.";
$brew_text_029 = "Modification administrateur désactivée. Votre profil est considéré comme un profil personnel et non comme un profil d'organisation, et donc, non éligible pour ajouter des inscriptions. Pour ajouter une inscription pour une organisation, accédez à la liste des Inscriptions à gérer et choisissez une organisation dans le menu déroulant \"Ajouter une inscription pour...\".";
$brew_text_030 = "lait / lactose";
$brew_text_031 = "œufs";
$brew_text_032 = "poisson";
$brew_text_033 = "crustacés";
$brew_text_034 = "fruits à coque";
$brew_text_035 = "arachides";
$brew_text_036 = "blé";
$brew_text_037 = "soja";
$brew_text_038 = "Cette inscription contient-elle des allergènes alimentaires potentiels ? Les allergènes alimentaires courants incluent le lait (y compris le lactose), les œufs, le poisson, les crustacés, les fruits à coque, les arachides, le blé, le soja, etc.";
$brew_text_039 = "Veuillez spécifier tout(s) allergène(s) potentiel(s).";
$brewer_text_022 = "Vous pourrez identifier un co-brasseur lors de l'ajout de vos inscriptions.";
$brewer_text_023 = "Sélectionnez \"Aucun\" si vous n'êtes pas affilié à un club. Sélectionnez \"Autre\" si votre club ne figure pas dans la liste - <strong>assurez-vous d'utiliser la barre de recherche</strong>.";
$brewer_text_024 = "Veuillez fournir votre prénom.";
$brewer_text_025 = "Veuillez fournir votre nom de famille.";
$brewer_text_026 = "Veuillez fournir votre numéro de téléphone.";
$brewer_text_027 = "Veuillez fournir votre adresse.";
$brewer_text_028 = "Veuillez fournir votre ville.";
$brewer_text_029 = "Veuillez fournir votre État ou province.";
$brewer_text_030 = "Veuillez fournir votre code postal.";
$brewer_text_031 = "Veuillez choisir votre pays.";

$brewer_text_032 = "Veuillez fournir le nom de votre organisation.";
$brewer_text_033 = "Veuillez choisir une question de sécurité.";
$brewer_text_034 = "Veuillez fournir une réponse à votre question de sécurité.";
$brewer_text_035 = "Avez-vous réussi l'examen de juge de cidre BJCP ?";
$entry_info_text_047 = "Si le nom d'un style est hyperlié, il a des exigences d'inscription spécifiques. Sélectionnez ou appuyez sur le nom pour accéder aux styles de l'Association des brasseurs tels qu'ils sont répertoriés sur leur site Web.";
$brewer_entries_text_016 = "Style inscrit NON accepté";
$brewer_entries_text_017 = "Les inscriptions ne seront pas affichées comme reçues tant que le personnel de la compétition ne les aura pas marquées comme telles dans le système. En général, cela se produit APRÈS que toutes les inscriptions ont été collectées auprès de tous les lieux de dépôt et d'expédition et triées.";
$brewer_entries_text_018 = "Vous ne pourrez pas imprimer la documentation de cette inscription (étiquettes de bouteilles, etc.) tant qu'elle n'aura pas été marquée comme payée.";
$brewer_entries_text_019 = "L'impression de la documentation d'inscription n'est pas disponible pour le moment.";
$brewer_entries_text_020 = "La modification des inscriptions n'est pas disponible pour le moment. Si vous souhaitez modifier votre inscription, contactez un responsable de la compétition.";
if (SINGLE) $brewer_info_000 = "Bonjour";
else $brewer_info_000 = "Merci de participer à la";
$brewer_info_001 = "Les détails de votre compte ont été mis à jour pour la dernière fois";
$brewer_info_002 = "Prenez un moment pour <a href=\"#entries\">revoir vos inscriptions</a>";
$brewer_info_003 = "<a href=\"#fees\">payer vos frais d'inscription</a>";
$brewer_info_004 = "par inscription";
$brewer_info_005 = "Une adhésion à l'American Homebrewers Association (AHA) est requise si l'une de vos inscriptions est sélectionnée pour le Great American Beer Festival Pro-Am.";
$brewer_info_006 = "Imprimez les étiquettes d'expédition à attacher à votre/vos boîte(s) de bouteilles.";
$brewer_info_007 = "Imprimer les étiquettes d'expédition";
$brewer_info_008 = "Vous avez déjà été affecté à une table en tant que";
$brewer_info_009 = "Si vous souhaitez modifier votre disponibilité et/ou annuler votre rôle, contactez l'organisateur de la compétition ou le coordinateur des juges.";
$brewer_info_010 = "Vous avez déjà été affecté en tant que";
$brewer_info_011 = "ou";
$brewer_info_012 = "Imprimez les étiquettes de vos feuilles de notation de jugement ";
$pay_text_030 = "En sélectionnant le bouton \"Je comprends\" ci-dessous, vous serez dirigé vers PayPal pour effectuer votre paiement. Une fois que vous avez <strong>terminé</strong> votre paiement, PayPal vous redirigera vers ce site et vous enverra un reçu par e-mail pour la transaction. <strong>Si votre paiement a été réussi, votre statut de paiement sera automatiquement mis à jour. Veuillez noter que vous devrez peut-être attendre quelques minutes pour que le statut de paiement soit mis à jour.</strong> Assurez-vous de rafraîchir la page de paiement ou d'accéder à votre liste d'inscriptions.";
$pay_text_031 = "Sur le point de quitter ce site";
$pay_text_032 = "Aucun paiement n'est nécessaire. Merci !";
$pay_text_033 = "Vous avez des inscriptions impayées. Sélectionnez ou appuyez pour payer vos inscriptions.";
$register_text_035 = "Les informations que vous fournissez au-delà du nom de votre organisation sont strictement destinées à des fins d'enregistrement et de contact.";
$register_text_036 = "Une condition d'inscription à la compétition est de fournir ces informations, y compris l'adresse e-mail et le numéro de téléphone d'une personne de contact. Le nom de votre organisation peut être affiché si l'une de vos inscriptions est primée, mais aucune autre information ne sera rendue publique.";
$register_text_037 = "Confirmation d'inscription";
$register_text_038 = "Un administrateur vous a inscrit à un compte. Voici la confirmation des informations saisies :";
$register_text_039 = "Merci de vous être inscrit. Voici la confirmation des informations que vous avez fournies :";
$register_text_040 = "Si l'une des informations ci-dessus est incorrecte,";
$register_text_041 = "connectez-vous à votre compte";
$register_text_042 = "et apportez les modifications nécessaires. Bonne chance dans la compétition !";
$register_text_043 = "Veuillez ne pas répondre à cet e-mail car il est généré automatiquement. Le compte d'origine n'est pas actif ni surveillé.";
$register_text_044 = "Veuillez fournir un nom d'organisation.";
$register_text_045 = "Fournissez un nom de brasserie, de brewpub, etc. Assurez-vous de vérifier les informations sur la compétition pour les types de boissons acceptées.";
$register_text_046 = "Pour les organisations américaines uniquement.";
$user_text_004 = "Assurez-vous d'utiliser des lettres majuscules et minuscules, des chiffres et des caractères spéciaux pour un mot de passe plus fort.";
$user_text_005 = "Votre adresse e-mail actuelle est";
$login_text_017 = "Envoyez-moi la réponse à ma question de sécurité par e-mail";
$login_text_018 = "Votre nom d'utilisateur (adresse e-mail) est requis.";
$login_text_019 = "Votre mot de passe est requis.";
$login_text_020 = "Le jeton fourni est invalide ou a déjà été utilisé. Veuillez utiliser à nouveau la fonction de réinitialisation de mot de passe pour générer un nouveau jeton de réinitialisation de mot de passe.";
$login_text_021 = "Le jeton fourni a expiré. Veuillez utiliser à nouveau la fonction de réinitialisation de mot de passe pour générer un nouveau jeton de réinitialisation de mot de passe.";
$login_text_022 = "L'adresse e-mail que vous avez saisie n'est pas associée au jeton fourni. Veuillez réessayer.";
$login_text_023 = "Les mots de passe ne correspondent pas. Veuillez réessayer.";
$login_text_024 = "Un mot de passe de confirmation est requis.";
$login_text_025 = "Mot de passe oublié ?";
$login_text_026 = "Saisissez l'adresse e-mail de votre compte et le nouveau mot de passe ci-dessous.";
$login_text_027 = "Votre mot de passe a été réinitialisé avec succès. Vous pouvez maintenant vous connecter avec le nouveau mot de passe.";
$winners_text_005 = "Les gagnants du Best of Show n'ont pas encore été publiés. Veuillez revenir ultérieurement.";
$paypal_response_text_000 = "Votre paiement a été effectué. Les détails de la transaction sont fournis ici pour votre commodité.";
$paypal_response_text_001 = "Veuillez noter que vous recevrez une communication officielle de PayPal à l'adresse e-mail indiquée ci-dessous.";
$paypal_response_text_002 = "Bonne chance dans la compétition !";
$paypal_response_text_003 = "Veuillez ne pas répondre à cet e-mail car il est généré automatiquement. Le compte d'origine n'est pas actif ni surveillé.";
$paypal_response_text_004 = "PayPal a traité votre transaction.";
$paypal_response_text_005 = "Le statut de votre paiement PayPal est :";
$paypal_response_text_006 = "La réponse de Paypal était \"non valide\". Veuillez essayer de faire votre paiement à nouveau.";
$paypal_response_text_007 = "Veuillez contacter l'organisateur de la compétition si vous avez des questions.";
$paypal_response_text_008 = "Paiement PayPal non valide";
$paypal_response_text_009 = "Paiement PayPal";
$pwd_email_reset_text_000 = "Une demande a été faite pour vérifier le compte sur le site";
$pwd_email_reset_text_001 = "en utilisant la fonction d'envoi de courrier électronique de vérification d'identifiant (ID Verification email). Si vous n'avez pas initié cela, veuillez contacter l'organisateur de la compétition.";
$pwd_email_reset_text_002 = "La réponse à la vérification d'identifiant (ID verification answer) est sensible à la casse";
$pwd_email_reset_text_003 = "Une demande a été faite pour changer votre mot de passe sur le site";
$pwd_email_reset_text_004 = ". Si vous n'avez pas initié cela, ne vous inquiétez pas. Votre mot de passe ne peut pas être réinitialisé sans le lien ci-dessous.";
$pwd_email_reset_text_005 = "Pour réinitialiser votre mot de passe, sélectionnez le lien ci-dessous ou copiez/collez-le dans votre navigateur.";
$best_brewer_text_000 = "brasseurs participants";
$best_brewer_text_001 = "HM";
$best_brewer_text_002 = "Les scores et les bris d'égalité ont été appliqués conformément à la <a href=\"#\" data-toggle=\"modal\" data-target=\"#scoreMethod\">méthodologie de notation</a>. Les chiffres affichés sont arrondis au centième. Passez la souris sur l'icône du point d'interrogation (<span class=\"fa fa-question-circle\"></span>) pour obtenir la valeur calculée réelle.";
$best_brewer_text_003 = "Méthodologie de notation";
$best_brewer_text_004 = "Chaque entrée placée reçoit les points suivants :";
$best_brewer_text_005 = "Les bris d'égalité suivants ont été appliqués, par ordre de priorité :";
$best_brewer_text_006 = "Le nombre total le plus élevé de premières, deuxièmes et troisièmes places.";
$best_brewer_text_007 = "Le nombre total le plus élevé de premières, deuxièmes, troisièmes, quatrièmes (le cas échéant) et mentions honorables.";
$best_brewer_text_008 = "Le nombre le plus élevé de premières places.";
$best_brewer_text_009 = "Le nombre le plus faible d'inscriptions.";
$best_brewer_text_010 = "Le score minimum le plus élevé.";
$best_brewer_text_011 = "Le score maximum le plus élevé.";
$best_brewer_text_012 = "La moyenne des scores la plus élevée.";
$best_brewer_text_013 = "Non utilisé.";
$best_brewer_text_014 = "clubs participants";
$dropoff_qualifier_text_001 = "Veuillez prêter attention aux notes fournies pour chaque lieu de dépôt. Il pourrait y avoir des délais plus courts pour certains lieux de dépôt répertoriés, des heures particulières d'acceptation des inscriptions, des personnes spécifiques pour déposer vos inscriptions, etc. <strong class=\"text-danger\">Tous les participants sont responsables de la lecture des informations fournies par les organisateurs pour chaque lieu de dépôt.</strong>";
$brewer_text_036 = "Comme vous avez choisi \"<em>Autre</em>\", assurez-vous que le nom de votre club n'est pas déjà dans notre liste sous une forme similaire.";
$brewer_text_037 = "Par exemple, vous avez peut-être entré l'acronyme de votre club au lieu du nom complet.";
$brewer_text_038 = "Des noms de clubs cohérents entre les utilisateurs sont essentiels si les calculs de \"Meilleur Club\" sont mis en œuvre pour cette compétition.";
$brewer_text_039 = "Le club que vous avez saisi précédemment ne correspond pas à celui de notre liste.";
$brewer_text_040 = "Veuillez choisir dans la liste ou sélectionner <em>Autre</em> et entrer le nom de votre club.";

/**
 * ------------------------------------------------------------------------
 * Version 2.1.13 Additions
 * ------------------------------------------------------------------------
 */
$entry_info_text_048 = "Pour garantir un jugement approprié, le participant doit fournir des informations supplémentaires sur la boisson.";
$entry_info_text_049 = "Pour garantir un jugement approprié, le participant doit fournir le niveau de force de la boisson.";
$entry_info_text_050 = "Pour garantir un jugement approprié, le participant doit fournir le niveau de carbonatation de la boisson.";
$entry_info_text_051 = "Pour garantir un jugement approprié, le participant doit fournir le niveau de douceur de la boisson.";
$entry_info_text_052 = "Si vous entrez dans cette catégorie, le participant doit fournir des informations supplémentaires pour que l'inscription puisse être jugée avec précision. Plus il y a d'informations, mieux c'est.";
$output_text_028 = "Les inscriptions suivantes présentent des allergènes possibles - tels qu'indiqués par les participants.";
$output_text_029 = "Aucun participant n'a fourni d'informations sur les allergènes pour ses inscriptions.";
$label_this_style = "Ce style";
$label_notes = "Notes";
$label_possible_allergens = "Allergènes possibles";
$label_please_choose = "Veuillez choisir";
$label_mead_cider_info = "Informations sur le hydromel/le cidre";

/**
 * ------------------------------------------------------------------------
 * Version 2.1.14 Additions
 * ------------------------------------------------------------------------
 */
$label_winners = "Gagnants";
$label_unconfirmed_entries = "Inscriptions non confirmées";
$label_recipe = "Recette";
$label_view = "Voir";
$label_number_bottles = "Nombre de bouteilles requis par inscription";
$label_pro_am = "Pro-Am";
$pay_text_034 = "La limite des inscriptions payées a été atteinte - les paiements supplémentaires d'inscriptions ne sont pas acceptés.";
$bottle_labels_000 = "Les étiquettes ne peuvent pas être générées pour le moment.";
$bottle_labels_001 = "Assurez-vous de vérifier les règles d'acceptation des inscriptions de la compétition pour des directives spécifiques sur la fixation des étiquettes avant de soumettre !";
$bottle_labels_002 = "En général, du ruban adhésif transparent est utilisé pour les fixer sur le fût de chaque inscription - couvrez complètement l'étiquette.";
$bottle_labels_003 = "En général, un élastique est utilisé pour fixer les étiquettes sur chaque inscription.";
if (isset($_SESSION['jPrefsBottleNum'])) $bottle_labels_004 = "4 étiquettes sont fournies en guise de courtoisie. Cette compétition nécessite ".$_SESSION['jPrefsBottleNum']." bouteilles par inscription. Vous devrez peut-être imprimer plusieurs pages en fonction du nombre de bouteilles requises.";
else $bottle_labels_004 = "4 étiquettes sont fournies en guise de courtoisie. Jetez les étiquettes supplémentaires.";
$bottle_labels_005 = "Si des éléments sont manquants, fermez cette fenêtre et modifiez l'inscription. Vous devrez peut-être imprimer plusieurs pages en fonction du nombre de bouteilles requises.";
$bottle_labels_006 = "Espace réservé à l'usage du personnel de la compétition.";
$bottle_labels_007 = "CE FORMULAIRE DE RECETTE EST UNIQUEMENT POUR VOS ARCHIVES - veuillez NE PAS l'inclure avec votre envoi d'inscription.";
$brew_text_040 = "Il n'est pas nécessaire de spécifier le gluten comme allergène pour les styles de bière ; on suppose qu'il sera présent. Les bières sans gluten doivent être inscrites dans la catégorie Bière sans gluten (BA) ou dans la catégorie Bière à base de céréales alternatives (BJCP). Spécifiez uniquement le gluten comme allergène dans les styles de hydromel ou de cidre si une source fermentescible contient du gluten (par exemple, de l'orge, du blé ou du seigle malté) ou si une levure de brasseur a été utilisée.";
$brewer_text_041 = "Avez-vous déjà obtenu une opportunité Pro-Am pour participer à la prochaine compétition du Great American Beer Festival Pro-Am ?";
$brewer_text_042 = "Si vous avez déjà obtenu une opportunité Pro-Am ou si vous avez fait partie du personnel de brassage de n'importe quelle brasserie, veuillez l'indiquer ici. Cela aidera le personnel de la compétition et les représentants de la brasserie Pro-Am (le cas échéant pour cette compétition) à choisir les inscriptions Pro-Am des brasseurs qui n'en ont pas encore obtenu.";

/**
 * ------------------------------------------------------------------------
 * Version 2.1.15 Additions
 * ------------------------------------------------------------------------
 */
$label_submitting = "Soumission";
$label_additional_info = "Inscriptions avec informations supplémentaires";
$label_working = "Travail en cours";
$output_text_030 = "Veuillez patienter.";
$brewer_entries_text_021 = "Cochez les inscriptions pour imprimer leurs étiquettes de bouteilles. Sélectionnez la case en haut pour cocher ou décocher toutes les cases de la colonne.";
$brewer_entries_text_022 = "Imprimer toutes les étiquettes de bouteilles pour les inscriptions cochées";
$brewer_entries_text_023 = "Les étiquettes de bouteilles s'ouvriront dans un nouvel onglet ou une nouvelle fenêtre.";
$brewer_entries_text_024 = "Imprimer les étiquettes de bouteilles";

/**
 * ------------------------------------------------------------------------
 * Version 2.1.18 Additions
 * ------------------------------------------------------------------------
 */
$output_text_031 = "Appuyez sur Échap pour masquer.";
$styles_entry_text_21X = "Le participant DOIT spécifier une force (session : 3,0-5,0 %, standard : 5,0-7,5 %, double : 7,5-9,5 %).";
$styles_entry_text_PRX4 = "Le participant doit spécifier les types de fruits frais utilisés.";

/**
 * ------------------------------------------------------------------------
 * Version 2.1.19 Additions
 * ------------------------------------------------------------------------
 */
$output_text_032 = "Le nombre d'inscriptions ne reflète que les participants qui ont indiqué un emplacement dans leur profil de compte. Le nombre réel d'inscriptions peut être supérieur ou inférieur.";
$brewer_text_043 = "Ou avez-vous déjà été employé, ou êtes-vous actuellement, au personnel de brassage d'une brasserie ? Cela comprend les postes de brasseur ainsi que les techniciens de laboratoire, l'équipe de la cave, l'équipe de mise en bouteille/mise en boîte, etc. Les employés actuels et anciens du personnel de brassage ne sont pas éligibles pour participer à la compétition du Great American Beer Festival Pro-Am.";
$label_entrant_reg = "Enregistrement de l'entrant";
$sidebar_text_026 = "sont dans le système à partir du";
$label_paid_entries = "Inscriptions payées";

/**
 * ------------------------------------------------------------------------
 * Version 2.2.0 Additions
 * ------------------------------------------------------------------------
 */
/**
 * ------------------------------------------------------------------------
 * Version 2.2.0 Additions
 * ------------------------------------------------------------------------
 */
$alert_text_086 = "Internet Explorer n'est pas pris en charge par BCOE&M - les fonctionnalités et les fonctions ne s'afficheront pas correctement et votre expérience ne sera pas optimale. Veuillez passer à un navigateur plus récent.";
$alert_text_087 = "Pour une expérience optimale et afin que toutes les fonctionnalités s'exécutent correctement, veuillez activer JavaScript pour continuer à utiliser ce site. Sinon, un comportement inattendu se produira.";
$alert_text_088 = "La présentation des prix sera disponible publiquement après la publication des résultats.";
$alert_text_089 = "Les données archivées ne sont pas disponibles.";
$brewer_entries_text_025 = "Afficher ou imprimer les fiches de dégustation des juges";
$brewer_info_013 = "Vous avez été désigné comme juge.";
$brewer_info_014 = "Accédez au tableau de bord de dégustation en utilisant le bouton ci-dessous pour entrer des évaluations des inscriptions qui vous ont été attribuées.";
$contact_text_004 = "Les organisateurs de la compétition n'ont pas spécifié de contacts.";
$label_thank_you = "Merci";
$label_congrats_winners = "Félicitations à tous les lauréats";
$label_placing_entries = "Inscriptions classées";
$label_by_the_numbers = "En chiffres";
$label_launch_pres = "Lancer la présentation des prix";
$label_entrants = "Participants";
$label_judging_dashboard = "Tableau de bord de dégustation";
$label_table_assignments = "Affectations de table";
$label_table = "Tableau";
$label_edit = "Modifier";
$label_add = "Ajouter";
$label_disabled = "Désactivé";
$label_judging_scoresheet = "Fiche de dégustation";
$label_checklist_version = "Version de la liste de contrôle";
$label_classic_version = "Version classique";
$label_structured_version = "Version structurée";
$label_submit_evaluation = "Soumettre l'évaluation";
$label_edit_evaluation = "Modifier l'évaluation";
$label_your_score = "Votre score";
$label_your_assigned_score = "Votre score de consensus saisi";
$label_assigned_score = "Score de consensus";
$label_accepted_score = "Score accepté officiellement";
$label_recorded_scores = "Scores de consensus saisis";
$label_go = "Aller";
$label_go_back = "Retourner";
$label_na = "N/A";
$label_evals_submitted = "Évaluations soumises";
$label_evaluations = "Évaluations des inscriptions";
$label_submitted_by = "Soumis par";
$label_attention = "Attention !";
$label_unassigned_eval = "Évaluations non attribuées";
$label_head_judge = "Juge en chef";
$label_lead_judge = "Juge principal";
$label_mini_bos_judge = "Juge Mini-BOS";
$label_view_my_eval = "Voir mon évaluation";
$label_view_other_judge_eval = "Voir l'évaluation d'un autre juge";
$label_place_awarded = "Place attribuée";
$label_honorable_mention = "Mention honorable";
$label_places_awarded_table = "Places attribuées à cette table";
$label_places_awarded_duplicate = "Places attribuées en double à cette table";
$evaluation_info_000 = "Le pool d'inscriptions pour chacune des tables et des sessions de dégustation qui vous ont été attribuées est détaillé ci-dessous.";
$evaluation_info_001 = "Cette compétition utilise la dégustation en file d'attente. S'il y a plus d'une paire de juges à votre table, évaluez la prochaine inscription dans la file d'attente établie.";
$evaluation_info_002 = "Pour garantir une compétition précise et fluide, vous et vos partenaires juges ne devez ÉVALUER QUE les inscriptions à votre table qui n'ont pas encore été évaluées. Consultez votre organisateur ou le coordinateur des juges si vous avez des questions.";
$evaluation_info_003 = "En attente d'acceptation finale par un administrateur du site.";
$evaluation_info_004 = "Votre score de consensus a été saisi.";
$evaluation_info_005 = "Cette inscription <strong>n'est pas</strong> partie de votre session attribuée.";
$evaluation_info_006 = "Modifiez au besoin.";
$evaluation_info_007 = "Pour enregistrer une évaluation, choisissez parmi les inscriptions suivantes avec un bouton bleu &quot;Ajouter&quot;.";
$evaluation_info_008 = "Pour enregistrer votre évaluation, sélectionnez le bouton Ajouter correspondant à une inscription. Seules les tables des sessions de dégustation passées et actuelles sont disponibles.";
$evaluation_info_009 = "Vous avez été désigné comme juge, mais vous n'avez été attribué à aucune table ou session dans le système. Veuillez vérifier auprès de l'organisateur ou du coordinateur des juges.";
$evaluation_info_010 = "Cette inscription fait partie de votre session attribuée.";
$evaluation_info_011 = "Ajoutez une évaluation pour une inscription qui ne vous a pas été attribuée.";
$evaluation_info_012 = "Utilisez uniquement lorsque vous êtes invité à évaluer une inscription qui ne vous a pas été attribuée dans le système.";
$evaluation_info_013 = "Inscription introuvable.";
$evaluation_info_014 = "Veuillez vérifier le numéro d'inscription à six caractères et réessayer.";
$evaluation_info_015 = "Assurez-vous que le numéro comporte 6 chiffres.";
$evaluation_info_016 = "Aucune évaluation n'a été soumise.";
$evaluation_info_017 = "Les scores de consensus saisis par les juges ne correspondent pas.";
$evaluation_info_018 = "La vérification est nécessaire pour les inscriptions suivantes :";
$evaluation_info_019 = "Les inscriptions suivantes n'ont qu'une seule évaluation soumise :";
$evaluation_info_020 = "Votre tableau de bord de dégustation sera disponible"; // La ponctuation a été omise délibérément
$evaluation_info_021 = "pour ajouter des évaluations aux inscriptions qui vous ont été attribuées"; // La ponctuation a été omise délibérément
$evaluation_info_022 = "La dégustation et la soumission des évaluations sont closes.";
$evaluation_info_023 = "Si vous avez des questions, contactez l'organisateur de la compétition ou le coordinateur des juges.";
$evaluation_info_024 = "Vous avez été attribué aux tables suivantes. <strong>Les listes d'inscriptions pour chaque table ne seront visibles que pour les sessions de dégustation passées et actuelles.</strong>";
$evaluation_info_025 = "Juges attribués à cette table :";
$evaluation_info_026 = "Tous les scores de consensus saisis par les juges correspondent.";
$evaluation_info_027 = "Inscriptions que vous avez terminées, ou un administrateur a saisi en votre nom, qui ne vous ont pas été attribuées dans le système.";
$evaluation_info_028 = "La session de dégustation est terminée.";
$evaluation_info_029 = "Des places en double ont été attribuées aux tables suivantes :";
$evaluation_info_030 = "Les administrateurs devront saisir toutes les inscriptions classées restantes.";
$evaluation_info_031 = "évaluations ont été ajoutées par des juges";
$evaluation_info_032 = "Plusieurs évaluations pour une seule inscription ont été soumises par un juge.";
$evaluation_info_033 = "Bien que cela soit une occurrence inhabituelle, une évaluation en double peut être soumise en raison de problèmes de connectivité, etc. Une seule évaluation enregistrée pour chaque juge devrait être officiellement acceptée - toutes les autres devraient être supprimées pour éviter toute confusion pour les participants.";
$evaluation_info_034 = "Lors de l'évaluation de styles de type spécialité, utilisez cette ligne pour commenter les caractéristiques uniques, telles que les fruits, les épices, les fermentables, l'acidité, etc.";
$evaluation_info_035 = "Fournissez des commentaires sur le style, la recette, le processus et le plaisir de dégustation. Incluez des suggestions utiles pour le brasseur.";
if (isset($_SESSION['jPrefsScoreDispMax'])) $evaluation_info_036 = "Un ou plusieurs scores de juge sont en dehors de la plage de score acceptable. La plage acceptable est de ".$_SESSION['jPrefsScoreDispMax']. " points ou moins.";
$evaluation_info_037 = "Toutes les sessions de dégustation à cette table semblent être terminées.";
$evaluation_info_038 = "En tant que juge en chef, il est de votre responsabilité de vérifier que les scores de consensus de chaque inscription correspondent, de vous assurer que tous les scores des juges pour chaque inscription sont dans la plage appropriée et d'attribuer des places aux inscriptions désignées.";
$evaluation_info_039 = "Inscriptions à cette table :";
$evaluation_info_040 = "Inscriptions notées à cette table :";
$evaluation_info_041 = "Inscriptions notées dans votre session :";
$evaluation_info_042 = "Vos inscriptions notées :";
$evaluation_info_043 = "Juges avec des évaluations à cette table :";
$label_submitted = "Soumis";
$label_ordinal_position = "Position ordonnée dans la session";
$label_alcoholic = "Alcoolisé";

$descr_alcoholic = "L'arôme, la saveur et l'effet réchauffant de l'éthanol et des alcools supérieurs. Parfois décrit comme &quot;brûlant&quot;.";
$descr_alcoholic_mead = "L'effet de l'éthanol. Réchauffant. Brûlant.";
$label_metallic = "Métallique"; 
$descr_metallic = "Saveur métallique, d'étain, de pièce de monnaie, de cuivre, de fer ou de sang.";
$label_oxidized = "Oxydé";
$descr_oxidized = "Une ou plusieurs combinaisons d'arômes et de saveurs rassis, vineux, cartonnés, papier ou de type xérès. Rassis.";
$descr_oxidized_cider = "Rançonne, l'arôme/la saveur du xérès, des raisins ou de fruits meurtris.";
$label_phenolic = "Phénolique";
$descr_phenolic = "Épicé (clou de girofle, poivre), fumé, plastique, bande adhésive en plastique et/ou médicinal (chlorophénolique).";
$label_vegetal = "Végétal";
$descr_vegetal = "Arôme et saveur de légumes cuits, en conserve ou pourris (chou, oignon, céleri, asperge, etc.).";
$label_astringent = "Astringent";
$descr_astringent = "Une sensation de dessèchement, d'âpreté et/ou de sécheresse persistante en fin de bouche ; rugosité prononcée ; rudesse.";
$descr_astringent_cider = "Sensation de dessèchement en bouche similaire à celle de la mastication d'un sachet de thé. Doit être équilibré s'il est présent.";
$label_acetaldehyde = "Acétaldéhyde";
$descr_acetaldehyde = "Arôme et saveur de pomme verte.";
$label_diacetyl = "Diacétyle";
$descr_diacetyl = "Arôme et saveur de beurre artificiel, de caramel au beurre ou de toffee. Parfois perçu comme une sensation de glissement sur la langue.";
$descr_diacetyl_cider = "Arôme ou saveur de beurre ou de caramel au beurre.";
$label_dms = "DMS (Diméthylsulfure)";
$descr_dms = "À faibles niveaux, un arôme et une saveur de maïs doux, cuit ou en conserve.";
$label_estery = "Estérique";
$descr_estery = "Arôme et/ou saveur d'esters (fruits, arômes de fruits ou roses).";
$label_grassy = "Herbacé";
$descr_grassy = "Arôme/saveur d'herbe fraîchement coupée ou de feuilles vertes.";
$label_light_struck = "Lumière-frappé";
$descr_light_struck = "Similaire à l'arôme d'une mouffette.";
$label_musty = "Moisi";
$descr_musty = "Arômes/saveurs rassis, moisis ou de moisi.";
$label_solvent = "Solvant";
$descr_solvent = "Arômes et saveurs d'alcools supérieurs (alcools fusel). Similaire aux arômes d'acétone ou de diluant pour laque.";
$label_sour_acidic = "Acide/Acidulé";
$descr_sour_acidic = "Tarteur en arôme et en saveur. Peut être tranchant et net (acide lactique) ou rappelant le vinaigre (acide acétique).";
$label_sulfur = "Soufre";
$descr_sulfur = "L'arôme des œufs pourris ou des allumettes brûlées.";
$label_sulfury = "Sulfureux";
$label_yeasty = "Levuré";
$descr_yeasty = "Arôme ou saveur de pain, de soufre ou de levure.";
$label_acetified = "Acétifié (Acidité volatile, VA)";
$descr_acetified = "Acétate d'éthyle (solvant, vernis à ongles) ou acide acétique (vinaigre, irritant au fond de la gorge).";
$label_acidic = "Acide";
$descr_acidic = "Saveur acidulée. Typiquement due à l'un des plusieurs acides : malique, lactique ou citrique. Doit être équilibré.";
$descr_acidic_mead = "Saveur/acétate d'arôme propre en raison d'un pH bas. Typiquement due à l'un des plusieurs acides : malique, lactique, gluconique ou citrique.";
$label_bitter = "Amer";
$descr_bitter = "Un goût piquant qui est désagréable à des niveaux élevés.";
$label_farmyard = "Ferme";
$descr_farmyard = "Arôme de fumier (vache ou cochon) ou d'écurie (écurie de cheval par une journée chaude).";
$label_fruity = "Fruité";
$descr_fruity = "L'arôme et la saveur de fruits frais qui peuvent convenir à certains styles et pas à d'autres.";
$descr_fruity_mead = "Arômes et saveurs d'esters souvent dérivés de fruits dans un mélocit. La banane et l'ananas sont souvent des arômes indésirables.";
$label_mousy = "Souris";
$descr_mousy = "Goût évocateur de l'odeur de la tanière ou de la cage d'un rongeur.";
$label_oaky = "Boisé";
$descr_oaky = "Un goût ou un arôme dû à une longue période en fût ou sur des copeaux de bois. &quot;Caractère de fût.&quot;";
$label_oily_ropy = "Huileux/Viandeux";
$descr_oily_ropy = "Un éclat dans l'apparence visuelle, comme un caractère désagréable visqueux précédant un caractère ropy.";
$label_spicy_smoky = "Épicé/Fumé";
$descr_spicy_smoky = "Épices, clous de girofle, fumé, jambon.";
$label_sulfide = "Sulfure";
$descr_sulfide = "Œufs pourris, dus à des problèmes de fermentation.";
$label_sulfite = "Sulfite";
$descr_sulfite = "Allumettes brûlées, dues à une sulfitation excessive/récente.";
$label_sweet = "Sucré";
$descr_sweet = "Goût de base du sucre. Doit être équilibré s'il est présent.";
$label_thin = "Mince";
$descr_thin = "Aqueux. Manque de corps ou de &quot;rembourrage.&quot;";
$label_acetic = "Acétique";
$descr_acetic = "Vinaigré, acide acétique, piquant.";
$label_chemical = "Chimique";
$descr_chemical = "Goût de vitamine, de nutriment ou de produit chimique.";
$label_cloying = "Écoeurant";
$descr_cloying = "Sirupeux, excessivement sucré, déséquilibré par l'acidité/les tanins.";
$label_floral = "Floral";
$descr_floral = "L'arôme des fleurs en fleurs ou du parfum.";
$label_moldy = "Moisi";
$descr_moldy = "Arômes/saveurs rassis, moisis ou de bouchon.";
$label_tannic = "Tannique";
$descr_tannic = "Sensation de bouche asséchante, astringente, rappelant l'amertume. Goût de thé fort non sucré ou de mastication d'une peau de raisin.";
$label_waxy = "Cireux";
$descr_waxy = "Similaire à la cire, suif, gras.";
$label_medicinal = "Médicinal";
$label_spicy = "Épicé";
$label_vinegary = "Vinaigre";
$label_plastic = "Plastique";
$label_smoky = "Fumé";
$label_inappropriate = "Inapproprié";
$label_possible_points = "Points Possibles";
$label_malt = "Malt";
$label_ferm_char = "Caractère de Fermentation";
$label_body = "Corpulence";
$label_creaminess = "Onctuosité";
$label_astringency = "Astringence";
$label_warmth = "Chaleur";
$label_appearance = "Apparence";
$label_flavor = "Saveur";
$label_mouthfeel = "Sensation en Bouche";
$label_overall_impression = "Impression Générale";
$label_balance = "Équilibre";
$label_finish_aftertaste = "Fin/Arrière-goût";
$label_hoppy = "Houblonné";
$label_malty = "Malté";

$label_comments = "Commentaires";
$label_flaws = "Défauts pour le style";
$label_flaw = "Défaut";
$label_flawless = "Sans défaut";
$label_significant_flaws = "Défauts significatifs";
$label_classic_example = "Exemple classique";
$label_not_style = "Hors style";
$label_tech_merit = "Mérite technique";
$label_style_accuracy = "Précision stylistique";
$label_intangibles = "Impression générale";
$label_wonderful = "Merveilleux";
$label_lifeless = "Sans vie";
$label_feedback = "Retour d'information";
$label_honey = "Miel";
$label_alcohol = "Alcool";
$label_complexity = "Complexité";
$label_viscous = "Visqueux";
$label_legs = "Jambes"; // Used to describe liquid clinging to glass
$label_clarity = "Clarté";
$label_brilliant = "Brillant";
$label_hazy = "Trouble";
$label_opaque = "Opaque";
$label_fruit = "Fruit";
$label_acidity = "Acidité";
$label_tannin = "Tanin";
$label_white = "Blanc";
$label_straw = "Paille";
$label_yellow = "Jaune";
$label_gold = "Or";
$label_copper = "Cuivre";
$label_quick = "Rapide";
$label_long_lasting = "Persistant";
$label_ivory = "Ivoire";
$label_beige = "Beige";
$label_tan = "Marron";
$label_lacing = "Dentelle";
$label_particulate = "Particules";
$label_black = "Noir";
$label_large = "Grosses";
$label_small = "Petites";
$label_size = "Taille";
$label_retention = "Maintien";
$label_head = "Mousse";
$label_head_size = "Taille de la mousse";
$label_head_retention = "Maintien de la mousse";
$label_head_color = "Couleur de la mousse";
$label_brettanomyces = "Brettanomyces";
$label_cardboard = "Carton";
$label_cloudy = "Nuageux";
$label_sherry = "Xérès";
$label_harsh = "Dur";
$label_harshness = "Dureté";
$label_full = "Plein";
$label_suggested = "Suggéré";
$label_lactic = "Lactique";
$label_smoke = "Fumé";
$label_spice = "Épicé";
$label_vinous = "Vineux";
$label_wood = "Boisé";
$label_cream = "Crème";
$label_flat = "Plat";
$label_descriptor_defs = "Définitions des descripteurs";
$label_outstanding = "Exceptionnel";
$descr_outstanding = "Exemple de classe mondiale du style.";
$label_excellent = "Excellent";
$descr_excellent = "Exemplifie bien le style, nécessite quelques ajustements mineurs.";
$label_very_good = "Très bien";
$descr_very_good = "Généralement conforme aux paramètres du style, quelques défauts mineurs.";
$label_good = "Bon";
$descr_good = "Manque le style et/ou présente des défauts mineurs.";
$label_fair = "Passable";
$descr_fair = "Des arômes ou des saveurs indésirables ou des lacunes majeures dans le style. Désagréable.";
$label_problematic = "Problématique";
$descr_problematic = "Des arômes et des saveurs indésirables dominent. Difficile à boire.";

/**
 * ------------------------------------------------------------------------
 * Version 2.3.0 Additions
 * ------------------------------------------------------------------------
 */
$winners_text_006 = "Veuillez noter : les résultats de cette table peuvent être incomplets en raison d'informations insuffisantes sur les entrées ou le style.";

$label_elapsed_time = "Temps écoulé";
$label_judge_score = "Note du juge(s)";
$label_judge_consensus_scores = "Note de consensus du juge(s)";
$label_your_consensus_score = "Votre note de consensus";
$label_score_range_status = "Statut de la plage de notes";
$label_consensus_caution = "Avertissement de consensus";
$label_consensus_match = "Correspondance de consensus";
$label_score_range_caution = "Avertissement de plage de notes des juges";
$label_score_range_ok = "Plage de notes des juges OK";
$label_auto_log_out = "Déconnexion automatique dans";
$label_place_previously_selected = "Place précédemment sélectionnée";
$label_entry_without_eval = "Entrée sans évaluation";
$label_entries_with_eval = "Entrées avec évaluation";
$label_entries_without_eval = "Entrées sans évaluation";
$label_judging_close = "Fermeture de la dégustation";
$label_session_expire = "Session sur le point d'expirer";
$label_refresh = "Actualiser cette page";
$label_stay_here = "Rester ici";
$label_bottle_inspection = "Inspection de la bouteille";
$label_bottle_inspection_comments = "Commentaires de l'inspection de la bouteille";
$label_consensus_no_match = "Les notes de consensus ne correspondent pas";
$label_score_below_courtesy = "La note saisie est inférieure au seuil de courtoisie";
$label_score_greater_50 = "La note saisie est supérieure à 50";
$label_score_out_range = "La note est hors de la plage de notes";
$label_score_range = "Plage de notes";
$label_ok = "OK";
$label_esters = "Esters";
$label_phenols = "Phénols";
$label_descriptors = "Descripteurs";
$label_grainy = "Granuleux";
$label_caramel = "Caramel";
$label_bready = "Pain";
$label_rich = "Riche";
$label_dark_fruit = "Fruits noirs";
$label_toasty = "Toasté";
$label_roasty = "Grillé";
$label_burnt = "Brûlé";
$label_citrusy = "Agrumé";
$label_earthy = "Terreux";
$label_herbal = "Herbacé";
$label_piney = "Résineux";
$label_woody = "Boisé";
$label_apple_pear = "Pomme/Poire";
$label_banana = "Banane";
$label_berry = "Baies";
$label_citrus = "Agrumes";
$label_dried_fruit = "Fruits secs";
$label_grape = "Raisin";
$label_stone_fruit = "Fruits à noyau";
$label_even = "Régulier";
$label_gushed = "Giclé";
$label_hot = "Alcool fort";
$label_slick = "Glissant";
$label_finish = "Finale";
$label_biting = "Mordant";
$label_drinkability = "Facilité de dégustation";
$label_bouquet = "Bouquet";
$label_of = "de";
$label_fault = "Défaut";
$label_weeks = "Semaines";
$label_days = "Jours";
$label_scoresheet = "Fiche de notation";
$label_beer_scoresheet = "Fiche de notation de la bière";
$label_cider_scoresheet = "Fiche de notation du cidre";
$label_mead_scoresheet = "Fiche de notation de l'hydromel";
$label_consensus_status = "Statut de consensus";

$evaluation_info_044 = "Votre note de consensus ne correspond pas à celles saisies par les autres juges.";
$evaluation_info_045 = "La note de consensus saisie correspond à celles saisies par les juges précédents.";
$evaluation_info_046 = "La différence de note est supérieure à";
$evaluation_info_047 = "La différence de note est dans la plage acceptable.";
$evaluation_info_048 = "L'emplacement que vous avez spécifié a déjà été saisi pour cette table. Veuillez choisir un autre emplacement ou ne pas en spécifier (Aucun).";
$evaluation_info_049 = "Ces entrées n'ont pas au moins une évaluation.";
$evaluation_info_050 = "Veuillez fournir la position ordinale de l'entrée dans la série.";
$evaluation_info_051 = "Veuillez fournir le nombre total d'entrées dans la série.";
$evaluation_info_052 = "Taille appropriée, capsule, niveau de remplissage, retrait de l'étiquette, etc.";
$evaluation_info_053 = "La note de consensus est la note finale acceptée par tous les juges évaluant l'entrée. Si la note de consensus est inconnue à ce stade, saisissez votre propre note. Si la note de consensus que vous saisissez ici diffère de celles saisies par d'autres juges, vous serez averti(e).";
$evaluation_info_054 = "Cette entrée a été sélectionnée pour une ronde mini-BOS (Best of Show).";
$evaluation_info_055 = "La note de consensus que vous avez saisie ne correspond pas à celles saisies par les juges précédents pour cette entrée. Veuillez consulter les autres juges évaluant cette entrée et réviser votre note de consensus si nécessaire.";
$evaluation_info_056 = "La note que vous avez saisie est inférieure à 13, <a href=\"https://www.bjcp.org/cep/GreatBeerJudging.pdf\" target=\"_blank\">un seuil de courtoisie couramment connu pour les juges BJCP</a>. Veuillez consulter les autres juges et réviser votre note si nécessaire.";
$evaluation_info_057 = "Les notes doivent être comprises entre 5 et 50.";
$evaluation_info_058 = "La note que vous avez saisie est supérieure à 50, la note maximale pour n'importe quelle entrée. Veuillez la revoir et la réviser.";
$evaluation_info_059 = "La note que vous avez fournie pour cette entrée est en dehors de la plage de différence de notation entre les juges.";
$evaluation_info_060 = "caractères maximum";
$evaluation_info_061 = "Veuillez fournir des commentaires.";
$evaluation_info_062 = "Veuillez choisir un descripteur.";
$evaluation_info_063 = "Je terminerais cet échantillon.";
$evaluation_info_064 = "Je boirais une pinte de cette bière.";
$evaluation_info_065 = "Je paierais pour cette bière.";
$evaluation_info_066 = "Je recommanderais cette bière.";
$evaluation_info_067 = "Veuillez fournir une note.";
$evaluation_info_068 = "Veuillez fournir la note de consensus - minimum de 5, maximum de 50.";
$evaluation_info_069 = "Au moins deux juges de la série dans laquelle votre soumission a été inscrite ont atteint un consensus sur votre note finale attribuée. Il ne s'agit pas nécessairement d'une moyenne des notes individuelles.";
$evaluation_info_070 = "Selon la fiche de notation BJCP pour";
$evaluation_info_071 = "Il s'est écoulé plus de 15 minutes.";
$evaluation_info_072 = "Par défaut, la déconnexion automatique est prolongée à 30 minutes pour les évaluations des entrées.";

$alert_text_090 = "Votre session expirera dans deux minutes. Vous pouvez rester sur la page actuelle pour terminer votre travail avant l'expiration du temps, actualiser cette page pour continuer votre session actuelle (les données du formulaire non enregistrées peuvent être perdues), ou vous déconnecter.";
$alert_text_091 = "Votre session expirera dans 30 secondes. Vous pouvez actualiser pour continuer votre session actuelle ou vous déconnecter.";
$alert_text_092 = "Au moins une session de dégustation doit être définie pour ajouter une table.";

$brewer_entries_text_026 = "Les fiches de notation des juges pour cette entrée sont dans plusieurs formats. Chaque format contient une ou plusieurs évaluations valides de cette entrée.";

// Update QR text
$qr_text_008 = "Pour enregistrer des entrées via un code QR, veuillez fournir le mot de passe correct. Vous n'aurez à fournir le mot de passe qu'une seule fois par session - veillez à maintenir le navigateur ou l'application de numérisation de code QR ouverts.";
$qr_text_015 = "Numérisez le code QR suivant. Fermez cet onglet du navigateur si vous le souhaitez.<br><br>Pour les systèmes d'exploitation plus récents, accédez à l'application appareil photo de votre appareil mobile. Pour les systèmes d'exploitation plus anciens, lancez/revenez à l'application de numérisation.";
$qr_text_017 = "La numérisation de codes QR est disponible nativement sur la plupart des systèmes d'exploitation mobiles modernes. Il vous suffit de pointer votre appareil photo vers le code QR sur l'étiquette d'une bouteille et de suivre les instructions. Pour les systèmes d'exploitation mobiles plus anciens, une application de numérisation de code QR est nécessaire pour utiliser cette fonctionnalité.";
$qr_text_018 = "Numérisez un code QR situé sur l'étiquette d'une bouteille, entrez le mot de passe requis et enregistrez l'entrée.";

/**
 * ------------------------------------------------------------------------
 * Version 2.3.2 Additions
 * ------------------------------------------------------------------------
 */

$label_select_state = "Sélectionnez ou recherchez votre État";
$label_select_below = "Sélectionnez ci-dessous";
$output_text_033 = "Lorsque vous soumettez votre rapport au BJCP, il est possible que tous les membres de la liste du personnel ne reçoivent pas de points. Il est recommandé d'attribuer des points en priorité à ceux qui ont des identifiants BJCP.";

$styles_entry_text_PRX3 = "Le participant doit spécifier la variété de raisins ou de moût de raisin utilisée.";
$styles_entry_text_C1A = "Les participants DOIVENT spécifier le niveau de carbonatation (3 niveaux). Les participants DOIVENT spécifier la douceur (5 catégories). Si la densité initiale (DI) est sensiblement supérieure à la plage typique, le participant doit l'expliquer, par exemple, une variété particulière de pomme produisant un moût à haute densité.";
$styles_entry_text_C1B = "Les participants DOIVENT spécifier le niveau de carbonatation (3 niveaux). Les participants DOIVENT spécifier la douceur (de sec à moyennement doux, 4 niveaux). Les participants PEUVENT spécifier la variété de pomme pour un cidre de variété unique ; si elle est spécifiée, la caractéristique de la variété sera attendue.";
$styles_entry_text_C1C = "Les participants DOIVENT spécifier le niveau de carbonatation (3 niveaux). Les participants DOIVENT spécifier la douceur (de moyennement doux à doux, 3 niveaux). Les participants PEUVENT spécifier la variété de pomme pour un cidre de variété unique ; si elle est spécifiée, la caractéristique de la variété sera attendue.";
$winners_text_007 = "Il n'y a pas d'entrées gagnantes à cette table.";

/**
 * ------------------------------------------------------------------------
 * Version 2.4.0 Additions
 * ------------------------------------------------------------------------
 */

$label_entries_to_judge = "Entrées à juger";
$evaluation_info_073 = "Si vous avez modifié ou ajouté un élément ou un commentaire dans cette fiche de notation, vos données peuvent être perdues si vous quittez cette page.";
$evaluation_info_074 = "Si vous AVEZ apporté des modifications, fermez cette fenêtre, faites défiler jusqu'au bas de la fiche de notation et sélectionnez Soumettre l'évaluation.";
$evaluation_info_075 = "Si vous N'AVEZ PAS apporté de modifications, sélectionnez le bouton bleu Tableau de bord du jugement ci-dessous.";
$evaluation_info_076 = "Commentaire sur le malt, les houblons, les esters et autres arômes.";
$evaluation_info_077 = "Commentaire sur la couleur, la limpidité et la tête (rétention, couleur et texture).";
$evaluation_info_078 = "Commentaire sur le malt, les houblons, les caractéristiques de fermentation, l'équilibre, la finition/l'arrière-goût et d'autres caractéristiques de saveur.";
$evaluation_info_079 = "Commentaire sur le corps, la carbonatation, la chaleur, la crémosité, l'astringence et d'autres sensations en bouche.";
$evaluation_info_080 = "Commentaire sur le plaisir de dégustation global associé à l'entrée, donnez des suggestions d'amélioration.";

if ((isset($_SESSION['prefsStyleSet'])) && ($_SESSION['prefsStyleSet'] == "BJCP2021")) {
    $styles_entry_text_21B = "Le participant DOIT spécifier une force (session, standard, double) ; si aucune force n'est spécifiée, la force standard sera supposée. Le participant DOIT spécifier le type spécifique de Specialty IPA à partir de la liste des types actuellement définis identifiés dans les directives de style, ou tel que modifié par les styles provisoires sur le site Web de la BJCP ; OU le participant DOIT décrire le type de Specialty IPA et ses caractéristiques clés dans un commentaire afin que les juges sachent à quoi s'attendre. Les participants PEUVENT spécifier les variétés spécifiques de houblon utilisées, si les participants estiment que les juges pourraient ne pas reconnaître les caractéristiques variétales des houblons plus récents. Les participants PEUVENT spécifier une combinaison de types IPA définis (par exemple, Black Rye IPA) sans fournir de descriptions supplémentaires.";

    $styles_entry_text_24C = "Le participant DOIT spécifier une Bière de Garde blonde, ambrée ou brune.";
    $styles_entry_text_25B = "Le participant DOIT spécifier la force (table, standard, super) et la couleur (pâle, foncée). Le participant PEUT identifier les céréales caractéristiques utilisées.";
    $styles_entry_text_27A = "Catégorie fourre-tout pour les autres bières historiques qui n'ont PAS été définies par la BJCP. Le participant DOIT fournir une description aux juges du style historique qui NE fait PAS partie des exemples de styles historiques actuellement définis fournis par la BJCP. Exemples actuellement définis : Kellerbier, Kentucky Common, Lichtenhainer, London Brown Ale, Piwo Grodziskie, Pre-Prohibition Lager, Pre-Prohibition Porter, Roggenbier, Sahti. Si une bière est inscrite uniquement avec un nom de style et aucune description, il est très peu probable que les juges comprennent comment la juger.";
    $styles_entry_text_28A = "Le participant DOIT spécifier soit un style de base, soit fournir une description des ingrédients, des spécifications ou du caractère souhaité. Le participant PEUT spécifier les souches de Brett utilisées.";
    $styles_entry_text_28B = "Le participant DOIT spécifier une description de la bière, en identifiant la levure ou les bactéries utilisées et soit un style de base, soit les ingrédients, les spécifications ou le caractère souhaité de la bière.";
    $styles_entry_text_28C = "Le participant DOIT spécifier tout ingrédient de type spécial (par exemple, fruit, épice, herbe ou bois) utilisé. Le participant DOIT spécifier soit une description de la bière, en identifiant la levure ou les bactéries utilisées et soit un style de base, soit les ingrédients, les spécifications ou le caractère souhaité de la bière. Une description générale de la nature spéciale de la bière peut couvrir tous les éléments requis.";
    $styles_entry_text_29A = "Le participant DOIT spécifier le type(s) de fruits utilisé(s). Le participant DOIT spécifier une description de la bière, en identifiant soit un style de base, soit les ingrédients, les spécifications ou le caractère souhaité de la bière. Une description générale de la nature spéciale de la bière peut couvrir tous les éléments requis. Les bières aux fruits basées sur un style classique doivent être inscrites dans ce style, à l'exception des Lambic.";
    $styles_entry_text_29B = "Le participant doit spécifier le type de fruit et le type d'épices, d'herbes ou de légumes utilisé ; les ingrédients individuels n'ont pas besoin d'être spécifiés si un mélange bien connu d'épices est utilisé (par exemple, épices pour tarte aux pommes). Le participant doit spécifier une description de la bière, en identifiant soit un style de base, soit les ingrédients, les spécifications ou le caractère souhaité de la bière. Une description générale de la nature spéciale de la bière peut couvrir tous les éléments requis.";
    $styles_entry_text_29C = "Le participant DOIT spécifier le type de fruits utilisés. Le participant DOIT spécifier le type d'ingrédient supplémentaire (conformément à l'introduction) ou le processus spécial utilisé. Le participant DOIT spécifier une description de la bière, en identifiant soit un style de base, soit les ingrédients, les spécifications ou le caractère souhaité de la bière. Une description générale de la nature spéciale de la bière peut couvrir tous les éléments requis.";
    $styles_entry_text_29D = "Le participant DOIT spécifier le type de raisin utilisé. Le participant PEUT fournir des informations supplémentaires sur le style de base ou les ingrédients caractéristiques.";
    $styles_entry_text_30A = "Le participant DOIT spécifier le type d'épices, d'herbes ou de légumes utilisé, mais les ingrédients individuels n'ont pas besoin d'être spécifiés si un mélange d'épices bien connu est utilisé (par exemple, épices pour tarte aux pommes, poudre de curry, poudre de chili). Le participant DOIT spécifier une description de la bière, en identifiant soit un style de base, soit les ingrédients, les spécifications ou le caractère souhaité de la bière. Une description générale de la nature spéciale de la bière peut couvrir tous les éléments requis.";
    $styles_entry_text_30B = "Le participant DOIT spécifier le type d'épices, d'herbes ou de légumes utilisé ; les ingrédients individuels n'ont pas besoin d'être spécifiés si un mélange d'épices bien connu est utilisé (par exemple, épices pour tarte à la citrouille). Le participant DOIT spécifier une description de la bière, en identifiant soit un style de base, soit les ingrédients, les spécifications ou le caractère souhaité de la bière. Une description générale de la nature spéciale de la bière peut couvrir tous les éléments requis.";
    $styles_entry_text_30C = "Le participant DOIT spécifier le type d'épices, de sucres, de fruits ou d'ingrédients fermentescibles supplémentaires utilisés ; les ingrédients individuels n'ont pas besoin d'être spécifiés si un mélange d'épices bien connu est utilisé (par exemple, mélange d'épices pour vin chaud). Le participant DOIT spécifier une description de la bière, en identifiant soit un style de base, soit les ingrédients, les spécifications ou le caractère souhaité de la bière. Une description générale de la nature spéciale de la bière peut couvrir tous les éléments requis.";
    $styles_entry_text_30D = "Le participant DOIT spécifier le type d'épices, d'herbes ou de légumes utilisé, mais les ingrédients individuels n'ont pas besoin d'être spécifiés si un mélange d'épices bien connu est utilisé (par exemple, épices pour tarte aux pommes, poudre de curry, poudre de chili). Le participant DOIT spécifier le type d'ingrédient supplémentaire (conformément à l'introduction) ou le processus spécial employé. Le participant DOIT spécifier une description de la bière, en identifiant soit un style de base, soit les ingrédients, les spécifications ou le caractère souhaité de la bière. Une description générale de la nature spéciale de la bière peut couvrir tous les éléments requis.";
    $styles_entry_text_31A = "Le participant DOIT spécifier le type de céréales alternatives utilisées. Le participant DOIT spécifier une description de la bière, en identifiant soit un style de base, soit les ingrédients, les spécifications ou le caractère souhaité de la bière. Une description générale de la nature spéciale de la bière peut couvrir tous les éléments requis.";
    $styles_entry_text_31B = "Le participant DOIT spécifier le type de sucre utilisé. Le participant DOIT spécifier une description de la bière, en identifiant soit un style de base, soit les ingrédients, les spécifications ou le caractère souhaité de la bière. Une description générale de la nature spéciale de la bière peut couvrir tous les éléments requis.";
    $styles_entry_text_32A = "Le participant DOIT spécifier un style de base. Le participant DOIT spécifier le type de bois ou de fumée si un caractère de fumée variétale est perceptible.";
    $styles_entry_text_32B = "Le participant DOIT spécifier le type de bois ou de fumée si un caractère de fumée variétale est perceptible. Le participant DOIT spécifier les ingrédients ou les processus supplémentaires qui font de cette bière fumée une bière spéciale. Le participant DOIT spécifier une description de la bière, en identifiant soit un style de base, soit les ingrédients, les spécifications ou le caractère souhaité de la bière. Une description générale de la nature spéciale de la bière peut couvrir tous les éléments requis.";
    $styles_entry_text_33A = "Le participant DOIT spécifier le type de bois utilisé et le niveau de toastage ou de carbonisation (si utilisé). Si un type de bois inhabituel est utilisé, le participant DOIT fournir une brève description des aspects sensoriels que le bois ajoute à la bière. Le participant DOIT spécifier une description de la bière, en identifiant soit un style de base, soit les ingrédients, les spécifications ou le caractère souhaité de la bière. Une description générale de la nature spéciale de la bière peut couvrir tous les éléments requis.";
    $styles_entry_text_33B = "Le participant DOIT spécifier le caractère alcoolique supplémentaire, avec des informations sur le fût si cela est pertinent pour le profil sensoriel final. Si un bois ou un ingrédient inhabituel est utilisé, le participant DOIT fournir une brève description des aspects sensoriels que les ingrédients ajoutent à la bière. Le participant DOIT spécifier une description de la bière, en identifiant soit un style de base, soit les ingrédients, les spécifications ou le caractère souhaité de la bière. Une description générale de la nature spéciale de la bière peut couvrir tous les éléments requis.";
    $styles_entry_text_34A = "Le participant DOIT spécifier le nom de la bière commerciale, les spécifications (paramètres essentiels) de la bière et soit une brève description sensorielle, soit une liste des ingrédients utilisés pour fabriquer la bière. Sans ces informations, les juges qui ne connaissent pas la bière n'auront aucune base de comparaison.";
    $styles_entry_text_34B = "Le participant DOIT spécifier le(s) style(s) de base utilisé(s), ainsi que tout ingrédient spécial, processus ou variation impliqué. Le participant PEUT fournir une description supplémentaire du profil sensoriel de la bière ou des paramètres essentiels de la bière résultante.";
    $styles_entry_text_PRX3 = "Le participant DOIT spécifier le type de raisin utilisé. Le participant PEUT fournir des informations supplémentaires sur le style de base ou les ingrédients caractéristiques.";
}

/**
 * ------------------------------------------------------------------------
 * Version 2.5.0 Additions
 * ------------------------------------------------------------------------
 */

$register_text_047 = "Votre question de sécurité et/ou réponse a changé.";
$register_text_048 = "Si vous n'avez pas initié ce changement, votre compte pourrait être compromis. Vous devriez immédiatement vous connecter à votre compte et changer votre mot de passe en plus de mettre à jour votre question de sécurité et la réponse.";
$register_text_049 = "Si vous ne pouvez pas vous connecter à votre compte, vous devriez immédiatement contacter un administrateur du site pour mettre à jour votre mot de passe et d'autres informations essentielles de votre compte.";
$register_text_050 = "La réponse à votre question de sécurité est chiffrée et ne peut pas être lue par les administrateurs du site. Elle doit être entrée si vous décidez de changer votre question de sécurité et/ou la réponse.";
$register_text_051 = "Informations du compte mises à jour";
$register_text_052 = "Une réponse \"Oui\" ou \"Non\" est requise pour chaque emplacement ci-dessous.";

$brewer_text_044 = "Souhaitez-vous changer votre question de sécurité et/ou votre réponse ?";
$brewer_text_045 = "Aucun résultat enregistré.";
$brewer_text_046 = "Pour la saisie du nom du club sous forme libre, certains symboles ne sont pas autorisés, notamment l'esperluette (&), les guillemets simples (&#39;), les guillemets doubles (&quot;) et le pourcentage (&#37;).";
$brewer_text_047 = "Si vous n'êtes pas disponible pour l'une des sessions répertoriées ci-dessous, mais que vous pouvez toujours servir en tant que personnel dans une autre capacité, laissez \"Oui\" sélectionné ci-dessus.";
$brewer_text_048 = "Expédition des Entrées";
$brewer_text_049 = "Sélectionnez \"Non Applicable\" si vous ne prévoyez pas de soumettre d'entrées à la compétition pour le moment.";
$brewer_text_050 = "Sélectionnez \"Expédition des Entrées\" si vous prévoyez d'emballer et d'envoyer vos entrées à l'emplacement d'expédition fourni.";

$label_change_security = "Changer la question/réponse de sécurité ?";
$label_semi_dry = "Mi-Sec";
$label_semi_sweet = "Mi-Doux";
$label_shipping_location = "Lieu d'Expédition";
$label_allergens = "Allergènes";

$volunteers_text_010 = "Le personnel peut indiquer leur disponibilité pour les sessions non liées à la dégustation suivantes :";

$evaluation_info_081 = "Commentaire sur l'expression du miel, de l'alcool, des esters, de la complexité et d'autres arômes.";
$evaluation_info_082 = "Commentaire sur la couleur, la clarté, les jambes et la carbonatation.";
$evaluation_info_083 = "Commentaire sur le miel, la douceur, l'acidité, les tanins, l'alcool, l'équilibre, le corps, la carbonatation, l'arrière-goût et tout ingrédient spécial ou des saveurs spécifiques au style.";
$evaluation_info_084 = "Commentaire sur le plaisir de boire global associé à l'entrée, donner des suggestions pour l'amélioration.";
$evaluation_info_085 = "Couleur (2), clarté (2), niveau de carbonatation (2).";
$evaluation_info_086 = "Expression des autres ingrédients au besoin.";
$evaluation_info_087 = "Équilibre entre l'acidité, la douceur, la force de l'alcool, le corps, la carbonatation (si approprié) (14), autres ingrédients au besoin (5), arrière-goût (5).";
$evaluation_info_088 = "Commentaire sur le plaisir global de boire associé à l'entrée, donner des suggestions pour l'amélioration.";

$evaluation_info_089 = "Nombre de mots minimum atteint ou dépassé.";
$evaluation_info_090 = "Merci d'avoir fourni l'évaluation la plus complète possible. Total des mots : ";
$evaluation_info_091 = "Nombre minimum de mots requis pour vos commentaires : ";
$evaluation_info_092 = "Nombre de mots jusqu'à présent : ";
$evaluation_info_093 = "L'exigence de mots minimum n'a pas été atteinte dans le champ de commentaires d'impression globale ci-dessus.";
$evaluation_info_094 = "L'exigence de mots minimum n'a pas été atteinte dans un ou plusieurs champs de commentaires ci-dessus.";

$label_regional_variation = "Variation Régionale";
$label_characteristics = "Caractéristiques";
$label_intensity = "Intensité";
$label_quality = "Qualité";
$label_palate = "Palais";
$label_medium = "Moyen";
$label_medium_dry = "Mi-Sec";
$label_medium_sweet = "Mi-Doux";
$label_your_score = "Votre Note";
$label_summary_overall_impression = "Résumé de l'Évaluation et Impression Générale";
$label_medal_count = "Nombre de Médailles dans le Groupe";
$label_best_brewer_place = "Meilleur Emplacement du Brasseur";
$label_industry_affiliations = "Affiliations à des Organisations de l'Industrie";
$label_deep_gold = "Or Profond";
$label_chestnut = "Châtain";
$label_pink = "Rose";
$label_red = "Rouge";
$label_purple = "Violet";
$label_garnet = "Grenat";
$label_clear = "Clair";
$label_final_judging_date = "Date de la Dernière Évaluation";
$label_entries_judged = "Entrées Évaluées";
$label_results_export = "Exporter les Résultats";
$label_results_export_personal = "Exporter les Résultats Personnels";

$brew_text_041 = "Optionnel - spécifiez une variation régionale (par exemple, Lager Mexicaine, Bière Blonde Hollandaise, Lager de Riz Japonaise, etc.).";

$evaluation_info_095 = "Prochaine session de jugement attribuée ouverte :";
$evaluation_info_096 = "Pour aider à la préparation, les tables/flights attribuées et les entrées associées sont disponibles dix minutes avant le début d'une session.";
$evaluation_info_097 = "Votre prochaine session de jugement est maintenant disponible.";
$evaluation_info_098 = "Actualisez pour voir.";
$evaluation_info_099 = "Sessions de jugement passées ou actuelles :";
$evaluation_info_100 = "Sessions de jugement à venir :";
$evaluation_info_101 = "Veuillez fournir un autre descripteur de couleur.";
$evaluation_info_102 = "Entrez votre note totale - maximum de 50. Utilisez le guide de notation ci-dessous si nécessaire.";
$evaluation_info_103 = "Veuillez fournir votre note - minimum de 5, maximum de 50.";

$brewer_text_051 = "Sélectionnez les organisations professionnelles auxquelles vous êtes affilié en tant qu'employé, bénévole, etc. Cela vise à éviter tout conflit d'intérêts lors de l'attribution des juges et des aides pour l'évaluation des entrées.";
$brewer_text_052 = "<strong>Si une organisation professionnelle n'est <u>pas</u> répertoriée dans la liste déroulante ci-dessus, entrez-la ici.</strong> Séparez les noms de chaque organisation par une virgule (,) ou un point-virgule (;). Certains symboles ne sont pas autorisés, notamment les guillemets doubles (&quot;) et le pourcentage (&#37;).";

/**
 * ------------------------------------------------------------------------
 * Version 2.6.0 Additions
 * ------------------------------------------------------------------------
 */
$evaluation_info_104 = "Tous les juges n'ont pas indiqué que cette entrée a été sélectionnée pour la ronde Mini-BOS. Veuillez vérifier et sélectionner Oui ou Non ci-dessus.";
$evaluation_info_105 = "Les entrées suivantes présentent des indications contradictoires de participation à la ronde Mini-BOS par les juges :";

$label_non_judging = "Sessions Hors Jugement";

/**
 * ------------------------------------------------------------------------
 * Version 2.6.2 Additions
 * ------------------------------------------------------------------------
 */
$label_mhp_number = "Numéro de Membre du Programme Master Homebrewer";
$brewer_text_053 = "Le Master Homebrewer Program est une organisation à but non lucratif créée pour promouvoir la maîtrise du brassage amateur.";
$best_brewer_text_015 = "Les points pour chaque entrée classée sont calculés selon la formule suivante, basée sur celle utilisée par le Master Homebrewer Program pour le <a href='https://www.masterhomebrewerprogram.com/circuit-of-america' target='_blank'>Circuit of America</a>:";

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

?>