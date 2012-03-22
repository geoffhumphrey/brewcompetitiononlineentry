// JavaScript Document
<!--
function jumpMenu(targ,selObj,restore){ //v3.0
  eval(targ+".location='"+selObj.options[selObj.selectedIndex].value+"'");
  if (restore) selObj.selectedIndex=0;
}
function jumpMenuThickbox(targ,selObj,restore){ //v3.0
  eval(targ+".location='"+selObj.options[selObj.selectedIndex].value+"&amp;TB_iframe=true&amp;height=450&amp;width=750&amp;KeepThis=true'");
  if (restore) selObj.selectedIndex=0;
}
//-->