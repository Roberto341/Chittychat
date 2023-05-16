<?php
require('../config_session.php');

if(!isset($_POST['get_profile'], $_POST['cp'])){
	die();
}
$id = escape($_POST['get_profile']);
$curpage = escape($_POST['cp']);
$user = waliUserInfo($id);
if(empty($user)){
	echo 2;
	die();
}
$user['page'] = $curpage;
$pro_menu = waliTemplate('element/pro_menu', $user);
$room = roomInfo($user['user_roomid']);
?>
<div class="modal_wrap_top modal_top profile_background cover_size" <?php echo getCover($user); ?>>
<div class="brow">
		<div class="bcell">
			<div class="modal_top_menu">
				<div class="bcell_mid hpad15">
				</div>
				<?php if(canEditUser($user, 8)){ ?>
				<div onclick="editUser(<?php echo $user['user_id']; ?>);" class="cover_text modal_top_item">
					<i class="fa fa-edit"></i>
				</div>
				<?php } ?>
				<?php if(canEditUser($user, 8, 1)){ ?>
				<div onclick="getActions(<?php echo $user['user_id']; ?>);" class="cover_text modal_top_item">
					<i class="fa fa-flash"></i>
				</div>
				<?php } ?>
				<?php if(!mySelf($user['user_id']) && !empty($pro_menu)){ ?>
				<div id="promenu" onclick="loadProMenu(<?php echo $user['user_id']; ?>, 'pro_menu');" class="cover_text modal_top_item">
					<i class="fa fa-bars"></i>
					<div id="pro_menu" class="add_shadow fmenu">
						<?php echo $pro_menu; ?>
					</div>
				</div>
				<?php } ?>
				<div class="modal_top_menu_empty">
				</div>
				<div class="cancel_modal cover_text modal_top_item">
					<i class="fa fa-times"></i>
				</div>
			</div>
		</div>
	</div>
	<div class="brow">
		<div class="bcell_bottom profile_top">
			<div class="btable_auto">
				<div id="proav" class="profile_avatar" data="<?php echo $user['user_avatar']; ?>">
				<div class="avatar_spin">
					<img class="fancybox avatar_profile" <?php echo profileAvatar($user['user_avatar']); ?> />
				</div>
					<?php echo userActive($user, 'state_profile'); ?>
				</div>
					<div class="profile_tinfo cover_text">
						<div class="pdetails">
							<div class="pdetails_text pro_rank">
 								<?php echo userIcon($user, 'list_rank');?>
							</div>
						</div>
						<div class="pdetails">
							<div class="pdetails_text pro_name">
 								<?php echo getUsername($user);?>
							</div>
						</div>
					</div>
					</div>
					</div>
				</div>
			</div>
	</div>
	<div class="modal_menu">
	<ul>
		<li class="modal_menu_item modal_selected" data="mprofilemenu" data-z="personal_info">About me</li>
		<li class="modal_menu_item" data="mprofilemenu" data-z="profile_friends">Friends</li>
		<li class="modal_menu_item" data="mprofilemenu" data-z="prodetails">Info</li>
	</ul>
</div>
<div id="mprofilemenu">
	<div class="modal_zone pad25 tpad15" id="profile_info">
		<div class="clearbox">
			<div class="listing_half_element info_pro">
				<div class="listing_title"><?php echo 'Age'?></div>
				<div class="listing_text"><?php echo getUserAge($user['user_age']);?></div>
			</div>
			<div class="listing_half_element info_pro">
				<div class="listing_title"><?php echo 'Gender'?></div>
				<div class="listing_text"><?php echo getGender($user['user_sex']);?></div>
			</div>
			<div class="listing_half_element info_pro">
				<div class="listing_title"><?php echo 'Country'?></div>
				<div class="listing_text"><?php echo getCountry($user['country']);?></div>
			</div>
			<div class="listing_half_element info_pro">
				<div class="listing_title"><?php echo $lang['join_chat']?></div>
				<div class="listing_text"><?php echo longDate($user['user_join']);?></div>
			</div>
			<?php if(userInRoom($user) && !empty($room)){ ?>
			<div class="listing_half_element info_pro">
				<div class="listing_title"><?php echo $lang['user_room']?></div>
				<div class="listing_text"><?php echo $room['room_name']?></div>
			</div>
			<?php } ?>
		</div>
	</div>
</div>
<!-- echo getCover($user); ?> -->
