<?php
class Export_user_model extends MY_Model {
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