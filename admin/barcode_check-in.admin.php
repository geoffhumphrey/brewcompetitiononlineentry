<?php
/**
 * Module:      barcode_check-in.admin.php
 * Description: Originally deployed as a "mod" for NHC 2013.
 *
 */

$fields = 15;
$maxlength = 6;
$entry_list = "";
$flag_jnum = "";
$flag_enum = "";
$jnum_info = "";

$barcode_text_000 = "Check-In Entries with a Barcode Reader/Scanner";
$barcode_text_001 = "The following entries have been checked in";
$barcode_text_002 = "<strong>The following judging number(s) have already been assigned to entries.</strong> Please use another judging number for each.";

// Update upon submitting the form
if ($action == "add") {
    include (LIB.'process.lib.php');
	include (INCLUDES.'process/process_barcode_check_in.inc.php');
}

if ($filter == "box-paid") {
    $switch_to_button = "Judging/Entry Numbers Only";
    $switch_to_link = $base_url."index.php?section=admin&amp;go=checkin";
}

else {
    $switch_to_button = "Entry/Judging Numbers, Box, and Paid";
    $switch_to_link = $base_url."index.php?section=admin&amp;go=checkin&amp;filter=box-paid";
}

?>
<script type="text/javascript">

function moveOnMax(field,nextFieldID) {
  if(field.value.length >= field.maxLength) {
    document.getElementById(nextFieldID).focus();
  }
}

function moveOnCheck(field,nextFieldID) {
    document.getElementById(nextFieldID).focus();
}

var p = false;

/**
 * Disable return key.
 * Most scanners are programmed to submit
 * after a barcode reaches its end. JS here
 * attempts to prevent that.
 */
$(function() {
    $("form").bind("keypress", function(e) {
        if (e.keyCode == 13) return false;
        if (e.keyCode == 10) return false;
        if (e.which == '10' || e.which == '13') {
            e.preventDefault();
        }
    });
});
</script>
<p class="lead"><?php echo $_SESSION['contestName'].": ".$barcode_text_000; ?></p>
<?php
if (!empty($entry_list)) {
$entry_list = rtrim($entry_list,", ");
$entry_list = ltrim($entry_list, ", ");
?>
<div class="alert alert-info">
<span class="fa fa-info-circle"></span> <?php echo sprintf("<strong>%s</strong>: %s", $barcode_text_001, $entry_list); ?>
</div>
<?php }
if (!empty($flag_jnum)) {
	// Build list of already used numbers and the entry number that it was associated with at scan
	foreach ($flag_jnum as $num) {
		if (!empty($num)) {
		$num = explode("*",$num);
		if ((NHC) && ($prefix == "final_")) $jnum_info .= "<li>".$num[0]."  - attempted to assign to entry ".number_pad($num[1],6)."</li>";
		else $jnum_info .= "<li>".$num[0]." - attempted to assign to entry ".number_pad($num[1],6)."</li>";
		}
	}
?>
<div class="alert alert-danger">
	<span class="fa fa-exclamation-circle"></span> <?php echo $barcode_text_002; ?>
	<ul class="small">
	<?php echo $jnum_info; ?>
    </ul>
</div>
<?php }
if (!empty($flag_enum)) {
// Build list of already used numbers and the entry number that it was associated with at scan
$enum_info = "";
foreach ($flag_enum as $num) {
	if ($num != "") {
	$num = explode("*",$num);
	if ((NHC) && ($prefix == "final_")) $enum_info .= "<li>Entry ".number_pad($num[1],6)." has already been assigned judging number ".$num[0]."</li>";
	else $enum_info .= "<li>Entry ".number_pad($num[1],4)." has already been assigned judging number ".$num[0]."</li>";
	}
}
?>
<div class="alert alert-grey">
    <p><span class="fa fa-info-circle"></span> These entries already have 6 digit judging numbers assigned to them - the current 6 digit judging number has been kept for each of the following:</p>
    <ul class="small"><?php echo $enum_info; ?></ul>
    <p>If any of the above are incorrect, you can update its judging number via the <a href="<?php $base_url; ?>index.php?section=admin&amp;go=entries">Administration: Entries</a> list.</p>
</div>
<?php } ?>
<div class="bcoem-admin-element">
    <p>Use the form below to check in entries into the system using a barcode reader/scanner.</p>
    <p>Leave the Judging Number field blank if you wish to use the system- or user-generated judging number already assigned to the entry.</p>
</div>
<div class="bcoem-admin-element" style="margin-bottom: 25px;">
    <a href="<?php echo $switch_to_link; ?>" class="btn btn-xs btn-primary">Switch View to <?php echo $switch_to_button; ?></a>
    <button type="button" class="btn btn-xs btn-info" data-toggle="modal" data-target="#barcodeInfoModal">
          Barcode Check-In Info
    </button>
</div>
<!-- Modal -->
<div class="modal fade" id="barcodeInfoModal" tabindex="-1" role="dialog" aria-labelledby="barcodeInfoModalLabel">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header bcoem-admin-modal">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="barcodeInfoModalLabel">Barcode Check-In Info</h4>
            </div>
            <div class="modal-body">
                <p>You can check-in up to <?php echo $fields; ?> entries at a time. You can also record each entry's box location <?php if (!NHC) { ?>and whether the entry has been paid<?php } ?>.</p>
                    <ul>
                      <li>The cursor will move automatically between fields if the maximum number of characters is input (<?php echo $maxlength; ?> for Entry Number, 6 for Judging Number, and 5 for Box Number).</li>
                      <li>Use the TAB key to move between fields, to skip a field, or if the cursor does not move after data is input.</li>
                      <li>Use the space bar to place a checkmark in the &quot;Paid&quot; box.</li>
                    </ul>
                <p>This function was developed to be used with a barcode reader/scanner in conjunction with the Judging Number Barcode Labels and the Judging Number Round Labels <a class="hide-loader" href="http://www.brewcompetition.com/barcode-labels" target="_blank">available for download at brewcompetition.com</a>. See the <a class="hide-loader" href="http://www.brewcompetition.com/barcode-check-in" target="_blank">suggested usage instructions</a>.</p>
                <p>However, this function can simply be used as a quick way to check-in entries without the use of the Judging Number Barcode Labels - simply leave the Judging Number field blank to use the system- or user-generated judging number already assigned to the entry.</p>
            </div>
            <div class="modal-footer">
            	<button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div><!-- ./modal -->
<form method="post" data-toggle="validator" action="<?php echo $base_url; ?>index.php?section=admin&amp;go=checkin&amp;action=add<?php if ($filter != "default") echo "&amp;filter=".$filter; ?>" id="form1" onsubmit = "return(p)">
<div class="form-inline">
	<?php 
    for ($i=1; $i <= $fields; $i++) { 
        if ($filter == "box-paid") $judging_number_move = "box".$i;
        else {
            $num = $i + 1;
            $judging_number_move = "eid".$num;
        };
    ?>
    <div class="bcoem-admin-element hidden-print">
    <input type="hidden" name="id[]" value="<?php echo $i; ?>">
	<div class="form-group">
    	<label for="">Entry Number</label>
    	<input type="text" class="form-control" maxlength="<?php echo $maxlength; ?>" id="eid<?php echo $i; ?>" name="eid<?php echo $i; ?>" onkeyup="moveOnMax(this,'judgingNumber<?php echo $i; ?>')" <?php if ($i == "1") echo "data-error=\"Field must have a 6 digit number.\" required autofocus"; ?> />
      <?php if ($i == "1") { ?>
      <div class="help-block with-errors"></div>
      <?php } ?>
  	</div>
  	<div class="form-group">
    	<label for="">Judging Number</label>
    	<input type="text" class="form-control" maxlength="6" id="judgingNumber<?php echo $i; ?>" name="judgingNumber<?php echo $i; ?>" onkeyup="moveOnMax(this,'<?php echo $judging_number_move; ?>')" />
  	</div>
    <?php if ($filter == "box-paid") { ?>
    <div class="form-group">
    	<label for="">Box Number</label>
    	<input type="text" class="form-control" maxlength="5" id="box<?php echo $i; ?>" name="box<?php echo $i; ?>" onkeyup="moveOnMax(this,'brewPaid<?php echo ($i); ?>')" />
  	</div>
	<?php if ($_SESSION['prefsPayToPrint'] == "N") { ?>
    <div class="form-group">
    	<label for="">Paid</label>
    	<input type="checkbox" class="form-control" id="brewPaid<?php echo $i; ?>" name="brewPaid<?php echo $i; ?>" value="1" onClick="moveOnCheck(this,'eid<?php echo ($i+1); ?>')" />
  	</div>
	<?php } // end if ($_SESSION['prefsPayToPrint'] == "N") ?>
    <?php } // end if ($action == "box-paid") ?>
    </div>
  	<?php } // end for ($i=1; $i <= $fields; $i++) ?>
</div>
<p><input type="submit" value="Check-In Entries" class="btn btn-primary" onClick = "javascript: p=true;"/></p>
</form>