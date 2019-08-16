<?php
/**
 * PIX Interaction
 * Amazon Simple Email Service
 * Document SDK: http://docs.aws.amazon.com/aws-sdk-php/v3/api/api-email-2010-12-01.html
 */
class Ses_model extends CI_Model {
	private $aws_config;
    private $notification_type;
    private $identity_type;

	function __construct() {
		parent::__construct();
        $this->load->library('aws');
		$this->aws_config = [
            'http'        => [ 'verify' => FALSE ],
            'credentials' => [ 'key'    => AWS_ACCESS_KEY,
                               'secret' => AWS_SECRET_KEY, ],
            'version'     => AWS_SDK_VERSION,
            'region'      => AWS_REGION,
        ];

        $this->notification_type = array('Bounce', 'Complaint', 'Delivery');
        $this->identity_type = array( 'Domain', 'EmailAddress');
	}

    public function get_list_notification_type() {
        return $this->notification_type;
    }

    public function get_list_identity_type() {
        return $this->identity_type;
    }

    public function list_identities($identity_type = '', $max_items = 10, $next_token = '') {
        $params = array();
        $params ['IdentityType'] = $identity_type;
        $params ['MaxItems'] = intval($max_items);
        $params ['NextToken'] = $next_token;
        try {
            $client = Aws\Ses\SesClient::factory($this->aws_config);
            $result = $client->listIdentities($params);
            return $result->get('Identities'); //Identities, NextToken
            // pr($result, 1);
        } catch (Exception $e) {
            return FALSE;
            // // for debug
            // echo("Error message: ");
            // echo($e->getMessage()."\n");
        }
    }

    public function get_identity_verification_attributes($identities) {
        $params = array();
        $params ['Identities'] = is_array($identities) ? $identities : array($identities);
        
        try {
            $client = Aws\Ses\SesClient::factory($this->aws_config);
            $result = $client->getIdentityVerificationAttributes($params);
            return $result->get('VerificationAttributes');
            return TRUE;
        } catch (Exception $e) {
            return FALSE;
            // // for debug
            // echo("Error message: ");
            // echo($e->getMessage()."\n");
        }
    }

    public function get_identity_notification_attributes($identities) {
        $params = array();
        $params ['Identities'] = is_array($identities) ? $identities : array($identities);
        
        try {
            $client = Aws\Ses\SesClient::factory($this->aws_config);
            $result = $client->getIdentityNotificationAttributes($params);
            return $result->get('NotificationAttributes');
            return TRUE;
        } catch (Exception $e) {
            return FALSE;
            // // for debug
            // echo("Error message: ");
            // echo($e->getMessage()."\n");
        }
    }

    public function verify_domain_identity($domain) {
        $params = array();
        $params ['Domain'] = $domain;
        try {
            $client = Aws\Ses\SesClient::factory($this->aws_config);
            $result = $client->verifyDomainIdentity($params);
            pr($result, 1);
        } catch (Exception $e) {
            // return FALSE;
            // for debug
            echo("Error message: ");
            echo($e->getMessage()."\n");
        }
    }

    public function verify_email_identity($email_address) {
        $params = array();
        $params ['EmailAddress'] = $email_address;
        try {
            $client = Aws\Ses\SesClient::factory($this->aws_config);
            $result = $client->verifyEmailIdentity($params);
            return TRUE;
        } catch (Exception $e) {
            return FALSE;
            // // for debug
            // echo("Error message: ");
            // echo($e->getMessage()."\n");
        }
    }

    public function get_send_quota() {
        $params = array();

        try {
            $client = Aws\Ses\SesClient::factory($this->aws_config);
            $result = $client->getIdentityNotificationAttributes($params);
            return $result;
            return TRUE;
        } catch (Exception $e) {
            return FALSE;
            // // for debug
            // echo("Error message: ");
            // echo($e->getMessage()."\n");
        }

    }

    public function set_identity_feedback_forwarding_enabled($identity, $is_enabled) {
        $params = array();
        $params ['Identity'] = $identity;
        $params ['ForwardingEnabled'] = ($is_enabled == TRUE) ? TRUE : FALSE;

        try {
            $client = Aws\Ses\SesClient::factory($this->aws_config);
            $result = $client->setIdentityFeedbackForwardingEnabled($params);
            return TRUE;
        } catch (Exception $e) {
            return FALSE;
            // // for debug
            // echo("Error message: ");
            // echo($e->getMessage()."\n");
        }
    }

    public function set_identity_headers_in_notifications_enabled($identity, $notification_type, $is_enabled) {
        $params = array();
        $params ['Identity'] = $identity;
        $params ['ForwardingEnabled'] = ($is_enabled == TRUE) ? TRUE : FALSE;
        $params ['NotificationType'] = $notification_type;
        
        try {
            $client = Aws\Ses\SesClient::factory($this->aws_config);
            $result = $client->setIdentityHeadersInNotificationsEnabled($params);
            return TRUE;
        } catch (Exception $e) {
            return FALSE;
            // // for debug
            // echo("Error message: ");
            // echo($e->getMessage()."\n");
        }
    }
	
}