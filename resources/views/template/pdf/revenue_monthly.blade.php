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
            <h1 class="center"><?= date("F Y") ?> - <?= $provider->name ?> Payout</h1>
        </div>
    </div>
    
    <hr/>
    
    <table width="100%" class="t-style" cellpadding="5">
        <thead class="thead-style">
            
            <tr>
                <td class="center">Purchase Date</td>
                <td class="center">Payment Date</td>
                <td class="center">Type</td>
                <td class="center">Customer</td>
                <td class="center">Course</td>
                <td class="center">Coupon Code</td>
                <td class="center">Channel</td>
                <td class="center">Revenue</td>
                <td class="center">Price Paid</td>
                <td class="center">Original Price</td>
                
            </tr>
        </thead>
        <tbody>
            
            @foreach($revenues as $revenue)
            <tr>
                
                <td class="center">
                    <?= 
                    date("M. d 'y",strtotime($revenue->purchase_date))
                    ?>
                </td>
                <td class="center">
                    <?= date("M. d 'y",strtotime($revenue->payment_date)) ?>
                </td>
                <td class="center">
                    <?= 
                    ucwords($revenue->type)
                    ?>
                </td>
                <td class="center">
                    <?= 
                    $revenue->customer
                    ?>
                </td>
                <td class="center">
                    <?= 
                        $revenue->type == "course" ? $revenue->course : $revenue->webinar  
                    ?>
                </td>
                <td class="center">
                    <?= $revenue->coupon_code ?>
                </td>
                <td class="center">
                    <?php
                    if($revenue->coupon_code == null && $revenue->discount == null ){
                        echo "";
                    }else{
                        switch($revenue->channel){
                            case "provider_promo": 
                            echo "Provider Promo";
                            break;
                            case 'fast_promo':
                            echo "Fast CPD Promo";
                            break;
                            case 'endorser_promo':
                            echo "Promoter Promo";
                            break;
                            default: 
                            echo "";
                            break;
                        }

                    }
                   
                    ?>
                </td>
                <td class="center"> Php 
                    <?= number_format($revenue->provider_revenue,2) ?>
                </td>
                <td class="center"> Php 
                    <?= number_format($revenue->price_paid,2) ?>
                </td>
                <td class="center"> Php 
                    <?php
                       $price = $revenue->type == "course" ? $revenue->course_original_price : 
                      ($revenue->webinar_original_price ? ($revenue->offering_type == "with" ? json_decode($revenue->webinar_original_price)->with : json_decode($revenue->webinar_original_price)->without) : "0");  
                      
                    ?>
                    <?= number_format($price,2) ?>
                </td>
               
            </tr>
            @endforeach
            
        </tbody>
    </table>
    <br/>
    
</div>