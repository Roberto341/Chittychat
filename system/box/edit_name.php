<?php 
require('../config_session.php');
?>
<div class="pad15">
	<div class="wali_form">
		<p class="label"><?php echo $lang['username'];?></p>
        <input type="text" id="new_username_text" value="<?php echo $data['user_name']; ?>" class="full_input"/>
    </div>
    <button class='reg_button theme_btn' onclick="changeMyUsername();"><i class="fa fa-save"></i> <?php echo $lang['save']; ?> </button>
    <button class="cancel_over reg_button default_btn"> <?php echo $lang['cancel']; ?> </button>
</div>