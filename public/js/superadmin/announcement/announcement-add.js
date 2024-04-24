jQuery(document).ready(function () {
    $("input[name='start_date']").datetimepicker();
    $("input[name='end_date']").datetimepicker();

    $("input[name='voucher_code']").keyup(function(e) {
        var value = e.target.value;
        $(this).val(value.toUpperCase());
    });

    $(".selection").change(function (e) {
        var value = e.target.value;
        if (value == "empty" || value == "!empty") {
            $(this).next("input").attr("disabled", "disabled");
            $(this).next("input").val("");
        } else {
            $(this).next("input").removeAttr("disabled", "disabled");
        }
    });
    FormDesign.init();
});

var FormDesign = function () {
    var validator;

    $.validator.addMethod("requiredIf", function( currentInput, element , overlook) {
        var overlooking_e = $(`input[name='${overlook}']`).val();
        if(overlook == "end_date" && overlooking_e != ""){
            var dateGiven = new Date(currentInput);
            var dateMinimum = new Date();
            
            return dateGiven >= dateMinimum;
        }else if(overlook == "start_date" && overlooking_e != ""){
            var dateGiven = new Date(currentInput);
            var dateMinimum = new Date(overlooking_e);
            return dateGiven >= dateMinimum;
        }else{
            return true;
        }


        return false;
    }, function(currentInput, element , overlook){
        var overlooking_e = $(`input[name='${currentInput}']`).val();
        if(currentInput == "end_date" && overlooking_e != ""){
            return "Please choose a date starting from today";
        }else if(currentInput == "start_date" && overlooking_e != ""){
            return "Please choose a date greater than the starting date" ;
        }

    });

    var promotion = function () {
        validator = $( "#announce_form" ).validate({
            // define validation rules
            rules: {
                target_audience: {
                    required: true,
                },
                banner_state: {
                    required: true,
                },
                title: {
                    required: true,
                    minlength: 10,
                },
                message: {
                    required: true,
                    maxlength: 100,
                    minlength: 10,
                },
                start_date: {
                    requiredIf: "end_date",
                },
                end_date: {
                    requiredIf: "start_date",
                },
            },


            
            invalidHandler: function(event, validator) {             
                var alert = $('#promotion_form_alert');
                alert.removeClass('kt-hidden').show();
                KTUtil.scrollTop();
            },
            
            submitHandler: function (form) {
                var $submit = $("#save_announcement");
                $submit.addClass('kt-spinner kt-spinner--right kt-spinner--md kt-spinner--light disabled').prop("disabled", true);
                $.ajax({
                    url: "/superadmin/announcement/save",
                    method: "POST",
                    data: {
                        target_audience: $("select[name='target_audience']").val(),
                        banner_state: $("select[name='banner_state']").val(),
                        title: $("input[name='title']").val(),
                        message: $("#message").val(),
                        start_date: $("input[name='start_date']").val(),
                        end_date: $("input[name='end_date']").val(),
                        _token: $("[name='_token']").val(),
                    }, success: function(response) {
                        toastr.success("Successfully saved an Announcement!");
                        $submit.removeClass('kt-spinner kt-spinner--right kt-spinner--md kt-spinner--light');

                        setTimeout(() => {
                            window.location="/superadmin/announcements";
                        }, 1500);
                        
                    }, error: function (){
                        toastr.error("Error", "Something went wrong! Please refresh your browser");
                        $submit.removeClass('kt-spinner kt-spinner--right kt-spinner--md kt-spinner--light disabled').prop("disabled", false);
                    }
                });
            }
        });       
    }

    return {
        // public functions
        init: function() {
            promotion(); 
        }
    };
}();