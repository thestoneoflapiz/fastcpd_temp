var status_ = ["success", "danger", "warning", "info"];
var window_width = $(window).width();
var card_to_break = window_width < 1020 ? 1 : 2;
var card_to_take = window_width < 1020 ? (window_width < 740 ? 1 : 2) : 3;

var provider_course_page = 0;
var provider_course_total_page = 0;
var provider_course_slide = true;

var instructor_course_page = 0;
var instructor_course_total_page = 0;
var instructor_course_slide = true;

var course_instructors_page = 0;
var course_instructors_total_page = 0;
var course_instructors_slide = true;

jQuery(document).ready(function () {
    renderProviderCourses({
        page: provider_course_page,
        break: card_to_break,
        take: card_to_take,
        id: "provider-courses",
        slide: provider_course_slide,
    });

    $(`#provider-courses-carousel-control-prev`).click(function () {
        provider_course_slide = true;
        provider_course_page -= 1;

        if (provider_course_total_page > 1) {
            $(`#provider-courses-carousel-control-next`).show();
        }

        if (provider_course_page == 0) {
            $(`#provider-courses-carousel-control-prev`).hide();
        }

        /**
         * Submit Ajax
         *
         */
        renderProviderCourses({
            page: provider_course_page,
            break: card_to_break,
            take: card_to_take,
            id: "provider-courses",
            slide: provider_course_slide,
        });
    });

    $(`#provider-courses-carousel-control-next`).click(function () {
        provider_course_slide = false;
        provider_course_page += 1;

        $(`#provider-courses-carousel-control-prev`).show();

        if (provider_course_page == provider_course_total_page - 1) {
            $(`#provider-courses-carousel-control-next`).hide();
        }

        /**
         * Submit Ajax
         *
         */
        renderProviderCourses({
            page: provider_course_page,
            break: card_to_break,
            take: card_to_take,
            id: "provider-courses",
            slide: provider_course_slide,
        });
    });

    renderInstructorCourses({
        page: instructor_course_page,
        break: card_to_break,
        take: card_to_take,
        id: "instructor-courses",
        slide: instructor_course_slide,
    });

    $(`#instructor-courses-carousel-control-prev`).click(function () {
        instructor_course_slide = true;
        instructor_course_page -= 1;

        if (instructor_course_total_page > 1) {
            $(`#instructor-courses-carousel-control-next`).show();
        }

        if (instructor_course_page == 0) {
            $(`#instructor-courses-carousel-control-prev`).hide();
        }

        /**
         * Submit Ajax
         *
         */
        renderInstructorCourses({
            page: instructor_course_page,
            break: card_to_break,
            take: card_to_take,
            id: "instructor-courses",
            slide: instructor_course_slide,
        });
    });

    $(`#instructor-courses-carousel-control-next`).click(function () {
        instructor_course_slide = false;
        instructor_course_page += 1;

        $(`#instructor-courses-carousel-control-prev`).show();

        if (instructor_course_page == instructor_course_total_page - 1) {
            $(`#instructor-courses-carousel-control-next`).hide();
        }

        /**
         * Submit Ajax
         *
         */
        renderInstructorCourses({
            page: instructor_course_page,
            break: card_to_break,
            take: card_to_take,
            id: "instructor-courses",
            slide: instructor_course_slide,
        });
    });

    renderCourseInstructors({
        page: course_instructors_page,
        break: card_to_break,
        take: card_to_take,
        id: "course-instructors",
        slide: course_instructors_slide,
    });

    $(`#course-instructors-carousel-control-prev`).click(function () {
        course_instructors_slide = true;
        course_instructors_page -= 1;

        if (course_instructors_total_page > 1) {
            $(`#course-instructors-carousel-control-next`).show();
        }

        if (course_instructors_page == 0) {
            $(`#course-instructors-carousel-control-prev`).hide();
        }

        /**
         * Submit Ajax
         *
         */
        renderCourseInstructors({
            page: course_instructors_page,
            break: card_to_break,
            take: card_to_take,
            id: "course-instructors",
            slide: course_instructors_slide,
        });
    });

    $(`#course-instructors-carousel-control-next`).click(function () {
        course_instructors_slide = false;
        course_instructors_page += 1;

        $(`#course-instructors-carousel-control-prev`).show();

        if (course_instructors_page == course_instructors_total_page - 1) {
            $(`#course-instructors-carousel-control-next`).hide();
        }

        /**
         * Submit Ajax
         *
         */
        renderCourseInstructors({
            page: course_instructors_page,
            break: card_to_break,
            take: card_to_take,
            id: "course-instructors",
            slide: course_instructors_slide,
        });
    });
});

function renderProviderCourses(carousel) {
    $.ajax({
        url: "/api/courses/provider",
        data: {
            provider: $(`input[name="provider_id"]`).val(),
            course: $(`input[name="course_id"]`).val(),
            page: carousel.page,
            taking: carousel.take,
        },
        success: function (response) {
            if (response.hasOwnProperty("data")) {
                var data = response.data;
                var total = response.total;

                var num_loop = Math.floor(total / carousel.take);
                var remainder =
                    total < 1
                        ? 0
                        : total < carousel.take
                        ? 0
                        : total % carousel.take;
                var totalPages =
                    (num_loop < 1 ? 1 : num_loop) + (remainder ? 1 : 0);
                provider_course_total_page = totalPages;

                if(total > 0){
                    renderCourseWithCarousel(
                        { data: data, total: total, totalPages: totalPages },
                        carousel
                    );
                    $(`#more-course-by-provider`).show();
                }else{
                    $(`#more-course-by-provider`).hide();
                }
                
            }
        },
        error: function (response) {
            console.log("Something went wrong! Please try again later.");
        },
    });
}

function renderInstructorCourses(carousel) {
    $.ajax({
        url: "/api/courses/instructor",
        data: {
            course: $(`input[name="course_id"]`).val(),
            page: carousel.page,
            taking: carousel.take,
        },
        success: function (response) {
            if (response.hasOwnProperty("data")) {
                var data = response.data;
                var total = response.total;

                var num_loop = Math.floor(total / carousel.take);
                var remainder =
                    total < 1
                        ? 0
                        : total < carousel.take
                        ? 0
                        : total % carousel.take;
                var totalPages =
                    (num_loop < 1 ? 1 : num_loop) + (remainder ? 1 : 0);
                instructor_course_total_page = totalPages;

                if(total > 0){
                    renderCourseWithCarousel(
                        { data: data, total: total, totalPages: totalPages },
                        carousel
                    );
                    $(`#more-course-by-instructors`).show();
                }else{
                    $(`#more-course-by-instructors`).hide();
                }
            }
        },
        error: function (response) {
            console.log("Something went wrong! Please try again later.");
        },
    });
}

function renderCourseInstructors(carousel) {
    $.ajax({
        url: "/api/course/instructor/list",
        data: {
            course: $(`input[name="course_id"]`).val(),
            page: carousel.page,
            take: carousel.take,
        },
        success: function (response) {
            if (response.hasOwnProperty("data")) {
                var data = response.data;
                var total = data.length;

                var num_loop = Math.floor(total / carousel.take);
                var remainder =
                    total < 1
                        ? 0
                        : total < carousel.take
                        ? 0
                        : total % carousel.take;
                var totalPages =
                    (num_loop < 1 ? 1 : num_loop) + (remainder ? 1 : 0);
                course_instructors_total_page = totalPages;

                renderInstructorWithCarousel(
                    { data: data, total: total, totalPages: totalPages },
                    carousel
                );
            }
        },
        error: function (response) {
            console.log("Something went wrong! Please try again later.");
        },
    });
}

function renderCourseWithCarousel(response, carousel) {
    var data = response.data;

    var inner = $(`#${carousel.id}-carousel-inner`);
    inner
        .hide("slide", { direction: carousel.slide ? "right" : "left" }, 350)
        .html(``);

    var carousel_prev = $(`#${carousel.id}-carousel-control-prev`);
    var carousel_next = $(`#${carousel.id}-carousel-control-next`);

    if (carousel.page == 0) {
        carousel_prev.hide();
    } else {
        carousel_prev.show();
    }
    if (response.totalPages == 1 || response.totalPages - 1 == carousel.page) {
        carousel_next.hide();
    } else {
        carousel_next.show();
    }

    if (data.length == 0) {
        $(`#${carousel.id} > div.centered`).show(
            "slide",
            { direction: carousel.slide ? "right" : "left" },
            350
        );
        return;
    } else {
        $(`#${carousel.id} > div.centered`).hide(
            "slide",
            { direction: carousel.slide ? "right" : "left" },
            350
        );
    }

    var carousel_item = document.createElement("div");
    $(carousel_item)
        .addClass("carousel-item active")
        .attr("id", `${carousel.id}-carousel-item`)
        .html('<div class="row"></div>');
    inner.append(carousel_item);

    var control = 0;
    var right = true;
    data.forEach((row, index) => {
        /**
         * Objectives
         *
         */

        var li_display = "";
        var objectives = JSON.parse(row.objectives);
        objectives.forEach((objective, index) => {
            var strObj = objective;
            if (strObj.length > 80) strObj = strObj.substring(0, 80) + "...";
            li_display += `<li>${strObj}</li>`;
        });

        var course_wrapper = $("<div />");
        var course_popover_main = $("<div />");

        $(course_wrapper)
            .addClass(
                "col-xl-4 col-lg-6 col-md-6 col-sm-6 col-12 popover__wrapper"
            )
            .attr("id", `${carousel.id}-course-card-${row.id}`)
            .appendTo(`div#${carousel.id}-carousel-item > div.row`);

        $(course_popover_main)
            .addClass(
                "kt-portlet kt-portlet--fit kt-portlet--head-lg kt-portlet--head-overlay kt-portlet--height-fluid kt-portlet--bordered popover__main"
            )
            .appendTo(`#${carousel.id}-course-card-${row.id}`);
        var course_popover_content = $("<div />");

        if (carousel.break == control) {
            right = !right;
            control = 0;
        }
        if (right) {
            course_popover_content.addClass(
                "kt-portlet kt-portlet--height-fluid- popover__content--right"
            );
        } else {
            course_popover_content.addClass(
                "kt-portlet kt-portlet--height-fluid- popover__content--left"
            );
        }
        control++;
        var course_popover_main_head = $("<div />")
            .addClass("kt-portlet__head kt-portlet__space-x");
        
        if(row.discount){
            var prices_div = `<div class="row card-price">
                <div class="col-12">
                    <span class="card-price--discounted">
                        ₱${row.discounted_price.toLocaleString(undefined, {maximumFractionDigits:2, minimumFractionDigits: 2})} 
                    </span>
                    <span class="card-price--original">
                        ₱${row.price.toLocaleString(undefined, {maximumFractionDigits:2, minimumFractionDigits: 2})} 
                    </span>
                </div>
            </div>`;
        }else{
            var prices_div = `<div class="row card-price">
                <div class="col-12">
                    <span class="card-price--discounted">
                        ₱${row.price.toLocaleString(undefined, {maximumFractionDigits:2, minimumFractionDigits: 2})} 
                    </span>
                </div>
            </div>`;
        }

        var course_popover_main_body = $("<div />").addClass("kt-portlet__body")
        .html(`<div class="kt-widget27">
            <div class="kt-widget27__visual">
                <img alt="FastCPD Courses ${row.title}" src="${row.course_poster}" class="card-img">
                <div class="popover__overlay"></div>
                <div class="kt-widget27__btn">
                    <a class="btn btn-pill btn-${
                        row.course_status_color
                    } btn-elevate btn-bold">${row.course_status}</a>
                </div>
            </div>
            <div class="kt-widget27__container kt-portlet__space-x">
                <div class="row">
                    <div class="col-12">${row.program_accreditation_no}</div>
                    <div class="col-12"><h5>${row.title}</h5></div>
                    <div class="col-12"><span class="course-rating-${row.id}" style="display:none"></span></div>
                </div>
                <br/>
                <div class="row card-price">
                    <div class="col-12">
                        <span class="card-price--discounted">
                            CPD Units ${row.total_unit_amounts}
                        </span>
                    </div>
                </div>
                ${prices_div}
            </div>
        </div>`);

        var course_popover_content_head = $("<div />")
            .addClass("kt-portlet__head kt-portlet__head--noborder")
            .html(
                row.course_status == "Ended"
                    ? ``
                    : `
        <div class="kt-portlet__head-label kt-label--adjust">
            <span>Get until ${row.session_end_string}</span>
        </div>`
            );
        var course_popover_content_body = $("<div />").addClass(
            "kt-portlet__body"
        ).html(`
        <div class="kt-widget kt-widget--user-profile-2">
            <div class="kt-widget__head">
                <div class="kt-widget__info">
                    <a href="javascript:;" class="kt-widget__username">${
                        row.title 
                    }</a><br/>
                    <span>${row.professions}</span>
                    <span class="kt-widget__desc">${row.instructors}</span>
                </div>
            </div>
            <div class="kt-widget__body">
                <div style="padding-left:1rem;">
                    <div class="kt-widget__section">${row.headline}</div>
                    <div class="kt-widget__content">
                        <div class="kt-widget__stats">
                            <div class="kt-widget__icon minimize">
                                <i class="flaticon-presentation"></i>
                            </div>
                            <div class="kt-widget__details">
                                <span class="kt-widget__title">Quizzes</span>
                                <span class="kt-widget__value">${
                                    row.total_quizzes
                                }</span>
                            </div>
                        </div>
                        <div class="kt-widget__stats">
                            <div class="kt-widget__icon minimize">
                                <i class="flaticon-time"></i>
                            </div>
                            <div class="kt-widget__details">
                                <span class="kt-widget__title">Video</span>
                                <span class="kt-widget__value">${
                                    row.total_videos
                                }</span>
                            </div>
                        </div>
                        <div class="kt-widget__stats">
                            <div class="kt-widget__icon minimize">
                                <i class="flaticon-medal"></i>
                            </div>
                            <div class="kt-widget__details">
                                <span class="kt-widget__title">UNITS</span>
                                <span class="kt-widget__value">${
                                    row.total_unit_amounts
                                }</span> 
                            </div>
                        </div>
                    </div>
                    <div class="kt-widget__item">
                        <div class="kt-widget__contact">
                            <span class="kt-widget__data">
                                <ul>${li_display}</ul>
                            </span>
                        </div>
                    </div>
                </div>
            </div>
            <div class="kt-widget__footer">
                <button type="button" class="btn btn-${row.add_to_cart_button.status} btn-lg btn-upper ${row.course_status == "Ended" ? "disabled" : "disabled"}" 
                    ${row.course_status == "Ended" ? "disabled" : "disabled"} onclick="${row.add_to_cart_button.link ? `window.location='${row.add_to_cart_button.link}'`: `addToCart({
                        type: 'course', data_id: ${row.id}
                    }, this)`}">
                    ${row.add_to_cart_button.label}
                </button>
            </div>
        </div>`);


        course_popover_main
            .append(course_popover_main_head)
            .append(course_popover_main_body)
            .click(function () {
                window.open(`/course/${row.url}`);
            }).css({
                cursor: "pointer"
            });
        course_popover_content
            .append(course_popover_content_head)
            .append(course_popover_content_body);
        course_wrapper
            .append(course_popover_main)
            .append(course_popover_content);

        if(row.fast_cpd_status=="live" && row.avg_course_rating > 0){
            $(`.course-rating-${row.id}`).starRating({
                totalStars: 5,
                initialRating: row.avg_course_rating,
                readOnly: true,
                starShape: "rounded",
                starSize: 15,
            }).show();
        }
    });

    $(inner).show(
        "slide",
        { direction: !carousel.slide ? "right" : "left" },
        350
    );
}

function renderInstructorWithCarousel(response, carousel) {
    var data = response.data;

    var inner = $(`#${carousel.id}-carousel-inner`);
    inner
        .hide("slide", { direction: carousel.slide ? "right" : "left" }, 350)
        .html(``);

    var carousel_prev = $(`#${carousel.id}-carousel-control-prev`);
    var carousel_next = $(`#${carousel.id}-carousel-control-next`);

    if (carousel.page == 0) {
        carousel_prev.hide();
    } else {
        carousel_prev.show();
    }
        
    if (response.totalPages == 1 || response.totalPages - 1 == carousel.page) {
        carousel_next.hide();
    } else {
        carousel_next.show();
    }

    if (data.length == 0) {
        $(`#${carousel.id} > div.centered`).show(
            "slide",
            { direction: carousel.slide ? "right" : "left" },
            350
        );
        return;
    } else {
        $(`#${carousel.id} > div.centered`).hide(
            "slide",
            { direction: carousel.slide ? "right" : "left" },
            350
        );
    }

    var carousel_item = document.createElement("div");
    $(carousel_item)
        .addClass("carousel-item active")
        .attr("id", `${carousel.id}-carousel-item`)
        .html('<div class="row"></div>');
    inner.append(carousel_item);

    var control = 0;
    data.forEach((row, index) => {
        var wrapper = $("<div />");
        var main = $("<div />");

        $(wrapper)
            .addClass("col-xl-4 col-lg-6 col-md-6 col-sm-6 col-12")
            .attr("id", `${carousel.id}-course-card-${row.id}`)
            .appendTo(`div#${carousel.id}-carousel-item > div.row`);

        $(main)
            .addClass("kt-portlet kt-portlet--height-fluid")
            .appendTo(`#${carousel.id}-course-card-${row.id}`);

        if (carousel.break == control) {
            control = 0;
        }

        control++;
        var main_head = $("<div />")
            .addClass("kt-portlet__head kt-portlet__head--noborder")
            .html(
                `<div class="kt-portlet__head-label"><h3 class="kt-portlet__head-title"></h3></div>`
            );

        var random_ = Math.floor(Math.random() * 4);
        var website_link = ``;
        if (row.website) {
            website_link = `<a href="${row.website}" target="_blank" class="btn btn-icon btn-google">\
                            <i class="flaticon2-world"></i>\
                        </a>`;
        }

        var facebook_link = ``;
        if (row.facebook) {
            facebook_link = `<a href="${row.facebook}" target="_blank" class="btn btn-icon btn-facebook">\
                            <i class="socicon-facebook"></i>\
                        </a>`;
        }

        var linkedin_link = ``;
        if (row.linkedin) {
            linkedin_link = `<a href="${row.linked}" target="_blank" class="btn btn-icon btn-twitter">\
                            <i class="socicon-linkedin"></i>\
                        </a>`;
        }

        var main_body = $("<div />").addClass("kt-portlet__body")
            .html(`<div class="kt-widget kt-widget--user-profile-2">
        <div class="kt-widget__head"> 
            <div class="kt-widget__media">
                ${
                    row.image
                        ? `<img class="kt-widget__img" src="${row.image}" alt="image">`
                        : `<div class="kt-widget__pic kt-widget__pic--${
                              status_[random_]
                          } kt-font-${status_[random_]} kt-font-boldest">${(
                              row.name[0] + row.name[1]
                          ).toUpperCase()}</div>`
                }
            </div>
            <div class="kt-widget__info">
                <a href="javascript:;" class="kt-widget__username">${
                    row.name
                }</a>
                <span class="kt-widget__desc kt-font-bold">Instructor</span>
            </div>
        </div>
        <div class="kt-widget__body">
            <div class="kt-widget__section" style="height:150px;">${
                row.headline ? (row.headline.length > 180 ? (row.headline.substr(0, 180))+'...' : row.headline) : ''
            }</div>
            <div class="kt-widget__item">
                <div class="kt-widget__contact">
                    <span  style="font-size:1rem;"><span class="kt-font-bolder kt-font-info" style="font-size:1.3rem;">${row.total_courses.toLocaleString()}</span>&nbsp; &nbsp; Courses</span>
                    <span  style="font-size:1rem;"><span class="kt-font-bolder kt-font-info" style="font-size:1.3rem;">${row.total_webinars.toLocaleString()}</span>&nbsp; &nbsp; Webinars</span>
                    <span  style="font-size:1rem;"><span class="kt-font-bolder kt-font-info" style="font-size:1.3rem;">${row.total_providers.toLocaleString()}</span>&nbsp; &nbsp; Assoc. Providers</span>
                </div> <br/>
                <div class="kt-widget__action row justify-content-center">${
                    website_link +
                    `&nbsp; &nbsp;` +
                    facebook_link +
                    `&nbsp; &nbsp;` +
                    linkedin_link
                }</div>
            </div>
        </div>
        <div class="kt-widget__footer">
            <button type="button" class="btn btn-label-info btn-lg btn-upper" onclick="window.open('/instructor/${
            row.username
        }')">view profile</button>
        </div>
    </div>`);

        main.append(main_head).append(main_body);
        wrapper.append(main);
    });

    $("#course-instructors").show();
    $(inner).show(
        "slide",
        { direction: !carousel.slide ? "right" : "left" },
        350
    );

}
