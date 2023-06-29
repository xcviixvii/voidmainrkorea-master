<title><?=((count($GSet) > 0) ? "".$GSet[0]['ServerTitle']."":"")?> | Notice</title>
<?php
$views = $data[0]['views'];
$id = $data[0]['newsid'];
if($this->session->userdata('IsAuth') == 'True'){
    $viewscounter = $views + 1;
    $data2 = array(
        'views' => $viewscounter
    );   
    $this->llllllllz_model->updatenews($id,$data2);
}

?>
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
		NEWS
		<img src="<?=base_url()?>Images/ico_navi.gif" alt="">
		<span class="page-history-select">NOTICE</span>
	</section>
	<h3 class="sub-title">
		<?php
			if($data[0]['type'] == 1){
				echo '<img src="'.base_url().'Images/SubTitle/News/notice_sub_title.gif" alt="NOTICE" class="left">';
			} elseif($data[0]['type'] == 2){
				echo '<img src="'.base_url().'Images/SubTitle/News/update_sub_title.gif" alt="UPDATE" class="left">';
			} elseif($data[0]['type'] == 3){
				echo '<img src="'.base_url().'Images/SubTitle/News/event_sub_title.gif" alt="EVENT" class="left">';
			}
		?>
		
	    <div class="clear"></div>
	</h3>	
	<section class="board-view">
		<section class="view-subject">
			<span class="no"><?=$data[0]['newsid']?></span>
			<span id="subject" class="subject"><?=$data[0]['newstitle']?></span>
		</section>
		<section class="view-info">
		
			<p class="right">
				<span class="view-info-title">Date Created:</span>
				<span class="date"><?=formatdate52($data[0]['datestamp'])?> Time: <?=formattime1($data[0]['datestamp'])?></span>
				<span class="selector">ㅣ</span>
				<span class="view-info-title">Views: </span>
				<span class="view"><?=$data[0]['views']?></span>
            
			</p>
			<div class="clear"></div>
		</section>
		<section class="content-view">
		    
            <article>
                <?=$data[0]['newscontent']?>
            </article>
            
		</section>
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
   
  