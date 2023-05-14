appAvMenu = function(type, icon, text, pCall){
	var cMenu = '<div data="" value="" data-av="" class="avset avitem" onclick="'+pCall+'"><i class="fa fa-'+icon+'"></i> '+text+'</div>';
	$('.av'+type).append(cMenu);
}
renderAvMenu = function(elem, uid, uname, urank, ubot, uflag, cover, age, gender){
	var avt = $(elem).find('.avav').attr('src');
	$('#action_menu .avset').attr('data', uid);
	$('#action_menu .avset').attr('value', uname);
	$('#action_menu .avset').attr('data-av', avt);
	$('#action_menu .avusername').text(uname);
	$('#action_menu .avavatar').attr('src', avt);
	console.log(cover);
	if(cover != '' && cardCover > 0){
		$('#action_menu .avbackground').css('background-image', 'url("' + cover + '")');
	}
	else {
		$('#action_menu .avbackground').css('background-image', '');
	}
	if(uflag != '' && uflag != 'ZZ'){
		$('.avflag').show();
		$('#action_menu .avflag').attr('src', 'system/location/flag/'+uflag+'.png');
	}
	else {
		$('.avflag').hide();
	}
	if(age > 0){
		$('#action_menu .avage').text(age+' '+userAge);
	}
	else {
		$('#action_menu .avage').text('');
	}
	if(gender != ''){
		$('#action_menu .avgender').text(gender);
	}
	else {
		$('#action_menu .avgender').text('');
	}
	var avHeight = 0;
	var avDrop = '';
	$("#action_menu .avheader").each(function(){
		avDrop += $(this)[0].outerHTML;
	});
	$("#action_menu .avinfo").each(function(){
		avDrop += $(this)[0].outerHTML;
	});
	if(uid == user_id){
		$("#action_menu .avself").each(function(){
			avDrop += $(this)[0].outerHTML;
		});
	}else if(isStaff(user_rank) && user_rank > urank){
		$("#action_menu .avstaff").each(function(){
			avDrop += $(this)[0].outerHTML;
		});
	}else {
		$("#action_menu .avother").each(function(){
			avDrop += $(this)[0].outerHTML;
		});
	}
	return avDrop;
}
dropUser = function(elem, uid, uname, urank, ubot, uflag, cover, age, gender){
	var avDrop = renderAvMenu(elem, uid, uname, urank, ubot, uflag, cover, age, gender);
	$('#avcontent').html(avDrop);

	if($('#av_menu').css('left') != '-5000px' && elem == avCurrent){
		resetAvMenu();
	}
	else {
		avCurrent = elem;
		var zHeight = $(window).height();
		var zWidth = $(window).width();
		var offset = $(elem).offset();
		var emoWidth = $(elem).width();
		var emoHeight = $(elem).outerHeight();
		var avMenu = $('#avcontent').outerHeight();
		var avWidth = $('#av_menu').width();
		var footHeight = $('#my_menu').outerHeight();
		var inputHeight = $('#top_chat_container').outerHeight();
		var avSafe = avMenu + footHeight;
		var avLeft = offset.left + 10;
		var leftSafe = zWidth - avWidth;
		if(offset.top > zHeight - avSafe){
			var avTop = zHeight - avSafe;
		}
		else {
			var avTop = offset.top + emoHeight - 10;
		}
		if(leftSafe > emoWidth){
			avLeft = offset.left - avWidth + 10;
		}
		$('#av_menu').css({
			'left': avLeft,
			'top': avTop,
			'height': avMenu,
			'z-index': 202,
		});
	}	
}
var avCurrent = '';
avMenu = function(elem, uid, uname, urank, ubot, uflag, cover, age, gender){
	var avDrop = renderAvMenu(elem, uid, uname, urank, ubot, uflag, cover, age, gender);
	$('#avcontent').html(avDrop);
	
	if($('#av_menu').css('left') != '-5000px' && elem == avCurrent){
		resetAvMenu();
	}
	else {
		avCurrent = elem;
		var zHeight = $(window).height();
		var offset = $(elem).offset();
		var emoWidth = $(elem).width();
		var emoHeight = $(elem).height();
		var avMenu = $('#avcontent').outerHeight();
		var avWidth = $('#av_menu').width();
		var footHeight = $('#my_menu').outerHeight();
		var inputHeight = $('#top_chat_container').outerHeight();
		var avSafe = avMenu + footHeight + inputHeight;
		if(offset.top > zHeight - avSafe){
			var avTop = zHeight - avSafe - 5;
		}
		else {
			var avTop = offset.top;
		}
		var avLeft = offset.left + emoWidth + 5;
		$('#av_menu').css({
			'left': avLeft,
			'top': avTop,
			'height': avMenu,
			'z-index': 99,
		}, 100);
	}
}
getProfile = function(profile){
	$.post('system/box/profile.php', { 
		get_profile: profile,
		cp: curPage,
		token: utk,
	}, function(response){
		if(response == 1){
			return false;
		}
		if(response == 2){
			callSaved(system.noUser, 3);
		}else{
			showEmptyModal(response, 580);
		}
	});
	/*
	$.post('system/box/profile.php',{get_profile: profile}, function(response){
		showEmptyModal(response, 580);
	});
	*/
}
closeLeft = function(){
	if($(window).width() < leftHide2 && $('#chat_left:visible').length){
		$('#chat_left').toggle();
	}	
}

closeTrigger = function(){
	$('.drop_list').slideUp(100);
}

showPrivate = function(){
	$('#private_box').show();
	console.log("Test");
}
hidePanel = function(){
	var wh = $(window).width();
	if(wh < leftHide2){
		$("#chat_left").hide();
	}
	if(wh < rightHide2){
		if(!$(".boom_keep:visible").length){
			$("#chat_right").hide();
		}
	}
}
forceHidePanel = function(){
	var wh = $(window).width();
	if(wh < leftHide2){
		$("#chat_left").hide();
	}
	if(wh < rightHide2){
		$("#chat_right").hide();
	}
}
openPrivate = function(who, whoName, whoAvatar){
	if(who != user_id){
		$('#get_private').attr('value', who);
		$('#private_av, #dpriv_av').attr('src', whoAvatar);
		$('#private_av').attr('onclick', 'getProfile('+who+')');
		if(!$('#private_box:visible').length){
			$('#private_box').toggle();
			resetPrivate();
		}
		$('#private_name').text(whoName);
		forceHidePanel();
	}
		else {
		return false;
	}
}
resetPrivate = function(){
	$('#private_box').removeClass('privhide');
	$('#dpriv').addClass('privhide');
	$('#dpriv_notify').text('0').hide();
}
closeList = function(){
	resetAvMenu();
	hidePanel();
}
$(document).on('click', '.gprivate', function(){
	morePriv = 0;
	var thisPrivate = $(this).attr('data');
	var thisUser = $(this).attr('value');
	var thisAvatar = $(this).attr('data-av');
	$('#private_content ul').html(largeSpinner);
	openPrivate(thisPrivate, thisUser, thisAvatar);
	closeList();
	hideModal();
	privReload = 1;
	lastPriv = 0;
});
$(document).on('click', '#private_close', function(){
	$('#private_content ul').html(largeSpinner);
		$('#get_private').attr('value', 0);
		$('#private_name').text('');
		$('#private_box').toggle();
		lastPriv = 0;
});
togglePrivate = function(type){
	if(type == 1){
		$('#dpriv').removeClass('privhide');
		$('#private_box').addClass('privhide');
	}
	if(type == 2){
		resetPrivate();
	}
}
$(document).on('click', '.get_info', function(){
	var profile = $(this).attr('data');
	closeTrigger();
	getProfile(profile);
	resetAvMenu();
});
toggleLeft = function(){
    $('#chat_left').toggle();
}
openLogout = function(){
	/*
	$.post('system/box/logout.php', { 
		token: utk,
		}, function(response) {
				showModal(response);
	});*/
	location = ("system/logout.php");
}
logOut = function(){
	$.post('system/logout.php', { 
		logout_from_system: 1,
		token: utk,
		}, function(response) {
			if(response == 1){
				location.reload();
			}
	});
}
toggleRight = function(){
    $('#chat_right').toggle();
}
offScroll = function(){
	if(curPage != 'chat'){
		$('body').addClass('modal_open');
	}
}
onScroll = function(){
	if(curPage != 'chat'){
		$('body').removeClass('modal_open');
	}
	else {
		$('body').css('overflow', 'hidden');
	}
}
hideAll = function(){
	$('.hideall').hide();
	$('.sysmenu').hide();
}
modalTop = function(){
	$(".modal_back").animate({ scrollTop: 0 }, "fast");
}

showModal = function(r,s){
	if(!s){
		s = 400;
	}
	if(s == 0){
		s = 400;
	}
	$('.small_modal_in').css('max-width', s+'px');
	$('#small_modal_content').html(r);
	$('#small_modal').show();
	offScroll();
	modalTop();
	selectIt();
}
resetModal = function(){
	$('#small_modal_content').html('');
}
showEmptyModal = function(r,s){
	if(!s){
		s = 400;
	}
	if(s == 0){
		s = 400;
	}
	$('.large_modal_in').css('max-width', s+'px');
	$('#large_modal_content').html(r);
	$('#large_modal').show();
	offScroll();
	modalTop();
	selectIt();
}
hideModal = function(){
	$('#small_modal_content, #large_modal_content').html('');
	$('#small_modal, #large_modal').hide();
	onScroll();
}
openStatusList = function(){
	$.post('system/box/status.php', function(response) {
		showModal(response, 320);
});
}
closeStatusList = function(){
    $('#small_modal').hide();
}

showMenu = function(id){
	if($('#'+id+':visible').length){
		$('#'+id).hide();
	}
	else {
		$('#'+id).show();
	}
	$('.sysmenu').each(function(){
		if($(this).attr('id') != id){
			$(this).hide();
		}
	});
	
}

editProfile = function(){
	$.post('system/box/edit_profile.php', function(response) {
			showEmptyModal(response, 580);
			hideMenu("chat_main_menu");
	});
}

chatRightIt = function(data){
	$('#chat_right_data').html(data);
}
panelIt = function(size, h){
	hideAll();
	if(!h){
		h = 0;
	}
	else {
		$('.panel_option').removeClass('panel_selected');
	}
	if(size == 0){
		$('#chat_right').css('width', defRightWidth+'px');
	}
	else {
		$('#chat_right').css('width', size+'px');
	}
	// chatRightIt(largeSpinner);
	if(!$('#chat_right:visible').length){
		$('#chat_right').toggle();
	}
}
userReload = function(type){
	if($('#container_user:visible').length || type == 1 || firstPanel == 'userlist'){
		if(type == 1){
			panelIt(0);
		}
		$.post('system/panel/user_list.php', function(response) {
			chatRightIt(response);
			firstPanel = '';
		});
	}
}

getRoomList = function(){
	panelIt(0,1);
	$.post('system/panel/room_list.php',function(response) {
		chatRightIt(response);
	});
}
scrollIt = function(f){
	var t = $('#show_chat ul');
	if(f == 0 || $('#show_chat').attr('value') == 1){
		t.scrollTop(t.prop("scrollHeight"));
	}
}
reportChatLog = function(){
	var data = $('#log_menu_content .log_menu_item').attr('data');
	var data_m = $('#log_menu_content .log_menu_item').attr('data-m');
	$.post(`system/chat.php?action=reportLog&uid=${data}&content=${data_m}`, function(response){
		resetLogMenu();
	});
}
deleteLog = function(){
	var data = $('#log_menu_content .log_menu_item').attr('data')
	$.post(`system/chat.php?action=deleteLog&cid=${data}}`, function(response){
		resetLogMenu();
	});
}
logMenu = function(elem,id,d,p){
	$('#log_menu_content .log_menu_item').attr('data', id);
	var menuLog = '';
	if(p == 1){
		$("#log_menu_content .log_report").each(function(){
			menuLog += $(this)[0].outerHTML;
		});
	}
	if(d == 1){
		$("#log_menu_content .log_delete").each(function(){
			menuLog += $(this)[0].outerHTML;
		});
	}
	$('#logmenu').html(menuLog);
	if($('#log_menu').css('left') != '-5000px'){
		resetLogMenu();
	}
	else {
		var zHeight = $(window).height();
		var offset = $(elem).offset();
		var emoWidth = $(elem).width();
		var emoHeight = $(elem).height();
		var avMenu = $('#logmenu').outerHeight();
		var avWidth = $('#log_menu').width();
		var footHeight = $('#my_menu').outerHeight();
		var inputHeight = $('#top_chat_container').outerHeight();
		var avSafe = avMenu + footHeight + inputHeight;
		if(offset.top > zHeight - avSafe){
			var avTop = zHeight - avSafe - 5;
		}
		else {
			var avTop = offset.top;
			var avLeft = offset.left - avWidth;
		}
		$('#log_menu').css({
			'left': avLeft,
			'top': avTop,
			'height': avMenu,
		});
	}	
}
resetLogMenu = function(){
	$('#logmenu').html('');
	$('#log_menu').css({
		'left': '-5000px',
	});	
}
resetAvMenu = function(){
	$('.avavatar').attr('src', '');
	$('#av_list').html('');
	$('#av_menu').css({
		'left': '-5000px',
	});	
}
function getReports(){
	$.post('system/chat.php?action=getReports', function(response){
		
	});
}
hideAll = function(){
	$('.hideall').hide();
	$('.sysmenu').hide();
}
hideOver = function(){
	$('#over_modal_content, #over_emodal_content').html('');
	$('#over_modal, #over_emodal').hide();
	if(!$('#small_modal:visible').length && !$('#large_modal:visible').length){
		onScroll();
	}
}
overEmptyModal = function(r,s){
	hideAll();
	hideOver();
	if(!s){
		s = 400;
	}
	if(s == 0){
		s = 400;
	}
	$('.over_emodal_in').css('max-width', s+'px');
	$('#over_emodal_content').html(r);
	$('#over_emodal').show();
	offScroll();
	selectIt();
}
getActions = function(id){
	$.post('system/box/action_main.php', {
		id: id,
		cp: curPage,
		}, function(response) {
			if(response == 0){
				callSaved(system.cannotUser, 3);
			}
			else if(response == 1){
			}
			else {
				overEmptyModal(response,400);
			}
	});
}
userlist = setInterval(userReload, 10000);
$(document).on('click', '.tab_menu_item', function(){
	$(this).parent().find('.tab_menu_item').removeClass('tab_selected');
	$(this).addClass('tab_selected');
	$('#'+$(this).attr('data')+' .tab_zone').hide();
	$('#'+$(this).attr('data-z')).fadeIn(200);
	selectIt();
});

$(document).on('click', '.panel_option', function(){
		$('.panel_option').removeClass('panel_selected');
		$(this).addClass('panel_selected');
});
$(document).on('click', '.get_actions', function(){
	var id = $(this).attr('data');
	closeTrigger();
	getActions(id);
});
$('.close_over').click(function(){
	$('#over_modal').hide();	
});

openAddRoom = function(){
	var rtList = $('#add_room_list').html();
	showModal(rtList, 400);
}

deleteAvatar = function(){
	$.post('system/chat.php?action=deleteAv', function(response){

	});
}


function extractFilename(path) {
	if (path.substr(0, 12) == "C:\\fakepath\\")
	  return path.substr(12); // modern browser
	var x;
	x = path.lastIndexOf('/');
	if (x >= 0) // Unix-based path
	  return path.substr(x+1);
	x = path.lastIndexOf('\\');
	if (x >= 0) // Windows-based path
	  return path.substr(x+1);
	return path; // just the filename
  }
getTextOptions = function(){
	$.post('system/box/chat_text.php', function(response) {
			showModal(response);
			// closeLeft();
			$("#main_input_extra").toggle();
	});
}
closeOver = function(){
	$('#over_modal').hide();	
}


loadProMenu = function(){
	$('#pro_menu').toggle();
}

changeColor = function(){
	$.post('system/box/edit_color.php', function(response){
		overModal(response, 400);
	});
}

$("#ok_sub_item").click(function(){
	$("#main_input_extra").toggle();
});


$(document).on('click', `#create_room`, function(){
	var e = document.getElementById("roomRank");
	var room_rank = e.options[e.selectedIndex].value;
	var room_name = $('#rname').val();
	var room_pass = $('#rpass').val();
	var passBool = 0;
	var rPass = '';
	var room_desc = $('#rdisc').val();
	var new_room_rank = '';
	var room_ico = '';
	if(room_pass == ''){
		passBool = 0;
		rPass = '';
	}else{
		passBool = 1;
		rPass = room_pass;
	}
});
selectIt = function(){
	$("select:visible").selectBoxIt({ 
		autoWidth: false,
		hideEffect: 'fadeOut',
		hideEffectSpeed: 100
	});
}
$(document).on('click', '.reg_menu_item', function(){
	$(this).parent().find('.reg_menu_item').removeClass('reg_selected');
	$(this).addClass('reg_selected');
	$('#'+$(this).attr('data')+' .reg_zone').hide();
	$('#'+$(this).attr('data-z')).fadeIn(200);
	selectIt();
	console.log("Selected");
});
callSaved = function(type){
	var s = 3000;
	if(type == 1){
		s = 1000;
	}
	if($('.saved_data:visible').length){
		return false;
	}
	else {
		if(type == 1){
			$('.saved_data').removeClass('saved_warn saved_error').addClass('saved_ok');
			$('.saved_span').text('Saved');
		}
		if(type == 2){
			$('.saved_data').removeClass('saved_ok saved_error').addClass('saved_warn');
			$('.saved_span').text('Warning');
		}
		if(type == 3){
			$('.saved_data').removeClass('saved_warn saved_ok').addClass('saved_error');
			$('.saved_span').text('Error');
		}
		$('.saved_data').fadeIn(300).delay(s).fadeOut();
	}
}
saveNameColor = function(newColor){
	$.post('system/action/action_profile.php', {
		my_username_color: $('.user_color').attr('data'),
		my_username_font: $('#fontitname').val(),
		}, function(response) {
			if(response == 1){
				callSaved(system.saved, 1);
			}else {
				callSaved(system.error, 3);
			}
	});
}
$(document).on('click', '.name_choice, .choice', function() {	
	var curColor = $(this).attr('data');
	if($('.user_color').attr('data') == curColor){
		$('.wccheck').remove();
		$('.user_color').attr('data', 'user');
	}
	else {
		$('.wccheck').remove();
		$(this).append('<i class="wccheck fa fa-check"></i>');
		$('.user_color').attr('data', curColor);
	}
	previewName();
});

$(document).on('change', '#fontitname', function(){		
	previewName();
});

$(document).on('click', '.close_over, .cancel_over', function(){
	hideOver();
});
$(document).on('click', '.close_modal, .cancel_modal', function(){
	hideModal();
});

previewName = function(c){
	var n = $('.user_color').attr('data');
	var f = $('#fontitname').val();
	$('#preview_name').removeClass();
	$('#preview_name').addClass(n+' '+f);
}
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
// resetRoom = function(troom, nroom){
// 	user_room = troom;
// 	$("#show_chat ul").html('');
// 	waitJoin = 0;
// 	if(nroom == ''){
// 		nroom = docTitle;
// 	}
// 	document.title = nroom;
// 	docTitle = nroom;
// 	moreMain = 1;
// 	hideModal();
// 	if($(window).width() < rightHide2){
// 		toggleRight();
// 	}
// 	else {
// 		resetRightPanel();
// 	}
// }
setUserTheme = function(item){
	var theme = $(item).val();
	$.ajax({
		url: "system/action/action_profile.php",
		type: "post",
		cache: "false",
		dataType: 'json',
		data: {
			set_user_theme: theme,
			token: utk
		},
		success: function(response){
			$("#actual_theme").attr("href", "themes/" + response.theme + "/" + response.theme + ".css");
			$('#main_logo').attr('src', response.logo);
		}
	});
}
var curCall = '';
callSaved = function(text, type){
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
// $('#private_input').submit(function(event){
// 	var target = $('#get_private').attr('value');
// 	var message = $('#message_content').val();
// 	$('#message_content').val('');
// 	if(message == ''){
// 		pWait = 0;
// 		event.preventDefault();
// 	}
// 	else if (/^\s+$/.test(message)){
// 		pWait = 0;
// 		event.preventDefault();
// 	}
// 	else{
// 		if(pWait == 0){
// 			pWait = 1;
// 			$.post('system/private_process.php', {
// 				target: target,
// 				content: message,
// 				token: utk,
// 				}, function(response) {
// 					if(response == 20){
// 						$('#message_content').focus();
// 						callSaved(system.cannotContact, 3);
// 					}
// 					else if (response == 100){
// 						checkRm(2);
// 					}
// 					else {
// 						$('#message_content').focus();
// 						$("#private_content ul").append(response);
// 						scrollPriv(1);
// 					}
// 					pWait = 0;
// 			});
// 		}
// 		else {
// 			event.preventDefault();
// 		}
// 	}
// 	return false;
// });

/*

previewText = function(){
		var c = $('.color_choices').attr('data');
		var b = $('#boldit').val();
		var f = $('#fontit').val();
		$('#preview_text').removeClass();
		$('#preview_text').addClass(c+' '+b+' '+f);
	}

	$(document).on('click', '.user_choice', function() {	
		var curColor = $(this).attr('data');
		if($('.color_choices').attr('data') == curColor){
			$('.bccheck').remove();
			$('.color_choices').attr('data', '');
		}
		else {
			$('.bccheck').remove();
			$(this).append('<i class="fa fa-check bccheck"></i>');
			$('.color_choices').attr('data', curColor);
		}
		previewText();
	});

*/