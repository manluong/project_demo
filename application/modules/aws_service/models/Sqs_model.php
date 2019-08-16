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
	
	public function receivce_message_queue() {
	try {
		$sqs_client = Aws\Sqs\SqsClient::factory($this->aws_config);

		// Get the queue URL from the queue name.
		$result = $sqs_client->getQueueUrl(array('QueueName' => "testREDM"));
		$queue_url = $result->get('QueueUrl');

		// Receive a message from the queue
		$result = $sqs_client->receiveMessage(array(
			'QueueUrl' => $queue_url
		));

		if ($result['Messages'] == null) {
			// No message to process
			exit;
		}

		// Get the message information
		$result_message = array_pop($result['Messages']);
		$queue_handle = $result_message['ReceiptHandle'];
		$message_json = $result_message['Body'];

		// Do some processing...

	} catch (Exception $e) {
		die('Error receiving message to queue ' . $e->getMessage());
}
    } 
	
	public function sending_message() {
		try {
			// Instantiate the client
			$client = Aws\Sqs\SqsClient::factory($this->aws_config);

			// Get the queue URL from the queue name.
			$queue_options = array(
				'QueueName' => 'testREDM'
			);
			$client->createQueue($queue_options);
			$result = $client->getQueueUrl(array('QueueName' => "testREDM"));
			$queue_url = $result->get('QueueUrl');pr($queue_url);
			//The message we will be sending
		    $our_message = array('foo' => 'blah', 'bar' => 'http://localhost/2017_REDM/CODE/cms/admincp_contact');

			//Send the message
			$client->sendMessage(array(
				'QueueUrl' => $queue_url,
				'MessageBody' => json_encode($our_message)
			));
			
				$result = $client->receiveMessage(array(
				'QueueUrl' => $queue_url
			));

			if ($result['Messages'] == null) {
				// No message to process
				exit;
			}

			// Get the message information
			$result_message = array_pop($result['Messages']);
			$queue_handle = $result_message['ReceiptHandle'];
			$message_json = $result_message['Body'];
			pr($result_message);
		} catch (Exception $e) {
			die('Error sending message to queue ' . $e->getMessage());
		}
    }
	
	public function create_new_queue() {
		try {
			// Instantiate the client
			$sqs_client = Aws\Sqs\SqsClient::factory($this->aws_config);
			//pr($sqs_client);
			// Create the queue
			$queue_options = array(
				'QueueName' => 'sendMessage1'
			);
			$sqs_client->createQueue($queue_options);
			$result = $sqs_client->getQueueUrl(array('QueueName' => "sendMessage1"));
			$queue_url = $result->get('QueueUrl');
			pr($queue_url, 1);
		} catch (Exception $e) {
			die('Error creating new queue ' . $e->getMessage());
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