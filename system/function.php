<?php 
function waliCode($code, $custom = array()){
	$def = array('code'=> $code);
	$res = array_merge($def, $custom);
	return json_encode( $res, JSON_UNESCAPED_UNICODE);
}
function getFileName($file){
    return $file;
}
function escape($t){
	global $mysqli;
	return $mysqli->real_escape_string(trim(htmlspecialchars($t, ENT_QUOTES)));
}
function myAvatar($a){
	return $a;
}
function checkToken() {
	global $wali;
    if (!isset($_POST['token']) || !isset($_SESSION[WALI_PREFIX . 'token']) || empty($_SESSION[WALI_PREFIX . 'token'])) {
        return false;
    }
	if($_POST['token'] == $_SESSION[WALI_PREFIX . 'token']){
		return true;
	}
    return false;
}
function canIgnore($user){
	if(!isStaff($user['user_rank'])&& !isBot($user) && !mySelf($user['user_id'])){
		return true;
	}
}
function ignore($id){
	global $mysqli, $data;
	$count_ignore = $mysqli->query("SELECT * FROM wali_ignore WHERE ignored = '$id' AND ignorer = '{$data['user_id']}'");
	if($count_ignore->num_rows < 1){
		$user = userDetails($id);
		if(empty($user)){
			return 3;
		}
		if(canIgnore($user)){
			$mysqli->query("INSERT INTO wali_ignore (ignorer, ignored, ignore_date) VALUES ('{$data['user_id']}', '$id', '" . time() . "')");
			$mysqli->query("DELETE FROM wali_friends WHERE hunter = '{$data['user_id']}' AND target = '$id' OR hunter = '$id' AND target = '{$data['user_id']}'");
			createIgnore();
			return 1;
		}
		else {
			return 0;
		}
	}
	else {
		return 2;
	}
}
function removeIgnore($id){
	global $mysqli, $data;
	$mysqli->query("DELETE FROM wali_ignore WHERE ignorer = '{$data['user_id']}' AND ignored = '$id'");
	createIgnore();
	return 1;
}
function createIgnore(){
	global $mysqli, $data, $wali;
	$ignore_list = '';
	$get_ignore = $mysqli->query("SELECT ignored FROM wali_ignore WHERE ignorer = '{$data['user_id']}'");
	while($ignore = $get_ignore->fetch_assoc()){
		$ignore_list .= $ignore['ignored'] . '|';
	}
	$_SESSION[WALI_PREFIX . 'ignore'] = '|' . $ignore_list;
}
function getIgnore(){
	global $wali;
	return $_SESSION[WALI_PREFIX . 'ignore'];
}
function isIgnored($ignore, $id){
	global $wali;
	if(strpos($ignore, '|' . $id . '|') !== false){
		return true;
	}
}
function waliRole($role){
	global $data;
	if($data['user_role'] >= $role){
		return true;
	}
}
function canDeleteRoomLog(){
	if(waliAllow(4) && waliRole(0)){
		return true;
	}
}
function canDeleteSelfLog($p){
	global $data, $wali;
	if($p['user_id'] == $data['user_id'] && waliAllow($wali['can_delete_slogs'])){
		return true;
	}
}
function isSystem($id){
	global $data;
	if($id == $data['system_id']){
		return true;
	}
}
function curRanking($type, $txt, $icon){
    return '<img src="default_images/rank/' . $icon . '" class="' . $type . '" title="'.$txt.'"/>';
}
function rankIcon($rank){
    switch($rank){
        case 1:
            return 'user.svg';
        case 2:
            return 'vip.svg';
        case 3:
            return 'mod.svg';
        case 4:
            return 'admin.svg';
        case 5:
            return 'super.svg';
        case 6:
            return 'owner.svg';
        default:
            return 'user.svg';
    }
}
function rankText($rank){
    switch($rank){
        case 1:
            return 'User';
        case 2:
            return 'VIP';
        case 3:
            return 'Moderator';
        case 4:
            return 'Admin';
        case 5:
            return 'Super Admin';
        case 6:
            return 'Owner';
        default:
            return 'User';
    }
}
function systemRank($rank, $type){
    switch($rank){
        case 1:
        case 2:
        case 3:
        case 4:
        case 5:
        case 6:
            return curRanking($type, rankText($rank), rankIcon($rank));
        default:
            return '';
    }
}

function chatRank($user){
	global $data;
	if(isBot($user)){
		return '';
	}
	$rank = systemRank($user['user_rank'], 'chat_rank');
	if($rank != ''){
		return $rank;
	}
}
function systemReplace($text){
	global $lang;
	$text = str_replace('%bcquit%', $lang['leave_message'], $text);
	$text = str_replace('%bcjoin%', $lang['join_message'], $text);
	$text = str_replace('%bcclear%', $lang['clear_message'], $text);
	$text = str_replace('%spam%', $lang['spam_content'], $text);
	$text = str_replace('%bcname%', $lang['name_message'], $text);
	$text = str_replace('%bckick%', $lang['kick_message'], $text);
	$text = str_replace('%bcban%', $lang['ban_message'], $text);
	$text = str_replace('%bcmute%', $lang['mute_message'], $text);
	return $text;
}
function processChatMsg($post) {
	global $data;
	if($post['user_id'] != $data['user_id'] && !preg_match('/http/',$post['post_message'])){
		$post['post_message'] = str_ireplace($data['user_name'], '<span class="my_notice">' . $data['user_name'] . '</span>', $post['post_message']);
	}
	return mb_convert_encoding(systemReplace($post['post_message']), 'UTF-8', 'auto');
}
function processPrivateMsg($post) {
	global $data;
	return mb_convert_encoding(systemReplace($post['message']), 'UTF-8', 'auto');
}
function ownAvatar($i){
	global $data;
	if($i == $data['user_id']){
		return 'glob_av';
	}
	return '';
}
function chatDate($date){
	return date("j/m G:i", $date);
}
function displayDate($date){
	return date("j/m G:i", $date);
}
function longDate($date){
	return date("Y-m-d ", $date);
}
function userGender($g){
	global $lang;
	switch($g){
		case 1:
			return $lang['male'];
		case 2:
			return $lang['female'];
		default:
			return '';
	}
}
function canDeleteLog(){
	global $wali;
	if(waliAllow(1) && waliAllow($wali['can_delete_logs'])){
		return true;
	}
}
function waliAllow($rank){
	global $data;
	if($data['user_rank'] >= $rank){
		return true;
	}
}
function avGender($s){
	global $data;
	if($data['gender_ico'] > 0){
		switch($s){
			case 1:
				return 'avsex boy';
			case 2:
				return 'avsex girl';
			case 3:
				return 'avsex nosex';
			default:
				return 'avsex nosex';
		}
	}
	else {
		return 'avsex nosex';
	}
}
function myColor($u){
	return $u['user_color'];
}
function myColorFont($u){
	return $u['user_color'] . ' ' . $u['user_font'];
}
function myTextColor($u){
	return $u['wccolor'] . ' ' . $u['wcbold'] . ' ' . $u['wcfont'];
}
function isBot($user){
	if($user['user_bot'] > 0){
		return true;
	}
}
function canReport(){
	global $wali;
	if(waliAllow($wali['can_report'])){
		return true;
	}
}
function createLog($data, $post){
	$log_options = '';
	$report = 0;
	$delete = 0;
	$m = 0;
	if(waliAllow($post['log_rank'])){
		return false;
	}
	if(canDeleteLog() || canDeleteRoomLog() || canDeleteSelfLog($post)){
		$delete = 1;
		$m++;
	}
	else if(canReport() && !isSystem($post['user_id'])){
		$report = 1;
		$m++;
	}
	if($m > 0){
		$log_options = '<div class="cclear" onclick="logMenu(this, ' . $post['post_id'] . ',' . $delete . ',' . $report . ');"><i class="fa fa-ellipsis-h"></i></div>';
	}
	return  '<li id="log' . $post['post_id'] . '" data="' . $post['post_id'] . '" class="ch_logs ' . $post['type'] . '">
				<div class="avtrig chat_avatar" onclick="avMenu(this,'.$post['user_id'].',\''.$post['user_name'].'\','.$post['user_rank'].','.$post['user_bot'].',\''.$post['country'].'\',\''.$post['user_cover'].'\',\''.$post['user_age'].'\',\''.userGender($post['user_sex']).'\');">
					<img class="cavatar avav ' . avGender($post['user_sex']) . ' ' . ownAvatar($post['user_id']) . '" src="' . myAvatar($post['user_avatar']) . '"/>
				</div>
				<div class="my_text">
					<div class="btable">
							<div class="cname">' . chatRank($post) . '<span class="username ' . myColorFont($post) . '">' . $post['user_name'] . '</span></div>
							<div class="cdate">' . chatDate($post['post_date']) . '</div>
							' . $log_options . '
					</div>
					<div class="chat_message ' . $post['tcolor'] . '">' . processChatMsg($post) . '</div>
				</div>
			</li>';
}

function privateLog($post, $hunter){
	if($hunter == $post['hunter']){
		return '<li id="priv' . $post['id'] . '">
					<div class="private_logs">
						<div class="private_avatar">
							<img data="' . $post['user_id'] . '" class="get_info avatar_private" src="' . myAvatar($post['user_avatar']) . '"/>
						</div>
						<div class="private_content">
							<div class="hunter_private">' . processPrivateMsg($post) . '</div>
							<p class="pdate">' . displayDate($post['time']) . '</p>
						</div>
					</div>
				</li>';
	}
	else {
		return '<li id="priv' . $post['id'] . '">
					<div class="private_logs">
						<div class="private_content">
							<div class="target_private">' . processPrivateMsg($post) . '</div>
							<p class="ptdate">' . displayDate($post['time']) . '</p>
						</div>
						<div class="private_avatar">
							<img data="' . $post['user_id'] . '" class="get_info avatar_private" src="' . myAvatar($post['user_avatar']) . '"/>
						</div>
					</div>
				</li>';
	}
}

function getDelay(){
	return time() - 35;
}
function getMinutes($t){
	return $t / 60;
}
function calMinutesUp($min){
	return time() + ($min * 60);
}
function clearNotifyAction($id, $type){
	global $mysqli;
	$mysqli->query("DELETE FROM wali_notification WHERE notified = '$id' AND notify_source = '$type'");
}
function muteLog($user){
	global $lang, $data, $wali;
	if(allowLogs() && $wali['action_log'] == 1 && userInRoom($user)){
		$content = str_replace('%user%', systemNameFilter($user), $lang['system_mute']);
		systemPostChat($user['user_roomid'], $content, array('type'=> 'system__action'));
	}
}
function waliNotify($type, $custom = array()){
	global $mysqli, $data;
	$def = array(
		'hunter'=> $data['system_id'],
		'target'=> 0,
		'room'=> $data['user_roomid'],
		'rank'=> 0,
		'delay'=> 0,
		'reason'=> '',
		'source'=> 'system',
		'sourceid'=> 0,
		'custom' => '',
		'custom2' => '',
	);
	$c = array_merge($def, $custom);
	if($c['target'] == 0){
		return false;
	}
	$mysqli->query("INSERT INTO wali_notification ( notifier, notified, notify_type, notify_date, notify_source, notify_id, notify_rank, notify_delay, notify_reason, notify_custom, notify_custom2) 
	VALUE ('{$c['hunter']}', '{$c['target']}', '$type', '" . time() . "', '{$c['source']}', '{$c['sourceid']}', '{$c['rank']}', '{$c['delay']}', '{$c['reason']}', '{$c['custom']}', '{$c['custom2']}')");
	updateNotify($c['target']); 
}
function waliConsole($type, $custom = array()){
	global $mysqli, $data;
	$def = array(
		'hunter'=> $data['user_id'],
		'target'=> $data['user_id'],
		'room'=> $data['user_roomid'],
		'rank'=> 0,
		'delay'=> 0,
		'reason'=> '',
		'custom' => '',
		'custom2' => '',
	);
	$c = array_merge($def, $custom);
	$mysqli->query("INSERT INTO wali_console (hunter, target, room, ctype, crank, delay, reason, custom, custom2, cdate) VALUES ('{$c['hunter']}', '{$c['target']}', '{$c['room']}', '$type', '{$c['rank']}', '{$c['delay']}', '{$c['reason']}', '{$c['custom']}', '{$c['custom2']}', '" . time() . "')");
}
function waliHistory($type, $custom = array()){
	global $mysqli, $data;
	$def = array(
		'hunter'=> $data['user_id'],
		'target'=> 0,
		'rank'=> 0,
		'delay'=> 0,
		'reason'=> '',
		'content'=> '',
	);
	$c = array_merge($def, $custom);
	if($c['target'] == 0){
		return false;
	}
	$mysqli->query("INSERT INTO wali_history (hunter, target, htype, delay, reason, history_date) VALUES ('{$c['hunter']}', '{$c['target']}', '$type',  '{$c['delay']}', '{$c['reason']}', '" . time() . "')");
}
function updateNotify($id){
	global $mysqli;
	$mysqli->query("UPDATE wali_users SET naction = naction + 1 WHERE user_id = '$id'");
}
function userInRoom($user){
	if($user['user_roomid'] != '0'){
		return true;
	}
}
function kickLog($user){
	global $lang, $data, $wali;
	if(allowLogs() && $wali['action_log'] == 1 && userInRoom($user)){
		$content = str_replace('%user%', systemNameFilter($user), $lang['system_kick']);
		systemPostChat($user['user_roomid'], $content, array('type'=> 'system__action'));
	}
}
function banLog($user){
	global $lang, $data, $wali;
	if(allowLogs() && $wali['action_log'] == 1 && userInRoom($user)){
		$content = str_replace('%user%', systemNameFilter($user), $lang['system_ban']);
		systemPostChat($user['user_roomid'], $content, array('type'=> 'system__action'));
	}
}

function getRankIcon($list, $type){
	if(isBot($list)){
		return botRank($type);
	}
	else if(haveRole($list['user_role']) && !isStaff($list['user_rank'])){
		return roomRank($list['user_role'], $type);
	}
	else {
		return systemRank($list['user_rank'], $type);
	}
}
function haveRole($role){
	if($role > 0){
		return true;
	}
}
function roomRankTitle($rank){
	global $lang;
	switch($rank){
		case 6:
			return $lang['r_owner'];
		case 5:
			return $lang['r_admin'];
		case 4:
			return $lang['r_mod'];
		default:
			return $lang['user'];
	}
}
function roomRankIcon($rank){
	switch($rank){
		case 6:
			return 'room_owner.svg';
		case 5:
			return 'room_admin.svg';
		case 4:
			return 'room_mod.svg';
		default:
			return 'user.svg';
	}
}
function roomRank($rank, $type){
	switch($rank){
		case 6:
		case 5:
		case 4:
			return curRanking($type, roomRankTitle($rank), roomRankIcon($rank));
		default:
			return '';
	}
}
function botRankTitle(){
	global $lang;
	return $lang['user_bot'];
}
function botRankIcon(){
	global $lang;
	return 'bot.svg';
}
function botRank($type){
	return curRanking($type, botRankTitle(), botRankIcon());
}
function isGuestMuted($user){
	global $data;
	if($user['user_rank'] == 0 && $data['guest_talk'] == 0){
		return true;
	}
}
function isRoomMuted($user){
	if($user['room_mute'] > 0){
		return true;
	}
}
function getMutedIcon($user, $c){
	global $lang;
	if(isGuestMuted($user)){
		return '<img title="' . $lang['view_only'] . '" class="' . $c . '" src="default_images/actions/guestmuted.svg"/>';
	}
	if(isRegmute($user)){
		return '<img title="' . $lang['muted'] . '" class="' . $c . '" src="default_images/actions/regmuted.svg"/>';
	}
	else if(isMuted($user)){
		return '<img title="' . $lang['muted'] . '" class="' . $c . '" src="default_images/actions/muted.svg"/>';
	}
	else if(isRoomMuted($user)){
		return '<img title="' . $lang['muted'] . '" class="' . $c . '" src="default_images/actions/room_muted.svg"/>';
	}
	else {
		return '';
	}
}
function useFlag($country){
	global $data;
	if($data['flag_ico'] > 0 && $country != 'ZZ' && $country != ''){
		return true;
	}
}
function countryFlag($country){
	global $data;
	return 'system/location/flag/' . $country . '.png';
}
function createUserlist($list){
	global $data, $lang;
	if(!isVisible($list)){
		return false;
	}
	$icon = '';
	$muted = '';
	$status = '';
	$mood = '';
	$flag = '';
	$offline = 'offline';
	$rank_icon = getRankIcon($list, 'list_rank');
	$mute_icon = getMutedIcon($list, 'list_mute');
	if(useFlag($list['country'])){
		$flag = '<div class="user_item_flag"><img src="' . countryFlag($list['country']) . '"/></div>';
	}
	if($rank_icon != ''){
		$icon = '<div class="user_item_icon icrank">' . $rank_icon . '</div>';
	}
	if($mute_icon != ''){
		$muted = '<div class="user_item_icon icmute">' . $mute_icon . '</div>';
	}
	if($list['last_action'] > getDelay() || isBot($list)){
		$offline = '';
		$status = getStatus($list['user_status'], 'list_status');
	}
	if(!empty($list['user_mood'])){
		$mood = '<p class="text_xsmall bustate bellips">' . $list['user_mood'] . '</p>';
	}
	return '<div onclick="dropUser(this,'.$list['user_id'].',\''.$list['user_name'].'\','.$list['user_rank'].','.$list['user_bot'].',\''.$list['country'].'\',\''.$list['user_cover'].'\',\''.$list['user_age'] .'\',\''.userGender($list['user_sex']).'\');" class="avtrig user_item ' . $offline . '">
				<div class="user_item_avatar"><img class="avav acav ' . avGender($list['user_sex']) . ' ' . ownAvatar($list['user_id']) . '" src="' . myAvatar($list['user_avatar']) . '"/> ' . $status . '</div>
				<div class="user_item_data"><p class="username ' . myColorFont($list) . '">' . $list["user_name"] . '</p>' . $mood . '</div>
				' . $muted . $icon . $flag . '
			</div>';
}
function defaultAvatar($a){
	if(stripos($a, 'default') !== false){
		return true;
	}
}
function profileAvatar($a){
	if(defaultAvatar($a)){
		$path =  '/default_images/avatar/';
	}
	return 'href="' . $a . '" src="'. $a . '"';
}
function userActive($user, $c){
	global $data, $wali;
	if(!isVisible($user) && !waliAllow($wali['can_inv_view'])){
		return '<img class="' . $c . '" src="default_images/icons/innactive.svg"/>';
	}
	else if($user['last_action'] >= getDelay() || isBot($user)){
		return '<img class="' . $c . '" src="default_images/icons/active.svg"/>';
	}
	else {
		return '<img class="' . $c . '" src="default_images/icons/innactive.svg"/>';
	}
}
function isMember($user){
	if(!isGuest($user) && !isBot($user)){
		return true;
	}
}
function postPrivate($from, $to, $content, $snum = ''){
	global $mysqli, $data;
	$mysqli->query("INSERT INTO `wali_private` (time, target, hunter, message) VALUES ('" . time() . "', '$to', '$from', '$content')");
	$last_id = $mysqli->insert_id;
	if($to != $from){
		$mysqli->query("UPDATE wali_users SET pcount = pcount + 1 WHERE user_id = '$to'");
	}
	if($snum != ''){
		$user_post = array(
			'id'=> $last_id,
			'time'=> time(),
			'message'=> $content,
			'hunter'=> $from,
		);
		$post = array_merge($data, $user_post);
		if(!empty($post)){
			return privateLog($post, $post['user_id']);
		}
	}
}
function roomRanking($rank = 0){
	global $lang;
	$room_menu = '<option value="0" ' . selCurrent($rank, 0) . '>' . roomAccessTitle(0) . '</option>';
	if(waliAllow(1)){
		$room_menu .= '<option value="1" ' . selCurrent($rank, 1) . '>' . roomAccessTitle(1) . '</option>';
	}
	if(waliAllow(2)){ 
		$room_menu .= '<option value="2" ' . selCurrent($rank, 2) . '>' . roomAccessTitle(2) . '</option>';
	}
	if(waliAllow(3)){ 
		$room_menu .= '<option value="3" ' . selCurrent($rank, 3) . '>' . roomAccessTitle(3) . '</option>';
	}
	if(waliAllow(4)){ 
		$room_menu .= '<option value="4" ' . selCurrent($rank, 4) . '>' . roomAccessTitle(4) . '</option>';
	}
	return $room_menu;
}

function canEditRoom(){
	if(waliRole(5)){
		return true;
	}
}
function waliActive($feature){
	if($feature <= 11){
		return true;
	}
}
function validGender($sex){
	$gender = array(1,2,3);
	if(in_array($sex, $gender)){
		return true;
	}
}
function canMood(){
	global $data;
	if(waliAllow($data['allow_mood'])){
		return true;
	}
}
function canAbout(){
	global $wali;
	if(waliAllow($wali['can_edit_about'])){
		return true;
	}
}
?>