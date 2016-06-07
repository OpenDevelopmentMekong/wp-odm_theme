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
  <div id="content" class="container">
    <div class="row">
      <header class="white"id="od-head">
    		<div class="organization">
    			<div class="six columns left_organization">
    				<div class="site-meta">
    					<?php opendev_logo(); ?>
    				</div>
    			</div>
    			<div class="language float-right">
    					<?php
                if (function_exists('qtranxf_generateLanguageSelectCode')) {
                  qtranxf_generateLanguageSelectCode('image');
                } ?>
    				</div>
    			<div class="right_organization">
    				<div class="search">
    					<div id="live-search">
    						  <input type="text" placeholder="<?php _e('Search site... &#128269;', 'opendev');?>" onfocus="this.placeholder=''" onblur="this.placeholder='<?php _e('Search site... &#128269;', 'opendev');?>'" />
    						  <img src="<?php bloginfo('stylesheet_directory');?>/img/loading.gif" alt="loading" id="loading" />
    						<div class="results-container"></div>
    					</div><!-- live-search -->
    				</div>
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

    		<?php
            $options_msg = get_option('opendev_options');
            if (isset($options_msg['notice_message']) && $options_msg['notice_message'] != ''): ?>
              <nav id="notification-message">
                <div class="container">
                  <div class="twelve columns">
                    <div class="notification-message-box">
                      <?php echo $options_msg['notice_message']; ?>
                    </div>
                  </div>
        			  </div>
              </nav>
            <?php endif; ?>
    		<?php
          if (!is_front_page()) : ?>

    		    <nav id="main-breadcrumb">
              <div class="container">
                <div class="twelve columns">
                  <?php the_breadcrumb(); ?>
                </div>
      			   </div>
            </nav>
        <?php
          endif; ?>
    	</header>
    </div>
  </div>

    <?php
        if (!wp_is_mobile() && function_exists('button_user_feedback_form')) {
            button_user_feedback_form();
        }
    ?>
