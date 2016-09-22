<?php

/*
 * Open Development
 * timeline
 */

class Odm_Timeline {

	function __construct() {

		add_action('init', array($this, 'register_post_type'));
		add_action('add_meta_boxes', array($this, 'add_meta_box'));
		add_action('save_post', array($this, 'save_post_data'));

	}

	function register_post_type() {

		$labels = array(
			'name'               => _x( 'Timelines', 'post type general name', 'odm' ),
			'singular_name'      => _x( 'Timeline', 'post type singular name', 'odm' ),
			'menu_name'          => _x( 'Timelines', 'admin menu', 'odm' ),
			'name_admin_bar'     => _x( 'Timeline', 'add new on admin bar', 'odm' ),
			'add_new'            => _x( 'Add new', 'timeline', 'odm' ),
			'add_new_item'       => __( 'Add new timeline', 'odm' ),
			'new_item'           => __( 'New timeline', 'odm' ),
			'edit_item'          => __( 'Edit timeline', 'odm' ),
			'view_item'          => __( 'View timeline', 'odm' ),
			'all_items'          => __( 'All timelines', 'odm' ),
			'search_items'       => __( 'Search timelines', 'odm' ),
			'parent_item_colon'  => __( 'Parent timelines:', 'odm' ),
			'not_found'          => __( 'No timelines found.', 'odm' ),
			'not_found_in_trash' => __( 'No timelines found in trash.', 'odm' )
		);

		$args = array(
			'labels'             => $labels,
			'public'             => true,
			'publicly_queryable' => true,
			'show_ui'            => true,
			'show_in_menu'       => true,
			'menu_icon' 				 => 'dashicons-calendar-alt',
			'query_var'          => true,
			'rewrite'            => array( 'slug' => 'timelines' ),
			'capability_type'    => 'post',
			'has_archive'        => true,
			'hierarchical'       => false,
			'menu_position'      => 5,
			'taxonomies'         => array( 'category', 'post_tag'),
			'supports'           => array( 'title', 'editor', 'author', 'thumbnail', 'excerpt', 'revisions')
		);

		register_post_type( 'timeline', $args );

	}


	function add_meta_box($post_type) {
		$list_post_types = array('profiles', 'topic');
		if(in_array($post_type, $list_post_types)){
			add_meta_box(
					'related-timeline',
					__('Add Related Timeline', 'odm'),
					array($this, 'add_related_timeline_box'),
					$post_type,
					'advanced',
					'high'
			);
		}
	}

	function add_related_timeline_box($post = false){
		$related_timeline_post = get_post_meta($post->ID, '_related_timeline_post', true);
		?>
		<div class="related_timeline">
			<?php $get_timeline_post = get_posts( array(  'post_type' => 'timeline', 'posts_per_page' => -1, 'post_status' => 'publish' ) );
			?>
			<p><label for="related_timeline"><?php _e('Select any Timeline Post to display on the right sidebar of the page.', 'odm' ); ?></label></p>
				 <select name="_related_timeline_post">
					 <option value="">Select a timeline</option>
						<?php
						foreach($get_timeline_post as $timeline_post){
							$timeline_post_title = apply_filters("translate_text", $timeline_post->post_title, odm_language_manager()->get_current_language());
						?>
							<option value="<?php echo $timeline_post->ID ?>" <?php echo ($related_timeline_post == $timeline_post->ID ? ' selected="selected"' : ''); ?>><?php echo $timeline_post_title; ?>
							</option>
						<?php
			 			 }
						?>
				 </select>
		</div>
		<?php
	}


	function save_post_data($post_id) {
		if(isset($_REQUEST['_related_timeline_post'])):
			update_post_meta($post_id, '_related_timeline_post', $_REQUEST['_related_timeline_post']);
		endif;
	}

}//class

$GLOBALS['odm_timeline'] = new Odm_Timeline();
