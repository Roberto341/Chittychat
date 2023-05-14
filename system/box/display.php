<?php
require("../config_session.php");
?>
<div class="pad20">
	<div class="setting_element ">
		<p class="label"><?php echo $lang['user_theme']?></p>
		<select id="set_user_theme" onchange="setUserTheme(this);">
		<option value="<?php echo getDefTheme(); ?>">Default theme</option>
			<?php echo listTheme($data['user_theme']); ?>
		</select>
	</div>
</div>