<div class="ulist_item">
    <div class="ulist_avatar">
        <img src="<?php echo myAvatar($walie['user_avatar']); ?>" />
    </div>
    <div class="ulist_name">
        <p class="username <?php echo myColor($boom); ?>"><?php echo $walie['user_name']; ?></p>
    </div>
    <div class="ulist_option" onclick="removeFriend(this, <?php echo $walie['user_id']; ?>);">
        <i class="fa fa-times"></i>
    </div>
</div>