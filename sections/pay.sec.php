<?php
/**
 * Module:      pay.sec.php 
 * Description: This module dispays payment information based upon the competition-
                specific payment preferences. 
 * 
 */

/* ---------------- PUBLIC Pages Rebuild Info ---------------------

Beginning with the 1.3.0 release, an effort was begun to separate the programming
layer from the presentation layer for all scripts with this header.

All Public pages have certain variables in common that build the page:

	$warningX = any warnings
  
	$primary_page_info = any information related to the page
	
	$header1_X = an <h2> header on the page
	$header2_X = an <h3> subheader on the page
	
	$page_infoX = the bulk of the information on the page.
	$help_page_link = link to the appropriate page on help.brewcompetition.com
	$print_page_link = the "Print This Page" link
	$competition_logo = display of the competition's logo
	
	$labelX = the various labels in a table or on a form
	$messageX = various messages to display
	
	$print_page_link = "<p><span class='icon'><img src='".$base_url."images/printer.png' border='0' alt='Print' title='Print' /></span><a id='modal_window_link' class='data' href='".$base_url."output/print.php?section=".$section."&amp;action=print' title='Print'>Print This Page</a></p>";
	$competition_logo = "<img src='".$base_url."user_images/".$_SESSION['contestLogo']."' width='".$_SESSION['prefsCompLogoSize']."' style='float:right; padding: 5px 0 5px 5px' alt='Competition Logo' title='Competition Logo' />";
	
Declare all variables empty at the top of the script. Add on later...
	$warning1 = "";
	$primary_page_info = "";
	$header1_1 = "";
	$page_info1 = "";
	$header1_2 = "";
	$page_info2 = "";
	
	etc., etc., etc.

 * ---------------- END Rebuild Info --------------------- */

$bid = $_SESSION['user_id'];
include (DB.'entries.db.php');

$currency_code = currency_info($_SESSION['prefsCurrency'],1);
$currency_code = explode("^",$currency_code);

$total_entry_fees = total_fees($_SESSION['contestEntryFee'], $_SESSION['contestEntryFee2'], $_SESSION['contestEntryFeeDiscount'], $_SESSION['contestEntryFeeDiscountNum'], $_SESSION['contestEntryCap'], $_SESSION['contestEntryFeePasswordNum'], $bid, $filter);
$total_paid_entry_fees = total_fees_paid($_SESSION['contestEntryFee'], $_SESSION['contestEntryFee2'], $_SESSION['contestEntryFeeDiscount'], $_SESSION['contestEntryFeeDiscountNum'], $_SESSION['contestEntryCap'], $_SESSION['contestEntryFeePasswordNum'], $bid, $filter);
$total_to_pay = $total_entry_fees - $total_paid_entry_fees; 
$total_not_paid = total_not_paid_brewer($_SESSION['user_id']);
$unconfirmed = entries_unconfirmed($_SESSION['user_id']);

$print_page_link = "<p><span class='icon'><img src='".$base_url."images/printer.png' border='0' alt='Print' title='Print' /></span><a id='modal_window_link' class='data' href='".$base_url."output/print.php?section=".$section."&amp;action=print' title='Print'>Print This Page</a></p>";
$competition_logo = "<img src='".$base_url."user_images/".$_SESSION['contestLogo']."' width='".$_SESSION['prefsCompLogoSize']."' style='float:right; padding: 5px 0 5px 5px' alt='Competition Logo' title='Competition Logo' />";
$help_page_link = "<p><span class='icon'><img src='".$base_url."images/help.png'  /></span><a id='modal_window_link' href='http://help.brewcompetition.com/files/pay_my_fees.html' title='BCOE&amp;M Help: Pay My Fees'>Pay My Fees Help</a></p>";

$warning1 = "";
$warning2 = "";
$warning3 = "";
$primary_page_info = "";
$header1_1 = "";
$page_info1 = "";
$header1_2 = "";
$page_info2 = "";
$header1_3 = "";
$page_info3 = "";
$header2_4 = "";
$page_info4 = "";
$header2_5 = "";
$page_info5 = "";
$page_info6 = "";
$header1_7 = "";
$page_info7 = "";

// Build top of page info: total entry fees, list of unpaid entries, etc.
$primary_page_info .= "<p>";
$primary_page_info .= sprintf("<span class='icon'><img src='".$base_url."images/money.png'  border='0' alt='Entry Fees' title='Entry Fees'></span>Fees are %s per entry.",$currency_code[0].number_format($_SESSION['contestEntryFee'],2));
if ($_SESSION['contestEntryFeeDiscount'] == "Y") $primary_page_info .= sprintf(" %s per entry after the %s entry. ",$currency_code[0].number_format($_SESSION['contestEntryFee2'], 2),addOrdinalNumberSuffix($_SESSION['contestEntryFeeDiscountNum'])); 
if ($_SESSION['contestEntryCap'] != "") $primary_page_info .= sprintf(" %s for unlimited entries. ",$currency_code[0].number_format($_SESSION['contestEntryCap'], 2));
$primary_page_info .= "</p>";
if ($row_brewer['brewerDiscount'] == "Y") {
	$primary_page_info .= sprintf("<span class='icon'><img src='".$base_url."images//star.png'  border='0' alt='Entry Fees' title='Entry Fees'></span>Your fees have been discounted to %s per entry.</p>",$currency_code[0].number_format($_SESSION['contestEntryFeePasswordNum'], 2));
}
$primary_page_info .= sprintf("<p><span class='icon'><img src='".$base_url."images/money.png'  border='0' alt='Entry Fees' title='Entry Fees'></span>Your total entry fees are %s. You need to pay %s.</p>",$currency_code[0].number_format($total_entry_fees,2),$currency_code[0].number_format($total_to_pay,2));

if ($total_not_paid == 0) $primary_page_info .= sprintf("<p><span class='icon'><img src='".$base_url."images/thumb_up.png'  border='0' alt='Entry Fees' title='Entry Fees'></span>%s</p>","Your fees have been paid. Thank you!");


else {
	$primary_page_info .= "<p>";
	$primary_page_info .= sprintf("<span class='icon'><img src='".$base_url."images/money.png'  border='0' alt='Entry Fees' title='Entry Fees'></span>You currently have %s <strong>unpaid</strong> ",readable_number($total_not_paid));
	if ($total_not_paid == "1") $primary_page_info .= "entry:"; else $primary_page_info .= "entries:";
	$primary_page_info .= "</p>";
	$primary_page_info .= "<ul>";
		do { 
			if ($row_log['brewPaid'] != "1") {
				$entry_no = sprintf("%04s",$row_log['id']);
				$primary_page_info .= sprintf("<li>Entry #%s: %s (Category %s)</li>",$entry_no,$row_log['brewName'],$row_log['brewCategory'].$row_log['brewSubCategory']);
				$entries .= sprintf("%04s",$row_log['id']).", ";
				$return .= $row_log['id']."-";
			}
		} while ($row_log = mysql_fetch_assoc($log)); 
	$primary_page_info .= "</ul>";
}

$return = $base_url."index.php?section=pay&msg=10&view=";
$entries = "";

if (($total_to_pay > 0) && ($view == "default")) {
	
	// Cash Payment
	if ($_SESSION['prefsCash'] == "Y") { 
		$header1_1 .= "<h2>Cash</h2>";
		$page_info1 .= "<p>Attach cash payment for the entire entry amount in a <em>sealed envelope</em> to one of  your bottles.</p>";
		$page_info1 .= "<p><span class='required'> Your returned score sheets will serve as your entry receipt.</span></p>";
	}

	
	if ($_SESSION['prefsCheck'] == "Y") {
		// Check Payment
		$header1_2 .= "<h2>Checks</h2>";
		$page_info2 .= sprintf("<p>Attach a check for the entire entry amount to one of your bottles. Checks should be made out to <em>%s</em>.</p>",$_SESSION['prefsCheckPayee']);
		$page_info2 .= "<p><span class='required'> Your check carbon or cashed check is your entry receipt.</span></p>";
	}

	if ($_SESSION['prefsPaypal'] == "Y")  { 
				
		if ($_SESSION['prefsTransFee'] == "Y") $payment_amount = $total_to_pay + number_format((($total_to_pay * .03) + .30), 2, '.', ''); 
		else $payment_amount = number_format($total_to_pay, 2);
		$fee = number_format((($total_to_pay * .03) + .30), 2, '.', ''); 
	
		// Online
		$header1_3 .= "<h2>Pay Online</h2>";
		$page_info3 .= "<p><span class='required'> Your payment confirmation email is your entry receipt. Include a copy with your entries as proof of payment.</span></p>";
	
		// PayPal
		$header2_4 .= "<h3>PayPal</h3>";
		$page_info4 .= "<p>Click the &quot;Pay Now&quot; button below to pay online using PayPal.";
		if ($_SESSION['prefsTransFee'] == "Y") $page_info4 .= sprintf(" Please note that a transaction fee of %s will be added into your total.</p>",$currency_code[0].$fee);
		$page_info4 .= "<div class='error'>To make sure your PayPal payment is marked &quot;paid&quot; on <em>this site</em>, please click the &quot;Return to...&quot; link on PayPal's confirmation screen after you have sent your payment.</div>";
		$page_info4 .= "<form name='PayPal' action='https://www.paypal.com/cgi-bin/webscr' method='post'>";
		$page_info4 .= "<input type='hidden' name='cmd' value='_xclick'>";
		$page_info4 .= sprintf("<input type='hidden' name='business' value='%s'>",$_SESSION['prefsPaypalAccount']);
		$page_info4 .= sprintf("<input type='hidden' name='item_name' value='%s, %s - %s Payment'>",$_SESSION['brewerLastName'],$_SESSION['brewerFirstName'],$_SESSION['contestName']);
		$page_info4 .= sprintf("<input type='hidden' name='amount' value='%s'>",$payment_amount);
		$page_info4 .= sprintf("<input type='hidden' name='currency_code' value='%s'>",$currency_code[1]);
		$page_info4 .= "<input type='hidden' name='button_subtype' value='services'>";
		$page_info4 .= "<input type='hidden' name='no_note' value='0'>";
		$page_info4 .= "<input type='hidden' name='cn' value='Add special instructions'>";
		$page_info4 .= "<input type='hidden' name='no_shipping' value='1'>";
		$page_info4 .= "<input type='hidden' name='rm' value='1'>";
		$page_info4 .= sprintf("<input type='hidden' name='return' value='%s'>",rtrim($return, '-'));
		$page_info4 .= sprintf("<input type='hidden' name='cancel_return' value='$s'>",$base_url."index.php?section=pay&msg=11");
		$page_info4 .= "<input type='hidden' name='bn' value='PP-BuyNowBF:btn_paynowCC_LG.gif:NonHosted'>";
		$page_info4 .= "<input type='image' src='https://www.paypalobjects.com/en_US/i/btn/btn_paynowCC_LG.gif' border='0' name='submit' class='paypal' alt='Pay your competition entry fees with PayPal' title='Pay your compeition entry fees with PayPal'>";
		$page_info4 .= "</form>";

		/*
		if ($_SESSION['prefsGoogle'] == "Y") {
			// Google Wallet
			$header2_5 .= "<h2>Google Wallet</h2>";
			$page_info5 .= "<p>Click the &quot;Buy Now&quot; button below to pay online using Google Wallet.";
			if ($_SESSION['prefsTransFee'] == "Y") $page_info5 .= sprintf(" Please note that a transaction fee of %s will be added into your total.</p>",$currency_code[0].$fee);
			$page_info5 .= "<div class='error'>To make sure your Google Wallet payment is marked &quot;paid&quot; on <em>this site</em>, please click the &quot;Return to...&quot; link on Google Wallet's confirmation screen after you have sent your payment.</div>";
			$page_info5 .= sprintf("<form action='https://checkout.google.com/api/checkout/v2/checkoutForm/Merchant/%s' id='BB_BuyButtonForm' method='post' name='BB_BuyButtonForm' target='_top'>",$_SESSION['prefsGoogleAccount']);
			$page_info5 .= sprintf("<input name='item_name_1' type='hidden' value='%s, %s - %s Payment'>",$_SESSION['brewerLastName'],$_SESSION['brewerFirstName'],$_SESSION['contestName']);
			$page_info5 .= sprintf("<input name='checkout-flow-support.merchant-checkout-flow-support.continue-shopping-url' type='hidden' value='%s' />",rtrim($return, '-'));
			$page_info5 .= sprintf("<input name='item_description_1' type='hidden' value='Entry #: %s'/>",rtrim($entries,', '));
			$page_info5 .= "<input name='item_quantity_1' type='hidden' value='1'/>";
			$page_info5 .= sprintf("<input name='item_price_1' type='hidden' value='%s'/>",$payment_amount);
			$page_info5 .= sprintf("<input name='item_currency_1' type='hidden' value='%s'/>",$currency_code);
			$page_info5 .= "<input name='_charset_' type='hidden' value='utf-8'/>";
			$page_info5 .= sprintf("<input src='https://checkout.google.com/buttons/buy.gif?merchant_id=%s&amp;w=117&amp;h=48&amp;style=white&amp;variant=text&amp;loc=en_US' type='image' class='paypal' alt='Pay your competition entry fees with Google Wallet' title='Pay your compeition entry fees with Google Wallet'/>",$_SESSION['prefsGoogleAccount']);
			$page_info5 .= "</form>";

		}
		*/
		
	} // end if (($_SESSION['prefsPaypal'] == "Y") || ($_SESSION['prefsGoogle'] == "Y"))
	
}

if (($row_brewer['brewerDiscount'] != "Y") && ($row_contest_info['contestEntryFeePassword'] != "") && ((($total_entry_fees > 0) && ($total_entry_fees != $total_paid_entry_fees)))) {
	$header1_7 .= "<h2>Discounted Entry Fee</h2>";
	$page_info7 .= "<p>Enter the code supplied by the competition organizers for a discounted entry fee.</p>";
	$page_info7 .= "<form action='".$base_url."includes/process.inc.php?action=check_discount&amp;dbTable=".$brewer_db_table."&amp;id=".$row_brewer['uid']."' method='POST' name='form1' id='form1'>";
	$page_info7 .= "<p>";
	$page_info7 .= sprintf("%s: ","Discount Code");
	$page_info7 .= "<input name='brewerDiscount' type='text' class='submit' size='20'>";
	$page_info7 .= "</p>";
	$page_info7 .= sprintf("<p><input type='submit' class='button' value='%s'></p>","Submit Code");
	$page_info7 .= "</form>";
}

if (($total_entry_fees > 0) && ($total_entry_fees == $total_paid_entry_fees)) $page_info6 .= "<span class='icon'><img src='".$base_url."images/thumb_up.png'  border='0' alt='Entry Fees' title='Entry Fees'></span>Your fees have been paid. Thank you!</p>";
if ($total_entry_fees == 0) $page_info6 .= "<p>You have not logged any entries yet.</p>";

if (($_SESSION['prefsPayToPrint'] == "Y") && ($unconfirmed > 0)) $warning1 .= "<div class='error'>You cannot pay for your entries because one or more of your entries is unconfirmed.</div><p>Click &ldquo;My Info and Entries&rdquo; above to review your unconfirmed entries.</p>"; 

	
// --------------------------------------------------------------
// Display
// --------------------------------------------------------------
if (($action != "print") && ($msg != "default")) echo $msg_output;
if ((($_SESSION['contestLogo'] != "") && (file_exists($_SERVER['DOCUMENT_ROOT'].$sub_directory.'/user_images/'.$_SESSION['contestLogo']))) && ((judging_date_return() > 0) || (NHC))) echo $competition_logo;
if ($action != "print") echo $print_page_link;

if ($total_entry_fees > 0) { 
	
	if (($_SESSION['prefsPayToPrint'] == "N") && ($unconfirmed > 0)) $warning2 .= "<div class='error'>You have unconfirmed entries that are <em>not</em> reflected in your fee totals below. Please go to <a href='".build_public_url("list","default","default",$sef,$base_url)."'>your entry list</a> to confirm all your entry data.<br />Unconfirmed entry data will be deleted every 24 hours.</div>";
	
	echo $warning1;
	echo $warning2;
	echo $primary_page_info;
	echo $header1_7;
	echo $page_info7;
	echo $header1_1;
	echo $page_info1;
	echo $header1_2;
	echo $page_info2;
	echo $header1_3;
	echo $page_info3;
	echo $header2_4;
	echo $page_info4;
	//echo $header2_5;
	//echo $page_info5;
	
} // end if ($total_entry_fees > 0)

else echo $page_info6;
?>

