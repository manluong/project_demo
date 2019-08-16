<?php
class Admincp_category_model extends CI_Model {
	private $module = 'admincp_category';
	private $table = 'admin_nqt_category';
	private $table_lang = 'admin_nqt_category_lang';
	private $field_parent_id = 'category_id';

	function getsearchname($limit,$page){
		$this->db->select('*');
		$this->db->limit($limit,$page);
		$this->db->order_by($this->input->post('func_order_by'),$this->input->post('order_by'));
		if($this->input->post('content')!=''){
			$this->db->where('(`name_vi` LIKE "%'.$this->input->post('content').'%" OR `name_en` LIKE "%'.$this->input->post('content').'%")');
		}
		if($this->input->post('dateFrom')!='' && $this->input->post('dateTo')==''){
			$this->db->where('created >= "'.date('Y-m-d 00:00:00',strtotime($this->input->post('dateFrom'))).'"');
		}
		if($this->input->post('dateFrom')=='' && $this->input->post('dateTo')!=''){
			$this->db->where('created <= "'.date('Y-m-d 23:59:59',strtotime($this->input->post('dateTo'))).'"');
		}
		if($this->input->post('dateFrom')!='' && $this->input->post('dateTo')!=''){
			$this->db->where('created >= "'.date('Y-m-d 00:00:00',strtotime($this->input->post('dateFrom'))).'"');
			$this->db->where('created <= "'.date('Y-m-d 23:59:59',strtotime($this->input->post('dateTo'))).'"');
		}
		
		#check soft delete
		$this->db->where('is_delete', 0);
		
		$query = $this->db->get(PREFIX.$this->table);

		return $query->result();
	}
	
	function getTotalsearchname(){
		$this->db->select('*');
		if($this->input->post('content')!='' && $this->input->post('content')!='type here...'){
			$this->db->where('(`name_vi` LIKE "%'.$this->input->post('content').'%" OR `name_en` LIKE "%'.$this->input->post('content').'%")');
		}
		if($this->input->post('dateFrom')!='' && $this->input->post('dateTo')==''){
			$this->db->where('created >= "'.date('Y-m-d 00:00:00',strtotime($this->input->post('dateFrom'))).'"');
		}
		if($this->input->post('dateFrom')=='' && $this->input->post('dateTo')!=''){
			$this->db->where('created <= "'.date('Y-m-d 23:59:59',strtotime($this->input->post('dateTo'))).'"');
		}
		if($this->input->post('dateFrom')!='' && $this->input->post('dateTo')!=''){
			$this->db->where('created >= "'.date('Y-m-d 00:00:00',strtotime($this->input->post('dateFrom'))).'"');
			$this->db->where('created <= "'.date('Y-m-d 23:59:59',strtotime($this->input->post('dateTo'))).'"');
		}
		
		#check soft delete
		$this->db->where('is_delete', 0);
		
		$query = $this->db->count_all_results(PREFIX.$this->table);

		if($query > 0){
			return $query;
		}else{
			return false;
		}
	}
	
	function getDetailManagement($id){
		// $this->db->select(PREFIX.$this->table.'.*, id AS data_lang');
		$this->db->select('*');
		
		#check soft delete
		$this->db->where('is_delete', 0);
		
		$this->db->where('id',$id);
		$query = $this->db->get(PREFIX.$this->table);

		if($query->result()){
			$result = $query->row();
			
			$this->db->select('*');
			$this->db->where($this->field_parent_id, $id);
			$query = $this->db->get(PREFIX.$this->table_lang);
			$temp = NULL;
			if($query->result()) {
				foreach ($query->result() as $key => $item) {
					$temp[$item->lang_code] = $item;
				}
			}
			//make data_lang property in result object. IMPORTANT!!!!
			$result->data_lang = $temp;
			return $result;
		}else{
			return false;
		}
	}
	
	function saveManagement($fileName=''){
		if(isset($this->lang->languages)){
			$all_lang = $this->lang->languages;
		}else{
			$all_lang = array(
				'' => ''
			);
		}

		//default data
		$_current_lang_code = '';
		$_status = ($this->input->post('statusAdmincp')=='on') ? 1 : 0;
		$_created = $_updated = date('Y-m-d H:i:s',time());
		$_created_by = $_updated_by = ''; //TO DO
		$data_lang = $data_lang_temp = array();
		
		if($this->input->post('hiddenIdAdmincp')==0){
			//Kiểm tra đã tồn tại chưa?
			foreach($all_lang as $key=>$val){
				if($key!=''){
					$_current_lang_code = $key;
					$keyerror = '-'.$key;
					$key = '_'.$key;
				}else{
					$key = '';
					$keyerror = '';
				}

				$checkData = $this->checkData($this->input->post('name'.$key.'Admincp'),$key);
				if($checkData){
					print 'error-name'.$keyerror.'-exists.'.$this->security->get_csrf_hash();
					exit;
				}
				
				$checkSlug = $this->checkSlug($this->input->post('slug'.$key.'Admincp'),$key);
				if($checkSlug){
					print 'error-slug'.$keyerror.'-exists.'.$this->security->get_csrf_hash();
					exit;
				}
			}

			$data = array(
				'status'=> $_status,
				'created'=> $_created,
				
			);
			foreach($all_lang as $key=>$val){
				$_current_lang_code = $key;
				$key = ($key != '') ? '_' . $key : $key;
				$subfix = $key . 'Admincp';

				$data['name'.$key] = trim(htmlspecialchars($this->input->post('name' . $subfix)));
				$data['slug'.$key] = trim($this->input->post('slug' . $subfix));
				//make data language
				$data_lang_temp['name'] = trim(htmlspecialchars($this->input->post('name' . $subfix)));
				$data_lang_temp['slug'] = trim($this->input->post('slug' . $subfix));
				$data_lang_temp['created'] = $_created;
				$data_lang_temp['status'] = $_status;
				$data_lang_temp['lang_code'] = $_current_lang_code;
				$data_lang[] = $data_lang_temp;
				unset($data_lang_temp);
			}
			//DO INSERT DATA
			if($this->db->insert(PREFIX.$this->table,$data)){
				$insert_id = $this->db->insert_id();
				//Insert data language
				foreach ($data_lang as $key => $item) {
					$item[$this->field_parent_id] = $insert_id;
					$this->db->insert(PREFIX.$this->table_lang, $item);
				}
				//End insert data language
				modules::run('admincp/saveLog',$this->module,$insert_id,'Add new','Add new');
				return true;
			}
		}else{
			$result = $this->getDetailManagement($this->input->post('hiddenIdAdmincp'));
			//Kiểm tra đã tồn tại chưa?
			foreach($all_lang as $key=>$val){
				if($key!=''){
					$_current_lang_code = $key;
					$keyerror = '-'.$key;
					$key = '_'.$key;
				}else{
					$key = '';
					$keyerror = '';
				}
				$name = 'name'.$key;
				$slug = 'slug'.$key;
				if($result->$name!=$this->input->post('name'.$key.'Admincp')){
					$checkData = $this->checkData($this->input->post('name'.$key.'Admincp'),$key,$this->input->post('hiddenIdAdmincp'));
					if($checkData){
						print 'error-name'.$keyerror.'-exists.'.$this->security->get_csrf_hash();
						exit;
					}
				}
				
				if($result->$slug!=$this->input->post('slug'.$key.'Admincp')){
					$checkSlug = $this->checkSlug($this->input->post('slug'.$key.'Admincp'),$key,$this->input->post('hiddenIdAdmincp'));
					if($checkSlug){
						print 'error-slug'.$keyerror.'-exists.'.$this->security->get_csrf_hash();
						exit;
					}
				}
			}
		}
		return false;
	}

	function softDeleteData ($id) {
		$data ['is_delete'] = 1;
		$this->db->where('id', $id);
		if ($this->db->update (PREFIX.$this->table, $data)) {
			modules::run('admincp/saveLog',$this->module,$id,'Delete','Delete');
			//Soft-delete data in table language
			$this->db->where ($this->field_parent_id, $id);
			$this->db->update (PREFIX.$this->table_lang, $data);
			//end soft-delete data language
			return TRUE;
		}
		return FALSE;
	}
	
	function checkData($name,$lang,$id=0){
		$this->db->select('id');
		$this->db->where('name'.$lang,$name);
		if($id!=0){
			$this->db->where_not_in('id',array($id));
		}
		$this->db->limit(1);
		$query = $this->db->get(PREFIX.$this->table);

		return $query->result();
	}
	
	function checkSlug($slug,$lang,$id=0){
		$this->db->select('id');
		$this->db->where('slug'.$lang,$slug);
		if($id!=0){
			$this->db->where_not_in('id',array($id));
		}
		$this->db->limit(1);
		$query = $this->db->get(PREFIX.$this->table);

		return $query->result();
	}
	
	function getData($limit,$start){
		$this->db->select('*');
		$this->db->where('status',1);
		$this->db->limit($limit,$start);
		$query = $this->db->get(PREFIX.$this->table);
		
		return $query->result();
	}
	
	function getTotal(){
		$this->db->select('id');
		$this->db->where('status',1);
		$query = $this->db->count_all_results(PREFIX.$this->table);

		if($query > 0){
			return $query;
		}else{
			return false;
		}
	}
	
	function getDetail($slug){
		$this->db->select('*');
		$this->db->where('status',1);
		$this->db->where('slug_'.$this->lang->lang(),$slug);
		$this->db->limit(1);
		$query = $this->db->get(PREFIX.$this->table);

		return $query->result();
	}
	
	function getOther($id){
		$this->db->select('*');
		$this->db->where('status',1);
		$this->db->where_not_in('id',array($id));
		$this->db->limit(5);
		$this->db->order_by('id','random');
		$query = $this->db->get(PREFIX.$this->table);

		return $query->result();
	}

	function getAllData() {
		$this->db->select('id, name_en, name_vi');
		$this->db->where('status',1);
		#check soft delete
		$this->db->where('is_delete', 0);
		$query = $this->db->get(PREFIX.$this->table);

		return $query->result();
	}
}