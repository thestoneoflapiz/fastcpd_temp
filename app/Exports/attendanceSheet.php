<?php

namespace App\Exports;
Use App\{User, Provider, Co_Provider, Profile_Requests, Profession,Course,
    Section,Video,Quiz,Article,Quiz_Item,Course_Progress,Handout,Instructor,Instructor_Resume, Instructional_Design,
    CompletionReport, Course_Rating, CoursePerformance, Purchase_Item,Payout,Review,Certificate, 
    Webinar,Webinar_Series,Webinar_Session,Webinar_Attendance};

use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithHeadings;


class attendanceSheet implements FromQuery, WithMapping, WithHeadings
{
    /**
    * @return \Illuminate\Support\Collection
    */
    use Exportable;

    public function __construct(int $data_id)
    {
        $this->data_id = $data_id;
    }

    public function query()
    {
        $completion_rep = CompletionReport::where("id",$this->data_id)->first();

        $type = $completion_rep->type ?? "webinar"; 

        if($type == "course"){
            $participants = Purchase_Item::select(
                "users.id as participant_id",
                "users.first_name as first_name",
                "users.middle_name as middle_name",
                "users.last_name as last_name",
                "users.professions as prc_no",
                "users.contact as contact_no",
                "users.email as email",
                "purchase_items.data_id as data_id",
                "purchase_items.updated_at as time_out",
                "purchase_items.type as type"
            )
            ->where("purchase_items.data_id",$completion_rep->data_id)
            ->where("purchase_items.type",$type)
            ->where("purchase_items.payment_status","paid")
            ->whereMonth("purchase_items.updated_at",date("m",strtotime($completion_rep->completion_date)))
            ->whereYear("purchase_items.updated_at",date("Y",strtotime($completion_rep->completion_date)))
            ->where("purchase_items.fast_status","complete")
            ->leftJoin("users","users.id","purchase_items.user_id");
        }else{
            $participants = Purchase_Item::select(
                "users.id as participant_id",
                "users.first_name as first_name",
                "users.middle_name as middle_name",
                "users.last_name as last_name",
                "users.professions as prc_no",
                "users.contact as contact_no",
                "users.email as email",
                "purchase_items.data_id as data_id",
                "purchase_items.updated_at as time_out",
                "purchase_items.type as type"
            )
            ->where("purchase_items.data_id",$this->data_id)
            ->where("purchase_items.type",$type)
            ->where("purchase_items.payment_status","paid")
            ->where("purchase_items.fast_status","complete")
            ->leftJoin("users","users.id","purchase_items.user_id");
        }
       
                       

        return $participants;
    }
    public function map($participants): array
    {
        $prof_id = json_decode($participants["prc_no"])[0]->id ?? 0;
        $prof_code = Profession::where("id", $prof_id)->first();
        $prc_no = json_decode($participants["prc_no"])[0]->prc_no ?? "";
        $proffession_id = json_decode($participants["prc_no"])[0]->id ?? 0;
        $units = 0;

       if($participants['type'] == "course"){
            $end = $participants['time_out'];
            $total_min = $this->course_total_time_length($participants['data_id']);
           
            $start = date("Y-m-d H:i:s", strtotime($total_min, strtotime($end) ));
            $course = Course::where("id",$participants['data_id'])->first();
            if($course->accreditation){
                foreach(json_decode($course->accreditation) as $prof)
                {
                    if($prof->id == $proffession_id)
                    {
                        $units = $prof->units ?? 0;
                        break;
                    }
                }
            }
            
       }else{
            $webinar = Webinar_Attendance::where("webinar_id",$participants["data_id"])
                                        ->where("user_id",$participants["participant_id"])
                                        ->first();
                                
            $end = $webinar->session_out ?? null;
            $start = $webinar->session_in ?? null;

            $webinar = Webinar::where("id",$participants['data_id'])->first();
           
            if($webinar->accreditation){
                foreach(json_decode($webinar->accreditation) as $prof)
                {
                    if($prof->id == $proffession_id)
                    {
                        $units = $prof->units ?? 0;
                        break;
                    }
                }
            }
       }
        return [
            $participants['first_name'],
            $participants['middle_name'],
            $participants['last_name'],
            $prof_code->profession_code ?? "",
            $prc_no,
            $start ? date("g:i A",strtotime($start)) : null,
            $end ? date("g:i A",strtotime($end)) : null,
            $units == 0 ? "0" : $units
        ];
    }
    public function headings(): array
    {
        $completion_rep = CompletionReport::where("id",$this->data_id)->first();
        $sheet =  
            [
                'FIRST NAME',
                'MIDDLE NAME',
                'LAST NAME',
                "PROFESSION CODE",
                "LICENSE NUMBER",
                "TIME IN",
                "TIME OUT",
                "UNIT/S EARNED",
            ];
        

        return $sheet;
       
    }
    function course_total_time_length($id)
    {
        $sections = Section::where([
            "type"=>"course",
            "data_id"=>$id,
            "deleted_at"=>null
        ])->get();
      
        if($sections && $sections->count() > 0){
            $total_course_length = 0;
            $remainder = 0;
            foreach ($sections as $key => $sec) {
                if($sec->sequences!=null){
                    $sequences = json_decode($sec->sequences);
                    foreach ($sequences as $key => $seq) {
                        if($seq->type == "video"){
                            $video = Video::find($seq->id);
                            if($video && $video->deleted_at==null && $video->cdn_url!=null){

                                $explode = explode(":",$video->length);
                                $explode_count = count($explode);
                                if($explode_count == 3){
                                    $convert = $explode[0] * 60;
                                    $convert += $explode[1];
                                    $remainder += $explode[2] ?? 0;
                                }else{
                                    $convert = $explode[0];
                                    $remainder += $explode[1];
                                }
                               
                                $total_course_length += (int)$convert;
                            }
                        }else if($seq->type == "article"){
                            $article = Article::find($seq->id);
                            if($article && $article->deleted_at==null){
                                $computed = _estimated_reading_time($article->description);
                                $explode = explode(".",$computed);
                                $explode_count = count($explode);
                                if($explode_count == 3){
                                    $convert = $explode[0] * 60;
                                    $convert += $explode[1];
                                    $remainder += $explode[2];
                                }else{
                                    $convert = $explode[0];
                                    $remainder += $explode[1];
                                }

                                $total_course_length += (int)$convert;
                            }
                        }else{
                            continue;
                        }
                    }
                }
            }
           // $total_course_length;
            $quotient = ($remainder >= 60) ? floor($remainder / 60) : 0;
            
            $total_minutes = $quotient+$total_course_length;
            return "-".$total_minutes." minutes";
            // $divided_minutes = $total_minutes / 60;
            // $dm_whole = floor($divided_minutes);
            // $dm_decimal = $divided_minutes - $dm_whole;
            // $seconds = ($remainder < 60) ? $remainder : 0;
            // $minutes = $dm_decimal * 60;
            // $hours = $dm_whole;
            // $hours = $hours<10&&$hours>=0 ? sprintf("%02d", $hours) : $hours;
            // $minutes = $minutes<10&&$minutes>=0 ? sprintf("%02d", $minutes) : $minutes;
            // $seconds = $seconds<10&&$seconds>=0 ? sprintf("%02d", $seconds) : $seconds;
            // if($hours == "00"){
            //     return "$minutes min $seconds s";
            // }elseif($minutes == "00"){
            //     return "$seconds s";
            // }elseif($seconds == "00"){
            //     return "Not defined";
            // }else{
            //     return "$hours hrs $minutes min $seconds s";
            // }
            
        }

        return "-0 minutes";
    }
}
