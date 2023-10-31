<?php
/**
 * Module:      pay.sec.php
 * Description: This module dispays payment information based upon the competition-
                specific payment preferences.
 *
 */

if (TESTING) {
	if ((isset($_SESSION['prefsPaypalIPN'])) && ($_SESSION['prefsPaypalIPN'] == 1)) $paypal_env = $base_url."includes/process.inc.php?section=paypal&action=paypal";
	else $paypal_env = "https://www.sandbox.paypal.com/cgi-bin/webscr";
}

else {
	if ((isset($_SESSION['prefsPaypalIPN'])) && ($_SESSION['prefsPaypalIPN'] == 1)) $paypal_env = $base_url."includes/process.inc.php?section=paypal&action=paypal";
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
	$primary_page_info .= "<p class=\"lead\">";
	if ($total_to_pay == 0) $primary_page_info .= sprintf("<small><span class=\"fa fa-lg fa-check-circle text-success\"></span> %s <strong class=\"text-success\">%s</strong>.",$pay_text_008,$currency_symbol.number_format($total_entry_fees,2));
	else $primary_page_info .= sprintf("<small><span class=\"fa fa-lg fa-exclamation-triangle text-danger\"></span> %s <strong class=\"text-success\">%s</strong>.",$pay_text_008,$currency_symbol.number_format($total_entry_fees,2));
	if ($total_to_pay == 0) $primary_page_info .= sprintf(" %s <strong class=\"text-success\">%s</strong>",$pay_text_009,$currency_symbol.number_format($total_to_pay,2));
	else $primary_page_info .= sprintf(" %s <strong class=\"text-danger\">%s</strong>",$pay_text_009,$currency_symbol.number_format($total_to_pay,2));
	if (($_SESSION['prefsTransFee'] == "Y") && ($total_to_pay > 0)) $primary_page_info .= "<strong><span class=\"text-primary\">*</span></strong>";
	$primary_page_info .= ".</small></p>";

	if (($total_not_paid == 0) || ($total_to_pay == 0)) $primary_page_info .= sprintf("<p class=\"lead\"><small><span class=\"fa fa-lg fa-check-circle text-success\"></span> %s</p></small></p>",$pay_text_010);


	else {
		$primary_page_info .= "<p class=\"lead\"><small>";
		$primary_page_info .= sprintf("<span class=\"fa fa-lg fa-exclamation-triangle text-danger\"></span>  %s <strong class=\"text-danger\">%s %s ",$pay_text_011,$total_not_paid,$pay_text_012);
		if ($total_not_paid == "1") $primary_page_info .= sprintf("%s</strong>:",strtolower($label_entry)); else $primary_page_info .= sprintf("%s</strong>:",strtolower($label_entries));
		$primary_page_info .= "</small></p>";
		$primary_page_info .= "<ol>";
			do {
				if ($row_log_confirmed['brewPaid'] != "1") {

					$entry_name = html_entity_decode($row_log_confirmed['brewName'],ENT_QUOTES|ENT_XML1,"UTF-8");
    				$entry_name = htmlentities($entry_name,ENT_QUOTES|ENT_SUBSTITUTE|ENT_HTML5,"UTF-8");
					if ($_SESSION['prefsStyleSet'] == "BA") $style = $row_log_confirmed['brewStyle'];
					else $style = "Style ".$row_log_confirmed['brewCategory'].$row_log_confirmed['brewSubCategory'];
					$entry_no = sprintf("%06s",$row_log_confirmed['id']);
					$primary_page_info .= sprintf("<li>Entry #%s: <em>%s</em> (%s)</li>",$entry_no,$entry_name,$style);
					$entries .= sprintf("%06s",$row_log_confirmed['id']).", ";
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

			/**
			 * August 1, 2021
			 * PayPal fees were split. Checkout transaction rate is 3.49% + fixed fee.
			 * @see https://www.paypal.com/us/webapps/mpp/merchant-fees#statement-2
			 * 
			 * June 23, 2022
			 * Adjusted PayPal fixed fees by currency - the 0.49 flat fee used 
			 * previously wasn't accurate for all currencies accepted by PayPal.
			 * @see https://www.paypal.com/us/webapps/mpp/merchant-fees#fixed-fees-commercialtrans
			 * 
			 * Recalculated fees using a more accurate formula that better aligns 
			 * with PayPal's calculations. This way, comps who add merchant fees 
			 * will actually end up with the correct amount after those fees are 
			 * deducted from the paid total.
			 * @see https://github.com/geoffhumphrey/brewcompetitiononlineentry/issues/1317
			 */
			
			if ($_SESSION['prefsTransFee'] == "Y") {
				
				$pp_fixed_fees_arr = array(
					"$" => 0.49,
					"R$" => 2.90,
					"pound" => 0.39,
					"euro" => 0.39,
					"A$" => 0.59,
					"C$" => 0.59,
					"H$" => 3.79,
					"N$" => 0.69,
					"S$" => 0.69,
					"T$" => 14.00,
					"Ft" => 149.00,
					"shekel" => 1.60,
					"yen" => 49.00,
					"nkr" => 3.90,
					"kr" => 2.90,
					"RM" => 2.00,
					"M$" => 9.00,
					"phpeso" => 25.00,
					"pol" => 1.89,
					"p." => 39.00,
					"skr" => 4.09,
					"sfranc" => 0.49,
					"baht" => 15.00,
				);

				if ((isset($_SESSION['prefsCurrency'])) && (array_key_exists($_SESSION['prefsCurrency'],$pp_fixed_fees_arr))) $pp_fixed_fee = $pp_fixed_fees_arr[$_SESSION['prefsCurrency']];
				else $pp_fixed_fee = 0;

				$payment_amount = (($total_to_pay + $pp_fixed_fee) / 0.9651);
				$fee = number_format($payment_amount - $total_to_pay, 2, '.', '');


			} else {
				$payment_amount = $total_to_pay;
			}

			// Online
			$header1_3 .= sprintf("<h2>%s</h2>",$label_pay_online);
			$page_info3 .= sprintf("<p>%s</p>", $pay_text_017);

			// PayPal
			$header2_4 .= "<h3>PayPal <span class=\"fa fa-lg fa-cc-paypal\"></span> <span class=\"fa fa-lg fa-cc-visa\"></span> <span class=\"fa fa-lg fa-cc-mastercard\"></span> <span class=\"fa fa-lg fa-cc-discover\"></span> <span class=\"fa fa-lg fa-cc-amex\"></span></h3>";
			$page_info4 .= sprintf("<p>%s</p>",$pay_text_018);

			$page_info4 .= "<form role=\"form\" id=\"formfield\" name=\"PayPal\" action=\"".$paypal_env."\" method=\"post\">\n";
			$page_info4 .= "<input type=\"hidden\" name=\"action\" value=\"add_form\" />\n";
			$page_info4 .= "<input type=\"hidden\" name=\"cmd\" value=\"_xclick\">\n";
			$page_info4 .= sprintf("<input type=\"hidden\" name=\"business\" value=\"%s\">\n",$_SESSION['prefsPaypalAccount']);
			if ($_SESSION['prefsProEdition'] == 1) $page_info4 .= sprintf("<input type=\"hidden\" name=\"item_name\" value=\"%s - %s - %s\">\n",$_SESSION['brewerBreweryName'],remove_accents($_SESSION['contestName']),$paypal_response_text_009);
			else $page_info4 .= sprintf("<input type=\"hidden\" name=\"item_name\" value=\"%s, %s - %s - %s\">\n",$_SESSION['brewerLastName'],$_SESSION['brewerFirstName'],remove_accents($_SESSION['contestName']),$paypal_response_text_009);
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
			$page_info4 .= "<div class=\"row\" style=\"margin-bottom:20px;\">";
			$page_info4 .= "<div class=\"col-sm-12 col-md-3 col-lg-2\">";
			$page_info4 .= "<input type=\"hidden\" name=\"bn\" value=\"PP-BuyNowBF:btn_paynowCC_LG.gif:NonHosted\">\n";
			$page_info4 .= "<button type=\"button\" name=\"btn\" id=\"submitBtn\" data-toggle=\"modal\" data-target=\"#confirm-submit\" class=\"btn btn-primary\" /><span class=\"fa fa-paypal\"></span> ".$label_pay_with_paypal."</button>\n";
			$page_info4 .= "</div>";
			$page_info4 .= "<div class=\"col-sm-12 col-md-9 col-lg-10\">";
			if ($_SESSION['prefsTransFee'] == "Y") $page_info4 .= sprintf("<p><strong class=\"text-primary\">*%s %s %s</strong></p>",$pay_text_019,$currency_symbol.$fee,$pay_text_020);
			$page_info4 .= "</div>";
			$page_info4 .= "</div>";
			$page_info4 .= "</form>\n";

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
				$page_info5 .= "<p>Select the &quot;Buy Now&quot; button below to pay online using Google Wallet.";
				if ($_SESSION['prefsTransFee'] == "Y") $page_info5 .= sprintf(" Please note that a transaction fee of %s will be added into your total.</p>",$currency_symbol.$fee);
				$page_info5 .= "<div class='error'>To make sure your Google Wallet payment is marked &quot;paid&quot; on <em>this site</em>, please select the &quot;Return to...&quot; link on Google Wallet's confirmation screen after you have sent your payment.</div>";
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

	if (($total_entry_fees > 0) && ($total_entry_fees == $total_paid_entry_fees)) 
		$page_info6 .= sprintf("<p class=\"text-success\"><span class=\"fa fa-lg fa-check-circle\"></span> <strong>%s</strong></p>",$pay_text_024);
	if (($total_entry_fees == 0) && ($_SESSION['contestEntryFee'] > 0)) 
		$page_info6 .= sprintf("<p>%s</p>",$pay_text_025);
	else 
		$page_info6 .= sprintf("<p class=\"text-success\"><span class=\"fa fa-lg fa-check-circle\"></span> <strong>%s</strong></p>",$pay_text_032);

	if (($_SESSION['prefsPayToPrint'] == "Y") && ($unconfirmed > 0)) $warning1 .= sprintf("<div class=\"alert alert-danger\"><span class=\"fa fa-lg fa-exclamation-circle\"></span> <strong>%s</strong> %s</div>",$pay_text_026,$pay_text_027);

	/**
	 * --------------------------------------------------------------
	 * Display
	 * --------------------------------------------------------------
	 */

	if ($total_entry_fees > 0) {

		if (($_SESSION['prefsPayToPrint'] == "N") && (($totalRows_log - $totalRows_log_confirmed) > 0)) $warning2 .= sprintf("<p class=\"alert alert-warning\"><span class=\"fa fa-lg fa-exclamation-triangle\"></span> <strong>%s</strong> %s</p>",$pay_text_028,$pay_text_029);

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

} // end if payment options are not disabled
?>
