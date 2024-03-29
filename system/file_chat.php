<?php
/**
 * Walichat
 * @package Walichat
 * @author www.wali-chat.com
 * @copyright 2023
 * @terms any use of this script without a legal license is prohibited 
 * all the content of walichat is the propriety of Wali-Chat and Cannot be 
 * used for another project.
 */
require_once('config_session.php');

if(!canUploadChat() || muted() || roomMuted()){
    die();
}
if(checkFlood()){
    die();
}
if(isset($_FILES["file"])){
    ini_set('memory_limit', '128M');
    $info = pathinfo($_FILES["file"]["name"]);
    $extension = $info['extension'];
    $origin = escape(filterOrigin($info['filename']) . '.' . $extension);
    if(fileError()){
        echo 1;
        die();
    }
    if(isImage($extension)){
        $imginfo = getimagesize($_FILES["file"]["tmp_name"]);
        if($imginfo !== false){
            $width = $imginfo[0];
            $height = $imginfo[1];
            $type = $imginfo['mime'];

            $fname = encodeFileTumb($extension, $data);
            $file_name = $fname['full'];
            $file_tumb = $fname['tumb'];

            waliMoveFile('upload/chat/' . $file_name);

            $source = 'upload/chat/' . $file_name;
            $tumb = 'upload/chat/' . $file_tumb;
            $img_path = "upload/chat/" . $file_name;
            $tumb_path = "upload/chat/" . $file_tumb;
            
            $create = imageTumb($source, $tumb, $type, 180);
            if(sourceExist($source) && sourceExist($tumb)){
                if(validImageData($tumb)){
                    $myimage = tumbLinking($img_path, $tumb_path);
                    userPostChatFile($myimage, $file_name, 'image', array('file2'=> $file_tumb));
                }
                else{
                    $myimage = linking($img_path);
                    userPostChatFile($myimage, $file_name, 'image');
                }
            }
            else{
                $myimage = linking($img_path);
                userPostChatFile($myimage, $file_name, 'image');
            }
            echo 5;
            die();
        }
        else{
            echo 1;
            die();
        }
    }
}else{
    echo 1;
    die();
}
?>