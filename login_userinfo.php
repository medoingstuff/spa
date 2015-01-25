<?
$pagetitle = "Update User Profile";
include('header.php');

?>


<tr><td>

<?

echo "<div id='updateuserinfo' style='margin-left: 20px; border-bottom: 1px solid #ccc;'><br /><br />";

//check if user is logged in: if not, end.
if (!($logged_in)) {
	echo "<p class='para_smltxt'>You cannot edit a user profile if you are not logged in.</p>";
	echo "</div>";
	include('footer.php');
	exit;
} else {
	//if logged in....
	if ($_GET['update_rows'] == 'y') {
		//update rows
		update_users($_GET['value'],$_GET['row'],$userinfoarray['user_id'],$_GET['which_db']);
		if ($_GET['row'] == "user_email") {
			$_SESSION['user_email'] = $_GET['value'];
		} else if ($_GET['row'] == "username") {
			$_SESSION['username'] = $_GET['value'];
			setcookie("cookname", $_SESSION['username'], time()+60*60*24*100, "/");			
		}
		
	} else if ($_POST['cp']) {
		
		//fetch existing password
		list($result,$n) = get_users_multi($userinfoarray['user_email'],$perm_db);

		/* Retrieve password from result, strip slashes */
		if ($n>0) {
			//retrieve row
			$dbarray = mysql_fetch_array($result);
			$dbarray['user_pwd']  = stripslashes($dbarray['user_pwd']);
			$password = md5(stripslashes($_POST['npw']));
			$oldpassword = md5(stripslashes($_POST['cpw']));			
			if ( ($oldpassword == $dbarray['user_pwd']) ){	
				update_users($password,"user_pwd",$userinfoarray['user_id'],$_POST['which_db']);
				$feedback = "Password updated.";
				$_SESSION['password'] = $password;
			} else {
				$feedback = "Your old password is incorrect.";
			}
		} else {
			$feedback = "A database error has occured. Please try again later.";
		}
	}//end requests (if logged in)
}

//we are still logged in here. User was forced to exit when not logged in status discovered above.


//get user info from db.
list($r,$n) = get_users_multi ($userinfoarray['user_email'],$perm_db);

if ($n > 0) {
	//there should be only one result.
	while ($rrow = mysql_fetch_array($r)) {
		$rr = $rrow;
	}
} else {
	//user not found in db 
	echo "<p class='para_medtxt'>"
			."We are experiencing difficulties accessing your account. Please try again."
		."</p>";
}

//determine which DB to change password in....
if ($rr['user_id'] > 9999) $which_db = 'master';
else $which_db = 'local';

echo "<p class='para_lrgtxt'>Edit User Information<br/>&nbsp;</p>";

//setup data to retrieve from tables
$d_r_a = array(
		array("Email Address","user_email","eml","email"),
		array("Username","username","usr","n"),
		array("First Name","user_first","fn","n"),
		array("Last Name","user_last","ln","n")
		);

//create data fields
foreach ($d_r_a as $d) {

	echo "<p class='para_medtxt'>".$d[0].":&nbsp;";
	echo make_clickable_text("input","click".$d[2],strip_quote($rr[$d[1]]),$args.$editing_args."&table=users&update_rows=y&which_db="
		.$which_db."",$d[1],"lightblue","lightgreen","",0,0,$d[3],"pagebody");
	echo not_blank_text(stripslashes($rr[$d[1]]),"Click to enter ".$d[0]." here")."</span>"
			."<span class='para_smltxt' style='color: #ccc;'>&nbsp;(click to edit)&nbsp;</span>";
	echo "<span id='click".$d[2]."' class='para_medtxt'></span>";
	echo "</p>";
}
echo "<p style='' class='para_medtxt'><br /></p>";

//change password fields 
?>
</div>
<div style='border-bottom: 1px solid #ccc; margin-left: 20px;'>
	<form action="?" method="post">
		<p style='' class='para_medtxt'><br /></p>	
		<p class='para_medtxt smltxt_b'>
			<b>Change Password</b>
		</p>
		<p class='para_medtxt' style='color:#0099cc;'><?=$feedback; ?></p>
		<p style='' class='para_medtxt'><br /></p>	
		<p style='' class='para_medtxt'>
			Current Password:&nbsp;&nbsp;&nbsp;&nbsp;
			<input type="password" name="cpw" value='' maxlength="30" id="cpw" />
		</p>

		<p style='' class='para_medtxt'>	
			New Password:&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
			<input type="password" name="npw" value='' maxlength="30" id="npw" />
		</p>

		<p style='' class='para_medtxt'>	
			Re-enter Password:&nbsp;&nbsp;
			<input type="password" name="rnpw" value='' maxlength="30" id="rnpw" />
		</p>

		<p style='' class='para_medtxt'>
			<input type='submit' name='cp' value='Change Password' 
				onclick='var c = $("cpw").value;
						var n = $("npw").value;
						var r = $("rnpw").value; 
						if (n == c) {
							alert("New password and old password cannot be the same");
							return false;
						}
						if ((c.length < 1) || (r.length < 6)) {
							alert("Password is too short.");
							return false;
						}
						if (!(n == r)) {
							alert("New password must match password confirmation. Please try again.");
							return false;
						}
						'>
		</p>
		<input type='hidden' value='<?=$which_db; ?>' name='which_db'>
	</form>
</div>

<?

echo "</div>";
?>
</td></tr>
<!-- </table> -->
<?

include('footer.php');

?>


