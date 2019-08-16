<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class banker_model extends MY_Model {

	private $module = 'banker';
    private $token;
	private $table = 'banker';

    function __construct(){
        parent::__construct();
        //$this->lang_code = $this->lang->default_lang();
        $this->load->database();
        $this->primaryKey = 'id';
    }
    
    function getlistLoanforBanker($user_id, $limit, $start){
        $this->db->select('student_loan_accessibility.id as loan_id, 
                            student_loan_accessibility.student_id as student_id, 
                            student.firstname, student.lastname, student.batch_no, student.email, student.phone');
        $this->db->join('student', 'student.id = student_loan_accessibility.student_id','left');
        $this->db->where('student_loan_accessibility.banker_id', $user_id);
        $this->db->where('student_loan_accessibility.status !=', 4);
        $this->db->group_by('student_id');
        $this->db->limit($limit, $start);
        $this->db->order_by('student_loan_accessibility.update_date', 'desc');

        $query = $this->db->get('student_loan_accessibility');
        return $query->result();
    }

    public function get_count_loan($user_id)
    {
        $this->db->select('id');
        $this->db->where('student_loan_accessibility.status !=', 4);
        $this->db->where('student_loan_accessibility.banker_id', $user_id);
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
} 
	
	
	

