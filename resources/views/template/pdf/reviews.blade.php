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
                <?= $provider->name ?>
            </h1>
            <h2 class="center"><?=$type?> Reviews <br/> <?= date("F Y") ?></h2>
        </div>
    </div>

    <hr/>
 
        <table width="100%" class="t-style" cellpadding="5">
            <thead class="thead-style">
               
                <tr>
                    <td class="center">Date</td>
                    <td class="center">Customer</td>
                    <td class="center">Course</td>
                    <td class="center">Rating Grade</td>
                    <td class="center">Comment</td>
                </tr>
            </thead>
            <tbody>
             
                @foreach($reviews as $review)
                <tr>
                    <td class="bold center">
                       <?= 
                            date("F j, Y",strtotime($review->feedback_date))
                        ?>
                    </td>
                    <td class="center"> 
                        <?= 
                            ucwords($review->customer)
                        ?>
                    </td>
                    <td class="center">
                        <?= 
                        ucwords($type == "Course" ? $review->course : $review->webinar )    
                        ?>
                    </td>
                    <td class="center">
                        <?= $review->rating ?>
                    </td>
                    <td>
                        <?= $review->feedback ?>
                    </td>
                </tr>
                @endforeach
              
            </tbody>
        </table>
        <br/>
  
</div>