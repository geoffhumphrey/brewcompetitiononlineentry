<?php
$list_help_title = "Nápověda ke mému účtu";
$list_help_body = "<p>Toto je komplexní přehled informací o vašem účtu.</p>";
$list_help_body .= "<p>Zde můžete zobrazit své osobní údaje, včetně jména, adresy, telefonního čísla, klubů, členského čísla AHA, identifikačního čísla BJCP, hodnosti BJCP soudce, preference ohledně hodnocení a preference ohledně sluhování.</p>";
$list_help_body .= "<ul>";
$list_help_body .= "<li>Vyberte tlačítko &ldquo;Upravit účet&rdquo; pro aktualizaci svých osobních údajů.</li>";
$list_help_body .= "<li>Vyberte tlačítko &ldquo;Změnit e-mail&rdquo; pro aktualizaci své e-mailové adresy. <strong>Poznámka:</strong> Vaše e-mailová adresa je také vaším uživatelským jménem.</li>";
$list_help_body .= "<li>Vyberte tlačítko &ldquo;Změnit heslo&rdquo; pro aktualizaci hesla k vašemu účtu.</li>";
$list_help_body .= "</ul>";
$list_help_body .= "<p>Na konci stránky je seznam vašich záznamů.</p>";
$list_help_body .= "<ul>";
$list_help_body .= "<li>Vyberte ikonu tiskárny <span class=\"fa fa-print\"></span> pro tisk potřebné dokumentace ke každému záznamu (etikety na láhve, atd.).</li>";
$list_help_body .= "<li>Vyberte ikonu tužky <span class=\"fa fa-pencil\"></span> pro úpravu záznamu.</li>";
$list_help_body .= "<li>Vyberte ikonu koše <span class=\"fa fa-trash-o\"></span> pro smazání záznamu.</li>";
$list_help_body .= "</ul>";

$brewer_acct_edit_help_title = "Nápověda k úpravě účtu";
$brewer_acct_edit_help_body = "<p>Zde můžete aktualizovat informace o vašem účtu, včetně adresy/telefonu, členského čísla AHA, identifikačního čísla BJCP, hodnosti BJCP soudce, dostupnosti a preferencí ohledně hodnocení nebo služby apod.";

$username_help_title = "Nápověda pro změnu e-mailové adresy";
$username_help_body = "<p>Zde můžete změnit svou e-mailovou adresu.</p>";
$username_help_body .= "<p><strong>Upozornění:</strong> Vaše e-mailová adresa slouží také jako uživatelské jméno pro přístup k vašemu účtu na této stránce.</p>";

$password_help_title = "Nápověda pro změnu hesla";
$password_help_body = "<p>Zde můžete změnit přístupové heslo k této stránce. Čím bezpečnější, tím lépe – zahrňte speciální znaky a/nebo číslice.</p>";

$pay_help_title = "Nápověda pro zaplacení vstupného";
$pay_help_body = "<p>Tato obrazovka obsahuje seznam vašich nezaplacených záznamů a příslušných poplatků. Pokud pořadatelé soutěže určili slevu pro účastníky s kódem, můžete tento kód zadat před zaplacením vašich záznamů.</p>";
$pay_help_body .= "<p>Pro soutěž ".$_SESSION['contestName']." jsou přijímány následující platební metody:</p>";
$pay_help_body .= "<ul>";
if ($_SESSION['prefsCash'] == "Y") $pay_help_body .= "<li><strong>Hotovost.</strong> Vložte hotovost do obálky a připevněte ji k jedné z vašich lahví. Pro usnadnění práce organizačního personálu se prosím vyhněte platbě mincemi.</li>";
if ($_SESSION['prefsCheck'] == "Y") $pay_help_body .= "<li><strong>Šek.</strong> Napište šek na jméno ".$_SESSION['prefsCheckPayee']." ve výši vašich vstupních poplatků, vložte jej do obálky a připevněte k jedné z vašich lahví. Pro organizační personál by bylo velmi užitečné, kdybyste ve zprávě uvedli čísla vašich záznamů.</li>";
if ($_SESSION['prefsPaypal'] == "Y") $pay_help_body .= "<li><strong>Kreditní/debetní karta prostřednictvím PayPal.</strong> Pro zaplacení vstupních poplatků kreditní nebo debetní kartou vyberte tlačítko &ldquo;Zaplatit přes PayPal&rdquo;. PayPal účet není nutný. Po zaplacení nezapomeňte kliknout na odkaz &ldquo;Vrátit se na...&rdquo; na potvrzovací obrazovce PayPal. Tím zajistíte, že vaše záznamy budou označeny jako zaplacené pro tuto soutěž.</li>";
$pay_help_body .= "</ul>";
?>