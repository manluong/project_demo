<?php

$root = str_replace('\\', '/',substr(__FILE__,0,-48));
$newfolder = $root . $_POST["folder"];

function remfolder($dir) {

	if(!file_exists($dir)) return true;

	$cnt = glob($dir . "/" . "*");

	foreach ($cnt as $f) {
	  if(is_file($f)) {
		unlink($f);
	  }

	  if(is_dir($f)) {
		remfolder($f);
	  }
	}

	return rmdir($dir);

}

if(file_exists ($newfolder)) {
	//delete the folder
	remfolder($newfolder);
} else {
	//do nothing
}

echo $_POST["folder"];

?>