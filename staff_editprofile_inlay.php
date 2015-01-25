<?php
//staff_editprofile_inlay.php



if ($skip_headers) {	
} else {
	include_once("config.inc.php");
	session_start();
	include_once("supporting_queries.php");
}



$staff_ids_to_hide = "almosttop,staff,studio,contact,legal";
$staff_spawnbefore = "almosttop";

//Get list of staff members
list($rstaff2,$nstaff2) = return_table_data("therapists"," WHERE th_admin_delete='n' ","ORDER BY th_active_in_spa ASC, th_pseudoname ASC");

//obtain $nstaff2 and $rstaff2 from staff_list.php (called before this document in index.php

if ($nstaff2>0) {
	//while($rs = mysql_fetch_array($rstaff)) {}
	$staffarray = fetch_data_to_array($rstaff2,$nstaff2);
	
	foreach($staffarray as $sa) {
		
		
		//SET VARIABLES:
		$staffid = $sa['th_id'];
		$staffname = $sa['th_pseudoname'];
		$staffnum = $sa['th_licensenum'] ? $sa['th_licensenum'] : "**Click to add a license #**";
		$staffabout = $sa['th_about'] ? $sa['th_about'] : "*CLICK HERE TO ADD INFO ABOUT THIS EMPLOYEE*";
		
		//profile images
		$th_profileimg_portrait = $sa['th_profileimg_portrait'];
		$th_profileimg_landscape = $sa['th_profileimg_landscape'];
		$th_profileimg_sq_rnd = $sa['th_profileimg_portrait'];

		//galleryID
		$staffgallery = $sa['th_galleryID'];
		
		//social advertising
		$th_twitterhandle = ($sa['th_twitterhandle']) ? ($sa['th_twitterhandle']) : "**add twitter handle**";
		$th_twitterpage = $sa['th_twitterpage'] ? $sa['th_twitterpage'] : "**add twitter handle**";
		$th_facebookpage = $sa['th_facebookpage'] ? $sa['th_facebookpage'] : "**add facebook page**";
		$th_instagrampage = $sa['th_instagrampage'] ? $sa['th_instagrampage'] : "**add instagram page**";
		
		
		include('staff_editprofile_inlay-browsers.php');
		
		?>
		<div id='editProfileID<?php echo $staffid; ?>' class='editProfileIDs img_roundtop' style='display: none; margin-bottom: 15px; width: 540px; background: rgba(255,255,255,.15); padding: 10px 0px;'>
			<div style='padding: 5px;'>
				<p class='righttext'><a href='#' class='showhidden' data-showhiddenid='editProfileID<?php echo $staffid; ?>'><img src="nav/x.gif" alt="x" width="20" height="20" />Close Tray</a>
				<h2>&nbsp</h2>
				
				
				<!-- Allow editing of the StaffName -->
				<h2 class='b'>
					<span id='edit_staffname<?php echo $staffid; ?>' class='clickspan_to_edit' data-results='edit_staffname<?php echo $staffid; ?>rslt' data-updaterows='update_rows.php' data-otherargs='&table=therapists&row=th_pseudoname&idprop=th_id&updatechkboxes=y&idval=<?php echo $staffid; ?>'><?php echo stripslashes($staffname); ?></span></h2>
					<p><span id='edit_staffname<?php echo $staffid; ?>rslt'></span></p>
					
				<!--Allow Editing of the license #-->	
				<p class=''>License #:&nbsp;
					<span id='edit_licensenum<?php echo $staffid; ?>' class='clickspan_to_edit' data-results='edit_licensenum<?php echo $staffid; ?>rslt' data-updaterows='update_rows.php' data-otherargs='&table=therapists&row=th_licensenum&idprop=th_id&updatechkboxes=y&idval=<?php echo $staffid; ?>'><?php echo $staffnum; ?></span>
					&nbsp;&nbsp;<span id='edit_licensenum<?php echo $staffid; ?>rslt'></span></p>
					
<!-- 					<p><span id='edit_staffname<?php echo $staffid; ?>rslt'></span></p> -->

				<div style='clear: both; float: none; height: 10px; '></div>

				<!-- UPDATE TEXT -->
				<h3 class='b'>Update Text</h3>
				<p>Click on the employee name or number to edit the information displayed on the website. Click in the text box below to edit the employee bio.</p>
				<!-- EDIT TEXT -->
				<div id=''>
					
					<div id='mcewrapper<?php echo $staffid; ?>' style='position: relative;'>
						<!--CE TEXT EDITOR-->
						
						
						
						<!-- #### FIX variables -->
						
						
						
						<div id='cetext<?php echo $staffid; ?>' name='cetext<?php echo $staffid; ?>' contentEditable='true' class='tabck nav-description aboutemployee' style='position: relative; margin: 15px;' data-saveparams='{"insertnewid":"y","idofspaninresults":"insertID<?php echo $ttt; ?>","preventslideup":"y","otherparams":"&updatechkboxes=y&table=therapists&row=th_about&idprop=th_id&idval=<?php echo $staffid; ?>&th_id=<?php echo $staffid; ?>","ttpath":"update_rows.php","nid":"<?php echo $staffid; ?>","tid":"<?php echo $staffid; ?>","tt":"<?php echo $ttt; ?>","posturl":"update_rows.php"}'><?php echo stripslashes($staffabout); ?></div>
					</div>
					<div class='clearall' style='height: 10px;'></div>
					<!-- Save Button-->
					<p><a href='#' class='Greenbtn' onclick='CKEditor_content_to_jQ_inline("cetext<?php echo $staffid; ?>","#cetext<?php echo $staffid; ?>","#mce_results<?php echo $staffid; ?>","#mcewrapper<?php echo $staffid; ?>"); return false;' style='color: white; margin-left: 15px;'>SAVE</a></p>
					<div class='clearall' style='height: 10px;'></div>
					<div id='mce_results<?php echo $staffid; ?>' style='height: 10px;'></div>
					<div id='mce_results<?php echo $staffid; ?>2' style='height: 10px;'></div>
					<div class='clearall' style='height: 10px;'></div>
					
					<!--Allow Editing of the TWITTER HANDLE-->	
					<p class=''>Twitter @:&nbsp;
						<span id='edit_twitterhandle<?php echo $staffid; ?>' class='clickspan_to_edit' data-results='edit_twitterhandle<?php echo $staffid; ?>rslt' data-updaterows='update_rows.php' data-otherargs='&table=therapists&row=th_twitterhandle&idprop=th_id&updatechkboxes=y&idval=<?php echo $staffid; ?>'><?php echo $th_twitterhandle; ?></span>
						&nbsp;&nbsp;<span id='edit_twitterhandle<?php echo $staffid; ?>rslt'></span></p>
					
					<!--Allow Editing of the FACEBOOK page -->	
					<p class=''>Facebook Page:&nbsp;
						<span id='edit_facebookpage<?php echo $staffid; ?>' class='clickspan_to_edit' data-results='edit_facebookpage<?php echo $staffid; ?>rslt' data-updaterows='update_rows.php' data-otherargs='&table=therapists&row=th_facebookpage&idprop=th_id&updatechkboxes=y&idval=<?php echo $staffid; ?>'><?php echo $th_facebookpage; ?></span>
						&nbsp;&nbsp;<span id='edit_facebookpage<?php echo $staffid; ?>rslt'></span></p>
					
					<!--Allow Editing of the INSTAGRAM page -->	
					<p class=''>Instagram Account @:&nbsp;
						<span id='edit_instagrampage<?php echo $staffid; ?>' class='clickspan_to_edit' data-results='edit_instagrampage<?php echo $staffid; ?>rslt' data-updaterows='update_rows.php' data-otherargs='&table=therapists&row=th_instagrampage&idprop=th_id&updatechkboxes=y&idval=<?php echo $staffid; ?>'><?php echo $th_instagrampage; ?></span>
						&nbsp;&nbsp;<span id='edit_instagrampage<?php echo $staffid; ?>rslt'></span></p>
					
				</div>
				<div style='clear: both; float: none; height: 10px; '></div>
				
				<h3 class='b'>Update Feature Images</h3>
				<p>Select Feature images with different orientations to be used in different places on the website. For example, the landscape image may be used on the front page schedule.</p>
			</div>
			<div class='clearall'></div>
			<!-- EDIT IMAGES -->
			<div id=''>
				<!-- IMAGE BROWSERS -->
				
				<div style='position: relative; float: left; width: 270px;'>
					<div style='position: relative; border: 1px solid #999; margin: 5px; width: 260px; background: rgba(255,255,255,.35);'>
						<?php echo $portrait_img_brsr; ?>
					</div><div class='clearall' style='clear: both; float: none;'></div>
				</div>

				<div style='position: relative; float: left; width: 270px;'>
					<div style='position: relative; border: 1px solid #999; margin: 5px; width: 260px; background: rgba(255,255,255,.35);'>
						<?php echo $landscape_img_brsr; ?>
					</div><div class='clearall' style='clear: both; float: none;'></div>
				</div>
				
				<!-- clear row -->
				<div class='clearall' style='clear: both; float: none;'></div>

				<div style='position: relative; float: left; width: 270px;'>
					<div style='position: relative; border: 1px solid #999; margin: 5px; width: 260px; background: rgba(255,255,255,.35);'>
						<?php echo $square_img_brsr; ?>
					</div><div class='clearall' style='clear: both; float: none;'></div>
				</div>

				<div style='position: relative; float: left; width: 270px;'>
					<div style='position: relative; border: 1px solid #999; margin: 5px; width: 260px; background: rgba(255,255,255,.35);'>
						<p class='psmall'>Use this function to select an image gallery separate from the gallery currently linked to this employee. <span class='redtext'>It is extremely unlikely that you will need to use this function.</span></p>

						<?php echo $gallery_selector; ?>
					</div><div class='clearall' style='clear: both; float: none;'></div>
				</div>
				<!-- clear row -->
				<div class='clearall' style='clear: both; float: none;'></div>

			</div>
			<div style='clear: both; float: none;'></div>
			
			<div style='padding: 15px 5px;'>
			<!-- DELETE PROFILE -->
				<p class='centertext'>
					<a href='#' class='Redbtn deletestaff' data-galleryid='<?php echo $staffgallery; ?>' data-staffid='<?php echo $staffid; ?>'>DELETE PROFILE</a>
					<span id='deleterslts<?php echo $staffid; ?>'></span>
				</p>
			</div>			
			
		</div>
		<div style='clear: both; float: none;'></div>

		<?php
	}
}


  
//
?>
