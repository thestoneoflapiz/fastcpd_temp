jQuery(document).ready(function () {
    UppyImageIntervention.init();
});

var UppyImageIntervention = function () {
    // Private functions
    const Tus = Uppy.Tus;
    const ProgressBar = Uppy.ProgressBar;
    const StatusBar = Uppy.StatusBar;
    const FileInput = Uppy.FileInput;
    const Informer = Uppy.Informer;
    const XHRUpload = Uppy.XHRUpload;

    // to get uppy companions working, please refer to the official documentation here: https://uppy.io/docs/companion/
    const Dashboard = Uppy.Dashboard;

    var uppyCoursePoster = function () {
        var id = `#uppy_webinar_poster`;
        var $statusBar = $(id + ' .kt-uppy__status');
        var $uploadedList = $(id + ' .kt-uppy__list');
        var timeout;

        var uppyMin = Uppy.Core({
            debug: true,
            autoProceed: true,
            showProgressDetails: true,
            restrictions: {
                maxFileSize: 5000000, // 5mb
                maxNumberOfFiles: 1,
                minNumberOfFiles: 1,
                allowedFileTypes: ['.jpg', '.jpeg', '.gif', '.png', '.JPG', '.JPEG', '.GIF', '.PNG'],
            }
        });

        uppyMin.use(FileInput, {
            target: id + ' .kt-uppy__wrapper',
            pretty: false
        });
        uppyMin.use(Informer, {
            target: id + ' .kt-uppy__informer'
        });

        // demo file upload server
        uppyMin.use(XHRUpload, {
            endpoint: '/webinar/management/attract/poster/upload', 
            method: 'post',
            formData: true,
            fieldName: 'files',
            metaFields: null,
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            bundle: false, // make true if responsedata to show
            // getResponseError(content, xhr) {
            //     return JSON.parse(content);
            // }
        });
        uppyMin.use(StatusBar, {
            target: id + ' .kt-uppy__status',
            hideUploadButton: true,
            hideAfterFinish: false
        });

        $(id + ' .uppy-FileInput-input').addClass('kt-uppy__input-control').attr('id', 'uppy_webinar_poster_input_control');
        var webinar_poster = $("input[name='webinar_poster']");
        if (webinar_poster.val() != "") {
            $(id + ' .uppy-FileInput-container').append('<label class="kt-uppy__input-label btn btn-danger btn-bold btn-font-sm remove-file-poster" onclick="removeCoursePoster()">Remove file</label>');
        } else {
            $(id + ' .uppy-FileInput-container').append('<label class="kt-uppy__input-label btn btn-label-brand btn-bold btn-font-sm attach-file-poster" for="uppy_webinar_poster_input_control">Attach file</label>');
        }

        var $fileLabel = $(id + ' .kt-uppy__input-label');

        uppyMin.on('upload', function (data) {
            $fileLabel.text("Uploading...");
            $statusBar.addClass('kt-uppy__status--ongoing');
            $statusBar.removeClass('kt-uppy__status--hidden');
            clearTimeout(timeout);
        });

        uppyMin.on('complete', function (file) {
            $.each(file.successful, function (index, value) {
                var sizeLabel = "bytes";
                var filesize = value.size;
                if (filesize > 1024) {
                    filesize = filesize / 1024;
                    sizeLabel = "kb";

                    if (filesize > 1024) {
                        filesize = filesize / 1024;
                        sizeLabel = "MB";
                    }
                }

                $(`#webinar_poster_acc`).find(`div.fastcpd-background`).css("background-image", `url('${value.response.body.pathname}')`);
                $(`input[name="webinar_poster"]`).val(value.response.body.pathname);

                var uploadListHtml = '<div class="kt-uppy__list-item" data-id="' + value.id + '"><div class="kt-uppy__list-label">' + value.name + ' (' + Math.round(filesize, 2) + ' ' + sizeLabel + ')</div><span class="kt-uppy__list-remove" data-id="' + value.id + '"><i class="flaticon2-cancel-music"></i></span></div>';
                $uploadedList.append(uploadListHtml);

                $(`.attach-file-poster`).remove();
                $(id + ' .uppy-FileInput-container').append('<label class="kt-uppy__input-label btn btn-danger btn-bold btn-font-sm remove-file-poster" onclick="removeCoursePoster()">Remove file</label>');
            });

            $.each(file.failed, function (index, value) {
                uppyMin.removeFile(value.id);
            });

            $fileLabel.text("Attach file");
            $statusBar.addClass('kt-uppy__status--hidden');
            $statusBar.removeClass('kt-uppy__status--ongoing');
        });

        $(document).on('click', id + ' .kt-uppy__list .kt-uppy__list-remove', function () {
            var itemId = $(this).attr('data-id');
            uppyMin.removeFile(itemId);
            $(id + ' .kt-uppy__list-item[data-id="' + itemId + '"').remove();
            removeCoursePoster();
        });
    }
    
    return {
        init: function () {
            uppyCoursePoster();
        }
    };
}();

function removeCoursePoster() {
    var remove = $(`.remove-file-poster`);
    remove.html(`Removing...`).addClass('kt-spinner kt-spinner--right kt-spinner--md kt-spinner--light').prop("disabled", true);
    $.ajax({
        url: "/webinar/management/attract/poster/remove",
        success: function () {
            remove.html(`Remove file`).removeClass('kt-spinner kt-spinner--right kt-spinner--md kt-spinner--light').prop("disabled", false);
            toastr.success("Successfully removed!");
            setTimeout(() => {
                location.reload();
            }, 500);
        }, error: function () {
            remove.html(`Remove file`).removeClass('kt-spinner kt-spinner--right kt-spinner--md kt-spinner--light').prop("disabled", false);
            toastr.error("Error!", "Something went wrong! Please refresh your browser");
        }
    });
}