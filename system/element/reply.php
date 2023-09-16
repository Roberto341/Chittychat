<div data="<?php echo $walie['reply_id']; ?>" id="wreply<?php echo $walie['reply_id']; ?>" class="reply_item">
    <div class="reply_avatar get_info" data="<?php echo $walie['user_id']; ?>">
        <img src="<?php echo myAvatar($walie['user_tumb']); ?>" />
    </div>
    <div class="reply_content">
        <div class="btable">
            <div class="bcell_top maxflow">
                <span class="<?php echo myColor($walie); ?> text_small username"><?php echo $walie['user_name']; ?></span> <span class="text_xsmall no_break date"><?php echo displayDate($boom['reply_date']); ?></p>
            </div>
            <?php if (canDeleteWallReply($walie)) { ?>
                <div onclick="openDeletePost('wall_reply', <?php echo $walie['reply_id']; ?>);" class="reply_delete bcell_top">
                    <i class="fa fa-times"></i>
                </div>
            <?php } ?>
        </div>
        <div class="text_small vpad3">
            <?php echo waliPostIt($walie, $walie['reply_content']); ?>
        </div>
    </div>
</div>