<?php
/**
 * Module:      mods.admin.php
 * Description: Add, edit, and delete any custom modules that extend core functions.
 */

$query_mods = "SELECT * FROM $mods_db_table";
if ($action == "edit") $query_mods .= sprintf(" WHERE id='%s'",$id);
$mods = mysql_query($query_mods, $brewing) or die(mysql_error());
$row_mods = mysql_fetch_assoc($mods);
$totalRows_mods = mysql_num_rows($mods); 

function mod_info($info,$method) {
	if ($method == 1) {
		switch($info) {
			case "0": $output = "Informational"; break;
			case "1": $output = "Report"; break;
			case "2": $output = "Export"; break;
			case "3": $output = "Other"; break;
		}
	}
	
	if ($method == 2) {
		switch($info) {
			case "0": $output = "None"; break;
			case "1": $output = "Home Page"; break;
			case "2": $output = "Rules"; break;
			case "3": $output = "Volunteer Info"; break;
			case "4": $output = "Sponsors"; break;
			case "5": $output = "Contact"; break;
			case "6": $output = "Registration"; break;
			case "7": $output = "Payment"; break;
			case "8": $output = "User's My Info and Entries"; break;
			case "9": $output = "Administration"; break;
		}
	}
	
	if ($method == 3) {
		switch($info) {
			case "0": $output = "Top level admins"; break;
			case "1": $output = "Admins"; break;
			case "2": $output = "All"; break;
		}
	}
	
	if ($method == 4) {
		switch($info) {
			case "0": $output = "N/A (Stand-alone)"; break;
			case "1": $output = "Before core content"; break;
			case "2": $output = "After core content"; break;
		}
	}
	
	return $output;
}

 ?>
<h2><?php if ($action == "add") echo "Add a Custom Module"; elseif ($action == "edit") echo "Edit a Custom Module"; else echo "Custom Modules"; ?></h2>
<div class="adminSubNavContainer">
   	<span class="adminSubNav"><span class="icon"><img src="<?php echo $base_url; ?>/images/arrow_left.png" alt="Back"></span><a href="<?php echo $base_url; ?>/index.php?section=admin">Back to Admin Dashboard</a></span>
    	<?php if (($action == "add") || ($action == "edit")) { ?>
    	<span class="adminSubNav"><span class="icon"><img src="<?php echo $base_url; ?>/images/arrow_left.png" alt="Back"></span><a href="<?php echo $base_url; ?>/index.php?section=admin&amp;go=mods">Back to the Custom Modules List</a></span>
        <?php } else { ?>
        <span class="adminSubNav"><span class="icon"><img src="<?php echo $base_url; ?>/images/award_star_add.png" /></span><a href="<?php echo $base_url; ?>/index.php?section=admin&amp;go=mods&amp;action=add">Add a Custom Module</a></span>
   		<?php } ?>
    </span>
</div>
<?php if ($action == "default") { ?>
    <p>Custom modules are useful for competitions that wish to extend BCOE&amp;M's core functions. Provided in the program package are templates for reports (both on-screen and printed) and simple HTML files. These are located in the &ldquo;Mods&rdquo; sub-folder. A complete guide to adding and using custom modules is provided in the BCOE&amp;M help site.</p>
    <p>Below is a list of the custom modules added to the database. For the program to use any custom module, its information MUST be added into the database and the file uploaded to the &ldquo;Mods&rdquo; sub-folder.</p>
	<?php if ($totalRows_mods > 0) { ?>
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
                    { "asSorting": [  ] },
                    null,
                    null,
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
      <th class="dataHeading bdr1B">Type</th>
      <th class="dataHeading bdr1B">Extends Core Function</th>
      <th class="dataHeading bdr1B">Extends Admin Function</th>
      <th class="dataHeading bdr1B">File Name</th>
      <th class="dataHeading bdr1B">Permission</th>
      <th class="dataHeading bdr1B">Display</th>
      <th class="dataHeading bdr1B">Actions</th>
     </tr>
     </thead>
     <tbody>
     <?php do { ?>
     <tr>
      <td width="15%" class="dataList"><?php echo $row_mods['mod_name']; ?></td>
      <td width="20%" class="dataList"><?php echo $row_mods['mod_description']; ?></td>
      <td width="5%" class="dataList"><?php echo mod_info($row_mods['mod_type'],1); ?></td>
      <td width="10%" class="dataList"><?php echo mod_info($row_mods['mod_extend_function'],2); ?></td>
      <td width="10%" class="dataList"><?php echo ucfirst(str_replace("_"," ",$row_mods['mod_extend_function_admin'])); ?></td>
      <td width="10%" class="dataList"><?php echo $row_mods['mod_filename']; ?></td>
      <td width="10%" class="dataList"><?php echo mod_info($row_mods['mod_permission'],3); ?></td>
      <td width="10%" class="dataList"><?php echo mod_info($row_mods['mod_display_rank'],4); ?></td>
      <td class="dataList" nowrap="nowrap">
      <span class="icon"><a href="<?php echo $base_url; ?>/index.php?section=admin&amp;go=<?php echo $go; ?>&amp;action=edit&amp;id=<?php echo $row_mods['id']; ?>"><img src="<?php echo $base_url; ?>/images/pencil.png"  border="0" alt="Edit <?php echo $row_mods['mod_name']; ?>" title="Edit <?php echo $row_mods['mod_name']; ?>"></a></span><span class="icon"><a href="javascript:DelWithCon('includes/process.inc.php?section=admin&amp;go=<?php echo $go; ?>&amp;dbTable=<?php echo $special_best_info_db_table; ?>&amp;action=delete','id',<?php echo $row_mods['id']; ?>,'Are you sure you want to delete <?php echo $row_mods['mod_name']; ?>? This cannot be undone. All associated data will be deleted as well.');"><img src="<?php echo $base_url; ?>/images/bin_closed.png"  border="0" alt="Delete <?php echo $row_mods['mod_name']; ?>" title="Delete <?php echo $row_mods['mod_name']; ?>"></a></span>
      </td>
     </tr>
    <?php } while($row_mods = mysql_fetch_assoc($mods)) ?>
     </tbody>
    </table>
    <?php } else echo "<p>There are no custom modules were found in the database.</p>";
} 
if (($action == "add") || ($action == "edit")) { ?>
<script type="text/javascript" src="<?php echo $base_url; ?>/js_includes/usable_forms.js"></script>
<form method="post" action="<?php echo $base_url; ?>/includes/process.inc.php?action=<?php echo $action; ?>&amp;dbTable=<?php echo $mods_db_table; ?><?php if ($action == "edit") echo "&amp;id=".$id; ?>" name="form1">
<table>
  <tr>
    <td class="dataLabel">Custom Module Name:</td>
    <td colspan="2" class="data"><input name="mod_name" type="text" size="30" maxlength="255" value="<?php if ($action == "edit") echo $row_mods['mod_name']; ?>"></td>
  </tr>
  <tr>
    <td class="dataLabel">Description:</td>
    <td class="data" colspan="2"><textarea name="mod_description" class="submit mceNoEditor" cols="40" rows="6"><?php if ($action == "edit") echo $row_mods['mod_description']; ?></textarea><!-- <p><a href="javascript:toggleEditor('mod_description');">Enable/Disable Rich Text</a></p>--></td>
  </tr>
  <tr>
    <td class="dataLabel">File Name:</td>
    <td class="data"><input name="mod_filename" type="text" size="30" maxlength="255" value="<?php if ($action == "edit") echo $row_mods['mod_filename']; ?>"></td>
    <td class="data"><span class="icon"><img src="<?php echo $base_url; ?>/images/brick_add.png" alt="Upload the Custom Module file"></span><a href="<?php echo $base_url; ?>/admin/upload_mod.admin.php" title="Upload the Custom Module file" id="modal_window_link" class="data">Upload the Custom Module file</a></td>
  </tr>
  <tr>
    <td class="dataLabel">Type:</td>
    <td class="data">
    <select name="mod_type">
    	<option value="0" <?php if (($action == "edit") && ($row_mods['mod_type'] == "0")) echo " SELECTED"; ?>>Informational</option>
        <option value="1" <?php if (($action == "edit") && ($row_mods['mod_type'] == "1")) echo " SELECTED"; ?>>Report</option>
        <option value="2" <?php if (($action == "edit") && ($row_mods['mod_type'] == "2")) echo " SELECTED"; ?>>Export</option>
        <option value="3" <?php if (($action == "edit") && ($row_mods['mod_type'] == "3")) echo " SELECTED"; ?>>Other</option>
    </select>
    </td>
    <td class="data">&nbsp;</td>
  </tr>
  
  
  
  
  <tr>
    <td class="dataLabel">Permission:</td>
    <td class="data">
    <select name="mod_permission" id="mod_permission">
    	<option value="0" <?php if (($action == "edit") && ($row_mods['mod_permission'] == "0")) echo " SELECTED"; ?>>Top Level Admins</option>
    	<option value="1" <?php if (($action == "edit") && ($row_mods['mod_permission'] == "1")) echo " SELECTED"; ?>>Admins</option>
        <option value="2" <?php if (($action == "edit") && ($row_mods['mod_permission'] == "2")) echo " SELECTED"; ?>>All Users</option>
    </select>
    </td>
    <td class="data">If informational, where will the module's contents be displayed?</td>
  </tr>
  <tr>
    <td class="dataLabel">Core Function Extends:</td>
    <td class="data">
    <select name="mod_extend_function" id="mod_extend_function">
    	<option rel="none" value="0" <?php if (($action == "edit") && ($row_mods['mod_extend_function'] == "0")) echo " SELECTED"; ?>>None</option>
		<option rel="none" value="1" <?php if (($action == "edit") && ($row_mods['mod_extend_function'] == "1")) echo " SELECTED"; ?>>Home Page</option>
		<option rel="none" value="2" <?php if (($action == "edit") && ($row_mods['mod_extend_function'] == "2")) echo " SELECTED"; ?>>Rules</option>
		<option rel="none" value="3" <?php if (($action == "edit") && ($row_mods['mod_extend_function'] == "3")) echo " SELECTED"; ?>>Volunteer Info</option>
		<option rel="none" value="4" <?php if (($action == "edit") && ($row_mods['mod_extend_function'] == "4")) echo " SELECTED"; ?>>Sponsors</option>
		<option rel="none" value="5" <?php if (($action == "edit") && ($row_mods['mod_extend_function'] == "5")) echo " SELECTED"; ?>>Contact</option>
		<option rel="none" value="6" <?php if (($action == "edit") && ($row_mods['mod_extend_function'] == "6")) echo " SELECTED"; ?>>Registration</option>
		<option rel="none" value="7" <?php if (($action == "edit") && ($row_mods['mod_extend_function'] == "7")) echo " SELECTED"; ?>>Payment</option>
		<option rel="none" value="8" <?php if (($action == "edit") && ($row_mods['mod_extend_function'] == "8")) echo " SELECTED"; ?>>User's My Info and Entries</option>
        <option rel="none" value="10" <?php if (($action == "edit") && ($row_mods['mod_extend_function'] == "10")) echo " SELECTED"; ?>>Entry Information</option>
        <option rel="none" value="11" <?php if (($action == "edit") && ($row_mods['mod_extend_function'] == "11")) echo " SELECTED"; ?>>Results</option>
		<option rel="admin" value="9" <?php if (($action == "edit") && ($row_mods['mod_extend_function'] == "9")) echo " SELECTED"; ?>>Administration</option>
    </select>
    </td>
    <td class="data">&nbsp;</td>
  </tr>
  <tr rel="admin">
    <td class="dataLabel">Extends Admin Function:</td>
    <td class="data">
    <select name="mod_extend_function_admin" id="mod_extend_function_admin">
    	<option value=""></option>
        <option value="archives" <?php if (($action == "edit") && ($row_mods['mod_extend_function_admin'] == "archives")) echo " SELECTED"; ?>>Archives</option>
    	<option value="entries" <?php if (($action == "edit") && ($row_mods['mod_extend_function_admin'] == "entries")) echo " SELECTED"; ?>>Entry Administration</option>
        <option value="judging_scores" <?php if (($action == "edit") && ($row_mods['mod_extend_function_admin'] == "judging_scores")) echo " SELECTED"; ?>>Scoring</option>
        <option value="judging_scores_bos" <?php if (($action == "edit") && ($row_mods['mod_extend_function_admin'] == "judging_scores_bos")) echo " SELECTED"; ?>>Scoring - Best of Show</option>
        <option value="special_best" <?php if (($action == "edit") && ($row_mods['mod_extend_function_admin'] == "special_best")) echo " SELECTED"; ?>>Scoring - Special Best of Show Categories</option>
        <option value="styles" <?php if (($action == "edit") && ($row_mods['mod_extend_function_admin'] == "styles")) echo " SELECTED"; ?>>Styles</option>    			
        <option value="style_types" <?php if (($action == "edit") && ($row_mods['mod_extend_function_admin'] == "style_types")) echo " SELECTED"; ?>>Style Types</option>
    	<option value="judging_tables" <?php if (($action == "edit") && ($row_mods['mod_extend_function_admin'] == "judging_tables")) echo " SELECTED"; ?>>Table Administration</option>
    	<option value="participants" <?php if (($action == "edit") && ($row_mods['mod_extend_function_admin'] == "participants")) echo " SELECTED"; ?>>Users (Participants)</option>
   </select>
    </td>
    <td class="data">What Admin function will the module extend?</td>
  </tr>
  <tr>
    <td class="dataLabel">Rank:</td>
    <td class="data">
    <select name="mod_rank" id="mod_rank">
    	<option value="0" <?php if (($action == "edit") && ($row_mods['mod_rank'] == "0")) echo " SELECTED"; ?>>N/A</option>
    	<option value="1" <?php if (($action == "edit") && ($row_mods['mod_rank'] == "1")) echo " SELECTED"; ?>>1</option>
        <option value="2" <?php if (($action == "edit") && ($row_mods['mod_rank'] == "2")) echo " SELECTED"; ?>>2</option>
        <option value="3" <?php if (($action == "edit") && ($row_mods['mod_rank'] == "3")) echo " SELECTED"; ?>>3</option>
        <option value="4" <?php if (($action == "edit") && ($row_mods['mod_rank'] == "4")) echo " SELECTED"; ?>>4</option>
        <option value="5" <?php if (($action == "edit") && ($row_mods['mod_rank'] == "5")) echo " SELECTED"; ?>>5</option>
        <option value="6" <?php if (($action == "edit") && ($row_mods['mod_rank'] == "6")) echo " SELECTED"; ?>>6</option>
        <option value="7" <?php if (($action == "edit") && ($row_mods['mod_rank'] == "7")) echo " SELECTED"; ?>>7</option>
        <option value="8" <?php if (($action == "edit") && ($row_mods['mod_rank'] == "8")) echo " SELECTED"; ?>>8</option>
        <option value="9" <?php if (($action == "edit") && ($row_mods['mod_rank'] == "9")) echo " SELECTED"; ?>>9</option>
        <option value="10" <?php if (($action == "edit") && ($row_mods['mod_rank'] == "10")) echo " SELECTED"; ?>>10</option>
        <option value="11" <?php if (($action == "edit") && ($row_mods['mod_rank'] == "11")) echo " SELECTED"; ?>>11</option>
        <option value="12" <?php if (($action == "edit") && ($row_mods['mod_rank'] == "12")) echo " SELECTED"; ?>>12</option>
        <option value="13" <?php if (($action == "edit") && ($row_mods['mod_rank'] == "13")) echo " SELECTED"; ?>>13</option>
        <option value="14" <?php if (($action == "edit") && ($row_mods['mod_rank'] == "14")) echo " SELECTED"; ?>>14</option>
        <option value="15" <?php if (($action == "edit") && ($row_mods['mod_rank'] == "15")) echo " SELECTED"; ?>>15</option>
        <option value="16" <?php if (($action == "edit") && ($row_mods['mod_rank'] == "16")) echo " SELECTED"; ?>>16</option>
        <option value="17" <?php if (($action == "edit") && ($row_mods['mod_rank'] == "17")) echo " SELECTED"; ?>>17</option>
        <option value="18" <?php if (($action == "edit") && ($row_mods['mod_rank'] == "18")) echo " SELECTED"; ?>>18</option>
        <option value="19" <?php if (($action == "edit") && ($row_mods['mod_rank'] == "19")) echo " SELECTED"; ?>>19</option>
        <option value="20" <?php if (($action == "edit") && ($row_mods['mod_rank'] == "20")) echo " SELECTED"; ?>>20</option>
    </select>
    </td>
    <td class="data">Determines custom module's rank in the display order. Useful if more than one custom module is displayed on a certain page (e.g., Rules or Home). The lower the number, the higher priority.</td>
  </tr>
  <tr>
    <td class="dataLabel">Display Order:</td>
    <td class="data">
    <select name="mod_display_rank" id="mod_display_rank">
    	<option value="0" <?php if (($action == "edit") && ($row_mods['mod_display_rank'] == "0")) echo " SELECTED"; ?>>N/A (Stand Alone)</option>
    	<option value="1" <?php if (($action == "edit") && ($row_mods['mod_display_rank'] == "1")) echo " SELECTED"; ?>>Before Core Content</option>
        <option value="2" <?php if (($action == "edit") && ($row_mods['mod_display_rank'] == "2")) echo " SELECTED"; ?>>After Core Content</option>
    </select>
    </td>
    <td class="data">If informational, where will the module's contents be displayed?</td>
  </tr>
</table>
<p><input name="submit" type="submit" class="button" value="<?php if ($action == "edit") echo "Edit"; else echo "Add"; ?> Custom Module"></p>
<input type="hidden" name="relocate" value="<?php echo relocate($_SERVER['HTTP_REFERER'],"default",$msg,$id); ?>">
</form>
<?php } ?>