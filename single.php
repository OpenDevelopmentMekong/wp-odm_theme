<?php get_header(); ?>

<?php if (have_posts()) : the_post(); ?>

  <article id="content" class="single-post">
      <div class="container">
        <div class="eight columns">
          <header class="single-post-header" class="clearfix">
            <h1><?php the_title(); ?></h1>
            <?php show_date_and_source_of_the_post(get_post()); ?>

            <div class="categories">
              <span class="lsf">&#9776;</span> <?php echo __('Filed under:', 'jeo'); ?> <?php the_category(); ?>
            </div>

            <div class="post-tags">
                <span class="lsf">&#xE128;</span> <?php echo __('Tags:', 'opendev'); ?> <?php the_tags('', ''); ?>
            </div>

          </header>
          <section class="content section-content">
              <?php
                if (jeo_has_marker_location()):
                    ?>
                <section id="featured-media" class="row">
                  <div style="height:350px;">
                    <?php jeo_map(); ?>
                  </div>
                </section>
              <?php endif; ?>
              <?php
                  if (function_exists('qtranxf_getLanguage')):
                    if ((qtranxf_getLanguage() == 'en') && (has_term('english-translated', 'language'))):
                  ?>
                  <p class="translated-by-odc"><strong><?php _e('Summary translated by ODC Team');?></strong></p>
                <?php
                  endif;
                    ?>
                  <?php if (isset($local_language) && (qtranxf_getLanguage() == $local_language) && (has_term('khmer-translated', 'language'))):?>
                      <p class="translated-by-odc"><strong><?php _e('Summary translated by ODC Team');?></strong></p>
                  <?php
                    endif; ?>
              <?php
                endif; ?>
              <?php the_content(); ?>

              <?php
                  if (function_exists('get')) :
                      if (get('author') == '' && get('author'.opendev_language_manager()->get_current_language()) == ''):
                        echo '';
                      else:
                        $news_source_info = '<span class="lsf">&#xE041;</span> ';
                        if (get('author'.opendev_language_manager()->get_current_language()) != ''):
                            $news_source_info .= get('author'.opendev_language_manager()->get_current_language()).'<br />';
                        else:
                            $news_source_info .= get('author').'<br />';
                        endif;
                      endif;
                  endif;
               ?>
              <?php
                  if (function_exists('get')):
                    if (get('article_link') == '' && get('article_link'.opendev_language_manager()->get_current_language()) == ''):
                        echo '';
                    else:
                      if (get('article_link'.opendev_language_manager()->get_current_language()) != ''):
                        $source = get('article_link'.opendev_language_manager()->get_current_language());
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
                  ?>
              <?php
                wp_link_pages(array(
                        'before' => '<div class="page-links"><span class="page-links-title">'.__('Pages:', 'jeo').'</span>',
                        'after' => '</div>',
                        'link_before' => '<span>',
                        'link_after' => '</span>',
                ));
                ?>
          </section>
        </div>
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
              <?php dynamic_sidebar('post'); ?>
            </ul>
          </aside>
        </div>
      </div>

  </article>

<?php endif; ?>

<?php get_footer(); ?>
