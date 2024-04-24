var course_id_input = $("[name='course_id']");
var avg_course_rating = $("[name='avg_course_rating']");
jQuery(document).ready(function () {
    //sticky banner
    $(window).scroll(function () {
        var height = $("#default-screen-banner").innerHeight();
        if ($(this).scrollTop() > height) {
            //if scroll reached end of banner, sticky flag displays

            $("#header").addClass("sticky_header");
            $("#header").show();
            $("#default-screen-banner").hide();
            $("#default-screen-flag").hide();

            if ($(window).width() <= 1020) {
                $("#small-screen-flag").show();
                $("#sticky").hide();
            } else {
                $("#small-screen-flag").hide();
                $("#sticky").show();
            }
        } else if (
            $(this).scrollTop() == 0 &&
            $("#header").hasClass("sticky_header")
        ) {
            //if scrolled up, the black banner will be displayed again
            $("#header").removeClass("sticky_header");
            $("#header").hide();
            $("#default-screen-banner").show();
            $("#sticky").hide();
            $("#default-screen-flag").show(); //CTA panel will be seen in the black banner
        }
    });

    $(".avg-course-rating-page").starRating({
        totalStars: 5,
        initialRating: avg_course_rating.val(),
        readOnly: true,
        starShape: "rounded",
        starSize: 15, 
    }).show();
});

//Expand All or Collapse All
$(".expand-collapse").click(function () {
    if ($(".expand-collapse").html() == "Expand All") {
        $(".expand-collapse").html("Collapse All");
        $(".card-title").addClass("collapsed");
        $(".collapse").addClass("show");
    } else {
        $(".expand-collapse").html("Expand All");
        $(".card-title").removeClass("collapsed");
        $(".collapse").removeClass("show");
    }
});

//Remove maximum height assigned
$(".see-requirements").click(function () {
    $(".footer-requirements").hide();
    $("#section-requirements").removeClass("limit-requirements");
});

$(".see-description").click(function () {
    $(".footer-description").hide();
    $("#section-description").removeClass("limit-description");
});

$(".see-target").click(function () {
    $(".footer-target").hide();
    $("#section-target").removeClass("limit-target");
});

$(".see-sections").click(function () {
    $(".footer").hide();
    $("#section-body").removeClass("limit-size");
});

$(".see-reviews").click(function () {
    $(".footer-reviews").hide();
    $("#section-reviews").removeClass("limit-reviews");
});

$(".see-objectives").click(function () {
    $(".footer-objectives").hide();
    $("#section-objectives").removeClass("limit-objectives");
});

//Check if data reached the maximum height or not
//Requirement
if ($("#section-requirements").height() < 200) {
    $(".footer-requirements").hide();
    $("#section-requirements").removeClass("limit-requirements");
} else {
    $(".footer-requirements").show();
    $("#section-requirements").addClass("limit-requirements");
}

//Description
if ($("#section-description").height() < 500) {
    $(".footer-description").hide();
    $("#section-description").removeClass("limit-description");
} else {
    $(".footer-description").show();
    $("#section-description").addClass("limit-description");
}

//Target student
if ($("#section-target").height() < 200) {
    $(".footer-target").hide();
    $("#section-target").removeClass("limit-target");
} else {
    $(".footer-target").show();
    $("#section-target").addClass("limit-target");
}

//Section
if ($("#section-body").height() < 510) {
    $(".footer").hide();
    $("#section-body").removeClass("limit-size");
} else {
    $(".footer").show();
    $("#section-body").addClass("limit-size");
}

//Reviews
if ($("#section-reviews").height() < 530) {
    $(".footer-reviews").hide();
    $("#section-reviews").removeClass("limit-reviews");
} else {
    $(".footer-reviews").show();
    $("#section-reviews").addClass("limit-reviews");
}

//Objectives
if ($("#section-objectives").height() < 300) {
    $(".footer-objectives").hide();
    $("#section-objectives").removeClass("limit-objectives");
} else {
    $(".footer-objectives").show();
    $("#section-objectives").addClass("limit-objectives");
}

/**
 * 
 * Apply voucher
 * 
 */
$("[name='apply_voucher_code1'], [name='apply_voucher_code2'], [name='apply_voucher_code3']").keyup(function(e){
    var value = e.target.value;
    $(this).val(value.toUpperCase());
}).keypress(function (e) {
    if (e.which == 13) {
        var value = e.target.value;
        addVoucher(course_id_input.val(), value, 1);
    }
});

function addVoucher($course_id, $voucher_code){
    if($voucher_code==""){
        toastr.error("Please enter voucher code first!");
    }else{
        var $apply_voucher_button = $(`#apply-voucher-button1, #apply-voucher-button2, #apply-voucher-button3`);
        $apply_voucher_button.empty().addClass(`btn-icon kt-spinner kt-spinner--center kt-spinner--sm kt-spinner--light disabled`).prop("disabled", true);

        $.ajax({
            url: "/course/voucher/add",
            data:{
                voucher_code: $voucher_code,
                course_id: $course_id,
            }, success: function(response){
                $("[name='apply_voucher_code1'], [name='apply_voucher_code2'], [name='apply_voucher_code3']").attr("placeholder", $voucher_code).val(null);
                toastr.success("Voucher applied to eligible items!");

                setTimeout(() => {
                    $apply_voucher_button.html("Apply").removeClass(`btn-icon kt-spinner kt-spinner--center kt-spinner--sm kt-spinner--light disabled`).prop("disabled", false);

                    if(response && (response.hasOwnProperty("update") && response.update)){
                        if(response.hasOwnProperty("reload") && response.reload){
                            location.reload();
                        }

                        if(response.hasOwnProperty("discount")){
                            if(response.discount.discount > 0){
                                var data = response.discount;
                                var display_total = `<p>
                                    <span style="font-weight:bold;font-size:32px;">&#8369;${data.discounted_price.toLocaleString(undefined, {
                                        minimumFractiondigits:2,
                                        maximumFractiondigits:2,
                                    })}</span>&nbsp;&nbsp;
                                    <span style="text-decoration: line-through;font-size:18px">&#8369;${data.price.toLocaleString(undefined, {maximumFractionDigits:2, minimumFractionDigits: 2})}</span><br/>
                                    <span style="font-size:18px">${data.discount}% off</span>
                                </p>`;
                                $(`#applied-voucher-list1, #applied-voucher-list2, #applied-voucher-list3`).empty().append(`
                                    <h5><i class="flaticon2-delete" onclick="removeVoucher(${$course_id}, '${response.discount.voucher_code}')"></i>&nbsp;&nbsp; <b>${response.discount.voucher_code}</b> is applied</h5> 
                                `);
                            }else{
                                var display_total = `<p><span style="font-weight:bold;font-size:32px;">&#8369;${data.price.toLocaleString(undefined, {maximumFractionDigits:2, minimumFractionDigits: 2})}</span>&nbsp;&nbsp;</p>`;
                            }
        
                            $(`#course-total-div3, #course-total-div2, #course-total-div1`).html(display_total);
                        }
                    }else{
                        $(`#applied-voucher-list1, #applied-voucher-list2, #applied-voucher-list3`).empty().append(`
                            <h5><i class="flaticon2-delete" onclick="removeVoucher(${$course_id}, '${response.discount.voucher_code}')"></i>&nbsp;&nbsp; <b>${response.discount.voucher_code}</b> is applied</h5> 
                        `);
                    }
                }, 1000);
            }, error: function(response){
                var body = response.responseJSON;
                if(body.hasOwnProperty("message")){
                    toastr.error(body.message);
                }else{
                    toastr.error("Something went wrong! Please try again later");
                }
    
                setTimeout(() => {
                    $apply_voucher_button.html("Apply").removeClass(`btn-icon kt-spinner kt-spinner--center kt-spinner--sm kt-spinner--light disabled`).prop("disabled", false);
                }, 1000);
            }
        });
    }
}

function removeVoucher($course_id, $voucher_code){
    var $apply_voucher_button = $(`#apply-voucher-button1, #apply-voucher-button2, #apply-voucher-button3`);
    $apply_voucher_button.empty().addClass(`btn-icon kt-spinner kt-spinner--center kt-spinner--sm kt-spinner--light disabled`).prop("disabled", true);

    $.ajax({
        url: "/course/voucher/remove",
        data:{
            voucher_code: $voucher_code,
            course_id: $course_id,
        }, success: function(response){
            if(response && response.hasOwnProperty("update")){
                if(response.update){
                    if(response.discount.discount > 0){
                        var data = response.discount;
                        var display_total = `<p>
                            <span style="font-weight:bold;font-size:32px;">&#8369;${data.discounted_price.toLocaleString(undefined, {
                                minimumFractiondigits:2,
                                maximumFractiondigits:2,
                            })}</span>&nbsp;&nbsp;
                            <span style="text-decoration: line-through;font-size:18px">&#8369;${data.price.toLocaleString(undefined, {maximumFractionDigits:2, minimumFractionDigits: 2})}</span><br/>
                            <span style="font-size:18px">${data.discount}% off</span>
                        </p>`;
                    }else{
                        var display_total = `<p><span style="font-weight:bold;font-size:32px;">&#8369;${data.price.toLocaleString(undefined, {maximumFractionDigits:2, minimumFractionDigits: 2})}</span>&nbsp;&nbsp;</p>`;
                    }

                    $(`#course-total-div3, #course-total-div2, #course-total-div1`).html(display_total);
                }
            }

            setTimeout(() => {
                $apply_voucher_button.html("Apply").removeClass(`btn-icon kt-spinner kt-spinner--center kt-spinner--sm kt-spinner--light disabled`).prop("disabled", false);
                $(`#applied-voucher-list1, #applied-voucher-list2, #applied-voucher-list3`).empty();
            }, 1000);
        }, error: function(response){
            var body = response.responseJSON;
            if(body.hasOwnProperty("message")){
                toastr.error(body.message);
            }else{
                toastr.error("Something went wrong! Please try again later");
            }

            setTimeout(() => {
                $apply_voucher_button.html("Apply").removeClass(`btn-icon kt-spinner kt-spinner--center kt-spinner--sm kt-spinner--light disabled`).prop("disabled", false);
            }, 1000);  
        }
    });
}

function buyNow($course_id) {
    $(`#buy-now1, #buy-now2, #buy-now3`).addClass(`kt-spinner kt-spinner--right kt-spinner--sm kt-spinner--dark disabled`).prop("disabled", true);
    $.ajax({
        url: "/course/buy_now",
        data: {
            course_id: $course_id,
        },
        success: function(response){
            window.location="/cart";
        },
        error: function(response){
            var body = response.responseJSON;
            toastr.error(body.hasOwnProperty("message") ? body.message : "Sorry! We're unable to add to cart this course!");
        },
        complete: function (){
            $(`#buy-now1, #buy-now2, #buy-now3`).removeClass(`kt-spinner kt-spinner--right kt-spinner--sm kt-spinner--dark disabled`);
        }
    });

}