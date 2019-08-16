<?php
class Sns_model extends CI_Model {
	private $aws_config;

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
	}

    /**
     * [list_topics description]
     * @return [type] [description]
     */
    public function list_topics() {
        $params = array();
        try {
            $client = Aws\Sns\SnsClient::factory($this->aws_config);
            $result = $client->listTopics($params);
            pr($result, 1);
        } catch (Exception $e) {
            // return FALSE;
            // for debug
            echo("Error message: ");
            echo($e->getMessage()."\n");
        }
    }

    /**
     * [create_topic description]
     * @param  [type] $topic_name [description]
     * @return [type]             [description]
     */
    public function create_topic($topic_name, $display_name) {
        $params = array();
        $params ['Name'] = $topic_name;
        $params ['DisplayName'] = $display_name;
        try {
            $client = Aws\Sns\SnsClient::factory($this->aws_config);
            $result = $client->createTopic($params);
            pr($result, 1);
        } catch (Exception $e) {
            // return FALSE;
            // for debug
            echo("Error message: ");
            echo($e->getMessage()."\n");
        }
    }

    /**
     * [get_topic_attributes description]
     * @param  [type] $topic_arn [description]
     * @return [type]            [description]
     */
    public function get_topic_attributes($topic_arn) {
        $params = array();
        $params ['TopicArn'] = $topic_arn;
        try {
            $client = Aws\Sns\SnsClient::factory($this->aws_config);
            $result = $client->getTopicAttributes($params);
            pr($result, 1);
        } catch (Exception $e) {
            // return FALSE;
            // for debug
            echo("Error message: ");
            echo($e->getMessage()."\n");
        }
    }

    /**
     * [delete_topic description]
     * @param  [type] $topic_arn [description]
     * @return [type]            [description]
     */
    public function delete_topic($topic_arn) {
        $params = array();
        $params ['TopicArn'] = $topic_arn;
        try {
            $client = Aws\Sns\SnsClient::factory($this->aws_config);
            $result = $client->deleteTopic($params);
            pr($result, 1);
        } catch (Exception $e) {
            // return FALSE;
            // for debug
            echo("Error message: ");
            echo($e->getMessage()."\n");
        }
        /*
        
         */
    }

    /**
     * [subscribe description]
     * @param  [type] $topic_arn [description]
     * @param  [type] $protocol  [description]
     * @param  [type] $endpoint  [description]
     * @return [type]            [description]
     */
    public function subscribe($topic_arn, $protocol, $endpoint) {
        $params = array();
        $params ['TopicArn'] = $topic_arn;
        $params ['Protocol'] = $protocol;
        $params ['Endpoint'] = $endpoint;

        try {
            $client = Aws\Sns\SnsClient::factory($this->aws_config);
            $result = $client->subscribe($params);
            pr($result, 1);
        } catch (Exception $e) {
            // return FALSE;
            // for debug
            echo("Error message: ");
            echo($e->getMessage()."\n");
        }
    }

    /**
     * [confirm_subscription description]
     * @param  [type] $topic_arn [description]
     * @param  [type] $token     [description]
     * @return [type]            [description]
     */
    public function confirm_subscription($topic_arn, $token) {
        $params = array();
        $params ['TopicArn'] = $topic_arn;
        $params ['Token'] = $token;

        try {
            $client = Aws\Sns\SnsClient::factory($this->aws_config);
            $result = $client->confirmSubscription($params);
            pr($result, 1);
        } catch (Exception $e) {
            // return FALSE;
            // for debug
            echo("Error message: ");
            echo($e->getMessage()."\n");
        }
    }

    /**
     * [unsubscribe description]
     * @param  [type] $subscription_arn [description]
     * @return [type]                   [description]
     */
    public function unsubscribe($subscription_arn) {
        
        $params = array();
        $params ['SubscriptionArn'] = $subscription_arn;

        try {
            $client = Aws\Sns\SnsClient::factory($this->aws_config);
            $result = $client->unsubscribe($params);
            pr($result, 1);
        } catch (Exception $e) {
            // return FALSE;
            // for debug
            echo("Error message: ");
            echo($e->getMessage()."\n");
        }
    }

	
}