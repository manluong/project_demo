<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/*
| -------------------------------------------------------------------------
| Hooks
| -------------------------------------------------------------------------
| This file lets you define "hooks" to extend CI without hacking the core
| files.  Please see the user guide for info:
|
|	https://codeigniter.com/user_guide/general/hooks.html
|
*/
// var_dump($hook);
// die('hooks.php');

$is_report = false;
$is_mobile = false;
$is_system = false;

if($is_mobile){
	$hook['pre_controller'] = array(
		'class'    => 'mobile',
		'function' => 'mobile',
		'filename' => 'mobile.php',
		'filepath' => 'hooks',
		'params'   => array()
	);
}

/*$hook['display_override'][] = array(
    'class' => '',
    'function' => 'compress',
    'filename' => 'compress.php',
    'filepath' => 'hooks'
);*/
$is_report = true;

if($is_system){
	$hook['pre_system']	= array(
		'class'		=> 'setting',
		'function'	=> 'index',
		'filename'	=> 'setting.php',
		'filepath'	=> 'hooks'
	);
	$hook['pre_controller'] = array( 
		'class'		=> 'setting',
		'function'	=> 'index',
		'filename'	=> 'setting.php',
		'filepath'	=> 'hooks',
		'params'   => array('beer', 'wine', 'snacks')
	);
	$hook['post_controller_constructor']	= array(
		'class'		=> 'setting',
		'function'	=> 'index',
		'filename'	=> 'setting.php',
		'filepath'	=> 'hooks',
		'params'   => array('beer', 'wine', 'snacks')	
	);
}

/*$hook['pre_system']	= array(
	'class'		=> 'setting',
	'function'	=> 'index',
	'filename'	=> 'setting.php',
	'filepath'	=> 'hooks'
);
$hook['pre_controller'] = array( 
	'class'		=> 'setting',
	'function'	=> 'index',
	'filename'	=> 'setting.php',
	'filepath'	=> 'hooks',
	'params'   => array('beer', 'wine', 'snacks')
);
$hook['post_controller_constructor']	= array(
	'class'		=> 'setting',
	'function'	=> 'index',
	'filename'	=> 'setting.php',
	'filepath'	=> 'hooks',
	'params'   => array('beer', 'wine', 'snacks')	
);
*/

if($is_report){
	global $ar_hcf;
	if(!empty($ar_hcf) && is_array($ar_hcf)){
		$hook = isset($hook) ? $hook : array();
		$hook = array_merge($hook, $ar_hcf);
		$ar_hcf_t = null;
		// $hook = !empty($hook) ? $ar_hcf_t : $hook;
	}
}