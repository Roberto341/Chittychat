<?php
    //session_start();
    //@author Robert Scharaswak Jr
    define('WALI_PATH', dirname(__DIR__));
    $check_install = 1;
    $wali['color_count'] = 32;			// number of color used and defined in css
    $wali['gradient_count'] = 40;		// number of gradient used and defined in css
    $wali['neon_count'] = 32;


    $wali['max_reg'] = 100; 				// max registration per day per ip
    $wali['max_room_name'] = 30; 		// max lenght of room name
    $wali['max_description'] = 150; 	// max lenght of room description
    $wali['act_time'] = 1;				// turn on off the innactivity balancer (0)off (1)on
    $wali['max_room'] = 1;				// maximum room that a single user can create
    $wali['reg_filter'] = 1;			// turn on off the ip registration filter (0)off (1)on
    $wali['strict_guest'] = 1;			// strict guest registration mode follow system settings
    $wali['max_verify'] = 3;			// maximum verification email allowed per 24 hours per user
    $wali['max_report'] = 3;			// maximum active report allowed per users.
    $wali['guest_per_day'] = 20;		// maximum guest account per day with same ip
    $wali['guest_delay'] = 30;			// delay for wich a guest account cannot be overwrited in minutes
    $wali['flood_delay'] = 15;			// minutes of mute applyed when a flood is detected
    $wali['flood_limit'] = 6;			// post required within 10 sec to trigger flood protection
    $wali['strip_direct'] = 0;			// set to 1 to activate direct display hard mode
    $wali['default_mute'] = 5;			// default mute delay in mute box
    $wali['ignore_clean'] = 30;			// ignore expire automaticly after x days 0 for never
    $wali['use_geo'] = 1;				// set to 0 to disable the auto geolocalisation
    $wali['default_kick'] = 5;			// default kick delay in kick box
    $wali['rbreak'] = 900;				// right chat panel mobile breakpoint in pixel
    $wali['lbreak'] = 1260;				// left chat panel mobile breakpoint in pixel
    $wali['right_size'] = 280;			// default right panel size in pixel
    $wali['left_size'] = 150;			// default left panel size in pixel
    $wali['report_history'] = 100;		// max log history private report will show
    $wali['card_cover'] = 1;			// display card cover set 0 to disable or 1 to enable

    /* permission settings */

$wali['can_flood'] = 3;				// rank that is not affected by the mute protection.
$wali['can_word_filter'] = 5;		// rank required to not be affected by word filter
$wali['can_post_news'] = 6;		// rank required to post news
$wali['can_delete_news'] = 6;		// rank required to delete news post
$wali['can_reply_news'] = 1;		// rank required to reply to news
$wali['can_delete_wall'] = 3;		// rank required to delete wall post
$wali['can_delete_logs'] = 3;       // rank required to delete chat post
$wali['can_delete_slogs'] = 1;		// rank required to delete self posted chat log
$wali['can_invisible'] = 5;			// rank required to have invisibility option
$wali['can_inv_view'] = 5;			// rank required to view invisible in admin panel
$wali['can_modify_avatar'] = 8;		// rank required to modify users avatar
$wali['can_modify_cover'] = 8;		// rank required to modify users cover
$wali['can_modify_name'] = 9;		// rank required to modify users username
$wali['can_modify_mood'] = 8;		// rank required to modify users mood
$wali['can_modify_about'] = 8;		// rank required to modify users about me
$wali['can_modify_email'] = 10;		// rank required to modify users email
$wali['can_modify_color'] = 10;		// rank required to modify users color
$wali['can_modify_password'] = 10;	// rank required to modify users password
$wali['can_view_history'] = 8;		// rank required to view users action history
$wali['can_view_console'] = 10;		// rank required to access console in admin panel
$wali['can_clear_console'] = 4;	// rank required to clear the admin console log
$wali['can_view_email'] = 4;		// rank required to view users email
$wali['can_view_timezone'] = 4;	// rank required to view users timezone
$wali['can_view_id'] = 4;			// rank required to view users id
$wali['can_view_ip'] = 4;			// rank required to view users ip
$wali['can_room_pass'] = 3;			// rank required to enter room without pass
$wali['can_rank'] = 4;				// rank required to change rank of members do not go bellow 11, 10 or 9
$wali['can_ban'] = 3;				// rank required to have ban power
$wali['can_kick'] = 3;				// rank required to have kick power
$wali['can_delete'] = 6;			// rank required to have delete power
$wali['can_report'] = 1;			// rank required to have report ability
$wali['can_maintenance'] = 6;		// rank required to enter chat while in maintenance mode
$wali['can_manage_addons'] = 6;	// rank required to install, config and uninstall addons
$wali['can_edit_info'] = 0;			// rank required to edit general profile information
$wali['can_edit_about'] = 0;		// rank required to edit profile about
$wali['can_manage_report'] = 4;		// rank required to view and manage report
$wali['can_self_report'] = 5;		// rank required to remove a self involved report
$wali['can_manage_history'] = 5;	// rank required to manage profile history
$wali['can_delete_private'] = 1;	// rank required to delete private chat

/* system log messages */

$wali['join_room'] = 0;				// show log when entering room 0 disabled 1 enabled
$wali['leave_room'] = 0;			// show log when leaving room 0 disabled 1 enabled
$wali['name_change'] = 0;			// show log when change username 0 disabled 1 enabled
$wali['action_log'] = 0;			// show log when an action is taken 0 disabled 1 enabled

/* misc */

$wali['audio_download'] = 0;        // show download button for uploaded audio
$wali['clean_delay'] = 5;			// delay for system cleaning in minutes

// cookie and session settings

define('WALI_PREFIX', 'wc_');

// do not edit function below they are very important for the system to work properly

define('WALI', 1);

define('WALI_DHOST', $DB_HOST);
define('WALI_DNAME', $DB_NAME);
define('WALI_DUSER', $DB_USER);
define('WALI_DPASS', $DB_PASS);

function setWaliCookie($i, $p){
	setcookie(WALI_PREFIX . "userid","$i",time()+ 31556926, '/');
	setcookie(WALI_PREFIX . "utk","$p",time()+ 31556926, '/');
}
function unsetWaliCookie(){
	setcookie(WALI_PREFIX . "userid","",time() - 1000, '/');
	setcookie(WALI_PREFIX . "utk","",time() - 1000, '/');
}
function setWaliLang($val){
	setcookie(WALI_PREFIX . "lang","$val",time()+ 31556926, '/');
}
function setWaliCookieLaw(){
	setcookie(WALI_PREFIX . "claw","1",time()+ 31556926, '/');
}
?>