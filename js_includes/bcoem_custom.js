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

tinymce.init({
	selector: "textarea",
	menubar: false,
	plugins: [
    "advlist autolink autosave link image lists charmap preview hr anchor pagebreak spellchecker",
    "searchreplace wordcount visualblocks visualchars code insertdatetime media nonbreaking",
    "paste"
  ],
	toolbar: "cut copy paste | undo redo | bold italic underline bullist numlist outdent indent | link unlink | code charmap"
});


// -------------------------------- Jump Menu ------------------------------------------------
function jumpMenu(targ,selObj,restore){ //v3.0
	  eval(targ+".location='"+selObj.options[selObj.selectedIndex].value+"'");
	  if (restore) selObj.selectedIndex=0;
}

function jumpMenuThickbox(targ,selObj,restore){ //v3.0
 	eval(targ+".location='"+selObj.options[selObj.selectedIndex].value+"'");
	if (restore) selObj.selectedIndex=0;
}


// -------------------------------- Smooth Scroll --------------------------------------------
// http://www.kryogenix.org/ v1.1 2005-06-16
var ss = {
  fixAllLinks: function() {
    // Get a list of all links in the page
    var allLinks = document.getElementsByTagName('a');
    // Walk through the list
    for (var i=0;i<allLinks.length;i++) {
      var lnk = allLinks[i];
      if ((lnk.href && lnk.href.indexOf('#') != -1) && 
          ( (lnk.pathname == location.pathname) ||
	    ('/'+lnk.pathname == location.pathname) ) && 
          (lnk.search == location.search)) {
        // If the link is internal to the page (begins in #)
        // then attach the smoothScroll function as an onclick
        // event handler
        ss.addEvent(lnk,'click',ss.smoothScroll);
      }
    }
  },
  smoothScroll: function(e) {
    // This is an event handler; get the clicked on element,
    // in a cross-browser fashion
    if (window.event) {
      target = window.event.srcElement;
    } else if (e) {
      target = e.target;
    } else return;
    // Make sure that the target is an element, not a text node
    // within an element
    if (target.nodeName.toLowerCase() != 'a') {
      target = target.parentNode;
    }
  
    // Paranoia; check this is an A tag
    if (target.nodeName.toLowerCase() != 'a') return;
  
    // Find the <a name> tag corresponding to this href
    // First strip off the hash (first character)
    anchor = target.hash.substr(1);
    // Now loop all A tags until we find one with that name
    var allLinks = document.getElementsByTagName('a');
    var destinationLink = null;
    for (var i=0;i<allLinks.length;i++) {
      var lnk = allLinks[i];
      if (lnk.name && (lnk.name == anchor)) {
        destinationLink = lnk;
        break;
      }
    }
  
    // If we didn't find a destination, give up and let the browser do
    // its thing
    if (!destinationLink) return true;
  
    // Find the destination's position
    var destx = destinationLink.offsetLeft; 
    var desty = destinationLink.offsetTop;
    var thisNode = destinationLink;
    while (thisNode.offsetParent && 
          (thisNode.offsetParent != document.body)) {
      thisNode = thisNode.offsetParent;
      destx += thisNode.offsetLeft;
      desty += thisNode.offsetTop;
    }
  
    // Stop any current scrolling
    clearInterval(ss.INTERVAL);
  
    cypos = ss.getCurrentYPos();
  
    ss_stepsize = parseInt((desty-cypos)/ss.STEPS);
    ss.INTERVAL =
setInterval('ss.scrollWindow('+ss_stepsize+','+desty+',"'+anchor+'")',10);
  
    // And stop the actual click happening
    if (window.event) {
      window.event.cancelBubble = true;
      window.event.returnValue = false;
    }
    if (e && e.preventDefault && e.stopPropagation) {
      e.preventDefault();
      e.stopPropagation();
    }
  },
  scrollWindow: function(scramount,dest,anchor) {
    wascypos = ss.getCurrentYPos();
    isAbove = (wascypos < dest);
    window.scrollTo(0,wascypos + scramount);
    iscypos = ss.getCurrentYPos();
    isAboveNow = (iscypos < dest);
    if ((isAbove != isAboveNow) || (wascypos == iscypos)) {
      // if we've just scrolled past the destination, or
      // we haven't moved from the last scroll (i.e., we're at the
      // bottom of the page) then scroll exactly to the link
      window.scrollTo(0,dest);
      // cancel the repeating timer
      clearInterval(ss.INTERVAL);
      // and jump to the link directly so the URL's right
      location.hash = anchor;
    }
  },
  getCurrentYPos: function() {
    if (document.body && document.body.scrollTop)
      return document.body.scrollTop;
    if (document.documentElement && document.documentElement.scrollTop)
      return document.documentElement.scrollTop;
    if (window.pageYOffset)
      return window.pageYOffset;
    return 0;
  },
  addEvent: function(elm, evType, fn, useCapture) {
    // addEvent and removeEvent
    // cross-browser event handling for IE5+,  NS6 and Mozilla
    // By Scott Andrew
    if (elm.addEventListener){
      elm.addEventListener(evType, fn, useCapture);
      return true;
    } else if (elm.attachEvent){
      var r = elm.attachEvent("on"+evType, fn);
      return r;
    } else {
      alert("Handler could not be removed");
    }
  } 
}
ss.STEPS = 25;
ss.addEvent(window,"load",ss.fixAllLinks);

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

// ------------------------------ Bootstrap Date/Time Picker ---------------------------------
// Configuration info at http://eonasdan.github.io/bootstrap-datetimepicker/
// JavaScript Document
$(function () {
	$('#contestEntryOpen').datetimepicker({
		format: 'YYYY-MM-DD hh:mm A'
	});
	
	$('#contestEntryDeadline').datetimepicker({
		format: 'YYYY-MM-DD hh:mm A'
	});
	
	$('#contestRegistrationOpen').datetimepicker({
		format: 'YYYY-MM-DD hh:mm A'
	});
	
	$('#contestRegistrationDeadline').datetimepicker({
		format: 'YYYY-MM-DD hh:mm A'
	});
	
	$('#contestJudgeOpen').datetimepicker({
		format: 'YYYY-MM-DD hh:mm A'
	});
	
	$('#contestJudgeDeadline').datetimepicker({
		format: 'YYYY-MM-DD hh:mm A'
	});
	
	$('#contestShippingOpen').datetimepicker({
		format: 'YYYY-MM-DD hh:mm A'
	});
	
	$('#contestShippingDeadline').datetimepicker({
		format: 'YYYY-MM-DD hh:mm A'
	});
	
	$('#contestDropoffOpen').datetimepicker({
		format: 'YYYY-MM-DD hh:mm A'
	});
	
	$('#contestDropoffDeadline').datetimepicker({
		format: 'YYYY-MM-DD hh:mm A'
	});
	
	$('#contestAwardsLocDate').datetimepicker({
		format: 'YYYY-MM-DD hh:mm A'
	});
	
	$('#prefsWinnerDelay').datetimepicker({
		format: 'YYYY-MM-DD hh:mm A'
	});
	
	$('#judgingDate').datetimepicker({
		format: 'YYYY-MM-DD hh:mm A'
	});
	
	$('#brewDate').datetimepicker({
		format: 'YYYY-MM-DD'
	});
	
	$('#brewBottleDate').datetimepicker({
		format: 'YYYY-MM-DD'
	});
	
});