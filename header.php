<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
<meta charset="<?php bloginfo('charset'); ?>" />
<title><?php
	global $page, $paged;

	wp_title( '|', true, 'right' );

	bloginfo( 'name' );

	$site_description = get_bloginfo('description', 'display');
	if ( $site_description && ( is_home() || is_front_page() ) )
		echo " | $site_description";

	if ( $paged >= 2 || $page >= 2 )
		echo ' | ' . __('Page', 'jeo') . max($paged, $page);

	?></title>
<link rel="profile" href="http://gmpg.org/xfn/11" />
<link rel="stylesheet" type="text/css" media="all" href="<?php bloginfo('stylesheet_url'); ?>" />
<link rel="pingback" href="<?php bloginfo('pingback_url'); ?>" />
<link rel="shortcut icon" href="<?php echo get_template_directory_uri(); ?>/img/favicon.ico" type="image/x-icon" />
<?php wp_head(); ?>
</head>
<body <?php body_class(get_bloginfo('language')); ?>>
	<header id="od-head">
		<div class="container">
			<div class="eight columns">
				<div class="site-meta">
					<?php opendev_logo(); ?>
					<?php
					if(is_multisite()) {
						$sites = wp_get_sites();
						if(!empty($sites)) {
							$current = get_current_blog_id();
							$name = str_replace('Open Development ', '', get_bloginfo('name'));
							$logo = opendev_get_logo();
							if($logo)
								$name = $logo;
							echo '<div class="ms-dropdown-title">';
							echo '<h2 class="side-title">' . $name . '<span class="icon-arrow-down5"></span></h2>';
							echo '<ul>';
							foreach($sites as $site) {
								if($current != $site['blog_id']) {
									$details = get_blog_details($site['blog_id']);
									$name = str_replace('Open Development ', '', $details->blogname);
									echo '<li><a href="' . $details->siteurl . '">' . $name . '</a></li>';
								}
							}
							echo '</ul>';
							echo '</div>';
						}
					}
					?>
				</div>
			</div>
			<div class="four columns">
				<div id="od-head-nav">
					<div class="clearfix">
						<nav id="social-nav">
							<?php
							$fb = opendev_get_facebook_url();
							if($fb) :
								?>
								<a class="icon-facebook" href="<?php echo $fb; ?>" target="_blank" rel="external" title="Facebook"></a>
								<?php
							endif;
							?>
							<?php
							$tw = opendev_get_twitter_url();
							if($tw) :
								?>
								<a class="icon-twitter" href="<?php echo $tw; ?>" target="_blank" rel="external" title="Twitter"></a>
								<?php
							endif;
							?>
							<?php
							$contact_id = opendev_get_contact_page_id();
							if($contact_id) :
								?>
								<a href="<?php echo get_permalink($contact_id); ?>">Contact</a>
								<?php
							endif;
							?>
						</nav>
					</div>
				</div>
			</div>
		</div>
		<nav id="ms-nav">
			<div class="container">
				<div class="twelve columns">
					<?php opendev_ms_nav(); ?>
				</div>
			</div>
		</nav>
		<nav id="main-nav">
			<div class="container">
				<div class="twelve columns">
					<?php wp_nav_menu(array('theme_location' => 'header_menu')); ?>
				</div>
			</div>
		</nav>
	</header>