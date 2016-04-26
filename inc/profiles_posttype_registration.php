<?php

/*
 * Open Development
 * profiles_posttype_registration
 */

class OpenDev_profiles_posttype_registration {
	function __construct() {
		add_action('init', array($this, 'register_post_type'));
  	add_action('add_meta_boxes', array($this, 'add_meta_box'));
		add_action('save_post', array($this, 'save_post_data'));

	}

	function register_post_type() {
		$labels = array(
			'name'               => _x( 'Profiles Page', 'post type general name', 'opendev' ),
			'singular_name'      => _x( 'Profile Page', 'post type singular name', 'opendev' ),
			'menu_name'          => _x( 'Profile Pages', 'admin menu', 'opendev' ),
			'name_admin_bar'     => _x( 'Profile Pages', 'add new on admin bar', 'opendev' ),
			'add_new'            => _x( 'Add new', 'profile page', 'opendev' ),
			'add_new_item'       => __( 'Add new profile page', 'opendev' ),
			'new_item'           => __( 'New profile pages', 'opendev' ),
			'edit_item'          => __( 'Edit profile pages', 'opendev' ),
			'view_item'          => __( 'View profile pages', 'opendev' ),
			'all_items'          => __( 'All profile pages', 'opendev' ),
			'search_items'       => __( 'Search profile pages', 'opendev' ),
			'parent_item_colon'  => __( 'Parent profile pages:', 'opendev' ),
			'not_found'          => __( 'No profile page found.', 'opendev' ),
			'not_found_in_trash' => __( 'No profile page found in trash.', 'opendev' )
		);

		$args = array(
			'labels'             => $labels,
			'public'             => true,
			'publicly_queryable' => true,
			'show_ui'            => true,
			'show_in_menu'       => true,
			//'query_var'          => true,
			//'rewrite'            => array( 'slug' => 'profiles' ),
			'capability_type'    => 'page',
			'has_archive'        => true,
			'hierarchical'       => true,
			//'taxonomies'         => array('category', 'language', 'post_tag'),
			'menu_position'      => 4,
			'supports'           => array( 'title', 'editor', 'page-attributes', 'revisions', 'author', 'thumbnail' )
		);

		register_post_type( 'profiles', $args );
	}

	function add_meta_box(){
		// Profile settings
		add_meta_box(
		 'profiles_resource',
		 __('CKAN​ Dataset Resource ()'.get_the_localization_language_by_website().')', 'opendev'),
		 array($this, 'resource_settings_box'),
		 'profiles',
		 'advanced',
		 'high'
		);
		/*add_meta_box(
		 'attributes-view',
		 __('Dataset Attributes', 'opendev'),
		 array($this, 'attributes_settings_box'),
		 'profiles',
		 'side',
		 'default'
	 );*/

	}//metabox

	function resource_settings_box($post = false) {
		$csv_resource_url = get_post_meta($post->ID, '_csv_resource_url', true);
		$csv_resource_url_localization = get_post_meta($post->ID, '_csv_resource_url_localization', true);
		$tracking_csv_resource_url = get_post_meta($post->ID, '_tracking_csv_resource_url', true);
		$tracking_csv_resource_url_localization = get_post_meta($post->ID, '_tracking_csv_resource_url_localization', true);
	  $filtered_by_column_index = get_post_meta($post->ID, '_filtered_by_column_index', true);
	  $filtered_by_column_index_localization = get_post_meta($post->ID, '_filtered_by_column_index_localization', true); 
		?>
		<div id="multiple-site">
			<input type="radio" id="en" class="en" name="language_site" value="en" checked />
			<label for="en"><?php _e('ENGLISH', 'jeo'); ?></label> &nbsp;
			<input type="radio" id="localization" class="localization" name="language_site" value="localization" />
			<label for="localization"><?php _e(get_the_localization_language_by_website(), 'opendev'); ?></label>
		</div>
		<div id="resource_settings_box">
		  <div class="resource_settings resource-en">
				<table class="form-table  resource_settings_box">
					<tbody>
					 <tr>
		 				<th><label for="_csv_resource_url"><?php _e('CSV Resource URL (English)', 'opendev'); ?></label></th>
						<td>
						 <input id="_csv_resource_url" type="text" placeholder="https://" size="40" name="_csv_resource_url" value="<?php echo $csv_resource_url; ?>" />
						 <p class="description"><?php _e('CSV Resource of dataset on CKAN. Eg. https://data.opendevelopmentmekong.net/dataset/economic-land-concessions/resource/3b817bce-9823-493b-8429-e5233ba3bd87?type=dataset', 'opendev'); ?></p>
						</td>
					 </tr>
					 <tr>
		 				<th><label for="_tracking_csv_resource_url"><?php _e('CSV Tracking URL (English)', 'opendev'); ?></label></th>
						<td>
						 <input id="_tracking_csv_resource_url" type="text" placeholder="https://" size="40" name="_tracking_csv_resource_url" value="<?php echo $tracking_csv_resource_url; ?>" />
						 <p class="description"><?php _e('CSV Resource of tracking dataset on CKAN. Eg. https://data.opendevelopmentmekong.net/dataset/economic-land-concessions/resource/8cc0c651-8131-404e-bbce-7fe6af728f89?type=dataset', 'opendev'); ?></p>
						</td>
					 </tr>
					 <tr>
					  <th><label for="_filtered_by_column_index"><?php _e('Create Select Filter by Column Index (English)', 'opendev'); ?></label></th>
					  <td>
					   <input id="_filtered_by_column_index" type="text" placeholder="15" size="40" name="_filtered_by_column_index" value="<?php echo $filtered_by_column_index; ?>" />
					   <p class="description"><?php _e('Eg. The column index of Data Adjustment of ELC: 15', 'opendev'); ?></p>
					  </td>
					 </tr>
					</tbody>
		 		</table>
				<?php $this->attributes_settings_box("English", $post); ?>
			</div>
<?php if(get_the_localization_language_by_website()){ ?>
		 <div class="resource_settings resource-localization">
			 	<table class="form-table form-table-localization resource_settings_box">
		 			<tbody>
		 			 <tr>
		 				<th><label for="_csv_resource_url_localization"><?php _e('CSV Resource URL ('.get_the_localization_language_by_website().')', 'opendev'); ?></label></th>
		 				<td>
		 				 <input id="_csv_resource_url_localization" type="text" placeholder="https://" size="40" name="_csv_resource_url_localization" value="<?php echo $csv_resource_url_localization; ?>" />
		 				  <p class="description"><?php _e('CSV Resource of dataset on CKAN. Eg. https://data.opendevelopmentmekong.net/dataset/economic-land-concessions/resource/3b817bce-9823-493b-8429-e5233ba3bd87?type=dataset', 'opendev'); ?></p>
		 				</td>
		 			 </tr>
					 <tr>
		 				<th><label for="_tracking_csv_resource_url_localization"><?php _e('CSV Tracking URL ('.get_the_localization_language_by_website().')', 'opendev'); ?></label></th>
		 				<td>
		 				 <input id="_tracking_csv_resource_url_localization" type="text" placeholder="https://" size="40" name="_tracking_csv_resource_url_localization" value="<?php echo $tracking_csv_resource_url_localization; ?>" />
		 				  <p class="description"><?php _e('CSV Resource of tracking dataset on CKAN. Eg. https://data.opendevelopmentmekong.net/dataset/economic-land-concessions/resource/8cc0c651-8131-404e-bbce-7fe6af728f89?type=dataset', 'opendev'); ?></p>
		 				</td>
		 			 </tr>
					 <tr>
					  <th><label for="_filtered_by_column_index_localization"><?php _e('Create Select Filter by Column Index ('.get_the_localization_language_by_website().')', 'opendev'); ?></label></th>
					  <td>
					   <input id="_filtered_by_column_index_localization" type="text" placeholder="15" size="40" name="_filtered_by_column_index_localization" value="<?php echo $filtered_by_column_index_localization; ?>" />
					   <p class="description"><?php _e('Eg. The column index of Data Adjustment of ELC: 15', 'opendev'); ?></p>
					  </td>
					 </tr>
					</tbody>
		 		</table>
				<?php $this->attributes_settings_box(get_the_localization_language_by_website(), $post); ?>
		 </div>
 <?php } ?>
		</div>
		<script type="text/javascript">
		 jQuery(document).ready(function($) {
			var $container = $('#multiple-site');
			var $languageSelection = $container.find('input[name="language_site"]');
			var $forms = $('.resource_settings');
			var showForms = function() {
				  $forms.hide();
					var selected = $languageSelection.filter(':checked').val();
					$('.resource-' + selected).show();
			}
			$languageSelection.on('change', function() {
			 showForms();
			});
			showForms();
     });
    </script>
		<?php
	}
	function attributes_settings_box($lang="English",$post = false) {
			$attributes = get_post_meta($post->ID, '_attributes_csv_resource', true);
			$attributes_localization = get_post_meta($post->ID, '_attributes_csv_resource_localization', true);
			$attributes_tracking = get_post_meta($post->ID, '_attributes_csv_resource_tracking', true);
			$attributes_tracking_localization = get_post_meta($post->ID, '_attributes_csv_resource_tracking_localization', true);
			 ?>
			 <?php if($lang!="English"){ ?>
							 <h4><?php _e('The attributes of Resource Dataset that would like to display ('.$lang.')', 'opendev'); ?></h4>
							 <textarea name="_attributes_csv_resource_localization" style="width:100%;height: 200px;"><?php echo $attributes_localization; ?></textarea>

							 <h4><?php _e('The attributes of Tracking Resource that would like to display ('.$lang.')', 'opendev'); ?></h4>
							 <textarea name="_attributes_csv_resource_tracking_localization" style="width:100%;height: 200px;"><?php echo $attributes_tracking_localization; ?></textarea>
			 <?php
						 }else {
							 ?>
							 <h4><?php _e('Add attributes of Dataset ('.$lang.')', 'opendev'); ?></h4>
							 <textarea name="_attributes_csv_resource" style="width:100%;height: 200px;"><?php echo $attributes; ?></textarea>

							 <h4><?php _e('The attributes of Tracking Resource that would like to display ('.$lang.')', 'opendev'); ?></h4>
							 <textarea name="_attributes_csv_resource_tracking" style="width:100%;height: 200px;"><?php echo $attributes_tracking; ?></textarea>
							 <?php
						 }
 	}//attributes

	function save_post_data($post_id) {
		global $post;
				if(get_post_type($post->ID) == 'profiles') {
					   // Skip auto save
					   if(defined('DOING_AUTOSAVE') && DOING_AUTOSAVE)
								return;

						 if (defined('DOING_AJAX') && DOING_AJAX)
								return;

						 if (false !== wp_is_post_revision($post_id))
								return;

						 // if our current user can't edit this post, bail
					 	 if( !current_user_can( 'edit_post' ) ) return;
						 /*
							* Resource Settting
							*/
						 if(isset($_POST['_csv_resource_url']))
							update_post_meta($post_id, '_csv_resource_url', $_POST['_csv_resource_url']);

						 if(isset($_POST['_csv_resource_url_localization']))
							update_post_meta($post_id, '_csv_resource_url_localization', $_POST['_csv_resource_url_localization']);

						 if(isset($_POST['_tracking_csv_resource_url']))
							update_post_meta($post_id, '_tracking_csv_resource_url', $_POST['_tracking_csv_resource_url']);

						 if(isset($_POST['_tracking_csv_resource_url_localization']))
							update_post_meta($post_id, '_tracking_csv_resource_url_localization', $_POST['_tracking_csv_resource_url_localization']);

						 if(isset($_POST['_filtered_by_column_index']))
							update_post_meta($post_id, '_filtered_by_column_index', $_POST['_filtered_by_column_index']);

						 if(isset($_POST['_filtered_by_column_index_localization']))
							update_post_meta($post_id, '_filtered_by_column_index_localization', $_POST['_filtered_by_column_index_localization']);

						 /*
							* Attributes Settting
							*/
						 if(isset($_POST['_attributes_csv_resource']))
							update_post_meta($post_id, '_attributes_csv_resource', $_POST['_attributes_csv_resource']);

						 if(isset($_POST['_attributes_csv_resource_localization']))
							update_post_meta($post_id, '_attributes_csv_resource_localization', $_POST['_attributes_csv_resource_localization']);

						 if(isset($_POST['_attributes_csv_resource_tracking']))
							update_post_meta($post_id, '_attributes_csv_resource_tracking', $_POST['_attributes_csv_resource_tracking']);

						 if(isset($_POST['_attributes_csv_resource_tracking_localization']))
							update_post_meta($post_id, '_attributes_csv_resource_tracking_localization', $_POST['_attributes_csv_resource_tracking_localization']);

					}// if post type: 'profiles'

	}//end function
}

$GLOBALS['opendev_profiles_posttype_registration'] = new OpenDev_profiles_posttype_registration();
