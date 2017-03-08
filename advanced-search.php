<?php if (have_posts()) : ?>

	<section class="container">
		<div class="row">

			<div class="sixteen columns">

				<h2>CKAN results</h2>
 				<?php
 					$resultset = Odm_Solr_CKAN_Manager()->query($s);
 				?>

 				<h2><?php echo count($resultset); ?> results found</h2>

 				<?php
 					foreach ($resultset as $document) {

 						?>

 						<div id="cse_results">
 							<div class="cse_result">
 								<h3><a href="<?php echo wpckan_get_link_to_dataset($document->id) ?>"><?php echo $document->title ?></a></h3>
 								<p><?php echo $document->notes ?></p>
 							</div>
 						</div>

 						<?php
 					}
 				 ?>

				<h2>WP results</h2>
				<?php
					$resultset = Odm_Solr_WP_Manager()->query($s);
				?>

				<h2><?php echo count($resultset); ?> results found</h2>

				<?php
					foreach ($resultset as $document) {

						?>

						<div id="cse_results">
							<div class="cse_result">
								<h3><a href="<?php echo $document->permalink ?>"><?php echo $document->title ?></a></h3>
								<p><?php echo strip_tags(substr($document->content,0,400)) ?></p>
							</div>
						</div>

						<?php
					}
				 ?>

			</div>
		</div>
	</section>

<?php endif; ?>
