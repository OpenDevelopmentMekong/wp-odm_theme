<?php

/*
 * Defining constants to be used across the whole theme
 * TODO: Replace with a function soted somewhere on /inc
 */
$country_codes = array('cambodia' => 'kh', 'laos' => 'lo', 'myanmar' => 'my', 'vietnam' => 'vn', 'thailand' => 'th', '1' => 'mekong');
$country = array_shift((explode('.', $_SERVER['HTTP_HOST'])));
define('COUNTRY_NAME', strtolower($country_codes[$country || 'mekong' ]));
define('THEME_DIR', get_stylesheet_directory());

/*
 * Requiring PHP files with extra functionality and content
 */
require_once THEME_DIR.'/inc/query-multisite.php';
require_once THEME_DIR.'/inc/theme-options.php';
require_once THEME_DIR.'/inc/topics.php';
require_once THEME_DIR.'/inc/announcements.php';
require_once THEME_DIR.'/inc/site-updates.php';
require_once THEME_DIR.'/inc/layer-category.php';
require_once THEME_DIR.'/inc/summary.php';
require_once THEME_DIR.'/inc/live-search/live-search.php';
require_once THEME_DIR.'/inc/interactive-map.php';
require_once THEME_DIR.'/inc/widgets/category-widget.php';
require_once THEME_DIR.'/inc/widgets/odm-taxonomy-widget.php';
require_once THEME_DIR.'/inc/widgets/od-related-recent-news-widget.php';
require_once THEME_DIR.'/inc/advanced-navigation.php';
require_once THEME_DIR.'/inc/category-walker.php';
require_once THEME_DIR.'/inc/localization.php';

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
  ));
    register_sidebar(array(
    'name' => __('Homepage area 2', 'jeo'),
    'id' => 'homepage-area-2',
    'before_title' => '<h2 class="widget-title">',
    'after_title' => '</h2>',
  ));
    register_sidebar(array(
    'name' => __('Homepage area 3', 'jeo'),
    'id' => 'homepage-area-3',
    'before_title' => '<h2 class="widget-title">',
    'after_title' => '</h2>',
  ));
    register_sidebar(array(
    'name' => __('Homepage area 4', 'jeo'),
    'id' => 'homepage-area-4',
    'before_title' => '<h2 class="widget-title">',
    'after_title' => '</h2>',
  ));
    register_sidebar(array(
    'name' => __('Homepage area 5', 'jeo'),
    'id' => 'homepage-area-5',
    'before_title' => '<h2 class="widget-title">',
    'after_title' => '</h2>',
  ));

    include THEME_DIR.'/inc/layers.php';
}
add_action('after_setup_theme', 'opendev_setup_theme');

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
//     if (file_exists(THEME_DIR.'/inc/js/filter-layers.js')) {
//         wp_enqueue_script('jeo.clearscreen', get_stylesheet_directory_uri().'/inc/js/clearscreen.js', array('jeo'), '1.0.0');
//     }
//
//     if (file_exists(THEME_DIR.'/inc/js/baselayer.js')) {
//         wp_enqueue_script('jeo.baselayer', get_stylesheet_directory_uri().'/inc/js/baselayer.js', array('jeo'), '1.0.0');
//     }
// }
// add_action('admin_enqueue_scripts', 'opendev_jeo_admin_scripts');

function opendev_styles()
{
    $options = get_option('opendev_options');

    $css_base = get_stylesheet_directory_uri().'/css/';
    wp_register_style('opendev-cambodia',  $css_base.'cambodia.css');
    wp_register_style('opendev-thailand',  $css_base.'thailand.css');
    wp_register_style('opendev-laos',  $css_base.'laos.css');
    wp_register_style('opendev-myanmar',  $css_base.'myanmar.css');
    wp_register_style('opendev-vietnam',  $css_base.'vietnam.css');

    $cambodia_base = get_stylesheet_directory_uri().'/Cambodia/';
    wp_enqueue_style('forest-cover',  $cambodia_base.'forest-cover.css');

    $bower_base = get_stylesheet_directory_uri().'/bower_components/';
    wp_enqueue_style('fontawesome-style',  $bower_base.'fontawesome/css/font-awesome.min.css');
    wp_enqueue_style('chosen-style',  $bower_base.'chosen/chosen.css');

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
    $name = 'Mekong';
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
    }
    ?>
  <h1>
   <a href="<?php echo home_url('/');
    ?>" title="<?php bloginfo('name');
    ?>">
    <span class="icon-od-logo"></span>
    Op<sup>e</sup>nDevelopment
   </a>
  </h1>
  <?php
  echo '<div class="ms-dropdown-title">';
    echo '<h2 class="side-title">'.$name.'</h2>';
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

function IsNullOrEmptyString($question)
{
    return !isset($question) || @trim($question) === '';
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
function get_all_parent_category($current_cat_id, $post_type, $separator = '', $current_page_name = '')
{
    $parent_cat = get_category_parents($current_cat_id, false);
    $parent_cats = explode('/', substr($parent_cat, 0, -1));
    foreach ($parent_cats as $p_cat) {
        $page_title = $p_cat;
        $page_id_exist = get_post_or_page_id_by_title($page_title);
        $page = get_post($page_id_exist);
        $page_slug = $post->post_name;

        echo '<li class="item-topic item-topic-'.$page_id_exist.' item-topic-'.$page_slug.'">';
        if ($page_id_exist) {
            $page_name_title = trim(strtolower($page_title));
            if ($page_name_title == $current_page_name) {
                echo '<strong class="bread-current bread-current-'.$page_id_exist.'" title="'.$page_title.'">';//strong if current page
            }
            echo '<a class="bread-topic bread-topic-'.$page_id_exist.' bread-topic-'.$page_slug.'" href="'.get_permalink($page_id_exist).'" title="'.$page_title.'">';
        }
        echo $page_title;

        if ($page_id_exist) {
            //Strong if current page
             if ($page_name_title == $current_page_name) {
                 echo '</strong>';
             }
            echo '</a>';
        }
        echo '</li>';
        echo the_separated_breadcrumb($separator, $topic_page_exist->ID, $post_type);
    }
}

// Creating Breadcrumbs for the site
function the_separated_breadcrumb($separator = '', $id, $category)
{
    if ($separator != '') {
        return '<li class="separator_by separator-'.$category.' separator-'.$id.'"> '.$separator.' </li>';
    } else {
        return '<li class="separator separator-'.$category.' separator-'.$id.'"></li>';
    }
}
function the_breadcrumb()
{
    // Settings
    $separator = '/'; //'&gt;';
    $id = 'breadcrumbs';
    $class = 'breadcrumbs';
    $home_title = 'Home';

    // Get the query & post information
    global $post,$wp_query;
    //$category = get_the_category();
    $category = get_category(get_query_var('cat'), false);
    // Build the breadcrums
    echo '<ul id="'.$id.'" class="breadcrumb '.$class.'">';
    // Do not display on the homepage
    if (!is_front_page()) {
        // Home page
        echo '<li class="item-home"><a class="bread-link bread-home" href="'.get_home_url().'" title="'.$home_title.'">';
        //_e('Home', 'opendev');
        echo '<i class="fa fa-home"></i>';
        echo '</a></li>';
        echo the_separated_breadcrumb($separator, '', 'home');

        if (is_single()) {
            //Single post of post type "Topic"
            $post_type_of_topic = get_post_type(get_the_ID());
            if ($post_type_of_topic  == 'topic') {
                $get_topic_title = get_the_title();
                $cats = get_the_category(get_the_ID());
                if ($cats) {
                    // if post is in this category
                    foreach ($cats as $cat) {
                        if (in_category($cat->term_id)) {
                            $page_title = trim(strtolower($get_topic_title));
                            $cat_name = trim(strtolower($cat->name));
                            // Which Category and Post have the same name
                            if ($cat_name == $page_title) {
                                $cat_id = $cat->term_id;
                                get_all_parent_category($cat_id, $post_type_of_topic, $separator, $page_title);
                            }
                        }
                    }//end foreach
                } else {
                    //if topic page is not categorized or the topic name is different from the category
                    echo '<li class="item-current item-'.$post->ID.'"><strong class="bread-current bread-'.$post->ID.'" title="'.get_the_title().'">'.get_the_title().'</strong></li>';
                }
            } else {
                // Single post (Only display the first category)
                /* echo '<li class="item-cat item-cat-' . $category[0]->term_id . ' item-cat-' . $category[0]->category_nicename . '"><a class="bread-cat bread-cat-' . $category[0]->term_id . ' bread-cat-' . $category[0]->category_nicename . '" href="' . get_category_link($category[0]->term_id ) . '" title="' . $category[0]->cat_name . '">' . $category[0]->cat_name . '</a></li>'; */
                //echo '<li class="separator separator-' . $category[0]->term_id . '"> ' . $separator . ' </li>';
                echo '<li class="item-current item-'.$post->ID.'">';
                echo '<a class="item-current bread-current-'.$post->ID.'" href="'.get_permalink().'" title="'.get_the_title().'">';
                echo '<strong class="bread-current bread-'.$post->ID.'" title="'.get_the_title().'">'.get_the_title().'</strong>';
                echo '</a>';
                echo '</li>';
            }
        } elseif (is_category()) {
            // Category page
                    $parent_cat = get_category_parents($category->term_id, true, '||');
            $parent_cat = substr($parent_cat, 0, -2);
            $parent_cats = explode('||', $parent_cat);
            foreach ($parent_cats as $cat) {
                echo '<li class="item-current item-cat-'.$category->term_id.' item-cat-'.$category->category_nicename.'">';
                if ($cat === end($parent_cats)) {
                    echo '<strong class="bread-current bread-cat-'.$category->term_id.' bread-cat-'.$category->category_nicename.'">';
                }

                echo $cat;

                if ($cat === end($parent_cats)) {
                    echo '</strong>';
                }
                echo '</li>';

                        //add separated
                        if ($cat != end($parent_cats)) {
                            echo the_separated_breadcrumb($separator, $category->term_id, 'category');
                        }
            }
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
                }

                // Display parent pages
                echo $parents;
                echo the_separated_breadcrumb($separator, $ancestor, 'page');
                        // Current page
                            echo '<li class="item-current item-'.$post->ID.'">';
                if (!isset($_GET['map_id']) and $_GET['map_id'] != '') {
                    echo '<strong title="'.get_the_title().'">';
                }
                echo '<a class="item-current bread-current-'.$post->ID.'" href="'.get_permalink().'" title="'.get_the_title().'">';
                echo get_the_title();
                echo '</a>';
                if (!isset($_GET['map_id']) && $_GET['map_id'] != '') {
                    echo '</strong>';
                }
                echo '</li>';
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
            echo '<li class="item-current item-tag-'.$terms[0]->term_id.' item-tag-'.$terms[0]->slug.'">';
            echo '<strong class="bread-current bread-tag-'.$terms[0]->term_id.'bread-tag-'.$terms[0]->slug.'">';
            echo '<a href="'.get_tag_link($terms[0]->term_id).'">';
            echo $terms[0]->name;
            echo '</a>';
            echo '</strong></li>';
        } elseif (is_day()) {
            //**** Day archive
            // Year link
            echo '<li class="item-year item-year-'.get_the_time('Y').'"><a class="bread-year bread-year-'.get_the_time('Y').'" href="'.get_year_link(get_the_time('Y')).'" title="'.get_the_time('Y').'">'.get_the_time('Y').' </a></li>';
            echo the_separated_breadcrumb($separator, get_the_time('Y'), 'archive');

            // Month link
            echo '<li class="item-month item-month-'.get_the_time('m').'"><a class="bread-month bread-month-'.get_the_time('m').'" href="'.get_month_link(get_the_time('Y'), get_the_time('m')).'" title="'.get_the_time('M').'">'.get_the_time('M').' </a></li>';
            echo the_separated_breadcrumb($separator, get_the_time('m'), 'archive');

            // Day display
            echo '<li class="item-current item-'.get_the_time('j').'"><a class="bread-month bread-month-'.get_the_time('m').'" href="'.get_day_link(get_the_time('Y'), get_the_time('m'), get_the_time('j')).'" title="'.get_the_time('M').'"><strong class="bread-current bread-'.get_the_time('j').'"> '.get_the_time('jS').'</strong></a> Archives</li>';
        } elseif (is_month()) {
            // Month Archive
            // Year link
            echo '<li class="item-year item-year-'.get_the_time('Y').'"><a class="bread-year bread-year-'.get_the_time('Y').'" href="'.get_year_link(get_the_time('Y')).'" title="'.get_the_time('Y').'">'.get_the_time('Y').' </a></li>';
            echo the_separated_breadcrumb($separator, get_the_time('Y'), 'archive');

            // Month displaydisplay
            echo '<li class="item-month item-month-'.get_the_time('m').'"><a class="bread-month bread-month-'.get_the_time('m').'" href="'.get_month_link(get_the_time('Y'), get_the_time('m')).'" title="'.get_the_time('M').'"><strong class="bread-month bread-month-'.get_the_time('m').'" title="'.get_the_time('M').'">'.get_the_time('M').'</strong></a> Archives</li>';
        } elseif (is_year()) {

            // Display year archive
            echo '<li class="item-current item-current-'.get_the_time('Y').'"><a class="bread-year bread-year-'.get_the_time('Y').'" href="'.get_year_link(get_the_time('Y')).'" title="'.get_the_time('Y').'"><strong class="bread-current bread-current-'.get_the_time('Y').'" title="'.get_the_time('Y').'">'.get_the_time('Y').'</strong></a> Archives</li>';
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

function get_post_or_page_id_by_title($title_str, $post_type = 'topic')
{
    global $wpdb;
    $get_post = $wpdb->get_results($wpdb->prepare(
            "SELECT ID, post_title FROM $wpdb->posts WHERE post_type = %s
             AND post_status = %s
			 AND post_title like %s
        	",
            $post_type,
            'publish',
            '%'.trim($title_str).'%'
            )
        );
    foreach ($get_post as $page_topic) {
        $lang_tag = '[:'.qtranxf_getLanguage().']';
        $lang_tag_finder = '/'.$lang_tag.'/';

        if (qtranxf_getLanguage() != 'en') {
            // if Kh
                if (strpos($page_topic->post_title, '[:kh]') !== false) {
                    $page_title = explode($lang_tag, $page_topic->post_title);
                    $pagetitle = trim(str_replace('[:]', '', $page_title[1]));
                } elseif (strpos($page_topic->post_title, '<!--:--><!--:kh-->') !== false) {
                    $page_title = explode('<!--:--><!--:kh-->', $page_topic->post_title);
                    $page_title = trim(str_replace('<!--:'.qtranxf_getLanguage().'-->', '', $page_title[1]));
                    $pagetitle = trim(str_replace('<!--:-->', '', $page_title));
                } elseif (strpos($page_topic->post_title, '<!--:-->')) {
                    $page_title = explode('<!--:-->', $page_topic->post_title);
                    $pagetitle = trim(str_replace('<!--:en-->', '', $page_title[0]));
                }
        } else {
            //if (preg_match("/[:kh]/" ,$page_topic->post_title)){
                if (strpos($page_topic->post_title, '[:kh]') !== false) {
                    $page_title = explode('[:kh]', $page_topic->post_title);
                    $pagetitle = trim(str_replace('[:en]', '', $page_title[0]));
                } elseif (strpos($page_topic->post_title, '[:vi]') !== false) {
                    $page_title = explode('[:vi]', $page_topic->post_title);
                    $pagetitle = trim(str_replace('[:en]', '', $page_title[0]));
                } elseif (strpos($page_topic->post_title, '[:]')) {
                    $page_title = explode('[:]', $page_topic->post_title);
                    $pagetitle = trim(str_replace('[:en]', '', $page_title[0]));
                } elseif (strpos($page_topic->post_title, '<!--:--><!--:kh-->')) {
                    $page_title = explode('<!--:--><!--:kh-->', $page_topic->post_title);
                    $pagetitle = trim(str_replace('<!--:en-->', '', $page_title[0]));
                } elseif (strpos($page_topic->post_title, '<!--:--><!--:vi-->')) {
                    $page_title = explode('<!--:--><!--:vi-->', $page_topic->post_title);
                    $pagetitle = trim(str_replace('<!--:en-->', '', $page_title[0]));
                } elseif (strpos($page_topic->post_title, '<!--:-->')) {
                    $page_title = explode('<!--:-->', $page_topic->post_title);
                    $pagetitle = trim(str_replace('<!--:en-->', '', $page_title[0]));
                } else {
                    $page_title = $page_topic->post_title;
                    $pagetitle = trim($page_title);
                }
        }
            //echo "<div style='display:none'>***".trim($title_str) ."== ".$pagetitle."</div>";
            if (trim(strtolower($title_str)) == strtolower($pagetitle)) {
                $page_id = $page_topic->ID;
            }
    }

    return $page_id;
}
 /****end Breadcrumb**/

/** SHOW CATEGORY BY Post type **/
function list_category_by_post_type($post_type = 'post', $args = '', $title = 1, $js_script = 1)
{
    global $post;
    if ($args == '') {
        $args = array(
        'orderby' => 'term_id',
        'parent' => 0,
        );
    }
    $categories = get_categories($args);
    $current_cat = get_queried_object();
    if (isset($current_cat->slug) && $current_cat->slug) {
        $current_cat_page = $current_cat->slug;
    } elseif (isset($current_cat->post_name)) {
        $current_cat_page = $current_cat->post_name;
    }
    if ($title == 1) {
        echo '<h2 class="widget-title">'.__('Categories', 'opendev').'</h2>';
    }

    echo "<ul class='opendev_taxonomy_widget_ul'>";
    foreach ($categories as $category) {
        $jackpot = true;
        $children = get_categories(array('parent' => $category->term_id, 'hide_empty' => 0, 'orderby' => 'term_id'));
        echo "<li class='cat_item'>";
        if( isset($current_cat_page)){
          print_category_by_post_type($category, $post_type, $current_cat_page);
        }
        if (!empty($children)) {
            echo '<ul>';
            walk_child_category_by_post_type($children, $post_type, $current_cat_page);
            echo '</ul>';
        }
        echo '</li>';
    }
    echo '</ul>';
    if ($js_script == 1) {
        ?>
        <script type="text/javascript">
          jQuery(document).ready(function($) {
          $('.opendev_taxonomy_widget_ul > li.cat_item').each(function(){
            if($('.opendev_taxonomy_widget_ul > li.cat_item:has(ul)')){
              $('.opendev_taxonomy_widget_ul > li.cat_item ul').siblings('span').removeClass("nochildimage-<?php echo COUNTRY_NAME;?>");
              $('.opendev_taxonomy_widget_ul > li.cat_item ul').siblings('span').addClass("plusimage-<?php echo COUNTRY_NAME;?>");
            }
            //if parent is showed, child need to expend
            if ($('span.<?php echo $current_cat_page;?>').length){
              $('span.<?php echo $current_cat_page;?>').siblings("ul").show();
              $('span.<?php echo $current_cat_page;?>').toggleClass('minusimage-<?php echo COUNTRY_NAME;?>');
              $('span.<?php echo $current_cat_page;?>').toggleClass('plusimage-<?php echo COUNTRY_NAME;?>');

              //if child is showed, parent expended
              $('span.<?php echo $current_cat_page;?>').parents("li").parents("ul").show();
              $('span.<?php echo $current_cat_page;?>').parents("li").parents("ul").siblings('span').toggleClass('minusimage-<?php echo COUNTRY_NAME;?>');
              $('span.<?php echo $current_cat_page;?>').parents("li").parents("ul").siblings('span').toggleClass('plusimage-<?php echo COUNTRY_NAME;?>');
            }
          });
          $('.opendev_taxonomy_widget_ul > li.cat_item span').click(function(event) {
            //event.preventDefault();
            var target =  $( event.target );
              if(target.parent("li").find('ul').length){
                target.parent("li").find('ul:first').slideToggle();
                target.toggleClass("plusimage-<?php echo COUNTRY_NAME;?>");
                target.toggleClass('minusimage-<?php echo COUNTRY_NAME;?>');
                }
            });
          });
         </script>
     <?php

    }//if js_script
} // end function

function print_category_by_post_type($category, $post_type = 'post', $current_cat = '')
{
    if ($current_cat == $category->slug) {
        $current_page = ' '.$current_cat;
    } else {
        $current_page = '';
    }
    echo "<span class='nochildimage-".COUNTRY_NAME.$current_page."'>";
    echo '<a href="'.get_category_link($category->cat_ID).'?post_type='.$post_type.'">';
    if ($current_cat == $category->slug) { // if page of the topic exists
                  echo "<strong class='".COUNTRY_NAME."-color'>";
        echo $category->name;
        echo '</strong>';
    } else {
        echo $category->name;
    }
    echo '</a>';
    echo '</span>';
}
function walk_child_category_by_post_type($children, $post_type, $current_cat = '')
{
    foreach ($children as $child) {
        // Get immediate children of current category
    $cat_children = get_categories(array('parent' => $child->term_id, 'hide_empty' => 1, 'orderby' => 'term_id'));
        echo '<li>';
    // Display current category
      print_category_by_post_type($child, $post_type, $current_cat);
    // if current category has children
    if (!empty($cat_children)) {
        // add a sublevel
      echo '<ul>';
      // display the children
        walk_child_category_by_post_type($cat_children, $post_type, $current_cat);
        echo '</ul>';
    }
        echo '</li>';
    }
}

/** END CATEGORY */

/**** Post Meta ******/
function show_date_and_source_of_the_post()
{
    ?>
  <div class="date">
     <span class="lsf">&#xE12b;</span>
       <?php
       if (function_exists('qtrans_getLanguage')) {
           if (qtrans_getLanguage() == 'kh' || qtrans_getLanguage() == 'km') {
               echo convert_date_to_kh_date(get_the_time('j.M.Y'));
           } else {
               echo get_the_time('j F Y');
           }
       } else {
           echo get_the_time('j F Y');
       }
    ?>
  </div>
  &nbsp;
  <?php
  if (taxonomy_exists('news_source') && isset($post)) {
      echo '<div class="news-source">';
      $terms_news_source = get_the_terms($post->ID, 'news_source');
      if ($terms_news_source && !is_wp_error($terms_news_source)) {
          if ($terms_news_source) {
              $news_sources = '';
              echo '<span class="icon-news"></span> ';
              foreach ($terms_news_source as $term) {
                  $term_link = get_term_link($term, 'news_source');
                  if (is_wp_error($term_link)) {
                      continue;
                  }
                  //We successfully got a link. Print it out.
                   $news_sources .= '<a href="'.$term_link.'"><srong>'.$term->name.'</srong></a>, ';
              }
              echo substr($news_sources, 0, -2);
          }
      } elseif (get_post_meta($post->ID, 'rssmi_source_feed', true)) {
          echo '<span class="icon-news"></span> ';
          $news_source_id = get_post_meta($post->ID, 'rssmi_source_feed', true);
          echo get_the_title($news_source_id);
      }
      echo '</div><!--news-source-->';
  }// if news_source exists
  if (taxonomy_exists('public_announcement_source')) {
      echo '<div class="news-source">';
      $terms_public_announcement_source = get_the_terms($post->ID, 'public_announcement_source');
      if ($terms_public_announcement_source && !is_wp_error($terms_public_announcement_source)) {
          if ($terms_public_announcement_source) {
              $public_announcement_sources = '';
              echo '<span class="icon-news"></span> ';
              foreach ($terms_public_announcement_source as $term) {
                  $term_link = get_term_link($term, 'public_announcement_source');
                  if (is_wp_error($term_link)) {
                      continue;
                  }
                  //We successfully got a link. Print it out.
                   $public_announcement_sources .= '<a href="'.$term_link.'"><srong>'.$term->name.'</srong></a>, ';
              }
              echo substr($public_announcement_sources, 0, -2);
          }
      } elseif (get_post_meta($post->ID, 'rssmi_source_feed', true)) {
          echo '<span class="icon-news"></span> ';
          $public_announcement_source_id = get_post_meta($post->ID, 'rssmi_source_feed', true);
          echo get_the_title($public_announcement_source_id);
      }
      echo '</div><!--news-source-->';
  }// if public_announcement_source exists
}
 //to set get_the_excerpt() limit words
 function excerpt($num, $read_more = '')
 {
     $limit = $num + 1;
     $excerpt = explode(' ', get_the_excerpt(), $limit);
     array_pop($excerpt);
     $excerpt_string = implode(' ', $excerpt);
     $excerpt_hidden_space = explode('?', $excerpt_string, $limit);
     array_pop($excerpt_hidden_space);
     $$excerpt_string = implode('?', $excerpt_hidden_space);
     $excerpt_words = $excerpt_string.' ...';
     if ($read_more != '') {
         $color_name = strtolower(str_replace('Open Development ', '', get_bloginfo('name'))).'-color';
         $excerpt_words .=  " (<a href='".get_permalink($post->ID)." ' class='".$color_name."'>".__($read_more, 'opendev').'</a>)';
     }

     return $excerpt_words;
 }

 function get_pages_templates_for_othersites($pages_templates)
 {
     $templates = get_page_templates();
     foreach ($templates as $template_name => $template_filename) {
         $template_files = explode('/', $template_filename);
         if (count($template_files) > 1) {
             if (strtolower($template_files[0]) != COUNTRY_NAME) {
                 $template_need_to_hide[] = $template_filename;
                 unset($pages_templates[$template_filename]);
             }
         }
     }

     return $pages_templates;
 }

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
