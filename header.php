<?php ?>
<!DOCTYPE html>
<html data-country="<?php echo $country; ?>" <?php language_attributes(); ?>>

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
<?php $sit_name = str_replace('Open Development ', '', get_bloginfo('name')); ?>
<link rel="shortcut icon" href="<?php echo get_stylesheet_directory_uri(); ?>/img/favicon/<?php echo strtolower($sit_name); ?>-favicon.ico" type="image/x-icon" />

<?php wp_head(); ?>

</head>
<body <?php body_class(get_bloginfo('language')); ?>>

  <!-- Country and language selector nav -->
  <nav id="od-selector">
    <div class="container">
      <div class="row">
        <div class="six columns">
          country selector here
        </div>
        <div class="six columns">
          <div class="language float-right">
            <?php
              if (function_exists('qtranxf_generateLanguageSelectCode')) {
                  qtranxf_generateLanguageSelectCode('image');
              } ?>
          </div>
        </div>
      </div>
    </div>
  </nav>

  <!-- Logo and contact icons -->
  <header id="od-head">
    <div class="container">
      <div class="row">
        <div class="nine columns organization left_organization">
          <div class="site-meta">
            <?php opendev_logo(); ?>
          </div>
        </div>
        <div class="three columns">
          <div class="social">
            <nav id="social-nav">
              <?php
                  $fb = opendev_get_facebook_url();
                  if ($fb) : ?>
                    <a class="icon-facebook" href="<?php echo $fb; ?>" target="_blank" rel="external" title="Facebook"></a>
                <?php
                  endif; ?>
              <?php
                  $tw = opendev_get_twitter_url();
                  if ($tw) : ?>
                    <a class="icon-twitter" href="<?php echo $tw; ?>" target="_blank" rel="external" title="Twitter"></a>
              <?php
                  endif; ?>
              <?php
                  $contact_id = opendev_get_contact_page_id();
                  if ($contact_id) : ?>
                    <a class="icon-envelop" href="<?php echo get_permalink($contact_id); ?>"><img src="<?php echo get_stylesheet_directory_uri(); ?>/img/envelop.svg"></a>
              <?php
                else: ?>
              <?php
                endif; ?>
            </nav>
          </div>
        </div>
      </div>
    </div>
  </header>

  <!-- Menu and search -->
  <nav id="od-menu">
    <div class="container">
      <div class="row">
        <div class="nine columns">
          <?php wp_nav_menu(array('theme_location' => 'header_menu')); ?>
        </div>
        <div class="three columns">
          <div class="search">
            <div id="live-search">
                <input type="text" placeholder="<?php _e('Search site... &#128269;', 'opendev');?>" onfocus="this.placeholder=''" onblur="this.placeholder='<?php _e('Search site... &#128269;', 'opendev');?>'" />
                <img src="<?php bloginfo('stylesheet_directory');?>/img/loading.gif" alt="loading" id="loading" />
              <div class="results-container"></div>
            </div><!-- live-search -->
          </div>
        </div>
      </div>
    </div>
  </nav>

  <!-- Submenu -->
  <nav id="od-submenu">
    <div class="container">
      <div class="row">
      </div>
    </div>
  </nav>

  <!-- Disclaimer -->
  <nav id="od-disclaimer">
    <div class="container">
      <div class="row">
        <?php
          $options_msg = get_option('opendev_options');
          if (isset($options_msg['notice_message']) && $options_msg['notice_message'] != ''): ?>
            <div id="notification-message">
              <div class="container">
                <div class="twelve columns">
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

  <!-- Breadcrumb -->
  <nav id="od-breadcrumb">
    <div class="container">
      <div class="row">
        <div class="twelve columns">
        <?php
          if (!is_front_page()) : ?>
            <div id="main-breadcrumb">
              <?php the_breadcrumb(); ?>
            </div>
        <?php
          endif; ?>
        </div>
      </div>
    </div>
  </nav>

  <?php
      if (!wp_is_mobile() && function_exists('button_user_feedback_form')):
          button_user_feedback_form();
      endif; ?>
