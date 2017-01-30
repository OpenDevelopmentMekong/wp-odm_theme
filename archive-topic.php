<?php get_header();?>

<div class="section-title main-title">

	<section class="container">
		<header class="row">
			<div class="eight columns">
				<h1><?php _e('Topics','odm') ?></h1>
			</div>
      <div class="eight columns">
				<?php get_template_part('section', 'query-actions'); ?>
			</div>
		</header>
	</section>

	<section class="container">
		<div class="sixteen columns">
			<div class="panel more-filters-content row">
				<?php odm_archive_nav_filters(); ?>
			</div>
		</div>
	</section>

	<section class="container">

    <div class="row">

      <div class="sixteen columns">

				<h3 class="clearfix"><?php _e('Environment and land','odm'); ?></h3>

        <?php

				$taxonomy_categories_1 = array("Agriculture and fishing", "Disaster and emergency response", "Environment and natural resources", "Extractive Industries", "Land");

				$taxonomy_categories_2 = array("Economy and commerce", "Energy", "Industry", "Infrastructure", "Labor", "Science and technology");

				$taxonomy_categories_3 = array("Aid and development", "Government", "Law and judiciary", "Population and cencuses", "Social development", "Urban administration and development");

				 	while (have_posts()) : the_post();
						$post = get_post();
						$category = get_the_category($post->ID);
						$top_level_cat = get_top_level_category_english_name($category[0]->cat_ID);
						if (in_array($top_level_cat,$taxonomy_categories_1)):
							odm_get_template('post-grid-single-4-cols',array(
		  					"post" => $post,
		  					"show_meta" => true),true);
						endif;
					endwhile; ?>

				<?php rewind_posts(); ?>

				<h3 class="clearfix"><?php _e('Economy','odm'); ?></h3>
				<?php
					while (have_posts()) : the_post();
					$post = get_post();
					$category = get_the_category($post->ID);
					$top_level_cat = get_top_level_category_english_name($category[0]->cat_ID);
					if (in_array($top_level_cat,$taxonomy_categories_2)):
							odm_get_template('post-grid-single-4-cols',array(
		  					"post" => $post,
		  					"show_meta" => true),true);
						endif;
					endwhile; ?>

				<?php rewind_posts(); ?>

				<h3 class="clearfix"><?php _e('People','odm'); ?></h3>

				<?php
					while (have_posts()) : the_post();
						$post = get_post();
						$category = get_the_category($post->ID);
						$top_level_cat = get_top_level_category_english_name($category[0]->cat_ID);
						if (in_array($top_level_cat,$taxonomy_categories_3)):
							odm_get_template('post-grid-single-4-cols',array(
		  					"post" => $post,
		  					"show_meta" => true),true);
						endif;
					endwhile; ?>

      </div>

    </div>

	</section>

</div>


<?php get_footer(); ?>
