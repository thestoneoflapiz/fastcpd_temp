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
            <h1 class="center"><?= $provider->name ?> Payout</h1>
        </div>
    </div>

    <hr/>
 
        <table width="100%" class="t-style" cellpadding="5">
            <thead class="thead-style">
               
                <tr>
                    <td class="center">Time Period</td>
                    <td class="center">Amount</td>
                    <td class="center">Expected Payment Date</td>
                    <td class="center">Notes</td>
                </tr>
            </thead>
            <tbody>
                @foreach($revenues as $revenue)
                <tr>
                    <?php 
                        $month_year = date("F Y",strtotime($revenue->date_from."+2 month"));
                        $expected_date =  date("F j, Y",strtotime($month_year."first friday"));    
                    ?>
                    <td class="bold center">
                       <?= 
                            date("F Y",strtotime($revenue->date_to))
                        ?>
                    </td>
                    <td class="center"> Php 
                        <?= 
                            number_format($revenue->provider_revenue,2)
                        ?>
                    </td>
                    <td class="center">
                        <?= 
                        date("j",strtotime($expected_date)) >= 8 ? date("F j, Y",strtotime("-7 day",strtotime($expected_date))) : $expected_date     
                        ?>
                    </td>
                    <td class="center">
                        <?= $revenue->provider_revenue != 0 ? "Paid" : "No Payout" ?>
                    </td>
                </tr>
                @endforeach
              
            </tbody>
        </table>
        <br/>
  
</div>