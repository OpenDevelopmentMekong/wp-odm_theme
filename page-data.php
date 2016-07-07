<?php
/*
Template Name: Data
*/
?>
<?php get_header(); ?>

<?php if (have_posts()) : the_post(); ?>
	<section id="content" class="single-post data-page">
		<header class="single-post-header">
			<div class="container">
				<div class="sixteen columns">
					<h1><span class="icon-archive"></span> <?php the_title(); ?></h1>
				</div>
			</div>
		</header>
		<div class="container">
			<div class="ten columns">
				<?php the_content(); ?>
				<?php dynamic_sidebar('data-main'); ?>
			</div>
			<div class="five columns offset-by-two">
				<aside id="sidebar">
					<ul class="widgets">
						<?php dynamic_sidebar('data-sidebar'); ?>
					</ul>
				</aside>
			</div>
		</div>
	</section>
<?php endif; ?>

<?php get_footer(); ?>
