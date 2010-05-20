<?php 
if ($row_contest_info['contestEntryFeeDiscount'] == "N") $entry_total = $totalRows_log * $row_contest_info['contestEntryFee'];
if (($row_contest_info['contestEntryFeeDiscount'] == "Y") && ($totalRows_log > $row_contest_info['contestEntryFeeDiscountNum'])) {
	$discFee = ($totalRows_log - $row_contest_info['contestEntryFeeDiscountNum']) * $row_contest_info['contestEntryFee2'];
	$regFee = $row_contest_info['contestEntryFeeDiscountNum'] * $row_contest_info['contestEntryFee'];
	$entry_total = $discFee + $regFee;
	}

if (($row_contest_info['contestEntryFeeDiscount'] == "Y") && ($totalRows_log <= $row_contest_info['contestEntryFeeDiscountNum'])) {
	if ($totalRows_log > 0) $entry_total = $totalRows_log * $row_contest_info['contestEntryFee'];
	else $entry_total = "0"; 
} 
 
if (($row_contest_info['contestEntryCap'] != "") && ($entry_total > $row_contest_info['contestEntryCap'])) { $fee = ($row_contest_info['contestEntryCap'] * .029); $entry_total_final = $row_contest_info['contestEntryCap']; } else { $fee = ($entry_total * .029); $entry_total_final = $entry_total; }
if ($row_contest_info['contestEntryCap'] == "") { $fee = ($entry_total * .029); $entry_total_final = $entry_total; }
if ($msg != "default") echo $msg_output;
?>
<p>You currently have logged <a href="index.php?section=list"><?php echo $totalRows_log; ?> entries</a> in the <?php echo $row_contest_info['contestName']; ?> competition.  Your total entry fees are <?php echo $row_prefs['prefsCurrency']; echo number_format($entry_total_final, 2);
if (($row_contest_info['contestEntryFeeDiscount'] == "Y") && ($totalRows_log > $row_contest_info['contestEntryFeeDiscountNum'])) echo " (".$row_prefs['prefsCurrency'].number_format($regFee, 2)." for the first ".$row_contest_info['contestEntryFeeDiscountNum']." entries, ".$row_prefs['prefsCurrency'].number_format($discFee, 2)." for the remaining ".($totalRows_log - $row_contest_info['contestEntryFeeDiscountNum'])." entries)"; ?>.</p>
<?php if ($row_prefs['prefsCash'] == "Y") { ?>
<h2>Cash</h2>
<p>Attach cash payment for the entire entry amount (currently <?php echo $row_prefs['prefsCurrency']; echo number_format($entry_total_final, 2); ?>) in a <em>sealed envelope</em> to one of  your bottles.</p>
<p><span class="required">Your returned scoresheets will serve as your entry receipt.</span></p>
<?php } ?>
<?php if ($row_prefs['prefsCheck'] == "Y") { ?>
<h2>Checks</h2>
<p>Attach a check for the entire entry amount (currently <?php echo $row_prefs['prefsCurrency']; echo number_format($entry_total_final, 2); ?>) to one of your bottles. Checks should be made out to <em><?php echo $row_prefs['prefsCheckPayee']; ?></em>.</p>
<p><span class="required">Your check carbon or cashed check is your entry receipt.</p>
<?php } ?>
<?php if  ($row_prefs['prefsPaypal'] == "Y") { 
if     ($row_prefs['prefsCurrency'] == "&pound;") $currency_code = "GBP";
elseif ($row_prefs['prefsCurrency'] == "&euro;")  $currency_code = "EUR";
elseif ($row_prefs['prefsCurrency'] == "&yen;")   $currency_code = "JPY";
else   $currency_code = "USD";
?>
<form action="https://www.paypal.com/cgi-bin/webscr" method="post">
<h2>Pay Online</h2>
<p>Click the "Pay Now" button below to pay online using PayPal. <?php if ($row_prefs['prefsTransFee'] == "Y") { ?>Please note that a PayPal transaction fee of <?php echo $row_prefs['prefsCurrency'].number_format($fee, 2, '.', ''); ?> will be added into your total.<?php } ?></p>
<p><span class="required">Your payment email from PayPal is your entry receipt. Include a copy with your entries as proof of payment.</p>
<br />
<input align="left" type="submit" border="0" name="submit" value="" class="paypal" alt="Pay your contest entry fees with PayPal" title="Pay your contest entry fees with PayPal"></p>
<input type="hidden" name="cmd" value="_xclick">
<input type="hidden" name="business" value="<?php echo $row_prefs['prefsPaypalAccount']; ?>">
<input type="hidden" name="item_name" value="<?php echo $row_contest_info['contestName']; ?> Competition Entry Payment for <?php echo $row_brewer['brewerLastName'].", ".$row_brewer['brewerFirstName']." (".$totalRows_log." Entries)"; ?>">
<input type="hidden" name="amount" value="<?php if ($row_prefs['prefsTransFee'] == "Y") echo ($entry_total_final + number_format($fee, 2, '.', '')); else echo number_format($entry_total_final, 2); ?>">
<input type="hidden" name="cn" value="Message to the organizers:">
<input type="hidden" name="no_shipping" value="1">
<input type="hidden" name="currency_code" value="<?php echo $currency_code; ?>">
<input type="hidden" name="rm" value="1">
<input type="hidden" name="return" value="<?php echo "http://".$_SERVER['SERVER_NAME'].$_SERVER['REQUEST_URI']."&amp;msg=1"; ?>">
<input type="hidden" name="cancel_return" value="<?php echo "http://".$_SERVER['SERVER_NAME'].$_SERVER['REQUEST_URI']."&amp;msg=2"; ?>">
<input type="hidden" name="bn" value="PP-BuyNowBF:btn_paynowCC_LG.gif:NonHosted">
</form>
<br /><br /><br />
<?php } ?>

