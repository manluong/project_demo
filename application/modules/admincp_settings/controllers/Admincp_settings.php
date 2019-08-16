<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Admincp_settings extends MX_Controller {

	private $module = 'admincp_settings';
	private $table = 'admin_nqt_settings';
	function __construct(){
		parent::__construct();
		$this->load->model($this->module.'_model','model');
		$this->load->model('admincp_modules/admincp_modules_model');
		if($this->uri->segment(1)==ADMINCP){
			if($this->uri->segment(2)!='login'){
				if(!$this->session->userdata('userInfo')){
					print '<script type="text/javascript">top.location="'.PATH_URL_ADMIN.'login"</script>';
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
	/*------------------------------------ Admin Control Panel ------------------------------------*/
	public function admincp_index(){
		permission_force_check('r');
		$default_func = 'created';
		$default_sort = 'ASC';
		$data = array(
			'module'=>$this->module,
			'module_name'=>$this->session->userdata('Name_Module'),
			'default_func'=>$default_func,
			'default_sort'=>$default_sort
		);
		$this->template->write_view('content','index',$data);
		$this->template->render();
	}
	
	
	public function admincp_update($id=0){
		// if($id==1){
			// if($this->session->userdata('userInfo')!='root'){
				// header('Location:'.PATH_URL_ADMIN.'permission');
			// }
		// }
		if($id==0){
			permission_force_check('w');
		}else{
			permission_force_check('r');
		}
		$result[0] = array();
		if($id!=0){
			$result = $this->model->getDetailManagement($id);
		}
		$this->load->model('admincp_account_groups/admincp_account_groups_model');
		$list_group = $this->admincp_account_groups_model->list_groups();
		$data = array(
			'result'=>$result[0],
			'module'=>$this->module,
			'list_group'=>$list_group,
			'id'=>$id
		);
		$this->template->write_view('content','ajax_editContent',$data);
		$this->template->render();
	}

	public function admincp_save(){
		permission_force_check('w');

		if($this->input->post('statusAdmincp')=='on'){
			$status = 1;
		}else{
			$status = 0;
		}
		if($_POST){
			//Upload Image
			$fileName = array('image'=>'');
			if($_FILES){
				pr($fileName,1);
				foreach($fileName as $k=>$v){
					if(isset($_FILES['content']['error'][$k]) && $_FILES['content']['error'][$k]!=4){
						$typeFileImage = strtolower(mb_substr($_FILES['content']['type'][$k],0,5));
						if($typeFileImage == 'image'){
							$tmp_name[$k] = $_FILES['content']["tmp_name"][$k];
							$file_name[$k] = $_FILES['content']['name'][$k];
							$ext = strtolower(mb_substr($file_name[$k], -4, 4));
							if($ext=='jpeg'){
								$fileName[$k] = date('Y').'/'.date('m').'/'.md5(time().'_'.SEO(mb_substr($file_name[$k],0,-5))).'.jpg';
							}else{
								$fileName[$k] = date('Y').'/'.date('m').'/'.md5(time().'_'.SEO(mb_substr($file_name[$k],0,-4))).$ext;
							}
						}else{
							print 'error-image.'.$this->security->get_csrf_hash();
							exit;
						}
					}
				}
			}
			//End Upload Image

			if($this->model->saveManagement($fileName)){
				//Upload Image
				if($_FILES){
					if($_FILES){
						$upload_path = BASEFOLDER.DIR_UPLOAD_NEWS;
						check_dir_upload($upload_path);
						foreach($fileName as $k=>$v){
							if(isset($_FILES['content']['error'][$k]) && $_FILES['content']['error'][$k]!=4){
								move_uploaded_file($tmp_name[$k], $upload_path.$fileName[$k]);
							}
						}
					}
				}
				//End Upload Image
				print 'success.'.$this->security->get_csrf_hash();
				exit;
			}
		}

		return; 
	
		if($_POST){
			//Permission list module
			$this->load->model('admincp_modules/admincp_modules_model');
			$list_modules = $this->admincp_modules_model->list_module();
			$perm = '';
			foreach($list_modules as $k=>$v){
				if($k==0){
					$perm .= $v->id.'|';
				}else{
					$perm .= ','.$v->id.'|';
				}
				
				if($this->input->post('groupAdmincp')==1){
					if($this->input->post('read'.$v->id.'Admincp')=='on'){
						$perm .= 'r';
					}else{
						$perm .= '-';
					}
					if($this->input->post('write'.$v->id.'Admincp')=='on'){
						$perm .= 'w';
					}else{
						$perm .= '-';
					}
					if($this->input->post('delete'.$v->id.'Admincp')=='on'){
						$perm .= 'd';
					}else{
						$perm .= '-';
					}
					if($this->input->post('approve'.$v->id.'Admincp')=='on'){
						$perm .= 'a';
					}else{
						$perm .= '-';
					}
				}else{
					if($v->id!=1 && $v->id!=2 && $v->id!=3 && $v->id!=4 && $v->id!=5){
						if($this->input->post('read'.$v->id.'Admincp')=='on'){
							$perm .= 'r';
						}else{
							$perm .= '-';
						}
						if($this->input->post('write'.$v->id.'Admincp')=='on'){
							$perm .= 'w';
						}else{
							$perm .= '-';
						}
						if($this->input->post('delete'.$v->id.'Admincp')=='on'){
							$perm .= 'd';
						}else{
							$perm .= '-';
						}
						if($this->input->post('approve'.$v->id.'Admincp')=='on'){
							$perm .= 'a';
						}else{
							$perm .= '-';
						}
					}else{
						$perm .= '---';
					}
				}
			}

			if($this->model->saveManagement($perm)){
				print 'success.'.$this->security->get_csrf_hash();
				exit;
			}
		}
	}
	
	public function admincp_delete(){
		//permission_force_check('d');

	
		if($this->input->post('id')){
			$id = $this->input->post('id');
			$result = $this->model->getDetailManagement($id);
			if($result[0]->username==$this->session->userdata('userInfo')){
				print 'permission-denied.'.$this->security->get_csrf_hash();
				exit;
			}
			modules::run('admincp/saveLog',$this->module,$id,'Delete','Delete');
			$this->db->where('id',$id);
			if($this->db->delete($this->table)){
				print 'success.'.$this->security->get_csrf_hash();
				exit;
			}
		}
	}
	
	public function admincp_reset_permission(){
		$data = array(
			'custom_permission'=>0,
			'permission'=>substr($this->input->post('permDefault'),6)
		);
		$this->db->where('id',$this->input->post('id'));
		if($this->db->update('admin_nqt_users',$data)){
			print 'success.'.$this->security->get_csrf_hash();
			exit;
		}else{
			print 'error.'.$this->security->get_csrf_hash();
			exit;
		}
	}
	
	public function admincp_ajaxLoadContent(){
		$this->load->library('AdminPagination');
		$config['total_rows'] = $this->model->getsearchContent();
		$config['per_page'] = (int)$this->input->post('per_page');
		$config['start'] = (int)$this->input->post('start');
		$config['num_links'] = 3;
		$config['func_ajax'] = 'searchContent';
		$this->adminpagination->initialize($config);

		$result = $this->model->getsearchContent($config['per_page'],$this->input->post('start'));
		$data = array(
			'result'=>$result,
			'per_page'=>$this->input->post('per_page'),
			'start'=>$this->input->post('start'),
			'module'=>$this->module,
			'total'=>$config['total_rows']
		);
		$this->session->set_userdata('start',$this->input->post('start'));
		$this->load->view('ajax_loadContent',$data);
	}
	
	public function admincp_ajaxPerm($group_id){
		$this->load->model('admincp_modules/admincp_modules_model');
		$this->load->model('admincp_account_groups/admincp_account_groups_model');
		$list_modules = $this->admincp_modules_model->list_module();
		$default_perm[0] = '';
		if($this->input->post('perm')){
			$result[0] = json_decode('{"permission":"'.$this->input->post('perm').'"}');
			$default_perm = $this->admincp_account_groups_model->getDetailManagement($group_id);
		}else{
			$result = $this->admincp_account_groups_model->getDetailManagement($group_id);
		}
		$data = array(
			'list_modules'=>$list_modules,
			'result'=>$result[0],
			'default_perm'=>$default_perm[0],
			'group_id'=>$group_id
		);
		$this->load->view('ajax_permission',$data);
	}
	public function admincp_ajaxUpdateStatus(){
		$perm = permission_force_check('a');
		if($perm=='permission-denied'){
			print '<script type="text/javascript">show_perm_denied()</script>';
			$status = $this->input->post('status');
			$data = array(
				'status'=>$status
			);
			$update = array(
				'status'=>$status,
				'id'=>$this->input->post('id'),
				'module'=>$this->module
			);
			$this->load->view('ajax_updateStatus',$update);
		}else{
			if($this->input->post('status')==0){
				$status = 1;
			}else{
				$status = 0;
			}
			$data = array(
				'status'=>$status
			);
			modules::run('admincp/saveLog',$this->module,$this->input->post('id'),'status','update',$this->input->post('status'),$status);
			$this->db->where('id', $this->input->post('id'));
			$this->db->update($this->table, $data);
			
			$update = array(
				'status'=>$status,
				'id'=>$this->input->post('id'),
				'module'=>$this->module
			);
			$this->load->view('ajax_updateStatus',$update);
		}
	}
	/*------------------------------------ End Admin Control Panel --------------------------------*/
}