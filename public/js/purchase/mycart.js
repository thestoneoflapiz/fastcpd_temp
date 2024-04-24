jQuery(document).ready(function(){
    
});

$("[name='apply_voucher_code']").keyup(function(e){
    var value = e.target.value;
    $(this).val(value.toUpperCase());
}).keypress(function (e) {
    if (e.which == 13) {
        addVoucher();
    }
});

$("#signin_submit_mycart").click(function(e) { 
    e.preventDefault();
    var btn = $(this);
    var form = $("#signin_my_cart");

    form.validate({
        rules: {
            email: {
                required: true,
                email: true
            },
            password: {
                required: true
            }
        }
    });

    if (!form.valid()) {
        return;
    }

    btn.addClass(
        "kt-spinner kt-spinner--right kt-spinner--sm kt-spinner--light"
    ).attr("disabled", true);

    form.ajaxSubmit({
        url: "/signin",
        success: function(response, status, xhr, $form) {
            if(response.status=='401'){
                btn.removeClass(
                    "kt-spinner kt-spinner--right kt-spinner--sm kt-spinner--light"
                ).attr("disabled", false);

                showErrorMsg(
                    form,
                    "danger",
                    response.msg,
                );
        
                KTUtil.scrollTop();
            }else{
                window.location.href="/checkout";
            }
        }
    });
});

$("#signup_submit_mycart").click(function(e) { 
    e.preventDefault();
    var btn = $(this);
    var form = $("#signup_my_cart");

    form.validate({
        rules: {
            c_first_name: {
                required: true
            },
            c_middle_name: {
                required: true
            },
            c_last_name: {
                required: true
            },
            cupemail: {
                required: true,
                email: true
            },
            cuppassword: {
                required: true
            },
            crpassword: {
                required: true,
                equalTo: "#cuppassword",
            },
            cprofession: {
                required: true
            },
            
        }
    });

    if (!form.valid()) {
        return;
    }

    btn.addClass(
        "kt-spinner kt-spinner--right kt-spinner--sm kt-spinner--light"
    ).attr("disabled", true);

    form.ajaxSubmit({
        url: "/public/signup",
        method: "post",
        data: {
            first_name: $("[name='c_first_name']").val(),
            middle_name: $("[name='c_middle_name']").val(),
            last_name: $("[name='c_last_name']").val(),
            upemail: $("[name='cupemail']").val(),
            uppassword: $("[name='cuppassword']").val(),
            profession: $("[name='cprofession']").val(),
            _token: $("[name='_token']").val(),
        },
        success: function(response, status, xhr, $form) {

            if(response.status==200){
                toastr.success("Success!", "You've been registered! Instructions have been sent to your email");
                setTimeout(() => {
                    window.location.href="/checkout";
                }, 1000);
            }else{
                setTimeout(function() {
                    btn.removeClass(
                        "kt-spinner kt-spinner--right kt-spinner--sm kt-spinner--light"
                    ).attr("disabled", false);

                    showErrorMsg(
                        form,
                        'success',
                        response.msg,
                    );
                }, 800);

                KTUtil.scrollTop();
            }
        }
    });
});

function openRemoveModal(data){
    var rmodal = $("#remove_modal");
    rmodal.find(".modal-body").empty().html(`Are you sure you want to remove <b>${data.title}</b> from your cart?`);
    rmodal.find(".yes-button").attr("onclick", `removeCartItem('${data.type}', ${data.data_id})`);
    rmodal.modal("show");
}

function removeCartItem(type, data_id){
    $.ajax({
        url: "/cart/remove",
        data: {
            type: type,
            data_id: data_id,
        }, success:function(response){
            $(`#my-cart-item-${type}-${data_id}`).slideUp(200).remove();

            my_cart_ui(response, false);

            toastr.success("Item removed from your cart!");
        }, error: function(){
            toastr.error("Unable to remove item from cart! Please refresh your browser");
        }
    });
}

function addVoucher(){
    var $voucher_code = $("[name='apply_voucher_code']");
    if($voucher_code==""){
        toastr.error("Please enter voucher code first!");
    }else{
        var $apply_voucher_button = $(`#apply-voucher-button`);
        $apply_voucher_button.empty().addClass(`btn-icon kt-spinner kt-spinner--center kt-spinner--sm kt-spinner--light disabled`).prop("disabled", true);

        $.ajax({
            url: "/cart/voucher/add",
            data:{
                voucher_code: $voucher_code.val(),
            }, success: function(response){
                if(response && response.hasOwnProperty("total")){
                    my_cart_ui(response, true);
                }

                $voucher_code.attr("placeholder", $voucher_code.val()).val(null);
                toastr.success("Voucher applied to eligible items!");
                setTimeout(() => {
                    $apply_voucher_button.html("Apply").removeClass(`btn-icon kt-spinner kt-spinner--center kt-spinner--sm kt-spinner--light disabled`).prop("disabled", false);
                }, 1500);
            }, error: function(response){
                var body = response.responseJSON;
                if(body.hasOwnProperty("message")){
                    toastr.error(body.message);
                }else{
                    toastr.error("Something went wrong! Please try again later");
                }
    
                setTimeout(() => {
                    $apply_voucher_button.html("Apply").removeClass(`btn-icon kt-spinner kt-spinner--center kt-spinner--sm kt-spinner--light disabled`).prop("disabled", false);
                }, 1500);
            }
        });
    }
}

/**
 * not yet done
 * 
 */
function removeVoucher($voucher_code){
    var $apply_voucher_button = $(`#apply-voucher-button`);
    $apply_voucher_button.empty().addClass(`btn-icon kt-spinner kt-spinner--center kt-spinner--sm kt-spinner--light disabled`).prop("disabled", true);

    $.ajax({
        url: "/cart/voucher/remove",
        data:{
            voucher_code: $voucher_code,
        }, success: function(response){
            if(response && response.hasOwnProperty("total")){
                my_cart_ui(response, true);
                toastr.success("Voucher already removed!");
            }else{
                toastr.info(`${$voucher_code} is a FastCPD Promo applied for every purchase!`);
            }

            setTimeout(() => {
                $('[name="apply_voucher_code"]').attr("placeholder", "Enter Voucher");
                $apply_voucher_button.html("Apply").removeClass(`btn-icon kt-spinner kt-spinner--center kt-spinner--sm kt-spinner--light disabled`).prop("disabled", false);
            }, 1500);
        }, error: function(response){
            var body = response.responseJSON;
            if(body.hasOwnProperty("message")){
                toastr.error(body.message);
            }else{
                toastr.error("Something went wrong! Please try again later");
            }

            setTimeout(() => {
                $apply_voucher_button.html("Apply").removeClass(`btn-icon kt-spinner kt-spinner--center kt-spinner--sm kt-spinner--light disabled`).prop("disabled", false);
            }, 1500);
        }
    });
}

function my_cart_ui(response, renew_list){            
    var total_div = $(`#my-cart-totals-div`).empty();
    var voucher_div = $(`#applied-voucher-list`);
    var my_cart_div = $(`#my-cart-list`);

    var total = response.total;
    if(total.items > 0){
        if(total.discounted_price){
            total_div.append(`
                <h1 class="kt-font-dark kt-font-boldest">
                    &#8369;${total.total_amount.toLocaleString(undefined, {
                        minimumFractiondigits:2,
                        maximumFractiondigits:2,
                    })}
                    <small class="kt-label-font-color-1" style="text-decoration:line-through;">&#8369;${total.price.toLocaleString(undefined, {maximumFractionDigits:2, minimumFractionDigits: 2})}</small>
                </h1>
                <h5>${total.discount.toLocaleString(undefined, {
                    minimumFractiondigits:1,
                    maximumFractiondigits:2,
                })}% off</h5>
            `);
        }else{
            total_div.append(`
                <h1 class="kt-font-dark kt-font-boldest">
                    &#8369;${total.total_amount.toLocaleString(undefined, {maximumFractionDigits:2, minimumFractionDigits: 2})}
                </h1>
            `);
        }

        var $units_display = ``;
        total.units.forEach((unit) => {
            var $unit_list = ``;

            unit.units.forEach((unit_init) => {
                $unit_list += ` <li>${unit_init}</li>`;
            });

            $units_display += `<p>${unit.title}<ul>${$unit_list}</ul></p>`;
        });

        total_div.append(`
            <div class="kt-space-15"></div>
            <div class="kt-space-15" style="border-bottom:2px dashed #ebedf2"></div>
            <h5>CPD Units you’ll earn:</h5>
            ${$units_display}
        `);

        voucher_div.empty();
        var $voucher_list = ``;
        total.vouchers.forEach(($voucher)=>{
            $voucher_list += `<h5><i class="flaticon2-delete" onclick="removeVoucher('${$voucher}')"></i>&nbsp;&nbsp; <b>${$voucher}</b> is applied</h5> `;
        });

        voucher_div.append($voucher_list);
    }else{
        total_div.append(`<h1 class="kt-font-dark kt-font-boldest">&#8369;0</h1>`);
        voucher_div.empty();
    }
    
    $(`#my-cart-item-count`).empty().append(`<h3 class="kt-portlet__head-title">Total of "${total.items}" ${(total.items > 1 ? 'items' : 'item')}</h3>`);

    if(total.items==0){
        $(`#btn-checkout`).prop("disabled", true).addClass("disabled");
    }

    if(renew_list){
        my_cart_div.slideUp(300).empty();
        total.data.forEach((row) => {
            var my_cart_item = $(`<div class="kt-widget5__item" id="my-cart-item-${row.type}-${row.data_id}" />`);
            my_cart_div.append(my_cart_item);
            my_cart_item = $(`#my-cart-item-${row.type}-${row.data_id}`);

            var accreditation_display = ``;
            if(row.units){
                row.units.forEach(acc => {
                    accreditation_display += `${acc.title} (${acc.program_no} &#9679; ${acc.units}) <br/>`;
                });
            }
            
            my_cart_item.append(`
                <div class="kt-widget5__content">
                    <div class="kt-widget5__pic">
                        <img alt="FastCPD Courses ${row.title}" src="${row.poster}" class="course-poster-img">
                    </div>
                    <div class="kt-widget5__section">
                        <a href="#" class="kt-widget5__title">${row.title}</a>
                        `+(row.units ? `<p class="kt-widget5__desc row">${accreditation_display}</p>` : "")+`
                    </div>
                </div>
            `);
            
            if(row.discounted_price){
                my_cart_item.append(`
                    <div class="kt-widget5__content">
                        <div class="kt-widget5__stats">
                            <span class=" kt-font-info"><b>${row.discount}%—${row.voucher} Applied!</b></span>
                            <span class="kt-widget5__number price"><b>&#8369;${row.discounted_price.toLocaleString(undefined,{
                                minimumFractiondigits: 2,
                                maximumFractiondigits: 2,
                            })}</b></span>
                            <span class="kt-widget5__votes kt-font-bold" style="text-decoration:line-through;">&#8369;${row.price.toLocaleString(undefined, {maximumFractionDigits:2, minimumFractionDigits: 2})}</span>
                        </div>
                        <button data-id="${row.data_id}" data-title="${row.title}" onclick="openRemoveModal({ type: '${row.type}', data_id: ${row.data_id}, title: '${row.title}'})" type="button" class="btn btn-sm btn-icon btn-outline-hover kt-label-font-color-1"><i class="fa fa-trash"></i></button>
                    </div>
                `);
            }else{
                my_cart_item.append(`
                    <div class="kt-widget5__content">
                        <div class="kt-widget5__stats">
                            <span class="kt-widget5__number price"><b>&#8369;${row.price.toLocaleString(undefined, {maximumFractionDigits:2, minimumFractionDigits: 2})}</b></span>
                        </div>
                        <button data-id="${row.data_id}" data-title="${row.title}" onclick="openRemoveModal({ type: '${row.type}', data_id: ${row.data_id}, title: '${row.title}'})" type="button" class="btn btn-sm btn-icon btn-outline-hover kt-label-font-color-1"><i class="fa fa-trash"></i></button>
                    </div>
                `);
            }
        });

        my_cart_div.slideDown(100);
    }
}