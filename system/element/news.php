<div id="boom_news<?php echo $walie['id']; ?>" data="<?php echo $walie['id']; ?>" class="news_box post_element">
	<div class="post_title">
		<div class=" post_avatar get_info" data="<?php echo $walie['user_id']; ?>">
			<img src="<?php echo myAvatar($walie['user_tumb']); ?>"/>
		</div>
		<div class="bcell_mid hpad5 maxflow post_info">
			<p class="username text_small <?php echo myColor($walie); ?>"><?php echo $walie['user_name']; ?></p>
			<p class="text_xsmall date"><?php echo displayDate($walie['news_date']); ?></p>
		</div>
		<div onclick="openPostOptions(this);" class="post_edit bcell_mid_center">
			<i class="fa fa-ellipsis-h"></i>
			<div class="post_menu fmenu">
				<div onclick="viewNewsLikes(<?php echo $walie['id']; ?>);" class="fmenu_item fmenut">
					<?php echo $lang['view_likes']; ?>
				</div>
				<?php if(canDeleteNews($walie) || mySelf($walie['news_poster'])){ ?>
				<div onclick="openDeletePost('news', <?php echo $walie['id']; ?>);" class="fmenu_item fmenut">
					<?php echo $lang['delete']; ?>
				</div>
				<?php } ?>
			</div>
		</div>
	</div>
	<div class="post_content">
		<?php echo waliPostIt($walie, $walie['news_message']); ?>
		<?php echo waliPostFile($walie['news_file']); ?>
	</div>
	<div class="post_control btauto">
		<div class="bcell_mid like_container newslike<?php echo $walie['id']; ?>">
			<?php echo getLikes($walie['id'], $walie['liked'], 'news'); ?>
		</div>
		<div data="0" class="bcell_mid comment_count bcauto load_comment <?php if($walie['reply_count'] < 1){ echo 'hidden'; } ?>" onclick="loadNewsComment(this, <?php echo $walie['id']; ?>);">
			<span id="nrepcount<?php echo $walie['id']; ?>"><?php echo $walie['reply_count']; ?></span> <img class="comment_icon" src="<?php echo $data['domain']; ?>/default_images/icons/comment.svg"/>
		</div>
	</div>
	<?php if(!muted() && waliAllow($cody['can_reply_news'])){ ?>
	<div class="add_comment_zone cmb<?php echo $walie['id']; ?>">
		<div class="tpad10 reply_post">
			<form class="news_reply_form" data-id="<?php echo $walie['id']; ?>">
				<input maxlength="500" placeholder="<?php echo $lang['comment_here']; ?>" class="add_comment full_input">
			</form>
		</div>
	</div>
	<?php } ?>
	<div class="tpad10 ncmtboxwrap<?php echo $walie['id']; ?>">
		<div class="ncmtbox ncmtbox<?php echo $walie['id']; ?>">
		</div>
		<div class="nmorebox nmorebox<?php echo $walie['id']; ?>">
		</div>
		<div class="clear"></div>
	</div>
</div>