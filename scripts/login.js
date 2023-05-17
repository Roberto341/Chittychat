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
sendRegistration = function(){
	var upass = $('#reg_password').val();
	var uuser = $('#reg_username').val();
	var uemail = $('#reg_email').val();
	var ugender = $('#login_select_gender').val();
	var uage = $('#login_select_age').val();
	if(upass == '' || uuser == '' || uemail == ''){
		callSaved(system.emptyField, 3);
		return false;
	}
	else if (/^\s+$/.test($('#reg_username').val())){
		callSaved(system.emptyField, 3);
		$('#reg_username').val("");
		return false;
	}
	else if (/^\s+$/.test($('#reg_password').val())){
		callSaved(system.emptyField, 3);
		$('#reg_password').val("");
		return false;
	}
	else if (/^\s+$/.test($('#reg_email').val())){
		callSaved(system.emptyField, 3);
		$('#reg_email').val("");
		return false;
	}
	else{
		if(waitReply == 0){
			waitReply = 1;
			$.post('system/registration.php', {
				password: upass,
				username: uuser,
				email: uemail,
				age: uage,
				gender: ugender,
			}, function(response){
				if(response == 2){
					callSaved(system.error, 3);
					$('#reg_password').val("");
					$('#reg_username').val("");
					$('#reg_email').val("");	
					console.log(upass);
				}
				else if (response == 3){
					callSaved(system.error, 3);
					$('#reg_password').val("");
					$('#reg_username').val("");
					$('#reg_email').val("");
				}
				else if (response == 4){
					callSaved(system.invalidUsername, 3);
					$('#reg_username').val("");
				}
				else if (response == 5){
					callSaved(system.usernameExist, 3);
					$('#reg_username').val("");
				}
				else if (response == 6){
					callSaved(system.invalidEmail, 3);
					$('#reg_email').val("");
				}
				else if (response == 10){
					callSaved(system.emailExist, 3);
					$('#reg_email').val("");
				}
				else if (response == 13){
					callSaved(system.selAge, 3);
				}
				else if (response == 14){
					callSaved(system.error, 3);
				}
				else if (response == 17){
					callSaved(system.shortPass, 3);
					$('#reg_password').val("");
				}
				else if (response == 1){
					location.reload();
				}
				else if(response == 0){
					callSaved(system.registerClose, 3);
				}
				else {
					waitReply = 0;
					return false;
				}
				waitReply = 0;
			});
		}
		else{
			return false;
		}
	}
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
				if(response == 1){
					callSaved(system.badLogin, 3);
					$('#password').val("");
				}
				else if(response == 2){
					callSaved(system.badLogin, 3);
					$('#password').val("");
				}
				else if(response == 3){
					location.reload();
				}
				// location.reload();
				// curPage = 'chat';
				waitReply = 0;
			});
		}
	}
}