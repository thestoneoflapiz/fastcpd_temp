/**
 * Webinar Type
 * 
 */
var webinar_type_page = 0;
var webinar_type_total_page = 0;
var webinar_type_slide = true;

$(document).ready(function(){
    renderWebinarType({profession:profession_id,take: card_to_take}, {page: webinar_type_page, break: card_to_break, take:card_to_take, id:"webinar-type", slide: webinar_type_slide});
    $(`#webinar-type-carousel-control-prev`).click(function () {
        webinar_type_slide = true;
        webinar_type_page -= 1;

        if (webinar_type_total_page > 1) {
            $(`#webinar-type-carousel-control-next`).show();
        }

        if (webinar_type_page == 0) {
            $(`#webinar-type-carousel-control-prev`).hide();
        }

        /**
         * Submit Ajax
         * 
         */
        renderWebinarType({profession:profession_id,take: card_to_take}, {page: webinar_type_page, break: card_to_break, take:card_to_take, id:"webinar-type", slide: webinar_type_slide});
    });

    $(`#webinar-type-carousel-control-next`).click(function(){
        webinar_type_slide = false;
        webinar_type_page += 1;

        $(`#webinar-type-carousel-control-prev`).show();
        
        if(webinar_type_page == (webinar_type_total_page-1)){
            $(`#webinar-type-carousel-control-next`).hide();
        }
        
        /**
         * Submit Ajax
         * 
         */
        renderWebinarType({profession:profession_id,take: card_to_take}, {page: webinar_type_page, break: card_to_break, take:card_to_take, id:"webinar-type", slide: webinar_type_slide});
    });
});

function renderWebinarType(data, carousel) {
    $.ajax({
        url: "/api/category/webinars",
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
            webinar_type_total_page = totalPages;
            renderWebinarWithCarousel(
                { data: data, total: total, totalPages: totalPages },
                carousel
            );

            if(total > 0){
                $("#category_webinars_div").show();
            }else{
                $("#category_webinars_div").hide();
            }
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


function renderWebinarWithCarousel(response, carousel) {
    var data = response.data;

    var inner = $(`#${carousel.id}-carousel-inner`);
    inner.hide("slide", { direction: carousel.slide ? "right" : "left" }, 350).html(``);

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

        var webinar_wrapper = $("<div />");
        var webinar_popover_main = $("<div />");

        $(webinar_wrapper)
            .addClass(
                "col-xl-3 col-lg-3 col-md-6 col-sm-6 col-12 popover__wrapper"
            )
            .attr("id", `${carousel.id}-webinar-card-${row.id}`)
            .appendTo(`div#${carousel.id}-carousel-item > div.row`);

        $(webinar_popover_main)
            .addClass(
                "kt-portlet kt-portlet--fit kt-portlet--head-lg kt-portlet--head-overlay kt-portlet--height-fluid kt-portlet--bordered popover__main"
            )
            .appendTo(`#${carousel.id}-webinar-card-${row.id}`);
        var webinar_popover_content = $("<div />");

        if (carousel.break == control) {
            right = !right;
            control = 0;
        }
        if (right) {
            webinar_popover_content.addClass(
                "kt-portlet kt-portlet--height-fluid- popover__content--right"
            );
        } else {
            webinar_popover_content.addClass(
                "kt-portlet kt-portlet--height-fluid- popover__content--left"
            );
        }
        control++;
        var webinar_popover_main_head = $("<div />")
            .addClass("kt-portlet__head kt-portlet__space-x");
        
        if(row.discount){
            // for discounted webinars
            var prices_div = `<div class="row card-price">
                <div class="col-12">
                    <span class="card-price--discounted">
                        ₱${row.discounted_price.toLocaleString('en-US', {maximumFractionDigits:2, minimumFractionDigits: 2})} 
                    </span>
                    <span class="card-price--original">
                        ₱${row.price.toLocaleString('en-US', {maximumFractionDigits:2, minimumFractionDigits: 2})} 
                    </span>
                </div>
            </div>`;
        }else{
            if(row.offering_units=="without"){
                var prices_div = `<div class="row card-price">
                    <div class="col-12">
                        <span class="card-price--discounted">
                            `+(row.prices.without > 0 ? `₱${(new Intl.NumberFormat('en-US', { minimumFractionDigits: 2, maximumFractionDigits: 2}).format(row.prices.without))}` : `Free`)+`
                        </span>
                    </div>
                </div>`;
            }else{
                var prices_div = `<div class="row card-price">
                    <div class="col-12">
                        <span class="card-price--discounted">
                            `+(row.prices.with > 0 ? `₱${(new Intl.NumberFormat('en-US', { minimumFractionDigits: 2, maximumFractionDigits: 2}).format(row.prices.with))}` : `Free`)+`
                        </span>
                    </div>
                </div>`;
            }
        }

        var webinar_popover_main_body = $("<div />").addClass("kt-portlet__body")
        .html(`<div class="kt-widget27">
            <div class="kt-widget27__visual">
                <img alt="FastCPD Webinars ${row.title}" src="${row.webinar_poster}" class="card-img">
                <div class="popover__overlay"></div>
                <div class="kt-widget27__btn">
                    <a class="btn btn-pill btn-${
                        row.webinar_purchase_before.color
                    } btn-elevate btn-bold">${row.webinar_purchase_before.status}</a>
                </div>
            </div>
            <div class="kt-widget27__container kt-portlet__space-x">
                <div class="row">
                    `+(row.accreditation ? `<div class="col-12">${row.accreditation[0].program_no}</div>` : ``)+`
                    <div class="col-12"><h5>${row.title}</h5></div>
                    <div class="col-12"><span class="webinar-rating-${row.id}" style="display:none"></span></div>
                </div>
                <br/>
                `+(row.accreditation ? `
                    <div class="row card-price">
                    <div class="col-12">
                        <span class="card-price--discounted">
                            CPD Units ${row.accreditation[0].units}
                        </span>
                    </div>
                </div>` : ``)+`
                ${prices_div}
            </div>
        </div>`);

        var webinar_popover_content_head = $("<div />")
        .addClass("kt-portlet__head kt-portlet__head--noborder")
        .html(row.webinar_status == "Ended" ? ``: `
            <div class="kt-portlet__head-label kt-label--adjust">
                <span>Get <b>before</b> ${row.webinar_purchase_before.string_}</span>
            </div>`
        );

        var webinar_popover_content_body = $("<div />").addClass(
            "kt-portlet__body"
        ).html(`
        <div class="kt-widget kt-widget--user-profile-2">
            <div class="kt-widget__head">
                <div class="kt-widget__info">
                    <a href="javascript:;" class="kt-widget__username">${row.title}</a><br/>
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
                                    row.total_quiz
                                }</span>
                            </div> 
                        </div>
                        <div class="kt-widget__stats">
                            <div class="kt-widget__icon minimize">
                                <i class="flaticon-speech-bubble"></i>
                            </div>
                            <div class="kt-widget__details">
                                <span class="kt-widget__title">Articles</span>
                                <span class="kt-widget__value">${
                                    row.total_article
                                }</span>
                            </div>
                        </div>
                        `+(row.accreditation ? `
                        <div class="kt-widget__stats">
                            <div class="kt-widget__icon minimize">
                                <i class="flaticon-medal"></i>
                            </div>
                            <div class="kt-widget__details">
                                <span class="kt-widget__title">UNITS</span>
                                <span class="kt-widget__value">${row.accreditation[0].units}</span>
                            </div>
                        </div>
                        ` : ``)+`
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
                <button type="button" class="btn btn-success" onclick="window.location='/webinar/${row.url}'">Go to Page</button>
            </div>
        </div>`);

        webinar_popover_main
            .append(webinar_popover_main_head)
            .append(webinar_popover_main_body)
            .click(function () {
                window.open(`/webinar/${row.url}`);
            }).css({
                cursor: "pointer"
            });
        webinar_popover_content
            .append(webinar_popover_content_head)
            .append(webinar_popover_content_body);
        webinar_wrapper
            .append(webinar_popover_main)
            .append(webinar_popover_content);

        if(row.fast_cpd_status=="live" && row.avg_webinar_rating > 0){
            $(`.webinar-rating-${row.id}`).starRating({
                totalStars: 5,
                initialRating: row.avg_webinar_rating,
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