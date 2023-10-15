<?php 
require_once("../config.php");

if(!isset($_POST['add_news'], $_POST['post_file'])){
    die();
}
if(isTooLong($_POST['add_news'], 2000)){
    die();
}

$content = escape($_POST['add_news']);
$file = escape($_POST['post_file']);
if(empty($content) && $content !== '0' || !inRoom()){
    echo waliCode(0, array("custom"=> $content));
    die();
}
echo adminPostNews($content, $file);
// id, new_poster, news_message, news_file, news_date
?>