<?php

class Odm_LiveSearch {

	var $ajax_action = 'live_search';

	function __construct() {
		add_action('wp_enqueue_scripts', array($this, 'enqueue_scripts'));
		add_action('wp_ajax_nopriv_' . $this->ajax_action, array($this, 'query'));
		add_action('wp_ajax_' . $this->ajax_action, array($this, 'query'));
	}

	function enqueue_scripts() {
		wp_enqueue_script('odm-live-search', get_stylesheet_directory_uri() . '/inc/live-search/live-search.js', array('jquery', 'underscore'));
		wp_localize_script('odm-live-search', 'livesearch', array(
			'ajaxurl' => admin_url('admin-ajax.php'),
			'siteurl' => get_site_url(),
			'action' => $this->ajax_action,
			'labels' => array(
				'more' => __('Go to the advanced search', 'odm'),
				'close' => __('Close', 'odm'),
				'no_more' => __('Showing all results', 'odm'),
				'post' => __('News', 'odm'),
				'topic' => __('Topic', 'odm'),
				'map' => __('Map', 'odm')
			)
		));
	}

	function query() {

		$response = array(
			"wp" => content_types_breakdown_for_query($_REQUEST['s'],5),
			"wpckan" => do_shortcode('[wpckan_query_datasets query="'.$_REQUEST['s'].'" limit="10" include_fields_dataset="title" include_fields_resources=""]')
		);

		header('Content-Type: application/json;charset=UTF-8');
		echo json_encode($response);
		exit;

	}

}

$GLOBALS['odm_livesearch'] = new Odm_LiveSearch();
