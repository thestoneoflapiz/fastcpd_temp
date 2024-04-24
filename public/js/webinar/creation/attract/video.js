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

jQuery(document).ready(function () {
    $('#preview-video-source').bind('contextmenu',function() { return false; });
});

function removeCourseVideo() {
    var remove = $(`#remove-file-video`);
    remove.html(`Removing...`).addClass('kt-spinner kt-spinner--right kt-spinner--md kt-spinner--light').prop("disabled", true);

    var $digest_ = digest.split(":");
    $.ajax({
        url: "/webinar/management/attract/video/remove",
        data: {
            provider_id: $digest_[0],
            webinar_id: $digest_[1],
        },
        success: function () {
            remove.html(`Remove file`).removeClass('kt-spinner kt-spinner--right kt-spinner--md kt-spinner--light');
            toastr.success("Video successfully removed!");

            $(".video-background").css("background-image", "url('https://fastcpd.com/img/sample/poster-sample.png')").empty();
            $('#file-video-buttons').empty().append(`
                <label class="btn btn-label-brand btn-bold btn-font-sm" for="video_file">Attach file</label>
                <input type="file" id="video_file" style="opacity: 0;position: absolute;z-index: -1;" accept=".mov, .mp4, video/quicktime"/>
                <span class="form-text text-muted">Max file size is 5MB and max number of files is 1.</span>
            `);
            $(`#webinar_pv_acc_title`).empty().append(`<i class="fa fa-video circle-icon"></i> Promotional Video`);

            $("#video_file").change(function(){
                upload_video_to_s3();
            });
        }, error: function () {
            toastr.error("Error!", "Something went wrong! Please refresh your browser");
        }, complete: function(){
            remove.html(`Remove file`).removeClass('kt-spinner kt-spinner--right kt-spinner--md kt-spinner--light').prop("disabled", false);
        }
    });
}

$("#video_file").change(function(){
    upload_video_to_s3();
});

function upload_video_to_s3(){
    var $label = $("label[for='video_file']");
    $label.removeClass('btn-label-brand').addClass('btn-brand kt-spinner kt-spinner--right kt-spinner--md kt-spinner--light disabled').html("0% Uploading...");
    $("#video_file").prop("disabled", true);

    var $progress = $("#progress-bar-video");
    var $digest_ = digest.split(":");

    var files = document.getElementById("video_file").files;
    if (files) {
        var file = files[0];
        var fileType = file.type.split("/")[1];

        if(jQuery.inArray(fileType, ["mp4", "quicktime", "mov", "MP4", "MOV"]) < 0){
            toastr.error("Wrong file format! Please use .mp4 or .mov video file formats");
            reshow_upload_button();
            return;
        }

        if(file.size > 25000000){ //20 MB only!
            toastr.error("Video file size too large!");
            reshow_upload_button();
            return;
        }

        var fileExtension = file.name.split(".");
        fileExtension = fileExtension.length ? fileExtension[fileExtension.length-1] : ".mp4";
        var fileName = `webinar_${$digest_[1]}${$digest_[2]}.${fileExtension}`;
        var filePath = `webinars/raw/provider${$digest_[0]}/webinar${$digest_[1]}/video/${fileName}`;
        var fileUrl = 'https://' + bucketName + '.s3-' + bucketRegion + '.amazonaws.com/' +  filePath;
        s3.upload({
            Key: filePath,
            Body: file,
            ACL: 'public-read'
        }, function(err, data) {
            if(err) {
                console.log(err);
                toastr.error('Error uploading file! Please refresh your browser');
                
                reshow_upload_button();
                return;
            }
            
            save_video_file({
                _token: $('input[name*="_token"]').val(),
                file_type: fileExtension,
                file_url: fileUrl,
                provider_id: $digest_[0],
                webinar_id: $digest_[1],
            });
        }).on('httpUploadProgress', function (progress) {
            $progress.show();
            var uploaded = parseInt((progress.loaded * 100) / progress.total);
            $progress.find(".progress-bar").attr('aria-valuenow', uploaded).css("width", `${uploaded}%`);
            $label.html(`${uploaded}% Uploading...`);
        });
    }
}

function save_video_file(data){
    var $progress = $("#progress-bar-video");
    var $label = $("label[for='video_file']");

    $.ajax({
        method: "POST",
        url: "/webinar/management/attract/video/upload",
        data: data,
        success: function () {
            toastr.success(`Video successfully uploaded!`);

            $(".video-background").css("background-image", "none").empty()
            .append(`
                <video controls style="height:320px;width:100%;" id="preview-video-source">
                    <source src="${data.file_url}" type="video/mp4">
                </video>
            `);
            $('#preview-video-source').bind('contextmenu',function() { return false; });
            $('#file-video-buttons').empty().append(`
                <button class="btn btn-danger" id="remove-file-video" onclick="removeCourseVideo()">Remove file</button>
            `);
            $(`#webinar_pv_acc_title`).empty().append(`
                <span><i class="fa fa-check-circle kt-font-success" style="font-size:1.5rem;"></i> </span> &nbsp; &nbsp; <i class="fa fa-video circle-icon"></i> Promotional Video
            `);
        },
        error: function (response) {
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
            $progress.find(".progress-bar").attr('aria-valuenow', 5).css("width", `5%`);
            $label.addClass("btn-label-brand").removeClass('btn-brand kt-spinner kt-spinner--right kt-spinner--md kt-spinner--light disabled').html("Upload");
            $("#video_file").prop("disabled", false);
        }
    });
}

function reshow_upload_button(){
    var $progress = $("#progress-bar-video");
    var $label = $("label[for='video_file']");

    $progress.hide();
    $progress.find(".progress-bar").attr('aria-valuenow', 5).css("width", `5%`);
    $label.addClass("btn-label-brand").removeClass('btn-brand kt-spinner kt-spinner--right kt-spinner--md kt-spinner--light disabled').html("Upload");
    $("#video_file").prop("disabled", false);
}
