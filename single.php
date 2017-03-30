<?php get_header(); ?>

<?php if (have_posts()) : the_post(); ?>

  <article id="content" class="single-post">

    <section class="container">
        <div class="eleven columns post-title">
          <header class="row">
          <h1><?php the_title(); ?></h1>
          <?php echo_post_meta(get_post()); ?>

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
							<?php
								$thumb_src = odm_get_thumbnail(get_the_ID(),false);
								if (isset($thumb_src)):
									echo $thumb_src;
								else:
									echo_documents_cover();
								endif;
							?>
							<div class="item-content">
	            	<?php the_content(); ?>
		            <?php echo_downloaded_documents(); ?>
		            <?php odm_echo_extras(); ?>
							</div>
						</section>
            <?php
              wp_link_pages(array(
                      'before' => '<div class="page-links"><span class="page-links-title">'.__('Pages:', 'odi').'</span>',
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
