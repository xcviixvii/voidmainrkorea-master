<?php

class LeftMenu extends CI_Controller {
	function __construct() {
		parent::__construct();
		$this->ci_minifier->init(2);
		
	}
	function LeftNavMyAccount() {
			renderview('LeftMenu/LeftNavMyAccount');
		
	}

	function LeftNav01() {
		$data['ClubLeader'] = $this->llllllllz_model->getClubLeader();
		$data['ranking'] = $this->llllllllz_model->gettop10ranking();
		renderview('LeftMenu/LeftNav01',$data);
		
	}

	function LeftNav02() {
		$data['ClubLeader'] = $this->llllllllz_model->getClubLeader();
		$data['ranking'] = $this->llllllllz_model->gettop10ranking();
		renderview('LeftMenu/LeftNav02',$data);
		
	}

	function LeftNav04() {
		$data['points'] = $this->llllllllz_model->getpoints($this->session->userdata('UserID'));
		$data['ClubLeader'] = $this->llllllllz_model->getClubLeader();
		$data['ranking'] = $this->llllllllz_model->gettop10ranking();
		renderview('LeftMenu/LeftNav04',$data);
		
	}

	function LeftNav05() {
		$data['ClubLeader'] = $this->llllllllz_model->getClubLeader();
		$data['ranking'] = $this->llllllllz_model->gettop10ranking();
		renderview('LeftMenu/LeftNav05',$data);
		
	}

	function LeftNav06() {
		$data['ClubLeader'] = $this->llllllllz_model->getClubLeader();
		$data['ranking'] = $this->llllllllz_model->gettop10ranking();
		renderview('LeftMenu/LeftNav06',$data);
		
	}

	function LeftNav07() {
		$data['ClubLeader'] = $this->llllllllz_model->getClubLeader();
		$data['ranking'] = $this->llllllllz_model->gettop10ranking();
		renderview('LeftMenu/LeftNav07',$data);
		
	}


	function LeftNav08(){
	?>
	<h2><img src="<?=base_url()?>Images/Leftnav/web_market_left_title.gif" alt="MarketPlace"></h2>
	<ul>
	<!-- <li><a href="<?=base_url()?>MarketPlace/Gold"><img id="sub-nav1" src="<?=base_url()?>Images/Leftnav/GoldMarket/goldmarket_nav_off.gif" alt="MarketPlace"></a></li>
	
	<li><a href="<?=base_url()?>MarketPlace/MyPost"><img id="sub-nav2" src="<?=base_url()?>Images/Leftnav/GoldMarket/post_nav_off.gif" alt="Post"></a></li>
	 -->
	<li class="left-nav">
		<a stype="cursor:pointer;"><img id="sub-nav1" src="<?=base_url()?>Images/Leftnav/Market/goldmarket_nav_off.gif" alt="MarketPlace"></a>
		<ul>
		<li id="depth1"><a href="<?=base_url()?>MarketPlace/Gold" style="cursor:pointer;" >Gold</a></li>
		</ul>
	</li>

	<li class="left-nav">
		<a stype="cursor:pointer;"><img id="sub-nav2" src="<?=base_url()?>Images/Leftnav/Market/market_place_nav_off.gif" alt="MarketPlace"></a>
		<ul>
		<li id="depth2"><a href="<?=base_url()?>MarketPlace/Items" style="cursor:pointer;">Items</a></li>
		<li id="depth3"><a href="<?=base_url()?>MarketPlace/Items2" style="cursor:pointer;" >Character</a></li>
		</ul>
	</li>
	<li><a href="<?=base_url()?>MarketPlace/MyPost"><img id="sub-nav5" src="<?=base_url()?>Images/Leftnav/Market/post_nav_off.gif" alt="Post"></a></li>
	</ul>
	<?php
		if($this->uri->segment(3) == 2){
			$Ctg = 1;
		} else {
			$Ctg = $this->uri->segment(3);
		}

		if($this->uri->segment(3) == 3){
			$Ctg = 2;
		} elseif($this->uri->segment(3) == 4){ 
			$Ctg = 2;
		}
	?>

	<script type="text/javascript">
		$(document).ready(function() {
        let Category = <?=$Ctg?>;
        if (Category != '0') {
            $('#sub-nav' + Category).attr('src', $('#sub-nav'  + Category).attr('src').replace('_off', '_on'));
            
            if(<?=$this->uri->segment(3);?> == 1){
                $('#depth1').addClass('select-menu');
            } else if(<?=$this->uri->segment(3);?> == 3){
                $('#depth2').addClass('select-menu');
            } else if(<?=$this->uri->segment(3);?> == 4){
                $('#depth3').addClass('select-menu');
            }
        }
		});
	</script>

	<?php
	}


	function LeftNav09(){
	?>
	<h2><img src="<?=base_url()?>Images/Leftnav/donate_left_title.gif" alt="Reference Room"></h2>
	<ul>
	<li><a href="<?=base_url()?>"><img id="sub-nav1" src="<?=base_url()?>Images/Leftnav/Donate/donate_nav_off.gif" alt="Donate"></a></li>
	
	</ul>


	<script type="text/javascript">
		var Category = <?=$this->uri->segment(3)?>;
		$(document).ready(function() {
			if (Category != "0") {
				$('#sub-nav'+Category+'').attr('src', $('#sub-nav'+Category+'').attr('src').replace('_off', '_on'));
			}
		});
	</script>

	<?php
	}


	function LeftNav10(){
	?>
	<h2><img src="<?=base_url()?>Images/Leftnav/support_left_title.gif" alt="Reference Room"></h2>
	<ul>
	<li><a href="<?=base_url()?>Support"><img id="sub-nav1" src="<?=base_url()?>Images/Leftnav/Support/support_nav_off.gif" alt="Support"></a></li>
	<li><a href="<?=base_url()?>Support/Ticket"><img id="sub-nav2" src="<?=base_url()?>Images/Leftnav/Support/my_ticket_nav_off.gif" alt="Support"></a></li>
	
	</ul>


	<script type="text/javascript">
		var Category = <?=$this->uri->segment(3)?>;
		$(document).ready(function() {
			if (Category != "0") {
				$('#sub-nav'+Category+'').attr('src', $('#sub-nav'+Category+'').attr('src').replace('_off', '_on'));
			}
		});
	</script>

	<?php
	}

}