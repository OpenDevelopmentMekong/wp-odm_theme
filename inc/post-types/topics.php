<?php

/*
 * Open Development
 * Topic content type
 */

class OpenDev_Topic {

	function __construct() {

		add_action('init', array($this, 'register_post_type'));

	}

	function register_post_type() {

		$labels = array(
			'name'               => _x( 'Topics', 'post type general name', 'opendev' ),
			'singular_name'      => _x( 'Topic', 'post type singular name', 'opendev' ),
			'menu_name'          => _x( 'Topics', 'admin menu', 'opendev' ),
			'name_admin_bar'     => _x( 'Topic', 'add new on admin bar', 'opendev' ),
			'add_new'            => _x( 'Add new', 'topic', 'opendev' ),
			'add_new_item'       => __( 'Add new topic', 'opendev' ),
			'new_item'           => __( 'New topic', 'opendev' ),
			'edit_item'          => __( 'Edit topic', 'opendev' ),
			'view_item'          => __( 'View topic', 'opendev' ),
			'all_items'          => __( 'All topics', 'opendev' ),
			'search_items'       => __( 'Search topics', 'opendev' ),
			'parent_item_colon'  => __( 'Parent topics:', 'opendev' ),
			'not_found'          => __( 'No topics found.', 'opendev' ),
			'not_found_in_trash' => __( 'No topics found in trash.', 'opendev' )
		);

		$args = array(
			'labels'             => $labels,
			'public'             => true,
			'publicly_queryable' => true,
			'show_ui'            => true,
			'show_in_menu'       => true,
			'query_var'          => true,
			'rewrite'            => array( 'slug' => 'topics' ),
			'capability_type'    => 'post',
			'taxonomies'         => array('category', 'post_tag'),
			'has_archive'        => true,
			'hierarchical'       => false,
			//'menu_position'      => 4,
			'supports'           => array( 'title', 'editor', 'author', 'thumbnail', 'excerpt', 'comments' )
		);

		register_post_type( 'topic', $args );

	}

}

$GLOBALS['opendev_topic'] = new OpenDev_Topic();
