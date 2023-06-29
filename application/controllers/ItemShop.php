<?php

class ItemShop extends CI_Controller {
	
	function __construct() {
		parent::__construct();
		$this->ci_minifier->init(2);
		$this->session->unset_userdata('path');
	}

	function index($offset = 0) {
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
			$config['base_url']=base_url().'ItemShop/Page';
			$config['total_rows']=$this->llllllllz_model->getItemShopcount();
			$config['use_page_numbers'] = TRUE;
			$config['per_page']=12;
			$this->pagination->initialize($config);
			if($offset != 0){
			    $offset = (($offset - 1) * $config["per_page"] );
			}
			$data['count'] = $this->llllllllz_model->getItemShopcount();
			$data['ItemShop'] = $this->llllllllz_model->getitemeshoppagination($config['per_page'],$offset);
			$data['pages']=$this->pagination->create_links();
			$data['GSet'] = $this->Internal_model->GetPanelSettings();
			$data['Slider'] = $this->Internal_model->GetSliderImage();
            renderhomebodyview('HomePage/Pages/ItemShop/index',$data);
	}

	function page($offset = 0){
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
			$config['base_url']=base_url().'ItemShop/Page';
			$config['total_rows']=$this->llllllllz_model->getItemShopcount();
			$config['use_page_numbers'] = TRUE;
			$config['per_page']=12;
			$config['first_url'] = base_url().'ItemShop';

			//$config['attributes'] = array('class' => 'asd');
			//$config['anchor_class'] = 'class="last" ';

			$this->pagination->initialize($config);
			if($offset != 0){
			    $offset = (($offset - 1) * $config["per_page"] );
			}
			$data['count'] = $this->llllllllz_model->getItemShopcount();
			$data['ItemShop'] = $this->llllllllz_model->getitemeshoppagination($config['per_page'],$offset);
			$data['pages']=$this->pagination->create_links();
			$data['GSet'] = $this->Internal_model->GetPanelSettings();
			$data['Slider'] = $this->Internal_model->GetSliderImage();
			renderhomebodyview('HomePage/Pages/ItemShop/index',$data);
	}

	function ItemFind($ItemName = "''"){

			$data['count'] = $this->llllllllz_model->getitemshopcountbyName($ItemName);
			$data['ItemShop'] = $this->llllllllz_model->getitemeshoppaginationbyname($ItemName);

			$data['pages']=1;
			$data['GSet'] = $this->Internal_model->GetPanelSettings();
			$data['Slider'] = $this->Internal_model->GetSliderImage();
			renderhomebodyview('HomePage/Pages/ItemShop/index',$data);
	}

	function ItemBuy($id){
		$data['product'] = $this->llllllllz_model->getitemebyitem($id);
		$data['points'] = $this->llllllllz_model->getpoints($this->session->userdata('UserID'));
		renderview('HomePage/Pages/ItemShop/ItemBuy',$data);
	}

	function PaymentStep1(){
		$data['points'] = $this->llllllllz_model->getpoints($this->session->userdata('UserID'));
		renderview('HomePage/Pages/ItemShop/Payment1',$data);
	}

	function PaymentStep2(){
		$data['points'] = $this->llllllllz_model->getpoints($this->session->userdata('UserID'));
		renderview('HomePage/Pages/ItemShop/Payment2',$data);
	}

	function CartList(){
		if(!$this->session->userdata('UserID')){
			?>
		 	<script> 
		 		alert("please try again after logging in..") 
		 		location.href = '<?=base_url()?>Login';
		 	</script>
		 	<?php
		}
		$data['Cart'] = $this->Internal_model->GetAllCartItemListByUser($this->session->userdata('UserID'));
		$data['points'] = $this->llllllllz_model->getpoints($this->session->userdata('UserID'));
		$data['GSet'] = $this->Internal_model->GetPanelSettings();
		$data['Slider'] = $this->Internal_model->GetSliderImage();
		renderhomebodyview('HomePage/Pages/ItemShop/CartList',$data);
	}

	function ItemCartDeleteAll(){
		if(isset($_GET)){
			$UserID = $this->session->userdata('UserID');
			$ItemNums = isset($_GET['ItemNums']) ? $_GET['ItemNums'] : 1;

			$Prod = array();
			$ProdNum = explode(",",$ItemNums);
			$cnt = count($ProdNum) - 1;

			for ($i = 0; $i <= $cnt; $i++) {
				array_push($Prod, array("ItemNum" => $ProdNum[$i]));
			}

			$ItemData = array();

			foreach ($Prod as $row) {
				$this->Internal_model->DeleteCartListByID($row['ItemNum'],$UserID);
			} 

			$output = array(
				"msg" => 0,
			);

			$ItemData[] = $output;
					
		} else {
			$output = array(
				"msg" => 1,
			);

			$ItemData[] = $output;	
		}
		
			print json_encode($ItemData);

		
	}

	function ItemCartBuy(){
		if(isset($_GET)){
			$UserID = $this->session->userdata('UserID');
			$ItemNums = isset($_GET['ItemNums']) ? $_GET['ItemNums'] : 1;
			$ItemName = isset($_GET['ItemNames']) ? $_GET['ItemNames'] : NULL;
			$itemCounts = isset($_GET['ItemCounts']) ? $_GET['ItemCounts'] : 1;

			

			$Prod = array();
			$ProdNum = explode(",",$ItemNums);
			$ProdName = explode(",",$ItemName);
			$ProdQty = explode(",",$itemCounts);
			$cnt = count($ProdNum) - 1;

			for ($i = 0; $i <= $cnt; $i++) {
				array_push($Prod, array("ItemNum" => $ProdNum[$i],"ProductName" => $ProdName[$i], "ItemCount" => $ProdQty[$i]));
			}

			$ItemData = array();
			$ctr = 0;
			foreach ($Prod as $row) {
				if($this->llllllllz_model->get_productStock($row['ItemNum']) <= $row['ItemCount']){
					$output = array(
							"OutStock" => 1,
							"retVal" => 2,
							"ItemName" => $row['ProductName']
					);
					$ItemData[] = $output;
					$ctr ++;
				} 
			}
			

			

			if($ctr <= 0){
				$TotalPrice = 0;
				foreach ($Prod as $row) {
					$Price = 0;
					for ($x = 1; $x <= $row['ItemCount']; $x++) {
						$ItemSub = $this->Internal_model->GetSubItemByItemSubID($row['ItemNum']);
						if($ItemSub){
							$data = array(
								'UserUID'=>$this->Internal_model->GetUserUserName($UserID),
								'ProductNum'=>$row['ItemNum'],
								'PurPrice'=>0,
								'PurFlag'=>0,
								'PurDate'=>currentdatetime()
							);

							$SubItem = array (
								'UserUID'=>$this->Internal_model->GetUserUserName($UserID),
								'ProductNum'=>$ItemSub[0]['ProductSubNum'],
								'PurPrice'=>0,
								'PurFlag'=>0,
								'PurDate'=>currentdatetime()
							);

							$ItemPrice = $this->llllllllz_model->get_productprice($row['ItemNum']);

							$this->Internal_model->InsertItemBank($data);
							$this->Internal_model->InsertItemBank($SubItem);
						} else {
							$data = array(
								'UserUID'=>$this->Internal_model->GetUserUserName($UserID),
								'ProductNum'=>$row['ItemNum'],
								'PurPrice'=>0,
								'PurFlag'=>0,
								'PurDate'=>currentdatetime()
							);

							$ItemPrice = $this->llllllllz_model->get_productprice($row['ItemNum']);

							$this->Internal_model->InsertItemBank($data);
						}

						$Price = $Price + $ItemPrice;
						
					}

					

					
					$TotalPrice = $TotalPrice + $Price;

					$RemainingStocks = $this->llllllllz_model->get_productStock($row['ItemNum']) - $row['ItemCount'];
					$RStock = array (
						'Itemstock' => $RemainingStocks
					);

					$this->Internal_model->UpdateShopStocks($RStock,$row['ItemNum']);

					$output2 = array(
							"OutStock" => 0,
							"retVal" => 0,
					);
					
					$ItemData[] = $output2;
				}


				$EPoints = $this->llllllllz_model->GetUserEpoints($UserID);

				$RemainingPoints = $EPoints - $TotalPrice;

				$RPoints = array (
					'EPoint' => $RemainingPoints
				);


				$transactionNo = date("Ymdhis").''.transaction();

				$History = array(
					'ItemsList' => $ItemNums,
					'UserNum' => $UserID,
					'PurchaseDate' => currentdatetime(),
					'TransNo' => $transactionNo,
					'Cost' => $TotalPrice
				);

				$this->Internal_model->InsertBuyHistory($History);
				

				$this->Internal_model->UpdateUserPoints($RPoints,$UserID);

				$this->Internal_model->DeleteCartList($UserID);

			} 

		print json_encode($ItemData);
		

		} else {
			$data = array(
					"retVal" => '1'
			);
			print json_encode($data);
		}
	}


	function BuyHistory($offset = 0){
		$UserID = $this->session->userdata('UserID');

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
		$config['base_url']=base_url().'ItemShop/BuyHistory';
		$config['total_rows']=$this->llllllllz_model->GetBuyHistoryCount($UserID);
		$config['use_page_numbers'] = TRUE;
		$config['per_page']=12;
		$config['first_url'] = base_url().'ItemShop/BuyHistory';

		$this->pagination->initialize($config);
		if($offset != 0){
		    $offset = (($offset - 1) * $config["per_page"] );
		}
		
		$data['GSet'] = $this->Internal_model->GetPanelSettings();
		$data['Slider'] = $this->Internal_model->GetSliderImage();
		$data['count'] = $this->llllllllz_model->GetBuyHistoryCount($UserID);
		$data['BuyList'] = $this->llllllllz_model->GetBuyHistoryPagination($config['per_page'],$offset,$UserID);
		$data['pages']=$this->pagination->create_links();
		renderhomebodyview('HomePage/Pages/ItemShop/BuyList',$data);
	}

	function ClaimEvent(){
		if(!$this->session->userdata('UserID')){
			?>
		 	<script> 
		 		alert("please try again after logging in..") 
		 		location.href = '<?=base_url()?>Login';
		 	</script>
		 	<?php
		}
		
		$data['GSet'] = $this->Internal_model->GetPanelSettings();
		$data['Slider'] = $this->Internal_model->GetSliderImage();
		renderhomebodyview('HomePage/Pages/ItemShop/ClaimEvent',$data);
	}


	function TopUpStart(){

		if($_POST){
			$Code = $_POST['txtCode'];
			$Pin = $_POST['txtPin'];

			$CDetails = $this->Internal_model->GetCardDetails($Code,$Pin);

			if($CDetails){
				$UserID = $this->session->userdata('UserID');
            	$EPoints = $this->llllllllz_model->GetUserEpoints($UserID);

				$NewPoints = $EPoints + $CDetails;

				$points = $this->llllllllz_model->getpoints($UserID);
                if($points){
                    $data = array(
                        'EPoint' => $NewPoints
                    );
                    $this->Internal_model->UpdateUserPoints($data,$UserID);
                } else {
                    $data = array(
                        'UserNum' => $UserID,
                        'UserName' => $this->llllllllz_model->GetUName($UserID),
                        'EPoint' => $NewPoints
                    );
                    $this->Internal_model->InsertPoints($data);
				}

				$this->Internal_model->DeleteCardDetails($Code,$Pin);
				redirect(base_url().'ItemShop/TopUpEnd');
					
			} else {
				?>
				<script> 
					alert("Invalid Code And Pin Please Try Another Code...") 
					self.close();
					location.href = '<?=base_url()?>ItemShop/TopUpStart';
				</script>
				<?php
			}

		} else {
			renderview('HomePage/Pages/ItemShop/TopUpStart');
		}
	
	}
	function TopUpEnd(){
		if(!$this->session->userdata('UserID')){
			?>
			<script> 
				alert("please try again after logging in..") 
				self.close();
				location.href = '<?=base_url()?>';
			</script>
			<?php
		}

		?>
		<script>
			var timeleft = 5;
			var downloadTimer = setInterval(function(){
			if(timeleft <= 0){
				clearInterval(downloadTimer);
				self.close();
				location.href = '<?=base_url()?>';
			} else {
				document.getElementById("countdown").innerHTML = timeleft + " seconds";
			}
			timeleft -= 1;
			}, 1000);
		</script>
		<?php
		
		echo 'Your Transaction Successfully Submited <br />';
		echo 'This Window Will Close in <div id="countdown"></div>';
	}

	

	
}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */