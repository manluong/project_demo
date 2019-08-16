<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class banker extends MX_Controller {
    
    private $module = 'banker';
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
        $this->load->model('banker_model','model');
        $this->load->model('student/student_model');
		$this->load->helper('main_helper');
        $this->load->helper('url');
        $this->load->helper('common_helper');
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
            $this->template->set_template('banker');
        }
    }

    public function index(){
	    header('Location: '.PATH_URL.'banker/notification');
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

    public function personal_info(){
        $user_id = $this->session->userdata('user_id');
        $user_info = $this->student_model->getUserInfo($user_id);
        
        $data = array(
            'user_info' => $user_info
        );

        $this->template->write('title','Personal Info');
        $this->template->write_view('content','FRONTEND/personal_info',$data);
        $this->template->render(); 
    }
    
    public function personal_info_edit(){
        $user_id = $this->session->userdata('user_id');
        $user_info = $this->student_model->getUserInfo($user_id);
        
        $data = array(
            'user_info' => $user_info['0']
        );
        $this->template->write('title','Personal Info Edit');
        $this->template->write_view('content','FRONTEND/personal_info_edit',$data);
        $this->template->render(); 
    }

    public function personal_info_change_password(){
        $data = null;
        $this->template->write('title','Change Password');
        $this->template->write_view('content','FRONTEND/personal_info_change_password',$data);
        $this->template->render(); 
    }

    public function loan_accessibility(){
        $user_id = $this->session->userdata('user_id');

        $config = array();
        $config["base_url"] = PATH_URL."/banker/loan_accessibility/";
        $config["total_rows"] = $this->model->get_count_loan($user_id);
        $config["per_page"] = 20;
        $config["uri_segment"] = 3;
        $config["next_link"] = 'Next';
        $config["prev_link"] = 'Prev';

        $this->pagination->initialize($config);

        $page = ($this->uri->segment(3)) ? $this->uri->segment(3) : 0;
        
        $data["links"] = $this->pagination->create_links();
        $data['data'] =  $this->model->getlistLoanforBanker($user_id, $config["per_page"], $page);
        
        /*print("<pre>".print_r($data,true)."</pre>");
        die;*/

        $this->template->write('title','Loan Accessibility');
        $this->template->write_view('content','FRONTEND/loan_accessibility',$data);
        $this->template->render(); 
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
        $this->update_status_studenloan($id);

        $data = $this->student_model->getLoanInfo($id);
        $remark = $this->student_model->getLoanRemark($data['0']->id);
        $user_info = $this->student_model->getUserInfo($data['0']->student_id);

        $user_id = $this->session->userdata('user_id');

        $data = array(
                'data' => $data['0'],
                'remark' => $remark,
                'user_info' => $user_info['0']
            );
            $this->template->write('title','Loan Accessibility');
            $this->template->write_view('content','FRONTEND/loan_accessibility_detail',$data);
            $this->template->render();
            
        /*if($user_id == $data['0']->banker_id){//Student only can access theirs loan
            $data = array(
                'data' => $data['0'],
                'remark' => $remark,
                'user_info' => $user_info['0']
            );
            $this->template->write('title','Loan Accessibility');
            $this->template->write_view('content','FRONTEND/loan_accessibility_detail',$data);
            $this->template->render();
        }else{
            header('Location: '.PATH_URL.'home/sign_in');
            exit;
        } */
    }


    public function notification(){
        $user_id = $this->session->userdata('user_id');
        $data = $this->model->getNofforBanker($user_id);

        $data = array(
            'data' => $data
        );
        $this->template->write('title','Notification');
        $this->template->write_view('content','FRONTEND/notification',$data);
        $this->template->render(); 
    }

    public function notification_header(){
        $user_id = $this->session->userdata('user_id');
        $data = $this->model->getNofforBankerHeader($user_id);

        $data = array(
            'data' => $data
        );
        $this->load->view('FRONTEND/notification_header',$data);
    }

    public function delete_not(){
        $this->db->where('id',$this->input->post('id'));
        if($this->db->delete('student_nof')){
            print 'success';
        }else{
            print 'fail';
        }
    }

    public function update_not(){
        
        $data['status'] = 2;
        $this->db->where('id',$this->input->post('id'));
        if($this->db->update('student_nof', $data)){
            print 'success';
        }else{
            print 'fail';
        }
    }

    public function support(){
        $data = null;
        $this->template->write('title','Support');
        $this->template->write_view('content','FRONTEND/support',$data);
        $this->template->render(); 
    }

    public function update_profile(){
        $id = $this->session->userdata('user_id');
        $firstname = $this->input->post('firstname');

        $data = array(
            'email'         =>  $this->input->post('email'),
            'firstname'     =>  $firstname,
            'lastname'      =>  $this->input->post('lastname'),
            'postal_code'   =>  $this->input->post('postal_code'),
            'bank'          =>  $this->input->post('bank')
        );
        
        $this->db->where('id',$id);
        if($this->db->update('student',$data)){
            $this->session->set_userdata('user_first_name', $firstname);
            print 'success';
        }else{
            print 'fail';
        }
        
    }

    public function banker_verify(){
        $student_loan_id = $this->input->post('student_loan_id');
        $data = array(
            'status'          =>  $this->input->post('status'),
            'agree'          =>  $this->input->post('agree'),
            'verified_amount' =>  $this->input->post('verified_amount'),
            'verify_date'     =>  date('Y-m-d H:i:s')
        );
        
        $this->db->where('id',$student_loan_id);

        if($this->db->update('student_loan_accessibility',$data)){
            if($this->save_banker_nof_verify($student_loan_id, 'verify')){
                print 'success';
            }
        }else{
            print 'fail';
        }
    }

    public function save_banker_nof_verify($student_loan_id, $type){

        $info = $this->student_model->getLoanInfo($student_loan_id);

        $data = array(
            'type'          =>  $type,
            'loan_id'       =>  $student_loan_id,
            'student_id'    =>  $info['0']->student_id,
            'banker_id'     =>  $info['0']->banker_id,
            'created'       =>  date('Y-m-d H:i:s'),
            'status'        =>  1
        );
        
        if($this->db->insert('banker_nof',$data)){
            print 'success';
        }else{
            print 'fail';
        }
    }

    public function update_status_studenloan($id){
        $status = $this->model->get_status_student_loan($id);

        if($status == 2){
            $data = array(
                'status'  =>  3
            );
            
            $this->db->where('id',$id);
            $this->db->update('student_loan_accessibility',$data);
        }
       
    }

    public function banker_comment()
    {
        $data = array(
            'banker_comment'      =>  $this->input->post('banker_comment'),
            'hdb_installmemt'     =>  date('Y-m-d', strtotime($this->input->post('hdb_install')))
        );

        $this->db->where('id', $this->input->post('student_loan_id'));
        if($this->db->update('student_loan_accessibility',$data)){
            print 'success';
        }else{
            print 'fail';
        }
    }

    public function banker_remark()
    {
        $student_loan_id = $this->input->post('student_loan_id');
        $data = array(
            'content'         =>  $this->input->post('remark'),
            'student_loan_id'     =>  $student_loan_id,
            'banker_id'     =>  $this->input->post('banker_id'),
            'status'    =>  1,
            'created'   =>  date('Y-m-d H:i:s')
        );

        if($this->db->insert('student_loan_note',$data)){
            if($this->save_banker_nof_verify($student_loan_id, 'remark')){
                print 'success';
            }
        }else{
            print 'fail';
        }
    }

}