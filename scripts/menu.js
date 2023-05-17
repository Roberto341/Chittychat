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

togglePrivate = function(type){
	if(type == 1){
		$('#dpriv').removeClass('privhide');
		$('#private_box').addClass('privhide');
	}
	if(type == 2){
		resetPrivate();
	}
}
toggleLeft = function(){
    $('#chat_left').toggle();
}
openLogout = function(){
	$.post('system/box/logout.php', { 
		token: utk,
		}, function(response) {
				showModal(response);
	});
	// location = ("system/logout.php");
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
	$.post('system/box/edit_profile.php', {
		token: utk,
	}, function(response) {
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
		$.post('system/panel/user_list.php',{
			token: utk,
		}, function(response) {
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
///////////////////////////////// DOCUMENT LOAD FUNCTION /////////////////////////////////////////////
$(document).ready(function(){
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
	$(document).on('click', '.get_info', function(){
		var profile = $(this).attr('data');
		closeTrigger();
		getProfile(profile);
		resetAvMenu();
	});
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
	$(document).on('click', '.user_choice', function() {	
		var curColor = $(this).attr('data');
		if($('.color_choices').attr('data') == curColor){
			$('.wccheck').remove();
			$('.color_choices').attr('data', '');
		}
		else {
			$('.wccheck').remove();
			$(this).append('<i class="fa fa-check wccheck"></i>');
			$('.color_choices').attr('data', curColor);
		}
		previewText();
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
	$(document).on('change', '#boldit', function(){		
		previewText();
	});
	
	$(document).on('change', '#fontit', function(){		
		previewText();
	});
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
	$(document).on('click', '.reg_menu_item', function(){
		$(this).parent().find('.reg_menu_item').removeClass('reg_selected');
		$(this).addClass('reg_selected');
		$('#'+$(this).attr('data')+' .reg_zone').hide();
		$('#'+$(this).attr('data-z')).fadeIn(200);
		selectIt();
	});
});

//////////////////////////// END DOC LOAD ///////////////////////////////////////
$('.close_over').click(function(){
	$('#over_modal').hide();	
});

openAddRoom = function(){
	$.post('system/box/create_room.php', {
		token: utk,
		}, function(response) {
			showModal(response);
	});
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

$("#ok_sub_item").click(function(){
	$("#main_input_extra").toggle();
});

selectIt = function(){
	$("select:visible").selectBoxIt({ 
		autoWidth: false,
		hideEffect: 'fadeOut',
		hideEffectSpeed: 100
	});
}

previewText = function(){
	var c = $('.color_choices').attr('data');
	var b = $('#boldit').val();
	var f = $('#fontit').val();
	$('#preview_text').removeClass();
	$('#preview_text').addClass(c+' '+b+' '+f);
}
previewName = function(c){
	var n = $('.user_color').attr('data');
	var f = $('#fontitname').val();
	$('#preview_name').removeClass();
	$('#preview_name').addClass(n+' '+f);
}
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
		cache: false,
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