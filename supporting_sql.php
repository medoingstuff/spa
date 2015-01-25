<?php

//Updated June 2012 - 

#
#
#	SQL Related Functions 
#
#

function check_results($result) {
	if((!$result) || (mysql_num_rows($result) < '1')) {
		$num_results = '0';
	} else $num_results = mysql_num_rows($result);
	return array($result,$num_results);
}

function return_table_data($table,$whereby=null,$orderby=null,$returnrows="*") {
	$r = mysql_query("
	SELECT ".$returnrows."
	FROM ".$table." 
	".$whereby." 
	".$orderby." 
	") or die("return_table_data(): "."SELECT ".$returnrows." FROM ".$table."  ".$whereby."  ".$orderby." ".mysql_error());;
	return check_results($r);
}

function return_join_table_data($rt_or_left_or_none,$tableleft,$tableright,$on_a_and_b,$whereby=null,$orderby=null,$returnrows="a.*,b.*") {
	//example: list($rlx,$nlx) = return_join_table_data(" LEFT","p_mytrips_links_join","p_mytrips_links","a.tlink_linkid = b.linx_id ","tlink_tripid='".$tid."'","ORDER BY linx_title");
	$q = "
	SELECT ".$returnrows."
	FROM ".$tableleft." AS a ".$rt_or_left_or_none." JOIN ".$tableright." AS b ON ".$on_a_and_b." 
	".$whereby." 
	".$orderby." 
	";
	//echo $q;
	$r = mysql_query($q) or die("return_join_table_data($rt_or_left_or_none,$tableleft,$tableright): ".mysql_error());
	return check_results($r);
}

function addto_mysql_table($table,$rows_strng,$vals_strng,$otheroptionsarr=null) {
	return mysql_query("INSERT INTO ".$table." 
						(".$rows_strng.")
						VALUES (".$vals_strng.")
						") or die("addto_mysql_table: INSERT INTO ".$table." (".$rows_strng.") VALUES(".$vals_strng.")".mysql_error());

}

function delete_from_mysql_table ($table,$idfld,$id,$otheroptionsarr=null) {
	return mysql_query("DELETE FROM ".$table." WHERE ".$idfld."='".$id."' ".$otheroptionsarr['andwhere']." ");
}

function update_mysql_table($table,$row,$idfld,$id,$newval,$otheroptionsarr=null) {
//echo("update_mysql_table(UPDATE ".$table." SET ".$row."='".$newval."' ".$otheroptionsarr['andset']." WHERE ".$idfld."='".$id."' ".$otheroptionsarr['andwhere']." ");

	return mysql_query("UPDATE ".$table." 
						SET ".$row."='".$newval."' ".$otheroptionsarr['andset']."
						WHERE ".$idfld."='".$id."' ".$otheroptionsarr['andwhere']."
						") or die("update_mysql_table(UPDATE ".$table." SET ".$row."='".$newval."' ".$otheroptionsarr['andset']." WHERE ".$idfld."='".$id."' ".$otheroptionsarr['andwhere']." ".mysql_error());
}
function fetch_data_to_array($datarslt,$data_count,$fld=null) {
	$rslt_array = array();
	if ($data_count > 0) {
		while ($d = mysql_fetch_array($datarslt)) {
			if ($fld) {
				$rslt_array[] = $d[$fld];
			} else {
				$rslt_array[] = $d;
			}	
		} //end while 
		return $rslt_array;		
	} //end if num > 0
}


?>