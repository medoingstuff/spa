<?
/**************************
 * Click to Edit V1.0 
 *
 */

	//UPDATED - March 31, 2011 [mds] - added class name "click_span_class" to 

###########
## Requires jfunctions.js - addslash and stripslashes functions
##			click_to_edit.js - javascript function for click to edit.
##
###########

##	edit header file to include:
//		<script type="text/javascript" src="click_to_edit.js"></script>
//		include('click_to_edit_text.php');

function make_clickable_text($fieldtype, $clikspanid, $spanvalue, $all_args, 
							$rowname, $bgcolor, $overcolor, $followto, $opt_h, $opt_w, $isdate="n", $targ="pagebody", $click_span_class="click_span_class") {
	//make a clickable SPAN that hides and shows a INPUT instead with the text previously contained by the SPAN. 
	//If more arguments are reqd, enter them into $ALL_ARGS and pass to the function. Format? "&a=b&b=b&c=c"

$str = <<<EOD
<span 	class="$click_span_class"
		onmouseover='this.style.background="$overcolor";'
		onmouseout='this.style.background="$bgcolor";'
		onclick='click_to_edit("$fieldtype", "$clikspanid", "$spanvalue", "$all_args", 
							"$rowname", "$bgcolor", "$overcolor", "$followto", "$opt_h", "$opt_w", "$isdate", "$targ");'
		style='cursor: pointer; background: $bgcolor; text-decoration: underline;'
		id="s_$clikspanid" >
EOD;

	return $str;
}

function make_wysiwyg_text($complexity, $clikspanid, $spanvalue, $all_args, 
							$rowname, $bgcolor, $overcolor, $followto, $opt_h, $opt_w, $isdate="n", $targ="pagebody") {
	//make a clickable SPAN that hides and shows a INPUT instead with the text previously contained by the SPAN. 
	//If more arguments are reqd, enter them into $ALL_ARGS and pass to the function. Format? "&a=b&b=b&c=c"
$str .= "<div id='panel_for_".$clikspanid."' style='width: ".$opt_w."px;'></div>";
$str = <<<EOD
<span 	onmouseover='this.style.background="$overcolor";'
		onmouseout='this.style.background="$bgcolor";'
		onclick='click_to_edit_wysiwyg("$complexity", "$clikspanid", "$spanvalue", "$all_args", 
							"$rowname", "$bgcolor", "$overcolor", "$followto", "$opt_h", "$opt_w", "$isdate", "$targ");'
		style='cursor: pointer; background: $bgcolor; text-decoration: underline;'
		id="s_$clikspanid" >
EOD;

	return $str;
}


function not_blank_text($str,$alternate) {
	//Substitutes blank text space with "alternate" text. ie. "Click here to add text" instead of "".
	if (!(strlen($str) > 0)) {
		$str = $alternate;
	}
	return $str;
}



function make_clickable_text_orig($fieldtype, $clikspanid, $spanvalue, $all_args, 
							$rowname, $bgcolor, $overcolor, $followto, $opt_h, $opt_w, $isdate="n", $targ="pagebody") {
//make a clickable SPAN that hides and shows a INPUT instead with the text previously contained by the SPAN. 
//If more arguments are reqd, enter them into $ALL_ARGS and pass to the function. Format? "&a=b&b=b&c=c"
//
//field type = input or textarea
//
//
##USAGE:
//if ($edit = y) {
//echo make_clickable_text("input","clickspan",$cat_info['cat_name'],$args,"cat_name","lightblue","lightgreen","","n");
//} else {
//echo "<span>";
//}
//echo $cat_info['cat_name']."</span>"
//
//echo $fieldtype." ".$clikspanid." ".$spanvalue." ".$all_args." ".$rowname." ".$bgcolor." ".$overcolor." ".$followto." ".$opt_h." ".$opt_w." ".$isdate="n".$targ="pagebody";
//
//

$str = <<<EOD
<span 	onmouseover='this.style.background="$overcolor";'
		onmouseout='this.style.background="$bgcolor";'
		onclick='javascript:
					var targ = "$targ";
					var fieldtype = "$fieldtype";
					var opth = "$opt_h";				
					var optw = "$opt_w";
					this.style.display = "none";
					emptyspanx = document.getElementById("$clikspanid");
					newfield = document.createElement(fieldtype);
					emptyspanx.appendChild(newfield);
					
					if (fieldtype == "input") {
						newfield.setAttribute("type","text");
					}
					newfield.setAttribute("id","inp_"+"$clikspanid");
					newfield.setAttribute("name","inp_"+"$clikspanid");
					if ((opth > 0) && (optw > 0)) {
						newfield.setAttribute("style","width: "+optw+"px; height: "+opth+"px;");
					}
				//	var fldentry = stripslashes("$spanvalue");
				//	var fldentry = stripslashes(document.getElementById("s_$clikspanid").firstChild.nodeValue);
					//must get all child nodes as <br> separates all the lines in a paragraph creating a separate child per line.
					var objel = _gebi("s_$clikspanid");
					var nodz = objel.childNodes;
//					var chld = objel.firstChild;
					
					var fldentry;
					var numchks;
					if (objel.firstChild.nodeType == 3) {
						var chld = objel.firstChild;
						fldentry = chld.nodeValue;
				//		alert(fldentry);
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
				//	alert("asdf666"+fldentry+"  "+numchks);
					
					for (icount=numchks; icount < nodz.length; icount++) {
						if (chld = chld.nextSibling) {
							if (chld.nodeType == 3) {
								fldentry = fldentry + chld.nodeValue;
							} else {
								fldentry = fldentry + "<br />";
							}
						}
					}
					
					
					
					 
				//	alert(fldentry);
				//	alert(_gebi("s_$clikspanid").lastChild.nodeValue);
					fldentry = fldentry.replace(RegExp("<br />","gi"),"\\n");
					newfield.value = fldentry;
					newfield.onblur = function () {
						notfocus = true;
						valuetype = "$isdate";
						if ( (valuetype == "y") ) {
							if (ddate = check_date_format(this.value)) {
								followLink("$followto?$all_args&row=$rowname&value="+(Date.parse(ddate)/1000),targ);
							} else {
								return false;
							}
							if (targ == "gridviewresult") {
								delete_children_of("s_"+"$clikspanid");
								var newvalue = document.createTextNode((Date.parse(ddate)/1000));
								_gebi("s_"+"$clikspanid").appendChild(newvalue);
								_gebi("s_$clikspanid").style.display = "inline";								
								_gebi("s_$clikspanid").appendChild(newvalue);
							}
						} else {
							var newcomment = addslash(this.value);
							if (fieldtype == "textarea") {
								var regex = new RegExp("\\n","gi");
								newcomment = newcomment.replace(regex,"<br />");
							}
							if (valuetype == "email") {
								if (isValidEmail(newcomment)) {
									//continue
								} else {
									alert("Please enter a valid email address.");
									return false;
								}
							}
							if (targ == "gridviewresult") {
								delete_children_of("s_"+"$clikspanid");
								var newvalue = document.createTextNode(this.value);
								_gebi("s_"+"$clikspanid").appendChild(newvalue);
								_gebi("s_$clikspanid").style.display = "inline";
								delete_children_of("$clikspanid");
							}
							followLink("$followto?$all_args&row=$rowname&value="+newcomment,targ);	
						}
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
					newfield.select(); '
			style='cursor: pointer; background: $bgcolor; text-decoration: underline;'
			id="s_$clikspanid" >
EOD;

	return $str;
}


?>