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
			'name'               => _x( 'Site updates', 'post type general name', 'odm' ),
			'singular_name'      => _x( 'Site update', 'post type singular name', 'odm' ),
			'menu_name'          => _x( 'Site updates', 'admin menu', 'odm' ),
			'name_admin_bar'     => _x( 'Site update', 'add new on admin bar', 'odm' ),
			'add_new'            => _x( 'Add new', 'site update', 'odm' ),
			'add_new_item'       => __( 'Add new site update', 'odm' ),
			'new_item'           => __( 'New site update', 'odm' ),
			'edit_item'          => __( 'Edit site update', 'odm' ),
			'view_item'          => __( 'View site update', 'odm' ),
			'all_items'          => __( 'All site updates', 'odm' ),
			'search_items'       => __( 'Search site updates', 'odm' ),
			'parent_item_colon'  => __( 'Parent site updates:', 'odm' ),
			'not_found'          => __( 'No site updates found.', 'odm' ),
			'not_found_in_trash' => __( 'No site updates found in trash.', 'odm' )
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
			'supports'           => array( 'title', 'editor', 'author', 'thumbnail', 'excerpt', 'revisions')
		);

		register_post_type( 'site-update', $args );

	}

}

$GLOBALS['odm_site_updates'] = new Odm_Site_Updates();
