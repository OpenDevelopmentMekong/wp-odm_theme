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
				$laws_json=do_shortcode('[wpckan_query_datasets query="*:*" type="laws_record" include_fields_extra="odm_document_type,odm_promulgation_date,odm_laws_version_date" format="json"]');
				$laws=json_decode($laws_json,true);
				?>


				<?php
					// <!-- Sorting for document type -->
					// $document_types=$laws[]
					function compare($a, $b) {
					    return strcmp($a['wpckan_dataset_extras']['wpkan_dataset_extras-odm_document_type'], $b['wpckan_dataset_extras']['wpkan_dataset_extras-odm_document_type']);
					}

					uasort($laws["wpckan_dataset_list"], 'compare');



				?>
				<ul>
					<?php foreach ($laws['wpckan_dataset_list'] as $key => $law) { ?>

						<!--  -->
						<li>
							<a href="<?php echo $law['wpckan_dataset_title_url'];?>"><?php echo $key;?></a>
						</li>
				<?php } ?>
				</ul>
				<!-- debug -->
				<?php d($laws);?>
			</div>
		</div>

	</section>
<?php endif; ?>

<?php get_footer(); ?>
