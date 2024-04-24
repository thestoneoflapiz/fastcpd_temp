var assessment = null;

jQuery(document).ready(function () {
    get_grade_requirements();
});
 
function get_grade_requirements(){

    $.ajax({
        url: "/webinar/live/api/grade_requirements",
        data: {
            webinar_id: $(`input[name="webinar_id"]`).val(),
        },
        success:function(response){
            assessment = response.assessment;
            if(assessment.assessment==="true"){
                check_overall_quiz_grade();
            }else{
                live_completed_quiz({
                    required: false,
                });
            }
        },
        error:function(){

        },
    });
}