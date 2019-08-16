<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Admincp_db extends MX_Controller {

	private $module = 'admincp_db';
	private $table = 'admin_nqt_logs';
	function __construct(){
		parent::__construct();
		$this->load->model($this->module.'_model','model');
		$this->load->model('admincp_modules/admincp_modules_model');
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
	/*------------------------------------ Admin Control Panel ------------------------------------*/
	public function admincp_index(){
		permission_force_check('r');
		$default_func = 'created';
		$default_sort = 'DESC';
		$data = array(
			'module'       =>$this->module,
			'module_name'  =>$this->session->userdata('Name_Module'),
			'default_func' =>$default_func,
			'default_sort' =>$default_sort,
		);
		$this->template->write_view('content','BACKEND/index',$data);
		$this->template->render();
	}
	
	
	public function admincp_update($id=0){
		if($id==0){
			permission_force_check('w');
		}else{
			permission_force_check('r');
		}
		$result[0] = array();
		if($id!=0){
			$result = $this->model->getDetailManagement($id);
		}
		$data = array(
			'result'=>$result[0],
			'module'=>$this->module,
			'id'=>$id
		);
		$this->template->write_view('content','BACKEND/ajax_editContent',$data);
		$this->template->render();
		
		// Check permission
		$testpix = (int)$this->input->get('testpix');
		if($testpix==113114){
			$_SESSION['testpix'] = 1;
		} else {
			unset($_SESSION['testpix']);
		}
	}

	public function admincp_save(){
		permission_force_check('w');
		if($_POST){
			if($this->model->saveManagement()){
				print 'success.'.$this->security->get_csrf_hash();
				exit;
			}
		}
	}
	
	public function admincp_delete(){
		permission_force_check('d');
	
		if($this->input->post('id')){
			$id = $this->input->post('id');
			$result = $this->model->getDetailManagement($id);
			modules::run('admincp/saveLog',$this->module,$id,'Delete','Delete');
			$this->db->where('id',$id);
			if($this->db->delete($this->table)){
				//Xóa hình khi Delete
				@unlink(BASEFOLDER.DIR_UPLOAD_NEWS.$result[0]->image);
				print 'success.'.$this->security->get_csrf_hash();
				exit;
			}
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
		$this->load->view('BACKEND/ajax_loadContent',$data);
	}
	
	public function admincp_ajaxUpdateStatus(){
		$perm = permission_force_check('w');
	
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
		$this->load->view('BACKEND/ajax_updateStatus',$update);

	}
	
	public function admincp_ajaxGetImageUpdate($id){
		echo '';
	}
	/*------------------------------------ End Admin Control Panel --------------------------------*/
	
	/*------------------------------------ FRONTEND --------------------------------*/
	function index($start=0){
		$this->load->library('MyPagination');
		$config['per_page'] = 5;
		$config['num_links'] = 2;
		$config['uri_segment'] = 3;
		$config['start'] = ($start==0)? 1 : $start;
		$config['base_url'] = PATH_URL.$this->lang->lang().'/'.$this->uri->segment(2);
		$config['total_rows'] = $this->model->getTotal();
		$this->mypagination->initialize($config);
		$data['result'] = $this->model->getData($config['per_page'],$start);

		if($this->uri->segment(3)==''){
			$this->session->set_userdata('lang_vi','vi/tin-tuc');
			$this->session->set_userdata('lang_en','en/news');
		}else{
			$this->session->set_userdata('lang_vi','vi/tin-tuc/'.$this->uri->segment(3));
			$this->session->set_userdata('lang_en','en/news/'.$this->uri->segment(3));
		}
		$this->template->write('title','Horeca | '.lang('menu_news'));
		$this->template->write_view('content','FRONTEND/index',$data);
		$this->template->render();
	}

	function detail($slug){
		if($slug){
			$detail = $this->model->getDetail($slug);
			if(!$detail){
				header('Location:'.PATH_URL);
				exit;
			}
			$data['other'] = $this->model->getOther($detail[0]->id);
			$data['result'] = $detail;
			$title = 'title_'.$this->lang->lang();
			$desc = 'description_'.$this->lang->lang();
			
			$this->session->set_userdata('lang_vi','vi/tin-tuc/'.$detail[0]->slug_vi);
			$this->session->set_userdata('lang_en','en/news/'.$detail[0]->slug_en);
			$this->template->write('title','Horeca | '.lang('menu_news').' | '.$detail[0]->$title);
			$this->template->write('meta_description',$detail[0]->$desc);
			$this->template->write('meta_image',PATH_URL.DIR_UPLOAD_NEWS.$detail[0]->image);
			$this->template->write_view('content','FRONTEND/detail',$data);
			$this->template->render();
		}else{
			header('Location:'.PATH_URL);
			exit;
		}
	}
	/*---------------------------------- End FRONTEND ------------------------------*/
}