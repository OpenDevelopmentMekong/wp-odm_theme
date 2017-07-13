<?php

function get_all_parent_category($current_cat_id, $post_type, $separator = '', $current_page_name = '')
{
    global $post,$wp_query;

    $parent_cat = get_category_parents($current_cat_id, false);
    $parent_cats = explode('/', substr($parent_cat, 0, -1));
    $queried_post_type = isset($_GET['queried_post_type']) ? $_GET['queried_post_type'] : get_post_type();
    foreach ($parent_cats as $p_cat) {
        $page_title = $p_cat;
        $page_id_exist = get_post_or_page_id_by_title($page_title);
        $page = get_post($page_id_exist);
        $page_slug = $post->post_name;

        echo '<li class="item-topic item-topic-'.$page_id_exist.' item-topic-'.$page_slug.'">';
        if ($page_id_exist) {
            $page_name_title = trim(strtolower($page_title));
            if ($page_name_title == $current_page_name) {
                echo '<div class="text-bgcolor bread-current-page bread-current-'.$page_id_exist.'" title="'.$page_title.'">';//strong if current page
            }
            echo '<a class="bread-topic bread-topic-'.$page_id_exist.' bread-topic-'.$page_slug.'" href="'.get_permalink($page_id_exist).'?queried_post_type='.$queried_post_type.'" title="'.$page_title.'">';
        }
        echo $page_title;

        if ($page_id_exist) {
            echo '</a>';
            if ($page_name_title == $current_page_name) {
               echo '</div>';
             }
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

function echo_the_breadcrumbs()
{
    // Settings
    $separator = '/'; //'&gt;';

    // Build the breadcrums
    echo '<ul id="breadcrumbs" class="breadcrumb breadcrumbs">';

    if (!is_front_page()):
			echo_the_breadcrumb_home($separator);
		endif;

    if (is_single()):
        $post_type_name = get_post_type(get_the_ID());
        $post_type = get_post_type_object($post_type_name);
        $post_type_lable = ($post_type->labels->name =="Posts")? __("Blogs", "odm") : $post_type->labels->name;
        $post_type_slug = ($post_type->labels->name =="Posts")? 'blog' : $post_type->rewrite['slug'];
        echo '<li class="item-post-type"><a class="bread-current bread-'.$post_type_name.'" href="/'. $post_type_slug .'" title="'.$post_type_lable.'">'.__($post_type_lable, "odm").'</a></li>';
        echo the_separated_breadcrumb($separator, '', 'post-type');
        if ($post_type_name  == 'topic'):
          echo_the_breadcrumb_single_topic($separator);
        else:
          echo_the_breadcrumb_single($separator);
        endif;
    elseif (is_category()):
      echo_the_breadcrumb_category($separator);
    elseif (is_page() && !empty(get_post_ancestors(get_the_ID()))):
			echo_the_breadcrumb_page_with_parents($separator);
    elseif (is_page() && is_page_template('page-dataset-detail.php')):
			echo_the_breadcrumb_dataset_detail_page($separator);
		elseif (is_page()):
			echo_the_breadcrumb_page_single($separator);
    elseif (is_tag()):
			echo_the_breadcrumb_tag($separator);
    elseif (is_day()):
			echo_the_breadcrumb_daily_archive($separator);
		elseif (is_month()):
			echo_the_breadcrumb_monthly_archive($separator);
    elseif (is_year()):
			echo_the_breadcrumb_yearly_archive($separator);
    elseif (is_author()):
      echo_the_breadcrumb_author($separator);
    elseif (get_query_var('paged')):
      echo_the_breadcrumb_paged($separator);
    elseif (is_search()):
			echo_the_breadcrumb_search($separator);
		elseif (is_404()):
      echo_the_breadcrumb_404($separator);
    elseif (is_post_type_archive()):
      echo_the_breadcrumb_post_type_archive($separator);
    endif;
    echo '</ul>';
}

function echo_the_breadcrumb_home($separator){
	echo '<li class="item-home"><a class="bread-link bread-home" href="'.get_home_url().'" title="Home">';
	echo '<i class="fa fa-home"></i>';
	echo '</a></li>';
	echo the_separated_breadcrumb($separator, '', 'home');
}

function echo_the_breadcrumb_single($separator){
	$get_parent= get_post_ancestors(get_the_ID());
	if($get_parent):
		$parent_id = $get_parent[0];
		$parent = get_post($parent_id);
		if(!empty($parent) ):
			echo '<li class="item-parent item-parent-' . $parent_id . ' item-parent-' . $parent->post_name . '"><a class="bread-parent bread-parent-' . $parent_id . ' bread-parent-' . $parent->post_name . '" href="' . get_post_permalink($parent_id) . '" title="' . get_the_title($parent_id). '">' . get_the_title($parent_id) . '</a></li>';
			echo the_separated_breadcrumb($separator,  $parent_id , 'parent');
		endif;
	endif;
	echo '<li class="item-current item-'.get_the_ID().'">';
	echo '<div class="text-bgcolor bread-current-page bread-'.get_the_ID().'" title="'.get_the_title().'">';
		echo '<a class="item-current bread-current-'.get_the_ID().'" href="'.get_permalink().'" title="'.get_the_title().'">';
			echo get_the_title();
		echo '</a>';
	echo '</div>';
	echo '</li>';
}

function echo_the_breadcrumb_single_topic($separator){
	$post_type_name = get_post_type(get_the_ID());
	$post_type = get_post_type_object($post_type_name);
	$get_topic_title = get_the_title();
	$cats = get_the_category(get_the_ID());
	if ($cats):
		// if post is in this category
		foreach ($cats as $cat):
			if (in_category($cat->term_id)):
				$page_title = trim(strtolower($get_topic_title));
				$cat_name = trim(strtolower($cat->name));
				// Which Category and Post have the same name
				if ($cat_name == $page_title):
					$cat_id = $cat->term_id;
					get_all_parent_category($cat_id, $post_type_name, $separator, $page_title);
				endif;
			endif;
		endforeach;
	else:
		//if topic page is not categorized or the topic name is different from the category
		echo '<li class="item-current item-'.get_the_ID().'"><div class="text-bgcolor bread-current-page bread-'.get_the_ID().'" title="'.get_the_title().'">'.get_the_title().'</div></li>';
	endif;
}

function echo_the_breadcrumb_category($separator){
	global $wp_query;
	$term = $wp_query->queried_object;
	$categories = get_the_category();

	// Category page
	$parent_cat = get_category_parents($term->term_id, false, '||');
	$parent_cat = substr($parent_cat, 0, -2);
	$parent_cats = explode('||', $parent_cat);
  $queried_post_type = isset($_GET['queried_post_type']) ? $_GET['queried_post_type'] : get_post_type();
  if($parent_cats && $categories):
  	foreach ($parent_cats as $cat):
  		echo '<li class="item-current item-cat-'.$categories[0]->term_id.' item-cat-'.$categories[0]->category_nicename.'">';
  		if ($cat === end($parent_cats)):
  				echo '<strong class="bread-current bread-cat-'.$categories[0]->term_id.' bread-cat-'.$categories[0]->category_nicename.'">';
  		endif;
  		echo '<a href="'.get_category_link($categories[0]->term_id).'?queried_post_type='.$queried_post_type.'">'.$cat.'</a>';

  		if ($cat === end($parent_cats)):
  				echo '</strong>';
  		endif;
  		echo '</li>';

  		//add separated
  		if ($cat != end($parent_cats)):
  				echo the_separated_breadcrumb($separator, $categories[0]->term_id, 'category');
  		endif;
  	endforeach;
  endif;
}

function echo_the_breadcrumb_dataset_detail_page($separator){
	$dataset_id = isset($_GET["id"]) ? $_GET["id"] : null;
	$search_query = isset($_GET["search_query"]) ? base64_decode($_GET["search_query"]) : null;
	if (isset($search_query)):
		$search_term = wp_odm_solr_parse_query_from_string($search_query);
		echo '<li class="item-current item-'.$search_term.'"><a class="item-current bread-current-'.$search_term.'" href="/'.$search_query.'"><strong>' . __('Search results for:','odm') . ' \'' . $search_term . '\'</strong></a></li>';
		echo the_separated_breadcrumb($separator, '', 'page');
	else:
		echo '<li class="item-current item-dataset-detail"><a class="item-current bread-current-item-dataset-detail"><strong>' . __('Dataset detail','odm') . '</strong></a></li>';
		echo the_separated_breadcrumb($separator, '', 'page');
	endif;
	echo '<li class="item-current item-'.get_the_ID().'"><a class="bread-'.get_the_ID().'" href="#"> '. wpckan_get_dataset_title($dataset_id) .'</a></li>';
}

function echo_the_breadcrumb_page_single($separator){
	echo '<li class="item-current item-'.get_the_ID().'"><strong class="bread-current bread-'.get_the_ID().'"> '.get_the_title().'</strong></li>';
}

function echo_the_breadcrumb_page_with_parents($separator){
	// If child page, get parents
	$anc = get_post_ancestors(get_the_ID());
	// Get parents in the right order
	$anc = array_reverse($anc);
	// Parent page loop
	foreach ($anc as $ancestor):
		$parents .= '<li class="item-parent item-parent-'.$ancestor.'"><a class="bread-parent bread-parent-'.$ancestor.'" href="'.get_permalink($ancestor).'" title="'.get_the_title($ancestor).'">'.get_the_title($ancestor).'</a></li>';
	endforeach;
	// Display parent pages
	echo $parents;
	echo the_separated_breadcrumb($separator, $ancestor, 'page');
	echo '<li class="item-current item-'.get_the_ID().'">';
	if (!isset($_GET['map_id']) and $_GET['map_id'] != ''):
		echo '<strong title="'.get_the_title().'">';
	endif;
	echo '<a class="item-current bread-current-'.get_the_ID().'" href="'.get_permalink().'" title="'.get_the_title().'">';
	echo get_the_title();
	echo '</a>';
	if (!isset($_GET['map_id']) && $_GET['map_id'] != ''):
		echo '</strong>';
	endif;
	echo '</li>';
}

function echo_the_breadcrumb_tag($separator){
	// Get tag information
	$term_id = get_query_var('tag_id');
	$taxonomy = 'post_tag';
	$args = 'include='.$term_id;
	$terms = get_terms($taxonomy, $args);
  $queried_post_type = isset($_GET['queried_post_type']) ? $_GET['queried_post_type'] : get_post_type();
	// Display the tag name
	echo '<li class="item-current item-tag-'.$terms[0]->term_id.' item-tag-'.$terms[0]->slug.'">';
	echo '<strong class="bread-current bread-tag-'.$terms[0]->term_id.'bread-tag-'.$terms[0]->slug.'">';
	echo '<a href="'.get_tag_link($terms[0]->term_id).'?queried_post_type='.$queried_post_type.'">';
		_e($terms[0]->name, "odm");
	echo '</a>';
	echo '</strong></li>';
}

function echo_the_breadcrumb_daily_archive($separator){
	// Year link
	echo '<li class="item-year item-year-'.get_the_time('Y').'"><a class="bread-year bread-year-'.get_the_time('Y').'" href="'.get_year_link(get_the_time('Y')).'" title="'.get_the_time('Y').'">'.get_the_time('Y').' </a></li>';
	echo the_separated_breadcrumb($separator, get_the_time('Y'), 'archive');
	// Month link
	echo '<li class="item-month item-month-'.get_the_time('m').'"><a class="bread-month bread-month-'.get_the_time('m').'" href="'.get_month_link(get_the_time('Y'), get_the_time('m')).'" title="'.get_the_time('M').'">'.get_the_time('M').' </a></li>';
	echo the_separated_breadcrumb($separator, get_the_time('m'), 'archive');
	// Day display
	echo '<li class="item-current item-'.get_the_time('j').'"><a class="bread-month bread-month-'.get_the_time('m').'" href="'.get_day_link(get_the_time('Y'), get_the_time('m'), get_the_time('j')).'" title="'.get_the_time('M').'"><strong class="bread-current bread-'.get_the_time('j').'"> '.get_the_time('jS').'</strong></a> Archives</li>';
}

function echo_the_breadcrumb_monthly_archive($separator){
	echo '<li class="item-year item-year-'.get_the_time('Y').'"><a class="bread-year bread-year-'.get_the_time('Y').'" href="'.get_year_link(get_the_time('Y')).'" title="'.get_the_time('Y').'">'.get_the_time('Y').' </a></li>';
	echo the_separated_breadcrumb($separator, get_the_time('Y'), 'archive');
	echo '<li class="item-month item-month-'.get_the_time('m').'"><a class="bread-month bread-month-'.get_the_time('m').'" href="'.get_month_link(get_the_time('Y'), get_the_time('m')).'" title="'.get_the_time('M').'"><strong class="bread-month bread-month-'.get_the_time('m').'" title="'.get_the_time('M').'">'.get_the_time('M').'</strong></a> Archives</li>';
}

function echo_the_breadcrumb_yearly_archive($separator){
	echo '<li class="item-current item-current-'.get_the_time('Y').'"><a class="bread-year bread-year-'.get_the_time('Y').'" href="'.get_year_link(get_the_time('Y')).'" title="'.get_the_time('Y').'"><strong class="bread-current bread-current-'.get_the_time('Y').'" title="'.get_the_time('Y').'">'.get_the_time('Y').'</strong></a> Archives</li>';
}

function echo_the_breadcrumb_author($separator){
	global $author;
	$userdata = get_userdata($author);
	echo '<li class="item-current item-current-'.$userdata->user_nicename.'"><strong class="bread-current bread-current-'.$userdata->user_nicename.'" title="'.$userdata->display_name.'">'.'Author: '.$userdata->display_name.'</strong></li>';
}

function echo_the_breadcrumb_paged($separator){
	$post_type = get_query_var('post_type');
	if ($post_type && (null !== get_post_type_object($post_type))):
		$post_type_data = get_post_type_object($post_type);
		$post_type_slug = $post_type_data->rewrite['slug'];
		echo '<li class="item-current item-current-'.$post_type_slug.'"> ';
		echo '<div class="bread-current bread-current-'.$post_type_slug.'">';
			_e(post_type_archive_title("", false), "odm");
		echo '</div>';
		echo '</li>';
		echo the_separated_breadcrumb($separator, '', 'post-type');
	endif;

	// Paginated archives
	echo '<li class="item-current item-current-'.get_query_var('paged').'"><strong class="bread-current bread-current-'.get_query_var('paged').'" title="Page '.get_query_var('paged').'">'.__('Page').' '.get_query_var('paged').'</strong></li>';
}

function echo_the_breadcrumb_search($separator){
	echo '<li class="item-current item-current-'.get_search_query().'"><strong class="bread-current bread-current-'.get_search_query().'" title="' . __('Search results for:','odm') . ' ' . get_search_query(). '">' . __('Search results for:','odm') . ' ' . get_search_query().'</strong></li>';
}

function echo_the_breadcrumb_404($separator){
	echo '<li>'.'Error 404'.'</li>';
}

function echo_the_breadcrumb_post_type_archive($separator){
	$post_type = get_query_var('post_type');
	if ($post_type) {
		$post_type_data = get_post_type_object($post_type);
		$post_type_slug = $post_type_data->rewrite['slug'];
		$post_type_label = $post_type_data->labels->name;
	}
	echo '<li class="item-current item-current-'.$post_type_slug.'"> ';
	echo '<div class="bread-current bread-current-'.$post_type_slug.'">';
		_e(post_type_archive_title("", false), "odm");
	echo '</div>';
	echo '</li>';
}
 ?>
