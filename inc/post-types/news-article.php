<?php

/*
 * Open Development
 * News article content type
 */

class OpenDev_News_Article {

	function __construct() {

		add_action('init', array($this, 'register_post_type'));

	}

	function register_post_type() {

		$labels = array(
			'name'               => _x( 'News', 'post type general name', 'opendev' ),
			'singular_name'      => _x( 'News article', 'post type singular name', 'opendev' ),
			'menu_name'          => _x( 'News', 'admin menu', 'opendev' ),
			'name_admin_bar'     => _x( 'News article', 'add new on admin bar', 'opendev' ),
			'add_new'            => _x( 'Add new', 'news article', 'opendev' ),
			'add_new_item'       => __( 'Add new news article', 'opendev' ),
			'new_item'           => __( 'New news article', 'opendev' ),
			'edit_item'          => __( 'Edit news article', 'opendev' ),
			'view_item'          => __( 'View news article', 'opendev' ),
			'all_items'          => __( 'All news articles', 'opendev' ),
			'search_items'       => __( 'Search news articles', 'opendev' ),
			'parent_item_colon'  => __( 'Parent news articles:', 'opendev' ),
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
			'rewrite'            => array( 'slug' => 'news' ),
			'capability_type'    => 'post',
			'taxonomies'         => array('category', 'post_tag'),
			'has_archive'        => true,
			'hierarchical'       => false,
			'menu_position'      => 5,
			'supports'           => array( 'title', 'editor', 'author', 'thumbnail', 'excerpt')
		);

		register_post_type( 'news-article', $args );

	}

}

$GLOBALS['opendev_news-article'] = new OpenDev_News_Article();
