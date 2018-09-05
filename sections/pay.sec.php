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

if (TESTING) {
	if ((isset($_SESSION['prefsPaypalIPN'])) && ($_SESSION['prefsPaypalIPN'] == 1)) $paypal_env = $base_url."includes/process.inc.php?action=paypal";
	else $paypal_env = "https://www.sandbox.paypal.com/cgi-bin/webscr";
}

else {
	if ((isset($_SESSION['prefsPaypalIPN'])) && ($_SESSION['prefsPaypalIPN'] == 1)) $paypal_env = $base_url."includes/process.inc.php?action=paypal";
	else $paypal_env = "https://www.paypal.com/cgi-bin/webscr";
}

$bid = $_SESSION['user_id'];
include (DB.'entries.db.php');

$total_entry_fees = total_fees($_SESSION['contestEntryFee'], $_SESSION['contestEntryFee2'], $_SESSION['contestEntryFeeDiscount'], $_SESSION['contestEntryFeeDiscountNum'], $_SESSION['contestEntryCap'], $_SESSION['contestEntryFeePasswordNum'], $bid, $filter, $_SESSION['comp_id']);

$total_paid_entry_fees = total_fees_paid($_SESSION['contestEntryFee'], $_SESSION['contestEntryFee2'], $_SESSION['contestEntryFeeDiscount'], $_SESSION['contestEntryFeeDiscountNum'], $_SESSION['contestEntryCap'], $_SESSION['contestEntryFeePasswordNum'], $bid, $filter, $_SESSION['comp_id']);
$total_to_pay = $total_entry_fees - $total_paid_entry_fees;
$total_not_paid = total_not_paid_brewer($_SESSION['user_id']);
$unconfirmed = array_sum(entries_unconfirmed($_SESSION['user_id']));

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
$return_entries = "";
$entries = "";

if ($disable_pay) {
	$primary_page_info .= sprintf("<p class=\"lead\">%s, %s <small><a href=\"%s\">%s</a></small></p>",$_SESSION['brewerFirstName'],strtolower($pay_text_000),$link_contacts,$pay_text_001);
	echo $primary_page_info;
}

elseif ($comp_paid_entry_limit) {
	$primary_page_info .= sprintf("<p class=\"lead\">%s<small> <a href=\"%s\">%s</a></small></p>",$pay_text_034,$link_contacts,$pay_text_001);
	echo $primary_page_info;
}

// If payment options are not disabled...

else {

	// Build top of page info: total entry fees, list of unpaid entries, etc.
	$primary_page_info .= sprintf("<p class=\"lead\">%s, %s</p>",$_SESSION['brewerFirstName'],$pay_text_002);
	$primary_page_info .= "<p class=\"lead\"><small>";
	$primary_page_info .= sprintf("<span class=\"fa fa-lg fa-money text-success\"></span> %s <strong class=\"text-success\">%s</strong> %s.",$pay_text_003,$currency_symbol.number_format($_SESSION['contestEntryFee'],2),$pay_text_004);

	if ($_SESSION['contestEntryFeeDiscount'] == "Y") $primary_page_info .= sprintf(" %s %s %s %s. ",$currency_symbol.number_format($_SESSION['contestEntryFee2'], 2), $pay_text_005,addOrdinalNumberSuffix($_SESSION['contestEntryFeeDiscountNum']), strtolower($label_entry));
	if ($_SESSION['contestEntryCap'] > 0) $primary_page_info .= sprintf(" %s %s. ",$currency_symbol.number_format($_SESSION['contestEntryCap'], 2),$pay_text_006);
	$primary_page_info .= "</small></p>";
	if (($row_brewer['brewerDiscount'] == "Y") && (isset($_SESSION['contestEntryFeePasswordNum']))) {
		$primary_page_info .= sprintf("<p class=\"lead\"><small><span class=\"fa fa-lg fa-star-o text-primary\"></span> %s <strong class=\"text-success\">%s</strong> %s.</small></p>",$pay_text_007,$currency_symbol.number_format($_SESSION['contestEntryFeePasswordNum'], 2),$pay_text_004);
	}
	$primary_page_info .= sprintf("<p class=\"lead\"><small><span class=\"fa fa-lg fa-exclamation-triangle text-danger\"></span> %s <strong class=\"text-success\">%s</strong>. %s <strong class=\"text-danger\">%s</strong>.</small></p>",$pay_text_008,$currency_symbol.number_format($total_entry_fees,2),$pay_text_009,$currency_symbol.number_format($total_to_pay,2));

	if (($total_not_paid == 0) || ($total_to_pay == 0)) $primary_page_info .= sprintf("<p class=\"lead\"><small><span class=\"fa fa-lg fa-check-circle text-success\"></span> %s</p></small></p>",$pay_text_010);


	else {
		$primary_page_info .= "<p class=\"lead\"><small>";
		$primary_page_info .= sprintf("<span class=\"fa fa-lg fa-exclamation-triangle text-danger\"></span>  %s <strong class=\"text-danger\">%s %s ",$pay_text_011,$total_not_paid,$pay_text_012);
		if ($total_not_paid == "1") $primary_page_info .= sprintf("%s</strong>:",strtolower($label_entry)); else $primary_page_info .= sprintf("%s</strong>:",strtolower($label_entries));
		$primary_page_info .= "</small></p>";
		$primary_page_info .= "<ol>";
			do {
				if ($row_log_confirmed['brewPaid'] != "1") {
					if ($_SESSION['prefsStyleSet'] == "BA") $style = $row_log_confirmed['brewStyle'];
					else $style = "Style ".$row_log_confirmed['brewCategory'].$row_log_confirmed['brewSubCategory'];
					$entry_no = sprintf("%04s",$row_log_confirmed['id']);
					$primary_page_info .= sprintf("<li>Entry #%s: %s (%s)</li>",$entry_no,$row_log_confirmed['brewName'],$style);
					$entries .= sprintf("%04s",$row_log_confirmed['id']).", ";
					$return_entries .= $row_log_confirmed['id']."-";
				}
			} while ($row_log_confirmed = mysqli_fetch_assoc($log_confirmed));
		$primary_page_info .= "</ol>";
	}

	if ((isset($_SESSION['prefsPaypalIPN'])) && ($_SESSION['prefsPaypalIPN'] == 1))  $return = $base_url."index.php?section=pay&msg=10";
	else $return = $base_url."index.php?section=pay&msg=10&view=".rtrim($return_entries,'-');
	if (($total_to_pay > 0) && ($view == "default")) {

		// Cash Payment
		if ($_SESSION['prefsCash'] == "Y") {
			$header1_1 .= sprintf("<h2>%s</h2>",$label_cash);
			$page_info1 .= sprintf("<p>%s</p>",$pay_text_015);
			$page_info1 .= sprintf("<p>%s</p>",$pay_text_016);
		}

		if ($_SESSION['prefsCheck'] == "Y") {
			// Check Payment
			$header1_2 .= sprintf("<h2>%s</h2>",$label_check);
			$page_info2 .= sprintf("<p>%s <em>%s</em>.</p>",$pay_text_013,$_SESSION['prefsCheckPayee']);
			$page_info2 .= sprintf("<p>%s</p>",$pay_text_014);
		}

		if ($_SESSION['prefsPaypal'] == "Y")  {

			$fee = number_format((($total_to_pay * .03) + .30), 2, '.', '') + .01; // Hack to resolve payments being off by a penny
			if ($_SESSION['prefsTransFee'] == "Y") $payment_amount = $total_to_pay + $fee;
			else $payment_amount = number_format($total_to_pay, 2);

			// Online
			$header1_3 .= sprintf("<h2>%s</h2>",$label_pay_online);
			$page_info3 .= sprintf("<p>%s</p>", $pay_text_017);

			// PayPal
			$header2_4 .= "<h3>PayPal <span class=\"fa fa-lg fa-cc-paypal\"></span> <span class=\"fa fa-lg fa-cc-visa\"></span> <span class=\"fa fa-lg fa-cc-mastercard\"></span> <span class=\"fa fa-lg fa-cc-discover\"></span> <span class=\"fa fa-lg fa-cc-amex\"></span></h3>";
			$page_info4 .= sprintf("<p>%s",$pay_text_018);
			if ($_SESSION['prefsTransFee'] == "Y") $page_info4 .= sprintf(" %s %s %s",$pay_text_019,$currency_symbol.$fee,$pay_text_020);
			$page_info4 .= "</p>";

			$page_info4 .= "<form role=\"form\" id=\"formfield\" name=\"PayPal\" action=\"".$paypal_env."\" method=\"post\">\n";
			$page_info4 .= "<input type=\"hidden\" name=\"action\" value=\"add_form\" />\n";
			$page_info4 .= "<input type=\"hidden\" name=\"cmd\" value=\"_xclick\">\n";
			$page_info4 .= sprintf("<input type=\"hidden\" name=\"business\" value=\"%s\">\n",$_SESSION['prefsPaypalAccount']);
			if ($_SESSION['prefsProEdition'] == 1) $page_info4 .= sprintf("<input type=\"hidden\" name=\"item_name\" value=\"%s - %s Payment\">\n",$_SESSION['brewerBreweryName'],ucwords($_SESSION['contestName']));
			else $page_info4 .= sprintf("<input type=\"hidden\" name=\"item_name\" value=\"%s, %s - %s Payment\">\n",$_SESSION['brewerLastName'],$_SESSION['brewerFirstName'],ucwords($_SESSION['contestName']));
			$page_info4 .= sprintf("<input type=\"hidden\" name=\"amount\" value=\"%s\">\n",$payment_amount);
			$page_info4 .= sprintf("<input type=\"hidden\" name=\"currency_code\" value=\"%s\">\n",$currency_code);
			$page_info4 .= "<input type=\"hidden\" name=\"button_subtype\" value=\"services\">\n";
			$page_info4 .= "<input type=\"hidden\" name=\"no_note\" value=\"0\">\n";
			$page_info4 .= "<input type=\"hidden\" name=\"cn\" value=\"Add special instructions\">\n";
			$page_info4 .= "<input type=\"hidden\" name=\"no_shipping\" value=\"1\">\n";
			$page_info4 .= "<input type=\"hidden\" name=\"rm\" value=\"1\">\n";
			$page_info4 .= sprintf("<input type=\"hidden\" name=\"custom\" value=\"%s|%s\">\n",$_SESSION['user_id'],rtrim($return_entries, '-'));
			$page_info4 .= sprintf("<input type=\"hidden\" name=\"return\" value=\"%s\">\n",rtrim($return, '-'));
			$page_info4 .= sprintf("<input type=\"hidden\" name=\"cancel_return\" value=\"%s\">\n",$base_url."index.php?section=pay&msg=11");
			if ((isset($_SESSION['prefsPaypalIPN'])) && ($_SESSION['prefsPaypalIPN'] == 1) && (TESTING)) $page_info4 .= "<input type=\"hidden\" name=\"test_ipn\" value=\"1\">\n";
			$page_info4 .= "<input type=\"hidden\" name=\"bn\" value=\"PP-BuyNowBF:btn_paynowCC_LG.gif:NonHosted\">\n";
			$page_info4 .= "<button type=\"button\" name=\"btn\" id=\"submitBtn\" data-toggle=\"modal\" data-target=\"#confirm-submit\" class=\"btn btn-primary\" /><span class=\"fa fa-paypal\"></span> ".$label_pay_with_paypal."</button>\n";
			$page_info4 .= "</form>\n";

			/*
			// If IPN is NOT enabled show this:
			$page_info4 .= "<form role=\"form\" id=\"formfield\" name=\"PayPal\" action=\"https://www.paypal.com/cgi-bin/webscr\" method=\"post\">";
			//$page_info4 .= "<form role=\"form\" id=\"formfield\" name=\"PayPal\" action=\"".$base_url."includes/process.inc.php?action=paypal\" method=\"post\">\n";
			$page_info4 .= "<input type=\"hidden\" name=\"action\" value=\"add_form\" />\n";
			$page_info4 .= "<input type=\"hidden\" name=\"cmd\" value=\"_xclick\">\n";
			$page_info4 .= sprintf("<input type=\"hidden\" name=\"business\" value=\"%s\">\n",$_SESSION['prefsPaypalAccount']);
			$page_info4 .= sprintf("<input type=\"hidden\" name=\"item_name\" value=\"%s, %s - %s Payment\">\n",$_SESSION['brewerLastName'],$_SESSION['brewerFirstName'],$_SESSION['contestName']);
			$page_info4 .= sprintf("<input type=\"hidden\" name=\"amount\" value=\"%s\">\n",$payment_amount);
			$page_info4 .= sprintf("<input type=\"hidden\" name=\"currency_code\" value=\"%s\">\n",$currency_code);
			$page_info4 .= "<input type=\"hidden\" name=\"button_subtype\" value=\"services\">\n";
			$page_info4 .= "<input type=\"hidden\" name=\"no_note\" value=\"0\">\n";
			$page_info4 .= "<input type=\"hidden\" name=\"cn\" value=\"Add special instructions\">\n";
			$page_info4 .= "<input type=\"hidden\" name=\"no_shipping\" value=\"1\">\n";
			$page_info4 .= "<input type=\"hidden\" name=\"rm\" value=\"1\">\n";
			if (($_SESSION['prefsPaypalIPN'] == 1) && (TESTING)) $page_info4 .= "<input type=\"hidden\" name=\"test_ipn\" value=\"1\">\n";
			$page_info4 .= sprintf("<input type=\"hidden\" name=\"custom\" value=\"%s|%s\">\n",$_SESSION['user_id'],rtrim($return_entries, '-'));
			$page_info4 .= sprintf("<input type=\"hidden\" name=\"return\" value=\"%s\">\n",rtrim($return, '-'));
			$page_info4 .= sprintf("<input type=\"hidden\" name=\"cancel_return\" value=\"%s\">\n",$base_url."index.php?section=pay&msg=11");
			$page_info4 .= "<input type=\"hidden\" name=\"bn\" value=\"PP-BuyNowBF:btn_paynowCC_LG.gif:NonHosted\">\n";
			$page_info4 .= "<button type=\"button\" name=\"btn\" id=\"submitBtn\" data-toggle=\"modal\" data-target=\"#confirm-submit\" class=\"btn btn-primary\" /><span class=\"fa fa-paypal\"></span> Pay with PayPal</button>\n";
			$page_info4 .= "</form>";
			*/

			$page_info4 .= "<!-- Form submit confirmation modal -->";
			$page_info4 .= "<!-- Refer to bcoem_custom.js for configuration -->";
			$page_info4 .= "<div class=\"modal fade\" id=\"confirm-submit\" tabindex=\"-1\" role=\"dialog\" aria-hidden=\"true\">";
			$page_info4 .= "<div class=\"modal-dialog\">";
			$page_info4 .= "<div class=\"modal-content\">";
			$page_info4 .= "<div class=\"modal-header\">";
			$page_info4 .= "<button type=\"button\" class=\"close\" data-dismiss=\"modal\" aria-label=\"Close\"><span aria-hidden=\"true\">&times;</span></button>";

			if ((isset($_SESSION['prefsPaypalIPN'])) && ($_SESSION['prefsPaypalIPN'] == 1)) $page_info4 .= sprintf("<h4 class=\"modal-title\">%s</h4>",$pay_text_031);
			else $page_info4 .= sprintf("<h4 class=\"modal-title\">%s</h4>",$pay_text_022);

			$page_info4 .= "</div>";

			if ((isset($_SESSION['prefsPaypalIPN'])) && ($_SESSION['prefsPaypalIPN'] == 1)) $page_info4 .= sprintf("<div class=\"modal-body\"><p>%s</p>",$pay_text_030);
			else $page_info4 .= sprintf("<div class=\"modal-body\"><p>%s</p>",$pay_text_021);

			$page_info4 .= "</div>";
			$page_info4 .= "<div class=\"modal-footer\">";
			$page_info4 .= sprintf("<button type=\"button\" class=\"btn btn-danger\" data-dismiss=\"modal\">%s</button>",$label_cancel);
			$page_info4 .= sprintf("<a href=\"#\" id=\"submit\" class=\"btn btn-success\">%s</a>",$label_understand);
			$page_info4 .= "</div>";
			$page_info4 .= "</div>";
			$page_info4 .= "</div>";
			$page_info4 .= "</div>";

			/*
			if ($_SESSION['prefsGoogle'] == "Y") {
				// Google Wallet
				$header2_5 .= "<h2>Google Wallet</h2>";
				$page_info5 .= "<p>Click the &quot;Buy Now&quot; button below to pay online using Google Wallet.";
				if ($_SESSION['prefsTransFee'] == "Y") $page_info5 .= sprintf(" Please note that a transaction fee of %s will be added into your total.</p>",$currency_symbol.$fee);
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
		$header1_7 .= sprintf("<h2>%s</h2>",$label_fee_discount);
		$page_info7 .= sprintf("<p>%s</p>",$pay_text_023);
		$page_info7 .= "<form class=\"form-inline\" action=\"".$base_url."includes/process.inc.php?action=check_discount&amp;dbTable=".$brewer_db_table."&amp;id=".$row_brewer['uid']."\" method=\"POST\" name=\"form1\" id=\"form1\">";
		$page_info7 .= sprintf("
		<div class=\"form-group\"><!-- Form Group NOT REQUIRED Text Input -->
				<label for=\"brewerDiscount\" class=\"sr-only\">%s</label>
					<!-- Input Here -->
					<input class=\"form-control\" name=\"brewerDiscount\" type=\"text\" value=\"\" placeholder=\"\" autofocus>
			</div><!-- ./Form Group -->
		",$label_discount_code);
		$page_info7 .= sprintf("<input type=\"submit\" class=\"btn btn-primary\" value=\"%s\">",$label_verify);
		$page_info7 .= "</form>";
	}

	if (($total_entry_fees > 0) && ($total_entry_fees == $total_paid_entry_fees)) $page_info6 .= sprintf("<span class=\"fa fa-lg fa-check-circle text-success\"></span> %s</p>",$pay_text_024);
	if (($total_entry_fees == 0) && ($_SESSION['contestEntryFee'] > 0)) $page_info6 .= sprintf("<p>%s</p>",$pay_text_025);
	else $page_info6 .= sprintf("<span class=\"fa fa-lg fa-check-circle text-success\"></span> %s</p>",$pay_text_032);

	if (($_SESSION['prefsPayToPrint'] == "Y") && ($unconfirmed > 0)) $warning1 .= sprintf("<div class=\"alert alert-danger\"><span class=\"fa fa-lg fa-exclamation-circle\"></span> <strong>%s</strong> %s</div>",$pay_text_026,$pay_text_027);


	// --------------------------------------------------------------
	// Display
	// --------------------------------------------------------------



	if ($total_entry_fees > 0) {

		if (($_SESSION['prefsPayToPrint'] == "N") && (($totalRows_log - $totalRows_log_confirmed) > 0)) $warning2 .= sprintf("<div class=\"alert alert-warning\"><span class=\"fa fa-lg fa-exclamation-triangle\"> <strong>%s</strong> %s</div>",$pay_text_028,$pay_text_029);

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

} // end pay not disabled
?>
