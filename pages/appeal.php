<?php
    // session_start();
    include("../db_conn.php");
    function validate($data){
        $data = trim($data);
        $data = stripslashes($data);
        $data = htmlspecialchars($data);
        return $data;
     }
 
     $user = validate($_POST['username']);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/appeal.css">
    <title>Appeal a ban</title>
</head>
<body>
    <form action="appeal.php" method="post">
        <h2>To appeal a ban fill out the fields <span class="req">* Requried</span></h2>
        <div class="userInfo">
            <input type="text" name="username" id="user" placeholder="Username">
            <select name="rank" id="rank">
                <option value="guest">Guest</option>
                <option value="user">User</option>
                <option value="vip">Vip</option>
            </select>
            <button type="submit" name="ra">Request appeal</button>
        </div>
    </form>
</body>
</html>