<header id="header">
    <?php renderview('HomePage/Templates/tmpnavlinks')?>
    <div id="header-contents">
        <aside id="<?=aside($this->uri->segment(1))?>">
            <div id="character">
                <img src="<?=charactersrc($this->uri->segment(1))?>" alt="" usemap="#MainCharacter">
            </div>
        </aside>
        <?php renderview('HomePage/Templates/tmpslider')?>
        <aside id="header-right-side">
         <section id="main_eb">


         </section>
         <section id="Game_Client_Download">
            <a href="<?=$this->llllllllz_model->GetDownloadLink();?>" target="_blank" class="main-client-download">
                <img src="<?=base_url()?>Images/Button/game_client_download_btn01.png" class="pointer" alt="Game Client Download-Download Game Client">
            </a>
        </section>
        <section id="login">
            <div class="clear"></div>

            <form id="frmLogin" method="post">

                <fieldset class="main-login">

                    <?php
                    if($this->session->userdata('IsAuth') == 'True'){
                        $points = $this->llllllllz_model->getpoints($this->session->userdata('UserID'));

                        if($points){
                            $ChaInfo = $this->llllllllz_model->getChaNumInfo($points[0]['ChaNum']);
                            
                            if($points[0]['ChaNum'] == NULL){
                                $ChaInfo2 = $this->llllllllz_model->getChaInfo($this->session->userdata('UserID'));
                                if(!$ChaInfo2){
                                    $ChaName = '';
                                    $ChaSchool = '<img src="'.base_url().'Images/icon/3.png" style="width:16px;"> No School';
                                    $ChaClass = '<img src="'.base_url().'Images/icon/3.png" style="width:16px;">';
                                    $ChaLevel = '0';
                                } else {
                                    $ChaName = $ChaInfo2[0]['ChaName'];
                                    $ChaSchool = school1($ChaInfo2[0]['ChaSchool']);
                                    $ChaClass = ClassIMG($ChaInfo2[0]['ChaClass']);
                                    $ChaLevel = $ChaInfo2[0]['ChaLevel'];
                                }
                            } else {
                                if(!$ChaInfo){
                                    $ChaName = '';
                                    $ChaSchool = '<img src="'.base_url().'Images/icon/3.png" style="width:16px;"> No School';
                                    $ChaClass = '<img src="'.base_url().'Images/icon/3.png" style="width:16px;">';
                                    $ChaLevel = '0';
                                } else {
                                    $ChaName = $ChaInfo[0]['ChaName'];
                                    $ChaSchool = school1($ChaInfo[0]['ChaSchool']);
                                    $ChaClass = ClassIMG($ChaInfo[0]['ChaClass']);
                                    $ChaLevel = $ChaInfo[0]['ChaLevel'];
                                }
                            }
                        } else {
                            $ChaInfo = $this->llllllllz_model->getChaInfo($this->session->userdata('UserID'));
                            if(!$ChaInfo){
                                $ChaName = '';
                                $ChaSchool = '<img src="'.base_url().'Images/icon/3.png" style="width:16px;"> No School';
                                $ChaClass = '<img src="'.base_url().'Images/icon/3.png" style="width:16px;">';
                                $ChaLevel = '0';
                            } else {
                                $ChaName = $ChaInfo[0]['ChaName'];
                                $ChaSchool = school1($ChaInfo[0]['ChaSchool']);
                                $ChaClass = ClassIMG($ChaInfo[0]['ChaClass']);
                                $ChaLevel = $ChaInfo[0]['ChaLevel'];
                            }
                        }

                        ?>
                        <div class="main-login-on">
                            <strong>Welcome Ran Online!</strong>
                            <div class="character-name"><?=$ChaName?></div>
                            <div class="character-info"><?=$ChaSchool;?> <span class="selector">ㅣ</span> <?=$ChaClass;?> Lv.<?=$ChaLevel;?></div>
                            <div class="my-cash">
                                <strong>E Points : </strong><em><?=((!$points) ? "0.00":"".formatnumber3($points[0]['EPoint'])."")?></em>
                                <br />
                                <strong>Share Points : </strong><em><?=((!$points) ? "0.00":"".formatnumber3($points[0]['VP'])."")?></em>
                            </div>
                            <div class="my-account">
                                <a href="<?=base_url()?>MyAccount"><img src="<?=base_url()?>Images/Button/My_Account.gif"></a> <a href="<?=base_url()?>MasterPage/logout"><img src="<?=base_url()?>Images/Button/logout.gif"></a>
                            </div>
                        </div>
                        <?php
                    } else {
                        ?>
                        <legend>login</legend>
                        <div class="login-input">
                            <input type="text" id="txtID" name="txtID" class="main-idText" autocomplete="off">
                            <input type="password" id="txtPW" name="txtPW" class="main-pwdText" autocomplete="off">
                            <input type="hidden" id="hdnHeartbeat" name="hdnHeartbeat" value="">
                        </div>
                        <div class="login-button">
                            <img src="<?=base_url()?>Images/Button/login_btn.gif" id="btnLogin" class="pointer" alt="login">
                        </div>
                        <div class="clear"></div>
                        <div class="member-management">
                            <span>Find <a href="#">ID</a> / <a href="#">PW</a> | <a href="<?=base_url()?>Registration">Sign-Up</a></span>
                        </div>
                        <div class="login-management2">
                            <span>
                            <!-- <a href="#">
                            <img src="<?=base_url()?>Images/Icon/logo-social-fb-facebook-icon.svg" style="width:16px;"> Facebook
                            </a> -->
                            <!-- | 
                            <a href="#">
                            <img src="<?=base_url()?>Images/Icon/google.png" style="width:16px;"> Google
                            </a> -->
                            </span>
                        </div>
                        <?php
                    }
                    ?>




                </fieldset>
            </form>

        </section>
    </aside>
</div>
<div class="clear"></div>
</header>