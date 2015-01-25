<?php

//staff_editprofile_inlay-browsers.php

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



?>