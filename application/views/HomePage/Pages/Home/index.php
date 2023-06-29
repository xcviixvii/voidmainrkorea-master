<title><?=((count($GSet) > 0) ? "".$GSet[0]['ServerTitle']."":"")?></title>
<body class="body">
	<div class="in-body">
		<div class="bottom-body">
			<div id="wrapper">
				<?php
				renderview('HomePage/Templates/tmpheader');
				?>
				<div id="container">
					<aside id="nav">
						<!-- CALENDAR-->
						<section class="calendar">
							<?php
                            renderview('HomePage/Pages/Home/GetCalendarNow');
							?>
						</section>

						<!-- friend list and club list -->
						<section class="relation">
							<header>
								<img src="<?=base_url()?>/Images/Main/relation_main_title.gif" alt="Connection Status">
								<ul class="tabs">
									<li class="friends">
										<span id="friend" class="on">Friend</span>
									</li>
									<li class="club">
										<span id="club" class="off">Club</span>
									</li>
								</ul>
							</header>
							<div class="clear"></div>
							<ul id="relation-list" class="relation-list">
								<li class="nothing">Login to see my friends and club members<br>You can check the connection status.</li>
							</ul>
							<div class="relation-count">
								<p class="count left">
									<span class="state-on" id="page-no">0</span> / <span id="total-page">0</span>
								</p>
								<div id="page-btn1" class="right display-none" style="display: none;">
									<img src="<?=base_url()?>Images/Button/relation_prev_btn.gif" id="btnFPrev" class="pointer" alt="prev">
									<img src="<?=base_url()?>Images/Button/relation_next_btn.gif" id="btnFNext" class="pointer" alt="next">
								</div>
								<div id="page-btn2" class="right display-none" style="display: none;">
									<img src="<?=base_url()?>Images/Button/relation_prev_btn.gif" id="btnGPrev" class="pointer" alt="prev">
									<img src="<?=base_url()?>Images/Button/relation_next_btn.gif" id="btnGNext" class="pointer" alt="next">
								</div>
								<div class="clear"></div>
							</div>
						</section>

						<section class="left-ranking">
							<table id="left-ranking">
								<caption class="display-block">
									<figure>

										<div class="title">
											<div class="left"><img src="<?=base_url()?>Images/Ranking/ranking_title.gif" alt="이벤트달력"></div>
											<div class="clear"></div>
										</div>
									</figure>
								</caption>
								<thead>
									<tr class="blankline2">
										<td class="blankcell" colspan="13"></td>
									</tr>

									<tr>
										<td><img src="<?=base_url()?>Images/Ranking/ranking_grade.gif" alt="순위"></td>
										<td><img src="<?=base_url()?>Images/Ranking/ranking_name.gif" alt="캐릭터명"></td>
										<td><img src="<?=base_url()?>Images/Ranking/ranking_class.gif" alt="소속부서"></td>
									</tr>
								</thead>
								<tbody class="left-ranking-list">
									<?php
									$ctr = 1;
									foreach ($ranking as $row) {
										echo '<tr>
										<td><img src="'.base_url().'Images/ranking/rank'.$ctr.'.gif" alt="Rank '.$ctr.'"></td>
										<td class="PlayerName">'.$row['ChaName'].'</td>
										<td>'.ClassIMG($row['ChaClass']).'</td>
										</tr>';

										$ctr++;
									}
									?>


								</tbody>
								<tfoot>
									<tr class="blankline2">
										<td class="blankcell" colspan="13"></td>
									</tr>
								</tfoot>
							</table>
						</section>



						<section class="club-holder">
							<table>
								<caption class="display-block">
									<figure>
										<div class="title">
											<div class="left"><img src="<?=base_url()?>Images/Club/club_title.gif" alt="이벤트달력"></div>
											<div class="clear"></div>
										</div>
									</figure>
								</caption> 
								<thead>
									<tr class="blankline2">
										<td class="blankcell" colspan="13"></td>
									</tr>

									<tr>
										<td><img src="<?=base_url()?>Images/Club/club_area.gif" alt="area"></td>
										<td><img src="<?=base_url()?>Images/Club/club_name.gif" alt="name"></td>
										<td><img src="<?=base_url()?>Images/Club/club_rate.gif" alt="rate"></td>
									</tr>
								</thead>
								<tbody class="club-holder-list">
									<?php
									$ctr = 1;
									$lead = "";
									$tax = "";
									$link = "";
									foreach (Club() as $Club) {


										if(!$ClubLeader){

										}else {
											$ClubName = $this->llllllllz_model->getClubName($ClubLeader[0]['GuNum']);
                                    //$GuNum = $this->encrypt->encode($ClubName[0]['GuNum']);
											$GuNum = $this->encrypt->encode($ClubName[0]['GuNum']);


											if($ctr == $ClubLeader[0]['RegionID']){
												$lead = $ClubName[0]['GuName'];
												$tax = $ClubLeader[0]['RegionTax'].'%';

												$link = '<a href="'.base_url().'/club/clubdetails/'.$GuNum.'" style="text-decoration:none;">'.$lead.'</a>';
											} else {

											}

										}

										if($lead){
											echo '<tr>
											<td>'.$Club.'</td>
											<td>'.$link.'</td>
											<td>'.$tax.'</td>
											</tr>';
										} 

										$ctr++;
									}


									?> 
								</tbody>
								<tr class="blankline2">
									<td class="blankcell" colspan="13"></td>
								</tr>
							</table>
						</section> 

					</aside>
					<div id="main">
						<!-- 메인 콘텐츠 -->
						<div id="main-content">
							<!-- 변화의 서막 배너 -->
							<!--
                            <section style="margin-bottom:10px;"><a href="/LostIsland/"><img src="<?=base_url()?>Images/LostIsland/lostisland_banner.jpg" alt=""></a></section>
                        -->
                        <section class="main-news">
                        	<caption class="display-block">
                        		<figure class="newsborder">
                        			<div class="title">
                        				<div class="left"><img src="<?=base_url()?>Images/News/news_title.gif" alt="News"></div>

                        				<div class="clear"></div>
                        			</div>
                        		</figure>
                        	</caption>
                        	<div class="main-news-content">
                        		<!--<div class="main-news-blank"></div>-->

                        		<ul>
                        			<?php
                        			foreach ($notice as $row) {
                        				$string = strip_tags($row['newscontent']);

                        				echo '
                        				<li class="main-new-list">
                        				<span class="main-news-thumb">
                        				<a href="'.base_url().'News/View/'.$row['newsid'].'"><img src="'.base_url().'/Uploadfiles/News/'.$row['banner'].'"></a>
                        				</span>
                        				<dl class="news-content">
                        				<dt class="titlenews">
                        				<a href="'.base_url().'News/View/'.$row['newsid'].'">'.NewsType($row['type']).' '.$row['newstitle'].'
                        				</a>
                        				</dt>
                        				<dd class="date">'.formatdate1($row['datestamp']).'</dd>

                        				</dl>
                        				</li>';
                        			}
                        			?>
                        			<!-- <img src="'.base_url().'assets/icons/new_icon.gif" > -->
                        		</ul>
                        	</div>
                        </section>




                        <section class="main-item-shop">
                        	<ul class="main-tabs">
                        		<li><img src="<?=base_url()?>Images/Main/newitem_tab_on.gif" id="main-item-header1" class="main-item-header pointer" alt="New Item"></li>
                        		<li><img src="<?=base_url()?>Images/Main/gmitem_tab_off.gif" id="main-item-header2" class="main-item-header pointer" alt="GM Pick"></li>
                        	</ul>
                        	<div class="clear"></div>
                        	<section id="main-item1" class="main-item-wrapper">
                        		<div class="main-item-box">
                        			<div class="inner-box">
                        				<section id="slides1" class="slides">
                        					<div id="main-itemshop1" class="slides_container">

											<?php

											$i = 1;
											foreach (array_chunk($NewItem, 4, true) as $array) {
												
												echo '<div class="slide">
														<ul class="main-item-list">';
												foreach($array as $item) {
													echo '<li>
														<a href="'.base_url().'ItemShop/ItemFind/'.$item['ItemName'].'"><img src="'.base_url().'Uploadfiles/ItemShop/'.$item['ItemSS'].'" alt="" width="72" height="72"></a>
														<p class="item-title"><a href="'.base_url().'ItemShop/ItemFind/'.$item['ItemName'].'" title="'.$item['ItemName'].'">'.$item['ItemName'].'</a></p>
														</li>';
													$i++;
												}
												echo '</ul>
												</div>';
											}
											?>
                        					</div>
                        					<a href="#" class="prev"><img src="<?=base_url()?>Images/Button/main_itemprev_btn.gif" id="Img1" alt="Preview"></a>
                        					<a href="#" class="next"><img src="<?=base_url()?>Images/Button/main_itemnext_btn.gif" id="Img2" alt="Next"></a>
                        				</section>
                        			</div>
                        		</div>
                        	</section>





                        	<section id="main-item2" class="main-item-wrapper display-none">
                        		<div class="main-item-box">
                        			<div class="inner-box">
                        				<section id="slides2" class="slides">
                        					<div id="main-itemshop2" class="slides_container">
                        					</div>
                        					<a href="#" class="prev"><img src="<?=base_url()?>Images/Button/main_itemprev_btn.gif" id="Img3" alt="Preview"></a>
                        					<a href="#" class="next"><img src="<?=base_url()?>Images/Button/main_itemnext_btn.gif" id="Img4" alt="Next"></a>
                        				</section>
                        			</div>
                        		</div>
                        	</section>
                        	<section class="main-itemsearch-wrapper">
                        		<fieldset>
                        			
                        		</fieldset>
                        	</section>
						</section>
						
                        <section class="main-fbpage">
                        	<header class="main-title-header">
                        		<a href="#" class="link3">FACEBOOK </a>
                        	</header>
                        	<div class="clear"></div>
                        	<section id="main-fbpage">
                        		<figure>
                        			<script src="https://connect.facebook.net/en_US/all.js#xfbml=1"></script>
                        			<fb:like-box href="<?=((count($GSet) > 0) ? "".$GSet[0]['FacebookPage']."":"")?>" width="458" height="185" colorscheme="dark" show_faces="true" border_color="#000000" stream="false" header="false"></fb:like-box>  <!-- your Facebook Page -->
                        		</figure>            
                        	</section>
                        </section>


						<section class="main-fbpage">
                        	<header class="main-title-header">
                        		<a href="#" class="link3">SHARE </a>
                        	</header>
                        	<div class="clear"></div>
                        	<section id="main-fbpage">
                        		<figure>
                        		<?php
								$new_time = "";
								
								foreach ($Vote as $row) {
									
									if($this->session->userdata('IsAuth')){
										$UserName = $this->Internal_model->GetUserUserName($this->session->userdata('UserID'));
										$NextVote = $this->Internal_model->GetVoteLogs($UserName,$row['VoteID']);
										if($NextVote){
											$new_time = date("Y-m-d H:i:s", strtotime('+'.$row['VoteTime'].' Hour', strtotime($NextVote[0]['LastVoteDatetime']))); 
											if($new_time > currentdatetime()){
												$timelabel = '<small>Next Share : <b>'.formattime2($new_time).'</b></small>';
											} else {
												$timelabel = '<small style="color:green"><center><b>Earn '.$row['VotePoints'].' Share Points</b></center></small>';
											}
											
										} else {
											$timelabel = '<small style="color:green"><center><b>Earn '.$row['VotePoints'].' Share Points</b></center></small>';
										}
									} else {
										$timelabel = "";
									}
									
									echo '
									<div style="width:140px; float:left; margin-left:8px; margin-bottom:5px;">
									<a style="cursor:pointer;" id="Vote'.$row['VoteID'].'" onclick="ShareMoto('.$row['VoteID'].')" rel="'.$row['VoteUrl'].'"> 
										<img class="img-thumbnail" src="'.base_url().'Images/Flash/Images/hqdefault.jpg" border="0" width="140px;" > 
									</a>
									'.$timelabel.'
									
									</div>
									';
								}
								?>
								<div style="clear:both;"></div>
                        		</figure>            
                        	</section>
                        </section>



						


						<!-- <section class="main-fbpage">
                        	<header class="main-title-header">
                        		<a href="#" class="link3">VOTE </a>
                        	</header>
                        	<div class="clear"></div>
                        	<section id="main-fbpage">
                        		<figure>
								<center>

								</center>
								</figure>            
                        	</section>
                        </section> -->
					<!-- https://gtop100.com/topsites/Ran-Online/sitedetails/SN-Gaming-Network-93564?vote=1 -->

					<?php
					if($this->Internal_model->GetConquerorPathDayCount() > 0){
						?>
						<section class="main-conqueror">
                        	<header class="main-title-header">
                        		<a href="#" >CONQUEROR'S <b style="color:black;">PATH</b></a>
							</header>
							
							<div class="clear"></div>
							<div style="margin-left:20px;">
                        	<section id="main-conqueror" style="float:left;">
                        		<figure>
								<table  style="font-size:12px;">
								<thead>
									<tr>
											<td><img src="<?=base_url()?>Images/Ranking/ranking_grade.gif" alt="#"></td>
											<td><img src="<?=base_url()?>Images/Club/club_name.gif" alt="NAME"></td>
											<td><img src="<?=base_url()?>Images/ConquerorPath/conqueror_score.gif"></td>
										</tr>
								</thead>
								<tbody class="conqueror-left-list">
								 <?php
									$ctr = 0;
									foreach ($Score as $row) {
										$getBadge = $this->llllllllz_model->getGuild($row['GuNum']);
										$ctr++;

										if($ctr == 1){
											$score = '<b style="color:#ffd700;">'.$row['Score'].'</b>';
										} elseif($ctr == 2){
											$score = '<b style="color:#c0c0c0;">'.$row['Score'].'</b>';
										} elseif($ctr == 3){
											$score = '<b style="color:#8b4513;">'.$row['Score'].'</b>';
										} else {
											$score = '<b>'.$row['Score'].'</b>';
										}

										echo '<tr>
										<td align="center"><img src="'.base_url().'Images/ranking/rank'.$ctr.'.gif" alt="Rank '.$ctr.'"></td>
										<td>';
										?>
										<ul id="GenBadge" style="padding-left: 0px;margin-bottom: 0px;padding-bottom: 0px;">
										<li>
											<?=((!$getBadge) ? '':''.GenerateBadge1(bin2hex($getBadge[0]['GuMarkImage'])).'')?>
										</li>
										<li>
											<?=((!$getBadge) ? '':''.$getBadge[0]['GuName'].'')?>
										</li>
										</ul>
										<?php
										echo '</td>
										<td align="center">'.$score.'</td>
										</tr>';
									}
								?>
								</tbody>
								</table>
                        		</figure>            
							</section>
							<?php
									renderview('HomePage/Pages/Home/GetConquerorsPath');
								?>
								</div>
                        </section>
						<?php
					} else {

					}
					?>

					
					</div>
					


                    <aside id="aside">

                    	<section id="qucik-banner">

                    		<ul>
                                    <!--
									<li><a href="/chagift/"><img src="<?=base_url()?>Images/Main/right_banner_character_gift_181212.png" alt="Gift a character" /></a></li>
									
                                    <li><a href="<?=base_url()?>Community/CommunityView.aspx?PageNo=1&Category=50&Idx=22973&SearchFlag=1&SearchString="><img src="<?=base_url()?>Images/Main/right_side_banner_flag_150601.png" alt="Flag Battle Update" /></a></li>
                                -->

                                <li><a href="<?=((count($GSet) > 0) ? "".$GSet[0]['FacebookPage']."":"")?>" target="_blank"><img src="<?=base_url()?>Images/Facebook/qr_code_btn.gif" alt="Ran online Facebook"></a></li>


                                <li><a href="#"><img src="Images/Main/right_banner_anomalyreport_180905.png" alt="Ran Online Illegal Report" /></a></li>

                            </ul>
                            
                        </section>

                        <section class="main-movie">
                        	<header class="title-bar">
                        		<div class="title"><img src="<?=base_url()?>Images/Movie/media_title.gif" alt="Movie Title"></div>
                        	</header>
                        	<div id="movie" style="z-index:1">
							<?php
								if($this->Internal_model->GetMediaAds()){
									echo $this->Internal_model->GetMediaAds();
								}
							?>
							
							        <!--script type="text/javascript">
							            Common.flashObject('<?=base_url()?>Images/Flash/mini_player.swf?mvUrl=<?=base_url()?>Images/Movie/EP9_Movie_mini_130612.flv&seekTime=18', '', 220, 124);
							        </script-->							        
							        <!--<iframe width="220" height="124" src="//www.youtube.com/embed/wjO-6ju2amc?wmode=transparent&controls=1&showinfo=1&autoplay=0" frameborder="0" allowfullscreen womde="Opaque"></iframe>-->
							        <!-- <iframe width="220" height="140" src="https://www.youtube.com/embed/ko-DOenNNfk" frameborder="0" allow="accelerometer; autoplay; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe> -->
									<!--<iframe src="https://www.facebook.com/plugins/video.php?href=https%3A%2F%2Fwww.facebook.com%2FRanWorld%2Fvideos%2F10154048903171463%2F&show_text=0&width=220" width="220" height="124" style="border:none;overflow:hidden" scrolling="no" frameborder="0" allowTransparency="true" allowFullScreen="true"></iframe>
										<iframe을 폭 = "220"높이 = "124" SRC = "http://www.facebook.com/v/1107074953447"frameborder = "0"으로하고 all​​owFullScreen> </ iframe>-->

							</div>
							</section>

						



								<section class="wars-info display-none"> <!-- style="display: none;" -->
									<table>
										<caption class="display-block">
											<figure>
												<div class="title">
													<div class="left"><img src="<?=base_url()?>Images/WarInfo/warinfo_title.gif"></div>
													<div class="clear"></div>
												</div>
											</figure>
										</caption> 

										<thead>
											<tr class="blankline2">
												<td class="blankcell" colspan="13"></td>
											</tr>
											<tr>
												<td>Wars</td>
											</tr>
											<tr class="blankline2">
												<td class="blankcell" colspan="13"></td>
											</tr>
										</thead>

									</table>
								</section>


								<?php renderview('homepage/templates/serverinformation')?>
								

								<?php
								if($this->Internal_model->GetCapsuleStatus() == 1){
								
								
								?>
								<section class="main-advertise-banner">
											<header class="main-title-header">
												<a style="cursor:pointer;" id="btncapsuleshop" class="link3">CAPSULE SHOP </a>
											</header>
											<div class="clear"></div>
											<figure>
												<a style="cursor:pointer;" id="btncapsuleshop1" title="Capsule Shop">
													<img src="<?=base_url()?>Image/CAPSULESHOP-218.png">
												</a>
											</figure>
										</section>
								<script>
								$('#btncapsuleshop').click(function() {
									if (IsAuth == 'False') {
										alert('Please try again after logging in..');
										location.href = '<?=base_url()?>Login';
										return false;
									}

									if(<?=$this->Internal_model->GetPlayerChaLevel($this->session->userdata('UserID'),$this->Internal_model->GetCapsuleReq())?> < 1){
										alert('Sorry Your Level Did not Reach The Level Requirements atleast Level <?=$this->Internal_model->GetCapsuleReq()?>+');
										// location.href = '<?=base_url()?>Login';
										return false;	
									}
									Common.OpenCenterWindow(800, 600, '<?=base_url()?>CapsuleShop', 'CapsuleShop', false);
								});

								$('#btncapsuleshop1').click(function() {
									if (IsAuth == 'False') {
										alert('Please try again after logging in..');
										location.href = '<?=base_url()?>Login';
										return false;
									}

									if(<?=$this->Internal_model->GetPlayerChaLevel($this->session->userdata('UserID'),$this->Internal_model->GetCapsuleReq())?> < 1){
										alert('Sorry Your Level Did not Reach The Level Requirements atleast Level <?=$this->Internal_model->GetCapsuleReq()?>+');
										// location.href = '<?=base_url()?>';
										return false;	
									}
									Common.OpenCenterWindow(800, 600, '<?=base_url()?>CapsuleShop', 'CapsuleShop', false);
								});
								</script>
								<?php
								}
								?>
								
<!-- 
								<section class="main-devnote">
									<header class="main-title-header">
										<a href="#" class="link1">DEVELOPMENT </a>
										<a href="#" class="right" style="margin-top: 0px;">MORE</a>
									</header>
									<div class="clear"></div>
									<section id="devnote">
										<figure>
											<a href="/Community/CommunityView.aspx?Category=50&Idx=25054"><img src="<?=base_url()?>UploadFiles/DevNote/(KR)_2017_Cataclysm_part04_note.png" width="218" height="100" alt="">
											</a>
											<figcaption>
												<section>
													<header>
														<a href="/Community/CommunityView.aspx?Category=50&Idx=25054">Link Development Note</a>
													</header>
													<article>
														<a href="/Community/CommunityView.aspx?Category=50&Idx=25054"></a>
													</article>
												</section>
											</figcaption>
										</figure>
									</section>
								</section> -->


								

								<section class="main-screenshot">
									<header class="main-title-header">
										<a href="#" class="link2">BEST </a>
										<a href="#" class="right" style="margin-top: 0px;">MORE</a>
									</header>
									<div class="clear"></div>
									<section id="bestscreen">

										<!-- <figure >
											<a href="#"><img src="<?=base_url()?>UploadFiles/ScreenShot/Thumb/201804100638324909.jpg" width="103" height="73" alt=""></a>
											<figcaption>
												<span class="screensubject">
													<a href="/Community/CommunityView.aspx?Category=40&Idx=26065">April Spring Fashion Event</a>
												</span>
												<span class="nickname">Dejabu</span>
											</figcaption>
										</figure>

										<figure class="last">
											<a href="#"><img src="<?=base_url()?>UploadFiles/ScreenShot/Thumb/201804050619044403.jpg" width="103" height="73" alt=""></a>
											<figcaption>
												<span class="screensubject">
													<a href="/Community/CommunityView.aspx?Category=40&Idx=26015">April Spring Fashion Participation</a>
												</span>
												<span class="nickname">After that, oh...</span>
											</figcaption>
										</figure> -->

										<div class="clear"></div>
									</section>
								</section>

								<section class="main-advertise-banner">
									<header class="main-title-header">
										<a href="#" class="link3">ADVERTISEMENT </a>
									</header>
									<div class="clear"></div>
									<figure>
										<!-- <script async src="https://pagead2.googlesyndication.com/pagead/js/adsbygoogle.js"></script> -->
										<!-- RanAds -->
										<ins class="adsbygoogle"
											style="display:block"
											data-ad-client="ca-pub-2624054502426779"
											data-ad-slot="1363475644"
											data-ad-format="auto"
											data-full-width-responsive="true"></ins>
										<script>
											(adsbygoogle = window.adsbygoogle || []).push({});
										</script>
									</figure>
								</section>

							</aside>
						</div>
						<div class="clear"></div>
					</div>



				</div>
			</div>
		</div>


		<!-- HTML for calendar event list output -->
		<div id="calendar-event" class="display-none">
			<section class="calendar-event-wrapper">
				<header>
					<span id="event-date" class="event-date left"></span>
					<span class="right" style="margin-top: 7px;">
						<img src="<?=base_url()?>Images/Button/calendar_close_btn.gif" id="calendar-event-close" class="pointer" alt="Close">
					</span>
				</header>
				<dl id="calendar-event-list">
				</dl>
				<div class="more" style="font-size: 10px;">
					<a href="<?=base_url()?>News/Notice">MORE</a>
				</div>
			</section>
		</div>
		<!-- HTML for calendar event list output -->

		<script type="text/javascript">

			$("#txtID").change(function () {
				$('#txtID').css('background-image', 'none');
			});
			$("#txtPW").change(function () {
				$('#txtPW').css('background-image', 'none');
			});
		</script>

