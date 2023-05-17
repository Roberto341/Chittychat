CREATE TABLE `wali_rooms` (
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

CREATE TABLE wali_room_action(
    `id` int(11) NOT NULL AUTO_INCREMENT,
    `action_room` int(11) NOT NULL DEFAULT '0',
    `action_user` int(11) NOT NULL DEFAULT '0',
    `action_muted` int(1) NOT NULL DEFAULT '0',
    `action_blocked` int(1) NOT NULL DEFAULT '0',

    PRIMARY KEY (`id`)
);

CREATE TABLE wali_room_staff(
    `id` int(11) NOT NULL AUTO_INCREMENT,
    `room_id` int(11) NOT NULL DEFAULT '0',
    `room_staff` int(11) NOT NULL DEFAULT '0',
    `room_rank` int(1) NOT NULL DEFAULT '0',

    PRIMARY KEY (`id`)
);

CREATE TABLE wali_private(
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

CREATE TABLE wali_chat(
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

CREATE TABLE wali_filter(
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

CREATE TABLE wali_ignore(
    `ignore_id` int(11) NOT NULL AUTO_INCREMENT,
    `ignorer` int(11) NOT NULL DEFAULT '0',
    `ignored`  int(11) NOT NULL DEFAULT '0',
    `ignore_date` int(11) NOT NULL DEFAULT '0',

    PRIMARY KEY (`ignore_id`)
);

CREATE TABLE wali_friends(
    `id` int(11) NOT NULL AUTO_INCREMENT,
    `hunter` int(11) NOT NULL DEFAULT '0',
    `target` int(11) NOT NULL DEFAULT '0',
    `fstatus` int(1) NOT NULL DEFAULT '1',
    `viewed` int(1) NOT NULL DEFAULT '0',

    PRIMARY KEY (`id`)
);

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
