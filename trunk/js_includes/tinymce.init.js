	tinyMCE.init({
	mode : "textareas",
	theme : "advanced",
	skin : "o2k7",
	skin_variant : "silver",
	plugins : "simplepaste,advlink",
	theme_advanced_buttons1_add : "pastetext,removeformat,cleanup,separator,code,charmap",
	theme_advanced_disable : "strikethrough,anchor,image,save,styleselect,fontselect,visualaid,help,justifyleft,justifycenter,justifyfull,justifyright",
	theme_advanced_buttons3 : "",
	theme_advanced_toolbar_location : "top",
	theme_advanced_toolbar_align : "left",
	theme_advanced_path_location : "bottom",
	theme_advanced_blockformats : "p,h1,h2,h3,h4",
	gecko_spellcheck : true,
	extended_valid_elements : "a[name|href|target|title|onclick],img[class|src|border=0|alt|title|hspace|vspace|width|height|align|onmouseover|onmouseout|name],hr[class|width|size|noshade],font[face|size|color|style],span[class|align|style]",
	external_link_list_url : "example_data/example_link_list.js",
	external_image_list_url : "example_data/example_image_list.js",
	paste_auto_cleanup_on_paste: "true",
	paste_convert_headers_to_strong:"true",
	paste_remove_spans:"true",
	paste_remove_styles:"true",
	editor_deselector : "mceNoEditor"
	});
	
	function toggleEditor(id) {
		if (!tinyMCE.get(id))
			tinyMCE.execCommand('mceAddControl', false, id);
		else
			tinyMCE.execCommand('mceRemoveControl', false, id);
	}