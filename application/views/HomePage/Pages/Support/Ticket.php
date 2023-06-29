<title><?=((count($GSet) > 0) ? "".$GSet[0]['ServerTitle']."":"")?> | <?=$this->uri->segment(2)?></title>

<body  class="sub-body">
    <div class="in-body5">
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
		SUPPORT
		<img src="<?=base_url()?>Images/ico_navi.gif" alt="">
		<span class="page-history-select">MY TICKET</span>
	</section>
	<h3 class="sub-title">
        <img src="<?=base_url()?>Images/SubTitle/Support/support_sub_title.gif" alt="Support" class="left">
	    <div class="clear"></div>
	</h3>	
	<section class="board-view">
		<section class="view-subject">
			<span id="subject" class="subject"><?=$Tickets[0]['TicketNumber']?></span>
		</section>
		<section class="view-info">
			<span class="view-info-title"><?=$Tickets[0]['TicketTitle']?></span>
			<p class="right">
				<span class="date">Date Created: <?=formatdate52($Tickets[0]['TicketDateTime'])?> Time: <?=formattime1($Tickets[0]['TicketDateTime'])?></span>
			</p>
			<div class="clear"></div>
		</section>
		<section class="content-view">
            <article>
                <?=$Tickets[0]['TicketContent']?>
            </article>
        </section>


        <?php
        $TReply = $this->Internal_model->GetTicketReply($Tickets[0]['TicketNumber']);

        


        foreach ($TReply as $rly) {
            $points = $this->llllllllz_model->getpoints($rly['UserID']);

                        if($points){
                            $ChaInfo = $this->llllllllz_model->getChaNumInfo($points[0]['ChaNum']);
                            
                            if($points[0]['ChaNum'] == NULL){
                                $ChaInfo2 = $this->llllllllz_model->getChaInfo($rly['UserID']);
                                if(!$ChaInfo2){
                                    $ChaName = 'No Name';
                                } else {
                                    $ChaName = $ChaInfo2[0]['ChaName'];
                                }
                            } else {
                                if(!$ChaInfo){
                                    $ChaName = 'No Name';
                                } else {
                                    $ChaName = $ChaInfo[0]['ChaName'];
                                }
                            }
                        } else {
                            $ChaInfo = $this->llllllllz_model->getChaInfo($rly['UserID']);
                            if(!$ChaInfo){
                                $ChaName = 'No Name';
                            } else {
                                $ChaName = $ChaInfo[0]['ChaName'];
                            }
                        }


            if($rly['UserID'] == 0){
                echo '<div class="reply-list-wrapper">
                        <div class="reply-list">
                            <div class="reply">
                                <div class="reply-info">
                                    <div class="action"><b class="dodgerblue" style="font-size:12px;">Administrator</b></div>
                                    <div class="date">Date: '.formatdate52($rly['DateTimeStamp']).' Time: '.formattime1($rly['DateTimeStamp']).'</div>
                                </div>
                                <div class="reply-content">
                                    <p>
                                    '.$rly['Value'].'
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>';
            }elseif($rly['UserID'] != 0){
                echo '<div class="reply-list-wrapper-right">
                        <div class="reply-list-right">
                        <div class="reply">
                                <div class="reply-info">
                                    <div class="action"><b class="mediumseagreen" style="font-size:12px;">'.$ChaName.'</b></div>
                                    <div class="date">Date: '.formatdate52($rly['DateTimeStamp']).' Time: '.formattime1($rly['DateTimeStamp']).'</div>
                                </div>
                                <div class="reply-content">
                                    '.$rly['Value'].'
                                </div>
                            </div>
                        </div>
                    </div>';
            }
        }
        ?>


        <br />
        <div class="re-reply-write"> 
            <form method="POST">
            <center>
            <input type="hidden" name="TicketNumber" value="<?=$Tickets[0]['TicketNumber']?>">
            <textarea name="TicketReply" class="txt-reply" style="height: 60px;"></textarea> &nbsp;
             <input type="image" src="<?=base_url()?>Images/Button/post_btn2.gif" alt="btnPost" style="vertical-align:baseline;" class="pointer" alt="Post">
             </center>
             </form>
        </div>

       
       


        


		<div class="text-center">
		</div>
		<section class="content-copy">
			<div class="right">
				<img src="<?=base_url()?>Images/Icon/facebook_icon.gif" id="btnFacebook" class="pointer" alt="Facebook">
				<img src="<?=base_url()?>Images/Icon/twitter_icon.gif" id="btnTwitter" class="pointer" alt="Twitter">
			</div>
			<div class="clear"></div>
		</section>

		<div class="clear"></div>
	
	
	</section>

                    </section>
                    <div class="clear"></div>
                </div>
            </div>
        </div>
    </div>
   
  