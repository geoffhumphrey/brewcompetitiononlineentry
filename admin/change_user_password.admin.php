<?php 

// Redirect if directly accessed without authenticated session
if ((!isset($_SESSION['loginUsername'])) || ((isset($_SESSION['loginUsername'])) && ($_SESSION['userLevel'] > 0))) {
    if (function_exists('redirect_or_exit')) redirect_or_exit("../../403.php");
    else { header("Location: ../../403.php"); exit(); }
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
<form role="form" name="form1" class="form-horizontal hide-loader-form-submit needs-validation" action="<?php echo $base_url; ?>includes/process.inc.php?section=admin&amp;go=change_user_password&amp;action=edit&amp;dbTable=<?php echo $users_db_table; ?>&amp;id=<?php echo $id; ?>" method="post" novalidate>
<input type="hidden" name="user_session_token" value ="<?php if (isset($_SESSION['user_session_token'])) echo htmlspecialchars($_SESSION['user_session_token'], ENT_QUOTES, 'UTF-8'); ?>">
<input type="hidden" name="userEdit" value="1">
<div class="row mb-3">
    <label for="password1" class="col-xs-12 col-sm-3 col-lg-2 col-form-label"><i class="fa fa-star me-1"></i>New Password</label>
    <div class="col-xs-12 col-sm-9 col-lg-10">
        <input class="form-control" name="password1" type="password" placeholder="Password" id="password1" required>
        <div class="help-block invalid-feedback text-danger">Password is required.</div>
    </div>
</div>
<div class="row mb-3" id="pwd-container">
		<label class="col-xs-12 col-sm-3 col-lg-2 col-form-label"><?php echo $label_password_strength; ?></label>
		<div class="col-xs-12 col-sm-9 col-lg-10">
			<div class="pwd-strength-viewport-progress"></div>
			<div id="length-help-text" class="small"></div>
		</div>
	</div>
<div class="row mb-3">
    <label for="password2" class="col-xs-12 col-sm-3 col-lg-2 col-form-label"><i class="fa fa-star me-1"></i>Confirm Password</label>
    <div class="col-xs-12 col-sm-9 col-lg-10">
        <input class="form-control" name="password" type="password" placeholder="Confirm" id="password2" required>
        <div class="help-block invalid-feedback text-danger" id="password2-error">The passwords do not match.</div>
    </div>
</div>
<div class="row mb-3">
    <div class="col-xs-12 col-sm-9 col-lg-10 offset-sm-3 offset-lg-2">
        <button type="submit" class="btn btn-primary">Change User Password</button>
    </div>
</div>
</form>
<script>
$(document).ready(function () {
    "use strict";
    function checkPasswordsMatch() {
        var pw1 = document.getElementById("password1");
        var pw2 = document.getElementById("password2");
        if (pw2.value && pw1.value !== pw2.value) {
            pw2.setCustomValidity("The passwords do not match.");
        } else {
            pw2.setCustomValidity("");
        }
    }
    $("#password1, #password2").on("input change", checkPasswordsMatch);
});
</script>