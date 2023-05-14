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
// session_start();
$boom_access = 0;
require("db_conn.php");
require("variable.php");
require("function.php");
if(!isset($_COOKIE['user_id']) || !isset($_COOKIE['utk'])){
    echo json_encode(array("check" => 99));
}
$mysqli = @new mysqli(BOOM_DHOST, BOOM_DUSER, BOOM_DPASS, BOOM_DNAME);
if(mysqli_connect_errno()){
    echo json_encode(array("check" => 199));
    die();
}
$pass = escape($_COOKIE['utk']);
$ident = escape($_COOKIE['user_id']);
$get_data = $mysqli->query("SELECT 
system_id, default_theme, site_description, domain, guest_talk, allow_logs, language, timezone, speed, user_sex, gender_ico, act_delay,
user_id, user_name, join_msg, last_action, user_lang, user_timezone, user_status, username_color, user_rank, user_roomid, user_sound, session_id, pcount,
user_news, user_mute, user_regmute, user_banned, user_kick, user_role, user_action, room_mute,
topic, room_id, delete_logs, rcaction,
(SELECT count( DISTINCT hunter ) FROM wali_private WHERE target = '$ident' AND hunter != '$ident'  AND wali_private.status = '0') as private_count
FROM users, settings, rooms
WHERE user_id = '$ident' AND user_password = '$pass' AND settings.id = '1' AND rooms.room_id = user_roomid");
if($get_data->num_rows > 0){
	$data = $get_data->fetch_assoc();
	require("language/English/language.php");
	date_default_timezone_set($data['user_timezone']);
	$boom_access = 1;
	// $ignore = getIgnore();
	session_write_close();
}
else {
	echo json_encode( array("check" => 99));
	die();
}
?>