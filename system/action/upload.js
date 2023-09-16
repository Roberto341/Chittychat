function uploadCover(event){
    // Allowed file types
    var allowedFileTypes = 'image.*|application/pdf'; //text.*|image.*|application.*

    // Allowed file size
    var allowedFileSize = 3000000; //in KB

    // Get selected file
    // files = event.target.files;
    var files = $("#fileInput").prop('files');
    // Form data check the above bullet for what it is  
    var data = new FormData();                                   

    // File data is presented as an array
    for (var i = 0; i < files.length; i++) {
        var file = files[i];
        if(!file.type.match(allowedFileTypes)) {              
            // Check file type
            console.log('File extension error! Please select the allowed file type only');
        }else if(file.size > (allowedFileSize*1024)){
            // Check file size (in bytes)
            console.log('File size error! Sorry, the selected file size is larger than the allowed size >'+allowedFileSize+'KB');
        }else{
            // Append the uploadable file to FormData object
            data.append('cover_file', file, file.name);
            
            // Create a new XMLHttpRequest
            var xhr = new XMLHttpRequest();     
            
            // Post file data for upload
            xhr.open('POST', 'system/action/upload_cover.php', true);  
            xhr.send(data);
            xhr.onload = function () {
                // Get response and show the uploading status
                // var response = xhr.response;
                var response = JSON.parse(xhr.responseText);
                // console.log(xhr.response);
                if(xhr.status === 200){
                    $('.profile_background').css('background-image', `url("${response.cover}")`);
                }else if(xhr.status === 400){
                    console.log("Error");
                }
            };
        }
    }
}

uploadAvatar = function(event){
    // Allowed file types
    var allowedFileTypes = 'image.*|application/pdf'; //text.*|image.*|application.*

    // Allowed file size
    var allowedFileSize = 3000000; //in KB

    // Get selected file
    // files = event.target.files;
    var files = $("#avInput").prop('files');
    // Form data check the above bullet for what it is  
    var data = new FormData();                                   

    // File data is presented as an array
    for (var i = 0; i < files.length; i++) {
        var file = files[i];
        if(!file.type.match(allowedFileTypes)) {              
            // Check file type
            console.log('File extension error! Please select the allowed file type only');
        }else if(file.size > (allowedFileSize*1024)){
            // Check file size (in bytes)
            console.log('File size error! Sorry, the selected file size is larger than the allowed size >'+allowedFileSize+'KB');
        }else{
            // Append the uploadable file to FormData object
            data.append('avatar_upload', file, file.name);
            
            // Create a new XMLHttpRequest
            var xhr = new XMLHttpRequest();     
            
            // Post file data for upload
            xhr.open('POST', 'system/action/upload_avatar.php', true);  
            xhr.send(data);
            xhr.onload = function () {
                // Get response and show the uploading status
                // var response = xhr.response;
                var response = JSON.parse(xhr.responseText);
                if(xhr.status === 200){
                    $('.avatar_profile').attr('src', `${response.avatar}`);
                    $('.glob_av').attr('src', `${response.avatar}`);
                }else if(xhr.status === 400){
                    console.log("Error");
                }
            };
        }
    }
}


deleteAvatar = function(){
    $.post("system/action/action_profile.php", {
        delete_avatar: 1,
        token: utk,
    }, function(response){
        if(response == 1){
            $('.avatar_profile').attr('src', 'avatar/default_avatar.png');
			$('.avatar_profile').attr('href', 'avatar/default_avatar.png');
			$('.glob_av').attr('src', 'avatar/default_avatar.png');
            console.log("saved");
        }else{
            callSaved(system.error, 3);
        }
    });
}

deleteCover = function(){
    $.post("system/action/action_profile.php", {
        delete_cover: 1,
        token: utk,
    }, function(response){
        if(response == 1){
            $('.profile_background').css();
            console.log("saved");
        }else{
            callSaved(system.error, 3);
        }
    });
}