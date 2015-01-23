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