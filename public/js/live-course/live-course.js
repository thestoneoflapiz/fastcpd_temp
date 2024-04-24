var _is_preview = $(`input[name="preview_"]`).val();
var progress_label = null;
var has_completed_course = false;
var has_completed_quiz = false;
var has_quiz = false;
var has_completed_rating = false;
var total_completed_quizzes = 0;
var total_quizzes = 0;

var window_width = $(window).width();

var circle1 = null;
var circle2 = null;
var rating_step = $("#rating_step").val();
var total_progress = 0;
var current_progress = 0;
/**
 * Video Area
 *
 */
var currentVideo = {};
var completed_video_sent = false;
var currentVideo_autosave = 0; // every 10 seconds

// control over main content's rotation
var currentRotation = 0;
// control over current quiz's rotation
var currentQuiz = {};
var currentQuizRotation = 0;
// this is for when taking a quiz
var quizScores = [];
var quizState = false;

// data for main content
var section_content_rotation = [];
// list data for course content
var section_content_progress = [];

var MyVideoJS = null;

jQuery(document).ready(function () {
    stars();
    get_sections();
    $('#live-course-video').bind('contextmenu',function() { return false; });
});

function stars(){
    $(".rating").starRating({
        totalStars: 5,
        initialRating: 0,
        starShape: "rounded",
        starSize: 50,
        disableAfterRate: true,
        onHover: function (currentIndex, currentRating, $el) {
            var displayText = "";
            var star_rating = 0;
            if (currentIndex == 5) {
                displayText = "Amazing, above expectation!";
                star_rating = 5.0;
            }

            if (currentIndex > 4 && currentIndex < 5) {
                displayText = "Good / Amazing";
                star_rating = 4.5;
            }

            if (currentIndex == 4) {
                displayText = "Good, what I expected";
                star_rating = 4.0;
            }

            if (currentIndex > 3 && currentIndex < 4) {
                displayText = "Average / Good";
                star_rating = 3.5;
            }

            if (currentIndex == 3) {
                displayText = "Average, could be better";
                star_rating = 3.0;
            }

            if (currentIndex > 2 && currentIndex < 3) {
                displayText = "Poor / Average";
                star_rating = 2.5;
            }

            if (currentIndex == 2) {
                displayText = "Poor, pretty disappointed";
                star_rating = 2.0;
            }

            if (currentIndex > 1 && currentIndex < 2) {
                displayText = "Awful / Poor";
                star_rating = 1.5;
            }

            if (currentIndex == 1) {
                displayText = "Aww, not what I expected at all!";
                star_rating = 1.0;
            }
            
            if (currentIndex > 0 && currentIndex < 1) {
                displayText = "Aww, not what I expected at all!";
                star_rating = 0.5;
            }
            $("#ratings_value").val(star_rating);
            $(".live-rating-label").text(displayText);
        },
        callback: function (currentRating, $el) {
            rating_step += 1;
            $(`#feedback-rating-textarea`).show(
                "slide",
                { direction: "right" },
                300
            );
            // $(`#feedback-back-button`).show(
            //     "slide",
            //     { direction: "right" },
            //     300
            // );
            
        },
    });
}

/**
 * Video JS
 * Should have the initial video, recommended: preview video
 *
 */
function initialize_videoJS(video) {
    if (MyVideoJS == null) {
        currentVideo = video;
        var vext_ = extract_file_ext(video.filename);
        MyVideoJS = videojs(
            "live-course-video",
            {
                controls: true,
                fluid: true,
                responsive: true,
                aspectRatio: "600:250",
                playbackRates: [0.5, 1, 1.5],
                plugins: {
                    videoJsResolutionSwitcher: {
                        ui: true,
                        default: "low",
                        dynamicLabel: true,
                    },
                    watermark: {
                        file: "https://fastcpd.com/img/system/icon-9.png",
                        opacity: 0.3,
                        xpos: 100,
                        ypos: 0,
                    },
                },
            },
            function () {
                var player = this;
                window.player = player;

                player.src({ type: `video/${vext_}`, src: video.source });
                player.seekButtons({
                    forward: 0,
                    back: 10,
                });
                this.hotkeys({
                    volumeStep: 0.1,
                    seekStep: 0,
                    enableModifiersForNumbers: false,
                });
                player.controlBar.progressControl.disable();
                player.on("resolutionchange", function () {
                    // The resolution label was not updating or showing
                    // Create a customized reso label on evert change of resolution
                    $(
                        "button.vjs-resolution-button > span.vjs-icon-placeholder"
                    ).html(player.currentResolutionState.label);
                });
                player.thumbnails(video.thumbnail);
                player.poster(video.poster);

                player.on("pause", function (e) {
                    section_content_rotation = section_content_rotation.map(
                        (varData) => {
                            if (
                                varData.type == "video" &&
                                varData.id == currentVideo.id
                            ) {
                                $.ajax({
                                    url: "/course/live/progress",
                                    method: "POST",
                                    data: {
                                        course_id: $(
                                            `input[name="course_id"]`
                                        ).val(),
                                        preview_: _is_preview,
                                        _token: $(
                                            'input[name*="_token"]'
                                        ).val(),
                                        type: "video",
                                        data_: currentVideo,
                                    },
                                });

                                return currentVideo;
                            }

                            return varData;
                        }
                    );
                });
            }
        );

        MyVideoJS.on("timeupdate", function () {
            currentVideo.current_play_time = Math.floor(this.currentTime());
            let vlength = currentVideo.video_length;
            let split_v = vlength.toString().split(":");
            if(split_v.length==3){
                let hours = parseInt(split_v[0]);
                let mins = parseInt(split_v[1]);
                let secs = parseInt(split_v[2]);
                
                var seconds_ = (hours > 0 ? hours * 60 * 60 : 0) + (mins > 0 ? mins * 60 : 0) + (secs);
                if (seconds_ - 20 == Math.round(this.currentTime())) {
                    // save progress & as complete
                    currentVideo.complete = true;
                    completedVideo(currentVideo);
                }

                currentVideo_autosave += 1;
                if (currentVideo_autosave == 20) {
                    $.ajax({
                        url: "/course/live/progress",
                        method: "POST",
                        data: {
                            course_id: $(`input[name="course_id"]`).val(),
                            preview_: _is_preview,
                            _token: $('input[name*="_token"]').val(),
                            type: "video",
                            data_: currentVideo,
                        },
                        success: function () {
                            setTimeout(() => {
                                currentVideo_autosave = 0;
                            }, 1000);
                        },
                    });
                }
            }else{
                let mins = parseInt(split_v[0]);
                let secs = parseInt(split_v[1]);
                var seconds_ = (mins > 0 ? mins * 60 : 0) + (secs);
                if (seconds_ - 5 == Math.round(this.currentTime())) {
                    // save progress & as complete
                    currentVideo.complete = true;
                    completedVideo(currentVideo);
                }

                currentVideo_autosave += 1;
                if (currentVideo_autosave == 20) {
                    $.ajax({
                        url: "/course/live/progress",
                        method: "POST",
                        data: {
                            course_id: $(`input[name="course_id"]`).val(),
                            preview_: _is_preview,
                            _token: $('input[name*="_token"]').val(),
                            type: "video",
                            data_: currentVideo,
                        },
                        success: function () {
                            setTimeout(() => {
                                currentVideo_autosave = 0;
                            }, 1000);
                        },
                    });
                }
            }
        });

        MyVideoJS.currentTime(video.current_play_time);
    }
}

function extract_file_ext(filename) {
    var split = filename.split(".");
    var file_ext = split[split.length - 1];
    var fext = file_ext;
    switch (file_ext.toLowerCase()) {
        case `mov`:
        case `mkv`:
        case `mp4`:
            fext = `mp4`;
            break;
    }

    return fext.toLowerCase();
}

/**
 *
 * fetching data of course's sections
 */
function get_sections() {
    $.ajax({
        url: "/course/live/api/sections",
        data: {
            course_id: $(`input[name="course_id"]`).val(),
            preview_: _is_preview,
        },
        success: function (response) {
            generate_sections(response.data);
            var progress_ = response.progress;

            if(progress_.current == progress_.total){
                $("#leave_a_rating, .leave-a-rating-icon").attr("onclick","$(`#leave-a-rating-content`).modal('show')");
            }else{
                $("#leave_a_rating, .leave-a-rating-icon").removeAttr("onclick");
            }
            if (_is_preview == "live") {
                current_progress = progress_.current;
                total_progress = progress_.total;

                if(current_progress==total_progress && total_progress!=0){
                    has_completed_course = true;
                    live_completed_course();
                }

                init_progress_circle();
                progress_label = $(`#live-course-progress-title`);
                progress_label.html(
                    `${current_progress} out of ${total_progress} complete`
                );
            }
        },
        error: function () {
            toastr.error(
                "Error!",
                "Something went wrong! Please refresh your browser"
            );
        },
    });
}

function generate_sections(data) {
    var sidemenu_sections = $(`#sidemenu-course-content-accordion`);
    var tabcontent_sections = $(`#tab-course-content-accordion`);

    var sections = data.section_content_info;
    sections.forEach((section) => {
        var sequence = section.detailed_sequence;
        var card = $(`<div class="card" />`);
        var card_header = $(`<div class="card-header">
            <div class="card-title collapsed" data-toggle="collapse" data-target="#course-content-collapse-${section.id}" aria-expanded="false" aria-controls="course-content-collapse-${section.id}">
                <h5 id="section-overall-count-${section.id}" data-title="${section.title}" data-sequencelength="${sequence.length}" data-sequencetime="${section.total_time}">
                    ${section.title} <br/>
                    <small>${section.current_progress}/${sequence.length} | ${section.total_time} min</small>
                </h5>
            </div>
        </div>`);

        var card_body_wrapper = $(
            `<div id="course-content-collapse-${section.id}" class="collapse" data-parent="" />`
        );
        var card_body = $(`<div class="card-body" />`);
        var section_list = $(`<div class="kt-checkbox-list" />`);

        var section_item_completion_list = [];
        sequence.forEach((data, index) => {
            switch (data.type) {
                case `video`:

                    if(data.source){
                        var video_tab = $(`<label class="kt-checkbox" onclick="renderMainContent({type:'video', id:${data.id}, section: ${section.id}, rotation:${data.rotation}})">
                            <input type="checkbox" name="section_part_checkbox" data-status="${data.complete}" data-rotation="${data.rotation}" data-type="video">
                            ${data.title}<br>
                            <i class="fa fa-play-circle"></i> &nbsp; ${data.video_length} min
                            <span></span>
                        </label>`);

                        section_list.append(video_tab);
                        initialize_videoJS(data);
                    }else{
                        var video_tab = $(`<label class="kt-checkbox">
                            <input type="checkbox" name="section_part_checkbox" data-status="false" data-rotation="${data.rotation}" data-type="video">
                            ${data.title}<br>
                            <i class="fa fa-play-circle kt-font-danger"></i> <b style="color:#fd397a;">Incomplete</b>
                            <span></span>
                        </label>`);

                        section_list.append(video_tab);
                    }
                    break;

                case `article`:
                    var article_tab = $(`<label class="kt-checkbox" onclick="renderMainContent({type:'article', id:${data.id}, section: ${section.id}, rotation: ${data.rotation}})">
                        <input type="checkbox" name="section_part_checkbox" data-status="${data.complete}" data-rotation="${data.rotation}" data-type="article">
                        ${data.title} <br>
                        <i class="fa fa-newspaper"></i> &nbsp; ${data.reading_time} min read
                        <span></span>
                    </label>`);

                    section_list.append(article_tab);
                    break;

                case `quiz`:
                    has_quiz = true;
                    total_quizzes+=1;
                    if(data.complete){
                        total_completed_quizzes+=1;
                    }

                    if(data.items && data.items.length > 0){
                        var quiz_tab = $(`<label class="kt-checkbox" onclick="renderMainContent({type:'quiz', id:${
                            data.id
                        }, section: ${section.id}, rotation: ${data.rotation}})">
                            <input type="checkbox" name="section_part_checkbox" data-status="${
                                data.complete
                            }" data-rotation="${data.rotation}" data-type="quiz">
                            ${data.title} <br>
                            <i class="fa fa-list"></i> &nbsp; ${
                                data.items.length
                            } item${data.items.length > 1 ? "s" : ""}
                            <span></span>
                        </label>`);
    
                        section_list.append(quiz_tab);
                    }else{
                        var quiz_tab = $(`<label class="kt-checkbox">
                            <input type="checkbox" name="section_part_checkbox" data-status="false" data-rotation="${data.rotation}" data-type="quiz">
                            ${data.title} <br>
                            <i class="fa fa-list kt-font-danger"></i> <b style="color:#fd397a;">Incomplete</b>
                            <span></span>
                        </label>`);
    
                        section_list.append(quiz_tab);
                    }
                    
                    break;
            }
        });

        card_body.append(section_list);
        card_body_wrapper.append(card_body);
        card.append(card_header).append(card_body_wrapper);

        var clone_tab = card.clone();
        var clone_sidemenu = card.clone();

        // assign to corresponding placement
        clone_tab
            .find(`div.card-title`)
            .attr(`data-target`, `#tab-course-content-collapse-${section.id}`)
            .attr(`aria-controls`, `tab-course-content-collapse-${section.id}`);
        clone_tab
            .find(`div#course-content-collapse-${section.id}`)
            .attr(`id`, `tab-course-content-collapse-${section.id}`);

        clone_sidemenu
            .find(`div.card-title`)
            .attr(
                `data-target`,
                `#sidemenu-course-content-collapse-${section.id}`
            )
            .attr(
                `aria-controls`,
                `sidemenu-course-content-collapse-${section.id}`
            );
        clone_sidemenu
            .find(`div#course-content-collapse-${section.id}`)
            .attr(`id`, `sidemenu-course-content-collapse-${section.id}`);

        tabcontent_sections.append(clone_tab);
        sidemenu_sections.append(clone_sidemenu);

        section_content_progress.push({
            id: section.id,
            title: section.name,
            complete: false,
            items: section_item_completion_list,
        });
    });

    section_content_progress = sections;
    section_content_rotation = data.section_content_rotation;

    showFirstRotation();
    validation_on_checkbox_list();
    toastr.success("Your live course is ready!");
}

/**
 *
 * First incomplete section to parts
 */
function showFirstRotation() {
    /**
     *
     * control on foreach loop
     */
    var stop = false;
    section_content_progress.forEach((section) => {
        if (section.complete === false) {
            section.detailed_sequence.forEach((part) => {
                if (part.complete === false && stop === false) {
                    stop = true;
                    renderMainContent(part);
                }
            });
        }
    });
}

/**
 * Sample Main Content Rotation through prev and next buttons
 * action
 * prev, next
 *
 */
function displayLiveCourseContent(action) {
    if (action == "next") {
        var possibleRotation = currentRotation+1;
        var data = section_content_rotation.find(
            (variable, index) => possibleRotation == index
        );

        var beforeData = section_content_rotation.find(
            (variable, index) => currentRotation == index
        );

        if (data) {
            if(currentRotation < possibleRotation && data.complete==false && beforeData.complete==false){
                toastr.warning("Sorry! No Skipping");
                return;
            }else{
                currentRotation+=1;
            }

            switch (data.type) {
                case 'video':
                    if(data.source){
                        renderMainContent({
                            id: data.id,
                            type: data.type,
                            section: data.section_id,
                            rotation: currentRotation,
                        });
                    }else{
                        displayLiveCourseContent(action);
                    }
                    break;

                case 'quiz':
                    if(data.items && data.items.length > 0){
                        renderMainContent({
                            id: data.id,
                            type: data.type,
                            section: data.section_id,
                            rotation: currentRotation,
                        });
                    }else{
                        displayLiveCourseContent(action);
                    }
                    break;
            
                default:
                    renderMainContent({
                        id: data.id,
                        type: data.type,
                        section: data.section_id,
                        rotation: currentRotation,
                    });
                    break;
            }
        } else {
            displayLiveCourseNavigators(false, true);
            if(_is_preview=="live"){
                if(has_quiz){
                    if(has_completed_quiz && has_completed_course){
                        $(`#leave-a-rating-content`).modal("show");
                        $("#leave_a_rating, .leave-a-rating-icon").attr("onclick","$(`#leave-a-rating-content`).modal('show')");
                    }
                }else{
                    if(has_completed_course){
                        $(`#leave-a-rating-content`).modal("show");
                        $("#leave_a_rating, .leave-a-rating-icon").attr("onclick","$(`#leave-a-rating-content`).modal('show')");
                    }
                }
            }
        }
    } else {
        currentRotation -= 1;
        currentRotation = currentRotation <= 0 ? 0 : currentRotation;
        var data = section_content_rotation.find(
            (v, index) => currentRotation == index
        );
        if (data) {
            renderMainContent({
                id: data.id,
                type: data.type,
                section: data.section_id,
                rotation: currentRotation,
            });
        }
    }
}

function displayLiveCourseNavigators(right, left) {
    var navleft_button = $(`.course-nav-left--btn`);
    var navright_button = $(`.course-nav-right--btn`);

    navright_button.css("display", right ? "block" : "none");
    navleft_button.css("display", left ? "block" : "none");
}

/**
 * Sample Main Content Rotation
 * type                 | id | rotation
 * video, quiz, article | id | index of the array
 *
 */
function renderMainContent(data) {

    var currentData = section_content_rotation.find(
        (variable, index) => data.rotation == index
    );

    var beforeData = section_content_rotation.find(
        (variable, index) => currentRotation == index
    );
  
    if(currentRotation < data.rotation && currentData.complete==false && beforeData.complete==false){
        toastr.warning("Sorry! No Skipping");
        return;
    }

    currentRotation = data.rotation;
    if (MyVideoJS) {
        MyVideoJS.pause();
    }

    var wrapper = $(`#transition-content-wrapper`);
    var nonvideo_content = $("#non-video-content");
    var video_content = $(".video-js");

    if (data.type == "video") {
        if (data.rotation == 0) {
            displayLiveCourseNavigators(true, false);
        } else if (data.rotation + 1 == section_content_rotation.length) {
            displayLiveCourseNavigators(false, true);
        } else {
            displayLiveCourseNavigators(true, true);
        }

        currentVideo = section_content_rotation.find(
            (varData) => varData.type == "video" && varData.id == data.id
        );
        wrapper
            .addClass(`transition-content-wrapper--video`)
            .removeClass(`transition-content-wrapper--row`);
        video_content.show();
        nonvideo_content.hide();

        var vext_ = extract_file_ext(currentVideo.filename);
        MyVideoJS.poster(currentVideo.poster);
        MyVideoJS.src({
            type: `video/${vext_}`,
            src: `${currentVideo.source}#t=${currentVideo.current_play_time}`,
        });
        MyVideoJS.currentTime(currentVideo.current_play_time);
        MyVideoJS.thumbnails(currentVideo.thumbnail);
    } else {
        displayLiveCourseNavigators(false, false);
        wrapper
            .addClass(`transition-content-wrapper--row`)
            .removeClass(`transition-content-wrapper--video`);
        video_content.hide();

        if (data.type == "quiz") {
            currentQuiz = section_content_rotation.find(
                (varData) => varData.type == "quiz" && varData.id == data.id
            );
            currentQuizRotation = 0;

            if (currentQuiz.status == "none") {
                nonvideo_content
                    .empty()
                    .append(
                        `
                    <div class="kt-portlet" id="quiz-start-page">
                        <div class="kt-portlet__body">
                            <div class="row justify-content-center">
                                <h3>
                                    ${currentQuiz.title} <br/>
                                    <small>${currentQuiz.items.length} item${
                            currentQuiz.items.length > 1 ? "s" : ""
                        }</small>
                                </h3>
                            </div>
                            <div class="kt-space-20"></div>
                            <div class="kt-space-20"></div>
                            <button class="btn btn-label-danger" onClick="startQuiz({
                                'section': ${currentQuiz.section_id},
                                'id': ${data.id},
                                'items': ${currentQuiz.items.length},
                                'status': '${currentQuiz.status}',
                                'type': 'start',
                            })">Start Quiz</button>
                        </div>
                    </div>
                `
                    )
                    .append(renderQuiz(currentQuiz));
            } else if (currentQuiz.status == "completed") {
                var QuizOverall = JSON.parse(currentQuiz.overall);
                if(assessment.retry==="true"){
                    nonvideo_content
                        .empty()
                        .append(
                            `
                        <div class="kt-portlet" id="quiz-start-page">
                            <div class="kt-portlet__body">
                                <div class="row justify-content-center">
                                    <h3>
                                        ${currentQuiz.title} <br/>
                                        <small>${currentQuiz.items.length} item${
                                currentQuiz.items.length > 1 ? "s" : ""
                            }</small>
                                    </h3>
                                </div>
                                <div class="kt-space-20"></div>
                                <div class="kt-space-20"></div>
                                <button class="btn btn-warning" onClick="startQuiz({
                                    'section': ${currentQuiz.section_id},
                                    'id': ${data.id},
                                    'items': ${currentQuiz.items.length},
                                    'status': '${currentQuiz.status}',
                                    'type': 'restart',
                                })">Retry Quiz</button>
                            </div>
                        </div>
                    `
                        )
                    .append(renderQuiz(currentQuiz));
                }else{
                    nonvideo_content.empty()
                        .append(`<div class="kt-portlet" id="quiz-start-page">
                        <div class="kt-portlet__body">
                            <div id="quiz-completion-page" class="row justify-content-center">
                                <div class="col-10">
                                    <h4>
                                        Quiz Completed! You're ready to move on to the next lecture<br/>
                                        <small>You got <b>${QuizOverall.total}/${QuizOverall.items} - ${QuizOverall.percentage}%</b> correct answers</small>
                                    </h4>
                                </div>
                                <div class="col-10"><button class="btn btn-sm btn-label-success button-done-quiz" onclick="displayLiveCourseContent('next');">Done</button></div>
                            </div>
                        </div>
                    </div>`);
                }
            } else {
                nonvideo_content
                    .empty()
                    .append(
                        `
                    <div class="kt-portlet" id="quiz-start-page">
                        <div class="kt-portlet__body">
                            <div class="row justify-content-center">
                                <h3>
                                    ${currentQuiz.title} <br/>
                                    <small>${currentQuiz.items.length} item${
                            currentQuiz.items.length > 1 ? "s" : ""
                        }</small>
                                </h3>
                            </div>
                            <div class="kt-space-20"></div>
                            <div class="kt-space-20"></div>
                            <button class="btn btn-warning" onClick="startQuiz({
                                'section': ${currentQuiz.section_id},
                                'id': ${data.id},
                                'items': ${currentQuiz.items.length},
                                'status': '${currentQuiz.status}',
                                'type': 'restart',
                            })">Retry Quiz</button>
                        </div>
                    </div>
                `
                    )
                .append(renderQuiz(currentQuiz));
            }
        }

        if (data.type == "article") {
            var currentArticle = section_content_rotation.find(
                (varData) => varData.type == "article" && varData.id == data.id
            );
            nonvideo_content.empty().append(`
            <div class="kt-portlet">
                <div class="kt-portlet__head">
                    <div class="kt-portlet__head-label">
                        <h3 class="kt-portlet__head-title">${currentArticle.title}</h3>
                    </div>
                </div>
                <div class="kt-portlet__body">
                    <div class="kt-scroll" style="height:250px;overflow-y:scroll;">
                    ${currentArticle.body}
                    </div>
                    <br/>
                    <button class="btn btn-label-success button-done-article" onclick='completedArticle({
                        "id": ${data.id},
                        "section": ${currentArticle.section_id},
                        "rotation": ${data.rotation},
                    });'>Done</button>
                </div>
            </div>
            `);
        }

        nonvideo_content.show();
    }
}

/**
 * Quiz Rendering item bodies
 *
 */
function renderQuiz(params) {
    var quiz_wrapper = $(`<div class="kt-portlet" id="quiz-wrapper-items"/>`);
    var quiz_head = $(`<div class="kt-portlet__head" />`).append(
        `<div class="kt-portlet__head-label"><h3 class="kt-portlet__head-title">${
            params.title
        } <small id="current_quiz_pagination">0/${params.items.length} item${
            params.items.length > 1 ? "s" : ""
        }</small></h3></div>`
    );
    var quiz_body = $(`<div class="kt-portlet__body" style="overflow:auto;max-height:300px;" />`);

    params.items.forEach((varData, index) => {
        var quiz_item_body = $(
            `<div class="row justify-content-center" id="question_item_${varData.id}" />`
        );
        var quiz_item_choices = ``;
        var item_choices = JSON.parse(varData.choices);

        item_choices.forEach((choice) => {
            quiz_item_choices += `<div class="col">
                <label class="kt-radio kt-radio--bold">
                    <input type="radio" value="${
                        choice.answer
                    }" name="choices_item_${varData.id}"> ${choice.choice}
                    <span></span>
                </label> <br/>
                <span class="kt-hidden btn btn-success">${
                    choice.explain ? choice.explain : ""
                }</span>
            </div>`;
        });

        var quiz_item_buttons = `<div class="col-10"><button class="btn btn-sm btn-outline-danger" style="float:right" onclick="quizAnswerItem({
            'id': ${varData.id}, 
            'index': ${index},
            'quiz': ${params.id},
        })">Answer</button></div>`;

        quiz_item_body.append(`
            <div class="col-10"><p>${varData.question}</p></div>
            <div class="col-10">
                <div class="kt-space-20"></div>
                <div class="kt-space-20"></div>
                <div class="row justify-content-center">${quiz_item_choices}</div>
                <div class="kt-space-20"></div>
                <div class="kt-space-20"></div>
            </div>${quiz_item_buttons}
        `);

        if (index > 0) {
            quiz_item_body.hide();
        }

        quiz_body.append(quiz_item_body);
    });

    var quiz_completion_page = $(`<div id="quiz-completion-page" class="row justify-content-center">
            <div class="col-10 quiz-completion-page-title">
                <h4>
                    Quiz Completed! You're ready to move on to the next lecture<br/>
                    <small id="current_quiz_scoring"></small>
                </h4>
            </div>
            <div class="col-10 quiz-completion-page-button"><button class="btn btn-sm btn-label-success button-done-quiz" onclick="completedQuiz({'id':${params.id}, 'rotation': ${params.rotation}});">Done</button></div>
        </div>`);
    quiz_completion_page.hide();
    quiz_body.append(quiz_completion_page);

    return quiz_wrapper.append(quiz_head).append(quiz_body).hide();
}

function startQuiz(data) {
    
    var new_status = data.status == "none" ? "in-progress" : data.status;
    if (quizScores.length == 0) {
        quizScores.push({
            section: data.section,
            id: data.id,
            total: 0,
            items: data.items,
            status: new_status,
        });

        saveQuizProgress({
            section: data.section,
            id: data.id,
            overall: {
                total: 0,
                items: data.items,
                percentage: 0,
            },
            status: new_status,
        });
    } else {
        var find_q = quizScores.find((varData) => varData.id == data.id);
        if (find_q) {
            if(data.type=="restart"){
                quizScores = quizScores.map((varData) => {
                    if(varData.id == data.id){
                        return {
                            section: data.section,
                            id: data.id,
                            total: 0,
                            items: data.items,
                            status: "in-progress",
                        };
                    }else{
                        return varData;
                    }
                });
    
                saveQuizProgress({
                    section: data.section,
                    id: data.id,
                    overall: {
                        total: 0,
                        items: data.items,
                        percentage: 0,
                    },
                    status: new_status,
                });
            }
        } else {
            quizScores.push({
                section: data.section,
                id: data.id,
                total: 0,
                items: data.items,
                status: new_status,
            });

            saveQuizProgress({
                section: data.section,
                id: data.id,
                overall: {
                    total: 0,
                    items: data.items,
                    percentage: 0,
                },
                status: new_status,
            });
        }
    }

    $("#quiz-wrapper-items").show();
    $("#quiz-start-page").hide();
}

function reStartQuiz(data){
    quizScores = quizScores.map((varData) => {
        if(varData.id == data.id){
            saveQuizProgress({
                section: data.section,
                id: data.id,
                overall: {
                    total: 0,
                    items: data.items,
                    percentage: 0,
                },
                status: "in-progress",
            });

            return {
                section: data.section,
                id: data.id,
                total: 0,
                items: data.items,
                status: "in-progress",
            };
        }else{
            return varData;
        }
    });

    renderMainContent({
        type:'quiz', 
        id: data.id, 
        section: data.section, 
        rotation: data.rotation
    });
}

/**
 * Quiz Rendering next item body
 *
 */
function quizNextItem() {
    currentQuizRotation += 1;

    var the_score = quizScores.find((find_q) => currentQuiz.id == find_q.id);
    $(`#current_quiz_pagination`).html(
        `${currentQuizRotation}/${currentQuiz.items.length} item${
            currentQuiz.items.length > 1 ? "s" : ""
        }`
    );

    if (currentQuiz.items.length == currentQuizRotation) {
        currentQuiz.items.forEach((item, index) => {
            $(`#question_item_${item.id}`).hide();
        });

        // get back here
        var score_percentage =
            the_score.total != 0
                ? (the_score.total / currentQuiz.items.length) * 100
                : 0;

        if(assessment.assessment==="true"){
            $(`#current_quiz_scoring`).html(
                `You got <b>${the_score.total}/${currentQuiz.items.length} - ${(new Intl.NumberFormat('en-US', { minimumFractionDigits: 2, maximumFractionDigits: 2}).format(score_percentage))}%</b> correct answers`
            );

            if(assessment.retry==="true"){
                $(`#quiz-completion-page`).find(`.quiz-completion-page-button`).html(`<div class="kt-space-20"></div>
                    <div class="kt-space-20"></div>
                    <button class="btn btn-label-warning kt-margin-r-10" onClick="reStartQuiz({
                        'section': ${currentQuiz.section_id},
                        'id': ${currentQuiz.id},
                        'items': ${currentQuiz.items.length},
                    })">Start Again?</button> 
                    <button class="btn btn-success button-done-quiz" onclick="completedQuiz({'id':${currentQuiz.id}, 'rotation': ${currentQuizRotation}});">Continue</button>
                </div>`);
            }   
        }else{
            $(`#current_quiz_scoring`).html(
                `You got <b>${the_score.total}/${currentQuiz.items.length} - ${(new Intl.NumberFormat('en-US', { minimumFractionDigits: 2, maximumFractionDigits: 2}).format(score_percentage))}%</b> correct answers`
            );
        }    

        $(`#quiz-completion-page`).show();
    } else {
        currentQuiz.items.forEach((item, index) => {
            if (index == currentQuizRotation) {
                $(`#question_item_${item.id}`).show();
            } else {
                $(`#question_item_${item.id}`).hide();
            }
        });
    }
}

function quizAnswerItem(item) {
    var question_item_ = $(`#question_item_${item.id}`);
    var answer = $(`input[name="choices_item_${item.id}"]:checked`);
    var items = $(`input[name="choices_item_${item.id}"]:not(:checked)`);

    if (answer.length > 0) {
        question_item_
        .find(`button.btn-outline-danger`)
        .removeClass(`btn-outline-danger`)
        .addClass(`btn-success`)
        .html(`Next`)
        .attr(`onclick`, `quizNextItem()`);
        
        items.each(function () {
            $(this).attr(`disabled`, `disabled`);
        });

        if (answer.val() == "false") {
            answer.parent().addClass(`kt-radio--danger`);
            items.each(function () {
                var current = $(this);
                if (current.val() == "true") {
                    current
                        .parent()
                        .addClass(`kt-radio--success`)
                        .next()
                        .next()
                        .removeClass(function () {
                            if ($(this).html() != "") {
                                return `kt-hidden`;
                            }
                        });
                }
            });
        } else {
            var current = answer;
            if (current.val() == "true") {
                current
                    .parent()
                    .addClass(`kt-radio--success`)
                    .next()
                    .next()
                    .removeClass(function () {
                        if ($(this).html() != "") {
                            return `kt-hidden`;
                        }
                    });
            }
        }

        quizScores = quizScores.map((data) => {
            if (data.id == item.quiz) {
                var total = answer.val() == "true" ? (data.total + 1) : data.total;
                var score_percentage = data.total != 0 ? (data.total / data.items) * 100 : 0;
                
                if(currentQuizRotation+1 == data.items){
                    var status = "completed";
                }else{
                    var status = "in-progress";
                }

                currentQuiz.status = status;
                currentQuiz.overall = JSON.stringify({
                    total: total,
                    items: data.items,
                    percentage: score_percentage,
                });

                saveQuizProgress({
                    section: data.section,
                    id: data.id,
                    overall: {
                        total: total,
                        items: data.items,
                        percentage: score_percentage,
                    },
                    status: status,
                });

                return {
                    section: data.section,
                    id: data.id,
                    total: total,
                    items: data.items,
                    status: status,
                };
            }else{
                return data;
            }
        });  
    } else {
        toastr.error(`Please provide an answer!`);
    }
}

function completedQuiz(data) {
    var done_button = $(`button.button-done-quiz`);
    done_button.addClass(
        "kt-spinner kt-spinner--right kt-spinner--md kt-spinner--light"
    );

    var checkbox_ = $(`input[data-rotation="${currentRotation}"]`);
    if (checkbox_.data("status") == false) {
        if (currentQuiz.status == "completed") {

            total_completed_quizzes+=1;
            current_progress += 1;

            circle2.animate(current_progress / total_progress);
            circle1.animate(current_progress / total_progress);
            progress_label.html(
                `${current_progress} out of ${total_progress} complete`
            );

            checkbox_.prop("checked", true);
            checkbox_.data("status", true);

            if (_is_preview == "live") {
                if(current_progress==total_progress && total_progress!=0){
                    has_completed_course=true;
                    live_completed_course();
                }
            }

            section_content_rotation = section_content_rotation.map(
                (varData) => {
                    if (
                        varData.type == "quiz" &&
                        varData.id == data.id
                    ) {
                        varData.complete = true;
                    }
            
                    return varData;
                }
            );
        }
        
    }

    setTimeout(() => {
        done_button.removeClass(
            "kt-spinner kt-spinner--right kt-spinner--md kt-spinner--light"
        );

        displayLiveCourseContent("next");
    }, 1000);

    currentQuizRotation = 0;
    currentQuiz = {};
}

function saveQuizProgress(data) {
    $.ajax({
        url: "/course/live/progress",
        method: "POST",
        data: {
            course_id: $(`input[name="course_id"]`).val(),
            preview_: _is_preview,
            _token: $('input[name*="_token"]').val(),
            type: "quiz",
            data_: data,
        },
        success: function(response){
            if(assessment.assessment==="true"){
                check_overall_quiz_grade();
            }else{
                if(total_quizzes==total_completed_quizzes){
                    has_completed_quiz=true;
                }
            }
            var section_id = data.hasOwnProperty("section") ? data.section : data.section_id;
            var section_progress_panel = $(`h5#section-overall-count-${section_id}`);
            section_progress_panel.html(`${section_progress_panel.data("title")}<br/><small>${response.section_progress}/${section_progress_panel.data("sequencelength")} | ${section_progress_panel.data("sequencetime")} min</small>`);
        }
    });
}

function check_overall_quiz_grade(){
    $.ajax({
        url: "/course/live/quiz/grade",
        method: "POST",
        data: {
            course_id: $(`input[name="course_id"]`).val(),
            preview_: _is_preview,
            _token: $('input[name*="_token"]').val(),
            assessment: assessment,
        },
        success: function(response){
            if(response.status == "passed"){
                has_completed_quiz=true;
            }else{
                has_completed_quiz=false;
            }

            live_completed_quiz({
                required: true,
                percentage: assessment.percentage,
                correct_percentage: response.hasOwnProperty("correct_percentage") ? response.correct_percentage : 0,
                total_correct: response.hasOwnProperty("total") ? response.total : 0,
                total_items: response.hasOwnProperty("items") ? response.items : 0,
                status: response.status,
            });
        }
    });
}

function completedArticle(article) {
    var done_button = $(`button.button-done-article`);
    done_button.addClass(
        "kt-spinner kt-spinner--right kt-spinner--md kt-spinner--light"
    );

    var checkbox_ = $(`input[data-rotation="${currentRotation}"]`);
    if (checkbox_.data("status") == false) {
        if (_is_preview == "live") {
            current_progress += 1;
            circle2.animate(current_progress / total_progress);
            circle1.animate(current_progress / total_progress);
            progress_label.html(
                `${current_progress} out of ${total_progress} complete`
            );

            if(current_progress==total_progress && total_progress!=0){
                has_completed_course=true;
                live_completed_course();
            }
        }

        checkbox_.prop("checked", true);
        checkbox_.data("status", true);

        $.ajax({
            url: "/course/live/progress",
            method: "POST",
            data: {
                course_id: $(`input[name="course_id"]`).val(),
                preview_: _is_preview,
                _token: $('input[name*="_token"]').val(),
                type: "article",
                data_: article,
            },
            success: function (response) {
                toastr.success("Good!", "You've finished an article!");

                section_content_rotation = section_content_rotation.map(
                    (varData) => {
                        if (
                            varData.type == "article" &&
                            varData.id == article.id
                        ) {
                            varData.complete = true;
                        }
                
                        return varData;
                    }
                );

                displayLiveCourseContent("next");
                done_button.removeClass(
                    "kt-spinner kt-spinner--right kt-spinner--md kt-spinner--light"
                );

                // response--here
                var section_id = article.hasOwnProperty("section") ? article.section : article.section_id;
                var section_progress_panel = $(`h5#section-overall-count-${section_id}`);
                section_progress_panel.html(`${section_progress_panel.data("title")}<br/><small>${response.section_progress}/${section_progress_panel.data("sequencelength")} | ${section_progress_panel.data("sequencetime")} min</small>`);
            },
            error: function () {
                toastr.error(
                    "Error!",
                    "Something went wrong! Please refresh your browser"
                );
                done_button.removeClass(
                    "kt-spinner kt-spinner--right kt-spinner--md kt-spinner--light"
                );
            },
        });
    } else {
        displayLiveCourseContent("next");
    }
}

function completedVideo(video) {
    if (completed_video_sent == false) {
        completed_video_sent = true;

        var checkbox_ = $(`input[data-rotation="${currentRotation}"]`);
        if (checkbox_.data("status") == false) {
            if (_is_preview == "live") {
                current_progress += 1;
                circle2.animate(current_progress / total_progress);
                circle1.animate(current_progress / total_progress);
                progress_label.html(
                    `${current_progress} out of ${total_progress} complete`
                );

                if(current_progress==total_progress && total_progress!=0){
                    has_completed_course=true;
                    live_completed_course();
                    
                }
            }

            checkbox_.prop("checked", true);
            checkbox_.data("status", true);
            displayLiveCourseContent("next");
        }

        $.ajax({
            url: "/course/live/progress",
            method: "POST",
            data: {
                course_id: $(`input[name="course_id"]`).val(),
                preview_: _is_preview,
                _token: $('input[name*="_token"]').val(),
                type: "video",
                data_: video,
            },
            success: function (response) {
                // response--here
                var section_id = video.hasOwnProperty("section") ? video.section : video.section_id;
                var section_progress_panel = $(`h5#section-overall-count-${section_id}`);
                section_progress_panel.html(`${section_progress_panel.data("title")}<br/><small>${response.section_progress}/${section_progress_panel.data("sequencelength")} | ${section_progress_panel.data("sequencetime")} min</small>`);
                
                setTimeout(() => {
                    completed_video_sent = false;
                }, 1000);

            },
        });
    }
}

/**
 * Show & Hide Course Content
 *
 */
function courseContentEvent(open) {
    if (open) {
        $(`#main-content`).removeClass(`col-12`).addClass(`col-xl-8 col-md-8`);
        $(`#sidemenu-course-content`).show(
            `slide`,
            { direction: `right` },
            350
        );
        $(`.course-content-open-btn`).hide(
            `slide`,
            { direction: `right` },
            350
        );
        $(`.hidden-course-content`).hide(`slide`, { direction: `left` }, 350);
        $(`#tab-course-content`)
            .removeClass("active")
            .next()
            .addClass("active");

        return;
    }

    $(`#main-content`).removeClass(`col-xl-8 col-md-8`).addClass(`col-12`);
    $(`#sidemenu-course-content`).hide(`slide`, { direction: `right` }, 350);
    $(`.hidden-course-content`).show(`slide`, { direction: `left` }, 350);
    $(`.course-content-open-btn`)
        .show(`slide`, { direction: `right` }, 350)
        .hover(
            function () {
                $(`.btn-label-content`).html(`Show Course Content`);
                $(this).removeClass(`btn-icon`);
            },
            function () {
                $(`.btn-label-content`).html(``);
                $(this).addClass(`btn-icon`);
            }
        );
}

/**
 * Leave a Rating
 *
 */
function oneStepBack() {
    rating_step -= 1;

    if (rating_step == 0) {
        $(`#feedback-rating-textarea`).hide(
            "slide",
            { direction: "right" },
            300
        );
        $(`#feedback-back-button`).hide("slide", { direction: "right" }, 300);
    }

    if (rating_step == 1) {
        $(`#feedback-question-content`).hide(
            "slide",
            { direction: "right" },
            300
        );
        $(`#rating-content-body`).show("slide", { direction: "right" }, 300);
    }

    if (rating_step == 2) {
        $(`#feedback-question-content`).show(
            "slide",
            { direction: "right" },
            300
        );
        $(`#feedback-final-content`).hide("slide", { direction: "right" }, 300);
    }
}

function oneStepForward(id) {
    rating_step = Number($("#rating_step").val());
    rating_step += 1;
  
    if(id == "btn_ratings_remarks"){
        $.ajax({
            url: "/provider/review/api/savedRatingsRemarks",
            data: {
                ratings: $("#ratings_value").val(),
                remarks: $("#remarks").val(),
                course_id: $(
                    `input[name="course_id"]`
                ).val(),
                _token: $(
                    'input[name*="_token"]'
                ).val(),
            },
            success:function(response){
                var message = response.message;
                toastr.success("Thank You!", message);
                rating_step = 2;
                show_modal_rating(rating_step);
            },
            error:function(response){
                var message = response.message;
                toastr.error("Thank You!", message);
            },
        });
    } else if(id == "btn_course_performance"){

        $("#course_performance_form").validate({
            rules: {
                valuable_information:{
                    required: true
                },
                concepts_clear:{
                    required: true
                },
                instructor_delivery: {
                    required: true
                },
                opportunities: {
                    required: true
                },
                expectations: {
                    required: true
                },
                knowledgeable: {
                    required: true
                }
            },
            errorPlacement: function(error,element){
                error.appendTo( element.parents('.btn-group-toggle') );
            },
            invalidHandler: function(event, validator) {
               
            },
            submitHandler: function(form) {
                $.ajax({
                    url: "/provider/review/api/savedPerformance",
                    data: {
                        valuable_information: $(`input[name="valuable_information"]:checked`).val(),
                        concepts_clear: $(`input[name="concepts_clear"]:checked`).val(),
                        instructor_delivery: $(`input[name="instructor_delivery"]:checked`).val(),
                        opportunities: $(`input[name="opportunities"]:checked`).val(),
                        expectations: $(`input[name="expectations"]:checked`).val(),
                        knowledgeable: $(`input[name="knowledgeable"]:checked`).val(),
                        course_id: $(
                            `input[name="course_id"]`
                        ).val(),
                        _token: $(
                            'input[name*="_token"]'
                        ).val(),
                       
                    },
                    success:function(response){
                        toastr.success("Thank You!", "Course Performance Review Submitted.");
                       $("#certificate_hash").val(response.certificate_code);
                        rating_step = 3;
                        show_modal_rating(rating_step);
        
                        has_completed_rating=true;
                        live_completed_rating();
                    },
                    error:function(response){
                        toastr.error("Thank You!", "Something went wrong. Please contact your admin.");
                    },
                });
            }
        });
       
    } else {
        rating_step = 4;
        show_modal_rating(rating_step);
        $.ajax({
            url: "/provider/review/api/checkProgressReview",
            data: {
                course_id: $(
                    `input[name="course_id"]`
                ).val(),
                
            },
            success:function(response){
                var progress = response.status;
                
                if(progress == "finished"){
                    has_completed_course=true;
                    live_completed_course();
                }

                rating_step = response.rating_step;
                $("#rating_step").val(rating_step);

                if(rating_step >= 3){
                    has_completed_rating=true;
                    live_completed_rating();
                }

                if(has_quiz){
                    if(has_completed_quiz && has_completed_course){
                        $("#leave_a_rating, .leave-a-rating-icon").attr("onclick","$(`#leave-a-rating-content`).modal('show')");
                    }
                }else{
                    if(has_completed_course){
                        $("#leave_a_rating, .leave-a-rating-icon").attr("onclick","$(`#leave-a-rating-content`).modal('show')");
                    }
                }
                
            },
            error:function(){

            },
        });
    }
    
}

function show_modal_rating(rating_step){
    if (rating_step == 2) {
        $(`#feedback-question-content`).show(
            "slide",
            { direction: "right" },
            300
        );
        $(`#rating-content-body`).hide("slide", { direction: "right" }, 300);
    }

    if (rating_step == 3) {
        $(`#feedback-question-content`).hide(
            "slide",
            { direction: "right" },
            300
        );
        $(`#feedback-final-content`).show("slide", { direction: "right" }, 300);
    }

    if (rating_step >= 4) {
        $(`#feedback-final-content`).hide("slide", { direction: "right" }, 300);
        $(`#leave-a-rating-content`).modal("hide");

        /**
         * Reset
         *
         */
        $(`#feedback-rating-textarea`).hide(
            "slide",
            { direction: "right" },
            300
        );
        $(`#rating-content-body`).hide("slide", { direction: "right" }, 300);
        $(`#feedback-back-button`).hide("slide", { direction: "right" }, 300);
        $(`#feedback-final-content`).show("slide", { direction: "right" }, 300);

        rating_step = 0;
    }
}

/**
 *
 * Pass the selector in any form
 *
 */
function copy_(element) {
    var copyText = $(element);
    copyText.select();
    document.execCommand("copy", false);

    toastr.info("Copied!");
}

function open_send_email_modal(element) {
    var link = $(element).val();
    $(`#share-to-email-modal`).modal("show");
}

function send_email() {
    toastr.success("Email has been sent!");
}

function validation_on_checkbox_list() {
    /**
     *
     * Section Part checkbox validation and uix
     *
     */
    $(`input[name="section_part_checkbox"]`).each(function () {
        var current = $(this);
        if (current.data("status") == false) {
            current.prop("checked", false);
        } else {
            current.prop("checked", true);
        }
    });

    $(`input[name="section_part_checkbox"]`).click(function () {
        var current = $(this);
        if (current.data("status") == false) {
            current.prop("checked", false);
        } else {
            current.prop("checked", true);
        }
    });
}

function init_progress_circle() {
    // small screen
    circle1 = new ProgressBar.Circle("#live-course-progress-circle1", {
        strokeWidth: 10,
        easing: "easeInOut",
        duration: 1400,
        color: "#20c997",
        trailColor: "#fff",
        trailWidth: 1,
        svgStyle: null,
        step: function (state, circle) {
            circle.setText('<i class="fa fa-trophy"></i>');
        },
    });

    // big screen
    circle2 = new ProgressBar.Circle("#live-course-progress-circle2", {
        strokeWidth: 10,
        easing: "easeInOut",
        duration: 1400,
        color: "#20c997",
        trailColor: "#fff",
        trailWidth: 1,
        svgStyle: null,
        step: function (state, circle) {
            circle.setText('<i class="fa fa-trophy"></i>');
        },
    });

    circle2.animate(current_progress / total_progress);
    circle1.animate(current_progress / total_progress);
}
