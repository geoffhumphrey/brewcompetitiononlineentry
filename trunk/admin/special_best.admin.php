<?php
/**
 * Module:      special_best.admin.php
 * Description: Add, edit, and delete any custom "best of" categories for a comp.
 *              (e.g., for a Pro-Am, Best Entry Name, Stewards Choice, etc.)
 */

$query_sbd = "SELECT COUNT(*) as 'count' FROM $special_best_data_db_table";
$sbd = mysql_query($query_sbd, $brewing) or die(mysql_error());
$row_sbd = mysql_fetch_assoc($sbd);

 ?>
<h2><?php if ($action == "add") echo "Add a Custom Winning Category"; elseif ($action == "edit") echo "Edit a Custom Winning Category"; else echo "Custom Winning Categories"; ?></h2>
<div class="adminSubNavContainer">
   	<span class="adminSubNav"><span class="icon"><img src="<?php echo $base_url; ?>/images/arrow_left.png" alt="Back"></span><a href="<?php echo $base_url; ?>/index.php?section=admin">Back to Admin Dashboard</a></span>
    	<?php if (($action == "add") || ($action == "edit")) { ?>
    	<span class="adminSubNav"><span class="icon"><img src="<?php echo $base_url; ?>/images/arrow_left.png" alt="Back"></span><a href="<?php echo $base_url; ?>/index.php?section=admin&amp;go=special_best">Back to the Custom Winning Category List</a></span>
        <?php } else { ?>
        <span class="adminSubNav"><span class="icon"><img src="<?php echo $base_url; ?>/images/award_star_add.png" /></span><a href="<?php echo $base_url; ?>/index.php?section=admin&amp;go=special_best&amp;action=add">Add a Custom Winning Category</a></span>
   		<?php } if ($row_sbd['count'] > 0) { ?>
        <span class="adminSubNav"><span class="icon"><img src="<?php echo $base_url; ?>/images/award_star_gold_2.png" /></span><a href="<?php echo $base_url; ?>/index.php?section=admin&amp;go=special_best_data">View Custom Winning Category Entires</a></span>
        <?php } ?>
    </span>
</div>
<?php if ($action == "default") { ?>
    <p>Custom winner categories are useful if your competition features unique "best of" competition categories, such as Pro-Am opportunites, Stewards&rsquo; Choice, Best Name, etc.</p>
	<?php if ($totalRows_sbi > 0) { ?>
    <script type="text/javascript" language="javascript">
         $(document).ready(function() {
            $('#sortable').dataTable( {
                "bPaginate" : true,
                "sPaginationType" : "full_numbers",
                "bLengthChange" : true,
                "iDisplayLength" : <?php echo $limit; ?>,
                "sDom": 'irtip',
                "bStateSave" : false,
                "aaSorting": [[4,'asc']],
                "aoColumns": [
                    null,
                    { "asSorting": [  ] },
                    null,
                    null,
					null,
					{ "asSorting": [  ] },
                    ]
                } );
            } );
        </script>
    <table class="dataTable" id="sortable">
     <thead>
     <tr>
      <th class="dataHeading bdr1B">Name</th>
      <th class="dataHeading bdr1B">Description</th>
      <th class="dataHeading bdr1B">Places</th>
      <th class="dataHeading bdr1B">Places Displayed?</th>
      <th class="dataHeading bdr1B">Rank</th>
      <th class="dataHeading bdr1B">Actions</th>
     </tr>
     </thead>
     <tbody>
     <?php do { 
		$query_sbd = sprintf("SELECT COUNT(*) as 'count' FROM $special_best_data_db_table WHERE sid='%s'",$row_sbi['id']);
		$sbd = mysql_query($query_sbd, $brewing) or die(mysql_error());
		$row_sbd = mysql_fetch_assoc($sbd);
	 ?>
     <tr>
      <td width="20%" class="dataList"><?php echo $row_sbi['sbi_name']; ?></td>
      <td width="35%" class="dataList"><?php echo $row_sbi['sbi_description']; ?></td>
      <td width="5%" class="dataList"><?php echo $row_sbi['sbi_places']; ?></td>
      <td width="5%" class="dataList"><?php if ($row_sbi['sbi_display_places'] == 1) echo "Yes"; else echo "No" ?></td>
      <td width="25%" class="dataList"><?php echo $row_sbi['sbi_rank']; ?></td>
      <td class="dataList" nowrap="nowrap">
      <span class="icon"><a href="<?php echo $base_url; ?>/index.php?section=admin&amp;go=<?php echo $go; ?>&amp;action=edit&amp;id=<?php echo $row_sbi['id']; ?>"><img src="<?php echo $base_url; ?>/images/pencil.png"  border="0" alt="Edit <?php echo $row_sbi['sbi_name']; ?>" title="Edit <?php echo $row_sbi['sbi_name']; ?>"></a></span><span class="icon"><a href="javascript:DelWithCon('includes/process.inc.php?section=admin&amp;go=<?php echo $go; ?>&amp;dbTable=<?php echo $special_best_info_db_table; ?>&amp;action=delete','id',<?php echo $row_sbi['id']; ?>,'Are you sure you want to delete <?php echo $row_sbi['sbi_name']; ?>? This cannot be undone. All associated data will be deleted as well.');"><img src="<?php echo $base_url; ?>/images/bin_closed.png"  border="0" alt="Delete <?php echo $row_sbi['sbi_name']; ?>" title="Delete <?php echo $row_sbi['sbi_name']; ?>"></a></span>
      <?php if ($row_sbd['count'] > 0) { ?><span class="icon"><a href="<?php echo $base_url; ?>/index.php?section=admin&amp;go=special_best_data&amp;action=edit&amp;id=<?php echo $row_sbi['id']; ?>"><img src="<?php echo $base_url; ?>/images/rosette_edit.png" alt="Edit winners for <?php echo $row_sbi['sbi_name']; ?>" title="Edit winners for <?php echo $row_sbi['sbi_name']; ?>" /></a></span><?php } else { ?><span class="icon"><a href="<?php echo $base_url; ?>/index.php?section=admin&amp;go=special_best_data&amp;action=add&amp;id=<?php echo $row_sbi['id']; ?>"><img src="<?php echo $base_url; ?>/images/rosette_add.png" alt="Enter winners for <?php echo $row_sbi['sbi_name']; ?>" title="Enter winners for <?php echo $row_sbi['sbi_name']; ?>" /></a></span><?php } ?>
      </td>
     </tr>
    <?php } while($row_sbi = mysql_fetch_assoc($sbi)) ?>
     </tbody>
    </table>
    <?php } else echo "<p>There are no custom winner categories were found in the database.</p>";
} 
if (($action == "add") || ($action == "edit")) { ?>
<form method="post" action="<?php echo $base_url; ?>/includes/process.inc.php?action=<?php echo $action; ?>&amp;dbTable=<?php echo $special_best_info_db_table; ?><?php if ($action == "edit") echo "&amp;id=".$id; ?>" name="form1">
<table>
  <tr>
    <td class="dataLabel">Custom Winning<br />
      Category Name:</td>
    <td colspan="2" class="data"><input name="sbi_name" type="text" size="30" maxlength="255" value="<?php if ($action == "edit") echo $row_sbi['sbi_name']; ?>"></td>
    </tr>
  <tr>
    <td class="dataLabel">Description:</td>
    <td class="data" colspan="2"><textarea name="sbi_description" class="submit mceNoEditor" cols="70" rows="15"><?php if ($action == "edit") echo $row_sbi['sbi_description']; ?></textarea><!-- <p><a href="javascript:toggleEditor('sbi_description');">Enable/Disable Rich Text</a></p>--></td>
  </tr>
  <tr>
    <td class="dataLabel">Places:</td>
    <td class="data"><input name="sbi_places" type="text" size="5" value="<?php if ($action == "edit") echo $row_sbi['sbi_places']; ?>"></td>
    <td class="data">&nbsp;</td>
  </tr>
  
  <tr>
    <td class="dataLabel">Display Places?</td>
    <td class="data">
    	<input type="radio" name="sbi_display_places" value="1" id="sbi_display_places_1" <?php if ($row_sbi['sbi_display_places'] == "1") echo "CHECKED"; ?> />Yes<br />
        <input type="radio" name="sbi_display_places" value="0" id="sbi_display_places_0" <?php if ($row_sbi['sbi_display_places'] == "0") echo "CHECKED"; ?> />No
    </td>
    <td class="data">&nbsp;</td>
  </tr>

  <tr>
    <td class="dataLabel">Rank:</td>
    <td class="data">
    <select name="sbi_rank">
    	<option value="1" <?php if (($action == "edit") && ($row_sbi['sbi_rank'] == "1")) echo " SELECTED"; ?>>1</option>
        <option value="2" <?php if (($action == "edit") && ($row_sbi['sbi_rank'] == "2")) echo " SELECTED"; ?>>2</option>
        <option value="3" <?php if (($action == "edit") && ($row_sbi['sbi_rank'] == "3")) echo " SELECTED"; ?>>3</option>
        <option value="4" <?php if (($action == "edit") && ($row_sbi['sbi_rank'] == "4")) echo " SELECTED"; ?>>4</option>
        <option value="5" <?php if (($action == "edit") && ($row_sbi['sbi_rank'] == "5")) echo " SELECTED"; ?>>5</option>
        <option value="6" <?php if (($action == "edit") && ($row_sbi['sbi_rank'] == "6")) echo " SELECTED"; ?>>6</option>
        <option value="7" <?php if (($action == "edit") && ($row_sbi['sbi_rank'] == "7")) echo " SELECTED"; ?>>7</option>
        <option value="8" <?php if (($action == "edit") && ($row_sbi['sbi_rank'] == "8")) echo " SELECTED"; ?>>8</option>
        <option value="9" <?php if (($action == "edit") && ($row_sbi['sbi_rank'] == "9")) echo " SELECTED"; ?>>9</option>
        <option value="10" <?php if (($action == "edit") && ($row_sbi['sbi_rank'] == "10")) echo " SELECTED"; ?>>10</option>
        <option value="11" <?php if (($action == "edit") && ($row_sbi['sbi_rank'] == "11")) echo " SELECTED"; ?>>11</option>
        <option value="12" <?php if (($action == "edit") && ($row_sbi['sbi_rank'] == "12")) echo " SELECTED"; ?>>12</option>
        <option value="13" <?php if (($action == "edit") && ($row_sbi['sbi_rank'] == "13")) echo " SELECTED"; ?>>13</option>
        <option value="14" <?php if (($action == "edit") && ($row_sbi['sbi_rank'] == "14")) echo " SELECTED"; ?>>14</option>
        <option value="15" <?php if (($action == "edit") && ($row_sbi['sbi_rank'] == "15")) echo " SELECTED"; ?>>15</option>
        <option value="16" <?php if (($action == "edit") && ($row_sbi['sbi_rank'] == "16")) echo " SELECTED"; ?>>16</option>
        <option value="17" <?php if (($action == "edit") && ($row_sbi['sbi_rank'] == "17")) echo " SELECTED"; ?>>17</option>
        <option value="18" <?php if (($action == "edit") && ($row_sbi['sbi_rank'] == "18")) echo " SELECTED"; ?>>18</option>
        <option value="19" <?php if (($action == "edit") && ($row_sbi['sbi_rank'] == "19")) echo " SELECTED"; ?>>19</option>
        <option value="20" <?php if (($action == "edit") && ($row_sbi['sbi_rank'] == "20")) echo " SELECTED"; ?>>20</option>
    </select>
    </td>
    <td class="data">Determines this category's rank in the display order. The lower the number, the higher priority.</td>
  </tr>
</table>
<p><input name="submit" type="submit" class="button" value="<?php if ($action == "edit") echo "Edit"; else echo "Add"; ?> Custom Winning Category"></p>
<input type="hidden" name="relocate" value="<?php echo relocate($_SERVER['HTTP_REFERER'],"default",$msg,$id); ?>">
</form>
<?php } ?>