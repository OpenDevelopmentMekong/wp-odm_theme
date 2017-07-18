<?php get_header(); ?>

<?php if (have_posts()) : the_post();
	$options = get_option('odm_options');
	$date_to_show = isset($options['single_page_date']) ? $options['single_page_date'] : "metadata_created";
	?>

  <article id="content" class="single-post story-content">

		<section class="container section-title main-title">
			<div class="row">

			<?php
				if (has_post_thumbnail() ): ?>
				<div class="sixteen columns post-title story-header">
					<?php $image = wp_get_attachment_image_src( get_post_thumbnail_id(), 'single-post-thumbnail' ); ?>
	        <header style="min-height:400px;background: linear-gradient( rgba(0, 0, 0, 0.5), rgba(0, 0, 0, 0.5) ), url('<?php echo $image[0]; ?>')">
						<div  class="container">
							<div class="row">
								<div class="eight columns offset-by-four">
									<div class="post-title">
										<h1><?php the_title(); ?></h1>
									</div>
			          </div>
							</div>
							<div class="row">
								<div class="ten columns offset-by-three">
									<p><?php the_excerpt(); ?></p>
			          </div>
							</div>
							<div class="row">
								<div class="six columns offset-by-five">
									<p><?php echo_post_meta(get_post(),array('date','categories','tags'),$date_to_show); ?></p>
			          </div>
							</div>
							<!-- <div class="row">
								<div class="sixteen columns">
									<div class="widget share-widget center post-meta">
										<?php odm_get_template('social-share',array(),true); ?>
									</div>
								</div>
							</div> -->
						</div>
					</header>
				</div>
			<?php
			else: ?>
	      <div class="twelve columns post-title">
	        <h1><?php the_title(); ?></h1>
	        <?php echo_post_meta(get_post()); ?>
	      </div>
	      <div class="four columns">
	        <div class="widget share-widget">
	          <?php odm_get_template('social-share',array(),true); ?>
	        </div>
	      </div>
			</div>
		</section>

				<?php
					endif; ?>

      <section class="container">
    		<div class="row">
    			<div class="sixteen columns">
            <?php dynamic_sidebar('story-top'); ?>
          </div>
        </div>
      </section>

			<?php
			$middle_content = get_post_meta(get_the_ID(), '_full_width_middle_content', true);
			if (odm_language_manager()->get_current_language() !== 'en') {
    		$middle_content = get_post_meta(get_the_ID(), '_full_width_middle_content_localization', true);
  		}
			if($middle_content): ?>
        <div style="width: 100%;">
          <?php echo "<div class='iframe-visualitation'>".$middle_content."</div>"; ?>
        </div>
  		<?php endif; ?>

      <section class="container">
    		<div class="row">
    			<div class="sixteen columns">
            <?php the_content(); ?>
            <?php odm_echo_extras(); ?>
          </section>
        </div>
      </div>
    </section>

    <section class="container">
      <div class="row">
        <div class="sixteen columns">
          <?php dynamic_sidebar('story-bottom'); ?>
        </div>
      </div>
    </section>

  </article>

<?php endif; ?>

<?php get_footer(); ?>
