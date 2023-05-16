<?php
require_once("../function_2.php");
include("../db_conn.php");
?>
<div class="in_room_element btauto <?php echo $current; ?> list_element" onclick="switchRoom('<?php echo $walie['room_name']; ?>', <?php echo $walie['id']?>); url='<?php echo $walie['id']?>'">
	<div class="in_room_icon">
		<?php echo roomIcon($walie, 'in_room_img'); ?>
	</div>
	<div class="in_room_name">
		<?php echo $walie['room_name']; ?>
	</div>
	<div class="in_room_count">
		<p class="text_reg"><?php echo $walie['room_count']; ?> <i class="fa fa-user theme_color"></i></p>
	</div>
</div>