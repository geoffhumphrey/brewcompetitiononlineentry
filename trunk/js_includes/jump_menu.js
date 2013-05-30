// JavaScript Document

<!--

function jumpMenu(targ,selObj,restore){ //v3.0

  eval(targ+".location='"+selObj.options[selObj.selectedIndex].value+"'");

  if (restore) selObj.selectedIndex=0;

}

function jumpMenuThickbox(targ,selObj,restore){ //v3.0

  eval(targ+".location='"+selObj.options[selObj.selectedIndex].value+"'");

  if (restore) selObj.selectedIndex=0;

}

//-->

// JS Popup to replace FancyBox for printing of bottle labels
var win;
function popUp(w,h,page)
{
   //get the width and height of the window
    var wide = w;
    var high = h;
    
    //this gets the total available width and height of the user's screen
    screen_height = window.screen.availHeight;
    screen_width = window.screen.availWidth; 

    //this gets the left and top point by saying total width of the screen
	//divided by 2 (center), minus the width of your window divided by 2,
	//which make its center point the middle of the screen. Same for the top
    left_point = parseInt(screen_width/2)-(wide/2); 
    top_point = parseInt(screen_height/2)-(high/2); 

    //just doing a simple popup window here, but plugging your info into it,
	//and setting the top and left corners to be our calculated points that 
	//will center your window.
    win = window.open(page, 'win', 'width='+wide+',height='+high+',left='+left_point+',top='+top_point+',toolbar=no,location=no,scrollbars=yes,status=no,resizable=yes,fullscreen=no');     //make sure your window is in the front 
    win.focus(); 
}