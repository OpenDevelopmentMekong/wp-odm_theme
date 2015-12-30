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

					<?php
						$laws_sorted=get_law_datasets_sorted_by_document_type();
						foreach ($laws_sorted as $key => $law){?>
							<div class="document_type_header"><?php echo $key?></div>
								<table>
										<?php foreach ($law as $title => $law_record) {?>
											<tr>
												<td class="law_title">
													<a href="<?php echo $law_record['wpckan_dataset_title_url'];?>"><?php echo $title;?></a>
												<td class="law_status">
													<?php echo $law_record['wpckan_dataset_extras']['wpkan_dataset_extras-odm_laws_status'];?>
												</td>
												<td class="law_version">
													<?php echo $law_record['wpckan_dataset_extras']['wpkan_dataset_extras-odm_laws_version_date'];?>
												</td>
												<td class="law_download_en">
													<span class="law_download en">
														<?php foreach ($law_record['wpckan_resources_list'] as $key => $resource) {?>
															<?php if ($resource['wpckan_resource_language'] == 'en'){?>
																<a href="<?php echo $resource['wpckan_resource_name_link'];?>/download/<?php echo $title;?>">
																	<span class="icon-arrow-down"></span> &nbsp; EN	</span>
															<?php } ?>
														<?php } ?>
												</td>
												<td class="law_download_km">
													<span class="law_download km">
														<?php foreach ($law_record['wpckan_resources_list'] as $key => $resource) {?>
															<?php if ($resource['wpckan_resource_language'] == 'km'){?>
																<a href="<?php echo $resource['wpckan_resource_name_link'];?>/download/<?php echo $resource['wpckan_resource_name'];?>">
																	<span class="icon-arrow-down"></span> &nbsp; KM	</span>
															<?php } ?>
														<?php } ?>
												</td>
												<td class="law_download_th">
													<span class="law_download th">
														<?php foreach ($law_record['wpckan_resources_list'] as $key => $resource) {?>
															<?php if ($resource['wpckan_resource_language'] == 'th'){?>
																<a href="<?php echo $resource['wpckan_resource_name_link'];?>/download/<?php echo $resource['wpckan_resource_name'];?>">
																	<span class="icon-arrow-down"></span> &nbsp; TH	</span>
															<?php } ?>
														<?php } ?>
												</td>
											</tr>
											<?php// d($law_record);?>
										<?php } ?>
								</table>
				<?php } ?>

				<!-- debug -->
				<?php d($laws_sorted);?>
			</div>
		</div>

	</section>
<?php endif; ?>

<?php get_footer(); ?>
