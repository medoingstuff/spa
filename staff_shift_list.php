<?php
//
//staff_shift_list.php
//
//
//UPDATE HISTORY
//
//
//
//UPdated: December 14, 2014 - published
//


if ($skip_headers) {	
} else {
	include_once("config.inc.php");
	session_start();
	include_once("supporting_queries.php");
}


//Get list of shifts worked by staff members
$today_r = getdate();
$timestamp = mktime(0,0,0,$today_r['mon'],$today_r['mday'],$today_r['year']);
$add30timestamp = mktime(0,0,0,$today_r['mon'],$today_r['mday']+45,$today_r['year']);

$todayNum = date("j",$today_r[0]);

$currMth = date("n",$currdate);
$nextMth = ($currMth < 12) ? ($currMth + 1) : 1;
$currYr = date("Y",$currdate);
$nextMthYr = ($nextMth > 1) ? $currYr : ($currYr + 1);
####
$firstOf = mktime(0,0,0,date("n",$currdate),1,date("Y",$currdate));
$firstDayOf = date("w",$firstOf);
####
$lastOf = mktime(0,0,0,date("n",$currdate)+1,1,date("Y",$currdate))-1;
$lastDayOf = date("w",$lastOf); 
$totNumDays = date("d",$lastOf); //last day of the month




//			$dayend =  mktime(23,59,59,$currMth,date("d",$today_r),$currYr);
$numdaysInMth = date('t',$timestamp);
$numdaysInNextMth = 21;

//build date arrays
//for ($i = $todayNum;$i<=$numdaysInMth;$i++) { $dates_array[] = $i; }
//for ($i=1;$i<=$numdaysInNextMth; $i++) { $dates_array[] = $i; }

//extract therapists by shift date
list($rshifts,$nshifts) = return_join_table_data("left","therapist_schedule","therapists","a.sch_therapist_id=b.th_id","WHERE a.sch_date >= '".$timestamp."'  ", "ORDER BY a.sch_date ",$returnrows="a.*,b.*"); //AND b.th_admin_delete='n'

if ($nshifts > 0) {
	while ($shift = mysql_fetch_array($rshifts)) {
		if (!($shift['th_admin_delete'] == 'y')) {
			//We cannot check this in our query because then we will not get any of the closed entries. Closed entries are NULL for this field because this is a JOIN query and th_admin_delete is in the Right table. By including it in the WHERE table, it disallows NULLS.
			
			$shiftdates[] = $shift['sch_date'];
			
			if ($shift['sch_closed'] == 'y') {
				//If the store is closed
				$shiftsDBjoin[$shift['sch_date']]['closed'] = "y";
//				echo "##<br/>";
				
			} else if ($shift['sch_therapist_id']>0) {
				//If an employee ID is assigned
				
				//
				if (!(($laststaffID == $shift['sch_therapist_id']) && ($lastdate == $shift['sch_date']))) {
					$staffcounter_perday[$shift['sch_date']]++;
				}
				
				//
	//			$shiftsDBjoin[$shift['sch_date']][$shift['sch_shift']][$shift['sch_therapist_id']] = $shift['sch_id'];
				
				$shiftsDBjoin[$shift['sch_date']]['date'][$shift['sch_date']];
				
				if (!(strlen($shiftsDBjoin[$shift['sch_date']][$shift['sch_therapist_id']]['name'])>1)) {
	
					//if length of name is less than 1, then insert name again, otherwise don't.
					$shiftsDBjoin[$shift['sch_date']][$shift['sch_therapist_id']]['name'] = $shift['th_pseudoname'];
					$shiftsDBjoin[$shift['sch_date']][$shift['sch_therapist_id']]['license'] = $shift['th_licensenum'];
					$shiftsDBjoin[$shift['sch_date']][$shift['sch_therapist_id']]['id'] = $shift['sch_therapist_id'];
					
				}
				$shiftsDBjoin[$shift['sch_date']][$shift['sch_therapist_id']][$shift['sch_shift']] = 'y';
	
				$laststaffID = $shift['sch_therapist_id'];
				$lastdate = $shift['sch_date'];
			} else {
				//only option not reviewed
				$shiftsDBjoin[$shift['sch_date']]['closed'] = "n";
			}
		}
	}
} else {
	$shiftsDBjoin = array();
}
//$shiftsDBjoin = fetch_data_to_array($rshifts,$nshifts);

//print_r($shiftsDBjoin);

//(test) build separate json arrays for each date - overload html5
if (count($shiftdates) > 0) {
	foreach ($shiftdates as $sd) {
		$dtxt .= " data-s".$sd."='".json_encode($shiftsDBjoin[$sd])."' data-l".$sd."='".$staffcounter_perday[$sd]."'";
	}
}

/*
//build json array regardless of whether shifts exist
$da_mth = $currMth; //setup the current month for now
$da_Yr = $nextMthYr;

foreach ($dates_array as $da) {
	$thisDateStamp = mktime(0,0,0,date("n",$da_mth),date("d",$da),date("Y",$da_Yr));
//	$shiftDatesArray[$thisDateStamp] = XXXXXXXXX
	
	
	//Check to see if the current array slice is the last day in the current month. Prep datesarray_mth to next month for the next round if it is.
	$da_mth = ($da == $numdaysInMth) ? $nextMth : $currMth;
	$da_yr = ($da_mth != $currMth) ? $nextMthYr : $currYr;
}
*/




//$staff_shift_list .= "<div id='shiftjsonarray' data-shifts='".json_encode($shiftsDBjoin)."' ".$dtxt."></div>";
$staff_shift_list .= "<div id='shiftjsonarray' ".$dtxt."></div>";



//Get list of staff members

list($rstaff,$nstaff) = return_table_data("therapists"," WHERE th_admin_delete='n' ","ORDER BY th_active_in_spa ASC, th_pseudoname ASC");

if ($nstaff>0) {
	//while($rs = mysql_fetch_array($rstaff)) {}
	$staffarray = fetch_data_to_array($rstaff,$nstaff);
	
	$rowcounter = 1;
	
	foreach ($staffarray as $staffmem) {
		//Hide inactive staff members - we will load them in case they are to be activated during this session from a different control table on this page
		$staff_visibleclass = ($staffmem['th_active_in_spa'] == 'n') ? "inactiveemployee" : "activeemployee hideinitial";
		
		$tdbgclass = (($rowcounter%2) == 0) ? "shadedtd" : "";
		
		//sch_id
//		$shiftsDBjoin[$shift['sch_date']][$shift['sch_shift']][$shift['sch_therapist_id']] = h$shift['sch_id'];
		
		//Build Table Row for setting schedule
		$staff_lineitem = "<tr id='staffshiftrow".$staffmem['th_id']."' class='".$staff_visibleclass." ".$tdbgclass."'>"
			."<td class='lefttext'>"
			."<p class='small'><span class='b'>".stripslashes($staffmem['th_pseudoname'])."</span><br/><span class='psmall it'>#".$staffmem['th_licensenum']."</span></p></td>"
			."<td class='lefttext psmall'>"
			."<label for='am".$staffmem['th_id']."'>AM&nbsp;</label>"
			."<input type='checkbox' class='shiftchkbox' name='".$staffmem['th_id']."' data-name='".$th_pseudoname."' data-license='".$staffmem['th_licensenum']."' id='am".$staffmem['th_id']."' value='y' "
					."data-getparams='&table=therapist_schedule&row=irrelevant&idval=".$staffmem['th_id']."&updateshifts=y'"
					."data-geturl='update_rows.php'"
					."data-uncheckedvalue='n'"
					."data-rslttargetprefix='rslt'"
					."data-shifttime='am'"
					."/>"
				."<br/>&nbsp;<span class='psmall' style='display: none;' id='rsltam".$staffmem['th_id']."'></span>"
			."</td>"
			."<td class='lefttext psmall'>"
			."<label for='pm".$staffmem['th_id']."'>PM&nbsp;</label>"
			."<input type='checkbox' class='shiftchkbox' name='".$staffmem['th_id']."' data-name='".$th_pseudoname."' data-license='".$staffmem['th_licensenum']."' id='pm".$staffmem['th_id']."' value='y' "
					."data-getparams='&table=therapist_schedule&row=irrelevant&idval=".$staffmem['th_id']."&updateshifts=y'"
					."data-geturl='update_rows.php'"
					."data-uncheckedvalue='n'"
					."data-rslttargetprefix='rslt'"
					."data-shifttime='pm'"
					."/>"
				."<br/>&nbsp;<span class='psmall' style='display: none;' id='rsltpm".$staffmem['th_id']."'></span>"
			."</td>"
			.""
			."</tr>";

		$staff_shift_list .= $staff_lineitem;
		$rowcounter++; //$table,$row,$idfld,$id,$newval,$otheroptionsarr=null)
	}
	
	
	$StoreClosed = "<p class='small'>"
		."Closed this day?&nbsp;"
		."<input type='radio' id='storeclosedY' name='storeclosed'  class='storeclosed' value='y' />"
		."<label for='storeclosedY'>Yes&nbsp;&nbsp;</label>"
		."<input type='radio' id='storeclosedN' name='storeclosed' class='storeclosed' value='n' checked='checked' />"
		."<label for='storeclosedN'>No&nbsp;</label>"
		."&nbsp;&nbsp;<span id='rsltstoreclosedY'></span>&nbsp;&nbsp;<span id='rsltstoreclosedN'></span>"
		."</p>";
	
	//Build table header
	$staff_shift_list = $StoreClosed."<table cellpadding=0 cellspacing=0 border=0 style='width: 300px;' width='300'><tbody>"
		."<tr class='darktext shadedtd'><th class='lefttext'><p class='small b lefttext'>Employee</p></th>
			<th class='small b'>AM</th>
			<th class='small b'>PM</th></tr>"
		.$staff_shift_list
		."</tbody></table>";
	
	
} else {
	//no staff results - no staff
		
	
	$staff_shift_list = "Please Update the Active Staff List. You currently don't have any employees to add to shifts.";
}


echo $staff_shift_list;

?>