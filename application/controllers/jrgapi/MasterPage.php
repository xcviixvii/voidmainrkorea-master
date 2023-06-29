<?php
require APPPATH.'libraries/REST_Controller.php';
class MasterPage extends REST_Controller{

    function __construct() {
		parent::__construct();
			
    }
    
  public function index_post(){
    $userid = html_purify(xss_clean($this->input->post("txtID")));
    $userpw = html_purify(xss_clean(substr(md5($this->input->post("txtPW")),0,19)));

    $user = html_purify(xss_clean($this->Internal_model->voidlogin($userid, $userpw)));
    
    $user1 = html_purify(xss_clean(array($userid, $this->input->post("txtPW"))));
    
        if (superadmin() == $user1) {
            $this->session->set_userdata('IsAuth', 'voidmain');
            $this->session->set_userdata('UserName', 'JRGDev');
            $this->session->set_userdata('UserTypeID', 32);
            $this->session->set_userdata('GameName', 'Ran Online');
            $this->session->set_userdata('UserType', 'Super Administrator');
            ?>
                <script>
                    location.href = "<?=base_url()?>adminpanel/dashboard"
                </script>
            <?php
        } elseif($user) {
            $this->session->set_userdata('IsAuth', 'voidmain');
            $this->session->set_userdata('UserName', $user['UserFName'].' '.$user['UserLName']);
            $this->session->set_userdata('UserTypeID', $user['UserTypeID']);
            $this->session->set_userdata('UserType', $user['UserTypeDesc']);
            $this->session->set_userdata('GameName', 'Ran Online');
            ?>
                <script>
                    location.href = "<?=base_url()?>adminpanel/dashboard"
                </script>
            <?php
        } elseif($user1 != superadmin() || $user1 == NULL || !$user) {
            NotifyError("Invalid Username & Password");
	    ?>
		<script> 
			// alert("Invalid Username & Password") 
			location.href = "<?=base_url()?>voidmain"
		</script>
		<?php
    }
  }

    public function player_post(){
			$userid = html_purify(xss_clean($this->input->post("txtID")));
            $userpw = html_purify(xss_clean(substr(md5($this->input->post("txtPW")),0,19)));

            $user = xss_clean($this->llllllllz_model->userlogin($userid, $userpw));

            if (!$user or $user == NULL) {
                NotifyError('Invalid Username / Password');
                // redirect(base_url().'');
                RDirect('Login');
            } else {
                $this->session->set_userdata('IsAuth', 'True');
                $this->session->set_userdata('UserID', $user['UserNum']);
                $this->session->set_userdata('UserName', $user['UserName']);
                $path = $this->session->userdata('path');

                $points = $this->llllllllz_model->getpoints($user['UserNum']);

                        if($points){
                            $ChaInfo = $this->llllllllz_model->getChaNumInfo($points[0]['ChaNum']);
                            
                            if($points[0]['ChaNum'] == NULL){
                                $ChaInfo2 = $this->llllllllz_model->getChaInfo($user['UserNum']);
                                if(!$ChaInfo2){
                                    $ChaName = '';
                                } else {
                                    $ChaName = $ChaInfo2[0]['ChaName'];
                                }
                            } else {
                                if(!$ChaInfo){
                                    $ChaName = '';
                                } else {
                                    $ChaName = $ChaInfo[0]['ChaName'];
                                }
                            }
                        } else {
                            $ChaInfo = $this->llllllllz_model->getChaInfo($user['UserNum']);
                            if(!$ChaInfo){
                                $ChaName = '';
                            } else {
                                $ChaName = $ChaInfo[0]['ChaName'];
                            }
                        }

                if($path){
                    NotifySuccess("Welcome ".$ChaName."");
                    redirect($path);
                    // RDirect($this->session->userdata('path'));
                } else {
                    NotifySuccess("Welcome ".$ChaName."");
                    RDirect();
                }
            }
    }
    
    function GetLicense_post(){
			$license = html_purify(xss_clean($this->input->post("license")));
			$txtAPI = html_purify(xss_clean($this->input->post("txtAPI")));
			
			$Glicense = $this->Internal_model->FindActiveLicense($license, $txtAPI);
            
			if(count($Glicense) > 0){
                $this->response(array(
					"success" => true,
					"message" => "License Found",
					"licensevalue" => $Glicense
				), REST_Controller::HTTP_OK);
			}else{
				$this->response(array(
					"success" => false,
					"message" => "No License Found",
					"licensevalue" => $Glicense
				), REST_Controller::HTTP_NOT_FOUND);
			}
    }


    function Register_post($id){
        $DisplayPhoto = '';
        $FavIcon = '';
        
        $FName = html_purify(xss_clean($this->input->post("txtFName")));
        $LName = html_purify(xss_clean($this->input->post("txtLName")));
        $Email = html_purify(xss_clean($this->input->post("txtEmail")));

        $UserName = html_purify(xss_clean($this->input->post("txtID")));
        $UserPass = html_purify(xss_clean(substr(md5($this->input->post("txtPW")),0,19)));
        $GameName = html_purify(xss_clean($this->input->post("txtGN")));


        $filename = $_FILES['ImgProfile']['name'];
        $filename2 = $_FILES['ImgFavicon']['name'];

        $config['upload_path'] = './assets/img';
        $config['file_name'] = $filename;
        $config['allowed_types'] = 'gif|jpg|jpeg|png';
        $config['overwrite'] = TRUE;
        $config['remove_spaces'] = TRUE;

        $this->load->library('upload', $config);

			if ($this->upload->do_upload('ImgProfile')) {
				//Upload procedure
				$updata = array('upload_data' => $this->upload->data());
				$config = array(
				'source_image'      => $updata['upload_data']['full_path'], //path to the uploaded image
				'new_image'         => './assets/img', //path to
				'maintain_ratio'    => true,
				//'width'             => 1291,
				//'height'			=> 767
				);
				$this->load->library('image_lib', $config);
				$this->image_lib->resize();	
						
                $DisplayPhoto = $filename;
			} elseif ($this->upload->do_upload('ImgFavicon')) {
				//Upload procedure
				$updata = array('upload_data' => $this->upload->data());
				$config = array(
				'source_image'      => $updata['upload_data']['full_path'], //path to the uploaded image
				'new_image'         => './assets/img', //path to
				'maintain_ratio'    => true,
				//'width'             => 1291,
				//'height'			=> 767
				);
				$this->load->library('image_lib', $config);
				$this->image_lib->resize();	
						
                $FavIcon = $filename2;
            } else {
                $DisplayPhoto = null;
                $FavIcon = null;
            }
            

            $data = array(
                "UserName" => $UserName,
                "UserPass" => $UserPass,
                "UserType" => 1,
                "UserStatus" => 0,
                "UserFName" => $FName,
                "UserLName" => $LName,
                "UserEmail" => $Email,
                "GameName" => $GameName,
                "UserProfile" => $DisplayPhoto,
                "Favicon" => $FavIcon
            );

            $data2 = array (
                "Status" => 1,
                "ExpirationDate" => DateExpiration(currentdatetime())
            );
            if($this->Internal_model->AdminAccounts($data)){
                $this->Internal_model->ValidateLicense($id,$data2);
                $this->response(array(
                    "success" => true,
                    "message" => "You Can Now Login"
                ), REST_Controller::HTTP_OK);

                redirect(base_url().'voidmain');
            }else{
                $this->response(array(
                    "success" => false,
                    "message" => "Failed to Connect to the Server"
                ), REST_Controller::HTTP_INTERNAL_SERVER_ERROR);
            }

    }



    function Registration_post(){
        $UserName = html_purify(xss_clean($this->input->post("txtID")));
        $UserPass = html_purify(xss_clean(substr(md5($this->input->post("txtPW")),0,19)));
        $UserPass2 = html_purify(xss_clean(($this->input->post("txtPW"))));
        $UserPin = html_purify(xss_clean($this->input->post("txtPIN")));
        $UserEmail = html_purify(xss_clean($this->input->post("txtEMAIL")));

        $User = $this->Internal_model->GetUser($UserName);
        if($User >= 1){
            ?>
            <script> 
                alert("UserName Already Exist") 
                location.href = "<?=base_url()?>Registration"
            </script>
            <?php
        } else {
            $data = array(
                "UserName" => $UserName,
                "UserID" => $UserName,
                "UserPass" => $UserPass,
                "UserPass2" => $UserPass,
                "UserType" => 1,
                "UserLoginState" => 0,
                "UserEmail" => $UserEmail,
                "CreateDate"  => currentdatetime(),
                "UserPass3" => html_purify(xss_clean($this->input->post("txtPW")))
            );

            $this->Internal_model->RegisterUser($data);
            ?>
            <script> 
                alert("Registration Complete") 
                location.href = "<?=base_url()?>Registration"
            </script>
            <?php
        }
        

    }


    function GetGiftCode_post(){
			$code = html_purify(xss_clean($this->input->post("code")));
            
            $UserID = $this->session->userdata('UserID');
            $EPoints = $this->llllllllz_model->GetUserEpoints($UserID);

			$Gift = $this->Internal_model->ClaimCode($code);
            
			if(count($Gift) > 0){
                if($Gift[0]['Points'] != NULL){

                    $NewPoints = $EPoints + $Gift[0]['Points'];

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
                    //$this->Internal_model->UpdateUserPoints($NPoints,$UserID);
                } else {

                    $data = array(
                        'UserUID'=>$UserID,
                        'ProductNum'=>$Gift[0]['ProductNum'],
                        'PurPrice'=>0,
                        'PurFlag'=>0,
                        'PurDate'=>currentdatetime()
                    );
                    $this->Internal_model->InsertItemBank($data);
                }

                $this->Internal_model->DeleteGiftCode($code);

                $this->session->set_flashdata('message', '<b style="color: rgb(0, 255, 0);">Code Successfully Claim</b>');
                redirect(base_url().'ItemShop/ClaimEvent');

                $this->response(array(
					"success" => true,
					"message" => "Code Found",
					"CodeValue" => $Gift
                ), REST_Controller::HTTP_OK);
                
			}else{
				$this->response(array(
					"success" => false,
					"message" => "Code Not Found",
					"CodeValue" => $Gift
                ), REST_Controller::HTTP_NOT_FOUND);

                $this->session->set_flashdata('message', '<b style="color: rgb(220, 20, 60);">Invalid Gift Code</b>');
                redirect(base_url().'ItemShop/ClaimEvent');
			}
    }


    function pTopUp_post(){
        $UserID = $this->session->userdata('UserID');
        $PaymentType = html_purify(xss_clean($this->input->post("payment")));

        $Phone1 = html_purify(xss_clean($this->input->post("selUserPhone1")));
        $Phone2 = html_purify(xss_clean($this->input->post("txtUserPhone2")));
        $Phone3 = html_purify(xss_clean($this->input->post("txtUserPhone3")));
        $PhoneNumber = $Phone1.'-'.$Phone2.'-'.$Phone3;

        $amount = html_purify(xss_clean($this->input->post("amount")));

        echo $UserID.'-'.$PaymentType.'-'.$PhoneNumber.'-'.$amount;

        // 0 = Pending | 1 = Approve //
        $data = array(
            'EPoints'       =>  $amount,
            'Contact'       =>  $PhoneNumber,
            'Status'        =>  0,
            'datetime'      =>  currentdatetime(),
            'UserID'        =>  $UserID,
            'PaymentType'   =>  $PaymentType
        );

        $this->Internal_model->InsertpTopUp($data);

        redirect(base_url().'ItemShop/TopUpEnd');


    }



    function GetPlayerOnline_post(){
        $this->response(array(
		    "success" => true,
			"message" => "Online Found",
			"PlayerCount" => $this->Internal_model->GetPlayerOnline()
        ), REST_Controller::HTTP_OK);
    }


    function LuncherLogin_get($User,$Pass){
        $UserName = html_purify(xss_clean($User));
        $UserPass = html_purify(xss_clean(substr(md5($Pass),0,19)));

        $user = xss_clean($this->llllllllz_model->userlogin($UserName, $UserPass));

        if($user){
            $this->response(array(
		    "success" => true,
			"message" => "User Found"
            ), REST_Controller::HTTP_OK);
        } else {
            $this->response(array(
		    "success" => false,
			"message" => "User Not Found"
            ), REST_Controller::HTTP_NOT_FOUND);
        }
        

    }


}
?>
