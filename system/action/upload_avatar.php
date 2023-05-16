<?php 
include("../config.php");

if(isset($_POST) == true){
    $fileName = basename($_FILES["avatar_upload"]["name"]); // add userid in the future

    // File upload path
    $targetDir = "../../avatar/";
    $targetFilePath = $targetDir . $fileName;

    // Allow certian file formats
    $fileType = pathinfo($targetFilePath, PATHINFO_EXTENSION);
    $allowTypes = array('jpg', 'png', 'jpeg', 'gif', 'pdf');

    if(in_array($fileType, $allowTypes)){
        // Upload to server
        if(move_uploaded_file($_FILES["avatar_upload"]["tmp_name"], $targetFilePath)){
            // Insert into datatbase
            $filePath = trim($targetFilePath, "\.\./");
            $sql = "UPDATE wali_users SET user_avatar='$filePath' WHERE user_id='{$data['user_id']}'";
            $result = mysqli_query($mysqli, $sql);
            if($result){
                echo waliCode(1, array('avatar'=> getFileName($filePath)));
                die();
            }
            // ......

        }
    }
}
?>