<style type="text/css">
    body {font-family: 'Calibri';font-size:13.5px;}
    hr{border:0;border-top:1px solid #ABB8BC;}
    table{border-collapse: collapse;}

    .center{text-align:center;}
    .left{text-align:left;}
    .right{text-align:right;}
    .bold{font-weight:bold;}
    .upper-line{border-top:1px solid #000;}
    .bottom-line{border-bottom:1px solid #000;}

    .t-style{background-color:#EAEEEF;}
    .t-style tbody tr td{border:0;border-bottom:0.5px solid #ABB8BC;}
    .thead-style tr td{background-color:#00ADEF;color:#fff;font-weight:bold;}
</style>
<div>
    <div class="row">
        <div class="col-xl-12">
            <h1 class="center">
                Courses Reports
            </h1>
            <h2 class="center"><?= $type == "video-on-demand" ? "Video on Demand" : "Webinars" ?></h2>
        </div>
    </div>

    <hr/>
        @if($type == "video-on-demand")
        <table width="100%" class="t-style" cellpadding="5">
            <thead class="thead-style">
               
                <tr>
                    <td class="center">Provider</td>
                    <td class="center">Course</td>
                    <td class="center">Date Published</td>
                    <td class="center">Start Date</td>
                    <td class="center">Price</td>
                    <td class="center">No. of Enrollees</td>
                    <td class="center">CPD Units</td>
                    <td class="center">Instructors</td>
                </tr>
            </thead>
            <tbody>
           
                @foreach($data as $datum)
                <tr>
                    
                    <td class="bold center">
                        
                       <?= 
                            $datum['provider_name']
                        ?>
                    </td>
                    <td class="center"> 
                        <?= 
                            $datum['course_title']
                        ?>
                    </td>
                    <td class="center">
                        <?= 
                            $datum['course_published_at']  
                        ?>
                    </td>
                    <td class="center">
                        <?= 
                            $datum['course_session_start']  
                        ?>
                    </td>
                    <td class="center">
                        Php <?=  number_format($datum['course_price'],2)   ?>
                    </td>
                    <td class="center">
                        <?=  $datum['enrollees_total']  ?>
                    </td>
                    <td  class="center">
                        <?=  $datum['cpd_units'][0]['profession']  ?> -  <?=  $datum['cpd_units'][0]['units']  ?>
                    </td>
                    <td class="center">
                        <?php 
                            foreach($datum['instructors'] as $instructors){
                                echo $instructors." ";
                            } 
                            
                        ?>
                    </td>
                </tr>
                @endforeach
              
            </tbody>
        </table>
        @endif
        @if($type == "webinar")
        <table width="100%" class="t-style" cellpadding="5">
            <thead class="thead-style">
               
                <tr>
                    <td class="center">Provider</td>
                    <td class="center">Course</td>
                    <td class="center">Date Published</td>
                    <td class="center">Start Date</td>
                    <td class="center">Price W/ CPD Units </td>
                    <td class="center">Price W/o CPD Units </td>
                    <td class="center">No. of Enrollees</td>
                    <td class="center">CPD Units</td>
                    <td class="center">Instructors</td>
                </tr>
            </thead>
            <tbody>
             
                @foreach($data as $datum)
                <tr>
                    
                    <td class="bold center">
                        
                       <?= 
                            $datum['provider_name']
                        ?>
                    </td>
                    <td class="center"> 
                        <?= 
                            $datum['course_title']
                        ?>
                    </td>
                    <td class="center">
                        <?= 
                            $datum['course_published_at']  
                        ?>
                    </td>
                    <td class="center">
                        <?= 
                            $datum['start_date']  
                        ?>
                    </td>
                    <td class="center">
                        Php <?=  number_format($datum['price_with'],2)   ?>
                    </td>
                    <td class="center">
                        Php <?=  number_format($datum['price_without'],2)   ?>
                    </td>
                    <td class="center">
                        <?=  $datum['enrollees_total']  ?>
                    </td>
                    <td  class="center">
                        <?=  $datum['cpd_units'][0]['profession']  ?> -  <?=  $datum['cpd_units'][0]['units']  ?>
                    </td>
                    <td class="center">
                        <?php 
                            foreach($datum['instructors'] as $instructors){
                                echo $instructors." ";
                            } 
                            
                        ?>
                    </td>
                </tr>
                @endforeach
              
            </tbody>
        </table>
        @endif
        <br/>
  
</div>