//spa.js
//
//LAST UPDATED
//
//
//
//UPDATE - January 12, 2015 - added function to Link Gallery
//December 14, 2014 - scroll to staff list after adding new staff using the form
//December 14, 2014 - check for entity var in form to add new staff


$(document).ready(function() {


/* FROM INDEXTEST */
/* 	on load, make sure that the position of the Canyoneering element is below the scroll size of the dom window  */
	var viewportheight = $(window).height();
	/* 	$( this ).offset().top; */
	

	if ($(".incredible").height()) {
		var incredibleTEXTpos = ($(".incredible").offset().top + $(".incredible").height());
	} else var incredibleTEXTpos = 0;
	
	//create object instead of array
//	var hidables["incredibles"] = [$(".incredible"),incredibleTextpos,false]; //array = (element,hide@scroll,chkscrollFlag) 
	
/* 	SET BACKGROUND AS RANDOM IMAGE */
	var backgroundimagesArray = [
								//"blackleather.jpg",
								//"floorbg.jpg",								
								"retrobg.jpg",
								"retrobg.jpg"
								];
/*
	var IncredibleOverlayArray = [["","",""],
									["","",""],
									["light","light","light"],
									["","",""]];
*/	
	
	/* MAKE HEADER BAR DARKER ON SCROLL	 */
	var randBG = Math.floor(Math.random() * backgroundimagesArray.length);
	$(".loginpageclasses").css("background","url(images/"+backgroundimagesArray[randBG]+") center center fixed");	
	$(".loginpageclasses").css("background-size","cover");	
	
	
//	var spa_ChkScrollElem = $("#firstimage");
	var spa_ChkScrollFlag = false;
	
	var spa_ChkScrollElemIncredibles;
	var spa_ChkScrollElemFlagIncredibles = false;
	
	$(document).on("scroll",function() {
		if ( ($(document).scrollTop()>70) && (spa_ChkScrollFlag == false)) {
			$(".blinderbar").css("background","rgba(0,0,0,.9)");
			spa_ChkScrollFlag = true;
		} else if ((spa_ChkScrollFlag == true) && ($(document).scrollTop()<70)) {
			$(".blinderbar").css("background","rgba(0,0,0,.45)");
			spa_ChkScrollFlag = false;
		}
		
		
		/* HIDE IMAGES ON SCROLL	 */
		if (incredibleTEXTpos > 0) {
			if ( ($(document).scrollTop()>incredibleTEXTpos) && (spa_ChkScrollElemFlagIncredibles == false)) {
				$(".ETI").fadeOut();
				spa_ChkScrollElemFlagIncredibles = true;
			} else if ((spa_ChkScrollElemFlagIncredibles == true) && ($(document).scrollTop()<incredibleTEXTpos)) {
				$(".ETI").fadeIn();
				spa_ChkScrollElemFlagIncredibles = false;
			}
		}		
		
		
		
	});	
/* END OF FROM INDEXTEST */
	
	
		
	
	$("#pagebody").on("click",".get_calendar_date", function(event) {
		event.preventDefault();
		var timestampv = $(this).attr("data-timestamp");
		var textdate = $(this).attr("data-textdate");
		var targetcell = $(this).attr("data-targetcell");
		$(".calendar").removeClass("cal-selected");
		$(this).addClass("cal-selected");
		
		$("#"+targetcell).val( timestampv );
		$("#scheduledate").html( textdate );
		$("#editschedule").show();
		$("#editschedule .activeemployee").fadeIn();
		
		//uncheck ALL staff shifts (reset)
		$(".shiftchkbox").prop('checked',false);
		//show that the store is open						
		$("#storeclosedY").prop('checked',false );
		$("#storeclosedN").prop('checked',true );
		//make sure the checkboxes are enabled.
		$(".shiftchkbox").attr("disabled",false);

		//get the json array
		var jsonArray = $('#shiftjsonarray').attr("data-s"+timestampv);
		if (jsonArray) {
			var ja = jQuery.parseJSON(jsonArray);

			//Check to see if the date clicked is equal to one of the array KEYs
			$.each(ja, function(j_key,j_item) {
				if (j_key == "closed") {
					//The store is closed. No date-time elements to view
					if (j_item == 'y') {
						$("#storeclosedY").prop('checked',true);
						$("#storeclosedN").prop('checked',false);
						$(".shiftchkbox").attr("disabled",true);
					} else {
						//the store is not closed?
						//we've already set the checkboxes to false
					}
					//break iteration. Store is closed.
					return;
				} else {
					//show that the store is open:s						
						//we've already done this before getting the json						
					//at this level, we can access the final array variables ie. j_item.name
					var staffid = j_item.id;
					(j_item.am == 'y') ? $("#am"+staffid+"").prop('checked',true ): false;
					(j_item.pm == 'y') ? $("#pm"+staffid+"").prop('checked',true ) : false;
				}
			});
		}
				
	});
	
	
	
	//CLOSE THE STORE Radio Buttons
	$("#pagebody").on("click",".storeclosed",function() {
		//Update Store open status
		var storeclosedval = $(".storeclosed:checked").val();
		
		//get selected timestamp from cal
		var timestampv = $("#calendartarget").val();
		if ((storeclosedval=='y')) {
			//StoreClosed=y
			//IF the store closed is not Y already, make it yes
			$('#shiftjsonarray').attr("data-s"+timestampv,'{"closed":"y"}');
				$('#shiftjsonarray').attr("data-l"+timestampv,"0");
			$(".shiftchkbox").prop("checked",false);
			$(".shiftchkbox").prop("disabled",true);
		} else { 
			//CLOSED = NO
			//check if there are activeemployees with shifts on this day, if so, then the store is already open and we don't want to click it because it will clear the shifts.
//			if ($('#shiftjsonarray').attr("data-s"+timestampv) == "n") {
			if ( $(".activeemployee .shiftchkbox:checked").length ) {	
				//check to see if any boxes are checked - this would only be possible if the store is open (or an error..)
				//if checked, do nothing, THE STORE is already on open and opening it again would clear the data for the day (to wipe the closed sign).
				//				
				alert("The store is already open.");
				return false;
			} else {
				//clear closed sign
				$(".shiftchkbox").prop("disabled",false);
				$('#shiftjsonarray').attr("data-s"+timestampv,"");
				$('#shiftjsonarray').attr("data-l"+timestampv,"0");
			}
		}
		//var checkboxvalue = $(this).val();
		var id = $(this).attr("id");
		var rslttarget = $("#rsltstoreclosedY");

		//AJAX
		$.ajax({
			type: "POST",
			url: 'update_rows.php',
			data: "&table=therapist_schedule&row=sch_closed"
				+"&idprop=sch_date&closedclick=y"
				+"&value="+storeclosedval+"&idval="+timestampv,
			success: function(data){
				$("#"+rslttarget).fadeIn('fast').html(data);
			}
		}).done(function() {
			$("#"+rslttarget).delay(2500).fadeOut('fast');
		});
	});
	
	
	
	//CLICK SHIFT CHECK BOXES
	$("#pagebody").on("click",".shiftchkbox",function() {
		//Update staff shifts
		//get date
		var timestampv = $("#calendartarget").val();
		
		//pull json array - IF ANY
		//iterate and view element we wish to change.
		//make change el.prop.prop = new
		//Alert parsed json element, see if changes made
		//insert into data attr

		// ### Check to make sure place is not closed after checkbox clicked. If closed, then OPEN.
		
		var ischecked = $(this).prop('checked'); //get check status of checkbox
		var thisid = $(this).attr('id');
		var thisshiftval = $(this).attr('data-shifttime');
		var staffid = $(this).attr('name');		
		var staffname = $(this).attr('data-name');	
		var stafflicense = $(this).attr('data-license');
		
		var amshiftval = (thisshiftval == "am" ) ? 'y' : 'n';
		var pmshiftval = (thisshiftval == "pm" ) ? 'y' : 'n';
		
		var update_mysql_flag = false;
						
		//get the json array
		var jsonArray = $('#shiftjsonarray').attr("data-s"+timestampv);
		var staffcount = $('#shiftjsonarray').attr("data-l"+timestampv);
		if (jsonArray) {

			var ja = jQuery.parseJSON(jsonArray);
			//Set a counter (so we can find the last element) and begin iteration
			var each_counter = 0;
			$.each(ja, function(j_key,j_item) {
				//If the store was closed, open it:
				if (j_key == "closed") {
					//The store is closed. Open it and then allow updates
					if (j_item == 'y') {
						//click Open.
						$("#storeclosedN").trigger('click');
						//clear store status from json array
						$("#shiftjsonarray").attr('data-s'+timestampv,"");
						//buttons are disabled if store is closed - makes scripting easier.
/*
						//build array
						var newstaffshift = { 
							"name" : staffname, 
							"license": stafflicense,
							"id": staffid,
							"am": amshiftval,
							"pm": pmshiftval
						}
						//convert json object to text
						var text_newstaffshift = $.toJSON(newstaffshift);
						//build array
						var txtreplacement = "{\""+staffid+"\":"+text_newstaffshift+"}";
						//insert into html DOM
						$("#shiftjsonarray").attr('data-s'+timestampv,txtreplacement);
						$("#shiftjsonarray").attr('data-l'+timestampv,1);
									
						// ## UPDATE MYSQL
						update_mysql_flag = true;
						
						return false; //breaks $.EACH loop but continues in IF function
*/
					}
				}

				//Check if this staff member is already working one shift?
//				if (parseInt(j_item.id) == parseInt(staffid)) {
				if (j_item.id == staffid) {					
					//If this staff member is in the jsonArray:				
					//Check if the shift is now checked off (added)
					if ( ischecked==true ) {
						//NEW SHIFT
						//check and add a shift to an existing staffID
						(thisshiftval == "am" ) ? j_item.am = 'y' : j_item.pm = 'y';
						$("#shiftjsonarray").attr('data-s'+timestampv,$.toJSON(ja));
						//we don't increase the shift count because this is an existing staffID
						
						// ##
						// ##
						// ## UPDATE MYSQL
						update_mysql_flag = true;
						
						//exit iteration
						return false;
					} else {
						//REMOVING A SHIFT
						//uncheck - remove the shift
						(thisshiftval == "am" ) ? j_item.am = 'n' : j_item.pm = 'n';
						$("#shiftjsonarray").attr('data-s'+timestampv,$.toJSON(ja));
						
						
						// ##
						// ##
						// ## UPDATE MYSQL
						update_mysql_flag = true;
						
						//exit iteration
						return false;
					}
					return false; //end EACH loop
				} else {
					//DOES NOT MATCH or NO MATCH FOUND
					if ( (ischecked == true ) && ( each_counter == (staffcount-1) )) {
						//No match
						//JSON Array exists for this date but this staff member is not in the array
						//add shift manually
						
						//build array
						var newstaffshift = { 
							"name" : staffname, 
							"license": stafflicense,
							"id": staffid,
							"am": amshiftval,
							"pm": pmshiftval
						}
						
						var text_newstaffshift = $.toJSON(newstaffshift);
						
						//jsonArray
						var txtreplacement = jsonArray.replace("}}","},\""+staffid+"\":"+text_newstaffshift+"}");
												
						// ##
						// ##
						// ## UPDATE MYSQL
						update_mysql_flag = true;

					} else {
						//IGNORE
						//Let the Each loop cycle so that it can find the correct person
					}
				} //end of If ID = ID
				each_counter++;
			});		
		} else {
			//No data for this date (because no JSON array)			
			//create new data array
		
			var amshiftval = (thisshiftval == "am" ) ? 'y' : 'n';
			var pmshiftval = (thisshiftval == "pm" ) ? 'y' : 'n';
			
			//build array
			var newstaffshift = { 
				"name" : staffname, 
				"license": stafflicense,
				"id": staffid,
				"am": amshiftval,
				"pm": pmshiftval
			}
			//convert json object to text
			var text_newstaffshift = $.toJSON(newstaffshift);
			//build array
			var txtreplacement = "{\""+staffid+"\":"+text_newstaffshift+"}";
			//insert into html DOM
			$("#shiftjsonarray").attr('data-s'+timestampv,txtreplacement);
			$("#shiftjsonarray").attr('data-l'+timestampv,1);
						
			// ##
			// ##
			// ## UPDATE MYSQL
			update_mysql_flag = true;

		}

		if (update_mysql_flag == true) {
			
			var checkboxvalue = $(this).val();
			var getparams = $(this).attr("data-getparams");
			
			//add to parameters to include date and id
			var geturl = $(this).attr("data-geturl");
			var postshifttime = $(this).attr("data-shifttime");			
			var uncheckedvalue = $(this).attr("data-uncheckedvalue");
			var rslttarget = $(this).attr("data-rslttargetprefix")+$(this).attr("id");
			var id = $(this).attr("id");
			if ($(this).prop('checked') == true) {
				var newval = checkboxvalue;
			} else {
				var newval = uncheckedvalue;
			}
			$.ajax({
				type: "POST",
				url: geturl,
				data: getparams+"&value="+newval+"&shifttime="+postshifttime+"&shiftdate="+timestampv,
				success: function(data){
					$("#"+rslttarget).fadeIn('fast').html(data);
					if ($("#"+id).attr("data-disablechecked") == 'y') {
						$("#"+id).prop('disabled',true);
					}
				}
			}).done(function() {
				$("#"+rslttarget).delay(2500).fadeOut('fast');
			});			
		}
	});
	
	
	//ENABLE INACTIVE STAFF
	$("#pagebody").on("click",".staffchkbox",function(event) {
		//show any hidden fields in the shift div
		//update mysql database to activate employee
		//that is all
		var staffID = $(this).attr("data-staffid");

		var staffparameters = "&table=therapists&row=th_active_in_spa&idprop=th_id&id="+staffID;
		
		if ( $(this).prop('checked') == true ) {
			$("#staffshiftrow"+staffID).fadeIn();
			$("#staffshiftrow"+staffID).addClass("activeemployee");
			$("#staffshiftrow"+staffID).removeClass("inactiveemployee");
			$("#staffrow"+staffID).removeClass("divinactive");

			var staffvalue = "y";	
				// ##$table,$row,$_POST['idprop'],$_POST['idval'],$value
		} else {
			$("#staffshiftrow"+staffID).fadeOut();
			$("#staffshiftrow"+staffID).removeClass("activeemployee");
			$("#staffshiftrow"+staffID).addClass("inactiveemployee");
			$("#staffrow"+staffID).addClass("divinactive");

			var staffvalue = "n";
		}
	});
	
	//SUBMIT NEW STAFF	
	function submit_add_new_staff(event) {
		var therapistname = $('#therapistname').val();
		var licensenumber = $('#licensenumber').val();
		var entityname = $('#entity').val();
	/* 	var songartist = $('#songartist').attr('value'); */
		var aboutgirl = $('#aboutgirl').val();
		var twitter = $('#twitter').val();
		
		if ((therapistname) && (licensenumber)) {
			var test = therapistname.length+licensenumber.length;
		} else test=0;
		
		if (test > 15) {
			//POST the form data 
			//$("#musicrequestrslt").html("test: "+songname+" "+songartist+" "+songplaywhen);
			$.ajax({
				type: "POST",
				url: "update_rows.php?",
				data: "&addstaff=y&therapistname="+encodeURIComponent(therapistname)+"&entity="+encodeURIComponent(entityname)+"&licensenumber="+encodeURIComponent(licensenumber)+"&aboutgirl="+encodeURIComponent(aboutgirl)+"&twitter="+twitter,
				success: function(data){
					$("#musicrequestrslt").html(data);			
					$("#addnewstaffform")[0].reset();
					
					//Refresh schedule editor
					$.ajax({
						type: "GET",
						url: "staff_shift_list.php",
						data: "",
						success: function(data){
							$("#staff_shift_list").html(data);
						}
					}).done(function() {
						if ($(".calendar").hasClass("cal-selected")) {
							$(".activeemployee").fadeIn();
						}
					});
					
					
					//refresh staff list
					$.ajax({
						type: "GET",
						url: "staff_list.php",
						data: "",
						success: function(data){
							$("#staff_list").html(data);
						}
					});

					//refresh staff editor
					$.ajax({
						type: "GET",
						url: "staff_editprofile_inlay.php",
						data: "",
						success: function(data){
							$("#editstaffprofiles").html(data);
						}
					});
					
					
					
					//scroll to staff list
					scrollToJQaak ("#staff_list",600)
					

					
				}
			});
		} else {
			$("#musicrequestrslt").html("<span class='redtext b'>You have not entered enough information, the license number is at least 11 digits long.</span>");
//			$("#musicrequestrslt").delay(2500).html(' ');			
			return false;
		}
	}
	
	// !Delete Staff
	$("#pagebody").on("click",".deletestaff",function(event) {
		var resp = confirm("Delete this employee?");
		if (resp == true) {
			var staffid = $(this).attr('data-staffid');
			var galleryid = $(this).attr('data-galleryid');
			$.ajax({
				type: "POST",
				url: "update_rows.php?",
				data: "&deletestaff="+staffid+"&galleryid="+galleryid,
				success: function(data){
					$("#deleterslts"+staffid).html(data);
					$("#staffrow"+staffid).slideUp("fast",function(){ $(this).remove(); });
					$("#staffshiftrow"+staffid).slideUp("fast",function(){ $(this).remove(); });
					$("#editProfileID"+staffid).slideUp("fast",function(){ $(this).remove(); });
				}
			});
			scrollToJQaak ("#staff_list",200)
		}
		event.preventDefault();
	});	
	
	// !addnewstaff on click
	$("#pagebody").on("click",".addnewstaff",function(event) {
		$("#addnewstaffform").submit();
	});	
	
	// !Submit NewStaff Form
	$("#addnewstaffform").submit(function(event) {
		submit_add_new_staff(event);
		event.preventDefault();
	});

	$("#pagebody").on("click",".editStaffPostButton",function() {
		
		var staffid = $(this).attr("data-staffid");
		$(".editProfileIDs").slideUp();
		//400,function() {
			$("#editProfileID"+staffid).slideDown(400,function(){
				scrollToJQaak ("#editProfileID"+staffid,400);
			});
		//});
		event.preventDefault();
	});
	




	
	
	/*  *****	Old Functions	*****  */
	$("#pagebody").on("click","a.scroll-link",function(event) {
		event.preventDefault();
		scrollToJQaak($(this).attr('href'),1000);
	});



	// !LinkNewGallery to imagebrowser
	$("#pagebody").on("click",".linkNewgallery",function(event) {
		event.preventDefault();
	//	scrollToJQaak($(this).attr('href'),1000);
		
		var browserID = $(this).attr('data-browserid');
		var newGalleryID = $("#"+browserID+"_inputtarget").val();
		var therapistID = $(this).attr('data-otherid');		//browserID.substring(16,5);
		
alert(therapistID+" "+newGalleryID+" "+browserID);
		
		prepare_results_box("#"+browserID+"_results");
		$.ajax({
			type: "POST",
			url: "update_rows.php",
			data: "&showresults=y&value="+newGalleryID+"&table=therapists"+"&row=th_galleryID"+"&idprop=th_id&updatechkboxes=y&idval="+therapistID,
			success: function(data){
				$("#"+browserid+"_results").html(data);
				show_ajax_results("#"+browserID+"_results");
				
				$("#"+browserID+"_results").append("Reload page to see results.");
			}
		});
		
	});



	/*  *****	Old Functions	*****  */
 


	

/*
	
$(".imgbox2").fadeIn(5000, function(){
    $(".imgbox2").css({
                'background-image' : 'url("img/2.jpg")',
                'visibility' : 'visible'
                });
});	
	
*/

	//uncheck cannot attend if attending is checked:
	$("#pagebody").on("click",".rsvpckbx",function() {
		$("#cannotattend").prop("checked",false);
	});
	//uncheck cannot attend if attending is checked:
	$("#pagebody").on("click","#cannotattend",function() {
		$(".rsvpckbx").prop("checked",false);
	});
		
	//SEND REMINDER EMAILS
	//<a href='#' class='sendreminderemail' data-recip='".$gu['u_userid']."'>Send</a>";
	$("#pagebody").on("click",".sendreminderemail",function() {
		event.preventDefault();
		var recip = $(this).attr("data-recip");
		var recipID = $(this).attr("id");
		$.ajax({
			type: "POST",
			url: "update_site_rows.php",
			data: "&rsvpreminder=y&recip="+recip,
			beforeSend: function(data) {
				$("#"+recipID).after("<img id='loadingicon' src='../images/lightbox-ico-loading.gif' width='16' height='16' />");
			},
			success: function(data){
				$("#"+recipID).after("<p>Sent Today</p>");
				$("#loadingicon").remove();
				$("#"+recipID).delay(1).fadeOut('fast');
			}
		}).done(function() {
			//nothing
		});
		
	});

	//ACTIVATE GENERIC CHECKBOX/CONTROL
	$("#pagebody").on("click",".chkboxclick",function() {
		var checkboxvalue = $(this).val();
		var getparams = $(this).attr("data-getparams");
		var geturl = $(this).attr("data-geturl");
		var uncheckedvalue = $(this).attr("data-uncheckedvalue");
		var rslttarget = $(this).attr("data-rslttargetprefix")+$(this).attr("id");
		var id = $(this).attr("id");
		if ($(this).prop('checked') == true) {
			var newval = checkboxvalue;
		} else {
			var newval = uncheckedvalue;
		}
		$.ajax({
			type: "POST",
			url: geturl,
			data: getparams+"&value="+newval,
			success: function(data){
				$("#"+rslttarget).fadeIn('fast').html(data);
				if ($("#"+id).attr("data-disablechecked") == 'y') {
					$("#"+id).prop('disabled',true);
				}
			}
		}).done(function() {
			$("#"+rslttarget).delay(2500).fadeOut('fast');
		});
		
	});

	
	//SHOW HIDDEN FIELDS - GENERIC
	$("#pagebody").on("click",".showhidden",function(event) {
		event.preventDefault();
		var substrid = $(this).attr("data-showhiddenid");
		$("#"+substrid).slideToggle();
		return false;
	});	
		
	$("#pagebody").on("click",".substrshow",function(event) {
		event.preventDefault();
		var substrid = $(this).attr("data-idx");
		$("#substr"+substrid).hide();
		$("#substrhidden"+substrid).show();
		return false;
	});
	
/*
	
	//setup product gallery
	$("#photoshow a").lightBox();
	
	//setup product gallery
	$("#photoshow a.lightB").lightBox();

	$("#pagebody a.lightB").lightBox();	
*/

	//hide the infodivs on the bridal party page after loading the gallery boxes
	$(".hide_on_load").hide();
	





	//SETUP TABS FOR EDIT MODE
	$("#reportspage").on("click",".tabs li",function(event) {
		event.preventDefault();
		var thistabwrapperID = $(this).parents(".tabs_wrapper").attr("id");
		//	First remove class "active" from currently active tab
		$("#"+thistabwrapperID + " #tabs li").removeClass('activated');
	
		//	Now add class "active" to the selected/clicked tab
		$(this).addClass("activated");
	
		//	Hide all tab content
		$("#"+thistabwrapperID + " .tab_content").hide();
	
		//	Here we get the href value of the selected tab
		var selected_tab = $(this).find("a").attr("href");
	
		//	Show the selected tab content
		$(selected_tab).fadeIn();
	/*
		$(selected_tab).promise().done(function() {
			var thisid = $(selected_tab+" .tabck").attr('id');
			alert(thisid);
			CKEDITOR.inline(thisid); 
			
		});
	*/
		
		//	At the end, we add return false so that the click on the link is not executed
		return false;
	})
	


	
	
	$("#musicrequestform").submit(function(event) {
		submit_musicrequestform(event);
		event.preventDefault();
	});	
	
	
	$("#form_contactusdirect").submit(function(event) {
		submit_contactform(event);
		event.preventDefault();
	});		


	$("#pagebody").on("click",".nodismiss",function() {
		event.preventDefault();
		$("#havenotrsvpd").fadeOut('fast');
/*
		var substrid = $(this).attr("data-imgid");
		var r = confirm("Delete this link?");
		if (r) {
			$.ajax({
				type: "POST",
				url: "updateslideshow_rows.php",
				data: "&index_admin=y&deletelink="+substrid,
				success: function(data){
					$("#editrow"+substrid).fadeOut();
					$("#lbr"+substrid).fadeOut();
					$("#weblink"+substrid).fadeOut();

					$("#links-resultsrow-"+substrid).fadeIn('fast').html(data);
					
					
				}
			}).done(function() {
				$("#links-resultsrow-"+substrid).delay(20000).fadeOut('fast');
			});
		} else {
			//cancel / false
		}
		return false;
*/
	});



	
});

function check_checkbox(chkid) {
	if ($("#"+chkid).prop('checked') == 'true') { 
		var chkpublic = "y";
	} else {
		var chkpublic = "n";
	}
	return chkpublic;	
}


function submit_contactform(event) {
	var chkpublic = check_checkbox("contactuspublic");
	var hidenames = check_checkbox("hidenames");	
/*
	if ($("#contactuspublic").prop('checked') == 'true') { 
		var chkpublic = "y";
	} else {
		var chkpublic = "n";
	}
*/
	var message = $("#text_contactusdirect").val();
	var enc_message = encodeURIComponent(message);	
	//check to make sure something was submitted
	if (message.length > 10) {
		$.ajax({
			type: "POST",
			url: "update_site_rows.php",
			data: "&pmsg=y&message="+enc_message+"&allowpublic="+chkpublic+"&userid="+$("#submitbyu_userid").val()+"&hidenames="+hidenames,
			success: function(data){
				$("#rslt_contactusdirect").fadeIn('fast').html(data);
				$("#form_contactusdirect")[0].reset();
			}
		}).done(function() {
			$("#rslt_contactusdirect").delay("2500").fadeOut();
		});
	} else {
		$("#rslt_contactusdirect").fadeIn('fast').html("Your message is not long enough.").delay("3000").fadeOut();
	}
}



function submit_musicrequestform(event) {
	var songname = $('#songname').attr('value');
	var songartist = $('#songartist').attr('value');
/* 	var songartist = $('#songartist').attr('value'); */
	var songplaywhen = $('#songplaywhen').attr('value');
	var songuserid = $('#songuserid').attr('value');
	
	var test = songname+" "+songartist+songplaywhen;
	if (test.length > 10) {		
		//POST the form data 
		//$("#musicrequestrslt").html("test: "+songname+" "+songartist+" "+songplaywhen);
		$.ajax({
			type: "POST",
			url: "musicrequest_post.php?",
			data: "&songname="+encodeURIComponent(songname)+"&songartist="+encodeURIComponent(songartist)+"&songplaywhen="+encodeURIComponent(songplaywhen)+"&songuserid="+songuserid,
			success: function(data){
				$("#musicrequestrslt").html(data);			
				$("#musicrequestform")[0].reset();
				
				$("#musicrequestlist").html("<div style='text-align: center; margin-right: auto; margin-left: auto; width: 37px;'><img src='galleryfiles/icons/load.gif'></div>");
				
				//Refresh the list of songs
				$.ajax({
					type: "GET",
					url: "musicrequest_list.php",
					data: "",
					success: function(data){
						$("#musicrequestlist").html(data);
					}
				});
				
			}
		});		
	} else {
		$("#musicrequestrslt").html("You have not entered enough information.");
	}
	

}



// !JQUERY SCROLL to JQ Element $(".asdf,#asdf")
function scrollToJQaak (jqObj,milli) {
	if (!milli) { milli = 1500; }
	$('html, body').animate({
	    scrollTop: $(jqObj).offset().top
	}, milli);
}

function followLink(url,target,callbackfunc) {
	$.ajax({
		type: "GET",
		url: url,
		data: "",
		success: function(data){
			$("#"+target).html(data);
		}
	});
}