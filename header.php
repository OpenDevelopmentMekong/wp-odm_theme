<!DOCTYPE html>
<html <?php language_attributes(); ?>>

<head>
<meta name="viewport" content="width=device-width, initial-scale=1">
<meta charset="<?php bloginfo('charset'); ?>" />
<title>

  <?php
    global $page, $paged;
    wp_title('|', true, 'right');

    bloginfo('name');

    $site_description = get_bloginfo('description', 'display');
    if ($site_description && (is_home() || is_front_page())) {
        echo " | $site_description";
    }

    if ($paged >= 2 || $page >= 2) {
        echo ' | '.__('Page', 'jeo').max($paged, $page);
    } ?>

</title>
<link rel="profile" href="http://gmpg.org/xfn/11" />
<link rel="stylesheet" type="text/css" media="all" href="<?php bloginfo('stylesheet_url'); ?>" />
<link rel="pingback" href="<?php bloginfo('pingback_url'); ?>" />
<?php $site_name = str_replace('Open Development ', '', get_bloginfo('name')); ?>
<link rel="shortcut icon" href="<?php echo get_stylesheet_directory_uri(); ?>/img/favicon/<?php echo strtolower($site_name); ?>-favicon.ico" type="image/x-icon" />

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
  <header id="od-head">
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
  </header>

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

  <!-- Disclaimer -->
  <?php if (isset($options_msg['notice_message'])): ?>
    <nav id="od-disclaimer">
      <div class="container">
        <div class="row">
          <?php
            $options_msg = get_option('odm_options');
            if (isset($options_msg['notice_message']) && $options_msg['notice_message'] != ''): ?>
              <div id="notification-message">
                <div class="container">
                  <div class="sixteen columns">
                    <div class="notification-message-box">
                      <?php echo $options_msg['notice_message']; ?>
                    </div>
                  </div>
                </div>
              </div>
        <?php endif; ?>
        </div>
      </div>
    </nav>
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
