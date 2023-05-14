<?php
require_once('../function_2.php');
if(!canColor()){
	die();
}
?>
<div class="pad_box">
    <div class="preview_zone border_bottom">
        <p class="label"><?php echo 'preview'?></p>
        <p id="preview_text" class="<?php echo myTextColor($row)?>">Lorem ipsum dolor sit amet.</p>
    </div>
    <div class="color_choices" data="<?php $row['wccolor']?>">
    <?php if(canGrad() || canNeon()){?>
    <div class="reg_menu_container">
        <div class="reg_menu">
            <ul>
                <li class="reg_menu_item norm_col reg_selected" data="color_tab" data-z="reg_color"><?php echo 'Color';?></li>
            <?php if(canNeon()){?>
                <li class="reg_menu_item neon_col" data="color_tab" data-z="neon_color"><?php echo 'Neon';?></li>
            <?php } ?>
            <?php if(canGrad()){?>
                <li class="reg_menu_item grad_col" data="color_tab" data-z="grad_color"><?php echo 'Gradient';?></li>
                <?php } ?>
            </ul>
        </div>
    </div>
    <?php } ?>
    <div id="color_tab">
        <div id="reg_color" class="reg_zone vpad15 norm_col_choice">
            <?php echo colorChoice($row['wccolor'], 2);?>
            <div class="clear"></div>
        </div>
        <?php if(canGrad()){?>
            <div id="grad_color" class="reg_zone vpad15 hide_zone grad_col_choice">
            <?php echo gradChoice($row['wccolor'], 2);?>
            <div class="clear"></div>
        </div>
        <?php }?>
        <?php if(canNeon()){?>
            <div id="neon_color" class="reg_zone vpad15 hide_zone neon_col_choice">
            <?php echo gradChoice($row['wccolor'], 2);?>
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
                <p class="label"><?php echo 'Font Style'?></p>
                <select id="boldit">
                    <?php echo listFontstyle($row['wcbold'])?>
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
                <p class="label"><?php echo 'Font'; ?></p>
                <select id="fontit">
                    <?php echo listFont($row['wcfont']);?>
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