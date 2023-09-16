<?php

/**
 * Walichat
 * @package Walichat
 * @author www.wali-chat.com
 * @copyright 2023
 * @terms any use of this script without a legal license is prohibited 
 * all the content of walichat is the propriety of Wali-Chat and Cannot be 
 * used for another project.
 */
require_once('config_chat.php');

$chat_history = 20;
$chat_substory = 20;
$private_history = 18;
$status_delay = $data['last_action'] + 21;
$out_delay = time() - 1800;

if(isset($_POST['last'], $_POST['snum'], $_POST['caction'], $_POST['fload'], $_POST['preload'], $_POST['priv'], $_POST['lastp'], $_POST['pcount'], $_POST['room'], $_POST['notify'])){
	
	// clearing post data 
	$last = escape($_POST['last']);
	$fload = escape($_POST['fload']);
	$snum = escape($_POST['snum']);
	$caction = escape($_POST['caction']);
	$preload = escape($_POST['preload']);
	$priv = escape($_POST['priv']);
	$lastp = escape($_POST['lastp']);
	$pcount = escape($_POST['pcount']);
	$room = escape($_POST['room']);
	$notify = escape($_POST['notify']);
	
	if($room != $data['user_roomid']){
		echo json_encode( array("check" => 199));
		die();
	}
	
	// main chat part
	$d['mlogs'] = '';
	$d['plogs'] = '';
	$d['mlast'] = $last;
	$d['plast'] = $lastp;
	$main = 1; 
	$private = 1;
	$ssnum = 0;
	
	// join room message part
	if( time() > $status_delay || $fload == 0 ){
		$ip = getIp();
		if($fload == 0 && $data['join_msg'] == 0 || $data['last_action'] < $out_delay){
			joinRoom();
		}
		$mysqli->query("UPDATE wali_users SET join_msg = '1', last_action = '" . time() . "', user_ip = '$ip' WHERE user_id = '{$data['user_id']}'");
	}
	
	// notification check
	
	if($notify < $data['naction']){
		$get_notify = $mysqli->query("SELECT
		(SELECT count(*) FROM wali_friends WHERE target = '{$data['user_id']}' AND fstatus = '2' AND viewed = '0') as friend_count,
		(SELECT count(*) FROM wali_notification WHERE notified = '{$data['user_id']}' AND notify_view = '0') as notify_count,
		(SELECT count(*) FROM wali_report) as report_count,
		(SELECT count(*) FROM wali_news WHERE news_date > '{$data['user_news']}') as news_count
		");
		if($get_notify->num_rows == 1){
			$fetch = $get_notify->fetch_assoc();
			$d['use'] = 1;
			$d['friends'] = $fetch['friend_count'];
			$d['notify'] = $fetch['notify_count'];
			$d['news'] = $fetch['news_count'];
			$d['nnotif'] = $data['naction'];
			if(waliAllow(8)){
				$d['report'] = $fetch['report_count'];
			}
		}
	}
   
	// main chat logs part
	if($fload == 0){
		$log = $mysqli->query("
		SELECT log.*, 
		wali_users.user_name, wali_users.user_color, wali_users.user_font, wali_users.user_rank, wali_users.wccolor, wali_users.user_sex, wali_users.user_age, wali_users.user_avatar, wali_users.user_cover, wali_users.country, wali_users.user_bot
		FROM ( SELECT * FROM `wali_chat` WHERE `post_roomid` = {$data['user_roomid']}  AND post_id > '$last' ORDER BY `post_id` DESC LIMIT $chat_history) AS log
		LEFT JOIN wali_users ON log.user_id = wali_users.user_id
		ORDER BY `post_id` ASC
		");
		$ssnum = 1;
	}
	else {
		if($caction != $data['rcaction']){
				$log = $mysqli->query("
				SELECT log.*,
				wali_users.user_name, wali_users.user_color, wali_users.user_font, wali_users.user_rank, wali_users.wccolor, wali_users.user_sex, wali_users.user_age, wali_users.user_avatar, wali_users.user_cover, wali_users.country, wali_users.user_bot
				FROM ( SELECT * FROM `wali_chat` WHERE `post_roomid` = {$data['user_roomid']} AND post_id > '$last' ORDER BY `post_id` DESC LIMIT $chat_substory) AS log
				LEFT JOIN wali_users ON log.user_id = wali_users.user_id
				ORDER BY `post_id` ASC
				");
		}
		else {
			$main = 0;
		}
	}
	if($main == 1){
		if($log->num_rows > 0){
			while ($chat = $log->fetch_assoc()){
				$d['mlast'] = $chat['post_id'];
				if($chat['snum'] != $snum || $ssnum == 1){
					$d['mlogs'] .= createLog($data, $chat, $ignore);
				}
			}
		}
	}
	/*
	if(!delExpired($data['rltime'])){
		$d['del'] = array();
		$todelete = explode(",", $data['rldelete']);
		foreach($todelete as $delpost) {
			$delpost = trim($delpost);
			array_push($d['del'], $delpost);
		}
	}
	*/
	// private logs part
	if($preload == 1){
		$privlog = $mysqli->query("
		SELECT 
		log.*, wali_users.user_id, wali_users.user_name, wali_users.user_color, wali_users.user_avatar, wali_users.user_bot 
		FROM ( SELECT * FROM `wali_private` WHERE  `hunter` = '{$data['user_id']}' AND `target` = '$priv'  OR `hunter` = '$priv' AND `target` = '{$data['user_id']}' ORDER BY `id` DESC LIMIT $private_history) AS log 
		LEFT JOIN wali_users
		ON log.hunter = wali_users.user_id
		ORDER BY `time` ASC");
	}
	else {
		if($pcount != $data['pcount'] && $priv != 0){
			$privlog = $mysqli->query("
			SELECT 
			log.*, wali_users.user_id, wali_users.user_name, wali_users.user_color, wali_users.user_avatar, wali_users.user_bot
			FROM ( SELECT * FROM `wali_private` WHERE  `hunter` = '$priv' AND `target` = '{$data['user_id']}' AND id > '$lastp' OR hunter = '{$data['user_id']}' AND target = '$priv' AND id > '$lastp' AND file = 1 ORDER BY `id` DESC LIMIT $private_history) AS log 
			LEFT JOIN wali_users
			ON log.hunter = wali_users.user_id
			ORDER BY `time` ASC");
		}
		else {
			$private = 0;
		}
	}
	if($private == 1){
		if ($privlog->num_rows > 0){
			$mysqli->query("UPDATE `wali_private` SET `status` = 1 WHERE `hunter` = '$priv' AND `target` = '{$data['user_id']}'");
			while ($private = $privlog->fetch_assoc()){
				$d['plogs'] .= privateLog($private, $data['user_id']);
				$d['plast'] = $private['id'];
			}
		}
	}
	
	// topic part
	/*if($fload == 0){
		if($data['topic'] != ''){
			$d['top'] = getTopic($data['topic']);
		}
	}*/
	
	// room access part
	if(canEditRoom()){
		$d['rset'] = 1;
	}
	
	// room ranking
	if(haveRole($data['user_role'])){
		$d['role'] = $data['user_role'];
	}
	/*
	// mute check
	if(roomMuted()){
		$d['rm'] = 1;
	}
	if(guestMuted()){
		$d['rm'] = 2;
	}
	if(mutedData($data)){
		if(isMuted($data) || isRegmute($data)){
			$d['rm'] = 2;
		}
		else {
			userUnmute($data);
		}
	}
*/
	mysqli_close($mysqli);
	
	// sending results
	$d['pcount'] = $data['pcount'];
	$d['cact'] = $data['rcaction'];
	$d['act'] = $data['user_action'];
	$d['ses'] = $data['session_id'];
	$d['curp'] = $priv;
	$d['spd'] = (int)$data['speed'];
	$d['acd'] = $data['act_delay'];
	$d['pico'] = $data['private_count'];
	
	echo json_encode($d, JSON_UNESCAPED_UNICODE);
}
