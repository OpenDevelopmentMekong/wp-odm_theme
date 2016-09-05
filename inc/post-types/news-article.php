<?php

/*
 * Open Development
 * News article content type
 */

class Odm_News_Article {

	function __construct() {

		add_action('init', array($this, 'register_post_type'));

	}

	function register_post_type() {

		$labels = array(
			'name'               => _x( 'News', 'post type general name', 'odm' ),
			'singular_name'      => _x( 'News article', 'post type singular name', 'odm' ),
			'menu_name'          => _x( 'News', 'admin menu', 'odm' ),
			'name_admin_bar'     => _x( 'News article', 'add new on admin bar', 'odm' ),
			'add_new'            => _x( 'Add new', 'news article', 'odm' ),
			'add_new_item'       => __( 'Add new news article', 'odm' ),
			'new_item'           => __( 'New news article', 'odm' ),
			'edit_item'          => __( 'Edit news article', 'odm' ),
			'view_item'          => __( 'View news article', 'odm' ),
			'all_items'          => __( 'All news articles', 'odm' ),
			'search_items'       => __( 'Search news articles', 'odm' ),
			'parent_item_colon'  => __( 'Parent news articles:', 'odm' ),
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
			'rewrite'            => array( 'slug' => 'news' ),
			'capability_type'    => 'post',
			'taxonomies'         => array( 'category', 'post_tag'),
			'has_archive'        => true,
			'hierarchical'       => false,
			'menu_position'      => 5,
			'supports'           => array( 'title', 'editor', 'author', 'thumbnail', 'excerpt', 'revisions')
		);

		register_post_type( 'news-article', $args );

	}

}

$GLOBALS['odm_news-article'] = new Odm_News_Article();
