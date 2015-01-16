<?php

/*
 * Open Development
 * site update
 */

class OpenDev_SiteUpdates {

	function __construct() {

		add_action('init', array($this, 'register_post_type'));

	}

	function register_post_type() {

		$labels = array(
			'name'               => _x( 'Site updates', 'post type general name', 'opendev' ),
			'singular_name'      => _x( 'Site update', 'post type singular name', 'opendev' ),
			'menu_name'          => _x( 'Site updates', 'admin menu', 'opendev' ),
			'name_admin_bar'     => _x( 'Site update', 'add new on admin bar', 'opendev' ),
			'add_new'            => _x( 'Add new', 'site update', 'opendev' ),
			'add_new_item'       => __( 'Add new site update', 'opendev' ),
			'new_item'           => __( 'New site update', 'opendev' ),
			'edit_item'          => __( 'Edit site update', 'opendev' ),
			'view_item'          => __( 'View site update', 'opendev' ),
			'all_items'          => __( 'All site updates', 'opendev' ),
			'search_items'       => __( 'Search site updates', 'opendev' ),
			'parent_item_colon'  => __( 'Parent site updates:', 'opendev' ),
			'not_found'          => __( 'No site updates found.', 'opendev' ),
			'not_found_in_trash' => __( 'No site updates found in trash.', 'opendev' )
		);

		$args = array(
			'labels'             => $labels,
			'public'             => true,
			'publicly_queryable' => true,
			'show_ui'            => true,
			'show_in_menu'       => true,
			'query_var'          => true,
			'rewrite'            => array( 'slug' => 'site-update' ),
			'capability_type'    => 'post',
			'has_archive'        => true,
			'hierarchical'       => false,
			'menu_position'      => 4,
			'supports'           => array( 'title', 'editor', 'excerpt' )
		);

		register_post_type( 'site-update', $args );

	}

}

$GLOBALS['opendev_siteupdates'] = new OpenDev_SiteUpdates();