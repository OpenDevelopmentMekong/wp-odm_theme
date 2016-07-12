<?php get_header(); ?>

<?php if (have_posts()) : the_post(); ?>

  <article id="content" class="single-post">

      <div class="container">
        <div class="eleven columns">
          <header class="single-post-header" class="clearfix">
            <h1><?php the_title(); ?></h1>
            <div class="categories">
                <span class="lsf">&#9776;</span> <?php echo __('Filed under:', 'jeo'); ?> <?php the_category(); ?>
            </div>
          </header>
          <?php if (jeo_has_marker_location()): ?>
            <section id="featured-media" class="row">
              <div class="container">
                <div class="sixteen columns">
                  <div style="height:400px;">
                    <?php jeo_map(); ?>
                  </div>
                </div>
              </div>
            </section>
          <?php endif; ?>
          <section class="content">
            <div class="post-content">
              <?php the_content(); ?>
            </div>
            <?php
            wp_link_pages(array(
              'before' => '<div class="page-links"><span class="page-links-title">'.__('Pages:', 'jeo').'</span>',
              'after' => '</div>',
              'link_before' => '<span>',
              'link_after' => '</span>',
            ));
            ?>
            <?php comments_template(); ?>
          </section>
        </div>
        <div class="four columns offset-by-one">
          <aside id="sidebar">
            <ul class="widgets">
              <li class="widget share-widget">
                <?php odm_get_template('social-share',array(),true); ?>
              </li>
              <li class="widget">
                <?php odm_summary(); ?>
              </li>
              <?php dynamic_sidebar('topic'); ?>
              <?php if (function_exists('get_group') && get_group('related_link') != '' && get_group('related_link') != null): ?>
                <li class="widget widget_odm_related_link_widget" style="clear:left">
                  <div>
                  <h2 class="widget-title">Related Links</h2>
                    <?php
                      $related_link = get_group('related_link');
                      echo '<ul>';
                      foreach ($related_link as $related):
                          echo '<li>';
                          if ($related['related_link_link'][1] != ''):
                            echo '<a title="Click to view." href="'.$related['related_link_link'][1].'">';
                          endif;
                          if ($related['related_link_label'][1] != ''):
                             echo $related['related_link_label'][1];
                          endif;
                          if ($related['related_link_link'][1] != ''):
                            echo '</a>';
                          endif;
                          echo '</li>';
                      endforeach;
                      echo '</ul>';
                  ?>
                  </div>
              </li>
            <?php endif; ?>
            </ul>
          </aside>
        </div>
      </div>
  </article>

<?php endif; ?>

<?php get_footer(); ?>
