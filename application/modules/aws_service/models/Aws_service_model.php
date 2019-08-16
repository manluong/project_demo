<?php
class Aws_service_model extends CI_Model {
	private $module = 'aws_service';
	private $table = 'test';
	private $table_bounce = 'aws_ses_bounce';
	private $table_complaint = 'aws_ses_complaint';

	/**
	 * [save_bounce_data description]
	 * @param  [type] $params [description]
	 * @return [type]         [description]
	 */
	function save_bounce_data($params) {
		$created = date('Y-m-d H:i:s', time());
		foreach ($params as $key => $item) {
			if ( $this->check_email_not_exists_in_bounce($item ['email']) ) {
				$data = $item;
				$data ['created'] = $created;
				$this->db->insert($this->table_bounce, $data);
			}
		}
	}

	/**
	 * [save_complaint_data description]
	 * @param  [type] $params [description]
	 * @return [type]         [description]
	 */
	function save_complaint_data($params) {
		$created = date('Y-m-d H:i:s', time());
		foreach ($params as $key => $item) {
			if ( $this->check_email_not_exists_in_complaint($item ['email']) ) {
				$data = $item;
				$data ['created'] = $created;
				$this->db->insert($this->table_complaint, $data);
			}
		}
	}

	/**
	 * [check_email_not_exists_in_bounce description]
	 * @param  [type] $email [description]
	 * @return [type]        [description]
	 */
	function check_email_not_exists_in_bounce($email) {
		$this->db->select('*');
		$this->db->where('email', $email);
		$query = $this->db->get($this->table_bounce);
		return ( empty ($query->result_array ()) ) ? TRUE : FALSE;
	}

	/**
	 * [check_email_not_exists_in_complaint description]
	 * @param  [type] $email [description]
	 * @return [type]        [description]
	 */
	function check_email_not_exists_in_complaint($email) {
		$this->db->select('*');
		$this->db->where('email', $email);
		$query = $this->db->get($this->table_complaint);
		return ( empty ($query->result_array ()) ) ? TRUE : FALSE;
	}
}