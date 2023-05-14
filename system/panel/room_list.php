<?php
include("../config.php");
?>
<?php if(canRoom()){ ?>
<div class="vpad15 hpad10">
	<button  class="thin_button rounded_button theme_btn" onclick="openAddRoom();"><i class="fa fa-plus"></i> <?php echo 'Add room'; ?></button>
</div>
<?php } ?>
<div id="container_room">
	<?php echo getRoomList('list'); ?>
</div>