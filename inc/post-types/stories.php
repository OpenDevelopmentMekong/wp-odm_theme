<?php

/*
 * Open Development
 * Stories content type
 */

class Odm_Story {

	function __construct() {

		add_action('init', array($this, 'register_post_type'));

	}

	function register_post_type() {

		$labels = array(
			'name'               => _x( 'Stories', 'post type general name', 'odm' ),
			'singular_name'      => _x( 'Story', 'post type singular name', 'odm' ),
			'menu_name'          => _x( 'Stories', 'admin menu', 'odm' ),
			'name_admin_bar'     => _x( 'Story', 'add new on admin bar', 'odm' ),
			'add_new'            => _x( 'Add new', 'story', 'odm' ),
			'add_new_item'       => __( 'Add new story', 'odm' ),
			'new_item'           => __( 'New story', 'odm' ),
			'edit_item'          => __( 'Edit story', 'odm' ),
			'view_item'          => __( 'View story', 'odm' ),
			'all_items'          => __( 'All stories', 'odm' ),
			'search_items'       => __( 'Search stories', 'odm' ),
			'parent_item_colon'  => __( 'Parent stories:', 'odm' ),
			'not_found'          => __( 'No stories found.', 'odm' ),
			'not_found_in_trash' => __( 'No stories found in trash.', 'odm' )
		);

		$args = array(
			'labels'             => $labels,
			'public'             => true,
			'publicly_queryable' => true,
			'show_ui'            => true,
			'show_in_menu'       => true,
			'menu_icon' 				 => '',
			'query_var'          => true,
			'rewrite'            => array( 'slug' => 'story' ),
			'capability_type'    => 'post',
			'taxonomies'         => array( 'category', 'post_tag'),
			'has_archive'        => true,
			'hierarchical'       => false,
			'menu_position'      => 5,
			'supports'           => array( 'title', 'editor', 'author', 'thumbnail', 'excerpt')
		);

		register_post_type( 'story', $args );

	}

}

$GLOBALS['odm_story'] = new Odm_Story();
