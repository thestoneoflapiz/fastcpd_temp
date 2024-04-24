jQuery(document).ready(function () {
    $(`.get_cpd_certificate_button`).click(function(){
        if(assessment.assessment==="true"){
            if(has_completed_quiz && has_completed_rating && has_completed_webinar){
                toastr.info(`Congratulations! Your CPD certificate will generate!!!`);
               
                setTimeout(function(){
                    get_certificate();
                },2500);
            }else{
                if(!has_quiz && has_completed_rating && has_completed_webinar){
                    toastr.info(`Congratulations! Your CPD certificate will generate!!!`);
                    setTimeout(function(){
                        get_certificate();
                    }, 2500);
                }else{
                    toastr.error(`Sorry! You haven't completed the requirements of this webinar yet. Kindly check again, or refresh your browser.`);
                }
            }
        }else{
            if(has_completed_rating && has_completed_webinar){
                toastr.info(`Congratulations! Your CPD certificate will generate!!!`);
                setTimeout(function(){
                    get_certificate();
                }, 2500);
            }else{
                toastr.error(`Sorry! You haven't completed the requirements of this webinar yet. Kindly check again, or refresh your browser.`);
            }
        }
        
    });
});

function live_completed_webinar(params) {
    var span = $("#span_completed_webinar");
    if(current_progress==total_progress && total_progress!=0){
        span.find("i").addClass("kt-font-success");
    }else{
        span.find("i").removeClass("kt-font-success");
    }
}

function live_completed_quiz(params) {
    if(has_quiz){
        if(params.required==true){
            var span = $("#span_completed_quiz"); 
            if(params.hasOwnProperty("percentage")){
                var percentage = params.percentage * 100;
                span.html(`<i class="fa fa-check-circle ${params.status=="passed"?"kt-font-success":""}"></i>&nbsp; A passing grade of ${percentage}% in the quizzes: <b>${params.total_correct}/${params.total_items}—${(new Intl.NumberFormat('en-US', { minimumFractionDigits: 2, maximumFractionDigits: 2}).format(params.correct_percentage))}%</b></span>`).show();
                span.next().show();
            }
    
            return;
        }
    }
    

    $("#span_completed_quiz").next().hide();
    $("#span_completed_quiz").hide();
    
}

function live_completed_rating(params) {
    var span = $("#span_completed_rating");
    if(has_completed_rating){
        span.find("i").addClass("kt-font-success");
    }else{
        span.find("i").removeClass("kt-font-success");
    }
}

function get_certificate(){
    var route = "/data/pdf/certificate";
    var hash = $("#certificate_hash").val();
    window.open(route+"?certificate_code="+hash,"_blank");
}
