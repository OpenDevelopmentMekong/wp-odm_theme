<?php get_header(); ?>

<?php if (have_posts()) : the_post(); ?>

  <article id="content" class="single-post">

    <section class="container">
      <header class="row">
        <div class="twelve columns post-title">
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

    <div class="container">
      <div class="row">
        <div class="eleven columns">
          <section class="content section-content">
            <?php
              if (jeo_has_marker_location()): ?>
              <section id="featured-media" class="row">
                <div style="height:350px;">
                  <?php jeo_map(); ?>
                </div>
              </section>
            <?php endif; ?>

            <?php
              if (function_exists('qtranxf_getLanguage')):
                if ((odm_language_manager()->get_current_language() == 'en') && (has_term('english-translated', 'language'))): ?>
                  <p class="translated-by-team"><strong><?php _e('Summary translated by ODC Team');?></strong></p>
              <?php endif; ?>
              <?php if (isset($local_language) && (odm_language_manager()->get_current_language() == $local_language) && (has_term('khmer-translated', 'language'))):?>
                  <p class="translated-by-team"><strong><?php _e('Summary translated by ODC Team');?></strong></p>
              <?php endif; ?>
            <?php endif; ?>
            <?php the_content(); ?>
            <?php odm_echo_extras(); ?>
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

        <div class="four columns offset-by-one">
          <aside id="sidebar">
            <ul class="widgets">
              <?php dynamic_sidebar('post'); ?>
            </ul>
          </aside>
        </div>

      </div>
    </div>

  </article>

<?php endif; ?>

<?php get_footer(); ?>
