<?php get_header(); ?>

<!-- //cookie -->
<?php if(have_posts()) : the_post(); ?>
	<section id="content" class="single-post">
		<header class="single-post-header">
			<div class="container">
				<div class="sixteen columns">
					<h1><?php the_title(); ?></h1>
				</div>
			</div>
		</header>
		<div class="container">
			<div class="sixteen columns">
				<?php the_content(); ?>
				<?php
				wp_link_pages( array(
					'before'      => '<div class="page-links"><span class="page-links-title">' . __( 'Pages:', 'jeo' ) . '</span>',
					'after'       => '</div>',
					'link_before' => '<span>',
					'link_after'  => '</span>',
				) );
				?>
			</div>
		</div>
	</section>
<?php endif; ?>

<?php get_footer(); ?>
