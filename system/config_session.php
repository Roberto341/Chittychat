<?php
session_start();
$wali_access = 0;
require("database.php");
require("variable.php");
require("function.php"); 
require("function_2.php");
if(!checkToken() || !isset($_COOKIE[WALI_PREFIX . 'userid']) || !isset($_COOKIE[WALI_PREFIX . 'utk'])){
	die();
}
$mysqli = @new mysqli(WALI_DHOST, WALI_DUSER, WALI_DPASS, WALI_DNAME);
if (mysqli_connect_errno()){
	die();
}
$ident = escape($_COOKIE[WALI_PREFIX . 'userid']);
$pass = escape($_COOKIE[WALI_PREFIX . 'utk']);
$get_data = $mysqli->query("SELECT wali_setting.*, wali_users.* FROM wali_users, wali_setting WHERE wali_users.user_id = '$ident' AND wali_users.user_password = '$pass' AND wali_setting.id = '1'");
if($get_data->num_rows > 0){
	$data = $get_data->fetch_assoc();
	$wali_access = 1;
}
else {
	die();
}
require("language/{$data['user_language']}/language.php");
date_default_timezone_set($data['user_timezone']);
?>