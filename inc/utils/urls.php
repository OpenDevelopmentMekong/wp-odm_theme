<?php

	function remove_querystring_var($url, $key) {
		$url = preg_replace('/(.*)(?|&)' . $key . '=[^&]+?(&)(.*)/i', '$1$2$4', $url . '&');
		$url = substr($url, 0, -1);
		return $url;
	}

	function url_path_exists($path){
		$url = 'http'. (443 == $_SERVER['SERVER_PORT']?'s':'') . "://" . $_SERVER['SERVER_NAME'] . $path;
		$response = wp_remote_head($url);
		$response_code = wp_remote_retrieve_response_message( $response );
		return $response_code == 'OK';
	}

 ?>
