<?php

/*
 * Open Development
 * Theme style
 */

class OpenDev_Style {

	function __construct() {

		add_action('admin_menu', array($this, 'admin_menu'));
		//add_action('admin_init', array($this, 'init_theme_settings'));

	}

	function admin_menu() {

		add_theme_page(__('Open Development Style', 'opendev'), __('Open Development', 'opendev'), 'edit_theme_options', 'opendev-style', '');

	}

	// function init_theme_settings() {

	// 	add_settings_section(
	// 		'opendev_style',
	// 		__('Color', 'opendev'),
	// 		'',
	// 		'general'
	// 	);

	// 	add_settings_field(
	// 		'opendev_color',
	// 		__('City', 'opendev'),
	// 		array($this, 'color_field'),
	// 		'general',
	// 		'opendev_style'
	// 	);

	// 	register_setting('general', 'opendev_color');

	// }

}

$GLOBALS['opendev_style'] = new OpenDev_Style();