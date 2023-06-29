<?php

class MyAccount extends CI_Controller {
	function __construct() {
		parent::__construct();
		$this->ci_minifier->init(2);
		$userid = xss_clean($this->session->userdata('UserID'));	

		if (!$userid ) {
			?>
			<script> 
				alert("please try again after logging in..") 
				location.href = "<?=base_url()?>"
			</script>
			<?php
	  	}	
		
	}

	function index() {
		$data['Slider'] = $this->Internal_model->GetSliderImage();
		$data['GSet'] = $this->Internal_model->GetPanelSettings();
		$data['Char'] = $this->Internal_model->GetAllCharacter($this->session->userdata('UserID'));
        renderhomebodyview('HomePage/Pages/MyAccount/index',$data);
	}

	function AccountFix(){
		if($_POST){
			$UserName = html_purify(xss_clean($_POST['txtUserID']));
			$UserPass = html_purify(xss_clean(substr(md5($_POST['txtUserPass']),0,19)));
			$user = xss_clean($this->llllllllz_model->findaccountfix($UserName, $UserPass));
			
			if(!$user){
				$this->session->set_flashdata('message', '<div id="childerror">User account does not exist or<br />Username and Password does not match.<br /></div>');
				redirect(base_url().'MyAccount/AccountFix');

			} else {
				$UserNum = $user['UserNum'];
				$fix = xss_clean($this->llllllllz_model->fixaccount($UserNum));
				
				$data = array(
					'UserLoginState'=>0,
				
				);
				
				foreach ($fix as $row) {
					$fixing = $this->llllllllz_model->fixingaccount($UserNum,$data);
				}
				$this->session->set_flashdata('message', '<div id="childsuccess">Succesfully Fix Your Account</div>');
				redirect(base_url().'MyAccount/AccountFix');
			}


			
		} else {
			renderview('HomePage/Pages/MyAccount/AccountFix');
		}
	}


	function SelectChar($ChaNum){
		$UserID = $this->session->userdata('UserID');
		$ChaNumDec = $this->encrypt->decode($ChaNum);

		$points = $this->llllllllz_model->getpoints($UserID);
		
		if($points){
			$data = array(
				'ChaNum' => $ChaNumDec
			);
			$this->Internal_model->UpdateUserPoints($data,$UserID);
		} else {
			$data = array(
				'UserNum' => $UserID,
				'UserName' => $this->llllllllz_model->GetUName($UserID),
				'ChaNum' => $ChaNumDec
			);

			$this->Internal_model->InsertPoints($data);

		}

		redirect(base_url().'MyAccount');

	}


	function GTtoGTP(){
		if($_POST){
			$GTime = $this->Internal_model->GetGameTime($this->session->userdata('UserID'));
			if($_POST['status'] == 1){
				?>
				<script> 
					alert("Please Log-off your Character before Using this Function...") 
					self.close();
					location.href = '<?=base_url()?>MyAccount/GTtoGTP';
				</script>
				<?php
			}

			if($GTime[0]['Gametime3'] < 60){
				?>
				<script> 
					alert("Not Enough Gametime to Convert...") 
					self.close();
					location.href = '<?=base_url()?>MyAccount/GTtoGTP';
				</script>
				<?php
			} else {
				if($this->Internal_model->GetUserCombatPoint($this->session->userdata('UserID')) == NULL){
					$UserCombatPoint = 0;
				} else {
					$UserCombatPoint = $this->Internal_model->GetUserCombatPoint($this->session->userdata('UserID'));
				}
				$GameTime = $_POST['GameTime'] * 60;
				$data = array(
					'Gametime3'			=> $GTime[0]['Gametime3'] - $GameTime,
					'UserCombatPoint'	=> $_POST['GameTimePoints'] + $UserCombatPoint
				);

				$this->Internal_model->UpdateUserInfo($this->session->userdata('UserID'),$data);
				redirect(base_url().'MyAccount/GTtoGTP');
			}

		} else {
			renderview('HomePage/Pages/MyAccount/GTtoGTP');
		}
		
	}


	function GTPtoVP(){
		if($_POST){
			$GTime = $this->Internal_model->GetGameTime($this->session->userdata('UserID'));
			if($_POST['status'] == 1){
				?>
				<script> 
					alert("Please Log-off your Character before Using this Function...") 
					self.close();
					location.href = '<?=base_url()?>MyAccount/GTPtoVP';
				</script>
				<?php
			}

			if($GTime[0]['UserCombatPoint'] < 2){
				?>
				<script> 
					alert("Not Enough Gametime Points to Convert...") 
					self.close();
					location.href = '<?=base_url()?>MyAccount/GTPtoVP';
				</script>
				<?php
			} else {
				if($this->Internal_model->GetUserUserPoint($this->session->userdata('UserID')) == NULL){
					$UserPoints = 0;
				} else {
					$UserPoints = $this->Internal_model->GetUserUserPoint($this->session->userdata('UserID'));
				}
				$UserCombatPoints = $_POST['GameTimePoints'] * 2;
				$data = array(
					'UserCombatPoint'	=> $GTime[0]['UserCombatPoint'] - $UserCombatPoints,
					'UserPoint'	=> $_POST['UserPoints'] + $UserPoints
				);

				$this->Internal_model->UpdateUserInfo($this->session->userdata('UserID'),$data);
				redirect(base_url().'MyAccount/GTPtoVP');
			}

		} else {
			renderview('HomePage/Pages/MyAccount/GTPtoVP');
		}
	}

	function EPtoVP(){
		if($_POST){
			$GTime = $this->Internal_model->GetGameTime($this->session->userdata('UserID'));
			if($_POST['status'] == 1){
				?>
				<script> 
					alert("Please Log-off your Character before Using this Function...") 
					self.close();
					location.href = '<?=base_url()?>MyAccount/EPtoVP';
				</script>
				<?php
			}

			if(formatnumber($this->llllllllz_model->GetUserEpoints($this->session->userdata('UserID'))) < 1){
				?>
				<script> 
					alert("Not Enough E-Points to Convert...") 
					self.close();
					location.href = '<?=base_url()?>MyAccount/EPtoVP';
				</script>
				<?php
			} else {

				$EP = $this->llllllllz_model->GetUserEpoints($this->session->userdata('UserID')) -$_POST['EPoints'];

				$dataEP = array(
					'EPoint' => $EP
				);

				$this->Internal_model->UpdateUserPoints($dataEP,$this->session->userdata('UserID'));

				if($this->Internal_model->GetUserUserPoint($this->session->userdata('UserID')) == NULL){
					$UserPoints = 0;
				} else {
					$UserPoints = $this->Internal_model->GetUserUserPoint($this->session->userdata('UserID'));
				}

				$data = array(
					'UserPoint'	=> $_POST['UserPoints'] + $UserPoints
				);




				$this->Internal_model->UpdateUserInfo($this->session->userdata('UserID'),$data);
				redirect(base_url().'MyAccount/EPtoVP');
			}


		} else {
			renderview('HomePage/Pages/MyAccount/EPtoVP');
		}
	}
}


/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */