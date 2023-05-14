<?php
    // session_start();
    include("../system/db_conn.php");
    // include "../system/function_2.php";
    if(isset($_COOKIE['id']) && isset($_COOKIE['username']) && $_COOKIE['rank'] >= 5){
    $sql = "SELECT * FROM users WHERE user_rank < 5";// WHERE user_name='$_SESSION[user_name]'";
    $result = mysqli_query($conn, $sql);
    if(mysqli_num_rows($result) > 0){
        while($row = mysqli_fetch_assoc($result)){
            $user[] = $row["user_name"];
            $rank[] = $row["rank"];
        }
    }
    if(isset($_POST['kickBtn'])){
        setcookie('kicked', '1', time() + (60 * 15));
        $info = $_POST['k_users'];
        $sql = "UPDATE users SET kicked=1 WHERE user_name='$info'";
        
        $result = mysqli_query($conn, $sql);
        if($result){
        header("Location: home.php");
        }
    } 
    if(isset($_POST['homeBtn'])){
        header("Location: ../index.php");
    }function getRank($data){
        switch($data){
            case 0:
                return 'Guest';
            case 1:
                return 'User';
            case 2:
                return 'Vip';
            case 3:
                return 'Moderator';
            case 4:
                return 'Admin';
            case 5:
                return 'Super admin';
            default:
                return 'User';
        }
    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/admin.css">
    <title>Admin</title>
</head>
<body>
<div class="admin_panel">
        <div class="box">
    <form action="admin.php" method="post">
    <label>Ban</label>
    <select name="b_users" id="ban_users">
        <?php foreach($user as $val){       
                    echo "<option name='$val'>$val</option>";
            }
        ?>
        </select>
        <button type="submit" name="banBtn">Ban</button>
        <label>Kick</label>
        <select name="k_users" id="kick_users">
        <?php foreach($user as $val){       
                    echo "<option name='$val'>$val</option>";
            }
        ?>
        </select>
        <button type="submit" name="kickBtn">Kick</button>
            <div class="info">
                <label>Username color</label>
                <div class="data">
                    <?php echo getRank($data['username_color'])?>
                </div>
            </div>
        <button type="submit" name="homeBtn">Home</button>
    </form>
    </div>
</div>
</body>
</html>

<?php
    }else{
        echo "Your not supposed to be here";
    }
?>