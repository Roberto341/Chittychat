<?php 
require('config.php');
if(isset($_POST['lang'])){
	$lang = waliSanitize($_POST['lang']);
	if(file_exists('system/language/' . $lang . '/language.php')){
		setWaliLang($lang);
	}
}
?>