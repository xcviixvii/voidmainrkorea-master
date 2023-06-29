<?php

class CapsuleShop extends CI_Controller {
	function __construct() {
		parent::__construct();	
		$this->ci_minifier->init(2);
	}

	function index(){
		$this->session->set_userdata('CapsuleItem',"");
		renderview('HomePage/Pages/CapsuleShop/index');
	}

	function GetCapsuleShopList(){

		$capslist = $this->capsule_model->capsuleshoplist();
		echo '<ul id="capsule-list">';

		foreach ($capslist as $row) {
			$rows = $this->Internal_model->getcapsuleimage($row['ItemNum']);
		echo '<li>
		<p class="capsule_img"><img src="'.base_url().'Uploadfiles/ItemShop/'.$rows[0]['ItemSS'].'" width="40" height="40" alt='.$rows[0]['ItemName'].'" /></p><p class="capsule_name">
		<a href="'.base_url().'CapsuleShop/Purchase/'.$row['ItemNum'].'">'.$rows[0]['ItemName'].'</a></p><p class="capsule_num"><span class="num_level1"></p> 
		</li>';
			}


		echo '</ul>';
	}


	function GetCapsuleWinnerMain(){
		$winlist = $this->capsule_model->getcapswinner();
		echo '
		
		{
			ItemNum : "'.$winlist[0]['ItemNum'].'",
			ItemName : "'.$winlist[0]['ItemName'].'",
			InsertUser : "'.$winlist[0]['InsertUser'].'",
		}
		';    

	}


	function getcapsulewinnerlist(){
		$winlist = $this->capsule_model->getcapswinner();

		echo '[';

		foreach ($winlist as $row){
			echo '{
			ItemNum : "'.$row['ItemNum'].'", 
			ItemName : "'.$row['ItemName'].'", 
			InsertUser : "'.$row['InsertUser'].'", 
			InsertDate : "'.formatdatecapsule($row['InsertDate']).'"
			},';
		}
		echo ']';	
	}


	function Purchase($Num){
		$data['capsuleshoplinklist'] = $this->capsule_model->capsuleshoplinklist($Num);
		$data['unique'] = $this->capsule_model->capsuleunique($Num);

		$this->session->set_userdata('CapsuleItem',$this->uri->segment(3));
		renderview('HomePage/Pages/CapsuleShop/Purchase',$data);
	}


	function GetCapsuleItemInfo($ProductNum){
		$ItemInfo = $this->capsule_model->getcapsuleitemname($ProductNum);

		echo '
		<div id="tooltip">
		<dl>
		<dt>'.$ItemInfo[0]['ItemName'].'</dt>
		<dd><p>
		<span style="color: #f0ffff"><!-- Item Description --></span></p>
		</dd>
		</dl>
		<div class="arrow"></div>
		</div>';
	}


	function GetRemainCapsuleCount($id){
		$count = 43;
		echo $count;
	}


	function CompleteProcess(){
		$UserNum = $this->session->userdata('UserID');
		$points = $this->llllllllz_model->getpoints($this->session->userdata('UserID'));
		$capsule = $points[0]['Capsule'];

		$price = $_POST['CapsuleCount'] * 20;
		$Remaining = $points[0]['VP'] - $price;


		if($_POST){

			if(!$points){
				$data = array(
					'Capsule'=>$_POST['CapsuleCount'],
					'VP'	=> $Remaining
				);
			} else {
				$insertcapsule = $capsule + $_POST['CapsuleCount'];
				$data = array(
					'Capsule'=>$insertcapsule,
					'VP'	=> $Remaining
				);
			}

		$this->capsule_model->insertcapsule($data,$UserNum);
		$this->session->userdata('CapsuleItem');
		redirect(base_url().'CapsuleShop/Complete');	
		}
	}



	function Complete(){
		renderview('HomePage/Pages/CapsuleShop/Complete');
	}



	function OpenProcess(){
		$UserNum = $this->session->userdata('UserID');
		$UserName =$this->Internal_model->GetUserUserName($UserNum);
		$points = $this->llllllllz_model->getpoints($this->session->userdata('UserID'));
		

		if(!$this->session->userdata('CapsuleItem')){
			//DITO ANG ALL
			$rando = rand(0,100);
    		//$rand = ($rando > 95 ? 1 : 0);

    		if($rando > 95){
				$random = $this->capsule_model->GetCapsuleUniqueItem();
    		} else {
				$random = $this->capsule_model->GetCapsuleCommonItem();
			}
			
		} else {
			//DITO PER CAPSULE
			$rando = rand(0,100);
    		//$rand = ($rando > 95 ? 1 : 0);

    		if($rando > 99){
				$random = $this->capsule_model->GetCapsuleUniqueItemByItem($this->session->userdata('CapsuleItem'));
    		} else {
				$random = $this->capsule_model->GetCapsuleCommonItemByItem($this->session->userdata('CapsuleItem'));
			}

			// $random = $this->capsule_model->getrandomitembycapsule($this->session->userdata('CapsuleItem'));
		}
		
		if(!$points){
			?>
			<script type="text/javascript">alert("Please purchase more Capsule(s).");location.href="<?=base_url()?>CapsuleShop/";</script>
			<?php
		} else {
			$capsule = $points[0]['Capsule'];
			if($capsule > 0){
				// dito magaganap ang ramdom ng capsule
				$capsulebalance = $points[0]['Capsule'] - 1;

				if($random[0]['IsUnique'] == 1){

					$itemname = $this->Internal_model->getcapsuleimage($random[0]['ItemNum']);

					$User = substr($UserName,0,2).'*';

					$data1 = array(
						'ItemNum'=>$random[0]['ItemNum'],
						'ItemName'=>$itemname[0]['ItemName'],
						'InsertUser'=>$User,
						'InsertDate'=>currentdatetime()
					);

					$this->capsule_model->insertwinner($data1);
				}



				$data = array(
					'Capsule'=>$capsulebalance
				);

				$this->capsule_model->updatecapsulepoint($UserNum,$data); //Minus Capsule

				$databank = array(
						'UserUID'		=>	$UserName,
						'ProductNum'	=> $random[0]['ItemNum'],
						'PurPrice'		=> 0,
						'PurFlag'		=> 0,
						'PurDate'		=> currentdatetime()
				);

				$this->Internal_model->InsertItemBank($databank);

				redirect(base_url().'CapsuleShop/Open/'.$random[0]['ItemNum'].'');	

				/*
				if($random[0]['IsUnique'] == 0){
					echo 'Normal';
				} else {
					echo 'Unique';
				}
				*/

			} else {
				?>
				<script type="text/javascript">alert("Please purchase more Capsule(s).");location.href="<?=base_url()?>CapsuleShop/";</script>
				<?php
			}
		}
	}


	function Open($ItemNum){
			$data['item'] = $this->capsule_model->getiteminfo($ItemNum);
			$items = $this->capsule_model->getiteminfo($ItemNum);

			
			if($items[0]['IsUnique'] == 0){
				renderview('HomePage/Pages/CapsuleShop/Open',$data);
			} else {
				renderview('HomePage/Pages/CapsuleShop/OpenUnique',$data);
			}
	}







}