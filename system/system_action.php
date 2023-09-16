<?php 
require("config_session.php");

if(isset($_POST['add_friend'])){
    $target = escape($_POST['add_friend']);
    if(waliUserInfo($target)){
        $mysqli->query("INSERT INTO wali_friends (hunter, target, fstatus, viewed) VALUES('{$data['user_id']}', '$target', '2', '0')");
        $mysqli->query("INSERT INTO wali_friends (hunter, target, fstatus, viewed) VALUES('$target', '{$data['user_id']}', '1', '0')");
        echo 1;
        die();
    } else{
        echo 3;
        die();
    }
}

if(isset($_POST['accept_friend'])){
    $target = escape($_POST['accept_friend']);
    if(waliUserInfo($target)){
        $mysqli->query("UPDATE wali_friends SET fstatus = '3' WHERE hunter = '{$data['user_id']}' AND target = '$target'");
        $mysqli->query("UPDATE wali_friends SET fstatus = '3' WHERE hunter = '$target' AND target = '{$data['user_id']}'");

        echo 1;
        die();
    } else {
        echo 3;
        die();
    }
}

if(isset($_POST['remove_friend'])){
    
}
?>