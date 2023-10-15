<?php 
require_once("system/config.php");

$page_info = array(
    'page' => 'admin',
    'page_load' => 'system/pages/admin/setting_dashboard.php',
    'page_menu' => 1,
    'page_rank' => 6,
    'page_nohome' => 1,
);
// loading head element

include('control/head_load.php');

include('control/header.php');
?>
<script data-cfasync="false" src="js/function_admin.js<?php echo $bbfv; ?>"></script>

<?php
include('control/body_end.php');
?>