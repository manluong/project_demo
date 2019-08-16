<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Student_model extends MY_Model {

	private $module = 'student';
    private $token;
	private $table = 'student';

    function __construct(){
        parent::__construct();
        //$this->lang_code = $this->lang->default_lang();
        $this->load->database();
        $this->primaryKey = 'id';
    }
	
    function getUserInfo($id){
        $this->db->select('*');
        $this->db->where('status', 1);
        $this->db->where('id', $id);
       
        $query = $this->db->get('student');
        return $query->result();
    }

    function getLoanInfo($id){
        $this->db->select('*');
        /*$this->db->where('status', 1);*/
        $this->db->where('id', $id);
        
        $query = $this->db->get('student_loan_accessibility');
        return $query->result();
    }

    function getLoanRemark($id){
        $this->db->select('*');
        $this->db->where('student_loan_id', $id);
        $this->db->order_by('created','asc');
        
        $query = $this->db->get('student_loan_note');
        return $query->result();
    }

    function getLatestLoanRemark($id){
        $this->db->select('*');
        $this->db->where('student_loan_id', $id);
        $this->db->order_by('created','desc');
        
        $query = $this->db->limit(1)->get('student_loan_note');
        return $query->result();
    }

    function getlistLoan($user_id){
        $this->db->select('*');
        $this->db->where('student_id', $user_id);
        $this->db->where('status !=', 5);
        $this->db->order_by('update_date','desc');

        $query = $this->db->get('student_loan_accessibility');
        return $query->result();
    }

    function getLoanSubmited($user_id)
    {
        $this->db->select('banker_id');
        $this->db->where('status', 2);
        $this->db->where('student_id', $user_id);
        $this->db->limit(1);
        
        $query = $this->db->get('student_loan_accessibility');
        
        foreach ($query->result() as $row){
            $banker_id = $row->banker_id;
        }
        
        if(!empty($banker_id)){
            return $banker_id;
        }else{
            return false;
        }
    }

    function getlistLoanSubmited($user_id){
        $this->db->select('*');
        $this->db->where('student_id', $user_id);
        $this->db->where('status !=', 1);
        $this->db->where('status !=', 5);
        $this->db->order_by('update_date', 'desc');
        $this->db->order_by('submit_date', 'desc');
       
        $query = $this->db->get('student_loan_accessibility');
        return $query->result();
    }

    function getListBanker(){
        $this->db->select('id');
        $this->db->where('status', 1);
        $this->db->where('type', 2);
       
        $query = $this->db->get('student');
        return $query->result();
    }

    function getListStudent(){
        $this->db->select('*');
        //$this->db->where('status', 1);
        //$this->db->where('type', 1);
       
        $query = $this->db->get('student');
        return $query->result();
    }

    function getNofforStudent($user_id){
        $this->db->select('*');
        $this->db->where('status !=', 0);
         $this->db->where('student_id', $user_id);
        $this->db->order_by('created', 'desc');
       
        $query = $this->db->get('banker_nof');
        return $query->result();
    }

    function getNofforStudentHeader($user_id){
        $this->db->select('*');
        $this->db->where('status', 1);
        $this->db->where('student_id', $user_id);
        $this->db->order_by('created', 'desc');
        $this->db->limit(5);
       
        $query = $this->db->get('banker_nof');
        return $query->result();
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
    
    function getNationalName($id){
        $this->db->select('national');
        $this->db->where('id', $id);
        
        $query = $this->db->get('national');
        
        foreach ($query->result() as $row){
            $national = $row->national;
        }
        
        if(!empty($national)){
            return $national;
        }else{
            return false;
        }
    }

    function getPimName($id){
        $this->db->select('pim');
        $this->db->where('id', $id);
        
        $query = $this->db->get('pim');
        
        foreach ($query->result() as $row){
            $pim = $row->pim;
        }
        
        if(!empty($pim)){
            return $pim;
        }else{
            return false;
        }
    }

    function getStudentPass($id){
        $this->db->select('password');
        $this->db->where('status', 1);
        $this->db->where('id', $id);
        
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
    
    function getStudentNote($id){
        $this->db->select('student_note');
        $this->db->where('id', $id);
        
        $query = $this->db->get('student_loan_accessibility');
        
        foreach ($query->result() as $row){
            $student_note = $row->student_note;
        }
        
        if(!empty($student_note)){
            return $student_note;
        }else{
            return false;
        }
    }
    function getStudentName($id){
        $this->db->select('firstname');
        $this->db->where('id', $id);
        
        $query = $this->db->get('student');
        
        foreach ($query->result() as $row){
            $firstname = $row->firstname;
        }
        
        if(!empty($firstname)){
            return $firstname;
        }else{
            return false;
        }
    }

    function getLoanDate($id){
        $this->db->select('submit_date');
        $this->db->where('id', $id);
        
        $query = $this->db->get('student_loan_accessibility');
        
        foreach ($query->result() as $row){
            $date = $row->submit_date;
        }
        
        if(!empty($date)){
            return date( 'd-M-y  g:i A',strtotime($date));
        }else{
            return false;
        }
    }

    function getStudentFullName($id){
        $this->db->select('firstname, lastname');
        $this->db->where('id', $id);
        
        $query = $this->db->get('student');
        
        foreach ($query->result() as $row){
            $fullname = $row->firstname.' '.$row->lastname;
        }
        
        if(!empty($fullname)){
            return $fullname;
        }else{
            return false;
        }
    }

    function getStudentAvatar($id){
        $this->db->select('image');
        $this->db->where('id', $id);
        
        $query = $this->db->get('student');
        
        foreach ($query->result() as $row){
            $avatar = $row->image;
        }
        
        if(!empty($avatar)){
            return $avatar;
        }else{
            return false;
        }
    }

    function verifyAccessToken($accesstoken){
        if (!$accesstoken || strlen ($accesstoken) == 0) return false;
        $this->db->select('*');
        $this->db->where('status', 1);
        $this->db->where('accesstoken', $accesstoken);

        $query = $this->db->get('student');
        return count($query->result()) > 0 ? true : false;
    }

    function mobileGetUserInfo($accesstoken){
        $this->db->select('*');
        $this->db->where('status', 1);
        $this->db->where('accesstoken', $accesstoken);
       
        $query = $this->db->get('student');
        return $query->result()[0];
    }

    function mobileGetUserInfoById($user_id){
        $this->db->select('*');
        $this->db->where('status', 1);
        $this->db->where('id', $user_id);
       
        $query = $this->db->get('student');
        return $query->result()[0];
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

    function successResponse($message, $data){
        header('Content-type: text/javascript');
        echo json_encode(['error'=>$message, 'status'=>'success', 'data'=> $data]);
    }

    function successResponseWithPage($message, $page, $data){
        header('Content-type: text/javascript');
        echo json_encode(['error'=>$message, 'status'=>'success', 'page'=> $page, 'data'=> $data]);
    }

    function errorResponse($message){
        header('Content-type: text/javascript');
        echo json_encode(['error'=>$message, 'status'=>'fail']);
    }

    function getNoteInLoanAccessibility($id){
        $this->db->select('*');
        $this->db->where('student_loan_id', $id);
        $query = $this->db->get('student');
        return $query->result();
    }


    function getLoanAccessibilityAtPage($user_id, $page){
        if ($page > 0){
            $this->db->select('*');
            $this->db->where('student_id', $user_id);
            $this->db->where('status !=', 5);
            $this->db->order_by('update_date','desc');
            $query = $this->db->limit(20, ($page-1)* 20)->get('student_loan_accessibility');
            $loans = [];
            foreach ($query->result() as $row){
                $row->type = 'student';
                $row->remarks = $this->getLatestLoanRemark($row->id);
                $row->banker = $this->getUserInfo($row->banker_id);
                array_push($loans,$row);
            }
            return $loans;
        } else {
            return [];
        }   
    }


    function getLoanAccessibilityOfBankerAtPage($user_id, $page, $status, $pim, $time){
        if ($page > 0){
            $this->db->select('student_loan_accessibility.*, student.batch_no as batch_no');
            $this->db->where('banker_id', $user_id);
            if (!empty($status)){
                $this->db->where('student_loan_accessibility.status', $status);
            } else {
                $this->db->where('student_loan_accessibility.status !=', 5);
            }
            if (!empty($pim)){
                $this->db->join('student','student_loan_accessibility.student_id = student.id');
                $this->db->where('student.batch_no', $pim);
            }
            if (!empty($time)){
                $this->db->order_by('update_date', $time);
            } else {
                $this->db->order_by('update_date','desc');
            }
            $query = $this->db->limit(20, ($page-1)* 20)->get('student_loan_accessibility');
            $loans = [];
            foreach ($query->result() as $row){
                $row->type = 'banker';
                $row->remarks = $this->getLatestLoanRemark($row->id);
                $row->student = $this->getUserInfo($row->student_id);
                array_push($loans,$row);
            }
            return $loans;
        } else {
            return [];
        }   
    }

    function countBankerLoanAccessibilityFilter($banker_id, $status, $pim, $time){
        $this->db->select('*');
        $this->db->where('banker_id', $banker_id);
        if (!empty($status)){
            $this->db->where('student_loan_accessibility.status', $status);
        } else {
            $this->db->where('student_loan_accessibility.status !=', 5);
        }
        if (!empty($pim)){
            $this->db->where('student.batch_no', $pim);
            $this->db->join('student','student_loan_accessibility.student_id = student.id');
        }
        if (!empty($time)){
            $this->db->order_by('update_date', $time);
        } else {
            $this->db->order_by('update_date','desc');
        }
        $query = $this->db->get('student_loan_accessibility');
        return $query->num_rows();
    }

    function countBankerLoanAccessibilityTotal($banker_id){
        $this->db->select('*');
        $this->db->where('banker_id', $banker_id);
        $this->db->where('status !=', 5);
        $query = $this->db->get('student_loan_accessibility');
        return $query->num_rows();
    }

    function getStudentNotificationAtPage($user_id, $page){
        if ($page > 0){
            $this->db->select('*');
            $this->db->where('status !=', 0);
             $this->db->where('student_id', $user_id);
            $this->db->order_by('created', 'desc');
        
            $query = $this->db->limit(20, ($page-1)* 20)->get('banker_nof');
            return $query->result();
        } else {
            return [];
        }   
    }

    function getBankerNotificationAtPage($user_id, $page){
        if ($page > 0){
            $this->db->select('student_nof.id, student_nof.status, student_nof.created, student_nof.type, loan_id, student_id, banker_id, student.firstname, student.lastname, student.batch_no, student.created as student_created');
            $this->db->where('student_nof.status !=', 0);
             $this->db->where('banker_id', $user_id);
            $this->db->order_by('student_nof.created', 'desc');
            $this->db->from('student_nof');
        
            $query = $this->db->limit(20, ($page-1)* 20)->join('student','student_nof.student_id = student.id')->get();
            return $query->result();
        } else {
            return [];
        }   
    }

    function getLoanAccessibilityDetail($id){
        if ($id > 0){
            $this->db->select('*');
            $this->db->where('id', $id);
            $this->db->where('status !=', 5);
            $query = $this->db->get('student_loan_accessibility');
            $loans = $query->result();
            $loans[0]->remarks = $this->getLoanRemark($id);
            $loans[0]->student = $this->getUserInfo($loans[0]->student_id);
            if ($loans[0]->banker_id != 0){
                $loans[0]->banker = $this->getUserInfo($loans[0]->banker_id);
            } else {
                $loans[0]->banker = [];
            }
            return $loans;
        } else {
            return [];
        }   
    }

    function mobileGetListBanker(){
        $this->db->select('id, firstname, lastname, email, image, nationality, phone, gender, bank');
        $this->db->where('status', 1);
        $this->db->where('type', 2);
       
        $query = $this->db->get('student');
        return $query->result();
    }

    /**
     
     * type loan_submitted => [New Loan Accessibility , John has submitted a new Loan Accessbility]
     * type loan_updated => [Loan Accessibility updated, John has updated a Loan Accessbility TIQ 11]
     * type student_send_remark => [New remark, John has sent a remark in TIQ 11] for Banker
     * type banker_send_remark => [New remark, Smith has sent a remark in your Loan Accessibility] for Student
     * type loan_verified => [Loan Accessibility verified, Smith has verified your Loan Accessibility] for Student
     */

    function mapNotificationType ($type, $loan_id) {
        $notification = array();
        $loan = $this-> getLoanInfo($loan_id)[0];
        if ($type == 'loan_submitted'){
            $student_fullname = $this->getStudentFullName($loan->student_id);
            $notification['title'] = "New Loan Accessibility";
            $notification['click_action'] = "SHOW_LOAN_REVIEW_ACTION";
            $notification['body'] = $student_fullname." has submitted a new AIP";
        } 
        if ($type == 'loan_updated'){
            $student_fullname = $this->getStudentFullName($loan->student_id);
            $notification['title'] = "Loan Accessibility updated";
            $notification['click_action'] = "SHOW_LOAN_REVIEW_ACTION";
            $notification['body'] = $student_fullname." has updated AIP";
        }
        if ($type == 'student_send_remark'){
            $student_fullname = $this->getStudentFullName($loan->student_id);
            $notification['title'] = "New remark";
            $notification['click_action'] = "SHOW_LOAN_REVIEW_ACTION";
            $notification['body'] = $student_fullname." has sent new remark";
        }
        if ($type == 'banker_send_remark'){
            $banker_fullname = $this->getStudentFullName($loan->banker_id);
            $notification['title'] = "New remark";
            $notification['click_action'] = "SHOW_LOAN_REVIEW_ACTION";
            $notification['body'] = $banker_fullname." has sent new remark";
        }
        if ($type == 'loan_verified'){
            $banker_fullname = $this->getStudentFullName($loan->banker_id);
            $notification['title'] = "Loan Accessibility verified";
            $notification['click_action'] = "SHOW_LOAN_REVIEW_ACTION";
            $notification['body'] = $banker_fullname." has verified your Loan Accessibility";
        }
        return $notification;
    }

    function initNotification($type, $loan_id){
        $notification = array();
        $notification['loan_id'] = $loan_id;
        return $notification;
    }

    function sendNotification($user_id, $type, $loan_id){
        $firebase_token = $this->mobileGetUserInfoById($user_id)->devicetoken;
        // Set POST variables
        $url = 'https://fcm.googleapis.com/fcm/send';

        $headers = array(
            'Authorization: key=' . 'AAAA8fZ5_HY:APA91bGmXSwq-PDHrXj1KUdvWEUfZgOJKnEB67xZy6SuLJnXmcYvUPy-t7pd5zyinEC1A-Ifpx1SSmn4mpjsZOOaG_YgZiB41DDkBMuIs_H-Z4Ksq1c4AqYv3ReCgir-uOSQlCr_JJ4s',
            'Content-Type: application/json'
        );

        $notification = $this->initNotification($type, $loan_id);
        $notificationContent = $this->mapNotificationType($type, $loan_id);
        $fields = array(
            'to' => $firebase_token,
            'content_available' => true,
            'data' => $notification,
            'notification'=> $notificationContent,
        );
        
        // Open connection
        $ch = curl_init();

        // Set the url, number of POST vars, POST data
        curl_setopt($ch, CURLOPT_URL, $url);

        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        // Disabling SSL Certificate support temporarily
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);

        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($fields));

        // Execute post
        $result = curl_exec($ch);
        if($result === FALSE){
            die('Curl failed: ' . curl_error($ch));
        }
        // Close connection
        curl_close($ch);
    }

    function countStudentNotification($user_id){
        $this->db->select('*');
        $this->db->where('status', 1);
        $this->db->where('student_id', $user_id);
    
        $query = $this->db->get('banker_nof');
        return $query->num_rows();
    }

    function countBankerNotification($banker_id){
        $this->db->select('*');
        $this->db->where('status', 1);
        $this->db->where('banker_id', $banker_id);
    
        $query = $this->db->get('student_nof');
        return $query->num_rows();
    }

    function deleteBankerNotification($ids){
        $ids_del =explode(',',$ids);
        $this->db->where_in('id', $ids_del);
        $this->db->delete('student_nof');
    }


    function deleteStudentNotification($ids){
        $ids_del =explode(',',$ids);
        $this->db->where_in('id', $ids_del);
        $this->db->delete('banker_nof');
    }

    function logout($accesstoken){
        $user = $this->mobileGetUserInfo($accesstoken);
        if (!empty($user->id)){
            $data = [];
            $data['devicetoken'] = '';
            $this->db->where('id',  $user->id);
            $this->db->update('student',$data);
        }
    }
} 
	
	
	

