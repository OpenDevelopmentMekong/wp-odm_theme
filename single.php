<?php get_header(); ?>

<?php if (have_posts()) : the_post(); ?>

  <article id="content" class="single-post">

    <section class="container">
      <header class="row">
        <div class="eleven columns post-title">
          <h1><?php the_title(); ?></h1>
          <?php echo_post_meta(get_post()); ?>
					<?php echo_post_translated_by_od_team(get_the_ID());?>

					<section class="content section-content">
            <?php
              if (jeo_has_marker_location()): ?>
              <section id="featured-media" class="row">
                <div style="height:350px;">
                  <?php jeo_map(); ?>
                </div>
              </section>
            <?php endif; ?>
						<section id="post-content" class="row">
            	<?php the_content(); ?>
	            <?php odm_echo_extras(); ?>
						</section>
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
        <div class="four columns">
          <div class="widget share-widget">
            <?php odm_get_template('social-share',array(),true); ?>
          </div>

					<div>
	          <aside id="sidebar">
	            <ul class="widgets">
	              <?php dynamic_sidebar('post'); ?>
	            </ul>
	          </aside>
	        </div>
        </div>
      </header>
    </section>
  </article>

<?php endif; ?>

<?php get_footer(); ?>
