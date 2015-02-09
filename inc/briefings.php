<?php

/*
 * Open Development
 * Briefing
 */

class OpenDev_Briefing {

	function __construct() {

		add_action('init', array($this, 'register_post_type'));

	}

	function register_post_type() {

		$labels = array(
			'name'               => _x( 'Briefings', 'post type general name', 'opendev' ),
			'singular_name'      => _x( 'Briefing', 'post type singular name', 'opendev' ),
			'menu_name'          => _x( 'Briefings', 'admin menu', 'opendev' ),
			'name_admin_bar'     => _x( 'Briefing', 'add new on admin bar', 'opendev' ),
			'add_new'            => _x( 'Add new', 'briefing', 'opendev' ),
			'add_new_item'       => __( 'Add new briefing', 'opendev' ),
			'new_item'           => __( 'New briefing', 'opendev' ),
			'edit_item'          => __( 'Edit briefing', 'opendev' ),
			'view_item'          => __( 'View briefing', 'opendev' ),
			'all_items'          => __( 'All briefings', 'opendev' ),
			'search_items'       => __( 'Search briefings', 'opendev' ),
			'parent_item_colon'  => __( 'Parent briefings:', 'opendev' ),
			'not_found'          => __( 'No briefings found.', 'opendev' ),
			'not_found_in_trash' => __( 'No briefings found in trash.', 'opendev' )
		);

		$args = array(
			'labels'             => $labels,
			'public'             => true,
			'publicly_queryable' => true,
			'show_ui'            => true,
			'show_in_menu'       => true,
			'query_var'          => true,
			'rewrite'            => array( 'slug' => 'briefings' ),
			'capability_type'    => 'post',
			'taxonomies'         => array('category', 'post_tag'),
			'has_archive'        => true,
			'hierarchical'       => false,
			'menu_position'      => 4,
			'supports'           => array( 'title', 'editor', 'author', 'thumbnail', 'excerpt', 'comments' )
		);

		register_post_type( 'briefing', $args );

	}

}

$GLOBALS['opendev_briefing'] = new OpenDev_Briefing();