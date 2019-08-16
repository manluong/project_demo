<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en">
<head>
	<meta http-equiv="Content-Type" content="text/html;charset=UTF-8" />
	<title></title>
</head>
<body>
	<?php
error_reporting(E_ALL);

date_default_timezone_set('Asia/Saigon');

/** PHPExcel */
require_once 'Classes/PHPExcel.php';

// Include ezSQL core
include_once "ez_sql_core.php";

// Include ezSQL database specific component
include_once "ez_sql_mysql.php";

// Initialise database object and establish a connection
// at the same time - db_user / db_password / db_name / db_host
$db = new ezSQL_mysql('root','','2012_afc','localhost');


$file = 'code.xlsx';


$objPHPExcel = PHPExcel_IOFactory::load($file);

$objWorksheet = $objPHPExcel->getActiveSheet();


$dataquest = array();
$i=0;
foreach ($objWorksheet->getRowIterator() as $row) {
	$i++;
	$cellIterator = $row->getCellIterator();
	$cellIterator->setIterateOnlyExistingCells(true); 
  
	$array_data = array();
	foreach ($cellIterator as $cell) {
		if('A' == $cell->getColumn()){
			$array_data['code'] = $cell->getCalculatedValue();
		} else if('B' == $cell->getColumn()){
			$array_data['type'] = $cell->getCalculatedValue();
		} 
	}

	echo "<div>INSERT INTO cli_code (`code`,`type`) VALUES ('".$array_data['code']."','".$array_data['type']."');</div>";
}

?>
</body>
</html>