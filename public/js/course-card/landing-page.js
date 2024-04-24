function renderTopProfession(data, carousel) {
    $.ajax({
        url: "/api/courses/profession",
        data: {
            profession: data.profession,
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
                    (num_loop < 1 ? 1 : num_loop) + (remainder > 0 ? 1 : 0);
                top_pr_total_page = totalPages;

                renderCourseWithCarousel(
                    { data: data, total: total, totalPages: totalPages },
                    carousel
                );
            }
        },
        error: function (response) {
            // toastr.error('Error!', 'Something went wrong! Please try again later.');
        },
    });
}

function renderTopCourses(carousel) {
    $.ajax({
        url: "/api/courses/top",
        data: {
            page: carousel.page,
            taking: carousel.take,
        },
        success: function (response) {
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
            top_course_total_page = totalPages;

            renderCourseWithCarousel(
                { data: data, total: total, totalPages: totalPages },
                carousel
            );
        },
        error: function (response) {
            // toastr.error('Error!', 'Something went wrong! Please try again later.');
        },
    });
}

function renderNewestCourses(carousel) {
    $.ajax({
        url: "/api/courses/newest",
        data: {
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
                newest_course_total_page = totalPages;

                renderCourseWithCarousel(
                    { data: data, total: total, totalPages: totalPages },
                    carousel
                );
            }
        },
        error: function (response) {
            // toastr.error('Error!', 'Something went wrong! Please try again later.');
        },
    });
}

function renderInProgressCourses(carousel) {
    $.ajax({
        url: "/api/courses/in-progress",
        data: {
            page: carousel.page,
            taking: carousel.take,
        },
        success: function (response) {
            if (response.hasOwnProperty("data")) {
            }
        },
        error: function (response) {
            // toastr.error('Error!', 'Something went wrong! Please try again later.');
        },
    });
}

function renderCourseWithCarousel(response, carousel) {
    var data = response.data;

    var inner = $(`#${carousel.id}-carousel-inner`);
    inner
        .hide()
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

    if (typeof(data) == 'undefined' || data.length == 0) {
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
                "col-xl-3 col-lg-3 col-md-6 col-sm-6 col-12 popover__wrapper"
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
        var course_popover_content_body = $("<div />").addClass("kt-portlet__body")
        .html(`
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
 