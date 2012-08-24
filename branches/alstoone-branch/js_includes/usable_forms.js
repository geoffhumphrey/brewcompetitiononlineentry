/*****************************************/
/** Usable Forms 2.0, November 2005     **/
/** Written by ppk, www.quirksmode.org  **/
/** Instructions for use on my site     **/
/**                                     **/
/** You may use or change this script   **/
/** only when this copyright notice     **/
/** is intact.                          **/
/**                                     **/
/** If you extend the script, please    **/
/** add a short description and your    **/
/** name below.                         **/
/*****************************************/

var containerTag = 'TR';

var compatible = (
	document.getElementById && document.getElementsByTagName && document.createElement
	&&
	!(navigator.userAgent.indexOf('MSIE 5') != -1 && navigator.userAgent.indexOf('Mac') != -1)
	);

if (compatible)
{
	document.write('<style>.accessibility{display: none}</style>');
	var waitingRoom = document.createElement('div');
}

var hiddenFormFieldsPointers = new Object();

function prepareForm()
{
	if (!compatible) return;
	var marker = document.createElement(containerTag);
	marker.style.display = 'none';

	var x = document.getElementsByTagName('select');
	for (var i=0;i<x.length;i++)
		addEvent(x[i],'change',showHideFields)

	var x = document.getElementsByTagName(containerTag);
	var hiddenFields = new Array;
	for (var i=0;i<x.length;i++)
	{
		if (x[i].getAttribute('rel'))
		{
			var y = getAllFormFields(x[i]);
			x[i].nestedRels = new Array();
			for (var j=0;j<y.length;j++)
			{
				var rel = y[j].getAttribute('rel');
				if (!rel || rel == 'none') continue;
				x[i].nestedRels.push(rel);
			}
			if (!x[i].nestedRels.length) x[i].nestedRels = null;
			hiddenFields.push(x[i]);
		}
	}

	while (hiddenFields.length)
	{
		var rel = hiddenFields[0].getAttribute('rel');
		if (!hiddenFormFieldsPointers[rel])
			hiddenFormFieldsPointers[rel] = new Array();
		var relIndex = hiddenFormFieldsPointers[rel].length;
		hiddenFormFieldsPointers[rel][relIndex] = hiddenFields[0];
		var newMarker = marker.cloneNode(true);
		newMarker.id = rel + relIndex;
		hiddenFields[0].parentNode.replaceChild(newMarker,hiddenFields[0]);
		waitingRoom.appendChild(hiddenFields.shift());
	}
	
	setDefaults();
	addEvent(document,'click',showHideFields);
}

function setDefaults()
{
	var y = document.getElementsByTagName('input');
	for (var i=0;i<y.length;i++)
	{
		if (y[i].checked && y[i].getAttribute('rel'))
			intoMainForm(y[i].getAttribute('rel'))
	}

	var z = document.getElementsByTagName('select');
	for (var i=0;i<z.length;i++)
	{
		if (z[i].options[z[i].selectedIndex].getAttribute('rel'))
			intoMainForm(z[i].options[z[i].selectedIndex].getAttribute('rel'))
	}

}

function showHideFields(e)
{
	if (!e) var e = window.event;
	var tg = e.target || e.srcElement;

	if (tg.nodeName == 'LABEL')
	{
		var relatedFieldName = tg.getAttribute('for') || tg.getAttribute('htmlFor');
		tg = document.getElementById(relatedFieldName);
	}
		
	if (
		!(tg.nodeName == 'SELECT' && e.type == 'change')
		&&
		!(tg.nodeName == 'INPUT' && tg.getAttribute('rel'))
	   ) return;

	var fieldsToBeInserted = tg.getAttribute('rel');

	if (tg.type == 'checkbox')
	{
		if (tg.checked)
			intoMainForm(fieldsToBeInserted);
		else
			intoWaitingRoom(fieldsToBeInserted);
	}
	else if (tg.type == 'radio')
	{
		removeOthers(tg.form[tg.name],fieldsToBeInserted)
		intoMainForm(fieldsToBeInserted);
	}
	else if (tg.type == 'select-one')
	{
		fieldsToBeInserted = tg.options[tg.selectedIndex].getAttribute('rel');
		removeOthers(tg.options,fieldsToBeInserted);
		intoMainForm(fieldsToBeInserted);
	}
}

function removeOthers(others,fieldsToBeInserted)
{
	for (var i=0;i<others.length;i++)
	{
		var show = others[i].getAttribute('rel');
		if (show == fieldsToBeInserted) continue;
		intoWaitingRoom(show);
	}
}

function intoWaitingRoom(relation)
{
	if (relation == 'none') return;
	var Elements = hiddenFormFieldsPointers[relation];
	for (var i=0;i<Elements.length;i++)
	{
		waitingRoom.appendChild(Elements[i]);
		if (Elements[i].nestedRels)
			for (var j=0;j<Elements[i].nestedRels.length;j++)
				intoWaitingRoom(Elements[i].nestedRels[j]);
	}
}

function intoMainForm(relation)
{
	if (relation == 'none') return;
	var Elements = hiddenFormFieldsPointers[relation];
	for (var i=0;i<Elements.length;i++)
	{
		var insertPoint = document.getElementById(relation+i);
		insertPoint.parentNode.insertBefore(Elements[i],insertPoint);
		if (Elements[i].nestedRels)
		{
			var fields = getAllFormFields(Elements[i]);
			for (var j=0;j<fields.length;j++)
			{
				if (!fields[j].getAttribute('rel')) continue;
				if (fields[j].checked || fields[j].selected) 
					intoMainForm(fields[j].getAttribute('rel'));
			}
		}
	}
}

function getAllFormFields(node)
{
	var allFormFields = new Array;
	var x = node.getElementsByTagName('input');
	for (var i=0;i<x.length;i++)
		allFormFields.push(x[i]);
	var y = node.getElementsByTagName('option');
	for (var i=0;i<y.length;i++)
		allFormFields.push(y[i]);
	return allFormFields;
}

/** ULTRA-SIMPLE EVENT ADDING **/

function addEvent(obj,type,fn)
{
	if (obj.addEventListener)
		obj.addEventListener(type,fn,false);
	else if (obj.attachEvent)
		obj.attachEvent("on"+type,fn);
}

addEvent(window,"load",prepareForm);


/** PUSH AND SHIFT FOR IE5 **/

function Array_push() {
	var A_p = 0
	for (A_p = 0; A_p < arguments.length; A_p++) {
		this[this.length] = arguments[A_p]
	}
	return this.length
}

if (typeof Array.prototype.push == "undefined") {
	Array.prototype.push = Array_push
}

function Array_shift() {
	var A_s = 0
	var response = this[0]
	for (A_s = 0; A_s < this.length-1; A_s++) {
		this[A_s] = this[A_s + 1]
	}
	this.length--
	return response
}

if (typeof Array.prototype.shift == "undefined") {
	Array.prototype.shift = Array_shift
}
