<?php
/*
echo $total_entry_fees;
$total_entry_fees = unpaid_fees($total_not_paid, $row_contest_info['contestEntryFeeDiscount'],$row_contest_info['contestEntryFeeDiscountNum'], $cap, $row_contest_info['contestEntryFee'], $row_contest_info['contestEntryFee2']);

if ($row_contest_info['contestEntryFeeDiscount'] == "Y") {
	$discount = discount_display($total_not_paid,$row_contest_info['contestEntryFeeDiscountNum'],$row_contest_info['contestEntryFee'], $row_contest_info['contestEntryFee2'], $cap);
}

if ($view != "default") {
$a = explode("-", $view);
	foreach ($a as $value) { 
	$updateSQL = "UPDATE brewing SET brewPaid='Y' WHERE id='$value'";
		mysql_select_db($database, $brewing);
		$result1 = mysql_query($updateSQL, $brewing) or die(mysql_error());
	}
}
*/
if ($msg != "default") echo $msg_output;
?>
<?php if ($total_entry_fees > 0) { ?>
<p><span class="icon"><img src="images/money.png"  border="0" alt="Entry Fees" title="Entry Fees"></span>You currently have <?php echo $total_not_paid; ?> <strong>unpaid</strong> <?php if ($total_not_paid == "1") echo "entry. "; else echo "entries. "; ?>
Your total entry fees are <?php echo $row_prefs['prefsCurrency'].$total_entry_fees.". You need to pay ".$row_prefs['prefsCurrency'].$total_to_pay."."; ?></p>
<p>Fees are: <?php echo $row_prefs['prefsCurrency'].number_format($row_contest_info['contestEntryFee'], 2); ?> per entry; <?php if ($row_contest_info['contestEntryFeeDiscount'] == "Y") echo $row_prefs['prefsCurrency'].number_format($row_contest_info['contestEntryFee2'], 2)." per entry after ".$row_contest_info['contestEntryFeeDiscountNum']." entries. "; if ($row_contest_info['contestEntryCap'] != "") echo $row_prefs['prefsCurrency'].number_format($row_contest_info['contestEntryCap'], 2)." for unlimited entries. "; ?></p>
<?php } ?>
<?php if ($total_entry_fees == $total_paid_entry_fees) { ?><span class="icon"><img src="images/thumb_up.png"  border="0" alt="Entry Fees" title="Entry Fees"></span>Your fees have been paid. Thank you!<?php } ?>
<?php if ($total_entry_fees == 0) echo "You have not logged any entries yet."; ?>

<?php if (($total_entry_fees > 0) && ($view == "default")) { ?>
	<?php if ($row_prefs['prefsCash'] == "Y") { ?>
		<h2>Cash</h2>
		<p>Attach cash payment for the entire entry amount in a <em>sealed envelope</em> to one of  your bottles.</p>
		<p><span class="required"> Your returned scoresheets will serve as your entry receipt.</span></p>
	<?php } ?>
	<?php if ($row_prefs['prefsCheck'] == "Y") { ?>
		<h2>Checks</h2>
		<p>Attach a check for the entire entry amount to one of your bottles. Checks should be made out to <em><?php echo $row_prefs['prefsCheckPayee']; ?></em>.</p>
		<p><span class="required"> Your check carbon or cashed check is your entry receipt.</p>
	<?php } ?>
	<?php if  (($row_prefs['prefsPaypal'] == "Y") || ($row_prefs['prefsGoogle'] == "Y")) { 
		switch ($row_prefs['prefsCurrency']) {
			case "&pound;": $currency_code = "GBP";
			break;
			case "&euro;": $currency_code = "EUR";
			break;
			case "&yen;": $currency_code = "JPY";
			break;
			default: $currency_code = "USD";
		}

?>
<h2>Pay Online</h2>
<p><span class="required"> Your payment confirmation email is your entry receipt. Include a copy with your entries as proof of payment.</p>
<p>You are paying for the following entries:</p>
	<ol>
    <?php 
	$return = "http://".$_SERVER['SERVER_NAME'].$_SERVER['REQUEST_URI']."&msg=1&view=";
	do { ?>
    	<li><?php echo "Entry #".$row_log['id'].": ".$row_log['brewName']." (Category ".$row_log['brewCategory'].$row_log['brewSubCategory'].")"; ?></li>
    <?php 
	$return .= $row_log['id']."-";
	} while ($row_log = mysql_fetch_assoc($log)); 
	?>
    </ol>
<?php } ?>
<?php if ($row_prefs['prefsPaypal'] == "Y") { ?>
<p>Click the "Pay Now" button below to pay online using PayPal. <?php if ($row_prefs['prefsTransFee'] == "Y") { ?>Please note that a PayPal transaction fee of <?php echo $row_prefs['prefsCurrency']; echo number_format(($total_to_pay * .029), 2, '.', ''); ?> will be added into your total.<?php } ?></p>
<p class="error"> To make sure your PayPal payment is marked "paid" on <em>this site</em>, please click the "Return to <?php echo $row_prefs['prefsPaypalAccount']; ?>" link on PayPal's confirmation screen after you have sent your payment.</p>
<form action="https://www.paypal.com/cgi-bin/webscr" method="post">
	<table class="dataTable">
		<tr>
    		<td>
			<input align="left" type="image" src="https://www.paypal.com/en_US/i/btn/btn_paynowCC_LG.gif" border="0" name="submit" value="" class="paypal" alt="Pay your competition entry fees with PayPal" title="Pay your compeition entry fees with PayPal"></p>
			<input type="hidden" name="cmd" value="_xclick">
			<input type="hidden" name="business" value="<?php echo $row_prefs['prefsPaypalAccount']; ?>">
			<input type="hidden" name="item_name" value="<?php echo $row_contest_info['contestName']; ?> Competition Entry Payment for <?php echo $row_brewer['brewerLastName'].", ".$row_brewer['brewerFirstName']." (".$total_not_paid." Entries)"; ?>">
			<input type="hidden" name="amount" value="<?php if ($row_prefs['prefsTransFee'] == "Y") echo $total_to_pay + number_format(($total_to_pay * .029), 2, '.', ''); else echo number_format($total_to_pay, 2); ?>">
			<input type="hidden" name="cn" value="Message to the organizers:">
			<input type="hidden" name="no_shipping" value="1">
			<input type="hidden" name="currency_code" value="<?php echo $currency_code; ?>">
			<input type="hidden" name="rm" value="1">
			<input type="hidden" name="return" value="<?php echo rtrim($return, "-"); ?>">
			<input type="hidden" name="cancel_return" value="<?php echo "http://".$_SERVER['SERVER_NAME'].$_SERVER['REQUEST_URI']."&msg=2"; ?>">
			<input type="hidden" name="bn" value="PP-BuyNowBF:btn_paynow_LG.gif:NonHosted">
			</td>
   		</tr>
	</table>
</form>
<?php } if ($row_prefs['prefsGoogle'] == "Y") { ?>
<p>Click the "Buy Now" button below to pay online using Google Checkout.</p>
<form action="https://checkout.google.com/api/checkout/v2/checkoutForm/Merchant/<?php echo  $row_prefs['prefsGoogleMerchantID']; ?>" id="BB_BuyButtonForm" method="post" name="BB_BuyButtonForm" target="_top">
    <table class="dataTable">
        <tr>
            <td>
                <select name="item_selection_1">
                    <option value="1"><?php echo $row_prefs['prefsCurrency'].number_format($total_entry_fees, 2);?> - <?php echo $row_contest_info['contestName']; ?> Competition Entry Payment</option>
                </select>
                <input name="item_option_name_1" type="hidden" value="<?php echo $row_contest_info['contestName']; ?> Competition Entry Payment for <?php echo $row_brewer['brewerLastName'].", ".$row_brewer['brewerFirstName']." (".$totalRows_log." Entries)"; ?>"/>
                <input name="item_option_price_1" type="hidden" value="<?php echo number_format($total_entry_fees, 2);?>"/>
                <input name="item_option_quantity_1" type="hidden" value="1"/>
                <input name="item_option_currency_1" type="hidden" value="<?php echo $currency_code; ?>"/>
                <input type="hidden" name="checkout-flow-support.merchant-checkout-flow-support.continue-shopping-url" value="http://www.example.com"/> 
            </td>
        </tr>
        <tr>
            <td>
                <input style="border:none" alt="Google Checkout" src="https://checkout.google.com/buttons/buy.gif?merchant_id=<?php echo  $row_prefs['prefsGoogleMerchantID']; ?>&amp;w=117&amp;h=48&amp;style=white&amp;variant=text&amp;loc=en_US" type="image"/>
            </td>
        </tr>
    </table>
</form>
<?php } ?>
<?php } ?>
