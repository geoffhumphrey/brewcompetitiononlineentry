	tinyMCE.init({
	mode : "textareas",
	theme : "advanced",
	skin : "o2k7",
	skin_variant : "silver",
	plugins : "autolink,lists,spellchecker,pagebreak,style,layer,table,save,advhr,advimage,advlink,emotions,iespell,inlinepopups,insertdatetime,preview,media,searchreplace,print,contextmenu,paste,directionality,fullscreen,noneditable,visualchars,nonbreaking,xhtmlxtras,template",
	theme_advanced_buttons1 : "save,newdocument,|,bold,italic,underline,strikethrough,justifyleft,justifycenter,justifyright,justifyfull,styleselect,formatselect,fontselect,fontsizeselect",
	theme_advanced_buttons1_add : "pastetext,removeformat,cleanup,|,code,charmap,|,image",
	theme_advanced_buttons2 : "cut,copy,paste,pastetext,pasteword,|,search,replace,|,bullist,numlist,|,outdent,indent,blockquote,|,undo,redo,|,link,unlink,anchor,image,cleanup,help,code,|,insertdate,inserttime,preview",
	theme_advanced_buttons3 : "tablecontrols,|,hr,removeformat,visualaid,|,sub,sup,|,charmap,emotions,iespell,media,advhr,print,ltr,rtl,fullscreen",  
	theme_advanced_disable : "fontsizeselect, strikethrough,anchor,save,styleselect,fontselect,visualaid,help,justifyleft,justifycenter,justifyfull,justifyright,print,ltr,rtl,fullscreen,forecolor,backcolor,hr",
	theme_advanced_toolbar_location : "top",
	theme_advanced_toolbar_align : "left",
	theme_advanced_path_location : "bottom",
	theme_advanced_blockformats : "p,h2,h3,h4",
	gecko_spellcheck : true,
	extended_valid_elements : "a[name|href|target|title|onclick],img[class|src|border=0|alt|title|hspace|vspace|width|height|align|onmouseover|onmouseout|name],hr[class|width|size|noshade],font[face|size|color|style],span[class|align|style]",
	
	external_link_list_url : "js/link_list.js",
	external_image_list_url : "js/image_list.js", 
	
	paste_auto_cleanup_on_paste: "true",
	paste_convert_headers_to_strong:"true",
	paste_remove_spans:"true",
	paste_remove_styles:"true",
	
	template_external_list_url : "js/template_list.js", 
	media_external_list_url : "js/media_list.js",
	
	editor_deselector : "mceNoEditor"
	
	});
	
	function toggleEditor(id) {
		if (!tinyMCE.get(id))
			tinyMCE.execCommand('mceAddControl', false, id);
		else
			tinyMCE.execCommand('mceRemoveControl', false, id);
	}