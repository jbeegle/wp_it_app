F = 2;var cs;var a = new Array();var rated = false;function check_isnum_or_minus(e) {  var chCode1 = e.which || e.keyCode;  if (chCode1 != 45 ) {    if (chCode1 > 31 && (chCode1 < 48 || chCode1 > 57)) {      return false;    }  }  return true;}function show_other_input(num, form_id) {	for (k = 0; k < 50; k++) {		if (document.getElementById(num+"_element"+form_id+k)) {			if (document.getElementById(num+"_element"+form_id+k).getAttribute('other')) {				if (document.getElementById(num+"_element"+form_id+k).getAttribute('other') == 1) {					element_other = document.getElementById(num+"_element"+form_id+k);					break;				}      }    }  }	parent_ = element_other.parentNode;	var br = document.createElement('br');  br.setAttribute("id", num+"_other_br"+form_id);	var el_other = document.createElement('input');  el_other.setAttribute("id", num+"_other_input"+form_id);  el_other.setAttribute("name", num+"_other_input"+form_id);  el_other.setAttribute("type", "text");  el_other.setAttribute("class", "other_input");	parent_.appendChild(br);	parent_.appendChild(el_other);}function check_isnum(e) {  var chCode1 = e.which || e.keyCode;  if (jQuery.inArray(chCode1,[46,8,9,27,13,190]) != -1 || e.ctrlKey === true || (chCode1 >= 35 && chCode1 < 39)) {    return true;  }  if (chCode1 > 31 && (chCode1 < 48 || chCode1 > 57)) {    return false;  }	return true;}function captcha_refresh(id, genid) {  if (document.getElementById(id+genid)) {    srcArr = document.getElementById(id+genid).src.split("&r=");    document.getElementById(id+genid).src = srcArr[0]+'&r='+Math.floor(Math.random()*100);    document.getElementById(id+"_input"+genid).value = '';    document.getElementById(id+genid).style.display = "inline-block";  }}function set_checked(id, j, form_id) {	checking = document.getElementById(id+"_element"+form_id+j);	if (checking.getAttribute('other')) {		if(checking.getAttribute('other')==1) {			if (!checking.checked) {				if (document.getElementById(id+"_other_input"+form_id)) {					document.getElementById(id+"_other_input"+form_id).parentNode.removeChild(document.getElementById(id+"_other_br"+form_id));					document.getElementById(id+"_other_input"+form_id).parentNode.removeChild(document.getElementById(id+"_other_input"+form_id));				}				return false;			}    }  }	return true;}function set_default(id, j, form_id) {  if (document.getElementById(id+"_other_input"+form_id)) {    document.getElementById(id+"_other_input"+form_id).parentNode.removeChild(document.getElementById(id+"_other_br"+form_id));    document.getElementById(id+"_other_input"+form_id).parentNode.removeChild(document.getElementById(id+"_other_input"+form_id));  }}function check_isnum_interval(e, x, from, to) {  var chCode1 = e.which || e.keyCode;  if (jQuery.inArray(chCode1,[46,8,9,27,13,190]) != -1 || e.ctrlKey === true || (chCode1 >= 35 && chCode1 < 39)) {    return true;  }  if (chCode1 > 31 && (chCode1 < 48 || chCode1 > 57)) {    return false;  }	val1=""+jQuery(x).val()+String.fromCharCode(chCode1);	if (val1.length>2)    return false;	if (val1=='00')    return false;	if ((val1<from) || (val1>to))    return false;	return true;}function delete_value(x) {  ofontStyle = jQuery(x).prop('class');	if (ofontStyle.indexOf("input_deactive") != -1) {    jQuery(x).val("").removeClass("input_deactive").addClass("input_active");  }}function return_value(x) {  if (jQuery(x).val() == "") {    jQuery(x).val(jQuery(x).prop('title')).removeClass("input_active").addClass("input_deactive");  }}function destroyChildren(node) {  while (node.firstChild) {    node.removeChild(node.firstChild);  }}function remove_whitespace(node) {  var ttt;	for (ttt=0; ttt < node.childNodes.length; ttt++) {    if (node.childNodes[ttt] && node.childNodes[ttt].nodeType == '3' && !/\S/.test(  node.childNodes[ttt].nodeValue )) {      node.removeChild(node.childNodes[ttt]);      ttt--;		}		else {			if (node.childNodes[ttt].childNodes.length) {				remove_whitespace(node.childNodes[ttt]);      }		}	}	return;}