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
			'name'               => _x( 'Topics', 'post type general name', 'odi' ),
			'singular_name'      => _x( 'Topic', 'post type singular name', 'odi' ),
			'menu_name'          => _x( 'Topics', 'admin menu', 'odi' ),
			'name_admin_bar'     => _x( 'Topic', 'add new on admin bar', 'odi' ),
			'add_new'            => _x( 'Add new', 'topic', 'odi' ),
			'add_new_item'       => __( 'Add new topic', 'odi' ),
			'new_item'           => __( 'New topic', 'odi' ),
			'edit_item'          => __( 'Edit topic', 'odi' ),
			'view_item'          => __( 'View topic', 'odi' ),
			'all_items'          => __( 'All topics', 'odi' ),
			'search_items'       => __( 'Search topics', 'odi' ),
			'parent_item_colon'  => __( 'Parent topics:', 'odi' ),
			'not_found'          => __( 'No topics found.', 'odi' ),
			'not_found_in_trash' => __( 'No topics found in trash.', 'odi' )
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
