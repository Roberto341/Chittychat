<?php
require('../config.php');
if(isset($_POST['update_status'])){
	$status = escape($_POST['update_status']);
	if(!validStatus($status)){
		$status = 1;
	}
	$mysqli->query("UPDATE wali_users SET user_status = '$status' WHERE user_id = '{$data['user_id']}'");
	echo waliCode(1, array('text'=> statusTitle($status), 'icon'=> newStatusIcon($status)));
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
	$mysqli->query("UPDATE wali_users SET user_name = '$new_name' WHERE user_id = '{$data['user_id']}'");
	waliConsole('change_name', array('custom'=>$data['user_name']));
	changeNameLog($data, $new_name);
	echo 1;
	die();
}

if(isset($_POST['set_user_theme'])){
	$t = escape($_POST['set_user_theme']);
	$mysqli->query("UPDATE wali_users SET user_theme = '$t' WHERE user_id = '{$data['user_id']}'");
	echo waliCode(1, array("theme"=>$t));
	die();
}
if(isset($_POST['save_info'], $_POST['age'], $_POST['gender'])){
	$age = escape($_POST['age']);
	$gender = escape($_POST['gender']);
	if(!validGender($gender) || !validAge($age)){
		echo waliCode(0);
		die();
	}
	$data['user_sex'] = $gender;
	if(defaultAvatar($data['user_avatar'])){
		$avatar = myAvatar(resetAvatar($data));
	}else{
		$avatar = myAvatar($data['user_avatar']);
	}
	$mysqli->query("UPDATE wali_users SET user_age = '$age', user_sex = '$gender' WHERE user_id = '{$data['user_id']}'");
	echo waliCode(1, array('av'=> $avatar));
	die();
}
if(isset($_POST['save_about'], $_POST['about'])){
	$about = clearBreak($_POST['about']);
	$about = escape($about);
	if(isTooLong($about, 900)){
		echo 0;
		die();
	}
	if(isBadText($about)){
		echo 2;
		die();
	}
	$mysqli->query("UPDATE wali_users SET user_about = '$about' WHERE user_id = '{$data['user_id']}'");
	echo 1;
	die();
}
if(isset($_POST['my_username_color'])){
	$color = escape($_POST['my_username_color']);
	$font = escape($_POST['my_username_font']);
	if(!validNameColor($color)){
		echo 0;
		die();
	}
	if(!validNameFont($font)){
		echo 0;
		die();
	}
	$mysqli->query("UPDATE wali_users SET user_color='$color', user_font='$font' WHERE user_id='{$data['user_id']}'");
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