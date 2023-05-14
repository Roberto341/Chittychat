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
	if(boomSound(1)){
		document.getElementById('message_sound').play();
	}
}
clearPlay = function(){
	if(boomSound(1)){
		document.getElementById('clear_sound').play();
	}
}
joinPlay = function(){
	if(boomSound(1)){
		document.getElementById('join_sound').play();
	}
}
leavePlay = function(){
	if(boomSound(1)){
		document.getElementById('leave_sound').play();
	}
}
actionPlay = function(){
	if(boomSound(1)){
		document.getElementById('action_sound').play();
	}
}
whistlePlay = function(){
	if(boomSound(1)){
		document.getElementById('whistle_sound').play();
	}
}
privatePlay = function(){
	if(boomSound(2)){
		document.getElementById('private_sound').play();
	}
}
notifyPlay = function(){
	if(boomSound(3)){
		document.getElementById('notify_sound').play();
	}
}
usernamePlay = function(){
	if(boomSound(4)){
		document.getElementById('username_sound').play();
	}
}
newsPlay = function(){
	if(boomSound(3)){
		document.getElementById('news_sound').play();
	}
}

getLanguage = function(){
	$.post('system/box/language.php', {
		}, function(response) {
				showModal(response, 240);
	});
}

$(document).ready(function(){

});