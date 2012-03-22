<?php 
include(DB.'judging_locations.db.php'); 
include(DB.'common.db.php');
include(DB.'styles.db.php'); 
?>
<h2><?php if ($action == "add") echo "Add a Custom Style Category"; elseif ($action == "edit") echo "Edit a Custom Style Category" ; elseif (($action == "default") && ($filter == "judging") && ($bid != "default")) echo "Style Categories Judged at ".$row_judging['judgingLocName']; else echo "Accepted Style Categories"; ?></h2>
<?php if ($section != "step7") { ?>
<div class="adminSubNavContainer">
    <span class="adminSubNav">
        <span class="icon"><img src="images/arrow_left.png" alt="Back"></span><a href="index.php?section=admin">Back to Admin</a>
    </span> 
    <?php if (($action == "add") || ($action == "edit") || ($filter != "default")) { ?>
   	<span class="adminSubNav">
    	<span class="icon"><img src="images/arrow_left.png" alt="Back"></span><a href="index.php?section=admin&amp;go=styles">Back to Styles Accepted List</a>
    </span>
    <?php } ?>
<?php if ($action == "default") { ?>
  	<?php if (($totalRows_judging > 1) && ($filter == "default") && ($row_prefs['prefsCompOrg'] == "N")) { ?>
  	<span class="adminSubNav">
    	<span class="icon"><img src="images/note.png"  /></span><a href="index.php?section=admin&amp;go=styles&amp;filter=judging">Designate Style Categories for Judging Locations</a>
  	</span>
	<?php } ?>
  	<span class="adminSubNav">
    	<span class="icon"><img src="images/note_add.png"  /></span><a href="index.php?section=admin&amp;go=styles&amp;action=add">Add a Custom Style Category</a>
    </span>
    <span class="adminSubNav">
    	<span class="icon"><img src="images/page_add.png"  /></span><a href="index.php?section=admin&amp;go=style_types&amp;action=add">Add a Custom Style Type</a>
    </span>
<?php } ?>
</div>
<?php if (($filter == "default") && ($action == "default")) { ?><p>Check or uncheck the styles <?php if (($action == "default") && ($filter == "judging") && ($bid != "default")) { echo "that will be judged at ".$row_judging['judgingLocName']." on "; echo date_convert($row_judging['judgingDate'], 2, $row_prefs['prefsDateFormat']); } else echo "your competition will accept (any custom styles will be at the <a href='#custom'>bottom</a> of the list)"; ?>.</p><?php } ?>
<?php } if ((($action == "default") && ($filter == "default")) || ($section == "step7") || (($action == "default") && ($filter == "judging") && ($bid != "default"))) { ?>
<script language="javascript" type="text/javascript">
//Custom JavaScript Functions by Shawn Olson
//Copyright 2006-2008
/*http://www.shawnolson.net
function checkUncheckAll(theElement) {
     var theForm = theElement.form, z = 0;
	 for(z=0; z<theForm.length;z++){
      if(theForm[z].type == 'checkbox' && theForm[z].name != 'checkall'){
	  theForm[z].checked = theElement.checked;
	  }
     }
    }
*/
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
				{ "asSorting": [  ] },
				{ "asSorting": [  ] },
				{ "asSorting": [  ] },
				{ "asSorting": [  ] }
				]
			} );
		} );
	</script>
<form name="form1" method="post" action="includes/process.inc.php?section=<?php echo $section; ?>&amp;action=update&amp;dbTable=styles&amp;filter=<?php echo $filter; if ($bid != "default") echo "&amp;bid=".$bid; ?>">
<p><input type="submit" class="button" name="Submit" value="<?php if (($filter == "judging") && ($bid != "default")) echo "Update ".$row_judging['judgingLocName']; else echo "Update Accepted Styles"; ?>" /></p>
<h3>Main Style Set: <?php echo $row_styles_active['style_name']." ".$row_styles_active['style_year']; ?></h3>
<table class="dataTable" id="sortable">
<thead>
 <tr>
  <th class="dataHeading bdr1B">&nbsp;</th>
  <th class="dataHeading bdr1B">Category Name</th>
  <th class="dataHeading bdr1B">Category</th>
  <th class="dataHeading bdr1B">Sub-Category</th>
  <th class="dataHeading bdr1B">Style Type</th>
  <th class="dataHeading bdr1B">Link</th>
  <th class="dataHeading bdr1B">Actions</th>
 </tr>
 </thead>
 <tbody>
 <?php do { 
    	/*
		if (($totalRows_judging > 1) && (($filter == "default") && ($bid == "default"))) { 
 		$query_judging2 = sprintf("SELECT * FROM judging_locations WHERE id='%s'", $row_styles['style_location']);
		$judging2 = mysql_query($query_judging2, $brewing) or die(mysql_error());
		$row_judging2 = mysql_fetch_assoc($judging2);
		$totalRows_judging2 = mysql_num_rows($judging2);
		}
		*/
	?>
 <tr>
  <input type="hidden" name="id[]" value="<?php echo $row_styles['id']; ?>" />
  <?php if ($bid == "default") { ?>
  <td width="1%" class="dataList"><input name="style_active<?php echo $row_styles['id']; ?>" type="checkbox" value="1" <?php if ($row_styles['style_active'] == "1") echo "CHECKED"; ?>></td>
  <?php } if ($bid != "default") { ?>
  <td width="1%" class="dataList"><input name="style_location<?php echo $row_styles['id']; ?>" type="checkbox" value="<?php echo $bid; ?>" <?php if ($row_styles['style_location'] == $bid) echo "CHECKED"; ?>></td>
  <?php } ?>
  <td width="25%" class="dataList"><?php echo $row_styles['style_name']; ?></td>
  <td width="15%" class="dataList"><?php echo $row_styles['style_cat']; ?></td>
  <td width="15%" class="dataList"><?php echo $row_styles['style_subcat']; ?></td>
  <td width="5%" class="dataList"><?php if (style_type($row_styles['style_type'],"1","") <= "3") $style_own = "bcoe"; else $style_own = "custom"; echo style_type($row_styles['style_type'],"2",$style_own); ?></td>
  <td width="5%" class="dataList"><?php if ($row_styles['style_link'] != "") { ?><a href="<?php echo $row_styles['style_link']; ?>" target="_blank"><img src="images/link.png" border="0" alt="Link to BJCP Style"></a><?php } else echo "&nbsp;"; ?></td>
  
  <td class="dataList">
  <?php if ($row_styles_active['style_owner'] != "bcoe") { ?>
  <span class="icon"><a href="index.php?section=admin&amp;go=<?php echo $go; ?>&amp;action=edit&amp;id=<?php echo $row_styles['id']; ?>"><img src="images/pencil.png"  border="0" alt="Edit <?php echo $row_styles['style_name']; ?>" title="Edit <?php echo $row_styles['style_name']; ?>"></a></span>
  <span class="icon"><a href="javascript:DelWithCon('includes/process.inc.php?section=admin&amp;go=<?php echo $go; ?>&amp;dbTable=styles&amp;action=delete','id',<?php echo $row_styles['id']; ?>,'Are you sure you want to delete <?php echo $row_styles['style_name']; ?>? This cannot be undone.');"><img src="images/bin_closed.png"  border="0" alt="Delete <?php echo $row_style['brewStyle']; ?>" title="Delete <?php echo $row_style['brewStyle']; ?>"></a></span></td>
  <?php } else { ?>
  <span class="icon"><img src="images/pencil_fade.png"  border="0" /></span>
  <span class="icon"><img src="images/bin_closed_fade.png"  border="0" /></span>
  <?php } ?>
 </tr>
 <?php } while($row_styles = mysql_fetch_assoc($styles)) ?>
 </tbody>
 </table>
<?php if ($totalRows_styles_custom > 0) { ?>
<script type="text/javascript" language="javascript">
	 $(document).ready(function() {
		$('#sortable_custom').dataTable( {
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
				{ "asSorting": [  ] },
				{ "asSorting": [  ] },
				{ "asSorting": [  ] },
				{ "asSorting": [  ] }
				]
			} );
		} );
	</script>
<a name="custom"></a><h3>Custom Styles</h3>
<table class="dataTable" id="sortable_custom">
<thead>
 <tr>
  <th class="dataHeading bdr1B">&nbsp;</th>
  <th class="dataHeading bdr1B">Category Name</th>
  <th class="dataHeading bdr1B">Category</th>
  <th class="dataHeading bdr1B">Sub-Category</th>
  <th class="dataHeading bdr1B">Style Type</th>
  <th class="dataHeading bdr1B">Link</th>
  <th class="dataHeading bdr1B">Actions</th>
 </tr>
 </thead>
 <tbody>
 <?php do { 
    	/*
		if (($totalRows_judging > 1) && (($filter == "default") && ($bid == "default"))) { 
 		$query_judging2 = sprintf("SELECT * FROM judging_locations WHERE id='%s'", $row_styles['style_location']);
		$judging2 = mysql_query($query_judging2, $brewing) or die(mysql_error());
		$row_judging2 = mysql_fetch_assoc($judging2);
		$totalRows_judging2 = mysql_num_rows($judging2);
		}
		*/
	?>
 <tr>
  <input type="hidden" name="id_custom[]" value="<?php echo $row_styles_custom['id']; ?>" />
  <?php if ($bid == "default") { ?>
  <td width="1%" class="dataList"><input name="style_active_custom<?php echo $row_styles_custom['id']; ?>" type="checkbox" value="1" <?php if ($row_styles_custom['style_active'] == "1") echo "CHECKED"; ?>></td>
  <?php } if ($bid != "default") { ?>
  <td width="1%" class="dataList"><input name="style_location_custom<?php echo $row_styles_custom['id']; ?>" type="checkbox" value="<?php echo $bid; ?>" <?php if ($row_styles['style_location'] == $bid) echo "CHECKED"; ?>></td>
  <?php } ?>
  <td width="25%" class="dataList"><?php echo $row_styles_custom['style_name']; ?></td>
  <td width="15%" class="dataList"><?php echo $row_styles_custom['style_cat']; ?></td>
  <td width="15%" class="dataList"><?php echo $row_styles_custom['style_subcat']; ?></td>
  <td width="5%" class="dataList"><?php if (style_type($row_styles_custom['style_type'],"1","") <= "3") $style_own = "bcoe"; else $style_own = "custom"; echo style_type($row_styles_custom['style_type'],"2",$style_own); ?></td>
  <td width="5%" class="dataList"><?php if ($row_styles_custom['style_link'] != "") { ?><a href="<?php echo $row_styles_custom['style_link']; ?>" target="_blank"><img src="images/link.png" border="0" alt="Link to Style"></a><?php } else echo "&nbsp;"; ?></td>
  <td class="dataList">
  <span class="icon"><a href="index.php?section=admin&amp;go=<?php echo $go; ?>&amp;action=edit&amp;id=<?php echo $row_styles_custom['id']; ?>"><img src="images/pencil.png"  border="0" alt="Edit <?php echo $row_styles_custom['style_name']; ?>" title="Edit <?php echo $row_styles_custom['style_name']; ?>"></a></span>
  <span class="icon"><a href="javascript:DelWithCon('includes/process.inc.php?section=admin&amp;go=<?php echo $go; ?>&amp;dbTable=styles&amp;action=delete','id',<?php echo $row_styles_custom['id']; ?>,'Are you sure you want to delete <?php echo $row_styles_custom['style_name']; ?>? This cannot be undone.');"><img src="images/bin_closed.png"  border="0" alt="Delete <?php echo $row_styles_custom['style_name']; ?>" title="Delete <?php echo $row_styles_custom['style_name']; ?>"></a></span></td>
 </tr>
 <?php } while($row_styles_custom = mysql_fetch_assoc($styles_custom)) ?>
 </tbody>
 </table>
<?php } ?>
<p><input type="submit" class="button" name="Submit" value="<?php if (($filter == "judging") && ($bid != "default")) echo "Update ".$row_judging['judgingLocName']; else echo "Update Accepted Styles"; ?>" /></p>
<input type="hidden" name="relocate" value="<?php echo relocate($_SERVER['HTTP_REFERER'],"default"); ?>">
</form>
<?php } ?>

<?php if (($action == "add") || ($action == "edit")) { ?>
<form method="post" action="includes/process.inc.php?section=<?php echo $section; ?>&amp;action=<?php echo $action; ?>&amp;dbTable=styles_custom&amp;go=<?php echo $go; if ($action == "edit") echo "&amp;id=".$id; ?>" name="form1" onSubmit="return CheckRequiredFields()">
<p>Add or edit a custom style category that participants can specify when they enter a brew. All custom style categories are available <em>in addition</em> to the overall style set, <?php echo $row_styles_active['style_name']." ".$row_styles_active['style_year']; ?>.
<table>
<tr>
    <td class="dataLabel" >Style Name:</td>
    <td colspan="3" class="data"><input type="text" name="style_name" value="<?php if ($action == "edit") echo $row_styles_custom['style_name']; ?>" size="25">&nbsp;<span class="required">Required</span></td>
    </tr>
<tr>
    <td class="dataLabel" >Style Type:</td>
    <td colspan="3" class="data">
        <select name="style_type" id="style_type" class="text_area">
            <option value=""></option>
            <?php do { ?>
            <option value="<?php echo $row_style_type['id']; ?>" <?php if ($action == "edit") if (style_type($row_styles_custom['style_type'],"1","bcoe") == $row_style_type['id']) echo "SELECTED"; ?>><?php echo $row_style_type['styleTypeName']; ?></option>
            <?php } while ($row_style_type = mysql_fetch_assoc($style_type)); ?>
            </select>
        <span class="required">Required</span></td>
    </tr>
<tr>
    <td class="dataLabel" >&nbsp;</td>
    <td colspan="3" class="data"><span class="icon"><img src="images/page_add.png" /></span><a href="index.php?section=admin&amp;go=style_types&amp;action=add">Add a Custom Style Type</a> </span></td>
</tr>
<tr>
    <td class="dataLabel">Style Category:</td>
    <td colspan="3" class="data"><input type="text" name="style_cat" value="<?php if ($action == "edit") echo $row_styles_custom['style_cat']; ?>" size="25" />&nbsp;<span class="required">Required</span></td>
</tr>
<tr>
    <td class="dataLabel">Style Sub-Category:</td>
    <td colspan="3" class="data"><input type="text" name="style_subcat" value="<?php if ($action == "edit") echo $row_styles_custom['style_subcat']; ?>" size="25" /></td>
</tr>
<tr>
    <td class="dataLabel">OG Minimum:</td>
    <td class="data"><input type="text" name="style_og_min" value="<?php if ($action == "edit") echo number_format($row_styles_custom['style_og_min'],3); ?>" size="5"></td>
    <td class="dataLabel" >OG Maximum:</td>
    <td class="data"><input type="text" name="style_og_max" value="<?php if ($action == "edit") echo number_format($row_styles_custom['style_og_max'],3); ?>" size="5" /></td>
    </tr>
<tr>
    <td class="dataLabel">FG Minimum:</td>
    <td class="data"><input type="text" name="style_fg_min" value="<?php if ($action == "edit") echo number_format($row_styles_custom['style_fg_min'],3); ?>" size="5"></td>
    <td class="dataLabel" >FG Maximum:</td>
    <td class="data"><input type="text" name="style_fg_max" value="<?php if ($action == "edit") echo number_format($row_styles_custom['style_fg_max'],3); ?>" size="5" /></td>
    </tr>
<tr>
    <td class="dataLabel" >ABV Minimum:</td>
    <td class="data"><input type="text" name="style_abv_min" value="<?php if ($action == "edit") echo $row_styles_custom['style_abv_min']; ?>" size="5"></td>
    <td class="dataLabel" >ABV Maximum:</td>
    <td class="data"><input type="text" name="style_abv_max" value="<?php if ($action == "edit") echo $row_styles_custom['style_abv_max']; ?>" size="5" /></td>
</tr>
<tr>
    <td class="dataLabel" >IBU Minimum:</td>
    <td class="data"><input type="text" name="style_ibu_min" value="<?php if ($action == "edit") echo $row_styles_custom['style_ibu_min']; ?>" size="5"></td>
    <td class="dataLabel" >IBU Maximum:</td>
    <td class="data"><input type="text" name="style_ibu_max" value="<?php if ($action == "edit") echo $row_styles_custom['style_ibu_max']; ?>" size="5" /></td>
</tr>
<tr>
    <td class="dataLabel" >SRM Minimum:</td>
    <td class="data"><input type="text" name="style_srm_min" value="<?php if ($action == "edit") echo $row_styles_custom['style_srm_min']; ?>" size="5"></td>
    <td class="dataLabel" >SRM Maximum:</td>
    <td class="data"><input type="text" name="style_srm_max" value="<?php if ($action == "edit") echo $row_styles_custom['style_srm_max']; ?>" size="5" /></td>
</tr>
<tr>
  <td class="dataLabel">Require Special<br />
    Ingredients or<br />
    Classic Style?</td>
  <td colspan="3" class="data"><input type="radio" name="style_spec_ingred" value="1" <?php if ($action == "add") echo "CHECKED"; if (($action == "edit") && ($row_styles_custom['style_spec_ingred'] == "1")) echo "CHECKED"; ?>/>Yes<br /><input type="radio" name="style_spec_ingred" value="0" <?php if (($action == "edit") && ($row_styles_custom['style_spec_ingred'] == "0")) echo "CHECKED"; ?>/>No</td>
</tr>
<tr>
  <td class="dataLabel">Accepted Style?</td>
  <td colspan="3" class="data"><input type="radio" name="style_active" value="1" <?php if ($action == "add") echo "CHECKED"; if (($action == "edit") && ($row_styles_custom['style_active'] == "1")) echo "CHECKED"; ?>/>Yes<br /><input type="radio" name="style_active" value="0" <?php if (($action == "edit") && ($row_styles_custom['style_active'] == "0")) echo "CHECKED"; ?>/>No</td>
</tr>
<tr>
    <td class="dataLabel">Info:</td>
    <td colspan="3" class="data"><textarea name="style_info" cols="40" rows="8" class="mceNoEditor"><?php if ($action == "edit") echo $row_styles_custom['style_info']; ?></textarea></td>
</tr>
<tr>
    	<td class="dataLabel">&nbsp;</td>
    	<td colspan="3" class="data"><input type="submit" class="button" value="Submit"></td>
    	</tr>
</table>
<input type="hidden" name="relocate" value="<?php echo relocate($_SERVER['HTTP_REFERER'],"default"); ?>">
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
	<option value="index.php?section=admin&amp;go=styles&amp;filter=judging&amp;bid=<?php echo $row_judging['id']; ?>"><?php  echo $row_judging['judgingLocName']." ("; echo date_convert($row_judging['judgingDate'], 3, $row_prefs['prefsDateFormat']).")"; ?></option>
    <?php } while ($row_judging = mysql_fetch_assoc($judging)); ?>
  </select>
  </td>
</tr>
</table>
<?php } ?>

<?php if (($action == "default") && ($filter == "orphans") && ($bid == "default")) { ?>
<h3>Styles Without a Valid Style Type</h3>
<?php 
echo orphan_styles();
} ?>