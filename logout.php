<?php
session_start(); 

$pagetitle = "Logout";
/**
 * Delete cookies - the time must be in the past,
 * so just negate what you added when creating the
 * cookie.
 */
if(isset($_COOKIE['cookname']) && isset($_COOKIE['cookpass'])){
   setcookie("cookname", "", time()-60*60*24*100, "/");
   setcookie("cookpass", "", time()-60*60*24*100, "/");
   setcookie("cookemail", "", time()-60*60*24*100, "/");
}
include('header.php');
//include("config.inc.php");
//include("login.php");
$skip_headers = true;



?>

<?php



if(!$logged_in){

	echo "<tr><td>";
   echo "<h1>Error!</h1>\n";
   echo "You are not currently logged in, logout failed. <a href='loginpage.php'>Login.</a>";
}
else{
   /* Kill session variables */
   unset($_SESSION['email']);
   unset($_SESSION['password']);
   unset($_SESSION['username']);
   $_SESSION = array(); // reset session array
   session_destroy();   // destroy session.
   
   //include('header.php');
   echo "<h1>Logged Out</h1>\n";
	echo "<tr><td>";
   echo "You have successfully <b>logged out</b>.<br/><br/>";
   echo "<a href='loginpage.php' style='color: #000;'>Sign in again...</a>";
}
echo "</td></tr>";
include('footer.php');
?>
