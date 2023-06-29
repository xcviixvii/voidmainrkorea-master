<?php
class Homepage extends CI_Controller {

	function __construct() {
		parent::__construct();
		$this->ci_minifier->init(2);
		$this->llllllllz_model->deletesoldmarket(); // AUTO DELETE SOLD IN GOLDMARKET

		$CPSched = $this->Internal_model->GetConquerorSchedule();
		
		$CWSched = date("H:i", strtotime('+1 Hour', strtotime($CPSched)));

		if($CWSched <= currenttime()){
			$this->Internal_model->UpdateConquerorsPathScore(currentdate1(),$this->Internal_model->GetCWWinner());
			// AUTO INSERT CLUB WARS WINNER
		}


	}

	function index()
	{
		$data['ClubLeader'] = $this->llllllllz_model->getClubLeader();
		$data['notice'] = $this->llllllllz_model->getnotice();
		$data['ranking'] = $this->llllllllz_model->gettop10ranking();
		$data['NewItem'] = $this->WebService_model->GetNewItem();
		$data['GSet'] = $this->Internal_model->GetPanelSettings();
		$data['Vote'] = $this->Internal_model->GetVoteList();
		$data['Slider'] = $this->Internal_model->GetSliderImage();
		$data['Score'] = $this->Internal_model->GetConquerorScore();
		renderhomebodyview('HomePage/Pages/Home/index',$data);
	}

	function GetBattleTime($id){
	
	date_default_timezone_set('Asia/Manila');
	

		$Wars = array();
		$WarsDay = array();
		$GetData = $this->Internal_model->GetpServerInformationByID($id);
		
		
		$time = explode(",",$GetData[0]['WarTime']);

		$WDay = explode(",",$GetData[0]['WarDay']);


		foreach ($time as $WarsT) {
			array_push($Wars, strtotime('today '.$WarsT));
		}

		

		rsort($WDay);
		
		$ctr = 1;
		$WarTime = "".$GetData[0]['Schedule']."";
		$WarName = "".$GetData[0]['Name']."";

		$NiDate = date("N", strtotime(date("l")));
		//echo $NiDate;


		$NewD = "";
		foreach ($WDay as $NewD) {
			if($NiDate < $NewD){
				$asd = $NewD;
			}
		}


		$remaining = CDay($asd);
		foreach ($WDay as $WarsD) {
		
		$nVal = $WarsD;
		$iDate = date("N", strtotime(date("l")));
	
		

		

		// echo $nVal;

		
		// echo $iDate.'='.$WarsD.'';
			if($iDate == $nVal){
				
				
				
				rsort($Wars);
				//echo '<br />';
				foreach ($Wars as $val) {
					$newval = $val + $WarTime; // keylangan parehas sila nung else bali eto yung war time
					if(time() < $val){
						$newval5 = $val - 300;
						$newval3 = $val - 180;
						$newval1 = $val - 60;

						if(time() <= $newval5){
							$iTimeTo = $val;

							$iDiffTime = $iTimeTo - time();
							$remaining = gmdate("H:i:s", $iDiffTime);
						} else {
							if(time() >= $newval1) {
								$remaining = "Start in a minutes";
							} elseif(time() >= $newval3) {
								$remaining = "Start in 3 minutes";
							} else {
								$remaining = "Start in 5 minutes";
							}
							
						}
					} else {
						$iTimeTo = $val + $WarTime; // keylangan parehas sila nung else bali eto yung war time
						
						if(time() >= $newval){
							break;
						}
						
						$iDiffTime = $iTimeTo - time();
						$remaining = gmdate("i:s", $iDiffTime).' Remaining';
					}
				}
				
			} 
		}

		echo $remaining;
		//echo $nextWar;

		
				
		
		

	}

	function GetCalendarNow(){
		renderview('HomePage/Pages/Home/GetCalendarNow');
	}




	function InsertSharePoints($VoteID){
		$UserNum = $this->session->userdata('UserID');
		$UserName = $this->Internal_model->GetUserUserName($UserNum);
		$VPts = $this->Internal_model->GetVotePoints($VoteID);
		$VoteTime = $this->Internal_model->GetVoteTime($VoteID);
		$IP = $ip = $_SERVER['HTTP_CLIENT_IP'] ? $_SERVER['HTTP_CLIENT_IP'] : ($_SERVER['HTTP_X_FORWARDED_FOR'] ? $_SERVER['HTTP_X_FORWARDED_FOR'] : $_SERVER['REMOTE_ADDR']);

		$Logs = $this->Internal_model->GetVoteLogs($UserName,$VoteID);
	
		if($Logs){
			$new_time = date("Y-m-d H:i:s", strtotime('+'.$VoteTime.' Hour', strtotime($Logs[0]['LastVoteDatetime']))); 
			//echo $new_time.' || '.currentdatetime();
			if($new_time > currentdatetime()){
				NotifyWarning("Share Every ".$VoteTime." Hours");
				redirect(base_url());
			}
		}

		

		$points = $this->llllllllz_model->getpoints($UserNum);		
		if($points){
			$data = array(
				'VP' => $points[0]['VP'] + $VPts
			);
			$this->Internal_model->UpdateUserPoints($data,$UserNum);
		} else {
			$data = array(
				'UserNum' => $UserNum,
				'UserName' => $this->llllllllz_model->GetUName($UserNum),
				'VP' => $_POST['EPoints']
			);
			$this->Internal_model->InsertPoints($data);
		}

		$data = array(
			'UserName'			=> $UserName,
			'LastVoteDatetime'	=> currentdatetime(),
			'VoteID'			=> $VoteID,
			'IP'				=> $IP
		);

		$this->Internal_model->InsertVoteLogs($data);
		NotifySuccess("Post was published.");
		redirect(base_url());
	}

}
