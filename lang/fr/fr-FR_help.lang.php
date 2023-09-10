<?php
$list_help_title = "Aide de Mon Compte";
$list_help_body = "<p>Il s'agit d'une vue complète des informations de votre compte.</p>";
$list_help_body .= "<p>Ici, vous pouvez consulter vos informations personnelles, y compris votre nom, adresse, numéro de téléphone(s), clubs, numéro de membre de l'AHA, ID BJCP, rang de juge BJCP, préférences de jugement et préférences de service.</p>";
$list_help_body .= "<ul>";
$list_help_body .= "<li>Sélectionnez le bouton &ldquo;Modifier le Compte&rdquo; pour mettre à jour vos informations personnelles.</li>";
$list_help_body .= "<li>Sélectionnez le bouton &ldquo;Changer l'Adresse Email&rdquo; pour mettre à jour votre adresse e-mail. <strong>Note :</strong> votre adresse e-mail est également votre nom d'utilisateur.</li>";
$list_help_body .= "<li>Sélectionnez le bouton &ldquo;Changer le Mot de Passe&rdquo; pour mettre à jour le mot de passe de votre compte.</li>";
$list_help_body .= "</ul>";
$list_help_body .= "<p>En bas de la page se trouve votre liste d'inscriptions.</p>";
$list_help_body .= "<ul>";
$list_help_body .= "<li>Sélectionnez l'icône de l'imprimante <span class=\"fa fa-print\"></span> pour imprimer la documentation nécessaire pour chaque inscription (étiquettes de bouteilles, etc.).</li>";
$list_help_body .= "<li>Sélectionnez l'icône du crayon <span class=\"fa fa-pencil\"></span> pour modifier l'inscription.</li>";
$list_help_body .= "<li>Sélectionnez l'icône de la poubelle <span class=\"fa fa-trash-o\"></span> pour supprimer l'inscription.</li>";
$list_help_body .= "</ul>";

$brewer_acct_edit_help_title = "Aide pour Modifier le Compte";
$brewer_acct_edit_help_body = "<p>Ici, vous pouvez mettre à jour les informations de votre compte, y compris l'adresse/téléphone, le numéro de membre de l'AHA, l'ID BJCP, le rang de juge BJCP, la disponibilité et les préférences de jugement ou de service, etc.";

$username_help_title = "Aide pour Changer l'Adresse Email";
$username_help_body = "<p>Ici, vous pouvez changer votre adresse e-mail.</p>";
$username_help_body .= "<p><strong>Veuillez noter :</strong> votre adresse e-mail sert également de nom d'utilisateur pour accéder à votre compte sur ce site.</p>";

$password_help_title = "Aide pour Changer le Mot de Passe";
$password_help_body = "<p>Ici, vous pouvez changer le mot de passe d'accès à ce site. Plus il est sécurisé, mieux c'est - incluez des caractères spéciaux et/ou des chiffres.</p>";

$pay_help_title = "Aide pour Payer les Frais d'Inscription";
$pay_help_body = "<p>Cette page détaille vos inscriptions impayées et les frais associés. Si les organisateurs de la compétition ont prévu une réduction pour les participants avec un code, vous pouvez saisir le code avant de payer vos inscriptions.</p>";
$pay_help_body .= "<p>Pour ".$_SESSION['contestName'].", les méthodes de paiement acceptées sont :</p>";
$pay_help_body .= "<ul>";
if ($_SESSION['prefsCash'] == "Y") $pay_help_body .= "<li><strong>En espèces.</strong> Mettez de l'argent liquide dans une enveloppe et attachez-la à l'une de vos bouteilles. S'il vous plaît, pour la santé du personnel d'organisation, n'utilisez pas de pièces de monnaie.</li>";
if ($_SESSION['prefsCheck'] == "Y") $pay_help_body .= "<li><strong>Chèque.</strong> Rédigez un chèque à l'ordre de ".$_SESSION['prefsCheckPayee']." pour le montant total de vos frais d'inscription, placez-le dans une enveloppe et attachez-le à l'une de vos bouteilles. Il serait extrêmement utile pour le personnel de la compétition que vous indiquiez les numéros de vos inscriptions dans la section de mémo du chèque.</li>";
if ($_SESSION['prefsPaypal'] == "Y") $pay_help_body .= "<li><strong>Carte de Crédit/Débit via PayPal.</strong> Pour payer vos frais d'inscription avec une carte de crédit ou de débit, sélectionnez le bouton &ldquo;Payer avec PayPal&rdquo;. Un compte PayPal n'est pas nécessaire. Après avoir payé, assurez-vous de cliquer sur le lien &ldquo;Retour à...&rdquo; sur l'écran de confirmation PayPal. Cela garantira que vos inscriptions sont marquées comme payées pour cette compétition.</li>";
$pay_help_body .= "</ul>";
?>