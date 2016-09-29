<?php

/*
 * Managers
 */
require_once get_stylesheet_directory().'/inc/country-manager.php';
require_once get_stylesheet_directory().'/inc/language-manager.php';
require_once get_stylesheet_directory().'/inc/taxonomy-manager.php';

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
//require_once get_stylesheet_directory().'/inc/live-search/live-search.php';
require_once get_stylesheet_directory().'/inc/interactive-map.php';
require_once get_stylesheet_directory().'/inc/widgets/odm-category-widget.php';
require_once get_stylesheet_directory().'/inc/widgets/odm-taxonomy-widget.php';
require_once get_stylesheet_directory().'/inc/widgets/odm-related-recent-news-widget.php';
require_once get_stylesheet_directory().'/inc/widgets/odm-custom-posts-widget.php';
require_once get_stylesheet_directory().'/inc/widgets/odm-contents-same-category-widget.php';
require_once get_stylesheet_directory().'/inc/advanced-navigation.php';
require_once get_stylesheet_directory().'/inc/category-walker.php';
require_once get_stylesheet_directory().'/inc/utils/localization.php';
require_once get_stylesheet_directory().'/inc/max-mega-menu-options.php';
require_once get_stylesheet_directory().'/inc/utils/content.php';
require_once get_stylesheet_directory().'/inc/utils/breadcrumbs.php';
require_once get_stylesheet_directory().'/inc/utils/layout.php';
require_once get_stylesheet_directory().'/inc/utils/urls.php';

/*
 * Loads the theme's translated strings. for 'odm' and 'jeo' domains
 * Registers widget sidebars
 */
function odm_setup_theme()
{
    $gsd = explode('wp-content', get_stylesheet_directory());
    load_theme_textdomain('odm', $gsd[0].'/wp-content/languages');
    load_theme_textdomain('jeo', $gsd[0].'/wp-content/languages');
    register_sidebar(array(
    'name' => __('Topic sidebar', 'jeo'),
    'id' => 'topic',
    'before_title' => '<h2 class="widget-title">',
    'after_title' => '</h2>',
  ));
    register_sidebar(array(
    'name' => __('Homepage area top left', 'jeo'),
    'id' => 'homepage-area-1',
    'before_title' => '<h2 class="widget-title">',
    'after_title' => '</h2>',
    'before_widget' => '',
	  'after_widget'  => ''
  ));
    register_sidebar(array(
    'name' => __('Homepage area top right', 'jeo'),
    'id' => 'homepage-area-2',
    'before_title' => '<h2 class="widget-title">',
    'after_title' => '</h2>',
    'before_widget' => '',
    'after_widget'  => ''
  ));
    register_sidebar(array(
    'name' => __('Homepage area middle', 'jeo'),
    'id' => 'homepage-area-3',
    'before_title' => '<h2 class="widget-title">',
    'after_title' => '</h2>',
    'before_widget' => '',
    'after_widget'  => ''
  ));
    register_sidebar(array(
    'name' => __('Homepage area bottom left', 'jeo'),
    'id' => 'homepage-area-4',
    'before_title' => '<h2 class="widget-title">',
    'after_title' => '</h2>',
    'before_widget' => '',
	  'after_widget'  => ''
  ));
    register_sidebar(array(
    'name' => __('Homepage area bottom right', 'jeo'),
    'id' => 'homepage-area-5',
    'before_title' => '<h2 class="widget-title">',
    'after_title' => '</h2>',
    'before_widget' => '',
	  'after_widget'  => ''
  ));
    register_sidebar(array(
    'name' => __('WPCKAN Dataset detail sidebar', 'jeo'),
    'id' => 'wpckan-dataset-detail-sidebar',
    'before_title' => '<h2 class="widget-title">',
    'after_title' => '</h2>',
  ));
    register_sidebar(array(
    'name' => __('Story top', 'jeo'),
    'id' => 'story-top',
    'before_title' => '<h2 class="widget-title">',
    'after_title' => '</h2>',
  ));
    register_sidebar(array(
    'name' => __('Story bottom', 'jeo'),
    'id' => 'story-bottom',
    'before_title' => '<h2 class="widget-title">',
    'after_title' => '</h2>',
  ));
    register_sidebar(array(
    'name' => __('Profile area right sidebar', 'odm'),
    'id' => 'profile-area-1',
    'before_title' => '<h2 class="widget-title">',
    'after_title' => '</h2>'
  ));
    register_sidebar(array(
    'name' => __('Profile area bottom left', 'odm'),
    'id' => 'profile-area-2',
    'before_title' => '<h2 class="widget-title">',
    'after_title' => '</h2>',
    'before_widget' => '',
    'after_widget'  => ''
  ));
    register_sidebar(array(
    'name' => __('Profile area bottom right', 'odm'),
    'id' => 'profile-area-3',
    'before_title' => '<h2 class="widget-title">',
    'after_title' => '</h2>',
    'before_widget' => '',
    'after_widget'  => ''
  ));
  register_sidebar(array(
  'name' => __('Profile sub-page right sidebar', 'odm'),
  'id' => 'profile-sub-page-sidebar',
  'before_title' => '<h2 class="widget-title">',
  'after_title' => '</h2>',
  'before_widget' => '',
  'after_widget'  => ''
));

  include_once get_stylesheet_directory().'/inc/layers.php';
  include_once get_stylesheet_directory().'/inc/embed.php';
}
add_action('after_setup_theme', 'odm_setup_theme');

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

function odm_dependency_scripts()
{
  wp_enqueue_script('odm-dependencies-chosen', get_stylesheet_directory_uri().'/bower_components/chosen/chosen.jquery.js');
  wp_enqueue_script('odm-dependencies-datatables', get_stylesheet_directory_uri().'/bower_components/datatables/media/js/jquery.dataTables.min.js');
  //wp_enqueue_script('odm-dependencies-leaflet', get_stylesheet_directory_uri().'/bower_components/leaflet/dist/leaflet.js');
}
add_action('wp_enqueue_scripts', 'odm_dependency_scripts', 100);

function odm_jeo_scripts()
{
  wp_dequeue_script('jeo-site');
  wp_enqueue_script('jquery-isotope');
  wp_register_script('twttr', 'https://platform.twitter.com/widgets.js');
  wp_register_script('jquery','https://ajax.googleapis.com/ajax/libs/jquery/2.1.4/jquery.min.js');
  wp_register_script('jquery-ui', 'https://code.jquery.com/ui/1.11.4/jquery-ui.js');
  $site_name = str_replace('Open Development ', '', get_bloginfo('name'));
  // custom marker system
  global $jeo_markers;
  wp_deregister_script('jeo.markers');
  wp_register_script('jeo.markers', get_stylesheet_directory_uri().'/inc/jeo-scripts/markers.js', array('jeo', 'underscore', 'twttr'), '0.3.17', true);

  wp_localize_script('jeo.markers', 'opendev_markers', array(
    'ajaxurl' => admin_url('admin-ajax.php'),
    'query' => $jeo_markers->query(),
    'stories_label' => __('stories', 'odm'),
    'home' => (is_home() && !is_paged() && !isset($_REQUEST['opendev_filter_'])),
    'copy_embed_label' => __('Copy the embed code', 'odm'),
    'share_label' => __('Share', 'odm'),
    'print_label' => __('Print', 'odm'),
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
   'read_more_label' => __('Read more', 'odm'),
   'lightbox_label' => array(
   'slideshow' => __('Open slideshow', 'odm'),
   'videos' => __('Watch video gallery', 'odm'),
   'video' => __('Watch video', 'odm'),
   'images' => __('View image gallery', 'odm'),
   'image' => __('View fullscreen image', 'odm'),
   'infographic' => __('View infographic', 'odm'),
   'infographics' => __('View infographics', 'odm'),
  ),
   'enable_clustering' => jeo_use_clustering() ? true : false,
   'default_icon' => jeo_formatted_default_marker(),
  ));

  if (is_home()) {
      wp_enqueue_script('opendev-sticky', get_stylesheet_directory_uri().'/inc/jeo-scripts/sticky-posts.js', array('jeo.markers', 'jquery'), '0.1.2');
  }
  if (is_page( array( 'map-explorer', 'maps', 'embed' )) || is_singular('map') || is_home()){
      if ( file_exists( STYLESHEETPATH . '/inc/jeo-scripts/jeo.js')) {
         wp_deregister_script('jeo');
         wp_enqueue_script('jeo', get_stylesheet_directory_uri() . '/inc/jeo-scripts/jeo.js', array('mapbox-js', 'underscore', 'jquery'), '0.5.0');
      }

      if ( file_exists( STYLESHEETPATH . '/inc//jeo-scripts/fullscreen.js')){
         wp_deregister_script('jeo.fullscreen');
         wp_enqueue_script('jeo.fullscreen', get_stylesheet_directory_uri() . '/inc/jeo-scripts/fullscreen.js',array('jeo'), '0.2.0');
      }

      wp_enqueue_script('BetterWMS', get_stylesheet_directory_uri() . '/inc/jeo-scripts/L.TileLayer.BetterWMS.js', array('jeo', 'jquery'), '1.0.0');

      wp_enqueue_script('jeo.clearscreen', get_stylesheet_directory_uri() . '/inc/jeo-scripts/clearscreen.js', array('jeo'), '1.0.0');

      wp_enqueue_script('mapping-script', get_stylesheet_directory_uri() . '/inc/jeo-scripts/mapping.js', array('jeo','jquery-ui'), '1.0.0');
  }

  if ( file_exists(STYLESHEETPATH . '/inc/jeo-scripts/share-widget.js')) {
    wp_deregister_script('jeo-share-widget');
    wp_enqueue_script('jeo-share-widget', get_stylesheet_directory_uri() . '/inc/jeo-scripts/share-widget.js', array('jquery', 'underscore', 'chosen'), '1.5.6');

    wp_localize_script('jeo-share-widget', 'extended_jeo_share_widget_settings', array(
    	'baseurl' => extended_jeo_get_embed_url(),
    	'default_label' => __('default', 'jeo')
    ));
  }

  wp_enqueue_script('odm-scripts', get_stylesheet_directory_uri().'/dist/js/scripts.min.js');
}
add_action('wp_enqueue_scripts', 'odm_jeo_scripts', 100);

function odm_jeo_admin_scripts() {
    if ( file_exists( get_stylesheet_directory_uri() . '/inc/jeo-scripts/clearscreen.js'))
			wp_enqueue_script('jeo.clearscreen', get_stylesheet_directory_uri() . '/inc/jeo-scripts/clearscreen.js', array('jeo'), '1.0.0');
}
add_action( 'admin_enqueue_scripts', 'odm_jeo_admin_scripts' );

function odm_styles()
{
    $options = get_option('odm_options');

    $css_base = get_stylesheet_directory_uri().'/dist/css/';
    wp_register_style('odm-cambodia',  $css_base.'cambodia.css');
    wp_register_style('odm-thailand',  $css_base.'thailand.css');
    wp_register_style('odm-laos',  $css_base.'laos.css');
    wp_register_style('odm-myanmar',  $css_base.'myanmar.css');
    wp_register_style('odm-vietnam',  $css_base.'vietnam.css');

    $cambodia_base = get_stylesheet_directory_uri().'/Cambodia/';
    wp_enqueue_style('forest-cover',  $cambodia_base.'forest-cover.css');

    $bower_base = get_stylesheet_directory_uri().'/bower_components/';
    wp_enqueue_style('bower-fontawesome-style',  $bower_base.'fontawesome/css/font-awesome.min.css');
    wp_enqueue_style('bower-chosen-style',  $bower_base.'chosen/chosen.css');

    $dist_base = get_stylesheet_directory_uri().'/dist/css/';
    wp_enqueue_style('extra-style',  $dist_base.'extra.min.css');
    wp_enqueue_style('odm-style',  $dist_base.'odm.css');

    if ($options['style']) {
        wp_enqueue_style('odm-'.$options['style']);
    }
}
add_action('wp_enqueue_scripts', 'odm_styles', 15);

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

    register_taxonomy('news_source', array('news-article'), $args);
}
add_action('init', 'create_news_source_taxonomies', 0);

function odm_marker_data($data, $post)
{
    global $post;

    $permalink = $data['url'];
    $permalink = add_query_arg(array('lang' => odm_language_manager()->get_current_language()), $permalink);

    $data['permalink'] = $permalink;
    $data['url'] = $permalink;
    $data['content'] = get_the_excerpt();
    if (get_post_meta($post->ID, 'geocode_zoom', true)) {
        $data['zoom'] = get_post_meta($post->ID, 'geocode_zoom', true);
    }

    $data['thumbnail'] = odm_get_thumbnail();

    return $data;
}
add_filter('jeo_marker_data', 'odm_marker_data', 10, 2);

function odm_social_apis()
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
add_action('wp_footer', 'odm_social_apis');

// Disable mousewheel zoom by default
function odm_map_data($data)
{
    $data['disable_mousewheel'] = true;

    return $data;
}
add_filter('jeo_map_data', 'odm_map_data');
add_filter('jeo_mapgroup_data', 'odm_map_data');

function odm_custom_admin_css()
{
/*    ?>
 <style>
  .handlers.map-setting { display: none !important; }
 </style>
 <?php
*/
 // dequeue parent script and enqueue from child theme
 wp_dequeue_script('mapbox-metabox');
 wp_enqueue_script('child-mapbox-metabox', get_stylesheet_directory_uri() . '/inc/jeo-scripts/mapbox.js', array('jquery', 'jeo', 'jquery-ui-sortable'), '0.5.1');

}
add_action('admin_footer', 'odm_custom_admin_css', 100);

function odm_return_all_topics( $query ) {

  if (is_archive() && $query->get('post_type') == 'topic') {
      $query->query_vars['posts_per_page'] = -1;
  }

}
add_action( 'pre_get_posts', 'odm_return_all_topics' );

function odm_search_pre_get_posts($query)
{
  if(get_post_type()){
    $post_type = $query->query['post_type'][0];
  }else {
    if (!is_admin() && ($query->is_search || get_query_var('odm_advanced_nav') || $query->is_tax || $query->is_category || $query->is_tag)) {
      $query->set('post_type', available_post_types_search());
    }
  }

}
add_action('pre_get_posts', 'odm_search_pre_get_posts');

function odm_category_pre_get_posts($query)
{
  if(get_post_type()){
    $post_type = $query->query['post_type'][0];
  }else {
      $post_type = isset($_GET['queried_post_type']) ? $_GET['queried_post_type'] : 'topic';
  }
  if ($query->is_category && isset($post_type)) {
      $query->set('post_type', array($post_type));
  }
}
add_action('pre_get_posts', 'odm_category_pre_get_posts', 20, 1);

function odm_posts_clauses_join($join)
{
    global $wpdb;
    if (get_post_type() == 'map' && get_post_type() == 'map-group'){
      $join = " INNER JOIN {$wpdb->postmeta} m_maps ON ({$wpdb->posts}.ID = m_maps.post_id) ";
    return $join;
    }
}
add_filter('jeo_posts_clauses_join', 'odm_posts_clauses_join');

function odm_posts_clauses_where($where)
{
  if (get_post_type() == 'map' && get_post_type() == 'map-group'){
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
}
  add_filter('jeo_posts_clauses_where', 'odm_posts_clauses_where');

function odm_ignore_sticky($query)
{
    if ($query->is_main_query()) {
        $query->set('ignore_sticky_posts', true);
    }
}
add_action('pre_get_posts', 'odm_ignore_sticky');

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
