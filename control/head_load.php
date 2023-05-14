<?php
if($chat_install != 1){
	header('location: ./');
	die();
}
$ip = getIp();
$page = getPageData($page_info);
// $bbfv = boomFileVersion();
if($page['page'] == 'chat'){
	$room = roomDetails(1);
	$page['page_title'] = $room['room_name'];
}
if(boomLogged() && !boomAllow($page['page_rank'])){
	header('location: ' . $data['domain']);
	die();
}
?>
<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml" lang="en">
<head>
<title><?php echo $page['page_title']; ?></title>
<meta http-equiv="content-type" content="text/html; charset=utf-8">
<meta name="description" content="<?php echo $page['page_description']; ?>">
<meta name="keywords" content="<?php echo $page['page_keyword']; ?>">
<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0">
<link id="siteicon" rel="shortcut icon" type="image/png" href="default_images/icon.png">
<link rel="stylesheet" type="text/css" href="scripts/fancybox/jquery.fancybox.css" media="screen">
<link rel="stylesheet" type="text/css" href="css/font-awesome.min.css">
<link rel="stylesheet" type="text/css" href="css/selectboxit.css">
<link rel="stylesheet" type="text/css" href="scripts/jqueryui/jquery-ui.min.css">
<link rel="stylesheet" type="text/css" href="css/chat.css">
<?php if(!boomLogged()){ ?>
<link rel="stylesheet" type="text/css" href="control/login/<?php echo getLoginPage(); ?>/login.css">
<?php } ?>
<link id="gradient_sheet" rel="stylesheet" type="text/css" href="css/colors.css">
<link id="actual_theme" rel="stylesheet" type="text/css" href="themes/<?php echo getTheme(); ?>">
<link rel="stylesheet" type="text/css" href="css/responsive.css">
<script data-cfasync="false" src="scripts/jq/jquery-1.11.2.min.js"></script>
<script data-cfasync="false" src="system/language/<?php echo $cur_lang; ?>/language.js"></script>
<script data-cfasync="false" src="scripts/fancybox/jquery.fancybox.js"></script>
<script data-cfasync="false" src="scripts/jqueryui/jquery-ui.min.js"></script>
<script data-cfasync="false" src="scripts/global.min.js"></script>
<script data-cfasync="false" src="scripts/function_split.js"></script>
<script data-cfasync="false" src="system/action/upload.js"></script>
<?php if(boomLogged()){ ?>
<script data-cfasync="false" src="scripts/logged.js"></script>
<?php } ?>
<link rel="stylesheet" type="text/css" href="css/custom.css">
	<script data-cfasync="false">
		var pageRoom = '<?php echo $page['page_room']; ?>';
		var curPage = '<?php echo $page['page']; ?>';
		var loadPage = '<?php echo $page['page_load']; ?>';
	</script>
<?php if(!boomLogged()){ ?>
	<script data-cfasync="false">
		var logged = 0;
		var utk = '0';
		var bbfv = "";
	</script>
<?php } ?>
<?php if(boomLogged()){ ?>
	<script data-cfasync="false">
		var user_rank = <?php echo $data["user_rank"]; ?>;
		var user_id = <?php echo $data["user_id"]; ?>;
		var uSound = '<?php echo $data['user_sound']; ?>';
		var utk = '<?php echo setToken(); ?>';
		var logged = 1;
		var systemLoaded = 0;
	</script>
<?php } ?>
<?php if(boomLogged() && $page['page'] == 'chat'){ ?>
	<script data-cfasync="false">
		var user_room = <?php echo $data['user_roomid']; ?>;
		var sesid = '<?php echo $data['session_id']; ?>';
		var userAction = '<?php echo $data['user_action']; ?>';
		var pCount = "<?php echo $data['pcount']; ?>";
		var speed = <?php echo $data['speed']; ?>;
		var inOut = <?php echo $data['act_delay']; ?>;
		var snum = "<?php echo genSnum(); ?>";
		var balStart = <?php echo $wali['act_time']; ?>;
		var rightHide = <?php echo $wali['rbreak']; ?>;
		var rightHide2 = <?php echo $wali['rbreak'] + 1; ?>;
		var leftHide = <?php echo $wali['lbreak']; ?>;
		var leftHide2 = <?php echo $wali['lbreak']; + 1 ?>;
		var defRightWidth = <?php echo $wali['right_size']; ?>;
		var defLeftWidth = <?php echo $wali['left_size']; ?>;
		var cardCover = <?php echo $wali['card_cover']; ?>;
		var userAge = "<?php echo $lang['years_old']; ?>";
		var bbfv = '';
	</script>
<?php } ?>
</head>
<body>
<?php
if(checkBan($ip)){
	include('banned.php');
	include('body_end.php');
	die();
}
if(checkKick()){
	include('kicked.php');
	include('body_end.php');
	die();
}
if(!boomLogged() && $page['page_out'] == 0){
	include('control/login/' . getLoginPage() . '/login.php');
	include('body_end.php');
	die();
}
if($page['page'] == 'chat'){
	createIgnore();
}
?>