<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
include'APIClient2.php';

class Home extends MX_Controller {
    
    private $module = 'home';
    private $current_page_data;
    private $current_category_data;
    private $current_detail_data;
    private $parent_url;
    private $segments;
    private $pre_uri;
    private $table = 'user_count';

    public function __construct(){
        parent::__construct();
        //load model
        $this->load->model('home_model','model');
		$this->load->helper('main_helper');
        $this->load->helper('url');

        $this->template->set_template('default');
        $this->current_page_data = NULL;
        $this->segments = $this->uri->segment_array();
        $this->load->library('session');
		$this->load->library('facebook');
		$this->session_id_current = session_id();
    }

    public function index(){
		$data = null;
        $this->template->write('title','Home page');
        $this->template->write_view('content','FRONTEND/sign_in',$data);
        $this->template->render();    
    }
   
	
	private $user_data;
	private $session_id_current;
	private $api_access_token;

	
	private function form_submit_upload_file($file_input_name = ''){
		$result = [];
		if(empty($file_input_name)){
			$file_input_name = 'file_attach';
		}
		
		if (!empty($_FILES[$file_input_name]['tmp_name'])) {
			$config['upload_path'] = BASEFOLDER.DIR_UPLOAD_CV;
			$config['allowed_types'] = 'doc|docx|pdf';
			$config['max_size'] = 1024 * 2;
			$config['encrypt_name'] = TRUE;
			$this->load->library('upload', $config);
			if (!$this->upload->do_upload($file_input_name)) {
				$error_message = $this->upload->display_errors();
				$result['error_message'] = json_encode(strip_tags($error_message));
				if(strpos($error_message,'You did not select a file to upload') !== false){
					$result['error_upload_file_missing'] = 1; // TODO - No need
				} else if(strpos($error_message,'larger than the permitted size') !== false){
					$result['error_upload_file_filesize'] = 1;
				}
			} else {
				$dataUpload = array('upload_data' => $this->upload->data());
				$file_name = $dataUpload['upload_data']['file_name'];
				$result['file_url'] = PATH_URL.DIR_UPLOAD_CV.$file_name;
				$result['file_path'] = BASEFOLDER.DIR_UPLOAD_CV.$file_name;
			}
		} else {
			$result['error_message'] = 'upload_file_missing';
			$result['error_upload_file_missing'] = 1;
		}
		
		$log_arr = array(
			'location' => __FILE__ ,
			'function' => 'form_submit_upload_file',
			'result' => !empty($result) ? $result : '',
		);
		debug_log_from_config($log_arr);
		
		return $result;
	}
	
	public function connect_google(){
		 $accessToken = $this->input->get('accessToken');
		 $data['accessToken'] = $accessToken;
		 $this->load->view('FRONTEND/connect_google',$data);
	}
	
	public function connect_facebook(){
		 $accessToken = $this->input->get('accessToken');
		 $data['accessToken'] = $accessToken;
		 $this->load->view('FRONTEND/connect_facebook',$data);
	}
	
	public function login_facebook(){
		$result[] = '';
        $userData = array();
        $result_url = PATH_URL;
		
		$vnw_api_get_settings_result = $this->vnw_api_get_settings();
		
		if(!empty($vnw_api_get_settings_result['error_message'])){
			$result['status'] = 'fail';
			$result['error'] = 'api_get_settings';
		}
		
		// pr($this->facebook->is_authenticated(),1);
		$access_token = $this->facebook->is_authenticated();
		    $log_arr = array(
				'location' => __FILE__ ,
				'function' => 'login_facebook',
				'access_token' => !empty($access_token) ? $access_token : '',
			);
			debug_log_from_config($log_arr);
        if(!empty($access_token)){
            $userProfile = $this->facebook->request('get', '/me?fields=id,first_name,last_name,email');
			
            $log_arr = array(
				'location' => __FILE__ ,
				'function' => 'login_facebook',
				'userProfile' => !empty($userProfile) ? $userProfile : '',
				'access_token' => !empty($access_token) ? $access_token : '',
			);
			debug_log_from_config($log_arr);
            if(!empty($userProfile['email']) && !empty( $userProfile['first_name']) && !empty($userProfile['last_name'])){
                $userData['oauth_provider'] = 'facebook';
                $userData['first_name'] = $userProfile['first_name'];
                $userData['last_name'] = $userProfile['last_name'];
                $userData['email'] = $userProfile['email'];
                $email = $userProfile['email'];
                $userData['access_token'] = $access_token;
				$userData['created'] = getNow();
				$userData['ip'] = getIP();
				$userData['session_id'] = $this->session_id_current;
				
                $data['logoutUrl'] = $this->facebook->logout_url();	
				
				if($this->model->insert('login_account_social', $userData)){
					$result['status_db'] = 'success';
					$obj_id = $this->db->insert_id();
					$result['debug_message'] = 'insert_id = '.$obj_id;
					$obj['id_social'] = $obj_id;
					$this->user_data = $obj; // Save
					$_SESSION['is_login'] = true ;
				} else {
					$result['error_message'] = 'DB_FAIL';
				}
				$api_response_data_login = $this->vnw_api_call_login_facebook($access_token);
				
				$json_data = $api_response_data_login;
			    	// echo "Profile: fullname: email: $email -|- firstname: $first_name -|- lastname: $last_name";
			    	// exit;
					
				if(!empty($json_data)){
					if(isset($json_data->access_token)){
						$obj_updated['access_token'] = (isset($json_data->access_token))? $json_data->access_token : $json_data->refresh_token;
						$result['access_token'] = $obj_updated['access_token'];
						$this->model->update('login_account_social', $obj_updated, "id = {$obj['id_social']}");
						$result['message'] = 'success';
						$result['login'] = 'success';
						$_SESSION['access_token'] = $result['access_token'];
						$result_url = PATH_URL.'home/connect_facebook?accessToken='.$result['access_token'];
					}
					else{
						$result['error_message'] = 'fail';
						print'Không cho đăng nhập bằng facebook!!!!';
					}
				}
				else{
					$result['error_message'] = 'fail';
					print'Không cho đăng nhập bằng facebook!!!!';
				}
            }
        }
		else{
             // Get login URL
            $result_url = $this->facebook->login_url();
			
			$log_arr = array(
				'location' => __FILE__ ,
				'function' => 'login_facebook',
				'result_url' => !empty($result_url) ? $result_url : '',
			);
			debug_log_from_config($log_arr);
        }
		if(!empty($result_url)){
			redirect($result_url);
		}
       return $result;
    }
	
	public function sendEmailToEmployee($data){
		$rs = false;
		$setting_mapping = $this->get_admin_settings();
		$location = $this->model->info_admin_nqt_job_by_id($data['job_id']);
		
		$subject = 'New candidate applied to Vietnamworks';
		
		$sendEmail_HCM = isset($setting_mapping['EmailNotification_HCM']) ? $setting_mapping['EmailNotification_HCM'] : '';
		$sendEmail_HN = isset($setting_mapping['EmailNotification_HN']) ? $setting_mapping['EmailNotification_HN'] : '';
		
		$body = 'Name: '.$data['name'].'<br>Phone: '.$data['phone'].'<br>Email: '.$data['email'].'<br> Years of experience: '.$data['experience'].'<br>The position: '.$data['job'].' tại '.$location->location.'-'.$data['job_id'].'<br>Link CV: '.$data['file_url'];
		
		
		if($data['job_id'] == '903126' || $data['job_id'] == '900045'){
			$result = ses_send_mail($subject, $body, $sendEmail_HN);
			$rs = ($result) ? true : false;
		}
		else{
			$result = ses_send_mail($subject, $body, $sendEmail_HCM);
			$rs = ($result) ? true : false;
		}
		
		return $rs;
	}
	
	public function send_mail_to_student()
	{		
		ses_send_mail('Test', 'ABC', 'luongtheman87@gmail.com');
	}

	public function get_utm(){
		$utm_campaign = $this->input->get('utm_campaign');
		$utm_source = $this->input->get('utm_source');
		$utm_medium = $this->input->get('utm_medium');
		$utm_term = $this->input->get('utm_term');
		$utm_content = $this->input->get('utm_content');
		if(!empty($utm_campaign)){
			$utm_data = array(
				'utm_campaign' => $utm_campaign,
				'utm_source' => $utm_source,
				'utm_medium' => $utm_medium,
				'utm_term' => $utm_term,
				'utm_content' => $utm_content,
			);
			$this->session->set_userdata(array('utm_data'=>$utm_data));
		}
	}
	/*PAGE*/
	public function verify_otp(){
		$data = null;
        $this->template->write('title','verify_otp');
        $this->template->write_view('content','FRONTEND/verify_otp',$data);
        $this->template->render();    
	}

	public function verify_otp_forget_pass(){
		$data = null;
        $this->template->write('title','verify_otp_forget_pass');
        $this->template->write_view('content','FRONTEND/verify_otp_forget_pass',$data);
        $this->template->render();    
	}

	public function forgot_pass(){
		$data = null;
        $this->template->write('title','forgot_pass');
        $this->template->write_view('content','FRONTEND/forgot_pass',$data);
        $this->template->render();    
	}

	public function change_password(){
		$data = null;
		
        $this->template->write('title','change_password');
        $this->template->write_view('content','FRONTEND/change_password',$data);
        $this->template->render();    
	}

	public function sign_up(){
		$national = $this->model->getNationality();
		$pim = $this->model->getPIM();
		
		$data = array(
	        'national' =>  $national,
	        'pim'	=>	$pim
	    );

        $this->template->write('title','sign_up');
        $this->template->write_view('content','FRONTEND/sign_up',$data);
        $this->template->render();    
	}

	public function sign_in(){
		$data = null;
		
        $this->template->write('title','sign_in');
        $this->template->write_view('content','FRONTEND/sign_in',$data);
        $this->template->render();    
	}
	
	/*END PAGE*/
	/*STUDENT API*/
	public function check_sign_in(){
		$phone = trim($this->input->post('phone'));
		$email = trim($this->input->post('email'));
		$pass = trim($this->input->post('pass'));
		if(md5($pass)==$this->model->checkLogin($phone, $email)){
			$user_id = $this->model->getUserId($phone, $email);
			$user_type = $this->model->getUserType($user_id);

			$this->session->set_userdata('user_type', $user_type);
			$this->session->set_userdata('user_id', $user_id);
			print $user_type;
		}else{
			print 'fail';
		}
	}

	public function resend_otp(){
		if($this->session->userdata('access_token')){
			$this->delete_access_token($this->session->userdata('access_token'));
			$this->session->unset_userdata('otp');
			$this->session->unset_userdata('access_token');
		}

		$otp = rand(1000,9999);
		$this->session->set_userdata('otp', $otp);
		
		$access_token = hash('sha256', time());
		$this->session->set_userdata('access_token', $access_token);

		$data = array(
	        'value'          =>  $access_token,
	        'created'       =>  date('Y-m-d H:i:s')
	    );
	    $this->db->insert('otp_access_token',$data);

			$phone = $this->session->userdata('phone');
			if (empty($phone)){
				$phone = $this->input->post('phone');
			}
		if(!empty($phone)){
			$this->send_token($otp, $phone);
		}
	}

	public function student_reg(){
		$phone = trim($this->input->post('phone'));

		if($this->model->validateStudent($phone)==0){
			print 'not_student';
			die;
		}

		if($this->model->checkPhoneExist($phone)==0){
			$otp = rand(1000,9999);
			/*$content = "Your SMS OTP is ".$otp.". This OTP expires at ".date( 'G:i',time()+120)." SG time on ".date( 'd M Y',time()).". ";
			print($content);
			die;*/
			$this->send_token($otp, $phone);
			
			$this->session->set_userdata('otp', $otp);
			$this->session->set_userdata('phone', $phone);

			$access_token = hash('sha256', time());
			$this->session->set_userdata('access_token', $access_token);

			$data = array(
		        'value'         =>  $access_token,
		        'created'       =>  date('Y-m-d H:i:s')
		    );
		    $this->db->insert('otp_access_token',$data);

			/*print($otp);*/
		}else{
			print 'fail';
		}
	}

	public function check_forget_pass(){
		$phone = trim($this->input->post('phone'));
		
		if($this->model->validateStudent($phone)==0){
			print 'not_student';
			die;
		}

		if($this->model->checkPhoneExist($phone)!=0){
			$otp = rand(1000,9999);
			$this->send_token($otp, $phone);
			$this->session->set_userdata('otp', $otp);
			$this->session->set_userdata('phone', $phone);
			
			$access_token = hash('sha256', time());
			$this->session->set_userdata('access_token', $access_token);

			$data = array(
		        'value'         =>  $access_token,
		        'created'       =>  date('Y-m-d H:i:s')
		    );
		    $this->db->insert('otp_access_token',$data);

			/*print($otp);*/
		}else{
			print 'fail';
		}
	}

	public function check_otp(){
		if($this->session->userdata('access_token')){
			$access_token = $this->session->userdata('access_token');
			$access_token_time_created = $this->model->getTimeCreatedAccessToken($access_token);
			if((time() - $access_token_time_created) < 120){
			 	if(!empty($this->session->userdata('otp'))){
			 		$otp = $this->input->post('otp');
					if($otp == $this->session->userdata('otp')){
						print 'success';
						$this->session->unset_userdata('otp');
						$this->session->unset_userdata('access_token');
						$this->delete_access_token($access_token);
					}else{
						print 'fail';
					}
				}
			}else{
				$this->session->unset_userdata('otp');
				$this->session->unset_userdata('access_token');
			 	print 'OTP expired';
			}
		}else{
			print "Don't have session" ;
		}
	}

	public function delete_access_token($access_token){
		$this->db->delete('otp_access_token', array('value' => $access_token)); 
	}

	public function submit_sign_up(){
		$phone = $this->session->userdata('phone');

		if (empty($phone)){
			$phone = $this->input->post('phone');
		}

		$email = trim($this->input->post('email'));
		$firstname = $this->input->post('firstname');
		$lastname = $this->input->post('lastname');
		$birthday = $this->input->post('birthday');
		$postal_code = $this->input->post('postal_code');
		$pass = $this->input->post('pass');
		$repass = $this->input->post('repass');

		if(empty($firstname)){
			print 'fail';
			die;
		}
		if(empty($lastname)){
			print 'fail';
			die;
		}
		if(empty($email)){
			print 'fail';
			die;
		}
		if(empty($birthday)){
			print 'fail';
			die;
		}
		if(empty($postal_code)){
			print 'fail';
			die;
		}
		if(empty($pass) || strlen($pass) < 3 || strlen($pass) > 12){
			print 'fail';
			die;
		}
		if($pass != $repass){
			print 'fail';
			die;
		}


		if(!empty($phone) && $this->model->checkEmailExist($email)==0){
			$nationality = $this->input->post('nationality');
			$unit = $this->input->post('unit');
			$building_name = $this->input->post('building_name');
			$street = $this->input->post('street');
			$batch_no = $this->input->post('batch_no');
			$gender = $this->input->post('gender');
			$data = array(
	            'status'        =>  1,
	            'created'       =>  date('Y-m-d H:i:s'),
	            'phone'         =>  $phone,
	            'password'      =>  md5($pass),
	            'firstname'		=>  $firstname,
	            'lastname'		=>  $lastname,
	            'fullname'		=>  $firstname.' '.$lastname,
	            'email'			=>  $email,
	            'birthday'		=>  date('Y-m-d', strtotime($birthday)),
	            'postal_code'	=>  $postal_code,
	            'type'			=>  1
			);
					
				if (!empty($gender)){
						$data['gender'] = $gender;
				}
				if (!empty($batch_no)){
					$data['batch_no'] = $batch_no;
				}
				if (!empty($nationality)){
					$data['nationality'] = $nationality;
				}
				if (!empty($unit)){
					$data['unit'] = $unit;
				}
        if (!empty($building_name)){
            $data['building_name'] = $building_name;
        }
        if (!empty($street)){
            $data['street'] = $street;
        }
	        
	      if($this->db->insert('student',$data)){
					$last_id = $this->db->insert_id();
					$this->session->set_userdata('user_id', $last_id);
					print 'success';
			}else{
				print 'fail';
			}
		}else{
			print 'fail';
		}
        
	}

	public function submit_change_pass(){
		$phone = $this->session->userdata('phone');

		if (empty($phone)){
			$phone = $this->input->post('phone');
		}

		if(!empty($phone)){
			$data = array(
	            'password'  =>  md5($this->input->post('pass'))
	        );
	        $this->db->where('phone',$phone);
	        if($this->db->update('student',$data)){
	        	 print 'success';
	        }else{
	        	print 'fail';
	        }
		}else{
        	print 'fail';
        }
	}

	public function update_user_profile(){
		if(!empty($this->input->post('student_id'))){
			if($this->model->updateUserProfile()){
				print 'suscess';
			}else{
				print 'fail';
			}
		}
	}

	public function forget_password(){
		$email = $this->input->post('email');
		if(!empty($email)){
			$token = sha1($user_id.time());
			if($this->model->updateToken($email, $token)){
				$this->sendmail_forget_password($token);
			}
		}
	}

	public function send_token($token, $phone){
		$api = new transmitsmsAPI('6f103af2ad411b5aeeeab7e78c824408', 'test@1234');
 
		 // sending to a set of numbers
		$content = "Your SMS OTP is ".$token.". This OTP expires at ".date( 'G:i:s',time()+120)." SG time on ".date( 'd M Y',time()).". ";
		$result = $api->sendSms($content, $phone, 'TIQ');
		 
		 // sending to a list
		 //$result = $api->sendSms('test', null, 'callerid', null, 6151);
		 
		if ($result->error->code == 'SUCCESS') {
		  /* echo "Message to {$result->recipients} recipients sent with ID 
		     {$result->message_id}, cost {$result->cost}Home.php";*/

		} else {
		     echo "Error: {$result->error->description}";
		}
	}

	public function sendmail_forget_password($token, $phone){
		$api = new transmitsmsAPI('6f103af2ad411b5aeeeab7e78c824408', 'test@1234');
 
		 // sending to a set of numbers
		$content = "Your SMS OTP is ".$token.". This OTP expires at ".date( 'G:i:s',time()+120)." SG time on ".date( 'd M Y',time()).". ";
		$result = $api->sendSms($content, $phone, 'TIQ');
		 
		 // sending to a list
		 //$result = $api->sendSms('test', null, 'callerid', null, 6151);
		 
		if ($result->error->code == 'SUCCESS') {
		   echo "Message to {$result->recipients} recipients sent with ID 
		     {$result->message_id}, cost {$result->cost}";

		} else {
		     echo "Error: {$result->error->description}";
		}

	}

	public function check_email_exits(){
		$email = $this->input->post('email');
		if($this->model->checkEmailExist($email)==0){
			print 'success';	
		}else{
			print 'fail';
		}
	}

	public function reset_pass(){
		$pass = $this->input->post('password');
		$token = $_GET['token'];
		if(!empty($token)){
			if($this->model->changePasswithToken($token, $pass)){
				print 'success';
			}
		}
	}
	/*STUDENT API*/
	/*API FOR MOBILE*/
	public function mobile_check_sign_in(){
		$phone = trim($this->input->post('phone'));
		$email = trim($this->input->post('email'));
		$pass = trim($this->input->post('pass'));
		if(md5($pass)==$this->model->checkLogin($phone, $email)){
			$user_id = $this->model->getUserId($phone, $email);
			$user_type = $this->model->getUserType($user_id);

			$this->session->set_userdata('user_type', $user_type);
			$this->session->set_userdata('user_id', $user_id);
			$accesstoken = $this->model->createAccessToken($user_id, $pass);
			$this->model->successResponse(null, $this->model->getUserInfo($user_id));
		}else{
			$this->model->errorResponse("Your username/email or password is incorrect");
		}
	}

	public function mobile_national_list(){
		$national  = $this->model->getNationality();
		$this->model->successResponse(null, $national);
	}

	public function mobile_pim_list(){
		$pim  = $this->model->getPIM();
		$this->model->successResponse(null, $pim);
	}

	public function mobile_student_reg(){
		$phone = trim($this->input->post('phone'));
		
		if($this->model->validateStudent($phone)==0){
			print 'not_student';
			die;
		}

		if($this->model->checkPhoneExist($phone)==0){
			$otp = rand(1000,9999);
			$this->send_token($otp, $phone);
			
			$access_token = hash('sha256', time());

			$data = array(
		        'value'         =>  $access_token,
		        'created'       =>  date('Y-m-d H:i:s'),
		        'otp_code'		=>  $otp
		    );
		    if($this->db->insert('otp_access_token',$data)){
		    	$mobile_data = array(
			        'access_token'         =>  $access_token,
			        'otp_code'		=>  $otp
			    );
		    	$this->model->successResponse(null, $mobile_data);
		    }
		}else{
			print 'fail';
		}
	}

	public function mobile_resend_otp(){
		if($this->input->post('access_token')){
			$this->delete_access_token($this->input->post('access_token'));
		}
		
		$otp = rand(1000,9999);
		$access_token = hash('sha256', time());

		$data = array(
	        'value'          =>  $access_token,
					'created'       =>  date('Y-m-d H:i:s'),
					'otp_code'		=>  $otp
	    );
	    if($this->db->insert('otp_access_token',$data)){
	    	$mobile_data = array(
		        'access_token'  =>  $access_token,
		        'otp_code'		=>  $otp
		    );
	    	$this->model->successResponse(null, $mobile_data);
	    	$phone = trim($this->input->post('phone'));
			if(!empty($phone)){
				$this->send_token($otp, $phone);
			}
	    }
	}

	public function mobile_check_otp(){
		if($this->input->post('access_token')){
			$access_token = $this->input->post('access_token');
			$access_token_time_created = $this->model->getTimeCreatedAccessToken($access_token);
			if((time() - $access_token_time_created) < 120){
			 	if($this->input->post('otp')){
			 		$otp = $this->input->post('otp');
					if($otp == $this->model->getOTPbyAccessToken($access_token)){
						print 'success';
						$this->delete_access_token($access_token);
					}else{
						print 'fail';
					}
				}
			}else{
			 	print 'OTP expired';
			}
			
		}else{
			print "Don't have session" ;
		}
	}

	public function mobile_check_forget_pass(){
		$phone = trim($this->input->post('phone'));
		
		if($this->model->validateStudent($phone)==0){
			print 'not_student';
			die;
		}
		
		if($this->model->checkPhoneExist($phone)!=0){
			$otp = rand(1000,9999);
			$this->send_token($otp, $phone);
			
			$access_token = hash('sha256', time());

			$data = array(
		        'value'         =>  $access_token,
		        'created'       =>  date('Y-m-d H:i:s'),
		        'otp_code'		=>  $otp
		    );
		    if($this->db->insert('otp_access_token',$data)){
		    	$mobile_data = array(
			        'access_token'  =>  $access_token,
			        'otp_code'		=>  $otp
			    );
		    	$this->model->successResponse(null, $mobile_data);
		    }
		}else{
			print 'fail';
		}
	}

	/*END API FOR MOBILE*/
}