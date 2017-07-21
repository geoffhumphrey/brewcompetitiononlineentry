<?php 
/**
 * Module:      judge.sec.php 
 * Description: This module houses the functionality for users to enter their
 *              judging information. 
 * 
 */
include (DB.'brewer.db.php');
include (DB.'styles.db.php'); 
?>
<form action="<?php echo $base_url; ?>includes/process.inc.php?action=edit&amp;dbTable=<?php echo $brewer_db_table; ?>&amp;go=<?php echo $go; ?>&amp;id=<?php echo $row_brewer['id']; ?>" method="POST" name="form1" onSubmit="return CheckRequiredFields()">
<table class="dataTable">
<?php include ('judge_info.sec.php'); ?>
<tr>
	  <td width="10%">&nbsp;</td>
      <td class="data"><input name="submit" type="submit" class="button" value="Submit Judge Information" /></td>
</tr>
</table>
<input type="hidden" name="register" value="Y" />
<input type="hidden" name="uid" value="<?php echo $row_brewer['uid']; ?>">
<input type="hidden" name="brewerEmail" value="<?php echo $_SESSION['loginUsername']; ?>" />
<input type="hidden" name="brewerFirstName" value="<?php echo $row_brewer['brewerFirstName']; ?>">
<input type="hidden" name="brewerLastName" value="<?php echo $row_brewer['brewerLastName']; ?>">
<input type="hidden" name="brewerAddress" value="<?php echo $row_brewer['brewerAddress']; ?>">
<input type="hidden" name="brewerCity" value="<?php echo $row_brewer['brewerCity']; ?>">
<input type="hidden" name="brewerState" value="<?php echo $row_brewer['brewerState']; ?>">
<input type="hidden" name="brewerZip" value="<?php echo $row_brewer['brewerZip']; ?>">
<input type="hidden" name="brewerPhone1" value="<?php echo $row_brewer['brewerPhone1']; ?>">
<input type="hidden" name="brewerPhone2" value="<?php echo $row_brewer['brewerPhone2']; ?>"> 
<input type="hidden" name="brewerClubs" value="<?php echo $row_brewer['brewerClubs']; ?>">
<input type="hidden" name="brewerJudge"  value="<?php echo $row_brewer['brewerJudge']; ?>" />
<input type="hidden" name="brewerSteward"  value="<?php echo $row_brewer['brewerSteward']; ?>" />
<input type="hidden" name="brewerJudgeLocation"  value="<?php echo $row_brewer['brewerJudgeLocation']; ?>" />
<input type="hidden" name="brewerStewardLocation"  value="<?php echo $row_brewer['brewerStewardLocation']; ?>" />
<input type="hidden" name="brewerAHA"  value="<?php echo $row_brewer['brewerAHA']; ?>" />
<?php if ($go != "judge") { ?>
<input type="hidden" name="brewerJudgeID"  value="<?php echo $row_brewer['brewerJudgeID']; ?>" />
<input type="hidden" name="brewerJudgeRank"  value="<?php echo $row_brewer['brewerJudgeRank']; ?>" />
<?php } ?>
<input type="hidden" name="brewerCountry"  value="<?php echo $row_brewer['brewerCountry']; ?>" />
<?php if (isset($_SERVER['HTTP_REFERER'])) { ?>
<input type="hidden" name="relocate" value="<?php echo relocate($_SERVER['HTTP_REFERER'],"default",$msg,$id); ?>">
<?php } else { ?>
<input type="hidden" name="relocate" value="<?php echo relocate($base_url."index.php?section=list","default",$msg,$id); ?>">
<?php } ?>
</form>