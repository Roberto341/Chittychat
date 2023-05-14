<?php
require('../config.php');
if(isset($_POST['update_status'])){
	$status = escape($_POST['update_status']);
	if(!validStatus($status)){
		$status = 1;
	}
	$mysqli->query("UPDATE users SET user_status = '$status' WHERE user_id = '{$data['user_id']}'");
	echo boomCode(1, array('text'=> statusTitle($status), 'icon'=> newStatusIcon($status)));
	die();
}
if(isset($_POST['edit_username'], $_POST['new_name'])){
	$new_name = escape($_POST['new_name']);
	if(!canUsername()){
		die();
	}
	if($new_name == $data['user_name']){
		echo 1;
		die();
	}
	if(!validName($new_name)){
		echo 2;
		die();
	}
	if(!waliSame($new_name, $data['user_name'])){
		if(!waliUsername($new_name)){
			echo 3;
			die();
		}
	}
	$mysqli->query("UPDATE users SET user_name = '$new_name' WHERE user_id = '{$data['user_id']}'");
	changeNameLog($data, $new_name);
	echo 1;
	die();
}

if(isset($_POST['set_user_theme'])){
	$t = escape($_POST['set_user_theme']);
	$mysqli->query("UPDATE users SET user_theme = '$t' WHERE user_id = '{$data['user_id']}'");
	echo boomCode(1, array("theme"=>$t));
	die();
}

if(isset($_POST['my_username_color'])){
	$color = escape($_POST['my_username_color']);
	$font = escape($_POST['my_username_font']);
	$mysqli->query("UPDATE users SET username_color='$color', user_font='$font' WHERE user_id='{$data['uid']}'");
	echo 1;
	die();
}
// if(isset($_POST['text_color'])){
//     $color = escape($_POST['text_color']);
//     $mysqli->query("UPDATE users SET wccolor='$color' WHERE id='{$data['uid']}'");
//     echo 1;
//     die();
// }
?>