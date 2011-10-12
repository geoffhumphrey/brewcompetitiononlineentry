<?php
/**
 * Module:      pay.sec.php 
 * Description: This module dispays payment information based upon the competition-
                specific payment preferences. 
 * 
 */


$bid = $row_name['uid'];
//echo $bid."<br>";

if ($msg == "10") {
	// If redirected from PayPal, update the brewer table to mark entries as paid
	$a = explode('-', $view);
	foreach (array_unique($a) as $value) {
		$updateSQL = "UPDATE brewing SET brewPaid='Y' WHERE id='".$value."';";
		//echo $updateSQL;
		mysql_select_db($database, $brewing);
		$Result1 = mysql_query($updateSQL, $brewing) or die(mysql_error());
	}
}

include (DB.'entries.db.php');
//echo $totalRows_entry_count."<br>";
//echo $query_log."<br>";
//echo $query_log_paid."<br>";
if ($msg != "default") echo $msg_output; 

$total_entry_fees = total_fees($row_contest_info['contestEntryFee'], $row_contest_info['contestEntryFee2'], $row_contest_info['contestEntryFeeDiscount'], $row_contest_info['contestEntryFeeDiscountNum'], $row_contest_info['contestEntryCap'], $row_contest_info['contestEntryFeePasswordNum'], $bid, $filter);
$total_paid_entry_fees = total_fees_paid($row_contest_info['contestEntryFee'], $row_contest_info['contestEntryFee2'], $row_contest_info['contestEntryFeeDiscount'], $row_contest_info['contestEntryFeeDiscountNum'], $row_contest_info['contestEntryCap'], $row_contest_info['contestEntryFeePasswordNum'], $bid, $filter);
$total_to_pay = $total_entry_fees - $total_paid_entry_fees; 
$total_not_paid = total_not_paid_brewer($row_user['id']);
/*
$total_entry_fees = total_fees($row_contest_info['contestEntryFee'], $row_contest_info['contestEntryFee2'], $row_contest_info['contestEntryFeeDiscount'], $row_contest_info['contestEntryFeeDiscountNum'], $row_contest_info['contestEntryCap'], $row_contest_info['contestEntryFeePasswordNum'], $bid, $filter);
$total_paid_entry_fees = total_fees_paid($row_contest_info['contestEntryFee'], $row_contest_info['contestEntryFee2'], $row_contest_info['contestEntryFeeDiscount'], $row_contest_info['contestEntryFeeDiscountNum'], $row_contest_info['contestEntryCap'], $row_contest_info['contestEntryFeePasswordNum'], $bid, $filter);
$total_to_pay = $total_entry_fees - $total_paid_entry_fees;
*/

//echo $total_entry_fees."<br>";
//echo $total_paid_entry_fees."<br>";
if ($total_entry_fees > 0) { 
//echo $query_brewer;
//echo $query_name;
?>
<p><span class="icon"><img src="images/help.png"  /></span><a class="thickbox" href="http://help.brewcompetition.com/files/pay_my_fees.html?KeepThis=true&amp;TB_iframe=true&amp;height=450&amp;width=800" title="BCOE&amp;M Help: Pay My Fees">Pay My Fees Help</a></p>
<p><span class="icon"><img src="images/money.png"  border="0" alt="Entry Fees" title="Entry Fees"></span>You currently have <?php echo $total_not_paid; ?> <strong>unpaid</strong> <?php if ($total_not_paid == "1") echo "entry. "; else echo "entries. "; ?> Your total entry fees are <?php echo $row_prefs['prefsCurrency'].$total_entry_fees.". You need to pay ".$row_prefs['prefsCurrency'].$total_to_pay."."; ?></p>
<p><span class="icon"><img src="images/money.png"  border="0" alt="Entry Fees" title="Entry Fees"></span>Fees are:</p>
<ul style="margin-bottom: 15px;">
	<li><?php if ($row_brewer['brewerDiscount'] == "Y") echo $row_prefs['prefsCurrency'].$row_contest_info['contestEntryFeePasswordNum']." per entry (discounted)."; else echo $row_prefs['prefsCurrency'].$row_contest_info['contestEntryFee']." per entry."; ?></li>
	<?php if (($row_contest_info['contestEntryFeeDiscount'] == "Y") && (($row_brewer['brewerDiscount'] == "Y") && ($row_contest_info['contestEntryFeePasswordNum'] > $row_contest_info['contestEntryFee2']))) { ?>
    <li><?php echo $row_prefs['prefsCurrency'].$row_contest_info['contestEntryFee2']." per entry after ".$row_contest_info['contestEntryFeeDiscountNum']." entries.	"; ?></li>   
    <?php } if ($row_contest_info['contestEntryCap'] != "") { ?>
    <li><?php echo $row_prefs['prefsCurrency'].$row_contest_info['contestEntryCap']." for unlimited entries.</li>"; ?></li>
    <?php } ?>
</ul>
<?php } ?>
<?php if (($total_entry_fees > 0) && ($total_entry_fees == $total_paid_entry_fees)) { ?><span class="icon"><img src="images/thumb_up.png"  border="0" alt="Entry Fees" title="Entry Fees"></span>Your fees have been paid. Thank you!<?php } ?>
<?php if ($total_entry_fees == 0) echo "You have not logged any entries yet."; ?>

<?php if (($row_brewer['brewerDiscount'] != "Y") && ($row_contest_info['contestEntryFeePassword'] != "") && ((($total_entry_fees > 0) && ($total_entry_fees != $total_paid_entry_fees)))) { ?>
<h2>Discounted Entry Fee</h2>
<p>Enter the code supplied by the competition organizers for a discounted entry fee.</p>
<form action="includes/process.inc.php?action=check_discount&amp;dbTable=brewer&amp;id=<?php echo $row_brewer['uid']; ?>" method="POST" name="form1" id="form1">
<table class="dataTable">
	<tr>
    	<td class="dataLabel" width="5%">Discount Code:</td>
    	<td class="data"><input name="brewerDiscount" type="text" class="submit" size="20"></td>
  	</tr>
</table>
<p><input type="submit" class="button" value="Submit Code"></p>
</form>
<?php } ?>
<?php if (($total_to_pay > 0) && ($view == "default")) { ?>
	<?php if ($row_prefs['prefsCash'] == "Y") { ?>
		<h2>Cash</h2>
		<p>Attach cash payment for the entire entry amount in a <em>sealed envelope</em> to one of  your bottles.</p>
		<p><span class="required"> Your returned score sheets will serve as your entry receipt.</span></p>
	<?php } ?>
	<?php if ($row_prefs['prefsCheck'] == "Y") { ?>
		<h2>Checks</h2>
		<p>Attach a check for the entire entry amount to one of your bottles. Checks should be made out to <em><?php echo $row_prefs['prefsCheckPayee']; ?></em>.</p>
		<p><span class="required"> Your check carbon or cashed check is your entry receipt.</span></p>
	<?php } ?>
	<?php if  ($row_prefs['prefsPaypal'] == "Y") { 
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
<p><span class="required"> Your payment confirmation email is your entry receipt. Include a copy with your entries as proof of payment.</span></p>
<p>You are paying for the following entries:</p>
	<ul>
    <?php 
	$return = "http://".$_SERVER['SERVER_NAME'].$_SERVER['REQUEST_URI']."&msg=10&view=";
	do { if ($row_log['brewPaid'] != "Y") { ?>
    	<li><?php echo "Entry #".$row_log['id'].": ".$row_log['brewName']." (Category ".$row_log['brewCategory'].$row_log['brewSubCategory'].")"; ?></li>
    <?php 
	$return .= $row_log['id']."-";
	  }
	} while ($row_log = mysql_fetch_assoc($log)); 
	?>
    </ul>
<?php } ?>
<?php if ($row_prefs['prefsPaypal'] == "Y") { ?>
<p>Click the "Pay Now" button below to pay online using PayPal. <?php if ($row_prefs['prefsTransFee'] == "Y") { ?>Please note that a PayPal transaction fee of <?php echo $row_prefs['prefsCurrency']; echo number_format(($total_to_pay * .029), 2, '.', ''); ?> will be added into your total.<?php } ?></p>
<p class="error"> To make sure your PayPal payment is marked "paid" on <em>this site</em>, please click the "Return to <?php echo $row_contest_info['contestHost']; ?>" link on PayPal's confirmation screen after you have sent your payment.</p>

<form name="PayPal" action="https://www.paypal.com/cgi-bin/webscr" method="post">
<input type="hidden" name="cmd" value="_xclick">
<input type="hidden" name="business" value="<?php echo $row_prefs['prefsPaypalAccount']; ?>">
<input type="hidden" name="item_name" value="<?php echo $row_contest_info['contestName']; ?> Payment for <?php echo $row_brewer['brewerLastName'].", ".$row_brewer['brewerFirstName']." (".$total_not_paid." Entries)"; ?>">
<input type="hidden" name="amount" value="<?php if ($row_prefs['prefsTransFee'] == "Y") echo $total_to_pay + number_format(($total_to_pay * .029), 2, '.', ''); else echo number_format($total_to_pay, 2); ?>">
<input type="hidden" name="currency_code" value="<?php echo $currency_code; ?>">
<input type="hidden" name="button_subtype" value="services">
<input type="hidden" name="no_note" value="0">
<input type="hidden" name="cn" value="Add special instructions">
<input type="hidden" name="no_shipping" value="1">
<input type="hidden" name="rm" value="1">
<input type="hidden" name="return" value="<?php echo rtrim($return, "-"); ?>">
<input type="hidden" name="cancel_return" value="<?php echo "http://".$_SERVER['SERVER_NAME'].$_SERVER['REQUEST_URI']."&msg=11"; ?>">
<input type="hidden" name="bn" value="PP-BuyNowBF:btn_paynowCC_LG.gif:NonHosted">
<input type="image" src="https://www.paypalobjects.com/en_US/i/btn/btn_paynowCC_LG.gif" border="0" name="submit" class="paypal" alt="Pay your competition entry fees with PayPal" title="Pay your compeition entry fees with PayPal">
</form>
<?php } ?>
<?php } ?>
