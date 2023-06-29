<title><?=((count($GSet) > 0) ? "".$GSet[0]['ServerTitle']."":"")?> | Gift Code</title>
<body  class="sub-body">
    <div class="in-body4">
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
                        
<form method="post" action="<?=base_url()?>jrgapi/MasterPage/GetGiftCode" >

    <section class="page-history">
		HOME<img src="<?=base_url()?>/Images/Icon/navi_icon.gif">
        ITEM SHOP<img src="<?=base_url()?>/Images/Icon/navi_icon.gif">
		<span class="page-history-select">CLAIM EVENT</span>
	</section>

	<h3 class="sub-title">
	     <img src="<?=base_url()?>/Images/SubTitle/Itemshop/claimevent_sub_title.gif" alt="Claim events">
	</h3>
	<div class="clear"></div>
	<div class="contentMiddle">

			<!--contentBox-->
			<div class="contentBox">


				<div class="rcoinevent_top_box">
					<div class="rcoinevent_day">
						<div class="rcoinevent_day_box">
							<!-- <span><img src="<?=base_url()?>/images/rcoinevent/icon.png"/></span> -->
						</div>
					</div>
					<!-- Precautions -->
					<div class="rcoinevent_notice">
						<div class="rn_box_t"></div>
						<div class="rn_box_c">
							<div class="rn_box_c_box">
								<div class="rn_box_c_tit">
                                    <center>
                                    <div id='Notif'>
                                        <?php
                                            if ($this->session->flashdata('message')) {
                                            echo $this->session->flashdata('message');     
                                            }
                                        ?>
                                    </div>
                                    </center>
                                </div>
								<div class="rn_box_c_txt">
								    <center>
                                    <input type="text" name="code" id="code" placeholder="Code" maxlength="6" style="text-align: center"/>
                                    </center>
                                </div>
							</div>
						</div>
						<div class="rn_box_b"></div>
					</div>
				</div>
				
				<div class="rcoinevent_step">
					<div class="rcoinevent_step_top"></div>
					
				
				</div>

				<div class="rcoinevent_getall">
					<div class="getall_btn">
                        
					<div class="getall_txt">
					
					</div>
				</div>


			</div>
			<!--//contentBox-->





		</div><!-- //contentMiddle -->

</form>

                    </section>
                    <div class="clear"></div>
                </div>
            </div>
        </div>
    </div>
 

    <script>
        setTimeout(function(){
            $('#Notif').fadeOut();
        }, 3000);
    </script>