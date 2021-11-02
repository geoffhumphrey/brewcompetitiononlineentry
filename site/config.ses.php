<?php
if (ENABLE_SES && !ENABLE_MAILER){
	$mail_use_ses = TRUE;
	$mail_use_smtp = FALSE;
}else{
	$mail_use_ses = FALSE;
}

//Set this to the Amazon AWS region to use for sending.
$ses_region = "";
//Key for the IAM user that can send SES.
$ses_key = "";
//Secret code for IAM user that can send SES.
$ses_secret = "";
