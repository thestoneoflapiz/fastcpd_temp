
    @extends('template.mail')
    @section('styles')
    <style>
    </style>
    @endsection
    @section('content')

    <div class="center" style="margin: 30px;">
        <img alt="FastCPD Company Logo" src="https://fastcpd.s3-ap-northeast-1.amazonaws.com/images/Logos/logo-1.png" width="150">
    </div>


    <div class="header-box">
        <table width="100%;" class="header-bg">
            <tr>
                <td>
                    <h2 class="h2-style">
                       <?=$notif_data['subject']?? 'Notification'?>
                    </h2>
                </td>
            </tr>
        </table>
    </div>
    <div class="body-box">
        <div class="body-container">

            <br/>
            <div class="message">
                <?= $notif_data['body'] ?>
                
            </div>
        </div>
        <div class="body-footer center ">
            <?php if($notif_data['link_button'] != "" ){?>
                
                <?php if($notif_data['base_link'] == ""){?>
                    <a href="{{ URL::to($notif_data['link_button']) }}" target="_blank" class="center_div button">{{$notif_data['label_button']??"VIEW"}}</a>
                <?php }else{ ?>
                    <a href="<?=\config("app.subdomain"). $notif_data['link_button'] ?>" target="_blank" class="center_div button">{{$notif_data['label_button']??"VIEW"}}</a>
                <?php } ?>
            <?php } ?>
            
        </div>
    </div>
    @endsection