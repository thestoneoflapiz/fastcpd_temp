//Bucket Configurations
var digest = $("[name='digest']").val();
var bucketName = $("[name='s3bname']").val();
var bucketRegion = "ap-northeast-1";
var IdentityPoolId = "ap-northeast-1:8145e063-e561-417b-a770-788d87699a1e";

AWS.config.region = bucketRegion; // Region
AWS.config.credentials = new AWS.CognitoIdentityCredentials({
    IdentityPoolId: IdentityPoolId,
});

var s3 = new AWS.S3({
    apiVersion: '2006-03-01',
    params: {Bucket: bucketName}
});

function video_aws_upload(data){
    $(`#part-video-error-message-${data.section}-${data.part}`).remove();
    var $drag_drop = $(`#part-drag-drop-video-wrapper-${data.section}-${data.part}`);
    var $progress = $(`#part-video-progress-bar-wrapper-${data.section}-${data.part}`);
    var $digest_ = digest.split(":");
    var video_id = $(`#video-id-${data.section}-${data.part}`).val();

    $drag_drop.hide();
    $progress.show();

    var files = document.getElementById(`part-video-input-file-${data.section}-${data.part}`).files;
    if (files) {
        var file = files[0];
        var fileType = file.type.split("/")[1];

        if(jQuery.inArray(fileType, ["mp4", "quicktime", "mov", "MP4", "MOV"]) < 0){
            toastr.error("Wrong file format! Please use .mp4 or .mov video file formats");
            $drag_drop.show();
            $progress.hide();
            return;
        }

        if(file.size > 2000000000){ //2GB or less than only!
            toastr.error("Video file size too large!");
            $drag_drop.show();
            $progress.hide();
            return;
        }

        var fileExtension = file.name.split(".");
        fileExtension = fileExtension.length ? fileExtension[fileExtension.length-1] : ".mp4";
        var fileName = `course_vcs${data.section}${data.part}_${$digest_[1]}${$digest_[2]}.${fileExtension}`;
        var filePath = `courses/raw/provider${$digest_[0]}/course${$digest_[1]}/video/${fileName}`;
        var fileUrl = 'https://' + bucketName + '.s3-' + bucketRegion + '.amazonaws.com/' +  filePath;

        s3.upload({
            Key: filePath,
            Body: file,
            ACL: 'public-read'
        }, function(err, data_s3) {
            if(err) {
                console.log(err);
                toastr.error('Error uploading file! Please refresh your browser or try again later');
                $(`#part-video-upload-${data.section}-${data.part}`).append(`<span id="part-video-error-message-${data.section}-${data.part}" class="video-failed-span">Video failed to upload.</span>`);
                
                $drag_drop.show();
                $progress.hide();
                return;
            }
            
            save_video_file({
                _token: $('input[name*="_token"]').val(),
                file_name: fileName,
                file_size: file.size,
                file_url: fileUrl,
                video_id: video_id,
                section: data.section,
                part: data.part
            });

        }).on('httpUploadProgress', function (progress) {
            var uploaded = parseInt((progress.loaded * 100) / progress.total);
            if(uploaded==100){
                $progress.find(".progress-bar").attr('aria-valuenow', uploaded).css("width", `${uploaded}%`);
                $progress.find(`#part-video-progress-bar-percent-${data.section}-${data.part}`).html(`Saving information...Please do not close or refresh your browser!`);
            }else{
                $progress.find(".progress-bar").attr('aria-valuenow', uploaded).css("width", `${uploaded}%`);
                $progress.find(`#part-video-progress-bar-percent-${data.section}-${data.part}`).html(`${uploaded}% Uploading...Please do not close or refresh your browser!`);
            }
        });
    }
}

function save_video_file(data){
    var $drag_drop = $(`#part-drag-drop-video-wrapper-${data.section}-${data.part}`);
    var $progress = $(`#part-video-progress-bar-wrapper-${data.section}-${data.part}`);
    var $video_upload = $(`#part-video-upload-${data.section}-${data.part}`);

    $.ajax({
        method: "POST",
        url: "/course/management/content/video/upload",
        data: data,
        success: function (response) {
            var video_data = response.data;
            var part_content_title_limit = video_data.title.length > 35 ? `${video_data.title.substring(0, 35)}...` : video_data.title;

            toastr.success(`Video successfully uploaded!`);
            
            $partContent = $(`#part-${data.section}-${data.part}`);
            $partContent.find(`#part-video-title`).html(`Video — ${part_content_title_limit} &nbsp; <i class="fa fa-check-circle kt-font-success" style="font-size:1rem;"></i>`);
            $partContent.find(`.video-filename`).each(function (){$(this).html(`Name: ${video_data.filename}`);});
            $partContent.find(`.video-length`).html(`Length: ${video_data["length"]} mins`);

            $(`#video-url-${data.section}-${data.part}`).val(video_data.cdn_url);
            $(`#video-filename-${data.section}-${data.part}`).val(video_data.filename);
            $(`#video-size-${data.section}-${data.part}`).val(video_data.size);

            $(`#part-video-view-button-${data.section}-${data.part}`).click(function () {
                window.open(`/course/management/content/preview/${video_data.id}`);
            }); 
            
            $video_upload.hide();
            $(`#part-video-exist-buttons-div-${data.section}-${data.part}`).show();
        },
        error: function (response) {
            $(`#part-video-upload-${data.section}-${data.part}`).append(`<span id="part-video-error-message-${data.section}-${data.part}" class="video-failed-span">Video failed to upload.</span>`);
            $drag_drop.show();
            
            var body = response.responseJSON;
            if(body.hasOwnProperty("message")){
                toastr.error(body.message);
                return;
            }
            
            toastr.error(
                "Error!",
                "Something went wrong! Please try again later."
            );
        },
        complete: function(){
            $progress.hide();
            $progress.find(".progress-bar").attr('aria-valuenow', 1).css("width", `1%`);
            $progress.find(`#part-video-progress-bar-percent-${data.section}-${data.part}`).html("Preparing to upload video...Please do not close or refresh your browser!");
        }
    });
}