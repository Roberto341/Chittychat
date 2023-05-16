<?php 
require("config.php");

if(isset($_POST['username'], $_POST['password'], $_POST['email'], $_POST['gender'], $_POST['age'])){
    
    $uname = validate($_POST['uname']);
    $password = validate($_POST['password']);
    $email = validate($_POST['email']);
    $gender = validate($_POST['gender']);
    $age = validate($_POST['age']);
    
    $start_reg = $mysqli->query("SELECT * FROM wali_users");
    if($start_reg->num_rows > 0){
        $data = $start_reg->fetch_assoc();
        if($data['user_name'] === $uname){
            echo waliCode();
        }
    }
    $mysqli->query("INSERT INTO wali_users(user_name, user_password, user_email, user_sex, user_age) VALUES('$uname', '$pass', '$email', '$gender', '$age')");
    $get_registration = $mysqli->query("SELECT * FROM wali_users WHERE user_name='$uname' AND user_password='$pass'");
    if($get_registration->num_rows > 0) 
        $data = $get_registration->fetch_assoc();
        if($data['user_name'] === $uname && $data['user_password'] === $pass){
                $log = $mysqli->query("UPDATE wali_users SET user_roomid='0' WHERE user_name='$uname'");
                 setcookie('username', $uname, time() + 259200, '/');
                 setcookie('rank', $row['user_rank'], time() + 259200, '/');
                 setcookie('room', $row['user_roomid'], time() + 259200, '/');
                 setWaliLang($data['uesr_language']);
                 setWaliCookie($row['user_id'], $row['user_password']);
                 echo waliCode(1);
                 exit();
        }
        else{
            echo waliCode(3);
            exit();
        }
}
?>