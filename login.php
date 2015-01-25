<?php
//
//UPDATED March 25, 2012 - Fix the Forgot Password email script link


//note:    when grabbing users from multiple user tables we may end up with the same userids..... 
// solution? start one table in the 1000s?


include_once('common_error.php');
include_once('login_queries.php');

if ($_GET['le'] == '1') {
	$showerrormsg = 'That email doesn\'t exist in our database. Please try again.';
} else if ($_GET['le'] == '2') {
	$showerrormsg = 'Incorrect password, please try again.';	
} else if ($_GET['le'] == '3') {
	$showerrormsg = '***YOU HAVE EXCEEDED THE MAXIMUM NUMBER OF LOGIN ATTEMPTS***';	
}

if ($_GET['tries'] > 0) {
	$tries = $_GET['tries'];
	$tries++;
} else {
	$tries = 1;
}

//echo "<Br/>multiuserlogin: ".$multiuserlogin."<br/>";

/**
  *
  *	ENCRYPTION
  *
  */

function HashPassword($password)
{
  mt_srand((double)microtime()*1000000);
  $salt = mhash_keygen_s2k(MHASH_SHA1, $password, substr(pack('h*', md5(mt_rand())), 0, 8), 4);
  $hash = "{SSHA}".base64_encode(mhash(MHASH_SHA1, $password.$salt).$salt);
  return $hash;
}

//Source code to validate SSHA passwords...

function ValidatePassword($password, $hash)
{
  $hash = base64_decode(substr($hash, 6));
  $original_hash = substr($hash, 0, 20);
  $salt = substr($hash, 20);
  $new_hash = mhash(MHASH_SHA1, $password . $salt);
   if (strcmp($original_hash, $new_hash) == 0)
	return true;
  else
	return false;
}


/**
 * Checks whether or not the given email is in the
 * database, if so it checks if the given password is
 * the same password in the database for that user.
 * If the user doesn't exist or if the passwords don't
 * match up, it returns an error code (1 or 2). 
 * On success it returns 0.
 */
function confirmUser($email, $password){
	global $multiuserlogin;
	global $perm_db; //see config.inc
	
   /* Add slashes if necessary (for query) */
   if(!get_magic_quotes_gpc()) {
	$email = addslashes($email);
   }

	/* Verify that user is in database */
	if ($multiuserlogin == 'y') {
		list($result,$n) = get_users_multi($email,$perm_db);
	
	} else {
		list($result,$n) = get_users($email,$perm_db);
	}
	
	if(!$result || (mysql_numrows($result) < 1)){
	  return 1; //Indicates email failure
	}
	
   /* Retrieve password from result, strip slashes */
   $dbarray = mysql_fetch_array($result);
   $dbarray['user_pwd']  = stripslashes($dbarray['user_pwd']);
   $password = stripslashes($password);
   
   if (!( ($dbarray['permissions'] == $perm_db) || ($dbarray['permissions'] == 'all') )) {
		//do nothing to local users. Make sure that admin users from master db have their admin abilities limited if they are not at their home site.
   }
   
   /* Validate that password is correct */
   if ( ($password == $dbarray['user_pwd']) ){
	//Load SESSION variable 
   		//check to see which site they have access to. 
   		$_SESSION['whichsitepermissions'] = $dbarray['permissions'];

		//ensure that master table users with admin priviledges are only able to make changes on other sites (as a normal user) if they have "access_other_sites"=y
		//users with "all" admin priviledges are exempt.
/*    		if ( (($dbarray['permissions'] != $perm_db) || ($dbarray['permissions'] != 'all')) && ($dbarray['access_other_sites'] == 'y') ) { */
   		if ( ($dbarray['permissions'] != $perm_db) && ($dbarray['permissions'] != 'all') && ($dbarray['access_other_sites'] == 'y') ) {
   			$_SESSION['user_access'] = 0;
   		} else {
			$_SESSION['user_access'] = $dbarray['user_access'];
   		}
   		//	$_SESSION['readonly'] == 'y';
   		
   		//Set session data 
   		$_SESSION['user_first'] = $dbarray['user_first'];
   		$_SESSION['user_last'] = $dbarray['user_last'];
   		$_SESSION['user_id'] = $dbarray['user_id'];
//   		$_SESSION['temp'] = $dbarray['user_pwd_temp'];
   		$_SESSION['username'] = $dbarray['username'];
   		$_SESSION['user_email'] = $dbarray['user_email'];
   		$_SESSION['show_online'] = $dbarray['show_online'];
   		//$_SESSION['password'] = $dbarray['user_pwd'];
//   		$_SESSION['id'] = session_id();
   		
/*
   		//enter user info into sessions table so we can see who is online.
   		if (SHOWONLINE == 'y') {
   			update_sessions ($_SESSION['user_id'],$dbarray['show_online']);
   		}
*/
		return 0; //Success! email and password confirmed
   }
   else{
      return 2; //Indicates password failure
   }
}

/***********************************************************/

/**
 * checkLogin - Checks if the user has already previously
 * logged in, and a session with the user has already been
 * established. Also checks to see if user has been remembered.
 * If so, the database is queried to make sure of the user's 
 * authenticity. Returns true if the user has logged in.
 */
function checkLogin(){
	/* Check if user has been remembered */
	if(isset($_COOKIE['cookname']) && isset($_COOKIE['cookpass'])){
		if (strpos($_COOKIE['cookname'],"@")) {
			$_SESSION['user_email'] = $_COOKIE['cookname'];   			
		} else {
			$_SESSION['username'] = $_COOKIE['cookname'];
		}
		$_SESSION['password'] = $_COOKIE['cookpass'];
	}
		
		/* email and password have been set */
	if(isset($_SESSION['username']) && isset($_SESSION['password'])){
		/* Confirm that email and password are valid */
		if(confirmUser($_SESSION['username'], $_SESSION['password']) != 0) {
			/* Variables are incorrect, user not logged in */
			unset($_SESSION['username']);
			unset($_SESSION['user_email']);
			unset($_SESSION['password']);
			
			$_SESSION = array();
			session_destroy();
			//         unset($_SESSION);
			return false;
		}
		//else, confirmUser=0 (confirmed):
		return true;
	} else {
		/* User not logged in - session data is not set*/
		return false;
	}
}

/**
 *Generate a random character password.
 *
 */
function generateCode($length=6) {
        $chars = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPRQSTUVWXYZ0123456789";
        $code = "";
        $clen = strlen($chars) - 1;  //a variable with the fixed length of chars correct for the fence post issue
        while (strlen($code) < $length) {
            $code .= $chars[mt_rand(0,$clen)];  //mt_rand's range is inclusive - this is why we need 0 to n-1
        }
        return $code;
}

/**
 *
 * Show who is online using sessions table
 *
 **/

function numOnline() {
	list($r,$n) = check_online_sessions();
	if (($n > 1) || ($n == 0)) {
		return "There are ".$n." users currently online";
	}
	return "There is 1 users online.";
}

function whoOnline() {
	list($rslt,$totalnum) = check_online_sessions();  //	return array(($n1+$n2),$rslt);check_online_sessions_w_users
	if ($totalnum > 0) {
		$c = 0;
		$guests = 0;
		while ($o = mysql_fetch_array($rslt)) {
			if (($o['session_user_id'] > 0)) {
				list($r,$n) = get_users_by_id_multi($o['session_user_id']);
				if ($n == '1') {
					while ($users = mysql_fetch_array($r)) {
						if ($users['show_online'] == 'y') {
							//show users's name
							$fr .= "<p class='para_smltxt' style='font-weight: bold; margin-left: 15px; padding: 3px;'>".$users['username']."</p>";
						}
					}
				} else {
					//if more than one user with the same id, show error 
					$fr .= "<p class='para_smltxt' style='font-weight: bold; margin-left: 15px; padding: 3px;'>Error...</p>";			
				}
				//increment user count 
				$c++;
			} else {
				//session user id = 0. increment guest counter 
				$guests++;
			}
		}
		if ($guests == 1) $ess = ''; else $ess = 's';
		echo "<div style='margin-right: 25px; border: 0px; margin-left: 3px; margin-bottom: 25px;'>"
			."<div style='color: #fff; font-weight: bold; margin-left: 3px; margin-top: 3px; margin-right: 3px; margin-bottom: 0px; border-bottom: 1px orange solid; background: url(nav/bg_grad.jpg) repeat-x #000;'>"
			."<b class='rtop_blk'><b class='r1'></b><b class='r2'></b><b class='r3'></b><b class='r4'></b></b>"
			."<p class='para_smltxt' style='padding-bottom: 2px; padding-left: 10px; color: #ccc;'>Users Online: ".($c+$guests)." <i>(Guest".$ess.": ".$guests.")</i></p>"
			."</div>"
			."<div style='background: #bbb; margin-right: 3px; margin-left: 3px; border: 0px; margin-top: 0px;'>"
//			."<p class='para_smltxt'>Number of users online: ".$totalnum."</p>"			
			.$fr
			."<b class='rbottom_blk'><b class='r4'></b><b class='r3'></b><b class='r2'></b><b class='r1'></b></b>"
			."</div></div>";
	} else {
		return "<div id='whoonline' class='para_medtxt' style='margin-top: 20px;'>There are 0 users online.</div>";
	}
}

/**
 * Determines whether or not to display the RESET PASSWORD form
 */
function displayForgotPwd($actionpath='index.html'){
	global $multiuserlogin;
	global $mainpage;
	global $logged_in;
	global $showerrormsg;
	global $tries;
	//get site globals
	global $site_name,$this_site,$perm_db,$contact_email;
	
	
	if(!$logged_in) {
		if (isset($_POST['resetpasswordbutton']) && isset($_POST['user'])) {
			//if email address has been submitted, then....
			
			//Enter a temporary authorization into the users table to allow the user to change their password. 
			//Confirm the temporary password later.		
			
			
			// Email the user a link to follow to reset the password
			// We must confirm that they wish to change the password before we change it.
			
			list($ru,$nu) = get_users_multi ($_POST['user'],$perm_db);
			if ($nu >0) {
				while ($rur = mysql_fetch_array($ru)) {
					//generate authorization code
					$temppwd = substr(md5(time()),0,9);
					
					//enter temp pwd into users table
					if ($rur['user_id'] > 9999) $which_db = 'master';
					else $which_db = 'local';
					update_password_resets($temppwd,$rur['user_id'],$which_db);

					//generate email re: password reset
					$message = "Dear ".$rur['user_first']." ($rur[username]): 
					
A password reset request was sent to our server for your $site_name account. If you have received this message in error, then you may ignore this email.
					
If you begin to receive this email frequently, you should notify your server administrator.
					
If you did request a password reset, then please follow this link to select your new password: ".$this_site."/login_forgotpwd.php?rp=forgotpassword&ti=".$temppwd."&u=".$rur['user_email']."
					
Sincerely, $site_name Admin Team";

					$user_email = $rur['user_email'];

					mail($user_email,"Forgotten Password at ".$site_name,$message, "From: $site_name Admin <".$contact_email.">");
	
				}
				$resultmessage = "A password reset request has been sent to your email address ($user_email). "
							."You will need to follow the instructions provided in the email to reset your password. "
							."Note that the forgotten password request email may end up in your Junk Mail folder.";
				
			} else {
				$resultmessage = "The email address you have entered does not exist in our system.";
			}         
         		?>
				<div id='loginborder' style='border: solid 3px #444; width: 500px;'>
				
				<div id='loginbox' style='background: #444; margin: 3px; padding: 3px; '>
				
				<p style='color: #ccc' class='para_lrgtxt'>
					Forgot your password?</p>
					
				<p style='color: #ccc' class='para_medtxt'>
					<?=$resultmessage; ?>
				</p>
				
				</div>
				</div>			
			<?
		} else if ($_GET['rp'] == 'forgotpassword') {
			list($ru,$nu) = get_users_multi ($_GET['u'],$perm_db);
			if ($nu >0) {
				while ($rur = mysql_fetch_array($ru)) {
					if ($_GET['ti'] == $rur['reset_auth']) {
						//validated the reset code sent via email. Now allow or complete a password reset.
						if (isset($_POST['resetpassword'])) {
							//new password has been submitted. Inform that reset is complete and perform reset.
							
							//determine which DB to change password in....
							if ($rur['user_id'] > 9999) $which_db = 'master';
							else $which_db = 'local';
							
							//reset password.
							update_users(md5($_POST['pwd']),"user_pwd",$rur['user_id'],$which_db);
							
							//erase authorization code
							update_users("","reset_auth",$rur['user_id'],$which_db);							
							
							//notify user 
							echo "<p class='para_medtxt'>Your password has been reset.<br /></p>";
							
							//display login box
							displayLogin($mainpage);
							
						} else {
							//allow user to enter new password.
							?>
							<div id='loginborder' style='border: solid 3px #444; width: 500px;'>
							<div id='loginbox' style='background: #444; margin: 3px; padding: 3px; '>
							<form action='?u=<? echo $_GET['u']."&ti=".$_GET['ti']."&rp=".$_GET['rp']; ?>' method='post'>
								<p style='color: #ccc' class='para_lrgtxt'>
									Enter New Password:
								</p>	
								<p style='color: #ccc' class='para_medtxt'>
									Enter new Password:
									<input type='password' name='pwd' id='pwd' value='' style='background: #ddd;'>
								</a>
								<p style='color: #ccc' class='para_medtxt'>
									Confirm Password:&nbsp;&nbsp;&nbsp;&nbsp;
									<input type='password' name='confirm' id='confirm' value='' style='background: #ddd;'>
								</a>								
								</p>
								<p class='para_smltxt'><br /></p>
								<p style='color: #ccc' class='para_medtxt'>
									<input type='submit' name='resetpassword' id='resetpassword' value='Reset Password'
										onclick='	var one = _gebi("pwd").value;
													var two = _gebi("confirm").value;
													if ((one == two) && (one.length >5)) {
														return true;
													} else {
														alert("Entries do not match or password is not long enough.");
														return false;
													}'>
								</a>								
								</p>
								<p class='para_smltxt' style='color: #ccc;'>NOTE: Minimum password length is 6 characters.</p>
							</form>
							</div></div>
							
							<?
						}
					} else {
						?>
						<div id='loginborder' style='border: solid 3px #444; width: 500px;'>
						<div id='loginbox' style='background: #444; margin: 3px; padding: 3px; '>							
							<p style='color: #ccc' class='para_lrgtxt'>
								Forgot your password?
							</p>	
							<p style='color: #ccc' class='para_medtxt'>
								The reset validation code is invalid for this user id. If you requested a password reset more than once, please make sure you click on the link sent to you via email most recently. You may need to try again: <a href='login_forgotpwd.php'>Click here to reset your password.</a>
<!-- 								<br/><br/>ti:<?=$_GET['ti']."<br/>rur:".$rur['reset_auth']; ?> -->
								<br/>
							</p>
						</div></div>				
						<?
					}
				} //end while
			}//end if account exists	
		} else {
?>
<form action="login_forgotpwd.php" method="post" onsubmit="post.send(this); return false;" >
<div id='loginborder' style='border: solid 3px #444; width: 500px;'>

<div id='loginbox' style='background: #444; margin: 3px; padding: 3px; '>

<p style='color: #ccc' class='para_lrgtxt'>
	Forgot your password?</p>
	
<p style='color: #ccc' class='para_smltxt'>&nbsp;</p>	
<p style='color: #ccc' class='para_medtxt'>
	Please enter your account email address:&nbsp;<input type="text" name="user" maxlength="30" id="user" style='border: 0px;'></p>
<p>
	<input onclick='var user = document.getElementById("user").value.length; 
					if (user == 0) {
						alert("Please enter an email address.");
						return false;
					} else if (isValidEmail(user)) {
						alert("An email will be sent to your email address with your new password.");
						return true;
					} else {
						alert("Please enter a valid email address.");
						return false;
					}'
	type="submit" name="resetpasswordbutton" value="Reset Password">
</p>
<p class='paragraph_style_3' style='color: red; font-weight: bold;'><?=$showerrormsg;?></p>
<p class='para_smltxt'>&nbsp;</p>

</div>

</div>
</form>

	<?
		} //resetpasswordbutton is not set so display the form to reset the password.
	} else {
		?>
			<div>
			<p class='para_medtxt'> Forgot your password? You are currently logged in....</p>
			</div>
		<?	
	}
}




/**
 * Determines whether or not to display the login
 * form or to show the user that he is logged in
 * based on if the session variables are set.
 */
 
function displayXXXLogin($actionpath=""){
	global $multiuserlogin;
	global $mainpage;
	global $logged_in;
	global $showerrormsg;
	global $tries;
	
	if(!$logged_in) {

		if ($actionpath=="") {
			$actionpath = $_SERVER['HTTP_REFERER'] ? $_SERVER['HTTP_REFERER'] : $mainpage;
		}
		
		if ( (strpos($actionpath,'logout.php')) || (strpos($actionpath,'loginsignup.php')) ) {
			$actionpath = $mainpage;
		}

?>
<form action="<?=$actionpath."?&tries=".$tries; ?>" method="post" onsubmit="post.send(this); return false;" >
<div id='loginborder' style='border: solid 3px #444; width: 250px;'>

<div id='loginbox' style='background: #444; margin: 3px; padding: 3px; '>
<!-- rounded corners -->
<!--
<b class="rtop">
	<b class="r1"></b>
	<b class="r2"></b>
	<b class="r3"></b>
	<b class="r4"></b>
</b>
-->
<!-- end rounded corners -->
<p style='color: #ccc' class='para_lrgtxt'>
	Sign In...</p>
	
<?
if ($tries > 1) {
	echo "<p style='color: #ccc' class='para_smltxt'>Number of tries: ".((($tries-1)/2)+1)."</p>";
}
?>
<p style='color: #ccc' class='para_smltxt'>&nbsp;</p>	
<p style='color: #ccc' class='para_medtxt'>
	Username:&nbsp;<input type="text" name="user" maxlength="30" id="user"></p>
<p style='color: #ccc' class='para_medtxt'>
	Password:&nbsp;&nbsp;<input type="password" name="pass" maxlength="30" id="pass"></p>
<p style='color: #ccc' class='paragraph_style_3'>
	<input type="checkbox" name="remember">Remember me next time</p>
<p>
	<input onclick='var pass = document.getElementById("pass").value.length; var user = document.getElementById("user").value.length; 
					if ((pass == 0) || (user == 0)) {
						alert("Please enter a valid email address or password.");
						return false;
					} else {
						return true;
					}'
	type="submit" name="sublogin" value="Sign In">
</p>
<p class='paragraph_style_3' style='color: red; font-weight: bold;'><?=$showerrormsg;?></p>
<p class='para_smltxt'>&nbsp;</p>
<?
if ($multiuserlogin == 'y') {
	?>
	<p style='color: #ccc' class='paragraph_style_3'><a href='loginsignup.php'>Register</a> for access to this site.</p>
<?
} else {
	echo "<p style='color: #ccc' class='paragraph_style_3'>Administrator login only. This site is not accepting registrations at this time.</p>";
}?>
<p class='para_smltxt'><br/></p>
<p class='paragraph_style_3' style='color:#ccc;'>Forget your password? <a href='login_forgotpwd.php'>Click here</a></p>


<!-- rounded corners -->
<!--
<b class="rbottom">
  <b class="r4"></b> <b class="r3"></b> <b class="r2"></b> <b class="r1"></b>
</b>	
-->
<!-- end rounded corners -->
</div>

</div>
</form>

<?
	} else {
		?>
			<div>
			<p class='para_medtxt'> You are currently logged in....</p>
			</div>
		<?	
	}
}



function displayLogin($actionpath=""){
	global $multiuserlogin;
	global $mainpage;
	global $logged_in;
	global $showerrormsg;
	global $tries;
	
	if(!$logged_in) {

		if ($actionpath=="") {
			$actionpath = $_SERVER['HTTP_REFERER'] ? $_SERVER['HTTP_REFERER'] : $mainpage;
		}
		
		if ( (strpos($actionpath,'logout.php')) || (strpos($actionpath,'loginsignup.php')) ) {
			$actionpath = $mainpage;
		}

?>

<script type='text/javascript'>
	function sub_login(event) {
		//event.preventDefault;
		//retrieve form fields
		var fields = $("#site_login_form").serializeArray()
		var jsonfields = decodeURIComponent($.param(fields));
		var url = 'login_jqcheck.php';
		
		//Post Form Data
		
/*
		$.post(url, $("#site_login_form").serialize(),
			function(data) {
//				event.preventDefault;
//				var cntnt = $(data).find("#login_jqchk").children();
//				$("#loginrsltfld").append(cntnt);
				
				
				$("#loginrsltfld").html(data);
				
				var islogged = $("#login_jqcheck").attr("data-logged");
				var islogged_le = $("#login_jqcheck").attr("data-le");
				alert(islogged+" "+islogged_le+" e");
			}
		);
		return false;
		
*/
		$("#loginbox").slideUp();
		$("#loginloading").show();
		
		
		
		//POST the form data

		$.ajax({
			type: 'POST',
			url: 'login_jqcheck.php',
			data: jsonfields,
			success: function(data) {

				//pull in data from first load
				$("#loginrsltfld").html(data);
				
				//get flag to see if password/username were correct
				var islogged_le = $("#login_jqcheck").attr("data-le");
				
				//refresh login page to check and set session data.
				$("#loginrsltfld").load('login_jqcheck.php',function() {

					//now check to see if we are successfully logged in
					var islogged = $("#login_jqcheck").attr("data-logged");
					
					//test: alert
//					alert(islogged+" "+islogged_le+" e");
					
					//if logged in, then load the main page - else load the loginpage again
					if (islogged == "y") {
						$("#pagebody").load('<?php echo $actionpath	; ?>',function() {
//							alert('Success');
		//					$("#pagebody").fadeIn("fast");
							history.pushState(null,null,'<?php echo $actionpath	; ?>');
							});		
					} else {
						$.get('loginpage.php?&le='+islogged_le,function(data) {
	//					$("#pagebody").load('loginpage.php','&le='+islogged_le,function() {
							alert('Incorrect Password or Username');
							$("#pagebody").empty();
							$("#pagebody").html(data);
							$("#pagebody").slideDown("fast");
							});
					}					
				});

//				$("#pagebody").hide();



			}
		});


	}
	
	
</script>

<div id='loginrsltfld' style='position: relative;'>
</div>
<div id='loginloading' style='display: none; position: relative;'>
	<img src='nav/load.gif' />
</div>
<form id='site_login_form' action="" method="" onsubmit='sub_login(); return false;' >
<div id='loginborder' style='position: relative; border: solid 3px #444; width: 250px;'>

<div id='loginbox' style='position: relative; background: #444; margin: 3px; padding: 3px; '>
<!-- rounded corners -->
<!--
<b class="rtop">
	<b class="r1"></b>
	<b class="r2"></b>
	<b class="r3"></b>
	<b class="r4"></b>
</b>
-->
<!-- end rounded corners -->
<p style='color: #ccc' class='para_lrgtxt'>
	Sign In...</p>
	
<?
if ($tries > 1) {
	echo "<p style='color: #ccc' class='para_smltxt'>Number of tries: ".((($tries-1)/2)+1)."</p>";
}
?>
<p style='color: #ccc' class='para_smltxt'>&nbsp;</p>	
<p style='color: #ccc' class='para_medtxt'>
	Username:&nbsp;<input type="text" name="user" maxlength="30" id="user"></p>
<p style='color: #ccc' class='para_medtxt'>
	Password:&nbsp;&nbsp;<input type="password" name="pass" maxlength="30" id="pass"></p>
<p style='color: #ccc' class='paragraph_style_3'>
	<input type="checkbox" name="remember">Remember me next time</p>
	<input type='hidden' value='x' name='sublogin'>
<p>


	<input onclick='var pass = document.getElementById("pass").value.length; var user = document.getElementById("user").value.length; 
					if ((pass == 0) || (user == 0)) {
						alert("Please enter a valid email address or password.");
						return false;
					} else {
						//$(this).submit();
						//return false;
					}'
	type="submit" name="subloginx" value="Sign In">


<!-- 	<input type='submit' name='subloginx' value='Sign In Now' /> -->
</p>
<p class='paragraph_style_3' style='color: red; font-weight: bold;'><?=$showerrormsg;?></p>
<p class='para_smltxt'>&nbsp;</p>
<?
if ($multiuserlogin == 'y') {
	?>
	<p style='color: #ccc' class='paragraph_style_3'><a href='loginsignup.php'>Register</a> for access to this site.</p>
<?
} else {
	echo "<p style='color: #ccc' class='paragraph_style_3'>Administrator login only. This site is not accepting registrations at this time.</p>";
}?>
<p class='para_smltxt'><br/></p>
<p class='paragraph_style_3' style='color:#ccc;'>Forget your password? <a href='login_forgotpwd.php'>Click here</a></p>


<!-- rounded corners -->
<!--
<b class="rbottom">
  <b class="r4"></b> <b class="r3"></b> <b class="r2"></b> <b class="r1"></b>
</b>	
-->
<!-- end rounded corners -->
</div>

</div>
</form>

<?
	} else {
		?>
			<div>
				<p class='para_medtxt'> You are currently logged in....<br/></p>
				<p class='para_medtxt'>Click here to <a href='logout.php'>logout</a>.</p>
			</div>
		<?	
	}
} //END displayLogin function 


/**
 * Checks to see if the user has submitted his
 * email and password through the login form,
 * if so, checks authenticity in database and
 * creates session.
 */
if(isset($_POST['sublogin'])){
	// Check that all fields were typed in 
	
	//destroy / update session after logging in with userid 
	delete_anon_session_on_login($_SESSION['id']);
	
	
	
	/* Checks that email is in database and password is correct */
	$md5pass = md5($_POST['pass']);
	$result = confirmUser($_POST['user'], $md5pass);
	
	/* email and password correct, register session variables */
	$_POST['user'] = stripslashes($_POST['user']);
	if (stripos($_POST['user'],'@')) {
		$_SESSION['user_email'] = $_POST['user'];
	} else {
		$_SESSION['username'] = $_POST['user'];
	}
	$_SESSION['password'] = $md5pass;
	
	/**
	* This is the cool part: the user has requested that we remember that
	* he's logged in, so we set two cookies. One to hold his email,
	* and one to hold his md5 encrypted password. We set them both to
	* expire in 100 days. Now, next time he comes to our site, we will
	* log him in automatically.
	*/
	if(isset($_POST['remember'])){
	  setcookie("cookname", $_SESSION['username'], time()+60*60*24*100, "/");
	  setcookie("cookpass", $_SESSION['password'], time()+60*60*24*100, "/");
	  setcookie("cookemail", $_SESSION['user_email'], time()+60*60*24*100, "/");
	  setcookie("user_id", $_SESSION['user_id'], time()+60*60*24*100, "/");            
	}
	
	if ((($tries-1)/2) > 1) {
		$redirect_to = $mainpage;
	} else {
		$actionpath = $_SERVER['HTTP_REFERER'] ? "?".$_SERVER['QUERY_STRING'] : $_SERVER['PHP_SELF'];
		
		if ( (strpos($actionpath,'logout.php')) || (strpos($actionpath,'loginsignup.php')) ) {
			$actionpath = $mainpage;
		}	
		$redirect_to = $actionpath;
	}
	
//XXX	return;
} //END OF IF LOGIN INFO SUBMITTED



/* Sets the value of the logged_in variable, which can be used in your code */
$logged_in = checkLogin();
$_SESSION['logged_in'] = $_SESSION['session_logged_in'] = $logged_in;

$_SESSION['rated'] = $_COOKIE['rated'];
$_SESSION['ip'] = $_SERVER['REMOTE_ADDR'];

$_SESSION['id'] = session_id();

//enter user info into sessions table so we can see who is online.
update_sessions ($_SESSION['user_id'],$dbarray['show_online']);


?>
