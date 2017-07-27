<?php get_header(); ?>

<?php if (have_posts()) : the_post();
	$options = get_option('odm_options');
	$date_to_show = isset($options['single_page_date']) ? $options['single_page_date'] : "metadata_created";
	?>

  <article id="content" class="single-post">

    <section class="container section-title main-title">
        <div class="eleven columns post-title">
          <header class="row">
						<?php odm_title($post,array('date','categories','tags'),$date_to_show); ?>        
					</header>
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

								<div class="item-content">
									<?php
									$thumb_src = odm_get_thumbnail(get_the_ID(),false);
									if (isset($thumb_src)):
										echo $thumb_src;
									else:
										echo_documents_cover();
									endif;

									if(trim(strip_tags(get_the_content()))):
										the_content();
									endif;

									odm_echo_extras();

									echo_downloaded_documents();

									?>
								</div>
							</section>
            <?php
              wp_link_pages(array(
                      'before' => '<div class="page-links"><span class="page-links-title">'.__('Pages:', 'odm').'</span>',
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
    </section>
  </article>

<?php endif; ?>

<?php get_footer(); ?>
