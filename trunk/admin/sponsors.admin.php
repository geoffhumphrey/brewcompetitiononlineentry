<h2><?php if ($action == "add") echo "Add a Sponsor"; elseif ($action == "edit") echo "Edit a Sponsor"; else echo "Sponsors"; ?></h2>

<table class="dataTable">
 <tr>
   <?php if (($action == "add") || ($action == "edit")) { ?><td class="dataList" width="5%" nowrap="nowrap"><a href="index.php?section=admin&go=sponsors">&laquo;  Back to Sponsor List</a></td><?php } else { ?><td class="dataList" width="5%" nowrap="nowrap"><span class="icon"><img src="images/award_star_add.png" align="absmiddle" /></span><a class="data" href="index.php?section=admin&go=sponsors&action=add">Add a Sponsor</a></td><?php } ?>
   <td class="dataList"><span class="icon"><img src="images/picture_add.png" align="absmiddle" /></span><a href="admin/upload.admin.php?KeepThis=true&TB_iframe=true&height=350&width=800" title="Upload Sponsor Logo Image" class="data thickbox"><?php if (($action == "add") || ($action == "edit")) echo "Upload the Sponsor's Logo Image"; else echo "Upload a Sponsor Logo Image"; ?></a></td>
 </tr>
</table>
<?php if ($totalRows_sponsors > 0) { ?>
<table class="dataTable">
 <?php if ($action == "default") { ?>
 <tr>
 	<td class="data"><img src="images/tick.png" align="absmiddle" alt="Yes" title="Yes"></td>
    <td class="data">=</td>
    <td class="data">The logo's image file is present on the server and the name of the file entered matches the file's name on the server.</td>
 </tr>
 <tr>
    <td class="data"><img src="images/cross.png" align="absmiddle" alt="No" title="No"></td>
    <td class="data">=</td>
    <td class="data">No logo present, possibly due to a) the name of the file in the sponsor's record does not match the name of the uploaded file, b) the name of the file is missing in the record, or c) the file has not been uploaded to the server.</td>
 </tr>
 <?php } ?>
</table>
<?php if ($action == "default") { ?>
<table class="dataTable">
 <tr>
  <td class="dataHeading bdr1B">Sponsor Name</td>
  <td class="dataHeading bdr1B">Sponsor Location</td>
  <td class="dataHeading bdr1B">Website Address</td>
  <td class="dataHeading bdr1B">Logo?</td>
  <td class="dataHeading bdr1B">Description/Text</td>
  <td class="dataHeading bdr1B">Actions</td>
 </tr>
 <?php do { ?>
 <tr <?php echo " style=\"background-color:$color\"";?>>
  <td width="20%" class="dataList"><?php echo $row_sponsors['sponsorName']; ?></td>
  <td width="15%" class="dataList"><?php echo $row_sponsors['sponsorLocation']; ?></td>
  <td width="5%" class="dataList"><a href="<?php echo $row_sponsors['sponsorURL']; ?>?KeepThis=true&TB_iframe=true&height=350&width=800" class="thickbox"><?php echo $row_sponsors['sponsorURL']; ?></a></td>
  <td width="5%" class="dataList"><?php if (($row_sponsors['sponsorImage'] !="") && (file_exists('user_images/'.$row_sponsors['sponsorImage']))) { ?><img src="images/tick.png" align="absmiddle" alt="Yes"><?php } else { ?><img src="images/cross.png" align="absmiddle" alt="No"><?php } ?></td>
  <td width="25%" class="dataList"><?php echo $row_sponsors['sponsorText']; ?></td>
  <td class="dataList">
  <span class="icon"><a href="index.php?section=admin&go=<?php echo $go; ?>&action=edit&id=<?php echo $row_sponsors['id']; ?>"><img src="images/pencil.png" align="absmiddle" border="0" alt="Edit <?php echo $row_sponsors['sponsorName']; ?>" title="Edit <?php echo $row_sponsors['sponsorName']; ?>"></a></span><span class="icon"><a href="javascript:DelWithCon('includes/process.inc.php?section=admin&go=<?php echo $go; ?>&dbTable=sponsors&action=delete','id',<?php echo $row_sponsors['id']; ?>,'Are you sure you want to delete <?php echo $row_sponsors['sponsorName']; ?>? This cannot be undone.');"><img src="images/bin_closed.png" align="absmiddle" border="0" alt="Delete <?php echo $row_sponsors['sponsorName']; ?>" title="Delete <?php echo $row_sponsors['sponsorName']; ?>"></a></span></td>
 </tr>
  <?php if ($color == $color1) { $color = $color2; } else { $color = $color1; } ?>
  <?php } while($row_sponsors = mysql_fetch_assoc($sponsors)) ?>
 <tr>
 	<td colspan="8" class="bdr1T">&nbsp;</td>
 </tr>
</table>
<?php } } else { ?>
<p>There are no sponsors in the database.</p>
<?php } ?>
<?php if (($action == "add") || ($action == "edit")) { ?>
<form method="post" action="includes/process.inc.php?action=<?php echo $action; ?>&dbTable=sponsors<?php if ($action == "edit") echo "&id=".$id; ?>" name="form1">
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
    <td class="dataLabel">Website Address:</td>
    <td class="data"><input name="sponsorURL" type="text" size="50" maxlength="255" value="<?php if ($action == "edit") echo $row_sponsors['sponsorURL']; ?>"></td>
    <td class="data">Be sure to include the entire website address, including the http:// (e.g., http://www.google.com).</td>
  </tr>
  <tr>
    <td class="dataLabel">Logo Image Name:</td>
    <td class="data"><input name="sponsorImage" type="text" size="50" maxlength="255" value="<?php if ($action == "edit") echo $row_sponsors['sponsorImage']; ?>"></td>
    <td class="data">Be sure to enter the <em>exact name</em> of the file (e.g., sponsor_logo.jpg) that has been <a href="admin/upload.admin.php?KeepThis=true&TB_iframe=true&height=350&width=800" title="Upload Sponsor Logo Image" class="thickbox">uploaded</a>.</td>
  </tr>
  <tr>
    <td class="dataLabel">Description:</td>
    <td class="data"><input name="sponsorText"type="text" size="80" value="<?php if ($action == "edit") echo $row_sponsors['sponsorText']; ?>" /></td>
    <td class="data">Any additional information about the sponsor (e.g., the items donated, money contributed, etc.).</td>
  </tr>
  <tr>
  	<td>&nbsp;</td>
  	<td colspan="2" class="data"><input name="submit" type="submit" class="button" value="Add Sponsor"></td>
  </tr>
</table>
</form>
<?php } ?>