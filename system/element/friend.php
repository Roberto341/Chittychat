<div class="ulist_item friend_request">
    <div class="ulist_avatar">
        <img src="<?php echo myAvatar($walie['user_avatar']) ?> " />
    </div>
    <div class="ulist_name">
        <p class="username <?php echo myColor($walie) ?>"><?php echo $walie['user_name']; ?></p>
    </div>
    <div class="ulist_option" onclick="declineFriend(this, <?php echo $walie['user_id'] ?>);">
        <i class="fa fa-times error"></i>
    </div>
    <div class="ulist_option" onclick="acceptFriend(this, <?php echo $walie['user_id'] ?>);">
        <i class="fa fa-check success"></i>
    </div>
</div>