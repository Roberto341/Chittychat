<?php
require_once("../function_2.php");
include("../db_conn.php");
?>
<div class="in_room_element btauto <?php echo $current; ?> list_element" onclick="switchRoom('<?php echo $boom['room_name']; ?>', <?php echo $boom['id']?>); url='<?php echo $boom['id']?>'">
	<div class="in_room_icon">
		<?php echo roomIcon($boom, 'in_room_img'); ?>
	</div>
	<div class="in_room_name">
		<?php echo $boom['room_name']; ?>
	</div>
	<div class="in_room_count">
		<p class="text_reg"><?php echo $boom['room_count']; ?> <i class="fa fa-user theme_color"></i></p>
	</div>
</div>