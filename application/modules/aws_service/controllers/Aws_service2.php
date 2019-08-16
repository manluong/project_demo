<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Aws_service extends MX_Controller {

	private $module = 'aws_service';
	private $aws_config;

	function __construct(){
		parent::__construct();
        
        $this->load->library('aws');
		
		$this->load->model($this->module.'_model','model');
		$this->load->model ('Ses_model');
		$this->load->model ('Sns_model');
		$this->load->model ('Sqs_model');

		$this->aws_config = [
                'http'        => [ 'verify' => FALSE ],
                'credentials' => [ 'key'    => AWS_ACCESS_KEY,
                                   'secret' => AWS_SECRET_KEY, ],
                'version'     => AWS_SDK_VERSION,
                'region'      => AWS_REGION,
            ];
	}
	
	/**
	 * [index description]
	 * @return [type] [description]
	 */
	public function index() {
		$email = ['guongvo.pix@gmail.com', 'nhatnguyen.pix@gmail.com'];
		$subject = 'Test subject';
		$body = 'Test body';
		$result = ses_send_mail($subject, $body, $email);
		echo ($result) ? 'Success' : $result;
		//TODO
		exit;
		// echo 'aaaa'; exit;
		// $this->amazon_message_listener();
	}

	public function test_send_mail() {
		$rs = 'Please enter email';
		$email_address = trim($this->input->get('email'));
		$subject = 'Test subject - ' . date('Y-m-d H:i:s', time());
		$body = 'Test body';
		if(!empty($email_address)) {
			$result = ses_send_mail($subject, $body, $email_address);
			$rs = ($result) ? 'Success' : $result;
		}
		echo '<h3>'. $rs .'</h3>';
		exit;
	}

	public function verify_email_sandbox() {
		$rs = 'Please enter email';
		$email_address = trim($this->input->get('email'));
		if(!empty($email_address)) {
			$result = $this->Ses_model->verify_email_identity($email_address);
			$rs = ($result) ? 'Check email to verify' : 'Request verify failed, pls request again';
		}
		echo '<h3>'. $rs .'</h3>';
		exit;
	}

	/**
	 * [amazon_message_listener description]
	 * @return [type] [description]
	 */
	public function amazon_message_listener() {
		$params     = array();
		$input_json = @file_get_contents('php://input');
		$data       = @json_decode($input_json, TRUE);

		///// for debug
		// $log_arr = array(
		// 	'location' => __FILE__ ,
		// 	'function' => 'get_bounce-json',
		// 	'params' => $input_json,
		// );
		debug_log_from_config($log_arr);
		///// End for debug

		//Confirm Subscription
		if( isset($data ['Type']) && ($data ['Type'] == 'SubscriptionConfirmation') ) {
			if( ! empty($data ['TopicArn']) && ! empty($data ['Token']) ) {
				$topic_arn = $data ['TopicArn'];
				$token     = $data ['Token'];
				$this->Sns_model->confirm_subscription($topic_arn, $token);
			}
			return TRUE;
		}
		//parse Message
		if( ! empty($data ['Message']) ) {
			$data ['Message'] = @json_decode($data ['Message'], TRUE);
		}
		//store message aws to db bounces OR complaint
		if( empty($data ['Message']['notificationType']) ) {
			return FALSE;
		}

		$message_data = $data ['Message'];
		//message for Bounce
		if( $message_data ['notificationType'] == 'Bounce') {
			$this->save_message_bounce($message_data, $input_json);
		}
		//message for complaint
		if( $message_data ['notificationType'] == 'Complaint') {
			$this->save_message_complaint($message_data, $input_json);
		}
	}

	/**
	 * [save_message_bounce description]
	 * @param  [type] $message_data [description]
	 * @param  [type] $draw_input   [description]
	 * @return [type]               [description]
	 */
	private function save_message_bounce($message_data, $draw_input) {
		$insert_data = $list_email = array();
		$target_data                = $message_data ['bounce'];
		$params ['bounce_type']     = $target_data ['bounceType'];
		$params ['bounce_sub_type'] = $target_data ['bounceSubType'];
		$params ['draw_input']      = $draw_input;
		foreach ($target_data ['bouncedRecipients'] as $key => $item) {
			$list_email [] = $params ['email'] = $item ['emailAddress'];
			$insert_data [] = $params;
			unset($params ['email']);
		}
		//Do insert to database
		$this->model->save_bounce_data($insert_data);
	}

	/**
	 * [save_message_complaint description]
	 * @param  [type] $message_data [description]
	 * @param  [type] $draw_input   [description]
	 * @return [type]               [description]
	 */
	private function save_message_complaint($message_data, $draw_input) {
		$insert_data = $list_email = array();
		$target_data = $message_data ['complaint'];
		$params ['draw_input'] = $draw_input;
		foreach ($target_data ['complainedRecipients'] as $key => $item) {
			$list_email [] = $params ['email'] = $item ['emailAddress'];
			$insert_data [] = $params;
			unset($params ['email']);
		}
		//Do insert to database
		$this->model->save_complaint_data($insert_data);
	}

	/**
	 * [test_send_email_bounce description]
	 * @return [type] [description]
	 */
	public function test_send_email_bounce() {
		$subject= 'test - ' . date('Y-m-d H:i:s');
		$body='Test send bounce';
		$recepient_email = 'bounce@simulator.amazonses.com';
		ses_send_mail($subject, $body, $recepient_email);
		echo 'Send Test email bounce Done';
		exit;
	}

	/**
	 * [test_send_email_complaint description]
	 * @return [type] [description]
	 */
	public function test_send_email_complaint() {
		$subject= 'test - ' . date('Y-m-d H:i:s');
		$body='Test send complaint';
		$recepient_email = 'complaint@simulator.amazonses.com';
		ses_send_mail($subject, $body, $recepient_email);
		echo 'Send Test email complaint Done';
		exit;
	}

	/**
	 * [test_send_email_delivery description]
	 * @return [type] [description]
	 */
	public function test_send_email_delivery() {
		$subject= 'test - ' . date('Y-m-d H:i:s');
		$body='Test send delivery';
		$recepient_email = 'delivery@simulator.amazonses.com';
		ses_send_mail($subject, $body, $recepient_email);
		echo 'Send Test email delivery Done';
		exit;
	}

	public function test_parse() {
		$params = array();
		$input_json = @file_get_contents ( 'php://input' );
		$parse_json = @json_decode($input_json, TRUE);

		if( ! empty($parse_json['Message'])) {
			$parse_json['Message'] = @json_decode($parse_json['Message'], TRUE);
		}

		pr($parse_json, 1);
	}
}