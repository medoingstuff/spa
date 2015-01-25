<?php
$skiphtmlheader = true;
include('header.php');
$pagename = "Staff";
$pageID = "Staff";
//include('header/pageheader.html'); //deferred to include pagename

//required for the gallery (embedded below)
$cancel_headerhtml['headerhtmlpath'] = 'header/pageheader.html';
###
##
##
#
#
#
#
$skip_headers = true; //??




	


//get staffID

//embed galleries


if (isset($_GET['staffid']) && ($_GET['staffid'] > 0)) {
	$staffid = $_GET['staffid'];
	
	//compatability with gallery parameters - ensures that staff page still shows staff info when gallery image is clicked.
	$args .= "&staffid=".$staffid;
}



if ($staffid > 0) {
	//A staffID is requested in the parameters, get related data.
	list($rstaff,$nstaff) = return_table_data("therapists"," WHERE th_id='".$staffid."' AND th_active_in_spa='y' AND th_admin_delete='n' ");
	$staff_array = fetch_data_to_array($rstaff,$nstaff); //(access as staff_array[0])
	
	if (($nstaff>0)) {
		
		##SETUP EMPLOYEE VARIABLES from database
		//employee info
		$staffid = $staff_array[0]['th_id'];
		$staffname = $staff_array[0]['th_pseudoname'];
		$staffnum = $staff_array[0]['th_licensenum'];
		$staffabout = $staff_array[0]['th_about'];
		
		//profile images
		$th_profileimg_portrait = $staff_array[0]['th_profileimg_portrait'];
		$th_profileimg_landscape = $staff_array[0]['th_profileimg_landscape'];
		$th_profileimg_sq_rnd = $staff_array[0]['th_profileimg_portrait'];

		//galleryID
		$staffgallery = $staff_array[0]['th_galleryID'];
		
		//social advertising
		$th_twitterhandle = $staff_array[0]['th_twitterhandle'];
		$th_twitterpage = $staff_array[0]['th_twitterpage'];
		$th_facebookpage = $staff_array[0]['th_facebookpage'];
		$th_instagrampage = $staff_array[0]['th_instagrampage'];
		
		//include header
		$pagename = "About ".$staffname;
		include('header/pageheader.html');
		
		
		//Check if logged in and appropriate admin level, if so, allow editing of the staff profile:
		if (($logged_in) && (true)) {
//			include('staff_editprofile.php');
		} 
		//STAFF WILL BE EDITED FROM THE Administrator Control Panel.
		
		
		
		//include html layout document for staff page - sandwhich between header and gallery and footer
		include('staff_layout.php');
		
		
		
		//INCLUDE GALLERY HERE
		if ($staffgallery > 0) {
			//we have a linked gallery for this staff member - show their pics			
			$_GET['cid'] = $cid = $staffgallery;
			?>
			<div id='staffgallery' class='pageMasterRow' style='position: relative; margin: 0px 0px; background: rgba(220,220,220,.7); padding: 20px 0px 45px 0px;'>
				<div class='pagerow' style='position: relative; margin: 0px; margin-left: 70px; margin-right: 70px;'>
					<div style='width: 1100px; float: left;'>
						<!-- margin-left: auto; margin-right: auto;'> -->
			<?php
			##
			#####################
			//<!--insert most of the galleries.php document here-->
			$loadgalleryscript = true;
			$cancel_headerhtml[0] = 'y'; 
			//get variables
			require_once('galleryfiles/gallery_vars.php');			
			$cancel_header = 'y';

			$ignore_foooter_call = 'y';
			
			include('gallery.php');
			if (($datecat && $item) || ($item) || ($cid) || (!$cid && !$item) ) echo "</div>"; //clearly it is missing in the datecat area.
			if ( ($logged_in) && ( ($_SESSION['user_access']>0) || ($_SESSION['user_id'] == 4) )) {
				if ( ((!$_GET['cid']) || ($_GET['cid'] == 0)) && (!$_GET['item']) && (!($_GET['datecat']>0)) ) {
					include('galleryfiles/ftp_upload.php');
				}
			}
				
			?>
					</div><div class='clearall'></Div>
				</div><div class='clearall'></Div>
			</div><div class='clearall'></Div>
			
			<?php					
					
		} //end if gallery is linked
		//end of page		
	} //database has the requested userID AND it is an active employee
	else {
		//staff ID is not active or does not exist
		$pagename = "Employee not found";
		include('header/pageheader.html');
		
		//*****THE Staff member that you are attempting to view is currently inactive or the profile no longer exists on our server. Please return to our main page and view one of our active employees.
		
		
	}//end if staffid
	
} //staffid > 0
else {
	//this staffID is invalid or has not been set. Show list of staff members:
	$pagename = "Staff";
	include('header/pageheader.html');
	
	/*
		Our staff members work at our studio as independent and licensed Body Rub Practitioners. They have all taken a course by the city of Edmonton to become well-versed in employment standards, regulations, and laws that apply to their work. Click on their modeling photos below to see their profile page 
		NOTE THAT THESE information displayed on these pages does not reflect the views or opinions of 50th street massage.
	*/
	
	
	
}


	
include('footer.php');

?>