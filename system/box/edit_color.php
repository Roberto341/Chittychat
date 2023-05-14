<?php 
require('../config.php');
if(!canColor()){
    die();
}
?>
<div class="pad_box">
            <div class="preview_zone border_bottom">
                <p class="label">Preview</p>
                <p id="preview_name" class="<?php echo myColorFont($data); ?>"><?php echo $_COOKIE['username']?></p>
            </div>
            <div class="user_edit_color">
                <div class="user_color" data="<?php echo $data['username_color']; ?>">
                    <div class="reg_menu_container tmargin10">
                        <div class="reg_menu">
                            <ul>
                                <li class="reg_menu_item reg_selected" data="color_tab" data-z="reg_color"><?php echo $lang['color']; ?></li>
                                <li class="reg_menu_item" data="color_tab" data-z="neon_color"><?php echo 'Neon';?></li>
                                <li class="reg_menu_item " data="color_tab" data-z="grad_color"><?php echo $lang['gradient'];?></li>
                            </ul>
                        </div>
                    </div>
                <div id="color_tab">
                    <div id="reg_color" class="reg_zone vpad5 norm_col_choice">
					<?php echo colorChoice($data['username_color'], 3); ?>
					<div class="clear"></div>
				</div>
				<?php if(canNeon()){ ?>
				<div id="neon_color" class="reg_zone vpad5 hide_zone neon_col_choice">
					<?php echo neonChoice($data['username_color'], 3); ?>
					<div class="clear"></div>
				</div>
				<?php } ?>
                <?php if(canGrad()){ ?>
				<div id="grad_color" class="reg_zone vpad5 hide_zone grad_col_choice">
					<?php echo gradChoice($data['username_color'], 3); ?>
					<div class="clear"></div>
				</div>
				<?php } ?>
                    </div>
                </div>
                <div class="clear"></div>
            </div>
            <div>
                <?php if(canFont()){?>
                    <div class="setting_element">
                        <p class="label"><?php echo 'Font';?></p>
                        <select id="fontitname">
                            <?php echo listNameFont($data['user_font']); ?>
                        </select>
                    </div>
                    <?php }?>
            </div>
            <div>
                <?php if(canFont()){?>
                    <input id="fontitname" value="" class="hidden"/>
                    <?php }?>
            </div>
            <div class="tpad10">
                <button onclick="saveNameColor();"class="reg_button theme_btn"><i class="fa fa-save"></i><?php echo $lang['save'];?></button>
            </div>
</div>