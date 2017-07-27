<?php get_header(); ?>

<!-- //cookie -->
<?php if(have_posts()) : the_post(); ?>

  <section class="container section-title main-title">
		<header class="row">
			<div class="sixteen columns">
				<?php odm_title($post,array()); ?> 
			</div>
		</header>
	</section>

	<section class="container">
		<div class="sixteen columns">
			<?php the_content(); ?>
			<?php
			wp_link_pages( array(
				'before'      => '<div class="page-links"><span class="page-links-title">' . __( 'Pages:', 'odm' ) . '</span>',
				'after'       => '</div>',
				'link_before' => '<span>',
				'link_after'  => '</span>',
			) );
			?>
		</div>
	</section>
  
<?php endif; ?>

<?php get_footer(); ?>
