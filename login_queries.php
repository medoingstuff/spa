<?php
//Updated April 6, 2012 - added function return_username() for mds site 
//Updated April 3, 2012 - fixed get_users to check the masterdb instead of the localdb.
//UPDATED March 25, 2012 - added localdb to most functions. - fixed issue with perm_db which is for user permissions rather than table selection.

function update_sessions ($user_id,$viewonline) {
	
	
	mysql_query("INSERT INTO user_sessions (session_id,session_user_id,session_time,session_ip,session_page,session_view_online)
				VALUES('".$_SESSION['id']."','".$_SESSION['user_id']."',UNIX_TIMESTAMP(),'".$_SERVER['REMOTE_ADDR']."','".$_SERVER['SCRIPT_FILENAME']."','".$viewonline."')
				ON DUPLICATE KEY
				UPDATE session_time=UNIX_TIMESTAMP() ,session_page='".$_SERVER['SCRIPT_FILENAME']."'
				") or die(mysql_error());
				
	mysql_query("DELETE FROM user_sessions WHERE session_time < (UNIX_TIMESTAMP()-900)") or die(mysql_query());
}

function delete_anon_session_on_login ($sessionid) {
	mysql_query("DELETE FROM user_sessions WHERE session_id = '".$sessionid."' ") or die(mysql_query());	
}


function check_online_sessions () {
	$sql = "SELECT * FROM user_sessions";
	return check_4_result(mysql_query($sql));
}

function return_username($userid) {
	global $localdb;
	global $masterdb;
	$r = mysql_query("SELECT username,user_first,user_last,user_email FROM $localdb.users WHERE user_id='".$userid."' 
						UNION
						SELECT username,user_first,user_last,user_email FROM $masterdb.users WHERE user_id='".$userid."' ");
	list($rslt,$n) = check_results($r);
	if ($n > 0) {
		$rslt_array = mysql_fetch_array($rslt);
		return $rslt_array;
	}
	else return;
}

function is_subscribed() {
	$sql = "SELECT * FROM newsfeed_subscribers WHERE subscriber_email='".$_SESSION['user_email']."' ";
	$r = mysql_query($sql);
	return check_results($r);
}

function check_online_sessions_w_users () {
	global $localdb;
	global $masterdb;
	$sql1 = "SELECT s.*,u.* 
			FROM ".$localdb.".user_sessions AS s
			LEFT JOIN ".$localdb.".users AS u
			ON u.user_id = s.session_user_id";
	$sql2 = "SELECT s.*,u.* 
			FROM ".$localdb.".user_sessions AS s
			LEFT JOIN ".$masterdb.".users AS u
			ON u.user_id = s.session_user_id";
	list($r1,$n1) = check_4_result(mysql_query($sql1));
	list($r2,$n2) = check_4_result(mysql_query($sql2));	

	if ($n1 > 0) {
		while ($rr = mysql_fetch_array($r1)) {
			$rslt[] = $rr;
		}
	}
	unset($rr,$r1);
	if ($n2 > 0) {
		while ($rr = mysql_fetch_array($r2)) {
			$rslt[] = $rr;
		}
	}
			
	return array(($n1+$n2),$rslt);
}

function check_for_user($username,$email_or_username,$multiusers) {
	global $localdb;
	global $masterdb;
	$sql = "SELECT username,user_id,user_email 
			FROM ".$localdb.".users 
			WHERE ".$email_or_username." = '".$username."' ";
	$sql_mstr = "SELECT username,user_id,user_email
			FROM ".$masterdb.".users 
			WHERE ".$email_or_username." = '".$username."' ";
	if ($multiusers == 'y') {
		list($r,$n) = check_4_result(mysql_query($sql));
		list($rm,$nm) = check_4_result(mysql_query($sql_mstr));
//		echo $n." : multi users : ".$nm."    ".$email_or_username." ".$username."  <br/> ";
		if (($n > 0) || ($nm > 0) ) {
			return true;
		}
	} else {
		list($r,$n) = check_4_result(mysql_query($sql_mstr));
		if ($n > 0) return true;
	}
}

function new_user ($username,$password,$email,$firstname,$lastname,$showonline,$user_access,$site_db_name,$db) {
    $sql = "INSERT INTO ".$db.".users (user_id,username,user_pwd,user_first,user_last,user_email,show_online,user_access,permissions,access_other_sites)
              VALUES(NULL,'$username',MD5('".$password."'),'$firstname','$lastname','$email','$showonline','$user_access','$site_db_name','n') ";
	return mysql_query($sql);
}

function get_users_multi ($email,$perm_db) {
	global $localdb;
	global $masterdb;
	//user_pwd,user_first,user_last,user_id,username,user_email,user_access,permissions,show_online,access_other_sites
	$result = mysql_query("
		SELECT user_pwd,user_first,user_last,user_id,username,user_email,user_access,permissions,
					show_online,access_other_sites,reset_auth,reset_count,reset_last_date
		FROM ".$localdb.".users 
		WHERE username = '".$email."' OR user_email = '".$email."'
			UNION 
		SELECT user_pwd,user_first,user_last,user_id,username,user_email,user_access,permissions,
					show_online,access_other_sites,reset_auth,reset_count,reset_last_date
		FROM ".$masterdb.".users 
		WHERE (username = '".$email."' OR user_email = '".$email."') AND (permissions = 'ALL' OR permissions = '".$perm_db."' OR access_other_sites='y')
					") or die(mysql_error());
	return check_4_result($result);
}

function get_users ($email,$perm_db) {
	global $localdb;
	global $masterdb; ##############LOCAL ONLY?
	$result = mysql_query("
			SELECT user_pwd,user_first,user_last,user_id,username,user_email,user_access,permissions,show_online,access_other_sites
			FROM ".$masterdb.".users 
			WHERE (username = '".$email."' OR user_email = '".$email."') AND (permissions = 'ALL' OR permissions = '".$perm_db."')
		") or die(mysql_error());
	return check_4_result($result);	
}

function get_users_by_id_multi ($id) {
	global $localdb;
	global $masterdb;
	$result = mysql_query("
		SELECT user_pwd,user_first,user_last,user_id,username,user_email,user_access,permissions,show_online,access_other_sites
		FROM users 
		WHERE user_id = '".$id."'
			UNION 
		SELECT user_pwd,user_first,user_last,user_id,username,user_email,user_access,permissions,show_online,access_other_sites
		FROM ".$masterdb.".users 
		WHERE user_id = '".$id."' ") or die(mysql_error());
	return check_4_result($result);
}

function get_all_users() {
	global $localdb;
	global $masterdb;
	$result = mysql_query("
		SELECT user_pwd,user_first,user_last,user_id,username,user_email,user_access,permissions,show_online,access_other_sites
		FROM users 
			UNION 
		SELECT user_pwd,user_first,user_last,user_id,username,user_email,user_access,permissions,show_online,access_other_sites
		FROM ".$masterdb.".users  ") or die(mysql_error());
	return check_4_result($result);
}

function update_users($to,$row,$id,$which_db) {
	global $localdb;
	global $masterdb;
	if ($which_db == 'master') {
		$db = $masterdb.".";
	} else $db = "";
	return mysql_query("UPDATE ".$db."users 
						SET ".$row."='".$to."' 
						WHERE user_id='".$id."'  ") 
		or die(mysql_error());
}

function update_password_resets($to,$id,$which_db) {
	global $localdb;
	global $masterdb;
	if ($which_db == 'master') {
		$db = $masterdb.".";
	} else $db = "";
	return mysql_query("UPDATE ".$db."users 
						SET reset_auth='".$to."',reset_count=(reset_count+1),reset_last_date=NOW() 
						WHERE user_id='".$id."'  ") or die(mysql_error());
}

/*******************************/

function get_auth_codes($auth_code) {
	global $localdb;
	global $masterdb;
	global $perm_db;
	$sql = "SELECT *
			FROM ".$masterdb.".new_user_auth_codes
			WHERE perm_db = '".$perm_db."' AND auth_code = '$auth_code' ";
	return check_4_result(mysql_query($sql));
	//Full Texts  	id 	perm_db 	auth_code 	user_access 0=stduser, 1=site admin, 2=admin all sites	code_expiry_date	
}



function check_4_result($result) {
	if((!$result)) {
		$num_results = -1;
	} else if (mysql_num_rows($result) < 1) {
		$num_results = 0;
	} else $num_results = mysql_num_rows($result);
	return array($result,$num_results);
}
?>