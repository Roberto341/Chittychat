<?php 
    require("config.php");
    
     if(isset($_POST['username'], $_POST['password'])){
         $uname = validate($_POST['username']);
         $pass = validate($_POST['password']);
         
         $sql = "SELECT * FROM users WHERE user_name = '$uname' AND user_password = '$pass'";
         $result = mysqli_query($conn, $sql);
         if(mysqli_num_rows($result) === 1){
             $row = mysqli_fetch_assoc($result);
             if($row['user_name'] === $uname && $row['user_password'] === $pass){
                 setcookie('username', $uname, time() + 259200, '/');
                 setcookie('rank', $row['user_rank'], time() + 259200, '/');
                 setcookie('room', $row['user_roomid'], time() + 259200, '/');
                 $sql2 = "UPDATE users SET user_roomid='$_COOKIE[room]' AND loggedIn='1' WHERE user_name = '$uname'";
                 $result2 = mysqli_query($conn, $sql2);
                 if($result2){
                     echo boomCode(3, array("custom"=>$row['user_roomid']));
                     setBoomCookie($row['user_id'], $row['user_password']);
                    //  header("Location: ../index.php");
                 }
                 exit();
                }
                else{
                    echo boomCode(2);
                    exit();
                }
            }
        }
        else{
            echo boomCode(1);
            exit();
        }
?>