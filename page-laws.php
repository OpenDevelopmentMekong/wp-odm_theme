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
				<ul>

					<?php foreach ($laws['wpckan_dataset_list'] as $key => $law) { ?>
							<?php
							if ($law['wpckan_dataset_extras']['wpkan_dataset_extras-odm_document_type'] == "anukretsub-decree") {$laws_sorted["anukretsub-decree"]=array_push_assoc($laws_sorted["anukretsub-decree"], $key, $law);}
							elseif ($law['wpckan_dataset_extras']['wpkan_dataset_extras-odm_document_type'] == "chbablawkram") {$laws_sorted["chbablawkram"]=array_push_assoc($laws_sorted["chbablawkram"], $key, $law);}
							elseif ($law['wpckan_dataset_extras']['wpkan_dataset_extras-odm_document_type'] == "constitution-of-cambodia") {$laws_sorted["constitution-of-cambodia"]=array_push_assoc($laws_sorted["constitution-of-cambodia"], $key, $law);}
							elseif ($law['wpckan_dataset_extras']['wpkan_dataset_extras-odm_document_type'] == "international-treatiesagreements") {$laws_sorted["international-treatiesagreements"]=array_push_assoc($laws_sorted["international-treatiesagreements"], $key, $law);}
							elseif ($law['wpckan_dataset_extras']['wpkan_dataset_extras-odm_document_type'] == "kech-sonyacontractagreement") {$laws_sorted["kech-sonyacontractagreement"]=array_push_assoc($laws_sorted["kech-sonyacontractagreement"], $key, $law);}
							elseif ($law['wpckan_dataset_extras']['wpkan_dataset_extras-odm_document_type'] == "kolkar-nenomguidelines") {$laws_sorted["kolkar-nenomguidelines"]=array_push_assoc($laws_sorted["kolkar-nenomguidelines"], $key, $law);}
							elseif ($law['wpckan_dataset_extras']['wpkan_dataset_extras-odm_document_type'] == "kolnyobaypolicy") {$laws_sorted["kolnyobaypolicy"]=array_push_assoc($laws_sorted["kolnyobaypolicy"], $key, $law);}
							elseif ($law['wpckan_dataset_extras']['wpkan_dataset_extras-odm_document_type'] == "likhetletter") {$laws_sorted["likhetletter"]=array_push_assoc($laws_sorted["likhetletter"], $key, $law);}
							elseif ($law['wpckan_dataset_extras']['wpkan_dataset_extras-odm_document_type'] == "prakasjoint-prakasproclamation") {$laws_sorted["prakasjoint-prakasproclamation"]=array_push_assoc($laws_sorted["prakasjoint-prakasproclamation"], $key, $law);}
							elseif ($law['wpckan_dataset_extras']['wpkan_dataset_extras-odm_document_type'] == "preah-reach-kramroyal-kram") {$laws_sorted["preah-reach-kramroyal-kram"]=array_push_assoc($laws_sorted["preah-reach-kramroyal-kram"], $key, $law);}
							elseif ($law['wpckan_dataset_extras']['wpkan_dataset_extras-odm_document_type'] == "sarachorcircular") {$laws_sorted["sarachorcircular"]=array_push_assoc($laws_sorted["sarachorcircular"], $key, $law);}
							elseif ($law['wpckan_dataset_extras']['wpkan_dataset_extras-odm_document_type'] == "sechkdei-chhun-damneoungnoticeannouncement") {$laws_sorted["sechkdei-chhun-damneoungnoticeannouncement"]=array_push_assoc($laws_sorted["sechkdei-chhun-damneoungnoticeannouncement"], $key, $law);}
							elseif ($law['wpckan_dataset_extras']['wpkan_dataset_extras-odm_document_type'] == "sechkdei-nenuminstruction") {$laws_sorted["sechkdei-nenuminstruction"]=array_push_assoc($laws_sorted["sechkdei-nenuminstruction"], $key, $law);}
							elseif ($law['wpckan_dataset_extras']['wpkan_dataset_extras-odm_document_type'] == "sechkdei-preang-chbabdraft-laws-amp-regulations") {$laws_sorted["sechkdei-preang-chbabdraft-laws-amp-regulations"]=array_push_assoc($laws_sorted["sechkdei-preang-chbabdraft-laws-amp-regulations"], $key, $law);}
							elseif ($law['wpckan_dataset_extras']['wpkan_dataset_extras-odm_document_type'] == "sechkdei-samrechdecision") {$laws_sorted["sechkdei-samrechdecision"]=array_push_assoc($laws_sorted["sechkdei-samrechdecision"], $key, $law);}
							elseif {$laws_sorted["other"]=array_push_assoc($laws_sorted["other"], $key, $law);}

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
