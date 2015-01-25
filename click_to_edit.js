/**
 * Click to Edit V1.0 
 *
 */

//Updated January 21, 2010 - Change check html address functions - [leduchitch]

function click_to_edit(fieldtype,clikspanid,spanvalue,all_args,rowname,bgcolor,overcolor,followto,opth,optw,isdate,targ) 
{
	//isdate = n 
	//targ = "pagebody"
	
	

	_gebi("s_"+clikspanid).style.display = "none";
	emptyspanx = document.getElementById(clikspanid);
	newfield = document.createElement(fieldtype);
	emptyspanx.appendChild(newfield);
	
	if (fieldtype == "input") {
		newfield.setAttribute("type","text");
	}
	newfield.setAttribute("id","inp_"+clikspanid);
	newfield.setAttribute("name","inp_"+clikspanid);
	if ((opth > 0) && (optw > 0)) {
		newfield.setAttribute("style","width: "+optw+"px; height: "+opth+"px;");
		newfield.style.height = opth+"px";
		newfield.style.width = optw+"px";
	}

	//must get all child nodes as <br> separates all the lines in a paragraph creating a separate child per line.
	var objel = _gebi("s_"+clikspanid);
	var nodz = objel.childNodes;
	
	var fldentry;
	var numchks;
	if (objel.firstChild.nodeType == 3) {
		var chld = objel.firstChild;
		fldentry = chld.nodeValue;
		numchks = 1;
	} else {
		var chld = objel.firstChild;
		numchks = 1;
		while (chld.nodeType != 3) {
			chld = chld.nextSibling;
			fldentry = fldentry + "<br />";
			numchks++;
		}
		fldentry = chld.nodeValue;
	}
	
	for (icount=numchks; icount < nodz.length; icount++) {
		if (chld = chld.nextSibling) {
			if (chld.nodeType == 3) {
				fldentry = fldentry + chld.nodeValue;
			} else {
				fldentry = fldentry + "<br />";
			}
		}
	}
	
	fldentry = fldentry.replace(RegExp("<br />","gi"),"\n");
	newfield.value = fldentry;
	newfield.onblur = function () {
		click_to_edit_onblur(isdate,rowname,followto,all_args,targ,clikspanid,fieldtype);
	}
	newfield.onfocus = function () {
			attachFocus(event);
	}					
	notfocus = false;					
	savebtn = document.createElement("span");
	savebtn.style.cursor = "pointer";
	savebtn.style.color = "red";
	savebtn.className = "para_smltxt";
	save = document.createTextNode(" Save");
	savebtn.appendChild(save);
	
	emptyspanx.appendChild(savebtn);
	
	newfield.focus();
	newfield.select();
}

function click_to_edit_onblur(isdate,rowname,followto,all_args,targ,clikspanid,fieldtype) {
//	newfield.setAttribute("id","inp_"+clikspanid);
	var newfield = _gebi("inp_"+clikspanid);
	notfocus = true;
	valuetype = isdate;
	if ( (valuetype == "y") ) {
		if (ddate = check_date_format(newfield.value)) {
			followLink(followto+"?"+all_args+"&row="+rowname+"&value="+(Date.parse(ddate)/1000),targ);
		} else {
			return false;
		}
		if (targ == "gridviewresult") {
			delete_children_of("s_"+clikspanid);
			var newvalue = document.createTextNode((Date.parse(ddate)/1000));
			_gebi("s_"+clikspanid).appendChild(newvalue);
			_gebi("s_"+clikspanid).style.display = "inline";								
			_gebi("s_"+clikspanid).appendChild(newvalue);
		}
	} else {
//		alert(newfield.id);
		var newcomment = addslash(newfield.value);
		if (fieldtype == "textarea") {
			var regex = new RegExp("\\n","gi");
			newcomment = newcomment.replace(regex,"<br />");
		}
		//change ampersand so it will be accepted in the querystring 
		var r = new RegExp("\\&","gi");
		newcomment = newcomment.replace(r,"%26");
					
		if (valuetype == "email") {
			if (isValidEmail(newcomment)) {
				//continue
			} else {
				alert("Please enter a valid email address.");
				return false;
			}
		}
		if (valuetype == "http") {
			if ( isURLx(newcomment) ) {
				//continue
			} else {
				alert("Please enter a valid web address starting with http://.");
				return false;
			}
		}			
		if (targ == "gridviewresult") {
			delete_children_of("s_"+clikspanid);
			var newvalue = document.createTextNode(newfield.value);
			_gebi("s_"+clikspanid).appendChild(newvalue);
			_gebi("s_"+clikspanid).style.display = "inline";
			delete_children_of(clikspanid);
		}
		followLink(followto+"?"+all_args+"&row="+rowname+"&value="+newcomment,targ);	
	}
}
