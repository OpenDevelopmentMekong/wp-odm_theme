<?php
	$post = isset($params["post"]) ? $params["post"] : null;
	$show_meta = isset($params["show_meta"]) ? $params["show_meta"] : true;
	$meta_fields = isset($params["meta_fields"]) ? $params["meta_fields"] : array("date");
	$show_solr_meta = isset($params["show_solr_meta"]) ? $params["show_solr_meta"] : false;
	$solr_search_result = isset($params["solr_search_result"]) ? $params["solr_search_result"] : null;
	$show_thumbnail = isset($params["show_thumbnail"]) ? $params["show_thumbnail"] : true;
	$show_excerpt = isset($params["show_excerpt"]) ? $params["show_excerpt"] : false;
	$show_source_meta = isset($params["show_source_meta"]) ? $params["show_source_meta"] : false;
	$show_post_type = isset($params["show_post_type"]) ? $params["show_post_type"] : false;
	$show_summary_translated_by_odc_team = isset($params["show_summary_translated_by_odc_team"]) ? $params["show_summary_translated_by_odc_team"] : false;
	$header_tag = isset($params["header_tag"]) ? $params["header_tag"] : false;
	$show_more_link = isset($params["show_more_link"]) ? $params["show_more_link"] : false;
	$order = isset($params["order"]) ? $params["order"] : 'created';
?>
<div class="sixteen columns">
	<div class="post-list-item highlighted single_result_container">
		
		<section class="content section-content panel">
			<?php
			if ($show_thumbnail): ?>
				<a href="<?php echo get_permalink($post->ID); ?>">
				<?php
					$thumb_src = odm_get_thumbnail($post->ID, false, array( 300, 'auto'));
					if (isset($thumb_src)):
						echo $thumb_src;
					else:
						echo_documents_cover($post->ID);
					endif; ?>
				</a> 
			<?php 
			endif;
			?>
			<?php if ($show_excerpt || $show_source_meta): ?>
				<div>
						<?php if ($show_meta):
							echo_post_meta($post,$meta_fields,$order);
						endif; ?>
						<?php if ($show_excerpt): ?>
							<div class="post-excerpt">
								<a href="<?php echo get_permalink($post->ID); ?>">
									<?php echo odm_excerpt($post);
									if ($show_more_link):
										_e('Read more','odm'); ?>
								<?php
									endif; ?>
								</a>								
							</div>
							<?php if( echo_downloaded_documents()):
								echo_downloaded_documents();
							endif; ?>
						<?php endif; ?>

						<?php if ($show_source_meta): ?>
							<?php odm_echo_extras(); ?>
						<?php endif; ?>
				</div>
			<?php endif; ?>
		</section>

		<?php
			if ($show_solr_meta && isset($solr_search_result)):
				odm_echo_solr_meta($solr_search_result);
			endif; ?>
	</div>
</div>
