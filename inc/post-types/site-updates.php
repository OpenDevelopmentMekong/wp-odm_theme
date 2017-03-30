<?php

/*
 * Open Development
 * Site updates content type
 */

class Odm_Site_Updates {

	function __construct() {

		add_action('init', array($this, 'register_post_type'));

	}

	function register_post_type() {

		$labels = array(
			'name'               => _x( 'Site updates', 'post type general name', 'odi' ),
			'singular_name'      => _x( 'Site update', 'post type singular name', 'odi' ),
			'menu_name'          => _x( 'Site updates', 'admin menu', 'odi' ),
			'name_admin_bar'     => _x( 'Site update', 'add new on admin bar', 'odi' ),
			'add_new'            => _x( 'Add new', 'site update', 'odi' ),
			'add_new_item'       => __( 'Add new site update', 'odi' ),
			'new_item'           => __( 'New site update', 'odi' ),
			'edit_item'          => __( 'Edit site update', 'odi' ),
			'view_item'          => __( 'View site update', 'odi' ),
			'all_items'          => __( 'All site updates', 'odi' ),
			'search_items'       => __( 'Search site updates', 'odi' ),
			'parent_item_colon'  => __( 'Parent site updates:', 'odi' ),
			'not_found'          => __( 'No site updates found.', 'odi' ),
			'not_found_in_trash' => __( 'No site updates found in trash.', 'odi' )
		);

		$args = array(
			'labels'             => $labels,
			'public'             => true,
			'publicly_queryable' => true,
			'show_ui'            => true,
			'show_in_menu'       => true,
			'menu_icon' 				 => '',
			'query_var'          => true,
			'rewrite'            => array( 'slug' => 'updates' ),
			'capability_type'    => 'post',
			'taxonomies'         => array( 'category', 'post_tag'),
			'has_archive'        => true,
			'hierarchical'       => false,
			'menu_position'      => 5,
			'supports'           => array( 'title', 'editor', 'author', 'thumbnail', 'excerpt', 'revisions', 'custom-fields')
		);

		register_post_type( 'site-update', $args );

	}

}

$GLOBALS['odm_site_updates'] = new Odm_Site_Updates();
