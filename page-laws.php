<?php
// dbug
require 'lib/kint/Kint.class.php';
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
		<div class="container laws-container">
			<div class="twelve columns">
				<?php the_content(); ?>
				<?php
				wp_link_pages( array(
					'before'      => '<div class="page-links"><span class="page-links-title">' . __( 'Pages:', 'jeo' ) . '</span>',
					'after'       => '</div>',
					'link_before' => '<span>',
					'link_after'  => '</span>',
				) );
				?>


				<?php

				?>
				<ul>
					<?php
						$laws_sorted=get_law_datasets_sorted_by_document_type();

						foreach ($laws_sorted as $key => $law){
						// foreach ($laws['wpckan_dataset_list'] as $key => $law) {
							?>

						<!--  -->
						<li>
							<a href="<?php echo $law['wpckan_dataset_title_url'];?>"><?php echo $key;?></a>
						</li>
				<?php } ?>
				</ul>
				<!-- debug -->
				<?php d($laws_sorted);?>
				<?php d($laws);?>
			</div>
		</div>

	</section>
<?php endif; ?>

<?php get_footer(); ?>
