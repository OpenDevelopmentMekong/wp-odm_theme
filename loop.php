<?php
  $options = get_option('odm_options');
  $date_to_show = isset($options['single_page_date']) ? $options['single_page_date'] : "metadata_created";
	$template = (get_post_type() == "post")? "blog-layout" : "list-single";
?>
<?php if( have_posts() ): ?>
  <section id="blog" class="container">
    <div id="post-content" class="row">
      <div class="sixteen columns">
        <?php
  				$index = 1;
  				while (have_posts()) : the_post();
  					if (should_open_row($template."-2-cols",$index)): ?>
  					<div class="row">
		  			<?php endif;
		  				odm_get_template('post-'.$template.'-2-cols',array(
		  					"post" => get_post(),
		  					"show_meta" => true,
		  					"meta_fields" => array("date","categories","tags","sources"),
		  					"show_source_meta" => true,
		  					"show_thumbnail" => true,
		  					"show_excerpt" => true,
		  					"show_summary_translated_by_odc_team" => true,
		  					"header_tag" => true,
		  					"order" => $date_to_show,
		  					"index" => $index
		  			),true);
		  			if (should_close_row("blog-layout-2-cols",$index)): ?>
  				</div>
  			<?php endif;
  			$index++;
  			endwhile; ?>
      </div>
    </div>
  </section>
<?php endif; ?>

  <section class="container">
  	<div class="row">
  		<div class="sixteen columns">
  			<?php odm_get_template('pagination',array(),true); ?>
  		</div>
  	</div>
  </section>

<?php /*if (have_posts()) : ?>
	<section class="posts-section row">
		<div class="container">
			<div class="eleven columns">
					<?php while (have_posts()) : the_post(); ?>
						<?php odm_get_template('post-list-single-1-cols',array(
	  					"post" => get_post(),
	  					"show_meta" => true,
							"show_excerpt" => true,
							"show_author_and_url_source" => true,
							"show_summary_translated_by_odc_team" => true
	  			),true);
					?>
					<?php endwhile; ?>
			</div>

			<div class="four columns offset-by-one">
				<aside id="sidebar">
					<ul class="widgets">
						<li class="widget share-widget">
							<?php odm_get_template('social-share',array(),true); ?>
						</li>

						<?php dynamic_sidebar(); ?>
						<li id="odm_taxonomy_widget" class="widget widget_odm_taxonomy_widget">
							<?php list_category_by_post_type(); ?>
						</li>
					</ul>
				</aside>
			</div>
		</div>
	</section>
<?php endif; */ ?>
