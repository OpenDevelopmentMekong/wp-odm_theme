<?php

function get_post_type_icon_class($type){

	$icon = "fa fa-database";
	if ($type == "dataset"){
		$icon = "fa fa-database";
	}elseif ($type == "library_record"){
		$icon = "fa fa-book";
	}elseif ($type == "laws_record"){
		$icon = "fa fa-gavel";
	}elseif ($type == "agreement"){
		$icon = "fa fa-handshake-o";
	}elseif ($type == "map-layer"){
		$icon = "fa fa-map-marker";
	}elseif ($type == "news-article"){
		$icon = "fa fa-quote-left";
	}elseif ($type == "topic"){
		$icon = "fa fa-list";
	}elseif ($type == "profiles"){
		$icon = "fa fa-briefcase";
	}elseif ($type == "story"){
		$icon = "fa fa-lightbulb-o";
	}elseif ($type == "announcement"){
		$icon = "fa fa-bullhorn";
	}elseif ($type == "site-update"){
		$icon = "fa fa-flag";
	}

	return $icon;
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
			$pagetitle = apply_filters('translate_text', $page_topic->post_title, odm_language_manager()->get_current_language());
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
				echo '<h2 class="widget-title">'.__('Topic', 'odm').'</h2>';
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
    $site_language = strtolower(odm_language_manager()->get_the_language_by_language_code($current_lang));
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
function echo_post_meta($the_post, $show_elements, $order = 'created')
{
	global $post;
	$post = $the_post;
	?>
	<div class="post-meta">
		<ul>
      <?php if (in_array('date',$show_elements)): ?>
        <li class="date">
					<?php if ($order == 'modified'): ?>
  					<i class="fa fa-pencil"></i>
						<?php
						 if (odm_language_manager()->get_current_language() == 'km') {
								 echo convert_date_to_kh_date(get_the_modified_time('j.M.Y'));
						 } else {
								 echo get_the_modified_time('j F Y');
						 }
					  ?>
					<?php else: ?>
						<i class="fa fa-clock-o"></i>
						<?php
						 if (odm_language_manager()->get_current_language() == 'km') {
								 echo convert_date_to_kh_date(get_the_time('j.M.Y'));
						 } else {
								 echo get_the_time('j F Y');
						 }
					  ?>
					<?php endif; ?>
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
        <li class="categories">&nbsp;
  				<i class="fa fa-folder-o"></i>
  				<?php the_category(' / '); ?>
  			</li>
      <?php endif; ?>
      <?php if (in_array('tags',$show_elements)):
        the_tags('<li class="post-tags"><i class="fa fa-tags"></i> ', ' / ', '</li>');
      endif; ?>
			<?php if (in_array('show_summary_translated_by_odc_team',$show_elements)): ?>
				<?php echo_post_translated_by_od_team(get_the_ID());
			endif; ?>
			<?php
				if (odm_country_manager()->get_current_country() == "mekong" && in_array('country',$show_elements)): ?>
        <li class="post-country">
					<i class="fa fa-globe"></i>
					<?php
						$country = odm_country_manager()->get_country_name(odm_country_manager()->get_current_country());
						_e($country,'odm');
					 ?>
				</li>
			<?php
      endif; ?>
			<?php if (in_array('language',$show_elements)): ?>
				<li class="post-languages">
					<?php
		        odm_language_manager()->print_language_flags_for_post($post);
		      endif; ?>
				</li>
		</ul>
	</div>

	<?php

}

function odm_excerpt($the_post, $num = 40, $read_more = '')
 {
	  global $post;
		$post = $the_post;
		$limit = $num;

		$post_content = apply_filters('the_content',$post->post_content);
		$translated_content = apply_filters('translate_text',$post_content, odm_language_manager()->get_current_language());
		$stripped_content = strip_tags($translated_content);
		$stripped_content = strip_shortcodes($stripped_content);

		$excerpt_hidden_space = explode("​", $stripped_content, $limit); //explode by zerowidthspace​
		$excerpt_string = implode("​", $excerpt_hidden_space); //implode by zerowidthspace
		$excerpt_words = $excerpt_string . ' ...';

		if ($read_more != '') {
			 $color_name = odm_country_manager()->get_current_country().'-color';
			 $excerpt_words .=  " (<a href='".get_permalink($post->ID)." ' class='".$color_name."'>".__($read_more, 'odm').'</a>)';
		}

		return $excerpt_words;
 }

function echo_post_translated_by_od_team($postID, $current_lang = "en", $taxonomy ="language") {
 	    $site_language = strtolower(odm_language_manager()->get_the_language_by_language_code($current_lang)); //english
 			$translated_term =  $site_language."-translated";
			$team_name = "OD". ucfirst(substr(odm_country_manager()->get_current_country(), 0, 1));
 			if (odm_country_manager()->get_current_country() == "mekong"):
 				$team_name = "OD". " ".ucfirst(odm_country_manager()->get_current_country());
 			endif;
 	    $terms = get_the_terms($postID, $taxonomy);
 	    if (!is_wp_error( $terms ) && !empty($terms)) {
 				if (has_term($translated_term, $taxonomy, $postID)) {
 					echo "<li class='translated-by-team'> <i class='fa fa-pencil' aria-hidden='true'></i> &nbsp;" .__('Summary translated by '.$team_name.' Team')."</li>";
 				}
 			}
 }

function echo_documents_cover ($postID = "") {
	$postID = $postID ? $postID : get_the_ID();
 //Get Cover image
	$get_cover = get_post_meta($postID, 'cover', true);
	$get_localized_cover = get_post_meta($postID, 'cover_'.odm_language_manager()->get_current_language(), true);

	if ($get_cover != '' || $get_localized_cover != ''):

		$img_attr = array("h" => 600, "w" => 800, "zc" => 1, "q" =>100);
		$files_mf_dir = get_bloginfo('url')."/wp-content/blogs.dir/".get_current_blog_id()."/files_mf/";

		if($get_localized_cover !=""){
			$get_img = '<img class="attachment-thumbnail" src="'.$files_mf_dir.$get_localized_cover.'">';
			$large_img = $files_mf_dir.$get_cover;
		}
		else{
			if($get_cover !=""){
				$get_img = '<img class="attachment-thumbnail" src="'.$files_mf_dir.$get_cover.'">';
				$large_img = $files_mf_dir.$get_cover; // get_image('cover',1,1,0,null,$img_attr);
			}
			else {
				$get_img = '<img class="attachment-thumbnail" src="'.$files_mf_dir.$get_localized_cover.'">';
				$large_img = $files_mf_dir.$get_cover;
			}
		}

		if($get_img !=""):
			echo '<div class="documents_cover">';
				echo '<a target="_blank" href="'.$large_img.'" rel="" >'.$get_img.'</a>';
			echo '</div>'; //<!-- documents_cover -->
		endif;
	endif;
}

function echo_downloaded_documents ($postID = "") {
	$postID = $postID ? $postID : get_the_ID();
	$country_name = odm_country_manager()->get_current_country();
	$local_lang = 'en';
	if(function_exists('qtrans_getSortedLanguages')){
		$enabled_languages_codes = qtrans_getSortedLanguages( true );
		if(!empty($enabled_languages_codes[1])):
			$local_lang = $enabled_languages_codes[1];
		endif;
	}

	//Get Download files
	$get_document = get_post_meta($postID, 'upload_document', true);
	$get_localized_document = get_post_meta($postID, 'upload_document_'.$local_lang, true);
	if ($get_document != '' || $get_localized_document != ''):
			echo "<div id='documents_download'><span>";
			_e("Download: ");
			//Get English PDF
			if($get_document !=""){
				echo '<a target="_blank" href="'.get_bloginfo("url").'/pdf-viewer/?pdf=files_mf/'.$get_document.'">';
					echo '<img src="'.get_bloginfo('stylesheet_directory').'/img/en_us.png" /> ';
					_e ('English PDF');
				echo '</a>';
			}
			else{
				echo '<img src="'.get_bloginfo('stylesheet_directory').'/img/en_us.png" /> ';
				_e("English PDF not available");
			}
			echo "&nbsp; &nbsp;";

			//Get Khmer PDF
			if($get_localized_document !=""){
				echo '<a target="_blank" href="'.get_bloginfo("url").'/pdf-viewer/?pdf=files_mf/'.$get_localized_document.'">';
					echo '<img src="'.get_bloginfo('stylesheet_directory').'/img/'. $country_name .'.png" /> ';
					echo __(ucfirst(odm_language_manager()->get_the_language_by_language_code($local_lang)). " " ."PDF");
				echo '</a>';
			}
			else{
				echo '<img src="'.get_bloginfo('stylesheet_directory').'/img/'. $country_name .'.png" /> ';
				echo __(ucfirst(odm_language_manager()->get_the_language_by_language_code($local_lang)). " " . "PDF not available");
			}
			echo "</span></div>";
		endif;
		?>
	<?php
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

 function available_post_types_search($output = null){
	 $searchable_post_types = array('topic','annoucement','profile','site-update','news-article','story', 'map-layer');

	 if($output == "object"):
		 $post_types = get_post_types(array(
		 	'public' => true,
		 	'_builtin' => false
		 ), 'object');

		 foreach ($post_types as $post_type) {
			 if(!in_array($post_type->name, $searchable_post_types)):
				 unset($post_types[$post_type->name]);
			 endif;
		 }
		 return $post_types;
	 endif;

 	 return $searchable_post_types;
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
										 'post_type'      => $post_type,
										 'post_status'    => 'publish',
									   'orderby' 		    => 'modified');
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

 function get_top_level_category_english_name($cat_id) {
	 global $wpdb;
	 while ($cat_id) {
			 $cat = get_category($cat_id); // get the object for the catid
			 $cat_id = $cat->category_parent; // assign parent ID (if exists) to $catid
			 $cat_parent_id = $cat->cat_ID;
	 }

	 $cat_parent_name = $wpdb->get_var($wpdb->prepare(
							 "SELECT `name` FROM $wpdb->terms WHERE `term_id` =  %d", $cat_parent_id));
	 $cat_parent_name = apply_filters('translate_text', $cat_parent_name, 'en');
	 return $cat_parent_name;
}

 function odm_echo_extras($postID = "") {
 	 $postID = $postID ? $postID : get_the_ID();
	 $news_source_info = null;
	 if (function_exists('get_post_meta')) :
		 $get_author = get_post_meta($postID, 'author', true);
		 $get_localized_author = get_post_meta($postID, 'author_'.odm_language_manager()->get_current_language(), true);
	   if ($get_author != '' || $get_localized_author != ''):
	     $news_source_info = '<span class="lsf">&#xE041;</span> ';
	     if ($get_localized_author != ''):
	         $news_source_info .= $get_localized_author.'<br />';
	     else:
	         $news_source_info .= $get_author.'<br />';
	     endif;
	   endif;

		 $get_article_link = get_post_meta($postID, 'article_link', true);
		 $get_localized_article_link = get_post_meta($postID, 'article_link_'.odm_language_manager()->get_current_language(), true);

		 if ($get_article_link != '' || $get_localized_article_link != ''):
			 if ($get_localized_author != ''):
					 $source = $get_localized_article_link;
			 else:
					 $source = $get_article_link;
			 endif;
		 endif;

		 if (isset($source) && $source != '') {
					if (false === strpos($source, '://')) {
							$news_source_info .= '<a href="http://'.$source.'" target="_blank">http://'.$source.'</a>';
					}  else {
							$news_source_info .= '<a href="'.$source.'" target="_blank">'.$source.'</a>';
					}
		 }

   endif;

   if (isset($news_source_info) && $news_source_info != ''):
     echo '<p>'.$news_source_info.'</p>';
   endif;

 }

 function odm_echo_solr_meta($solr_search_result){

	 ?>

	 <div class="data_meta_wrapper sixteen columns">
	 <!-- Language -->
	 <?php if (!empty($solr_search_result->odm_language)): ?>
		 <div class="data_languages data_meta">
			 <span>
				 <?php
				 foreach ($solr_search_result->odm_language as $lang):
					 $path_to_flag = odm_language_manager()->get_path_to_flag_image($lang);
					 if (!empty($path_to_flag)): ?>
					 <img class="lang_flag" alt="<?php echo $lang ?>" src="<?php echo $path_to_flag; ?>"></img>
				 <?php
					 endif;
				 endforeach; ?>
			 </span>
		 </div>
	 <?php endif; ?>
	 <!-- Country -->
	 <?php if (odm_country_manager()->get_current_country() == "mekong" && !empty($solr_search_result->odm_spatial_range)): ?>
		 <div class="country_indicator data_meta">
			 <i class="fa fa-globe"></i>
			 <span>
				 <?php
					 $countries = (array) $solr_search_result->odm_spatial_range;
					 foreach ($countries as $country_code):
						 $country_name = odm_country_manager()->get_country_name_by_country_code($country_code);
						 if (!empty($country_name)):
							 _e($country_name, 'odm');
							 if ($country_code !== end($countries)):
								 echo ', ';
							 endif;
						 endif;
					 endforeach; ?>
			 </span>
		 </div>
	 <?php
		 endif;
		 if (!empty($solr_search_result->categories)): ?>
			 <i class="fa fa-folder-o"></i>
			 <span>
				 <?php
					 $categories = (array) $solr_search_result->categories;
					 foreach ($categories as $category):
						 _e($category, 'odm') ;
						 if ($category !== end($categories)):
							 echo ", ";
						 endif;
					 endforeach;?>
			 </span>
	 <?php
 		endif; ?>
		</div>

	<?php
 }

 ?>
