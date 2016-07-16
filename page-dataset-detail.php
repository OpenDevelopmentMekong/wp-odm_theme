<?php
/*
 * Template Name: WPCKAN Dataset detail
 */
?>
<?php get_header(); ?>

<?php if(have_posts()) : the_post(); ?>
	<section id="content" class="container single-post">
		<div class="row">
			<div class="eleven columns">
				<?php the_content(); ?>
				<?php
					if (isset($_GET['id'])):
						echo do_shortcode('[wpckan_dataset_detail id="' . $_GET['id'] . '"]');
					else:
						echo "<p>" . _e('Please provide an id as parameter', 'wpckan') . "</p>";
					endif;
				?>
			</div>

			<div class="four columns offset-by-one">
				<aside id="sidebar">
					<ul class="widgets">
						<?php dynamic_sidebar('wpckan-dataset-detail-sidebar'); ?>
					</ul>
				</aside>
			</div>
		</div>
	</section>
<?php endif; ?>

<script type="text/javascript">
  jQuery(document).ready(function($) {
    $('.download').prepend($('<i class="fa fa-download"></i> '));
  })
</script>

<?php get_footer(); ?>
