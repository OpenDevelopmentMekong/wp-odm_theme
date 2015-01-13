<?php

// Style manager
require_once(STYLESHEETPATH . '/inc/theme-style.php');

// Briefings
require_once(STYLESHEETPATH . '/inc/briefings.php');

// Map category
require_once(STYLESHEETPATH . '/inc/map-category.php');

function opendev_styles() {

	$options = get_option('opendev_options');

	$css_base = get_stylesheet_directory_uri() . '/css/';

	//wp_dequeue_style('jeo-main');

	wp_register_style('webfont-droid-serif', 'http://fonts.googleapis.com/css?family=Droid+Serif:400,700');
	wp_register_style('webfont-opendev', get_stylesheet_directory_uri() . '/font/style.css');
	wp_register_style('opendev-base',  $css_base . 'opendev.css', array('webfont-droid-serif', 'webfont-opendev'));
	wp_register_style('opendev-theme_01',  $css_base . 'theme_01.css');
	wp_register_style('opendev-theme_02',  $css_base . 'theme_02.css');
	wp_register_style('opendev-theme_03',  $css_base . 'theme_03.css');

	wp_enqueue_style('opendev-base');
	if($options['style']) {
		wp_enqueue_style('opendev-' . $options['style']);
	}

}
add_action('wp_enqueue_scripts', 'opendev_styles', 15);

function opendev_jeo_scripts() {
	wp_enqueue_script('opendev-interactive-map', get_stylesheet_directory_uri() . '/inc/interactive-map.js', array('jeo'));
}
add_action('jeo_enqueue_scripts', 'opendev_jeo_scripts');

function opendev_get_logo() {

	$options = get_option('opendev_options');
	if($options['logo'])
		return '<img src="' . $options['logo'] . '" alt="' . get_bloginfo('name') . '" />';
	else
		return false;

}

function opendev_logo() {

	$logo = opendev_get_logo();

	if($logo && !is_multisite()) {
		?>
		<h1 class="with-logo">
			<a href="<?php echo home_url('/'); ?>" title="<?php bloginfo('name'); ?>">
				<?php bloginfo('name'); ?>
				<?php echo $logo; ?>
			</a>
		</h1>
		<?php
	} else {
		?>
		<h1>
			<a href="<?php echo home_url('/'); ?>" title="<?php bloginfo('name'); ?>">
				<?php // bloginfo('name'); ?>
				<span class="icon-od-logo"></span>
				Op<sup>e</sup>nDevelopment
			</a>
		</h1>
		<?php
	}

}