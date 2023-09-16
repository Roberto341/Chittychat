<div class="post_element" id="boom_post<?php echo $walie['post_id']; ?>">
	<div class="post_title">
		<div class="post_avatar get_info" data="<?php echo $walie['user_id']; ?>">
			<img src="<?php echo myAvatar($walie['user_avatar']); ?>"/>
		</div>
		<div class="hpad5 post_info maxflow bcell_mid">
			<p class="text_small username <?php echo myColor($walie); ?>"><?php echo $walie['user_name']; ?></p>
			<p class="text_xsmall date"><?php echo displayDate($walie['post_date']); ?></p>
		</div>
		<div onclick="openPostOptions(this);" class="post_edit bcell_mid_center">
			<i class="fa fa-ellipsis-h"></i>
			<div class="post_menu fmenu">
				<div onclick="viewWallLikes(<?php echo $walie['post_id']; ?>);" class="fmenu_item fmenut">
					<?php echo $lang['view_likes']; ?>
				</div>
				<?php if(canDeleteWall($walie)){ ?>
				<div onclick="openDeletePost('wall', <?php echo $walie['post_id']; ?>);" class="fmenu_item fmenut">
					<?php echo $lang['delete']; ?>
				</div>
				<?php } ?>
				<?php if(!canDeleteWall($walie) && !mySelf($walie['user_id'])){ ?>
				<div onclick="reportWallLog(<?php echo $walie['post_id']; ?>, 2);" class="fmenu_item fmenut">
					<?php echo $lang['report']; ?>
				</div>
				<?php } ?>
			</div>
		</div>
	</div>
	<div class="post_content">
		<?php echo waliPostIt($walie, $walie['post_content']); ?>
		<?php echo waliPostFile($waliee['post_file']); ?>
	</div>
	<div class="post_control btauto">
		<div class="bcell_mid like_container like<?php echo $walie['post_id']; ?>">
			<?php echo getLikes($walie['post_id'], $walie['liked'], 'wall'); ?>
		</div>
		<div data="0" class="bcell_mid comment_count bcauto load_comment <?php if($walie['reply_count'] < 1){ echo 'hidden'; } ?>" onclick="loadComment(this, <?php echo $walie['post_id']; ?>);">
			<span id="repcount<?php echo $walie['post_id']; ?>"><?php echo $walie['reply_count']; ?></span> <img class="comment_icon" src="<?php echo $data['domain']; ?>/default_images/icons/comment.svg"/>
		</div>
	</div>
	<?php if(!muted()){ ?>
	<div class="add_comment_zone cmb<?php echo $walie['post_id']; ?>">
		<div class="vpad10 reply_post">
			<form class="friend_reply_form" data-id="<?php echo $walie['post_id']; ?>">
				<input  maxlength="500" placeholder="<?php echo $lang['comment_here']; ?>" class="add_comment full_input">
			</form>
		</div>
	</div>
	<?php } ?>
	<div class="cmtboxwrap<?php echo $walie['post_id']; ?>">
		<div class="cmtbox cmtbox<?php echo $walie['post_id']; ?>">
		</div>
		<div class="morebox morebox<?php echo $walie['post_id']; ?>">
		</div>
		<div class="clear"></div>
	</div>
</div>