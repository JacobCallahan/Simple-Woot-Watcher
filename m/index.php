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
		</div>
	</body>
</html>

