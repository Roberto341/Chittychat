<?php 
// session_start();
require_once("config.php");
$sql = "SELECT * FROM users WHERE user_name='$_COOKIE[username]'";
$result = mysqli_query($conn, $sql);

if(mysqli_num_rows($result) > 0){
    $row = mysqli_fetch_assoc($result);
setcookie('username', $row['username'], time()-259200, '/');
setcookie('user_id', $row['user_id'], time()-259200, '/');
setcookie('rank', $row['user_rank'], time()-259200, '/');
setcookie('loggedin',$row['loggedIn'],  time() - 259200, '/');
setcookie('room', $row['user_roomid'],time()-259200, '/');

$sql2 = "UPDATE users SET loggedIn='0' AND user_roomid = 1 AND user_role = 0 WHERE user_id='{$data['user_id']}'";
$result2 = mysqli_query($conn, $sql2);
if($result2){
    unsetBoomCookie();
    leaveRoom();
    session_unset();
    session_destroy();
    header("Location: ../index.php");
}

}
?>