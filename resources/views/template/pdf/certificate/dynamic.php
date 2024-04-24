<style>
html, body{font-family: baskerville}
.right{text-align: right;}
.left{text-align: left;}
.center{text-align: center;}
.justify{text-align:justify;}
.border-bottom{border-bottom:1px solid grey;}
.bold{font-weight:bold;}
.footer-right{float:right; width:35%;}
.footer-left{float:left; width:35%;}
.font-xl{font-size: 25px; color:#5a5a5a !important;}
.font-xsl{font-size: 23px; color:#5a5a5a !important;}
.font-xssl{font-size: 21px; color:#5a5a5a !important;}
.font-l{font-size: 20px; color:grey;}
.font-m{font-size: 17px; color:grey}
.font-s{font-size: 14px;color:grey}
small{font-size:10px;}
.container{font-family:baskerville;}
.pad-down{padding-bottom:25px; color:grey}
.pad-down-x2{padding-bottom:45px;}
.pad-side{padding-left:100px;padding-right:100px;}
.image-logo{height:120;width:120px;}
.image-qr{height:50px;width:50px;}
.image-signature{height:120px;width:auto;z-index:999;margin-bottom:-30px;}

/* $user->professions */
</style>
<?php if($data->accreditation){ ?>
    <?php foreach($data->accreditation as $key => $acc){ ?>
        <?php if($key > 0){ ?>
        <pagebreak/>
        <?php } ?>
        <div class="container">
            <div class="row">
                <table width="100%">
                    <tr>
                        <td width="30%"></td>
                        <td width="70%" class="center pad-side" >
                            <img class="image-logo" src="<?= $provider->logo ?>" alt="dbsaf">
                        </td>
                    </tr>
                    <tr>
                        <td width="30%"></td>
                        <td width="70%" class="center pad-side pad-down font-l">
                            This is to certify
                        </td>
                    </tr>
                    <tr>
                        <td width="30%"></td>
                        <td width="70%" class="center pad-side <?= strlen($user->name) <= 28 ? "font-xl" : "font-xssl"?>">
                            <h2><?= $user->name ?></h2>
                        </td>
                    </tr>
                    <tr>
                        <td width="30%"></td>
                        <td width="70%" class="center pad-side font-l">
                        <?php foreach(json_decode($user->professions) as $prof){ ?>
                            <?php if($acc->id == $prof->id) { ?>
                                PRC LICENSE NO. <?=$prof->prc_no;?>
                            <?php } ?>
                        <?php } ?>
                        </td>
                    </tr>
                    <tr>
                        <td width="30%"></td>
                        <td width="70%" class="center pad-side font-m">
                            as
                        </td>
                    </tr>
                    <tr>
                        <td width="30%"></td>
                        <td width="70%" class="center font-xl" style="color:#5a5a5a">
                            PARTICIPANT
                        </td>
                    </tr>
                    <tr>
                        <td width="30%"></td>
                        <td width="70%" class="center pad-side pad-down font-m">
                            has successfully completed with distinction
                        </td>
                    </tr>
                    <tr>
                        <td width="30%"></td>
                        <td width="70%" class="center <?= strlen($user->title) <= 60 ? "font-xl" : (strlen($user->title) <= 78 && strlen($user->title) > 60  ? "font-xsl" : "font-xssl") ?>" style="color:#5a5a5a">
                            <h3><?= $data->title ?></h3>
                        </td>
                    </tr>
                    <tr >
                        <td width="30%"></td>
                        <td width="70%" class="center font-m" >
                        <?= $data->headline ?>
                        </td>
                    </tr>
                    <tr>
                        <td width="30%"></td>
                        <td width="70%" class="center pad-side pad-down font-m">
                        Program Accreditation No. <?= $acc->program_no ?>
                        </td>
                    </tr>
                    <tr>
                        <td width="30%"></td>
                        <td width="70%" class="center pad-side pad-down-x2 font-m" >
                        Has been awarded <?= $acc->units ?> CPD Credit Units <br/>
                        on <?= date("F. d, Y", strtotime($certificate->created_at)) ?>
                        </td>
                    </tr>
                    <tr>
                        <td width="30%"><img class="image-qr" src="<?= $certificate->qr_link ?>"></td>
                        <td width="70%" colspan="5" class="pad-side border-bottom center">
                            <?php if($provider->signature){ ?><img class="image-signature" src="<?= $provider->signature ?>"> <?php } ?>
                        </td>
                    </tr>
                    <tr>
                        <td width="30%" class="font-s">Certificate No.:<?= $certificate->id ?><br/>Verified at "<?= $verification_link ?>"</td>
                        <td width="70%" class="center pad-side font-m">
                            <?= $provider->name ?><br/>
                            PRC No. <?= $provider->accreditation_number?>
                        </td>
                    </tr>
                    <tr>
                        <td width="30%"></td>
                        <td width="60%">
                        </td>
                    </tr>
                </table>
            </div>
        </div>
    <?php } ?>
<?php }else{ ?>
    <div class="container">
        <div class="row">
            <table width="100%">
                <tr>
                    <td width="30%"></td>
                    <td width="70%" class="center pad-side" >
                        <img class="image-logo" src="<?= $provider->logo ?>" alt="dbsaf">
                    </td>
                </tr>
                <tr>
                    <td width="30%"></td>
                    <td width="70%" class="center pad-side pad-down font-l">
                        This is to certify
                    </td>
                </tr>
                <tr>
                    <td width="30%"></td>
                    <td width="70%" class="center pad-side font-xl">
                        <h2><?= $user->name ?></h2>
                    </td>
                </tr>
                <tr>
                    <td width="30%"></td>
                    <td width="70%" class="center pad-side font-l"></td>
                </tr>
                <tr>
                    <td width="30%"></td>
                    <td width="70%" class="center pad-side font-m">
                        as
                    </td>
                </tr>
                <tr>
                    <td width="30%"></td>
                    <td width="70%" class="center font-xl" style="color:#5a5a5a">
                        PARTICIPANT
                    </td>
                </tr>
                <tr>
                    <td width="30%"></td>
                    <td width="70%" class="center pad-side pad-down font-m">
                        has successfully completed with distinction
                    </td>
                </tr>
                <tr>
                    <td width="30%"></td>
                    <td width="70%" class="center font-xl" style="color:#5a5a5a">
                        <h3><?= $data->title ?></h3>
                    </td>
                </tr>
                <tr >
                    <td width="30%"></td>
                    <td width="70%" class="center font-m" >
                    <?= $data->headline ?>
                    </td>
                </tr>
                <tr>
                    <td width="30%"></td>
                    <td width="70%" class="center pad-side pad-down font-m"> </td>
                </tr>
                <tr>
                    <td width="30%"></td>
                    <td width="70%" class="center pad-side pad-down-x2 font-m"></td>
                </tr>
                <tr>
                    <td width="30%"><img class="image-qr" src="<?= $certificate->qr_link ?>"></td>
                    <td width="70%" colspan="5" class="pad-side border-bottom center">
                        <img class="image-signature" src="<?= $provider->signature ?>">
                    </td>
                </tr>
                <tr>
                    <td width="30%" class="font-s">Certificate No.:<?= $certificate->id ?><br/>Verified at "<?= $verification_link ?>"</td>
                    <td width="70%" class="center pad-side font-m">
                        <?= $provider->name ?><br/>
                        PRC No. <?= $provider->accreditation_number?>
                    </td>
                </tr>
                <tr>
                    <td width="30%"></td>
                    <td width="60%">
                    </td>
                </tr>
            </table>
        </div>
    </div>
<?php } ?>
