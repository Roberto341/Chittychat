resetRoom = function(troom, nroom){
	user_room = troom;
	$("#show_chat ul").html('');
	float = 0;
	lastPost = 0;
	waitJoin = 0;
	actualTopic = '';
	roomRank = 0;
	if(nroom == ''){
		nroom = docTitle;
	}
	document.title = nroom;
	docTitle = nroom;
	moreMain = 1;
	hideModal();
	if($(window).width() < rightHide2){
		toggleRight();
	}
	else {
		resetRightPanel();
	}
}
listAction = function(target, act){
	closeTrigger();
	if(act == 'ban'){
		banBox(target);
	}
	else if(act == 'kick'){
		kickBox(target);
	}
	else if(act == 'mute'){
		muteBox(target);
	}
	else if(act == 'change_rank'){
		adminGetRank(target);
	}
	else if(act == 'delete_account'){
		eraseAccount(target);
	}
	else {
		$.post('system/action.php', {
			take_action: act,
			target: target,
			}, function(response) {
				if(response == 0){
					callSaved(system.cannotUser, 3);
				}
				else if(response == 1){
					hideOver();
					callSaved(system.actionComplete, 1);
					processAction(act);
				}
				else if(response == 2){
					callSaved(system.alreadyAction, 3);
				}
				else {
					callSaved(system.error, 3);
				}
		});
	}
}

waliAllow = function(rnk){
	if(user_rank >= rnk){
		return true;
	}
	else {
		return false;
	}
}
var waitJoin = 0;
function switchRoom(room, pass, rank){
	if(curPage == 'chat'){
		if(room == user_room){
			return false;
		}
	}
	if(waitJoin == 0){
		waitJoin = 1;
		if(waliAllow(rank)){
			if(pass == 1){
				$.post('system/box/pass_room.php', {
					room_rank: rank,
					room_id: room,
					token: utk
				}, function(response){
					overModal(response);
					waitJoin = 0;
				});
			}
			else{
				$.ajax({
					url: "system/action_room.php",
					type: "post",
					cache: "false",
					dataType: 'json',
					data: {
						room: room,
						get_in_room: 1,
						token: utk
					},
					success: function(response){
						if(response.code == 10){
							if(curPage == 'chat'){
								resetRoom(response.id, response.name);
							}else{
								location.reload();
							}
						}
						else if(response.code == 99){
							callSaved(system.roomBlock, 3);
							waitJoin = 0;
						}
						else if(response.code == 3){
							callSaved(system.roomFull, 3);
						}
						else{
							waitJoin = 0;
							return false;
						}
					},
					error: function(response){
						callSaved(system.error, 3);
					}
				});
			}
		}
		else{
			callSaved(system.accessRequirement, 3);
			waitJoin = 0;
		}
	}
	else{
		return false;
	}
}
closeChatSub = function(){
	$('#main_input_extra').hide();
}
hideEmoticon = function(){
	$('#main_emoticon').hide();
}
roomBlock = function(){
	$('#content, #submit_button, #chat_file').prop('disabled', true);
	if ($('#chat_file').length){
		$("#chat_file")[0].setAttribute("onchange", "doNothing()");
	}
}
fullBlock = function(){
	$('#content, #submit_button, #chat_file, #private_send, #private_file, #message_content').prop('disabled', true);
	if ($('#chat_file').length){
		$("#chat_file")[0].setAttribute("onchange", "doNothing()");
	}
	if ($('#private_file').length){
		$("#private_file")[0].setAttribute("onchange", "doNothing()");
	}
	$(".add_post_container, .add_comment, .do_comment").remove();
}
unblockAll = function(){
	$('#content, #submit_button, #chat_file, #private_send, #private_file, #message_content').prop('disabled', false);
	if ($('#chat_file').length){
		$("#chat_file")[0].setAttribute("onchange", "uploadChat()");
	}
	if ($('#private_file').length){
		$("#private_file")[0].setAttribute("onchange", "uploadPrivate()");
	}
}
checkRm = function(rmval){
	if(rmval != curRm){
		if(rmval == 1){
			roomBlock();
		}
		else if(rmval == 2){
			fullBlock();
		}
		else {
			unblockAll();
		}
		curRm = rmval;
	}
}

processChatCommand = function(message){
	$.ajax({
		url: "system/chat_command.php",
		type: "post",
		cache: false,
		dataType: 'json',
		data: { 
			content: message,
		},
		success: function(response){
			if(typeof response != 'object'){
				waitReply = 0;
			}
			else {
				var code = response.code;
				if(code == 99){
					noAction();
				}
				if(code == 0){
					callSaved(system.cannotUser, 3);
				}
				else if(code == 1){
					callSaved(system.actionComplete, 1);
				}
				else if (code == 2){
					callSaved(system.alreadyAction, 3);
				}
				else if (code == 3){
					callSaved(system.noUser, 3);
				}
				else if (code == 4){
					callSaved(system.error, 3);
				}
				if(code == 10){
					getConsole();
				}
				else if(code == 12){
					$('.ch_logs').remove();
				}
				else if(code == 14){
					$("#show_chat ul").append(response.data);
					actualTopic = response.data;
					scrollIt(fload);
				}
				else if (code == 100){
					checkRm(2);
				}
				else if (code == 200){
					callSaved(system.invalidCommand, 3);
				}
				else if (code == 300){
					muteBox(response.data);
				}
				else if (code == 400){
					kickBox(response.data);
				}
				else if (code == 500){
					banBox(response.data);
				}
				else if (code == 1000){
					$('#name').val('');
					$("#show_chat ul").append(response.data);
					scrollIt(0);
				}
				else {
					noAction();
				}
				waitReply = 0;
			}
		},
		error: function(){
			waitReply = 0;		
		}
	});
}

processChatPost = function(message){
	$.post('system/chat_process.php', {
		content: message,
		snum: snum,
		token: utk,
		}, function(response) {
			if(response == ''){
			}
			else if (response == 100){
				checkRm(2);
			}
			else{
				$('#name').val('');
				$("#show_chat ul").append(response);
				scrollIt(0);
			}
			waitReply = 0;
	});
}
waliSound = function(snd){
	if(uSound.match(snd)){
		return true;
	}
}
var waitRoom = 0;
addRoom = function(){
	var rrname = $('#set_room_name').val();
	if(/^\s+$/.test(rrname) || rrname == ''){
		callSaved(system.emptyField, 3);
	}
	else{
		if(waitRoom == 0){
			waitRoom = 1;
			$.ajax({
				url: "system/action_room.php",
				type: "post",
				cache: "false",
				dataType: 'json',
				data: {
					set_name: $("#set_room_name").val(),
					set_type: $("#set_room_type").val(),
					set_pass: $("#set_room_password").val(),
					set_description: $("#set_room_description").val(),
					token: utk
				},
				success: function(response){
					if(response.code == 1){
						callSaved(system.error, 3);
					}
					else if(response.code == 2){
						callSaved(system.roomName, 3);
					}
					else if(response.code == 5){
						hideModal();
						callSaved(system.maxRoom, 3);
					}
					else if(response.code == 6){
						callSaved(system.roomExist, 3);
					}
					else if(response.code == 7){
						if(curPage == 'chat'){
							hideModal();
							resetRoom(response.id, response.name);
						}
						else{
							location.reload();
						}
					}
					else{
						waitRoom = 0;
						return false;
					}
					waitRoom = 0;
				},
				error: function(){
					callSaved(system.error, 3);
				}
			});
		}
		else{
			return false;
		}
	}
}
accessRoom = function(rt, rank){
	if(waliAllow(rank)){
		$.ajax({
			url: "system/action_room.php",
			type: "post",
			cache: false,
			dataType: 'json',
			data: { 
				pass: $('#pass_input').val(),
				room: rt,
				get_in_room: 1,
				token: utk
			},
			success: function(response){
				if(response.code == 10){
					if(curPage == 'chat'){
						resetRoom(response.id, response.name);
						hideOver();
					}
					else {
						location.reload();
					}
				}
				else if(response.code == 5){
					callSaved(system.wrongPass, 3);
					$('#pass_input').val('');
				}
				else if(response.code == 1){
					callSaved(system.error, 3);
				}
				else if(response.code == 2){
					callSaved(system.accessRequirement, 3);
				}
				else if(response.code == 4){
					callSaved(system.error, 3);
				}
				else if(response.code == 99){
					callSaved(system.roomBlock, 3);
				}
				else {
					callSaved(system.error, 3);
				}
			},
			error: function(){
				callSaved(system.error, 3);	
			}
		});
	}
	else {
		callSaved(system.accessRequirement, 3);
	}
}