CREATE TABLE wali_banned(
    `id` int(11) NOT NULL AUTO_INCREMENT,
    `ip` varchar(100) NOT NULL,
    `ban_user` int(11) NOT NULL DEFAULT '0',

    PRIMARY KEY(`id`)
);

CREATE TABLE wali_console (
    `id` int(11) NOT NULL AUTO_INCREMENT,
    `hunter` int(11) NOT NULL DEFAULT '0',
    `target` int(11) NOT NULL DEFAULT '0',
    `room` int(11) NOT NULL DEFAULT '0',
    `ctype` varchar(200) NOT NULL,
    `crank` int(11) NOT NULL DEFAULT '0',
    `delay` int(11) NOT NULL DEFAULT '0',
    `reason` varchar(200) NOT NULL,
    `custom` varchar(2000) NOT NULL,
    `custom2` varchar(2000) NOT NULL,
    `cdate` int(11) NOT NULL DEFAULT '0',
    PRIMARY KEY(`id`)
);

CREATE TABLE wali_rooms (
    `room_id` int(11) NOT NULL AUTO_INCREMENT,
    `room_name` varchar(40) NOT NULL,
    `topic` varchar(1000) NOT NULL,
    `access` int(1) DEFAULT '0' NOT NULL,
    `description` varchar(400) NOT NULL,
    `max_user` int(3) DEFAULT '0' NOT NULL,
    `password` varchar(40) NOT NULL,
    `room_system` int(1) DEFAULT '1' NOT NULL,
    `room_action` int(1) DEFAULT '0' NOT NULL,
    `room_player_id` int(1) DEFAULT '0' NOT NULL,
    `room_creator` int(1) DEFAULT '0' NOT NULL,
    `rcaction` int(1) DEFAULT '0' NOT NULL,
    `rldelete` varchar(300) NOT NULL,
    `rltime` int(11) DEFAULT '0' NOT NULL,

    PRIMARY KEY (`room_id`)
);

CREATE TABLE wali_radio_stream (
    `id` int(11) NOT NULL AUTO_INCREMENT,
    `stream_url` varchar(300) NOT NULL,
    `stream_alias` varchar(50) NOT NULL,
    PRIMARY KEY(`id`)
);

CREATE TABLE wali_room_action (
    `id` int(11) NOT NULL AUTO_INCREMENT,
    `action_room` int(11) NOT NULL DEFAULT '0',
    `action_user` int(11) NOT NULL DEFAULT '0',
    `action_muted` int(1) NOT NULL DEFAULT '0',
    `action_blocked` int(1) NOT NULL DEFAULT '0',

    PRIMARY KEY (`id`)
);

CREATE TABLE wali_room_staff (
    `id` int(11) NOT NULL AUTO_INCREMENT,
    `room_id` int(11) NOT NULL DEFAULT '0',
    `room_staff` int(11) NOT NULL DEFAULT '0',
    `room_rank` int(1) NOT NULL DEFAULT '0',

    PRIMARY KEY (`id`)
);

CREATE TABLE wali_private (
    `id` int(11) NOT NULL AUTO_INCREMENT,
    `time` int(11) NOT NULL DEFAULT '0',
    `message` varchar(1000) NOT NULL,
    `hunter` int(11) NOT NULL DEFAULT '0',
    `target` int(11) NOT NULL DEFAULT '0',
    `status` int(1) NOT NULL DEFAULT '0',
    `view` int(1) NOT NULL DEFAULT '0',
    `file` int(11) NOT NULL DEFAULT '0',

    PRIMARY KEY (`id`)
);

CREATE TABLE wali_chat ( 
    `post_id` int(11) NOT NULL AUTO_INCREMENT,
    `user_id` int(11) NOT NULL DEFAULT '0',
    `post_date` int(11) NOT NULL DEFAULT '0',
    `post_message` varchar(2000) NOT NULL,
    `post_roomid` int(6) NOT NULL DEFAULT '1',
    `type` varchar(50) NOT NULL,
    `log_rank` int(5) NOT NULL DEFAULT '99',
    `file` int(1) NOT NULL DEFAULT '0',
    `snum` varchar(20) NOT NULL,
    `tcolor` varchar(50) NOT NULL,

    PRIMARY KEY (`post_id`)
);

CREATE TABLE wali_filter (
    `id` int(11) NOT NULL AUTO_INCREMENT,
    `word` varchar(100) NOT NULL,
    `word_type` varchar(12) NOT NULL DEFAULT 'word',

    PRIMARY KEY (`id`)
);

CREATE TABLE wali_setting (
    `id` int(11) NOT NULL AUTO_INCREMENT,
    `title` varchar(600) NOT NULL,
    `site_description` varchar(600) NOT NULL,
    `site_keyword` varchar(600) NOT NULL,
    `login_page` varchar(50) DEFAULT 'Default' NOT NULL,
    `dat` varchar(100) NOT NULL,
    `system_id` int(11) DEFAULT '0' NOT NULL,
    `registration` int(1) DEFAULT '1' NOT NULL,
    `maint_mode` int(1) DEFAULT '0' NOT NULL,
    `use_bridge` int(1) DEFAULT '0' NOT NULL,
    `use_lobby` int(1) DEFAULT '0' NOT NULL,
    `allow_guest` int(1) DEFAULT '0' NOT NULL,
    `guest_form` int(1) DEFAULT '0' NOT NULL,
    `guest_talk` int(1) DEFAULT '1' NOT NULL,
    `default_theme` varchar(15) DEFAULT 'dark' NOT NULL,
    `allow_theme` int(2) DEFAULT '1' NOT NULL,
    `domain` varchar(1000) NOT NULL,
    `allow_avatar` int(2) DEFAULT '1' NOT NULL,
    `allow_cover` int(2) DEFAULT '1' NOT NULL,
    `allow_gcover` int(2) DEFAULT '1' NOT NULL,
    `allow_name_color` int(2) DEFAULT '1' NOT NULL,
    `allow_name_grad` int(2) DEFAULT '1' NOT NULL,
    `allow_name_neon` int(2) DEFAULT '2' NOT NULL,
    `allow_name_font` int(2) DEFAULT '1' NOT NULL,
    `allow_verify` int(2) DEFAULT '1' NOT NULL,
    `allow_logs` int(2) DEFAULT '1' NOT NULL,
    `allow_cupload` int(2) DEFAULT '1' NOT NULL,
    `allow_pupload` int(2) DEFAULT '1' NOT NULL,
    `allow_wupload` int(2) DEFAULT '1' NOT NULL,
    `allow_direct` int(2) DEFAULT '1' NOT NULL,
    `allow_room` int(2) DEFAULT '4' NOT NULL,
    `verison` varchar(5) DEFAULT '1.0' NOT NULL,
    `bbfv` varchar(5) DEFAULT '1.0' NOT NULL,
    `language` varchar(20) DEFAULT 'English' NOT NULL,
    `activation` int(1) DEFAULT '0' NOT NULL,
    `use_wall` int(1) DEFAULT '1' NOT NULL,
    `timezone` varchar(60) Default 'America/Montreal' NOT NULL,
    `boom` varchar(50) NOT NULL DEFAULT '9cdc338d70a2660843863d11a3c662cbfef8f93e',
    `min_age` int(2) DEFAULT '13' NOT NULL,
    `allow_colors` int(2) DEFAULT '1' NOT NULL,
    `allow_grad` int(2) DEFAULT '1' NOT NULL,
    `allow_neon` int(2) DEFAULT '2' NOT NULL,
    `allow_font` int(2) DEFAULT '1' NOT NULL,
    `allow_mood` int(2) DEFAULT '1' NOT NULL,
    `emo_plus` int(2) DEFAULT '2' NOT NULL,
    `speed` int(4) DEFAULT '3000' NOT NULL,
    `player_id` int(11) DEFAULT '0' NOT NULL,
    `max_main` int(4) DEFAULT '300' NOT NULL,
    `max_private` int(4) DEFAULT '200' NOT NULL,
    `gender_ico` int(1) DEFAULT '1' NOT NULL,
    `flag_ico` int(1) DEFAULT '1' NOT NULL,
    `word_action` int(1) DEFAULT '0' NOT NULL,
    `word_delay` int(11) DEFAULT '5' NOT NULL,
    `spam_action` int(1) DEFAULT '0' NOT NULL,
    `spam_delay` int(1) DEFAULT '60' NOT NULL,
    `email_filter` int(1) DEFAULT '0' NOT NULL,
    `max_username` int(2) DEFAULT '18' NOT NULL,
    `chat_delete` int(11) DEFAULT '0' NOT NULL,
    `private_delete` int(11) DEFAULT '0' NOT NULL,
    `wall_delete` int(11) DEFAULT '0' NOT NULL,
    `last_clean` int(11) DEFAULT '0' NOT NULL,
    `member_delete` int(11) DEFAULT '0' NOT NULL,
    `room_delete` int(11) DEFAULT '0' NOT NULL,
    `max_offcount` int(3) DEFAULT '20' NOT NULL,
    `allow_name` int(2) DEFAULT '2',
    `act_delay` int(1) DEFAULT '0',
    `cookie_law` int(1) DEFAULT '0',
    `reg_mute` int(1) DEFAULT '0',

    PRIMARY KEY (`id`)
);

CREATE TABLE wali_users (
    `user_id` int(11) NOT NULL AUTO_INCREMENT,
    `user_name` varchar(60) NOT NULL,
    `user_password` varchar(60) NOT NULL,
    `user_email` varchar(80) NOT NULL,
    `sub_id` varchar(50) NOT NULL,
    `user_ip` varchar(50) NOT NULL,
    `user_mobile` int(1) NOT NULL DEFAULT '0',
    `user_join` int(11) NOT NULL DEFAULT '0',
    `join_msg` int(1) NOT NULL DEFAULT '0',
    `last_action` int(11) NOT NULL DEFAULT '0',
    `user_language` varchar(30) NOT NULL DEFAULT 'English',
    `user_timezone` varchar(30) NOT NULL DEFAULT 'America/Montreal',
    `user_status` int(3) NOT NULL DEFAULT '1',
    `user_color` varchar(20) NOT NULL DEFAULT 'user',
    `user_font` varchar(10) NOT NULL,
    `wccolor` varchar(10) NOT NULL,
    `wcbold` varchar(10) NOT NULL,
    `wcfont` varchar(10) NOT NULL,
    `user_rank` int(2) NOT NULL DEFAULT '1',
    `user_dj` int(1) NOT NULL DEFAULT '0',
    `user_onair` int(1) NOT NULL DEFAULT '0',
    `user_roomid` int(11) NOT NULL DEFAULT '1',
    `user_theme` varchar(20) NOT NULL DEFAULT 'system',
    `user_sex` int(1) NOT NULL DEFAULT '1',
    `user_age` int(3) NOT NULL,
    `user_avatar` varchar(200) NOT NULL DEFAULT '1',
    `user_cover` varchar(100) NOT NULL DEFAULT '1',
    `user_sound` int(10) NOT NULL DEFAULT '1234',
    `temp_pass` varchar(60) NOT NULL DEFAULT '1',
    `temp_date` int(11) NOT NULL DEFAULT '0',
    `verified` int(1) NOT NULL DEFAULT '0',
    `user_verify` int(1) NOT NULL DEFAULT '0',
    `country` varchar(10) NOT NULL DEFAULT 'US',
    `session_id` int(11) NOT NULL DEFAULT '1',
    `pcount` int(11) NOT NULL DEFAULT '0',
    `user_news` int(11) NOT NULL DEFAULT '0',
    `user_mute` int(11) NOT NULL DEFAULT '0',
    `user_regmute` int(11) NOT NULL DEFAULT '0',
    `user_banned` int(11) NOT NULL DEFAULT '0',
    `user_kick` int(11) NOT NULL DEFAULT '0',
    `kick_msg` varchar(300) NOT NULL,
    `mute_msg` varchar(300) NOT NULL,
    `ban_msg` varchar(300) NOT NULL,
    `user_role` int(1) NOT NULL DEFAULT '0',
    `user_action` int(11) NOT NULL DEFAULT '0',
    `room_mute` int(1) NOT NULL DEFAULT '0',
    `user_about` varchar(1000) NOT NULL,
    `user_mood` varchar(100) NOT NULL,
    `user_bot` int(1) NOT NULL DEFAULT '0',
    `naction` int(11) NOT NULL DEFAULT '1',
    `user_private` int(11) NOT NULL DEFAULT '1',
    `user_delete` int(11) NOT NULL DEFAULT '0',
    `quizscore` int(11) NOT NULL DEFAULT '0',

    PRIMARY KEY(`user_id`)
);

CREATE TABLE wali_ignore (
    `ignore_id` int(11) NOT NULL AUTO_INCREMENT,
    `ignorer` int(11) NOT NULL DEFAULT '0',
    `ignored`  int(11) NOT NULL DEFAULT '0',
    `ignore_date` int(11) NOT NULL DEFAULT '0',

    PRIMARY KEY (`ignore_id`)
);

CREATE TABLE wali_friends (
    `id` int(11) NOT NULL AUTO_INCREMENT,
    `hunter` int(11) NOT NULL DEFAULT '0',
    `target` int(11) NOT NULL DEFAULT '0',
    `fstatus` int(1) NOT NULL DEFAULT '1',
    `viewed` int(1) NOT NULL DEFAULT '0',

    PRIMARY KEY (`id`)
);

CREATE TABLE wali_upload (
    `id` int(11) NOT NULL AUTO_INCREMENT,
    `file_name` varchar(300) NOT NULL,
    `file_key` varchar(100) NOT NULL,
    `date_sent` int(11) NOT NULL DEFAULT '0',
    `file_user` int(11) NOT NULL DEFAULT '0',
    `file_zone` varchar(30) NOT NULL DEFAULT '1',
    `file_type` varchar(30) NOT NULL,
    `file_complete` int(1) NOT NULL DEFAULT '1',
    `relative_post` int(11) NOT NULL DEFAULT '0',

    PRIMARY KEY (`id`)
);

CREATE TABLE wali_report (
    `report_id` int(11) NOT NULL AUTO_INCREMENT,
    `report_type` int(2) NOT NULL DEFAULT '0',
    `report_user` int(11) NOT NULL DEFAULT '0',
    `report_target` int(11) NOT NULL DEFAULT '0',
    `report_post` int(11) NOT NULL DEFAULT '0',
    `report_reason` varchar(500) NOT NULL,
    `report_room` int(11) NOT NULL DEFAULT '0',
    `report_date` int(11) NOT NULL DEFAULT '0',
    PRIMARY KEY (`report_id`)
);

CREATE TABLE wali_notification (
    `id`int(11) NOT NULL AUTO_INCREMENT,
    `notifier` int(11) NOT NULL DEFAULT '0',
    `notified` int(11) NOT NULL DEFAULT '0',
    `notify_type` varchar(30) NOT NULL,
    `notify_date` int(11) NOT NULL DEFAULT '0',
    `notify_source` varchar(30) NOT NULL,
    `notify_id` int(11) NOT NULL DEFAULT '0',
    `notify_rank` int(11) NOT NULL DEFAULT '0',
    `notify_delay` int(11) NOT NULL DEFAULT '0',
    `notify_reason` varchar(2000) NOT NULL,
    `notify_view` int(11) NOT NULL DEFAULT '0',
    `notify_custom` varchar(2000) NOT NULL,
    `notify_custom2` varchar(2000) NOT NULL,
    PRIMARY KEY(`id`)
);

CREATE TABLE wali_news (
    `id` int(11) NOT NULL AUTO_INCREMENT,
    `news_poster`int(11) NOT NULL DEFAULT '0',
    `news_message` varchar(3000) NOT NULL,
    `news_file` varchar(1000) NOT NULL,
    `news_date` int(11) NOT NULL DEFAULT '1',
    PRIMARY KEY(`id`)
);

CREATE TABLE wali_post (
    `post_id` int(11) NOT NULL AUTO_INCREMENT,
    `post_user` int(11) NOT NULL DEFAULT '0',
    `post_date` int(11) NOT NULL DEFAULT '0',
    `post_comment` varchar(2000) NOT NULL,
    `post_file` varchar(1000) NOT NULL,
    `post_type` int(1) NOT NULL DEFAULT '1',
    `post_actual` int(11) NOT NULL DEFAULT '0',
    PRIMARY KEY(`post_id`)
);

CREATE TABLE wali_post_reply (
    `reply_id` int(11) NOT NULL AUTO_INCREMENT,
    `parent_id` int(11) NOT NULL DEFAULT '0',
    `reply_user` int(11) NOT NULL DEFAULT '0',
    `reply_date` int(11) NOT NULL DEFAULT '0',
    `reply_conent` varchar(1000) NOT NULL,
    `reply_uid` int(11) NOT NULL DEFAULT '0',
    PRIMARY KEY(`reply_id`)
);

CREATE TABLE wali_post_like (
    `id` int(11) NOT NULL AUTO_INCREMENT,
    `uid` int(11) NOT NULL DEFAULT '0',
    `liked_uid` int(11) NOT NULL DEFAULT '0',
    `like_type` int(1) NOT NULL DEFAULT '1',
    `like_post` int(11) NOT NULL DEFAULT '1',
    `like_date` int(11) NOT NULL DEFAULT '0',
    PRIMARY KEY(`id`)
);

CREATE TABLE wali_news_like (
    `id` int(11) NOT NULL AUTO_INCREMENT,
    `uid` int(11) NOT NULL DEFAULT '0',
    `liked_uid` int(11) NOT NULL DEFAULT '0',
    `like_type` int(1) NOT NULL DEFAULT '1',
    `like_post` int(11) NOT NULL DEFAULT '1',
    `like_date` int(11) NOT NULL DEFAULT '0',
    PRIMARY KEY(`id`)
);

CREATE TABLE wali_news_reply (
    `reply_id` int(11) NOT NULL AUTO_INCREMENT,
    `parent_id` int(11) NOT NULL DEFAULT '0',
    `reply_user` int(11) NOT NULL DEFAULT '0',
    `reply_date` int(1) NOT NULL DEFAULT '0',
    `reply_content` varchar(1000) NOT NULL,
    `reply_uid` int(11) NOT NULL DEFAULT '0',
    PRIMARY KEY(`reply_id`)
);

ALTER TABLE `wali_news_reply`ADD INDEX(`parent_id`);
ALTER TABLE `wali_news_reply`ADD INDEX(`reply_user`);
ALTER TABLE `wali_news_reply`ADD INDEX(`reply_date`);
ALTER TABLE `wali_news_reply`ADD INDEX(`reply_uid`);

ALTER TABLE `wali_news_like`ADD INDEX(`uid`);
ALTER TABLE `wali_news_like`ADD INDEX(`liked_uid`);
ALTER TABLE `wali_news_like`ADD INDEX(`like_date`);


ALTER TABLE `wali_post_like` ADD INDEX(`uid`);
ALTER TABLE `wali_post_like` ADD INDEX(`liked_uid`);
ALTER TABLE `wali_post_like` ADD INDEX(`like_date`);

ALTER TABLE `wali_post_reply` ADD INDEX(`parent_id`);
ALTER TABLE `wali_post_reply` ADD INDEX(`reply_user`);
ALTER TABLE `wali_post_reply` ADD INDEX(`reply_date`);
ALTER TABLE `wali_post_reply` ADD INDEX(`reply_uid`);


ALTER TABLE `wali_post` ADD INDEX(`post_user`);
ALTER TABLE `wali_post` ADD INDEX(`post_date`);

ALTER TABLE `wali_users` ADD INDEX(`user_email`);
ALTER TABLE `wali_users` ADD INDEX(`user_ip`);
ALTER TABLE `wali_users` ADD INDEX(`last_action`);
ALTER TABLE `wali_users` ADD INDEX(`user_status`);
ALTER TABLE `wali_users` ADD INDEX(`user_roomid`);
ALTER TABLE `wali_users` ADD INDEX(`user_bot`);
ALTER TABLE `wali_users` ADD INDEX(`user_delete`);

ALTER TABLE `wali_chat` ADD INDEX(`user_id`);
ALTER TABLE `wali_chat` ADD INDEX(`post_date`);
ALTER TABLE `wali_chat` ADD INDEX(`post_roomid`);

ALTER TABLE `wali_private` ADD INDEX(`time`);
ALTER TABLE `wali_private` ADD INDEX(`hunter`);
ALTER TABLE `wali_private` ADD INDEX(`target`);
ALTER TABLE `wali_private` ADD INDEX(`status`);


ALTER TABLE `wali_ignore` ADD INDEX(`ignorer`);
ALTER TABLE `wali_ignore` ADD INDEX(`ignored`);
ALTER TABLE `wali_ignore` ADD INDEX(`ignore_date`);

ALTER TABLE `wali_filter` ADD INDEX(`word_type`);

ALTER TABLE `wali_rooms` ADD INDEX(`room_system`);
ALTER TABLE `wali_rooms` ADD INDEX(`room_action`);

ALTER TABLE `wali_room_action` ADD INDEX(`action_user`);

ALTER TABLE `wali_room_staff` ADD INDEX(`room_id`);
ALTER TABLE `wali_room_staff` ADD INDEX(`room_staff`);

ALTER TABLE `wali_upload` ADD INDEX(`date_sent`);
ALTER TABLE `wali_upload` ADD INDEX(`file_zone`);
ALTER TABLE `wali_upload` ADD INDEX(`file_complete`);

ALTER TABLE `wali_banned` ADD INDEX(`ip`);
ALTER TABLE `wali_banned` ADD INDEX(`ban_user`);

ALTER TABLE `wali_console` ADD INDEX(`hunter`);
ALTER TABLE `wali_console` ADD INDEX(`target`);
ALTER TABLE `wali_console` ADD INDEX(`room`);

ALTER TABLE `wali_notification` ADD INDEX(`notifier`);
ALTER TABLE `wali_notification` ADD INDEX(`notified`);
ALTER TABLE `wali_notification` ADD INDEX(`notify_date`);
ALTER TABLE `wali_notification` ADD INDEX(`notify_source`);
ALTER TABLE `wali_notification` ADD INDEX(`notify_id`);
ALTER TABLE `wali_notification` ADD INDEX(`notify_view`);

ALTER TABLE `wali_report` ADD INDEX(`report_user`);
ALTER TABLE `wali_report` ADD INDEX(`report_target`);

ALTER TABLE `wali_news` ADD INDEX(`news_date`);