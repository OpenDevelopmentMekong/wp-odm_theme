<?php get_header(); ?>

<?php if (have_posts()) : the_post(); ?>
<?php
if (function_exists('qtranxf_getLanguage')) {
    if (qtranxf_getLanguage() != 'en') {
        $lang = '_'.qtranxf_getLanguage();
    } else {
        $lang = '';
    }
    //Get all languages that is available
    $languages = qtranxf_getSortedLanguages();
    $local_language = $languages[1];
    $local_lang = '_'.$languages[1];
} else {
    $lang = '';
}
?>
	<article id="content" class="single-post">
			<div class="container">
				<div class="eight columns">
		            <header class="single-post-header" class="clearfix">
    					<h1><?php the_title(); ?></h1>
    					 <?php show_date_and_source_of_the_post(); ?>

    					<div class="categories">
    						  <span class="lsf">&#9776;</span> <?php echo __('Filed under:', 'jeo'); ?> <?php the_category(); ?>
    					</div>
		            </header>

					<?php get_template_part('section', 'related-datasets'); ?>
            		<section class="content section-content">
    					<?php
                        if (jeo_has_marker_location()) {
                            ?>
    						<section id="featured-media" class="row">
    							<div style="height:350px;">
    								<?php jeo_map();
                            ?>
    							</div>
    						</section>
    						<?php

                        }
                        ?>
    					<?php
              if (function_exists('qtranxf_getLanguage')) {
                  if ((qtranxf_getLanguage() == 'en') && (has_term('english-translated', 'language'))) {
                      ?>
                      <p class="translated-by-odc"><strong><?php _e('Summary translated by ODC Team');
                      ?></strong></p>
                  <?php
                  }
                  ?>
                  <?php if ((qtranxf_getLanguage() == $local_language) && (has_term('khmer-translated', 'language'))) {
    ?>
                      <p class="translated-by-odc"><strong><?php _e('Summary translated by ODC Team');
    ?></strong></p>
                  <?php
}
                  ?>
              <?php
              } ?>
    					<?php the_content(); ?>

              <!-- News Source: author and link -->
              <?php
              if (function_exists('qtranxf_getLanguage')) {
                  if (qtranxf_getLanguage() != 'en') {
                      $lang = '_'.qtranxf_getLanguage();
                  } else {
                      $lang = '';
                  }
              }
              //Get author
              if (function_exists('get')) {
                  if (get('author') == '' && get('author'.$lang) == '') {
                      echo '';
                  } else {
                      $news_source_info = '<span class="lsf">&#xE041;</span> ';
                      if (get('author'.$lang) != '') {
                          $news_source_info .= get('author'.$lang).'<br />';
                      } else {
                          $news_source_info .= get('author').'<br />';
                      }
                  }
              }
             ?>
        <?php
              if (function_exists('get')) {
                  //Get url
                if (get('article_link') == '' && get('article_link'.$lang) == '') {
                    echo '';
                } else {
                    if (get('article_link'.$lang) != '') {
                        $source = get('article_link'.$lang);
                    } else {
                        $source = get('article_link');
                    }
                }

                  if (isset($source) && $source != '') {
                      if (substr($source, 0, 7) != 'http://') {
                          $news_source_info .= '<a href="http://'.$source.'" target="_blank">http://'.$source.'</a>';
                      } else {
                          $news_source_info .= '<a href="'.$source.'" target="_blank">'.$source.'</a>';
                      }
                  }
              }
              if (isset($news_source_info) && $news_source_info != '') {
                  echo '<p>'.$news_source_info.'</p>';
              }
              ?>
    					<div class="post-tags">
    						  <span class="lsf">&#xE128;</span> <?php echo __('Tags:', 'opendev'); ?> <?php the_tags('', ''); ?>
    					</div>
    					<?php
                        wp_link_pages(array(
                            'before' => '<div class="page-links"><span class="page-links-title">'.__('Pages:', 'jeo').'</span>',
                            'after' => '</div>',
                            'link_before' => '<span>',
                            'link_after' => '</span>',
                        ));
                        ?>

    					<?php //comments_template(); ?>
            		</section>
				</div> <!-- eight -->
				<div class="three columns offset-by-one">
            					<aside id="sidebar">
            						<ul class="widgets">
            							<li class="widget share-widget">
            								<div class="share clearfix">
            									<ul>
                                <li>
                  								<div class="fb-share-button" data-href="<?php echo get_permalink($post->ID)?>" data-send="false" data-layout="button" data-show-faces="false"></div>
                  							</li>
                  							<li>
                  								<div class="twitter-share-button"><a href="https://twitter.com/share" class="twitter-share-button" data-url="<?php the_permalink(); ?>" data-lang="en" data-count="vertical">Tweet</a></div>
                  							</li>
                  							<li>
                  								<div class="g-plusone" data-width="50" data-annotation="none" data-size="tall" data-href="<?php the_permalink(); ?>" data-count="false"></div>
                  							</li>
            									</ul>
            								</div>
            							</li>
                          <!--<li id="opendev_taxonomy_widget" class="widget widget_opendev_taxonomy_widget">
          									<?php // list_category_by_post_type(); ?>
          								</li>-->
            							<?php dynamic_sidebar('post'); ?>
            						</ul>
            					</aside>
				</div>    <!-- three -->
			</div><!-- container -->

	</article>

<?php endif; ?>

<?php get_footer(); ?>
