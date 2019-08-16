<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class TestLinkedIn extends MX_Controller {

    private $linkedin;
    /*
    In routes.php file
    $route["auth/linkedin"] = 'Api/Test/get_linkedin_login_url';
    $route["auth/linkedin/callback"] = 'Api/Test/linkedin';
     */
    public function __construct() {
        parent::__construct();

        $this->load->library('LinkedInCI');
        $client_id = CLIENT_ID_LINKED;
        $client_secret = CLIENT_SECRET_LINKED;
        $redirect_uri = 'http://localhost/VNW_DMC/CODE//auth/linkedin/callback';
        $this->linkedin = new LinkedIn($client_id, $client_secret, $redirect_uri);
    }

    public function get_linkedin_login_url() {
        $linkedin_login_url =  $this->linkedin->getOAuthURL();
        // echo $linkedin_login_url; 
        exit;
    }

    public function linkedin() {
        $params = $this->input->get();
        $code = isset($params ['code']) ? $params ['code'] : NULL;
        $state = isset($params ['state']) ? $params ['state'] : NULL;
        $verify = $this->linkedin->verifyState($state);
        
        $access_token = $this->linkedin->getAccessToken($code);
        // $profile = $this->linkedin->getUserProfile();
        
        $profile = $this->linkedin->getUserProfileByAccessToken($access_token);
		if(!empty($profile)){
			// pr($profile);
			redirect(PATH_URL.'thanh-cong');
		}
		else{
			echo 'Taì khoản đăng ký không hợp lệ.';
		}
    }

}

?>