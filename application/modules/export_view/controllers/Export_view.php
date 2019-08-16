<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Export_view extends MX_Controller {
	protected $module = 'export_view';
	protected $is_testing = false;
	
	function __construct(){
		parent::__construct();
		$this->load->helper('url_helper');
		$this->load->helper('main_helper');
		
		$this->load->model('Export_view_model','model');
		$this->template->set_template('admin');
		$this->template->write('title','Download');
		
		$data['module'] = $this->module;
		$this->load->vars($data);
	}

    function export_download_file($action='export_view',$file_id=''){
        if(!empty($file_id)){
            header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
            header("Content-Disposition: attachment; filename={$file_id}.xlsx");
            $export_file = '';
                $export_file = BASEFOLDER.'statics/uploads/export_view/'.$file_id.'.xlsx';
            
            echo file_get_contents($export_file);
        }
    }
	
	function admincp_index() {
		
		// TODO - TEST - DEBUG
		$data['is_testing'] = (int)$this->input->get('test');

		$this->template->write_view('content','BACKEND/index',$data);
		$this->template->render();
	}
   

	function ajax_export(){
		$this->is_testing = (int)$this->input->post('is_testing');
		ini_set('display_errors', 1);
		error_reporting(E_ALL);
		
		set_time_limit(0);
		ini_set('memory_limit','2048M');
		$json_data = array(
			'result' => '',
			'file_name' => ''
		);
        $this->get_export_data_excel();
        $json_data = $this->write_export_excel_data();//cpm
		echo json_encode($json_data);
	}
    
	private function get_export_data_excel(){
       
        $this->load->model('Admincp_users/Admincp_users_model');
        $data_list='';
		
            
        //1. danh sách plan đến cửa hàng (upload_time)
       
        $query_view='guest_id, time';
        $query = $this->db->select("$query_view")
            ->from('user_count');
        $data_list = $query->get()->result_array();
        //pr($data_list,1);
        $this->data_list = $data_list;
        
       
	}
    
	private function write_export_excel_data(){

        $json_data = array();
        $flag = false;

        if(!empty($this->data_list)){
            $file_name = 'VNW_Export_view';
            $template_file = BASEFOLDER.'statics/uploads/export/'.$file_name.'.xlsx';
            //pr($template_file,1);
            
            if(!file_exists($template_file))
                return;
            
            //Clone Template
            $now = date('Y_m_d_H_i_s');

            $export_file_name = "VNW_thong_tin_view".str_replace("export_view", "", $file_name)."_{$now}.xlsx";
            $export_file = BASEFOLDER.'statics/uploads/export_view/'.$export_file_name;
           
            if(!file_exists($export_file)){

                @unlink($export_file);
            }
            
            if(copy($template_file, $export_file)){

                $objReader = phpexcel_get_obj_reader(false);
                $objPHPExcel = $objReader->load($export_file);
                $this->objSheet = $objPHPExcel->getActiveSheet();
                
                $this->start_row = 2; // View above, start row based on template type
                $this->row = $this->start_row;
              
                $stt = 0;
                foreach($this->data_list as  $data) {
                    
                    $stt++;
                    $this->row_data = array();
                    $this->column = 0;
                    $this->row_data[] = $stt; $this->column++; // STT,                   
                    $this->row_data[] = $data['guest_id']; $this->column++;
                    $this->row_data[] = date('d/m/Y H:i:s',$data['time']); $this->column++;
                    // $this->row_data[] = $data['email']; $this->column++; // 
                    // $this->row_data[] = $data['phone']; $this->column++; 
                    // $this->row_data[] = $data['industry']; $this->column++; 
                    // $this->row_data[] = $data['address']; $this->column++; 
                    // $this->row_data[] = $data['created']; $this->column++;    
                    // $this->row_data[] = $data['utm_source']; $this->column++;
                    // $this->row_data[] = $data['utm_medium']; $this->column++; // 
                    // $this->row_data[] = $data['utm_campaign']; $this->column++; 
                    // $this->row_data[] = $data['utm_term']; $this->column++; 
                    // $this->row_data[] = $data['utm_content']; $this->column++;   
                    //pr($this->row_data,1);
                    $this->objSheet->fromArray($this->row_data, NULL, excel_get_cell_address(0,$this->row));
                    $this->row++;                  
                }
              
                $objWriter = new PHPExcel_Writer_Excel2007($objPHPExcel);
                $objWriter->save($export_file);
                
                $json_data['file_name'] = str_replace(".xlsx",'', $export_file_name);
                $flag = true;
            }
        }
        
        //kiểm tra trạng thái
        if ($flag == true) {
            $json_data['status'] = 'success';
            $json_data['message'] = 'Xuất dữ liệu thành công';
        }elseif ($flag == false) {
            $json_data['status'] = 'fail';
            $json_data['message'] = 'Không có dữ liệu';
           
        }
       
        return $json_data;
    }   
}