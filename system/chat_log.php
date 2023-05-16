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
require_once('config_chat.php');

$chat_history = 25;
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

    // main chat logs part
    if($fload == 0){
		$log = $mysqli->query("SELECT log.*, 
		wali_users.user_name, wali_users.user_color, wali_users.user_font, wali_users.user_rank, wali_users.wccolor, wali_users.user_sex, wali_users.user_age, wali_users.user_avatar, wali_users.user_cover, wali_users.country, wali_users.user_bot
		FROM ( SELECT * FROM `wali_chat` WHERE `post_roomid` = {$data['user_roomid']}  AND post_id > '$last' ORDER BY `post_id` DESC LIMIT $chat_history) AS log
		LEFT JOIN wali_users ON log.user_id = wali_users.user_id
		ORDER BY `post_id` ASC
		");
		$ssnum = 1;
	}
	else {
		if($caction != $data['rcaction']){
				$log = $mysqli->query("SELECT log.*,
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
					$d['mlogs'] .= createLog($data, $chat);
				}
			}
		}
	}


    // private logs part
	if($preload == 1){
		$privlog = $mysqli->query("
		SELECT 
		log.*, wali_users.user_id, wali_users.user_name, wali_users.user_color, wali_users.user_avatar, wali_users.user_bot 
		FROM ( SELECT * FROM `wali_private` WHERE  `hunter` = '{$data['user_id']}' AND `target` = '$priv'  OR `hunter` = '$priv' AND `target` = '{$data['user_id']}' ORDER BY `id` DESC LIMIT $private_history) AS log 
		LEFT JOIN users
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
?>