var _is_preview = "preview";
var has_completed_webinar = false;
var has_completed_quiz = false;
var has_quiz = false;
var has_completed_rating = false;
var total_completed_quizzes = 0;
var total_quizzes = 0;

var window_width = $(window).width();

var total_progress = 0;
var current_progress = 0;

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
// list data for webinar content
var section_content_progress = [];

var assessment = null;

jQuery(document).ready(function () {
    get_grade_requirements();
    get_sections();
});

function get_grade_requirements(){

    $.ajax({
        url: "/webinar/live/api/grade_requirements",
        data: {
            webinar_id: $(`input[name="webinar_id"]`).val(),
        },
        success:function(response){
            assessment = response.assessment;
        },
        error:function(){
        },
    });
}

/**
 *
 * fetching data of webinar's sections
 */
function get_sections() {
    $.ajax({
        url: "/webinar/live/api/sections",
        data: {
            webinar_id: $(`input[name="webinar_id"]`).val(),
            preview_: _is_preview,
        },
        success: function (response) {
            var progress_ = response.progress;
            if (_is_preview == "live") {
                current_progress = progress_.current;
                total_progress = progress_.total;

                if(current_progress==total_progress && total_progress!=0){
                    has_completed_webinar = true;
                    live_completed_webinar();
                    $(".attendance-out-head").click(function(){
                        renderSubContents('end');
                    })
                }

                init_progress_circle();
                progress_label = $(`#live-webinar-progress-title`);
                progress_label.html(
                    `${current_progress} out of ${total_progress} complete`
                );
            }

            generate_sections(response.data);
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
    var tabcontent_sections = $(`#tab-webinar-content-accordion`);
    var sidemenu_sections = $(`#sidemenu-webinar-content-accordion`);

    tabcontent_sections.append(`
    <div class="card">
        <div class="card-header">
            <div class="card-title collapsed" data-toggle="collapse" aria-expanded="false" onclick="renderSubContents('start')">
                <h5>Welcome!</h5>
            </div>
        </div>
    </div>`);
    sidemenu_sections.append(`
    <div class="card">
        <div class="card-header">
            <div class="card-title collapsed" data-toggle="collapse" aria-expanded="false" onclick="renderSubContents('start')">
                <h5>Welcome!</h5>
            </div>
        </div>
    </div>`);

    var sections = data.section_content_info;
    sections.forEach((section) => {
        var sequence = section.detailed_sequence;
        var card = $(`<div class="card" />`);
        var card_header = $(`<div class="card-header">
            <div class="card-title collapsed" data-toggle="collapse" data-target="#webinar-content-collapse-${section.id}" aria-expanded="false" aria-controls="webinar-content-collapse-${section.id}">
                <h5 id="section-overall-count-${section.id}" data-title="${section.title}" data-sequencelength="${sequence.length}">
                    ${section.title} <br/>
                    <small>${section.current_progress}/${sequence.length}</small>
                </h5>
            </div>
        </div>`);

        var card_body_wrapper = $(
            `<div id="webinar-content-collapse-${section.id}" class="collapse" data-parent="" />`
        );
        var card_body = $(`<div class="card-body" />`);
        var section_list = $(`<div class="kt-checkbox-list" />`);

        var section_item_completion_list = [];
        sequence.forEach((data, index) => {
            switch (data.type) {
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
            .attr(`data-target`, `#tab-webinar-content-collapse-${section.id}`)
            .attr(`aria-controls`, `tab-webinar-content-collapse-${section.id}`);
        clone_tab
            .find(`div#webinar-content-collapse-${section.id}`)
            .attr(`id`, `tab-webinar-content-collapse-${section.id}`);

        clone_sidemenu
            .find(`div.card-title`)
            .attr(
                `data-target`,
                `#sidemenu-webinar-content-collapse-${section.id}`
            )
            .attr(
                `aria-controls`,
                `sidemenu-webinar-content-collapse-${section.id}`
            );
        clone_sidemenu
            .find(`div#webinar-content-collapse-${section.id}`)
            .attr(`id`, `sidemenu-webinar-content-collapse-${section.id}`);

        tabcontent_sections.append(clone_tab);
        sidemenu_sections.append(clone_sidemenu);

        section_content_progress.push({
            id: section.id,
            title: section.name,
            complete: false,
            items: section_item_completion_list,
        });
    });

    tabcontent_sections.append(`
    <div class="card">
        <div class="card-header">
            <div class="card-title collapsed attendance-out-head" data-toggle="collapse" aria-expanded="false" onclick="renderSubContents('end')">
                <h5>Thank you!</h5>
            </div>
        </div>
    </div>`);
    sidemenu_sections.append(`
    <div class="card">
        <div class="card-header">
            <div class="card-title collapsed attendance-out-head" data-toggle="collapse" aria-expanded="false" onclick="renderSubContents('end')">
                <h5>Thank you!</h5>
            </div>
        </div>
    </div>`);


    section_content_progress = sections;
    section_content_rotation = data.section_content_rotation;

    // showFirstRotation();
    validation_on_checkbox_list();
    toastr.success("Your live webinar is ready!");

    renderSubContents("start");
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
function displayLiveWebinarContent(action) {
    if($("[name='attendance_in']").val()==""){
        toastr.warning("Sorry! You must attend for <b>IN</b> first");
        return;
    }

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
                case 'quiz':
                    if(data.items && data.items.length > 0){
                        renderMainContent({
                            id: data.id,
                            type: data.type,
                            section: data.section_id,
                            rotation: currentRotation,
                        });
                    }else{
                        displayLiveWebinarContent(action);
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
            if(_is_preview=="live"){
                if(has_quiz){
                    if(has_completed_quiz && has_completed_webinar){
                        $(".attendance-out-head").click(function(){
                            renderSubContents('end');
                        });
                        renderSubContents('end');
                    }
                }else{
                    if(has_completed_webinar){
                        $(".attendance-out-head").click(function(){
                            renderSubContents('end');
                        });
                        renderSubContents('end');
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

/**
 * starting and ending page
 */
function renderSubContents(type){
    var content = $("#content");
    if(type=="start"){
        content.empty().append(`
            <div class="kt-portlet content-page" style="background-image: url(${webinar_info.webinar.poster});">
                <div class="kt-portlet__body">
                    <div class="row justify-content-center kt-margin-b-5">
                        <h3 class="kt-font-light">${webinar_info.webinar.title} </h3>
                    </div>
                    <div class="row justify-content-center kt-margin-b-15">
                        <div class="col-10">
                            <div class="form-group form-group-marginless">
                                <div class="input-group">
                                    <input type="text" class="form-control" readonly value="${webinar_info.session.link ? webinar_info.session.link : "FASTCPD WILL PROVIDE LINK"} " aria-describedby="basic-addon2">
                                    <div class="input-group-append"><a class="btn btn-info" target="_blank" href="${webinar_info.session.link ? webinar_info.session.link : "#" }" type="button">Go to Link</a></div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row justify-content-center">
                        <div class="col">
                            <button id="attendance-in-btn" class="btn btn-${webinar_info.attendance.session_in ? "success" : "danger"}" disabled>${webinar_info.attendance.session_in ? "You have attended for IN" : "Click here to confirm your attendance IN"}</button>
                        </div>
                    </div>
                </div>
            </div>
        `);
    }else{
        content.empty().append(`
            <div class="kt-portlet content-page" style="background-image: url(${webinar_info.webinar.poster});">
                <div class="kt-portlet__body">
                    <div class="row justify-content-center">
                        <h3 class="kt-font-light">Thankyou</h3>
                    </div>
                    <div class="row justify-content-center">
                        <div class="col">
                            <button id="attendance-out-btn" class="btn btn-${webinar_info.attendance.session_out ? "success" : "warning"}" disabled>${webinar_info.attendance.session_out ? "You have attended for OUT" : "Click here to confirm your attendance OUT"}</button>
                        </div>
                    </div>
                </div>
            </div>
        `);
    }

    content.show();
}

/**
 * Sample Main Content Rotation
 * type                 | id | rotation
 * quiz, article | id | index of the array
 *
 */
function renderMainContent(data) {
    if($("[name='attendance_in']").val()==""){
        toastr.warning("Sorry! You must attend for <b>IN</b> first");
        return;
    }

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
    var content = $("#content");
    if (data.type == "quiz") {
        currentQuiz = section_content_rotation.find(
            (varData) => varData.type == "quiz" && varData.id == data.id
        );
        currentQuizRotation = 0;

        if (currentQuiz.status == "none") {
            content
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
                            'status': '${currentQuiz.status}'
                        })">Start Quiz</button>
                    </div>
                </div>
            `
                )
                .append(renderQuiz(currentQuiz));
        } else if (currentQuiz.status == "complete") {
            var QuizOverall = JSON.parse(currentQuiz.overall);
            if(assessment.retry==="true"){
                content
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
                                'status': '${currentQuiz.status}'
                            })">Retry Quiz</button>
                        </div>
                    </div>
                `
                    )
                .append(renderQuiz(currentQuiz));
            }else{
                content.empty()
                    .append(`<div class="kt-portlet" id="quiz-start-page">
                    <div class="kt-portlet__body">
                        <div id="quiz-completion-page" class="row justify-content-center">
                            <div class="col-10">
                                <h4>
                                    Quiz Completed! You're ready to move on to the next lecture<br/>
                                    <small>You got <b>${QuizOverall.total}/${QuizOverall.items} - ${QuizOverall.percentage}%</b> correct answers</small>
                                </h4>
                            </div>
                            <div class="col-10"><button class="btn btn-sm btn-label-success button-done-quiz" onclick="displayLiveWebinarContent('next');">Done</button></div>
                        </div>
                    </div>
                </div>`);
            }
        } else {
            content
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
                            'status': '${currentQuiz.status}'
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
        content.empty().append(`
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

    content.show();
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
    var restartScore = quizScores.map((varData) => {
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
        }

        return varData;
    });

    quizScores = restartScore;
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
                `You got <b>${the_score.total}/${currentQuiz.items.length} - ${score_percentage}%</b> correct answers`
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
                `You got <b>${the_score.total}/${currentQuiz.items.length} - ${score_percentage}%</b> correct answers`
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
                data.total =
                    answer.val() == "true" ? data.total + 1 : data.total;
                
                var score_percentage =
                    data.total != 0 ? (data.total / data.items) * 100 : 0;
                
                if(currentQuizRotation+1 == data.items){
                    data.status = "completed";
                }else{
                    data.status = "in-progress";
                }

                currentQuiz.status = data.status;
                currentQuiz.overall = JSON.stringify({
                    total: data.total,
                    items: data.items,
                    percentage: score_percentage,
                });

                saveQuizProgress({
                    section: data.section,
                    id: data.id,
                    overall: {
                        total: data.total,
                        items: data.items,
                        percentage: score_percentage,
                    },
                    status: data.status,
                });
            }

            return data;
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

            checkbox_.prop("checked", true);
            checkbox_.data("status", true);

            if (_is_preview == "live") {
                if(current_progress==total_progress && total_progress!=0){
                    has_completed_webinar=true;
                    live_completed_webinar();
                    $(".attendance-out-head").click(function(){
                        renderSubContents('end');
                    })
                }
            }
        }
        
    }

    setTimeout(() => {
        done_button.removeClass(
            "kt-spinner kt-spinner--right kt-spinner--md kt-spinner--light"
        );

        displayLiveWebinarContent("next");
    }, 1000);

    currentQuizRotation = 0;
    currentQuiz = {};
}

function saveQuizProgress(data) {
    var section_id = data.hasOwnProperty("section") ? data.section : data.section_id;
    var section_progress_panel = $(`h5#section-overall-count-${section_id}`);
    section_progress_panel.html(`${section_progress_panel.data("title")}<br/><small>${current_progress}/${section_progress_panel.data("sequencelength")}</small>`);
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
            if(current_progress==total_progress && total_progress!=0){
                has_completed_webinar=true;
                live_completed_webinar();
                $(".attendance-out-head").click(function(){
                    renderSubContents('end');
                })
            }
        }

        checkbox_.prop("checked", true);
        checkbox_.data("status", true);

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

        displayLiveWebinarContent("next");
        done_button.removeClass(
            "kt-spinner kt-spinner--right kt-spinner--md kt-spinner--light"
        );

        var section_id = article.hasOwnProperty("section") ? article.section : article.section_id;
        var section_progress_panel = $(`h5#section-overall-count-${section_id}`);
        section_progress_panel.html(`${section_progress_panel.data("title")}<br/><small>${current_progress}/${section_progress_panel.data("sequencelength")}</small>`);
    } else {
        displayLiveWebinarContent("next");
    }
}

/**
 * Show & Hide Webinar Content
 *
 */
function webinarContentEvent(open) {
    if (open) {
        $(`#main-content`).removeClass(`col-12`).addClass(`col-xl-8 col-md-8`);
        $(`#sidemenu-webinar-content`).show(
            `slide`,
            { direction: `right` },
            350
        );
        $(`.webinar-content-open-btn`).hide(
            `slide`,
            { direction: `right` },
            350
        );
        $(`.hidden-webinar-content`).hide(`slide`, { direction: `left` }, 350);
        $(`#tab-webinar-content`)
            .removeClass("active")
            .next()
            .addClass("active");

        return;
    }

    $(`#main-content`).removeClass(`col-xl-8 col-md-8`).addClass(`col-12`);
    $(`#sidemenu-webinar-content`).hide(`slide`, { direction: `right` }, 350);
    $(`.hidden-webinar-content`).show(`slide`, { direction: `left` }, 350);
    $(`.webinar-content-open-btn`)
        .show(`slide`, { direction: `right` }, 350)
        .hover(
            function () {
                $(`.btn-label-content`).html(`Show Webinar Content`);
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

function copy_(){
    toastr.info('This is an Accreditor preview!')
}

function open_send_email_modal(){
    toastr.info('This is an Accreditor preview!')
}