<script type="text/javascript" language="JavaScript">
<!-- Javascript code copyright 2003 Bontrager Connection, LLC // Code obtained from http://WillMaster.com/
<?php if (($section == "step1") || (($section == "admin") && ($go == "preferences"))) { ?>
function CheckRequiredFields() {
var errormessage = new String();
// Put field checks below this point.
if(WithoutContent(document.form1.prefsWeight1.value))
	{ errormessage += "\nSmall weight measurement"; }
if(WithoutContent(document.form1.prefsWeight2.value))
	{ errormessage += "\nLarge weight measurement"; }
if(WithoutContent(document.form1.prefsLiquid1.value))
	{ errormessage += "\nSmall liquid measurement"; }
if(WithoutContent(document.form1.prefsLiquid2.value))
	{ errormessage += "\nLarge liquid measurement"; }
// Put field checks above this point.
if(errormessage.length > 2) {
	alert('The following information is required to set up your competition information:\n' + errormessage);
	return false;
	}
return true;
} // end of function CheckRequiredFields()
<?php } ?>
<?php if (($section == "step2") || (($section == "admin") && ($go == "contest_info"))) { ?>
function CheckRequiredFields() {
var errormessage = new String();
// Put field checks below this point.
if(WithoutContent(document.form1.contestContactName.value))
	{ errormessage += "\nThe competition contact name"; }
if(WithoutContent(document.form1.contestContactEmail.value))
	{ errormessage += "\nThe competition contact email"; }
if(WithoutContent(document.form1.contestName.value))
	{ errormessage += "\nThe competition name"; }
if(WithoutContent(document.form1.contestHost.value))
	{ errormessage += "\nThe competition host"; }
if(WithoutContent(document.form1.contestEntryDeadline.value))
	{ errormessage += "\nThe competition entry deadline"; }
if(WithoutContent(document.form1.contestRegistrationDeadline.value))
	{ errormessage += "\nThe competition registration deadline"; }
if(WithoutContent(document.form1.contestDate.value))
	{ errormessage += "\nAt least one date of judging"; }
if(WithoutContent(document.form1.contestEntryFee.value))
	{ errormessage += "\nThe entry fee - enter a zero for a free entry fee"; }
if(WithoutContent(document.form1.contestShippingAddress.value))
	{ errormessage += "\nThe shipping address"; }
// Put field checks above this point.
if(errormessage.length > 2) {
	alert('The following information is required to set up your site preferences:\n' + errormessage);
	return false;
	}
return true;
} // end of function CheckRequiredFields()
<?php } ?>
<?php if (($section == "step3") || (($section == "admin") && ($go == "judging"))) { ?>
function CheckRequiredFields() {
var errormessage = new String();
// Put field checks below this point.
if(WithoutContent(document.form1.judgingDate.value))
	{ errormessage += "\nThe date of judging for this location"; }
if(WithoutContent(document.form1.judgingLocName.value))
	{ errormessage += "\nThe name of this judging location"; }
if(WithoutContent(document.form1.judgingTime.value))
	{ errormessage += "\nThe time judging begins at this location"; }
if(WithoutContent(document.form1.judgingLocation.value))
	{ errormessage += "\nThe judging location's address"; }
if(WithoutContent(document.form1.judgingRounds.value))
	{ errormessage += "\nThe number of rounds at this location"; }
// Put field checks above this point.
if(errormessage.length > 2) {
	alert('The following information is required to set up your site preferences:\n' + errormessage);
	return false;
	}
return true;
} // end of function CheckRequiredFields()
<?php } ?>
<?php if (($section == "step4") || (($action == "add") && ($go == "dropoff") && ($filter == "default"))) { ?>
function CheckRequiredFields() {
var errormessage = new String();
// Put field checks below this point.
if(WithoutContent(document.form1.dropLocation.value))
	{ errormessage += "\nThe name of the drop-off location"; }
if(WithoutContent(document.form1.dropLocationPhone.value))
	{ errormessage += "\nThe drop-off location's phone number"; }
if(WithoutContent(document.form1.dropLocation.value))
	{ errormessage += "\nThe drop-off location's full address"; }
	
// Put field checks above this point.// Put field checks above this point.
if(errormessage.length > 2) {
	alert('The following information is required to set up a drop-off location:\n' + errormessage);
	return false;
	}
return true;
} // end of function CheckRequiredFields()
<?php } ?>
<?php if (($section == "step6") || (($action == "add") && ($go == "participants") && ($filter == "default"))) { ?>
function CheckRequiredFields() {
var errormessage = new String();
// Put field checks below this point.
if(WithoutContent(document.form1.user_name.value))
	{ errormessage += "\nAn email as a user name"; }
if(WithoutContent(document.form1.password.value))
	{ errormessage += "\nA password"; }
<?php if ($section == "step6") { ?>
if(WithoutContent(document.form1.userQuestionAnswer.value))
	{ errormessage += "\nAn answer to your security question"; }
<?php } ?>
// Put field checks above this point.// Put field checks above this point.
if(errormessage.length > 2) {
	alert('The following information is required to set up the admin user:\n' + errormessage);
	return false;
	}
return true;
} // end of function CheckRequiredFields()
<?php } ?>
<?php if ($section == "step7") { ?>
function CheckRequiredFields() {
var errormessage = new String();
// Put field checks below this point.
if(WithoutContent(document.form1.brewerFirstName.value))
	{ errormessage += "\nFirst name"; }
if(WithoutContent(document.form1.brewerLastName.value))
	{ errormessage += "\nLast name"; }
if(WithoutContent(document.form1.brewerAddress.value))
	{ errormessage += "\nStreet address"; }
if(WithoutContent(document.form1.brewerCity.value))
	{ errormessage += "\nCity"; }
if(WithoutContent(document.form1.brewerState.value))
	{ errormessage += "\nState, province, or country"; }
if(WithoutContent(document.form1.brewerZip.value))
	{ errormessage += "\nZip code"; }
if(WithoutContent(document.form1.brewerPhone1.value))
	{ errormessage += "\nAt least one phone number"; }
// Put field checks above this point.
if(errormessage.length > 2) {
	alert('The following information is required to set up the admin user information:\n' + errormessage);
	return false;
	}
return true;
} // end of function CheckRequiredFields()
<?php } ?>
<?php if (($section == "brewer") || (($action == "add") && ($go == "participants") && ($filter == "info"))) { ?>
function CheckRequiredFields() {
var errormessage = new String();
// Put field checks below this point.
if(WithoutContent(document.form1.brewerFirstName.value))
	{ errormessage += "\nFirst name"; }
if(WithoutContent(document.form1.brewerLastName.value))
	{ errormessage += "\nLast name"; }
if(WithoutContent(document.form1.brewerAddress.value))
	{ errormessage += "\nAddress"; }
if(WithoutContent(document.form1.brewerCity.value))
	{ errormessage += "\nCityity"; }
if(WithoutContent(document.form1.brewerState.value))
	{ errormessage += "\nState or country"; }
if(WithoutContent(document.form1.brewerZip.value))
	{ errormessage += "\nZip or postal code"; }
if(WithoutContent(document.form1.brewerPhone1.value))
	{ errormessage += "\nA phone number"; }
// Put field checks above this point.
if(errormessage.length > 2) {
	alert('To process, the following information is required:\n' + errormessage);
	return false;
	}
return true;
} // end of function CheckRequiredFields()
<?php } ?>
<?php if ($section == "brew") { ?>
function CheckRequiredFields() {
var errormessage = new String();
// Put field checks below this point.
if(WithoutContent(document.form1.brewName.value))
	{ errormessage += "\nThe name of the brew"; }
if(NoneWithContent(document.form1.brewStyle.value))
	{ errormessage += "\nA style from the drop-down list."; }
// Put field checks above this point.
if(errormessage.length > 2) {
	alert('To enter your brew, the following information is required:\n' + errormessage + '\n\nAlso, to make print-outs of your recipe and bottle labels more complete, you should consider filling out all applicable items.');
	return false;
	}
return true;
} // end of function CheckRequiredFields()
<?php } ?>
<?php if ($section == "register") { ?>
function CheckRequiredFields() {
var errormessage = new String();
// Put field checks below this point.
if(WithoutContent(document.form1.user_name.value))
	{ errormessage += "\nAn email for your user name"; }
if(WithoutContent(document.form1.password.value))
	{ errormessage += "\nA password"; }
if(WithoutContent(document.form1.userQuestionAnswer.value))
	{ errormessage += "\nAn answer to your security question"; }
// Put field checks above this point.
if(errormessage.length > 2) {
	alert('To register, the following information is required:\n' + errormessage);
	return false;
	}
return true;
} // end of function CheckRequiredFields()
<?php } ?>
<?php if ($section == "judge") { ?>
function CheckRequiredFields() {
var errormessage = new String();
// Put field checks below this point.
if(WithoutContent(document.form1.brewerJudgeID.value))
	{ errormessage += "\nYour BJCP ID"; }
// Put field checks above this point.
if(errormessage.length > 2) {
	alert('To process, the following information is required:\n' + errormessage);
	return false;
	}
return true;
} // end of function CheckRequiredFields()
<?php } ?>
<?php if (($section == "user") && ($action == "password")) { ?>
function CheckRequiredFields() {
var errormessage = new String();
// Put field checks below this point.
if(WithoutContent(document.form1.passwordOld.value))
	{ errormessage += "\nOld Password"; }
if(WithoutContent(document.form1.password.value))
	{ errormessage += "\nNew Password"; }
// Put field checks above this point.
if(errormessage.length > 2) {
	alert('To process, the following fields cannot be empty:\n' + errormessage);
	return false;
	}
return true;
} // end of function CheckRequiredFields()
<?php } ?>
<?php if ($section == "contact") { ?>
function CheckRequiredFields() {
var errormessage = new String();
// Put field checks below this point.
if(WithoutContent(document.form1.from_name.value))
	{ errormessage += "\nYour name"; }
if(WithoutContent(document.form1.from_email.value))
	{ errormessage += "\nYour email"; }
if(WithoutContent(document.form1.subject.value))
	{ errormessage += "\nThe subject"; }
if(WithoutContent(document.form1.message.value))
	{ errormessage += "\nYour message"; }
// Put field checks above this point.
if(errormessage.length > 2) {
	alert('The following information is required to contact the competition representative:\n' + errormessage);
	return false;
	}
return true;
} // end of function CheckRequiredFields()
<?php } ?>
<?php if (($section == "admin") && ($go == "styles")) { ?>
function CheckRequiredFields() {
var errormessage = new String();
// Put field checks below this point.
if(WithoutContent(document.form1.brewStyle.value))
	{ errormessage += "\nThe style name"; }
if(WithoutContent(document.form1.brewStyleType.value))
	{ errormessage += "\nThe style type"; }
// Put field checks above this point.
if(errormessage.length > 2) {
	alert('The following information is required to add or edit your custom style:\n' + errormessage);
	return false;
	}
return true;
} // end of function CheckRequiredFields()
<?php } ?>
function WithoutContent(ss) {
if(ss.length > 0) { return false; }
return true;
}

function NoneWithContent(ss) {
for(var i = 0; i < ss.length; i++) {
	if(ss[i].value.length > 0) { return false; }
	}
return true;
}

function NoneWithCheck(ss) {
for(var i = 0; i < ss.length; i++) {
	if(ss[i].checked) { return false; }
	}
return true;
}

function WithoutCheck(ss) {
if(ss.checked) { return false; }
return true;
}

function WithoutSelectionValue(ss) {
for(var i = 0; i < ss.length; i++) {
	if(ss[i].selected) {
		if(ss[i].value.length) { return false; }
		}
	}
return true;
}

//-->
</script>
