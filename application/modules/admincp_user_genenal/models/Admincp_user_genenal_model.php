<?php
class Admincp_user_genenal_model extends CI_Model {
	private $module = 'admincp_user_genenal';
	private $table = 'admin_nqt_user_info';

	function get_user_type_list(){
		$this->db->select('user_type.*');
		$this->db->from('user_type');
		$this->db->order_by('type','asc');
		$query = $this->db->get();
		return $query->result();
	}
	function get_user_type(){
		$this->db->select('user_type.*');
		$this->db->from('user_type');
		$this->db->order_by('type','asc');
		$query = $this->db->get();
		return $query->result();
	}
	
	function get_name_type($id){
		$this->db->select('type');
		$this->db->from('user_type');
		$this->db->limit(1);
		$query = $this->db->get();
		return $query->result();
	}
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
		$this->db->order_by("created", "asc");
		
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
				"{$main_table}.email LIKE '%{$content}%'",
				"{$main_table}.first_name LIKE '%{$content}%'",
				"{$main_table}.last_name LIKE '%{$content}%'",
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
		$filter1 = (int)$this->input->post('filter1');
		// $filter2 = (int)$this->input->post('filter2');
		// $filter3 = (int)$this->input->post('filter3');
		if(!empty($filter1)){
			$this->db->where("{$main_table}.user_type_id = '{$filter1}'");
		}
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
		$this->db->select('admin_nqt_user_info.*');
		$this->db->where('admin_nqt_user_info.id',$id);
		$this->db->from('admin_nqt_user_info');
		
		$query = $this->db->get();

		return $query->result();
		
	}
	
	function saveManagement($fileName=''){
			
		//$_current_lang_code = '';
		$_status = ($this->input->post('status')=='on') ? 1 : 0;
		$_created = $_updated = date('Y-m-d H:i:s',time());
		//$_created_by = $_updated_by = ''; //TO DO
		//$data_lang = $data_lang_temp = array();
		$_image_url = $_thumbnail_url = '';
		$_image_url_1 = $_thumbnail_url_1 = '';
		$_email = $this->input->post('email');
        $_username = $this->input->post('username');
        $_location = $this->input->post('location');
        $_country = $this->input->post('country');
        $_city = $this->input->post('city');
		$_biiography = $this->input->post('biiography');
		$_status_genenal= $this->input->post('status_genenal');
		$_distributor= $this->input->post('distributor');
		$_contact = $this->input->post('contact');
		$_marketing = $this->input->post('marketing');
		$_bandcamp = $this->input->post('bandcamp');
		$_mixcloud = $this->input->post('mixcloud');
		$_soundcloud = $this->input->post('soundcloud');
		$_beatport =$this->input->post('beatport');	
		$_itunes =$this->input->post('itunes');
		//$_image = $this->input->post('image');
        $_user_type_id = $this->input->post('type');
        // pr($user_type_id,1);
		
		if($this->input->post('hiddenIdAdmincp')==0){
		
		}else{
			$result = $this->getDetailManagement($this->input->post('hiddenIdAdmincp'));
			//Kiểm tra đã tồn tại chưa?
			if($result[0]->email!=$this->input->post('email')){
				$checkData = $this->checkData($this->input->post('email'));
				if($checkData){
					print 'error-username-exists';
					exit;
				}
			}
			//Xử lý xóa hình khi update thay đổi hình
			// if($fileName['image']==''){
			// 	$fileName['image'] = $result[0]->image;
				
			// }else{
			// 	@unlink(BASEFOLDER.DIR_UPLOAD_NEWS.$result[0]->image);
			// }
		
			//End xử lý xóa hình khi update thay đổi hình
			
			// $data = array(
			// 	'' => $_thumbnail_url,
			// 	'image' => $_image_url,
			// 	'status'=> $_status,
			// 	'updated'=> $_updated
			// );
			$thumbnail_urlAdmincp_1 = $this->input->post('thumbnail_urlAdmincp_1');
			if( ! empty($thumbnail_urlAdmincp_1) ) {
				$pre_url_1 = $thumbnail_urlAdmincp_1;
				$_thumbnail_url_1 = move_file_from_url('image_1', $pre_url_1, TRUE);
			}

			$image_urlAdmincp_1 = $this->input->post('image_urlAdmincp_1');
			if( ! empty($image_urlAdmincp_1) ) {
				$pre_url_1 = $image_urlAdmincp_1;
				$_image_url_1 = move_file_from_url('image_1', $pre_url_1, FALSE);
			}

			if ( empty($_thumbnail_url_1) || empty($_image_url_1) ) {
				print 'error-image.'.$this->security->get_csrf_hash();
				exit;
			}
			
			$thumbnail_urlAdmincp = $this->input->post('thumbnail_urlAdmincp');
			if( ! empty($thumbnail_urlAdmincp) ) {
				$pre_url = $thumbnail_urlAdmincp;
				$_thumbnail_url = move_file_from_url('image', $pre_url, TRUE);
			}

			$image_urlAdmincp = $this->input->post('image_urlAdmincp');
			if( ! empty($image_urlAdmincp) ) {
				$pre_url = $image_urlAdmincp;
				$_image_url = move_file_from_url('image', $pre_url, FALSE);
			}

			if ( empty($_thumbnail_url) || empty($_image_url) ) {
				print 'error-image.'.$this->security->get_csrf_hash();
				exit;
			}
			$data = array(
				'email' => $_email,
				'username' =>$_username,
				'user_type_id' => '2',
				'biiography' => $_biiography,
				'status_genenal' => $_status_genenal,
				'marketing' => $_marketing,
				'contact' => $_contact,
				'distributor' => $_distributor,
				'bandcamp' => $_bandcamp,
				'beatport' => $_beatport,
				'soundcloud' => $_soundcloud,
				'mixcloud' => $_mixcloud,
				'image' => $_image_url,
				'image_1' => $_image_url_1,
				'thumbnail' => $_thumbnail_url,
				'thumbnail_1' => $_thumbnail_url_1,
				'created' => $_created
				);

			//pr($data,1	);
			
			$update_id = $this->input->post('hiddenIdAdmincp');
			pr($result,1);
			$old_value[] = $result;
			//$old_value = $old_value[];
			//pr($old_value,1);
			//unset($data['created'], $data['updated']);
			modules::run('admincp/saveLog',$this->module,$this->input->post('hiddenIdAdmincp'),'','Update', $old_value, $data);
			//DO UPDATE DATA
			$this->db->where('id', $update_id);
			if($this->db->update(PREFIX.$this->table,$data)){
				//Update data in table language
				foreach ($data_lang as $key => $item) {
					$lang_code = $item['lang_code'];
					unset($item['lang_code']);
					$this->db->where('lang_code', $lang_code);
					$this->db->where($this->field_parent_id, $update_id);
					$this->db->update(PREFIX.$this->table, $item);
				}
				//end update data language
				return true;
			}
		}
		return false;
	}
	
	function checkData($email,$id=0){
		$this->db->select('id');
		$this->db->where('email'.$email);
		if($id!=0){
			$this->db->where_not_in('id',array($id));
		}
		$this->db->limit(1);
		$query = $this->db->get($this->table);

		return $query->result();
	}
	
	function checkId($id){
		$this->db->select('id');
		$this->db->where('id',$id);
		$this->db->limit(1);
		$query = $this->db->get($this->table);
		return $query->result();
	}
	
	
	function getData($limit,$start){
		$this->db->select('*');
		$this->db->where('status',1);
		$this->db->limit($limit,$start);
		$query = $this->db->get($this->table);

		return $query->result();
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