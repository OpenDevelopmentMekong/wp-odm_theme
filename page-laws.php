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
					function get_law_datasets_sorted_by_document_type(){
						$laws_json=do_shortcode('[wpckan_query_datasets query="*:*" type="laws_record" include_fields_extra="odm_document_type,odm_promulgation_date,odm_laws_version_date" format="json"]');
						$laws=json_decode($laws_json,true);
						// sort by document type
						uasort($laws["wpckan_dataset_list"], 'compare_by_dataset_list');
						return $laws;
					}
				 ?>
				 <?php
				 function array_push_assoc($array, $key, $value){
						$array[$key] = $value;
						return $array;
						}
				  ?>

				<?php
				$laws=get_law_datasets_sorted_by_document_type();
				?>

				<?php

				?>
				<ul>
					<?php
						$laws_sorted = array();
					?>
					<?php foreach ($laws['wpckan_dataset_list'] as $key => $law) { ?>
							<?php
							if ($law['wpckan_dataset_extras']['wpkan_dataset_extras-odm_document_type'] == "anukretsub-decree") {$laws_sorted["anukretsub-decree"]=array_push_assoc($laws_sorted["anukretsub-decree"], $key, $law);}
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
