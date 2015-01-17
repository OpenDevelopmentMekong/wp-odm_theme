<?php get_header(); ?>

<?php if(have_posts()) : the_post(); ?>

	<article id="content" class="single-post">
		<header class="single-post-header" class="clearfix">
			<div class="container">
				<div class="eight columns">
					<?php the_category(); ?>
					<h1><?php the_title(); ?></h1>
				</div>
			</div>
		</header>
		<section id="featured-media" class="row">
			<div class="container">
				<div class="twelve columns">
					<div style="height:500px;">
						<?php
						if(jeo_has_marker_location()) {
							jeo_map();
						}
						?>
					</div>
				</div>
			</div>
		</section>
		<section class="content">
			<div class="container">
				<div class="eight columns">
					<?php the_content(); ?>
					<?php
					wp_link_pages( array(
						'before'      => '<div class="page-links"><span class="page-links-title">' . __( 'Pages:', 'jeo' ) . '</span>',
						'after'       => '</div>',
						'link_before' => '<span>',
						'link_after'  => '</span>',
					) );
					?>
					<?php comments_template(); ?>
				</div>
				<div class="three columns offset-by-one">
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