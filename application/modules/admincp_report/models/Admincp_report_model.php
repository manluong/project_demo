<?php
class Admincp_report_model extends CI_Model {

	function checkLogin($user){
		$this->db->select('*');
		$this->db->where('username', $user);
		$this->db->where('status', 1);
		$query = $this->db->get('admin_nqt_users');
		
		foreach ($query->result() as $row){
			$pass = $row->password;
		}
		
		if(!empty($pass)){
			return $pass;
		}else{
			return false;
		}
	}
	
	function getFirstDate(){
		$this->db->select('created');
		$this->db->order_by('created','asc');
		$query = $this->db->get('student');
			
		return $query->result();
	}
	
	function getLoanSubmited($data){
		 $this->db->select('submit_date');
		 $this->db->where('submit_date >=', $data['first_date']);
		 $this->db->where('submit_date <=', $data['current_date']);
		 $this->db->where('status !=', 1);
		 $this->db->order_by('submit_date', 'asc'); 
		 $query = $this->db->get('student_loan_accessibility');
		 
		 return $query->result();
	}
	
	function getLoanVerified($data){
		 $this->db->select('verify_date');
		 $this->db->where('verify_date >=', $data['first_date']);
		 $this->db->where('verify_date <=', $data['current_date']);
		 $this->db->where('status', 4);
		 $this->db->order_by('verify_date', 'asc'); 
		 $query = $this->db->get('student_loan_accessibility');
		 
		 return $query->result();
	}

	function getStudent($data){
		 $this->db->select('created');
		 $this->db->where("created >= '{$data['first_date']}'");
		 $this->db->where("created <= '{$data['current_date']}'");
		 /*$this->db->where('type', 1);*/
		 $this->db->order_by('created', 'asc'); 
		 $query = $this->db->get('student');
		 
		 return $query->result();
	}
	
	/*function getConversion($data){
		 $this->db->select('*');
		 $this->db->where("time >= '{$data['first_date']}'");
		 $this->db->where("time <= '{$data['current_date']}'");
		 $this->db->order_by('time', 'asc'); 
		 $query = $this->db->get('user_count');
		 
		 return $query->result();
	}*/
	
	function getInfo($user){
		$this->db->select('*');
		$this->db->where('username', $user);
		$this->db->where('status', 1);
		$query = $this->db->get('admin_nqt_users');

		return $query->result();
	}
	
	function getSetting($slug=''){
		$this->db->select('*');
		if($slug!=''){
			$this->db->where('slug', $slug);
			$this->db->limit(1);
		}
		$query = $this->db->get('admin_nqt_settings');

		return $query->result();
	}
	
	function checkSlug($slug){
		$this->db->select('id');
		$this->db->where('slug', $slug);
		$this->db->limit(1);
		$query = $this->db->get('admin_nqt_settings');

		return $query->result();
	}
	
	public $admin_user = NULL;
	function admin_user_set($user){
		$this->admin_user = $user;
	}
	
	function getAdminUser($user){
		$this->db->select('image_url');
		$this->db->where('username', $user);
		$this->db->limit(1);
		$query = $this->db->get('admin_nqt_users');

		if($query->row()){
			return $query->row();
		}else{
			return false;
		}
	}
}