<?php    
	
	session_start();

	if (@$_POST["pictureCheck"]){
		$_SESSION["pictures"] = "false";
	}
	else {		
		$_SESSION["pictures"] = "true";
	}

?>

<!DOCTYPE html>
<html>
	<head>
		<meta name="description" content="Free, easy to use, customizable Woot watcher.">
		<meta name="keywords" content="Woot Watcher, Simple, Easy, flexible, ajax, free, fast">
		<meta name="author" content="Jacob J Callahan">
		<meta name="viewport" content="width=device-width; initial-scale=1.0; maximum-scale=1.0; user-scalable=0;"/>
		<meta charset="UTF-8">

		<title>Simple Woot Watcher</title>
		<link rel="shortcut icon" href="favicon.ico" >
		<link rel="stylesheet" type="text/css" href="woot/mobileStyles.css">

		<script src="//ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js"></script>
		<script>
			//This site was created by Jacob J Callahan (jacob.callahan05@gmail.com) as a simple weekend project
			//This is the meat and potatoes of the whole thing!			
			
			function getContent(longWootName, shortWootName, idNumber, ajaxData) {
					var narrowedPage = $(longWootName, ajaxData);
					var productTitle = $(narrowedPage).find(".title").html();

	    			$productPic = "";
					$tempTitle = productTitle.replace("&amp;","&");
	    			<?php
						if ($_SESSION["pictures"] == "false") {
							echo "//";
						}
					?>

	    			$productPic = "<br/><img class='productImage' src='" + $(narrowedPage).find("img[alt='" + $tempTitle + "']").attr("src") + "'/>";
					
					//get the price
					if ($(narrowedPage).find("span.min").length != 0) {  //if we have a min price, we have a max
	    				$productPrice = $(narrowedPage).find("span.min").html() + " - " + $(narrowedPage).find("span.max").html();
	    			}
	    			else {   //if not, then just give the regular price
	    				$productPrice = $(narrowedPage).find("span.price").html();
	    			}
					$productPic += "<br/>" + $productPrice;	    			

	    			$productPage = $(narrowedPage).find("a.photo").attr("href");
	    			if ($(narrowedPage).find(".progress-bar").length != 0) {
					   $(shortWootName).addClass('yellow');
					   $(shortWootName).removeClass('dark');
					   $productPic += "<br/>" + $(narrowedPage).find(".progress-bar").children().css("width");
					}
					else {						
					   $(shortWootName).removeClass('yellow');
					}
				  	$("#title" + idNumber).html(productTitle + $productPic);
				  	$("#page" + idNumber + "Link").attr("href", $productPage) ;
			};

			$(document).ready(function() {
				reloadWoot();
				function reloadWoot() {	
					var ajaxData = "";
					$.post("woot/wootHelper.php",
				    {
				      page:"woot"  //".sellout-woot"
				    },
				    function(data,status) {
				    	ajaxData = data;  
				    	//console.log(ajaxData);				    	

						if ($('#woot').length != 0) {
							getContent(".woot", "#woot", 0, ajaxData);
						}

						if ($('#electronics').length != 0) {
							getContent(".electronics-woot", "#electronics", 1, ajaxData);
						}

						if ($('#computers').length != 0) {
							getContent(".computers-woot", "#computers", 2, ajaxData);
						}

						if ($('#home').length != 0) {
							getContent(".home-woot", "#home", 3, ajaxData);
						}

						if ($('#tools').length != 0) {
							getContent(".tools-woot", "#tools", 4, ajaxData);
						}

						if ($('#sport').length != 0) {
							getContent(".sport-woot", "#sport", 5, ajaxData);
						}

						if ($('#access').length != 0) {
							getContent(".accessories-woot", "#access", 6, ajaxData);
						}

						if ($('#kids').length != 0) {
							getContent(".kids-woot", "#kids", 7, ajaxData);
						}

						if ($('#shirt').length != 0) {
							getContent(".shirt-woot", "#shirt", 8, ajaxData);
						}

						if ($('#wine').length != 0) {
							getContent(".wine-woot", "#wine", 9, ajaxData);
						}

						if ($('#sellout').length != 0) {
							getContent(".sellout-woot", "#sellout", 10, ajaxData);
						}
				    });
				}
				setInterval(reloadWoot, <?php if ($_SESSION["wootoff"] == "true") {echo "10000"; } else {echo "4320000";} ?>);

				$('.refreshButton').bind('touchstart mousedown', function(){	
					reloadWoot();					
				});
			});
		</script>

	</head>
	<body>
		<div class="main">
			<div class="wootHolder">
				<a href='#' id='page0Link' target='_blank'><div class='wootContent dark' id='woot'><div class='title' id='title0'>Loading...</div></div></a>
				
				<a href='#' id='page1Link' target='_blank'><div class='wootContent dark' id='electronics'><div class='title' id='title1'>Loading...</div></div></a>
				 
				<a href='#' id='page2Link' target='_blank'><div class='wootContent dark' id='computers'><div class='title' id='title2'>Loading...</div></div></a>
			
				<a href='#' id='page3Link' target='_blank'><div class='wootContent dark' id='home'><div class='title' id='title3'>Loading...</div></div></a>
				
				<a href='#' id='page4Link' target='_blank'><div class='wootContent dark' id='tools'><div class='title' id='title4'>Loading...</div></div></a>
				
				<a href='#' id='page5Link' target='_blank'><div class='wootContent dark' id='sport'><div class='title' id='title5'>Loading...</div></div></a>
				
				<a href='#' id='page6Link' target='_blank'><div class='wootContent dark' id='access'><div class='title' id='title6'>Loading...</div></div></a>
				
				<a href='#' id='page7Link' target='_blank'><div class='wootContent dark' id='kids'><div class='title' id='title7'>Loading...</div></div></a>
				
				<a href='#' id='page8Link' target='_blank'><div class='wootContent dark' id='shirt'><div class='title' id='title8'>Loading...</div></div></a>
				
				<a href='#' id='page9Link' target='_blank'><div class='wootContent dark' id='wine'><div class='title' id='title9'>Loading...</div></div></a>
				
				<a href='#' id='page10Link' target='_blank'><div class='wootContent dark' id='sellout'><div class='title' id='title10'>Loading...</div></div></a>
				<br/><br/><br/><br/><br/>
			</div>
		</div>
		<div id="footer">
			<div class="refreshButton">Refresh</div> 
			<form action="https://www.paypal.com/cgi-bin/webscr" method="post" target="_blank">
				<input type="hidden" name="cmd" value="_s-xclick">
				<input type="hidden" name="encrypted" value="-----BEGIN PKCS7-----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-----END PKCS7-----
				">				
				<input type="submit" class="donateButton" name="submit" value="Donate"/>
								<!-- <input type="image" class="donateButton" border="0" name="submit" alt="Donate"> -->
			</form>
		</div>
	</body>
</html>

