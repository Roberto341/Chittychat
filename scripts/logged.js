system = {
	accessRequirement: "You do not meet the requirements to access this room",
	actionComplete: "Action completed",
	alreadyAction: "This action is already set",
	alreadyErase: "This post does not exist anymore",
	alreadyReported: "This post has already been reported",
	badActual: "Wrong actual password",
	badLogin: "Incorrect Username or Password",
	cannotContact: "You cannot contact this member at this time",
	cannotUser: "You cannot perform this action on the specified user",
	cantModifyUser: "You do not have right to modify this user",
	cleanComplete: "Clean complete",
	confirmedCommand: "Command successfully confirmed",
	dataExist: "Data already exists in the database",
	emailExist: "An account already exists with that email",
	emailSent: "Email sent. please check your email.",
	emptyField: "Some required field are empty",
	error: "An error occured",
	fileBig: "File size is too big",
	friendSent: "Your friend request has been sent",
	ignored: "User added to your ignore list",
	invalidCode: "Invalid code",
	invalidCommand: "That command does not exist.",
	invalidEmail: "Please enter valid email address",
	invalidUsername: "Invalid username. Please choose a new one.",
	maxReg: "You have reached the maximum allowed registrations.  Please try again later",
	maxRoom: "Sorry you have reached your room limit",
	missingRecaptcha: "Please complete the reCAPTCHA form below",
	newFriend: "Congratulations, you just made a new friend",
	newMessage: "New message",
	noBridge: "No bridge detected at specified location",
	noFile: "You must select a file",
	noResult: "No results found",
	noUser: "Sorry no user found with those details",
	notMatch: "New password is not matching",
	oops: "Oops! something strange happened. Please try again later",
	recoverySent: "Temporary password has been sent to your email",
	registerClose: "We are sorry we do not accept new registrations at the moment",
	reportLimit: "You have reached your report limit",
	reported: "Report sent thank you !",
	restrictedContent: "Posted data contains restricted content",
	roomBlock: "Sorry you cannot enter this room at the momment",
	roomDescription: "Room description is too short",
	roomExist: "This room name already exist",
	roomFull: "This room is full, please try another one",
	roomName: "Invalid room name",
	saved: "Saved",
	selAge: "Please select your age",
	selectSomething: "Please select something",
	shortPass: "Password is too short",
	siteConnect: "Please connect to the site to enter chat",
	somethingWrong: "Something wrong ... please wait for an administrator to review your account.",
	tooShort: "Search critera too short",
	updated: "Update completed",
	usernameExist: "Username already exists",
	wrongFile: "Sorry, this file is not accepted.",
	wrongPass: "Password incorrect"
};
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
var curCall = '';
callSaved = function(text, type){
	console.log(text);
	var s = 3000;
	if(type == 1){
		s = 1000;
	}
	if(text == curCall && $('.saved_data:visible').length){
		return false;
	}
	else {
		if(type == 1){
			$('.saved_data').removeClass('saved_warn saved_error').addClass('saved_ok');
		}
		if(type == 2){
			$('.saved_data').removeClass('saved_ok saved_error').addClass('saved_warn');
		}
		if(type == 3){
			$('.saved_data').removeClass('saved_warn saved_ok').addClass('saved_error');
		}
		$('.saved_span').text(text);
		$('.saved_data').fadeIn(300).delay(s).fadeOut();
		curCall = text;
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

boomAllow = function(rnk){
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
		if(boomAllow(rank)){
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
boomSound = function(snd){
	if(uSound.match(snd)){
		return true;
	}
}

sendMain = function(event){
	var message = $('#content').val();
	if(message == ''){
		event.preventDefault();
	}else{

		console.log(message);
	}
	return false;
}
