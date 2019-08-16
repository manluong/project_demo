<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Student extends MX_Controller {
    
    private $module = 'student';
    private $current_page_data;
    private $current_category_data;
    private $current_detail_data;
    private $parent_url;
    private $segments;
    private $pre_uri;
    private $table = 'student';

    public function __construct(){
        parent::__construct();
        //load model
        $this->load->model('student_model','model');
        $this->load->model('banker/banker_model');
        $this->load->helper('main_helper');
        $this->load->helper('common_helper');
        $this->load->helper('url');
        $this->segments = $this->uri->segment_array();
        $this->load->library('session');  

        //$this->session->set_userdata('user_type', 3);
        //$this->session->set_userdata('user_id', 3);

        $accesstokenPost = $this->input->post('accesstoken');
        $accesstokenGet = $this->input->get('accesstoken');
        
        if ($this->model->verifyAccessToken($accesstokenPost) || $this->model->verifyAccessToken($accesstokenGet)){
            $user_id = $this->session->userdata('user_id');
            $user_info = $this->model->getUserInfo($user_id);
        } else if($this->session->userdata('user_id')){
            $user_id = $this->session->userdata('user_id');
            $user_info = $this->model->getUserInfo($user_id);
            $this->session->set_userdata('user_first_name', $user_info['0']->firstname);
            $this->template->set_template('student');
        }else{
            header('Location: '.PATH_URL.'home/sign_in');
            exit;
        }
    }

    public function index(){
    
        $data = null;
        $this->template->write('title','Home page');
        $this->template->write_view('content','FRONTEND/dashboard',$data);
        $this->template->render();    
    }
    
    /*CODE OF MANLUONG*/
    public function dashboard(){
        $user_id = $this->session->userdata('user_id');
        $user_info = $this->model->getUserInfo($user_id);
        
        $data = array(
            'user_info' => $user_info
        );
       
        $this->template->write('title','Student Dashboard');
        $this->template->write_view('content','FRONTEND/dashboard',$data);
        $this->template->render(); 
    }

    public function user_avatar(){
        $user_id = $this->session->userdata('user_id');
        $user_info = $this->model->getUserInfo($user_id);

        if(!empty($user_info['0']->image)){
            print '<img src="'.PATH_URL.'statics/uploads/avatar/'.$user_info['0']->image.'" class="user-img">';
        }else{
            print '<img src="'.PATH_URL.'statics/uploads/avatar/user-icon.png" class="user-img">';
        }
    }

    public function logout(){
        if(!empty($this->session->userdata('user_id'))){
            $this->session->unset_userdata('user_id');
            $this->session->unset_userdata('user_type');
            $this->session->unset_userdata('user_first_name');
            
            print 'success';
        }else{
            print 'fail';
        }
    }

    public function upload_avatar(){
        if(is_array($_FILES)) {
            if(is_uploaded_file($_FILES['userImage']['tmp_name'])) {
                $sourcePath = $_FILES['userImage']['tmp_name'];
                $targetPath = BASEFOLDER."statics/uploads/avatar/".$_FILES['userImage']['name'];
                $imgPath =  PATH_URL."statics/uploads/avatar/".$_FILES['userImage']['name'];
                $user_id = $this->session->userdata('user_id');

                if(move_uploaded_file($sourcePath,$targetPath)){
                    $data = array(
                        'image'  =>  $_FILES['userImage']['name']
                    );
                    $this->db->where('id',$user_id);
                    if($this->db->update('student',$data)){
                        print '<img class="image-preview" src="'.$imgPath.'" class="upload-preview"/>';
                    }
                }        
            }
        }
    }

    public function update_user_profile(){
        if($this->session->userdata('user_id')){
            $id = $this->session->userdata('user_id');
        }else{
            $id = $this->input->post('id');
        }

        $firstname = $this->input->post('firstname');
        $lastname = $this->input->post('lastname');
        $birthday = $this->input->post('birthday');

        $data = array(
            'email'         =>  $this->input->post('email'),
            'firstname'     =>  $firstname,
            'lastname'      =>  $lastname,
            'fullname'      =>  $firstname.' '.$lastname,
            'nationality'   =>  $this->input->post('nationality'),
            'gender'        =>  $this->input->post('gender'),
            'street'        =>  $this->input->post('street'),
            'unit'          =>  $this->input->post('unit'),
            'building_name' =>  $this->input->post('building_name'),
            'birthday'      =>  date('Y-m-d', strtotime($birthday)),
            'batch_no'      =>  $this->input->post('batch_no'),
            'postal_code'   =>  $this->input->post('postal_code')
        );
        
        $this->db->where('id',$id);
        if($this->db->update('student',$data)){
            $this->session->set_userdata('user_first_name', $firstname);
            print 'success';
        }else{
            print 'fail';
        }
    }

    public function change_pass(){
        $id = $this->session->userdata('user_id');

        $oldpass = trim($this->input->post('oldpass'));
        $oldpass2 = $this->model->getStudentPass($id);
       
        if(md5($oldpass) == $this->model->getStudentPass($id)){
            $data = array(
                'password'  =>  md5($this->input->post('pass'))
            );
            $this->db->where('id',$id);
            if($this->db->update('student',$data)){
                 print 'success';
            }else{
                print 'fail';
            }
        }else{
            print 'Current Password incorrect';
        }
    }

    public function submit_loan_accessibility(){
        $student_id = $this->session->userdata('user_id');
        $student_loan_id = $this->input->post('student_loan_id');
        $status = $this->input->post('status');

        $data = array(
            'monthly_fixed_income'      =>  $this->input->post('monthly_fixed_income'),
            'monthly_rental_income'     =>  $this->input->post('monthly_rental_income'),
            'credit_cards'              =>  $this->input->post('credit_cards'),
            'monthly_variable_income'   =>  $this->input->post('monthly_variable_income'),
            'car_loans'                 =>  $this->input->post('car_loans'),
            'pledged_deposits'          =>  $this->input->post('pledged_deposits'),
            'existing_home_loans'       =>  $this->input->post('existing_home_loans'),
            'unpledged_deposits'        =>  $this->input->post('unpledged_deposits'),
            'other_loans'               =>  $this->input->post('other_loans'),
            'gross_income'              =>  $this->input->post('gross_income'),
            'tdsr_limit'                =>  $this->input->post('tdsr_limit'),
            'debt_obligations'          =>  $this->input->post('debt_obligations'),
            'current_tdsr'              =>  $this->input->post('current_tdsr'),
            'servicing'                 =>  $this->input->post('servicing'),
            'student_note'              =>  $this->input->post('student_note'),
            'monthly_installment'       =>  $this->input->post('monthly_installment'),
            'interest_rate'             =>  $this->input->post('interest_rate'),
            'loan_duration'             =>  $this->input->post('loan_duration'),
            'maximum_loan'              =>  $this->input->post('maximum_loan'),
            'purchase_price_75'         =>  $this->input->post('purchase_price_75'),
            'purchase_price_80'         =>  $this->input->post('purchase_price_80'),
            'purchase_price_90'         =>  $this->input->post('purchase_price_90'),
            'student_id'                =>  $student_id,
            'status'                    =>  $status,
        );

        if(!empty($student_loan_id)){//EDIT
            
            $data['update_date'] = date('Y-m-d H:i:s');
            
            $this->db->where('id',$student_loan_id);
            if($this->db->update('student_loan_accessibility',$data)){
                if($status == 2 || $status == 3){
                    $this->save_student_nof_submitaip($student_loan_id, 'update');
                    print $student_loan_id;
                }
            }else{
                print 'fail';
            }
        }else{//ADD NEW
            if($status == 1){
                $data['save_date'] = date('Y-m-d H:i:s');
                $data['update_date'] = date('Y-m-d H:i:s');
            }else{
                $data['submit_date'] = date('Y-m-d H:i:s');
                $data['update_date'] = date('Y-m-d H:i:s');
                $data['banker_id'] = $this->find_banker($student_id);
            }
            
            if($this->db->insert('student_loan_accessibility',$data)){
                $last_id = $this->db->insert_id();
                if($status == 2){
                    $this->save_student_nof_submitaip($last_id, 'submit');
                }
                print $last_id;
            }else{
                print 'fail';
            } 
        }
        
    }

    public function save_loan_accessibility(){
        $student_id = $this->session->userdata('user_id');
        $student_loan_id = $this->input->post('student_loan_id');
        $status = $this->input->post('status');

        $data = array(
            'monthly_fixed_income'      =>  $this->input->post('monthly_fixed_income'),
            'monthly_rental_income'     =>  $this->input->post('monthly_rental_income'),
            'credit_cards'              =>  $this->input->post('credit_cards'),
            'monthly_variable_income'   =>  $this->input->post('monthly_variable_income'),
            'car_loans'                 =>  $this->input->post('car_loans'),
            'pledged_deposits'          =>  $this->input->post('pledged_deposits'),
            'existing_home_loans'       =>  $this->input->post('existing_home_loans'),
            'unpledged_deposits'        =>  $this->input->post('unpledged_deposits'),
            'other_loans'               =>  $this->input->post('other_loans'),
            'gross_income'              =>  $this->input->post('gross_income'),
            'tdsr_limit'                =>  $this->input->post('tdsr_limit'),
            'debt_obligations'          =>  $this->input->post('debt_obligations'),
            'current_tdsr'              =>  $this->input->post('current_tdsr'),
            'servicing'                 =>  $this->input->post('servicing'),
            'student_note'              =>  $this->input->post('student_note'),
            'student_id'                =>  $student_id,
            'status'                    =>  $status,
        );

        if(!empty($student_loan_id)){//EDIT
            
            $data['update_date'] = date('Y-m-d H:i:s');
            
            $this->db->where('id',$student_loan_id);
            if($this->db->update('student_loan_accessibility',$data)){
                if($status == 2 || $status == 3){
                    $this->save_student_nof_submitaip($student_loan_id, 'update');
                    print $student_loan_id;
                }
            }else{
                print 'fail';
            }
        }else{//ADD NEW
            if($status == 1){
                $data['save_date'] = date('Y-m-d H:i:s');
                $data['update_date'] = date('Y-m-d H:i:s');
            }else{
                $data['submit_date'] = date('Y-m-d H:i:s');
                $data['update_date'] = date('Y-m-d H:i:s');
                $data['banker_id'] = $this->find_banker($student_id);
            }
            
            if($this->db->insert('student_loan_accessibility',$data)){
                $last_id = $this->db->insert_id();
                if($status == 2){
                    $this->save_student_nof_submitaip($last_id, 'submit');
                }
                print $last_id;
            }else{
                print 'fail';
            } 
        }
        
    }

    public function upgrade_loan_accessibility(){
        $student_id = $this->session->userdata('user_id');
        $student_loan_id = $this->input->post('student_loan_id');
        $status = $this->input->post('status');

        $data = array(
            'monthly_fixed_income'      =>  $this->input->post('monthly_fixed_income'),
            'monthly_rental_income'     =>  $this->input->post('monthly_rental_income'),
            'credit_cards'              =>  $this->input->post('credit_cards'),
            'monthly_variable_income'   =>  $this->input->post('monthly_variable_income'),
            'car_loans'                 =>  $this->input->post('car_loans'),
            'pledged_deposits'          =>  $this->input->post('pledged_deposits'),
            'existing_home_loans'       =>  $this->input->post('existing_home_loans'),
            'unpledged_deposits'        =>  $this->input->post('unpledged_deposits'),
            'other_loans'               =>  $this->input->post('other_loans'),
            'gross_income'              =>  $this->input->post('gross_income'),
            'tdsr_limit'                =>  $this->input->post('tdsr_limit'),
            'debt_obligations'          =>  $this->input->post('debt_obligations'),
            'current_tdsr'              =>  $this->input->post('current_tdsr'),
            'servicing'                 =>  $this->input->post('servicing'),
            'student_note'              =>  $this->input->post('student_note'),
            'student_id'                =>  $student_id,
            'status'                    =>  $status,
        );

        $data['submit_date'] = date('Y-m-d H:i:s');
        $data['update_date'] = date('Y-m-d H:i:s');
        $data['banker_id'] = $this->find_banker($student_id);

        $this->db->where('id',$student_loan_id);
        if($this->db->update('student_loan_accessibility',$data)){
            $this->save_student_nof_submitaip($student_loan_id, 'submit');
            print $student_loan_id;
        }else{
            print 'fail';
        }
    }

    public function save_student_nof_submitaip($student_loan_id, $type){
        $info = $this->model->getLoanInfo($student_loan_id);

        $data = array(
            'type'          =>  $type,
            'loan_id'       =>  $student_loan_id,
            'student_id'    =>  $info['0']->student_id,
            'banker_id'     =>  $info['0']->banker_id,
            'created'       =>  date('Y-m-d H:i:s'),
            'status'        =>  1
        );
        $this->db->insert('student_nof',$data);
    }

    public function update_affordability()
    {
        $loan_id = $this->input->post('loan_id');
        if(!empty($loan_id)){
            $data = array(
                'monthly_installment'       =>  $this->input->post('monthly_installment'),
                'interest_rate'             =>  $this->input->post('interest_rate'),
                'loan_duration'             =>  $this->input->post('loan_duration'),
                'maximum_loan'              =>  $this->input->post('maximum_loan'),
                'purchase_price_75'         =>  $this->input->post('purchase_price_75'),
                'purchase_price_80'         =>  $this->input->post('purchase_price_80'),
                'purchase_price_90'         =>  $this->input->post('purchase_price_90'),
                'update_date'               =>  date('Y-m-d H:i:s')
            );
            $this->db->where('id',$loan_id);
            if($this->db->update('student_loan_accessibility',$data)){
                $this->save_student_nof_submitaip($loan_id, 'update');
                print 'success';
            }else{
                print 'fail';
            }
        }

    }

    public function save_banker_nof_submitaip($student_loan_id, $type){
        $info = $this->model->getLoanInfo($student_loan_id);

        $data = array(
            'type'          =>  $type,
            'loan_id'       =>  $student_loan_id,
            'student_id'    =>  $info['0']->student_id,
            'banker_id'     =>  $info['0']->banker_id,
            'created'       =>  date('Y-m-d H:i:s'),
            'status'        =>  1
        );
        $this->db->insert('banker_nof',$data);
    }

     public function find_banker($user_id){
        $info = $this->model->getLoanSubmited($user_id);

        if(!empty($info)){
            return $info;
        }else{
            $list_banker = $this->model->getListBanker();
            $number = rand(0, sizeof($list_banker) - 1);
            return $list_banker[$number]->id;
        }
    }

    public function save_loan_remark()
    {
        $student_id = $this->session->userdata('user_id');

        $data = array(
            'content'            =>  $this->input->post('remark'),
            'student_id'         =>  $student_id ,
            'student_loan_id'    =>  $this->input->post('id'),
            'status'             =>  1,
            'created'            =>  date('Y-m-d H:i:s')
        );

        if($this->db->insert('student_loan_note',$data)){
            print 'success';
        }else{
            print 'fail';
        }
    }

    public function delete_not(){
        $this->db->where('id',$this->input->post('id'));
        if($this->db->delete('banker_nof')){
            print 'success';
        }else{
            print 'fail';
        }
    }

    public function update_not(){
        $data['status'] = 2;
        $this->db->where('id',$this->input->post('id'));
        if($this->db->update('banker_nof', $data)){
            print 'success';
        }else{
            print 'fail';
        }
    }

    public function get_student_note(){
        $note = $this->model->getStudentNote($this->input->post('id'));
        print $note;
    }

    public function update_loan_status(){
        //Change status 5->2, REVIEW to SUBMIT
        //Save Nof submit
        //Find banker
        //Save Submit Date 
        $student_id = $this->session->userdata('user_id');
       
        $data = array(
            'status' => 2,
            'banker_id' => $this->find_banker($student_id),
            'submit_date' => date('Y-m-d H:i:s')
        );
        $this->db->where('id',$this->input->post('id'));
        if($this->db->update('student_loan_accessibility', $data)){
            $this->save_student_nof_submitaip($this->input->post('id'), 'submit');
        }
    }

    function update_fullname(){
        $list = $this->model->getListStudent();

        foreach ($list as $k => $v){
            $data = array(
                'fullname' => $v->firstname.' '.$v->lastname
            );

            $this->db->where('id',$v->id);
            $this->db->update('student', $data);
        }
    }
    /*END CODE OF MANLUONG*/

    public function personal_info(){
        $user_id = $this->session->userdata('user_id');
        $user_info = $this->model->getUserInfo($user_id);
        
        $data = array(
            'user_info' => $user_info
        );

        $this->template->write('title','Personal Info');
        $this->template->write_view('content','FRONTEND/personal_info',$data);
        $this->template->render(); 
    }

    public function personal_info_edit(){
        $user_id = $this->session->userdata('user_id');
        $user_info = $this->model->getUserInfo($user_id);
        
        $data = array(
            'user_info' => $user_info['0'],
            'national'  => $this->model->getNationality(),
            'pim'       => $this->model->getPIM()
            
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

    public function property_ultimate(){

        $data = null;
        $this->template->write('title','Property Ultimate');
        $this->template->write_view('content','FRONTEND/property_ultimate',$data);
        $this->template->render(); 
    }

    public function loan_accessibility(){

        $data = null;
        $this->template->write('title','Loan Accessibility');
        $this->template->write_view('content','FRONTEND/loan_accessibility',$data);
        $this->template->render(); 
    }    

    public function edit_loan_accessibility(){
        $id = $this->uri->segment(3);
        $data = $this->model->getLoanInfo($id);
        
        $data = array(
            'data' => $data['0']
        );
        $this->template->write('title','Loan Accessibility');
        $this->template->write_view('content','FRONTEND/loan_accessibility_edit',$data);
        $this->template->render(); 
    }  

    public function loan_accessibility_detail(){
        $user_id = $this->session->userdata('user_id');

        $id = $this->uri->segment(3);
        $data = $this->model->getLoanInfo($id);
        $remark = $this->model->getLoanRemark($data['0']->id);

        if($user_id == $data['0']->student_id){//Student only can access theirs loan
            $data = array(
                'data' => $data['0'],
                'remark' => $remark
            );

            $this->template->write('title','Detail');
            $this->template->write_view('content','FRONTEND/loan_accessibility_detail',$data);
            $this->template->render(); 
        }else{
            header('Location: '.PATH_URL.'home/sign_in');
            exit;
        }
        
    } 

    public function affordability(){
        $data =  null;
        $this->template->write('title','Affordability');
        $this->template->write_view('content','FRONTEND/affordability',$data);
        $this->template->render(); 
    }

    public function affordability_edit(){

        $user_id = $this->session->userdata('user_id');

        $id = $this->uri->segment(3);
        $data = $this->model->getLoanInfo($id);

        if($user_id == $data['0']->student_id){//Student only can access theirs loan
            $data = array(
                'data' => $data['0'],
            );

            $this->template->write('title','Affordability');
            $this->template->write_view('content','FRONTEND/affordability_edit',$data);
            $this->template->render(); 
        }else{
            header('Location: '.PATH_URL.'home/sign_in');
            exit;
        }
    } 

    public function notification(){
        $user_id = $this->session->userdata('user_id');
        $data = $this->model->getNofforStudent($user_id);

        $data = array(
            'data' => $data
        );
        $this->template->write('title','Notification');
        $this->template->write_view('content','FRONTEND/notification',$data);
        $this->template->render(); 
    }

    public function notification_header(){
        $user_id = $this->session->userdata('user_id');
        $data = $this->model->getNofforStudentHeader($user_id);

        $data = array(
            'data' => $data
        );
        $this->load->view('FRONTEND/notification_header',$data);
    }

    public function summary(){
        $user_id = $this->session->userdata('user_id');
        $data = $this->model->getlistLoan($user_id);
        
        $data = array(
            'data' => $data
        );
        $this->template->write('title','Summary');
        $this->template->write_view('content','FRONTEND/summary',$data);
        $this->template->render(); 
    }


    /*===================================
        Calculate for Property Ultimate
    ===================================== */
    function ajax_property_ultimate_func(){      

        $dataForm = array();
        $results ='';
        parse_str($_POST['dataForm'], $dataForm);
        foreach ($dataForm as $key => $value) {
            $dataForm[$key] = str_replace(array(','),array(''),$value);
        }   
        $dataForm = array_map('floatval', $dataForm);
        extract($dataForm);
        $rate_months = $rate_years/100/12;
        
        $layout = file_get_contents(PATH_URL.'application/modules/student/views/FRONTEND/layout-result-property-ultimate.php');    
        $loan = $home_price - $down_payment_dola;
        $payment = $loan*$rate_months*pow(1+$rate_months, $number_months) / (pow(1+$rate_months, $number_months) -1);
        $total_interest = 0;
        $date_start = date("d/m/Y") ;

        for ($i=1; $i <= $number_months; $i++) {

            $interest   = $loan * $rate_months;   
            $total_interest = $total_interest + $interest;        
            $principal  = $payment - $interest;
            $balance    = $loan - $principal;
            $loan       = $balance;          
            $next_month =  strtotime("+".$i." month");  
            $results .='<tr>
                <td class="calculator-amortization-date">'.$i.'</td>
                <td class="calculator-amortization-data">'.number_format($payment,2).'</td>
                <td class="calculator-amortization-data">'.number_format($principal,2) .'</td> 
                <td class="calculator-amortization-data">'.number_format($interest,2).'</td>            
                <td class="calculator-amortization-data">'.number_format($balance,2).'</td></tr>';
            

        }
        $date_end = date("d/m/Y", $next_month) ;
        $output  = str_replace(array('{{payment}}','{{date_start}}','{{date_end}}','{{results}}'), array(number_format($payment,2),$date_start, $date_end,$results), $layout);

        echo $output;
        exit();
        
    }
    /*===================================
        Calculate for Loan Accessibility
    ===================================== */ 
    function ajax_loan_accessibility_func(){

        $dataForm = array();
        parse_str($_POST['dataForm'], $dataForm);
        foreach ($dataForm as $key => $value) {
            $dataForm[$key] = str_replace(array(','),array(''),$value);
        }   
        $dataForm = array_map('floatval', $dataForm);
        extract($dataForm);
        $gross_income_org = $monthly_fixed_income + $monthly_rental_income*0.7 + $monthly_variable_income/12*0.7 + $pledged_deposits/100000*2083 + $unpledged_deposits/100000*625;
        $gross_income = number_format($gross_income_org, 2);
        $tdsr_limit = number_format($gross_income_org*0.6, 2);
        $debt_obligations = number_format($credit_cards + $car_loans + $existing_home_loans + $other_loans, 2);
        if($monthly_fixed_income || $monthly_variable_income || $pledged_deposits || $unpledged_deposits){
            $current_tdsr = number_format(($credit_cards + $car_loans + $existing_home_loans + $other_loans)/$gross_income_org,2)*100;
        }else{
            $current_tdsr =0;
        }       
        $servicing = number_format(0.6*($monthly_fixed_income + $monthly_rental_income*0.7 + $monthly_variable_income/12*0.7 + $pledged_deposits/100000*2083 + $unpledged_deposits/100000*625) - ($credit_cards + $car_loans + $existing_home_loans + $other_loans), 2);      
        $layout = file_get_contents(PATH_URL.'application/modules/student/views/FRONTEND/layout-result-loan_accessibility.php'); 
        $warning = ($gross_income>0)? '': '<div class="warning">Improve your TDSR</div>';      
        $html = str_replace(array('{{gross_income}}','{{tdsr_limit}}','{{debt_obligations}}','{{current_tdsr}}','{{servicing}}','{{warning}}'), array($gross_income,$tdsr_limit, $debt_obligations,$current_tdsr,$servicing, $warning), $layout);
        $output = array(
            'html' =>$html,
            'gross_income' =>(float)$gross_income
        );  

        echo json_encode($output);
        exit();
        
    }

    /*===================================
        Calculate for Loan Affordability
    ===================================== */ 
    function ajax_affordability_calculate_func(){

        $dataForm = array();
        parse_str($_POST['dataForm'], $dataForm);
        foreach ($dataForm as $key => $value) {
            $dataForm[$key] = str_replace(array(','),array(''),$value);
        }   
        $dataForm = array_map('floatval', $dataForm);
        extract($dataForm); 
        $interest_rate = $interest_rate/12/100;
        $loan_duration = $loan_duration*12;
        $maximum_loan = ($monthly_installment*(pow(1+$interest_rate, $loan_duration) -1)/$interest_rate) /pow(1+$interest_rate, $loan_duration);    
        $purchase_price_75 = number_format($maximum_loan/0.75,2);
        $purchase_price_80 = number_format($maximum_loan/0.80,2);
        $purchase_price_90 = number_format($maximum_loan/0.90,2);
        
        $layout = file_get_contents(PATH_URL.'application/modules/student/views/FRONTEND/layout-result-affordability.php'); 
        $output  = str_replace(array('{{maximum_loan}}','{{purchase_price_75}}','{{purchase_price_80}}','{{purchase_price_90}}'), array(number_format($maximum_loan,2),$purchase_price_75, $purchase_price_80,$purchase_price_90), $layout);

        echo $output;
       exit();
        
    }
    public function faqs(){
        $data = null;
        $this->template->write('title','Support - FAQs');
        $this->template->write_view('content','FRONTEND/faqs',$data);
        $this->template->render(); 
    }
     public function pdpa(){
        $data = null;
        $this->template->write('title','Support - PDPA');
        $this->template->write_view('content','FRONTEND/pdpa',$data);
        $this->template->render(); 
    }
    /**
     * MOBILE API
     */

    public function mobile_change_pass(){
        $accesstoken = $this->input->post('accesstoken');
        if($this->model->verifyAccessToken($accesstoken)){
            $id = $this->model->mobileGetUserInfo($accesstoken)->id;
        } else {
            $this->model->errorResponse("Access token is required");
            die;
        }
        $oldpass = trim($this->input->post('oldpass'));
        $oldpass2 = $this->model->getStudentPass($id);
       
        if(md5($oldpass) == $this->model->getStudentPass($id)){
            $data = array(
                'password'  =>  md5($this->input->post('pass'))
            );
            $this->db->where('id',$id);
            if($this->db->update('student',$data)){
                $accesstoken = $this->model->createAccessToken($id, $this->input->post('pass'));
                $this->model->successResponse(null, ['accesstoken' => $accesstoken]);
            }else{
                $this->model->errorResponse("Can not change password");
            }
        }else{
            $this->model->errorResponse("Current password is incorrect");
        }
    }

    public function mobile_update_banker_profile(){
        $accesstoken = $this->input->post('accesstoken');
        if($this->model->verifyAccessToken($accesstoken)){
            $id = $this->model->mobileGetUserInfo($accesstoken)->id;
        } else {
            $this->model->errorResponse("Access token is required");
            die;
        }

        $firstname = $this->input->post('firstname');
        $data = array(
            'firstname'     =>  $firstname,
            'lastname'      =>  $this->input->post('lastname'),
        );
        $email = $this->input->post('email');
        if (!empty($email)){
            $data['email'] = $email;
        }
        
        $this->db->where('id',$id);
        if($this->db->update('student',$data)){
            $this->session->set_userdata('user_first_name', $firstname);
            $user = $this->model->mobileGetUserInfo($accesstoken);
            $this->model->successResponse(null, $user);
        }else{
            $this->model->errorResponse("Can not update your changes");
        }
        
    }


    public function mobile_update_user_profile(){
        $accesstoken = $this->input->post('accesstoken');
        if($this->model->verifyAccessToken($accesstoken)){
            $id = $this->model->mobileGetUserInfo($accesstoken)->id;
        } else {
            $this->model->errorResponse("Access token is required");
            die;
        }

        $firstname = $this->input->post('firstname');
        $data = array(
            'firstname'     =>  $firstname,
            'lastname'      =>  $this->input->post('lastname'),
            'birthday'      =>  $this->input->post('birthday'),
            'postal_code'   =>  $this->input->post('postal_code'),
            'email'         =>  $this->input->post('email'),
            'street'        =>  $this->input->post('street'),
            'batch_no'      =>  $this->input->post('batch_no'),
            'gender'        =>  $this->input->post('gender'),
            'nationality'   =>  $this->input->post('nationality'),
            'unit'          =>  $this->input->post('unit'),
            'building_name' => $this->input->post('building_name')
        );
        
        $this->db->where('id',$id);
        if($this->db->update('student',$data)){
            $this->session->set_userdata('user_first_name', $firstname);
            $user = $this->model->mobileGetUserInfo($accesstoken);
            $this->model->successResponse(null, $user);
        }else{
            $this->model->errorResponse("Can not update your changes");
        }
        
    }

    public function mobile_get_loan_accessibility(){
        $accesstoken = $this->input->get('accesstoken');
        if($this->model->verifyAccessToken($accesstoken)){
            $user = $this->model->mobileGetUserInfo($accesstoken);
            $page = $this->input->get('page');
            $data = [];
            if ($user->type == 1){
                $data = $this->model->getLoanAccessibilityAtPage($user->id, $page);
            } else if ($user->type == 2) {
                $status = $this->input->get('status');
                $pim = $this->input->get('pim');
                $time = $this->input->get('time');
                $data= $this->model->getLoanAccessibilityOfBankerAtPage($user->id, $page, $status, $pim, $time);
            }
            $this->model->successResponseWithPage(null, $page, $data);
        } else {
            $this->model->errorResponse("Access token is required");
        }
    }

    public function mobile_get_loan_accessibility_detail(){
        $accesstoken = $this->input->get('accesstoken');
        if($this->model->verifyAccessToken($accesstoken)){
            $id = $this->input->get('id');
            $data = $this->model->getLoanAccessibilityDetail($id);
            $this->model->successResponse(null, $data);
        } else {
            $this->model->errorResponse("Access token is required");
        }
    }

    public function mobile_get_banker(){
        $accesstoken = $this->input->get('accesstoken');
        if($this->model->verifyAccessToken($accesstoken)){
            $data = $this->model->mobileGetListBanker();
            $this->model->successResponse(null, $data);
        } else {
            $this->model->errorResponse("Access token is required");
        }
    }

    public function mobile_get_notification(){
        $accesstoken = $this->input->get('accesstoken');
        if($this->model->verifyAccessToken($accesstoken)){
            $user = $this->model->mobileGetUserInfo($accesstoken);
            $page = $this->input->get('page');
            $data = [];
            if ($user->type == 2){
                $data = $this->model->getBankerNotificationAtPage($user->id, $page);
            } else if ($user->type == 1){
                $data = $this->model->getStudentNotificationAtPage($user->id, $page);
            } 
            $this->model->successResponseWithPage(null, $page, $data);
        } else {
            $this->model->errorResponse("Access token is required");
        }
    }


    public function mobile_save_loan_accessibility(){
        $accesstoken = $this->input->post('accesstoken');
        if($this->model->verifyAccessToken($accesstoken)){
            $user = $this->model->mobileGetUserInfo($accesstoken);
        } else {
            $this->model->errorResponse("Access token is required");
            die;
        }

        $id = $this->input->post('student_loan_id');
        $status = $this->input->post('status');

        $data = array(
            'monthly_fixed_income'      =>  $this->input->post('monthly_fixed_income'),
            'monthly_rental_income'     =>  $this->input->post('monthly_rental_income'),
            'credit_cards'              =>  $this->input->post('credit_cards'),
            'monthly_variable_income'   =>  $this->input->post('monthly_variable_income'),
            'car_loans'                 =>  $this->input->post('car_loans'),
            'pledged_deposits'          =>  $this->input->post('pledged_deposits'),
            'existing_home_loans'       =>  $this->input->post('existing_home_loans'),
            'unpledged_deposits'        =>  $this->input->post('unpledged_deposits'),
            'other_loans'               =>  $this->input->post('other_loans'),
            'gross_income'              =>  $this->input->post('gross_income'),
            'tdsr_limit'                =>  $this->input->post('tdsr_limit'),
            'debt_obligations'          =>  $this->input->post('debt_obligations'),
            'current_tdsr'              =>  $this->input->post('current_tdsr'),
            'servicing'                 =>  $this->input->post('servicing'),
            'monthly_installment'       =>  $this->input->post('monthly_installment'),
            'interest_rate'             =>  $this->input->post('interest_rate'),
            'loan_duration'             =>  $this->input->post('loan_duration'),
            'maximum_loan'              =>  $this->input->post('maximum_loan'),
            'purchase_price_75'         =>  $this->input->post('purchase_price_75'),
            'purchase_price_80'         =>  $this->input->post('purchase_price_80'),
            'purchase_price_90'         =>  $this->input->post('purchase_price_90'),
            'student_id'                =>  $user->id,
            'status'                    =>  $status
        );

        $student_note = $this->input->post('student_note');
        if (!empty($student_note)){
            $data['student_note'] = $student_note;
        }

        if(!empty($id)){//EDIT
            if ($status == 2){
                $data['submit_date'] = date('Y-m-d H:i:s');
                $data['banker_id'] = $this->find_banker($user->id);
            }
            $previousStatus = $this->model->getLoanInfo($id)[0]->status;
            $data['update_date'] = date('Y-m-d H:i:s');
            $this->db->where('id',$id);
            if($this->db->update('student_loan_accessibility',$data)){
                $loan = $this->model->getLoanInfo($id);
                $loan[0]->remarks = $this->model->getLoanRemark($id);
                $loan[0]->banker = $this->model->getUserInfo($loan[0]->banker_id);
                $loan[0]->student = $this->model->getUserInfo($loan[0]->student_id);
                if($status == 2){
                    if ($previousStatus == 1){
                        $this->save_student_nof_submitaip($id, 'submit');
                        $this->model->sendNotification($loan[0]->banker_id, 'loan_submitted', $id);
                    } else if ($previousStatus == 2){
                        $this->save_student_nof_submitaip($id, 'update');
                        $this->model->sendNotification($loan[0]->banker_id, 'loan_updated', $id);
                    }
                }
                $this->model->successResponse(null, $loan);
            }else{
                $this->model->errorResponse("Can not update your changes");
            }
        }else{//ADD NEW
            if($status == 1){
                $data['save_date'] = date('Y-m-d H:i:s');
            }else{
                $data['submit_date'] = date('Y-m-d H:i:s');
                $data['banker_id'] = $this->find_banker($user->id);
            }
            $data['update_date'] = date('Y-m-d H:i:s');
            if($this->db->insert('student_loan_accessibility',$data)){
                $last_id = $this->db->insert_id();
                $loan = $this->model->getLoanInfo($last_id);
                if($status == 2){
                    $this->save_student_nof_submitaip($last_id, 'submit');
                    $this->model->sendNotification($loan[0]->banker_id, 'loan_submitted', $last_id);
                }
                $this->model->successResponse(null, $loan);
            }else{
                $this->model->errorResponse("Can not create your submission");
            } 
        }
    }

    public function mobile_send_loan_remark()
    {
        $accesstoken = $this->input->post('accesstoken');
        if($this->model->verifyAccessToken($accesstoken)){
            $user = $this->model->mobileGetUserInfo($accesstoken);
            $student_loan_id = $this->input->post('student_loan_id');
            $saveAsNotify = $this->input->post('save_as_notify');
            $loan = $this->model->getLoanInfo($student_loan_id);
            $data = array(
                'content'            =>  $this->input->post('remark'),
                'student_loan_id'    =>  $student_loan_id,
                'status'             =>  1,
                'created'            =>  date('Y-m-d H:i:s')
            );

            if ($user->type == 1){
                $data['student_id'] = $user->id;
            } else if ($user->type == 2){
                $data['banker_id'] = $user->id;
            }
    
            if($this->db->insert('student_loan_note',$data)){
                if ($saveAsNotify == 1){
                    if ($user->type == 1){
                        $this->save_student_nof_submitaip($student_loan_id,'remark');
                        $this->model->sendNotification($loan[0]->banker_id, 'student_send_remark', $student_loan_id);
                    } else if ($user->type == 2){
                        $this->save_banker_nof_submitaip($student_loan_id,'remark');
                        $this->model->sendNotification($loan[0]->student_id, 'banker_send_remark', $student_loan_id);
                    }
                }
                $this->model->successResponse(null, []);
            }else{
                $this->model->errorResponse("Can not send your remark");
            }
        } else {
            $this->model->errorResponse("Access token is required");
        }
    }

    public function mobile_upload_avatar(){
        $accesstoken = $this->input->post('accesstoken');
        if($this->model->verifyAccessToken($accesstoken)){
            $user = $this->model->mobileGetUserInfo($accesstoken);
            $base64 = $this->input->post('base64');
            $fileName = $this->input->post('filename');
            $targetPath = BASEFOLDER."statics/uploads/avatar/".$fileName;
            if (strlen($base64) > 0){
                if(file_put_contents($targetPath, base64_decode($base64))){
                    $data = array(
                        'image'  =>  $fileName
                    );
                    $this->db->where('id',$user->id);
                    if($this->db->update('student',$data)){
                        $user = $this->model->mobileGetUserInfo($accesstoken);
                        $this->model->successResponse(null, $user);    
                    } else{
                        $this->model->errorResponse("Can not upload the file");    
                    }
                } else {
                    $this->model->errorResponse("Can not upload the file");    
                }
            } else {
                $this->model->errorResponse("Param base64 is required");
            }
        } else {
            $this->model->errorResponse("Access token is required");
            die;
        }
        
    }

    public function mobile_update_device_token(){
        $accesstoken = $this->input->post('accesstoken');
        if($this->model->verifyAccessToken($accesstoken)){
            $id = $this->model->mobileGetUserInfo($accesstoken)->id;
        } else {
            $this->model->errorResponse("Access token is required");
            die;
        }

        $devicetoken = $this->input->post('devicetoken');
        $data = array(
            'devicetoken'     =>  $devicetoken
        );
        
        $this->db->where('id',$id);
        if($this->db->update('student',$data)){
            $this->model->successResponse(null, []);
        }else{
            $this->model->errorResponse("Can not update your device token");
        }
        
    }

    public function mobile_banker_comment()
    {
        $accesstoken = $this->input->post('accesstoken');
        if($this->model->verifyAccessToken($accesstoken)){

            $data = array(
                'banker_comment'      =>  $this->input->post('banker_comment'),
                'hdb_installmemt'     =>  date('Y-m-d', strtotime($this->input->post('hdb_installmemt')))
            );
    
            $this->db->where('id', $this->input->post('student_loan_id'));
            if($this->db->update('student_loan_accessibility',$data)){
                $this->model->successResponse(null, []);
            }else{
                $this->model->errorResponse("Can not update your installment");
            }
        } else {
            $this->model->errorResponse("Access token is required");
        }   
    }


    public function mobile_student_note()
    {
        $accesstoken = $this->input->post('accesstoken');
        if($this->model->verifyAccessToken($accesstoken)){

            $data = array(
                'student_note'      =>  $this->input->post('student_note'),
            );
    
            $this->db->where('id', $this->input->post('student_loan_id'));
            if($this->db->update('student_loan_accessibility',$data)){
                $this->model->successResponse(null, []);
            }else{
                $this->model->errorResponse("Can not update your note");
            }
        } else {
            $this->model->errorResponse("Access token is required");
        }
    }

    public function mobile_confirm_aip()
    {
        $accesstoken = $this->input->post('accesstoken');
        if($this->model->verifyAccessToken($accesstoken)){
            $loan_id = $this->input->post('student_loan_id');
            $data = array(
                'verified_amount'      =>  $this->input->post('verified_amount'),
                'agree'                =>  1,
                'status'               =>  4,
                'update_date'          =>  date('Y-m-d H:i:s'),
                'verify_date'          =>  date('Y-m-d H:i:s')
            );
    
            $this->db->where('id', $loan_id);
            if($this->db->update('student_loan_accessibility',$data)){
                $loan = $this->model->getLoanInfo($loan_id);
                $loan[0]->remarks = $this->model->getLoanRemark($loan_id);
                $loan[0]->student = $this->model->getUserInfo($loan[0]->student_id);
                $loan[0]->banker = $this->model->getUserInfo($loan[0]->banker_id);
                $this->save_banker_nof_submitaip($loan_id,'verify');
                $this->model->sendNotification($loan[0]->student_id, 'loan_verified', $loan_id);
                $this->model->successResponse(null, $loan);
            }else{
                $this->model->errorResponse("Can not confirm AIP");
            }
        } else {
            $this->model->errorResponse("Access token is required");
        }
        
    }

    public function mobile_banker_view_loan()
    {
        $accesstoken = $this->input->post('accesstoken');
        if($this->model->verifyAccessToken($accesstoken)){
            $loan_id = $this->input->post('student_loan_id');
            $data = array(
                'status'               =>  3,
                'update_date'          =>  date('Y-m-d H:i:s')
            );
    
            $this->db->where('id', $loan_id);
            if($this->db->update('student_loan_accessibility',$data)){
                $loan = $this->model->getLoanInfo($loan_id);
                $loan[0]->remarks = $this->model->getLoanRemark($loan_id);
                $loan[0]->student = $this->model->getUserInfo($loan[0]->student_id);
                $loan[0]->banker = $this->model->getUserInfo($loan[0]->banker_id);
                $this->model->successResponse(null, $loan);
            }else{
                $this->model->errorResponse("Can not update Loan Accessibility status");
            }
        } else {
            $this->model->errorResponse("Access token is required");
        }
        
    }

    public function notification_count(){
        $accesstoken = $this->input->get('accesstoken');
        if($this->model->verifyAccessToken($accesstoken)){
            $user = $this->model->mobileGetUserInfo($accesstoken);
            $data = [];
            if ($user->type == 2){
                $data = $this->model->countBankerNotification($user->id);
            } else if ($user->type == 1){
                $data = $this->model->countStudentNotification($user->id);
            } 
            $this->model->successResponse(null, $data);
        } else {
            $this->model->errorResponse("Access token is required");
        }
    }

    public function view_notification(){
        $accesstoken = $this->input->post('accesstoken');

        if($this->model->verifyAccessToken($accesstoken)) {
            $user = $this->model->mobileGetUserInfo($accesstoken);
            $data['status'] = 2;
            $this->db->where('id',$this->input->post('id'));
            if ($user->type == 2){
                $this->db->update('student_nof', $data);
            } else if ($user->type == 1) {
                $this->db->update('banker_nof', $data);
            } 
            $this->model->successResponse(null,[]);
        } else {
            $this->model->errorResponse("Access token is required");
        };
    }

    public function mobile_delete_notification(){
        $accesstoken = $this->input->post('accesstoken');
        if($this->model->verifyAccessToken($accesstoken)){
            $ids = $this->input->post('ids');
            $user = $this->model->mobileGetUserInfo($accesstoken);
            if ($user->type == 2){
                $this->model->deleteBankerNotification($ids);
            } else if ($user->type == 1) {
                $this->model->deleteStudentNotification($ids);
            } 
            $this->model->successResponse(null, []);
        } else {
            $this->model->errorResponse("Access token is required");
        }
    }

    public function mobile_banker_count_loan(){
        $accesstoken = $this->input->get('accesstoken');
        if($this->model->verifyAccessToken($accesstoken)){
            $id = $this->model->mobileGetUserInfo($accesstoken)->id;
            $status = $this->input->get('status');
            $pim = $this->input->get('pim');
            $time = $this->input->get('time');
            $data = [];
            $data['count'] = $this->model->countBankerLoanAccessibilityFilter($id, $status, $pim, $time);
            $data['total'] = $this->model->countBankerLoanAccessibilityTotal($id);
            $this->model->successResponse(null, $data);
        } else {
            $this->model->errorResponse("Access token is required");
        }
    }

    public function mobile_logout(){
        $accesstoken = $this->input->post('accesstoken');
        if($this->model->verifyAccessToken($accesstoken)){
            $this->model->logout($accesstoken);
            $this->model->successResponse(null, []);
        } else {
            $this->model->errorResponse("Access token is required");
        }
    }
}