<?php 
	function index(){
		global $ar_hcf;
		if(!empty($ar_hcf)){
			echo('-' . '0' . '2');
			exit();
		}
	}
?>