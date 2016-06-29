<?php

function get_all_parent_category($current_cat_id, $post_type, $separator = '', $current_page_name = '')
{
    global $post,$wp_query;

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
        echo the_separated_breadcrumb($separator, $page_id_exist, $post_type);
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
                  }
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
            $post_type = get_query_var('post_type');
            if ($post_type) {
              $post_type_data = get_post_type_object($post_type);
              $post_type_slug = $post_type_data->rewrite['slug'];
            }
            echo '<li class="item-current item-current-'.$post_type_slug.'"> ';
            echo '<div class="bread-current bread-current-'.$post_type_slug.'">';
            echo post_type_archive_title();
            echo '</div>';
            echo '</li>';
          }
    }
    echo '</ul>';
}

 ?>
