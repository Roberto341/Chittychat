<div onclick="newsLike(<?php echo $walie['like_post']; ?>, 1);" class="like_count <?php echo $walie['liked']; ?>">
    <img class="like_icon" src="<?php echo $data['domain']; ?>default_images/reaction/like.svg" /> <?php echo $walie['like_count']; ?>
</div>
<div onclick="newsLike(<?php echo $walie['like_post']; ?>, 2);" class="like_count <?php echo $walie['disliked']; ?>">
    <img class="like_icon" src="<?php echo $data['domain']; ?>default_images/reaction/dislike.svg" /> <?php echo $walie['dislike_count']; ?>
</div>
<div onclick="newsLike(<?php echo $walie['like_post']; ?>, 3);" class="like_count <?php echo $walie['loved']; ?>">
    <img class="like_icon" src="<?php echo $data['domain']; ?>default_images/reaction/love.svg" /> <?php echo $walie['love_count']; ?>
</div>
<div onclick="newsLike(<?php echo $walie['like_post']; ?>, 4);" class="like_count <?php echo $walie['funned']; ?>">
    <img class="like_icon" src="<?php echo $data['domain']; ?>default_images/reaction/funny.svg" /> <?php echo $walie['fun_count']; ?>
</div>