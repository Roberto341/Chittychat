<div id="login_wrap" class="back_login">
	<div id="header2" class="background_header">
		<div id="wrap_main_header">
			<div id="main_header" class="out_head headers">
				<div class="head_logo">
					<img id="main_logo" alt="logo" src="<?php echo getLogo(); ?>"/>
				</div>
				<div class="bcell_mid login_main_menu">
				</div>
				<div onclick="getLanguage();" class="bclick bcell_mid_center" id="open_login_menu">
					<img alt="flag" class="intro_lang" src="system/language/<?php echo $cur_lang; ?>/flag.png"/>
				</div>
			</div>
		</div>
	</div>
	<div class="empty_subhead">
	</div>
	<div id="intro_top" class="btable">
		<div class="bcell_mid">
			<div id="login_all" class="pad30">
				<div class="login_text bpad15 centered_element">
					<p class="login_title_text bold text_jumbo bpad5"><?php echo $lang['left_title']; ?></p>
					<p class="login_sub_text bold text_med"><?php echo $lang['left_welcome']; ?></p>
				</div>
				<div class="centered_element login_box">
					<?php if(bridgeMode(0)){ ?>
					<button onclick="getLogin();" class="intro_login_btn large_button_rounded  ok_btn"><i class="fa fa-send"></i> <?php echo $lang['login']; ?></button>
					<?php } ?>
					<?php if(allowGuest()){ ?>
					<div class="clear"></div>
					<button onclick="getGuestLogin();" class="intro_guest_btn large_button_rounded default_btn"><?php echo $lang['guest_login']; ?></button>
					<?php } ?>
				</div>
				<?php if(registration()){ ?>
				<div id="not_yet_member" class="login_not_member bclick">
					<p onclick="getRegistration();" class="inblock login_register_text pad10"><?php echo $lang['not_member']; ?></p>
				</div>
				<?php } ?>
			</div>
		</div>
	</div>
	<div class="section back_xlite" id="intro_section_user">
		<div class="section_content">
			<div class="section_inside">
				<div id="last_active">
				  <div class="left-arrow"></div>
				  <div class="right-arrow"></div>

				  <div class="last-clip">
					<div class="last_10">
						<?php echo introActive(8); ?>
					</div>
				  </div>
				</div>
			</div>
		</div>
		<div class="clear"></div>
	</div>
	<div class="section" id="intro_section_bottom">
	</div>
	<div class="section intro_footer" id="main_footer">
		<div class="section_content">
			<div class="section_inside">
				<?php waliFooterMenu(); ?>
			</div>
		</div>
		<div class="clear"></div>
	</div>
</div>
<script data-cfasync="false" src="scripts/login.js"></script>
<script data-cfasync="false" src="scripts/function_active.js"></script>