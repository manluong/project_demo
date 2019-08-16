<?php
class Admincp_job_model extends CI_Model {
	private $module = 'admincp_job';
	private $table = 'admin_nqt_job';

	/*function get_user_type_list(){
		$this->db->select('user_type.*');
		$this->db->from('user_type');
		$this->db->order_by('type','asc');
		$query = $this->db->get();
		return $query->result();
	}*/
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
		//$this->db->join('user_type',"{$main_table}.user_type_id = user_type.id", 'left'); // Force LEFT JOIN to keep data of main table
		
		if(!$is_count_total_rows){
			// Limit
			$this->db->limit($limit,$page);
			
			// Order
			$order_by = $this->input->post('func_order_by');
			$order_by = "{$main_table}.{$order_by}";
			$order_direction = $this->input->post('order_by');
			$this->db->order_by($order_by,$order_direction);
		}
		
		/*Begin: Condition*/
		// Begin - Search condition
		$content = $this->input->post('content');
		if(!empty($content)){
			$search_condition_arr = array(
				"{$main_table}.name LIKE '%{$content}%'",
				//"{$main_table}.username LIKE '%{$content}%'",
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
	
	
	
	function saveManagement($fileName=''){
		$job_id = $this->input->post('job_id');
		$name = $this->input->post('name');
		$slug = SEO($name).'-'.$job_id;
		$link = $this->input->post('link');
		$content = $this->input->post('content');
		$location = $this->input->post('location');
		$wage = $this->input->post('wage');
		
		$data = array(
			'status'		=>	isset($_POST['status']) ? 1 : 0,
			'created'		=>	date('Y-m-d H:i:s'),
			'name'	=> $name,
			'job_id'	=> $job_id,
			'slug' => $slug,
			'link'	=> $link,
			'content'	=> $content,
			'location'	=> $location,
			'wage'	=> $wage,
			);
			
			if($this->input->post('hiddenIdAdmincp')==0){
			
				if($this->db->insert($this->table,$data)){
					modules::run('admincp/saveLog',$this->module,$this->db->insert_id(),'Add new','Add new');
					return true;
				}
			}
			else{
				$result = $this->getDetailManagement($this->input->post('hiddenIdAdmincp'));
				
				$update_id = $this->input->post('hiddenIdAdmincp');
				$this->db->where('id', $update_id);
				if($this->db->update($this->table,$data)){
					modules::run('admincp/saveLog',$this->module,$this->db->insert_id(),'Edit','Edit');
					return true;
				}
			}
		return false;
	}
	function getDetailManagement($id){
		$this->db->select('*');
		$this->db->where('id',$id);
		$this->db->from($this->table);
		//$this->db->join('user_type','user_type.id = admin_nqt_user_info.user_type_id');
		
		$query = $this->db->get();
		
		return $query->result();
		
	}
	
	function checkData($name,$id=0){
		$this->db->select('id');
		$this->db->where('name',$name);
		if($id!=0){
			$this->db->where_not_in('id',array($id));
		}
		$this->db->limit(1);
		$query = $this->db->get($this->table);

		if($query->result()){
			return true;
		}else{
			return false;
		}
	}
	
	function getData($limit,$start){
		$this->db->select('*');
		$this->db->where('status',1);
		$this->db->limit($limit,$start);
		$query = $this->db->get($this->table);

		if($query->result()){
			return $query->result();
		}else{
			return false;
		}
	}
	
	function getTotal(){
		$this->db->select('id');
		$this->db->where('status',1);
		$query = $this->db->count_all_results($this->table);

		if($query > 0){
			return $query;
		}else{
			return false;
		}
	}
	
	function getDetail($slug){
		$this->db->select('*');
		$this->db->where('status',1);
		//$this->db->where('slug_'.$this->lang->lang(),$slug);
		$this->db->limit(1);
		$query = $this->db->get($this->table);

		if($query->result()){
			return $query->result();
		}else{
			return false;
		}
	}
	
	function getOther($id){
		$this->db->select('*');
		$this->db->where('status',1);
		$this->db->where_not_in('id',array($id));
		$this->db->limit(5);
		$this->db->order_by('id','random');
		$query = $this->db->get($this->table);

		if($query->result()){
			return $query->result();
		}else{
			return false;
		}
	}
	function get_type($user_type_id ='' ){
		$this->db->select('admin_nqt_user_info.*, user_type.type');
		$this->db->from('admin_nqt_user_info');
		$this->db->join('user_type','admin_nqt_user_info.user_type_id = user_type.id');
		$this->db->where('user_type_id',$user_type_id);	
		$this->db->order_by($this->input->post('func_order_by'),$this->input->post('order_by'));
		
		
		$query = $this->db->get();

		if($query->result()){
			return $query->result();
		}else{
			return false;
		}
	}
}