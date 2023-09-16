// other used default values
var width = $(window).width();
var height = $(window).height();
var docTitle = document.title;
var actualTopic = '';
var actSpeed = '';
var curActive = 0;
var firstPanel = 'userlist';
var morePriv = 1;
var moreMain = 1;
var scroll = 1;
var roomStaff = 0;
var waitReply = 0;
var pWait = 0;
var errPost = 0;

var fload = 0;
var lastPost = 0;
var cAction = 0;
var privReload = 0;
var lastPriv = 0;
var curNotify = 0;
var curReport = 0;
var curFriends = 0;
var notifyLoad = 0;
var curNews = 0;
var globNotify = 0;
var curRm = 0;
var roomRank = 0;

selectIt = function () {
	$("select:visible").selectBoxIt({
		autoWidth: false,
		hideEffect: 'fadeOut',
		hideEffectSpeed: 100
	});
	console.log("Clicked");
}

var PageTitleNotification = {
	On: function () {
		$('#siteicon').attr('href', 'default_images/icon2.png' + bbfv);
	},
	Off: function () {
		$('#siteicon').attr('href', 'default_images/icon.png' + bbfv);
	}
}
focused = true;
window.onfocus = function () {
	focused = true;
	PageTitleNotification.Off();
}
window.onblur = function () {
	focused = false;
}
adjustPanelWidth = function () {
	$('.cright, .cright2').css('width', defRightWidth + 'px');
	$('.cleft, .cleft2').css('width', defLeftWidth + 'px');
}
adjustHeight = function () {
	var winWidth = $(window).width();
	var winHeight = $(window).height();
	var headHeight = $('#chat_head').outerHeight();
	var menuFooter = $('#my_menu').outerHeight();
	var topChatHeight = $('#top_chat_container').outerHeight();
	var sideTop = $('#side_top').outerHeight();
	var panelBar = $('#right_panel_bar').outerHeight();

	var ch = (winHeight - menuFooter - headHeight);
	var ch2 = (winHeight - menuFooter - headHeight);
	var ch3 = (winHeight);
	var cb = (ch - topChatHeight);
	$(".chatheight").css({
		"height": ch2,
	});
	$('#side_inside').css({ "height": winHeight - sideTop });
	if ($('#player_box').length) {
		$('#player_box').css({ "top": headHeight });
	}
	if (winWidth > leftHide) {
		$("#chat_left").removeClass("cleft2").addClass("cleft").css("display", "table-cell");
		$("#warp_show_chat").css({ "height": cb });
		$(".pheight").css('height', ch2);
		$(".left_bar_ctn").hide();
	}
	else {
		$("#chat_left").removeClass("cleft").addClass("cleft2");
		$("#warp_show_chat").css({ "height": cb });
		$(".pheight").css('height', ch3);
		$(".left_bar_ctn").show();
	}
	if (winWidth > rightHide) {
		$("#chat_right").removeClass("cright2").addClass("cright").css("display", "table-cell");
		$(".prheight").css('height', ch2);
		$(".crheight").css('height', ch2 - panelBar);
	}
	else {
		$("#chat_right").removeClass("cright").addClass("cright2");
		$(".prheight").css('height', ch3);
		$(".crheight").css('height', ch3 - panelBar);
	}
}
chatInput = function () {
	$('#content').val('');
	if ($(window).width() > 768 && $(window).height() > 480) {
		$('#content').focus();
	}
}
resizeScroll = function () {
	var m = $('#show_chat ul');
	m.scrollTop(m.prop("scrollHeight"));
}
adjustSide = function () {
	var winHeight = $(window).height();
	var sideTop = $('#side_top').outerHeight();
	$('#side_inside').css({ "height": winHeight - sideTop });
}
adjustSubMenu = function () {
	$('#side_menu').hide();
}
adjustHeight();
adjustSide();
hidePanel = function () {
	var wh = $(window).width();
	if (wh < leftHide2) {
		$("#chat_left").hide();
	}
	if (wh < rightHide2) {
		if (!$(".boom_keep:visible").length) {
			$("#chat_right").hide();
		}
	}
}
forceHidePanel = function () {
	var wh = $(window).width();
	if (wh < leftHide2) {
		$("#chat_left").hide();
	}
	if (wh < rightHide2) {
		$("#chat_right").hide();
	}
}
closeLeft = function () {
	if ($(window).width() < leftHide2 && $('#chat_left:visible').length) {
		$('#chat_left').toggle();
	}
}
saveRoom = function () {
	$.post('system/action_room.php', {
		save_room: '1',
		set_room_name: $('#set_room_name').val(),
		set_room_description: $('#set_room_description').val(),
		set_room_password: $('#set_room_password').val(),
		set_room_player: $('#set_room_player').val(),
		token: utk
	}, function (response) {
		if (response == 1) {
			callSaved(system.saved, 1);
		}
		if (response == 2) {
			callSaved(system.roomExist, 3);
		}
		if (response == 3) {
			location.reload();
		}
		if (response == 4) {
			callSaved(system.roomName, 3);
		}
		if (response == 0) {
			callSaved(system.error, 3);
		}
	});
}

backHome = function () {
	$.post('system/action_room.php', {
		leave_room: '1',
		token: utk,
	}, function (response) {
		location.reload();
	});
}

$( window ).resize(function () {
	adjustHeight();
	resizeScroll();
	hidePanel();
	resetAvMenu();
	console.log("Resized");
});

//////////////////////////////////////////////////////////// DOCUMENT READY FUNCTION //////////////////////////////////////////////
$(document).ready(function () {
	$(function () {
		if ($(window).width() > 1024) {
			$("#private_box").draggable()({
				handle: "#private_top",
				containment: "document",
			}
			);
		}
	});

	$('body').css('overflow', 'hidden');
	userlist = setInterval(userReload, 30000);
	friendlis = setInterval(myFriends, 30000);
	chatLog = setInterval(chatReload, speed);
	userReload();
	chatReload();
	checkSubItem();
	adjustHeight();
	checkPrivSubItem();
	adjustSide();
	adjustPanelWidth();

	$('#main_input').submit(function (event) {
		var message = $('#content').val();
		if (message == '') {
			event.preventDefault();
		}
		else if (/^\s+$/.test(message)) {
			event.preventDefault();
			chatInput();
		}
		else {
			chatInput();
			if (waitReply == 0) {
				waitReply = 1;
				if (message.match("^\/")) {
					processChatCommand(message);
				}
				else {
					processChatPost(message);
				}
			}
			else {
				event.preventDefault();
			}
		}
		return false;
	});

	$('#private_input').submit(function (event) {
		var target = $('#get_private').attr('value');
		var message = $('#message_content').val();
		$('#message_content').val('');
		if (message == '') {
			pWait = 0;
			event.preventDefault();
		}
		else if (/^\s+$/.test(message)) {
			pWait = 0;
			event.preventDefault();
		}
		else {
			if (pWait == 0) {
				pWait = 1;
				$.post('system/private_process.php', {
					target: target,
					content: message,
					token: utk,
				}, function (response) {
					if (response == 20) {
						$('#message_content').focus();
						callSaved(system.cannotContact, 3);
					}
					else if (response == 100) {
						checkRm(2);
					}
					else {
						$('#message_content').focus();
						$("#private_content ul").append(response);
						scrollPriv(1);
					}
					pWait = 0;
				});
			}
			else {
				event.preventDefault();
			}
		}
		return false;
	});

	$(document).on('click', '#save_room', function () {
		saveRoom();
	});

	$(document).on('click', '#back_home', function () {
		backHome();
	});
});
////////////////////////////////////////////////// END DOC ////////////////////////////

privDown = function (v) {
	if (v > 0) {
		if ($('#dpriv:visible').length) {
			var cval = parseInt($('#dpriv_notify').text());
			var nval = cval + v;
			$('#dpriv_notify').text(nval).show();
		}
	}
}
resetRightPanel = function () {
	$('.panel_option').removeClass('panel_selected');
	$('#users_option').addClass('panel_selected');
	userReload(1);
}

beautyLogs = function () {
	$(".ch_logs").removeClass("log2");
	$(".ch_logs:visible:even").addClass("log2");
}
logsControl = function () {
	if ($('#show_chat').attr('value') == 1) {
		var countLog = $('.ch_logs').length;
		var countLimit = 60;
		var countDiff = countLog - countLimit;
		if (countDiff > 0 && countDiff % 2 === 0) {
			$('#chat_logs_container').find('.ch_logs:lt(' + countDiff + ')').remove();
			moreMain = 1;
		}
	}
}
hideMenu = function (id) {
	$('#' + id).hide();
}
updateStatus = function (id) {
	$.ajax({
		url: "system/action/action_profile.php",
		type: "post",
		cache: false,
		dataType: 'json',
		data: {
			update_status: id,
		},
		success: function (response) {
			if (response.code == 1) {
				$('.status_icon').attr('src', response.icon);
				$('.status_text').text(response.text);
				hideModal();
				console.log(response.icon);
			}
			else {
				return false;
			}
		},
		error: function () {
			return false;
		}
	});
}
saveInfo = function () {
	$.ajax({
		url: "system/action/action_profile.php",
		type: "post",
		cache: false,
		dataType: 'json',
		data: {
			save_info: 1,
			age: $('#set_profile_age').val(),
			gender: $('#set_profile_gender').val(),
			token: utk
		},
		success: function (response) {
			if (response.code == 1) {
				$('.avatar_profile').attr('src', response.av);
				$('.avatar_profile').attr('href', response.av);
				$('.glob_av').attr('src', response.av);
				callSaved(system.saved, 1);
				hideOver();
			}
			else {
				callSaved(system.error, 3);
			}
		},
		error: function () {
			callSaved(system.error, 3);
		}
	});
}
saveNameColor = function (newColor) {
	$.post('system/action/action_profile.php', {
		my_username_color: $('.user_color').attr('data'),
		my_username_font: $('#fontitname').val(),
	}, function (response) {
		if (response == 1) {
			callSaved(system.saved, 1);
		} else {
			callSaved(system.error, 3);
		}
	});
}
overModal = function (r, s) {
	if (!s) {
		s = 400;
	}
	if (s == 0) {
		s = 400;
	}
	$('.over_modal_in').css('max-width', s + 'px');
	$('#over_modal_content').html(r);
	$('#over_modal').show();
	offScroll();
	selectIt();
}
closeOverModal = function () {
	$('#over_modal_content').html(" ");
	$('#over_modal').hide();
}
getDisplaySetting = function () {
	$.post('system/box/display.php', {
		token: utk,
	}, function (response) {
		if (response == 0) {
			return false;
		}
		else {
			overModal(response, 460);
		}
	});
}
changeUsername = function () {
	$.post('system/box/edit_name.php', {
		token: utk,
	}, function (response) {
		overModal(response);
	});
}
changeColor = function () {
	$.post('system/box/edit_color.php', {
		token: utk,
	}, function (response) {
		if (response == 0) {
			return false;
		}
		else {
			overModal(response);
		}
	});
}
getLocation = function () {
	$.post('system/box/location.php', {
		token: utk,
	}, function (response) {
		if (response == 0) {
			return false;
		} else {
			overModal(response, 460);
		}
	});
}
getPrivateSettings = function () {
	$.post('system/box/private_settings.php', {
		token: utk,
	}, function (response) {
		if (response == 0) {
			return false;
		}
		else {
			overModal(response, 460);
		}
	});
}
getSoundSetting = function () {
	$.post('system/box/sound.php', {
		token: utk,
	}, function (response) {
		overModal(response, 380);
	});
}
changeInfo = function () {
	$.post('system/box/edit_info.php', {
		token: utk,
	}, function (response) {
		overModal(response);
	});
}
changeAbout = function () {
	$.post('system/box/edit_about.php', {
		token: utk,
	}, function (response) {
		overModal(response, 500);
	});
}
changeMood = function () {
	$.post('system/box/edit_mood.php', {
		token: utk,
	}, function (response) {
		overModal(response);
	});
}
newUsername = function () {
	var myNewName = $('#new_username_text').val();
	$.post('system/action/action_profile.php', {
		edit_username: 1,
		new_name: myNewName,
		token: utk,
	}, function (response) {
		if (response == 1) {
			callSaved(system.saved, 1);
			$("#pro_name").text(myNewName);
			hideOver();
		}
		else if (response == 2) {
			callSaved(system.invalidUsername, 3);
			$('#my_new_username').val('');
		}
		else if (response == 3) {
			callSaved(system.usernameExist, 3);
			$('#my_new_username').val();
		}
		else {
			callSaved(system.error, 3);
			console.log(response);
			hideOver();
		}
	});
}
grantRoom = function () {
	$('.room_granted').removeClass('nogranted');
}
ungrantRoom = function () {
	$('.room_granted').addClass('nogranted');
}
innactiveControl = function (cPost) {
	inactiveStart = 2;
	inMaxStaff = 2;
	inMaxUser = 3;
	inIncrement = 125;
	cLatency = (Date.now() - cPost);
	sp = parseInt(speed);
	nsp = sp + ((curActive - inactiveStart) * inIncrement);
	msp = sp * inMaxUser;
	if (waliAllow(4)) {
		msp = sp * inMaxStaff;
	}
	if (nsp > msp) {
		nsp = msp;
	}
	if (balStart > 0 && curActive >= inactiveStart) {
		clearInterval(chatLog);
		chatLog = setInterval(chatReload, nsp);
		actSpeed = nsp;
	}
	else {
		clearInterval(chatLog);
		chatLog = setInterval(chatReload, sp);
		actSpeed = sp;
	}
	$('#current_speed').text(actSpeed);
	$('#current_latency').text(cLatency);
	$('#logs_counter').text($('.ch_logs').length);
}
tabNotify = function () {
	if (focused == false) {
		PageTitleNotification.On();
	}
}
scrollPriv = function (z) {
	var p = $('#private_content');
	if (z == 1 || $('#private_content').attr('value') == 1) {
		p.scrollTop(p.prop("scrollHeight"));
	}
}
chatReload = function () {
	var cPosted = Date.now();
	var priv = $('#get_private').attr('value');
	logsControl();
	$.ajax({
		url: "system/chat_log.php",
		type: "post",
		cache: false,
		timeout: speed,
		dataType: 'json',
		data: {
			fload: fload,
			caction: cAction,
			last: lastPost,
			snum: snum,
			preload: privReload,
			priv: priv,
			lastp: lastPriv,
			pcount: pCount,
			room: user_room,
			notify: globNotify,
			token: utk
		},
		success: function (response) {
			if ('check' in response) {
				if (response.check == 99) {
					location.reload();
					return false;
				}
				else if (response.check == 199) {
					return false;
				}
			}
			else {
				var mLogs = response.mlogs;
				var mLast = response.mlast;
				var cact = response.cact;
				var pLogs = response.plogs;
				var pLast = response.plast;
				var getPcount = response.pcount;
				speed = response.spd;
				inOut = response.acd;

				if (response.act != userAction) {
					location.reload();
				}
				else {
					if (mLogs.indexOf("system__clear") >= 1) {
						$("#show_chat ul").html(mLogs);
						if (fload == 1) {
							clearPlay();
						}
						fload = 1;
					}
					else {
						$("#show_chat ul").append(mLogs);
						if (fload == 1) {
							if (mLogs.indexOf("my_notice") >= 1) {
								usernamePlay();
							}
							if (mLogs.indexOf("system__join") >= 1) {
								joinPlay();
							}
							if (mLogs.indexOf("system__leave") >= 1) {
								leavePlay();
							}
							if (mLogs.indexOf("system__action") >= 1) {
								actionPlay();
							}
							if (mLogs.indexOf("public__message") >= 1) {
								messagePlay();
								tabNotify();
							}
						}
						scrollIt(fload);
						fload = 1;
					}
					cAction = cact;
					lastPost = mLast;
					beautyLogs();
					if ('del' in response) {
						var getDel = response.del;
						for (var i = 0; i < getDel.length; i++) {
							$("#log" + getDel[i]).remove();
						}
					}
					if (response.curp == $('#get_private').attr('value')) {
						if (privReload == 1) {
							if (pLogs == '') {
								$('#private_content ul').html('');
							}
							else {
								$('#private_content ul').html(pLogs);
							}
							scrollPriv(privReload);
							lastPriv = pLast;
							privReload = 0;
							morePriv = 1;
						}
						else {
							if (pLogs == '' || lastPriv == pLast) {
								scrollPriv(privReload);
							}
							else {
								if (response.curp == priv) {
									$("#private_content ul").append(pLogs);
									privDown($(pLogs).find('.target_private').length);
								}
								scrollPriv(privReload);
							}
							if (getPcount !== pCount) {
								privatePlay();
								pCount = getPcount;
								tabNotify();
							}
							else {
								pCount = getPcount;
							}
							lastPriv = pLast;
						}
					}
					if ('top' in response) {
						var newTopic = response.top;
						if (newTopic != '' && newTopic != actualTopic) {
							$("#show_chat ul").append(newTopic);
							actualTopic = newTopic;
							scrollIt(fload);
						}
					}
					if (response.pico != 0) {
						$("#notify_private").text(response.pico);
						$('#notify_private').show();
					}
					else {
						$('#notify_private').hide();
					}
					if ('use' in response) {
						var friendsCount = response.friends;
						var newsCount = response.news;
						var noticeCount = response.notify;
						var reportCount = response.report;
						var newNotify = response.nnotif;
						if (newsCount > 0) {
							$('#news_notify').text(newsCount).show();
							if (!$('#chat_left:visible').length) {
								$('#bottom_news_notify').text(newsCount).show();
							}
							if (notifyLoad > 0) {
								if (newsCount > curNews) {
									newsPlay();
								}
							}
						}
						else {
							$('#news_notify').hide();
							$('#bottom_news_notify').hide();
						}
						if (reportCount > 0) {
							$('#report_notify').text(reportCount).show();
						}
						else {
							$('#report_notify').hide();
						}
						if (friendsCount > 0) {
							$("#notify_friends").text(friendsCount).show();
						}
						else {
							$("#notify_friends").hide();
						}
						if (noticeCount > 0) {
							$("#notify_notify").text(noticeCount).show();
						}
						else {
							$("#notify_notify").hide();
						}
						if (notifyLoad > 0) {
							if (noticeCount > curNotify || friendsCount > curFriends || reportCount > curReport) {
								notifyPlay();
							}
						}
						curNotify = noticeCount;
						curFriends = friendsCount;
						curReport = reportCount;
						curNews = newsCount;
						globNotify = newNotify;
						notifyLoad = 1;
					}
					if ('rset' in response) {
						grantRoom();
					}
					else {
						ungrantRoom();
					}
					if ('role' in response) {
						roomRank = response.role;
					}
					else {
						roomRank = 0;
					}
					if ('rm' in response) {
						checkRm(response.rm);
					}
					else {
						checkRm(0);
					}
					innactiveControl(cPosted);
					systemLoaded = 1;
				}
			}
		},
		error: function () {
			return false;
		}
	});
}


getPrivate = function () {
	$.post('system/box/private_notify.php', {
		token: utk,
	}, function (response) {
		showEmptyModal(response, 400);
	});
}

friendRequest = function () {
	$('#notify_friends').hide();
	$.post('system/box/friend_request.php', {
		token: utk,
	}, function (response) {
		showModal(response);
		curFriends = 0;
	});
}
getNotification = function () {
	$('#notify_notify').hide();
	$.post('system/box/notification.php', {
		token: utk,
	}, function (response) {
		showModal(response, 400);
		curNotify = 0;
	});
}

acceptFriend = function (t, friend) {
	$.post("system/system_action.php", {
		accept_friend: friend,
		token: utk,
	}, function (response) {
		if (response == 1) {
			$(t).parent().remove();
			if ($('.friend_request').length < 1) {
				hideModal();
			}
		}
		else {
			$(t).parent().remove();
		}
	});
}
declineFriend = function (t, id) {
	$.post("system/system_action.php", {
		remove_friend: id,
		token: utk,
	}, function (response) {
		$(t).parent().remove();
		if ($('.friend_request').length < 1) {
			hideModal();
		}
	});
}
uploadStatus = function (target, type) {
	if (type == 2) {
		$("#" + target).prop('disabled', true);
	}
	else {
		$("#" + target).prop('disabled', false);
	}
}
var waitUpload = 0;
uploadChat = function () {
	var file_data = $("#chat_file").prop("files")[0];
	var filez = ($("#chat_file")[0].files[0].size / 1024 / 1024).toFixed(2);
	if (filez > fmw) {
		callSaved(system.fileBig, 3);
	}
	else if ($("#chat_file").val() === "") {
		callSaved(system.noFile, 3);
	}
	else {
		if (waitUpload == 0) {
			uploadStatus('chat_file', 2);
			waitUpload = 1;
			var form_data = new FormData();
			form_data.append("file", file_data)
			form_data.append("token", utk)
			form_data.append("zone", 'chat')
			$.ajax({
				url: "system/file_chat.php",
				dataType: 'script',
				cache: false,
				contentType: false,
				processData: false,
				data: form_data,
				type: 'post',
				success: function (response) {
					if (response == 1) {
						callSaved(system.wrongFile, 3);
					}
					$("#chat_file").val("");
					uploadStatus('chat_file', 1);
					waitUpload = 0;
				},
				error: function () {
					$("#chat_file").val("");
					uploadStatus('chat_file', 1);
					waitUpload = 0;
				}
			})
		}
		else {
			return false;
		}
	}
}
var regSpinner = '<i class="fa fa-spinner fa-spin fa-fw reg_spinner"></i>';
var largeSpinner = '<div class="large_spinner"><i class="fa fa-spinner fa-spin fa-fw boom_spinner"></i></div>';
