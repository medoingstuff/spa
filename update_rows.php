<?php
/*
 *
 * Update_rows.php
 *
 *
 *
 *
 */
 

include('config.inc.php');
include('supporting_queries.php');
include_once('galleryfiles/queries.php');

$value = $_REQUEST['value'];
$table = $_REQUEST['table'];
$row = $_REQUEST['row'];

//$_POST['idprop'] = (isset($_POST['idfld'])) ? $_POST['idfld'] : $_POST['idprop'];
//$_POST['idval'] = (isset($_POST['id'])) ? $_POST['id'] : $_POST['idval'];



if ($_POST['updatechkboxes']) {
	//GENERIC CHECKBOXES
	//All checks updated
	//Value is determined in the javascript before this doc is called	
	
	if (($_POST['ntext']) && ($_POST['updateblogtext'])) $value = addslashes($_POST['ntext']);
	
	//update tables
	$bool = update_mysql_table($table,$row,$_POST['idprop'],$_POST['idval'],$value);
	//alert
	if ($bool) echo "Updated!";	else echo "*Error*";
	
} else if ($_POST['closedclick'] == 'y') {
	//delete all the other entries for this date, then insert a new entry for Closed sign
	$bool1 = delete_from_mysql_table ("therapist_schedule","sch_date",$_POST['idval']);
	if ($bool1) echo "...";	else echo "*Error*";
		
	if ($value == "y") {
		$bool = addto_mysql_table("therapist_schedule","sch_date,sch_shift,sch_closed",
			"'".$_POST['idval']."','am','y'");
		if ($bool) echo "Updated!";	else echo "*Error*";
	}
	
} else if ($_POST['updateshifts']) {
	//All checks updated
	//Value is determined in the javascript before this doc is called
	$shifttime = $_POST['shifttime'];
	$shiftdate = $_POST['shiftdate'];
	
	//Check to see if the checkbox value is Y or N	
	if ($value == 'n') {
		//delete this entry - they have unchecked the am/pm box
		
		$bool = delete_from_mysql_table ($table,"sch_date",$shiftdate,array("andwhere"=>" AND sch_shift='".$shifttime."' AND sch_therapist_id='".$_POST['idval']."' "));
		
	} else {
		//add a new entry or update an existing entry for the same date/therapist
		if ($_POST['closestore']=='y') {
			// delete all entries for this date
			delete_from_mysql_table ($table,"sch_date",$shiftdate,array("andwhere"=>" AND sch_shift='".$shifttime."' "));
			
			//then enter the closed sign into the DB
		} else {
			$_POST['closestore']='n';
		}
		
		$rows_strng = "sch_date,sch_shift,sch_closed,sch_therapist_id";
		$vals_strng = "'".$shiftdate."','".$shifttime."','".$_POST['closestore']."','".$_POST['idval']."'";
		
		//SQL QUERY INSERT
		$bool = mysql_query("INSERT INTO ".$table." 
						(".$rows_strng.")
						VALUES (".$vals_strng.")
						ON DUPLICATE KEY
						UPDATE sch_shift='".$shifttime."'
						") or die("addto_mysql_table: INSERT INTO ".$table." (".$rows_strng.") VALUES(".$vals_strng.") 
							ON DUPLICATE KEY
							UPDATE sch_shift='".$shifttime."'".mysql_error());
			
	
	}
//	update_mysql_table($table,$row,$_POST['idprop'],$_POST['idval'],$value);
	if ($bool) echo "Updated!";
	else echo "Error";
} else if ($_POST['addstaff']) {
	
	include_once('galleryfiles/queries.php');
	$newgalleryid = new_category(addslashes($_POST['therapistname']),0,0,1,0,0,"cat_name");
	
	$addtwitter = ($_POST['twitter']) ? ($_POST['twitter']) : "";
	
	$vals_string = "'".$_POST['therapistname']."','".$_POST['licensenumber']."','y','n','".$_POST['aboutgirl']."','".$addtwitter."','".$newgalleryid."'";
	
	$bool = addto_mysql_table("therapists","th_pseudoname,th_licensenum,th_active_in_spa,th_admin_delete,th_about,th_twitterhandle,th_galleryID",$vals_string,null);
	

	echo "New employee added!";
	
	//	th_id	th_pseudoname	th_licensenum	th_active_in_spa	th_admin_delete	th_create_date	th_profileimg_portrait	th_profileimg_landscape	th_profileimg_sq_rnd	th_about
//	if ($bool) echo "";
} else if ($_POST['deletestaff']) {
	//Set admin_delete = y - the application will act as though the entry has been deleted from the database.
	
	$bool = update_mysql_table("therapists","th_admin_delete","th_id",$_POST['deletestaff'],"y");
	if ($_POST['galleryid'] > 0) {
		update_mysql_table("item_category","membersonly","cat_id",$_POST['galleryid'],"y");
		update_mysql_table("item_category","cat_enabled","cat_id",$_POST['galleryid'],"n");
	}
	if ($bool) echo "DELETED!";
	//also hide/delete the gallery
	
	
} else  if (($_GET['table'] == 'therapists') && (($_GET['deletenewsimage2']) || ( true ) )) {
	// !Browser v10 - add single or multi images / delete multi images
	if ($_GET['deletenewsimage2']) {
		//delete one of the multi-images (multi slides (s1) only)
		//"&edit=y&table=tripslides&value=y&showresults=y&id=".$iBrowserParams['srcid']."&deletenewsimage2="
		//update_mysql_table($table,$row,$idfld,$id,$newval,$otheroptionsarr=null)
		update_mysql_table("trip_slides","slides_deleted","slides_tripid",$_GET['id'],"y",array("andwhere"=>" AND slides_pageposition='".$_GET['row']."' AND slides_imageid='".$_GET['deletenewsimage2']."' "));
		$fdbk = "Image removed from slideshow.";
		
	} else {
		//add multiple or single images
		switch($_GET['row']) {
			case 's1':
				/*
if ($_GET['addmulti'] == 'y') {
					$valuearray = explode("x", $_GET['value']);
					for($i=0; $i<count($valuearray); $i++) {
						if ($valuearray[$i]>0) {
							//add_images_of_post($id,$valuearray[$i]);
//							addto_mysql_table("trip_slides", "slides_tripid,slides_pageposition,slides_imageid", "'".$_GET['id']."','s1','".$valuearray[$i]."'");
						}
					}
				}
*/
				//NO NEED TO UPDATE EXISTING where s1 is concerned. Simply add new ones.
				/* addto_mysql_table("trip_slides", "slides_tripid,slides_pageposition,slides_imageid", "'".$_GET['id']."','s1','".$_GET['value']."'"); */
				$fdbk = "New image(s) added to the slideshow.";
			break;
			case 'th_profileimg_sq_rnd':
			case 'th_profileimg_landscape':
			case 'th_profileimg_portrait':
				mysql_query("UPDATE therapists SET ".$_GET['row']."='".$_GET['value']."' WHERE th_id='".$_GET['id']."' ");
				echo "Updated profile images";

//				mysql_query("DELETE FROM trip_slides WHERE slides_tripid='".$_GET['id']."' AND slides_pageposition='".$_GET['row']."' ");
//				addto_mysql_table("trip_slides", "slides_tripid,slides_pageposition,slides_imageid", "'".$_GET['id']."','".$_GET['row']."','".$_GET['value']."'");
	/* 			update_mysql_table("trip_slides","slides_imageid","slides_tripid",$_GET['id'],$_GET['value'],array("andwhere"=>" AND slides_pageposition='".$_GET['row']."' ")); */
//				$fdbk = "The image has been updated.";
			break;
		}
	}
}	

/*
addto_mysql_table: INSERT INTO therapist (th_pseudoname,th_licensenum,th_active_in_spa,th_admin_delete,th_about,th_twitterhandle) VALUES('asdfasdfasdf','123123123','y','n','','')Table 'sql_spa.therapist' doesn't exist
*/



?>