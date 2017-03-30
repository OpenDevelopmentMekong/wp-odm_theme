<?php

/*
 * Open Development
 * timeline
 */

class Odm_Timeline {

	function __construct() {

		add_action('init', array($this, 'register_post_type'));
		add_action('save_post', array($this, 'save_post_data'));

	}

	function register_post_type() {

		$labels = array(
			'name'               => _x( 'Timelines', 'post type general name', 'odi' ),
			'singular_name'      => _x( 'Timeline', 'post type singular name', 'odi' ),
			'menu_name'          => _x( 'Timelines', 'admin menu', 'odi' ),
			'name_admin_bar'     => _x( 'Timeline', 'add new on admin bar', 'odi' ),
			'add_new'            => _x( 'Add new', 'timeline', 'odi' ),
			'add_new_item'       => __( 'Add new timeline', 'odi' ),
			'new_item'           => __( 'New timeline', 'odi' ),
			'edit_item'          => __( 'Edit timeline', 'odi' ),
			'view_item'          => __( 'View timeline', 'odi' ),
			'all_items'          => __( 'All timelines', 'odi' ),
			'search_items'       => __( 'Search timelines', 'odi' ),
			'parent_item_colon'  => __( 'Parent timelines:', 'odi' ),
			'not_found'          => __( 'No timelines found.', 'odi' ),
			'not_found_in_trash' => __( 'No timelines found in trash.', 'odi' )
		);

		$args = array(
			'labels'             => $labels,
			'public'             => true,
			'publicly_queryable' => true,
			'show_ui'            => true,
			'show_in_menu'       => true,
			'menu_icon' 				 => 'dashicons-calendar-alt',
			'query_var'          => true,
			'rewrite'            => array( 'slug' => 'timelines' ),
			'capability_type'    => 'post',
			'has_archive'        => true,
			'hierarchical'       => false,
			'menu_position'      => 5,
			'taxonomies'         => array( 'category', 'post_tag'),
			'supports'           => array( 'title', 'editor', 'author', 'thumbnail', 'excerpt', 'revisions', 'custom-fields')
		);

		register_post_type( 'timeline', $args );

	}

	function save_post_data($post_id) {
	 if(get_post_type($post_id) == 'timeline') {

		if(defined('DOING_AUTOSAVE') && DOING_AUTOSAVE)
		 return;

		// save variables here
	 }
	}

}//class

$GLOBALS['odm_timeline'] = new Odm_Timeline();
