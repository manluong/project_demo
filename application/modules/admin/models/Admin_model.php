<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Admin_model extends MY_Model {

	private $module = 'admin';
    private $token;
	

    function __construct(){
        parent::__construct();
        //$this->lang_code = $this->lang->default_lang();
        $this->load->database();
        $this->primaryKey = 'id';
    }
    
    function getlistLoanforAdmin($limit, $start){
        $this->db->select('student_loan_accessibility.id as loan_id, 
                            student_loan_accessibility.student_id as student_id, 
                            student.firstname, student.lastname, student.batch_no as batch_no, student.email, student.phone');
        $this->db->join('student', 'student.id = student_id','left');
        $this->db->where('student_loan_accessibility.status !=', 1);
        $this->db->group_by('student_id');
        $this->db->limit($limit, $start);
        $this->db->order_by('student_loan_accessibility.submit_date', 'asc');

        $query = $this->db->get('student_loan_accessibility');
        return $query->result();
    }

    function getsearchLoan($limit, $start){
        $this->db->select('student_loan_accessibility.*, student_loan_accessibility.id as loan_id, 
                        student.firstname, student.lastname, student.batch_no as batch_no');
        $this->db->join('student', 'student.id = student_loan_accessibility.student_id','left');
        $this->db->where('student_loan_accessibility.status !=', 1);
        if(!empty($this->input->post('status'))){
            $this->db->where('student_loan_accessibility.status', $this->input->post('status'));
        }
        if(!empty($this->input->post('pim'))){
            $this->db->where('batch_no', $this->input->post('pim'));
        }
        if(!empty($this->input->post('student_name'))){
            $this->db->like('student.fullname', trim($this->input->post('student_name')));
        }

       
        $dateFrom = $this->input->post('dateFrom');
        $dateTo = $this->input->post('dateTo');
        if(!empty($dateFrom) || !empty($dateTo)){
            $datetimeFrom = date('Y-m-d 00:00:00',strtotime($dateFrom));
            $datetimeTo = date('Y-m-d 23:59:59',strtotime($dateTo));
            if(empty($dateFrom)){
                $this->db->where("student_loan_accessibility.update_date <= '{$datetimeTo}'");
            } else if(empty($dateTo)){
                $this->db->where("student_loan_accessibility.update_date >= '{$datetimeFrom}'");
            } else {
                $this->db->where("student_loan_accessibility.update_date >= '{$datetimeFrom}'");
                $this->db->where("student_loan_accessibility.update_date <= '{$datetimeTo}'");
            }
        }

        $this->db->limit($limit, $start);
        $this->db->order_by('student_loan_accessibility.update_date', 'desc');

        $query = $this->db->get('student_loan_accessibility');
        return $query->result();
    }

    public function get_new_count_loan()
    {
        $this->db->select('student_loan_accessibility.id');
        $this->db->join('student', 'student.id = student_loan_accessibility.student_id','left');
        $this->db->where('student_loan_accessibility.status !=', 1);

        if(!empty($this->input->post('status'))){
            $this->db->where('student_loan_accessibility.status', $this->input->post('status'));
        }
        if(!empty($this->input->post('pim'))){
            $this->db->where('batch_no', $this->input->post('pim'));
        }
        if(!empty($this->input->post('student_name'))){
            $this->db->like('student.fullname', trim($this->input->post('student_name')));
        }

       
        $dateFrom = $this->input->post('dateFrom');
        $dateTo = $this->input->post('dateTo');
        if(!empty($dateFrom) || !empty($dateTo)){
            $datetimeFrom = date('Y-m-d 00:00:00',strtotime($dateFrom));
            $datetimeTo = date('Y-m-d 23:59:59',strtotime($dateTo));
            if(empty($dateFrom)){
                $this->db->where("student_loan_accessibility.update_date <= '{$datetimeTo}'");
            } else if(empty($dateTo)){
                $this->db->where("student_loan_accessibility.update_date >= '{$datetimeFrom}'");
            } else {
                $this->db->where("student_loan_accessibility.update_date >= '{$datetimeFrom}'");
                $this->db->where("student_loan_accessibility.update_date <= '{$datetimeTo}'");
            }
        }

        $query = $this->db->get('student_loan_accessibility');
        return count($query->result());
    }

    public function get_count_loan()
    {
        $this->db->select('id');
        $this->db->where('student_loan_accessibility.status !=', 1);
        $this->db->group_by('student_id');

        $query = $this->db->get('student_loan_accessibility');
        return count($query->result());
    }

    function getLatestRemark($id){
        $this->db->select('content');
        $this->db->where('student_loan_id', $id);
        $this->db->order_by('created', 'DESC');
        $this->db->limit('1');

        $query = $this->db->get('student_loan_note');
        
        foreach ($query->result() as $row){
            $content = $row->content;
        }
        
        if(!empty($content)){
            return $content;
        }else{
            return false;
        }
    }

     function get_status_student_loan($id)
    {
        $this->db->select('status');
        $this->db->where('id', $id);
        
        $query = $this->db->get('student_loan_accessibility');
        
        foreach ($query->result() as $row){
            $status = $row->status;
        }
        
        if(!empty($status)){
            return $status;
        }else{
            return false;
        }
    }

     function get_bankname_of_banker($id)
    {
        $this->db->select('bank');
        $this->db->where('id', $id);
        
        $query = $this->db->get('student');
        
        foreach ($query->result() as $row){
            $bank = $row->bank;
        }
        
        if(!empty($bank)){
            return $bank;
        }else{
            return false;
        }
    }

    function getNofforBanker($user_id){
        $this->db->select('*');
        $this->db->where('status !=', 0);
        $this->db->where('banker_id', $user_id);
        $this->db->order_by('created', 'desc');
       
        $query = $this->db->get('student_nof');
        return $query->result();
    }

    function getNofforBankerHeader($user_id){
        $this->db->select('*');
        $this->db->where('status', 1);
        $this->db->where('banker_id', $user_id);
        $this->db->order_by('created', 'desc');
        $this->db->limit(5);
       
        $query = $this->db->get('student_nof');
        return $query->result();
    }

    function get_student_list()
    {
        $this->db->order_by('id', 'DESC');
        $query = $this->db->get('student_list');
        return $query;
    }

    function insert_student_list($data)
    {
        $this->db->insert_batch('student_list', $data);
    }
} 
	
	
	

