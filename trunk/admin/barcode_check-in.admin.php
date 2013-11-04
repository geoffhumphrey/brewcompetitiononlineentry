<?php 
/**
 * Module:      barcode_check-in.admin.php 
 * Description: Originally deployed as a "mod" for NHC 2013.
 * 
 */
 
$fields = 10;

// Update upon submitting the form
if ($action == "add") {
	include(INCLUDES.'process/process_barcode_check_in.inc.php');
}
 
 ?>
 
<script type="text/javascript">
function moveOnMax(field,nextFieldID){
  if(field.value.length >= field.maxLength){
    document.getElementById(nextFieldID).focus();
  }
}
function moveOnCheck(field,nextFieldID){
    document.getElementById(nextFieldID).focus();
}
document.form1.first.focus();
var p = false;
</script>
<script type="text/javascript">
$(function() {
 
    $("form").bind("keypress", function(e) {
            if (e.keyCode == 13) return false;
      });
 
});
</script>
<?php if ($entry_list != "") { ?>
<div class="info"><?php if (count($entries_updated) == 1) echo "Entry ".rtrim($entry_list,", ")." has been checked in with the assigned judging number."; else echo "Entries ".rtrim($entry_list,", ")." have been checked in with the assigned judging numbers."; ?></div>
<?php } 
if (!empty($flag_jnum)) { 
// Build list of already used numbers and the entry number that it was associated with at scan
$jnum_info = "";
foreach ($flag_jnum as $num) {
	if ($num != "") {
	$num = explode("*",$num);
	if ((NHC) && ($prefix == "final_")) $jnum_info .= "- ".$num[0]." (attempted to assign to entry ".number_pad($num[1],6).")<br>";
	else $jnum_info .= "- ".$num[0]." (attempted to assign to entry ".number_pad($num[1],4).")<br>";
	}
}
?>
<div class="error">The following judging number(s) have already been assigned to entries. Please use another judging number for each.<br /><?php echo rtrim($jnum_info,"<br>"); ?></div>
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
<div class="error">The following entries already have 6 digit judging numbers assigned to them - the original 6 digit judging number has been kept. <ul style="font-size: .9em; font-weight:normal; "><?php echo $enum_info; ?></ul>If any of the above are incorrect, you can update its judging number via the <a href="<?php $base_url; ?>index.php?section=admin&amp;go=entries">Administration: Entries</a> list.</div>
<?php } ?>
<h2>Check-In Entries with a Barcode Reader/Scanner</h2>
<p>This function is intended to be used with a barcode reader/scanner in conjunction with the Judging Number Barcode Labels and the Judging Number Round Labels <a href="http://www.brewcompetition.com/bottle-labels" target="_blank">available for download at brewcompetition.com</a>. </p>
<p>Also available are <a href="http://www.brewcompetition.com/downloads/entry_check-in.pdf" target="_blank">suggested usage instructions</a>.</p>
<p>Use the form below to check in entries and assign their judging number in the system using a barcode reader/scanner. You can enter up to <?php echo $fields; ?> at a time. You can also record each entry's box location <?php if (!NHC) { ?>and whether the entry has been paid<?php } ?>.</p>
<ul>
  <li>The cursor will move automatically between fields if the maximum number of characters is input (4 for Entry Number, 6 for Judging Number, and 5 for Box Number).</li>
  <li>Use the TAB key to move between fields, to skip a field, or if the cursor does not move after data is input.</li>
  <li>Use the space bar to place a checkmark in the &quot;Paid&quot; box.</li>
</ul>

<form method="post" action="index.php?section=admin&amp;go=checkin&amp;action=add" id="form1" onsubmit = "return(p)">
	<table>
		<?php if (NHC) { 
		for ($i=1; $i <= $fields; $i++) { ?>
		<input type="hidden" name="id[]" value="<?php echo $i; ?>" />
		<tr>
			<td class="dataLabel">Entry Number:</td>
			<td class="data"><input type="text" size="10" <?php if ((NHC) && ($prefix == "final_")) { ?>maxlength="6"<?php } else { ?>maxlength="4"<?php } ?> id="eid<?php echo $i; ?>" name="eid<?php echo $i; ?>" onkeyup="moveOnMax(this,'judgingNumber<?php echo $i; ?>')" /><?php if ($i == "1") { ?><script>document.getElementById('eid1').focus()</script><?php } ?></td>
			<td class="dataLabel">Judging Number:</td>
			<td class="data"><input type="text" size="10" maxlength="6" id="judgingNumber<?php echo $i; ?>" name="judgingNumber<?php echo $i; ?>" onkeyup="moveOnMax(this,'box<?php echo $i; ?>')" /></td>
			<td class="dataLabel">Box Number</td>
			<td class="data"><input type="text" size="10" maxlength="5" id="box<?php echo $i; ?>" name="box<?php echo $i; ?>"  onkeyup="moveOnMax(this,'eit<?php echo ($i+1); ?>')" /></td>
		</tr>
		<?php } 
		} 
		else { 
		for ($i=1; $i <= $fields; $i++) { ?>
		<input type="hidden" name="id[]" value="<?php echo $i; ?>" />
		<tr>
			<td class="dataLabel">Entry Number:</td>
			<td class="data"><input type="text" size="10" <?php if ((NHC) && ($prefix == "final_")) { ?>maxlength="6"<?php } else { ?>maxlength="4"<?php } ?> id="eid<?php echo $i; ?>" name="eid<?php echo $i; ?>" onkeyup="moveOnMax(this,'judgingNumber<?php echo $i; ?>')" /><?php if ($i == "1") { ?><script>document.getElementById('eid1').focus()</script><?php } ?></td>
			<td class="dataLabel">Judging Number:</td>
			<td class="data"><input type="text" size="10" maxlength="6" id="judgingNumber<?php echo $i; ?>" name="judgingNumber<?php echo $i; ?>" onkeyup="moveOnMax(this,'box<?php echo $i; ?>')" /></td>
			<td class="dataLabel">Box Number</td>
			<td class="data"><input type="text" size="10" maxlength="5" id="box<?php echo $i; ?>" name="box<?php echo $i; ?>"  onkeyup="moveOnMax(this,'brewPaid<?php echo ($i); ?>')" /></td>
			<td class="dataLabel">Paid?</td>
			<td class="data"><input type="checkbox" id="brewPaid<?php echo $i; ?>" name="brewPaid<?php echo $i; ?>" value="1" onClick="moveOnCheck(this,'eid<?php echo ($i+1); ?>')" />
		</tr>
		<?php } 
		} ?>
	</table>
	<p><input type="submit" value="Submit" class="button" onClick = "javascript: p=true;"/></p>
</form>
 
 
 