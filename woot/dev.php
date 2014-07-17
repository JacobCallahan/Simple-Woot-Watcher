<?php

$shortNameArry = array("woot", "electronics", "computers", "home", "tools", "sport", "accessories", "kids", "shirt", "wine", "sellout")	
$CATEGORY_COUNT = 11;
$DEFAULT_PAGES = "11100000000";




$testPages = "";
for ($i = 0; $i < $CATEGORY_COUNT; $i++) {
	if (@$_POST["page" . $i]) {$newPages = $newPages."1";} else{$newPages = $newPages."0";}
	$testPages .= "0";
}
if ($newPages == $testPages) {$newPages = $DEFAULT_PAGES;}




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


for ($i = 0; $i < $CATEGORY_COUNT; $i++) {
	$page[$i] = "";
	if (substr($_SESSION["pages"], $i, 1)==1) {$page[$i] = "checked";}
	echo "<div class='minibox'><input type='checkbox' name='page" . $i . "' ".$page[$i]." value='1' form='content'/>" . $shortNameArry[$i] . "</div><br/>";
}




	
?>