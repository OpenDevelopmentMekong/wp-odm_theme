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

						foreach ($laws_sorted as $key => $law){?>
							<li><?php echo $key?>
									<ul>
										<?php foreach ($law as $title => $law_record) {?>
											<li>
												<a href="<?php echo $law_record['wpckan_dataset_title_url'];?>"><?php echo $title;?></a>
											</li>
										<?php } ?>
									</ul>
				<?php } ?>
				</ul>
				<!-- debug -->
				<?php d($laws_sorted);?>
			</div>
		</div>

	</section>
<?php endif; ?>

<?php get_footer(); ?>
