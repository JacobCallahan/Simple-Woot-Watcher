<?php    
	session_start();
	
	if (!isset($_SESSION["pages"])){
		$_SESSION["pages"] = "111000000"; //the on/off state of the 
	}

	if (@$_POST["wootOffCheck"]){
		$_SESSION["wootoff"] = "true";
	}
	else {		
		$_SESSION["wootoff"] = "false";
	}

	if (@$_POST["watchItem"]){
		$newWatchItems = "";
		foreach ($_POST["watchItem"] as $currentItem) {
			$newWatchItems .= $currentItem . ",";
		}  
		$_SESSION["watching"] = $newWatchItems;
	}

	if (!isset($_SESSION["currentProducts"])){
		$_SESSION["currentProducts"] = array(0 => "",
										1 => "",
										2 => "",
										3 => "",
										4 => "",
										5 => "",
										6 => "",
										7 => "",
										8 => "",
										9 => "",);  
	}

	if (!isset($_SESSION["watching"])){
		$_SESSION["watching"] = "";  
	}

	if (@$_POST["pictureCheck"]){
		$_SESSION["pictures"] = "false";
	}
	else {		
		$_SESSION["pictures"] = "true";
	}

	$newPages = "";
	if (@$_POST["page0"]) {$newPages = $newPages."1";} else{$newPages = $newPages."0";}
	if (@$_POST["page1"]) {$newPages = $newPages."1";} else{$newPages = $newPages."0";}
	if (@$_POST["page2"]) {$newPages = $newPages."1";} else{$newPages = $newPages."0";}
	if (@$_POST["page3"]) {$newPages = $newPages."1";} else{$newPages = $newPages."0";}
	if (@$_POST["page4"]) {$newPages = $newPages."1";} else{$newPages = $newPages."0";}
	if (@$_POST["page5"]) {$newPages = $newPages."1";} else{$newPages = $newPages."0";}
	if (@$_POST["page6"]) {$newPages = $newPages."1";} else{$newPages = $newPages."0";}
	if (@$_POST["page7"]) {$newPages = $newPages."1";} else{$newPages = $newPages."0";}
	if (@$_POST["page8"]) {$newPages = $newPages."1";} else{$newPages = $newPages."0";}
	if (@$_POST["page9"]) {$newPages = $newPages."1";} else{$newPages = $newPages."0";}
	if ($newPages == "0000000000") {$newPages = "1110000000";}
	$_SESSION["pages"] = $newPages;

?>

<!DOCTYPE html>
<html>
	<head>
		<meta name="description" content="Free, easy to use, customizable Woot watcher.">
		<meta name="keywords" content="Woot Watcher, Simple, Easy, flexible, ajax, free, fast">
		<meta name="author" content="Jacob J Callahan">
		<meta charset="UTF-8">

		<title>Simple Woot Watcher</title>
		<link rel="shortcut icon" href="favicon.ico" >
		<link rel="stylesheet" type="text/css" href="woot/styles.css">

		<script src="//ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js"></script>
		<script>
			//This site was created by Jacob J Callahan (jacob.callahan05@gmail.com) as a simple weekend project
			//This is the meat and potatoes of the whole thing!			

			var currentProducts = <?php echo json_encode($_SESSION['currentProducts']); ?>;

			function newInputBox() {
				var thisButton = $('#newBoxButton');
				var newInput = "<input type='text' class='watchInput' name='watchItem[]' placeholder='Keyword'/><br/>";
				$(newInput).hide().insertBefore(thisButton).fadeIn();
			};

			function getContent(longWootName, shortWootName, idNumber) {
				$.post("woot/wootHelper.php",
			    {
			      page:longWootName  //".sellout-woot"
			    },
			    function(data,status) {
	    			$productTitle = $(".title", $.parseHTML(data)).html();

	    			//see if it is a new product. if so, then do some nifty things and update the session variable
	    			if (currentProducts[idNumber] != $productTitle) { 
	    				$(".control").append("<embed src='woot/new.mp3' hidden=true autostart=true loop=false>");
					    $(shortWootName).addClass('green');   //let's highlight it!
	    				currentProducts[idNumber] = $productTitle;
	    				$.post("woot/productHelper.php",
					    {
					      products:currentProducts  //the array with the current products
					    },
			    		function(data,status) {
			    			//nothing to do right now
			    		});
			    		//add the listener to remove the green
			    		$(shortWootName).hover(function () {
							$(shortWootName).removeClass('green');
						});
	    			}
	    			if ($("span.min", $.parseHTML(data)).length != 0) {  //if we have a min price, we have a max
	    				$productPrice = $("span.min", $.parseHTML(data)).html() + " - " + $("span.max", $.parseHTML(data)).html();
	    			}
	    			else {   //if not, then just give the regular price
	    				$productPrice = $("span.price", $.parseHTML(data)).html();
	    			}

	    			$(shortWootName).attr('title', $productPrice);
	    			$productPic = "";
					$tempTitle = $productTitle.replace("&amp;","&");
	    			<?php
						if ($_SESSION["pictures"] == "false") {
							echo "//";
						}
					?>
	    			$productPic = "<br/><img class='productImage' src='" + $("img[alt='" + $tempTitle + "']", $.parseHTML(data)).attr("src") + "'/>";
	    			$productPage = $("a.photo", $.parseHTML(data)).attr("href");
	    			if ($(".progress-bar", $.parseHTML(data)).length != 0) {
					   $(shortWootName).addClass('yellow');
					   $(shortWootName).removeClass('dark');
					   $productPic += "<br/>" + $(".progress-bar", $.parseHTML(data)).children().css("width");
					}
					else {						
					   $(shortWootName).removeClass('yellow');
					}
				  	$("#title" + idNumber).html($productTitle + $productPic);
				  	$("#page" + idNumber + "Link").attr("href", $productPage) ;
				  	$(shortWootName).removeClass('red');
				  	$('.watchInput').each(function(){
					 	if (this.value.length > 0) {
					 		if ($productTitle.toLowerCase().indexOf(this.value.toLowerCase())>= 0) {
			  					$(".control").append("<embed src='woot/alert.mp3' hidden=true autostart=true loop=false>");
					 			$(shortWootName).addClass('red');
								// $(shortWootName).removeClass('yellow');
								// $(shortWootName).removeClass('dark');
					 		}
					 	}
					});					  		
				});
			};

			$(document).ready(function() {

				reloadWoot();
				function reloadWoot() {	

					if ($('#woot').length != 0) {
						getContent(".woot", "#woot", 0);
					}

					if ($('#tech').length != 0) {
						getContent(".tech-woot", "#tech", 1);
					}

					if ($('#home').length != 0) {
						getContent(".home-woot", "#home", 2);
					}

					if ($('#tools').length != 0) {
						getContent(".tools-woot", "#tools", 3);
					}

					if ($('#sport').length != 0) {
						getContent(".sport-woot", "#sport", 4);
					}

					if ($('#access').length != 0) {
						getContent(".accessories-woot", "#access", 5);
					}

					if ($('#kids').length != 0) {
						getContent(".kids-woot", "#kids", 6);
					}

					if ($('#shirt').length != 0) {
						getContent(".shirt-woot", "#shirt", 7);
					}

					if ($('#wine').length != 0) {
						getContent(".wine-woot", "#wine", 8);
					}

					if ($('#sellout').length != 0) {
						getContent(".sellout-woot", "#sellout", 9);
					}
				}
				setInterval(reloadWoot, <?php if ($_SESSION["wootoff"] == "true") {echo "10000"; } else {echo "4320000";} ?>);

			});
		</script>

	</head>
	<body>
		<div class="main">
			<div class="wootHolder">
				<?php
					if (substr($_SESSION["pages"], 0, 1)==1) {
						echo "<a href='#' id='page0Link' target='_blank'><div class='wootContent dark' id='woot'><div class='title' id='title0'>Loading...</div></div></a>";
					}
					if (substr($_SESSION["pages"], 1, 1)==1) {
						echo "<a href='#' id='page1Link' target='_blank'><div class='wootContent dark' id='tech'><div class='title' id='title1'>Loading...</div></div></a>";
					}
					if (substr($_SESSION["pages"], 2, 1)==1) {
						echo "<a href='#' id='page2Link' target='_blank'><div class='wootContent dark' id='home'><div class='title' id='title2'>Loading...</div></div></a>";
					}
					if (substr($_SESSION["pages"], 3, 1)==1) {
						echo "<a href='#' id='page3Link' target='_blank'><div class='wootContent dark' id='tools'><div class='title' id='title3'>Loading...</div></div></a>";
					}
					if (substr($_SESSION["pages"], 4, 1)==1) {
						echo "<a href='#' id='page4Link' target='_blank'><div class='wootContent dark' id='sport'><div class='title' id='title4'>Loading...</div></div></a>";
					}
					if (substr($_SESSION["pages"], 5, 1)==1) {
						echo "<a href='#' id='page5Link' target='_blank'><div class='wootContent dark' id='access'><div class='title' id='title5'>Loading...</div></div></a>";
					}
					if (substr($_SESSION["pages"], 6, 1)==1) {
						echo "<a href='#' id='page6Link' target='_blank'><div class='wootContent dark' id='kids'><div class='title' id='title6'>Loading...</div></div></a>";
					}
					if (substr($_SESSION["pages"], 7, 1)==1) {
						echo "<a href='#' id='page7Link' target='_blank'><div class='wootContent dark' id='shirt'><div class='title' id='title7'>Loading...</div></div></a>";
					}
					if (substr($_SESSION["pages"], 8, 1)==1) {
						echo "<a href='#' id='page8Link' target='_blank'><div class='wootContent dark' id='wine'><div class='title' id='title8'>Loading...</div></div></a>";
					}
					if (substr($_SESSION["pages"], 9, 1)==1) {
						echo "<a href='#' id='page9Link' target='_blank'><div class='wootContent dark' id='sellout'><div class='title' id='title9'>Loading...</div></div></a>";
					}
				?>
			</div>
			<div class="control dark">
				<h3>Woot-off Mode?</h3>
				<?php
					$wootoffCheck = "";
					if ($_SESSION["wootoff"] == "true") {$wootoffCheck = "checked";}
					echo "<div class='minibox'><input type='checkbox' name='wootOffCheck' ".$wootoffCheck." value='1' form='content'/>Enabled</div><br/>";
				?>
				<h3>Woot Pages</h3>
				<form action="index.php" id="content" enctype="multipart/form-data" method="post">
					<?php
						$page[0] = ""; $page[1] = ""; $page[2] = ""; $page[3] = ""; $page[4] = "";
						$page[5] = ""; $page[6] = ""; $page[7] = "";  $page[8] = "";  $page[9] = "";
						
						if (substr($_SESSION["pages"], 0, 1)==1) {$page[0] = "checked";}
						if (substr($_SESSION["pages"], 1, 1)==1) {$page[1] = "checked";}
						if (substr($_SESSION["pages"], 2, 1)==1) {$page[2] = "checked";}
						if (substr($_SESSION["pages"], 3, 1)==1) {$page[3] = "checked";}
						if (substr($_SESSION["pages"], 4, 1)==1) {$page[4] = "checked";}
						if (substr($_SESSION["pages"], 5, 1)==1) {$page[5] = "checked";}
						if (substr($_SESSION["pages"], 6, 1)==1) {$page[6] = "checked";}
						if (substr($_SESSION["pages"], 7, 1)==1) {$page[7] = "checked";}
						if (substr($_SESSION["pages"], 8, 1)==1) {$page[8] = "checked";}
						if (substr($_SESSION["pages"], 9, 1)==1) {$page[9] = "checked";}
						
						echo "<div class='minibox'><input type='checkbox' name='page0' ".$page[0]." value='1' form='content'/>Woot</div><br/>";
						echo "<div class='minibox'><input type='checkbox' name='page1' ".$page[1]." value='1' form='content'/>Tech</div><br/>";
						echo "<div class='minibox'><input type='checkbox' name='page2' ".$page[2]." value='1' form='content'/>Home</div><br/>";
						echo "<div class='minibox'><input type='checkbox' name='page3' ".$page[3]." value='1' form='content'/>Tools</div><br/>";
						echo "<div class='minibox'><input type='checkbox' name='page4' ".$page[4]." value='1' form='content'/>Sport</div><br/>";
						echo "<div class='minibox'><input type='checkbox' name='page5' ".$page[5]." value='1' form='content'/>Accessories</div><br/>";
						echo "<div class='minibox'><input type='checkbox' name='page6' ".$page[6]." value='1' form='content'/>Kids</div><br/>";
						echo "<div class='minibox'><input type='checkbox' name='page7' ".$page[7]." value='1' form='content'/>Shirt</div><br/>";
						echo "<div class='minibox'><input type='checkbox' name='page8' ".$page[8]." value='1' form='content'/>Wine</div><br/>";
						echo "<div class='minibox'><input type='checkbox' name='page9' ".$page[9]." value='1' form='content'/>Sellout</div><br/>";
					?>					
					<h3>Words to Watch</h3>
					<?php
						if (strlen($_SESSION["watching"]) > 0) {
							$watchArray = explode(",", $_SESSION["watching"]);
							foreach ($watchArray as $watchItem) {
								if (strlen($watchItem) > 0) {
									echo "<input type='text' class='watchInput' name='watchItem[]' value='".$watchItem."'/><br/>";
								}
							}
						}
					?>
					<input type="text" class="watchInput" name="watchItem[]" placeholder="Keyword"/><br/>
					<button id="newBoxButton" onclick="newInputBox();" type="button">Add</button>
					<h3>Extra Settings</h3>
					<?php
						$pictureCheck = "";
						if ($_SESSION["pictures"] == "false") {$pictureCheck = "checked";}
						echo "<div class='minibox'><input type='checkbox' name='pictureCheck' ".$pictureCheck." value='1' form='content'/>Disable Pictures</div><br/>";
					?>
					<br/>
					<input type="submit" value="Save All Changes" />
				</form>
				<br/>
				<div id="donate">
					Help me keep this page running<br/> and AD-FREE!<br/>
					<form action="https://www.paypal.com/cgi-bin/webscr" method="post" target="_blank">
					<input type="hidden" name="cmd" value="_s-xclick">
					<input type="hidden" name="encrypted" value="-----BEGIN PKCS7-----MIIHNwYJKoZIhvcNAQcEoIIHKDCCByQCAQExggEwMIIBLAIBADCBlDCBjjELMAkGA1UEBhMCVVMxCzAJBgNVBAgTAkNBMRYwFAYDVQQHEw1Nb3VudGFpbiBWaWV3MRQwEgYDVQQKEwtQYXlQYWwgSW5jLjETMBEGA1UECxQKbGl2ZV9jZXJ0czERMA8GA1UEAxQIbGl2ZV9hcGkxHDAaBgkqhkiG9w0BCQEWDXJlQHBheXBhbC5jb20CAQAwDQYJKoZIhvcNAQEBBQAEgYCmg+Q6U1ZdAynnjru85pnlyV8HjxRzh6KGyCrN71isFU9ugHtfK0C4PchyXbVYtYD4TnshYzGpsDJpDj3JFt16CHAi7I5beV4IrIVRU27WsIKV1Ha3utRFYqbXLdXOL0MAoLzHtV7aptYO7tOODq6Y9/tqHIgusyxpHzMseIZXYDELMAkGBSsOAwIaBQAwgbQGCSqGSIb3DQEHATAUBggqhkiG9w0DBwQIBe6xU/ULpwaAgZBkGWGYYJ56t+fIUA++LGXLYpQlWYoH3fl9IRsj1EzsCHsbsuW0CKXoHe6uLGKd94+9JZZ6sQ+in77e3giSRx0jjWjOxTnxbxKe/R1DuVFoPi3kgyyrjgSK9SvyZWhOxRW/QI0RinvURUgOZ468N6G293jtqZ2DiBFYt6p/SYjENbxPctMkiXxL2QXwi+5thxCgggOHMIIDgzCCAuygAwIBAgIBADANBgkqhkiG9w0BAQUFADCBjjELMAkGA1UEBhMCVVMxCzAJBgNVBAgTAkNBMRYwFAYDVQQHEw1Nb3VudGFpbiBWaWV3MRQwEgYDVQQKEwtQYXlQYWwgSW5jLjETMBEGA1UECxQKbGl2ZV9jZXJ0czERMA8GA1UEAxQIbGl2ZV9hcGkxHDAaBgkqhkiG9w0BCQEWDXJlQHBheXBhbC5jb20wHhcNMDQwMjEzMTAxMzE1WhcNMzUwMjEzMTAxMzE1WjCBjjELMAkGA1UEBhMCVVMxCzAJBgNVBAgTAkNBMRYwFAYDVQQHEw1Nb3VudGFpbiBWaWV3MRQwEgYDVQQKEwtQYXlQYWwgSW5jLjETMBEGA1UECxQKbGl2ZV9jZXJ0czERMA8GA1UEAxQIbGl2ZV9hcGkxHDAaBgkqhkiG9w0BCQEWDXJlQHBheXBhbC5jb20wgZ8wDQYJKoZIhvcNAQEBBQADgY0AMIGJAoGBAMFHTt38RMxLXJyO2SmS+Ndl72T7oKJ4u4uw+6awntALWh03PewmIJuzbALScsTS4sZoS1fKciBGoh11gIfHzylvkdNe/hJl66/RGqrj5rFb08sAABNTzDTiqqNpJeBsYs/c2aiGozptX2RlnBktH+SUNpAajW724Nv2Wvhif6sFAgMBAAGjge4wgeswHQYDVR0OBBYEFJaffLvGbxe9WT9S1wob7BDWZJRrMIG7BgNVHSMEgbMwgbCAFJaffLvGbxe9WT9S1wob7BDWZJRroYGUpIGRMIGOMQswCQYDVQQGEwJVUzELMAkGA1UECBMCQ0ExFjAUBgNVBAcTDU1vdW50YWluIFZpZXcxFDASBgNVBAoTC1BheVBhbCBJbmMuMRMwEQYDVQQLFApsaXZlX2NlcnRzMREwDwYDVQQDFAhsaXZlX2FwaTEcMBoGCSqGSIb3DQEJARYNcmVAcGF5cGFsLmNvbYIBADAMBgNVHRMEBTADAQH/MA0GCSqGSIb3DQEBBQUAA4GBAIFfOlaagFrl71+jq6OKidbWFSE+Q4FqROvdgIONth+8kSK//Y/4ihuE4Ymvzn5ceE3S/iBSQQMjyvb+s2TWbQYDwcp129OPIbD9epdr4tJOUNiSojw7BHwYRiPh58S1xGlFgHFXwrEBb3dgNbMUa+u4qectsMAXpVHnD9wIyfmHMYIBmjCCAZYCAQEwgZQwgY4xCzAJBgNVBAYTAlVTMQswCQYDVQQIEwJDQTEWMBQGA1UEBxMNTW91bnRhaW4gVmlldzEUMBIGA1UEChMLUGF5UGFsIEluYy4xEzARBgNVBAsUCmxpdmVfY2VydHMxETAPBgNVBAMUCGxpdmVfYXBpMRwwGgYJKoZIhvcNAQkBFg1yZUBwYXlwYWwuY29tAgEAMAkGBSsOAwIaBQCgXTAYBgkqhkiG9w0BCQMxCwYJKoZIhvcNAQcBMBwGCSqGSIb3DQEJBTEPFw0xMzEwMjQxNzE4MjVaMCMGCSqGSIb3DQEJBDEWBBR1t3+kuwD2xgq1B2MV4XqFnK5FxzANBgkqhkiG9w0BAQEFAASBgEXbVmSvLVLFCyMqQsXKLGhMshwxHI5P5E+8+x29MVdK3I6wIdvf08aeog1wK7r9M4q2DUW1exhQGLR6/He80TQyN9zVW8hYeh2qLIrXBQUhQUUOh0g6n6yqeVrN2y+LGeqvF0id1bV4n1o6sisSsKHZaQrCVoEtv7m+FA2HQB/H-----END PKCS7-----
					">
					<input type="image" src="https://www.paypalobjects.com/en_US/i/btn/btn_donate_LG.gif" border="0" name="submit" alt="PayPal - The safer, easier way to pay online!">
					<img alt="" border="0" src="https://www.paypalobjects.com/en_US/i/scr/pixel.gif" width="1" height="1">
					</form>
				</div>
			</div>
		</div>
	</body>
</html>

