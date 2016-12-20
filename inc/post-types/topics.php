<?php

/*
 * Open Development
 * Topic content type
 */

class Odm_Topic {

	function __construct() {

		add_action('init', array($this, 'register_post_type'));

	}

	function register_post_type() {

		$labels = array(
			'name'               => _x( 'Topics', 'post type general name', 'odm' ),
			'singular_name'      => _x( 'Topic', 'post type singular name', 'odm' ),
			'menu_name'          => _x( 'Topics', 'admin menu', 'odm' ),
			'name_admin_bar'     => _x( 'Topic', 'add new on admin bar', 'odm' ),
			'add_new'            => _x( 'Add new', 'topic', 'odm' ),
			'add_new_item'       => __( 'Add new topic', 'odm' ),
			'new_item'           => __( 'New topic', 'odm' ),
			'edit_item'          => __( 'Edit topic', 'odm' ),
			'view_item'          => __( 'View topic', 'odm' ),
			'all_items'          => __( 'All topics', 'odm' ),
			'search_items'       => __( 'Search topics', 'odm' ),
			'parent_item_colon'  => __( 'Parent topics:', 'odm' ),
			'not_found'          => __( 'No topics found.', 'odm' ),
			'not_found_in_trash' => __( 'No topics found in trash.', 'odm' )
		);

		$args = array(
			'labels'             => $labels,
			'public'             => true,
			'publicly_queryable' => true,
			'show_ui'            => true,
			'show_in_menu'       => true,
			'menu_icon' 				 => '',
			'query_var'          => true,
			'rewrite'            => array( 'slug' => 'topics' ),
			'capability_type'    => 'post',
			'taxonomies'         => array( 'category', 'post_tag'),
			'has_archive'        => true,
			'hierarchical'       => false,
			'menu_position'      => 5,
			'supports'           => array( 'title', 'editor', 'author', 'thumbnail', 'excerpt', 'revisions', 'custom-fields')
		);

		register_post_type( 'topic', $args );

	}

}

$GLOBALS['odm_topic'] = new Odm_Topic();
