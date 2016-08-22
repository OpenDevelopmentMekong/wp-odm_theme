<?php
	$post = isset($params["post"]) ? $params["post"] : null;
	$show_meta = isset($params["show_meta"]) ? $params["show_meta"] : true;
	$show_thumbnail = isset($params["show_thumbnail"]) ? $params["show_thumbnail"] : true;
	$show_excerpt = isset($params["show_excerpt"]) ? $params["show_excerpt"] : false;
	$show_source_meta = isset($params["show_source_meta"]) ? $params["show_source_meta"] : false;
	$show_post_type = isset($params["show_post_type"]) ? $params["show_post_type"] : false;
	$show_summary_translated_by_odc_team = isset($params["show_summary_translated_by_odc_team"]) ? $params["show_summary_translated_by_odc_team"] : false;
	$header_tag = isset($params["header_tag"]) ? $params["header_tag"] : false;
?>

<div class="sixteen columns">
	<div class="post-list-item">
		<?php if ($header_tag): ?>
			<h3>
				<a class="item-title" href="<?php echo get_permalink($post->ID); ?>" title="<?php echo $post->post_title; ?>">
					<?php echo $post->post_title; ?>
				</a>
			</h3>
		<?php else: ?>
			<p>
				<?php
					if ($show_post_type):
						$post_type_name = get_post_type($post->ID);
						$post_type = get_post_type_object($post_type_name);
						?>

						<a class="item-post-type" href="<?php echo $post_type->rewrite['slug'] ?>">
							<?php echo $post_type->labels->name ?>
						</a>
					<?php
					endif; ?>

					<a class="item-title" href="<?php echo get_permalink($post->ID); ?>" title="<?php echo $post->post_title; ?>">
						<?php echo $post->post_title; ?>
					</a>
			</p>
		<?php endif; ?>

		<?php if ($show_meta): ?>
			<?php echo_post_meta($post,array('date','sources')); ?>
		<?php endif; ?>

		<?php if ($show_summary_translated_by_odc_team): ?>
			<?php echo_post_translated_by_od_team(get_the_ID());?>
		<?php endif; ?>

		<section class="content section-content">
			<?php
			if ($show_thumbnail):
				$thumb_src = odm_get_thumbnail($post->ID,false);
				if (isset($thumb_src)):
					echo $thumb_src;
				else:
					echo_documents_cover($post->ID);
				endif;
			endif;
			?>
			<?php if ($show_excerpt || $show_source_meta): ?>
				<div class="item-content">
						<?php if ($show_excerpt): ?>
							<div class="post-excerpt">
								<?php echo odm_excerpt($post); ?>
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
	</div>
</div>
