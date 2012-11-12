<?php include(DB.'sponsors.db.php'); ?>
<h2><?php if ($action == "add") echo "Add a Sponsor"; elseif ($action == "edit") echo "Edit a Sponsor"; else echo "Sponsors"; ?></h2>
<div class="adminSubNavContainer">
   	<span class="adminSubNav">
    	<?php if (($action == "add") || ($action == "edit")) { ?>
    	<span class="icon"><img src="<?php echo $base_url; ?>/images/arrow_left.png" alt="Back"></span><a href="<?php echo $base_url; ?>/index.php?section=admin&amp;go=sponsors">Back to Sponsor List</a>
        <?php } else { ?>
        <span class="icon"><img src="<?php echo $base_url; ?>/images/award_star_add.png"  /></span><a href="<?php echo $base_url; ?>/index.php?section=admin&amp;go=sponsors&amp;action=add">Add a Sponsor</a>
   		<?php } ?>
    </span>
   	<span class="adminSubNav">
    	<span class="icon"><img src="<?php echo $base_url; ?>/images/picture_add.png"  /></span><a href="admin/upload.admin.php" title="Upload Sponsor Logo Image" id="modal_window_link" class="data"><?php if (($action == "add") || ($action == "edit")) echo "Upload the Sponsor's Logo Image"; else echo "Upload a Sponsor Logo Image"; ?></a>
	</span>
</div>
<?php if ($totalRows_sponsors > 0) { ?>
<?php if ($action == "default") { ?>
<div class="adminSubNavContainer">
<p><span class="icon"><img src="<?php echo $base_url; ?>/images/tick.png"  alt="Yes" title="Yes"></span> = The logo's image file is present on the server and the name of the file entered matches the file's name on the server.
<p><span class="icon"><img src="<?php echo $base_url; ?>/images/cross.png"  alt="No" title="No"></span> =  No logo.
</div>
<?php } ?>
<?php if ($action == "default") { ?>
<script type="text/javascript" language="javascript">
	 $(document).ready(function() {
		$('#sortable').dataTable( {
			"bPaginate" : true,
			"sPaginationType" : "full_numbers",
			"bLengthChange" : true,
			"iDisplayLength" : <?php echo $limit; ?>,
			"sDom": 'irtip',
			"bStateSave" : false,
			"aaSorting": [[0,'asc']],
			"aoColumns": [
				null,
				null,
				null,
				null,
				{ "asSorting": [  ] },
				{ "asSorting": [  ] },
				{ "asSorting": [  ] },
				]
			} );
		} );
	</script>
<table class="dataTable" id="sortable">
 <thead>
 <tr>
  <th class="dataHeading bdr1B">Sponsor Name</th>
  <th class="dataHeading bdr1B">Sponsor Location</th>
  <th class="dataHeading bdr1B">Website Address</th>
  <th class="dataHeading bdr1B">Level</th>
  <th class="dataHeading bdr1B">Logo?</th>
  <th class="dataHeading bdr1B">Description/Text</th>
  <th class="dataHeading bdr1B">Actions</th>
 </tr>
 </thead>
 <tbody>
 <?php do { ?>
 <tr>
  <td width="20%" class="dataList"><?php echo $row_sponsors['sponsorName']; ?></td>
  <td width="15%" class="dataList"><?php echo $row_sponsors['sponsorLocation']; ?></td>
  <td width="5%" class="dataList"><a href="<?php echo $row_sponsors['sponsorURL']; ?>" id="modal_window_link"><?php echo $row_sponsors['sponsorURL']; ?></a></td>
  <td width="5%" class="dataList"><?php echo $row_sponsors['sponsorLevel']; ?></td>
  <td width="5%" class="dataList"><?php if (($row_sponsors['sponsorImage'] !="") && (file_exists($_SERVER['DOCUMENT_ROOT'].'/user_images/'.$row_sponsors['sponsorImage']))) { ?><img src="<?php echo $base_url; ?>/images/tick.png"  alt="Yes"><?php } else { ?><img src="<?php echo $base_url; ?>/images/cross.png"  alt="No"><?php } ?></td>
  <td width="25%" class="dataList"><?php echo $row_sponsors['sponsorText']; ?></td>
  <td class="dataList" nowrap="nowrap">
  <span class="icon"><a href="<?php echo $base_url; ?>/index.php?section=admin&amp;go=<?php echo $go; ?>&amp;action=edit&amp;id=<?php echo $row_sponsors['id']; ?>"><img src="<?php echo $base_url; ?>/images/pencil.png"  border="0" alt="Edit <?php echo $row_sponsors['sponsorName']; ?>" title="Edit <?php echo $row_sponsors['sponsorName']; ?>"></a></span><span class="icon"><a href="javascript:DelWithCon('includes/process.inc.php?section=admin&amp;go=<?php echo $go; ?>&amp;dbTable=<?php echo $sponsors_db_table; ?>&amp;action=delete','id',<?php echo $row_sponsors['id']; ?>,'Are you sure you want to delete <?php echo $row_sponsors['sponsorName']; ?>? This cannot be undone.');"><img src="<?php echo $base_url; ?>/images/bin_closed.png"  border="0" alt="Delete <?php echo $row_sponsors['sponsorName']; ?>" title="Delete <?php echo $row_sponsors['sponsorName']; ?>"></a></span></td>
 </tr>
<?php } while($row_sponsors = mysql_fetch_assoc($sponsors)) ?>
 </tbody>
</table>
<?php } } else { ?>
<p>There are no sponsors in the database.</p>
<?php } ?>
<?php if (($action == "add") || ($action == "edit")) { ?>
<form method="post" action="<?php echo $base_url; ?>/includes/process.inc.php?action=<?php echo $action; ?>&amp;dbTable=<?php echo $sponsors_db_table; ?><?php if ($action == "edit") echo "&amp;id=".$id; ?>" name="form1">
<table>
  <tr>
    <td class="dataLabel">Sponsor Name:</td>
    <td class="data"><input name="sponsorName" type="text" size="50" maxlength="255" value="<?php if ($action == "edit") echo $row_sponsors['sponsorName']; ?>"></td>
    <td class="data">&nbsp;</td>
  </tr>
  <tr>
    <td class="dataLabel">Sponsor Location:</td>
    <td class="data"><input name="sponsorLocation" type="text" size="50" maxlength="255" value="<?php if ($action == "edit") echo $row_sponsors['sponsorLocation']; ?>"></td>
    <td class="data">&nbsp;</td>
  </tr>
  <tr>
    <td class="dataLabel">Sponsor Level:</td>
    <td class="data">
    <select name="sponsorLevel">
    	<option value="1" <?php if (($action == "edit") && ($row_sponsors['sponsorLevel'] == "1")) echo " SELECTED"; ?>>1</option>
        <option value="2" <?php if (($action == "edit") && ($row_sponsors['sponsorLevel'] == "2")) echo " SELECTED"; ?>>2</option>
        <option value="3" <?php if (($action == "edit") && ($row_sponsors['sponsorLevel'] == "3")) echo " SELECTED"; ?>>3</option>
        <option value="4" <?php if (($action == "edit") && ($row_sponsors['sponsorLevel'] == "4")) echo " SELECTED"; ?>>4</option>
        <option value="5" <?php if (($action == "edit") && ($row_sponsors['sponsorLevel'] == "5")) echo " SELECTED"; ?>>5</option>
    </select>
    </td>
    <td class="data">1 is the highest level of sponsorship; 5 is the lowest.</td>
  </tr>
  <tr>
    <td class="dataLabel">Website Address:</td>
    <td class="data"><input name="sponsorURL" type="text" size="50" maxlength="255" value="<?php if ($action == "edit") echo $row_sponsors['sponsorURL']; ?>"></td>
    <td class="data">Be sure to include the entire website address, including the http:// (e.g., http://www.google.com).</td>
  </tr>
  <tr>
    <td class="dataLabel">Logo Image Name:</td>
    <td class="data"><input name="sponsorImage" type="text" size="50" maxlength="255" value="<?php if ($action == "edit") echo $row_sponsors['sponsorImage']; ?>"></td>
    <td class="data">Be sure to enter the <em>exact name</em> of the file (e.g., sponsor_logo.jpg) that has been <a href="admin/upload.admin.php" title="Upload Sponsor Logo Image" id="modal_window_link">uploaded</a>.</td>
  </tr>
  <tr>
    <td class="dataLabel">Description:</td>
    <td class="data"><input name="sponsorText"type="text" size="80" value="<?php if ($action == "edit") echo $row_sponsors['sponsorText']; ?>" /></td>
    <td class="data">Any additional information about the sponsor (e.g., a description of sponsorship level, the items donated, money contributed, etc.).</td>
  </tr>
  <tr>
  	<td>&nbsp;</td>
  	<td colspan="2" class="data"><input name="submit" type="submit" class="button" value="<?php if ($action == "edit") echo "Edit"; else echo "Add"; ?> Sponsor"></td>
  </tr>
</table>
<input type="hidden" name="relocate" value="<?php echo relocate($_SERVER['HTTP_REFERER'],"default",$msg,$id); ?>">
</form>
<?php } ?>