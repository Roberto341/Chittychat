<?php 
require("config.php");

if(isset($_POST['username']) && isset($_POST['password']) && isset($_POST['email']) && isset($_POST['gender']) && isset($_POST['age'])){
    $user_name = validate($_POST['username']);
    $user_pass = validate($_POST['password']);
    $user_email = validate($_POST['email']);
    $user_gender = validate($_POST['gender']);
    $user_age = validate($_POST['age']);
    
    $user_ip = getIp();
    /*
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
        }*/
        if(empty($user_name) || empty($user_email)){
            echo 2;
            die();
        }
        if(empty($user_pass)){
            echo 3;
            die();
        }
        if(isTooLong($user_name, $data['max_username']) || !validName($user_name)){
            echo 4;
            die();
        }
        $check_duplicate_name = $mysqli->query("SELECT user_name FROM wali_users WHERE user_name = '$user_name'");
        if($check_duplicate_name->num_rows > 0){
            echo 5;
            die();
        }
        if(!validEmail($user_email)){
            echo 6;
            die();
        }
        $check_duplicate_email = $mysqli->query("SELECT user_email FROM wali_users WHERE user_email = '$user_email'");
        if($check_duplicate_email->num_rows > 0){
            echo 10;
            die();
        }
        if(!validAge($user_age)){
            echo 13;
            die();
        }
        if(!waliValidPassword($user_pass)){
            echo 17;
            die();
        }
        // if($wali['max_reg']){
        //     echo 0;
        //     die();                   /// Implement this later
        // }
        $mysqli->query("INSERT INTO wali_users (user_name, user_password, user_email, user_sex, user_age, user_ip, user_join) VALUES ('$user_name', '$user_pass', '$user_email', '$user_gender', '$user_age', '$user_ip', '". time() ."')");
        $last_id = $mysqli->insert_id;

        $reg = $mysqli->query("SELECT * FROM wali_users WHERE user_name = '$user_name' AND user_password = '$user_pass'");
        if($reg->num_rows > 0){
            setcookie('username', $user_name, time() + 259200, '/');
            setWaliLang($data['language']);
            setWaliCookie($last_id, $user_pass);
            echo 1;
            die();
        }else{
            echo 14;
            die();
        }
}
?>