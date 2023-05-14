<?php
    $conn = mysqli_connect('localhost', 'root', '', 'chat');

    $sql = "SELECT * FROM users";
    $result = mysqli_query($conn, $sql);
    if(mysqli_num_rows($result) > 0){
        while($row = mysqli_fetch_assoc($result)){
            $people[] = $row['user_name'];
        }
    }

    $q = $_REQUEST['q'];

    $user = '';

    if($q !== ""){
        $q = strtolower($q);
        $len = strlen($q);

        foreach($people as $person){
            if(stristr($q, substr($person, 0, $len))){
                if($user === ""){
                    $user = $person;
                }else{
                    $user .= "<br> $person";
                }
            }
        }
    }

    echo $user === "" ? "User not found" : $user;
?>