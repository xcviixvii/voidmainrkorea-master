<?php
require_once APPPATH . "libraries/Facebook/autoload.php";
defined('BASEPATH') OR exit('No direct script access allowed');

//use Facebook;
class Authentication extends CI_Controller {

    function __construct() {
        parent::__construct();
        // Load user model
        $this->load->helper('url');
        $this->load->helper('email');
        $this->load->helper('cookie');
        $this->datetime = date('Y-m-d H:i:s');
    }

    public function index() {     
        $fb = new Facebook\Facebook([
            'app_id' => '1085285338326090', // Replace {app-id} with your app id
            'app_secret' => '17ecff6bdb8e77d066aaf46c58de9f6f',
            'default_graph_version' => 'v2.10',
        ]);

        $redirectUrl = base_url().'Authentication/callbackfb';
        $permissions = ['email']; // Optional permissions
        
        $helper = $fb->getRedirectLoginHelper();

        $loginUrl = $helper->getLoginUrl($redirectUrl, $permissions);

        $data['login_url'] = $loginUrl;

        $this->load->view('authentication/index', $data);
    }


    public function callbackfb() {
        
        $fb = new Facebook\Facebook([
            'app_id' => '1085285338326090', // Replace {app-id} with your app id
            'app_secret' => '17ecff6bdb8e77d066aaf46c58de9f6f',
            'default_graph_version' => 'v2.10',
        ]);
        $redirectUrl = base_url().'Authentication/callbackfb';
        
        $helper = $fb->getRedirectLoginHelper();

        if (isset($_REQUEST['code'])) {
            //$gClient->authenticate();
            $this->session->set_userdata('token', $helper->getAccessToken());
            redirect($redirectUrl);
        }
      
        try {
            //$accessToken = $helper->getAccessToken();
            $accessToken = $this->session->userdata('token');
            $response = $fb->get('/me?fields=id,name,gender,email,birthday', $accessToken);
            $pic= $fb->get('/me/picture?type=large', $accessToken);
        } catch (Facebook\Exceptions\FacebookResponseException $e) {
            // When Graph returns an error
            echo 'Graph returned an error: ' . $e->getMessage();
            exit;
        } catch (Facebook\Exceptions\FacebookSDKException $e) {
            // When validation fails or other local issues
            echo 'Facebook SDK returned an error: ' . $e->getMessage();
            exit;
        }

        if (!isset($accessToken)) {
            if ($helper->getError()) {
                header('HTTP/1.0 401 Unauthorized');
                echo "Error: " . $helper->getError() . "\n";
                echo "Error Code: " . $helper->getErrorCode() . "\n";
                echo "Error Reason: " . $helper->getErrorReason() . "\n";
                echo "Error Description: " . $helper->getErrorDescription() . "\n";
            } else {
                header('HTTP/1.0 400 Bad Request');
                echo 'Bad request';
            }
            exit;
        }

        // Logged in
        //echo '<h3>Access Token</h3>';
        //var_dump($accessToken->getValue());

        // The OAuth 2.0 client handler helps us manage access tokens
        $oAuth2Client = $fb->getOAuth2Client();

        // Get the access token metadata from /debug_token
        $tokenMetadata = $oAuth2Client->debugToken($accessToken);
        //echo '<h3>Metadata</h3>';
        //var_dump($tokenMetadata);

        // Validation (these will throw FacebookSDKException's when they fail)
        $tokenMetadata->validateAppId('1085285338326090'); // Replace {app-id} with your app id
        // If you know the user ID this access token belongs to, you can validate it here
        //$tokenMetadata->validateUserId('123');
        $tokenMetadata->validateExpiration();

        if (!$accessToken->isLongLived()) {
            // Exchanges a short-lived access token for a long-lived one
            try {
                $accessToken = $oAuth2Client->getLongLivedAccessToken($accessToken);
            } catch (Facebook\Exceptions\FacebookSDKException $e) {
                echo "<p>Error getting long-lived access token: " . $helper->getMessage() . "</p>\n\n";
                exit;
            }

            echo '<h3>Long-lived</h3>';
            var_dump($accessToken->getValue());
        }

       // $_SESSION['fb_access_token'] = (string) $accessToken;


        if ($response) {
            $userProfile=$response->getDecodedBody();

            $email = $this->Internal_model->CheckEmail($userProfile['email']);
            if($email <= 0){
                ?>
                <script> 
                    alert("No User Found ...") 
                    self.close();
                    location.href = '<?=base_url()?>authentication';
                </script>
                <?php
            }


            //echo "<img src='".$pic->getHeaders()['Location']."'>";
            
            //echo "<pre>";print_r($response->getDecodedBody());die;
            // Preparing data for database insertion
            $userData['oauth_provider'] = 'Facebook';
            $userData['oauth_uid'] = $userProfile['id'];
            $userData['first_name'] = $userProfile['name'];
            $userData['last_name'] = '';
            $userData['email'] = $userProfile['email'];
            //$userData['locale'] = $userProfile['locale'];
            $userData['profile_url'] = '';

            $data['userData'] = $userData;
        } 
        
        $this->load->view('authentication/index', $data);
        // User is logged in with a long-lived access token.
        // You can redirect them to a members-only page.
        //header('Location: https://example.com/members.php');
    }

    public function logout() {
        //echo "<pre>";print_r($this->session);die;
        $this->session->unset_userdata('token');
        $this->session->unset_userdata('userData');
        $this->session->sess_destroy();
        redirect('/authentication');
    }

}
