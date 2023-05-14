<?php 
$page_info = array(
	'page'=> 'home',
	'page_nohome'=> 0,
);
require_once("system/config.php");
// setcookie('lang', 'English', time() + 259200, '/');
$chat_room = getRoomId();
if($chat_room > 0){
	$data['user_roomid'] = $chat_room;
	$page_info['page'] = 'chat';
}

// loading head tag
include('control/head_load.php');
// loading page content
if($page['page'] == 'chat'){
	include('control/chat.php');
}else{
	include('control/lobby.php');
}

// close page body
include('control/body_end.php');
?>