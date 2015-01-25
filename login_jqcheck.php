<?php

session_start();
require('config.inc.php');
include('login.php');

//$jqloggedin = ($logged_in) ? '"y"' : '"n"';
$jqloggedin = ($logged_in) ? "y" : "n";

echo "<div id='login_jqchk'><div id='login_jqcheck' data-logged='".$jqloggedin."' data-le='".$result."' style='position: relative;'></div>";

//echo json_encode(array("jqloggedin"=>"yx","u"=>"2pm"));
//echo json_encode(array("jqloggedin"=>$jqloggedin,"u"=>"2pm"));

//echo $logged_in."#### p:".$_POST['pass']." u:".$_POST['user']." s:".$_POST['sublogin']." jqlogged:".$jqloggedin." r:".$result;

echo "</div>";
?>