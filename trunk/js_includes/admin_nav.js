function Go(){return}
Menu1=new Array("Admin","index.php?section=admin","",4,20,200);
  	Menu1_1=new Array("Edit","","",2,20,100);
   		Menu1_1_1=new Array("Contest Info","index.php?section=admin&go=contest_info","",0,20,120);
  		Menu1_1_2=new Array("Preferences","index.php?section=admin&go=preferences","",0,20,200);
  	Menu1_2=new Array("Upload","","",2,20,100);
   		Menu1_2_1=new Array("Competition Logo","admin/upload.admin.php?KeepThis=true&TB_iframe=true&height=350&width=800","",0,20,120);
   		Menu1_2_2=new Array("Sponsor Logo","admin/upload.admin.php?KeepThis=true&TB_iframe=true&height=350&width=800","",0,20,0);
  	Menu1_3=new Array("Export","","",11,20,100);
   		Menu1_3_1=new Array("Promo Materials (HTML)","admin/promo_export.admin.php?action=html","",0,20,225);
  		Menu1_3_2=new Array("Promo Materials (Word)","admin/promo_export.admin.php?action=word","",0,20,225);
   		Menu1_3_3=new Array("All Participant Email Addresses (CSV)","admin/email_export","",0,20,225);
   		Menu1_3_4=new Array("All Judge Email Addresses (CSV)","admin/email_export.php?filter=judges","",0,20,225);
   		Menu1_3_5=new Array("All Steward Email Addresses (CSV)","admin/email_export.php?filter=stewards","",0,20,225);
   		Menu1_3_6=new Array("All Participants (Tab)","admin/participants_export.php?go=tab","",0,20,225);
   		Menu1_3_7=new Array("All Paid & Recieved Entries (Tab)","admin/entries_export.php?go=tab&filter=paid","",0,20,225);
   		Menu1_3_8=new Array("All Entries (Tab)","admin/entries_export.php?go=tab","",0,20,225);
   		Menu1_3_9=new Array("All Participants (CSV)","admin/participants_export.php?go=csv","",0,20,225);
   		Menu1_3_10=new Array("All Paid & Recieved Entries (CSV)","admin/entries_export.php?go=csv&filter=paid","",0,20,225);
   		Menu1_3_11=new Array("All Entries (CSV)","admin/entries_export.php?go=csv","",0,20,225);
  		Menu1_4=new Array("Maintenance","","",1,20,100);
   		Menu1_4_1=new Array("Archives","index.php?section=admin&go=archive","",0,20,120);


var NoOffFirstLineMenus=1;	// Number of first level items
var LowBgColor='#FFFFFF';		// Background color when mouse is not over
var LowSubBgColor='#EEEEEE';	// Background color when mouse is not over on subs
var HighBgColor='#FFFFFF';	// Background color when mouse is over
var HighSubBgColor='#AAAAAA';	// Background color when mouse is over on subs
var FontLowColor='#990000';	// Font color when mouse is not over
var FontSubLowColor='#990000';	// Font color subs when mouse is not over
var FontHighColor='#990000';	// Font color when mouse is over
var FontSubHighColor='#333333';	// Font color subs when mouse is over
var BorderColor='#FFFFFF';	// Border color
var BorderSubColor='#000000';	// Border color for subs
var BorderWidth=1;		// Border width
var BorderBtwnElmnts=1;		// Border between elements 1 or 0
var FontFamily="Verdana"	        // Font family menu items
var FontSize=8;			// Font size menu items
var FontBold=0;			// Bold menu items 1 or 0
var FontItalic=0;		// Italic menu items 1 or 0
var MenuTextCentered='left';	// Item text position 'left', 'center' or 'right'
var MenuCentered='left';	// Menu horizontal position 'left', 'center' or 'right'
var MenuVerticalCentered='top';	// Menu vertical position 'top', 'middle','bottom' or static
var ChildOverlap=.2;		// horizontal overlap child/ parent
var ChildVerticalOverlap=.2;	// vertical overlap child/ parent
var StartTop=1;		// Menu offset x coordinate
var StartLeft=1;		// Menu offset y coordinate
var VerCorrect=0;		// Multiple frames y correction
var HorCorrect=0;		// Multiple frames x correction
var LeftPaddng=3;		// Left padding
var TopPaddng=2;		// Top padding
var FirstLineHorizontal=1;	// SET TO 1 FOR HORIZONTAL MENU, 0 FOR VERTICAL
var MenuFramesVertical=1;	// Frames in cols or rows 1 or 0
var DissapearDelay=1000;	// delay before menu folds in
var TakeOverBgColor=1;		// Menu frame takes over background color subitem frame
var FirstLineFrame='navig';	// Frame where first level appears
var SecLineFrame='space';	// Frame where sub levels appear
var DocTargetFrame='space';	// Frame where target documents appear
var TargetLoc='';		// span id for relative positioning
var HideTop=0;			// Hide first level when loading new document 1 or 0
var MenuWrap=1;			// enables/ disables menu wrap 1 or 0
var RightToLeft=0;		// enables/ disables right to left unfold 1 or 0
var UnfoldsOnClick=0;		// Level 1 unfolds onclick/ onmouseover
var WebMasterCheck=0;		// menu tree checking on or off 1 or 0
var ShowArrow=0;		// Uses arrow gifs when 1
var KeepHilite=1;		// Keep selected path highligthed
var Arrws=['tri.gif',5,10,'tridown.gif',10,5,'trileft.gif',5,10];	// Arrow source, width and height

