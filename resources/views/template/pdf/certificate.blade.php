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

</style>
<?php 
if(isset($courses)){
    $count_course_profession_id = count(json_decode($courses->profession_id));

    foreach(json_decode($courses->profession_id) as $key => $course){
        if($count_course_profession_id > 1 && $key > 0){
            echo "<pagebreak/>";
        }

?>
<div class="container">
    <div class="row">
        <table width="100%">
            <tr>
                <td width="30%"></td>
                <td width="70%" class="center pad-side" >
                    <img class="image-logo" src="<?= $provider_logo ?>" alt="dbsaf">
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
                    <h2><?= $name ?></h2>
                </td>
            </tr>
            <tr>
                <td width="30%"></td>
                <td width="70%" class="center pad-side font-l">
                    PRC LICENSE NO. <?php 
                                foreach(json_decode($professions) as $prof){
                                        if($course == $prof->id){
                                            echo $prof->prc_no;
                                        }
                                }
                               
                            
                        ?>
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
                <td width="70%" class="center font-xl" style="color:#5a5a5a">
                    <h3><?= $title ?></h3>
                </td>
            </tr>

            <tr >
                <td width="30%"></td>
                <td width="70%" class="center  font-m" >
                <?= $headline ?>
                </td>
            </tr>

            <tr>
                <td width="30%"></td>
                <td width="70%" class="center pad-side pad-down font-m">
                Program Accreditation No. <?= $acc_number ?>
                </td>
            </tr>

            <tr>
                <td width="30%"></td>
                <td width="70%" class="center pad-side pad-down-x2 font-m" >
                Has been awarded <?= $cpd_units ?> CPD Credit Units <br/>
                on <?= $date ?>
                </td>
            </tr>

            <tr>
                <td width="30%"><img class="image-qr" src="<?= $qr_link ?>"></td>
                <td width="70%" colspan="5" class="pad-side border-bottom center " >
                    <span style="color:white;">SPACING IS INEVITABLE</span></td>
            </tr>

            <tr>
                <td width="30%" class="font-s">Certificate No.:<?= $cert_number ?><br/>Verified at "<?= $verification_link ?>"</td>
                <td width="70%" class="center pad-side font-m">
                    <?= $provider_name ?><br/>
                    PRC No. <?= $provider_prc_number?>
                </td>
            </tr>

            <tr>
                <td width="30%"  ></td>
                <td width="60%" >
                </td>
            </tr>
            
        </table>
        
      
        
    </div>
</div>
<?php } }else{ ?>

    <div class="container">
        <div class="row">
            <table width="100%">
                <tr>
                    <td width="30%"></td>
                    <td width="70%" class="center pad-side" >
                        <img class="image-logo" src="<?= $provider_logo ?>" alt="dbsaf">
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
                        <h2><?= $name ?></h2>
                    </td>
                </tr>
                <tr>
                    <td width="30%"></td>
                    <td width="70%" class="center pad-side font-l">
                        PRC LICENSE NO. 4324321431 
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
                    <td width="70%" class="center font-xl" style="color:#5a5a5a">
                        <h3><?= $title ?></h3>
                    </td>
                </tr>

                <tr >
                    <td width="30%"></td>
                    <td width="70%" class="center  font-m" >
                    <?= $headline ?>
                    </td>
                </tr>

                <tr>
                    <td width="30%"></td>
                    <td width="70%" class="center pad-side pad-down font-m">
                    Program Accreditation No. <?= $acc_number ?>
                    </td>
                </tr>

                <tr>
                    <td width="30%"></td>
                    <td width="70%" class="center pad-side pad-down-x2 font-m" >
                    Has been awarded <?= $cpd_units ?> CPD Credit Units <br/>
                    on <?= $date ?>
                    </td>
                </tr>

                <tr>
                    <td width="30%"><img class="image-qr" src="<?= $qr_link ?>"></td>
                    <td width="70%" colspan="5" class="pad-side border-bottom center " >
                        <span style="color:white;">SPACING IS INEVITABLE</span></td>
                </tr>

                <tr>
                    <td width="30%" class="font-s">Certificate No.:<?= $cert_number ?><br/>Verified at "<?= $verification_link ?>"</td>
                    <td width="70%" class="center pad-side font-m">
                        <?= $provider_name ?><br/>
                        PRC No. <?= $provider_prc_number?>
                    </td>
                </tr>

                <tr>
                    <td width="30%"  ></td>
                    <td width="60%" >
                    </td>
                </tr>
                
            </table>
            
        
            
        </div>
    </div>
<?php }
    

?>