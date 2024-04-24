var section_tabitem_count = 0;
var current_section = 1;

var section_part_data_initialization = {
    part: 1,
    section: 1,
};

var toolbar_show = [
    ["style", ["bold", "italic", "underline", "clear"]],
    ["font", ["strikethrough"]],
    ["fontsize", ["fontsize"]],
    ["color", ["color"]],
    ["para", ["ul", "ol", "paragraph"]],
    ["insert", ["link", "picture", "hr"]],
];

jQuery(document).ready(function () {
    get_sections();
});

function get_sections() {
    $.ajax({
        url: "/course/management/content/sections",
        success: function (response) {
            var data = response.data;
            generate_sections(data);
        },
        error: function () {
            toastr.error(
                "Error!",
                "Something went wrong! Please try again later."
            );
        },
    });
}

function generate_sections(sections) {
    var tablist = $(`#section-tablist`);
    tablist
        .empty()
        .append(
            `<li class="nav-item"><a class="btn btn-sm btn-success kt-shape-font-color-1" id="add_section" role="tab">Add Section</a></li>`
        );
    $(`#add_section`).click(function () {
        generate_new_section();
    });

    if (sections.length > 0) {
        sections.forEach((section, index) => {
            section_tabitem_count++;
            tablist.append(
                `<li class="nav-item" onclick="javascript:current_section=${
                section.section_number
                };"><a class="nav-link section_link ${
                index == 0 ? "active" : ""
                }" id="section-tabitem-${
                section.section_number
                }" data-toggle="tab" href="#section-tabpanel-${
                section.section_number
                }" role="tab">Section ${index + 1}</a></li>`
            );
            generate_section_content(false, section);
        });
    } else {
        section_tabitem_count++;
        tablist.append(
            `<li class="nav-item" onclick="javascript:current_section=1;"><a class="nav-link section_link active" id="section-tabitem-1" data-toggle="tab" href="#section-tabpanel-1" role="tab">Section 1</a></li>`
        );
        generate_section_content(true, { section: section_tabitem_count });
    }
}

function generate_new_section() {
    var tablist = $(`#section-tablist`);
    section_tabitem_count++;

    tablist.append(
        `<li class="nav-item" onclick="javascript:current_section=${section_tabitem_count};"><a class="nav-link section_link" id="section-tabitem-${section_tabitem_count}" data-toggle="tab" href="#section-tabpanel-${section_tabitem_count}" role="tab">Section ${section_tabitem_count}</a></li>`
    );
    generate_section_content(true, { section: section_tabitem_count });
}

function generate_section_content(is_new, section) {
    var tab = $(`#tab_content`);
    var content = $(`#hidden-section-clone`).clone();
    if (is_new == false) {
        /**
         * variable "section_number" was called here since the section's data is unable
         * to go through the jQuery elements' function in this kind of approach; so I created
         * a variable for them[function(){}] to notice the data.
         *
         */
        var section_number = section.section_number;

        content
            .attr(`id`, `section-tabpanel-${section.section_number}`)
            .addClass(`${section.section_number == 1 ? "active" : ""}`);
        content
            .find(`#hidden-section-body`)
            .attr(`id`, `section-${section.section_number}`);
        content
            .find(`#hidden-section-form`)
            .attr(`id`, `section-form-${section.section_number}`);
        content
            .find(`#hidden-section-form-alert`)
            .attr(`id`, `section-form-alert-${section.section_number}`);
        content
            .find(`#hidden-section-form-submit`)
            .attr(`id`, `section-form-submit-${section.section_number}`)
            .click(function () {
                FormDesignSection.init();
            });
        content
            .find(`#hidden-section-form-remove`)
            .attr(`id`, `section-form-remove-${section.section_number}`)
            .attr(`data-id`, section.section_number)
            .click(function () {
                removeSection(this);
            });

        /**
         * Inputs
         *
         */
        content
            .find(`#hidden-section-name-input`)
            .attr(`id`, `section-name-input-${section.section_number}`)
            .val(section.name)
            .keyup(function (event) {
                var value = event.target.value;
                if (value && value != "") {
                    $(`#section-name-label-${section_number}`).html(value);
                } else {
                    $(`#section-name-label-${section_number}`).html(
                        `Introduction`
                    );
                }
            });

        content
            .find(`#hidden-section-name-muted`)
            .attr(`id`, `section-name-muted-${section.section_number}`);
        content
            .find(`#hidden-section-name-label`)
            .attr(`id`, `section-name-label-${section.section_number}`)
            .html(section.name)
            .click(function () {
                $(this).hide();
                $(`#section-name-input-${section_number}`).show();
                $(`#section-name-muted-${section_number}`).show();
            });
        if (section.objective) {
            content
                .find(`#hidden-section-objective-input`)
                .attr(`id`, `section-objective-input-${section.section_number}`)
                .val(section.objective)
                .keyup(function (event) {
                    var value = event.target.value;
                    if (value && value != "") {
                        $(`#section-objective-label-${section_number}`).html(
                            value
                        );
                    } else {
                        $(`#section-objective-label-${section_number}`).html(
                            `Objective`
                        );
                    }
                });
            content
                .find(`#hidden-section-objective-muted`)
                .attr(
                    `id`,
                    `section-objective-muted-${section.section_number}`
                );
            content
                .find(`#hidden-section-objective-label`)
                .attr(`id`, `section-objective-label-${section.section_number}`)
                .html(section.objective)
                .click(function () {
                    $(this).hide();
                    $(`#section-objective-input-${section_number}`).show();
                    $(`#section-objective-muted-${section_number}`).show();
                });
        } else {
            content
                .find(`#hidden-section-objective-input`)
                .attr(`id`, `section-objective-input-${section.section_number}`)
                .keyup(function (event) {
                    var value = event.target.value;
                    if (value && value != "") {
                        $(`#section-objective-label-${section_number}`).html(
                            value
                        );
                    } else {
                        $(`#section-objective-label-${section_number}`).html(
                            `Objective`
                        );
                    }
                })
                .show();
            content
                .find(`#hidden-section-objective-muted`)
                .attr(`id`, `section-objective-muted-${section.section_number}`)
                .show();
            content
                .find(`#hidden-section-objective-label`)
                .attr(`id`, `section-objective-label-${section.section_number}`)
                .click(function () {
                    $(this).hide();
                    $(`#section-objective-input-${section_number}`).show();
                    $(`#section-objective-muted-${section_number}`).show();
                })
                .hide();
        }

        content
            .find(`#hidden-section-number`)
            .attr(`id`, `section-number-${section.section_number}`)
            .val(section.section_number);
        tab.append(content);

        // Part Selection & Content
        if (section.detailed_sequence.length > 0) {
            generateExistingParts(section);
        } else {
            generateParts({ part: 1, section: section.section_number });

            /**
             * For dynamic data initialization for UPPY
             *
             */
            section_part_data_initialization.part = 1;
            section_part_data_initialization.section = section.section_number;
            // PartVideoUppy.init();
            FormDesignVideo.init();
            FormDesignArticle.init();
            FormDesignQuiz.init();
            FormDesignQuizItem.init();
        }
    } else {
        content
            .attr(`id`, `section-tabpanel-${section.section}`)
            .addClass(`${section.section == 1 ? "active" : ""}`);
        content
            .find(`#hidden-section-body`)
            .attr(`id`, `section-${section.section}`);
        content
            .find(`#hidden-section-form`)
            .attr(`id`, `section-form-${section.section}`);
        content
            .find(`#hidden-section-form-alert`)
            .attr(`id`, `section-form-alert-${section.section}`);
        content
            .find(`#hidden-section-form-submit`)
            .attr(`id`, `section-form-submit-${section.section}`)
            .click(function () {
                FormDesignSection.init();
            });
        content
            .find(`#hidden-section-form-remove`)
            .attr(`id`, `section-form-remove-${section.section}`)
            .attr(`data-id`, section.section)
            .click(function () {
                removeSection(this);
            })
            .hide();

        /**
         * Inputs
         *
         */
        content
            .find(`#hidden-section-name-input`)
            .attr(`id`, `section-name-input-${section.section}`)
            .keyup(function (event) {
                var value = event.target.value;
                if (value && value != "") {
                    $(`#section-name-label-${section.section}`).html(value);
                } else {
                    $(`#section-name-label-${section.section}`).html(
                        `Introduction`
                    );
                }
            })
            .show();
        content
            .find(`#hidden-section-name-muted`)
            .attr(`id`, `section-name-muted-${section.section}`)
            .show();
        content
            .find(`#hidden-section-name-label`)
            .attr(`id`, `section-name-label-${section.section}`)
            .click(function () {
                $(this).hide();
                $(`#section-name-input-${section.section}`).show();
                $(`#section-name-muted-${section.section}`).show();
            })
            .hide();

        content
            .find(`#hidden-section-objective-input`)
            .attr(`id`, `section-objective-input-${section.section}`)
            .keyup(function (event) {
                var value = event.target.value;
                if (value && value != "") {
                    $(`#section-objective-label-${section.section}`).html(value);
                } else {
                    $(`#section-objective-label-${section.section}`).html(
                        `Objective`
                    );
                }
            })
            .show();
        content
            .find(`#hidden-section-objective-muted`)
            .attr(`id`, `section-objective-muted-${section.section}`)
            .show();
        content
            .find(`#hidden-section-objective-label`)
            .attr(`id`, `section-objective-label-${section.section}`)
            .click(function () {
                $(this).hide();
                $(`#section-objective-input-${section.section}`).show();
                $(`#section-objective-muted-${section.section}`).show();
            })
            .hide();

        content
            .find(`#hidden-section-number`)
            .attr(`id`, `section-number-${section.section}`)
            .val(section.section);
        tab.append(content);
    }
}

function generateExistingParts(section) {
    var section_div = $(`#section-${section.section_number}`);
    var sequence = section.detailed_sequence;

    sequence.forEach((seq, key) => {
        var data = { part: seq.part, section: section.section_number };

        var partcontent = $(`#hidden-part-clone`).clone();
        partcontent
            .attr(`id`, `part-${data.section}-${data.part}`)
            .show()
            .hover(
                function () {
                    partcontent.find(`.selection`).slideDown(100);

                    var last_part = section_div.find(`.part`).last();
                    if (
                        last_part.attr(`id`) ==
                        `part-${data.section}-${data.part}` &&
                        partcontent.find(".nav-link").hasClass("active")
                    ) {
                        partcontent.find(`.selection-after`).slideDown(100);
                    }
                },
                function () {
                    if (partcontent.find(".nav-link").hasClass("active")) {
                        partcontent.find(`.selection`).slideUp(100);
                        partcontent.find(`.selection-after`).slideUp(100);
                    }
                }
            );
        partcontent.find(`.button-remove-part`).click(function () {
            removeSectionExistingPart(data, seq);
        });
        partcontent.find(`.button-add-part`).click(function () {
            if (limitAdditionalPart(data)) {
                generateNewPartSelection(data, "before");
            } else {
                toastr.warning(
                    `Reminder!`,
                    `Please complete a part before adding another one!`
                );
            }
        });
        partcontent.find(`.selection`).hide();

        partcontent.find(`.button-add-part-after`).click(function () {
            if (limitAdditionalPart(data)) {
                generateNewPartSelection(data, "after");
            } else {
                toastr.warning(
                    `Reminder!`,
                    `Please complete a part before adding another one!`
                );
            }
        });
        partcontent
            .find(`.button-add-video`)
            .attr(`href`, `#part-video-content-${data.section}-${data.part}`)
            .click(function () {
                partcontent.find(`.nav-link.section_links`).hide();
            });
        partcontent
            .find(`.button-add-article`)
            .attr(`href`, `#part-article-content-${data.section}-${data.part}`)
            .click(function () {
                partcontent.find(`.nav-link.section_links`).hide();
            });
        partcontent
            .find(`.button-add-quiz`)
            .attr(`href`, `#part-quiz-content-${data.section}-${data.part}`)
            .click(function () {
                partcontent.find(`.nav-link.section_links`).hide();
            });

        partcontent
            .find(`#hidden-part-content`)
            .attr(`id`, `part-content-${data.section}-${data.part}`);
        // Video Part
        partcontent
            .find(`#hidden-part-video-content`)
            .attr(`id`, `part-video-content-${data.section}-${data.part}`);
        partcontent
            .find(`#hidden-part-video-form`)
            .attr(`id`, `part-video-form-${data.section}-${data.part}`);
        partcontent
            .find(`#hidden-part-video-form-alert`)
            .attr(`id`, `part-video-form-alert-${data.section}-${data.part}`);
        partcontent
            .find(`#hidden-part-video-accordion`)
            .attr(`id`, `part-video-accordion-${data.section}-${data.part}`);
        partcontent
            .find(`#hidden-part-video-card-title`)
            .attr(`id`, `part-video-card-title-${data.section}-${data.part}`)
            .attr(
                `data-target`,
                `#part-video-collapse-${data.section}-${data.part}`
            );
        partcontent
            .find(`#hidden-part-video-collapse`)
            .attr(`id`, `part-video-collapse-${data.section}-${data.part}`)
            .attr(
                `data-parent`,
                `#part-video-accordion-${data.section}-${data.part}`
            );

        /**
         * PART VIDEO! BEGIN!
         */
        partcontent.find(`#hidden-part-video-input`).attr(`id`, `part-video-input-${data.section}-${data.part}`)
        .keyup(function (event) {
            var value = event.target.value;
            if (value && value != "") {
                $(`#part-video-label-${data.section}-${data.part}`).html(value);
            } else {
                $(`#part-video-label-${data.section}-${data.part}`).html(`Video Title`);
            }
        });
        partcontent.find(`#hidden-part-video-muted`).attr(`id`, `part-video-muted-${data.section}-${data.part}`);
        partcontent.find(`#hidden-part-video-label`).attr(`id`, `part-video-label-${data.section}-${data.part}`)
        .click(function () {
            $(this).hide();
            $(`#part-video-input-${data.section}-${data.part}`).show();
            $(`#part-video-muted-${data.section}-${data.part}`).show();
        });
        /**
         * PART VIDEO FILE UPLOAD WITH PROGRESS BAR! BEGIN!
         */
        partcontent.find(`#hidden-part-video-upload`).attr(`id`, `part-video-upload-${data.section}-${data.part}`);
        partcontent.find(`#hidden-part-drag-drop-video-wrapper`).attr(`id`, `part-drag-drop-video-wrapper-${data.section}-${data.part}`);
        partcontent.find(`#hidden-part-video-input-file`).attr(`id`, `part-video-input-file-${data.section}-${data.part}`).change(function(){
            video_aws_upload(data);
        });
        partcontent.find(`#hidden-part-video-progress-bar-wrapper`).attr(`id`, `part-video-progress-bar-wrapper-${data.section}-${data.part}`);
        partcontent.find(`#hidden-part-video-progress-bar-percent`).attr(`id`, `part-video-progress-bar-percent-${data.section}-${data.part}`);
        partcontent.find(`#hidden-part-video-progress-bar-`).attr(`id`, `part-video-progress-bar-${data.section}-${data.part}`);
        
        partcontent.find(`#hidden-part-video-exist-buttons-div`).attr(`id`,`part-video-exist-buttons-div-${data.section}-${data.part}`);
        partcontent.find(`#hidden-part-video-view-button`).attr(`id`, `part-video-view-button-${data.section}-${data.part}`);
        partcontent.find(`#hidden-part-video-remove-button`).attr(`id`,`part-video-remove-button-${data.section}-${data.part}`);

        partcontent.find(`#hidden-video-url`).attr(`id`, `video-url-${data.section}-${data.part}`);
        partcontent.find(`#hidden-video-filename`).attr(`id`, `video-filename-${data.section}-${data.part}`);
        partcontent.find(`#hidden-video-size`).attr(`id`, `video-size-${data.section}-${data.part}`);
        partcontent.find(`#hidden-video-length`).attr(`id`, `video-length-${data.section}-${data.part}`);
        partcontent.find(`#hidden-video-id`).attr(`id`, `video-id-${data.section}-${data.part}`);
        /**
         * PART VIDEO FILE UPLOAD WITH PROGRESS BAR! END!
         */
        partcontent.find(`#hidden-part-video-form-submit`).attr(`id`, `part-video-form-submit-${data.section}-${data.part}`);
        /**
         * PART VIDEO! END!
         */


        // Article Part
        partcontent
            .find(`#hidden-part-article-content`)
            .attr(`id`, `part-article-content-${data.section}-${data.part}`);
        partcontent
            .find(`#hidden-part-article-accordion`)
            .attr(`id`, `part-article-accordion-${data.section}-${data.part}`);
        partcontent
            .find(`#hidden-part-article-collapse`)
            .attr(`id`, `part-article-collapse-${data.section}-${data.part}`)
            .attr(
                `data-parent`,
                `#part-article-accordion-${data.section}-${data.part}`
            );
        partcontent
            .find(`#hidden-part-article-form`)
            .attr(`id`, `part-article-form-${data.section}-${data.part}`);
        partcontent
            .find(`#hidden-part-article-form-alert`)
            .attr(`id`, `part-article-form-alert-${data.section}-${data.part}`);
        partcontent
            .find(`#hidden-part-article-card-title`)
            .attr(`id`, `part-article-card-title-${data.section}-${data.part}`)
            .attr(
                `data-target`,
                `#part-article-collapse-${data.section}-${data.part}`
            );
        partcontent
            .find(`#hidden-part-article-input`)
            .attr(`id`, `part-article-input-${data.section}-${data.part}`)
            .keyup(function (event) {
                var value = event.target.value;
                if (value && value != "") {
                    $(`#part-article-label-${data.section}-${data.part}`).html(
                        value
                    );
                } else {
                    $(`#part-article-label-${data.section}-${data.part}`).html(
                        `Article Title`
                    );
                }
            });

        partcontent
            .find(`#hidden-part-article-muted`)
            .attr(`id`, `part-article-muted-${data.section}-${data.part}`);
        partcontent
            .find(`#hidden-part-article-label`)
            .attr(`id`, `part-article-label-${data.section}-${data.part}`)
            .click(function () {
                $(this).hide();
                $(`#part-article-input-${data.section}-${data.part}`).show();
                $(`#part-article-muted-${data.section}-${data.part}`).show();
            });
        partcontent
            .find(`#hidden-part-article-id`)
            .attr(`id`, `part-article-id-${data.section}-${data.part}`);
        // Article TextArea
        partcontent
            .find(`#hidden-part-article-textarea-div`)
            .attr(
                `id`,
                `part-article-textarea-div-${data.section}-${data.part}`
            );
        partcontent
            .find(`#hidden-part-article-body-textarea`)
            .attr(
                `id`,
                `part-article-body-textarea-${data.section}-${data.part}`
            );
        partcontent
            .find(`#hidden-part-article-body-muted`)
            .attr(`id`, `part-article-body-muted-${data.section}-${data.part}`);
        partcontent
            .find(`#hidden-part-article-body-div`)
            .attr(`id`, `part-article-body-div-${data.section}-${data.part}`)
            .click(function () {
                $(this).hide();
                $(
                    `#part-article-textarea-div-${data.section}-${data.part}`
                ).show();
                $(
                    `#part-article-body-muted-${data.section}-${data.part}`
                ).show();
            });
        // Article Part Submit
        partcontent
            .find(`#hidden-part-article-form-submit`)
            .attr(
                `id`,
                `part-article-form-submit-${data.section}-${data.part}`
            );

        // Quiz Part
        partcontent
            .find(`#hidden-part-quiz-content`)
            .attr(`id`, `part-quiz-content-${data.section}-${data.part}`);
        partcontent
            .find(`#hidden-part-quiz-accordion`)
            .attr(`id`, `part-quiz-accordion-${data.section}-${data.part}`);
        partcontent
            .find(`#hidden-part-quiz-collapse`)
            .attr(`id`, `part-quiz-collapse-${data.section}-${data.part}`)
            .attr(
                `data-parent`,
                `#part-quiz-accordion-${data.section}-${data.part}`
            );
        partcontent
            .find(`#hidden-part-quiz-form`)
            .attr(`id`, `part-quiz-form-${data.section}-${data.part}`);
        partcontent
            .find(`#hidden-part-quiz-form-alert`)
            .attr(`id`, `part-quiz-form-alert-${data.section}-${data.part}`);
        partcontent
            .find(`#hidden-part-quiz-card-title`)
            .attr(`id`, `part-quiz-card-title-${data.section}-${data.part}`)
            .attr(
                `data-target`,
                `#part-quiz-collapse-${data.section}-${data.part}`
            );
        partcontent
            .find(`#hidden-part-quiz-input`)
            .attr(`id`, `part-quiz-input-${data.section}-${data.part}`)
            .keyup(function (event) {
                var value = event.target.value;
                $(`#part-quiz-label-${data.section}-${data.part}`).html(
                    value ? value : "Quiz Title"
                );
            });
        partcontent
            .find(`#hidden-part-quiz-muted`)
            .attr(`id`, `part-quiz-muted-${data.section}-${data.part}`);
        partcontent
            .find(`#hidden-part-quiz-label`)
            .attr(`id`, `part-quiz-label-${data.section}-${data.part}`)
            .click(function () {
                $(this).hide();
                $(`#part-quiz-input-${data.section}-${data.part}`).show();
                $(`#part-quiz-muted-${data.section}-${data.part}`).show();
            });
        partcontent
            .find(`#hidden-part-quiz-id`)
            .attr(`id`, `part-quiz-id-${data.section}-${data.part}`);
        // Quiz Part Submit
        partcontent
            .find(`#hidden-part-quiz-form-submit`)
            .attr(`id`, `part-quiz-form-submit-${data.section}-${data.part}`);

        // Quiz Item Wrapper
        partcontent
            .find(`#hidden-part-quiz-item-wrapper`)
            .attr(`id`, `part-quiz-item-wrapper-${data.section}-${data.part}`);
        partcontent.find(`.button-add-quiz-item`).click(function () {
            generate_new_quiz_item(data);
            $(this).hide().next().show();
        });
        partcontent.find(`.button-back-quiz-list`).click(function () {
            show_quiz_list(data);
            $(this).hide().prev().show();
        });
        partcontent
            .find(`#hidden-part-quiz-item-list`)
            .attr(`id`, `part-quiz-item-list-${data.section}-${data.part}`);
        partcontent
            .find(`#hidden-part-quiz-item-body-wrapper`)
            .attr(
                `id`,
                `part-quiz-item-body-wrapper-${data.section}-${data.part}`
            );

        // Quiz Item Form
        partcontent
            .find(`#hidden-part-quiz-item-form`)
            .attr(`id`, `part-quiz-item-form-${data.section}-${data.part}`);
        partcontent
            .find(`#hidden-part-quiz-item-form-alert`)
            .attr(
                `id`,
                `part-quiz-item-form-alert-${data.section}-${data.part}`
            );
        partcontent
            .find(`#hidden-part-quiz-item-id`)
            .attr(`id`, `part-quiz-item-id-${data.section}-${data.part}`);

        partcontent
            .find(`#hidden-part-quiz-textarea-div`)
            .attr(`id`, `part-quiz-textarea-div-${data.section}-${data.part}`);
        partcontent
            .find(`#hidden-part-quiz-body-textarea`)
            .attr(`id`, `part-quiz-body-textarea-${data.section}-${data.part}`);
        partcontent
            .find(`#hidden-part-quiz-body-div`)
            .attr(`id`, `part-quiz-body-div-${data.section}-${data.part}`)
            .click(function () {
                $(this).hide().prev().show();
            });
        partcontent
            .find(`#hidden-part-quiz-body-muted`)
            .attr(`id`, `part-quiz-body-muted-${data.section}-${data.part}`);

        partcontent
            .find(`input[name="hidden-choices"]`)
            .attr(
                `name`,
                `part-quiz-item-choices-${data.section}-${data.part}`
            );
        partcontent
            .find(`input[name="hidden-part-quiz-choice-1"]`)
            .attr(`name`, `part-quiz-choice-1-${data.section}-${data.part}`);
        partcontent
            .find(`input[name="hidden-part-quiz-choice-explain-1"]`)
            .attr(
                `name`,
                `part-quiz-choice-explain-1-${data.section}-${data.part}`
            );
        partcontent
            .find(`input[name="hidden-part-quiz-choice-2"]`)
            .attr(`name`, `part-quiz-choice-2-${data.section}-${data.part}`);
        partcontent
            .find(`input[name="hidden-part-quiz-choice-explain-2"]`)
            .attr(
                `name`,
                `part-quiz-choice-explain-2-${data.section}-${data.part}`
            );
        partcontent
            .find(`input[name="hidden-part-quiz-choice-3"]`)
            .attr(`name`, `part-quiz-choice-3-${data.section}-${data.part}`);
        partcontent
            .find(`input[name="hidden-part-quiz-choice-explain-3"]`)
            .attr(
                `name`,
                `part-quiz-choice-explain-3-${data.section}-${data.part}`
            );
        partcontent
            .find(`input[name="hidden-part-quiz-choice-4"]`)
            .attr(`name`, `part-quiz-choice-4-${data.section}-${data.part}`);
        partcontent
            .find(`input[name="hidden-part-quiz-choice-explain-4"]`)
            .attr(
                `name`,
                `part-quiz-choice-explain-4-${data.section}-${data.part}`
            );

        partcontent
            .find(`#hidden-part-quiz-item-form-submit`)
            .attr(
                `name`,
                `part-quiz-item-form-submit-${data.section}-${data.part}`
            );

        section_div.append(partcontent);

        // Apply data to parts
        switch (seq.type) {
            case `video`:
                $(`#part-video-collapse-${data.section}-${data.part}`).removeClass(`show`);
                $(`#part-video-card-title-${data.section}-${data.part}`).attr(`aria-expanded`, false).addClass(`collapsed`);
                partcontent.find(`.nav-link.section_links`).hide();
                $(`#part-video-content-${data.section}-${data.part}`).addClass("active");
                partcontent.find(`.button-add-video`).addClass("active");

                $(`#part-video-label-${data.section}-${data.part}`).html(seq.title);
                $(`#part-video-input-${data.section}-${data.part}`).val(seq.title);
                /** video_id */

                $(`#video-id-${data.section}-${data.part}`).val(seq.id);

                var video_content_title = seq.title;
                var part_content_title_limit = video_content_title.length > 35 ? `${video_content_title.substring(0, 35)}...` : video_content_title;

                $(`#part-video-remove-button-${data.section}-${data.part}`).click(function () {
                    var remove_modal = $(`#part-removal-video-modal`);
                    remove_modal.find(`#part-removal-video-modal-submit`).attr(`data-part`, data.part).attr(`data-section`, data.section);
                    remove_modal.find(`.modal-body`).html(`You're removing the <b>Part #${data.part}</b>'s video titled <b>"${seq.title}"</b> from your current <b>Section</b>.<br><br>Are you sure you want to remove it?`);
                    remove_modal.modal(`show`);
                });

                if(seq.cdn_url){
                    partcontent.find(`#part-video-title`).html(`Video — ${part_content_title_limit} &nbsp; <i class="fa fa-check-circle kt-font-success" style="font-size:1rem;"></i>`);
                    partcontent.find(`.video-filename`).each(function (){$(this).html(`Name: ${seq.filename}`);});
                    partcontent.find(`.video-length`).html(`Length: ${seq["length"]} mins`);

                    // $(`#part-video-upload-${data.section}-${data.part}`).hide();
                    $(`#part-video-exist-buttons-div-${data.section}-${data.part}`).show();
                    $(`#part-video-view-button-${data.section}-${data.part}`).click(function () {
                        window.open(`/course/management/content/preview/${seq.id}`);
                    }); 

                    $(`#video-url-${data.section}-${data.part}`).val(seq.cdn_url);
                    $(`#video-filename-${data.section}-${data.part}`).val(seq.filename);
                    $(`#video-size-${data.section}-${data.part}`).val(seq.size);
                }else{
                    partcontent.find(`.video-filename`).each(function (){$(this).html(`Name: ${seq.filename}`);});
                    partcontent.find(`#part-video-title`).html(`Video — ${part_content_title_limit}`);
                    if(seq.uploading_status=="uploading"){
                        partcontent.find(`#part-video-title`).html(`Video — ${part_content_title_limit} &nbsp; <i class="fa fa-minus-circle kt-font-warning" style="font-size:1rem;"></i>`);
                        // $(`#part-video-upload-${data.section}-${data.part}`).hide();
                        $(`#part-video-uploading-buttons-div-${data.section}-${data.part}`).show();
                        
                        $(`#part-video-refresh-button-${data.section}-${data.part}`).click(function(){
                            partVideoRefresh(data, {id:seq.id, title: seq.title});
                        });
                        $(`#part-video-cancel-button-${data.section}-${data.part}`).click(function(){
                            partVideoCancelUpload(data, {id:seq.id, title: seq.title});
                        });
                    }else{
                        if(seq.uploading_status=="failed"){
                            partcontent.find(`#part-video-title`).html(`Video — ${part_content_title_limit} &nbsp; <i class="fa fa-exclamation-circle kt-font-danger" style="font-size:1rem;"></i>`);
                        }
                        $(`#part-video-upload-${data.section}-${data.part}`).show();
                    }
                }
                break;

            case `article`:
                $(
                    `#part-article-collapse-${data.section}-${data.part}`
                ).removeClass(`show`);
                $(`#part-article-card-title-${data.section}-${data.part}`)
                    .attr(`aria-expanded`, false)
                    .addClass(`collapsed`);

                partcontent.find(`.nav-link.section_links`).hide();
                $(
                    `#part-article-content-${data.section}-${data.part}`
                ).addClass("active");
                partcontent.find(`.button-add-article`).addClass("active");

                var article_content_title = seq.title;
                var part_content_title_limit =
                    article_content_title.length > 35
                        ? `${article_content_title.substring(0, 35)}...`
                        : article_content_title;
                partcontent
                    .find(`#part-article-title`)
                    .html(
                        `Article — ${part_content_title_limit} &nbsp; <i class="fa fa-check-circle kt-font-success" style="font-size:1rem;"></i>`
                    );

                $(`#part-article-id-${data.section}-${data.part}`).val(seq.id);
                $(`#part-article-label-${data.section}-${data.part}`).html(
                    seq.title
                );
                $(`#part-article-input-${data.section}-${data.part}`).val(
                    seq.title
                );
                $(`#part-article-body-div-${data.section}-${data.part}`).html(
                    seq.description
                );
                $(
                    `#part-article-body-textarea-${data.section}-${data.part}`
                ).html(seq.description);
                break;

            case `quiz`:
                $(
                    `#part-quiz-collapse-${data.section}-${data.part}`
                ).removeClass(`show`);
                $(`#part-quiz-card-title-${data.section}-${data.part}`)
                    .attr(`aria-expanded`, false)
                    .addClass(`collapsed`);
                partcontent.find(`.nav-link.section_links`).hide();
                $(`#part-quiz-content-${data.section}-${data.part}`).addClass(
                    "active"
                );

                var quiz_content_title = seq.title;
                var part_content_title_limit =
                    quiz_content_title.length > 35
                        ? `${quiz_content_title.substring(0, 35)}...`
                        : quiz_content_title;
                var should_display_icon = seq.items.length > 0 ? true : false;
                partcontent
                    .find(`#part-quiz-title`)
                    .html(
                        `Quiz — ${part_content_title_limit} &nbsp; ${
                        should_display_icon
                            ? '<i class="fa fa-check-circle kt-font-success" style="font-size:1rem;"></i>'
                            : ""
                        }`
                    );

                partcontent.find(`.button-add-quiz`).addClass("active");
                $(`#part-quiz-id-${data.section}-${data.part}`).val(seq.id);
                $(`#part-quiz-label-${data.section}-${data.part}`).html(
                    seq.title
                );
                $(`#part-quiz-input-${data.section}-${data.part}`).val(
                    seq.title
                );
                $(
                    `#part-quiz-item-wrapper-${data.section}-${data.part}`
                ).show();

                generate_quiz_item(seq, data);
                break;
        }

        /**
         * After existing data to make sure the html is initialized
         * with the summernote
         *
        */
        initiateSummernote(`#part-article-body-textarea-${data.section}-${data.part}`, toolbar_show, 80, 10000, "");
        initiateSummernote(`#part-quiz-body-textarea-${data.section}-${data.part}`, toolbar_show, 80, 1000, "");

        /**
         * For dynamic data initialization for UPPY
         *
         */
        section_part_data_initialization.part = data.part;
        section_part_data_initialization.section = data.section;

        // PartVideoUppy.init();
        FormDesignVideo.init();
        FormDesignArticle.init();
        FormDesignQuiz.init();
        FormDesignQuizItem.init();
    });
}

function generateParts(data) {
    var section_div = $(`#section-${data.section}`);
    var part = $(`#hidden-part-clone`).clone();
    part.find(`.selection`).addClass(`selection-bottom-margin`);
    part.attr(`id`, `part-${data.section}-${data.part}`)
        .show()
        .hover(
            function () {
                part.find(`.selection`).slideDown(100);

                var last_part = section_div.find(`.part`).last();
                if (
                    last_part.attr(`id`) ==
                    `part-${data.section}-${data.part}` &&
                    part.find(".nav-link").hasClass("active")
                ) {
                    part.find(`.selection-after`).slideDown(100);
                }
            },
            function () {
                // hover out
                if (part.find(".nav-link").hasClass("active")) {
                    part.find(`.selection`).slideUp(100);
                    part.find(`.selection-after`).slideUp(100);
                }
            }
        );
    part.find(`.button-remove-part`).click(function () {
        removeSectionPart(data);
    });
    part.find(`.button-add-part`).click(function () {
        if (limitAdditionalPart(data)) {
            generateNewPartSelection(data, "before");
        } else {
            toastr.warning(
                `Reminder!`,
                `Please complete a part before adding another one!`
            );
        }
    });
    part.find(`.button-add-part-after`).click(function () {
        if (limitAdditionalPart(data)) {
            generateNewPartSelection(data, "after");
        } else {
            toastr.warning(
                `Reminder!`,
                `Please complete a part before adding another one!`
            );
        }
    });
    part.find(`.button-add-video`)
        .attr(`href`, `#part-video-content-${data.section}-${data.part}`)
        .click(function () {
            part.find(`.selection`).removeClass(`selection-bottom-margin`);
            part.find(`.nav-link.section_links`).hide();
        });
    part.find(`.button-add-article`)
        .attr(`href`, `#part-article-content-${data.section}-${data.part}`)
        .click(function () {
            part.find(`.selection`).removeClass(`selection-bottom-margin`);
            part.find(`.nav-link.section_links`).hide();
        });
    part.find(`.button-add-quiz`)
        .attr(`href`, `#part-quiz-content-${data.section}-${data.part}`)
        .click(function () {
            part.find(`.selection`).removeClass(`selection-bottom-margin`);
            part.find(`.nav-link.section_links`).hide();
        });

    part.find(`#hidden-part-content`).attr(
        `id`,
        `part-content-${data.section}-${data.part}`
    );
    // Video Part
    part.find(`#hidden-part-video-content`).attr(
        `id`,
        `part-video-content-${data.section}-${data.part}`
    );
    part.find(`#hidden-part-video-form`).attr(
        `id`,
        `part-video-form-${data.section}-${data.part}`
    );
    part.find(`#hidden-part-video-form-alert`).attr(
        `id`,
        `part-video-form-alert-${data.section}-${data.part}`
    );
    part.find(`#hidden-part-video-accordion`).attr(
        `id`,
        `part-video-accordion-${data.section}-${data.part}`
    );
    part.find(`#hidden-part-video-card-title`)
        .attr(`id`, `part-video-card-title-${data.section}-${data.part}`)
        .attr(
            `data-target`,
            `#part-video-collapse-${data.section}-${data.part}`
        );
    part.find(`#hidden-part-video-collapse`)
        .attr(`id`, `part-video-collapse-${data.section}-${data.part}`)
        .attr(
            `data-parent`,
            `#part-video-accordion-${data.section}-${data.part}`
        );
    /**
     * PART VIDEO! BEGIN!
     */
    part.find(`#hidden-part-video-input`).attr(`id`, `part-video-input-${data.section}-${data.part}`)
    .keyup(function (event) {
        var value = event.target.value;
        if (value && value != "") {
            $(`#part-video-label-${data.section}-${data.part}`).html(value);
        } else {
            $(`#part-video-label-${data.section}-${data.part}`).html(`Video Title`);
        }
    });
    part.find(`#hidden-part-video-muted`).attr(`id`, `part-video-muted-${data.section}-${data.part}`);
    part.find(`#hidden-part-video-label`).attr(`id`, `part-video-label-${data.section}-${data.part}`)
    .click(function () {
        $(this).hide();
        $(`#part-video-input-${data.section}-${data.part}`).show();
        $(`#part-video-muted-${data.section}-${data.part}`).show();
    });
    /**
     * PART VIDEO FILE UPLOAD WITH PROGRESS BAR! BEGIN!
     */
    part.find(`#hidden-part-video-upload`).attr(`id`, `part-video-upload-${data.section}-${data.part}`);
    part.find(`#hidden-part-drag-drop-video-wrapper`).attr(`id`, `part-drag-drop-video-wrapper-${data.section}-${data.part}`);
    part.find(`#hidden-part-video-input-file`).attr(`id`, `part-video-input-file-${data.section}-${data.part}`).change(function(){
        video_aws_upload(data);
    });
    part.find(`#hidden-part-video-progress-bar-wrapper`).attr(`id`, `part-video-progress-bar-wrapper-${data.section}-${data.part}`);
    part.find(`#hidden-part-video-progress-bar-percent`).attr(`id`, `part-video-progress-bar-percent-${data.section}-${data.part}`);
    part.find(`#hidden-part-video-progress-bar-`).attr(`id`, `part-video-progress-bar-${data.section}-${data.part}`);
    
    part.find(`#hidden-part-video-exist-buttons-div`).attr(`id`,`part-video-exist-buttons-div-${data.section}-${data.part}`);
    part.find(`#hidden-part-video-view-button`).attr(`id`, `part-video-view-button-${data.section}-${data.part}`);
    part.find(`#hidden-part-video-remove-button`).attr(`id`,`part-video-remove-button-${data.section}-${data.part}`);
    
    part.find(`#hidden-video-url`).attr(`id`, `video-url-${data.section}-${data.part}`);
    part.find(`#hidden-video-filename`).attr(`id`, `video-filename-${data.section}-${data.part}`);
    part.find(`#hidden-video-size`).attr(`id`, `video-size-${data.section}-${data.part}`);
    part.find(`#hidden-video-length`).attr(`id`, `video-length-${data.section}-${data.part}`);
    part.find(`#hidden-video-id`).attr(`id`, `video-id-${data.section}-${data.part}`);
    /**
     * PART VIDEO FILE UPLOAD WITH PROGRESS BAR! END!
     */
    part.find(`#hidden-part-video-form-submit`).attr(`id`, `part-video-form-submit-${data.section}-${data.part}`);
    /**
     * PART VIDEO! END!
     */

    // Article Part
    part.find(`#hidden-part-article-content`).attr(
        `id`,
        `part-article-content-${data.section}-${data.part}`
    );
    part.find(`#hidden-part-article-accordion`).attr(
        `id`,
        `part-article-accordion-${data.section}-${data.part}`
    );
    part.find(`#hidden-part-article-collapse`)
        .attr(`id`, `part-article-collapse-${data.section}-${data.part}`)
        .attr(
            `data-parent`,
            `#part-article-accordion-${data.section}-${data.part}`
        );
    part.find(`#hidden-part-article-form`).attr(
        `id`,
        `part-article-form-${data.section}-${data.part}`
    );
    part.find(`#hidden-part-article-form-alert`).attr(
        `id`,
        `part-article-form-alert-${data.section}-${data.part}`
    );
    part.find(`#hidden-part-article-card-title`)
        .attr(`id`, `part-article-card-title-${data.section}-${data.part}`)
        .attr(
            `data-target`,
            `#part-article-collapse-${data.section}-${data.part}`
        );
    part.find(`#hidden-part-article-input`)
        .attr(`id`, `part-article-input-${data.section}-${data.part}`)
        .keyup(function (event) {
            var value = event.target.value;
            if (value && value != "") {
                $(`#part-article-label-${data.section}-${data.part}`).html(
                    value
                );
            } else {
                $(`#part-article-label-${data.section}-${data.part}`).html(
                    `Article Title`
                );
            }
        });
    part.find(`#hidden-part-article-muted`).attr(
        `id`,
        `part-article-muted-${data.section}-${data.part}`
    );
    part.find(`#hidden-part-article-label`)
        .attr(`id`, `part-article-label-${data.section}-${data.part}`)
        .click(function () {
            $(this).hide();
            $(`#part-article-input-${data.section}-${data.part}`).show();
            $(`#part-article-muted-${data.section}-${data.part}`).show();
        });
    part.find(`#hidden-part-article-id`).attr(
        `id`,
        `part-article-id-${data.section}-${data.part}`
    );
    // Article TextArea
    part.find(`#hidden-part-article-textarea-div`).attr(
        `id`,
        `part-article-textarea-div-${data.section}-${data.part}`
    );
    part.find(`#hidden-part-article-body-textarea`)
        .attr(`id`, `part-article-body-textarea-${data.section}-${data.part}`);

    part.find(`#hidden-part-article-body-muted`).attr(
        `id`,
        `part-article-body-muted-${data.section}-${data.part}`
    );
    part.find(`#hidden-part-article-body-div`)
        .attr(`id`, `part-article-body-div-${data.section}-${data.part}`)
        .click(function () {
            $(this).hide();
            $(`#part-article-textarea-div-${data.section}-${data.part}`).show();
            $(`#part-article-body-muted-${data.section}-${data.part}`).show();
        });
    // Article Part Submit
    part.find(`#hidden-part-article-form-submit`).attr(
        `id`,
        `part-article-form-submit-${data.section}-${data.part}`
    );

    // Quiz Part
    part.find(`#hidden-part-quiz-content`).attr(
        `id`,
        `part-quiz-content-${data.section}-${data.part}`
    );
    part.find(`#hidden-part-quiz-accordion`).attr(
        `id`,
        `part-quiz-accordion-${data.section}-${data.part}`
    );
    part.find(`#hidden-part-quiz-collapse`)
        .attr(`id`, `part-quiz-collapse-${data.section}-${data.part}`)
        .attr(
            `data-parent`,
            `#part-quiz-accordion-${data.section}-${data.part}`
        );
    part.find(`#hidden-part-quiz-form`).attr(
        `id`,
        `part-quiz-form-${data.section}-${data.part}`
    );
    part.find(`#hidden-part-quiz-form-alert`).attr(
        `id`,
        `part-quiz-form-alert-${data.section}-${data.part}`
    );
    part.find(`#hidden-part-quiz-card-title`)
        .attr(`id`, `part-quiz-card-title-${data.section}-${data.part}`)
        .attr(
            `data-target`,
            `#part-quiz-collapse-${data.section}-${data.part}`
        );
    part.find(`#hidden-part-quiz-input`)
        .attr(`id`, `part-quiz-input-${data.section}-${data.part}`)
        .keyup(function (event) {
            var value = event.target.value;
            $(`#part-quiz-label-${data.section}-${data.part}`).html(
                value ? value : "Quiz Title"
            );
        });

    part.find(`#hidden-part-quiz-muted`).attr(
        `id`,
        `part-quiz-muted-${data.section}-${data.part}`
    );
    part.find(`#hidden-part-quiz-label`)
        .attr(`id`, `part-quiz-label-${data.section}-${data.part}`)
        .click(function () {
            $(this).hide();
            $(`#part-quiz-input-${data.section}-${data.part}`).show();
            $(`#part-quiz-muted-${data.section}-${data.part}`).show();
        });
    part.find(`#hidden-part-quiz-id`).attr(
        `id`,
        `part-quiz-id-${data.section}-${data.part}`
    );
    // Quiz Part Submit
    part.find(`#hidden-part-quiz-form-submit`).attr(
        `id`,
        `part-quiz-form-submit-${data.section}-${data.part}`
    );

    // Quiz Item Wrapper
    part.find(`#hidden-part-quiz-item-wrapper`).attr(
        `id`,
        `part-quiz-item-wrapper-${data.section}-${data.part}`
    );
    part.find(`.button-add-quiz-item`).click(function () {
        generate_new_quiz_item(data);
        $(this).hide().next().show();
    });
    part.find(`.button-back-quiz-list`).click(function () {
        show_quiz_list(data);
        $(this).hide().prev().show();
    });
    part.find(`#hidden-part-quiz-item-list`).attr(
        `id`,
        `part-quiz-item-list-${data.section}-${data.part}`
    );
    part.find(`#hidden-part-quiz-item-body-wrapper`).attr(
        `id`,
        `part-quiz-item-body-wrapper-${data.section}-${data.part}`
    );

    // Quiz Item Form
    part.find(`#hidden-part-quiz-item-form`).attr(
        `id`,
        `part-quiz-item-form-${data.section}-${data.part}`
    );
    part.find(`#hidden-part-quiz-item-id`).attr(
        `id`,
        `part-quiz-item-id-${data.section}-${data.part}`
    );

    part.find(`#hidden-part-quiz-textarea-div`).attr(
        `id`,
        `part-quiz-textarea-div-${data.section}-${data.part}`
    );
    part.find(`#hidden-part-quiz-body-textarea`).attr(
        `id`,
        `part-quiz-body-textarea-${data.section}-${data.part}`
    );
    part.find(`#hidden-part-quiz-body-div`)
        .attr(`id`, `part-quiz-body-div-${data.section}-${data.part}`)
        .click(function () {
            $(this).hide().prev().show();
        });
    part.find(`#hidden-part-quiz-body-muted`).attr(
        `id`,
        `part-quiz-body-muted-${data.section}-${data.part}`
    );

    part.find(`input[name="hidden-choices"]`).attr(
        `name`,
        `part-quiz-item-choices-${data.section}-${data.part}`
    );
    part.find(`input[name="hidden-part-quiz-choice-1"]`).attr(
        `name`,
        `part-quiz-choice-1-${data.section}-${data.part}`
    );
    part.find(`input[name="hidden-part-quiz-choice-explain-1"]`).attr(
        `name`,
        `part-quiz-choice-explain-1-${data.section}-${data.part}`
    );
    part.find(`input[name="hidden-part-quiz-choice-2"]`).attr(
        `name`,
        `part-quiz-choice-2-${data.section}-${data.part}`
    );
    part.find(`input[name="hidden-part-quiz-choice-explain-2"]`).attr(
        `name`,
        `part-quiz-choice-explain-2-${data.section}-${data.part}`
    );
    part.find(`input[name="hidden-part-quiz-choice-3"]`).attr(
        `name`,
        `part-quiz-choice-3-${data.section}-${data.part}`
    );
    part.find(`input[name="hidden-part-quiz-choice-explain-3"]`).attr(
        `name`,
        `part-quiz-choice-explain-3-${data.section}-${data.part}`
    );
    part.find(`input[name="hidden-part-quiz-choice-4"]`).attr(
        `name`,
        `part-quiz-choice-4-${data.section}-${data.part}`
    );
    part.find(`input[name="hidden-part-quiz-choice-explain-4"]`).attr(
        `name`,
        `part-quiz-choice-explain-4-${data.section}-${data.part}`
    );

    part.find(`#hidden-part-quiz-item-form-submit`).attr(
        `name`,
        `part-quiz-item-form-submit-${data.section}-${data.part}`
    );

    var section_div = $(`#section-${data.section}`);
    section_div.append(part);
    initiateSummernote(`#part-article-body-textarea-${data.section}-${data.part}`, toolbar_show, 80, 10000, "");
}

function insertParts(data, prev, insert_to) {
    var section_div = $(`#section-${data.section}`);
    var part = $(`#hidden-part-clone`).clone();
    part.find(`.selection`).addClass(`selection-bottom-margin`);

    part.attr(`id`, `part-${data.section}-${data.part}`)
        .show()
        .hover(
            function () {
                part.find(`.selection`).slideDown(100);

                var last_part = section_div.find(`.part`).last();
                if (
                    last_part.attr(`id`) ==
                    `part-${data.section}-${data.part}` &&
                    part.find(".nav-link").hasClass("active")
                ) {
                    part.find(`.selection-after`).slideDown(100);
                }
            },
            function () {
                // hover out
                if (part.find(".nav-link").hasClass("active")) {
                    part.find(`.selection`).slideUp(100);
                    part.find(`.selection-after`).slideUp(100);
                }
            }
        );
    part.find(`.button-remove-part`).click(function () {
        removeSectionPart(data);
    });
    part.find(`.button-add-part`).click(function () {
        if (limitAdditionalPart(data)) {
            generateNewPartSelection(data, "before");
        } else {
            toastr.warning(
                `Reminder!`,
                `Please complete a part before adding another one!`
            );
        }
    });
    part.find(`.button-add-part-after`).click(function () {
        if (limitAdditionalPart(data)) {
            generateNewPartSelection(data, "after");
        } else {
            toastr.warning(
                `Reminder!`,
                `Please complete a part before adding another one!`
            );
        }
    });
    part.find(`.button-add-video`)
        .attr(`href`, `#part-video-content-${data.section}-${data.part}`)
        .click(function () {
            part.find(`.selection`).removeClass(`selection-bottom-margin`);
            part.find(`.nav-link.section_links`).hide();
        });
    part.find(`.button-add-article`)
        .attr(`href`, `#part-article-content-${data.section}-${data.part}`)
        .click(function () {
            part.find(`.selection`).removeClass(`selection-bottom-margin`);
            part.find(`.nav-link.section_links`).hide();
        });
    part.find(`.button-add-quiz`)
        .attr(`href`, `#part-quiz-content-${data.section}-${data.part}`)
        .click(function () {
            part.find(`.selection`).removeClass(`selection-bottom-margin`);
            part.find(`.nav-link.section_links`).hide();
        });

    part.find(`#hidden-part-content`).attr(
        `id`,
        `part-content-${data.section}-${data.part}`
    );
    // Video Part
    part.find(`#hidden-part-video-content`).attr(
        `id`,
        `part-video-content-${data.section}-${data.part}`
    );
    part.find(`#hidden-part-video-form`).attr(
        `id`,
        `part-video-form-${data.section}-${data.part}`
    );
    part.find(`#hidden-part-video-form-alert`).attr(
        `id`,
        `part-video-form-alert-${data.section}-${data.part}`
    );
    part.find(`#hidden-part-video-accordion`).attr(
        `id`,
        `part-video-accordion-${data.section}-${data.part}`
    );
    part.find(`#hidden-part-video-card-title`)
        .attr(`id`, `part-video-card-title-${data.section}-${data.part}`)
        .attr(
            `data-target`,
            `#part-video-collapse-${data.section}-${data.part}`
        );
    part.find(`#hidden-part-video-collapse`)
        .attr(`id`, `part-video-collapse-${data.section}-${data.part}`)
        .attr(
            `data-parent`,
            `#part-video-accordion-${data.section}-${data.part}`
        );
    /**
     * PART VIDEO! BEGIN!
     */
    part.find(`#hidden-part-video-input`).attr(`id`, `part-video-input-${data.section}-${data.part}`)
    .keyup(function (event) {
        var value = event.target.value;
        if (value && value != "") {
            $(`#part-video-label-${data.section}-${data.part}`).html(value);
        } else {
            $(`#part-video-label-${data.section}-${data.part}`).html(`Video Title`);
        }
    });
    part.find(`#hidden-part-video-muted`).attr(`id`, `part-video-muted-${data.section}-${data.part}`);
    part.find(`#hidden-part-video-label`).attr(`id`, `part-video-label-${data.section}-${data.part}`)
    .click(function () {
        $(this).hide();
        $(`#part-video-input-${data.section}-${data.part}`).show();
        $(`#part-video-muted-${data.section}-${data.part}`).show();
    });
    /**
     * PART VIDEO FILE UPLOAD WITH PROGRESS BAR! BEGIN!
     */
    part.find(`#hidden-part-video-upload`).attr(`id`, `part-video-upload-${data.section}-${data.part}`);
    part.find(`#hidden-part-drag-drop-video-wrapper`).attr(`id`, `part-drag-drop-video-wrapper-${data.section}-${data.part}`);
    part.find(`#hidden-part-video-input-file`).attr(`id`, `part-video-input-file-${data.section}-${data.part}`).change(function(){
        video_aws_upload(data);
    });
    part.find(`#hidden-part-video-progress-bar-wrapper`).attr(`id`, `part-video-progress-bar-wrapper-${data.section}-${data.part}`);
    part.find(`#hidden-part-video-progress-bar-percent`).attr(`id`, `part-video-progress-bar-percent-${data.section}-${data.part}`);
    part.find(`#hidden-part-video-progress-bar-`).attr(`id`, `part-video-progress-bar-${data.section}-${data.part}`);
    
    part.find(`#hidden-part-video-exist-buttons-div`).attr(`id`,`part-video-exist-buttons-div-${data.section}-${data.part}`);
    part.find(`#hidden-part-video-view-button`).attr(`id`, `part-video-view-button-${data.section}-${data.part}`);
    part.find(`#hidden-part-video-remove-button`).attr(`id`,`part-video-remove-button-${data.section}-${data.part}`);
    
    part.find(`#hidden-video-url`).attr(`id`, `video-url-${data.section}-${data.part}`);
    part.find(`#hidden-video-filename`).attr(`id`, `video-filename-${data.section}-${data.part}`);
    part.find(`#hidden-video-size`).attr(`id`, `video-size-${data.section}-${data.part}`);
    part.find(`#hidden-video-length`).attr(`id`, `video-length-${data.section}-${data.part}`);
    part.find(`#hidden-video-id`).attr(`id`, `video-id-${data.section}-${data.part}`);
    /**
     * PART VIDEO FILE UPLOAD WITH PROGRESS BAR! END!
     */
    part.find(`#hidden-part-video-form-submit`).attr(`id`, `part-video-form-submit-${data.section}-${data.part}`);
    /**
     * PART VIDEO! END!
     */

    // Article Part
    part.find(`#hidden-part-article-content`).attr(
        `id`,
        `part-article-content-${data.section}-${data.part}`
    );
    part.find(`#hidden-part-article-accordion`).attr(
        `id`,
        `part-article-accordion-${data.section}-${data.part}`
    );
    part.find(`#hidden-part-article-collapse`)
        .attr(`id`, `part-article-collapse-${data.section}-${data.part}`)
        .attr(
            `data-parent`,
            `#part-article-accordion-${data.section}-${data.part}`
        );
    part.find(`#hidden-part-article-form`).attr(
        `id`,
        `part-article-form-${data.section}-${data.part}`
    );
    part.find(`#hidden-part-article-form-alert`).attr(
        `id`,
        `part-article-form-alert-${data.section}-${data.part}`
    );
    part.find(`#hidden-part-article-card-title`)
        .attr(`id`, `part-article-card-title-${data.section}-${data.part}`)
        .attr(
            `data-target`,
            `#part-article-collapse-${data.section}-${data.part}`
        );
    part.find(`#hidden-part-article-input`)
        .attr(`id`, `part-article-input-${data.section}-${data.part}`)
        .keyup(function (event) {
            var value = event.target.value;
            if (value && value != "") {
                $(`#part-article-label-${data.section}-${data.part}`).html(
                    value
                );
            } else {
                $(`#part-article-label-${data.section}-${data.part}`).html(
                    `Article Title`
                );
            }
        });
    part.find(`#hidden-part-article-muted`).attr(
        `id`,
        `part-article-muted-${data.section}-${data.part}`
    );
    part.find(`#hidden-part-article-label`)
        .attr(`id`, `part-article-label-${data.section}-${data.part}`)
        .click(function () {
            $(this).hide();
            $(`#part-article-input-${data.section}-${data.part}`).show();
            $(`#part-article-muted-${data.section}-${data.part}`).show();
        });
    part.find(`#hidden-part-article-id`).attr(
        `id`,
        `part-article-id-${data.section}-${data.part}`
    );
    // Article TextArea
    part.find(`#hidden-part-article-textarea-div`).attr(
        `id`,
        `part-article-textarea-div-${data.section}-${data.part}`
    );
    part.find(`#hidden-part-article-body-textarea`)
        .attr(`id`, `part-article-body-textarea-${data.section}-${data.part}`);

    part.find(`#hidden-part-article-body-muted`).attr(
        `id`,
        `part-article-body-muted-${data.section}-${data.part}`
    );
    part.find(`#hidden-part-article-body-div`)
        .attr(`id`, `part-article-body-div-${data.section}-${data.part}`)
        .click(function () {
            $(this).hide();
            $(`#part-article-textarea-div-${data.section}-${data.part}`).show();
            $(`#part-article-body-muted-${data.section}-${data.part}`).show();
        });
    // Article Part Submit
    part.find(`#hidden-part-article-form-submit`).attr(
        `id`,
        `part-article-form-submit-${data.section}-${data.part}`
    );

    // Quiz Part
    part.find(`#hidden-part-quiz-content`).attr(
        `id`,
        `part-quiz-content-${data.section}-${data.part}`
    );
    part.find(`#hidden-part-quiz-accordion`).attr(
        `id`,
        `part-quiz-accordion-${data.section}-${data.part}`
    );
    part.find(`#hidden-part-quiz-collapse`)
        .attr(`id`, `part-quiz-collapse-${data.section}-${data.part}`)
        .attr(
            `data-parent`,
            `#part-quiz-accordion-${data.section}-${data.part}`
        );
    part.find(`#hidden-part-quiz-form`).attr(
        `id`,
        `part-quiz-form-${data.section}-${data.part}`
    );
    part.find(`#hidden-part-quiz-form-alert`).attr(
        `id`,
        `part-quiz-form-alert-${data.section}-${data.part}`
    );
    part.find(`#hidden-part-quiz-card-title`)
        .attr(`id`, `part-quiz-card-title-${data.section}-${data.part}`)
        .attr(
            `data-target`,
            `#part-quiz-collapse-${data.section}-${data.part}`
        );
    part.find(`#hidden-part-quiz-input`)
        .attr(`id`, `part-quiz-input-${data.section}-${data.part}`)
        .keyup(function (event) {
            var value = event.target.value;
            $(`#part-quiz-label-${data.section}-${data.part}`).html(
                value ? value : "Quiz Title"
            );
        });

    part.find(`#hidden-part-quiz-muted`).attr(
        `id`,
        `part-quiz-muted-${data.section}-${data.part}`
    );
    part.find(`#hidden-part-quiz-label`)
        .attr(`id`, `part-quiz-label-${data.section}-${data.part}`)
        .click(function () {
            $(this).hide();
            $(`#part-quiz-input-${data.section}-${data.part}`).show();
            $(`#part-quiz-muted-${data.section}-${data.part}`).show();
        });
    part.find(`#hidden-part-quiz-id`).attr(
        `id`,
        `part-quiz-id-${data.section}-${data.part}`
    );
    // Quiz Part Submit
    part.find(`#hidden-part-quiz-form-submit`).attr(
        `id`,
        `part-quiz-form-submit-${data.section}-${data.part}`
    );

    // Quiz Item Wrapper
    part.find(`#hidden-part-quiz-item-wrapper`).attr(
        `id`,
        `part-quiz-item-wrapper-${data.section}-${data.part}`
    );
    part.find(`.button-add-quiz-item`).click(function () {
        generate_new_quiz_item(data);
        $(this).hide().next().show();
    });
    part.find(`.button-back-quiz-list`).click(function () {
        show_quiz_list(data);
        $(this).hide().prev().show();
    });
    part.find(`#hidden-part-quiz-item-list`).attr(
        `id`,
        `part-quiz-item-list-${data.section}-${data.part}`
    );
    part.find(`#hidden-part-quiz-item-body-wrapper`).attr(
        `id`,
        `part-quiz-item-body-wrapper-${data.section}-${data.part}`
    );

    // Quiz Item Form
    part.find(`#hidden-part-quiz-item-form`).attr(
        `id`,
        `part-quiz-item-form-${data.section}-${data.part}`
    );
    part.find(`#hidden-part-quiz-item-id`).attr(
        `id`,
        `part-quiz-item-id-${data.section}-${data.part}`
    );

    part.find(`#hidden-part-quiz-textarea-div`).attr(
        `id`,
        `part-quiz-textarea-div-${data.section}-${data.part}`
    );
    part.find(`#hidden-part-quiz-body-textarea`).attr(
        `id`,
        `part-quiz-body-textarea-${data.section}-${data.part}`
    );
    part.find(`#hidden-part-quiz-body-div`)
        .attr(`id`, `part-quiz-body-div-${data.section}-${data.part}`)
        .click(function () {
            $(this).hide().prev().show();
        });
    part.find(`#hidden-part-quiz-body-muted`).attr(
        `id`,
        `part-quiz-body-muted-${data.section}-${data.part}`
    );

    part.find(`input[name="hidden-choices"]`).attr(
        `name`,
        `part-quiz-item-choices-${data.section}-${data.part}`
    );
    part.find(`input[name="hidden-part-quiz-choice-1"]`).attr(
        `name`,
        `part-quiz-choice-1-${data.section}-${data.part}`
    );
    part.find(`input[name="hidden-part-quiz-choice-explain-1"]`).attr(
        `name`,
        `part-quiz-choice-explain-1-${data.section}-${data.part}`
    );
    part.find(`input[name="hidden-part-quiz-choice-2"]`).attr(
        `name`,
        `part-quiz-choice-2-${data.section}-${data.part}`
    );
    part.find(`input[name="hidden-part-quiz-choice-explain-2"]`).attr(
        `name`,
        `part-quiz-choice-explain-2-${data.section}-${data.part}`
    );
    part.find(`input[name="hidden-part-quiz-choice-3"]`).attr(
        `name`,
        `part-quiz-choice-3-${data.section}-${data.part}`
    );
    part.find(`input[name="hidden-part-quiz-choice-explain-3"]`).attr(
        `name`,
        `part-quiz-choice-explain-3-${data.section}-${data.part}`
    );
    part.find(`input[name="hidden-part-quiz-choice-4"]`).attr(
        `name`,
        `part-quiz-choice-4-${data.section}-${data.part}`
    );
    part.find(`input[name="hidden-part-quiz-choice-explain-4"]`).attr(
        `name`,
        `part-quiz-choice-explain-4-${data.section}-${data.part}`
    );

    part.find(`#hidden-part-quiz-item-form-submit`).attr(
        `name`,
        `part-quiz-item-form-submit-${data.section}-${data.part}`
    );

    var section_div = $(`#section-${data.section}`);

    if (insert_to == "before") {
        section_div.find(`#part-${prev.section}-${prev.part}`).before(part);
    } else {
        section_div.find(`#part-${prev.section}-${prev.part}`).after(part);
    }

    initiateSummernote(`#part-article-body-textarea-${data.section}-${data.part}`, toolbar_show, 80, 10000, "");
}

function limitAdditionalPart(data) {
    var parts = $(`#section-${data.section}`).find(".part");

    var should_add = 0;
    parts.each(function (index, e) {
        if (index == 0) {
            return;
        }

        var video = $(this).find(`input[name="video_id"]`);
        var article = $(this).find(`input[name="article_id"]`);
        var quiz = $(this).find(`input[name="quiz_id"]`);
        if (
            !video.val() == true &&
            !article.val() == true &&
            !quiz.val() == true
        ) {
            should_add++;
            return true;
        }
    });

    return should_add > 0 ? false : true;
}

function generateNewPartSelection(data, insert_to) {
    var parts = $(`#section-${data.section}`).find(".part");
    var created_part = parseInt(data.part) + 1;
    if (parts.length <= created_part) {
        insertParts(
            { part: created_part, section: data.section },
            data,
            insert_to
        );
        /**
         * For dynamic data initialization
         *
         */
        section_part_data_initialization.part = created_part;
        section_part_data_initialization.section = data.section;
        FormDesignVideo.init();
        FormDesignArticle.init();
        FormDesignQuiz.init();
        FormDesignQuizItem.init();
    } else if (parts.length > created_part) {
        var newID = 0;
        parts.each(function (index, e) {
            if (index == 0) {
                return;
            }

            var currentId = $(this).attr(`id`);
            var extractedID = parseInt(currentId.split("-")[2]) + 1;
            if ($(`#part-${data.section}-${extractedID}`).length == 0) {
                newID = extractedID;
                return true;
            }
        });

        insertParts({ part: newID, section: data.section }, data, insert_to);
        /**
         * For dynamic data initialization
         *
         */
        section_part_data_initialization.part = newID;
        section_part_data_initialization.section = data.section;
        FormDesignVideo.init();
        FormDesignArticle.init();
        FormDesignQuiz.init();
        FormDesignQuizItem.init();
    } else if (parts.length == 1) {
        // Part Selection & Content
        generateParts({ part: 1, section: data.section });

        /**
         * For dynamic data initialization
         *
         */
        section_part_data_initialization.part = 1;
        section_part_data_initialization.section = data.section;
        FormDesignVideo.init();
        FormDesignArticle.init();
        FormDesignQuiz.init();
        FormDesignQuizItem.init();
    } else {
        console.log(`UNABLE TO PRODUCE NEW ELEMENT`);
    }
}

function removeSection(element) {
    var current = $(element);
    var section_number = current.data("id");
    var remove_modal = $(`#section-removal-modal`);
    remove_modal
        .find(`#section-removal-modal-submit`)
        .attr(`data-id`, section_number);
    remove_modal
        .find(`.modal-body`)
        .html(
            `You're removing a <b>Section</b>.<br><br>Are you sure you want to remove it?`
        );
    remove_modal.modal(`show`);
}

/**
 *
 *
 * was removed inside the removeSection since the click function
 * has been duplicating too!
 */
$(`#section-removal-modal-submit`).click(function () {
    var current = $(this);
    var section_number = current.data("id");

    // counting part: validating if part has exisiting data
    var section = $(`#section-${section_number}`);
    var parts = section.find(`[id*="part-${section_number}"]`);
    var _should_remove = true;

    parts.each(function () {
        var current_part = $(this);
        var video_value = current_part.find(`input[name="video_id"]`);
        var article_value = current_part.find(`input[name="article_id"]`);
        var quiz_value = current_part.find(`input[name="quiz_id"]`);

        if (video_value.val() || quiz_value.val() || article_value.val()) {
            _should_remove = false;
        }
    });

    if (_should_remove) {
        $.ajax({
            url: "/course/management/content/remove/section",
            data: {
                section: section_number,
            },
            success: function (response) {
                toastr.success("Reminder!", "Section has been removed!");
                if (response._arranged_sections) {
                    rearrangement_sections();
                }
            },
            error: function () {
                toastr.error(
                    "Error!",
                    "Unable to remove section! Please refresh your browser"
                );
            },
        });
    } else {
        toastr.error(
            "Error!",
            "Sorry! You're unable to delete this section since you have existing parts"
        );
    }
});

function rearrangement_sections() {
    section_tabitem_count = 0;
    current_section = 1;
    section_part_data_initialization = {
        part: 1,
        section: 1,
    };

    var tab = $(`#tab_content`);
    tab.find(`[id*="section-tabpanel-"]`).each(function () {
        $(this).remove();
    });

    get_sections();
}

function removeSectionPart(data) {
    // part & section
    var section = $(`#section-${data.section}`);
    var parts = section.find(`.part`);

    var remove_modal = $(`#part-removal-modal`);
    remove_modal
        .find(`#part-removal-modal-submit`)
        .attr("data-section", data.section)
        .attr("data-part", data.part)
        .attr("data-exist", false)
        .attr("data-generate", parts.length == 2 ? true : false);
    remove_modal
        .find(`.modal-body`)
        .html(
            `You're removing the <b>Part #${data.part}</b> from your current <b>Section</b>.<br><br>Are you sure you want to remove it?`
        );
    remove_modal.modal(`show`);
}

function removeSectionExistingPart(data, sequence) {
    // part & section
    var section = $(`#section-${data.section}`);
    var parts = section.find(`.part`);

    var remove_modal = $(`#part-removal-modal`);
    remove_modal
        .find(`#part-removal-modal-submit`)
        .attr("data-section", data.section)
        .attr("data-part", data.part)
        .attr("data-exist", true)
        .attr("data-id", sequence.id)
        .attr("data-type", sequence.type)
        .attr("data-generate", parts.length == 2 ? true : false);
    remove_modal
        .find(`.modal-body`)
        .html(
            `You're removing the <b>Part #${data.part}</b> from your current <b>Section</b>.<br><br>Are you sure you want to remove it?`
        );
    remove_modal.modal(`show`);
}

$(`#part-removal-modal-submit`).click(function (element) {
    var current = element.target.dataset;
    var section = $(`#section-${current.section}`);
    if (current.exist == "true") {
        if (section.find(`#part-${current.section}-${current.part}`).remove()) {
            $.ajax({
                url: "/course/management/content/remove/part",
                data: {
                    type: current.type,
                    section: current.section,
                    part: current.part,
                    id: current.id,
                },
                success: function (response) {
                    if (response.status == 200) {
                        if (current.generate == "true") {
                            generateNewPartSelection(
                                { section: current.section },
                                "after"
                            );
                        }
                        toastr.success("Reminder!", response.message);
                    } else {
                        toastr.error("Reminder!", response.message);
                    }
                },
                error: function () {
                    toastr.error(
                        "Error!",
                        `Unable to remove ${current.type}! Please refresh your browser`
                    );
                },
            });
        }
    } else {
        if (section.find(`#part-${current.section}-${current.part}`).remove()) {
            if (current.generate == "true") {
                generateNewPartSelection({ section: current.section }, "after");
            }
            toastr.success("Reminder!", `Part has been removed!`);
        }
    }
});

function generate_quiz_item(quiz, data) {
    // part-quiz-item-list
    if (quiz.items.length > 0) {
        var list = $(`#part-quiz-item-list-${data.section}-${data.part}`);
        quiz.items.forEach((item, index) => {
            var textarea = jQuery(`<div>${item.question}</div>`).text();
            var textarea_limit =
                textarea.length > 100
                    ? textarea.substring(0, 100) + "..."
                    : textarea;
            var itemtab = $(
                `<div class="row question-items"><b>${
                index + 1
                }. &nbsp; </b>${textarea_limit}</div>`
            );
            itemtab.on("click", function () {
                generate_quiz_item_content(item, data);
            });

            list.append(itemtab);
        });
    } else {
        var quiz_wrapper = $(
            `#part-quiz-item-wrapper-${data.section}-${data.part}`
        );
        quiz_wrapper.find(`.button-back-quiz-list`).show().prev().hide();
        generate_new_quiz_item(data);
    }
}

function generate_new_quiz_item(data) {
    var list = $(`#part-quiz-item-list-${data.section}-${data.part}`);
    var wrapper = $(
        `#part-quiz-item-body-wrapper-${data.section}-${data.part}`
    );
    list.hide();
    wrapper.show();

    // remove values from Quiz Item's Form
    $(`input[name="part-quiz-choice-1-${data.section}-${data.part}"]`).val(
        null
    );
    $(
        `input[name="part-quiz-choice-explain-1-${data.section}-${data.part}"]`
    ).val(null);
    $(`input[name="part-quiz-choice-2-${data.section}-${data.part}"]`).val(
        null
    );
    $(
        `input[name="part-quiz-choice-explain-2-${data.section}-${data.part}"]`
    ).val(null);
    $(`input[name="part-quiz-choice-3-${data.section}-${data.part}"]`).val(
        null
    );
    $(
        `input[name="part-quiz-choice-explain-3-${data.section}-${data.part}"]`
    ).val(null);
    $(`input[name="part-quiz-choice-4-${data.section}-${data.part}"]`).val(
        null
    );
    $(
        `input[name="part-quiz-choice-explain-4-${data.section}-${data.part}"]`
    ).val(null);
    $(`#part-quiz-item-id-${data.section}-${data.part}`).val(null);
    $(`#part-quiz-body-textarea-${data.section}-${data.part}`).val(null);
    initiateSummernote(`#part-quiz-body-textarea-${data.section}-${data.part}`, toolbar_show, 80, 1000, "");
    $(`#part-quiz-body-div-${data.section}-${data.part}`).html("Question");
    $(`input[name="part-quiz-item-choices-${data.section}-${data.part}"]`).each(
        function () {
            var current_radio = $(this);
            if (current_radio.val() == "one") {
                current_radio.prop(`checked`, true);
            } else {
                current_radio.prop(`checked`, false);
            }
        }
    );
}

function generate_quiz_item_content(item, data) {
    var quiz_wrapper = $(
        `#part-quiz-item-wrapper-${data.section}-${data.part}`
    );
    var list = $(`#part-quiz-item-list-${data.section}-${data.part}`);
    var wrapper = $(
        `#part-quiz-item-body-wrapper-${data.section}-${data.part}`
    );
    quiz_wrapper.find(`.button-back-quiz-list`).show().prev().hide();
    list.hide();
    wrapper.show();

    // add values from Quiz Item's Form
    var choices = JSON.parse(item.choices);
    choices.forEach((choice, index) => {
        var counting_control = index + 1;
        $(`input[name="part-quiz-choice-${counting_control}-${data.section}-${data.part}"]` ).val(choice.choice);
        $(`input[name="part-quiz-choice-explain-${counting_control}-${data.section}-${data.part}"]`).val(choice.explain);
    });

    $(`#part-quiz-item-id-${data.section}-${data.part}`).val(item.id);
    $(`#part-quiz-body-textarea-${data.section}-${data.part}`).val(item.question);
    initiateSummernote(`#part-quiz-body-textarea-${data.section}-${data.part}`, toolbar_show, 80, 1000, item.question);
    $(`#part-quiz-body-div-${data.section}-${data.part}`).html(item.question);

    $(`input[name="part-quiz-item-choices-${data.section}-${data.part}"]`).each(
        function () {
            var current_radio = $(this);
            if (current_radio.val() == item.answer) {
                current_radio.prop(`checked`, true);
            } else {
                current_radio.prop(`checked`, false);
            }
        }
    );
}

function show_quiz_list(data) {
    var list = $(`#part-quiz-item-list-${data.section}-${data.part}`);
    var wrapper = $(
        `#part-quiz-item-body-wrapper-${data.section}-${data.part}`
    );
    list.show();
    wrapper.hide();

    // remove values from Quiz Item's Form
    $(`input[name="part-quiz-choice-1-${data.section}-${data.part}"]`).val(
        null
    );
    $(
        `input[name="part-quiz-choice-explain-1-${data.section}-${data.part}"]`
    ).val(null);
    $(`input[name="part-quiz-choice-2-${data.section}-${data.part}"]`).val(
        null
    );
    $(
        `input[name="part-quiz-choice-explain-2-${data.section}-${data.part}"]`
    ).val(null);
    $(`input[name="part-quiz-choice-3-${data.section}-${data.part}"]`).val(
        null
    );
    $(
        `input[name="part-quiz-choice-explain-3-${data.section}-${data.part}"]`
    ).val(null);
    $(`input[name="part-quiz-choice-4-${data.section}-${data.part}"]`).val(
        null
    );
    $(
        `input[name="part-quiz-choice-explain-4-${data.section}-${data.part}"]`
    ).val(null);
    $(`#part-quiz-item-id-${data.section}-${data.part}`).val(null);
    $(`#part-quiz-body-textarea-${data.section}-${data.part}`).val(null);
    $(`#part-quiz-body-div-${data.section}-${data.part}`).html("Question");
    $(`input[name="part-quiz-item-choices-${data.section}-${data.part}"]`).each(
        function () {
            var current_radio = $(this);
            if (current_radio.val() == "one") {
                current_radio.prop(`checked`, true);
            } else {
                current_radio.prop(`checked`, false);
            }
        }
    );
}

/**
 * Document's on functions
 *
 */
$(document).on("click", function (e) {
    hidden_inputs(e);
});

$(`#part-removal-video-modal-submit`).click(function (e) {
    var data = e.target.dataset;
    var section = $(`#section-${data.section}`);
    var part = section.find(`#part-${data.section}-${data.part}`);
    var video_id = $(`#video-id-${data.section}-${data.part}`);

    var remove_button = $(`#part-video-remove-button-${data.section}-${data.part}`);
    remove_button.addClass("kt-spinner kt-spinner--right kt-spinner--md kt-spinner--dark disabled").html("Removing...").prop("disabled", true);

    $.ajax({
        url: "/course/management/content/remove/video",
        data: {
            section: data.section,
            part: data.part,
            video_id: video_id.val(),
        },
        success: function (response) {
            $(`#part-video-upload-${data.section}-${data.part}`).show().find(`.kt-uppy__drag`).show();
            $(`#part-video-exist-buttons-div-${data.section}-${data.part}`).hide();
            $(`#part-video-uploading-buttons-div-${data.section}-${data.part}`).hide();
        
            $(`#video-url-${data.section}-${data.part}`).val(null);
            $(`#video-filename-${data.section}-${data.part}`).val(null);
            $(`#video-size-${data.section}-${data.part}`).val(null);

            if(response.hasOwnProperty("title")){
                part.find(`#part-video-title`).html(response.title);
            }else{
                part.find(`#part-video-title`).html(`Video`);
            }

            toastr.success("Video has been removed successfully!");
            remove_button.removeClass("kt-spinner kt-spinner--right kt-spinner--md kt-spinner--dark disabled").html("Yes, I'm sure.").prop("disabled", false);
        },
        error: function () {
            toastr.error(
                "Error!",
                `Unable to remove video! Please refresh your browser`
            );
            remove_button.removeClass("kt-spinner kt-spinner--right kt-spinner--md kt-spinner--dark disabled").html("Yes, I'm sure.").prop("disabled", false);
        },
    });
});

function hidden_inputs(event) {
    if ($(event.target).closest(".section-name-div").length === 0) {
        var current_input = $(`#section-name-input-${current_section}`);
        if (current_input.val() && current_input.val() != "") {
            $(`#section-name-input-${current_section}`).hide();
            $(`#section-name-muted-${current_section}`).hide();
            $(`#section-name-label-${current_section}`).show();
        } else {
            $(`#section-name-input-${current_section}`).show();
            $(`#section-name-muted-${current_section}`).show();
            $(`#section-name-label-${current_section}`).hide();
        }
    }

    if ($(event.target).closest(".section-objective-div").length === 0) {
        var current_input = $(`#section-objective-input-${current_section}`);
        if (current_input.val() && current_input.val() != "") {
            $(`#section-objective-input-${current_section}`).hide();
            $(`#section-objective-muted-${current_section}`).hide();
            $(`#section-objective-label-${current_section}`).show();
        } else {
            $(`#section-objective-input-${current_section}`).show();
            $(`#section-objective-muted-${current_section}`).show();
            $(`#section-objective-label-${current_section}`).hide();
        }
    }

    if ($(event.target).closest(".part-video-div").length === 0) {
        $(`[id^=part-video-input-${current_section}]`).each(function () {
            var current = $(this);
            if (current.val() && current.val() != "") {
                current.hide();
                current.next(`span`).show();
                current.parent().next(`span`).hide();
            } else {
                current.show();
                current.next(`span`).hide();
                current.parent().next(`span`).show();
            }
        });
    }

    if ($(event.target).closest(".part-article-div").length === 0) {
        $(`[id^=part-article-input-${current_section}]`).each(function () {
            var current = $(this);
            if (current.val() && current.val() != "") {
                current.hide();
                current.next(`span`).show();
                current.parent().next(`span`).hide();
            } else {
                current.show();
                current.next(`span`).hide();
                current.parent().next(`span`).show();
            }
        });
    }

    if ($(event.target).closest(".part-article-body-wrapper").length === 0) {
        $(`[id^=part-article-textarea-div-${current_section}]`).each(
            function () {
                var textarea = $(this).find(`textarea`);
                var textarea_value = $(textarea[0]).val();
                if (textarea_value == "") {
                    $(this).next(`div`).html("Article Body");
                } else {
                    $(this).next(`div`).html(textarea_value);
                }
            }
        );

        $(`[id^=part-article-textarea-div-${current_section}]`).hide();
        $(`[id^=part-article-body-muted-${current_section}]`).hide();
        $(`[id^=part-article-body-div-${current_section}]`).show();
    }

    if ($(event.target).closest(".part-quiz-div").length === 0) {
        $(`[id^=part-quiz-input-${current_section}]`).each(function () {
            var current = $(this);
            if (current.val() && current.val() != "") {
                current.hide();
                current.next(`span`).show();
                current.parent().next(`span`).hide();
            } else {
                current.show();
                current.next(`span`).hide();
                current.parent().next(`span`).show();
            }
        });
    }

    if ($(event.target).closest(".part-quiz-body-wrapper").length === 0) {
        $(`[id^=part-quiz-textarea-div-${current_section}]`).each(function () {
            var textarea = $(this).find(`textarea`);
            var textarea_value = $(textarea[0]).val();
            if (textarea_value == "") {
                $(this).next(`div`).html("Quiz Body");
            } else {
                $(this).next(`div`).html(textarea_value);
            }
        });

        $(`[id^=part-quiz-textarea-div-${current_section}]`).hide();
        $(`[id^=part-quiz-body-muted-${current_section}]`).hide();
        $(`[id^=part-quiz-body-div-${current_section}]`).show();
    }
}

function uploadSummernoteImage(file, summernote_element) {
    var data = new FormData();
    data.append("image", file);
    $.ajax({
        url: "/course/management/content/textarea/upload",
        method: "POST",
        data: data,
        cache: false,
        contentType: false,
        processData: false,
        success: function (response) {
            $(summernote_element).summernote("insertImage", response.image_url);
        },
        error: function () {
            toastr.error(
                "Error!",
                "Something went wrong! Please refresh you browser"
            );
        },
    });
}

function initiateSummernote(element, toolbar, height, maximum, code){
    $(element).summernote({
        code: code,
        height: height,
        toolbar: toolbar,
        callbacks: {
            onImageUpload: function (files, editor, welEditable) {
                uploadSummernoteImage(files[0], this);
            },
            onKeydown: function (e) { 
                var t = e.currentTarget.innerText; 
                if (t.trim().length >= maximum) {
                    //delete keys, arrow keys, copy, cut, select all
                    if (e.keyCode != 8 && !(e.keyCode >=37 && e.keyCode <=40) && e.keyCode != 46 && !(e.keyCode == 88 && e.ctrlKey) && !(e.keyCode == 67 && e.ctrlKey) && !(e.keyCode == 65 && e.ctrlKey))
                    e.preventDefault(); 
                } 
            },
            onKeyup: function (e) {
                var t = e.currentTarget.innerText;
                $('#maxContentPost').text(maximum - t.trim().length);
            },
            onPaste: function (e) {
                var t = e.currentTarget.innerText;
                var bufferText = ((e.originalEvent || e).clipboardData || window.clipboardData).getData('Text');
                e.preventDefault();
                var maxPaste = bufferText.length;
                if(t.length + bufferText.length > maximum){
                    maxPaste = maximum - t.length;
                }
                if(maxPaste > 0){
                    document.execCommand('insertText', false, bufferText.substring(0, maxPaste));
                }
                $('#maxContentPost').text(maximum - t.length);
            }
        },
    });
}

function partVideoRefresh(data, $video){
    var refresh_button = $(`#part-video-refresh-button-${data.section}-${data.part}`);
    refresh_button.addClass("kt-spinner kt-spinner--right kt-spinner--md kt-spinner--light disabled").html("Refreshing...").prop("disabled", true);

    $.ajax({
        url: "/course/management/content/refresh/video",
        data: {
            section: data.section,
            part: data.part,
            video_id: $video.id,
        },
        success: function (response) {
            $(`#part-video-upload-${data.section}-${data.part}`).show().find(`.kt-uppy__drag`).hide();
            $(`#part-video-exist-buttons-div-${data.section}-${data.part}`).show();
            $(`#part-video-uploading-buttons-div-${data.section}-${data.part}`).hide();

            var part = $(`#part-content-${data.section}-${data.part}`);
            part.find(`#part-video-title`).html(`Video — ${response.title} <i class="fa fa-check-circle kt-font-success" style="font-size:1rem;"></i>`);
            part.find(`.video-length`).html(`Length: ${response.duration} mins`);
            $(`#part-video-view-button-${data.section}-${data.part}`).click(function () {
                window.open(`/course/management/content/preview/${$video.id}`);
            });
            refresh_button.removeClass("kt-spinner kt-spinner--right kt-spinner--md kt-spinner--light disabled").html("Refresh").prop("disabled", false);
        },
        error: function () {

            refresh_button.removeClass("kt-spinner kt-spinner--right kt-spinner--md kt-spinner--light disabled").html("Refresh").prop("disabled", false);
        },
    });
}

function partVideoCancelUpload(data, $video){
    var cancel_modal = $(`#part-cancel-video-modal`);
    cancel_modal
        .find(`#part-cancel-video-modal-submit`)
        .attr("data-section", data.section)
        .attr("data-part", data.part)
        .attr("data-id", $video.id);
    cancel_modal.find(`.modal-body`).html(`<p style="text-align:center;">You're trying to cancel the upload for<br/><b>Video — ${$video.title}</b>.<br>Are you sure you want to cancel it?</p>`);
    cancel_modal.modal(`show`);
}

$(`#part-cancel-video-modal-submit`).click(function (e) {
    var data = e.target.dataset;
    var $video_id = data.id;

    var cancel_button = $(`#part-video-cancel-button-${data.section}-${data.part}`);
    cancel_button.addClass("kt-spinner kt-spinner--right kt-spinner--md kt-spinner--dark disabled").html("Cancelling...").prop("disabled", true);

    $(`#part-video-upload-${data.section}-${data.part}`).show().find(`.kt-uppy__drag`).show();
    $(`#part-video-exist-buttons-div-${data.section}-${data.part}`).hide();
    $(`#part-video-uploading-buttons-div-${data.section}-${data.part}`).hide();
    $(`#video-filename-${data.section}-${data.part}`).val(null);

    $.ajax({
        url: "/course/management/content/cancel/video",
        data: {
            section: data.section,
            part: data.part,
            video_id: $video_id,
        },
        success: function (response) {
            toastr.success("Video upload has been cancelled successfully!");
            $(`#part-video-card-title-${data.section}-${data.part}`).find(`#part-video-title`).find(`i.fa`).remove();
            cancel_button.removeClass("kt-spinner kt-spinner--right kt-spinner--md kt-spinner--dark disabled").html("Cancel Upload").prop("disabled", false);
        },
        error: function () {
            toastr.error(
                "Error!",
                `Unable to cancel video upload! Please refresh your browser`
            );
            cancel_button.removeClass("kt-spinner kt-spinner--right kt-spinner--md kt-spinner--dark disabled").html("Cancel Upload").prop("disabled", false);
        },
    });
});

/**
 * FormDesigns:: Validation, Input Mask and etc
 *
 */

var FormDesignSection = (function () {
    var input_validations = function () {
        var alert = $(`#section-form-alert-${current_section}`);
        validator = $(`#section-form-${current_section}`).validate({
            rules: {
                section_name: {
                    required: true,
                    maxlength: 80,
                },
                objective: {
                    required: true,
                    maxlength: 200,
                },
            },

            invalidHandler: function (event, validator) {
                alert.removeClass("kt-hidden").show();
                KTUtil.scrollTop();
            },

            submitHandler: function (form) {
                $(`#section-form-submit-${current_section}`).addClass("kt-spinner kt-spinner--right kt-spinner--md kt-spinner--light disabled").html("Saving...").prop("disabled", true);
                $.ajax({
                    url: "/course_management/video_and_content/storeSection",
                    type: "POST",
                    data: {
                        section_name: $(
                            `#section-name-input-${current_section}`
                        ).val(),
                        objective: $(
                            `#section-objective-input-${current_section}`
                        ).val(),
                        section_id: current_section,
                        _token: $('input[name*="_token"]').val(),
                    },
                    success: function (response) {
                        alert.addClass("kt-hidden").hide();
                        $(
                            `#section-form-submit-${current_section}`
                        ).removeClass("kt-spinner kt-spinner--right kt-spinner--md kt-spinner--light disabled").html("Save Section").prop("disabled", false);

                        if (response.status == 200) {
                            toastr.success("Success!", response.message);
                            $(`#section-form-remove-${current_section}`).show();
                            if (response.exist == false) {
                                // Part Selection & Content
                                generateParts({
                                    part: 1,
                                    section: current_section,
                                });

                                /**
                                 * For dynamic data initialization for UPPY
                                 *
                                 */
                                section_part_data_initialization.part = 1;
                                section_part_data_initialization.section = current_section;
                                FormDesignVideo.init();
                                FormDesignArticle.init();
                                FormDesignQuiz.init();
                                FormDesignQuizItem.init();
                            }
                        } else {
                            toastr.error("Error!", response.message);
                        }
                    },
                    error: function (response) {
                        $(
                            `#section-form-submit-${current_section}`
                        ).removeClass("kt-spinner kt-spinner--right kt-spinner--md kt-spinner--light disabled").html("Save Section").prop("disabled", false);
                        toastr.error(
                            "Error!",
                            "Something went wrong! Please contact our support team to help you."
                        );
                    },
                });
            },
        });
    };

    return {
        init: function () {
            input_validations();
        },
    };
})();

var FormDesignVideo = (function () {
    var input_validations = function () {
        var data = {
            part: section_part_data_initialization.part,
            section: section_part_data_initialization.section,
        };
        var alert = $(`#part-video-form-alert-${data.section}-${data.part}`);
        validator = $(`#part-video-form-${data.section}-${data.part}`).validate({
            rules: {
                video_name: {
                    required: true,
                    maxlength: 80,
                },
            },
            invalidHandler: function (event, validator) {
                alert.removeClass("kt-hidden").show();
            },
            submitHandler: function (form) {
                var parts = $(`#section-${data.section}`).find(".part");
                var current_number_part = 1;
                parts.each(function (index, e) {
                    if ($(this).attr(`id`) == `part-${data.section}-${data.part}`) {
                        current_number_part = index;
                        return true;
                    }
                });

                var form_submit = $(`#part-video-form-submit-${data.section}-${data.part}` );
                form_submit.addClass("kt-spinner kt-spinner--right kt-spinner--md kt-spinner--light disabled").html("Saving Video Title...").prop("disabled", true);
                var $video_id = $(`#video-id-${data.section}-${data.part}`).val();

                $.ajax({
                    url: "/course/management/content/video/store",
                    type: "POST",
                    data: {
                        video_id: $video_id,
                        title: $(
                            `#part-video-input-${data.section}-${data.part}`
                        ).val(),
                        section: data.section,
                        part: data.part,
                        number_of_parts: parts.length - 1,
                        current_number_part: current_number_part,
                        _token: $('input[name*="_token"]').val(),
                    },
                    success: function (response) {
                        alert.addClass("kt-hidden").hide();
                        form_submit.removeClass("kt-spinner kt-spinner--right kt-spinner--md kt-spinner--light disabled").html("Save Video Title").prop("disabled", false);

                        if (response.status == 200) {
                            var partcontent = $(`#part-${data.section}-${data.part}`);
                            partcontent.find(`.button-remove-part`).click(function () {
                                removeSectionExistingPart(data, {
                                    id: response.data.video_id,
                                    type: "video",
                                });
                            });
                            var video_content_title = $(`#part-video-input-${data.section}-${data.part}`).val();
                            var part_content_title_limit = video_content_title.length > 35 ? `${video_content_title.substring( 0,35 )}...` : video_content_title;
                            partcontent.find(`#part-video-title`).html(`Video — ${part_content_title_limit} &nbsp; ${response.data.url==true? '<i class="fa fa-check-circle kt-font-success" style="font-size:1rem;"></i>' : ''}`);

                            $(`#video-id-${data.section}-${data.part}`).val(response.data.video_id);

                            if($video_id==""){
                                section_part_data_initialization.part = data.part;
                                section_part_data_initialization.section = data.section;
                                // PartVideoUppy.init();
                            }

                            if($(`#video-filename-${data.section}-${data.part}`).val()==""){
                                $(`#part-video-upload-${data.section}-${data.part}`).show();
                            }

                            toastr.success("Success!", response.message);
                        } else {
                            toastr.error("Error!", response.message);
                        }
                    },
                    error: function (response) {
                        form_submit.removeClass("kt-spinner kt-spinner--right kt-spinner--md kt-spinner--light disabled").html("Save Video Title").prop("disabled", false);
                        toastr.error(
                            "Error!",
                            "Something went wrong! Please try again later."
                        );
                    },
                });
            },
        });
    };

    return {
        init: function () {
            input_validations();
        },
    };
})();

var FormDesignArticle = (function () {
    var input_validations = function () {
        var data = {
            part: section_part_data_initialization.part,
            section: section_part_data_initialization.section,
        };
        var alert = $(`#part-article-form-alert-${data.section}-${data.part}`);

        validator = $(
            `#part-article-form-${data.section}-${data.part}`
        ).validate({
            rules: {
                article: {
                    required: true,
                    maxlength: 80,
                },
                article_textarea: {
                    required: true,
                },
            },
            invalidHandler: function (event, validator) {
                alert.removeClass("kt-hidden").show();
            },
            submitHandler: function (form) {
                var parts = $(`#section-${data.section}`).find(".part");
                var current_number_part = 1;
                parts.each(function (index, e) {
                    if (
                        $(this).attr(`id`) ==
                        `part-${data.section}-${data.part}`
                    ) {
                        current_number_part = index;
                        return true;
                    }
                });

                var form_submit = $(
                    `#part-article-form-submit-${data.section}-${data.part}`
                );
                form_submit.addClass("kt-spinner kt-spinner--right kt-spinner--md kt-spinner--light disabled").html("Saving Article...").prop("disabled", true);

                $.ajax({
                    url: "/course/management/content/article/store",
                    type: "POST",
                    data: {
                        article_id: $(
                            `#part-article-id-${data.section}-${data.part}`
                        ).val(),
                        title: $(
                            `#part-article-input-${data.section}-${data.part}`
                        ).val(),
                        textarea: $(
                            `#part-article-body-textarea-${data.section}-${data.part}`
                        ).val(),
                        section: data.section,
                        part: data.part,
                        number_of_parts: parts.length - 1,
                        current_number_part: current_number_part,
                        _token: $('input[name*="_token"]').val(),
                    },
                    success: function (response) {
                        alert.addClass("kt-hidden").hide();
                        form_submit.removeClass("kt-spinner kt-spinner--right kt-spinner--md kt-spinner--light disabled").html("Save Article").prop("disabled", false);
                        if (response.status == 200) {
                            var partcontent = $(
                                `#part-${data.section}-${data.part}`
                            );
                            partcontent
                                .find(`.button-remove-part`)
                                .click(function () {
                                    removeSectionExistingPart(data, {
                                        id: response.data.article_id,
                                        type: "article",
                                    });
                                });

                            var article_content_title = $(
                                `#part-article-input-${data.section}-${data.part}`
                            ).val();
                            var part_content_title_limit =
                                article_content_title.length > 35
                                    ? `${article_content_title.substring(
                                        0,
                                        35
                                    )}...`
                                    : article_content_title;
                            partcontent
                                .find(`#part-article-title`)
                                .html(
                                    `Article — ${part_content_title_limit} &nbsp; <i class="fa fa-check-circle kt-font-success" style="font-size:1rem;"></i>`
                                );

                            $(
                                `#part-article-id-${data.section}-${data.part}`
                            ).val(response.data.article_id);

                            toastr.success("Success!", response.message);

                            if (
                                $(
                                    `#video-id-${data.section}-${data.part}`
                                ).val() ||
                                $(
                                    `#part-quiz-id-${data.section}-${data.part}`
                                ).val()
                            ) {
                                setTimeout(() => {
                                    location.reload();
                                }, 500);
                            }
                        } else {
                            toastr.error("Error!", response.message);
                        }
                    },
                    error: function (response) {
                        form_submit.removeClass("kt-spinner kt-spinner--right kt-spinner--md kt-spinner--light disabled").html("Save Article").prop("disabled", false);
                        toastr.error(
                            "Error!",
                            "Something went wrong! Please try again later."
                        );
                    },
                });
            },
        });
    };

    return {
        init: function () {
            input_validations();
        },
    };
})();

var FormDesignQuiz = (function () {
    var input_validations = function () {
        var data = {
            part: section_part_data_initialization.part,
            section: section_part_data_initialization.section,
        };
        var alert = $(`#part-quiz-form-alert-${data.section}-${data.part}`);

        validator = $(`#part-quiz-form-${data.section}-${data.part}`).validate({
            rules: {
                quiz: {
                    required: true,
                    maxlength: 80,
                },
            },
            invalidHandler: function (event, validator) {
                alert.removeClass("kt-hidden").show();
            },
            submitHandler: function (form) {
                var parts = $(`#section-${data.section}`).find(".part");
                var current_number_part = 1;
                parts.each(function (index, e) {
                    if (
                        $(this).attr(`id`) ==
                        `part-${data.section}-${data.part}`
                    ) {
                        current_number_part = index;
                        return true;
                    }
                });

                var form_submit = $(
                    `#part-quiz-form-submit-${data.section}-${data.part}`
                );
                form_submit.addClass("kt-spinner kt-spinner--right kt-spinner--md kt-spinner--light disabled").html("Saving Quiz...").prop("disabled", true);

                $.ajax({
                    url: "/course/management/content/quiz/store",
                    type: "POST",
                    data: {
                        quiz_id: $(
                            `#part-quiz-id-${data.section}-${data.part}`
                        ).val(),
                        title: $(
                            `#part-quiz-input-${data.section}-${data.part}`
                        ).val(),
                        section: data.section,
                        part: data.part,
                        number_of_parts: parts.length - 1,
                        current_number_part: current_number_part,
                        _token: $('input[name*="_token"]').val(),
                    },
                    success: function (response) {
                        alert.addClass("kt-hidden").hide();
                        form_submit.removeClass("kt-spinner kt-spinner--right kt-spinner--md kt-spinner--light disabled").html("Save Quiz").prop("disabled", false);
                        if (response.status == 200) {
                            var partcontent = $(`#part-${data.section}-${data.part}`);
                            partcontent.find(`.button-remove-part`).click(function () {
                                removeSectionExistingPart(data, {
                                    id: response.data.quiz_id,
                                    type: "quiz",
                                });
                            });

                            var quiz_content_title = $(`#part-quiz-input-${data.section}-${data.part}`).val();
                            var part_content_title_limit =
                                quiz_content_title.length > 35
                                    ? `${quiz_content_title.substring(
                                        0,
                                        35
                                    )}...`
                                    : quiz_content_title;

                            var should_display_icon = response.data.items > 0 ? true : false;
                            partcontent
                                .find(`#part-quiz-title`)
                                .html(
                                    `Quiz — ${part_content_title_limit} &nbsp; ${
                                    should_display_icon
                                        ? '<i class="fa fa-check-circle kt-font-success" style="font-size:1rem;"></i>'
                                        : ""
                                    }`
                                );

                            $(`#part-quiz-id-${data.section}-${data.part}`).val(response.data.quiz_id);
                            $(`#part-quiz-item-wrapper-${data.section}-${data.part}`).show();
                            toastr.success("Success!", response.message);
                            

                        } else {
                            toastr.error("Error!", response.message);
                        }
                    },
                    error: function (response) {
                        form_submit.removeClass("kt-spinner kt-spinner--right kt-spinner--md kt-spinner--light disabled").html("Save Quiz").prop("disabled", false);
                        toastr.error("Error!","Something went wrong! Please try again later.");
                    },
                });
            },
        });
    };

    return {
        init: function () {
            input_validations();
        },
    };
})();

var FormDesignQuizItem = (function () {
    var input_validations = function () {
        var data = {
            part: section_part_data_initialization.part,
            section: section_part_data_initialization.section,
        };
        var alert = $(
            `#part-quiz-item-form-alert-${data.section}-${data.part}`
        );

        validator = $(
            `#part-quiz-item-form-${data.section}-${data.part}`
        ).validate({
            rules: {
                quiz_textarea: {
                    required: true,
                },
                [`part-quiz-choice-1-${data.section}-${data.part}`]: {
                    required: true,
                },
                [`part-quiz-choice-2-${data.section}-${data.part}`]: {
                    required: true,
                },
                [`part-quiz-choice-3-${data.section}-${data.part}`]: {
                    required: true,
                },
                [`part-quiz-choice-4-${data.section}-${data.part}`]: {
                    required: true,
                },
            },
            invalidHandler: function (event, validator) {
                alert.removeClass("kt-hidden").show();
            },
            submitHandler: function (form) {
                var parts = $(`#section-${data.section}`).find(".part");
                var current_number_part = 1;
                parts.each(function (index, e) {
                    if (
                        $(this).attr(`id`) ==
                        `part-${data.section}-${data.part}`
                    ) {
                        current_number_part = index;
                        return true;
                    }
                });

                var form_submit = $(
                    `#part-quiz-item-form-submit-${data.section}-${data.part}`
                );
                form_submit.addClass("kt-spinner kt-spinner--right kt-spinner--md kt-spinner--light disabled").html("Saving Quiz Item...").prop("disabled", true);

                var choices = {
                    one: {
                        description: $(
                            `input[name="part-quiz-choice-1-${data.section}-${data.part}"]`
                        ).val(),
                        explanation: $(
                            `input[name="part-quiz-choice-explain-1-${data.section}-${data.part}"]`
                        ).val(),
                    },
                    two: {
                        description: $(
                            `input[name="part-quiz-choice-2-${data.section}-${data.part}"]`
                        ).val(),
                        explanation: $(
                            `input[name="part-quiz-choice-explain-2-${data.section}-${data.part}"]`
                        ).val(),
                    },
                    three: {
                        description: $(
                            `input[name="part-quiz-choice-3-${data.section}-${data.part}"]`
                        ).val(),
                        explanation: $(
                            `input[name="part-quiz-choice-explain-3-${data.section}-${data.part}"]`
                        ).val(),
                    },
                    four: {
                        description: $(
                            `input[name="part-quiz-choice-4-${data.section}-${data.part}"]`
                        ).val(),
                        explanation: $(
                            `input[name="part-quiz-choice-explain-4-${data.section}-${data.part}"]`
                        ).val(),
                    },
                };

                var quiz_item_id = $(
                    `#part-quiz-item-id-${data.section}-${data.part}`
                ).val();
                $.ajax({
                    url: "/course/management/content/quiz/item/store",
                    type: "POST",
                    data: {
                        quiz_id: $(
                            `#part-quiz-id-${data.section}-${data.part}`
                        ).val(),
                        quiz_item_id: quiz_item_id,
                        question: $(
                            `#part-quiz-body-textarea-${data.section}-${data.part}`
                        ).val(),
                        choices: choices,
                        answer: $(
                            `input[name="part-quiz-item-choices-${data.section}-${data.part}"]:checked`
                        ).val(),
                        section: data.section,
                        part: data.part,
                        number_of_parts: parts.length - 1,
                        current_number_part: current_number_part,
                        _token: $('input[name*="_token"]').val(),
                    },
                    success: function (response) {
                        alert.addClass("kt-hidden").hide();
                        form_submit.removeClass("kt-spinner kt-spinner--right kt-spinner--md kt-spinner--light disabled").html("Save Quiz Item").prop("disabled", false);
                        if (response.status == 200) {
                            var quiz_wrapper = $(`#part-quiz-item-wrapper-${data.section}-${data.part}`);
                            var quiz_item_body_wrapper = $(`#part-quiz-item-body-wrapper-${data.section}-${data.part}`);

                            var quiz_item_data = response.data.quiz_item;
                            $(`#part-quiz-item-id-${data.section}-${data.part}`).val(quiz_item_data.id);

                            var partcontent = $(`#part-${data.section}-${data.part}`);
                            var quiz_content_title = $(`#part-quiz-input-${data.section}-${data.part}`).val();
                            var part_content_title_limit =
                                quiz_content_title.length > 35
                                    ? `${quiz_content_title.substring(0,35)}...`
                                    : quiz_content_title;

                            partcontent.find(`#part-quiz-title`)
                                .html(`Quiz — ${part_content_title_limit} &nbsp; <i class="fa fa-check-circle kt-font-success" style="font-size:1rem;"></i>`);

                            toastr.success("Success!", response.message);

                            if (quiz_item_id && quiz_item_id != " ") {
                                setTimeout(() => {
                                    location.reload();
                                }, 500);
                            } else {
                                var list = $(`#part-quiz-item-list-${data.section}-${data.part}`);
                                var list_row_no = $(`#part-quiz-item-list-${data.section}-${data.part}`).find(`div.question-items`).length;
                                var textarea = jQuery(`<div>${quiz_item_data.question}</div>`).text();

                                var itemtab = $(`<div class="row question-items"><b>${list_row_no + 1}.</b>&nbsp; ${(textarea != "" ? (textarea.length > 100
                                        ? textarea.substring(0, 100) + "..."
                                        : textarea) : (quiz_item_data.question.length > 100 ? quiz_item_data.question.substring(0, 100) : quiz_item_data.question))}</div>`);

                                itemtab.on("click", function () {
                                    generate_quiz_item_content(
                                        quiz_item_data,
                                        data
                                    );
                                });

                                list.append(itemtab);
                            }

                            $(`#part-quiz-body-textarea-${data.section}-${data.part}`).val("");
                            $(`#part-quiz-body-textarea-${data.section}-${data.part}`).summernote({code: ""});
                            
                            quiz_wrapper.find(`.button-back-quiz-list`).hide().prev().show();
                            quiz_item_body_wrapper.hide().prev().show();
                        } else {
                            toastr.error("Error!", response.message);
                        }
                    },
                    error: function (response) {
                        form_submit.removeClass("kt-spinner kt-spinner--right kt-spinner--md kt-spinner--light disabled").html("Save Quiz Item").prop("disabled", false);
                        toastr.error(
                            "Error!",
                            "Something went wrong! Please try again later."
                        );
                    },
                });
            },
        });
    };

    return {
        init: function () {
            input_validations();
        },
    };
})();
