<?php 
require(__DIR__ . "/config_session.php");

if(isset($_POST['logout_from_system'])){
    unsetWaliCookie();
    setcookie('username', $row['username'], time()-259200, '/');
    setcookie('rank', $row['user_rank'], time()-259200, '/');
    setcookie('room', $row['user_roomid'],time()-259200, '/');
    $mysqli->query("UPDATE `wali_users` SET `user_roomid` = '0', user_role = '0' WHERE `user_id` = '{$data["user_id"]}'");
    // leaveRoom();
	if(isGuest($data)){
		softGuestDelete($data);
	}
    echo 1;
    die();
}

if(isset($_POST['overwrite'])){
    unsetWaliCookie();
    die();
}
?>