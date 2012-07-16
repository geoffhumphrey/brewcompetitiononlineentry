<?php
/**
 * Module:      special_best_data.admin.php
 * Description: Add, edit, and delete any custom "best of" categories for a comp.
 *              (e.g., for a Pro-Am, Best Entry Name, Stewards Choice, etc.)
 */



?>

<?php 
//if (($action == "add") || ($action == "edit")) $query_sbd = "SELECT * FROM $special_best_data_db_table WHERE id='$id'";
$query_sbi = "SELECT * FROM $special_best_info_db_table";
if (($action == "add") || ($action == "edit")) $query_sbi .= " WHERE id=$id"; 
$sbi = mysql_query($query_sbi, $brewing) or die(mysql_error());
$row_sbi = mysql_fetch_assoc($sbi);
$totalRows_sbi = mysql_num_rows($sbi);

if ($action == "add") $query_sbd = "SELECT sbi_places,sbi_name FROM $special_best_info_db_table WHERE id='$id'";
elseif ($action == "edit") $query_sbd = "SELECT * FROM $special_best_data_db_table WHERE sid='$id' ORDER BY sbd_place ASC";
$sbd = mysql_query($query_sbd, $brewing) or die(mysql_error());
$row_sbd = mysql_fetch_assoc($sbd);
$totalRows_sbd = mysql_num_rows($sbd);

?>
<h2><?php if ($action == "add") echo "Add Entries to Custom Winning Category: ".$row_sbi['sbi_name']; elseif ($action == "edit") echo "Edit Entries in Custom Winning Category: ".$row_sbi['sbi_name']; else echo "Custom Winner Category Entries"; ?></h2>
<div class="adminSubNavContainer">
   	<span class="adminSubNav">
    	<span class="icon"><img src="images/arrow_left.png" alt="Back"></span><a href="index.php?section=admin">Back to Admin</a></span>
    </span>
    <span class="adminSubNav">
    	<span class="icon"><img src="images/arrow_left.png" alt="Back"></span><a href="index.php?section=admin&amp;go=special_best">Back to the Custom Winner Category List</a>
    </span>
</div>
<?php if (($action == "default") || ($action == "list")) { ?>
<p>Custom winner categories are useful if your competition features unique "best of" competition categories, such as Pro-Am opportunites, Stewards&rsquo; Choice, Best Name, etc.</p>
	<?php if ($totalRows_sbd > 0) { ?>
    <script type="text/javascript" language="javascript">
         $(document).ready(function() {
            $('#sortable').dataTable( {
                "bPaginate" : true,
                "sPaginationType" : "full_numbers",
                "bLengthChange" : true,
                "iDisplayLength" : <?php echo $limit; ?>,
                "sDom": 'irtip',
                "bStateSave" : false,
                "aaSorting": [[0,'asc'],[1,'asc']],
                "aoColumns": [
                    null,
                    { "asSorting": [  ] },
                    { "asSorting": [  ] },
                    { "asSorting": [  ] },
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
      <th class="dataHeading bdr1B">Custom Category</th>
      <th class="dataHeading bdr1B">Place</th>
      <th class="dataHeading bdr1B">Entry #</th>
      <th class="dataHeading bdr1B">Judging #</th>
      <th class="dataHeading bdr1B">Entry Name</th>
      <th class="dataHeading bdr1B">Brewer</th>
      <th class="dataHeading bdr1B">Actions</th>
     </tr>
     </thead>
     <tbody>
    <?php do { ?>
     <tr>
      <td width="15%" class="dataList"><?php echo $row_sbd['sbi_name']; ?></td>
      <td width="1%" class="dataList"><?php echo $row_sbd['sbd_place']; ?></td>
      <td width="1%" class="dataList"><?php echo $row_sbd['sid']; ?></td>
      <td width="1%" class="dataList"><?php echo $row_sbd['brewJudgingNumber']; ?></td>
      <td width="20%" class="dataList"><?php echo $row_sbd['brewName']; ?></td>
      <td width="20%" class="dataList"><?php echo $row_sbd['brewerFirstName']." ".$row_sbd['brewerLastName']; ?></td>
      <td class="dataList" nowrap="nowrap">
      <span class="icon"><a href="index.php?section=admin&amp;go=<?php echo $go; ?>&amp;action=edit&amp;id=<?php echo $row_sbd['id']; ?>"><img src="images/pencil.png"  border="0" alt="Edit <?php echo $row_sbd['brewName']; ?>" title="Edit <?php echo $row_sbd['brewName']; ?>"></a></span><span class="icon"><a href="javascript:DelWithCon('includes/process.inc.php?section=admin&amp;go=<?php echo $go; ?>&amp;dbTable=sponsors&amp;action=delete','id',<?php echo $row_sbd['id']; ?>,'Are you sure you want to delete <?php echo $row_sbd['brewName'] ?>? This cannot be undone.');"><img src="images/bin_closed.png"  border="0" alt="Delete <?php echo $row_sbd['brewName']; ?>" title="Delete <?php echo $row_sbd['brewName']; ?>"></a></span>
      </td>
     </tr>
    <?php } while($row_sbd = mysql_fetch_assoc($sbd));  ?>
     </tbody>
    </table>
    <?php } else echo "<p>There are entries found in any custom winner categories.</p>";
} 
if (($action == "add") || ($action == "edit")) { ?>
<form method="post" action="includes/process.inc.php?action=<?php echo $action; ?>&amp;dbTable=special_best_data" name="form1">
<table>
<?php if ($action == "add") { for ($i=1; $i <= $row_sbd['sbi_places']; $i++) { ?>
	<input type="hidden" name="id[]" value="<?php echo $i; ?>" />
  <tr>
    <td class="dataLabel">Entry <?php echo $i; ?> Judging Number:</td>
    <td class="data"><input name="sbd_judging_no<?php echo $i; ?>" type="text" size="10" maxlength="255" value=""></td>
    <td class="dataLabel">Place:</td>
    <td class="data">
    <input name="sbd_place<?php echo $i; ?>" type="text" size="5" value="">
    <input type="hidden" name="sid<?php echo $i; ?>" value="<?php echo $id; ?>">
    </td>
  </tr>
  <?php } 
	}
	if ($action == "edit") { 
		do { ?>
  <input type="hidden" name="id[]" value="<?php echo $row_sbd['id']; ?>" />
  <tr>
    <td class="dataLabel">Judging Number:</td>
    <td class="data"><input name="sbd_judging_no<?php echo $row_sbd['id']; ?>" type="text" size="10" maxlength="255" value="<?php $info = explode("^", entry_info($row_sbd['eid']));  echo $info[6]; ?>"></td>
    <td class="dataLabel">Place:</td>
    <td class="data"><input name="sbd_place<?php  echo $row_sbd['id']; ?>" type="text" size="5" value="<?php echo $row_sbd['sbd_place']; ?>"></td>
    <td class="dataLabel">Entry Name:</td> 
	<td class="data"><?php echo $info[0]; ?></td>
    <td class="dataLabel">Brewer:</td>
	<td class="data"><?php $info = explode("^", brewer_info($row_sbd['bid'])); echo $info[0]." ".$info[1]; ?></td>
  </tr>
  <input type="hidden" name="sid<?php echo $row_sbd['id']; ?>" value="<?php echo $id; ?>">
  	<?php } while($row_sbd = mysql_fetch_assoc($sbd)); ?>
  <?php } ?>
</table>

<p><input name="submit" type="submit" class="button" value="<?php if ($action == "edit") echo "Edit"; else echo "Add"; ?> Entries in <?php echo $row_sbi['sbi_name'];?>"></p>
<input type="hidden" name="relocate" value="<?php echo relocate($_SERVER['HTTP_REFERER'],"default"); ?>">
</form>
<?php } ?>

