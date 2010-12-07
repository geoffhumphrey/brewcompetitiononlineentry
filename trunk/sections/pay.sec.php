<?php
$entry_total_final = unpaid_fees($total_not_paid, $row_contest_info['contestEntryFeeDiscount'],$row_contest_info['contestEntryFeeDiscountNum'], $cap, $row_contest_info['contestEntryFee'], $row_contest_info['contestEntryFee2']);

if ($row_contest_info['contestEntryFeeDiscount'] == "Y") {
	$discount = discount_display($total_not_paid,$row_contest_info['contestEntryFeeDiscountNum'],$row_contest_info['contestEntryFee'], $row_contest_info['contestEntryFee2'], $cap);
}
/*
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
<?php if ($entry_total_final > 0) { ?>
<p><span class="icon"><img src="images/money.png"  border="0" alt="Entry Fees" title="Entry Fees"></span>You currently have <?php echo $total_not_paid; ?> <strong>unpaid</strong> <?php if ($total_not_paid == "1") echo "entry. "; else echo "entries. "; if ($entry_total_final > 0) { ?>
Your total entry fees are <?php  echo $row_prefs['prefsCurrency']; if (($row_contest_info['contestEntryFeeDiscount'] == "Y") && ($totalRows_log > $row_contest_info['contestEntryFeeDiscountNum'])) { echo number_format($discount["d"], 2); if ($discount["d"] < $cap) echo " (".$row_prefs['prefsCurrency'].number_format($discount["b"], 2)." for the first ".$row_contest_info['contestEntryFeeDiscountNum']." entries, ".$row_prefs['prefsCurrency'].number_format($discount["c"], 2)." for the remaining ".($totalRows_log - $row_contest_info['contestEntryFeeDiscountNum'])." entries)"; } else echo number_format($entry_total_final, 2) ?>.<?php  } ?></p>
<?php } elseif ($totalRows_all == $totalRows_paid) { ?><span class="icon"><img src="images/thumb_up.png"  border="0" alt="Entry Fees" title="Entry Fees"></span>Your fees have been paid. Thank you!<?php } else echo "You have not logged any entries yet."; ?>

<?php if (($entry_total_final > 0) && ($view == "default")) { ?>
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
if     ($row_prefs['prefsCurrency'] == "&pound;") $currency_code = "GBP";
elseif ($row_prefs['prefsCurrency'] == "&euro;")  $currency_code = "EUR";
elseif ($row_prefs['prefsCurrency'] == "&yen;")   $currency_code = "JPY";
else   $currency_code = "USD";
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
<p>Click the "Pay Now" button below to pay online using PayPal. <?php if ($row_prefs['prefsTransFee'] == "Y") { ?>Please note that a PayPal transaction fee of <?php echo $row_prefs['prefsCurrency']; if ($row_contest_info['contestEntryFeeDiscount'] == "Y") echo number_format(($discount["d"] * .029), 2, '.', ''); else echo number_format(($entry_total_final * .029), 2, '.', ''); ?> will be added into your total.<?php } ?></p>
<p class="error"> To make sure your PayPal payment is marked "paid" on <em>this site</em>, please click the "Return to <?php echo $row_prefs['prefsPaypalAccount']; ?>" link on PayPal's confirmation screen after you have sent your payment.</p>
<form action="https://www.paypal.com/cgi-bin/webscr" method="post">
	<table class="dataTable">
		<tr>
    		<td>
			<input align="left" type="image" src="https://www.paypal.com/en_US/i/btn/btn_paynowCC_LG.gif" border="0" name="submit" value="" class="paypal" alt="Pay your competition entry fees with PayPal" title="Pay your compeition entry fees with PayPal"></p>
			<input type="hidden" name="cmd" value="_xclick">
			<input type="hidden" name="business" value="<?php echo $row_prefs['prefsPaypalAccount']; ?>">
			<input type="hidden" name="item_name" value="<?php echo $row_contest_info['contestName']; ?> Competition Entry Payment for <?php echo $row_brewer['brewerLastName'].", ".$row_brewer['brewerFirstName']." (".$totalRows_log." Entries)"; ?>">
			<input type="hidden" name="amount" value="<?php if ($row_prefs['prefsTransFee'] == "Y") { if ($row_contest_info['contestEntryFeeDiscount'] == "Y") echo $discount["d"] + number_format(($discount["d"] * .029), 2, '.', ''); else echo $entry_total_final + number_format(($entry_total_final * .029), 2, '.', ''); } else echo number_format($entry_total_final, 2); ?>">
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
                    <option value="1"><?php echo $row_prefs['prefsCurrency'].number_format($entry_total_final, 2);?> - <?php echo $row_contest_info['contestName']; ?> Competition Entry Payment</option>
                </select>
                <input name="item_option_name_1" type="hidden" value="<?php echo $row_contest_info['contestName']; ?> Competition Entry Payment for <?php echo $row_brewer['brewerLastName'].", ".$row_brewer['brewerFirstName']." (".$totalRows_log." Entries)"; ?>"/>
                <input name="item_option_price_1" type="hidden" value="<?php echo number_format($entry_total_final, 2);?>"/>
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
