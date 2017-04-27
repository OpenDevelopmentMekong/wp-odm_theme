<!DOCTYPE html>
<html <?php language_attributes(); ?>>

<head>
<?php global $post, $page, $paged; ?>
<meta name="viewport" content="width=device-width, initial-scale=1">
<meta charset="<?php bloginfo('charset'); ?>" />
<meta property="og:title" content="<?php the_title(); ?>" />
<meta property="og:description" content="<?php echo strip_tags(odm_excerpt($post)); ?>" />
<meta property="og:site_name" content="<?php bloginfo('name'); ?>"/>
<meta property="og:type" content="opendevelopment:<?php echo get_post_type(); ?>" />
<?php if(has_post_thumbnail( $post->ID )) { ?>
<meta property="og:image" content="<?php $img_src = wp_get_attachment_image_src( get_post_thumbnail_id($post->ID), 'full', false); echo $img_src[0]; ?>" />
<?php } ?>
<meta property="og:url" content="<?php echo get_permalink()?>" />
<title>

  <?php
    wp_title('|', true, 'right');

    bloginfo('name');

    $site_description = get_bloginfo('description', 'display');
    if ($site_description && (is_home() || is_front_page())) {
        echo " | $site_description";
    }

    if ($paged >= 2 || $page >= 2) {
        echo ' | '.__('Page', 'odm').max($paged, $page);
    } ?>

</title>
<link rel="profile" href="http://gmpg.org/xfn/11" />
<link rel="stylesheet" type="text/css" media="all" href="<?php bloginfo('stylesheet_url'); ?>" />
<link rel="pingback" href="<?php bloginfo('pingback_url'); ?>" />
<link rel="shortcut icon" href="<?php echo get_stylesheet_directory_uri(); ?>/img/favicon/<?php echo odm_country_manager()->get_current_country(); ?>-favicon.ico" type="image/x-icon" />

<?php wp_head(); ?>

</head>
<body <?php body_class(get_bloginfo('language')); ?>>

  <!-- Country and language selector nav -->
  <nav id="od-selector">
    <div class="container">
        <div class="eleven columns">
          <?php odm_country_manager()->echo_country_selectors(); ?>
          <i class="fa fa-caret-down" style="display:none;" id="country-select-dropdown"></i>
        </div>
        <div class="five columns">
          <?php odm_language_manager()->echo_language_selectors(); ?>
        </div>
    </div>
  </nav>

  <!-- Logo and contact icons -->
  <nav id="od-head">
    <div class="container">
        <div class="thirteen columns">
          <div class="site-meta">
            <?php odm_logo(); ?>
          </div>
        </div>
        <div class="three columns">
          <div class="social">
            <?php odm_get_template('social-nav',array(),true); ?>
          </div>
        </div>
    </div>
  </nav>

  <!-- Menu and search -->
  <nav id="od-menu">
      <div class="container">
        <div class="sixteen columns">
          <?php wp_nav_menu(array('theme_location' => 'header_menu')); ?>
        </div>
      </div>
  </nav>

  <!-- Submenu -->
  <nav id="od-search-results">
    <div class="container">
      <div class="sixteen columns">
        <div class="results-container"></div>
      </div>
    </div>
  </nav>

  <!-- notification-message -->
	<?php
	$options_msg = get_option('odm_options');
	if (isset($options_msg['notice_message']) && $options_msg['notice_message'] != ''): ?>
		<div id="notification-message" class="container">
			<div class="sixteen columns">
				<div class="notification-message-box">
					<a id="notification-message-close-btn" href="#" class="close-btn"><i class="fa fa-times-circle fa-2x"></i></a>
	        <?php echo $options_msg['notice_message']; ?>
				</div>
			</div>
	  </div>

	<?php endif; ?>


  <!-- Breadcrumb -->
  <nav id="od-breadcrumb">
    <div class="container">
      <div class="row">
        <div class="sixteen columns">
        <?php
          if (!is_front_page() && !is_page('map-explorer')) : ?>
            <div id="main-breadcrumb">
              <?php echo_the_breadcrumb(); ?>
            </div>
        <?php
          endif; ?>
        </div>
      </div>
    </div>
  </nav>

<article>
