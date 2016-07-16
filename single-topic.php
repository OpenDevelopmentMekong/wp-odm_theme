<?php get_header(); ?>

<?php if (have_posts()) : the_post(); ?>

  <article id="content" class="single-post">

      <section class="container">
        <header class="row">
          <div class="twelve columns">
            <h1><?php the_title(); ?></h1>
            <?php echo_post_meta(get_post()); ?>
          </div>
          <div class="four columns">
            <div class="widget share-widget">
              <?php odm_get_template('social-share',array(),true); ?>
            </div>
          </div>
        </header>
      </section>

      <section class="container">
    		<div class="row">
    			<div class="eleven columns">
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
    </section>

  </article>

<?php endif; ?>

<?php get_footer(); ?>
