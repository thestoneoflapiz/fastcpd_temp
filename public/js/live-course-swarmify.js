var window_width = $(window).width();
var rating_step = 0;

/**
 * Video Area
 * 
 */
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
    $('.rating').starRating({
        totalStars: 5,
        initialRating: 0,
        starShape: "rounded",
        starSize: 50,
        disableAfterRate: false,
        onHover: function (currentIndex, currentRating, $el) {
            var displayText = '';

            if (currentIndex == 5) {
                displayText = "Amazing, above expectation!";
            }

            if (currentIndex > 4 && currentIndex < 5) {
                displayText = "Good / Amazing";
            }

            if (currentIndex == 4) {
                displayText = "Good, what I expected";
            }

            if (currentIndex > 3 && currentIndex < 4) {
                displayText = "Average / Good";
            }

            if (currentIndex == 3) {
                displayText = "Average, could be better";
            }

            if (currentIndex > 2 && currentIndex < 3) {
                displayText = "Poor / Average";
            }

            if (currentIndex == 2) {
                displayText = "Poor, pretty disappointed";
            }

            if (currentIndex > 1 && currentIndex < 2) {
                displayText = "Awful / Poor";
            }

            if (currentIndex <= 1 && currentIndex > 0) {
                displayText = "Aww, not what I expected at all!";
            }

            $('.live-rating-label').text(displayText);
        },
        callback: function (currentRating, $el) {
            rating_step += 1;
            $(`#feedback-rating-textarea`).show('slide', { direction: 'right' }, 300);
            $(`#feedback-back-button`).show('slide', { direction: 'right' }, 300);
        }
    });

    get_sections();
});

/**
 * Video JS
 * Should have the initial video, recommended: preview video
 * 
 */
function initialize_videoJS(video) {
    if (MyVideoJS == null) {
        $(`#live-course-video`).attr(`src`, video.source).attr(`poster`, video.poster);
    }
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
            preview_: $(`input[name="preview_"]`).val(),
        },
        success: function (response) {
            generate_sections(response.data);
        },
        error: function () {
            toastr.error("Error!", "Something went wrong! Please refresh your browser");
        }
    });
}

function generate_sections(data) {
    var sidemenu_sections = $(`#sidemenu-course-content-accordion`);
    var tabcontent_sections = $(`#tab-course-content-accordion`);

    var sections = data.section_content_info;
    sections.forEach(section => {
        var sequence = section.detailed_sequence;
        var card = $(`<div class="card" />`);
        var card_header = $(`<div class="card-header">
            <div class="card-title collapsed" data-toggle="collapse" data-target="#course-content-collapse-${section.id}" aria-expanded="false" aria-controls="course-content-collapse-${section.id}">
                <h5>
                    ${section.title} <br/>
                    <small>0/${sequence.length} | ${section.total_time} min</small>
                </h5>
            </div>
        </div>`);

        var card_body_wrapper = $(`<div id="course-content-collapse-${section.id}" class="collapse" data-parent="" />`);
        var card_body = $(`<div class="card-body" />`);
        var section_list = $(`<div class="kt-checkbox-list" />`);

        var section_item_completion_list = [];
        sequence.forEach((data, index) => {
            switch (data.type) {
                case `video`:
                    var video_tab = $(`<label class="kt-checkbox" onclick="renderMainContent({type:'video', id:${data.id}, rotation:${data.rotation}})">
                        <input type="checkbox" name="section_part_checkbox" data-status="${data.complete}" data-rotation="${data.rotation}" data-type="video">
                        ${data.title}<br>
                        <i class="fa fa-play-circle"></i> &nbsp; ${data.video_length} min
                        <span></span>
                    </label>`);

                    section_list.append(video_tab);
                    initialize_videoJS(data);
                    break;

                case `article`:
                    var article_tab = $(`<label class="kt-checkbox" onclick="renderMainContent({type:'article', id:${data.id}, rotation: ${data.rotation}})">
                        <input type="checkbox" name="section_part_checkbox" data-status="${data.complete}" data-rotation="${data.rotation}" data-type="article">
                        ${data.title} <br>
                        <i class="fa fa-newspaper"></i> &nbsp; ${data.reading_time} min read
                        <span></span>
                    </label>`);

                    section_list.append(article_tab);
                    break;

                case `quiz`:
                    var quiz_tab = $(`<label class="kt-checkbox" onclick="renderMainContent({type:'quiz', id:${data.id}, rotation: ${data.rotation}})">
                        <input type="checkbox" name="section_part_checkbox" data-status="${data.complete}" data-rotation="${data.rotation}" data-type="quiz">
                        ${data.title} <br>
                        <i class="fa fa-list"></i> &nbsp; ${data.items.length} item${data.items.length > 1 ? 's' : ''}
                        <span></span>
                    </label>`);

                    section_list.append(quiz_tab);
                    break;
            }
        });

        card_body.append(section_list);
        card_body_wrapper.append(card_body);
        card.append(card_header).append(card_body_wrapper);

        var clone_tab = card.clone();
        var clone_sidemenu = card.clone();

        // assign to corresponding placement
        clone_tab.find(`div.card-title`).attr(`data-target`, `#tab-course-content-collapse-${section.id}`).attr(`aria-controls`, `tab-course-content-collapse-${section.id}`);
        clone_tab.find(`div#course-content-collapse-${section.id}`).attr(`id`, `tab-course-content-collapse-${section.id}`);

        clone_sidemenu.find(`div.card-title`).attr(`data-target`, `#sidemenu-course-content-collapse-${section.id}`).attr(`aria-controls`, `sidemenu-course-content-collapse-${section.id}`);
        clone_sidemenu.find(`div#course-content-collapse-${section.id}`).attr(`id`, `sidemenu-course-content-collapse-${section.id}`);

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
        if (section.complete == false) {
            (section.detailed_sequence).forEach(part => {
                if (part.complete == false && stop == false) {
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
    if (action == 'next') {
        currentRotation += 1;
        var data = section_content_rotation.find((variable, index) => currentRotation == index);
        if (data) {
            renderMainContent({ id: data.id, type: data.type, rotation: currentRotation });
        } else {
            displayLiveCourseNavigators(false, true);
            $(`#leave-a-rating-content`).modal("show");
        }
    } else {
        currentRotation -= 1;
        currentRotation = (currentRotation <= 0 ? 0 : currentRotation);
        var data = section_content_rotation.find((v, index) => currentRotation == index);
        if (data) {
            renderMainContent({ id: data.id, type: data.type, rotation: currentRotation });
        }
    }
}

function displayLiveCourseNavigators(right, left) {
    var navleft_button = $(`.course-nav-left--btn`);
    var navright_button = $(`.course-nav-right--btn`);

    navright_button.css('display', right ? 'block' : 'none');
    navleft_button.css('display', left ? 'block' : 'none');
}

/**
 * Sample Main Content Rotation
 * type                 | id | rotation
 * video, quiz, article | id | index of the array
 * 
 */
function renderMainContent(data) {
    currentRotation = data.rotation;

    var wrapper = $(`#transition-content-wrapper`);
    var nonvideo_content = $('#non-video-content');
    var video_content = $('.swarm-fluid');

    if (data.type == "video") {
        if (data.rotation == 0) {
            displayLiveCourseNavigators(true, false);
        } else if ((data.rotation + 1) == (section_content_rotation.length)) { displayLiveCourseNavigators(false, true); } else { displayLiveCourseNavigators(true, true); }

        var myVideoData = section_content_rotation.find(varData => varData.type == 'video' && varData.id == data.id);

        wrapper.addClass(`transition-content-wrapper--video`).removeClass(`transition-content-wrapper--row`);
        video_content.show(); nonvideo_content.hide();

        // MyVideoJS.poster(myVideoData.poster);
        // MyVideoJS.src({ type: 'video/mp4', src: myVideoData.source });
        // MyVideoJS.thumbnails(myVideoData.thumbnail);
        // MyVideoJS.play();
    } else {
        displayLiveCourseNavigators(false, false);
        wrapper.addClass(`transition-content-wrapper--row`).removeClass(`transition-content-wrapper--video`);
        video_content.hide();

        if (MyVideoJS) {
            MyVideoJS.pause();
        }

        if (data.type == "quiz") {
            var MyQuizData = section_content_rotation.find(varData => varData.type == 'quiz' && varData.id == data.id);
            currentQuizRotation = 0;
            currentQuiz = MyQuizData;

            nonvideo_content.empty().append(`
                <div class="kt-portlet" id="quiz-start-page">
                    <div class="kt-portlet__body">
                        <div class="row justify-content-center">
                            <h3>
                                ${MyQuizData.title} <br/>
                                <small>${MyQuizData.items.length} item${MyQuizData.items.length > 1 ? 's' : ''}</small>
                            </h3>
                        </div>
                        <div class="kt-space-20"></div>
                        <div class="kt-space-20"></div>
                        <button class="btn btn-label-danger" onClick="startQuiz({
                            'section': ${data.section_id},
                            'id': ${data.id},
                            'items': ${MyQuizData.items.length},
                            'status': '${MyQuizData.status}'
                        })">Start Quiz</button>
                    </div>
                </div>
            `).append(renderQuiz(MyQuizData));
        }

        if (data.type == "article") {

            var myArticleData = section_content_rotation.find(varData => varData.type == 'article' && varData.id == data.id);
            var stringify_data = JSON.stringify(data);
            nonvideo_content.empty().append(`
            <div class="kt-portlet">
                <div class="kt-portlet__head">
                    <div class="kt-portlet__head-label">
                        <h3 class="kt-portlet__head-title">${myArticleData.title}</h3>
                    </div>
                </div>
                <div class="kt-portlet__body">
                    <div class="kt-scroll" style="height:250px;overflow-y:scroll;">
                    ${myArticleData.body}
                    </div>
                    <br/>
                    <button class="btn btn-label-success button-done-article" onclick='completedArticle(${stringify_data});'>Done</button>
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
    var quiz_head = $(`<div class="kt-portlet__head" />`).append(`<div class="kt-portlet__head-label"><h3 class="kt-portlet__head-title">${params.title} <small id="current_quiz_pagination">0/${params.items.length} item${params.items.length > 1 ? 's' : ''}</small></h3></div>`);
    var quiz_body = $(`<div class="kt-portlet__body" />`);

    params.items.forEach((varData, index) => {
        var quiz_item_body = $(`<div class="row justify-content-center" id="question_item_${varData.id}" />`);
        var quiz_item_choices = ``;
        var item_choices = JSON.parse(varData.choices);

        item_choices.forEach(choice => {
            quiz_item_choices += `<div class="col-xl-3 col-md-3 col-sm-3 col-6">
                <label class="kt-radio kt-radio--bold">
                    <input type="radio" value="${choice.answer}" name="choices_item_${varData.id}"> ${choice.choice}
                    <span></span>
                </label> <br/>
                <span class="kt-hidden btn btn-success">${choice.explain ? choice.explain : ''}</span>
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
            <div class="col-10">
                <h4>
                    Quiz Completed! You're ready to move on to the next lecture<br/>
                    <small id="current_quiz_scoring"></small>
                </h4>
            </div>
            <div class="col-10"><button class="btn btn-sm btn-label-success button-done-quiz" onclick="completedQuiz({'id':${params.id}, 'rotation': ${params.rotation}});">Done</button></div>
        </div>`);
    quiz_completion_page.hide();
    quiz_body.append(quiz_completion_page);

    return quiz_wrapper.append(quiz_head).append(quiz_body).hide();
}

function startQuiz(data) {
    var new_status = data.status == "none" ? "in-progress" : data.status;
    if (quizScores.length == 0) {
        quizScores.push({
            id: data.id,
            complete: false,
            current: 0,
            total: 0,
            items: data.items,
            status: new_status,
        });

        saveQuizProgress({
            section: data.section,
            quiz: data.id,
            total: 0,
            items: data.items,
            status: new_status,
        });

    } else {
        var find_q = quizScores.find(varData => varData.id == data.id);
        if (find_q) {
            console.log(`Quiz has been revisited!`);
        } else {
            quizScores.push({
                id: data.id,
                complete: false,
                current: 0,
                total: 0,
                items: data.items,
                status: new_status,
            });

            saveQuizProgress({
                section: data.section,
                quiz: data.id,
                total: 0,
                items: data.items,
                status: new_status,
            });
        }
    }

    $('#quiz-wrapper-items').show();
    $('#quiz-start-page').hide();
}
/**
 * Quiz Rendering next item body
 *  
 */
function quizNextItem() {
    currentQuizRotation += 1;

    var the_score = quizScores.find(find_q => currentQuiz.id == find_q.id);
    $(`#current_quiz_pagination`).html(`${currentQuizRotation}/${currentQuiz.items.length} item${currentQuiz.items.length > 1 ? 's' : ''}`);
    if (currentQuiz.items.length == currentQuizRotation) {
        currentQuiz.items.forEach((item, index) => {
            $(`#question_item_${item.id}`).hide();
        });

        var score_percentage = the_score.total != 0 ? ((the_score.total / currentQuiz.items.length) * 100) : 0;
        quizScores = quizScores.map(find_q => {
            if (currentQuiz.id == find_q.id) {
                if (score_percentage < 75) {
                    find_q.status = "failed";
                    currentQuiz.status = "failed";
                } else { find_q.status = "passed"; currentQuiz.status = "passed"; }
            }

            return find_q;
        });
        currentQuiz.complete = true;

        $(`#current_quiz_scoring`).html(`You got <b>${the_score.total}/${currentQuiz.items.length} - ${score_percentage}%</b> correct answers`);
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

    question_item_.find(`button.btn-outline-danger`).removeClass(`btn-outline-danger`).addClass(`btn-success`).html(`Next`).attr(`onclick`, `quizNextItem()`);
    if (answer.length != 0) {
        items.each(function () {
            $(this).attr(`disabled`, `disabled`);
        });

        if (answer.val() == "false") {
            answer.parent().addClass(`kt-radio--danger`);
            items.each(function () {
                var current = $(this);
                if (current.val() == "true") {
                    current.parent().addClass(`kt-radio--success`).next().next().removeClass(function () {
                        if ($(this).html() != "") {
                            return `kt-hidden`;
                        }
                    });
                }
            });
        } else {
            var current = answer;
            if (current.val() == "true") {
                current.parent().addClass(`kt-radio--success`).next().next().removeClass(function () {
                    if ($(this).html() != "") {
                        return `kt-hidden`;
                    }
                });
            }
        }

        quizScores = quizScores.map(data => {
            if (data.id == item.quiz) {
                data.current = data.current + 1;
                data.total = answer.val() == "true" ? (data.total + 1) : data.total;
            }

            return data;
        });

    } else {
        toastr.error(`Please provide an answer!`);
    }
}

function completedQuiz(data) {
    var done_button = $(`button.button-done-quiz`);
    done_button.addClass('kt-spinner kt-spinner--right kt-spinner--md kt-spinner--light');

    // console.log(data);
    // console.log(section_content_rotation);
    // console.log(currentQuiz);

    var checkbox_ = $(`input[data-rotation="${data.rotation}"]`);
    if (checkbox_.data("status") == false) {
        checkbox_.prop("checked", true);
        checkbox_.data("status", true);
    }

    setTimeout(() => { // temporary
        done_button.removeClass('kt-spinner kt-spinner--right kt-spinner--md kt-spinner--light');
        displayLiveCourseContent('next');
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
            preview_: $(`input[name="preview_"]`).val(),
            _token: $('input[name*="_token"]').val(),
            type: "quiz",
            data_: data,
        }, success: function () {
            toastr.info("Saving Progress in Quiz...");

        }, error: function () {
            toastr.error("Error!", "Something went wrong! Please refresh your browser");
        }
    });
}

function completedArticle(article) {
    var done_button = $(`button.button-done-article`);
    done_button.addClass('kt-spinner kt-spinner--right kt-spinner--md kt-spinner--light');

    var checkbox_ = $(`input[data-rotation="${article.rotation}"]`);
    if (checkbox_.data("status") == false) {
        checkbox_.prop("checked", true);
        checkbox_.data("status", true);

        $.ajax({
            url: "/course/live/progress",
            method: "POST",
            data: {
                course_id: $(`input[name="course_id"]`).val(),
                preview_: $(`input[name="preview_"]`).val(),
                _token: $('input[name*="_token"]').val(),
                type: "article",
                data: article,
                complete: true,
            }, success: function () {
                toastr.success("Good!", "You've finished an article!");

                displayLiveCourseContent('next');
                done_button.removeClass('kt-spinner kt-spinner--right kt-spinner--md kt-spinner--light');
            }, error: function () {
                toastr.error("Error!", "Something went wrong! Please refresh your browser");
                done_button.removeClass('kt-spinner kt-spinner--right kt-spinner--md kt-spinner--light');
            }
        });
    } else {
        displayLiveCourseContent('next');
    }
}

/**
 * Show & Hide Course Content 
 *  
 */
function courseContentEvent(open) {
    if (open) {
        $(`#main-content`).removeClass(`col-12`).addClass(`col-xl-8 col-md-8`);
        $(`#sidemenu-course-content`).show(`slide`, { direction: `right` }, 350);
        $(`.course-content-open-btn`).hide(`slide`, { direction: `right` }, 350);
        $(`.hidden-course-content`).hide(`slide`, { direction: `left` }, 350);
        $(`#tab-course-content`).removeClass("active").next().addClass("active");

        return;
    }

    $(`#main-content`).removeClass(`col-xl-8 col-md-8`).addClass(`col-12`);
    $(`#sidemenu-course-content`).hide(`slide`, { direction: `right` }, 350);
    $(`.hidden-course-content`).show(`slide`, { direction: `left` }, 350);
    $(`.course-content-open-btn`).show(`slide`, { direction: `right` }, 350).hover(function () {
        $(`.btn-label-content`).html(`Show Course Content`);
        $(this).removeClass(`btn-icon`);
    }, function () {
        $(`.btn-label-content`).html(``);
        $(this).addClass(`btn-icon`);
    });
}

/**
 * Leave a Rating
 * 
 */
function oneStepBack() {
    rating_step -= 1;

    if (rating_step == 0) {
        $(`#feedback-rating-textarea`).hide('slide', { direction: 'right' }, 300);
        $(`#feedback-back-button`).hide('slide', { direction: 'right' }, 300);
    }

    if (rating_step == 1) {
        $(`#feedback-question-content`).hide('slide', { direction: 'right' }, 300);
        $(`#rating-content-body`).show('slide', { direction: 'right' }, 300);
    }

    if (rating_step == 2) {
        $(`#feedback-question-content`).show('slide', { direction: 'right' }, 300);
        $(`#feedback-final-content`).hide('slide', { direction: 'right' }, 300);
    }
}

function oneStepForward() {
    rating_step += 1;

    if (rating_step == 2) {
        $(`#feedback-question-content`).show('slide', { direction: 'right' }, 300);
        $(`#rating-content-body`).hide('slide', { direction: 'right' }, 300);
    }

    if (rating_step == 3) {
        $(`#feedback-question-content`).hide('slide', { direction: 'right' }, 300);
        $(`#feedback-final-content`).show('slide', { direction: 'right' }, 300);
    }

    if (rating_step >= 4) {
        $(`#feedback-final-content`).hide('slide', { direction: 'right' }, 300);
        $(`#leave-a-rating-content`).modal("hide");

        /**
         * Reset
         * 
         */
        $(`#feedback-rating-textarea`).hide('slide', { direction: 'right' }, 300);
        $(`#rating-content-body`).show('slide', { direction: 'right' }, 300);
        $(`#feedback-back-button`).hide('slide', { direction: 'right' }, 300);

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
    document.execCommand('copy', false);

    toastr.info("Copied!");
}

function open_send_email_modal(element) {
    var link = $(element).val();
    $(`#share-to-email-modal`).modal('show');
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
    $(`input[name="section_part_checkbox"]`).click(function () {
        var current = $(this);
        if (current.data('status') == false) {
            current.prop("checked", false);
        } else {
            current.prop("checked", true);
        }
    });
}
