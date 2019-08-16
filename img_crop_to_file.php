<?php
	session_start();
	require 'index_config.php';
	$imgUrl = $_POST['imgUrl'];
	// original sizes
	$imgInitW = $_POST['imgInitW'];
	$imgInitH = $_POST['imgInitH'];
	// resized sizes
	$imgW = $_POST['imgW'];
	$imgH = $_POST['imgH'];
	// offsets
	$imgY1 = $_POST['imgY1'];
	$imgX1 = $_POST['imgX1'];
	// crop box
	$cropW = $_POST['cropW']; 
	$cropH = $_POST['cropH'];
	// rotation angle
	$angle = $_POST['rotation'];
	
	$jpeg_quality = 100;
	
	$output_filename = "assets/images/croppic/cropped/croppedImg_".rand();
	
	$what = getimagesize($imgUrl);
	
	switch(strtolower($what['mime']))
	{
		case 'image/png':
			$img_r = imagecreatefrompng($imgUrl);
			$source_image = imagecreatefrompng($imgUrl);
			$type = '.png';
			break;
		case 'image/jpeg':
			$img_r = imagecreatefromjpeg($imgUrl);
			$source_image = imagecreatefromjpeg($imgUrl);
			error_log("jpg");
			$type = '.jpeg';
			break;
		case 'image/gif':
			$img_r = imagecreatefromgif($imgUrl);
			$source_image = imagecreatefromgif($imgUrl);
			$type = '.gif';
			break;
		default: die('image type not supported');
	}
	
	
	//Check write Access to Directory
	
	if(!is_writable(dirname($output_filename))){
		$response = Array(
			"status" => 'error',
			"message" => 'Can`t write cropped File'
		);	
	}else{
	
		// resize the original image to size of editor
		$resizedImage = imagecreatetruecolor($imgW, $imgH);
		imagecopyresampled($resizedImage, $source_image, 0, 0, 0, 0, $imgW, $imgH, $imgInitW, $imgInitH);
		// rotate the rezized image
		// $rotated_image = imagerotate($resizedImage, -$angle, 0); //make poor quality is there
		$rotated_image = imagerotateEquivalent($resizedImage, -$angle, 0);
		// find new width & height of rotated image
		$rotated_width = imagesx($rotated_image);
		$rotated_height = imagesy($rotated_image);
		// diff between rotated & original sizes
		$dx = $rotated_width - $imgW;
		$dy = $rotated_height - $imgH;
		// crop rotated image to fit into original rezized rectangle
		$cropped_rotated_image = imagecreatetruecolor($imgW, $imgH);
		// imagecolortransparent($cropped_rotated_image, imagecolorallocate($cropped_rotated_image, 0, 0, 0));
		imagecopyresampled($cropped_rotated_image, $rotated_image, 0, 0, $dx / 2, $dy / 2, $imgW, $imgH, $imgW, $imgH);
		// crop image into selected area
		$final_image = imagecreatetruecolor($cropW, $cropH);
		// $final_image = $resizedImage; // this is TEST
		// imagecolortransparent($final_image, imagecolorallocate($final_image, 0, 0, 0));
		imagecopyresampled($final_image, $cropped_rotated_image, 0, 0, $imgX1, $imgY1, $cropW, $cropH, $cropW, $cropH);
		if ($type == '.png') {
			// finally output PNG image no compression
			imagepng($final_image, $output_filename.$type, 0);
		}
		elseif ($type == '.gif') {
			// finally output GIF image
			imagepng($final_image, $output_filename.$type);
		}
		else {
			// finally output JPG image
			imagejpeg($final_image, $output_filename.$type, 100);
		}
		// // finally output image
		// imagejpeg($final_image, $output_filename.$type, $jpeg_quality);
		$response = Array(
			"status" => 'success',
			"url" => PATH_URL.$output_filename.$type
		);
		$_SESSION['filename'] = $output_filename.$type;
	}
	
	print json_encode($response);
	
	
	//fucking awesome function make good image
	function imagerotateEquivalent(&$srcImg, $angle, $bgcolor, $ignore_transparent = 0) 
	{
	    function rotateX($x, $y, $theta){
	        return $x * cos($theta) - $y * sin($theta);
	    }
	    function rotateY($x, $y, $theta){
	        return $x * sin($theta) + $y * cos($theta);
	    }
	    
	    $srcw = imagesx($srcImg);
	    $srch = imagesy($srcImg);
	  
	    if($angle == 0) return $srcImg;
	    
	    // Convert the angle to radians
	    $theta = deg2rad ($angle);

	    
	    // Calculate the width of the destination image.
	    $temp = array (    rotateX(0,     0, 0-$theta),
	                    rotateX($srcw, 0, 0-$theta),
	                    rotateX(0,     $srch, 0-$theta),
	                    rotateX($srcw, $srch, 0-$theta)
	                );
	    $minX = floor(min($temp));
	    $maxX = ceil(max($temp));
	    $width = $maxX - $minX;
	    
	    // Calculate the height of the destination image.
	    $temp = array (    rotateY(0,     0, 0-$theta),
	                    rotateY($srcw, 0, 0-$theta),
	                    rotateY(0,     $srch, 0-$theta),
	                    rotateY($srcw, $srch, 0-$theta)
	                );
	    $minY = floor(min($temp));
	    $maxY = ceil(max($temp));
	    $height = $maxY - $minY;
	    
	    $destimg = imagecreatetruecolor($width, $height);
	    imagefill($destimg, 0, 0, imagecolorallocate($destimg, 0,255, 0));

	    // sets all pixels in the new image
	    for($x=$minX;$x<$maxX;$x++) {
	        for($y=$minY;$y<$maxY;$y++) 
	        {
	            // fetch corresponding pixel from the source image
	            $srcX = round(rotateX($x, $y, $theta));
	            $srcY = round(rotateY($x, $y, $theta));
	            if($srcX >= 0 && $srcX < $srcw && $srcY >= 0 && $srcY < $srch)
	            {
	                $color = imagecolorat($srcImg, $srcX, $srcY );
	            }
	            else
	            {
	                $color = $bgcolor;
	            }
	            imagesetpixel($destimg, $x-$minX, $y-$minY, $color);
	        }
	    }
	    
	    return $destimg;
	}
	
?>