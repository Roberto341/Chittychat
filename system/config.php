<?php
// session_start();
$boom_access = 0;
require("db_conn.php");
require("variable.php");
require("function_2.php");
require("function.php");
$mysqli = @new mysqli(BOOM_DHOST, BOOM_DUSER, BOOM_DPASS, BOOM_DNAME);
if (mysqli_connect_errno()) {
	echo "Failed to connect to database";
	die();
}
else{
	$chat_install = 1;
	if(isset($_COOKIE['user_id']) && isset($_COOKIE['utk']) || isset($_COOKIE['username'])){           
		$ident = escape($_COOKIE['user_id']);
		$get_data = $mysqli->query("SELECT settings.*, users.* FROM users, settings WHERE user_id = '$ident' AND settings.id = '1'");
		if($get_data->num_rows > 0){
			$data = $get_data->fetch_assoc();
			$boom_access = 1;
		}
		else {
			$get_data = $mysqli->query("SELECT * FROM settings WHERE settings.id = '1'");
			$data = $get_data->fetch_assoc();
			sessionCleanup();
		}
	}
	else {
		$get_data = $mysqli->query("SELECT * FROM settings WHERE settings.id = '1'");
		$data = $get_data->fetch_assoc();
		sessionCleanup();
	}
	$cur_lang = getLanguage();
	require("language/English/language.php");
}
if($chat_install == 1){
	date_default_timezone_set("{$data['timezone']}");
}
else {
	date_default_timezone_set("America/Montreal");
}
?>