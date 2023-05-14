<?php 
// session_start(); 
include("system/db_conn.php");

if (isset($_POST['uname']) && isset($_POST['password'])) {

	function validate($data){
       $data = trim($data);
	   $data = stripslashes($data);
	   $data = htmlspecialchars($data);
	   return $data;
	}

	$uname = validate($_POST['uname']);
	$pass = validate($_POST['password']);
    $ema = validate($_POST['email']);
    $gend = validate($_POST['gender']);
    $age = validate($_POST['age']);

    if (empty($uname)) {
		header("Location: index.php?error=User Name is required");
	    exit();
	}else if(empty($pass)){
        header("Location: index.php?error=Password is required");
	    exit();
	}else{
		$sql = "SELECT * FROM users WHERE user_name='$uname'";
        $result = mysqli_query($conn, $sql);

        if(mysqli_num_rows($result) > 0){
            header("Location: index.php?error=Username already taken");
            exit();
        }else{
            $sql = "INSERT INTO users(user_name, password, email, avatar, gender, status, rank,username_color, theme, age, veri_status, user_lang, user_timezone) VALUES('$uname', '$pass', '$ema', 'avatar/avatar.jpg', '$gend', '1', '1', '#333','Light', '$age', '0', 'English', 'America/Montreal')";
            $result = mysqli_query($conn, $sql);
            if($result){
                header("Location: index.php?success=Account created successfully");
                $sql2 = "SELECT * FROM users WHERE user_name='$uname' AND password='$pass'";
                $result2 = mysqli_query($conn, $sql2);
                if(mysqli_num_rows($result2) === 1){
                    $row = mysqli_fetch_assoc($result2);
                    if($row['user_name'] === $uname && $row['password'] === $pass){
                        $_SESSION['user_name'] = $row['user_name'];
				        $_SESSION['id'] = $row['id'];
				        $_SESSION['loggedIn'] = $row['loggedIn'];
				        $_SESSION['rank'] = $row['rank'];
                        $id = $row['id'];
                        $rank = $row['rank'];
				        setcookie('username', $uname, time()+259200);
                        setcookie('rank', $rank, time() + 259200);
                        setcookie('id', $id, time() + 259200);
				        header("Location: home.php");
				    $sql3 = "UPDATE users SET loggedIn='1' WHERE user_name='$uname'";
				    $result3 = mysqli_query($conn, $sql3);
				    $sql4 = "UPDATE users SET ip='$ip' WHERE user_name='$uname'";
				    $result4 = mysqli_query($conn, $sql4);
		        exit();
                    }
                }
                exit();  
            }else{
                header("Location: index.php?error=Unkown error occurred");
                exit(); 
            }
        }
    }
}else{
	header("Location: index.php");
	exit();
}