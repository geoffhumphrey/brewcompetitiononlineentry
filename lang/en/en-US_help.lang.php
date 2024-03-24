<?php
$list_help_title = "My Account Help";
$list_help_body = "<p>This is a comprehensive snapshot of your account information.</p>";
$list_help_body .= "<p>Here, you can view your personal information including name, address, phone number(s), clubs, AHA member number, BJCP ID, BJCP judge rank, judging preferences, and stewarding preferences.</p>";
$list_help_body .= "<ul>";
$list_help_body .= "<li>Select the &ldquo;Edit Account&rdquo; button to update your personal information.</li>";
$list_help_body .= "<li>Select the &ldquo;Change Email&rdquo; button to update your email address. <strong>Note:</strong> your email address is also your user name.</li>";
$list_help_body .= "<li>Select the &ldquo;Change Password&rdquo; button to update your account password.</li>";
$list_help_body .= "</ul>";
$list_help_body .= "<p>At the bottom of the page is your list of entries.</p>";
$list_help_body .= "<ul>";
$list_help_body .= "<li>Select the printer icon <span class=\"fa fa-print\"></span> to print the necessary documentation for each entry (bottle labels, etc.).</li>";
$list_help_body .= "<li>Select the pencil icon <span class=\"fa fa-pencil\"></span> to edit the entry.</li>";
$list_help_body .= "<li>Select the trash can icon <span class=\"fa fa-trash-o\"></span> to delete the entry.</li>";
$list_help_body .= "</ul>";

$brewer_acct_edit_help_title = "Edit Account Help";
$brewer_acct_edit_help_body = "<p>Here, you can update your account information including address/phone, AHA member number, BJCP ID, BJCP judge rank, judging or stewarding location availability and preferences, and so on.";

$username_help_title = "Change Email Address Help";
$username_help_body = "<p>Here, you can change your email address.</p>";
$username_help_body .= "<p><strong>Please Note:</strong> your email address also serves as your user name to access your account on this site.</p>";

$password_help_title = "Change Password Help";
$password_help_body = "<p>Here, you can change your access password to this site. The more secure, the better &ndash; include special characters and/or numbers.</p>";

$pay_help_title = "Pay Entry Fees Help";
$pay_help_body = "<p>This screen details your unpaid entries and associated fees. If the competition organizers have designated a discount for participants with a code, you can enter the code before paying for your entries.</p>";
$pay_help_body .= "<p>For the ".$_SESSION['contestName'].", accepted payment methods are:</p>";
$pay_help_body .= "<ul>";
if ($_SESSION['prefsCash'] == "Y") $pay_help_body .= "<li><strong>Cash.</strong> Put cash in an envelope and attach to one of your bottles. Please, for the sanity of the organizing staff, do not pay with coins.</li>";
if ($_SESSION['prefsCheck'] == "Y") $pay_help_body .= "<li><strong>Check.</strong> Make your check out to ".$_SESSION['prefsCheckPayee']." for the full amount of your entry fees, place in an envelope, and attach to one of your bottles. It would be extremely helpful for competition staff if you would list your entry numbers in the memo section.</li>";
if ($_SESSION['prefsPaypal'] == "Y") $pay_help_body .= "<li><strong>Credit/Debit Card via PayPal.</strong> To pay your entry fees with a credit or debit card, select the &ldquo;Pay with PayPal&rdquo; button. A PayPal account is not necessary. After you have paid, be sure to click the &ldquo;Return to...&rdquo; link on the PayPal confirmation screen. This will ensure that your entries are marked as paid for this competition.</li>";
$pay_help_body .= "</ul>";
?>