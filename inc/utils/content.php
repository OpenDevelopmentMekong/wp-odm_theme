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
		}else{
			return;
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

		}
}

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
function show_date_and_source_of_the_post($post)
{
	?>

	<div class="date">
		 <span class="lsf">&#xE12b;</span>
			 <?php
			 if (function_exists('qtrans_getLanguage')) {
					 if (qtrans_getLanguage() == 'kh' || qtrans_getLanguage() == 'km') {
							 echo convert_date_to_kh_date(get_the_time('j.M.Y'),$post->ID);
					 } else {
							 echo get_the_time('j F Y',$post->ID);
					 }
			 } else {
					 echo get_the_time('j F Y',$post->ID);
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

 ?>
