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
                Purchases Reports
            </h1>
            <h2 class="center">
                <?=date("F j, Y",strtotime($start_date))?> - <?= date("F j, Y",strtotime($end_date)) ?> <br/> 
                <?php 
                    if($methods[0] == 0){
                        echo "All Payment Methods";
                    }else{
                        $method_count = count($methods);
                        foreach ($methods as $key => $method) {
                            if($key+1 == $method_count){
                                switch($method){
                                    case "1":
                                        echo "Credit Card.";
                                    break;
                                    case "2":
                                        echo "E-Wallet.";
                                    break;
                                    case "3":
                                        echo "Online Banking.";
                                    break;
                                    case "4":
                                        echo "Bank Deposit OTC.";
                                    break;
                                    case "5":
                                        echo "Payment Centers.";
                                    break;
                                    default:
                                        echo "Undefined.";
                                    break;
                                }
                            }else{
                                switch($method){
                                    case "1":
                                        echo "Credit Card, ";
                                    break;
                                    case "2":
                                        echo "E-Wallet, ";
                                    break;
                                    case "3":
                                        echo "Online Banking, ";
                                    break;
                                    case "4":
                                        echo "Bank Deposit OTC, ";
                                    break;
                                    case "5":
                                        echo "Payment Centers, ";
                                    break;
                                    default:
                                        echo "Undefined, ";
                                    break;
                                }
                               
                            }
                        }
                    }
                ?>
            </h2>
            
        </div>
    </div>

    <hr/>
       
        <table width="100%" class="t-style" cellpadding="5">
            <thead class="thead-style">
               
                <tr>
                    <td class="center">Transaction Code</td>
                    <td class="center">Purchased By</td>
                    <td class="center">Purchased Date</td>
                    <td class="center">Payment Date</td>
                    <td class="center">Original Price</td>
                    <td class="center">Purchased Price</td>
                    <td class="center">Items</td>
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
                            $datum['payment_date']  
                        ?>
                    </td>
                    <td class="center">
                        Php <?=  number_format($datum['original_price'],2)   ?>
                    </td>
                    <td class="center">
                        Php <?=  number_format($datum['purchased_price'],2)   ?>
                    </td>
                   
                    <td class="center">
                        <?= $datum['items'] ?>
                    </td>
                </tr>
                @endforeach
              
            </tbody>
        </table>
      
      
        <br/>
  
</div>