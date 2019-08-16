<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Admin extends MX_Controller {
    
    private $module = 'admin';
    private $current_page_data;
    private $current_category_data;
    private $current_detail_data;
    private $parent_url;
    private $segments;
    private $pre_uri;
    private $table = 'banker';

    public function __construct(){
        parent::__construct();
        //load model
        $this->load->model('admin_model','model');
        $this->load->model('banker_model','model');
        $this->load->model('student/student_model');
		$this->load->helper('main_helper');
        $this->load->helper('url');
        $this->load->library('excel');
        $this->segments = $this->uri->segment_array();
        $this->load->library('session');
        $this->load->library("pagination");    
        $user_type = $this->session->userdata('user_id');

        //$this->session->set_userdata('user_type', 13);
        //$this->session->set_userdata('user_id', 13);

        if(!$this->session->userdata('user_id') && $user_type != 2){
            header('Location: '.PATH_URL.'home/sign_in');
            exit;
        }else{
            $user_id = $this->session->userdata('user_id');
            $user_info = $this->student_model->getUserInfo($user_id);
            $this->session->set_userdata('user_first_name', $user_info['0']->firstname);
            $this->template->set_template('tiqadmin');
        }
    }

    public function index(){
	    header('Location: '.PATH_URL.'admin/loan_list');
        exit;
    }

    public function user_avatar(){
        $user_id = $this->session->userdata('user_id');
        $user_info = $this->student_model->getUserInfo($user_id);
        
        if(!empty($user_info['0']->image)){
            print '<img src="'.PATH_URL.'statics/uploads/avatar/'.$user_info['0']->image.'" class="user-img">';
        }else{
            print '<img src="'.PATH_URL.'statics/uploads/avatar/user-icon.png" class="user-img">';
        }
    }

    public function loan_accessibility(){
        $user_id = $this->session->userdata('user_id');
        
        $config = array();
        $config["base_url"] = PATH_URL."/admin/loan_accessibility/";
        $config["total_rows"] = $this->model->get_count_loan();
        $config["per_page"] = 20;
        $config["uri_segment"] = 3;
        $config["next_link"] = 'Next';
        $config["prev_link"] = 'Prev';

        $this->pagination->initialize($config);

        $page = ($this->uri->segment(3)) ? $this->uri->segment(3) : 0;
        
        $data["links"] = $this->pagination->create_links();
        $data['data'] = $this->model->getlistLoanforAdmin($config["per_page"], $page);
        
        $this->template->write('title','Loan Accessibility');
        $this->template->write_view('content','FRONTEND/loan_accessibility',$data);
        $this->template->render(); 
    }  

    public function loan_list(){
       
        $data[] =  null;
        /*print("<pre>".print_r( $data['data'],true)."</pre>");
        die;*/

        $this->template->write('title','Loan Accessibility');
        $this->template->write_view('content','FRONTEND/loan_list',$data);
        $this->template->render(); 
    }

    public function ajax_loan_list(){
        $this->load->library('AdminPagination');
        $config['total_rows'] = $this->model->get_new_count_loan();
        $config['per_page'] = (int)$this->input->post('per_page');
        $config['start'] = (int)$this->input->post('start');
        $config['num_links'] = 3;
        $config['func_ajax'] = 'searchLoan';
        $this->adminpagination->initialize($config);

        $result = $this->model->getsearchLoan($config['per_page'],$this->input->post('start'));
        $data = array(
            'result'=>$result,
            'per_page'=>$this->input->post('per_page'),
            'start'=>$this->input->post('start'),
            'total'=>$config['total_rows']
        );
        $this->session->set_userdata('start',$this->input->post('start'));
        
        $this->load->view('FRONTEND/ajax_loan_list', $data);
    }

    public function loan_accessibility_list($id){

        $data = $this->student_model->getlistLoanSubmited($id);
        $user_info = $this->student_model->getUserInfo($id);

        $data = array(
            'data' => $data,
            'user_info' => $user_info['0']
        );
        $this->template->write('title','Loan Accessibility');
        $this->template->write_view('content','FRONTEND/loan_accessibility_list',$data);
        $this->template->render(); 
    }       

    public function loan_accessibility_detail($id){
        $data = $this->student_model->getLoanInfo($id);
        $remark = $this->student_model->getLoanRemark($data['0']->id);
        $user_info = $this->student_model->getUserInfo($data['0']->student_id);

        $data = array(
            'data' => $data['0'],
            'remark' => $remark,
            'user_info' => $user_info['0']
        );
        $this->template->write('title','Loan Accessibility');
        $this->template->write_view('content','FRONTEND/loan_accessibility_detail',$data);
        $this->template->render(); 
    }

    public function import_student_list()
    {
        $data = null;

        $this->template->write('title','Import Student List');
        $this->template->write_view('content','FRONTEND/import_student_list',$data);
        $this->template->render(); 
    }

    function fetch()
    {
        $data = $this->model->get_student_list();
        $output = '
        <h3 align="center">Total Data - '.$data->num_rows().'</h3></br>
        <table class="table table-striped table-bordered">
            <tr>
                <th scope="col">ID</th>
                <th scope="col">First Name</th>
                <th scope="col">Last Name</th>
                <th scope="col">Mobile No.</th>
                <th scope="col">Email</th>
            </tr>
        ';
        foreach($data->result() as $row)
        {
            $output .= '
            <tr>
                <td>'.$row->id.'</td>
                <td>'.$row->first_name.'</td>
                <td>'.$row->last_name.'</td>
                <td>'.$row->mobile_no.'</td>
                <td>'.$row->email.'</td>
            </tr>
            ';
        }
        $output .= '</table>';
        echo $output;
    }

    function import()
    {
        if(isset($_FILES["file"]["name"]))
        {
            $path = $_FILES["file"]["tmp_name"];
            $object = PHPExcel_IOFactory::load($path);
            foreach($object->getWorksheetIterator() as $worksheet)
            {
                $highestRow = $worksheet->getHighestRow();
                
                $highestColumn = $worksheet->getHighestColumn();
                for($row=2; $row<=$highestRow; $row++)
                {
                    $first_name = $worksheet->getCellByColumnAndRow(0, $row)->getValue();
                    $last_name = $worksheet->getCellByColumnAndRow(1, $row)->getValue();
                    $mobile_no = $worksheet->getCellByColumnAndRow(2, $row)->getValue();
                    $email = $worksheet->getCellByColumnAndRow(3, $row)->getValue();
                    $data[] = array(
                        'created'       =>  date('Y-m-d H:i:s'),
                        'first_name'      =>  $first_name,
                        'last_name'           =>  $last_name,
                        'mobile_no'              =>  $mobile_no,
                        'email'        =>  $email
                    );
                }
            }
            /*print("<pre>".print_r($data,true)."</pre>");
            die;*/
            $this->model->insert_student_list($data);
            echo 'Data Imported successfully';
        }   
    }
    
}