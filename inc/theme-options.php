<?php

/*
 * Open Development
 * Theme options
 */

class OpenDev_Options {

	function __construct() {

		add_action('admin_menu', array($this, 'admin_menu'));
		add_action('admin_init', array($this, 'init_theme_settings'));
		add_action('admin_enqueue_scripts', array($this, 'enqueue_scripts'));

	}

	function enqueue_scripts() {

		if(get_current_screen()->id == 'appearance_page_opendev_options') {
			wp_enqueue_media();
			wp_enqueue_script('opendev-theme-options', get_stylesheet_directory_uri() . '/inc/theme-options.js');
		}

	}

	var $themes = array(
		'Default' => '',
		'cambodia' => 'cambodia',
		'thailand' => 'thailand',
		'laos' => 'laos',
		'myanmar' => 'myanmar',
		'vietnam' => 'vietnam'
	);

	function admin_menu() {

		add_theme_page(__('Open Development Style', 'opendev'), __('Open Development', 'opendev'), 'edit_theme_options', 'opendev_options', array($this, 'admin_page'));

	}

	function admin_page() {

		$this->options = get_option('opendev_options');

		?>
		<div class="wrap">
			<?php screen_icon(); ?>
			<h2><?php _e('Open Development Theme Options', 'opendev'); ?></h2>
			<form method="post" action="options.php">
			<?php
				settings_fields('opendev_options_group');
				do_settings_sections('opendev_options');
				submit_button();
			?>
			</form>
		</div>
		<?php
	}

	function init_theme_settings() {

		mapbox_metabox_init();

		add_settings_section(
			'opendev_style_section',
			__('Style', 'opendev'),
			'',
			'opendev_options'
		);

		add_settings_section(
			'opendev_links_section',
			__('Links', 'opendev'),
			'',
			'opendev_options'
		);

		add_settings_section(
			'opendev_interactive_map_section',
			__('Interactive map', 'opendev'),
			'',
			'opendev_options'
		);

		add_settings_field(
			'opendev_style',
			__('Choose a style', 'opendev'),
			array($this, 'style_field'),
			'opendev_options',
			'opendev_style_section'
		);

		add_settings_field(
			'opendev_logo',
			__('Upload a custom logo', 'opendev'),
			array($this, 'logo_field'),
			'opendev_options',
			'opendev_style_section'
		);

		add_settings_field(
			'opendev_facebook',
			__('Facebook url', 'opendev'),
			array($this, 'facebook_field'),
			'opendev_options',
			'opendev_links_section'
		);

		add_settings_field(
			'opendev_twitter',
			__('Twitter url', 'opendev'),
			array($this, 'twitter_field'),
			'opendev_options',
			'opendev_links_section'
		);

		add_settings_field(
			'opendev_legal_disclaimer',
			__('Legal disclaimer', 'opendev'),
			array($this, 'legal_disclaimer_field'),
			'opendev_options',
			'opendev_links_section'
		);

		add_settings_field(
			'opendev_contact',
			__('Contact page', 'opendev'),
			array($this, 'contact_page_field'),
			'opendev_options',
			'opendev_links_section'
		);

		add_settings_field(
			'opendev_data_page',
			__('Data page', 'opendev'),
			array($this, 'data_page_field'),
			'opendev_options',
			'opendev_links_section'
		);

		add_settings_field(
			'opendev_interactive_map',
			__('Base map settings', 'opendev'),
			array($this, 'interactive_map_field'),
			'opendev_options',
			'opendev_interactive_map_section'
		);

		register_setting('opendev_options_group', 'opendev_options');

	}

	function style_field() {
		?>
		<select id="opendev_style" name="opendev_options[style]">
			<?php foreach($this->themes as $theme => $path) { ?>
				<option <?php if($this->options['style'] == $path) echo 'selected'; ?> value="<?php echo $path; ?>"><?php _e($theme); ?></option>
			<?php } ?>
		</select>
		<?php
	}

	function logo_field() {
		$logo = $this->options['logo'];
		?>
		<div class="uploader">
			<input id="opendev_logo" name="opendev_options[logo]" type="text" placeholder="<?php _e('Logo url', 'opendev'); ?>" value="<?php echo $logo; ?>" size="80" />
			<a  id="opendev_logo_button" class="button" /><?php _e('Upload'); ?></a>
		</div>
		<?php if($logo) { ?>
			<div class="logo-preview">
				<img src="<?php echo $logo; ?>" style="max-width:300px;height:auto;" />
			</div>
			<?php } ?>
		<?php
	}

	function facebook_field() {
		$facebook = $this->options['facebook_url'];
		?>
		<input id="opendev_facebook_url" name="opendev_options[facebook_url]" type="text" value="<?php echo $facebook; ?>" size="70" />
		<?php
	}

	function twitter_field() {
		$twitter = $this->options['twitter_url'];
		?>
		<input id="opendev_twitter_url" name="opendev_options[twitter_url]" type="text" value="<?php echo $twitter; ?>" size="70" />
		<?php
	}

	function contact_page_field() {
		$contact_page = $this->options['contact_page'];
		wp_dropdown_pages(array(
			'name' => 'opendev_options[contact_page]',
			'selected' => $contact_page,
			'show_option_none' => __('None')
		));
	}

	function data_page_field() {
		$data_page = $this->options['data_page'];
		wp_dropdown_pages(array(
			'name' => 'opendev_options[data_page]',
			'selected' => $data_page,
			'show_option_none' => __('None')
		));
	}

	function legal_disclaimer_field() {
		$disclaimer = $this->options['legal_disclaimer'];
		?>
		<textarea id="opendev_legal_disclaimer" name="opendev_options[legal_disclaimer]" rows="10" cols="70"><?php echo $disclaimer; ?></textarea>
		<?php
	}

	function interactive_map_field() {

		$map_data = $this->options['map_data'];

		if(!isset($map_data['server']) || !$map_data['server'])
			$map_data['server'] = 'mapbox'; // default map service

		?>
		<div id="mapbox-metabox">

			<div class="layers-container">

				<?php
				if(isset($map_data['base_layer']))  {
					$select_base_layer = $map_data['base_layer']['type'];
					$base_layer_url = $map_data['base_layer']['url'];
				} else {
					$select_base_layer = 'openstreetmap';
					$base_layer_url = '';
				}
				?>

				<div>
					<?php _e('Select base layer', 'jeo'); ?>
					<select name="opendev_options[map_data][base_layer][type]" id="baselayer_drop_down">
						<option value="openstreetmap" <?php echo $select_base_layer == 'openstreetmap' ? ' selected="selected"' : ''; ?> >OpenStreetMap</option>
						<option value="mapquest_osm" <?php echo $select_base_layer == 'mapquest_osm' ? ' selected="selected"' : ''; ?> >Mapquest OpenStreetMap</option>
						<option value="mapquest_sat" <?php echo $select_base_layer == 'mapquest_sat' ? ' selected="selected"' : ''; ?> >Mapquest Satellite</option>
						<option value="stamen_toner" <?php echo $select_base_layer == 'stamen_toner' ? ' selected="selected"' : ''; ?> >Stamen Toner</option>
						<option value="stamen_watercolor" <?php echo $select_base_layer == 'stamen_watercolor' ? ' selected="selected"' : ''; ?>>Stamen Watercolor</option>
						<option value="stamen_terrain" <?php echo $select_base_layer == 'stamen_terrain' ? ' selected="selected"' : ''; ?> >Stamen Terrain <?php _e('(USA Only)','jeo'); ?></option>
						<option value="custom" <?php echo $select_base_layer == 'custom' ? ' selected="selected"' : ''; ?> ><?php _e('Custom','jeo'); ?></option>
						<option value="none" <?php echo $select_base_layer == 'none' ? ' selected="selected"' : ''; ?> ><?php _e('None','jeo'); ?></option>
					</select>
					<input type="text" name="opendev_options[map_data][base_layer][url]" id="baselayer_url_box" class="layer_title" size="40" placeholder="<?php _e('Enter layer URL', 'jeo'); ?>" value="<?php echo $base_layer_url; ?>" />
				</div>

				<p><a class="button-primary preview-map" href="#"><?php _e('Update preview', 'jeo'); ?></a></p>
			</div>
			<h3><?php _e('Preview map', 'jeo'); ?></h3>
			<div class="map-container">
				<div id="map_preview" class="map"></div>
			</div>
			<div class="map-settings clearfix">
				<h3><?php _e('Map settings', 'jeo'); ?></h3>
				<div class="current map-setting">
					<h4><?php _e('Currently viewing', 'jeo'); ?></h4>
					<table>
						<tr>
							<td><?php _e('Center', 'jeo'); ?></td>
							<td><span class="center"></span></td>
						</tr>
						<tr>
							<td><?php _e('Zoom', 'jeo'); ?></td>
							<td><span class="zoom"></span></td>
						</tr>
						<tr>
							<td><?php _e('East', 'jeo'); ?></td>
							<td><span class="east"></span></td>
						</tr>
						<tr>
							<td><?php _e('North', 'jeo'); ?></td>
							<td><span class="north"></span></td>
						</tr>
						<tr>
							<td><?php _e('South', 'jeo'); ?></td>
							<td><span class="south"></span></td>
						</tr>
						<tr>
							<td><?php _e('West', 'jeo'); ?></td>
							<td><span class="west"></span></td>
						</tr>
					</table>
				</div>
				<div class="centerzoom map-setting">
					<h4><?php _e('Map center & zoom', 'jeo'); ?></h4>
					<p><a class="button set-map-centerzoom"><?php _e('Set current as map center & zoom', 'jeo'); ?></a></p>
					<table>
						<tr>
							<td><?php _e('Center', 'jeo'); ?></td>
							<td><span class="center">(<?php if(isset($map_data['center'])) echo $map_data['center']['lat']; ?>, <?php if(isset($map_data['center'])) echo $map_data['center']['lon']; ?>)</span></td>
						</tr>
						<tr>
							<td><?php _e('Zoom', 'jeo'); ?></td>
							<td><span class="zoom"><?php if(isset($map_data['zoom'])) echo $map_data['zoom']; ?></span></td>
						</tr>
						<tr>
							<td><label for="min-zoom-input"><?php _e('Min zoom', 'jeo'); ?></label></td>
							<td>
								<input type="text" size="2" id="min-zoom-input" value="<?php if(isset($map_data['min_zoom'])) echo $map_data['min_zoom']; ?>" name="opendev_options[map_data][min_zoom]" />
								<a class="button set-min-zoom" href="#"><?php _e('Current', 'jeo'); ?></a>
							</td>
						</tr>
						<tr>
							<td><label for="max-zoom-input"><?php _e('Max zoom', 'jeo'); ?></label></td>
							<td>
								<input type="text" size="2" id="max-zoom-input" value="<?php if(isset($map_data['center'])) echo $map_data['max_zoom']; ?>" name="opendev_options[map_data][max_zoom]" />
								<a class="button set-max-zoom" href="#"><?php _e('Current', 'jeo'); ?></a>
							</td>
						</tr>
					</table>
					<input type="hidden" class="center-lat" name="opendev_options[map_data][center][lat]" value="<?php if(isset($map_data['center'])) echo $map_data['center']['lat']; ?>" />
					<input type="hidden" class="center-lon" name="opendev_options[map_data][center][lon]" value="<?php if(isset($map_data['center'])) echo $map_data['center']['lon']; ?>" />
					<input type="hidden" class="zoom" name="opendev_options[map_data][zoom]" value="<?php if(isset($map_data['zoom'])) echo $map_data['zoom']; ?>" />
				</div>
				<div class="pan-limits map-setting">
					<h4><?php _e('Pan limits', 'jeo'); ?></h4>
					<p><a class="button set-map-pan"><?php _e('Set current as map panning limits', 'jeo'); ?></a></p>
					<table>
						<tr>
							<td><?php _e('East', 'jeo'); ?></td>
							<td><span class="east"><?php if(isset($map_data['pan_limits'])) echo $map_data['pan_limits']['east']; ?></span></td>
						</tr>
						<tr>
							<td><?php _e('North', 'jeo'); ?></td>
							<td><span class="north"><?php if(isset($map_data['pan_limits'])) echo $map_data['pan_limits']['north']; ?></span></td>
						</tr>
						<tr>
							<td><?php _e('South', 'jeo'); ?></td>
							<td><span class="south"><?php if(isset($map_data['pan_limits'])) echo $map_data['pan_limits']['south']; ?></span></td>
						</tr>
						<tr>
							<td><?php _e('West', 'jeo'); ?></td>
							<td><span class="west"><?php if(isset($map_data['pan_limits'])) echo $map_data['pan_limits']['west']; ?></span></td>
						</tr>
					</table>
					<input type="hidden" class="east" name="opendev_options[map_data][pan_limits][east]" value="<?php if(isset($map_data['pan_limits'])) echo $map_data['pan_limits']['east']; ?>" />
					<input type="hidden" class="north" name="opendev_options[map_data][pan_limits][north]" value="<?php if(isset($map_data['pan_limits'])) echo $map_data['pan_limits']['north']; ?>" />
					<input type="hidden" class="south" name="opendev_options[map_data][pan_limits][south]" value="<?php if(isset($map_data['pan_limits'])) echo $map_data['pan_limits']['south']; ?>" />
					<input type="hidden" class="west" name="opendev_options[map_data][pan_limits][west]" value="<?php if(isset($map_data['pan_limits'])) echo $map_data['pan_limits']['west']; ?>" />
				</div>
				<div class="geocode map-setting">
					<h4><?php _e('Enable geocoding service', 'jeo'); ?></h4>
					<p>
						<input class="enable-geocode" id="enable_geocode" type="checkbox" name="opendev_options[map_data][geocode]" <?php if(isset($map_data['geocode']) && $map_data['geocode']) echo 'checked'; ?> />
						<label for="enable_geocode"><?php _e('Enable geocode search service', 'jeo'); ?></label>
					</p>
				</div>
				<div class="handlers map-setting">
					<h4><?php _e('Map handlers', 'jeo'); ?></h4>
					<p>
						<input class="disable-mousewheel" id="disable_mousewheel" type="checkbox" name="opendev_options[map_data][disable_mousewheel]" <?php if(isset($map_data['disable_mousewheel']) && $map_data['disable_mousewheel']) echo 'checked'; ?> />
						<label for="disable_mousewheel"><?php _e('Disable mousewheel zooming', 'jeo'); ?></label>
					</p>
				</div>
				<?php do_action('jeo_map_setup_options', $map_data); ?>
			</div>
			<p>
				<a class="button-primary preview-map" href="#"><?php _e('Update preview', 'jeo'); ?></a>
				<input type="checkbox" class="toggle-preview-mode" id="toggle_preview_mode" checked /> <label for="toggle_preview_mode"><strong><?php _e('Preview mode', 'jeo'); ?></strong></label>
				<i><?php _e("(preview mode doesn't apply zoom range nor pan limits setup)", 'jeo'); ?></i>
			</p>
		</div>
		<?php

	}

}

if(is_admin())
	$GLOBALS['opendev_options'] = new OpenDev_Options();

function opendev_get_logo() {

	$options = get_option('opendev_options');
	if($options['logo'])
		return '<img src="' . $options['logo'] . '" alt="' . get_bloginfo('name') . '" />';
	else
		return false;

}

function opendev_get_facebook_url() {

	$options = get_option('opendev_options');
	if($options['facebook_url']) {
		return $options['facebook_url'];
	} else {
		return false;
	}

}

function opendev_get_twitter_url() {

	$options = get_option('opendev_options');
	if($options['twitter_url']) {
		return $options['twitter_url'];
	} else {
		return false;
	}

}

function opendev_get_legal_disclaimer() {

	$options = get_option('opendev_options');
	if($options['legal_disclaimer']) {
		return $options['legal_disclaimer'];
	} else {
		return false;
	}

}

function opendev_get_contact_page_id() {

	$options = get_option('opendev_options');
	if($options['contact_page']) {
		return $options['contact_page'];
	} else {
		return false;
	}

}

function opendev_get_data_page_id() {

	$options = get_option('opendev_options');
	if($options['data_page']) {
		return $options['data_page'];
	} else {
		return false;
	}

}

function opendev_get_interactive_map_data() {

	$options = get_option('opendev_options');
	if($options['map_data']) {
		return $options['map_data'];
	} else {
		return false;
	}

}