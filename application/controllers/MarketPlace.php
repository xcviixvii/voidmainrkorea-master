<?php

class MarketPlace extends CI_Controller {

    function __construct() {
		parent::__construct();
		$this->ci_minifier->init(2);
    }
    
	function Gold($offset = 0) {
        
        $this->session->unset_userdata('stat');
		$this->session->unset_userdata('amount');

		$this->load->library('pagination');
		
		$config['full_tag_open'] = "<section class='paging'><ul>";
        $config['full_tag_close'] ="</ul></section>";
        $config['cur_tag_open'] = "<li class='on try' style='float:left;'>";
        $config['cur_tag_close'] = "</li>";
        $config['num_tag_open'] = "<li class='try' style='float:left;'>";
        $config['num_tag_close'] = "</li>";

        $config['prev_tag_open'] = "<li class='image' style='float:left;'>";
        $config['prev_tag_close'] = "</li>";
        $config['prev_link'] = '<img src="'.base_url().'Images/Board/prev_page_btn.gif" alt="">';

        $config['next_tag_open'] = "<li class='image' style='float:left;'>";
        $config['next_tag_close'] = "</li>";
        $config['next_link'] = '<img src="'.base_url().'Images/Board/next_page_btn.gif" alt="">';

        $config['first_tag_open'] = "<li class='image' style='float:left;'>";
        $config['first_tag_close'] = "</li>";
        $config['first_link'] = '<img src="'.base_url().'Images/Board/first_page_btn.gif" alt="">';
            
        $config['last_tag_open'] = "<li class='image' style='float:left;'>";
        $config['last_tag_close'] = "</li>";
        $config['last_link'] = '<img src="'.base_url().'Images/Board/last_page_btn.gif" alt="">';
		$config['base_url']=base_url().'MarketPlace/Gold/Page';
		$config['total_rows']=$this->Internal_model->GetPostMarketNumRows();
		$config['use_page_numbers'] = TRUE;
		$config['per_page']=15;
		$this->pagination->initialize($config);
		
		//$data['records']=$this->db->get('MarketPlace',$config['per_page'],$this->uri->segment(3));
		//$offset = ($this->input->get('MarketPlace')) ? ( ( $this->input->get('MarketPlace') - 1 ) * $config["per_page"] ) : 0;
		//$data["links"] = explode('&nbsp;', $this->pagination->create_links() );
		if($offset != 0){
	      $offset = (($offset - 1) * $config["per_page"] );
	    }
		$data['records'] = $this->Internal_model->GetPostMarket($config['per_page'],$offset);
		$data['pages']=$this->pagination->create_links();
	
        $data['GSet'] = $this->Internal_model->GetPanelSettings();
        $data['Slider'] = $this->Internal_model->GetSliderImage();
        renderhomebodyview('HomePage/Pages/MarketPlace/Gold/index',$data);
	}

    function page($offset = 0){
        $this->session->unset_userdata('stat');
		$this->session->unset_userdata('amount');

		$this->load->library('pagination');
		
		$config['full_tag_open'] = "<section class='paging'><ul>";
        $config['full_tag_close'] ="</ul></section>";
        $config['cur_tag_open'] = "<li class='on try' style='float:left;'>";
        $config['cur_tag_close'] = "</li>";
        $config['num_tag_open'] = "<li class='try' style='float:left;'>";
        $config['num_tag_close'] = "</li>";

        $config['prev_tag_open'] = "<li class='image' style='float:left;'>";
        $config['prev_tag_close'] = "</li>";
        $config['prev_link'] = '<img src="'.base_url().'Images/Board/prev_page_btn.gif" alt="">';

        $config['next_tag_open'] = "<li class='image' style='float:left;'>";
        $config['next_tag_close'] = "</li>";
        $config['next_link'] = '<img src="'.base_url().'Images/Board/next_page_btn.gif" alt="">';

        $config['first_tag_open'] = "<li class='image' style='float:left;'>";
        $config['first_tag_close'] = "</li>";
        $config['first_link'] = '<img src="'.base_url().'Images/Board/first_page_btn.gif" alt="">';
            
        $config['last_tag_open'] = "<li class='image' style='float:left;'>";
        $config['last_tag_close'] = "</li>";
        $config['last_link'] = '<img src="'.base_url().'Images/Board/last_page_btn.gif" alt="">';
		$config['base_url']=base_url().'MarketPlace/Page';
		$config['total_rows']=$this->Internal_model->GetPostMarketNumRows();
		$config['use_page_numbers'] = TRUE;
		$config['per_page']=15;
		$this->pagination->initialize($config);
		
		//$data['records']=$this->db->get('MarketPlace',$config['per_page'],$this->uri->segment(3));
		//$offset = ($this->input->get('MarketPlace')) ? ( ( $this->input->get('MarketPlace') - 1 ) * $config["per_page"] ) : 0;
		//$data["links"] = explode('&nbsp;', $this->pagination->create_links() );
		if($offset != 0){
	      $offset = (($offset - 1) * $config["per_page"] );
	    }
		$data['records'] = $this->Internal_model->GetPostMarket($config['per_page'],$offset);
		$data['pages']=$this->pagination->create_links();
	
        $data['GSet'] = $this->Internal_model->GetPanelSettings();
        $data['Slider'] = $this->Internal_model->GetSliderImage();
        renderhomebodyview('HomePage/Pages/MarketPlace/Gold/index',$data);
    }

    function Filter($offset = 0){
        if($_POST){
			$search_text = $_POST['selStat'];
			$search_amount = $_POST['selAmount'];
			

			switch($search_text){
			case "1":
				if($search_text == "1") $stat = "WHERE Stat = 1 ".(($search_amount == "All") ? "":"AND").""; 
				break;
			case "0":
				if($search_text == "0") $stat = "WHERE Stat = 0 ".(($search_amount == "All") ? "":"AND").""; 
				break;
			case "All":
				if($search_text =="All") $stat = "";
				break;
			}


			switch($search_amount) {
				case "1000000":
					if($search_amount == "1000000") $amount = "".(($search_text == "All") ? "WHERE":"")." Gold >= 1000000";
					break;
				case "10000000":
					if($search_amount == "10000000") $amount = "".(($search_text == "All") ? "WHERE":"")." Gold >= 10000000";
					break;
				case "50000000":
					if($search_amount == "50000000") $amount = "".(($search_text == "All") ? "WHERE":"")." Gold >= 50000000";
					break;
				case "100000000":
					if($search_amount == "100000000") $amount = "".(($search_text == "All") ? "WHERE":"")." Gold >= 100000000";
					break;
				case "500000000":
					if($search_amount == "500000000") $amount = "".(($search_text == "All") ? "WHERE":"")." Gold >= 500000000";
					break;
				case "All":
					if($search_amount =="All") $amount = "";
					break;
			}


			$this->session->set_userdata(array("stat"=>$stat));
			$this->session->set_userdata(array("amount"=>$amount));

		} else {
		        $stat = $this->session->userdata('stat');
		        $amount = $this->session->userdata('amount');
		      
        }


        $this->load->library('pagination');
		
		$config['full_tag_open'] = "<section class='paging'><ul>";
        $config['full_tag_close'] ="</ul></section>";
        $config['cur_tag_open'] = "<li class='on try' style='float:left;'>";
        $config['cur_tag_close'] = "</li>";
        $config['num_tag_open'] = "<li class='try' style='float:left;'>";
        $config['num_tag_close'] = "</li>";

        $config['prev_tag_open'] = "<li class='image' style='float:left;'>";
        $config['prev_tag_close'] = "</li>";
        $config['prev_link'] = '<img src="'.base_url().'Images/Board/prev_page_btn.gif" alt="">';

        $config['next_tag_open'] = "<li class='image' style='float:left;'>";
        $config['next_tag_close'] = "</li>";
        $config['next_link'] = '<img src="'.base_url().'Images/Board/next_page_btn.gif" alt="">';

        $config['first_tag_open'] = "<li class='image' style='float:left;'>";
        $config['first_tag_close'] = "</li>";
        $config['first_link'] = '<img src="'.base_url().'Images/Board/first_page_btn.gif" alt="">';
            
        $config['last_tag_open'] = "<li class='image' style='float:left;'>";
        $config['last_tag_close'] = "</li>";
        $config['last_link'] = '<img src="'.base_url().'Images/Board/last_page_btn.gif" alt="">';
		$config['base_url']=base_url().'MarketPlace/Filter';
		$config['total_rows']=$this->llllllllz_model->searchpostmarketnumrows($stat,$amount);
		$config['use_page_numbers'] = TRUE;
		$config['per_page']=15;
		$this->pagination->initialize($config);
		
		//$data['records']=$this->db->get('MarketPlace',$config['per_page'],$this->uri->segment(3));
		//$offset = ($this->input->get('MarketPlace')) ? ( ( $this->input->get('MarketPlace') - 1 ) * $config["per_page"] ) : 0;
		//$data["links"] = explode('&nbsp;', $this->pagination->create_links() );
		if($offset != 0){
	      $offset = (($offset - 1) * $config["per_page"] );
	    }
		$data['records'] = $this->llllllllz_model->searchpostmarket($config['per_page'],$offset,$stat,$amount);
		$data['pages']=$this->pagination->create_links();
	
        $data['GSet'] = $this->Internal_model->GetPanelSettings();
        $data['Slider'] = $this->Internal_model->GetSliderImage();
        renderhomebodyview('HomePage/Pages/MarketPlace/Gold/index',$data);
        

    }





    function MyPost($offset = 0){
        if(!$this->session->userdata('UserID')){
            ?>
            <script> 
            alert("please try again after logging in..") 
            location.href = '<?=base_url()?>';
            </script>
            <?php
        }


        $GetCNum = $this->Internal_model->GetAllChaNum($this->session->userdata('UserID'));

        $ChaNum = "";
        foreach ($GetCNum as $row) {
            $ChaNum .= $row['ChaNum'].',';
        }

        //echo rtrim($ChaNum, ',');
        //$ChaNumData = $this->Internal_model->GetGoldMarketPost(rtrim($ChaNum, ','));

        //exit;
        $this->session->unset_userdata('stat');
		$this->session->unset_userdata('amount');

		$this->load->library('pagination');
		
		$config['full_tag_open'] = "<section class='paging'><ul>";
        $config['full_tag_close'] ="</ul></section>";
        $config['cur_tag_open'] = "<li class='on try' style='float:left;'>";
        $config['cur_tag_close'] = "</li>";
        $config['num_tag_open'] = "<li class='try' style='float:left;'>";
        $config['num_tag_close'] = "</li>";

        $config['prev_tag_open'] = "<li class='image' style='float:left;'>";
        $config['prev_tag_close'] = "</li>";
        $config['prev_link'] = '<img src="'.base_url().'Images/Board/prev_page_btn.gif" alt="">';

        $config['next_tag_open'] = "<li class='image' style='float:left;'>";
        $config['next_tag_close'] = "</li>";
        $config['next_link'] = '<img src="'.base_url().'Images/Board/next_page_btn.gif" alt="">';

        $config['first_tag_open'] = "<li class='image' style='float:left;'>";
        $config['first_tag_close'] = "</li>";
        $config['first_link'] = '<img src="'.base_url().'Images/Board/first_page_btn.gif" alt="">';
            
        $config['last_tag_open'] = "<li class='image' style='float:left;'>";
        $config['last_tag_close'] = "</li>";
        $config['last_link'] = '<img src="'.base_url().'Images/Board/last_page_btn.gif" alt="">';
		$config['base_url']=base_url().'MarketPlace/MyPost';
		$config['total_rows']=$this->Internal_model->GetMyPostMarketNumRows(rtrim($ChaNum, ','));
		$config['use_page_numbers'] = TRUE;
		$config['per_page']=15;
		$this->pagination->initialize($config);
		
		if($offset != 0){
	      $offset = (($offset - 1) * $config["per_page"] );
	    }
		$data['records'] = $this->Internal_model->GetMyPostGoldMarket(rtrim($ChaNum, ','),$config['per_page'],$offset);
        $data['pages']=$this->pagination->create_links();
        


        $data['GSet'] = $this->Internal_model->GetPanelSettings();
        $data['Slider'] = $this->Internal_model->GetSliderImage();
        renderhomebodyview('HomePage/Pages/MarketPlace/Gold/MyPost',$data);

    }




    function userTransdoConfirm($marketid){
		$market = $this->llllllllz_model->getpostmarketbyid($marketid);
		$chanum = $market[0]['ChaNum'];

		$char = $this->llllllllz_model->getchanum($chanum);
		$CheckEP = $this->llllllllz_model->getpoints($this->session->userdata('UserID'));
		$CKLocker = $this->llllllllz_model->getlockermoney($this->session->userdata('UserID'));

		//LOCKER MONEY
		$lockmoney = $CKLocker[0]['UserMoney'];
		//BUYER SELLER
        $BuyerUserNum = $this->session->userdata('UserID');
    
		$SellerUserNum = $char[0]['UserNum'];
		// Check Seller Kung Meron EPoints
		$CheckSellerEP = $this->llllllllz_model->getpoints($SellerUserNum);
		$CheckUserName = $this->llllllllz_model->CheckUser($SellerUserNum);
		$SellerUserName = $CheckUserName[0]['UserName'];
		$SellerEP = $CheckSellerEP[0]['EPoint'];
		//Check Buyer Kung Meron EPoints
		$BuyerEP = $CheckEP[0]['EPoint'];
		//Check SELLER PRICE
		$GldPrice = $market[0]['EPoints'];
		//Check Gold Sale
		$Gld = $market[0]['Gold'];
		//Check Seller Locker
		if(isset($_POST['buypost'])){
	//========================================== Other Validation Here ===============================================
			if($BuyerUserNum == $SellerUserNum) {
                ?>
                <script> 
                alert("You Cant Buy your Own Gold") 
                location.href = '<?=base_url()?>MarketPlace';
                </script>
                <?php
				//$this->session->set_flashdata('message', 'cantbuyyourown');
				redirect(base_url().'MarketPlace');
				//exit;
			}
	//========================================== Computation HERE ====================================================
			//SUM Sale Gold + UserMoney
			$totalGld = $Gld + $lockmoney;
			$deductEP = $BuyerEP - $GldPrice;
			$SumSellerEP = $GldPrice + $SellerEP;

				if($BuyerEP >= $GldPrice){
					//Change Post Status
					$data = array(
						'Stat'=>0,
						'dtetmesold'=>currentdatetime()
					);
					$this->llllllllz_model->doupdatemarket($marketid,$data);

					//Gold Add to Locker
				if(!$CKLocker){
					
					$data1 = array(
						'UserNum'=>$BuyerUserNum,
						'SGNum'=>0,
						'ChaStorage2'=>currentdatetime(),
						'ChaStorage3'=>currentdatetime(),
						'ChaStorage4'=>currentdatetime(),
						'UserMoney'=>$totalGld
					);
					$this->llllllllz_model->doinsertlocker($data1);
				} else {
					$data1 = array(
						'UserMoney'=>$totalGld
					);
					$this->llllllllz_model->doupdatelocker($BuyerUserNum,$data1);
				}
				
				

				//Deducted EP for the buyer
				$data2 = array(
					'EPoint'=>$deductEP
				);
				$this->llllllllz_model->doupdatepoint($BuyerUserNum,$data2);

				if(!$CheckSellerEP){
					$data4 = array(
						'UserNum'=>$SellerUserNum,
						'UserName'=>$SellerUserName,
						'EPoint'=>$GldPrice,
						'VP'=>0
					);
					$this->llllllllz_model->doinsertvp($data4);
					
				} else {
					$data4 = array(
						'EPoint'=>$SumSellerEP
					);				
					$this->llllllllz_model->doupdatepoint($SellerUserNum,$data4);
					
				}
                //$this->session->set_flashdata('message', 'buygold');
               
				NotifySuccess("You Have Successfully Purchase a Gold");
				redirect(base_url().'MarketPlace');

			} else {
                //$this->session->set_flashdata('message', 'insufficientep');
                
				NotifyError("Your E-Points is not enough to Purchase");
				redirect(base_url().'MarketPlace');
			}
		} elseif(isset($_POST['cancelpost'])) {
			$totalGld = $Gld + $lockmoney;

			//Gold Add to Locker
			$data1 = array(
				'UserMoney'=>$totalGld
			);

			//var_dump($market);
			$this->llllllllz_model->cancelmarketpost($marketid);
			$this->llllllllz_model->doupdatelocker($BuyerUserNum,$data1);
            // $this->session->set_flashdata('message', 'cancelmarketpost');
           
			NotifySuccess("You Have Successfully Cancel Your Post");
			redirect(base_url().'MarketPlace');
		} else {
			//$this->load->view('err404');
		}
		

		//echo $Gld;
		//var_dump($lockmoney);
		//exit;
		//redirect(base_url().'homepanel/market');
    }
    
    function SaleGold(){
        if($_POST){
            if($_POST['status'] == 1){
				?>
				<script> 
					alert("Please Log-off your Character before Using this Function...") 
					//self.close();
					location.href = '<?=base_url()?>MarketPlace/SaleGold';
				</script>
				<?php
            }
            
            $char = $this->llllllllz_model->getchanum($_POST['character']);
			//var_dump($char);
			$ChaNum = $_POST['character'];
			$Gold = $_POST['goldsale'];
			$EP = $_POST['epprice'];
			$stat = 1;
			$PostDateTime = currentdatetime();

			$array = array(
					'ChaNum'=>$ChaNum,
					'Gold'=>$Gold,
					'EPoints'=>$EP,
					'Stat'=>$stat,
					'dtetmepost'=>$PostDateTime
				);


			if(!$ChaNum){
                ?>
				<script> 
					alert("Please Select Character") 
					//self.close();
					location.href = '<?=base_url()?>MarketPlace/SaleGold';
				</script>
				<?php
				redirect(base_url().'MarketPlace/SaleGold');
			}

			if($_POST['goldsale'] < 1000000){
                
                ?>
				<script> 
					alert("Minimum Gold to sell must be at least 1,000,000 Gold.") 
					//self.close();
					location.href = '<?=base_url()?>MarketPlace/SaleGold';
				</script>
				<?php
				redirect(base_url().'MarketPlace/SaleGold');
			}

			if(!$EP){
                ?>
				<script> 
					alert("You shall never post your gold here for free.") 
					//self.close();
					location.href = '<?=base_url()?>MarketPlace/SaleGold';
				</script>
                <?php
                
				redirect(base_url().'MarketPlace/SaleGold');
			}

			if($char[0]['ChaMoney'] < $_POST['goldsale']){
                ?>
				<script> 
					alert("You shall never post your gold here for free.") 
					//self.close();
					location.href = '<?=base_url()?>MarketPlace/SaleGold';
				</script>
                <?php
				redirect(base_url().'MarketPlace/SaleGold');
			}

			$return = $char[0]['ChaMoney'] - $Gold;
			
				$data = array(
					'ChaNum'=>$ChaNum,
					'Gold'=>$Gold,
					'EPoints'=>$EP,
					'Stat'=>$stat,
					'dtetmepost'=>$PostDateTime
				);

				$data2 = array(
					'ChaMoney'=>$return
				);



				$this->llllllllz_model->dopostgold($data);
                $this->llllllllz_model->doupdategold($ChaNum,$data2);
                ?>
				<script> 
					alert("Succesfully Sale a Gold") 
					//self.close();
					location.href = '<?=base_url()?>MarketPlace/SaleGold';
				</script>
                <?php
				redirect(base_url().'MarketPlace/SaleGold');
        } else {
            renderview('HomePage/Pages/MarketPlace/SaleGold');
        }
    }


    function ChaInfo($ChaNum){
        $char = $this->llllllllz_model->GetChaNum($ChaNum);

		if(!$char){
			echo '
			<tr>
			<td></td>
			<td></td> 
			<td></td>
			</tr>
			';
		} else {
			echo '
			<tr>
			<td align="center">'.ClassIMG($char[0]['ChaClass']).'</td>
			<td align="center">'.$char[0]['ChaName'].'</td> 
			<td align="center">'.formatnumber2($char[0]['ChaMoney']).'</td> 
			</tr>
			';
		}
    }

}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */