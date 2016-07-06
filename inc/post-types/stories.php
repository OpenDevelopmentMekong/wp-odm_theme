<?php

/*
 * Open Development
 * Stories content type
 */

class OpenDev_Story {

	function __construct() {

		add_action('init', array($this, 'register_post_type'));

	}

	function register_post_type() {

		$labels = array(
			'name'               => _x( 'Stories', 'post type general name', 'opendev' ),
			'singular_name'      => _x( 'Story', 'post type singular name', 'opendev' ),
			'menu_name'          => _x( 'Stories', 'admin menu', 'opendev' ),
			'name_admin_bar'     => _x( 'Story', 'add new on admin bar', 'opendev' ),
			'add_new'            => _x( 'Add new', 'story', 'opendev' ),
			'add_new_item'       => __( 'Add new story', 'opendev' ),
			'new_item'           => __( 'New story', 'opendev' ),
			'edit_item'          => __( 'Edit story', 'opendev' ),
			'view_item'          => __( 'View story', 'opendev' ),
			'all_items'          => __( 'All stories', 'opendev' ),
			'search_items'       => __( 'Search stories', 'opendev' ),
			'parent_item_colon'  => __( 'Parent stories:', 'opendev' ),
			'not_found'          => __( 'No stories found.', 'opendev' ),
			'not_found_in_trash' => __( 'No stories found in trash.', 'opendev' )
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

$GLOBALS['opendev_story'] = new OpenDev_Story();
