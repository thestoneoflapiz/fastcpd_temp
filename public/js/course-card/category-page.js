/**
 * Filter
 *
 */

var top_5_courses = [];
var status_icon = {
    draft: {
        icon: "flaticon2-pen",
        color: "info",
    },
    approved: {
        icon: "flaticon2-correct",
        color: "success",
    },
    live: {
        icon: "flaticon2-correct",
        color: "success",
    },
    published: {
        icon: "flaticon2-correct",
        color: "success",
    },
    "in-review": {
        icon: "flaticon2-hourglass-1",
        color: "warning",
    },
    closed: {
        icon: "flaticon2-gear",
        color: "danger",
    },
    canceled: {
        icon: "flaticon2-gear",
        color: "danger",
    },
    ended: {
        icon: "flaticon2-calendar-2",
        color: "danger",
    },
};

var pagination = {
    page: 0,
    take: 12,
    order: "newest",
    search: null,
};

var filter = {
    quiz: 2, // all quizes
    rating: 0, // all ratings
    language: [],
};

var startPage = 0;
var incremSlide = 4;

jQuery(document).ready(function () {
    $(`input[name="filter_checkbox[]"]`).click(function () {
        var current = $(this);
        if (current.is(":checked")) {
            filterCourses(current.val(), "checked");
        } else {
            filterCourses(current.val(), "unchecked");
        }

        var all_checkboxes = $(`input[name="filter_checkbox[]"]:checked`);
        var all_radio = $(`input[name="filter_radio[]"]:checked`);

        $(`.filter_count`).html(
            all_checkboxes.length == 0 && all_radio.length == 0
                ? ``
                : `(${all_checkboxes.length + all_radio.length})`
        );
    });

    $(`input[name="filter_radio[]"]`).click(function () {
        var all_checkboxes = $(`input[name="filter_checkbox[]"]:checked`);
        var all_radio = $(`input[name="filter_radio[]"]:checked`);

        var current = $(this);
        filterCourses(current.val(), "checked");

        $(`.filter_count`).html(
            all_checkboxes.length == 0 && all_radio.length == 0
                ? ``
                : `(${all_checkboxes.length + all_radio.length})`
        );
    });

    fetchFilterCourses(pagination, filter);
    /**
     * Events on Search, Order, Page, and Take of Pagination
     *
     */
    // on change no. of records to take
    $('select[name="records"]').change(function (event) {
        var value = event.target.value;
        pagination.take = value;

        fetchFilterCourses(pagination, filter);
    });

    // on change course order
    $('select[name="orders"]').change(function (event) {
        var value = event.target.value;
        pagination.order = value;

        fetchFilterCourses(pagination, filter);
    });

    // on keypress enter search course title
    $('input[name="searches"]').keypress(function (event) {
        var keycode = event.keyCode ? event.keyCode : event.which;
        if (keycode == "13") {
            var value = event.target.value;
            pagination.search = value;

            fetchFilterCourses(pagination, filter);
        }
    });
});

function sortedBy(type) {
    pagination.order = type;
    fetchFilterCourses(pagination, filter);
    $(`.sorting-courses`).html(`Sort by: <b>${type.toUpperCase()}</b>`);
}

function renderCourseType(data, carousel) {
    $.ajax({
        url: "/api/courses/category",
        data: {
            profession: data.profession,
            type: data.type,
            page: carousel.page,
            take: carousel.take,
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
                (num_loop < 1 ? 1 : num_loop) + (remainder > 0 ? 1 : 0);
            course_type_total_page = totalPages;

            if(data.length > 0){
                if(top_5_courses.length == 0){
                    data.forEach((iicourses,i)=>{
                        top_5_courses.push({
                            name: iicourses.title,
                            url: iicourses.url
                        });
                    });

                    renderXML();
                }
            }
            renderCourseWithCarousel(
                { data: data, total: total, totalPages: totalPages },
                carousel
            );
        },
        error: function (response) {
            var body = response.responseJSON;
            toastr.error(
                "Error!",
                "Something went wrong! Please try again later."
            );
        },
    });
}

function renderPopularProvider(data, carousel) {
    $.ajax({
        url: "/api/category/providers",
        data: {
            profession: data.profession,
            page: carousel.page,
            take: carousel.take,
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
                (num_loop < 1 ? 1 : num_loop) + (remainder > 0 ? 1 : 0);
            popular_providers_total_page = totalPages;

            renderProviderWithCarousel(
                { data: data, total: total, totalPages: totalPages },
                carousel
            );
        },
        error: function (response) {
            var body = response.responseJSON;
            toastr.error(
                "Error!",
                "Something went wrong! Please try again later."
            );
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

        var li_display = ``;

        var objectives = JSON.parse(row.objectives);
        objectives.forEach((objective, index) => {
            var strObj = objective;
            if (strObj.length > 80) strObj = strObj.substring(0, 80) + `...`;
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
                <img alt="FastCPD Courses ${row.title}"  src="${row.course_poster}" class="card-img">
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
        .html(`<div class="row" id="${carousel.id}-carousel-item"></div>`);
    inner.append(carousel_item);

    data.forEach((row, index) => {
        $(`#${carousel.id}-carousel-item`).append(`
        <div class="col-xl-3 col-md-4 col-sm-6 col-12">
            <div class="kt-portlet kt-portlet--bordered">
                <div class="kt-portlet__body">
                    <div class="row">
                        <div class="col-4"><img class="user-image" src="${
                            row.logo
                        }"></div>
                        <div class="col-8">
                            <b>${row.name}</b> <br/>
                            ${row.total_webinars.toLocaleString()} Webinars <br/>
                            ${row.total_courses.toLocaleString()} Courses
                            <div class="kt-space-10"></div>
                            <button class="btn btn-sm btn-label-primary" onclick="window.open('/provider/${
                                row.url
                            }')">VIEW PROVIDER</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>`);
    });

    $(inner).show(
        "slide",
        { direction: !carousel.slide ? "right" : "left" },
        350
    );
}

function fetchFilterCourses(pagination, filter) {
    $("#all_courses").slideUp(250);
    $("#loading").slideDown(500);

    $.ajax({
        url: "/api/category/list",
        data: {
            profession: profession_id,
            pagination: pagination,
            filter: filter,
        },
        success: function (response) {
            var courses = response.data;

            renderPagination(response, pagination);
            renderCourseRow(response);

            setTimeout(() => {
                $("#all_courses").slideDown(750);
                $("#loading").slideUp(500);
            }, 1000);
        },
        error: function () {},
    });
}

function renderPagination(response, pagination) {
    var filter = response.data.length;
    var total = response.total; // total records
    var totalFilter = filter * (pagination.page + 1); // total fetched records

    var num_loop = parseInt(total < 1 ? 0 : total / pagination.take);
    var remainder = num_loop < 1 ? 0 : 3 % total;
    var totalPages = (num_loop < 1 ? 0 : num_loop) + (remainder ? 1 : 0);

    $("span#total_results").html(
        `${total} ${total > 1 ? "results" : "result"}`
    );
    $("span.pagination__desc").html(
        `Displaying ${totalFilter} of ${total} Courses`
    );
    var pagination_links = $("ul.kt-pagination__links").empty();

    /**
     * Render selection of pages
     *
     */
    var limit = totalPages - 1;
    var display = 0;
    for (let i = 0; i < totalPages; i++) {
        pagination_links.append(
            `<li ` +
                (i == pagination.page
                    ? `class="kt-pagination__link--active page-li"`
                    : `class="page-li"`) +
                `><a onclick="pageRender(${i})">${i + 1}</a></li>`
        );

        if (startPage <= i && display < 5) {
            display++;
        } else {
            var diff = limit - i;
            if (diff < 5 && display < 5) {
            } else {
                $("ul.kt-pagination__links li.page-li").eq(i).hide();
            }
        }
    }

    if (totalPages == 0) {
        $(`.pagination-row-courses`).hide();
    }

    if (totalPages > 5) {
        var prev_multi_link = $("<li />")
            .addClass("kt-pagination__link--first")
            .append(
                `<a href="javascript:;"><i class="fa fa-angle-double-left kt-font-brand"></i></a>`
            )
            .click(function () {
                startPage = 0;
                slide(totalPages, pagination);
            });
        var prev_step_link = $("<li />")
            .addClass("kt-pagination__link--next")
            .append(
                `<a href="javascript:;"><i class="fa fa-angle-left kt-font-brand"></i></a>`
            )
            .click(function () {
                startPage -= 6;
                slide(totalPages, pagination);
            });

        var next_multi_link = $("<li />")
            .addClass("kt-pagination__link--last")
            .append(
                `<a href="javascript:;"><i class="fa fa-angle-double-right kt-font-brand"></i></a>`
            )
            .click(function () {
                startPage = totalPages;
                slide(totalPages, pagination);
            });
        var next_step_link = $("<li />")
            .addClass("kt-pagination__link--prev")
            .append(
                `<a href="javascript:;"><i class="fa fa-angle-right kt-font-brand"></i></a>`
            )
            .click(function () {
                startPage += 5;
                slide(totalPages, pagination);
            });

        pagination_links
            .prepend(prev_step_link)
            .prepend(prev_multi_link)
            .append(next_step_link)
            .append(next_multi_link);
    }
}

function pageRender(page) {
    pagination.page = page;
    startPage = page;

    fetchFilterCourses(pagination, filter);
}

function slide(total, pagination) {
    var pagination_links = $("ul.kt-pagination__links li.page-li").hide();

    /**
     * Render selection of pages
     *
     */

    var limit = total - 1;
    startPage = startPage < 0 ? 0 : startPage;
    startPage = startPage > limit ? limit : startPage;

    var display = 0;
    for (let i = 0; i < total; i++) {
        pagination_links.eq(i).show();

        if (startPage <= i && display < 5) {
            display++;
        } else {
            var diff = limit - i;
            if (diff < 5 && display < 5) {
            } else {
                pagination_links.eq(i).hide();
            }
        }
    }
}

function renderCourseRow(response) {
    var group = $("#all_courses").empty();
    response.data.forEach((product, index) => {
        var container = $(`<div id="course-container-${product.id}" />`)
            .addClass(`course-container`)
            .css(`position`, `relative`)
            .hover(
                function () {
                    $(`#course-poster-img-${product.id}`).css({
                        "-webkit-filter": "grayscale(90%)",
                        "-webkit-transition": "all .6s ease",
                    });

                    $(
                        `#course-container-${product.id} .kt-portlet .kt-portlet__body`
                    ).css({
                        "background-color": "rgba(79, 101, 209, 0.08)",
                        "-webkit-transition": "all 0.6s ease",
                    });
                },
                function () {
                    $(`#course-poster-img-${product.id}`).css({
                        "-webkit-filter": "grayscale(0%)",
                        "-webkit-transition": "all 0.6s ease",
                    });

                    $(
                        `#course-container-${product.id} .kt-portlet .kt-portlet__body`
                    ).css({
                        "background-color": "rgba(79, 101, 209, 0)",
                        "-webkit-transition": "all 0.6s ease",
                    });
                }
            ).click(function(){
                window.open(`/${product.type}/${product.url}`);
            });

        var row = $(`<div />`).addClass(
            `kt-portlet kt-portlet--bordered course-row`
        );
        var body = $(`<div />`).addClass(`kt-portlet__body`);
        var profile = $(`<div />`).addClass(
            `kt-widget kt-widget--user-profile-3`
        );
        var top = $(`<div />`).addClass(`kt-widget__top`);
        var image = $(`<div />`)
            .addClass(`kt-widget__media`)
            .append(
                `<img id='course-poster-img-${product.id}' src="` +
                    (product.poster
                        ? product.poster
                        : `{{ asset('img/sample/noimage.png') }}`) +
                    `" class="course-list-poster" alt="image">`
            );

        var accreditation_string = ``;
        var units_string = ``;
        var prices_string = ``;


        if(product.accreditation){
            product.accreditation.forEach((acc, i) => {
                if(product.accreditation.length == i+1){
                    accreditation_string+=`${acc.program_no}`;
                    units_string+=`${acc.units}`;
                }else{
                    accreditation_string+=`${acc.program_no}<br/>`;
                    units_string+=`${acc.units} | `;
                }
            });
        }

        if(product.hasOwnProperty("offering_units")){
            if(product.offering_units=="both"){
                prices_string = `WITH ₱${(new Intl.NumberFormat('en-US', { minimumFractionDigits: 2, maximumFractionDigits: 2}).format(product.prices.with))} | WITH ${product.prices.without ? "₱"+(new Intl.NumberFormat('en-US', { minimumFractionDigits: 2, maximumFractionDigits: 2}).format(product.prices.without)) : "FREE"}`;
            }else if(product.offering_units=="with"){
                prices_string = `₱${(new Intl.NumberFormat('en-US', { minimumFractionDigits: 2, maximumFractionDigits: 2}).format(product.prices.with))}`;
            }else{
                prices_string = `${product.prices.without ? "₱"+(new Intl.NumberFormat('en-US', { minimumFractionDigits: 2, maximumFractionDigits: 2}).format(product.prices.without)) : "FREE"}`;
            }
        }

        if(product.type=="course"){
            if(product.discount){
                var prices_div = `
                <div class="col-xl-12 col-md-12">
                    <div class="kt-widget__head"><a href="javascript:;" class="kt-widget__username">${product.title}</a> <span style="text-align:right;">${accreditation_string}</span></div>
                    <div class="kt-widget__subhead">
                        ${product.provider.name}<br/>
                        ${product.headline}<br/>
                        CPD Units <b>${units_string}</b> &nbsp; &#9679; &nbsp; Price <b>₱${(new Intl.NumberFormat('en-US', { minimumFractionDigits: 2, maximumFractionDigits: 2}).format(product.discounted_price))}</b>(Before: ₱${product.price.toLocaleString({ minimumFractionDigits: 2,})})<br/>
                    </div>
                </div>`;
            }else{
                var prices_div = `
                <div class="col-xl-12 col-md-12">
                    <div class="kt-widget__head"><a href="javascript:;" class="kt-widget__username">${product.title}</a> <span style="text-align:right;">${accreditation_string}</span></div>
                    <div class="kt-widget__subhead">
                        ${product.provider.name}<br/>
                        ${product.headline}<br/>
                        CPD Units <b>${units_string}</b> &nbsp; &#9679; &nbsp; Price <b>₱${product.price.toLocaleString({ minimumFractionDigits: 2,})}</b><br/>
                    </div>
                </div>`;
            }
        }else{
            if(product.discount && product.highest_price > 0){
                var prices_div = `
                <div class="col-xl-12 col-md-12">
                    <div class="kt-widget__head"><a href="javascript:;" class="kt-widget__username">${product.title}</a> <span style="text-align:right;">${accreditation_string}</span></div>
                    <div class="kt-widget__subhead">
                        ${product.provider.name}<br/>
                        ${product.headline}<br/>
                        ${(product.webinar_type=="official" ? `CPD Units <b>${units_string}</b> &nbsp; &#9679; &nbsp; ` : "")}Price <b>₱${(new Intl.NumberFormat('en-US', { minimumFractionDigits: 2, maximumFractionDigits: 2}).format(product.discounted_price))}</b>(Before: ${prices_string})<br/>
                        </div>
                </div>`;
            }else{
                var prices_div = `
                <div class="col-xl-12 col-md-12">
                    <div class="kt-widget__head"><a href="javascript:;" class="kt-widget__username">${product.title}</a> <span style="text-align:right;">${accreditation_string}</span></div>
                    <div class="kt-widget__subhead">
                        ${product.provider.name}<br/>
                        ${product.headline}<br/>
                        ${(product.webinar_type=="official" ? `CPD Units <b>${units_string}</b> &nbsp; &#9679; &nbsp; ` : "")}Price <b>${prices_string}</b><br/>
                    </div>
                </div>`;
            }
        }

        var contents = $(`<div />`).addClass(
            `kt-widget__content row margin-auto`
        ).append(prices_div);

        top.append(image).append(contents);
        profile.append(top);
        body.append(profile);
        row.append(body);
        container.append(row).appendTo(group);
    });
}

function filterCourses(field, status) {
    switch (field) {
        case "quiz":
            if (status == "checked") {
                filter.quiz = filter.quiz == 0 ? 2 : 1;
            } else {
                filter.quiz = filter.quiz == 2 ? 0 : 2;
            }
            break;

        case "no-quiz":
            if (status == "checked") {
                filter.quiz = filter.quiz == 1 ? 2 : 0;
            } else {
                filter.quiz = filter.quiz == 2 ? 1 : 2;
            }
            break;

        case "english":
        case "mixed":
        case "tagalog":
            if (status == "checked") {
                filter.language.push(field);
            } else {
                var indexOf = filter.language.indexOf(field);
                filter.language.splice(indexOf, 1);
            }
            break;

        case "4":
            if (status == "checked") {
                filter.rating = 4;
            } else {
                filter.rating = 0;
            }
            break;

        case "3":
            if (status == "checked") {
                filter.rating = 3;
            } else {
                filter.rating = 0;
            }
            break;

        case "2":
            if (status == "checked") {
                filter.rating = 2;
            } else {
                filter.rating = 0;
            }
            break;

        case "1":
            if (status == "checked") {
                filter.rating = 1;
            } else {
                filter.rating = 0;
            }
            break;
    }
    fetchFilterCourses(pagination, filter);
}
