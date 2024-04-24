<?php $accreditor = _current_webinar()->submit_accreditation_evaluation ? json_decode(_current_webinar()->submit_accreditation_evaluation) : null; ?>

@extends('template.webinar.master_creation')

@section('styles')
<style>

table, th, td {
  border: 1px solid grey;
  border-collapse: collapse;
  color:#646c9a;
}

th {
    background-color: #c7ffff;
}

.center_div{margin: auto;width: 90%;}
.center{text-align: center;}
.hidden{display:none;}

.accordion .card .card-header .card-title.collapsed > i{color:#5d78ff;}
.accordion.accordion-toggle-arrow .card .card-header .card-title{color:#343a40;}
.fastcpd-background{background-size:cover;height:320px;width:100%;border:1px solid #F1F2F7;border-radius:5px;-webkit-box-shadow: 4px 4px 10px -9px rgba(0,0,0,0.49);-moz-box-shadow: 4px 4px 10px -9px rgba(0,0,0,0.49);box-shadow: 4px 4px 10px -9px rgba(0,0,0,0.49);}
.small-text{text-size-adjust: 10px}
</style>
@endsection

@section('content')
<div class="kt-portlet kt-portlet--mobile">
    <div class="kt-portlet__head">
        <div class="kt-portlet__head-label">
            <h3 class="kt-portlet__head-title">
                PRC Accreditation
            </h3>
        </div>
    </div>
    <div class="kt-portlet__body" id="disabled-form" style="display:none">
        <div class="kt-portlet kt-portlet--mobile">
            <div class="kt-portlet__body">
                <h5><i class="fa fa-clipboard-list" style="color:#2A7DE9;"></i> &nbsp;This form is currently disabled; It is required to complete the following:</h5>
                <ul id="list-of-errors">
                    <li>Webinar Details</li>
                    <li>Attract Enrollments</li>
                    <li>Instructors</li>
                    <li>Webinar & Content</li>
                    <li>Handouts</li>
                    <li>Grading & Assessment</li>
                    <li>Instructor Resumes</li>
                </ul>
            </div>
        </div>
    </div>
    <div class="kt-portlet__body">
        <!--begin::Accordion-->
        <div class="accordion  accordion-toggle-arrow">
            <div class="card">
                <div class="card-header" id="price_target_studemt_parents">
                    <div class="card-title collapsed" data-toggle="collapse" data-target="#price_target_students" aria-expanded="false" aria-controls="price_target_students">
                        @if($with_price > 0 && _current_webinar()->target_number_students) <span><i class="fa fa-check-circle kt-font-success" style="font-size:1.5rem;"></i> </span> &nbsp; &nbsp; @endif<i class="fa fa-crosshairs circle-icon"></i> Price and Target Students
                    </div>
                </div>
                <div id="price_target_students" class="collapse" aria-labelledby="headingThree1" data-parent="#price_target_studemt_parents">
                    <div class="card-body">
                        <form class="kt-form kt-form--label-left" id="price_target_students_form" autocomplete="off">
                            {{ csrf_field() }}
                            <div class="row">
                                <div class="col-xl-6 col-lg-6 col-md-6 col-sm-9">
                                    <div class="form-group" id="price_div">
                                        <label class="bold" style="font-weight:bold;">Price</label>
                                        <input class="form-control" id="price" type="text" value="{{ $with_price ?? '' }}" name="price">
                                    </div>
                                </div>
                                <div class="col-xl-6 col-lg-6 col-md-6 col-sm-9">
                                    <div class="form-group" id="number_students_div">
                                        <label class="bold" style="font-weight:bold;">Target number of Students</label>
                                        <input class="form-control" id="number_students" type="text" value="{{ _current_webinar()->target_number_students ?? '' }}" name="number_students">
                                    </div>

                                    <div class="row" style="float:right">
                                        <div class="col-lg-12 ml-lg-xl-auto">
                                            <button class="btn btn-success">Submit</button>
                                            <button type="reset" class="btn btn-secondary">Clear</button>
                                            <div class="kt-space-20"></div>
                                            <div class="kt-space-20"></div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            <div class="card">
                <div class="card-header" id="expense_breakdown_parents">
                    <div class="card-title collapsed" data-toggle="collapse" data-target="#expense_breakdown" aria-expanded="false" aria-controls="expense_breakdown">
                        @if(_current_webinar()->expenses_breakdown) <span><i class="fa fa-check-circle kt-font-success" style="font-size:1.5rem;"></i> </span> &nbsp; &nbsp; @endif<i class="fa fa-scroll circle-icon"></i> Expenses Breakdown
                    </div>
                </div>
                <div id="expense_breakdown" class="collapse" aria-labelledby="headingThree1" data-parent="#expense_breakdown_parents">
                    <div class="card-body">
                        <form class="kt-form kt-form--label-left" id="expendses_breakdown_form">
                            <?php $expenses = json_decode(_current_webinar()->expenses_breakdown);?>
                            <!-- //////////////////////////// start expendses_breakdown ///////////////////////////////////e -->
                            <table class="table table-bordered" width="100%">
                                <tr>
                                    <th width="40%">
                                        EXPENSE ITEMS
                                    </th>
                                    <th width="40%">
                                        DETAILS OF THE EXPENSES
                                    </th>
                                    <th width="20%">
                                        AMOUNT
                                    </th>
                                </tr>
                                <tr>
                                    <td>
                                        1. Venue
                                    </td>
                                    <td><textarea class="form-control" type="text" name="venue_details" id="venue_details">{{ $expenses->venue_details ?? '' }}</textarea> </td>
                                    <td><input class="form-control" type="text" name="venue_cost" id="venue_cost" onchange="getTotal()" value="{{ $expenses->venue_cost ?? '0' }}"> </td>
                                </tr>
                                <tr>
                                    <td>
                                        2. Meals
                                    </td>
                                    <td><textarea class="form-control" type="text" name="meal_details" id="meal_details" >{{ $expenses->meal_details ?? '' }}</textarea> </td>
                                    <td><input class="form-control" type="text" name="meal_cost" id="meal_cost" onchange="getTotal()" value="{{ $expenses->meal_cost ?? '0' }}"> </td>
                                </tr>
                                <tr>
                                    <td>
                                        3. Honoraria
                                        <ol type="A">
                                            <li>Speaker (or panel of experts)</li>
                                            <li>Facilitator</li>
                                            <li>Moderator/Master of Ceremony</li>
                                            <li>Secretariat</li>
                                        </ol>
                                    </td>
                                    <td><textarea class="form-control" type="text" name="honoraria_details" id="honoraria_details" >{{ $expenses->honoraria_details ?? '' }}</textarea> </td>
                                    <td><input class="form-control" type="text" name="honoraria_cost" id="honoraria_cost" onchange="getTotal()" value="{{ $expenses->honoraria_cost ?? '0' }}"> </td>
                                </tr>
                                <tr>
                                    <td>
                                        4. Itemized Materials <div class="small-text">( e.g handbook/handouts, Certificates, pencil and papers, seminar kits, ink for printers)</div>
                                    </td>
                                    <td><textarea class="form-control" type="text" name="item_materials_details" id="item_materials_details" >{{ $expenses->item_materials_details ?? '' }}</textarea> </td>
                                    <td><input class="form-control" type="text" name="item_materials_cost" id="item_materials_cost" onchange="getTotal()" value="{{ $expenses->item_materials_cost ?? '0' }}"> </td>
                                </tr>
                                <tr>
                                    <td>
                                        5. Advertising expenses
                                    </td>
                                    <td><textarea class="form-control" type="text" name="advertising_expenses_details" id="advertising_expenses_details">{{ $expenses->advertising_expenses_details ?? '' }}</textarea> </td>
                                    <td><input class="form-control" type="text" name="advertising_expenses_cost" id="advertising_expenses_cost" onchange="getTotal()" value="{{ $expenses->advertising_expenses_cost ?? '0' }}"> </td>
                                </tr>
                                <tr>
                                    <td>
                                        6. Transportation 
                                        <ol type="A">
                                            <li>Speaker/s</li>
                                            <li>Staff</li>
                                        </ol>
                                    </td>
                                    <td><textarea class="form-control" type="text" name="transportation_details" id="transportation_details" >{{ $expenses->transportation_details ?? '' }}</textarea> </td>
                                    <td><input class="form-control" type="text" name="transportation_cost" id="transportation_cost" onchange="getTotal()" value="{{ $expenses->transportation_cost ?? '0' }}"> </td>
                                </tr>
                                <tr>
                                    <td>
                                        7. Accommodation <br/><div class="small-text">(for the speaker)</div>
                                    </td>
                                    <td><textarea class="form-control" type="text" name="accommodation_details" id="accommodation_details">{{ $expenses->accommodation_details ?? '' }}</textarea> </td>
                                    <td><input class="form-control" type="text" name="accommodation_cost" id="accommodation_cost" onchange="getTotal()" value="{{ $expenses->accommodation_cost ?? '0' }}"> </td>
                                </tr>
                                <tr>
                                    <td>
                                        8. Processing Fee (Accreditation Fee)
                                    </td>
                                    <td><textarea class="form-control" type="text" name="process_fee_details" id="process_fee_details">{{ $expenses->process_fee_details ?? '' }}</textarea> </td>
                                    <td><input class="form-control" type="text" name="process_fee_cost" id="process_fee_cost" onchange="getTotal()" value="{{ $expenses->process_fee_cost ?? '0' }}"> </td>
                                </tr>
                                <tr>
                                    <td>
                                        9. Supplies and Equipment
                                    </td>
                                    <td><textarea class="form-control" type="text" name="supplies_equip_details" id="supplies_equip_details" value="">{{ $expenses->supplies_equip_details ?? '' }}</textarea> </td>
                                    <td><input class="form-control" type="text" name="supplies_equip_cost" id="supplies_equip_cost" onchange="getTotal()" value="{{ $expenses->supplies_equip_cost ?? '0' }}"> </td>
                                </tr>
                                <tr>
                                    <td>
                                        10. Laboratory
                                    </td>
                                    <td><textarea class="form-control" type="text" name="laboratory_details" id="laboratory_details">{{ $expenses->laboratory_details ?? '' }}</textarea> </td>
                                    <td><input class="form-control" type="text" name="laboratory_cost" id="laboratory_cost" onchange="getTotal()" value="{{ $expenses->laboratory_cost ?? '0' }}"> </td>
                                </tr>
                                <tr>
                                    <td>
                                       11. VAT (12%)
                                    </td>
                                    <td><textarea class="form-control" type="text" name="vat_details" id="vat_details">{{ $expenses->vat_details ?? '' }}</textarea> </td>
                                    <td><input class="form-control" type="text" name="vat_cost" id="vat_cost" onchange="getTotal()" value="{{ $expenses->vat_cost ?? '0' }}"> </td>
                                </tr>
                                <tr>
                                    <td>
                                        12. Entrance fees <div class="small-text">(for museum, heritage/historical sites, cultural centers, exhibits, geographical sites, other sites etc.)</div>
                                    </td>
                                    <td><textarea class="form-control" type="text" name="entrance_fee_details" id="entrance_fee_details">{{ $expenses->entrance_fee_details ?? '' }}</textarea> </td>
                                    <td><input class="form-control" type="text" name="entrance_fee_cost" id="entrance_fee_cost" onchange="getTotal()" value="{{ $expenses->entrance_fee_cost ?? '0' }}"> </td>
                                </tr>
                                <tr>
                                    <td>
                                        13. Tour guide/Facilitator's fee
                                    </td>
                                    <td><textarea  class="form-control" type="text" name="facilitator_fee_details" id="facilitator_fee_details" >{{ $expenses->facilitator_fee_details ?? '' }}</textarea> </td>
                                    <td><input class="form-control" type="text" name="facilitator_fee_cost" id="facilitator_fee_cost" onchange="getTotal()" value="{{ $expenses->facilitator_fee_cost ?? '0' }}"> </td>
                                </tr>
                                <tr>
                                    <td>
                                        14. Miscellaneous (Please specify)
                                    </td>
                                    <td><textarea class="form-control" type="text" name="misc_details" id="misc_details" >{{ $expenses->misc_details ?? '' }}</textarea></td>
                                    <td><input class="form-control" type="text" name="misc_cost" id="misc_cost" onchange="getTotal()" value="{{ $expenses->misc_cost ?? '0' }}"> </td>
                                </tr>
                                <tr>
                                    <td colspan=2 style="text-align:right">
                                        TOTAL : 
                                    </td>
                                    <td colspan=2 >
                                         <div id="total_breakdown"></div>
                                         <input class="form-control" type="hidden" name="total_breakdown_val" id="total_breakdown_val" value="{{ $expenses->total_breakdown ?? '0' }}">
                                    </td>
                                </tr>
                            </table>
                            <div class="row" style="float:right">
                                <div class="col-lg-12 ml-lg-xl-auto">
                                    <button class="btn btn-success" >Submit</button>
                                    <!-- <button type="reset" class="btn btn-secondary">Clear</button> -->
                                    <div class="kt-space-20"></div>
                                    <div class="kt-space-20"></div>
                                </div>
                            </div>

                            <!-- //////////////////////////// end expendses_breakdown ///////////////////////////////////e -->
                        </form>
                    </div>
                </div>
            </div>
            <div class="card">
                <div class="card-header" id="instructional_design_parent">
                    <div class="card-title collapsed" data-toggle="collapse" data-target="#instructional_design" aria-expanded="false" aria-controls="instructional_design">
                        @if(_current_webinar()->submit_accreditation_evaluation) <span><i class="fa fa-check-circle kt-font-success" style="font-size:1.5rem;"></i> </span> &nbsp; &nbsp; @endif<i class="fa fa-book-open circle-icon"></i> Instructional Design
                    </div>
                </div>
                <div id="instructional_design" class="collapse" aria-labelledby="headingThree1" data-parent="#instructional_design_parent">
                    <div class="card-body">
                        <form class="kt-form kt-form--label-left" id="instructional_form">
                            <!-- //////////////////////////// start instructional ///////////////////////////////////e -->
                    
                            <div class="row center center_div">
                                <h5 id="close_label" style="width:100%;">INSTRUCTIONAL DESIGN <br/>
                                <small >Please edit the industrial design form below before downloading the document</small></h5>  
                            </div> 
                            <table class="table table-bordered" width="100%">
                                <tr>
                                    <th width="25%">
                                        Specific Objectives of the Program 
                                    </th>
                                    <th width="15%">
                                        Learning Outcomes per Topic  
                                    </th>
                                    <th width="15%">
                                        Topics To Be Discussed / Resource Person
                                    </th>
                                    <th width="10%">
                                        Time Allotment For Each Topic
                                    </th>
                                    <th width="15%">
                                        Teaching Methods and Aids Needed For Each Topic 
                                    </th>
                                    <th width="20%">
                                        Evaluation Method or Tools To Be Used to Measure the Program Objectives
                                    </th>
                                </tr>
                                <?php foreach($learning_outcomes['section'] as $index => $section){  
                                    $video_counter =0;
                                    $article_counter =0;
                                    $video_length = 0;
                                    $array_emp = [];
                                    foreach($section['parts'] as $part){ 
                                        if($part['type'] == "article") {
                                            $article_counter += 1; }
                                        if($part['type'] == "video") {
                                            $video_counter += 1;
                                            $video_length += $part['video_length']; }
                                    }
                                ?>
                                <tr>
                                    <td>
                                        <div class="form-group" id="[objectives][<?= $index ?>]">
                                            <select class="form-control kt-select2" name="[objectives][<?= $index ?>]" multiple="multiple" style="width:100% !important">
                                                <!-- <option selected value="all">All Instructors</option> -->
                                                @if(isset($objectives)))
                                                    @foreach ( $objectives as $objective)
                                                        <?php if( count($instructional_design)){?>
                                                            <option value="{{$objective}}" <?= array_search($objective,json_decode($instructional_design[$index]->objectives)) !== false ? "selected" :"" ?>><?= $objective; ?></option>
                                                        <?php }else{ ?>
                                                            <option value="{{$objective}}" ><?= $objective; ?></option>
                                                        <?php } ?>
                                                    @endforeach
                                                @else
                                                <option disabled selected>No objective Found</option>
                                                @endif
                                            </select>
                                        </div>
                                    </td>
                                    <td>
                                        <?= $section['objective'] ?><input type="hidden" name="[section_objective][<?= $index ?>]" value="<?= $section['objective'] ?>">
                                    </td>
                                    <td>
                                        <div class="form-group" id="[objectives][<?= $index ?>]">
                                            <select class="form-control kt-select2"  name="[instructors][<?=$index?>]" multiple="multiple" style="width:100% !important">
                                                <!-- <option selected value="all">All Instructors</option> -->
                                                @if(count($webinar_instructors)) > 0)
                                                    @foreach ( $webinar_instructors as $instructor)
                                                    <?php if( count($instructional_design)){?>
                                                        <option value="{{$instructor->name}}" <?= array_search($instructor->name,json_decode($instructional_design[$index]->instructors)) !== false ? "selected" :"" ?>><?= $instructor->name; ?></option>
                                                    <?php }else{ ?>
                                                        <option value="{{$instructor->name}}"><?= $instructor->name; ?></option>
                                                    <?php } ?>
                                                    
                                                    @endforeach
                                                @else
                                                <option disabled selected>No Instructors Found</option>
                                                @endif
                                            </select>
                                        </div>
                                    </td>
                                    <td>
                                        <?= json_decode($schedule_details['time_allotment'][$index]) ?> mins <input type="hidden" name="[video_length][<?= $index ?>]" value="<?= json_decode($schedule_details['time_allotment'][$index]) ?>">
                                    </td>
                                    <td>
                                        <?= $video_counter ?> Lecture(s)<br/><input type="hidden" name="[video_counter][<?= $index ?>]" value="<?= $video_counter?>">
                                        <?= $article_counter ?> Article(s)<input type="hidden" name="[article_counter][<?= $index ?>]" value="<?= $article_counter ?>">
                                    </td>
                                    <td>
                                        <?php $quiz_counter =0;
                                        $quiz_question_counter =0;
                                        foreach($section['parts'] as $part){ 
                                            if($part['type'] == "quiz") {
                                            $quiz_counter += 1; $quiz_question_counter += count($part['items']);  ?>
                                                
                                            <?php } ?>
                                        <?php } ?>
                                        
                                        <?php
                                        if($quiz_counter == 0) { ?> 
                                            No Assessment to be made
                                            <input type="hidden" name="[evaluation_quiz_count][<?= $index ?>]" value="No Assessment to be made">
                                            <input type="hidden" name="[evaluation_question_count][<?= $index ?>]" value="No Assessment to be made">
                                        <?php }else{?>
                                            <?= $quiz_counter ?> Quiz of <input type="hidden" name="[evaluation_quiz_count][<?= $index ?>]" value="<?= $quiz_counter ?>">
                                            <?= $quiz_question_counter ?> questions<input type="hidden" name="[evaluation_question_count][<?= $index ?>]" value="<?= $quiz_question_counter ?>">
                                        <?php }?>
                                    </td>
                                    
                                </tr>
                            <?php }?>
                            </table>
                            <div class="form-group">
                                <button type="button" id="edit_inst" class="btn btn-sm btn-outline-info hidden" >Edit</button>
                            </div>
                            <div class="row" style="float:right">
                                <div class="col-lg-12 ml-lg-xl-auto">
                                    <button class="btn btn-success" id="submit_form">Submit</button>
                                    <!-- <button type="reset" class="btn btn-secondary">Clear</button> -->
                                    <div class="kt-space-20"></div>
                                    <div class="kt-space-20"></div>
                                </div>
                            </div>

                            <!-- //////////////////////////// end instructional ///////////////////////////////////e -->
                        </form>
                    </div>
                </div>
            </div>
        </div>
        <div class="kt-space-20"></div>
        <div class="kt-space-20"></div>
        <div class="form-group">
            <label class="bold">We have generated your document - Application for Accreditation of CPD Program. Click the button to download a DOC file which you can finish editing. We have provided all the information about the webinar for faster accreditation.  </label>
                                
            <label class="bold" style="font-weight:bold;">Sample Certificate Link: &nbsp;
                <?php if(isset(_current_webinar()->certificate_templates)){ 
                    foreach(json_decode(_current_webinar()->certificate_templates) as $link){?>
                    
                    <a style="font-weight:normal;" href="<?= $link ?? " https://www.fastcpd.com/webinar-certificate/notfound" ?>"> 
                        <?= $link ?? " https://www.fastcpd.com/webinar-certificate/notfound" ?>
                    </a>
                <?php } }else{ ?>
                    <a style="font-weight:normal;" href="<?= _current_webinar()->certificate_templates ?? " https://www.fastcpd.com/webinar-certificate/notfound" ?>"> <?= json_decode(_current_webinar()->certificate_templates) ?? " https://www.fastcpd.com/webinar-certificate/notfound" ?></a></label>
                <?php } ?>
            </label>

        </div>
        
        <div class="form-group">
            <label class="bold" style="font-weight:bold;font-size:15px;">Accreditor Credentials:</label> <br />
            <label class="bold" style="font-weight:bold;">Email: &nbsp; <font style="font-weight:normal;"> {{ $accreditor ? ($accreditor->email) : 'Submit accreditation first'}}</font> </label> <br />
            <label class="bold" style="font-weight:bold;">Password: &nbsp; <font style="font-weight:normal;"> {{ $accreditor ? ($accreditor->password) : 'Submit accreditation first'}}</font></label>
        </div>

        <div class="kt-portlet__foot">
            <div class="row" style="float:right;">
                <div class="col-lg-4 ml-lg-xl-auto">
                    @if(_current_webinar()->expenses_breakdown && $with_price && _current_webinar()->submit_accreditation_evaluation) 
                        <button type="button" id="view_cpdas" class="btn btn-sm btn-outline-info hidden" >CPDAS</button>
                    @endif

                    &nbsp;
                    &nbsp;
                    &nbsp;
                </div>
                <div class="col-lg-8 ml-lg-xl-auto">
                    @if(_current_webinar()->expenses_breakdown && $with_price && _current_webinar()->submit_accreditation_evaluation) 
                        <button type="button" id="print_app_form" class="btn btn-sm btn-outline-info hidden" >{{ $accreditor ? 'Document Download' : 'Submit accreditation first' }}</button>
                    @endif

                    &nbsp;
                    &nbsp;
                    &nbsp;

                </div>
            </div>
        </div>
        
    </div>
</div>
<input type="hidden" id="webinar_id" value="<?= _current_webinar()->id ?>" >
<input type="hidden" id="checked" value="{{ $accreditor ? true : false }}" >
<!-- Modal -->
@endsection

@section('scripts')
<script src="{{asset('js/webinar/creation/accreditation/accreditation.js')}}" type="text/javascript"></script>
@endsection