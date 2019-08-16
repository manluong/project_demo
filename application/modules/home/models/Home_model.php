<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Home_model extends MY_Model {

    private $module = 'home';
    private $token;
    private $table = 'admin_nqt_user_info';

    function __construct(){
        parent::__construct();
        //$this->lang_code = $this->lang->default_lang();
        $this->load->database();
        $this->primaryKey = 'id';
    }
    
    function checkMail($email){
        $this->db->select('email');
        $this->db->where('email', $email);
        $query = $this->db->get('student');
        return count($query->result());
    }
    
    function checkPhoneExist($phone){
        $this->db->select('id');
        $this->db->where('phone', $phone);
        $query = $this->db->get('student');
        return count($query->result());
    }

    function validateStudent($phone){
        $this->db->select('id');
        $this->db->where('mobile_no', $phone);
        $query = $this->db->get('student_list');
        return count($query->result());
    }

    function checkEmailExist($email){
        $this->db->select('id');
        $this->db->where('email', $email);
        $query = $this->db->get('student');
        return count($query->result());
    }

    function checkLogin($phone, $email){
        $this->db->select('password');
        $this->db->where('status', 1);
        if(!empty($phone)){
             $this->db->where('phone', $phone);
        }
        if(!empty($email)){
             $this->db->where('email', $email);
        }
        
        $query = $this->db->get('student');
        
        foreach ($query->result() as $row){
            $pass = $row->password;
        }
        
        if(!empty($pass)){
            return $pass;
        }else{
            return false;
        }
    }
    
    function getUserId($phone, $email){
        $this->db->select('id');
        $this->db->where('status', 1);
        if(!empty($phone)){
             $this->db->where('phone', $phone);
        }
        if(!empty($email)){
             $this->db->where('email', $email);
        }
        
        $query = $this->db->get('student');
        
        foreach ($query->result() as $row){
            $id = $row->id;
        }
        
        if(!empty($id)){
            return $id;
        }else{
            return false;
        }
    }

    function getTimeCreatedAccessToken($access_token){
        $this->db->select('created');
        $this->db->where('value', $access_token);
        
        $query = $this->db->get('otp_access_token');
        
        foreach ($query->result() as $row){
            $created = $row->created;
        }
        
        if(!empty($created)){
            return strtotime($created);
        }else{
            return false;
        }
    }

    function getOTPbyAccessToken($access_token){
        $this->db->select('otp_code');
        $this->db->where('value', $access_token);
        
        $query = $this->db->get('otp_access_token');
        
        foreach ($query->result() as $row){
            $otp_code = $row->otp_code;
        }
        
        if(!empty($otp_code)){
            return $otp_code;
        }else{
            return false;
        }
    }

    function getUserType($id){
        $this->db->select('type');
        $this->db->where('id', $id);
        
        $query = $this->db->get('student');
        
        foreach ($query->result() as $row){
            $type = $row->type;
        }
        
        if(!empty($type)){
            return $type;
        }else{
            return false;
        }
    }
    

    function updateUserProfile(){
        $student_id = $this->input->post('student_id');
        $data = array(
            'status'        =>  1,
            'created'       =>  date('Y-m-d H:i:s'),
            'image'          => '',
            'firstname'     => $this->input->post('firstname'),
            'lastname'      => $this->input->post('lastname'),
            'email'         => $this->input->post('email'),
            'phone'         => $this->input->post('phone'),
            'age'           => $this->input->post('age'),
            'address'       => $this->input->post('address'),
            'password'      => $this->input->post('password')
        );
        $this->db->where('id',  $student_id);
        if($this->db->update('student',$data)){
            return true;
        }
    }

    function updateToken($email, $token){
        $data = array(
            'token_reset_pass' => $token
        );
        $this->db->where('email',  $email);
        if($this->db->update('student',$data)){
            return true;
        }
    }

    function changePasswithToken($token, $pass){
        $data = array(
            'password' => md5($pass)
        );
        $this->db->where('token_reset_pass',  $token);
        if($this->db->update('student',$data)){
            return true;
        }
    }
    
    function createAccessToken($user_id, $password){
        $accesstoken = hash('sha256', md5($password.$user_id));
        $data = array(
            'accesstoken' => $accesstoken
        );
        $this->db->where('id',  $user_id);
        if($this->db->update('student',$data)){
            return $accesstoken;
        }
    }

    function getUserInfo($id){
        $this->db->select('*');
        $this->db->where('status', 1);
        $this->db->where('id', $id);
       
        $query = $this->db->get('student');
        return $query->result()[0];
    }

    function getNationality(){
        $this->db->select('*');
        $this->db->order_by('id', 'asc');
       
        $query = $this->db->get('national');
        return $query->result();
    }

    function getPIM(){
        $this->db->select('*');
        $this->db->order_by('id', 'asc');
       
        $query = $this->db->get('pim');
        return $query->result();
    }

    function successResponse($message, $data){
        header('Content-type: text/javascript');
        echo json_encode(['error'=>$message, 'status'=>'success', 'data'=> $data]);
    }

    function errorResponse($message){
        header('Content-type: text/javascript');
        echo json_encode(['error'=>$message, 'status'=>'fail']);
    }
} 
    
    
    

