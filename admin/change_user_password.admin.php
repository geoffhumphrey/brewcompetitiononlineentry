<?php 

// Redirect if directly accessed without authenticated session
if ((!isset($_SESSION['loginUsername'])) || ((isset($_SESSION['loginUsername'])) && ($_SESSION['userLevel'] > 0))) {
    $redirect = "../../403.php";
    $redirect_go_to = sprintf("Location: %s", $redirect);
    header($redirect_go_to);
    exit();
}

include (DB.'brewer.db.php'); 

?>
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
<form role="form" name="form1" data-toggle="validator" class="form-horizontal" action="<?php echo $base_url; ?>includes/process.inc.php?section=admin&amp;go=change_user_password&amp;action=edit&amp;dbTable=<?php echo $users_db_table; ?>&amp;id=<?php echo $id; ?>" method="post">
<input type="hidden" name="token" value ="<?php if (isset($_SESSION['token'])) echo $_SESSION['token']; ?>">
<input type="hidden" name="userEdit" value="1">
<div class="form-group"><!-- Form Group REQUIRED Text Input -->
    <label for="password1" class="col-lg-2 col-md-3 col-sm-3 col-xs-12 control-label">New Password</label>
    <div class="col-lg-6 col-md-6 col-sm-9 col-xs-12">
        <div class="input-group has-warning">
            <!-- Input Here -->
            <span class="input-group-addon" id="password1-addon1"><span class="fa fa-key"></span></span>
            <input class="form-control" name="password1" type="password" placeholder="Password" id="password1" required>
            <span class="input-group-addon" id="password1-addon2" data-tooltip="true" title="<?php echo $form_required_fields_02; ?>"><span class="fa fa-star"></span></span>
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
            <span class="input-group-addon" id="password2-addon2" data-tooltip="true" title="<?php echo $form_required_fields_02; ?>"><span class="fa fa-star"></span></span>
        </div>
        <div class="help-block with-errors"></div>
    </div>
</div><!-- ./Form Group -->
<div class="form-group">
    <div class="col-sm-offset-2 col-lg-10 col-md-6 col-sm-9 col-xs-12">
        <!-- Input Here -->
        <!-- For confirm modal to work, must have a type="button" -->
        <button type="submit" class="btn btn-primary">Change User Password</button>
    </div>
</div><!-- Form Group -->
</form>