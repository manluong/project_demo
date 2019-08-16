<?php
class Sqs_model extends CI_Model {
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

    public function receivce_message($queue_url) {
        $params = array();
        $params ['QueueUrl'] = $queue_url;
        $params ['AttributeNames'] = ['All'];
        $params ['MaxNumberOfMessages'] = 5;
        try {
            $client = Aws\Sqs\SqsClient::factory($this->aws_config);
            $result = $client->receiveMessage($params);
            pr($result, 1);
        } catch (Exception $e) {
            // return FALSE;
            // for debug
            echo("Error message: ");
            echo($e->getMessage()."\n");
        }
    }

    public function list_queues() {
        $params = array();
        try {
            $client = Aws\Sqs\SqsClient::factory($this->aws_config);
            $result = $client->listQueues($params);
            pr($result, 1);
        } catch (Exception $e) {
            // return FALSE;
            // for debug
            echo("Error message: ");
            echo($e->getMessage()."\n");
        }
    }

    public function get_queue_attributes($queue_url) {
        $params = array();
        $params ['QueueUrl'] = $queue_url;
        $params ['AttributeNames'] = ['All'];
        try {
            $client = Aws\Sqs\SqsClient::factory($this->aws_config);
            $result = $client->getQueueAttributes($params);
            pr($result, 1);
        } catch (Exception $e) {
            // return FALSE;
            // for debug
            echo("Error message: ");
            echo($e->getMessage()."\n");
        }
    }
    
    public function set_permission_send_message($queue_url, $queue_arn, $topic_arn) {
        $sid = 'Sid' . str_replace('.', '', microtime(TRUE));
        $policy = '{
              "Version":"2012-10-17",
              "Statement":[
                {
                  "Sid":"%1$s",
                  "Effect":"Allow",
                  "Principal":"*",
                  "Action":"sqs:SendMessage",
                  "Resource":"%2$s",
                  "Condition":{
                    "ArnEquals":{
                      "aws:SourceArn":"%3$s"
                    }
                  }
                }
              ]
            }';
        $params ['Attributes']['Policy'] = sprintf($policy, $sid, $queue_arn, $topic_arn);
        $params ['QueueUrl'] = $queue_url;
        try {
            $client = Aws\Sqs\SqsClient::factory($this->aws_config);
            $result = $client->setQueueAttributes($params);
            pr($result, 1);
        } catch (Exception $e) {
            // return FALSE;
            // for debug
            echo("Error message: ");
            echo($e->getMessage()."\n");
        }
    }

}