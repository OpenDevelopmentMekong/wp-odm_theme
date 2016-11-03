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
			'name'               => _x( 'Timelines', 'post type general name', 'odm' ),
			'singular_name'      => _x( 'Timeline', 'post type singular name', 'odm' ),
			'menu_name'          => _x( 'Timelines', 'admin menu', 'odm' ),
			'name_admin_bar'     => _x( 'Timeline', 'add new on admin bar', 'odm' ),
			'add_new'            => _x( 'Add new', 'timeline', 'odm' ),
			'add_new_item'       => __( 'Add new timeline', 'odm' ),
			'new_item'           => __( 'New timeline', 'odm' ),
			'edit_item'          => __( 'Edit timeline', 'odm' ),
			'view_item'          => __( 'View timeline', 'odm' ),
			'all_items'          => __( 'All timelines', 'odm' ),
			'search_items'       => __( 'Search timelines', 'odm' ),
			'parent_item_colon'  => __( 'Parent timelines:', 'odm' ),
			'not_found'          => __( 'No timelines found.', 'odm' ),
			'not_found_in_trash' => __( 'No timelines found in trash.', 'odm' )
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
			'supports'           => array( 'title', 'editor', 'author', 'thumbnail', 'excerpt', 'revisions')
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
