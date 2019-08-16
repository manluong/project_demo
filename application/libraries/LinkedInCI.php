<?php defined('BASEPATH') OR exit('No direct script access allowed');

// Include the autoloader provided in the SDK
require_once 'google-api-php-client/init.php';

/**
* Google Client API for CodeIgniter
*/
class LinkedInCI {

}

class LinkedIn {
	private $client_id;
	private $client_secret;
	private $redirect_uri;
	private $state;
	private $scope;
	private $endpoint_auth;
	private $endpoint_get_access_token;
	private $access_token;
	private $basic_profile_fields;
	private $default_fields;

	public function __construct($client_id, $client_secret, $redirect_uri) {
		$this->client_id = $client_id;
		$this->client_secret = $client_secret;
		$this->redirect_uri = $redirect_uri;
		$this->endpoint_auth = 'https://www.linkedin.com/oauth/v2/authorization';
		$this->endpoint_get_access_token = 'https://www.linkedin.com/oauth/v2/accessToken';
		$this->basic_profile_fields = array('id', 'first-name', 'last-name', 'maiden-name', 'formatted-name', 'phonetic-first-name', 'phonetic-last-name', 'formatted-phonetic-name', 'headline', 'location', 'industry', 'current-share', 'num-connections', 'num-connections-capped', 'summary', 'specialties', 'positions', 'picture-url', 'picture-urls::(original)', 'site-standard-profile-request', 'api-standard-profile-request', 'public-profile-url', 'email-address');
		$this->default_fields = array('id', 'first-name', 'last-name', 'maiden-name', 'formatted-name', 'public-profile-url', 'email-address');
	}
	
	private function _generateState($time_hash = FALSE) {
		if ($time_hash === FALSE) {
			$mc_time = explode('.',number_format(microtime(TRUE), 4))[1];
			$date = new DateTime('now', new DateTimeZone('UTC'));
			$timestamp = $date->getTimestamp();
			$time_request = $date->getTimestamp() . $mc_time;
			$time_hash = hash_hmac('adler32', $time_request, 'LinkedInSDK'); 
		}
		
		$algorithm  = 'ripemd160';
        $secret_key = $this->client_secret;
        $hash_1 = hash_hmac($algorithm, $time_hash, $secret_key);
        $algorithm_step_2 = 'sha1';
        $secret_key_step_2 = $this->client_secret;
        $hash_step_2 = hash_hmac($algorithm_step_2, $hash_1, $secret_key_step_2);
        $state = $time_hash . ':' . $hash_step_2;
        return $state;
	}

	private function _verifyState($state) {
		$data = explode(':', $state);
		$time_hash = $data [0];
		$_state = $this->_generateState($time_hash);
		
		return ($state === $_state) ? TRUE : FALSE;
	}

	protected function doRequestGetAccessToken($code) {
        $result = $form_data = array();
		$url = $this->endpoint_get_access_token;
		$form_data ['grant_type']    = 'authorization_code';
		$form_data ['code']          = $code;
		$form_data ['redirect_uri']  = $this->redirect_uri;
		$form_data ['client_id']     = $this->client_id;
		$form_data ['client_secret'] = $this->client_secret;
        
        $data = http_build_query($form_data);
        $content_length = strlen($data);
        $headers = array(
	        	'Content-Type:application/x-www-form-urlencoded',
	        	'Content-Length:' . $content_length
	        );
        static $ch = NULL;
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POST, TRUE);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
        // Do request
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
        curl_setopt($ch, CURLOPT_FRESH_CONNECT, TRUE); // Do Not Cache
        curl_setopt($ch, CURLOPT_TIMEOUT, 120); //timeout in seconds

        $response         = curl_exec($ch);
        $headers_response = curl_getinfo($ch);
        curl_close($ch);
        $response = @json_decode($response, TRUE);
        if ($response === FALSE) {
        	exit('Error: Cannot request to LinkedIn');
        }
        else if ( ! empty($headers_response ['http_code']) && $headers_response ['http_code'] == 200) {
        	
        	if (isset($response ['access_token'])) {
        		$result = $this->access_token = $response ['access_token'];
        	} else {
        		$result = FALSE;
        	}
        } else {
        	$error_code = isset($response ['error']) ? $response ['error'] : '';
        	$error_description = isset($response ['error_description']) ? $response ['error_description'] : '';
        	$error_message = (!empty($error_code) ? $error_code.' - ' : '') . $error_description;
        	exit('Error: ' . $error_message);
        }

        return $result;
	}

	protected function doRequestGetBasicProfile($access_token, $profile_fields = array()) {
		$fiels = array();
		if (empty($profile_fields) || ! is_array($profile_fields)) {
			$fields = $this->default_fields;
		} else {
			//check fields valid
			foreach ($profile_fields as $key => $field) {
				if (in_array($field, $this->basic_profile_fields)) {
					$fields[] = $field;
				}
			}
		}
		$fields_string = implode(',', $fields);
		$url = "https://api.linkedin.com/v1/people/~:({$fields_string})?format=json";

		$headers = array('Authorization:Bearer ' . $access_token);
        static $ch = NULL;
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
        // Do request
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
        curl_setopt($ch, CURLOPT_FRESH_CONNECT, TRUE); // Do Not Cache
        curl_setopt($ch, CURLOPT_TIMEOUT, 120); //timeout in seconds

        $response         = curl_exec($ch);
        $headers_response = curl_getinfo($ch);
        curl_close($ch);
        $response = @json_decode($response, TRUE);
        if ($response === FALSE) {
        	exit('Error: Cannot request to LinkedIn');
        }
        else if ( ! empty($headers_response ['http_code']) && $headers_response ['http_code'] == 200) {
        	$result = $response;
        } else {
        	$error_code = isset($response ['error']) ? $response ['error'] : '';
        	$error_description = isset($response ['error_description']) ? $response ['error_description'] : '';
        	$error_message = (!empty($error_code) ? $error_code.' - ' : '') . $error_description;
        	exit('Error: ' . $error_message);
        }
        return $result;
	}

	public function setRedirectURI($redirect_uri) {
		if (filter_var($redirect_uri, FILTER_VALIDATE_URL) !== FALSE) {
			$this->redirect_uri = $redirect_uri;
		} else {
			exit('Error: Redirect URL invalid');
			
		}
	}

	public function generateState() {
		return $this->_generateState();
	}

	public function verifyState($state) {
		return $this->_verifyState($state);
	}

	public function getOAuthURL() {
		$queries = array();
        $queries ['response_type'] = "code";
        $queries ['client_id'] = $this->client_id;
        $queries ['redirect_uri'] = $this->redirect_uri;
        $queries ['state'] = $this->_generateState();
        $query_string = http_build_query($queries);
        return $this->endpoint_auth . '?' . $query_string;
	}

	public function getAccessToken($code = '') {
		$result = $this->doRequestGetAccessToken($code);
		return $result;
	}

	public function getUserProfile($profile_fields = array()) {
		$access_token = $this->access_token;
		return $this->doRequestGetBasicProfile($access_token, $profile_fields);
	}

	public function getUserProfileByAccessToken($access_token, $profile_fields = array()) {
		return $this->doRequestGetBasicProfile($access_token, $profile_fields);
	}


}