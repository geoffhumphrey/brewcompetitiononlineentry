// JavaScript Document
// Minify at http://jscompress.com/

// -------------------------------- Enable Bootsrap Tooltips -------------------------------- 
$(function () {
  $('[data-toggle="tooltip"]').tooltip()
});

// Enable for modal links too
$(function() {
	$('[data-tooltip="true"]').tooltip();
});

$(document).ready(function() {
	$('.my-dropdown').dropdown();
	$('.my-dropdown').tooltip();
});

$(document).ready(function() {
	$('#admin-arrow').click(function(){
		$(this).find('i').toggleClass("fa-chevron-circle-left fa-chevron-circle-right");
		$(this).find('i').toggleClass("text-teal text-orange");
	});
});

// -------------------------------- Enable Bootstrap Popovers -------------------------------
$(function () {
  $('[data-toggle="popover"]').popover()
});

// -------------------------------- Define Global Fancybox Parameters -----------------------
$(document).ready(function() {
	$("#modal_window_link").fancybox({
		'width'				: '85%',
		'maxHeight'			: '85%',
		'fitToView'			: false,
		'scrolling'         : 'auto',
		'openEffect'		: 'elastic',
		'closeEffect'		: 'elastic',
		'openEasing'     	: 'easeOutBack',
		'closeEasing'   	: 'easeInBack',
		'openSpeed'         : 'normal',
		'closeSpeed'        : 'normal',
		'type'				: 'iframe',
		'helpers' 			: {	title : { type : 'inside' } }
	});

});

// -------------------------------- Delete with Confirmation --------------------------------
// Use Bootstrap modal for confirmation
// Possibly replace with http://bootboxjs.com or https://ethaizone.github.io/Bootstrap-Confirmation
$(document).ready(function() {
	$('a[data-confirm]').click(function(ev) {
		var href = $(this).attr('href');
		if (!$('#dataConfirmModal').length) {
			$('body').append('<div class="modal fade" id="dataConfirmModal" aria-labelledby="dataConfirmLabel"><div class="modal-dialog"><div class="modal-content"><div class="modal-header"><button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button><h4 id="dataConfirmLabel" class="modal-title">Please Confirm</h4></div><div class="modal-body"></div><div class="modal-footer"><button type="button" class="btn btn-danger" data-dismiss="modal">Cancel</button><a class="btn btn-success" id="dataConfirmOK">Yes</a></div></div></div></div>');
		} 
		$('#dataConfirmModal').find('.modal-body').text($(this).attr('data-confirm'));
		$('#dataConfirmOK').attr('href', href);
		$('#dataConfirmModal').modal({show:true});
		return false;
	});
});


// -------------------------------- Form Submit with Confirmation --------------------------------
// Use Bootstrap modal for confirmation
$(window).load(function(){
	$('#submitBtn').click(function() {
		// Customize below for modal dialog information from forms
    	$('#archiveName').html($('#archiveSuffix').val()); // For archive form
	});

	$('#submit').click(function(){
		$('#formfield').submit();
	});
}); 


// -------------------------------- DEPRECATED Delete With Confirmation ------------------------------------

// DEPRECATED - including here just in case
function DelWithCon(deletepage_url,field_name,field_value,messagetext) { //v1.0 - Deletes a record with confirmation
  if (confirm(messagetext)==1){
  	location.href = eval('\"'+deletepage_url+'&'+field_name+'='+field_value+'\"');
  }
}


// -------------------------------- TinyMCE Configuration ------------------------------------

// Moved to tinymce-init.js


// -------------------------------- Jump Menu ------------------------------------------------
function jumpMenu(targ,selObj,restore){ //v3.0
	  eval(targ+".location='"+selObj.options[selObj.selectedIndex].value+"'");
	  if (restore) selObj.selectedIndex=0;
}

function jumpMenuThickbox(targ,selObj,restore){ //v3.0
 	eval(targ+".location='"+selObj.options[selObj.selectedIndex].value+"'");
	if (restore) selObj.selectedIndex=0;
}

// -------------------------------- Email Validation --------------------------------
function AjaxFunction(email) {
	var httpxml;
		try {
		// Firefox, Opera 8.0+, Safari
		httpxml=new XMLHttpRequest();
		}
	catch (e) {
			// Internet Explorer
			try	{
			httpxml=new ActiveXObject("Msxml2.XMLHTTP");
			}
	catch (e) {
		try 	{
		httpxml=new ActiveXObject("Microsoft.XMLHTTP");
		}
		catch (e) {
		//alert("Your browser does not support AJAX!");
		return false;
		}
	}
}
function stateck() {
	if(httpxml.readyState==4) {
		document.getElementById("msg_email").innerHTML=httpxml.responseText;
		}
	}
	var url="/includes/email.inc.php";
	url=url+"?email="+email;
	url=url+"&sid="+Math.random();
	httpxml.onreadystatechange=stateck;
	httpxml.open("GET",url,true);
	httpxml.send(null);
}
