<?php
	
	require "simple_html_dom.php"; //we'll use this to narrow the results

	//todo: security!!
	//this function will grab the contents of a page and return the html
	//$page = $_POST['page'];
	$url = "http://www.woot.com/";

	$html = file_get_html($url);
	$ret = $html->find("#global-header");
	$newRet = implode(" ", $ret);
	$str = $newRet;

	echo $newRet;

?>
