<?php
//
//staff_shift_list.php
//
//
//
//
//##UPDATES:
//
//Updated: December 14, 2014 - final draft - pub to K and 50

if ($skip_headers) {	
} else {
	include_once("config.inc.php");
	session_start();
	include_once("supporting_queries.php");
}


//Get list of staff members
list($rstaff,$nstaff) = return_table_data("therapists"," WHERE th_admin_delete='n' ","ORDER BY th_active_in_spa ASC, th_pseudoname ASC");

//Copy these variables for use in staff_editprofile_inlay.php
/*
$rstaff2 = $rstaff;
$nstaff2 = $nstaff;
*/

$staff_list = "";

if (( (strlen($_GET['entity'])>0)) ) {
	//check to make sure the form submit is from kings.
	$entity = $_GET['entity'];
	if ($entity == 'kings') {
		$kings = 'y';
		//echo $entity.$kings;
	} else {
		
	}
} else {
	//we may already have done this prior to an include. 
}

if ($nstaff>0) {
	//while($rs = mysql_fetch_array($rstaff)) {}
	$staffarray = fetch_data_to_array($rstaff,$nstaff);
	
	$rowcounter = 1;
	
	foreach ($staffarray as $staffmem) {
		//Hide inactive staff members - we will load them in case they are to be activated during this session from a different control table on this page
		
		//specific to kings site (leave in for modular functionality
		$edithref = ($kings =='y') ? "EditStaff.php?id=".$staffmem['th_id'] : "#'data-staffid='".$staffmem['th_id'];
		
		
		//shade alternating rows of the table
		$tdbgclass = (($rowcounter%2) == 0) ? "shadedtd" : "";
		
		$tdbgclass .= ($staffmem['th_active_in_spa'] == 'y') ? " " : " divinactive";
		
		$check_staffchkbox = ($staffmem['th_active_in_spa'] == 'y') ? "checked='checked'" : "";
		
		//Build Table Row for setting schedule
		$staff_lineitem = "<div id='staffrow".$staffmem['th_id']."' class='".$tdbgclass."'>"
			."<div style='float: left; width: 450px;'>"
			."<p class='psmall'><Span class='small b'>"
			."<input type='checkbox' class='staffchkbox chkboxclick' name='activate".$staffmem['th_id']."' "
				."data-staffid='".$staffmem['th_id']."'"
				."data-name='".$th_pseudoname."' "
				."data-license='".$staffmem['th_licensenum']."' "
				."data-getparams='&table=therapists&row=th_active_in_spa&idprop=th_id&idval=".$staffmem['th_id']."&updatechkboxes=y' "
				."data-uncheckedvalue='n' "
				."data-geturl='update_rows.php' "
				."data-rslttargetprefix='rslt' "
				."id='activate".$staffmem['th_id']."' value='y' ".$check_staffchkbox." />"
			."<label for='activate".$staffmem['th_id']."'>".stripslashes($staffmem['th_pseudoname'])."</label>"
			."</span>"
			."&nbsp;"
			."(#".$staffmem['th_licensenum'].")"

			."&nbsp;&nbsp;<span class='psmall' id='rsltactivate".$staffmem['th_id']."' style='display: none;'></span>"
			."</div>"
			."<div style='float: right; width: 60px;'>"
				."<p><a href='".$edithref."' class='editStaffPostButton' >Edit</a></p>"
			."</div>"
			."<div class='clearall'></div>"
			."</div>"
			."<div class='clearall'></div>";

		$staff_list .= $staff_lineitem;
		$rowcounter++;
	}
	
} else {
	//no staff results - no staff
		
	
	$staff_list = "There are currently no staff. Please add a staff member";
}


echo $staff_list;

?>