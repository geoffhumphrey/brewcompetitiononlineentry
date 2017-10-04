<?php include (DB.'brewer.db.php'); ?>
<script type="text/javascript">
        $(document).ready(function () {
            "use strict";
            var options = {};
            options.ui = {
                container: "#pwd-container",
				showErrors: true,
				useVerdictCssClass: true,
                showVerdictsInsideProgressBar: true,
                viewports: {
                    progress: ".pwd-strength-viewport-progress"
                },
				progressBarExtraCssClasses: "progress-bar-striped active",
				progressBarEmptyPercentage: 2,
				progressBarMinPercentage: 6
            };
            options.common = {
                zxcvbn: true,
				minChar: 8,
				onKeyUp: function (evt, data) {
					$("#length-help-text").text("<?php echo $label_length; ?>: " + $(evt.target).val().length + " - <?php echo $label_score; ?>: " + data.score.toFixed(2));
				},
            };
            $('#password1').pwstrength(options);
        });
</script>
<p class="lead">Change Password for <?php echo $row_brewer['brewerFirstName']." ".$row_brewer['brewerLastName']; ?></p>
<form role="form" name="form1" id="formfield" data-toggle="validator" class="form-horizontal" action="<?php echo $base_url; ?>includes/process.inc.php?section=admin&amp;go=change_user_password&amp;action=edit&amp;dbTable=<?php echo $users_db_table; ?>&amp;id=<?php echo $id; ?>" method="post">
<input type="hidden" name="userEdit" value="1">
<div class="form-group"><!-- Form Group REQUIRED Text Input -->
    <label for="password1" class="col-lg-2 col-md-3 col-sm-3 col-xs-12 control-label">New Password</label>
    <div class="col-lg-6 col-md-6 col-sm-9 col-xs-12">
        <div class="input-group has-warning">
            <!-- Input Here -->
            <span class="input-group-addon" id="password1-addon1"><span class="fa fa-key"></span></span>
            <input class="form-control" name="password1" type="password" placeholder="Password" id="password1" required>
            <span class="input-group-addon" id="password1-addon2"><span class="fa fa-star"></span></span>
        </div>
        <div class="help-block with-errors"></div>
    </div>
</div><!-- ./Form Group -->
<div class="form-group" id="pwd-container">
		<label class="col-lg-2 col-md-3 col-sm-4 col-xs-12 control-label"><?php echo $label_password_strength; ?></label>
		<div class="col-lg-6 col-md-6 col-sm-9 col-xs-12">
			<div class="pwd-strength-viewport-progress"></div>
			<div id="length-help-text" class="small"></div>
		</div>
	</div>
<div class="form-group"><!-- Form Group REQUIRED Text Input -->
    <label for="password2" class="col-lg-2 col-md-3 col-sm-3 col-xs-12 control-label">Confirm Password</label>
    <div class="col-lg-6 col-md-6 col-sm-9 col-xs-12">
        <div class="input-group has-warning">
            <!-- Input Here -->
            <span class="input-group-addon" id="password2-addon1"><span class="fa fa-key"></span></span>
            <input class="form-control" name="password" type="password" placeholder="Confirm" id="password2" data-match="#password1" data-match-error="The passwords do not match" required>
            <span class="input-group-addon" id="password2-addon2"><span class="fa fa-star"></span></span>
        </div>
        <div class="help-block with-errors"></div>
    </div>
</div><!-- ./Form Group -->
<div class="form-group">
    <div class="col-sm-offset-2 col-lg-10 col-md-6 col-sm-9 col-xs-12">
        <!-- Input Here -->
        <!-- For confirm modal to work, must have a type="button" -->
        <button type="button" name="Submit" class="btn btn-primary" data-toggle="modal" data-target="#confirm-submit" >Change User Password</button>
    </div>
</div><!-- Form Group -->
</form>

<!-- Form submit confirmation modal -->
<!-- Refer to bcoem_custom.js for configuration -->
<div class="modal fade" id="confirm-submit" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
            	<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title">Please Confirm</h4>
            </div>
            <div class="modal-body">
                <strong>This will change <?php echo $row_brewer['brewerFirstName']." ".$row_brewer['brewerLastName']; ?>&rsquo;s password.</strong> Do you wish to continue?
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-danger" data-dismiss="modal">Cancel</button>
                <a href="#" id="submit" class="btn btn-success">Yes</a>
            </div>
        </div>
    </div>
</div>
