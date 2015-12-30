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
				 $law_records_json = do_shortcode('[wpckan_query_datasets query="*:*" type="laws_record" include_fields_extra="odm_document_type,odm_promulgation_date,odm_laws_version_date" format="json"]');
				//  $laws=json_decode($law_records_json);
				var_dump($laws_record_json);

				 ?>

			</div>
		</div>
	</section>
<?php endif; ?>

<?php get_footer(); ?>
