<?php
require('../config.php');
if(isset($_POST['user_language'], $_POST['user_timezone'], $_POST['user_country'])){
	$ut = escape($_POST['user_timezone']);
	$ul = escape($_POST['user_language']);
	$uc = escape($_POST['user_country']);

	$mysqli->query("UPDATE wali_users SET user_timezone = '$ut', user_language = '$ul', country = '$uc' WHERE user_id = '{$data['user_id']}'");
	echo 1;
	die();	
}
?>