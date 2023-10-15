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
function getIp()
{
	$client  = @$_SERVER['HTTP_CLIENT_IP'];
	$forward = @$_SERVER['HTTP_X_FORWARDED_FOR'];
	$cloud =   @$_SERVER["HTTP_CF_CONNECTING_IP"];
	$remote  = $_SERVER['REMOTE_ADDR'];
	if (filter_var($cloud, FILTER_VALIDATE_IP)) {
		$ip = $cloud;
	} else if (filter_var($client, FILTER_VALIDATE_IP)) {
		$ip = $client;
	} elseif (filter_var($forward, FILTER_VALIDATE_IP)) {
		$ip = $forward;
	} else {
		$ip = $remote;
	}
	return escape($ip);
}
function systemPostChat($room, $content, $custom = array())
{
	global $mysqli, $data;
	$def = array(
		'type' => 'system',
		'color' => 'chat_system',
		'rank' => 99,
	);
	$post = array_merge($def, $custom);
	$mysqli->query("INSERT INTO `wali_chat` (post_date, user_id, post_message, post_roomid, type, log_rank, tcolor) VALUES ('" . time() . "', '{$data['system_id']}', '$content', '$room', '{$post['type']}', '{$post['rank']}', '{$post['color']}')");
	chatAction($room);
	return true;
}
function systemNameFilter($user)
{
	return '<span onclick="getProfile(' . $user['user_id'] . ')"; class="sysname">' . $user['user_name'] . '</span>';
}
function allowLogs()
{
	global $data;
	if ($data['allow_logs'] == 1) {
		return true;
	}
}
function isVisible($user)
{
	if ($user['user_status'] != 6) {
		return true;
	}
}
function chatAction($room)
{
	global $mysqli, $data;
	$mysqli->query("UPDATE wali_rooms SET rcaction = rcaction + 1, room_action = '" . time() . "' WHERE room_id = '$room'");
}
function joinRoom()
{
	global $lang, $data, $wali;
	if (allowLogs() && isVisible($data) && $wali['join_room'] == 1) {
		$content = str_replace('%user%', systemNameFilter($data), $lang['system_join_room']);
		systemPostChat($data['user_roomid'], $content, array('type' => 'system__join'));
	}
}
function leaveRoom()
{
	global $data, $lang, $wali;
	if (allowLogs() && $wali['leave_room'] == 1) {
		if (isVisible($data) && $data['user_roomid'] != 0 && $data['last_action'] > time() - 30) {
			$content = str_replace('%user%', systemNameFilter($data), $lang['quit_room']);
			systemPostChat($data['user_roomid'], $content, array('type' => 'system__leave'));
		}
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
function rankIcon($rank)
{
	switch ($rank) {
		case 0:
			return 'user.svg';
		case 1:
			return 'vip.svg';
		case 2:
			return 'elite_vip.svg';
		case 3:
			return 'mod.svg';
		case 4:
			return 'admin.svg';
		case 5:
			return 'super.svg';
		case 6:
			return 'owner.svg';
		case 7:
			return 'bot.svg';
		default:
			return 'user.svg';
	}
}
function rankText($rank)
{
	switch ($rank) {
		case 0:
			return 'User';
		case 1:
			return 'VIP';
		case 2:
			return 'Eite VIP';
		case 3:
			return 'Moderator';
		case 4:
			return 'Admin';
		case 5:
			return 'Super Admin';
		case 6:
			return 'Owner';
		case 7: 
			return 'Bot';
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
		case 7:
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
function createNewsLog($data, $news)
{
	return  '<div id="wali_news' . $news['id'] . '" data="' . $news['id'] . '" class="news_box post_element">
				<div class="post_title">
					<div class="post_avatar get_info" data='.$news['user_id']. '>
						<img src="' . myAvatar($news['user_avatar']) . '"/>
					</div>
					<div class="bcell_mid hpad5 maxflow post_info">
						<p class="username text_small ' . myColorFont($news) . '">'. $news['user_name'].'</p>
						<p class="text_xsmall date">'. displayDate($news['news_date']) . '</p>
					</div>
				</div>
				<div class="post_content">
				 '. waliPostIt($news, $news['news_message']). '
				 ' . waliPostNewsFile($news['news_file']).'
				</div>
			</div>';
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
// function waliHistory($type, $custom = array()){
// 	global $mysqli, $data;
// 	$def = array(
// 		'hunter'=> $data['user_id'],
// 		'target'=> 0,
// 		'rank'=> 0,
// 		'delay'=> 0,
// 		'reason'=> '',
// 		'content'=> '',
// 	);
// 	$c = array_merge($def, $custom);
// 	if($c['target'] == 0){
// 		return false;
// 	}
// 	$mysqli->query("INSERT INTO wali_history (hunter, target, htype, delay, reason, history_date) VALUES ('{$c['hunter']}', '{$c['target']}', '$type',  '{$c['delay']}', '{$c['reason']}', '" . time() . "')");
// }
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
		case 9:
			return $lang['r_owner'];
		case 8:
			return $lang['r_admin'];
		case 7:
			return $lang['r_mod'];
		default:
			return $lang['user'];
	}
}
function roomRankIcon($rank){
	switch($rank){
		case 9:
			return 'room_owner.svg';
		case 8:
			return 'room_admin.svg';
		case 7:
			return 'room_mod.svg';
		default:
			return 'user.svg';
	}
}
function roomRank($rank, $type){
	switch($rank){
		case 7:
		case 8:
		case 9:
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
	if(waliRole(7) || waliAllow(4)){
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
function userCountry($country){
	global $data;
	if($country != 'ZZ' && $country != ''){
		return true;
	}
}
function verified($user){
	if($user['verified'] > 0){
		return true;
	}
}
function waliSex($s){
	if($s != 0){
		return true;
	}
}
function waliAge($a){
	if($a != 0){
		return true;
	}
}
function waliFormat($txt){
	$count = substr_count($txt, "\n" );
	if($count > 20){
		return $txt;
	}
	else {
		return nl2br($txt);
	}
}
function soundStatus($val)
{
	global $data;
	if (preg_match('@[' . $val . ']@i', $data['user_sound'])) {
		return 1;
	} else {
		return 0;
	}
}
function statusElement($val, $txt, $icon)
{
	return '<div class="status_option sub_item" onclick="updateStatus(' . $val . ');" data="' . $val . '">
				<div class="zone_status"><img class="icon_status" src="default_images/status/' . $icon . '"/></div>
				<div class="icon_text">' . $txt . '</div>
			</div>';
}
function canInvisible()
{
	global $data, $wali;
	if (waliAllow($wali['can_invisible'])) {
		return true;
	}
}
function listAllStatus()
{
	$list = '';
	$list .= statusElement(1, statusTitle(1), statusIcon(1));
	$list .= statusElement(2, statusTitle(2), statusIcon(2));
	$list .= statusElement(3, statusTitle(3), statusIcon(3));
	if (canInvisible()) {
		$list .= statusElement(4, statusTitle(4), statusIcon(4));
	}
	return $list;
}
function myFriendList()
{
	global $mysqli, $lang, $data;
	$friend_list = '';
	$find_friend = $mysqli->query("SELECT wali_users.user_name, wali_users.user_id, wali_users.user_avatar, wali_users.user_color, wali_users.last_action, wali_users.user_rank, wali_friends.* FROM wali_users, wali_friends 
	WHERE hunter = '{$data['user_id']}' AND fstatus > 1 AND target = wali_users.user_id ORDER BY fstatus DESC, user_name ASC");
	if ($find_friend->num_rows > 0) {
		while ($find = $find_friend->fetch_assoc()) {
			$friend_list .= waliTemplate('element/friend_element', $find);
		}
	} else {
		$friend_list .= emptyZone($lang['no_friend']);
	}
	return $friend_list;
}
function validTextWeight($f)
{
	$val = array('', 'ital', 'bold', 'boldital', 'heavybold', 'heavyital');
	if (in_array($f, $val)) {
		return true;
	}
}
function validTextColor($color)
{
	global $data;
	if ($color == '') {
		return true;
	}
	if (canColor() && preg_match('/^bcolor[0-9]{1,2}$/', $color)) {
		return true;
	}
	if (canGrad() && preg_match('/^bgrad[0-9]{1,2}$/', $color)) {
		return true;
	}
	if (canNeon() && preg_match('/^bneon[0-9]{1,2}$/', $color)) {
		return true;
	}
}
function validTextFont($font)
{
	global $data;
	if ($font == '') {
		return true;
	}
	if (canFont() && preg_match('/^bfont[0-9]{1,2}$/', $font)) {
		return true;
	}
}
function userCanDirect($user)
{
	global $data;
	if (userWaliAllow($user, $data['allow_direct'])) {
		return true;
	}
}
function userWaliAllow($user, $val)
{
	if ($user['user_rank'] >= $val) {
		return true;
	}
}
function getLikes($post, $liked, $type)
{
	global $mysqli, $data, $wali, $lang;
	$result = array(
		'like_post' => $post,
		'like_count' => 0,
		'dislike_count' => 0,
		'love_count' => 0,
		'fun_count' => 0,
		'liked' => '',
		'disliked' => '',
		'loved' => '',
		'funned' => '',
	);
	if ($type == 'wall') {
		$get_like = $mysqli->query("SELECT like_type FROM wali_post_like WHERE like_post = '$post'");
	} else if ($type == 'news') {
		$get_like = $mysqli->query("SELECT like_type FROM wali_news_like WHERE like_post = '$post'");
	} else {
		return '';
	}
	switch ($liked) {
		case 1:
			$result['liked'] = ' liked';
			break;
		case 2:
			$result['disliked'] = ' liked';
			break;
		case 3:
			$result['loved'] = ' liked';
			break;
		case 4:
			$result['funned'] = ' liked';
			break;
		default:
			break;
	}
	if ($get_like->num_rows > 0) {
		while ($like = $get_like->fetch_assoc()) {
			if ($like['like_type'] == 1) {
				$result['like_count']++;
			} else if ($like['like_type'] == 2) {
				$result['dislike_count']++;
			} else if ($like['like_type'] == 3) {
				$result['love_count']++;
			} else if ($like['like_type'] == 4) {
				$result['fun_count']++;
			}
		}
	}
	if ($type == 'wall') {
		return 	waliTemplate('element/likes', $result);
	} else if ($type == 'news') {
		return waliTemplate('element/likes_news', $result);
	} else {
		return '';
	}
}
function likeType($type, $c)
{
	switch ($type) {
		case 1:
			return '<img class="' . $c . '" src="default_images/reaction/like.svg">';
		case 2:
			return '<img class="' . $c . '" src="default_images/reaction/dislike.svg">';
		case 3:
			return '<img class="' . $c . '" src="default_images/reaction/love.svg">';
		case 4:
			return '<img class="' . $c . '" src="default_images/reaction/funny.svg">';
		default:
			return 'liked';
	}
}
function canDeleteNews($news)
{
	global $data, $wali;
	if (mySelf($news['news_poster'])) {
		return true;
	}
	if (waliAllow($wali['can_delete_news']) && isGreater($news['user_rank'])) {
		return true;
	}
}
function canPostNews()
{
	global $data, $wali;
	if (waliAllow($wali['can_post_news'])) {
		return true;
	}
}
function canDeleteWallReply($wall)
{
	global $data, $wali;
	if (mySelf($wall['reply_user'])) {
		return true;
	}
	if (mySelf($wall['reply_uid'])) {
		return true;
	}
	if (waliAllow($wali['can_delete_wall']) && isGreater($wall['user_rank'])) {
		return true;
	}
}
function muteAccount($id, $delay, $reason = ''){
	global $mysqli, $data, $wali;
	$user = userDetails($id);
	if(empty($user)){
		return 3;
	}
	if(!canMuteUser($user)){
		return 0;
	}
	if(isMuted($user)){
		return 2;
	}
	systemMute($user, $delay, $reason);
	waliNotify('mute', array('target' => $user['user_id'], 'source' => 'mute', 'reason'=> $reason, 'delay'=> $delay));
	waliConsole('mute', array('target'=> $user['user_id'], 'reason'=>$reason, 'delay'=> $delay));
	return 1;
}
function unmuteAccount($id)
{
	global $mysqli, $data, $cody;
	$user = userDetails($id);
	if (empty($user)) {
		return 3;
	}
	if (!canMuteUser($user)) {
		return 0;
	}
	if (!isMuted($user) && !isRegmute($user)) {
		return 2;
	}
	systemUnmute($user);
	waliConsole('unmute', array('target' => $user['user_id']));
	return 1;
}
function kickAccount($id, $delay, $reason = ''){
	global $mysqli, $data, $cody;
	$user = userDetails($id);
	if(empty($user)){
		return 3;
	}
	if(!canKickUser($user)){
		return 0;
	}
	if(isKicked($user)){
		return 2;
	}
	if(!validKick($delay)){
		return 0;
	}
	systemKick($user, $delay, $reason);
	waliConsole('kick', array('target'=> $user['user_id'], 'reason'=>$reason, 'delay'=> $delay));
	// waliHistory('kick', array('target'=> $user['user_id'], 'delay'=> $delay, 'reason'=> $reason));
	return 1;
}
function unkickAccount($id){
	global $mysqli, $data, $cody;
	$user = userDetails($id);
	if(empty($user)){
		return 3;
	}
	if(!canKickUser($user)){
		return 0;
	}
	if(!isKicked($user)){
		return 2;
	}
	systemUnkick($user);
	waliConsole('unkick', array('target'=> $user['user_id']));
	return 1;
}
function waliDuplicateIp($val)
{
	global $mysqli, $data, $cody;
	$dupli = $mysqli->query("SELECT * FROM `wali_banned` WHERE `ip` = '$val'");
	if ($dupli->num_rows > 0) {
		return true;
	}
}
function banAccount($id, $reason = ''){
	global $mysqli, $data, $cody;
	$user = userDetails($id);
	if(!canBanUser($user)){
		return 0;
	}
	if(isBanned($user)){
		return 2;
	}
	systemBan($user, $reason);
	waliConsole('ban', array('target'=> $user['user_id'], 'custom'=>$user['user_ip'], 'reason'=> $reason));
	// waliHistory('ban', array('target'=> $user['user_id'], 'reason'=> $reason));
	return 1;
}
function unbanAccount($id)
{
	$user = userDetails($id);
	systemUnban($user);
	waliConsole('unban', array('target' => $user['user_id'], 'custom' => $user['user_ip']));
	return 1;
}
function validKick($val)
{
	$valid = array(2, 5, 10, 15, 30, 60, 1440, 2880, 4320, 5760, 7200, 8640, 10080, 20160, 43200);
	if (in_array($val, $valid)) {
		return true;
	}
}
function validMute($val)
{
	$valid = array(2, 5, 10, 15, 30, 60, 1440, 2880, 4320, 5760, 7200, 8640, 10080, 20160, 43200);
	if (in_array($val, $valid)) {
		return true;
	}
}
function systemUnban($user)
{
	global $mysqli;
	$mysqli->query("UPDATE wali_users SET user_banned = 0, ban_msg = '', user_action = user_action + 1 WHERE user_id = '{$user['user_id']}'");
	$mysqli->query("DELETE FROM wali_banned WHERE ip = '{$user['user_ip']}' OR ban_user = '{$user['user_id']}'");
	// return 1;
}
function blockRoom($id)
{
	global $mysqli, $data;
	$user = userRoomDetails($id);
	if (empty($user)) {
		return 3;
	}
	if (mainRoom()) {
		return 0;
	}
	if (!canRoomAction($user, 5, 2)) {
		return 0;
	} else {
		$mysqli->query("UPDATE wali_users SET user_action = user_action + 1, user_roomid = '0' WHERE user_id = '$id' AND user_roomid = '{$data['user_roomid']}'");
		$checkroom = $mysqli->query("SELECT * FROM wali_room_action WHERE action_room = '{$data['user_roomid']}' AND action_user = '$id'");
		if ($checkroom->num_rows > 0) {
			$mysqli->query("UPDATE wali_room_action SET action_blocked = '1' WHERE action_user = '$id' AND action_room = '{$data['user_roomid']}'");
		} else {
			$mysqli->query("INSERT INTO wali_room_action ( action_room , action_user, action_blocked ) VALUES ('{$data['user_roomid']}', '$id', '1')");
		}
		waliConsole('room_block', array('target' => $user['user_id']));
		return 1;
	}
}
function unblockRoom($id)
{
	global $mysqli, $data;
	$user = userRoomDetails($id);
	if (empty($user)) {
		return 3;
	}
	if (!canRoomAction($user, 5, 2)) {
		return 0;
	} else {
		$mysqli->query("DELETE FROM wali_room_action WHERE action_room = '{$data['user_roomid']}' AND action_user = '$id' AND action_blocked = '1' AND action_muted = '0'");
		$mysqli->query("UPDATE wali_room_action SET action_blocked = '0' WHERE action_room = '{$data['user_roomid']}' AND action_user = '$id' AND action_blocked = '1'");
		waliConsole('room_unblock', array('target' => $user['user_id']));
		return 1;
	}
}
function muteRoom($id)
{
	global $mysqli, $data;
	$user = userRoomDetails($id);
	if (empty($user)) {
		return 3;
	}
	if (!canRoomAction($user, 4, 2)) {
		return 0;
	} else {
		$mysqli->query("UPDATE wali_users SET room_mute = 1 WHERE user_id = '$id' AND user_roomid = '{$data['user_roomid']}'");
		$checkroom = $mysqli->query("SELECT * FROM wali_room_action WHERE action_room = '{$data['user_roomid']}' AND action_user = '$id'");
		if ($checkroom->num_rows > 0) {
			$mysqli->query("UPDATE wali_room_action SET action_muted = '1' WHERE action_user = '$id' AND action_room = '{$data['user_roomid']}'");
		} else {
			$mysqli->query("INSERT INTO wali_room_action ( action_room , action_user, action_muted ) VALUES ('{$data['user_roomid']}', '$id', '1')");
		}
		waliConsole('room_mute', array('target' => $user['user_id']));
		return 1;
	}
}
function unmuteRoom($id)
{
	global $mysqli, $data;
	$user = userRoomDetails($id);
	if (empty($user)) {
		return 3;
	}
	if (!canRoomAction($user, 4, 2)) {
		return 0;
	} else {
		$mysqli->query("UPDATE wali_users SET room_mute = 0 WHERE user_id = '$id' AND user_roomid = '{$data['user_roomid']}'");
		$mysqli->query("DELETE FROM wali_room_action WHERE action_room = '{$data['user_roomid']}' AND action_user = '$id' AND action_muted = '1' AND action_blocked = '0'");
		$mysqli->query("UPDATE wali_room_action SET action_muted = '0' WHERE action_room = '{$data['user_roomid']}' AND action_user = '$id' AND action_muted = '1'");
		waliConsole('room_unmute', array('target' => $user['user_id']));
		return 1;
	}
}
function removeRoomStaff($target)
{
	global $mysqli, $data, $lang;
	$user = userRoomDetails($target);
	if (!canEditRoom()) {
		return 0;
	}
	if (!betterRole($user['room_ranking']) && !waliAllow(9)) {
		return 0;
	}
	$mysqli->query("DELETE FROM wali_room_staff WHERE room_staff = '{$user['user_id']}' AND room_id = '{$data['user_roomid']}'");
	$mysqli->query("UPDATE wali_users SET user_role = 0 WHERE user_id = '{$user['user_id']}' AND user_roomid = '{$data['user_roomid']}'");
	waliConsole('change_room_rank', array('target' => $user['user_id'], 'rank' => 0));
	return 1;
}
function renderReason($t)
{
	global $lang;
	switch ($t) {
		case '':
			return $lang['no_reason'];
		case 'badword':
			return $lang['badword'];
		case 'spam':
			return $lang['spam'];
		case 'flood':
			return $lang['flood'];
		default:
			return systemReplace($t);
	}
}
?>