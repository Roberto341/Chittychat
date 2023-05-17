selectIt = function(){
	$("select:visible").selectBoxIt({ 
		autoWidth: false,
		hideEffect: 'fadeOut',
		hideEffectSpeed: 100
	});
	console.log("Clicked");
}
modalTop = function(){
	$(".modal_back").animate({ scrollTop: 0 }, "fast");
}
offScroll = function(){
	if(curPage != 'chat'){
		$('body').addClass('modal_open');
	}
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

$(document).on('click', '#content, #submit_button', function(){
	hideEmoticon();
	closeChatSub();
	resetAvMenu();
	resetLogMenu();
});
$(document).on('click', '.close_modal, .cancel_modal', function(){
	hideModal();
});
hideModal = function(){
	$('#small_modal_content, #large_modal_content').html('');
	$('#small_modal, #large_modal').hide();
	onScroll();
}
onScroll = function(){
	if(curPage != 'chat'){
		$('body').removeClass('modal_open');
	}
	else {
		$('body').css('overflow', 'hidden');
	}
}

isStaff = function(rnk){
	if(rnk >= 4){
		return true;
	}
	else {
		return false;
	}
}


messagePlay = function(){
	if(waliSound(1)){
		document.getElementById('message_sound').play();
	}
}
clearPlay = function(){
	if(waliSound(1)){
		document.getElementById('clear_sound').play();
	}
}
joinPlay = function(){
	if(waliSound(1)){
		document.getElementById('join_sound').play();
	}
}
leavePlay = function(){
	if(waliSound(1)){
		document.getElementById('leave_sound').play();
	}
}
actionPlay = function(){
	if(waliSound(1)){
		document.getElementById('action_sound').play();
	}
}
whistlePlay = function(){
	if(waliSound(1)){
		document.getElementById('whistle_sound').play();
	}
}
privatePlay = function(){
	if(waliSound(2)){
		document.getElementById('private_sound').play();
	}
}
notifyPlay = function(){
	if(waliSound(3)){
		document.getElementById('notify_sound').play();
	}
}
usernamePlay = function(){
	if(waliSound(4)){
		document.getElementById('username_sound').play();
	}
}
newsPlay = function(){
	if(waliSound(3)){
		document.getElementById('news_sound').play();
	}
}

getLanguage = function(){
	$.post('system/box/language.php', {
		}, function(response) {
				showModal(response, 240);
	});
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

$(document).ready(function(){
	$(document).on('click', '.bswitch', function(){
		var cval = $(this).attr('data');
		var callback = $(this).attr('data-c');
		if(cval == 1){
			$(this).attr('data', 0);
			$(this).switchClass( "onswitch", "offswitch", 100);
			$(this).find('.bball').switchClass( "onball", "offball", 100, function(){ window[callback](); });
		}
		else if(cval == 0){
			$(this).attr('data', 1);
			$(this).switchClass( "offswitch", "onswitch", 100);
			$(this).find('.bball').switchClass( "offball", "onball", 100, function(){ window[callback](); });
		}
	});
});