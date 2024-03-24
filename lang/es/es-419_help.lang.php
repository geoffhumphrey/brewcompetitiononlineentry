<?php
$list_help_title = "Ayuda de Mi Cuenta";
$list_help_body = "<p>Esto es una vista completa de la información de tu cuenta.</p>";
$list_help_body .= "<p>Aquí puedes ver tu información personal, incluyendo nombre, dirección, número de teléfono(s), clubes, número de miembro de AHA, ID de BJCP, rango de juez de BJCP, preferencias de juicio y preferencias de servicio.</p>";
$list_help_body .= "<ul>";
$list_help_body .= "<li>Selecciona el botón &ldquo;Editar Cuenta&rdquo; para actualizar tu información personal.</li>";
$list_help_body .= "<li>Selecciona el botón &ldquo;Cambiar Correo Electrónico&rdquo; para actualizar tu dirección de correo electrónico. <strong>Nota:</strong> tu dirección de correo electrónico también es tu nombre de usuario.</li>";
$list_help_body .= "<li>Selecciona el botón &ldquo;Cambiar Contraseña&rdquo; para actualizar la contraseña de tu cuenta.</li>";
$list_help_body .= "</ul>";
$list_help_body .= "<p>En la parte inferior de la página se encuentra tu lista de inscripciones.</p>";
$list_help_body .= "<ul>";
$list_help_body .= "<li>Selecciona el ícono de la impresora <span class=\"fa fa-print\"></span> para imprimir la documentación necesaria para cada inscripción (etiquetas de botellas, etc.).</li>";
$list_help_body .= "<li>Selecciona el ícono del lápiz <span class=\"fa fa-pencil\"></span> para editar la inscripción.</li>";
$list_help_body .= "<li>Selecciona el ícono del basurero <span class=\"fa fa-trash-o\"></span> para eliminar la inscripción.</li>";
$list_help_body .= "</ul>";

$brewer_acct_edit_help_title = "Ayuda para Editar la Cuenta";
$brewer_acct_edit_help_body = "<p>Aquí puedes actualizar la información de tu cuenta, incluyendo dirección/teléfono, número de miembro de AHA, ID de BJCP, rango de juez BJCP, disponibilidad y preferencias de juicio o servicio, y más.";

$username_help_title = "Ayuda para Cambiar la Dirección de Correo Electrónico";
$username_help_body = "<p>Aquí puedes cambiar tu dirección de correo electrónico.</p>";
$username_help_body .= "<p><strong>Por favor, ten en cuenta:</strong> tu dirección de correo electrónico también sirve como tu nombre de usuario para acceder a tu cuenta en este sitio.</p>";

$password_help_title = "Ayuda para Cambiar la Contraseña";
$password_help_body = "<p>Aquí puedes cambiar la contraseña de acceso a este sitio. Cuanto más segura sea, mejor, incluye caracteres especiales y/o números.</p>";

$pay_help_title = "Ayuda para Pagar las Tarifas de Inscripción";
$pay_help_body = "<p>Esta pantalla detalla tus inscripciones no pagadas y las tarifas asociadas. Si los organizadores de la competencia han designado un descuento para los participantes con un código, puedes ingresar el código antes de pagar tus inscripciones.</p>";
$pay_help_body .= "<p>Para el ".$_SESSION['contestName'].", los métodos de pago aceptados son:</p>";
$pay_help_body .= "<ul>";
if ($_SESSION['prefsCash'] == "Y") $pay_help_body .= "<li><strong>Efectivo.</strong> Coloca efectivo en un sobre y adjúntalo a una de tus botellas. Por favor, por el bienestar del personal de organización, evita pagar con monedas.</li>";
if ($_SESSION['prefsCheck'] == "Y") $pay_help_body .= "<li><strong>Cheque.</strong> Escribe un cheque a nombre de ".$_SESSION['prefsCheckPayee']." por la cantidad total de tus tarifas de inscripción, colócalo en un sobre y adjúntalo a una de tus botellas. Sería extremadamente útil para el personal de la competencia que incluyeras los números de tus inscripciones en la sección de memo del cheque.</li>";
if ($_SESSION['prefsPaypal'] == "Y") $pay_help_body .= "<li><strong>Tarjeta de Crédito/Débito a través de PayPal.</strong> Para pagar tus tarifas de inscripción con una tarjeta de crédito o débito, selecciona el botón &ldquo;Pagar con PayPal&rdquo;. No es necesario tener una cuenta de PayPal. Después de realizar el pago, asegúrate de hacer clic en el enlace &ldquo;Volver a...&rdquo; en la pantalla de confirmación de PayPal. Esto garantizará que tus inscripciones se marquen como pagadas para esta competencia.</li>";
$pay_help_body .= "</ul>";
?>