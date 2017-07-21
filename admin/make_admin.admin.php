<?php include (DB.'admin_make_admin.db.php'); 

if ($_SESSION['userLevel'] == 0) $edit_user_enable = 1;
else $edit_user_enable = 0;

?>
<p class="lead">Change User Level for <?php echo $row_brewer['brewerFirstName']." ".$row_brewer['brewerLastName']; ?></p>

<form class="form-hoizontal" action="<?php echo $base_url; ?>includes/process.inc.php?section=<?php echo $section; ?>&amp;action=edit&amp;dbTable=<?php echo $users_db_table; ?>&amp;go=make_admin" name="form1" method="post">
<p><strong>Top-level admins</strong> have full access to add, change, and delete all information in the database, including preferences, competition information, and archival data.</p>
<p><strong>Admin users</strong> are able to add, change, and delete most information in the database, including participants, entries, tables, scores, etc.</p>
<div class="bcoem-admin-element hidden-print">
<div class="form-group"><!-- Form Group Radio STACKED -->
	<label for="userLevel" class="col-lg-1 col-md-3 col-sm-4 col-xs-12 control-label">User Level</label>
	<div class="col-lg-6 col-md-3 col-sm-8 col-xs-12">
		<div class="input-group">
			<!-- Input Here -->
			<div class="radio">
				<label>
					<input type="radio" name="userLevel" id="userLevel_2" value="2" <?php if ($row_username['userLevel'] == "2") echo "checked"; ?>> Participant
				</label>
			</div>
			<div class="radio">
				<label>
					<input type="radio" name="userLevel" id="userLevel_1" value="1" <?php if ($row_username['userLevel'] == "1") echo "checked"; ?>> Admin
				</label>
			</div>
			<div class="radio">
				<label>
					<input type="radio" name="userLevel" id="userLevel_0" value="0" <?php if ($row_username['userLevel'] == "0") echo "checked"; ?>> Top-Level Admin
				</label>
			</div>
		</div>
	</div>
</div><!-- ./Form Group -->
<div class="bcoem-admin-element hidden-print">
	<div class="form-group">
		<div class="col-sm-offset-1 col-sm-10">
			<input name="submit" type="submit" class="btn btn-primary" value="Change User Level">
		</div>
	</div>
</div>
</div>
<input type="hidden" name="user_name" value="<?php echo $row_username['user_name']; ?>">
<input type="hidden" name="userEdit" value="<?php echo $edit_user_enable; ?>">
<?php if (isset($_SERVER['HTTP_REFERER'])) { ?>
<input type="hidden" name="relocate" value="<?php echo relocate($_SERVER['HTTP_REFERER'],"default",$msg,$id); ?>">
<?php } else { ?>
<input type="hidden" name="relocate" value="<?php echo relocate($base_url."index.php?section=admin&go=participants","default",$msg,$id); ?>">
<?php } ?>
</form>

