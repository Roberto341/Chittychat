<?php
/**
* Codychat
* @package Codychat
* @author www.boomcoding.com
* @copyright 2020
* @terms any use of this script without a legal license is prohibited 
* all the content of Codychat is the propriety of BoomCoding and Cannot be 
* used for another project.
*/
session_start();
$wali_access = 0;
require("database.php");
require("variable.php");
require("function.php");
if(!checkToken() || !isset($_COOKIE[WALI_PREFIX . 'userid']) || !isset($_COOKIE[WALI_PREFIX . 'utk'])){
	echo json_encode( array("check" => 99));
	die();
}
$mysqli = @new mysqli(WALI_DHOST, WALI_DUSER, WALI_DPASS, WALI_DNAME);
if (mysqli_connect_errno()){
	echo json_encode( array("check" => 199));
	die();
}
$pass = escape($_COOKIE[WALI_PREFIX . 'utk']);
$ident = escape($_COOKIE[WALI_PREFIX . 'userid']);
$get_data = $mysqli->query("SELECT 
system_id, default_theme, site_description, domain, guest_talk, allow_logs, bbfv, language, timezone, speed, user_sex, gender_ico, act_delay,
user_id, user_name, user_join, join_msg, last_action, user_language, user_timezone, user_status, user_color, user_rank, user_roomid, user_sound, session_id, pcount,
user_news, user_mute, user_regmute, user_banned, user_kick, user_role, user_action, room_mute, naction,
topic, room_id, rcaction, rldelete, rltime,
(SELECT count( DISTINCT hunter ) FROM wali_private WHERE target = '$ident' AND hunter != '$ident'  AND status = '0') as private_count
FROM wali_users, wali_setting, wali_rooms
WHERE user_id = '$ident' AND user_password = '$pass' AND id = '1' AND room_id = user_roomid");
if($get_data->num_rows > 0){
	$data = $get_data->fetch_assoc();
	require("language/{$data['user_language']}/language.php");
	date_default_timezone_set($data['user_timezone']);
	$wali_access = 1;
	$ignore = getIgnore();
	session_write_close();
}
else {
	echo json_encode( array("check" => 99));
	die();
}
?>