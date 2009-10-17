/**
 * @author Kevin Renskers [DMM Development]
 *
 * The normal paste plugin cleans the content IN the clipboard,
 * but this does not work in Firefox.
 *
 * This plugin does work in Firefox, Safari and IE...
 */

var clipboardHTML;

(function() {
	var Event = tinymce.dom.Event;

	tinymce.create('tinymce.plugins.SimplePastePlugin', {
		init : function(ed, url) {
			var t = this;
			t.editor = ed;

			// Browsers which support the onPaste event can just use that
			// This also makes sure content is cleaned in IE when using the Edit menu.
			// The nice thing is that this is NOT fired on a ctrl-v keypress
			ed.onPaste.add(function(ed, event) {
				t._afterPaste();
			});

			// Listen to ctrl-v, and clean content afterwards. This does not work in Opera en Safari.
			// But Safari has the onPaste event (see above) and Opera cleans everything itself anyway.
			if (!tinymce.isOpera && !tinymce.isWebKit) {
				// Create an hidden tinymce editor with minimal config
				if (!document.getElementById('_TinyMCE_clipboardHTML')) {
					var pasteClipboard = document.createElement('textarea');
					pasteClipboard.id = '_TinyMCE_clipboardHTML';
					document.body.appendChild(pasteClipboard);

					with (pasteClipboard.style) {
						position = 'absolute';
						top = '0px';
						left = '-1000px';
					}

					clipboardHTML = new tinymce.Editor('_TinyMCE_clipboardHTML',
					{
						mode: '_TinyMCE_clipboardHTML',
						theme: 'advanced',
						plugins: '',
						theme_advanced_buttons1: '',
						theme_advanced_buttons2: '',
						theme_advanced_buttons3: '',
						remove_linebreaks: true,
						apply_source_formatting: false,
						theme_advanced_resizing : false,
						forced_root_block: '',
						cleanup: false
					});

					clipboardHTML.init();

					// Hide the editor out of sight
					document.getElementById('_TinyMCE_clipboardHTML_tbl').style.position = 'absolute';
					document.getElementById('_TinyMCE_clipboardHTML_tbl').style.left = '-1000px';
					document.getElementById('_TinyMCE_clipboardHTML_tbl').style.top = '0px';
				}

				// When the user presses ctrl-v or shift-insert the magic happens...
				ed.onKeyDown.add(function(ed, event) {
					if ((navigator.userAgent.match(/mac/i) && event.metaKey && event.charCode == 118) || (event.ctrlKey && event.keyCode == 86) || (event.shiftKey && event.keyCode == 45)) {
						t._beforePaste();
					}
				});
			}
		},

		getInfo: function() {
			return {
				longname : 'Clean html and Word content on paste',
				author : 'Kevin Renskers [DMM Development]',
				authorurl : 'http://www.dauphin-mm.nl',
				infourl : 'http://www.dauphin-mm.nl',
				version : '1.14'
			};
		},

		_beforePaste: function() {
			var t = this;

			// Move the hidden editor to the same level so we don't get weird scrolling effects
			var windowPos = t._getScrollOffsets();
			document.getElementById('_TinyMCE_clipboardHTML_tbl').style.top = windowPos.top+'px';

			// Make a bookmark of our current position
			var bookmark = t.editor.selection.getBookmark(false);

			// Give the hidden extra editor focus, so really we are pasting into that one
			clipboardHTML.focus(false);

			window.setTimeout(function() {
				// Clean the content of the extra editor
				var cleanContent = t._cleanPasteClipboard(clipboardHTML.getContent());

				// Clear the extra editor
				clipboardHTML.setContent('');

				// Insert the cleaned content on the position of our last bookmark
				t.editor.selection.moveToBookmark(bookmark);
				t.editor.execCommand('mceInsertContent', false, cleanContent);

				// Do normal cleanup detached from this thread
				if (t.editor.getParam('paste_force_cleanup_wordpaste', true)) {
					var ed = t.editor;
					window.setTimeout(function() {
						ed.execCommand('mceCleanup');
					}, 1);
				}
			},1);
		},

		_afterPaste: function() {
			var t = this;

			window.setTimeout(function() {
				// Make a bookmark of our current position
				var bookmark = t.editor.selection.getBookmark(false);

				// Clean the content of the entire editor
				var cleanContent = t._cleanPasteClipboard(t.editor.getContent());

				// Replace the content of the editor
				t.editor.execCommand('mceSetContent', false, cleanContent);

				// Do normal cleanup detached from this thread
				if (t.editor.getParam('paste_force_cleanup_wordpaste', true)) {
					var ed = t.editor;
					window.setTimeout(function() {
						ed.execCommand('mceCleanup');
					}, 1);
				}

				// Move back to bookmark
				t.editor.selection.moveToBookmark(bookmark);
			},1);
		},

		_getScrollOffsets: function() {
			return this._returnOffset(
			window.pageXOffset || document.documentElement.scrollLeft || document.body.scrollLeft,
			window.pageYOffset || document.documentElement.scrollTop || document.body.scrollTop);
		},

		_returnOffset: function(l, t) {
			var result = [l, t];
			result.left = l;
			result.top = t;
			return result;
		},

		_cleanPasteClipboard: function(content) {
			var t = this;

			if (content && content.length > 0) {
				// Cleanup Word content
				var bull = String.fromCharCode(8226);
				var middot = String.fromCharCode(183);

				if (t.editor.getParam('paste_insert_word_content_callback'))
					content = ed.execCallback('paste_insert_word_content_callback', 'before', content);

				var rl = t.editor.getParam("paste_replace_list", '\u2122,<sup>TM</sup>,\u2026,...,\u201c|\u201d,",\u2019,\',\u2013|\u2014|\u2015|\u2212,-').split(',');
				for (var i=0; i<rl.length; i+=2)
					content = content.replace(new RegExp(rl[i], 'gi'), rl[i+1]);

				if (t.editor.getParam("paste_convert_headers_to_strong", false)) {
					content = content.replace(new RegExp('<p class=MsoHeading.*?>(.*?)<\/p>', 'gi'), '<p><b>$1</b></p>');
				}

				content = content.replace(new RegExp('tab-stops: list [0-9]+.0pt">', 'gi'), '">' + "--list--");
				content = content.replace(new RegExp(bull + "(.*?)<BR>", "gi"), "<p>" + middot + "$1</p>");
				content = content.replace(new RegExp('<SPAN style="mso-list: Ignore">', 'gi'), "<span>" + bull); // Covert to bull list
				content = content.replace(/<o:p><\/o:p>/gi, "");
				content = content.replace(new RegExp('<br style="page-break-before: always;.*>', 'gi'), '-- page break --'); // Replace pagebreaks
				content = content.replace(new RegExp('<!(?:--[\\s\\S]*?--\s*)?>', 'g'), '');  // Word comments

				if (t.editor.getParam("paste_remove_spans", true))
					content = content.replace(/<\/?span[^>]*>/gi, "");

				if (t.editor.getParam("paste_remove_styles", true))
					content = content.replace(new RegExp('<(\\w[^>]*) style="([^"]*)"([^>]*)', 'gi'), "<$1$3");

				content = content.replace(/<\/?font[^>]*>/gi, "");

				// Strips class attributes.
				switch (t.editor.getParam("paste_strip_class_attributes", "all")) {
					case "all":
						content = content.replace(/<(\w[^>]*) class=([^ |>]*)([^>]*)/gi, "<$1$3");
					break;

					case "mso":
						content = content.replace(new RegExp('<(\\w[^>]*) class="?mso([^ |>]*)([^>]*)', 'gi'), "<$1$3");
					break;
				}

				content = content.replace(new RegExp('href="?' + this._reEscape("" + document.location) + '', 'gi'), 'href="' + t.editor.documentBaseURI.getURI());
				content = content.replace(/<(\w[^>]*) lang=([^ |>]*)([^>]*)/gi, "<$1$3");
				content = content.replace(/<\\?\?xml[^>]*>/gi, "");
				content = content.replace(/<\/?\w+:[^>]*>/gi, "");
				content = content.replace(/-- page break --\s*<p>&nbsp;<\/p>/gi, ""); // Remove pagebreaks
				content = content.replace(/-- page break --/gi, ""); // Remove pagebreaks

				if (!t.editor.getParam('force_p_newlines')) {
					content = content.replace('', '' ,'gi');
					content = content.replace('</p>', '<br /><br />' ,'gi');
				}

				if (!tinymce.isIE && !t.editor.getParam('force_p_newlines')) {
					content = content.replace(/<\/?p[^>]*>/gi, "");
				}

				content = content.replace(/<\/?div[^>]*>/gi, "");

				// Convert all middlot lists to UL lists
				if (t.editor.getParam("paste_convert_middot_lists", true)) {
					var div = t.editor.dom.create("div", null, content);

					// Convert all middot paragraphs to li elements
					var className = t.editor.getParam("paste_unindented_list_class", "unIndentedList");

					while (t._convertMiddots(div, "--list--")) ; // bull
					while (t._convertMiddots(div, middot, className)) ; // Middot
					while (t._convertMiddots(div, bull)) ; // bull

					content = div.innerHTML;
				}

				// Replace all headers with strong and fix some other issues
				if (t.editor.getParam('paste_convert_headers_to_strong', false)) {
					content = content.replace(/<h[1-6]>&nbsp;<\/h[1-6]>/gi, '<p>&nbsp;&nbsp;</p>');
					content = content.replace(/<h[1-6]>/gi, '<p><b>');
					content = content.replace(/<\/h[1-6]>/gi, '</b></p>');
					content = content.replace(/<b>&nbsp;<\/b>/gi, '<b>&nbsp;&nbsp;</b>');
					content = content.replace(/^(&nbsp;)*/gi, '');
				}

				// Get rid of Word comments, --list-- and meta tags
				content = content.replace(/--list--/gi, '');
				content = content.replace(/<(!--)([\s\S]*)(--)>/gi, '');
				content = content.replace(/<\/?meta[\s]*[^>]*>/g, '');
				content = content.replace(/<\/?link[\s]*[^>]*>/g, '');
				content = content.replace(/<\/?o:smart[\s]*[^>]*>/g, '');

				if (t.editor.getParam('paste_insert_word_content_callback')) {
					content = t.editor.execCallback('paste_insert_word_content_callback', 'after', content);
				}

				if (t.editor.getParam('paste_as_plain_text', true)) {
					//Strip all attributes
					content = content.replace(/ [a-z]+="[a-z0-9]*"/gi, '');

					//Strip everything except text
					content = content.replace(/<[a-z]+[^>]*>/gi, '');
				}

				// Condense white space.
				content = content.replace(/\s+/g, ' ');
				content = content.replace(/^\s(.*)/, "$1");
				content = content.replace(/(.*)\s$/, "$1");
				content = content.replace(/&nbsp;/g, '');
				content = content.replace(/<\/p>\s*<p>\s*(?:<br>)?\s*<\/p>\s*<p>/g, '</p><p');

				return content;
			}
		},

		_reEscape: function(s) {
			var l = "?.\\*[](){}+^$:";
			var o = "";

			for (var i=0; i<s.length; i++) {
				var c = s.charAt(i);

				if (l.indexOf(c) != -1)
					o += '\\' + c;
				else
					o += c;
			}

			return o;
		},

		_replace: function(element, content) {
			element = $(element);
			if (content && content.toElement) content = content.toElement();
			else if (!Object.isElement(content)) {
				content = Object.toHTML(content);
				var range = element.ownerDocument.createRange();
				range.selectNode(element);
				content.evalScripts.bind(content).defer();
				content = range.createContextualFragment(content.stripScripts());
			}
			element.parentNode.replaceChild(content, element);
			return element;
		},

		_convertMiddots : function(div, search, class_name) {
			var ed = this.editor, mdot = String.fromCharCode(183), bull = String.fromCharCode(8226);
			var nodes, prevul, i, p, ul, li, np, cp, li;

			nodes = div.getElementsByTagName("p");
			for (i=0; i<nodes.length; i++) {
				p = nodes[i];

				// Is middot
				if (p.innerHTML.indexOf(search) == 0) {
					ul = ed.dom.create("ul");

					if (class_name)
						ul.className = class_name;

					// Add the first one
					li = ed.dom.create("li");
					li.innerHTML = p.innerHTML.replace(new RegExp('' + mdot + '|' + bull + '|--list--|&nbsp;', "gi"), '');
					ul.appendChild(li);

					// Add the rest
					np = p.nextSibling;
					while (np) {
						// If the node is whitespace, then
						// ignore it and continue on.
						if (np.nodeType == 3 && new RegExp('^\\s$', 'm').test(np.nodeValue)) {
								np = np.nextSibling;
								continue;
						}

						if (search == mdot) {
								if (np.nodeType == 1 && new RegExp('^o(\\s+|&nbsp;)').test(np.innerHTML)) {
										// Second level of nesting
										if (!prevul) {
												prevul = ul;
												ul = ed.dom.create("ul");
												prevul.appendChild(ul);
										}
										np.innerHTML = np.innerHTML.replace(/^o/, '');
								} else {
										// Pop the stack if we're going back up to the first level
										if (prevul) {
												ul = prevul;
												prevul = null;
										}
										// Not element or middot paragraph
										if (np.nodeType != 1 || np.innerHTML.indexOf(search) != 0)
												break;
								}
						} else {
								// Not element or middot paragraph
								if (np.nodeType != 1 || np.innerHTML.indexOf(search) != 0)
										break;
							}

						cp = np.nextSibling;
						li = ed.dom.create("li");
						li.innerHTML = np.innerHTML.replace(new RegExp('' + mdot + '|' + bull + '|--list--|&nbsp;', "gi"), '');
						np.parentNode.removeChild(np);
						ul.appendChild(li);
						np = cp;
					}

					p.parentNode.replaceChild(ul, p);

					return true;
				}
			}

			return false;
		}
	});

	// Register plugin
	tinymce.PluginManager.add('simplepaste', tinymce.plugins.SimplePastePlugin);
})();