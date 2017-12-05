<?php
	$post = isset($params["post"]) ? $params["post"] : null;
	$show_meta = isset($params["show_meta"]) ? $params["show_meta"] : true;
	$meta_fields = isset($params["meta_fields"]) ? $params["meta_fields"] : array("date", "tags");
	$max_num_topics = isset($params["max_num_topics"]) ? $params["max_num_topics"] : null;
	$max_num_tags = isset($params["max_num_tags"]) ? $params["max_num_tags"] : null;
	$show_solr_meta = isset($params["show_solr_meta"]) ? $params["show_solr_meta"] : false;
	$highlight_words_query = isset($params["highlight_words_query"]) ? $params["highlight_words_query"] : null;
	$solr_search_result = isset($params["solr_search_result"]) ? $params["solr_search_result"] : null;
	$show_thumbnail = isset($params["show_thumbnail"]) ? $params["show_thumbnail"] : true;
	$show_excerpt = isset($params["show_excerpt"]) ? $params["show_excerpt"] : false;
	$show_source_meta = isset($params["show_source_meta"]) ? $params["show_source_meta"] : false;
	$show_post_type = isset($params["show_post_type"]) ? $params["show_post_type"] : false;
	$header_tag = isset($params["header_tag"]) ? $params["header_tag"] : false;
	$order = isset($params["order"]) ? $params["order"] : "metadata_created";
?>

<div class="sixteen columns">
	<div class="post-list-item single_result_container">

		<section class="content item-content section-content">
			<?php
			if ($show_thumbnail):
				$thumb_src = odm_get_thumbnail($post->ID, false, 'medium');
				if (isset($thumb_src)):
					echo $thumb_src;
				else:
					echo_documents_cover($post->ID);
				endif;
			endif;
			?>
			<?php if ($show_excerpt || $show_source_meta): ?>
					<?php if ($show_excerpt): ?>
						<div class="post-excerpt">

							<?php
								if ($show_meta):
									echo_post_meta($post,array("date", "tags"),$order,$max_num_topics,$max_num_tags);
								endif; ?>

							<?php
								$excerpt = odm_excerpt($post, 600);
								if (isset($highlight_words_query) && function_exists('wp_odm_solr_highlight_search_words')):
									$excerpt = wp_odm_solr_highlight_search_words($highlight_words_query,$excerpt);
								endif;
								echo $excerpt; ?>

								</br>

								<a class="button" href="<?php echo get_permalink($post->ID); ?>"><?php _e('Read more','odm'); ?></a>
						</div>
						<?php
					endif;

					if ($show_source_meta):
						odm_echo_extras();
					endif;

					echo_downloaded_documents();

				endif;
					?>
		</section>

	</div>
</div>
