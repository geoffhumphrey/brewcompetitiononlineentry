<div id="header">	
	<div id="header-inner"><h1>Pay Entry Fees</h1></div>
</div>
<?php 
if ($msg == "1") echo "<div class=\"error\">Your payment has been received. Thanks and best of luck in the competition.</div>"; 
if ($msg == "2") echo "<div class=\"error\">Your payment has been cancelled.</div>"; 
$fee = (($totalRows_log * $row_contest_info['contestEntryFee']) * .029);
?>
<p>You currently have logged <a href="index.php?section=list"><?php echo $totalRows_log; ?> entries</a> in the <?php echo $row_contest_info['contestName']; ?> contest.  Your total entry fees are <?php echo $row_prefs['prefsCurrency']; echo ($totalRows_log * $row_contest_info['contestEntryFee']).".00" ; ?>.</p>
<?php if ($row_prefs['prefsCash'] == "Y") { ?>
<h2>Cash</h2>
<p>Attach cash payment for the entire entry amount (currently <?php echo $row_prefs['prefsCurrency']; echo ($totalRows_log * $row_contest_info['contestEntryFee']).".00"; ?>) in a <em>sealed envelope</em> to one of  your bottles.</p>
<?php } ?>
<?php if ($row_prefs['prefsCheck'] == "Y") { ?>
<h2>Checks</h2>
<p>Attach a check for the entire entry amount (currently <?php echo $row_prefs['prefsCurrency']; echo ($totalRows_log * $row_contest_info['contestEntryFee']).".00"; ?>) to one of your bottles. Checks should be made out to <em><?php echo $row_prefs['prefsCheckPayee']; ?></em>.</p>
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
<br /><input align="left" type="image" src="https://www.paypal.com/en_US/i/btn/btn_paynowCC_LG.gif" border="0" name="submit" alt="Pay your contest entry fees with PayPal" title="Pay your contest entry fees with PayPal"></p>
<input type="hidden" name="cmd" value="_xclick">
<input type="hidden" name="business" value="<?php echo $row_prefs['prefsPaypalAccount']; ?>">
<input type="hidden" name="item_name" value="<?php echo $row_contest_info['contestName']; ?> Contest Entry Payment for <?php echo $row_brewer['brewerLastName'].", ".$row_brewer['brewerFirstName']." (".$totalRows_log." Entries)"; ?>">
<input type="hidden" name="amount" value="<?php if ($row_prefs['prefsTransFee'] == "Y") echo (($totalRows_log * $row_contest_info['contestEntryFee']) + number_format($fee, 2, '.', '')); else echo ($totalRows_log * $row_contest_info['contestEntryFee']).".00"; ?>">
<input type="hidden" name="cn" value="Message to the organizers:">
<input type="hidden" name="no_shipping" value="1">
<input type="hidden" name="currency_code" value="<?php echo $currency_code; ?>">
<input type="hidden" name="rm" value="1">
<input type="hidden" name="return" value="<?php echo "http://".$_SERVER['SERVER_NAME'].$_SERVER['SCRIPT_NAME']."?section=pay&msg=1"; ?>">
<input type="hidden" name="cancel_return" value="<?php echo "http://".$_SERVER['SERVER_NAME'].$_SERVER['SCRIPT_NAME']."?section=pay&msg=2"; ?>">
<input type="hidden" name="bn" value="PP-BuyNowBF:btn_paynowCC_LG.gif:NonHosted">
</form>
<br /><br /><br />
<?php } ?>

