var course_id = $(`input[name="course_id"]`).val();

jQuery(document).ready(function () {
    $('#preview-video').bind('contextmenu',function() { return false; });
});

function previewCourse() {
    $(`#preview-modal`).modal("show");

    var exist = $(`#preview-modal_body video`);
    if (exist.length == 0) {
        $.ajax({
            url: "/api/course/preview",
            data: {
                course: course_id,
            },
            success: function (response) {
                setTimeout(() => {
                    $(`#preview-modal_body`).empty()
                        .append(`<video id="preview-video" class="preview-course-video" poster="${response.poster}" controls>
                            <source src="${response.video}" type="video/${response.extension}"/>
                        </video>`);
                    $('#preview-video').bind('contextmenu',function() { return false; });
                }, 1000);
            },
            error: function () {
                toastr.warning(
                    "Sorry! Course preview is unavailable right now."
                );
            },
        });
    }
}

$("#preview-modal").on("hidden.bs.modal", function () {
    var exist = $(`#preview-video`);
    if (exist.length != 0) {
        exist.trigger("pause");
    }
});
