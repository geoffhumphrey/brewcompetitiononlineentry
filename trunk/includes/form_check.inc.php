<script type="text/javascript" language="JavaScript">
<!-- Javascript code copyright 2003 Bontrager Connection, LLC 
// Code obtained from http://WillMaster.com/
<?php if (($section == "step3") || (($section == "admin") && ($go == "preferences"))) { ?>
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
<?php if (($section == "step4") || (($section == "admin") && ($go == "contest_info"))) { ?>
function CheckRequiredFields() {
var errormessage = new String();
// Put field checks below this point.
<?php if ($section == "step4") { ?>
if(WithoutContent(document.form1.contactFirstName.value))
	{ errormessage += "\nThe competition contact's first name"; }
if(WithoutContent(document.form1.contactLastName.value))
	{ errormessage += "\nThe competition contact's last name"; }
if(WithoutContent(document.form1.contactEmail.value))
	{ errormessage += "\nThe competition contact email"; }
<?php } ?>
if(WithoutContent(document.form1.contestName.value))
	{ errormessage += "\nThe competition name"; }
if(WithoutContent(document.form1.contestHost.value))
	{ errormessage += "\nThe competition host"; }
if(WithoutContent(document.form1.contestEntryDeadline.value))
	{ errormessage += "\nThe competition entry deadline"; }
if(WithoutContent(document.form1.contestRegistrationDeadline.value))
	{ errormessage += "\nThe competition registration deadline"; }
if(WithoutContent(document.form1.contestEntryFee.value))
	{ errormessage += "\nThe entry fee - enter a zero for a free entry fee"; }
// Put field checks above this point.
if(errormessage.length > 2) {
	alert('The following information is required:\n' + errormessage);
	return false;
	}
return true;
} // end of function CheckRequiredFields()
<?php } ?>
<?php if (($section == "step5") || (($section == "admin") && ($go == "judging"))) { ?>
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
	alert('The following information is required:\n' + errormessage);
	return false;
	}
return true;
} // end of function CheckRequiredFields()
<?php } ?>
<?php if (($section == "step6") || (($action == "add") && ($go == "dropoff") && ($filter == "default"))) { ?>
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
	alert('The following information is required:\n' + errormessage);
	return false;
	}
return true;
} // end of function CheckRequiredFields()
<?php } ?>
<?php if (($section == "step1") || (($action == "add") && ($go == "participants") && ($filter == "default"))) { ?>
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
	alert('The following information is required:\n' + errormessage);
	return false;
	}
return true;
} // end of function CheckRequiredFields()
<?php } ?>
<?php if ($section == "step2") { ?>
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
	alert('The following information is required:\n' + errormessage);
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
	{ errormessage += "\nStree address"; }
if(WithoutContent(document.form1.brewerCity.value))
	{ errormessage += "\nCity"; }
if(WithoutContent(document.form1.brewerState.value))
	{ errormessage += "\nState or province"; }
if(WithoutContent(document.form1.brewerZip.value))
	{ errormessage += "\nZip or postal code"; }
if(WithoutContent(document.form1.brewerPhone1.value))
	{ errormessage += "\nAt least one phone number"; }
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
<?php if (!NHC) { ?>
if(WithoutContent(document.form1.brewName.value))
	{ errormessage += "\nThe name of the brew"; }
<?php } 
if ((NHC) && ($prefix != "final_")) { ?>
if(WithoutSelectionValue(document.form1.brewStyle))
	{ errormessage += "\nA style from the drop-down list."; }
<?php } ?>
// Put field checks above this point.
if(errormessage.length > 2) {
	alert('To enter your brew, the following information is required:\n' + errormessage <?php if ($_SESSION['prefsHideRecipe'] == "N") { ?>+ '\n\nAlso, to make print-outs of your recipe and bottle labels more complete, you should consider filling out all applicable items.'<?php } ?>);
	return false;
	}
return true;
} // end of function CheckRequiredFields()
<?php } ?>
<?php if (($section == "register") || ($action == "register"))  { ?>
function CheckRequiredFields() {
var errormessage = new String();
// Put field checks below this point.
if(WithoutContent(document.form1.user_name.value))
	{ errormessage += "\nAn email for your user name"; }
if(WithoutContent(document.form1.user_name2.value))
	{ errormessage += "\nRe-entry of your email address"; }
if(WithoutContent(document.form1.password.value))
	{ errormessage += "\nA password"; }
<?php if ($section == "register") { ?>
if(WithoutContent(document.form1.userQuestionAnswer.value))
	{ errormessage += "\nAn answer to your security question"; }
<?php } ?>
if(WithoutContent(document.form1.brewerFirstName.value))
	{ errormessage += "\nFirst name"; }
if(WithoutContent(document.form1.brewerLastName.value))
	{ errormessage += "\nLast name"; }
if(WithoutContent(document.form1.brewerAddress.value))
	{ errormessage += "\nStreet address"; }
if(WithoutContent(document.form1.brewerCity.value))
	{ errormessage += "\nCity"; }
if(WithoutContent(document.form1.brewerState.value))
	{ errormessage += "\nState or province"; }
if(WithoutContent(document.form1.brewerZip.value))
	{ errormessage += "\nZip or postal code"; }
if(WithoutContent(document.form1.brewerPhone1.value))
	{ errormessage += "\nAt least one phone number"; }
<?php if ($section == "register") { ?>
if(WithoutContent(document.form1.captcha_code.value))
	{ errormessage += "\nThe CAPTCHA code"; }
<?php } ?>
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
	alert('To process, the following information is required:\n' + errormessage);
	return false;
	}
return true;
} // end of function CheckRequiredFields()
<?php } ?>


<?php if (($section == "user") && ($action == "username")) { ?>
function CheckRequiredFields() {
var errormessage = new String();
// Put field checks below this point.
if(WithoutContent(document.form1.user_name.value))
	{ errormessage += "\nA valid user name"; }
if(WithoutCheck(document.form1.sure))
	{ errormessage += "\nCheck the Are You Sure? checkbox"; }
// Put field checks above this point.
if(errormessage.length > 2) {
	alert('To process, the following information is required:\n' + errormessage);
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
	alert('To process, the following information is required:\n' + errormessage);
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
	alert('To process, the following information is required:\n' + errormessage);
	return false;
	}
return true;
} // end of function CheckRequiredFields()
<?php } ?>


<?php if (($section == "admin") && ($go == "judging_tables")) { ?>
function CheckRequiredFields() {
var errormessage = new String();
// Put field checks below this point.
if(WithoutContent(document.form1.tableName.value))
	{ errormessage += "\nThe table name"; }
// Put field checks above this point.
if(errormessage.length > 2) {
	alert('To process, the following information is required:\n' + errormessage);
	return false;
	}
return true;
} // end of function CheckRequiredFields()
<?php } ?>

<?php if (($section == "admin") && ($go == "judging_preferences")) { ?>
function CheckRequiredFields() {
var errormessage = new String();
// Put field checks below this point.
if(WithoutContent(document.form1.jPrefsFlightEntries.value))
	{ errormessage += "\nThe Entries per Flight"; }
if(WithoutContent(document.form1.jPrefsRounds.value))
	{ errormessage += "\nThe Maximum Rounds Per Location"; }
if(WithoutContent(document.form1.jPrefsMaxBOS.value))
	{ errormessage += "\nThe Maximum Places in BOS Round"; }// Put field checks above this point.
if(errormessage.length > 2) {
	alert('To process, the following information is required:\n' + errormessage);
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