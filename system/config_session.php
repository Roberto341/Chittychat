<?php
// session_start();
$boom_access = 0;
require_once("db_conn.php");
require_once("variable.php");
require_once("function.php"); 
require_once("function_2.php");
$mysqli = @new mysqli(BOOM_DHOST, BOOM_DUSER, BOOM_DPASS, BOOM_DNAME);
if (mysqli_connect_errno()){
	die();
}
$ident = escape($_COOKIE['user_id']);
$get_data = $mysqli->query("SELECT settings.*, users.* FROM users, settings WHERE user_id = '$ident' AND settings.id = '1'");
if($get_data->num_rows > 0){
	$data = $get_data->fetch_assoc();
	$boom_access = 1;
}
else {
	die();
}
?>