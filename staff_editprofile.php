<?php
//staff_layout.php

//#####################JANUARY 2015##################################
//###################################################################
//###################################################################
//##
//##THIS DOCUMENT IS NOT IN USE - REFER TO staff_editprofile_inlay.php
//##
//###################################################################
//###################################################################

$staff_ids_to_hide = "staff,staffgallery";
$staff_spawnbefore = "staffeditor";



/*	##################################### */
/*	##################################### */  // PORTRAIT

		$iBrowserParams = array();
		$iBrowserParams['albumimageid'] = $th_profileimg_portrait;
		$iBrowserParams['browser_sub_type'] = "addimgs";
		$iBrowserParams['browsertype'] = "i";
		$iBrowserParams['thisbrowserid'] = "newportraitimage".$staffid;
		$iBrowserParams['instructions'] = "Select a portrait image (tall and narrow):";
		$iBrowserParams['currentgalname'] = urlencode(stripslashes($staffname." Gallery"));
		$iBrowserParams['startingGalleryCID'] = (($staffgallery) ? $staffgallery : 0); //set to cid of currently linked gallery if viewing type=c
//		$iBrowserParams['listOfImageIds'] = $listOfImageIds;
//		$iBrowserParams['arrayOfImageIds'] = $arrayOfImageIds;
//		$iBrowserParams['arrayOfImgDBIds'] = $arrayOfImgDBIds;
		$iBrowserParams['srcid'] = $staffid;
		$iBrowserParams['table'] = "therapists";
		$iBrowserParams['row'] = "th_profileimg_portrait";
		$iBrowserParams['resultsdiv'] = $iBrowserParams['thisbrowserid']."_results";
		############
		$iBrowserParams['final_script_loc'] = "update_rows.php";
		############
		$iBrowserParams['multi_delete_path'] = "&edit=y&table=tripslides&row=x&value=y&showresults=y&id=".$iBrowserParams['srcid']."&deletenewsimage2="; //NOTE THIS PATH requires the img ID that is to be deleted to be placed at the end - this is done in most of the scripts
			
//		print_r($iBrowserParams['arrayOfImageIds']);
		$ibrowser = "<div class='ibrowser_loadwrapper' id='ibrowser_loadwrapper' style='position: relative;'>"
			."<div id='".$iBrowserParams['thisbrowserid']."' class='ibrowser' data-params='{"
				."\"albumimageid\":\"".$iBrowserParams['albumimageid']."\","
				."\"browser_sub_type\":\"".$iBrowserParams['browser_sub_type']."\","
				."\"browsertype\":\"".$iBrowserParams['browsertype']."\","
				."\"edit\":\"y\","
				."\"logged_in\":\"".$logged_in."\","
				."\"pgwth\":\"".SITE_PAGEWIDTH."\","
				."\"thisbrowserid\":\"".$iBrowserParams['thisbrowserid']."\","
				."\"currentgalname\":\"".$iBrowserParams['galname']."\","
				."\"final_script_loc\":\"".$iBrowserParams['final_script_loc']."\","
				."\"table\":\"".$iBrowserParams['table']."\","
				."\"row\":\"".$iBrowserParams['row']."\","
				."\"rw\":\"".$iBrowserParams['row']."\","
				."\"id\":\"".$iBrowserParams['srcid']."\","
				."\"resultsdiv\":\"".$iBrowserParams['resultsdiv']."\"," 
				."\"instructions\":\"".$iBrowserParams['instructions']."\","
				."\"spawnbeforeelement\":\"".$staff_spawnbefore."\","
				//make a list of ids to hide jquery style - separate with commas
				."\"ids_to_hide\":\"".$staff_ids_to_hide."\","
				."\"multi_delete_path\":\"".$iBrowserParams['multi_delete_path']."\","
//												."\"\":\"\","
//				."\"imgidarray\":{".$iBrowserParams['listOfImageIds']."}," //list of image IDs - 0 or more
				."\"imgpatharray\":{\"x\":\"y\"}"
				."}' data-lastgallery='".$iBrowserParams['startingGalleryCID']."' data-loaded='n' style='padding: 10px; position: relative;'>";
			//.'<img src="'.$thisdomain.'galleryfiles/icons/load.gif" alt="load" width="37" height="37" />'
		include('galleryfiles/forms/image_browser_form_v10.php'); //returns $iBrowserv10
		$ibrowser .= $iBrowserv10;
		$ibrowser .= "<div style='float: none; clear: both;'></div>";
		$ibrowser .= "</div><div style='float: none; clear: both;'></div></div>";
		unset($iBrowserv10);
		//END NEW BROWSER
 							 
$portrait_img_brsr = "<div id='portraitWRAP".$trip_main_img_id."'>"
//		.'<img src="galleryfiles/icons/load.gif" alt="load" width="37" height="37" />'
		.$ibrowser
		."</div>";
unset($ibrowser);
/*	##################################### */
/*	##################################### */


/*	##################################### */
/*	##################################### */	/* LANDSCAPE */

		$iBrowserParams = array();
		$iBrowserParams['albumimageid'] = $th_profileimg_landscape;
		$iBrowserParams['browser_sub_type'] = "addimgs";
		$iBrowserParams['browsertype'] = "i";
		$iBrowserParams['thisbrowserid'] = "newLandscapeImage".$staffid;
		$iBrowserParams['instructions'] = "Select a landscape image (wide and short):";
		$iBrowserParams['currentgalname'] = urlencode(stripslashes($staffname." Gallery"));
		$iBrowserParams['startingGalleryCID'] = (($staffgallery) ? $staffgallery : 0); //set to cid of currently linked gallery if viewing type=c
//		$iBrowserParams['listOfImageIds'] = $listOfImageIds;
//		$iBrowserParams['arrayOfImageIds'] = $arrayOfImageIds;
//		$iBrowserParams['arrayOfImgDBIds'] = $arrayOfImgDBIds;
		$iBrowserParams['srcid'] = $staffid;
		$iBrowserParams['table'] = "therapists";
		$iBrowserParams['row'] = "th_profileimg_landscape";
		$iBrowserParams['resultsdiv'] = $iBrowserParams['thisbrowserid']."_results";
		############
		$iBrowserParams['final_script_loc'] = "update_rows.php";
		############
		$iBrowserParams['multi_delete_path'] = "&edit=y&table=tripslides&row=x&value=y&showresults=y&id=".$iBrowserParams['srcid']."&deletenewsimage2="; //NOTE THIS PATH requires the img ID that is to be deleted to be placed at the end - this is done in most of the scripts
			
//		print_r($iBrowserParams['arrayOfImageIds']);
		$ibrowser = "<div class='ibrowser_loadwrapper' id='ibrowser_loadwrapper' style='position: relative;'>"
			."<div id='".$iBrowserParams['thisbrowserid']."' class='ibrowser' data-params='{"
				."\"albumimageid\":\"".$iBrowserParams['albumimageid']."\","
				."\"browser_sub_type\":\"".$iBrowserParams['browser_sub_type']."\","
				."\"browsertype\":\"".$iBrowserParams['browsertype']."\","
				."\"edit\":\"y\","
				."\"logged_in\":\"".$logged_in."\","
				."\"pgwth\":\"".SITE_PAGEWIDTH."\","
				."\"thisbrowserid\":\"".$iBrowserParams['thisbrowserid']."\","
				."\"currentgalname\":\"".$iBrowserParams['galname']."\","
				."\"final_script_loc\":\"".$iBrowserParams['final_script_loc']."\","
				."\"table\":\"".$iBrowserParams['table']."\","
				."\"row\":\"".$iBrowserParams['row']."\","
				."\"rw\":\"".$iBrowserParams['row']."\","
				."\"id\":\"".$iBrowserParams['srcid']."\","
				."\"resultsdiv\":\"".$iBrowserParams['resultsdiv']."\"," 
				."\"instructions\":\"".$iBrowserParams['instructions']."\","
				."\"spawnbeforeelement\":\"".$staff_spawnbefore."\","
				//make a list of ids to hide jquery style - separate with commas
				."\"ids_to_hide\":\"".$staff_ids_to_hide."\","
				."\"multi_delete_path\":\"".$iBrowserParams['multi_delete_path']."\","
//												."\"\":\"\","
//				."\"imgidarray\":{".$iBrowserParams['listOfImageIds']."}," //list of image IDs - 0 or more
				."\"imgpatharray\":{\"x\":\"y\"}"
				."}' data-lastgallery='".$iBrowserParams['startingGalleryCID']."' data-loaded='n' style='padding: 10px; position: relative;'>";
			//.'<img src="'.$thisdomain.'galleryfiles/icons/load.gif" alt="load" width="37" height="37" />'
		include('galleryfiles/forms/image_browser_form_v10.php'); //returns $iBrowserv10
		$ibrowser .= $iBrowserv10;
		$ibrowser .= "<div style='float: none; clear: both;'></div>";
		$ibrowser .= "</div><div style='float: none; clear: both;'></div></div>";
		unset($iBrowserv10);
		//END NEW BROWSER
 							 
$landscape_img_brsr = "<div id='landscapeWRAP".$staffid."'>"
//		.'<img src="galleryfiles/icons/load.gif" alt="load" width="37" height="37" />'
		.$ibrowser
		."</div>";
unset($ibrowser);
/*	##################################### */
/*	##################################### */


/*	##################################### */
/*	##################################### */	/* SQUARE */

		$iBrowserParams = array();
		$iBrowserParams['albumimageid'] = $th_profileimg_sq_rnd;
		$iBrowserParams['browser_sub_type'] = "addimgs";
		$iBrowserParams['browsertype'] = "i";
		$iBrowserParams['thisbrowserid'] = "newSquareImage".$staffid;
		$iBrowserParams['instructions'] = "Select a square image:";
		$iBrowserParams['currentgalname'] = urlencode(stripslashes($staffname." Gallery"));
		$iBrowserParams['startingGalleryCID'] = (($staffgallery) ? $staffgallery : 0); //set to cid of currently linked gallery if viewing type=c
//		$iBrowserParams['listOfImageIds'] = $listOfImageIds;
//		$iBrowserParams['arrayOfImageIds'] = $arrayOfImageIds;
//		$iBrowserParams['arrayOfImgDBIds'] = $arrayOfImgDBIds;
		$iBrowserParams['srcid'] = $staffid;
		$iBrowserParams['table'] = "therapists";
		$iBrowserParams['row'] = "th_profileimg_sq_rnd";
		$iBrowserParams['resultsdiv'] = $iBrowserParams['thisbrowserid']."_results";
		############
		$iBrowserParams['final_script_loc'] = "update_rows.php";
		############
		$iBrowserParams['multi_delete_path'] = "&edit=y&table=tripslides&row=x&value=y&showresults=y&id=".$iBrowserParams['srcid']."&deletenewsimage2="; //NOTE THIS PATH requires the img ID that is to be deleted to be placed at the end - this is done in most of the scripts
			
//		print_r($iBrowserParams['arrayOfImageIds']);
		$ibrowser = "<div class='ibrowser_loadwrapper' id='ibrowser_loadwrapper' style='position: relative;'>"
			."<div id='".$iBrowserParams['thisbrowserid']."' class='ibrowser' data-params='{"
				."\"albumimageid\":\"".$iBrowserParams['albumimageid']."\","
				."\"browser_sub_type\":\"".$iBrowserParams['browser_sub_type']."\","
				."\"browsertype\":\"".$iBrowserParams['browsertype']."\","
				."\"edit\":\"y\","
				."\"logged_in\":\"".$logged_in."\","
				."\"pgwth\":\"".SITE_PAGEWIDTH."\","
				."\"thisbrowserid\":\"".$iBrowserParams['thisbrowserid']."\","
				."\"currentgalname\":\"".$iBrowserParams['galname']."\","
				."\"final_script_loc\":\"".$iBrowserParams['final_script_loc']."\","
				."\"table\":\"".$iBrowserParams['table']."\","
				."\"row\":\"".$iBrowserParams['row']."\","
				."\"rw\":\"".$iBrowserParams['row']."\","
				."\"id\":\"".$iBrowserParams['srcid']."\","
				."\"resultsdiv\":\"".$iBrowserParams['resultsdiv']."\"," 
				."\"instructions\":\"".$iBrowserParams['instructions']."\","
				."\"spawnbeforeelement\":\"".$staff_spawnbefore."\","
				//make a list of ids to hide jquery style - separate with commas
				."\"ids_to_hide\":\"".$staff_ids_to_hide."\","
				."\"multi_delete_path\":\"".$iBrowserParams['multi_delete_path']."\","
//												."\"\":\"\","
//				."\"imgidarray\":{".$iBrowserParams['listOfImageIds']."}," //list of image IDs - 0 or more
				."\"imgpatharray\":{\"x\":\"y\"}"
				."}' data-lastgallery='".$iBrowserParams['startingGalleryCID']."' data-loaded='n' style='padding: 10px; position: relative;'>";
			//.'<img src="'.$thisdomain.'galleryfiles/icons/load.gif" alt="load" width="37" height="37" />'
		include('galleryfiles/forms/image_browser_form_v10.php'); //returns $iBrowserv10
		$ibrowser .= $iBrowserv10;
		$ibrowser .= "<div style='float: none; clear: both;'></div>";
		$ibrowser .= "</div><div style='float: none; clear: both;'></div></div>";
		unset($iBrowserv10);
		//END NEW BROWSER
 							 
$square_img_brsr = "<div id='squareWRAP".$staffid."'>"
//		.'<img src="galleryfiles/icons/load.gif" alt="load" width="37" height="37" />'
		.$ibrowser
		."</div>";
unset($ibrowser);
/*	##################################### */
/*	##################################### */


/*	##################################### */
/*	##################################### */	/* galleryselector */
		$iBrowserParams = array();
		$iBrowserParams['albumimageid'] = $th_profileimg_sq_rnd;
		$iBrowserParams['browser_sub_type'] = "NONE";
		$iBrowserParams['browsertype'] = "c";
		$iBrowserParams['thisbrowserid'] = "galleryselector".$staffid;
		$iBrowserParams['instructions'] = "Select a gallery:";
		$iBrowserParams['currentgalname'] = urlencode(stripslashes($staffgallery));
		$iBrowserParams['startingGalleryCID'] = 0; //set to cid of currently linked gallery if viewing type=c
//		$iBrowserParams['listOfImageIds'] = $listOfImageIds;
//		$iBrowserParams['arrayOfImageIds'] = $arrayOfImageIds;
//		$iBrowserParams['arrayOfImgDBIds'] = $arrayOfImgDBIds;
		$iBrowserParams['srcid'] = $staffid;
		$iBrowserParams['table'] = "therapists";
		$iBrowserParams['row'] = "th_profileimg_sq_rnd";
		$iBrowserParams['resultsdiv'] = $iBrowserParams['thisbrowserid']."_results";
		############
		$iBrowserParams['final_script_loc'] = "update_rows.php";
		############
		$iBrowserParams['multi_delete_path'] = "&edit=y&table=tripslides&row=x&value=y&showresults=y&id=".$iBrowserParams['srcid']."&deletenewsimage2="; //NOTE THIS PATH requires the img ID that is to be deleted to be placed at the end - this is done in most of the scripts
			
//		print_r($iBrowserParams['arrayOfImageIds']);
		$ibrowser = "<div class='ibrowser_loadwrapper' id='ibrowser_loadwrapper' style='position: relative;'>"
			."<div id='".$iBrowserParams['thisbrowserid']."' class='ibrowser' data-params='{"
				."\"albumimageid\":\"".$iBrowserParams['albumimageid']."\","
				."\"browser_sub_type\":\"".$iBrowserParams['browser_sub_type']."\","
				."\"browsertype\":\"".$iBrowserParams['browsertype']."\","
				."\"edit\":\"y\","
				."\"logged_in\":\"".$logged_in."\","
				."\"pgwth\":\"".SITE_PAGEWIDTH."\","
				."\"thisbrowserid\":\"".$iBrowserParams['thisbrowserid']."\","
				."\"currentgalname\":\"".$iBrowserParams['galname']."\","
				."\"final_script_loc\":\"".$iBrowserParams['final_script_loc']."\","
				."\"table\":\"".$iBrowserParams['table']."\","
				."\"row\":\"".$iBrowserParams['row']."\","
				."\"rw\":\"".$iBrowserParams['row']."\","
				."\"id\":\"".$iBrowserParams['srcid']."\","
				."\"resultsdiv\":\"".$iBrowserParams['resultsdiv']."\"," 
				."\"instructions\":\"".$iBrowserParams['instructions']."\","
				."\"spawnbeforeelement\":\"".$staff_spawnbefore."\","
				//make a list of ids to hide jquery style - separate with commas
				."\"ids_to_hide\":\"".$staff_ids_to_hide."\","
				."\"multi_delete_path\":\"".$iBrowserParams['multi_delete_path']."\","
//												."\"\":\"\","
//				."\"imgidarray\":{".$iBrowserParams['listOfImageIds']."}," //list of image IDs - 0 or more
				."\"imgpatharray\":{\"x\":\"y\"}"
				."}' data-lastgallery='".$iBrowserParams['startingGalleryCID']."' data-loaded='n' style='padding: 10px; position: relative;'>";
			//.'<img src="'.$thisdomain.'galleryfiles/icons/load.gif" alt="load" width="37" height="37" />'
		include('galleryfiles/forms/image_browser_form_v10.php'); //returns $iBrowserv10
		$ibrowser .= $iBrowserv10;
		$ibrowser .= "<div style='float: none; clear: both;'></div>";
		$ibrowser .= "</div><div style='float: none; clear: both;'></div></div>";
		unset($iBrowserv10);
		//END NEW BROWSER
 							 
$gallery_selector = "<div id='galleryWRAP".$staffid."'>"
//		.'<img src="galleryfiles/icons/load.gif" alt="load" width="37" height="37" />'
		.$ibrowser
		."</div>";
unset($ibrowser);
/*	##################################### */
/*	##################################### */




//
?>

<!-- ################	FADED ROW	################	-->	
<div id='staffeditorToggle' class='pageMasterRow' style='position: relative; margin: 0px 0px; background: rgba(220,220,220,.95); padding: 5px 0px 7px 0px;'>
	<div class='pagerow' style='position: relative; margin: 0px; margin-left: 70px; margin-right: 70px;'>
		<p class='small righttext'><a href='#' class='showhidden' data-showhiddenid='staffeditor'>Close this Row</a></p>
	</div>
</div>

<div id='staffeditor' class='pageMasterRow' style='position: relative; margin: 0px 0px; background: rgba(220,220,220,.95); padding: 10px 0px 5px 0px;'>
	<div class='pagerow' style='position: relative; margin: 0px; margin-left: 70px; margin-right: 70px;'>
<!-- 		<div class='imagemenu' style='position: relative; float: left; width: 420px;'> -->
			<h2 class='b uu'>Welcome, Administrator</h2>
			<div class='clearall' style='clear: both; float: none;'></div>
<!-- 		</div> -->
		<div class='clearall' style='clear: both; float: none;'></div>

	</div>
	<div class='clearall' style='clear: both; float: none;'></div>

	<div class='pagerow' style='position: relative; margin: 0px; margin-left: 70px; margin-right: 70px;'>
		<p>Use the following tools to edit the employee profile.</p>
		
		<h3 class='b uu'>Update Text</h3>
		
		<div class='clearall' style='clear: both; float: none; height: 20px;'></div>	
		
		
		<h3 class='b uu'>Update Images</h3>
		<p>Select Feature images with different orientations to be used in different places on the website. For example, the landscape image may be used on the front page schedule.</p>
	</div>

	<div class='clearall' style='clear: both; float: none;'></div>
	
	<div class='pagerow' style='position: relative; margin: 45px; margin-top: 5px; margin-left: 70px; margin-right: 70px; '>			
		<!--	### new row -->
		<div style='position: relative; float: left; width: 25%;'>		
			<div style='position: relative; float: left;'>
				<div style='position: relative; border: 1px solid #999; margin: 10px;'>
<!-- 					STUFF -->
					<?php echo $portrait_img_brsr; ?>
				</div><div class='clearall' style='clear: both; float: none;'></div>
			</div><div class='clearall' style='clear: both; float: none;'></div>
		</div>
		<div style='position: relative; float: left; width: 25%;'>
			<div style='position: relative; border: 1px solid #999; margin: 10px;'>						
<!-- 					STUFF -->
					<?php echo $landscape_img_brsr; ?>
			</div><div class='clearall' style='clear: both; float: none;'></div>
		</div>
		<div style='position: relative; float: left; width: 25%;'>
			<div style='position: relative; border: 1px solid #999; margin: 10px;'>						
<!-- 					STUFF -->
					<?php echo $square_img_brsr; ?>
			</div><div class='clearall' style='clear: both; float: none;'></div>
		</div>
		<div style='position: relative; float: right; width: 25%;'>
			<div style='position: relative; border: 1px solid #999; margin: 10px;'>						
<!-- 					STUFF -->
				<p class='psmall'>Use this function to select an image gallery separate from the gallery currently linked to this employee. <span class='redtext'>It is extremely unlikely that you will need to use this function.</span></p>
					<?php echo $gallery_selector; ?>
				<p class='psmall'>Note: The page must be reloaded after selecting a new gallery before the new gallery images will be visible below.</p>
			</div><div class='clearall' style='clear: both; float: none;'></div>
		</div>
		<div class='clearall' style='clear: both; float: none;'></div>
	</div>
	<div class='clearall' style='clear: both; float: none;'></div>
</div>
<div style='clear: both; float: none;'></div>
<!-- #### END OF FADED ROW		 -->
<!-- #################### -->
