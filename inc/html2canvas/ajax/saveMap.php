<?php

	// Thanks to Jamund Ferguson
	// http://j-query.blogspot.com/2011/02/save-base64-encoded-canvas-image-to-png.html

	define('SAVEMAP_UPLOAD_DIR', '../savedMaps/');
	$savedMap = $_POST['savedMap'];
	$savedMap = str_replace('data:image/jpg;base64,', '', $savedMap);
	$savedMap = str_replace(' ', '+', $savedMap);
	$data = base64_decode($savedMap);
	$file = SAVEMAP_UPLOAD_DIR . uniqid() . '.jpg';
	$view_file = str_replace("..", "", $file);
	$success = file_put_contents($file, $data);
	print $success ? $view_file : 'Unable to save the file.';

?>
