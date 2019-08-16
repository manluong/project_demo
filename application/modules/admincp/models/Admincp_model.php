<?php
class Admincp_model extends CI_Model {

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