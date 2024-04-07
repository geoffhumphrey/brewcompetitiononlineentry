<?php
/**
 * Module:      es-419.lang.php
 * Description: This module houses all display text in the Spanish 
 *              (Latin America) language.
 * Updated:     November 6, 2023
 * Translation: ChatGPT
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
$label_home = "Página de Inicio";
$label_welcome = "Bienvenidos";
$label_comps = "Directorio de Competencia";
$label_info = "Información";
$label_volunteers = "Voluntarios";
$label_register = "Registrarse";
$label_help = "Ayuda";
$label_print = "Imprimir";
$label_my_account = "Mi Cuenta";
$label_yes = "Si";
$label_no = "No";
$label_low_none = "Reducido / Ninguno";
$label_low = "Reducido";
$label_med = "Medio";
$label_high = "Elevado";
$label_pay = "Pagar Tarifas de Entrada";
$label_reset_password = "Restablecer la Contraseña";
$label_log_in = "Iniciar Sesión";
$label_logged_in = "Conectado";
$label_log_out = "Cerrar Sesión";
$label_logged_out = "Desconectado";
$label_sponsors = "Patrocinadores";
$label_rules = "Reglas";
$label_volunteer_info = "Información del Voluntario";
$label_reg = $label_register;
$label_judge_reg = "Registro de Juez";
$label_steward_reg = "Registro de Mayordomo";
$label_past_winners = "Ganadores Pasados";
$label_contact = "Contacto";
$label_style = "Estilo";
$label_entry = "Entrada";
$label_add_entry = "Añadir Entrada";
$label_edit_entry = "Editar Entrada";
$label_upload = "Subir";
$label_bos = "Lo Mejor del Show";
$label_brewer = "Cervecero";
$label_cobrewer = "Cervecero Asociado";
$label_entry_name = "Nombre de Entrada";
$label_required_info = "Información Requerida";
$label_character_limit = " límite de caracteres. Use palabras clave y abreviaturas si el espacio es limitado.<br>Caracteres utilizados: ";
$label_carbonation = "Carbonatación";
$label_sweetness = "Dulzura";
$label_strength = "Intensidad";
$label_color = 	"Color";
$label_table = "Table";
$label_standard = "Normal";
$label_super = "Súper";
$label_session = "Bajo en Alcohol";
$label_double = "Doble";
$label_blonde = "Rubia";
$label_amber = "Ámbar";
$label_brown = "Marrón";
$label_pale = "Pálido";
$label_dark = "Oscuro";
$label_hydromel = "Hidromel";
$label_sack = "Sack";
$label_still = "Inmóvil";
$label_petillant = "Petilante";
$label_sparkling = "Espumoso";
$label_dry = "Seco";
$label_med_dry = "Medio Seco";
$label_med_sweet = "Medio Dulce";
$label_sweet = "Dulce";
$label_brewer_specifics = "Detalles del Cervecero";
$label_general = "General";
$label_amount_brewed = "Cantidad Elaborada";
$label_specific_gravity = "Gravedad Específica";
$label_fermentables = "Fermentables";
$label_malt_extract = "Extracto de Malta";
$label_grain = "Grano";
$label_hops = "Los Lúpulos";
$label_hop = "El Lúpulo";
$label_mash = "La Mezcla";
$label_steep = "Empapar";
$label_other = "Otro";
$label_weight = "Peso";
$label_use = "Use";
$label_time = "Tiempo";
$label_first_wort = "Primer Mosto";
$label_boil = "Hervir";
$label_aroma = "Aroma";
$label_dry_hop = "Lúpulo Seco";
$label_type = "Tipo";
$label_bittering = "Amargo";
$label_both = "Ambos";
$label_form = "Formar";
$label_whole = "Entero";
$label_pellets = "Pellets";
$label_plug = "Enchufe";
$label_extract = "Extracto";
$label_date = "Fetcha";
$label_bottled = "Embotellado";
$label_misc = "Diverso";
$label_minutes = "Minutos";
$label_hours = "Horas";
$label_step = "Step";
$label_temperature = "Temperature";
$label_water = "Agua";
$label_amount = "Cantidad";
$label_yeast = "Levadura";
$label_name = "Nombre";
$label_manufacturer = "Fabricante";
$label_nutrients = "Nutrientes";
$label_liquid = "Líquido";
$label_ale = "Cerveza Inglesa";
$label_lager = "Cerveza Lager";
$label_wine = "Vino";
$label_champagne = "Champán";
$label_boil = "Hervor";
$label_fermentation = "Fermentación";
$label_finishing = "Refinamiento";
$label_finings = "Acabados";
$label_primary = "Primario";
$label_secondary = "Secundario";
$label_days = "Days";
$label_forced = "CO2 Forzado";
$label_bottle_cond = "Botella Acondicionada";
$label_volume = "Volumen";
$label_og = "Gravedad Original";
$label_fg = "Gravedad Final";
$label_starter = "Arrancador";
$label_password = "Contraseña";
$label_judging_number = "Número de Juez";
$label_check_in = "Entrada de Registro";
$label_box_number = "Numero de Caja";
$label_first_name = "Primer Nombre";
$label_last_name = "Apellido";
$label_secret_01 = "¿Cuál es tu cerveza favorita de todos los tiempos para beber?";
$label_secret_02 = "¿Cuál era el nombre de tu primera mascota?";
$label_secret_03 = "¿Cómo se llamaba la calle en la que creciste?";
$label_secret_04 = "Cual fue la mascota de tu colegio?";
$label_security_answer = "Respuesta a la Pregunta de Seguridad";
$label_security_question = "Pregunta de Seguridad";
$label_judging = "Evaluar";
$label_judge = "Juez";
$label_steward = "Mayordomo";
$label_account_info = "Informacion de Cuenta";
$label_street_address = "Dirección";
$label_address = "Dirección";
$label_city = "Ciudad";
$label_state_province = "Estado o Provincia";
$label_zip = "Código Postal";
$label_country = "País";
$label_phone = "Phone";
$label_phone_primary = "Teléfono Principal";
$label_phone_secondary = "Teléfono Secundario";
$label_drop_off = "Punto de Entrega";
$label_drop_offs = "Lugares de Entrega";
$label_club = "Club";
$label_aha_number = "Número de Miembro de la AHA";
$label_org_notes = "Notas para el Organizador";
$label_avail = "Disponibilidad";
$label_location = "Localidad";
$label_judging_avail = "Juzgar la Disponibilidad de la Sesión";
$label_stewarding = "Mayordomía";
$label_stewarding_avail = "Disponibilidad de Sesiones de Administración";
$label_bjcp_id = "ID de BJCP";
$label_bjcp_mead = "Juez de Hidromiel";
$label_bjcp_rank = "Rango BJCP";
$label_designations = "Designaciones";
$label_judge_sensory = "Juez con Entrenamiento Sensorial";
$label_judge_pro = "Cervecero Profesional";
$label_judge_comps = "Número de Competiciones Juzgadas";
$label_judge_preferred = "Estilos Preferidos";
$label_judge_non_preferred = "Estilos no Preferidos";
$label_waiver = "Renuncia";
$label_add_admin = "Agregar Información de Usuario Administrador";
$label_add_account = "Agregar Información de Cuenta";
$label_edit_account = "Editar Información de Cuenta";
$label_entries = "Entradas";
$label_confirmed = "Confirmado";
$label_paid = "Pagado";
$label_updated = "Actualizado";
$label_mini_bos = "Mini-BOS";
$label_actions = "Comportamientos";
$label_score = "Puntuación";
$label_winner = "¿Ganador?";
$label_change_email = "Cambiar E-mail";
$label_change_password = "Cambia la Contraseña";
$label_add_beerXML = "Agregar una Entrada Usando BeerXML";
$label_none_entered = "Ninguno ingresado";
$label_none = "Ninguno";
$label_discount = "Descuento";
$label_subject = "Asunto";
$label_message = "Mensaje";
$label_send_message = "Enviar Mensaje";
$label_email = "Email Address";
$label_account_registration = "Registro de Cuenta";
$label_entry_registration = "Registro de Entrada";
$label_entry_fees = "Derechos de Inscripción";
$label_entry_limit = "Límite de Entrada";
$label_entry_info = "Información de Entrada";
$label_entry_per_entrant = "Límites por participante";
$label_categories_accepted = "Estilos Aceptados";
$label_judging_categories = "Categorías de Juzgamiento";
$label_entry_acceptance_rules = "Reglas de Aceptación de Entrada";
$label_shipping_info = "Información de Envío";
$label_packing_shipping = "Embalaje y Envío";
$label_awards = "Premios";
$label_awards_ceremony = "Ceremonia de Premiación";
$label_circuit = "Clasificación en el Circuito";
$label_hosted = "Edición Hospedada";
$label_entry_check_in = "Registro de Entrada";
$label_cash = "Efectivo";
$label_check = "Cheque";
$label_pay_online = "Pagar en Línea";
$label_cancel = "Cancelar";
$label_understand = "Entiendo";
$label_fee_discount = "Tarifa de Inscripción con Descuento";
$label_discount_code = "Código de Descuento";
$label_register_judge = "¿Estás Registrando como Participante, Juez o Catador?";
$label_register_judge_standard = "Registrar un Juez o Catador (Estándar)";
$label_register_judge_quick = "Registrar un Juez o Catador (Rápido)";
$label_all_participants = "Todos los Participantes";
$label_open = "Abierto";
$label_closed = "Cerrado";
$label_judging_loc = "Ubicaciones y Fechas de Sesiones de Evaluación";
$label_new = "Nuevo";
$label_old = "Antiguo";
$label_sure = "¿Estás Seguro?";
$label_judges = "Jueces";
$label_stewards = "Catadores";
$label_staff = "Personal";
$label_category = "Categoría";
$label_delete = "Eliminar";
$label_undone = "Esto no se puede deshacer";
$label_bitterness = "Amargura";
$label_close = "Cerrar";
$label_custom_style = "Estilo Personalizado";
$label_custom_style_types = "Tipos de Estilo Personalizado";
$label_assigned_to_table = "Asignado a Mesa";
$label_organizer = "Organizador";
$label_by_table = "Por Mesa";
$label_by_category = "Por Estilo";
$label_by_subcategory = "Por Subestilo";
$label_by_last_name = "Por Apellido";
$label_by_table = "Por Mesa";
$label_by_location = "Por Ubicación de Sesión";
$label_shipping_entries = "Envío de Entradas";
$label_no_availability = "No se ha Definido Disponibilidad";
$label_error = "Error";
$label_round = "Ronda";
$label_flight = "Grupo";
$label_rounds = "Rondas";
$label_flights = "Grupos";
$label_sign_in = "Iniciar Sesión";
$label_signature = "Firma";
$label_assignment = "Asignación";
$label_assignments = "Asignaciones";
$label_letter = "Letra";
$label_re_enter = "Volver a Ingresar";
$label_website = "Sitio Web";
$label_place = "Lugar";
$label_cheers = "¡Salud!";
$label_count = "Cantidad";
$label_total = "Total";
$label_participant = "Participante";
$label_entrant = "Participante";
$label_received = "Recibido";
$label_please_note = "Por Favor, Ten en Cuenta";
$label_pull_order = "Orden de Extracción";
$label_box = "Caja";
$label_sorted = "Ordenado";
$label_subcategory = "Subcategoría";
$label_affixed = "¿Etiqueta Colocada?";
$label_points = "Puntos";
$label_comp_id = "ID de Competencia BJCP";
$label_days = "Días";
$label_sessions = "Sesiones";
$label_number = "Número";
$label_more_info = "Más Información";
$label_entry_instructions = "Instrucciones de Inscripción";
$label_commercial_examples = "Ejemplos Comerciales";
$label_users = "Usuarios";
$label_participants = "Participantes";
$label_please_confirm = "Por Favor, Confirma";
$label_undone = "Esto no se puede deshacer.";
$label_data_retain = "Datos a Conservar";
$label_comp_portal = "Directorio de Competencias";
$label_comp = "Competencia";
$label_continue = "Continuar";
$label_host = "Anfitrión";
$label_closing_soon = "Cerrando Pronto";
$label_access = "Acceso";
$label_length = "Duración";

// Admin
$label_admin = "Administración";
$label_admin_short = "Admin";
$label_admin_dashboard = "Tablero de Control";
$label_admin_judging = $label_judging;
$label_admin_stewarding = "Servicio de Catadores";
$label_admin_participants = $label_participants;
$label_admin_entries = $label_entries;
$label_admin_comp_info = "Información de la Competencia";
$label_admin_web_prefs = "Preferencias del Sitio Web";
$label_admin_judge_prefs = "Preferencias de Evaluación/Organización de la Competencia";
$label_admin_archives = "Archivos";
$label_admin_style = $label_style;
$label_admin_styles = "Estilos";
$label_admin_dropoff = $label_drop_offs;
$label_admin_judging_loc = $label_judging_loc;
$label_admin_contacts = "Contactos";
$label_admin_tables = "Mesas";
$label_admin_scores = "Puntuaciones";
$label_admin_bos = $label_bos;
$label_admin_bos_acr = "BOS";
$label_admin_style_types = "Tipos de Estilo";
$label_admin_custom_cat = "Categorías Personalizadas";
$label_admin_custom_cat_data = "Entradas de Categoría Personalizada";
$label_admin_sponsors = $label_sponsors;
$label_admin_entry_count = "Conteo de Entradas por Estilo";
$label_admin_entry_count_sub = "Conteo de Entradas por Subestilo";
$label_admin_custom_mods = "Módulos Personalizados";
$label_admin_check_in = $label_entry_check_in;
$label_admin_make_admin = "Cambiar Nivel de Usuario";
$label_admin_register = "Registrar un Participante";
$label_admin_upload_img = "Subir Imágenes";
$label_admin_upload_doc = "Subir Hojas de Evaluación y Otros Documentos";
$label_admin_password = "Cambiar Contraseña de Usuario";
$label_admin_edit_account = "Editar Cuenta de Usuario";

// Etiquetas de la Barra Lateral
$label_account_summary = "Resumen de mi Cuenta";
$label_confirmed_entries = "Entradas Confirmadas";
$label_unpaid_confirmed_entries = "Entradas Confirmadas no Pagadas";
$label_total_entry_fees = "Total de Tarifas de Entrada";
$label_entry_fees_to_pay = "Tarifas de Entrada Pendientes de Pago";
$label_entry_drop_off = "Entrega de Entradas";
$label_maintenance = "Mantenimiento";
$label_judge_info = "Información del Juez";
$label_cellar = "Mi Bodega";
$label_verify = "Verificar";
$label_entry_number = "Número de Entrada";

/**
 * ------------------------------------------------------------------------
 * Headers
 * Missing punctuation intentional for all.
 * ------------------------------------------------------------------------
 */
$header_text_000 = "La configuración se realizó con éxito.";
$header_text_001 = "Ahora has iniciado sesión y estás listo para personalizar aún más el sitio de tu competencia.";
$header_text_002 = "Sin embargo, no se pudo cambiar el archivo de permisos config.php.";
$header_text_003 = "Se recomienda encarecidamente que cambies los permisos del servidor (chmod) en el archivo config.php a 555. Para hacerlo, deberás acceder al archivo en tu servidor.";
$header_text_004 = "Además, la variable &#36;setup_free_access en config.php está actualmente establecida en TRUE. Por razones de seguridad, la configuración debe volver a FALSE. Deberás editar config.php directamente y volver a cargarlo en tu servidor para hacer esto.";
$header_text_005 = "Información agregada con éxito.";
$header_text_006 = "Información editada con éxito.";
$header_text_007 = "Hubo un error.";
$header_text_008 = "Por favor, inténtalo de nuevo.";
$header_text_009 = "Debes ser un administrador para acceder a las funciones de administración.";
$header_text_010 = "Cambiar";
$header_text_011 = $label_email;
$header_text_012 = $label_password;
$header_text_013 = "La dirección de correo electrónico proporcionada ya está en uso, por favor proporciona otra dirección de correo electrónico.";
$header_text_014 = "Hubo un problema con la última solicitud, por favor inténtalo de nuevo.";
$header_text_015 = "Tu contraseña actual era incorrecta.";
$header_text_016 = "Por favor, proporciona una dirección de correo electrónico.";
$header_text_017 = "Lo siento, hubo un problema con tu último intento de inicio de sesión.";
$header_text_018 = "Lo siento, el nombre de usuario que ingresaste ya está en uso.";
$header_text_019 = "¿Quizás ya has creado una cuenta?";
$header_text_020 = "Si es así, inicia sesión aquí.";
$header_text_021 = "El nombre de usuario proporcionado no es una dirección de correo electrónico válida.";
$header_text_022 = "Por favor, introduce una dirección de correo electrónico válida.";
$header_text_023 = "El CAPTCHA no se completó correctamente.";
$header_text_024 = "Las direcciones de correo electrónico que ingresaste no coinciden.";
$header_text_025 = "El número de AHA que ingresaste ya está en el sistema.";
$header_text_026 = "Hemos recibido tu pago en línea y la transacción se ha completado. Ten en cuenta que puede que necesites esperar unos minutos para que el estado del pago se actualice aquí; asegúrate de actualizar esta página o acceder a tu lista de entradas. Recibirás un recibo de pago por correo electrónico de PayPal.";
$header_text_027 = "Asegúrate de imprimir el recibo y adjuntarlo a una de tus entradas como comprobante de pago.";
$header_text_028 = "Tu pago en línea ha sido cancelado.";
$header_text_029 = "El código ha sido verificado.";
$header_text_030 = "Lo siento, el código que ingresaste era incorrecto.";
$header_text_031 = "Debes iniciar sesión y tener privilegios de administrador para acceder a las funciones de administración.";
$header_text_032 = "Lo siento, hubo un problema con tu último intento de inicio de sesión.";
$header_text_033 = "Por favor, asegúrate de que tu dirección de correo electrónico y contraseña sean correctas.";
$header_text_034 = "Se ha generado un token para restablecer la contraseña y se ha enviado por correo electrónico a la dirección asociada a tu cuenta.";
$header_text_035 = "- ahora puedes iniciar sesión usando tu nombre de usuario actual y la nueva contraseña.";
$header_text_036 = "Has cerrado la sesión.";
$header_text_037 = "¿Iniciar sesión de nuevo?";
$header_text_038 = "Tu pregunta de verificación no coincide con lo que está en la base de datos.";
$header_text_039 = "Tu información de verificación de ID se ha enviado a la dirección de correo electrónico asociada con tu cuenta.";
$header_text_040 = "Tu mensaje se ha enviado a";
$header_text_041 = $header_text_023;
$header_text_042 = "Tu dirección de correo electrónico ha sido actualizada.";
$header_text_043 = "Tu contraseña ha sido actualizada.";
$header_text_044 = "Información eliminada con éxito.";
$header_text_045 = "Debes verificar todas tus entradas importadas utilizando BeerXML.";
$header_text_046 = "Te has registrado.";
$header_text_047 = "Has alcanzado el límite de entradas.";
$header_text_048 = "Tu entrada no fue agregada.";
$header_text_049 = "Has alcanzado el límite de entradas para la subcategoría.";
$header_text_050 = "Configuración: Instalar Tablas y Datos de la Base de Datos";
$header_text_051 = "Configuración: Crear Usuario Administrador";
$header_text_052 = "Configuración: Agregar Información de Usuario Administrador";
$header_text_053 = "Configuración: Establecer Preferencias del Sitio Web";
$header_text_054 = "Configuración: Agregar Información de la Competencia";
$header_text_055 = "Configuración: Agregar Ubicaciones de Sesiones de Evaluación";
$header_text_056 = "Configuración: Agregar Ubicaciones para Entrega de Entradas";
$header_text_057 = "Configuración: Designar Estilos Aceptados";
$header_text_058 = "Configuración: Establecer Preferencias de Evaluación";
$header_text_059 = "Importar una Entrada Usando BeerXML";
$header_text_060 = "Tu entrada ha sido registrada.";
$header_text_061 = "Tu entrada ha sido confirmada.";
$header_text_065 = "Todas las entradas recibidas han sido verificadas y las que no están asignadas a mesas han sido asignadas.";
$header_text_066 = "Información actualizada con éxito.";
$header_text_067 = "El sufijo que ingresaste ya está en uso, por favor ingresa uno diferente.";
$header_text_068 = "Se ha eliminado la información de la competencia especificada.";
$header_text_069 = "Archivos creados con éxito. ";
$header_text_070 = "Haz clic en el nombre del archivo para verlo.";
$header_text_071 = "Recuerda actualizar tu ".$label_admin_comp_info." y tu ".$label_admin_judging_loc." si estás comenzando una nueva competencia.";
$header_text_072 = "Se eliminó el archivo \"".$filter."\".";
$header_text_073 = "Los registros se han actualizado.";
$header_text_074 = "El nombre de usuario que ingresaste ya está en uso.";
$header_text_075 = "¿Agregar otra ubicación de entrega?";
$header_text_076 = "¿Agregar otra ubicación, fecha u hora de evaluación?";
$header_text_077 = "La mesa que se acaba de definir no tiene estilos asociados.";
$header_text_078 = "Falta uno o más datos requeridos, marcados en rojo a continuación.";
$header_text_079 = "Las direcciones de correo electrónico que ingresaste no coinciden.";
$header_text_080 = "El número de AHA que ingresaste ya está en el sistema.";
$header_text_081 = "Todas las entradas han sido marcadas como pagadas.";
$header_text_082 = "Todas las entradas han sido marcadas como recibidas.";
$header_text_083 = "Todas las entradas no confirmadas ahora están marcadas como confirmadas.";
$header_text_084 = "Se han eliminado todas las asignaciones de participantes.";
$header_text_085 = "No se encontró un número de evaluación que ingresaste en la base de datos.";
$header_text_086 = "Todos los estilos de entrada se han convertido de BJCP 2008 a BJCP 2015.";
$header_text_087 = "Datos eliminados con éxito.";
$header_text_088 = "El juez/catador se ha agregado correctamente. Recuerda asignar al usuario como juez o catador antes de asignarlo a mesas.";
$header_text_089 = "El archivo se ha subido correctamente. Verifica la lista para confirmar.";
$header_text_090 = "El archivo que se intentó subir no es un tipo de archivo aceptado.";
$header_text_091 = "Archivos eliminados con éxito.";
$header_text_092 = "El correo electrónico de prueba se ha generado. Asegúrate de revisar tu carpeta de correo no deseado.";
$header_text_093 = "La contraseña del usuario se ha cambiado. ¡Asegúrate de informarle cuál es su nueva contraseña!";
$header_text_094 = "El cambio de permisos de la carpeta user_images a 755 ha fallado.";
$header_text_095 = "Deberás cambiar los permisos de la carpeta manualmente. Consulta tu programa FTP o la documentación de tu ISP para chmod (permisos de carpeta).";
$header_text_096 = "Se han regenerado los números de evaluación.";
$header_text_097 = "¡Tu instalación se ha configurado con éxito!";
$header_text_098 = "POR RAZONES DE SEGURIDAD, deberías cambiar inmediatamente la variable &#36;setup_free_access en config.php a FALSE.";
$header_text_099 = "De lo contrario, tu instalación y servidor serán vulnerables a brechas de seguridad.";
$header_text_100 = "Inicia sesión ahora para acceder al Panel de Administración";
$header_text_101 = "¡Tu instalación se ha actualizado con éxito!";
$header_text_102 = "Las direcciones de correo electrónico no coinciden.";
$header_text_103 = "Por favor, inicia sesión para acceder a tu cuenta.";
$header_text_104 = "No tienes suficientes privilegios de acceso para ver esta página.";
$header_text_105 = "Se requiere más información para que tu entrada sea aceptada y confirmada.";
$header_text_106 = "Mira la(s) área(s) resaltada(s) en ROJO abajo.";
$header_text_107 = "Por favor, elige un estilo.";
$header_text_108 = "Esta entrada no puede ser aceptada o confirmada hasta que se haya elegido un estilo. Las entradas no confirmadas pueden ser eliminadas del sistema sin previo aviso.";
$header_text_109 = "Te has registrado como catador.";
$header_text_110 = "Todas las entradas han sido desmarcadas como pagadas.";
$header_text_111 = "Todas las entradas han sido desmarcadas como recibidas.";

/**
 * ------------------------------------------------------------------------
 * Alerts
 * ------------------------------------------------------------------------
 */
$alert_text_000 = "Otorgar a los usuarios acceso de administrador de alto nivel y acceso de administrador con precaución.";
$alert_text_001 = "Limpieza de datos completada.";
$alert_text_002 = "La variable &#36;setup_free_access en config.php está actualmente configurada en TRUE.";
$alert_text_003 = "Por razones de seguridad, la configuración debería volver a FALSE. Deberás editar config.php directamente y volver a cargar el archivo en tu servidor.";
$alert_text_005 = "No se han especificado ubicaciones de entrega de entradas.";
$alert_text_006 = "¿Agregar una ubicación de entrega?";
$alert_text_008 = "No se han especificado fechas/ubicaciones de evaluación.";
$alert_text_009 = "¿Agregar una ubicación de evaluación?";
$alert_text_011 = "No se han especificado contactos de competencia.";
$alert_text_012 = "¿Agregar un contacto de competencia?";
$alert_text_014 = "Tu conjunto de estilos actual es BJCP 2008.";
$alert_text_015 = "¿Deseas convertir todas las entradas a BJCP 2015?";
$alert_text_016 = "¿Estás seguro? Esta acción convertirá todas las entradas en la base de datos para que se ajusten a las pautas de estilo BJCP 2015. Las categorías serán 1:1 cuando sea posible, sin embargo, algunos estilos especializados pueden necesitar ser actualizados por el participante.";
$alert_text_017 = "Para conservar la funcionalidad, la conversión debe realizarse <em>antes</em> de definir las mesas.";
$alert_text_019 = "Todas las entradas no confirmadas han sido eliminadas de la base de datos.";
$alert_text_020 = "Las entradas no confirmadas están resaltadas y marcadas con un <span class=\"fa fa-lg fa-exclamation-triangle text-danger\"></span> icono a continuación.";
$alert_text_021 = "Se debe contactar a los propietarios de estas entradas. Estas entradas no se incluyen en los cálculos de tarifas.";
$alert_text_023 = "¿Agregar una ubicación de entrega de entradas?";
$alert_text_024 = $label_yes;
$alert_text_025 = $label_no;
$alert_text_027 = "La inscripción de entradas aún no ha comenzado.";
$alert_text_028 = "La inscripción de entradas ha cerrado.";
$alert_text_029 = "La adición de entradas no está disponible.";
$alert_text_030 = "Se ha alcanzado el límite de inscripción en la competencia.";
$alert_text_031 = "Se ha alcanzado tu límite de inscripción personal.";
$alert_text_032 = "Podrás agregar entradas el ".$entry_open." o después.";
$alert_text_033 = "La inscripción de cuentas se abrirá el ".$reg_open.".";
$alert_text_034 = "Por favor, regresa entonces para registrar tu cuenta.";
$alert_text_036 = "La inscripción de entradas se abrirá el ".$entry_open.".";
$alert_text_037 = "Por favor, regresa entonces para agregar tus entradas al sistema.";
$alert_text_039 = "La inscripción de jueces y catadores se abrirá el ".$judge_open.".";
$alert_text_040 = "Por favor, regresa entonces para registrarte como juez o catador.";
$alert_text_042 = "¡La inscripción de entradas está abierta!";
$alert_text_043 = "Se han agregado un total de ".$total_entries." entradas al sistema hasta ".$current_time.".";
$alert_text_044 = "La inscripción cerrará ";
$alert_text_046 = "¡Casi se ha alcanzado el límite de inscripción!";
$alert_text_047 = "Se han agregado ".$total_entries." de un máximo de ".$row_limits['prefsEntryLimit']." entradas al sistema hasta ".$current_time.".";
$alert_text_049 = "Se ha alcanzado el límite de inscripción.";
$alert_text_050 = "Se ha alcanzado el límite de ".$row_limits['prefsEntryLimit']." entradas. No se aceptarán más entradas.";
$alert_text_052 = "Se ha alcanzado el límite de inscripción de pagos.";
$alert_text_053 = "Se ha alcanzado el límite de ".$row_limits['prefsEntryLimitPaid']." entradas <em>pagadas</em>. No se aceptarán más entradas.";
$alert_text_055 = "La inscripción está cerrada.";
$alert_text_056 = "Si ya registraste una cuenta,";
$alert_text_057 = "inicia sesión aquí"; // Minúsculas y falta de puntuación a propósito
$alert_text_059 = "La inscripción de entradas está cerrada.";
$alert_text_060 = "Se han agregado un total de ".$total_entries." entradas al sistema.";
$alert_text_062 = "La entrega de entradas está cerrada.";
$alert_text_063 = "Las botellas de entrada ya no se aceptan en las ubicaciones de entrega.";
$alert_text_065 = "El envío de entradas está cerrado.";
$alert_text_066 = "Las botellas de entrada ya no se aceptan en la ubicación de envío.";
$alert_text_068 = "La inscripción de ".$j_s_text." está abierta.";
$alert_text_069 = "Regístrate aquí"; // Falta de puntuación a propósito
$alert_text_070 = "La inscripción de ".$j_s_text." cerrará el ".$judge_closed.".";
$alert_text_072 = "Se ha alcanzado el límite de jueces registrados.";
$alert_text_073 = "No se aceptarán más registros de jueces.";
$alert_text_074 = "La inscripción como catador aún está disponible.";
$alert_text_076 = "Se ha alcanzado el límite de catadores registrados.";
$alert_text_077 = "No se aceptarán más registros de catadores.";
$alert_text_078 = "La inscripción como juez aún está disponible.";
$alert_text_080 = "Contraseña incorrecta.";
$alert_text_081 = "Contraseña aceptada.";
$alert_email_valid = "¡El formato de correo electrónico es válido!";
$alert_email_not_valid = "¡El formato de correo electrónico no es válido!";
$alert_email_in_use = "La dirección de correo electrónico que ingresaste ya está en uso. Por favor, elige otra.";
$alert_email_not_in_use = "¡Felicidades! La dirección de correo electrónico que ingresaste no está en uso.";

/**
 * ------------------------------------------------------------------------
 * Public Pages
 * ------------------------------------------------------------------------
 */
$comps_text_000 = "Elige la competencia a la que deseas acceder de la lista siguiente.";
$comps_text_001 = "Competencia actual:";
$comps_text_002 = "No hay competencias con ventanas de inscripción abiertas en este momento.";
$comps_text_003 = "No hay competencias con ventanas de inscripción que se cierren en los próximos 7 días.";

/**
 * ------------------------------------------------------------------------
 * BeerXML
 * ------------------------------------------------------------------------
 */
$beerxml_text_000 = "La importación de entradas no está disponible.";
$beerxml_text_001 = "se ha cargado y la elaboración se ha añadido a tu lista de entradas.";
$beerxml_text_002 = "Lo siento, ese tipo de archivo no está permitido para la carga. Solo se permiten extensiones de archivo .xml.";
$beerxml_text_003 = "El tamaño del archivo supera los 2 MB. Ajusta el tamaño e inténtalo de nuevo.";
$beerxml_text_004 = "Se ha especificado un archivo no válido.";
$beerxml_text_005 = "Sin embargo, no se ha confirmado. Para confirmar tu entrada, accede a tu lista de entradas para obtener más instrucciones. O puedes cargar otra entrada BeerXML a continuación.";
$beerxml_text_006 = "La versión de PHP de tu servidor no admite la función de importación BeerXML.";
$beerxml_text_007 = "Se requiere PHP versión 5.x o superior: este servidor está ejecutando PHP versión ".$php_version.".";
$beerxml_text_008 = "Busca tu archivo compatible con BeerXML en tu disco duro y haz clic en <em>Cargar</em>.";
$beerxml_text_009 = "Seleccionar archivo BeerXML";
$beerxml_text_010 = "Ningún archivo seleccionado...";
$beerxml_text_011 = "entradas agregadas"; // Minúsculas y falta de puntuación a propósito
$beerxml_text_012 = "entrada agregada"; // Minúsculas y falta de puntuación a propósito

/**
 * ------------------------------------------------------------------------
 * Add Entry
 * ------------------------------------------------------------------------
 */
$brew_text_000 = "Haz clic para obtener detalles sobre el estilo"; // Falta de puntuación a propósito
$brew_text_001 = "Los jueces no conocerán el nombre de tu entrada.";
$brew_text_002 = "[desactivado - límite de entradas de estilo alcanzado]"; // Falta de puntuación a propósito
$brew_text_003 = "[desactivado - límite de entradas de estilo alcanzado para el usuario]"; // Falta de puntuación a propósito
$brew_text_004 = "Se requiere información específica sobre el tipo, ingredientes especiales, estilo clásico, fortaleza (para estilos de cerveza) y/o color.";
$brew_text_005 = "Fortaleza requerida (solo para hidromieles)"; // Falta de puntuación a propósito
$brew_text_006 = "Nivel de carbonatación requerido (solo para hidromieles y sidras)"; // Falta de puntuación a propósito
$brew_text_007 = "Nivel de dulzura requerido (solo para hidromieles y sidras)"; // Falta de puntuación a propósito
$brew_text_008 = "Este estilo requiere que proporciones información específica para la entrada.";
$brew_text_009 = "Requisitos para"; // Falta de puntuación a propósito
$brew_text_010 = "Este estilo requiere más información. Por favor, introdúcela en el área proporcionada.";
$brew_text_011 = "Se requiere el nombre de la entrada.";
$brew_text_012 = "***NO REQUERIDO*** Proporciona SOLO si deseas que los jueces consideren completamente lo que escribas aquí al evaluar y puntuar tu entrada. Úsalo para registrar detalles que te gustaría que los jueces consideren al evaluar tu entrada y que NO HAYAS ESPECIFICADO en otros campos (por ejemplo, técnica de maceración, variedad de lúpulo, variedad de miel, variedad de uva, variedad de pera, etc.).";
$brew_text_013 = "NO uses este campo para especificar ingredientes especiales, estilo clásico, fortaleza (para entradas de cerveza) o color.";
$brew_text_014 = "Proporciona SOLO si deseas que los jueces consideren completamente lo que especifiques al evaluar y puntuar tu entrada.";
$brew_text_015 = "Tipo de extracto (por ejemplo, claro, oscuro) o marca.";
$brew_text_016 = "Tipo de grano (por ejemplo, pilsner, pale ale, etc.)";
$brew_text_017 = "Tipo de ingrediente o nombre.";
$brew_text_018 = "Nombre del lúpulo.";
$brew_text_019 = "Solo números (por ejemplo, 12.2, 6.6, etc.).";
$brew_text_020 = "Nombre de la cepa (por ejemplo, 1056 American Ale).";
$brew_text_021 = "Wyeast, White Labs, etc.";
$brew_text_022 = "1 paquete de activación, 2 viales, 2000 ml, etc.";
$brew_text_023 = "Fermentación primaria en días.";
$brew_text_024 = "Descanso de sacarificación, etc.";
$brew_text_025 = "Fermentación secundaria en días.";
$brew_text_026 = "Otra fermentación en días.";

/**
 * ------------------------------------------------------------------------
 * My Account
 * ------------------------------------------------------------------------
 */
$brewer_text_000 = "Por favor, ingresa solo <em>un</em> nombre de persona.";
$brewer_text_001 = "Elige una. Esta pregunta se utilizará para verificar tu identidad en caso de que olvides tu contraseña.";
$brewer_text_003 = "Para ser considerado para una oportunidad de elaboración de GABF Pro-Am, debes ser miembro de la AHA.";
$brewer_text_004 = "Proporciona cualquier información que creas que el organizador de la competencia, el coordinador de jueces o el personal de la competencia debería conocer (por ejemplo, alergias, restricciones dietéticas especiales, talla de camiseta, etc.).";
$brewer_text_005 = "No aplicable / Envío de entradas";
$brewer_text_006 = "¿Estás dispuesto y calificado para servir como juez en esta competencia?";
$brewer_text_007 = "¿Has aprobado el examen de Juez de Hidromieles BJCP?";
$brewer_text_008 = "* El rango <em>No-BJCP</em> es para aquellos que no han tomado el Examen de Ingreso de Juez de Cerveza BJCP y <em>no</em> son cerveceros profesionales.";
$brewer_text_009 = "** El rango <em>Provisional</em> es para aquellos que han tomado y aprobado el Examen de Ingreso de Juez de Cerveza BJCP, pero aún no han tomado el Examen de Juez de Cerveza BJCP.";
$brewer_text_010 = "Solo las dos primeras designaciones aparecerán en las etiquetas de tu hoja de puntuación impresa.";
$brewer_text_011 = "¿Cuántas competencias has servido previamente como <strong>".strtolower($label_judge)."</strong>?";
$brewer_text_012 = "Solo para preferencias. Dejar un estilo sin marcar indica que estás disponible para juzgarlo; no es necesario marcar todos los estilos para los que estás disponible para juzgar.";
$brewer_text_013 = "Haz clic o toca el botón de arriba para expandir la lista de estilos no preferidos para juzgar.";
$brewer_text_014 = "No es necesario marcar los estilos para los cuales tienes entradas; el sistema no te permitirá ser asignado a ninguna mesa en la que tengas entradas.";
$brewer_text_015 = "¿Estás dispuesto a servir como steward en esta competencia?";
$brewer_text_016 = "Mi participación en este juzgamiento es completamente voluntaria. Sé que participar en este juzgamiento implica el consumo de bebidas alcohólicas y que este consumo puede afectar mis percepciones y reacciones.";
$brewer_text_017 = "Haz clic o toca el botón de arriba para expandir la lista de estilos preferidos para juzgar.";
$brewer_text_018 = "Al marcar esta casilla, estoy firmando efectivamente un documento legal en el que acepto la responsabilidad de mi conducta, comportamiento y acciones, y absuelvo completamente a la competencia y a sus organizadores, individual o colectivamente, de la responsabilidad de mi conducta, comportamiento y acciones.";
$brewer_text_019 = "Si planeas servir como juez en cualquier competencia, haz clic o toca el botón de arriba para ingresar tu información relacionada con el juez.";
$brewer_text_020 = "¿Estás dispuesto a servir como miembro del personal en esta competencia?";
$brewer_text_021 = "Los miembros del personal de la competencia son personas que desempeñan diversos roles para ayudar en la organización y ejecución de la competencia antes, durante y después del juzgamiento. Los jueces y stewards también pueden servir como miembros del personal. Los miembros del personal pueden ganar puntos BJCP si la competencia está sancionada.";

/**
 * ------------------------------------------------------------------------
 * Contact
 * ------------------------------------------------------------------------
 */
$contact_text_000 = "Utiliza los enlaces a continuación para contactar a las personas involucradas en la coordinación de esta competencia:";
$contact_text_001 = "Utiliza el formulario a continuación para contactar a un funcionario de la competencia. Todos los campos con un asterisco son obligatorios.";
$contact_text_002 = "Además, se ha enviado una copia a la dirección de correo electrónico que proporcionaste.";
$contact_text_003 = "¿Te gustaría enviar otro mensaje?";

/**
 * ------------------------------------------------------------------------
 * Home Pages
 * ------------------------------------------------------------------------
 */
$default_page_text_000 = "No se han especificado lugares de entrega.";
$default_page_text_001 = "¿Agregar un lugar de entrega?";
$default_page_text_002 = "No se han especificado fechas/ubicaciones de juzgamiento.";
$default_page_text_003 = "¿Agregar una ubicación de juzgamiento?";
$default_page_text_004 = "Entradas Ganadoras";
$default_page_text_005 = "Los ganadores serán publicados a partir del";
$default_page_text_006 = "Bienvenido";
$default_page_text_007 = "Consulta los detalles de tu cuenta y la lista de tus entradas.";
$default_page_text_008 = "Ver los detalles de tu cuenta aquí.";
$default_page_text_009 = "Mejores de Show";
$default_page_text_010 = "Entradas Ganadoras";
$default_page_text_011 = "Solo necesitas registrar tu información una vez y puedes regresar a este sitio para ingresar más cervezas o editar las que hayas registrado.";
$default_page_text_012 = "Incluso puedes pagar tus tarifas de inscripción en línea si lo deseas.";
$default_page_text_013 = "Funcionario de la Competencia";
$default_page_text_014 = "Funcionarios de la Competencia";
$default_page_text_015 = "Puedes enviar un correo electrónico a cualquiera de las siguientes personas a través de ";
$default_page_text_016 = "se enorgullece de tener a los siguientes";
$default_page_text_017 = "para el";
$default_page_text_018 = "Descarga los ganadores de Mejor de Show en formato PDF.";
$default_page_text_019 = "Descarga los ganadores de Mejor de Show en formato HTML.";
$default_page_text_020 = "Descarga las entradas ganadoras en formato PDF.";
$default_page_text_021 = "Descarga las entradas ganadoras en formato HTML.";
$default_page_text_022 = "Gracias por tu interés en el";
$default_page_text_023 = "organizado por";
$reg_open_text_000 = "La inscripción de Jueces y Stewards está";
$reg_open_text_001 = "Abierta";
$reg_open_text_002 = "Si <em>no</em> te has registrado y estás dispuesto a ser voluntario,";
$reg_open_text_003 = "por favor regístrate";
$reg_open_text_004 = "Si <em>ya</em> te has registrado, inicia sesión y elige <em>Editar Cuenta</em> desde el menú Mi Cuenta indicado por el";
$reg_open_text_005 = "ícono en el menú superior.";
$reg_open_text_006 = "Como ya te has registrado, puedes";
$reg_open_text_007 = "ver la información de tu cuenta";
$reg_open_text_008 = "para ver si has indicado que estás dispuesto a juzgar y/o ser steward.";
$reg_open_text_009 = "Si estás dispuesto a ser juez o steward, por favor regresa para inscribirte a partir del";
$reg_open_text_010 = "La inscripción de Entradas está";
$reg_open_text_011 = "Para agregar tus entradas al sistema";
$reg_open_text_012 = "por favor sigue el proceso de inscripción";
$reg_open_text_013 = "si ya tienes una cuenta.";
$reg_open_text_014 = "utiliza el formulario para agregar una entrada";
$reg_open_text_015 = "La inscripción de Jueces está";
$reg_open_text_016 = "La inscripción de Stewards está";
$reg_closed_text_000 = "Gracias y Buena Suerte a Todos los que Participaron en el";
$reg_closed_text_001 = "Hubo";
$reg_closed_text_002 = "entradas juzgadas y";
$reg_closed_text_003 = "participantes, jueces y stewards registrados.";
$reg_closed_text_004 = "entradas registradas y";
$reg_closed_text_005 = "participantes, jueces y stewards registrados.";
$reg_closed_text_006 = "A partir del";
$reg_closed_text_007 = "entradas recibidas y procesadas (este número se actualizará a medida que se recojan las entradas de los lugares de entrega y se organicen para el juzgamiento).";
$reg_closed_text_008 = "Las fechas de juzgamiento de la competencia aún están por determinarse. Por favor, vuelve más tarde.";
$judge_closed_000 = "Gracias a todos los que participaron en el";
$judge_closed_001 = "Se juzgaron";
$judge_closed_002 = "entradas y";
$judge_closed_003 = "participantes, jueces y stewards registrados.";

/**
 * ------------------------------------------------------------------------
 * Entry Info
 * ------------------------------------------------------------------------
 */
$entry_info_text_000 = "Podrás crear tu cuenta a partir del";
$entry_info_text_001 = "hasta";
$entry_info_text_002 = "Los jueces y steward pueden registrarse a partir del";
$entry_info_text_003 = "por entrada";
$entry_info_text_004 = "Puedes crear tu cuenta hoy hasta";
$entry_info_text_005 = "Los jueces y steward pueden registrarse hasta";
$entry_info_text_006 = "Las inscripciones para";
$entry_info_text_007 = "jueces y steward solamente";
$entry_info_text_008 = "serán aceptadas hasta";
$entry_info_text_009 = "La inscripción está <strong class=\"text-danger\">cerrada</strong>.";
$entry_info_text_010 = "Bienvenido";
$entry_info_text_011 = "Consulta los detalles de tu cuenta y la lista de tus entradas.";
$entry_info_text_012 = "Ver tu información de cuenta aquí.";
$entry_info_text_013 = "por entrada después del";
$entry_info_text_014 = "Podrás agregar tus entradas al sistema a partir del";
$entry_info_text_015 = "Puedes agregar tus entradas al sistema hoy hasta";
$entry_info_text_016 = "La inscripción de entradas está <strong class=\"text-danger\">cerrada</strong>.";
$entry_info_text_017 = "para entradas ilimitadas.";
$entry_info_text_018 = "para miembros de AHA.";
$entry_info_text_019 = "Hay un límite de";
$entry_info_text_020 = "entradas para esta competencia.";
$entry_info_text_021 = "Cada participante tiene un límite de";
$entry_info_text_022 = strtolower($label_entry);
$entry_info_text_023 = strtolower($label_entries);
$entry_info_text_024 = "entrada por subcategoría";
$entry_info_text_025 = "entradas por subcategoría";
$entry_info_text_026 = "las excepciones se detallan a continuación";
$entry_info_text_027 = strtolower($label_subcategory);
$entry_info_text_028 = "subcategorías";
$entry_info_text_029 = "entrada para las siguientes";
$entry_info_text_030 = "entradas para las siguientes";
$entry_info_text_031 = "Después de crear tu cuenta y agregar tus entradas al sistema, deberás pagar la tarifa de inscripción. Los métodos de pago aceptados son:";
$entry_info_text_032 = $label_cash;
$entry_info_text_033 = $label_check.", a nombre de";
$entry_info_text_034 = "Tarjeta de crédito/débito y e-check, a través de PayPal";
$entry_info_text_035 = "Las fechas de juzgamiento de la competencia aún están por determinarse. Por favor, vuelve más tarde.";
$entry_info_text_036 = "Se aceptarán botellas de entrada en nuestra ubicación de envío";
$entry_info_text_037 = "Enviar entradas a:";
$entry_info_text_038 = "Empaqueta cuidadosamente tus entradas en una caja resistente. Forra el interior de tu cartón con una bolsa de basura de plástico. Separa y empaqueta cada botella con suficiente material de embalaje. ¡Por favor, no sobrecargues!";
$entry_info_text_039 = "Escribe claramente: <em>Frágil. Este Lado Arriba.</em> en el paquete. Utiliza solo burbujas de aire como material de embalaje.";
$entry_info_text_040 = "Incluye <em>cada</em> etiqueta de tu botella en una pequeña bolsa con cierre antes de adjuntarlas a sus respectivas botellas. De esta manera, el organizador podrá identificar específicamente cuál entrada se ha roto si hay daños durante el envío.";
$entry_info_text_041 = "Se hará todo lo posible para contactar a los participantes cuyas botellas se hayan roto para hacer arreglos para enviar botellas de reemplazo.";
$entry_info_text_042 = "Si vives en los Estados Unidos, ten en cuenta que es <strong>ilegal</strong> enviar tus entradas a través del Servicio Postal de los Estados Unidos (USPS). Las compañías de envío privadas tienen derecho a rechazar tu envío si se les informa que el paquete contiene vidrio y/o bebidas alcohólicas. Ten en cuenta que las entradas enviadas internacionalmente a menudo requieren documentación adecuada por parte de aduanas. Estas entradas pueden ser abiertas y/o devueltas al remitente por funcionarios de aduanas a su discreción. Es tu responsabilidad seguir todas las leyes y regulaciones aplicables.";
$entry_info_text_043 = "Se aceptarán botellas de entrada en nuestros lugares de entrega";
$entry_info_text_044 = "Mapa para";
$entry_info_text_045 = "Haz clic/toca para obtener información de inscripción requerida";
$entry_info_text_046 = "Si el nombre de un estilo está vinculado, tiene requisitos de entrada específicos. Haz clic o toca el nombre para ver los requisitos de la subcategoría.";

/**
 * ------------------------------------------------------------------------
 * My Account Entry List
 * ------------------------------------------------------------------------
 */
$brewer_entries_text_000 = "Hay un problema conocido con la impresión desde el navegador Firefox.";
$brewer_entries_text_001 = "Tienes entradas no confirmadas.";
$brewer_entries_text_002 = "Para cada entrada a continuación con un ícono <span class=\"fa fa-lg fa-exclamation-circle text-danger\"></span>, haz clic en el ícono <span class=\"fa fa-lg fa-pencil text-primary\"></span> para revisar y confirmar todos los datos de tu entrada. Las entradas no confirmadas pueden ser eliminadas del sistema sin previo aviso.";
$brewer_entries_text_003 = "NO PUEDES pagar tus entradas hasta que todas las entradas estén confirmadas.";
$brewer_entries_text_004 = "Tienes entradas que requieren que definas un tipo específico, ingredientes especiales, estilo clásico, fuerza y/o color.";
$brewer_entries_text_005 = "Para cada entrada a continuación con un ícono <span class=\"fa fa-lg fa-exclamation-circle text-danger\"></span>, haz clic en el ícono <span class=\"fa fa-lg fa-pencil text-primary\"></span> para ingresar la información requerida. Las entradas sin un tipo específico, ingredientes especiales, estilo clásico, fuerza y/o color en categorías que lo requieren pueden ser eliminadas por el sistema sin previo aviso.";
$brewer_entries_text_006 = "Descargar hojas de puntuación de los jueces para";
$brewer_entries_text_007 = "Estilo NO Ingresado";
$brewer_entries_text_008 = "Formulario de Entrada y";
$brewer_entries_text_009 = "Etiquetas de Botellas";
$brewer_entries_text_010 = "Imprimir el formulario de receta para";
$brewer_entries_text_011 = "Además, no podrás agregar otra entrada ya que se ha alcanzado el límite de entradas para la competencia. Haz clic en Cancelar en este cuadro y luego edita la entrada si deseas conservarla.";
$brewer_entries_text_012 = "¿Estás seguro de que deseas eliminar la entrada llamada";
$brewer_entries_text_013 = "Podrás agregar entradas a partir del";
$brewer_entries_text_014 = "No has agregado ninguna entrada al sistema.";
$brewer_entries_text_015 = "No puedes eliminar tu entrada en este momento.";

/**
 * ------------------------------------------------------------------------
 * Past Winners
 * ------------------------------------------------------------------------
 */
$past_winners_text_000 = "Ver ganadores anteriores:";

/**
 * ------------------------------------------------------------------------
 * Pay for Entries
 * ------------------------------------------------------------------------
 */
$pay_text_000 = "Dado que las fechas de registro de cuentas, inscripciones, envío y entrega han pasado, ya no se aceptan pagos.";
$pay_text_001 = "Si tienes alguna pregunta, comunícate con un funcionario de la competencia.";
$pay_text_002 = "Las siguientes son tus opciones para pagar tus tarifas de inscripción.";
$pay_text_003 = "Las tarifas son";
$pay_text_004 = "por entrada";
$pay_text_005 = "por entrada después del";
$pay_text_006 = "para entradas ilimitadas";
$pay_text_007 = "Tus tarifas han sido descontadas a";
$pay_text_008 = "El total de tus tarifas de inscripción es";
$pay_text_009 = "Debes pagar";
$pay_text_010 = "Tus tarifas han sido marcadas como pagadas. ¡Gracias!";
$pay_text_011 = "Actualmente tienes";
$pay_text_012 = "entradas confirmadas no pagadas";
$pay_text_013 = "Adjunta un cheque por el monto total de la inscripción a una de tus botellas. Los cheques deben hacerse a nombre de";
$pay_text_014 = "Tu cheque con la marca de carbono o el cheque cobrado es tu recibo de inscripción.";
$pay_text_015 = "Adjunta el pago en efectivo por el monto total de la inscripción en un <em>sobre sellado</em> a una de tus botellas.";
$pay_text_016 = "Tus hojas de puntuación devueltas servirán como tu recibo de inscripción.";
$pay_text_017 = "El correo electrónico de confirmación de tu pago es tu recibo de inscripción. Incluye una copia con tus entradas como comprobante de pago.";
$pay_text_018 = "Haz clic en el botón <em>Pagar con PayPal</em> a continuación para pagar en línea.";
$pay_text_019 = "Ten en cuenta que se agregará una tarifa de transacción de";
$pay_text_020 = "a tu total.";
$pay_text_021 = "Para asegurarte de que tu pago de PayPal esté marcado como <strong>pagado</strong> en <strong>este sitio</strong>, asegúrate de hacer clic en el enlace <em>Volver a...</em> en la pantalla de confirmación de PayPal <strong>después</strong> de haber enviado tu pago. Además, asegúrate de imprimir el recibo de tu pago y adjuntarlo a una de tus botellas de inscripción.";
$pay_text_022 = "Asegúrate de Hacer Clic en <em>Volver a...</em> Después de Pagar tus Tarifas";
$pay_text_023 = "Ingresa el código proporcionado por los organizadores de la competencia para obtener una tarifa de inscripción con descuento.";
$pay_text_024 = $pay_text_010;
$pay_text_025 = "Aún no has registrado ninguna entrada.";
$pay_text_026 = "No puedes pagar tus entradas porque una o más de tus entradas no están confirmadas.";
$pay_text_027 = "Haz clic en <em>Mi Cuenta</em> arriba para revisar tus entradas no confirmadas.";
$pay_text_028 = "Tienes entradas no confirmadas que <em>no</em> se reflejan en tus totales de tarifas a continuación.";
$pay_text_029 = "Ve a tu lista de entradas para confirmar todos los datos de tu entrada. Las entradas no confirmadas pueden ser eliminadas del sistema sin previo aviso.";

/**
 * ------------------------------------------------------------------------
 * QR Code Check-in
 * ------------------------------------------------------------------------
 */

// Ignore the next four lines
if (strpos($view, "^") !== FALSE) {
	$qr_text_019 = sprintf("%04d",$checked_in_numbers[0]);
	if (is_numeric($checked_in_numbers[1])) $qr_text_020 = sprintf("%06d",$checked_in_numbers[1]);
	else $qr_text_020 = $checked_in_numbers[1];
}

$qr_text_000 = $alert_text_080;
$qr_text_001 = $alert_text_081;

// Begin translations here
if (strpos($view, "^") !== FALSE) $qr_text_002 = sprintf("El número de entrada <span class=\"text-danger\">%s</span> está marcado con el número de evaluación <span class=\"text-danger\">%s</span>.",$qr_text_019,$qr_text_020); else $qr_text_002 = "";
$qr_text_003 = "Si este número de evaluación <em>no</em> es correcto, <strong>escanea nuevamente el código y vuelve a ingresar el número de evaluación correcto.";
if (strpos($view, "^") !== FALSE) $qr_text_004 = sprintf("La entrada número %s está registrada.",$qr_text_019); else $qr_text_004 = "";
if (strpos($view, "^") !== FALSE) $qr_text_005 = sprintf("La entrada número %s no se encontró en la base de datos. Coloca las botellas a un lado y avisa al organizador de la competencia.",$qr_text_019); else $qr_text_005 = "";
if (strpos($view, "^") !== FALSE) $qr_text_006 = sprintf("El número de evaluación que ingresaste - %s - ya está asignado a la entrada número %s.",$qr_text_020,$qr_text_019); else $qr_text_006 = "";
$qr_text_007 = "Registro de Entrada mediante Código QR";
$qr_text_008 = "Para registrar las entradas mediante código QR, proporciona la contraseña correcta. Solo necesitarás proporcionar la contraseña una vez por sesión; asegúrate de mantener la aplicación de escaneo de códigos QR abierta.";
$qr_text_009 = "Asigna un número de evaluación y/o un número de caja a la entrada";
$qr_text_010 = "SOLO ingresa un número de evaluación si tu competencia utiliza etiquetas de números de evaluación en la clasificación.";
$qr_text_011 = "Seis números con ceros iniciales, por ejemplo, 000021.";
$qr_text_012 = "Asegúrate de verificar dos veces tu entrada y coloca las etiquetas de número de evaluación correspondientes en cada botella y etiqueta de botella (si corresponde).";
$qr_text_013 = "Los números de evaluación deben tener seis caracteres y no pueden incluir el carácter ^.";
$qr_text_014 = "Esperando la entrada escaneada del código QR.";
$qr_text_016 = "¿Necesitas una aplicación de escaneo de códigos QR? Busca en <a class=\"hide-loader\" href=\"https://play.google.com/store/search?q=qr%20code%20scanner&c=apps&hl=en\" target=\"_blank\">Google Play</a> (Android) o <a class=\"hide-loader\" href=\"https://itunes.apple.com/store/\" target=\"_blank\">iTunes</a> (iOS).";

/**
 * ------------------------------------------------------------------------
 * Registration
 * ------------------------------------------------------------------------
 */
$register_text_000 = "¿Es el voluntario ";
$register_text_001 = "¿Eres ";
$register_text_002 = "Las inscripciones han cerrado.";
$register_text_003 = "Gracias por tu interés.";
$register_text_004 = "La información que proporcionas, más allá de tu nombre y apellido, es estrictamente para fines de registro y contacto.";
$register_text_005 = "Una condición de participación en la competencia es proporcionar esta información. Tu nombre y club pueden mostrarse si una de tus entradas gana, pero no se hará pública ninguna otra información.";
$register_text_006 = "Recordatorio: Solo se te permite inscribirte en una región y, una vez que te hayas registrado en una ubicación, NO podrás cambiarla.";
$register_text_007 = "Registro";
$register_text_008 = "rápido";
$register_text_009 = "un Juez";
$register_text_010 = "un Participante";
$register_text_011 = "Para registrarte, crea tu cuenta en línea completando los campos a continuación.";
$register_text_012 = "Agrega rápidamente un participante al grupo de jueces/organizadores de la competencia. Se utilizará una dirección y número de teléfono ficticios, y se asignará una contraseña predeterminada de <em>bcoem</em> a cada participante agregado a través de esta pantalla.";
$register_text_013 = "La entrada a esta competencia se realiza completamente en línea.";
$register_text_014 = "Para agregar tus entradas y/o indicar que estás dispuesto a ser juez u organizador (los jueces y organizadores también pueden agregar entradas), deberás crear una cuenta en nuestro sistema.";
$register_text_015 = "Tu dirección de correo electrónico será tu nombre de usuario y se utilizará como medio de difusión de información por parte del personal de la competencia. Asegúrate de que sea correcto.";
$register_text_016 = "Una vez que te hayas registrado, podrás avanzar en el proceso de inscripción.";
$register_text_017 = "Cada entrada que agregues será asignada automáticamente por el sistema.";
$register_text_018 = "Elige una. Esta pregunta se utilizará para verificar tu identidad en caso de que olvides tu contraseña.";
$register_text_019 = "Proporciona una dirección de correo electrónico.";
$register_text_020 = "Las direcciones de correo electrónico que ingresaste no coinciden.";
$register_text_021 = "Las direcciones de correo electrónico sirven como nombres de usuario.";
$register_text_022 = "Proporciona una contraseña.";
$register_text_023 = "Proporciona una respuesta a tu pregunta de seguridad.";
$register_text_024 = "¡Haz que tu respuesta de seguridad sea algo que solo tú recuerdes fácilmente!";
$register_text_025 = "Proporciona un nombre.";
$register_text_026 = "Proporciona un apellido.";
$register_text_027 = "un Organizador";
$register_text_028 = "Proporciona una dirección.";
$register_text_029 = "Proporciona una ciudad.";
$register_text_030 = "Proporciona un estado o provincia.";
$register_text_031 = "Proporciona un código postal.";
$register_text_032 = "Proporciona un número de teléfono principal.";
$register_text_033 = "Solo los miembros de la American Homebrewers Association son elegibles para una oportunidad de Great American Beer Festival Pro-Am.";
$register_text_034 = "Para registrarte, debes marcar la casilla que indica que aceptas la declaración de exención de responsabilidad.";

/**
 * ------------------------------------------------------------------------
 * Sidebar
 * ------------------------------------------------------------------------
 */
$sidebar_text_000 = "Se aceptan inscripciones de jueces u organizadores";
$sidebar_text_001 = "Se aceptan inscripciones de organizadores";
$sidebar_text_002 = "Se aceptan inscripciones de jueces";
$sidebar_text_003 = "Ya no se aceptan inscripciones. Se ha alcanzado el límite de jueces y organizadores.";
$sidebar_text_004 = "hasta";
$sidebar_text_005 = "Se aceptan registros de cuentas";
$sidebar_text_006 = "Está abierto solo para jueces u organizadores";
$sidebar_text_007 = "Está abierto solo para organizadores";
$sidebar_text_008 = "Está abierto solo para jueces";
$sidebar_text_009 = "Se aceptan inscripciones de entradas";
$sidebar_text_010 = "Se ha alcanzado el límite de inscripciones pagadas en la competencia.";
$sidebar_text_011 = "Se ha alcanzado el límite de inscripciones en la competencia.";
$sidebar_text_012 = "Ver tu lista de entradas.";
$sidebar_text_013 = "Haz clic aquí para pagar tus tarifas.";
$sidebar_text_014 = "Las tarifas de inscripción no incluyen entradas no confirmadas.";
$sidebar_text_015 = "Tienes entradas no confirmadas, se necesita acción para confirmarlas.";
$sidebar_text_016 = "Ver tu lista de entradas.";
$sidebar_text_017 = "Tienes";
$sidebar_text_018 = "restantes antes de alcanzar el límite de";
$sidebar_text_019 = "por participante";
$sidebar_text_020 = "Has alcanzado el límite de";
$sidebar_text_021 = "en esta competencia";
$sidebar_text_022 = "Se aceptan botellas de entrada en";
$sidebar_text_023 = "la ubicación de envío";
$sidebar_text_024 = "Las fechas de evaluación de la competencia aún están por determinarse. Por favor, vuelve más tarde.";
$sidebar_text_025 = "se han agregado al sistema a partir de";

/**
 * ------------------------------------------------------------------------
 * Styles
 * ------------------------------------------------------------------------
 */
$styles_entry_text_07C = "El concursante debe especificar si la entrada es una Munich Kellerbier (clara, basada en Helles) o una Franconian Kellerbier (ámbar, basada en Märzen). El concursante puede especificar otro tipo de Kellerbier basada en otros estilos base como Pils, Bock, Schwarzbier, pero debe proporcionar una descripción del estilo para los jueces.";
$styles_entry_text_09A = "El concursante debe especificar si la entrada es una variante clara o oscura.";
$styles_entry_text_10C = "El concursante debe especificar si la entrada es una variante clara o oscura.";
$styles_entry_text_21B = "El concursante debe especificar una fuerza (sesión: 3.0-5.0%, estándar: 5.0-7.5%, doble: 7.5-9.5%); si no se especifica ninguna fuerza, se asumirá estándar. El concursante debe especificar el tipo específico de Specialty IPA de la biblioteca de tipos conocidos enumerados en las Pautas de Estilo, o según lo modificado por el sitio web del BJCP; o el concursante debe describir el tipo de Specialty IPA y sus características clave en el formulario de comentarios para que los jueces sepan qué esperar. Los concursantes pueden especificar variedades de lúpulo específicas utilizadas, si creen que los jueces pueden no reconocer las características varietales de los lúpulos más nuevos. Los concursantes pueden especificar una combinación de tipos de IPA definidos (por ejemplo, Black Rye IPA) sin proporcionar descripciones adicionales. Los concursantes pueden utilizar esta categoría para una versión de fuerza diferente de una IPA definida por su propia subcategoría BJCP (por ejemplo, IPA [americana] de sesión), excepto donde ya exista una subcategoría BJCP existente para ese estilo (por ejemplo, IPA doble [americana]). Tipos Definidos Actualmente: Black IPA, Brown IPA, White IPA, Rye IPA, Belgian IPA, Red IPA.";
$styles_entry_text_23F = "Debe especificarse el tipo de fruta utilizada. El cervecero debe declarar un nivel de carbonatación (bajo, medio, alto) y un nivel de dulzura (bajo/nulo, medio, alto).";
$styles_entry_text_24C = "El concursante debe especificar si la bière de garde es rubia, ámbar o marrón. Si no se especifica el color, el juez deberá intentar evaluarlo en función de la observación inicial, esperando un sabor y equilibrio de malta que coincida con el color.";
$styles_entry_text_25B = "El concursante debe especificar la fuerza (mesa, estándar, súper) y el color (claro, oscuro).";
$styles_entry_text_27A = "El concursante debe especificar un estilo con una descripción suministrada por el BJCP, o proporcionar una descripción similar para los jueces de un estilo diferente. Si una cerveza se ingresa solo con un nombre de estilo y sin descripción, es muy poco probable que los jueces entiendan cómo juzgarla. Ejemplos actualmente definidos: Gose, Piwo Grodziskie, Lichtenhainer, Roggenbier, Sahti, Kentucky Common, Pre-Prohibition Lager, Pre-Prohibition Porter, London Brown Ale.";
$styles_entry_text_28A = "El concursante debe especificar un estilo base (estilo BJCP clásico o una familia de estilos genéricos) o proporcionar una descripción de los ingredientes/especificaciones/características deseadas. El concursante debe especificar si se realizó una fermentación 100% con Brett. El concursante puede especificar las cepas de Brettanomyces utilizadas, junto con una breve descripción de su carácter.";
$styles_entry_text_28B = "El concursante debe especificar una descripción de la cerveza, identificando la levadura/bacterias utilizadas y especificando un estilo base o los ingredientes/especificaciones/características del objetivo de la cerveza.";
$styles_entry_text_28C = "El concursante debe especificar el tipo de fruta, especia, hierba o madera utilizada. El concursante debe especificar una descripción de la cerveza, identificando la levadura/bacterias utilizadas y especificando un estilo base o los ingredientes/especificaciones/características del objetivo de la cerveza. Una descripción general de la naturaleza especial de la cerveza puede cubrir todos los elementos requeridos.";
$styles_entry_text_29A = "El concursante debe especificar un estilo base; el estilo declarado no tiene que ser un Estilo Clásico. El concursante debe especificar el tipo de fruta utilizada. Las cervezas agrias de frutas que no son lambics deben ingresarse en la categoría American Wild Ale.";
$styles_entry_text_29B = "El concursante debe especificar un estilo base; el estilo declarado no tiene que ser un Estilo Clásico. El concursante debe especificar el tipo de fruta y especias, hierbas o verduras (SHV) utilizados; no es necesario especificar los ingredientes SHV individuales si se utiliza una mezcla de especias bien conocida (por ejemplo, especias de pastel de manzana).";
$styles_entry_text_29C = "El concursante debe especificar un estilo base; el estilo declarado no tiene que ser un Estilo Clásico. El concursante debe especificar el tipo de fruta utilizada. El concursante debe especificar el tipo de azúcar fermentable adicional o proceso especial empleado.";
$styles_entry_text_30A = "El concursante debe especificar un estilo base; el estilo declarado no tiene que ser un Estilo Clásico. El concursante debe especificar el tipo de especias, hierbas o verduras utilizadas; no es necesario especificar los ingredientes individuales si se utiliza una mezcla de especias bien conocida (por ejemplo, especias de pastel de manzana).";
$styles_entry_text_30B = "El concursante debe especificar un estilo base; el estilo declarado no tiene que ser un Estilo Clásico. El concursante debe especificar el tipo de especias, hierbas o verduras utilizadas; no es necesario especificar los ingredientes individuales si se utiliza una mezcla de especias bien conocida (por ejemplo, especias de pastel de calabaza). La cerveza debe contener especias y puede contener verduras y/o azúcares.";
$styles_entry_text_30C = "El concursante debe especificar un estilo base; el estilo declarado no tiene que ser un Estilo Clásico. El concursante debe especificar el tipo de especias, azúcares, frutas o fermentables adicionales utilizados; no es necesario especificar los ingredientes individuales si se utiliza una mezcla de especias bien conocida (por ejemplo, especias de vino caliente).";
$styles_entry_text_31A = "El concursante debe especificar un estilo base; el estilo declarado no tiene que ser un Estilo Clásico. El concursante debe especificar el tipo de grano alternativo utilizado.";
$styles_entry_text_31B = "El concursante debe especificar un estilo base; el estilo declarado no tiene que ser un Estilo Clásico. El concursante debe especificar el tipo de azúcar utilizado.";
$styles_entry_text_32A = "El concursante debe especificar un estilo base Clásico. El concursante debe especificar el tipo de madera o humo si se percibe un carácter de humo varietal.";
$styles_entry_text_32B = "El concursante debe especificar un estilo base de cerveza; el estilo base de la cerveza no tiene que ser un Estilo Clásico. El concursante debe especificar el tipo de madera o humo si se percibe un carácter de humo varietal. El concursante debe especificar los ingredientes adicionales o procesos que hacen de esta una cerveza ahumada especial.";
$styles_entry_text_33A = "El concursante debe especificar el tipo de madera utilizada y el nivel de tostado (si está carbonizado). El concursante debe especificar el estilo base; el estilo base puede ser un estilo BJCP clásico (es decir, una subcategoría nombrada) o puede ser un tipo de cerveza genérico (por ejemplo, porter, brown ale). Si se ha utilizado una madera inusual, el concursante debe proporcionar una breve descripción de los aspectos sensoriales que la madera agrega a la cerveza.";
$styles_entry_text_33B = "El concursante debe especificar el carácter adicional del alcohol, con información sobre el barril si es relevante para el perfil de sabor final. El concursante debe especificar el estilo base; el estilo base puede ser un estilo BJCP clásico (es decir, una subcategoría nombrada) o puede ser un tipo de cerveza genérico (por ejemplo, porter, brown ale). Si se ha utilizado una madera o ingrediente inusual, el concursante debe proporcionar una breve descripción de los aspectos sensoriales que los ingredientes agregan a la cerveza.";
$styles_entry_text_34A = "El concursante debe especificar el nombre de la cerveza comercial que se está clonando, las especificaciones (estadísticas vitales) de la cerveza y una descripción sensorial breve o una lista de ingredientes utilizados en la elaboración de la cerveza. Sin esta información, los jueces que no estén familiarizados con la cerveza no tendrán una base de comparación.";
$styles_entry_text_34B = "El concursante debe especificar los estilos que se están mezclando. El concursante puede proporcionar una descripción adicional del perfil sensorial de la cerveza o las estadísticas vitales de la cerveza resultante.";
$styles_entry_text_34C = "El concursante debe especificar la naturaleza especial de la cerveza experimental, incluidos los ingredientes o procesos especiales que la hacen no encajar en ninguna otra parte de las pautas. El concursante debe proporcionar estadísticas vitales para la cerveza y una breve descripción sensorial o una lista de ingredientes utilizados en la elaboración de la cerveza. Sin esta información, los jueces no tendrán una base de comparación.";
$styles_entry_text_M1A = "Instrucciones de Entrada: Los concursantes deben especificar el nivel de carbonatación y la fuerza. Se asume que la dulzura es SECA en esta categoría. Los concursantes pueden especificar variedades de miel.";
$styles_entry_text_M1B = "Los concursantes deben especificar el nivel de carbonatación y la fuerza. Se asume que la dulzura es SEMI-DULCE en esta categoría. Los concursantes PUEDEN especificar variedades de miel.";
$styles_entry_text_M1C = "Los concursantes DEBEN especificar el nivel de carbonatación y la fuerza. Se asume que la dulzura es DULCE en esta categoría. Los concursantes PUEDEN especificar variedades de miel.";
$styles_entry_text_M2A = "Los concursantes deben especificar el nivel de carbonatación, la fuerza y la dulzura. Los concursantes pueden especificar variedades de miel. Los concursantes pueden especificar las variedades de manzana utilizadas; si se especifican, se esperará un carácter varietal.";
$styles_entry_text_M2B = "Los concursantes deben especificar el nivel de carbonatación, la fuerza y la dulzura. Los concursantes pueden especificar variedades de miel. Los concursantes pueden especificar las variedades de uva utilizadas; si se especifican, se esperará un carácter varietal. Un pyment con especias (hipocrás) debe ingresarse como una Fruit and Spice Mead. Un pyment hecho con otras frutas debe ingresarse como un Melomel. Un pyment con otros ingredientes debe ingresarse como un Experimental Mead.";
$styles_entry_text_M2C = "Los concursantes deben especificar el nivel de carbonatación, la fuerza y la dulzura. Los concursantes pueden especificar variedades de miel. Los concursantes deben especificar el tipo de fruta utilizada. Un hidromiel de frutas que contenga ingredientes adicionales debe ingresarse como un Experimental Mead.";
$styles_entry_text_M2D = "Los concursantes deben especificar el nivel de carbonatación, la fuerza y la dulzura. Los concursantes pueden especificar variedades de miel. Los concursantes deben especificar el tipo de fruta utilizada. Un hidromiel de frutas de hueso con especias debe ingresarse como un Fruit and Spice Mead. Un hidromiel de frutas de hueso que contenga frutas que no sean de hueso debe ingresarse como un Melomel. Un hidromiel de frutas de hueso que contenga otros ingredientes debe ingresarse como un Experimental Mead.";
$styles_entry_text_M2E = "Los concursantes deben especificar el nivel de carbonatación, la fuerza y la dulzura. Los concursantes pueden especificar variedades de miel. Los concursantes deben especificar el tipo de fruta utilizada. Un melomel con especias debe ingresarse como un Fruit and Spice Mead. Un melomel que contenga otros ingredientes debe ingresarse como un Experimental Mead. Los melomels hechos con manzanas o uvas como única fuente de fruta deben ingresarse como Cysers y Pyments, respectivamente. Los melomels con manzanas o uvas, más otras frutas, deben ingresarse en esta categoría, no como Experimentales.";
$styles_entry_text_M3A = "Los concursantes deben especificar el nivel de carbonatación, la fuerza y la dulzura. Los concursantes pueden especificar variedades de miel. Los concursantes deben especificar los tipos de especias utilizados (aunque las mezclas de especias bien conocidas pueden mencionarse por su nombre común, como especias para pastel de manzana). Los concursantes deben especificar los tipos de frutas utilizadas. Si solo se utilizan combinaciones de especias, ingréselo como un Spice, Herb, or Vegetable Mead. Si solo se utilizan combinaciones de frutas, ingréselo como un Melomel. Si se utilizan otros tipos de ingredientes, ingréselo como un Experimental Mead.";
$styles_entry_text_M3B = "Los concursantes DEBEN especificar el nivel de carbonatación, la fuerza y la dulzura. Los concursantes PUEDEN especificar variedades de miel. Los concursantes DEBEN especificar los tipos de especias utilizados (aunque las mezclas de especias bien conocidas pueden mencionarse por su nombre común, como especias para pastel de manzana).";
$styles_entry_text_M4A = "Los concursantes DEBEN especificar el nivel de carbonatación, la fuerza y la dulzura. Los concursantes PUEDEN especificar variedades de miel. Los concursantes PUEDEN especificar el estilo base o la cerveza o los tipos de maltas utilizados. Los productos con una proporción relativamente baja de miel deben ingresarse en la categoría de cerveza especiada como una Honey Beer.";
$styles_entry_text_M4B = "Los concursantes DEBEN especificar el nivel de carbonatación, la fuerza y la dulzura. Los concursantes PUEDEN especificar variedades de miel. Los concursantes DEBEN especificar la naturaleza especial del hidromiel, proporcionando una descripción del hidromiel para los jueces si no hay tal descripción disponible del BJCP.";
$styles_entry_text_M4C = "Los concursantes DEBEN especificar el nivel de carbonatación, la fuerza y la dulzura. Los concursantes PUEDEN especificar variedades de miel. Los concursantes DEBEN especificar la naturaleza especial del hidromiel, ya sea una combinación de estilos existentes, un hidromiel experimental o alguna otra creación. Cualquier ingrediente especial que aporte un carácter identificable PUEDE declararse.";
$styles_entry_text_C1E = "Los concursantes DEBEN especificar el nivel de carbonatación (3 niveles). Los concursantes DEBEN especificar la dulzura (5 categorías). Los concursantes DEBEN indicar la variedad de pera(s) utilizada.";
$styles_entry_text_C2A = "Los concursantes DEBEN especificar si el cider fue fermentado o envejecido en barrica. Los concursantes DEBEN especificar el nivel de carbonatación (3 niveles). Los concursantes DEBEN especificar la dulzura (5 niveles).";
$styles_entry_text_C2B = "Los concursantes DEBEN especificar el nivel de carbonatación (3 niveles). Los concursantes DEBEN especificar la dulzura (5 categorías). Los concursantes DEBEN especificar todas las frutas y/o jugos de frutas añadidos.";
$styles_entry_text_C2C = "Los concursantes DEBEN especificar el nivel de carbonatación (3 niveles). Los concursantes DEBEN especificar la dulzura (5 niveles).";
$styles_entry_text_C2D = "Los concursantes DEBEN especificar la gravedad inicial, la gravedad final o el azúcar residual, y el nivel de alcohol. Los concursantes DEBEN especificar el nivel de carbonatación (3 niveles).";
$styles_entry_text_C2E = "Los concursantes DEBEN especificar todos los ingredientes. Los concursantes DEBEN especificar el nivel de carbonatación (3 niveles). Los concursantes DEBEN especificar la dulzura (5 categorías). Los concursantes DEBEN especificar todas las botánicas añadidas. Si se utilizan lúpulos, el concursante debe especificar la variedad/variedades utilizada(s).";
$styles_entry_text_C2F = "Los concursantes DEBEN especificar todos los ingredientes. Los concursantes DEBEN especificar el nivel de carbonatación (3 niveles). Los concursantes DEBEN especificar la dulzura (5 categorías).";

/**
 * ------------------------------------------------------------------------
 * User Edit Email
 * ------------------------------------------------------------------------
 */
$user_text_000 = "Se requiere una nueva dirección de correo electrónico y debe tener un formato válido.";
$user_text_001 = "Ingrese la contraseña antigua.";
$user_text_002 = "Ingrese la nueva contraseña.";
$user_text_003 = "Por favor, marque esta casilla si desea proceder con el cambio de su dirección de correo electrónico.";

/**
 * ------------------------------------------------------------------------
 * Volunteers
 * ------------------------------------------------------------------------
 */
$volunteers_text_000 = "Si te has registrado,";
$volunteers_text_001 = "y luego elige <em>Editar cuenta</em> en el menú Mi cuenta indicado por el";
$volunteers_text_002 = "icono en el menú superior";
$volunteers_text_003 = "y";
$volunteers_text_004 = "Si <em>no</em> te has registrado y estás dispuesto a ser juez o steward, por favor regístrate";
$volunteers_text_005 = "Ya que te has registrado previamente,";
$volunteers_text_006 = "accede a tu cuenta";
$volunteers_text_007 = "para ver si te has ofrecido como juez o steward";
$volunteers_text_008 = "Si estás dispuesto a ser juez o steward, por favor regresa a registrarte a partir de";
$volunteers_text_009 = "Si deseas ser voluntario para ser miembro del personal de la competencia, regístrate o actualiza tu cuenta para indicar que deseas ser parte del personal de la competencia.";
$volunteers_text_010 = "";

/**
 * ------------------------------------------------------------------------
 * Login
 * ------------------------------------------------------------------------
 */
$login_text_000 = "Ya has iniciado sesión.";
$login_text_001 = "No hay una dirección de correo electrónico en el sistema que coincida con la que ingresaste.";
$login_text_002 = "¿Intentarlo de nuevo?";
$login_text_003 = "¿Ya has registrado tu cuenta?";
$login_text_004 = "¿Olvidaste tu contraseña?";
$login_text_005 = "Restablecerla";
$login_text_006 = "Para restablecer tu contraseña, ingresa la dirección de correo electrónico que usaste al registrarte.";
$login_text_007 = "Verificar";
$login_text_008 = "Generada aleatoriamente.";
$login_text_009 = "<strong>No disponible.</strong> Tu cuenta fue creada por un administrador y tu &quot;pregunta secreta&quot; fue generada aleatoriamente. Por favor, contacta a un administrador del sitio web para recuperar o cambiar tu contraseña.";
$login_text_010 = "O usa la opción de correo electrónico a continuación.";
$login_text_011 = "Tu pregunta de seguridad es...";
$login_text_012 = "Si no recibiste el correo electrónico,";
$login_text_013 = "Se te enviará un correo electrónico con tu pregunta y respuesta de verificación. Asegúrate de revisar tu carpeta de SPAM.";
$login_text_014 = "haz clic aquí para reenviarlo a";
$login_text_015 = "Si no puedes recordar la respuesta a tu pregunta de seguridad, contacta a un funcionario de la competencia o al administrador del sitio.";
$login_text_016 = "Recíbela por correo electrónico a";

/**
 * ------------------------------------------------------------------------
 * Winners
 * ------------------------------------------------------------------------
 */
$winners_text_000 = "No se han ingresado ganadores para esta tabla. Por favor, vuelve más tarde.";
$winners_text_001 = "Las entradas ganadoras aún no se han publicado. Por favor, vuelve más tarde.";
$winners_text_002 = "Tu estructura de premios seleccionada es otorgar lugares por mesa. Selecciona los lugares de premio para toda la mesa a continuación.";
$winners_text_003 = "Tu estructura de premios seleccionada es otorgar lugares por categoría. Selecciona los lugares de premio para cada categoría general a continuación (puede haber más de una en esta mesa).";
$winners_text_004 = "Tu estructura de premios seleccionada es otorgar lugares por subcategoría. Selecciona los lugares de premio para cada subcategoría a continuación (puede haber más de una en esta mesa).";

/**
 * ------------------------------------------------------------------------
 * Output
 * ------------------------------------------------------------------------
 */
$output_text_000 = "Gracias por participar en nuestra competencia";
$output_text_001 = "Un resumen de tus inscripciones, puntajes y lugares se encuentra a continuación.";
$output_text_002 = "Resumen para";
$output_text_003 = "inscripciones fueron evaluadas";
$output_text_004 = "No se pudieron generar las hojas de puntuación correctamente. Por favor, contacta a los organizadores de la competencia.";
$output_text_005 = "No se han definido asignaciones de jueces/stewards";
$output_text_006 = "para esta ubicación";
$output_text_007 = "Si deseas imprimir tarjetas de mesa en blanco, cierra esta ventana y elige <em>Imprimir Tarjetas de Mesa: Todas las Mesas</em> en el menú <em>Informe</em>.";
$output_text_008 = "Por favor, asegúrate de que tu ID de Juez BJCP sea correcto. Si no lo es, o si tienes uno y no está en la lista, ingrésalo en el formulario.";
$output_text_009 = "Si tu nombre no está en la lista a continuación, regístrate en la(s) hoja(s) adjunta(s).";
$output_text_010 = "Para recibir crédito como juez, asegúrate de ingresar tu ID de Juez BJCP de manera correcta y legible.";
$output_text_011 = "No se han hecho asignaciones.";
$output_text_012 = "Total de inscripciones en esta ubicación";
$output_text_013 = "Ningún participante proporcionó notas a los organizadores.";
$output_text_014 = "Las siguientes son las notas a los organizadores ingresadas por jueces o stewards.";
$output_text_015 = "Ningún participante proporcionó notas a los organizadores.";
$output_text_016 = "Inventario de Inscripciones Post-Juicio";
$output_text_017 = "Si no aparecen inscripciones a continuación, los vuelos en esta mesa no se han asignado a rondas.";
$output_text_018 = "Si faltan inscripciones, todas las inscripciones no se han asignado a un vuelo o ronda, O se han asignado a una ronda diferente.";
$output_text_019 = "Si no aparecen inscripciones a continuación, esta mesa no se ha asignado a una ronda.";
$output_text_020 = "No hay inscripciones elegibles.";
$output_text_021 = "Número de Inscripción / Hoja de Puntuación de Jueces";
$output_text_022 = "Los puntos en este informe se derivan de los Requisitos Oficiales de Competencias con Sanción de BJCP, disponibles en";
$output_text_023 = "incluye Mejor de Show";
$output_text_024 = "Informe de Puntos BJCP";
$output_text_025 = "Total de Puntos de Personal Disponibles";
$output_text_026 = "Los estilos en esta categoría no son aceptados en esta competencia.";
$output_text_027 = "enlace a las Pautas de Estilos del Programa de Certificación de Jueces de Cerveza";

/**
 * ------------------------------------------------------------------------
 * Maintenance
 * ------------------------------------------------------------------------
 */
$maintenance_text_000 = "El administrador del sitio ha puesto el sitio en línea para llevar a cabo labores de mantenimiento.";
$maintenance_text_001 = "Por favor, vuelve más tarde.";

/**
 * ------------------------------------------------------------------------
 * Version 2.1.10-2.1.13 Additions
 * ------------------------------------------------------------------------
 */
$label_entry_numbers = "Número(s) de Inscripción"; // Para Email de PayPal IPN
$label_status = "Estado"; // Para Email de PayPal IPN
$label_transaction_id = "ID de Transacción"; // Para Email de PayPal IPN
$label_organization = "Organización";
$label_ttb = "Número TTB";
$label_username = "Nombre de Usuario";
$label_from = "De"; // Para encabezados de correo electrónico
$label_to = "Para"; // Para encabezados de correo electrónico
$label_varies = "Varía";
$label_styles_accepted = "Estilos Aceptados";
$label_judging_styles = "Estilos de Evaluación";
$label_select_club = "Selecciona o Busca tu Club";
$label_select_style = "Selecciona o Busca el Estilo de tu Inscripción";
$label_select_country = "Selecciona o Busca tu País";
$label_select_dropoff = "Selecciona tu Punto de Entrega";
$label_club_enter = "Ingresa el Nombre del Club";
$label_secret_05 = "¿Cuál es el apellido de soltera de tu abuela materna?";
$label_secret_06 = "¿Cuál fue el primer nombre de tu primera novia o novio?";
$label_secret_07 = "¿Cuál fue la marca y modelo de tu primer vehículo?";
$label_secret_08 = "¿Cuál fue el apellido de tu profesor de tercer grado?";
$label_secret_09 = "¿En qué ciudad o pueblo conociste a tu pareja?";
$label_secret_10 = "¿Cuál fue el primer nombre de tu mejor amigo en sexto grado?";
$label_secret_11 = "¿Cuál es el nombre de tu artista o grupo musical favorito?";
$label_secret_12 = "¿Cuál fue tu apodo de la infancia?";
$label_secret_13 = "¿Cuál es el apellido del profesor que te dio tu primera nota reprobatoria?";
$label_secret_14 = "¿Cuál es el nombre de tu amigo de la infancia favorito?";
$label_secret_15 = "¿En qué ciudad o pueblo se conocieron tu madre y tu padre?";
$label_secret_16 = "¿Cuál fue el número de teléfono de tu infancia que recuerdas mejor, incluyendo el código de área?";
$label_secret_17 = "¿Cuál fue tu lugar favorito para visitar cuando eras niño?";
$label_secret_18 = "¿Dónde estabas cuando diste tu primer beso?";
$label_secret_19 = "¿En qué ciudad o pueblo tuviste tu primer trabajo?";
$label_secret_20 = "¿En qué ciudad o pueblo estabas en Año Nuevo de 2000?";
$label_secret_21 = "¿Cuál es el nombre de una universidad a la que aplicaste pero no asististe?";
$label_secret_22 = "¿Cuál es el primer nombre del chico o la chica a quien besaste por primera vez?";
$label_secret_23 = "¿Cuál era el nombre de tu primer peluche, muñeca o figura de acción?";
$label_secret_24 = "¿En qué ciudad o pueblo conociste a tu cónyuge o pareja?";
$label_secret_25 = "¿En qué calle vivías en primer grado?";
$label_secret_26 = "¿Cuál es la velocidad del aire de una golondrina sin carga?";
$label_secret_27 = "¿Cuál es el nombre de tu programa de televisión cancelado favorito?";
$label_pro = "Profesional";
$label_amateur = "Aficionado";
$label_hosted = "Organizado";
$label_edition = "Edición";
$label_pro_comp_edition = "Edición de Competición Profesional";
$label_amateur_comp_edition = "Edición de Competición Aficionada";
$label_optional_info = "Información Opcional";
$label_or = "O";
$label_admin_payments = "Pagos";
$label_payer = "Pagador";
$label_pay_with_paypal = "Pagar con PayPal";
$label_submit = "Enviar";
$label_id_verification_question = "Pregunta de Verificación de ID";
$label_id_verification_answer = "Respuesta de Verificación de ID";
$label_server = "Servidor";
$label_password_reset = "Restablecer Contraseña";
$label_id_verification_request = "Solicitud de Verificación de ID";
$label_new_password = "Nueva Contraseña";
$label_confirm_password = "Confirmar Contraseña";
$label_with_token = "Con Token";
$label_password_strength = "Fortaleza de la Contraseña";
$label_entry_shipping = "Envío de Inscripción";
$label_jump_to = "Ir a...";
$label_top = "Arriba";
$label_bjcp_cider = "Juez de Sidra BJCP";
$header_text_112 = "No tienes suficientes privilegios de acceso para realizar esta acción.";
$header_text_113 = "Solo puedes editar la información de tu propia cuenta.";
$header_text_114 = "Como administrador, puedes cambiar la información de la cuenta de un usuario a través de Administrador > Inscripciones y Participantes > Gestionar Participantes.";
$header_text_115 = "Los resultados han sido publicados.";
$header_text_116 = "Si no recibes el correo electrónico dentro de un período de tiempo razonable, comunícate con un organizador de la competencia o un administrador del sitio para restablecer tu contraseña.";
$alert_text_082 = "Dado que te registraste como juez o steward, no se te permite agregar inscripciones a tu cuenta. Solo los representantes de una organización pueden agregar inscripciones a sus cuentas.";
$alert_text_083 = "La adición y edición de inscripciones no están disponibles.";
$alert_text_084 = "Como administrador, puedes agregar una inscripción a la cuenta de una organización utilizando el menú desplegable \"Agregar Inscripción Para...\" en la página Administrador > Inscripciones y Participantes > Gestionar Inscripciones.";
$alert_text_085 = "No podrás imprimir la documentación de ninguna inscripción (etiquetas de botellas, etc.) hasta que se haya confirmado el pago y se haya marcado como \"pagada\".";
$brew_text_027 = "Este estilo de la Brewers Association requiere una declaración del cervecero sobre la naturaleza especial del producto. Consulta las <a href=\"https://www.brewersassociation.org/resources/brewers-association-beer-style-guidelines/\" target=\"_blank\">Guías de Estilo de la BA</a> para obtener orientación específica.";
$brew_text_028 = "***NO REQUERIDO*** Agrega información aquí que esté detallada en las pautas de estilo como una característica que PUEDES declarar.";
$brew_text_029 = "Edición de administrador desactivada. Tu perfil se considera un perfil personal y no un perfil organizativo, por lo que no es elegible para agregar inscripciones. Para agregar una inscripción para una organización, accede a la lista de Gestionar Inscripciones y elige una organización en el menú desplegable \"Agregar una Inscripción Para...\".";
$brew_text_030 = "leche / lactosa";
$brew_text_031 = "huevos";
$brew_text_032 = "pescado";
$brew_text_033 = "mariscos";
$brew_text_034 = "frutos secos";
$brew_text_035 = "cacahuetes";
$brew_text_036 = "trigo";
$brew_text_037 = "soja";
$brew_text_038 = "¿Esta inscripción tiene posibles alérgenos alimentarios? Los alérgenos alimentarios comunes incluyen leche (incluida la lactosa), huevos, pescado, mariscos, frutos secos, cacahuetes, trigo, soja, etc.";
$brew_text_039 = "Especifica cualquier alérgeno(s) posible(s).";
$brewer_text_022 = "Podrás identificar a un coproductor cuando agregues tus inscripciones.";
$brewer_text_023 = "Selecciona \"Ninguno\" si no estás afiliado a un club. Selecciona \"Otro\" si tu club no está en la lista, asegúrate de utilizar el cuadro de búsqueda.";
$brewer_text_024 = "Por favor, proporciona tu nombre.";
$brewer_text_025 = "Por favor, proporciona tu apellido.";
$brewer_text_026 = "Por favor, proporciona tu número de teléfono.";
$brewer_text_027 = "Por favor, proporciona tu dirección.";
$brewer_text_028 = "Por favor, proporciona tu ciudad.";
$brewer_text_029 = "Por favor, proporciona tu estado o provincia.";
$brewer_text_030 = "Por favor, proporciona tu código postal o postal.";
$brewer_text_031 = "Por favor, elige tu país.";
$brewer_text_032 = "Por favor, proporciona el nombre de tu organización.";
$brewer_text_033 = "Por favor, elige una pregunta de seguridad.";
$brewer_text_034 = "Por favor, proporciona una respuesta a tu pregunta de seguridad.";
$brewer_text_035 = "¿Has aprobado el examen de Juez de Sidra BJCP?";
$entry_info_text_047 = "Si el nombre de un estilo está vinculado, tiene requisitos de inscripción específicos. Haz clic o toca el nombre para acceder a los estilos de la Brewers Association según se enumeran en su sitio web.";
$brewer_entries_text_016 = "Estilo Ingresado NO Aceptado";
$brewer_entries_text_017 = "Las inscripciones no se mostrarán como recibidas hasta que el personal de la competencia las haya marcado como tales en el sistema. Normalmente, esto ocurre DESPUÉS de que todas las inscripciones se han recopilado en todos los puntos de entrega y envío y se han ordenado.";
$brewer_entries_text_018 = "No podrás imprimir la documentación de esta inscripción (etiquetas de botellas, etc.) hasta que se haya marcado como pagada.";
$brewer_entries_text_019 = "La impresión de la documentación de inscripciones no está disponible en este momento.";
$brewer_entries_text_020 = "La edición de inscripciones no está disponible en este momento. Si deseas editar tu inscripción, comunícate con un organizador de la competencia.";
if (SINGLE) $brewer_info_000 = "Hola";
else $brewer_info_000 = "Gracias por participar en la";
$brewer_info_001 = "Los detalles de tu cuenta se actualizaron por última vez";
$brewer_info_002 = "tómate un momento para <a href=\"#entries\">revisar tus inscripciones</a>";
$brewer_info_003 = "pagar tus tasas de inscripción</a>";
$brewer_info_004 = "por inscripción";
$brewer_info_005 = "Se requiere una membresía de la American Homebrewers Association (AHA) si una de tus inscripciones es seleccionada para un Great American Beer Festival Pro-Am.";
$brewer_info_006 = "Imprime las etiquetas de envío para adjuntar a tu(s) caja(s) de botellas.";
$brewer_info_007 = "Imprime Etiquetas de Envío";
$brewer_info_008 = "Ya te han asignado a una mesa como";
$brewer_info_009 = "Si deseas cambiar tu disponibilidad y/o retirar tu rol, comunícate con el organizador de la competencia o el coordinador de jueces.";
$brewer_info_010 = "Ya te han asignado como";
$brewer_info_011 = "o";
$brewer_info_012 = "Imprime tus etiquetas de hojas de puntuación de jueces ";
$pay_text_030 = "Al hacer clic en el botón \"Entiendo\" a continuación, serás dirigido a PayPal para realizar tu pago. Una vez que hayas <strong>completado</strong> tu pago, PayPal te redirigirá de nuevo a este sitio y te enviará un recibo por correo electrónico de la transacción. <strong>Si tu pago se ha realizado con éxito, tu estado de pago se actualizará automáticamente. Ten en cuenta que es posible que debas esperar unos minutos para que se actualice el estado de pago.</strong> Asegúrate de actualizar la página de pago o acceder a tu lista de inscripciones.";
$pay_text_031 = "A punto de salir de este sitio";
$pay_text_032 = "No es necesario realizar ningún pago. ¡Gracias!";
$pay_text_033 = "Tienes inscripciones sin pagar. Haz clic o toca para pagar tus inscripciones.";
$register_text_035 = "La información que proporcionas más allá del nombre de tu organización es estrictamente para fines de registro y contacto.";
$register_text_036 = "Una condición para la inscripción en la competencia es proporcionar esta información, incluida la dirección de correo electrónico y el número de teléfono de una persona de contacto. El nombre de tu organización puede mostrarse si una de tus inscripciones se coloca, pero no se hará pública ninguna otra información.";
$register_text_037 = "Confirmación de Registro";
$register_text_038 = "Un administrador te ha registrado para una cuenta. Lo siguiente es una confirmación de la información ingresada:";
$register_text_039 = "Gracias por registrar una cuenta. Lo siguiente es una confirmación de la información que proporcionaste:";
$register_text_040 = "Si alguno de los datos anteriores es incorrecto,";
$register_text_041 = "inicia sesión en tu cuenta";
$register_text_042 = "y realiza los cambios necesarios. ¡Buena suerte en la competencia!";
$register_text_043 = "No respondas a este correo electrónico, ya que se genera automáticamente. La cuenta de origen no está activa ni monitoreada.";
$register_text_044 = "Proporciona un nombre de organización.";
$register_text_045 = "Proporciona un nombre de cervecería, brewpub, etc. Asegúrate de consultar la información de la competencia para conocer los tipos de bebidas aceptadas.";
$register_text_046 = "Solo para organizaciones de EE. UU.";
$user_text_004 = "Asegúrate de usar letras mayúsculas y minúsculas, números y caracteres especiales para una contraseña más segura.";
$user_text_005 = "Tu dirección de correo electrónico actual es";
$login_text_017 = "Enviar Mi Respuesta de Pregunta de Seguridad por Correo Electrónico";
$login_text_018 = "Se requiere tu nombre de usuario (dirección de correo electrónico).";
$login_text_019 = "Se requiere tu contraseña.";
$login_text_020 = "El token proporcionado no es válido o ya ha sido utilizado. Utiliza la función de contraseña olvidada nuevamente para generar un nuevo token de restablecimiento de contraseña.";
$login_text_021 = "El token proporcionado ha caducado. Utiliza la función de contraseña olvidada nuevamente para generar un nuevo token de restablecimiento de contraseña.";
$login_text_022 = "El correo electrónico que ingresaste no está asociado con el token proporcionado. Inténtalo de nuevo.";
$login_text_023 = "Las contraseñas no coinciden. Inténtalo de nuevo.";
$login_text_024 = "Se requiere una contraseña de confirmación.";
$login_text_025 = "¿Olvidaste tu contraseña?";
$login_text_026 = "Ingresa la dirección de correo electrónico de tu cuenta y la nueva contraseña a continuación.";
$login_text_027 = "Tu contraseña se ha restablecido con éxito. Ahora puedes iniciar sesión con la nueva contraseña.";
$winners_text_005 = "Los ganadores del Mejor de Show aún no se han publicado. Por favor, vuelva más tarde.";
$paypal_response_text_000 = "Su pago se ha completado. Los detalles de la transacción se proporcionan aquí para su conveniencia.";
$paypal_response_text_001 = "Tenga en cuenta que recibirá una comunicación oficial de PayPal en la dirección de correo electrónico que se muestra a continuación.";
$paypal_response_text_002 = "¡Buena suerte en la competencia!";
$paypal_response_text_003 = "Por favor, no responda a este correo electrónico ya que se genera automáticamente. La cuenta de origen no está activa ni monitoreada.";
$paypal_response_text_004 = "PayPal ha procesado su transacción.";
$paypal_response_text_005 = "El estado de su pago de PayPal es:";
$paypal_response_text_006 = "La respuesta de PayPal fue &quot;inválida&quot;. Por favor, intente hacer su pago nuevamente.";
$paypal_response_text_007 = "Por favor, póngase en contacto con el organizador de la competencia si tiene alguna pregunta.";
$paypal_response_text_008 = "Pago de PayPal Inválido";
$paypal_response_text_009 = "Detalles del Pago de PayPal";
$pwd_email_reset_text_000 = "Se realizó una solicitud para verificar la cuenta en el";
$pwd_email_reset_text_001 = "sitio web utilizando la función de correo electrónico de verificación de ID. Si no inició esto, por favor, póngase en contacto con el organizador de la competencia.";
$pwd_email_reset_text_002 = "La respuesta de verificación de ID distingue entre mayúsculas y minúsculas.";
$pwd_email_reset_text_003 = "Se realizó una solicitud para cambiar su contraseña en el";
$pwd_email_reset_text_004 = "sitio web. Si no lo inició, no se preocupe. Su contraseña no puede restablecerse sin el enlace de abajo.";
$pwd_email_reset_text_005 = "Para restablecer su contraseña, haga clic en el enlace de abajo o cópielo/pegúelo en su navegador.";
$best_brewer_text_000 = "cerveceros participantes";
$best_brewer_text_001 = "MH"; // ¿Qué significa "HM" en este contexto?
$best_brewer_text_002 = "Se han aplicado puntuaciones y desempates de acuerdo con la <a href=\"#\" data-toggle=\"modal\" data-target=\"#scoreMethod\">metodología de puntuación</a>. Los números reflejados están redondeados a la centésima. Coloque el cursor sobre el icono de interrogación (<span class=\"fa fa-question-circle\"></span>) para ver el valor calculado real.";
$best_brewer_text_003 = "Metodología de Puntuación";
$best_brewer_text_004 = "A cada entrada que se coloca se le asigna la siguiente cantidad de puntos:";
$best_brewer_text_005 = "Se han aplicado los siguientes desempates, en orden de prioridad:";
$best_brewer_text_006 = "El mayor número total de primeros, segundos y terceros lugares.";
$best_brewer_text_007 = "El mayor número total de primeros, segundos, terceros, cuartos y menciones honoríficas.";
$best_brewer_text_008 = "El mayor número de primeros lugares.";
$best_brewer_text_009 = "El menor número de inscripciones.";
$best_brewer_text_010 = "La puntuación mínima más alta.";
$best_brewer_text_011 = "La puntuación máxima más alta.";
$best_brewer_text_012 = "La puntuación promedio más alta.";
$best_brewer_text_013 = "No utilizado.";
$best_brewer_text_014 = "clubes participantes";
$dropoff_qualifier_text_001 = "Preste atención a las notas proporcionadas para cada lugar de entrega. Puede haber plazos anteriores para algunos lugares de entrega listados, horas específicas para aceptar entradas, personas específicas con las que dejar sus entradas, etc. <strong class=\"text-danger\">Todos los participantes son responsables de leer la información proporcionada por los organizadores para cada lugar de entrega.</strong>";
$brewer_text_036 = "Dado que ha elegido \"<em>Otro</em>\", asegúrese de que el club que ha ingresado no esté en nuestra lista de alguna forma similar.";
$brewer_text_037 = "Por ejemplo, puede haber ingresado el acrónimo de su club en lugar del nombre completo.";
$brewer_text_038 = "Nombres de club coherentes entre los usuarios son esenciales si se implementa el cálculo de \"Mejor Club\" para esta competición.";
$brewer_text_039 = "El club que ingresó anteriormente no coincide con ninguno de nuestra lista.";
$brewer_text_040 = "Elija de la lista o seleccione <em>Otro</em> e ingrese el nombre de su club.";

/**
 * ------------------------------------------------------------------------
 * Version 2.1.13 Additions
 * ------------------------------------------------------------------------
 */
$entry_info_text_048 = "Para garantizar una evaluación adecuada, el participante debe proporcionar información adicional sobre la bebida.";
$entry_info_text_049 = "Para garantizar una evaluación adecuada, el participante debe proporcionar el nivel de fortaleza de la bebida.";
$entry_info_text_050 = "Para garantizar una evaluación adecuada, el participante debe proporcionar el nivel de carbonatación de la bebida.";
$entry_info_text_051 = "Para garantizar una evaluación adecuada, el participante debe proporcionar el nivel de dulzura de la bebida.";
$entry_info_text_052 = "Si está ingresando en esta categoría, el participante debe proporcionar información adicional para que la entrada sea evaluada con precisión. Cuanta más información, mejor.";
$output_text_028 = "Las siguientes entradas pueden contener alérgenos, según lo informado por los participantes.";
$output_text_029 = "Ningún participante proporcionó información sobre alérgenos para sus entradas.";
$label_this_style = "Este Estilo";
$label_notes = "Notas";
$label_possible_allergens = "Posibles Alérgenos";
$label_please_choose = "Por Favor Elija";
$label_mead_cider_info = "Información de Hidromiel / Sidra";

/**
 * ------------------------------------------------------------------------
 * Version 2.1.14 Additions
 * ------------------------------------------------------------------------
 */
$label_winners = "Ganadores";
$label_unconfirmed_entries = "Entradas No Confirmadas";
$label_recipe = "Receta";
$label_view = "Ver";
$label_number_bottles = "Número de Botellas Requeridas por Entrada";
$label_pro_am = "Pro-Am";
$pay_text_034 = "Se ha alcanzado el límite de entradas pagadas; no se están aceptando más pagos de entradas.";
$bottle_labels_000 = "Las etiquetas no se pueden generar en este momento.";
$bottle_labels_001 = "Adjunte la etiqueta SOLAMENTE con una banda elástica.";
$bottle_labels_002 = "Use cinta adhesiva transparente para adjuntarla al cuerpo de cada botella.";
$bottle_labels_003 = "¡Cubra completamente la etiqueta!";
if (isset($_SESSION['jPrefsBottleNum'])) $bottle_labels_004 = "Tenga en cuenta: se proporcionan 4 etiquetas como cortesía. Esta competencia requiere ".$_SESSION['jPrefsBottleNum']." botellas por entrada. Deseche cualquier etiqueta adicional.";
else $bottle_labels_004 = "Tenga en cuenta: se proporcionan 4 etiquetas como cortesía. Deseche cualquier etiqueta adicional.";
$bottle_labels_005 = "Si falta algún elemento, cierre esta ventana y edite la entrada.";
$bottle_labels_006 = "Espacio reservado para uso del personal de la competencia.";
$bottle_labels_007 = "ESTE FORMULARIO DE RECETA ES SOLO PARA SUS REGISTROS. POR FAVOR, NO INCLUYA UNA COPIA EN SU ENVÍO DE ENTRADA.";
$brew_text_040 = "No es necesario especificar el gluten como alérgeno para ningún estilo de cerveza; se asume que estará presente. Las cervezas sin gluten deben ingresarse en la categoría de Cerveza Sin Gluten (BA) o la categoría de Cerveza con Granos Alternativos (BJCP). Solo especifique gluten como alérgeno en los estilos de hidromiel o sidra si una fuente de fermentables contiene gluten (por ejemplo, malta de cebada, trigo o centeno) o si se utilizó levadura de cervecería.";
$brewer_text_041 = "¿Ya ha sido galardonado con una oportunidad Pro-Am para competir en la próxima Competencia Pro-Am del Gran Festival Americano de la Cerveza?";
$brewer_text_042 = "Si ya ha sido galardonado con un Pro-Am o ha formado parte del personal de elaboración en alguna cervecería, indíquelo aquí. Esto ayudará al personal de la competencia y a los representantes de la cervecería Pro-Am (si corresponde a esta competencia) a elegir entradas Pro-Am de cerveceros que no hayan obtenido uno.";

/**
 * ------------------------------------------------------------------------
 * Version 2.1.15 Additions
 * ------------------------------------------------------------------------
 */
$label_submitting = "Enviando";
$label_additional_info = "Entradas con Información Adicional";
$label_working = "Trabajando";
$output_text_030 = "Por favor, espere.";
$brewer_entries_text_021 = "Marque las entradas para imprimir sus etiquetas de botellas. Seleccione la casilla superior para marcar o desmarcar todas las casillas de la columna.";
$brewer_entries_text_022 = "Imprimir Todas las Etiquetas de Botellas de las Entradas Marcadas";
$brewer_entries_text_023 = "Las etiquetas de botellas se abrirán en una nueva pestaña o ventana.";
$brewer_entries_text_024 = "Imprimir Etiquetas de Botellas";

/**
 * ------------------------------------------------------------------------
 * Version 2.1.18 Additions
 * ------------------------------------------------------------------------
 */
$output_text_031 = "Presione Esc para ocultar.";
$styles_entry_text_21X = "El participante DEBE especificar una fortaleza (sesión: 3.0-5.0%, estándar: 5.0-7.5%, doble: 7.5-9.5%).";
$styles_entry_text_PRX4 = "El participante debe especificar el tipo de fruta(s) fresca(s) utilizada(s).";

/**
 * ------------------------------------------------------------------------
 * Version 2.1.19 Additions
 * ------------------------------------------------------------------------
 */
$output_text_032 = "El recuento de entradas solo refleja a los participantes que indicaron esta ubicación en su perfil de cuenta. El número real de entradas puede ser mayor o menor.";
$brewer_text_043 = "¿O, has estado o estás empleado en el personal de elaboración de alguna cervecería? Esto incluye puestos de elaboración, así como técnicos de laboratorio, personal de bodega, personal de embotellado/envasado, etc. Los empleados actuales y anteriores del personal de elaboración no son elegibles para participar en la competencia Pro-Am del Gran Festival Americano de la Cerveza.";
$label_entrant_reg = "Registro de Participante";
$sidebar_text_026 = "están en el sistema a partir del";
$label_paid_entries = "Entradas Pagadas";

/**
 * ------------------------------------------------------------------------
 * Version 2.2.0 Additions
 * ------------------------------------------------------------------------
 */
$alert_text_086 = "Internet Explorer no es compatible con BCOE&M: las características y funciones no se mostrarán correctamente y tu experiencia no será óptima. Por favor, actualiza a un navegador más nuevo.";
$alert_text_087 = "Para una experiencia óptima y para que todas las características y funciones se ejecuten correctamente, habilita JavaScript para continuar utilizando este sitio. De lo contrario, ocurrirán comportamientos inesperados.";
$alert_text_088 = "La Presentación de Premios estará disponible públicamente después de que se publiquen los resultados.";
$alert_text_089 = "Los datos archivados no están disponibles.";
$brewer_entries_text_025 = "Ver o Imprimir hojas de puntuación de los jueces";
$brewer_info_013 = "Has sido asignado como juez.";
$brewer_info_014 = "Accede al Panel de Jueces utilizando el botón de abajo para ingresar las evaluaciones de las entradas asignadas a ti.";
$contact_text_004 = "Los organizadores de la competencia no han especificado ningún contacto.";
$label_thank_you = "Gracias";
$label_congrats_winners = "Felicidades a todos los ganadores de medallas";
$label_placing_entries = "Entradas Premiadas";
$label_by_the_numbers = "Por los Números";
$label_launch_pres = "Iniciar Presentación de Premios";
$label_entrants = "Participantes";
$label_judging_dashboard = "Panel de Jueces";
$label_table_assignments = "Asignaciones de Mesas";
$label_table = "Mesa";
$label_edit = "Editar";
$label_add = "Agregar";
$label_disabled = "Deshabilitado";
$label_judging_scoresheet = "Hoja de Puntuación de Jueces";
$label_checklist_version = "Versión de Lista de Verificación";
$label_classic_version = "Versión Clásica";
$label_structured_version = "Versión Estructurada";
$label_submit_evaluation = "Enviar Evaluación";
$label_edit_evaluation = "Editar Evaluación";
$label_your_score = "Tu Puntuación";
$label_your_assigned_score = "Tu Puntuación de Consenso Ingresada";
$label_assigned_score = "Puntuación de Consenso";
$label_accepted_score = "Puntuación Aceptada Oficial";
$label_recorded_scores = "Puntuaciones de Consenso Ingresadas";
$label_go = "Ir";
$label_go_back = "Volver";
$label_na = "N/A";
$label_evals_submitted = "Evaluaciones Enviadas";
$label_evaluations = "Evaluaciones de Entradas";
$label_submitted_by = "Enviado Por";
$label_attention = "¡Atención!";
$label_unassigned_eval = "Evaluaciones No Asignadas";
$label_head_judge = "Juez Principal";
$label_lead_judge = "Juez Líder";
$label_mini_bos_judge = "Juez Mini-BOS";
$label_view_my_eval = "Ver Mi Evaluación";
$label_view_other_judge_eval = "Ver Evaluación de Otro Juez";
$label_place_awarded = "Lugar Premiado";
$label_honorable_mention = "Mención de Honor";
$label_places_awarded_table = "Lugares Premiados en esta Mesa";
$label_places_awarded_duplicate = "Lugares Premiados Duplicados en esta Mesa";
$evaluation_info_000 = "El grupo de entradas para cada una de las mesas y vuelos asignados a ti se detalla a continuación.";
$evaluation_info_001 = "Esta competencia emplea la evaluación en cola. Si hay más de un par de jueces en tu mesa, evalúa la siguiente entrada en la cola establecida.";
$evaluation_info_002 = "Para asegurar una competencia precisa y fluida, tú y tu pareja(s) de jueces solo deben evaluar las entradas en tu mesa que aún no han sido evaluadas. Consulta con el organizador o coordinador de jueces si tienes alguna pregunta.";
$evaluation_info_003 = "Esperando la aceptación final de un administrador del sitio.";
$evaluation_info_004 = "Tu puntuación de consenso ha sido ingresada.";
$evaluation_info_005 = "Esta entrada <strong>no</strong> forma parte de tu vuelo asignado.";
$evaluation_info_006 = "Edita según sea necesario.";
$evaluation_info_007 = "Para registrar una evaluación, elige entre las siguientes entradas con un botón azul de \"Agregar\".";
$evaluation_info_008 = "Para registrar tu evaluación, selecciona el botón \"Agregar\" correspondiente a una entrada. Solo están disponibles mesas para sesiones de evaluación pasadas y actuales.";
$evaluation_info_009 = "Has sido asignado como juez, pero no has sido asignado a ninguna mesa(s) o vuelo(s) en el sistema. Consulta con el organizador o coordinador de jueces.";
$evaluation_info_010 = "Esta entrada forma parte de tu vuelo asignado.";
$evaluation_info_011 = "Añade una evaluación para una entrada que no te ha sido asignada.";
$evaluation_info_012 = "Usa esto solo cuando te pidan evaluar una entrada que no te haya sido asignada en el sistema.";
$evaluation_info_013 = "No se encontró la entrada.";
$evaluation_info_014 = "Verifica el número de entrada de seis caracteres y vuelve a intentarlo.";
$evaluation_info_015 = "Asegúrate de que el número tenga una longitud de 6 dígitos.";
$evaluation_info_016 = "No se han enviado evaluaciones.";
$evaluation_info_017 = "Las puntuaciones de consenso ingresadas por los jueces no coinciden.";
$evaluation_info_018 = "Se necesita verificación para las siguientes entradas:";
$evaluation_info_019 = "Las siguientes entradas solo tienen una evaluación enviada:";
$evaluation_info_020 = "Tu Panel de Jueces estará disponible"; // Puntuación omitida intencionadamente
$evaluation_info_021 = "para agregar evaluaciones de entradas asignadas a ti"; // Puntuación omitida intencionadamente
$evaluation_info_022 = "La evaluación y el envío están cerrados.";
$evaluation_info_023 = "Si tienes alguna pregunta, comunícate con el organizador de la competencia o el coordinador de jueces.";
$evaluation_info_024 = "Has sido asignado a las siguientes mesas. <strong>Las listas de entradas para cada mesa solo se mostrarán en sesiones de evaluación pasadas y actuales.</strong>";
$evaluation_info_025 = "Jueces asignados a esta mesa:";
$evaluation_info_026 = "Todas las puntuaciones de consenso ingresadas por los jueces coinciden.";
$evaluation_info_027 = "Entradas que has completado o que un administrador ha ingresado en tu nombre, que no te fueron asignadas en el sistema.";
$evaluation_info_028 = "La sesión de evaluación ha finalizado.";
$evaluation_info_029 = "Se han otorgado lugares duplicados en las siguientes mesas:";
$evaluation_info_030 = "Los administradores deberán ingresar las entradas premiadas que queden.";
$evaluation_info_031 = "se han agregado evaluaciones de jueces";
$evaluation_info_032 = "Se han enviado múltiples evaluaciones para una sola entrada por un juez.";
$evaluation_info_033 = "Si bien esto es un acontecimiento inusual, una evaluación duplicada puede enviarse debido a problemas de conectividad, etc. Debería aceptarse oficialmente una sola evaluación registrada por cada juez, y todas las demás deben eliminarse para evitar confusiones entre los participantes.";
$evaluation_info_034 = "Cuando evalúes estilos de tipo especialidad, utiliza esta línea para comentar sobre características únicas, como frutas, especias, fermentables, acidez, etc.";
$evaluation_info_035 = "Proporciona comentarios sobre el estilo, la receta, el proceso y el placer de beber. Incluye sugerencias útiles para el cervecero.";
if (isset($_SESSION['jPrefsScoreDispMax'])) $evaluation_info_036 = "Una o más puntuaciones de jueces están fuera del rango de puntuación aceptable. El rango aceptable es de ".$_SESSION['jPrefsScoreDispMax']. " puntos o menos.";
$evaluation_info_037 = "Todas las mesas en esta mesa parecen estar completas.";
$evaluation_info_038 = "Como Juez Principal, es tu responsabilidad verificar que las puntuaciones de consenso de cada entrada coincidan, asegurarte de que todas las puntuaciones de los jueces estén dentro del rango adecuado y otorgar lugares a las entradas designadas.";
$evaluation_info_039 = "Entradas en esta mesa:";
$evaluation_info_040 = "Entradas puntuadas en esta mesa:";
$evaluation_info_041 = "Entradas puntuadas en tu vuelo:";
$evaluation_info_042 = "Tus entradas puntuadas:";
$evaluation_info_043 = "Jueces con evaluaciones en esta mesa:";

$label_submitted = "Enviado";
$label_ordinal_position = "Posición Ordinal en la Ronda";
$label_alcoholic = "Alcohólico";
$descr_alcoholic = "El aroma, sabor y efecto cálido del etanol y alcoholes superiores. A veces descrito como 'ardiente'.";
$descr_alcoholic_mead = "El efecto del etanol. Calentador. Ardiente.";
$label_metallic = "Metálico";
$descr_metallic = "Sabor a metálico, como de estaño, moneda de cobre, hierro o sangre.";
$label_oxidized = "Oxidado";
$descr_oxidized = "Cualquier combinación de aromas y sabores rancios, vinosos, a cartón, a papel o similares. Rancio.";
$descr_oxidized_cider = "Rancidez, aroma/sabor a jerez, pasas o fruta magullada.";
$label_phenolic = "Fenólico";
$descr_phenolic = "Aroma y/o sabor picante (clavo, pimienta), ahumado, plástico, cinta adhesiva de plástico y/o medicinal (clorofenólico).";
$label_vegetal = "Vegetal";
$descr_vegetal = "Aroma y sabor a vegetales cocidos, enlatados o podridos (repollo, cebolla, apio, espárragos, etc.).";
$label_astringent = "Astringente";
$descr_astringent = "Sensación de sequedad, aspereza y/o sequedad prolongada en el final/después del sabor; granulosidad áspera; rusticidad.";
$descr_astringent_cider = "Sensación de sequedad en la boca similar a masticar una bolsita de té. Debe estar en equilibrio si está presente.";
$label_acetaldehyde = "Acetaldehído";
$descr_acetaldehyde = "Aroma y sabor a manzana verde.";
$label_diacetyl = "Diacetilo";
$descr_diacetyl = "Aroma y sabor a mantequilla artificial, caramelo o toffee. A veces se percibe como una sensación viscosa en la lengua.";
$descr_diacetyl_cider = "Aroma o sabor a mantequilla o caramelo.";
$label_dms = "DMS (Dimetilsulfuro)";
$descr_dms = "A niveles bajos, aroma y sabor dulce a maíz cocido o enlatado.";
$label_estery = "Esterificado";
$descr_estery = "Aroma y/o sabor a ésteres (frutas, sabores de frutas o rosas).";
$label_grassy = "Herbáceo";
$descr_grassy = "Aroma/sabor a césped recién cortado o hojas verdes.";
$label_light_struck = "Afectado por la Luz";
$descr_light_struck = "Similar al aroma de un zorrillo.";
$label_musty = "Mofado";
$descr_musty = "Aromas/sabores rancios, mofados o a moho.";
$label_solvent = "Disolvente";
$descr_solvent = "Aromas y sabores a alcoholes superiores (alcoholes fusel). Similar al acetona o al aroma de disolvente de barniz.";
$label_sour_acidic = "Ácido/Acido";
$descr_sour_acidic = "Acidez en aroma y sabor. Puede ser agudo y limpio (ácido láctico) o similar al vinagre (ácido acético).";
$label_sulfur = "Azufre";
$descr_sulfur = "Aroma a huevos podridos o fósforos encendidos.";
$label_sulfury = "Sulfuroso";
$label_yeasty = "Levadura";
$descr_yeasty = "Aroma o sabor a pan, sulfuroso o similar a levadura.";
$label_acetified = "Acetificado (Acidez Volátil, AV)";
$descr_acetified = "Acetato de etilo (disolvente, esmalte de uñas) o ácido acético (vinagre, irritante en la parte posterior de la garganta).";
$label_acidic = "Ácido";
$descr_acidic = "Sabor agrio. Típicamente debido a uno de varios ácidos: málico, láctico o cítrico. Debe estar en equilibrio.";
$descr_acidic_mead = "Sabor y aroma ácido y limpio debido a un bajo pH. Típicamente debido a uno de varios ácidos: málico, láctico, gluconico o cítrico.";
$label_bitter = "Amargo";
$descr_bitter = "Un sabor agudo que es desagradable en niveles altos.";
$label_farmyard = "Establo";
$descr_farmyard = "Aroma a estiércol (de vaca o cerdo) o establo (cuadra de caballo en un día cálido).";
$label_fruity = "Frutal";
$descr_fruity = "El aroma y sabor de frutas frescas que pueden ser apropiados en algunos estilos y no en otros.";
$descr_fruity_mead = "Sabor y aroma a ésteres a menudo derivados de frutas en un melomel. El plátano y la piña suelen ser sabores no deseados.";
$label_mousy = "Ratón";
$descr_mousy = "Sabor evocador del olor de la madriguera/jaula de un roedor.";
$label_oaky = "Roble";
$descr_oaky = "Un sabor o aroma debido a un período prolongado de tiempo en una barrica o en virutas de madera. 'Carácter de barrica'.";
$label_oily_ropy = "Aceitoso/Viscoso";
$descr_oily_ropy = "Un brillo en la apariencia visual, como un desagradable carácter viscoso que progresa hacia un carácter viscoso.";
$label_spicy_smoky = "Especiado/ahumado";
$descr_spicy_smoky = "Especias, clavos, ahumado, jamón.";
$label_sulfide = "Sulfuro";
$descr_sulfide = "Huevos podridos, de problemas de fermentación.";
$label_sulfite = "Sulfito";
$descr_sulfite = "Fósforos encendidos, de sulfitado excesivo/reciente.";
$label_sweet = "Dulce";
$descr_sweet = "Sabor básico de azúcar. Debe estar en equilibrio si está presente.";
$label_thin = "Delgado";
$descr_thin = "Acuoso. Falta de cuerpo o 'sustancia'.";
$label_acetic = "Acético";
$descr_acetic = "A vinagre, ácido acético, agudo.";
$label_chemical = "Químico";
$descr_chemical = "Sabor a vitaminas, nutrientes o productos químicos.";
$label_cloying = "Empalagoso";
$descr_cloying = "Empalagoso, excesivamente dulce, desequilibrado por ácido/tanino.";
$label_floral = "Floral";
$descr_floral = "El aroma de flores o perfume.";
$label_moldy = "Mohoso";
$descr_moldy = "Aromas/sabores rancios, mohosos o a corcho.";
$label_tannic = "Tanino";
$descr_tannic = "Sensación de sequedad, astringencia y rugosidad en la boca, similar al sabor amargo. Sabor a té fuerte sin azúcar o masticar la piel de una uva.";
$label_waxy = "Ceroso";
$descr_waxy = "Semejante a la cera, grasa, untuoso.";

$label_medicinal = "Medicinal";
$label_spicy = "Especiado";
$label_vinegary = "Avinagrado";
$label_plastic = "Plástico";
$label_smoky = "Ahumado";
$label_inappropriate = "Inapropiado";
$label_possible_points = "Puntos Posibles";
$label_malt = "Malta";
$label_ferm_char = "Carácter de Fermentación";
$label_body = "Cuerpo";
$label_creaminess = "Cremosidad";
$label_astringency = "Astringencia";
$label_warmth = "Calidez";
$label_appearance = "Apariencia";
$label_flavor = "Sabor";
$label_mouthfeel = "Sensación en Boca";
$label_overall_impression = "Impresión General";
$label_balance = "Equilibrio";
$label_finish_aftertaste = "Final/Regusto";
$label_hoppy = "A lúpulo";
$label_malty = "A malta";
$label_comments = "Comentarios";
$label_flaws = "Defectos para el Estilo";
$label_flaw = "Defecto";
$label_flawless = "Sin Defectos";
$label_significant_flaws = "Defectos Significativos";
$label_classic_example = "Ejemplo Clásico";
$label_not_style = "No Cumple con el Estilo";
$label_tech_merit = "Mérito Técnico";
$label_style_accuracy = "Precisión Estilística";
$label_intangibles = "Intangibles";
$label_wonderful = "Maravilloso";
$label_lifeless = "Desprovisto de Vida";
$label_feedback = "Comentarios";
$label_honey = "Miel";
$label_alcohol = "Alcohol";
$label_complexity = "Complejidad";
$label_viscous = "Víscoso";
$label_legs = "Piernas"; // Utilizado para describir el líquido que se adhiere al cristal
$label_clarity = "Claridad";
$label_brilliant = "Brillante";
$label_hazy = "Turbio";
$label_opaque = "Opaco";
$label_fruit = "Fruta";
$label_acidity = "Acidez";
$label_tannin = "Tanino";
$label_white = "Blanco";
$label_straw = "Paja";
$label_yellow = "Amarillo";
$label_gold = "Dorado";
$label_copper = "Cobre";
$label_quick = "Rápido";
$label_long_lasting = "Duradero";
$label_ivory = "Marfil";
$label_beige = "Beige";
$label_tan = "Bronceado";
$label_lacing = "Encaje";
$label_particulate = "Particulado";
$label_black = "Negro";
$label_large = "Grande";
$label_small = "Pequeño";
$label_size = "Tamaño";
$label_retention = "Retención";
$label_head = "Espuma";
$label_head_size = "Tamaño de la Espuma";
$label_head_retention = "Retención de la Espuma";
$label_head_color = "Color de la Espuma";
$label_brettanomyces = "Brettanomyces";
$label_cardboard = "Cartón";
$label_cloudy = "Nublado";
$label_sherry = "Jerez";
$label_harsh = "Áspero";
$label_harshness = "Aspereza";
$label_full = "Completo";
$label_suggested = "Sugerido";
$label_lactic = "Láctico";
$label_smoke = "Humo";
$label_spice = "Especias";
$label_vinous = "Vinoso";
$label_wood = "Madera";
$label_cream = "Crema";
$label_flat = "Plano";
$label_descriptor_defs = "Definiciones de Descriptores";
$label_outstanding = "Excelente";
$descr_outstanding = "Ejemplo de clase mundial del estilo.";
$label_excellent = "Excelente";
$descr_excellent = "Ejemplifica bien el estilo, requiere pequeños ajustes.";
$label_very_good = "Muy Bueno";
$descr_very_good = "Generalmente dentro de los parámetros del estilo, algunos defectos menores.";
$label_good = "Bueno";
$descr_good = "No alcanza el estilo y/o tiene defectos menores.";
$label_fair = "Regular";
$descr_fair = "Sabores/aromas no deseados o deficiencias importantes de estilo. Desagradable.";
$label_problematic = "Problemático";
$descr_problematic = "Dominan los sabores y aromas no deseados. Difícil de beber.";

/**
 * ------------------------------------------------------------------------
 * Version 2.3.0 Additions
 * ------------------------------------------------------------------------
 */
$winners_text_006 = "Por favor, ten en cuenta: los resultados de esta tabla pueden estar incompletos debido a información insuficiente de entradas o estilos.";

$label_elapsed_time = "Tiempo Transcurrido";
$label_judge_score = "Puntuación del Jurado";
$label_judge_consensus_scores = "Puntuación de Consenso del Jurado";
$label_your_consensus_score = "Tu Puntuación de Consenso";
$label_score_range_status = "Estado del Rango de Puntuación";
$label_consensus_caution = "Precaución del Consenso";
$label_consensus_match = "Coincidencia del Consenso";
$label_score_range_caution = "Precaución del Rango de Puntuación de los Jueces";
$label_score_range_ok = "Rango de Puntuación de los Jueces Aprobado";
$label_auto_log_out = "Cierre Automático en";
$label_place_previously_selected = "Lugar Previamnete Seleccionado";
$label_entry_without_eval = "Entrada Sin Evaluación";
$label_entries_with_eval = "Entradas Con Evaluación";
$label_entries_without_eval = "Entradas Sin Evaluación";
$label_judging_close = "Cierre de la Evaluación";
$label_session_expire = "Sesión a Punto de Expirar";
$label_refresh = "Actualizar Esta Página";
$label_stay_here = "Permanecer Aquí";
$label_bottle_inspection = "Inspección de Botellas";
$label_bottle_inspection_comments = "Comentarios de la Inspección de Botellas";
$label_consensus_no_match = "Las Puntuaciones de Consenso no Coinciden";
$label_score_below_courtesy = "La Puntuación Ingresada está por Debajo del Umbral de Cortesía";
$label_score_greater_50 = "La Puntuación Ingresada es Mayor a 50";
$label_score_out_range = "La Puntuación está Fuera del Rango";
$label_score_range = "Rango de Puntuación";
$label_ok = "Aceptar";
$label_esters = "Ésteres";
$label_phenols = "Fenoles";
$label_descriptors = "Descriptores";
$label_grainy = "Afrutado";
$label_caramel = "Caramelo";
$label_bready = "Panificado";
$label_rich = "Rico";
$label_dark_fruit = "Fruta Oscura";
$label_toasty = "Tostado";
$label_roasty = "Tostado Intenso";
$label_burnt = "Quemado";
$label_citrusy = "Cítrico";
$label_earthy = "Terroso";
$label_herbal = "Herbal";
$label_piney = "Aresinado";
$label_woody = "Amaderado";
$label_apple_pear = "Manzana/Pera";
$label_banana = "Banana";
$label_berry = "Bayas";
$label_citrus = "Cítricos";
$label_dried_fruit = "Fruta Seca";
$label_grape = "Uva";
$label_stone_fruit = "Fruta de Hueso";
$label_even = "Uniforme";
$label_gushed = "Desbordante";
$label_hot = "Caliente";
$label_slick = "Resbaladizo";
$label_finish = "Final";
$label_biting = "Abrasivo";
$label_drinkability = "Bebibilidad";
$label_bouquet = "Bouquet";
$label_of = "de";
$label_fault = "Defecto";
$label_weeks = "Semanas";
$label_days = "Días";
$label_scoresheet = "Hoja de Puntuación";
$label_beer_scoresheet = "Hoja de Puntuación de Cerveza";
$label_cider_scoresheet = "Hoja de Puntuación de Sidra";
$label_mead_scoresheet = "Hoja de Puntuación de Hidromiel";
$label_consensus_status = "Estado del Consenso";
$evaluation_info_044 = "Tu puntuación de consenso no coincide con las ingresadas por otros jueces.";
$evaluation_info_045 = "La puntuación de consenso ingresada coincide con las ingresadas por jueces anteriores.";
$evaluation_info_046 = "La diferencia de puntuación es mayor que";
$evaluation_info_047 = "La diferencia de puntuación está dentro del rango aceptable.";
$evaluation_info_048 = "El lugar que especificaste ya ha sido ingresado en la tabla. Por favor, elige otro lugar o ninguno (Ninguno).";
$evaluation_info_049 = "Estas entradas no tienen al menos una evaluación.";
$evaluation_info_050 = "Por favor, proporciona la posición ordinal de la entrada en el vuelo.";
$evaluation_info_051 = "Por favor, proporciona el número total de entradas en el vuelo.";
$evaluation_info_052 = "Tamaño apropiado, tapa, nivel de llenado, eliminación de etiquetas, etc.";
$evaluation_info_053 = "La puntuación de consenso es la puntuación final acordada por todos los jueces que evalúan la entrada. Si la puntuación de consenso es desconocida en este momento, ingresa tu propia puntuación. Si la puntuación de consenso ingresada aquí difiere de las ingresadas por otros jueces, se te notificará.";
$evaluation_info_054 = "Esta entrada avanzó a una ronda de mini-BOS.";
$evaluation_info_055 = "La puntuación de consenso que ingresaste no coincide con las ingresadas por jueces anteriores para esta entrada. Por favor, consulta con otros jueces que evalúan esta entrada y revisa tu puntuación de consenso según sea necesario.";
$evaluation_info_056 = "La puntuación que ingresaste es inferior a 13, <a href=\"https://www.bjcp.org/cep/GreatBeerJudging.pdf\" target=\"_blank\">un umbral de cortesía ampliamente conocido para jueces BJCP</a>. Por favor, consulta con otros jueces y revisa tu puntuación según sea necesario.";
$evaluation_info_057 = "Las puntuaciones deben ser de al menos 5 y no más de 50.";
$evaluation_info_058 = "La puntuación que ingresaste es mayor de 50, la puntuación máxima para cualquier entrada. Por favor, revisa y corrige tu puntuación de consenso.";
$evaluation_info_059 = "La puntuación que proporcionaste para esta entrada está fuera del rango de diferencia de puntuación entre jueces.";
$evaluation_info_060 = "máximo de caracteres";
$evaluation_info_061 = "Por favor, proporciona comentarios.";
$evaluation_info_062 = "Por favor, elige un descriptor.";
$evaluation_info_063 = "Me gustaría terminar esta muestra.";
$evaluation_info_064 = "Me tomaría una pinta de esta cerveza.";
$evaluation_info_065 = "Pagaría por esta cerveza.";
$evaluation_info_066 = "Recomendaría esta cerveza.";
$evaluation_info_067 = "Por favor, proporciona una calificación.";
$evaluation_info_068 = "Por favor, proporciona la puntuación de consenso, mínimo de 5, máximo de 50.";
$evaluation_info_069 = "Al menos dos jueces del vuelo en el que se ingresó tu envío alcanzaron un consenso sobre tu puntuación final asignada. No necesariamente es un promedio de las puntuaciones individuales.";
$evaluation_info_070 = "Basado en la hoja de puntuación BJCP para";
$evaluation_info_071 = "Han transcurrido más de 15 minutos.";
$evaluation_info_072 = "Por defecto, el Cierre Automático se extiende a 30 minutos para las evaluaciones de entradas.";
$alert_text_090 = "Tu sesión expirará en dos minutos. Puedes quedarte en la página actual para terminar tu trabajo antes de que el tiempo expire, actualizar esta página para continuar tu sesión actual (los datos del formulario no guardados pueden perderse) o cerrar sesión.";
$alert_text_091 = "Tu sesión expirará en 30 segundos. Puedes actualizar para continuar tu sesión actual o cerrar sesión.";
$alert_text_092 = "Debe definirse al menos una sesión de evaluación para agregar una tabla.";
$brewer_entries_text_026 = "Las hojas de puntuación de los jueces para esta entrada están en múltiples formatos. Cada formato contiene una o más evaluaciones válidas de esta entrada.";
$qr_text_008 = "Para hacer el check-in de las entradas a través del código QR, por favor proporciona la contraseña correcta. Solo necesitarás proporcionar la contraseña una vez por sesión; asegúrate de mantener el navegador o la aplicación de escaneo de códigos QR abierta.";
$qr_text_015 = "Escanear el siguiente código QR. Cierra esta pestaña del navegador si lo deseas.<br><br>Para los sistemas operativos más nuevos, accede a la aplicación de la cámara de tu dispositivo móvil. Para sistemas operativos más antiguos, abre/vuelve a la aplicación de escaneo.";
$qr_text_017 = "El escaneo de códigos QR está disponible de forma nativa en la mayoría de los sistemas operativos móviles modernos. Simplemente apunta tu cámara al código QR en la etiqueta de la botella y sigue las indicaciones. Para sistemas operativos móviles más antiguos, se requiere una aplicación de escaneo de códigos QR para utilizar esta función.";
$qr_text_018 = "Escanear un código QR ubicado en la etiqueta de una botella, ingresa la contraseña requerida y realiza el check-in de la entrada.";

/**
 * ------------------------------------------------------------------------
 * Version 2.3.2 Additions
 * ------------------------------------------------------------------------
 */

$label_select_state = "Selecciona o Busca tu Estado";
$label_select_below = "Selecciona Abajo";
$output_text_033 = "Cuando envíes tu informe al BJCP, es posible que no todos los miembros del personal reciban puntos. Se sugiere que asignes puntos primero a aquellos con IDs de BJCP.";

$styles_entry_text_PRX3 = "El participante debe especificar la variedad de uva o mosto de uva utilizada.";
$styles_entry_text_C1A = "Los participantes DEBEN especificar el nivel de carbonatación (3 niveles). Los participantes DEBEN especificar el nivel de dulzura (5 categorías). Si el OG está sustancialmente por encima del rango típico, el participante debe explicar, por ejemplo, la variedad particular de manzana que da un jugo de alta gravedad.";
$styles_entry_text_C1B = "Los participantes DEBEN especificar el nivel de carbonatación (3 niveles). Los participantes DEBEN especificar la dulzura (de seco a medio-dulce, 4 niveles). Los participantes PUEDEN especificar la variedad de manzana para un cider de una sola variedad; si se especifica, se esperará el carácter varietal.";
$styles_entry_text_C1C = "Los participantes DEBEN especificar el nivel de carbonatación (3 niveles). Los participantes DEBEN especificar la dulzura (de medio a dulce solamente, 3 niveles). Los participantes PUEDEN especificar la variedad de manzana para un cider de una sola variedad; si se especifica, se esperará el carácter varietal.";
$winners_text_007 = "No hay entradas ganadoras en esta tabla.";

/**
 * ------------------------------------------------------------------------
 * Version 2.4.0 Additions
 * ------------------------------------------------------------------------
 */

$label_entries_to_judge = "Entradas a Evaluar";
$evaluation_info_073 = "Si has cambiado o añadido algún elemento o comentario en esta hoja de puntuación, tus datos pueden perderse si navegas lejos de esta página.";
$evaluation_info_074 = "Si HAS realizado cambios, cierra este cuadro de diálogo, desplázate hacia la parte inferior de la hoja de puntuación y selecciona Enviar Evaluación.";
$evaluation_info_075 = "Si NO HAS realizado ningún cambio, selecciona el botón azul Panel de Evaluación de Abajo.";
$evaluation_info_076 = "Comentario sobre malta, lúpulo, ésteres y otros aromáticos.";
$evaluation_info_077 = "Comentario sobre color, claridad y espuma (retención, color y textura).";
$evaluation_info_078 = "Comentario sobre malta, lúpulo, características de fermentación, equilibrio, final/sabor residual y otras características de sabor.";
$evaluation_info_079 = "Comentario sobre cuerpo, carbonatación, calidez, cremosidad, astringencia y otras sensaciones en el paladar.";
$evaluation_info_080 = "Comentario sobre el placer general de beber la entrada, da sugerencias para mejorar.";

if ((isset($_SESSION['prefsStyleSet'])) && ($_SESSION['prefsStyleSet'] == "BJCP2021")) {
    $styles_entry_text_21B = "El participante DEBE especificar una fuerza (session, estándar, doble); si no se especifica una fuerza, se asumirá la estándar. El participante DEBE especificar el tipo específico de IPA Especial de la lista de Tipos Actualmente Definidos identificados en las Pautas de Estilo, o según enmiendas de Estilos Provisionales en el sitio web de BJCP; O el participante DEBE describir el tipo de IPA Especial y sus características clave en forma de comentario para que los jueces sepan qué esperar. Los participantes PUEDEN especificar variedades de lúpulo específicas utilizadas, si los participantes creen que los jueces pueden no reconocer las características varietales de lúpulos más nuevos. Los participantes PUEDEN especificar una combinación de tipos de IPA definidos (por ejemplo, IPA Negra de Centeno) sin proporcionar descripciones adicionales.";
    $styles_entry_text_24C = "El participante DEBE especificar si es rubia, ámbar o marrón Bière de Garde.";
    $styles_entry_text_25B = "El participante DEBE especificar la fuerza (mesa, estándar, súper) y el color (pálido, oscuro). El participante PUEDE identificar granos característicos utilizados.";
    $styles_entry_text_27A = "Categoría comodín para otras cervezas históricas que NO han sido definidas por el BJCP. El participante DEBE proporcionar una descripción para los jueces del estilo histórico que NO sea uno de los ejemplos de estilo histórico actualmente definidos proporcionados por el BJCP. Ejemplos actualmente definidos: Kellerbier, Kentucky Common, Lichtenhainer, London Brown Ale, Piwo Grodziskie, Pre-Prohibition Lager, Pre-Prohibition Porter, Roggenbier, Sahti. Si una cerveza se presenta solo con un nombre de estilo y sin descripción, es muy poco probable que los jueces entiendan cómo juzgarla.";
    $styles_entry_text_28A = "El participante DEBE especificar o un Estilo Base o proporcionar una descripción de los ingredientes, especificaciones o carácter deseado. El participante PUEDE especificar las cepas de Brett utilizadas.";
    $styles_entry_text_28B = "El participante DEBE especificar el tipo de levadura o bacteria utilizada y proporcionar una descripción del estilo base o ingredientes, especificaciones o carácter objetivo de la cerveza.";
    $styles_entry_text_28C = "El participante DEBE especificar cualquier Ingrediente Tipo Especial (por ejemplo, fruta, especia, hierba o madera) utilizado. El participante DEBE especificar una descripción de la cerveza, identificando un Estilo Base o los ingredientes, especificaciones o carácter objetivo de la cerveza. Una descripción general de la naturaleza especial de la cerveza puede cubrir todos los elementos requeridos.";
    $styles_entry_text_29A = "El participante DEBE especificar el tipo(s) de fruta utilizada. El participante DEBE especificar una descripción de la cerveza, identificando un Estilo Base o los ingredientes, especificaciones o carácter objetivo de la cerveza. Una descripción general de la naturaleza especial de la cerveza puede cubrir todos los elementos requeridos. Las Cervezas de Frutas basadas en un Estilo Clásico deben presentarse en este estilo, excepto Lambic.";
    $styles_entry_text_29B = "El participante DEBE especificar el tipo de fruta y el tipo de SHV utilizado; los ingredientes SHV individuales no necesitan especificarse si se utiliza una mezcla de especias bien conocida (por ejemplo, especias para pastel de manzana). El participante DEBE especificar una descripción de la cerveza, identificando un Estilo Base o los ingredientes, especificaciones o carácter objetivo de la cerveza. Una descripción general de la naturaleza especial de la cerveza puede cubrir todos los elementos requeridos.";
    $styles_entry_text_29C = "El participante DEBE especificar el tipo de fruta utilizada. El participante DEBE especificar el tipo de ingrediente adicional (según la introducción) o proceso especial empleado. El participante DEBE especificar una descripción de la cerveza, identificando un Estilo Base o los ingredientes, especificaciones o carácter objetivo de la cerveza. Una descripción general de la naturaleza especial de la cerveza puede cubrir todos los elementos requeridos.";
    $styles_entry_text_29D = "El participante DEBE especificar el tipo de uva utilizada. El participante PUEDE proporcionar información adicional sobre el estilo base o ingredientes característicos.";
    $styles_entry_text_30A = "El participante DEBE especificar el tipo de especias, hierbas o vegetales utilizados, pero los ingredientes individuales no necesitan especificarse si se utiliza una mezcla de especias bien conocida (por ejemplo, especias para pastel de manzana, curry, chile). El participante DEBE especificar una descripción de la cerveza, identificando un Estilo Base o los ingredientes, especificaciones o carácter objetivo de la cerveza. Una descripción general de la naturaleza especial de la cerveza puede cubrir todos los elementos requeridos.";
    $styles_entry_text_30B = "El participante DEBE especificar el tipo de especias, hierbas o vegetales utilizados; los ingredientes individuales no necesitan especificarse si se utiliza una mezcla de especias bien conocida (por ejemplo, especias para pastel de calabaza). El participante DEBE especificar una descripción de la cerveza, identificando un Estilo Base o los ingredientes, especificaciones o carácter objetivo de la cerveza. Una descripción general de la naturaleza especial de la cerveza puede cubrir todos los elementos requeridos.";
    $styles_entry_text_30C = "El participante DEBE especificar el tipo de especias, azúcares, frutas o fermentables adicionales utilizados; los ingredientes individuales no necesitan especificarse si se utiliza una mezcla de especias bien conocida (por ejemplo, especias para vino caliente). El participante DEBE especificar una descripción de la cerveza, identificando un Estilo Base o los ingredientes, especificaciones o carácter objetivo de la cerveza. Una descripción general de la naturaleza especial de la cerveza puede cubrir todos los elementos requeridos.";
    $styles_entry_text_30D = "El participante DEBE especificar el tipo de SHVs utilizados, pero los ingredientes individuales no necesitan especificarse si se utiliza una mezcla de especias bien conocida (por ejemplo, especias para pastel de manzana, curry, chile). El participante DEBE especificar el tipo de ingrediente adicional (según la introducción) o proceso especial empleado. El participante DEBE especificar una descripción de la cerveza, identificando un Estilo Base o los ingredientes, especificaciones o carácter objetivo de la cerveza. Una descripción general de la naturaleza especial de la cerveza puede cubrir todos los elementos requeridos.";
    $styles_entry_text_31A = "El participante DEBE especificar el tipo de grano alternativo utilizado. El participante DEBE especificar una descripción de la cerveza, identificando un Estilo Base o los ingredientes, especificaciones o carácter objetivo de la cerveza. Una descripción general de la naturaleza especial de la cerveza puede cubrir todos los elementos requeridos.";
    $styles_entry_text_31B = "El participante DEBE especificar el tipo de azúcar utilizado. El participante DEBE especificar una descripción de la cerveza, identificando un Estilo Base o los ingredientes, especificaciones o carácter objetivo de la cerveza. Una descripción general de la naturaleza especial de la cerveza puede cubrir todos los elementos requeridos.";
    $styles_entry_text_32A = "El participante DEBE especificar un Estilo Base. El participante DEBE especificar el tipo de madera o humo si se nota un carácter varietal de humo.";
    $styles_entry_text_32B = "El participante DEBE especificar el tipo de madera o humo si se nota un carácter varietal de humo. El participante DEBE especificar los ingredientes o procesos adicionales que hacen que esta sea una cerveza ahumada especial. El participante DEBE especificar una descripción de la cerveza, identificando un Estilo Base o los ingredientes, especificaciones o carácter objetivo de la cerveza. Una descripción general de la naturaleza especial de la cerveza puede cubrir todos los elementos requeridos.";
    $styles_entry_text_33A = "El participante DEBE especificar el tipo de madera utilizada y el nivel de tostado o carbonizado (si se utiliza). Si se utiliza una madera inusual varietal, el participante DEBE proporcionar una breve descripción de los aspectos sensoriales que la madera agrega a la cerveza. El participante DEBE especificar una descripción de la cerveza, identificando un Estilo Base o los ingredientes, especificaciones o carácter objetivo de la cerveza. Una descripción general de la naturaleza especial de la cerveza puede cubrir todos los elementos requeridos.";
    $styles_entry_text_33B = "El participante DEBE especificar el carácter adicional de alcohol, con información sobre el barril si es relevante para el perfil de sabor final. Si se utiliza una madera o ingrediente inusual, el participante DEBE proporcionar una breve descripción de los aspectos sensoriales que los ingredientes agregan a la cerveza. El participante DEBE especificar una descripción de la cerveza, identificando un Estilo Base o los ingredientes, especificaciones o carácter objetivo de la cerveza. Una descripción general de la naturaleza especial de la cerveza puede cubrir todos los elementos requeridos.";
    $styles_entry_text_34A = "El participante DEBE especificar el nombre de la cerveza comercial, las especificaciones (estadísticas vitales) de la cerveza y una descripción sensorial breve o una lista de ingredientes utilizados en la elaboración de la cerveza. Sin esta información, los jueces que no estén familiarizados con la cerveza no tendrán una base para hacer comparaciones.";
    $styles_entry_text_34B = "El participante DEBE especificar el/los Estilo(s) Base(s) que se están utilizando, y cualquier ingrediente especial, proceso o variación involucrada. El participante PUEDE proporcionar una descripción adicional del perfil sensorial de la cerveza o las estadísticas vitales de la cerveza resultante.";
    $styles_entry_text_PRX3 = "El participante DEBE especificar el tipo de uva utilizada. El participante PUEDE proporcionar información adicional sobre el estilo base o los ingredientes característicos.";
}

/**
 * ------------------------------------------------------------------------
 * Version 2.5.0 Additions
 * ------------------------------------------------------------------------
 */

$register_text_047 = "Su pregunta de seguridad y/o respuesta ha cambiado.";
$register_text_048 = "Si no inició este cambio, su cuenta podría estar comprometida. Debe iniciar sesión de inmediato en su cuenta y cambiar su contraseña además de actualizar su pregunta de seguridad y respuesta.";
$register_text_049 = "Si no puede iniciar sesión en su cuenta, debe comunicarse de inmediato con un administrador del sitio para actualizar su contraseña y otra información vital de la cuenta.";
$register_text_050 = "La respuesta a su pregunta de seguridad está encriptada y no puede ser leída por los administradores del sitio. Debe ingresarla si elige cambiar su pregunta de seguridad y/o respuesta.";
$register_text_051 = "Información de la cuenta actualizada";
$register_text_052 = "Se requiere una respuesta \"Sí\" o \"No\" para cada ubicación a continuación.";

$brewer_text_044 = "¿Desea cambiar su pregunta de seguridad y/o respuesta?";
$brewer_text_045 = "No se han registrado resultados.";
$brewer_text_046 = "Para la entrada de nombre de club en formato libre, no se permiten algunos símbolos, incluyendo el ampersand (&amp;), comillas simples (&#39;), comillas dobles (&quot;) y porcentaje (&#37;).";
$brewer_text_047 = "Si no está disponible para alguna de las sesiones enumeradas a continuación, pero aún puede desempeñar funciones de personal en otra capacidad, mantenga seleccionado \"Sí\" arriba.";
$brewer_text_048 = "Envío de Entradas";
$brewer_text_049 = "Seleccione \"No Aplicable\" si no planea enviar ninguna entrada a la competencia en este momento.";
$brewer_text_050 = "Seleccione \"Envío de Entradas\" si planea empacar y enviar sus entradas a la ubicación de envío proporcionada.";

$label_change_security = "¿Cambiar Pregunta/Respuesta de Seguridad?";
$label_semi_dry = "Semi-Seco";
$label_semi_sweet = "Semi-Dulce";
$label_shipping_location = "Ubicación de Envío";
$label_allergens = "Alergenos";

$volunteers_text_010 = "El personal puede indicar su disponibilidad para las siguientes sesiones que no son de evaluación:";

$evaluation_info_081 = "Comentario sobre la expresión de la miel, el alcohol, las ésteres, la complejidad y otros aromas.";
$evaluation_info_082 = "Comentario sobre el color, la claridad, las piernas y la carbonatación.";
$evaluation_info_083 = "Comentario sobre la miel, la dulzura, la acidez, los taninos, el alcohol, el equilibrio, el cuerpo, la carbonatación, el retrogusto y cualquier ingrediente especial o sabores específicos del estilo.";
$evaluation_info_084 = "Comentario sobre el placer general al beber la entrada, dé sugerencias para mejorar.";
$evaluation_info_085 = "Color (2), claridad (2), nivel de carbonatación (2).";
$evaluation_info_086 = "Expresión de otros ingredientes según corresponda.";
$evaluation_info_087 = "Equilibrio de acidez, dulzura, fuerza alcohólica, cuerpo, carbonatación (si es apropiado) (14),
Otros ingredientes según corresponda (5), Retrogusto (5).";
$evaluation_info_088 = "Comentario sobre el placer general al beber la entrada, dé sugerencias para mejorar.";

$evaluation_info_089 = "Se alcanzó o superó el recuento mínimo de palabras.";
$evaluation_info_090 = "Gracias por proporcionar la evaluación más completa posible. Palabras totales: ";
$evaluation_info_091 = "Palabras mínimas requeridas para sus comentarios: ";
$evaluation_info_092 = "Recuento de palabras hasta ahora: ";
$evaluation_info_093 = "No se ha alcanzado el requisito mínimo de palabras en el campo de Comentarios de Impresión General anterior.";
$evaluation_info_094 = "No se ha alcanzado el requisito mínimo de palabras en uno o más campos de comentarios anteriores.";

/**
 * ------------------------------------------------------------------------
 * Version 2.6.0 Additions
 * ------------------------------------------------------------------------
 */

$label_regional_variation = "Variación Regional";
$label_characteristics = "Características";
$label_intensity = "Intensidad";
$label_quality = "Calidad";
$label_palate = "Paladar";
$label_medium = "Medio";
$label_medium_dry = "Semi Seco";
$label_medium_sweet = "Semi Dulce";
$label_your_score = "Su Puntuación";
$label_summary_overall_impression = "Resumen de la Evaluación e Impresión General";
$label_medal_count = "Cantidad de Medallas en el Grupo";
$label_best_brewer_place = "Mejor Lugar del Cervecero";
$label_industry_affiliations = "Afiliaciones a Organizaciones de la Industria";
$label_deep_gold = "Oro Profundo";
$label_chestnut = "Castaño";
$label_pink = "Rosado";
$label_red = "Rojo";
$label_purple = "Púrpura";
$label_garnet = "Granate";
$label_clear = "Claro";
$label_final_judging_date = "Fecha de Evaluación Final";
$label_entries_judged = "Entradas Evaluadas";
$label_results_export = "Exportar Resultados";
$label_results_export_personal = "Exportar Resultados Personales";

$brew_text_041 = "Opcional: especifique una variación regional (por ejemplo, Lager Mexicana, Lager Holandesa, Lager de Arroz Japonesa, etc.).";

$evaluation_info_095 = "Siguiente sesión de evaluación asignada abierta:";
$evaluation_info_096 = "Para ayudar en la preparación, las mesas/vuelos asignados y las entradas asociadas estarán disponibles diez minutos antes del inicio de una sesión.";
$evaluation_info_097 = "Su próxima sesión de evaluación ya está disponible.";
$evaluation_info_098 = "Actualice para ver.";
$evaluation_info_099 = "Sesiones de evaluación pasadas o actuales:";
$evaluation_info_100 = "Sesiones de evaluación próximas:";
$evaluation_info_101 = "Por favor, proporcione otro descriptor de color.";
$evaluation_info_102 = "Ingrese su puntuación total - máximo de 50. Utilice la guía de puntuación a continuación si es necesario.";
$evaluation_info_103 = "Por favor, proporcione su puntuación - mínimo de 5, máximo de 50.";

$brewer_text_051 = "Seleccione las organizaciones de la industria con las que está afiliado como empleado, voluntario, etc. Esto es para asegurarse de que no haya conflictos de interés al asignar jueces y asistentes para evaluar las entradas.";
$brewer_text_052 = "<strong>Si alguna organización de la industria <u>no</u> está en la lista desplegable anterior, ingrésela aquí.</strong> Separe el nombre de cada organización con coma (,) o punto y coma (;). Algunos símbolos no están permitidos, incluyendo comillas dobles (&quot;) y porcentaje (&#37;).";

/**
 * ------------------------------------------------------------------------
 * Version 2.6.0 Additions
 * ------------------------------------------------------------------------
 */

$evaluation_info_104 = "No todos los jueces indicaron que esta entrada avanzó a la ronda de Mini-BOS. Por favor, verifique y seleccione Sí o No arriba.";
$evaluation_info_105 = "Las siguientes entradas tienen indicaciones desiguales de Mini-BOS por parte de los jueces:";

$label_non_judging = "Sesiones sin Evaluación";

/**
 * ------------------------------------------------------------------------
 * Version 2.6.2 Additions
 * ------------------------------------------------------------------------
 */
$label_mhp_number = "Número de miembro del Programa Master Homebrewer";
$brewer_text_053 = "El Master Homebrewer Program es una organización sin ánimo de lucro creada para promover el dominio de la elaboración de cerveza entre aficionados.";
$best_brewer_text_015 = "Los puntos de cada inscripción se calculan mediante la siguiente fórmula, basada en la utilizada por el Master Homebrewer Program del <a href='https://www.masterhomebrewerprogram.com/circuit-of-america' target='_blank'>Circuit of America</a>:";

/**
 * ------------------------------------------------------------------------
 * Version 2.7.0 Additions
 * ------------------------------------------------------------------------
 */
$label_abv = "Grado alcohólico por volumen (ABV)";
$label_final_gravity = "Gravedad Final";
$label_juice_source = "Fuente(s) de Jugo";
$label_select_all_apply = "Seleccionar Todo lo que Corresponda";
$label_pouring = "Vertido";
$label_pouring_notes = "Notas de Vertido";
$label_rouse_yeast = "Revolver Levadura";
$label_fast = "Rápido";
$label_slow = "Lento";
$label_normal = "Normal";
$label_brewing_partners = "Socios de Elaboración";
$label_entry_edit_deadline = "Fecha límite de Edición de la Entrada";
$brew_text_042 = "Por favor, proporciona el grado alcohólico por volumen hasta el centésimo lugar.";
$brew_text_043 = "Sólo números - se aceptan decimales hasta el centésimo lugar (por ejemplo, 5.2, 12.84, etc.).";
$brew_text_044 = "Por favor, proporciona la gravedad específica final hasta el milésimo lugar (por ejemplo, 0.991, 1.000, 1.007, etc.).";
$brew_text_045 = "Por favor, proporciona la(s) fuente(s) de jugo - selecciona todas las que correspondan.";
$brew_text_046 = "Por favor, especifica la abreviatura de dos letras del estado/provincia de cualquier otra(s) fuente(s) de jugo (por ejemplo, VT, ME, CA, ON, etc.). Separa la abreviatura de cada ubicación con coma (,) o punto y coma (;). Algunos símbolos no están permitidos, incluyendo comillas dobles (&quot;) y porcentaje (&#37;).";
$brew_text_047 = "¿Cómo debería ser vertida tu entrada para los jueces?";
$brew_text_048 = "¿Debería ser revuelta alguna levadura antes de verter?";
$brew_text_049 = "Proporciona información adicional sobre cómo debería ser vertida tu entrada u otros elementos relacionados (por ejemplo, posibles erupciones, etc.).";
$brewer_text_055 = "Selecciona cualquier socio de elaboración con el que estés afiliado. Esto es para asegurarse de que no haya conflictos de interés al asignar jueces y ayudantes para evaluar las entradas."; 
$brewer_text_054 = "<strong>Si el nombre de alguna persona no está listado en el menú desplegable de arriba, ingresa su NOMBRE COMPLETO aquí (por ejemplo, John Doe, Wyatt Earp, Selina Kyle, etc.). Agrega aquí también cualquier nombre de equipo de elaboración.</strong> Separa cada nombre de equipo o persona con coma (,) o punto y coma (;). Algunos símbolos no están permitidos, incluyendo comillas dobles (&quot;) y porcentaje (&#37;).";

$brew_text_050 = "Algunos estilos están desactivados ya que se ha alcanzado el límite para su tipo de estilo correspondiente (por ejemplo, cerveza, hidromiel, sidra, etc.).";
$entry_info_text_053 = "Límites de entradas por tipo de estilo:";
$alert_text_093 = "¡Algunos límites de entradas alcanzados!";
$alert_text_094 = "No se aceptarán más entradas para";
$label_limit = "Límite";
$label_beer = "Cerveza";
$label_mead = "Hidromiel";
$label_cider = "Sidra";
$label_mead_cider = "Hidromiel/Sidra";
$label_wine = "Vino";
$label_rice_wine = "Vino de Arroz";
$label_spirits = "Licores";
$label_kombucha = "Kombucha";
$label_pulque = "Pulque";

$form_required_fields_00 = "No se han completado o seleccionado todos los campos obligatorios.";
$form_required_fields_01 = "Los campos obligatorios que faltan valores están indicados en <strong class=\"text-danger\">rojo</strong> arriba. Por favor, desplázate hacia arriba según sea necesario.";
$form_required_fields_02 = "Este campo es obligatorio.";

$entry_info_text_054 = "Entradas por tipo de estilo y límites asociados:";

$maintenance_text_002 = "Sólo los administradores de nivel superior pueden iniciar sesión cuando el sitio está en modo de mantenimiento.";

$brew_text_054 = "¿De dónde proviene la fruta o jugo de manzana/pera? Por favor, seleccione todas las opciones que correspondan para la bebida base.";
$label_packaging = "Embalaje";
$label_bottle = "Botella";
$label_other_size = "Otro Tamaño";
$label_can = "Lata";
$label_fruit_add_source = "Fuente(s) de Adición de Fruta";
$label_yearly_volume = "Volumen Anual";
$label_gallons = "Galones";
$label_barrels = "Barriles";
$label_hectoliters = "Hectolitros";

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