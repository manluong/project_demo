<?php
class Admincp_settings_model extends CI_Model {
	private $module = 'admincp_settings';
	private $table = 'admin_nqt_settings';

	function getsearchContent($limit = 0, $page = -1){
		$result = false;
		$main_table = $this->table;
		
		$is_count_total_rows = ($limit == 0); // To get total_rows
		
		if($is_count_total_rows){ 
             $this->db->select("{$main_table}.id");
        } else {
           $this->db->select("{$main_table}.*");
        }
		
		$this->db->from($main_table);
		
		if(!$is_count_total_rows){
			// Limit
			$this->db->limit($limit,$page);
			
			// Order
			$order_by = $this->input->post('func_order_by');
			$order_by = "{$main_table}.{$order_by}";
			$order_direction = $this->input->post('order_by');
			$this->db->order_by($order_by,$order_direction);
			$this->db->order_by("{$main_table}.id",'asc');
		}
		
		/*Begin: Condition*/
		// Begin - Search condition
		$content = $this->input->post('content');
		if(!empty($content)){
			$search_condition_arr = array(
				"{$main_table}.slug LIKE '%{$content}%'",
				"{$main_table}.content LIKE '%{$content}%'",
			);
			$search_condition = implode($search_condition_arr, ' OR ');
			$search_condition = "( {$search_condition} )";
			$this->db->where($search_condition);
		}
		$dateFrom = $this->input->post('dateFrom');
		$dateTo = $this->input->post('dateTo');
		if(!empty($dateFrom) || !empty($dateTo)){
			$datetimeFrom = date('Y-m-d 00:00:00',strtotime($dateFrom));
			$datetimeTo = date('Y-m-d 23:59:59',strtotime($dateTo));
			if(empty($dateFrom)){
				$this->db->where("{$main_table}.created <= '{$datetimeTo}'");
			} else if(empty($dateTo)){
				$this->db->where("{$main_table}.created >= '{$datetimeFrom}'");
			} else {
				$this->db->where("{$main_table}.created >= '{$datetimeFrom}'");
				$this->db->where("{$main_table}.created <= '{$datetimeTo}'");
			}
		}
		// End - Search condition
		
		// Begin - Filter condition
		// $filter1 = (int)$this->input->post('filter1');
		// $filter2 = (int)$this->input->post('filter2');
		// $filter3 = (int)$this->input->post('filter3');
		// if(!empty($filter1)){
			// $this->db->where("{$main_table}.user_type_id = '{$filter1}'");
		// }
		// End - Filter condition
		
		/*End: Condition*/
		
		
		if($is_count_total_rows){
			$result = $this->db->count_all_results();
		} else {
			$query = $this->db->get();
			$result = $query->result();
		}
		
		// FOR DEBUG
		$debug = false;
		if($debug){
			echo $this->db->last_query();
			exit();
		}
		
		return $result;
	}
	
	function getDetailManagement($id){
		$this->db->select('*');
		$this->db->where('id',$id);
		$query = $this->db->get($this->table);

		return $query->result();
	}
	
	function saveManagement($perm=''){
		$this->load->library('upload');

		

		
		$arr_perm_group = explode(',',substr($this->input->post('perm_group'),6));
		// foreach($arr_perm_group as $v){
		// 	$custom_perm = 0;
		// 	if(strpos($perm,$v)===false){
		// 		$custom_perm = 1;
		// 		break;
		// 	}
		// }
		

		if($this->input->post('hiddenIdAdmincp')==0){
			//Kiểm tra đã tồn tại chưa?
			$checkData = $this->checkData($this->input->post('slug'));
			if($checkData){
				print 'error-username-exists';
				exit;
			}
			
		
			// if( ! empty($_POST['content'])) {
			// 	$pre_url = $_POST['content'];
			// 	$_thumbnail_url = move_file_from_url('img', $pre_url, TRUE);
			// }

			$data = array(
				'slug'=> $this->input->post('slug'),
				'content'=> $this->input->post('content'),
				'created'=> date('Y-m-d H:i:s',time()),
			);
			if($this->db->insert($this->table,$data)){
				modules::run('admincp/saveLog',$this->module,$this->db->insert_id(),'Add new','Add new');
				return true;
			}

		}else{
			// $result = $this->getDetailManagement($this->input->post('hiddenIdAdmincp'));
			// //Kiểm tra đã tồn tại chưa?
			// if($result[0]->username!=$this->input->post('usernameAdmincp')){
			// 	$checkData = $this->checkData($this->input->post('usernameAdmincp'));
			// 	if($checkData){
			// 		print 'error-username-exists';
			// 		exit;
			// 	}
			// }
			
			// if($this->input->post('passAdmincp')==''){
			// 	$pass = $result[0]->password;
			// }else{
			// 	$pass = md5($this->input->post('passAdmincp'));
			// }
			$data = array(
				'slug'=> $this->input->post('slug'),
				'content'=> $this->input->post('content'),
			);
		//	modules::run('admincp/saveLog',$this->module,$this->input->post('hiddenIdAdmincp'),'','Update',$result,$data);
			$this->db->where('id',$this->input->post('hiddenIdAdmincp'));
			if($this->db->update($this->table,$data)){
				return true;
			}
		}
		return false;
	}
	
	function checkData($username){
		$this->db->select('id');
		$this->db->where('id',$username);
		$this->db->limit(1);
		$query = $this->db->get($this->table);

		return $query->result();
	}
	
	function getData($username){
		$result = false;
		
		$this->db->select('id,permission');
		$this->db->where('username',$username);
		$query = $this->db->get($this->table);
		
		$result = $query->row();
		
		return $result;
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