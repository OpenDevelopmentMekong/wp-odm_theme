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
	
	function category_name_to_slug($name){
		$slug = strtolower($name);
		$slug = str_replace(" ","-",$slug);
		return $slug;
	}

	/**
	 * Construct Filter Url
	 *
	 * @return string
	 * @author
	 **/
	function construct_filter_url($current_url, $key, $value) {

	  $url_parts = parse_url($current_url);
	  if (isset($url_parts['query'])) {
	    parse_str($url_parts['query'], $params);
	  } else {
	    $params = [];
	  }

	  $params[$key] = $value;

	  $url_parts['query'] = http_build_query($params);

	  return $url_parts['path'] . '?' . $url_parts['query'];

	}

 ?>
