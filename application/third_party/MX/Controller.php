<?php (defined('BASEPATH')) OR exit('No direct script access allowed');

/** load the CI class for Modular Extensions **/
require dirname(__FILE__).'/Base.php';

/**
 * Modular Extensions - HMVC
 *
 * Adapted from the CodeIgniter Core Classes
 * @link	http://codeigniter.com
 *
 * Description:
 * This library replaces the CodeIgniter Controller class
 * and adds features allowing use of modules and the HMVC design pattern.
 *
 * Install this file as application/third_party/MX/Controller.php
 *
 * @copyright	Copyright (c) 2015 Wiredesignz
 * @version 	5.5
 * 
 * Permission is hereby granted, free of charge, to any person obtaining a copy
 * of this software and associated documentation files (the "Software"), to deal
 * in the Software without restriction, including without limitation the rights
 * to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
 * copies of the Software, and to permit persons to whom the Software is
 * furnished to do so, subject to the following conditions:
 * 
 * The above copyright notice and this permission notice shall be included in
 * all copies or substantial portions of the Software.
 * 
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
 * IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
 * FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
 * AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
 * LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
 * OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN
 * THE SOFTWARE.
 **/
class MX_Controller 
{
	public $autoload = array();
	
	public $admin_module = '';
	// private $table = 'page_item';
	// private $table_lang = 'page_item_lang';
	
	public function __construct() 
	{
		global $current_ip;
		if(empty($current_ip)){
			show_404();
		}
		
		$class = str_replace(CI::$APP->config->item('controller_suffix'), '', get_class($this));
		log_message('debug', $class." MX_Controller Initialized");
		Modules::$registry[strtolower($class)] = $this;	
		
		/* copy a loader instance and initialize */
		$this->load = clone load_class('Loader');
		$this->load->initialize($this);	
		
		/* autoload module items */
		$this->load->_autoloader($this->autoload);
		
		if(!empty($this->admin_module)){
			$this->load->model($this->admin_module.'_model','model');
			$this->load->model('admincp_modules/admincp_modules_model');
			$this->load->model('admincp/admincp_model');
			if($this->uri->segment(1)==ADMINCP){
				if($this->uri->segment(2)!='login'){
					if(!$this->session->userdata('userInfo')){
						header('Location: '.PATH_URL_ADMIN.'login');
						exit;
					}
					
					$this->load->helper('admin_helper');
					
					$get_module = $this->admincp_modules_model->check_modules($this->uri->segment(2));
					$this->session->set_userdata('ID_Module',$get_module[0]->id);
					$this->session->set_userdata('Name_Module',$get_module[0]->name);
					$admin_user = $this->admincp_model->getInfo($this->session->userdata('userInfo')); // TODO => Move to Model
					if(!empty($admin_user)){
						$this->admincp_model->admin_user_set($admin_user[0]);
					}
				}
				$this->template->set_template('admin');
				$this->template->write('title','Admin Control Panel');
			}
		}
	}
	
	public function __get($class) 
	{
		return CI::$APP->$class;
	}
}