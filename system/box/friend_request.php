<?php
require_once("../config_session.php");

if(!waliAllow(1)){
    die();
}
$flist = '';
$find_friend = $mysqli->query("SELECT 
wali_users.user_name,
wali_users.user_id,
wali_users.user_avatar,
wali_users.user_color, wali_friends.*
FROM wali_users, wali_friends
WHERE hunter = '{$data['user_id']}' AND fstatus = '1' AND target = wali_users.user_id ORDER BY user_status ASC, user_name ASC
");
$mysqli->query("UPDATE wali_friends SET viewed = '1' WHERE target = '{$data['user_id']}'");
if($find_friend->num_rows > 0){
    while($friend = $find_friend->fetch_assoc()){
        $flist .= waliTemplate('element/friend', $friend);
    }
}
else{
    $flist .= emptyZone($lang['no_friend_request']);
}
?>
<div class="ulist_container">
    <?php echo $flist; ?>
</div>