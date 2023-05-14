<?php 
require("config.php");

if(isset($_POST['uname'], $_POST['password'], $_POST['email'], $_POST['gender'], $_POST['age'])){
    
    $uname = validate($_POST['uname']);
    $password = validate($_POST['password']);
    $email = validate($_POST['email']);
    $gender = validate($_POST['gender']);
    $age = validate($_POST['age']);

    if(empty($uname)){
        echo boomCode(99);
        die();
    }
    else if(empty($password)){
        echo boomCode(98);
        die();
    }
    else if(empty($email)){
        echo boomCode(97);
        die();
    }
    else if(empty($gender)){
        echo boomCode(96);
        die();
    }
    else if(empty($age)){
        echo boomCode(95);
        die();
    }
    else{
        $mysqli->query("INSERT INTO users(user_name, user_password, user_email, user_sex, user_age) VALUES('$uname', '$pass', '$email', '$gender', '$age')");
        
    }
}
?>