<?php
//staff_layout.php
?>

		
		<!-- ################	FADED ROW	################	-->	
		<div id='staff' class='pageMasterRow' style='position: relative; margin: 0px 0px; background: rgba(220,220,220,.7); padding: 100px 0px 45px 0px;'>
			<div class='pagerow' style='position: relative; margin: 0px; margin-left: 70px; margin-right: 70px;'>
				<div class='imagemenu' style='position: relative; float: left; width: 420px;'>
					<h1 class=''><span id='span_staffname'><?php echo $staffname; ?></span></h1>
					<p><span id='span_staffID'><?php echo $staffnum; ?></span></p>
					<div class='clearall' style='clear: both; float: none;'></div>
				</div>
				<div class='clearall' style='clear: both; float: none;'></div>

			</div>
			<div class='clearall' style='clear: both; float: none;'></div>


			<div class='pagerow' style='position: relative; margin: 45px; margin-left: 70px; margin-right: 70px;'>			
				<!--	### new row -->
				<div style='position: relative; float: left; margin-left: -30%; width: 100%;'>		
					<div style='position: relative; float: left; margin: 0 350px 0 30%;'>
						<div style='position: relative; padding: 10px;'>
							<?php
							if ($th_twitterhandle) {
							?>
							<a class="twitter-timeline" height="400" width="425" href="https://twitter.com/<?php echo $th_twitterpage; ?>" data-widget-id="549738187497934850">Tweets by @<?php echo $th_twitterpage; ?></a> <script>!function(d,s,id){var js,fjs=d.getElementsByTagName(s)[0],p=/^http:/.test(d.location)?'http':'https';if(!d.getElementById(id)){js=d.createElement(s);js.id=id;js.src=p+"://platform.twitter.com/widgets.js";fjs.parentNode.insertBefore(js,fjs);}}(document,"script","twitter-wjs");</script>
							<?php
							}
							?>
<!--
							<p class='b'>Title</p>
							<p class=''>Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.</p>
-->
						</div>
					</div>
				</div>
				<div style='position: relative; float: left; margin-left: -350px; width: 350px;'>
					<div style='position: relative; padding: 10px;'>						
						<p><?php echo $staffabout; ?></p>
					</div>				
				</div>
				<div style='position: relative; float: right; width: 30%;'>
					<div style='position: relative; padding: 10px;'>						
<!-- 						<p><?php echo $staffabout; ?></p> -->
					</div>				
				</div>
				<div class='clearall' style='clear: both; float: none;'></div>
			</div>
			<div class='clearall' style='clear: both; float: none;'></div>
		</div>
		<div style='clear: both; float: none;'></div>
		<!-- #### END OF FADED ROW		 -->
		<!-- #################### -->

		


<?
		//employee info
		$staffname = $staff_array[0]['th_pseudoname'];
		$staffnum = $staff_array[0]['th_licensenum'];
		$staffgallery = $staff_array[0]['th_galleryID'];
		$staffabout = $staff_array[0]['th_about'];
		
		//profile images
		$th_profileimg_portrait = $staff_array[0]['th_profileimg_portrait'];
		$th_profileimg_landscape = $staff_array[0]['th_profileimg_landscape'];
		$th_profileimg_sq_rnd = $staff_array[0]['th_profileimg_portrait'];
		
		//social advertising
		$th_twitterhandle = $staff_array[0]['th_twitterhandle'];
		$th_twitterpage = $staff_array[0]['th_twitterpage'];
		$th_facebookpage = $staff_array[0]['th_facebookpage'];
		$th_instagrampage = $staff_array[0]['th_instagrampage'];
	

?>