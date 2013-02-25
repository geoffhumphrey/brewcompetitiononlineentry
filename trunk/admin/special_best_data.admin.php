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

if (($action == "add") || ($action == "edit")) $query_sbi .= " WHERE id='$id'"; 

$sbi = mysql_query($query_sbi, $brewing) or die(mysql_error());

$row_sbi = mysql_fetch_assoc($sbi);

$totalRows_sbi = mysql_num_rows($sbi);



$query_sbd = "";

if ($action == "add") $query_sbd .= "SELECT * FROM $special_best_data_db_table WHERE id='$id'";

elseif ($action == "edit") $query_sbd .= "SELECT * FROM $special_best_data_db_table WHERE sid='$id' ORDER BY sbd_place ASC";

else $query_sbd .= "SELECT * FROM $special_best_data_db_table ORDER BY sid,sbd_place ASC";

$sbd = mysql_query($query_sbd, $brewing) or die(mysql_error());

$row_sbd = mysql_fetch_assoc($sbd);

$totalRows_sbd = mysql_num_rows($sbd);



?> 

<h2><?php if ($action == "add") echo "Add Entries to Custom Winning Category: ".$row_sbi['sbi_name']; elseif ($action == "edit") echo "Edit Entries in Custom Winning Category: ".$row_sbi['sbi_name']; else echo "Custom Winning Category Entries"; ?></h2>

<div class="adminSubNavContainer">

   	<span class="adminSubNav">

    	<span class="icon"><img src="<?php echo $base_url; ?>images/arrow_left.png" alt="Back"></span><a href="<?php echo $base_url; ?>index.php?section=admin">Back to Admin Dashboard</a></span>

    </span>

    <span class="adminSubNav">

    	<span class="icon"><img src="<?php echo $base_url; ?>images/arrow_left.png" alt="Back"></span><a href="<?php echo $base_url; ?>index.php?section=admin&amp;go=special_best">Back to the Custom Winning Category List</a>

    </span>

    <span class="adminSubNav">

    	<span class="icon"><img src="<?php echo $base_url; ?>images/arrow_left.png" alt="Back"></span><a href="<?php echo $base_url; ?>index.php?section=admin&amp;go=special_best_data">Back to the Custom Winning Category Entry List</a>

    </span>

</div>

<div class="adminSubNavContainer">

    <span class="adminSubNav">

    	<span class="icon"><img src="<?php echo $base_url; ?>images/award_star_add.png" /></span>Add/Edit Custom Winning Category Entries For: <?php echo score_custom_winning_choose($special_best_info_db_table,$special_best_data_db_table); ?>

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

    <?php do { 

	$info = explode("^", entry_info($row_sbd['eid']));

	$brewer_info = explode("^", brewer_info($row_sbd['bid']));

	

	$query_sbi = sprintf("SELECT * FROM $special_best_info_db_table WHERE id='%s'",$row_sbd['sid']); 

	$sbi = mysql_query($query_sbi, $brewing) or die(mysql_error());

	$row_sbi = mysql_fetch_assoc($sbi);

	$totalRows_sbi = mysql_num_rows($sbi);

	?>

     <tr>

      <td width="15%" class="dataList"><?php echo $row_sbi['sbi_name']; ?></td>

      <td width="1%" class="dataList"><?php echo $row_sbd['sbd_place']; ?></td>

      <td width="1%" class="dataList"><?php echo sprintf("%04s",$row_sbd['eid']); ?></td>

      <td width="1%" class="dataList"><?php echo readable_judging_number($info[1],$info[6]); ?></td>

      <td width="20%" class="dataList"><?php echo $info[0]; ?></td>

      <td width="20%" class="dataList"><?php echo $brewer_info[0]." ".$brewer_info[1]; ?></td>

      <td class="dataList" nowrap="nowrap">

      <span class="icon"><a href="<?php echo $base_url; ?>index.php?section=admin&amp;go=<?php echo $go; ?>&amp;action=edit&amp;id=<?php echo $row_sbi['id']; ?>"><img src="<?php echo $base_url; ?>images/pencil.png"  border="0" alt="Edit <?php echo $row_sbd['brewName']; ?>" title="Edit <?php echo $row_sbd['brewName']; ?>"></a></span><span class="icon"><a href="javascript:DelWithCon('includes/process.inc.php?section=admin&amp;go=<?php echo $go; ?>&amp;dbTable=<?php echo $special_best_data_db_table; ?>&amp;action=delete','id',<?php echo $row_sbd['id']; ?>,'Are you sure you want to delete? This cannot be undone.');"><img src="<?php echo $base_url; ?>images/bin_closed.png"  border="0" alt="Delete" title="Delete"></a></span>

      </td>

     </tr>

    <?php

	mysql_free_result($sbi);

	} while($row_sbd = mysql_fetch_assoc($sbd));  ?>

     </tbody>

    </table>

    <?php } 

	else 

	echo "

	<p>There are no entries found in any custom winner category.</p>

	<p>Add Winners for Custom Winning Cateory: ".score_custom_winning_choose($special_best_info_db_table,$special_best_data_db_table)."</p>";

} 

if (($action == "add") || ($action == "edit")) { ?>

<form method="post" action="<?php echo $base_url; ?>includes/process.inc.php?action=<?php echo $action; ?>&amp;dbTable=<?php echo $special_best_data_db_table; ?>" name="form1">

<table>

<?php if ($action == "add") { for ($i=1; $i <= $row_sbi['sbi_places']; $i++) { ?>

	<input type="hidden" name="id[]" value="<?php echo $i; ?>" />

  <tr>

    <td class="dataLabel"><?php echo $row_sbi['sbi_name']; ?> Winning Entry <?php echo $i; ?> Judging Number:</td>

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

		do { 

		$info = explode("^", entry_info($row_sbd['eid']));

		?>

  <input type="hidden" name="id[]" value="<?php echo $row_sbd['id']; ?>" />

  <input type="hidden" name="bid<?php echo $row_sbd['id']; ?>" value="<?php echo $row_sbd['bid']; ?>" />

  <input type="hidden" name="eid<?php echo $row_sbd['id']; ?>" value="<?php echo $row_sbd['eid']; ?>" />

  <input type="hidden" name="sid<?php echo $row_sbd['id']; ?>" value="<?php echo $id; ?>">

  <input type="hidden" name="entry_exists<?php echo $row_sbd['id']; ?>" value="Y" />

  <tr>

    <td class="dataLabel"><?php echo $row_sbi['sbi_name']; ?> Winning Entry Judging Number:</td>

    <td class="data"><input name="sbd_judging_no<?php echo $row_sbd['id']; ?>" type="text" size="10" maxlength="255" value="<?php echo readable_judging_number($info[1],$info[6]); ?>"></td>

    <td class="dataLabel">Place:</td>

    <td class="data"><input name="sbd_place<?php  echo $row_sbd['id']; ?>" type="text" size="5" value="<?php echo $row_sbd['sbd_place']; ?>"></td>

    <td class="dataLabel">Entry Name:</td> 

	<td class="data"><?php echo $info[0]; ?></td>

    <td class="dataLabel">Brewer:</td>

	<td class="data"><?php $info = explode("^", brewer_info($row_sbd['bid'])); echo $info[0]." ".$info[1]; ?></td>

  </tr>

  	<?php } while($row_sbd = mysql_fetch_assoc($sbd)); 

	

	if ($totalRows_sbd < $row_sbi['sbi_places']) {

	

	for ($i=1; $i <= ($row_sbi['sbi_places'] - $totalRows_sbd); $i++) { 

	$random = random_generator(6,2);

	?>

    <input type="hidden" name="id[]" value="<?php echo $random; ?>" />

    <input type="hidden" name="entry_exists<?php echo $random; ?>" value="N" />

    <input type="hidden" name="sid<?php echo $random; ?>" value="<?php echo $id; ?>">

  <tr>

    <td class="dataLabel"><?php echo $row_sbi['sbi_name']; ?> Winning Entry Judging Number:</td>

    <td class="data"><input name="sbd_judging_no<?php echo $random; ?>" type="text" size="10" maxlength="255" value=""></td>

    <td class="dataLabel">Place:</td>

    <td class="data"><input name="sbd_place<?php echo $random; ?>" type="text" size="5" value=""></td>

  </tr>

  <?php }

	}

	?>

  <?php } ?>

</table>



<p><input name="submit" type="submit" class="button" value="<?php if ($action == "edit") echo "Edit"; else echo "Add"; ?> Entries in <?php echo $row_sbi['sbi_name'];?>"></p>

<input type="hidden" name="relocate" value="<?php echo relocate($_SERVER['HTTP_REFERER'],"default",$msg,$id); ?>">

</form>

<?php } ?>



