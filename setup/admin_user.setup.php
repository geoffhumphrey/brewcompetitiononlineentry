<script type="text/javascript">
var username_url = "<?php echo $base_url; ?>ajax/username.ajax.php";
var email_url="<?php echo $base_url; ?>ajax/valid_email.ajax.php";
var setup = 1;
</script>
<script src="<?php echo $base_url; ?>js_includes/registration_checks.min.js"></script>
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
            $('#password').pwstrength(options);
        });
</script>
<?php
$security_questions_display = (array_rand($security_question, 10));
$security = "";
foreach ($security_questions_display as $key => $value) {
	$security .= "<div class=\"radio\"><label><input type=\"radio\" name=\"userQuestion\" value=\"".$security_question[$value]."\" required> ".$security_question[$value]."</label></div>";
}

?>
<p class="lead">This will be the Administrator's account with full access to <em>all</em> of the installation's features and functions.</p>
<p class="lead"><small>The owner of this account will be able to add, edit, and delete any entry and participant, grant administration privileges to other users, define custom styles, define tables and flights, add scores, print reports, etc. This user will also be able to add, edit, and delete their own entries into the competition.</small></p>
<form class="form-horizontal" data-toggle="validator" action="<?php echo $base_url; ?>includes/process.inc.php?section=<?php if ($section == "step1") echo "setup"; else echo $section; ?>&amp;action=add&amp;dbTable=<?php echo $users_db_table; ?>" method="POST" name="form1" id="form1">
<input type="hidden" name="token" value ="<?php if (isset($_SESSION['token'])) echo $_SESSION['token']; ?>">
<input name="userLevel" type="hidden" value="0" />
<div class="form-group"><!-- Form Group REQUIRED Text Input -->
		<label for="" class="col-lg-2 col-md-3 col-sm-4 col-xs-12 control-label">Email Address</label>
		<div class="col-lg-6 col-md-6 col-sm-8 col-xs-12">
			<div class="input-group has-warning">
				<span class="input-group-addon" id="email-addon1"><span class="fa fa-envelope"></span></span>
				<!-- Input Here -->
				<input class="form-control" name="user_name" id="user_name" type="email" placeholder="Your email address is your user name" onchange="AjaxFunction(this.value);" value="<?php if ((isset($_COOKIE['user_name'])) && ($msg > 0)) echo $_COOKIE['user_name']; ?>" required>
				<span class="input-group-addon" id="email-addon2"><span class="fa fa-star"></span>
			</div>
			<div id="msg_email" class="help-block"></div>
			<div id="username-status"></div>
		</div>
	</div><!-- ./Form Group -->

	<div class="form-group"><!-- Form Group REQUIRED Text Input -->
		<label for="" class="col-lg-2 col-md-3 col-sm-4 col-xs-12 control-label">Password</label>
		<div class="col-lg-6 col-md-6 col-sm-8 col-xs-12">
			<div class="input-group has-warning">
				<span class="input-group-addon" id="password-addon1"><span class="fa fa-key"></span></span>
				<!-- Input Here -->
				<input class="form-control" name="password" id="password" type="password" placeholder="Password" value="<?php if ((isset($_COOKIE['password'])) && ($msg > 0)) echo $_COOKIE['password']; ?>" required>
				<span class="input-group-addon" id="password-addon2"><span class="fa fa-star"></span>
			</div>
		</div>
	</div><!-- ./Form Group -->

	<div class="form-group" id="pwd-container">
		<label class="col-lg-2 col-md-3 col-sm-4 col-xs-12 control-label"><?php echo $label_password_strength; ?></label>
		<div class="col-lg-6 col-md-9 col-sm-8 col-xs-12">
			<div class="pwd-strength-viewport-progress"></div>
			<div id="length-help-text" class="small"></div>
		</div>
	</div>

	<div class="form-group"><!-- Form Group REQUIRED Radio Group -->
		<label for="" class="col-lg-2 col-md-3 col-sm-4 col-xs-12 control-label"><?php echo $label_security_question; ?></label>
		<div class="col-lg-6 col-md-6 col-sm-8 col-xs-12">
			<div class="input-group">
            	<?php echo $security; ?>
            </div>
		</div>
	</div><!-- ./Form Group -->

	<div class="form-group"><!-- Form Group REQUIRED Text Input -->
		<label for="" class="col-lg-2 col-md-3 col-sm-4 col-xs-12 control-label">Security Question Answer</label>
		<div class="col-lg-6 col-md-6 col-sm-8 col-xs-12">
			<div class="input-group has-warning">
				<span class="input-group-addon" id="security-question-answer-addon1"><span class="fa fa-bullhorn"></span></span>
				<!-- Input Here -->
				<input class="form-control" name="userQuestionAnswer" id="userQuestionAnswer" type="text" placeholder="" value="<?php if ((isset($_COOKIE['userQuestionAnswer'])) && ($msg > 0)) echo $_COOKIE['userQuestionAnswer']; ?>" required>
				<span class="input-group-addon" id="security-question-answer-addon2"><span class="fa fa-star"></span>
			</div>
		</div>
	</div><!-- ./Form Group -->

	<div class="form-group">
		<div class="col-lg-offset-2 col-md-offset-3 col-sm-offset-4">
			<!-- Input Here -->
			<button name="submit" type="submit" class="btn btn-primary" >Register Admin User</button>
		</div>
	</div><!-- Form Group -->
</form>