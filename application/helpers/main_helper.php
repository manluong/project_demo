<?php

/**************************** BEGIN: EXCEL HELPER ***************************/
if ( ! function_exists('get_excel_object'))
{
	function get_excel_object() {
		/** PHPExcel */
		require_once 'PHPExcel/Classes/PHPExcel.php';
		
		// Create new PHPExcel object
		$objPHPExcel = new PHPExcel();

		return $objPHPExcel;
	}
}

if ( ! function_exists('load_excel_file'))
{
	function load_excel_file($file_path) {
		/** PHPExcel */
		require_once 'PHPExcel/Classes/PHPExcel.php';

		// Include ezSQL core
		include_once "PHPExcel/ez_sql_core.php";

		// Include ezSQL database specific component
		include_once "PHPExcel/ez_sql_mysql.php";

		$objPHPExcel = PHPExcel_IOFactory::load($file_path);
		return $objPHPExcel;
	}
}
if ( ! function_exists('load_excel_file_xml'))
{
	function load_excel_file_xml($file_path) {
		require_once 'PHPExcel/Classes/PHPExcel.php';
		$objReader = PHPExcel_IOFactory::createReader('Excel2007');
		// $objReader->setLoadSheetsOnly(array("HCM"));
		// $objReader = PHPExcel_IOFactory::createReaderForFile($file_path);
		$objReader->setReadDataOnly(true);
		$worksheetNames = $objReader->listWorksheetNames($file_path);
		foreach ($worksheetNames as $sheetName) {
			pr($sheetName);
		}
		$objReader->setLoadSheetsOnly(array($worksheetNames[0]));

		$objPHPExcel = $objReader->load($file_path); // Extremely slow/ Bad performance -> Allowed memory size of xxx bytes exhausted
		return $objPHPExcel;
	}
}
if ( ! function_exists('phpexcel_get_obj_reader'))
{
	function phpexcel_get_obj_reader($for_reading=true) {
		require_once 'PHPExcel/Classes/PHPExcel.php';
		$objReader = PHPExcel_IOFactory::createReader('Excel2007');
		if($for_reading){
			$objReader->setReadDataOnly(true);
		}
		return $objReader;
	}
}

if ( ! function_exists('excel_write_cell'))
{
	function excel_write_cell($sheet,$col,$row,$value) {
		$cell_address = $col.$row;
		$sheet->setCellValue($cell_address, $value);
	}
}

if ( ! function_exists('num2alpha'))
{
	function num2alpha($n)
	{
		for($r = ""; $n >= 0; $n = intval($n / 26) - 1)
			$r = chr($n%26 + 0x41) . $r;
		return $r;
	}
}
if ( ! function_exists('stringFromColumnIndex'))
{
	function stringFromColumnIndex($index)
	{
		require_once 'PHPExcel/Classes/PHPExcel.php';
		return PHPExcel_Cell::stringFromColumnIndex($index);
	}
}

if ( ! function_exists('excel_get_cell_address'))
{
	function excel_get_cell_address($column,$row)
	{
		$address = num2alpha($column).$row;
		return $address;
	}
}

if ( ! function_exists('excel_duplicate_formula'))
{
	function excel_duplicate_formula($start_row,$row,$formula){
		$duplicate_formula = str_replace($start_row,$row,$formula);
		return $duplicate_formula;
	}
}

if ( ! function_exists('excel_format_percentage'))
{
	function excel_format_percentage($objSheet,$column,$row){
		$objSheet->getStyle(excel_get_cell_address($column,$row))->getNumberFormat()->applyFromArray(
			array(
				'code' => PHPExcel_Style_NumberFormat::FORMAT_PERCENTAGE
			)
		);
	}
}

if ( ! function_exists('excel_get_column_index'))
{
	function excel_get_column_index($column_name)
	{
		$index = 0;
		if(!empty($column_name)){
			require_once 'PHPExcel/Classes/PHPExcel.php';
			$index = PHPExcel_Cell::columnIndexFromString($column_name)-1;
		}
		return $index;
	}
}
/**************************** END: EXCEL HELPER ***************************/


/*BEGIN: IMPORT*/
function import_check_column_header($column_name,$checked_header,$rowData){
	$result = false;
	if(!empty($column_name) && !empty($rowData)){
		$col = excel_get_column_index($column_name);
		$column_header = !empty($rowData[$col]) ? SEO(trim($rowData[$col])) : '';
		$checked_header = SEO($checked_header);
		if($column_header == $checked_header){
			$result = true;
		}
	}
	return $result;
}

if ( ! function_exists('pr'))
{
	function pr($item, $exit=false)
	{
		$output = '';
		
		ob_start();
		echo "<pre/>";
		if(is_array($item) || is_object($item)){
			var_dump($item);
		} else {
			echo $item;
		}
		$output = ob_get_contents();
		ob_end_clean();
		
		echo $output;
		
		if($exit){
			exit();
		}
		return $output;
	}
}
/*END: IMPORT*/


function is_localhost() {
    $result = false;
    if(defined('IS_LOCALHOST')){
        $result = IS_LOCALHOST;
    } else {
        $whitelist = array( '127.0.0.1', '::1' );
        if(in_array($_SERVER['REMOTE_ADDR'], $whitelist)){
            $result = true;
        }
    }
    return $result;
}

/*BEGIN: GET RESOURCE URL */
function get_resource_url ($path = null)
{
	$url = PATH_URL . $path;
	$path_full = BASEFOLDER . $path;
	if (function_exists('filemtime')) {
		if (file_exists($path_full)) {
			$url .= '?r=' . filemtime($path_full);
		}
	}
	return $url;
}
/*END: GET RESOURCE URL */

function get_full_path ($path = null)
{
	return trim(rtrim(PATH_URL, '/\\') . '/' . ltrim($path, '/\\'));
}


/* ========================== BEGIN: AWS SEND MAIL ================================ */
if ( ! function_exists('ses_send_mail') ) {
    function ses_send_mail($subject, $body, $recepient_emails, $sender_name = NULL, $sender_email = NULL) {
		$from = 'Noreply';
        $CI = &get_instance();
		$config = Array(
			'protocol' => 'smtp',
			'smtp_host' => 'tls://email-smtp.us-east-1.amazonaws.com',
			'smtp_port' => 465,
			'smtp_user' => 'AKIAIR5YC3MYI23WTSDA',
			'smtp_pass' => 'AmGKkWnmCRC1WtU9OHteMSQaK1//04BWPkDzVwmajPgs',
			'mailtype'  => 'html', 
			'charset'   => 'UTF-8',
			'newline'   => "\r\n"
		);
		$CI->load->library('email', $config);
		$CI->email->set_newline("\r\n");
		// Set to, from, message, etc.
		
		$CI->email->to($recepient_emails);
        $CI->email->from($from);
        $CI->email->subject($subject);
        $CI->email->message($body);
		
		$result = $CI->email->send();
		if ($result == false )
		{
			echo $CI->email->print_debugger();
		}
		else{
			return TRUE;
		}
        /*
		$CI->load->library('aws');
        $aws_ses_config = [
                'http'        => [ 'verify' => false ],
                'credentials' => [ 'key'    => AWS_ACCESS_KEY,
                                   'secret' => AWS_SECRET_KEY, ],
                'version'     => AWS_SDK_VERSION,
                'region'      => AWS_REGION,
            ];
        $sender_name   = ( ! empty ($sender_name) ) ? $sender_name : FLATFORM_NAME;
        $sender_email  = ( ! empty ($sender_email) ) ? $sender_email : NO_REPLY_SENDER;
        $source_name   = "{$sender_name} <{$sender_email}>";
        $arr_recipient = is_array($recepient_emails) ? $recepient_emails : array($recepient_emails);
        $request                                           = array();
        $request ['Source']                                = $source_name;
        // $request ['Destination'] ['ToAddresses']           = $arr_recipient;
        $request ['Destination'] ['BccAddresses']          = $arr_recipient;
        $request ['Message'] ['Subject'] ['Data']          = $subject;
        $request ['Message'] ['Subject'] ['Charset']       = 'UTF-8';
        $request ['Message'] ['Body'] ['Html'] ['Data']    = $body;
        $request ['Message'] ['Body'] ['Html'] ['Charset'] = 'UTF-8';
        // print_r($source_name); exit;
        try {
            $client = Aws\Ses\SesClient::factory($aws_ses_config);
            $result = $client->sendEmail($request);
            return TRUE;
            // for debug
            // $messageId = $result->get('MessageId');
            // echo("Email sent! Message ID: $messageId"."\n");
        } catch (Exception $e) {
        	return $e->getMessage();
            return FALSE;
            // for debug
            // echo("The email was not sent. Error message: ");
            // echo($e->getMessage()."\n");
        }
		*/
    }
}
/* ========================== END: AWS SEND MAIL ================================ */


function generate_random_string($length = 4, $prefix = '', $suffix = '') {
	$chars = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789";
	$res = strval ($prefix);
	for ($i = 0; $i < $length; $i++) {
		$res .= $chars [ mt_rand (0, strlen ($chars) - 1) ];
	}
	$res .= strval ($suffix);
	return $res;
}

function generate_random_number($length = 4, $prefix = '', $suffix = '') {
	$chars = "1234567890";
	$res = strval ($prefix);
	for ($i = 0; $i < $length; $i++) {
		$res .= $chars [ mt_rand (0, strlen ($chars) - 1) ];
	}
	$res .= strval ($suffix);
	return $res;
}

function generate_id_by_bitwise($userId, $feedId = FALSE, $commentId = FALSE, $replyId = FALSE) {
	$time = explode(' ', microtime());
	$miliSecond = $time[1] . substr(round($time[0], 10), 2);
	$lenMiliSecond = strlen($miliSecond);
	$ourEpoch = strtotime('2017-06-01 00:00:00');
	$intervalTimestamp =  abs($miliSecond - str_pad($ourEpoch, $lenMiliSecond, "0"));
	$id = ($intervalTimestamp << 13);
    $id |= ($feedId << 8);
    if($feedId !== FALSE) {
		$id |= (intval($userId) << 3);
    }
	if($commentId !== FALSE) {
    	$id |= (intval($commentId) << 3);
	}
	if($replyId !== FALSE) {
		$id |= (intval($replyId) << 3);
	}
	return $id;
}

function return_json_result($data) {
	echo json_encode($data);
	// $CI = &get_instance();
	// return $CI->output
 //        ->set_content_type('application/json', 'UTF-8')
 //        ->set_output(json_encode($data));
    exit;
}

function encode_paging_id($id, $pageNumber) {
	if(empty($id) || empty($pageNumber)) {
		return FALSE;
	}
	return ($id << 12) | ($pageNumber >> 2);
}

function decode_paging_id($id, $pagingId) {
	if(empty($pagingId)) {
		return FALSE;
	}
	return ($pagingId ^ $id) >> 2;
}
/****************************	BEGIN: PAGINATION HELPER ***************************/
function paginate($ci_instance, $base_url, $total_rows = 100, $current_page = 1, $per_page=10, $action = '', $suffix=''){
    if($ci_instance != null){
    	$ci_instance->load->library('pagination');
    	$config['cur_page'] = $current_page;					
    	$config['base_url'] = $base_url;
    	$config['total_rows'] = $total_rows;
    	$config['per_page'] = $per_page;
    	$config['page_query_string'] = false;
    	$config['uri_segment'] = 100;
    	// $config['next_link'] = '<img src="'.base_url().'images/p_next.gif" align="absmiddle" />';
        $config['next_link'] = '&gt;&gt;';
        $config['next_tag_open'] = '<div class="number_page page_next">';
        $config['next_tag_close'] = '</div>';
        // $config['prev_link'] = '<img src="'.base_url().'images/p_prev.gif" align="absmiddle" />';
        $config['prev_link'] = '&lt;&lt;';
    	$config['prev_tag_open'] = '<div class="number_page page_prev">';
        $config['prev_tag_close'] = '</div>';
		
    	$config['cur_tag_open'] = '<div class="number_page active">';
    	$config['cur_tag_close'] = '</div>';
		
        // $config['full_tag_open'] = '<div class="paginator_number" style="width:auto;">';
        // $config['full_tag_close'] = '<div class="clearBoth"></div></div>';
		
        $config['num_links'] = 4;
        $config['num_tag_open'] = '<div class="number_page">';
        $config['num_tag_close'] = '</div>';
        
        $config['first_link'] = 'Trang đầu';
        $config['first_tag_open'] = '<div class="first_page">';
        $config['first_tag_close'] = '</div>';
        
        $config['last_link'] = 'Trang cuối';
        $config['last_tag_open'] = '<div class="first_page">';
        $config['last_tag_close'] = '</div>';
    	
    	$ci_instance->pagination->initialize($config);	
    	$paginator = $ci_instance->pagination->create_links(false,true);

    	if(!empty($action) && !empty($suffix)){
            if($action == SEARCH_URL_LINK){
				// Fix first link
				$paginator = str_replace('tim-kiem/trang/"','tim-kiem/"',$paginator);
				
				// From: 	http://localhost/autobay/Code/mua-ban-oto/tu-khoa/acura+mdx+2007+2010+ho+chi+minh/tim-kiem/trang/15
				// To: 		http://localhost/autobay/Code/mua-ban-oto/tu-khoa/acura+mdx+2007+2010+ho+chi+minh/trang/15/tim-kiem
				$pattern = '/\/tim-kiem\/trang\/(\d+)/'; // $pattern = '/(.*)\/tim-kiem\/(\d+)/'; // -> Wrong???
                $replacement = '/trang/$1/tim-kiem';
            } else if($action == 'detail'){
                $pattern = '/'.$action.'(\/\d*)(\/\d*)(\/\d*)/';
                $replacement = $action.'$1$2$3?'.$suffix;
            } else if($action == 'chitiet_nv_kinh_doanh'){
                $pattern = '/'.$action.'(\/\d*)(\/\d*)(\/\d*)/';
                $replacement = $action.'$1$2$3?'.$suffix;
            }
			$paginator = preg_replace($pattern, $replacement, $paginator);
    	}
		$data['paginator'] = $paginator;
        $ci_instance->load->vars($data);
    }
}

function paginate_new($ci_instance, $base_url, $total_rows = 100, $current_page = 1, $per_page=10, $action = '', $suffix='', $num_links=4){
    if($ci_instance != null){
		if($per_page == -1){
			$per_page = $total_rows;
		}
    	$ci_instance->load->library('pagination');
		
    	$config['cur_page'] = $current_page;					
    	$config['base_url'] = $base_url;
    	$config['total_rows'] = $total_rows;
    	$config['per_page'] = $per_page;
    	$config['page_query_string'] = false;
    	$config['uri_segment'] = 100;
    	$config['next_link'] = '<img src="'.base_url().'images/p_next.gif" align="absmiddle" />';
        //$config['next_link'] = '';
        $config['next_tag_open'] = '<div class="next_link">';
        $config['next_tag_close'] = '</div>';
        $config['prev_link'] = '<img src="'.base_url().'images/p_prev.gif" align="absmiddle" />';
        //$config['prev_link'] = '';
    	$config['prev_tag_open'] = '<div class="prev_link">';
        $config['prev_tag_close'] = '</div>';
    	$config['cur_tag_open'] = '<div class="green">';
    	$config['cur_tag_close'] = '</div>';
        $config['full_tag_open'] = '<div class="paginator_number" style="width:auto;">';
        $config['full_tag_close'] = '</div>';
        $config['num_links'] = $num_links;
        $config['num_tag_open'] = '<div class="white">';
        $config['num_tag_close'] = '</div>';
        
        $config['first_link'] = '';
        $config['first_tag_open'] = '<div class="first_link">';
        $config['first_tag_close'] = '</div>';
        
        $config['last_link'] = '';
        $config['last_tag_open'] = '<div class="last_link">';
        $config['last_tag_close'] = '</div>';
    	
    	$ci_instance->pagination->initialize($config);
    			
    	$data['paginator'] = $ci_instance->pagination->create_links(false,true);
    	if(!empty($action) && !empty($suffix)){
            if($action == 'search_result'){
                $pattern = '/'.$action.'(\/?)(\d*)/';
                $replacement = $action.'$1$2?'.$suffix;
            } else if($action == 'detail'){
                $pattern = '/'.$action.'(\/\d*)(\/\d*)(\/\d*)/';
                $replacement = $action.'$1$2$3?'.$suffix;
            } else if($action == 'query_format'){
                $pattern = '/'.$suffix.'=(\/)(\d*)/';
                $replacement = $suffix.'=$2';
            }
			$data['paginator'] = preg_replace($pattern, $replacement, $data['paginator']);
    	}
        $ci_instance->load->vars($data);
    }
}
/****************************	END: PAGINATION HELPER ***************************/
