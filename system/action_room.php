<?php 
require('./config.php');
// require_once('./function_2.php');
if(isset($_POST['room'], $_POST['get_in_room'])){
    $target = escape($_POST['room']);
    $muted = 0;
    $blocked = 0;
    $role = 0;
    $data['user_role'] = 0;

    $check_room = $mysqli->query("SELECT *, 
    (SELECT count(id) FROM room_action WHERE action_room = '$target' AND action_user = '{$data['user_id']}' AND action_muted = '1') as is_muted,
    (SELECT count(id) FROM room_action WHERE action_room = '$target' AND action_user = '{$data['user_id']}' AND action_blocked = '1') as is_blocked,
    (SELECT room_rank FROM room_staff WHERE room_staff = '{$data['user_id']}' AND room_id = '$target') as room_status
    FROM rooms
    WHERE room_id = '$target'");
        if($check_room->num_rows > 0){
            $room = $check_room->fetch_assoc();
            if($room['is_muted'] > 0){
                $muted = 1;
            }
            if($room['is_blocked'] == 1){
                echo boomCode(99);
                die();
            }
            if(!is_null($room['room_status'])){
                $role = $room['room_status'];
                $data['user_role'] = $room['room_status'];
            }
            if(boomAllow($room['access'])){
                if($room['password'] != ''){
                    if(isset($_POST['pass'])){
                        $pass = escape($_POST['pass']);
                        if($pass == $room['password'] || canRoomPassword()){
                            $mysqli->query("UPDATE users SET join_msg = 0, user_roomid = '$target',
                            last_action = '" . time() . "', user_role = '$role', room_mute = '$muted' WHERE user_id = '{$data['user_id']}'");
                            $mysqli->query("UPDATE rooms SET room_action = '" . time() . "' WHERE room_id = '$target'");
                            leaveRoom();
                            echo boomCode(10, array('name'=> $room['room_name'], 'id'=> $room['room_id']));
                            die();
                        }
                        else{
                            echo boomCode(5);
                            die();
                        }
                    }
                    else{
                        echo boomCode(4);
                        die();
                    }
                }
                else{
                    $mysqli->query("UPDATE users SET join_msg = 0, user_roomid = '$target', last_action = '" . time() . "', user_role = '$role', room_mute = '$muted' WHERE user_id = '{$data['user_id']}'");
                    $mysqli->query("UPDATE rooms SET room_action = '" . time() . "' WHERE room_id = '$target'");
                    leaveRoom();
                    echo boomCode(10, array('name'=> $room['room_name'], 'id'=> $room['room_id']));
                    die();
                }
            }
            else{
                echo boomCode(2);
                die();
            }
        }
        else{
            echo boomCode(1);
            die();
        }
}

?>