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
                Payouts Reports
            </h1>
            
        </div>
    </div>

    <hr/>
       
        <table width="100%" class="t-style" cellpadding="5">
            <thead class="thead-style">
               
                <tr>
                    <td class="center">Month</td>
                    <td class="center">Year</td>
                    <td class="center">Type</td>
                    <td class="center">Provider</td>
                    <td class="center">Fast CPD</td>
                    <td class="center">Name</td>
                    <td class="center">Amount</td>
                    <td class="center">Expected Payment Date</td>
                    <td class="center">Status</td>
                    <td class="center">Notes</td>
                </tr>
            </thead>
            <tbody>
             
                @foreach($data as $datum)
                <tr>
                    
                    <td class="bold center">
                        
                       <?= 
                          $datum['month']
                        ?>
                    </td>
                    <td class="center"> 
                        <?= 
                            $datum['year']
                        ?>
                    </td>
                    <td class="center">
                        <?= 
                            $datum['type']  
                        ?>
                    </td>
                    <td class="center">
                        <?= 
                            $datum['provider_name']  
                        ?>
                    </td>
                    <td class="center">
                         <?=$datum['fast_cpd']?>
                    </td>
                    <td class="center">
                        <?=$datum['full_name']?>
                   </td>
                    <td class="center">
                        Php <?=  number_format($datum['amount'],2)   ?>
                    </td>
                   
                    <td class="center">
                        <?= $datum['expected_payment_date'] ?>
                    </td>
                    <td class="center">
                        <?= ucwords($datum['status']) ?>
                    </td>
                    <td class="center">
                        <?= $datum['notes'] ?>
                    </td>
                </tr>
                @endforeach
              
            </tbody>
        </table>
      
      
        <br/>
  
</div>