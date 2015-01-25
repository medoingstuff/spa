<?php
//Updated January 13, 2015 - added if (DEBUG) to all troubleshooting ECHO scripts (ie. echo "-")
//Updated March 28, 2013
//Updated September 13, 2012 - updated send_standard_mail function to include an options array
//Updated April 16, 2012 - added $root to set_image_dimensions();
//Updated August 18, 2011 - include supporting_sql.php - move check_results() to it 
//Updated March 7, 2011 - Fix set_image_dimensions() so that smaller images are not downsized to TB size when the TB is square.
//Updated January 17, 2011 - updated set_image_dimensions()
//Updated Jan 12, 2010 - include email sending function [ge]
//Updated October 18, 2009 - updated set_image_dimensions to allow portrait images to return ft_BOX_h
//Last Updated September 11, 2009 - AB - updated img_brsr script 
//
//
################################################################################
##																			  ##
##	Supporting Functions													  ##
##																			  ##
################################################################################

include_once('supporting_sql.php');
include_once('galleryfiles/simpleimage.php');

function generateRandomCode($length=6,$full='y') {
		if ($full=='y') {
			$chars = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPRQSTUVWXYZ0123456789";
		} else if ($full = 'lower') {
			$chars = "abcdefghijklmnopqrstuvwxyz123456789";
		} else {
			$chars = "abcdemnABCDEFGHIJKLMNOPRQSTUVWXYZ123456789";
		}
        $code = "";
        $clen = strlen($chars) - 1;  //a variable with the fixed length of chars correct for the fence post issue
        while (strlen($code) < $length) {
            $code .= $chars[mt_rand(0,$clen)];  //mt_rand's range is inclusive - this is why we need 0 to n-1
        }
        return $code;
}

function send_standard_email($to,$toname,$from,$from_name,$personalmessage,$subject,$otherheaders,$bcc=null,$optarray=null) {
	global $donotreplyemail;
	global $donotreplyName;	
	global $emaildomain;
	global $site_emailsettings;
	
	if ($optarray['skip_header_tags']=='y') {
	} else {
		$msg="
		<html>
		<head>
		
		<title>".$subject."</title>
		</head>
		<body>".$personalmessage."</body>
		</html>";
	}		
	
	
	if ($donotreplyemail) {
		//if set, then use the phpmailer script
		include('includes/PHPMailer/class.phpmailer.php');
		
		$mail = new PHPMailer;
		
		$body             = $msg; //file_get_contents('contents.html');
		//$body             = preg_replace('/[\]/','',$body);

/*
	$site_emailsettings = array('useHost'=>false,'smtpdebug'=>0,'domain'=>'mail.photographsbyglen.com','smtpAuth'=>true,'SMTPSecure'=>'tls','Host'=>'smtp.gmail.com','Port'=>587,'Username'=>'do-not-reply@medoingstuff.com','Password'=>'J@n2december');
	
*/

/////// REQUIRED FOR BUILT IN SMTP
		if (!$site_emailsettings['useHost']) {
			$mail->IsSendmail(); // telling the class to use SendMail transport
		} else {
/////// REQUIRED FOR GMAIL:
			$mail->IsSMTP(); // telling the class to use SMTP
			$mail->Host       = $site_emailsettings['domain']; // SMTP server
			$mail->SMTPDebug  = $site_emailsettings['smtpdebug']; // enables SMTP debug information (for testing)
			                                           // 1 = errors and messages
			                                           // 2 = messages only
			$mail->SMTPAuth   = $site_emailsettings['smtpAuth']; // enable SMTP authentication
			$mail->SMTPSecure = $site_emailsettings['SMTPSecure']; // sets the prefix to the servier
			$mail->Host       = $site_emailsettings['Host']; // sets GMAIL as the SMTP server
			$mail->Port       = $site_emailsettings['Port']; // set the SMTP port for the GMAIL server
			$mail->Username   = $site_emailsettings['Username'];  // GMAIL username
			$mail->Password   = $site_emailsettings['Password']; // GMAIL password
		}
//////		
		$mail->SetFrom($donotreplyemail, $donotreplyName);
		
		$mail->AddReplyTo($donotreplyemail, $donotreplyName);
		
		$mail->Subject    = $subject;
		
		$mail->AltBody    = "To view the message, please use an HTML compatible email viewer!"; // optional, comment out and test
		
		$mail->MsgHTML($body);
		
//		$address = "whoto@otherdomain.com";
		$mail->AddAddress($to, $toname);
		$mail->AddBCC($bcc);
//		$mail->AddAttachment("images/phpmailer.gif");      // attachment
//		$mail->AddAttachment("images/phpmailer_mini.gif"); // attachment
		
		if(!$mail->Send()) {
		  echo "Mailer Error: " . $mail->ErrorInfo;
		  return false;
		} else {
		  echo "Message sent!";
		  return true;
		}		
		
	} else {
		//send_standard_email
	/*
		if (!$to) {
			$to = null;
		} else if ($toname) {
			$to = $toname."<".$to.">";
		}	else {
			$to = $to."<".$to.">";
		}
	*/
		
		if (($toname) && ($to)) {
			$to = $toname."<".$to.">";
		} else if (!$to) {
			$to = null;
		} else {
			$to = $to."<".$to.">";
		}
		
		$styleX = "
			<style>
		@media only screen and (max-device-width: 480px) {
		     .page {
		          padding: 0px 10px 5px 10px !important;
		     }
		     html {
		     	-webkit-text-size-adjust: none;
		     	width: 480px;
		     }
		     body {
		          padding: 10px !important;
		          width: 480px !important;
		          background: #fff;
		     }
		     div.addwidth {
		     	width: 480px !important;
		     }
		}
		</style>
	";
		
		if ($optarray['skip_header_tags']=='y') {
		} else {
			$body="
			<html>
			<head>
			
			<title>".$subject."</title>
			</head>
			<body>".$personalmessage."</body>
			</html>";
		}		
				
		// To send HTML mail, the Content-type header must be set
		$headers  = 'MIME-Version: 1.0' . "\r\n";
		$headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
		
		$headers .= 'From: '.$from_name.' <'.$from.'>' . "\r\n";
		
		$headers .= "Reply-To: ".$from."\r\n";
		
		$headers .= $otherheaders;
	
	    $headers .= "X-Mailer: PHP v".phpversion();          // These two to help avoid spam-filters
	    
	//	$mail_sent = mail($to,$subject,$body,$headers);
		$mail_sent = imap_mail($to,$subject,$body,$headers,null,$bcc);
		return $mail_sent;
	}
}

function string_to_array($strng,$delimiter) {
	//searches a string and splits it at each delimiter. 
	//Returns an array of the characters between the delimiters
	$num = substr_count($strng,$delimiter);
	for ($i=0; $i < $num;$i++) {
		if ($pos = stripos($strng,$delimiter)) {
			$arr[] = substr($strng,0,$pos);
			$strng = substr($strng,($pos+1),strlen($strng));
		}
	}
	if (strlen($strng) > 0) $arr[] = $strng;
	return $arr;
}

function short($strng,$l,$needle=null) {
	//make sure that string is not shortened in middle of needle: ie: &#039; the apostrophe
	$needle_ln = strlen($needle);
	if (($needle) && ( (strlen($strng)+$needle_ln) > $l ) ) {
		$num = substr_count($strng,$needle);
//		echo "<br/>".$num." ln: ".strlen($strng)." pos:";
		$teststring = $strng;
		$running_tot = 0;
		for ($i=0; $i<$num; $i++) {
			if ( ($pos = stripos($teststring, $needle)) && ($pos <= ($l+$needle_ln)) ) {
				$running_tot = ($running_tot + $pos);
//				echo $pos."  running: ".$running_tot." ".$teststring." ";
				if ( (($running_tot+$pos) >= ($l-$needle_ln)) && (($running_tot+$pos) <= ($l+$needle_ln)) ) {
					$l = (($running_tot+$pos) + $needle_ln);
				}
				$teststring = substr($teststring,($pos+$needle_ln),strlen($teststring));
			}
		}
	}	
	$x = substr($strng,0,$l);
	if ( strlen($x) < strlen($strng) ) $x .= "...";
	return $x;
}

function short_c($strng,$l) {
	$x = substr($strng,0,($l/2));
	$x .= "......".substr($strng,(strlen($strng)-($l/2)),($l/2));
	return $x;	
}	

function replace_char($str) {
	$str = str_replace("'", "&#8217;", $str);
	$str = str_replace('"', '&quot;', $str );
//	$str = str_replace('&','&amp;',$str);
	return $str;
}

function check_for_new_image_news() {
	$now = getdate();
	$today = $now['year']."-".$now['mon']."-".$now['mday'];
	$r = mysql_query("SELECT * FROM news WHERE n_postdate='".$today."' AND n_x='g' ") or die("check for new image news(): ".mysql_error());
	return check_results($r);
}

function select_random_image_from_today($not=null) {
	if ($not > 0) {
		$whereby = " i.item_id <> ".$not." AND ";
	}
	$now = getdate();
	$tomorrow = $now['year']."-".$now['mon']."-".($now['mday']+1);
	$r = mysql_query("SELECT i.* 
						FROM item_items as i 
						INNER JOIN gallery_imgs_of_cat as g 
						ON i.item_id = g.img_id
						WHERE ".$whereby." i.item_upload_date 
						BETWEEN UNIX_TIMESTAMP(CURDATE())
						AND
						UNIX_TIMESTAMP(DATE_ADD(CURDATE(), INTERVAL + 86399 SECOND))
						");
	list($rc,$n) = check_results($r);
	echo " <br/> num images uploaded today:".$n;
	if ($n > 0) {
		while ($rx = mysql_fetch_array($rc)) {
			$a[] = $rx['item_id'];
		}
		$ran = rand(0,(count($a)-1));
		echo "<Br/> new random: ".$a[$ran]." <Br/>";
		return $a[$ran];
	}
	
}

function get_images_fr_gallery($item,$img_width,$img_ht,$bool,$align,$root=null) {
	//$root should equal "../" in some situations
	include_once($root.'galleryfiles/queries.php');
	list($r_i,$n_i) = get_image($item);
	while ($img = mysql_fetch_array($r_i)) {
		if ($bool === true) {
			$tb_ = "tb/tb_";
		}
		$filename = IMAGES_DIR.$tb_.$img['item_img'];
		$size[] = getimagesize($filename);		
		//if (($size[0][0] > $size[0][1])) { 		
			if ($size[0][0] > $img_width) {
				$width = " width='".$img_width."' ";
				if (!($img_ht == '0')) $ht = " height='".$img_ht."' ";
			}
		//} 
		else {
			//width is 0, height is 1
				$width = " width = '".$size[0][0]."'";
//				$ht = $size[0][1];
		}
		//create IMAGE constant to call in gallery.htm
		return "<img src='".$filename."' ".$width.$ht." border = '0' align=".$align." />";	
		//return $filename;
	}
}

function img_tag($filename,$align) {
	return "<img src='".$filename."' ".$width.$ht." border = '0' align='".$align."' />";
}

## Get Image Info
function get_image_info ($item,$root = null,$root2 = null,$imgArrIn=null) {
	//GLOBALS
	global $images_dir, $images_dir_rel, $originals_dir, $originals_dir_rel,$skip_check_exists,$ftpupload_serverpath_images,$ftpupload_serverpath_originals;
	
	//$root should equal "../" in some situations depending on which file this is called from 
	include_once($root.'galleryfiles/queries.php');

	//make sure that the image ID is greater than 0 before hitting the database..
	if ($item > 0) {
		unset($item_img);
		if (count($imgArrIn)>0) {
			$item_img = $imgArrIn['item_img'];
			$iarray = $imgArrIn;
		} else {
			list($r_i,$n_i) = get_image($item);
			$iarray = array();
			while ($img = mysql_fetch_array($r_i)) {
				$item_img = $img['item_img'];
				$iarray = $img;				
/*	
				$filename = IMAGES_DIR.$img['item_img'];  
				$filenametb = IMAGES_DIR."tb/tb_".$img['item_img'];
				$filename_O = ORIGINALS_DIR.$img['item_img'];			
				
				$iarray = $img;
				
				$iarray['pathrelative'] = $root2.IMAGES_DIR_REL.$img['item_img'];  
				$iarray['tbrelative'] = $root2.IMAGES_DIR_REL."tb/tb_".$img['item_img'];
				$iarray['originalrelative'] = $root2.ORIGINALS_DIR_REL.$img['item_img'];			
	
				$iarray['original'] = $filename_O;
*/
				//echo "@@".$iarray['pathrelative']."@@";
				/*
				$iarray['fname'] = $filename;
				$iarray['tbname'] = $filenametb;
	*/
			} //end while
		} //end else filename has not been set already
		$filename = $images_dir.$item_img;  
		$filenametb = $images_dir."tb/tb_".$item_img;
		$filename_O = $originals_dir.$item_img;
		$iarray['pathrelative'] = $root2.$images_dir_rel.$item_img;  
		$iarray['tbrelative'] = $root2.$images_dir_rel."tb/tb_".$item_img;
		$iarray['originalrelative'] = $root2.$originals_dir_rel.$item_img;
		$iarray['original'] = $filename_O;
		//internal serverpath
		$iarray['server_path'] = $ftpupload_serverpath_images.$item_img;
		$iarray['server_original'] = $ftpupload_serverpath_originals.$item_img;
		$iarray['server_tb'] = $ftpupload_serverpath_images."tb/tb_".$item_img;
		$iarray['server_tb400'] = $ftpupload_serverpath_images."tb400/tb400_".$item_img;
		
		
	} //end if item > 0
		
	//CHECK TO SEE IF FILE EXISTS FIRST. 
		//Note 2: this only will work when viewing an image in the gallery because it checks to see if item>0
		//NOTE: We are checking if a remote file exists because we are checking the ABSOLUTE http path rather than a relative path 
		//relative vs absolute: (to support the de vs en vs fr servers for cwsx)
//			echo " Rel:".$iarray['pathrelative']." filename: ".$filename." IDR: ".IMAGES_DIR_REL." root:".$root2."*****<br/>";
	
	
	if (!($skip_check_exists === true)) {
	if ( (file_exists($iarray['pathrelative']))  ) {// && ($item > 0)) {
		//Test relative path to the image - if it exists, do nothing.
		//	echo "**";
	} else {
		//if it doesn't exist, then check the remote file path - (absolute url) - if it doesn't exist, then display our default image
		if ( (remoteFileExists($filename)) && ($item > 0) && (CHECK_REMOTE_DIR)) {
			//file exists, do nothing!
		//	echo "##";			
		} else {
			//image file does not exist. Use this path instead:
			$filename = $root2."galleryfiles/icons/noimg.jpg";
		}
	}
	
	if ( (file_exists($iarray['tbrelative'])) ) {//&& ($item > 0)) {
		//Test relative path to the image - if it exists, do nothing.
	} else {
		if ( (remoteFileExists($filenametb)) && ($item > 0) && (CHECK_REMOTE_DIR === true)) {
			//$sizetb = getimagesize($root.$filenametb);
			if (DEBUG) echo "-"; //indicate that we had to do a remote file check - (must verify if this is happening a lot)			
		} else {
			//image file does not exist. Use this path instead:
			$filenametb = $root2."galleryfiles/icons/noimg_tb.jpg";
		}
	}
	}//end $skip_check_exists
	
	//Get/Set the size of the TB and the image
//	$size = getimagesize($iarray['server_path']);
//	$sizetb = getimagesize($iarray['server_tb']);
	$sizetb = getimagesize($filenametb);

		$size = getimagesize($filename);
		
	//return filename,width,height,html dimensions
	return array($filename,$size[0],$size[1],$size[3],$filenametb,$sizetb[0],$sizetb[1],$sizetb[3],'imginfo'=>$iarray); //4,5,6,7 for tbs
}


function remoteFileExists($url) {
    $curl = curl_init($url);

    //don't fetch the actual page, you only want to check the connection is ok
    curl_setopt($curl, CURLOPT_NOBODY, true);

    //do request
    $result = curl_exec($curl);

    $ret = false;

    //if request did not fail
    if ($result !== false) {
        //if request was ok, check response code
        $statusCode = curl_getinfo($curl, CURLINFO_HTTP_CODE);  

        if ($statusCode == 200) {
            $ret = true;   
        }
    }

    curl_close($curl);

    return $ret;
}

function scale_size($size=array(0,0),$desired_w,$desired_h) {
	//take $size array and return image dimensions per DesiredW and desiredH
	$w = $size[0];
	$h = $size[1];
	if ( ($desired_w >= $size[0]) && ($desired_h >= $size[1])) {
		//image is smaller than desired size. Show actual size: 
		$w = $size[0];
		$h = $size[1];
		return array($w,$h);
	}
	
	if ($w > $desired_w) {
		$h = (($desired_w*$h)/$w);
		$w = $desired_w;
	}
	
	if ($h > $desired_h) {
		$w = (($w*$desired_h)/$h);
		$h = $desired_h;
	}
	return array(round($w,0),round($h,0));
}


//make new function with same name. deprecate the old function temporarily or /**/ it out....***************

function set_image_dimensions ($imgid,$max_img_w,$max_img_h,$root=null,$root2=null,$imgArrIn=null) {
//	echo $root2."@";
	
	//get filepath, tb path, and w and h of each 
	$imageinfoarray = array();
	$imageinfoarray = get_image_info ($imgid,$root,$root2,$imgArrIn);
	list($filename,$fsizeW,$fsizeH,$htmlsize,$filenametb,$tbsizeW,$tbsizeH,$tbhtmlsize) = $imageinfoarray;
		
	//check to see if the thumbnail is smaller than it should be.
	if ($tbsizeW < G_THUMBNAIL_W) {
//echo "resized".$imgid."   ";
		//resize if necessary. Maintain image proportions:
		if ($tbsizeW == $tbsizeH) {
			if (DEBUG) echo ",";
				//reset_tb($imgid,G_THUMBNAIL_W,"NorthSQ");
			reset_tb2($imgid,G_THUMBNAIL_W,$imageinfoarray['imginfo']['server_tb'],$imageinfoarray['imginfo']['server_path'],$fsizeW,$fsizeH);			
			//DISABLED TO AVOID OVERLOADING SERVER
			//

		} else {
//echo "this one";
			if (DEBUG) echo ".";
				//reset_tb($imgid,G_THUMBNAIL_W,"center");
			reset_tb2($imgid,G_THUMBNAIL_W,$imageinfoarray['imginfo']['server_tb'],$imageinfoarray['imginfo']['server_path'],$fsizeW,$fsizeH);
			//DISABLED TO AVOID OVERLOADING SERVER
			//

			
		}
/*
		$newsize = getimagesize($root.$filenametb);
		$tbsizeW = $newsize[0];
		$tbsizeH = $newsize[1];
*/
	}

	//determine desired size to fit within the max w and h
	//full-size
	list($dw_fs,$dh_fs) = scale_size(array($fsizeW,$fsizeH),$max_img_w,$max_img_h);
	//thumbnail
	list($dw_tb,$dh_tb) = scale_size(array($tbsizeW,$tbsizeH),$max_img_w,$max_img_h);
	
/* 	echo $dw_fs."/".$dh_fs."  ".$dw_tb."/".$dh_tb; */
	
	
	#//determine if the image is landscape or portrait:
	
	//determine which image to use: tb or fullsize

	//IF the max_img_w makes it so that the tb size and f size for W are equal,
	//then make it so the FS dimensions are equal to the TB dimensions
	if (($tbsizeW == $tbsizeH) && (!($fsizeW == $fsizeH)) && ($tbsizeW == $fsizeW)) { 
		$dw_fs = $dw_tb;
		$dh_fs = $dh_tb;
	}

//	echo "<Br/>max:".$max_img_w."   ".$dw_fs.">  ".$tbsizeW."    ".(($dw_fs > $tbsizeW) ? true : "tb")."    ".$dh_fs.">  ".$tbsizeH."  ".(($dh_fs > $tbsizeH) ? true : "tb")."  ";

	if (($dw_fs > $tbsizeW) || ($dh_fs > $tbsizeH)) {
		//desired size is larger than available tb. Use full size image 
		$ft_box_h = $dh;
		$topspace = 0;
//echo "&nbsp;&nbsp;&nbsp;".$filename;
		return array($imageinfoarray,$filename,$ft_box_h,$topspace,$dw_fs,$dh_fs);
	} else {
		$ft_box_h = $dh;
		$topspace = 0;	
//echo "&nbsp;&nbsp;&nbsp;".$filenametb;
		return array($imageinfoarray,$filenametb,$ft_box_h,$topspace,$dw_tb,$dh_tb);		
	}
}

function encode($ss,$ntime) {
	for ($i=0; $i<$ntime; $i++) {
		$ss=base64_encode($ss);
	}
	return $ss;
}

function decode($ss,$ntime){
    for($i=0; $i<$ntime; $i++){
		$ss=base64_decode($ss);
    }
	return $ss;
}

/*
//<a href="index.php?page=<? echo encode($url,5); ?>">My page</a>
//$mypage=$_GET['page'];
//$mypage=decode($mypage,5);
//echo file_get_contents($mypage);
//
*/ 
///////////////////////////// Returns



function no_blanks($edit,$text,$pretitle,$posttitle,$filler_text=null) {
	//insert default text to ensure that there aren't any blanks
	//return filler text, question mark if the text is empty
	if ($edit == 'y') {
		if (strlen($text) < 1) {
			if (!($filler_text == null)) {
				$r = $filler_text;
			} else {
				$r = "&nbsp;&nbsp;&nbsp;&nbsp;?&nbsp;&nbsp;&nbsp;&nbsp;";
			}
		} else {
			$r = $text;
		}
		return "<span>".$r."</span>";
	} else {
		return $pretitle.$text."&nbsp;".$posttitle;
	}
}

//if (xmlHttp.readyState==4 || xmlHttp.readyState=="complete") {
//		if (xmlHttp.status == 200){

function strip_quote($str) {
	$str = htmlspecialchars($str,ENT_QUOTES);
/*
	$patterns = array(	"\'"
						
						);
	$replace = array(	"&#39;"
						
						);
	$str = preg_replace($patterns,$replace,$str);
	*/
	return $str;
}

function strip_single_quote($str) {
	$patterns = array("/'/","/\\'/","/\'/");
	$replace = array("&#039;","&#039;","&#039;");
	$str = preg_replace($patterns,$replace,$str);
	
	return $str;
}

function stripjavaslashes($str) {	
	$str = preg_replace("/\\'/g","'",$str);
//	mixed preg_replace ( mixed pattern, mixed replacement, mixed subject [, int limit [, int &count]] )
//	str=str.replace(/\'/g,'\\\'');		
}


/*
function addslash(str) {
	//str = str.replace(/\'/g,'asd&amp;quot;&#39;');
	str=str.replace(/\'/g,'\\\'');	
	str=str.replace(/\"/g,'\\"');
	str=str.replace(/\\/g,'\\\\');
	str=str.replace(/\0/g,'\\0');
alert(str);
	return str;
}

function addslashes(str) {
	str = str.replace(/\'/g,'&#39;');
//	str=str.replace(/\'/g,'\\\'');
	str=str.replace(/\"/g,'\\"');
	str=str.replace(/\\/g,'\\\\');
	str=str.replace(/\0/g,'\\0');

	return str;
}

function stripslashes(str) {
	str=str.replace(/\\'/g,'\'');
	str=str.replace(/\\"/g,'"');
	str=str.replace(/\\\\/g,'\\');
	str=str.replace(/\\0/g,'\0');
	return str;
}
*/


#######################################


/*
@param string $text String to truncate.
@param integer $length Length of returned string, including ellipsis.
@param string $ending Ending to be appended to the trimmed string.
@param boolean $exact If false, $text will not be cut mid-word
@param boolean $considerHtml If true, HTML tags would be handled correctly
@return string Trimmed string.
//truncate($text, $length = 100, $ending = '...', $exact = false, $considerHtml = true)
*/

function truncate($text, $length = 100, $ending = '...', $exact = false, $considerHtml = true) {
	if ($considerHtml) {
		// if the plain text is shorter than the maximum length, return the whole text
		if (strlen(preg_replace('/<.*?>/', '', $text)) <= $length) {
			return $text;
		}
		// splits all html-tags to scanable lines
		preg_match_all('/(<.+?>)?([^<>]*)/s', $text, $lines, PREG_SET_ORDER);
		$total_length = strlen($ending);
		$open_tags = array();
		$truncate = '';
		foreach ($lines as $line_matchings) {
			// if there is any html-tag in this line, handle it and add it (uncounted) to the output
			if (!empty($line_matchings[1])) {
				// if it's an "empty element" with or without xhtml-conform closing slash
				if (preg_match('/^<(\s*.+?\/\s*|\s*(img|br|input|hr|area|base|basefont|col|frame|isindex|link|meta|param)(\s.+?)?)>$/is', $line_matchings[1])) {
					// do nothing
				// if tag is a closing tag
				} else if (preg_match('/^<\s*\/([^\s]+?)\s*>$/s', $line_matchings[1], $tag_matchings)) {
					// delete tag from $open_tags list
					$pos = array_search($tag_matchings[1], $open_tags);
					if ($pos !== false) {
					unset($open_tags[$pos]);
					}
				// if tag is an opening tag
				} else if (preg_match('/^<\s*([^\s>!]+).*?>$/s', $line_matchings[1], $tag_matchings)) {
					// add tag to the beginning of $open_tags list
					array_unshift($open_tags, strtolower($tag_matchings[1]));
				}
				// add html-tag to $truncate'd text
				$truncate .= $line_matchings[1];
			}
			// calculate the length of the plain text part of the line; handle entities as one character
			$content_length = strlen(preg_replace('/&[0-9a-z]{2,8};|&#[0-9]{1,7};|[0-9a-f]{1,6};/i', ' ', $line_matchings[2]));
			if ($total_length+$content_length> $length) {
				// the number of characters which are left
				$left = $length - $total_length;
				$entities_length = 0;
				// search for html entities
				if (preg_match_all('/&[0-9a-z]{2,8};|&#[0-9]{1,7};|[0-9a-f]{1,6};/i', $line_matchings[2], $entities, PREG_OFFSET_CAPTURE)) {
					// calculate the real length of all entities in the legal range
					foreach ($entities[0] as $entity) {
						if ($entity[1]+1-$entities_length <= $left) {
							$left--;
							$entities_length += strlen($entity[0]);
						} else {
							// no more characters left
							break;
						}
					}
				}
				$truncate .= substr($line_matchings[2], 0, $left+$entities_length);
				// maximum lenght is reached, so get off the loop
				break;
			} else {
				$truncate .= $line_matchings[2];
				$total_length += $content_length;
			}
			// if the maximum length is reached, get off the loop
			if($total_length>= $length) {
				break;
			}
		}
	} else {
		if (strlen($text) <= $length) {
			return $text;
		} else {
			$truncate = substr($text, 0, $length - strlen($ending));
		}
	}
	// if the words shouldn't be cut in the middle...
	if (!$exact) {
		// ...search the last occurance of a space...
		$spacepos = strrpos($truncate, ' ');
		if (isset($spacepos)) {
			// ...and cut the text in this position
			$truncate = substr($truncate, 0, $spacepos);
		}
	}
	// add the defined ending to the text
	$truncate .= $ending;
	if($considerHtml) {
		// close all unclosed html-tags
		foreach ($open_tags as $tag) {
			$truncate .= '</' . $tag . '>';
		}
	}
	return $truncate;
}

?>