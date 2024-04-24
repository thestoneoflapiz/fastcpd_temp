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
                Purchase Items Report
               
            </h1>
            <h2 class="center"> <?= date("F j, Y",strtotime($start_date)) ?> - <?=date("F j, Y") ?></h2>
            
        </div>
    </div>

    <hr/>
     
        <table width="100%" class="t-style" cellpadding="5">
            <thead class="thead-style">
               
                <tr>
                    <td class="center">Transaction Code</td>
                    <td class="center">Voucher Code</td>
                    <td class="center">Purchased By</td>
                    <td class="center">Purchased Date</td>
                    <td class="center">Provider</td>
                    <td class="center">Type</td>
                    <td class="center">Course</td>
                    <td class="center">Channel</td>
                    <td class="center">Original Price</td>
                    <td class="center">Price Paid</td>
                    <td class="center">Provider Commission</td>
                    <td class="center">Provider Revenue</td>
                    <td class="center">Fast Commission</td>
                    <td class="center">Fast Revenue</td>
                    <td class="center">Affiliate Commission</td>
                    <td class="center">Affiliate Revenue</td>
                </tr>
            </thead>
            <tbody>
             
                @foreach($data as $datum)
                <tr>
                    
                    <td class="bold center">
                        
                       <?= 
                            $datum['transaction_code']
                        ?>
                    </td>
                    <td class="center"> 
                        <?= 
                            $datum['voucher']
                        ?>
                    </td>
                    <td class="center"> 
                        <?= 
                            $datum['purchased_by']
                        ?>
                    </td>
                    <td class="center">
                        <?= 
                            $datum['purchased_date']  
                        ?>
                    </td>
                    <td class="center"> 
                        <?= 
                            $datum['provider']
                        ?>
                    </td>
                    <td class="center"> 
                        <?= 
                            $datum['type']
                        ?>
                    </td>
                    <td class="center"> 
                        <?= 
                            $datum['course']
                        ?>
                    </td>
                    <td class="center"> 
                        <?= 
                            $datum['channel']
                        ?>
                    </td>
                    <td class="center">
                        Php <?=  number_format($datum['original_price'],2)   ?>
                    </td>
                    <td class="center">
                        Php <?=  number_format($datum['price_paid'],2)   ?>
                    </td>
                    <td class="center">
                       <?= $datum['provider_comm']   ?>
                    </td>
                    <td class="center">
                        Php <?=  number_format($datum['provider_revenue'],2)   ?>
                    </td>
                    <td class="center">
                        <?=  $datum['fast_comm']   ?>
                    </td>
                    <td class="center">
                        Php <?=  number_format($datum['fast_revenue'],2)   ?>
                    </td>
                    <td class="center">
                        <?=  $datum['affiliate_comm']   ?>
                    </td>
                    <td class="center">
                        Php <?=  number_format($datum['affiliate_revenue'],2)   ?>
                    </td>
                </tr>
                @endforeach
              
            </tbody>
        </table>
      
      
        <br/>
  
</div>