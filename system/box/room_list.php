<?php
require_once('../config_session.php');
?>
<div class="modal_top">
	<?php if(canAddroom()){ ?>
	<div onclick="openAddRoom();" class="bcell_mid hpad10 bold private_cleaning">
		<i class="fa fa-plus"></i> <?php echo $lang['add_room']; ?>
	</div>
	<?php } ?>
	<div class="modal_top_empty bold">
	</div>
	<div class="modal_top_element cancel_modal">
		<i class="fa fa-times"></i>
	</div>
</div>
<div class="box_height600">
	<div id="container_room">
		<?php echo getRoomList('list'); ?>
	</div>
	<div class="clear"></div>
</div>