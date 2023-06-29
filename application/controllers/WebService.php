<?php

class WebService extends CI_Controller {

	function index() {
			renderview('WebService/index');
	}

	function GetMainEventList(){
		
		renderview('WebService/GetMainEventList');
	}

	function GetServerDate(){
		renderview('WebService/GetServerDate');
	}

	function GetServerHour(){
		if($_POST){
			echo 'here';

			exit;
		} else {
		renderview('WebService/GetServerHour');
		}
	}

	function GetMainItemShopGMList(){
		$data['GMPick'] = $this->WebService_model->GetGMPick();
		renderview('WebService/GetMainItemShopGMList',$data);
	}

	function GetMainItemShopList(){
		$data['NewItem'] = $this->WebService_model->GetNewItem();
		renderview('WebService/GetMainItemShopList',$data);
	}

	function GetCalendar(){
		renderview('WebService/GetCalendar');
	}

	function GetUserFriendList(){
		$PageNo = isset($_GET['PageNo']) ? intval($_GET['PageNo']) : 1;
		$pagelimit = isset($_GET['pagelimit']) ? intval($_GET['pagelimit']) : 5;
		$UserID = isset($_GET['UserID']) ? VoidDecrypter2($_GET['UserID']) : null;

		if ($PageNo < 1) {
		  $PageNo = 1;
		}

		if ($pagelimit < 0) {
		  $pagelimit = 5;
		}

		$offset = ($PageNo * $pagelimit) - $pagelimit;
	

		$FlistCount = $this->llllllllz_model->getfriendlistcount($UserID);
		$Flist = $this->llllllllz_model->getfriendlist($UserID,$offset,$pagelimit);

		$data = array();
		foreach ($Flist as $row) {
		  $data[] = $row;
		}

		
		if ($FlistCount === 0) {
		  $data = array(
		    "error" => "No data"
		  );

		  print json_encode($data);
		} else {
			$totalrecord = $FlistCount;

			$data = array(
			    "success" => array(
			      "data" => $data,
			      "PageNo" => $PageNo,
			      "pagelimit" => $pagelimit,
			      "fetched" => count($data),
			      "totalrecord" => $totalrecord
			    )
			);

			print json_encode($data);
		}
	}


	function GetUserGuildList(){
		$PageNo = isset($_GET['PageNo']) ? intval($_GET['PageNo']) : 1;
		$pagelimit = isset($_GET['pagelimit']) ? intval($_GET['pagelimit']) : 5;
		$UserID = isset($_GET['UserID']) ? VoidDecrypter2($_GET['UserID']) : null;
		$GuNum = isset($_GET['GuNum']) ? VoidDecrypter2($_GET['GuNum']) : null;

		if ($PageNo < 1) {
		  $PageNo = 1;
		}

		if ($pagelimit < 0) {
		  $pagelimit = 5;
		}

		$offset = ($PageNo * $pagelimit) - $pagelimit;
	

		$FlistCount = $this->llllllllz_model->GetGuildMemberListCount($GuNum,$UserID);
		$Flist = $this->llllllllz_model->GetGuildMemberList($GuNum,$UserID,$offset,$pagelimit);

		$data = array();
		foreach ($Flist as $row) {
		  $data[] = $row;
		}

		
		if ($FlistCount === 0) {
		  $data = array(
		    "error" => "No data"
		  );

		  print json_encode($data);
		} else {
			$totalrecord = $FlistCount;

			$data = array(
			    "success" => array(
			      "data" => $data,
			      "PageNo" => $PageNo,
			      "pagelimit" => $pagelimit,
			      "fetched" => count($data),
			      "totalrecord" => $totalrecord
			    )
			);

			print json_encode($data);
		}

		//renderview('WebService/GetUserGuildList');
	}
	
	function ItemCart(){
		if(isset($_GET)){
			$ProductNum = isset($_GET['ItemNum']) ? intval($_GET['ItemNum']) : -1; //$_POST['ItemNum'];
			$Quantity = 1;
			
			$UserNum = $this->session->userdata('UserID'); //$_POST['UserID'];

			$Cart = array (
				'ProductNum'=> $ProductNum,
				'Quantity'=> $Quantity,
				'UserNum' => $UserNum
			);

			if($this->Internal_model->GetCartItemAlreadyExist($ProductNum) >= 1){
				$data = array(
					"result" => 2
				);

				print json_encode($data);
			} else {	
				$this->Internal_model->AddToCart($Cart);
				$data = array(
					"result" => 0
				);
				print json_encode($data);
			}
			//
			
		} else {
			$data = array(
				"result" => -1
			);
			print json_encode($data);
		}

	}


	function ItemBuy(){
		if(isset($_GET)){
			$ProductNum = isset($_GET['ItemNum']) ? intval($_GET['ItemNum']) : -1; //$_POST['ItemNum'];
			$UserNum = $this->session->userdata('UserID');
			$itemCounts = isset($_GET['ItemCounts']) ? $_GET['ItemCounts'] : 1;

			

			if($this->llllllllz_model->get_productStock($ProductNum) <= $itemCounts){
				$data = array(
					"result" => 2
				);
				
			} else {
				
                $ItemPrice = $this->llllllllz_model->get_productprice($ProductNum);
                $EPoints = $this->llllllllz_model->GetUserEpoints($UserNum);

                if($EPoints >= $ItemPrice){
                    $ItemSub = $this->Internal_model->GetSubItemByItemSubID($ProductNum);

                    if($ItemSub){
                        $data = array(
                            'UserUID'=>$this->Internal_model->GetUserUserName($UserNum),
                            'ProductNum'=>$ProductNum,
                            'PurPrice'=>0,
                            'PurFlag'=>0,
                            'PurDate'=>currentdatetime()
                        );

                        $SubItem = array (
                            'UserUID'=>$this->Internal_model->GetUserUserName($UserNum),
                            'ProductNum'=>$ItemSub[0]['ProductSubNum'],
                            'PurPrice'=>0,
                            'PurFlag'=>0,
                            'PurDate'=>currentdatetime()
                        );

                        $this->Internal_model->InsertItemBank($data);
                        $this->Internal_model->InsertItemBank($SubItem);

                    } else {
                        $data = array(
                            'UserUID'=>$this->Internal_model->GetUserUserName($UserNum),
                            'ProductNum'=>$ProductNum,
                            'PurPrice'=>0,
                            'PurFlag'=>0,
                            'PurDate'=>currentdatetime()
                        );
                        
                        $this->Internal_model->InsertItemBank($data);

                    }

                    $RemainingPoints = $EPoints - $ItemPrice;

                    $RPoints = array (
                        'EPoint' => $RemainingPoints
                    );


                    $RemainingStocks = $this->llllllllz_model->get_productStock($ProductNum) - 1;
                    $RStock = array (
                        'Itemstock' => $RemainingStocks
					);
					
					$transactionNo = date("Ymdhis").''.transaction();

					$History = array(
						'ItemsList' => $ProductNum,
						'UserNum' => $UserNum,
						'PurchaseDate' => currentdatetime(),
						'TransNo' => $transactionNo,
						'Cost' => $ItemPrice
					);

					$this->Internal_model->InsertBuyHistory($History);
					

                    $this->Internal_model->UpdateUserPoints($RPoints,$UserNum);
                    $this->Internal_model->UpdateShopStocks($RStock,$ProductNum);
                    $data = array(
                        "result" => 0
                    );
                } else {
                    $data = array(
                        "result" => 1
                    );
                }
			}
			print json_encode($data);
		}
	}
}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */