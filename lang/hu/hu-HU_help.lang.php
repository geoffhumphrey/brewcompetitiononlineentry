<?php
$list_help_title = "Fiókom Segítség";
$list_help_body = "<p>Ez egy átfogó pillanatkép az Ön fiókadatairól.</p>";
$list_help_body .= "<p>Itt megtekintheti személyes adatait, beleértve a nevet, címet, telefonszámo(ka)t, klubokat, AHA tagsági számot, BJCP azonosítót, BJCP bírói rangot, bírálási preferenciákat és kiszolgálói preferenciákat.</p>";
$list_help_body .= "<ul>";
$list_help_body .= "<li>Válassza a &ldquo;Fiók szerkesztése&rdquo; gombot a személyes adatok frissítéséhez.</li>";
$list_help_body .= "<li>Válassza a &ldquo;E-mail módosítása&rdquo; gombot az e-mail cím frissítéséhez. <strong>Megjegyzés:</strong> az e-mail címe egyben a felhasználóneve is.</li>";
$list_help_body .= "<li>Válassza a &ldquo;Jelszó módosítása&rdquo; gombot a fiók jelszavának frissítéséhez.</li>";
$list_help_body .= "</ul>";
$list_help_body .= "<p>Az oldal alján található a nevezéseinek listája.</p>";
$list_help_body .= "<ul>";
$list_help_body .= "<li>Válassza a nyomtató ikont <span class=\"fa fa-print\"></span> az egyes nevezésekhez szükséges dokumentáció kinyomtatásához (palackcímkék stb.).</li>";
$list_help_body .= "<li>Válassza a ceruza ikont <span class=\"fa fa-pencil\"></span> a nevezés szerkesztéséhez.</li>";
$list_help_body .= "<li>Válassza a kuka ikont <span class=\"fa fa-trash-o\"></span> a nevezés törléséhez.</li>";
$list_help_body .= "</ul>";

$brewer_acct_edit_help_title = "Fiók szerkesztése Segítség";
$brewer_acct_edit_help_body = "<p>Itt frissítheti fiókadatait, beleértve a címet/telefonszámot, AHA tagsági számot, BJCP azonosítót, BJCP bírói rangot, bírálási vagy kiszolgálói helyszín-elérhetőséget és preferenciákat, és így tovább.";

$username_help_title = "E-mail cím módosítása Segítség";
$username_help_body = "<p>Itt módosíthatja az e-mail címét.</p>";
$username_help_body .= "<p><strong>Kérjük, vegye figyelembe:</strong> az e-mail címe egyben felhasználónévként is szolgál a fiókjához való hozzáféréshez ezen az oldalon.</p>";

$password_help_title = "Jelszó módosítása Segítség";
$password_help_body = "<p>Itt módosíthatja a hozzáférési jelszavát ehhez az oldalhoz. Minél biztonságosabb, annál jobb &ndash; használjon különleges karaktereket és/vagy számokat.</p>";

$pay_help_title = "Nevezési díjak fizetése Segítség";
$pay_help_body = "<p>Ez a képernyő a kifizetetlen nevezéseit és a kapcsolódó díjakat részletezi. Ha a verseny szervezői kedvezményt jelöltek ki kóddal rendelkező résztvevők számára, a kódot a nevezési díjak kifizetése előtt adhatja meg.</p>";
$pay_help_body .= "<p>A(z) ".$_SESSION['contestName']." verseny esetében az elfogadott fizetési módok a következők:</p>";
$pay_help_body .= "<ul>";
if ($_SESSION['prefsCash'] == "Y") $pay_help_body .= "<li><strong>Készpénz.</strong> Helyezze a készpénzt egy borítékba, és csatolja az egyik palackjához. Kérjük, a szervező személyzet érdekében ne fizessen érmékkel.</li>";
if ($_SESSION['prefsCheck'] == "Y") $pay_help_body .= "<li><strong>Csekk.</strong> Állítsa ki a csekket ".$_SESSION['prefsCheckPayee']." nevére a nevezési díjak teljes összegéről, helyezze borítékba, és csatolja az egyik palackjához. A verseny személyzete számára rendkívül hasznos lenne, ha a közlemény rovatban feltüntetné a nevezési számait.</li>";
if ($_SESSION['prefsPaypal'] == "Y") $pay_help_body .= "<li><strong>Hitel-/bankkártya PayPal-on keresztül.</strong> A nevezési díjak hitel- vagy bankkártyával történő kifizetéséhez válassza a &ldquo;Fizetés PayPal-lal&rdquo; gombot. PayPal fiók nem szükséges. A fizetés után feltétlenül kattintson a &ldquo;Visszatérés a...&rdquo; hivatkozásra a PayPal visszaigazoló képernyőn. Ez biztosítja, hogy nevezései fizetettként legyenek megjelölve ennél a versenynél.</li>";
$pay_help_body .= "</ul>";
?>