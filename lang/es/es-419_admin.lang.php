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

$archive_text_000 = "Debido a limitaciones de almacenamiento del servidor, no está disponible la archivación de datos de cuentas de BCOE&amp;M alojadas. Para utilizar el software en una nueva competencia o simplemente para borrar la base de datos de datos, utilice los botones a continuación.";
$archive_text_001 = "Los datos de categoría personalizada, tipo de estilo personalizado, ubicación de entrega, ubicación de evaluación y patrocinador <strong class=\"text-success\">no se borrarán</strong>. Los administradores deberán actualizar estos datos para futuras instancias de la competencia.";
$archive_text_002 = "Opción 1";
$archive_text_003 = "¿Está seguro de que desea borrar los datos de la competencia actual? Esto NO se puede deshacer.";
$archive_text_004 = "Borrar Todos los Datos de Participantes, Entradas, Evaluación y Puntuación";
$archive_text_005 = "Esta opción borra todas las cuentas de participantes que no sean de administrador, así como todos los datos de entrada, evaluación y puntuación, incluyendo todas las hojas de puntuación cargadas. Proporciona un inicio limpio.";
$archive_text_006 = "Opción 2";
$archive_text_007 = "¿Está seguro de que desea borrar los datos de la competencia actual? Esto NO se puede deshacer.";
$archive_text_008 = "Borrar Solo Datos de Entradas, Evaluación y Puntuación";
$archive_text_009 = "Esta opción borra todos los datos de entrada, evaluación y puntuación, incluyendo todas las hojas de puntuación cargadas, pero conserva los datos de los participantes. Útil si no desea que los participantes creen nuevos perfiles de cuenta.";
$archive_text_010 = "Para archivar los datos almacenados actualmente en la base de datos, proporcione un nombre para el archivo. Se sugiere que elija un nombre único para este conjunto de datos. Por ejemplo, si realiza su competencia anualmente, el nombre podría ser el año en que se llevó a cabo. Si organiza competencias sucesivas en una única instalación, el nombre de la competencia y el año podrían servir como nombre.";
$archive_text_011 = "Sólo se permiten caracteres alfanuméricos; los demás se omitirán.";
$archive_text_012 = "Marque la información que le gustaría conservar para su uso en futuras instancias de competencia.";
$archive_text_013 = "¿Está seguro de que desea archivar los datos actuales?";
$archive_text_014 = "Luego, elija qué datos le gustaría conservar.";
$archive_text_015 = "Esto eliminará el archivo llamado";
$archive_text_016 = "También se eliminarán todos los registros asociados.";

/*
 * --------------------- v 2.2.0 -----------------------
 */
$archive_text_017 = "Edite su información de archivo con precaución. Cambiar cualquiera de los siguientes datos puede resultar en un comportamiento inesperado al intentar acceder a los datos archivados.";
$archive_text_018 = "Los archivos se moverán a una subcarpeta con el mismo nombre de su archivo en el directorio user_docs.";
$archive_text_019 = "Listas de ganadores archivadas disponibles para su visualización pública.";
$archive_text_020 = "Por lo general, esto solo debería cambiarse si la lista de ganadores de este archivo se muestra incorrectamente.";
$archive_text_021 = "Las hojas de puntuación en PDF se han guardado para este archivo. Esta es la convención de nomenclatura de cada archivo utilizada por el sistema al acceder a ellos.";
$archive_text_022 = "Deshabilitado. No existen datos de resultados para este archivo.";
$archive_text_023 = "No se especifica un conjunto de estilos. Los datos archivados de entradas, puntuación y cajas pueden no mostrarse correctamente.";

$label_uploaded_scoresheets = "Hojas de Puntuación Cargadas (Archivos PDF)";
$label_admin_comp_type = "Tipo de Competencia";
$label_admin_styleset = "Conjunto de Estilos";
$label_admin_winner_display = "Visualización de Ganadores";
$label_admin_enable = "Habilitar";
$label_admin_disable = "Deshabilitar";
$label_admin_winner_dist = "Método de Distribución de Lugares de Ganadores";
$label_admin_archive = "Archivo";
$label_admin_scoresheet_names = "Nombres de Archivos de Carga de Hojas de Puntuación";
$label_six_char_judging = "Número de Evaluación de 6 Caracteres";
$label_six_digit_entry = "Número de Entrada de 6 Dígitos";
$label_not_archived = "No Archivado";

// -------------------- Barcode Check-In --------------------



// -------------------- Navigation --------------------

?>