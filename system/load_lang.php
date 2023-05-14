<?php 
require('config.php');
if(isset($_POST['lang'])){
	$lang = boomSanitize($_POST['lang']);
	if(file_exists('system/language/' . $lang . '/language.php')){
		setBoomLang($lang);
	}
}
?>