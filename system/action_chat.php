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
require("config.php");

if(isset($_POST['del_post'])){
    $id = escape($_POST['del_post']);
    $mysqli->query("DELETE FROM wali_chat WHERE post_id = '$id'");
}
