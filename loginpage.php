<?
$pagetitle = "Login";
include('header.php');

?>


<tr><td>

<?

/*
if ($logged_in) {
	?>
	<p class='para_medtxt'>You are already logged in...</p>
	<p class='para_lrgtxt'>&nbsp;</p>
	<p class='para_lrgtxt'>&nbsp;</p>	
	<?
} else {
	displayLogin();
}
*/
echo "<div style='margin-left: 20px;'>";
displayLogin();
echo "</div>";
?>
</td></tr>
<!-- </table> -->
<?

include('footer.php');

?>


