<?php

/*
 * Open Development
 * Stories content type
 */

class Odm_Story {

	function __construct() {

		add_action('init', array($this, 'register_post_type'));
    add_action('add_meta_boxes', array($this, 'add_meta_box'));
    add_action('save_post', array($this, 'save_post_data'));

	}

	function register_post_type() {

		$labels = array(
			'name'               => _x( 'Stories', 'post type general name', 'odm' ),
			'singular_name'      => _x( 'Story', 'post type singular name', 'odm' ),
			'menu_name'          => _x( 'Stories', 'admin menu', 'odm' ),
			'name_admin_bar'     => _x( 'Story', 'add new on admin bar', 'odm' ),
			'add_new'            => _x( 'Add new', 'story', 'odm' ),
			'add_new_item'       => __( 'Add new story', 'odm' ),
			'new_item'           => __( 'New story', 'odm' ),
			'edit_item'          => __( 'Edit story', 'odm' ),
			'view_item'          => __( 'View story', 'odm' ),
			'all_items'          => __( 'All stories', 'odm' ),
			'search_items'       => __( 'Search stories', 'odm' ),
			'parent_item_colon'  => __( 'Parent stories:', 'odm' ),
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
			'rewrite'            => array( 'slug' => 'stories' ),
			'capability_type'    => 'post',
			'taxonomies'         => array( 'category', 'post_tag'),
			'has_archive'        => true,
			'hierarchical'       => false,
			'menu_position'      => 5,
			'supports'           => array( 'title', 'editor', 'author', 'thumbnail', 'excerpt', 'revisions', 'custom-fields')
		);

		register_post_type( 'story', $args );

	}

	public function add_meta_box()
  {
    add_meta_box(
     'full_width_middle_content',
     __('Full Width Middle Content', 'odm'),
     array($this, 'full_width_middle_content_box'),
     'story',
     'advanced',
     'high'
    );

  }//metabox

	public function full_width_middle_content_box($post = false)
  {
    $full_width_middle_content = get_post_meta($post->ID, '_full_width_middle_content', true);
    $full_width_middle_content_localization = get_post_meta($post->ID, '_full_width_middle_content_localization', true);
    ?>
    <div id="multiple-site">
      <input type="radio" id="middle_content_en" class="en" name="language_site1" value="en" checked />
      <label for="middle_content_en"><?php _e('ENGLISH', 'odm'); ?></label> &nbsp;
      <?php if (odm_language_manager()->get_the_language_by_site() != "English"): ?>
        <input type="radio" id="middle_content_localization" class="localization" name="language_site1" value="localization" />
        <label for="middle_content_localization"><?php _e(odm_language_manager()->get_the_language_by_site(), 'odm');  ?></label>
      <?php endif; ?>

    </div>

    <div id="middle_content_box">
      <div class="language_settings language-en">
        <table class="form-table middle_content_box">
          <tbody>
            <tr>
            <td><label for="_full_width_middle_content"><?php _e('Full width content (English)', 'odm');
            ?></label>
            </br>
            <textarea name="_full_width_middle_content" style="width:100%;height: 50px;" placeholder=""><?php echo $full_width_middle_content; ?></textarea>
            <p class="description"><?php _e('Any content can add to under the Editor content and sidebar and  full width of website even the iframe.', 'odm');
            ?></p>
            </td>
           </tr>
          </tbody>
        </table>
      </div>
      <?php if (odm_language_manager()->get_the_language_by_site() != "English") { ?>
      <div class="language_settings language-localization">
        <table class="form-table form-table-localization middle_content_box">
          <tbody>
            <tr>
            <td><label for="_full_width_middle_content_localization"><?php _e('Full width content (('.odm_language_manager()->get_the_language_by_site().')', 'odm');
            ?></label>
            </br>
            <textarea name="_full_width_middle_content_localization" style="width:100%;height: 50px;" placeholder=""><?php echo $full_width_middle_content_localization; ?></textarea>
            <p class="description"><?php _e('Any content can add to under the Editor content and sidebar and  full width of website even the iframe.', 'odm');
            ?></p>
            </td>
           </tr>
          </tbody>
        </table>
      </div>
      <?php
      }
      ?>
    </div>
    <?php
  }

	public function save_post_data($post_id)
  {
    global $post;
    if (isset($post->ID) && get_post_type($post->ID) == 'story') {

      if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
        return;
      }

      if (defined('DOING_AJAX') && DOING_AJAX) {
        return;
      }

      if (false !== wp_is_post_revision($post_id)) {
        return;
      }

      if (!current_user_can('edit_post')) {
        return;
      }

      if (isset($_POST['_full_width_middle_content'])) {
        update_post_meta($post_id, '_full_width_middle_content', $_POST['_full_width_middle_content']);
      }

      if (isset($_POST['_full_width_middle_content_localization'])) {
        update_post_meta($post_id, '_full_width_middle_content_localization', $_POST['_full_width_middle_content_localization']);
			}
    }
  }

}

$GLOBALS['odm_story'] = new Odm_Story();
