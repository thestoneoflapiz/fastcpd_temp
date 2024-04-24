var beforeValue = null;
var currentRequest = null;
jQuery(document).ready(function(){
    $input = $("[name='search_code']");
    $progress = $("#progress-bar");

    $input.on('keypress',function(e) {
        if(e.which == 13) {
            // enter key is pressed
            if($input.val()=="" || $input.val()==" "){
                toastr.error("Please enter a URL or code");
                return;
            }

            if(beforeValue!=null && beforeValue!=$input.val() && currentRequest){
                currentRequest.abort();
                beforeValue=$input.val();
            }else if(beforeValue==$input.val()){
                return;
            }

            beforeValue = $input.val();

            currentRequest = $.ajax({
                method: "GET",
                url: `/verify/search?data=${$input.val()}`,
                beforeSend: function () {
                    $progress.find(".progress-bar").attr("aria-valuenow", 10).css("width", "10%");
                    $progress.removeClass("kt-hidden");
                },
                complete: function () {
                    $progress.find(".progress-bar").attr("aria-valuenow", 100).css("width", "100%");
                    setTimeout(() => {
                        $progress.addClass("kt-hidden");
                    }, 500);
                },
                success: function (response) {
                    if(response.hasOwnProperty("redirect")){
                        if(response.redirect){
                            window.location=response.link;
                        }
                    }
                },
                error: function(response){
                    var body = response.responseJSON;
                    if(body.hasOwnProperty("message")){
                        toastr.error(body.message);
                        return;
                    }

                    toastr.error("Something went wrong! Please try again later.");
                }
            });

        }
    });
});