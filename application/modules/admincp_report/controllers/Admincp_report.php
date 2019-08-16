<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Admincp_report extends MX_Controller {
	
	private $module = 'admincp_report';
	private $table = 'admin_nqt_user_info';
	function __construct(){
		parent::__construct();
		$this->load->model($this->module.'_model','model');
		$this->load->model('admincp_modules/admincp_modules_model');
		$this->load->helper('main_helper');
		if($this->uri->segment(1)==ADMINCP){
			if($this->uri->segment(2)!='login'){
				if(!$this->session->userdata('userInfo')){
					header('Location: '.PATH_URL_ADMIN.'login');
					exit;
				}
				$get_module = $this->admincp_modules_model->check_modules($this->uri->segment(2));
				$this->session->set_userdata('ID_Module',$get_module[0]->id);
				$this->session->set_userdata('Name_Module',$get_module[0]->name);
			}
			$this->template->set_template('admin');
			$this->template->write('title','Admin Control Panel');
		}
	}
	
	function admincp_index(){
		permission_force_check('r');
		$data['module'] = 'admincp_report';
		$this->submit_CV($data);
	}
	
	function submit_CV($data){
		
		$date = $this->model->getFirstDate();
		if(!empty($date)){
			$first_date = date('Y-m-d 00:00:00', strtotime($date[0]->created));
		} else {
			$first_date = date('Y-m-d 00:00:00');
		}
		
		$data['first_date'] = $first_date;
		$data['current_date'] = date('Y-m-d 23:59:59');
		
		$from = $this->input->get('from');
		$to = $this->input->get('to');
		
		if(!empty($from)&&!empty($to)){
			$data['first_date'] = $from;
			$data['current_date'] = date("Y-m-d 23:59:59", strtotime($to));
		}
		
		$loan_submited = $this->model->getLoanSubmited($data);//Loan Submited
		$loan_verified= $this->model->getLoanVerified($data);//Loan Verified
		$student = $this->model->getStudent($data);//student
		
		$arrayMapping = array();
		if(!empty($loan_submited)){
			foreach($loan_submited as $v){
				$created = $v->submit_date;
				$key = date('d-m-Y',strtotime($created));
				if(!isset($arrayMapping[$key])){
					$arrayMapping[$key] = 0;
				}
				$arrayMapping[$key]++;
			}
		}
		$data['arrayMapping'] = $arrayMapping;
		
		$arrayLoanverified = array();
		if(!empty($loan_verified)){
			foreach($loan_verified as $v){
				$created = $v->verify_date;
				$key = date('d-m-Y',strtotime($created));
				if(!isset($arrayLoanverified[$key])){
					$arrayLoanverified[$key] = 0;
				}
				$arrayLoanverified[$key]++;
			}
		}
		$data['arrayLoanverified'] = $arrayLoanverified;

		$arrayMapView = array();
		if(!empty($student)){
			foreach($student as $val){
				$time = $val->created;
				$key = date('d-m-Y',strtotime($time));
				if(!isset($arrayMapView[$key])){
					$arrayMapView[$key] = 0;
				}
				$arrayMapView[$key]++;
			}
		}
		$data['arrayMapView'] = $arrayMapView;
		
		/*$arrayMapConversion = array();
		
		if(!empty($conversion)){
			// pr($conversion,1);
			foreach($conversion as $va){
				$time = $va->time;
				$key = date('d-m-Y',strtotime($time));
				if(!isset($arrayMapConversion[$key])){
					$arrayMapConversion[$key] = array(
						'view' => 0,
						'register' => 0
					);
				}
				$arrayMapConversion[$key]['view']++;
			}
			
			//lấy list application
			foreach($cv_list as $cv){
				$time_cv = $cv->created;
				$key_cv = date('d-m-Y',strtotime($time_cv));
				if(isset($arrayMapConversion[$key_cv])){
					$arrayMapConversion[$key_cv]['register']++;
				}
				
			}			
		}
		//pr($arrayMapConversion,1);
		$data['arrayMapConversion'] = $arrayMapConversion;*/
		
		$this->template->write_view('content','index',$data);
		$this->template->render();
	}
	
	function menu(){
		$this->load->model('admincp_modules/admincp_modules_model');
		$this->load->model('admincp_accounts/admincp_accounts_model');
		$data['perm'] = $this->admincp_accounts_model->getData($this->session->userdata('userInfo'));
		$data['menu'] = $this->admincp_modules_model->list_module();
		// $data['main_menu'] = $this->admincp_modules_model->list_main_menu(); // TODO - Multi menu
		$this->load->view('menu',$data);
	}
	
	function permission(){
		$data['module'] = 'admincp_report';
		$this->template->write_view('content','permission',$data);
		$this->template->render();
	}
	function listview_buttons(){
		return $this->load->view('listview_buttons',$data, TRUE);
	}
	function search(){
		return $this->load->view('search',$data, TRUE);
	}
	function chk_perm($id_module,$type,$isAjax){
		if(empty($id_module)){
			die('permission-denied');
		}
		
		$this->load->model('admincp_accounts/admincp_accounts_model');
		$this->load->model('admincp/admincp_report_model');
		$info = $this->admincp_model->getInfo($this->session->userdata('userInfo'));
		
		$permission = $type;
		$user_permission = $info[0]->id == 1 ? 'all' : $info[0]->permission; // TODO
		$module_id = $id_module;
		
		$check_result = permission_check($permission, $module_id, $user_permission);
		if(!$check_result){
			if($isAjax==0){
				header('Location: '.PATH_URL_ADMIN.'permission');
				exit();
			}else{
				return 'permission-denied';
				exit;
			}
		}
	}
	
	function saveLog($func,$func_id,$field,$type,$old_value='',$new_value=''){
		if($field!=''){
			$data = array(
				'function' => $func,
				'function_id' => $func_id,
				'field' => $field,
				'type' => $type,
				'old_value' => $old_value,
				'new_value' => $new_value,
				'account' => $this->session->userdata('userInfo'),
				'ip' => getIP(),
				'created' => date('Y-m-d H:i:s')
			);
			$this->db->insert('admin_nqt_logs',$data);
		}else{
			foreach($new_value as $k=>$v){
				if($v!=$old_value[0]->$k){	
					$data = array(
						'function' => $func,
						'function_id' => $func_id,
						'field' => $k,
						'type' => $type,
						'old_value' => $old_value[0]->$k,
						'new_value' => $v,
						'account' => $this->session->userdata('userInfo'),
						'ip' => getIP(),
						'created' => date('Y-m-d H:i:s')
					);
					$this->db->insert('admin_nqt_logs',$data);
				}
			}
		}
	}
	
	function update_profile(){
		if(!empty($_POST)){
			if(md5($this->input->post('oldpassAdmincp'))==$this->model->checkLogin($this->session->userdata('userInfo'))){
				$data = array(
					'username'=> $this->session->userdata('userInfo'),
					'password'=> md5($this->input->post('newpassAdmincp')),
				);
				$this->db->where('username', $this->session->userdata('userInfo'));
				if($this->db->update('admin_nqt_users',$data)){
					$this->load->model('admincp_accounts/admincp_accounts_model');
					$userInfo = $this->admincp_accounts_model->getData($this->session->userdata('userInfo'));
					$this->saveLog('update_profile',$userInfo[0]->id,'password','Update',$this->input->post('oldpassAdmincp'),$this->input->post('newpassAdmincp'));
					print 'success_update_profile.'.$this->security->get_csrf_hash();
					exit;
				}
			}else{
				print 'error_update_profile.'.$this->security->get_csrf_hash();
				exit;
			}
		}else{
			$this->template->write_view('content','update_profile');
			$this->template->render();
		}
	}
	
	function setting(){
		if(!empty($_POST)){
			foreach($this->input->post('hd_slugAdmincp') as $k=>$v){
				$content = $this->input->post('contentAdmincp');
				$chk_slug = $this->model->checkSlug($v);
				if($chk_slug){
					$data = array(
						'content'=>$content[$k],
						'modified'=>date('Y-m-d H:i:s')
					);
					$this->db->where('id',$chk_slug[0]->id);
					$this->db->update('admin_nqt_settings',$data);
				}else{
					$data = array(
						'slug'=>$v,
						'content'=>$content[$k],
						'modified'=>date('Y-m-d H:i:s')
					);
					$this->db->insert('admin_nqt_settings',$data);
				}
			}
			print 'success-setting.'.$this->security->get_csrf_hash();
			exit;
		}else{
			$data['setting'] = $this->model->getSetting();
			$this->template->write_view('content','setting',$data);
			$this->template->render();
		}
	}
	
	function getSetting($slug=''){
		$this->load->model('admincp_report_model');
		$data['setting'] = $this->admincp_model->getSetting($slug);
		$this->load->view('getSetting',$data);
	}	
}