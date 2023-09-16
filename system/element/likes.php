<div onclick="likeIt(<?php echo $wali['like_post']; ?>, 1);" class="like_count <?php echo $wali['liked']; ?>">
    <img class="like_icon" src="<?php echo $data['domain']; ?>/default_images/reaction/like.svg" /> <?php echo $wali['like_count']; ?>
</div>
<div onclick="likeIt(<?php echo $wali['like_post']; ?>, 2);" class="like_count <?php echo $wali['disliked']; ?>">
    <img class="like_icon" src="<?php echo $data['domain']; ?>/default_images/reaction/dislike.svg" /> <?php echo $wali['dislike_count']; ?>
</div>
<div onclick="likeIt(<?php echo $wali['like_post']; ?>, 3);" class="like_count <?php echo $wali['loved']; ?>">
    <img class="like_icon" src="<?php echo $data['domain']; ?>/default_images/reaction/love.svg" /> <?php echo $wali['love_count']; ?>
</div>
<div onclick="likeIt(<?php echo $wali['like_post']; ?>, 4);" class="like_count <?php echo $wali['funned']; ?>">
    <img class="like_icon" src="<?php echo $data['domain']; ?>/default_images/reaction/funny.svg" /> <?php echo $wali['fun_count']; ?>
</div>