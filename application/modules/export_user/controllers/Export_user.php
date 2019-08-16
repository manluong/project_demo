<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Export_user extends MX_Controller {
    protected $module = 'export_user';
    protected $is_testing = false;
    
    function __construct(){
        parent::__construct();
        $this->load->helper('url_helper');
        $this->load->helper('main_helper');
        
        $this->load->model('Export_user_model','model');
        $this->load->model('banker/banker_model');
        $this->load->model('student/student_model');
        $this->template->set_template('admin');
        $this->template->write('title','Download');
        
        $data['module'] = $this->module;
        $this->load->vars($data);
    }

    function export_download_file($action='export_user',$file_id=''){
        if(!empty($file_id)){
            header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
            header("Content-Disposition: attachment; filename={$file_id}.xlsx");
            $export_file = '';
                $export_file = BASEFOLDER.'statics/uploads/export_user/'.$file_id.'.xlsx';
            
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
        
        $query = $this->db->select('sl.*, student.*, student.created as student_created, sl.id as loan_id, sl.status as loan_status')
                          ->from('student_loan_accessibility as sl')
                          ->join('student', 'student.id = sl.student_id')
                          ->where('sl.status !=', 5)
                          ->order_by('update_date', 'asc')
                          ->order_by('submit_date', 'asc');
                          
        $data_list = $query->get()->result_array();

       /* print("<pre>".print_r($data_list,true)."</pre>");
        die;*/
        $this->data_list = $data_list;
    }
    
    private function write_export_excel_data(){

        $json_data = array();
        $flag = false;

        if(!empty($this->data_list)){
            $file_name = 'TIQ_App_Report';
            $template_file = BASEFOLDER.'statics/uploads/export/'.$file_name.'.xlsx';
            
            if(!file_exists($template_file))
                return;
            
            //Clone Template
            $now = date('Y_m_d_H_i_s');
            $export_file_name = "TIQ_App_Report_{$now}.xlsx";
            
            $export_file = BASEFOLDER.'statics/uploads/export_user/'.$export_file_name;
           
            if(!file_exists($export_file)){

                @unlink($export_file);
            }
            
            if(copy($template_file, $export_file)){

                $objReader = phpexcel_get_obj_reader(false);
                $objPHPExcel = $objReader->load($export_file);
                $this->objSheet = $objPHPExcel->getActiveSheet();
                
                $this->start_row = 4; 
                $this->row = $this->start_row;
                
               
                $stt = 0;
                foreach($this->data_list as  $data) {
                    $stt++;
                    $this->row_data = array();
                    $this->column = 0;
                    $this->row_data[] = $stt; $this->column++;                  
                    $this->row_data[] = $data['student_id']; $this->column++;
                    $this->row_data[] = $data['firstname']; $this->column++; 
                    $this->row_data[] = $data['lastname']; $this->column++; 
                    $this->row_data[] = $data['student_created']; $this->column++; 
                    $this->row_data[] = $data['phone']; $this->column++; 
                    $this->row_data[] = $data['email']; $this->column++;
                    $this->row_data[] = $this->student_model->getNationalName($data['nationality']); $this->column++;
                    $this->row_data[] = ($data['gender']==1?'Male':'Female'); $this->column++;
                    $this->row_data[] = date('Y-m-d',strtotime($data['birthday'])) ; $this->column++;
                    $this->row_data[] = floor((time() - strtotime($data['birthday'])) / 31556926) ; $this->column++;
                    $this->row_data[] = $data['street']; $this->column++;
                    $this->row_data[] = $data['unit']; $this->column++;
                    $this->row_data[] = $data['building_name']; $this->column++;
                    $this->row_data[] = $data['postal_code']; $this->column++;
                    $this->row_data[] = $data['batch_no']; $this->column++;
                    $this->row_data[] = $data['loan_id']; $this->column++;
                    //TDSR - Gross Income               
                    $this->row_data[] = '$'.number_format((float)str_replace(',','',$data['monthly_fixed_income']), 2); $this->column++;
                    $this->row_data[] = '$'.number_format((float)str_replace(',','',$data['monthly_rental_income']), 2); $this->column++;
                    $this->row_data[] = '$'.number_format((float)str_replace(',','',$data['monthly_variable_income']), 2); $this->column++;
                    $this->row_data[] = '$'.number_format((float)str_replace(',','',$data['pledged_deposits']), 2); $this->column++;
                    $this->row_data[] = '$'.number_format((float)str_replace(',','',$data['unpledged_deposits']), 2); $this->column++;
                    //TDRS - Debt Obligations
                    $this->row_data[] = '$'.number_format((float)str_replace(',','',$data['credit_cards']), 2); $this->column++;
                    $this->row_data[] = '$'.number_format((float)str_replace(',','',$data['car_loans']), 2); $this->column++;
                    $this->row_data[] = '$'.number_format((float)str_replace(',','',$data['existing_home_loans']), 2); $this->column++;
                    $this->row_data[] = '$'.number_format((float)str_replace(',','',$data['other_loans']), 2); $this->column++;
                                   
                    /*$this->row_data[] = $data['credit_cards']; $this->column++;
                    $this->row_data[] = $data['car_loans']; $this->column++;
                    $this->row_data[] = $data['existing_home_loans']; $this->column++;
                    $this->row_data[] = $data['other_loans']; $this->column++;*/

                    $this->row_data[] = $data['student_note']; $this->column++;
                    $this->row_data[] = $data['gross_income']; $this->column++;
                    $this->row_data[] = $data['tdsr_limit']; $this->column++;
                    $this->row_data[] = $data['debt_obligations']; $this->column++;
                    $this->row_data[] = $data['current_tdsr']; $this->column++;
                    $this->row_data[] = $data['servicing']; $this->column++;
                    /*Affordability*/
                    $this->row_data[] = $data['monthly_installment']; $this->column++;
                    $this->row_data[] = $data['interest_rate']; $this->column++;
                    $this->row_data[] = $data['loan_duration']; $this->column++;
                    $this->row_data[] = $data['maximum_loan']; $this->column++;
                    $this->row_data[] = $data['purchase_price_75']; $this->column++;
                    $this->row_data[] = $data['purchase_price_80']; $this->column++;
                    $this->row_data[] = $data['purchase_price_90']; $this->column++;
                    /*End Affordability*/
                    $this->row_data[] = $data['verified_amount']; $this->column++;
                    //Save/Submit Date & Time
                    if(strtotime($data['submit_date'])>0){
                        $date_time = date('d-m-Y H-i',strtotime($data['submit_date']));
                    }else{
                        $date_time = date('d-m-Y H-i',strtotime($data['save_date']));
                    }
                    $this->row_data[] = $date_time ; $this->column++;

                    //Verified Date & Time
                    if(strtotime($data['verify_date'])>0){
                        $verify_date = date('d-m-Y H-i',strtotime($data['verify_date']));
                    }else{
                        $verify_date = 'NA';
                    }
                    $this->row_data[] = $verify_date ; $this->column++;
                    //TAT Day
                    switch ($data['loan_status']) {
                        case 1:
                            $this->row_data[] = 'NA';
                            break;
                        case 2:
                            $tat = floor((time() - strtotime($data['submit_date']))/86400);
                            if($tat == 0){
                                $tat = '0';
                            }
                            $this->row_data[] = $tat;
                            break;
                        case 3:
                            $tat = floor((time() - strtotime($data['submit_date']))/86400);
                            if($tat == 0){
                                $tat = '0';
                            }
                            $this->row_data[] = $tat;
                            break;
                        case 4:
                            $tat = floor((strtotime($data['verify_date']) - strtotime($data['submit_date']))/86400);
                            if($tat == 0){
                                $tat = '0';
                            }
                            $this->row_data[] = $tat;
                            break;
                    }$this->column++; //last update loan
                    //Status
                    switch ($data['loan_status']) {
                        case 1:
                            $this->row_data[] = 'SAVED';
                            break;
                        case 2:
                            $this->row_data[] = 'SUBMITTED';
                            break;
                        case 3:
                            $this->row_data[] = 'PENDING';
                            break;
                        case 4:
                            $this->row_data[] = 'VERIFIED';
                            break;
                    }$this->column++;
                    $this->row_data[] = $this->banker_model->getLatestRemark($data['loan_id']); $this->column++;
                    $this->row_data[] = $this->student_model->getStudentFullName($data['banker_id']); $this->column++;
                    $this->row_data[] = $this->banker_model->get_bankname_of_banker($data['banker_id']); $this->column++;

                    
                    

                    $this->row_data[] = (strtotime($data['hdb_installmemt'])>0?date('d-m-Y',strtotime($data['hdb_installmemt'])):'NA') ; $this->column++;
                    $this->row_data[] = $data['banker_comment']; $this->column++;
                    
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
            $json_data['message'] = 'Export success';
        }elseif ($flag == false) {
            $json_data['status'] = 'fail';
            $json_data['message'] = 'Export fail';
           
        }
       
        return $json_data;
    }

    //Export Student List 

    function export_student_list_download_file($action='export_student_list',$file_id=''){
        if(!empty($file_id)){
            header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
            header("Content-Disposition: attachment; filename={$file_id}.xlsx");
            $export_file = '';
                $export_file = BASEFOLDER.'statics/uploads/export_student_list/'.$file_id.'.xlsx';
            
            echo file_get_contents($export_file);
        }
    }

    function student_list_ajax_export(){
        
        $this->is_testing = (int)$this->input->post('is_testing');
        ini_set('display_errors', 1);
        error_reporting(E_ALL);
        
        set_time_limit(0);
        ini_set('memory_limit','2048M');
        $json_data = array(
            'result' => '',
            'file_name' => ''
        );
        $this->get_export_student_list_data_excel();
        $json_data = $this->write_export_student_list_excel_data();//cpm
        echo json_encode($json_data);
    }
    
    private function get_export_student_list_data_excel(){
        $data_list='';
        
        $query = $this->db->select('*')
                          ->from('student_list')
                          ->order_by('id', 'desc');
        $data_list = $query->get()->result_array();

       /* print("<pre>".print_r($data_list,true)."</pre>");
        die;*/
        $this->data_list = $data_list;
    }


    private function write_export_student_list_excel_data(){

        $json_data = array();
        $flag = false;

        if(!empty($this->data_list)){
            $file_name = 'Student_List_Template';
            $template_file = BASEFOLDER.'statics/uploads/export/'.$file_name.'.xlsx';
            
            if(!file_exists($template_file))
                return;
            //Clone Template
            $now = date('Y_m_d_H_i_s');
            $export_file_name = "Student_List_{$now}.xlsx";
            $export_file = BASEFOLDER.'statics/uploads/export_student_list/'.$export_file_name;
           
            if(!file_exists($export_file)){
                @unlink($export_file);
            }
            
            if(copy($template_file, $export_file)){
                $objReader = phpexcel_get_obj_reader(false);
                $objPHPExcel = $objReader->load($export_file);
                $this->objSheet = $objPHPExcel->getActiveSheet();
                $this->start_row = 2; 
                $this->row = $this->start_row;
               
                foreach($this->data_list as  $data) {
                    $this->row_data = array();
                    $this->column = 0;
                    $this->row_data[] = $data['id']; $this->column++;                 
                    $this->row_data[] = $data['first_name']; $this->column++;
                    $this->row_data[] = $data['last_name']; $this->column++; 
                    $this->row_data[] = $data['mobile_no']; $this->column++; 
                    $this->row_data[] = $data['email']; $this->column++; 
                    $this->objSheet->fromArray($this->row_data, NULL, excel_get_cell_address(0,$this->row));
                    $this->row++;                          
                }
              
                $objWriter = new PHPExcel_Writer_Excel2007($objPHPExcel);
                $objWriter->save($export_file);
                
                $json_data['file_name'] = str_replace(".xlsx",'', $export_file_name);
                $flag = true;
            }

            //kiểm tra trạng thái
            if ($flag == true) {
                $json_data['status'] = 'success';
                $json_data['message'] = 'Export success';
            }elseif ($flag == false) {
                $json_data['status'] = 'fail';
                $json_data['message'] = 'Export fail';
               
            }
            return $json_data;
        }
    }
}