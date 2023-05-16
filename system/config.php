<?php
/**
 * Walichat
 * 
 * @package Walichat
 * @author  www.wali-chat.com
 * @copyright 2023
 * @terms any use of this script without a legal license is prohibited
 * all the content of walichat is the propriety of Wali and Cannot be 
 * used for another project.
 */
session_start();
$wali_access = 0;
require("database.php");
require("variable.php");
require("function.php");
require("function_2.php");
$mysqli = @new mysqli(WALI_DHOST, WALI_DUSER, WALI_DPASS, WALI_DNAME);
if (mysqli_connect_errno() || $check_install != 1) {
	if($check_install != 1){
		$chat_install = 2;
	}
	else{
		$chat_install = 3;
	}
}
else{
	$chat_install = 1;
	if(isset($_COOKIE[WALI_PREFIX . 'userid']) && isset($_COOKIE[WALI_PREFIX . 'utk'])){
		$ident = escape($_COOKIE[WALI_PREFIX . 'userid']);
		$pass = escape($_COOKIE[WALI_PREFIX . 'utk']);
		$get_data = $mysqli->query("SELECT wali_setting.*, wali_users.* FROM wali_users, wali_setting WHERE wali_users.user_id = '$ident' AND wali_users.user_password = '$pass' AND wali_setting.id = '1'");
		if($get_data->num_rows > 0){
			$data = $get_data->fetch_assoc();
			$wali_access = 1;
		}
		else {
			$get_data = $mysqli->query("SELECT * FROM wali_setting WHERE wali_setting.id = '1'");
			$data = $get_data->fetch_assoc();
			sessionCleanup();
		}
	}
	else {
		$get_data = $mysqli->query("SELECT * FROM wali_setting WHERE wali_setting.id = '1'");
		$data = $get_data->fetch_assoc();
		sessionCleanup();
	}
	$cur_lang = getLanguage();
	require("language/" . $cur_lang . "/language.php");
}
if($chat_install == 1){
	date_default_timezone_set("{$data['timezone']}");
}
else {
	date_default_timezone_set("America/Montreal");
}
?>