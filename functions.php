<?php

// Query multisite
require_once STYLESHEETPATH.'/inc/query-multisite.php';

// Theme options
require_once STYLESHEETPATH.'/inc/theme-options.php';

// Topics
require_once STYLESHEETPATH.'/inc/topics.php';

// Announcements
require_once STYLESHEETPATH.'/inc/announcements.php';

// Site updates
require_once STYLESHEETPATH.'/inc/site-updates.php';

// Map category
require_once STYLESHEETPATH.'/inc/layer-category.php';

// summary
require_once STYLESHEETPATH.'/inc/summary.php';

// Live search
require_once STYLESHEETPATH.'/inc/live-search/live-search.php';

// Interactive map
require_once STYLESHEETPATH.'/inc/interactive-map.php';

// Category widget
require_once STYLESHEETPATH.'/inc/category-widget.php';

// Category widget
require_once STYLESHEETPATH.'/inc/odm-taxonomy-widget.php';

// Related resources
require_once STYLESHEETPATH.'/inc/related-resources-widget.php';

// Query resources
require_once STYLESHEETPATH.'/inc/query-resources-widget.php';

// Related recent news
require_once STYLESHEETPATH.'/inc/od-related-recent-news-widget.php';

// Mekong region storms and floods
require_once STYLESHEETPATH.'/inc/mekong-region-storms-and-floods.php';

// Advanced nav
require_once STYLESHEETPATH.'/inc/advanced-navigation.php';

// Advanced nav
require_once STYLESHEETPATH.'/inc/category-walker.php';

$country_name = str_replace('Open Development ', '', get_bloginfo('name'));
define('COUNTRY_NAME', strtolower($country_name));

function opendev_setup_theme()
{
    $gsd = explode('wp-content', get_stylesheet_directory());
    load_theme_textdomain('opendev', $gsd[0].'/wp-content/languages');
    load_theme_textdomain('jeo', $gsd[0].'/wp-content/languages');
    register_sidebar(array(
  'name' => __('Topic sidebar', 'jeo'),
  'id' => 'topic',
  'before_title' => '<h2 class="widget-title">',
  'after_title' => '</h2>',
 ));
    register_sidebar(array(
  'name' => __('Mekong Storms and Floods sidebar', 'opendev'),
  'id' => 'mekong-storm-flood',
  'before_title' => '<h2 class="widget-title">',
  'after_title' => '</h2>',
 ));
    register_sidebar(array(
  'name' => __('Frontpage footer left', 'jeo'),
  'id' => 'frontpage-footer-left',
  'before_title' => '<h2 class="widget-title">',
  'after_title' => '</h2>',
 ));
    register_sidebar(array(
  'name' => __('Frontpage footer right', 'jeo'),
  'id' => 'frontpage-footer-right',
  'before_title' => '<h2 class="widget-title">',
  'after_title' => '</h2>',
 ));
    register_sidebar(array(
  'name' => __('Upcoming footer left', 'jeo'),
  'id' => 'upcoming-footer-left',
  'before_title' => '<h2 class="widget-title">',
  'after_title' => '</h2>',
 ));
    register_sidebar(array(
  'name' => __('Upcoming footer middle', 'jeo'),
  'id' => 'upcoming-footer-middle',
  'before_title' => '<h2 class="widget-title">',
  'after_title' => '</h2>',
 ));
    register_sidebar(array(
  'name' => __('Upcoming footer right', 'jeo'),
  'id' => 'upcoming-footer-right',
  'before_title' => '<h2 class="widget-title">',
  'after_title' => '</h2>',
 ));
}
add_action('after_setup_theme', 'opendev_setup_theme');

function opendev_styles()
{
  $options = get_option('opendev_options');

  $css_base = get_stylesheet_directory_uri().'/css/';

  wp_register_style('webfont-droid-serif', 'https://fonts.googleapis.com/css?family=Droid+Serif:400,700');
  wp_register_style('webfont-opendev', get_stylesheet_directory_uri().'/font/style.css');
  wp_register_style('opendev-base',  $css_base.'opendev.css', array('webfont-droid-serif', 'webfont-opendev'));
  wp_register_style('mCustomScrollbar',  $css_base.'jquery.mCustomScrollbar.min.css?ver=3.1.12');
  wp_register_style('opendev-cambodia',  $css_base.'cambodia.css');
  wp_register_style('opendev-thailand',  $css_base.'thailand.css');
  wp_register_style('opendev-laos',  $css_base.'laos.css');
  wp_register_style('opendev-myanmar',  $css_base.'myanmar.css');
  wp_register_style('opendev-vietnam',  $css_base.'vietnam.css');
  wp_register_style('nav-concept',  $css_base.'nav_concept.css');
  wp_register_style('map-explorer',  $css_base.'map_explorer.css');
  wp_register_style('table-pages',  $css_base.'table-pages.css');
  wp_register_style('elc',  $css_base.'elc.css');
  wp_register_style('forest-cover',  $css_base.'forest-cover.css');
  wp_register_style('responsive',  $css_base.'responsive.css');

  wp_enqueue_style('mCustomScrollbar');
  wp_enqueue_style('opendev-base');
  wp_enqueue_style('nav-concept');
  wp_enqueue_style('table-pages');
  wp_enqueue_style('map-explorer');
  wp_enqueue_style('elc');
  wp_enqueue_style('forest-cover');
  wp_enqueue_style('responsive');

  if ($options['style']) {
      wp_enqueue_style('opendev-'.$options['style']);
  }
}
add_action('wp_enqueue_scripts', 'opendev_styles', 15);

function opendev_jeo_scripts()
{
  wp_dequeue_script('jeo-site');
  wp_enqueue_script('jquery-isotope');
  wp_register_script('twttr', 'https://platform.twitter.com/widgets.js');
  $site_name = str_replace('Open Development ', '', get_bloginfo('name'));
  // custom marker system
  global $jeo_markers;
  wp_deregister_script('jeo.markers');
  wp_register_script('jeo.markers', get_stylesheet_directory_uri().'/js/markers.js', array('jeo', 'underscore', 'twttr'), '0.3.17', true);
  wp_localize_script('jeo.markers', 'opendev_markers', array(
    'ajaxurl' => admin_url('admin-ajax.php'),
    'query' => $jeo_markers->query(),
    'stories_label' => __('stories', 'opendev'),
    'home' => (is_home() && !is_paged() && !$_REQUEST['opendev_filter_']),
    'copy_embed_label' => __('Copy the embed code', 'opendev'),
    'share_label' => __('Share', 'opendev'),
    'print_label' => __('Print', 'opendev'),
    'embed_base_url' => home_url('/embed/'),
    'share_base_url' => home_url('/share/'),
    'marker_active' => array(
    'iconUrl' => get_stylesheet_directory_uri().'/img/marker_active_'.$site_name.'.png',
    'iconSize' => array(26, 30),
    'iconAnchor' => array(13, 30),
    'popupAnchor' => array(0, -40),
    'markerId' => 'none',
  ),
   'site_url' => home_url('/'),
   'read_more_label' => __('Read more', 'opendev'),
   'lightbox_label' => array(
   'slideshow' => __('Open slideshow', 'opendev'),
   'videos' => __('Watch video gallery', 'opendev'),
   'video' => __('Watch video', 'opendev'),
   'images' => __('View image gallery', 'opendev'),
   'image' => __('View fullscreen image', 'opendev'),
   'infographic' => __('View infographic', 'opendev'),
   'infographics' => __('View infographics', 'opendev'),
  ),
   'enable_clustering' => jeo_use_clustering() ? true : false,
   'default_icon' => jeo_formatted_default_marker(),
  ));

  if (is_home()) {
      wp_enqueue_script('opendev-sticky', get_stylesheet_directory_uri().'/js/sticky-posts.js', array('jeo.markers', 'jquery'), '0.1.2');
  }
  wp_enqueue_script('opendev-mCustomScrollbar', get_stylesheet_directory_uri().'/js/jquery.mCustomScrollbar.concat.min.js', array('jquery'), '3.1.12');
 //wp_enqueue_script('opendev-interactive-map', get_stylesheet_directory_uri() . '/inc/interactive-map.js', array('jeo'));
}
add_action('wp_enqueue_scripts', 'opendev_jeo_scripts', 100);

function nav_concept_scripts()
{

	wp_register_style('dataTables-css', get_stylesheet_directory_uri().'/lib/dataTables/css/jquery.dataTables.min.css');
  wp_register_style('dataTables-css', get_stylesheet_directory_uri().'/lib/dataTables/css/responsive.dataTables.css');

  wp_enqueue_script('cookie-handler', get_stylesheet_directory_uri().'/js/cookie.js', array('jquery'), '0.1.2');
  wp_enqueue_script('data-tables-js', get_stylesheet_directory_uri().'/lib/dataTables/js/jquery.dataTables.min.js', array('jquery'), '1.10.10');
	wp_enqueue_script('data-tables-responsive', get_stylesheet_directory_uri().'/lib/dataTables/js/dataTables.responsive.js', array('data-tables-js'), '1.10.10');
  wp_enqueue_script('cartodb-config', get_stylesheet_directory_uri().'/inc/js/cartodb-config.js', null, '1.0.0');

	wp_enqueue_style('dataTables-css');
}

add_action('wp_enqueue_scripts', 'nav_concept_scripts', 100);

// hook into the init action and call create_book_taxonomies when it fires
add_action('init', 'create_news_source_taxonomies', 0);
// create two taxonomies, genres and writers for the post type "book"
function create_news_source_taxonomies()
{
    // Add new taxonomy, make it hierarchical (like categories)
    $labels = array(
        'name' => _x('News Sources', 'taxonomy general name'),
        'singular_name' => _x('News Source', 'taxonomy singular name'),
        'search_items' => __('Search News Source'),
        'all_items' => __('All News Sources'),
        'parent_item' => __('Parent News Source'),
        'parent_item_colon' => __('Parent News Source:'),
        'edit_item' => __('Edit News Source'),
        'update_item' => __('Update News Source'),
        'add_new_item' => __('Add New News Source'),
        'new_item_name' => __('New News Source Name'),
        'menu_name' => __('News Source'),
    );

    $args = array(
        'hierarchical' => true,
        'labels' => $labels,
        'show_ui' => true,
        'show_admin_column' => true,
        'query_var' => true, /* ,
        'rewrite'           => array( 'slug' => 'news_source' ) */
    );

    register_taxonomy('news_source', array('post'), $args);
}
// custom marker data
function opendev_marker_data($data, $post)
{
    global $post;

    $permalink = $data['url'];

    if (function_exists('qtranxf_getLanguage')) {
        $permalink = add_query_arg(array('lang' => qtranxf_getLanguage()), $permalink);
    }

    $data['permalink'] = $permalink;
    $data['url'] = $permalink;
    $data['content'] = get_the_excerpt();
    if (get_post_meta($post->ID, 'geocode_zoom', true)) {
        $data['zoom'] = get_post_meta($post->ID, 'geocode_zoom', true);
    }

 // thumbnail
 $data['thumbnail'] = opendev_get_thumbnail();

    return $data;
}
add_filter('jeo_marker_data', 'opendev_marker_data', 10, 2);

function opendev_get_thumbnail($post_id = false)
{
    global $post;
    $post_id = $post_id ? $post_id : $post->ID;
    $thumb_src = wp_get_attachment_image_src(get_post_thumbnail_id(), 'post-thumb');
    if ($thumb_src) {
        return $thumb_src[0];
    } else {
        return get_post_meta($post->ID, 'picture', true);
    }
}

function opendev_logo()
{
  $name = "Mekong";
  if (is_multisite()) {
    $sites = wp_get_sites();
    if (!empty($sites)) {
      $current = get_current_blog_id();
      $name = str_replace('Open Development ', '', get_bloginfo('name'));
    }
  }
  $logo = opendev_get_logo();
  if ($logo) {
      $name = $logo;
  }?>
  <h1>
   <a href="<?php echo home_url('/');?>" title="<?php bloginfo('name');?>">
    <span class="icon-od-logo"></span>
    Op<sup>e</sup>nDevelopment
   </a>
  </h1>
  <?php
  echo '<div class="ms-dropdown-title">';
  echo '<h2 class="side-title">'.$name.'<span class="icon-arrow-down5"></span></h2>';
  echo '</div>';
}

function opendev_social_apis()
{

 // Facebook
 ?>
 <div id="fb-root"></div>
 <script>(function(d, s, id) {
   var js, fjs = d.getElementsByTagName(s)[0];
   if (d.getElementById(id)) return;
   js = d.createElement(s); js.id = id;
   js.src = "//connect.facebook.net/en_US/all.js#xfbml=1&appId=1413694808863403";
   fjs.parentNode.insertBefore(js, fjs);
 }(document, 'script', 'facebook-jssdk'));</script>
 <?php

 // Twitter
 ?>
 <script>!function(d,s,id){var js,fjs=d.getElementsByTagName(s)[0];if(!d.getElementById(id)){js=d.createElement(s);js.id=id;js.src="https://platform.twitter.com/widgets.js";fjs.parentNode.insertBefore(js,fjs);}}(document,"script","twitter-wjs");</script>
 <?php

 // Google Plus
 ?>
 <script type="text/javascript">
   (function() {
     var po = document.createElement('script'); po.type = 'text/javascript'; po.async = true;
     po.src = 'https://apis.google.com/js/plusone.js';
     var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(po, s);
   })();
 </script>
 <?php

}
add_action('wp_footer', 'opendev_social_apis');

function opendev_ms_nav()
{
    ?>
  <link rel="stylesheet" href="https://code.jquery.com/ui/1.11.4/themes/smoothness/jquery-ui.css">
  <script src="https://code.jquery.com/ui/1.11.4/jquery-ui.js"></script>
  <script>
  jQuery(function($) {
         $('.ms-nav li a').tooltip({
            position: {
                using: function( position, feedback ) {
                    $( this ).css( position );
                    $( "<div>" )
                    .addClass( "arrow" )
                    .addClass( feedback.vertical )
                    .addClass( feedback.horizontal )
                    .appendTo( this );
                }
            }
        });
        $('#menu-header-menu li a').tooltip({
		  position: {
			using: function( position, feedback ) {
			  $( this ).css( position );
			  $( "<div>" )
				.addClass( "arrow" )
				.addClass( feedback.vertical )
				.addClass( feedback.horizontal )
				.appendTo( this );
			}
		  }
		});

        $('#intro-texts p a.tooltip').tooltip({
		  track: true,
          position: {
			using: function( position, feedback ) {
			  $( this ).css( position );
			  $( "<div>" )
				.addClass( "arrow" )
				.addClass( feedback.vertical )
				.addClass( feedback.horizontal )
				.appendTo( this );
			}
		  }
		});

		<?php
            $options_msg = get_option('opendev_options');
    $m = array(1, 2, 3);
    foreach ($m as $i) {
        if (isset($options_msg['tooltip_message_'.$i])) {
            $tooltip = $options_msg['tooltip_message_'.$i];
            if (isset($tooltip['menu_name']) && $tooltip['menu_name'] != 'Tooltip') {
                if (isset($tooltip['message']) && $tooltip['message']) {
                    // Looking for menu item that match with menu_name of tooltip
                             $menu_obj = get_term_by('name', 'Header Menu', 'nav_menu');
                    $menu_items = wp_get_nav_menu_items($menu_obj->term_id);
                    foreach ((array) $menu_items as $key => $menu_item) {
                        if ($menu_item->menu_item_parent != 0) {
                            continue;
                        }
                        $title = $menu_item->title;
                        $menu_id = $menu_item->ID;
                        if (strtolower(trim($title)) == strtolower(trim($tooltip['menu_name']))) {
                            ?>
                              $('#menu-header-menu li.menu-item-<?php echo $menu_id;?> a').attr( "title", "<?php echo trim($tooltip['message']);?>");
                            <?php   break;
                        }
                    } //foreach
                } //if isset($tooltip['message'])
            }//if isset($tooltip['message'])
            elseif (isset($tooltip['menu_name']) && $tooltip['menu_name'] == 'Tooltip') {
                if (isset($tooltip['message']) && $tooltip['message']) {
                    ?>
                  $('#menu-header-menu li.tooltip a').attr( "title", "<?php echo trim($tooltip['message']);?>");
        <?php

                }
            }
        }//isset($options_msg['tooltip_message_' . $i])
    }
    ?>
           <?php //page under construction
            if (isset($options_msg['message_page_construction']) && $options_msg['message_page_construction'] != '') {
                ?>
                 $('#intro-texts p a.tooltip').removeAttr('href');
                 $('#intro-texts p a.tooltip').attr( "title", "<?php echo trim($options_msg['message_page_construction']);?>");
          <?php

            }
    ?>
  });
  </script>
<?php
 if (is_multisite()) {
     $sites = wp_get_sites();
     if (!empty($sites) && count($sites) > 1) {
         $current = get_current_blog_id();
   // $name = str_replace('Open Development ', '', get_bloginfo('name'));
   // $logo = opendev_get_logo();
   // if($logo)
   // 	$name = $logo;
   ?>
   <ul class="ms-nav">
    <?php
    foreach ($sites as $site) {
        $details = get_blog_details($site['blog_id']);
        $name = str_replace('Open Development ', '', $details->blogname);
        $siteurl = $details->siteurl;
        switch_to_blog($site['blog_id']);
        ?>
     <li class="site-item">
     <?php
     $options = get_option('opendev_options');
        if (isset($options['site_in_development']) && ($options['site_in_development'] == 'true')) {
            ?>
      <a href="#"<?php if ($current == $site['blog_id']) {
    echo ' class="current-site-'.strtolower($name).'"';
}
            ?> title="<?php if ($options['message_construction'] != '') {
    _e($options['message_construction'], 'opendev');
} else {
    _e('Site coming soon.', 'opendev');
}
            ?>"><?php _e($name, 'opendev');
            ?></a>
     <?php

        } else {
            ?>
      <a href="<?php echo $siteurl;
            ?>"<?php if ($current == $site['blog_id']) {
    echo ' class="current-site-'.strtolower($name).'"';
}
            ?>><?php _e($name, 'opendev');
            ?></a>
      <?php
    if (isset($options['dropbox_menu']) && ($options['dropbox_menu'] == 'on')) {
        ?>
      <div class="sub-menu">
       <ul class="first-menu">
        <li data-content="news"><a href="<?php echo $siteurl;
        ?>">
         <span class="icon-news"></span> <?php _e('News', 'opendev');
        ?>
        </a></li>
        <li data-content="issues"><a href="<?php echo get_post_type_archive_link('topic');
        ?>">
         <span class="icon-text-document"></span> <?php _e('Topics', 'opendev');
        ?>
        </a></li>
        <li data-content="maps"><a href="<?php echo get_post_type_archive_link('map');
        ?>">
         <span class="icon-map"></span> <?php _e('Maps', 'opendev');
        ?>
        </a></li>
        <?php
        $data_page_id = opendev_get_data_page_id();
        if ($data_page_id) :
         ?>
         <li data-content="data"><a href="<?php echo get_permalink($data_page_id);
        ?>">
          <span class="icon-archive"></span> <?php _e('Data', 'opendev');
        ?>
         </a></li>
         <?php
        endif;
        ?>
       </ul>
       <div class="content">

        <?php query_posts(array('posts_per_page' => 3, 'without_map_query' => true));
        ?>
        <?php if (have_posts()) : ?>
         <div class="news content-item">
          <h2><?php _e('Latest news', 'opendev');
        ?></h2>
          <ul>
           <?php while (have_posts()) : the_post();
        ?>
            <li>
             <h3><a href="<?php the_permalink();
        ?>"><?php the_title();
        ?></a></h3>
             <?php the_excerpt();
        ?>
            </li>
           <?php endwhile;
        ?>
          </ul>
         </div>
        <?php endif;
        ?>
        <?php wp_reset_query();
        ?>

        <?php query_posts(array('post_type' => 'topic', 'posts_per_page' => 3, 'without_map_query' => true));
        ?>
        <?php if (have_posts()) : ?>
         <div class="issues content-item">
          <h2><?php _e('Latest topics', 'opendev');
        ?></h2>
          <ul>
           <?php while (have_posts()) : the_post();
        ?>
            <li>
             <h3><a href="<?php the_permalink();
        ?>"><?php the_title();
        ?></a></h3>
             <?php the_excerpt();
        ?>
            </li>
           <?php endwhile;
        ?>
          </ul>
         </div>
        <?php endif;
        ?>
        <?php wp_reset_query();
        ?>

        <?php query_posts(array('post_type' => 'map', 'posts_per_page' => 3, 'without_map_query' => true));
        ?>
        <?php if (have_posts()) : ?>
         <div class="maps content-item">
          <h2><?php _e('Latest maps', 'opendev');
        ?></h2>
          <ul>
           <?php while (have_posts()) : the_post();
        ?>
            <li>
             <h3><a href="<?php the_permalink();
        ?>"><?php the_title();
        ?></a></h3>
             <?php the_excerpt();
        ?>
            </li>
           <?php endwhile;
        ?>
          </ul>
         </div>
        <?php endif;
        ?>
        <?php wp_reset_query();
        ?>

       </div>
      </div><!--sub-menu-->
     <?php

    } //if $options['dropbox_menu'] ?>
    <?php

        } // if $options['site_in_development'] ?>
     </li>
     <?php
     restore_current_blog();
    }
         ?>
   </ul>
   <script type="text/javascript">
    jQuery(document).ready(function($) {
     $('.ms-nav').each(function() {
      $nav = $(this);

      $nav.find('.site-item').each(function() {

       var $siteNav = $(this);

       $siteNav.find('.content-item').hide();

       $siteNav.find('.content-item:first-child').show();
       $siteNav.find('.first-menu li:first-child').addClass('active');

       $siteNav.find('.first-menu li').on('mouseover', function() {

        var $content = $siteNav.find('.content-item.' + $(this).data('content'));

        if($content.length) {

         $siteNav.find('.first-menu li').removeClass('active');
         $(this).addClass('active');

         $siteNav.find('.content-item').hide();
         $siteNav.find('.content-item.' + $(this).data('content')).show();

        }
       });

      });
     });
    });
   </script>
   <?php

     }
 }
}

function opendev_wpckan_post_types()
{
    return array('post', 'page', 'topic', 'layer');
}
add_filter('wpckan_post_types', 'opendev_wpckan_post_types');

if (!function_exists('IsNullOrEmptyString')) {
    function IsNullOrEmptyString($question)
    {
        return !isset($question) || @trim($question) === '';
    }
}

function opendev_get_related_datasets($atts = false)
{
    if (!$atts) {
        $atts = array();
    }

    if (!isset($atts['post_id'])) {
        $atts['post_id'] = get_the_ID();
    }

    $related_datasets_json = get_post_meta($atts['post_id'], 'wpckan_related_datasets', true);
    $related_datasets = array();
    if (!IsNullOrEmptyString($related_datasets_json)) {
        $related_datasets = json_decode($related_datasets_json, true);
    }

    $dataset_array = array();

    foreach ($related_datasets as $dataset) {
        $dataset_atts = array('id' => $dataset['dataset_id']);
        try {
            array_push($dataset_array, wpckan_api_get_dataset($dataset_atts));
        } catch (Exception $e) {
            wpckan_log($e->getMessage());
        }
        if (array_key_exists('limit', $atts) && (count($dataset_array) >= (int) ($atts['limit']))) {
            break;
        }
    }

    return $dataset_array;
}

function opendev_wpckan_api_query_datasets($atts)
{
    if (is_null(wpckan_get_ckan_settings())) {
        wpckan_api_settings_error('wpckan_api_query_datasets');
    }

    if (!isset($atts['query'])) {
        wpckan_api_call_error('wpckan_api_query_datasets', null);
    }

    try {
        $settings = wpckan_get_ckan_settings();
        $ckanClient = CkanClient::factory($settings);
        $commandName = 'PackageSearch';
        $arguments = array('q' => $atts['query']);

        if (isset($atts['limit'])) {
            $arguments['rows'] = (int) $atts['limit'];

            if (isset($atts['page'])) {
                $page = (int) $atts['page'];
                if ($page > 0) {
                    $arguments['start'] = (int) $atts['limit'] * ($page - 1);
                }
            }
        }

        $filter = null;
        if (isset($atts['organization'])) {
            $filter = $filter.'+owner_org:'.$atts['organization'];
        }
        if (isset($atts['organization']) && isset($atts['group'])) {
            $filter = $filter.' ';
        }
        if (isset($atts['group'])) {
            $filter = $filter.'+groups:'.$atts['group'];
        }
        if (!is_null($filter)) {
            $arguments['fq'] = $filter;
        }
        $command = $ckanClient->getCommand($commandName, $arguments);
        $response = $command->execute();

        wpckan_log('wpckan_api_query_datasets commandName: '.$commandName.' arguments: '.print_r($arguments, true).' settings: '.print_r($settings, true));

        if ($response['success'] == false) {
            wpckan_api_call_error('wpckan_api_query_datasets', null);
        }
    } catch (Exception $e) {
        wpckan_api_call_error('wpckan_api_query_datasets', $e->getMessage());
    }

    return $response;
}

// Disable mousewheel zoom by default
function opendev_map_data($data)
{
    $data['disable_mousewheel'] = true;

    return $data;
}
add_filter('jeo_map_data', 'opendev_map_data');
add_filter('jeo_mapgroup_data', 'opendev_map_data');

function opendev_custom_admin_css()
{
    ?>
 <style>
  .handlers.map-setting { display: none !important; }
 </style>
 <?php

}
add_action('admin_footer', 'opendev_custom_admin_css');

function opendev_search_pre_get_posts($query)
{
    if (!is_admin() && ($query->is_search || get_query_var('opendev_advanced_nav') || $query->is_tax || $query->is_category || $query->is_tag)) {
        $query->set('post_type', get_post_types(array('public' => true)));
    }
}
add_action('pre_get_posts', 'opendev_search_pre_get_posts');

function opendev_category_pre_get_posts($query)
{
    if ($query->is_category) {
        $post_type = isset($_GET['post_type']) ? $_GET['post_type'] : 'post';
        $query->set('post_type', array($post_type));
    }
}
add_action('pre_get_posts', 'opendev_category_pre_get_posts', 20, 1);

function opendev_posts_clauses_join($join)
{
    global $wpdb;

    $join = " INNER JOIN {$wpdb->postmeta} m_maps ON ({$wpdb->posts}.ID = m_maps.post_id) ";

    return $join;
}
//add_filter('jeo_posts_clauses_join', 'opendev_posts_clauses_join');

function opendev_posts_clauses_where($where)
{
    $map_id = jeo_get_map_id();

    $where = '';

 // MAP
 if (get_post_type($map_id) == 'map') {
     $where = " AND ( m_maps.meta_key = 'maps' AND CAST(m_maps.meta_value AS CHAR) = '{$map_id}' ) ";

 // MAPGROUP
 } elseif (get_post_type($map_id) == 'map-group') {
     $groupdata = get_post_meta($map_id, 'mapgroup_data', true);

     if (isset($groupdata['maps']) && is_array($groupdata['maps'])) {
         $where = ' AND ( ';

         $size = count($groupdata['maps']);
         $i = 1;

         foreach ($groupdata['maps'] as $m) {
             $c_map_id = $m['id'];

             $where .= " ( m_maps.meta_key = 'maps' AND CAST(m_maps.meta_value AS CHAR) = '{$c_map_id}' ) ";

             if ($i < $size) {
                 $where .= ' OR ';
             }

             ++$i;
         }

         $where .= ' ) ';
     }
 }

    return $where;
}
//add_filter('jeo_posts_clauses_where', 'opendev_posts_clauses_where');

function opendev_ignore_sticky($query)
{
    if ($query->is_main_query()) {
        $query->set('ignore_sticky_posts', true);
    }
}
add_action('pre_get_posts', 'opendev_ignore_sticky');

function my_mce_buttons_2($buttons)
{
    array_unshift($buttons, 'fontselect', 'fontsizeselect');
    $buttons[] = 'superscript';
    $buttons[] = 'subscript';

    return $buttons;
}
add_filter('mce_buttons_2', 'my_mce_buttons_2');

/****Breadcrumbs****/
//Get parent of category
function get_all_parent_category($sub_cat_id, $post_type, $separator = '', $current_page_name = '')
{
    $parent_cat = get_category_parents($sub_cat_id, false);
    $parent_cats = explode('/', substr($parent_cat, 0, -1));
    foreach ($parent_cats as $p_cat) {
        $page_title = $p_cat;
        $page_slug = strtolower(preg_replace('/\s+/', '-', $p_cat));
        $topic_page_exist = get_topic_page_link($page_slug, $post_type);

        echo '<li class="item-topic item-topic-'.$topic_page_exist->ID.' item-topic-'.$page_slug.'">';
        if ($topic_page_exist) {
            $page_name_title = trim(strtolower($page_title));
            if ($page_name_title == $current_page_name) {
                echo '<div class="bread-current bread-current-'.$topic_page_exist->ID.'" title="'.$page_title.'">';
            } else {
                echo '<a class="bread-topic bread-topic-'.$topic_page_exist->ID.' bread-topic-'.$page_slug.'" href="'.get_permalink($topic_page_exist).'" title="'.$page_title.'">';
            }
        }
        echo $page_title;

        if ($topic_page_exist) {
            if ($page_slug == $current_page_name) {
                echo '</div>';
            } else {
                echo '</a>';
            }
        }
        echo '</li>';
        echo '<li class="separator separator-topic separator-'.$topic_page_exist->ID.'"> '.$separator.' </li>';
    }
}
/***
 *
 *  Get the topic page link by the name of category as it was assumpted the same name
 */
//$name is the catogory name or slug
function get_topic_page_link($name, $post_type)
{
    $topical_page_url = get_page_by_path($name, OBJECT, $post_type);
  //chekc if topic page exist
         if ($topical_page_url) {
             return  $topical_page_url;
         } else {
             return false;
         }
}

 // Creating Breadcrumbs for the site
function the_breadcrumb()
{
    // Settings
    $separator = ''; //'&gt;';
    $id = 'breadcrumbs';
    $class = 'breadcrumbs';
    $home_title = 'Home';

    // Get the query & post information
    global $post,$wp_query;
    $category = get_the_category();

    // Build the breadcrums
    echo '<ul id="'.$id.'" class="'.$class.'">';
    // Do not display on the homepage
    if (!is_front_page()) {
        // Home page
        echo '<li class="item-home"><a class="bread-link bread-home" href="'.get_home_url().'" title="'.$home_title.'">';
        _e('Home', 'opendev');
        echo '</a></li>';
        echo '<li class="separator separator-home"> '.$separator.' </li>';

        if (is_single()) {
            //Single post of post type "Topic"
            if (get_post_type(get_the_ID())  == 'topic') {
                $post_type_topic = get_post_type(get_the_ID());
                $cats = get_the_category(get_the_ID());
                if ($cats) {
                    // if post is in this category
                    foreach ($cats as $cat) {
                        if (in_category($cat->term_id)) {
                            //$page_slug = strtolower(preg_replace('/\s+/', '-', get_the_title()));
                     $page_title = trim(strtolower(get_the_title()));
                            $cat_name = trim(strtolower($cat->name));
                     // Which Category and Post have the same name
                            if ($cat_name == $page_title) {
                                $cat_id = $cat->term_id;
                                get_all_parent_category($cat_id, $post_type_topic, $separator, $page_title);
                            }
                        }
                    }//end foreach
                } else { //if topic page is not categoried or the topic name is different from the category
                    echo '<li class="item-current item-'.$post->ID.'"><strong class="bread-current bread-'.$post->ID.'" title="'.get_the_title().'">'.get_the_title().'</strong></li>';
                }
            } else {
                // Single post (Only display the first category)
                /* echo '<li class="item-cat item-cat-' . $category[0]->term_id . ' item-cat-' . $category[0]->category_nicename . '"><a class="bread-cat bread-cat-' . $category[0]->term_id . ' bread-cat-' . $category[0]->category_nicename . '" href="' . get_category_link($category[0]->term_id ) . '" title="' . $category[0]->cat_name . '">' . $category[0]->cat_name . '</a></li>'; */
                //echo '<li class="separator separator-' . $category[0]->term_id . '"> ' . $separator . ' </li>';
                echo '<li class="item-current item-'.$post->ID.'"><strong class="bread-current bread-'.$post->ID.'" title="'.get_the_title().'">'.get_the_title().'</strong></li>';
            }
        } elseif (is_category()) {
            // Category page
            echo '<li class="item-current item-cat-'.$category[0]->term_id.' item-cat-'.$category[0]->category_nicename.'"><strong class="bread-current bread-cat-'.$category[0]->term_id.' bread-cat-'.$category[0]->category_nicename.'">'.$category[0]->cat_name.'</strong></li>';
        } elseif (is_page()) {
            // Standard page
            if ($post->post_parent) {
                // If child page, get parents
                $anc = get_post_ancestors($post->ID);
                // Get parents in the right order
                $anc = array_reverse($anc);

                // Parent page loop
                foreach ($anc as $ancestor) {
                    $parents .= '<li class="item-parent item-parent-'.$ancestor.'"><a class="bread-parent bread-parent-'.$ancestor.'" href="'.get_permalink($ancestor).'" title="'.get_the_title($ancestor).'">'.get_the_title($ancestor).'</a></li>';
                    $parents .= '<li class="separator separator-'.$ancestor.'"> '.$separator.' </li>';
                }

                // Display parent pages
                echo $parents;

                // Current page
                echo '<li class="item-current item-'.$post->ID.'"><strong title="'.get_the_title().'"> '.get_the_title().'</strong></li>';
            } else {

                // Just display current page if not parents
                echo '<li class="item-current item-'.$post->ID.'"><strong class="bread-current bread-'.$post->ID.'"> '.get_the_title().'</strong></li>';
            }
        } elseif (is_tag()) {

            // Tag page

            // Get tag information
            $term_id = get_query_var('tag_id');
            $taxonomy = 'post_tag';
            $args = 'include='.$term_id;
            $terms = get_terms($taxonomy, $args);

            // Display the tag name
            echo '<li class="item-current item-tag-'.$terms[0]->term_id.' item-tag-'.$terms[0]->slug.'"><strong class="bread-current bread-tag-'.$terms[0]->term_id.' bread-tag-'.$terms[0]->slug.'">'.$terms[0]->name.'</strong></li>';
        } elseif (is_day()) {

            // Day archive

            // Year link
            echo '<li class="item-year item-year-'.get_the_time('Y').'"><a class="bread-year bread-year-'.get_the_time('Y').'" href="'.get_year_link(get_the_time('Y')).'" title="'.get_the_time('Y').'">'.get_the_time('Y').' Archives</a></li>';
            echo '<li class="separator separator-'.get_the_time('Y').'"> '.$separator.' </li>';

            // Month link
            echo '<li class="item-month item-month-'.get_the_time('m').'"><a class="bread-month bread-month-'.get_the_time('m').'" href="'.get_month_link(get_the_time('Y'), get_the_time('m')).'" title="'.get_the_time('M').'">'.get_the_time('M').' Archives</a></li>';
            echo '<li class="separator separator-'.get_the_time('m').'"> '.$separator.' </li>';

            // Day display
            echo '<li class="item-current item-'.get_the_time('j').'"><strong class="bread-current bread-'.get_the_time('j').'"> '.get_the_time('jS').' '.get_the_time('M').' Archives</strong></li>';
        } elseif (is_month()) {

            // Month Archive

            // Year link
            echo '<li class="item-year item-year-'.get_the_time('Y').'"><a class="bread-year bread-year-'.get_the_time('Y').'" href="'.get_year_link(get_the_time('Y')).'" title="'.get_the_time('Y').'">'.get_the_time('Y').' Archives</a></li>';
            echo '<li class="separator separator-'.get_the_time('Y').'"> '.$separator.' </li>';

            // Month display
            echo '<li class="item-month item-month-'.get_the_time('m').'"><strong class="bread-month bread-month-'.get_the_time('m').'" title="'.get_the_time('M').'">'.get_the_time('M').' Archives</strong></li>';
        } elseif (is_year()) {

            // Display year archive
            echo '<li class="item-current item-current-'.get_the_time('Y').'"><strong class="bread-current bread-current-'.get_the_time('Y').'" title="'.get_the_time('Y').'">'.get_the_time('Y').' Archives</strong></li>';
        } elseif (is_author()) {
            // Auhor archive
            // Get the author information
            global $author;
            $userdata = get_userdata($author);

            // Display author name
            echo '<li class="item-current item-current-'.$userdata->user_nicename.'"><strong class="bread-current bread-current-'.$userdata->user_nicename.'" title="'.$userdata->display_name.'">'.'Author: '.$userdata->display_name.'</strong></li>';
        } elseif (get_query_var('paged')) {

            // Paginated archives
            echo '<li class="item-current item-current-'.get_query_var('paged').'"><strong class="bread-current bread-current-'.get_query_var('paged').'" title="Page '.get_query_var('paged').'">'.__('Page').' '.get_query_var('paged').'</strong></li>';
        } elseif (is_search()) {

            // Search results page
            echo '<li class="item-current item-current-'.get_search_query().'"><strong class="bread-current bread-current-'.get_search_query().'" title="Search results for: '.get_search_query().'">Search results for: '.get_search_query().'</strong></li>';
        } elseif (is_404()) {
            // 404 page
            echo '<li>'.'Error 404'.'</li>';
        } elseif (is_post_type_archive()) {
            $post_type = get_post_type();
            if ($post_type) {
                $post_type_data = get_post_type_object($post_type);
                $post_type_slug = $post_type_data->rewrite['slug'];
            }
            echo '<li class="item-current item-current-'.$post_type_slug.'"> ';
            echo '<div class="bread-current bread-current-'.$post_type_slug.'">';
                //echo '<a class="bread-link bread-posttype-archive" href="' . get_post_type_archive_link($post_type) . '">';
                echo post_type_archive_title();
               //echo  '</a>';
            echo '</div>';
            echo '</li>';
        }
    }
    echo '</ul>';
}
 /****end Breadcrumb**/

 //to set get_the_excerpt() limit words
 function excerpt($num, $read_more="") {
   $limit = $num+1;
   $excerpt = explode(' ', get_the_excerpt(), $limit);
   array_pop($excerpt);
   $excerpt_string = implode(" ", $excerpt);
   $excerpt_hidden_space = explode('?', $excerpt_string, $limit);
   array_pop($excerpt_hidden_space);
   $$excerpt_string = implode("?", $excerpt_hidden_space) ;
   $excerpt_words = $excerpt_string. " ...";
   if ($read_more !=""){
    $color_name = strtolower(str_replace('Open Development ', '', get_bloginfo('name')))."-color";
    $excerpt_words .=  " (<a href='" .get_permalink($post->ID) ." ' class='".$color_name."'>". __($read_more,"opendev")."</a>)";
   }
         return $excerpt_words;
 }

 function get_pages_templates_for_othersites($pages_templates) {
     $templates = get_page_templates();
 	foreach ( $templates as $template_name => $template_filename ) {
 	    $template_files = explode ("/", $template_filename);
 		if(count($template_files) > 1){
 			if (strtolower($template_files[0]) != COUNTRY_NAME){
 				$template_need_to_hide[] = $template_filename;
 				unset( $pages_templates[$template_filename] );
 			}
 		}
 	}
 	return $pages_templates;
 }

 function hide_other_country_page_template ($pages_templates) {
     return $pages_templates;
 }
 add_filter( 'theme_page_templates', 'hide_other_country_page_template' );

/**
 * Allow embed iframe.
 *****/
function add_iframe($initArray)
{
    $initArray['extended_valid_elements'] = 'iframe[id|class|title|style|align|frameborder|height|longdesc|marginheight|marginwidth|name|scrolling|src|width|allowtransparency|allowfullscreen|webkitallowfullscreen|mozallowfullscreen|oallowfullscreen|msallowfullscreen]';
    $initArray['extended_valid_elements_div'] = 'div[id|style]';
    return $initArray;
}
// this function alters the way the WordPress editor filters your code
add_filter('tiny_mce_before_init', 'add_iframe');

//###############
// Remove WordPress Auto br and p tags
//###############
remove_filter('the_content', 'wpautop');
remove_filter('the_excerpt', 'wpautop');
// function aus advanced tinymce plugin
if (!function_exists('tmce_replace')) {
    function tmce_replace()
    {
        $tadv_options = get_option('tadv_options', array());
        $tadv_plugins = get_option('tadv_plugins', array());

        ?>
        <script type="text/javascript">
            if ( typeof(jQuery) != 'undefined' ) {
              jQuery('body').bind('afterPreWpautop', function(e, o){
                o.data = o.unfiltered
                .replace(/caption\]\ +?<\/object>/g, function(a) {
                  return a.replace(/[\r\n]+/g, ' ');
                });
              }).bind('afterWpautop', function(e, o){
                o.data = o.unfiltered;
              });
            }
        </script>
<?php

    }//end function
  add_action('after_wp_tiny_mce', 'tmce_replace');
}
// eof advanced tinymce plugin
// http://tinymce.moxiecode.com/wiki.php/Configuration
function teslina_tinymce_config($init)
{
    // Change code cleanup/content filtering config
    // Don't remove line breaks
    $init['remove_linebreaks'] = false;
    // Convert newline characters to BR tags
    //$init['convert_newlines_to_brs'] = true;
    //$init['force_br_newlines '] = true;

    // With this option set to false, the line breaks are stripped from the HTML source.
    $init['apply_source_formatting'] = true;
    // Preserve tab/space whitespace
    $init['preformatted'] = true;
    // Do not remove redundant BR tags
    $init['remove_redundant_brs'] = false;

    return $init;
}
add_filter('tiny_mce_before_init', 'teslina_tinymce_config');

function setTransitionCookies($user_data, $limit = 4096, $cookie_name = 'odm_transition_data')
{
  // retrieve old cookie
  // base64 encode and put into json
  // $user_data = base64_encode(json_encode($user_data));
  $user_data = json_encode($user_data);
  // $domain = ($_SERVER['HTTP_HOST'] != 'localhost') ? $_SERVER['HTTP_HOST'] : false;
  //$domain = '192.168.33.10';
  setcookie($cookie_name, $user_data, (time() + 3600), '/');
}

function buildTopTopicNav($lang)
{
    $navigation_vocab = @file_get_contents(get_stylesheet_directory().'/odm-taxonomy/top_topics/top_topics_multilingual.json');
    if ($navigation_vocab){
      $json_a = json_decode($navigation_vocab, true);

      // get Top Topic Names
      foreach ($json_a as $key => $value) {
          echo '<ul>'.$value['titles'][$lang].'</ul>';
          // get entries
          foreach ($json_a[$key]['children'] as $child) {
              // make wp url from title
            $url = sanitize_title($child['name']);
              echo '<li><a href="topics/'.$url.'">'.$child['titles'][$lang].'</a></li>';
          }
      }
    }

}

function get_law_datasets($ckan_domain,$filter_key,$filter_value){
  $ckanapi_url = $ckan_domain . "/api/3/action/package_search?q=*:*&fq=type:laws_record&rows=1000";
  $json = @file_get_contents($ckanapi_url);
  if ($json === FALSE) return [];
  $result = json_decode($json, true) ?: [];
  $datasets = $result["result"]["results"];
  if (isset($filter_key) && isset($filter_value)){
    foreach ($datasets as $key => $dataset){
      if ( !isset($dataset[$filter_key])){
        unset($datasets[$key]);
      }else{
        if (is_array($dataset[$filter_key])){
          if (!in_array($filter_value,$dataset[$filter_key])){
            unset($datasets[$key]);
          }
        }else if ($dataset[$filter_key] != $filter_value){
          unset($datasets[$key]);
        }
      }
    }
  }
  return $datasets;
}

// function get_law_datasets($filter_odm_taxonomy,$filter_odm_document_type){
//   $shortcode = '[wpckan_query_datasets query="*:*" limit=1000 type="laws_record" include_fields_extra="taxonomy,odm_document_type,title_translated,odm_document_number,odm_promulgation_date" format="json"]';
//   $laws_json = null;
//
//   try{
//     $laws_json = do_shortcode($shortcode);
//   } catch (Exception $e){
//     return [];
//   }
//
//   $laws = json_decode($laws_json,true);
//   foreach ($laws["wpckan_dataset_list"] as $key => $law_record){
//     if (!empty($filter_odm_document_type) && $law_record['wpckan_dataset_extras']['wpckan_dataset_extras-odm_document_type'] != $filter_odm_document_type){
//       unset($laws["wpckan_dataset_list"][$key]);
//     }
//     if (!empty($filter_odm_taxonomy) && !in_array($filter_odm_taxonomy,$law_record['wpckan_dataset_extras']['wpckan_dataset_extras-taxonomy'])){
//       unset($laws["wpckan_dataset_list"][$key]);
//     }
//   }
//   return $laws["wpckan_dataset_list"];
// }

function get_dataset_by_id($ckan_domain,$id){
  $ckanapi_url = $ckan_domain . "/api/3/action/package_show?id=" . $id;
  $json = @file_get_contents($ckanapi_url);
  if ($json === FALSE) return [];
  $datasets = json_decode($json, true) ?: [];
  return $datasets["result"];
}

function get_datasets_filter($ckan_domain,$key,$value){
  $ckanapi_url = $ckan_domain . "/api/3/action/package_search?fq=" . $key . ":" . $value;
  $json = @file_get_contents($ckanapi_url);
  if ($json === FALSE) return [];
  $datasets = json_decode($json, true) ?: [];
  return $datasets["result"]["results"];
}

function get_datastore_resources_filter($ckan_domain,$resource_id,$key,$value){
  $datastore_url = $ckan_domain . "/api/3/action/datastore_search?resource_id=" . $resource_id . "&limit=1000&filters={\"" . $key . "\":\"" . $value . "\"}";
  $json = @file_get_contents($datastore_url);
  if ($json === FALSE) return [];
  $profiles = json_decode($json, true) ?: [];
  return $profiles["result"]["records"];
}

function get_datastore_resource($ckan_domain,$resource_id){
  $datastore_url = $ckan_domain . "/api/3/action/datastore_search?resource_id=" . $resource_id . "&limit=1000";
  $json = @file_get_contents($datastore_url);
  if ($json === FALSE) return [];
  $profiles = json_decode($json, true) ?: [];
  return $profiles["result"]["records"];
}

function buildStyledTopTopicListForLaws($lang)
{
    $navigation_vocab = @file_get_contents(get_stylesheet_directory().'/odm-taxonomy/top_topics/top_topics_multilingual.json');
    if ($navigation_vocab === FALSE) echo '<ul></ul>';
    $json_a = json_decode($navigation_vocab, true);

    echo '<ul>';
    // get Top Topic Names
    foreach ($json_a as $key => $value) {
      foreach ($json_a[$key]['children'] as $child) {
         echo '<li><a href="/laws/?odm_taxonomy='.$child['titles']['en'].'">'. $child['titles'][$lang] .'</a></li>';
      }
    }
    echo '</ul>';
}

function buildStyledTopTopicNav($lang)
{	if ($lang == "kh")
		$lang = "km";
    $navigation_vocab = @file_get_contents(get_stylesheet_directory().'/odm-taxonomy/top_topics/top_topics_multilingual.json');
    if ($navigation_vocab === FALSE || is_null($navigation_vocab)){
      return;
    }
    $json_a = json_decode($navigation_vocab, true);

    // get Top Topic Names
    foreach ($json_a as $key => $value) {
        switch ($key) {
          case 0:
              $icon = 'icon_tree.png';
              $menu = 'menu_environment';
              break;
          case 1:
              $icon = 'icon_industry.png';
              $menu = 'menu_economy';
              break;
          case 2:
              $icon = 'icon_mensch.png';
              $menu = 'menu_people';
              break;
      }
        echo '<li class="first icon_menu '.$menu.'">';
        echo '<a href="#" target="_self">';
        $icon_url = get_stylesheet_directory_uri().'/img/'.$icon;
        echo '<img src="'.$icon_url.'" alt="Top Topic Icon for '.$menu.'">';
        echo '<span class="cNavState"></span></a>';

        echo '<ul class="level2 '.$menu.'">';
        echo '<li class="top-topic">'.$value['titles'][$lang].'</li>';
      // counter
       // get entries -->
       foreach ($json_a[$key]['children'] as $child) {
           $url = sanitize_title($child['name']);
           echo '<li class="'. $child['class'] .'"><a href="/topics/'.$url.'">'.$child['titles'][$lang].'</a></li>';
       }

        ?>
        <span class="border"></span>
      </ul><?php

    }
}

function getMultilingualValueOrFallback($field,$lang){

  if (!isset($field[$lang]) || IsNullOrEmptyString($field[$lang])){
    return $field['en'];
  }

  return $field[$lang];

}

// include backend layer interface for adding wms layer into map explorer of child theme
add_action( 'after_setup_theme', function() {
    include(STYLESHEETPATH . '/inc/layers.php');
}, 42 );


//custom submenus for country specific menu
class country_specific_sub_menus extends Walker_Nav_Menu {
  function start_lvl(&$output, $depth) {
    $level=$depth+2;
    $indent = str_repeat("\t", $depth);
    $output .= "\n$indent<ul class=\"level$level \">\n";
  }
  function end_lvl(&$output, $depth) {
    $indent = str_repeat("\t", $depth);
    $output .= "$indent</ul>\n";
  }
}
/****** Add function convert date, H-E/**/
//echo convert_date_to_kh_date("18.05.2014");
function convert_date_to_kh_date($date_string, $splitted_by = "."){ //$date_string = Day.Month.Year
		$splitted_date = explode($splitted_by,$date_string); // split the date by "."
		$joined_date = "";
		if (count($splitted_date ) > 1){
			if (strlen($date_string) == 7){ //month and year //Month.Year  02.2014
				$month_year = $splitted_date; //get Month.Year  02.2014
					if ($month_year[0] != "00")
						$joined_date .= " ខែ".convert_to_kh_month($month_year[0]);
					if ($month_year[1] != "0000")
						$joined_date .= " ឆ្នាំ".convert_to_kh_number($month_year[1]);
			}else {
				$day_month_year = $splitted_date; //get Day.Month.Year  20.02.2014
					if ($day_month_year[0] != "00")
						$joined_date .= "ថ្ងៃទី ". convert_to_kh_number($day_month_year[0]);
					if ($day_month_year[1] != "00")
						$joined_date .= " ខែ".convert_to_kh_month($day_month_year[1]);
					if ($day_month_year[2] != "0000")
						$joined_date .= " ឆ្នាំ".convert_to_kh_number($day_month_year[2]);
			}

	   }else{
			if (strlen($date_string) == 4){ //only year
				$joined_date = " ឆ្នាំ".convert_to_kh_number($date_string);
			}
	   }
	   return $joined_date;
}
function convert_to_kh_month($month="") {
	if ($month=="Jan"){	$kh_month =  "មករា";	}
	else if ($month=="Feb"){	$kh_month = "កុម្ភៈ";	}
	else if ($month=="Mar"){	$kh_month =  "មីនា";	}
	else if ($month=="Apr"){	$kh_month =  "មេសា";	}
	else if ($month=="May"){	$kh_month =  "ឧសភា";	}
	else if ($month=="Jun"){	$kh_month =  "មិថុនា";	}
	else if ($month=="Jul"){	$kh_month =  "កក្កដា"; }
	else if ($month=="Aug"){	$kh_month =  "សីហា";	}
	else if ($month=="Sep"){	$kh_month =  "កញ្ញា";	}
	else if ($month=="Oct"){	$kh_month =  "តុលា";	}
	else if ($month=="Nov"){	$kh_month =  "វិច្ឆិកា";	}
	else if ($month=="Dec"){	$kh_month =  "ធ្នូ"; }

	else if ($month=="01"){	$kh_month =  "មករា";	}
	else if ($month=="02"){	$kh_month =  "កុម្ភៈ";	}
	else if ($month=="03"){	$kh_month =  "មីនា";	}
	else if ($month=="04"){	$kh_month =  "មេសា";	}
	else if ($month=="05"){	$kh_month =  "ឧសភា";	}
	else if ($month=="06"){	$kh_month =  "មិថុនា";	}
	else if ($month=="07"){	$kh_month =  "កក្កដា"; }
	else if ($month=="08"){	$kh_month =  "សីហា";	}
	else if ($month=="09"){	$kh_month =  "កញ្ញា";	}
	else if ($month=="10"){	$kh_month =  "តុលា";	}
	else if ($month=="11"){	$kh_month =  "វិច្ឆិកា";	}
	else if ($month=="12"){	$kh_month =  "ធ្នូ"; }

	else if ($month=="០១"){	$kh_month =  "មករា";	}
	else if ($month=="០២"){	$kh_month =  "កុម្ភៈ";	}
	else if ($month=="០៣"){	$kh_month =  "មីនា";	}
	else if ($month=="០៤"){	$kh_month =  "មេសា";	}
	else if ($month=="០៥"){	$kh_month =  "ឧសភា";	}
	else if ($month=="០៦"){	$kh_month =  "មិថុនា";	}
	else if ($month=="០៧"){	$kh_month =  "កក្កដា"; }
	else if ($month=="០៨"){	$kh_month =  "សីហា";	}
	else if ($month=="០៩"){	$kh_month =  "កញ្ញា";	}
	else if ($month=="១០"){	$kh_month =  "តុលា";	}
	else if ($month=="១១"){	$kh_month =  "វិច្ឆិកា";	}
	else if ($month=="១២"){	$kh_month =  "ធ្នូ"; }
return $kh_month;

}
function convert_to_kh_number($number) {
	$conbine_num = "";
	$split_num = str_split($number);
	foreach( $split_num as $num){
		if ($num=="0"){	$kh_num =  "០";	}
		else if ($num=="1"){	$kh_num = "១";	}
		else if ($num=="2"){	$kh_num =  "២";	}
		else if ($num=="3"){	$kh_num =  "៣";	}
		else if ($num=="4"){	$kh_num =  "៤";	}
		else if ($num=="5"){	$kh_num =  "៥";	}
		else if ($num=="6"){	$kh_num =  "៦";	}
		else if ($num=="7"){	$kh_num =  "៧";	}
		else if ($num=="8"){	$kh_num =  "៨";	}
		else if ($num=="9"){	$kh_num =  "៩";	}

	$conbine_num .= $kh_num;
	}
return $conbine_num;
}
?>
