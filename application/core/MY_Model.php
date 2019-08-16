<?php

class MY_Model extends CI_Model
{
	function __construct(){
		parent::__construct();
		// Load the Database Module REQUIRED for this to work.
		$this->load->database();//Without it -> Message: Undefined property: XXXController::$db
	}

	function get($select = "*", $table = "", $where = "", $return_array = false)
	{
		$this->db->select($select);
		if($where != "")
		{
			$this->db->where($where);
		}
		#Query
		$query = $this->db->get($table);
		if($return_array){
			$result = $query->row_array();
		} else {
			$result = $query->row();
		}
		$query->free_result();
		return $result;
	}
	function get_arr($select = "*", $table = "", $where = "", $return_array = false)
	{
		return $this->get($select, $table, $where, true);
	}

	function fetch($select = "*", $table = "", $where = "", $order = "", $by = "DESC", $start = -1, $limit = 0, $return_array = false)
	{
		
		$this->db->select($select);
		if($where != "")
		{
			$this->db->where($where);
		}
		if($order != "" && (strtolower($by) == "desc" || strtolower($by) == "asc"))
		{
			if($order == 'rand'){
				$this->db->order_by('rand()');
			}else{
				$this->db->order_by($order, $by);
			}
		}
		
		if((int)$start >= 0 && (int)$limit > 0)
		{
			$this->db->limit($limit, $start);
		}
		#Query
		$query = $this->db->get($table);
		if($return_array){
			$result = $query->result_array();
		} else {
			$result = $query->result();
		}
		$query->free_result();
		return $result;
	}
	function fetch_arr($select = "*", $table = "", $where = "", $order = "", $by = "DESC", $start = -1, $limit = 0, $return_array = false){
		return $this->fetch($select, $table, $where, $order, $by, $start, $limit, true);
	}

	function fetch_join($select = "*", $table = "", $where = "", $join_1 = "", $table_1 = "", $on_1 = "", $join_2 = "", $table_2 = "", $on_2 = "", $order = "", $by = "DESC", $start = -1, $limit = 0, $distinct = false,$return_array = false)
	{
        $this->db->select($select);
		if(($join_1 == "INNER" || $join_1 == "LEFT" || $join_1 == "RIGHT") && $table_1 != "" && $on_1 != "")
		{
			$this->db->join($table_1, $on_1, $join_1);
		}
		if(($join_2 == "INNER" || $join_2 == "LEFT" || $join_2 == "RIGHT") && $table_2 != "" && $on_2 != "")
		{
			$this->db->join($table_2, $on_2, $join_2);
		}
		if($where != "")
		{
			$this->db->where($where);
		}
		if($order != "" && (strtolower($by) == "desc" || strtolower($by) == "asc"))
		{
            $this->db->order_by($order, $by);
		}
		if((int)$start >= 0 && (int)$limit > 0)
		{
			$this->db->limit($limit, $start);
		}
		if($distinct == true)
		{
			$this->db->distinct();
		}
		#Query
		$query = $this->db->get($table);
		if($return_array){
			$result = $query->result_array();
		} else {
            $result = $query->result();
		}
		$query->free_result();
		return $result;
	}
	function fetch_join_arr($select = "*", $table = "", $where = "", $join_1 = "", $table_1 = "", $on_1 = "", $join_2 = "", $table_2 = "", $on_2 = "", $order = "", $by = "DESC", $start = -1, $limit = 0, $distinct = false){
		return $this->fetch_join($select, $table, $where, $join_1, $table_1, $on_1, $join_2, $table_2, $on_2, $order, $by, $start, $limit, $distinct, true);
	}

	function insert($table = "", $data)
	{
		return $this->db->insert($table, $data);
	}

	function update($table = "", $data, $where = "")
	{
    	if($where != "")
    	{
            $this->db->where($where);
    	}
		return $this->db->update($table, $data);
	}

	function delete($table = "", $where = "")
    {
		if($where != "")
    	{
            $this->db->where($where);
    	}
		return $this->db->delete($table);
    }
    
    
	/*BEGIN: ADMIN*/
	function getRecords($table_name, $select='*', $from, $where_conditions=null, $char_sort = 'id', $t_sort = 'DESC', $start=-1, $limit=-1){
		$result = array();
		if(!empty($from)){
			if($start==-1){ //Count
				$query = "SELECT 1 ";
			} else {
				$query = "SELECT $select ";
			}
			$query .= "FROM $from ";
			if($where_conditions!=null){
				$query .= "WHERE $where_conditions ";
			}
			$query .= "ORDER BY $table_name.$char_sort $t_sort ";
			if($start!=-1){
				$query .= "LIMIT $start, $limit ";
			}
			
			//echo $query; exit();
			$query = $this->db->query($query);
			//$result = $query->result_array();
			if($start==-1){ //Count
				$result = $query->num_rows();
			} else {
				$result = $query->result();
			}
		}
		return $result;
	}

	public function delete_record($table_name, $record_ids)
	{
		if($table_name == 'cli_newcars'){
			$this->load->model('new_car_model');			
			foreach($record_ids as $id){
				$this->new_car_model->delete_car($id);
			}
		}else{
			return $this->db->query("DELETE FROM $table_name WHERE id IN ($record_ids)");
		}
	}
	
	public function set_enable_record($table_name, $record_ids, $is_active)
	{
		return $this->db->query("UPDATE $table_name SET is_active = $is_active WHERE id IN ($record_ids)");
	}
	
	public function findCount($params){
		$records = $this->base_model->fetch('id',$params['table'],$params['conditions']);		
		return count($records);
	}
	
	/*The Anh*/
	function updateCount($params){
		$currentCount = $this->get('count',$params['table'],$params['conditions']);
		$currentCount->count += 1;
		$this->insert($params['table'],$currentCount->count);
	}
	
	function updateSalonCar($params){
		/*$currentCount = $this->get($params['field'],$params['table'],$params['condition']);
		
		$data['count_car'] = $currentCount->count_car + 1;
		$this->update($params['table'],$data,$params['condition']);*/
	}
	/*END: ADMIN*/ 
}