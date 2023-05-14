<?php
require('../config_session.php');
// $usr = $_COOKIE['username'];
?>
<div id="my_profile_top" class="modal_wrap_top modal_top profile_background" <?php echo getCover($data);?>>
	<div class="brow">
		<div class="bcell">
			<div class="modal_top_menu">
				<div class="bcell_mid">
				</div>
				<div class="cover_menu">
					<div class="cover_item_wrap lite_olay">
						<div class="cover_item delete_cover" onclick="deleteCover();">
							<i id="cover_button" class="fa fa-times"></i>
						</div>
						<div class="cover_item add_cover" id="add_cover">
								<i id="cover_icon" data="fa-camera" class="fa fa-camera"></i>
								<input type="file" name="cover_file" id="fileInput" class="up_input" onchange="uploadCover();"/>
						</div>
					</div>
				</div>
				<div class="modal_top_menu_empty">
				</div>
				<div class="cancel_modal modal_top_item cover_text">
					<i class="fa fa-times"></i> 
				</div>
			</div>
		</div>
	</div>
	<div class="brow">
		<div class="bcell_bottom profile_top">
			<div class="btable_auto">
				<div id="proav" class="profile_avatar" data="<?php echo $data['user_avatar']?>">
					<div class="avatar_spin">
						<img class='fancybox avatar_profile' src='<?php echo getAvatar($data['user_avatar'])?>'>
					</div>
					<div class="avatar_control olay">
						<div class="avatar_button" onclick="deleteAvatar();" id="deleteAvatar"><i class="fa fa-times"></i></div>
						<div id="avatarupload" class="avatar_button">
							<i id="avat_icon" data="fa-camera" class="fa fa-camera"></i>
							<input type="file" name="avatar_image" id="avInput" class="up_input" onchange="uploadAvatar();"/>

						</div>
					</div>
				</div>
				<div class="profile_tinfo">
					<div class="pdetails">
						<div id="pro_name" class="pdetails_text pro_name cover_text">
							<?php echo $data['user_name']; ?>
						</div>
					</div>

					<div class="pdetails">
						<div id="pro_mood" class="pdetails_text pro_mood cover_text bellips">

						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
<div class="modal_menu">
	<ul>
		<li class="modal_menu_item modal_selected" data="meditprofile" data-z="personal_more">Account</li>
	</ul>
</div>
<div id="meditprofile">
	<div class="modal_zone pad25" id="personal_more">
		<div class="clearbox">
			<div onclick="changeInfo();" class="listing_half_element">
				<i class="fa fa-address-book listing_icon"></i><?php echo "Edit info" ?>
			</div>
			<div onclick="changeAbout();" class="listing_half_element">
				<i class="fa fa-question-circle listing_icon"></i><?php echo "Edit about me" ?>
			</div>
			<?php if(canUsername()){?>
				<div onclick="changeUsername();" class="listing_half_element">
				<i class="fa fa-edit listing_icon"></i><?php echo "Edit username" ?>
			</div>
			<?php }?>
			<?php if(canUCol()){?>
			<div onclick="changeColor();" class="listing_half_element">
				<i class="fa fa-paint-brush listing_icon"></i><?php echo "Edit username color" ?>
			</div>
			<?php }?>
			<div onclick="changeMood();" class="listing_half_element">
				<i class="fa fa-pencil listing_icon"></i><?php echo "Edit mood" ?>
			</div>
			<div onclick="getFriends();" class="listing_half_element">
				<i class="fa fa-user-plus listing_icon"></i><?php echo "Manage friends" ?>
			</div>
			<div onclick="getIgnore();" class="listing_half_element">
				<i class="fa fa-ban listing_icon"></i><?php echo "Manage ignores" ?>
			</div>
			<div onclick="getSoundSetting();" class="listing_half_element">
				<i class="fa fa-volume-up listing_icon"></i><?php echo "Sound settings" ?>
			</div>
			<?php if(canTheme()){?>
			<div onclick="getDisplaySetting();" class="listing_half_element">
				<i class="fa fa-desktop listing_icon"></i><?php echo "Theme settings" ?>
			</div>
			<?php }?>
			<div onclick="getPrivateSettings();" class="listing_half_element">
				<i class="fa fa-comments listing_icon"></i><?php echo "Private settings" ?>
			</div>
			<div onclick="getLocation();" class="listing_half_element">
				<i class="fa fa-globe listing_icon"></i><?php echo "Language/location" ?>
			</div>
			<div onclick="getEmail();" class="listing_half_element">
				<i class="fa fa-envelope listing_icon"></i><?php echo "Edit email" ?>
			</div>
			<div onclick="getPassword();" class="listing_half_element">
				<i class="fa fa-key listing_icon"></i><?php echo "Change password" ?>
			</div>
		</div>
	</div>
</div>