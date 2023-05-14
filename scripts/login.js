getLogin = function(){
	$.post('system/box/login.php', {
		}, function(response) {
			if(response != 0){
				showModal(response);
			}
			else {
				return false;
			}
	});
}
var waitReply = 0;
getRegistration = function(){
	$.post('system/box/registration.php', {
		}, function(response) {
			if(response != 0){
				showModal(response);
			}
			else {
				return false;
			}
	});
}

sendLogin = function(){
    var upass = $("#user_password").val();
	var uuser = $("#user_username").val();
	if(upass == '' || uuser == ''){
		callSaved(system.emptyField, 3);
		return false;
	}
	else if(/^\s+$/.test($('#password').val())){
		callSaved(system.emptyField, 3);
		$("#password").val("");
		return false;
	}
	else if(/^\s+$/.test($('#username').val())){
		callSaved(system.emptyField, 3);
		$("#username").val("");
		return false;
	}
	else{
		if(waitReply == 0){
			waitReply = 1;
			$.post('system/login.php', {
				password: upass,
				username: uuser
			},function(response){
				console.log(response.custom);
				if(response.code == 1){
					callSaved(system.badLogin, 3);
					$('#password').val("");
					// location.reload();
				}
				else if(response.code == 2){
					callSaved(system.badLogin, 3);
					$('#password').val("");
				}
				else if(response.code == 3){
					// location.reload();
				}
				location.reload();
				waitReply = 0;
			});
		}
	}
}