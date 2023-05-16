<?php
require_once('../config_session.php');
if(!canColor()){
	die();
}
?>
<div class="pad_box">
    <div class="preview_zone border_bottom">
        <p class="label"><?php echo 'preview'?></p>
        <p id="preview_text" class="<?php echo myTextColor($data)?>">Lorem ipsum dolor sit amet.</p>
    </div>
    <div class="color_choices" data="<?php $data['wccolor']?>">
    <?php if(canGrad() || canNeon()){?>
    <div class="reg_menu_container">
        <div class="reg_menu">
            <ul>
                <li class="reg_menu_item norm_col reg_selected" data="color_tab" data-z="reg_color"><?php echo $lang['color'];;?></li>
            <?php if(canNeon()){?>
                <li class="reg_menu_item neon_col" data="color_tab" data-z="neon_color"><?php echo $lang['neon'];;?></li>
            <?php } ?>
            <?php if(canGrad()){?>
                <li class="reg_menu_item grad_col" data="color_tab" data-z="grad_color"><?php echo $lang['gradient'];;?></li>
                <?php } ?>
            </ul>
        </div>
    </div>
    <?php } ?>
    <div id="color_tab">
        <div id="reg_color" class="reg_zone vpad15 norm_col_choice">
            <?php echo colorChoice($data['wccolor'], 2);?>
            <div class="clear"></div>
        </div>
        <?php if(canGrad()){?>
            <div id="grad_color" class="reg_zone vpad15 hide_zone grad_col_choice">
            <?php echo gradChoice($data['wccolor'], 2);?>
            <div class="clear"></div>
        </div>
        <?php }?>
        <?php if(canNeon()){?>
            <div id="neon_color" class="reg_zone vpad15 hide_zone neon_col_choice">
            <?php echo gradChoice($data['wccolor'], 2);?>
            <div class="clear"></div>
        </div>
        <?php }?>
    </div>
    <div class="clear"></div>
</div>
<div>
    <div class="btable">
        <div class="bcell_mid">
            <div class="setting_element">
                <p class="label"><?php echo $lang['font_style'];?></p>
                <select id="boldit">
                    <?php echo listFontstyle($data['wcbold'])?>
                </select>
                <?php if(!canFont()){?>
                    <input id="fontit" value="" class="hidden">
                <?php }?>
            </div>
        </div>
        <?php if(canFont()){?>
            <div class="bcell_mid pwidth10"></div>
            <div class="bcell_mid">
                <div class="setting_element">
                <p class="label"><?php echo $lang['font']; ?></p>
                <select id="fontit">
                    <?php echo listFont($data['wcfont']);?>
                </select>
                </div>
            </div>
        <?php }?>
    </div>
</div>
<div class="tpad10">
    <button onclick="saveColor();" class="reg_button theme_btn"><i class="fa fa-save"></i>Save</button>
</div>
        </div>