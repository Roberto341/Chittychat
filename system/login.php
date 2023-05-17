<?php 
require("config.php");
    
if(isset($_POST['username'], $_POST['password'])){
    $log_username = validate($_POST['username']); 
    $log_pass = validate($_POST['password']);
        
    if(empty($log_username) || empty($log_pass)){
        echo 1;
        die();
    }
    $log_user = $mysqli->query("SELECT * FROM wali_users WHERE user_name = '$log_username' OR user_email = '$log_username' AND user_password = '$log_pass'");
    if($log_user->num_rows > 0){
        $log = $log_user->fetch_assoc();
        setcookie('username', $log['user_name'], time() + 259200, '/');
        setWaliLang($log['user_language']);
        setWaliCookie($log['user_id'], $log['user_password']);
        echo 3;
        die();
    }else{
        echo 2;
        die();
    }
}
?>


