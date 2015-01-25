<?php

//Updated June 12, 2012 - fix for jquery
//Updated Feb 27, 2011 - fix formatting issues
//Updated Feb 26, 2011 - allow comparison of "start date" and "end date".
//Updated Feb 26, 2011 - fix incoming currdate to be seconds and exclude milliseconds
//Updated February 25, 2011 - fix next year and month links to include "currdate"

//make calendar
date_default_timezone_set("Canada/Mountain");

$calendarlocation = "makecal.php";

$today_r = getdate();
$timestamp = mktime(0,0,0,$today_r['mon'],$today_r['mday'],$today_r['year']);
$todayDay = date("d",$timestamp);
$todayMth = date("n",$timestamp);
$todayYr = date("Y",$timestamp);
//echo $_GET['otherdatestr'];

if ( isset($_GET['currdate']) && ($_GET['currdate']) ) {
	$raw_currdate = $_GET['currdate'];
	
	//check see if our date string has dashes in it. OR if it is a datestamp.
	if ( (stripos($raw_currdate,"-")>2) && (strripos($raw_currdate, "-")>5) ) {
		$currdate = strtotime($raw_currdate);
		//check to see if there was a second date field pulled by the javascript pop_cal. 
		//if the date is higher than our currdate value, we want to use that as our calendar date instead. ie. start and end dates.
		if ( (stripos($_GET['otherdatestr'],"-")>2) && (strripos($_GET['otherdatestr'], "-")>5) ) {
			$otherdatestr = strtotime($_GET['otherdatestr']);
			//if the "start" date is higher, use that date as our reference point 
			if ($otherdatestr > $currdate) $currdate = $otherdatestr;
		} else {
			//if there is no other date, then		
			//make sure our date is not zero/1969-12-31 (unix epoch)
			if ( ($_GET['currdate'] == "1969-12-31") || ($_GET['currdate'] == "0000-00-00") ) { 
				//if so, set to today's date as a string so that the next IF statement works properly
				$currdate = $timestamp;
			} else {	
				$currdate = strtotime($_GET['currdate']);
			}
		}
	} else if (is_numeric($raw_currdate)) {
		$currdate = $raw_currdate;
	} else {
		$raw_currdate = $_GET['currdate'];
	}
} else {
	$currdate = $timestamp;
}

//get target cell 
if (!$targetcell) $targetcell = $_GET['caltarget'];

//get calendar enclosing div
if (!$closecaldiv) $closecaldiv = $_GET['closecal'];  //DEPRECATED

//close function name
if (!$closefunction) $closefunction = $_GET['closefunction'];//DEPRECATED

//&currdate="+currdate_unix+"&caltarget="+target_input+"&closecal=newbox"+target_input+"&closefunction=rem_el





$currMth = date("n",$currdate);
$currYr = date("Y",$currdate);

$firstOf = mktime(0,0,0,date("n",$currdate),1,date("Y",$currdate));
$firstDayOf = date("w",$firstOf);

$lastOf = mktime(0,0,0,date("n",$currdate)+1,1,date("Y",$currdate))-1; //last day of the month as a timestamp
$lastDayOf = date("w",$lastOf); //weekday # of the last day of the month (0=sunday, 6=saturday) 
$totNumDays = date("d",$lastOf); //last day of the month
/*
echo "<Br/>last day:".$lastOf." ".date("YMd",$lastOf)." ";
echo "<br/>".date("YMd",1312178399)." <br/>";
*/
$nextMth = mktime(0,0,0,date("n",$currdate)+1,1,date("Y",$currdate));
$prevMth = mktime(0,0,0,date("n",$currdate)-1,1,date("Y",$currdate));

/* echo "next: ".date("YMd",$nextMth)." ".$nextMth; */

$nextYR = mktime(0,0,0,date("m",$currdate),1,date("Y",$currdate)+1);
$prevYR = mktime(0,0,0,date("m",$currdate),1,date("Y",$currdate)-1);

$calcontrols = "<p style='font-family: 'Arial', sans-serif; padding: 0; margin: 0; font-size: 14px; text-align: center;'>"
			."<a style='text-decoration: none; color: #555;' href='".$calendarlocation."?&caltarget=".$targetcell."&closecal=".$closecaldiv."&closefunction=".$closefunction."&currdate=".$prevYR."' onclick='followLink(this.href,\"".$closecaldiv."\"); return false;'>&lt;&lt;</a>&nbsp;&nbsp;"
			."<a style='text-decoration: none; color: #555;' href='".$calendarlocation."?&caltarget=".$targetcell."&closecal=".$closecaldiv."&closefunction=".$closefunction."&currdate=".$prevMth."' onclick='followLink(this.href,\"".$closecaldiv."\"); return false;'>&lt;</a>&nbsp;"
			.date("M Y",$currdate)
			."&nbsp;<a style='text-decoration: none; color: #555;' href='".$calendarlocation."?&caltarget=".$targetcell."&closecal=".$closecaldiv."&closefunction=".$closefunction."&currdate=".$nextMth."' onclick='followLink(this.href,\"".$closecaldiv."\"); return false;'>&gt;</a>"
			."&nbsp;&nbsp;<a style='text-decoration: none; color: #555;' href='".$calendarlocation."?&caltarget=".$targetcell."&closecal=".$closecaldiv."&closefunction=".$closefunction."&currdate=".$nextYR."' onclick='followLink(this.href,\"".$closecaldiv."\"); return false;'>&gt;&gt;</a>"			
			."</p>";

$cal = "<table border='0' cellspacing='0' cellpadding='0' style='width: 196px; margin-left: auto; margin-right: auto;'><tbody>"
		."<tr><td colspan='7' style='text-align: center;'>".$calcontrols."</td></tr>"
		."<tr style='border: 1px solid #999; background: #ddd;'>";
	$dow = array("S","M","T","W","T","F","S");
	foreach ($dow as $doweek) {
		$cal .= "<th class='cal-day' style='width: 28px; color: #222; font-weight: bold;'>".$doweek."</th>";
	}
$cal .= "</tr>";

$cal .= "<tr style='border: 1px solid #bbb; border-top: 0px solid;'>";

$dow_c = 0;

//$weekdifference = (7-($lastDayOf+1));
//+7+$weekdifference

for ($i=1; $i<=($totNumDays); $i++) {
	//delete old vars
	unset($preventLink,$cal_cellclass);
	
	if (($i == $todayDay) && ($currMth == $todayMth ) && ($currYr == $todayYr) ) {
		$cal_cellclass = "cal-day cal-today";
		$preventLink = false;
	} else if (mktime(0,0,0,$currMth,$i,$currYr) < mktime(0,0,0,$todayMth,$todayDay,$todayYr) ) {
		$cal_cellclass = "cal-expired";
		$preventLink = true;
	} else {
		$classtype = "style='text-align: center;'";
		
		$cal_cellclass = "cal-day";
		$preventLink = false;
	}
	
	$thisday = mktime(0,0,0,$currMth,$i,$currYr); 
	
	if (( date("N",$thisday)>5 ) && (!strpos($cal_cellclass,'expired'))) $cal_cellclass = " cal-wkd";

	if ((!strpos($cal_cellclass,'expired'))) $cal_cellclass .= " get_calendar_date ";
	
	$cal_cellclass .= " calendar";
	
	//insert cell contents (Date/month)
	$cellcontent = "<p style='padding: 0; margin: 0; font-size: 14px; text-align: center;'>";
	if (!$preventLink) {
		//no need for a link in the past, if not in the past, then allow a link
//		$cellcontent .= "<a href='#' class='get_calendar_date' data-date='".date("Y-m-d",$thisday)."' data-timestamp='".$thisday."' data-textdate='".date("l, F d, Y",$thisday)."' data-targetcell='".$targetcell."' style='text-decoration: none; color: #555; text-align: center;'>".$i."</a>";
		$cellcontent .= $i;
		$td_click_data = "data-date='".date("Y-m-d",$thisday)."' data-timestamp='".$thisday."' data-textdate='".date("l, F d, Y",$thisday)."' data-targetcell='".$targetcell."'";
		//onclick='$(\"#".$targetcell."\").val(\"".date("Y-m-d",$thisday)."\"); return false;'
	} else {
		$cellcontent .= $i;
	}
	$cellcontent .= "</p>";
	
	if ($i > $totNumDays) {
		if ($i == $totNumDays) {
			
		} else {
			
		}
	} else if ($i == 1) {
		if ($firstDayOf > 0) {
			$dow_c = ($firstDayOf);
			$cal .= "<td class='cal-expired' colspan='".($firstDayOf)."'></td><td class='".$cal_cellclass."'>";
		} else {
			$cal .= "<td class='".$cal_cellclass."'>";
		}
		$cal .= $cellcontent."</td>";
	} else {
		if ($dow_c == 7) {
			$cal .= "</tr>"
					."<tr style='border: 1px solid #bbb; border-top: 0px solid;'>";
			$dow_c = 0;
		}		

		$cal .= "<td ".$td_click_data." class='".$cal_cellclass."'>"
				.$cellcontent
				."</td>";

		if ( ($i == ($totNumDays)) && ($lastDayOf < 6)) {
			$cal .= "<td class='cal-expired' colspan='".(6-$lastDayOf+1)."'>&nbsp;</td>";
		}

	}
	$dow_c++;
}
$cal .= "</tr>"
		."</tbody></table>";
		//."<p class='c_sml'><a href='#' onclick='rem_el(\"newbox".$targetcell."\"); return false;'>Close</a></p>";

/*
<html>
<head>
<title>Calendar</title>
<script  type="text/javascript" src="../../js/ajaxlinx.js"></script>    
<script type="text/javascript" src="../../js/jfunctions.js"></script>

*/
?>




<div id='<?php echo $closecaldiv; ?>'>
<?php

echo $cal;
?>
<input type='hidden' id='calendartarget' />
</div>
<?php
/*
</body>
</html>
*/