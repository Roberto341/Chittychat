<?php
include_once("../function_2.php");
?>
<div class="pad15">
	<div class="wali_form">
		<p class="label"><?php echo 'Username'?></p>
        <input id="new_username_text" class="full_input" type="text" name="new_username" id="nu">
    </div>
    <button class='reg_button theme_btn' onclick="newUsername();"><i class="fa fa-save"></i><?php echo ' Save';?></button>
    <button class='reg_button default_btn' id="canc_btn" onclick="closeOver()">Cancel</button>

</div>