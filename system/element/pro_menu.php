<?php
$ignore = getIgnore();
?>
<?php if(!mySelf($walie['user_id']) && insideChat($walie['page'])){ ?>
<div data="<?php echo $walie['user_id']; ?>" value="<?php echo $walie['user_name']; ?>" data-av="<?php echo myAvatar($walie['user_avatar']); ?>" class="gprivate fmenu_item">
	<div class="fmenu_icon">
		<i class="fa fa-comments theme_color"></i>
	</div>
	<div class="fmenu_text">
		<?php echo $lang['private']; ?>
	</div>
</div>
<?php } ?>
<?php if(canFriend($walie)&& isMember($data) && isMember($walie)){ ?>
<div onclick="addFriend(<?php echo $walie['user_id']; ?>);" class="fmenu_item">
	<div class="fmenu_icon">
		<i class="fa fa-user-plus success"></i>
	</div>
	<div class="fmenu_text">
		<?php echo $lang['add_friend']; ?>
	</div>
</div>
<?php } ?>
<?php if(!canFriend($walie)&& isMember($data) && isMember($walie)){ ?>
<div onclick="unFriend(<?php echo $walie['user_id']; ?>);" class="fmenu_item">
	<div class="fmenu_icon">
		<i class="fa fa-user-times error"></i>
	</div>
	<div class="fmenu_text">
		<?php echo $lang['unfriend']; ?>
	</div>
</div>
<?php } ?>
<?php if(!isIgnored($ignore, $walie['user_id']) && canIgnore($walie)){ ?>
<div onclick="ignoreUser(<?php echo $walie['user_id']; ?>);" class="fmenu_item">
	<div class="fmenu_icon">
		<i class="fa fa-ban error"></i>
	</div>
	<div class="fmenu_text">
		<?php echo $lang['ignore']; ?>
	</div>
</div>
<?php } ?>
<?php if(isIgnored($ignore, $walie['user_id'])){ ?>
<div onclick="unIgnore(<?php echo $walie['user_id']; ?>);" class="fmenu_item">
	<div class="fmenu_icon">
		<i class="fa fa-check-circle success"></i>
	</div>
	<div class="fmenu_text">
		<?php echo $lang['unignore']; ?>
	</div>
</div>
<?php } ?>
<?php if(!canManageReport() && !mySelf($walie['user_id']) && !isBot($walie) && canReport()){ ?>
<div onclick="openReport(<?php echo $walie['user_id']; ?>, 4);" class="fmenu_item">
	<div class="fmenu_icon">
		<i class="fa fa-flag warn"></i>
	</div>
	<div class="fmenu_text">
		<?php echo $lang['report']; ?>
	</div>
</div>
<?php } ?>