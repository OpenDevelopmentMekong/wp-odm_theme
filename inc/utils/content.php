<?php

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
			$pagetitle = qtrans_use(odm_language_manager()->get_current_language(), $page_topic->post_title, false);//get content by langauge

			if (trim(strtolower($title_str)) == strtolower($pagetitle)) {
					$page_id = $page_topic->ID;
					return $page_id;
			}
		}
}
 /****end Breadcrumb**/

/** SHOW CATEGORY BY Post type **/
function list_category_by_post_type($post_type = 'post', $args = '', $title = 1, $js_script = 1, $exclude_cat ='map-catalogue')
{
		global $post;
		if ($args == '') {
				$args = array(
				'post_type' => 'map-layer',
				'parent' => 0,
				'exclude' => $exclude_cat
				);
		}

		$categories = get_categories($args);
		$current_cat = get_queried_object();

		if (isset($current_cat->slug) && $current_cat->slug) {
				$current_cat_page = $current_cat->slug;
		} elseif (isset($current_cat->post_name)) {
  			$current_cat_page = $current_cat->post_name;
		} else {
				$current_cat_page = "post-type-category";
		}

		if ($title == 1) {
				echo '<h2 class="widget-title">'.__('Categories', 'odm').'</h2>';
		}

		echo "<ul class='odm_taxonomy_widget_ul'>";
		foreach ($categories as $category) {
				$jackpot = true;
				$args['parent'] = $category->term_id;
				$children = get_categories($args);

				echo "<li class='cat_item'>";
				if( isset($current_cat_page)){
					print_category_by_post_type($category, $post_type, $current_cat_page, $exclude_cat);
				}

				if (!empty($children)) {
						echo '<ul>';
						walk_child_category_by_post_type($children, $post_type, $current_cat_page, $exclude_cat);
						echo '</ul>';
				}
				echo '</li>';
		}
		echo '</ul>';
		if ($js_script == 1) {
				?>
				<script type="text/javascript">
					jQuery(document).ready(function($) {
					$('.odm_taxonomy_widget_ul > li.cat_item').each(function(){
						if($('.odm_taxonomy_widget_ul > li.cat_item:has(ul)')){
							$('.odm_taxonomy_widget_ul > li.cat_item ul').siblings('span').removeClass("nochildimage-<?php echo odm_country_manager()->get_current_country();?>");
							$('.odm_taxonomy_widget_ul > li.cat_item ul').siblings('span').addClass("plusimage-<?php echo odm_country_manager()->get_current_country();?>");
						}
						//if parent is showed, child need to expend
						if ($('span.<?php echo $current_cat_page;?>').length){
							$('span.<?php echo $current_cat_page;?>').siblings("ul").show();
							$('span.<?php echo $current_cat_page;?>').toggleClass('minusimage-<?php echo odm_country_manager()->get_current_country();?>');
							$('span.<?php echo $current_cat_page;?>').toggleClass('plusimage-<?php echo odm_country_manager()->get_current_country();?>');

							//if child is showed, parent expended
							$('span.<?php echo $current_cat_page;?>').parents("li").parents("ul").show();
							$('span.<?php echo $current_cat_page;?>').parents("li").parents("ul").siblings('span').toggleClass('minusimage-<?php echo odm_country_manager()->get_current_country();?>');
							$('span.<?php echo $current_cat_page;?>').parents("li").parents("ul").siblings('span').toggleClass('plusimage-<?php echo odm_country_manager()->get_current_country();?>');
						}
					});
					$('.odm_taxonomy_widget_ul > li.cat_item span').click(function(event) {
						//event.preventDefault();
						var target =  $( event.target );
							if(target.parent("li").find('ul').length){
								target.parent("li").find('ul:first').slideToggle();
								target.toggleClass("plusimage-<?php echo odm_country_manager()->get_current_country();?>");
								target.toggleClass('minusimage-<?php echo odm_country_manager()->get_current_country();?>');
								}
						});
					});
				 </script>
		 <?php

		}
}
// Check if post specific to any langue or not
function posts_for_both_and_current_languages($postID, $current_lang = "en", $taxonomy ="language"){
    $site_language = strtolower(get_the_language_by_language_code($current_lang));
    $terms = get_the_terms($postID, $taxonomy);
    if ( empty($terms) && !is_wp_error( $terms )) {
        return true;
    }else if (has_term( $site_language, $taxonomy, $postID )){
        return true;
    }else if ( !taxonomy_exists($taxonomy)){
        return true;
    }
    return false;
}

function echo_post_translated_by_od_team($postID, $current_lang = "en", $taxonomy ="language") {
	    $site_language = strtolower(get_the_language_by_language_code($current_lang)); //english
			$translated_term =  $site_language."-translated";
			$org_name = ucfirst(get_bloginfo('name'));
			$team_name = implode('', array_map(function($v) { return $v[0]; }, explode(' ', $org_name)));
			if (odm_country_manager()->get_current_country() == "mekong"):
				$team_name = substr($team_name, 0, -1). " ".ucfirst(odm_country_manager()->get_current_country());
			endif;
	    $terms = get_the_terms($postID, $taxonomy);
	    if (!is_wp_error( $terms ) && !empty($terms)) {
				if (has_term($translated_term, $taxonomy, $postID)) {
					echo "<p class='translated-by-team'><strong>".__('Summary translated by '.$team_name.' Team')."</strong></p>";
				}
			}
}

function print_category_by_post_type( $category, $post_type ="post", $current_cat='', $exclude_cat ='') {
 if ($current_cat == $category->slug){
     $current_page = " ".$current_cat;
  }else {
     $current_page = "";
  }
  if($category->cat_ID){
    $cat_ID = $category->cat_ID;
    $get_category_link = get_category_link( $category->cat_ID );
  }else if($category->term_id){
    $cat_ID = $category->term_id;
    $get_category_link = get_term_link( $category);
  }

	if(is_tax( 'layer-category' ) ||  get_post_type()== "map-layer"):
		$included_posttype = "";
	else :
		$included_posttype = '?post_type='.$post_type;
	endif;
  if($post_type == "map-layer" && is_page(array("map-explorer", "maps")) ){
    $cat_name = '<a href="' . $get_category_link. $included_posttype.'">';
    $cat_name .= $category->name;
    $cat_name .= "</a>";
    $count_layer_items = 0;
    $args_get_post = array(
        'post_type' => $post_type,
        'tax_query' => array(
                            array(
                              'taxonomy' => $category->taxonomy,
                              'field' => 'id',
                              'terms' => $cat_ID, // Where term_id of Term 1 is "1".
                              'include_children' => false
                            ),
														array(
															'taxonomy' => $category->taxonomy,
															'field' => 'id',
															'terms' => $exclude_cat,
															'operator' => 'NOT IN'
														 )
                          )
    );
    $query_get_post = new WP_Query( $args_get_post );
    if($query_get_post->have_posts() ){
			$layer_items = '';
      $cat_layer_ul = "<ul class='cat-layers switch-layers'>";
      while ( $query_get_post->have_posts() ) : $query_get_post->the_post();
          if(posts_for_both_and_current_languages(get_the_ID(), odm_language_manager()->get_current_language())){
            $count_layer_items++;
            $layer_items .= display_layer_as_menu_item_on_mapNavigation(get_the_ID(), 0);
          }
      endwhile;

      wp_reset_postdata();
      $cat_layer_close_ul = "</ul>";
      $print_items = "";
      if ($count_layer_items > 0){
          $print_items .= $cat_name;
          $print_items .= $cat_layer_ul;
            $print_items .= $layer_items;
          $print_items .= $cat_layer_close_ul;

      }
      return $print_items;
    } //$query_get_post->have_posts
  }else {
    echo "<span class='nochildimage-".odm_country_manager()->get_current_country().$current_page."'>";
            echo '<a href="' . get_category_link( $category->cat_ID ) .$included_posttype.'">';
                if ($current_cat == $category->slug){ // if page of the topic exists
                    echo "<strong class='".odm_country_manager()->get_current_country()."-color'>";
                        echo $category->name;
                    echo "</strong>";
                }else{
                      echo $category->name;
                }
            echo "</a>";
      echo "</span>";
  }
}

function walk_child_category_by_post_type( $children, $post_type, $current_cat = "",  $exclude_cat ="") {
    if($post_type == "map-layer" && is_page(array("map-explorer", "maps")) ){
       $cat_item_and_posts = "";
       $count_items_of_subcat = 0;
          foreach($children as $child){
            $cat_children = get_categories( array('parent' => $child->term_id, 'hide_empty' => 1, 'orderby' => 'name') );
            $get_cat_and_posts = print_category_by_post_type($child, $post_type, $current_cat, $exclude_cat);
            if ($get_cat_and_posts != ""){
                $count_items_of_subcat++;
                $cat_item_and_posts .= "<li class='cat-item cat-item-".$child->term_id."'>";
                    // Display current category
                    $cat_item_and_posts .= $get_cat_and_posts;
                    // if current category has children
                    if ( !empty($cat_children) ) {
                      // add a sublevel
                      // display the children
                        walk_child_category_by_post_type( $cat_children, $post_type, $current_cat, $exclude_cat);
                    }
                $cat_item_and_posts .= "</li>";
            }
          }//foreach
      $print_sub_cat_and_posts = "";
      if($count_items_of_subcat > 0){ //if sub cats have layer items and sub-cats
          $print_sub_cat_and_posts .= '<ul class="children">';
          $print_sub_cat_and_posts .= $cat_item_and_posts;
          $print_sub_cat_and_posts .= '</ul>';
      }
      return $print_sub_cat_and_posts;
    }else { //widget
        foreach($children as $child){
          // Get immediate children of current category
          $cat_children = get_categories( array('parent' => $child->term_id, 'hide_empty' => 1, 'orderby' => 'term_id', ) );
          echo "<li>";
          // Display current category
            print_category_by_post_type($child, $post_type, $current_cat, $exclude_cat);
          // if current category has children
          if ( !empty($cat_children) ) {
            // add a sublevel
            echo "<ul>";
            // display the children
              walk_child_category_by_post_type( $cat_children, $post_type, $current_cat,  $exclude_cat);
            echo "</ul>";
          }
          echo "</li>";
        }
    }
}
/** END CATEGORY */

/**** Post Meta ******/
function echo_post_meta($post, $show_elements = array('date','sources','categories','tags'))
{
	?>
	<div class="post-meta">
		<ul>
      <?php if (in_array('date',$show_elements)): ?>
        <li class="date">
  				<i class="fa fa-clock-o"></i>
  					 <?php
  					 if (function_exists('qtrans_getLanguage')) {
  							 if (odm_language_manager()->get_current_language() == 'km') {
  									 echo convert_date_to_kh_date(get_the_time('j.M.Y'),$post->ID);
  							 } else {
  									 echo get_the_time('j F Y',$post->ID);
  							 }
  					 } else {
  							 echo get_the_time('j F Y',$post->ID);
  					 }
  				?>
  			</li>
      <?php endif; ?>
      <?php if (in_array('sources', $show_elements)):
        if (taxonomy_exists('news_source') && isset($post)) {
  				$terms_news_source = get_the_terms($post->ID, 'news_source');
  				if ($terms_news_source && !is_wp_error($terms_news_source)) {
  					if ($terms_news_source) {
  						$news_sources = '';

  						foreach ($terms_news_source as $term) {
  							$term_link = get_term_link($term, 'news_source');
  							if (is_wp_error($term_link)) {
  								continue;
  							}
  							//We successfully got a link. Print it out.
  							$news_sources .= '<a href="'.$term_link.'">'.$term->name.'</a>, ';
  							if (isset($news_sources)):
  								echo '<li class="news-source">';
  								echo '<i class="fa fa-chain"></i> ';
  								echo substr($news_sources, 0, -2);
  								echo '</li> ';
  							endif;
  						}

  					}
  				} elseif (get_post_meta($post->ID, 'rssmi_source_feed', true)) {
  					echo '<li class="feed">';
  					echo '<span class="icon-news"></span> ';
  					$news_source_id = get_post_meta($post->ID, 'rssmi_source_feed', true);
  					echo get_the_title($news_source_id);
  					echo '</li> ';
  				}

  			}// if news_source exists
  			if (taxonomy_exists('public_announcement_source')) {
  					echo '<li class="source-cateogy">';
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
  											 $public_announcement_sources .= '<a href="'.$term_link.'">'.$term->name.'</a>, ';
  									}
										echo '<i class="fa fa-chain"></i> ';
  									echo substr($public_announcement_sources, 0, -2);
  							}
  					} elseif (get_post_meta($post->ID, 'rssmi_source_feed', true)) {
  							echo '<span class="icon-news"></span> ';
  							$public_announcement_source_id = get_post_meta($post->ID, 'rssmi_source_feed', true);
  							echo get_the_title($public_announcement_source_id);
  					}
  			}
      endif; ?>
      <?php if (in_array('categories',$show_elements) && !empty(get_the_category())): ?>
        <li class="categories">
  				<i class="fa fa-folder-o"></i>
  				<?php the_category(''); ?>
  			</li>
      <?php endif; ?>
      <?php if (in_array('tags',$show_elements)):
        the_tags('<li class="post-tags"><i class="fa fa-tags"></i> ', ', ', '</li>');
      endif; ?>
		</ul>
	</div>

	<?php

}

 function odm_excerpt($post, $num = 40, $read_more = '')
 {
		 $limit = $num + 1;
		 $excerpt = explode(' ', strip_shortcodes(get_the_excerpt($post->ID)), $limit);
		 array_pop($excerpt);
		 $excerpt_string = implode(' ', $excerpt);
		 $excerpt_hidden_space = explode('?', $excerpt_string, $limit);
		 array_pop($excerpt_hidden_space);
		 $$excerpt_string = implode('?', $excerpt_hidden_space);
		 $excerpt_words = $excerpt_string.' ...';
		 if ($read_more != '') {
				 $color_name = strtolower(str_replace('Open Development ', '', get_bloginfo('name'))).'-color';
				 $excerpt_words .=  " (<a href='".get_permalink($post->ID)." ' class='".$color_name."'>".__($read_more, 'odm').'</a>)';
		 }

		 return $excerpt_words;
 }


function available_post_types(){
	 $args = array(
			'public'   => true
	 );

	 $output = 'objects';
	 $operator = 'and';
	 $post_types = get_post_types( $args, $output, $operator );

	 return $post_types;
}

function available_custom_post_types(){
	 $args = array(
			'public'   => true,
			'_builtin' => false
	 );

	 $output = 'objects';
	 $operator = 'and';
	 $post_types = get_post_types( $args, $output, $operator );

	 return $post_types;
 }

 function available_post_types_search(){
 	 return array('topic','annoucement','profile','site-update','news-article','story', 'map-layer','nav_menu_item');
  }

 function content_types_breakdown_for_query($search_term,$posts_per_page){

	 $response = array();
	 wp_reset_query();
	 if(isset($search_term) && $search_term):

		 $supported_post_types = array('topic','news-article', 'story');

		 foreach ( $supported_post_types as $post_type):

			 $post_type_obj = get_post_type_object($post_type);
			 $post_type_label = $post_type_obj->label;

			 $response[$post_type] = array(
				 'title' => $post_type_label,
				 'posts' => array()
			 );

			 $args = array('s' => $search_term,
										 'posts_per_page' => $posts_per_page,
										 'post_type' => $post_type,
										 'post_status' => 'publish');
			 $posts = get_posts($args);

			 foreach ($posts as $post):
				 array_push($response[$post_type]['posts'],array(
					 'ID' => $post->ID,
					 'title' => $post->post_title,
					 'excerpt' => odm_excerpt($post),
					 'post_type' => $post_type,
					 'url' => get_permalink($post->ID),
					 'thumbnail' => odm_get_thumbnail($post->ID,true)
				 ));
			 endforeach;
		 endforeach;
	 endif;

	 wp_reset_postdata();

	 return $response;

 }

 function is_page_dataset_detail_template(){
	 return (strpos(get_page_template(), 'page-dataset-detail') !== false);
 }

 function get_categories_array($post){
		return array_map(function($cat){
			return $cat->name;
		},get_the_category($post->ID));
 }

 function odm_echo_extras(){

   if (function_exists('get')) :
       if (get('author') == '' && get('author'.odm_language_manager()->get_current_language()) == ''):
         echo '';
       else:
         $news_source_info = '<span class="lsf">&#xE041;</span> ';
         if (get('author'.odm_language_manager()->get_current_language()) != ''):
             $news_source_info .= get('author'.odm_language_manager()->get_current_language()).'<br />';
         else:
             $news_source_info .= get('author').'<br />';
         endif;
       endif;
   endif;

   if (function_exists('get')):
     if (get('article_link') == '' && get('article_link'.odm_language_manager()->get_current_language()) == ''):
         echo '';
     else:
       if (get('article_link'.odm_language_manager()->get_current_language()) != ''):
         $source = get('article_link'.odm_language_manager()->get_current_language());
       else:
         $source = get('article_link');
       endif;
     endif;

     if (isset($source) && $source != ''):
         if (substr($source, 0, 7) != 'http://'):
           $news_source_info .= '<a href="http://'.$source.'" target="_blank">http://'.$source.'</a>';
         else:
           $news_source_info .= '<a href="'.$source.'" target="_blank">'.$source.'</a>';
         endif;
     endif;
   endif;

   if (isset($news_source_info) && $news_source_info != ''):
     echo '<p>'.$news_source_info.'</p>';
   endif;

 }

 ?>
