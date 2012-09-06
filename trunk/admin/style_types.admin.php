<h2><?php if ($action == "add") echo "Add a Style Type"; elseif ($action == "edit") echo "Edit the ".$row_style_type['styleTypeName']." Style Type";  else echo "Style Types"; ?></h2>
<div class="adminSubNavContainer">
  	<span class="adminSubNav">
		<span class="icon"><img src="images/arrow_left.png" alt="Back"></span><a href="index.php?section=admin">Back to Admin</a>
  	</span>
    <span class="adminSubNav">
		<span class="icon"><img src="images/arrow_left.png" alt="Back"></span><a href="index.php?section=admin&amp;go=styles">Back to Accepted Style Categories</a>
    </span>
    <?php if ($action == "default") { ?>
    <span class="adminSubNav">
    	<span class="icon"><img src="images/note_add.png"  /></span><a href="index.php?section=admin&amp;go=styles&amp;action=add">Add a Custom Style Category</a>
    </span>
    <span class="adminSubNav">
    	<span class="icon"><img src="images/page_add.png"  /></span><a href="index.php?section=admin&amp;go=style_types&amp;action=add">Add a Custom Style Type</a>
    </span>
    <?php } ?>
    <?php if (($action == "add") || ($action == "edit")) { ?>
    <span class="adminSubNav">
		<span class="icon"><img src="images/arrow_left.png" alt="Back"></span><a href="index.php?section=admin&amp;go=style_types">Back to Style Types List</a>
    </span>
    <?php } ?>
</div>
<?php if ($action == "default") { ?>
<script type="text/javascript" language="javascript">
	 $(document).ready(function() {
		$('#sortable').dataTable( {
			"bPaginate" : false,
			"sDom": 'rt',
			"bStateSave" : false,
			"bLengthChange" : false,
			"aaSorting": [[0,'asc']],
			"bProcessing" : false,
			"aoColumns": [
				null,
				null,
				{ "asSorting": [  ] },
				{ "asSorting": [  ] }
			]
			} );
		} );
</script>
<table class="dataTable" id="sortable">
<thead>
	<tr>
    	<th class="dataList bdr1B" width="25%">Name</th>
        <th class="dataList bdr1B" width="10%" nowrap="nowrap">BOS for Style</th>
        <th class="dataList bdr1B" width="15%" nowrap="nowrap">BOS Method</th>
        <th class="dataList bdr1B">Actions</th>
    </tr>
</thead>
<tbody>
	<?php do { ?>
	<tr>
    	<td><?php echo $row_style_type['styleTypeName']; ?></td>
        <td class="data"><?php if ($row_style_type['styleTypeBOS'] == "Y") echo "<img src='images/tick.png' alt='Y'>"; else echo "<img src='images/cross.png' alt='Y'>"; ?></td>
        <td class="data"><?php echo bos_method($row_style_type['styleTypeBOSMethod']); ?></td>
        <td class="data">
        <span class="icon"><a href="index.php?section=admin&amp;go=<?php echo $go; ?>&amp;action=edit&amp;id=<?php echo $row_style_type['id']; ?>"><img src="images/pencil.png"  border="0" alt="Edit <?php echo $row_style_type['styleTypeName']; ?>" title="Edit <?php echo $row_style_type['styleTypeName']; ?>"></a></span>
  		<?php if ($row_style_type['styleTypeOwn'] != "bcoe") { ?>
  		<span class="icon"><a href="javascript:DelWithCon('includes/process.inc.php?section=admin&amp;go=<?php echo $go; ?>&amp;dbTable=<?php echo $style_types_db_table; ?>&amp;action=delete','id',<?php echo $row_style_type['id']; ?>,'Are you sure you want to delete <?php echo $row_style_type['styleTypeName']; ?>? This cannot be undone.');"><img src="images/bin_closed.png"  border="0" alt="Delete <?php echo $row_style_type['styleTypeName']; ?>" title="Delete <?php echo $row_style_type['styleTypeName']; ?>"></a></span></td>
  		<?php } else { ?>
 		<span class="icon"><img src="images/bin_closed_fade.png"  border="0" /></span>
  		<?php } ?>
        </td>
    </tr>
    <?php } while ($row_style_type = mysql_fetch_assoc($style_type)); ?>
</tbody>
</table>
<?php } // END if ($action == "default")?>
<?php if (($action == "add") || ($action == "edit")) { ?>
<form name="scores" method="post" action="includes/process.inc.php?action=<?php echo $action; ?>&amp;dbTable=<?php echo $style_types_db_table; if ($action == "edit") echo "&id=".$id; ?>">
<table>
<tbody>
	<tr>
    	<td class="dataLabel">Style Type Name:</td>
        <td class="data">
        <?php if (($action == "add") || (($action == "edit") && ($row_style_type['styleTypeOwn'] != "bcoe"))) { ?>
        <input name="styleTypeName" type="text" value="<?php if ($action == "edit") echo $row_style_type['styleTypeName']; ?>" size="25" />
        <?php } else { echo $row_style_type['styleTypeName'] ; ?>
        <input name="styleTypeName" type="hidden" value="<?php if ($action == "edit") echo $row_style_type['styleTypeName']; ?>" />
        <?php } ?>
        </td>
   	</tr>
    <tr>
    	<td class="dataLabel">Best of Show for this Style?</td>
        <td class="data">
        <input type="radio" name="styleTypeBOS" value="Y" id="styleTypeBOS_0" <?php if (($action == "edit") && ($row_style_type['styleTypeBOS'] == "Y")) echo "checked"; if ($action == "add") echo "checked"; ?> /> Yes&nbsp;&nbsp;
        <input type="radio" name="styleTypeBOS" value="N" id="styleTypeBOS_1" <?php if (($action == "edit") && ($row_style_type['styleTypeBOS'] == "N")) echo "checked"; ?> /> No
        </td>
    </tr>
    <tr>
    	<td class="dataLabel">Best of Show Method:</td>
        <td class="data">
        <input type="radio" name="styleTypeBOSMethod" value="1" id="styleTypeBOSMethod_0" <?php if (($action == "edit") && ($row_style_type['styleTypeBOSMethod'] == "1")) echo "checked"; if ($action == "add") echo "checked"; ?> />1st place only<br />
      	<input type="radio" name="styleTypeBOSMethod" value="2" id="styleTypeBOSMethod_1" <?php if (($action == "edit") && ($row_style_type['styleTypeBOSMethod'] == "2")) echo "checked"; ?> />1st and 2nd places only<br />
      	<input type="radio" name="styleTypeBOSMethod" value="3" id="styleTypeBOSMethod_2" <?php if (($action == "edit") && ($row_style_type['styleTypeBOSMethod'] == "3")) echo "checked"; ?> />1st, 2nd, and 3rd places
        </td>
    </tr>
</tbody>
</table>
<p><input type="submit" class="button" value="<?php if ($action == "edit") echo "Update"; else echo "Submit"; ?>"></p>
<input type="hidden" name="styleTypeOwn" value="<?php if ($action == "add") echo "custom"; else echo $row_style_type['styleTypeOwn']; ?>">
<input type="hidden" name="relocate" value="<?php echo relocate($_SERVER['HTTP_REFERER'],"default",$msg,$id); ?>">
</form>
<?php } // END if (($action == "add") || ($action == "edit")) ?>
