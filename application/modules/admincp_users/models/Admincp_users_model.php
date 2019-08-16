<?php
class Admincp_users_model extends CI_Model {
	private $module = 'admincp_users_model';
	private $table = 'users';

	function getsearchContent($limit,$page){
		$this->db->select($this->table.'.*');
		
		$this->db->limit($limit,$page);
		$this->db->order_by($this->input->post('func_order_by'),$this->input->post('order_by'));
		if($this->input->post('content')!='' && $this->input->post('content')!='type here...'){
			$this->db->where('`email` LIKE "%'.$this->input->post('content').'%" OR `firstname` LIKE "%'.$this->input->post('content').'%" OR `lastname` LIKE "%'.$this->input->post('content').'%"');
		}
		if($this->input->post('dateFrom')!='' && $this->input->post('dateTo')==''){
			$this->db->where($this->table.'.created_at >= "'.date('Y-m-d 00:00:00',strtotime($this->input->post('dateFrom'))).'"');
		}
		if($this->input->post('dateFrom')=='' && $this->input->post('dateTo')!=''){
			$this->db->where($this->table.'.created_at <= "'.date('Y-m-d 23:59:59',strtotime($this->input->post('dateTo'))).'"');
		}
		if($this->input->post('dateFrom')!='' && $this->input->post('dateTo')!=''){
			$this->db->where($this->table.'.created_at >= "'.date('Y-m-d 00:00:00',strtotime($this->input->post('dateFrom'))).'"');
			$this->db->where($this->table.'.created_at <= "'.date('Y-m-d 23:59:59',strtotime($this->input->post('dateTo'))).'"');
		}
		$query = $this->db->get($this->table);

		return $query->result();
	}
	
	function getTotalsearchContent(){
		$this->db->select('*');
		if($this->input->post('content')!='' && $this->input->post('content')!='type here...'){
			$this->db->where('`email` LIKE "%'.$this->input->post('content').'%" OR `firstname` LIKE "%'.$this->input->post('content').'%" OR `lastname` LIKE "%'.$this->input->post('content').'%"');
		}
		if($this->input->post('dateFrom')!='' && $this->input->post('dateTo')==''){
			$this->db->where($this->table.'.created_at >= "'.date('Y-m-d 00:00:00',strtotime($this->input->post('dateFrom'))).'"');
		}
		if($this->input->post('dateFrom')=='' && $this->input->post('dateTo')!=''){
			$this->db->where($this->table.'.created_at <= "'.date('Y-m-d 23:59:59',strtotime($this->input->post('dateTo'))).'"');
		}
		if($this->input->post('dateFrom')!='' && $this->input->post('dateTo')!=''){
			$this->db->where($this->table.'.created_at >= "'.date('Y-m-d 00:00:00',strtotime($this->input->post('dateFrom'))).'"');
			$this->db->where($this->table.'.created_at <= "'.date('Y-m-d 23:59:59',strtotime($this->input->post('dateTo'))).'"');
		}
		$query = $this->db->count_all_results($this->table);

		if($query > 0){
			return $query;
		}else{
			return false;
		}
	}
	
	function getDetailManagement($id){
		$this->db->select('*');
		$this->db->where('id',$id);
		$query = $this->db->get($this->table);

		return $query->result();
	}
	
	function saveManagement($perm=''){

		$_thumbnail_url = $_image_url = '';

		if($_POST['hiddenIdAdmincp']==0){
			//Kiểm tra đã tồn tại chưa?
			$checkData = $this->checkData(trim($_POST['email']));
			if($checkData){
				print 'error-email-exists';
				exit;
			}


			$data = array(
				'status'		=>	isset($_POST['status']) ? 1 : 0,
				'firstname'		=>	trim(ucwords($_POST['firstname'])),
				'lastname'		=> 	trim(ucwords($_POST['lastname'])),
				'email'			=> 	trim($_POST['email']),
				'password'		=> 	md5($_POST['password']),
				'gender' 		=> 	(int)$_POST['gender'],
				'birthday' 		=> 	date('Y-m-d', strtotime($_POST['birthday'])),
				'phone' 		=> 	trim($_POST['phone']),
				'country_id'	=> 	(int)$_POST['country'],
				'city_id'		=>	isset($_POST['city']) ? (int)$_POST['city'] : 0,
				'user_role' 	=> 	(int)$_POST['user_role'],
				'social_account'=> 	trim($_POST['social_link']),
				'account_created_by' =>	ACCOUNT_CREATED_BY_NORMAL,
				// 'avatar'		=>	trim($_POST['image_urlAdmincp']),
				// 'thumbnail'		=>	trim($_POST['thumbnail_urlAdmincp']),
				'created_at'	=>	date('Y-m-d H:i:s'),
				'updated_at'	=> 	'0000-00-00 00:00:00'
			);
			

			if($data['user_role'] == USER_ROLE_PRO){
				$data['user_type'] = (int)$_POST['user_type'];
			}else {
				$data['user_type'] = 0;
			}

			if( ! empty($_POST['thumbnail_urlAdmincp'])) {
				$pre_url = $_POST['thumbnail_urlAdmincp'];
				$_thumbnail_url = move_file_from_url('thumb_avatar', $pre_url, TRUE);
			}

			if( ! empty($_POST['image_urlAdmincp']) ) {
				$pre_url = $_POST['image_urlAdmincp'];
				$_image_url = move_file_from_url('avatar', $pre_url, FALSE);
			}

			$data['avatar'] = $_image_url;
			$data['thumbnail'] = $_thumbnail_url;

			if($this->db->insert($this->table,$data)){
				modules::run('admincp/saveLog',$this->module,$this->db->insert_id(),'Add new','Add new');
				return true;
			}
		}else{
			$result = $this->getDetailManagement($_POST['hiddenIdAdmincp']);
			//Kiểm tra đã tồn tại chưa?
			if($result[0]->email != trim($_POST['email'])){
				$checkData = $this->checkData(trim($_POST['email']));
				if($checkData){
					print 'error-email-exists';
					exit;
				}
			}
			
			if($this->input->post('password')==''){
				$pass = $result[0]->password;
			}else{
				$pass = md5($_POST['password']);
			}

			$data = array(
				'status'		=>	isset($_POST['status']) ? 1 : 0,
				'firstname'		=>	trim(ucwords($_POST['firstname'])),
				'lastname'		=> 	trim(ucwords($_POST['lastname'])),
				'email'			=> 	trim($_POST['email']),
				'password'		=> 	$pass,
				'gender' 		=> 	(int)$_POST['gender'],
				'birthday' 		=> 	date('Y-m-d', strtotime($_POST['birthday'])),
				'phone' 		=> 	trim($_POST['phone']),
				'country_id'	=> 	(int)$_POST['country'],
				'city_id'		=>	isset($_POST['city']) ? (int)$_POST['city'] : 0,
				'user_role' 	=> 	(int)$_POST['user_role'],
				'social_account'=> 	trim($_POST['social_link']),
				'account_created_by' =>	ACCOUNT_CREATED_BY_NORMAL,
				// 'avatar'		=>	trim($_POST['image_urlAdmincp']),
				// 'thumbnail'		=>	trim($_POST['thumbnail_urlAdmincp']),
				'updated_at'	=> 	date('Y-m-d H:i:s')
			);

			if($data['user_role'] == USER_ROLE_PRO){
				$data['user_type'] = (int)$_POST['user_type'];
			}else {
				$data['user_type'] = 0;
			}

			if( ! empty($_POST['thumbnail_urlAdmincp']) ) {
				$pre_url = $_POST['thumbnail_urlAdmincp'];
				$_thumbnail_url = move_file_from_url('thumb_avatar', $pre_url, TRUE);
			}else{
				$_thumbnail_url = $result[0]->thumbnail;
			}

			if( ! empty($_POST['image_urlAdmincp']) ) {
				$pre_url = $_POST['image_urlAdmincp'];
				$_image_url = move_file_from_url('avatar', $pre_url, FALSE);
			}else{
				$_image_url =  $result[0]->avatar;
			}

			$data['avatar'] = $_image_url;
			$data['thumbnail'] = $_thumbnail_url;

			modules::run('admincp/saveLog',$this->module,$this->input->post('hiddenIdAdmincp'),'','Update',$result,$data);

			$this->db->where('id',$_POST['hiddenIdAdmincp']);
			if($this->db->update($this->table,$data)){
				return true;
			}
		}
		return false;
	}
	
	function checkData($email){
		$this->db->select('id');
		$this->db->where('email',$email);
		$this->db->limit(1);
		$query = $this->db->get($this->table);

		return $query->result();
	}
	
	function getData($username){
		$this->db->select('id,permission');
		$this->db->where('username',$username);
		$this->db->limit(1);
		$query = $this->db->get($this->table);

		return $query->result();
	}
	
	function list_accounts($cus=0,$group=0){
		$this->db->select('*');
		if($cus!=0){
			$this->db->where('custom_permission',0);
		}
		if($group!=0){
			$this->db->where('group_id',$group);
		}
		$this->db->order_by('username','ASC');
		$query = $this->db->get($this->table);

		return $query->result();
	}

	public function list_country(){
		$this->db->select('*');
		$query = $this->db->get('countries');

		return $query->result();
	}

	public function list_city($countryID = 0){
		if(!empty($countryID)){
			$this->db->select('states.id AS state_id, states.name AS state_name, cities.id AS city_id, cities.name AS city_name');
			$this->db->from('cities');
			$this->db->join('states', 'cities.state_id = states.id', 'inner');
			$this->db->where('states.country_id', $countryID);
			$query = $this->db->get();
			
			return $query->result();
		}else{
			return FALSE;
		}
	}

	// public function list_city($countryID = 0){
	// 	if(!empty($countryID)){
	// 		$this->db->select('states.id AS state_id, states.name AS state_name');
	// 		$this->db->from('states');
	// 		$this->db->where('country_id', $countryID);
	// 		$query = $this->db->get();
	// 		if($query->result()){
	// 			return $query->result();
	// 		}else{
	// 			return FALSE;
	// 		}
	// 	}else{
	// 		return FALSE;
	// 	}
	// }
}