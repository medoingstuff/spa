<?php
/*
 * staff_addnew.php
 * 
 *
 * HISTORY:
 //Updated: December 14, 2014 (kings)
 *
 */
  
 
 
 ?>
<form id='addnewstaffform' name='addnewstaffform' method='post' action=''>
	<p class='small'>
		<label for='therapistname'>Therapist Name:</label>
		<input type='text' id='therapistname'>
	</p>
	
	<p class='small'>
		<label for='licensenumber'>License Number:</label>
		<input type='text' id='licensenumber'>
	</p>
	
	<p class='small'>
		<label for='aboutgirl'>About this therapist:</label>
		<textarea id='aboutgirl' style='width: 450px; height: 120px;'></textarea>
		<input type='hidden' name='entity' value='<?php echo $entity; ?>' id='entity' />
	</p>
	
	<p class='small'>
<!--
		<label for='twitter'>Twitter Name:</label>
		<input type='text' id='twitter'>
-->
	</p>
	<p class='psmall it'><Br/>After you click "Add New", this profile will be added to the schedule list (above) and a Gallery and a Staff profile page will be automatically created. Further editing of the profile or photos can be done from those pages directly.<br/></p>
	<div class='Greenbtn addnewstaff centertext' style='width: 130px;'>Add New</div>
	<div id='musicrequestrslt'><p>&nbsp;</p></div>
</form>