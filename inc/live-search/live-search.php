<?php

class OpenDev_LiveSearch {

	var $ajax_action = 'live_search';

	function __construct() {
		add_action('wp_enqueue_scripts', array($this, 'enqueue_scripts'));
		add_action('wp_ajax_nopriv_' . $this->ajax_action, array($this, 'query'));
		add_action('wp_ajax_' . $this->ajax_action, array($this, 'query'));
	}

	function enqueue_scripts() {
		wp_enqueue_script('opendev-live-search', get_stylesheet_directory_uri() . '/inc/live-search/live-search.js', array('jquery', 'underscore'));
		wp_localize_script('opendev-live-search', 'livesearch', array(
			'ajaxurl' => admin_url('admin-ajax.php'),
			'siteurl' => get_site_url(),
			'action' => $this->ajax_action,
			'labels' => array(
				'more' => __('Go to the advanced search', 'opendev'),
				'no_more' => __('Showing all results', 'opendev'),
				'post' => __('Post', 'opendev'),
				'briefing' => __('Briefing', 'opendev'),
				'map' => __('Map', 'opendev')
			)
		));
	}

	function query() {

		if(isset($_REQUEST['s']) && $_REQUEST['s']) {
			$query = new WP_Query(array(
				's' => $_REQUEST['s'],
				'post_type' => array('post', 'briefing', 'map'),
				'posts_per_page' => 7
			));
			$response = array(
				'posts' => array(),
				'found_posts' => $query->found_posts
			);
			if($query->have_posts()) {
				while($query->have_posts()) {
					$query->the_post();
					$response['posts'][] = array(
						'title' => get_the_title(),
						'excerpt' => get_the_excerpt(),
						'post_type' => get_post_type($post->ID),
						'url' => get_permalink()
					);
					wp_reset_postdata();
				}
				wp_reset_query();
			}
		}

		header('Content-Type: application/json;charset=UTF-8');
		echo json_encode($response);
		exit;

	}

}

$GLOBALS['opendev_livesearch'] = new OpenDev_LiveSearch();