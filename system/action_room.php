<?php 
require('config_session.php');
if(isset($_POST['set_room_name'], $_POST['set_room_name'], $_POST['set_room_desciption'], $_POST['set_room_password'], $_POST['save_room'])){
    if(!canEditRoom()){
        die();
    }
    $player_check = 0;
    $name = escape($_POST['set_room_name']);
    $description = escape($_POST['set_room_description']);
    $password = escape($_POST['set_room_password']);
    if(isset($_POST['set_room_player'])){
        $player = escape($_POST['set_room_player']);
        $player_check = 1;
    }
    $get_room = $mysqli->query("SELECT * FROM wali_rooms WHERE room_id = '{$data['user_roomid']}'");
    $room = $get_room->fetch_assoc();

    if(roomExist($name, $data['user_roomid'])){
        echo 2;
        die();
    }
    if(isTooLong($description, $wali['max_description'])){
        echo 0;
        die();
    }
    if($name == '' || checkName($name) || strlen($name) > $wali['max_room_name']){
        echo 4;
        die();
    }
    if($data['user_roomid'] == 1){
        $password = '';
    }
    if($player_check == 1){
        if($player != 0){
            if($player != $room['room_player_id']){
                $check_player = $mysqli->query("SELECT * FROM wali_radio_stream WHERE id = '$player'");
                if($check_player->num_rows > 0){
                    $setplay = $check_player->fetch_assoc();
                    $player_id = $setplay['id'];
                }
                else{
                    $player_id = $room['room_player_id'];
                }
            }  
            else{
                $player_id = $room['room_player_id'];
            } 
        }
        else{
            $player_id = 0;
        }
    }
    else{
        $player_id = 0;
    }
    $mysqli->query("UPDATE wali_rooms SET room_name = '$name', description = '$description', password = '$password', room_player_id = '$player_id' WHERE room_id = '{$data['user_roomid']}'");
    echo 1;
    die();
}
if(isset($_POST['room'], $_POST['get_in_room'])){
    $target = escape($_POST['room']);
    $muted = 0;
    $blocked = 0;
    $role = 0;
    $data['user_role'] = 0;

    $check_room = $mysqli->query("SELECT *, 
    (SELECT count(id) FROM wali_room_action WHERE action_room = '$target' AND action_user = '{$data['user_id']}' AND action_muted = '1') as is_muted,
    (SELECT count(id) FROM wali_room_action WHERE action_room = '$target' AND action_user = '{$data['user_id']}' AND action_blocked = '1') as is_blocked,
    (SELECT room_rank FROM wali_room_staff WHERE room_staff = '{$data['user_id']}' AND room_id = '$target') as room_status
    FROM wali_rooms
    WHERE room_id = '$target'");
        if($check_room->num_rows > 0){
            $room = $check_room->fetch_assoc();
            if($room['is_muted'] > 0){
                $muted = 1;
            }
            if($room['is_blocked'] == 1){
                echo waliCode(99);
                die();
            }
            if(!is_null($room['room_status'])){
                $role = $room['room_status'];
                $data['user_role'] = $room['room_status'];
            }
            if(waliAllow($room['access'])){
                if($room['password'] != ''){
                    if(isset($_POST['pass'])){
                        $pass = escape($_POST['pass']);
                        if($pass == $room['password'] || canRoomPassword()){
                            $mysqli->query("UPDATE wali_users SET join_msg = 0, user_roomid = '$target',
                            last_action = '" . time() . "', user_role = '$role', room_mute = '$muted' WHERE user_id = '{$data['user_id']}'");
                            $mysqli->query("UPDATE wali_rooms SET room_action = '" . time() . "' WHERE room_id = '$target'");
                            // leaveRoom();
                            echo waliCode(10, array('name'=> $room['room_name'], 'id'=> $room['room_id']));
                            die();
                        }
                        else{
                            echo waliCode(5);
                            die();
                        }
                    }
                    else{
                        echo waliCode(4);
                        die();
                    }
                }
                else{
                    $mysqli->query("UPDATE wali_users SET join_msg = 0, user_roomid = '$target', last_action = '" . time() . "', user_role = '$role', room_mute = '$muted' WHERE user_id = '{$data['user_id']}'");
                    $mysqli->query("UPDATE wali_rooms SET room_action = '" . time() . "' WHERE room_id = '$target'");
                    // leaveRoom();
                    echo waliCode(10, array('name'=> $room['room_name'], 'id'=> $room['room_id']));
                    die();
                }
            }
            else{
                echo waliCode(2);
                die();
            }
        }
        else{
            echo waliCode(1);
            die();
        }
}
if(isset($_POST['set_name']) && isset($_POST['set_pass']) && isset($_POST['set_type']) && isset($_POST['set_description'])){
    $set_pass = escape($_POST["set_pass"]);
    $set_type = escape($_POST["set_type"]);
    $set_name = escape($_POST['set_name']);
    $set_descripion = escape($_POST['set_description']);
    if(!canRoom() || !roomType($set_type)){
        echo waliCode(0);
        die();
    }
    $room_system = 0;
    if(waliAllow(6)){
        $room_system = 1;
    }
    if(!validRoomName($set_name)){
        echo waliCode(2);
        die();
    }
    if(isTooLong($set_descripion, $wali['max_description'])){
        echo 1; 
        die();
    }
    $max_room = $mysqli->query("SELECT room_id FROM wali_rooms WHERE room_creator = '{$data['user_id']}'");
    if($max_room->num_rows >= $wali['max_room'] && !waliAllow(4)){
        echo waliCode(5);
        die();
    }
    $check_duplicate = $mysqli->query("SELECT room_name FROM wali_rooms WHERE room_name = '$set_name'");
    if($check_duplicate->num_rows > 0){
        echo waliCode(6);
        die();
    }
    if(mb_strlen($set_pass) > 20){
        echo waliCode(1);
        die();
    }
    $mysqli->query("INSERT INTO wali_rooms (room_name, access, description, password, room_system, room_creator, room_action) VALUES ('$set_name', '$set_type', '$set_descripion', '$set_pass', '$room_system', '{$data['user_id']}','". time() ."')");
    $last_id = $mysqli->insert_id;

    $mysqli->query("DELETE FROM wali_room_staff WHERE room_id = '$last_id'");

    if(!waliAllow(5)){
        $mysqli->query("UPDATE wali_users SET user_roomid = '$last_id', last_action = '" . time() . "', user_role = '6' WHERE user_id = '{$data['user_id']}'");
        $mysqli->query("INSERT INTO wali_room_staff ( room_id, room_staff, room_rank) VAlUES ('$last_id', '{$data['user_id']}', '6')");
	}
	else {
		$mysqli->query("UPDATE wali_users SET user_roomid = '$last_id', last_action = '" . time() . "' WHERE user_id = '{$data['user_id']}'");
	}
	$groom = roomInfo($last_id);
	waliConsole('create_room', array('room'=>$groom['room_id']));
	echo waliCode(7, array('name'=> $groom['room_name'], 'id'=> $groom['room_id']));
	die();
}
if(isset($_POST['leave_room'])){
	$mysqli->query("UPDATE wali_users SET user_roomid = '0' WHERE user_id = '{$data['user_id']}'");
	echo 1;
	die();
}
if (isset($_POST['target'], $_POST['room_staff_rank'])) {
    if (!canEditRoom()) {
        die();
    }
    $target = escape($_POST['target']);
    $rank = escape($_POST['room_staff_rank']);
    $user = userRoomDetails($target);
    if (empty($target)) {
        echo 2;
        die();
    }
    if (!canRoomAction($user, 6)) {
        echo 0;
        die();
    }
    if ($rank > 0) {
        if (checkMod($user['user_id'])) {
            $mysqli->query("INSERT INTO wali_room_staff ( room_id, room_staff, room_rank) VALUES ('{$data['user_roomid']}', '{$user['user_id']}', '$rank')");
        } else {
            $mysqli->query("UPDATE wali_room_staff SET room_rank = '$rank' WHERE room_id = '{$data['user_roomid']}' AND room_staff = '{$user['user_id']}'");
        }
        $mysqli->query("DELETE FROM wali_room_action WHERE action_user = '{$user['user_id']}' AND action_room = '{$data['user_roomid']}'");
        $mysqli->query("UPDATE wali_users SET user_role = '$rank', room_mute = '0' WHERE user_id = '{$user['user_id']}' AND user_roomid = '{$data['user_roomid']}'");
    } else {
        $mysqli->query("DELETE FROM wali_room_staff WHERE room_staff = '{$user['user_id']}' AND room_id = '{$data['user_roomid']}'");
        $mysqli->query("UPDATE wali_users SET user_role = 0 WHERE user_id = '{$user['user_id']}' AND user_roomid = '{$data['user_roomid']}'");
    }
    waliConsole('change_room_rank', array('target' => $user['user_id'], 'rank' => $rank));
    echo 1;
    die();
}
?>