<?php
class Admincp_image_banner_footer_model extends CI_Model {
	private $module = 'admincp_image_banner_footer';
	private $table = 'admin_nqt_banner';

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
		// $this->db->where('type_banner',2);//footer
		
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
		// End - Search condition
		
		// Begin - Filter condition
		// $filter1 = (int)$this->input->post('filter1');
		// // $filter2 = (int)$this->input->post('filter2');
		// // $filter3 = (int)$this->input->post('filter3');
		// if(!empty($filter1)){
		// 	$this->db->where("{$main_table}.user_type_id = '{$filter1}'");
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
	
	
	
	function saveManagement($fileName=''){
		$_image_url = $_thumbnail_url = '';
			
		$pre_url = $this->input->post('image_urlAdmincp');
		$_thumbnail_url = move_file_from_url('admincp_thumb', $pre_url, TRUE);
		
		
		$data = array(
			'status'		=>	isset($_POST['status']) ? 1 : 0,
			'created'		=>	date('Y-m-d H:i:s'),
			'image' 	=> $_thumbnail_url,
			'other'		=> $this->input->post('other'),
			);
			
			if($this->input->post('hiddenIdAdmincp')==0){
			
				if($this->db->insert($this->table,$data)){
					modules::run('admincp/saveLog',$this->module,$this->db->insert_id(),'Add new','Add new');
					return true;
				}
			}
			else{
				$result = $this->getDetailManagement($this->input->post('hiddenIdAdmincp'));
				if($this->input->post('image_urlAdmincp')== ''){
				 $data['image'] = $result[0]->image;
				}else{
					@unlink(BASEFOLDER.DIR_UPLOAD_NEWS.$result[0]->image);
				}
				

				if( ! empty($_thumbnail_url) ) {
					$data['image'] = $_thumbnail_url;
				}
				
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
	
	// function checkSlug($slug,$lang,$id=0){
		// $this->db->select('id');
		// $this->db->where('slug'.$lang,$slug);
		// if($id!=0){
			// $this->db->where_not_in('id',array($id));
		// }
		// $this->db->limit(1);
		// $query = $this->db->get($this->table);

		// if($query->result()){
			// return true;
		// }else{
			// return false;
		// }
	// }
	
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
		//$this->db->limit($limit,$page);
		$this->db->order_by($this->input->post('func_order_by'),$this->input->post('order_by'));
		
		
		$query = $this->db->get();

		if($query->result()){
			return $query->result();
		}else{
			return false;
		}
	}
}