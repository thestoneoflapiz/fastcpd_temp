var status_ = ["success", "danger", "warning", "info"];
var window_width = $(window).width();
var card_to_break = window_width < 1020 ? 1 : 2;
var card_to_take =
    window_width < 1400
        ? window_width < 1020
            ? window_width < 600
                ? 1
                : 2
            : 3
        : 4;

var instructor_course_page = 0;
var instructor_course_total_page = 0;
var instructor_course_slide = true;

var instructor_providers_page = 0;
var instructor_providers_total_page = 0;
var instructor_providers_slide = true;

jQuery(document).ready(function () {
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

    renderInstructorProviders({
        page: instructor_providers_page,
        break: card_to_break,
        take: card_to_take,
        id: "instructor-providers",
        slide: instructor_providers_slide,
    });

    $(`#instructor-providers-carousel-control-prev`).click(function () {
        instructor_providers_slide = true;
        instructor_providers_page -= 1;

        if (instructor_providers_total_page > 1) {
            $(`#instructor-providers-carousel-control-next`).show();
        }

        if (instructor_providers_page == 0) {
            $(`#instructor-providers-carousel-control-prev`).hide();
        }

        /**
         * Submit Ajax
         *
         */
        renderInstructorProviders({
            page: instructor_providers_page,
            break: card_to_break,
            take: card_to_take,
            id: "instructor-providers",
            slide: instructor_providers_slide,
        });
    });

    $(`#instructor-providers-carousel-control-next`).click(function () {
        instructor_providers_slide = false;
        instructor_providers_page += 1;

        $(`#instructor-providers-carousel-control-prev`).show();

        if (instructor_providers_page == instructor_providers_total_page - 1) {
            $(`#instructor-providers-carousel-control-next`).hide();
        }

        /**
         * Submit Ajax
         *
         */
        renderInstructorProviders({
            page: instructor_providers_page,
            break: card_to_break,
            take: card_to_take,
            id: "instructor-providers",
            slide: instructor_providers_slide,
        });
    });
});

function renderInstructorCourses(carousel) {
    $.ajax({
        url: "/api/instructor/courses",
        data: {
            instructor: $(`input[name="user_id"]`).val(),
            page: carousel.page,
            take: carousel.take,
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

                renderCourseWithCarousel(
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

function renderInstructorProviders(carousel) {
    $.ajax({
        url: "/api/instructor/providers",
        data: {
            instructor: $(`input[name="user_id"]`).val(),
            page: carousel.page,
            take: carousel.take,
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
                instructor_providers_total_page = totalPages;

                renderProviderWithCarousel(
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
                "col-xl-3 col-lg-4 col-md-6 col-sm-6 col-12 popover__wrapper"
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
                <button type="button" class="btn btn-${row.add_to_cart_button.status} btn-lg btn-upper ${row.course_status == "Ended" ? "disabled" : ""}" 
                    ${row.course_status == "Ended" ? "disabled" : ""} onclick="${row.add_to_cart_button.link ? `window.location='${row.add_to_cart_button.link}'`: `addToCart({
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

function renderProviderWithCarousel(response, carousel) {
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
    data.forEach((profile, index) => {
        var row = profile.info;
        var wrapper = $("<div />");
        var main = $("<div />");

        $(wrapper)
            .addClass("col-xl-3 col-lg-4 col-md-6 col-sm-6 col-12")
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
                    row.logo
                        ? `<img class="kt-widget__img" src="${row.logo}" alt="image">`
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
                <span class="kt-widget__desc kt-font-bold">Provider</span>
            </div>
        </div>
        <div class="kt-widget__body">
            <div class="kt-widget__section">${
                row.headline ? (row.headline.length > 180 ? (row.headline.substr(0,180)+'...') : row.headline) : ""
            }</div>
            <div class="kt-widget__item">
                <div class="kt-widget__contact">
                    <span  style="font-size:1rem;"><span class="kt-font-bolder kt-font-info" style="font-size:1.7rem;">${profile.total_courses.toLocaleString()}</span>&nbsp; &nbsp; Courses</span>
                    <span  style="font-size:1rem;"><span class="kt-font-bolder kt-font-info" style="font-size:1.7rem;">${profile.total_webinars.toLocaleString()}</span>&nbsp; &nbsp; Webinars</span>
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
            <button type="button" class="btn btn-label-info btn-lg btn-upper" onclick="window.open('/provider/${
            row.url
        }')">view provider</button>
        </div>
    </div>`);

        main.append(main_head).append(main_body);
        wrapper.append(main);
    });

    $(inner).show(
        "slide",
        { direction: !carousel.slide ? "right" : "left" },
        350
    );
}
