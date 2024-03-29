<?php
/*
 * Open Development
 * Theme options
 */

class Odm_Options
{
    public function __construct()
    {
        add_action('admin_menu', array($this, 'admin_menu'));
        add_action('admin_init', array($this, 'init_theme_settings'));
        add_action('admin_enqueue_scripts', array($this, 'enqueue_scripts'));
    }

    public function enqueue_scripts()
    {
        if ( get_current_screen()->id == 'appearance_page_odm_options' ) {
            wp_enqueue_media();
            wp_enqueue_script( 'odm-theme-options', get_stylesheet_directory_uri().'/inc/theme-options.js' );
        }
    }

    public function admin_menu()
    {
        add_theme_page( __( 'Open Development Style', 'odm' ), __( 'Open Development', 'odm' ), 'edit_theme_options', 'odm_options', array( $this, 'admin_page' ) );
    }

    public function admin_page()
    {
        $this->options = get_option( 'odm_options' );
        ?>

        <div class="wrap">
        	<?php //screen_icon(); ?>
        	<h2><?php _e( 'Open Development Theme Options', 'odm' );?></h2>
        	<form method="post" action="options.php">
				<?php
					settings_fields('odm_options_group');
					do_settings_sections('odm_options');
					submit_button();
				?>
         	</form>
    	</div>
        <?php
    }

    public function init_theme_settings()
    {
        mapbox_metabox_init();

        add_settings_section(
        	'odm_style_section',
         	__('Style', 'odm'),
         	'',
         	'odm_options'
        );

        add_settings_section(
         'odm_links_section',
         __('Links', 'odm'),
         '',
         'odm_options'
        );

        add_settings_section(
          'odm_header_section',
          __('Header', 'odm'),
          '',
          'odm_options'
         );

        add_settings_section(
         'odm_category_page_section',
         __('Category page', 'odm'),
         '',
         'odm_options'
        );

        add_settings_section(
         'odm_single_page_section',
         __('Single and archive pages', 'odm'),
         '',
         'odm_options'
        );

        add_settings_section(
         'odm_interactive_map_section',
         __('Interactive map', 'odm'),
         '',
         'odm_options'
        );

        add_settings_field(
         'odm_style',
         __('Choose a style', 'odm'),
         array($this, 'style_field'),
         'odm_options',
         'odm_style_section'
        );

        add_settings_field(
          'odm_notice_message',
          __('Notice Message appear above the slider', 'odm'),
          array($this, 'notice_message_field'),
          'odm_options',
          'odm_style_section'
        );

        add_settings_field(
         'odm_frontpage_slider_id',
         __('Frontpage slider id', 'odm'),
         array($this, 'frontpage_slider_id_field'),
         'odm_options',
         'odm_style_section'
        );

        add_settings_field(
         'odm_facebook',
         __('Facebook url', 'odm'),
         array($this, 'facebook_field'),
         'odm_options',
         'odm_links_section'
        );

        add_settings_field(
         'odm_twitter',
         __('Twitter url', 'odm'),
         array($this, 'twitter_field'),
         'odm_options',
         'odm_links_section'
        );

        add_settings_field(
            'odm_linkedin',
            __('LinkedIn url', 'odm'),
            array($this, 'linkedin_field'),
            'odm_options',
            'odm_links_section'
        );

        add_settings_field(
            'odm_youtube',
            __('YouTube URL', 'odm'),
            array($this, 'youtube_url_field'),
            'odm_options',
            'odm_links_section'
        );

        add_settings_field(
          'odm_print_icon',
          __('Display print icon', 'odm'),
          array($this, 'print_icon_field'),
          'odm_options',
          'odm_header_section'
        );

        add_settings_field(
         'odm_legal_disclaimer',
         __('Legal disclaimer', 'odm'),
         array($this, 'legal_disclaimer_field'),
         'odm_options',
         'odm_links_section'
        );

        add_settings_field(
         'odm_contact',
         __('Contact page', 'odm'),
         array($this, 'contact_page_field'),
         'odm_options',
         'odm_links_section'
        );

        add_settings_field(
         'odm_data_page',
         __('Data page', 'odm'),
         array($this, 'data_page_field'),
         'odm_options',
         'odm_links_section'
        );

        add_settings_field(
         'odm_category_page',
         __('Category page (WP)', 'odm'),
         array($this, 'category_page_field'),
         'odm_options',
         'odm_category_page_section'
        );

        add_settings_field(
         'odm_category_page_ckan',
         __('Category page (CKAN)', 'odm'),
         array($this, 'category_page_ckan_field'),
         'odm_options',
         'odm_category_page_section'
        );

        add_settings_field(
         'odm_category_page_template',
         __('Category page template', 'odm'),
         array($this, 'category_page_template_field'),
         'odm_options',
         'odm_category_page_section'
        );

        add_settings_field(
         'odm_single_page_date',
         __('Date shown on single and archive page templates', 'odm'),
         array($this, 'single_page_date_field'),
         'odm_options',
         'odm_single_page_section'
        );

        add_settings_field(
            'odm_single_topic_page_date',
            __('Date shown on single topic page template', 'odm'),
            array($this, 'single_topic_page_date_field'),
            'odm_options',
            'odm_single_page_section'
        );

        add_settings_field(
         'odm_interactive_map',
         __('Base map settings', 'odm'),
         array($this, 'interactive_map_field'),
         'odm_options',
         'odm_interactive_map_section'
        );

        register_setting('odm_options_group', 'odm_options');
    }

    public function style_field()
    {
      ?>
      <select id="odm_style" name="odm_options[style]">
        <?php foreach (odm_country_manager()->get_country_themes() as $label => $theme): ?>
          <option <?php if (isset($this->options['style']) && $this->options['style'] == $theme): echo 'selected'; endif;?> value="<?php echo $theme;?>"><?php _e($label);?></option>
        <?php endforeach ?>
      </select>
      <?php
    }

    function notice_message_field() {
      $notice_message = $this->options['notice_message'];
      if (isset($this->options['enable_notification']) && $this->options['enable_notification']):
        if(!$notice_message):
          echo '<i style="color:red">The notification message can not empty, please add notification message.</i><br/>';
        endif;
      endif;
      ?>
      <textarea id="odm_notice_message" name="odm_options[notice_message]" rows="5" placeholder="<?php _e('Notification messages','odm'); ?>" onfocus="this.placeholder=''" onblur="this.placeholder='<?php _e('Notification messages','odm');?>'" rows="1" cols="68"><?php echo $notice_message; ?></textarea></br>
      <p>
       <input class="odm_enable-notification" id="odm_enable_notification" type="checkbox" name="odm_options[enable_notification]" <?php if (isset($this->options['enable_notification']) && $this->options['enable_notification']) echo 'checked'; ?> />
       <label for="odm_enable_notification"><?php _e('Enable notification', 'odm'); ?>
         <?php _e("(will appear floating above the slider of the site)", 'odm'); ?>
       </label>
      </p>
      <?php
   }

    public function frontpage_slider_id_field()
    {
        $frontpage_slider_id = $this->options['frontpage_slider_id'];?>
        <input id="odm_frontpage_slider_id" name="odm_options[frontpage_slider_id]" type="text" placeholder="<?php _e('281321');
              ?>" onfocus="this.placeholder=''" onblur="this.placeholder='<?php _e('281321');
              ?>'" value="<?php echo $frontpage_slider_id;
              ?>" size="70" /><br/>
        <i><?php _e("(This id can be found on the Featured Area plugin slider's settings.)", 'odm');
              ?></i>
  <?php

    }

    public function facebook_field()
    {
        $facebook = $this->options['facebook_url'];
        ?>
  <input id="odm_facebook_url" name="odm_options[facebook_url]" type="text" value="<?php echo $facebook;
        ?>" size="70" />
  <?php

    }

    public function twitter_field()
    {
        $twitter = $this->options['twitter_url']; ?>
        <input id="odm_twitter_url" name="odm_options[twitter_url]" type="text" value="<?php echo $twitter;?>" size="70" />
  	<?php
    }

    /**
     * Create a text input for LinkedIn URL of the Orgnaization
     *
     * @return void
     */
    public function linkedin_field()
    {
        $linkedin = $this->options['linkedin_url']; ?>
        <input id="odm_linkedin_url" name="odm_options[linkedin_url]" type="text" value="<?php echo $linkedin;?>" size="70" />
  	<?php
    }

	function youtube_url_field() {
        $youtube = $this->options['youtube_url']; ?>
        <input id="odm_youtube_url" name="odm_options[youtube_url]" type="text" value="<?php echo $youtube;?>" size="70" />
	<?php
	}

    function print_icon_field() {
      ?>
      <p>
       <input class="odm_print_icon" id="odm_print_icon" type="checkbox" name="odm_options[print_icon]" <?php if (isset($this->options['print_icon']) && $this->options['print_icon']) echo 'checked'; ?> />
       <br>
       <label><em>(Display the <strong>Print icon</strong> for print the webpage in the Social Navigation section of the website header.)</em></label>
      </p>
      <?php
    }

    public function category_page_field()
    {
        $selected_post_type = isset($this->options['category_page'])? $this->options['category_page']: "news-article, announcement ,topic ,profiles";?>
        <input id="odm_category_page" name="odm_options[category_page]" type="text" placeholder="<?php _e('news-article,announcement,topic,profiles');
              ?>" onfocus="this.placeholder=''" onblur="this.placeholder='<?php _e('news-article,announcement,topic,profiles');
              ?>'" value="<?php echo $selected_post_type;?>" size="70" /><br/>
              <i><?php _e("(Add the WP post type name that would like to show on the category page. (separated by comma))", 'odm');
              ?></i>
  <?php
    }

		public function category_page_ckan_field()
    {
        $selected_post_type = isset($this->options['category_page_ckan'])? $this->options['category_page_ckan']: "dataset, library_record, laws_record, agreement";?>
        <input id="odm_category_page_ckan" name="odm_options[category_page_ckan]" type="text" placeholder="<?php _e('dataset, library_record, laws_record, agreement');
              ?>" onfocus="this.placeholder=''" onblur="this.placeholder='<?php _e('dataset, library_record, laws_record, agreement');
              ?>'" value="<?php echo $selected_post_type;?>" size="70" /><br/>
              <i><?php _e("(Add the ckan record's type name that would like to show on the category page. (separated by comma))", 'odm');
              ?></i>
  <?php
    }

    public function category_page_template_field()
    {
        $selected_template = isset($this->options['category_page_template'])? $this->options['category_page_template'] : "default";?>
        <select id="odm_category_page_template" name="odm_options[category_page_template]" type="text" />
          <option <?php if (isset($this->options['category_page_template']) && $this->options['category_page_template'] == "default"): echo 'selected'; endif;?> value="default">2.0</option>
          <option <?php if (isset($this->options['category_page_template']) && $this->options['category_page_template'] == "latest"): echo 'selected'; endif;?> value="latest">2.2</option>
        </select>
  <?php
    }

    public function single_page_date_field()
    {
    ?>
        <select id="odm_single_page_date" name="odm_options[single_page_date]" type="text" />
          <option <?php if (isset($this->options['single_page_date']) && $this->options['single_page_date'] == "metadata_created"): echo 'selected'; endif;?> value="metadata_created"><?php _e('Creation date','odm'); ?></option>
          <option <?php if (isset($this->options['single_page_date']) && $this->options['single_page_date'] == "metadata_modified"): echo 'selected'; endif;?> value="metadata_modified"><?php _e('Modification date','odm'); ?></option>
        </select>
    <?php
    }

    public function single_topic_page_date_field()
    {
    ?>
        <select id="odm_single_topic_page_date" name="odm_options[single_topic_page_date]" type="text">
            <option <?php echo (isset($this->options['single_topic_page_date']) && $this->options['single_topic_page_date'] == 'metadata_created_modified') ? 'selected' : ''; ?> value="metadata_both"><?php _e('Creation and Modification date','odm'); ?></option>
            <option <?php echo (isset($this->options['single_topic_page_date']) && $this->options['single_topic_page_date'] == 'metadata_created') ? 'selected' : '';?> value="metadata_created"><?php _e('Creation date','odm'); ?></option>
            <option <?php echo (isset($this->options['single_topic_page_date']) && $this->options['single_topic_page_date'] == 'metadata_modified') ? 'selected' : ''; ?> value="metadata_modified"><?php _e('Modification date','odm'); ?></option>
        </select>
    <?php
    }

    public function contact_page_field()
    {
        $contact_page = isset($this->options['contact_page']) ? $this->options['contact_page'] : __('None') ;
        wp_dropdown_pages(array(
         'name' => 'odm_options[contact_page]',
         'show_option_none' => __('None'),
         'selected' => $contact_page
        ));
    }

    public function data_page_field()
    {
        $data_page = isset($this->options['data_page']) ? $this->options['data_page'] : __('None') ;
        wp_dropdown_pages(array(
         'name' => 'odm_options[data_page]',
         'show_option_none' => __('None'),
         'selected' => $data_page
        ));
    }

    public function legal_disclaimer_field()
    {
        $disclaimer = $this->options['legal_disclaimer'];
        ?>
  <textarea id="odm_legal_disclaimer" name="odm_options[legal_disclaimer]" rows="5" cols="70"><?php echo $disclaimer;
        ?></textarea>
  <?php

    }

    public function interactive_map_field()
    {
        $map_data = $this->options['map_data'];

        if (!isset($map_data['server']) || !$map_data['server']) {
            $map_data['server'] = 'mapbox';
        } // default map service

  ?>
  <div id="mapbox-metabox">

   <div class="layers-container">

    <?php
    if (isset($map_data['base_layer'])) {
        $select_base_layer = $map_data['base_layer']['type'];
        $base_layer_url = $map_data['base_layer']['url'];
        $base_layer_url_copyright = $map_data['base_layer']['copy_right']? $map_data['base_layer']['copy_right'] : '© <a href="http://osm.org/copyright" target="_blank">OpenStreetMap</a> contributors.';
    } else {
        $select_base_layer = 'openstreetmap';
        $base_layer_url = '';
        $base_layer_url_copyright = '';
    }
        ?>

    <div>
     <?php _e('Select base layer', 'odm');
        ?>
     <select name="odm_options[map_data][base_layer][type]" id="baselayer_drop_down">
      <option value="openstreetmap" <?php echo $select_base_layer == 'openstreetmap' ? ' selected="selected"' : '';
        ?> >OpenStreetMap</option>
      <option value="mapquest_osm" <?php echo $select_base_layer == 'mapquest_osm' ? ' selected="selected"' : '';
        ?> >Mapquest OpenStreetMap</option>
      <option value="mapquest_sat" <?php echo $select_base_layer == 'mapquest_sat' ? ' selected="selected"' : '';
        ?> >Mapquest Satellite</option>
      <option value="stamen_toner" <?php echo $select_base_layer == 'stamen_toner' ? ' selected="selected"' : '';
        ?> >Stamen Toner</option>
      <option value="stamen_watercolor" <?php echo $select_base_layer == 'stamen_watercolor' ? ' selected="selected"' : '';
        ?>>Stamen Watercolor</option>
      <option value="stamen_terrain" <?php echo $select_base_layer == 'stamen_terrain' ? ' selected="selected"' : '';
        ?> >Stamen Terrain <?php _e('(USA Only)', 'odm');
        ?></option>
      <option value="nextgis" <?php echo $select_base_layer == 'nextgis' ? ' selected="selected"' : '';
        ?> >Nextgis
			</option>
      <option value="world_imagery" <?php echo $select_base_layer == 'world_imagery' ? ' selected="selected"' : '';
        ?> >World Imagery
			</option>
			<option value="world_boundaries_and_places" <?php echo $select_base_layer == 'world_boundaries_and_places' ? ' selected="selected"' : '';
        ?> >World boundaries and places
			</option>
			<option value="national_geographic_world_map" <?php echo $select_base_layer == 'national_geographic_world_map' ? ' selected="selected"' : '';
        ?> >National Geographic World Map
			</option>
			<option value="world_topographic_map" <?php echo $select_base_layer == 'world_topographic_map' ? ' selected="selected"' : '';
        ?> >World Topografic Map
			</option>
      <option value="custom" <?php echo $select_base_layer == 'custom' ? ' selected="selected"' : '';
        ?> ><?php _e('Custom', 'odm');
        ?></option>
      <option value="none" <?php echo $select_base_layer == 'none' ? ' selected="selected"' : '';
        ?> ><?php _e('None', 'odm');
        ?></option>
     </select>
     <input type="text" name="odm_options[map_data][base_layer][url]" id="baselayer_url_box" class="layer_title" size="40" placeholder="<?php _e('Enter layer URL', 'odm');
        ?>" value="<?php echo $base_layer_url;
        ?>" />
    <p>
      <?php _e('Enter layer copy right', 'odm'); ?>
    </p>
    <textarea id="baselayer_copy_right_box" name="odm_options[map_data][base_layer][copy_right]" rows="1" cols="90"><?php echo $base_layer_url_copyright;?></textarea>
    </div>

    <p><a class="button-primary preview-map" href="#"><?php _e('Update preview', 'odm');
        ?></a></p>
   </div>
   <h3><?php _e('Preview map', 'odm');
        ?></h3>
   <div class="map-container">
    <div id="map_preview" class="map"></div>
   </div>
   <div class="map-settings clearfix">
    <h3><?php _e('Map settings', 'odm');
        ?></h3>
    <div class="current map-setting">
     <h4><?php _e('Currently viewing', 'odm');
        ?></h4>
     <table>
      <tr>
       <td><?php _e('Center', 'odm');
        ?></td>
       <td><span class="center"></span></td>
      </tr>
      <tr>
       <td><?php _e('Zoom', 'odm');
        ?></td>
       <td><span class="zoom"></span></td>
      </tr>
      <tr>
       <td><?php _e('East', 'odm');
        ?></td>
       <td><span class="east"></span></td>
      </tr>
      <tr>
       <td><?php _e('North', 'odm');
        ?></td>
       <td><span class="north"></span></td>
      </tr>
      <tr>
       <td><?php _e('South', 'odm');
        ?></td>
       <td><span class="south"></span></td>
      </tr>
      <tr>
       <td><?php _e('West', 'odm');
        ?></td>
       <td><span class="west"></span></td>
      </tr>
     </table>
    </div>
    <div class="centerzoom map-setting">
     <h4><?php _e('Map center & zoom', 'odm');
        ?></h4>
     <p><a class="button set-map-centerzoom"><?php _e('Set current as map center & zoom', 'odm');
        ?></a></p>
     <table>
      <tr>
       <td><?php _e('Center', 'odm');
        ?></td>
       <td><span class="center">(<?php if (isset($map_data['center'])) {
    echo $map_data['center']['lat'];
}
        ?>, <?php if (isset($map_data['center'])) {
    echo $map_data['center']['lon'];
}
        ?>)</span></td>
      </tr>
      <tr>
       <td><?php _e('Zoom', 'odm');
        ?></td>
       <td><span class="zoom"><?php if (isset($map_data['zoom'])) {
    echo $map_data['zoom'];
}
        ?></span></td>
      </tr>
      <tr>
       <td><label for="min-zoom-input"><?php _e('Min zoom', 'odm');
        ?></label></td>
       <td>
        <input type="text" size="2" id="min-zoom-input" value="<?php if (isset($map_data['min_zoom'])) {
    echo $map_data['min_zoom'];
}
        ?>" name="odm_options[map_data][min_zoom]" />
        <a class="button set-min-zoom" href="#"><?php _e('Current', 'odm');
        ?></a>
       </td>
      </tr>
      <tr>
       <td><label for="max-zoom-input"><?php _e('Max zoom', 'odm');
        ?></label></td>
       <td>
        <input type="text" size="2" id="max-zoom-input" value="<?php if (isset($map_data['center'])) {
    echo $map_data['max_zoom'];
}
        ?>" name="odm_options[map_data][max_zoom]" />
        <a class="button set-max-zoom" href="#"><?php _e('Current', 'odm');
        ?></a>
       </td>
      </tr>
     </table>
     <input type="hidden" class="center-lat" name="odm_options[map_data][center][lat]" value="<?php if (isset($map_data['center'])) {
    echo $map_data['center']['lat'];
}
        ?>" />
     <input type="hidden" class="center-lon" name="odm_options[map_data][center][lon]" value="<?php if (isset($map_data['center'])) {
    echo $map_data['center']['lon'];
}
        ?>" />
     <input type="hidden" class="zoom" name="odm_options[map_data][zoom]" value="<?php if (isset($map_data['zoom'])) {
    echo $map_data['zoom'];
}
        ?>" />
    </div>
    <div class="pan-limits map-setting">
     <h4><?php _e('Pan limits', 'odm');
        ?></h4>
     <p><a class="button set-map-pan"><?php _e('Set current as map panning limits', 'odm');
        ?></a></p>
     <table>
      <tr>
       <td><?php _e('East', 'odm');
        ?></td>
       <td><span class="east"><?php if (isset($map_data['pan_limits'])) {
    echo $map_data['pan_limits']['east'];
}
        ?></span></td>
      </tr>
      <tr>
       <td><?php _e('North', 'odm');
        ?></td>
       <td><span class="north"><?php if (isset($map_data['pan_limits'])) {
    echo $map_data['pan_limits']['north'];
}
        ?></span></td>
      </tr>
      <tr>
       <td><?php _e('South', 'odm');
        ?></td>
       <td><span class="south"><?php if (isset($map_data['pan_limits'])) {
    echo $map_data['pan_limits']['south'];
}
        ?></span></td>
      </tr>
      <tr>
       <td><?php _e('West', 'odm');
        ?></td>
       <td><span class="west"><?php if (isset($map_data['pan_limits'])) {
    echo $map_data['pan_limits']['west'];
}
        ?></span></td>
      </tr>
     </table>
     <input type="hidden" class="east" name="odm_options[map_data][pan_limits][east]" value="<?php if (isset($map_data['pan_limits'])) {
    echo $map_data['pan_limits']['east'];
}
        ?>" />
     <input type="hidden" class="north" name="odm_options[map_data][pan_limits][north]" value="<?php if (isset($map_data['pan_limits'])) {
    echo $map_data['pan_limits']['north'];
}
        ?>" />
     <input type="hidden" class="south" name="odm_options[map_data][pan_limits][south]" value="<?php if (isset($map_data['pan_limits'])) {
    echo $map_data['pan_limits']['south'];
}
        ?>" />
     <input type="hidden" class="west" name="odm_options[map_data][pan_limits][west]" value="<?php if (isset($map_data['pan_limits'])) {
    echo $map_data['pan_limits']['west'];
}
        ?>" />
    </div>
    <div class="geocode map-setting">
     <h4><?php _e('Enable geocoding service', 'odm');
        ?></h4>
     <p>
      <input class="enable-geocode" id="enable_geocode" type="checkbox" name="odm_options[map_data][geocode]" <?php if (isset($map_data['geocode']) && $map_data['geocode']) {
    echo 'checked';
}
        ?> />
      <label for="enable_geocode"><?php _e('Enable geocode search service', 'odm');
        ?></label>
     </p>
    </div>
    <div class="handlers map-setting">
     <h4><?php _e('Map handlers', 'odm');
        ?></h4>
     <p>
      <input class="disable-mousewheel" id="disable_mousewheel" type="checkbox" name="odm_options[map_data][disable_mousewheel]" <?php if (isset($map_data['disable_mousewheel']) && $map_data['disable_mousewheel']) {
    echo 'checked';
}
        ?> />
      <label for="disable_mousewheel"><?php _e('Disable mousewheel zooming', 'odm');
        ?></label>
     </p>
    </div>
    <?php do_action('jeo_map_setup_options', $map_data);
        ?>
   </div>
   <p>
    <a class="button-primary preview-map" href="#"><?php _e('Update preview', 'odm');
        ?></a>
    <input type="checkbox" class="toggle-preview-mode" id="toggle_preview_mode" checked /> <label for="toggle_preview_mode"><strong><?php _e('Preview mode', 'odm');
        ?></strong></label>
    <i><?php _e("(preview mode doesn't apply zoom range nor pan limits setup)", 'odm');
        ?></i>
   </p>
  </div>
  <?php

    }

}

if (is_admin()) {
    $GLOBALS['odm_options'] = new Odm_Options();
}

function odm_get_logo()
{
    $options = get_option('odm_options');
    if (isset($options['logo'])) {
        return '<img src="'.$options['logo'].'" alt="'.get_bloginfo('name').'" />';
    } else {
        return false;
    }
}

function odm_get_style()
{
    $options = get_option('odm_options');
    if (isset($options['style'])) {
        return $options['style'];
    } else {
        return false;
    }
}

function odm_get_facebook_url()
{
    $options = get_option('odm_options');
    if (isset($options['facebook_url'])) {
        return $options['facebook_url'];
    } else {
        return false;
    }
}

function odm_get_twitter_url()
{
    $options = get_option('odm_options');
    if (isset($options['twitter_url'])) {
        return $options['twitter_url'];
    } else {
        return false;
    }
}

/**
 * Load the existing LinkedIn URL
 *
 * @return boolean
 */
function odm_get_linkedin_url()
{
    $options = get_option('odm_options');

    if ( isset( $options['linkedin_url'] ) ) {
        return $options['linkedin_url'];
    } else {
        return false;
    }
}

function odm_get_youtube_url()
{
    $options = get_option('odm_options');

    if ( isset( $options['youtube_url'] ) ) {
        return $options['youtube_url'];
    } else {
        return false;
    }
}

function odm_get_legal_disclaimer()
{
    $options = get_option('odm_options');
    if (isset($options['legal_disclaimer'])) {
        return $options['legal_disclaimer'];
    } else {
        return false;
    }
}

function odm_get_wp_post_types_for_category_page()
{
    $options = get_option('odm_options');
    if (isset($options['category_page'])) {
        $post_type = array_map('trim', explode(',', $options['category_page']));
        return $post_type;
    } else {
        return array('news-article', 'announcement', 'topic', 'profiles', 'map-layer');
    }
}

function odm_get_ckan_post_types_for_category_page()
{
    $options = get_option('odm_options');
    if (isset($options['category_page_ckan'])) {
        $post_type = array_map('trim', explode(',', $options['category_page_ckan']));
        return $post_type;
    } else {
        return array('dataset', 'library_record', 'laws_record', 'agreement');
    }
}

function odm_get_contact_page_id()
{
    $options = get_option('odm_options');
    if (isset($options['contact_page'])) {
        return $options['contact_page'];
    } else {
        return false;
    }
}

function odm_get_data_page_id()
{
    $options = get_option('odm_options');
    if (isset($options['data_page'])) {
        return $options['data_page'];
    } else {
        return false;
    }
}

function odm_get_interactive_map_data()
{
    $options = get_option('odm_options');
    if (isset($options['map_data'])) {
        return $options['map_data'];
    } else {
        return false;
    }
}

function odm_show_print_icon() {
  $options = get_option('odm_options');
  if ( isset( $options['print_icon'] ) ) {
    return true;
  } else {
    return false;
  }
}
