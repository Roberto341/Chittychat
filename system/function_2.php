<?php
function validStatus($val){ 
	$valid = array(1,2,3,4);
	if(in_array($val, $valid)){
		return true;
	}
}
function validate($data){
	$data = trim($data);
	$data = stripslashes($data);
	$data = htmlspecialchars($data);
	return $data;
 }
function statusTitle($status){
	global $lang;
	switch($status){
		case 1:  
			return $lang['online'];
		case 2:  
			return $lang['away'];
		case 3:  
			return $lang['busy'];
		case 4:  
			return $lang['invisible'];
		default: 
			return $lang['online'];
	}
}
function showNews($id)
{
	global $mysqli, $lang, $data;
	$news_content = '';
	$get_news = $mysqli->query("SELECT wali_news.*, wali_users.*,
	(SELECT count( parent_id ) FROM wali_news_reply WHERE parent_id = wali_news.id ) as reply_count,
	(SELECT like_type FROM wali_news_like WHERE uid = '{$data['user_id']}' AND like_post = wali_news.id) as liked
	FROM wali_news, wali_users
	WHERE wali_news.news_poster = wali_users.user_id AND wali_news.id = '$id'
	ORDER BY news_date DESC LIMIT 1");
	while ($news = $get_news->fetch_assoc()) {
		$news_content .= waliTemplate('element/news', $news);
	}
	return $news_content;
}
function newStatusIcon($status){
	return 'default_images/status/' . statusIcon($status);
}
function muted(){
	global $data;
	if(isMuted($data) || isBanned($data) || isKicked($data) || isRegmute($data)){
		return true;
	}
}
function roomMuted(){
	global $data;
	if($data['room_mute'] > 0){
		return true;
	}
}
function roomAccessTitle($room){
	switch($room){
		case 0:
			return 'Public';
		case 1:
			return 'Members';
		case 2:
			return 'Vip';
		case 3:
			return 'Staff';
		case 4:
			return 'Admin';
		default:
			return 'Public';
	}
}
function roomAccessIcon($room){
	switch($room){
		case 0:
			return 'public_room.svg';
		case 1:
			return 'member_room.svg';
		case 2:
			return 'vip_room.svg';
		case 3:
			return 'staff_room.svg';
		case 4:
			return 'admin_room.svg';
		default:
			return 'public_room.svg';
	}
}
function roomIcon($room, $type){
	global $lang;
	switch($room['access']){
		case 0:
		case 1:
		case 2:
		case 3:
		case 4:
			return roomIconTemplate($type, roomAccessTitle($room['access']), roomAccessIcon($room['access']));
		default:
			return roomIconTemplate($type, roomAccessTitle(0), roomAccessIcon(0));
	}
}

function roomIconTemplate($type, $txt, $icon){
	return '<img title="' . $txt . '" class="' . $type .  '" src="default_images/rooms/' . $icon . '">';	
}
function userAccessTitle($rank){
	switch($rank){
		case 0:
			return 'user';
		case 1:
			return 'vip';
		case 2:
			return 'elite vip';
		case 3:
			return 'moderator';
		case 4:
			return 'admin';
		case 5:
			return 'super admin';
		case 6:
			return 'owner';
		case 7: 
			return 'bot';
		default:
			return 'user';
	}
}
function userAccessIcon($rank){
	switch($rank){
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

function userIcon($rank, $type){
	switch($rank['user_rank']){
		case 0:
		case 1: 
		case 2:
		case 3:
		case 4:
		case 5:
		case 6:
		case 7:
			//return roomIconTemplate($type, roomAccessTitle($room['rank']), roomAccessIcon($room['rank']));
			return userIconTemplate($type, userAccessTitle($rank['user_rank']), userAccessIcon($rank['user_rank']));
		default:
			return userIconTemplate($type, userAccessTitle(1), userAccessIcon(1));
	}
}

function userIconTemplate($type, $txt, $icon){
	return '<img title="' .$txt .'" class="'. $type .'" src="default_images/rank/' . $icon .'">';
}
function selCurrent($cur, $val){
	if($cur == $val){
		return 'selected';
	}
}

function getDefTheme(){
	global $data;
		return $data['default_theme'];
}

function waliTemplate($getpage, $walie = '') {
	global $data, $lang, $mysqli, $wali;
    $page = WALI_PATH . '/system/' . $getpage . '.php';
    $structure = '';
    ob_start();
    require($page);
    $structure = ob_get_contents();
    ob_end_clean();
    return $structure;
}
function getRoomList($type){
	global $mysqli, $data, $lang;
	$check_action = getDelay();
	$rooms = $mysqli->query(" SELECT *, 
	( SELECT Count(wali_users.user_id) FROM wali_users  Where wali_users.user_roomid = room_id AND last_action > '$check_action' AND user_status != 4 ) as room_count
	FROM wali_rooms 
	ORDER BY room_count DESC, room_action DESC");
	$sroom = 0;
	$room_list = '';
	while ($room = $rooms->fetch_assoc()){
		switch($type){
			case 'list':
				$room_list .= waliTemplate('element/room_item', $room);
				break;
			case 'box':
				$room_list .= waliTemplate('element/room_box', $room);
				break;
		}
	}
	return $room_list;
}

function canAddroom(){
	global $data;
	if($data['user_rank'] >= $data['allow_room']){
		return true;	
	}
}
function useFont(){
	global $data;
	if(waliActive($data['allow_font']) || waliActive($data['allow_name_font'])){
		return true;
	}
}
function canFont(){
	global $data;
	if(useFont() && waliAllow($data['allow_font'])){
		return true;
	}
}
function canColor(){
	global $data;
		if(waliAllow($data['allow_colors'])){
			return true;
		}
}
function canGrad(){
	global $data;
	if($data['user_rank'] >= $data['allow_grad']){
		return true;
	}
}

function canNeon(){
	global $data;
	if($data['user_rank'] >= $data['allow_neon']){
		return true;
	}
}

function canNameFont(){
	global $data;
		if(useFont() && waliAllow($data['allow_name_font'])){
			return true;
		}
}
function canNameColor(){
	global $data;
	if(waliAllow($data['allow_name_color'])){
		return true;
	}
}
function canNameGrad(){
	global $data;
	if(waliAllow($data['allow_name_grad'])){
		return true;
	}
}
	
function canNameNeon(){
	global $data;
	if(waliAllow($data['allow_name_neon'])){
		return true;
	}
}
function validNameColor($color){
	global $data;
	if(canNameColor() && $color == 'user'){
		return true;
	}
	if(canNameGrad() && preg_match('/^bcolor[0-9]{1,2}$/', $color)){
		return true;
	}
	if(canNameGrad() && preg_match('/^bgrad[0-9]{1,2}$/', $color)){
		return true;
	}
}
function validNameFont($font){
	global $data;
	if($font == ''){
		return true;
	}
	if(canNameFont() && preg_match('/^bnfont[0-9]{1,2}$/', $font)){
		return true;
	}
}
function colorChoice($sel, $type, $min = 1){
	global $wali;
	$show_c = '';
	switch($type){
		case 1:
			$c = 'choice';
			break;
		case 2:
			$c = 'user_choice';
			break;
		case 3:
			$c = 'name_choice';
			break;
		default:
			return false;
	}
	for ($n = $min; $n <= $wali['color_count']; $n++) {
		$val = 'wcolor' . $n;
		$back = 'wcback' . $n;
		$add_sel = '';
		if($val == $sel){
			$add_sel = '<i class="fa fa-check wccheck"></i>';
		}
		$show_c .= '<div data="' . $val . '" class="color_switch ' . $c . ' ' . $back . '">' . $add_sel . '</div>';
	}
	return $show_c;
}
function gradChoice($sel, $type, $min = 1){
	global $wali;
	$show_c = '';
	switch($type){
		case 1:
			$c = 'choice';
			break;
		case 2:
			$c = 'user_choice';
			break;
		case 3:
			$c = 'name_choice';
			break;
		default:
			return false;
	}
	for ($n = $min; $n <= $wali['gradient_count']; $n++) {
		$val = 'bgrad' . $n;
		$back = 'backgrad' . $n;
		$add_sel = '';
		if($val == $sel){
			$add_sel = '<i class="fa fa-check wccheck"></i>';
		}
		$show_c .= '<div data="' . $val . '" class="color_switch ' . $c . ' ' . $back . '">' . $add_sel . '</div>';
	}
	return $show_c;
}
function neonChoice($sel, $type, $min = 1){
	global $wali;
	$show_c = '';
	switch($type){
		case 1:
			$c = 'choice';
			break;
		case 2:
			$c = 'user_choice';
			break;
		case 3:
			$c = 'name_choice';
			break;
		default:
			return false;
	}
	for ($n = $min; $n <= $wali['neon_count']; $n++) {
		$val = 'bneon' . $n;
		$back = 'bnback' . $n;
		$add_sel = '';
		if($val == $sel){
			$add_sel = '<i class="fa fa-check wccheck"></i>';
		}
		$show_c .= '<div data="' . $val . '" class="color_switch ' . $c . ' ' . $back . '">' . $add_sel . '</div>';
	}
	return $show_c;
}
function listFontStyle($v){
	$list = '';
	$list .= '<option ' . selCurrent($v, '') . ' value="">Normal</option>';
	$list .= '<option ' . selCurrent($v, 'bold') . ' value="bold">Bold</option>';
	$list .= '<option ' . selCurrent($v, 'heavybold') . ' value="heavybold">Heavy</option>';
	$list .= '<option ' . selCurrent($v, 'ital') . ' value="ital">Italic</option>';
	$list .= '<option ' . selCurrent($v, 'boldital') . ' value="boldital">Bold italic</option>';
	$list .= '<option ' . selCurrent($v, 'heavyital') . ' value="heavyital">Heavy italic</option>';
	return $list;
}
function listFont($v){
	$list = '';
	$list .= '<option ' . selCurrent($v, '') . ' value="">Normal</option>';
	$list .= '<option ' . selCurrent($v, 'bfont1') . ' value="bfont1">Kalam</option>';
	$list .= '<option ' . selCurrent($v, 'bfont2') . ' value="bfont2">Signika</option>';
	$list .= '<option ' . selCurrent($v, 'bfont3') . ' value="bfont3">Grandmaster</option>';
	$list .= '<option ' . selCurrent($v, 'bfont4') . ' value="bfont4">Comic neue</option>';
	$list .= '<option ' . selCurrent($v, 'bfont5') . ' value="bfont5">Quicksand</option>';
	$list .= '<option ' . selCurrent($v, 'bfont6') . ' value="bfont6">Orbitron</option>';
	$list .= '<option ' . selCurrent($v, 'bfont7') . ' value="bfont7">Lemonada</option>';
	$list .= '<option ' . selCurrent($v, 'bfont8') . ' value="bfont8">Grenze Gotisch</option>';
	$list .= '<option ' . selCurrent($v, 'bfont9') . ' value="bfont9">Merienda</option>';
	$list .= '<option ' . selCurrent($v, 'bfont10') . ' value="bfont10">Amita</option>';
	$list .= '<option ' . selCurrent($v, 'bfont11') . ' value="bfont11">Averia Libre</option>';
	$list .= '<option ' . selCurrent($v, 'bfont12') . ' value="bfont12">Turret Road</option>';
	$list .= '<option ' . selCurrent($v, 'bfont13') . ' value="bfont13">Sansita</option>';
	$list .= '<option ' . selCurrent($v, 'bfont14') . ' value="bfont14">Comfortaa</option>';

	return $list;
}
function listNameFont($v){
	$list = '';
	$list .= '<option ' . selCurrent($v, '') . ' value="">Normal</option>';
	$list .= '<option ' . selCurrent($v, 'bnfont1') . ' value="bnfont1">Kalam</option>';
	$list .= '<option ' . selCurrent($v, 'bnfont2') . ' value="bnfont2">Signika</option>';
	$list .= '<option ' . selCurrent($v, 'bnfont3') . ' value="bnfont3">Grandmaster</option>';
	$list .= '<option ' . selCurrent($v, 'bnfont4') . ' value="bnfont4">Comic neue</option>';
	$list .= '<option ' . selCurrent($v, 'bnfont5') . ' value="bnfont5">Quicksand</option>';
	$list .= '<option ' . selCurrent($v, 'bnfont6') . ' value="bnfont6">Orbitron</option>';
	$list .= '<option ' . selCurrent($v, 'bnfont7') . ' value="bnfont7">Lemonada</option>';
	$list .= '<option ' . selCurrent($v, 'bnfont8') . ' value="bnfont8">Grenze Gotisch</option>';
	$list .= '<option ' . selCurrent($v, 'bnfont9') . ' value="bnfont9">Merienda</option>';
	$list .= '<option ' . selCurrent($v, 'bnfont10') . ' value="bnfont10">Amita</option>';
	$list .= '<option ' . selCurrent($v, 'bnfont11') . ' value="bnfont11">Averia Libre</option>';
	$list .= '<option ' . selCurrent($v, 'bnfont12') . ' value="bnfont12">Turret Road</option>';
	$list .= '<option ' . selCurrent($v, 'bnfont13') . ' value="bnfont13">Sansita</option>';
	$list .= '<option ' . selCurrent($v, 'bnfont14') . ' value="bnfont14">Comfortaa</option>';
	$list .= '<option ' . selCurrent($v, 'bnfont15') . ' value="bnfont15">Charm</option>';
	$list .= '<option ' . selCurrent($v, 'bnfont16') . ' value="bnfont16">Lobster Two</option>';

	return $list;
}
function listAge($current, $type){
	global $data;
	$age = '';
	if($type == 1){
		$age .= '<option value="0" class="placeholder" selected disabled>' . 'Age' . '</option>';
	}
	for($value = $data['min_age']; $value <= 99; $value++){
		$age .=  '<option name="age" value="' . $value . '" ' . selCurrent($current, $value) . '>' . $value . '</option>';
	}
	if($type == 2){
		$age .=  '<option value="0" name="age"' . selCurrent($current, 0) . '>' . 'Do not display' . '</option>';
	}
	return $age;
}
function listGender($sex){
	$list = '';
	$list .= '<option name="gender"' . selCurrent($sex, 1) . ' value="1">' . 'Male' . '</option>';
	$list .= '<option name="gender"' . selCurrent($sex, 2) . ' value="2">' . 'Female' . '</option>';
	$list .= '<option name="gender"' . selCurrent($sex, 3) . ' value="3">' . 'Other' . '</option>';
	return $list;
}

function getAvatarStr($user){
	return $user['avatar'];
}

function getPrivAv($user){
	return "<img class='private_av' src='".$user['user_avatar']." onclick=getProfile('".$user['user_id']."');'>";
}
function getAvatar2($user){
return "<img class='fancybox avatar_profile' src='".$user['user_avatar']."'>";
}
function getCover($user){
		return "style='background-image: url(".$user['user_cover'].");'";
}
function getUserAge($age){
	return $age . ' ' . 'years old';
}
function getGender($s){
	global $lang;
	switch($s){
		case 1:
			return $lang['male'];
		case 2:
			return $lang['female'];
		case 3:
			return $lang['other'];
		default:
			return $lang['other'];
	}
}
function roomInfo($id){
	global $data, $mysqli;
	$room = array();
	$get_room = $mysqli->query("SELECT * FROM wali_rooms WHERE room_id = '$id'");
	if($get_room->num_rows > 0){
		$room = $get_room->fetch_assoc();
	}
	return $room;
}
function getVerStatus($stat){
	return $stat;
}
function curStatus($txt, $icon, $c){
	return '<img title="' . $txt . '" class="' . $c . '" src="default_images/status/' . $icon . '"/>';	
}
function getStatus($status, $c){
	switch($status){
		case 2:
			return curStatus(statusTitle(2), statusIcon(2), $c);
		case 3:
			return curStatus(statusTitle(3), statusIcon(3), $c);
		default:
			return '';
	}
}
function userPostChat($content, $custom = array()){
	global $mysqli, $data;
	$def = array(
		'hunter'=> $data['user_id'],
		'room'=> $data['user_roomid'],
		'color'=> escape(myTextColor($data)),
		'type'=> 'public__message',
		'rank'=> 99,
		'snum'=> '',
	);
	$c = array_merge($def, $data, $custom);
	$mysqli->query("INSERT INTO `wali_chat` (post_date, user_id, post_message, post_roomid, type, log_rank, snum, tcolor) VALUES ('" . time() . "', '{$c['hunter']}', '$content', '{$c['room']}', '{$c['type']}', '{$c['rank']}', '{$c['snum']}', '{$c['color']}')");
	$last_id = $mysqli->insert_id;
	chatAction($data['user_roomid']);
	if(!empty($c['snum'])){
		$user_post = array(
			'post_id'=> $last_id,
			'type'=> $c['type'],
			'post_date'=> time(),
			'tcolor'=> $c['color'],
			'log_rank'=> 99,
			'post_message'=> $content,
		);
		$post = array_merge($c, $user_post);
		if(!empty($post)){
			return createLog($data, $post);
		}
	}
}
function adminPostNews($content, $file = " "){
	global $mysqli, $data;
	$def = array(
		'news_poster' => $data['user_id'],
		'news_message' => $content,
		'news_file' => $file,
	);
	$nw = array_merge($def, $data);
    $mysqli->query("INSERT INTO `wali_news` (news_poster, news_message, news_file, news_date) VALUES ('{$nw['news_poster']}', '{$nw['news_message']}', '{$nw['news_file']}', '" . time() . "')");
	$last_id = $mysqli->insert_id;
	if(!empty($nw['news_poster'] || $nw['news_message'])){
		$news_post = array(
			'news_id' => $last_id,
			'news_date' => time(),
			'news_message' => $content,
		);
		$news = array_merge($nw, $news_post);
		if(!empty($news)){
			return createNewsLog($data, $news);
		}
	}
}
function userPostChatFile($content, $file_name, $type, $custom = array())
{
	global $mysqli, $data;
	$def = array(
		'type' => 'public__message',
		'file2' => '',
	);
	$c = array_merge($def, $custom);
	$mysqli->query("INSERT INTO `wali_chat` (post_date, user_id, post_message, post_roomid, type, file) VALUES ('" . time() . "', '{$data['user_id']}', '$content', '{$data['user_roomid']}', '{$c['type']}', '1')");
	$rel = $mysqli->insert_id;
	chatAction($data['user_roomid']);
	if ($c['file2'] != '') {
		$mysqli->query("INSERT INTO `wali_upload` (file_name, date_sent, file_user, file_zone, file_type, relative_post) VALUES
		('$file_name', '" . time() . "', '{$data['user_id']}', 'chat', '$type', '$rel'),
		('{$c['file2']}', '" . time() . "', '{$data['user_id']}', 'chat', '$type', '$rel')
		");
	} else {
		$mysqli->query("INSERT INTO `wali_upload` (file_name, date_sent, file_user, file_zone, file_type, relative_post) VALUES ('$file_name', '" . time() . "', '{$data['user_id']}', 'chat', '$type', '$rel')");
	}
	return true;
}
function badWord($content)
{
	$regex = '\w/_\.\%\+#\-\?:\=\&\;\(\)';
	if (preg_match('@https?:\/\/(www\.)?([' . $regex . ']*)?([\*]{4}){1,}([' . $regex . ']*)?@ui', $content)) {
		return true;
	}
}
function normalise($text, $a)
{
	$count = substr_count($text, "http");
	if ($count > $a) {
		return false;
	}
	return true;
}
function linking($content)
{
	if (!badWord($content)) {
		$source = $content;
		$regex = '\w/_\.\%\+#\-\?:\=\&\;\(\)';
		if (normalise($content, 1)) {
			$content = str_replace('youtu.be/', 'youtube.com/watch?v=', $content);
			$content = preg_replace('@https?:\/\/(www\.)?youtube.com/watch\?v=([\w_-]*)([' . $regex . ']*)?@ui', '<div class="chat_video_container"><div class="chat_video"><iframe src="https://www.youtube.com/embed/$2" frameborder="0" allowfullscreen></iframe></div><div data="https://www.youtube.com/embed/$2" value="youtube" class="boom_youtube open_player hide_mobile"><i class="fa fa-external-link-square"></i></div></div>', $content);
			$content = preg_replace('@https?:\/\/([-\w\.]+[-\w])+(:\d+)?\/[' . $regex . ']+\.(png|gif|jpg|jpeg|webp)((\?\S+)?[^\.\s])?@ui', ' <a href="$0" class="fancybox"><img class="chat_image"src="$0"/></a> ', $content);
			if (preg_last_error()) {
				$content = $source;
			}
			$content = preg_replace('@([^=][^"])(https?://([-\w\.]+[-\w])+(:\d+)?(/([' . $regex . ']*(\?\S+)?[^\.\s])?)?)@ui', '$1<a href="$2" target="_blank">$2</a>', $content);
			$content = preg_replace('@^(https?://([-\w\.]+[-\w])+(:\d+)?(/([' . $regex . ']*(\?\S+)?[^\.\s])?)?)@ui', '<a href="$1" target="_blank">$1</a>', $content);
		}
	}
	return $content;
}
function getAccCreate($user){
	return $user;
}
function getCountry($user){
	return $user;
}

function registration(){
	global $data;
	if($data['registration'] == 1){
		return true;
	}
}
function sessionCleanup(){
	global $wali;
	unset($_SESSION['facebook_access_token']);
	unset($_SESSION[WALI_PREFIX . 'token']);
	unset($_SESSION[WALI_PREFIX . 'last']);
	unset($_SESSION[WALI_PREFIX . 'flood']);
	unset($_SESSION['HA::STORE']);
	unset($_SESSION['HA::CONFIG']);
	unset($_SESSION['FBRLH_state']);
	unset($_SESSION['token']);
}
function waliLogged(){
	global $wali_access;
	if($wali_access == 1){
		return true;
	}
}
function waliSanitize($t){
	global $mysqli;
	$t = str_replace(array('\\', '/', '.', '<', '>', '%', '#'), '', $t);
	return $mysqli->real_escape_string(trim(htmlspecialchars($t, ENT_QUOTES)));
}
function getLanguage(){
	global $mysqli, $data, $wali;
	$l = $data['language'];
	if(waliLogged()){
		if(file_exists(WALI_PATH . 'system/language/' . $data['user_language'] . '/language.php')){
			$l = $data['user_language'];
		}
		else {
			$mysqli->query("UPDATE wali_users SET user_language = '{$data['language']}' WHERE user_id = '{$data['user_id']}'");
		}
	}
	else {
		if(isset($_COOKIE['lang'])){
			$lang = 'English';
			if(file_exists(WALI_PATH . 'system/language/' . $lang . '/language.php')){
				$l = $lang;
			}
		}
	}
	return $l;
}

function userDetails($id){
	global $mysqli;
	$user = array();
	$getuser = $mysqli->query("SELECT * FROM wali_users WHERE user_id = '$id'");
	if($getuser->num_rows > 0){
		$user = $getuser->fetch_assoc();
	}
	return $user;
}
function canInfo(){
	global $wali;
	if(waliAllow($wali['can_edit_info'])){
		return true;
	}
}
function listCountry($c){
	global $lang;
	require WALI_PATH . '/system/location/country_list.php';
	$list_country = '';
	$list_country .= '<option value="ZZ" ' . selCurrent($c, 'ZZ') . '>' . $lang['not_shared'] . '</option>';
	foreach ($country_list as $country => $key) {
		$list_country .= '<option ' . selCurrent($c, $country) . ' value="' . $country . '">' . $key . '</option>';
	}	
	return $list_country;
}

function introLanguage(){
	$language_list = '';
	$dir = glob(WALI_PATH . '/system/language/*', GLOB_ONLYDIR);
	foreach($dir as $dirnew){
		$language = str_replace( WALI_PATH . '/system/language/', '', $dirnew);
		$language_list .= waliTemplate('element/language', $language);
	}
	return $language;
}

function listLanguage($lang){
	$language_list = '';
	$dir = glob(WALI_PATH . '/system/language/*', GLOB_ONLYDIR);
	foreach($dir as $dirnew){
		$language = str_replace(WALI_PATH . '/system/language/', '', $dirnew);
		$language_list .= '<option ' . selCurrent($lang, $language) . ' value="' . $language . '">' . $language . '</option>';
	}
	return $language_list;
}

function getTimezone($zone){
	$list_zone = '';
	require 'element/timezone.php';
	foreach ($timezone as $line) {
		$list_zone .= '<option value="' . $line . '" ' . selCurrent($zone, $line) . '>' . $line . '</option>';
	}
	return $list_zone;
}

function listTheme($th){
	$theme_list = '';
	$dir = glob('../../themes/*', GLOB_ONLYDIR);
	foreach($dir as $dirnew){
		$theme = str_replace('../../themes/', '', $dirnew);
		$theme_list .= '<option ' . selCurrent($th, $theme) . ' value="' . $theme . '">' . $theme . '</option>';
	}
	return $theme_list;
}
function getActiveStatus($user){
	$stat = '';
	if($user['user_status'] == 1){
		$stat = '<img title="Online" class="list_status" src="default_images\status\online.svg"/>';
	}
	if($user['user_status'] == 2){
		$stat = '<img title="Away" class="list_status" src="default_images\status\away.svg"/>';
	}
	
	if($user['user_status'] == 3){
		$stat = '<img title="Busy" class="list_status" src="default_images\status\busy.svg"/>';
	}
	
	return $stat;
}

function createUserData($user){
	return  '
	<div class="bcell_mid hpad10">
		<div class="menuranktxt">'. chatRank($user) .'</div>
		<div class="menuname bellips globname">'. $user->user_name .'</div>
	</div>';
}

function getTopStatus($user){
	global $conn;
	$username = $_COOKIE['username'];
	$sql = "SELECT * FROM wali_users WHERE user_name = '$username'";
	$result = mysqli_query($conn, $sql);
	if (mysqli_num_rows($result) > 0) {
		while($row = mysqli_fetch_assoc($result)){
			$stat = '';
			switch($row['user_status']){
				case '1':
					$stat = "default_images\status\online.svg";
					break;
				case '2':
					$stat = "default_images\status\away.svg";
					break;
				case '3':
					$stat = "default_images\status\busy.svg";
					break;
				case '4':
					$stat = "default_images\status\invisible.svg";
					break;
			}
			return $stat;
			}
		}
}
function getActiveStatusTitle($user){
	$statTit = '';
	if($user['user_status'] == 1){
		$statTit = 'Online';
	}

	if($user['user_status'] == 2){
		$statTit = 'Away';
	}
	
	if($user['user_status'] == 3){
		$statTit = 'Busy';
	}
	
	return $statTit;
}

function getUserId($user){
	echo $user['user_id'];
}
function getUsername($user){
	echo $user['user_name'];
}
function getUserTime($user){
	echo $user['user_timezone'];

} 
function getUserCountry($user){
	echo $user['country'];

}
function getUserLang($user){
	echo $user['user_language'];
}
function addFriend($user){
	echo $user['user_id'];
}

function waliUserInfo($id){
	global $mysqli, $data;
	$user = array();
	$getuser = $mysqli->query("SELECT *,
	(SELECT fstatus FROM wali_friends WHERE hunter = '{$data['user_id']}' AND target = '$id') as friendship,
	(SELECT count(ignore_id) FROM wali_ignore WHERE ignorer = '{$data['user_id']}' AND ignored = '$id' OR ignorer = '$id' AND ignored = '{$data['user_id']}') as ignored
	FROM wali_users WHERE `user_id` = '$id'");
	if($getuser->num_rows > 0){
		$user = $getuser->fetch_assoc();
		$user['friendship'] = waliNull($user['friendship']);
	}
	return $user;
}
function waliNull($val){
	if(is_null($val)){
		return 0;
	}
	else {
		return $val;
	}
}
function mySelf($id){
	global $data;
	if($id == $data['user_id']){
		return true;
	}
}
function insideChat($p){
	if($p == 'chat'){
		return true;
	}
}
function emptyZone($text, $icon = ''){
	$zone['text'] = $text;
	$zone['icon'] = 'nodata.svg';
	if($icon != ''){
		$zone['icon'] = $icon;
	}
	return waliTemplate('element/empty_zone', $zone);	
}
function userRoomDetails($id){
	global $mysqli, $data;
	$user = array();
	$getuser = $mysqli->query("SELECT *,
	(SELECT room_rank FROM wali_room_staff WHERE room_staff = '$id' AND room_id = '{$data['user_roomid']}') as room_ranking,
	(SELECT count(*) FROM wali_room_action WHERE action_muted = '1' AND action_user = '$id' AND action_room = '{$data['user_roomid']}') as is_muted,
	(SELECT count(*) FROM wali_room_action WHERE action_blocked = '1' AND action_user = '$id' AND action_room = '{$data['user_roomid']}') as is_blocked
	FROM wali_users WHERE user_id = '$id'");
	if($getuser->num_rows > 0){
		$user = $getuser->fetch_assoc();
		$user['room_ranking'] = waliNull($user['room_ranking']);
	}
	return $user;
}
function userRoomStaff($rank){
	if($rank >= 3){
		return true;
	}
}
function isOwner($user){
	if($user['user_rank'] == 6){
		return true;
	}
}
function isStaff($rank){
	if($rank >= 3){
		return true;
	}
}
function betterRole($rank){
	global $data;
	if($data['user_role'] > $rank || waliAllow(4)){
		return true;
	}
}
function canRoom(){
	global $data, $wali;
	if($data['user_rank'] > $data['allow_room']){
		return true;
	} 
}
function canRoomAction($user, $role, $type = 1){
	global $mysqli, $data;
	if(empty($user)){
		return false;
	}
	if(mySelf($user['user_id'])){
		return false;
	}
	if(!waliRole($role) && !waliAllow(3)){
		return false;
	}
	if(isStaff($user['user_rank']) || isBot($user)){
		return false;
	}
	if(!betterRole($user['room_ranking']) && !waliAllow(3)){
		return false;
	}
	if($type == 2 && userRoomStaff($user['room_ranking'])){
		return false;
	}
	return true;
}
function systemBot($user){
	if($user == 9){
		return true;
	}
}
function canEditUser($user, $rank, $type = 0)
{
	global $data;
	if ($type == 1 && isBot($user)) {
		return false;
	}
	if (mySelf($user['user_id'])) {
		return false;
	}
	if (waliAllow($rank) && isGreater($user['user_rank']) && !isBot($user)) {
		return true;
	}
	if (waliAllow(6) && !isOwner($user)) {
		return true;
	}
}
function isMuted($user){
	if($user['user_mute'] > time()){
		return true;
	}
}
function isRegmute($user){
	if($user['user_regmute'] > time()){
		return true;
	}
}
function canMute(){
	global $data, $wali;
	if(waliAllow(4)){
		return true;
	}
}
function canMuteUser($user){
	global $data, $wali;
	if(!empty($user) && canEditUser($user, 3, 1)){ 
		return true;
	}
}
function isGreater($rank){
	global $data;
	if($data['user_rank'] > $rank){
		return true;
	}
}
function canBan(){
	global $data, $wali;
	if(waliAllow($wali['can_ban'])){
		return true;
	}
}
function canBanUser($user){
	global $data, $wali;
	if(!empty($user) && canEditUser($user, $wali['can_ban'], 1)){ 
		return true;
	}
}
function canRankUser($user){
	global $data, $wali;
	if (isOwner($user) || isGuest($user)) {
		return false;
	}
	if (!empty($user) && canEditUser($user, $wali['can_rank'], 0)) {
		return true;
	}
}
function canRoomPassword(){
	global $data, $wali;
	if(waliAllow($wali['can_room_pass']) || waliRole(6)){
		return true;
	}
}
function canDeleteUser($user){
	global $data, $wali;
	if(isOwner($user)){
		return false;
	}
	if(!empty($user) && canEditUser($user, $wali['can_delete'], 1)){ 
		return true;
	}
}
function canKick(){
	global $data, $wali;
	if(waliAllow($wali['can_kick'])){
		return true;
	}
}
function canKickUser($user){
	global $data, $wali;
	if(!empty($user) && canEditUser($user, $wali['can_kick'], 1)){ 
		return true;
	}
}
function isBanned($user){
	if($user['user_banned'] > 0){
		return true;
	}
}
function isKicked($user){
	if($user['user_kick'] > time()){
		return true;
	}
}
function rankTitle($rank){
	switch($rank){
		case 0:
			return 'User';
		case 1:
			return 'Vip';
		case 2:
			return 'Elite Vip';
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
function changeRank($current){
	global $data, $wali;
	$rank = '';
	if(waliAllow($wali['can_rank'])){
		$rank .= '<option value="0" ' . selCurrent($current, 0) . '>' . rankTitle(0) . '</option>';
		$rank .= '<option value="1" ' . selCurrent($current, 1) . '>' . rankTitle(1) . '</option>';
		$rank .= '<option value="2" ' . selCurrent($current, 2) . '>' . rankTitle(2) . '</option>';
		$rank .= '<option value="3" ' . selCurrent($current, 3) . '>' . rankTitle(3) . '</option>';
	}
	if(waliAllow(5)){
		$rank .= '<option value="4" ' . selCurrent($current, 4) . '>' . rankTitle(4) . '</option>';
		$rank .= '<option value="5" ' . selCurrent($current, 5) . '>' . rankTitle(5) . '</option>';
	}
	return $rank;
}
function useLobby(){
	global $data;
	if($data['use_lobby'] == 1){
		return true;
	}
}
function setToken(){
	global $data, $wali;
	if(!empty($_SESSION[WALI_PREFIX . 'token'])){
		$_SESSION[WALI_PREFIX . 'token'] = $_SESSION[WALI_PREFIX . 'token'];
		return $_SESSION[WALI_PREFIX . 'token'];
	}
	else {
		$session = md5(rand(000000,999999));
		$_SESSION[WALI_PREFIX . 'token'] = $session;
		return $session;
	}
}
function roomDetails($type = 0){
	global $data, $mysqli;
	$muted = 0;
	$status = 0;
	$get_room = $mysqli->query("SELECT *,
	(SELECT count(id) FROM wali_room_action WHERE action_room = '{$data['user_roomid']}' AND action_user = '{$data['user_id']}' AND action_muted = 1) as is_muted,
	(SELECT room_rank FROM wali_room_staff WHERE room_staff = '{$data['user_id']}' AND room_id = '{$data['user_roomid']}') as room_status
	FROM wali_rooms
	WHERE room_id = '{$data['user_roomid']}'");
	if($get_room->num_rows > 0){
		$room = $get_room->fetch_assoc();
		if($type == 1){
			if($room['is_muted'] > 0){
				$muted = 1;
			}
			if(!is_null($room['room_status'])){
				$status = $room['room_status'];
			}
			$mysqli->query("UPDATE wali_users SET room_mute = '$muted', user_role = '$status' WHERE user_id = '{$data['user_id']}'");			
		}
	}
	else {
		$room = array();
	}
	return $room;
}
function getLoginPage(){
	global $data;
	return $data['login_page'];
}
function kickedData($user){
	if($user['user_kick'] > 0){
		return true;
	}
}
function checkBan($ip){
	global $mysqli, $data;
	if(waliLogged()){
		if(waliAllow(6)){
			return false;
		}
		if(isBanned($data)){
			return true;
		}
		else {
			$getip = $mysqli->query("SELECT * FROM wali_banned WHERE ip = '$ip'");
			if($getip->num_rows > 0){
				return true;
			}
		}
	}
}
function removeAllAction($user){
	global $mysqli;
	$mysqli->query("UPDATE wali_users SET user_kick = 0, user_mute = 0, user_ban = 0, user_regmute = 0 WHERE user_id = {$user['user_id']}");
}
function userUnkick($user){
	global $mysqli;
	$mysqli->query("UPDATE wali_users SET user_kick = 0 WHERE user_id = '{$user['user_id']}'");
}
function checkKick(){
	global $mysqli, $data;
	if(waliLogged()){
		if(kickedData($data)){
			if(isKicked($data)){
				if(waliAllow(11)){
					removeAllAction($user);
				}
				else {
					return true;
				}
			}
			else {
				userUnkick($data);
			}
		}
	}
}
function getPageData($page_data = array()){
	global $data;
	$page_default = array(
		'page'=> '',
		'page_load'=> '',
		'page_menu'=> 0,
		'page_rank'=> 0,
		'page_room'=> 1,
		'page_out'=> 0,
		'page_title'=> $data['title'],
		'page_keyword'=> $data['site_keyword'],
		'page_description'=> $data['site_description'],
		'page_rtl'=> 1,
		'page_nohome'=> 0,
	);
	$page = array_merge($page_default, $page_data);
	return $page;
}
function getRoomId(){
	global $mysqli, $data;
	if(waliLogged()){
		if($data['user_roomid'] == 0 && !isBanned($data)){
			if(!useLobby()){
				$mysqli->query("UPDATE wali_users SET user_roomid = '1' WHERE user_id = '{$data['user_id']}'");
				return 1;
			}
		}
		else {
			return $data['user_roomid'];
		}
	}
	return 0;
}
function genSnum(){
	global $data;
	return $data['user_id'] . rand(1111111, 9999999);
}
function inRoom(){
	global $data;
	if($data['user_roomid'] != '0'){
		return true;
	}
}
function mainRoom(){
	global $data;
	if($data['user_roomid'] == 1){
		return true;
	}
}
function listRoomRank($current = 0){
	global $lang, $data;
	$rank = '';
	$rank .= '<option value="0" ' . selCurrent($current, 0) . '>' . $lang['none'] . '</option>';
	$rank .= '<option value="7" ' . selCurrent($current, 7) . '>' . roomRankTitle(7) . '</option>';
	$rank .= '<option value="8" ' . selCurrent($current, 8) . '>' . roomRankTitle(8) . '</option>';
	if(waliAllow(4)){
		$rank .= '<option value="9" ' . selCurrent($current, 9) . '>' . roomRankTitle(9) . '</option>';
	}
	return $rank;
}

function getLogo(){
	global $mysqli, $data;
	$logo = 'default_images/logo.png';
	if(waliLogged()){
		if($data['user_theme'] != 'system'){
			if(file_exists('themes/' . $data['user_theme'] . '/images/logo.png')){
				$logo = 'themes/' . $data['user_theme'] . '/images/logo.png';
			}
		}
	}
	else {
		if(file_exists('/css/themes/' . $data['default_theme'] . '/images/logo.png')){
			$logo = '/css/themes/' . $data['default_theme'] . '/images/logo.png';
		}
	}
	return $logo;
}

function waliFooterMenu(){
	global $data, $lang;
	include 'control/footer_menu.php';
}
function introActive($amount){
	global $mysqli;
	$find_last = $mysqli->query("SELECT user_avatar, user_name FROM wali_users WHERE user_bot = 0 AND user_rank > 0 ORDER BY last_action DESC LIMIT $amount");
	$active = '';
	if($find_last->num_rows > 0){
		while ($last = $find_last->fetch_assoc()){
			$active .= waliTemplate('element/active_intro', $last);
		}
	}
	return $active;
}

function allowGuest(){
	global $data;
	if($data['allow_guest'] == 1){
		return true;
	}
}

function canManageReport(){
	global $wali;
	if(waliAllow($wali['can_manage_report'])){
		return true;
	}
}

function statusIcon($status){
	switch($status){
		case 1:
			return 'online.svg';
		case 2:
			return 'away.svg';
		case 3:
			return 'busy.svg';
		case 4:
			return 'invisible.svg';
		default:
			return 'online.svg';
	}	
}
function statusMenu($txt, $icon){
	return '<div class="status_zone"><img class="status_icon" src="default_images/status/' . $icon . '"/></div><div class="status_text">' . $txt . '</div>';
}
function listStatus($status){
	switch($status){
		case 1:
			return statusMenu(statusTitle(1), statusIcon(1));
		case 2:
			return statusMenu(statusTitle(2), statusIcon(2));
		case 3:
			return statusMenu(statusTitle(3), statusIcon(3));
		case 4:
			return statusMenu(statusTitle(4), statusIcon(4));
		default:
			return statusMenu(statusTitle(1), statusIcon(1));
	}
}

function getTheme(){
	global $mysqli, $data;
	$t = $data['default_theme']; 
	if(waliLogged()){
		if($data['user_theme'] != 'system'){
			if(file_exists('themes/' . $data['user_theme'] . '/' . $data['user_theme'] . '.css')){
				$t = $data['user_theme'];
			}
			else {
				$mysqli->query("UPDATE wali_users SET user_theme = 'system' WHERE uid = '{$data['user_id']}'");
			}
		}
	}
	return $t . '/' . $t . '.css';
}

function getPrivateTheme(){
	global $mysqli, $data;
	$pt = $data['user_private_theme'];
	if(waliLogged()){
		if($data['user_private_theme'] != ""){
			if(file_exists('upload/private/bg/' . $data['user_private_theme'])){
				$pt = $data['user_private_theme'];
			}
			else {
				$mysqli->query("UPDATE wali_users SET user_private_theme = '' WHERE uid = '{$data['user_id']}'");
			}
		}
	}
	return 'upload/private/bg/' . $pt;
}

function bridgeMode($type){
	global $data;
	if($data['use_bridge'] == $type){
		return true;
	}
}

function emoItem($type){
	switch($type){
		case 1:
			$emoclass = 'emo_menu_item';
			break;
		case 2:
			$emoclass = 'emo_menu_item_priv';
			break;
	}
	$emo = '';
	$dir = glob('emoticon/*' , GLOB_ONLYDIR);
	foreach($dir as $dirnew){
		$emoitem = str_replace('emoticon/', '', $dirnew);
		$emo .= '<div data="' . $emoitem . '" class="emo_menu ' . $emoclass . '"><img class="emo_select" src="emoticon_icon/' . $emoitem . '.png"/></div>';
	}
	return $emo;
}

function canEmo(){
	global $data;
	if(waliAllow($data['emo_plus'])){
		return true;
	}
}
function listSmilies($type){
	$supported = array('.png', '.svg', '.gif'); // can change as well
	switch($type){
		case 1:
			$emo_act = 'content';
			$closetype = 'closesmilies';
			break;
		case 2:
			$emo_act = 'message_content';
			$closetype = 'closesmilies_priv';
			break;
	}
	$files = scandir(WALI_PATH . '/emoticon');
	foreach ($files as $file){
		if ($file != "." && $file != ".."){
			$smile = preg_replace('/\.[^.]*$/', '', $file);
			foreach($supported as $sup){
				if(strpos($file, $sup)){
					echo '<div  title=":' . $smile . ':" class="emoticon ' . $closetype . '"><img  class="lazyboom" data-img="emoticon/' . $smile . $sup . '" src="" onclick="emoticon(\'' . $emo_act . '\', \':' . $smile . ':\')"/></div>';;
				}
			}
		}
	}
}
function canUploadChat(){
	global $data;
	if(waliAllow($data['allow_cupload'])){
		return true;
	}
}
function canUploadPrivate(){
	global $data;
	if(waliAllow($data['allow_pupload'])){
		return true;
	}
}
function canUploadWall()
{
	global $data;
	if (waliAllow($data['allow_wupload'])) {
		return true;
	}
}
function canDeleteWall($wall)
{
	global $data, $wali;
	if (mySelf($wall['post_user'])) {
		return true;
	}
	if (waliAllow($wali['can_delete_wall']) && isGreater($wall['user_rank'])) {
		return true;
	}
}
function canDeletePrivate(){
	global $wali;
	if(waliAllow($wali['can_delete_private'])){
		return true;
	}
}
function useWall(){
	global $data;
	if($data['use_wall'] == 1){
		return true;
	}
}
//////////////////////////////////////////////////
function getAvatar($usr){
		return $usr;    
}

function canUCol(){
	global $data;
	
	if($data['user_rank'] >= $data['allow_name_color']){
		return true;	
	}
}

function canUsername(){
	global $data;

	if($data['user_rank'] >= $data['allow_name']){
		return true;
	}
	
}
function canTheme(){
	global $data;
	
	if($data['user_rank'] >= $data['allow_theme']){
		return true;	
	}
}
function waliSame($val1, $val2){
	if(mb_strtolower($val1) == mb_strtolower($val2)){
		return true;
	}
}
function calMinutes($min){
	return time() - ($min * 60);
}
function nameDetails($name){
	global $mysqli, $data;
	$user = array();
	$getuser = $mysqli->query("SELECT * FROM wali_users WHERE user_name = '$name'");
	if($getuser->num_rows > 0){
		$user = $getuser->fetch_assoc();
	}
	return $user;
}
function isGuest($user){
	if($user['user_rank'] == 8){
		return true;
	}
}
function encrypt($d){
	return sha1(str_rot13($d));
}
function randomPass(){
	$text = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ01234567890++--@@@___';
	$text = substr(str_shuffle($text), 0, 10);
	return encrypt($text);
}
function softGuestDelete($u){
	global $mysqli, $wali, $data;
	$id = $u['uid'];
	if(!isGuest($u)){
		return false;
	}
	$new_pass = randomPass();
	$new_name = '@' . $u['user_name'] . '-' . $id;
	$mysqli->query("DELETE FROM room_action WHERE action_user = '$id'");
	$mysqli->query("DELETE FROM room_staff WHERE room_staff = '$id'");
	$mysqli->query("UPDATE wali_users SET user_name = '$new_name', user_password = '$new_pass' WHERE user_id = '$id'");
}
function waliUsername($name){
	global $mysqli, $wali;
	$user = nameDetails($name);
	if(empty($user)){
		return true;
	}
	else {
		if(isGuest($user) && $user['last_action'] < calMinutes($wali['guest_delay'])){
			softGuestDelete($user);
			return true;
		}
	}
}
function changeNameLog($user, $n){
	global $lang, $data, $wali;
	if(allowLogs() && isVisible($user) && $wali['name_change'] == 1){
		$content = str_replace('%user%', $user['user_name'], $lang['system_name_change']);
		$user['user_name'] = $n;
		$content = str_replace('%nname%', systemNameFilter($user), $content);
		systemPostChat($user['user_roomid'], $content, array('type'=> 'system__action'));
	}
}
function validName($name){
	global $data, $mysqli;
	$lowname = mb_strtolower($name);
	$get_name = $mysqli->query("SELECT word FROM wali_filter WHERE word_type != 'email'");
	if($get_name->num_rows > 0){
		while($reject = $get_name->fetch_assoc()){
			if(stripos($lowname, mb_strtolower($reject['word'])) !== FALSE){
				return false;
			}
		}
	}
	$regex = 'a-zA-Z0-9\p{Arabic}\p{Cyrillic}\p{Latin}\p{Han}\p{Katakana}\p{Hiragana}\p{Hebrew}';
	if(preg_match('/^[' . $regex . ']{1,}([\-\_ ]{1})?([' . $regex . ']{1,})?$/ui', $name) && mb_strlen($name, 'UTF-8') <= $data['max_username'] && !ctype_digit($name) && mb_strlen($name, 'UTF-8') >= 2){
		return true;
	}
	return false;
}
function isEmail($email){
	if(filter_var($email, FILTER_VALIDATE_EMAIL) && !strstr($email, '+')){
		return true;
	}
}
function validEmail($email){
	global $data, $mysqli;
	if(!isEmail($email)){
		return false;
	}
	if($data['email_filter'] == 1){
		$get_end = explode('@', $email);
		$get_domain = explode('.', $get_end[1]);
		$allowed = $get_domain[0];
		$get_email = $mysqli->query("SELECT word FROM wali_filter WHERE word_type = 'email' AND word = '$allowed'");
		if($get_email->num_rows < 1){
			return false;
		}
	}
	return true;
}
function waliValidPassword($pass){
	if(strlen($pass) > 8){
		return true;
	}
}
function nameExist($name){
	global $data, $mysqli;
	$get_name = $mysqli->query("SELECT user_name FROM wali_users");
}
function checkName($name){
	if(preg_match('/[\'^£$%&*()}{@#~?><>,|=_+¬-]/', $name)){
		return true;
	}
}
function isTooLong($text, $max){
	if(mb_strlen($text, 'UTF-8') > $max){
		return true;
	}
}
function roomType($type){
	global $data;
	if($type >= 0 && $type <= $data['user_rank']){
		return true;
	}
}
function validRoomName($name){
	global $data, $wali;
	if(strlen($name) <= $wali['max_room_name'] && strlen($name) >= 4 && preg_match("/^[a-zA-Z0-9 _\-\p{Arabic}\p{Cyrillic}\p{Latin}\p{Han}\p{Katakana}\p{Hiragana}\p{Hebrew}]{4,}$/ui", $name)){
		return true;
	}
}
function checkFlood(){
	global $wali, $data;
	if(waliAllow($wali['can_flood'])){
		return false;
	}
	if(isset($_SESSION[WALI_PREFIX . 'last'], $_SESSION[WALI_PREFIX . 'flood'])){
		if($_SESSION[WALI_PREFIX . 'last'] >= time() - 2){
			$_SESSION[WALI_PREFIX . 'last'] = time();
			$_SESSION[WALI_PREFIX . 'flood'] = $_SESSION[WALI_PREFIX . 'flood'] + 1;
			if($_SESSION[WALI_PREFIX . 'flood'] >= $wali['flood_limit']){
				// systemFloodMute($data);
				return true;
			}
			else {
				return false;
			}
		}
		else {
			$_SESSION[WALI_PREFIX . 'last'] = time();
			$_SESSION[WALI_PREFIX . 'flood'] = 0;
			return false;
		}
	}
	else {
		$_SESSION[WALI_PREFIX . 'last'] = time();
		$_SESSION[WALI_PREFIX . 'flood'] = 0;
		return false;
	}
}
function trimContent($text){
	$text = str_ireplace(array('****', 'system__', 'public__', 'my_notice', '%bcclear%', '%bcjoin%', '%bcquit%', '%bckick%', '%bcban%', '%bcmute%', '%bcname%', '%spam%'), '*****', $text);
	return $text;
}
function processFilterReason($word, $text){
	$rep = preg_quote($word, '/');
	return preg_replace("/($rep)/i", '$1', $text);
}
function systemMute($user, $delay, $reason = ''){
	global $mysqli;
	$mute_end = max($user['user_mute'], calMinutesUp($delay));
	$mysqli->query("UPDATE wali_users SET user_mute = '$mute_end', mute_msg = '$reason', user_regmute = 0 WHERE user_id = '{$user['user_id']}'");
	clearNotifyAction($user['user_id'], 'mute');
	muteLog($user);
}
function systemWordMute($user, $custom = ''){
	global $data, $mysqli, $lang;
	if(isMuted($user) || isRegmute($user)){
		return false;
	}
	if(!isStaff($user['user_rank']) && !isBot($user)){
		systemMute($user, $data['word_delay'], 'badword');
		waliNotify('word_mute', array('target'=> $user['user_id'], 'source'=> 'mute', 'delay'=> $data['word_delay']));
		// waliHistory('word_mute', array('hunter'=> $data['system_id'], 'target'=> $user['user_id'], 'delay'=> $data['word_delay'], 'reason'=> $custom));
		waliConsole('word_mute', array('hunter'=>$data['system_id'], 'target'=> $user['user_id'], 'reason'=>$custom, 'delay'=> $data['word_delay']));
	}
}
function systemUnmute($user)
{
	global $mysqli;
	clearNotifyAction($user['user_id'], 'mute');
	$mysqli->query("UPDATE wali_users SET user_mute = 0, mute_msg = '', user_regmute = 0 WHERE user_id = '{$user['user_id']}'");
}
function systemKick($user, $delay, $reason = ''){
	global $mysqli;
	$this_delay = max($user['user_kick'], calMinutesUp($delay));
	$mysqli->query("UPDATE wali_users SET user_kick = '$this_delay', kick_msg = '$reason', user_action = user_action + 1 WHERE user_id = '{$user['user_id']}'");
	kickLog($user);
}
function systemWordKick($user, $custom = ''){
	global $mysqli, $data, $lang;
	if(isKicked($user)){
		return false;
	}
	if(!isStaff($user['user_rank']) && !isBot($user)){
		systemKick($user, $data['word_delay'], 'badword');
		// waliHistory('word_kick', array('hunter'=> $data['system_id'], 'target'=> $user['user_id'], 'delay'=> $data['word_delay'], 'reason'=> $custom));
		waliConsole('word_kick', array('hunter'=>$data['system_id'], 'target'=> $user['user_id'], 'reason'=>$custom, 'delay'=> $data['word_delay']));
	}
}
function systemUnkick($user)
{
	global $mysqli;
	$mysqli->query("UPDATE wali_users SET user_kick = '0', kick_msg = '', user_action = user_action + 1 WHERE user_id = '{$user['user_id']}'");
}
function systemSpamMute($user, $custom = ''){
	global $mysqli, $data, $lang, $wali;
	if(isMuted($user) || isRegmute($user)){
		return false;
	}
	if(!isStaff($user['user_rank']) && !isBot($user)){
		systemMute($user, $data['spam_delay'], 'spam');
		waliNotify('spam_mute', array('target'=> $user['user_id'], 'source'=> 'mute', 'delay'=> $data['spam_delay']));
		// waliHistory('spam_mute', array('hunter'=> $data['system_id'], 'target'=> $user['user_id'], 'delay'=> $data['spam_delay'], 'reason'=> $custom));
		waliConsole('spam_mute', array('hunter'=>$data['system_id'], 'target'=> $user['user_id'], 'reason'=> $custom, 'delay'=> $data['spam_delay']));
	}
}
function systemSpamBan($user, $custom = ''){
	global $mysqli, $data, $lang;
	if(isBanned($user)){
		return false;
	}
	if(!isStaff($user['user_rank']) && !isBot($user)){
		systemBan($user, 'spam');
		// waliHistory('spam_ban', array('hunter'=> $data['system_id'], 'target'=> $user['user_id'], 'reason'=> $custom));
		waliConsole('spam_ban', array('hunter'=>$data['system_id'], 'target'=> $user['user_id'], 'reason'=>$custom));
	}
}
function systemBan($user, $reason = ''){
	global $mysqli;
	$mysqli->query("UPDATE wali_users SET user_banned = '" . time() . "', ban_msg = '$reason', user_action = user_action + 1, user_roomid = '0' WHERE user_id = '{$user['user_id']}'");
	if (!waliDuplicateIp($user['user_ip'])) {
		$mysqli->query("INSERT INTO wali_banned (ip, ban_user) VALUES ('{$user['user_ip']}', '{$user['user_id']}')");
	}
	banLog($user);
}
function wordFilter($text, $type = 0){
	global $mysqli, $data, $wali, $lang;
	$text2 = trimContent($text);
	$text = trimContent($text);
	$text_trim = mb_strtolower(str_replace(array(' '), '', $text));
	$take_action = 0;
	$spam_action = 0;
	$reason = '';
	if(!waliAllow($wali['can_word_filter'])){
		$words = $mysqli->query("SELECT * FROM `wali_filter` WHERE word_type = 'word' OR word_type = 'spam'");
		if ($words->num_rows > 0){
			while($filter = $words->fetch_assoc()){
				if($filter['word_type'] == 'word'){
					if(stripos($text, $filter['word']) !== false){
						$text = str_ireplace($filter['word'], '****',$text);
						$text2 = processFilterReason($filter['word'], $text2);
						$take_action++;
					}
				}
				else if($filter['word_type'] == 'spam'){
					if(stripos($text_trim, $filter['word']) !== false){
						$text2 = processFilterReason($filter['word'], $text2);
						$spam_action++;
					}
				} 
			}
		}
		if($take_action > 0 && $type == 1 && $spam_action == 0){
			switch($data['word_action']){
				case 2:
					systemWordMute($data, $text2);
					break;
				case 3:
					systemWordKick($data, $text2);
					break;
			}
		}
		if($spam_action > 0){
			$text = waliTemplate('element/spam_text');
			switch($data['spam_action']){
				case 1:
					systemSpamMute($data, $text2);
					break;
				case 2:
					systemSpamBan($data, $text2);
					break;
			}
		}
	}
	return $text;
}
function canFriend($user){
	if($user['friendship'] < 2){
		return true;
	}
}
function haveFriendship($user){
	if($user['friendship'] == 3){
		return true;
	}
}
function canSendPrivate($id){
	global $mysqli, $data;
	$user = waliUserInfo($id);
	if(empty($user)){
		return false;
	}
	if(isStaff($data['user_rank'])){
		return true;
	}
	if($user['user_private'] == 0){
		return false;
	}
	if($data['user_private'] == 0 && !isStaff($data['user_rank'])){
		return false;
	}
	if($user['user_private'] == 2 && !haveFriendship($user)){
		return false;
	}
	if($data['user_private'] == 2 && !haveFriendship($user)){
		return false;
	}
	if($user['user_private'] == 3 && $data['user_rank'] < 1){
		return false;
	}
	if($user['ignored'] > 0){
		return false;
	}
	return true;
}
function roomExist($name, $id){
	global $mysqli;
	$check_room = $mysqli->query("SELECT room_name FROM wali_rooms WHERE room_name = '$name' AND room_id != '$id'");
	if($check_room->num_rows > 0){
		return true;
	}
}
function clearBreak($text){
	$text = preg_replace("/[\r\n]{2,}/", "\n\n", $text);
	return $text;
}
function isBadText($text){
	global $mysqli, $data;
	$text = trimContent($text);
	$text_trim = mb_strtolower(str_replace(array(' '), '', $text));
	if(!waliAllow(5)){
		$words = $mysqli->query("SELECT * FROM `wali_filter` WHERE word_type = 'word' OR word_type = 'spam'");
		if ($words->num_rows > 0){
			while($filter = $words->fetch_assoc()){
				if($filter['word_type'] == 'word'){
					if(stripos($text, $filter['word']) !== false){
						return true;
					}
				}
				else if($filter['word_type'] == 'spam'){
					if(stripos($text_trim, $filter['word']) !== false){
						return true;
					}
				}
			}
		}
	}
}
function validAge($age){
	global $data;
	if($age == ''){
		return false;
	}
	if($age == 0){
		return true;
	}
	if($age >= $data['min_age'] && $age != "" && $age < 100){
		return true;
	}
}
function unlinkAvatar($file){
	if(!defaultAvatar($file)){
		$delete =  WALI_PATH. '/avatar/' . $file;
		if(file_exists($delete)){
			unlink($delete);
		}
	}
	return true;
}
function genderAvatar($s){
	switch($s){
		case 1:
			return 'avatar/default_male.png';
		case 2:
			return 'avatar/default_female.png';
		default:
			return 'avatar/default_avatar.png';
	}
}
function resetAvatar($u){
	global $mysqli;
	$unlink_tumb = unlinkAvatar($u['user_avatar']);
	if(isBot($u)){
		switch($u['user_bot']){
			case 1:
				$av = 'avatar/default_bot.png';
				break;
			case 9:
				$av = 'avatar/default_system.png';
				break;
			default:
				$av = 'avatar/default_bot.png';
		}
	}
	else {
		switch($u['user_rank']){
			case 0:
				$av = 'avatar/default_guest.png';
				break;
			default:
				$av = genderAvatar($u['user_sex']);
		}
	}
	$mysqli->query("UPDATE wali_users SET user_avatar = '$av' WHERE user_id = '{$u['user_id']}'");
	return $av;
}
function createSwitch($id, $val, $ccall = 'noAction'){
	switch($val){
		case 0:
			return '<div id="' . $id . '" class="btable bswitch offswitch" data="0" data-c="' . $ccall . '">
						<div class="bball_wrap"><div class="bball offball"></div></div>
					</div>';
		case 1:
			return '<div id="' . $id . '" class="btable bswitch onswitch" data="1" data-c="' . $ccall . '">
						<div class="bball_wrap"><div class="bball onball"></div></div>
					</div>';
		default:
			return false;
	}
}
function getMood($user){
	global $lang;
	if($user['user_mood'] == ''){
		return $lang['unset_mood'];
	}
	else {
		return $user['user_mood'];
	}
}
function soundCode($sound, $val){
	if($val > 0){
		switch($sound){
			case 'chat':
				return '1';
			case 'private':
				return '2';
			case 'notify':
				return '3';
			case 'name':
				return '4';
			default:
				return '';
		}
	}
	else {
		return '';
	}
}

function fileError($type = 1){
	global $data;
	$size = 3000000;
	if($type == 2){
		$size = 3000000;
	}else if($type == 3){
		$size = 3000000;
	}
	if($_FILES["file"]["error"] > 0 || (($_FILES["file"]["size"] / 1024)/1024) > $size){
		return true;
	}
}
function isImage($ext)
{
	$ext = strtolower($ext);
	$img = array('image/gif', 'image/jpeg', 'image/jpg', 'image/pjpeg', 'image/x-png', 'image/png', 'image/JPG', 'image/webp');
	$img_ext = array('gif', 'jpeg', 'jpg', 'JPG', 'PNG', 'png', 'x-png', 'pjpeg', 'webp');
	if (in_array($_FILES["file"]["type"], $img) && in_array($ext, $img_ext)) {
		return true;
	}
}
function encodeFile($ext)
{
	global $data;
	$file_name = md5(microtime());
	$file_name = substr($file_name, 0, 12);
	return 'user' . $data['user_id'] . '_' . $file_name . "." . $ext;
}
function encodeFileTumb($ext, $user)
{
	global $data;
	$file_name = md5(microtime());
	$file_name = substr($file_name, 0, 12);
	$fname['full'] = 'user' . $user['user_id'] . '_' . $file_name . '.' . $ext;
	$fname['tumb'] = 'user' . $user['user_id'] . '_' . $file_name . '_tumb.' . $ext;
	return $fname;
}
function waliMoveFile($source)
{
	move_uploaded_file(preg_replace('/\s+/', '', $_FILES["file"]["tmp_name"]), WALI_PATH . '/' . $source);
}
function filterOrigin($origin){
	if(strlen($origin) > 55){
		$origin = mb_substr($origin, 0, 55);
	}
	return str_replace(array(' ', '.', '-'), '_', $origin);
}

function imageTumb($source, $path, $type, $size)
{
	$dst = '';
	switch ($type) {
		case 'image/png':
			$src = @imagecreatefrompng(WALI_PATH . '/' .$source);
			break;
		case 'image/jpeg':
			$src = @imagecreatefromjpeg(WALI_PATH . '/' . $source);
			break;
		default:
			return false;
			break;
	}
	$width = imagesx($src);
	$height = imagesy($src);
	$new_width = floor($width * ($size / $height));
	$new_height = $size;
	if ($height > $size) {
		$dst = @imagecreatetruecolor($new_width, $new_height);
		if ($type == 'image/png') {
			@imagecolortransparent($dst, imagecolorallocate($dst, 0, 0, 0));
			@imagealphablending($dst, false);
			@imagesavealpha($dst, true);
		}
		@imagecopyresized($dst, $src, 0, 0, 0, 0, $new_width, $new_height, $width, $height);
		switch ($type) {
			case 'image/png':
				@imagepng($dst, WALI_PATH . '/' . $path);
				break;
			case 'image/jpeg':
				@imagejpeg($dst, WALI_PATH . '/' . $path);
				break;
			default:
				return false;
				break;
		}
	}
	if ($dst != '') {
		@imagedestroy($dst);
	}
	if ($src) {
		@imagedestroy($src);
	}
}

function validImageData($source)
{
	$i = getimagesize(WALI_PATH . '/' . $source);
	if ($i !== false) {
		return true;
	}
}
function sourceExist($source)
{
	if (file_exists(WALI_PATH . '/' . $source)) {
		return true;
	}
}
function tumbLinking($img, $tumb)
{
	return '<a href="' . $img . '" class="fancybox"><img class="chat_image"src="' . $tumb . '"/></a> ';
}
function findFriend($user)
{
	global $mysqli, $lang;
	$friend_list = '';
	$find_friend = $mysqli->query("SELECT wali_users.user_name, wali_users.user_id, wali_users.user_avatar, wali_users.user_color, wali_users.last_action, wali_users.user_status, wali_users.user_rank, wali_friends.* FROM wali_users, wali_friends 
	WHERE hunter = '{$user['user_id']}' AND fstatus = '3' AND target = wali_users.user_id ORDER BY last_action DESC, user_name ASC LIMIT 36");
	if ($find_friend->num_rows > 0) {
		while ($find = $find_friend->fetch_assoc()) {
			if (isVisible($find)) {
				$friend_list .= waliTemplate('element/user_square', $find);
			}
		}
	}
	if ($friend_list == '') {
		$friend_list = emptyZone($lang['no_data']);
	}
	return $friend_list;
}
function longDateTime($date)
{
	return date("Y-m-d G:i ", $date);
}
function waliUserTheme($user)
{
	global $data;
	if ($user['user_theme'] == 'system') {
		return $data['default_theme'];
	} else {
		return $user['user_theme'];
	}
}
function canViewTimezone($user)
{
	global $data, $wali;
	if (canEditUser($user, $wali['can_view_timezone'], 1)) {
		return true;
	}
}
function canViewEmail($user)
{
	global $data, $wali;
	if (userHaveEmail($user) && canEditUser($user, $wali['can_view_email'], 1)) {
		return true;
	}
}
function canViewId($user)
{
	global $data, $wali;
	if (canEditUser($user, $wali['can_view_id'], 1)) {
		return true;
	}
}
function userTime($user)
{
	$d = new DateTime(date("d F Y H:i:s", time()));
	$d->setTimezone(new DateTimeZone($user['user_timezone']));
	$r = $d->format('G:i');
	return $r;
}
function userHaveEmail($user)
{
	if (isEmail($user['user_email'])) {
		return true;
	}
}
function canViewIp($user)
{
	global $data, $wali;
	if (canEditUser($user, $wali['can_view_ip'], 1)) {
		return true;
	}
}
function listThisArray($a)
{
	return implode(", ", $a);
}
function arrayThisList($l)
{
	return explode(',', $l);
}
function sameAccount($u)
{
	global $mysqli, $lang;
	$getsame = $mysqli->query("SELECT user_name FROM wali_users WHERE user_ip = '{$u['user_ip']}' AND user_id != '{$u['user_id']}' AND user_bot = 0 ORDER BY user_id DESC LIMIT 50");
	$same = array();
	if ($getsame->num_rows > 0) {
		while ($usame = $getsame->fetch_assoc()) {
			array_push($same, $usame['user_name']);
		}
	} else {
		array_push($same, $lang['none']);
	}
	return listThisArray($same);
}
function waliPostIt($user, $content, $type = 1)
{
	$content = systemReplace($content);
	if (userCanDirect($user)) {
		$content = postLinking($content);
	} else {
		$content = linkingReg($content);
	}
	if ($type == 1) {
		return nl2br($content);
	} else {
		return $content;
	}
}
function stripLinking($t)
{
	$list = arrayThisList(
		'www.,https://,http://,.com,.org,.net,.us,.co,.biz,.info,.mobi,.name,.ly,
	.tel,.email,.tech,.xyz,.codes,.bid,.expert,.ca,.cn,.fr,.ch,.au,.in,.de,
	.jp,.nl,.uk,.mx,.no,.ru,.br,.se,.es,.bg,.png,.xpng,.jpeg,.jpg,.gif,.webp,
	.mp3,.mp4,.html,.php,.xml,.zip,.rar,.pdf,.txt
	'
	);
	$t = str_ireplace($list, '', $t);
	return $t;
}
function linkingReg($content)
{
	global $wali;
	if ($wali['strip_direct'] > 0) {
		$content = stripLinking($content);
	}
	return $content;
}
function postLinking($content, $n = 2)
{
	if (!badWord($content)) {
		$source = $content;
		$regex = '\w/_\.\%\+#\-\?:\=\&\;\(\)';
		if (normalise($content, $n)) {
			$content = str_replace('youtu.be/', 'youtube.com/watch?v=', $content);
			$content = preg_replace('@https?:\/\/(www\.)?youtube.com/watch\?v=([\w_-]*)([' . $regex . ']*)?@ui', '<div class="video_container"><iframe src="https://www.youtube.com/embed/$2" frameborder="0" allowfullscreen></iframe></div>', $content);
			$content = preg_replace('@https?:\/\/([-\w\.]+[-\w])+(:\d+)?\/[' . $regex . ']+\.(png|gif|jpg|jpeg|webp)((\?\S+)?[^\.\s])?@i', '<div class="post_image"> <a href="$0" class="fancybox"><img src="$0"/></a> </div>', $content);
			if (preg_last_error()) {
				$content = $source;
			}
			$content = preg_replace('@([^=][^"])(https?://([-\w\.]+[-\w])+(:\d+)?(/([' . $regex . ']*(\?\S+)?[^\.\s])?)?)@ui', '$1<a href="$2" target="_blank">$2</a>', $content);
			$content = preg_replace('@^(https?://([-\w\.]+[-\w])+(:\d+)?(/([' . $regex . ']*(\?\S+)?[^\.\s])?)?)@ui', '<a href="$1" target="_blank">$1</a>', $content);
		}
	}
	return $content;
}

function waliPostFile($content)
{
	global $data;
	if ($content == '') {
		return '';
	}
	$content = $data['domain'] . $content;
	return '<div class="post_image"><a href="' . $content . '" class="fancybox"><img src="' . $content . '"/></a></div>';
}
function waliPostNewsFile($content)
{
	global $mysqli, $data;
	$nwf = '';
	if ($content == '') {
		return '';
	}
	$news_file = $mysqli->query("SELECT * FROM `wali_upload` WHERE file_key = '$content'");
	if ($news_file->num_rows > 0) {
		$nwf = $news_file->fetch_assoc();
	}

	// $file = $data['domain'] . $nwf['file_name'];
	return '<div class="post_image"><a href="upload/news/' . $nwf['file_name'] . '" class="fancybox"><img src="upload/news/' . $nwf['file_name'] . '"/></a></div>';
}
function checkMod($id)
{
	global $data, $mysqli;
	$checkmod = $mysqli->query("SELECT * FROM wali_room_staff WHERE room_id = '{$data['user_roomid']}' AND room_staff = '$id'");
	if ($checkmod->num_rows < 1) {
		return true;
	}
}
function optionMinutes($sel, $list = array())
{
	$val = '';
	foreach ($list as $n) {
		$val .= '<option value="' . $n . '" ' . selCurrent($sel, $n) . '>' . waliRenderMinutes($n) . '</option>';
	}
	return $val;
}
function waliRenderMinutes($val)
{
	global $lang;
	$day = '';
	$hour = '';
	$minute = '';
	$d = floor($val / 1440);
	$h = floor(($val - $d * 1440) / 60);
	$m = $val - ($d * 1440) - ($h * 60);
	if ($d > 0) {
		if ($d > 1) {
			$day = $d . ' ' . $lang['days'];
		} else {
			$day = $d . ' ' . $lang['day'];
		}
	}
	if ($h > 0) {
		if ($h > 1) {
			$hour = $h . ' ' . $lang['hours'];
		} else {
			$hour = $h . ' ' . $lang['hour'];
		}
	}
	if ($m > 0) {
		if ($m > 1) {
			$minute = $m . ' ' . $lang['minutes'];
		} else {
			$minute = $m . ' ' . $lang['minute'];
		}
	}
	return trim($day . ' ' . $hour  . ' ' . $minute);
}
function waliRenderSeconds($val)
{
	global $lang;
	$day = '';
	$hour = '';
	$minute = '';
	$second = '';
	$d = floor($val / 86400);
	$h = floor(($val - $d * 86400) / 3600);
	$m = floor(($val - ($d * 86400) - ($h * 3600)) / 60);
	$s = $val - ($d * 86400) - ($h * 3600) - ($m * 60);
	if ($d > 0) {
		if ($d > 1) {
			$day = $d . ' ' . $lang['days'];
		} else {
			$day = $d . ' ' . $lang['day'];
		}
	}
	if ($h > 0) {
		if ($h > 1) {
			$hour = $h . ' ' . $lang['hours'];
		} else {
			$hour = $h . ' ' . $lang['hour'];
		}
	}
	if ($m > 0) {
		if ($m > 1) {
			$minute = $m . ' ' . $lang['minutes'];
		} else {
			$minute = $m . ' ' . $lang['minute'];
		}
	}
	if ($s > 0) {
		if ($s > 1) {
			$second = $s . ' ' . $lang['seconds'];
		} else {
			$second = $s . ' ' . $lang['second'];
		}
	}
	return trim($day . ' ' . $hour  . ' ' . $minute . ' ' . $second);
}
function waliTimeLeft($t)
{
	return waliRenderMinutes(floor(($t - time()) / 60));
}
?>