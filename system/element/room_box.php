<?php
require_once("system/config_session.php");
$ask = 0;
$current = '';
$owner = '';
if($walie['password'] != ''){
    $ask = 1;
}
if($walie['room_id'] == $data['user_roomid']){
    $current = 'noview';
}
if($walie['description'] == ''){
    $description = $lang['room_no_description'];
}
else{
    $description = $walie['description'];
} 
if($data['user_id'] == $walie['room_creator']){
    $owner = 'owner ';
}
?>
<div class="room_element add_shadow element_color" onclick="switchRoom(<?php echo $walie['room_id']; ?>, <?php echo $ask; ?>, <?php echo $walie['access']?>);">
    <div class="rtable">
        <div class="bcell">
        </div>
        <div class="room_icon">
            <?php echo roomIcon($walie, 'room_img'); ?>
        </div>
        <div class="bcell">
        </div>
    </div>
    <div class="rtable">
        <div class="bcell">
            <div class="room_name centered_element">
                <?php echo $walie['room_name']; ?>
            </div>
            <div class="room_description sub_text centered_element">
                <?php echo $walie['description'];?>
            </div>
            <div class="room_count centered_element">
                <p class="default_color text_xreg"><?php echo $walie['room_count'];?> <i class="fa fa-users"></i></p>
            </div>
        </div>
    </div>
</div>