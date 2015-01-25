<?php // signup.php
$pagetitle = "Register";

require('header.php');
include_once('common_error.php');


if (!($multiuserlogin == 'y')) {
	echo "<tr><td><p class='para_medtxt'>This site is not accepting registrations at this time</p></td></tr>";
	include('footer.php');
	exit;
}


if (!isset($_POST['submitok'])):
    // Display the user signup form

    ?>


<tr><td>

<p class='para_lrgtxt'>New User Registration Form</p>
<p class='para_medtxt'><font color="orangered" size="+1"><tt><b>*</b></tt></font>
   indicates a required field</p>
<form method="post" action="<?=$_SERVER['PHP_SELF']?>">
<table border="0" cellpadding="0" cellspacing="5">
    <tr>
        <td align="right" valign='top'>
            <p class='para_medtxt'>Authorization Code</p>
        </td>
        <td>
            <input id="authcode" name="authcode" type="text" maxlength="15" size="15" />
            <font color="orangered" size="+1"><tt><b>*</b></tt></font>
        </td>
    </tr>

	<tr>
		<td colspan="2">
			<hr noshade="noshade">
		</td>
	</tr>

    <tr>
        <td align="right">
            <p class='para_medtxt'>Username</p>
        </td>
        <td>
            <input id="newid" name="newid" type="text" maxlength="100" size="25" />
            <font color="orangered" size="+1"><tt><b>*</b></tt></font>
        </td>
    </tr>
    
    <tr>
        <td align="right">
            <p class='para_medtxt'>E-Mail Address</p>
        </td>
        <td>
            <input id="newemail" name="newemail" type="text" maxlength="100" size="25" />
            <font color="orangered" size="+1"><tt><b>*</b></tt></font>
        </td>
    </tr>    

    <tr>
        <td align="right">
            <p class='para_medtxt'>Show my online status?</p>
        </td>
        <td>
            <input name="show_online" type="checkbox" checked='checked' />
        </td>
    </tr>

    
    <tr>
        <td align="right">
            <p class='para_medtxt'>First Name</p>
        </td>
        <td>
            <input name="firstname" type="text" maxlength="100" size="25" />
        </td>
    </tr>

    <tr>
        <td align="right">
            <p class='para_medtxt'>Last Name</p>
        </td>
        <td>
            <input name="lastname" type="text" maxlength="100" size="25" />
        </td>
    </tr>


    <tr>
        <td align="right" colspan="2">
            <hr noshade="noshade" />
			<p class='para_smltxt'>A password will be generated and sent to your email address. You will be able to change the password after you login the first time.</p>
			<p class='para_medtxt'> 
				<input type="reset" value="Reset Form" />
				<input onclick="if ((chkstrlen($('newemail').value,5,'n','email address')) && (echeck($('newemail').value)) &&  (chkstrlen($('newid').value,3,'y','username')) && (chkstrlen($('authcode').value,4,'y','authorization code') ) ) {return true;} else return false;"
					type="submit" name="submitok" value="   OK   " />
			</p>
        </td>
    </tr>
</table>
</form>


</td></tr>
<!-- </table> -->

    <?php
    include('footer.php');
else:
    // Process signup submission

    if ($_POST['newid']=='' or $_POST['newemail']=='' or $_POST['authcode']=='') {
        error('One or more required fields were left blank.\\n'.
              'Please fill them in and try again.');
    }
    
	
	//CHECK AUTH CODES
	//check auth codes for this site only using perm_db from config.inc.php
	list($aa,$aan) = get_auth_codes($_POST['authcode']); //perm_db is passed as a global variable 
	//Full Texts  	id 	perm_db 	auth_code 	user_access 0=stduser, 1=site admin, 2=admin all sites	code_expiry_date	    
    if ($aan > 0) {
		$authc = mysql_fetch_array($aa);
		$newdate = date('Y-m-d');
		if (($authc['code_expiry_date'] < $newdate) && ($authc['code_expiry_date'] > '0000-00-00')) {
			error('This authorization code has expired.');
		}
		$user_access = $authc['user_access'];
    } else if ($aan == 0) {
    	error('This authorization code does not exist.');
    } else {
    	error('A database error has occured. If this error persists please contact the site administrator.');
    }
    
    
    // Check for existing user with the new id
	if ($xxx = check_for_user($_POST['newid'],"username",$multiuserlogin)) {
        error('A user already exists with your chosen username.\\n'.
              'Please try another.');
    }

    // Check for existing email with the new email
	if ($xxx = check_for_user($_POST['newemail'],"user_email",$multiuserlogin)) {
        error('This email address already exists in our system. Please contact the administrator if you have forgotten your password.\\n'.
              'Or please try another email address.');
    }    

    $newpass = substr(md5(time()),0,6);

    if ($user_access > 0) $db = $masterdb;
    if ($user_access == 3) $perm_db = 'all'; //this will not be enabled
    if ($_POST['show_online'] != 'y') $showonline = 'n';
    
   	if ( ! new_user($_POST['newid'],$newpass,$_POST['newemail'],$_POST['firstname'],$_POST['lastname'],$showonline,$user_access,$perm_db,$db) )
        die('A database error occurred in processing your '.
              'submission.\\nIf this error persists, please '.
              'contact you@example.com.\\n' . mysql_error());
              
    // Email the new password to the person.
    $message = "Dear $_POST[firstname]:

Your personal account for the $site_name website
has been created! To log in, proceed to the
following address:

    ".$this_site."loginpage.php

Your personal login ID and password are as
follows:

    username: $_POST[newid]
    email:    $_POST[newemail]
    password: $newpass

You aren't stuck with this password! Your can
change it at any time after you have logged in.

";

    mail($_POST['newemail'],"Your Password for the ".$site_name." Website",
         $message, "From: ".$site_name." Admin <".$contact_email.">");
         
    ?>
	<tr><td>
    <p class='para_lrgtxt'>Registration Successful</p>	
    <p class='para_medtxt'>&nbsp;</p>
    <p class='para_medtxt'>Your username and random temporary password have been sent to
       <strong><?=$_POST['newemail']?></strong>, the email address
       you just provided in the registration form. Login below: </p>
<?
	displayLogin();
?>
	</td></tr>
<!--
	</table>
	</td></tr>

-->    <?php
    include('footer.php');
endif;
?>
