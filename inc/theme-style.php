<?php

/*
 * Open Development
 * Theme style
 */

class OpenDev_Style {

	function __construct() {

		add_action('admin_menu', array($this, 'admin_menu'));
		add_action('admin_init', array($this, 'init_theme_settings'));
		add_action('admin_enqueue_scripts', array($this, 'enqueue_scripts'));

	}

	function enqueue_scripts() {

		if(get_current_screen()->id == 'appearance_page_opendev_options') {
			wp_enqueue_media();
			wp_enqueue_script('opendev-theme-style', get_stylesheet_directory_uri() . '/inc/theme-style.js');
		}

	}

	var $themes = array(
		'Default' => '',
		'theme 01' => 'theme_01',
		'theme 02' => 'theme_02',
		'theme 03' => 'theme_03'
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
			<button id="opendev_logo_button" class="button" /><?php _e('Upload'); ?></button>
		</div>
		<?php if($logo) { ?>
			<div class="logo-preview">
				<img src="<?php echo $logo; ?>" style="max-width:300px;height:auto;" />
			</div>
			<?php } ?>
		<?php
	}

}

if(is_admin())
	$GLOBALS['opendev_style'] = new OpenDev_Style();