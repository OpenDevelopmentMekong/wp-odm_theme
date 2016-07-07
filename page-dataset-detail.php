<?php
/*
 * Template Name: WPCKAN Dataset detail
 */
?>
<?php get_header(); ?>

<?php if(have_posts()) : the_post(); ?>
	<section id="content" class="single-post">

		<div class="container">
			<div class="row">
				<div class="twelve columns">
					<?php the_content(); ?>
					<?php
						if (isset($_GET['id'])):
							echo do_shortcode('[wpckan_dataset_detail id="' . $_GET['id'] . '"]');
						else:
							echo "<p>" . _e('Please provide an id as parameter', 'wpckan') . "</p>";
						endif;
					?>
				</div>
			</div>

			<div class="row">
				<div class="twelve columns">
					<?php dynamic_sidebar('wpckan-dataset-detail-bottom'); ?>
				</div>
			</div>

		</div>
	</section>
<?php endif; ?>

<?php get_footer(); ?>
