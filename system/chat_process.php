<?php 
// session_start();
require_once("config_session.php");
if (!isset($_POST['content'], $_POST['snum'])){
	die();
}
if(isTooLong($_POST['content'], 300)){
	die();
}
if(muted() || roomMuted()){
	die();
}
if(checkFlood()){
	echo 100;
	die();
}

$snum = escape($_POST['snum']);
$content = escape($_POST['content']);
$content = wordFilter($content, 1); /**Implement later */
// $content = textFilter($content);   /**Implement later */

if(empty($content) && $content !== '0' || !inRoom()){
	echo waliCode(99, array("custom"=> $content));
    die();
}
echo userPostChat($content, array('snum'=> $snum));
?>