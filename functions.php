<?php

/*
 * Managers
 */
require_once get_stylesheet_directory().'/inc/country-manager.php';
require_once get_stylesheet_directory().'/inc/language-manager.php';

/*
 * Defining constants to be used across the whole theme
 */
define('COUNTRY_NAME', opendev_country_manager()->get_current_country());
define('CURRENT_LANGUAGE', opendev_language_manager()->get_current_language());

/*
 * Post types
 */
require_once get_stylesheet_directory().'/inc/post-types/news-article.php';
require_once get_stylesheet_directory().'/inc/post-types/topics.php';
require_once get_stylesheet_directory().'/inc/post-types/announcements.php';
require_once get_stylesheet_directory().'/inc/post-types/site-updates.php';
require_once get_stylesheet_directory().'/inc/post-types/stories.php';

/*
 * Importing utility classes
 */
require_once get_stylesheet_directory().'/inc/query-multisite.php';
require_once get_stylesheet_directory().'/inc/theme-options.php';
require_once get_stylesheet_directory().'/inc/layer-category.php';
require_once get_stylesheet_directory().'/inc/summary.php';
require_once get_stylesheet_directory().'/inc/live-search/live-search.php';
require_once get_stylesheet_directory().'/inc/interactive-map.php';
require_once get_stylesheet_directory().'/inc/widgets/odm-category-widget.php';
require_once get_stylesheet_directory().'/inc/widgets/odm-taxonomy-widget.php';
require_once get_stylesheet_directory().'/inc/widgets/odm-related-recent-news-widget.php';
require_once get_stylesheet_directory().'/inc/widgets/odm-custom-posts-widget.php';
require_once get_stylesheet_directory().'/inc/widgets/odm-contents-same-category-widget.php';
require_once get_stylesheet_directory().'/inc/advanced-navigation.php';
require_once get_stylesheet_directory().'/inc/category-walker.php';
require_once get_stylesheet_directory().'/inc/utils/localization.php';
require_once get_stylesheet_directory().'/inc/utils/max-mega-menu-options.php';
require_once get_stylesheet_directory().'/inc/utils/templating.php';
require_once get_stylesheet_directory().'/inc/utils/content.php';
require_once get_stylesheet_directory().'/inc/utils/breadcrumbs.php';
require_once get_stylesheet_directory().'/inc/utils/layout.php';

/*
 * Loads the theme's translated strings. for 'opendev' and 'jeo' domains
 * Registers widget sidebars
 */
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
    'name' => __('Homepage area 1', 'jeo'),
    'id' => 'homepage-area-1',
    'before_title' => '<h2 class="widget-title">',
    'after_title' => '</h2>',
    'before_widget' => '',
	  'after_widget'  => ''
  ));
    register_sidebar(array(
    'name' => __('Homepage area 2', 'jeo'),
    'id' => 'homepage-area-4',
    'before_title' => '<h2 class="widget-title">',
    'after_title' => '</h2>',
    'before_widget' => '',
	  'after_widget'  => ''
  ));
    register_sidebar(array(
    'name' => __('Homepage area 3', 'jeo'),
    'id' => 'homepage-area-5',
    'before_title' => '<h2 class="widget-title">',
    'after_title' => '</h2>',
    'before_widget' => '',
	  'after_widget'  => ''
  ));
    register_sidebar(array(
    'name' => __('WPCKAN Dataset detail bottom', 'jeo'),
    'id' => 'wpckan-dataset-detail-bottom',
    'before_title' => '<h2 class="widget-title">',
    'after_title' => '</h2>',
  ));
    register_sidebar(array(
    'name' => __('Data main', 'jeo'),
    'id' => 'data-main',
    'before_title' => '<h2 class="widget-title">',
    'after_title' => '</h2>',
  ));
    register_sidebar(array(
    'name' => __('Data sidebar', 'jeo'),
    'id' => 'data-sidebar',
    'before_title' => '<h2 class="widget-title">',
    'after_title' => '</h2>',
  ));

  include get_stylesheet_directory().'/inc/layers.php';
}
add_action('after_setup_theme', 'opendev_setup_theme');

function add_menu_icons_styles(){
?>

<style>
#adminmenu .menu-icon-announcement div.wp-menu-image:before {
  content: "\f488";
}
#adminmenu .menu-icon-site-update div.wp-menu-image:before {
  content: "\f348";
}
#adminmenu .menu-icon-news-article div.wp-menu-image:before {
  content: "\f119";
}
#adminmenu .menu-icon-story div.wp-menu-image:before {
  content: "\f125";
}
#adminmenu .menu-icon-topic div.wp-menu-image:before {
  content: "\f163";
}
#adminmenu .menu-icon-profiles div.wp-menu-image:before {
  content: "\f325";
}
#adminmenu .menu-icon-tabular div.wp-menu-image:before {
  content: "\f509";
}
</style>

<?php
}
add_action( 'admin_head', 'add_menu_icons_styles' );

function opendev_dependency_scripts()
{
  wp_enqueue_script('odm-dependencies-leaflet', get_stylesheet_directory_uri().'/bower_components/leaflet/dist/leaflet.js');
  wp_enqueue_script('odm-dependencies-chosen', get_stylesheet_directory_uri().'/bower_components/chosen/chosen.jquery.js');
  wp_enqueue_script('odm-dependencies-datatables', get_stylesheet_directory_uri().'/bower_components/datatables/media/js/jquery.dataTables.min.js');
}
add_action('wp_enqueue_scripts', 'opendev_dependency_scripts', 100);

function opendev_jeo_scripts()
{
    wp_dequeue_script('jeo-site');
    wp_register_script('twttr', 'https://platform.twitter.com/widgets.js');
    $site_name = str_replace('Open Development ', '', get_bloginfo('name'));

    global $jeo_markers;
    wp_deregister_script('jeo.markers');
    wp_register_script('jeo.markers', get_stylesheet_directory_uri().'/lib/js/markers.js', array('jeo', 'underscore', 'twttr'), '0.3.17', true);
    wp_localize_script('jeo.markers', 'opendev_markers', array(
    'ajaxurl' => admin_url('admin-ajax.php'),
    'query' => $jeo_markers->query(),
    'stories_label' => __('stories', 'opendev'),
    'home' => (is_home() && !is_paged() && (isset($_REQUEST['opendev_filter_']) && !$_REQUEST['opendev_filter_'])),
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

  wp_enqueue_script('odm-scripts', get_stylesheet_directory_uri().'/dist/js/scripts.min.js');
}
add_action('wp_enqueue_scripts', 'opendev_jeo_scripts', 101);

// function opendev_jeo_admin_scripts()
// {
//     if (file_exists(get_stylesheet_directory().'/inc/js/filter-layers.js')) {
//         wp_enqueue_script('jeo.clearscreen', get_stylesheet_directory_uri().'/inc/js/clearscreen.js', array('jeo'), '1.0.0');
//     }
//
//     if (file_exists(get_stylesheet_directory().'/inc/js/baselayer.js')) {
//         wp_enqueue_script('jeo.baselayer', get_stylesheet_directory_uri().'/inc/js/baselayer.js', array('jeo'), '1.0.0');
//     }
// }
// add_action('admin_enqueue_scripts', 'opendev_jeo_admin_scripts');

function opendev_styles()
{
    $options = get_option('opendev_options');

    $css_base = get_stylesheet_directory_uri().'/dist/css/';
    wp_register_style('opendev-cambodia',  $css_base.'cambodia.css');
    wp_register_style('opendev-thailand',  $css_base.'thailand.css');
    wp_register_style('opendev-laos',  $css_base.'laos.css');
    wp_register_style('opendev-myanmar',  $css_base.'myanmar.css');
    wp_register_style('opendev-vietnam',  $css_base.'vietnam.css');

    $cambodia_base = get_stylesheet_directory_uri().'/Cambodia/';
    wp_enqueue_style('forest-cover',  $cambodia_base.'forest-cover.css');

    $bower_base = get_stylesheet_directory_uri().'/bower_components/';
    wp_enqueue_style('bower-fontawesome-style',  $bower_base.'fontawesome/css/font-awesome.min.css');
    wp_enqueue_style('bower-chosen-style',  $bower_base.'chosen/chosen.css');

    $dist_base = get_stylesheet_directory_uri().'/dist/css/';
    wp_enqueue_style('extra-style',  $dist_base.'extra.min.css');
    wp_enqueue_style('odm-style',  $dist_base.'odm.css');

    if ($options['style']) {
        wp_enqueue_style('opendev-'.$options['style']);
    }
}
add_action('wp_enqueue_scripts', 'opendev_styles', 15);

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
add_action('init', 'create_news_source_taxonomies', 0);

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

    $data['thumbnail'] = opendev_get_thumbnail();

    return $data;
}
add_filter('jeo_marker_data', 'opendev_marker_data', 10, 2);

function opendev_social_apis()
{

 // Facebook
 ?>
 <div id="fb-root"></div>
 <script>(function(d, s, id) {
   var js, fjs = d.getElementsByTagName(s)[0];
   if (d.getElementById(id)) return;
   js = d.createElement(s); js.id = id;
   //js.src = "//connect.facebook.net/en_US/all.js#xfbml=1&appId=1413694808863403";
   js.src = "//connect.facebook.net/en_US/all.js#xfbml=1&appId=1066174610071139";
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
      $query->set('post_type', get_post_types());
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

function hide_other_country_page_template($pages_templates)
{
   return $pages_templates;
}
add_filter('theme_page_templates', 'hide_other_country_page_template');

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

?>
