var no_forms = 0;

jQuery(document).ready(function () {
    get_handouts();
    generate_form();

    $(`#submit-allow`).click(function(){
        var $submit_button = $(this);

        $submit_button.addClass("kt-spinner kt-spinner--right kt-spinner--md kt-spinner--light disabled").html("Saving...").prop("disabled", true);
        $.ajax({
            url: '/course_management/allow/handouts',
            data: {
                allow: $(`input[name="allow_handout"]`).is(`:checked`),
            },
            success: function () {
                $submit_button.removeClass("kt-spinner kt-spinner--right kt-spinner--md kt-spinner--light disabled").html("Submit");
                if($(`input[name="allow_handout"]`).is(`:checked`)){
                    $(`#handout_form`).slideDown();
                    $(`#allow-handout`).slideUp();  
                    $(`input[name="allow_handout_in_form"]`).prop("checked", true);
                }
                toastr.success('Successfully updated!');
            },error: function(){
                $submit_button.removeClass("kt-spinner kt-spinner--right kt-spinner--md kt-spinner--light disabled").html("Submit").prop("disabled", false);
                toastr.error(`Error!`, `Something went wrong! Please refresh your browser`);
            }
        });
    });

    $(`input[name="allow_handout"]`).click(function(){
        if($(this).is(`:checked`)){
            $(`input[name="allow_handout_in_form"]`).prop("checked", true);
            
            $.ajax({
                url: '/course_management/allow/handouts',
                data: {
                    allow: $(`input[name="allow_handout"]`).is(`:checked`),
                },
                success: function () {
                    $(`#handout_form`).slideDown();
                    $(`#allow-handout`).slideUp(); 
                },error: function(){
                    toastr.error(`Error!`, `Something went wrong! Please refresh your browser`);
                }
            }); 
        }else{
            $(`#handout_form`).slideUp();
            $(`#allow-handout`).slideDown();
        }
    });

    $(`input[name="allow_handout_in_form"]`).click(function(){
        if($(this).is(`:checked`)){
            $(`#handout_form`).slideDown();
            $(`#allow-handout`).slideUp();  
        }else{
            $(`input[name="allow_handout"]`).prop("checked", false);

            $.ajax({
                url: '/course_management/allow/handouts',
                data: {
                    allow: $(`input[name="allow_handout_in_form"]`).is(`:checked`),
                },
                success: function () {
                    $(`#handout_form`).slideUp();
                    $(`#allow-handout`).slideDown();
                },error: function(){
                    toastr.error(`Error!`, `Something went wrong! Please refresh your browser`);
                }
            });
        }
    });
});

function get_handouts() {
    $.ajax({
        url: '/course_management/api/handouts',
        success: function (response) {
            var data = response.data;
            var allow = response.allow;

            if(allow){
                data.forEach((handout, index) => {
                    var ext = handout.url.split('.').pop().toLowerCase();
                    switch (ext) {
                        case 'pdf':
                            var icon = `https://www.fastcpd.com/img/pdf.png`;
                            break;
                        case 'xls':
                            var icon = `https://www.fastcpd.com/img/excel.png`;
                            break;
                        case 'csv':
                            var icon = `https://www.fastcpd.com/img/excel.png`;
                            break;
                        case 'zip':
                            var icon = `https://www.fastcpd.com/img/folder.png`;
                            break;
                        default:
                            var icon = `https://www.fastcpd.com/img/document.png`;
                            break;
                    }
    
                    var group_wrapper = $(`<div class="col-xl-3 col-md-3 col-4" style="text-align:center" id="handout-group-icon-${handout.id}" />`).append(`<h5>${handout.title} &nbsp; <i class="fa fa-trash kt-font-danger" onclick="deleteHandout(${handout.id})"></i></h5>`);
                    var file_wrapper = $(`<img src="${icon}" height="80" />`).click(function () {
                        window.open(handout.url);
                    });
    
                    group_wrapper.append(file_wrapper);
                    $("#handouts_div").append(group_wrapper);
                });

                $(`#handout_form`).show();
            }else{
                $(`#allow-handout`).show();
            }
        },
        error: function () {
            toastr.error(`Error!`, `Something went wrong! Please refresh your browser.`);
        }
    });
}

function deleteHandout(id) {
    var remove_modal = $(`#removal-modal`);
    remove_modal.find(`#removal-modal-submit`).attr(`data-id`, id);
    remove_modal.find(`.modal-body`).html(`You're removing a <b>Handout</b>.<br>Are you sure you want to remove it?`);
    remove_modal.modal(`show`);
}

$(`#removal-modal-submit`).click(function (event) {
    var current = event.target.dataset;
    var id = current.id;

    $.ajax({
        url: "/course/management/handouts/remove",
        method: "POST",
        data: {
            id: id,
            _token: $(`input[name="_token"]`).val(),
        },
        success: function () {
            toastr.success(`Handout successfully removed!`);
            $(`#handout-group-icon-${id}`).remove();
        }, error: function () {
            toastr.error(`Error!`, `Something went wrong! Please refresh your browser`);
        }
    });
});

var FormDesign = function () {
    var input_validations = function () {
        validator = $("#handout_form").validate({
            rules: {
                title: {
                    required: true,
                },
                handout_file: {
                    required: true,
                },
                notes: {
                    maxlength: 50,
                },
            },

            invalidHandler: function (event, validator) {
                var alert = $('#form_msg');
                alert.removeClass('kt-hidden').show();
            },

            submitHandler: function (form) {
                var alert = $('#form_msg');
                alert.addClass('kt-hidden').hide();

                var submit = $("#submit_form");

                var handouts = [];
                var forms = $(`div.real-form`);
                forms.each(function () {
                    var current = $(this);

                    var title = current.find(`input[name^="title"]`);
                    var notes = current.find(`input[name^="notes"]`);
                    var file = current.find(`input[name^="handout_file"]`);

                    handouts.push({
                        title: title.val(),
                        note: notes.val(),
                        url: file.val(),
                    })
                });

                submit.html(`Saving...`).addClass('kt-spinner kt-spinner--right kt-spinner--md kt-spinner--light disabled').prop("disabled", true);
                $.ajax({
                    url: '/course_management/handouts/store',
                    type: 'POST',
                    data: {
                        handouts: handouts,
                        _token: $(`input[name="_token"]`).val(),
                    },
                    success: function (response) {
                        submit.html(`Submit`).removeClass('kt-spinner kt-spinner--right kt-spinner--md kt-spinner--light disabled');
                        toastr.success('Success!', response.message);
                        setTimeout(() => {
                            location.reload();
                        }, 1000);
                    },
                    error: function (response) {
                        submit.html(`Submit`).removeClass('kt-spinner kt-spinner--right kt-spinner--md kt-spinner--light disabled').prop("disabled", false);
                        toastr.error('Error!', 'Something went wrong! Please refresh your browser');
                    }
                });
            }
        });

        $(`input[name^=title]`).each(function () {
            $(this).rules("add", {
                required: true,
            });
        });

        $(`input[name^=handout_file]`).each(function () {
            $(this).rules("add", {
                required: true,
            });
        });

        $(`input[name^=notes]`).each(function () {
            $(this).rules("add", {
                maxlength: 50,
            });
        });
    }

    return {
        init: function () {
            input_validations();
        }
    };
}();

var KTUppy = function () {
    const StatusBar = Uppy.StatusBar;
    const FileInput = Uppy.FileInput;
    const Informer = Uppy.Informer;
    const XHRUpload = Uppy.XHRUpload;

    var uppy_upload = function () {
        var data = { id: no_forms };
        var elemId = `uppy_handout_fileupload-${data.id}`;
        var id = `#uppy_handout_fileupload-${data.id}`;

        var $statusBar = $(id + ' .kt-uppy__status');
        var $uploadedList = $(id + ' .kt-uppy__list');
        var timeout;

        var uppyMin = Uppy.Core({
            debug: true,
            autoProceed: true,
            allowMultipleUploads: false,
            showProgressDetails: true,
            restrictions: {
                maxFileSize: 6000000, // 5mb
                maxNumberOfFiles: 1,
                minNumberOfFiles: 1,
                allowedFileTypes: ['.pptx', '.PPTX', '.docx', 'DOCX', '.pdf', '.PDF', '.xls', '.XLS', '.zip', '.ZIP', '.csv', '.CSV'],
            }
        });

        uppyMin.use(FileInput, {
            target: id + ' .kt-uppy__wrapper',
            pretty: false
        });
        uppyMin.use(Informer, {
            target: id + ' .kt-uppy__informer'
        });

        uppyMin.use(XHRUpload, {
            endpoint: '/course_management/upload/file',
            method: 'post',
            formData: true,
            fieldName: 'files',
            metaFields: null,
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            bundle: false
        });

        uppyMin.use(StatusBar, {
            target: id + ' .kt-uppy__status',
            hideUploadButton: true,
            hideAfterFinish: false
        });

        $(id + ' .uppy-FileInput-input').addClass('kt-uppy__input-control').attr('id', elemId + '_input_control');
        $(id + ' .uppy-FileInput-container').append('<label class="kt-uppy__input-label btn btn-label-brand btn-bold btn-font-sm" for="' + (elemId + '_input_control') + '">Attach file</label>');

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
                var uploadListHtml = '<div class="kt-uppy__list-item" data-id="' + value.id + '"><div class="kt-uppy__list-label">' + value.name + ' (' + Math.round(filesize, 2) + ' ' + sizeLabel + ')</div><span class="kt-uppy__list-remove" data-id="' + value.id + '"><i class="flaticon2-cancel-music"></i></span></div>';
                $uploadedList.append(uploadListHtml);
                $(`#handout_file-${data.id}`).val(value.response.body.pathname);
            });

            $.each(file.failed, function (index, value) {
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
                var uploadListHtml = '<div class="kt-uppy__list-item" data-id="' + value.id + '"><div class="kt-uppy__list-label" style="color:red;"><i class="fa fa-exclamation-circle" style="color:red;height:10px;"></i> &nbsp;' + value.name + ' (' + Math.round(filesize, 2) + ' ' + sizeLabel + ')</div><span class="kt-uppy__list-remove" data-id="' + value.id + '"><i class="flaticon2-cancel-music"></i></span></div>';
                $uploadedList.append(uploadListHtml);
                $(`#handout_file-${data.id}`).val(null);
            });

            $fileLabel.text(`Attach file`).hide();

            $statusBar.addClass('kt-uppy__status--hidden');
            $statusBar.removeClass('kt-uppy__status--ongoing');
        });

        $(document).on('click', id + ' .kt-uppy__list .kt-uppy__list-remove', function () {
            $fileLabel.show();
            var itemId = $(this).attr('data-id');
            uppyMin.removeFile(itemId);
            $(id + ' .kt-uppy__list-item[data-id="' + itemId + '"').remove();
            $(`#handout_file-${data.id}`).val(null);
        });
    }

    return {
        init: function () {
            uppy_upload();
        }
    };
}();

function generate_form() {
    no_forms++;

    var group = $(`#hidden-handout-group-wrapper`).clone();
    group.attr(`id`, `handout-group-wrapper-${no_forms}`).addClass(`real-form`);

    group.find(`input[name="ti-tle"]`).attr(`id`, `title-${no_forms}`).attr(`name`, `title[${no_forms}]`);
    group.find(`input[name="no-tes"]`).attr(`id`, `notes-${no_forms}`).attr(`name`, `notes[${no_forms}]`);
    group.find(`div.kt-uppy`).attr(`id`, `uppy_handout_fileupload-${no_forms}`);
    group.find(`input[name="hand-out-file"]`).attr(`id`, `handout_file-${no_forms}`).attr(`name`, `handout_file[${no_forms}]`);
    group.find(`.remove-handout-form`).click(function(){
        var forms = $(`div.real-form`);
        if(forms.length > 1){
            group.remove();
        }else{
            toastr.error("Please upload at least one(1) handout!");
        }
    });
    group.show();

    if (no_forms == 1) {
        $(`#hidden-handout-group-wrapper`).after(group);
    } else {
        $(`#handout-group-wrapper-${(no_forms - 1)}`).after(group);
    }

    FormDesign.init();
    KTUppy.init();
}

$(`.add-another-hd`).click(function () {
    var forms = $(`div.real-form`);

    var should_add = true;
    forms.each(function () {
        var current = $(this);

        var title = current.find(`input[name^="title"]`);
        var file = current.find(`input[name^="handout_file"]`);

        if (!title.val() == true && !file.val() == true) {
            should_add = false;
            return false;
        }
    });

    if (should_add) {
        generate_form();
    } else {
        toastr.warning(`Reminder!`, `Please complete every field before adding another handout!`);
    }
});


