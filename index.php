<?php    
	//thanks! http://wallydavid.com/simple-php-mobile-website-redirect-code
	$iphone = strpos($_SERVER['HTTP_USER_AGENT'],"iPhone");
	$android = strpos($_SERVER['HTTP_USER_AGENT'],"Android");
	$palmpre = strpos($_SERVER['HTTP_USER_AGENT'],"webOS");
	$berry = strpos($_SERVER['HTTP_USER_AGENT'],"BlackBerry");
	$ipod = strpos($_SERVER['HTTP_USER_AGENT'],"iPod");

	if ($iphone || $android || $palmpre || $ipod || $berry == true) 
	{	    
		echo "<script>window.location='http://m.simplewootwatcher.com/index.php'</script>";
	}


	session_start();

	$shortNameArry = array("woot", "electronics", "computers", "home", "tools", "sport", "accessories", "kids", "shirt", "wine", "sellout")	
	$CATEGORY_COUNT = 11;
	$DEFAULT_PAGES = "11100000000";
	
	if (!isset($_SESSION["pages"])){
		$_SESSION["pages"] = $DEFAULT_PAGES; //the on/off state of the 
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
										9 => "",
										10 => "",);  
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

	$testPages = "";
	for ($i = 0; $i < $CATEGORY_COUNT; $i++) {
		if (@$_POST["page" . $i]) {$newPages = $newPages."1";} else{$newPages = $newPages."0";}
		$testPages .= "0";
	}
	if ($newPages == $testPages) {$newPages = $DEFAULT_PAGES;}
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

			function getContent(longWootName, shortWootName, idNumber, ajaxData) {
					var narrowedPage = $(longWootName, ajaxData);
					var productTitle = $(narrowedPage).find(".title").html();

	    			//see if it is a new product. if so, then do some nifty things and update the session variable
	    			if (currentProducts[idNumber] != productTitle) { 
	    				$(".control").append("<embed src='woot/new.mp3' hidden=true autostart=true loop=false>");
					    $(shortWootName).addClass('green');   //let's highlight it!
	    				currentProducts[idNumber] = productTitle;
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
	    			
	    			$(shortWootName).attr('title', shortWootName);
	    			$productPic = "";
					$tempTitle = productTitle.replace("&amp;","&");
	    			<?php
						if ($_SESSION["pictures"] == "false") {
							echo "//";
						}
					?>

	    			$productPic = "<br/><img class='productImage' src='" + $(narrowedPage).find("img[alt='" + $tempTitle + "']").attr("src") + "'/>";
	    			
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
				  	$(shortWootName).removeClass('red');
				  	$('.watchInput').each(function(){
					 	if (this.value.length > 0) {
					 		if (productTitle.toLowerCase().indexOf(this.value.toLowerCase())>= 0) {
			  					$(".control").append("<embed src='woot/alert.mp3' hidden=true autostart=true loop=false>");
					 			$(shortWootName).addClass('red');
					 		}
					 	}
					});	
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

						$i = 0;
						foreach ($shortNameArry as $value) {
							if ($value === "woot") {
								echo "if ($('#" . $shortName . "').length != 0) {
									getContent('." . $shortName . "', '#" . $shortName . "', " . $i . ", ajaxData);
								}";
							} else {
								echo "if ($('#" . $shortName . "').length != 0) {
									getContent('." . $shortName . "-woot', '#" . $shortName . "', " . $i . ", ajaxData);
								}";
							}
							$i++;
						}
				    });
				}
				setInterval(reloadWoot, <?php if ($_SESSION["wootoff"] == "true") {echo "10000"; } else {echo "43200000";} ?>);
				console.log("Feel free to browse! - Jake");
			});
		</script>

	</head>
	<body>
		<div class="main">
			<div class="wootHolder">
				<?php
					for ($i = 0; $i < $CATEGORY_COUNT; $i++) {
						if (substr($_SESSION["pages"], $i, 1)==1) {
							echo "<a href='#' id='page" . $i . "Link' target='_blank'>
									<div class='wootContent dark' id='sport'>
										<div class='title' id='title" . $i . "'>
											Loading...
										</div>
									</div>
								  </a>";
						}
						$page[$i] = "";
						if (substr($_SESSION["pages"], $i, 1)==1) {$page[$i] = "checked";}
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
					<?phfor ($i = 0; $i < $CATEGORY_COUNT; $i++) {
						$page[$i] = "";
						if (substr($_SESSION["pages"], $i, 1)==1) {$page[$i] = "checked";}
						echo "<div class='minibox'><input type='checkbox' name='page" . $i . "' ".$page[$i]." value='1' form='content'/>" . $shortNameArry[$i] . "</div><br/>";
					}
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
			</div>
		</div>
	</body>
</html>

