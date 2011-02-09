<h2><?php if ($action == "add") echo "Add a Custom Style Category"; elseif ($action == "edit") echo "Edit a Custom Style Category" ; elseif (($action == "default") && ($filter == "judging") && ($bid != "default")) echo "Style Categories Judged at ".$row_judging['judgingLocName']; else echo "Accepted Style Categories"; ?></h2>
<?php if ($action == "default") { ?><p>Check or uncheck the styles <?php if (($action == "default") && ($filter == "judging") && ($bid != "default")) { echo "that will be judged at ".$row_judging['judgingLocName']." on "; echo dateconvert($row_judging['judgingDate'], 2); } else echo "your competition will accept (any custom styles will be at the <a href='#bottom'>bottom</a> of the list)"; ?>.</p><?php } ?>
<?php if ($section != "step5") { ?>
<table class="dataTable">
<tr>
  <td class="dataList" width="5%" nowrap="nowrap"><?php if (($action == "add") || ($action == "edit") || ($filter == "judging")) { ?><span class="icon"><img src="images/arrow_left.png" alt="Back"></span><span class="data"><a href="index.php?section=admin&amp;go=styles">Back to Styles Accepted List</a></span><?php } else { ?><span class="icon"><img src="images/arrow_left.png" alt="Back"></span><a class="data" href="index.php?section=admin">Back to Admin</a><?php } ?></td>
  <?php if ($action == "default") { ?>
  	<?php if (($totalRows_judging > 1) && ($filter == "default")) { ?>
  	<td class="dataList" width="5%" nowrap="nowrap"><span class="icon"><img src="images/note.png"  /></span><a class="data" href="index.php?section=admin&amp;go=styles&amp;filter=judging">Designate Style Categories for Judging Locations</a></td>
  	<?php } ?>
  	<td class="dataList"><span class="icon"><img src="images/note_add.png"  /></span><a class="data" href="index.php?section=admin&amp;go=styles&amp;action=add">Add a Custom Style Category</a></td>
  <?php } ?>
</tr>
</table> 
<?php } if (
(($action == "default") && ($filter == "default")) 
|| 
($section == "setup") 
|| 
(($action == "default") && ($filter == "judging") && ($bid != "default"))
) { ?>
<script language="javascript" type="text/javascript">
//Custom JavaScript Functions by Shawn Olson
//Copyright 2006-2008
//http://www.shawnolson.net
function checkUncheckAll(theElement) {
     var theForm = theElement.form, z = 0;
	 for(z=0; z<theForm.length;z++){
      if(theForm[z].type == 'checkbox' && theForm[z].name != 'checkall'){
	  theForm[z].checked = theElement.checked;
	  }
     }
    }
	</script>
	<script type="text/javascript" language="javascript">
	 $(document).ready(function() {
		$('#sortable').dataTable( {
			"bPaginate" : true,
			"sPaginationType" : "full_numbers",
			"bLengthChange" : true,
			"iDisplayLength" : <?php echo $limit; ?>,
			"sDom": 'irtip',
			"bStateSave" : false,
			"aaSorting": [[2,'asc']],
			"aoColumns": [
				{ "asSorting": [  ] },
				null,
				null,
				<?php if (($totalRows_judging > 1) && (($filter == "default") && ($bid == "default"))) { ?>
				null,
				<?php } ?>
				{ "asSorting": [  ] },
				{ "asSorting": [  ] },
				]
			} );
		} );
	</script>
<form name="form1" method="post" action="includes/process.inc.php?section=<?php echo $section; ?>&amp;action=update&amp;dbTable=styles&amp;filter=<?php echo $filter; if ($bid != "default") echo "&amp;bid=".$bid; ?>">
<p><input type="submit" class="button" name="Submit" value="<?php if (($filter == "judging") && ($bid != "default")) echo "Update ".$row_judging['judgingLocName']; else echo "Update Accepted Styles"; ?>" /></p>
<table class="dataTable" id="sortable">
<thead>
 <tr>
  <th class="dataHeading bdr1B"><input type="checkbox" name="checkall" onclick="checkUncheckAll(this);"/></th>
  <th class="dataHeading bdr1B">Category Name</th>
  <th class="dataHeading bdr1B">#</th>
  <?php if (($totalRows_judging > 1) && (($filter == "default") && ($bid == "default"))) { ?>
  <th class="dataHeading bdr1B">Table Assignment</th>
  <?php } ?>
  <th class="dataHeading bdr1B">Link</th>
  <th class="dataHeading bdr1B">Actions</th>
 </tr>
 </thead>
 <tbody>
 <?php do { 
    	if (($totalRows_judging > 1) && (($filter == "default") && ($bid == "default"))) { 
 		$query_judging2 = sprintf("SELECT * FROM judging WHERE id='%s'", $row_styles['brewStyleJudgingLoc']);
		$judging2 = mysql_query($query_judging2, $brewing) or die(mysql_error());
		$row_judging2 = mysql_fetch_assoc($judging2);
		$totalRows_judging2 = mysql_num_rows($judging2);
		}
	?>
 <tr>
  <input type="hidden" name="id[]" value="<?php echo $row_styles['id']; ?>" />
  <?php if ($bid == "default") { ?>
  <td width="1%" class="dataList"><input name="brewStyleActive<?php echo $row_styles['id']; ?>" type="checkbox" value="Y" <?php if ($row_styles['brewStyleActive'] == "Y") echo "CHECKED"; ?>></td>
  <?php } if ($bid != "default") { ?>
  <td width="1%" class="dataList"><input name="brewStyleJudgingLoc<?php echo $row_styles['id']; ?>" type="checkbox" value="<?php echo $bid; ?>" <?php if ($row_styles['brewStyleJudgingLoc'] == $bid) echo "CHECKED"; ?>></td>
  <?php } ?>
  <td width="15%" class="dataList"><?php echo $row_styles['brewStyle']; ?></td>
  <td width="5%" class="dataList"><?php if ($row_styles['brewStyleGroup'] > 28) echo "Custom"; else echo $row_styles['brewStyleGroup'].$row_styles['brewStyleNum']; ?></td>
  <?php if (($totalRows_judging > 1) && (($filter == "default") && ($bid == "default"))) { ?>
  <td width="25%" class="dataList">
  <?php if ($row_styles['brewStyleActive'] == "Y") { echo $row_judging2['judgingLocName']." ("; echo dateconvert($row_judging2['judgingDate'], 3).")"; } else echo "N/A"; ?>
  </td>
  <?php } ?>
  <td width="5%" class="dataList"><?php if ($row_styles['brewStyleLink'] != "") { ?><a href="<?php echo $row_styles['brewStyleLink']; ?>" target="_blank"><img src="images/link.png" border="0" alt="Link to BJCP Style"></a><?php } else echo "&nbsp;"; ?></td>
  <td class="dataList">
  <?php if ($row_styles['brewStyleOwn'] != "bcoe") { ?>
  <span class="icon"><a href="index.php?section=admin&amp;go=<?php echo $go; ?>&amp;action=edit&amp;id=<?php echo $row_styles['id']; ?>"><img src="images/pencil.png"  border="0" alt="Edit <?php echo $row_styles['brewStyle']; ?>" title="Edit <?php echo $row_styles['brewStyle']; ?>"></a></span>
  <span class="icon"><a href="javascript:DelWithCon('includes/process.inc.php?section=admin&amp;go=<?php echo $go; ?>&amp;dbTable=styles&amp;action=delete','id',<?php echo $row_styles['id']; ?>,'Are you sure you want to delete <?php echo $row_styles['brewStyle']; ?>? This cannot be undone.');"><img src="images/bin_closed.png"  border="0" alt="Delete <?php echo $row_style['brewStyle']; ?>" title="Delete <?php echo $row_style['brewStyle']; ?>"></a></span></td>
  <?php } else { ?>
  <span class="icon"><img src="images/pencil_fade.png"  border="0" /></span>
  <span class="icon"><img src="images/bin_closed_fade.png"  border="0" /></span>
  <?php } ?>
 </tr>
 <?php } while($row_styles = mysql_fetch_assoc($styles)) ?>
 </tbody>
 </table>
 <p><input type="submit" class="button" name="Submit" value="<?php if (($filter == "judging") && ($bid != "default")) echo "Update ".$row_judging['judgingLocName']; else echo "Update Accepted Styles"; ?>" /></p>
<input type="hidden" name="relocate" value="<?php echo relocate($_SERVER['HTTP_REFERER']); ?>">
</form>
<?php } ?>

<?php if (($action == "add") || ($action == "edit")) { ?>
<form method="post" action="includes/process.inc.php?section=<?php echo $section; ?>&amp;action=<?php echo $action; ?>&amp;dbTable=styles&amp;go=<?php echo $go; if ($action == "edit") echo "&amp;id=".$id; ?>" name="form1" onSubmit="return CheckRequiredFields()">
<table>
<tr>
    <td class="dataLabel" >Style Name:</td>
    <td class="data"><input type="text" name="brewStyle" tooltipText="<?php echo $toolTip_name; ?>" value="<?php if ($action == "edit") echo $row_styles['brewStyle']; ?>" size="70"></td>
<tr>
    <td class="dataLabel" >OG Minimum:</td>
    <td class="data"><input type="text" name="brewStyleOG" tooltipText="<?php echo $toolTip_gravity; ?>" value="<?php if ($action == "edit") echo $row_styles['brewStyleOG']; ?>" size="5"></td>
</tr>
<tr>
    <td class="dataLabel" >OG Maximum:</td>
    <td class="data"><input type="text" name="brewStyleOGMax" tooltipText="<?php echo $toolTip_gravity; ?>" value="<?php if ($action == "edit") echo $row_styles['brewStyleOGMax']; ?>" size="5"></td>
</tr>
<tr>
    <td class="dataLabel" >FG Minimum:</td>
    <td class="data"><input type="text" name="brewStyleFG" tooltipText="<?php echo $toolTip_gravity; ?>" value="<?php if ($action == "edit") echo $row_styles['brewStyleFG']; ?>" size="5"></td>
</tr>
<tr>
    <td class="dataLabel" >FG Maximum:</td>
    <td class="data"><input type="text" name="brewStyleFGMax" tooltipText="<?php echo $toolTip_gravity; ?>" value="<?php if ($action == "edit") echo $row_styles['brewStyleFGMax']; ?>" size="5"></td>
</tr>
<tr>
    <td class="dataLabel" >ABV Minimum:</td>
    <td class="data"><input type="text" name="brewStyleABV" tooltipText="<?php echo $toolTip_decimal; ?>" value="<?php if ($action == "edit") echo $row_styles['brewStyleABV']; ?>" size="5"></td>
</tr>
<tr>
    <td class="dataLabel" >ABV Maximum:</td>
    <td class="data"><input type="text" name="brewStyleABVMax" tooltipText="<?php echo $toolTip_decimal; ?>" value="<?php if ($action == "edit") echo $row_styles['brewStyleABVMax']; ?>" size="5"></td>
</tr>
<tr>
    <td class="dataLabel" >IBU Minimum:</td>
    <td class="data"><input type="text" name="brewStyleIBU" tooltipText="<?php echo $toolTip_decimal; ?>" value="<?php if ($action == "edit") echo $row_styles['brewStyleIBU']; ?>" size="5"></td>
</tr>
<tr>
    <td class="dataLabel" >IBU Maximum:</td>
    <td class="data"><input type="text" name="brewStyleIBUMax" tooltipText="<?php echo $toolTip_decimal; ?>" value="<?php if ($action == "edit") echo $row_styles['brewStyleIBUMax']; ?>" size="5"></td>
</tr>
<tr>
    <td class="dataLabel" >SRM Minimum:</td>
    <td class="data"><input type="text" name="brewStyleSRM" tooltipText="<?php echo $toolTip_decimal; ?>" value="<?php if ($action == "edit") echo $row_styles['brewStyleSRM']; ?>" size="5"></td>
</tr>
<tr>
    <td class="dataLabel" >SRM Maximum:</td>
    <td class="data"><input type="text" name="brewStyleSRMMax" tooltipText="<?php echo $toolTip_decimal; ?>" value="<?php if ($action == "edit") echo $row_styles['brewStyleSRMMax']; ?>" size="5"></td>
</tr>
<tr>
    <td class="dataLabel" >Type:</td>
    <td class="data"><select name="brewStyleType" id="brewStyleType" class="text_area">
        <option value="Ale" <?php if ($action == "edit") { if (!(strcmp($row_styles['brewStyleType'], "Ale"))) {echo "SELECTED";} } ?>>Ale</option>
        <option value="Lager" <?php if ($action == "edit") { if (!(strcmp($row_styles['brewStyleType'], "Lager"))) {echo "SELECTED";} } ?>>Lager</option>
        <option value="Mixed" <?php if ($action == "edit") { if (!(strcmp($row_styles['brewStyleType'], "Mixed"))) {echo "SELECTED";} } ?>>Mixed</option>
        <option value="Mead" <?php if ($action == "edit") { if (!(strcmp($row_styles['brewStyleType'], "Mead"))) {echo "SELECTED";} } ?>>Mead</option>
        <option value="Cider" <?php if ($action == "edit") { if (!(strcmp($row_styles['brewStyleType'], "Cider"))) {echo "SELECTED";} } ?>>Cider</option>
        <option value="N/A" <?php if ($action == "edit") { if (!(strcmp($row_styles['brewStyleType'], "N/A"))) {echo "SELECTED";} } ?>>N/A</option>
        </select>
    </td>
</tr>
<tr>
    <td class="dataLabel">Info:</td>
    <td class="data"><textarea name="brewStyleInfo" cols="50" rows="8" class="mceNoEditor"><?php if ($action == "edit") echo $row_styles['brewStyleInfo']; ?></textarea></td>
</tr>
<tr>
    	<td class="dataLabel">&nbsp;</td>
    	<td class="data"><input type="submit" class="button" value="Submit"></td>
        <td class="data">&nbsp;</td>
  	</tr>
</table>
<input type="hidden" name="brewStyleGroup" value="<?php if ($action == "edit") echo $row_styles['brewStyleGroup'];?>">
<input type="hidden" name="brewStyleNum" value="<?php if ($action == "edit") echo $row_styles['brewStyleNum'];?>" >
<input type="hidden" name="brewStyleActive" value="<?php if ($action == "edit") echo $row_styles['brewStyleActive']; else echo "Y"; ?>">
<input type="hidden" name="brewStyleOwn" value="<?php if ($action == "edit") echo $row_styles['brewStyleOwn']; else echo "custom"; ?>">
<input type="hidden" name="relocate" value="<?php echo relocate($_SERVER['HTTP_REFERER']); ?>">
</form>
<?php } ?>

<?php if (($action == "default") && ($filter == "judging") && ($bid == "default")) { ?>
<table>
 <tr>
   <td class="dataLabel">Choose a judging location:</td>
   <td class="data">
   <select name="judge_loc" id="judge_loc" onchange="jumpMenu('self',this,0)">
	<option value=""></option>
    <?php do { ?>
	<option value="index.php?section=admin&amp;go=styles&amp;filter=judging&amp;bid=<?php echo $row_judging['id']; ?>"><?php  echo $row_judging['judgingLocName']." ("; echo dateconvert($row_judging['judgingDate'], 3).")"; ?></option>
    <?php } while ($row_judging = mysql_fetch_assoc($judging)); ?>
  </select>
  </td>
</tr>
</table>
<?php } ?>