<title><?=((count($GSet) > 0) ? "".$GSet[0]['ServerTitle']."":"")?>| My Account</title>
<body  class="sub-body">
    <div class="in-body9">
        <div class="bottom-body">
            <div id="wrapper">
                <?php
                renderview('HomePage/Templates/tmpheader');
                ?>
                <div id="container">
                    <nav id="nav">                
                        <section id="left-nav">&nbsp;</section>
                    
                    </nav>
                    <section id="content">
                        
    <section class="page-history">
		HOME
		<img src="<?=base_url()?>Images/ico_navi.gif" alt="">
		<span class="page-history-select">MY ACCOUNT</span>
	</section>
	<h3 class="sub-title">
		<img src="<?=base_url()?>Images/SubTitle/myaccount/myaccount_sub_title.gif" alt="My Account" class="left">
	</h3>
	<div class="clear"></div>
    <section class="MyAccount">
    <section class="system">
            <table class="system-info">
            <colgroup>
                <col style="width:100px;">
                <col style="width:600px;">
            </colgroup>
            <tbody>

            <tr>
                <th class="header"><h4>ID</h4></th>
                <td class="sysbody"><?=$this->Internal_model->GetUserUserName($this->session->userdata('UserID'))?></td>
            </tr>
            <!-- <tr>
                <th class="header"><h4>Email</h4></th>
                <td class="sysbody">
                <p><label id="Email"><b>jarwingines@gmail.com</b></label> (<label id="lbCert" style="color:#5cb85c">Verify</label>)
                <button class="btnmyaccount pointer">CHANGE EMAIL</button><br></p>
                <div class="myaccount-notice">
                <p>If you have Verified your email, you can change your email.</p>
                <p>You cannot change your email without verifying your email.</p>
                <p>You can't Recharge without Verified your Email address.</p>
                </div>
                </td>
            </tr> -->
            <tr>
                <th class="header"><h4>DISCONNECT</h4></th>
                <td class="sysbody"><a class="jquery-wm">
                <button id="btnAccountFix" class="btnmyaccount pointer" style="width: 100px;">ACCOUNT FIX</button></a></td>
            </tr>

            
            <tr>
                <th class="header"><h4>CONVERTER</h4></th>
                <td class="sysbody">
                    <div>
                        <a class="jquery-wm">
                        <button onclick="myFunction();" id="btnGTtoGTP" class="btnmyaccount pointer">Gametime to Gametime Points</button></a>
                        
                        <a class="jquery-wm">
                        <button onclick="myFunction();" id="btnGTPtoVP" class="btnmyaccount pointer">GameTime Points - Vote Points</button></a>
                        <a class="jquery-wm">
                        <button onclick="myFunction();" id="btnEPtoVP" class="btnmyaccount pointer">E-Point to Vote Points</button></a>
                    </div>
                </td>
            </tr> 
           
            
            <!-- 
            <tr>
                <th><h4>ACCOUNT CONTROL</h4></th>
                <td>
                    <a href='#' class=jquery-wm>
                    <button onclick="myFunction();" id="buttonstat" class="btnmyaccount pointer">RESET STAT</button></a>
                    <a href='#' class=jquery-wm>
                    </a>
                    <button onclick="myFunction();" id="buttonreborn" class="btnmyaccount pointer" style="letter-spacing:1px;">REBORN</button></a>
                    <div class="myaccount-notice">
                    <p style="color:grey;">Note Please Logout your Game Account before use it</p>
                    </div>
                </td>
            </tr> 
            -->

            <!--             
            <tr>
                <th><h4>Gold Market</h4></th>
                <td>
                    <a href="#" class=jquery-wm>
                    <button onclick="myFunction();" id="btnfix" class="btnmyaccount pointer">SALE GOLD</button></a></td>
            </tr>
            <tr>
                <th><h4>Referral</h4></th>
                <td>
                    <a href="#" class=jquery-wm>
                    <button onclick="myFunction();" id="btnfix" class="btnmyaccount pointer">SALE GOLD</button></a></td>
            </tr> 
            -->

            <tr>
                <th class="header"><h4>Character List</h4></th>
                <td class="sysbody">
                    <table class="CharacterList" style="width:100%;">
                    <caption>Ranking List</caption>
                    <colgroup>
                        <col style="width:25%;">
                        <col style="width:25%;">
                        <col style="width:20%;">
                        <col style="width:10%;">
                        <col style="width:10%;">
                        <col style="width:10%;">
                    </colgroup>
                    <thead>
                    <tr>
                        <th width="col" style="border-left:1px solid black; text-align:center;"><label style="font-size: 11px; color:white; font-weight: normal;">Name</label></th>
                        <th width="col" style="text-align:center;"><label style="font-size: 11px; color:white; font-weight: normal;">Club</label></th>
                        <th width="col" style="text-align:center;"><label style="font-size: 11px; color:white; font-weight: normal;">School</label></th>
                        <th width="col" style="text-align:center;"><label style="font-size: 11px; color:white; font-weight: normal;">Class</label></th>
                        <th width="col" style="text-align:center;"><label style="font-size: 11px; color:white; font-weight: normal;">Level</label></th>
                        <th width="col" class="last" style="border-right:1px solid black; text-align:center;"><label style="font-size: 11px; color:white; font-weight: normal;"></label></th>

                    </tr>
                    </thead>
                    <tbody>
                        <?php
                        foreach ($Char as $row) {
                            echo '<tr>
                                    <td class="format" style="padding:5px 0; border-bottom: 1px solid black;">'.$row['ChaName'].'</td>
                                    <td class="format" style="padding:5px 0; border-bottom: 1px solid black;">';
                                    
                                    $getBadge = $this->llllllllz_model->getGuild($row['GuNum']); 
                                    $GuNum = $this->encrypt->encode($row['GuNum']);
                                    $ChaNum = $this->encrypt->encode($row['ChaNum']);
                                    ?>
                                    <center>
                                        <table>
                                            <tr>
                                                <td class="badge " style="border-top:none;"><?=((!$getBadge) ? '':''.GenerateBadge(bin2hex($getBadge[0]['GuMarkImage'])).'')?></td>

                                                <td class="badge format" style="border-top:none;">&nbsp; <?=((!$getBadge) ? '<a style="cursor:default;color:#808080;text-decoration:none;">No Guild</a>':'<a href="'.base_url().'homepanel/club/'.$GuNum.'" style="text-decoration:none;">'.$getBadge[0]['GuName'].'</a>')?></td>
                                            </tr>
                                        </table>
                                    </center>             
                                    <?php 
                                    echo '</td>

                                    <td class="format" style="padding:5px 0; border-bottom: 1px solid black;">'.school1($row['ChaSchool']).'</td>
                                    <td class="format"style="padding:5px 0; border-bottom: 1px solid black;">'.ClassIMG($row['ChaClass']).'</td>
                                    <td class="format"style="padding:5px 0; border-bottom: 1px solid black;">'.$row['ChaLevel'].'</td>
                                    <td class="format"style="padding:5px 0; border-bottom: 1px solid black; font-size:15px;"><a href="'.base_url().'MyAccount/SelectChar/'.$ChaNum.'" title="Select Main Character"><i class="fas fa-caret-left"></i></a></td>
                                </tr>';
                        }
                        ?>
                        
                    </tbody>
                    </table>
                </td>
            </table>

            </tbody>
            </table>
        </section> 
    </section>

                    </section>
                    <div class="clear"></div>
                </div>
            </div>
        </div>
    </div>


<script>
        $('#btnAccountFix').click(function() {
            if (IsAuth == 'False') {
                alert('Please try again after logging in..');
                location.href = '<?=base_url()?>Login';
                return false;
            }
            Common.OpenCenterWindow(500, 412, '<?=base_url()?>MyAccount/AccountFix', 'Account Fix', false);
        });
</script>


<script>
        $('#btnGTtoGTP').click(function() {
            if (IsAuth == 'False') {
                alert('Please try again after logging in..');
                location.href = '<?=base_url()?>Login';
                return false;
            }
            Common.OpenCenterWindow(500, 412, '<?=base_url()?>MyAccount/GTtoGTP', 'Gametime to Gametime Points', false);
        });
</script>

<script>
        $('#btnGTPtoVP').click(function() {
            if (IsAuth == 'False') {
                alert('Please try again after logging in..');
                location.href = '<?=base_url()?>Login';
                return false;
            }
            Common.OpenCenterWindow(500, 412, '<?=base_url()?>MyAccount/GTPtoVP', 'Gametime Points to Vote Points', false);
        });
</script>

<script>
        $('#btnEPtoVP').click(function() {
            if (IsAuth == 'False') {
                alert('Please try again after logging in..');
                location.href = '<?=base_url()?>Login';
                return false;
            }
            Common.OpenCenterWindow(500, 412, '<?=base_url()?>MyAccount/EPtoVP', 'E-Points to Vote Points', false);
        });
</script>