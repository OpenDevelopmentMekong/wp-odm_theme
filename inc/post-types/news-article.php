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
			'name'               => _x( 'News', 'post type general name', 'odi' ),
			'singular_name'      => _x( 'News article', 'post type singular name', 'odi' ),
			'menu_name'          => _x( 'News', 'admin menu', 'odi' ),
			'name_admin_bar'     => _x( 'News article', 'add new on admin bar', 'odi' ),
			'add_new'            => _x( 'Add new', 'news article', 'odi' ),
			'add_new_item'       => __( 'Add new news article', 'odi' ),
			'new_item'           => __( 'New news article', 'odi' ),
			'edit_item'          => __( 'Edit news article', 'odi' ),
			'view_item'          => __( 'View news article', 'odi' ),
			'all_items'          => __( 'All news articles', 'odi' ),
			'search_items'       => __( 'Search news articles', 'odi' ),
			'parent_item_colon'  => __( 'Parent news articles:', 'odi' ),
			'not_found'          => __( 'No stories found.', 'odi' ),
			'not_found_in_trash' => __( 'No stories found in trash.', 'odi' )
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
			'supports'           => array( 'title', 'editor', 'author', 'thumbnail', 'excerpt', 'revisions', 'custom-fields')
		);

		register_post_type( 'news-article', $args );

	}

}

$GLOBALS['odm_news-article'] = new Odm_News_Article();
