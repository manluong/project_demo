<?php

if ( ! function_exists('get_full_url'))
{
    function get_full_url($path)
    {
        $result = FALSE;
        if ( ! empty($path)) {
            $result = trim(rtrim(PATH_API, '/\\') . '/' . ltrim($path, '/\\'));
        }
        return $result;
    }
}

if ( ! function_exists('post_data_to_api'))
{
    function post_data_to_api($path, $data, $type = NULL, $array_return = FALSE)
    {
        $result = FALSE;
        $_url =  get_full_url($path);
        $_header = array();
        $_data = NULL;
        $_flag = FALSE;
        switch (strtolower($type)) {
            case 'json':
                $_header = array('Content-Type:application/json');
                $_data = @json_encode($data);
                if (json_last_error() === 0) $_flag = TRUE;
                break;
            case 'data':
                $_header = array("Content-Type:multipart/form-data");
                $_data = $data;
                $_flag = TRUE;
                break;
            default: //text/html
                $_header = array("Content-Type:text/html");
                $_data = $data;
                $_flag = TRUE;
                break;
        }
        if ($_flag) {
            $ch = curl_init();
            curl_setopt($ch,CURLOPT_SSL_VERIFYPEER, FALSE);
            curl_setopt($ch, CURLOPT_HTTPHEADER, $_header);
            // curl_setopt($ch, CURLOPT_POST, TRUE);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $_data);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
            curl_setopt($ch, CURLOPT_URL, $_url);
            $response = curl_exec($ch);
			
			// Check for errors and display the error message
			if($errno = curl_errno($ch)) {
				$error_message = curl_strerror($errno);
			}

			$log_arr = array(
				'location' => __FILE__ ,
				'function' => 'post_data_to_api',
				'_url' => $_url,
				'_data' => $_data,
				'response' => $response,
				'error_message' => !empty($error_message) ? $error_message : '',
			);
			debug_log_from_config($log_arr);
			// pr($response,1);
			
            curl_close($ch);
            if($array_return) {
                $json_result = @json_decode($response, 1);
            }else {
                $json_result = @json_decode($response);
            }
            if (json_last_error() === 0) {
                $result = $json_result;
            }
            else {
                $result = FALSE;
            }
        }
        return $result;
    }
}

if ( ! function_exists('get_data_from_api'))
{
    function get_data_from_api($path, $array_return = FALSE)
    {
        $_url =  get_full_url($path);
        $response = @file_get_contents($_url);
        if($array_return) {
            $json_result = @json_decode($response, 1);
        }else {
            $json_result = @json_decode($response);
        }
        if (json_last_error() === 0) {
            return $json_result;
        }
        else {
            return FALSE;
        }
    }
}

if ( ! function_exists('get_result'))
{
    function get_result($data)
    {
        if( ! empty($data->ResultSet)) {
            return $data->ResultSet;
        }
        else {
            return FALSE;
        }
    }
}

if ( ! function_exists('get_result_array'))
{
    function get_result_array($data)
    {
        if( ! empty($data['ResultSet'])) {
            return $data['ResultSet'];
        }
        else {
            return FALSE;
        }
    }
}

if ( ! function_exists('get_real_path'))
{
    function get_real_path($input_name = NULL)
    {
        $path = FALSE;
        if (isset($_FILES[$input_name]) &&
            $_FILES[$input_name]['error'] != UPLOAD_ERR_NO_FILE) {
            if (function_exists('curl_file_create')) {
                $tmp_name = $_FILES[$input_name]['tmp_name'];
                $type = $_FILES[$input_name]['type'];
                $name = $_FILES[$input_name]['name'];
                $path =curl_file_create($tmp_name, $type, $name);
                // $path =curl_file_create($_FILES[$input_name]['tmp_name']);
            }
            else {
                // $path = '@' . $_FILES[$input_name]['tmp_name'];
                $path = '@' . realpath($_FILES[$input_name]['tmp_name']);
            }
        }
        return $path;
    }
}

if ( ! function_exists('make_curl_obj_by_url'))
{
    function make_curl_obj_by_url($url = NULL)
    {
        $result = FALSE;
        // $path_file = str_replace('/', '\\', FCPATH . str_replace(PATH_URL, '', $url));
         // $path_file = str_replace('/', '\\', BASEFOLDER . str_replace(PATH_URL, '', $url));
        $path_file =  BASEFOLDER . str_replace(PATH_URL, '', $url);
        if ( ! is_file ($path_file) ) {
            return FALSE;
        }
        $image_name = basename($path_file);
        $image_info = getimagesize($path_file);
        $tmp_name = $path_file;
        $type = $image_info['mime'];
        $name = $image_name;
        $result =curl_file_create($tmp_name, $type, $name);
        return $result;
    }
}

if ( ! function_exists('return_my_json'))
{
    //convert_response_to_json
    function return_my_json($data)
    {
        $status = isset($data->responseCd) ? $data->responseCd : NULL;
        $message = isset($data->responseMsg) ? $data->responseMsg : NULL;
        return json_encode(array('status' => $status, 'message' => $message));
    }
}

if ( ! function_exists('is_success'))
{
    function is_success($data)
    {
		$result = false;
		if(!empty($data)){
			if(is_array($data)){
				$result = isset($data['responseCd']) && ((int)$data['responseCd'] == 0);
			} else { // Is object
				$result = isset($data->responseCd) && ((int)$data->responseCd == 0);
			}
		}
			
		return $result;
    }
}

if ( ! function_exists('is_empty_data'))
{
    function is_empty_data($data)
    {
        if(isset($data->responseCd) && ((int)$data->responseCd == 7))
            return TRUE;
        else
            return FALSE;
    }
}

if ( ! function_exists('convert_time'))
{
    function convert_time($time, $flag = TRUE)
    {
        $fmt = ($flag) ? 'h:i A' : 'H:i:s';
        return strtotime($time) ? (string)date($fmt, strtotime($time)) : FALSE;
    }
}

if( ! function_exists('split_services'))
{
    function split_services($services)
    {
        $services_odd = array();
        $services_even = array();
        foreach ($services as $key => $service) {
            if ($key%2 === 0) { //Even chan
                $services_even[] = $service;
            }
            else { //index = 0
                $services_odd[] = $service;
            }
        }
        return array('odd' => $services_odd, 'even' => $services_even);
    }
}

if ( ! function_exists('convert_data'))
{
    function convert_data($data, $mapping, $obj_return = FALSE)
    {
        $json_data = @json_encode($data);
        $result = str_replace(array_keys($mapping), array_values($mapping), $json_data);
        if ($obj_return) {
            $result = json_decode($result, FALSE);
        }
        return $result;
    }
}

if ( ! function_exists('retrieve_data'))
{
    function retrieve_data($data, $mapping, $obj_return = FALSE)
    {
        $json_data = @json_encode($data);
        $result = str_replace(array_values($mapping), array_keys($mapping), $json_data);
        if ($obj_return) {
            $result = json_decode($result, FALSE);
        }
        return $result;
    }
}

if ( ! function_exists('set_checked'))
{
    function set_chk($is_check)
    {
        $result = '';
        if($is_check == 1 || $is_check == TRUE) {
            $result = 'checked';
        }
        echo $result;
    }
}

if ( ! function_exists('show_msg_denied'))
{
    function show_msg_denied()
    {
        $result['status'] = STATUS_FAIL;
        $result['title'] = 'Permission denied';
        $result['message'] = 'You do not have permission to action';
        return $result;
    }
}

if ( ! function_exists('return_json'))
{
    function return_json($array)
    {
        echo json_encode($array);
        exit;
    }
}

if ( ! function_exists('get_time_stamp'))
{
    function get_time_stamp($time, $fmt='m/d/Y')
    {
        $dt = DateTime::createFromFormat('m/d/Y', $time);
        return $dt->getTimestamp();
    }
}
if ( ! function_exists('echo_price'))
{
    function echo_price($price)
    {
        return (!empty($price))?'$'.$price:'&nbsp;';
    }
}
if ( ! function_exists('print_price'))
{
    function print_price($price)
    {
        return '$'.abs(floatval($price));
    }
}
if ( ! function_exists('format_date'))
{
    function format_date($date)
    {
        return date('M d Y', $date);
    }
}

if( ! function_exists('get_full_avatar') ){
    /**
     * @param string $avatar
     * @return string full url avatar
     */
    function get_full_avatar( $avatar = null ){
        if( empty($avatar) )
            return PATH_URL."assets/images/user/no_avatar.png";
        
        if (strpos($avatar, 'facebook.com') !== false) 
            return $avatar;
        
        
        if( @getimagesize(PATH_API.$avatar) !== false )
            return PATH_API.$avatar;
        
        return PATH_URL."assets/images/user/no_avatar.png";
    }
}

if( !function_exists( 'resize_image' ) ){
	function resize_image( $full_url = false , $width = 100, $height = 100 ){

		if( $full_url == false ){
			$full_url = PATH_URL."/assets/images/noimage.png";
		}

		return PATH_URL.'timthumb.php?src='.$full_url.'&w='.$width.'&h='.$height;
	}
}

if( !function_exists( 'get_youtube_id' ) ){
	function get_youtube_id($url)
	{
		if (preg_match('%(?:youtube(?:-nocookie)?\.com/(?:[^/]+/.+/|(?:v|e(?:mbed)?)/|.*[?&]v=)|youtu\.be/)([^"&?/ ]{11})%i', $url, $match)) {
			$id = $match[1];
			return ($id);
		}
	}
}

?>