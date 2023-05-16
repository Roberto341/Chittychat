<?php 
    require("config.php");
    
     if(isset($_POST['username'], $_POST['password'])){
         $uname = validate($_POST['username']);
         $pass = validate($_POST['password']);
         
         $get_login = $mysqli->query("SELECT * FROM wali_users WHERE user_name = '$uname' AND user_password = '$pass'");
         if($get_login->num_rows > 0){
            $ldata = $get_login->fetch_assoc();
            if($ldata['user_name'] === $uname && $ldata['user_password'] === $pass){
                $log = $mysqli->query("UPDATE wali_users SET user_roomid='0' WHERE user_name='$uname'");
                 setcookie('username', $uname, time() + 259200, '/');
                 setcookie('rank', $ldata['user_rank'], time() + 259200, '/');
                 setcookie('room', $ldata['user_roomid'], time() + 259200, '/');
                 setWaliLang($ldata['user_language']);
                 setWaliCookie($ldata['user_id'], $ldata['user_password']);
                 echo waliCode(3);
                 exit();
            }
            else{
                echo waliCode(1);
                exit();
            }
         }
        }
        else{
            echo waliCode(2);
            exit();
        }
?>


