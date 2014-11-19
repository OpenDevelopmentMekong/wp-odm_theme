<?php

// Style manager
require_once(STYLESHEETPATH . '/inc/theme-style.php');

// Map category
require_once(STYLESHEETPATH . '/inc/map-category.php');

function opendev_styles() {

	$options = get_option('opendev_options');

	$css_base = get_stylesheet_directory_uri() . '/css/';

	wp_register_style('opendev-base',  $css_base . 'opendev.css');
	wp_register_style('opendev-theme_01',  $css_base . 'theme_01.css');
	wp_register_style('opendev-theme_02',  $css_base . 'theme_02.css');
	wp_register_style('opendev-theme_03',  $css_base . 'theme_03.css');

	wp_enqueue_style('opendev-base');
	if($options['style']) {
		wp_enqueue_style('opendev-' . $options['style']);
	}

}
add_action('wp_footer', 'opendev_styles');

function opendev_logo() {

	$options = get_option('opendev_options');
	if($options['logo']) {
		?>
		<h1 class="with-logo">
			<a href="<?php echo home_url('/'); ?>" title="<?php bloginfo('name'); ?>">
				<?php bloginfo('name'); ?>
				<img src="<?php echo $options['logo']; ?>" alt="<?php bloginfo('name'); ?>" />
			</a>
		</h1>
		<?php
	} else {
		?>
		<h1>
			<a href="<?php echo home_url('/'); ?>" title="<?php bloginfo('name'); ?>">
				<?php bloginfo('name'); ?>
			</a>
		</h1>
		<h2><?php bloginfo('description'); ?></h2>
		<?php
	}

}