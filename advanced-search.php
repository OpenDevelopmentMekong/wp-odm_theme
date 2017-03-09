
<?php if (have_posts()) : ?>

	<section class="container">
		<div class="row">

			<div class="sixteen columns">

				<div id="accordion">

					<?php

					$supported_ckan_types = array(
						'dataset' => 'Datasets',
						'library_record' => 'Library publications',
						'laws_record' => 'Laws',
						'agreement' => 'Agreements'
					);

					foreach( $supported_ckan_types as $key => $value):
						$resultset = Odm_Solr_CKAN_Manager()->query($s,$key);
					?>

						<h3><?php echo $value . " (" . count($resultset) . ")" ?></h3>
						<div>
						<?php
							foreach ($resultset as $document):

								?>

								<div id="solr_results">
									<div class="solr_result">
										<h4><a href="<?php echo wpckan_get_link_to_dataset($document->id) ?>"><?php echo $document->title ?></a></h4>
										<p><?php echo strip_tags(substr($document->notes,0,400)) ?></p>
										<p><?php echo "country: " . $document->extras_odm_spatial_range ?> <?php echo "language: " . $document->extras_odm_language ?> <?php echo "topics: " . $document->extras_taxonomy ?></p>
										<p></p>
										<p></p>
									</div>
								</div>

								<?php
							endforeach;
						 ?>
					 </div>

					<?php
 						endforeach;
 			 		?>

					<?php

					$supported_wp_types = array(
						'map-layer' => 'Maps',
						'news-article' => 'News articles',
						'topic' => 'Topics',
						'profiles' => 'Profiles',
						'story' => 'Story',
						'announcement' => 'Announcements',
						'site-update' => 'Site updates'
					);

					foreach( $supported_wp_types as $key => $value):
						$resultset = Odm_Solr_WP_Manager()->query($s,$key);
					?>

						<h3><?php echo $value . " (" . count($resultset) . ")" ?></h3>
						<div>
						<?php
							foreach ($resultset as $document):

								?>

								<div id="solr_results">
									<div class="solr_result">
										<h4><a href="<?php echo $document->permalink ?>"><?php echo $document->title ?></a></h4>
										<p><?php echo strip_tags(substr($document->content,0,400)) ?></p>
										<p><?php echo "language: " . $document->odm_language ?> <?php echo "topics: " . $document->categories ?></p>
									</div>
								</div>

								<?php
							endforeach;
						 ?>
					 </div>

					<?php
					endforeach;
					?>
				</div>
			</div>
		</div>
	</section>

	<script>
		jQuery( function() {
			jQuery( "#accordion" ).accordion({
				collapsible: true, active: false
			});
		} );
	</script>

<?php endif; ?>
