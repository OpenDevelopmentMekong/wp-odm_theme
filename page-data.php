<?php
/*
Template Name: Data archive
*/
?>
<?php get_header(); ?>

<?php if(have_posts()) : the_post(); ?>
	<section id="content" class="single-post">
		<header class="single-post-header">
			<div class="container">
				<div class="twelve columns">
					<h1><?php the_title(); ?></h1>
				</div>
			</div>
		</header>
		<div class="container">
			<div class="eight columns">
				<?php echo get_option('setting_ckan_url'); ?>
				<form id="wpckan_search" method="GET">
					<input type="text" name="ckan_s" placeholder="<?php _e('Type and hit enter', 'opendev'); ?>" />
				</form>
				<?php
				// echo do_shortcode('[wpckan_query_datasets limit="10"]');
				$atts = array(
					'query' => 'a',
					'limit' => 2,
					'page' => 2
				);
				$datasets = wpckan_api_query_datasets($atts);
				// echo '<ul>';
				// foreach($datasets as $dataset) {
				// 	echo '<li>' . $dataset['title'] . '</li>';
				// }
				// echo '</ul>';
				?>
			</div>
			<div class="three columns offset-by-one">
				<aside id="sidebar">
					<ul class="widgets">
						<?php dynamic_sidebar('general'); ?>
					</ul>
				</aside>
			</div>
		</div>
	</section>
<?php endif; ?>

<?php get_footer(); ?>